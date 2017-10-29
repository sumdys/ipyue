<?php
class OrderAction extends CommonAction{
	
	//////////////////////---业务组 begin---////////////////////////
	function newOrder(){//新订单
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['type']='newOrder';
		$data['no']=1;
		$data['dep']=1;
		$this->com($data);
	}	
	
	function waitTicketOrder(){//待出票订单
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['type']='waitTicketOrder';
		$data['no']=1;
		$data['dep']=1;
		$this->com($data);	
	}	
	
	function hasTicketOrder(){//已出票订单
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['type']='hasTicketOrder';
		$data['no']=1;
		$data['dep']=1;
		$this->com($data);			
	}	
	
	function finishOrder(){//已完成订单
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['type']='finishOrder';
		$data['no']=1;
		$data['dep']=1;
		$this->com($data);		
	}
	
	function abolishOrder(){//已取消订单
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['type']='abolishOrder';
		$data['no']=1;
		$data['dep']=1;
		$this->com($data);
	}		
	
	function addOrder(){//新增订单
		if(IS_AJAX){
			if(I('type') == 'type'){
				$wh['type_id']=I('typeID');
				$data['list']=D('CollectionBank')->where($wh)->field('id,bank')->select();				
				if($data['list']){
					$data['status']=1;
					$this->ajaxReturn($data);
				}else{
					$data['status']=0;
					$this->ajaxReturn($data);
				}
			}
		}
		$this->orderID='XS'.date('YmdHis',time()).rand(1000,9999);
		$this->supplier2=D('Supplier2')->field('id,supplier')->select();
		$this->display();		
	}
		
	function editOrder(){//编辑订单
		$this->supplier2=D('Supplier2')->field('id,supplier')->select();
		$this->orderInfo(I('id'));
	}	
	
	function editTicketOrder(){//编辑出票订单
		if($_POST){			
			//如果订票人手机号不为空，则进行会员验证
			if(!empty($_POST['memberTel'])){
				$member=array(
					'memberName'=>$_POST['memberName'],
					'memberTel'         =>$_POST['memberTel'],
					'memberQQ'          =>$_POST['memberQQ'],
					'memberEmail'       =>$_POST['memberEmail']				
				);
				$memberID=$this->verifyMember($member);
			}else{
				$memberID='';
			}
			
			//保险处理
			if($_POST['insuranceType'] ==1){
				$insuranceCost=$_POST['insuranceCost']*0.78;
			}elseif($_POST['insuranceType'] ==2){
				$insuranceCost=$_POST['insuranceCost']*0.70;
			}
			
			//乘机人 应收金额  利润
			$wh['id']=$_POST['id'];
			$info=D('Order')->where($wh)->field('passengers,procurementPrice,salePrice,taxation')->find();
			$passengers=object_to_array(json_decode($info['passengers']));
			$passengers=array_values($passengers);
			foreach($passengers as $key=>$val){
				$name=$_POST['cjrInfo'][$key+1]['name'];
				$name=explode('/',$name);
				$p=array(
					'first_name' =>$name[0],
					'last_name'  =>$name[1],
					'ticketNO'   =>$val['ticketNO'],
					'DOCS'       =>$_POST['cjrInfo'][$key+1]['DOCS']				
				);			
			}

			$data=array(
				'passengers'        =>json_encode($p),
				'payableAmount'     =>$info['procurementPrice']+$info['salePrice']+$_POST['insuranceCost'],
				'profit'            =>$info['salePrice']-$info['procurementPrice']-$info['taxation']-$insuranceCost-$_POST['supplierCost']-$_POST['interiorCost']-$_POST['otherCost'],
				'memberID'          =>$memberID,
				'PNR'               =>$_POST['PNR'],				
				'memberName'        =>$_POST['memberName'],
				'memberTel'         =>$_POST['memberTel'],
				'customerType'      =>$_POST['customerType'],
				'memberQQ'          =>$_POST['memberQQ'],
				'memberEmail'       =>$_POST['memberEmail'],						
				'airLine'           =>$_POST['airLine'],
				'insurance'         =>$insuranceCost,
				'insuranceType'     =>$_POST['insuranceType'],				
				'supplierCost'      =>$_POST['supplierCost'],
				'interiorCost'      =>$_POST['interiorCost'],				
				'otherCost'   	    =>$_POST['otherCost'],
				'supplierCostType'  =>$_POST['supplierCostType'],
				'interiorCostType'  =>$_POST['interiorCostType'],
				'otherCostType'   	=>$_POST['otherCostType'],								
				'payInfo'           =>json_encode($_POST['zfInfo']),
				'remark2'           =>$_POST['remark2']	
			);
			
				//提交财务
			if(empty($_POST['t'])){
				$data['modifyForBack']=1;//修改财务打回标记
				$data['BackByFinance']=0;//取消财务打回标记
				$data['ordeSupplement']=0;//取消补标			
			}	
			
			$res=D('Order')->where($wh)->data($data)->save();
			if($res){$this->succsss('提交成功');}else{$this->error('系统繁忙，请稍后再操作');};
		}else{
			$this->orderInfo(I('id'));
		}
	}
	
	function viewOrder(){//查看订单
		$this->orderInfo(I('id'));
	}
	
	function insertData(){//写入订单
		if($_POST){
			//数据验证
			$this->verification($_POST);
			
			//如果订票人手机号不为空，则进行会员验证
			if(!empty($_POST['memberTel'])){
				$member=array(
					'memberName'=>$_POST['memberName'],
					'memberTel'         =>$_POST['memberTel'],
					'memberQQ'          =>$_POST['memberQQ'],
					'memberEmail'       =>$_POST['memberEmail']				
				);
				$memberID=$this->verifyMember($member);
			}
			
			//保险处理
			if($_POST['insuranceType'] ==1){
				$insuranceCost=$_POST['insuranceCost']*0.78;
			}elseif($_POST['insuranceType'] ==2){
				$insuranceCost=$_POST['insuranceCost']*0.70;
			}
			
			foreach($_POST['cjrInfo'] as $key=>$val){
				if($val['name']  != ''){
					if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$val['name'])){$this->error('乘机人姓名不能有汉字');}
					if(preg_match("/^(-?\d+)(\.\d+)?$/",$val['name'])){$this->error('乘机人姓名不能有数字');}
					$name=explode('/',$val['name']);
					$passengers[]=array(
						'first_name'=>$name[0],
						'last_name'=>$name[1],
						'DOCS'=>$val['DOCS']
					);					
				}			
			}
	
			$data=array(
				'memberID'          =>$memberID,
				'PNR'               =>$_POST['PNR'],				
				'memberName'        =>$_POST['memberName'],
				'memberTel'         =>$_POST['memberTel'],
				'customerType'      =>$_POST['customerType'],
				'memberQQ'          =>$_POST['memberQQ'],
				'memberEmail'       =>$_POST['memberEmail'],				
				'passengers'        =>json_encode($passengers),
				'voyage'            =>json_encode($_POST['hcInfo']),			
				'airLine'           =>$_POST['airLine'],				
				'rebatePolicy'      =>$_POST['rebatePolicy'],
				'ticketOfficNO'     =>$_POST['ticketOfficNO'],
				'supplier'          =>$_POST['supplier'],
				'fileNumber'        =>$_POST['fileNumber'],							
				'procurementPrice'  =>$_POST['procurementPrice'],
				'salePrice'         =>$_POST['salePrice'],
				'taxation'          =>$_POST['taxation'],
				'insurance'         =>$insuranceCost,
				'insuranceType'     =>$_POST['insuranceType'],
				'payableAmount'     =>$_POST['salePrice']+$_POST['taxation']+$_POST['insurance'],//  应收金额= 销售价+税费+保险金额
				'profit'            =>$_POST['salePrice']-$_POST['procurementPrice']-$insuranceCost-$_POST['supplierCost']-$_POST['interiorCost']-$_POST['otherCost'],//利润=销售价-采购价
				'supplierCost'      =>$_POST['supplierCost'],
				'interiorCost'      =>$_POST['interiorCost'],				
				'otherCost'   	    =>$_POST['otherCost'],
				'supplierCostType'  =>$_POST['supplierCostType'],
				'interiorCostType'  =>$_POST['interiorCostType'],
				'otherCostType'   	=>$_POST['otherCostType'],								
				'payInfo'           =>json_encode($_POST['zfInfo']),
				'remark2'           =>$_POST['remark2']	
			);
					
			if($_POST['type'] == 'add'){
				$info="新增成功";
				$data['orderID']=$_POST['orderID'];
				$data['userID']=getUId();
				$data['create_time']=time();							
				$res=D('Order')->data($data)->add();				
			}		
			
