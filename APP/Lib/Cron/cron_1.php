<?php
/**
 * 定时任务1
 */

/**
 * 生日大礼包
 */

//$where['birthday']=array(array("gt",1),array('neq','1900-1-1'));
$date=date("md",time());
//echo date_format('2013','%m%d')."|".$date;
//echo 1;
//echo date("Y-m-d",strtotime("-6 month"));exit;
$where['_string'] ="date_format(birthday,'%m%d')=$date";
//echo date_format("1988-03-18",'%m%d');

$where['mobile']=array("gt",1);
//print_r($where);
$memberDb=D("Member");
$pointsDb=D("Points");
$data=$memberDb->field("id,username,name,birthday,mobile,email,rank_id")->where($where)->select();
//print_r($data);
$map['_string'] ="from_unixtime(create_time,'%m%d')=$date";
$map['tags']="birthday";
foreach($data as $val){
    $map['member_id']=$val['id'];
    if(!$pointsDb->where($map)->count()){
        if($val['rank_id']==1){
            addPoints($val['id'],100);
        }elseif($val['rank_id']==2){
            addPoints($val['id'],100);addPoints($val['id'],150);
        }elseif($val['rank_id']==3){
            addPoints($val['id'],100);addPoints($val['id'],150);addPoints($val['id'],200);
        }elseif($val['rank_id']==4){
            addPoints($val['id'],100);addPoints($val['id'],150);addPoints($val['id'],200);addPoints($val['id'],250);
        }
        D("Message")->send("birthday",$val);
    }
}

//增加
function addPoints($uid,$points){
    $pointsDb=D("Points");
    $mpoints=$pointsDb->where("member_id=$uid and type2=2" )->order('id DESC')->getField('last_points');
    $points= (int)$points;
    $arr['member_id']=$uid;
    $arr['type2']=2;
    $arr['points']="+".$points;
    $arr['type']=0;
    $arr['description']="生日大礼包 $points";
    $arr['last_points']=$mpoints+$points;
    $arr['create_time']=time();
    $arr['tags']="birthday";
    $arr['valid_time']=strtotime("-6 month");
    if($pointsDb->create($arr)){
        $rs=$pointsDb->add();
        if($rs){
            return true;
        }else{
            return false;
        }
    }
    return  false;
}

?>