<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/Css/base.css"/>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/Js/asyncbox/skins/zcms.css"/>
<script type="text/javascript" src="__PUBLIC__/admin/Js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/Js/functions.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/Js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/Js/asyncbox/asyncbox.js"></script>
</head>

<body class="loginWeb">
<div class="loginBox">
  <div class="innerBox">
    <div class="logo"><span style="font-size:32px;color:#666;font-weight: bold;font-family: 微软雅黑;">网站管理系统
      </span>
    </div>
    <form id="form1" action="__URL__/checkLogin/" method="post">
      <div class="loginInfo">
      <ul>
        <li class="row1">登录账号：</li>
        <li class="row2 inp_txt" >
          <label>
            <input class="input" name="username" id="name" size="30" type="text" />
          </label>
        </li>
      </ul>
      <ul>
        <li class="row1">登录密码：</li>
        <li class="row2 inp_txt">
          <label>
            <input class="input" name="password" id="pwd" size="30" type="password" />
          </label>
        </li>
      </ul>
      <ul>
      <li class="row1">验证码：</li>
      <li class="row2 inp_code">
      <label>
      <input class="input" id="verify_code" name="verify" type="text" style="width:100px;" />
      <label>
      <img src="<?php echo U('Public/verify/');?>"  title="看不清？单击此处刷新" onclick="this.src+='?rand='+Math.random();"  style="cursor: pointer; vertical-align: middle;"/>
      </li>
      </ul>
      </div>
      <input type="hidden" name="op_type" id="op_type" value="1"/>
    </form>
    <div class="clear"></div>
    <div class="operation">
      <button class="btn submit">登录</button>
      <button class="btn findPwd">忘记密码？</button>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(function(){
        $(".submit").click(function(){
            $("#op_type").val("1");
            if($("#name").val()==''||$("#pwd").val()==''||$("#verify_code").val()==''){
                popup.alert("填写完整方可登陆");
                return false;
            }
            $('#form1').submit();
        });
    });
</script>
</body>
</html>