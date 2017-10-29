<?php

/**
 * Class Pay
 */
class Pay {
    protected $order_no;       //订单号
    protected $transaction_id;  //交易id  支付通道id
    protected $product_name;  //商品名
    protected $order_price;   //价格
    protected $order_tag;
    protected $return_url;    //返回url
    protected $notify_url;    //异步返回url

    protected $member_id;     //会员id
    protected $client_ip;

    protected  $error;

    function __construct($data=array()){
        $this->order_no=$data['order_no'];
        $this->product_name=$data['product_name'];
        $this->order_price=$data['order_price'];
        $this->order_tag=$data['order_tag'];
        $this->return_url=$data['return_url'];
        $this->notify_url=$data['notify_url'];

        $this->member_id=$data['member_id'];
        $this->client_ip= get_client_ip();
    }

    /*
     * 返回错误
     */
    function getError(){
        return $this->error;
    }

    /*
     *表单提交后处理函数
     */
    function doBeforPay($data=array()){
       //记录行为
        action_log('pay', 'pay', getUid(), getUid(),$this);
        $data['id']=$this->order_no;
        $data['product_name']=$this->product_name;
        $data['order_price']=$this->order_price;
        $data['order_tag']=$this->order_tag;
        $data['client_ip']=$this->client_ip;
        $data['member_id']=$this->member_id;

        $data['request_url']=$_SERVER['REQUEST_URI'];
        $data['create_time']=time();

        $PayOrder=D("payOrder");
        $PayOrder->create($data,1);
        if(!$PayOrder->add()){
            $this->error='订单创建失败';
            return false;
        }

    }


    /*
    *支付失败后的处理函数
    */
    function doFailAfterPay($data=array()){
        action_log('payFail', 'pay', getUid(), getUid(),$this);
    }

    /*
     *支付成功后的处理函数
     */
    function doSuccessAfterPay($data=array()){
        action_log('paySuccess', 'pay', getUid(), getUid(),$this);
        $PayOrder=D('PayOrder');
        $rs=$PayOrder->find($this->order_no);
        if($rs['order_price']!=$this->order_price){
            $this->error='支付失败';
            return false;
        }

        $data['id']=$this->order_no;
        $data['status']=1;
        $data['transaction_id']=$this->transaction_id;
        $data['return_request_url']=$_SERVER['REQUEST_URI'];
        $data['update_time']=time();

        if($PayOrder->save($data)){
            return true;
        }
        return false;

    }


    /*
     *****************************财付通支付***************************************
     */
    function tenPay($data=array()){
        if(!empty($_POST)){
            Vendor('Tenpay.RequestHandler#class');

            $this->product_name=get_encoding($this->product_name);
            /* 创建支付请求对象 */
            $reqHandler = new RequestHandler();
            $reqHandler->init();
            $key= C('TENPAY_KEY');
            $reqHandler->setKey($key);
            $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");
            $partner=C('TENPAY_PARTNER');
            $notify_url=C('TENPAY_NOTIFY_URL');
            $return_url=$this->return_url;//支付后返回地址
        //    echo $reqHandler.$key.$partner.$notify_url.$return_url;

            //----------------------------------------
            //设置支付参数
            //----------------------------------------

            $reqHandler->setParameter("partner", $partner);
            $reqHandler->setParameter("out_trade_no", $this->order_no);
            $reqHandler->setParameter("total_fee", $this->order_price*100);  //总金额
            $reqHandler->setParameter("return_url",  $return_url);
            $reqHandler->setParameter("notify_url", $notify_url);
            $reqHandler->setParameter("body",  $this->product_name);
            $reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
            //用户ip
            $reqHandler->setParameter("spbill_create_ip", $this->client_ip);//客户端IP
            $reqHandler->setParameter("fee_type", "1");               //币种
            $reqHandler->setParameter("subject", $this->product_name);          //商品名称，（中介交易时必填）

            //系统可选参数
            $reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
            $reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
            $reqHandler->setParameter("input_charset", "UTF-8");   	  //字符集
            $reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

            //业务可选参数
            $reqHandler->setParameter("attach", ""); 	  //附件数据，原样返回就可以了
            $reqHandler->setParameter("product_fee", "");        	  //商品费用
            $reqHandler->setParameter("transport_fee", "0");      	  //物流费用
            $reqHandler->setParameter("time_start",date("YmdHis"));  //订单生成时间
            $reqHandler->setParameter("time_expire", "");             //订单失效时间
            $reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
            $reqHandler->setParameter("goods_tag", $this->order_tag);               //商品标记
            $reqHandler->setParameter("trade_mode",1);              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
            $reqHandler->setParameter("transport_desc","");              //物流说明
            $reqHandler->setParameter("trans_type","1");              //交易类型
            $reqHandler->setParameter("agentid","");                  //平台ID
            $reqHandler->setParameter("agent_type","0");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
            $reqHandler->setParameter("seller_id",$partner);                //卖家的商户号

            $tenpayUrl=$reqHandler->getRequestURL();
            $debugInfo=$reqHandler->getDebugInfo();

            $this->doBeforPay();

            header("Location:$tenpayUrl");
        }
    }
       
