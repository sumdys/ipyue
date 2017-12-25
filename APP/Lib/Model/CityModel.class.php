<?php
class CityModel extends RelationModel{
	protected $_link = array(
		'profile'=> array(
			'mapping_type'=>BELONGS_TO,
			'city_iata'=>'city',
           // 定义更多的关联属性
      ),
	);


    /*
     * 获取城市列表
     *
     */
    public function getCityList($where,$first_row,$last_row){
        if($where){

        }else{
            $where='1=1';
        }
        $arr=$this->where($where)->field('name')->limit($first_row,$last_row)->select();
        return $arr;
    }

    function getCity($data){
        $abc=array('A','B','C','D',);
        if(is_array($data)){
            $where=$show=$sql='';
            foreach($data as $key=>$val){
                $k=$abc[$key];
                $where.="and $k.iata='$val' ";
                $show.=",{$k}c.name {$k}name";
                $sql .=",asf_airport $k left join asf_city {$k}c on {$k}c.iata={$k}.city_iata ";
            }
            $show = trim($show,',');
            $sql = trim($sql,',');
            $sql2="select $show from $sql where 1=1 $where";
//            echo $sql2;
            $md5=md5($sql2);
            if(S($md5)){
                $rs= S($md5);
            }else{
                $rs= $this->query($sql2);
                S($md5,$rs);
            }
        //    $arr=isset($rs[0])?$rs[0]:'';
           if(empty($rs)){
               foreach($data as $key=>$val){
                   $arr[$key]=$this->getCityName($val);
               }
           }else{
               $arr=array_values($rs[0]);
           }
           return $arr;
        }else{
            return  $this->getCityName($data);
        }
    }

    /**
     * 城市列表
     */
    public function getCityName($iata,$shiqu=0){
        $iata=trim(str_replace('<br>','',$iata));
    	$where["iata"]=$iata;
        $city=$this->field('name,shiqu')->cache(true)->where($where)->find();

        if(!$shiqu){
            $city=$city['name'];
        }
        $DB_PREFIX=C('DB_PREFIX');
    	if(!$city){ // 如果无结果则表示该iata可能是机场的三字码
            if(!$shiqu){
                $airport=D('Airport')->cache(true)->join("$DB_PREFIX"."city on ".$DB_PREFIX."airport.city_iata=".$DB_PREFIX."city.iata")->where("$DB_PREFIX"."airport.iata='$iata'")->getField($DB_PREFIX."city.name");
            }else{
                $field=$DB_PREFIX .'city.name,'.$DB_PREFIX .'city.shiqu';
                $airport=D('Airport')->cache(true)->field($field)->join("$DB_PREFIX"."city on ".$DB_PREFIX."airport.city_iata=".$DB_PREFIX."city.iata")->where("$DB_PREFIX"."airport.iata='$iata'")->find();
            }

    		if($airport){
                return $airport;
            }else
    			return false;
    	}else{
            return $city;
        }

    }

    public function getCityIata($iata){
        $iata=trim(str_replace(' ','',$iata));
        $rs=preg_match('/^([a-zA-Z]){3}$/i',$iata);
        if($rs){
            $where['iata']=$iata;
        }else{
            $where['name']=$iata;
        }

        $city=$this->cache(true)->where($where)->getField('iata');
        $DB_PREFIX=C('DB_PREFIX');
        if(!$city) // 如果无结果则表示该iata可能是机场的三字码
        {
            if($rs){
            $airport=D('Airport')->cache(true)->join("$DB_PREFIX"."city on ".$DB_PREFIX."airport.city_iata=".$DB_PREFIX."city.iata")->where("$DB_PREFIX"."airport.iata='$iata'")->getField($DB_PREFIX."city.iata");
            }else{
                $airport=D('Airport')->cache(true)->join("$DB_PREFIX"."city on ".$DB_PREFIX."airport.city_iata=".$DB_PREFIX."city.iata")->where("$DB_PREFIX"."airport.name='$iata'")->getField($DB_PREFIX."city.iata");
            }
            if($airport){
                return $airport;
            }else
                return false;
        }
        else
            return $city;
    }
	/**
     * 根据iata代码获取城市和机场。
     * @param string $iata
     * @return string
     */
    public function getCityAirportByIata($iata){
		$iata=trim(str_replace('<br>','',$iata));
	
		$where="iata='$iata'";
        $city=$this->cache(true)->where($where)->find();
        $airport=D('Airport')->cache(true)->relation(true)->where($where)->find();

        if(!$city) # 如果无结果则表示该iata可能是机场的三字码
        {
            if($airport)
                return $airport['city']['name'].'<br />'.$airport['name'];
            else 
                return '';
        }
        else
            return $airport ? $city['name'].'<br />'.$airport['name'] : $city['name'];
    }
	
	
		
		

		
		
}