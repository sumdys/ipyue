<?php
// 首页控制器
class ExtendAction extends IniAction {
    public function index(){
        $keyword = trim(I('get.keyword'));
        $type = trim(I('get.type'));
        $from = trim(I('get.from'));
        session('channel',$from);
        //特价线路
        $where['old_price'] =array('gt',0);
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
//           var_dump($countPage);exit;
		$ad = M('news_img')->where('type=1')->field('id,img,src')->order('rand DESC')->select();
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
        $this->countPage= ceil($countPage/10);
        $this->display();
    }



	/*
	 * 推订订单
	 * 
	 * 
	 */
	public function extend_order(){
		$pay_state=I('param.type');
		$model = D("TripOrder");
        $page = I('param.page');
        //特价线路
        $where[]=$where2[]=$where3[] = 'channel_type='.getUid();
        $fields = 'id,order_num,freetour_id,total_price,num,mobile,create_time,remark,start_date,pay_state';
        $allData = $model->ajaxExtendOrderList(10,$where,'',$fields,$page);
//      echo $model->getLastSql();exit;
        $where2['pay_state']=0;
        $nopayData = $model->ajaxExtendOrderList(10,$where2,'',$fields,$page);
        $where3['pay_state']=2;
        $cancelData = $model->ajaxExtendOrderList(10,$where3,'',$fields,$page);
//      var_dump($data);exit;
//echo $model->getLastSql();exit;
        $this->assign('allList',$allData['list']);
        $this->assign('nopayList',$nopayData['list']);
        $this->assign('cancelList',$cancelData['list']);
		$this->display();
	}
	
	
	/*
	 * 导步获取订单列表
	 * param int page 页码
	 * param int type 订单状态
	 * 
	 * 
	 */
	public function ajaxOrderList(){
		$pay_state=I('param.type');
		$model = D("TripOrder");
        $page = I('param.page');
        //特价线路
        $where =  array(
            'channel_type'=>getUid(),
        );
        $fields = 'id,order_num,freetour_id,total_price,num,mobile,create_time,remark,start_date,pay_state';
        $data = $model->ajaxExtendOrderList(10,$where,'',$fields,$page);
//      var_dump($data);exit;
//echo $model->getLastSql();exit;
        $str['contact']= $data['list'];
        $str['status']= 1;
        $str['msg']='成功';
        $this->ajaxReturn($str);
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
		$res['start_date']=date('Y-m-d',$res['start_date']);
		$res['create_time']=date('Y-m-d',$res['create_time']);
		$this->assign('list',$res);
		$this->display('orderdetail');
	}
	
	
	//详细
    public function detail(){
    	$this->title="自由行详细页";
        $this->channel=trim(I('get.channel'));
        if(!isset($_REQUEST['id'])){
            $this->error("页面不存在");
        }
        $this->info = D("Freetour")->info();
//        var_dump($this->info);exit;
        if(!$this->info){
            $this->error("页面不存在");
        }
		$this->assign('id',$_REQUEST['id']);
        $this->display();
    }
	
	/*
     * 异步获取分页
     *
     */
    public function ajaxList(){
        $model = D("Freetour");
        $page = I('param.page');
        //特价线路
        $where[] ='brokerage>0';
        $data = $model->getAjaxList(15,$where,'','id,title,old_price,brokerage',$page-1);
        $str['contact']= $data['list'];
        $str['status']= 1;
        $str['msg']='成功';
        $this->ajaxReturn($str);
    }
	
	
	/*
	 * 推广收益
	 * 
	 */
	public function profit(){
		$member_id=getUid();
		$freetour_ids=D('WeiShopItems')->where('member_id='.$member_id)->getField('freetour_id',true);
		if(!$freetour_ids){
			$this->error('没有订单');
		}
		//获取商品佣金
		$w['id']=array('IN',$freetour_ids);
		$w['brokerage']=array('gt',0);
		$goodsB =D('Freetour')->where($w)->getField('id,brokerage',true);
		$where['freetour_id']=array('IN',$freetour_ids);
        $where[]='channel_type='.$member_id;
        $where[]='pay_state=1';
		$list = D('TripOrder')->where($where)->field('id,order_num,create_time,freetour_id,total_price')->select();
		$order_money=$brokerage_money=0;
		foreach($list as &$val){
			$val['create_time']=date('Y-m-d',$val['create_time']);
			$val['brokerage']=$goodsB[$val['freetour_id']];
			$val['brokerage_rate']=sprintf('%0.2f',$goodsB[$val['freetour_id']]/$val['total_price'])*100;
			$order_money+=$val['total_price'];
			$brokerage_money+=$val['brokerage'];
		}
		$this->assign('order_money',$order_money);
		$this->assign('brokerage_money',$brokerage_money);
		$this->assign('list',$list);
		$this->display();
	}
	
	
	/*
	 * 选 择推广素材
	 * 
	 */
	public function addExtend(){
		$freetour_id = I('post.freetour_id');
		if(!$freetour_id){
            $this->error("请选择路线");			
		}
		
		$where['member_id']=getUid();
		$where['freetour_id']=$freetour_id;
		if(D('WeiShopItems')->where($where)->count()){
			$this->error("已选择该路线");
		}
		
		$data['member_id']=getUid();
		$data['freetour_id']=$freetour_id;
		$data['create_time']=date('Y-m-d H:i:s');
		if(D('WeiShopItems')->add($data)){
			$this->success('添加成功');
		}
		
		$this->error("选择路线失败");
	}
}