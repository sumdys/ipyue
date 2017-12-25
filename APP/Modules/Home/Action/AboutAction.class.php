<?php
//关于我们
class AboutAction extends IniAction{

    Public function index(){	
		$this->title="关于品悦";
		$this->display();
    }
	
	Public function job(){
        $this->title="人才招聘";
        $this->display();
    }

    Public function duty(){
        $this->title="免责声明";
        $this->display();
    }
	
    function privacy(){
        $this->title="隐私保护 ";
        $this->display();
    }
	
    Public function contact(){
        $this->title="联系我们";
        $this->display();
    }

	

}