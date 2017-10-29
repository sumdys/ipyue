<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <title><?php echo ($title); ?></title>
    <meta charset="utf-8" />
		<!--页面优化-->
		<meta name="MobileOptimized" content="320">
		<!--默认宽度320-->
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
		<!--viewport 等比 不缩放-->
		<meta http-equiv="cleartype" content="on">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<!--删除苹果菜单-->
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<!--默认颜色-->
		<meta name="apple-mobile-web-app-title" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<!--加载全部后 显示-->
		<meta content="telephone=no" name="format-detection" />
		<!--不识别电话-->
		<meta content="email=no" name="format-detection" />
		<link rel="stylesheet" href="__PUBLIC__/mobile/css/style.css" />
	    <!--组件依赖js begin-->
	    <script src="__PUBLIC__/mobile/js/zepto.min.js"></script>
	    <!--组件依赖js end-->		
		<script type="text/javascript" src="__PUBLIC__/mobile/js/gmu.min.js"></script>
        <script type="text/javascript" src="__PUBLIC__/mobile/js/app-basegmu.js"></script>
         <style  type="text/css">
			 /*.aaaa{margin-left: 10px;width: 10%;}*/
			 /*.ads_orinput{width: 95%;}*/
			 .ads-hd{padding: 0px 10px;}
		 </style>

