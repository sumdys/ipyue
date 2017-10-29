<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>会员中心-品悦手机网</title>
<link href="__PUBLIC__/mobile/css/global.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
<style type="text/css">
#topnav .icon{width:80px;}
.block h2,.block h3,.block li a,#block li a,#tab_block li a{background:url(__PUBLIC__/mobile/images/title_bg.png) no-repeat;}
.block{background:#FFF;padding:0px 10px;margin:10px 10px 0px 10px;}
.block h2,.block h3{color:#FFF;height:31px;line-height:31px;text-align:center;}
.block h2{width:100px;background-position:0px -40px;}
.block h3{width:130px;background-position:0px -82px;}
.block ul,.block .link{padding:0px 15px;}
.block li,.block li a,.block .list dd,#tab_block li a,#block li a{line-height:30px;color:#333;}
.block li span,.block .link span{width:60px;color:#666;display:inline-block;}
.block li a,#tab_block li a,#block li a{display:block;background-position:right -130px;}
.block .link{height:40px;line-height:40px;}
.block .link a{color:#2690d8;font-size:14px;line-height:40px;padding:0px 5px;}
.block .link .orange{color:#FF8400;}
.block .list{padding:0 10px 20px 10px;}
.block .list dd{padding:0 10px;}
.block .list dd{background:#f2f1f0;margin-top:10px;}
.block .list span{padding-right:10px;color:#666;display:inline-block;}
.block .list em{font-style:normal;color:#2690d8;}
.block .list b{padding-left:25px;display:inline-block;height:30px;background:url(__PUBLIC__/images/assistant.png) no-repeat -10px -152px;font-family:Arial;font-weight:normal;}
.block .list a{float:right;color:#2690d8;}

#tab_block,#block{background:#FFF;margin:10px 10px 0px 10px;}
#tab,#openShop{background:#f2f1f0;}
#tab a,#openShop a{display:inline-block;width:23%;margin-right:2%;background-color:#67b1e4;text-align:center;color:#FFF;line-height:30px;cursor:pointer;}
#tab a:hover,#tab a.on,#openShop a:hover,#openShop a.on{background-color:#FFF;color:#ff8400;}
#tab_block ul,#block ul{padding:0px 25px;display:none;}
#tab_block ul.on,#block ul.on{display:block;}

#adviser{padding:15px;}
#adviser .avatar,.block .infos,.block .star a{display:inline-block;}
#adviser .avatar{width:82px;}
#adviser .avatar img{width:82px;height:82px;}
#adviser .avatar a.name{color:#2690d8;font-weight:bold;line-height:24px}
#adviser .infos{padding-left:15px;}
#adviser .infos div{display:block;line-height:23px;color:#666;}
#adviser .infos label{color:#000;}
#adviser .infos .name{color: #2690d8;font-weight: bold;line-height: 24px;}
#adviser .kfpj,.block .pj{width:100%;display:block;color:#666;line-height:25px;}
#adviser .star a{margin-right:5px;background:url(__PUBLIC__/mobile/images/pingjia_dj.gif) no-repeat 0px 0px;width:59px;height:11px;}
#adviser .star a.a10{background-position:0 0px}
#adviser .star a.a9{background-position:0 -20px}
#adviser .star a.a8{background-position:0 -40px}
#adviser .star a.a7{background-position:0 -60px}
#adviser .star a.a6{background-position:0 -80px}
#adviser .star a.a5{background-position:0 -100px}
#adviser .star a.a4{background-position:0 -120px}
#adviser .star a.a3{background-position:0 -140px}
#adviser .star a.a2{background-position:0 -160px}
#adviser .star a.a1{background-position:0 -180px}
#adviser .star a.a0{background-position:0 -200px}
#adviser .kfpj .aa a{color:#06C;padding-left:10px;width:100px;}
#adviser .pj span{line-height:16px;height:16px;padding:4px 0 4px 20px;background:url(__PUBLIC__/mobile/images/pj.gif) no-repeat 0px 0px;display:inline-block;}
#adviser .pj .hp{background-position:0 4px;}
#adviser .pj .zp{background-position:0 -16px;}
#adviser .pj .cp{background-position:0 -36px;}
.mui-h2, h2{font-size: 20px;}
</style>
</head>
 
<body>
<div id="page">
  <div id="content">
    <h1 id="topnav"><span class="left icon"><a href="<?php echo U('/');?>" class="icon-home">首页</a></span>我的帐户<span class="right" id="exit">退出登录</span></h1>
    <div class="block">
      <h2>个人设置
      </h2>
      <!--<ul>
       <li><span>用&nbsp;户&nbsp;名：</span> <?php echo ($userinfo["username"]); ?></li>
       <li><a href="<?php echo U('/Member/information');?>"><span>会员级别：</span>蓝钻会员</a></li>
      </ul>-->
      <div class="link"><a class="right" href="<?php echo U('/Member/information');?>">设置</a><span>用&nbsp;户&nbsp;名：</span> <?php echo ($userinfo["username"]); ?></div>
      <!--<div class="link"><a class="right" href="<?php echo U('/About/faq/');?>#upgrade">如何升级</a><a class="right" href="<?php echo U('/Member/myPrivilege');?>">我的特权</a><span>会员级别：</span><span class="orange">蓝钻会员</span></div>-->
      <div class="link"><span>账户安全：</span><?php echo ($Safety_level); ?>&nbsp;&nbsp;[<a href="<?php echo U('/Member/editPwd');?>">修改密码</a>]</div>
      <!--<dl class="list">-->
      	<!--<dd><span>预付款余额：</span>0元</dd>-->
        <!--<dd><a href="<?php echo U('/About/faq/');?>#coupon">如何使用？</a><span>现金券：</span><em><?php echo ($overage); ?></em>元</dd>-->
        <!--<dd><a href="<?php echo U('/About/faq/');?>#fun">积fun规则</a><span>积fun：</span><?php echo ($totlejf); ?></dd>-->
        <!--<dd><a href="<?php echo U('/About/faq/');?>#aizuan">如何获取</a><span>爱钻：</span><b>x<?php echo ($totleaz); ?></b></dd>-->
      <!--</dl>-->
    </div>

    <div class="block">
      <h2>我的订单</h2>
      <ul>
       <li><a href="<?php echo U('/Member/orders');?>">所有订单：（<?php echo (($common['process_count'])?($common['process_count']):0); ?>）</a></li>
       <li><a href="<?php echo U('/Member/ordersPending');?>">待付款订单：（<?php echo (($common['pending_count'])?($common['pending_count']):0); ?>）</a></li>
       <li><a href="<?php echo U('/Member/ordersCancel');?>">已取消订单：（<?php echo (($common['cancel_count'])?($common['cancel_count']):0); ?>）</a></li>
      </ul>
    </div>

      <div id="block">
          <div id="openShop"><a class="on">推广中心</a><a>我的微店</a></div>
          <ul class="on">
              <li><a href="<?php echo U('/Extend/index');?>">推广素材</a></li>
              <li><a href="<?php echo U('/Extend/extend_order');?>">推广订单</a></li>
              <li><a href="<?php echo U('/Extend/profit');?>">推广收益</a></li>
          </ul>
          <ul>
          	<li><a href="<?php echo U('/Microshop/open_shop');?>">我要开店</a></li>
              <?php if($userinfo["shop_type"] == 1): ?><li><a href="<?php echo U('/Microshop/shop_index',array('user_id'=>$userinfo['id']));?>">我的微店</a></li>
                  <li><a href="<?php echo U('/Microshop/bookingProcess');?>">店内订单</a></li><?php endif; ?>
          </ul>
      </div>
    
    
    <div id="tab_block">
    	<div id="tab"><a class="on">现金券</a><a>我的积分</a>
            <a>我的爱钻</a><!--<a>我的礼品</a>-->
        </div>
		<ul class="on">
			<li><a href="<?php echo U('/Member/cashcouponExchange');?>">使用记录</a></li>
            <li><a href="<?php echo U('/Member/cashcouponDetail');?>">发放记录</a></li>
      	</ul>
        <ul>
       		<li><a href="<?php echo U('/Member/integralDetail');?>">积fun明细</a></li>
            <li><a href="<?php echo U('/Member/integralExchange');?>">兑换记录</a></li>
      	</ul>
        <ul>
       		<li><a href="<?php echo U('/Member/aizuanDetail');?>">爱钻明细</a></li>
            <li><a href="<?php echo U('/Member/aizuanExchange');?>">兑换记录</a></li>
      	</ul>
        <ul>
       		<li><a href="<?php echo U('/Member/myFavorite');?>">我的收藏</a></li>
            <li><a href="<?php echo U('/Member/myCart');?>">我的购物车</a></li>
            <li><a href="<?php echo U('/Member/myGift');?>">已兑换礼品</a></li>
      	</ul>
    </div>
      <div class="block">
          <h2>应用中心</h2>
          <ul>
              <li><a href="<?php echo U('/Member/bookingPending');?>">预订评价</a></li>
              <!--<li><a href="<?php echo U('/Member/bookingProcess');?>">推广历史</a></li>-->
              <!--<li><a href="<?php echo U('/Member/bookingCancel');?>">推广收益</a></li>-->
          </ul>
      </div>
    <!--<div class="block">-->
      <!--<h3>我的旅行顾问</h3>-->
      <!--<div id="adviser">-->
      <!--<span class="avatar">-->
        <!--<img src="__PUBLIC__/uploads<?php if($userinfo['user']['avatar']): echo ($userinfo['user']['avatar']); else: ?>/avatar/default.gif<?php endif; ?>">-->
      <!--</span>-->
      <!--<span class="infos">-->
          <!--<a href="<?php echo U('/Adviser/review/id/'.$userinfo['user']['id']);?>" class="name"><?php echo ($userinfo["user"]["name"]); ?></a>-->
        <!--<div>QQ：<label><?php echo ($userinfo["user"]["qq"]); ?></label></div>-->
        <!--<div>手机号码：<label><a href="tel:<?php echo ($userinfo["user"]["public_mobile"]); ?>"><?php echo ($userinfo["user"]["public_mobile"]); ?></a></label></div>-->
        <!--<div>电话号码：<label><a href="tel:<?php echo ($userinfo["user"]["telephone"]); ?>"><?php echo ($userinfo["user"]["telephone"]); ?></a></label></div>-->
      <!--</span>-->
      <!--<span class="kfpj">-->
        <!--<label>服务评价：</label>-->
        <!--<label class="star" title="五颗星"><a class="a<?php echo ($serverImg); ?>"></a><?php echo ($server); ?></label>-->
        <!--<label class="aa"><a href="<?php echo U('/Adviser/review/id/'.$userinfo['user']['id']);?>">查看所有评价</a></label>-->
      <!--</span>-->
      <!--</div>-->
    <!--</div>-->
    
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
		$('#tab a').click(function(){
			var i=$(this).index();
			$(this).addClass('on').siblings().removeClass('on');
			$('#tab_block ul').eq(i).addClass('on').siblings().removeClass('on');
			});
        $('#openShop a').click(function(){
            var i=$(this).index();
            $(this).addClass('on').siblings().removeClass('on');
            $('#block ul').eq(i).addClass('on').siblings().removeClass('on');
        });
    });
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