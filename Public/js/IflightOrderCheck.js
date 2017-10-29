$(function(){
	//显示或隐藏航班信息
	$(".more_journey").click(function(){
		var ifbox=$(this).prev();
		if($(this).hasClass("mj_show")){
			$(this).removeClass("mj_show");
			ifbox.hide();
		}else{
			$(this).addClass("mj_show");
			ifbox.show();
		}
	});

	//显示退改签信息
	$(document).on("mouseenter",".line_tgq",function(){
		var title=$(this).attr("title");
		var top=$(this).offset().top+15,left=$(this).offset().left;
		$(".line_tgq_box").css({"top":top,"left":left}).show().find("p").html(title);
	});
	$(document).on("mouseleave",".line_tgq",function(){
		$(".line_tgq_box").hide().find("p").html("");
	});

	/*会员登录弹窗*/
	$("#username").focus(function(){
		if($(this).val() == "用户名/手机"){
			$(this).val("");
		}
	});
	$("#username").blur(function(){
		if($(this).val() == ""){
			$(this).val("用户名/手机")
		}
	});
	$("#close").click(function(){
		$("#ifLoginPopup").hide();
		$("#ifTier").hide();
	});
	$(window).scroll(function(){
		var top  = $(this).scrollTop()+100 + 'px';
		var left = ($(this).width()-400)/2 + 'px';
		var loadTop  = $(this).scrollTop()+200 + 'px';
		var loadLeft = ($(this).width()-190)/2 + 'px';
		$("#ifLoginPopup").css({top:top,left:left});
		$("#ifLoading").css({top:loadTop,left:loadLeft});
		$("#ifTier").css({width:$("body").width(),height:$("body").height(),opacity:0.60});

	});



	// //
	// $("#flightOrderCheck").submit(function(){
	// 	//获得cookie的uid
	// 	// var uid=getCookie("uid");
	// 	// //获取cookie
	// 	// function getCookie(c_name){
	// 	// 	if(document.cookie.length>0){
	// 	// 		c_start=document.cookie.indexOf(c_name + "=");
	// 	// 		if(c_start!=-1){
	// 	// 			c_start=c_start + c_name.length+1 ;
	// 	// 			c_end=document.cookie.indexOf(";",c_start);
	// 	// 			if (c_end==-1){
	// 	// 				c_end=document.cookie.length;
	// 	// 			}
	// 	// 			return unescape(document.cookie.substring(c_start,c_end));
	// 	// 		} 
	// 	// 	}else{
	// 	// 		return "";
	// 	// 	}		
	// 	// }

	// 	// //通过uid判断是否登录
	// 	// if(uid==""||uid==undefined){
	// 	// 	//如果为空，判断未登录，显示弹窗
	// 	// 	$("#ifTier").show();
	// 	// 	$("#ifLoginPopup").show();
	// 	// 	return false;
	// 	// }else{
	// 	// 	return true;
	// 	// }
	// 	$.getJSON(BaseInfo.path+"/Iflight/flightOrderCheck",{login:"query"},function(data){
	// 		if(data.status==0){
	// 			// 判断未登录，显示弹窗
	// 			$("#ifTier").show();
	// 			$("#ifLoginPopup").show();
	// 			$("#foSub").attr("state","0");
	// 		}else if(data.status==1){
	// 			$("#foSub").attr("state","1");
	// 		}
	// 	});
	// 	var state=$("#foSub").attr("state");
	// 	if(state=="0"){
	// 		alert(0);
	// 		return false;
	// 	}else if(state=="1"){
	// 		alert(1);
	// 		return true;
	// 	}
	// });


	//支付
	$("#foSub").click(function(){
		$.getJSON("/Iflight/flightOrderCheck",function(data){
			if(data.status==0){
				// 判断未登录，显示弹窗
				$("#ifTier").show();
				$("#ifLoginPopup").show();
			}else if(data.status==1){
				$("#ifTier").show();
                $("#ifLoading").show();
				$("#flightOrderCheck").submit(function(){}).trigger("submit");
			}
		});
	});

	//通过弹窗进行登录
	$("#ifLoginPopup .login_sub").click(function(){
		var username=$("#username").val(),userpassword=$("#userpassword").val(),usercode=$("#usercode").val();
		var type='hy';
		$.ajax({
            type:'post',
            // url:BaseInfo.path+"Member/Login",
            url:"/Iflight/flightOrderCheck",
            dataType: "json",
            data:{act:'login',name:username,password:userpassword,verify_code:usercode},
            success: function(data){
                if(data.status==1){
                     $("#ifLoginPopup").hide();
                    // $("#ifTier").hide();
                    $("#ifLoading").show();
                    $("#flightOrderCheck").submit(function(){}).trigger("submit");
                }else{
                    alert(data.info);
                }
            }
		});
	});

});