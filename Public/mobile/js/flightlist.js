$(function(){

	//加载航班列表时的过场动画
	resultLoading(1);

	//加载航班列表
	resultFlightList(0,"");
	
	$(document).on("click",".inputSubmit",function(){
		var hbid=$(this).parents("li").attr("hbid"),journey=$(this).parents("li").attr("journey");
		window.location.href="/Flight/bookinginfo?hbid="+hbid+"&journey="+journey; 
		//window.location.href="/aishangfei/m/Flight/bookinginfo?hbid="+hbid+"&journey="+journey;   ///////////////////////////////上线之后地址要更改
		
	});

	// 选择排序
	$("#order_by li").click(function(){
		//选中点击的项目
		$(this).addClass("on").siblings().removeClass("on");
		var seta=$(this).find("a");sortType=seta.attr("sorttype"),parClass=seta.attr("class");
		//根据当前的类取反的选择排序方式
		if(parClass=="up"){
			//当前排序方式为"up"，点击后的排序方式为"down"，其值为"2"
			updown="2";
			seta.removeClass("up").addClass("down");
		}else if(parClass=="down"){
			//当前排序方式为"down"，点击后的排序方式为"up"，其值为"1"
			updown="1";
			seta.removeClass("down").addClass("up");
		}
		//调用排序函数
		flightListEvent('#ifallList',sortType,'',updown);
	});

	// 点击显示或隐藏航班列表的返程信息
	$(document).on("click","#ifallList .details",function(){
		var dl=$(this).next();
		if(dl.hasClass("none")){
			dl.removeClass("none");
		}else{
			dl.addClass("none");
		}
	});

});



