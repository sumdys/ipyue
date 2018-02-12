<?php if (!defined('THINK_PATH')) exit();?>﻿<form id="pagerForm" action="__URL__" method="post">
    <input type="hidden" name="pageNum" value="<?php echo (($_REQUEST['pageNum'])?($_REQUEST['pageNum']):1); ?>"/>
    <input type="hidden" name="numPerPage" value="<?php echo ($numPerPage); ?>"/>
    <input type="hidden" name="_order" value="<?php echo ($_REQUEST['_order']); ?>"/>
    <input type="hidden" name="_sort" value="<?php echo (($_REQUEST['_sort'])?($_REQUEST['_sort']):'1'); ?>"/>
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST['listRows']); ?>"/>
    <?php if(is_array($map)): $i = 0; $__LIST__ = $map;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?><input type="hidden" name="<?php echo ($key); ?>" value="<?php echo ($_REQUEST[$key]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
</form>


<div class="page">
    <div class="pageHeader">
        <form onsubmit="return navTabSearch(this);" action="__URL__" method="post">
            <div class="searchBar">
                <ul class="searchContent">
                    <li>
                        <label>文章标题：</label>
                        <input type="text" name="title" value="<?php echo ($_REQUEST["title"]); ?>" class="medium" >
                    </li>
                    <li>
                        <label>状态：</label>
                        <select class="combox" name="status">
                            <option value="">请选择</option>
                            <option value="1" <?php if(($_REQUEST["status"]) == "1"): ?>selected=selected<?php endif; ?>>已审核</option>
                            <option value="0" <?php if(($_REQUEST["status"]) == "0"): ?>selected=selected<?php endif; ?>>未审核</option>
                        </select>
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
                <li><a class="add" href="__URL__/add" target="dialog" mask="true" width="890" height="550"><span>新增</span></a></li>
                <li><a class="delete" href="__URL__/foreverdelete/id/{sid_node}/navTabId/__MODULE__-index" target="ajaxTodo"  title="你确定要删除吗？" warn="请选择节点"><span>删除</span></a></li>
                <li><a class="edit" href="__URL__/edit/id/{sid_node}" target="dialog" mask="true" warn="请选择节点"  width="890" height="550"><span>修改</span></a></li>
                <li><a title="确实要删除这些记录吗?" target="selectedTodo" target="dialog" rel="ids[]" href="__URL__/delAll" class="delete" ><span>批量删除</span></a></li>
            </ul>
        </div>
        <table class="list" width="100%" layoutH="116">
            <thead>
            <tr>
                <th width="20"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th width="300">标题</th>
                <th width="50">出发城市</th>
                <th width="50" >目地城市</th>
                <th width="80" >发布日期</th>
                <th width="40">排序</th>
                <th width="100">操作员</th>
                <th width="100">状态</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>


            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_node" rel="<?php echo ($vo['id']); ?>">
                    <td><input name="ids[]" value="<?php echo ($vo['id']); ?>" type="checkbox">:<?php echo ($vo['id']); ?></td>
                    <td><?php echo ($vo['title']); ?></td>
                    <td><?php echo ($vo["dcity_name"]); ?></td>
                    <td><?php echo ($vo["acity_name"]); ?></td>
                    <td><?php echo (date('Y-m-d H:i:s',$vo['create_time'])); ?></td>
                    <td><?php echo ($vo['sorts']); ?></td>
                    <td><?php echo ($vo['update_uid']); ?></td>
                    <td><?php echo (getstatus($vo['status'])); ?></td>

                    <td>
                        <a href="__URL__/activity/id/<?php echo ($vo['id']); ?>" target="dialog" rel="activity" width="330" height="200">限购</a>
                        <a href="__URL__/edit/id/<?php echo ($vo['id']); ?>" target="dialog" rel="edit" width="890" height="550">编辑</a>
                        <a href="__URL__/delete/id/<?php echo ($vo['id']); ?>/navTabId/__MODULE__-index" target="navTabTodo" title="你确定要删除吗？">删除</a></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>

            </tbody>
        </table>
        <div class="panelBar">
            <div class="pages">
                <span>显示</span>
                <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                    <option value="5" <?php if(($numPerPage) == "5"): ?>selected=selected<?php endif; ?>>5</option>
                    <option value="10" <?php if(($numPerPage) == "10"): ?>selected=selected<?php endif; ?>>10</option>
                    <option value="15" <?php if(($numPerPage) == "15"): ?>selected=selected<?php endif; ?>>15</option>
                    <option value="20" <?php if(($numPerPage) == "20"): ?>selected=selected<?php endif; ?>>20</option>
                </select>
                <span>共<?php echo ($totalCount); ?>条</span>

            </div>
            <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
        </div>
    </div>
</div>