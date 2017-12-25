<?php

/**
  +------------------------------------------------------------------------------
 * 自由飞越 订单查询 预定api
 * 正式上线URL  https://interface.trafree.com/platform/portal;
 * $url="http://test.trafree.com/platform/portal";
 * http://114.80.119.106:8997/platform/portal
 *
 * 引用类： Array2xml.class.php
 *
 * Author:me@yin.cc
 * Date:2014-04-21
  +------------------------------------------------------------------------------
 */
class trafree {
    protected    $url;
    protected    $user;            //用户名
    protected    $password;        //密码
    private      $logPath=false;        // log 日志路径
    public       $rsObj;            //请求返回 xml object

    public       $returnFormat;   //反回格式
    private      $error;
    protected    $debug=false;

    /**
      +----------------------------------------------------------
     * 构造函数，对象初始化
      +----------------------------------------------------------
     */
     function __construct($data=array()){
     //    import('ORG.Array2xml',APP_PATH.'Lib/'); // 导入
         include_once "Array2xml.class.php";
         $this->user=isset($data['user'])?$data['user']:"meile";
         $this->password=isset($data['password'])?strtoupper(md5($data['password'])):strtoupper(md5("meile123"));//gzmeile123

        $this->logPath=isset($data['log'])?$data['log']:"log";
      //   http://test.trafree.com/platform/portal;
      //   $this->url=isset($data['url'])?$data['url']:"http://test.trafree.com/platform/portal";
        $this->url=isset($data['url'])?$data['url']:"https://interface.trafree.com/platform/portal";
    }

    /*
     * 设置错误信息
     */
    function setError($re){
        $this->error=$re;
    }

    /*
     * 日志记录
     */
    private function log($name='',$data){
        if($this->logPath==false || empty($data))
            return false;
        $name=$name?$name:md5($data);   //日志文件目录
        $dir=$this->logPath."/".date("Y-m-d");
        if(!is_dir($dir))
            mkdir($dir,'0777',true);
        file_put_contents($dir.'/'."$name.xml",$data);
    }

