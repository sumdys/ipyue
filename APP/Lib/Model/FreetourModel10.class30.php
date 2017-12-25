<?php
/* * Created by JetBrains PhpStorm.
 * User: pengfei
 * To change this template use File | Settings | File Templates.
 */
class FreetourModel extends RelationModel {
    protected $_link = array(
        'User'=> array(
            'mapping_type'=>BELONGS_TO,
            'class_name'=>'User',
            'foreign_key'=>'aid',
            'mapping_fields'=>'id,name',
            // 定义更多的关联属性 relation(true)
        )
    );

    protected $_auto = array (
        array('create_time','time',1,'function'),
        array('create_uid','getUid',1,'function'),
        array('update_time','getUid',2,'function'),
        array('update_uid','getUid',2,'function'),
    );

    public function lists($where=array(),$fie='*',$order='',$limit=10) {
        $where['status'] = 1;
        $order = $order?$order:'published desc';
        $list = $this->field($fie)->where($where)->order($order)->limit($limit)->select();
//      var_dump($list);
//        echo $this->getLastSql();exit;
        if($list){
        	$CityModel = D('City');
            foreach($list as $key=>$val){
                isset($list[$key]['published']) && $list[$key]['published']=date("Y-m-d H:i:s",$val['published']);
                isset($list[$key]['images']) && $list[$key]['images']  = json_decode($val['images'],true);
                
                isset($list[$key]['package']) && $list[$key]['package'] = explode(',',$val['package']);                
                isset($list[$key]['dcity']) &&  ($list[$key]['dcity_name'] = $val['dcity']?$CityModel->getCityName($val['dcity']):'');
                isset($list[$key]['acity']) &&   ($list[$key]['acity_name'] = $val['acity']?$CityModel->getCityName($val['acity']):'');
            }
        }
//          var_dump($list);
        return $list;
    }


    public function addNews() {
        $data = $_POST['info'];
        $data['published']=strtotime($data['published']);
        $data['dcity'] = preg_replace("/([^A-Z]+)/","",$data['dcity']);
        $data['acity'] = preg_replace("/([^A-Z]+)/","",$data['acity']);
        $data['package'] = implode(',',$data['package']);
        $data['images'] = json_encode(array_filter($_POST['images']));
        $data['tags'] = $_POST['tag_name']?json_encode(array_filter($_POST['tag_name'])):'';
        if (empty($data['description'])) {
            $data['description'] = cutStr($data['content'], 200);
        }
//        var_dump($data);exit;
        $this->create($data);
        if ($this->add()) {
            return array('status' => 1, 'info' => "已经发布", 'url' => U('News/index'));
        } else {
//            echo $this->getLastSql();exit;
            return array('status' => 0, 'info' => "发布失败，请刷新页面尝试操作");
        }
    }

    public function edit() {
        $data = $_POST['info'];
        $data['published']=strtotime($data['published']);
        $data['dcity'] = preg_replace("/([^A-Z]+)/","",$data['dcity']);
        $data['acity'] = preg_replace("/([^A-Z]+)/","",$data['acity']);
        $data['package'] = implode(',',$data['package']);
        $data['images'] = json_encode(array_filter($_POST['images']));
        $data['tags'] = $_POST['tag_name']?json_encode(array_filter($_POST['tag_name'])):'';
        if (empty($data['description'])) {
            $data['description'] = cutStr($data['content'], 200);
        }

        $id = $this->where('sorts='.$data['sorts'])->getField('id');
        if ($this->save($data)) {
//          echo M()->getLastSql();exit;
            //更新已的排序
            if($id){
                $saveData['sorts']=99;
                $this->where('id='.$id)->save($saveData);
            }

            return array('status' => 1, 'info' => "已经更新");
        } else {
            return array('status' => 0, 'info' => "更新失败，请刷新页面尝试操作");
        }
    }

    //列表
    function getList($limit,$where,$order='',$fields='*'){
        import('ORG.Util.Page');// 导入分页类
        $where["status"]=1;
        $order = $order?$order:"published desc";
        $count      = $this->where($where)->count();// 查询满足要求的总记录数
        import('ORG.Util.Page');// 导入分页类
        $Page       = new Page($count,$limit);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出
        $list=$this->field($fields)->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page']=$show;
        $CityModel = D("City");
        foreach($list as $key=>$val){
            $list[$key]['images']  = json_decode($val['images'],true);
            $list[$key]['dcity_name'] = $val['dcity']?$CityModel->getCityName($val['dcity']):'';
            $list[$key]['acity_name'] = $val['acity']?$CityModel->getCityName($val['acity']):'';
        }
        $data['list']=$list;
        return $data;
    }

    /*
     * 异步获取列表
     */
    function getAjaxList($limit,$where,$order='',$fields='*',$page){
        $where["status"]=1;
        $order = $order?$order:"published desc";
        $firstRow =  $page*$limit;
		$list=$this->field($fields)->where($where)->order($order)->limit($firstRow.','.$limit)->select();
        $CityModel = D("City");
        foreach($list as $key=>$val){
            $list[$key]['images']  = json_decode($val['images'],true);
            $list[$key]['dcity_name'] = $val['dcity']?$CityModel->getCityName($val['dcity']):'';
            $list[$key]['acity_name'] = $val['acity']?$CityModel->getCityName($val['acity']):'';
        }
        $data['list']=$list;
        return $data;
    }

    function info(){
        $CityModel = D("City");
        $info=$this->find(I('id'));
        if($info){
            $info['dcity_name'] = $info['dcity']?$CityModel->getCityName($info['dcity']):'';
            $info['acity_name'] = $info['acity']?$CityModel->getCityName($info['acity']):'';
            $info['published']=date("Y-m-d H:i:s",$info['published']);
            $info['images']  = array_filter(array_unique(json_decode($info['images'],true)));
            $info['tags']  = array_filter(array_unique(json_decode($info['tags'],true)));
            $info['package'] = explode(',',$info['package']);
        }
        return $info;
    }




}
