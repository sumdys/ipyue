<?php
// 首页控制器
class TestAction extends Action {
    function sv(){
        $arr= D("Sj")->select();

       dump(count($arr));
        $UserDb=D("User");
        $MemberDb=D("Member");
        $M=M();
        foreach($arr as $key=>$val){
            $map['name']=$val['name'];
            $map['status']=1;
            $arr[$key]['user']= $UserDb->field('id,username,name')->where($map)->find();

        }

        foreach($arr as $key=>$val){
            $where['mobile']=$val['mobile'];
            $r= $MemberDb->field('id,username,mobile,user_id,name,source,create_time')->where($where)->select();
            $array[$key]=$r;
            foreach($r as $ka=>$va){
                if($va['user_id']==$val['user']['id']){
                    $rs[$key][$ka]=$va;
                }else{
                    $brs[$key][$ka]=$va;
                    $brs[$key][$ka]['u']=$val['user']['id'];
                    $data['user_id']=$val['user']['id'];
                    $data['id']=$va['id'];
                //    $data['mobile']=$val['mobile'];
                    $uid=$val['user']['id'];
                    $mobile=$val['mobile'];
                    $sql="update asf_member set user_id=$uid where  mobile=$mobile and id=$va[id]";
                    echo $sql;
                    $rsd= $M->query($sql);
                  //    $rsd= $MemberDb->save($data);
                    dump($rsd);
                    echo $M->getDbError();
                }
            }
        }

        print_r($brs);
        $this->display('index');
     //   print_r($array);
    }





function pointsAAA(){
     $rs=D('NewTmp')->where('id>10621')->select();
    $Points=D('Points');
    foreach($rs as $val){
        if(!$Points->where("member_id=".$val['id'])->count()){
            echo $val['id']."|";
            $Points->addPoints($val['id'],1000,I('username').'注册 获得1000'.'积分'); //添加注册送积分
            $Points->addPoints($val['id'],50,I('username').'注册 获得50'.'现金券',2); //添加注册送现金
        }
    }
}









    function asmsuser(){  //查询显示升级页面
        $AsmsMember= D("AsmsMember");
        import('ORG.Util.Page');// 导入分页类
        $where['sj']=array("gt",1);
        $count      = $AsmsMember->where($where)->count();
        echo $count."\n";
        $Page       = new Page($count,100);//

        $totalPages   =   ceil($count/100);

        echo $Page->firstRow.','.$Page->listRows."\n\n";
        $p=I("p")?I("p"):0;
        $p++;


        $rrs=D("AsmsMember")->limit($Page->firstRow.','.$Page->listRows)->select();
        $AsmsUser=D('AsmsUser');
        $member=D("Member");
        foreach($rrs as $val){

            $data=array();
            if(empty($val['sj'])){
            //    echo "手机号为空";
                continue;
            }
            $data['username']=$val['sj'];

            $data['name']=$val['name']?$val['name']:$val['hyzcm'];
            $data['email']=$val['email'];
            $data['mobile']=$val['sj'];

            $data['sex']==$val['xb'];
            $data['zip_code']==$val['yzbm'];

            $data['salt']=generateSalt();
            $data['password']=hashPassword($val['mm'],$data['salt']);
        //    echo $val['ywyid']."||".$AsmsUser->asmsUserTo($val['ywyid']);
            $ywyid=$AsmsUser->asmsUserTo($val['ywyid']);
            $data['user_id']=$ywyid?$ywyid:C('ASMS_ACCOUNT');
            $data['source']='Asms';
            $data['status']=0;
            $data['asms_member_id']=$val['hyid'];
            $data['create_time']=strtotime($val['cjrq']);

            if($member->where("mobile='$data[mobile]' or username='$data[mobile]'")->find()){
                print_r($data['mobile']);
            }else{
                $r=$member->add($data);
                if(!$r){
                    echo $member->getDbError();
                }else{
                    echo "add ($r)";
                }
            }
        }
        if($p>$totalPages){
            die("完成");
        }

        $url="?p=$p";
        echo "<script type='text/javascript'>location.href='$url'</script>";

     //   $this->display("index");
    }

    function asmsOrder(){
        $order=D("AsmsOrder");
        $member=D("Member");
        $where['asms_member_id']=array('gt',1);
        $rs=$member->field('id,asms_member_id,user_id')->where($where)->select();

        foreach($rs as $val){
            $data['hyid']=$val['asms_member_id'];
            $data['member_id']=$val['id'];
            if($order->where("hyid=$val[asms_member_id] and (user_id<1 or user_id is null)")){
                $data['user_id']=$val['user_id'];
            }

            print_r($data);
            $rs=$order->where("hyid=$val[asms_member_id]")->save($data);
            if(!$rs){
                echo $order->getDbError();
            }
        }
    }

}