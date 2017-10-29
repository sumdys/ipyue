<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo ($title); ?></title>
	<link href="__PUBLIC__/mobile/css/global.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
	<style type="text/css">
		#ad{margin-bottom:10px;position:relative;}
		#ad img{width:100%;border:0;}
		#ad #adlist li{display:none;}
		#ad #adlist .on{display:block;}
		#ad #slidebar{position:absolute;left:48%;bottom:8px;}
		#ad #slidebar li{font-size:10px;display:inline-block;color:#5d5d5d;cursor:pointer;padding:0 2px;}
		#ad #slidebar li.select{color:#e0e0e0;}

		#entrance{height:320px;background-color:#fff;}
		#entrance dt,#entrance dd{float:left;width:100%;/*height:60%;*/margin-bottom:3px;}
		#entrance a{display:block;/*position:relative;*/}
		#entrance a.c01{background:#e43636 url(__PUBLIC__/mobile/images/icon01.png) no-repeat right bottom;}
		#entrance a.c02{background:#d3c41e url(__PUBLIC__/mobile/images/icon07.png) no-repeat right bottom}
		#entrance a.c03{background:#348bd3 url(__PUBLIC__/mobile/images/icon03.png) no-repeat right bottom}
		#entrance a.c04{background:#2ba5c9 url(__PUBLIC__/mobile/images/icon04.png) no-repeat right bottom}
		#entrance a.c05{background:#795e6d url(__PUBLIC__/mobile/images/icon05.png) no-repeat right bottom}
		#entrance a.c06{background:#afafac url(__PUBLIC__/mobile/images/icon06.png) no-repeat right bottom}
		#entrance dt a{height:70%;}
		#entrance dd a{height:70px;}
		#entrance h3{color:#fff;font-size:16px;z-index:100;line-height:24px;position:absolute;left:15px;top:15px;}
		#entrance span{position:absolute;right:15px;bottom:10px;}
		#entrance .ft-tj-d{background-color:white;width:100%;color:blue;/*position:absolute;opacity: 0.8;*/bottom:0px;}
		.search{width: 100%;text-align: center;height: 45px;margin-top:15px}
		.search p{width: 100%;}
		.search input{width: 50%;height: 70%;border: 2px solid #000000;border-radius:3px;}
		#nav{width: 98.5%;height: 90px;padding-left: 10px;font-size:16px;font-family: "微软雅黑";}
		#nav dt{width: 25%;text-align: center;float: left;
			height: 80px;color:black;}
		#nav dt p{font-size: 1.25em;}
		.btn-info{border: none;background-color: blue;
			color: white;height: 70%;text-align: center;font-size: 1.25em;border-radius:3px;}
		.mui-table-view-chevron .mui-table-view-cell{padding-right: 0px;}
		.mui-table-view-cell{padding:0px;}
		.mui-bar-nav{
			height: 50px;
			text-align: center;
		}
		#keyword{height: 80%;margin-top: 5px;width: 80%;}
		#searchdData{
			height: 80%;margin-top: 5px;font-size: 15px;}
		.images_c01{width: 100%;height: 100%;}
		.mui-scroll-wrapper{top:50px;}
		#pullrefresh{background-color: #fff;}
		.liji{
			float: right;padding: 3px 10px;margin-right:5px;border: 1px solid orange;color:orange;
		}
	</style>
<script>
//var _hmt = _hmt || [];
//(function() {
//var hm = document.createElement("script");
//hm.src = "https://hm.baidu.com/hm.js?646713249fd3ceeab66e16bc78c8e814";
//var s = document.getElementsByTagName("script")[0]; 
//s.parentNode.insertBefore(hm, s);
//})();
</script>

</head>

<body style="height: 80%">
<header class="mui-bar mui-bar-nav">
	<!--<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>-->
	<!--<h1 id="title" class="mui-title">-->
	<input type="text" name="" id="keyword" placeholder="城市名称/景点名称/游玩主题" value=""><button class="btn btn-info" style="background-color:#5bc0de;" id="searchdData">搜索</button>
	<!--</h1>-->
