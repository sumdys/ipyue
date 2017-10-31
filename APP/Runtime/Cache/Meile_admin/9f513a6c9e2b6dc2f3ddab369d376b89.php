<?php if (!defined('THINK_PATH')) exit();?>
<div class="accordion" fillSpace="sideBar">
    <?php if(is_array($groups)): $i = 0; $__LIST__ = $groups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i; if(!empty($menu[$group['id']])): ?><div class="accordionHeader"><h2><span>Folder</span><?php echo ($group["title"]); ?></h2></div>        
            <div class="accordionContent">
                <ul class="tree treeFolder">
                    <?php if(is_array($menu[$group['id']])): $i = 0; $__LIST__ = $menu[$group['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if(($item["level"]) == "3"): ?><li <?php if(($i) == "1"): ?>class="first"<?php endif; ?>>
                                    <a href="__GROUP__/<?php echo ($list[$item['pid']]['name']); ?>/<?php echo ($item['name']); ?>" target="navTab" 
                                     rel="<?php echo ($list[$item['pid']]['name']); ?>-<?php echo ($item['name']); ?>"><?php echo ($item['title']); ?></a>
                                 </li>
                             <?php else: ?>
                                <li <?php if(($i) == "1"): ?>class="first"<?php endif; ?>><a href="__GROUP__/<?php echo ($item['name']); ?>" target="navTab" rel="<?php echo ($item['name']); ?>-index"><?php echo ($item['title']); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    <script>
        function clickfirst(){
         //   $('.first a').first().click();
        }
        setTimeout(clickfirst,50);
    </script>
</div>