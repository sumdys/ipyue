<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>品悦旅行</title>
<link href="__PUBLIC__/mobile/css/global.css" rel="stylesheet" type="text/css">
<style type="text/css">
#ad{margin-bottom:10px;position:relative;}
#ad img{width:100%;border:0;}
#ad #adlist li{display:none;}
#ad #adlist .on{display:block;}
#ad #slidebar{position:absolute;left:48%;bottom:8px;}
#ad #slidebar li{font-size:10px;display:inline-block;color:#5d5d5d;cursor:pointer;padding:0 2px;}
#ad #slidebar li.select{color:#e0e0e0;}

#entrance{padding-left:10px;height:320px;background-color:#fff;}
#entrance dt,#entrance dd{float:left;width:50%;height:160px;}
#entrance a{display:block;margin:0 10px 10px 0;position:relative;}
#entrance a.c01{background:#e43636 url(__PUBLIC__/mobile/images/icon01.png) no-repeat right bottom;}
#entrance a.c02{background:#d3c41e url(__PUBLIC__/mobile/images/icon07.png) no-repeat right bottom}
#entrance a.c03{background:#348bd3 url(__PUBLIC__/mobile/images/icon03.png) no-repeat right bottom}
#entrance a.c04{background:#2ba5c9 url(__PUBLIC__/mobile/images/icon04.png) no-repeat right bottom}
#entrance a.c05{background:#795e6d url(__PUBLIC__/mobile/images/icon05.png) no-repeat right bottom}
#entrance a.c06{background:#afafac url(__PUBLIC__/mobile/images/icon06.png) no-repeat right bottom}
#entrance dt a{height:150px;}
#entrance dd a{height:70px;}
#entrance h3{color:#fff;font-size:16px;z-index:100;line-height:24px;position:absolute;left:15px;top:15px;}
#entrance span{position:absolute;right:15px;bottom:10px;}

</style>
</head>

<body>
<div id="page">
  <div id="content">
    <div id="ad">
    <ul id="adlist">
    <li class="on"><a href="http://www.ipyue.com/freetour/detail/id/78"><img src="__PUBLIC__/images/bgy001.jpg" width="100%"></a></li>
    <li><a href="http://www.ipyue.com/freetour/kezhan.html"><img src="__PUBLIC__/images/bg0002.jpg" width="100%"></a></li>
	<li><a href="http://www.ipyue.com/freetour"><img src="__PUBLIC__/images/m-index-1.jpg" width="100%"></a></li>
	<li><a href="#"><img src="__PUBLIC__/images/m-index-2.jpg" width="100%"></a></li>
    <!-- <li><a href="<?php echo U('/specialoffer/hnair_sales');?>"><img src="__PUBLIC__/mobile/images/iad11.jpg" width="100%"></a></li> -->
    
    </ul>
    <ul id="slidebar">
    <li class="select">●</li>
    <li>●</li>
    </ul>
    </div>
	  <!--<dl id="nav">-->
		  <!--<dt><a href="<?php echo U('/Freetour');?>">-->
			  <!--<img src="__PUBLIC__/member/images/3headimg_60.jpg" height="70%">-->
		  <!--<p>自由行</p>-->
		  <!--</a>-->
		  <!--</dt>-->

		  <!--<dt><a href="<?php echo U('/Freetour');?>"><img src="__PUBLIC__/member/images/2headimg_60.jpg" height="70%"><p>别墅客栈</p></a></dt>-->
		  <!--<dt><a href="<?php echo U('/Freetour');?>"><img src="__PUBLIC__/member/images/5headimg_60.jpg" height="70%"><p>邮轮</p></a></dt>-->
		  <!--<dt><a href="<?php echo U('/Freetour');?>"><img src="__PUBLIC__/member/images/1507headimg_60.jpg" height="70%"><p>独家资源</p></a></dt>-->
	  <!--</dl>-->
    <dl id="entrance">
    <dt><a href="<?php echo U('/Freetour');?>" class="c01"><h3>旅游路线<br/>预订</h3></a></dt>
    <dd><a href="<?php echo U('/Flight');?>" class="c02"><h3>机票预订</h3></a><a href="<?php echo U('/mall');?>" class="c05"><h3>积fun商城</h3></a></dd>
    <dd><a href="<?php echo U('/Mydemand');?>" class="c04"><h3>定制需求</h3></a><a href="<?php echo U('/adviser');?>" class="c03"><h3>旅行顾问</h3></a></dd>
    <dt><a href="<?php echo U('/Member');?>" class="c06"><h3>我的<br/>品悦</h3></a></dt>
    </dl>
  </div>
    <div id="footer">
    <!-- <div id="telservice">
    <a href="javascript:open_kf()" class="service">在线咨询</a>
    <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=test" target="_blank" class="qq">QQ咨询</a>
    <a href="tel:4006085188" class="tel"></a>
    </div> -->
    <div class="nav"><a href="<?php echo U('/About/faq');?>">常见问题</a><!--<a href="<?php echo U('/Verify');?>" class="bor">机票验真</a>--><a href="<?php echo U('/Complaint');?>" class="bor">意见反馈</a><a href="http://www.ipyue.com/" target="_blank">电脑版</a></div>
    <div id="copyright">@ 2016 品悦网</div>
  </div>


