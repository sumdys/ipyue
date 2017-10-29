<?php
// 商城
class MallAction extends Action {
	
	//商城首页
    public function index(){
		
		//分类
		$this->jf_cate=D('MallCategory')->where('pid=55')->field('cid,name')->select();
		$this->az_cate=D('MallCategory')->where('pid=56')->field('cid,name')->select();
		
		//积分好礼推荐	
		$where['status']=1;		
		$where['tj']=1;
		$where['type']=0;
		$this->jifen_tj=D('Mall')->field('id,title,img,jifen,amount')->where($where)->order("create_time DESC")->select();
		//爱钻兑好礼推荐
		$where['type']=1;
		$this->aizuan_tj=D('Mall')->field('id,title,img,jifen,amount')->where($where)->order("create_time DESC")->select();	

		$this->title="商城首页";
        $this->display();
    }
	
	//商城分类页
    public function classlist(){
		if($_GET){
			$pid=I('oneClass');
			$cid=I('twoClass');
			$search=I('searchCont');			
			if(empty($search)){
				import('@.ORG.Category'); 			
				$category=M('mall_category')->field('pid,cid,name')->select();
				$clist=list_to_tree($category,$cid);
				 foreach($clist as $key=>$val){	
					$where['cid']=$val['cid'];
					$clist[$key]['info']=D('Mall')->where($where)->field('id,title,img,jifen,amount')->select(); 
				 }		
				$this->clist=$clist;
				$this->type=1;
			}else{
				//$where['cid']=$cid;
				$where['title']=array('like','%'.$search.'%');
				$result=D("Mall")->field('id,title,img,jifen,amount')->where($where)->order("create_time DESC")->select();
				$this->result=$result;
			}
			$this->pid=$pid;
			$this->title="商城搜索结果";
			$this->display();			
		}
    }
	
	
}?>