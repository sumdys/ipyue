<?php
/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 */
function time_format($time = NULL,$format='Y-m-d H:i'){
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}
/**
 * 密码二次加密
 *@param $password 密码
 *@param $salt 随机字符串
 *@return string 密码串
 */
function hashPassword($password,$salt)
{
    return md5($password.$salt);
}

/*
 * crypt 加密
 */
function crypt_encrypt($data){
    if(!$data) return false;
    import('ORG.Crypt.Crypt');
    $Crypt= new Crypt();
    $data=json_encode($data);
    $key=C('CRYPT_KEY')?C('CRYPT_KEY'):"666";
    $data=$Crypt->encrypt($data,$key,1);
    return $data;
}


/*
 * crypt 解密
 */
function crypt_decrypt($data){
    import('ORG.Crypt.Crypt');
    $Crypt= new Crypt();
    $key=C('CRYPT_KEY')?C('CRYPT_KEY'):"666";
    $data=$Crypt->decrypt($data,$key,1);
    $data=json_decode($data);
    return $data;
}

/**
 * 产生一个随机字符串
 *@return 微秒的当前时间
 */
function generateSalt(){
    return uniqid('',true);
}

function getUid(){
    return session("uid")?session("uid"):false;
}
/**
 * 格式化时间戳
 *@param $time 时间戳
 *@return string 时间戳对应的日期
 */
function getTime($time)
{
    return $time ? date('y-m-d H:i',$time) : '';
}

/**
 * 返回来路
 * @param int $host
 * @return bool|mixed
 */
function get_http_referer($host=0){
    $url=$_SERVER['HTTP_REFERER'];
    $parse=parse_url($url);
    if(isset($parse['host'])){
        if($host){
            return $parse['host'];
        }else{
            $parse['url']=$url;
            return $parse;
        }
    }else{
        return false;
    }
}

/**
+----------------------------------------------------------
 * 功能：字符串截取指定长度
+----------------------------------------------------------
 * @param string    $string      待截取的字符串
 * @param int       $len         截取的长度
 * @param int       $start       从第几个字符开始截取
 * @param boolean   $suffix      是否在截取后的字符串后跟上省略号
+----------------------------------------------------------
 * @return string               返回截取后的字符串
+----------------------------------------------------------
 */
function cutStr($str, $len = 100, $start = 0, $suffix = 1) {
    $str = strip_tags(trim(strip_tags($str)));
    $str = str_replace(array("\n", "\t"), "", $str);
    $strlen = mb_strlen($str);
    while ($strlen) {
        $array[] = mb_substr($str, 0, 1, "utf8");
        $str = mb_substr($str, 1, $strlen, "utf8");
        $strlen = mb_strlen($str);
    }
    $end = $len + $start;
    $str = '';
    for ($i = $start; $i < $end; $i++) {
        $str.=isset($array[$i])?$array[$i]:'';
    }
    return count($array) > $len ? ($suffix == 1 ? $str . "&hellip;" : $str) : $str;
}

function htmltojs($str){
    $re='';
    $str = trim($str);
    $str = str_replace("\t","",$str);
//    $str = str_replace(" ","",$str);
    $str = str_replace("'",'\'',$str);
    $str = str_replace('"','\"',$str);
//    $str = str_replace('/','\/',$str);
    $str=preg_split('/\r\n/',$str);
    for($i=0;$i<count($str);$i++){
           $re.="document.writeln(\"".$str[$i]."\");\r\n";
    }
    return $re;
 }

/*
 * 数字转大字
 */
