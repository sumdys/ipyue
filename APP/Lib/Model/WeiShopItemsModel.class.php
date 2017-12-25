<?php

class WeiShopItemsModel extends RelationModel{


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