    /*
     * 支付返回
     */
    function tenReturn(){
        unset($_GET[_URL_]);
        Vendor('Tenpay.ResponseHandler#class');
        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey(C('TENPAY_KEY'));
        //判断签名
        if($resHandler->isTenpaySign()) {
            //通知id
            $notify_id = $resHandler->getParameter("notify_id");
            //商户订单号
            $out_trade_no = $resHandler->getParameter("out_trade_no");
            //财付通订单号
            $transaction_id = $resHandler->getParameter("transaction_id");
            //金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");
            //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            $discount = $resHandler->getParameter("discount");
            //支付结果
            $trade_state = $resHandler->getParameter("trade_state");
            //交易模式,1即时到账
            $trade_mode = $resHandler->getParameter("trade_mode");

            if("1" == $trade_mode ) {
                if( "0" == $trade_state){
                    //------------------------------
                    //处理业务开始
                    //------------------------------
                    //注意交易单不要重复处理
                    //注意判断返回金额
                    $this->order_no=$out_trade_no;
                    $this->transaction_id=$transaction_id;
                    $this->order_price=$total_fee/100;
                    $this->doSuccessAfterPay();
                } else {
                    //当做不成功处理
                    $this->doFailAfterPay();
                }
            }elseif( "2" == $trade_mode  ){
                if( "0" == $trade_state){
                    //------------------------------
                    //处理业务开始
                    $this->doSuccessAfterPay();
                } else {
                    //当做不成功处理
                    $this->doFailAfterPay();
                }
            }
        } else {
            $this->doFailAfterPay();
        }
    }

