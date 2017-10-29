<?php if (!defined('THINK_PATH')) exit();?><!----------------
******新客户名单
----------------->

<style type="text/css">	
	.lib_Menubox {height:28px;line-height:28px;position:relative;}
	.lib_Menubox ul{margin:0px;padding:0px;list-style:none; position:absolute; top:3px; left:0; margin-left:10px; height:25px;text-align:center;}
	.lib_Menubox li{float:left;display:block;cursor:pointer;width:114px;color:#949694;font-weight:bold; margin-right:2px;height:25px;line-height:25px; background-color:#E4F2FD}
	.lib_Menubox li.hover{padding:0px;background:#fff;width:116px;color:#739242;height:25px;line-height:25px;}
	.lib_Contentbox{clear:both;margin-top:0px; height:181px; text-align:center;padding-top:8px;}
	td{text-align:center!important;}
	.subBar a {margin-left:20px;}
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
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>搜索：</label><input type="text" name="so" value="<?php echo ($_REQUEST["so"]); ?>" placeholder ="姓名、手机或QQ号"/></li>
            </ul>
            <div class="subBar">
                <ul><li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li></ul>
            </div>
        </div>
    </form>
    <?php if(($type == 0)or($type == 2)): ?><ul class="subBar"> 
    	<li>
        	<a class="edit" href="__URL__/daoru" height="450" width="650" target="dialog" mask="true"><input type="button" value="导入新客户" /></a>        	
            <a class="edit" href="__URL__/fenfa" height="440" width="340" target="dialog" mask="true"><input type="button" value="分发新客户" /></a>
            <a class="delete" href="__URL__/foreverdelete/id/{sid_user}/navTabId/__MODULE__-index" target="ajaxTodo" title="你确定要删除吗？" warn="请选择用户"><input type="button" value="删除" /></a>
            <a title="确实要删除这些记录吗?" target="selectedTodo" target="dialog" rel="ids[]" href="__URL__/delAll" class="delete" ><input type="button" value="批量删除" /></a>
        </li> 
	</ul><?php endif; ?>    
</div>


<div class="pageContent">
    <div class="lib_Menubox lib_tabborder">
        <ul>
           <li class="hover">新客户名单<span style="color:#999;">（<?php echo ($totle['newORold']); ?>）</span></li>
           <li><a href="<?php echo U('/Meile_admin/Sales/appointment');?>" target="navTab" rel="Sales-index" style="color:#06F;">预约名单</a><span style="color:#999;">（<?php echo ($totle['appointment']); ?>）</span></li>
            <?php if(($type == 0)or($type == 2)): ?><li><a href="<?php echo U('/Meile_admin/Sales/audit');?>" target="navTab" rel="Sales-index" style="color:#06F;">待审核名单</a><span style="color:#999;">（<?php echo ($totle['audit']); ?>）</span></li><?php endif; ?>
           <li><a href="<?php echo U('/Meile_admin/Sales/audit_yes');?>" target="navTab" rel="Sales-index" style="color:#06F;">审核通过名单</a><span style="color:#999;">（<?php echo ($totle['auditYes']); ?>）</span></li>
           <li><a href="<?php echo U('/Meile_admin/Sales/no_need');?>" target="navTab" rel="Sales-index" style="color:#06F;">不需要名单</a><span style="color:#999;">（<?php echo ($totle['no_need']); ?>）</span></li>
           <li><a href="<?php echo U('/Meile_admin/Sales/invalid');?>" target="navTab" rel="Sales-index" style="color:#06F;">无效名单</a><span style="color:#999;">（<?php echo ($totle['invalid']); ?>）</span></li>
        </ul>
    </div>
    	<form action="__URL__/save" method="post" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <table width="100%" layoutH="115" class="table">
            <thead>
                <tr>
               <?php if(($type == 0)or($type == 2)): ?><th width="60" style="text-align:center;"><input type="checkbox" group="ids[]" class="checkboxCtrl" style="vertical-align:middle;">全选/撤销</th><?php endif; ?>
                	<th width="60" style="text-align:center;">编号</th>          
                	<th width="50" style="text-align:center;">姓名</th>
                    <th width="70" style="text-align:center;">手机</th>
                    <th width="60"  style="text-align:center;">电话</th>
                    <th width="60"  style="text-align:center;">邮箱</th>
                    <th width="40"  style="text-align:center;">性别</th>
                    <th width="60"  style="text-align:center;">QQ号</th>
                    <th width="60"  style="text-align:center;">微信</th>
                    <th width="80"  style="text-align:center;">地址</th>
                    <th width="80"  style="text-align:center;" >备注</th>
                    <!---业务员才显示这一块-->
                    <?php if(($type == 1)or($type == 2)): ?><th width="190" style="text-align:center;">本次拨打情况</th> 
                        <th width="160" style="text-align:center;">设置重拨</th>  
                        <th width="90" style="text-align:center;">操作</th><?php endif; ?> 
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_user" rel="<?php echo ($vo['id']); ?>"> 
                   <?php if(($type == 0)or($type == 2)): ?><td><input name="ids[]" value="<?php echo ($vo['id']); ?>" type="checkbox"></td><?php endif; ?> 
                    	<td><?php echo ($vo["bh"]); ?></td>                    
                    	<td><?php echo ($vo["xm"]); ?></td>
                        <td><?php echo ($vo["mobi"]); ?></td>
                        <td><?php echo ($vo["tel"]); ?></td>
                        <td><?php echo ($vo["mail"]); ?></td>
                        <td><?php echo ($vo["sex"]); ?></td>
                        <td><?php echo ($vo["qq"]); ?></td>
                        <td><?php echo ($vo["wechat"]); ?></td>
                        <td><?php echo ($vo["address"]); ?></td>
                        <td><?php echo ($vo["remark"]); ?></td>
                        
                      <!---业务员才显示这一块-->
                      <?php if(($type == 1)or($type == 2)): ?><td class="select">
                            <select name="bigclass[<?php echo ($vo["id"]); ?>]">
                            	<option  selected></option>                               
                                <option value="接通">接通</option>
                                <option value="无人接听">无人接听</option>
                                <option value="无效">无效</option>
                            </select>
                            <select name="smallclass[<?php echo ($vo["id"]); ?>]">
                            	<option selected></option>
                                <option value="再联系" >再联系</option>
                                <option value="不需要" >不需要</option>
                                <option value="成功" >成功</option>
                            </select>      
                        </td> 
                        <td> 
                            <input type="text" size="10" class="date textInput valid"  name="date[<?php echo ($vo["id"]); ?>]">&nbsp;
                            <select name="time[<?php echo ($vo["id"]); ?>]"><option value=""></option><option value="上午">上午</option><option value="下午">下午</option></select>
                        </td>  
                        <td>
                        	<a class="edit" href="__URL__/edit?id=<?php echo ($vo["id"]); ?>" height="250" width="575" target="dialog" mask="true" title="编辑"><input type="button" value="编辑" /></a>
                           <input type="submit" value="提交"/>
                        </td><?php endif; ?>                                        
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>  
            </tbody>
        </table>
        </form>                     
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