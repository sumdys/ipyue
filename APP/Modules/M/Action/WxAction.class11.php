<?php
// 微信支付JSAPI版本
// 基于版本 V3
// By App 2015-1-20


class WxAction extends Action
{
    public function index(){
        $this->display();
    }

    //打印输出数组信息
    public function printf_info($data)
    {
        foreach($data as $key=>$value){
            echo "<font color='#00ff55;'>$key</font> : $value <br/>";
        }
    }

    public function jsapi(){
        ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
        require_once "APP/Lib/ORG/Wxpay/WxPay.Api.php";
        require_once "APP/Lib/ORG/Wxpay/WxPay.JsApiPay.php";
//        require_once 'APP/Lib/ORG/Wxpay/log.php';
//        echo 111;exit;
//初始化日志
//        $logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
//        $log = Log::Init($logHandler, 15);

        //获取订单信息
        $info = session('orderData');
        $res = D('TripOrder')->getOrderInfo($info['id']);
        $orderData = $res['data'];
        $this->assign('info',$orderData);
//        var_dump($orderData);exit;
//①、获取用户openid
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

//②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($orderData['title']);
        $input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee($orderData['total_price']*100*$orderData['num']);
//        $input->SetTotal_fee('1');
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
//        $input->SetGoods_tag("test");
        $input->SetNotify_url('http://' . $_SERVER['HTTP_HOST'] . '/wx/wxpay_notify_url');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
//        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//        echo '<font color="#f00"><b>openId：'.$openId.'</b></font><br/>';
//        $this->printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);
//        var_dump($jsApiParameters);exit;
//获取共享收货地址js函数参数
//        $editAddress = $tools->GetEditAddressParameters();
        $this->jsApiParameters=$jsApiParameters;
        $this->display();
    }

    public function getbutton($code,$returnrul=''){
        $button = <<<EOT
<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall(){
			WeixinJSBridge.invoke('getBrandWCPayRequest',{$code},function(res){
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_code+'调试信息：'+res.err_desc+res.err_msg);
					if(res.err_msg.indexOf('ok')>0){
						window.location.href="{:U('/orders/confirm')}";
					}
				});
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
	</script>
<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
EOT;
        return $button;
    }

    /*
     * 微信回调
     */
    public function wxpay_notify_url(){
            $postdata = file_get_contents("php://input");
            file_put_contents("../WxNotifylog.log", date('Y-m-d H:i:s', time()) . $postdata . ':' . json_encode($_POST), FILE_APPEND);
            ini_set('date.timezone', 'Asia/Shanghai');
            error_reporting(E_ERROR);
            require_once 'APP/Lib/ORG/Wxpay/WxPay.Api.php';
            require_once 'APP/Lib/ORG/Wxpay/WxPay.Notify.php';
            //   Log::DEBUG("begin notify");
            $notify = new WxPayNotify();
            $notify->SetNotifyProcess($this, 'NotifyProcess');
            $notify->Handle(false);
            file_put_contents("../WxNotifylog.log", 'exit' . "\r\n", FILE_APPEND);
    }

    ///*微信支付回调处理*/
    public function NotifyProcess($data, $msg) {
        $notfiyOutput = array();
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        $tripOrder = D('TripOrder');
        if (!$tripOrder->WxQueryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }
} //Wxpay类结束
