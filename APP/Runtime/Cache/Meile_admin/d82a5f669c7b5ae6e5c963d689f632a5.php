<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo (C("sitename")); ?></title>

    <link href="__PUBLIC__/dwz/themes/default/style.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/dwz/themes/css/core.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/dwz/themes/css/myStyle.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/dwz/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
    <!--[if IE]>
    <link href="__PUBLIC__/dwz/themes/css/ieHack.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <style type="text/css">
        #header{height:85px }
        #leftside, #container, #splitBar, #splitBarProxy{top:90px}
    </style>
    <script src="__PUBLIC__/dwz/js/speedup.js" type="text/javascript"></script>
    <script src="__PUBLIC__/dwz/js/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/jquery.placeholder.js" type="text/javascript"></script>
    <script src="__PUBLIC__/dwz/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="__PUBLIC__/dwz/js/jquery.validate.js" type="text/javascript"></script>
    <script src="__PUBLIC__/dwz/js/jquery.bgiframe.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/xheditor/xheditor-1.2.1.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/dwz/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>
    <script src="__PUBLIC__/dwz/uploadify/scripts/jquery.uploadify.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/dwz/js/dwz.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/dwz/js/dwz.regional.zh.js" type="text/javascript"></script>
    <script type="text/javascript" src="__PUBLIC__/js/layer/layer.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/lightbox-2.6.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/dwz/js/my.js" ></script>
    <link rel="stylesheet" href="__PUBLIC__/css/lightbox.css" media="screen"/>

    <!-- svg图表  supports Firefox 3.0+, Safari 3.0+, Chrome 5.0+, Opera 9.5+ and Internet Explorer 6.0+ -->
    <script type="text/javascript" src="__PUBLIC__/dwz/js/chart/raphael.js"></script>
    <script type="text/javascript" src="__PUBLIC__/dwz/js/chart/g.raphael.js"></script>
    <script type="text/javascript" src="__PUBLIC__/dwz/js/chart/g.bar.js"></script>
    <script type="text/javascript" src="__PUBLIC__/dwz/js/chart/g.line.js"></script>
    <script type="text/javascript" src="__PUBLIC__/dwz/js/chart/g.pie.js"></script>
    <script type="text/javascript" src="__PUBLIC__/dwz/js/chart/g.dot.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/member.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/hotcitypop/popcitylist.js"></script>
    <script type="text/javascript">
        var Public="<?php echo U('/Public/');?>";
        var hash_val = window.location.hash;
            hash_val = hash_val.replace("#", "");

        function fleshVerify(){
            //重载验证码
            $('#verifyImg').attr("src", '__GROUP__/Public/verify/'+new Date().getTime());
        }

        function dialogAjaxMenu(json){
            dialogAjaxDone(json);
            if (json.statusCode == DWZ.statusCode.ok){
                //扩展
                var menuTag=$("#navMenu .selected").attr('menu');
                $("#sidebar").loadUrl("__GROUP__/Public/menu/menu/"+menuTag);
            }
        }

        function navTabAjaxMenu(json){
            navTabAjaxDone(json);
            if (json.statusCode == DWZ.statusCode.ok){
                //扩展
                var menuTag=$("#navMenu .selected").attr('menu');
                $("#sidebar").loadUrl("__GROUP__/Public/menu/menu/"+menuTag);
            }
        }

        function funComplete(event, ID, fileObj, response, data) {
            //alert('上传事件');
            if (response == 0) {
                alert('图片' + fileObj.name + '操作失败');
                return false;
            }else{
                var str=$('.image').html();
                var add="<img src='"+"/Uploads/"+response+"'" +"style='margin-left:15px;margin-top:15px'/></img>";
                str+=add;
                $('.image').html(str);
                return true;
            }
        }


        //刷新我的需求单
        function afreshRequireOrderInfo(json){
            navTabAjaxDone(json);
            if (json.statusCode == DWZ.statusCode.ok){
                $('.requireOrderInfo').trigger("click");
                    layer.close();
            }
        }
        //获取新的需求单

        $(function(){
            var la_ms=0;var la_mss=0;
            function ajaxRequireOrder(){
                if(la_ms){ layer.close(la_ms);la_ms=''}
                $.ajax({
                    url: "__GROUP__/RequireOrder/getNew",
                    cache: false,
                    success: function(data){
                        if(data && data.status==1){
                            var info = data.info;
                            var html = "你有一条新有客户需求 待认领：<br/>";
                            html += "客户姓名："+info.name+'  <br/>';
                            html +='认领查看详情：<a class="add" style="color: red" href="__GROUP__/RequireOrder/getNews/id/'+info.id+'" callback="afreshRequireOrderInfo" target="ajaxTodo" rel="RequireOrder" >点击获取</a>';
                            la_ms=$.layer({
                                type:4,
                                shade : false,
                                zIndex: layer.zIndex,
                                move : ['.layerTipsG' , true],
                                tips : {
                                    msg: html,
                                    follow: $('.requireOrderInfo'),
                                    guide: 0,
                                    isGuide: true,
                                    style: ['background-color:#449ef6; color:#fff;padding-top:5px;padding-bottom:5px;','#449ef6']
                                },
                                maxWidth:220,
                                close : function(index){ layer.close(la_ms); la_ms='';
                                }
                            });
                            initUI('.xubox_tips');
                           }else{
                            if(la_ms){ layer.close(la_ms);la_ms=0 }
                           }
                    },
                    global:false,
                    dataType:'json'
                });
            }
            function ajaxClaimSystem(first){
                if(la_mss==0){
                    $.ajax({
                        url: "__GROUP__/ClaimSystemS/newTips/first/"+first,
                        cache: false,
                        success: function(data){
                            if(data.msg){
                               la_mss=$.layer({
                                   type:4,
                                   shade : false,
                                   zIndex: layer.zIndex,
                                   move : ['.layerTipsG' , true],
                                   tips : {
                                        msg: data.msg,
                                        follow: $('.sysInfo'),
                                        guide: 0,
                                        isGuide: true,
                                        style: ['border-bottom-color:#1780E7;background-color:#1780E7; color:#fff;padding-top:5px;padding-bottom:5px;','#449ef6']
                                    },
                                    maxWidth:220,
                                    close : function(index){ layer.close(la_mss); la_mss=0;
                                    }
                                });
                                initUI('.xubox_tips');
                            }else{
                                if(la_mss){
                                    layer.close(la_mss);
                                }
                            }
                        },
                        global:false,
                        dataType:'json'
                    });
                }
            }

            ajaxClaimSystem(1);ajaxRequireOrder();
            var shTime = setInterval(ajaxClaimSystem,120000);
            var shTime = setInterval(ajaxRequireOrder,60000);

        })

        function navTabAjaxGroupMenu(json){
            navTabAjaxDone(json);
            if (json.statusCode == DWZ.statusCode.ok){
                //扩展
                var menuTag=$("#navMenu .selected").attr('menu');
                $("#sidebar").loadUrl("__GROUP__/Public/menu/menu/"+menuTag);
            }
        }


        $(function(){
            DWZ.init("__PUBLIC__/dwz/dwz.frag.xml",{
                loginUrl:"__GROUP__/Public/login_dialog", loginTitle:"登录",	// 弹出登录对话框
                statusCode:{ok:1,error:0},
                pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"_order", orderDirection:"_sort"}, //【可选】
                debug:false,	// 调试模式 【true|false】
                callback:function(){
                    initEnv();
                    $("#themeList").theme({themeBase:"__PUBLIC__/dwz/themes"});
                    intIndex();
                }
            });


            function intIndex(){
                setTimeout(function() {
                    if(hash_val){
                        $.getJSON('__GROUP__/Public/jsCheckUrl?u='+hash_val,function(data){
                            if(data){
                                $.each($('#navMenu ul li'),function(i,item){
                                    if($(item).attr('menu')==data.menu){
                                        $(item).children('a').trigger("click");
                                    }
                                })
                                navTab.openTab(hash_val, data.url,{ title: data.title,data: {},fresh: true,external: false});
                            }
                        })
                    }
                    },10);
            }
        });
    </script>
