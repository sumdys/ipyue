<?php
/* * Created by JetBrains PhpStorm.
 * User: pengfei
 * To change this template use File | Settings | File Templates.
 */
class ActivityModel extends RelationModel {

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
        $now_time = time();
        $admin_id = getUid();
        $data = $_POST['info'];
        $start_time = strtotime($data['start_time']);
        $data['start_time']=$start_time;
        $data['end_time']=strtotime($data['end_time']);
        $data['sell_price'] = trim($data['sell_price']);
        $data['update_time'] = $now_time;
        $data['update_user_id'] = $admin_id;
        $data['status'] = 1;
//        var_dump($data);exit;
        $where['freetour_id']=$data['freetour_id'];
        $where['end_time']=array('gt',$now_time);
        $where['type']=$data['type'];
        if($this->where($where)->find()){
            if(!$this->where($where)->save($data)){
                return array('status' => 0, 'info' => "更新失败，请刷新页面尝试操作");
            }
        }else{
            $data['create_time'] = $now_time;
            $data['create_user_id'] = $admin_id;
//        var_dump($data);exit;
//        $this->create($data);
            if (!$this->add($data)) {
//            echo $this->getLastSql();exit;
                return array('status' => 0, 'info' => "发布失败，请刷新页面尝试操作");
                exit;
            }
        }

        return array('status' => 1, 'info' => "已经发布", 'url' => U('Freetour/index'));
    }



    /*
     * 获取活动信息
     * @author hesheng
     * 2017.12.27
     */
    public function getInfo($freetour_id){
        $where['freetour_id']=$freetour_id;
        $where['end_time']=array('gt',time());
        $where['type']=1;
        $field='id,start_time,end_time,type,sell_price';
        $res = $this->where($where)->field($field)->find();
        if($res){
            $res['start_time']=date('Y-m-d H:i:s',$res['start_time']);
            $res['end_time']=date('Y-m-d H:i:s',$res['end_time']);
        }
        return $res;
    }
}
