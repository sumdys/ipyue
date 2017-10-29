<?php
/**
 * 自由飞越 订单查询
 * 引用类： Trafree.class.php
 * Author:me@yin.cc
 * Date: 14-4-30
 */
include("Trafree.class.php") ;
class flight extends  Trafree{

    private  $cacheTime=3600; //缓存时间
    protected  $db;
    protected $guid;

    function getGid(){
        $id=I('get.gid')?I('get.gid'):md5(session_id());
        return $id;
    }
    /*
     * 机票查询
     */
    function searchTicket($queryData,$back=false){
        if(empty($queryData)){
            $this->setError('参数错误');
            return false;
        }
        global $common;
        $this->db->AirlineDb=D('Airline');
        $this->db->CityDb=D("City");
        $this->db->AirportDb=D("Airport");

    //    print_r($this->format("array")->_searchTicket($queryData));exit;
         // 缓存数据
        $Name=md5(implode(',',$queryData));
        if(S($Name)){
            $rs=S($Name);
        }else{
            $rs= $this->_searchTicket($queryData);
            $obj=$this->xml2Object($rs);
            if($queryData['Mode']=="A"){
                if($obj->PricedItineraries->FinFlag=="T"){
                    S($Name,$rs,$this->cacheTime);
                }
            }else{
                $Success=(array)$obj->Success;
                if(!empty($Success)) S($Name,$rs,$this->cacheTime);
            }
        }
        //转为数组
        $rs=$this->xml2array($rs);
        $data= $this->unAttributes($rs['PricedItineraries']);
        //格式化 重新组合、转义
        foreach($data['PricedItinerary'] as $key=>$val){
            $data['PricedItinerary'][$key]['OriginDestinationOption']=$data['PricedItinerary'][$key]['AirItinerary']['OriginDestinationOption'];
            unset($data['PricedItinerary'][$key]['AirItinerary']);
            unset($data['PricedItinerary'][$key]['AirItineraryPricingInfo']['AirCommissions']);
            $Option = &$data['PricedItinerary'][$key]['OriginDestinationOption'];
            if(!isset($Option[0])){
                $data['PricedItinerary'][$key]['OriginDestinationOption']=array($Option);
            }
            $this->fr($Option,$key);
        }
     //   print_r($data);exit;
        //过滤重复  去返分离
        $datas['FinFlag']=isset($data['FinFlag'])?$data['FinFlag']:"T";
        foreach($data['PricedItinerary'] as $val){
            if($back){
                $QPosId=null;
                foreach($val['OriginDestinationOption'] as $v){
                    if($v['RefNumber']==1 && $back==$v['FlightNos']){
                        $QPosId['OptionId']=$v['OptionId'];
                        !isset($datas['OriginRoute']) && $datas['OriginRoute']=$v;//去的行程
                    //    $datas['OriginRoute']=$v;//去的行程
                        break;
                    }
                }
                if(!$QPosId)  continue;
            }
            foreach($val['OriginDestinationOption'] as $k=>$v){
                if($back){
                    if($v['RefNumber']==1){
                         unset($v);continue;
                    }
                    $v['PosId']=$v['PricedItineraryId'].'-'.$QPosId['OptionId'].'-'.$v['OptionId'];
                }elseif($v['RefNumber']==2){
                    unset($v); continue;
                }else{
                    $v['PosId']=$v['PricedItineraryId'].'-'.$v['OptionId'];
                }

                if(!isset($$v['FlightNos'])){
                    $v['PricedItineraryId']=$val['PricedItineraryId'];
                    $v['FareInfo']=$val['AirItineraryPricingInfo'];
                    $Remark=$v['FareInfo']['Remarks']['Remark'];
                    $v['FareInfo']['Remarks']['string']=is_array($Remark)?implode('\n',$Remark):$Remark;
                    $v['PriceFare']=$val['AirItineraryPricingInfo']['PriceInfo'][0]['Fare'];
                    $v['PriceTax']=$val['AirItineraryPricingInfo']['PriceInfo'][0]['Tax'];
                    $datas['list'][]=$v;
                }
                $$v['FlightNos']=$v['FlightNos'];
            }
        }
        return $datas;
    }

