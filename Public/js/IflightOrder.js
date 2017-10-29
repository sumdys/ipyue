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

	//日期
	$(document).on("click",".date_box",function(){
		WdatePicker({dateFmt:'yyyy-MM-dd'});
	});

	//加载签发国家
	$(document).on("click",".issuing_state",function(){
		var html=$(this).attr("html");
		var issuingState='<option value="CN">中国大陆</option><option value="TW">台湾</option><option value="HK">香港</option><option value="MO">澳门</option><option value="AL">阿尔巴尼亚</option><option value="DZ">阿尔及利亚</option><option value="AF">阿富汗</option><option value="AR">阿根廷</option><option value="AE">阿联酋</option><option value="AW">阿鲁巴</option><option value="OM">阿曼</option><option value="AZ">阿塞拜疆</option><option value="IE">爱尔兰</option><option value="EG">埃及</option><option value="ET">埃塞俄比亚</option><option value="EE">爱沙尼亚</option><option value="AD">安道尔</option><option value="AO">安哥拉</option><option value="AI">安圭拉岛</option><option value="AG">安提瓜与巴布达</option><option value="AU">澳大利亚</option><option value="AT">奥地利</option><option value="BB">巴巴多斯</option><option value="PG">巴布亚新几内亚</option><option value="BS">巴哈马</option><option value="PK">巴基斯坦</option><option value="PY">巴拉圭</option><option value="PS">巴勒斯坦</option><option value="BH">巴林</option><option value="PA">巴拿马</option><option value="BR">巴西</option><option value="BY">白俄罗斯</option><option value="BM">百慕大</option><option value="BG">保加利亚</option><option value="BJ">贝宁</option><option value="BE">比利时</option><option value="IS">冰岛</option><option value="BW">博茨瓦纳</option><option value="PR">波多黎各</option><option value="PL">波兰</option><option value="BO">玻利维亚</option><option value="BZ">伯利兹</option><option value="BA">波黑</option><option value="BT">不丹</option><option value="BF">布基纳法索</option><option value="BI">布隆迪</option><option value="GQ">赤道几内亚</option><option value="DK">丹麦</option><option value="DE">德国</option><option value="TG">多哥</option><option value="DM">多米尼克</option><option value="DO">多米尼加共和国</option><option value="EC">厄瓜多尔</option><option value="ER">厄立特里亚</option><option value="BY">俄罗斯</option><option value="FR">法国</option><option value="FO">法罗群岛</option><option value="PF">法属波利尼西亚</option><option value="GF">法属圭亚那</option><option value="VA">梵蒂冈</option><option value="FJ">斐济</option><option value="PH">菲律宾</option><option value="FI">芬兰</option><option value="CV">佛得角</option><option value="FK">福克兰群岛</option><option value="GM">冈比亚</option><option value="CG">刚果共和国</option><option value="CD">刚果民主共和国</option><option value="GL">格陵兰岛</option><option value="GD">格林纳达</option><option value="CO">哥伦比亚</option><option value="CR">哥斯达黎加</option><option value="CU">古巴</option><option value="GT">危地马拉</option><option value="GU">关岛</option><option value="GY">圭亚那</option><option value="KZ">哈萨克斯坦</option><option value="HT">海地</option><option value="KR">韩国</option><option value="NL">荷兰</option><option value="AN">荷属安的列斯</option><option value="HN">洪都拉斯</option><option value="DJ">吉布提</option><option value="KG">吉尔吉斯斯坦</option><option value="KI">基里巴斯</option><option value="GN">几内亚</option><option value="GW">几内亚比绍</option><option value="GH">加纳</option><option value="CA">加拿大</option><option value="GA">加蓬</option><option value="KH">柬埔寨</option><option value="CZ">捷克</option><option value="ZW">津巴布韦</option><option value="CM">喀麦隆</option><option value="QA">卡塔尔</option><option value="KY">开曼</option><option value="CC">科科斯（基林）群岛</option><option value="HR">克罗地亚</option><option value="KM">科摩罗</option><option value="CI">科特迪瓦</option><option value="KW">科威特</option><option value="KE">肯尼亚</option><option value="CK">库克群岛</option><option value="LV">拉脱维亚</option><option value="LS">莱索托</option><option value="LA">老挝</option><option value="LB">黎巴嫩</option><option value="LR">利比里亚</option><option value="LY">利比亚</option><option value="LT">立陶宛</option><option value="LI">列支敦士登</option><option value="RE">留尼旺</option><option value="LU">卢森堡</option><option value="RW">卢旺达</option><option value="RO">罗马尼亚</option><option value="MG">马达加斯加</option><option value="MV">马尔代夫</option><option value="MT">马耳他</option><option value="MW">马拉维</option><option value="MY">马来西亚</option><option value="ML">马里</option><option value="MK">马其顿</option><option value="MQ">马提尼克</option><option value="YT">马约特岛</option><option value="MU">毛里求斯</option><option value="MR">毛里塔尼亚</option><option value="US">美国</option><option value="AS">美属萨摩亚</option><option value="VI">美属维尔京群岛</option><option value="MN">蒙古</option><option value="BD">孟加拉</option><option value="MS">蒙特塞拉特</option><option value="FM">密克罗尼西亚</option><option value="PE">秘鲁</option><option value="MM">缅甸</option><option value="MD">摩尔多瓦</option><option value="MA">摩洛哥</option><option value="MC">摩纳哥</option><option value="MZ">莫桑比克</option><option value="MX">墨西哥</option><option value="NA">纳米比亚</option><option value="ZA">南非</option><option value="GS">南乔治亚</option><option value="NR">瑙鲁</option><option value="NP">尼泊尔</option><option value="NI">尼加拉瓜</option><option value="NE">尼日尔</option><option value="NG">尼日利亚</option><option value="KN">圣基茨和尼维斯</option><option value="NU">纽埃</option><option value="NF">诺福克岛</option><option value="NO">挪威</option><option value="PW">帕劳群岛</option><option value="PN">皮特凯恩岛</option><option value="PT">葡萄牙</option><option value="GS">乔治亚</option><option value="JP">日本</option><option value="SE">瑞典</option><option value="CH">瑞士</option><option value="SV">萨尔瓦多</option><option value="WS">萨摩亚</option><option value="CS">塞黑</option><option value="SL">塞拉利昂</option><option value="SN">塞内加尔</option><option value="CY">塞浦路斯</option><option value="SC">塞舌尔群岛</option><option value="SA">沙特阿拉伯</option><option value="CX">圣诞岛</option><option value="ST">圣多美及普林西比</option><option value="SH">圣赫勒拿</option><option value="LC">圣卢西亚</option><option value="SM">圣马力诺</option><option value="PM">圣皮埃尔岛及密克隆岛</option><option value="VC">圣文森特和格林纳丁斯</option><option value="LK">斯里兰卡</option><option value="SK">斯洛伐克</option><option value="SI">斯洛文尼亚</option><option value="SJ">斯瓦尔巴群岛</option><option value="SZ">斯威士兰</option><option value="SD">苏丹</option><option value="SR">苏里南</option><option value="SB">所罗门群岛</option><option value="SO">索马里</option><option value="TJ">塔吉克斯坦</option><option value="TH">泰国</option><option value="TZ">坦桑尼亚</option><option value="TO">汤加</option><option value="TC">特克斯和凯科斯群岛</option><option value="TT">特立尼达与多巴哥</option><option value="TR">土耳其</option><option value="TM">土库曼</option><option value="TN">突尼斯</option><option value="TV">图瓦卢</option><option value="TK">托克劳</option><option value="TL">东帝汶</option><option value="UM">美国本土外小岛屿</option><option value="WF">瓦利斯与富图纳</option><option value="VU">瓦努阿图</option><option value="VE">委内瑞拉</option><option value="BN">文莱</option><option value="UG">乌干达</option><option value="UA">乌克兰</option><option value="UY">乌拉圭</option><option value="UZ">乌兹别克斯坦</option><option value="ES">西班牙和加那利群岛</option><option value="GR">希腊</option><option value="EH">西撒哈拉</option><option value="SG">新加坡</option><option value="NC">新喀里多尼亚</option><option value="NZ">新西兰</option><option value="HU">匈牙利</option><option value="SY">叙利亚</option><option value="JM">牙买加</option><option value="AM">亚美尼亚</option><option value="YE">也门</option><option value="IT">意大利</option><option value="IQ">伊拉克</option><option value="IR">伊朗</option><option value="IL">以色列</option><option value="IN">印度</option><option value="ID">印度尼西亚</option><option value="GB">英国</option><option value="VG">英属维尔京群岛</option><option value="IO">英属印度洋领地</option><option value="JO">约旦</option><option value="VN">越南</option><option value="ZM">赞比亚</option><option value="TD">乍得</option><option value="GI">直布罗陀</option><option value="CL">智利</option><option value="CF">中非</option>';
		if(html=="0"){
			$(this).attr("html","1").append(issuingState);
		}
	});
	
	//点击增加乘机人信息
	$("#addPassenger .add").click(function(){
		var html='';
			html+='<div class="passenger_info">';
			html+='<h3>第<span class="list_word">一</span>位乘客<a class="del_passenger none">删除</a></h3>';
			html+='<ul>';
			html+='<li>';
			html+='<span><em>*</em>英文姓名：</span>';
			html+='<input type="text" name="TravelerInfo[surName][]" class="sur_name mr10" value placeholder="姓(Surname)">';
			html+='<i></i>';
			html+='<input type="text" name="TravelerInfo[givenName][]" class="given_name mr10" value placeholder="名(Given names)">';
			html+='<i></i>';
			html+='<a class="name_exp">填写说明</a>';
			html+='</li>';
			html+='<li>';
			html+='<span><em>*</em>乘客类型：</span>';
			html+='<select name="TravelerInfo[passengerType][]" class="passenger_type wd90">';
			html+='<option value="ADT">成人</option>';
            html+='<option value="CNN">儿童</option>';
			html+='</select>';
			html+='</li>';
			html+='<li>';
			html+='<span><em>*</em>出生日期：</span>';
			html+='<input type="text" name="TravelerInfo[dobDate][]" class="date_box mr10" value="1980-01-01">';
			html+='<i></i>';
			html+='</li>';
			html+='<li>';
			html+='<span><em>*</em>签发国家：</span>';
            html+='<select name="TravelerInfo[CountryCode][]" class="issuing_state mr10" html="0">';
            html+='<option value="0">请选择签发国家</option>';
            html+='</select>';
            html+='<i></i>';
			html+='</li>';
			html+='<li class="sex_type">';
			html+='<span><em>*</em>性别：</span>';
			html+='<input type="radio" name="TravelerInfo[sex][]" class="mt5" checked="checked" value="1"><label class="sex">男</label>';
			html+='<input type="radio" name="TravelerInfo[sex][]" class="mt5" value="2"><label class="sex">女</label>';
			html+='</li>';
			html+='<li>';
			html+='<span><em>*</em>证件号码：</span>';
			html+='<select name="TravelerInfo[credentialsType][]" class="credentials_type mr10">';
			html+='<option value="护照">护照</option>';
			html+='</select>';
			html+='<input type="text" class="credentials_no mr10" name="TravelerInfo[ncredentialsNo][]" value>';
			html+='<i></i>';
			html+='</li>';
			html+='<li>';
			html+='<span><em>*</em>证件有效期：</span>';
			html+='<input type="text" name="TravelerInfo[ctDate][]" class="date_box validity mr10" value="2020-01-01">';
			html+='<i></i>';
			html+='</li>';
			html+='<li>';
			html+='<span>&nbsp;</span>';
			html+='<div class="if_hint"><em>品悦温馨提示：</em>亲爱的用户，为了您的顺利出行，请确认您的行程结束时间至少比证件有效期早6个月</div>';
			html+='</li>';

			/*html+='<li>';
			html+='<span>保险：</span>';
			html+='<select name="TravelerInfo[insurance][]" class="insurance wd160 color333">';
			html+='<option value="60">保额100万  60元/份</option>';
			html+='</select>';
			html+='（全程2航段，每航段30元）';
			html+='<a href="#">保险详情及条款</a>';
			html+='</li>';
			html+='<li>';
			html+='<span>常旅客卡名称：</span>';
			html+='<input type="text" class="wd160" name="TravelerInfo[frequentlyName][]" value>';
			html+='</li>';
			html+='<li>';
			html+='<span>常旅客卡号：</span>';
			html+='<input type="text" class="wd160" name="TravelerInfo[frequentlyNo][]" value>';
			html+='</li>';*/

			html+='</ul>';
			html+='</div>';

		//增加乘机人信息
		var no=$(".passenger_info").length;
		if(no<9){
			$("#addPassenger").before(html);
			//循环修改乘机人的编号
			passengerInfoEach();
			//计算费用
			countCost();
		}else{
			alert("每个订单最多添加9个乘机人,9人以上请填写!");
		}
	});

	//点击显示填写说明信息
	$(document).on("click",".name_exp",function(){
		var top=$(this).offset().top-175,left=$(this).offset().left+50;
		$(".name_exp_box").css({"top":top,"left":left}).show();
	});
	//点击隐藏填写说明信息
	$(document).on("click",".name_exp_box i",function(){
		$(this).parent().hide();
	});

	//点击删除乘机人信息
	$(document).on("click",".del_passenger",function(){
		$(this).parent().parent().remove();
		//循环修改乘机人的编号
		passengerInfoEach();
		//计算费用
		countCost();
	});

	//点击修改乘客类型总新计算费用
	$(document).on("change",".passenger_type",function(){
		//计算费用
		countCost();
	});

	//英文姓名输入自动转为大写
	$(document).on("change",".sur_name,.given_name",function(){
		var val=$(this).val().toUpperCase();
		$(this).val(val);
	});

	//提交表单
	$("#flight_order_form").submit(function(){
		var state=1;
		//英文姓名
		formVerify(".sur_name","姓",/^[A-Z]*$/,"",2);
		formVerify(".given_name","名",/^[A-Z]*$/,"",2);

		//签发国家
		formVerify(".issuing_state","签发国家","","0","");

		//证件号码
		formVerify(".credentials_no","证件号码",/^1[45][0-9]{7}|G[0-9]{8}|E[0-9]{8}|P[0-9]{7}|S[0-9]{7,8}|D[0-9]+$/,"","");

		//出生日期
		formVerify(".birthday","出生日期",/^\d{4}-\d{2}-\d{2}$/,"1980-01-01","");

		//证件有效期
		formVerify(".validity","证件有效期",/^\d{4}-\d{2}-\d{2}$/,"2020-01-01","");

		//联系人姓名
		formVerify(".contacts_name","联系人姓名","","",2);

		//手机号码
		formVerify(".cell_phone","手机号码",/^[1]{1}[358]{1}\d{9}$/,"","");

		//邮箱地址
		formVerify(".email","邮箱地址",/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/,"","");
		$(".fo_bor_box li i").each(function(){
			var error=$(this).hasClass("error");
			if(error){
				state=0;
				return false;
			}
		});
		if(state==0){
			return false;
		}else if(state==1){
			return true;
		}
	});


});


