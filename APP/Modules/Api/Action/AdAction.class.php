<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 13-11-4
 * Time: 下午5:31
 */

class AdAction extends Action{
    
    function index(){
        
    }
          
     //查询广告模板
     function admodel(){
          
           $id=$_GET["id"];
           $this->info=D('Ad')->where('id='.$id)->find();
           $aid=$this->$info;
           $this->res=D('Adsize')->where('aid='. $aid['info']['aid'])->find();    
           $url=$_SERVER['HTTP_REFERER'];  
           //$url = $aid['info']['link']; 
           $this->assign('url',$url);
           $content = $this->fetch();
           header("Content-type: text/javascript; charset=utf-8");    
           echo htmltojs($content);
           
           
      }
      
      
}

