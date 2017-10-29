<?php
/***
**
**newORold新客户：0表示对主管是新客户，1表示对员工是新客户，2表示非新客户
**appointment预约：0表示非预约名单，1为预约名单，默认0
**audit审核：0表示不能审核，1表示待审核，2为通过审核，默认0
**no_need不需要：1为不需要，0为需要，默认0
**invalid无效:1为无效，0为有效，默认0
**
**
***/
class SalesModel extends RelationModel{
	private $uid;
	private $username;
	protected $_link = array(
		'user'=> array(  //关联客服表
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'user',
			'foreign_key'=>'id',
			'mapping_fields'=>'name',
			'as_fields'=>'name'
           // 定义更多的关联属性 relation(true)
		)	
	);
}

class SalesAction extends CommonAction{
	function index(){//新客户名单
		if($_POST){$data=$_POST;}
		$data['type']='newORold';		
		if($this->userInfo['username'] == 'tm001' || $this->userInfo['username'] == 'admin'){
			$data['num']=0;
		}else{
			$data['num']=1;
		}
		$this->coun($this->userInfo['username']);
		$this->com($data);	
	}
	
	function appointment(){//预约名单
		if($_POST){$data=$_POST;}
		$data['type']='appointment';
		$data['num']=1;
		$this->coun($this->userInfo['username']);
		$this->com($data);
	}	
	
	function audit(){//待审核名单
		if($_POST){$data=$_POST;}
		$data['type']='audit';
		$data['num']=1;
		$this->coun($this->userInfo['username']);
		$this->com($data);		
	}
	
	function audit_remark(){//审核说明 ok		
		if($_POST){
			$id=$_POST['id'];
			$data['audit_remark']=$_POST['con'];
			
			$res=D('Sales')->where('id='.$id)->save($data);
			//print_r(D("Sales")->getLastSql());
			if($res){$this->success('提交成功');}		
		}else{
			$id=I('id');
			$this->remark=D('Sales')->where('id='.$id)->field('audit_remark')->find();			
			$this->id=$id;
			$this->title="电销管理系统";
			$this->display();		
		}	
	}
	
	function audit_confirm(){//审核确认
		if($_POST){
			if($_POST['t'] == 'yes'){
				$data['audit']=2;
			}else{
				$data['audit']=0;
				$data['newORold']=1;
			}
			$id=$_POST['id'];
			$res=D('Sales')->where('id='.$id)->save($data);			
			
			if($res){
				$this->success('审核成功',U('/Meile_admin/Sales/audit'));
			}else{
				$this->error(D('Sales')->getLastSql());
			}
		}else{
			$t=I('t');
			$id=I('id');
			if($t == 'yes'){
				$this->vo='通过';
			}else{
				$this->vo='不通过';
			}			
			$this->t=$t;
			$this->id=$id;
			$this->title="电销管理系统";
			$this->display();		
		}
	}
	
	function audit_yes(){//审核通过名单 ok		
		if($_POST){$data=$_POST;}
		$data['type']='audit';
		$data['num']=2;
		$this->coun($this->userInfo['username']);
		$this->com($data);		
	}	
	
	function no_need(){//不需要名单  ok		
		if($_POST){$data=$_POST;}
		$data['type']='no_need';
		$data['num']=1;
		$this->coun($this->userInfo['username']);
		$this->com($data);			
	}	
	
	function invalid(){//无效名单  ok
		if($_POST){$data=$_POST;}
		$data['type']='invalid';
		$data['num']=1;
		$this->coun($this->userInfo['username']);
		$this->com($data);		
	}	
	
