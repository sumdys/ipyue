<?php
//  Author: yinpengfei <me@yin.cc>
//ini_set("session.save_handler", "memcache");
//ini_set("session.save_path", "tcp://192.168.4.206:11223");
//定义项目名称和路径
define('APP_NAME', 'APP');
define('APP_PATH', './APP/');

define("WEB_ROOT", dirname(__FILE__) . "/");
define("COOKIE_FILE", WEB_ROOT.APP_NAME."/cookie_#");

// 开启调试模式
if(isset($_GET['dg']) && $_GET['dg'=='c']){
    define('APP_DEBUG',TRUE);
}

// 加载框架入口文件
require( "./ThinkPHP/ThinkPHP.php");