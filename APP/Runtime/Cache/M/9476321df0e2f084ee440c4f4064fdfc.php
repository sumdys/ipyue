<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>会员登录-品悦手机网</title>
<link href="__PUBLIC__/mobile/css/global.css" rel="stylesheet" type="text/css">
<style type="text/css">
#topnav{padding-right:42px;}
#import{padding:10px 10px 0 10px;margin-bottom:10px;height:80px;}
#import span{margin-bottom:10px;display:block;}
.box p{padding:10px;clear: both;}
.box p span a{color:#3598db;font-weight:bold;}
</style>
</head>

<body>
<div id="page">
  <div id="content">
    <h1 id="topnav"><span class="left icon"><a href="<?php echo U('/');?>" class="icon-home">首页</a></span>我的帐号</h1>
    <div class="box">
      <form action="" method="post">
        <h2>会员登录</h2>
        <div id="import">
          <span><input name="name" type="text" id="name" class="txt" maxlength="20" required value placeholder="用户名"></span>
          <span><input name="password" type="password" id="password" class="txt" maxlength="18" required placeholder="密码(6-18位字符)"></span>
        </div>
          <input type="hidden" name="act" value="login">
        <input type="submit" value="立即登录" class="sub">
      </form>
      <p><span class="left"><a href="<?php echo U('/Member/register');?>">免费注册</a></span><span class="right"><a href="<?php echo U('/Member/getPassword');?>">忘记密码</a></span></p>
      <!--<p>注册即送50元现金券(预订机票时使用)及1000积fun</p>-->
    </div>
  </div>
</div>
<script type="text/javascript" src="__PUBLIC__/mobile/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/mobile/js/msg.js"></script>
<script type="text/javascript">
    $('#exit').click(function(){
       msg('您将退出本次登录，是否确定？',1,'query',"out()");
    });
    function out(){
        location.href="<?php echo U('/member/out');?>";
    }
</script>
<script type="text/javascript">
$(function(){
	//提交表单
	$('form').submit(function(){
		var url="<?php echo U('/Member/login');?>";
		if(!(verify('#name','用户名',20))){
			return false;
			}else if(!(verify('#password','密码',18))){
				return false;
				}else{
						$.post(url,$(this).serialize(),function(data){
                            if(data.status==1){
                                location.href=data.url;
                            }else{
								msg(data.info);
                            }
							},'json');
					}
                     return false;
		});
});
//表单验证
function verify(id,name,maxlength){
	var value=$(id).val(),content="";
    content=value==''?name+"不能为空！":value.length>maxlength?name+"内容超出限制！":"";
	if(content!=""){return msg(content);}else{return true;}
	}
</script>
</body>
</html>