</div>
<script type="text/javascript" src="__PUBLIC__/mobile/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
$(function(){
	
	//初始化变量index为0，用于保存索引
	var index = 0;
	//初始化变量len为为图片列表的数量
	var len = $('#adlist li').size();
	
	//setInterval()方法
	setInterval(function(){
		showPics(index);                   
		index++;
		if(index == len){index = 0;};
		},4000);
		
	//showPics()设置图片和滑块的显示和隐藏
	function showPics(index){
		$('#adlist li').eq(index).addClass('on').siblings('li').removeClass('on');
		$('#slidebar li').eq(index).addClass('select').siblings('li').removeClass('select');
		};
	
	//当鼠标进入滑块,调用showPics()函数
	$('#slidebar li').mouseenter(function(){
		index = $(this).index();
		showPics(index);
		});
		
	});
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

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Hello MUI</title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">


	<style>
		html,
		body {
			background-color: #efeff4;
		}
	</style>
</head>

<body>

<nav class="mui-bar mui-bar-tab">
	<a id="defaultTab" class="mui-tab-item mui-active" href="<?php echo U('/index');?>">
		<span class="mui-icon mui-icon-home"></span>
		<span class="mui-tab-label">首页</span>
	</a>
	<a class="mui-tab-item" href="<?php echo U('/freetour');?>">
		<span class="mui-icon mui-icon-email"></span>
		<span class="mui-tab-label">发现</span>
	</a>
	<a class="mui-tab-item" href="<?php echo U('/Mydemand');?>">
		<span class="mui-icon mui-icon-contact"></span>
		<span class="mui-tab-label">定制</span>
	</a>
	<a class="mui-tab-item" href="<?php echo U('/member');?>">
		<span class="mui-icon mui-icon-gear"></span>
		<span class="mui-tab-label">我的</span>
	</a>
</nav>
<script src="__PUBLIC__/js/mui.min.js"></script>
<script type="text/javascript" charset="utf-8">
	//mui初始化

	var init=function(){
		var subpages = ['<?php echo U("/index");?>', '<?php echo U("/freetour");?>', '<?php echo U("/Mydemand");?>', '<?php echo U("/member");?>'];
		var subpage_style = {
			top: '0px',
			bottom: '50px'
		};
		var aniShow = {};

		//创建子页面，首个选项卡页面显示，其它均隐藏；
		var self = plus.webview.currentWebview();
		for (var i = 0; i < 4; i++) {
			var temp = {};
			var sub = plus.webview.create(subpages[i], subpages[i], subpage_style);
			if (i > 0) {
				sub.hide();
			}else{
				temp[subpages[i]] = "true";
				mui.extend(aniShow,temp);
			}
			self.append(sub);
		}

		//当前激活选项
		var activeTab = subpages[0];
//		var title = document.getElementById("title");
		//选项卡点击事件
		mui('.mui-bar-tab').on('tap', 'a', function(e) {
			var targetTab = this.getAttribute('href');
			if (targetTab == activeTab) {
				return;
			}
			//更换标题
//			title.innerHTML = this.querySelector('.mui-tab-label').innerHTML;
			//显示目标选项卡
			//若为iOS平台或非首次显示，则直接显示
			if(mui.os.ios||aniShow[targetTab]){
				plus.webview.show(targetTab);
			}else{
				//否则，使用fade-in动画，且保存变量
				var temp = {};
				temp[targetTab] = "true";
				mui.extend(aniShow,temp);
				plus.webview.show(targetTab,"fade-in",300);
			}
			//隐藏当前;
			plus.webview.hide(activeTab);
			//更改当前活跃的选项卡
			activeTab = targetTab;
		});
	}




	mui.init();
	mui.plusReady(function() {
		 init();
	});


	//自定义事件，模拟点击“首页选项卡”
	document.addEventListener('gohome', function() {
		var defaultTab = document.getElementById("defaultTab");
		//模拟首页点击
		mui.trigger(defaultTab, 'tap');
		//切换选项卡高亮
		var current = document.querySelector(".mui-bar-tab>.mui-tab-item.mui-active");
		if (defaultTab !== current) {
			current.classList.remove('mui-active');
			defaultTab.classList.add('mui-active');
		}
	});
</script>
</body>

</html>
</body>
</html>