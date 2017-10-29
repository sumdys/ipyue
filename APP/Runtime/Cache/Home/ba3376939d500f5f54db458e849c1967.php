<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>品悦旅行网_国内领先的国际旅游定制服务平台</title>
    <meta name="keywords" content="旅游度假、国际旅行、独家定制、私人定制、定制旅行、自由行、境外游" />
    <meta name="description" content="品悦旅行网是国内领先的提供北京、上海、广州、深圳、南京、重庆、武汉等多个出发城市，遍及全球100多个国家和地区的私人定制旅游产品预订、境外自由行产品预订的专业服务平台" />
    <link rel="stylesheet" href="__PUBLIC__/css/main.css" type="text/css" />
    <link rel="stylesheet" href="__PUBLIC__/css/index.css" type="text/css" />
    <script type="text/javascript" src="__PUBLIC__/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/main.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/index.js"></script>
    <link rel="stylesheet" href="__PUBLIC__/css/register.css" type="text/css" />
        <script>
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "//hm.baidu.com/hm.js?7d002bf201366e2ecc985cc83f8c4998";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
	</script>
</head>
<body>
<script type="text/javascript">
    var BaseInfo = {
        'path':"<?php echo U('/');?>"
    };
</script>
<div id="hd_top">
        <div class="wd980"><script type="text/javascript"  src="<?php echo U('/Public/topMenu');?>"></script></div>
    </div>
        <div id="hd_logo">
        <div class="wd960">
            <a href="<?php echo U('/') ?>" class="left"><img src="__PUBLIC__/images/logo.gif" alt="logo" class="logo" /></a>
            <!-- <div class="right"><a href="<?php echo U('/activity/share');?>" target="_blank"><img src="__PUBLIC__/images/activity/share_home_top.jpg" alt="中国国际航空" /></a></div> -->
        </div>
    </div>
    <div id="hd_nav">
        <div class="wd980 relative">
            <div id="index_ticket_query">
                <h2>国际机票预订</h2>
                <script type="text/javascript" src="__PUBLIC__/js/hotcitypop/popcitylist.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" language="javascript" src="__PUBLIC__/js/hotcitypop/searchconditions.js?v=20140331"></script>
<script type="text/javascript" language="javascript" src="__PUBLIC__/js/hotcitypop/paramfarmat.js"></script>
<div class="chaxun">
    <form action="<?php echo U('/iflight/flightquery');?>" name="form0" id="chaxun_form" autocomplete="off" onsubmit="return false;">
        <ul>
            <li id="radio_sel">
                <input type="radio" name="journey" class="journey2" value="2" /><label>单程</label>
                <input type="radio" name="journey" class="journey1" value="1" checked="checked" /><label>往返</label>
                <input type="radio" name="journey" class="journey3" value="3" /><label>多程</label>
            </li>
            <li>
                <span>出发城市</span>
                <input type="text" class="text city" name="origincode"  value="<?php echo ($_GET['origin_name']); ?>"  placeholder="城市名"  state="1"/>
            </li>
            <li>
                <span>到达城市</span>
                <input type="text" class="text city" name="desinationcode"  value="<?php echo ($_GET['desination_name']); ?>" placeholder="城市名"state="1"/>
            </li>
            <li>
                <span>出发日期</span>
                <input type="text" class="text date" name="originDate" id="originDate"  value="<?php echo ($_GET['originDate']); ?>" placeholder="出发时间" state="1" />
            </li>
            <li>
                <span>返回日期</span>
                <input type="text" class="text date" name="returnDate" id="returnDate"  value="<?php echo ($_GET['returnDate']); ?>"  placeholder="返回日期" state="1"/>
            </li>
            <li class="center submit" id="submit_sousuo">
                <input type="submit" value="查询" class="spr sub" />
            </li>
        </ul>
    </form>
    <p class="tishi">如有更多需求，请直接联系<a href="javascript:;" onclick="open_kf()">专业顾问</a></p>
