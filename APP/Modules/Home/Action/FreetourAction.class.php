<?php
// 首页控制器
class FreetourAction extends IniAction {
    public function index(){

        $this->title="品悦定制旅游_定制旅游";
        $this->keywords="品悦旅行，定制旅行，定制旅游，旅行定制，旅行定制师，海外旅行，旅游路书，旅行";
        $this->description="品悦定制旅行最专业的海外旅行定制专家。我们拥有优秀的旅行定制师团队，凭借多年深度旅行和海外旅居经历，为想要出国旅行的朋友提供咨询服务。专业的推荐、精彩的设计、严谨的安排、24小时电话和网络协助，一切都只为，让你的旅行有温度。";

        $data = array();

        $model = D("Freetour");

        //特价线路
        $where =  array(
            'old_price'=>array('gt',0)
        );
        $data['tj_line'] = $model->lists($where,'*','',9);
//var_dump($data);
        //短线游
        $data['short_line'] = $model->lists(array('line_type'=>1),'*','',3);

        //长线游
        $data['long_line'] = $model->lists(array('line_type'=>2),'*','',3);
        //酒店
        $data['hotle_line'] = $model->lists(array('line_type'=>3),'*','',3);
        //客栈
        $data['kezhan_line'] = $model->lists(array('line_type'=>4),'*','',3);
        //国内航线
        $data['guonei_line'] = $model->lists(array('line_type'=>5),'*','',6);
        //国际航线
        $data['guoji_line'] = $model->lists(array('line_type'=>6),'*','',6);
        //独家资源
        $data['dujia_line'] = $model->lists(array('line_type'=>7),'*','',4);
        //跟团游
        $data['gentuan_line'] = $model->lists(array('line_type'=>8),'*','',4);
        //高端游
        $data['gaoduan_line'] = $model->lists(array('line_type'=>9),'*','',4);
        //亲子游学
        $data['gaoduan_line'] = $model->lists(array('line_type'=>10),'*','',4);
        //景点门票
        $data['gaoduan_line'] = $model->lists(array('line_type'=>11),'*','',4);

        $this->assign('list', $data);
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
        $data['guonei_line'] = $model->lists(array('line_type'=>5),'*','',15);
        //国际航线
        $data['guoji_line'] = $model->lists(array('line_type'=>6),'*','',15);

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
        $data['short_line'] = $model->lists(array('line_type'=>1),'*','',15);

        //长线游
        $data['long_line'] = $model->lists(array('line_type'=>2),'*','',15);

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
        $data['hotle_line'] = $model->lists(array('line_type'=>3),'*','',30);
        //客栈
        $data['kezhan_line'] = $model->lists(array('line_type'=>4),'*','',15);


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
        $keyword = I('get.to');
//        if($from){
//            $where_city['name']=$from;
//            $where['dcity']=M('City')->where($where_city)->getField('iata');
//        }
        if($days){
            $where['days']=$days;
        }
        if($keyword){
            $where['title']=array('like',"%{$keyword}%");
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
        if(!isset($_REQUEST['id'])){
            $this->error("页面不存在");
        }
        $model=D("Freetour");
        $this->info = $model->info();
        if(!$this->info){
            $this->error("页面不存在");
        }
        //特价
        $ids = D('Activity')->getActivityList();
        $speciaPrice = array();
        if($ids){
            $where['id']=array('in',$ids);
            $fields='id,title,price';
            $order = 'sorts ASC,create_time DESC';
            $speciaPrice = $model->lists($where,$fields,$order,10);
        }
        $where_1['id']=array('gt',0);
        $hotFreetour = $model->lists($where_1,$fields,$order,10);
//        var_dump($speciaPrice);exit;
        $this->assign('speciaPrice',$speciaPrice);
        $this->assign('hotFreetour',$hotFreetour);
		$this->assign('id',$_REQUEST['id']);
        $this->display();
    }
}