</head>

<body scroll="no">
<div id="layout">
    <div id="header">
        <div class="headerNav">
            <a  href="__GROUP__"><img src="__PUBLIC__/dwz/logo.png" width="200px" height="45px"></a>
            <ul class="nav">
                <li><a class="requireOrderInfo" href="__GROUP__/RequireOrder/myRequireOrder" target="dialog" width="620" height="360" rel="requireOrderInfo">我的需求单</a></li>
                <li><a class="sysInfo" href="#"  width="580" height="360" rel="sysInfo">系统消息</a></li>
                <li><a class="password" href="__GROUP__/Public/password/" target="dialog" mask="true">修改密码</a></li>
                <li><a class="profile" href="__GROUP__/Public/profile/" target="dialog" mask="true">修改资料</a></li>
                <li><a href="__GROUP__/Public/logout/">退出</a></li>
            </ul>
            <ul class="themeList" id="themeList">
                <li theme="default"><div class="selected">蓝色</div></li>
                <li theme="green"><div>绿色</div></li>
                <li theme="purple"><div>紫色</div></li>
                <li theme="silver"><div>银色</div></li>
                <li theme="azure"><div>天蓝</div></li>
            </ul>
        </div>
        <div id="navMenu">
            <ul>
                <?php if(is_array($volist)): $k = 0; $__LIST__ = $volist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li <?php if(($k) == "1"): ?>class="selected"<?php endif; ?> menu="<?php echo ($vo["menu"]); ?>">
                     	<a href="__GROUP__/Public/menu/menu/<?php echo ($vo["menu"]); ?>"><span><?php echo ($vo["name"]); ?></span></a>
                     </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>

    <div id="leftside">
        <div id="sidebar_s">
            <div class="collapse">
                <div class="toggleCollapse"><div></div></div>
            </div>
        </div>
        <div id="sidebar">
            <div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>
            
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



        </div>
    </div>

    <div id="container">
        <div id="navTab" class="tabsPage">
            <div class="tabsPageHeader">
                <div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
                    <ul class="navTab-tab">
                        <li tabid="main" class="main"><a href="javascript:void(0)"><span><span class="home_icon">我的主页</span></span></a></li>
                    </ul>
                </div>
                <div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
                <div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
                <div class="tabsMore">more</div>
            </div>
            <ul class="tabsMoreList">
                <li><a href="javascript:void(0)">我的主页</a></li>
            </ul>
            <div class="navTab-panel tabsPageContent layoutBox">
                <div class="page unitBox">
                    <div class="accountInfo">
                        <div class="alertInfo"></div>
                        <div class="right"></div>
                        <p>您好:  <?php echo ($userInfo["username"]); ?> <?php echo ($userInfo["name"]); ?> </p>
                    </div>
                    <div class="pageFormContent" layoutH="80">
                        <script type="text/javascript" charset="utf-8">
                            /* Title settings */
                            title = "October Browser Statistics";
                            titleXpos = 390;
                            titleYpos = 85;

                            /* Pie Data */
                            pieRadius = 130;
                            pieXpos = 150;
                            pieYpos = 180;
                            pieData = [1149422, 551315, 172095, 166565, 53329, 18060, 8074, 1941, 1393, 1104, 2110];
                            pieLegend = [
                                "%%.%% – Firefox",
                                "%%.%% – Internet Explorer",
                                "%%.%% – Chrome",
                                "%%.%% – Safari",
                                "%%.%% – Opera",
                                "%%.%% – Mozilla",
                                "%%.%% – Mozilla Compatible Agent",
                                "%%.%% – Opera Mini",
                                "%%.%% – SeaMonkey",
                                "%%.%% – Camino",
                                "%%.%% – Other"];

                            pieLegendPos = "east";

                            $(function () {
                                var r = Raphael("chartHolder");

                                r.text(titleXpos, titleYpos, title).attr({"font-size": 20});

                                var pie = r.piechart(pieXpos, pieYpos, pieRadius, pieData, {legend: pieLegend, legendpos: pieLegendPos});
                                pie.hover(function () {
                                    this.sector.stop();
                                    this.sector.scale(1.1, 1.1, this.cx, this.cy);
                                    if (this.label) {
                                        this.label[0].stop();
                                        this.label[0].attr({ r: 7.5 });
                                        this.label[1].attr({"font-weight": 800});
                                    }
                                }, function () {
                                    this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");
                                    if (this.label) {
                                        this.label[0].animate({ r: 5 }, 500, "bounce");
                                        this.label[1].attr({"font-weight": 400});
                                    }
                                });

                            });
                        </script>
                        <div id="chartHolder"></div>
                        <div class="divider"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="footer">Copyright &copy; 2013 <a href="#" target="_blank">aishangfei.net</a></div>
</body>
</html>