<?php if (!defined('THINK_PATH')) exit();?><!--
**
**登帐平台首页
**2014.4
**
-->
<style type="text/css">
.billing span{margin-left:5px;}
.ulclass li{float:right; margin-right:20px;}
</style>
<form id="pagerForm" action="__URL__/index" method="post">
    <input type="hidden" name="pageNum" value="<?php echo (($_REQUEST['pageNum'])?($_REQUEST['pageNum']):1); ?>"/>
    <input type="hidden" name="numPerPage" value="<?php echo ($numPerPage); ?>"/>    
    <input type="hidden" name="_order" value="<?php echo ($_REQUEST["_order"]); ?>"/>
    <input type="hidden" name="_sort" value="<?php echo (($_REQUEST['_sort'])?($_REQUEST['_sort']):'1'); ?>"/>
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST['listRows']); ?>"/>
    <input type="hidden" name="book_id" value="<?php echo ($_REQUEST["book_id"]); ?>" />
    <input type="hidden" name="so" value="<?php echo ($_REQUEST["so"]); ?>" />
    <input type="hidden" name="so_date1" value="<?php echo ($_REQUEST["so_date1"]); ?>" />
    <input type="hidden" name="so_date2" value="<?php echo ($_REQUEST["so_date2"]); ?>" />
    <?php if(is_array($map)): $i = 0; $__LIST__ = $map;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?><input type="hidden" name="<?php echo ($key); ?>" value="<?php echo ($_REQUEST[$key]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="__URL__/index" method="post">
        <div class="searchBar" style="float:left;">
            <ul class="searchContent">
                <li><label>搜索：</label><input type="text" name="so" value="<?php echo ($_REQUEST["so"]); ?>" placeholder ="搜索出票地"/></li>
            </ul>
            <div class="subBar">
                <ul><li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li></ul>
            </div>
        </div>
    </form>
     <ul class="ulclass">      
        <li><a class="icon" href="__URL__/daochu" height="450" width="650" target="dialog" mask="true"><input type="button" value="导出账单" style="width:80px;"/></a></li>
        <li><a class="edit" href="__URL__/daoru"  height="450" width="650" target="dialog" mask="true"><input type="button" value="导入账单" style="width:80px;"/></a></li>
        <li><a class="edit " href="__URL__/add"   height="500" width="680" target="dialog" mask="true" ><input type="button" value="新增账单" style="width:80px;"/></a></li>
    </ul>   
