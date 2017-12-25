<?php 

/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-8-12
 * Time: 下午6:05
 * To change this template use File | Settings | File Templates.
 */
class ManagerAction extends CommonAction{
       
	//总经理（管理}界面 成功客户订单
	function t_customer(){
		//$map=D("UserAdmin")->userLevelWhere('id');
		//$map=D("UserAdmin")->userLevelWhere();
		if(I('so')){
			if(strstr(I('so'),':')){
				$so=explode(':',I('so'));
				$map[$so[0]]=$so[1];
			}else{
				$where['qq'] = array('like',"%".I('so')."%");
				$where['tel'] = array('like',"%".I('so')."%");
				$where['t_name'] = array('like',"%".I('so')."%"); 
				$where['status']  = array('like',"%".I('so')."%");
				$where['_logic'] = 'or';
				$map['_complex'] = $where;
			}
		}
			
		if(I('so_date1')&& I('so_date2')){
			$map['create_time']=array(array('egt',strtotime(I('so_date1'))),array('elt',strtotime(I('so_date2'))));
		}
	
		$this->map=$map;
		$this->order='id desc';
		$this->relation = true;
		parent::index(D("Telname"));
		$this->display();
	}
	
	//导出
	function t_daochu(){
		if($_POST){
			$Tel=D('Telname');
			if($_POST['xd1']!='' || $_POST['xd2']!=''){
				//$xd1=strtotime($_POST['xd1']);
				//$xd2=strtotime($_POST['xd2']);
				$xd1=$_POST['xd1'];
				$xd2=$_POST['xd2'];
				if($xd1==''){$this->error('导出开始日期不能为空');};
				if($xd2==''){$this->error('导出结束日期不能为空');};
				if($xd1>$xd2){$this->error('开始日期不能大于结束日期');};
				$wh['create_time']=array(array('egt',$xd1),array('elt',$xd2));
			}
			//$w['user_id']=getUid();
			//echo $w['user_id'];
	
			//$data=$Tel ->relation(true)-> where($wh) -> select();
			//$data=$Tel ->field('b_id,name,tel,phone,email,sex,qq,weixin,address,content') -> where($wh) -> select();
			
		 $data=$Tel ->relation(true)->field('b_id,t_name,sex,tel,phone,email,qq,weixin,address,content,create_time,user_id') -> where($wh) -> select();
	  
		 for($i=0;$i<=count($data);$i++){
		 	unset($data[$i][user_id]);
		 };
		 
		 //print_r($data);
		 if(empty($data)){
				$this->error('没有找到相关数据，请重新选择查询条件');
			}else{
				//$title=array('名单编号','客户姓名','性别','手机','电话','邮箱','QQ号','微信','地址','备注','录入时间','电销业务员');
				//$filename=time().getUid();
				//$this->exportexcel($data,$title,$filename);
				$this->export($data);
			}
		}else{
			$this->title="录入名单管理-导出";
			$this->display();
		}
	}
	
	     function export($data){
	     	
	     	vendor('PHPExcel.PHPExcel');
	     	$fileName = '客户信息名单-'.date('Y-m-d',time());
	     	$PHPExcel = new PHPExcel();
	     	$PHPExcel->createSheet();
	     	$subObject = $PHPExcel->getSheet(1);
	     	$subObject->setTitle('data');
	     	//填入表头
	     	$PHPExcel->getActiveSheet()->setCellValue('A1', '名单编号');
	     	$PHPExcel->getActiveSheet()->setCellValue('B1', '客户姓名');
	     	$PHPExcel->getActiveSheet()->setCellValue('C1', '性别');
	     	$PHPExcel->getActiveSheet()->setCellValue('D1', '手机');
	     	$PHPExcel->getActiveSheet()->setCellValue('E1', '电话');
	     	$PHPExcel->getActiveSheet()->setCellValue('F1', '邮箱');
	     	$PHPExcel->getActiveSheet()->setCellValue('G1', 'QQ号');
	     	$PHPExcel->getActiveSheet()->setCellValue('H1', '微信');
	     	$PHPExcel->getActiveSheet()->setCellValue('I1', '地址');
	     	$PHPExcel->getActiveSheet()->setCellValue('J1', '备注');
	     	$PHPExcel->getActiveSheet()->setCellValue('K1', '录入时间');
	     	$PHPExcel->getActiveSheet()->setCellValue('L1', '电销业务员');
	     	$PHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
	     	//填入列表
	     	$k = 1;
	     	foreach ($data as $key => $v) {
	     		$k++;
	     		$PHPExcel->getActiveSheet()->setCellValue('A'.($k), $v['b_id']);
	     		$PHPExcel->getActiveSheet()->setCellValue('B'.($k), $v['t_name']);
	     		$PHPExcel->getActiveSheet()->setCellValue('C'.($k), $v['sex']);
	     		$PHPExcel->getActiveSheet()->setCellValue('D'.($k), $v['tel']);
	     		$PHPExcel->getActiveSheet()->setCellValue('E'.($k), $v['phone']);
	     		$PHPExcel->getActiveSheet()->setCellValue('F'.($k), $v['email']);
	     		$PHPExcel->getActiveSheet()->setCellValue('G'.($k), $v['qq']);
	     		$PHPExcel->getActiveSheet()->setCellValue('H'.($k), $v['weixin']);
	     		$PHPExcel->getActiveSheet()->setCellValue('I'.($k), $v['address']);
	     		$PHPExcel->getActiveSheet()->setCellValue('J'.($k), $v['content']);
	     		$PHPExcel->getActiveSheet()->setCellValue('K'.($k), $v['create_time']);
	     		$PHPExcel->getActiveSheet()->setCellValue('L'.($k), $v['name']);
	     		$PHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight(20);
	     	}
	     	
	     	
	     	//设置单元格宽度
	        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(21);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	     	$PHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
	     	
	     	 
	     	//保存为2003格式
	     	$objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
	     	header("Pragma: public");
	     	header("Expires: 0");
	     	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	     	header("Content-Type:application/force-download");
	     	header("Content-Type:application/vnd.ms-execl");
	     	header("Content-Type:application/octet-stream");
	     	header("Content-Type:application/download");
	     	
	     	//多浏览器下兼容中文标题
	     	$encoded_filename = urlencode($fileName);
	     	$ua = $_SERVER["HTTP_USER_AGENT"];
	     	if (preg_match("/MSIE/", $ua)) {
	     		header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
	     	} else if (preg_match("/Firefox/", $ua)) {
	     		header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
	     	} else {
	     		header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
	     	}
	     	
	     	header("Content-Transfer-Encoding:binary");
	     	$objWriter->save('php://output');
	     	
	     }
	/*  function exportexcel($data=array(),$title=array(),$filename='report'){
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
			}  */
}

?>