    /*
     * 数据重组转换
     */
    function fr(&$Option,$key){
        global $common;
        $this->db->AirlineDb=$this->db->AirlineDb?$this->db->AirlineDb:D('Airline');
        $this->db->CityDb=$this->db->CityDb?$this->db->CityDb:D("City");
        $this->db->AirportDb=$this->db->AirportDb?$this->db->AirportDb:D("Airport");
        foreach($Option as $ka=>$va){
            if(is_numeric($ka)){
                $Option[$ka]['PricedItineraryId']=$key;
                $Option[$ka]['FlightTime']=0;
                foreach($va as $kb=>$vb){
                    if($kb==='FilingAirline'){ //航空公司
                        $Airline="Airline".$vb;
                        !isset($common[$Airline]) &&  $common[$Airline]=$this->db->AirlineDb->getAirlineName($vb);
                        $Option[$ka]['AirlineName'] = $common[$Airline];
                    }elseif($kb==='FlightSegment'){     //航程
                        if(!isset($vb[0])){
                            unset($Option[$ka][$kb]);
                            $vb=$Option[$ka][$kb]=array($vb);
                        }
                        $Option[$ka]['Flat'] =count($vb);
                        foreach($vb as $ko=>$vo){
                            // 时间格式转换
                            $Option[$ka][$kb][$ko]['DepartureDate']= strtotime($vo['DepartureDateTime']);
                            $Option[$ka][$kb][$ko]['ArrivalDate']=strtotime($vo['ArrivalDateTime']);
                            !isset($Option[$ka]['DepartureDate']) && $Option[$ka]['DepartureDate']=strtotime($vo['DepartureDateTime']);
                            if($ko==($Option[$ka]['Flat']-1)) $Option[$ka]['ArrivalDate']=strtotime($vo['ArrivalDateTime']);

                            if(is_numeric($ko)){
                                $ko!=0 && $Option[$ka][$kb][$ko]['DepartureSpanDays']=floor((strtotime($vo['DepartureDateTime'])-$Option[$ka]['DepartureDate'])/86400);
                                $Option[$ka][$kb][$ko]['ArrivalSpanDays']=floor((strtotime($vo['ArrivalDateTime'])-$Option[$ka]['DepartureDate'])/86400);

                                foreach($vo as $ks=>$vs){
                                    if($ks==='FlightNumber'){// 行班号
                                        $Option[$ka]['FlightNos']=isset($Option[$ka]['FlightNos'])?$Option[$ka]['FlightNos'].'-'.$vo['OperatorCode'].$vs:$vo['OperatorCode'].$vs;
                                    }elseif($ks=='FlightTime'){ //飞行时间
                                        $Option[$ka]['FlightTime']=isset($Option[$ka]['FlightTime'])?$Option[$ka]['FlightTime']+$vs:"";
                                    }elseif($ks=='CabinLeft'){//剩余仓位
                                        $Option[$ka]['CabinLeft']=isset($Option[$ka]['CabinLeft']) && ($Option[$ka]['CabinLeft']>$vs) ? $Option[$ka]['CabinLeft']:$vs;
                                    }elseif($ks==='OperatorCode'){//航空公司
                                        $Option[$ka]['AirCodes']=isset($Option[$ka]['AirCodes'])?$Option[$ka]['AirCodes'].','.$vs:$vs;
                                        $Airline="Airline".$vs;
                                        !isset($common[$Airline]) &&  $common[$Airline]=$this->db->AirlineDb->getAirlineName($vs);
                                        $Option[$ka][$kb][$ko]['AirCompanyName']= $common[$Airline];
                                    }elseif($ks==='Equipment'){
                                        $Option[$ka][$kb][$ko]['Equipment']=$vs['AirEquipType'];
                                    }elseif(isset($vs['LocationCode'])){ //城市代码
                                        $city ="City".$vs['LocationCode'];
                                        $Airport="Airport".$vs['LocationCode'];
                                        !isset($common[$city]) && $common[$city]=$this->db->CityDb->getCityName($vs['LocationCode']);
                                        !isset($common[$Airport])  && $common[$Airport]=$this->db->AirportDb->getAirportCity($vs['LocationCode']);
                                        $Option[$ka]['AirPorts']=isset($Option[$ka]['AirPorts'])?$Option[$ka]['AirPorts'].'-'.$vs['LocationCode']:$vs['LocationCode'];
                                        if($ks=='ArrivalAirport'){//到达地
                                            $Option[$ka][$kb][$ko]['ArrivalCityCode']=$vs['LocationCode'];
                                            $Option[$ka][$kb][$ko]['ArrivalCityName']=$common[$city];
                                            $Option[$ka]['ArrivalCityName']=$common[$city];
                                            $Option[$ka][$kb][$ko]['ArrivalAirportName']=$common[$Airport];
                                        }elseif($ks=='DepartureAirport'){//出发地
                                            $Option[$ka][$kb][$ko]['DepartureCityCode']=$vs['LocationCode'];
                                            $Option[$ka][$kb][$ko]['DepartureCityName']=$common[$city];
                                            !isset($Option[$ka]['DepartureCityName']) &&  $Option[$ka]['DepartureCityName']=$common[$city];
                                            $Option[$ka][$kb][$ko]['DepartureAirportName']=$common[$Airport];
                                        }
                                    }elseif($ks=='DptTerminal'){ //出发航站楼
                                        $Option[$ka][$kb][$ko]['DptTerminal']=$vs['Code'];
                                    }elseif($ks=='ArvTerminal'){ //到达航站楼
                                        $Option[$ka][$kb][$ko]['ArvTerminal']=$vs['Code'];
                                    }elseif($ks=='MarketingAirline'){
                                        $Option[$ka][$kb][$ko]['MarketingAirline']=$vs['Code'];
                                    }

                                    if($ko>0){ //停留时间
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

    /*
     * 航程预定信息
     */
    function flightBookingInfo($queryData,$PosId){
        $Name=md5(implode(',',$queryData['query']));
        if(!S($Name)){
            $rs=$this->_searchTicket($queryData['query']);
            $obj=$this->xml2Object($rs);
            if($obj->PricedItineraries->FinFlag=="T"){
                S($Name,$rs);
            }
        }else{
            $rs=S($Name);
        }
        $rs=simplexml_load_string($rs);
        $posId=explode('-',$PosId);
        $posId=array(
            'ItineraryId'=>(int)$posId[0],
            'Option1'=>(int)$posId[1], //去程 序号id
            'Option2'=>(int)$posId[2], //回程 序号id
        );
        $data=$rs->PricedItineraries->PricedItinerary[$posId['ItineraryId']];

        $dataOriginDestination=$data->AirItinerary->OriginDestinationOption;
        foreach($dataOriginDestination as $val){
            $arr=(array)$val;
            if(isset($arr['@attributes'])){
                $attr=$arr['@attributes'];
                if($attr['RefNumber']==1){
                    if($attr['OptionId']==$posId['Option1']){
                        $xml[]=$this->object2Array($val);
                    }
                }

                if($attr['RefNumber']==2){
                    if($attr['OptionId']==$posId['Option2']){
                        $xml[]=$this->object2Array($val);
                    }
                }
            }
        }
        if(empty($xml)){
            $this->setError('操作超时');
            //    return false;
        }
        $AirItineraryPricingInfo = $this->object2Array($data->AirItineraryPricingInfo);
        $data=$queryData;
        $data['AirItineraryPricingInfo']=$AirItineraryPricingInfo;
        $data['OriginDestinationOption']=$xml;
        //机票选定信息
       // session('flightBookingInfo',$data);
        S($this->getGid().'flightBookingInfo',$data);
        $data['OriginDestinationOption']=$this->unAttributes($xml);
        $this->fr($data['OriginDestinationOption'],1);
        return $data;
    }

    /*
     * 机票预定
     */
    function ticketBooking($data){
        if(!$data){
            $this->setError('操作超时');
            return false;
        }
    //    $rs=$this->_ticketBooking($data);
        $rs=<<<xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_AirCreateOrderRS xmlns="http://www.trafree.com/OTA/2011/05" Version="1.000" encoding="UTF-8">
  <POS>
    <Requestors>
      <Requestor Type="13" Password="27DE099770E48EEBF754F330E0B997FE" ID="meile"/>
    </Requestors>
    <Currency Code="CNY"/>
  </POS>
  <AirOrderInfo AliasOrderId="TR1405240000545e" LocalOrderId="A201405241146357835" ReturnURL="" PayMode="0">
    <AirCommissions>
      <AirCommission CommissonId="209@15@12168/0;14701/0;" Type="0"  Rate="4.70" BaseFareRate="3.00" TicketFee="0.00" ReturnCash="0.00" ReturnCashCurrency="CNY" CnnRate="0.00" CnnTicketFee="0.00" CnnReturnCash="0.00" CnnReturnCashCurrency="CNY"/>
    </AirCommissions>
  </AirOrderInfo>
  <BookingReferences>
    <BookingReference>
      <OriginDestinationOption RefNumber="1" OptionId="1"  IsCheckfare="1" FilingAirline="CZ" Resource="TFR"   >
        <FlightSegment DepartureDateTime="2014-06-17T12:40:00" ArrivalDateTime="2014-06-17T14:30:00" DepartureDay="TUE" E_TicketEligibility="true" FlightNumber="3081" InfoSource="PEK440" InfoSourceTypeCode="14" NumberInParty="1" ResBookDesigCode="V" StopQuantity="0" ValidConnectionInd="false" IsShareCode="false" CabinLeft="9" OperatorCode="CZ" FlightTime="170">
          <DepartureAirport LocationCode="CAN"/>
          <ArrivalAirport LocationCode="BKK"/>
          <Equipment AirEquipType="738"/>
          <MarketingAirline Code="CZ"/>
          <DptTerminal Code=""/>
          <ArvTerminal Code=""/>
        </FlightSegment>
      </OriginDestinationOption>
      <OriginDestinationOption RefNumber="2" OptionId="1"  IsCheckfare="1" FilingAirline="CZ" Resource="TFR"   >
        <FlightSegment DepartureDateTime="2014-07-31T08:40:00" ArrivalDateTime="2014-07-31T12:25:00" DepartureDay="THU" E_TicketEligibility="true" FlightNumber="0362" InfoSource="PEK440" InfoSourceTypeCode="14" NumberInParty="1" ResBookDesigCode="V" StopQuantity="0" ValidConnectionInd="false" IsShareCode="false" CabinLeft="9" OperatorCode="CZ" FlightTime="170">
          <DepartureAirport LocationCode="BKK"/>
          <ArrivalAirport LocationCode="CAN"/>
          <Equipment AirEquipType="738"/>
          <MarketingAirline Code="CZ"/>
          <DptTerminal Code=""/>
          <ArvTerminal Code=""/>
        </FlightSegment>
      </OriginDestinationOption>
      <AirItineraryPricingInfo>
        <TicketingInfo/>
        <PriceInfo Fare="1560" Tax="879" Currency="CNY"    PassengerTypeCode="ADT" PassengerQuantity="1"  />
      </AirItineraryPricingInfo>
      <BookingReferenceID ID="JG52L1" Type="14" InfoSource="PEK440" Resource="TFR"/>
      <TravelerInfo>
       <AirTraveler PassengerTypeCode="ADT" NamePrefix="1" GivenName="yinpengfei" Surname="yin" BirthDate="1980-01-01" CountryCode="CN" IDNumber="123456" IDValidTo="1980-01-05" PermitType="" TicketingRefCode="" />
        <AirTraveler PassengerTypeCode="ADT" NamePrefix="1" GivenName="yinpengfei" Surname="yin" BirthDate="1980-01-01" CountryCode="CN" IDNumber="123456" IDValidTo="1980-01-05" PermitType="" TicketingRefCode="" />
      </TravelerInfo>
      <AirContacter NamePrefix="ms" GivenName="yinpengfei yin" Surname="yinpengfei yin" Phone="18673800250" Email="1000@yin.cc"/>
      <ServiceClassChange Changed="false"/>
    </BookingReference>
  </BookingReferences>
</OTA_AirCreateOrderRS>
xml;

        $data=$this->xml2Array($rs);
        $data=$this->unAttributes($data);
        if(isset($data['Fail'])){
            $this->setError('操作失败:'.$data['Fail']['ErrMsg']);
            return false;
        }

        return $data;
    }



}