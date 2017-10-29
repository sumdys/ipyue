<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" action="__URL__" method="post">
    <input type="hidden" name="pageNum" value="<?php echo (($_REQUEST['pageNum'])?($_REQUEST['pageNum']):1); ?>"/>
    <input type="hidden" name="numPerPage" value="<?php echo ($numPerPage); ?>"/>
    <input type="hidden" name="so" value="<?php echo ($_REQUEST["so"]); ?>" />
    <input type="hidden" name="_order" value="<?php echo ($_REQUEST["_order"]); ?>"/>
    <input type="hidden" name="_sort" value="<?php echo (($_REQUEST['_sort'])?($_REQUEST['_sort']):'1'); ?>"/>
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST['listRows']); ?>"/>

    <?php if(is_array($map)): $i = 0; $__LIST__ = $map;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?><input type="hidden" name="<?php echo ($key); ?>" value="<?php echo ($_REQUEST[$key]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>

</form>
<script>
    var zhou='';
    var img_path='__PUBLIC__/uploads/cheap/';
    var url='';
    function searchs(urls){
        var  datas=$('#search').serialize();
        url = urls?urls:"<?php echo U('Cheap/cheap');?>?ss=1&"+datas;
        $.getJSON(url,function(data){
            if(data){
                var html='';
                $.each(data, function(i,vo){
                    html +='<tr align="center" id="'+vo.id+'">';
                    html +=' <td align="left">'+vo.id+'</td>';
                    html +=' <td>'+vo.zhou+'</td>';
                    html +='  <td>'+vo.from_city+'</td>';
                    html +=' <td>'+vo.to_city+'</td>';
                    html +=' <td>'+vo.time_name+'</td>';
                    html +=' <td>'+vo.air+'</td>';
                    html +=' <td>'+vo.price+'</td>';
                    if(vo.img){
                        html +='<td><a href="'+img_path+vo.img+'" target="_blank">查看图片</a></td>';
                    }else{
                        html +='<td><a href="javascript:;" onclick="edit('+vo.id+')">添加图片</a></td>';
                    }
                    html +=' <td>'+vo.update_time+'</td>';
                    html +=' <td>[ <a  href="javascript:;" onclick="edit('+vo.id+')">编辑 </a> ]' +
                            ' [ <a link="?act=del&id'+vo.id+'" href="javascript:void(0)" name="'+vo.title+'" class="del">删除 </a> ]</td>';
                    html +='</tr>';
                });
                $('#datalist').html(html);
                $('#page').html(data.page);
            }else{
                $('#datalist').html('');
                $('#page').html('');
            }
        })

    }
    function page(u,p){
        searchs(p);
    }
    function gettj(dq){
        var send=encodeURI('dq='+dq);
        url="<?php echo U('Cheap/cheap');?>?"+send;
        searchs(url);
    }
</script>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="__URL__" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>搜索：</label>
                    <input type="text" name="so" value="<?php echo ($_REQUEST["so"]); ?>"/>
                </li>
                <!--
                <li>
                    <label>状态：</label>
                    <select class="combox" name="status">
                        <option value="">请选择</option>
                        <option value="0" selected=selected>有效</option>
                        <option value="1">过期</option>
                    </select>
                </li>
                -->
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
            <?php if(is_array($zhou)): foreach($zhou as $k=>$v): ?><li>
                    <a class="icon" style="color: red;font-weight: bold;" target="navTab" href="__URL__/index/zhou/<?php echo ($v["zhou"]); ?>" <?php if($k==4): ?>class="active"<?php endif; ?>>
                    <span><?php echo ($v["zhou"]); ?></span>
                    </a>
                </li><?php endforeach; endif; ?>
            <li class="line">line</li>
            <li><a class="add" href="__URL__/add" target="dialog" rel="add" mask="true" width="810" height="400" ><span>新增</span></a></li>
            <li><a class="delete" href="__URL__/foreverdelete/id/{sid_user}/navTabId/__MODULE__" target="ajaxTodo" title="你确定要删除吗？" warn="请选择用户" ><span>删除</span></a></li>
            <li><a class="edit" href="__URL__/edit/id/{sid_user}" target="dialog" rel="edit" mask="true" warn="请选择用户"><span>编辑</span></a></li>
        </ul>
    </div>

    <table class="table" width="100%" layoutH="115">
        <thead>
        <tr>
            <th width="60"><input type="checkbox" group="ids[]" class="checkboxCtrl"> :编号</th>
            <th width="60" orderField="zhou" <?php if($_REQUEST["_order"] == 'zhou'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>州</th>
            <th width="100" orderField="from_city" <?php if($_REQUEST["_order"] == 'from_city'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>出发城市</th>
            <th width="100" orderField="to_city" <?php if($_REQUEST["_order"] == 'to_city'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>到达城市</th>
            <th width="100" orderField="time_name" <?php if($_REQUEST["_order"] == 'time_name'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>截止日期</th>
            <th width="100" orderField="air" <?php if($_REQUEST["_order"] == 'air'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>航空公司</th>
            <th width="100" orderField="price" <?php if($_REQUEST["_order"] == 'price'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>是>价格</th>
            <th width="100" orderField="img" <?php if($_REQUEST["_order"] == 'img'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>广告图</th>
            <th width="80" orderField="update_time" <?php if($_REQUEST["_order"] == 'update_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>更新时间</th>

            <th width="120">操作</th>
        </tr>
        </thead>
        <tbody id="datalist">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_user" rel="<?php echo ($vo['id']); ?>">
                <td><input name="ids[]" value="<?php echo ($vo['id']); ?>" type="checkbox">:<?php echo ($vo['id']); ?></td>
                <td><?php echo ($vo['zhou']); ?></td>
                <td><?php echo ($vo['from_city']); ?></td>
                <td><?php echo ($vo['to_city']); ?></td>
                <td><?php echo ($vo['time']); ?></td>
                <td><?php echo ($vo['air']); ?></td>
                <td><?php echo ($vo['price']); ?></td>
                <td><?php if(empty($vo["img"])): ?><a href="__URL__/edit/id/<?php echo ($vo['id']); ?>" target="dialog" rel="edit" width="810" height="400">添加图片</a>
                    <?php else: ?>
                    <a href="__PUBLIC__/uploads/cheap/<?php echo ($vo["img"]); ?>" target="_blank">查看图片</a><?php endif; ?>
                </td>
                <td><?php echo (date("Y-m-d H:i",$vo["update_time"])); ?></td>
                <td>
                    <!--                                   <?php echo (showstatus($vo['status'],$vo['id'],'navTabAjaxDone')); ?>&nbsp;&nbsp;&nbsp;-->
                    <a href="__URL__/edit/id/<?php echo ($vo['id']); ?>" target="dialog" rel="edit" width="810" height="400" ><span>编辑</span></a>&nbsp;&nbsp;&nbsp;
                    <a href="__URL__/del/id/<?php echo ($vo['id']); ?>/navTabId/__MODULE__-index" target="navTabTodo" title="你确定要删除吗？">删除</a>
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

</div>