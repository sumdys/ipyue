$(function(){
	// 获取页面的高度
	var H=$(document).height();

	// 显示姓名填写说明
	$("#flight_details").click(function(){
		$("#line_tgq_box").height(H).show();
		$(document).scrollTop(0);
	});
	// 关闭姓名填写说明
	$("#close_line_tgq").click(function(){
		$("#line_tgq_box").hide();
		$(document).scrollTop(H);
	});

	// 删除乘机人
	$(document).on("click","#boarding .bi_del",function(){
		$(this).parent("dd").remove();
		countCost();//计算费用
	}); 
	

	// 点击修改乘机人
	$(document).on("click","#boarding .bi_edit",function(){
		editBoarding($(this),1);
	});

	// 打开选择乘机人弹窗
	$("#open_select_boarding").click(function(){
		$("#select_boarding_box").height(H).show();
		$(document).scrollTop(0);
	});
	// 关闭选择乘机人弹窗
	$("#close_select_list").click(function(){
		$("#select_boarding_box").hide();
		$(document).scrollTop(H);
	});
	// 多选择常用乘机人
	$("#select_boarding .checkbox").click(function(){
		var span=$(this).find("span");
		if(span.hasClass("active")){
			span.removeClass("active");
		}else{
			span.addClass("active");
		}
	})
	// 修改常用乘机人
	$(document).on("click","#select_boarding .edit",function(){
		editBoarding($(this),2);
	});
	// 点击遍历常用乘机人
	$("#select_boarding_sub").click(function(){
		// actNo为存储以选定的常用乘机人个数
		var actNo=0,liNo=$("#select_boarding li").size(),html="";
		$("#select_boarding li").each(function(){
			var surName=$(this).attr("surName"),givenName=$(this).attr("givenName"),passengerType=$(this).attr("passengerType"),dobDate=$(this).attr("dobDate"),CountryCode=$(this).attr("CountryCode"),sex=$(this).attr("sex"),credentialsType=$(this).attr("credentialsType"),ncredentialsNo=$(this).attr("ncredentialsNo"),ctDate=$(this).attr("ctDate");
			var span=$(this).find("span");
			if(span.hasClass("active")){
				actNo+=1;
				html+='<dd  surName="'+surName+'" givenName="'+givenName+'" passengerType="'+passengerType+'" dobDate="'+dobDate+'" CountryCode="'+CountryCode+'" sex="'+sex+'" credentialsType="'+credentialsType+'" ncredentialsNo="'+ncredentialsNo+'" ctDate="'+ctDate+'">';
				html+='<input type="hidden" name="TravelerInfo[surName][]" value="'+surName+'">';
				html+='<input type="hidden" name="TravelerInfo[givenName][]" value="'+givenName+'">';
				html+='<input type="hidden" name="TravelerInfo[passengerType][]" value="'+passengerType+'">';
				html+='<input type="hidden" name="TravelerInfo[dobDate][]" value="'+dobDate+'">';
				html+='<input type="hidden" name="TravelerInfo[CountryCode][]" value="'+CountryCode+'">';
				html+='<input type="hidden" name="TravelerInfo[sex][]" value="'+sex+'">';
				html+='<input type="hidden" name="TravelerInfo[credentialsType][]" value="'+credentialsType+'">';
				html+='<input type="hidden" name="TravelerInfo[ncredentialsNo][]" value="'+ncredentialsNo+'">';
				html+='<input type="hidden" name="TravelerInfo[ctDate][]" value="'+ctDate+'">';
				html+='<div class="bi_del"></div>';
				html+='<ul>';
  				html+='<li><span>姓名</span>'+surName+'/'+givenName+'</li>';
  				html+='<li><span>证件类型</span>'+credentialsType+'</li>';
  				html+='<li><span>证件号码</span>'+ncredentialsNo+'</li>';
				html+='</ul>';
				html+='<div class="bi_edit">修改</div>';
				html+='</dd>';
				$(this).remove();
			}
		});
		if(actNo>=1){
			$("#boarding").append(html);
			if(actNo==liNo){$("#select_boarding_sub").remove();}
			countCost();//计算费用
		}else{
			alert("请选择常用乘机人！");
		}
	});




	// 新增乘机人&修改乘机人共用
	// 选择乘客类型
	$(".passenger_type").click(function(){
		var val=$(this).attr("value"),input=$(this).parent("li").find("input");
		$(this).addClass("active").siblings().removeClass("active");
		input.val(val);
	});
	// 选择性别
	$(".sexr").click(function(){
		var val=$(this).attr("value"),input=$(this).parent("li").find("input");
		$(this).addClass("active").siblings().removeClass("active");
		input.val(val);
	});
	// 显示姓名填写说明
	$(".doubt").click(function(){
		$("#name_explain_box").height(H).show();
		$(document).scrollTop(0);
	});
	// 关闭姓名填写说明
	$("#close_name_explain").click(function(){
		$("#name_explain_box").hide();
		$(document).scrollTop(0);
	});



	// 显示新增乘机人弹窗
	$("#add_boarding").click(function(){
		var H=$(document).height();
		initializeAddBoarding("#boarding_info");// 初始化
		$("#boarding_info_box").height(H).show();
		$(document).scrollTop(0);
		$("#select_boarding_box").hide();// 关闭选择乘机人弹窗
	});
	// 关闭新增乘机人弹窗
	$("#close_info_list").click(function(){
		var H=$(document).height();
		initializeAddBoarding("#boarding_info");// 初始化
		$("#boarding_info_box").hide();
		$(document).scrollTop(H);
	});
	// 选择签发国家
	$("#add_boarding_country_code").click(function(){
		selectCountryCode("#add_boarding_country_code");
	});
	// 增加乘机人
	$("#boarding_info_sub").click(function(){
		var surName=$("#boarding_info .surName").val(),givenName=$("#boarding_info .givenName").val(),passengerType=$("#boarding_info .passengerType").val(),dobDate=$("#boarding_info .dobDate").val(),CountryCode=$("#boarding_info .CountryCode").val(),sex=$("#boarding_info .sex").val(),credentialsType=$("#boarding_info .credentialsType").val(),ncredentialsNo=$("#boarding_info .ncredentialsNo").val(),ctDate=$("#boarding_info .ctDate").val();
		if(surName==""||!/^[a-zA-Z]*$/.test(surName)||surName.length<2){
			alert("姓(Surname)错误！");
			return false;
		}else if(givenName==""||!/^[a-zA-Z]*$/.test(givenName)||givenName.length<2){
			alert("名(Given names)错误！");
			return false;
		}else if(dobDate==""||!/^\d{4}-\d{2}-\d{2}$/.test(dobDate)){
			alert("出生日期错误！");
			return false;
		}else if(ncredentialsNo==""||!/^1[45][0-9]{7}|G[0-9]{8}|P[0-9]{7}|S[0-9]{7,8}|D[0-9]+$/.test(ncredentialsNo)){
			alert("证件号码期错误！");
			return false;
		}else if(ctDate==""||!/^\d{4}-\d{2}-\d{2}$/.test(ctDate)){
			alert("证件有效期错误！");
			return false;
		}else{
			var html="";
			html+='<dd  surName="'+surName+'" givenName="'+givenName+'" passengerType="'+passengerType+'" dobDate="'+dobDate+'" CountryCode="'+CountryCode+'" sex="'+sex+'" credentialsType="'+credentialsType+'" ncredentialsNo="'+ncredentialsNo+'" ctDate="'+ctDate+'">';
			html+='<input type="hidden" name="TravelerInfo[surName][]" value="'+surName+'">';
			html+='<input type="hidden" name="TravelerInfo[givenName][]" value="'+givenName+'">';
			html+='<input type="hidden" name="TravelerInfo[passengerType][]" value="'+passengerType+'">';
			html+='<input type="hidden" name="TravelerInfo[dobDate][]" value="'+dobDate+'">';
			html+='<input type="hidden" name="TravelerInfo[CountryCode][]" value="'+CountryCode+'">';
			html+='<input type="hidden" name="TravelerInfo[sex][]" value="'+sex+'">';
			html+='<input type="hidden" name="TravelerInfo[credentialsType][]" value="'+credentialsType+'">';
			html+='<input type="hidden" name="TravelerInfo[ncredentialsNo][]" value="'+ncredentialsNo+'">';
			html+='<input type="hidden" name="TravelerInfo[ctDate][]" value="'+ctDate+'">';
			html+='<div class="bi_del"></div>';
			html+='<ul>';
  			html+='<li><span>姓名</span>'+surName+'/'+givenName+'</li>';
  			html+='<li><span>证件类型</span>'+credentialsType+'</li>';
  			html+='<li><span>证件号码</span>'+ncredentialsNo+'</li>';
			html+='</ul>';
			html+='<div class="bi_edit">修改</div>';
			html+='</dd>';
			$("#boarding").append(html);
			var H=$(document).height();
			$("#boarding_info_box").hide();
			$(document).scrollTop(H);
			countCost();//计算费用
		}
	});


	// 提交表单
	$("#payment").click(function(){
		var contactsName=$("#contactsName").val(),cellPhone=$("#cellPhone").val(),boardingNo=$("#boarding dd").size();
		if(boardingNo==null||boardingNo==0){
			alert("请添加乘机人信息！");
			$("#open_select_boarding").click();
			return false;
		}else if(contactsName==""||contactsName.length<2){
			alert("联系人错误！");
			return false;
		}else if(cellPhone==""||!/^[1]{1}[358]{1}\d{9}$/.test(cellPhone)){
			alert("手机号码错误！");
			return false;				
		}
		$.post(bookingInfoUrl, $("#bookinginfo_form").serialize(),function(data){
			if(data.status==1){
				//跳转至支付页面
				window.location.href="/Flight/pay?orderID="+data.orderID;
			}else if(data.status==0){
				alert(data.info);
			}
		},'json');
	})


});



