<?php
//
//短信管理控制器

class MobilesmsAction extends CommonAction{
	function _before_index(){//首页	
        if(I('so')){
            $where['mobile'] = array('like',"%".I('so')."%");
            $where['ip']  = array('like',"%".I('so')."%");
            $where['source']  = array('like',"%".I('so')."%");
			$where['return_var']  = array('like',"%".I('so')."%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
		if($_POST['so_date1'] && $_POST['so_date2']){ //日期搜索
            $map['sent_time']=array(array('egt',strtotime(I('so_date1'))),array('elt',strtotime(I('so_date2'))));
        }
		if(!empty($map))
			$this->map = $map;		  
        $this->order='id desc';//默认倒序
	    $this->index(D('Mobilesms'));
	}

	function check(){//查看页
		$sms=D('Mobilesms');		
		$where['id']=I('id');
		$this->vo=$sms->where($where)->find();	
		$this->display();
	}

    /*
     * 查看单个手机记录
     */
    function send_log(){
        if(I('so') || I('mobile')){
            $mobile=I('so')?I('so'):I('mobile');
            $where['mobile'] = array('eq',$mobile);
        }else{
            $where['mobile'] =0;
        }
        if($_POST['so_date1'] && $_POST['so_date2']){ //日期搜索
            $where['sent_time']=array(array('egt',strtotime(I('so_date1'))),array('elt',strtotime(I('so_date2'))));
        }
        $this->map = $where;
        $this->order='id desc';//默认倒序
        $this->index(D('Mobilesms'));
        $this->display("index");
    }

    //发送页
	function send(){
        $TM=D("messageTpl");
        $MTG=D("MessageTplGroup");
        if(IS_AJAX ){
            if(I('view') && I("content")){
                $data['mobile']=I('mobile');
                if($list=D('Message')->send_str(I("content"),$data,1)){
                    $data['list']=$list;
                    $data['status']=1;
                    $this->AjaxReturn($data);
                }
                $this->error('执行错误');
            }elseif(I("mod")){
                $where['module']=I("mod");
                $cidArr=$MTG->field('id')->where($where)->select();
                foreach($cidArr as $val){
                    $cid[]=$val['id'];
                }
                $wheres['cid']=array('in',$cid);
                $wheres['status']=1;
                $list = $TM->field("id,contents,remark as name")->where($wheres)->select();
                if($list){
                    $data['list']=$list;
                    $data['status']=1;
                    $this->AjaxReturn($data);
                }else{
                    $this->error();
                }

            }

        }
        $map['status']=1;
        $map['is_user']=1;
        $rs= $MTG->field("id,name,module")->where($map)->group("module")->select();
        $list['mod']=$rs;
        $mod= I("mod")?I("mod"):(isset($rs[0]['module'])?$rs[0]['module']:"");
        $where['module']=$mod;
        $list['name']= $TM->field("id,name,module,contents")->where($where)->select();
        $this->tpl_list=$list;

        if(I('id')){
            $mid=I('id');
            $memberDb=D('Member');
            $this->mInfo=$memberDb->find($mid);
        }

		$this->display();		 
	}
    
    /*
     * 重新发送
     */
	function resend(){
        if(!I('ids')) $this->error("请选择");
        $where['id']=array('in', I('ids'));
        $sms=D('Mobilesms');
        $rs=$sms->field('mobile,content')->where($where)->select();
        foreach($rs as $val){
            sendMobileSms($val['mobile'],$val['content']);
        }
        $this->success("执行成功");
    }

	function confirmsend(){//短信发送提交处理
		$contents=I('content');
        if(strlen($contents)>10){
            $data['mobile']=I('mobile');
            $data['is_sms']=I('is_sms');
            $data['is_sys']=I('is_sys');
            D('Message')->send_str($contents,$data);
            $this->success("执行成功");
        }else{
                $this->error("发送失败");
        }
	}

}