</header>
<div id="page">
	<div id="content" class="mui-content">
		<div id="pullrefresh" class="mui-content mui-scroll-wrapper">
			<div class="mui-scroll">
				<!--<div class="ad search">-->
					<!--<input type="text" name="" id="keyword" placeholder="输入搜索" value=""><button class="btn btn-info" id="searchdData">搜索</button>-->
				<!--</div>-->
				<div id="ad">
					<ul id="adlist">
						<?php if(is_array($ad_list)): $i = 0; $__LIST__ = $ad_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($key == 0): ?>class="on"<?php endif; ?>><a href="<?php echo ($vo["src"]); ?>"><img src="__PUBLIC__/uploads/ad/<?php echo ($vo["img"]); ?>" width="100%"></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						
						<!--<li><a href="<?php echo U('/Mydemand');?>"><img src="__PUBLIC__/images/m-index-2.jpg" width="100%"></a></li>-->
						<!-- <li><a href="<?php echo U('/specialoffer/hnair_sales');?>"><img src="__PUBLIC__/mobile/images/iad11.jpg" width="100%"></a></li> -->

					</ul>
					<ul id="slidebar">
						<?php if(is_array($ad_list)): $i = 0; $__LIST__ = $ad_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($key == 0): ?>class="select"<?php endif; ?>>●</li><?php endforeach; endif; else: echo "" ;endif; ?>
						<li>●</li>
					</ul>
				</div>

				<dl id="nav">
					<dt><a href="<?php echo U('/Microshop/shop_index');?>">
						<img src="__PUBLIC__/mobile/images/icon/58c8fa4d97524_thumb.png" height="58%">
						<p>自由行</p>
					</a>
					</dt>

					<dt><a href="<?php echo U('/Microshop/shop_index');?>/type/4"><img src="__PUBLIC__/mobile/images/icon/58c8fa3a98564_thumb.png" height="58%"><p>别墅客栈</p></a></dt>
					<dt><a href="<?php echo U('/Microshop/shop_index');?>/type/5"><img src="__PUBLIC__/mobile/images/icon/58c8fa5df35a2_thumb.png" height="58%"><p>邮轮</p></a></dt>
					<dt><a href="<?php echo U('/Microshop/shop_index');?>/type/7"><img src="__PUBLIC__/mobile/images/icon/58c8fa194dbfd_thumb.png" height="58%"><p>独家资源</p></a></dt>
				</dl>
				<dl id="nav">
					<dt><a href="<?php echo U('/Microshop/shop_index');?>/type/8">
						<img src="__PUBLIC__/mobile/images/icon/gentuan.png" height="58%">
						<p>跟团游</p>
					</a>
					</dt>

					<dt><a href="<?php echo U('/Microshop/shop_index');?>/type/9"><img src="__PUBLIC__/mobile/images/icon/guanduan.png" height="58%"><p>高端游</p></a></dt>
					<dt><a href="<?php echo U('/Microshop/shop_index');?>/type/10"><img src="__PUBLIC__/mobile/images/icon/xlyy.png" height="58%"><p>亲子游学</p></a></dt>
					<dt><a href="<?php echo U('/Microshop/shop_index');?>/type/11"><img src="__PUBLIC__/mobile/images/icon/menpiao.png" height="58%"><p>景点门票</p></a></dt>
				</dl>
				<dl id="entrance" class="mui-table-view mui-table-view-chevron">
					<?php if(is_array($list["tj_line"])): $i = 0; $__LIST__ = $list["tj_line"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt>
							<a href="<?php echo U('freetour/detail');?>/id/<?php echo ($vo["id"]); ?>/from/<?php echo ($vo["form"]); ?>" class="c01">
								<img class="images_c01" src="__PUBLIC__/uploads<?php echo ($vo[images][0]); ?>">

								<div class="ft-tj-d">
									<div style="float: left;margin: 5px;font-size: 1.2em;width: 98%;">
										<font color="#7A7A7A"><B><?php echo ($vo["title"]); ?></B></font><br><font color="#EE2C2C">现价:<?php echo ($vo["price"]); ?>RMB</font>&nbsp;&nbsp;&nbsp;<font color="#C5C1AA"><del>原价:<?php echo ($vo["old_price"]); ?>RMB</del></font><b class="liji">立即预订</b>
									</div>
								</div>
								<!--<h3>旅游路线推荐</h3>-->

							</a>
						</dt><?php endforeach; endif; else: echo "" ;endif; ?>
				</dl>
			</div>
		</div>


	</div>
	<!--  <div id="footer">
    <!-- <div id="telservice">
    <a href="javascript:open_kf()" class="service">在线咨询</a>
    <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=test" target="_blank" class="qq">QQ咨询</a>
    <a href="tel:4006085188" class="tel"></a>
    </div> -->
    <div class="nav"><a href="<?php echo U('/About/faq');?>">常见问题</a><!--<a href="<?php echo U('/Verify');?>" class="bor">机票验真</a>--><a href="<?php echo U('/Complaint');?>" class="bor">意见反馈</a><a href="http://www.ipyue.com/" target="_blank">电脑版</a></div>
    <div id="copyright">@ 2016 品悦网</div>
  </div>

-->
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
	
</div>

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

<script src="__PUBLIC__/js/mui.min.js"></script>
<script src="__PUBLIC__/js/mui.pullToRefresh.js"></script>
<script src="__PUBLIC__/js/mui.pullToRefresh.material.js"></script>
<script>
	var page = 0;
	var sumPage = 0;
	var type = '';
	var cid = '';
	mui.init({		
		pullRefresh: {
			container: '#pullrefresh',
//			down: {
//				callback: pulldownRefresh
//			},
			up: {
				height:10,
				contentrefresh: '正在加载...',
				callback: pullupRefresh
			}
		}		
		
	});
	mui('.mui-scroll').style.transform='translate3d(0px, 0px, 0px) translateZ(0px)';
	/**
	 * 下拉刷新具体业务实现
	 */
//	function pulldownRefresh() {
//		setTimeout(function() {
//			var table = document.body.querySelector('.mui-table-view');
//			var cells = document.body.querySelectorAll('.mui-table-view-cell');
//			for (var i = cells.length, len = i + 3; i < len; i++) {
//				var li = document.createElement('dt');
//				li.className = 'mui-table-view-cell';
//				li.innerHTML = '<a class="mui-navigate-right">Item ' + (i + 1) + '</a>';
//				//下拉刷新，新纪录插到最前面；
//				table.insertBefore(li, table.firstChild);
//			}
//			mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
//		}, 1500);
//	}
	var count = 0;
	/**
	 * 上拉加载具体业务实现
	 */
	function pullupRefresh() {
		page++;
		//参数为true代表没有更多数据了。
		mui('#pullrefresh').pullRefresh().endPullupToRefresh((++count > <?php echo ($countPage); ?>));
		var table = document.body.querySelector('.mui-table-view');
//		var cells = document.body.querySelectorAll('.mui-table-view-cell');
		$.ajax({
			type: "POST",
			url: "<?php echo U('/Microshop/ajaxList');?>",
			data: {'page':page,'type':type,'cid':cid},
			success: function(res){
				var data=(eval("("+res+")"));
				if(data.status){
					$.each(data.contact,function(k,v){
						var li = document.createElement('dt');
//						li.className = 'mui-table-view-cell';
						li.innerHTML = '<a href="<?php echo U("freetour/detail");?>/id/'+ v.id+'/from/'+v.form+'" class="c01">'+
						'<img class="images_c01" src="__PUBLIC__/uploads'+v.images[0]+'">'+
						'<div class="ft-tj-d">'+
						'<div style="float: left;margin: 5px;font-size: 1.2em;width:98%;">'+
						'<font color="#7A7A7A"><B>'+ v.title+'</B></font><br><font color="#EE2C2C">现价:'+v.price+'RMB</font>&nbsp;&nbsp;&nbsp;<font color="#C5C1AA"><del>原价:'+ v.old_price+'RMB</del></font>'+ '<b class="liji">立即预订</b>'+
						'</div>'+
						'</div>'+
						'</a>';
						table.appendChild(li);
					});
				}else{

				}
			}
		});
	}

	function getActivityLists(page,type,cid,callback){
		$.ajax({
			type: "POST",
			url: "<?php echo U('/Microshop/ajaxList');?>",
			data: {'page':page,'type':type,'cid':cid},
			success: function(data){
				if(data.status){
					callback(data);
				}else{
					callback(false,data.msg);
				}
			}
		});
	}

	if (mui.os.plus) {
		mui.plusReady(function() {
			setTimeout(function() {
				mui('#pullrefresh').pullRefresh().pullupLoading();
			}, 1000);

		});
	} else {
		mui.ready(function() {
			mui('#pullrefresh').pullRefresh().pullupLoading();
		});
	}
</script>
</body>
</html>
<script type="text/javascript" src="__PUBLIC__/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.query-object.js"></script>
<!--<script type="text/javascript" src="__PUBLIC__/mobile/js/gmu.js"></script>-->
<!--<script type="text/javascript" src="__PUBLIC__/js/iscrollAssist.js"></script>-->
<script type="text/javascript">
	$(function(){
		 mui('body').on('tap','a',function(){
			 window.location.href=this.href;
		 });
		 //搜索
		$('#searchdData').click(function(e) {
			var kw = $('#keyword').val();
			location.search = $.query.set('keyword', kw);
		});

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