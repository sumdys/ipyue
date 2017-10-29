<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/15
 * Time: 0:16
 */
class Message
{


    /**
     * 短信发送
     * @param string $mobile 手机号
     * @param array $paramArr 要发送信息
     * @param string $TemplateCode 短信模板的模板CODE
     */
    public function sendMessage($mobile,$templateCode, $paramArr) {
        date_default_timezone_set("GMT");
        //$message = urlencode($message);
//        session_start();
        header("Content-type:text/html; charset=UTF-8");
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
        $data['Signature'] = $this->computeSignature($data, $accessKeySecret);
    $send = $this->xml_to_array($this->https_request($target.http_build_query($data)));
    file_put_contents("./APP/Runtime/Logs/message".date('Ymd').".log", date('Y-m-d H:i:s', time()) .':' . json_encode($send)."\r\n", FILE_APPEND);
    return $send;
    }


    private function https_request($url)
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

    private function percentEncode($str)
    {
        // 使用urlencode编码后，将"+","*","%7E"做替换即满足ECS API规定的编码规范
        $res = urlencode($str);
        $res = preg_replace('/\+/', '%20', $res);
        $res = preg_replace('/\*/', '%2A', $res);
        $res = preg_replace('/%7E/', '~', $res);
        return $res;
    }


    private function computeSignature($parameters, $accessKeySecret)
    {
        // 将参数Key按字典顺序排序
        ksort($parameters);
        // 生成规范化请求字符串
        $canonicalizedQueryString = '';
        foreach($parameters as $key => $value)
        {
            $canonicalizedQueryString .= '&' . $this->percentEncode($key)
                . '=' . $this->percentEncode($value);
        }
        // 生成用于计算签名的字符串 stringToSign
        $stringToSign = 'GET&%2F&' . $this->percentencode(substr($canonicalizedQueryString, 1));
        //echo "<br>".$stringToSign."<br>";
        // 计算签名，注意accessKeySecret后面要加上字符'&'
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
        return $signature;
    }


    private function xml_to_array($xml)
    {
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return @$arr;
    }

}