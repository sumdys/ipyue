<?php
class OrderModel extends RelationModel{
	protected $_link = array(
        'user'=> array(//关联用户表
            'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'foreign_key'=>'userID',
			'mapping_fields'=>'id,username,name,department_id',
        ),
		'ticket'=>array(//出票员
			'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'foreign_key'=>'drawer',
			'mapping_fields'=>'id,name',
		),
		'paymentFinance'=>array(//付款财务
			'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'foreign_key'=>'paymentFinanceID',
			'mapping_fields'=>'id,name',		
		),
		'gatheringFinance'=>array(//收款财务
			'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'foreign_key'=>'gatheringFinanceID',
			'mapping_fields'=>'id,name',		
		),	
		'gongyingshang'=>array(//供应商
			'mapping_type'=>BELONGS_TO,
            'class_name'=>'supplier2',
            'foreign_key'=>'supplier',
			'mapping_fields'=>'id,supplier',		
		),			
		
		
		
    );
	
	
	//财务状态
	public function financeStatus($index){
		$arr=array('待收款待付款','已收款待付款','待收款已付款','已完成');
		return  $arr[$index-1];
	}
	
	//内部费用
	public function interiorCostType($index){
		$arr=array('转账费','刷卡费','追位费','汇款手续费','燃气费','有线电视费','办公费用','快递费','电话费','社保','管理费','支、取、转账手续费','广告费','软件支持费','无线上网');
		return  $arr[$index-1];
	}
	
	//客户费用
	public function customerCostType($index){
		$arr=array('快递费','环讯刷卡手续费','易宝刷卡手续费','客户返利','分利','追位费','pos机刷卡手续费','晚间出票服务费','晚间废票服务费','财付通手续费','汇款手续费','退客人订金','节日礼品费','收入','分利2','ACM单费用','ADM单费用','送票费用');
		return  $arr[$index-1];
	}
	
	//供应商费用
	public function  supplierCostType($index){
		$arr=array('其它供应商收入费用','其它供应商支出费用','供应商改期手续费','供应商退票手续费','供应商签证费','供应商机票款','航空公司改签');
		return  $arr[$index-1];
	}
		
	//其他费用
	public function  otherCostType($index){
		$arr=array('eterm租金支出','行程单收入','行程单支出','UR租金收入','航空保险收入','航空保险支出','第三方保险支出');
		return  $arr[$index-1];
	}
	
	//付款银行
	public function paymentBank($index){
		$arr=array('中信银行','农业银行','建设银行','商业银行','交通银行','中行3235','中行4588','美乐中行','招行5321','招行5188','美乐工行','工行6176','工行0693','民生银行','支付宝','财付通','现金','支票','信用卡','协议欠款','抵北京票款','抵广州票款','抵深圳票款','抵上海票款');	
		return  $arr[$index-1];
	
	}
	
	//航线
	public function airLine($index){
		$arr=array('国内','美国','欧洲','亚洲','非洲','南美洲','加拿大');
		return  $arr[$index-1];
	}
			
		
}