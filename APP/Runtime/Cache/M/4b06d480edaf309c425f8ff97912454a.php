<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="__PUBLIC__/css/mui.min.css" rel="stylesheet" />
		<link href="__PUBLIC__/css/bootstrap.min.css" rel="stylesheet" />
		<style>
			body{font-size: 12px !important;}
		</style>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">佣金明细</h1>
		</header>
		<div style="padding-top: 45px;">
			<form class="mui-input-group">
			    <div class="mui-input-row">
			        <input type="text" style="width: 30%;" placeholder="时间">
			        <input type="text" style="width: 54%;" placeholder="请输入订单号">
					<button type="button" class="mui-btn mui-btn-primary">搜索</button>
			    </div>
			</form>
		</div>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><table class="table table-bordered table-striped mui-row">
			<thead>
				<tr>
					<th>订单号</th>
					<th colspan="4"><?php echo ($vo["order_num"]); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>日期</td>
					<td>订单金额</td>
					<td>佣金比例</td>
					<td>佣金</td>
					<td>退款记录</td>
				</tr>
				<tr>
					<td><?php echo ($vo["create_time"]); ?></td>
					<td><?php echo ($vo["total_price"]); ?></td>
					<td><?php echo ($vo["brokerage_rate"]); ?>%</td>
					<td><?php echo ($vo["brokerage"]); ?></td>
					<td></td>
				</tr>
			</tbody>
		</table><?php endforeach; endif; else: echo "" ;endif; ?>
		<nav class="mui-bar mui-bar-tab">
		    <a class="mui-tab-item mui-active" href="#">
		        订单金额总计：<?php echo ($order_money); ?>
		    </a>
		    
		    <a class="mui-tab-item mui-active" href="#">
		        佣金金额总计：<?php echo ($brokerage_money); ?>
		    </a>
		    
		</nav>
		<script src="__PUBLIC__/js/mui.min.js"></script>
		<script type="text/javascript">
			mui.init()
		</script>
	</body>

</html>