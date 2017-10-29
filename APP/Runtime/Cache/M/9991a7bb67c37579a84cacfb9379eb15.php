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
		<link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/mui.picker.min.css" />
	    <!--组件依赖js begin-->
	    <script src="__PUBLIC__/mobile/js/zepto.min.js"></script>
	    <!--组件依赖js end-->		

         <style  type="text/css">
			 /*.aaaa{margin-left: 10px;width: 10%;}*/
			 /*.ads_orinput{width: 95%;}*/
			 .ads-hd{padding: 0px 10px;}
			 .mui-btn-block{margin-bottom: 0px;padding:0px 0px;height: 40px;text-align: left;padding-left: 12px;}
			 .mui-btn:enabled:active{background-color:white;color: black;}
			 .mui-active:enabled{background-color: white;color: black;}
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
			<p class=" ads_ortt3 fonts85 ovflw"><span class="fr ">金额：<em class="fonts18 color3">￥<b class="totalprice"><?php echo ($info["price"]); ?></b></em></span></p>
			<p class="border-b1 ads_ortt3 fonts85 ads"><input type="text" name="msg" class="ads_orinput" placeholder="给卖家留言"/></p>
		</div>
		<!-- 地址  -->
		<div class="ads-hd border-b1 back2 ovflw mr-b">
			<p class="border-b1 ads_ortt3 fonts85 ads"><!--<span class="aaaa"><b style="color: red;">*</b>&nbsp;联&nbsp;系&nbsp;人：</span>--><input type="text" name="content" class="ads_orinput" id="content" placeholder="联系人"/></p>
			<p class="border-b1 ads_ortt3 fonts85 ads"><!--<span class="aaaa"><b style="color: red;">*</b>联系电话：</span>--><input type="text" name="telephone" class="ads_orinput" id="telephone" placeholder="联系手机号"/></p>
			<p class="border-b1 ads_ortt3 fonts85 ads"><!--<span class="aaaa"><b style="color: red;">*</b>出发日期：</span>--><input id='outDate' data-options='<?php echo ($beginYear); ?>' value="" class="ads_orinput btn mui-btn mui-btn-block" placeholder="选择出发日期" /><!--<input type="text" name="msg" class="ads_orinput" id="outDate" placeholder="选择出发日期"/>--></p>
			<p class="border-b1 ads_ortt3 fonts85 ads"><!--<span class="aaaa"><b style="color: red;">*</b>出发日期：</span>--><input type="text" name="num" class="ads_orinput" id="num" value="1" placeholder="选择出发日期"/></p>
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
			<input type="hidden" name="id" id="freetour_id" value="<?php echo ($info["id"]); ?>">
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
					<!--<div class="ads_pay ovflw" data-paytype = "wxpay" data-disable="0">-->
						<!--<span class="iconfont fl ads_pay_lineh dtl_mar1">&#xe656</span>-->
						<!--<div class="ads_orimg fl dtl_mar1">-->
							<!--<img src="__PUBLIC__/mobile/images/wxpay.jpg" />-->
						<!--</div>-->
						<!--<p class="ads_pay_lineh">微信支付</p>-->
					<!--</div>					-->
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
				<a href="javascript:void(0);" class="ads-btn back3" id="orderconfirm" style="margin-right: 10px;padding: 10px 0px;">提交订单</a>
				<!--<span class="fr ads-sum"><em class="fonts9">商品:</em><em class="fonts1">￥<b class="totalprice"><?php echo ($payprice); ?></b></em>&nbsp;&nbsp;&nbsp;&nbsp;<em class="fonts1">抵扣<?php echo ($_SESSION["SHOP"]["set"]["pointname"]); ?><b class="totalprice"><?php echo ($totalpoint); ?></b></em>&nbsp;&nbsp;&nbsp;&nbsp;邮费:<em class="fonts18 color3">￥<b><?php echo ($yf); ?></b></em></span>-->
		</div>

		<!--通用分享-->
		<!---->
	</body>
