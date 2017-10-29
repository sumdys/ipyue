<?php
/**
 * 
 * @author Administrator
 *
 */
class TelnameModel extends RelationModel{
	private $userid;
	private $username;
	
    protected $_link=array(
			'User'=> array(  
     			'mapping_type'=>BELONGS_TO,
          		'class_name'=>'User',
          		'foreign_key'=>'user_id',
				'mapping_name'=>'user',
				'mapping_fields'=>'username',
				'as_fields'=>'username',
			),
		);

    protected $_auto = array (
        array('user_id','getUid',1,'function'),
       
    );

    //会员详细信息
	function userInfo($userid){
		return $this->relation(true)->find($userid);
	}

    //获得用户id  支持模糊查找 返回多个id  1，2，3
    function getUserId($name){
        if(!is_array($name))
            $where['name']=array('like',"%$name%");
        $rs=$this->field('id')->where($where)->select();
        if($rs){
            $arr=array();
            foreach($rs as $v){
                $arr[]=$v['id'];
            }
            return implode(',',$arr);
        }
        return false;


    }

    //获得某个用户salt
	function getSalt($username){
        return $this->where("id='$username' or username='$username'")->getField('salt');
    }
	
}

?>