function numToUpper($num=0){
    $arr=array("零","一","二","三","四","五","六","七","八","九");
    $array=str_split($num);
    $atr='';
    foreach($array as $val){
        $atr.=$arr[$val];
    }
    return $atr;
}
  //中文截取
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=false){
    if(function_exists("mb_substr")){
        if($suffix)
            return mb_substr($str, $start, $length, $charset)."...";
        else
            return mb_substr($str, $start, $length, $charset);
    }elseif(function_exists('iconv_substr')) {
        if($suffix)
            return iconv_substr($str,$start,$length,$charset)."...";
        else
            return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}

/**
*短信平台
*$mobile 手机号
* $contents  内容
* $restrict  发送时间范围限制
* http://kegoo.hss100.cn/Interface/Send.aspx?CorpID=*&Pwd=*&Mobile=*&Content=*&Cell=*&SendTime=*
*
**/

function sendMobileSms($mobile,$contents,$restrict=null,$fh=0){
	if(empty($mobile)){
		return false;
	}

    //发送限制
    if($restrict){
          $sms=D('Mobilesms');
          $time=$restrict>10?time()-$restrict:time()-59;
          $rs=$sms->where("mobile=$mobile and sent_time>$time")->count();
          if($rs){
              return false;
          }
    }

	if(is_array($mobile)){
		$mobile=implode(',',$mobile);
	}
		
	$flag = 0;
	//要post的数据
	$SMS_SIGN=C('SMS_SIGN');
	$argv = array(
		'CorpID'=>C('SMS_SN'), ////替换成您自己的序列号
		'Pwd'=>C('SMS_PWD'), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
		'Mobile'=>$mobile,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
		'Content'=>iconv( "UTF-8", "gb2312//IGNORE" ,"$SMS_SIGN $contents"),//短信内容
		'Cell'=>'',
		'SendTime'=>''//定时时间 格式为2011-6-29 11:09:21
	);
	//构造要post的字符串
	$params='';
	foreach ($argv as $key=>$value){
		if ($flag!=0){
			$params .= "&";
			$flag = 1;
		}
		$params.= $key."="; $params.= urlencode($value);
		$flag = 1;
	}
	$length = strlen($params);
	//创建socket连接
	$fp = fsockopen("kegoo.hss100.cn",80,$errno,$errstr,10) or exit($errstr."--->".$errno);
	//构造post请求的头
	$header = "GET /Interface/Send.aspx HTTP/1.1\r\n";
	$header .= "Host:kegoo.hss100.cn\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: ".$length."\r\n";
	$header .= "Connection: Close\r\n\r\n";
	//添加post的字符串
	$header .= $params."\r\n";
	//发送post的数据
	fputs($fp,$header);
	$inheader = 1;
	while (!feof($fp)) {
		$line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据
		if ($inheader && ($line == "\n" || $line == "\r\n")) {
			$inheader = 0;
		}
		if ($inheader == 0) {
			// echo $line;
		}
	}
	//<string xmlns="http://tempuri.org/">-5</string>
	$line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
	$line=str_replace("</string>","",$line);
	$result=explode("-",$line);
	
	$sms=D('Mobilesms');
	$arr['mobile']=$mobile;
	$arr['content']=$contents;
	$arr['ip']=get_client_ip();
	$arr['sent_time']=time();
	$arr['source']=$_SERVER['REQUEST_URI'];
	$arr['return_var']=$line;
	
	if($sms->create($arr)){			
	   $rs=$sms->add();	
	}	
	
	if($fh==1){
	   if(count($result)>1)
			echo '发送失败返回值为:'.$line;
		else
			echo '发送成功 返回值为:'.$line;
	}else{
		if(count($result)>1)
			return false;
		 else
			 return true;
	}

}

/**邮件发送
 * @param $address  地址
 * @param $title   标题
 * @param $message  内容
 * @return bool
 */
function sendMail($address,$title,$message){
    vendor('PHPMailer.class#phpmailer');
    $mail=new PHPMailer();
    // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();
    // 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
    $mail->CharSet='UTF-8';
    // 添加收件人地址，可以多次使用来添加多个收件人
    $mail->AddAddress($address);
    // 设置邮件正文
    $mail->Body=$message;
    // 设置邮件头的From字段。
    $mail->From=C('SMTP_USERMAIL');
    // 设置发件人名字
    $mail->FromName=C('SMTP_FROM_NAME');
    // 设置邮件标题
    $mail->Subject=$title;
    // 设置SMTP服务器。
    $mail->Host=C('SMTP_SERVER');
    $mail->Port =C('SMTP_SERVERPORT');
    // 设置为"需要验证"
    $mail->SMTPAuth=true;
    // 设置用户名和密码。
    $mail->Username=C('SMTP_USER');
    $mail->Password=C('SMTP_PASS');
	
	$mail->IsHTML();
    // 发送邮件。
    return($mail->Send());
}

/**
 * 配置文件操作(修改);
update_config("./2.php", "name", "admin");
 * */ 
function update_config($file,$key,$value=''){
    if(!file_exists($file)) return false;
    $str = file_get_contents($file);
    $str2="";
    function update($str,$key,$value){
        if(is_int($value)){
            $str = preg_replace("/[\'\"]".$key."[\'\"]\s*=>\s*(.*)/","'$key'=>$value,",$str);
        }
        else{
            $str= preg_replace("/[\'\"]".$key."[\'\"]\s*=>\s*(.*)/","'$key'=>'$value',",$str);
        }
        return $str;
    }
    if(is_array($key)){
        foreach($key as $k=>$v){
            $str=update($str,$k,$v);
        }
        file_put_contents($file, $str);
        return $str;
    }

    $str2=update($str,$key,$value);
    file_put_contents($file, $str2);
    return $str2;
}


//自动检测内容是编码进行转换 get_encoding($data,"GB2312");
function get_encoding($data,$to="UTF-8")
{
    if(empty($data)) return false;
    $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
    $encoded = mb_detect_encoding($data, $encode_arr);
    $data = mb_convert_encoding($data,$to,$encoded);
    return $data;
}

function get_cur_url($type=0){
    $url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if($type==1){
        return urlencode($url);
    }else{
        return $url;
    }
}

function selectMobileNum(){   //查短信余额
    $flag = 0;
    $argv = array(
        'sn'=>C('SMS_SN'), ////替换成您自己的序列号
        'pwd'=>C('SMS_PWD'), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
    );
    $params="";//构造要post的字符串
    foreach ($argv as $key=>$value) {
        if ($flag!=0) {
            $params .= "&";
            $flag = 1;
        }
        $params.= $key."="; $params.= urlencode($value);
        $flag = 1;
    }
    $length = strlen($params);
    $fp = fsockopen("sdk2.zucp.net",80,$errno,$errstr,10) or exit($errstr."--->".$errno);
    $header = "POST /webservice.asmx/GetBalance HTTP/1.1\r\n";
    $header .= "Host:sdk2.zucp.net\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: ".$length."\r\n";
    $header .= "Connection: Close\r\n\r\n";  //添加post的字符串
    $header .= $params."\r\n"; //发送post的数据
    fputs($fp,$header);
    $inheader = 1;
    while (!feof($fp)) {
        $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据
        if ($inheader && ($line == "\n" || $line == "\r\n")) {
            $inheader = 0;
        }
    }
    $line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
    $line=str_replace("</string>","",$line);
    $result=explode("-",$line);
    if(count($result)>1)
        return '发送失败返回值为:'.$line;
    else
        return '剩余:'.$line."条";
}

/**
+-----------------------------------------------------------------------------------------
 * 删除目录及目录下所有文件或删除指定文件
+-----------------------------------------------------------------------------------------
 * @param str $path   待删除目录路径
 * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
+-----------------------------------------------------------------------------------------
 * @return bool 返回删除状态
+-----------------------------------------------------------------------------------------
 */
function delDirAndFile($path, $delDir = FALSE) {
    $handle = opendir($path);
    if ($handle){
        while (false !== ( $item = readdir($handle) )) {
            if ($item != "." && $item != "..")
                is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
        }
        closedir($handle);
        if ($delDir)
            return rmdir($path);
    }else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return FALSE;
        }
    }
}
/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list,$field, $sortby='asc') {
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

//查找分类下的子分类
function list_to_tree($list,$root=0,$pk='cid',$pid = 'pid',$child = '_child'){
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[$list[$key][$pk]] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][$list[$key][$pk]] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}
/*
 * 判断是是否为移动端访问
 */
