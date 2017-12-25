<?php
// 后台用户模块
class MessageAction extends CommonAction {
    public function index(){
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        if(I('so')){
            if(strstr(I('so'),':')){
                $so=explode(':',I('so'));
                $map[$so[0]]=$so[1];
            }else{
                $where['name'] = array('like',"%".I('so')."%");
                $where['module']  = array('like',"%".I('so')."%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
        }
        $this->map=$map;
        $this->title = '消息模板';
        $this->order="id desc,name";
        parent::index(D('MessageTplGroup'));
        $this->display();
    }

    public function tplList(){
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        if(I('so')){
            if(strstr(I('so'),':')){
                $so=explode(':',I('so'));
                $map[$so[0]]=$so[1];
            }else{
                $where['contents']  = array('like',"%".I('so')."%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
        }
        $map['cid']=I('get.cid');
        $this->groupInfo=D("MessageTplGroup")->find(I('get.cid'));
        $this->map=$map;
        $this->title = '消息模板';
        $this->order="id desc,name";
        parent::index(D('MessageTpl'));
        $this->display();
    }


    /*
      * 禁用 恢复
      */
    function resume(){
        $this->actionName="MessageTplGroup";
        parent::resume();
    }

    /*
   * 禁用 恢复
   */
    function forbid(){
        $this->actionName="MessageTplGroup";
        parent::forbid();
    }

    /*
     * add添加
     */
    function tplGroupAdd(){
        $this->display('tplGroupAdd');
    }

    /*
     * edit编辑
     */
    function tplGroupEdit(){
        $this->actionName="MessageTplGroup";
        parent::edit();
    }

    function insert(){
        $this->actionName="MessageTplGroup";
        parent::insert();
    }

    function update(){
        $this->actionName="MessageTplGroup";
        parent::update();
    }

    function foreverdelete(){
        $this->actionName="MessageTplGroup";
        parent::foreverdelete();
    }

    /*
     * add添加
     */
    function tplAdd(){
        $where['id']=I('get.cid');
        $this->vo=D("MessageTplGroup")->where($where)->find();
        $this->display('tplAdd');
    }

    /*
     * edit编辑
     */
    function tplEdit(){
        $_POST['is_sms']=I('is_sms');
        $_POST['is_sys']=I('is_sys');
        $this->actionName="MessageTpl";
        parent::edit();
    }

    function tplInsert(){
        $this->actionName="MessageTpl";
        parent::insert();
    }

    function tplUpdate(){
        $this->actionName="MessageTpl";
        parent::update();
    }

    function tplForeverdelete(){
        $this->actionName="MessageTpl";
        parent::foreverdelete();
    }

    /*
     *站内信
     */
    function mesList(){
        $this->title = '站内信';
        $this->order="id desc";
        parent::index(D('Message'));
        $this->display();
    }

    /*
     *站内信
     */
    function mesEdit(){
        $this->actionName="Message";
        parent::edit();
    }


    /*
   * 禁用 恢复
   */
    function mesResume(){
        $this->actionName="Message";
        parent::resume();
    }

    /*
   * 禁用 恢复
   */
    function mesForbid(){
        $this->actionName="Message";
        parent::forbid();
    }


  //  function
}
?>