    //财富通 异步通知
    function tenNotify(){
        $data=array();
        $data['input']=file_get_contents('php://input');  # 获取trafree推送的信息
        $data['get']=$_GET;
        $data['post']=$_POST;
        $data=json_encode($data);

        Vendor('Tenpay.ResponseHandler#class');
        Vendor('Tenpay.RequestHandler#class');
        Vendor('Tenpay.ClientResponseHandler#class');
        Vendor('Tenpay.TenpayHttpClient#class');

        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey(C('TENPAY_KEY'));

        //判断签名
        if($resHandler->isTenpaySign()) {
            //通知id
            $notify_id = $resHandler->getParameter("notify_id");

            //通过通知ID查询，确保通知来至财付通
            //创建查询请求
            $queryReq = new RequestHandler();
            $queryReq->init();
            $queryReq->setKey(C('TENPAY_KEY'));
            $queryReq->setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
            $queryReq->setParameter("partner", C('TENPAY_PARTNER'));
            $queryReq->setParameter("notify_id", $notify_id);

            //通信对象
            $httpClient = new TenpayHttpClient();
            $httpClient->setTimeOut(5);
            //设置请求内容
            $httpClient->setReqContent($queryReq->getRequestURL());
            //后台调用
            if($httpClient->call()){
                //设置结果参数
                $queryRes = new ClientResponseHandler();
                $queryRes->setContent($httpClient->getResContent());
                $queryRes->setKey(C('TENPAY_KEY'));
                if($resHandler->getParameter("trade_mode") == "1"){
                    //判断签名及结果（即时到帐）
                    //只有签名正确,retcode为0，trade_state为0才是支付成功
                    if($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $resHandler->getParameter("trade_state") == "0") {
                        log_result("即时到帐验签ID成功");
                        //取结果参数做业务处理
                        $out_trade_no = $resHandler->getParameter("out_trade_no");
                        //财付通订单号
                        $transaction_id = $resHandler->getParameter("transaction_id");
                        //金额,以分为单位
                        $total_fee = $resHandler->getParameter("total_fee");
                        //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
                        $discount = $resHandler->getParameter("discount");
                        //------------------------------
                        //处理业务开始
                        //------------------------------

                        $this->order_no=$out_trade_no;
                        $this->transaction_id=$transaction_id;
                        $this->order_price=$total_fee/100;

                        $this->doSuccessAfterPay();
                        echo "success";
                    } else {
                        //错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
                        //echo "验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes->                         getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
                          echo "fail";
                         $this->doFailAfterPay( $resHandler->getErrInfo());
                    }
                }
               // echo "<br>DebugInfo :" . $queryRes->getDebugInfo() . "<br>";
                //获取查询的debug信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题
                /*
                    echo "<br>------------------------------------------------------<br>";
                    echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
                    echo "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
                    echo "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
                    echo "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ;
                    echo "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
                    */
            }else{
                //通信失败
                echo "fail";
                $this->doFailAfterPay( $resHandler->getErrInfo());
                //后台调用通信失败,写日志，方便定位问题
                // echo "<br>call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
            }
        }else{
            echo "<br/>" . "认证签名失败" . "<br/>";
            $this->doFailAfterPay( $resHandler->getDebugInfo());
        }
    }

