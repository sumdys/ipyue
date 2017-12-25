<?php
class CommonAction extends IniAction{
	function index(){
		
	}
	
	function verify_code($w=52,$h=27,$verify='verify'){ //验证码
		import("ORG.Util.Image");
        $w=I('w')?I('w'):$w;
        $h=I('h')?I('h'):$h;
        $verify=I('verify')?I('verify'):$verify;
		Image::buildImageVerify($length=4, $mode=1, $type='png',$w,$h,$verify);
    }
	
	function CheckName(){
		if(IS_AJAX){
			if(isset($_GET['d'])){
				$username=I('d');
				$member=D('Member');
                echo $member->regCheckName($username);
			}
		}
	}
	
	function CheckPassword(){
		if(IS_AJAX){
			if(isset($_GET['name']) && isset($_GET['password'])){
				$username=I('post.name');
				$password=I('post.password');
				$member=D('Member');
				$salt=$member->getsalt($username);
				$password=hashPassword($password,$salt);
				$userinfo=$member->where("username='$username' and password='$password'")->find();
				if(!empty($userinfo)){
					echo 1;
				}else{
					echo 0;
				}
			}
		}
	}

    //检测手机号
	function CheckPhone(){
		if(IS_AJAX){
			if(isset($_GET['d'])){
				$phone=$_GET['d'];
                $rs=preg_match('/^([0-9]){11,12}$/i',$phone);
                if(!$rs){
                    echo "手机号格式不正确";
                    exit;
                }

				$member=D('Member');
				$userinfo=$member->where("mobile='$phone'")->find();
				if(empty($userinfo)){
					echo 1;				
				}else{
					echo "该手机已注册过";
				}
			}elseif(isset($_GET['phone'])){
                $phone=I('phone');
                $member=D('Member');
                $userinfo=$member->where("mobile='$phone'")->find();
                if($userinfo){
                    echo "该手机已注册过";exit;
                }
                import('ORG.Util.String');
                $auth_str=String::randString(6,1);  //生成6位数的认证码
                session('auth_reg_mobile', $phone);
                session('auth_reg_str',$auth_str);
//                $data['mobile']=$phone;
//                $data['yzm']=$auth_str;
                $data['code']= $auth_str;
                $data['product']= '品悦旅行网';
//                import('ORG.Message',APP_PATH.'Lib/');// 导入
//                $rs=Message::send($phone,'SMS_48355102',$data);
                $rs=sendMessage($phone,'SMS_48355102',$data);

//                import('ORG.Message');
//                $message= new Message();
//                $re=$message->sendMessage($phone,'SMS_48355102',$data);
//                var_dump($re);exit;
//                $rs=D('Message')->message_action('reg_verify_phone',$data);  echo 1111;exit;
              //  $rs=sendMobileSms(session('auth_reg_mobile'),str_replace('xxxxxx',$auth_str,C('REG_AUTH')));//
                if($rs){
                    echo 1;
                }else{
                    echo 0;
                }
            }elseif(isset($_GET['num'])){
                if(I('num')==session('auth_reg_str')){
                    session('reg_mobile',session('auth_reg_mobile'));
                    echo 1;
                }else{
                    echo 0;
                }
            }
		}
	}
	
	//检测验证码
	function CheckVerify(){
		if(IS_AJAX){
			if(isset($_GET['d'])){
				if(md5($_GET['d']) == session('verify')){
					echo 1;
				}else{
					echo "验证码错误 请重新输入";
				}
			}
		}
	}

    //发送密码找回验证码
    function forgotPwd(){
        if(!session('auth_num'))
            session('auth_num',1);
        if(session('auth_num')>3) session(null);
        if(session('auth_str')){
            $data['mobile']=session('auth_mobile');
            $data['yzm']=session('auth_str');
            $rs=D('Message')->message_action('forgot_pwd_verify_phone',$data);
            if($rs){
                session('auth_num', session('auth_num')+1);
                echo 1;
            }else{
                echo 0;
            }
        }
    }

    function sendMobile(){
        if(!session('auth_num'))
            session('auth_num',1);
        else
            session('auth_num', session('auth_num')+1);
        if(session('auth_num')>5) session(null);
        if(session('auth_str')){
            $rs=sendMobileSms(session('auth_mobile'), str_replace('xxxxxx',session('auth_str'),C('GETPASSWORD')));;
            if($rs){
                echo 1;
            }else{
                echo 0;
            }
        }
    }

