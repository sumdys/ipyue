<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-7-25
 * Time: 上午9:44
 * To change this template use File | Settings | File Templates.
 */
class AdModel extends RelationModel{
    protected $_link = array(
        'user'=> array( //
            'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'user_id'=>'id',
            'mapping_fields'=>'id,username,name',
        ),
        'Category'=> array(
            'mapping_type'=>BELONGS_TO,
            'class_name'=>'AdCategory',
            'foreign_key'=>'cid',
        //    'parent_key'=>'cid',
            'mapping_fields'=>'cid,name',
            // 定义更多的关联属性 relation(true)
        ),
    );
   
    public function listNews($firstRow = 0, $listRows = 20,$where="",$order='desc'){
        $M = D("ad");
        $list = $M->where($where)->relation(true)->limit("$firstRow , $listRows")->order('id '.$order)->select();

        $statusArr = array("审核状态", "已发布状态");
    //    $aidArr = M("Admin")->field("`aid`,`email`,`nickname`")->select();
    //    foreach ($aidArr as $k => $v) {
     //       $aids[$v['aid']] = $v;
     //   }
        unset($aidArr);
        $cidArr = M("ad_category")->field("`cid`,`name`")->select();
        foreach ($cidArr as $k => $v) {
            $cids[$v['cid']] = $v;
        }
        unset($cidArr);

        foreach ($list as $k => $v) {
         //   $list[$k]['aidName'] =$aids[$v['aid']]['nickname'] == '' ? $aids[$v['aid']]['email'] : $aids[$v['aid']]['nickname'];
            $list[$k]['status'] = $statusArr[$v['status']];
            $list[$k]['cidName'] = $cids[$v['cid']]['name'];
            //$list[$k]['type_name'] = $v['type']==0?'积分':'爱钻';
            $list[$k]['type_name'] = $cids[$v['type']]['name'];
        }
        return $list;
    }
    
    public function category() {
        if (isset($_POST['act'])) {
            $act = $_POST['act'];
            $data = $_POST['data'];
            $data['name'] = addslashes($data['name']);
            $M = M("ad_category");
            if ($act == "add") { //添加分类
                unset($data[cid]);
                if ($M->where($data)->count() == 0){
                    return ($M->add($data)) ? array( 'info' => '分类 ' . $data['name'] . ' 已经成功添加到系统中','status' => 1, 'url' => U('Jifen/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 添加失败');
                } else {
                    return array( 'info' => '系统中已经存在分类' . $data['name'],'status' => 0);
                }
            } else if ($act == "edit") { //修改分类
                if (empty($data['name'])) {
                    unset($data['name']);
                }
                if ($data['pid'] == $data['cid']) {
                    unset($data['pid']);
                }
                return ($M->save($data)) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功更新', 'url' => U('Ad/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 更新失败');
            } else if ($act == "del") { //删除分类
                unset($data['pid'], $data['name']);
                return ($M->where($data)->delete()) ? array('status' => 1, 'info' => '分类 ' . $data['name'] . ' 已经成功删除', 'url' => U('Ad/category', array('time' => time()))) : array('status' => 0, 'info' => '分类 ' . $data['name'] . ' 删除失败');
            }
        } else {
        import('@.ORG.Category');
        $cat = new Category('ad_category', array('cid','pid','name'));
        return $cat->getList('status=1',0,'sort desc');
    }
    }

    function edittype(){
        
        
    }
        
    //分类信息
    function categoryInfo($cid){
        $cid=$cid?$cid:0;
        import('@.ORG.Category');
        $cat = new Category('ad_category', array('cid','pid','name'));
        return $cat->getPath($cid);
    }
}