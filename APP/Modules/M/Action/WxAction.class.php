<?php
// 微信支付JSAPI版本
// 基于版本 V3
// By App 2015-1-20


class WxAction extends Action
{
    /*
     * 分享
     */
    public function share_test(){
        require_once "APP/Lib/ORG/Wxpay/JsSdk.class.php";
        $jssdk = new JSSDK();
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('data',$signPackage);
        $this->display();
    }
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
//        echo 'http://' . $_SERVER['HTTP_HOST'] . '/wx/wxpay_notify_url';exit;
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
        $input->SetOut_trade_no($orderData['order_num']);
        $input->SetTotal_fee($orderData['pay_price']*100);
        if($_SERVER['REMOTE_ADDR']=='58.62.103.40'){
            $input->SetTotal_fee('1');
        }

        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));

//        $input->SetGoods_tag("test");
        $input->SetNotify_url('http://' . $_SERVER['HTTP_HOST'] . '/wx/wxpay_notify_url');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
//        $this->printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->jsApiParameters=$jsApiParameters;
//        if($_SERVER['REMOTE_ADDR']=='58.62.103.40'){
//            echo 222;exit;
//        }
        $this->display();
    }



    /*
     * 微信回调
     */
    public function wxpay_notify_url(){
            $postdata = file_get_contents("php://input");

            file_put_contents("./APP/Runtime/Logs/WxNotifylog.log", date('Y-m-d H:i:s', time()) . $postdata . ':' . json_encode($_POST)."\r\n", FILE_APPEND);

            ini_set('date.timezone', 'Asia/Shanghai');
            error_reporting(E_ERROR);
            require_once 'APP/Lib/ORG/Wxpay/WxPay.Api.php';
            require_once 'APP/Lib/ORG/Wxpay/WxPay.Notify.php';
            //   Log::DEBUG("begin notify");
            $notify = new WxPayNotify();
            $notify->SetNotifyProcess($this, 'NotifyProcess');
            $notify->Handle(false);
            file_put_contents("./APP/Runtime/Logs/WxNotifylog.log", 'exit' . "\r\n", FILE_APPEND);
    }

    ///*微信支付回调处理*/
    public function NotifyProcess($data, $msg) {
        $name = date('Ymd');
        file_put_contents("./APP/Runtime/Logs/".$name.".log", date('Y-m-d H:i:s', time()) .json_encode($data). "\r\n", FILE_APPEND);
        $notfiyOutput = array();
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        import('@.ORG.Order');
        if (!Order::WxQueryorder($data['out_trade_no'],$data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }
        file_put_contents("./APP/Runtime/Logs/".$name.".log", date('Y-m-d H:i:s', time()) .'：end'. "\r\n", FILE_APPEND);
        return true;
    }
} //Wxpay类结束
