<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-7-23
 * Time: 下午5:32
 * To change this template use File | Settings | File Templates.
 */
class IndexAction extends Action{
        function index(){
            ini_set('max_execution_time', '60');
         if(I('originCode')){
             $ShopPing=D("ShopPing");
             $from_code= I('originCode')?cutStr(I('originCode'),3,0,''):"PEK";
             $to_code= I('desinationCode')?cutStr(I('desinationCode'),3,0,''):"FRA";
             $from_date= I('originDate')?I('originDate'):"2014-05-15";
             $return_date= I('returnDate')?I('returnDate'):"FRA";
             if($_GET['flightType']==1){
$xmll=<<<xml
<OriginDestinationInformation>
<DepartureDate>
<Date>{$return_date}</Date>
</DepartureDate>
<OriginLocationCode>{$to_code}</OriginLocationCode>
<DestinationLocationCode>{$from_code}</DestinationLocationCode>
</OriginDestinationInformation>
xml;
             }
             $xml=<<<xml
<?xml version="1.0" encoding="UTF-8"?>
<TSK_AirfareFlightShop>
    <TSK_AirfareFlightShopRQ>
        <OTA_AirLowFareSearchRQ>
            <POS>
                <PseudoCityCode>CAN526</PseudoCityCode>
                <AirportCode>CAN</AirportCode>
                <ChannelID>1E</ChannelID>
            </POS>
            <OriginDestinationInformation>
                <DepartureDate>
                    <Date>{$from_date}</Date>
                </DepartureDate>
                <OriginLocationCode>{$from_code}</OriginLocationCode>
                <DestinationLocationCode>{$to_code}</DestinationLocationCode>
            </OriginDestinationInformation>
            {$xmll}
            <TravelPreferences />
        </OTA_AirLowFareSearchRQ>
        <AdditionalShopRQData>
            <IncludeFlightAvailability>true</IncludeFlightAvailability>
        </AdditionalShopRQData>
    </TSK_AirfareFlightShopRQ>
</TSK_AirfareFlightShop>
xml;
            echo $xml;
             $rs=$ShopPing->GetResponse($xml,0);

         }

            $xmlObj=simplexml_load_string($rs);
         //  print_r($xmlObj);
            $AvailableJourneys=$xmlObj->TSK_AirfareFlightShopRS->AvailableJourneys;
          //  print_r($AvailableJourneys);
            $rs=object_to_array($AvailableJourneys->AvailableJourney);

            $PricedTrips=object_to_array($xmlObj->TSK_AirfareFlightShopRS->PricedTrips);


            foreach($rs['AvailJourneyOption'] as $val){
                $AvailJourneyOption[$val['RPH']]=$val;
            }

          //  print_r($PricedTrips);

            foreach($PricedTrips['PricedTrip'] as $val){
                $PricedTrip[$val['SequenceNumber']]=$val;
            }
         //   print_R($PricedTrips);
            foreach($PricedTrips['PriceAvailabilityBindings']['PriceAvailabilityBinding'] as $val){
                $Binding[]=$val;
                foreach($AvailJourneyOption as $k=>$v){
                    if(deep_in_array($k,$val['AvailJourneyBinding'])){
                        $AvailJourneyOption[$k]['Price']= $PricedTrip[$val['SequenceNumber']];
                    }

                }

            }
         //   print_r($Binding);
           $this->count_num=count($AvailJourneyOption);
            $this->Price_num=count($Binding);
           $this->info=$AvailJourneyOption;
            $this->display();
          //  echo "<pre>";
         //   print_r($AvailJourneyOption);
          //  echo "</pre>";
    //     print_r($rs);


}

    function test(){
        $xml= isset($_POST['inter_xml'])?$_POST['inter_xml']:0;
        if($xml){
            ini_set('max_execution_time', '0');
            $ShopPing=D("ShopPing");
            $rs=$ShopPing->GetResponse($xml,0);
            header("content-type:text/xml");
            echo $rs;
        }else{
            $this->display('test');
        }


    }

    function index2(){
        $xml=<<<xml
<TSK_AirfareFlightShop>
<TSK_AirfareFlightShopRQ>
<OTA_AirLowFareSearchRQ>
<DirectFlightsOnly>false</DirectFlightsOnly>
<POS>
<PseudoCityCode>CAN526</PseudoCityCode>
<AirportCode>CAN</AirportCode>
<ChannelID>1E</ChannelID>
<IataNo>08322042</IataNo>
</POS>
<OriginDestinationInformation>
<RPH>01</RPH>
<OriginLocationCode>PEK</OriginLocationCode>
<DestinationLocationCode>HKG</DestinationLocationCode>
<DepartureDate>
<Date>2014-05-20</Date>
<WindowBefore>1</WindowBefore>
<WindowAfter>0</WindowAfter>
</DepartureDate>
</OriginDestinationInformation>
<TravelerInfoSummary>
<AirTravelerAvail>
<PassengerTypeQuantity>
<Code>ADT</Code>
<Quantity>1</Quantity>
</PassengerTypeQuantity>
</AirTravelerAvail>
<PriceRequestInformation>
<PricingSource>Both</PricingSource>
</PriceRequestInformation>
</TravelerInfoSummary>
</OTA_AirLowFareSearchRQ>
<AdditionalShopRQData>
<IncludeFlightAvailability>true</IncludeFlightAvailability>
<LowestOrAll>Lowest</LowestOrAll>
<FsOrCs>CS</FsOrCs>
</AdditionalShopRQData>
</TSK_AirfareFlightShopRQ>
</TSK_AirfareFlightShop>
xml;
        $ShopPing=D("ShopPing");
        echo "<pre>";
        print_r($ShopPing->GetResponse($xml,0));
        echo "</pre>";
    }

}