    //特价机票
    function cheap($type='json'){
        $data=D('Cheap')->getList();
        $this->zhou=$data['zhou'];
        $this->list=$data['list'];
        if($type=='json'){
            $cb = $_GET['callback'];
            echo $cb."({code:".json_encode( $this->list)."})";
        // echo json_encode($arr);
         }
    }


    //关闭窗口
    function close(){
        echo "<script type='text/javascript'>window.opener=null; window.open('', '_self', '');window.close(); </script>";
    }
    
    
    /*
     * 测试发短信
     */
    public function sendHesheng(){
    	
    	$data['code']= '123456';
        $data['product']= '品悦旅行网';
        $rs=sendMessage('13143061188','SMS_48355102',$data);
    	exit;
    	import('ORG.Message');
        $message= new Message();
        $re=$message->sendMessage('13143061188','SMS_48355102',$data);
    }
    

}

/**
 * 短信发送
 * @param string $mobile 手机号
 * @param array $paramArr 要发送信息
 * @param string $TemplateCode 短信模板的模板CODE
 */
//function sendMessage1($mobile,$templateCode, $paramArr) {
//  date_default_timezone_set("GMT");
//  //$message = urlencode($message);
//  session_start();
//  header("Content-type:text/html; charset=UTF-8");
//  $target = "https://sms.aliyuncs.com/?";
//  $dateTimeFormat = 'Y-m-d\TH:i:s\Z'; // ISO8601规范
//  $accessKeyId = 'LTAIZGUDcMxqTMuI';      // 这里填写您的Access Key ID
//  $accessKeySecret = '2txz7hVNh3mHfLWsJuJ0LmIdkbLOwb';  // 这里填写您的Access Key Secret
//  $ParamString=json_encode($paramArr);
//  $data = array(
//      // 公共参数
//      'SignName'=>'品悦旅行网',
//      'Format' => 'XML',
//      'Version' => '2016-09-27',
//      'AccessKeyId' => $accessKeyId,
//      'SignatureVersion' => '1.0',
//      'SignatureMethod' => 'HMAC-SHA1',
//      'SignatureNonce'=> uniqid(),
//      'Timestamp' => date($dateTimeFormat),
//      // 接口参数
//      'Action' => 'SingleSendSms',
//      'TemplateCode' => $templateCode,
//      'RecNum' => $mobile,
//      'ParamString' => $ParamString
//  );
//  // 计算签名并把签名结果加入请求参数
//  $data['Signature'] = computeSignature($data, $accessKeySecret);
//  $send = xml_to_array(https_request($target.http_build_query($data)));
//  file_put_contents("./APP/Runtime/Logs/message".date('Ymd').".log", date('Y-m-d H:i:s', time()) .':' . json_encode($send)."\r\n", FILE_APPEND);
//  return $send;
//}
//
//
//function https_request($url)
//{
//  $curl = curl_init();
//  curl_setopt($curl, CURLOPT_URL, $url);
//  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
//  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//  $data = curl_exec($curl);
//  if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
//  curl_close($curl);
//  return $data;
//}

//function percentEncode($str)
//{
//  // 使用urlencode编码后，将"+","*","%7E"做替换即满足ECS API规定的编码规范
//  $res = urlencode($str);
//  $res = preg_replace('/\+/', '%20', $res);
//  $res = preg_replace('/\*/', '%2A', $res);
//  $res = preg_replace('/%7E/', '~', $res);
//  return $res;
//}


//function computeSignature($parameters, $accessKeySecret)
//{
//  // 将参数Key按字典顺序排序
//  ksort($parameters);
//  // 生成规范化请求字符串
//  $canonicalizedQueryString = '';
//  foreach($parameters as $key => $value)
//  {
//      $canonicalizedQueryString .= '&' . percentEncode($key)
//          . '=' . percentEncode($value);
//  }
//  // 生成用于计算签名的字符串 stringToSign
//  $stringToSign = 'GET&%2F&' . percentencode(substr($canonicalizedQueryString, 1));
//  //echo "<br>".$stringToSign."<br>";
//  // 计算签名，注意accessKeySecret后面要加上字符'&'
//  $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
//  return $signature;
//}


//function xml_to_array($xml){
//  $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
//  if(preg_match_all($reg, $xml, $matches)){
//      $count = count($matches[0]);
//      for($i = 0; $i < $count; $i++){
//          $subxml= $matches[2][$i];
//          $key = $matches[1][$i];
//          if(preg_match( $reg, $subxml )){
//              $arr[$key] = xml_to_array( $subxml );
//          }else{
//              $arr[$key] = $subxml;
//          }
//      }
//  }
//  return @$arr;
//}
?>