$(function(){
	$("#chaxun_form").find("li .city").each(function(i){
		$(this).click(function(){
            textInputDIs($(this));
			if(i==0){popCityList($(this)[0]);}
			if(i==1){popCityList($(this)[0],"guowai");}
		});
	});
	$("#chaxun_form").find("li .date").each(function(i){
		$(this).click(function(){
            textInputDIs($(this));
			if(i==0){WdatePicker({minDate:'%y-%M-%d',doubleCalendar:true});}
			if(i==1){WdatePicker({minDate:'%y-%M-{%d+1}',doubleCalendar:true});}
		});
	});
        function textInputDIs(dom){
            if(dom.attr("state")!="1"){
                dom.css("color","#333");
                dom.val("");
                dom.attr("state","1");
            }
        }
	$("#radio_sel input").each(function(i){
		var li=$("#date_list li");
		$(this).click(function(){
			if(i==0){
				li.eq(1).hide();
			}
			if(i==1){
				li.eq(1).show();
			}
		});
	});
	// 调换城市
	$("#exchange a").click(function(){
		var city=$("#city_list input"),city0,city1;
		city0=city.eq(0).val();
		city1=city.eq(1).val();
		city.eq(0).val(city1);
		city.eq(1).val(city0);
	});

	// 查询提交
	$("#chaxun_form").submit(function(e){
        var state=0;
		$(this).find("input.text").each(function(i){
            if($(this).parent().css("display")==="none") return true;
			if($(this).attr("state")!="1"||$(this).val()==""){
				if($(this).hasClass("city"))$(this).click();
				else{alert("请输入日期");}
				state=1;
				return false;   
			}
		});
        if(state==1) return false;
	});
	
	//图片轮换
	slider($("#iflightAd"),1000,6000,0,2,700);

	//自由行首页搜索
	$('#sLf').click(function(){
		popCityList($(this)[0]);
	});
	$('#sLc').click(function(){
		$('#sLf').click();
	})
});
//动画制作
(function(){
var timer = null;var timer1 = null;
//t轮换时间，s等待时间,n当前图片位置，m总共张数，w图片宽度
function slider(div,t, s, n, m, w) {
	sliderChange(div,t, s, n, m, w);
	sliderClick(div,t, s, m, w);
}
function sliderChange(div,t, s, n, m, w) {
	div.find("ul").animate({ "margin-left": -(n * w) + "px" }, t, function () {
			timer = setTimeout(function () {
			var j = n + 1;
			if (j > m - 1) { j = 0; }
			div.find("ol li a").eq(n).removeClass("active");
			div.find("ol li a").eq(j).addClass("active");
			sliderChange(div,t, s, j, m, w);
		}, s);
	});
}
function sliderClick(div,t, s, m, w) {
	div.find("ol li").each(function (i) {
		$(this).find("a").click(function () {
			clearTimeout(timer); clearTimeout(timer1);
			div.find("ul").stop(true);
			div.find("ol li a").removeClass("active");
			div.find("ol li a").eq(i).addClass("active");
			div.find("ul").animate({ "margin-left": -(i * w) + "px" }, t);
			timer1 = setTimeout(function () {
				sliderChange(div,t, s, i, m, w) 
			},s);
		});
	});
}
window.slider=slider;
})()
