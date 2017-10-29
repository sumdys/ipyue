<?php
// 评论模块
class CommentAction extends CommonAction {
	function index(){
        if(I('so')){
            $where['name'] = array('like',"%".I('so')."%");
            $where['content']  = array('like',"%".I('so')."%");
            $where['mobile']  = array('like',"%".I('so')."%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        $this->map=$map;
        $this->relation=true;
        parent::index();
        $this->display();

	}
    function _before_read(){
        $this->actionName='Evaluat';
    }
    function _before_edit(){
        $this->actionName='Evaluat';
    }

    function _before_update(){
        $this->actionName='Evaluat';
    }

}
?>