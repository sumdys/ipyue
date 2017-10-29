<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo ($title); ?> - <?php echo C('WEB_NAME');?></title>
    <meta name="keywords" content="<?php echo $keywords ? $keywords : C('WEB_KEYWORD'); ?>" />
    <meta name="description" content="<?php echo $description ? $description : C('WEB_DESCRIPTION'); ?>" />
    <meta name="baidu-site-verification" content="sTECrKJdXz" />
    <link rel="stylesheet" href="__PUBLIC__/css/main.css" type="text/css" />
    <script type="text/javascript" src="__PUBLIC__/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/main.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/layer/layer.min.js"></script>
	    <script>
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "//hm.baidu.com/hm.js?7d002bf201366e2ecc985cc83f8c4998";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
	</script>
</head>
<body>
<script type="text/javascript">
    var BaseInfo = {
        'path':"<?php echo U('/');?>"
    };
</script>
<div id="hd_top">
        <div class="wd960"><script type="text/javascript"  src="<?php echo U('/Public/topMenu');?>"></script></div>
    </div>
        <div id="hd_logo">
        <div class="wd960">
            <a href="<?php echo U('/') ?>" class="left"><img src="__PUBLIC__/images/logo.gif" alt="logo" class="logo" /></a>
            <!-- <div class="right"><a href="<?php echo U('/activity/share');?>" target="_blank"><img src="__PUBLIC__/images/activity/share_home_top.jpg" alt="中国国际航空" /></a></div> -->
        </div>
    </div>
    <div id="hd_nav">
        <div class="wd960">
            <ul class="home_nav<?php if(MODULE_NAME=='Index' && $_REQUEST[_URL_][0]!='Member') echo ''; ?>">
    <li <?php if(MODULE_NAME=='Index' && $_REQUEST[_URL_][0]!='Member') echo "class='active'"; ?> ><a href="<?php echo U('/') ?>">首页</a></li>
	    <li <?php if(MODULE_NAME=='Freetour' && $_REQUEST[_URL_][1]!='diy') echo "class='active'"; ?> ><a href="<?php echo U('/freetour')?>">自由行</a></li>
    <!-- <li <?php if(MODULE_NAME=='Iflight') echo "class='active'"; ?> ><a href="<?php echo U('/iflight')?>">国际机票</a></li> -->
    <li <?php if($_REQUEST[_URL_][1]=='diy') echo "class='active'"; ?> ><a href="<?php echo U('/freetour/diy')?>">私人定制</a></li>
    <li <?php if(MODULE_NAME=='Hotel') echo "class='active'"; ?> ><a href="<?php echo U('/Iflight/orderflight')?>">机票预订</a></li>
    <!-- <li <?php if(MODULE_NAME=='Special') echo "class='active'"; ?> ><a href="<?php echo U('/special')?>">特卖</a></li> -->
    <!-- <li><a <?php if(MODULE_NAME=='Ailehui') echo "class='active'"; ?> href="<?php echo U('/Ailehui')?>" class="new">国际酒店<span><img src="__PUBLIC__/images/top_nav_new.gif" alt="new"/></span></a></li> -->
	<!-- <li><a <?php if(MODULE_NAME=='Adviser') echo "class='active'"; ?> href="<?php if($userinfo): ?>{:U('/Adviser/review');?>_<?php echo ($userinfo["user"]["id"]); else: echo U('/adviser'); endif; ?>">专业顾问</a></li>-->
    <!-- <li><a <?php if(MODULE_NAME=='About') echo "class='active'"; ?> href="<?php echo U('/About');?>">关于我们</a></li> -->
</ul>
        </div>
	</div>