	 ///////////////////////////////////////////////支付宝//////////////////////////////////////////////
	 //在类初始化方法中，引入相关类库    
     public function _initialize() {

    }
		//入口
	 public function alipay(){
         vendor('Alipay.Corefunction');
         vendor('Alipay.Md5function');
         vendor('Alipay.Notify');
         vendor('Alipay.Submit');
         if(!empty($_POST)){
             //支付表单认证
            $this->pay_auth();

			$alipay_config['partner']		= C('ALIPAY_PARTNER');
			$alipay_config['key']			= C('ALIPAY_KEY');
			$alipay_config['sign_type']     = strtoupper('MD5');
			$alipay_config['input_charset'] = strtolower('utf-8');
			$alipay_config['cacert']        = getcwd().'\\cacert.pem';
			$alipay_config['transport']     = 'http';
			
			/**************************请求参数**************************/
			$payment_type = "1"; //支付类型 //必填，不能修改
			$notify_url = C('ALIPAY_NOTIFY_URL'); //服务器异步通知页面路径
			$return_url =  $return_url=U('/Member/Pay/returnurl','','','',true); //页面跳转同步通知页面路径
			$seller_email = C('seller_email');//卖家支付宝帐户必填			
			$out_trade_no = $_POST['order_no'];//商户订单号 通过支付页面的表单进行传递，注意要唯一！
			$subject = $_POST['product_name'];  //订单名称 //必填 通过支付页面的表单进行传递
			$total_fee = $_POST['order_price'];   //付款金额  //必填 通过支付页面的表单进行传递
			$body = $_POST['product_name'];  //订单描述 通过支付页面的表单进行传递
			$anti_phishing_key = "";//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
			$exter_invoke_ip = $_SERVER['REMOTE_ADDR']; //客户端的IP地址
			$show_url = "";//商品展示地址
			$goods_tag=$_POST['order_id_arr']; //商品标记
			$trade_mode=$_POST['trade_mode'];//交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
			$trans_type=1; //交易类型
			$time_start=date("YmdHis");  //订单生成时间			
			/**********************************************************/
		
			//构造要请求的参数数组，无需改动
			$parameter = array(
				"service" => "create_direct_pay_by_user",
				"partner" => trim($alipay_config['partner']),
				"payment_type"    => $payment_type,
				"notify_url"    => $notify_url,
				"return_url"    => $return_url,
				"seller_email"    => $seller_email,
				"out_trade_no"    => $out_trade_no,
				"subject"    => $subject,
				"total_fee"    => $total_fee,
				"body"            => $body,
				"show_url"	=> $show_url,
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
			);
			//建立请求
			$alipaySubmit = new AlipaySubmit($alipay_config);
			$html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");

			
			//写入数据
			$PayOrder=D('PayOrder');
			$_POST['product_name']=get_encoding($_POST['product_name']);
			
            $data['id']=$_POST['order_no'];//交易号
			$data['order_id_arr']=$_POST['order_id_arr'];
			$data['type']=1;
			$data['member_id']=getUid();
			$data['product_name']=$_POST['product_name'];			
            $route=I('route');
            if(is_array($route)){
                $data['route']=implode(',',$route);
            }else{
                $data['route']=$route;
            } 
			$data['coupon']=0;
			$data['order_price']=$_POST['order_price'];		
			$data['trade_mode']=$_POST['trade_mode'];
			$data['trade_state']=$payment_type;	
		   	$data['status']=0;
			$data['data_json']=0;
            $data['remark']=$_POST['remarkexplain'];//简要说明			
			$data['create_time']=time();
			$data['update_time']=time();
            $data['order_info']=json_encode(session('order_info'));
            if($PayOrder->create($data)){
                if(!$PayOrder->add()){
                    $this->error('订单写入失败');
                }else{
                   echo  $PayOrder->getDbError();
                }
            }else{
                $this->error('订单创建失败');
            }

            //转向支付页面
            //记录行为
            action_log('pay_aliPay', 'alipay', getUid(), getUid(),$this);
            echo $html_text;
            //header("Location:$alipayUrl");
		}
	 }

