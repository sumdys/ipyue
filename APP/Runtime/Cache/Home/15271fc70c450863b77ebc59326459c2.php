<?php if (!defined('THINK_PATH')) exit();?><script type="text\/javascript">
    function open_kf(){
    <?php if($userinfo): ?>
        window.open('http://wpa.qq.com/msgrd?v=3&uin=<?php echo (trim($userinfo["user"]["qq"])); ?>&site=qq&menu=yes');
    <?php else: ?>
        setIsinvited();hz6d_cus_web_msg_open();
    <?php endif;?>
    }
  //  var uid=<?php echo ($userinfo['id']); ?>;
</script>

<?php if($userinfo): ?><span class="left">
    	<a>您好,</a>
    	<a title="进入会员中心" href="<?php echo U('/Member/index');?>"><?php if($userinfo['name']): echo ($userinfo["name"]); else: echo ($userinfo["username"]); endif; ?></a>
    	<span title="<?php echo ($userinfo["rank"]["name"]); ?>" class="mgi m<?php echo ($userinfo["rank_id"]); ?>"><?php echo ($userinfo["rank"]["name"]); ?></span>
		<a href="<?php echo U('/member/common/out');?>">退出</a>
    </span>
<?php else: ?>
    <span class="left">
    	<a>您好，欢迎光临品悦旅行网</a>
        <a href="<?php echo U('/member/login');?>">登录</a>
        <a href="<?php echo U('/member/register');?>">注册</a>
	</span><?php endif; ?>
<span class="right">
 <?php if($userinfo): ?><a id="myAisf_w" href="<?php echo U('/member');?>">我的品悦</a>
     <ul id="myAisf_u">
         <li><a href="<?php echo U('/member/booking');?>">我的订单</a></li>
         <li><a href="<?php echo U('/member/Setting/myinfo');?>">个人资料</a></li>
		  <li><a  target="_blank" href="http://wpa.qq.com/msgrd?V=3&uin=1392660499&Site=QQ客服&Menu=yes" >咨询我的顾问</a></li>
         <li><a href="<?php echo U('/member/Setting/password');?>">修改密码</a></li>
     </ul>
     <a id="myAisf_t"></a><?php endif; ?>
    <a class="spr gz"></a><a href="http://weibo.com/315439998?from=py" class="ml0" target="_blank">关注我们</a><a class="spr wx"></a><a href="javascript:;" class="ml0" id="weixin_me">微信我们</a><!--<a href="<?php echo U('/iflight/verify');?>" class="ts">机票验真</a>--><a href="<?php echo U('/help');?>" class="bz">帮助中心</a><a class="spr dh"></a>
</span>