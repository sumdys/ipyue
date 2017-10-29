<?php if (!defined('THINK_PATH')) exit();?>﻿<form id="pagerForm" action="__URL__/payOrder" method="post">
    <input type="hidden" name="pageNum" value="<?php echo (($_REQUEST['pageNum'])?($_REQUEST['pageNum']):1); ?>"/>
    <input type="hidden" name="numPerPage" value="<?php echo ($numPerPage); ?>"/>
    <input type="hidden" name="so" value="<?php echo ($_REQUEST["so"]); ?>" />
    <input type="hidden" name="_order" value="<?php echo ($_REQUEST["_order"]); ?>"/>
    <input type="hidden" name="_sort" value="<?php echo (($_REQUEST['_sort'])?($_REQUEST['_sort']):'1'); ?>"/>
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST['listRows']); ?>"/>
    <input type="hidden" name="so_date1" value="<?php echo ($_REQUEST["so_date1"]); ?>" />
    <input type="hidden" name="so_date2" value="<?php echo ($_REQUEST["so_date2"]); ?>" />

    <?php if(is_array($map)): $i = 0; $__LIST__ = $map;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?><input type="hidden" name="<?php echo ($key); ?>" value="<?php echo ($_REQUEST[$key]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
</form>

<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="__URL__/payOrder" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>搜索：</label>
                    <input type="text" name="so" value="<?php echo ($_REQUEST["so"]); ?>"/>
                </li>
                <li>
                    <label>状态：</label>
                    <select class="combox" name="status">
                        <option value="">请选择</option>
                        <option value="0" <?php if(($_REQUEST["status"]) == "0"): ?>selected=selected<?php endif; ?>>未支付</option>
                        <option value="1" <?php if(($_REQUEST["status"]) == "1"): ?>selected=selected<?php endif; ?>>已支付</option>
                    </select>
                </li>
                <li>
                    <label>日期：</label>
                    <input type="text" class="date textInput valid"  name="so_date1" value="<?php echo ($_REQUEST["so_date1"]); ?>">
                    <a class="inputDateButton" href="javascript:;">选择</a> &nbsp;&nbsp;-
                </li>
                <li>
                    <input type="text" class="date textInput valid"  name="so_date2" value="<?php echo ($_REQUEST["so_date2"]); ?>">
                    <a class="inputDateButton" href="javascript:;">选择</a>
                </li>
            </ul>
            <div class="subBar">
                <ul>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li>
                </ul>
            </div>
        </div>
    </form>
</div>

<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">

        </ul>
	</div>

	<table class="table" width="100%" layoutH="115">
		<thead>
		<tr>
			<th width="150" orderField="id" <?php if($_REQUEST["_order"] == 'id'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>编号</th>
			<th width="100" orderField="member_id" <?php if($_REQUEST["_order"] == 'member_id'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>会员名</th>
            <th width="100">订单号</th>
            <th orderField="route" <?php if($_REQUEST["_order"] == 'route'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>行程</th>
            <th width="100">联系人</th>
            <th width="100">手机</th>
			<th width="100" orderField="order_price" <?php if($_REQUEST["_order"] == 'order_price'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>是>金额</th>
			<th width="80" orderField="create_time" <?php if($_REQUEST["_order"] == 'create_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>创建时间</th>
            <!--<th width="80" orderField="update_time" <?php if($_REQUEST["_order"] == 'update_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>更新时间</th>-->
			<th width="80" orderField="status" <?php if($_REQUEST["_order"] == 'status'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>支付状态</th>
            <th width="80">操作</th>
		</tr>
		</thead>
		<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_user" rel="<?php echo ($vo['id']); ?>">
				<td><?php echo ($vo['id']); ?></td>
                <td><a title="<?php echo ($vo['member_id']); ?>"><?php echo ($vo['member_id']); ?></a></td>
                <td><?php echo ($vo['order_num']); ?></td>
				<td><?php echo ($vo['title']); ?></td>
                <td><?php echo ($vo['linkman']); ?></td>
                <td><?php echo ($vo['mobile']); ?></td>
				<td><?php echo ($vo['total_price']); ?></td>

				<td><?php echo (date("Y-m-d",$vo['create_time'])); ?></td>
				<!--<td><?php echo (date("Y-m-d H:i:s",$vo['update_time'])); ?></td>-->

                <td>已支付</td>
                <td><a href="<?php echo ($vo["payUrl"]); ?>" target="_blank" rel="edit">支付连接</a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>

	<div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="10" <?php if(($numPerPage) == "10"): ?>selected=selected<?php endif; ?>>10</option>
                <option value="20" <?php if(($numPerPage) == "20"): ?>selected=selected<?php endif; ?>>20</option>
                <option value="30" <?php if(($numPerPage) == "35"): ?>selected=selected<?php endif; ?>>30</option>
                <option value="50" <?php if(($numPerPage) == "50"): ?>selected=selected<?php endif; ?>>50</option>
            </select>
            <span>共<?php echo ($totalCount); ?>条</span>
        </div>
		<div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
	</div>

</div>