function is_mobile(){
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
    $is_mobile = false;
    foreach ($mobile_agents as $device) {
        if (stristr($user_agent, $device)) {
            $is_mobile = true;
            break;
        }
    }
    return $is_mobile;
}

/*
 * 对象转数组
 */
function object_to_array($obj)
{
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val)
    {
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

/**
 * 秒转时间
 * @param $sec
 * @return string
 */
function sec2time($sec,$show='s'){
    if($show=='i'){
        $sec=$sec*60;
    }

    if($sec<60) return $sec.'秒';
    $sec = round($sec/60);
    if ($sec >= 60){
        $hour = floor($sec/60);
        $min = $sec%60;
        $res = $hour.'小时';
        $min != 0  &&  $res .= $min.'分';
    }else{
        $res = $sec.'分钟';
    }
    return $res;
}


/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $user_id 执行行为的用户id
 * @return boolean
 * @author huajie <banhuajie@163.com>
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null,$md=null){

    //参数检查
    if(empty($action) || empty($model)){
        return '参数不能为空';
    }
    if(empty($record_id)){
        $record_id=get_client_ip();
    }
    if(empty($user_id)){
        $user_id = get_client_ip();
    }

    //查询行为,判断是否执行
    $action_info = M('Action')->getByName($action);
    if($action_info['status'] != 1){
        return '该行为被禁用或删除';
    }

    //插入行为日志
    $data['action_id']      =   $action_info['id'];
    $data['user_id']        =   $user_id;
    $data['action_ip']      =   ip2long(get_client_ip());
    $data['model']          =   $model;
    $data['record_id']      =   $record_id;
    $data['create_time']    =   NOW_TIME;

    //解析日志规则,生成日志备注
    if(!empty($action_info['log'])){
        if(preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)){
            $log['user']    =   $user_id;
            $log['record']  =   $record_id;
            $log['model']   =   $model;
            $log['time']    =   NOW_TIME;
            $log['url']   =   $_SERVER['REQUEST_URI'];
            $log['post']   =  http_build_query($_POST);
            $log['this']   =  http_build_query($md);
            $log['data']    =   array('user'=>$user_id,'model'=>$model,'record'=>$record_id,'time'=>NOW_TIME);
            foreach ($match[1] as $value){
                $param = explode('|', $value);
                if(isset($param[1])){
                    $replace[] = call_user_func($param[1],$log[$param[0]]);
                }else{
                    $replace[] = $log[$param[0]];
                }
            }
            $data['remark'] =   str_replace($match[0], $replace, $action_info['log']);
        }else{
            $data['remark'] =   $action_info['log'];
        }
    }else{
        //未定义日志规则，记录操作url
        $data['remark']     =   '操作url：'.$_SERVER['REQUEST_URI'] ;
    }
    $post=!isset($_POST['password'])?http_build_query($_POST):'隐私';
    $data['request'] = 'url：'.$_SERVER['REQUEST_URI']." | post:".$post;
    $data['remark']=$data['remark'];

    M('ActionLog')->add($data);

    if(!empty($action_info['rule'])){
        //解析行为
        $rules = parse_action($action, $user_id);

        //执行行为
        $res = execute_action($rules, $action_info['id'], $user_id);
    }

    if($md && !empty($action_info['rule'])){

    }
}

