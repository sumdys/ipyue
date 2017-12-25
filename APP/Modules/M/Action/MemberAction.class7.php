<?php
// 会员中心控制器
class MemberAction extends IniAction {
	//登录
    function login(){
        if($_POST['act']=='login'){
            C('VERIFY_CODE',0);
            $member=D('Member');
            $rs=$member->login();
            if($rs===true){
                if($_POST['check']) $this->updateCookie(); // 用户信息写入Cookie
                $url=isset($_GET['u'])?$_GET['u']:U("/member/index");
                $this->success('登陆成功',$url);
            }else{
                $this->error($rs);
            }
        }else{
            $this->display();
        }
    }
	
    //退出
    function out(){		
        session_unset();
        session_destroy();
        cookie('uid',null);
        $host=$_SERVER['SERVER_NAME'];
        cookie("uid",null,array('domain'=>$host));
        cookie("uid",null,array('domain'=>'www.aishangfei.net'));
        cookie("uid",null,array('domain'=>'/'));
        cookie('salt',null);
        //	$this->redirect('/member/login');
        $this->success("成功退出",U('/index'));
    }
	
    // 用户信息写入Cookie
    function updateCookie(){
        if(getUid()){
            $cookie_time=3600*24*14;
            $salt=D('Member')->getsalt(getUid(),1);
            cookie('uid',getUid(),$cookie_time);
            cookie('salt',md5($salt),$cookie_time);
        }
    }	
	
	//注册
	public function register(){
        //邀请注册人id保存到cookie
        if(I('referee_id')) cookie('referee_id',I('referee_id'),3600*24*7);
        //提交数据处理
        if($_POST['act']=='register'){
            $rs=D('Member')->regCheckName(I('name'));
            if($rs!==true){
                $this->error($rs);
            }
            if(D('Member')->checkName(I('phone'))){
                $this->error('手机号已存在');
            }
            session('reg_name',I('name'));
            session('reg_phone',I('phone'));
            session('reg_password',I('password'));
            if(IS_AJAX) $this->success('验证手机',U('/Member/phoneverify'));
            $this->display('phoneverify');
        }else{
            $this->title="会员注册";
            $this->display();
        }
    }

	//注册第二步
	public function phoneverify(){
        if(!session('reg_phone')) return false;
        if($_POST['act']=='phoneverify'){
            if(I('verify')==session('auth_reg_str')){
                session('reg_mobile',session('auth_reg_mobile'));
            }else{
                $this->error('验证码不正确');
            }
            C('VERIFY_CODE',0);
            $_POST['username']= session('reg_name');
            $_POST['mobile']= session('reg_phone');
            $_POST['password']= session('reg_password');
            $_POST['source']='mobile';
            $rs=D('Member')->register();
            if($rs===true){
                $data['name']=D('Member')->username;
                D('Message')->message_action('reg_success',$data);//发送信息
                $rs=D('User')->assignUserid();
                $this->success('完成',U('/member/index'));
            }else{
                $this->error($rs,U('/member/register'));
            }
        }else{
            $this->reg_phone=session('reg_phone');
            $this->title="验证手机";
            $this->display();
        }
    }  

	//1、主页 ok
    function index(){
        if($_GET['test']){
            $this->error(123);
        }
        if(session('uid')){
            $this->title="会员中心";
			$orderDB=D("TripOrder");
			$Member=D('Member');
			$uid['id']=getUid();
			$asmsId=$Member->field('asms_member_id')->where($uid)->find();
			$where['member_id']=array(array('eq',$uid['id']));
			//已取消
            $where['pay_state']=2;
            $common['cancel_count']=$orderDB->where($where)->count();
			//未支付
            $where['pay_state']=0;
            $common['pending_count']=$orderDB->where($where)->count();
			 //所有订单
            $where['pay_state']=array('gt',0);
            $common['process_count']=$orderDB->where($where)->limit($pagesize)->count();		
            $this->common=$common;//公共数据			
	
			
            //客服好评数
            $server=D("Member")->pjCount();
            $this->server=$server['server'];
            $this->serverImg=round($this->server*2);
            $this->sysMessageList=D('Message')->sysMessageList();
			
			
			//现金券总数
			$wh['member_id']=getUid();
			$wh['type2']=2;
			$this->overage=D('Points')->where($wh)->sum('points');
			if($this->overage<=0 && $this->overage==''){
				$this->overage=0;
			}
			
			//积分总数		
			$wh['type2']=0;	
			$this->totlejf=D('Points')->where($wh)->sum('points');
			if($this->totlejf<=0 && $this->totlejf==''){
				$this->totlejf=0;
			}
			//爱钻总数
			$wh['type2']=1;	
			$this->totleaz=D('Points')->where($wh)->sum('points');
			if($this->totleaz<=0 && $this->totleaz==''){
				$this->totleaz=0;
			}
			
			//账户安全等级
			$mail=D(CheckEmail);
			$w['member_id']=getUid();
			$mail=$mail->where($w)->find();
			if(!empty($mail)){									
				if($mail['is_check'] ==1){
					$this->Safety_level="高";
				}else{
					$this->Safety_level="中";
				}
			}else{
				$this->Safety_level="中";
			}
			
            $this->display();
        }else{
            $this->redirect('/Member/login');
        }
    }


