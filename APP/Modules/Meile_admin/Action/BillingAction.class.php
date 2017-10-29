<?php
class BillingAction extends CommonAction{
	function index(){
		$userinfo=$this->userInfo;
		$did=$userinfo['department']['id'];	
		$uname=strtolower($userinfo['username']);
		$uid=$userinfo['id'];
		$username=array('can502','can402','l501','cw03','cw07','cw05','admin');

		if($uname == 'cw07'){
			$this->type=1;//收款财务权限				
		}
		if($uname == 'cw03'){
			$this->type=2;//付款财务权限			
		}
		if($uname == 'cw05' || $uname == 'admin'){
			$this->type=3;//收付款财务权限				
		}

		if($_POST){$conditon=$_POST;}
		$conditon['uid']=$uid;
		$conditon['did']=$did;
		$conditon['uname']=$uname;
		$data=$this->billingInfo($conditon);
		
		$this->assign('data',$data);
		$this->title="登帐平台";
		$this->display();
	}
	
	function billingInfo($conditon){
		if($conditon['uname'] == 'can502' || $conditon['uname'] == 'can402'){//客服部经理
			$wh['department']=9;
		}elseif($conditon['uname'] == 'l501'){//商旅部经理
			$wh['department']=10;
		}elseif($conditon['did'] == 9 || $conditon['did'] == 10){//
			$wh['user_id']=$user;
		}			
	

        if($conditon['so'] != ''){
            if(strstr(I('so'),':')){
                $so=explode(':',$conditon['so']);
                $map[$so[0]]=$so[1];
            }else{
				$where['cpd'] = array('like',"%".$conditon['so']."%");
            }
        }
        $this->map=$map;
        $this->order='id desc';
        //$this->relation = true;
        parent::index(D("Billing"));	
		$data=$this->list;

		foreach($data as $k=>$v){
			$bz=(array)json_decode($v['bz_income']);
			$income=(array)json_decode($v['income_bank']);
			$income=array_values($income);
			$bzs=array_values($bz);	
			
			foreach($income as $key=>$val){
				$data[$k]['incomebank'][]=array($val[0],$val[1],$bzs[$key]);
				$data[$k]['count']=count($data[$k]['incomebank']);	
			}			
		}		
		return $data;
	}	
	
	//查看ok
	function view(){
		$billing=D('Billing');
		$wh['id']=I('id');
		$data=$billing->where($wh)->find();		
		$bz=(array)json_decode($data['bz_income']);
		$income=(array)json_decode($data['income_bank']);
		$income=array_values($income);	
		$bzs=array_values($bz);	
		foreach($income as $key=>$val){
			$list[]=array(
				'con'=>$val[0],
				'bank'=>$val[1],
				'bz' =>$bzs[$key]
			);
		}			
		$this->assign('list',$list);
		$this->assign('data',$data);
		$this->title="登帐平台-查看明细";
		$this->display();	
	}
	
	//新增 ok
	function add(){
		if(IS_POST){
			$data=$_POST;
			$i=0;
			foreach($_POST['income'] as $k=>$v){
				$i++;
				$in_b[$i]=array($_POST['income'][$k],$_POST['bank'][$k]);
				$totelIncome +=$v;
			}
			$data['income_bank']=json_encode($in_b);
			$data['profit']=$totelIncome-$_POST['pay']-$_POST['nwf']-$_POST['skf']-$_POST['spf']-$_POST['skf']-$_POST['fkf']-$_POST['fl'];
			
			$data['cpdate']=strtotime($data['cpdate']);
			$data['create_time']=time();
			$data['update_time']=time();
			$data['user_id']=getUId();
			$data['department']=$this->userInfo['department']['id'];

			D('Billing')->create($data);
			$res=D('Billing')->add();
			if($res){
				$this->success('增加成功');
			}else{
				$this->error(D('Billing')->getDBerror());
			}
		}else{
			$this->display();
		}
	}		
	
