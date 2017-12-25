<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-8-12
 * Time: 下午6:05
 * To change this template use File | Settings | File Templates.
 */

class CheapAction extends CommonAction{

    function index(){

        if(I('so')){
            if(strstr(I('so'),':')){
                $so=explode(':',I('so'));
                $map[$so[0]]=$so[1];
            }else{
                $where['zhou'] = array('like',"%".I('so')."%");
                $where['from_city'] = array('like',"%".I('so')."%");
                $where['to_city'] = array('like',"%".I('so')."%");
                $where['status']  = array('like',"%".I('so')."%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
        }
        I('zhou') && $map['zhou']=I('zhou');
        $this->map = $map;
        parent::index(D('cheap'));
        $data=D('Cheap')->getAdminList();

        $this->zhou=$data['zhou'];
        //$this->list=$data['list'];

        //$data=D('Cheap');
        //$data -> where($map)->select();
        //echo $data->getLastSql();
        $this->display();

    }

    //增加航班
    function add(){
        if($_POST){
            if($_POST['zhou']==''){
                $this->error('洲不能为空');
            }
            if($_POST['from_city']==''){
                $this->error('出发城市不能为空');
            }
            if($_POST['to_city']==''){
                $this->error('到达城市不能为空');
            }
            if($_POST['time']==''){
                $this->error('截止日期不能为空');
            }
            if($_POST['air']==''){
                $this->error('航空公司不能为空');
            }
            if($_POST['price']==''){
                $this->error('价格不能为空');
            }

            if($_FILES['img']['error']==0){
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();                       // 实例化上传类
                $upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
                $upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
                $upload->savePath =  './Public/uploads/cheap/';   // 设置附件上传目录
                $upload->uploadReplace = true;				  //同名则替换
                $upload->saveRule = 'uniqid';				 //设置上传头像命名规则(临时图片)

                if(!$upload->upload()) {// 上传错误提示错误信息
                    $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                    $info =  $upload->getUploadFileInfo();
                    $path=$upload->savePath;
                    $temp_size = getimagesize($path.$info['0']['savename']);
                }
                $info=array(
                    'img'          =>$info[0]['savename'],
                    'zhou'        =>$_POST['zhou'],
                    'from_city'   =>$_POST['from_city'],
                    'to_city'     =>$_POST['to_city'],
                    'time'      =>$_POST['time'],
                    'air'     =>$_POST['air'],
                    'price'     =>$_POST['price'],
                    'uid'  =>   $_SESSION['uid'],
                    'update_time'  =>time()
                );
            }else{
                $info=array(
                    //'img'          =>$info[0]['savename'],
                    'zhou'        =>$_POST['zhou'],
                    'from_city'   =>$_POST['from_city'],
                    'to_city'     =>$_POST['to_city'],
                    'time'      =>$_POST['time'],
                    'air'     =>$_POST['air'],
                    'price'     =>$_POST['price'],
                    'uid'  =>   $_SESSION['uid'],
                    'update_time'  =>time()
                );
            }
            $Ch = D('Cheap');
            if(!$Ch->create($info)){
                $this->error($Ch->getError());
            }
            $res=$Ch->add();
            if($res){
                $this->success('航班添加成功');
            }else{
                $this->error('航班添加失败');
            }
        }else{
            $this->display();
        }
    }

    //修改航班
    function edit(){
        if($_POST){
            if($_FILES['img']['error']==0){
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();                       // 实例化上传类
                $upload->maxSize  = 2*1024*1024 ;                // 设置附件上传大小
                $upload->allowExts  = array('jpg','gif','png'); // 设置附件上传类型
                $upload->savePath =  './Public/uploads/cheap/';   // 设置附件上传目录
                $upload->uploadReplace = true;				  //同名则替换
                $upload->saveRule = 'uniqid';
                if(!$upload->upload()) {// 上传错误提示错误信息
                    $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                    $info =  $upload->getUploadFileInfo();
                    $path=$upload->savePath;
                    $temp_size = getimagesize($path.$info['0']['savename']);
                }
                $info=array(
                    'img'          =>$info[0]['savename'],
                    'zhou'        =>$_POST['zhou'],
                    'from_city'   =>$_POST['from_city'],
                    'to_city'     =>$_POST['to_city'],
                    'time'      =>$_POST['time'],
                    'air'     =>$_POST['air'],
                    'price'     =>$_POST['price'],
                    'uid'  =>   $_SESSION['uid'],
                    'update_time'  =>time()
                );
            }else{
                $info=array(
                    'zhou'        =>$_POST['zhou'],
                    'from_city'   =>$_POST['from_city'],
                    'to_city'     =>$_POST['to_city'],
                    'time'      =>$_POST['time'],
                    'air'     =>$_POST['air'],
                    'price'     =>$_POST['price'],
                    'uid'  =>   $_SESSION['uid'],
                    'update_time'  =>time()
                );
            }

            $AdDb=D('Cheap');
            if(!$AdDb->create($info)){
                $this->error($AdDb->getError());
            }
            $res=$AdDb->where('id='.$_POST['id'])->save($info);
            if($res){
                $this->success('修改成功');
            }else{
                $this->error('修改失败'.$AdDb->getDbError());
            }
        }elseif(I('act')=='del_img'){
            $this->ajaxReturn( D('Cheap')->delImg());exit;
        }
        else{
            $id = $_GET['id'];
            $this->info=D('Cheap')->where('id='.$id)->find();
            $this->display();
        }
    }

    //删除航班
    function del(){
        $res=D('Cheap')->where('id='.$_GET['id'])->delete();
        if($res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}