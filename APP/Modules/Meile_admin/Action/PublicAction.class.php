<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

class PublicAction extends Action {
	// 检查用户是否登录
	protected function checkUser(){
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->assign('jumpUrl','Public/login');
			$this->error('没有登录');
		}
	}

    //ajax赋值扩展
    protected function ajaxAssign(&$result){
        $result['statusCode']  =  $result['status'];
        $result['navTabId']  =  $_REQUEST['navTabId'];
        $result['message']=$result['info'];
    }

	// 顶部页面
	public function top(){
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$model	=	M("Group");
		$list	=	$model->where('status=1')->getField('id,title');
		$this->assign('nodeGroupList',$list);
		$this->display();
	}
	// 尾部页面
	public function footer() {
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this->display();
	}
	// 菜单页面
	public function menu() {
        $this->checkUser();
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
            //显示菜单项
            $menu  = array();
           // if(isset($_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]])) {

                //如果已经缓存，直接读取缓存
                //$menu   =   $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]];
           // }else {
                //读取数据库模块列表生成菜单项
                $node    =   M("Node");
				$id	=	$node->getField("id");

			//	$where['level']=2;
				$where['status']=1;
			//	$where['pid']=$id;
                $list	=	$node->where($where)->field('id,name,group_id,pid,title,level')->order('sort asc')->select();

                 if(C('USER_AUTH_TYPE')==2) {
                     import('@.ORG.Util.RBAC');
                   //加强验证和即时验证模式 更加安全 后台权限修改可以即时生效
                   $accessList = RBAC::getAccessList($_SESSION[C('USER_AUTH_KEY')]);
                }else{
                    $accessList = $_SESSION['_ACCESS_LIST'];
                 }
                 foreach($list as $k=>$v){
                     $lists[$v['id']]=$v;
                 }
                 $this->assign('list',$lists);
           //  print_r($list);
                foreach($lists as $key=>$module){

                    if($module['level']==2){
                        if(isset($accessList[strtoupper(GROUP_NAME)][strtoupper($module['name'])]) || $_SESSION[C('ADMIN_AUTH_KEY')]) {
                            //设置模块访问权限
                            $module['access'] =  1;
                            $menu[$module['group_id']][$key]  = $module;
                        }
                    }elseif($module['level']==3){
                        if(isset($accessList[strtoupper(GROUP_NAME)][strtoupper($lists[$module['pid']]['name'])][strtoupper($module['name'])]) || $_SESSION[C('ADMIN_AUTH_KEY')]){
                            //设置模块访问权限
                            $module['access'] =  1;
                            $menu[$module['group_id']][$key]  = $module;
                        }
                    }

                }
            //    print_r($menu);
                //缓存菜单访问
                $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]]	=	$menu;
            //}
            if(!empty($_GET['tag'])){
                $this->assign('menuTag',$_GET['tag']);
            }
		//	print_r($menu);
			$groups=M("Group")->where(array('group_menu'=>"{$_GET['menu']}",'status'=>"1"))->order("sort desc,id desc")->select();
		//	dump($groups);
			$this->assign("groups",$groups);
            $this->assign('menu',$menu);
		}
	//	C('SHOW_RUN_TIME',false);			// 运行时间显示
	//	C('SHOW_PAGE_TRACE',false);
		$this->display();
	}
	
