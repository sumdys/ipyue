<?php
class ActivityAction extends IniAction{
     public function index(){
        $this->title="活动频道";
        $this->newList=D('News')->getList(33,4,1);
        $this->newlist=$this->newList['list'];
       
        $hot = M('hot');
        $a_hot=$hot->where(array('status'=>1))->field('id,title,description,url,pic1,pic2,filename')->order('id desc')->limit(5)->select();
        $this->assign('a_hot', $a_hot);
        //echo $hot->getDbError();
        //$url='http://'.$_SERVER['SERVER_NAME'];
       //dump($a_hot);
		$this->display();
    }
	
	public function theme(){
        $this->title="主题活动";
        $hot = M('hot');
        $t_hot=$hot->where(array('status'=>1))->field('id,url,pic1,filename')->order('id desc')->select();
        $this->assign('t_hot', $t_hot);
		$this->display();
    }
	
	public function trademark(){
        $this->title="品牌专区";
		$this->display();
    }
	
	public function sale(){
        $this->title="特价汇";
        
        //最新推荐
        $special=M('Special');
        $sp1=$special->where(array('is_new'=>1))->field('id,from_city,to_city,air,price,img')->order('id desc')->limit(3)->select();
        $this->assign('sp1',$sp1);
        
        //特价汇
        $sp2=$special->where(array('is_new'=>0))->field('id,from_city,to_city,air,price,img,travel_time')->order('id desc')->limit(8)->select();
        $this->assign('sp2',$sp2);
        
		$this->display();
    }
	
	public function share(){
        //推荐商品
        $list=D('Mall')->where('type=1')->order('update_time desc')->limit('12')->select();
        $where['invite_id']=getUid();
        $this->fx_num=D("Member")->where($where)->count();
        $this->fun_list=$list;
        $this->title="会员分享活动首页";
		$this->display();
    }
	
	public function sharerule(){
        $this->title="会员分享活动规则";
		$this->display();
    }
}