	/*
	 * 所有订单
	 * 2017.4.22
	 *
	 */
	function orders(){
//		$_GET['status']);exit;
		$orderDB=D("TripOrder o");
		//给 第一个 未支付的订单 使用现金劵
		if($this->common['pending_count']>0){
			$wh['member_id']=getUid();
			$wh['type2']=2;
			$xjj=D('Points')->where($wh)->limit(1)->getField('points');
			if($xjj>1){ //有现金劵
				$where_xjj['ddzt']=array('not in',array('7','8'));
				$where_xjj['zf_fkf']=0;//未支付
				$where_xjj['hyid']=ASMSUID;
				$order_id=$orderDB->where($where_xjj)->limit(1)->order('ddbh')->getField('ddbh');
				if($order_id){ //有符合的订单
					$order_rs= $orderDB->format($orderDB->getOrderInfo($order_id));
					if($order_rs['xsj']>$xjj && $order_rs['xjj']<1){
						$dataUp['ddbh']=$order_rs['ddbh'];
						$dataUp['xjj']=$xjj;
						$orderDB->save($dataUp);
					}
				}
			}
		}

		$Member=D('Member');
		$uid['id']=getUid();
		$where['o.member_id']=$uid['id'];
		$list=$orderDB->field('o.id,o.order_num,o.freetour_id,o.total_price,o.num,o.mobile,o.create_time,o.remark,o.start_date,f.title,o.pay_state,f.line_type')->where($where)->order('create_time desc')->join('Left join asf_freetour f On o.freetour_id=f.id')->select();

		foreach($list as $k=>$v){
//			$v['hc_a'] = D("City")->getCity( $v['hc']);
//			$list[$k]['hc_n']=implode('-',$v['hc_a']);//航程
//			$list[$k]['jp_type']=$this->jptype($v['lx']);//机票类型
			$list[$k]['create_time']=date('Y-m-d H:i',$v['create_time']);
			$list[$k]['start_date']=date('Y-m-d',$v['start_date']);
			$list[$k]['price']=$v['total_price']/$v['num'];
			switch($v['pay_state']){
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
			$list[$k]['state']=$str;
			switch($v['line_type']){
				case 0:
					$str1='短线';
					break;
				case 1:
				case 2:
					$str1='自由行';
					break;
				case 3:
				case 4:
				$str1='别墅客栈';
					break;
				case 5:
				case 6:
				$str1='邮轮';
					break;
			}
			$list[$k]['type']=$str1;
		}
//		var_dump($list);exit;
		//待支付笔数
		$pending['count']=count($list);
		foreach($list as $val){
			$pending['price']+=$val['total_price'];
		}
		if($pending['price'] <0 || $pending['price']==''){
			$pending['price']=0;
		}
//		var_dump($list);exit;
		$this->pending=$pending;
		$this->list=$list;
		$this->title="所有订单";
		$this->display();
	}

	/* 2、我的订单 ok
	 * 待支付
	 */
	function ordersPending(){//待支付
        $orderDB=D("TripOrder o");
		$where['o.member_id']=getUid();
		$where['o.pay_state']=0;
		$list=$orderDB->field('o.id,o.order_num,o.freetour_id,o.total_price,o.num,o.mobile,o.create_time,o.remark,o.start_date,f.title,o.pay_state,f.line_type')->where($where)->order('o.create_time desc')->join('Left join asf_freetour f On o.freetour_id=f.id')->select();
//		   echo M()->getLastSql();exit;

        foreach($list as $k=>$v){
			$v['hc']=str_split($v['hc'],3);
			$v['hc_a'] = D("City")->getCity( $v['hc']);
			$list[$k]['hc_n']=implode('-',$v['hc_a']);//航程
			$list[$k]['jp_type']=$this->jptype($v['lx']);//机票类型
            $list[$k]['create_time']=date('Y-m-d H:i',$v['create_time']);
            $list[$k]['start_date']=date('Y-m-d',$v['start_date']);
        }
	     //待支付笔数
        $pending['count']=count($list);
        foreach($list as $val){
            $pending['price']+=$val['ysje'];
        }
		if($pending['price'] <0 || $pending['price']==''){
			$pending['price']=0;
		}
        $this->pending=$pending;
        $this->list=$list;
		$this->title="待支付订单";
		$this->display('ordersPending');
    }

	function ordersProcess(){//已支付
		$orderDB=D("TripOrder");
		$Member=D('Member');
		$uid['id']=getUid();
		$asmsId=$Member->field('asms_member_id')->where($uid)->find();
		$where['hyid']=$asmsId['asms_member_id'];
		$where['hyid']=array(array('eq',$asmsId['asms_member_id']),array('eq',getUid()),'or');
        $where['zf_fkf']=1;
		$list=$orderDB->field('order_num,freetour_id,total_price,num,mobile,create_time,remark')->where($where)->order('create_time desc')->select();
		$totle=count($list);
		if($totle>0){		
			foreach($list as $k=>$v){
				$v['hc']=str_split($v['hc'],3);
				$v['hc_a'] = D("City")->getCity( $v['hc']);
				$list[$k]['hc_n']=implode('-',$v['hc_a']);	
				$list[$k]['jp_type']=$this->jptype($v['lx']);//单程-往返
				$list[$k]['zt']=$this->ddzt($v['ddzt']);//状态
                $list[$k]['create_time']=date('Y-m-d H:i',$v['create_time']);
                $list[$k]['start_date']=date('Y-m-d',$v['start_date']);
			}
		}
		$this->totle=$totle;
	    $this->list=$list;	
		$this->title="已付款订单";
		$this->display('ordersProcess');
    }
	
	function ordersCancel(){//已取消
		$orderDB=D("TripOrder o");
		$uid['id']=getUid();
		$where['o.member_id']=$uid['id'];
		$where['o.pay_state']=2;
		$list=$orderDB->field('o.id,o.order_num,o.freetour_id,o.total_price,o.num,o.mobile,o.create_time,o.remark,o.start_date,f.title,o.pay_state,f.line_type')->where($where)->order('create_time desc')->join('Left join asf_freetour f On o.freetour_id=f.id')->select();
//		echo M()->getLastSql();exit;
		$totle=count($list);
		if($totle>0){
			foreach($list as $k=>$v){
				$v['hc']=str_split($v['hc'],3);
				$v['hc_a'] = D("City")->getCity( $v['hc']);
				$list[$k]['hc_n']=implode('-',$v['hc_a']);
				$list[$k]['jp_type']=$this->jptype($v['lx']);//单程-往返
				$list[$k]['zt']=$this->ddzt($v['ddzt']);//状态
                $list[$k]['create_time']=date('Y-m-d H:i',$v['create_time']);
                $list[$k]['start_date']=date('Y-m-d',$v['start_date']);
			}
		}
		$this->totle=$totle;
	    $this->list=$list;
		$this->title="已取消订单";
		$this->display('ordersCancel');
    }


	/*
	 * 取消订单
	 *
	 */
	public function cancelOrder(){
		$id =I('post.id');
		if(!$id){
			$this->error('参数不对');
		}
		$data['pay_state']=2;
		if(!D('TripOrder')->where('id='.$id)->save($data)){
			$this->error('订单取消失败');
		}
		$this->success('订单取消成功');
	}

	/*
	 * 订单详情
	 * 2017.4.27
	 */
	public function orderDetail(){
		$id = I('get.id');
		if(!$id){
			$this->error('参数不正确');
		}
		$where['o.id']=$id;
		$orderDB=D("TripOrder o");
		$res = $orderDB->field('o.id,o.order_num,o.freetour_id,o.total_price,o.num,o.mobile,o.create_time,o.remark,o.start_date,f.title,o.pay_state,f.line_type')->where($where)->join('Left join asf_freetour f On o.freetour_id=f.id')->find();
		$res['create_time']=date('Y-m-d',$res['create_time']);
		$this->assign('list',$res);
		$this->display('orderdetail');
	}
	
	//3、我的积fun  ok
	function integralDetail(){
		$this->info=$this->detail(0);
		$this->title="积fun明细";
		$this->display();
    }
	
	function integralExchange(){
		$this->info=$this->exchange(0);
		$this->title="兑换记录";
		$this->display();
    }

	//4、我的爱钻 ok
	function aizuanDetail(){
		$this->info=$this->detail(1);		
		$this->title="爱钻明细";
		$this->display();
    }
	
	function aizuanExchange(){
		$this->info=$this->exchange(1);
		$this->title="兑换记录";
		$this->display();
    }
	
	//5、现金券 ok
	function cashcouponDetail(){
		$this->info=$this->detail(2);
		$this->title="发放记录";
		$this->display();
    }
	
	function cashcouponExchange(){
		$AsmsOrder=D('AsmsOrder');  
		$member=D('Member');
		$Mid['id']=getUid();
		$MemberId=$member->where($Mid)->field('asms_member_id')->find();
		$wh['hyid']=$MemberId['asms_member_id'];
		$wh['xjj']=array('eq',0);
		$info=$AsmsOrder->where($wh)->field('ddbh,lx,hc,zf_fkf,info_update_time,update_time,xjj,ddzt')->select();		
		foreach($info as $key=>$val){
			$hc= $AsmsOrder->format($val['hc']);
			$hc=str_split($hc,3);
			$hc= D("City")->getCity($hc);
			$info[$key]['hc_n']=implode('->',$hc);			
			$info[$key]['jp_type']=$this->jptype($val['lx']);//单程/往返
			$info[$key]['ddzt_c']=$this->ddzt($val['ddzt']);//订单状态	
		}
		$this->assign('info',$info);
		$this->title="使用记录";
		$this->display();
    }
	
	function detail($type){//积分、爱钻、现金券明细
		$points=D('Points');
        $wh['member_id']=getUid();
		$wh['type2']=$type;
		$res=$points->where($wh)->order('create_time desc')->select();
		return $res;
	}
	
	function exchange($type){//积分、爱钻兑换记录
		$MallExchange=D('MallExchange');
        $wh['member_id']=getUid();
		$wh['type2']=$type;
		$info=$MallExchange->where($wh)->order('create_time desc')->select();
		foreach($info as $k=>$v){
			$MallInfo=json_decode($v['info']);		
			$mall=D('Mall');
			$where['id']=$MallInfo->mall_id;
			$mall=$mall->where($where)->field('img')->find();			
			$info[$k]['img']=$mall['img'];
			$info[$k]['num']=$MallInfo->num;
			$info[$k]['jifen']=$MallInfo->jifen;
			$info[$k]['title']=$MallInfo->title;
			if($v['status'] == 0){
				$info[$k]['zt']="未发货";
			}elseif($v['status'] == 1){
				$info[$k]['zt']="已发货";
			}else{
				$info[$k]['zt']="已完成";
			}			
		}
		return $info;
	}
	
	//我的收藏
	function myFavorite(){
		$wh['member_id']=getUId();
		$wh['type']=0;
		$jifen=D('MallCollect')->where($wh)->order('create_time desc')->limit(3)->relation(true)->select();//积分收藏
		$this->assign('jifen',$jifen);
		
		if(IS_AJAX){
			$page=I('p');
			if(I('type')== 'jifen'){
				$data=$this->toAjax(0,0,$page);
				$this->ajaxReturn($data);			
			}else{
				$data=$this->toAjax(1,0,$page);
				$this->ajaxReturn($data,$page);			
			}			
		}
		$this->title="我的收藏";
		$this->display();
    }
	
	//我的购物车
	function myCart(){
		if(I('type') == 'aizuan'){
			$WhereCart['member_id']=getUId();			
			$WhereCart['type']=1;//爱钻
			$CartAizuan=D('MallCart')->where($WhereCart)->order('create_time desc')->relation(true)->select();
			$this->assign('aizuan',$CartAizuan);//积分收藏
			$this->tag=1;
		}else{
			$WhereCart['member_id']=getUId();	
			$WhereCart['type']=0;//积分
			$CartJifen=D('MallCart')->where($WhereCart)->order('create_time desc')->relation(true)->select();	
			$this->assign('jifen',$CartJifen);//积分收藏
			$this->tag=0;
		}
		
		//ajax删除、清除、移动处理
		$mall=D('Mall');		
		$cart=D('MallCart');
		$collect=D('MallCollect');
		 		   
		if($this->isAjax()){
			$mallId=I('id');
			$type=I('act');		
			
			$wheres['mall_id']=array('in',$mallId);							
			$wheres['member_id']=getUid();
						
			//删除
			if($type == 'del'){				
				$rs=$cart->where($wheres)->delete();
				if($rs){
					$this->success();				
				}else{
					$this->error();	
				}
			}	
			
			//移入收藏夹
			if($type == 'move'){				
				$condition['member_id']=getUid();				
				$sc=$collect->where($condition)->field('mall_id')->select();//查询户收藏夹里面的内容
				
				if(empty($sc)){//收藏夹为空
					$scInfo=$cart->where($wheres)->field('type,mall_id')->select();
					foreach($scInfo as $v){
						$data=array(
							'mall_id'   =>$v['mall_id'],
							'type'      =>$v['type'],
							'member_id' =>getUid(),
							'create_time'=>time()
						);
						$collect->create($data);
						$collect->add();
					}
					$cart->where($wheres)->delete();
					$this->success();
					
				}else{//收藏夹不为空
					foreach($sc as $key=>$value){
						$mallids[]=$value['mall_id'];
					}
					$newids= array_diff($mallId,$mallids);//取两个数组的差集
					if(empty($newids)){//如果差集为空，说明已经收藏过这些产品了							
						$cart->where($wheres)->delete();
						$this->success();
					}else{//差值不为空
						$idsarr['mall_id']=array('in',$newids);
						$idsarr['member_id']=getUid();							
						$scInfo=$cart->where($idsarr)->field('type,mall_id')->select();
						
						foreach($scInfo as $v){
							$data=array(
								'mall_id'   =>$v['mall_id'],
								'type'      =>$v['type'],
								'member_id' =>getUid(),
								'create_time'=>time()
							);
							$collect->create($data);
							$collect->add();
						}						
						$cart->where($wheres)->delete();
						$this->success();
					}
				}
				$this->error();				
			}
			
			//清楚失效产品
			if($type == 'invalid'){	
				$idsarr['mall_id']=array('in',$mallId);		
				$clean=$mall->field('id,status')->where($idsarr)->select();
				foreach($clean as $k=>$v){
					if($v['status'] == 0){
						$ids[]=$v['id'];
					}				
				}			
				$idsarr['mall_id']=array('in',$ids);
				$res=$cart->where($idsarr)->delete();
				if($res){
					$data['gid']=$ids;
					$data['status']=1;					
					$this->AjaxReturn($data);
				}else{
					$this->error();
				}
			}	
		} 			
		$this->title="我的购物车";
		$this->display();
    }
	
	//已兑换礼品
	function myGift(){	
		$where['member_id']=getUId();		
		$where['type2']=0;//积分
		$exchange=D('MallExchange')->field('order_num,info,status,create_time')->limit(3)->where($where)->select();
		$jifen=$this->giftIfno($exchange);
		$this->assign('jifen',$jifen);	
		
		if(IS_AJAX){
			$page=I('p');
			if(I('type')== 'jifen'){
				$data=$this->toAjax(0,1,$page);
				$this->ajaxReturn($data);			
			}else{
				$data=$this->toAjax(1,1,$page);
				$this->ajaxReturn($data);			
			}			
		}		
		$this->title="已兑换礼品";
		$this->display();
    }
	
	function toAjax($type,$menu,$page){//$type:0-积分，1-爱钻； $menu:0-我的收藏，1-已兑换礼品		
		$wh['member_id']=getUId();
		$wh['type']=$type;
		
		if($menu == 0){	
			$count= D('MallCollect')->where($wh)->count();//总记录数
		}else{
			$count= D('MallExchange')->where($wh)->count();//总记录数
		}
		
		$pagesize=3; //每页显示的记录
		$totlePage=ceil($count/$pagesize);//总页数
		$offset=($page-1)*$pagesize;
		
		if($page>$totlePage){
			$data['status']=0;
			$data['info']='已经是最后一页了！';
		}else{
			if($menu == 0){
				$list=D('MallCollect')->where($wh)->order('create_time desc')->limit($offset,$pagesize)->relation(true)->select();
			}else{
				$info=D('MallExchange')->where($wh)->order('create_time desc')->limit($offset,$pagesize)->relation(true)->select();
				$list=$this->giftIfno(info);		
			}
			if($type == 0){
				for($i=0;$i<count($list);$i++){
					$list[$i]['dhfs']="积fun兑换";
					$list[$i]['t']="积fun";
				}
			}else{
				for($i=0;$i<count($list);$i++){
					$list[$i]['dhfs']="爱钻兑换";
					$list[$i]['t']="爱钻";
				}		
			}
			$data['status']=1;
			$data['list']=$list;
		}
		return $data;
	}	
	
	function giftIfno($info){
		foreach($info as $key=>$value){				
			$obj[$key]=json_decode($value['info'],true);			
			$whereMallId['id']=$obj[$key]['mall_id'];
			$mallimg=D('Mall')->field('img')->where($whereMallId)->find();
			$obj[$key]['img']=$mallimg['img'];
			$obj[$key]['order_num']=$info[$key]['order_num'];
			$obj[$key]['create_time']=date('Y-m-d',$info[$key]['create_time']);
			$obj[$key]['xj']=$info[$key]['num']*$info[$key]['jifen'];
			
			if($info[$key]['status'] == 0){
				$obj[$key]['status']='未发货';
			}elseif($info[$key]['status'] == 1){
				$obj[$key]['status']='已发货';
			}else{
				$obj[$key]['status']='已完成';
			}
		}
		return $obj;
	}		
	
	//我的特权
	function myPrivilege(){
        $userInfo=D('Member')->userInfo(getUId());

        $arrPrivilege=json_decode($userInfo['rank']['privilege']);
        $where['id']=array("in",$arrPrivilege);
        $this->myPrivilege=D("MemberPrivilege")->where($where)->select();

//        $map['id']=array("gt",$userInfo['rank_id']);
//        $gjRank=D("member_rank")->where($map)->find();
//
//        ///高一级权限
//        $arrGjPrivilege=json_decode($gjRank['privilege']);
//        $gj=array_diff_assoc($arrGjPrivilege,$arrPrivilege);
//        $where['id']=array("in",$gj);
//        $this->gjPrivilege=D("MemberPrivilege")->where($where)->select();		
				
		$this->privilege=getUid()?$this->myPrivilege:D("MemberPrivilege")->order("sort")->select();
		
		$this->title="我的特权";
		$this->display();
    }
		
    //6、我的信息 ok
    function information(){
		$member=D('Member');
		$wh['id']=getUid();
		$userinfo=$member->where($wh)->find();
		//生日处理
		$this->birthday=explode('-',$userinfo['birthday']);		
		//邮箱是否验证
		$checkemail=D('CheckEmail');
		$wh['member_id']=getUid();
		$wh['email']=$userinfo['email'];
		$email=$checkemail->where($wh)->find();
		if($email['is_check'] == 1){
			$this->emailCheck="已验证";
		}else{
			$this->emailCheck="未验证";
		}		
		
        if($_POST){
            $member= D('Member');
            $data['name']=I('post.name');
            $data['sex']=I('post.sex');           
            $data['province']=I('post.province');
            $data['city']=I('post.city');
            $data['nickname']=I('post.nickname');			
            $data['email']=I('post.email');  
			$data['birthday']=I('post.year').'-'.I('post.month').'-'.I('post.day');
			
            $uid=session('uid');			
            $rs=$member->where("id=$uid")->save($data);
            if($rs){
                $this->success("修改成功");
            }else{
                $this->error("修改失败");
            }
        }else{
            $this->title="我的信息";
            $this->display();
        }
    }
	
	//7、修改密码 ok
	public function editpwd(){
        if($_POST){
            $rs=D('Member')->editPwd();
            if($rs===true){
                D('Message')->message_action('edit_pwd');//发送信息
                $this->success("修改成功",'/Member/index');
            }else{
                $this->error($rs);
            }
        }else{
            $this->title="修改密码";
            $this->display();
        }
    }	
	
    //8、找回密码 ok
    function getPassword(){
        $this->title="密码找回";
        if($_POST){
            if($_POST['act']=='step1'){
                $mobile= I('phone');
                if(!$mobile){
                    $this->error('请输入用户名和手机号码');
                }
                $member= D('Member');
                $rs= $member->where("mobile='$mobile'")->find();
                if($rs){
                    $this->assign('mobile',$mobile);
                    import('ORG.Util.String');
                    $auth_str=String::randString(6,1);  //生成6位数的认证码
                    session('auth_username',$rs['username']);
                    session('auth_mobile', $mobile);
                    session('auth_str', $auth_str);
                    $this->display("member/getPassword1");
                }else{
                    return ;                
                }
            }
            elseif($_POST['act']=='step2' &&  session('auth_mobile')){
                if( I('post.auth_str')==session('auth_str')){
                    $this->display("/member/getPassword2");
                }else{
                    $this->error('输入的手机验证码有误');
                }
            }
            elseif($_POST['act']=='step3'){
                $member= D('Member');
                if( strlen(I('password')) < 6 || I('password')!=I('re_password')){
                    $this->error('密码长度不能少于6位数 ，或两次密码不一样');
                }
                $password=I('password');
                $salt=generateSalt();
                $data['salt']=$salt;  // 设置salt字段值
                $data['password']=hashPassword($password,$salt); //# 对密码进行md5 混合加密
                $username=session('auth_username');
                $mobile=session('auth_mobile');
                $rs=$member->where("username='$username' and mobile='$mobile'")->save($data);
                if($rs){
                    $mobile=session('auth_mobile');
					$data['code']= session('auth_str');
					$data['product']= '品悦旅行网';
//                import('ORG.Message',APP_PATH.'Lib/');// 导入
//                $rs=Message::send($phone,'SMS_48355102',$data);
					$rs=sendMessage($mobile,'SMS_48355102',$data);
//                    D('Message')->message_action('forgot_pwd_success',$data);//发送信息
                    session('act',null);
                    session('auth_mobile',null);
                    session('auth_username',null);
                    session('auth_str',null);
                    $this->display("/member/getPassword3");
                }else{
                    $this->error('操作失败');
                }
            }
        }else{
            $this->display();
        }
    }	
		
	//9、邮箱验证
	public function mailboxverify(){
		$member=D('Member');
		$email=D('CheckEmail');
		$wh['id']=getUid();	
		$this->email=$member->field('email')->where($wh)->find();
		
		if($this->isAjax()){
			if(I('mail') != ''){//更改邮箱
				$re=$member->where($wh)->setField('email',I('mail'));
				$data['status']=1;	
				$this->success($data);
			}
			if(I('m') !=''){//邮箱验证			
				$email=I('m');
				$this->notify($email);
				$data['status']=1;	
				$this->success($data);			
			}
		}		
		
		//验证用户和激活码
		$key=I('key');
		$uid=I('uid');	
		if(!empty($uid) && !empty($key)){			
			$where['member_id']=$uid;
			$where['key']=$key;
			$res=$email->where($where)->find();				
			if(!empty($res)){
				if(time()<=$res['key_exptime']){					
					$info=array('key'=>'','is_check'=>1);
					$email->where($where)->setField($info);					
					
					//邮箱验证成功增加500积分
					$points=D('Points');
					$wh['member_id']=$userInfo['id'];
					$last_points=$points->field('last_points')->where($wh)->order('cteate_time desc')->find();
					$data=array(
						'member_id'  =>	$userInfo['id'],
						'type2'      =>1,
						'points'     =>500,
						'type'       =>1,
						'description'=>"邮箱验证成功赠送500积fun",
						'last_points'=>$last_points['last_points']+500,
						'cteate_time'=>time()
						
					);					
					$points->add($data);					
					session('verify',1);
					$this->success('恭喜你，邮箱验证成功！',U('/Member/index'));
				}else{
					$this->error('验证码已过期，请重新获取！',U('/Member/mailboxverify'));
				}
			}else{
				$this->error('邮箱验证失败，请重新验证！',U('/Member/mailboxverify'));
			}
		}		
		$this->title="邮箱验证";
		$this->display();
    }

	//发送邮件
	function notify($email){
		$smtpemailto 	= 	$email;//发送给谁
		$actcodes = md5($smtpemailto.mt_rand(111111,999999)); //激活码			
		$mailsubject 	= 	 "邮箱验证激活——品悦旅行网";//邮件主题	
		
		////////////////模板赋值
			$userInfo=$this->userInfo;
			$username=$userInfo['username'];
			$uid=$userInfo['id'];
			$key=$actcodes;
			$href='http://'.$this->_server('HTTP_HOST').__ROOT__.'/Member/mailboxverify';
			$time=date('Y-m-d H:i:s',time());		
		$template = $this->fetch('sendemail');
		$template = str_replace('{username}',$username,$template);
		$template = str_replace('{uid}',$uid,$template);
		$template = str_replace('{key}',$key,$template);
		$template = str_replace('{href}',$href,$template);
		$template = str_replace('{time}',$time,$template);	
		
		/////////////////写入数据库
			$email=D('CheckEmail');
			$data['member_id']=$this->userInfo['id'];
			$data['email']=$smtpemailto;
			$data['key']=$actcodes;
			$data['key_exptime']=strtotime("+15 day");		
		$where['email']=$smtpemailto;
		$res=$email->where($where)->select();		
		if(!empty($res)){
			$hh=$email->where($where)->save($data);//如果已有，则更新
		}else{		
			$hh = $email->data($data)->add();  //如果没有，则写入数据
		}		
		
		//////////////////发送邮件			
		if($hh){
		   sendMail($smtpemailto,$mailsubject,$template);  //发送邮件
		}else {
		  echo '发送失败！';
		  exit;
		}
	}
	
	//10、订单在线支付
	function pay(){		
		$orderDB=D('AsmsOrder');
		$id=I('id');
		if($id != ''){//来自手机短信
			$orderID=D('PayOrder')->where('id='.$id)->field('order_id_arr,order_price')->find();
			$wh['ddbh']=$orderID['order_id_arr'];
			$this->order_pay_id=$id;
			$this->totleMoney=$orderID['order_price'];
		}else{
			$gid=explode(',',I('gid'));
			$wh['ddbh']=array('in',$gid);
			$this->order_pay_id=date("YmdHis").rand(1000,2000);
			$this->order_id_arr=I('gid');
			$this->totleMoney=$orderDB->where($wh)->sum('ysje');
		}	
		
		$list=$orderDB->where($wh)->field('ddbh,lx,hd_info,cjr_info,ysje,hc')->select();		
		foreach($list as $k=>$v){		
			//航班信息
			$hd_info=json_decode($v['hd_info']);			
			foreach($hd_info as $key=>$val){
				$hc2=substr($val->hc,0,6);				
				$hc2= D("AsmsOrder")->format($hc2);
				$hc2=str_split($hc2,3);
				$hc2= D("City")->getCity($hc2);
				
				$list[$k]['hc1'][]=array(
					'hc'  =>implode('->',$hc2),
					'cfsj'=>$val->cfsj,		
					'hbh' =>$val->hbh	
				);						
			}
						
			$hc= D("AsmsOrder")->format($v['hc']);
			$hc=str_split($hc,3);
			$hc= D("City")->getCity($hc);	
			
			$route .=implode('-',$hc).',';
			//乘客信息					
			$cjr=json_decode($v['cjr_info']);			
			$cjr=get_object_vars($cjr);			
			foreach($cjr as $key=>$val){				
				if($val->cjr_lx == 1){$cjrlx="成人";}
				if($val->cjr_lx == 2){$cjrlx="儿童";}
				if($val->cjr_lx == 3){$cjrlx="婴儿";}			
				$list[$k]['cjr'][]=array(
					'cjr_cjrxm'=>$val->cjr_cjrxm,									  
					'cjr_lx'    =>$cjrlx,
					'cjr_clkid' =>$val->cjr_clkid,
					'cjr_zjlx'  =>$val->cjr_zjlx

				);
			}
			unset($list[$k]['hc']);
			unset($list[$k]['hd_info']);
			unset($list[$k]['cjr_info']);			
		}			
		$this->route=substr($route,0,strlen($route)-1);		
		$this->assign('list',$list);
		$this->title="在线支付";
		$this->display();	
    }
	
	function payList(){//已支付列表
		$orderDB=D('AsmsOrder');
		$PayDB=D('PayOrder');
		$wh['member_id']=getUid();
		//$wh['member_id']=1717;
		$list=$PayDB->where($wh)->field('order_id_arr')->select();
		foreach($list as $key=>$val){
			$arr .= $val['order_id_arr'].',';		
		}
		$arr=explode(',',$arr);
		$arr=array_unique($arr);//去掉重复值
		array_pop($arr);//去掉最后一个空数组
		
		$condition['ddbh']=array('in',$arr);
		$order=$orderDB->where($condition)->field('ddbh,ddzt,lx,xsj,xjj,ysje,dprq,hc')->select();
		foreach($order as $k=>$v){
			//航程
			$hc= $orderDB->format($v['hc']);
			$hc=str_split($hc,3);
			$hc= D("City")->getCity($hc);
			$order[$k]['hc_n']=implode('-',$hc);
			
			//类型	
			$order[$k]['jp_type']=$this->jptype($v['lx']);
			
			//订单状态
			$order[$k]['zt']=$this->ddzt($v['ddzt']);			
		}
		$this->assign('order',$order);		
		$this->title="交易记录";
		$this->display();
    }
	
	function jptype($type){//机票类型：单程、往返、联程 
		if($type == 1){
			return "单程"; 
		}elseif($type == 2){
			return "往返";
		}elseif($type == 3){
			return "联程";
		}elseif($type == 4){
			return "缺口";
		}			
	}
	
	function ddzt($data){//订单状态：申请、出票、取消、完成
		$ddzt=array('申请中','已订座','已调度','已出票','配送中','部分出票','','客户消','已取消','完成');
		if($data == 0){			
			return $ddzt[0];
		}elseif($data == 1){
			return $ddzt[1];
		}elseif($data == 2){
			return $ddzt[2];
		}elseif($data == 3){
			return $ddzt[3];
		}elseif($data == 4){
			return $ddzt[4];
		}elseif($data == 5){
			return $ddzt[5];
		}elseif($data == 7){
			return $ddzt[7];
		}elseif($data == 8){
			return $ddzt[8];
		}elseif($data == 9){
			return $ddzt[9];
		}
	}
	
	////////////////////////////////////////////////////////支付宝  开始///////////////////////////////////////////////////
	function alipay(){//支付入口
		//引入类		
		vendor('Alipaywap.lib.Corefunction');
		vendor('Alipaywap.lib.Md5function');
		vendor('Alipaywap.lib.Submit');
	
		//置您基本信息	
		$alipay_config['partner']		= C('ALIPAY_PARTNER');//合作身份者id，以2088开头的16位纯数字
		$alipay_config['key']			= C('ALIPAY_KEY');//安全检验码，以数字和字母组成的32位字符
		$alipay_config['private_key_path']	= '';
		$alipay_config['ali_public_key_path']='';		
		$alipay_config['sign_type']     =  'MD5';//签名方式 不需修改	
		$alipay_config['input_charset'] = 'utf-8';//字符编码格式 目前支持 gbk 或 utf-8
		//$alipay_config['cacert']    = getcwd().'\\cacert.pem';//ca证书路径地址，用于curl中ssl校验	
		$alipay_config['transport']    = 'http';//访问模式,若支持请选择https；若不支持请选择http	
		$alipay_config['cacert']='http://m.aihangfei.com/ThinkPHP/Extend/vendor/Alipaywap/cacert.pem';
						
		//建立订单
		if(is_array($_POST['route'])){
			$data['route']=implode(',',$route);
		}else{
			$data['route']=$route;
		} 
		$data=array(
			'id'             =>$_POST['WIDout_trade_no'],
			'order_id_arr'   =>$_POST['order_id_arr'],
			'product_name'   =>$_POST['WIDsubject'],
			'order_price'    =>$_POST['WIDtotal_fee'],
			'member_id'      =>getUId(),
			'type'           =>1,
			'trade_mode'     =>1,
			'trade_state'    =>1,
			'create_time'    =>time(),
			'update_time'    =>time()
		);
		
		$find=D('PayOrder')->where('id='.$_POST['WIDout_trade_no'])->find();
		
		if(!empty($find)){
			D('PayOrder')->save($data);
		}else{
			D('PayOrder')->add($data);
		}
			
		
		//返回格式
		$format = "xml";//必填，不需要修改
		
		//返回格式
		$v = "2.0";//必填，不需要修改
		
		//请求号
		$req_id = date('Ymdhis').rand(1000,9999);//必填，须保证每次请求都是唯一
		
		//**req_data详细信息**
		
		//服务器异步通知页面路径
		$notify_url = "http://m.aishangfei.com/Member/notifyUrl.php"; 
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//页面跳转同步通知页面路径
		$call_back_url = "http://m.aishangfei.com/Member/callback.php";
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//操作中断返回地址
		$merchant_url = "http://m.aishangfei.com";
		//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//卖家支付宝帐户
		$seller_email = $_POST['WIDseller_email'];//必填
		
		//商户订单号
		$out_trade_no = $_POST['WIDout_trade_no'];//商户网站订单系统中唯一订单号，必填
		
		//订单名称
		$subject = $_POST['WIDsubject'];//必填
		
		//付款金额
		$total_fee = $_POST['WIDtotal_fee'];//必填
		
		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
		//必填
		
		/************************************************************/		
		//构造要请求的参数数组，无需改动
		$para_token = array(
				"service" => "alipay.wap.trade.create.direct",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);
		
		//URLDECODE返回的信息
		$html_text = urldecode($html_text);
		
		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);
		
		//获取request_token
		$request_token = $para_html_text['request_token'];
		
		
		/************根据授权码token调用交易接口alipay.wap.auth.authAndExecute************/
		
		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
		echo $html_text;		
	}
	
