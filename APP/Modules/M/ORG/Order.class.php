<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/31
 * Time: 18:20
 */
class Order
{
    /* 微信支付，主动查询订单状态 */

    public static function WxQueryorder($order_pay_no = null, $transaction_id = null) {
        $name = date('Ymd');
        file_put_contents("./APP/Runtime/Logs/".$name.".log", date('Y-m-d H:i:s', time()) .'WxQueryorder_start'. "\r\n", FILE_APPEND);
//        $order_pay_no = Input::get('order_pay_no') ? Input::get('order_pay_no') : $order_pay_no;
        require_once './APP/Lib/ORG/Wxpay/WxPay.Api.php';
        require_once './APP/Lib/ORG/Wxpay/WxPay.Notify.php';
        $input = new WxPayOrderQuery();
        if (strlen($order_pay_no) > 0) {
            $input->SetOut_trade_no($order_pay_no);
        }
        if (strlen($transaction_id) > 0) {
            $input->SetTransaction_id($transaction_id);
        }
        $result = WxPayApi::orderQuery($input);
        file_put_contents("./APP/Runtime/Logs/".$name.".log", date('Y-m-d H:i:s', time()) .'：WxQueryorder——'.json_encode($result). "\r\n", FILE_APPEND);
//         var_dump($result);
        if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            //  var_dump($result);
            if ($result['trade_state'] == 'SUCCESS') {
                //支付订单号
                $out_trade_no = $result['out_trade_no'];
                //总金额
                $total_fee = $result['total_fee'];
//                //微信支付订单号
//                $transaction_id = $result['transaction_id'];
//                //状态
//                $trade_state = $result['trade_state'];
//
//                $notify_id = $transaction_id; //没有通知ID只能这个了。。
                //更新订单状态
                $tripOrder =D('TripOrder');
                $res=$tripOrder->updatepay($out_trade_no, $total_fee);
                file_put_contents("./APP/Runtime/Logs/".$name.".log", date('Y-m-d H:i:s', time()) .'：updatepay——'.$res. "\r\n", FILE_APPEND);
                return true;
            }
        }
    }
}