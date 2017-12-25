<?php
// 后台用户模块
class MarketingApplyAction extends CommonAction {


    function index(){
        $title=I('post.title');
        $status = I('post.status');
        $start_date = I('post.start_date');
        $end_date = I('post.end_date');
        if($title){
            $map['cantent_name|cantent_mobile']= array('like',"%".$title ."%");
        }
        if($status){
            $map['status']=$status;
        }
        if($start_date){
            $start_date = strtotime($start_date);
            $map[]='UNIX_TIMESTAMP(create_time)>='.$start_date;
        }
        if($end_date){
            $end_date = strtotime($end_date);
            $map[]='UNIX_TIMESTAMP(create_time)>='.$end_date;
        }
//        else{
//            $map['status']=$status;
//        }
        $this->map=$map;
//        $this->relation=false;
        $this->order='id desc';
        parent::index(D('WeiShops'));
//        echo M()->getLastSql();exit;
        $list = $this->list;
        if($list){
            $CityModel = D("City");
            foreach($list as $key=>$val){
                isset($list[$key]['published']) && $list[$key]['published']=date("Y-m-d H:i:s",$val['published']);
                isset($list[$key]['images']) && $list[$key]['images']  = json_decode($val['images'],true);
                isset($list[$key]['package']) && $list[$key]['package'] = explode(',',$val['package']);
                isset($list[$key]['dcity']) &&  ($list[$key]['dcity_name'] = $val['dcity']?$CityModel->getCityName($val['dcity']):'');
                isset($list[$key]['acity']) &&   ($list[$key]['acity_name'] = $val['acity']?$CityModel->getCityName($val['acity']):'');
            }
        }

        $this->list = $list;
        $this->display();
    }


    /*
     * 审核操作
     * @author hesheng
     * 2017.5.24
     */
    public function changeStatus(){
        $id = I('get.id');
        $status=I('get.status');
        $msg = $status==1?'审核通过':'审核不通过';
        $data['status']=$status;
        $data['application_time']=date('Y-m-d H:i:s');
        $data['applictioner']=$_SESSION['loginUserName'];
        
        $member_id = D('WeiShops')->where('id='.$id)->getField('member_id');
        M()->startTrans();
        if(!D('WeiShops')->where('id='.$id)->save($data)){        	
        	M()->rollback();
            $this->error($msg.'失败');
        }
        $member_data['shop_type']=1;
        $sql = 'UPDATE `asf_member` SET shop_type=1 WHERE id='.$member_id;
        if(!D('member')->execute($sql)){
        	M()->rollback();
            $this->error($msg.'失败');
        }
        M()->commit();
       	$this->success($msg.'成功');
    }


    function add(){
        if (IS_POST) {
            $rs=D("Freetour")->addNews();
            if($rs['status']==1){
                $this->success($rs['info']);
            }else{
                $this->error($rs['info']);
            }
        } else {
            $this->display();
        }
    }

    function edit(){
        if (IS_POST) {
            $data['id']=$_POST['info']['id'];
            $data['brokerage']= $_POST['brokerage'];
            $rs=D("Freetour")->save($data);
            if($rs){
                $this->success('保存成功');
            }else{
                $this->error('保存失败');
            }
        } else {
            $this->info = D("Freetour")->info();
            $this->display();
        }
    }


    function _before_update(){
        $_POST['published']=strtotime($_POST['published']);
    }


}
?>