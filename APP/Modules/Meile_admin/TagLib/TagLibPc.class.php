<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: TagLibHtml.class.php 2601 2012-01-15 04:59:14Z liu21st $
import('TagLib');
class TagLibPc extends TagLib{
    // 标签定义
    protected $tags   =  array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'auth'=>array('attr'=>'name','close'=>1),
        );
    /**
     +----------------------------------------------------------
     * auth()
     */
    public function _auth($attr,$content){
        $tag        =	$this->parseXmlAttr($attr,'auth');
        $name     =	$tag['name'];

        $action='';
        $module='';
        $group='';

        $name=explode('/',$name);
        if(isset($name[0])){
            $s=!$name[0]?4:3;
            for($i=$s;$i>=0;$i--){
                if(isset($name[$i])){
                    $action =   isset($name[$i])?$name[$i]:'';
                    $module =   isset($name[$i-1])?$name[$i-1]:'';
                    $group  =   isset($name[$i-2])?$name[$i-2]:'';
                    break;
                }
            }
        }
        $parseStr='';
        if(AccessDecision($action,$module,$group)){
            $parseStr=$content;
        }
        return $parseStr;
    }



}
?>