	//编辑 ok
	function edit(){
		if($_POST){
			$data=$_POST;
			$i=0;
			foreach($_POST['income'] as $k=>$v){
				$i++;
				$in_b[$i]=array($_POST['income'][$k],$_POST['bank'][$k]);
				$totelIncome +=$v;	
			}
			$data['income_bank']=json_encode($in_b);
			$data['update_time']=time();
			$data['cpdate']=strtotime($data['cpdate']);
			
			unset($data['id']);
			unset($data['income']);
			unset($data['bank']);

			$res=D('Billing')->where('id='.$_POST['id'])->data($data)->save();
			if($res){
				$info=D('Billing')->where('id='.$_POST['id'])->find();
				$income=(array)json_decode($info['income_bank']);
				$income=array_values($income);	
				foreach($income as $k=>$v){				
					$totelIncome +=$v[0];	
				}				
				$profit=$totelIncome+$info['dpk']-$info['pay']-$info['nwf']-$info['skf']-$info['spf']-$info['tpk']-$info['fkf']-$info['fl'];
				$rs=D('Billing')->where('id='.$_POST['id'])->setField('profit',$profit);
				if($rs){
					$this->success('编辑成功^_^');
				}
			}
		}else{
			$data=D('Billing')->where('id='.I('id'))->find();
			$income=(array)json_decode($data['income_bank']);
			$income=array_values($income);	
			foreach($income as $key=>$val){
				$list[]=array(
					'con'=>$val[0],
					'bank'=>$val[1],
				);
			}		
			
			$userinfo=$this->userInfo;
			$uname=strtolower($userinfo['username']);
			if($uname == 'cw07'){
				$this->type=1;//收款财务权限				
			}
			if($uname == 'cw03'){
				$this->type=2;//付款财务权限			
			}
			if($uname == 'cw05'){
				$this->type=3;//收付款财务权限				
			}			
			$this->assign('list',$list);
			$this->assign('data',$data);
			$this->title="登帐平台-编辑";
			$this->display();
		}	
	}		

	//备注-收款
	function bz_sk(){		
		if($_POST){			
			if($_POST['bz'] == ''){
				$this->error('请输入备注内容');
			}else{
				$billingDB=D('Billing');
				$t=$_POST['t'];
				$bz=$_POST['bz'];
				$where['id']=$_POST['id'];
			
				if($t == 'income'){//收入金额
					$data['bz_income']=json_encode($bz);
				}
				if($t == 'dpk'){//抵票款
					$data['bz_dpk']=$bz;					
				}	
				if($t == 'skf'){//刷卡费
					$data['bz_skf']=$bz;					
				}	
				if($t == 'remark'){//备注
					$data['remark']=$bz;
				}
					$data['update_time']=time();
				
				$res=$billingDB->where($where)->save($data);	
				if($res){
					$this->success('备注成功');
				}				
			}		
		}else{
			$this->id=I('id');
			$this->type=I('t');
			if(I('t') == 'income'){
				$this->info=D('Billing')->where('id='.I('id'))->field('audit_sk,bz_income,income_bank')->find();
				
				$bz=(array)json_decode($this->info['bz_income']);
				$income=(array)json_decode($this->info['income_bank']);
				$income=array_values($income);	
				$bzs=array_values($bz);	
				foreach($income as $key=>$val){
					$list[]=array(
						'con'=>$val[0],
						'bz' =>$bzs[$key]
					);
				}
				$this->assign('list',$list);
			}else{
				$this->info=D('Billing')->where('id='.I('id'))->field('audit_sk,bz_dpk,bz_skf,remark')->find();
			}
			$this->title="登帐平台-新增";
			$this->display();
		}	
	}	
	
	//备注-付款 ok
	function bz_fk(){		
		if($_POST){
			if($_POST['bz'] ==""){
				$this->error('请输入备注内容');
			}else{
				$billingDB=D('Billing');
				$t=$_POST['t'];
				$bz=$_POST['bz'];
				$where['id']=$_POST['id'];

				if($t == 'pay'){//取票金额
					$res=$billingDB->where($where)->setField('bz_pay',$bz);
				}	
				
				if($t == 'tpk'){//退票款
					$res=$billingDB->where($where)->setField('bz_tpk',$bz);
				}					
				if($t == 'fkf'){//返客费
					$res=$billingDB->where($where)->setField('bz_fkf',$bz);
				}				
				if($t == 'nwf'){//拿位费
					$res=$billingDB->where($where)->setField('bz_nwf',$bz);
				}	
				if($res){
					$this->success('备注成功');
				}				
			}		
		}else{
			$this->id=I('id');
			$this->type=I('t');
			$this->info=D('Billing')->where('id='.I('id'))->field('audit_sk,audit_fk,bz_pay,bz_tpk,bz_fkf,bz_nwf')->find();
			$this->title="登帐平台-新增";
			$this->display();
		}	
	}			
	
	//导入 ok
	function daoru(){
		$this->title="订单管理-导入";
		$this->display();		
	}	
	