</div>
          </div>
            <ul class="home_nav<?php if(MODULE_NAME=='Index' && $_REQUEST[_URL_][0]!='Member') echo ''; ?>">
    <li <?php if(MODULE_NAME=='Index' && $_REQUEST[_URL_][0]!='Member') echo "class='active'"; ?> ><a href="<?php echo U('/') ?>">首页</a></li>
	    <li <?php if(MODULE_NAME=='Freetour' && $_REQUEST[_URL_][1]!='diy') echo "class='active'"; ?> ><a href="<?php echo U('/freetour')?>">自由行</a></li>
    <!-- <li <?php if(MODULE_NAME=='Iflight') echo "class='active'"; ?> ><a href="<?php echo U('/iflight')?>">国际机票</a></li> -->
    <li <?php if($_REQUEST[_URL_][1]=='diy') echo "class='active'"; ?> ><a href="<?php echo U('/freetour/diy')?>">私人定制</a></li>
    <li <?php if(MODULE_NAME=='Hotel') echo "class='active'"; ?> ><a href="<?php echo U('/Iflight/orderflight')?>">机票预订</a></li>
    <!-- <li <?php if(MODULE_NAME=='Special') echo "class='active'"; ?> ><a href="<?php echo U('/special')?>">特卖</a></li> -->
    <!-- <li><a <?php if(MODULE_NAME=='Ailehui') echo "class='active'"; ?> href="<?php echo U('/Ailehui')?>" class="new">国际酒店<span><img src="__PUBLIC__/images/top_nav_new.gif" alt="new"/></span></a></li> -->
	<!-- <li><a <?php if(MODULE_NAME=='Adviser') echo "class='active'"; ?> href="<?php if($userinfo): ?>{:U('/Adviser/review');?>_<?php echo ($userinfo["user"]["id"]); else: echo U('/adviser'); endif; ?>">专业顾问</a></li>-->
    <!-- <li><a <?php if(MODULE_NAME=='About') echo "class='active'"; ?> href="<?php echo U('/About');?>">关于我们</a></li> -->
