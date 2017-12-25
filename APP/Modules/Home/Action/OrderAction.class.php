<?php
// 订单控制器
class OrderAction extends IniAction {
    public function index(){
        $this->title="品悦定制旅行_定制旅游";
        $this->keywords="品悦旅行，定制旅行，定制旅游，旅行定制，旅行定制师，海外旅行，旅游路书，旅行";
        $this->description="品悦定制旅行最专业的海外旅行定制专家。我们拥有优秀的旅行定制师团队，凭借多年深度旅行和海外旅居经历，为想要出国旅行的朋友提供咨询服务。专业的推荐、精彩的设计、严谨的安排、24小时电话和网络协助，一切都只为，让你的旅行有温度。";
        $info = D("Freetour")->info();
        $this->assign('freetour_id',I('id'));
        $this->assign('info', $info);
        $this->display();
    }

     
    public function payMethod(){
        
        $res = D('TripOrder')->addOrder();
        if ($res['status']) {
            $info = D('TripOrder')->getInfo($res['id']);
            $this->assign('info', $info['data']);
           
        } else {
            echo "<script>alert('订单创建失败，请重试')</script>";
            $this->redirect('/Index/index', 3, '');
        }
       $this->display('payMethod');
    }

    public function pay()
    {
      
        require_once("./Alipay/alipay.config.php");
        require_once("./Alipay/lib/alipay_submit.class.php");
        /* * ************************请求参数************************* */
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = "http://www.ipyue.com/Alipay/payNotifyUrl.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        //页面跳转同步通知页面路径
        $return_url = "http://www.ipyue.com/order/returnUrl/";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //卖家支付宝帐户
        //   $seller_email = $_POST['WIDseller_email'];
        $seller_email = "webservice@ipyue.com";
        //必填
        //商户订单号
        $out_trade_no = $_POST['order_no'];
        //商户网站订单系统中唯一订单号，必填
        //订单名称
        $subject = $_POST['product_name'];
        //必填
        //付款金额
        $total_fee = isset($_POST['order_price']) ? $_POST['order_price'] : 0;
        //必填
        //订单描述

    $body = $_POST['product_name'];
        //商品展示地址
        $show_url = isset($_POST['product_name']) ? $_POST['product_name'] : '';
        //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html
        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数
        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1


        /*         * ********************************************************* */
        if ($total_fee <= 0) {
            echo "<script>alert('金额必需大于0');history.back();</script>";
            exit;
        }
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "payment_type" => $payment_type,
            "notify_url" => $notify_url,
            "return_url" => $return_url,
            "seller_email" => $seller_email,
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            "show_url" => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip" => $exter_invoke_ip,
            "_input_charset" => trim(strtolower($alipay_config['input_charset']))
        );

//建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
        echo $html_text;
    }

    /****************************
	支付返回
	****************************/
	public function returnUrl(){

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
        if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                  $pay_data = json_encode($parameter);
                   $info = D('TripOrder')->upPayState($out_trade_no,$pay_data,1);
        } else {
           U('/order/index');
        }
       $this->display('returnUrl');

	}

    //查询
    public function paySuccess(){
        $this->display();
    }

}