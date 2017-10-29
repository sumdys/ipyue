<?php
// 首页控制器
class FreetourAction extends IniAction {
    public function index(){
        $this->title="【品悦定制旅游】定制旅游";
        $this->keywords="品悦旅行，定制旅行，定制旅游，旅行定制，旅行定制师，海外旅行，旅游路书，旅行";
        $this->description="品悦定制旅行最专业的海外旅行定制专家。我们拥有优秀的旅行定制师团队，凭借多年深度旅行和海外旅居经历，为想要出国旅行的朋友提供咨询服务。专业的推荐、精彩的设计、严谨的安排、24小时电话和网络协助，一切都只为，让你的旅行有温度。";

        $data = array();

        $model = D("Freetour");

        //特价线路
        $where =  array(
            'old_price'=>array('gt',0)
        );
        $data['tj_line'] = $model->lists($where,'*','',12);

        //短线游
        $data['short_line'] = $model->lists(array('line_type'=>1),'*','',3);

        //长线游
        $data['long_line'] = $model->lists(array('line_type'=>2),'*','',3);

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

        $order='';
        $data = D("Freetour")->getList(20,$where,$order);
        $this->list = $data['list'];
        $this->page = $data['page'];
        $this->display();
    }

    //详细
    public function detail(){
    	$this->title="自由行详细页";
        if(!isset($_REQUEST['id'])){
            $this->error("页面不存在");
        }
        $this->info = D("Freetour")->info();
        if(!$this->info){
            $this->error("页面不存在");
        }

        $this->display();
    }
}