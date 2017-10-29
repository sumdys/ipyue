<?php
// 首页控制器
class FreetourAction extends IniAction {
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
//           var_dump($data['tj_line']);exit;
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

//国内航线邮轮
    public function guonei(){
         $this->title="邮轮";
        $this->keywords="品悦旅行，定制旅行，定制旅游，旅行定制，旅行定制师，海外旅行，旅游路书，旅行";
        $this->description="品悦定制旅行最专业的海外旅行定制专家。我们拥有优秀的旅行定制师团队，凭借多年深度旅行和海外旅居经历，为想要出国旅行的朋友提供咨询服务。专业的推荐、精彩的设计、严谨的安排、24小时电话和网络协助，一切都只为，让你的旅行有温度。";
        $data = array();

        $model = D("Freetour");


        //国内航线
        $data['guonei_line'] = $model->lists(array('line_type'=>5),'*','',16);
        //国际航线
        $data['guoji_line'] = $model->lists(array('line_type'=>6),'*','',16);

        $this->assign('list', $data);
        $this->display();
    }
//路线页面
    public function luxian(){
        $this->title="景点路线";
        $this->keywords="品悦旅行，定制旅行，定制旅游，旅行定制，旅行定制师，海外旅行，旅游路书，旅行";
        $this->description="品悦定制旅行最专业的海外旅行定制专家。我们拥有优秀的旅行定制师团队，凭借多年深度旅行和海外旅居经历，为想要出国旅行的朋友提供咨询服务。专业的推荐、精彩的设计、严谨的安排、24小时电话和网络协助，一切都只为，让你的旅行有温度。";

        $data = array();

        $model = D("Freetour");

        //特价线路
        $where =  array(
            'old_price'=>array('gt',0)
        );
        $data['tj_line'] = $model->lists($where,'*','',8);

        //短线游
        $data['short_line'] = $model->lists(array('line_type'=>1),'*','',16);

        //长线游
        $data['long_line'] = $model->lists(array('line_type'=>2),'*','',16);

        $this->assign('list', $data);
        $this->display();
    }
//客栈酒店
    public function kezhan(){
        $this->title="别墅预订_酒店预订_客栈预订_客栈价格查询_客栈推荐,网上订客栈";
        $this->keywords="别墅预订,客栈预订,酒店,酒店预订,酒店查询,酒店地址,宾馆住宿推荐,网上订酒店";
        $this->description="品悦旅行网为您提供高性价比的在线别墅，酒店，客栈预订服务。在品悦旅行，你可以查询全国数千城市的酒店,别墅,客栈信息,客栈价格,以及客栈地址、客栈图片。我们为您推荐优质的别墅,酒店及客栈、真实用户点评等客栈信息。品悦拥有国内外数千万会员别墅及客栈,是中国领先的别墅及客栈预订服务中心";

        $data = array();

        $model = D("Freetour");

        //酒店
        $data['hotle_line'] = $model->lists(array('line_type'=>3),'*','',32);
        //客栈
        $data['kezhan_line'] = $model->lists(array('line_type'=>4),'*','',16);


        $this->assign('list', $data);
        $this->display();
    }

     // 自由行定制
    public function diy(){
    	$this->title="自由行定制";
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

    //详细
    public function detail(){
    	$this->title="自由行详细页";
        $this->channel=trim(I('get.channel'));
        $from = trim(I('get.from'));
        session('channel',$from);
        if(!isset($_REQUEST['id'])){
            $this->error("页面不存在");
        }
        $this->info = D("Freetour")->info();
//        var_dump($this->info);exit;
        if(!$this->info){
            $this->error("页面不存在");
        }
		$this->assign('id',$_REQUEST['id']);
        //获取微信信息
        require_once "APP/Lib/ORG/Wxpay/JsSdk.class.php";
        $jssdk = new JSSDK();
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('data',$signPackage);
        $this->assign('from',$from);
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
        $where =  array(
            'old_price'=>array('gt',0)
        );
        $data = $model->getAjaxList(10,$where,'','id,images,price,title,old_price',$page);
//        $list='';
//        if($data['list']){
//            foreach($data['list'] as $val){
//                $list.='<dt>
//				<a href="{:U(\'freetour/detail\')}/id/'.$val['id'].'" class="c01">
//				<img src="__PUBLIC__/uploads'.$val[images][0].'" width="100%" height="100%">
//
//					<div class="ft-tj-d">
//						<div style="float: left;margin: 5px;font-size: 1.2em;">
//						<font color="#7A7A7A"><B>'.$val['title'].'</B></font><br><font color="#EE2C2C">现价:'.$val['price'].'RMB</font>&nbsp;&nbsp;&nbsp;<font color="#C5C1AA"><del>原价:'.$val['old_price'].'RMB</del></font>
//						</div>
//					</div>
//			</a>
//			</dt>';
//            }
//        }
        $str['contact']= $data['list'];
        $str['status']= 1;
        $str['msg']='成功';
        $this->ajaxReturn($str);
    }
}