//查询并加载航班列表函数;addto为1是追加数据,0为更迭数据;lfid为返回的航班ID数组;
function resultFlightList(addto,lfid){
	// 城市和日期数据
	var journey				= $("#ifallList").attr("journey");
	var originCode			= reg($("#ifallList").attr("originCode"),/[A-Z]{3}/);
	var desinationCode		= reg($("#ifallList").attr("desinationCode"),/[A-Z]{3}/);
	var originDate			= $("#ifallList").attr("originDate");
	var returnDate			= $("#ifallList").attr("returnDate");
	var formInfo ={"journey":journey,"originCode":originCode,"desinationCode":desinationCode,"originDate":originDate,"returnDate":returnDate,"selInPassengersType":"ADT","selInAdult":1,"selInChild":0}

	//读取请求次数的值并加1
	var request=parseInt($("#ifallList").attr("request"))+1;
	$("#ifallList").attr("request",request);

	// AJAX请求航班数据---未写好
		//searchTicketUrl
		var sUrl='/Flight/searchTicket';
	
	$.getJSON(searchTicketUrl,formInfo,function(data){ ///////////////////////////////上线之后地址要更改
		if(data.status==1){
			var dataList=data.OriginDestinationOption,html="";
			// 如果航班数据不为空
			if(dataList!=null){				
				if(journey=="1"){
					$.each(dataList,function(i,item){//航线列表循环						
						html +='<li class="iflist_box" offtime="5000" arrtime="8000" stroketime="5000" price="1000" nonstop="n" hbid="'+i+'" journey="1">';
						html +='<dl>';
						html +='<dt>去程：</dt>';
						
						$.each(item.hc0,function(y,hc){//去程循环
							html +='<dd class="material"> <span class="t">'+hc.DepartureDate+'-'+hc.ArrivalDate+'</span> <span>往返票价<strong>￥'+hc.PriceFare+'</strong></span> <span class="t">约'+hc.timeCost+'小时</span> <span>税<em>￥'+hc.PriceTax+'</em> > '+hc.CabinLeft+'张</span> </dd>';
							$.each(hc.FlightSegment,function(f,fs){//FlightSegment循环
								if(fs.stopTimes == 1){
									html +='<dd class="turn inputSubmit">';
									html +='<div class="airport"><span>'+fs.DepartureAirportName+'</span><span class="turn_icon">'+fs.ArrivalAirportName+'</span><span>'+fs.zz_ddjc+'</span></div>';
									html +='<div class="airline"><span>'+fs.AirCompanyName+fs.OperatorCode+fs.FlightNumber+'<em>'+fs.ResBookDesigCode+'舱</em></span><span>'+fs.zz_airname+fs.zz_OCode+fs.zz_fm+'<em>'+fs.zz_rd+'舱</em></span></div>';
									html +='</dd>';					
								}else{
									html +='<dd class="donot_turn inputSubmit">';
									html +='<div class="airport"><span>'+fs.DepartureAirportName+'</span><span>'+fs.ArrivalAirportName+'</span></div>';
									html +='<div class="airline"><span>'+fs.AirCompanyName+fs.OperatorCode+fs.FlightNumber+'&nbsp;<em>'+fs.ResBookDesigCode+'舱</em></span></div>';
									html +='</dd>';		
								}
							});							
						
          				html +='</dl>';						
						
          				html +='<div class="details">返程详情</div>';
          				html +='<dl class="none">';
            			html +='<dt>返程：</dt>';						
						$.each(hc.fc,function(y,hcs){//去程循环
							html +='<dd class="material"> <span class="t">'+hcs.DepartureDate+'-'+hcs.ArrivalDate+'</span> <span>往返票价<strong>￥'+hcs.PriceFare+'</strong></span> <span class="t">约'+hcs.timeCost+'小时</span> <span>税<em>￥'+hcs.PriceTax+'</em> > '+hcs.CabinLeft+'张</span> </dd>';
							$.each(hcs.FlightSegment,function(f,fss){//FlightSegment循环													 
								if(fss.stopTimes == 1){
									html +='<dd class="turn inputSubmit" >';
									html +='<div class="airport"><span>'+fss.DepartureAirportName+'</span><span class="turn_icon">'+fss.ArrivalAirportName+'</span><span>'+fss.zz_ddjc+'</span></div>';
									html +='<div class="airline"><span>'+fss.AirCompanyName+fss.OperatorCode+fss.FlightNumber+'<em>'+fss.ResBookDesigCode+'舱</em></span><span>'+fss.zz_airname+fss.zz_OCode+fss.zz_fm+'<em>'+fss.zz_rd+'舱</em></span></div>';
									html +='</dd>';					
								}else{
									html +='<dd class="donot_turn inputSubmit">';
									html +='<div class="airport"><span>'+fss.DepartureAirportName+'</span><span>'+fss.ArrivalAirportName+'</span></div>';
									html +='<div class="airline"><span>'+fss.AirCompanyName+fss.OperatorCode+fss.FlightNumber+'&nbsp;<em>'+fss.ResBookDesigCode+'舱</em></span></div>';
									html +='</dd>';		
								}
							});							
						});	
					    html +='</dl>';
						});
					    html +='</li>';
        			});
					
					
				}else if(journey=="2"){					
					$.each(dataList,function(i,item){ //航线列表循环						 
						html +='';
						html +='<li class="iflist_box" offtime="2000" arrtime="10000" stroketime="3000" price="2000" nonstop="y" hbid="'+i+'" journey="2">';
						html +='<dl>';						
						$.each(item.hc0,function(y,hc){//去程循环
							html +='<dd class="material"> <span class="t">'+hc.DepartureDate+'-'+hc.ArrivalDate+'</span> <span>单程票价<strong>￥'+hc.PriceFare+'</strong></span> <span class="t">约'+hc.timeCost+'小时</span> <span>税<em>￥'+hc.PriceTax+'</em> > '+hc.CabinLeft+'张</span> </dd>';
							$.each(hc.FlightSegment,function(f,fs){//FlightSegment循环
								if(fs.stopTimes==1){
									html +='<dd class="turn inputSubmit">';
									html +='<div class="airport"><span>'+fs.DepartureAirportName+'</span><span class="turn_icon">'+fs.ArrivalAirportName+'</span><span>'+fs.zz_ddjc+'</span></div>';
									html +='<div class="airline"><span>'+fs.AirCompanyName+fs.OperatorCode+fs.FlightNumber+'<em>'+fs.ResBookDesigCode+'舱</em></span><span>'+fs.zz_airname+fs.zz_OCode+fs.zz_fm+'<em>'+fs.zz_rd+'舱</em></span></div>';
									html +='</dd>';					
								}else{
									html +='<dd class="donot_turn inputSubmit">';
									html +='<div class="airport"><span>'+fs.DepartureAirportName+'</span><span>'+fs.ArrivalAirportName+'</span></div>';
									html +='<div class="airline"><span>'+fs.AirCompanyName+fs.OperatorCode+fs.FlightNumber+'&nbsp;<em>'+fs.ResBookDesigCode+'舱</em></span></div>';
									html +='</dd>';		
								}
							});							
						});							
						html +='</dl>';
						html +='</li>';
        			});
				}
				
				

				//addto为1是追加数据,0为更迭数据
				if(addto==0){
					$("#order_by").show();
					$('#ifallList').html(html);
				}else if(addto==1){
					$('#ifallList').append(html)
				}
			
			}
			// 关闭过场动画
			resultLoading(0);
			$("#resultLoading").hide();
			$("#ifallList").show();
			/*
			调用自身，通过AJAX加载航班
			如果FinFlag="T"，初次加载航班时以全部加载;
			如果FinFlag="F"，初次加载航班时，只加载一部分，需要再次加载；
			LoadingFlightId为每次返回航班的ID；
			*/
			var FinFlag=data.FinFlag,LoadingFlightId=data.LoadingFlightId;
			if(FinFlag=="F"&&request<=30){
				resultFlightList(1,LoadingFlightId);
			}
		}else if(data.status==0){
			// resultLoading还没有写
			// resultLoading(0);
			// $("#resultLoading").hide();
			$("#errorInfo").html(data.info).show();
		}
	});
}