<style type="text/css">
    input{font-size: 12px;}
    td{font-size: 12px;}
    .p2{font-size: 12px;}
    .p6{font-size: 12px; color: #1b6ad8;}
    a{color: #1b6ad8text-decoration: none;}
    a:hover{color: red;}
</style>

<body oncontextmenu="return true" onselectstart="return true">
<p align="center">
</p>
<p align="center">
</p>
<table cellspacing="0" cellpadding="0" width="540" align="center" border="0" style="margin-top: 50px;">
    <tbody>
    <tr>
        <td valign="top" height="270">
            <div align="center">

                <img height="211" src="__PUBLIC__/img/404/error.gif" width="329"><br>
                <br>
                <table cellspacing="0" cellpadding="0" width="80%" border="0">
                    <tbody>
                    <tr>
                        <td>
                            <font class="p2">&nbsp;&nbsp;&nbsp; <font color="#ff0000">
                                <img height="13" src="__PUBLIC__/img/404/error_emessage.gif" width="12">&nbsp;无法访问本页的原因是：</font>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td height="8">
                        </td>
                    <tr>
                        <td>
                            <div style="position: relative; margin-left: 120px">
                                <font color="#000000"><br/><div id="error_pro">您所请求的页面不存在</div></font><br/>
                                <br/>
                                </div>
                            <br />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td height="5">
        </td>
    <tr>
        <td align="middle">
            <center>
                <table cellspacing="0" cellpadding="0" width="480" border="0"  style="margin-bottom: 10px;">
                    <tbody>
                    <tr>
                        <td width="6">
                            <img height="26" src="__PUBLIC__/img/404/error_left.gif" width="7">
                        </td>
                        <td background="__PUBLIC__/img/404/error_bg.gif">
                            <div align="center">
                                <font class="p6"><a href="<?php echo U('/');?>">返回首页</a> | <a href="javascript:history.go(-1)">返回上页</a>
                                </font>
                            </div>
                        </td>
                        <td width="7">
                            <img src="__PUBLIC__/img/404/error_right.gif">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </center>
        </td>
    </tr>
    </tbody>
</table>

﻿ <div id="footer">
    	<div class="wd960">
            <div id="ft_link">
                    <a href="<?php echo U('/about');?>">关于我们</a>
                    <a href="<?php echo U('/about/duty');?>">免责声明</a>
                    <a href="<?php echo U('/about/privacy');?>">隐私保护</a>
                    <a href="<?php echo U('/about/job');?>">诚聘英才</a>
                    <a href="<?php echo U('/about/contact');?>">联系我们</a>
            </div>
            <?php
 if($_SERVER['HTTP_HOST']=='www.aishangfei.cn'){ $bah="粤ICP备11009654号-7"; }elseif($_SERVER['HTTP_HOST']=='www.aishangfei.org'){ $bah="粤ICP备11009654号-9"; }elseif($_SERVER['HTTP_HOST']=='www.aishangfei.com.cn'){ $bah="粤ICP备13026678号-1"; }else{ $bah="粤ICP备15024109号-1"; } ?>
            <div id="ft_copyright">Copyright © 2014-2017 品悦旅行网 All Rights Reserved
                <img src="__PUBLIC__/images/ft_icp.jpg" alt="icp" />
                <a href="http://www.miitbeian.gov.cn/" target="_blank"><?php echo ($bah); ?></a>
            </div>
            <div id="ft_icons">
                <!-- <a class="a0" href="#" target="_blank"></a> -->
                <!-- <a class="a1" href="#" target="_blank"></a> -->
                <a class="a2" href="#" target="_blank"></a>
                <a class="a3" href="http://trust.360.cn/search.php" target="_blank"></a>
                <a class="a4" href="#" target="_blank"></a>
                <a class="a5" href="http://www.gzaic.gov.cn/GZCX/WebUI/credit/qiyeInfo.htm#" target="_blank"></a>
                <a class="a6" href="http://net.china.com.cn/index.htm" target="_blank"></a>
                <a class="a7" href="http://www.cyberpolice.cn/wfjb/" target="_blank"></a>
            </div>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?646713249fd3ceeab66e16bc78c8e814";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

</body>
</html>