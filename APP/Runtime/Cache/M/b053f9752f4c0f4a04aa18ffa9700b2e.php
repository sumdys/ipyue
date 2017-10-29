<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>专业旅行顾问-品悦手机网</title>
<link href="__PUBLIC__/mobile/css/global.css" rel="stylesheet" type="text/css">
<style type="text/css">
#topnav{padding-right:42px;}
#adviser{margin-top:10px;}
#tab a{display:inline-block;text-align:center;width:22%;line-height:29px;color:#FFF;background-color:#67b1e4;cursor:pointer;}
#tab a:hover,#tab a.on{color:#ff840f;background-color:#FFF;}
#tab a.mar{margin-right:4%;}
#result{padding:15px 15px 0 15px;line-height:30px;display:none;background-color:#FFF;}
#adviser_list{background-color:#FFF;}
#adviser_list li{border-bottom:1px solid #e7e5e5;padding:16px;}
#adviser_list li .avatar,#adviser_list li .infos,#adviser_list li .star a{display:inline-block;}
#adviser_list li .avatar img{width:82px;height:82px;}
#adviser_list li .infos{padding-left:15px;}
#adviser_list li .infos a.name{color:#ff6600;font-weight:bold;line-height:24px}
#adviser_list li .infos div{display:block;line-height:32px;color:#666;}
#adviser_list li .infos div a{display:inline-block;}
#adviser_list li .kfpj,#adviser_list li .pj{width:100%;display:block;color:#666;line-height:32px;}
#adviser_list li .star a{margin-right:5px;background:url(__PUBLIC__/mobile/images/pingjia_dj.gif) no-repeat 0px 0px;width:59px;height:11px;}
#adviser_list li .star a.a10{background-position:0 0px}
#adviser_list li .star a.a9{background-position:0 -20px}
#adviser_list li .star a.a8{background-position:0 -40px}
#adviser_list li .star a.a7{background-position:0 -60px}
#adviser_list li .star a.a6{background-position:0 -80px}
#adviser_list li .star a.a5{background-position:0 -100px}
#adviser_list li .star a.a4{background-position:0 -120px}
#adviser_list li .star a.a3{background-position:0 -140px}
#adviser_list li .star a.a2{background-position:0 -160px}
#adviser_list li .star a.a1{background-position:0 -180px}
#adviser_list li .star a.a0{background-position:0 -200px}
#adviser_list li .kfpj .aa a{color:#06C;padding-left:10px;width:100px;display:inline-block;}
#adviser_list li .pj span{line-height:16px;height:16px;padding:4px 0 4px 20px;background:url(__PUBLIC__/mobile/images/pj.gif) no-repeat 0px 0px;display:inline-block;}
#adviser_list li .pj .hp{background-position:0 4px;}
#adviser_list li .pj .zp{background-position:0 -16px;}
#adviser_list li .pj .cp{background-position:0 -36px;}
</style>
</head>

<body>
<div id="page">
  <div id="content">
    <h1 id="topnav"><span class="left icon"><a href="<?php echo U('/');?>" class="icon-home">首页</a></span>专业旅行顾问</h1>
    <div class="box">
      <form action="" method="post">
        <span class="pad10"><input name="condition" type="text" id="condition" required placeholder="请输入顾问姓名、手机号或电话（不含区号）" class="txt"></span>
        <input type="submit" value="搜索" class="sub">
      </form>
      <div id="adviser">
        <div id="tab"><a class="mar on" company="1">广州</a><a class="mar" company="2">上海</a><a class="mar" company="3">北京</a><a company="4">深圳</a></div>
        <div id="result"><strong>搜索结果：</strong></div>
        <ul id="adviser_list" company="1" page="1" searchvar="">
            <?php if(is_array($userlist)): $i = 0; $__LIST__ = $userlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
                <span class="avatar"><img src="__PUBLIC__/uploads<?php if($v['avatar']): echo ($v["avatar"]); else: ?>/avatar/default.gif<?php endif; ?>"></span>
                <span class="infos">
                  <a href="<?php echo U('/Adviser/review/id/'.$v[id]);?>" class="name"><?php echo ($v["name"]); ?></a>
                      <div>QQ：<label><?php echo ($v["qq"]); ?></label></div>
                      <div>手机号码：<label><a  href="tel:<?php echo ($v["public_mobile"]); ?>"><?php echo ($v["public_mobile"]); ?></a></label></div>
                      <div>电话号码：<label><a  href="tel:<?php echo ($v["telephone"]); ?>"><?php echo ($v["telephone"]); ?></a></label></div>
                </span>
                <span class="kfpj">
                  <label>服务评价：</label>
                  <label class="star" title="五颗星"><a class="a<?php echo ($v["serverImg"]); ?>"></a><?php echo ($v["server"]); ?></label>
                  <label class="aa"><a href="<?php echo U('/Adviser/review/id/'.$v[id]);?>">查看所有评价</a></label>
                </span>
              </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
      <div id="loading"><img src="__PUBLIC__/mobile/images/loading.gif"></div>
      <div id="reminder"></div>
    </div>
  </div>
    <div id="footer">
    <!-- <div id="telservice">
    <a href="javascript:open_kf()" class="service">在线咨询</a>
    <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=test" target="_blank" class="qq">QQ咨询</a>
    <a href="tel:4006085188" class="tel"></a>
    </div> -->
    <div class="nav"><a href="<?php echo U('/About/faq');?>">常见问题</a><!--<a href="<?php echo U('/Verify');?>" class="bor">机票验真</a>--><a href="<?php echo U('/Complaint');?>" class="bor">意见反馈</a><a href="http://www.ipyue.com/" target="_blank">电脑版</a></div>
    <div id="copyright">@ 2016 品悦网</div>
  </div>


