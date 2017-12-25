<?php
class IflightAction extends IniAction {
    public function index(){
        $this->title="【品悦国际机票】机票查询,特价机票,打折飞机票";
        $this->keywords="国际机票,机票预定,特价机票,打折机票,机票预订,飞机票,航班信息,航班查询,航班查询";
        $this->description="品悦国际机票,特价机票，提供国内2600多条航线的飞机票查询服务和特价机票信息。同时搜索国航、南航、携程等上千家机票预定网站.99元特价机票、1折超低折扣机票,是您机票查询和飞机票预定的最佳渠道.";
        R('Common/cheap','arr'); //特价机票数据
        $this->push=D('News')->getList(33,5,1);
        $this->display();
    }

    public function demand(){
        if(IS_POST){
            $rs=preg_match('/^([0-9]){11,12}$/i',I('phone'));
            if(!$rs){
                echo "手机号格式不正确";
                exit;
            }

            $data= I('post.');
            $data['from_city']=implode(',',$data['from_city']);
            $data['to_city']=implode(',',$data['to_city']);
            $data['origin_date']=implode(',',$data['originDate']);
            $data['telephone']=$data['area'].'-'.$data['phone_no'].'-'.$data['ext'];
            $rs=D('RequireOrder')->insert($data);
            if($rs){
                $this->success('提交成功,稍后我们客服将联系您');
            }else{
                $this->error('抱歉！ 提交失败,或您已提交过此需求单,请稍后再试, 建议直接给我们来电');
            }
        }else{
            $this->title="国际航班机票需求单";
            $this->display();
        }
    }

    //接收需求表单提交信息
    function requireReceive(){
        if(IS_POST){
            $referer=isset($_POST['referer'])?$_POST['referer']:"http://".get_http_referer(1);
			print_r( $referer);
			exit();
            $rs=preg_match('/^([0-9]){11,12}$/i',I('phone'));
            if(!$rs){
                if(IS_AJAX){
                    echo "手机号格式不正确"; exit;
                }else{
                    $this->error('手机号格式不正确');
                }
            }

            $_POST['from_city']=get_encoding($_POST['from_city']);
            $_POST['to_city']=get_encoding($_POST['to_city']);
            $_POST['name']=get_encoding($_POST['name']);

            $rs=D('RequireOrder')->insert();
            if($rs){
                $this->success('提交成功,稍后我们客服将联系您',$referer);
            }else{
                $this->error('抱歉！ 提交失败,或您已提交过此需求单, 建议直接给我们来电');
            }
        }else{
            $this->error('参数有误');
        }
    }

    function demandTemplates(){
        $this->display();
    }

    //测边需求提交单
    function sidebar(){
        if(IS_POST){
            $referer=isset($_POST['referer'])?$_POST['referer']:"http://".get_http_referer(1);
            $rs=preg_match('/^([0-9]){11,12}$/i',I('phone'));
            if(!$rs){
                if(IS_AJAX){
                    echo "手机号格式不正确"; exit;
                }else{
                    $this->error('手机号格式不正确');
                }
            }
            $rs=D('RequireOrder')->insert();
            if($rs){
                $this->success('提交成功,稍后我们客服将联系您',$referer);
            }else{
                $this->error('抱歉！ 提交失败,或您已提交过此需求单, 建议直接给我们来电');
            }
        }else{
            $html=$this->fetch('sidebar');
            header("Content-type: text/javascript; charset=utf-8");
            echo htmltojs($html);
        }
    }

    function ajaxSend(){
        $this->send=D('RequireOrder')->ajaxList();
        $this->display('sidebar');
    }

    function ajaxInfo(){
        $require=D('RequireOrder');
        $this->info=$require->ajaxInfo();
        //   $this->display('sidebar');
    }

    //国际机票验证
    function verify(){
        $this->title="国际机票验证";
        $this->display();
    }