	/******************************
	服务器异步通知页面方法
	其实这里就是将notify_url.php文件中的代码复制过来进行处理        
	*******************************/
        function alipayNotifyurl(){
		action_log('pay_notifyurl', 'pay', getUid(), getUid(),$this);
		$alipay_config['partner']		= C('ALIPAY_PARTNER');
		$alipay_config['key']			= C('ALIPAY_KEY');
		$alipay_config['sign_type']     = strtoupper('MD5');
		$alipay_config['input_charset'] = strtolower('utf-8');
		$alipay_config['cacert']        = getcwd().'\\cacert.pem';
		$alipay_config['transport']     = 'http';		
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		if($verify_result) {
			   //验证成功
				   //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			   $out_trade_no   = $_POST['out_trade_no'];      //商户订单号
			   $trade_no       = $_POST['trade_no'];          //支付宝交易号
			   $trade_status   = $_POST['trade_status'];      //交易状态
			   $total_fee      = $_POST['total_fee'];         //交易金额
			   $notify_id      = $_POST['notify_id'];         //通知校验ID。
			   $notify_time    = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
			   $buyer_email    = $_POST['buyer_email'];       //买家支付宝帐号；
			
			$parameter = array(
				"out_trade_no"     => $out_trade_no,      //商户订单编号；
				"trade_no"     => $trade_no,          //支付宝交易号；
				"total_fee"      => $total_fee,         //交易金额；
				"trade_status"     => $trade_status,      //交易状态
				"notify_id"      => $notify_id,         //通知校验ID。
				"notify_time"    => $notify_time,       //通知的发送时间。
				"buyer_email"    => $buyer_email,       //买家支付宝帐号
			);			
		   if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
			   $res=$this->checkorderstatus($out_trade_no);
			   if($res==0){
				   $this->orderhandle($parameter);
				   $this->changeZf($out_trade_no);
				}
			   echo "success";        //请不要修改或删除
			}
		  }else {
					//验证失败
					echo "fail";    
	  }
	}
		
	
	/****************************
	支付返回
	****************************/		
	public function aliPayReturnUrl(){
		action_log('pay_returnurl', 'pay', getUid(), getUid(),$this);
		$alipay_config['partner']		= C('ALIPAY_PARTNER');
		$alipay_config['key']			= C('ALIPAY_KEY');
		$alipay_config['sign_type']     = strtoupper('MD5');
		$alipay_config['input_charset'] = strtolower('utf-8');
		$alipay_config['cacert']        = getcwd().'\\cacert.pem';
		$alipay_config['transport']     = 'http';
		
		$alipayNotify = new AlipayNotify($alipay_config);//计算得出通知验证结果
		$verify_result = $alipayNotify->verifyReturn();
		
		
		if($verify_result) {	   
			//验证成功
			//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
			$out_trade_no   = $_GET['out_trade_no'];      //商户订单号
			$trade_no       = $_GET['trade_no'];          //支付宝交易号
			$trade_status   = $_GET['trade_status'];      //交易状态
			$total_fee      = $_GET['total_fee'];         //交易金额
			$notify_id      = $_GET['notify_id'];         //通知校验ID。
			$notify_time    = $_GET['notify_time'];       //通知的发送时间。
			$buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；				
	
			$parameter = array(
				"out_trade_no"     => $out_trade_no,      //商户订单编号；
				"trade_no"     => $trade_no,          //支付宝交易号；
				"total_fee"      => $total_fee,         //交易金额；
				"trade_status"     => $trade_status,      //交易状态
				"notify_id"      => $notify_id,         //通知校验ID。
				"notify_time"    => $notify_time,       //通知的发送时间。
				"buyer_email"    => $buyer_email,       //买家支付宝帐号
			);
			
			if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
				$res=$this->checkorderstatus($out_trade_no);	
				if($res == 0){//未支付则执行
					if($this->orderhandle($parameter)){
						$rs=$this->changeZf($out_trade_no);
						if($rs == 1){
							$this->success('即时到帐支付成功',U('/Member/booking')."?status=process");
						}else{
							$this->error('支付失败',U('/Member/booking'));
						}
					}else{
						$this->error('支付失败',U('/Member/booking'));
					}
				}else{
					$rs=$this->changeZf($out_trade_no);
					if($rs == 1){
						$this->success('即时到帐支付成功^_^',U('/Member/booking')."?status=process");
					}else{
						$this->error('支付失败-_-',U('/Member/booking'));
					}
				}
			}
		 }else {		
			$this->error('验证失败',U('/Member/booking'));
		}
	}


		
	//----------------------------------------------2014.2.12
	//在线交易订单支付处理函数
	//函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
	//返回值：如果订单已经成功支付，返回true，否则返回false；
	function checkorderstatus($ordid){
		$Ord=D('PayOrder');
		$ordstatus=$Ord->where('id='.$ordid)->getField('status');
		if($ordstatus==1){
			return 1;//1表示已支付
		}else{
			return 0; //0表示未支付   
		}
	}
	
	//处理订单函数
	//更新订单状态，写入订单支付后返回的数据
	function orderhandle($parameter){
		$PayOrder=D('PayOrder');		
		$id=$parameter['out_trade_no'];		
		$data['update_time'] =time();
		$data['data_json']   =json_encode($parameter);
		$data['status']      =1;		
		$rs=$PayOrder->where('id='.$id)->save($data);		
		if($rs){
			return true;
		}
	}
	
	function changeZf($id){
		$orderDB = D('AsmsOrder');
		$PayOrder=D('PayOrder');
		$rs= $PayOrder->find($id);
		if($rs){
			$order_info=object_to_array(json_decode($rs['order_info']));		
			foreach($order_info as $val){
				$info=$orderDB->find($val['ddbh']);
				if($info['order_logo'] != 1){
					//如果是胜意订单
					$res=$orderDB->orderPay($val['ddbh'],ASMSUID,$val['yfje'],$val['xjj'],$ordid,1,$rs['remark']);
					if(!$res){return 1;}
				}else{
					if($info['zf_fkf'] == 1){
						return 1;
					}else{
						$wh['ddbh']=$val['ddbh'];
						$res=$orderDB->where($wh)->setField('zf_fkf','1');
						if($res){
							return 1;
						}
					}
				}
   			 }
		}
	}

	//////////////////////////////////////////////快钱支付 begin///////////////////////////////////////////////////////////
	function send(){
		//人民币网关账号，该账号为11位人民币网关商户编号+01,该参数必填。
		$merchantAcctId = "1002340723701";
		
		//编码方式，1代表 UTF-8; 2 代表 GBK; 3代表 GB2312 默认为1,该参数必填。
		$inputCharset = "1";
		
		//接收支付结果的页面地址，该参数一般置为空即可。
		$pageUrl = "";
		
		//服务器接收支付结果的后台地址，该参数务必填写，不能为空。
		$bgUrl = "http://www.aishangfei.com/Member/pay/recieve";

		//网关版本，固定值：v2.0,该参数必填。
		$version =  "v2.0";
		
		//语言种类，1代表中文显示，2代表英文显示。默认为1,该参数必填。
		$language =  "1";
		
		//签名类型,该值为4，代表PKI加密方式,该参数必填。
		$signType =  "4";
		
		//支付人姓名,可以为空。
		$payerName= ""; 
		
		//支付人联系类型，1 代表电子邮件方式；2 代表手机联系方式。可以为空。
		$payerContactType =  "";
		
		//支付人联系方式，与payerContactType设置对应，payerContactType为1，则填邮箱地址；payerContactType为2，则填手机号码。可为空。
		$payerContact =  "";
				
		//指定付款人 可为空
		$payerIdType="3";
		
		//付款人标识
		$payerId=getUid();		
		
		//商户订单号，以下采用时间来定义订单号，商户可以根据自己订单号的定义规则来定义该值，不能为空。
		$orderId = $_POST['order_no'];
		
		//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试。该参数必填。
		$orderAmount = $_POST['order_price']*100;
		
		//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101，不能为空。
		$orderTime = date("YmdHis");
		
		//商品名称，可以为空。
		$productName= $_POST['product_name'];
		
		//商品数量，可以为空。
		$productNum = "";
		
		//商品代码，可以为空。
		$productId = "";
		
		//商品描述，可以为空。
		$productDesc = $_POST['remarkexplain'];
		
		//扩展字段1，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
		$ext1 = $_POST['order_id_arr'];
		
		//扩展自段2，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
		$ext2 = $_POST['route'];
		
		//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10，必填。
		$payType = "00";
		
		//银行代码，如果payType为00，该值可以为空；如果payType为10，该值必须填写，具体请参考银行列表。
		$bankId = "";
		
		//同一订单禁止重复提交标志，实物购物车填1，虚拟产品用0。1代表只能提交一次，0代表在支付不成功情况下可以再提交。可为空。
		$redoFlag = "";
		
		//快钱合作伙伴的帐户号，即商户编号，可为空。
		$pid = "";

		
		// signMsg 签名字符串 不可空，生成加密签名串
		    function kq_ck_null($kq_va,$kq_na){
				if($kq_va == ""){
					$kq_va="";
				}else{
					return $kq_va=$kq_na.'='.$kq_va.'&';
				}
			}		
			$kq_all_para=kq_ck_null($inputCharset,'inputCharset');
			$kq_all_para.=kq_ck_null($pageUrl,"pageUrl");
			$kq_all_para.=kq_ck_null($bgUrl,'bgUrl');
			$kq_all_para.=kq_ck_null($version,'version');
			$kq_all_para.=kq_ck_null($language,'language');
			$kq_all_para.=kq_ck_null($signType,'signType');
			$kq_all_para.=kq_ck_null($merchantAcctId,'merchantAcctId');
			$kq_all_para.=kq_ck_null($payerName,'payerName');
			$kq_all_para.=kq_ck_null($payerContactType,'payerContactType');
			$kq_all_para.=kq_ck_null($payerContact,'payerContact');
			$kq_all_para.=kq_ck_null($payerIdType,'payerIdType');
			$kq_all_para.=kq_ck_null($payerId,'payerId');				
			$kq_all_para.=kq_ck_null($orderId,'orderId');
			$kq_all_para.=kq_ck_null($orderAmount,'orderAmount');
			$kq_all_para.=kq_ck_null($orderTime,'orderTime');
			$kq_all_para.=kq_ck_null($productName,'productName');
			$kq_all_para.=kq_ck_null($productNum,'productNum');
			$kq_all_para.=kq_ck_null($productId,'productId');
			$kq_all_para.=kq_ck_null($productDesc,'productDesc');
			$kq_all_para.=kq_ck_null($ext1,'ext1');
			$kq_all_para.=kq_ck_null($ext2,'ext2');
			$kq_all_para.=kq_ck_null($payType,'payType');
			$kq_all_para.=kq_ck_null($bankId,'bankId');
			$kq_all_para.=kq_ck_null($redoFlag,'redoFlag');
			$kq_all_para.=kq_ck_null($pid,'pid');	
			
			$kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);		
			
		/////////////  RSA 签名计算 ///////// 开始 //
		$fp = fopen(WEB_ROOT."99bill/99bill-rsa.pem", "r");
		$priv_key = fread($fp, 123456);		
		fclose($fp);
		$pkeyid = openssl_get_privatekey($priv_key);	
		// compute signature
		openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);	
		// free the key from memory
		openssl_free_key($pkeyid);	
		$signMsg = base64_encode($signMsg);		 
		/////////////  RSA 签名计算 ///////// 结束 //
		
		$this->assign('merchantAcctId',$merchantAcctId);
		$this->assign('inputCharset',$inputCharset);
		$this->assign('pageUrl',$pageUrl);
		$this->assign('bgUrl',$bgUrl);
		$this->assign('version',$version);
		
		$this->assign('language',$language);
		$this->assign('signType',$signType);
		$this->assign('signMsg',$signMsg);
		$this->assign('payerName',$payerName);
		$this->assign('payerContactType',$payerContactType);
		
		$this->assign('payerContact',$payerContact);		
		$this->assign('orderId',$orderId);
		$this->assign('orderAmount',$orderAmount);
		$this->assign('orderTime',$orderTime);
		$this->assign('productName',$productName);
		
		$this->assign('productNum',$productNum);		
		$this->assign('productId',$productId);
		$this->assign('productDesc',$productDesc);
		$this->assign('ext1',$ext1);
		$this->assign('ext2',$ext2);
		
		$this->assign('payType',$payType);		
		$this->assign('bankId',$bankId);
		$this->assign('redoFlag',$redoFlag);
		$this->assign('pid',$pid);
		
		$this->assign('payerIdType',$payerIdType);		
		$this->assign('payerId',$payerId);
		
		$this->title="确认支付";
		$this->display();		
	}
	
	function recieve(){
		function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
		
		//人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
		$kq_check_all_para=kq_ck_null($_REQUEST[merchantAcctId],'merchantAcctId');
		
		//网关版本，固定值：v2.0,该值与提交时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[version],'version');
		
		//语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[language],'language');
		
		//签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[signType],'signType');
		
		//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[payType],'payType');
		
		//银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[bankId],'bankId');

		//商户订单号，,该值与提交时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[orderId],'orderId');
		
		//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[orderTime],'orderTime');
		
		//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[orderAmount],'orderAmount');
		
		////////////////////////////
			//已绑短卡号
		$kq_check_all_para.=kq_ck_null($_REQUEST[bindCard],'bindCard');
			//已绑短手机尾号
		$kq_check_all_para.=kq_ck_null($_REQUEST[bindMobile],'bindMobile');		
		////////////////////////////
		
		// 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
		$kq_check_all_para.=kq_ck_null($_REQUEST[dealId],'dealId');
		
		//银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
		$kq_check_all_para.=kq_ck_null($_REQUEST[bankDealId],'bankDealId');
		
		//快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
		$kq_check_all_para.=kq_ck_null($_REQUEST[dealTime],'dealTime');
		
		//商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
		$kq_check_all_para.=kq_ck_null($_REQUEST[payAmount],'payAmount');
		
		//费用，快钱收取商户的手续费，单位为分。
		$kq_check_all_para.=kq_ck_null($_REQUEST[fee],'fee');
		
		//扩展字段1，该值与提交时相同
		$kq_check_all_para.=kq_ck_null($_REQUEST[ext1],'ext1');
		
		//扩展字段2，该值与提交时相同。
		$kq_check_all_para.=kq_ck_null($_REQUEST[ext2],'ext2');
		
		//处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
		$kq_check_all_para.=kq_ck_null($_REQUEST[payResult],'payResult');
		
		//错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
		$kq_check_all_para.=kq_ck_null($_REQUEST[errCode],'errCode');
		
		$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
		$MAC=base64_decode($_REQUEST[signMsg]);
	
		$fp = fopen(WEB_ROOT."99bill/99bill.cert.rsa.20140728.cer", "r"); 
		$cert = fread($fp, 8192); 
		fclose($fp); 
		$pubkeyid = openssl_get_publickey($cert); 
		$ok = openssl_verify($trans_body, $MAC, $pubkeyid); 
	
		if ($ok == 1) { 
			switch($_REQUEST[payResult]){
					case '10':
							//此处做商户逻辑处理
							//$rtnOK=1;
							if($_REQUEST[payAmount] == $_REQUEST[orderAmount]){								
								$data=array(
									'id'          =>$_REQUEST[orderId],
									'order_id_arr'=>$_REQUEST[ext1],
									'member_id'   =>getUId(),
									'route'       =>$_REQUEST[ext2],
									'product_name'=>'机票预订-快钱支付',
									'order_price' =>$_REQUEST[orderAmount]/100,	
									'status'      =>1,
									'create_time' =>time()
								);
								$rs=D('PayOrder')->add($data);
								if($rs){
									$orderID=explode(',',$_REQUEST[ext1]);
									$where['ddbh']=array('in',$orderID);
									$res=D('AsmsOrder')->where($where)->setField('zf_fkf',1);
									if($res){
										echo '<result>1</result>';
										echo '<redirecturl>http://www.aishangfei.com/Member/booking?status=process</redirecturl>';
									}
								}								
							}
					default:
							echo '<result>1</result>';
							echo '<redirecturl>http://www.aishangfei.com/Member/booking?status=pending</redirecturl>';								
			}	
		}else{	
			echo '<result>1</result>';
			echo '<redirecturl>http://www.aishangfei.com/Member/booking?status=pending</redirecturl>';								
		}	
	}
	//////////////////////////////////////////////快钱支付 end ///////////////////////////////////////////////////////////////////
}?>