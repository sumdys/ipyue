﻿// JavaScript Document
//省份数据
var mp = ["安徽省","北京市","福建省","甘肃省","广东省","广西省","贵州省","海南省","河北省","河南省","黑龙江省","湖北省","湖南省","吉林省","江苏省","江西省","辽宁省","内蒙古自治区","宁夏回族自治区","青海省","山东省","山西省","陕西省","上海市","四川省","天津市","西藏自治区","新疆维吾尔自治区","云南省","浙江省","重庆市"];
//城市数据
var mc = [
			  ["安庆","蚌埠","巢湖","池州","滁州","阜阳","合肥","淮北","淮南","黄山","六安","马鞍山","宿州","铜陵","芜湖","宣城","亳州"],
			  ["北京市","海淀区","朝阳区","丰台区","东城区","西城区","宣武区","崇文区","石景山区","门头沟区","房山区","通州区","顺义区","昌平区","大兴区","怀柔区","平谷区"],
			  ["福州","龙岩","南平","宁德","莆田","泉州","三明","厦门","漳州"],
			  ["白银","定西","甘南藏族自治州","嘉峪关","金昌","酒泉","兰州","临夏回族自治州","陇南","平凉","庆阳","天水","武威","张掖"],
			  ["潮州","东莞","佛山","广州","河源","惠州","江门","揭阳","茂名","梅州","清远","汕头","汕尾","韶关","深圳","阳江","云浮","湛江","肇庆","中山","珠海"],
			  ["百色","北海","崇左","防城港","桂林","贵港","河池","贺州","来宾","柳州","南宁","钦州","梧州","玉林"],
			  ["安顺","毕节","贵阳","六盘水","黔东南苗族侗族自治州","黔南布依族苗族自治州","黔西南布依族苗族自治州","铜仁","遵义"],
			  ["白沙黎族自治县","保亭黎族苗族自治县","昌江黎族自治县","澄迈县","定安县","东方","海口","乐东黎族自治县","临高县","陵水黎族自治县","琼海","琼中黎族苗族自治县","三亚","屯昌县","万宁","文昌","五指山","儋州"],
			  ["保定","沧州","承德","邯郸","衡水","廊坊","秦皇岛","石家庄","唐山","邢台","张家口"],
			  ["安阳","鹤壁","济源","焦作","开封","洛阳","南阳","平顶山","三门峡","商丘","新乡","信阳","许昌","郑州","周口","驻马店","漯河","濮阳"],
			  ["大庆","大兴安岭","哈尔滨","鹤岗","黑河","鸡西","佳木斯","牡丹江","七台河","齐齐哈尔","双鸭山","绥化","伊春"],
			  ["鄂州","恩施土家族苗族自治州","黄冈","黄石","荆门","荆州","潜江","神农架林区","十堰","随州","天门","武汉","仙桃","咸宁","襄樊","孝感","宜昌"],
			  ["常德","长沙","郴州","衡阳","怀化","娄底","邵阳","湘潭","湘西土家族苗族自治州","益阳","永州","岳阳","张家界","株洲"],
			  ["白城","白山","长春","吉林","辽源","四平","松原","通化","延边朝鲜族自治州"],
			  ["常州","淮安","连云港","南京","南通","苏州","宿迁","泰州","无锡","徐州","盐城","扬州","镇江"],
			  ["抚州","赣州","吉安","景德镇","九江","南昌","萍乡","上饶","新余","宜春","鹰潭"],
			  ["鞍山","本溪","朝阳","大连","丹东","抚顺","阜新","葫芦岛","锦州","辽阳","盘锦","沈阳","铁岭","营口"],
			  ["阿拉善盟","巴彦淖尔盟","包头","赤峰","鄂尔多斯","呼和浩特","呼伦贝尔","通辽","乌海","乌兰察布盟","锡林郭勒盟","兴安盟"],
			  ["固原","石嘴山","吴忠","银川"],
			  ["果洛藏族自治州","海北藏族自治州","海东","海南藏族自治州","海西蒙古族藏族自治州","黄南藏族自治州","西宁","玉树藏族自治州"],
			  ["滨州","德州","东营","菏泽","济南","济宁","莱芜","聊城","临沂","青岛","日照","泰安","威海","潍坊","烟台","枣庄","淄博"],
			  ["长治","大同","晋城","晋中","临汾","吕梁","朔州","太原","忻州","阳泉","运城"],
			  ["安康","宝鸡","汉中","商洛","铜川","渭南","西安","咸阳","延安","榆林"],
			  ["上海"],
			  ["阿坝藏族羌族自治州","巴中","成都","达州","德阳","甘孜藏族自治州","广安","广元","乐山","凉山彝族自治州","眉山","绵阳","南充","内江","攀枝花","遂宁","雅安","宜宾","资阳","自贡","泸州"],
			  ["天津"],
			  ["阿里","昌都","拉萨","林芝","那曲","日喀则","山南"],
			  ["阿克苏","阿拉尔","巴音郭楞蒙古自治州","博尔塔拉蒙古自治州","昌吉回族自治州","哈密","和田","喀什","克拉玛依","克孜勒苏柯尔克孜自治州","石河子","图木舒克","吐鲁番","乌鲁木齐","五家渠","伊犁哈萨克自治州"],
			  ["保山","楚雄彝族自治州","大理白族自治州","德宏傣族景颇族自治州","迪庆藏族自治州","红河哈尼族彝族自治州","昆明","丽江","临沧","怒江傈傈族自治州","曲靖","思茅","文山壮族苗族自治州","西双版纳傣族自治州","玉溪","昭通"],
			  ["杭州","湖州","嘉兴","金华","丽水","宁波","绍兴","台州","温州","舟山","衢州"],
			  ["重庆"]
		  ];

