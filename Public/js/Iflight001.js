$(function(){
	//加载航班列表时的过场动画
	resultLoading(1);
	
	//初始查询并加载航班列表
	resultFlightList(0,"",0,"");

	//显示退改签信息
	$(document).on("mouseenter",".line_tgq",function(){
		var title=$(this).attr("title");
		var top=$(this).offset().top+15,left=$(this).offset().left;
		$(".line_tgq_box").css({"top":top,"left":left}).show().find("p").html(title);
	});
	$(document).on("mouseleave",".line_tgq",function(){
		$(".line_tgq_box").hide().find("p").html("");
	});

	//查询并加载返程航班
	$(document).on("click",".if_back",function(){
		//获得航班号
		var back=$(this).attr("FlightNos");
		//显示过场动画
		$("#resultLoading").show();
		//隐藏航班列表
		$("#resultList").hide();
		//设置return的值为1
		$('#ifallList').attr("returnType","1");
		//查询并加载返程航班
		resultFlightList(1,back,0,"");
	});

	//点击跳转至航班订单页面
	$(document).on("click",".if_reserve",function(){
		var journey				= $("#ifallList").attr("journey");
		var originCode			= $("#ifallList").attr("originCode");
		var desinationCode		= $("#ifallList").attr("desinationCode");
		var originDate			= $("#ifallList").attr("originDate");
		var returnDate			= $("#ifallList").attr("returnDate");

		var selInCabinType		= $("#ifallList").attr("selInCabinType");
		var selInPassengersType	= $("#ifallList").attr("selInPassengersType");
		var selInAdult			= $("#ifallList").attr("selInAdult");
		var selInChild			= $("#ifallList").attr("selInChild");
		//获得航班的PosId
		var PosId=$(this).attr("PosId");
		//跳转至航班订单页面
		window.location.href=flightOrderUrl+"?journey="+journey+"&originCode="+originCode+"&desinationCode="+desinationCode+"&originDate="+originDate+"&returnDate="+returnDate+"&selInCabinType="+selInCabinType+"&selInPassengersType="+selInPassengersType+"&selInAdult="+selInAdult+"&selInChild="+selInChild+"&PosId="+PosId;		
	});



	//使用下拉列表方式选择排序
	$("#ftickListSet").change(function(){
		var setVal=$(this).val(),sortType,updown;

		//当选择下拉列表,提取当前的值val和列表值setVal对比
		$("#ftickListSet option").each(function(){
			var val=$(this).val();
			if(val==setVal){
				//配对正确后，把当前的排序种类sortType和排序方式updown赋值
				sortType=$(this).attr("sorttype");
				updown=$(this).attr("updown");
			}
		});

		//当选择下拉列表时和图标相关联
		$("#ftickListTop .flight_effect").each(function(i){
			//获得当前排序种类并赋值给st
			var st=$(this).attr("sorttype");
			//当st值与下拉列表中的sortType值相等时
			if(st==sortType){
				$(this).addClass("on");
				if(updown=="1"){
					$(this).parent().removeClass("down").addClass("up");
				}else if(updown=="2"){
					$(this).parent().removeClass("up").addClass("down");
				}
			}else{
				$(this).removeClass("on");
			}
		});

		switch(setVal){
			case "1":
			flightListEvent('#ifallList','price','按价格从低到高','1');
			break;
			case "2":
			flightListEvent('#ifallList','price','按价格从高到低','2');
			break;
			case "3":
			flightListEvent('#ifallList','offtime','按起飞从早到晚','1');
			break;
			case "4":
			flightListEvent('#ifallList','offtime','按起飞从晚到早','2');
			break;
			case "5":
			flightListEvent('#ifallList','arrtime','按到达从早到晚','1');
			break;
			case "6":
			flightListEvent('#ifallList','arrtime','按到达从晚到早','2');
			break;
			case "7":
			flightListEvent('#ifallList','stroketime','按行程从短到长','1');
			break;
			case "8":
			flightListEvent('#ifallList','stroketime','按行程从长到短','2');
			break;
		}
	});



	
	//使用图标方式选择排序
	$("#ftickListTop .flight_effect").click(function(){
		var sortType=$(this).attr("sorttype"),parClass=$(this).parent().attr("class");

		//根据当前的类取反的选择排序方式
		if(parClass=="up"){
			//当前排序方式为"up"，点击后的排序方式为"down"，其值为"2"
			updown="2";
			$(this).parent().removeClass("up").addClass("down");
		}else if(parClass=="down"){
			//当前排序方式为"down"，点击后的排序方式为"up"，其值为"1"
			updown="1";
			$(this).parent().removeClass("down").addClass("up");
		}

		//选中点击的图标
		$("#ftickListTop .flight_effect").each(function(){
			var st=$(this).attr("sorttype");
			if(st==sortType){
				$(this).addClass("on");
			}else{
				$(this).removeClass("on");
			}
		});

		//点击图标与选择下拉列表相关联
		$("#ftickListSet option").each(function(){
			var st=$(this).attr("sorttype"),ud=$(this).attr("updown");
			if(st==sortType&ud==updown){
				this.selected="selected";
			}else{
				this.selected="";
			}
		});

		//调用排序函数
		flightListEvent('#ifallList',sortType,'',updown);
	});



	//仅显示直飞
	$("#filterTransit input").click(function(){
		var checked=this.checked;
		//判断是否选择了直飞
		if(checked){
			//循环把中转的航班隐藏
			$("#ifallList .iflist_box").each(function(){
				var nonstop=$(this).attr("nonstop");
				if(nonstop=="n"){
					$(this).addClass("none");
				}
			});
		}else{
			//循环显示全部
			$("#ifallList .iflist_box").each(function(){
					$(this).removeClass("none");
			});
		}
	});


});





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