	public function upload() {
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
			$billingDB=D('Billing');
			$userDB=D('User');
			$i=0;
			for($j=2;$j<=$highestRow;$j++){
				$aa="";
				for($l=1;$l<=$this->abc('AF');$l++){
					$a = $objPHPExcel->getActiveSheet()->getCell($this->abc($l).$j)->getValue();//获取A列的值
	
					$aa.="$a,";	
				}
				$aa=trim($aa,',');				
				if($aa != ""){
					$info=explode(",",$aa);
					$info[0]=strtotime($this->excelTime($info[0]));
					
					$income=explode('/',$info[8]);
					$bank=explode('/',$info[9]);
					foreach($income as $k=>$v){
						$i++;
						$in_b[$i]=array($income[$k],$bank[$k]);
					}
				
					unset($info[9]);
					$info[8]=json_encode($in_b);	
					
					$array=array('cpdate','cpd','ticketNo','PNR','route','krxm','newORold','airline','income_bank','dpk','pay','ds','nwd','nwf','skf','spf','tpk','fkf','fl','slr','profit','ylce','cdr','dzd','remark','pay_time','pay_detail','tkmx','fkmx','nwfmx');
					
					$data[]=array_combine($array,$info);									
				}	
			}			
			$res=$billingDB->addAll($data);
			if($res){
				$this->success('导入成功');
			}else{
				$this->error('导入失败');				
			}	
		}
	}
	
	function abc($a){
		$abc=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF');
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
	
	//导出 ok
	function daochu(){
		if($_POST){
			$billingDB=D('Billing');
			if($_POST['cp1']!='' || $_POST['cp2']!=''){
				$cp1=strtotime($_POST['cp1']);
				$cp2=strtotime($_POST['cp2']);
				if($cp1==''){$this->error('开始日期不能为空');};
				if($cp2==''){$this->error('结束日期不能为空');};
				if($cp1>$cp2){$this->error('开始日期不能大于结束日期');};
				$wh['cpdate']=array('between',$cp1,$cp2);
			}
			$data=$billingDB->field( 'id,create_time,update_time,audit_sk,audit_fk,bz_dpk,bz_skf,bz_income,bz_qpje,bz_tpk,bz_fkf,bz_nwf,user_id,deparment',true)->select();			
			if(empty($data)){
				$this->error('没有找到相关数据，请重新选择查询条件');
			}else{
				foreach($data as $k=>$v){
					$date=date('Y/m/d',$v['cpdate']);
					array_splice($data[$k],0,1,$date);
					$incomeBank=(array)json_decode($v['income_bank']);
					$income=array_values($income);	
					
					foreach($incomeBank as $key=>$val){
						$income .=$val[0].'/';
						$bank .=$val[1].'/';											
					}
					$in_b=array($income,$bank);
					array_splice($data[$k],7,0,$in_b);	
					unset($income);
					unset($bank);
					unset($data[$k]['income_bank']);//将最后一个income_bank删除					
				}
				$title=array('出票日期','出票地','票号','PNR','行程','客人姓名','新&旧','航线','收入金额','收款银行','抵票款','取票金额','单数','拿位地','拿位费','刷卡费','送票费','退票款','返客费','分利','收利人','利润','盈利差额','承担人','到账地','备注','支付时间','支付明细','退客明细','返客明细','拿位费明细');
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
	
	//审核
	function audit(){
		if($_POST){
			if(I('t') == 'sk'){
			    $res=D('Billing')->where('id='.I('id'))->setField('audit_sk',1);
			}elseif(I('t') == 'fk'){
				$res=D('Billing')->where('id='.I('id'))->setField('audit_fk',1);
			}
			if($res){
				$this->success('审核成功^_^');
			}
		}else{
			$data=D('Billing')->where('id='.I('id'))->find();			
			$bz=(array)json_decode($data['bz_income']);
			$income=(array)json_decode($data['income_bank']);
			$income=array_values($income);	
			$bzs=array_values($bz);	
			foreach($income as $key=>$val){
				$list[]=array(
					'con'=>$val[0],
					'bank'=>$val[1],
					'bz' =>$bzs[$key]
				);
			}			
			$userinfo=$this->userInfo;
			$uname=strtolower($userinfo['username']);			
			$arr=array('cw03','cw07','cw05','admin');			
			if(in_array($uname,$arr)){
				if($uname == 'cw07'){
					$this->type=1;//收款财务权限				
				}
				if($uname == 'cw03'){
					$this->type=2;//付款财务权限			
				}
				if($uname == 'cw05' || $uname == 'admin'){
					$this->type=3;//收付款财务权限				
				}					
					
				$this->assign('list',$list);
				$this->assign('data',$data);			
				$this->t=I('t');
				$this->title="登帐平台-审核账单";
				$this->display();
			}else{
				echo '你无审核权';				
			}			
		}
	}

}?>