    //航空公司专栏
    function specialColumn(){
        $this->title="航空公司专栏";
        $this->display();
    }

    //国际机票查询
    function flightquery(){
        if(!$_GET['s_sengine'] && isset($_SESSION['s_sengine'])){
            $s_keyword=get_encoding($_SESSION['s_keyword']);
            $request_url = isset($_SERVER['QUERY_STRING']) ? "?s_sengine={$_SESSION['s_sengine']}&s_keyword=$s_keyword&".$_SERVER['QUERY_STRING'] : '';//判断地址后面部分
            //    $request_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI']."&s_sengine={$_SESSION['s_sengine']}&s_keyword={$_SESSION['s_keyword']}" : '';//判断地址后面部分
            header('HTTP/1.1 301 Moved Permanently');//发出301头部
            header('Location: '.U($_SERVER['PATH_INFO']).$request_url);//跳转到我的新域名地址
        }
        $this->title="国际机票查询";
        $originCode=substr(I("originCode"),0,3);
        $originName=D("City")->getCityName($originCode);
        $desinationCode=substr(I("desinationCode"),0,3);
        $desinationName=D("City")->getCityName($desinationCode);

        $_GET['origin_name']=$originName."($originCode)";
        $_GET['desination_name']=$desinationName."($desinationCode)";
        //检查城市
        if(!$originName || !$desinationName){
            $this->error('抱歉！ 查询失败,您输入城市的有误 请检查！');
        }

        $_GET['origin_name']=urldecode(I("origin_name"));
        $_GET['desination_name']=urldecode(I("desination_name"));
        //修正日期
        $originTime=strtotime(I("originDate"));
        $_GET['originDate']=$originTime<time()?date('Y-m-d',strtotime("+7 day")):date('Y-m-d',$originTime);
        $returnTime=strtotime(I("returnDate"));
        $_GET['returnDate']=$returnTime<time()?date('Y-m-d',strtotime("+14 day")):date('Y-m-d',$returnTime);
        //加入搜索记录
        if(I('originCode')){
            $searchRecord=D('searchRecord');
            $data['type']=I("flightType")?I("flightType"):1;
            $data['from_city']=$originName;
            $data['from_code']=I("originCode");
            $data['from_date']=I("originDate");
            $data['to_city']=$desinationName;
            $data['to_code']=I('desinationCode');
            $data['to_date']=I('returnDate');
            if(!$searchRecord->create($data)){
                $this->error($searchRecord->getError());
            }
            $searchRecord->add();
        }
        $this->display();
    }

    //国际机票预约
    function orderflight(){
        $this->title="国际机票预约";
        $this->display();
    }







    /*************   api 在线查询订票 ****************/

    public function flightBook(){
        session("flightBook",1);
        $this->title="国际机票查询";
        $this->display();
    }

    /*
     * 检测查询
     */
    function checkQuery(){
        if(!session("flightBook")){
            $this->error("查询超时");
        }
        if(!I('originDate'))
            $this->error('出发日期不能为空');
        if(strtotime(I('originDate'))<time())
            $this->error('出发日期不能小于当前日期');
        if(!I('originCode') || !I('desinationCode'))
            $this->error('出发城市、到达城市 不能为空');
        if(I('journey')==2){
            if(strtotime(I('returnDate'))<strtotime(I('originDate')))
                $this->error('返回日期不能小于出发日期');
        }
        $data=array(
            'OriginDate'=>I('originDate'),
            'OriginCode'=>I('originCode')?strtoupper(substr(I('originCode'),0,3)):"CAN",
            'DestinationCode'=>I('desinationCode')?strtoupper(substr(I('desinationCode'),0,3)):"SEL",
            'ReturnDate'=>I('returnDate'),
            'FlightType'=>I('journey')?I('journey'):1,
            'PersonNum'=>I('selInAdult'),
            'TickType'=>I('selInPassengersType')?I('selInPassengersType'):"ADT",
            'ChildNum'=>I('selInChild'),
            'MaxStops'=>1,
            'Mode'=>'S',
        );
       return $data;
    }