</head>
<body class="back1 color6">
		<form action="" method="post" id="orderform">
		
		<!-- 商品明细  -->
		<div class="ads-lst border-t1 border-b1 ovflw mr-b back2">
			<div class="ads-line"></div>
			<p class="ads-tt border-b1">您选择的路线：</p>
					<div class="ads_orinfo ads_padding3 ovflw border-b1">
						<div class="ads_orinfol ovflw fl" style="vertical-align: middle">
							<div class="ads_or_img fl">
								<!-- 图片大小为147*101 -->
								<img src="__PUBLIC__/uploads<?php echo ($info['images'][0]); ?>" style="height: 25%"/>
							</div>
							<h3><?php echo ($info["title"]); ?></h3>
							<p class="color3 fonts2"></p>
						</div>
						<!--<div class="ads_orprice ovflw ">-->
							<!--<p ><em class="fonts85">￥</em><em class="fonts18"><?php echo ($vo["price"]); ?></em></p>-->
							<!--<p class="ads_ornum fonts85">X</p>-->
						<!--</div>-->
					</div>				
			<!-- <p class="border-b1 ads_ortt3 fonts18 color3">&nbsp;使用代金卷<span class="fr"><select name="djqid" id="djqid" class="ads-sel"><option value="0" data-money="0">请选择有效代金卷</option><?php if(is_array($djq)): foreach($djq as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" data-money="<?php echo ($vo["money"]); ?>"><?php echo ($vo["money"]); ?>元代金卷</option><?php endforeach; endif; ?></select></span></p> -->
			<!-- <p class="border-b1 ads_ortt3 fonts85">&nbsp;邮费政策：<?php if(($isyf) == "1"): ?>全场定邮<?php echo ($yf); ?>元，订单满<?php echo ($yftop); ?>元包邮。<?php else: ?>全场包邮<?php endif; ?></p> -->			
			<p class=" ads_ortt3 fonts85 ovflw"><span class="fr ">总金额：<em class="fonts18 color3">￥<b class="totalprice"><?php echo ($info["total_price"]); ?></b></em></span></p>
			<p class="border-b1 ads_ortt3 fonts85 ads"><input type="text" name="msg" class="ads_orinput" placeholder="给卖家留言" value="<?php echo ($info["remark"]); ?>"/></p>
		</div>
		<!-- 地址  -->
		<div class="ads-hd border-b1 back2 ovflw mr-b">
			<p class="border-b1 ads_ortt3 fonts85 ads"><!--<span class="aaaa"><b style="color: red;">*</b>&nbsp;联&nbsp;系&nbsp;人：</span>--><input type="text" name="content" class="ads_orinput" disabled id="content" value="<?php echo ($info["linkman"]); ?>" placeholder="联系人"/></p>
			<p class="border-b1 ads_ortt3 fonts85 ads"><!--<span class="aaaa"><b style="color: red;">*</b>联系电话：</span>--><input type="text" name="telephone" class="ads_orinput" id="telephone" disabled value="<?php echo ($info["mobile"]); ?>" placeholder="联系手机号"/></p>
			<p class="border-b1 ads_ortt3 fonts85 ads"><!--<span class="aaaa"><b style="color: red;">*</b>出发日期：</span>--><input type="text" name="msg" class="ads_orinput" id="outDate" value="<?php echo ($info["start_date"]); ?>" disabled placeholder="选择出发日期"/></p>
			<p class="border-b1 ads_ortt3 fonts85 ads"><!--<span class="aaaa"><b style="color: red;">*</b>出发日期：</span>--><input type="text" name="num" class="ads_orinput" id="num" disabled value="<?php echo ($info["num"]); ?>" placeholder="选择出发日期"/></p>
			<!-- <div class="ads-line"></div> -->
			<!--<a href="#" class="ads-chs" id="changeaddress">-->
				<!--<?php if(empty($vip)): ?>-->
					<!--请选择收货地址<i class="iconfont fr">新增地址&#xe6a3</i>-->
					<!--<?php else: ?>-->
					<!--收货人：<?php echo ($vip["name"]); ?><i class="iconfont fr">修改地址&#xe6a3</i>-->
					<!--<p class="fonts9">电话：<?php echo ($vip["mobile"]); ?></p>-->
					<!--<p class="fonts9">收货地址：<?php echo ($vip["address"]); ?></p>-->
				<!--<?php endif; ?>-->
			<!--</a>-->
			<input type="hidden" name="oid" id="oid" value="<?php echo ($info["id"]); ?>">
			<input type="hidden" name="paytype" value="" id="paytype">
		</div>
		</form>
		<!-- 支付方式 -->
		<div class="ads-lst border-t1 border-b1 ovflw mr-b back2">
			<!-- <p class="ads-tt border-b1">支付方式</p> -->
					<!--<div class="ads_pay ovflw ads_border_dashed" data-paytype = "money" data-disable="0">-->
						<!--<span class="iconfont fl ads_pay_lineh dtl_mar1">&#xe6d4</span>-->
						<!--<div class="ads_orimg fl dtl_mar1">-->
							<!--<img src="__PUBLIC__/App/img/tue.jpg" />-->
						<!--</div>-->
						<!--<p class="ads_pay_p1 ads_pay_lineh1">余额：<i id='money' data-money='<?php echo ($_SESSION['WAP']['vip']['money']); ?>'>￥<?php echo ($_SESSION['WAP']['vip']['money']); ?></i></p>-->
						<!--<p class="ads_pay_p2 ads_pay_lineh1 color10 ads_font_size2">余额支付</p>-->
					<!--</div>-->
					<div class="ads_pay ovflw" data-paytype = "wxpay" data-disable="0">
						<span class="iconfont fl ads_pay_lineh dtl_mar1">&#xe656</span>
						<div class="ads_orimg fl dtl_mar1">
							<img src="__PUBLIC__/mobile/images/wxpay.jpg" />
						</div>
						<p class="ads_pay_lineh">微信支付</p>
					</div>					
					<!-- div class="ads_pay ovflw" data-paytype = "alipayApp" data-disable="1">
						<span class="iconfont fl ads_pay_lineh dtl_mar1">&#xe656</span>
						<div class="ads_orimg fl dtl_mar1">
							<img src="__PUBLIC__/App/img/zhif.jpg" />
						</div>
						<p class="ads_pay_lineh">支付宝支付</p>
					</div -->
					
					<!-- 银联支付备用 -->
					<!--<div class="ads_pay ovflw " data-paytype = "yinlian">
						<span class="iconfont fl ads_pay_lineh dtl_mar1">&#xe656</span>
						<div class="ads_orimg fl dtl_mar1">
							<img src="__PUBLIC__/App/img/yl.jpg" />
						</div>
						<p class="ads_pay_lineh">银联支付</p>
					</div>-->
		</div>
		<div class="insert1"></div>
		<div class="dtl-ft ovflw" style="text-align: center;">
				<!--<div class=" fl dtl-icon dtl-bck ovflw">-->
					<!--<a href="<?php echo ($basketurl); ?>">-->
						<!--<i class="iconfont">&#xe679</i>-->
					<!--</a>-->
				<!--</div>-->
				<a href="javascript:void(0);" class="ads-btn back3" id="orderconfirm" style="margin-right: 10px;padding: 10px 0px;">确认支付</a>
				<!--<span class="fr ads-sum"><em class="fonts9">商品:</em><em class="fonts1">￥<b class="totalprice"><?php echo ($payprice); ?></b></em>&nbsp;&nbsp;&nbsp;&nbsp;<em class="fonts1">抵扣<?php echo ($_SESSION["SHOP"]["set"]["pointname"]); ?><b class="totalprice"><?php echo ($totalpoint); ?></b></em>&nbsp;&nbsp;&nbsp;&nbsp;邮费:<em class="fonts18 color3">￥<b><?php echo ($yf); ?></b></em></span>-->
		</div>

		<!--通用分享-->
		
	</body>
</html>
<!--<script type="text/javascript" src="__PUBLIC__/js/jquery-2.0.3.min.js"></script>-->
<script type="text/javascript">
	$('.ads_pay').click(function(){
		var isdis=$(this).data('disable');
		if(isdis==0){
			var sp=$('.ads_pay span');
			$(sp).css('color',' #cfcfcf');
			$(this).find('span').css('color',' #ff3000');
			$(paytype).val($(this).data('paytype'));
		}else{
			App_gmuMsg('请使用其它方式！');
		}
	});
	$('#orderconfirm').on('click',function(){
		if(!$('#paytype').val()){
			App_gmuMsg('请选择支付方式！');
			return false;
		}
		window.location.href="<?php echo U('/wx/jsapi');?>";
		//调起微信支付
//		callpay();
	});

</script>