<?php
class IniAction extends Action{
	private $userinfo; //用户信息
    public function _initialize(){

        if(MODULE_NAME=='Adviser'){
            if($_GET['company']>1){
                unset($_GET['department']);
            }
            if(!IS_AJAX ){
                unset($_GET['department']);
            }
        }

        $member=D('Member');
		if(!session('uid')){ //cookie 登陆  取得用户数据
			if(cookie('uid') && cookie('salt')){
                $auth=crypt_decrypt(cookie('salt'));
                if(!isset($auth['salt']) || $auth['expires']<time()){
                    cookie('uid',null);  cookie('salt',null);
                }
                $userInfo=$member->UserInfo(cookie('uid'));
                if($userInfo){
                    if($userInfo['salt']==$auth['salt']){
                        session('uid',cookie('uid'));
                        $this->userInfo=$userInfo;
                        define('ASMSUID',$userInfo['asms']['hyid']);//加入asms id
                        D('Member')->updateLogin();//更新登陆
                    }
                }else{
                    cookie('uid',null);  cookie('salt',null);
                }
			}
		}elseif(session('uid')){  //取得用户数据
            $this->userinfo=$member->UserInfo(session('uid'));
            cookie('uid',session('uid'));
		}
        $this->userInfo=$this->userinfo;
        $this->assign('userInfo', $this->userinfo);

        if(isset($_GET['mobile'])){
            cookie('ts_refer','mobile');
        }elseif(cookie('ts_refer')=='mobile'){
            cookie('ts_refer','mobile');
        }

       // $this->restrict();//未登录不能访问

        //当没有对应客服,跳到客服选择页
        if($this->userinfo && !$this->userinfo['user_id'] && MODULE_NAME!='Index'){
            if(MODULE_NAME!='Adviser'){
                if(ACTION_NAME!='setkf'){
                //    redirect(U('/Index'));
                }
            }
        }

		$this->assign('userinfo',$this->userinfo);//用户信息输出到模板

        if(MODULE_NAME == "Jifen"){
            $this->jifen();
        }
	}

    //未登录不能访问
	function restrict(){
		$array=array('register','login','getpassword');//不需要登陆可访问的function
		if(MODULE_NAME == "Member"){
			if(!in_array(ACTION_NAME,$array)){
				if(!session('uid')){
                    $u=isset($_GET["u"])?$_GET["u"]:get_cur_url(1);
				//	$this->redirect('member/login',"u='$cururl'");
                    redirect(U('member/login')."/?u=$u");
					exit;
				}
			}else{
				if(session('uid')){
					$this->success("你已经登陆",U('member/index'));
					exit;
				}
			}
		}
	}

    //积分测边栏数据
    function jifen(){
		$cid=I('cid');
        $mall=D('Mall');
		import('@.ORG.Category');
        $cat = new Category('mall_category', array('cid','pid','name'));
        $this->path=$cat->getPath($cid);			
        $this->category=M('mall_category')->cache(true)->select();
        $clist=list_to_tree($this->category); 	
		$this->assign('clist',$clist);
	
		//积分兑换排行榜		
		$where['status']=1;
		$where['type']=0;
		$this->jifen=$mall->cache(true)->field('id,title,img,jifen')->where($where)->order("sales DESC")->limit(5)->select();
		//爱钻兑换排行榜
		$where['status']=1;
		$where['type']=1;
		$this->aizuan=$mall->cache(true)->field('id,title,img,jifen')->where($where)->order("sales DESC")->limit(5)->select();
		
		//用户信息
		$points=D('Points');
		$where['member_id']=getUid();
		$where['type2']=0;		
		$this->jfpoints=$points->where($where)->sum('points');
		if($this->jfpoints <= 0){
			$this->jfpoints = 0;
		}
		$where['type2']=1;		
		$this->azpoints=$points->where($where)->sum('points');
		if($this->azpoints<= 0){
			$this->azpoints = 0;
		}

        $category2=M('mall_category')->cache(true)->order('sort desc')->select();
        $category_left=list_to_tree($category2,55);    
        $this->assign('category_left',$category_left);
        $wh['status']=1;
        $this->sales=$mall->cache(true)->where($wh)->order("sales desc ")->limit("10")->select();
		
    }
}	
?>