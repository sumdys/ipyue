<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>推荐订单</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
		<style>
			html,
			body {
				background-color: #efeff4;
			}
			.mui-bar~.mui-content .mui-fullscreen {
				top: 44px;
				height: auto;
			}
			.mui-pull-top-tips {
				position: absolute;
				top: -20px;
				left: 50%;
				margin-left: -25px;
				width: 40px;
				height: 40px;
				border-radius: 100%;
				z-index: 1;
			}
			.mui-bar~.mui-pull-top-tips {
				top: 24px;
			}
			.mui-pull-top-wrapper {
				width: 42px;
				height: 42px;
				display: block;
				text-align: center;
				background-color: #efeff4;
				border: 1px solid #ddd;
				border-radius: 25px;
				background-clip: padding-box;
				box-shadow: 0 4px 10px #bbb;
				overflow: hidden;
			}
			.mui-pull-top-tips.mui-transitioning {
				-webkit-transition-duration: 200ms;
				transition-duration: 200ms;
			}
			.mui-pull-top-tips .mui-pull-loading {
				/*-webkit-backface-visibility: hidden;
				-webkit-transition-duration: 400ms;
				transition-duration: 400ms;*/
				
				margin: 0;
			}
			.mui-pull-top-wrapper .mui-icon,
			.mui-pull-top-wrapper .mui-spinner {
				margin-top: 7px;
			}
			.mui-pull-top-wrapper .mui-icon.mui-reverse {
				/*-webkit-transform: rotate(180deg) translateZ(0);*/
			}
			.mui-pull-bottom-tips {
				text-align: center;
				background-color: #efeff4;
				font-size: 14px;
				line-height: 40px;
				color: #777;
			}
			.mui-pull-top-canvas {
				overflow: hidden;
				background-color: #fafafa;
				border-radius: 40px;
				box-shadow: 0 4px 10px #bbb;
				width: 40px;
				height: 40px;
				margin: 0 auto;
			}
			.mui-pull-top-canvas canvas {
				width: 40px;
			}
			.mui-slider-indicator.mui-segmented-control {
				background-color: #efeff4;
			}
			.mui-segmented-control.mui-scroll-wrapper .mui-control-item{padding:0px 35px;}
			.list{background-color:#FFF;padding:10px;border-bottom:1px solid #e7e5e5;}
			.list li{line-height:26px;}
			.list li input{float:right;margin-right:10px;}
			.list li span{color:#333;}
			.list li em{font-style:normal;color:#ff840f;}
			.list li a{color:#2690d8;}
			.mui-fullscreen .mui-segmented-control~.mui-slider-group{top:95px;}
		</style>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">推荐订单</h1>
		</header>
		<div class="mui-content">
			
			<div id="slider" class="mui-slider mui-fullscreen">
				<div>
						<form class="mui-input-group">
					    <div class="mui-input-row">
					        <input type="text" style="width: 80%;" placeholder="请输入订单号">
					        <button type="button" class="mui-btn mui-btn-primary">搜索</button>
					    </div>
					</form>
					</div>
				<div id="sliderSegmentedControl" class="mui-scroll-wrapper mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
					<div class="mui-scroll">
						<a class="mui-control-item mui-active" href="#item1mobile">
							所有订单
						</a>
						<a class="mui-control-item" href="#item2mobile">
							待付款
						</a>
						<a class="mui-control-item" href="#item3mobile">
						已取消
						</a>
						<!--<a class="mui-control-item" href="#item4mobile">
							社会
						</a>
						<a class="mui-control-item" href="#item5mobile">
							娱乐
						</a>
						<a class="mui-control-item" href="#item6mobile">
							科技
						</a>-->
					</div>
					
					
				</div>
				<div class="mui-slider-group">
					
					<div id="item1mobile" class="mui-slider-item mui-control-content mui-active">
						<div id="scroll1" class="mui-scroll-wrapper">
							<div class="mui-scroll">
								<ul class="mui-table-view">
									<?php if(is_array($allList)): $i = 0; $__LIST__ = $allList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell">
										<ul class="mui-table-view">
				                            <li><span style="font-size: 16px;"><?php echo ($vo["type"]); ?></span></li>
											 <li><span>订单号 ：</span><?php echo ($vo["order_num"]); ?></li>
											 <li><span>下单时间：</span><?php echo ($vo["create_time"]); ?></li>
											 <li><span><?php echo ($vo["title"]); ?></span></li>
											 <li style="margin-top: 10px"><span style="color:white;font-size:14px;padding: 5px;background-color:#c5862b;">去</span>&nbsp;&nbsp;<?php echo ($vo["create_time"]); ?></li>
											 <li style="margin-top: 10px;"><span style="color:white;padding: 5px;font-size:14px;background-color: #67b1e4;">返</span>&nbsp;&nbsp;<?php echo ($vo["create_time"]); ?></li>
											 <li><span>单价：</span><em>￥<?php echo ($vo["price"]); ?></em></li>
											 <li><span>总价：</span><em>￥<?php echo ($vo["total_price"]); ?></em></li>
											 <li><span>订单状态：</span><?php echo ($vo["state"]); ?>&nbsp;&nbsp;</li>
											 <li><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($vo["pay_state"] != 2): ?><!--<a href="javascript:void(0);" class="cancel_order" data-order_id="<?php echo ($vo["id"]); ?>">取消订单</a>--><?php endif; ?></span><?php if($vo["pay_state"] == 0): ?><span style="width: 90%;text-align: right;display: block;"><a href="<?php echo U('/extend/orderDetail');?>/id/<?php echo ($vo["id"]); ?>">查看详情</a><!--<a href="<?php echo U('Member/onlinePay','id='.$v[id]);?>" style="color:white;padding: 5px;background-color: darkorange;">立即付款</a>--></span><?php endif; ?></li>
				                        </ul>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
									
								</ul>
							</div>
						</div>
					</div>
					<div id="item2mobile" class="mui-slider-item mui-control-content">
						<div class="mui-scroll-wrapper">
							<div class="mui-scroll">
								<ul class="mui-table-view">
									<?php if(is_array($nopayList)): $i = 0; $__LIST__ = $nopayList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell">
										<ul class="mui-table-view">
				                            <li><span style="font-size: 16px;"><?php echo ($vo["type"]); ?></span></li>
											 <li><span>订单号 ：</span><?php echo ($vo["order_num"]); ?></li>
											 <li><span>下单时间：</span><?php echo ($vo["create_time"]); ?></li>
											 <li><span><?php echo ($vo["title"]); ?></span></li>
											 <li style="margin-top: 10px"><span style="color:white;font-size:14px;padding: 5px;background-color:#c5862b;">去</span>&nbsp;&nbsp;<?php echo ($vo["create_time"]); ?></li>
											 <li style="margin-top: 10px;"><span style="color:white;padding: 5px;font-size:14px;background-color: #67b1e4;">返</span>&nbsp;&nbsp;<?php echo ($vo["create_time"]); ?></li>
											 <li><span>单价：</span><em>￥<?php echo ($vo["price"]); ?></em></li>
											 <li><span>总价：</span><em>￥<?php echo ($vo["total_price"]); ?></em></li>
											 <li><span>订单状态：</span><?php echo ($vo["state"]); ?>&nbsp;&nbsp;</li>
											 <li><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($vo["pay_state"] != 2): ?><!--<a href="javascript:void(0);" class="cancel_order" data-order_id="<?php echo ($vo["id"]); ?>">取消订单</a>--><?php endif; ?></span><?php if($vo["pay_state"] == 0): ?><span style="width: 90%;text-align: right;display: block;"><a href="<?php echo U('/extend/orderDetail');?>/id/<?php echo ($vo["id"]); ?>">查看详情</a><!--<a href="<?php echo U('Member/onlinePay','id='.$v[id]);?>" style="color:white;padding: 5px;background-color: darkorange;">立即付款</a>--></span><?php endif; ?></li>
				                        </ul>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div>
						</div>
					</div>
					<div id="item3mobile" class="mui-slider-item mui-control-content">
						<div class="mui-scroll-wrapper">
							<div class="mui-scroll">
								<ul class="mui-table-view">
									<?php if(is_array($cancelList)): $i = 0; $__LIST__ = $cancelList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell">
										<ul class="mui-table-view">
				                            <li><span style="font-size: 16px;"><?php echo ($vo["type"]); ?></span></li>
											 <li><span>订单号 ：</span><?php echo ($vo["order_num"]); ?></li>
											 <li><span>下单时间：</span><?php echo ($vo["create_time"]); ?></li>
											 <li><span><?php echo ($vo["title"]); ?></span></li>
											 <li style="margin-top: 10px"><span style="color:white;font-size:14px;padding: 5px;background-color:#c5862b;">去</span>&nbsp;&nbsp;<?php echo ($vo["create_time"]); ?></li>
											 <li style="margin-top: 10px;"><span style="color:white;padding: 5px;font-size:14px;background-color: #67b1e4;">返</span>&nbsp;&nbsp;<?php echo ($vo["create_time"]); ?></li>
											 <li><span>单价：</span><em>￥<?php echo ($vo["price"]); ?></em></li>
											 <li><span>总价：</span><em>￥<?php echo ($vo["total_price"]); ?></em></li>
											 <li><span>订单状态：</span><?php echo ($vo["state"]); ?>&nbsp;&nbsp;</li>
											 <li><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($vo["pay_state"] != 2): ?><!--<a href="javascript:void(0);" class="cancel_order" data-order_id="<?php echo ($vo["id"]); ?>">取消订单</a>--><?php endif; ?></span><?php if($vo["pay_state"] == 2): ?><span style="width: 90%;text-align: right;display: block;"><a href="<?php echo U('/extend/orderDetail');?>/id/<?php echo ($vo["id"]); ?>">查看详情</a><!--<a href="<?php echo U('Member/onlinePay','id='.$v[id]);?>" style="color:white;padding: 5px;background-color: darkorange;">立即付款</a>--></span><?php endif; ?></li>
				                        </ul>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div>
						</div>
					</div>
					<!--<div id="item4mobile" class="mui-slider-item mui-control-content">
						<div class="mui-scroll-wrapper">
							<div class="mui-scroll">
								<ul class="mui-table-view">
									<li class="mui-table-view-cell">
										第4个选项卡子项-1
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-2
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-3
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-4
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-5
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-6
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-7
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-8
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-9
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-10
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-11
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-12
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-13
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-14
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-15
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-16
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-17
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-18
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-19
									</li>
									<li class="mui-table-view-cell">
										第4个选项卡子项-20
									</li>
								</ul>
							</div>
						</div>
					</div>-->
					<!--<div id="item5mobile" class="mui-slider-item mui-control-content">
						<div class="mui-scroll-wrapper">
							<div class="mui-scroll">
								<ul class="mui-table-view">
									<li class="mui-table-view-cell">
										第5个选项卡子项-1
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-2
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-3
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-4
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-5
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-6
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-7
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-8
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-9
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-10
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-11
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-12
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-13
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-14
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-15
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-16
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-17
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-18
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-19
									</li>
									<li class="mui-table-view-cell">
										第5个选项卡子项-20
									</li>
								</ul>
							</div>
						</div>
					</div>-->
					<!--<div id="item6mobile" class="mui-slider-item mui-control-content">
						<div class="mui-scroll-wrapper">
							<div class="mui-scroll">
								<ul class="mui-table-view">
									<li class="mui-table-view-cell">
										第6个选项卡子项-1
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-2
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-3
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-4
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-5
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-6
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-7
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-8
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-9
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-10
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-11
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-12
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-13
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-14
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-15
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-16
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-17
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-18
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-19
									</li>
									<li class="mui-table-view-cell">
										第6个选项卡子项-20
									</li>
								</ul>
							</div>
						</div>
					</div>-->
				</div>
			</div>
			<input type="hidden" value="" id="datares"/>
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
		<script src="__PUBLIC__/js/mui.min.js"></script>
		<script src="__PUBLIC__/js/mui.pullToRefresh.js"></script>
		<script src="__PUBLIC__/js/mui.pullToRefresh.material.js"></script>
		<script>
			$(function(){
				//取消订单
				$('.cancel_order').click(function(){alert(111);
					var id = $(this).data('order_id');
					cancelOrder(id);
				})
				var cancelOrder = function(id){
					$.ajax({
			//				dataType:'json',
						type:'post',
						url:"<?php echo U('/Member/cancelOrder');?>",
						data:{'id':id},
			
						success:function(res){
							var data=(eval("("+res+")"));
							if(data.status==1){
								location.reload();
							}else{
								mui.alert(data.msg);
			//						App_gmuMsg(data.msg);
								return false;
							}
						}
			
					});
				}
				
			});
		</script>
		<script>
			var page=0;
			var result =[];
			mui.init();
			(function($) {
				mui('#item1mobile,#item2mobile,#item3mobile').on('tap','a',function(){document.location.href=this.href;});  

				
				//阻尼系数
				var deceleration = mui.os.ios?0.003:0.0009;
				$('.mui-scroll-wrapper').scroll({
					bounce: false,
					indicators: true, //是否显示滚动条
					deceleration:deceleration
				});
				
				
				
				
				$.ready(function() {
					//循环初始化所有下拉刷新，上拉加载。
					$.each(document.querySelectorAll('.mui-slider-group .mui-scroll'), function(index, pullRefreshEl) {
						$(pullRefreshEl).pullToRefresh({
//							down: {
//								callback: function() {
//									var self = this;
//									setTimeout(function() {
//										var ul = self.element.querySelector('.mui-table-view');
//										ul.insertBefore(createFragment(ul, index, 10, true), ul.firstChild);
//										self.endPullDownToRefresh();
//									}, 1000);
//								}
//							},
							up: {
								callback: function() {
									var self = this;
									setTimeout(function() {
										var ul = self.element.querySelector('.mui-table-view');
										ul.appendChild(createFragment(ul, index, 5));
										self.endPullUpToRefresh();
									}, 1000);
								}
							}
						});
					});
					var createFragment = function(ul, index, count, reverse) {
						var length = ul.querySelectorAll('li').length;
						var fragment = document.createDocumentFragment();
						var li;
						page++;
//						var data = getData(page,index);
//						var data=JSON.parse(res);
						mui.each(data,function(k,v){
								var li = document.createElement('li');
								li.className = 'mui-table-view-cell';
								li.innerHTML = '<a href="<?php echo U("freetour/detail");?>/id/'+11+'" class="c01">'+		
//								'<div class="ft-tj-d">'+
//								'<div style="float: left;margin: 5px;font-size: 1.2em;width:98%;">'+
//								'<font color="#7A7A7A"><B>'+ v.title+'</B></font><br><font color="#EE2C2C">现价:'+v.price+'RMB</font>&nbsp;&nbsp;&nbsp;<font color="#C5C1AA"><del>原价:'+ v.old_price+'RMB</del></font>'+ '<b class="liji">立即预订</b>'+
//								'</div>'+
//								'</div>'+
//								'</a>';
//var li = document.createElement('li');
//							li.className = 'mui-table-view-cell';
//							li.innerHTML = '第个选项卡子项-';
							fragment.appendChild(li);
							});
						return fragment;
					};
				});
			})(mui);
			
			var getData=function(page,type){
				mui.ajax("<?php echo U('/Extend/ajaxOrderList');?>",
					{type: "POST",
					data: {'page':page,'type':type},
					success: function(res){						
						var data=(eval("("+res+")"));
						if(data.status){
							document.querySelector('#datares').value=data;
						}else{
							document.querySelector('#datares').value='';
						}
					}
				});
				var datas = document.querySelector('#datares').value;
				return datas;
			}
		</script>
	</body>

</html>