//表单验证
function formVerify(name,option,regStr,val,len){
	//name为选择器名，option为选项名称，regStr为正则表达式，val为特定的对比值
	$(name).each(function(){
		var thisVal=$(this).val();
		var hintBox=$(this).next("i");
		//验证正则表达式
		if(thisVal==""){
			hintBox.text(option+"不能为空").addClass("error").removeClass("correct");
		}else if(regStr!=""&&!regStr.test(thisVal)){
			hintBox.text("请输入确认的"+option).addClass("error").removeClass("correct");
		}else if(val!=""&&thisVal==val){
			hintBox.text("请修改"+option+"的默认值").addClass("error").removeClass("correct");
		}else if(len!=""&&thisVal.length<len){
			hintBox.text("你输入的"+option+"长度不正确！").addClass("error").removeClass("correct");
		}else{
			hintBox.text("").addClass("correct").removeClass("error");
		}	
	});
}



//循环修改乘机人的编号
function passengerInfoEach(){
	$(".passenger_info").each(function(i){
		//查询乘机人的个数
		var len=$(".passenger_info").length,str="";

		//如果乘机人的个数大于1，显示删除按钮
		if(len==1){
			$(".del_passenger").addClass("none");
		}else if(len>1){
			$(".del_passenger").removeClass("none");
		}
		//如果乘机人的个数大于等于9，关闭增加按钮
		if(len>=9){
			$(".add_css").removeClass("add");
		}else{
			$(".add_css").addClass("add");
		}

		//把乘机人的编号赋值给str
		switch(i){
			case 0:
			str="一";
			break;
			case 1:
			str="二";
			break;
			case 2:
			str="三";
			break;
			case 3:
			str="四";
			break;
			case 4:
			str="五";
			break;
			case 5:
			str="六";
			break;
			case 6:
			str="七";
			break;
			case 7:
			str="八";
			break;
			case 8:
			str="九";
			break;
		}
		$(this).find(".list_word").text(str);
		$(this).find(".sex_type").children("input").attr("name","TravelerInfo[sex]["+i+"]");
	});
}


//计算费用
function countCost(){
	var adultNo=0,childNo=0;
	//查看每个乘机人的乘客类型，赋值
	$(".passenger_info").each(function(i){
		var passengerType=$(this).find(".passenger_type").val();
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