//	public function menu() {
//        $this->checkUser();
//        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
//            //显示菜单项
//            $menu  = array();
//            if(isset($_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]])) {
//
//                //如果已经缓存，直接读取缓存
//                $menu   =   $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]];
//            }else {
//                //读取数据库模块列表生成菜单项
//                $node    =   M("Node");
//				$id	=	$node->getField("id");
//				$where['level']=2;
//				$where['status']=1;
//				$where['pid']=$id;
//                $list	=	$node->where($where)->field('id,name,group_id,title')->order('sort asc')->select();
//                $accessList = $_SESSION['_ACCESS_LIST'];
//                foreach($list as $key=>$module) {
//                     if(isset($accessList[strtoupper(APP_NAME)][strtoupper($module['name'])]) || $_SESSION['administrator']) {
//                        //设置模块访问权限
//                        $module['access'] =   1;
//                        $menu[$key]  = $module;
//                    }
//                }
//                //缓存菜单访问
//                $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]]	=	$menu;
//            }
//            if(!empty($_GET['tag'])){
//                $this->assign('menuTag',$_GET['tag']);
//            }
//			//dump($menu);
//            $this->assign('menu',$menu);
//		}
//		C('SHOW_RUN_TIME',false);			// 运行时间显示
//		C('SHOW_PAGE_TRACE',false);
//		$this->display();
//	}

    // 后台首页 查看系统信息
    public function main() {
        $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
            'ThinkPHP版本'=>THINK_VERSION.'',
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
        $this->display();
    }


	// 用户登录页面
	public function login() {
		if(isset($_SESSION[C('USER_AUTH_KEY')]) or isset($_SESSION[C('ADMIN_AUTH_KEY')])) {
			$this->redirect('Index/index');
		}else{
            $this->display();
		}
	}

	public function index()
	{
		//如果通过认证跳转到首页
		redirect(__APP__);
	}

	// 用户退出
    public function logout()
    {
        if(isset($_SESSION[C('USER_AUTH_KEY')]) || isset($_SESSION[C('ADMIN_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION[C('ADMIN_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
            $this->assign("jumpUrl",__URL__.'/login/');
            $this->success('退出成功！');
        }else {
            session_destroy();
            $this->error('已经退出！');
        }
    }

	// 登录检测
	public function checkLogin() {
		if(empty($_POST['username'])) {
			$this->error('帐号错误！');
		}elseif (empty($_POST['password'])){
			$this->error('密码必须！');
		}elseif (empty($_POST['verify'])){
			$this->error('验证码必须！');
		}
        //生成认证条件
        $map            =   array();
		// 支持使用绑定帐号登录
		$map['username']	= $_POST['username'];
        $map["status"]	=	array('gt',0);
		if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误！');
		}
		import ( '@.ORG.Util.RBAC' );
        $authInfo = RBAC::authenticate($map);

        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }else {
            $user=D('User');
            $username=trim(I('username'));
            $salt=$user->getsalt($username);
            $password=I('password');
            $password=hashPassword($password,$salt);
            $userinfo=$user->where("username='$username' and password='$password'")->find();
            if(!$userinfo){
            	$this->error('密码错误！');
            }
            session('uid',$authInfo['id']);
            session('user_type','2');
            $_SESSION[C('USER_AUTH_KEY')]	=$authInfo['id'];
            $_SESSION['email']	=	$authInfo['email'];
            $_SESSION['loginUserName']		=	$authInfo['nickname'];
            $_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
			$_SESSION['login_count']	=	$authInfo['login_count'];
            if($authInfo['id']==C('ADMIN_ID')){
            	$_SESSION['administrator']	=	true;
            }

            //保存登录日志
            $log['vc_operation']="用户登录：登录成功！";
			$log['vc_module']="系统管理";
			$log['creator_id']=$authInfo['id'];
			$log['creator_name']=$authInfo['username'];
			$log['vc_ip']=get_client_ip();
			$log['createtime']=time();
			M("Log")->add($log);
            //保存登录信息
			$User	=	M('User');
			$ip		=	get_client_ip();
			$time	=	time();
            $data = array();
			$data['id']	=	$authInfo['id'];
			$data['last_login_time']	=	$time;
			$data['login_count']	=	array('exp','login_count+1');
			$data['last_login_ip']	=	$ip;
			$User->save($data);
			// 缓存访问权限
            RBAC::saveAccessList();
            if(I('password')=='123456'){
                $this->success('你的密码为初始密码，请更改！','__GROUP__#Public-password');
            }else{
	    		$this->success('登录成功！');
            }
		}
	}
    // 更换密码
    public function changePwd()
    {
		$this->checkUser();
        //对表单提交处理进行处理或者增加非表单数据
		if(md5($_POST['verify'])	!= $_SESSION['verify']) {
			$this->error('验证码错误！');
		}
        if(I('repassword') != I('password')){
            $this->error('新密码与确认新密码不一致！');
        }
		$map	=	array();
        $User    =   D("User");

        if(isset($_POST['username'])) {
            $map['username']	 =	 $_POST['username'];
        }elseif(isset($_SESSION[C('USER_AUTH_KEY')])) {
            $map['id']		=	$_SESSION[C('USER_AUTH_KEY')];
        }
        $salt=$User->getsalt(getUid());

        $map['password']=  hashPassword(I('oldpassword'),$salt);
    //    print_r(getUid()) ;
        //检查用户
        if(!$User->where($map)->field('id')->find()) {
            $this->error('旧密码不符或者用户名错误！');
        }else {
            $User->salt=generateSalt(); // 设置salt字段值
			$User->password	=hashPassword(I('password'),$User->salt);//# 对密码进行md5 混合加密
			$User->save();
			$this->success('密码修改成功！');
         }
    }

	public function profile() {
		$this->checkUser();
		$User	 =	 M("User");
		$vo	=	$User->getById($_SESSION[C('USER_AUTH_KEY')]);
		$this->assign('vo',$vo);
		$this->display();
	}

	public function verify(){
		$type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Util.Image");
        Image::buildImageVerify(4,1,$type);
    }
// 修改资料
	public function change(){
		$this->checkUser();
		$User	 =	 D("User");
		if(!$User->create()) {
			$this->error($User->getError());
		}
		$result	=	$User->save();
		if(false !== $result) {
			$this->success('资料修改成功！');
		}else{
			$this->error('资料修改失败!');
		}
	}

    function database(){

      //  $this->display();
    }

    //js url 检测
    function jsCheckUrl(){
        $furl=$_GET['u'];
        if(!$furl)
            return;
        $u=explode('-',$furl);
        if(count($u)==1){
            $group=GROUP_NAME;
            $module=$u[0];
            $action='Index';
        }elseif(count($u)==2){
            $group=GROUP_NAME;
            $module=$u[0];
            $action=$u[1];
        }elseif(count($u)==3){
            $group=$u[0];
            $module=$u[1];
            $action=$u[2];
        }
       $node=M('node')->select();
       $list_tree=list_to_tree($node,'0','id','pid');
       foreach($list_tree as $gk=>$gv){
           if($gv['name']!=$group) continue;
               foreach($gv['_child'] as $mv){
                   if($mv['name']!=$module) continue;
                   if(count($u)==1){ $re=$mv;continue;}
                       foreach($mv['_child'] as $av){
                           if($av['name']==$action){
                                $re=$av;
                           }
                       }
               }
       }
         if(!$re) return false;
         $re['menu']=M('group')->where('id='.$re['group_id'])->getField('group_menu');
         $re['url']=U("$group/$module/$action");
         $this->ajaxReturn($re);
    }

    function upload(){
       /*!
         ************************************本文件用途为：编辑器上传本地图片********************************************
         * upload demo for php
                           * @requires xhEditor
                           *
         * @author Yanis.Wang<yanis.wang@gmail.com>
         * @site http://xheditor.com/
         * @licence LGPL(http://www.opensource.org/licenses/lgpl-license.php)
         *
         * @Version: 0.9.6 (build 111027)
         *
         * 注1：本程序仅为演示用，请您务必根据自己需求进行相应修改，或者重开发
                * 注2：本程序特别针对HTML5上传，加入了特殊处理
                */
        header('Content-Type: text/html; charset=UTF-8');

        $inputName='filedata';//表单文件域name


        $attachDir ='Public/Uploads';//上传文件保存路径，结尾不要带/


        $dirType=1;//1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
        $maxAttachSize=2097152;//最大上传大小，默认是2M
        $upExt='txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid';//上传扩展名
        $msgType=2;//返回上传参数的格式：1，只返回url，2，返回参数数组
        $immediate=isset($_GET['immediate'])?$_GET['immediate']:0;//立即上传模式，仅为演示用
        ini_set('date.timezone','Asia/Shanghai');//时区


        $err = "";
        $msg = "''";
        $tempPath=$attachDir.'/'.date("YmdHis").mt_rand(10000,99999).'.tmp';
        $localName='';


        if(isset($_SERVER['HTTP_CONTENT_DISPOSITION'])&&preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'],$info)){//HTML5上传
            file_put_contents($tempPath,file_get_contents("php://input"));
            $localName=urldecode($info[2]);


        }
        else{//标准表单式上传
            $upfile=@$_FILES[$inputName];
            if(!isset($upfile))$err='文件域的name错误';
            elseif(!empty($upfile['error'])){
                switch($upfile['error'])
                {
                    case '1':
                        $err = '文件大小超过了php.ini定义的upload_max_filesize值';
                        break;
                    case '2':
                        $err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
                        break;
                    case '3':
                        $err = '文件上传不完全';
                        break;
                    case '4':
                        $err = '无文件上传';
                        break;
                    case '6':
                        $err = '缺少临时文件夹';
                        break;
                    case '7':
                        $err = '写文件失败';
                        break;
                    case '8':
                        $err = '上传被其它扩展中断';
                        break;
                    case '999':
                    default:
                        $err = '无有效错误代码';
                }
            }
            elseif(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none')$err = '无文件上传';
            else{
                move_uploaded_file($upfile['tmp_name'],$tempPath);
                $localName=$upfile['name'];
            }
        }

        if($err==''){
            $fileInfo=pathinfo($localName);
            $extension=$fileInfo['extension'];
            if(preg_match('/^('.str_replace(',','|',$upExt).')$/i',$extension))
            {
                $bytes=filesize($tempPath);
                if($bytes > $maxAttachSize)$err='请不要上传大小超过'.formatBytes($maxAttachSize).'的文件';
                else
                {
                    switch($dirType)
                    {
                        case 1: $attachSubDir = 'day_'.date('ymd'); break;
                        case 2: $attachSubDir = 'month_'.date('ym'); break;
                        case 3: $attachSubDir = 'ext_'.$extension; break;
                    }
                    $attachDir = $attachDir.'/'.$attachSubDir;
                    if(!is_dir($attachDir))
                    {
                        @mkdir($attachDir, 0777);
                        @fclose(fopen($attachDir.'/index.htm', 'w'));
                    }
                    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
                    $newFilename=date("YmdHis").mt_rand(1000,9999).'.'.$extension;
                    $targetPath = $attachDir.'/'.$newFilename;

                    rename($tempPath,$targetPath);
                    @chmod($targetPath,0755);

                    $targetPath=jsonString(U('/').$targetPath);
                    if($immediate=='1')$targetPath='!'.$targetPath;
                    if($msgType==1)$msg="'$targetPath'";
                    else $msg="{'url':'".$targetPath."','localname':'".jsonString($localName)."','id':'1'}";//id参数固定不变，仅供演示，实际项目中可以是数据库ID
                }
            }
            else $err='上传文件扩展名必需为：'.$upExt;

            @unlink($tempPath);
        }

        echo "{'err':'".jsonString($err)."','msg':".$msg."}";

    }


}
?>