//区县数据

var mh = [
		  	  [
				   ["安庆市","怀宁县","潜山县","宿松县","太湖县","桐城市","望江县","岳西县","枞阳县"],
				   ["蚌埠市","固镇县","怀远县","五河县"],
				   ["巢湖市","含山县","和县","庐江县","无为县"],
				   ["池州市","东至县","青阳县","石台县"],
				   ["滁州市","定远县","凤阳县","来安县","明光市","全椒县","天长市"],
				   ["阜南县","阜阳市","界首市","临泉县","太和县","颖上县"],
				   ["长丰县","肥东县","肥西县"],
				   ["淮北市","濉溪县"],
				   ["凤台县","淮南市"],
				   ["黄山市","祁门县","休宁县","歙县","黟县"],
				   ["霍邱县","霍山县","金寨县","六安市","寿县","舒城县"],
				   ["当涂县","马鞍山市"],
				   ["灵璧县","宿州市","萧县","泗县","砀山县"],
				   ["铜陵市","铜陵县"],
				   ["繁昌县","南陵县","芜湖市","芜湖县"],
				   ["广德县","绩溪县","郎溪县","宁国市","宣城市","泾县","旌德县"],
				   ["利辛县","蒙城县","涡阳县","亳州市"]
			   ],
			  
			  [
			   		["东城区","西城区","海淀区","朝阳区","丰台区","石景山区","通州区","顺义区","房山区","大兴区","昌平区","怀柔区","平谷区","门头沟区","密云县","延庆县"]
			   ],
			  
			  [
			   		["长乐市","福清市","福州市","连江县","罗源县","闽侯县","闽清县","平潭县","永泰县"],
					["长汀县","连城县","龙岩市","上杭县","武平县","永定县","漳平市"],
					["光泽县","建阳市","建瓯市","南平市","浦城县","邵武市","顺昌县","松溪县","武夷山市","政和县"],
					["福安市","福鼎市","古田县","宁德市","屏南县","寿宁县","霞浦县","周宁县","柘荣县"],
					["莆田市","仙游县"],
					["安溪县","德化县","惠安县","金门县","晋江市","南安市","泉州市","石狮市","永春县"],
					["大田县","建宁县","将乐县","明溪县","宁化县","清流县","三明市","沙县","泰宁县","永安市","尤溪县"],
					["厦门市"],
					["长泰县","东山县","华安县","龙海市","南靖县","平和县","云霄县","漳浦县","漳州市","诏安县"]
				],
			  
			  [
			   		["白银市","会宁县","景泰县","靖远县"],
					["定西县","临洮县","陇西县","通渭县","渭源县","漳县","岷县"],
					["迭部县","合作市","临潭县","碌曲县","玛曲县","夏河县","舟曲县","卓尼县"],
					["嘉峪关市"],
					["金昌市","永昌县"],
					["阿克塞哈萨克族自治县","安西县","敦煌市","金塔县","酒泉市","肃北蒙古族自治县","玉门市"],
					["皋兰县","兰州市","永登县","榆中县"],
					["东乡族自治县","广河县","和政县","积石山保安族东乡族撒拉族自治县","康乐县","临夏市","临夏县","永靖县"],
					["成县","徽县","康县","礼县","两当县","文县","武都县","西和县","宕昌县"],
					["崇信县","华亭县","静宁县","灵台县","平凉市","庄浪县","泾川县"],
					["合水县","华池县","环县","宁县","庆城县","庆阳市","镇原县","正宁县"],
					["甘谷县","秦安县","清水县","天水市","武山县","张家川回族自治县"],
					["古浪县","民勤县","天祝藏族自治县","武威市"],
					["高台县","临泽县","民乐县","山丹县","肃南裕固族自治县","张掖市"]
				],
			  
			  [
			   		["潮安县","潮州市","饶平县"],
					["东莞市"],
					["佛山市"],
					["从化市","广州市","增城市"],
					["东源县","和平县","河源市","连平县","龙川县","紫金县"],
					["博罗县","惠东县","惠阳市","惠州市","龙门县"],
					["恩平市","鹤山市","江门市","开平市","台山市"],
					["惠来县","揭东县","揭西县","揭阳市","普宁市"],
					["电白县","高州市","化州市","茂名市","信宜市"],
					["大埔县","丰顺县","蕉岭县","梅县","梅州市","平远县","五华县","兴宁市"],
					["佛冈县","连南瑶族自治县","连山壮族瑶族自治县","连州市","清新县","清远市","阳山县","英德市"],
					["潮阳市","澄海市","南澳县","汕头市"],
					["海丰县","陆丰市","陆河县","汕尾市"],
					["乐昌市","南雄市","曲江县","仁化县","乳源瑶族自治县","韶关市","始兴县","翁源县","新丰县"],
					["深圳市"],
					["阳春市","阳东县","阳江市","阳西县"],
					["罗定市","新兴县","郁南县","云安县","云浮市"],
					["雷州市","廉江市","遂溪县","吴川市","徐闻县","湛江市"],
					["德庆县","封开县","高要市","广宁县","怀集县","四会市","肇庆市"],
					["中山市"],
					["珠海市"]
				],
			  [
			   	   	["百色市","德保县","靖西县","乐业县","凌云县","隆林各族自治县","那坡县","平果县","田东县","田林县","田阳县","西林县"],
					["北海市","合浦县"],
					["崇左市","大新县","扶绥县","龙州县","宁明县","凭祥市","天等县"],
					["东兴市","防城港市","上思县"],
					["恭城瑶族自治县","灌阳县","桂林市","荔浦县","临桂县","灵川县","龙胜各族自治县","平乐县","全州县","兴安县","阳朔县","永福县","资源县"],
					["桂平市","贵港市","平南县"],
					["巴马瑶族自治县","大化瑶族自治县","东兰县","都安瑶族自治县","凤山县","河池市","环江毛南族自治县","罗城仡佬族自治县","南丹县","天峨县","宜州市"],
					["富川瑶族自治县","贺州市","昭平县","钟山县"],
					["合山市","金秀瑶族自治县","来宾市","武宣县","象州县","忻城县"],
					["柳城县","柳江县","柳州市","鹿寨县","融安县","融水苗族自治县","三江侗族自治县"],
					["宾阳县","横县","隆安县","马山县","南宁市","上林县","武鸣县","邕宁县"],
					["灵山县","浦北县","钦州市"],
					["苍梧县","蒙山县","藤县","梧州市","岑溪市"],
					["北流市","博白县","陆川县","容县","兴业县","玉林市"]
				],
			  [
			   		["安顺市","关岭布依族苗族自治县","平坝县","普定县","镇宁布依族苗族自治县","紫云苗族布依族自治县"],
					["毕节市","大方县","赫章县","金沙县","纳雍县","黔西县","威宁彝族回族苗族自治县","织金县"],
					["贵阳市","开阳县","清镇市","息烽县","修文县"],
					["六盘水市","六枝特区","盘县","水城县"],
					["从江县","丹寨县","黄平县","剑河县","锦屏县","凯里市","雷山县","黎平县","麻江县","三穗县","施秉县","台江县","天柱县","镇远县","岑巩县","榕江县"]
					["长顺县","都匀市","独山县","福泉市","贵定县","惠水县","荔波县","龙里县","罗甸县","平塘县","三都水族自治县","瓮安县"],
					["安龙县","册亨县","普安县","晴隆县","望谟县","兴仁县","兴义市","贞丰县"],
					["德江县","江口县","石阡县","思南县","松桃苗族自治县","铜仁市","万山特区","沿河土家族自治县","印江土家族苗族自治县","玉屏侗族自治县"],
					["赤水市","道真仡佬族苗族自治县","凤冈县","仁怀市","绥阳县","桐梓县","务川仡佬族苗族自治县","习水县","余庆县","正安县","遵义市","遵义县","湄潭县"]
				],
			  [
			   		["白沙黎族自治县"],
					["保亭黎族苗族自治县"],
					["昌江黎族自治县"],
					["澄迈县"],
					["定安县"],
					["东方市"],
					["海口市"],
					["乐东黎族自治县"],
					["临高县"],
					["陵水黎族自治县"],
					["琼海市"],
					["琼中黎族苗族自治县"],
					["三亚市"],
					["屯昌县"],
					["万宁市"],
					["文昌市"],
					["五指山市"],
					["儋州市"]
				],
			  [
					["安国市","安新县","保定市","博野县","定兴县","定州市","阜平县","高碑店市","高阳县","满城县","清苑县","曲阳县","容城县","顺平县","唐县","望都县","雄县","徐水县","易县","涞水县","涞源县","涿州市","蠡县"],
					["泊头市","沧县","沧州市","东光县","海兴县","河间市","黄骅市","孟村回族自治县","南皮县","青县","任丘市","肃宁县","吴桥县","献县","盐山县"],
					["承德市","承德县","丰宁满族自治县","宽城满族自治县","隆化县","滦平县","平泉县","围场满族蒙古族自治县","兴隆县"],
					["成安县","磁县","大名县","肥乡县","馆陶县","广平县","邯郸市","邯郸县","鸡泽县","临漳县","邱县","曲周县","涉县","魏县","武安市","永年县"],
					["安平县","阜城县","故城县","衡水市","冀州市","景县","饶阳县","深州市","武强县","武邑县","枣强县"],
					["霸州市","大厂回族自治县","大城县","固安县","廊坊市","三河市","文安县","香河县","永清县"],
					["昌黎县","抚宁县","卢龙县","秦皇岛市","青龙满族自治县"],
					["高邑县","晋州市","井陉县","灵寿县","鹿泉市","平山县","深泽县","石家庄市","无极县","辛集市","新乐市","行唐县","元氏县","赞皇县","赵县","正定县","藁城市","栾城县"],
					["乐亭县","滦南县","滦县","迁安市","迁西县","唐海县","唐山市","玉田县","遵化市"],
					["柏乡县","广宗县","巨鹿县","临城县","临西县","隆尧县","南宫市","南和县","内丘县","宁晋县","平乡县","清河县","任县","沙河市","威县","新河县","邢台市","邢台县"],
					["赤城县","崇礼县","沽源县","怀安县","怀来县","康保县","尚义县","万全县","蔚县","宣化县","阳原县","张北县","张家口市","涿鹿县"]
				],
			  [
			   		["安阳市","安阳县","滑县","林州市","内黄县","汤阴县"],
					["","鹤壁市","浚县","淇县"],
					["济源市"],
					["博爱县","焦作市","孟州市","沁阳市","温县","武陟县","修武县"],
					["开封市","开封县","兰考县","通许县","尉氏县","杞县"],
					["洛宁县","洛阳市","孟津县","汝阳县","新安县","伊川县","宜阳县","偃师市","嵩县","栾川县"],
					["邓州市","方城县","南阳市","南召县","内乡县","社旗县","唐河县","桐柏县","西峡县","新野县","镇平县","淅川县"],
					["宝丰县","鲁山县","平顶山市","汝州市","舞钢市","叶县","郏县"],
					["灵宝市","卢氏县","三门峡市","陕县","义马市","渑池县"],
					["民权县","宁陵县","商丘市","夏邑县","永城市","虞城县","柘城县","睢县"],
					["长垣县","封丘县","辉县市","获嘉县","卫辉市","新乡市","新乡县","延津县","原阳县"],
					["固始县","光山县","淮滨县","罗山县","商城县","息县","新县","信阳市","潢川县"],
					["长葛市","襄城县","许昌市","许昌县","禹州市","鄢陵县"],
					["登封市","巩义市","新密市","新郑市","郑州市","中牟县","荥阳市"],
					["郸城县","扶沟县","淮阳县","鹿邑县","商水县","沈丘县","太康县","西华县","项城市","周口市"],
					["泌阳县","平舆县","确山县","汝南县","上蔡县","遂平县","西平县","新蔡县","正阳县","驻马店市"],
					["临颍县","舞阳县","郾城县","漯河市"],
					["范县","南乐县","清丰县","台前县","濮阳市","濮阳县"]
				],
			  [
			   		["大庆市","杜尔伯特蒙古族自治县","林甸县","肇源县","肇州县"],
					["呼玛县","漠河县","塔河县"],
					["阿城市","巴彦县","宾县","方正县","哈尔滨市","呼兰县","木兰县","尚志市","双城市","通河县","五常市","延寿县","依兰县"]
					["鹤岗市","萝北县","绥滨县"],
					["北安市","黑河市","嫩江县","孙吴县","五大连池市","逊克县"],
					["虎林市","鸡东县","鸡西市","密山市"],
					["抚远县","富锦市","佳木斯市","汤原县","同江市","桦川县","桦南县"],
					["东宁县","海林市","林口县","牡丹江市","穆棱市","宁安市","绥芬河市"],
					["勃利县","七台河市"],
					["拜泉县","富裕县","甘南县","克东县","克山县","龙江县","齐齐哈尔市","泰来县","依安县","讷河市"],
					["宝清县","集贤县","饶河县","双鸭山市","友谊县"],
					["安达市","海伦市","兰西县","明水县","青冈县","庆安县","绥化市","绥棱县","望奎县","肇东市"],
					["嘉荫县","铁力市","伊春市"]
				],
			  [
			   		["鄂州市"],
					["巴东县","恩施市","鹤峰县","建始县","来凤县","利川市","咸丰县","宣恩县"],
					["红安县","黄冈市","黄梅县","罗田县","麻城市","团风县","武穴市","英山县","蕲春县","浠水县"],
					["大冶市","黄石市","阳新县"],
					["荆门市","京山县","沙洋县","钟祥市"],
					["公安县","洪湖市","监利县","江陵县","荆州市","石首市","松滋市"],
					["潜江市"],
					["神农架林区"],
					["丹江口市","房县","十堰市","郧西县","郧县","竹山县","竹溪县"],
					["广水市","随州市"],
					["天门市"],
					["武汉市"],
					["仙桃市"],
					["赤壁市","崇阳县","嘉鱼县","通城县","通山县","咸宁市"],
					["保康县","谷城县","老河口市","南漳县","襄樊市","宜城市","枣阳市"],
					["安陆市","大悟县","汉川市","孝昌县","孝感市","应城市","云梦县"],
					["长阳土家族自治县","当阳市","五峰土家族自治县","兴山县","宜昌市","宜都市","远安县","枝江市","秭归县"]
				],
			  [
			   		["安乡县","常德市","汉寿县","津市市","临澧县","石门县","桃源县","澧县"],
					["长沙市","长沙县","宁乡县","望城县","浏阳市"],
					["安仁县","郴州市","桂东县","桂阳县","嘉禾县","临武县","汝城县","宜章县","永兴县","资兴市"],
					["常宁市","衡东县","衡南县","衡山县","衡阳市","衡阳县","祁东县","耒阳市"],
					["辰溪县","洪江市","怀化市","会同县","靖州苗族侗族自治县","麻阳苗族自治县","通道侗族自治县","新晃侗族自治县","中方县","芷江侗族自治县","沅陵县","溆浦县"],
					["冷水江市","涟源市","娄底市","双峰县","新化县"],
					["城步苗族自治县","洞口县","隆回县","邵东县","邵阳市","邵阳县","绥宁县","武冈市","新宁县","新邵县"],
					["韶山市","湘潭市","湘潭县","湘乡市"],
					["保靖县","凤凰县","古丈县","花垣县","吉首市","龙山县","永顺县","泸溪县"],
					["安化县","南县","桃江县","益阳市","沅江市"],
					["道县","东安县","江华瑶族自治县","江永县","蓝山县","宁远县","祁阳县","双牌县","新田县","永州市"],
					["华容县","临湘市","平江县","湘阴县","岳阳市","岳阳县","汨罗市"],
					["慈利县","桑植县","张家界市"],
					["茶陵县","炎陵县","株洲市","株洲县","攸县","醴陵市"]
				],
			  [
			   		["白城市","大安市","通榆县","镇赉县","洮南市"],
					["白山市","长白朝鲜族自治县","抚松县","江源县","靖宇县","临江市"],
					["长春市","德惠市","九台市","农安县","榆树市"],
					["吉林市","磐石市","舒兰市","永吉县","桦甸市","蛟河市"],
					["东丰县","东辽县","辽源市"],
					["公主岭市","梨树县","双辽市","四平市","伊通满族自治县"],
					["长岭县","扶余县","乾安县","前郭尔罗斯蒙古族自治县","松原市"],
					["辉南县","集安市","柳河县","梅河口市","通化市","通化县"],
					["安图县","敦化市","和龙市","龙井市","图们市","汪清县","延吉市","珲春市"]
				],
			  [
			   		["常州市","金坛市","溧阳市"],
					["洪泽县","淮安市","金湖县","涟水县","盱眙县"],
					["东海县","赣榆县","灌南县","灌云县","连云港市"],
					["高淳县","南京市","溧水县"],
					["海安县","海门市","南通市","启东市","如东县","如皋市","通州市"],
					["常熟市","昆山市","苏州市","太仓市","吴江市","张家港市"],
					["宿迁市","宿豫县","沭阳县","泗洪县","泗阳县"],
					["姜堰市","靖江市","泰兴市","泰州市","兴化市"],
					["江阴市","无锡市","宜兴市"],
					["丰县","沛县","铜山县","新沂市","徐州市","邳州市","睢宁县"],
					["滨海县","大丰市","东台市","阜宁县","建湖县","射阳县","响水县","盐城市","盐都县"],
					["宝应县","高邮市","江都市","扬州市","仪征市"],
					["丹阳市","句容市","扬中市","镇江市"]
				],
			  [
			   		["崇仁县","东乡县","抚州市","广昌县","金溪县","乐安县","黎川县","南城县","南丰县","宜黄县","资溪县"],
					["安远县","崇义县","大余县","定南县","赣县","赣州市","会昌县","龙南县","南康市","宁都县","全南县","瑞金市","上犹县","石城县","信丰县","兴国县","寻乌县","于都县"],
					["安福县","吉安市","吉安县","吉水县","井冈山市","遂川县","泰和县","万安县","峡江县","新干县","永丰县","永新县"],
					["浮梁县","景德镇市","乐平市"],
					["德安县","都昌县","湖口县","九江市","九江县","彭泽县","瑞昌市","武宁县","星子县","修水县","永修县"],
					["安义县","进贤县","南昌市","南昌县","新建县"],
					["莲花县","芦溪县","萍乡市","上栗县"],
					["波阳县","德兴市","广丰县","横峰县","铅山县","上饶市","上饶县","万年县","余干县","玉山县","弋阳县","婺源县"],
					["分宜县","新余市"],
					["丰城市","奉新县","高安市","靖安县","上高县","铜鼓县","万载县","宜春市","宜丰县","樟树市"],
					["贵溪市","鹰潭市","余江县"]
				],
			  [
			   		["辖铁东","铁西","立山","千山","海城市","台安县","岫岩满族自治县"],
					["平山区","溪湖区","明山区","南芬区","本溪满族自治县","桓仁满族自治县"],
					["龙城区","双塔区","北票市","朝阳县","建平县","喀喇沁左翼蒙古族自治县","凌源市"],
					["西岗区","中山区","沙河口区","甘井子区","旅顺口区","金州区","瓦房店市","普兰店市","庄河市","长海县","大连金州新区","大连普湾新区","大连保税区","大连出口加工区","大连大窑湾保税港区","大连高新技术产业园区","大连经济技术开发区","大连长兴岛经济技术开发区","大连金石滩国家旅游度假区","大连花园口经济区","大连长山群岛旅游避暑度假区"],
					["振兴区","元宝区","振安区","新城区","国门湾","东港市","凤城市","宽甸满族自治县"],
					["顺城区","新抚区","东洲区","望花区","清原满族自治县","新宾满族自治县"],
					["海州区","新邱区","太平区","清河门区","细河区","阜新蒙古族自治县","彰武县"],
					["龙港区","连山区","南票区","建昌县","绥中县","兴城市"],
					["凌河区","古塔区","太和区","松山新区","锦州经济技术开发区","北镇市","北宁市","黑山县","凌海市","义县"],
					["白塔区","文圣区","宏伟区","弓长岭区","太子河区","灯塔市","辽阳县"],
					["双台子区","兴隆台区","大洼县","盘山县"],
					["沈河区","和平区","大东区","皇姑区","铁西区","苏家屯区","东陵区","沈北新区","于洪区","浑南新区","康平县","辽中县","法库县","新民市"],
					["银州区","清河区","昌图县","调兵山市","开原市","铁岭县","西丰县"],
					["站前区","西市区","老边区","大石桥市","盖州市"]
				],
			  [
			   		["阿拉善右旗","阿拉善左旗","额济纳旗"],
					["杭锦后旗","临河市","乌拉特后旗","乌拉特前旗","乌拉特中旗","五原县","磴口县"],
					["包头市","达尔罕茂明安联合旗","固阳县","土默特右旗"],
					["阿鲁科尔沁旗","敖汉旗","巴林右旗","巴林左旗","赤峰市","喀喇沁旗","克什克腾旗","林西县","宁城县","翁牛特旗"],
					["达拉特旗","鄂尔多斯市","鄂托克旗","鄂托克前旗","杭锦旗","乌审旗","伊金霍洛旗","准格尔旗"],
					["和林格尔县","呼和浩特市","清水河县","土默特左旗","托克托县","武川县"],
					["阿荣旗","陈巴尔虎旗","额尔古纳市","鄂伦春自治旗","鄂温克族自治旗","根河市","呼伦贝尔市","满洲里市","莫力达瓦达斡尔族自治旗","新巴尔虎右旗","新巴尔虎左旗","牙克石市","扎兰屯市"],
					["霍林郭勒市","开鲁县","科尔沁左翼后旗","科尔沁左翼中旗","库伦旗","奈曼旗","通辽市","扎鲁特旗"],
					["乌海市"],
					["察哈尔右翼后旗","察哈尔右翼前旗","察哈尔右翼中旗","丰镇市","化德县","集宁市","凉城县","商都县","四子王旗","兴和县","卓资县"],
					["阿巴嘎旗","东乌珠穆沁旗","多伦县","二连浩特市","苏尼特右旗","苏尼特左旗","太仆寺旗","西乌珠穆沁旗","锡林浩特市","镶黄旗","正蓝旗","正镶白旗"],
					["阿尔山市","科尔沁右翼前旗","科尔沁右翼中旗","突泉县","乌兰浩特市","扎赉特旗"]
				],
			  [
			   		["固原市","海原县","隆德县","彭阳县","西吉县","泾源县"],
					["惠农县","平罗县","石嘴山市","陶乐县"],
					["青铜峡市","同心县","吴忠市","盐池县","中宁县","中卫县"],
					["贺兰县","灵武市","银川市","永宁县"]
				],
			  [
			   		["班玛县","达日县","甘德县","久治县","玛多县","玛沁县"],
					["刚察县","海晏县","门源回族自治县","祁连县"],
					["互助土族自治县","化隆回族自治县","乐都县","民和回族土族自治县","平安县","循化撒拉族自治县"],
					["共和县","贵德县","贵南县","同德县","兴海县"],
					["德令哈市","都兰县","格尔木市","天峻县","乌兰县"],
					["河南蒙古族自治县","尖扎县","同仁县","泽库县"],
					["大通回族土族自治县","西宁市","湟源县","湟中县"],
					["称多县","囊谦县","曲麻莱县","玉树县","杂多县","治多县"]
				],
			  [
			   		["滨州市","博兴县","惠民县","无棣县","阳信县","沾化县","邹平县"],
					["德州市","乐陵市","临邑县","陵县","宁津县","平原县","齐河县","庆云县","武城县","夏津县","禹城市"],
					["东营市","广饶县","垦利县","利津县"],
					["曹县","成武县","单县","定陶县","东明县","菏泽市","巨野县","郓城县","鄄城县"],
					["济南市","济阳县","平阴县","商河县","章丘市"],
					["济宁市","嘉祥县","金乡县","梁山县","曲阜市","微山县","鱼台县","邹城市","兖州市","汶上县","泗水县"],
					["莱芜市"],
					["东阿县","高唐县","冠县","聊城市","临清市","阳谷县","茌平县","莘县"],
					["苍山县","费县","临沂市","临沭县","蒙阴县","平邑县","沂南县","沂水县","郯城县","莒南县"],
					["即墨市","胶南市","胶州市","莱西市","平度市","青岛市"],
					["日照市","五莲县","莒县"],
					["东平县","肥城市","宁阳县","泰安市","新泰市"],
					["荣成市","乳山市","威海市","文登市"],
					["安丘市","昌乐县","昌邑市","高密市","临朐县","青州市","寿光市","潍坊市","诸城市"],
					["长岛县","海阳市","莱阳市","莱州市","龙口市","蓬莱市","栖霞市","烟台市","招远市"],
					["枣庄市","滕州市"],
					["高青县","桓台县","沂源县","淄博市"]
				],
			  [
			   		["长治市","长治县","长子县","壶关县","黎城县","潞城市","平顺县","沁县","沁源县","屯留县","武乡县","襄垣县"],
					["大同市","大同县","广灵县","浑源县","灵丘县","天镇县","阳高县","左云县"],
					["高平市","晋城市","陵川县","沁水县","阳城县","泽州县"],
					["和顺县","介休市","晋中市","灵石县","平遥县","祁县","寿阳县","太谷县","昔阳县","榆社县","左权县"],
					["安泽县","大宁县","汾西县","浮山县","古县","洪洞县","侯马市","霍州市","吉县","临汾市","蒲县","曲沃县","襄汾县","乡宁县","翼城县","永和县","隰县"],
					["方山县","汾阳市","交城县","交口县","离石市","临县","柳林县","石楼县","文水县","孝义市","兴县","中阳县","岚县"],
					["怀仁县","山阴县","朔州市","应县","右玉县"],
					["古交市","娄烦县","清徐县","太原市","阳曲县"],
					["保德县","代县","定襄县","繁峙县","河曲县","静乐县","宁武县","偏关县","神池县","五台县","五寨县","忻州市","原平市","岢岚县"],
					["平定县","阳泉市","盂县"],
					["河津市","临猗县","平陆县","万荣县","闻喜县","夏县","新绛县","永济市","垣曲县","运城市","芮城县","绛县","稷山县"]
				],
			  [
			   		["安康市","白河县","汉阴县","宁陕县","平利县","石泉县","旬阳县","镇坪县","紫阳县","岚皋县"],
					["宝鸡市","宝鸡县","凤县","凤翔县","扶风县","陇县","眉县","千阳县","太白县","岐山县","麟游县"],
					["城固县","佛坪县","汉中市","留坝县","略阳县","勉县","南郑县","宁强县","西乡县","洋县","镇巴县"],
					["丹凤县","洛南县","山阳县","商洛市","商南县","镇安县","柞水县"],
					["铜川市","宜君县"],
					["白水县","澄城县","大荔县","富平县","韩城市","合阳县","华县","华阴市","蒲城县","渭南市","潼关县"],
					["高陵县","户县","蓝田县","西安市","周至县"],
					["彬县","长武县","淳化县","礼泉县","乾县","三原县","武功县","咸阳市","兴平市","旬邑县","永寿县","泾阳县"],
					["安塞县","富县","甘泉县","黄陵县","黄龙县","洛川县","吴旗县","延安市","延长县","延川县","宜川县","志丹县","子长县"],
					["定边县","府谷县","横山县","佳县","靖边县","米脂县","清涧县","神木县","绥德县","吴堡县","榆林市","子洲县"]
				],
			  [
			   		["崇明县","上海市"]
				],
			  [
			   		["阿坝县","黑水县","红原县","金川县","九寨沟县","理县","马尔康县","茂县","壤塘县","若尔盖县","松潘县","小金县","汶川县"],
					["巴中市","南江县","平昌县","通江县"],
					["成都市","崇州市","大邑县","都江堰市","金堂县","彭州市","蒲江县","双流县","新津县","邛崃市","郫县"],
					["达县","达州市","大竹县","开江县","渠县","万源市","宣汉县"],
					["德阳市","广汉市","罗江县","绵竹市","什邡市","中江县"],
					["巴塘县","白玉县","丹巴县","稻城县","道孚县","德格县","得荣县","甘孜县","九龙县","康定县","理塘县","炉霍县","色达县","石渠县","乡城县","新龙县","雅江县","泸定县"],
					["广安市","华蓥市","邻水县","武胜县","岳池县"],
					["苍溪县","广元市","剑阁县","青川县","旺苍县"],
					["峨边彝族自治县","峨眉山市","夹江县","井研县","乐山市","马边彝族自治县","沐川县","犍为县"],
					["布拖县","德昌县","甘洛县","会东县","会理县","金阳县","雷波县","美姑县","冕宁县","木里藏族自治县","宁南县","普格县","西昌市","喜德县","盐源县","越西县","昭觉县"],
					["丹棱县","洪雅县","眉山市","彭山县","青神县","仁寿县"],
					["安县","北川县","江油市","绵阳市","平武县","三台县","盐亭县","梓潼县"],
					["南部县","南充市","蓬安县","西充县","仪陇县","营山县","阆中市"],
					["隆昌县","内江市","威远县","资中县"],
					["米易县","攀枝花市","盐边县"],
					["大英县","蓬溪县","射洪县","遂宁市"],
					["宝兴县","汉源县","芦山县","名山县","石棉县","天全县","雅安市","荥经县"],
					["长宁县","高县","江安县","南溪县","屏山县","兴文县","宜宾市","宜宾县","珙县","筠连县"],
					["安岳县","简阳市","乐至县","资阳市"],
					["富顺县","荣县","自贡市"],
					["古蔺县","合江县","叙永县","泸县","泸州市"]
				],
			  [
			   		["蓟县","静海县","宁河县","天津市"]
			   ],
			  [
			   		["措勤县","噶尔县","改则县","革吉县","普兰县","日土县","札达县"],
					["八宿县","边坝县","察雅县","昌都县","丁青县","贡觉县","江达县","类乌齐县","洛隆县","芒康县","左贡县"],
					["达孜县","当雄县","堆龙德庆县","拉萨市","林周县","墨竹工卡县","尼木县","曲水县"],
					["波密县","察隅县","工布江达县","朗县","林芝县","米林县","墨脱县"],
					["安多县","巴青县","班戈县","比如县","嘉黎县","那曲县","尼玛县","聂荣县","申扎县","索县"],
					["昂仁县","白朗县","定结县","定日县","岗巴县","吉隆县","江孜县","康马县","拉孜县","南木林县","聂拉木县","仁布县","日喀则市","萨嘎县","萨迦县","谢通门县","亚东县","仲巴县"],
					["措美县","错那县","贡嘎县","加查县","浪卡子县","隆子县","洛扎县","乃东县","琼结县","曲松县","桑日县","扎囊县"]
				],
			  [
			   		["阿克苏市","阿瓦提县","拜城县","柯坪县","库车县","沙雅县","温宿县","乌什县","新和县"],
					["阿拉尔市"],
					["博湖县","和静县","和硕县","库尔勒市","轮台县","且末县","若羌县","尉犁县","焉耆回族自治县"],
					["博乐市","精河县","温泉县"],
					["昌吉市","阜康市","呼图壁县","吉木萨尔县","玛纳斯县","米泉市","木垒哈萨克自治县","奇台县"],
					["巴里坤哈萨克自治县","哈密市","伊吾县"],
					["策勒县","和田市","和田县","洛浦县","民丰县","墨玉县","皮山县","于田县"],
					["巴楚县","喀什市","麦盖提县","莎车县","疏附县","疏勒县","塔什库尔干塔吉克自治县","叶城县","英吉沙县","岳普湖县","泽普县","伽师县"],
					["克拉玛依市"],
					["阿合奇县","阿克陶县","阿图什市","乌恰县"],
					["石河子市"],
					["图木舒克市"],
					["吐鲁番市","托克逊县","鄯善县"],
					["乌鲁木齐市","乌鲁木齐县"],
					["五家渠市"],
					["阿勒泰市","布尔津县","察布查尔锡伯自治县","额敏县","福海县","富蕴县","巩留县","哈巴河县","和布克赛尔蒙古自治县","霍城县","吉木乃县","奎屯市","尼勒克县","青河县","沙湾县","塔城市","特克斯县","托里县","乌苏市","新源县","伊宁市","伊宁县","裕民县","昭苏县"]
				],
			  [
			   		["保山市","昌宁县","龙陵县","施甸县","腾冲县"],
					["楚雄市","大姚县","禄丰县","牟定县","南华县","双柏县","武定县","姚安县","永仁县","元谋县"],
					["宾川县","大理市","洱源县","鹤庆县","剑川县","弥渡县","南涧彝族自治县","巍山彝族回族自治县","祥云县","漾濞彝族自治县","永平县","云龙县"],
					["梁河县","陇川县","潞西市","瑞丽市","盈江县"],
					["德钦县","维西傈僳族自治县","香格里拉县"],
					["个旧市","河口瑶族自治县","红河县","建水县","金平苗族瑶族傣族自治县","开远市","绿春县","蒙自县","弥勒县","屏边苗族自治县","石屏县","元阳县","泸西县"],
					["安宁市","呈贡县","富民县","晋宁县","昆明市","禄劝彝族苗族自治县","石林彝族自治县","寻甸回族自治县","宜良县","嵩明县"],
					["华坪县","丽江市","宁蒗彝族自治县","永胜县","玉龙纳西族自治县"],
					["沧源佤族自治县","凤庆县","耿马傣族佤族治县","临沧县","双江拉祜族佤族布朗族傣族自治县","永德县","云县","镇康县"],
					["福贡县","贡山独龙族怒族自治县","兰坪白族普米族自治县","泸水县"],
					["富源县","会泽县","陆良县","罗平县","马龙县","曲靖市","师宗县","宣威市","沾益县"],
					["江城哈尼族彝族自治县","景东彝族自治县","景谷彝族傣族自治县","澜沧拉祜族自治县","孟连傣族拉祜族佤族自治县","墨江哈尼族自治县","普洱哈尼族彝族自治县","思茅市","西盟佤族自治县","镇沅彝族哈尼族拉祜族自治县"],
					["富宁县","广南县","麻栗坡县","马关县","丘北县","文山县","西畴县","砚山县"],
					["景洪市","勐海县","勐腊县"],
					["澄江县","峨山彝族自治县","华宁县","江川县","通海县","新平彝族傣族自治县","易门县","玉溪市","元江哈尼族彝族傣族自治县"],
					["大关县","鲁甸县","巧家县","水富县","绥江县","威信县","盐津县","彝良县","永善县","昭通市","镇雄县"]
				],
			  [
			   		["淳安县","富阳市","杭州市","建德市","临安市","桐庐县"],
					["安吉县","长兴县","德清县","湖州市"],
					["海宁市","海盐县","嘉善县","嘉兴市","平湖市","桐乡市"],
					["东阳市","金华市","兰溪市","磐安县","浦江县","武义县","义乌市","永康市"],
					["景宁畲族自治县","丽水市","龙泉市","青田县","庆元县","松阳县","遂昌县","云和县","缙云县"],
					["慈溪市","奉化市","宁波市","宁海县","象山县","余姚市"],
					["上虞市","绍兴市","绍兴县","新昌县","诸暨市","嵊州市"],
					["临海市","三门县","台州市","天台县","温岭市","仙居县","玉环县"],
					["苍南县","洞头县","乐清市","平阳县","瑞安市","泰顺县","温州市","文成县","永嘉县"],
					["舟山市","岱山县","嵊泗县"],
					["常山县","江山市","开化县","龙游县","衢州市"]
				],
			 [
			   		["城口县","大足县","垫江县","丰都县","奉节县","合川市","江津市","开县","梁平县","南川市","彭水苗族土家族自治县","荣昌县","石柱土家族自治县","铜梁县","巫山县","巫溪县","武隆县","秀山土家族苗族自治县","永川市","酉阳土家族苗族自治县","云阳县","忠县","重庆市","潼南县","璧山县","綦江县"]
			   ]
		  ];















