<?php
// 订单控制器
class OrdersAction extends Action {
    /*
     * 下单验证是否已登陆
     *
     */
    public function create_order(){
//                               echo 111;exit;
        if(session('uid')) {
            $this->title = "下单页面";
            if (!isset($_REQUEST['id'])) {
                $this->error("页面不存在");
            }
            $this->info = D("Freetour")->info();
            if (!$this->info) {
                $this->error("页面不存在");
            }
            $this->assign('beginYear', '{"type":"date","beginYear":' . date('Y', time()) . '}');
            $this->assign('id', $_REQUEST['id']);
            $this->display();
        }else{
            $this->redirect('/Member/login');
        }
    }


    public function index(){
        $keyword = trim(I('get.keyword'));
        $type = trim(I('get.type'));
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
            }

        }
        $this->title="【品悦定制旅游】定制旅游";
        $this->keywords="品悦旅行，定制旅行，定制旅游，旅行定制，旅行定制师，海外旅行，旅游路书，旅行";
        $this->description="品悦定制旅行最专业的海外旅行定制专家。我们拥有优秀的旅行定制师团队，凭借多年深度旅行和海外旅居经历，为想要出国旅行的朋友提供咨询服务。专业的推荐、精彩的设计、严谨的安排、24小时电话和网络协助，一切都只为，让你的旅行有温度。";

        $data = array();

        $model = D("Freetour");

        $fields = 'id,images,price,title,old_price';
        $data['tj_line'] = $model->lists($where,$fields,'',10);

        $this->assign('list', $data);
        $this->display();
    }



    //查询
    public function query(){
    	$this->title="自由行查询";
        //搜索条件
        $where = array();
        $from = I('get.from');
        $days = I('get.days');
        if($from){
            $where_city['name']=$from;
            $where['dcity']=M('City')->where($where_city)->getField('iata');
        }
        if($days){
            $where['days']=$days;
        }
        //获取城市列表
        $where_city=array();
        $where_city['hot']=1;
        $cityData = D('City')->getCityList($where_city,0,9);
        $cityDataSecond = D('City')->getCityList($where_city,10,100);
//        var_dump($cityData);
        //获取天数
        $daysList = M('Freetour')->field('days')->limit(5)->group('days')->select();
        $order='';
        $data = D("Freetour")->getList(20,$where,$order);
        $this->list = $data['list'];
        $this->city_list=$cityData;
        $this->city_list_more=$cityDataSecond;
        $this->days_list=$daysList;
        $this->page = $data['page'];
        $this->display();
    }



    /*
     * 异步获取分页
     *
     */
    public function ajaxList(){
        $model = D("Freetour");
        $page = I('get.page');
        //特价线路
        $where =  array(
            'old_price'=>array('gt',0)
        );
        $data = $model->getAjaxList(10,$where,'','id,images,price,title,old_price',$page);
        $list='';
        if($data['list']){
            foreach($data['list'] as $val){
                $list.='<dt>
				<a href="{:U(\'freetour/detail\')}/id/'.$val['id'].'" class="c01">
				<img src="__PUBLIC__/uploads'.$val[images][0].'" width="100%" height="100%">

					<div class="ft-tj-d">
						<div style="float: left;margin: 5px;font-size: 1.2em;">
						<font color="#7A7A7A"><B>'.$val['title'].'</B></font><br><font color="#EE2C2C">现价:'.$val['price'].'RMB</font>&nbsp;&nbsp;&nbsp;<font color="#C5C1AA"><del>原价:'.$val['old_price'].'RMB</del></font>
						</div>
					</div>
			</a>
			</dt>';
            }
        }
        echo $list;exit;
        $str['contact']= $list;
        $this->ajaxReturn($str);
    }

    /*
     * 异步下单
     *
     */
    public function submitOrderAjax(){
        if(IS_AJAX){
            $now_time = time();
            $Input=(I('post.'));
            $data['freetour_id']=$Input['id'];
            $info =  D("Freetour")->info();
            //验证路线
            if(!$info){
                $res['msg']='路线已下架或不存在';
                $res['status']=0;
                $this->ajaxReturn($res);
            }
            //验证手机号
            if(!verfy_mobile($Input['mobile'])){
                $res['msg']='请填写正确联系手机号';
                $res['status']=0;
                $this->ajaxReturn($res);
            }
            $data['mobile']=$Input['mobile'];
            $data['linkman']=$Input['linkman'];
            $data['start_date']=strtotime($Input['start_date']);
            $data['remark']=$Input['msg']?$Input['msg']:'';
            $data['pay_type']=2;
            $data['member_id']=getUid();
            $data['update_time']=$now_time;
            $data['create_time']=$now_time;
            $data['total_price']=$info['price']*$Input['num'];
            $data['num']=$Input['num'];
            $data['pay_state']=0;
            $data['channel_type']=session('channel')?session('channel'):1;
            $data['order_num'] = rand(100,999).date('YmdHis').rand(10000,99999); //订单号
            $re=D('TripOrder')->add($data);
            if($re){
                $res['oid']=$re;
                $res['status']=1;
            }else{
                $res['msg']='下单失败';
                $res['status']=0;
            }
        }else{
            $res['oid']=0;
            $res['status']=0;
        }
        $this->ajaxReturn($res);
    }

    /*
     * 确认订单
     *
     */
    public function confirm(){
        $orderId = I('get.oid');
        $info = D('TripOrder')->getOrderInfo($orderId);
        session('orderData',$info['data']);
//        var_dump(session('orderData'));
        $this->assign('info',$info['data']);
//        $this->jsApiParameters=$this->jsapi();
        $this->display();
    }


}