</div>
<script type="text/javascript" src="__PUBLIC__/mobile/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
$(function(){
	//搜索顾问
	$('form').submit(function(){
		var searchvar=$('#condition').val(),company=0,page=1,addto=0;
		if(searchvar!=""){
			$('#adviser_list').attr('company',0);//设置company地区代为0
			$('#adviser_list').attr('searchvar',searchvar);//设置searchvar的值为搜索内容
			$('#result').show();
			ajax_list(company,page,addto,searchvar);
			}else{
				alert("搜索内容不能为空！");
				}
			return false;
		});
	
	//点击地区刷新顾问
	$('#tab a').click(function(){
		$('#adviser_list').html("");//先清空内容
		var company=$(this).attr('company'),page=1,searchvar="",addto=0;//company为地区代码;page为页数;addto为1是追加数据,0为更迭数据
		$('#result').hide();
		$(this).addClass('on').siblings().removeClass('on');
		$('#adviser_list').attr({company:company,page:page,searchvar:searchvar});
		ajax_list(company,page,addto,searchvar);
		});
	//自动刷新内容
	$(window).scroll(function(){
		//被卷去的高度
		var scrollTop=$(this).scrollTop();
		//可视区域高度
		var viewHeight=$(window).height();
		//页面高度
		var pageHeight=$('body').height();
		if((scrollTop+viewHeight)>=(pageHeight)){
			var company=$('#adviser_list').attr('company');//company为地区代码
			var searchvar=$('#adviser_list').attr('searchvar');//获取searchvar的内容
			var page=parseInt($('#adviser_list').attr('page'))+1,addto=1;//page为页数;addto为1是追加数据,0为更迭数据
			ajax_list(company,page,addto,searchvar);//调用AJAX的函数
			$('#adviser_list').attr('page',page);//更新分页数
			}
		});
	});
	
//AJAX函数
function ajax_list(company,page,addto,searchvar){
	$('#loading').show();//显示加载中
	$('#reminder').hide();
	$.getJSON("<?php echo U('/Adviser');?>",{company:company,p:page,search:searchvar},function(data){
		if(data.status==1){
			var html='',adviser=data.list,reviewUrl="<?php echo U('/Adviser/review/id');?>";
			$.each(adviser,function(i,item){
				html += '<li>';
				html += '<span class="avatar"><img src="__PUBLIC__/uploads'
				if(item.avatar){
					html += item.avatar;
					}else{
						html += '/avatar/default.gif';
						}
				html += '"></span>';
				html += '<span class="infos">';
				html += '<a href="'+reviewUrl+item.id+'" class="name">'+item.name+'</a>';
                html += '<div>QQ：<label>'+item.qq+'</label></div>';
				html += '<div>手机号码：<label><a href="tel:'+item.public_mobile+'">'+item.public_mobile+'</a></label></div>';
				html += '<div>电话号码：<label><a href="tel:'+item.telephone+'">'+item.telephone+'</a></label></div>';
				html += '</span>';
				html += '<span class="kfpj">';
				html += '<label>服务评价：</label>';
				html += '<label class="star" title="五颗星"><a class="a'+item.serverImg+'"></a>'+item.server+'</label>';
				html += '<label class="aa"><a href="'+reviewUrl+'/'+item.id+'">查看所有评价</a></label>';
				html += '</span>';
				html += '</li>';
				});
				if(addto==0){
					$('#loading').hide();
				    $('#adviser_list').html(html);
				}else{
					$('#loading').hide();
					$('#adviser_list').append(html);
					}
			}else{
				$('#loading').hide();
				$('#reminder').show().text(data.info);
				}
		});
	}
</script>
<style type="text/css">
    #mobile_icon_div{display:none!important;}
</style>
<script type="text/javascript">
    function open_kf(){
    <?php if($userinfo): ?>
        //   window.open('tel:<?php echo ($userinfo["user"]["public_mobile"]); ?>');
        location.href='tel:<?php echo ($userinfo["user"]["public_mobile"]); ?>';
    <?php else: ?>
      //  location.href='tel:400-608-5188';
     //   window.open('http://m.53kf.com/70697090/54/1');
        var kf_url=$("#mobile_icon_div").attr("href")?$("#mobile_icon_div").attr("href"):"http://m.53kf.com/70697090/54/1";
        location.href=kf_url;
    <?php endif;?>
    }
</script>
<div style="display: none">
    <script type='text/javascript' src='http://tb.53kf.com/kf.php?arg=aaa&style=4'></script>
</div>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?6af3a968236e1a82336482ca0d41a71f";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

</body>
</html>