	function com($data){
		if($data['so'] != ''){
			if(strstr($data['so'],':')){
				$so=explode(':',$data['so']);
				$map[$so[0]]=$so[1];
			}else{							
				$where['xm|mobi|qq']= array('like',"%".$data['so'] ."%");
			}
		}		
		if($this->userInfo['username'] == 'tm001' ){
			//主管				
			$this->type=0;
		}elseif($this->userInfo['username'] == 'admin'){
			$this->type=2;
		}else{
			//业务员
			$where['uid']=getUId();	
			$this->type=1;
		}
			
		//名单类型
		$parameter=$data['type'];			
		$where[$parameter]=$data['num'];		
		$map['_complex'] = $where;			
	
		$this->map=$map;
		$this->order='id desc';
		$this->relation = true;
		parent::index(D("Sales"));		
		$info=$this->list;
		
		//print_r(D("Sales")->getLastSql());
		
		$this->info=$info;
		$this->title="电销管理系统";
		$this->display();
	}
	
	function coun($username){
		if($username == 'tm001' || $username == 'admin'){ //
			$totle['newORold']=D('Sales')->where('newORold =0')->count();//新客户-主管
			$totle['appointment']=D('Sales')->where('appointment =1')->count();//预约
			$totle['audit']=D('Sales')->where('audit =1')->count();//待审核
			$totle['auditYes']=D('Sales')->where('audit =2')->count();//审核通过
			$totle['no_need']=D('Sales')->where('no_need =1')->count();//不需要
			$totle['invalid']=D('Sales')->where('invalid =1')->count();//无效
			//print_r($totle);
		}else{
			//业务员
			$totle['newORold']=D('Sales')->where('newORold =0 and uid='.getUid())->count();//新客户-主管			
			$totle['appointment']=D('Sales')->where('appointment =1 and uid='.getUid())->count();//预约
			$totle['audit']=D('Sales')->where('audit =1 and uid='.getUid())->count();//待审核
			$totle['auditYes']=D('Sales')->where('audit =2 and uid='.getUid())->count();//审核通过
			$totle['no_need']=D('Sales')->where('no_need =1 and uid='.getUid())->count();//不需要
			$totle['invalid']=D('Sales')->where('invalid =1 and uid='.getUid())->count();//无效				
		}			
		$this->totle=$totle;
	}
	
	function save(){
		if($_POST){
			foreach($_POST as $key=>$val){
				foreach($val as $k=>$v){
					if(empty($v)){
						unset($_POST[$key][$k]);
					}			
				}
			}
			foreach($_POST as $key=>$val){
				foreach($val as $k=>$v){
					$info[$key]=$val[$k];
					$info['id']=$k;
				}			
			}

			if($info['bigclass'] == ''){
				$this->error('请设置拨打情况');			
			}
			
			if($info['bigclass'] == '无人接听'){
				if($info['date'] == ''){$this->error('请设置重拨日期');}
				if($info['time'] == ''){$this->error('请设置重拨时间');}
				
				$data['dial_case']=$info['bigclass'];				
				$data['appointment_time']=$info['date'].','.$info['time'];
				$data['appointment']=1;//预约名单
			}elseif($info['bigclass'] == '无效'){
				$data['dial_case']=$info['bigclass'];
				$data['invalid']=1;//无效名单
				$data['appointment']=0;
			}else{				
				if($info['smallclass'] == '再联系'){	
					if($info['date'] == ''){$this->error('请设置重拨日期');}
					if($info['time'] == ''){$this->error('请设置重拨时间');}	
					
					$data['appointment_time']=$info['date'].','.$info['time'];
					$data['appointment']=1;//预约名单
				}elseif($info['smallclass'] == '不需要'){
					$data['no_need']=1;	//不需要名单
					$data['appointment']=0;
				}else{					
					$data['audit']=1;//成功->待审核名单
					$data['appointment']=0;
				}
				$data['dial_case']=$info['bigclass'].'-'.$info['smallclass'];
			}	
			
			$data['newORold']=2;
			$data['update_time']=time();
			
			$id=$info['id'];
			$res=D('Sales')->where('id='.$id)->save($data);
			if($res){
				$this->success('提交成功');
			}	
		}
	}	
	
