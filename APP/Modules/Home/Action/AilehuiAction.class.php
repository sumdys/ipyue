<?php
class AilehuiAction extends IniAction {
    public function index(){
        $this->left();
		$this->title='爱乐汇';
        $this->display();

    }

    function left(){
        if($this->userInfo){
            //用户信息
            $points=D('Points');
            $where['member_id']=getUid();
            $where['type2']=0;
            $this->jfpoints=$points->where($where)->sum('points');
            $where['type2']=1;
            $this->azpoints=$points->where($where)->sum('points');
            $where['type2']=2;
            $this->xjjpoints=$points->where($where)->sum('points');

            //我的特权
            $userInfo=$this->userInfo;

            // print_r($userInfo);

            $arrPrivilege=json_decode($userInfo['rank']['privilege']);
            $where['id']=array("in",$arrPrivilege);
            $this->myPrivilege=D("MemberPrivilege")->where($where)->select();

            $map['id']=array("gt",$userInfo['rank_id']);
            $gjRank=D("member_rank")->where($map)->find();

            ///高一级权限
            $arrGjPrivilege=json_decode($gjRank['privilege']);
            $gj=array_diff_assoc($arrGjPrivilege,$arrPrivilege);
            $where['id']=array("in",$gj);
            $this->gjPrivilege=D("MemberPrivilege")->where($where)->select();
        }


        $memberRank=D("MemberRank")->select();
        foreach($memberRank as $val){
            $rankArr[$val['id']]=$val;
        }
        $this->rankArr=$rankArr;
        $map['rank_id']=array('gt',1);
        $this->upgrade_list=D("Member")->field('id,username,rank_id,upgrade_valid_time')->where($map)->order('upgrade_valid_time desc')->limit('10')->select();

    }

	function upgrade(){
        $this->left();
        if($this->userInfo){
            $userInfo=$this->userInfo;
            $hyid = $userInfo['asms_member_id'];

            $orderDB=D("AsmsOrder");
            $where['hyid'] =array("in",array($hyid,getUid()));
            $where['zf_fkf']=1; //已支付
            $pagesize=10; //每页显示的记录
            $count= $orderDB->where($where)->count();//总记录数
            if($count){
                if(IS_AJAX){
                    $totlePage=ceil($count/$pagesize);//总页数
                    $page=I('p')>1 && I('p')<=$totlePage?I('p'):1;//定义当前页
                    $offset=($page-1)*$pagesize;
                    $list= $orderDB->where($where)->limit($offset,$pagesize)->select();
                    $data['totlePage']=$totlePage;
                    $data['page']=$page;
                    $data['status']=1;
                    $data['list']=$list;
                    $this->ajaxReturn($data);
                }

                $orderList=$orderDB->where($where)->limit($pagesize)->select();
                //  print_r($this->orderList);
                $orderList=$orderDB->format($orderList);
                foreach($orderList as $k=>$v){
                    $v['hc']=str_split($v['hc'],3);
                    $v['hc_a'] = D("City")->getCity( $v['hc']);
                    $v['hc_n']=implode('-',$v['hc_a']);
                    $orderList[$k]=$v;
                }
                $this->orderList=$orderList;
            }
            $this->upgradeInfo=D("Member")->upgradeInfo();
            $str=$userInfo['rank']['variables'].";";
            eval($str);
            $this->upgradeVariables=array(
                'price'=>$price,
                'count'=>$count
            );
        }

        $this->title='会员升级';
        $this->display();
    }
    function myprivilege(){
        $this->left();
        $this->privilege=getUid()?$this->myPrivilege:D("MemberPrivilege")->order("sort")->select();
        $this->title='会员特权';
        $this->display();
    }
	
	function birthdaygift(){
        $userInfo=$this->userInfo;
        if(IS_POST){
            $uid=getUid();
           if(!$uid) $this->error("请先登陆 再提交");
           if(!I('year') || !I('month') || !I("day")){
               $this->error("提交日期有误");
           }
           if($userInfo['birthday'] && $userInfo['birthday']!="1900-1-1"){
               $this->error("生日日期已提交不可进行更改哦");
           }
           $data['id']=$uid;
           $data['birthday']=I('year')."-".I('month')."-".I("day");
           $Member=D("Member");
           $Member->create($data);
           if($Member->save()){
               $this->success("预订成功");
           }
        }
        $this->left();
        $this->title='生日大礼包';
        $this->display();
    }

}
