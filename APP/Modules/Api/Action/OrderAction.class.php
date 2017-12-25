<?php

class OrderAction extends Action {

    function test(){
        import('ORG.flight',APP_PATH.'Lib/');// 导入
        $flight= new flight();

        $flight->searchTicket('');
    }
    /*
     * 机票查询
     */
    function searchTicket(){
        import('ORG.flight',APP_PATH.'Lib/');// 导入
        $flight= new flight();
        $data['OriginDate']=isset($_GET['OriginDate'])?$_GET['OriginDate']:"2014-06-20";
        $data['OriginCode']=isset($_GET['OriginCode'])?$_GET['OriginCode']:"CAN";
        $data['DestinationCode']=isset($_GET['DestinationCode'])?$_GET['DestinationCode']:"LAX";
        $data['ReturnDate']=isset($_GET['ReturnDate'])?$_GET['ReturnDate']:"2014-08-10";
        $data['FlightType']=1;
        $data['PersonNum']=2;
        $data['MaxStops']=1;
        $data['rad']='11344u4544458o.l4y4';
        $data['back']=true;
        $data= $flight->searchTicket($data);

      //  $this->ajaxreturn($data);
        if(I('test')){
               print_r($data);
        }
        $this->display('index');

        }

    /*
     * 定机票
     */
    function ticketBooking(){
        import('ORG.Trafree',APP_PATH.'Lib/');// 导入
        $trafree= new trafree();

        $data['OriginDate']="2014-06-08";
        $data['OriginCode']="CAN";
        $data['DestinationCode']="HKG";
        //    $data['returnDate']="2014-07-08";

        $TravelerInfo=array(
            "PassengerTypeCode"=>"ADT",
            "NamePrefix"=>"Ms",
            "GivenName"=>"Rachel",
            "Surname"=>"Vidmar",
            "BirthDate"=>"1988-07-06",
            "CountryCode"=>"CN",
            "IDNumber"=>"470241527",
            "IDValidTo"=>"2020-04-11",
            "PermitType"=>"1" ,
        );

        $Contact=array(
            "NamePrefix"=>"ms",
            "GivenName"=>"fei",
            "Surname"=>"fei",
            "Email"=>"me@yin.cc",
            "Phone"=>"18673800250",
        );
    //    print_r($data);
        $rs = $trafree->ticketBooking($data);
        print($rs);
        echo $trafree->getError();
    }

    /*
     * 验证价格
     */
   function validatePrice(){
       import('ORG.Trafree',APP_PATH.'Lib/');// 导入
       $trafree= new trafree();
       $data=array(
           "AliasOrderId"=>"TR140426000025c7",
           "BookingReferenceID"=>array(
               "ID"=>"HWCQFY",
                "Type"=>14,
                "InfoSource"=>"PEK440",
                "Resource"=>"TFR",
           ),
       );
       $rs=$trafree->validatePrice($data);
       print_r($rs);
   }

    /*
     *  查询政策
     */
    function commission(){
        import('ORG.Trafree',APP_PATH.'Lib/');// 导入
        $trafree= new trafree();
        $data['BookingReferenceID']=array(
            "ID"=>"HWCQFY",
            "InfoSource"=>"PEK440",
            "Resource"=>"TFR",
        );
        $rs=$trafree->commission($data);
        print_r($rs);
    }

    /*
     * 订单详情查询
     */
    function orderDetail(){
        import('ORG.Trafree',APP_PATH.'Lib/');// 导入
        $trafree= new trafree();
        $data=array(
            "BookingReferenceID"=>array(
                "ID"=>"HWCQFY",
                "Type"=>14,
                "InfoSource"=>"PEK440",
                "Resource"=>"TFR",
            ),
            "AliasOrderId"=>"TR140426000025c7",
            "LocalOrderId"=>"B123vac",
        );
        $rs=$trafree->orderDetail($data);
        print_r($rs);
    }




}