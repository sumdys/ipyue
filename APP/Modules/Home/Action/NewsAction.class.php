<?php

class NewsAction extends Action{

    function index(){
		$this->display('News/company');
    }
	
	function company(){//公司新闻		
        $rs=D('News')->getlist(33,20);
        $this->page=$rs['page'];
		for($i=0;$i<3;$i++){
			if($rs['list'][$i]['is_hot'] !=0 ){
				$rs['list'][$i]['type']='<span style="color:#F00;margin-left:10px;">HOT</span>';
			}else{
				$published=$rs['list'][$i]['published'];				
				$day7=strtotime("+1week",$published);
				if($published<$day7){
					$rs['list'][$i]['type']='<span style="color:#F00;margin-left:10px;">NEW</span>';
				}
			}
		}
        $this->list=$rs['list'];		
		$this->title="公司新闻";
		$this->display();
    }
	
	function industry(){//行业新闻
        $rs=D('News')->getlist(34,20);
        $this->page=$rs['page'];        
		for($i=0;$i<3;$i++){
			if($rs['list'][$i]['is_hot'] !=0 ){
				$rs['list'][$i]['type']='<span style="color:#F00;margin-left:10px;">HOT</span>';
			}else{
				$published=$rs['list'][$i]['published'];				
				$day7=strtotime("+1week",$published);
				if($published<$day7){
					$rs['list'][$i]['type']='<span style="color:#F00;margin-left:10px;">NEW</span>';
				}
			}
		}
		
		$this->list=$rs['list'];
		$this->title="行业新闻";
		$this->display();	
	}	
	
	function media(){//媒体新闻
        $rs=D('News')->getlist(34,20);
        $this->page=$rs['page'];
		for($i=0;$i<3;$i++){
			if($rs['list'][$i]['is_hot'] !=0 ){
				$rs['list'][$i]['type']='<span style="color:#F00;margin-left:10px;">HOT</span>';
			}else{
				$published=$rs['list'][$i]['published'];				
				$day7=strtotime("+1week",$published);
				if($published<$day7){
					$rs['list'][$i]['type']='<span style="color:#F00;margin-left:10px;">NEW</span>';
				}
			}
		}		
        $this->list=$rs['list'];
		$this->title="媒体新闻";
		$this->display();	
	}
	
    //新闻内容
    function content(){
		$id=$_GET["_URL_"][2];
		$info=D('News')->where('id='.$id)->find();
		
		//广告图片
		$this->img=D('NewsImg')->limit(4)->order('rand desc,update_time desc')->select();	
		
		//总评论数
		$this->totleComment=D('NewsComment')->where('nid='.$id)->count();
		
		//参与人数
		$username=D('NewsComment')->where('nid='.$id)->field('username')->select();
		foreach($username as $k=>$v){
			$arr[]=$v['uid'];
		}
		if($arr != ''){
			$arr=array_unique($arr);
		} 
		$this->totleid=count($arr);
		
		//二维码
		$pic_url='./public/uploads/ad/er/'.$id.'.png';		
		if(!file_exists($pic_url)){				
			vendor('phpqrcode.qrlib');			
			$PNG_TEMP_DIR = './public/uploads/ad/er/';		
			$PNG_WEB_DIR  = './public/uploads/ad/er/';		
			if (!file_exists($PNG_TEMP_DIR)){
				mkdir($PNG_TEMP_DIR);  
			}			
			$data = 'http://www.aishangfei.com/news/content/id/'.$id;
			$errorCorrectionLevel = 'Q';
			$matrixPointSize = 4; 	
			$filename = $PNG_TEMP_DIR.$id.'.png';
			QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
		}
		
		$this->info=$info;
		$this->title=$info['title'];
		$this->display();
    }	
	
	//新闻评论
	function comment(){
		$id=I('id');
		
		//新闻内容
		$info=D('News')->where('id='.$id)->field('published,source,content,title')->find();
		$this->info=$info;	
		
		//总评论数
		$this->totleComment=D('NewsComment')->where('nid='.$id)->count();
		
		//参与人数
		$username=D('NewsComment')->where('nid='.$id)->field('username')->select();
		foreach($username as $k=>$v){
			$arr[]=$v['uid'];
		}
		if($arr != ''){
			$arr=array_unique($arr);
		} 
		$this->totleid=count($arr);
		
		//评论列表
		$comment=D('NewsComment')->where('nid='.$id)->order('time desc')->select();
		
		foreach($comment as $key=>$val){
			$m=D('Member')->where('id='.$val['uid'])->field('headimg')->find();
			$comment[$key]['headimg']=$m['headimg'];
		}
		$this->comment=$comment;
		
		//二维码
		$pic_url='./public/uploads/ad/er/'.$id.'.png';		
		if(!file_exists($pic_url)){				
			vendor('phpqrcode.qrlib');			
			$PNG_TEMP_DIR = './public/uploads/ad/er/';		
			$PNG_WEB_DIR  = './public/uploads/ad/er/';		
			if (!file_exists($PNG_TEMP_DIR)){
				mkdir($PNG_TEMP_DIR);  
			}			
			$data = 'http://www.aishangfei.com/news/industry/id/'.$id;
			$errorCorrectionLevel = 'Q';
			$matrixPointSize = 4; 	
			$filename = $PNG_TEMP_DIR.$id.'.png';
			QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
		}			
						
		$this->title='新闻评论';
		$this->display();		
    }

