<?php
session_start();
header("Content-type:text/html; charset=UTF-8");
$paramArr =array('customer'=>'何生');
var_dump(xml_to_array(send_message('13143061188','SMS_48355105',$paramArr)));
function send_message($mobile,$templateCode,$paramArr){
    $target = "https://sms.aliyuncs.com/?";
    $dateTimeFormat = 'Y-m-d\TH:i:s\Z'; // ISO8601规范
    $accessKeyId = 'LTAIZGUDcMxqTMuI';      // 这里填写您的Access Key ID
    $accessKeySecret = '2txz7hVNh3mHfLWsJuJ0LmIdkbLOwb';  // 这里填写您的Access Key Secret
    $ParamString=json_encode($paramArr);
    $data = array(
        // 公共参数
        'SignName'=>'品悦旅行网',
        'Format' => 'XML',
        'Version' => '2016-09-27',
        'AccessKeyId' => $accessKeyId,
        'SignatureVersion' => '1.0',
        'SignatureMethod' => 'HMAC-SHA1',
        'SignatureNonce'=> uniqid(),
        'Timestamp' => date($dateTimeFormat),
        // 接口参数
        'Action' => 'SingleSendSms',
        'TemplateCode' => $templateCode,
        'RecNum' => $mobile,
        'ParamString' => $ParamString
    );
    // 计算签名并把签名结果加入请求参数
    $data['Signature'] = computeSignature($data, $accessKeySecret);
    return https_request($target.http_build_query($data));
}
echo 111;exit;

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
//function random($length = 6 , $numeric = 0) {
//    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
//    if($numeric) {
//        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
//    } else {
//        $hash = '';
//        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
//        $max = strlen($chars) - 1;
//        for($i = 0; $i < $length; $i++) {
//            $hash .= $chars[mt_rand(0, $max)];
//        }
//    }
//    return $hash;
//}
//$target = "https://sms.aliyuncs.com/?";
//
//$mobile_code = random(6,1);

function percentEncode($str)
{
    // 使用urlencode编码后，将"+","*","%7E"做替换即满足ECS API规定的编码规范  
    $res = urlencode($str);
    $res = preg_replace('/\+/', '%20', $res);
    $res = preg_replace('/\*/', '%2A', $res);
    $res = preg_replace('/%7E/', '~', $res);
    return $res;
}


function computeSignature($parameters, $accessKeySecret)
{
    // 将参数Key按字典顺序排序  
    ksort($parameters);
    // 生成规范化请求字符串  
    $canonicalizedQueryString = '';
    foreach($parameters as $key => $value)
    {
        $canonicalizedQueryString .= '&' . percentEncode($key)
            . '=' . percentEncode($value);
    }
    // 生成用于计算签名的字符串 stringToSign  
    $stringToSign = 'GET&%2F&' . percentencode(substr($canonicalizedQueryString, 1));
    //echo "<br>".$stringToSign."<br>";
    // 计算签名，注意accessKeySecret后面要加上字符'&'  
    $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
    return $signature;
}
// 注意使用GMT时间  
date_default_timezone_set("GMT");
$dateTimeFormat = 'Y-m-d\TH:i:s\Z'; // ISO8601规范
$accessKeyId = 'LTAIZGUDcMxqTMuI';      // 这里填写您的Access Key ID
$accessKeySecret = '2txz7hVNh3mHfLWsJuJ0LmIdkbLOwb';  // 这里填写您的Access Key Secret
$ParamString="{\"customer\":\"".strval($mobile_code)."\"}";

$data = array(
    // 公共参数
    'SignName'=>'品悦旅行网',
    'Format' => 'XML',
    'Version' => '2016-09-27',
    'AccessKeyId' => $accessKeyId,
    'SignatureVersion' => '1.0',
    'SignatureMethod' => 'HMAC-SHA1',
    'SignatureNonce'=> uniqid(),
    'Timestamp' => date($dateTimeFormat),
    // 接口参数
    'Action' => 'SingleSendSms',
    'TemplateCode' => 'SMS_48355105',
    'RecNum' => '13143061188',
    'ParamString' => $ParamString
);
// 计算签名并把签名结果加入请求参数
//echo $data['Version']."<br>";
//echo $data['Timestamp']."<br>";
$data['Signature'] = computeSignature($data, $accessKeySecret);
// 发送请求  
$result = xml_to_array(https_request($target.http_build_query($data)));

//echo $result['Error']['Code']."--->".$result['Error']['Message'];

echo "<br><br>".$target . http_build_query($data);
?>