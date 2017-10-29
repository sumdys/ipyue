<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" action="__URL__/index" method="post">
    <input type="hidden" name="pageNum" value="<?php echo (($_REQUEST['pageNum'])?($_REQUEST['pageNum']):1); ?>"/>
    <input type="hidden" name="numPerPage" value="<?php echo ($numPerPage); ?>"/>
    <input type="hidden" name="_order" value="<?php echo ($_REQUEST['_order']); ?>"/>
    <input type="hidden" name="_sort" value="<?php echo (($_REQUEST['_sort'])?($_REQUEST['_sort']):'1'); ?>"/>
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST['listRows']); ?>"/>
    <?php if(is_array($map)): $i = 0; $__LIST__ = $map;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?><input type="hidden" name="<?php echo ($key); ?>" value="<?php echo ($_REQUEST[$key]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
</form>


    <div class="pageHeader">
        <form onsubmit="return navTabSearch(this);" action="__URL__" method="post">
            <div class="searchBar">
                <ul class="searchContent">
                    <li>
                        <input type="text" name="title" value="<?php echo ($_REQUEST["title"]); ?>" class="medium" placeholder="联系人/联系电话" >
                    </li>
                    <li>
                        <label>状态：</label>
                        <select class="combox" name="status">
                            <option value="">请选择</option>
                            <option value="1" <?php if(($_REQUEST["status"]) == "1"): ?>selected=selected<?php endif; ?>>已审核</option>
                            <option value="0" <?php if(($_REQUEST["status"]) == "0"): ?>selected=selected<?php endif; ?>>未审核</option>
                        </select>
                    </li>
                    <li>
                        <label>审请日期：</label>
                        <input type="text" class="date textInput valid"  name="start_date" value="<?php echo ($_REQUEST["start_date"]); ?>">
                        <a class="inputDateButton" href="javascript:;">选择</a> &nbsp;&nbsp;<span style="float: left;margin-top: 5px;">至</span><input type="text" class="date textInput valid"  name="end_date" value="<?php echo ($_REQUEST["end_date"]); ?>">
                        <a class="inputDateButton" href="javascript:;">选择</a> &nbsp;&nbsp;
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
                <!--<li><a class="add" href="__URL__/add" target="dialog" mask="true" width="890" height="550"><span>新增</span></a></li>-->
                <!--<li><a class="delete" href="__URL__/foreverdelete/id/{sid_node}/navTabId/__MODULE__-index" target="ajaxTodo"  title="你确定要删除吗？" warn="请选择节点"><span>删除</span></a></li>-->
                <!--<li><a class="edit" href="__URL__/edit/id/{sid_node}" target="dialog" mask="true" warn="请选择节点"  width="890" height="550"><span>修改</span></a></li>-->
                <!--<li><a title="确实要删除这些记录吗?" target="selectedTodo" target="dialog" rel="ids[]" href="__URL__/delAll" class="delete" ><span>批量删除</span></a></li>-->
            </ul>
        </div>
        <table class="list" width="100%" layoutH="116">
            <thead>
            <tr>
                <th width="20"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th width="100">店铺名称</th>
                <th width="50">联系人</th>
                <th width="50" >联系电话</th>
                <th width="80" >申请时间</th>
                <th width="100">操作员</th>
                <th width="100">状态</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>


            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_node" rel="<?php echo ($vo['id']); ?>">
                    <td><input name="ids[]" value="<?php echo ($vo['id']); ?>" type="checkbox">:<?php echo ($vo['id']); ?></td>
                    <td><?php echo ($vo['shop_name']); ?></td>
                    <td><?php echo ($vo["cantent_name"]); ?></td>
                    <td><?php echo ($vo["cantent_mobile"]); ?></td>
                    <td><?php echo ($vo["create_time"]); ?></td>
                    <td><?php echo ($vo["applictioner"]); ?></td>
                    <td>
                        <?php switch($vo["status"]): case "0": ?>未审核<?php break;?>
                            <?php case "1": ?>通过<?php break;?>
                            <?php case "2": ?>不通过<?php break; endswitch;?>
                    </td>
                    <td>
                        <?php if($vo["status"] < 1): ?><a href="__URL__/changeStatus/status/1/id/<?php echo ($vo["id"]); ?>/navTabId/__MODULE__-index" target="navTabTodo" title="你确定要审核通过吗？">通过</a>
                        <!--<a href="__URL__/edit/id/<?php echo ($vo['id']); ?>" target="dialog" rel="edit" width="890" height="400">编辑</a>-->
                        <a href="__URL__/changeStatus/status/2/id/<?php echo ($vo['id']); ?>/navTabId/__MODULE__-index" target="navTabTodo" title="你确定要审核不通过吗？">不通过</a><?php endif; ?>
                    </td>
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
                    <option value="30" <?php if(($numPerPage) == "30"): ?>selected=selected<?php endif; ?>>30</option>
                </select>
                <span>共<?php echo ($totalCount); ?>条</span>

            </div>
            <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
        </div>
    </div>