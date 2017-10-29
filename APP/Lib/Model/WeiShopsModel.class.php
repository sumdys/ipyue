<?php

class WeiShopsModel extends RelationModel{
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

        }
//          var_dump($list);
        return $list;
    }



}