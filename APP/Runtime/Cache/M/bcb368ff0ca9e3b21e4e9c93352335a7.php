<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo ($title); ?> - <?php echo C('WEB_NAME');?></title>
<link href="__PUBLIC__/mobile/css/global.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/mobile/css/flight.css" rel="stylesheet" type="text/css">
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
  <div id="header"><a href="<?php echo U('/');?>">
    <img src="__PUBLIC__/mobile/images/logo.png" alt="品悦手机网">
</a></div>
  <div id="content">
    <h1 id="topnav"><span class="left icon"><a href="<?php echo U('/');?>" class="icon-home">首页</a></span>国际机票预订<span class="right icon"><a href="tel:18824906653" class="icon-tel">电话</a></span></h1>
    <div id="flight_query">
    <dl id="flight_tab">
    <dt class="tab_on">往返</dt>
    <dd>单程</dd>
    </dl>
    <div class="cont_box white_bg">
    <ul id="flight_cont">
    <li>
    <dl class="left">
    <dt>出发城市</dt>
    <dd id="js_departcity">北京</dd>
    </dl>
    <dl class="right">
    <dt>到达城市</dt>
    <dd id="js_backcity">洛杉矶</dd>
    </dl>
    </li>
    <li id="set_date" type="two">
    <div class="left wid49">
    <h3>出发日期</h3>
    <span id="js_departDate">
    <em date="yyyy-mm-ss">--月--号</em>
    <strong>--</strong>
    </span>
    </div>
    <div class="right wid49">
    <h3>返程日期</h3>
    <span id="js_backDate">
    <em date="yyyy-mm-ss">--月--号</em>
    <strong>--</strong>
    </span>
    </div>
    </li>
    </ul>
    </div>
    <div class="cont_box">
    <span class="sub" id="sub">搜索</span>
    </div>
    </div>
  </div>  
  <ul id="footer_list">
  <li><a href="<?php echo U('/Flight');?>" class="fl_on">国际机票预订</a></li>
  <li><a href="<?php echo U('/Specialticket');?>">特价国际机票</a></li>
  </ul>
</div>

<div id="date_list_box">
<div class="header">
<div class="lgog"><a href="<?php echo U('/');?>"><img src="__PUBLIC__/mobile/images/logo.png?v=2" alt="品悦手机网"></a></div>
<h1 class="topnav"><span class="left icon"><a id="close_date_list" class="icon-returnclose">返回</a></span>出发日期选择<span class="right icon"><a href="tel:18824906653" class="icon-tel">电话</a></span></h1>
<ul id="date_week"><li>日</li><li>一</li><li>二</li><li>三</li><li>四</li><li>五</li><li>六</li></ul>
</div>
<div id="date_list"></div>
</div>

<!--国际机票查询弹窗-->
<div id="flight_query_box">
<div class="header">
<div class="lgog"><a href="<?php echo U('/');?>"><img src="__PUBLIC__/mobile/images/logo.png" alt="品悦手机网"></a></div>
<h1 class="topnav"><span class="left icon"><a id="close_flight_query" class="icon-returnclose">返回</a></span>国际机票预订<span class="right icon"><a href="tel:18824906653" class="icon-tel">电话</a></span></h1>
</div>
<div class="cont_box">
	<div class="white_bg">
    	<div id="load">
			<h3>正在为您实时搜索：</h3>
			<p id="query_city">从<strong>出发城市</strong>到<strong>到达城市</strong>的<strong>单程或往返</strong>航班</p>
			<p>出发时间：<em>yyyy-mm-ss</em></p>
			<p style="display:none;">返回时间：<em>yyyy-mm-ss</em></p>
			<div><img src="__PUBLIC__/mobile/images/f_loading.gif" width="180" height="8" /><span></span></div>
		</div>
        
        <div id="consultancy">
        	<div class="warn"><strong>温馨提示：</strong>您的查询请求已被接收，我们正在为您转接专业旅行顾问，为您提供一对一专业国际机票预订服务！</div>
            <div id="cons_select">
				<label><input type="radio" name="cons" value="1" checked="checked"/>咨询专业旅行顾问<span><b>7</b>秒</span></label>
				<label><input type="radio" name="cons" value="2" />快速下单<em>&nbsp;&nbsp;&nbsp;&nbsp;（1分钟轻松完成低价预约）</em></label>
			</div>

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
            <div id="counseling">您也可以点击<a href="tel:18824906653">&nbsp;</a>进行电话咨询</div>
            <div id="commitment">
            	<h2>品悦郑重承诺</h2>
                <ul>
                	<li class="c0"><div><span>100%国际航协资质，订票无忧</span></div></li>
                    <li class="c1"><div><span>百家航空公司联盟，第一手低价</span></div></li>
                    <li class="c2"><div><span>100%真实出票，可先验票，再付款</span></div></li>
                    <li class="c3"><div><span>优越售后服务，退改签毫无障碍</span></div></li>
                </ul>
            </div>
            
      </div>
        
    </div>
</div>
</div>

<script type="text/javascript" src="__PUBLIC__/mobile/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/mobile/js/citylist/citylist.js"></script>
<script type="text/javascript" src="__PUBLIC__/mobile/js/datelist/datelist.js"></script>
<script type="text/javascript" src="__PUBLIC__/mobile/js/flightquery.js"></script><!--国际机票查询弹窗-->
<script type="text/javascript">
//传值到citylist.js
//把cityListUrl传至函数setCityList()
var cityListUrl="<?php echo U('/Flight/citylist.html');?>";
//把citySearchUrl传至“搜索相关结果”的AJAX搜索城市函数
var citySearchUrl="__PUBLIC__/mobile/js/citylist/getCityList.php";

$(function(){
	//单程&往返
	$('#flight_tab dd').click(function(){
		$('#set_date div').removeClass('wid49');
		$(this).addClass('tab_on').siblings().removeClass('tab_on');
		$('#set_date').attr('type','one');
		});
	$('#flight_tab dt').click(function(){
		$('#set_date div').addClass('wid49');
		$(this).addClass('tab_on').siblings().removeClass('tab_on');
		$('#set_date').attr('type','two');
		});
		
});

//调用updateDepartBack()更新出发日期和返程日期,新的出发日期是今天的5天后，新的返程日期是今天的10天后
updateDepartBack(5,10);
//创建新的出发日期和返程日期并更新
function updateDepartBack(addDepartDay,addBackDay){
	var toDay = new Date(),toDayTime = toDay.getTime();
	//调用formattingDate()把毫秒数格式为yyyy-mm-ss
	var departDate = formattingDate(toDayTime,addDepartDay,2),backDate = formattingDate(toDayTime,addBackDay,2);
	//调用upDate()更新日期
	upDate(departDate,'#js_departDate');upDate(backDate,'#js_backDate');
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