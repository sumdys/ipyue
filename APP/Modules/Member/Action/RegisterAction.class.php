<?php
// 首页控制器
class RegisterAction extends IniAction {
    //注册
    function index(){
        //提交数据处理
        if($_POST['act']=='register'){
            $rs=D('Member')->register();
            if($rs===true){
                D('Message')->message_action('reg_success');//发送信息
                if(cookie('invite_id')){ //邀请注册送积分
                    $member=D('Member')->field('username,mobile')->find(cookie('invite_id'));
                    if($member){
                        $data['name']=$member['username'];
                        $data['invite_name']=$rs['username'];
                        $data['mobile']=$member['mobile'];
                        D('Message')->send('invite_reg',$data);
                    }
                }
                $this->redirect('/index');
            }else{
                $this->error($rs,U('/member/register'));
            }
        }else{
            $this->title="会员注册";
            $this->display();
        }
    }



	
	
}