	//会员登录
	function login(){
		if(IS_AJAX){	
			//会员登录
			if(I('type') == 'hy'){
				$username=I('username');
				$userpassword=I('userpassword');
				$usercode=I('usercode');
				
				if(empty($username) || $username=="用户名/手机"){
					$this->error('请输入登录名');				
				}				
				if(!$userpassword){
					$this->error('请输入密码');
				}				
				if( C('VERIFY_CODE') && I('usercode','','md5') != session('verify')){
					$this->error( '验证码错误！');
				}				
				$salt=D('Member')->getSalt($username);
				 if(empty($salt)){					 
					$this->error('用户名不存在');
				}
				
				$password=hashPassword($userpassword,$salt);
				$userinfo=D('Member')->where("(username='$username' or mobile='$username') and password='$password'")->find();
				if(!empty($userinfo)){
					session('name',$userinfo['name']);
					session('uid',$userinfo['id']);
					session('username',$userinfo['username']);
					$rs=D('Member')->updateLogin();
					if($rs){
						//记录行为
						action_log('member_login', 'member', getUid(), getUid());
						//D('Member')->updateCookie(); // 用户信息写入Cookie
						$data['status']=1;
						$this->success($data);
					}
				}else{
					$this->error('密码不正确');
				}									
			}
			
			//游客登录
			if(I('type') == 'tourist'){
				$touristname=I('touristname');
				$touristcode=I('touristcode');
				if(empty($touristcode)){
					$this->error( '验证码不能为空！');
				}
				if( C('VERIFY_CODE') && I('touristcode','','md5') != session('verify')){
					$this->error( '验证码错误！');
				}					
				$salt=D('Member')->getSalt($touristname);
				if(!empty($salt)){					 
					$this->error('用户名已存在');
				}else{
					$data['status']=1;
					session('username',$touristname);
					$data['touristname']=$touristname;
					$this->success($data);				
				}			
			}
			
			if(session('username') != ''){				
				$data['status']=1;
				$this->success($data);				
			}else{
				$this->error();		
			}
			
		}		
	}
	
	//评论内容
	function commentContent(){
		if(IS_AJAX){
			$content=I('comment_cont');
			$id=I('news_id');
			$res=D('Comment')->wordFilter($content);//字符过滤
		
			if(!$res){			
				$data=array(
					'uid'     =>session('uid'),
					'username'=>session('username'),
					'nid'     =>$id,
					'content' =>$content,
					'time'    =>time() 
				);
				$re=D('NewsComment')->add($data);
				if($re){
					$data['status']=1;
					$this->success($data);
				}				
			}
		}	
	}	
	
    //新闻内容
    function info(){
		$id=$_GET["_URL_"][2];
		$info=D('News')->where('id='.$id)->find();
		
		if($info['cid'] ==33){
			$this->type='公司新闻';
			$this->href==__URL__.'/company';
		}
		if($info['cid'] ==34){
			$this->type='媒体新闻';
			$this->href=__URL__.'/media';
		}		
		if($info['cid'] ==36){
			$this->type='行业新闻';
			$this->href==__URL__.'/industry';
		}
		$this->img=D('NewsImg')->limit(4)->order('rand desc,update_time desc')->select();			
		$this->info=$info;
		$this->title=$info['title'];
		$this->display();
    }		
		
	 Public function Lists(){
        $alias=I('alias');
        if(is_numeric($alias)){
            $cid=$alias;
        }else{
            $where['alias']=$alias;
            $cid=D('Category')->where($where)->getField('cid');
            if(!$cid) R('Empty/_empty');
        }
        $rs=D('News')->getlist($cid,20);
        $this->info=$rs['info'];
        $this->page=$rs['page'];
        $this->list=$rs['list'];
        import('@.ORG.Category');
        $cat = new Category('category', array('cid','pid','name'));
        $this->path=$cat->getPath($cid);

        $this->title=$this->info['name'];
        $this->display('lists');
    }	

}