    /*
     * 机票查询
     */
    public function searchTicket(){
        import('ORG.flight',APP_PATH.'Lib/');// 导入
        global $common;
        $CityDb=D("City");
        $flight= new flight();
        $data=$this->checkQuery();
        $list= $flight->searchTicket($data,I('back'));
        $data['queryStr']=http_build_query($data);

        if(empty($list['list'])){
            $this->error('没有该航班信息');
        }


        $DestinationCity= $common['City'.$data['DestinationCode']];
        $OriginCity=$common['City'.$data['OriginCode']];
        $data['DestinationCity']=$DestinationCity?$DestinationCity:$CityDb->getCityName($data['DestinationCode']);
        $data['OriginCity']=$OriginCity?$OriginCity:$CityDb->getCityName($data['OriginCode']);
        isset($list['OriginRoute']) && $data['OriginRoute']=$list['OriginRoute'];
        $data['OriginDestinationOption']=$list['list'];
        $data['originReturn']= I('back')?1:0;
        $data['status']=1;
        $data['FinFlag']=$list['FinFlag'];

        $this->list=$data;
        if(IS_AJAX){
            $this->ajaxreturn($data);
        }
        $this->title="国际机票查询";
        $this->display();
    }


    /*
     * 填写订单
     */
    public function flightOrder(){
        import('ORG.flight',APP_PATH.'Lib/');// 导入
        $flight= new flight();
        $CityDb=D("City");
        $data['query']=$this->checkQuery();

        $posid=I('PosId')?I('PosId'):(I('posid')?I('posid'):'0-1-1');
        $list=$flight->flightBookingInfo($data,$posid);
        //超时操作
        if(!$list){
            $this->error($flight->getError(),U('searchTicket')."?".http_build_query($data['query']));
        }

        //价格计算
        $PriceInfo=$list['AirItineraryPricingInfo']['PriceInfo'];
        $FareInfo['FareSubtotal']=$FareInfo['TaxSubtotal']=$FareInfo['PriceTotal']=0;
        foreach($PriceInfo as $val){
            $passenger_type=$val['@attributes']['PassengerTypeCode'];
            $FareInfo[$passenger_type]=$val['@attributes'];
            if($passenger_type=='ADT'){
                $num=$data['query']['PersonNum'];
            }elseif($passenger_type=='CNN'){
                $num=$data['query']['ChildNum'];
            }
            $FareInfo['FareSubtotal']+=($val['@attributes']['Fare']*$num);
            $FareInfo['TaxSubtotal']+=($val['@attributes']['Tax']*$num);
        }

        $FareInfo['PriceTotal'] =($FareInfo['FareSubtotal']+$FareInfo['TaxSubtotal']);
        $this->fareInfo=$FareInfo;
        $list['DestinationCity']=$CityDb->getCityName($data['query']['DestinationCode']);
        $list['OriginCity']=$CityDb->getCityName($data['query']['OriginCode']);
        $this->list=$list;
        $this->title="填写订单";
		$this->display();
    }