	function notifyUrl(){//异步处理
		//引入类			
		vendor('Alipaywap.lib.Corefunction');
		vendor('Alipaywap.lib.Md5function');
		vendor('Alipaywap.lib.Notify');
		
		//置您基本信息	
		$alipay_config['partner']		= C('ALIPAY_PARTNER');//合作身份者id，以2088开头的16位纯数字
		$alipay_config['key']			= C('ALIPAY_KEY');//安全检验码，以数字和字母组成的32位字符	
		$alipay_config['private_key_path']	= '';
		$alipay_config['ali_public_key_path']='';		
		$alipay_config['sign_type']     =  'MD5';//签名方式 不需修改	
		$alipay_config['input_charset'] = 'utf-8';//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['cacert']='http://m.aihangfei.com/ThinkPHP/Extend/vendor/Alipaywap/cacert.pem';//ca证书路径地址，用于curl中ssl校验	
		$alipay_config['transport']    = 'http';//访问模式,若支持请选择https；若不支持请选择http		
		
		//计算得出通知验证结果
		///$alipayNotify = new AlipayNotify($alipay_config);
		//$verify_result = $alipayNotify->verifyNotify();
		
		//if($verify_result) {//验证成功			
			//解密（如果是RSA签名需要解密，如果是MD5签名则下面一行清注释掉）
			//$notify_data = $alipayNotify->decrypt($_POST['notify_data']);			

			
			//解析notify_data
			//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
			$doc = new DOMDocument();
			$doc->loadXML($notify_data);
			
			if(!empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
				//商户订单号
				$out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
				//支付宝交易号
				$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
				//交易状态
				$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
				
				if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS'){
					
					///////////////////////////////更改订单支付状态/////////////////////////////////////////
					$ordstatus=D('PayOrder')->where('id='.$out_trade_no)->getField('status');
					if($ordstatus == 0){//判断该笔订单是否已经做过处理
						$parameter=array(
							'out_trade_no'   =>$out_trade_no,
							'trade_no'       =>$trade_no,
							'trade_status'   =>$trade_status
						);
						
						$data=array(
							'status'     =>1, //支付状态更改为已支付
							'data_json'  =>json_encode($parameter),
							'update_time'=>time() 
						);
						$res=D('PayOrder')->where('id='.$out_trade_no)->save($data);
						if($res){
							$info=D('PayOrder')->find($out_trade_no);					
							$idArr=explode(',',$info['order_id_arr']);
							$where['ddbh']=array('in',$idArr);
							D('AsmsOrder')->where($where)->setField('zf_fkf',1);						
						}				
					}
					//////////////////////////////////////////////////////////////////////////////////////////	
				}
			}
			echo "success";		//请不要修改或删除
		//}else {
			//验证失败
		//	echo "fail";
		//}
	
	}
	
	function callback(){//返回处理
		
		//引入类		
		vendor('Alipaywap.lib.Corefunction');
		vendor('Alipaywap.lib.Md5function');
		vendor('Alipaywap.lib.Notify');	

		//置您基本信息	
		$alipay_config['partner']		= C('ALIPAY_PARTNER');//合作身份者id，以2088开头的16位纯数字
		$alipay_config['key']			= C('ALIPAY_KEY');//安全检验码，以数字和字母组成的32位字符	
		$alipay_config['private_key_path']	= '';
		$alipay_config['ali_public_key_path']='';		
		$alipay_config['sign_type']     =  'MD5';//签名方式 不需修改	
		$alipay_config['input_charset'] = 'utf-8';//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['cacert']='http://m.aihangfei.com/ThinkPHP/Extend/vendor/Alipaywap/cacert.pem';//ca证书路径地址，用于curl中ssl校验	
		$alipay_config['transport']    = 'http';//访问模式,若支持请选择https；若不支持请选择http		
		
		//计算得出通知验证结果
		//$alipayNotify = new AlipayNotify($alipay_config);
		//$verify_result = $alipayNotify->verifyReturn();
		//if($verify_result) {//验证成功							
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];		
			//支付宝交易号
			$trade_no = $_GET['trade_no'];		
			//交易状态
			$result = $_GET['result'];			
			
			///////////////////////////////更改订单支付状态/////////////////////////////////////////
			$ordstatus=D('PayOrder')->where('id='.$out_trade_no)->getField('status');
			if($ordstatus == 0){//判断该笔订单是否已经做过处理
				$parameter=array(
					'out_trade_no'   =>$out_trade_no,
					'trade_no'       =>$trade_no,
					'trade_status'   =>$trade_status
				);
				
				$data=array(
					'status'     =>1, //支付状态更改为已支付
					'data_json'  =>json_encode($parameter),
					'update_time'=>time() 
				);
				$res=D('PayOrder')->where('id='.$out_trade_no)->save($data);
				if($res){
					$info=D('PayOrder')->find($out_trade_no);					
					$idArr=explode(',',$info['order_id_arr']);
					$where['ddbh']=array('in',$idArr);
					D('AsmsOrder')->where($where)->setField('zf_fkf',1);						
				}				
			}
			//////////////////////////////////////////////////////////////////////////////////////////	
			$this->success('success',U('/Member/ordersProcess'));
			echo "success<br />";			
		//}else {
		//	echo "error";
		//}	
	}
	///////////////////////////////////////////////////////支付宝   结束///////////////////////////////////////////////////
	
	
	////////////////////////////////////////////易宝支付 开始///////////////////////////////////////////////////////////////
	function yee(){
		$info=array(
			'trade_no'=>$_POST['WIDout_trade_no'],//订单号
			'subject'=>$_POST['WIDsubject'], //商品名称
			'total_fee'=>$_POST['WIDtotal_fee']//消费总额
		);
		$this->info=$info;
		$this->title="易宝支付";
		$this->display();
	}
	////////////////////////////////////////////易宝支付 结束///////////////////////////////////////////////////////////////
	
	
	////////////////////////////////////////////快钱支付 开始///////////////////////////////////////////////////////////////
	function bill(){
		// ======================= 传送参数设置  开始  =====================================
			//* 表示 必填写项目.  ( )里的表示字符长度
			$kq_target="https://www.99bill.com/mobilegateway/recvMerchantInfoAction.htm";			
			
			$kq_inputCharset	= "1";	                                    //1 -> UTF-8，2 ->GBK，3->GB2312，default: 1	(2)
			$kq_pageUrl	        = "http://m.aishangfei.com/Member/ordersProcess.php";	//   直接跳转页面	(256)
			$kq_bgUrl	        = "http://m.aishangfei.com/Member/billReturn.php";    	//   后台通知页面	(256)
			$kq_version	        = "mobile1.0";	                            //*	 版本  固定值 v2.0	(10)
			$kq_mobileGateway   ='';	   		//移动网关版本，当version= mobile1.0时有效phone代表手机版移动网关，pad代表平板移动网关，默认为phone			
			$kq_language		= "1";	                                    //*  默认 1 ， 显示 汉语	(2)
			$kq_signType		= "4";                                      //*  固定值 1 表示 MD5 加密方式 , 4 表示 PKI 证书签名方式	(2)
			
			$kq_merchantAcctId  = "1002340723701";                          //*  商家用户编号		(30)	
			$kq_payerName		= "";	                                    //   英文或者中文字符	(32)
			$kq_payerContactType= "1";                                      //  支付人联系类型  固定值： 1  代表电子邮件方式 (2)
			$kq_payerContact    = "";	                                    //	 支付人联系方式	(50)			
			$kq_payerIdType="3"; //指定付款人 可为
			$kq_payerId=getUid(); //付款人标识
			
			$kq_orderId		    = $_POST['WIDout_trade_no'];	            //*  字母数字或者, _ , - ,  并且字母数字开头 并且在自身交易中式唯一	(50)
			$kq_orderAmount	    = $_POST['WIDtotal_fee']*100;	                //*	  字符金额 以 分为单位 比如 10 元， 应写成 1000	(10)
			$kq_orderTime		= date(YmdHis);                             //*  交易时间  格式: 20110805110533
			$kq_productName	    = $_POST['WIDsubject'];	                    //	  商品名称英文或者中文字符串(256)
			$kq_productNum		= "";	                                    //	  商品数量	(8)
			$kq_productId		= "";                                       //    商品代码，可以是 字母,数字,-,_   (20) 
			$kq_productDesc	    = "";	                                    //	  商品描述， 英文或者中文字符串  (400)
			$kq_ext1			= $_POST['order_id_arr'];                   //	  扩展字段， 英文或者中文字符串，支付完成后，按照原样返回给商户。 (128)
			$kq_ext2			= $_POST['route'];
			$kq_payType		    = "00";	                                    //*   支付方式 固定值: 00, 10, 11, 12, 13, 14, 15, 16, 17  (2)
																			// 00: 其他支付，10: 银行卡支付，11: 电话支付，12: 快钱账户支，13: 线下支付，15: 信用卡在线支																	
			$kq_bankId			= "";                                       // 银行代码 银行代码 要在开通银行时 使用， 默认不开通 (8)
			$kq_redoFlag		= "1";                                      // 同一订单禁止重复提交标志  固定值 1 、 0      
																				// 1 表示同一订单只允许提交一次 ； 0 表示在订单没有支付成功状态下 可以重复提交； 默认 0 
			$kq_pid			    = "";                                      //  合作伙伴在快钱的用户编号 (30)
		// ======================= 传送参数设置  结束  =====================================
		
		// ======================= 快钱 封装代码 ! ! 勿随便更改 开始  =====================================
			function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){$kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
			$kq_all_para=kq_ck_null($kq_inputCharset,'inputCharset');
			$kq_all_para.=kq_ck_null($kq_pageUrl,"pageUrl");
			$kq_all_para.=kq_ck_null($kq_bgUrl,'bgUrl');
			$kq_all_para.=kq_ck_null($kq_version,'version');
			$kq_all_para.=kq_ck_null($kq_mobileGateway,'mobileGateway');
			$kq_all_para.=kq_ck_null($kq_language,'language');			
			$kq_all_para.=kq_ck_null($kq_signType,'signType');
			
			$kq_all_para.=kq_ck_null($kq_merchantAcctId,'merchantAcctId');
			$kq_all_para.=kq_ck_null($kq_payerName,'payerName');
			$kq_all_para.=kq_ck_null($kq_payerContactType,'payerContactType');
			$kq_all_para.=kq_ck_null($kq_payerContact,'payerContact');
			
			//
			//$kq_all_para.=kq_ck_null($kq_payerIdType,'payerIdType');
			//$kq_all_para.=kq_ck_null($kq_payerId,'payerId');			
			//
			$kq_all_para.=kq_ck_null($kq_orderId,'orderId');
			$kq_all_para.=kq_ck_null($kq_orderAmount,'orderAmount');
			$kq_all_para.=kq_ck_null($kq_orderTime,'orderTime');
			$kq_all_para.=kq_ck_null($kq_productName,'productName');
			$kq_all_para.=kq_ck_null($kq_productNum,'productNum');
			
			$kq_all_para.=kq_ck_null($kq_productId,'productId');
			$kq_all_para.=kq_ck_null($kq_productDesc,'productDesc');
			$kq_all_para.=kq_ck_null($kq_ext1,'ext1');
			$kq_all_para.=kq_ck_null($kq_ext2,'ext2');
			$kq_all_para.=kq_ck_null($kq_payType,'payType');
			
			$kq_all_para.=kq_ck_null($kq_bankId,'bankId');;
			$kq_all_para.=kq_ck_null($kq_redoFlag,'redoFlag');
			$kq_all_para.=kq_ck_null($kq_pid,'pid');
		
			//$kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);
			//$kq_all_para=substr($kq_all_para,0,-1);
			  $kq_all_para=rtrim($kq_all_para,'&');
			 // echo $kq_all_para;
			
			//////////////////////////////               lib  start  
			////////  私钥加密 生成 MAC
				$priv_key = file_get_contents(WEB_ROOT."99bill/99bill-rsa.pem");
				$pkeyid = openssl_get_privatekey($priv_key);
			//	echo '$pkeyid='.$pkeyid.'<br>';
			
				// compute signature
				openssl_sign($kq_all_para, $signMsg, $pkeyid);
			
				// free the key from memory
				openssl_free_key($pkeyid);
				$kq_sign_msg = base64_encode($signMsg);
			//	echo $kq_sign_msg;
			///////////
			//////////////////////////////               lib  end	
			
			$kq_get_url=$kq_target.'?'.$kq_all_para.'&signMsg='.$kq_sign_msg;		
		// ======================= 快钱 封装代码 ! ! 勿随便更改 结束  =====================================	
		
		$this->assign('kq_inputCharset',$kq_inputCharset);
		$this->assign('kq_pageUrl',$kq_pageUrl);
		$this->assign('kq_bgUrl',$kq_bgUrl);
		$this->assign('kq_version',$kq_version);		
		$this->assign('kq_language',$kq_language);
		
		$this->assign('kq_signType',$kq_signType);
		$this->assign('kq_merchantAcctId',$kq_merchantAcctId);		
		$this->assign('kq_payerName',$kq_payerName);
		$this->assign('kq_payerContactType',$kq_payerContactType);		
		$this->assign('kq_payerContact',$kq_payerContact);
		
		$this->assign('kq_orderId',$kq_orderId);
		$this->assign('kq_orderAmount',$kq_orderAmount);
		$this->assign('kq_orderTime',$kq_orderTime);
		$this->assign('kq_productName',$kq_productName);		
		$this->assign('kq_productNum',$kq_productNum);
		
		$this->assign('kq_productId',$kq_productId);
		$this->assign('kq_productDesc',$kq_productDesc);
		$this->assign('kq_ext1',$kq_ext1);
		$this->assign('kq_ext2',$kq_ext2);		
		$this->assign('kq_payType',$kq_payType);	
		
		$this->assign('kq_bankId',$kq_bankId);
		$this->assign('kq_redoFlag',$kq_redoFlag);
		$this->assign('kq_pid',$kq_pid);
		$this->assign('kq_sign_msg',$kq_sign_msg);
		
		$this->assign('kq_payerIdType',$kq_payerIdType);		
		$this->assign('kq_payerId',$kq_payerId);
		
		$this->assign('kq_target',$kq_target);
		
		$this->title="确认支付";
		$this->display();
	}
	
	function billReturn(){
		//      核对签名是否正确 ==============  开始 ================		
		function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}		
		$kq_check_all_para=kq_ck_null($_GET[merchantAcctId],'merchantAcctId').kq_ck_null($_GET[version],'version').kq_ck_null($_GET[language],'language').kq_ck_null($_GET[signType],'signType').kq_ck_null($_GET[payType],'payType').kq_ck_null($_GET[bankId],'bankId').kq_ck_null($_GET[orderId],'orderId').kq_ck_null($_GET[orderTime],'orderTime').kq_ck_null($_GET[orderAmount],'orderAmount').kq_ck_null($_GET[bindCard],'bindCard').kq_ck_null($_GET[bindMobile],'bindMobile').kq_ck_null($_GET[dealId],'dealId').kq_ck_null($_GET[bankDealId],'bankDealId').kq_ck_null($_GET[dealTime],'dealTime').kq_ck_null($_GET[payAmount],'payAmount').kq_ck_null($_GET[fee],'fee').kq_ck_null($_GET[ext1],'ext1').kq_ck_null($_GET[ext2],'ext2').kq_ck_null($_GET[payResult],'payResult').kq_ck_null($_GET[errCode],'errCode');		
		//      核对签名是否正确 ==============  结束 =================		
		
		$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
		$MAC=base64_decode($_GET[signMsg]);

		$cert = file_get_contents(WEB_ROOT."99bill/99bill.cert.rsa.20140728.cer");
		$pubkeyid = openssl_get_publickey($cert); 
		$ok = openssl_verify($trans_body, $MAC, $pubkeyid); 		
		
		if ($ok == 1) { 
			//此处做商户逻辑处理
			if($_GET[payAmount] == $_GET[orderAmount]){								
				$data=array(
					'id'          =>$_GET[orderId],
					'order_id_arr'=>$_GET[ext1],
					'member_id'   =>getUId(),
					'route'       =>$_GET[ext2],
					'product_name'=>'机票预订-快钱支付',
					'order_price' =>$_GET[orderAmount]/100,	
					'status'      =>1,
					'create_time' =>time()
				);
				$rs=D('PayOrder')->add($data);
				if($rs){
					$orderID=explode(',',$_GET[ext1]);
					$where['ddbh']=array('in',$orderID);
					$res=D('AsmsOrder')->where($where)->setField('zf_fkf',1);
					if($res){
						echo '<result>1</result><redirecturl>m.aishangfei.com/Member/ordersProcess</redirecturl>';
					}
				}								
			}
		}else{
			echo '<result>1</result><redirecturl>m.aishangfei.com/Member/ordersPending</redirecturl>';
		}	
	}
	////////////////////////////////////////////快钱支付 结束///////////////////////////////////////////////////////////////
}?>