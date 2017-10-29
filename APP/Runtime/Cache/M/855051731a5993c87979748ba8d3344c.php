<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>推广素材</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="__PUBLIC__/css/mui.min.css">
		<style>
			.inputs{height: 30px;line-height: 30px;padding: 5px;}
			.inputs .old_price{float:left;width: 40%;}
			.addExtend{float: right;}
			.mui-table-view-chevron .mui-table-view-cell{padding-right: 55px;}
		</style>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">推广素材</h1>
		</header>
		<!--下拉刷新容器-->
		<div id="pullrefresh" class="mui-content mui-scroll-wrapper">
			<div class="mui-scroll">
				<!--数据列表-->
				<ul class="mui-table-view mui-table-view-chevron">
					
				</ul>
			</div>
		</div>

	</body>
		<script type="text/javascript" src="__PUBLIC__/js/jquery-2.0.3.min.js"></script>		
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
					down: {
//						callback: pulldownRefresh
					},
					up: {
						height:10,
						contentrefresh: '正在加载...',
						callback: pullupRefresh
					}
				}
			});
			/**
			 * 下拉刷新具体业务实现
			 */
//			function pulldownRefresh() {
//				setTimeout(function() {
//					var table = document.body.querySelector('.mui-table-view');
//					var cells = document.body.querySelectorAll('.mui-table-view-cell');
//					for (var i = cells.length, len = i + 3; i < len; i++) {
//						var li = document.createElement('li');
//						li.className = 'mui-table-view-cell';
//						li.innerHTML = '<a class="mui-navigate-right">Item ' + (i + 1) + '</a>';
						//下拉刷新，新纪录插到最前面；
//						table.insertBefore(li, table.firstChild);
//					}
//					mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
//				}, 1500);
//			}
			var count = 0;
			/**
			 * 上拉加载具体业务实现
			 */
			function pullupRefresh() {
				page++;
				setTimeout(function() {
					mui('#pullrefresh').pullRefresh().endPullupToRefresh((++count > <?php echo ($countPage); ?>)); //参数为true代表没有更多数据了。
					var table = document.body.querySelector('.mui-table-view');
					var cells = document.body.querySelectorAll('.mui-table-view-cell');
					$.ajax({
						type:"get",
						url:"<?php echo U('Extend/ajaxList');?>",
						data:{'page':page,'type':type,'cid':cid},
						success:function(res){
							var data=(eval("("+res+")"));
							mui.each(data.contact, function(index,items) {
								var li = document.createElement('li');
								li.className = 'mui-table-view-cell';
								li.innerHTML = '<a href="<?php echo U("extend/detail");?>/id/'+items.id+'" class="mui-navigate-right">' + items.title + '</a><p class="inputs"><span class="old_price">原价:'+items.old_price+'</span><span>佣金:'+items.brokerage+'</span><button type="button" class="mui-btn mui-btn-primary addExtend" data-id="'+items.id+'">加入推广</button></p>';
								table.appendChild(li);
							});	
						}
					});
										
				}, 1500);
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
	<script type="text/javascript">
		$(function(){
			 mui('body').on('tap','a',function(){
				 window.location.href=this.href;
			 });
			//加入推广
			mui('body').on('tap','button',function(){
				var id = this.getAttribute('data-id');
				$.ajax({
					type:"POST",
					url:"<?php echo U('Extend/addExtend');?>",
					data:{freetour_id:id},
					success:function(res){
						var data=(eval("("+res+")"));
						mui.alert(data.info);
					}
				});

			});

		});
	</script>
</html>