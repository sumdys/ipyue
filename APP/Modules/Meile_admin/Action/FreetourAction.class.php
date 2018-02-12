<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-2
 * Time: 下午5:16
 * To change this template use File | Settings | File Templates.
 */
//需求模型
class FreetourAction extends CommonAction{
    function _before_index(){
    	$this->relation=true;
    }

    function upload(){
        parent::upload('','','freetour',true,300,200);
    }

    function index(){
        $title=I('post.title');
        $status = I('post.status');
        if($title)
            $map['title']= array('like',"%".$title ."%");
        if($status){
            $map['status']=$status;
        }
//        else{
//            $map['status']=$status;
//        }
        $this->map=$map;
//        $this->relation=false;
        $this->order='sorts ASC,id DESC';
        parent::index(D('Freetour'));
        $list = $this->list;
//        var_dump($data);
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
   


    function add(){
       if (IS_POST) {
            $rs=D("Freetour")->addNews();
            if($rs['status']==1){
                $this->success($rs['info']);
            }else{
                $this->error($rs['info']);
            }
        } else {
            $sorts = range(1,20);
            $this->assign('sorts',$sorts);
            $this->display();
        }
    }

    function edit(){
        if (IS_POST) {
            $rs=D("Freetour")->edit();
            if($rs['status']==1){
                $this->success($rs['info']);
            }else{
                $this->error($rs['info']);
            }
        } else {
            $sorts = range(1,20);
            $this->assign('sorts',$sorts);
            $this->info = D("Freetour")->info();
            $this->display();
        }
    }


    /*
     * 限时抢购
     * @author hesheng
     * 2017.12.26
     */
    public function activity(){
        if (IS_POST) {

            $rs=D("Activity")->addNews();
            if($rs['status']==1){
                $this->success($rs['info']);
            }else{
                $this->error($rs['info']);
            }
        } else {
            $freetour_id = I('get.id');
            $sorts = range(1,20);
            $this->assign('sorts',$sorts);
            $this->assign('freetour_id',$freetour_id);
            $this->display();
        }
    }


    function _before_update(){
       $_POST['published']=strtotime($_POST['published']);
    }

	

}?>