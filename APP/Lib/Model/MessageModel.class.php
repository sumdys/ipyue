<?php
class MessageModel extends RelationModel{
    public $data=array();

    protected $_auto = array (
        array('create_time','time',1,'function'),
        array('update_time','time',3,'function'),
    );

    //发送系统消息
    function message_sys_send($from=0,$to,$title='',$contents='',$sys=0){
        if($to){
            $data['title']=$title;
            $data['contents']=$contents;
            $data['create_time']=time();
            $data['to_id']=$to;
            $data['from_id']=$from;
            $data['is_sys']=$sys;
            if(!$this->create($data)){
                $this->error=$this->getError();
                return false;
            }

            $rs=$this->add();
            return $rs;
        }
        return false;
    }

    function tplData($data){
        $MemberDb=D('Member');
        $UserDb=D('User');
        //当前登陆状态 下的
        if(session("user_type")==2 || GROUP_NAME=="Meile_admin"){ //后台用户触发
            getUid() && $data['user']=$UserDb->relation(true)->find(getUid());
            if(isset($data['member_id']) || isset($data['mobile'])){
                $member_id=isset($data['member_id'])?$data['member_id']:$MemberDb->where("mobile='{$data['mobile']}'")->getField('id');
                $member_id && $data['member']=$MemberDb->relation(true)->find($member_id);
            }
        }else{
            getUid() && $data['member']=$MemberDb->relation(true)->find(getUid());
        }

        $data=array_merge($this->data,$data);
        if(!isset($data['to'])){
            if($data['mobile']){
                $data['to']=$MemberDb->where("mobile='{$data['mobile']}'")->getField('id');
            }else{
                $data['to']=getUid();
            }
        }
        $data['date']=date('Y-m-d');
        $data['date_time']=date('Y-m-d H:i:s');
        $data['weburl']=isset($data['weburl'])?$data['weburl']:C("WEBURL");
        $data['tel']=isset($data['tel'])?$data['tel']:C('TEL');
        return $data;
    }

    /*
     * 发送
     */
    function send_str($str,$data=array(),$is_view=0){
        $MemberDb=D('Member');
        $UserDb=D('User');
        $data=$this->tplData($data);

        extract($data, EXTR_OVERWRITE);
        $name=$data['name']?$data['name']:($member['name']?$member['name']:$member['username']);

        //变量转换
        $content=$str;
        $preg="/{\\$C(.*?)}/";
        preg_match_all($preg,$content,$d);
        if(!empty($d)){
            foreach($d[1] as $val){
                C($val) && $C[$val]=C($val);//存在的配置值
            }
        }
        eval("\$content = \"$content\";" );

        if($is_view){
            return $content;
        }
        //发送系统信息
        if( $data['is_sys']){
            $this->message_sys_send('',$data['to'],$data['title'],$content,1);
        }
        //发送短信
        if($data['is_sms']){
            $mobile=$data['mobile']?$data['mobile']:$member['mobile'];
          //  echo "send:".$mobile."|".$content;
               sendMobileSms($mobile,$content);
        }
    }

    //执行信息发送
    function send($act,$data=array()){
        if(!$act) return false;
        $MessageTplDB=D("MessageTpl");
        $where['name']=$act;
        $where['status']=1;
        //获取模板
        $tpl_cid=D("MessageTplGroup")->where($where)->getField('id');
        if(!$tpl_cid)  return false;
        $wheres['cid']=$tpl_cid;
        $wheres['status']=1;
        $rs=$MessageTplDB->where($wheres)->select();
        if(!$rs)  return false;
        $MemberDb=D('Member');
        $UserDb=D('User');
        $data=$this->tplData($data);

        extract($data, EXTR_OVERWRITE);
        $name=$data['name']?$data['name']:($member['name']?$member['name']:$member['username']);

        foreach($rs as $val){
            //模板变量转换
            $content=$val['contents'];
            $preg="/{\\$C(.*?)}/";
            preg_match_all($preg,$content,$d);
            if(!empty($d)){
                foreach($d[1] as $val){
                    C($val) && $C[$val]=C($val);//存在的配置值
                }
            }
            eval("\$content = \"$content\";" );

            //发送系统信息
            if($val['is_sys'] || $data['is_sys']){
                $this->message_sys_send('',$data['to'],$data['title'],$content,1);
            }

            //发送短信
            if($val['is_sms'] || $data['is_sms']){
                $mobile=$data['mobile']?$data['mobile']:$member['mobile'];
               // echo "send:".$mobile."|".$content;
                sendMobileSms($mobile,$content,30);
            }
        }
        return true;
    }

    /*
    *执行信息发送
    *
    */
    function message_action($act,$data=array()){
        return  $this->send($act,$data);
    }

    //发送系统消息
    //@tpl 模板名
    //@data array 模板变量替换
    function message_sys($tpl,$data=array()){
        if(empty($tpl)) return false;

        if(empty($data)){
            $content=$tpl;
        }else if(is_array($data)){
            $this->data=array_merge($this->data,$data);
        }

        if(!$content){
            $this->data['date_time']=date('Y-m-d');
            $content=M('message_sys_tpl')->where("name='$tpl'")->getField('contents');
            foreach($this->data as $k=>$v){
                $content=str_replace('{$'. $k.'}',$v,$content);
            }
        }

        if($this->data['to']){
            $to=$this->data['to'];
        }elseif($this->data['mobile']){
            $to=D('Member')->where("mobile='{$this->data['mobile']}'")->getField('id');
            echo $to;
        }else{
            $to=getUid();
        }

        return $this->message_sys_send('',$to,$this->data['title'],$content,1);
    }

    //发送手机通知
    function message_sms($mobile,$tpl,$data=array()){

        if(!$tpl) return false;
        if(empty($data)){
            $content=$tpl;
        }elseif(is_array($data)){
            $this->data=array_merge($this->data,$data);
        }
        if(!$mobile){
            if($data['mobile']){
                $mobile=$data['mobile'];
            }else{
                $rs=D('Member')->where("id=".getUid())->getField('mobile');
                if($rs){
                    $mobile=$rs;
                }else{
                    $this->u_error='手机号不能为空';
                    return false;
                }
            }
        }
        if(!preg_match('/^([0-9]){11,12}$/i',$mobile)){ //验证手机号码
            return false;
        }

        if(!$content){
            $this->data['date']=date('Y-m-d');
            $this->data['date_time']=date('Y-m-d');
            $this->data['tel']=C('TEL');
            $content=M('message_sms_tpl')->where("name='$tpl'")->getField('contents');
            foreach($this->data as $k=>$v){
                $content=str_replace('{$'. $k.'}',$v,$content);
            }
        }
        return sendMobileSms($mobile,$content);
    }


    function sysMessageList($limit=8){
        $where['is_sys']=1;
        $where['to_id']=getUid();
        $rs=$this->where($where)->order('create_time desc')->limit($limit)->select();
        foreach($rs as $k=>$v){
            $rs[$k]['time']=date('Y-m-d H:i:s',$v['create_time']);
            $rs[$k]['is_new']=($v['create_time']+(3600*24*3))>time();
        }
        return $rs;
    }

    function MessageList($limit=10){
        $where['to_id']=getUid();
        $rs=$this->where($where)->order('create_time desc')->limit($limit)->select();
        foreach($rs as $k=>$v){
            $rs[$k]['time']=date('Y-m-d H:i:s',$v['create_time']);
            $rs[$k]['is_new']=($v['create_time']+(3600*24*3))>time();
        }
        return $rs;
    }
		
}