/**
 * 解析行为规则
 * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *              field->要操作的字段；
 *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 * @param string $action 行为id或者name
 * @param int $self 替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author huajie <banhuajie@163.com>
 */
function parse_action($action = null, $self){
    if(empty($action)){
        return false;
    }

    //参数支持id或者name
    if(is_numeric($action)){
        $map = array('id'=>$action);
    }else{
        $map = array('name'=>$action);
    }

    //查询行为信息
    $info = M('Action')->where($map)->find();
    if(!$info || $info['status'] != 1){
        return false;
    }

    //解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
    $rules = $info['rule'];
    $rules = str_replace('{$self}', $self, $rules);
    $rules = explode(';', $rules);
    $return = array();
    foreach ($rules as $key=>&$rule){
        $rule = explode('|', $rule);
        foreach ($rule as $k=>$fields){
            $field = empty($fields) ? array() : explode(':', $fields);
            if(!empty($field)){
                $return[$key][$field[0]] = $field[1];
            }
        }
        //cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
        if(!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])){
            unset($return[$key]['cycle'],$return[$key]['max']);
        }
    }

    return $return;
}

/**
 * 执行行为
 * @param array $rules 解析后的规则数组
 * @param int $action_id 行为id
 * @param array $user_id 执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author huajie <banhuajie@163.com>
 */
function execute_action($rules = false, $action_id = null, $user_id = null){
    if(!$rules || empty($action_id) || empty($user_id)){
        return false;
    }

    $return = true;
    foreach ($rules as $rule){

        //检查执行周期
        $map = array('action_id'=>$action_id, 'user_id'=>$user_id);
        $map['create_time'] = array('gt', NOW_TIME - intval($rule['cycle']) * 3600);
        $exec_count = M('ActionLog')->where($map)->count();
        if($exec_count > $rule['max']){
            continue;
        }

        //执行数据库操作
        $Model = M(ucfirst($rule['table']));
        $field = $rule['field'];
        $res = $Model->where($rule['condition'])->setField($field, array('exp', $rule['rule']));

        if(!$res){
            $return = false;
        }
    }
    return $return;
}

