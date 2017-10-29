<?php
// 后台用户模块
class MarketingAction extends CommonAction {


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
        $this->order='id desc';
        parent::index(D('Freetour'));
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