</div>


    <table class="table" width="101%" layoutH="115"  >
        <thead>
            <tr>
                 <th width="70">出票日期</th>
                 <th width="80">出票地</th>                                             
                 <th width="75">收入金额</th> 
                 <th width="85">到账银行</th>                  
                 <th width="80">抵票款</th> 
                 <th width="80">取票金额</th>
                  <th width="45">拿位地</th>
                 <th width="80">拿位费</th>            
                 <th width="80">刷卡费</th>
                 <th width="80">退票款</th>
                 <th width="80">返客费</th>
                 <th width="70">利润</th> 
                 <?php if(($type == 1)or($type == 2)or($type == 3)): ?><th width="70">盈利差额</th><?php endif; ?>   
                 <th width="45">承担人</th>
                 <th width="45">到账地</th>
                 <th width="85">备注</th> 
                 <th width="80">支付时间</th>
                 <th width="85">支付明细</th> 
                 <th width="85">退客明细</th> 
                 <th width="85">返客明细</th>
                 <th width="85">详情</th>                   
                 <th width="60">收款审核</th> 
                 <th width="60">付款审核</th> 
            </tr>
        </thead>
        <tbody>     
        	<?php if(is_array($data)): $k = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="billing">
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo (date("Y/m/d",$vo["cpdate"])); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>" title="<?php echo ($vo["cpd"]); ?>"><?php echo ($vo["cpd"]); ?></td>  
                    <td ><?php echo ($vo['incomebank'][0][0]); ?><!-----收入金额 收款添备注----->
                    	<?php if($vo["audit_sk"] == 1): ?><!----收款财务已审核 将不再能备注------>
                        	<?php if($vo['incomebank'][0][2] != ''): ?><span title="<?php echo ($vo['incomebank'][0][2]); ?>" style="color:#F00;">注</span><?php endif; ?>     
                       <?php else: ?><!----收款财务未审核------>
                   			<?php if(($type == 1)or($type == 3)): ?><!---有修改权--->
                                 <?php if($vo['incomebank'][0][2] == ''): ?><!----未审核 未备注 颜色为黑 可以备注--->
                                    <span><a href="__URL__/bz_sk/t/income/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="添加备注">注</a></span>                               
                                <?php else: ?><!----未审核 已备注 颜色为蓝 可以修改备注--->
                                    <span><a href="__URL__/bz_sk/t/income/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="<?php echo ($vo['incomebank'][0][2]); ?>" style="color:#00F;">注</a></span><?php endif; ?> 
                        	<?php else: ?><!---无修改权--->
                                <?php if($vo['incomebank'][0][2] != ''): ?><<span title="<?php echo ($vo['incomebank'][0][2]); ?>" style="color:#F00;">注</span><?php endif; endif; endif; ?>
                    </td>                    
					 <td><?php echo ($vo['incomebank'][0][1]); ?></td> 
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["dpk"]); ?><!-----抵票款 收款添备注----->
                    	<?php if($vo["audit_sk"] == 1): ?><!----收款财务已审核 将不再能备注------>
                        	<?php if($vo['bz_dpk'] != ''): ?><span title="<?php echo ($vo['bz_dpk']); ?>" style="color:#F00;">注</span><?php endif; ?>     
                       <?php else: ?><!----收款财务未审核------>
                   			<?php if(($type == 1)or($type == 3)): ?><!---有修改权--->
                                 <?php if($vo['bz_dpk'] == ''): ?><!----未审核 未备注 颜色为黑 可以备注--->
                                    <span><a href="__URL__/bz_sk/t/dpk/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="添加备注">注</a></span>                               
                                <?php else: ?><!----未审核 已备注 颜色为蓝 可以修改备注--->
                                    <span><a href="__URL__/bz_sk/t/dpk/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="<?php echo ($vo['bz_dpk']); ?>" style="color:#00F;">注</a></span><?php endif; ?> 
                        	<?php else: ?><!---无修改权--->
                                <?php if($vo['bz_dpk'] != ''): ?><span title="<?php echo ($vo['bz_dpk']); ?>" style="color:#F00;">注</span><?php endif; endif; endif; ?> 
                    </td> 
                          
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["pay"]); ?><!-----取票金额 付款添备注----->
                    	<?php if($vo["audit_sk"] == 1): ?><!----收款没有审核 空--->  
                            <?php if($vo["audit_fk"] == 1): ?><!---付款已审核-->
                                <?php if($vo["bz_pay"] == ''): else: ?><span title="<?php echo ($vo["bz_pay"]); ?>" style="color:#F00;">注</span><?php endif; ?> 
                            <?php else: ?><!---付款未审核-->
                            	<?php if(($type == 2)or($type == 3)): ?><!---有修改权--->
                                     <?php if($vo["bz_pay"] == ''): ?><!---- 未备注 颜色为黑 可以备注--->
                                        <span><a href="__URL__/bz_fk/t/pay/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="备注">注</a></span>
                                    <?php else: ?><!---- 已备注 颜色为蓝 可以修改备注--->
                                        <span><a href="__URL__/bz_fk/t/pay/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="<?php echo ($vo["bz_pay"]); ?>" style="color:#00F;">注</a></span><?php endif; ?>                                  
                            	<?php else: ?><!---无修改权--->
                                    <?php if($vo["bz_pay"] != ''): ?><span style="color:#F00;">注</span><?php endif; endif; endif; endif; ?> 
                    </td>
                    
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["nwd"]); ?></td>
                                  
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["nwf"]); ?><!-----拿位费 付款添备注----->
                    	<?php if($vo["audit_sk"] == 1): ?><!----收款没有审核 不可备注 颜色为灰--->
                            <?php if($vo["audit_fk"] == 1): ?><!---付款已审核-->
                                <?php if($vo["bz_nwf"] != ''): ?><span title="<?php echo ($vo["bz_nwf"]); ?>" style="color:#F00;">注</span><?php endif; ?> 
                            <?php else: ?><!---付款未审核-->
                            	<?php if(($type == 2)or($type == 3)): ?><!---有修改权--->
                                     <?php if($vo["bz_nwf"] == ''): ?><!---- 未备注 颜色为黑 可以备注--->
                                        <span><a href="__URL__/bz_fk/t/nwf/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="备注">注</a></span>
                                    <?php else: ?><!---- 已备注 颜色为蓝 可以修改备注--->
                                        <span><a href="__URL__/bz_fk/t/nwf/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="<?php echo ($vo["bz_nwf"]); ?>" style="color:#00F;">注</a></span><?php endif; ?>                                  
                            	<?php else: ?><!---无修改权--->
                                    <?php if($vo["bz_nwf"] != ''): ?><span style="color:#F00;">注</span><?php endif; endif; endif; endif; ?>                   		
                    </td>                    
                    
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["skf"]); ?><!-----刷卡费 收款添备注----->
						<?php if($vo["audit_sk"] == 1): ?><!----收款财务已审核 将不再能备注------>
                        	<?php if($vo['bz_skf'] != ''): ?><span title="<?php echo ($vo['bz_skf']); ?>" style="color:#F00;">注</span><?php endif; ?>     
                       <?php else: ?><!----收款财务未审核------>
                   			<?php if(($type == 1)or($type == 3)): ?><!---有修改权--->
                                 <?php if($vo['bz_skf'] == ''): ?><!----未审核 未备注 颜色为黑 可以备注--->
                                    <span><a href="__URL__/bz_sk/t/skf/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="添加备注">注</a></span>                               
                                <?php else: ?><!----未审核 已备注 颜色为蓝 可以修改备注--->
                                    <span><a href="__URL__/bz_sk/t/skf/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="<?php echo ($vo['bz_skf']); ?>" style="color:#00F;">注</a></span><?php endif; ?> 
                        	<?php else: ?><!---无修改权--->
                                <?php if($vo['bz_skf'] != ''): ?><span title="<?php echo ($vo['bz_skf']); ?>" style="color:#F00;">注</span><?php endif; endif; endif; ?>                                         	
                    </td>
                                        
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["tpk"]); ?><!-----退票款 付款添备注----->
                    	<?php if($vo["audit_sk"] == 1): if($vo["audit_fk"] == 1): ?><!---付款已审核-->
                                <?php if($vo["bz_tpk"] != ''): ?><span title="<?php echo ($vo["bz_tpk"]); ?>" style="color:#00F;">注</span><?php endif; ?> 
                            <?php else: ?><!---付款未审核-->
                            	<?php if(($type == 2)or($type == 3)): ?><!---有修改权--->
                                     <?php if($vo["bz_tpk"] == ''): ?><!---- 未备注 颜色为黑 可以备注--->
                                        <span><a href="__URL__/bz_fk/t/tpk/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="备注">注</a></span>
                                    <?php else: ?><!---- 已备注 颜色为蓝 可以修改备注--->
                                        <span><a href="__URL__/bz_fk/t/tpk/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="<?php echo ($vo["bz_tpk"]); ?>" style="color:#00F;">注</a></span><?php endif; ?>                                  
                            	<?php else: ?><!---无修改权--->
                                    <?php if($vo["bz_tpk"] != ''): ?><span style="color:#00F;">注</span><?php endif; endif; endif; endif; ?> 
                    </td>
                    
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["fkf"]); ?><!-----返客费 付款添备注----->
                    	<?php if($vo["audit_sk"] == 1): if($vo["audit_fk"] == 1): ?><!---付款已审核-->
                                <?php if($vo["bz_fkf"] != ''): ?><span title="<?php echo ($vo["bz_fkf"]); ?>" style="color:#F00;">注</span><?php endif; ?> 
                            <?php else: ?><!---付款未审核-->
                            	<?php if(($type == 2)or($type == 3)): ?><!---有修改权--->
                                     <?php if($vo["bz_fkf"] == ''): ?><!---- 未备注 颜色为黑 可以备注--->
                                        <span><a href="__URL__/bz_fk/t/fkf/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="备注">注</a></span>
                                    <?php else: ?><!---- 已备注 颜色为蓝 可以修改备注--->
                                        <span><a href="__URL__/bz_fk/t/fkf/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="<?php echo ($vo["bz_fkf"]); ?>" style="color:#00F;">注</a></span><?php endif; ?>                                  
                            	<?php else: ?><!---无修改权--->
                                    <?php if($vo["bz_fkf"] != ''): ?><span style="color:#F00;">注</span><?php endif; endif; endif; endif; ?>                   	
                    </td>
                    
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["profit"]); ?></td>
                    <?php if(($type == 1)or($type == 2)or($type == 3)): ?><td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["ylce"]); ?></td><?php endif; ?>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["cdr"]); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["dzd"]); ?></td>
                    
                    <td rowspan="<?php echo ($vo["count"]); ?>"><!-----备注 收款添备注---->
                    	<?php if($vo["audit_sk"] == 1): ?><!----已审核------>
                    		<?php echo (($vo["remark"])?($vo["remark"]):'无'); ?>
                        <?php else: ?>
                        	<?php if(($type == 1)or($type == 3)): if($vo['remark'] == ''): ?><span><a href="__URL__/bz_sk/t/remark/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" style="color:#00F;">添加</a></span>
                                <?php else: ?>
                                    <a href="__URL__/bz_sk/t/remark/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="修改备注"><?php echo ($vo["remark"]); ?></a><?php endif; ?>
                            <?php else: ?>
                             	<?php echo (($vo["remark"])?($vo["remark"]):'无'); endif; endif; ?>
                    </td> 
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["pay_time"]); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["pay_detail"]); ?></td> 
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["tkmx"]); ?></td> 
                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["fkmx"]); ?></td>
                    <td rowspan="<?php echo ($vo["count"]); ?>">
                   		<a href="__URL__/view/id/<?php echo ($vo["id"]); ?>" height="450" width="650" target="dialog" style="color:#00F;">查看</a>&nbsp;&nbsp;
                        <a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>" height="530" width="700" target="dialog" style="color:#00F;">编辑</a>
                    </td>            
                                     
                    <td rowspan="<?php echo ($vo["count"]); ?>"><!---收款审核-->
                    	<?php if($vo['audit_sk'] == 0): if(($type == 1)or($type == 3)): ?><a href="__URL__/audit/t/sk/id/<?php echo ($vo["id"]); ?>" height="490" width="650" target="dialog" title="审核" style="color:#00F;">审核</a> 
                             <?php else: ?>
                             	<a style="color:#999;">未审核</a><?php endif; ?>
                        <?php else: ?>
                        	已审核<?php endif; ?>
                    </td> 
                    <td rowspan="<?php echo ($vo["count"]); ?>"><!---付款审核-->
                        <?php if($vo['audit_sk'] == 0): ?><a style="color:#999;">未审核</a>
                       	<?php else: ?>
                            <?php if($vo['audit_fk'] == 0): if(($type == 2)or($type == 3)): ?><a href="__URL__/audit/t/fk/id/<?php echo ($vo["id"]); ?>" height="490" width="650" target="dialog" title="审核" style="color:#00F;">审核</a>
                                 <?php else: ?>
                                    <a style="color:#999;">未审核</a><?php endif; ?>                                    
                            <?php else: ?>
                            	已审核<?php endif; endif; ?>                        
                    </td>
                </tr>
                <?php if(is_array($vo['incomebank'])): $i = 0; $__LIST__ = array_slice($vo['incomebank'],1,null,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><tr class="billing">                    	                      
                        <td><?php echo ($vo1[0]); ?>                        
                            <?php if($vo["audit_sk"] == 1): ?><!----已审核------>
                                <?php if($vo1[2] != ''): ?><span title="<?php echo ($vo1[2]); ?>" style="color:#F00;">注</span><?php endif; ?>
                            <?php else: ?><!----未审核------>
                                <?php if(($type == 1)or($type == 3)): ?><!---有修改权--->
                                     <?php if($vo1[2] == ''): ?><!----未审核 未备注 颜色为黑 可以备注--->
                                        <span><a href="__URL__/bz_sk/t/income/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="添加备注">注</a></span>
                                    <?php else: ?><!----未审核 已备注 颜色为蓝 可以修改备注--->
                                        <span><a href="__URL__/bz_sk/t/income/id/<?php echo ($vo["id"]); ?>" height="195" width="420" target="dialog" title="<?php echo ($vo1[2]); ?>" style="color:#00F;">注</a></span><?php endif; ?> 
                                <?php else: ?><!---无修改权--->
                                     <?php if($vo1[2] != ''): ?><span title="<?php echo ($vo1[2]); ?>" style="color:#F00;">注</span><?php endif; endif; endif; ?>                       
                        </td>
                        <td><?php echo ($vo1[1]); ?></td>  
                    </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
        </tbody>       
    </table>

<div class="panelBar">
    <div class="pages">
        <span>显示</span>
        <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
            <option value="10" <?php if(($numPerPage) == "10"): ?>selected=selected<?php endif; ?>>10</option>
            <option value="20" <?php if(($numPerPage) == "20"): ?>selected=selected<?php endif; ?>>20</option>
            <option value="50" <?php if(($numPerPage) == "50"): ?>selected=selected<?php endif; ?>>50</option>
            <option value="100" <?php if(($numPerPage) == "100"): ?>selected=selected<?php endif; ?>>100</option>
        </select>
        <span>共<?php echo ($totalCount); ?>条</span>  <span class="mgl_10">  页：<?php echo ($currentPage); ?>/<?php echo ($totalPages); ?></span>
    </div>
    <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
</div>