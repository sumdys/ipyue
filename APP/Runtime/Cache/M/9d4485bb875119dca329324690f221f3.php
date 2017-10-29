<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>我的定制需求-品悦旅行网</title>
<link href="__PUBLIC__/mobile/css/global.css" rel="stylesheet" type="text/css">
<style type="text/css">
#topnav{padding-right:42px;}
#import{padding:10px 10px 0 10px;margin-bottom:10px;height:248px;}
#import span{margin-bottom:10px;display:block;}
#import textarea{height:120px;}
.box p{padding:10px;}
</style>
</head>

<body>
<div id="page">
  <div id="content">
    <h1 id="topnav"><span class="left icon"><a href="<?php echo U('/');?>" class="icon-home">首页</a></span>独家定制旅行</h1>
    <div class="box">
      <form action="" method="post">
        <div id="import">
          <span><input name="name" type="text" id="name" maxlength="20" required placeholder="您的姓名" class="txt" value="<?php echo ($userinfo["name"]); ?>"></span>
          <span><input name="phone" type="text" id="phone" maxlength="11" required placeholder="您的手机号" class="txt" value="<?php echo ($userinfo["mobile"]); ?>"></span>
          <span><input name="qq" type="text" id="qq" required placeholder="您的QQ号" class="txt" value=""></span>
          <span><textarea name="content" id="demand" maxlength="60" required placeholder="您的定制需求,例如：目的地，主题景点，旅游时间，人数,往返时间限30个字符以内"></textarea></span>
        </div>
        <input type="submit" value="完成" class="sub" id="sub">
      </form>
      <p>我们将尽快与您联系，敬请留意。（为避免影响您的休息，我们会在9:00-21:00联系您）</p>
    </div>
  </div>
</div>
<script type="text/javascript" src="__PUBLIC__/mobile/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
$(function(){
	//提交表单
	$('form').submit(function(){
		var url="<?php echo U('/Mydemand/index');?>";
		if(!(verify('#name','姓名',20,''))){
			return false;
			}else if(!(verify('#phone','手机',11,/^(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/))){
				return false;
				}else if(!(verify('#qq','QQ号码',50,''))){
					return false;
					}else if(!(verify('#demand','需求',60,''))){
						return false;
						}else{
							$.post(url,$(this).serialize(),function(data){
                            	if(data.status==1){
									alert(data.info);
									location.href=data.url;
                            	}else{
                               	 	alert(data.info);
                            		}
							},'json');
					}
                     return false;
		});
});

function verify(id,name,maxlength,expression){
	var value=$(id).val(),content="";
	/*alert(expr);*/
	if(expression!=""){
		content=value==""?name+"不能为空！":value.length>maxlength?name+"内容超出限制！":(!expression.test(value))?name+"输入不正确！":"";
		}else{
			content=value==""?name+"不能为空！":value.length>maxlength?name+"内容超出限制！":"";
			}
	if(content!=""){return alert(content);}else{return true;}
	}
</script>
<style type="text/css">
    #mobile_icon_div{display:none!important;}
</style>
<script type="text/javascript">
    function open_kf(){
    <?php if($userinfo): ?>
        //   window.open('tel:<?php echo ($userinfo["user"]["public_mobile"]); ?>');
        location.href='tel:<?php echo ($userinfo["user"]["public_mobile"]); ?>';
    <?php else: ?>
      //  location.href='tel:400-608-5188';
     //   window.open('http://m.53kf.com/70697090/54/1');
        var kf_url=$("#mobile_icon_div").attr("href")?$("#mobile_icon_div").attr("href"):"http://m.53kf.com/70697090/54/1";
        location.href=kf_url;
    <?php endif;?>
    }
</script>
<div style="display: none">
    <script type='text/javascript' src='http://tb.53kf.com/kf.php?arg=aaa&style=4'></script>
</div>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?6af3a968236e1a82336482ca0d41a71f";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

</body>
</html>