//----------------------------------------------2014.2.12
//在线交易订单支付处理函数
//函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
//返回值：如果订单已经成功支付，返回true，否则返回false；
function checkorderstatus($ordid){
    $Ord=D('PayOrder');
    $ordstatus=$Ord->where('id='.$ordid)->getField('status');
    if($ordstatus==1){
        return true;
    }else{
        return false;    
    }
}

//处理订单函数
//更新订单状态，写入订单支付后返回的数据
function orderhandle($parameter){
    $ordid=$parameter['out_trade_no'];
    $data['id']=$ordid;
    $data['update_time']   =time();
	$data['data_json']=json_encode($parameter);
    $data['status']       =1;
    $PayOrder=D('PayOrder');
    $rs= $PayOrder->find($ordid);
    if($rs){
        $PayOrder->where('id='.$ordid)->save($data);
    }else{
        $PayOrder->add($data);
    }	

    $order_info=object_to_array(json_decode($rs['order_info']));
    foreach($order_info as $val){
		$orderDB = D('AsmsOrder');	
		$wh['ddbh']=$val['ddbh'];
        $orderDB->where($wh)->setField('zf_fkf','1');
     	$orderDB->orderPay($val['ddbh'],ASMSUID,$val['yfje'],$val['xjj'],$ordid,1,$rs['remark']);
    }
}

/**
 *	多维数组 合并
 *	支持 参数 和 array_merge 一样  2个参数以上 后面覆盖前面的
 *	返回值 数组
 **/
function array_merge_multi() {
    $args = func_get_args();

    if ( !isset( $args[0] ) && !array_key_exists( 0, $args ) ) {
        return array();
    }

    $arr = array();
    foreach ( $args as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $k => $v ) {
                if ( is_array( $v ) ) {
                    if ( !isset( $arr[$k] ) && !array_key_exists( $k, $arr ) ) {
                        $arr[$k] = array();
                    }
                    $arr[$k] = array_merge_multi( $arr[$k], $v );
                } else {
                    $arr[$k] = $v;
                }
            }
        }
    }
    return $arr;
}

/*
 * 判断多维数组是否存在某个值
 */
function deep_in_array($value, $array) {
    foreach($array as $item) {
        if(!is_array($item)) {
            if ($item == $value) {
                return true;
            } else {
                continue;
            }
        }
        if(in_array($value, $item)) {
            return true;
        } else if(deep_in_array($value, $item)) {
            return true;
        }
    }
    return false;
}


function jsonString($str)
{
    return preg_replace("/([\\\\\/'])/",'\\\$1',$str);
}
function formatBytes($bytes) {
    if($bytes >= 1073741824) {
        $bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
    } elseif($bytes >= 1048576) {
        $bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
    } elseif($bytes >= 1024) {
        $bytes = round($bytes / 1024 * 100) / 100 . 'KB';
    } else {
        $bytes = $bytes . 'Bytes';
    }
    return $bytes;
}

/*
 * 验证手机号
 * @param string $mobile 手号号
 */
function verfy_mobile($mobile) {
    return (preg_match('/^1[34578][0-9]{9}$/', $mobile)) ? true : false;
}


function aaa(){
    echo 2222;exit;
}


/**
 * 短信发送
 * @param string $mobile 手机号
 * @param array $paramArr 要发送信息
 * @param string $TemplateCode 短信模板的模板CODE
 */
function sendMessage($mobile,$templateCode, $paramArr) {
    date_default_timezone_set("GMT");
    //$message = urlencode($message);
    session_start();
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


/*
 * 订单状态
 * @param int state 订单状态
 */
function getOrderState($state){
    $str='';
    switch($state){
        case 0:
            $str='未支付';
            break;
        case 1:
            $str='已支付';
            break;
        case 2:
            $str='已取消';
            break;
    }
    return $str;
}

/*
 *路线类型
 * @param int type 路线类型值
 */
function getLineType($type){
    $str='';
    switch($type){
        case 0:
            $str='短线';
            break;
        case 1:
        case 2:
            $str='自由行';
            break;
        case 3:
        case 4:
            $str='别墅客栈';
            break;
        case 5:
        case 6:
            $str='邮轮';
            break;
    }
    return $str;
}
?>