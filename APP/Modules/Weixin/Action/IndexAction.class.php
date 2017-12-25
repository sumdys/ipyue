<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-7-23
 * Time: 下午5:32
 * To change this template use File | Settings | File Templates.
 */
class IndexAction extends Action{
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
//        require_once 'log.php';
//        echo 111;exit;
//初始化日志
//        $logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
//        $log = Log::Init($logHandler, 15);



//①、获取用户openid
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

//②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
//        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//        printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();
        echo $this->getbutton($jsApiParameters);
        exit;
    }

    public function getbutton($code,$returnrul){
        $button = <<<EOT
<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall(){
			WeixinJSBridge.invoke('getBrandWCPayRequest',{$code},function(res){
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_code+'调试信息：'+res.err_desc+res.err_msg);
					if(res.err_msg.indexOf('ok')>0){
						window.location.href='{$returnrul}';
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
<img onclick="callpay()" src="/data/images/wxzf.png" style="height:40px; width:140px;cursor:pointer; display:block; "  />
EOT;
        return $button;
    }
}