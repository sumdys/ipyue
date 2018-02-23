<?php
/**
 * Created by PhpStorm.
 * User: hesheng
 * Date: 2/18/2018
 * Time: 20:16
 */

class HomeAction extends Action
{
    private $iconCantent = array(
        array('description'=>'自由行','type'=>'0','imageUrl'=>'http://www.ipyue.com/Public/mobile/images/icon/58c8fa4d97524_thumb.png'),
        array('description'=>'别墅客栈','imageUrl'=>'http://www.ipyue.com/Public/mobile/images/icon/58c8fa3a98564_thumb.png','type'=>'4'),
        array('description'=>'邮轮','imageUrl'=>'http://www.ipyue.com/Public/mobile/images/icon/58c8fa5df35a2_thumb.png','type'=>'5'),
        array('description'=>'独家资源','imageUrl'=>'http://www.ipyue.com/Public/mobile/images/icon/58c8fa194dbfd_thumb.png','type'=>'7'),
        array('description'=>'跟团游','imageUrl'=>'http://www.ipyue.com/Public/mobile/images/icon/gentuan.png','type'=>'8'),
        array('description'=>'高端游','imageUrl'=>'http://www.ipyue.com/Public/mobile/images/icon/guanduan.png','type'=>'9'),
        array('description'=>'亲子游学','imageUrl'=>'http://www.ipyue.com/Public/mobile/images/icon/xlyy.png','type'=>'10'),
        array('description'=>'景点门票','imageUrl'=>'http://www.ipyue.com/Public/mobile/images/icon/menpiao.png','type'=>'11'),
    );

    private $topImg = array(
        array('image'=>'http://www.ipyue.com/Public/uploads/ad/591271aa56174.jpg'),
        array('image'=>'http://www.ipyue.com/Public/uploads/ad/590f2f8807486.jpg'),
        array('image'=>'http://www.ipyue.com/Public/uploads/ad/590f2f5350fdf.jpg')
    );
    public function index(){
        $data['topimg']=$this->topImg;
        $data['serverlist']=$this->iconCantent;
        $data['hot']='特价推荐';
        $data['recomlist']=$this->recomlist();
        return returnJson($data);
    }


    private function recomlist($type=0,$pageSize=5){
        //特价线路
        $where =  array(
            'old_price'=>array('gt',0)
        );
        if($type){
            $where['type']=$type;
        }
        $model = D("Freetour");
        $field = 'id,title,price,images';
        $res=$model->lists($where,$field,'',$pageSize);
        return $res;

    }


    public function lists(){
        $type=I('get.type');
        switch ($type){
            case 0:
                $title = '自由行';
                break;
            case 4:
                $title = '别墅客栈';
                break;
            case 5:
                $title = '邮轮';
                break;
            case 7:
                $title = '独家资源';
                break;
            case 8:
                $title = '跟团游';
                break;
            case 9:
                $title = '高端游';
                break;
            case 10:
                $title = '亲子游学';
                break;
            case 11:
                $title = '景点门票';
                break;
        }
        $data['itemList']=$this->recomlist($type,10);
        $data['title']=$title;
        return returnJson($data);
    }





}