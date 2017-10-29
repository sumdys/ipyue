<?php
// 首页控制器
class MicroshopAction extends IniAction {
	
    public function shop_index(){
		$keyword = trim(I('get.keyword'));
        $type = trim(I('get.type'));
        $from = trim(I('get.from'));
		$userId = trim(I('get.user_id'));
        session('channel',$from);
        //特价线路
        $where['old_price'] =array('gt',0);
        $member_id=getUid();
		!$member_id && $member_id=$userId;
		$itemIds = D('WeiShopItems')->where('member_id='.$member_id)->getField('freetour_id',true);
		$where['id']=array('IN',$itemIds);
        //搜索
        if($keyword){
            $where['title']=array('like',"%{$keyword}%");
        }
        //导航
        if($type){
            if($type==4){
                //别墅客栈
                $where['line_type']=array('in',array(3,4));
            }elseif($type==5){
                //邮轮
                $where['line_type']=array('in',array(5,6));
            }elseif($type==7){
                //独家资源
                $where['line_type']=array('in',array(7));
            }elseif($type==8){
                //跟团游
                $where['line_type']=array('in',array(8));
            }elseif($type==9){
                //高端游
                $where['line_type']=array('in',array(9));
            }elseif($type==10){
                //亲子游学
                $where['line_type']=array('in',array(10));
            }elseif($type==11){
                //景点门票
                $where['line_type']=array('in',array(11));
            }

        }
        $this->title="【品悦定制旅游】定制旅游";
        $this->keywords="品悦旅行，定制旅行，定制旅游，旅行定制，旅行定制师，海外旅行，旅游路书，旅行";
        $this->description="品悦定制旅行最专业的海外旅行定制专家。我们拥有优秀的旅行定制师团队，凭借多年深度旅行和海外旅居经历，为想要出国旅行的朋友提供咨询服务。专业的推荐、精彩的设计、严谨的安排、24小时电话和网络协助，一切都只为，让你的旅行有温度。";

        $data = array();

        $model = D("Freetour");

        $fields = 'id,images,price,title,old_price';
        $data['tj_line'] = $model->lists($where,$fields,'',10);
        $countPage=$model->where($where)->count();
//        echo $model->getLastSql();exit;
//           var_dump($data['tj_line']);exit;
		$ad = M('news_img')->where('type=1')->field('id,img,src')->order('rand DESC')->select();
		foreach($data['tj_line'] as &$val){
			$val['form']=$member_id;
		}
		
		//获取店名
		$title = D('WeiShops')->where('member_id='.$member_id)->getField('shop_name');
//		var_dump($title);
        //短线游
    //    $data['short_line'] = $model->lists(array('line_type'=>1),'*','',3);

        //长线游
      //  $data['long_line'] = $model->lists(array('line_type'=>2),'*','',3);
        //酒店
       // $data['hotle_line'] = $model->lists(array('line_type'=>3),'*','',3);
        //客栈
        //$data['kezhan_line'] = $model->lists(array('line_type'=>4),'*','',3);
        //国内航线
        //$data['guonei_line'] = $model->lists(array('line_type'=>5),'*','',4);
        //国际航线
        //$data['guoji_line'] = $model->lists(array('line_type'=>6),'*','',4);
        //var_dump($data);exit;
        $this->assign('list', $data);
        $this->assign('ad_list',$ad);
        $this->assign('title',$title);
        $this->countPage= ceil($countPage/10);
        $this->display();
    }