	function fenfa(){//分发新客户  ok
		$where['newORold']=0;		
		$where[_string] = 'uid is NULL'; //字段为空需要特殊处理
		$id=D('Sales')->where($where)->field('id')->select();		
				
		foreach($id as $k=>$v){
			$arrid[$k]=$v['id'];
		}
		$totle=count($arrid);
		$this->totle=$totle;		
		$this->yewu=D('User')->where('department_id=18 and id!=341')->field('id,name')->select();
		
		if($_POST){		
			if(count($arrid) == 0){
				$this->error('没有新客户可分发');
			}else{	
				$uid=array_values($_POST);	
				$amount=count($uid);//分发的人数
				$count=floor($this->totle/$amount);//每个人能的到多少个  舍去法取整		
	
				$new=array_chunk($arrid,$count);
				if(!empty($new[$amount])){
					for($i=0;$i<count($new[$amount]);$i++){
						$new[$i][]=$new[$amount][$i];				
					}			
				}
				unset($new[$amount]);
				
				foreach($new as $key=>$val){
					$where['id']=array('in',$val);
					$data['newORold']=1;
					$data['uid']=$uid[$key];
					$res=D('Sales')->where($where)->save($data);
				}
				if($res){
					$this->success('成功分发');
				}else{
					$this->error('分发失败');
				}
			}
		}			
		$this->title="电销管理系统";
		$this->display();
	}	
	
	function edit(){//编辑客户信息  ok
		if($_POST){
			$id=$_POST['id'];
			$data=$_POST;
			unset($data['id']);
			$res=D('Sales')->where('id='.$id)->data($data)->save();
			if($res){
				$this->success('编辑成功');
			}				
		}else{
			$id=I('id');
			$this->info=D('Sales')->where('id='.$id)->field('id,bh,xm,mobi,tel,mail,sex,qq,wechat,address,remark')->find();			
			$this->title="电销管理系统";
			$this->display();
		}
	}	
	
	function daoru(){//导入ok
		$this->title="电销管理系统-导入";
		$this->display();		
	}	
	public function upload() {
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('xls','xlsx');// 设置附件上传类型
		$upload->hashLevel = 3;
		$upload->savePath =  './Public/Uploads/excelData/';
		
		$upload->saveRule =time().rand(1000,9999);	//文件名命名规则
	
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
				for($l=1;$l<=$this->abc('J');$l++){
					$a = $objPHPExcel->getActiveSheet()->getCell($this->abc($l).$j)->getValue();//获取A列的值	
					$aa.="$a,";	
				}
				$aa=trim($aa,',');
				if($aa != ""){
					$info=explode(",",$aa);				
					$array=array('bh','xm','mobi','tel','mail','sex','qq','wechat','address','remark');
					$data[]=array_combine($array,$info);					
					$data[$j-2]['create_time']=time();				
				}	
			}

			
			$res=D('Sales')->addAll($data);
			if($res){
				$this->success('导入成功');
			}else{
				$this->error('导入失败');
				
			}	
		}
	}
	
	function abc($a){
		$abc=array('A','B','C','D','E','F','G','H','I','J');
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
	
	function daochu(){//导出   ok
		if($_POST){			
			if($_POST['begin']!='' ){
				$begin=strtotime($_POST['begin']);
				$end=strtotime($_POST['end']);
				$where['create_time']=array(array('gt',$begin),array('lt',$end));
			}
			$where['audit']=1;
			$data=D('Sales')->where($where)->field('bh,xm,mobi,tel,mail,sex,qq,wechat,address,remark')->select();

			if(empty($data)){
				$this->error('没有找到相关数据，请重新选择查询条件');
			}else{
				$title=array('编号','姓名','手机','电话号码','邮箱','性别','QQ号','微信','地址','备注');
				$filename=time().getUid();
				$this->exportexcel($data,$title,$filename);	
			}
		}else{
			$this->title="电销管理系统-导出";
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
}?>