</ul>
        </div>
	</div>
    <div id="index_slide_ad">
    	<ul>
            <li img-data="__PUBLIC__/images/index-ad.jpg"><a href="javascript:;" target="_blank"></a></li>
            <li img-data="__PUBLIC__/images/index-banner-2.jpg"><a href="javascript:;" target="_blank"></a></li>
		</ul>
        <div id="slide_ad_nav"><a class="active">1</a><a>2</a></div>
    </div>

    <script type="text/javascript" src="__PUBLIC__/js/jquery.fullScreenAds.js"></script>
    <script type="text/javascript">
    $(function(){
        //使用全屏轮换广告
        $('#index_slide_ad').fullScreenAds({fadeTime:1000,showtime:4000,mainWidth:980});
        //首页国际机票查询的不透明度设置
        $('#index_ticket_query').css("opacity",0.9);
    });
    </script>


    <div id="renzheng_lan"  class="wd980">
    <h2>选择品悦的理由</h2>
       <ul>
          <li class="lb0"><label></label><span><B>百里挑一</B><br/>精选资深定制师,完成您的梦想清单</span></li>
          <li class="lb1"><label></label><span><B>退一赔一</B><br/>旅行途中服务未达标，双倍赔付</span></li>
          <li class="lb2"><label></label><span><B>全程保证</B><br/>全球旅游保险，最高保额达人民币270万元</span></li>
          <li class="lb3"><label></label><span><B>售后保证</B><br/>优越售后服务，退改签毫无障碍</span></li>
        </ul>
     </div>
	      <div id="iF" class="i-f wd980">
        <h3 class="t">周末趣游</h3>
        <div class="b">
          <ol id="iFt" class="i-f-t">
            <li class="curr">暑期玩水</li>
            <li>主题乐园</li>
            <li>特色古镇</li>
            <li>游山玩水</li>
        </ol>
        <ul id="iFc" class="i-f-c">
            <li class="curr">
                <a class="more" href="http://www.ipyue.com/freetour">更多周末趣游路线》</a>
                <div class="one">
                    <a href="http://www.ipyue.com/freetour"><img src="__PUBLIC__/images/index-3.jpg" alt="" /></a>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/34"><img src="http://www.ipyue.com/Public/img/index/1.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/34" title="溧阳天目湖御水温泉度假酒店（主楼）2天1晚家庭游">溧阳天目湖御水温泉度假酒店（主楼）2天1晚家庭游</a></p>
                    <p><span>￥770起</span> 广州出发  2日1夜</p>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/35"><img src="http://www.ipyue.com/Public/img/index/2.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/35" title="置身神农架　２天１夜深度游">置身神农架　２天１夜深度游</a></p>
                    <p><span>￥1128起</span> 广州出发  2日1夜</p>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/33"><img src="http://www.ipyue.com/Public/img/index/3.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/33" title="黄山山上＋山脚，高山森林温泉之游"> 黄山山上＋山脚，高山森林温泉之游</a></p>
                    <p><span>￥1580 起</span> 广州出发  3日2晚</p>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/36"><img src="http://www.ipyue.com/Public/img/index/4.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/36" title="常州恐龙城维景国际大酒店＋玩恐龙园">常州恐龙城维景国际大酒店＋玩恐龙园</a></p>
                    <p><span>￥530起</span> 广州出发  2天1晚</p>
                </div>
                <div>
                   <a href="http://www.ipyue.com/freetour/detail/id/37"> <img src="http://www.ipyue.com/Public/img/index/5.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/37" title="横店旗下酒店＋横店6大景点任选4点＋梦幻谷">横店旗下酒店＋横店6大景点任选4点＋梦幻谷</a></p>
                    <p><span>￥1358 起</span> 广州出发  3天2晚</p>
                </div>
                <div>
                   <a href="http://www.ipyue.com/freetour/detail/id/38"> <img src="http://www.ipyue.com/Public/img/index/6.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/38" title=" 苏州林泉里精品民宿2天1晚度假游">  苏州林泉里精品民宿2天1晚度假游</a></p>
                    <p><span>￥498起</span>广州出发  2天1晚</p>
                </div>
            </li>
            <li>日本</li>
            <li>毛里求斯</li>
            <li>意大利</li>
            <li>奥克兰</li>
        </ul>
        </div>
     </div>
	 
    <!-- 独家定制 -->
         <div class="i-f wd980">
        <h3 class="t">主题活动</h3>
		 <div class="b">
          <ol id="iFt" class="i-f-t">
            <li class="curr">亲近自然</li>
            <li>亲子乐园</li>
            <li>农家采摘</li>
            <li>城市观光</li>
           
        </ol>
        <ul class="i-f-c">
            <li class="curr">
                <a class="more" href="http://www.ipyue.com/freetour">更多主题活动的推荐》</a>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/39"><img src="http://www.ipyue.com/Public/img/index/7.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/39" title="观飞云山麓之景，深呼吸新鲜空气，开启祈福之旅">观飞云山麓之景，深呼吸新鲜空气，开启祈福之旅</a></p>
                    <p><span>￥648起</span> 东莞樟木头  1日</p>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/40"><img src="http://www.ipyue.com/Public/img/index/8.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/40" title="青山绿水里的世外桃源，独门独户枕水而眠，泡珍稀“神仙水">青山绿水里的世外桃源，独门独户枕水而眠，泡珍稀“神仙水</a></p>
                    <p><span>￥¥434起</span> 云浮龙山  2天1晚</p>
                </div>
                <div>
                   <a href="http://www.ipyue.com/freetour/detail/id/41"> <img src="http://www.ipyue.com/Public/img/index/9.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/41" title="空中园林式温泉，日沐阳光夜观星">空中园林式温泉，日沐阳光夜观星</a></p>
                    <p><span>￥317起</span> 阳江  2天1晚</p>
                </div>
                <div>
                   <a href="http://www.ipyue.com/freetour/detail/id/42"> <img src="http://www.ipyue.com/Public/img/index/10.png" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/42" title=" 住进公主的城堡，九龙湖度假洗肺，萌娃乐园玩不停"> 住进公主的城堡，九龙湖度假洗肺，萌娃乐园玩不停</a></p>
                    <p><span>￥624起</span>广州九龙  2天1晚</p>
                </div>
            </li>
            <li>日本</li>
            <li>毛里求斯</li>
            <li>意大利</li>
            <li>奥克兰</li>
        </ul>
        </div>
     </div>
     <div id="iF" class="i-f wd980">
        <h3 class="t">境外自由行</h3>
        <div class="b">
          <ol id="iFt" class="i-f-t">
            <li class="curr">美国</li>
            <li>日本</li>
            <li>毛里求斯</li>
            <li>意大利</li>
            <li>奥克兰</li>
        </ol>
        <ul id="iFc" class="i-f-c">
            <li class="curr">
                <a class="more" href="http://www.ipyue.com/freetour">更多美国的路线》</a>
                <div class="one">
                    <a href="http://www.ipyue.com/freetour"><img src="__PUBLIC__/images/index-2.jpg" alt="" /></a>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/29"><img src="http://www.ipyue.com/Public/img/1.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/29" title="美国童子军14日王牌强者训练营">美国童子军14日王牌强者训练营</a></p>
                    <p><span>￥29800</span> 广州出发  14日</p>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/19"><img src="http://www.ipyue.com/Public/img/2.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/19" title="美国西岸一号公路自驾十天自由行">美国西岸一号公路自驾十天自由行</a></p>
                    <p><span>￥10999</span> 广州出发  10日</p>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/22"><img src="http://www.ipyue.com/Public/img/3.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/22" title="15天14晚美西精华大环线深度游"> 15天14晚美西精华大环线深度游</a></p>
                    <p><span>￥54688</span> 广州出发  15日</p>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/26"><img src="http://www.ipyue.com/Public/img/4.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/26" title="迪拜阿布扎比沙迦3晚5日自由行">迪拜阿布扎比沙迦3晚5日自由行</a></p>
                    <p><span>￥5090</span> 广州出发  5日</p>
                </div>
                <div>
                   <a href="http://www.ipyue.com/freetour/detail/id/24"> <img src="http://www.ipyue.com/Public/img/6.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/24" title="曼谷+芭堤雅5晚7日自由行">曼谷+芭堤雅5晚7日自由行</a></p>
                    <p><span>￥1298</span> 广州出发  7日</p>
                </div>
                <div>
                   <a href="http://www.ipyue.com/freetour/detail/id/25"> <img src="http://www.ipyue.com/Public/img/5.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/25" title=" 悉尼-墨尔本7晚9日自由行">  悉尼-墨尔本7晚9日自由行</a></p>
                    <p><span>￥4699</span>广州出发  9日</p>
                </div>
            </li>
            <li>日本</li>
            <li>毛里求斯</li>
            <li>意大利</li>
            <li>奥克兰</li>
        </ul>
        </div>
     </div>

    <!-- 独家定制 -->
         <div class="i-f wd980">
        <h3 class="t">独家定制</h3>
		 <div class="b">
          <ol id="iFt" class="i-f-t">
            <li class="curr">法国</li>
            <li>韩国</li>
            <li>毛里求斯</li>
            <li>意大利</li>
            <li>奥克兰</li>
        </ol>
        <ul class="i-f-c">
            <li class="curr">
                <a class="more" href="http://www.ipyue.com/freetour">更多自由行的路线》</a>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/18"><img src="http://www.ipyue.com/Public/img/7.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/18" title="【五一】美国洛杉矶+拉斯维加斯6晚8日自由行"> 匈牙利、斯洛文尼亚、克罗地亚、波黑、黑山、塞尔维亚六国往返13天文景游</a></p>
                    <p><span>￥6800</span> 广州出发  8日</p>
                </div>
                <div>
                    <a href="http://www.ipyue.com/freetour/detail/id/17"><img src="http://www.ipyue.com/Public/img/8.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/17" title="法国意大利8晚10日自由行">法国意大利8晚10日自由行</a></p>
                    <p><span>￥8599</span> 广州出发  10日</p>
                </div>
                <div>
                   <a href="http://www.ipyue.com/freetour/detail/id/13"> <img src="http://www.ipyue.com/Public/img/9.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/13" title="迪拜-沙迦-阿布扎比3晚6日跟团游全程五星级住宿">迪拜-沙迦-阿布扎比3晚6日跟团游全程五星级住宿</a></p>
                    <p><span>￥6800</span> 广州出发  6日</p>
                </div>
                <div>
                   <a href="http://www.ipyue.com/freetour/detail/id/20"> <img src="http://www.ipyue.com/Public/img/10.jpg" alt="" /></a>
                    <p><a href="http://www.ipyue.com/freetour/detail/id/20" title=" 国黄石公园10天自驾游"> 国黄石公园10天自驾游</a></p>
                    <p><span>￥12999</span>广州出发  10日</p>
                </div>
            </li>
            <li>日本</li>
            <li>毛里求斯</li>
            <li>意大利</li>
            <li>奥克兰</li>
        </ul>
        </div>
     </div>
    <div id="cont">
        <div class="wd980 contMain">
            <!--首页左边栏-->
            <div class="left contlt">
               <!--特价机票-->
                              <div id="index_special_tic">
					<div id="st_head">
                    	<h2>品悦服务时间：<span>9:00-21:00(周一至周五)</span>，<span>9:00-18:00(周六日)</span></h2>
                        <div id="st_departcity">
                        	<a class="bln active" dc="gz">广州出发</a>
                            <a dc="bj">北京出发</a>
                            <a dc="sh">上海出发</a>
                            <a dc="xg">香港出发</a>
                            <a dc="qt">其他城市出发</a>
                        </div>
                    </div>
                    <div id="st_main">
                    	<div id="st_destination">
                        <?php if(is_array($zhou)): foreach($zhou as $k=>$v): ?><a href="javascript:;" onclick="gettj('<?php echo ($v["zhou"]); ?>',this)" <?php if($k==0): ?>class="active"<?php endif; ?> <?php if($k==5): ?>class="bbn"<?php endif; ?>><?php echo ($v["zhou"]); ?></a><?php endforeach; endif; ?>
                        </div>
                        <div id="st_list">
                    	<p id="loadWaitImg" ><img src="__PUBLIC__/images/loading_animation.gif" width="37" height="37"/></p>
                        <?php if(is_array($list)): foreach($list as $k=>$v): ?><ul id='from<?php echo ($k); ?>' <?php if($k!='gz'): ?>style="display:none"<?php endif; ?>>
                            <?php if(is_array($v)): foreach($v as $kk=>$vv): ?><li>
                                    <span class="span0 <?php if(!empty($vv["img"])): ?>link1<?php endif; ?>"><a class="a0"><?php echo ($vv["from_city"]); ?></a><a class="spr3 rec"></a><a class="a1"><?php echo ($vv["to_city"]); ?></a></span>
                                    <span class="span1"><a class="zz"><?php if($vv['hub']): ?>中转<?php else: ?>&nbsp;<?php endif; ?></a></span>
                                    <span class="span2"><a class="time"><?php echo ($vv["time"]); ?></a><a>截止</a></span>
                                    <span class="span3"><a class="sign"><img src="__PUBLIC__/images/df_10.gif" alt="sign" /></a><a><?php echo ($vv["air"]); ?></a></span>
                                    <span class="span4"><a>往返</a><a class="jg">￥<?php echo ($vv["price"]); ?></a></span>
                                    <span class="end span5"><a class="spr3 yd" href="javascript:;" onclick="open_kf()" >预订</a></span>
                                    <!--WHF新增的showAdImg-->
                                <!--    <?php if(!empty($vv["img"])): ?><span data="__PUBLIC__/uploads/cheap/<?php echo ($vv["img"]); ?>" class="showadimg"><img src="__PUBLIC__/images/loading_animation.gif"/></span><?php endif; ?> -->
                                </li><?php endforeach; endif; ?>
                            </ul><?php endforeach; endif; ?>
                    </div><!--st_list END-->
                    </div>
               </div><!--特价机票 END-->
        <script type="text/javascript">
            function gettj(dq,t){
				$("#loadWaitImg").show();
                var send=encodeURI('dq='+dq+'&callback=?');
                $.getJSON("<?php echo U('/common/cheap');?>",send,function(data){
					$("#loadWaitImg").hide();
                    var html='';
					var data2=data.code;
                    $.each(data2, function(i,item){
						if(i!=$("#st_departcity a.active").attr('dc')){
                            var  st ='style="display:none"';
                        }
                        html +='<ul id="from'+i+'" '+st+'>';
                        $.each(item,function(i,items){
                            html+='<li>';
                           var link=items.img?'link1':'';
                            html+='<span class="span0 '+link+'" ><a class="a0">'+items.from_city+'</a><a class="spr3 rec"></a><a class="a1">'+items.to_city+'</a></span>';
                            html+= '<span class="span1"><a class="zz">&nbsp;</a></span>';
                            html+='<span class="span2"><a class="time">'+items.time+'</a><a>截止</a></span>';
                            html+= '<span class="span3"><a class="sign"><img src="__PUBLIC__/images/df_10.gif" alt="sign" /></a><a>'+items.air+'</a></span>';
                            var wfType=items.type?items.type:'往返';
                            html+= '<span class="span4"><a>'+wfType+'</a><a class="jg">￥'+items.price+'</a></span>';
                            html+= '<span class="end span5"><a class="spr3 yd" href="javascript:;" onclick="open_kf()">预订</a></span>';
							/*WHF新增的showAdImg*/
                            if(items.img){
						//	html+= ' <span data="__PUBLIC__/uploads/cheap/'+items.img+'" class="showadimg"><img src="__PUBLIC__/images/loading_animation.gif"/></span>';
                            }
                            html+= ' </li>';
                        })
                        html+= '</ul>';
                    });
                    $('#st_list').html($("#loadWaitImg")[0].outerHTML+html );

					//WHF增加的JS用于AJAX取回内容后重新显示showAdImg
					showAdImg();

                })
            }


			$('#st_departcity a').bind('click',function(){
				var from='#from'+$(this).attr('dc');
                $(this).addClass("active").siblings().removeClass("active");
				$(from).show().siblings().hide();
            })
            $('#st_destination a').bind('click',function(){
                $(this).addClass("active").siblings().removeClass("active");
            })
			

			//WHF增加的JS用于显示showAdImg
			showAdImg();
			
			function showAdImg(){
				$('#special_tic li .span0').mouseover(function(){
					//选取showadimg
					var showadimg=$(this).siblings('.showadimg');
					//取得showadimg中的data属性值
					var imgdata=showadimg.attr("data");
					//取得当前元素的到TOP的长度，取得BODY的高度
					var Y = $(this).offset().top,H = $('body').height();
					if((H-Y) < 400){
						//向上显示showadimg并设置真正的地
						showadimg.css('top','-376px').show().find('img').attr('src',imgdata);
						}else{
							//向下显示showadimg并设置真正的地
							showadimg.show().find('img').attr('src',imgdata);
							};
					}).mouseout(function(){
					//隐藏showadimg
					$(this).siblings('.showadimg').hide();
					});
				}

        </script>
               
               <!--航空公司特惠-->
                              <div class="box_l" id="index_preferential">
                     <h2>航空公司特惠</h2>
                     <div class="pre_box">
                         <ul class="type_img">
                            <li><a href="javascript:;" onclick="open_kf();"><img src="__PUBLIC__/images/preferential_img_i1.jpg" /></a><span>[国泰航空]<a href="javascript:;" onclick="open_kf();">北京往返巴黎</a><br/><strong>3350元起</strong></span></li>
                            <li><a href="javascript:;" onclick="open_kf();"><img src="__PUBLIC__/images/preferential_img_i2.jpg" /></a><span>[法荷航空]<a href="javascript:;" onclick="open_kf();">上海往返伦敦</a><br/><strong>2600元起</strong></span></li>
                            <li><a href="javascript:;" onclick="open_kf();"><img src="__PUBLIC__/images/preferential_img_i3.jpg" /></a><span>[中国国航]<a href="javascript:;" onclick="open_kf();">广州往返洛杉矶</a><br/><strong>4850元起</strong></span></li>
                         </ul>
                         <ul class="type_txt">
                            <li><span>[法荷航空]</span><a href="javascript:;" onclick="open_kf();">上海往返巴黎</a><strong>3800元起</strong></li>
                            <li><span>[国泰航空]</span><a href="javascript:;" onclick="open_kf();">北京往返法兰克福</a><strong>3330元起</strong></li>
                            <li><span>[荷兰航空]</span><a href="javascript:;" onclick="open_kf();">上海往返阿姆斯特丹</a><strong>3800元起</strong></li>
                            <li><span>[澳洲航空]</span><a href="javascript:;" onclick="open_kf();">香港往返悉尼</a><strong>3320元起</strong></li>
                            <li><span>[土耳其航空]</span><a href="javascript:;" onclick="open_kf();">广州往返休斯敦</a><strong>5700元起</strong></li>
                            <li><span>[中华航空]</span><a href="javascript:;" onclick="open_kf();">香港往返东京</a><strong>2750元起</strong></li>
                            <li><span>[海南航空]</span><a href="javascript:;" onclick="open_kf();">北京往返西雅图</a><strong>4880元起</strong></li>
                            <li><span>[维珍航空]</span><a href="javascript:;" onclick="open_kf();">香港往返曼彻斯特</a><strong>3180元起</strong></li>
                            <li><span>[全日空]</span><a href="javascript:;" onclick="open_kf();">杭州往返东京</a><strong>2630元起</strong></li>
                            <li><span>[中国国航]</span><a href="javascript:;" onclick="open_kf();">广州往返首尔</a><strong>2050元起</strong></li>
                         </ul>
                  </div>
               </div>
               
            </div><!--首页左边栏 END-->
            
            
            <div class="right contrt">
            	<!--他们正在查...-->
                <div class="box_r">
                    <h2><strong>他们正在查...</strong></h2>
                    <ul id="find_flight">
                        <?php if(is_array($while_search)): $i = 0; $__LIST__ = $while_search;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('/'); echo ($vo["url"]); ?>" title="<?php echo ($vo["from_city"]); ?> 至 <?php echo ($vo["to_city"]); ?>"><?php echo cutStr($vo['from_city'].'-'.$vo['to_city'],7);?></a><strong><?php echo ($vo["type"]); ?></strong><em><?php echo ($vo["from_now"]); ?>前</em></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div><!--他们正在查 END-->
                
                <!--我们的十大优势-->
                <div class="box_r">
                    <h2>我们的十大优势</h2>
                    <ul id="service">
                    	<li><a href="<?php echo U('/about/toubu');?>#step1" target="_blank" class="spr1 a0"><label>信息真实</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step2" target="_blank" class="spr1 a1"><label>机票专营</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step3" target="_blank" class="spr1 a2"><label>专业顾问</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step4" target="_blank" class="spr1 a3"><label>航协认证</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step5" target="_blank" class="spr1 a4"><label>百度认证</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step6" target="_blank" class="spr1 a5"><label>资质公开</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step7" target="_blank" class="spr1 a6"><label>出票保证</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step8" target="_blank" class="spr1 a7"><label>全天服务</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step9" target="_blank" class="spr1 a8"><label>免费送票</label></a></li>
                        <li><a href="<?php echo U('/about/toubu');?>#step10" target="_blank" class="spr1 a9"><label>支付灵活</label></a></li>
                    </ul>
                </div><!--我们的十大优势 END-->
                
                <div id="web_link">
                <a href="<?php echo U('/zt/united_sales2');?>" target="_blank"><img src="__PUBLIC__/images/index-ad1.gif" /></a>
                </div>
                
                <!--机票工具箱-->
                <div class="box_r" id="chest_link">
                    <h2>机票工具箱</h2>
                    <dl>
                        <dd class="a0"><a href="<?php echo U('/member/login');?>" target="_blank">我的机票</a></dd>
                        <dd class="a1"><a href="<?php echo U('/iflight/orderflight');?>" target="_blank">低价预约</a></dd>
                        <dd class="a2"><a href="<?php echo U('/help/members#help_ul3');?>" target="_blank">订票攻略</a></dd>
                    </dl>
                    <ul>
		                <li><label class="spr2 a0"></label><a rel="nofollow" href="http://www.tianqi.com/worldcity.html" target="_blank">天气预报</a></li>
		                <li><label class="spr2 a1"></label><a rel="nofollow" href="http://time.123cha.com/" target="_blank">国际时差</a></li>
		                <li><label class="spr2 a2"></label><a rel="nofollow" href="http://qq.ip138.com/hl.asp" target="_blank">汇率查询</a></li>
		                <li><label class="spr2 a3"></label><a rel="nofollow" href="http://baike.baidu.com/view/1618627.htm" target="_blank">旅游签证</a></li>
		                <li><label class="spr2 a4"></label><a rel="nofollow" href="http://ditu.google.cn/" target="_blank">电子地图</a></li>
		                <li><label class="spr2 a5"></label><a rel="nofollow" href="http://www.travelsky.com/newsky/index.html" target="_blank">机票真伪</a></li>
	                </ul>
                </div><!--机票工具箱 END-->
                
                <script type="text/javascript">
                	$(function(){
							$('#page_nav dt').click(function(){
									evalPage(-1);
								});
							$('#page_nav dd').click(function(){
									evalPage(1);
								});
							function evalPage(numder){
									var page=parseInt($('#page_nav em').text());
									if(page+numder==0){
											page=3;
										}else if(page+numder>=4){
												page=1;
											}else{
													page=page+numder;
												}
									$('#page_nav em').text(page);
									$('#evaluate ul').eq(page-1).addClass('active').siblings().removeClass('active');
								}
						});
                </script>

            </div>
            
        </div>
        
     
        
        <div  class="wd980 infobt">
                <dl>
                	<dt>品悦介绍</dt>
                    <dd><a href="<?php echo U('/about');?>" target="_blank">公司简介</a></dd>
                    <dd><a href="<?php echo U('/about/qualifications');?>" target="_blank">荣誉资质</a></dd>
                    <dd><a href="<?php echo U('/about/events');?>" target="_blank">发展历程  </a></dd>
                    <dd><a href="<?php echo U('/about/contact');?>" target="_blank">联系我们</a></dd>
                </dl>
                <dl>
                	<dt>会员帮助</dt>
                    <dd><a href="<?php echo U('/');?>help/members#help_ul0" target="_blank">会员注册</a></dd>
                    <dd><a href="<?php echo U('/');?>help/members#help_ul1" target="_blank">会员积fun</a></dd>
                    <dd><a href="<?php echo U('/');?>help/members#help_ul2" target="_blank">密码修改</a></dd>
                    <dd><a href="<?php echo U('member/information');?>" target="_blank">资料修改 </a></dd>
                </dl>
                <dl>
                	<dt>支付方式</dt>
                    <dd><a href="<?php echo U('/');?>help/pay#help_ul0" target="_blank">转账支付</a></dd>
                    <dd><a href="<?php echo U('/');?>help/pay#help_ul1" target="_blank">在线支付</a></dd>
                    <dd><a href="<?php echo U('/');?>help/pay#help_ul2" target="_blank">刷卡支付</a></dd>
                    <dd><a href="<?php echo U('/');?>help/pay#help_ul3" target="_blank">现金支付</a></dd>
                </dl>
            <div class="blk">
            	<a class="spr bki"></a>
            </div>
        </div>
    </div>
    <!--<script type="text/javascript" src="<?php echo U('/Public/setKf');?>"></script>-->
    <!-- 投诉建议&国际机票咨询预订流程 首页右漂 -->
 <!--    <div id="comp_zxyd">
    <div class="verify"><a target="_blank" href="<?php echo U('/iflight/verify');?>">机票验真</a></div>
    <div class="comp"><a href="<?php echo U('/complaint');?>">投诉建议</a></div>
    <div class="zxyd"><a href="<?php echo U('/Process/zxyd');?>">咨询预订<br/>流程</a></div>
  </div>   --> 
