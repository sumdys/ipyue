<?php
class OrderModel extends RelationModel{
	protected $_link = array(
        'user'=> array(//�����û���
            'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'foreign_key'=>'userID',
			'mapping_fields'=>'id,username,name,department_id',
        ),
		'ticket'=>array(//��ƱԱ
			'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'foreign_key'=>'drawer',
			'mapping_fields'=>'id,name',
		),
		'paymentFinance'=>array(//�������
			'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'foreign_key'=>'paymentFinanceID',
			'mapping_fields'=>'id,name',		
		),
		'gatheringFinance'=>array(//�տ����
			'mapping_type'=>BELONGS_TO,
            'class_name'=>'user',
            'foreign_key'=>'gatheringFinanceID',
			'mapping_fields'=>'id,name',		
		),	
		'gongyingshang'=>array(//��Ӧ��
			'mapping_type'=>BELONGS_TO,
            'class_name'=>'supplier2',
            'foreign_key'=>'supplier',
			'mapping_fields'=>'id,supplier',		
		),			
		
		
		
    );
	
	
	//����״̬
	public function financeStatus($index){
		$arr=array('���տ������','���տ������','���տ��Ѹ���','�����');
		return  $arr[$index-1];
	}
	
	//�ڲ�����
	public function interiorCostType($index){
		$arr=array('ת�˷�','ˢ����','׷λ��','���������','ȼ����','���ߵ��ӷ�','�칫����','��ݷ�','�绰��','�籣','�����','֧��ȡ��ת��������','����','���֧�ַ�','��������');
		return  $arr[$index-1];
	}
	
	//�ͻ�����
	public function customerCostType($index){
		$arr=array('��ݷ�','��Ѷˢ��������','�ױ�ˢ��������','�ͻ�����','����','׷λ��','pos��ˢ��������','����Ʊ�����','����Ʊ�����','�Ƹ�ͨ������','���������','�˿��˶���','������Ʒ��','����','����2','ACM������','ADM������','��Ʊ����');
		return  $arr[$index-1];
	}
	
	//��Ӧ�̷���
	public function  supplierCostType($index){
		$arr=array('������Ӧ���������','������Ӧ��֧������','��Ӧ�̸���������','��Ӧ����Ʊ������','��Ӧ��ǩ֤��','��Ӧ�̻�Ʊ��','���չ�˾��ǩ');
		return  $arr[$index-1];
	}
		
	//��������
	public function  otherCostType($index){
		$arr=array('eterm���֧��','�г̵�����','�г̵�֧��','UR�������','���ձ�������','���ձ���֧��','����������֧��');
		return  $arr[$index-1];
	}
	
	//��������
	public function paymentBank($index){
		$arr=array('��������','ũҵ����','��������','��ҵ����','��ͨ����','����3235','����4588','��������','����5321','����5188','���ֹ���','����6176','����0693','��������','֧����','�Ƹ�ͨ','�ֽ�','֧Ʊ','���ÿ�','Э��Ƿ��','�ֱ���Ʊ��','�ֹ���Ʊ��','������Ʊ��','���Ϻ�Ʊ��');	
		return  $arr[$index-1];
	
	}
	
	//����
	public function airLine($index){
		$arr=array('����','����','ŷ��','����','����','������','���ô�');
		return  $arr[$index-1];
	}
			
		
}