//正则表达式函数
function reg(str,regStr){
	var tostr=str.match(regStr);
	return tostr.toString();
}

//分钟转小时分钟（hour:minute）函数
function minuteToTime(min){
	if(min==""||min=="0"||min==0){
		return "--";
	}
	var hour=Math.round(min/60)+"时";
	var minute=min%60+"分钟";
	return hour+minute;
}

//秒转小时分钟函数
function secToTime(sec){
	var date=new Date(parseInt(sec)*1000);
	var hour=date.getHours();
	var minute=date.getMinutes();
	return hour+":"+minute;
}

//yyyy-mm-ss格式转化为mm月ss号函数
function yearMonthDay(dateStr){
	var dateArr = dateStr.split("-");
	return dateArr[0]+'年'+dateArr[1]+'月'+dateArr[2]+'号';
}

//航班排序函数
function flightListEvent(listId,sortType,explain,updown){
	//listId为航班列表的ID；sortType为选择排序的种类；explain为选择排序的说明；updown为选择排序的方式

	//定义变量keys用于存储排序种类sortType的值；定义变量data用于存储初始的st与相对的HTML；定义变量newData用于存储排序后的HTML；
	var keys=new Array(),data=new Array(),newData=new Array(),html="";
	var iflistBox=$(listId).find(".iflist_box");
	iflistBox.each(function(i){
		//var st=parseInt($(this).attr(sortType));
		//为了防止有相同的st值，原始的st值加上变量i产生一个新的值;
		var st=$(this).attr(sortType)+i;
		//存储排st的值
		keys[i]=st;
		//获取自身的HTML
		data[st]=this.outerHTML;
	});

	//通过sort()根据排序方式重新排序keys
	if(updown=="1"){
		keys.sort(function(a,b){return a-b;});
	}else if(updown=="2"){
		keys.sort(function(a,b){return b-a;});
	}

	//对新keys和data配对后重新赋值给newData
	for(var i=0;i<keys.length;i++){
		for(st in data){
			//用keys的值和data的键值st对比
			if(keys[i]==st){
				newData[i]=data[st];
			}
		}
	}

	//通过循环把newData的值赋给html
	for(var i=0;i<newData.length;i++){
		html+=newData[i];
	}

	//输出html
	$(listId).html(html);
}

//加载航班列表时的过场动画函数
function resultLoading(val){
	var airline_arr=['中国国际航空','新加坡航空','法国航空','维珍航空','香港航空','印度航空','新加坡航空','大韩航空','国泰航空','马来西亚航空','印度航空','日本航空'];
	var i=0;
	var fload=setInterval(function(){
		$('#resultLoading span').html(airline_arr[i]);
		i++;
		if(i>12){i=0;}
	},100);

	if(val==0){
		clearInterval(fload);
	}
}