// 初始化新增乘机人弹窗
function initializeAddBoarding(id){
	$(id).find(".surName").val("");
	$(id).find(".givenName").val("");
	$(id).find(".dobDate").val("");
	$(id).find(".ncredentialsNo").val("");
	$(id).find(".ctDate").val("");
}

// 选择签发国家弹窗
function selectCountryCode(id){
	var name=$(id).text(),H=$(document).height();
	$("#country_code_box").height(H).show();
	// 遍历列表签发国家
	$("#country_code li").each(function(){
		if(name==$(this).text()){
			$(this).addClass("designate");
		}
	});
	//点击选择签发国家
	$("#country_code li").click(function(){
		var val=$(this).attr("value"),thisName=$(this).text();
		$(this).addClass("designate").siblings().removeClass("designate");
		$("#country_code_box").hide();
		$(id).text(thisName).prev("input").val(val);
		$(document).scrollTop(0);
	});
	//关闭签发国家弹窗
	$("#close_country_code_list").click(function(){
		$("#country_code_box").hide();
		$(document).scrollTop(0);
	});
}

// 点击修改乘机人信息
function editBoarding(thisClass,type){
	// type=1为乘机人，type=2为常用乘机人
	var H=$(document).height(),boarding=thisClass.parent();
	var surName=boarding.attr("surName"),givenName=boarding.attr("givenName"),passengerType=boarding.attr("passengerType"),dobDate=boarding.attr("dobDate"),CountryCode=boarding.attr("CountryCode"),sex=boarding.attr("sex"),credentialsType=boarding.attr("credentialsType"),ncredentialsNo=boarding.attr("ncredentialsNo"),ctDate=boarding.attr("ctDate");
	$("#edit_boarding_info .surName").val(surName);
	$("#edit_boarding_info .givenName").val(givenName);
	$("#edit_boarding_info .passengerType").val(passengerType);
	// 遍历乘客类型，选择类型
	$("#edit_boarding_info .passenger_type").each(function(){
		var val=$(this).attr("value");
		if(val==passengerType){$(this).addClass("active");}else{$(this).removeClass("active");}
	});
	$("#edit_boarding_info .dobDate").val(dobDate);
	$("#edit_boarding_info .CountryCode").val(CountryCode);
	// 遍历列表签发国家，选择国家名
	$("#country_code li").each(function(){
		if(CountryCode==$(this).attr("value")){
			var txt=$(this).text();
			$("#edit_boarding_country_code").text(txt);
		}
	});
	$("#edit_boarding_info .sex").val(sex);
	// 遍历性别类型，选择性别
	$("#edit_boarding_info .sexr").each(function(){
		var val=$(this).attr("value");
		if(val==sex){$(this).addClass("active");}else{$(this).removeClass("active");}
	});
	$("#edit_boarding_info .credentialsType").val(credentialsType);
	$("#edit_boarding_info .ncredentialsNo").val(ncredentialsNo);
	$("#edit_boarding_info .ctDate").val(ctDate);
	$("#edit_boarding_info_box").height(H).show();
	$(document).scrollTop(0);

	// 选择签发国家
	$("#edit_boarding_country_code").click(function(){
		selectCountryCode($(this));
	});

	$("#edit_boarding_info_sub").click(function(){
		surName=$("#edit_boarding_info .surName").val();givenName=$("#edit_boarding_info .givenName").val();passengerType=$("#edit_boarding_info .passengerType").val();dobDate=$("#edit_boarding_info .dobDate").val();CountryCode=$("#edit_boarding_info .CountryCode").val();sex=$("#edit_boarding_info .sex").val();credentialsType=$("#edit_boarding_info .credentialsType").val();ncredentialsNo=$("#edit_boarding_info .ncredentialsNo").val();ctDate=$("#edit_boarding_info .ctDate").val();
		if(surName==""||!/^[a-zA-Z]*$/.test(surName)||surName.length<2){
			alert("姓(Surname)错误！");
			return false;
		}else if(givenName==""||!/^[a-zA-Z]*$/.test(givenName)||givenName.length<2){
			alert("名(Given names)错误！");
			return false;
		}else if(dobDate==""||!/^\d{4}-\d{2}-\d{2}$/.test(dobDate)){
			alert("出生日期错误！");
			return false;
		}else if(ncredentialsNo==""||!/^1[45][0-9]{7}|G[0-9]{8}|P[0-9]{7}|S[0-9]{7,8}|D[0-9]+$/.test(ncredentialsNo)){
			alert("证件号码期错误！");
			return false;
		}else if(ctDate==""||!/^\d{4}-\d{2}-\d{2}$/.test(ctDate)){
			alert("证件有效期错误！");
			return false;
		}else{
			boarding.attr("surName",surName);boarding.attr("givenName",givenName);boarding.attr("passengerType",passengerType);boarding.attr("dobDate",dobDate);boarding.attr("CountryCode",CountryCode);boarding.attr("sex",sex);boarding.attr("credentialsType",credentialsType);boarding.attr("ncredentialsNo",ncredentialsNo);boarding.attr("ctDate",ctDate);
			if(type==1){
				var html="";
				html+='<input type="hidden" name="TravelerInfo[surName][]" value="'+surName+'">';
				html+='<input type="hidden" name="TravelerInfo[givenName][]" value="'+givenName+'">';
				html+='<input type="hidden" name="TravelerInfo[passengerType][]" value="'+passengerType+'">';
				html+='<input type="hidden" name="TravelerInfo[dobDate][]" value="'+dobDate+'">';
				html+='<input type="hidden" name="TravelerInfo[CountryCode][]" value="'+CountryCode+'">';
				html+='<input type="hidden" name="TravelerInfo[sex][]" value="'+sex+'">';
				html+='<input type="hidden" name="TravelerInfo[credentialsType][]" value="'+credentialsType+'">';
				html+='<input type="hidden" name="TravelerInfo[ncredentialsNo][]" value="'+ncredentialsNo+'">';
				html+='<input type="hidden" name="TravelerInfo[ctDate][]" value="'+ctDate+'">';
				html+='<div class="bi_del"></div>';
				html+='<ul>';
  				html+='<li><span>姓名</span>'+surName+'/'+givenName+'</li>';
  				html+='<li><span>证件类型</span>'+credentialsType+'</li>';
  				html+='<li><span>证件号码</span>'+ncredentialsNo+'</li>';
				html+='</ul>';
				html+='<div class="bi_edit">修改</div>';
				boarding.html(html);
				$(document).scrollTop(H);
				countCost();//计算费用
			}else if(type=2){
				boarding.find("dt").text(surName+'/'+givenName);
				boarding.find("dd").text("护照   "+ncredentialsNo);
				$(document).scrollTop(0);
			}
			$("#edit_boarding_info_box").hide();
		}
	});

	// 关闭修改乘机人弹窗
	$("#close_edit_info_list").click(function(){
		$("#edit_boarding_info_box").hide();
		if(type==1){
			$(document).scrollTop(H);
		}else if(type=2){
			$(document).scrollTop(0);
		}
	});
}