    /*
     * 乘客类型转义
     * 成人ADT 学生STU 儿童CNN
     */
    function  toPassengerType($type){
        $type=strtoupper($type);
        switch($type){
            case "ADT":
                $str="成人";
                break;
            case "学生":
                $str="学生";
                break;
            case "CNN":
                $str="儿童";
                break;
            default:
                $str="";
        }
        return $str;
    }
    /*
     * 发送请求
     */
    function query($xml,$tag=''){
     //  print($xml);
        $logFileName=$tag.'_'.substr(md5($xml),0,6);
        $this->log($logFileName."_".date("His")."_RQ",$xml);
        $ch = curl_init();
    //   $proxy= "http://fei:77169@115.29.7.90:3122";
    //   curl_setopt ($ch,CURLOPT_PROXY, $proxy);
        $header[] = "Content-type: text/xml";//定义content-type为xml
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$xml);
        curl_setopt ( $ch, CURLOPT_TIMEOUT,60);
        $this->debug && curl_setopt($ch, CURLOPT_HEADER, 1);
        $rs=curl_exec($ch);
        curl_close($ch);
        $this->log($logFileName."_".date("His")."_RS",$rs);
        if($this->returnFormat){
            $fun="xml2".$this->returnFormat;
            if(method_exists($this,$fun)) $rs=$this->$fun($rs);
        }
        return $rs;
    }

    /*
     * 返回错误信息
     */
    function getError(){
        return $this->error;
    }

    /*
 * 递归去除 xml @attributes
 */
    function unAttributes($data){
        $arr=array();
        foreach($data as $key=>$val){
            if($key==='@attributes'){
                if(is_array($val))
                    $arr=$this->unAttributes($val);
                else
                    $arr=$val;
            }else{
                if(is_array($val))
                    $arr[$key]=$this->unAttributes($val);
                else
                    $arr[$key]=$val;
            }
        }
        return $arr;
    }

    /*
     * XML转数组
     */
    function  xml2Array($data='',$field=array()){
        $Obj = $data==''?$this->rsObj:(is_object($data)?$data:$this->xml2Object($data));
        if(!$Obj){
            $this->error="格式错误";
            return false;
        }else{
            $rs=json_decode(json_encode($Obj),TRUE);
            $str = serialize($rs);
            $str = str_replace('O:16:"SimpleXMLElement"','a',$str);
            return unserialize($str);

        }
    }

    /*
    * xml转对象
   */
    function xml2Object($data){
        return simplexml_load_string($data);
    }

   /*
    * 对象转数组
   */
    function object2Array($obj,$field=array()){
        $_arr = is_object($obj) ? (array)$obj : $obj;
        $arr=array();
        foreach ($_arr as $key => $val){
            if($key && in_array($key,$field)){
                continue;
            }
            $val = (is_array($val) || is_object($val)) ? $this->object2Array($val,$field) : $val;
            $arr[$key] = $val;

        }
        return $arr;
    }

    /*
     * 设置返回格式
     */
    function format($format){
        $format=strtolower($format);
        $arr=array("array","xml","object");
        if(!in_array($format,$arr)){
            $format="";
        }
        $this->returnFormat=$format;
        return $this;
    }


    /*
     * 机票查询
     * DepartureDate  出发日期
     * Origin  出发地
     * Destination  目的地
     * 成人ADT 学生STU 儿童CNN
     */
    function _searchTicket($data){
        if(!is_array($data)) return false;

        //默认参数
        $defaultData=array(
            "FlightType"=>1,    //1往返  2单程
            "TickType"=>'ADT',  //成人ADT 学生STU 儿童CNN
            "Age"=>"30",
            "PersonNum"=>1,  //成人
            "ChildNum"=>0,  //儿童

            "OriginDate"=>date("Y-m-d",time()+3600*24*3), //默认出发时间

            "OriginCode"=>"CAN",        //默认出发地 广州
            "DestinationCode"=>"HKG",   //默认的地 香港

            "MaxStops"=>2,  //指定结果集中最大转机次数，默认为2
            'Mode'=>'S',

            "Airline"=>'',  //指定结果集中必须包含的航司结果

            /*非必须*/
            "Language"=>'ZH',
        );
        //合并参数
        $data=array_merge($defaultData,$data);
        $data['FlightType']==1 && !$data['ReturnDate'] && $data['ReturnDate']=date("Y-m-d",strtotime($data['OriginDate'])+86400*7);

        $xmlArr['POS']=array(
                'Requestors'=>array(  //请求者信息
                    'Requestor'=>array(
                        "@attributes"=>array("Type"=>13,'Password' => "$this->password","ID"=>"$this->user"),
                    ),
                ),
                'TrendFactor'=>array("@attributes"=>array("Factor"=>"50")),
                'AV'=>array("@attributes"=>array("Code"=>"true")),
                'Currency'=>array("@attributes"=>array("Code"=>"CNY")),
                'Language'=>array("@attributes"=>array("Code"=>"$data[Language]")),
                'Mode'=>array( "@attributes"=>array("Code"=>"$data[Mode]"),),     //查询模式S：同步A：异步
                "CommissionMode"=>array("@attributes"=>array("Code"=>"G")),
        );

        //行程描述
        $xmlArr['OriginDestinationInformation'][0]=array(
            "@attributes"=>array("RefNumber"=>"1"),     //表示该信息的方向
            "DepartureDateTime"=>"$data[OriginDate]",      //出发时间
            "OriginLocation"=>array(     //LocationCode 出发地三字码  MultiAirportCityInd 出发地类型是否是城市,值域:true false
                "@attributes"=>array("LocationCode"=>"$data[OriginCode]","MultiAirportCityInd"=>"true"),
            ),
            "DestinationLocation"=>array(    //LocationCode到达地三字码,IATA 标准
                "@attributes"=>array("LocationCode"=>"$data[DestinationCode]","MultiAirportCityInd"=>"true"),
            ),
        );

        //往返
        if($data['FlightType']==1){
            $xmlArr['OriginDestinationInformation'][1]=array(
                "@attributes"=>array("RefNumber"=>"2"),     //表示该信息的方向
                "DepartureDateTime"=>"$data[ReturnDate]",   //出发时间
                "OriginLocation"=>array(            //LocationCode 出发地三字码  MultiAirportCityInd 出发地类型是否是城市,值域:true false
                    "@attributes"=>array("LocationCode"=>"$data[DestinationCode]","MultiAirportCityInd"=>"true"),
                ),
                "DestinationLocation"=>array(   //LocationCode到达地三字码,IATA 标准
                    "@attributes"=>array( "LocationCode"=>"$data[OriginCode]","MultiAirportCityInd"=>"true"),
                ),
            );
        }

        //乘客信息
        $AirTravelerAvail[0]=array(
            "PassengerTypeQuantity"=>array(
                "@attributes"=>array("Code"=>"$data[TickType]","Age"=>"$data[Age]","Quantity"=>"$data[PersonNum]"),
        ));
        $AirTravelerAvail[1]=array(//儿童
            "PassengerTypeQuantity"=>array(
                "@attributes"=>array("Code"=>"CNN","Age"=>"6","Quantity"=>"$data[ChildNum]"),
        ));
        //乘客信息
        $xmlArr['TravelerInfoSummary']=array(
            "TravelerInfoSummary"=>array(
                "AirTravelerAvail"=>$AirTravelerAvail,
            ),
        );
        //TravelPreferences 行程喜好信息
        $xmlArr['TravelPreferences']=array(
            "MaxStopsQuantity"=>array("@attributes"=>array("Quantity"=>"$data[MaxStops]")),
            "Airline"=>array("@attributes"=>array("Code"=>"$data[Airline]")),
        );

        //生成xml
        $xml=Array2XML::createXML("OTA_AirLowFareSearchRQ",$xmlArr);
        $xml=$xml->saveXML();
      //  echo $xml;exit;
        return $this->query($xml,'OTA_AirLowFareSearchRQ');
    }

    /*
     * 查询政策 OTA_AirCommission
     * 当API 使用者已经自己创建PNR 时，可以利用PNR 或RT 信息到Trafree 平台查询政策。以备后续计算结算价之用。
     */
    function _commission($data=array()){
        $defaultData=array(
            "Rt"=>'',    //RT
            'Type'=>0,
        );
        //合并参数
        $data=array_merge($defaultData,$data);

        $xmlArr['POS']=array(
            'Requestors'=>array(     //请求者信息
                'Requestor'=>array(
                    "@attributes"=>array("Type"=>13,'Password' => "$this->password","ID"=>"$this->user"),
                ),
            ),
        //    'CommissionMode'=>array("@attributes"=>array("Code"=>"$data[commissionMode]")), //政策查询模式： G：普通， F：所有
        );

        $xmlArr['BookingReferences']=array(
            'BookingReference'=>array(
                'BookingReferenceID'=>array(
                    "@attributes"=>$data['BookingReferenceID'],
                ),
               'RTDetail'=>"$data[Rt]",
            ),
        );
    //   print_r($xmlArr);exit;
        //生成xml
        $xml=Array2XML::createXML("OTA_AirCommissionRQ",$xmlArr);
        $xml=$xml->saveXML();
        return $this->query($xml,'OTA_AirCommissionRQ');
    }


    /*
     * PNR 创建出票交易请求
     */
    function _orderPnr($data=array()){
        $defaultData=array(
            "CommissionMode"=>'F',
        );
        //合并参数
        $data=array_merge($defaultData,$data);

        $xmlArr['POS']=array(
            'Requestors'=>array(  //请求者信息
                'Requestor'=>array("@attributes"=>array("Type"=>13,'Password' => "$this->password","ID"=>"$this->user"),
                ),
            ),
            'Currency'=>array("@attributes"=>array("Code"=>"CNY")),
        );
        $xmlArr['AirOrderInfo']=array("@attributes"=>array("LocalOrderId"=>"","ReturnURL"=>"","PayMode"=>'',));
        $xmlArr['BookingReferences']=array(
            'BookingReference'=>array(
                'AirItineraryPricingInfo'=>array("TicketingInfo"),
                'BookingReferenceID'=>array("@attributes"=>array("ID"=>"$data[Pnr]","Type"=>"0","InfoSource"=>"$data[InfoSource]",)),
                "AirContacter"=>array("@attributes"=>array("NamePrefix"=>"{$data['Contact']['NamePrefix']}","GivenName"=>"{$data['Contact']['GivenName']}","Surname"=>"{$data['Contact']['Surname']}","Email"=>"{$data['Contact']['Email']}","Phone"=>"{$data['Contact']['Phone']}")),
            ),
        );

        //生成xml
        $xml=Array2XML::createXML("OTA_AirCreateOrderRQ",$xmlArr);
        $xml=$xml->saveXML();
        return $this->query($xml,'OTA_AirCreateOrderRQ');

    }

    /*
     * 机票预定
     */
    function _ticketBooking($data=array()){
        $defaultData=array(
            "CommissionMode"=>'F',  //
            "LocalOrderId"=>"A".date("YmdHis").rand(1000,9999),
        );
        //合并参数
        $data=array_merge($defaultData,$data);

        $xmlArr['POS']=array(
            'Requestors'=>array(  //请求者信息
                'Requestor'=>array("@attributes"=>array("Type"=>13,'Password' => "$this->password","ID"=>"$this->user"),
                ),
            ),
            'Currency'=>array("@attributes"=>array("Code"=>"CNY")),
        );
        //订单信息节点
        $xmlArr['AirOrderInfo']=array(
            "@attributes"=>array("LocalOrderId"=>"$data[LocalOrderId]","ReturnURL"=>""),
         //   $data['airCommission'] && "AirCommissions"=>array("AirCommission"=>array("@attributes"=>array($data['airCommission'])))
        );

        $xmlArr['BookingReferences']=array(
            'BookingReference'=>array(
                'OriginDestinationOption'=>$data['OriginDestinationOption'],
                "AirItineraryPricingInfo"=>$data['AirItineraryPricingInfo'],
            ),
        );

       foreach($data['TravelerInfo'] as $val){
           $xmlArr['BookingReferences']['BookingReference']["TravelerInfo"][]=array("AirTraveler"=>array("@attributes"=>$val));
       }
        $xmlArr['BookingReferences']['BookingReference']["AirContacter"]=array("@attributes"=>array("NamePrefix"=>"{$data['Contact']['NamePrefix']}","GivenName"=>"{$data['Contact']['GivenName']}","Surname"=>"{$data['Contact']['Surname']}","Email"=>"{$data['Contact']['Email']}","Phone"=>"{$data['Contact']['Phone']}"));

    //   print_r($xmlArr);exit;
        //生成xml
        $xml=Array2XML::createXML("OTA_AirCreateOrderRQ",$xmlArr);
        $xml=$xml->saveXML();
    //    print_r($xml);exit;
        return $this->query($xml,'OTA_AirCreateOrderRQ');
    }

    /*
     * 验证价格
     */
    function _validatePrice($data=array()){
        $defaultData=array(//默认参数
            "BookingReferenceID"=>array(
                "ID"    => "",
                "Type"  =>"14",
                "InfoSource"=>"",
            ),
        );
        //合并参数
        $data=array_merge($defaultData,$data);

        $xmlArr['POS']=array(
            'Requestors' =>array(  //请求者信息
                'Requestor' =>array("@attributes"=>array("Type"=>13,'Password' => "$this->password","ID"=>"$this->user"),
                ),
            ),
            'Currency'=>array("@attributes"=>array("Code"=>"CNY")),
        );
        //订单信息节点
        $xmlArr['AirOrderInfo']=array(
            "@attributes"   =>array(
                "AliasOrderId"=>"$data[AliasOrderId]","PayMode"=>"0"
            ),
         //   "AirCommissions"=>array( "AirCommission"=>array("@attributes"=>$data['AirCommission']),  )
        );
        $xmlArr['BookingReferences']=array(
            'BookingReference' =>array(
                'AirItineraryPricingInfo'=>array("TicketingInfo"=>array("@attributes"=>array()),),
                "BookingReferenceID"=>array("@attributes"=>array("ID"=>"{$data['BookingReferenceID']['ID']}","Type"=>"14","InfoSource"=>"{$data['BookingReferenceID']['InfoSource']}")),
            ),
        );
     //   print_r($xmlArr);exit;
        //生成xml
        $xml=Array2XML::createXML("OTA_AirValidatePriceRQ",$xmlArr);
        $xml=$xml->saveXML();
        return $this->query($xml,'OTA_AirValidatePriceRQ');
    }



    /*
     * 订单详情查询
     */
    function _orderDetail($data=array()){
        $defaultData=array(//默认参数
            "BookingReferenceID"=>array(
                "ID"    => "",
                "Type"  =>"14",
                "InfoSource"=>"",
            ),
        );
        //合并参数
        $data=array_merge($defaultData,$data);

        $xmlArr['POS']=array(
            'Requestors' =>array(  //请求者信息
                'Requestor' =>array("@attributes"=>array("Type"=>13,'Password' => "$this->password","ID"=>"$this->user")),
            ),
            'Currency'=>array("@attributes"=>array("Code"=>"CNY")),
        );
        //订单信息节点
        $xmlArr['AirOrderInfo']=array("@attributes"   =>array("AliasOrderId"=>"$data[AliasOrderId]","PayMode"=>"0"));

        $xmlArr['BookingReferences']=array(
            'BookingReference' =>array(
                'AirItineraryPricingInfo'=>array("TicketingInfo"=>array("@attributes"=>array()),),
                "BookingReferenceID"=>array("@attributes"=>array("ID"=>"{$data['BookingReferenceID']['ID']}","Type"=>"14","InfoSource"=>"{$data['BookingReferenceID']['InfoSource']}")),
            ),
        );

        //生成xml
        $xml=Array2XML::createXML("OTA_AirOrderDetailRQ",$xmlArr);
        $xml=$xml->saveXML();
        return $this->query($xml,'OTA_AirOrderDetailRQ');
    }





}
?>