    /*
     * 核对订单
     */
    public function flightOrderCheck(){
        if(!IS_POST) $this->error('操作有误');
        // 导入
        import('ORG.flight',APP_PATH.'Lib/');
        $flight= new flight();

        if(!S($flight->getGid().'flightBookingInfo'))  $this->error('操作超时');
        //表单乘机人数组
        foreach($_POST['TravelerInfo'] as $key=>$val){
            foreach($val as $ko=>$vo){
                $TravelerInfo[$ko][$key]=$vo;
            }
        }
       //转换数组
        $Passengers_as=array(
            'PassengerTypeCode'=>'passengerType',
            "NamePrefix"=>"sex",
            "GivenName"=>"givenName",
            "Surname"=>"surName",
            "BirthDate"=>'dobDate',
            "CountryCode"=>"CountryCode",
            "IDNumber"=>"ncredentialsNo",
            "IDValidTo"=>"ctDate",
         //   "CredentialsType"=>"credentialsType" ,
        );

        //转换数组
        $savePassengers_as=array(
            'passenger_type'=>'passengerType',
            "sex"=>"sex",
            "first_name"=>"givenName",
            "last_name"=>"surName",
            "birthday"=>'dobDate',
            "id_country"=>"CountryCode",
            "number"=>"ncredentialsNo",
            "validity"=>"ctDate",
            't_id'=>'credentialsType',
        );

        foreach($TravelerInfo as $key=>$val){
            foreach($val as $kk=>$vv){
                foreach($Passengers_as as $k=>$v){
                    if($kk==$v){
                        $TravelerInfos[$key][$k]=$vv;
                        if($k=='NamePrefix'){
                            $TravelerInfos[$key][$k]=$vv==1?"Mr":"Ms";
                        }
                    }
                }
                foreach($savePassengers_as as $k=>$v){
                    if($kk==$v){
                        $savePassengers[$key][$k]=$vv;
                    }
                }
            }
        }

        //添加常用乘机人
        if($val['savePassengers'] && getUid()){
            $PassengerDb=D("Passenger");
            $DocumentInfoDb=D('DocumentInfo');
            foreach($savePassengers as $val){
                $val['create_time']=time();
                $val['member_id']=getUid();
                $PassengerDb->create($val);
                if($val['p_id']=$PassengerDb->add()){
                    $DocumentInfoDb->create($val);
                    $DocumentInfoDb->add();
                }
            }
        }

        $data= S($flight->getGid().'flightBookingInfo');
        $data["LocalOrderId"]="A".date("YmdHis").rand(1000,9999);

        $data['TravelerInfo']=$TravelerInfos;
        $data['Contact']=array(
            "NamePrefix"=>"ms",
            "GivenName"=>I('contactsName'),
            'Surname'=>I('contactsName'),
            'Email'=>I('email'),
            'Phone'=> I('cellPhone')?I('cellPhone'):I('phoneArea').'-'.I('phoneNo').'-'.I('phoneExtension'),
        );

        // 人数统计
        $FareInfo['ADT']['num']=$FareInfo['CNN']['num']=0;
        foreach($TravelerInfos as $val){
            if($val['PassengerTypeCode']=='ADT'){
                $FareInfo['ADT']['num']++;
            }elseif($val['PassengerTypeCode']=='CNN'){
                $FareInfo['CNN']['num']++;
            }
        }

        //价格计算
        $PriceInfo=$data['AirItineraryPricingInfo']['PriceInfo'];
        $PriceInfo=$flight->unAttributes($PriceInfo);
        $FareInfo['FareSubtotal']=$FareInfo['TaxSubtotal']=$FareInfo['PriceTotal']=0;
        foreach($PriceInfo as $val){
            $passenger_type=$val['PassengerTypeCode'];
            $val['num']=$FareInfo[$passenger_type]['num'];
            $FareInfo[$passenger_type]=$val;
            $FareInfo['FareSubtotal']+=($val['Fare']* $val['num']);
            $FareInfo['TaxSubtotal']+=($val['Tax']*$val['num']);
        }
        $FareInfo['PriceTotal'] =($FareInfo['FareSubtotal']+$FareInfo['TaxSubtotal']);
        $data['FareInfo']=$FareInfo;

        //写入 缓存
        S($flight->getGid().'flightOrderCheck',$data);

        $rs=$flight->unAttributes($data['OriginDestinationOption']);
        $flight->fr($rs,1);
        $data['OriginDestinationOption']=$rs;

        $countryDb = D('Country');
        foreach($TravelerInfos as $key=>$val){
            $data['TravelerInfo'][$key]['CountryName']=$countryDb->where("country_code='$val[CountryCode]'")->getField('name');
            $data['TravelerInfo'][$key]['passengerType']=$flight->toPassengerType($val['PassengerTypeCode']);
            $data['TravelerInfo'][$key]['sex']=$val['NamePrefix']=='Mr'?"男":"女";
        }
        $this->fareInfo=$FareInfo;
        $this->list=$data;
        $this->title="核对订单";
        $this->display();
    }