//计算费用
function countCost(){
	var adultNo=0,childNo=0;
	//查看每个乘机人的乘客类型，赋值
	$("#boarding dd").each(function(i){
		var passengerType=$(this).attr("passengerType");
		if(passengerType=="ADT"){
			adultNo+=1;
		}else if(passengerType=="CNN"){
			childNo+=1;
		}
	});

	//成人价格
	var adultPrice=parseInt($("#adultPrice").text());
	//儿童价格
	var childPrice=parseInt($("#childPrice").text());

	//计算小计价格
	var subtotalPrice=adultPrice*adultNo+childPrice*childNo;
	//成人税费
	var adultTaxPrice=parseInt($("#taxPrice").attr("adultTaxPrice"));
	//儿童税费
	var childTaxPrice=parseInt($("#taxPrice").attr("childTaxPrice"));
	//参考税费
	var taxPrice=adultTaxPrice*adultNo+childTaxPrice*childNo;
	//合计总费用
	var ifTotal=subtotalPrice+taxPrice;

	//成人个数
	$("#adultNo").text(adultNo);
	//儿童个数
	$("#childNo").text(childNo);
	//小计价格
	$("#subtotalPrice").text(subtotalPrice);
	//参考税费合计
	$("#taxPrice").text(taxPrice);
	//总费用
	$("#ifTotal").text(ifTotal);
	$("#ifTotalInput").val(ifTotal);
		
}