<script src="__PUBLIC__/js/mui.min.js"></script>
<!--<script src="../js/mui.picker.js"></script>
<script src="../js/mui.dtpicker.js"></script>-->
<script src="__PUBLIC__/js/mui.picker.min.js"></script>
<script>
	(function($) {
		$.init();
		var result = $('#outDate')[0];
		var btns = $('.btn');
		btns.each(function(i, btn) {
			btn.addEventListener('tap', function() {
				var optionsJson = this.getAttribute('data-options') || '{}';
				var options = JSON.parse(optionsJson);
				var id = this.getAttribute('id');
				/*
				 * 首次显示时实例化组件
				 * 示例为了简洁，将 options 放在了按钮的 dom 上
				 * 也可以直接通过代码声明 optinos 用于实例化 DtPicker
				 */
				var picker = new $.DtPicker(options);
				picker.show(function(rs) {
					/*
					 * rs.value 拼合后的 value
					 * rs.text 拼合后的 text
					 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
					 * rs.m 月，用法同年
					 * rs.d 日，用法同年
					 * rs.h 时，用法同年
					 * rs.i 分（minutes 的第二个字母），用法同年
					 */
					result.value=rs.text;
					/*
					 * 返回 false 可以阻止选择框的关闭
					 * return false;
					 */
					/*
					 * 释放组件资源，释放后将将不能再操作组件
					 * 通常情况下，不需要示放组件，new DtPicker(options) 后，可以一直使用。
					 * 当前示例，因为内容较多，如不进行资原释放，在某些设备上会较慢。
					 * 所以每次用完便立即调用 dispose 进行释放，下次用时再创建新实例。
					 */
					picker.dispose();
				});
			}, false);
		});

	})(mui);
</script>
</html>
<!--<script type="text/javascript" src="__PUBLIC__/mobile/js/gmu.min.js"></script>-->
<!--<script type="text/javascript" src="__PUBLIC__/mobile/js/app-basegmu.js"></script>-->
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
			mui.alert('请使用其它方式！');
//			App_gmuMsg('请使用其它方式！');
		}
	});
	$('#orderconfirm').on('click',function(){
		if(!$('#content').val()){
			mui.toast('请填写联系人');
//			mui.alert('请填写联系人');
//			App_gmuMsg('请填写联系人！');
			return false;
		}
		if(!$('#telephone').val()){
			mui.toast('请填写联系手机号');
//			App_gmuMsg('请填写联系手机号！');
			return false;
		}
		if(!$('#outDate').val()){
			mui.toast('请选择出发日期');
//			App_gmuMsg('请选择出发日期！');
			return false;
		}
//		if(!$('#paytype').val()){
//			App_gmuMsg('请选择支付方式！');
//			return false;
//		}
		if(!$('#num').val()){
			mui.toast('请填写人数');
//			App_gmuMsg('请填写人数！');
			return false;
		}
//				var okfun=function(){$('#orderform').submit();}
		var okfun=function(){
			$.ajax({
//				dataType:'json',
				type:'post',
				url:"<?php echo U('/orders/submitOrderAjax');?>",
				data:{'id':$('#freetour_id').val(),'mobile':$('#telephone').val(),'linkman':$('#content').val(),'start_date':$('#outDate').val(),'paytype':$('#paytype').val(),'num':$('#num').val(),'msg':$('#msg').val()},

				success:function(res){
					var data=(eval("("+res+")"));
					if(data.status==1){
						window.location.href="<?php echo U('/orders/confirm');?>/oid/"+data.oid;
					}else{
						mui.alert(data.msg);
//						App_gmuMsg(data.msg);
						return false;
					}
				}

			});
		}
		//alert($('#djqid').val());
		if($('#djqid').val()==0){
			//App_gmuAlert('确认？','确认现在生成订单并付款吗？',false,okfun);
			okfun();
		}else{
			//App_gmuAlert('确认？','您选择使用代金卷，生成订单后此代金卷将立刻作废，不可再次使用！确认现在生成订单并付款吗？',false,okfun);
			okfun();
		}

	});

</script>