			if($_POST['type'] == 'edit'){
				$info="编辑成功";
				if($_POST['orderBeBack'] == 1){
					$data['editForBeBack']=1;//订单回传重编辑标记
				}
				$data['update_time']=time();
				$wh['id']=$_POST['id'];				
				$res=D('Order')->where($wh)->data($data)->save();				
			}
			
			if($res){$this->success($info);}else{$this->error('系统繁忙，请稍后再操作');/*$this->error(D('Order')->getDbError());*/}
		}
	}		
	
	function handleOrder(){//订单操作：出票、加急出票、取消		
		if($_POST){
			$wh['id']=$_POST['id'];

			//取消
			if($_POST['type'] == 'cancel'){
				$data=array('newOrder'=>0,'abolishOrder'=>1);
				$info="取消成功";
			}
			
			//出票
			if($_POST['type'] == 'draw'){
				$data=array('newOrder'=>0,'waitTicketOrder'=>1,'orderBeBack'=>0);
				if($_POST['orderBeBack'] ==0){//如果订单被出票部打回后再出票，不需更改出票人
					$data['accept']=0;
				}				
				$info="提交出票成功";
			}	
			
			//加急出票
			if($_POST['type'] == 'urgent'){	
				$data=array('newOrder'=>0,'waitTicketOrder'=>1,'orderUrgent'=>1,'orderBeBack'=>0);
				if($_POST['orderBeBack'] ==0){//如果订单被出票部打回后再出票，不需更改出票人
					$data['accept']=0;
				}			
				
				$info="加急出票成功";
			}				
			
			$res=D('Order')->where($wh)->save($data);
			if($res){$this->success($info);}else{$this->error('系统繁忙，请稍后再操作');}
		}else{
			$id=I('id');
			$order=D('Order')->where('id='.$id)->field('id,orderID,orderBeBack')->find();
			$this->vo=$order;
			if(I('t') == 1){
				$this->display('drawOrder');
			}elseif(I('t') == 2){
				$this->display('urgentDrawOrder');
			}elseif(I('t') == 3){
				$this->display('cancelOrder');
			}
		}	
	}
	//////////////////////---业务组 end---///////////////////////////
	
		
	//////////////////////---出票组 begin---/////////////////////////	
	function ticketWaitForAccept(){//待接收订单
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['type']='accept';
		$data['no']=0;
		$data['dep']=2;
		$list=$this->com($data);	
	}		
	
	function ticketWaitForDraw(){//待出票订单
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['type']='accept';
		$data['no']=1;
		$data['dep']=2;
		$this->com($data);	
	}		
	
	function ticketHasTicket(){//已出票订单
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['type']='hasTicketOrder';
		$data['no']=1;
		$data['dep']=2;
		$list=$this->com($data);	
	}		
	
	function ticketEditForDraw(){//订单出票操作
		if($_POST){			
			$wh['id']=$_POST['id'];
			$info=D('Order')->where($wh)->field('passengers')->find();
			
			//判断该订单的支付信息是否完整，如果不完整，加上补标记			
			$payInfo=object_to_array(json_decode($info['payInfo']));
			if(empty($payInfo)){
				$data['ordeSupplement']=1;
			}else{
				foreach($payInfo as $key=>$val){
					if($val['procurementPrice']="" || $val['amount_type']="" || $val['bank']="" || $val['payTime']="" || $val['payType']=""){
						$data['ordeSupplement']=1;
					}				
				}
			}
			
			//把票号加入到乘机人信息里面
			$passengers=object_to_array(json_decode($info['passengers']));
			$passengers=array_values($passengers);
			
			foreach($_POST['ticketNO'] as $key=>$val){
				$p[]=array(
					'first_name' =>$passengers[$key]['first_name'],
					'last_name'  =>$passengers[$key]['last_name'],
					'DOCS'       =>$passengers[$key]['DOCS'],
					'ticketNO'   =>$_POST['ticketNO'][$key]				
				);
			}
			
			$data=array(
				'ticketOfficNO'=>$_POST['ticketOfficNO'],								
				'supplier'=>$_POST['supplier'],
				'backMoney' =>$_POST['backMoney'],
				'remark1' =>$_POST['remark1'],
				'passengers'   =>json_encode($p),
				'accept'  =>'',
				'waitTicketOrder'=>0,
				'hasTicketOrder' =>1,
				'update_time'  =>time(),
				'hasTicketTime'  =>time()				 
			);				

			$res=D('Order')->where($wh)->data($data)->save();
			if($res){$this->success('出票成功');}else{$this->error('系统繁忙，请稍后再操作');}
		}else{
			$this->supplier2=D('Supplier2')->field('id,supplier')->select();
			$this->orderInfo(I('id'));
		}
	}		
	
	function ticketTrunTo(){//转单
		if($_POST){
			$wh['orderBeBack']=0;
			$wh['accept']=1;
			$wh['drawer']=$_POST['drawer'];
			$count=D('Order')->where($wh)->count();
			if($count >= 2){
				$this->error('该出票员已接受了两个订单，无法再接受订单，请转给其他人');
			}else{
				$where['id']=$_POST['id'];
				$data=array('drawer'=>$_POST['drawer']);
				$res=D('Order')->where($where)->data($data)->save();
				if($res){$this->success('转单成功');}else{$this->error('系统繁忙，请稍后再操作');}
			}
		}else{
			$wh['id']=I('id');
			$this->list=D('Order')->where($wh)->field('id,userID,drawer,orderID')->relation(true)->find();
			$this->display();
		}
	}	
		
	function ticketPostBack(){//打回
		if($_POST){
			$wh['id']=$_POST['id'];
			if($_POST['PostBackRemark'] ==''){$this->error('请输入说明');}
			$data=array(
				'PostBackRemark'=>$_POST['PostBackRemark'],
				'newOrder'      =>1,
				'orderBeBack'   =>1,
				'waitTicketOrder'  =>0				
			);
			$res=D('Order')->where($wh)->data($data)->save();
			if($res){$this->success('打回成功');}else{$this->error('系统繁忙，请稍后再操作');}
		}else{
			$this->id=I('id');
			$this->display();
		}		
	}
		
	function ticketAccept(){//接受订单
		if($_POST){
			$wh['id']=$_POST['id'];
			$data=array('accept'=>1,'drawer'=>getUId());
			$res=D('Order')->where($wh)->data($data)->save();
			if($res){$this->success('接受成功');}else{$this->error('系统繁忙，请稍后再操作');}
		}else{
			$wh['accept']=1;
			$wh['drawer']=getUId();
			$wh['orderBeBack']=0;				
			$count=D('Order')->where($wh)->count();
			if($count >= 2){
				$this->error('你最多只能同时接受两个订单');
			}else{
				$id=I('id');
				$orderID=D('Order')->where('id='.$id)->getField('orderID');
				$this->id=$id;
				$this->orderID=$orderID;			
				$this->display();
			}
		}
	}
	//////////////////////---出票组 end---///////////////////////////
	
	//////////////////////---财务组 begin---/////////////////////////	
	function financeNNOrder(){//待收款、待付款	
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['financStatus']=1;
		$this->com($data);
	}	
	
	function financeNYOrder(){//待收款、已付款
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['financStatus']=2;
		$this->com($data);
	}	
	
	function financeYNOrder(){//已收款、待付款
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['financStatus']=3;
		$this->com($data);
	}		
	
	function financeYYOrder(){//已收款、已付款
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['financStatus']=4;
		$this->com($data);	
	}	
	
	function financeHasDone(){//已完成
		if(I('so')){
			$data['so']=I('so');
		}
		if(I('date')){
			$data['date']=I('date');
		}
		$data['financStatus']=5;
		$this->com($data);		
	}		
	
	function financePayment(){//付款确认
		$this->orderInfo(I('id'));		
	}	
	
	function financeGathering(){//收款确认
		$this->orderInfo(I('id'));
	}	
	
	function updateFinance(){//收款、付款确认提交
		if($_POST){
			$wh['id']=$_POST['id'];	
			
			//付款
			if($_POST['finance'] == 1){
				$data['paymentFinanceRemark']=$_POST['remark'];	
				$data['paymentBank']=$_POST['bank'];	
				if($_POST['t'] == 1){//临时保存
					$data['temporarySave']=1;					
				}else{
					$data['temporarySave']=0; //如果订单有临时保存，取消临时保存标
					$data['paymentOrNot']=1;
					$data['paymentFinanceID']=getUId();
				}			
			}
			
			//收款
			if($_POST['finance'] == 2){
				$data['gatheringFinanceRemark']=$_POST['remark'];			
				if($_POST['t'] == 1){//临时保存
					$data['temporarySave']=1;					
				}else{
					$data['modifyForBack']=0; //如果订单有打回，取消修改财务打回标记
					$data['BackByFinance']=0; //如果订单有打回，取消打回标记
					$data['temporarySave']=0; //如果订单有临时保存，取消临时保存标记
					$data['gatheringOrNot']=1;
					$data['gatheringFinanceID']=getUId();
				}			
			}		
			$data['update_time']=time();
			$res=D('Order')->where($wh)->data($data)->save();
			if($res){$this->success('保存成功');}else{$this->error('系统繁忙，请稍后再操作');}			
		}else{
			$this->error('非法操作');
		}	
	}	
	
	function financePostBack(){//打回说明
		if($_POST){
			if($_POST['remark'] == ''){$this->error('请输入说明！');}			
			$data=array(
				'BackByFinance'=>1,
				'remarkByFinance'=>$_POST['remark']
			);
			$wh['id']=$_POST['id'];
			$res=D('Order')->where($wh)->data($data)->save();
			if($res){$this->success('打回成功');}else{$this->error('系统繁忙，请稍后再操作');}
		}else{
			$this->id=I('id');
			$this->display();
		}
	}
	
	function financeReview(){//财务主管复核
		if($_POST){			
			//保险处理
			if($_POST['insuranceType'] ==1){
				$insuranceCost=$_POST['insuranceCost']*0.78;
			}elseif($_POST['insuranceType'] ==2){
				$insuranceCost=$_POST['insuranceCost']*0.70;
			}		
			
			$data=array(			
				'procurementPrice'  =>$_POST['procurementPrice'],
				'salePrice'         =>$_POST['salePrice'],
				'taxation'          =>$_POST['taxation'],
				'insurance'         =>$insuranceCost,
				'insuranceType'     =>$_POST['insuranceType'],
				'payableAmount'     =>$_POST['salePrice']+$_POST['taxation']+$_POST['insurance'],//  应收金额= 销售价+税费+保险金额
				'profit'            =>$_POST['salePrice']-$_POST['procurementPrice']-$insuranceCost-$_POST['supplierCost']-$_POST['interiorCost']-$_POST['otherCost'],//利润=销售价-采购价
				'supplierCost'      =>$_POST['supplierCost'],
				'interiorCost'      =>$_POST['interiorCost'],				
				'otherCost'   	    =>$_POST['otherCost'],
				'supplierCostType'  =>$_POST['supplierCostType'],
				'interiorCostType'  =>$_POST['interiorCostType'],
				'otherCostType'   	=>$_POST['otherCostType'],								
				'payInfo'           =>json_encode($_POST['zfInfo']),
				'finishOrder'       =>1,
				'finishTime'       =>time()				
			);
			$wh['id']=$_POST['id'];				
			$res=D('Order')->where($wh)->data($data)->save();
			if($res){$this->success($info);}else{$this->error('系统繁忙，请稍后再操作');}			
		}else{
			$this->orderInfo(I('id'));
		}
	}
	
	function financeModify(){//修改采购价
		if($_POST){
			if(!preg_match("/^(-?\d+)(\.\d+)?$/",$_POST['price'])){$this->error('采购价只能输入数字');}
			$wh['id']=$_POST['id'];
			$data['procurementPrice']=$_POST['price'];
			$res=D('Order')->where($wh)->save($data);
		}else{
			$wh['id']=I('id');
			$list=D('Order')->where($wh)->field('id,orderID,procurementPrice')->find();
			$this->list=$list;
			$this->display();
		}	
	}
	//////////////////////---财务组 end---///////////////////////////
	
	//////////公共调用函数
	function orderInfo($id){//一个订单详情，主要用于编辑和查看订单
		$wh['id']=$id;
		$list=D('Order')->where($wh)->relation(true)->find();	
		
		$payInfo=object_to_array(json_decode($list['payInfo']));		
		foreach($payInfo as $key=>$val){
			$where['type_id']=$val['amount_type'];
			$bankInfo=D('CollectionBank')->field('amount_type,bank')->where($where)->find();
			$payInfo[$key]['amount']=$bankInfo['amount_type'];
			$payInfo[$key]['bankName']=$bankInfo['bank'];
			$payInfo[$key]['payType']=$this->payType($val['payType']);
			$payInfo[$key]['banklist']=D('CollectionBank')->field('bank')->where($where)->select();
		}
		$list['payInfo']=$payInfo;
		$list['passengers']=object_to_array(json_decode($list['passengers']));
		$list['voyage']=object_to_array(json_decode($list['voyage']));		
		
		if($_GET["_URL_"][2] != 'editOrder'){
			$list['airLine']=$this->airLine($list['airLine']);
			$list['gatheringBank']=$this->gatheringBank($list['gatheringBank']);		
			$list['supplierCostType']=$this->supplierCostType($list['supplierCostType']);	
			$list['interiorCostType']=$this->interiorCostType($list['interiorCostType']);	
			$list['otherCostType']=$this->otherCostType($list['otherCostType']);
			$list['customerType']=$this->customerType($list['customerType']);
			$list['insuranceType']=$this->insuranceType($list['insuranceType']);			
		}
		$list['countPasser']=count($list['passengers']);
		$list['countPay']=count($list['payInfo']);
		$list['countVoyage']=count($list['voyage']);
		
		//财务状态
		if($list['paymentOrNot']==0 && $list['gatheringOrNot']==0){
			$list['financeStatus']=$this->financeStatus(1);
		}elseif($list['paymentOrNot']==0 && $list['gatheringOrNot']==1){
			$list['financeStatus']=$this->financeStatus(2);
		}elseif($list['paymentOrNot']==1 && $list['gatheringOrNot']==0){
			$list['financeStatus']=$this->financeStatus(3);
		}elseif($list['paymentOrNot']==1 && $list['gatheringOrNot']==1){
			$list['financeStatus']=$this->financeStatus(4);
		}
		
		//print_r($list);
		$e=I('e');
		if(!empty($e)){$this->e=$e;}
		$this->assign('list',$list);
		$this->display();	
	}
	
	function com($data){//订单列表，主要用于业务系统每个页面
		if($data['so'] != ''){
			if(strstr($data['so'],':')){
				$so=explode(':',$data['so']);
				$map[$so[0]]=$so[1];
			}else{							
				$wh['memberName|memberTel|PNR']= array('like',"%".$data['so'] ."%");
			}
		} 
        if($data['date'] != ''){
			$now=strtotime($data['date']);
			$now1=strtotime('+1 day',$now);
            $wh['create_time']=array('between',$now,$now1);
        }		
		$orderDB=D('Order');
		$userDB=D('user');
		$userInfo=$userDB->where('id='.getUId())->field('id,company_id,department_id,username')->find();	
		//print_r($userInfo);
		//业务组
		if($data['dep'] == 1){
			$total=$this->totalNum(1);			
			//业务部主管或经理
			$businessManager=array('can402','l501','sha893','pek503','szx919');	
			if(in_array($userInfo['username'],$businessManager)){
				//模板某个功能权限控制
				$this->business=1;
				//条件构造
				$where['company_id']=$userInfo['company_id'];
				$where['department_id']=$userInfo['department_id'];
				$user=$userDB->where($where)->field('id')->select();
				foreach($user as $key=>$val){$userID[]=$val['id'];}
				$wh['userID']=array('in',$userID);
			}else{
				//业务员
				if($userInfo['id'] !=1){
					$wh['userID']=$userInfo['id'];
				}
			}			
			$wh[$data['type']]=$data['no'];				
		}
	
		//出票组
		if($data['dep'] == 2){
			if($data['no'] == 0){
				$total=$this->totalNum(2);
			}else{
				$total=$this->totalNum(3);
			}			
			
			if($userInfo['username'] == 'cp01'){//出票部主管
				$this->ticketAgen=1;
			}else{				
				if(getUId() !=1){
					if($data['no'] !=0){
						if($data['type']=='accept'){
							if($data['no'] ==1){
								$wh['orderBeBack']=0;
							}						
							$wh['drawer']=getUId();
						}
					}
				}				
			}			
			$wh[$data['type']]=$data['no'];
		}
		
		
		//财务组
		if(!empty($data['financStatus'])){
			$total=$this->totalNum(4);	
			//财务部
			$finance=array('cw05','cw02','cw03','cw07','sha889','szx','pek501');	
			//付款财务
			$paymentFinance=array('cw02','cw03');					
			if(in_array($userInfo['username'],$paymentFinance)){$this->finance=1;}
			//收款财务
			$gatheringFinance=array('cw07');
			if(in_array($userInfo['username'],$gatheringFinance)){$this->finance=2;}
			//财务主管
			$financeManager=array('cw05','sha889','szx','pek501');
			if(in_array($userInfo['username'],$financeManager)){$this->finance=3;}		

			//构造查询条件
			if(in_array($userInfo['username'],$finance)){
				$where['company_id']=$userInfo['company']['id'];
				$where['department_id']=array('in','9,10');
				$user=$userDB->where($where)->field('id')->select();	
				foreach($user as $key=>$val){$userID[]=$val['id'];}
				$wh['userID']=array('in',$userID);
			}		
	
			$wh['hasTicketOrder']=1;
			$wh['ordeSupplement']=0;
			if($data['financStatus'] == 1){
				// 未收款 未付款				
				$wh['gatheringOrNot']=0;
				$wh['paymentOrNot']=0;
			}elseif($data['financStatus'] == 2){
				//待收款、已付款				
				$wh['gatheringOrNot']=0;
				$wh['paymentOrNot']=1;
			}elseif($data['financStatus'] == 3){
				//已收款、待付款				
				$wh['gatheringOrNot']=1;
				$wh['paymentOrNot']=0;
			}elseif($data['financStatus'] == 4){
				//已收款、已付款
				$wh['paymentOrNot']=1;
				$wh['gatheringOrNot']=1;
				$wh['finishOrder']=0;
			}else{
				//完成
				$wh['finishOrder']=1;
			}
		}
				
		$map['_complex'] = $wh;	
        $this->map=$map;
        $this->order='orderUrgent desc,id desc';		  
        $this->relation = true;
        parent::index($orderDB);		
		$list=$this->list;
		
		if(!empty($data['financStatus'])){//财务
			foreach($list as $key=>$val){
				$payInfo=object_to_array(json_decode($val['payInfo']));
				
				foreach($payInfo as $k=>$v){
					$whBank['id']=$v['bank'];
					$pay=D('CollectionBank')->field('amount_type,bank')->where($whBank)->find();
					$payInfo[$k]['bank']=$pay['bank'];
				}
				$list[$key]['payInfo']=array_values($payInfo);
				$count=count($payInfo);
				if($count == 0){$count=1;}
				$list[$key]['countPayInfo']=$count;
			}
		}
		//print_r($list);
		$this->total=$total;
		$this->list=$list;			
		$this->display();
	}		
		
	function verification($data){//数据验证,主要用于新增和编辑订单提交
		if($data['PNR'] ==''){$this->error('PNR不能为空');}		
		if($data['rebatePolicy'] ==''){$this->error('返点政策不能为空');}
		if($data['ticketOfficNO'] ==''){$this->error('出票OFFICE号不能为空');}		
		if($data['supplier'] ==''){$this->error('供应商不能为空');}
		if($data['procurementPrice'] ==''){$this->error('采购价不能为空');}
		if(!preg_match("/^(-?\d+)(\.\d+)?$/",$data['procurementPrice'])){$this->error('采购价只能是数字');}
		if($data['salePrice'] ==''){$this->error('销售价不能为空');}
		if(!preg_match("/^(-?\d+)(\.\d+)?$/",$data['salePrice'])){$this->error('销售价只能是数字');}
		if($data['taxation'] ==''){$this->error('税费不能为空');}
		if(!preg_match("/^(-?\d+)(\.\d+)?$/",$data['taxation'])){$this->error('税费只能是数字');}
		
//		if($data['insurance'] ==''){$this->error('保险金额不能为空');}
//		if(!preg_match("/^(-?\d+)(\.\d+)?$/",$data['insurance'])){$this->error('保险金额只能是数字');}
//		if($data['supplierCost'] ==''){$this->error('供应商费用不能为空');}
//		if(!preg_match("/^(-?\d+)(\.\d+)?$/",$data['supplierCost'])){$this->error('供应商费用只能是数字');}
//		if($data['interiorCost'] ==''){$this->error('内部费用不能为空');}
//		if(!preg_match("/^(-?\d+)(\.\d+)?$/",$data['interiorCost'])){$this->error('内部费用只能是数字');}
//		if($data['otherCost'] ==''){$this->error('其他费用不能为空');}
//		if(!preg_match("/^(-?\d+)(\.\d+)?$/",$data['otherCost'])){$this->error('其他费用只能是数字');}		
//		if($data['memberName'] ==''){$this->error('订票人姓名不能为空');}
//		if($data['memberTel'] ==''){$this->error('手机号不能为空');}
//		if(!preg_match("/^[0-9]+$/",$data['memberTel'])){$this->error('手机号只能是数字');}
//		if($data['cjrInfo'][1]['name'] == ''){$this->error('乘机人姓名不能为空');}
//		if($data['cjrInfo'][1]['DOCS'] == ''){$this->error('DOCS不能为空');}
//		if($data['hcInfo'][1]['departure'] == ''){$this->error('始发地不能为空');}
//		if($data['hcInfo'][1]['destination'] == ''){$this->error('目的地不能为空');}
//		if($data['hcInfo'][1]['date'] == ''){$this->error('日期不能为空');}
//		if($data['fileNumber'] ==''){$this->error('文件号不能为空');		
	}	
		
	function verifyMember($info){//验证手机号，如果手机号已经有就关联会员表，如果没有就注册然后再关联
		$memberDB=D('Member');
		$wh['mobile']=$info['memberTel'];
		$id=$memberDB->where($wh)->getField('id');
		if(!empty($id)){
			return $id;
		}else{
			$salt=generateSalt();			
			$password=hashPassword(123456,$salt);	
			$data=array(
				'name' =>$info['memberName'],
				'username'=>$info['memberTel'],
				'mobile'=>$info['memberTel'],
				'qq'    =>$info['memberQQ'],
				'email' =>$info['memberEmail'],
				'password'	=>$password,
				'salt'     =>$salt,
				'user_id'=>getUId(),
				'source' =>1,
				'create_time'=>time()
			);				
			$res=$memberDB->data($data)->add();
			if($res){
				D('Points')->addPoints($res,1000,"成功注册会员赠1000分",0);
				D('Points')->addPoints($res,50,"成功注册 获得50现金券",2);
				//执行发送通知
				$data1['mobile']=$info['memberTel'];
				$data1['name']=$info['memberName'];//姓名
				$data1['password']='123456';
				$userInfo=$this->userInfo;
				$data1['weburl']=$userInfo['department_id']==10?"http://sl.aishangfei.com":"http://www.aishangfei.com";
				D("Message")->send("manually_add_member",$data1);
				
				$id=$memberDB->where($wh)->getField('id');
				return $id;
			}else{
				//$this->error($memberDB->getDbError());
				$this->error('数据出错，系统无法写入数据');
			}
		}
	}
	
	function totalNum($data){
		$orderDB=D('Order');
		$userDB=D('user');
		$userInfo=$userDB->where('id='.getUId())->field('id,company_id,department_id,username')->find();	
		
		//业务组
		if($data == 1){
			//业务部主管或经理
			$businessManager=array('can402','l501','sha893','pek503','szx919');
			if($userInfo['username'] != 'admin'){
				if(in_array($userInfo['username'],$businessManager)){
					//条件构造
					$where['company_id']=$userInfo['company']['id'];
					$where['department_id']=$userInfo['department']['id'];
					$user=$userDB->where($where)->field('id')->select();
					foreach($user as $key=>$val){$userID[]=$val['id'];}
					$wh['userID']=array('in',$userID);
				}else{
					//业务员
					if($userInfo['id'] != 1){
						$wh['userID']=$userInfo['id'];
					}
				}
			}
			
			//新订单
			$wh['newOrder']=1;
			$totle[0]=$orderDB->where($wh)->count();
			
			//待出票
			unset($wh['newOrder']);
			$wh['waitTicketOrder']=1;
			$totle[1]=$orderDB->where($wh)->count();
			
			//已出票
			unset($wh['waitTicketOrder']);
			$wh['hasTicketOrder']=1;
			$totle[2]=$orderDB->where($wh)->count();		
			
			//已完成
			unset($wh['hasTicketOrder']);
			$wh['finishOrder']=1;
			$totle[3]=$orderDB->where($wh)->count();	
			
			//已取消
			unset($wh['finishOrder']);
			$wh['abolishOrder']=1;
			$totle[4]=$orderDB->where($wh)->count();				
		}
		
		//出票组
		if($data >= 2){
			if($userInfo['id'] !=1){
				if($userInfo['id']!=357){
					if($data !=2){					
						$wh['drawer']=getUId();
					}
				}
			}

			//待接受			
			$wh['accept']=0;
			$totle[0]=$orderDB->where($wh)->count();			
			
			//待出票
			$wh['accept']=1;
			$wh['orderBeBack']=0;
			
			$totle[1]=$orderDB->where($wh)->count();	
			
			//已出票
			unset($wh['accept']);
			unset($wh['orderBeBack']);
			$wh['hasTicketOrder']=1;
			$totle[2]=$orderDB->where($wh)->count();
		}		
		
		//财务组
		if($data == 4){	
			$where['company_id']=$userInfo['company_id'];
			$where['department_id']=array('in','9,10');
			$user=$userDB->where($where)->field('id')->select();	
			foreach($user as $key=>$val){$userID[]=$val['id'];}
			
			$wh['userID']=array('in',$userID);	
			$wh['hasTicketOrder']=1;
			$wh['ordeSupplement']=0;			

			//未付款 未收款			
			$wh['gatheringOrNot']=0;
			$wh['paymentOrNot']=0;
			$totle[0]=$orderDB->where($wh)->count();
			//待收款、已付款			
			$wh['gatheringOrNot']=0;
			$wh['paymentOrNot']=1;
			$totle[1]=$orderDB->where($wh)->count();
			//已收款、待付款			
			$wh['gatheringOrNot']=1;
			$wh['paymentOrNot']=0;
			$totle[2]=$orderDB->where($wh)->count();
			//已收款、已付款
			$wh['paymentOrNot']=1;
			$wh['gatheringOrNot']=1;
			$wh['finishOrder']=0;
			$totle[3]=$orderDB->where($wh)->count();
			//完成
			$wh['finishOrder']=1;
			$totle[4]=$orderDB->where($wh)->count();
		}
		//print_r($wh);
		return $totle;	
	}
	
	

    /*
    function payOrder(){
        $this->relation=true;
        if(I('so')){
            $where['member_id']  = array('like',"%".I('so')."%");
            $where['order_id_arr']  = array('like',"%".$_POST['so']."%");
            $where['route']  = array('like',"%".I('so')."%");
            $where['order_price']  = array('like',"%".I('so')."%");
            $where['remark']  = array('like',"%".I('so')."%");
            $where['_logic'] = 'or';
            $map['_complex']=$where;
        }
        if(I('so_date1')&& I('so_date2')){
            $map['update_time']=array(array('egt',strtotime(I('so_date1'))),array('elt',strtotime(I('so_date2'))));
        }
        $this->modelName="PayOrder";

        $this->order='id desc';
        $this->map = $map;
        parent::index();
    }
  */
    /*
     * 支付订单
     */

    function payOrder(){
        $this->relation=true;
        if(I('so')){
            $where['member_id']  = array('like',"%".I('so')."%");
            $where['order_id_arr']  = array('like',"%".$_POST['so']."%");
            $where['route']  = array('like',"%".I('so')."%");
            $where['order_price']  = array('like',"%".I('so')."%");
            $where['remark']  = array('like',"%".I('so')."%");
            $where['_logic'] = 'or';
            $map['_complex']=$where;
        }
        $map['pay_state']=1;
//      if(I('so_date1')&& I('so_date2')){
//          $map['update_time']=array(array('egt',strtotime(I('so_date1'))),array('elt',strtotime(I('so_date2'))));
//      }
        $this->modelName="TripOrder";

        $this->order='id desc';
        $this->map = $map;
        parent::index();
    }

	//asms订单
    function asmsOrder(){
        R('Asms/orderList');
        $this->display('Asms/orderList');
    }	
	
	//订单管理
	function index(){//待支付订单	
		if($_POST){$conditon=$_POST;}
		$conditon['pay_state']=0;
		$this->index1_2_3($conditon);	
	}
	
	function index2(){//已支付订单
		if($_POST){$conditon=$_POST;}
		$conditon['pay_state']=1;
		$this->index1_2_3($conditon);
	}	
	
	function index3(){//已取消订单
		if($_POST){$conditon=$_POST;}
		$conditon['pay_state']=2;
		$this->index1_2_3($conditon);
	}

	function index1_2_3($conditon){
		if($conditon['so'] != ''){
			if(strstr($conditon['so'],':')){
				$so=explode(':',$conditon['so']);
				$map[$so[0]]=$so[1];
			}else{							
				$where['hyid|ddbh']= array('like',"%".$conditon['so'] ."%");
			}
		}
		if(getUid() != 1){
			$where['user_id']=getUid();
		}
		$map['pay_state']=$conditon['pay_state'];
		
		
//		$map['_complex'] = $where;	
		$this->map=$map;
		$this->order='id desc';
		//$this->relation = true;
		parent::index(D("TripOrder"));		
		$list=$this->list;
		foreach($list as $k=>$v){			
			$hc=str_split($hc,3);
			$hc= D("City")->getCity($hc);
			$list[$k]['hc_n']=implode('->',$hc);				
			$list[$k]['title']=M('Freetour')->where('id='.$v['freetour_id'])->getField('title');
			$list[$k]['jp_type']=$this->jpType($v['lx']);
		}		
		$this->list=$list;
		$this->display();
	}
	/*
	function index1_2_3($conditon){
		if($conditon['so'] != ''){
			if(strstr($conditon['so'],':')){
				$so=explode(':',$conditon['so']);
				$map[$so[0]]=$so[1];
			}else{							
				$where['hyid|ddbh']= array('like',"%".$conditon['so'] ."%");
			}
		}
		if(getUid() != 1){
			$where['user_id']=getUid();
		}
		if($conditon['type'] == 8){
			$where['ddzt']=array('in','7,8');
		}else{
			$where['ddzt']=array('not in','7,8'); 
			$where['zf_fkf']=$conditon['type'];
		}	
		
		$map['_complex'] = $where;	
		$this->map=$map;
		$this->order='update_time desc';
		//$this->relation = true;
		parent::index(D("AsmsOrder"));		
		$list=$this->list;
		
		foreach($list as $k=>$v){			
			$hc= D("AsmsOrder")->format($v['hc']);
			$hc=str_split($hc,3);
			$hc= D("City")->getCity($hc);
			$list[$k]['hc_n']=implode('->',$hc);				

			$list[$k]['jp_type']=$this->jpType($v['lx']);
		}		
		$this->list=$list;
		$this->display();
	}*/
	
	//编辑订单
	function order_edit(){		
		$orderDB=D('AsmsOrder');
		$where['ddbh']= I('id');
		$list=$orderDB->where($where)->find();	
				
		//航班信息	
		$hd_info=json_decode($list['hd_info']);
		$hdinfo=$this->hb($hd_info);
		
		//乘客信息
		$cjr_info=json_decode($list['cjr_info']);		
		$cjr_info=get_object_vars($cjr_info);		
		foreach($cjr_info as $k=>$v){
			if($v->cjr_cjrlx == 1){$v->lx = '成人';$men++;$this->assign('men',$men);}
			if($v->cjr_cjrlx == 2){$v->lx = '儿童';$chl++;$this->assign('chl',$chl);}
			if($v->cjr_cjrlx == 3){$v->lx = '婴儿';$baby++;$this->assign('baby',$baby);}
		}
		
		//支付状态
		$list['zf_status']=$this->zf($list['zf_fkf']);
		
		$this->assign('hdinfo',$hdinfo);
		$this->assign('cjr_info',$cjr_info);
	    $this->assign('list',$list);
		
		if($_POST){
			if($_POST['xsj'] == ""){				
				$this->error('票面总价不能为空,请输入数字');
			}			
			if($_POST['sf'] == ""){				
				$this->error('总税费不能为空,请输入数字，如无请填0');
			}	
			if($_POST['taxa']==""){				
				$this->error('总保险费不能为空,请输入数字，如无请填0');
			}				
			if($_POST['jsf'] == ""){				
				$this->error('总机建费不能为空,请输入数字，如无请填0');
			}
			if($_POST['ysje'] == ""){				
				$this->error('应付金额不能为空,请输入数字，如无请填0');
			}			
			if($_POST['athud']== ""){				
				$this->error('成人数量不能为空,请输入数字，如无请填0');
			}
			if($_POST['chilren']== ""){				
				$this->error('儿童数量不能为空,请输入数字，如无请填0');
			}			
			if($_POST['baby']== ""){				
				$this->error('婴儿数量不能为空,请输入数字，如无请填0');
			}
			if($_POST['nklxr']==""){				
				$this->error('联系人姓名不能为空');
			}				
			if($_POST['lxdh']==""){				
				$this->error('联系人手机号不能为空');
			}
			
			$orderDB=D('AsmsOrder');
			//航程信息
			if($_POST['t'] == 1){//单程
				if(empty($_POST['hbh1'])){				
					$this->error('航班号不能为空');
				}				
				if(empty($_POST['cw1'])){				
					$this->error('舱位不能为空');
				}				
				if(empty($_POST['fjjx1'])){				
					$this->error('机型不能为空');
				}	
				if(empty($_POST['from1'])){				
					$this->error('出发城市不能为空');
				}					
				if(empty($_POST['to1'])){				
					$this->error('到达城市不能为空');
				}	
				if(empty($_POST['date1'])){				
					$this->error('出发日期不能为空');
				}	
				if(empty($_POST['time1'])){				
					$this->error('出发时间不能为空');
				}					
				$from_wz1=stripos($_POST['from1'],'(');
				$cityname1=substr($_POST['from1'],0,$from_wz1);//出发城市名-中文
				$hd_cfcity1=substr($_POST['from1'],-4,-1);//出发城市名-三字码
				
				$to_wz1=stripos($_POST['to1'],'(');
				$ddcityname1=substr($_POST['to1'],0,$to_wz1);//中文
				$hd_ddcity1=substr($_POST['to1'],-4,-1);	//三字码						   
											  
				$hb=array();
				$hb[]=array(
					'hbh'       =>$_POST['hbh1'],
					'cw'        =>$_POST['cw1'],				
					'hd_cfsj'   =>$_POST['date1'],
					'hd_cfsj_p' =>$_POST['time1'],
					'cfsj'      =>$_POST['date1'].'&nbsp;'.$_POST['time1'],
					'hd_fjjx'   =>$_POST['fjjx1'],
					'hc'        =>$hd_cfcity1.$hd_ddcity1.'$nbsp;$nbsp;'.$cityname1.'-'.$ddcityname1,
					'hd_cfcity' =>$hd_cfcity1,
					'hd_ddcity' =>$hd_ddcity1,
					'hd_cityname'=>$cityname1,					
					'hd_ddcityname'=>$ddcityname1					
				);	
				$hb_info=json_encode($hb);
				$data['lx']=1;//类型-单程
				$data['hc']=$hd_cfcity1.$hd_ddcity1;//航程
				$data['hbh']=$_POST['hbh1'];//航班号
				$data['qfsj']=$_POST['date1'].''.$_POST['time1'];//起飞时间
				$data['cw']=$_POST['cw1'];//舱位
			}
						
			if($_POST['t'] == 2){//往返
				if(empty($_POST['hbh2']) || empty($_POST['hbh3'])){				
					$this->error('航班号不能为空');
				}				
				if(empty($_POST['cw2']) || empty($_POST['cw3'])){				
					$this->error('舱位不能为空');
				}				
				if(empty($_POST['fjjx2']) || empty($_POST['fjjx3'])){				
					$this->error('机型不能为空');
				}	
				if(empty($_POST['from2']) || empty($_POST['from3'])){				
					$this->error('出发城市不能为空');
				}					
				if(empty($_POST['to2']) || empty($_POST['to3'])){				
					$this->error('到达城市不能为空');
				}	
				if(empty($_POST['date2']) || empty($_POST['date3'])){				
					$this->error('出发日期不能为空');
				}	
				if(empty($_POST['time2']) || empty($_POST['time3'])){				
					$this->error('出发时间不能为空');
				}			
				$hb=array();
				//去程
				$from_wz2=stripos($_POST['from2'],'(');
				$cityname2=substr($_POST['from2'],0,$from_wz2);//出发城市名-中文
				$hd_cfcity2=substr($_POST['from2'],-4,-1);//出发城市名-三字码
				
				$to_wz2=stripos($_POST['to2'],'(');
				$ddcityname2=substr($_POST['to2'],0,$to_wz2);//中文
				$hd_ddcity2=substr($_POST['to2'],-4,-1);	//三字码	
				
				$hb[]=array(
					'hbh'       =>$_POST['hbh2'],
					'cw'        =>$_POST['cw2'],				
					'hd_cfsj'   =>$_POST['date2'],
					'hd_cfsj_p' =>$_POST['time2'],
					'cfsj'      =>$_POST['date2'].'&nbsp;'.$_POST['time2'],
					'hd_fjjx'   =>$_POST['fjjx1'],
					'hc'        =>$hd_cfcity2.$hd_ddcity2.'""'.$cityname2.'-'.$ddcityname2,
					'hd_cfcity' =>$hd_cfcity2,
					'hd_ddcity' =>$hd_ddcity2,
					'hd_cityname'=>$cityname2,					
					'hd_ddcityname'=>$ddcityname2	
				);
				
				//返程
				$from_wz3=stripos($_POST['from3'],'(');
				$cityname3=substr($_POST['from3'],0,$from_wz3);//出发城市名-中文
				$hd_cfcity3=substr($_POST['from3'],-4,-1);//出发城市名-三字码
				
				$to_wz3=stripos($_POST['to3'],'(');
				$ddcityname3=substr($_POST['to3'],0,$to_wz3);//中文
				$hd_ddcity3=substr($_POST['to3'],-4,-1);	//三字码					
				$hb[]=array(
					'hbh'       =>$_POST['hbh3'],
					'cw'        =>$_POST['cw3'],				
					'hd_bzbz'   =>$_POST['date3'],
					'hd_bzbz_p' =>$_POST['time3'],
					'ddsj'      =>$_POST['date3'].'&nbsp;'.$_POST['time3'],
					'hd_fjjx'   =>$_POST['fjjx1'],
					'hc'        =>$hd_cfcity3.$hd_ddcity3.'$nbsp;$nbsp;'.$cityname3.'-'.$ddcityname3,
					'hd_cfcity' =>$hd_cfcity3,
					'hd_ddcity' =>$hd_ddcity3,
					'hd_cityname'=>$cityname3,					
					'hd_ddcityname'=>$ddcityname3
				);
				$hb_info=json_encode($hb);
				$data['lx']=2; //类型-往返
				$data['hc']=$hd_cfcity2.$hd_ddcity2.$hd_cfcity3.$hd_ddcity3;//航程
				$data['hbh']=$_POST['hbh2'].'-'.$_POST['hbh3'];//航班号
				$data['qfsj']=$_POST['date2'].''.$_POST['time2'];//起飞时间
				$data['cw']=$_POST['cw2'];//舱位
			}
			
			if($_POST['t'] == 3){//多程			
				$hb=array();
				$hcdata=$_POST['hcdata'];
				foreach($hcdata['hbh'] as $k=>$v){
					if(empty($hcdata['hbh'][$k])){				
						$this->error('航班号不能为空');
					}				
					if(empty($hcdata['cw'][$k])){				
						$this->error('舱位不能为空');
					}				
					if(empty($hcdata['fjjx'][$k])){				
						$this->error('机型不能为空');
					}	
					if(empty($hcdata['from'][$k])){				
						$this->error('出发城市不能为空');
					}					
					if(empty($hcdata['to'][$k])){				
						$this->error('到达城市不能为空');
					}	
					if(empty($hcdata['date'][$k])){				
						$this->error('出发日期不能为空');
					}	
					if(empty($hcdata['time'][$k])){				
						$this->error('出发时间不能为空');
					}					
					
					$from_wz=stripos($hcdata['from'][$k],'(');
					$cityname=substr($hcdata['from'][$k],0,$from_wz);//出发城市名-中文
					$hd_cfcity=substr($hcdata['from'][$k],-4,-1);//出发城市名-三字码
					
					$to_wz=stripos($hcdata['to'][$k],'(');
					$ddcityname=substr($hcdata['to'][$k],0,$to_wz);//中文
					$hd_ddcity=substr($hcdata['to'][$k],-4,-1);	//三字码
					
					$hb[]=array(
						'hbh'       =>$hcdata['hbh'][$k],
						'cw'        =>$hcdata['cw'][$k],
						'hd_cfsj'   =>$hcdata['date'][$k],
						'hd_cfsj_p' =>$hcdata['time'][$k],
						'cfsj'      =>$hcdata['date'][$k].'&nbsp;'.$hcdata['time'][$k],
						'hd_fjjx'   =>$hcdata['fjjx'][$k],
						'hc'        =>$hd_cfcity.$hd_ddcity.'&nbsp;&nbsp;'.$cityname.'-'.$ddcityname,
						'hd_cfcity' =>$hd_cfcity,
						'hd_ddcity' =>$hd_ddcity,					
						'hd_cityname'=>$cityname,
						'hd_ddcityname'=>$ddcityname
					);
					$data['hc'].=$hd_cfcity.$hd_ddcity;//航程
					$data['hbh'].=$hcdata['hbh'][$k].'-';//航班号	
				}
				$hb_info=json_encode($hb);
				$data['lx']=3;//类型-多程	
				$data['qfsj']=$_POST['date3'].''.$_POST['time4'];//起飞时间
				$data['cw']=$hcdata['cw'][4];//舱位				
			}

			//乘机人信息
			$i=0;
			foreach($_POST['info']['cjr_cjrxm'] as $k=>$v){
				if($_POST['info']['cjr_cjrxm'][$k]==""){				
					$this->error('乘机人姓名不能为空');
				}			
				if($_POST['info']['cjr_clkid'][$k]==""){				
					$this->error('证件号码不能为空');
				}
				if($_POST['info']['cjr_zjlx'][$k]==""){				
					$this->error('证件类型不能为空');
				}	
				if($_POST['info']['cjr_xsj'][$k]== ""){				
					$this->error('票价不能为空,请输入数字');
				}	
				$i++;
				$info[$i]=array(
					'cjr_cjrxm'	=>$_POST['info']['cjr_cjrxm'][$k],//姓名
					'cjr_lx'	=>$_POST['info']['cjr_lx'][$k],//乘客类型
					'cjr_zjlx'	=>$_POST['info']['cjr_zjlx'][$k],//证件类型
					'cjr_clkid'	=>$_POST['info']['cjr_clkid'][$k],//证件号
					'cjr_xsj'	=>$_POST['info']['cjr_xsj'][$k],//票价
					'cjr_jsf'	=>$_POST['info']['cjr_jsf'][$k],//机建
					'cjr_tax'	=>$_POST['info']['cjr_tax'][$k]//税费
				);
			}
			$obj=json_encode($info);
			
			//写入数据库			
			$data['xsj']=$_POST['xsj'];//票面价格
			$data['sf']=$_POST['sf'];//税费
			$data['taxa']=$_POST['taxa'];//保险
			$data['jj']=$_POST['jsf'];//机建费
			$data['xjj']=0;//现金券
			$data['ysje']=$_POST['ysje'];//应付金额			
			$data['nklxr']=$_POST['nklxr'];//联系人姓名
			$data['lxdh']=$_POST['lxdh'];//联系人手机
			$data['email']=$_POST['email'];//联系人邮箱					
			$data['rs']=$_POST['athud']+$_POST['chilren']+$_POST['baby'];//人数
			$data['xm']=$_POST['nklxr'];//姓名
			$data['zf_fkf']=$_POST['zf_fkf'];//支付状态
			$data['ddzt']=0;
			$data['xj']=$_POST['xsj']+$_POST['sf']+$_POST['taxa']+$_POST['jsf'];//小计			
			$data['order_logo']=1;
			$data['cpsj']=substr($_POST['cpsj'],5).''.$_POST['cpsj_p'];//出票时间
			$data['info_update_time']=time();//详情更新时间
			
			$data['hd_info']=$hb_info;//航程信息
			$data['cjr_info']=$obj;//乘机人信息	
			
			$wh['ddbh']=$_POST['ddbh'];			
			$res=$orderDB->where($wh)->data($data)->save();
			if($res){
				$this->success('编辑成功');
			}
		}	
				
		$this->display();
	}
	
	//查看订单
	function order_view(){
		$orderDB=D('AsmsOrder');	
		$where['ddbh']= I('id');
		$list=$orderDB->where($where)->find();

//		if($list['order_logo'] == 1){
			//航班信息
			$hd_info=json_decode($list['hd_info']);
			$hdinfo=$this->hb($hd_info);
			
			
			
			//乘客信息
			$cjr=json_decode($list['cjr_info']);			
			$cjr=get_object_vars($cjr);
			$cjr=$this->cjr($list['cjr_info']);	
			
			//支付状态
			$list['zf_status']=$this->zf($list['zf_fkf']);
//		}else{ //连接胜意
//			$list=$orderDB->getOrderInfo(I('id'));
//			//航班信息
//			$hdinfo=$this->hb($list['hd_info']);
//			//乘客信息
//			$cjr=$this->cjr($list['cjr_info']);			
//			//支付状态
//			$list['zf_status']=$this->zf($list['zf_fkf']);			
//		}

		//航程三字码转
		$hc= D("AsmsOrder")->format($list['hc']);
		$hc=str_split($hc,3);
		$hc= D("City")->getCity($hc);
		$list['hc_n']=implode('->',$hc);				


		$this->assign('hdinfo',$hdinfo);
		$this->assign('cjr_info',$cjr['cjr_info']);
		$this->assign('men',$cjr['men']);
		$this->assign('chl',$cjr['chl']);
		$this->assign('baby',$cjr['baby']);
	    $this->assign('list',$list);
		$this->display();
	}
	
	//取消订单
	function order_cancel(){
		$orderDB=D('AsmsOrder');
		$this->ddbh=I('id');
		if($_POST){
			$where['ddbh']=$_POST['ddbh'];
			$res=$orderDB->where($where)->setField('ddzt',8);
			if($res){
				$this->success('取消成功');
			}
		}else{
			$this->display();
		}
	}
	
	
	//发送短信
	function sendMess(){
		if($_POST){
			$id=date("YmdHis").rand(1000,2000);			
			//写入支付订单
			$order_info=array(
				'ddbh'=>$_POST['orderID'],
				'yfje'=>$_POST['money'],
				'xjj' =>0.00
			);
			$info=array(
				'id'            =>$id,
				'order_id_arr'  =>$_POST['orderID'],
				'member_id'     =>$_POST['hyid'],
				'order_info'    =>json_encode($order_info),
				'order_price'   =>$_POST['money'],
				'route'         =>$_POST['route'],
				'product_name'  =>'机票预定-在线支付',
				'payUrl'        =>'www.ipyue.com/member/id='.$id,
				'create_time'   =>time()
			);
			$res=D('PayOrder')->add($info);
			if($res){
				//发送短信
				$data=array(
					'name'     =>$_POST['name'],//客户姓名
					'route'    =>$_POST['route'],
					'money'    =>$_POST['money'],
					'mobile'   =>$_POST['tel'],
					'weburl'  =>'www.ipyue.com/member/pay?id='.$id
				);
				$re=D("Message")->send("order_pay_link",$data);	
				if($re){
					$this->success('短信发送成功');
				}else{
					$this->error('短信发送失败');
				}
			}
		}	
	}


	//支付状态
	function zf($data){
		if($data == 0){$status="[待支付]";}
		if($data == 1){$status="[已付款]";}
		if($data == 2){$status="[已取消]";}
		return $status;
	}
	
	//航程类型
	function jpType($data){		
		if($data == 1){$type="单程";}
		if($data == 2){$type="往返";}
		if($data == 3){$type="多程";}	
		return $type;
	}

	//航班信息
	function hb($data){	
		foreach($data as $k=>$v){
				if(is_array($v)){$v=(object)$v;}			
				
				//航程
				$hc=substr($v->hc,0,6);				
				$hc= D("AsmsOrder")->format($hc);
				$hc=str_split($hc,3);
				$hc= D("City")->getCity($hc);
				$hc=implode('->',$hc);			
				
				//出发、到达城市
				$cfcity=D("City")->getCity($v->hd_cfcity);
				$ddcity=D("City")->getCity($v->hd_ddcity);
				
				$hdinfo[]=array(
					'hc'  =>$hc,
					'cfsj'=>$v->cfsj,
					'hbh' =>$v->hbh,
					'cw'  =>$v->cw,
					'jx'  =>$v->hd_fjjx,
					'date'=>$v->hd_cfsj,
					'time'=>$v->hd_cfsj_p,
					'ddsj'=>$v->ddsj,
					'cfcity'=>$cfcity.'('.$v->hd_cfcity.')',
					'ddcity'=>$ddcity.'('.$v->hd_ddcity.')'	
				);	
			}		
		return $hdinfo;	
	}
	
	//乘客信息
	function cjr($data){
		foreach($data as $k=>$v){
			if(is_array($v)){$v=(object)$v;}			
			if($v->cjr_lx == 1){
				$lx = '成人';
				$men++;
				$info['men']=$men;
			}
																
			if($v->cjr_lx == 2){
				$lx  = '儿童';
				$chl++;
				$info['chl']=$chl;
			}
			
			if($v->cjr_lx == 3){
				$lx  = '婴儿';
				$baby++;
				$info['baby']=$baby;
			}	
			
			$cjr_info[]=array(
				'cjr_cjrxm' =>$v->cjr_cjrxm,									  
				'lx'        =>$lx ,
				'cjr_clkid' =>$v->cjr_clkid,
				'cjr_xsj'   =>$v->cjr_xsj,
				'cjr_jsf'   =>$v->cjr_jsf,
				'cjr_tax'   =>$v->cjr_tax
			);	
		}
		$info['cjr_info']=$cjr_info;
		return $info;
	}	

	//导入
	function daoru(){
		$this->title="订单管理-导入";
		$this->display();		
	}	
	
	function upload() {
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('xls','xlsx');// 设置附件上传类型
		$upload->hashLevel = 3;
		$upload->savePath =  './Public/Uploads/excelData/';
		$userInfo=$this->userInfo;
		//文件名命名规则
		if(substr($userInfo['username'],0,3) == 'can'){
			//客服部
			$upload->saveRule = $userInfo['username'].'_'.time();
		}elseif(substr($userInfo['username'],0,1) == 'L'){
			//商旅部
			$upload->saveRule = $userInfo['username'].'_'.time();
		}elseif(substr($userInfo['username'],0,2) == 'cw'){
			//财务
			$upload->saveRule = $userInfo['username'].'_'.time();
		}else{
			//其他
			$upload->saveRule = $userInfo['username'].'_'.time();
		}		
	
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功			
			require_once './test/11/Classes/PHPExcel.php';
			require_once './test/11/Classes/PHPExcel/IOFactory.php';			
			
			$info=$upload->getUploadFileInfo();			
			$filename=WEB_ROOT.$info[0]['savepath'].$info[0]['savename'];
			if($info[0]['extension'] == 'xls'){
				require_once './test/11/Classes/PHPExcel/Reader/Excel5.php';
				$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
			}else{
				require_once './test/11/Classes/PHPExcel/Reader/Excel2007.php';
				$objReader = PHPExcel_IOFactory::createReader('excel2007');
			}
			
			$objPHPExcel = $objReader->load($filename); //$filename可以是上传的文件，或者是指定的
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数	
			
			//循环读取excel文件,读取一条,插入一条
			for($j=2;$j<=$highestRow;$j++){
				$aa="";
				for($l=1;$l<=$this->abc('C');$l++){
					$a = $objPHPExcel->getActiveSheet()->getCell($this->abc($l).$j)->getValue();//获取A列的值	
					$aa.="$a,";	
				}
				$aa=trim($aa,',');				
				if($aa != ""){
					$info=explode(",",$aa);					
					$array=array('amount_type','type_id','bank');
					$data[]=array_combine($array,$info);	
				}	
			}		
			$res=D('CollectionBank')->addAll($data);
			if($res){
				$this->success('导入成功');
			}else{
				$this->error('导入失败');				
			}	
		}
	}
	
	function abc($a){
		$abc=array('A','B','C');
		foreach($abc as $k=>$v){		
			if(is_numeric($a)){
				if(($a-1)==$k){
					return $v;
				}
			}else{
				if($a==$v){
					return $k+1;
				}
			}
		}
	}
	
	function excelTime($date, $time = false) {  
		if(function_exists('GregorianToJD')){  
			if (is_numeric( $date )) {  
			$jd = GregorianToJD( 1, 1, 1970 );  
			$gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );  
			$date = explode( '/', $gregorian );  
			$date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )  
			."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )  
			."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )  
			. ($time ? " 00:00:00" : '');  
			return $date_str;  
			}  
		}else{  
			$date=$date>25568?$date+1:25569;  
			/*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/  
			$ofs=(70 * 365 + 17+2) * 86400;  
			$date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');  
		}  
	  return $date;  
	}  	
	
	
	//导出
	function daochu(){
		if($_POST){
			$AsmsOrder=D('AsmsOrder');
			if($_POST['xd1']!='' || $_POST['xd2']!=''){
				$xd1=strtotime($_POST['xd1']);
				$xd2=strtotime($_POST['xd2']);
				if($xd1==''){$this->error('下单开始日期不能为空');};
				if($xd2==''){$this->error('下单结束日期不能为空');};
				if($xd1>$xd2){$this->error('开始日期不能大于结束日期');};
				$wh['dprq']=array(array('gt',$xd1),array('lt',$xd2));
			}
			if($_POST['cp1']!='' || $_POST['cp2']!=''){
				$cp1=strtotime($_POST['cp1']);
				$cp2=strtotime($_POST['cp2']);
				if($cp1==''){$this->error('出票开始日期不能为空');};
				if($cp2==''){$this->error('出票结束日期不能为空');};
				if($cp1>$cp2){$this->error('开始日期不能大于结束日期');};
				$wh['cpsj']=array(array('gt',$cp1),array('lt',$cp2));
			}
			$wh['user_id']=getUid();
			//$wh['user_id']=185;
			$data=$AsmsOrder->field('user_id,hyid,lx,qfsj,cw,hc,xm,sshy,zjhm,xsj,sf,xjj,ysje,dprq,cpsj,nklxr,lxdh,email')->where($wh)->select();
			if(empty($data)){
				$this->error('没有找到相关数据，请重新选择查询条件');
			}else{
				$title=array('工号','客户手机号','机票类型','起飞时间','舱位等级','航班行程','乘客姓名','证件类型','证件号码','票面价格','参考税','现金券','应付金额','下单时间','出票时间','联系人姓名','联系人电话','联系人邮箱');
				$filename=time().getUid();
				$this->exportexcel($data,$title,$filename);	
			}
		}else{
			$this->title="订单管理-导出";
			$this->display();
		}
	}	
	
	function exportexcel($data=array(),$title=array(),$filename='report'){
		header("Content-type:application/octet-stream");
		header("Accept-Ranges:bytes");
		header("Content-type:application/vnd.ms-excel");  
		header("Content-Disposition:attachment;filename=".$filename.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		//导出xls 开始
		if (!empty($title)){
			foreach ($title as $k => $v) {
				$title[$k]=iconv("UTF-8", "GB2312",$v);
			}
			$title= implode("\t", $title);
			echo "$title\n";
		}
		if (!empty($data)){
			foreach($data as $key=>$val){
				foreach ($val as $ck => $cv) {
					$data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
				}
				$data[$key]=implode("\t", $data[$key]);				
			}
			echo implode("\n",$data);
		}
	}	
	
	
	//航线
	public function airLine($index){
		$arr=array('国内','美国','欧洲','亚洲','非洲','南美洲','加拿大');
		return $arr[$index-1];
	}	
	//内部费用
	public function interiorCostType($index){
		$arr=array('转账费','刷卡费','追位费','汇款手续费','燃气费','有线电视费','办公费用','快递费','电话费','社保','管理费','支、取、转账手续费','广告费','软件支持费','无线上网');
		return $arr[$index-1];
	}
	
	//客户费用
	public function customerCostType($index){
		$arr=array('快递费','环讯刷卡手续费','易宝刷卡手续费','客户返利','分利','追位费','pos机刷卡手续费','晚间出票服务费','晚间废票服务费','财付通手续费','汇款手续费','退客人订金','节日礼品费','收入','分利2','ACM单费用','ADM单费用','送票费用');
		return $arr[$index-1];
	}
	
	//供应商费用
	public function  supplierCostType($index){
		$arr=array('其它供应商收入费用','其它供应商支出费用','供应商改期手续费','供应商退票手续费','供应商签证费','供应商机票款','航空公司改签');
		return $arr[$index-1];
	}
		
	//其他费用
	public function  otherCostType($index){
		$arr=array('eterm租金支出','行程单收入','行程单支出','UR租金收入','航空保险收入','航空保险支出','第三方保险支出');
		return $arr[$index-1];
	}	
	
	//支付方式
	public function payType($index){
		$arr=array('在线支付','转账支付','刷卡支付','现金支付');
		return $arr[$index-1];
	}
	
	//付款银行
	public function gatheringBank($index){
		$arr=array('中信银行','农业银行','建设银行','商业银行','交通银行','中行3235','中行4588','美乐中行','招行5321','招行5188','美乐工行','工行6176','工行0693','民生银行','支付宝','财付通','现金','支票','信用卡','协议欠款','抵北京票款','抵广州票款','抵深圳票款','抵上海票款');
		return $arr[$index-1];
	}
	
	//客户类型
	public function customerType($index){
		$arr=array('新客','老客','介绍','同行','大客户');
		return $arr[$index-1];
	}
  	
	//保险类型
   public function insuranceType($index){
   		$arr=array('华泰A','中国人保');
   		return $arr[$index-1];
   }
   
   //财务状态
   public function financeStatus($index){
		$arr=array('待付款待收款','待付款已收款','已付款待收款','已付款已收款','已完成');
   		return $arr[$index-1];
   }
	
}?>