//查询并加载航班列表函数
function resultFlightList(type,back,addto,lfid){
	/*
	如果type=0为调用初始的表单数据来请求航班列，如果type=1为调用初始调用后的表单数据来请求航班列;
	back为航班号;addto为1是追加数据,0为更迭数据;lfid为返回的航班ID数组；
	*/

	var formInfo="";
	if(type==0){
		//搜索框中的城市和日期数据
		var journey             = $("#iflight_rad input:checked").val();
		var originCode          = reg($("#originCode").val(),/[A-Z]{3}/);
		var desinationCode      = reg($("#desinationCode").val(),/[A-Z]{3}/);
		var originDate          = $("#originDate").val();
		var returnDate          = $("#returnDate").val();
		//搜索框中的高级搜索数据
		var selInCabinType      = $("#selInCabinType").val();
		var selInPassengersType = $("#selInPassengersType").val();
		var selInAdult          = $("#selInAdult").val();
		var selInChild          = $("#selInChild").val();

		if(lfid!=""){
			formInfo ={"journey":journey,"originCode":originCode,"desinationCode":desinationCode,"originDate":originDate,"returnDate":returnDate,"selInCabinType":selInCabinType,"selInPassengersType":selInPassengersType,"selInAdult":selInAdult,"selInChild":selInChild,"LoadingFlightId":lfid}
		}else if(lfid==""){
			formInfo ={"journey":journey,"originCode":originCode,"desinationCode":desinationCode,"originDate":originDate,"returnDate":returnDate,"selInCabinType":selInCabinType,"selInPassengersType":selInPassengersType,"selInAdult":selInAdult,"selInChild":selInChild}
		}

		//在航班列表头显示航班信息
		$("#flyOriginCity").text(reg($("#originCode").val(),/^[^x00-xff][^(]*/));
		$("#flyDesinationCity").text(reg($("#desinationCode").val(),/^[^x00-xff][^(]*/));
		$("#departTime").text("出发日期："+yearMonthDay(originDate));
		$("#backTime").text("返回日期："+yearMonthDay(returnDate));
		if(journey==2){
			$("#flyType").text("(单程)");
			$("#flyTips").removeClass("round");
			$("#backTime").addClass("none");
		}else if(journey==1){
			$("#flyType").text("(往返)");
			$("#flyTips").addClass("round");
			$("#backTime").removeClass("none");
		}

		//更新航班信息,用于显示返程信息
		$("#ifallList").attr("journey",journey);
		$("#ifallList").attr("originCode",originCode);
		$("#ifallList").attr("desinationCode",desinationCode);
		$("#ifallList").attr("originDate",originDate);
		$("#ifallList").attr("returnDate",returnDate);

		$("#ifallList").attr("selInCabinType",selInCabinType);
		$("#ifallList").attr("selInPassengersType",selInPassengersType);
		$("#ifallList").attr("selInAdult",selInAdult);
		$("#ifallList").attr("selInChild",selInChild);

	}else if(type==1){

		var journey				= $("#ifallList").attr("journey");
		var originCode			= $("#ifallList").attr("originCode");
		var desinationCode		= $("#ifallList").attr("desinationCode");
		var originDate			= $("#ifallList").attr("originDate");
		var returnDate			= $("#ifallList").attr("returnDate");

		var selInCabinType		= $("#ifallList").attr("selInCabinType");
		var selInPassengersType	= $("#ifallList").attr("selInPassengersType");
		var selInAdult			= $("#ifallList").attr("selInAdult");
		var selInChild			= $("#ifallList").attr("selInChild");

		 formInfo ={"journey":journey,"originCode":originCode,"desinationCode":desinationCode,"originDate":originDate,"returnDate":returnDate,"selInCabinType":selInCabinType,"selInPassengersType":selInPassengersType,"selInAdult":selInAdult,"selInChild":selInChild,"back":back}

	}

	//读取请求次数的值并加1
	var request=parseInt($("#ifallList").attr("request"))+1;
	$("#ifallList").attr("request",request);

	$.getJSON(flightBookUrl,formInfo,function(data){
		if(data.status==1){
			var dataList=data.OriginDestinationOption,html='';
			if(dataList!=null){
				$.each(dataList,function(i,item){

					html+='<div class="iflist_box" offtime="'+item.DepartureDate+'" arrtime="8000" stroketime="'+item.FlightTime+'" price="'+item.PriceFare+'" ';
					if(item.Flat=="2"){
						html+='nonstop="n"';
					}else if(item.Flat=="1"){
						html+='nonstop="y"';
					}
					html+='>';
                	html+='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
					html+='<tr>';
					html+='<td width="345" class="flight_show">';

					$.each(item.FlightSegment,function(j,fs){
						if(j==0){
                			html+='<table class="list_info" width="330" border="0" align="center" cellpadding="0" cellspacing="0">';
							html+='<tr>';
                			html+='<td width="190">';
                			html+='<p><em class="offTime">'+secToTime(fs.DepartureDate)+'</em><span>'+fs.DepartureAirportName+'</span></p>';
                			html+='<p><em class="arrTime">'+secToTime(fs.ArrivalDate)+'</em><span>'+fs.ArrivalAirportName+'</span></p>';
                			html+='</td>';
                			html+='<td width="125">';
                			html+='<p><span class="if_logo"></span><span>'+fs.AirCompanyName+'</span></p>';
                			html+='<p><span>'+fs.OperatorCode+fs.FlightNumber+'</span><span>计划机型<a>'+fs.Equipment+'</a></span></p>';
                			html+='</td>';
                			html+='</tr>';
                			html+='</table>';
            			}else if(j==1){
            				html+='<div class="tran_flight">';
                			html+='<div class="transit_text">';
                			html+='<p>';
                			html+='<span class="icon"></span>';
                			html+='<span>'+fs.DepartureCityName+'中转</span>';
                			html+='<span class="stay_time">停留'+fs.FlyTime+'</span>';
                			html+='</p>';
                			html+='</div>';
                			html+='<table width="330" border="0" align="center" cellpadding="0" cellspacing="0" class="list_info">';
							html+='<tr>';
                			html+='<td width="190">';
                			html+='<p><em class="offTime">'+secToTime(fs.DepartureDate)+'</em>';
                			if(fs.DepartureSpanDays>0){
                			html+='<a>第二天</a>';
                			}
                			html+='<span>'+fs.DepartureAirportName+'</span></p>';
                			html+='<p><em class="arrTime">'+secToTime(fs.ArrivalDate)+'</em>';
                			if(fs.DepartureSpanDays>0){
                			html+='<a>第二天</a>';
                			}
                			html+='<span>'+fs.ArrivalAirportName+'</span></p>';
                			html+='</td>';
                			html+='<td width="125">';
                			html+='<p><span class="if_logo"></span><span>'+fs.AirCompanyName+'</span></p>';
                			html+='<p><span>'+fs.OperatorCode+fs.FlightNumber+'</span><span>计划机型<a>'+fs.Equipment+'</a></span></p>';
                			html+='</td>';
                			html+='</tr>';
                			html+='</table>';
                			html+='</div>';
            			}
            		});

                	html+='</td>';
                	html+='<td width="90" align="center">';
                	html+='<p><span>'+minuteToTime(item.FlightTime)+'</span></p>';
                	html+='<p><a class="line_tgq" title="'+item.FareInfo.Remarks.Remark+'">退改签</a></p>';
                	html+='</td>';
                	html+='<td width="145">';
                	html+='<p><span class="if_price">票价<em>￥</em><b>'+item.PriceFare+'</b></span></p>';
                	html+='<p><span class="if_tax">参考税￥'+item.PriceTax+'</span></p>';
                	html+='</td>';
                	html+='<td width="140" class="if_reserve_back">';

                	if(data.FlightType=="2"||data.originReturn=="1"){
                		html+='<p><a class="if_reserve" PosId="'+item.PosId+'">预订</a></p>';
                	}else if(data.FlightType=="1"&&data.originReturn=="0"){
                		html+='<p><a class="if_back" FlightNos="'+item.FlightNos+'">查看返程</a></p>';
                	}
                
                	html+='<p><span>剩余'+item.CabinLeft+'张</span></p>';
                	html+='</td>';
                	html+='</tr>';
					html+='</table>';
                	html+='</div>';
				});
			}
			
			//已选定去程
			var originRoute=data.OriginRoute,orHtml='';
			if(originRoute){
				orHtml+='<div class="iflist_box">';
                orHtml+='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
                orHtml+='<tr>';
                orHtml+='<td width="100">';
                orHtml+='</td>';
                orHtml+='<td width="345" class="flight_show">';

                $.each(originRoute.FlightSegment,function(j,fs){
                	if(j==0){
                		orHtml+='<table class="list_info" width="330" border="0" align="center" cellpadding="0" cellspacing="0">';
                		orHtml+='<tr>';
                		orHtml+='<td width="190">';
                		orHtml+='<p><em class="offTime">'+secToTime(fs.DepartureDate)+'</em><span>'+fs.DepartureAirportName+'</span></p>';
                		orHtml+='<p><em class="arrTime">'+secToTime(fs.ArrivalDate)+'</em><span>'+fs.ArrivalAirportName+'</span></p>';
                		orHtml+='</td>';
                		orHtml+='<td width="125">';
                		orHtml+='<p><span class="if_logo"></span><span>'+fs.AirCompanyName+'</span></p>';
                		orHtml+='<p><span>'+fs.OperatorCode+fs.FlightNumber+'</span><span>计划机型<a>'+fs.Equipment+'</a></span></p>';
                		orHtml+='</td>';
                		orHtml+='</tr>';
                		orHtml+='</table>';
                	}else if(j==1){
                		orHtml+='<div class="tran_flight">';
                		orHtml+='<div class="transit_text">';
                		orHtml+='<p>';
                		orHtml+='<span class="icon"></span>';
                		orHtml+='<span>'+fs.DepartureCityName+'</span>';
                		orHtml+='<span class="stay_time">停留'+fs.FlyTime+'</span>';
                		orHtml+='</p>';
                		orHtml+='</div>';
                		orHtml+='<table width="330" border="0" align="center" cellpadding="0" cellspacing="0" class="list_info">';
                		orHtml+='<tr>';
                		orHtml+='<td width="190">';
                		orHtml+='<p><em class="offTime">'+secToTime(fs.DepartureDate)+'</em>';
                		if(fs.DepartureSpanDays>0){
                			orHtml+='<a>第二天</a>';
                		}
                		orHtml+='<span>'+fs.DepartureAirportName+'</span></p>';
                		orHtml+='<p><em class="arrTime">'+secToTime(fs.ArrivalDate)+'</em>';
                		if(fs.DepartureSpanDays>0){
                			orHtml+='<a>第二天</a>';
                		}
                		orHtml+='<span>'+fs.ArrivalAirportName+'</span></p>';
                		orHtml+='</td>';
                		orHtml+='<td width="125">';
                		orHtml+='<p><span class="if_logo"></span><span>'+fs.AirCompanyName+'</span></p>';
                		orHtml+='<p><span>'+fs.OperatorCode+fs.FlightNumber+'</span><span>计划机型<a>'+fs.Equipment+'</a></span></p>';
                		orHtml+='</td>';
                		orHtml+='</tr>';
                		orHtml+='</table>';
                		orHtml+='</div>';
                	}

            	});
                orHtml+='</td>';
                orHtml+='<td width="90" align="center">';
                orHtml+='<p><span>'+minuteToTime(originRoute.FlightTime)+'</span></p>';
                orHtml+='</td>';
                orHtml+='<td width="185">';
                orHtml+='</td>';
                orHtml+='</tr>';
                orHtml+='</table>';
                orHtml+='</div>';

                var corCity=$("#flyOriginCity").text()+"→"+$("#flyDesinationCity").text();
                var corDate="日期："+originDate;
                $("#corCity").text(corCity);
                $("#corDate").text(corDate);
                $("#checkedOriginRoute").show();
                $("#corInfo").html(orHtml);

			}
			
			
			//addto为1是追加数据,0为更迭数据;returnType为0表示去程，为1表示为返程
			var returnType=parseInt($('#ifallList').attr("returnType"));
			//如果returnType为0，返回的数据才能加载入#ifallList
			if(returnType==0&&dataList!=null){
				if(addto==0){
					$('#ifallList').html(html);
				}else if(addto==1){
					$('#ifallList').append(html)
				}
				resultLoading(0);
				$("#resultLoading").hide();
				$("#resultList").show();
			}else if(returnType==1&&originRoute){
				if(addto==0){
					$('#ifallList').html(html);
				}else if(addto==1){
					$('#ifallList').append(html)
				}
				resultLoading(0);
				$("#resultLoading").hide();
				$("#resultList").show();
			}
			
			

			/*
			如果FinFlag="T"，初次加载航班时以全部加载;
			如果FinFlag="F"，初次加载航班时，只加载一部分，需要再次加载；
			LoadingFlightId为每次返回航班的ID；
			*/
			var FinFlag=data.FinFlag,LoadingFlightId=data.LoadingFlightId;
			if(FinFlag=="F"&&request<=30){
				resultFlightList(0,"",1,LoadingFlightId);
			}
		}else if(data.status==0){
			resultLoading(0);
			$("#resultLoading").hide();
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