    /*
    *提交 创建出票交易
    */
    function ticketBooking(){
     //   if(!IS_POST) $this->error('操作有误');
        // 导入
        import('ORG.flight',APP_PATH.'Lib/');
        $flight= new flight();
        //确认提交 跳转到支付

        if(S($flight->getGid().'flightOrderCheck')){
            $flightOrder=S($flight->getGid().'flightOrderCheck');
            $OrderId=$flightOrder['LocalOrderId'];
           // print_r($flightOrder);
            if(S($OrderId)){
                $Rs=S($OrderId);
            }else{
                $Rs=$flight->ticketBooking($flightOrder);
                S($OrderId,$Rs);
            }
            $BookingReference=$Rs['BookingReferences']['BookingReference'];
            $OriginDestinationOption=$BookingReference['OriginDestinationOption'];
            $flight->fr($OriginDestinationOption,1);
            $BookingReference['BookingReferenceID'];
            if(I('test')){
                print_r($OriginDestinationOption);
            }
            $OrderDb=D('Order');
            if($OrderDb->where("orderID='$OrderId'")->count()){
                $this->error("订单已存在，请勿重复提交");
            }
            $data=array(
                'orderID'=>$OrderId,
                'bookingJsonData'=>json_encode($Rs),
                'memberID'=>getUid(),
                'PNR'=>$BookingReference['BookingReferenceID']['ID'],
                'ticketOfficNO'=>$BookingReference['BookingReferenceID']['InfoSource'],
                'memberName'=>$BookingReference['AirContacter']['GivenName'].$flightOrder['Contact']['Surname'],
                'memberTel'=>$BookingReference['AirContacter']['Phone'],
                'memberEmail'=>$BookingReference['AirContacter']['Email'],
                'passengers'=>json_encode($BookingReference['TravelerInfo']['AirTraveler']),
                'voyage'=>json_encode($OriginDestinationOption),
                'salePrice'=>$flightOrder['FareInfo']['PriceTotal'],
                'taxation'=>$flightOrder['FareInfo']['TaxSubtotal'],
                'payableAmount'=>$flightOrder['FareInfo']['PriceTotal'],
                'create_time'=>time(),
            );
            if(!$OrderDb->create($data)){
                $this->error($OrderDb->getError());
            }
            $OrderDb->add();
          //     exit;
            redirect(U('/Iflight/flightPay/orderId/'.$OrderId));
        }
    }

    /*
     * 订单支付页
     */
    public function flightPay(){
        if(!I('orderId')){
            $this->error('请输入订单号');
        }
        $OrderDb=D('Order');
        $where['orderID']=I('orderId');
        $info=$OrderDb->where($where)->find();
        if(empty($info)){
            $this->error('订单号不存在');
        }
        $voyage=json_decode($info['voyage']);
        $count=count($voyage);
        $data['type']=$count==1?"单程":"往返";
        $data['DepartureCityName']=$voyage[0]->DepartureCityName;
        $data['ArrivalCityName']=$voyage[0]->ArrivalCityName;
        $data['DepartureDate']=$voyage[0]->DepartureDate;
        $data['PriceTotal']=$info['payableAmount'];
        $data['orderId']=I('orderId');

        $pay=array(
            'order_pay_id'=>$data['orderId'],
            'total_price'=>$data['PriceTotal'],
        );
        session('pay_auth',$pay);
        $this->data=$data;
        $this->title="支付订单";
        $this->display();
    }


    public function flightPaySucceed(){
        $this->title="支付成功";
        $this->display();
    }
}
