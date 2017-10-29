<?php if (!defined('THINK_PATH')) exit();?>﻿<script>
    $(function(){
        $(".city").live('click',function(){
            popCityList($(this)[0]);
        });
        $('#addTag').click(function(){
            var html='<input type="text" class="tag" name="tag_name[]" value=""/>';
            $('#tags').append(html);
        })
    })
</script>

<style type="text/css">
    .border_div{border:1px solid #999; font-size:12px; margin-right:10px;}
    .border_div span{font-weight:bold; clear:both;}
    .border_bottom{border-bottom:1px solid #CCC; padding-top:3px; padding-bottom:3px;padding-left:20px;}
    .border_div input{ border:1px solid #999; color:#666;margin-top:2px; margin-left:5px; margin-right:5px;float:none;}
    .in{width:100px; margin:0 auto; padding:0px;}
    .tag{margin-right: 10px;margin-bottom: 10px;}
</style>

<div class="page">
	<div class="pageContent">
	<form method="post" enctype="multipart/form-data" action="__URL__/edit/navTabId/__MODULE__-index" class="pageForm required-validate" onsubmit="return  validateCallback(this,dialogAjaxDone)" novalidate="novalidate">
		<div class="pageFormContent" layoutH="58">
            <input type="hidden" name="info[id]" value="<?php echo ($info["id"]); ?>">
            <div class="unit">
				<label>标题：</label>
				<input type="text" class="required" size="60" name="info[title]" id="title" value="<?php echo ($info["title"]); ?>">
			</div>
            <div class="unit">
                <label>线路类型：</label>
                <input type="radio" name="info[line_type]" value="1"  <?=$info['line_type']==1?'checked':''?>/>短线
                <input type="radio" name="info[line_type]" value="2"  <?=$info['line_type']==2?'checked':''?>/>长线
                <input type="radio" name="info[line_type]" value="3"  <?=$info['line_type']==3?'checked':''?>/>别墅
                <input type="radio" name="info[line_type]" value="4"  <?=$info['line_type']==4?'checked':''?>/>客栈
                <input type="radio" name="info[line_type]" value="5"  <?=$info['line_type']==5?'checked':''?>/>国内航线
                <input type="radio" name="info[line_type]" value="6"  <?=$info['line_type']==6?'checked':''?>/>国际航线

            </div>
            <div class="unit">
                <label>行程：</label>
                <input type="text" name="info[dcity]" value="<?php echo ($info["dcity_name"]); ?>(<?php echo ($info["dcity"]); ?>)" placeholder="出发城市" class="text city textInput valid required" state="1" size="12">
                <input type="text" name="info[acity]" value="<?php echo ($info["acity_name"]); ?>(<?php echo ($info["acity"]); ?>)" placeholder="目的城市" class="text city textInput valid required" state="1" size="12">
            </div>
            <div class="unit">
                <label>游玩天数：</label>
                <input type="text" name="info[days]" value="<?php echo (($info["days"])?($info["days"]):1); ?>" />
            </div>
            <div class="unit">
                <label>套餐包含：</label>
                <input type="checkbox" name="info[package][]" value="jp"  <?=in_array('jp',$info['package'])?'checked':''?>/>机票
                <input type="checkbox" name="info[package][]" value="jd" <?=in_array('jd',$info['package'])?'checked':''?>/>酒店
            </div>
            <div class="unit">
                <label>价格：</label>
                <input type="text" class="required" name="info[price]" value="<?php echo ($info["price"]); ?>" placeholder="出售价格"  />
                &nbsp;&nbsp;| 原价：  <input type="text" style="float: none" placeholder="旧的价格" value="<?php echo ($info["old_price"]); ?>" name="info[old_price]" value="" />
            </div>
            <div class="unit">
                <label>图片：</label>
                <input type="text" name="images[]" lookup="img1.fileName" size="12"  readonly="readonly" class="textInput readonly" value='<?php echo ($info[images][0]); ?>'>
                <a class="btnAttach" href="__URL__/upload" rel="upload"  lookupGroup="img1" width="560" height="300" title="查找带回">查找带回</a>

                <input type="text" name="images[]" lookup="img2.fileName" size="12"  readonly="readonly" class="textInput readonly" value='<?php echo ($info[images][1]); ?>'>
                <a class="btnAttach" href="__URL__/upload" rel="upload"  lookupGroup="img2" width="560" height="300" title="查找带回">查找带回</a>

                <input type="text" name="images[]" lookup="img3.fileName" size="12"  readonly="readonly" class="textInput readonly" value='<?php echo ($info[images][2]); ?>'>
                <a class="btnAttach" href="__URL__/upload" rel="upload" lookupGroup="img3" width="560" height="300" title="查找带回">查找带回</a>

                <input type="text" name="images[]" lookup="img4.fileName" size="12"  readonly="readonly" class="textInput readonly" value='<?php echo ($info[images][2]); ?>'>
                <a class="btnAttach" href="__URL__/upload" rel="upload" lookupGroup="img4" width="560" height="300" title="查找带回">查找带回</a>

                <input type="text" name="images[]" lookup="img5.fileName" size="12"  readonly="readonly" class="textInput readonly" value='<?php echo ($info[images][2]); ?>'>
                <a class="btnAttach" href="__URL__/upload" rel="upload" lookupGroup="img5" width="560" height="300" title="查找带回">查找带回</a>
            </div>
            <div class="unit">
                <label width="100">发布时间：</label>
                <input type="text"  class="input"  name="info[published]" value="<?php echo ($info['published']); ?>"/>
            </div>
            <div class="unit">
                <label width="100">热门推荐：</label>
                <input type="checkbox"  class="checkbox"  name="info[hot]" value="1" <?=$info['hot']?'checked':''?>/>
            </div>
            <div class="unit">
                <label>描述：</label>
                <textarea class="alt required" cols="100" rows="3" name="info[description]" alt="用于SEO 如果不填写则系统自动截取文章前200个字符" ><?php echo ($info["description"]); ?></textarea>
                <span class="info"></span>
            </div>
            <div class="unit">
                <label>标签属性：</label>
                <div id="tags" style="float: left;">
                    <?php if(info.tags != ''): if(is_array($info["tags"])): $i = 0; $__LIST__ = $info["tags"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input type="text" class="tag" name="tag_name[]" value="<?php echo ($vo); ?>"/><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </div>
                <span class="info">&nbsp;&nbsp;<a href="javascript:void(0);" id="addTag">添加+</a></span>
            </div>
            <div class="unit">
                <label>线路特色：</label>
                <textarea id="feature" class="editor {upImgUrl:'__GROUP__/Public/upload',upImgExt:'jpg,jpeg,gif,png'}" cols="100" rows="20" name="info[feature]"><?php echo ($info["feature"]); ?></textarea>
            </div>
            <div class="unit">
                <label>参考行程：</label>
                <textarea id="cost_info" class="editor {upImgUrl:'__GROUP__/Public/upload',upImgExt:'jpg,jpeg,gif,png'}" cols="100" rows="20" name="info[cost_info]"><?php echo ($info["cost_info"]); ?></textarea>
            </div>
            <div class="unit">
                <label>费用说明：</label>
                <textarea id="reference_route" class="editor {upImgUrl:'__GROUP__/Public/upload',upImgExt:'jpg,jpeg,gif,png'}" cols="100" rows="20" name="info[reference_route]"><?php echo ($info["reference_route"]); ?></textarea>
            </div>
            <div class="unit">
                <label>预订须知：</label>
                <textarea id="	booking_notice" class="editor {upImgUrl:'__GROUP__/Public/upload',upImgExt:'jpg,jpeg,gif,png'}" cols="100" rows="20" name="info[booking_notice]"><?php echo ($info["booking_notice"]); ?></textarea>
            </div>

		</div>		
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	</div>
</div>