    /*
     * 我要开店
     * @author hesheng
     * 2017.5.29
     */
    public function open_shop(){
    	$regoin = D('Region')->where('parent_id=0')->field('id value,name text')->select();
		$data=D('WeiShops')->where('member_id='.getUid())->field('id,shop_name,cantent_name,cantent_mobile,provice_id,city_id')->find();
		if($data['provice_id']){
			$regoins[]=$data['provice_id'];
			$regoins[]=$data['city_id'];
		}
		$cityData=array();
		if($regoins){
			$where['id']=array('IN',$regoins);
			$area = D('Region')->where($where)->field('name')->getField('name',true);
			$data['provice_name']=$area[0];
			$data['city_name']=$area[1];
			$cityData = D('Region')->where('parent_id='.$data['provice_id'])->field('id value,name text')->select();
			
		}
		//获取帐号信息
		$accountData = D('WeiApliayAccount')->where('member_id='.getUid())->field('account_no,account_name')->find();
		$data['account_no']=$accountData['account_no'];
		$data['account_name']=$accountData['account_name'];
		$this->assign('cityData',json_encode($cityData));
		$this->assign('data',$data);
    	$this->assign('region',json_encode($regoin));
        $this->display();
    }
    
    /*
     * 获取城市
     */
    public function getCity(){
    	$parent_id = I('get.provice_id');
    	$cityData = D('Region')->where('parent_id='.$parent_id)->field('id value,name text')->select();
    	$str['cityData']= $cityData;
        $str['status']= 1;
        $str['msg']='成功';
        $this->ajaxReturn($str);
    }
    
    /*
     * 保存店信息
     * 2017.5.29
     */
	public function saveShop(){
		$shop_name = I('post.shop_name');
		$cantent_name = I('post.cantent_name');
		$cantent_mobile = I('post.cantent_mobile');
		$provice_id = I('post.provice_id');
		$city_id = I('post.city_id');
		$account_no = I('post.account_no');
		$account_name = I('post.account_name');
		if(!$shop_name){
			$this->error('请先填写微店名称！');
		}
		if(!$cantent_name){
			$this->error('请先填写联系人！');
		}
		if(!verfy_mobile($cantent_mobile)){
			$this->error('手机格式不正确！');
		}
		$member_id = getUid();
		$idata['shop_name']=$shop_name;
		$idata['cantent_name']=$cantent_name;
		$idata['cantent_mobile']=$cantent_mobile;
		$idata['provice_id']=$provice_id;
		$idata['city_id']=$city_id;
		$accountData['account_no'] =$account_no;
		$accountData['account_name'] =$account_name;
		$m=D('WeiShops');
		$accountModel = D('WeiApliayAccount');
		M()->startTrans();
		if($m->where('member_id='.$member_id)->count()){
			$res = $m->where('member_id='.$member_id)->save($idata);
			if($res && $accountData->where('member_id='.$member_id)->count()){
				$res = $accountData->where('member_id='.$member_id)->save($accountData);
			} else{
				$accountData['member_id']=$member_id;
				$accountData['create_time']=date('Y-m-d H:i:s');
				$res = $accountModel->add($accountData);
			}
			$res = $res &&  $accountModel->where('member_id='.$member_id)->save($accountData);
		}else{			
			$idata['create_time']=$accountData['create_time']=date('Y-m-d H:i:s');
			$idata['member_id']=$accountData['member_id']=$member_id;
			$res = $m->add($idata);
			$res = $res && $accountModel->add($accountData);
		}
		if($res!==false){
			M()->commit();
			$str['status']= 1;
			$str['info']='提交成功，请耐心等待审核';
			$this->ajaxReturn($str);
		}else{
			M()->rollback();
			$this->error('提交失败');
		}
	}
	
	
	/*
     * 异步获取分页
     *
     */
    public function ajaxList(){
        $model = D("Freetour");
        $page = I('post.page');
		$itemIds = D('WeiShopItems')->where('member_id='.getUid())->getField('freetour_id',true);
        //特价线路
        $where =  array(
            'old_price'=>array('gt',0),
			'id'=>array('IN',$itemIds)
        );
        $data = $model->getAjaxList(10,$where,'','id,images,price,title,old_price',$page);
        foreach($data['list'] as &$val){
        	$val['form']=getUid();
        }
        $str['contact']= $data['list'];
        $str['status']= 1;
        $str['msg']='成功';
        $this->ajaxReturn($str);
    }
	
}