<!--    <script type="text/javascript">
    $(function(){
		$(window).scroll(function(){
			var top=$(this).scrollTop()+440+'px';
			$('#comp_zxyd').css('top',top);
			});
		});
    </script>-->
   ﻿ <div id="footer">
    	<div class="wd960">
            <div id="ft_link">
                    <a href="<?php echo U('/about');?>">关于我们</a>
                    <a href="<?php echo U('/about/duty');?>">免责声明</a>
                    <a href="<?php echo U('/about/privacy');?>">隐私保护</a>
                    <a href="<?php echo U('/about/job');?>">诚聘英才</a>
                    <a href="<?php echo U('/about/contact');?>">联系我们</a>
            </div>
            <?php
 if($_SERVER['HTTP_HOST']=='www.aishangfei.cn'){ $bah="粤ICP备11009654号-7"; }elseif($_SERVER['HTTP_HOST']=='www.aishangfei.org'){ $bah="粤ICP备11009654号-9"; }elseif($_SERVER['HTTP_HOST']=='www.aishangfei.com.cn'){ $bah="粤ICP备13026678号-1"; }else{ $bah="粤ICP备15024109号-1"; } ?>
            <div id="ft_copyright">Copyright © 2014-2017 品悦旅行网 All Rights Reserved
                <img src="__PUBLIC__/images/ft_icp.jpg" alt="icp" />
                <a href="http://www.miitbeian.gov.cn/" target="_blank"><?php echo ($bah); ?></a>
            </div>
            <div id="ft_icons">
                <!-- <a class="a0" href="#" target="_blank"></a> -->
                <!-- <a class="a1" href="#" target="_blank"></a> -->
                <a class="a2" href="#" target="_blank"></a>
                <a class="a3" href="http://trust.360.cn/search.php" target="_blank"></a>
                <a class="a4" href="#" target="_blank"></a>
                <a class="a5" href="http://www.gzaic.gov.cn/GZCX/WebUI/credit/qiyeInfo.htm#" target="_blank"></a>
                <a class="a6" href="http://net.china.com.cn/index.htm" target="_blank"></a>
                <a class="a7" href="http://www.cyberpolice.cn/wfjb/" target="_blank"></a>
            </div>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?646713249fd3ceeab66e16bc78c8e814";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

</body>
</html>