<?php if (!defined('THINK_PATH')) exit();?>﻿<!----------------
******待支付订单
----------------->
<style type="text/css">	
	.lib_Menubox {height:28px;line-height:28px;position:relative;}
	.lib_Menubox ul{margin:0px;padding:0px;list-style:none; position:absolute; top:3px; left:0; margin-left:10px; height:25px;text-align:center;}
	.lib_Menubox li{float:left;display:block;cursor:pointer;width:114px;color:#949694;font-weight:bold; margin-right:2px;height:25px;line-height:25px; background-color:#E4F2FD}
	.lib_Menubox li.hover{padding:0px;background:#fff;width:116px;color:#739242;height:25px;line-height:25px;}
	.lib_Contentbox{clear:both;margin-top:0px; height:181px; text-align:center;padding-top:8px;}
	.ulclass li{float:right; margin-right:20px;}
</style>

<form id="pagerForm" action="__URL__/index" method="post">
    <input type="hidden" name="pageNum" value="<?php echo (($_REQUEST['pageNum'])?($_REQUEST['pageNum']):1); ?>"/>
    <input type="hidden" name="numPerPage" value="<?php echo ($numPerPage); ?>"/>
    <input type="hidden" name="so" value="<?php echo ($_REQUEST["so"]); ?>" />
    <input type="hidden" name="_order" value="<?php echo ($_REQUEST["_order"]); ?>"/>
    <input type="hidden" name="_sort" value="<?php echo (($_REQUEST['_sort'])?($_REQUEST['_sort']):'1'); ?>"/>
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST['listRows']); ?>"/>
    <?php if(is_array($map)): $i = 0; $__LIST__ = $map;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?><input type="hidden" name="<?php echo ($key); ?>" value="<?php echo ($_REQUEST[$key]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="__URL__/index" method="post">
        <div class="searchBar" style="float:left;">
            <ul class="searchContent">
                <li><label>搜索：</label><input type="text" name="so" value="<?php echo ($_REQUEST["so"]); ?>" placeholder ="搜索会员ID、订单ID"/></li>
            </ul>
            <div class="subBar">
                <ul><li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li></ul>
            </div>
        </div>
    </form>
     <ul class="ulclass">      
        <li><a class="icon" href="__URL__/daochu" height="450" width="650" target="dialog" mask="true"><input type="button" value="导出订单" style="width:80px;"/></a></li>
        <li><a class="edit" href="__URL__/daoru" height="450" width="650" target="dialog" mask="true"><input type="button" value="导入订单" style="width:80px;"/></a></li>
    </ul>   
</div>

<div class="pageContent">
    <div class="lib_Menubox lib_tabborder">
        <ul>
           <li class="hover">待付款订单</li>
           <li><a href="<?php echo U('/Meile_admin/Order/index2');?>" target="navTab" rel="Order-index" style="color:#06F;">已付款订单</a></li>
           <li><a href="<?php echo U('/Meile_admin/Order/index3');?>" target="navTab" rel="Order-index" style="color:#06F;">已取消订单</a></li>
        </ul>
    </div>
        <table class="table" width="100%" layoutH="115">
            <thead>
                <tr>    
                	<th width="25" style="text-align:center;">编号</th>          
                	<th width="30" style="text-align:center;">会员ID</th>
                    <th width="100" style="text-align:center;">订单ID</th>
                    <th width="100"  style="text-align:center;">路线名称</th>
                    <!--<th width="240"  style="text-align:center;">航班航程</th>-->
                    <th width="60"  style="text-align:center;">价格（含税）</th>
                    <th width="40"  style="text-align:center;">现金券</th>
                    <th width="60"  style="text-align:center;">应付价格</th>
                    <th width="60"  style="text-align:center;">联系人</th>
                    <th width="60"  style="text-align:center;">联系电话</th>
                    <th width="60"  style="text-align:center;">出发时间</th>
                    <th width="60"  style="text-align:center;">下单时间</th>
                    <th width="60"  style="text-align:center;">订单来源</th>
                    <th width="60"  style="text-align:center;" >订单状态</th>
                    <th width="120"  style="text-align:center;">操作</th>  
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_user" rel=""> 
                    	<td style="text-align:center;"><?php echo ($i); ?></td>                    
                    	<td style="text-align:center;"><?php echo ($vo["member_id"]); ?></td>
                        <td style="text-align:center;"><?php echo ($vo["order_num"]); ?></td>
                       <td style="text-align:center;"><?php echo ($vo["title"]); ?></td>
                        <!--<td><?php echo ($vo["hc_n"]); ?>&nbsp;&nbsp;&nbsp;<span style="color:#09F;">（<?php echo ($vo["jp_type"]); ?>）</span></td>-->
                        <td style="text-align:center;">￥<?php echo ($vo["total_price"]); ?></td>
                        <td style="text-align:center;">无</td>
                        <td style="text-align:center;">￥<?php echo ($vo["total_price"]); ?></td>
                        <td style="text-align:center;"><?php echo ($vo['linkman']); ?></td>
                        <td style="text-align:center;"><?php echo ($vo['mobile']); ?></td>
                        <td style="text-align:center;"><?php echo (date("Y-m-d",$vo["start_date"])); ?></td>
                        <td style="text-align:center;"><?php echo (date("Y-m-d",$vo["create_time"])); ?></td>
                        <td style="text-align:center;"><?php echo ($vo["channel_type"]); ?></td>
                        <td style="text-align:center;">待付款</td>
                        <td>
                        <a href="__URL__/order_view/id/<?php echo ($vo["id"]); ?>" target="dialog" rel="edit" width="800" height="488"style="color:#0CF;">查看</a>
                        &nbsp;&nbsp;&nbsp;
                        <?php if($user_type == 'can'): ?><a style="color:#ccc;">编辑</a>&nbsp;&nbsp;&nbsp;<a style="color:#ccc;">取消订单</a>
                        <?php else: ?>
                            <a href="__URL__/order_edit/id/<?php echo ($vo["id"]); ?>" target="dialog" rel="edit" style="color:#0CF;"  width="1150" height="450">编辑</a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="__URL__/order_cancel/id/<?php echo ($vo["id"]); ?>" target="dialog" rel="edit" style="color:#0CF;"  width="360" height="150">取消订单</a><?php endif; ?>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>  
            </tbody>
        </table>     

    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="10" <?php if(($numPerPage) == "10"): ?>selected=selected<?php endif; ?>>10</option>
                <option value="20" <?php if(($numPerPage) == "20"): ?>selected=selected<?php endif; ?>>20</option>
                <option value="30" <?php if(($numPerPage) == "30"): ?>selected=selected<?php endif; ?>>30</option>
                <option value="50" <?php if(($numPerPage) == "50"): ?>selected=selected<?php endif; ?>>50</option>
                <option value="100" <?php if(($numPerPage) == "100"): ?>selected=selected<?php endif; ?>>100</option>
            </select>
            <span>共<?php echo ($totalCount); ?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
    </div> 
    
</div><!---pageContent end-->