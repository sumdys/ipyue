<?php

/**
 * Created by PhpStorm.
 * User: He Sheng
 * Date: 2017/4/16
 * Time: 16:37
 */
class Message
{


    public static function send($mobile,$templateCode,$paramArr){
        sendMessage($mobile,$templateCode,$paramArr);
    }


//    private static function https_request($url)
//    {
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        $data = curl_exec($curl);
//        if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
//        curl_close($curl);
//        return $data;
//    }
//
//    private static function percentEncode($str)
//    {
//        // ʹ��urlencode����󣬽�"+","*","%7E"���滻������ECS API�涨�ı���淶
//        $res = urlencode($str);
//        $res = preg_replace('/\+/', '%20', $res);
//        $res = preg_replace('/\*/', '%2A', $res);
//        $res = preg_replace('/%7E/', '~', $res);
//        return $res;
//    }
//
//
//    private static function computeSignature($parameters, $accessKeySecret)
//    {
//        // ������Key���ֵ�˳������
//        ksort($parameters);
//        // ���ɹ淶�������ַ���
//        $canonicalizedQueryString = '';
//        foreach($parameters as $key => $value)
//        {
//            $canonicalizedQueryString .= '&' . self::percentEncode($key)
//                . '=' . self::percentEncode($value);
//        }
//        // �������ڼ���ǩ�����ַ��� stringToSign
//        $stringToSign = 'GET&%2F&' . self::percentencode(substr($canonicalizedQueryString, 1));
//        //echo "<br>".$stringToSign."<br>";
//        // ����ǩ����ע��accessKeySecret����Ҫ�����ַ�'&'
//        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
//        return $signature;
//    }
//
//
//    private static function xml_to_array($xml){
//        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
//        if(preg_match_all($reg, $xml, $matches)){
//            $count = count($matches[0]);
//            for($i = 0; $i < $count; $i++){
//                $subxml= $matches[2][$i];
//                $key = $matches[1][$i];
//                if(preg_match( $reg, $subxml )){
//                    $arr[$key] = self::xml_to_array( $subxml );
//                }else{
//                    $arr[$key] = $subxml;
//                }
//            }
//        }
//        return @$arr;
//    }

}



/**
 * ���ŷ���
 * @param string $mobile �ֻ���
 * @param array $paramArr Ҫ������Ϣ
 * @param string $TemplateCode ����ģ���ģ��CODE
 */
function sendMessage($mobile,$templateCode, $paramArr) {
    date_default_timezone_set("GMT");
    //$message = urlencode($message);
    session_start();
    header("Content-type:text/html; charset=UTF-8");
    $target = "https://sms.aliyuncs.com/?";
    $dateTimeFormat = 'Y-m-d\TH:i:s\Z'; // ISO8601�淶
    $accessKeyId = 'LTAIZGUDcMxqTMuI';      // ������д����Access Key ID
    $accessKeySecret = '2txz7hVNh3mHfLWsJuJ0LmIdkbLOwb';  // ������д����Access Key Secret
    $ParamString=json_encode($paramArr);
    $data = array(
        // ��������
        'SignName'=>'Ʒ��������',
        'Format' => 'XML',
        'Version' => '2016-09-27',
        'AccessKeyId' => $accessKeyId,
        'SignatureVersion' => '1.0',
        'SignatureMethod' => 'HMAC-SHA1',
        'SignatureNonce'=> uniqid(),
        'Timestamp' => date($dateTimeFormat),
        // �ӿڲ���
        'Action' => 'SingleSendSms',
        'TemplateCode' => $templateCode,
        'RecNum' => $mobile,
        'ParamString' => $ParamString
    );
    // ����ǩ������ǩ����������������
    $data['Signature'] = computeSignature($data, $accessKeySecret);
    $send = xml_to_array(https_request($target.http_build_query($data)));
    file_put_contents("./APP/Runtime/Logs/message".date('Ymd').".log", date('Y-m-d H:i:s', time()) .':' . json_encode($send)."\r\n", FILE_APPEND);
    return $send;
}


function https_request($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
    curl_close($curl);
    return $data;
}

function percentEncode($str)
{
    // ʹ��urlencode����󣬽�"+","*","%7E"���滻������ECS API�涨�ı���淶
    $res = urlencode($str);
    $res = preg_replace('/\+/', '%20', $res);
    $res = preg_replace('/\*/', '%2A', $res);
    $res = preg_replace('/%7E/', '~', $res);
    return $res;
}


function computeSignature($parameters, $accessKeySecret)
{
    // ������Key���ֵ�˳������
    ksort($parameters);
    // ���ɹ淶�������ַ���
    $canonicalizedQueryString = '';
    foreach($parameters as $key => $value)
    {
        $canonicalizedQueryString .= '&' . percentEncode($key)
            . '=' . percentEncode($value);
    }
    // �������ڼ���ǩ�����ַ��� stringToSign
    $stringToSign = 'GET&%2F&' . percentencode(substr($canonicalizedQueryString, 1));
    //echo "<br>".$stringToSign."<br>";
    // ����ǩ����ע��accessKeySecret����Ҫ�����ַ�'&'
    $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
    return $signature;
}


function xml_to_array($xml){
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches)){
        $count = count($matches[0]);
        for($i = 0; $i < $count; $i++){
            $subxml= $matches[2][$i];
            $key = $matches[1][$i];
            if(preg_match( $reg, $subxml )){
                $arr[$key] = xml_to_array( $subxml );
            }else{
                $arr[$key] = $subxml;
            }
        }
    }
    return @$arr;
}