<?php
/**
 * 自由飞越 订单查询
 * Author:me@yin.cc
 * Date: 14-4-30
 */
include("Trafree.class.php") ;
class flight extends Trafree{

    private  $cacheTime=3600; //缓存时间

    /*
     * 递归去除 xml @attributes
     */
    function unAttributes($data){
        $arr=array();
        $data=is_object($data)?(array)$data:$data;
        foreach($data as $key=>$val){
            if($key=='@attributes'){
                if(is_array($val) || is_object($val)){
                    $arr=$this->unAttributes($val);
                }else
                    $arr=$val;
            }else{
                if(is_array($val) || is_object($val)){
                    $arr[$key]=$this->unAttributes($val);
                }else
                    $arr[$key]=$val;
            }
        }
        return $arr;
    }


    /*
     * 机票查询
     */
    function searchTicket($queryData=array()){
        if(empty($queryData)){
            $this->setError('参数错误');
            return false;
        }
        global $common;

        $AirlineDb=D('Airline');
        $CityDb=D("City");
        $AirportDb=D("Airport");

        /*
         * 缓存数据
         */
        $Name=md5(implode(',',$queryData));
        if(S($Name)){
            $rs=S($Name);
        }else{
            $rs= $this->format("xml")->_searchTicket($queryData);
            if(!empty($rs)) S($Name,$rs,$this->cacheTime);
        }

        //转为数组
      //   $rs=$this->xml2Object($rs);
        $rs=$this->xml2array($rs,array('AirCommissions'));
    //    $data= $this->unAttributes($rs);
       return ;
        print_r($data);
        exit;
         $data= $this->unAttributes($rs['PricedItineraries']);
    //    print_r($data);
        $data= $data['PricedItinerary'];
        //格式化 重新组合、转义
        foreach($data as $key=>$val){
           //$data['PricedItinerary'][$key]['DirectionInd']=$data['PricedItinerary'][$key]['AirItinerary']['DirectionInd'];
            $data[$key]['OriginDestinationOption']=$data[$key]['AirItinerary']['OriginDestinationOption'];
            unset($data[$key]['AirItinerary']);
            //移除政策信息
            unset($data[$key]['AirItineraryPricingInfo']['AirCommissions']);
            $Option = &$data[$key]['OriginDestinationOption'];
            if(!isset($Option[0])){
                $data[$key]['OriginDestinationOption']=array($Option);
            }
            foreach($Option as $ka=>$va){
                if(is_numeric($ka)){
                   if($queryData['back']==true && $va['RefNumber']==1){
                        unset($Option[$ka]);
                        continue;
                    }elseif($queryData['back']!=true && $va['RefNumber']==2){
                        unset($Option[$ka]);
                        continue;
                    }
                  //  print_r($Option);exit;
                    $Option[$ka]['PricedItineraryId']=$key;
                    $Option[$ka]['PosId']=$key."-".$va['RefNumber'].'-'.$va['OptionId'];

                    foreach($va as $kb=>$vb){
                        if($kb==='FilingAirline'){ //航空公司
                            $AirlineCode=$vb;
                            if(!isset($$AirlineCode)) $$AirlineCode=$AirlineDb->getAirlineName($vb);
                            $Option[$ka]['AirlineName']=$$AirlineCode;
                        }elseif($kb==='FlightSegment'){     //航程
                            if(!isset($vb[0])){
                                unset($Option[$ka][$kb]);
                                $vb=$Option[$ka][$kb]=array($vb);
                            }
                            $Option[$ka]['Flat'] =count($vb);
                            $Flat=$Option[$ka]['Flat']>1?$Option[$ka]['Flat']-1:0;
                            $DepartureDateTime=strtotime($Option[$ka][$kb][0]['DepartureDateTime']);
                            $ArrivalDateTime= strtotime($Option[$ka][$kb][$Flat]['ArrivalDateTime']);
                            $Option[$ka]['FlightTime']=$ArrivalDateTime-$DepartureDateTime;
                            foreach($vb as $ko=>$vo){
                                $Option[$ka][$kb][$ko]['ArrivalDate']=strtotime($vo['ArrivalDateTime']);
                                $Option[$ka][$kb][$ko]['DepartureDate']= strtotime($vo['DepartureDateTime']);
                                if(is_numeric($ko)){
                                    foreach($vo as $ks=>$vs){
                                        if($ks==='FlightNumber'){
                                            $Option[$ka]['FlightNos']=isset($Option[$ka]['FlightNos'])?$Option[$ka]['FlightNos'].'-'.$vo['OperatorCode'].$vs:$vo['OperatorCode'].$vs;
                                        }elseif($ks==='OperatorCode'){
                                            $Option[$ka]['AirCodes']=isset($Option[$ka]['AirCodes'])?$Option[$ka]['AirCodes'].','.$vs:$vs;
                                            $AirlineCode=$vs;
                                            if(!isset($$AirlineCode)) $$AirlineCode=$AirlineDb->getAirlineName($vs);
                                            $Option[$ka][$kb][$ko]['AirCompanyName']=$$AirlineCode;
                                        }elseif($ks==='Equipment'){
                                            $Option[$ka][$kb][$ko]['Equipment']=$vs['AirEquipType'];
                                        }elseif(isset($vs['LocationCode'])){ //城市代码
                                            $city ="City".$vs['LocationCode'];
                                            $Airport="Airport".$vs['LocationCode'];
                                            !isset($common[$city]) && $common[$city]=$CityDb->getCityName($vs['LocationCode']);
                                            !isset($common[$Airport])  && $common[$Airport]=$AirportDb->getAirportCity($vs['LocationCode']);
                                            $Option[$ka]['AirPorts']=isset($Option[$ka]['AirPorts'])?$Option[$ka]['AirPorts'].'-'.$vs['LocationCode']:$vs['LocationCode'];
                                            if($ks=='ArrivalAirport'){
                                                $Option[$ka][$kb][$ko]['ArrivalCityCode']=$vs['LocationCode'];
                                                $Option[$ka][$kb][$ko]['ArrivalCityName']=$common[$city];
                                                $Option[$ka][$kb][$ko]['ArrivalAirportName']=$common[$Airport];
                                            }elseif($ks=='DepartureAirport'){
                                                $Option[$ka][$kb][$ko]['DepartureCityCode']=$vs['LocationCode'];
                                                $Option[$ka][$kb][$ko]['DepartureCityName']=$common[$city];
                                                $Option[$ka][$kb][$ko]['DepartureAirportName']=$common[$Airport];
                                            }
                                        }elseif($ks=='DptTerminal'){//出发航站楼
                                            $Option[$ka][$kb][$ko]['DptTerminal']=$vs['Code'];
                                        }elseif($ks=='ArvTerminal'){ //到达航站楼
                                            $Option[$ka][$kb][$ko]['ArvTerminal']=$vs['Code'];
                                        }elseif($ks=='MarketingAirline'){
                                            $Option[$ka][$kb][$ko]['MarketingAirline']=$vs['Code'];
                                        }

                                        if($ko>0){
                                            $prev=$ko-1;
                                            $FlyTime=$Option[$ka][$kb][$ko]['DepartureDate']-$Option[$ka][$kb][$prev]['ArrivalDate'];
                                            $Option[$ka][$kb][$ko]['FlyTime']=sec2time($FlyTime);
                                        }
                                        if(is_array($Option[$ka][$kb][$ko][$ks])) unset($Option[$ka][$kb][$ko][$ks]);
                                    }
                                }
                            }
                        }

                    }
                }
            }
        }

        //过滤重复去程
        $datas=array();
        foreach($data['PricedItinerary'] as $key=>$val){
            foreach($val['OriginDestinationOption'] as $k=>$v){
                if(1){
                    if(!isset($$v['FlightNos'])){
                        $v['PricedItineraryId']=$val['PricedItineraryId'];
                        $v['FareInfo']=$val['AirItineraryPricingInfo'];
                        $datas[]=$v;
                    }
                    $$v['FlightNos']=$v['FlightNos'];
                }
            }
        }
     return $data;
    }
}