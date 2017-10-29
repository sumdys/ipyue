<?php
/*
 * 扩展参数
 */

return array(
    'WEB_NAME'=>'品悦旅行网Ipyue.com',
    'WEB_DOMAIN'=>'www.jp.com',
	'WEB_KEYWORD'=>'旅游度假、国际旅行、独家定制、私人定制、定制旅行、自由行、境外游',
	'WEB_DESCRIPTION'=>'品悦旅行网是国内领先的提供北京、上海、广州、深圳、南京、重庆、武汉等多个出发城市，遍及全球100多个国家和地区的私人定制旅游产品预订、境外自由行产品预订的专业服务平台',

    //SMTP服务器设置
    'SMTP_SERVER'=>'smtp.exmail.qq.com',
    'SMTP_SERVERPORT'=>'25',
    'SMTP_USERMAIL'=>'',
    'SMTP_EMAILTO'=>'',
	'SMTP_TEST'=>'yin.pengfei@qq.com',
	'SMTP_FROM_NAME'=>'',
    'SMTP_USER'=>'',
    'SMTP_PASS'=>'',
	
	//手机短信
	'SMS_SN'=>'xyswfw',
	//454545
	'SMS_PWD'=>'py623835',
	'SMS_SIGN'=>'【品悦旅行网】',
	'SMS_TEST'=>'18673800250',



    //自由飞越 订单同步
    'SYNORDER_URL'=>'http://interface.trafree.com/portal',
    'SYNORDER_ID'=>'aishangfei',
    'SYNORDER_KEY'=>'XX',

    //支付
    //财付通
    'TENPAY_SPNAME'=>"XX",
    'TENPAY_PARTNER' => 'XX',
    'TENPAY_KEY' => 'XX',
    'TENPAY_RETURN_URL' => "http://www.ipyue.com/pay/payReturnUrl.php",
    'TENPAY_NOTIFY_URL' => "http://www.ipyue.com/pay/payNotifyUrl.php",

    //易宝支付
    'YEE_LOGNAME'=>'YeePay_EPOS.log', // 日志文件路径
    'YEE_P0_CMD'=>'EposSale',// 业务类型
    'YEE_P4_CUR'=>'CNY',// 交易币种
    'YEE_PR_NEEDRESPONSE'=>1,// 是否需要应答
    'YEE_P1_MERID'=>'XX',// 商户编号
    // 商户密钥,用于生成hmac(hmac的说明详见文档)key为测试
    'YEE_MERCHANTKEY'=>'XX',
    'YEE_ACTIONURL'=>'https://www.yeepay.com/app-merchant-proxy/command.action',// 地址


    //aliPay 支付宝
    'ALIPAY_SPNAME'=>"广州讯悦商务服务有限公司",
    'ALIPAY_PARTNER' => '2088911386906180',
    'ALIPAY_KEY' => 's5mxnjg38c7do4dopcekcd39ft6sngk0',
	'seller_email'=>'webservice@ipyue.com',//这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
	//	'ALIPAY_RETURN_URL' => "http://www.ipyue.com/Alipay/return_url.php",//这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
	'ALIPAY_RETURN_URL' => "http://www.ipyue.com/Member/pay/returnurl",

	//	'ALIPAY_NOTIFY_URL' => "http://www.ipyue.com/Alipay/notify_url.php", //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
	'ALIPAY_NOTIFY_URL' => "http://www.ipyue.com/Member/pay/notifyurl",
	'successpage'=>'http://www.ipyue.cn/Member/booking?status=process',     //支付成功跳转到的页面
	'errorpage'=>'http://www.ipyue.com/Member/booking?status=pending',       //支付失败跳转到的页面	


     //其它配置项
    'VERIFY_CODE'=>1,       //注册码开关
    'REG_REBATE'=>50,	//注册返利rebate
    'REG_COUPON'=>50, ///注册送现金劵
    'REG_POINTS'=>1000,   //注册送积分

    'INVITE_POINTS'=>500,   //邀请注册送积分

    'TEL'=>'400-888-8888',
		
	
);
?>
