<?php
/* * Created by JetBrains PhpStorm.
 * User: pengfei
 * To change this template use File | Settings | File Templates.
 */
class TripOrderModel extends RelationModel {

    public function addOrder() {
        $data = array();
        $data['member_id'] = I('post.member_id','0');
        $data['freetour_id'] = I('post.freetour_id');
        if (intval($data['freetour_id'])<=0) {
            return array('status' => 0, 'info' => "获取线路信息失败，请重新选择");
        }
        $data['num'] = I('post.num',0);
        $data['total_price'] = $data['num'] * I('post.price');
        $data['start_date'] = strtotime(I('post.start_date'));
        $data['linkman'] = trim(I('post.linkman'));
        $data['mobile'] = trim(I('post.mobile'));
        $data['email'] = trim(I('post.email'));
        $data['append_price'] = I('post.append_price',0);
        $data['trip'] = (string)implode(',',I('post.trip',''));
        $data['create_time'] = time();
        $data['member_id'] = getUid();
        $data['order_num'] = rand(100,999).date('YmdHis').rand(10000,99999); //订单号
        $res = $this->add($data);
        if($res){
             return array('status' => 1, 'info' => "创建订单成功",'id'=>$res);
        } else {
             return array('status' => 0, 'info' => "创建订单失败，请重试");
        }

    }

    public function getInfo($id=0) {
       $where['o.id'] = $id;
       
       $field[] = 'o.id';
       $field[] = 'fr.dcity';
       $field[] = 'fr.title';
       $field[] = 'o.num';
       $field[] = 'o.total_price';
       $field[] = 'o.start_date';
       $field[] = 'o.order_num';

       $res = M('TripOrder o')->join('LEFT JOIN asf_freetour fr ON fr.id=o.freetour_id')
               ->where($where)
               ->field($field)
               ->find();
        $CityModel = D("City");
        if ($res) {
            $res['dcity'] = $res['dcity']?$CityModel->getCityName($res['dcity']):'';
            $res['start_date'] = date('Y年m月d日',$res['start_date']);
            return array('status' => 1, 'info' => "获取成功",'data'=>$res);
        } else {
            return array('status' => 0, 'info' => "获取失败");
        }
    }
     /*
      * @param $order_num 订单号
      * @param $pay_data 支付返回数据 json格式
      * @param $pay_state 支付状态
      */
      public function upPayState($order_num='',$pay_data='',$pay_state=0) {

        $where['order_num'] = $order_num;
        $data['pay_data'] = $pay_data;
        $data['pay_state'] = $pay_state;
        $data['update_time'] = time();
        $res = $this->where($where)->save($data);
        if ($res!==false) {
            return array('status' => 1, 'info' => "更新成功",'data'=>$res);
        } else {
            return array('status' => 0, 'info' => "更新失败");
        }
    }


	/*
	 * 获取订单信息
	 * 
	 */
	public function getOrderInfo($id=0){
		$where['o.id'] = $id;
       
       $field[] = 'o.id';
       $field[] = 'fr.dcity';
       $field[] = 'fr.acity';
       $field[] = 'fr.title';
        $field[] = 'fr.days';
        $field[] = 'fr.images';
       $field[] = 'o.num';
       $field[] = 'o.total_price';
       $field[] = 'o.order_num';
       $field[] = 'o.start_date';
       $field[] = 'o.linkman';
       $field[] = 'o.mobile';
       $field[] = 'o.create_time';
        $field[] = 'o.pay_state';
        $field[] = 'o.pay_price';
       $res = M('TripOrder o')->join('LEFT JOIN asf_freetour fr ON fr.id=o.freetour_id')
               ->where($where)
               ->field($field)
               ->find();
        $CityModel = D("City");
        if ($res) {
            $res['dcity'] = $res['dcity']?$CityModel->getCityName($res['dcity']):'';
            $res['acity'] = $res['acity']?$CityModel->getCityName($res['acity']):'';
            $res['start_date'] = date('Y-m-d',$res['start_date']);
            $res['create_time'] = date('Y-m-d H:i',$res['create_time']);
            $res['images']  = json_decode($res['images'],true);
            return array('status' => 1, 'info' => "获取成功",'data'=>$res);
        } else {
            return array('status' => 0, 'info' => "获取失败");
        }
	}



    /*
     * 修改订单信息
     */
    public function updatePay($out_trade_no, $total_fee){
        $TripOrder= M('TripOrder');
        $where['order_num'] = $out_trade_no;
        $result =$TripOrder->where($where)->field('id')->find();
        if(!$result){
            return -1;
        }
        //修改订单状态
        $data['pay_state']=1;
        if(!$TripOrder->where('id='.$result['id'])->save($data)){
            return -2;
        }

        return 1;

    }
    
    /*
     * 异步获取推广订单
     * @param 
     */
    
    function ajaxExtendOrderList($limit,$where,$order='',$fields='*',$page){
        $where["status"]=1;
        $order = $order?$order:"create_time desc";
        $firstRow =  $page*$limit;
        $list=$this->field($fields)->where($where)->order($order)->select();
        foreach($list as $k=>$v){
//			$v['hc_a'] = D("City")->getCity( $v['hc']);
//			$list[$k]['hc_n']=implode('-',$v['hc_a']);//航程
//			$list[$k]['jp_type']=$this->jptype($v['lx']);//机票类型
			$list[$k]['create_time']=date('Y-m-d H:i',$v['create_time']);
			$list[$k]['start_date']=date('Y-m-d',$v['start_date']);
			$list[$k]['price']=$v['total_price']/$v['num'];
			switch($v['pay_state']){
				case 0:
					$str='未支付';
					break;
				case 1:
					$str='已支付';
					break;
				case 2:
					$str='已取消';
					break;
			}
			$list[$k]['state']=$str;
			switch($v['line_type']){
				case 0:
					$str1='短线';
					break;
				case 1:
				case 2:
					$str1='自由行';
					break;
				case 3:
				case 4:
				$str1='别墅客栈';
					break;
				case 5:
				case 6:
				$str1='邮轮';
					break;
			}
			$list[$k]['type']=$str1;
		}
//		var_dump($list);exit;
		//待支付笔数
		$pending['count']=count($list);
		foreach($list as $val){
			$pending['price']+=$val['total_price'];
		}
		if($pending['price'] <0 || $pending['price']==''){
			$pending['price']=0;
		}
        $data['list']=$list;
        return $data;
    }


}
