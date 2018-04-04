<?php
/**
 * Project:              车险保费在线计算接口
 * File:                 PINGANKHYXYN_PC.class.php
 * Copyright:            2017 成都启点科技有限公司.
 * Author:               Cosmo
 * Version:              1.0.0
 * Description:
 *                       中国平安云南客户营销管理系统算价接口
 **/
class PINGANKHYXYN_PC
{
    //(必需)算价模板文件
    const formFile = 'Calculate.tpl';

    //(必需)保险公司
    const company  = 'PINGAN';

    //(必需)设置错误信息成员属性（默认值）
    private $error    = "";

    //(必需)算价器设置配置项
    private $setItems = array(
        'username' => '登录名',
        'password' => '密码',
        'fueltype' => '燃油类型(1/0)',
    );

    //链接地址
    private $_link = Array();

    //网址
    private $url = Array(
        "PTS" => Array(
            "DOMAIN" => "https://icore-pts.pingan.com.cn",
            "IPADDR" => "https://202.69.21.113:443",
        ),
        "NBS" => Array(
            "DOMAIN" => "https://icorepnbs.pingan.com.cn",
            "IPADDR" => "https://202.69.21.122:443",
        )
    );

    //链接
    private $uri = Array(
        "PTS" => Array(
            //登录验证
            "LOGIN"    => "/ebusiness/j_security_check",
            //来源
            "REFERER"  => "/ebusiness/login.jsp",
            //验证码
            "RANDCODE" => "/ebusiness/auto/rand-code-imgage.do",
            //获取用户登录信息
            "WRITER"   => "/ebusiness/auto/newness/toibcswriter.do?transmitId=apply",
        ),
        "NBS" => Array(
            //用户登录信息提交
            "TRANSFER"      => "/icore_pnbs/do/usermanage/systemTransfer",
            //客户信息检索
            "CUSTOMER"      => "/do/app/quotation/searchIndiCustomerInfo",
            //车辆查询(车辆识别号)
            "VINQUERY"      => "/icore_pnbs/do/app/quotation/autoModelCodeQuery",
            //车辆查询(车辆型号)
            "MODELQUERY"    => "/icore_pnbs/do/app/quotation/queryAutoModelType",
            //车辆折旧价查询
            "DEPRECIATION"  => "/icore_pnbs/do/app/calculate/defaultCalculate?_cflag=calculateStealRobInsuredAmount",
            //交管车辆查询验证码
            "DM_RAND_CODE"  => "/icore_pnbs/do/app/quotation/getDMVehicleInfo",
            //交管车辆查询
            "DMVEHICLEINFO" => "/icore_pnbs/do/app/quotation/queryDMVehicleInfoConfirm",
            //申请报价
            "APPLY"         => "/icore_pnbs/do/app/quotation/applyQueryAndQuote",
        )
    );

    //使用性质
    private $usageAttributeCode = Array(
        "OPERATING"     => "01",
        "NON_OPERATING" => "02",
    );

    //车辆类型转换
    private $vehicleType = Array(
        //min,max,type,class,name
        "PASSENGER_CAR" => Array(
            Array(0, 6, "A012", 1, "六座以下客车"),
            Array(6, 10, "A022", 1, "六座至十座以下客车"),
            Array(10, 20, "A032", 1, "十座至二十座以下客车"),
            Array(20, 36, "A042", 1, "二十座至三十六座以下客车"),
            Array(36, 0xffff, "A052", 1, "三十六座及三十六座以上客车"),
        ),
    );
    /*
    //车辆类型转换来源:
    private $vehClass = Array(
        "A012" => Array(
            "optionDisplay" => "六座以下客车",
            "optionValue" => "A012",
            "vehicleClass" => "1",
        ),
        "A022" => Array(
            "optionDisplay" => "六座至十座以下客车",
            "optionValue" => "A022",
            "vehicleClass" => "1",
        ),
        "A032" => Array(
            "optionDisplay" => "十座至二十座以下客车",
            "optionValue" => "A032",
            "vehicleClass" => "1",
        ),
        "A042" => Array(
            "optionDisplay" => "二十座至三十六座以下客车",
            "optionValue" => "A042",
            "vehicleClass" => "1",
        ),
        "A052" => Array(
            "optionDisplay" => "三十六座及三十六座以上客车",
            "optionValue" => "A052",
            "vehicleClass" => "1",
        ),
        "B012" => Array(
            "optionDisplay" => "二吨以下货车",
            "optionValue" => "B012",
            "vehicleClass" => "2",
        ),
        "B022" => Array(
            "optionDisplay" => "二吨至五吨以下货车",
            "optionValue" => "B022",
            "vehicleClass" => "2",
        ),
        "B032" => Array(
            "optionDisplay" => "五吨至十吨以下货车",
            "optionValue" => "B032",
            "vehicleClass" => "2",
        ),
        "B042" => Array(
            "optionDisplay" => "十吨以上货车",
            "optionValue" => "B042",
            "vehicleClass" => "2",
        ),
        "B052" => Array(
            "optionDisplay" => "低速载货汽车",
            "optionValue" => "B052",
            "vehicleClass" => "2",
        ),
        "B062" => Array(
            "optionDisplay" => "三轮农用运输车",
            "optionValue" => "B062",
            "vehicleClass" => "2",
        ),
        "B101" => Array(
            "optionDisplay" => "低速载货汽车14.7KW及以下",
            "optionValue" => "B101",
            "vehicleClass" => "2",
        ),
        "B102" => Array(
            "optionDisplay" => "低速载货汽车14.7KW-17.6KW及以下",
            "optionValue" => "B102",
            "vehicleClass" => "2",
        ),
        "B103" => Array(
            "optionDisplay" => "低速载货汽车17.6KW-50KW及以下",
            "optionValue" => "B103",
            "vehicleClass" => "2",
        ),
        "B104" => Array(
            "optionDisplay" => "低速载货汽车50KW-80KW及以下",
            "optionValue" => "B104",
            "vehicleClass" => "2",
        ),
        "B105" => Array(
            "optionDisplay" => "低速载货汽车80KW以上",
            "optionValue" => "B105",
            "vehicleClass" => "2",
        ),
        "B142" => Array(
            "optionDisplay" => "特种车四：集装箱拖头",
            "optionValue" => "B142",
            "vehicleClass" => "3",
        ),
        "B201" => Array(
            "optionDisplay" => "功率小于等于14.7KW的三轮汽车",
            "optionValue" => "B201",
            "vehicleClass" => "2",
        ),
        "B202" => Array(
            "optionDisplay" => "功率大于14.7KW小于等于17.6KW的三轮汽车",
            "optionValue" => "B202",
            "vehicleClass" => "2",
        ),
        "B203" => Array(
            "optionDisplay" => "功率大于17.6KW小于等于50KW的三轮汽车",
            "optionValue" => "B203",
            "vehicleClass" => "2",
        ),
        "B204" => Array(
            "optionDisplay" => "功率大于50KW小于等于80KW的三轮汽车",
            "optionValue" => "B204",
            "vehicleClass" => "2",
        ),
        "B205" => Array(
            "optionDisplay" => "功率大于80KW的三轮汽车",
            "optionValue" => "B205",
            "vehicleClass" => "2",
        ),
        "C022" => Array(
            "optionDisplay" => "特种车一：油罐车、汽罐车、液罐车、冷藏车",
            "optionValue" => "C022",
            "vehicleClass" => "3",
        ),
        "C032" => Array(
            "optionDisplay" => "特种车二：专用净水车、特种车一以外的罐式货车,用于清障、清扫、清洁、起重、装卸（不含自卸车）、升降、搅拌、挖掘、推土、冷藏、保温车等的各种专用机动车",
            "optionValue" => "C032",
            "vehicleClass" => "3",
        ),
        "C042" => Array(
            "optionDisplay" => "特种车三：装有固定专用仪器设备从事专业工作的监测、消防、运钞、医疗、电视转播的各种专用机动车",
            "optionValue" => "C042",
            "vehicleClass" => "3",
        ),
        "C112" => Array(
            "optionDisplay" => "挂车(2吨以下)",
            "optionValue" => "C112",
            "vehicleClass" => "3",
        ),
        "C122" => Array(
            "optionDisplay" => "挂车(2-5吨)",
            "optionValue" => "C122",
            "vehicleClass" => "3",
        ),
        "C132" => Array(
            "optionDisplay" => "挂车(5-10吨)",
            "optionValue" => "C132",
            "vehicleClass" => "3",
        ),
        "C142" => Array(
            "optionDisplay" => "挂车(10吨以上)",
            "optionValue" => "C142",
            "vehicleClass" => "3",
        ),
        "C152" => Array(
            "optionDisplay" => "挂车(特种车一)",
            "optionValue" => "C152",
            "vehicleClass" => "3",
        ),
        "C162" => Array(
            "optionDisplay" => "挂车(特种车二，罐体)",
            "optionValue" => "C162",
            "vehicleClass" => "3",
        ),
        "C172" => Array(
            "optionDisplay" => "挂车(特种车二，冷藏、保温)",
            "optionValue" => "C172",
            "vehicleClass" => "3",
        ),
        "C182" => Array(
            "optionDisplay" => "挂车(特种车三)",
            "optionValue" => "C182",
            "vehicleClass" => "3",
        ),
        "D012" => Array(
            "optionDisplay" => "摩托车50CC-250CC（含）",
            "optionValue" => "D012",
            "vehicleClass" => "4",
        ),
        "D022" => Array(
            "optionDisplay" => "摩托车250CC以上及侧三轮",
            "optionValue" => "D022",
            "vehicleClass" => "4",
        ),
        "D112" => Array(
            "optionDisplay" => "摩托车50CC及以下",
            "optionValue" => "D112",
            "vehicleClass" => "4",
        ),
        "E012" => Array(
            "optionDisplay" => "兼用型拖拉机14.7KW以上",
            "optionValue" => "E012",
            "vehicleClass" => "5",
        ),
        "E022" => Array(
            "optionDisplay" => "运输型拖拉机14.7KW以上",
            "optionValue" => "E022",
            "vehicleClass" => "5",
        ),
        "E112" => Array(
            "optionDisplay" => "兼用型拖拉机14.7KW及以下",
            "optionValue" => "E112",
            "vehicleClass" => "5",
        ),
        "E122" => Array(
            "optionDisplay" => "运输型拖拉机14.7KW及以下",
            "optionValue" => "E122",
            "vehicleClass" => "5",
        ),
        "E201" => Array(
            "optionDisplay" => "变形拖拉机14.7KW及以下",
            "optionValue" => "E201",
            "vehicleClass" => "5",
        ),
        "E202" => Array(
            "optionDisplay" => "变形拖拉机14.7KW-17.6KW及以下",
            "optionValue" => "E202",
            "vehicleClass" => "5",
        ),
        "E203" => Array(
            "optionDisplay" => "变形拖拉机17.6KW-50KW及以下",
            "optionValue" => "E203",
            "vehicleClass" => "5",
        ),
        "E204" => Array(
            "optionDisplay" => "变形拖拉机50KW-80KW及以下",
            "optionValue" => "E204",
            "vehicleClass" => "5",
        ),
        "E205" => Array(
            "optionDisplay" => "变形拖拉机80KW以上",
            "optionValue" => "E205",
            "vehicleClass" => "5",
        ),
        "E301" => Array(
            "optionDisplay" => "超标拖拉机14.7KW及以下",
            "optionValue" => "E301",
            "vehicleClass" => "5",
        ),
        "E302" => Array(
            "optionDisplay" => "超标拖拉机14.7KW-17.6KW及以下",
            "optionValue" => "E302",
            "vehicleClass" => "5",
        ),
        "E303" => Array(
            "optionDisplay" => "超标拖拉机17.6KW-50KW及以下",
            "optionValue" => "E303",
            "vehicleClass" => "5",
        ),
        "E304" => Array(
            "optionDisplay" => "超标拖拉机50KW-80KW及以下",
            "optionValue" => "E304",
            "vehicleClass" => "5",
        ),
        "E305" => Array(
            "optionDisplay" => "超标拖拉机80KW以上",
            "optionValue" => "E305",
            "vehicleClass" => "5",
        ),
    );
    */

    //险种代码对应关系
    private $duty_code = Array(
        //'MVTALCI'              => '',//交强险
        'TVDI'                 => '01',//车损险
        'TTBLI'                => '02',//三者险
        'TWCDMVI'              => '03',//盗抢险
        'TCPLI_DRIVER'         => '04',//车上司机
        'TCPLI_PASSENGER'      => '05',//车上乘客
        'BSDI'                 => '17',//车身划痕
        'BGAI'                 => '08',//玻璃破碎
        'NIELI'                => '11',//新增设备损失险
        'VWTLI'                => '41',//发动机涉水险
        'SLOI'                 => '18',//自燃损失
        'STSFS'                => '57',//指定修理厂
        'RDCCI'                => '10',//维修期间费用补偿险
        'MVLINFTPSI'           => '63',//第三方特约险

        'TVDI_NDSI'            => '27',//不计免赔车损险
        'TTBLI_NDSI'           => '28',//不计免赔三者险
        'TWCDMVI_NDSI'         => '48',//不计免赔盗抢险
        'TCPLI_DRIVER_NDSI'    => '49',//不计免赔车上司机
        'TCPLI_PASSENGER_NDSI' => '80',//不计免赔车上乘客
        'BSDI_NDSI'            => '75',//不计免赔车身划痕
        'SLOI_NDSI'            => '77',//不计免赔自燃险
        'NIELI_NDSI'           => '76',//不计免赔新增设备损失险
        'VWTLI_NDSI'           => '79',//不计免赔发动机涉水险
        //车上货物责任险 06         //不计免赔 73
        //精神损害抚慰金责任险 29   //不计免赔 78
    );

    //车辆种类代码
    private $vehTypeCode = Array(
        'A012' => 'K33',
        'A022' => 'K31',
        'A032' => 'K21',
        'A042' => 'K11',
        'A052' => 'K11',
        'B012' => 'H31',
        'B022' => 'H21',
        'B032' => 'H21',
        'B042' => 'H11',
        'C022' => 'H14',
        'B052' => 'H51',
        'B062' => 'N11',
        'B142' => 'Q11',
        'C032' => 'Z11',
        'C042' => 'Z11',
        'C112' => 'G31',
        'C122' => 'G21',
        'C132' => 'G21',
        'C142' => 'G11',
        'C152' => 'G13',
        'C162' => 'G13',
        'C172' => 'G12',
        'C182' => 'B17',
        'B101' => 'H51',
        'B102' => 'H51',
        'B103' => 'H51',
        'B104' => 'H51',
        'B105' => 'H51',
        'D112' => 'M22',
        'D012' => 'M11',
        'D022' => 'M11',
        'E012' => 'T11',
        'E022' => 'T11',
        'E112' => 'T11',
        'E122' => 'T11',
        'E201' => 'T11',
        'E202' => 'T11',
        'E203' => 'T11',
        'E204' => 'T11',
        'E205' => 'T11',
        'E301' => 'T11',
        'E302' => 'T11',
        'E303' => 'T11',
        'E304' => 'T11',
        'E305' => 'T11',
        'B201' => 'N11',
        'B202' => 'N11',
        'B203' => 'N11',
        'B204' => 'N11',
        'B205' => 'N11',
    );

    private $vehTypeCodeName = Array(
        "B11" => "重型普通半挂车",
        "B12" => "重型厢式半挂车",
        "B13" => "重型罐式半挂车",
        "B14" => "重型平板半挂车",
        "B15" => "重型集装箱半挂车",
        "B16" => "重型自卸半挂车",
        "B17" => "重型特殊结构半挂车",
        "B21" => "中型普通半挂车",
        "B22" => "中型厢式半挂车",
        "B23" => "中型罐式半挂车",
        "B24" => "中型平板半挂车",
        "B25" => "中型集装箱半挂车",
        "B26" => "中型自卸半挂车",
        "B27" => "中型特殊结构半挂车",
        "B31" => "轻型普通半挂车",
        "B32" => "轻型厢式半挂车",
        "B33" => "轻型罐式半挂车",
        "B34" => "轻型平板半挂车",
        "B35" => "轻型自卸半挂车",
        "D11" => "无轨电车",
        "D12" => "有轨电车",
        "G11" => "重型普通全挂车",
        "G12" => "重型厢式全挂车",
        "G13" => "重型罐式全挂车",
        "G14" => "重型平板全挂车",
        "G15" => "重型集装箱全挂车",
        "G16" => "重型自卸全挂车",
        "G21" => "中型普通全挂车",
        "G22" => "中型厢式全挂车",
        "G23" => "中型罐式全挂车",
        "G24" => "中型平板全挂车",
        "G25" => "中型集装箱全挂车",
        "G26" => "中型自卸全挂车",
        "G31" => "轻型普通全挂车",
        "G32" => "轻型厢式全挂车",
        "G33" => "轻型罐式全挂车",
        "G34" => "轻型平板全挂车",
        "G35" => "轻型自卸全挂车",
        "H11" => "重型普通货车",
        "H12" => "重型厢式货车",
        "H13" => "重型封闭货车",
        "H14" => "重型罐式货车",
        "H15" => "重型平板货车",
        "H16" => "重型集装厢车",
        "H17" => "重型自卸货车",
        "H18" => "重型特殊结构货车",
        "H21" => "中型普通货车",
        "H22" => "中型厢式货车",
        "H23" => "中型封闭货车",
        "H24" => "中型罐式货车",
        "H25" => "中型平板货车",
        "H26" => "中型集装厢车",
        "H27" => "中型自卸货车",
        "H28" => "中型特殊结构货车",
        "H31" => "轻型普通货车",
        "H32" => "轻型厢式货车",
        "H33" => "轻型封闭货车",
        "H34" => "轻型罐式货车",
        "H35" => "轻型平板货车",
        "H37" => "轻型自卸货车",
        "H38" => "轻型特殊结构货车",
        "H41" => "微型普通货车",
        "H42" => "微型厢式货车",
        "H43" => "微型封闭货车",
        "H44" => "微型罐式货车",
        "H45" => "微型自卸货车",
        "H46" => "微型特殊结构货车",
        "H51" => "低速普通货车",
        "H52" => "低速厢式货车",
        "H53" => "低速罐式货车",
        "H54" => "低速自卸货车",
        "J11" => "轮式装载机械",
        "J12" => "轮式挖掘机械",
        "J13" => "轮式平地机械",
        "K11" => "大型普通客车",
        "K12" => "大型双层客车",
        "K13" => "大型卧铺客车",
        "K14" => "大型铰接客车",
        "K15" => "大型越野客车",
        "K21" => "中型普通客车",
        "K22" => "中型双层客车",
        "K23" => "中型卧铺客车",
        "K24" => "中型铰接客车",
        "K25" => "中型越野客车",
        "K31" => "小型普通客车",
        "K32" => "小型越野客车",
        "K33" => "轿车",
        "K41" => "微型普通客车",
        "K42" => "微型越野客车",
        "K43" => "微型轿车",
        "M11" => "普通正三轮摩托车",
        "M12" => "轻便正三轮摩托车",
        "M13" => "正三轮载客摩托车",
        "M14" => "正三轮载货摩托车",
        "M15" => "侧三轮摩托车",
        "M21" => "普通二轮摩托车",
        "M22" => "轻便二轮摩托车",
        "N11" => "三轮汽车",
        "Q11" => "重型半挂牵引车",
        "Q21" => "中型半挂牵引车",
        "Q31" => "轻型半挂牵引车",
        "S"   => "特种作业专用车",
        "T11" => "大型轮式拖拉机",
        "T21" => "小型轮式拖拉机",
        "T22" => "手扶拖拉机",
        "T23" => "手扶变形运输机",
        "X99" => "其他",
        "Z11" => "大型专项作业车",
        "Z21" => "中型专项作业车",
        "Z31" => "小型专项作业车",
        "Z41" => "微型专项作业车",
        "Z51" => "重型专项作业车",
        "Z71" => "轻型专项作业车",
    );

    //号牌种类
    /*
        01 => 大型汽车
        02 => 小型汽车
        03 => 使馆汽车
        04 => 领馆汽车
        05 => 境外汽车
        06 => 外籍汽车
        07 => 两、三轮摩托车
        08 => 轻便摩托车
        09 => 使馆摩托车
        10 => 领馆摩托车
        11 => 境外摩托车
        12 => 外籍摩托车
        13 => 农用运输车
        14 => 拖拉机
        15 => 挂车
        16 => 教练汽车
        17 => 教练摩托车
        18 => 试验汽车
        19 => 试验摩托车
        20 => 临时入境汽车
        21 => 临时入境摩托车
        22 => 临时行驶车
        23 => 公安警车
        24 => 其它车型
        25 => 专用机械
        26 => 警备摩托车
        27 => 大型警车
        31 => 武警号牌
        32 => 军队号牌
     */
    private $licenseType = Array(
        "SMALL_CAR"               => "02",
        "LARGE_AUTOMOBILE"        => "01",
        "TRAILER"                 => "15",
        "EMBASSY_CAR"             => "03",
        "CONSULATE_VEHICLE"       => "04",
        "HK_MACAO_ENTRY_EXIT_CAR" => "20",
        "COACH_CAR"               => "16",
        "POLICE_CAR"              => "23",
        "GENERAL_MOTORCYCLE"      => "07",
        "MOPED"                   => "08",
        "EMBASSY_MOTORCYCLE"      => "09",
        "CONSULATE_MOTORCYCLE"    => "10",
        "COACH_MOTORCYCLE"        => "17",
        "POLICE_MOTORCYCLE"       => "26",
        "TEMPORARY_VEHICLE"       => "22",
    );
    private $licenseName = Array(
        "SMALL_CAR"               => "小型汽车",
        "LARGE_AUTOMOBILE"        => "大型汽车",
        "TRAILER"                 => "挂车",
        "EMBASSY_CAR"             => "使馆汽车",
        "CONSULATE_VEHICLE"       => "领馆汽车",
        "HK_MACAO_ENTRY_EXIT_CAR" => "临时入境汽车",
        "COACH_CAR"               => "教练汽车",
        "POLICE_CAR"              => "公安警车",
        "GENERAL_MOTORCYCLE"      => "两、三轮摩托车",
        "MOPED"                   => "轻便摩托车",
        "EMBASSY_MOTORCYCLE"      => "使馆摩托车",
        "CONSULATE_MOTORCYCLE"    => "领馆摩托车",
        "COACH_MOTORCYCLE"        => "教练摩托车",
        "POLICE_MOTORCYCLE"       => "警备摩托车",
        "TEMPORARY_VEHICLE"       => "临时行驶车",
    );

    //所属性质
    private $ownershipAttributeCode = Array(
        "NON_OPERATING_AUTHORITY"  => "01",//机关
        "NON_OPERATING_ENTERPRISE" => "02",//企业
        "NON_OPERATING_PRIVATE"    => "03",//私人
    );

    //指定修理厂
    private $stsfs_type = Array(
        "DOMESTIC" => Array("SEATS" => 0, "RATE" => 30),
        "IMPORTED" => Array("SEATS" => 1, "RATE" => 60),
    );

    //玻璃种类
    private $glass_type = Array(
        "DOMESTIC"         => Array("SEATS" => 0, "SPECIAL" => 0),
        "DOMESTIC_SPECIAL" => Array("SEATS" => 0, "SPECIAL" => 1),
        "IMPORTED"         => Array("SEATS" => 1, "SPECIAL" => 0),
        "IMPORTED_SPECIAL" => Array("SEATS" => 1, "SPECIAL" => 1),
    );


    //声明cURL会话配置
    private $_options = Array();

    //cURL连接资源句柄信息
    private $_info = Array();



    //获取链接地址
    private function getURL($usIP = true)
    {
        $link = Array();
        $affix = $usIP ? "IPADDR" : "DOMAIN";
        foreach ($this->uri as $type => $uri) {
            foreach ($uri as $key => $val) {
                $link[$key] = $this->url[$type][$affix] . $val;
            }
        }
        return $link;
    }

    /**
     * 构造函数
     * 参数:
     * @config          必需。配置 数组
     * @cachePath       必需。缓存目录 绝对路径
     **/
    function __construct($config,$cachePath)
    {
        //获取登录信息
        $user = '';
        if(array_key_exists('username',$config))
        {
            $user = $config['username'];
        }
        $password = '';
        if(array_key_exists('password',$config))
        {
            $password = $config['password'];
        }
        $this->fueltype = FALSE;
        if(array_key_exists('fueltype', $config))
        {
            $this->fueltype = $config['fueltype'];
        }

        //设置登陆信息
        $this->loginInfo    = Array("j_username" => $user, "j_password" => $password, "SMAUTHREASON" => 0, "randCode" => "");
        //获取链接地址
        $this->_link        = $this->getURL();
        //cookie文件名
        $cookie_name = "pingankhyxyn_cookie.txt";
        //设置cookie目录文件
        $this->cookie_file = empty($cachePath) ? dirname(__FILE__) . '/' . $cookie_name : $cachePath . '/' . $cookie_name;
    }

    /**
     * [getSetItems 获取设置项目]
     * @return [Array] [设置项目]
     * (必需)
     */
    public function getSetItems()
    {
        return $this->setItems;
    }

    /**
     * [getFormFile 获取表单模板文件名]
     * @return [String] [模板文件名]
     * (必需)
     */
    public function getFormFile()
    {
        return self::formFile;
    }

    /**
     * [setOptions 设置cURL参数]
     * @param [Array] $options [cURL请求参数]
     */
    private function setOptions($options)
    {
        $def_options = Array(
            //声明用户代理头
            CURLOPT_USERAGENT      => "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)",
            //头信息
            CURLOPT_HTTPHEADER     => Array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate'),
            //关闭SSL验证
            CURLOPT_SSL_VERIFYPEER => false,
            //关闭SSL名称验证
            CURLOPT_SSL_VERIFYHOST => 0,
            //请求地址
            CURLOPT_URL            => "",
            //来源地址
            CURLOPT_REFERER        => $this->_link["REFERER"],
            //启用自动跳转
            CURLOPT_FOLLOWLOCATION => 1,
            //设置cookie存放地址
            CURLOPT_COOKIEJAR      => $this->cookie_file,
            //设置cookie读取地址
            CURLOPT_COOKIEFILE     => $this->cookie_file,
            //解释gzip
            CURLOPT_ENCODING       => 'gzip,deflate',
            //设置超时时间
            CURLOPT_TIMEOUT        => 60,
            //设置连接超时时间
            CURLOPT_CONNECTTIMEOUT => 30,
            //获取的信息以文件流的形式返回
            CURLOPT_RETURNTRANSFER => 1,
        );
        //追加默认配置
        $this->_options = $options + $def_options;
    }

    /**
     * [getOptions 获取cURL参数信息]
     * @return [Array] [cURL参数信息]
     */
    private function getOptions()
    {
        return $this->_options;
    }

    /**
     * [request 发起一个cURL会话请求]
     * @param  [Array] $options [cURL参数]
     * @return [Mixed]          [请求响应结果]
     */
    private function request($options)
    {
        //启动CURL会话
        $curl      = curl_init();
        $this->setOptions($options);
        curl_setopt_array($curl, $this->_options);
        $response  = curl_exec($curl);
        $this->_info = curl_getinfo($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * [login 登录验证]
     * @return [Bool] [成功返回true,失败返回false]
     */
    private function login()
    {
        $options = Array(
            CURLOPT_URL        => $this->_link["LOGIN"],
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => http_build_query($this->loginInfo),
        );
        $response = $this->request($options);
        if($this->_info["http_code"] == 403)
        {
            $this->error["errorMsg"] = "用户名或者密码错误,请检查算价器设置信息.";
            return false;
        }
        return true;
    }

    /**
     * [pregWriter 正则匹配用户信息]
     * @param  [String] $html [页面内容]
     * @return [Array]        [用户信息数组]
     */
    private function pregWriter($html)
    {
        preg_match_all("/<input.*name=[\'\"]{1}(.*)[\'\"]{1}.*value=[\'\"]{1}(.*)[\'\"]{1}/sU", $html, $response);
        return empty($response[1]) || empty($response[2]) ? Array() : array_combine($response[1], $response[2]);
    }

    /**
     * [getWriter 获取登录用户信息]
     * @return [Mixed] [成功返回用户信息,失败返回false]
     */
    private function getWriter()
    {
        $options = Array(
            CURLOPT_URL => $this->_link["WRITER"],
        );
        $response = $this->request($options);
        $writer = $this->pregWriter($response);
        if(empty($writer))
        {
            $this->error["errorMsg"] = "用户信息获取失败.";
            return false;
        }
        return $writer;
    }

    /**
     * [submitWriter 提交用户信息]
     * @param  [Array] $writer [用户信息]
     */
    private function submitWriter($writer)
    {
        $options = Array(
            CURLOPT_URL        => $this->_link["TRANSFER"],
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => http_build_query($writer),
        );
        $this->request($options);
    }

    private function setWriter()
    {
        $writer = $this->getWriter();
        if($writer === false)
        {
            if($this->login())
            {
                $writer = $this->getWriter();
                if($writer !== false)
                {
                    $this->submitWriter($writer);
                    return $writer;
                }
            }
        }
        else
        {
            return $writer;
        }
        return false;
    }

    /**
     * [getErrorMessage 获取购置价查询失败错误信息]
     * @param  [string] $erMsg [错误信息]
     * @return [string]        [错误信息]
     */
    private function getErrorMessage($erMsg)
    {
        preg_match_all("/<errorMsg>(.*)<\/errorMsg>/sU", $erMsg, $result);
        if(isset($result[1][0]))
        {
            $this->error["errorMsg"] = $result[1][0];
            return $result[1][0];
        }
        return NULL;
    }

    /**
     * [premiumError 算价错误信息]
     * @param  [Mix]  $resen [算价返回内容]
     * @return [bool]        [算价错误返回FALSE,正确返回TRUE]
     */
    private function premiumError($resen)
    {
        if (!is_array($resen))
        {
            $this->error["errorMsg"] = $resen;
        }
        elseif (isset($resen["c51CaculateResult"]["c51ResultDTO"]["errorCode"]) && $resen["c51CaculateResult"]["c51ResultDTO"]["errorCode"] != 0) {
            $this->error["errorMsg"] = $resen["c51CaculateResult"]["c51ResultDTO"]["circMessage"] . "<br />";
        }
        elseif (isset($resen['applyQueryResult']['circInfoDTO']['thirdVehicleReinsureList']) && !empty($resen['applyQueryResult']['circInfoDTO']['thirdVehicleReinsureList']) && isset($resen['applyQueryResult']['circInfoDTO']['thirdVehicleReinsureList'][0]['vin']))
        {
            $third          = $resen['applyQueryResult']['circInfoDTO']['thirdVehicleReinsureList'];
            $policyNo       = isset($third[0]['policyNo']) ? $third[0]['policyNo'] : null;
            $dateEffective  = isset($third[0]['dateEffective']) ? $third[0]['dateEffective'] : null;
            $dateExpire     = isset($third[0]['dateExpire']) ? $third[0]['dateExpire'] : null;
            $companyName    = isset($third[0]['companyName']) ? $third[0]['companyName'] : null;
            $licensePlateNo = isset($third[0]['licensePlateNo']) ? $third[0]['licensePlateNo'] : null;
            $vin            = isset($third[0]['vin']) ? $third[0]['vin'] : null;
            $engineNo       = isset($third[0]['engineNo']) ? $third[0]['engineNo'] : null;

            $message = "该单重复投保，重复投保保单信息如下:<br />";
            $message .= "保单号:{$policyNo}<br />";
            $message .= "保险起期:{$dateEffective}<br />";
            $message .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;保险止期:{$dateExpire}<br />";
            $message .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;保险公司:{$companyName}<br />";
            $message .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;车牌号:{$licensePlateNo}<br />";
            $message .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;车架号:{$vin}<br />";
            $message .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;发动机号:{$engineNo}<br />";
            $this->error["errorMsg"] = $message;
        }
        elseif (isset($resen["c01CaculateResult"]["errorCode"]) && $resen["c01CaculateResult"]["errorCode"] != 0 && isset($resen["c01CaculateResult"]["c01ResultDTO"]["decisionTreeResult"]["noQuoteReason"])) {
            $this->error["errorMsg"] = "决策树不报价原因:<br />" . str_replace("@@@", "<br />", $resen["c01CaculateResult"]["c01ResultDTO"]["decisionTreeResult"]["noQuoteReason"]);
        }
        else
        {
            return true;
        }
        return false;
    }

    /**
     * [decodeMessage 解析购置价返回信息]
     * @param  [string] $content   [json数据/错误信息]
     * @return [mixed]         [成功返回array,失败返回错误信息(string)]
     */
    private function decodeMessage($content)
    {
        $data = json_decode($content, true);
        return is_null($data) ? $this->getErrorMessage($content) : $data;
    }

    /**
     * vinQuery 通过车架号查询新车购置价
     *
     * @param [Array] $info
     * @param [Array] $writer
     * @return Mixed
     */
    private function vinQuery($info, $writer)
    {
        //构造请求参数
        $post_data = Array(
            "vehicleFrameNo" => $info["vin_no"],
            "departmentCode" => $writer["departmentCode"],
            "insuranceType"  => 1,
        );
        //构造cURL请求参数
        $options = Array(
            CURLOPT_URL        => $this->_link["VINQUERY"],
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => http_build_query($post_data),
        );
        //发起请求
        $response = $this->request($options);
        //解析请求返回数据
        $result   = $this->decodeMessage($response);
        return $result;
    }

    /**
     * modelQuery 通过车型查询新车购置价
     *
     * @param [Array] $info
     * @param [Array] $writer
     * @return Mixed
     */
    private function modelQuery($info, $writer)
    {
        //构造请求参数
        $post_data = Array(
            "departmentCode" => $writer["departmentCode"],
            "brandPara"      => $info['model'],
            "rateClassFlag"  => "14",
            "insuranceType"  => "1",
        );
        //构造cURL请求参数
        $options = Array(
            CURLOPT_URL        => $this->_link["MODELQUERY"],
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => http_build_query($post_data),
        );
        //发起请求
        $response = $this->request($options);
        //解析请求返回数据
        $result   = $this->decodeMessage($response);
        $result   = isset($result["data"]) ? $result["data"] : $result;
        return $result;
    }

    /**
     * [queryBuyingPrice 新车购置价查询]
     * @param  array  $info    [车辆信息]
     * @return [Mixed]         [成功返回购置价,失败返回false]
     * (必需)
     */
    public function queryBuyingPrice($info = Array())
    {
        //每页显示行数
        $rows  = 10;
        //当前页数
        $page = empty($info['page']) ? 1 : $info['page'];
        //默认返回信息
        $data  = Array('total'=>0,'page'=>0,'records'=>0,'rows'=>Array());
        //获取用户信息
        $writer = $this->setWriter();
        if($writer === false)
            return false;
        //通过车型查询新车购置价
        $result = $this->modelQuery($info, $writer);
        //通过车架号查询新车购置价
        if(!isset($result["encodeDict"]) || empty($result["encodeDict"]))
        {
            $result = $this->vinQuery($info, $writer);
        }
        if(isset($result["encodeDict"]))
        {
            //获取总条数
            $total    = sizeof($result["encodeDict"]);
            $data     = Array('total' => ceil($total/$rows), 'page' => $page, 'records' => $total, 'rows' => Array());
            //手动分页
            $subindex = $rows * ($page - 1);
            $subEnd   = $page * $rows;
            foreach ($result["encodeDict"] as $key => $val) {
                if($key >= $subindex && $key < $subEnd){
                    $line = Array();
                    //数组键值定义templates/templates/PolicyCalculateCom/queryBuyingPrice.tpl
                    $line['vehicleId']             = $val['autoModelCode'];
                    $line['vehicleName']           = $val['autoModelName'];
                    $line['vehicleAlias']          = isset($val['pa18AliasName']) ? $val['pa18AliasName'] : "";
                    $line['vehicleMaker']          = $val['brandName'];
                    $line['vehicleWeight']         = 0;
                    $line['vehicleDisplacement']   = $val['exhaustMeasure'];
                    $line['vehicleTonnage']        = $val['tons'];
                    $line['vehiclePrice']          = $val['purchasePrice'];
                    $line['szxhTaxedPrice']        = $val['purchasePriceIncludeTax'];
                    $line['xhKindPrice']           = 0;
                    $line['nXhKindpriceWithouttax']= 0;
                    $line['vehicleSeat']           = $val['seats'];
                    $line['vehicleYear']           = isset($val['firstSaleDate']) ? $val['firstSaleDate'] : "";
                    $line['vehicleTypeNew']        = $val['vehicleTypeNew'];
                    $data["rows"][]                = $line;
                }
            }
        }
        return $data;
    }

    /**
     * [getMonthDeprecition 根据车型和使用性质返回月折旧系数]
     * @param  [String] $vehicleType [车辆种类]
     * @param  [String] $usAge       [使用性质]
     * @return [FLOAT]               [折旧系数]
     *
     *
     * //js代码中对折旧系数的判断
        if ((vehicleType == NBAC.VEHICLETYPE_B042) || (vehicleType == NBAC.VEHICLETYPE_B032)) {
            monthDeprecition = NBAC.DEPRECITION_MILLI_TWELEVE;
        } else {
            monthDeprecition = NBAC.DEPRECITION_MILLI_NIGHT;
        };
        if (usageAttributeCode == NBAC.USAGE_ATTRIBUTE_BUSINESS && (vehicleType == NBAC.VEHICLETYPE_A012 || vehicleType == NBAC.VEHICLETYPE_A022)) {
            monthDeprecition = NBAC.DEPRECITION_MILLI_TWELEVE;
        } else if (usageAttributeCode == NBAC.USAGE_ATTRIBUTE_NO_BUSINESS && (vehicleType == NBAC.VEHICLETYPE_A012) || (vehicleType == NBAC.VEHICLETYPE_A022)) {
            monthDeprecition = NBAC.DEPRECITION_MILLI_SIX;
        };
     */
    private function getMonthDeprecition($vehicleType,$usAge)
    {
        $monthDeprecition = "";
        if ($vehicleType == "B042" || $vehicleType == "B032"){
            $monthDeprecition = 0.012;
        }else{
            $monthDeprecition = 0.009;
        }
        if ($usAge == "01" && ($vehicleType == "A012" || $vehicleType == "A022")){
            $monthDeprecition = 0.012;
        }elseif ($usAge == "02" && ($vehicleType == "A012") || ($vehicleType == "A022")) {
            $monthDeprecition = 0.006;
        }
        return $monthDeprecition;
    }

    /**
     * [getVehicleType 获取车辆类型代码]
     * @param  [String] $type  [车辆类型]
     * @param  [float]  $param [座位/载重/功率....]
     * @return [mixed]         [成功返回车辆类型代码信息数组,失败返回false]
     */
    private function getVehicleType($type, $param)
    {
        if(isset($this->vehicleType[$type]))
        {
            foreach ($this->vehicleType[$type] as $row) {
                if($param >= $row[0] && $param < $row[1])
                    return Array("type" => $row[2], "class" => $row[3], "name" => $row[4]);
            }
        }
        return false;
    }

    /**
     * [getUsageAttributeCode 获取使用性质代码]
     * @param  [String] $usAge [使用性质]
     * @return [Mixed]         [使用性质代码,失败返回false]
     */
    private function getUsageAttributeCode($usAge)
    {
        foreach ($this->usageAttributeCode as $key => $val) {
            if(strpos($usAge,$key) === 0){
                return $val;
            }
        }
        return false;
    }

    /**
     * [depreciation 车辆折旧价计算]
     * @param  array  $info    [参数信息]
     * @return [Mixed]         [成功返回折旧价,失败返回false]
     * (必需)
     */
    public function depreciation($info = Array())
    {
        //获取用户信息
        $writer = $this->setWriter();
        if($writer === false)
            return false;

        $vehicleType = $this->getVehicleType($info["VEHICLE_TYPE"], $info["SEATS"]);
        $usAge       = $this->getUsageAttributeCode($info["USE_CHARACTER"]);

        $post_data = Array(
            //购置价BUYING_PRICE
            "purchasePriceToWrite" => $info["BUYING_PRICE"],
            //初登日期ENROLL_DATE
            "dateFirstRegister"    => $info["ENROLL_DATE"],
            "monthDeprecition"     => $this->getMonthDeprecition($vehicleType["type"], $usAge),
            //保险开始日期BUSINESS_START_TIME
            "insuranceBeginTime"   => $info["BUSINESS_START_TIME"],
            "calculateType"        => "calculateStealRobInsuredAmount",
            "calculateParamNames"  => "purchasePriceToWrite,dateFirstRegister,monthDeprecition,insuranceBeginTime",
            "departmentCode"       => $writer["departmentCode"],
            //车辆种类
            "vehicleType"          => $vehicleType["type"],
            "vehicleTypeDetail"    => "",
            //使用性质
            "usageAttributeCode"   => $usAge,
        );

        $options = Array(
            CURLOPT_URL        => $this->_link["DEPRECIATION"],
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => http_build_query($post_data),
        );
        $response = $this->decodeMessage($this->request($options));
        if(is_array($response)) return $response["calculateResult"]["stealRobInsuredAmount"];
        return false;
    }

    /**
     * [deviceDepreciation 设备折旧价计算]
     * @param  array  $info    [参数信息]
     * @return [Mixed]         [成功返回折旧价,失败返回false]
     * 平安算价未找到设备折旧价,该折旧价计算方式拷贝至JingTai_PC.class.php
     * (必需)
     */
    public function deviceDepreciation($info = Array())
    {
        if(empty($info) || !isset($info)){
            $this->error = "参数信息不能为空";
            return false;
        }

        foreach($info['DEVICE_LIST'] as $k =>$v)
        {
            if(!isset($v['NAME']))
            {
                $this->error = "设备名称不能为空";
                return false;
            }

            if(!isset($v['BUYING_PRICE']))
            {
                $this->error = "新购价格不能为空";
                return false;
            }

            if(!isset($v['COUNT']))
            {
                $this->error = "数量不能为空";
                return false;
            }

            if(!isset($v['BUYING_DATE']))
            {
                $this->error = "购置日期不能空";
                return false;
            }

            if(!isset($info['BUSINESS_START_TIME']))
            {
                $this->error = "商业险日期不能为空";
                return false;
            }

            if(!isset($info['VEHICLE_TYPE']))
            {
                $this->error = "车辆种类不能为空";
                return false;
            }


            if(!isset($info['USE_CHARACTER']))
            {
                $this->error = "使用性质不能为空";
                return false;
            }

            $ENROLLhour = date('Y',strtotime($v['BUYING_DATE']))*12+(date('m',strtotime($v['BUYING_DATE'])));
            $START_TIMEhour = date('Y',strtotime($info['BUSINESS_START_TIME']))*12+(date('m',strtotime($info['BUSINESS_START_TIME'])));
            $Month=$START_TIMEhour-$ENROLLhour;//折扣月份

            if(date('d',strtotime($info['BUSINESS_START_TIME']))<date('d',strtotime($v['BUYING_DATE'])))
            {
                $Month--;//比较日期
            }
            if($Month < 0)
             {
                    $Month = 0;//如果相差为负数时，设置为0，否则为引起险别重新计算保额错误

             }
             //判断是否是家用车
            if($info['USE_CHARACTER'] == "NON_OPERATING_PRIVATE" && $info['VEHICLE_TYPE'] == "PASSENGER_CAR")
            {
                $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.006;

            }
            else if($info['VEHICLE_TYPE'] == "TRUCK")//货车类型
            {

                if($info['USE_CHARACTER'] == 'OPERATING_TRUCK')
                {
                     $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;
                }

                else if($info['USE_CHARACTER'] == 'NON_OPERATING_TRUCK')
                {
                     $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;
                }
                else if($info['USE_CHARACTER'] == 'NON_OPERATING_LOW_SPEED_TRUCK')
                {
                     $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;
                }
                else if($info['USE_CHARACTER'] == 'OPERATING_LOW_SPEED_TRUCK')
                {
                     $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.014;

                }

            }
            else if($v['VEHICLE_TYPE'] == "PASSENGER_CAR")//客车类型
            {

                if($v['USE_CHARACTER'] == "NON_OPERATING_ENTERPRISE" || $v['USE_CHARACTER'] == "NON_OPERATING_AUTHORITY")
                {
                     $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.006;

                }
                else if($v['USE_CHARACTER'] == "OPERATING_LEASE_RENTAL")
                {
                    $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;

                }
                else if($v['USE_CHARACTER'] == "OPERATING_CITY_BUS" || $v['USE_CHARACTER'] == "OPERATING_HIGHWAY_BUS")
                {
                    $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;
                }


            }
            else if($v['VEHICLE_TYPE'] == 'THREE_WHEELED')
            {
                $BUYING_PRICE = $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;
            }

            $data[$k]['BUYING_DATE']  = $v['BUYING_DATE'];
            $data[$k]['BUYING_PRICE'] = $v['BUYING_PRICE'];
            $data[$k]['COUNT']        = $v['COUNT'];
            $data[$k]['DEPRECIATION'] = $BUYING_PRICE;//$BUYING_PRICE*$v['COUNT']设备小计
            $data[$k]['NAME']         = $v['NAME'];

        }
            return $data;
    }
    /**
     * [getGenderAndBirthday 根据身份证(未设置15位身份证)获取性别、生日]
     * @param  [String] $identify_no [身份证号]
     * @return [Array]               [性别、生日]
     */
    private function getGenderAndBirthday($identify_no)
    {
        $result = FALSE;
        $identify_len = strlen($identify_no);
        if($identify_len == 18) {
            $result["BIRTHDAY"] = substr($identify_no, 6, 4) . "-" .substr($identify_no, 10, 2) . "-" . substr($identify_no, 12,2);
            $result["GENDER"] = substr($identify_no, $identify_len - 2,1) % 2 === 1 ? "M" : "F";
        }
        return $result;
    }

    /**
     * [getBusinessItems 获取商业险请求参数]
     * @param  [Array] $business [商业险参数]
     * @return [Array]           [商业险请求参数]
     */
    private function getBusinessItems($business)
    {
        $business_items = Array();
        foreach ($business["POLICY"]["BUSINESS_ITEMS"] as $key => $val)
        {
            $business_items[$key]["dutyCode"]             = $this->duty_code[$val];
            //设置非不计免赔险种默认值
            if(!strstr($val,"NDSI"))
            {
                $business_items[$key]["basePremium"]          = 0;
                $business_items[$key]["premiumRate"]          = 0;
                $business_items[$key]["totalStandardPremium"] = 0;
                $business_items[$key]["totalAgreePremium"]    = 0;
                $business_items[$key]["totalActualPremium"]   = 0;
                $business_items[$key]["pureRiskPremium"]      = "";
                $business_items[$key]["riskPremium"]          = "";
            }
            switch ($val)
            {
                case 'TVDI':
                    $business_items[$key]["insuredAmount"]       = $business["POLICY"]["TVDI_INSURANCE_AMOUNT"];
                    //$business_items[$key]["pureRiskPremiumFlag"] = "1";
                    break;
                case 'TTBLI':
                    $business_items[$key]["insuredAmount"]       = $business["POLICY"]["TTBLI_INSURANCE_AMOUNT"];
                    break;
                case 'TWCDMVI':
                    $business_items[$key]["insuredAmount"]             = $business["POLICY"]["TWCDMVI_INSURANCE_AMOUNT"];
                    $business_items[$key]["insuredAmountDefaultValue"] = $business["POLICY"]["TWCDMVI_INSURANCE_AMOUNT"];
                    break;
                case 'TCPLI_DRIVER':
                    $business_items[$key]["insuredAmount"]             = $business["POLICY"]["TCPLI_INSURANCE_DRIVER_AMOUNT"];
                    break;
                case 'TCPLI_PASSENGER':
                    $business_items[$key]["insuredAmount"] = $business["POLICY"]["TCPLI_INSURANCE_PASSENGER_AMOUNT"];
                    $business_items[$key]["seats"]         = $business["POLICY"]["TCPLI_PASSENGER_COUNT"];
                    break;
                case 'BGAI':
                    $glassInfo                              = $this->glass_type[$business["POLICY"]["GLASS_ORIGIN"]];
                    $business_items[$key]["seats"]          = $glassInfo["SEATS"];
                    $business_items[$key]["isSpecialGlass"] = $glassInfo["SPECIAL"];
                    break;
                case 'RDCCI':
                    $business_items[$key]["seats"]          = $business["POLICY"]["RDCCI_INSURANCE_UNIT"];
                    $business_items[$key]["insuredAmount"]  = $business["POLICY"]["RDCCI_INSURANCE_QUANTITY"];
                    break;
                case 'NIELI':
                    $device_list = Array();
                    $insuredAmount = 0;
                    foreach ($business["POLICY"]["NIELI_DEVICE_LIST"] as $device) {
                        $device_list[] = Array("subjectName" => $device["NAME"], "insuredAmount" => $device["BUYING_PRICE"]);
                        $insuredAmount += $device["BUYING_PRICE"];
                    }
                    $business_items[$key]["insuredAmount"]  = $insuredAmount;
                    $business_items[$key]["dutyDetailList"] = $device_list;
                    break;
                case 'BSDI':
                    $business_items[$key]["insuredAmount"] = $business["POLICY"]["BSDI_INSURANCE_AMOUNT"];
                    break;
                case 'SLOI':
                    $business_items[$key]["insuredAmount"] = $business["POLICY"]["SLOI_INSURANCE_AMOUNT"];
                    $business_items[$key]["insuredAmountDefaultValue"] = $business["POLICY"]["SLOI_INSURANCE_AMOUNT"];
                    break;
                case 'STSFS':
                    $business_items[$key]["seats"]          = $this->stsfs_type[$business["POLICY"]["STSFS_RATE"]]["SEATS"];
                    $business_items[$key]["premiumRate"]    = $this->stsfs_type[$business["POLICY"]["STSFS_RATE"]]["RATE"];
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $business_items;
    }

    /**
     * [checkcode 交管车辆查询获取验证码]
     * @param  array  $info [车牌号,车架号]
     * @return [JSON]       [验证码等信息]
     * (交管车辆查询必须)
     * 拷贝至Jiangsu_PICCKHYXSC_PC.class.php
     */
    public function checkcode($info = array())
    {
        $checkcode = Array();
        if(trim($info['LICENSE_NO']) == "" || trim($info['VIN_NO']) == "")
        {
            $this->error['errorMsg'] = "车牌号或车架号不能为空";
            return false;
        }
        //获取用户信息
        $writer = $this->setWriter();
        if($writer === false)
            return false;

        $post_data = Array(
            "carMark"        => $info['LICENSE_NO'],
            "vehicleFrameNo" => $info['VIN_NO'],
            "departmentCode" => $writer['departmentCode'],
            "insuranceCode"  => "C51",
            "rateClassFlag"  => "14",
        );

        $options = Array(
            CURLOPT_URL        => $this->_link["DM_RAND_CODE"],
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => http_build_query($post_data),
        );
        $response = json_decode($this->request($options), true);
        if(isset($response["errorCode"]) && $response["errorCode"] == 0)
        {
            $checkcode["checkcode"] = $response["checkCode"];
            $checkcode["checkno"]   = $response["checkNo"];
            return json_encode($checkcode);
        }
        else
        {
            $this->error['errorMsg'] = $response["errorMessage"];
        }
        return false;
    }

    /**
     * [check_data 交管信息查询]
     * @param  array  $info [description]
     * @return [type]       [description]
     * (交管车辆查询必须)
     * 拷贝至Jiangsu_PICCKHYXSC_PC.class.php
     */
    public function check_data($info = array())
    {
        $data = Array();
        if(trim($info['checkno']) == "" || trim($info['checkcode']) == "")
        {
            $this->error['errorMsg'] = "交管车辆查询编号或验证码不能为空";
            return false;
        }

        //获取用户信息
        $writer = $this->setWriter();
        if($writer === false)
            return false;

        $post_data = Array(
            "checkNo"        => $info['checkno'],
            "checkCode"      => $info['checkcode'],
            "rateClassFlag"  => "14",
            "insuranceCode"  => "C51",
            "carMark"        => $info['license_no'],
            "vehicleFrameNo" => $info['vin_no'],
            "departmentCode" => $writer['departmentCode'],
        );

        $options = Array(
            CURLOPT_URL        => $this->_link["DMVEHICLEINFO"],
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => http_build_query($post_data),
        );
        $response = json_decode($this->request($options), true);
        if((isset($response["data"]["errorCode"]) && $response["data"]["errorCode"] == 0) || (isset($response["errorCode"]) && $response["errorCode"] == 0))
        {
            $data = Array(
                Array(
                    "carloteququality" => $response["wholeWeight"] * 1000,
                    "color"            => $response["color"],
                    "engineno"         => $response["engineNo"],
                    "enrolldate"       => date("Y-m-d", strtotime($response["vehicleRegisterDate"])),
                    "exhaustcapacity"  => $response["displacement"],
                    "ineffectualdate"  => date("Y-m-d", strtotime($response["ineffectualDate"])),
                    "licenseno"        => str_replace("-", "", $response["carMark"]),
                    "licensetype"      => $response["vehicleType"],
                    "madefactory"      => $response["madeFactory"],
                    "owner"            => $response["owner"],
                    "rackno"           => $response["rackNo"],
                    "rejectdate"       => date("Y-m-d", strtotime($response["rejectDate"])),
                    "seatcount"        => $response["limitLoadPerson"],
                    "status"           => $response["status"],
                    "vehiclebarand1"   => $response["vehicleBrand1"],
                    "vehiclecategory"  => $response["vehicleCategory"],
                    "vehiclemodel"     => $response["vehicleModel"],
                    "vehiclestyle"     => $response["vehicleStyle"],
                    "checkcode"        => $info['checkcode'],
                )
            );
            $data_json = json_encode($data);
            if(isset($data[0]["vehiclestyle"])) $_SESSION["PINGANKHYX"]["TRAFFIC"] = $data[0]["vehiclestyle"];
            return $data_json;
        }
        else
        {
            $this->error["errorMsg"] = $response["errorMessage"];
        }
        return false;
    }

    /**
     * [premium 获取算价信息]
     * @param  array  $auto     [车辆信息]
     * @param  array  $business [商业险信息]
     * @param  array  $mvtalci  [交强险信息]
     * @return [Mixed]          [成功返回算价结果数组,失败返回false]
     * (必需)
     */
    public function premium($auto = Array(), $business = Array(), $mvtalci = Array())
    {
        //获取出生年月日、性别
        $owner_info = $this->getGenderAndBirthday($auto["IDENTIFY_NO"]);
        //车牌号加"－"
        if(substr($auto["LICENSE_NO"], -6, 1) !== "-")
            $auto["LICENSE_NO"] = substr_replace($auto["LICENSE_NO"], "-", -5, 0);
        //获取用户信息
        $writer = $this->setWriter();
        if($writer === false)
            return false;

        //询价单号
        $mainQuotationNo  = "Q165400390000247067554";
        //燃油类型
        $this->fueltype = $this->fueltype ? 'A' : 0;
        //获取商业险参数
        $business_items = $this->getBusinessItems($business);
        //初始化交强险起止时间
        $mvtalci_start_time = isset($mvtalci["MVTALCI_START_TIME"]) ? $mvtalci["MVTALCI_START_TIME"] : (isset($business["BUSINESS_START_TIME"]) ? $business["BUSINESS_START_TIME"] : "0000-00-00 00:00:00");
        $mvtalci_end_time   = isset($mvtalci["MVTALCI_END_TIME"]) ? $mvtalci["MVTALCI_END_TIME"] : (isset($business["BUSINESS_END_TIME"]) ? $business["BUSINESS_END_TIME"] : "0000-00-00 00:00:00");

        $vehicleType = $this->getVehicleType($auto["VEHICLE_TYPE"], $auto["SEATS"]);

        $vehicleTypeCode      = $vehicleType["type"];
        //$ownerVehicleTypeCode = $this->vehTypeCode[$vehicleTypeCode];
        $ownerVehicleTypeCode = isset($_SESSION["PINGANKHYX"]["TRAFFIC"]) && !empty($_SESSION["PINGANKHYX"]["TRAFFIC"]) ? $_SESSION["PINGANKHYX"]["TRAFFIC"] : $this->vehTypeCode[$vehicleTypeCode];


        $vehicleClass         = $vehicleType["class"];
        $optionDisplay        = $vehicleType["name"];
        $firstSaleDate        = $auto["ENROLL_DATE"];
        $brandName            = $auto["MODEL"];
        $auto["BUSINESS_START_TIME"] = $business["BUSINESS_START_TIME"];
        $depreciation         = $this->depreciation($auto);
        //使用性质
        $usAge  = $this->getUsageAttributeCode($auto["USE_CHARACTER"]);


        /*$post_data = Array(
            "mainQuotationNo" => $mainQuotationNo,
            "aplylicantInfoList" => Array(
                Array(
                    "address"                   => "",
                    "billingAddress"            => "",
                    "billingDepositBank"        => "",
                    "billingDepositBankAccount" => "",
                    "billingPhone"              => "",
                    "certificateType"           => "01",
                    "homeTelephone"             => "",
                    "invoicePrintType"          => "03",
                    "isConfirm"                 => "5",
                    "nationality"               => "156",
                    "personnelType"             => "1",
                    "sexCode"                   => $owner_info["GENDER"],
                    "taxpayerCertificateNo"     => "",
                    "taxpayerCertificateType"   => "",
                )
            ),
            "insurantInfoList" => Array(),
            "quotationBaseInfo" => Array(
                "documentGroupId"      => "",
                "totalStandardPremium" => "0",
            ),
            "quotationList" => Array(
                Array(
                    "applyPlans"                  => "",
                    "c01CircInfoDTO"              => new stdClass(),
                    "c01IsApply"                  => "",
                    "c01RateFactorPremCalcResult" => new stdClass(),
                    "c51CircInfoDTO"              => new stdClass(),
                    "c51IsApply"                  => "",
                    "combineQuotationNo"          => "",
                    "confirmTime"                 => time()."000",
                    "displayNo"                   => "01",
                    "quoteCompleteTime"           => "",
                    "voucher" => Array(
                        "accommodationInfoDTO" => Array(
                            "ApproveChain"             => "",
                            "ApproveChainType"         => "",
                            "ApproveChainUmNum"        => "",
                            "accmmdtnBrkrChrgPrprtn"   => "",
                            "accmmdtnCmmssnChrgPrprtn" => "",
                            "accommodatePremium"       => "",
                            "accommodationDiscount"    => "",
                            "accommodationExemptFlag"  => "",
                            "accommodationReason"      => "",
                            "approveChain"             => "",
                            "brokerageFeeCheck"        => "",
                            "commissionFeeCheck"       => "",
                            "exemptCarCheck"           => "",
                            "isAbnormalCar"            => "0",
                            "isAbnormalCarCheck"       => "",
                            "reason"                   => "",
                            "totalDiscountCheck"       => false,
                            "totalDiscountCommercial"  => "",
                        ),
                        "applicantInfo" => Array(
                            "certificateTypeCode"          => "01",
                            "city"                         => "",
                            "communicationAddress"         => "",
                            "country"                      => "00",
                            "county"                       => "",
                            "invoicePrintType"             => "03",
                            "isConfirm"                    => "5",
                            "linkmodeType"                 => "03",
                            "nationality"                  => "156",
                            "personnelFlag"                => "1",
                            "province"                     => "",
                            "sexCode"                      => $owner_info["GENDER"],
                        ),
                        "assistantDriver"    => "",
                        "attachDelayEOAInfo" => Array(),
                        "baseInfo" => Array(
                            "departmentCode"         => $writer["departmentCode"],
                            "disputedSettleModeCode" => "1",
                            "rateClassFlag"          => "14",
                        ),
                        "c01BaseInfo" => Array(
                            "agentAgreementNo"                 => "",
                            "agentCode"                        => "",
                            "agentName"                        => "",
                            "brokerCode"                       => $writer["agentCode"],
                            "calculateResult" => Array(
                                "beginTime"      => $business["BUSINESS_START_TIME"],
                                "endTime"        => $business["BUSINESS_END_TIME"],
                                "shortTermRatio" => "1",
                            ),
                            "channelAdjustDeploitationFeeRate" => "",
                            "channelAdjustPoudndageRate"       => "",
                            "channeladjustPromptingFee"        => "",
                            "departmentCode"                   => $writer["departmentCode"],
                            "disputedSettleModeCode"           => "1",
                            "insuranceBeginTime"               => $business["BUSINESS_START_TIME"],
                            "insuranceEndTime"                 => $business["BUSINESS_END_TIME"],
                            "insuranceType"                    => "1",
                            "isCalculateWithoutCirc"           => "N",
                            "isReportElectronRelation"         => "",
                            "isRound"                          => "N",
                            "isaccommodation"                  => "N",
                            "lastPolicyNo"                     => "",
                            "productCode"                      => "",
                            "productName"                      => "",
                            "quoteTimes"                       => "0",
                            "rateChannelAdjustFlag"            => "",
                            "rateClassFlag"                    => "14",
                            "remark"                           => "",
                            "renewalTypeCode"                  => "0",
                            "shortTimeCoefficient"             => "1",
                            "supplementAgreementNo"            => "",
                            "totalActualPremium"               => "",
                            "totalAgreePremium"                => "",
                            "totalDiscountCommercial"          => "",
                            "totalStandardPremium"             => "",
                        ),
                        "c01DisplayRateFactorList" => Array(
                            Array(
                                "factorCode"      => "F15",
                                "factorValue"     => "0",
                                "ratingTableNo"   => "I100003001",
                            ),
                            Array(
                                "factorCode"      => "F30",
                                "factorRatioCOM"  => "",
                                "factorValue"     => "",
                                "factorValueName" => "",
                                "ratingTableNo"   => "I100003001",
                            ),
                            Array(
                                "factorCode"      => "F76",
                                "factorRatioCOM"  => "",
                                "factorValue"     => "",
                                "factorValueName" => "",
                                "ratingTableNo"   => "I100003001",
                            ),
                            Array(
                                "factorCode"      => "F34",
                                "factorRatioCOM"  => "",
                                "factorValue"     => "",
                                "factorValueName" => "",
                                "ratingTableNo"   => "I100003001",
                            ),
                            Array(
                                "factorCode"      => "F74",
                                "factorRatioCOM"  => "",
                                "factorValue"     => "",
                                "factorValueName" => "",
                                "ratingTableNo"   => "I100003001",
                            ),
                        ),
                        "c01DutyList" => $business_items,
                        "c01ExtendInfo" => Array(
                            "analogyVehicleFlag"         => "0",
                            "applyYears"                 => "",
                            "brandDetail"                => "",
                            "commercialClaimRecord"      => "09",
                            "dealerCode"                 => "",
                            "documentGroupId"            => "",
                            "expectationUnderwriteLimit" => "2",
                            "md5Result"                  => "",
                            "offerLastPolicyFlag"        => "N",
                            "ownerVehicleTypeCode"       => $ownerVehicleTypeCode,
                            "useMobileLocation"          => "N",
                        ),
                        "c01SpecialPromiseList" => Array(),
                        "c01UdwrAttachList"     => Array(),
                        "c51BaseInfo" => Array(
                            "agentAgreementNo"         => "",
                            "agentCode"                => $writer["agentCode"],
                            "agentName"                => "",
                            "brokerCode"               => "",
                            "calculateResult" => Array(
                                "beginTime"      => $mvtalci_start_time,
                                "endTime"        => $mvtalci_end_time,
                                "shortTermRatio" => "1",
                            ),
                            "departmentCode"           => $writer["departmentCode"],
                            "disputedSettleModeCode"   => "1",
                            "insuranceBeginTime"       => $mvtalci_start_time,
                            "insuranceEndTime"         => $mvtalci_end_time,
                            "insuranceType"            => "1",
                            "isCalculateWithoutCirc"   => "N",
                            "isReportElectronRelation" => "",
                            "isRound"                  => "N",
                            "lastPolicyNo"             => "",
                            "planCode"                 => "C51",
                            "quoteTimes"               => "0",
                            "rateClassFlag"            => "14",
                            "renewalTypeCode"          => "0",
                            "shortTimeCoefficient"     => "1",
                            "supplementAgreementNo"    => "",
                            "totalActualPremium"       => "",
                            "totalAgreePremium"        => "",
                            "totalDiscountCommercial"  => "",
                            "totalStandardPremium"     => "",
                        ),
                        "c51DisplayRateFactorList" => Array(
                            Array(
                                    "factorCode"     => "F54",
                                    "factorRatioCOM" => "1",
                                    "factorValue"    => "A4",
                                    "ratingTableNo"  => "",
                                ),
                            Array(
                                    "factorCode"     => "F55",
                                    "factorRatioCOM" => "",
                                    "factorValue"    => "V4",
                                    "ratingTableNo"  => "",
                                ),
                            Array(
                                    "factorCode"     => "F999",
                                    "factorRatioCOM" => "0",
                                    "factorValue"    => "",
                                    "ratingTableNo"  => "",
                                ),
                        ),
                        "c51DutyList" => Array(
                            Array(
                                    "dutyCode"      => "45",
                                    "insuredAmount" => "110000",
                                ),
                            Array(
                                    "dutyCode"      => "46",
                                    "insuredAmount" => "10000",
                                ),
                            Array(
                                    "dutyCode"      => "47",
                                    "insuredAmount" => "2000",
                                ),
                        ),
                        "c51ExtendInfo" => Array(
                            "brandDetail"                => "",
                            "dealerCode"                 => "",
                            "documentGroupId"            => "",
                            "expectationUnderwriteLimit" => "2",
                            "md5Result"                  => null,
                            "ownerVehicleTypeCode"       => $ownerVehicleTypeCode,
                            "useMobileLocation"          => "N",
                        ),
                        "c51FleetInfoDTO"       => Array(),
                        "c51SpecialPromiseList" => Array(),
                        "c51UdwrAttachList"     => Array(),
                        "insurantInfo"          => Array(
                            "certificateTypeCode"          => "01",
                            "city"                         => "",
                            "communicationAddress"         => "",
                            "country"                      => "00",
                            "county"                       => "",
                            "encryptInfoDTO"               => Array(),
                            "isShowCustomerHistory"        => "",
                            "linkmodeType"                 => "03",
                            "nationality"                  => "156",
                            "personnelFlag"                => "1",
                            "province"                     => "",
                            "sameAsText"                   => "--请选择--",
                            "sexCode"                      => $owner_info["GENDER"],
                        ),
                        "mainDriver"  => "",
                        "ownerDriver" => Array(
                            "birthday"                     => $owner_info["BIRTHDAY"],
                            "certificateTypeCode"          => "01",
                            "certificateTypeNo"            => $auto["IDENTIFY_NO"],
                            "clientNo"                     => "",
                            "communicationAddress"         => "",
                            "encryptedAddress"             => "#undefined",
                            "encryptedEmail"               => "#undefined",
                            "encryptedLinkmodeNum"         => "",
                            "isShowCustomerHistory"        => "1",
                            "linkmodeNum"                  => "",
                            "linkmodeType"                 => "03",
                            "mobileTelephone"              => "",
                            "nationality"                  => "156",
                            "personnelFlag"                => "1",
                            "personnelName"                => $auto["OWNER"],
                            "sexCode"                      => $owner_info["GENDER"],
                        ),
                        "paymentInfo"  => null,
                        "propertyList" => Array(),
                        "receiverInfo" => Array(
                            "country"         => "01",
                            "province"        => "",
                            "receiveAddress"  => "",
                            "receiveTimeZone" => "0",
                            "sendWay"         => "03",
                        ),
                        "saleAgentList" => Array(),
                        "saleInfo" => Array(
                            "businessSourceCode"       => $writer["businessSourceCode"],
                            "businessSourceDetailCode" => $writer["businessSourceDetailCode"],
                            "channelSourceCode"        => $writer["channelSourceCode"],
                            "channelSourceDetailCode"  => $writer["channelSourceDetailCode"],
                            "departmentCode"           => $writer["departmentCode"],
                            "developFlg"               => "N",
                            "opportunityCode"          => "",
                            "opportunityName"          => "",
                            "saleAgentCode"            => $writer["saleAgentCode"],
                        ),
                        "thirdCarBusinessInfoDTO" => Array(
                            "agentLicenseNo" => "0",
                            "agentName"      => "",
                            "cardNo"         => "0",
                            "companyCode"    => "",
                            "dateValidBegin" => "",
                            "dateValidEnd"   => "",
                            "saleAddr"       => "",
                            "saleName"       => "",
                            "usbkeyNo"       => "",
                        ),
                        "vehicleTarget" => Array(
                            "addr" => Array(
                                "country" => "01",
                            ),
                            "analogyPrice"              => "0",
                            "autoModelCode"             => $auto["MODEL_CODE"],
                            "autoModelName"             => $auto["MODEL"],
                            "brandName"                 => $brandName,
                            "brandParaOutYear"          => $firstSaleDate,
                            "cache" => Array(
                                "brand" => "",
                            ),
                            "changeOwnerFlag"           => "0",
                            "circVehicleChineseBrand"   => $brandName,
                            "circVehicleModel"          => $auto["MODEL"],
                            "energyType"                => "0",
                            "engineNo"                  => $auto["ENGINE_NO"],
                            "exhaustCapability"         => $auto["ENGINE"],
                            "firstRegisterDate"         => $auto["ENROLL_DATE"],
                            "fleetMark"                 => "0",
                            "fleetNo"                   => "",
                            "isAbnormalCar"             => "0",
                            "isMiniVehicle"             => "N",
                            "licenceTypeCode"           => $this->licenseType[$auto["LICENSE_TYPE"]],
                            "licenceTypeName"           => $this->licenseName[$auto["LICENSE_TYPE"]],
                            "loanVehicleFlag"           => "0",
                            "modifyAutoModelName"       => $auto["MODEL"],
                            "ownerVehicleTypeCode"      => $ownerVehicleTypeCode,
                            "ownerVehicleTypeDesc"      => $this->vehTypeCodeName[$ownerVehicleTypeCode],
                            "ownershipAttributeCode"    => $this->ownershipAttributeCode[$auto["USE_CHARACTER"]],
                            "price"                     => $auto["BUYING_PRICE"],
                            "purchasePriceDefaultValue" => $auto["BUYING_PRICE"],
                            "specialCarFlag"            => "",
                            "specialCarLicenseChoice"   => "",
                            "transferDate"              => "",
                            "usageAttributeCode"        => $usAge,
                            "vehicleClassCode"          => $vehicleClass,
                            "vehicleFrameNo"            => $auto["VIN_NO"],
                            "vehicleLicenceCode"        => $auto["LICENSE_NO"],
                            "vehicleLossInsuredValue"   => $depreciation,
                            "vehicleSeats"              => $auto["SEATS"],
                            "vehicleTonnages"           => $auto["TONNAGE"],
                            "vehicleTypeCode"           => $vehicleTypeCode,
                            "vehicleTypeDetailCode"     => "",
                            "vehicleTypeName"           => $optionDisplay,
                            "verifyCode"                => "",
                            "wholeWeight"               => $auto["KERB_MASS"] / 1000,
                        ),
                        "vehicleTaxInfo" => Array(
                            "deduction"              => "",
                            "deductionDueCode"       => "",
                            "deductionDueProportion" => "",
                            "deductionDueType"       => "",
                            "delinquentTaxDue"       => "1",
                            "fuelType"               => $this->fueltype,
                            "isNeedAddTax"           => "02",
                            "taxPayerId"             => $auto["IDENTIFY_NO"],
                            "taxType"                => "1",
                            "totalTaxMoney"          => "",
                        ),
                    ),
                ),
            ),
            "saleInfo"        => Array (
                "agentInfoList"            => Array(
                    Array(
                            "agencyCode"             => "",
                            "agencySaleName"         => "",
                            "agencySaleProfCertifNo" => "",
                            "agentAgreementNo"       => "",
                            "agentCode"              => $writer["agentCode"],
                            "supplementAgreementNo"  => "",
                        )
                ),
                "brokerInfoList" => Array(
                    Array(
                            "brokerCode" => $writer["brokerCode"],
                        )
                ),
                "businessSourceCode"       => $writer["businessSourceCode"],
                "businessSourceDetailCode" => $writer["businessSourceDetailCode"],
                "channelSourceCode"        => $writer["channelSourceCode"],
                "channelSourceDetailCode"  => $writer["channelSourceDetailCode"],
                "dealerCode"               => $writer["dealerCodes"],
                "departmentCode"           => $writer["departmentCode"],
                "employeeInfoList" => Array(
                    Array(
                            "employeeCode" => $writer["saleAgentCode"],
                            "employeeProfCertifNo" => "",
                        )
                ),
                "partnerInfoList" => Array(
                    Array(
                            "partnerType" => $writer["partnerType"],
                            "partnerCode" => $writer["partnerWorkNetCode"],
                        )
                ),
                "primaryIntroducerInfo" => null,


            ),
            "sendInfo" => Array(
                "country"         => "01",
                "province"        => "",
                "receiveAddress"  => "",
                "receiveTimeZone" => "0",
                "sendWay"         => "03",
            ),
        );*/
        $post_data = Array(
            "mainQuotationNo" => $mainQuotationNo,
            "saleInfo"        => Array (
                "departmentCode"           => $writer["departmentCode"],
                "dealerCode"               => $writer["dealerCodes"],
                "businessSourceCode"       => $writer["businessSourceCode"],
                "businessSourceDetailCode" => $writer["businessSourceDetailCode"],
                "channelSourceCode"        => $writer["channelSourceCode"],
                "channelSourceDetailCode"  => $writer["channelSourceDetailCode"],
                "agentInfoList"            => Array(
                    Array(
                            "agencyCode"             => "",
                            "agentCode"              => $writer["agentCode"],
                            "agentAgreementNo"       => "",
                            "supplementAgreementNo"  => "",
                            "agencySaleName"         => "",
                            "agencySaleProfCertifNo" => "",
                        )
                ),
                "brokerInfoList" => Array(
                    Array(
                            "brokerCode" => $writer["brokerCode"],
                        )
                ),
                "employeeInfoList" => Array(
                    Array(
                            "employeeCode" => $writer["saleAgentCode"],
                            "employeeProfCertifNo" => "",
                        )
                ),
                "primaryIntroducerInfo" => null,
                "partnerInfoList" => Array(
                    Array(
                            "partnerType" => $writer["partnerType"],
                            "partnerCode" => $writer["partnerWorkNetCode"],
                        )
                ),
            ),
            "quotationBaseInfo" => Array(
                "totalStandardPremium" => "0",
                "documentGroupId"      => "",
            ),
            "sendInfo" => Array(
                "sendWay"         => "03",
                "country"         => "01",
                "province"        => "",
                "receiveTimeZone" => "0",
                "receiveAddress"  => "",
            ),
            "aplylicantInfoList" => Array(
                Array(
                    "sexCode"                   => $owner_info["GENDER"],
                    "nationality"               => "156",
                    "personnelType"             => "1",
                    "certificateType"           => "01",
                    "homeTelephone"             => "",
                    "address"                   => "",
                    "isConfirm"                 => "5",
                    "encryptInfoDTO"            => null,
                    "invoicePrintType"          => "03",
                    "taxpayerCertificateType"   => "",
                    "taxpayerCertificateNo"     => "",
                    "billingAddress"            => "",
                    "billingPhone"              => "",
                    "billingDepositBank"        => "",
                    "billingDepositBankAccount" => "",
                )
            ),
            //"insurantInfoList" => Array(),
            "quotationList" => Array(
                Array(
                "voucher" => Array(
                        "accommodationInfoDTO" => null,
                        /*"accommodationInfoDTO" => Array(
                            "ApproveChain"             => "",
                            "ApproveChainType"         => "",
                            "ApproveChainUmNum"        => "",
                            "accmmdtnBrkrChrgPrprtn"   => "",
                            "accmmdtnCmmssnChrgPrprtn" => "",
                            "accommodatePremium"       => "",
                            "accommodationDiscount"    => "",
                            "accommodationExemptFlag"  => "",
                            "accommodationReason"      => "",
                            "approveChain"             => "",
                            "brokerageFeeCheck"        => "",
                            "commissionFeeCheck"       => "",
                            "exemptCarCheck"           => "",
                            "isAbnormalCar"            => "0",
                            "isAbnormalCarCheck"       => "",
                            "reason"                   => "",
                            "totalDiscountCheck"       => false,
                            "totalDiscountCommercial"  => "",
                        ),*/
                        "ownerDriver" => Array(
                            "certificateTypeCode"          => "01",
                            "linkmodeType"                 => "03",
                            "sexCode"                      => $owner_info["GENDER"],
                            "personnelFlag"                => "1",
                            "nationality"                  => "156",
                            "certificateTypeNo"            => $auto["IDENTIFY_NO"],
                            "birthday"                     => $owner_info["BIRTHDAY"],
                            "personnelName"                => $auto["OWNER"],
                            "clientNo"                     => "",
                            "encryptedAddress"             => "#undefined",
                            "email"                        => "#undefined",
                            "encryptedEmail"               => "#undefined",
                            "isShowCustomerHistory"        => "1",
                            "linkmodeNum"                  => "",
                            "encryptedLinkmodeNum"         => "",
                            "mobileTelephone"              => "",

                            //"communicationAddress"         => "",
                        ),
                        "applicantInfo" => Array(
                            "personnelFlag"                => "1",
                            "invoicePrintType"             => "03",
                            "country"                      => "00",
                            "province"                     => "",
                            "city"                         => "",
                            "county"                       => "",
                            "communicationAddress"         => "",
                            "certificateTypeCode"          => "01",
                            "sexCode"                      => $owner_info["GENDER"],
                            "linkmodeType"                 => "03",
                            "nationality"                  => "156",
                            "isConfirm"                    => "5",
                        ),
                        "insurantInfo"          => Array(
                            "certificateTypeCode"          => "01",
                            "linkmodeType"                 => "03",
                            "sexCode"                      => $owner_info["GENDER"],
                            "personnelFlag"                => "1",
                            "country"                      => "00",
                            "province"                     => "",
                            "city"                         => "",
                            "county"                       => "",
                            "communicationAddress"         => "",
                            "nationality"                  => "156",
                            //"encryptInfoDTO"               => Array(),
                            "sameAsText"                   => "--请选择--",
                            "isShowCustomerHistory"        => "",
                        ),
                        "mainDriver"  => "",
                        "assistantDriver"    => "",
                        //"attachDelayEOAInfo" => Array(),
                        "baseInfo" => Array(
                            "disputedSettleModeCode" => "1",
                            "departmentCode"         => $writer["departmentCode"],
                            "rateClassFlag"          => "14",
                        ),
                        "c01BaseInfo" => Array(
                            "calculateResult" => Array(
                                "shortTermRatio" => "1",
                                "beginTime"      => $business["BUSINESS_START_TIME"],
                                "endTime"        => $business["BUSINESS_END_TIME"],
                            ),
                            "insuranceBeginTime"               => $business["BUSINESS_START_TIME"],
                            "insuranceEndTime"                 => $business["BUSINESS_END_TIME"],
                            "shortTimeCoefficient"             => "1",
                            "isReportElectronRelation"         => "",
                            "renewalTypeCode"                  => "0",
                            "quoteTimes"                       => "0",
                            "isCalculateWithoutCirc"           => "N",
                            "departmentCode"                   => $writer["departmentCode"],
                            "brokerCode"                       => $writer["agentCode"],
                            "agentCode"                        => "",
                            "agentName"                        => "",
                            "agentAgreementNo"                 => "",
                            "supplementAgreementNo"            => "",
                            "remark"                           => "",
                            "productCode"                      => "",
                            "productName"                      => "",
                            "disputedSettleModeCode"           => "1",
                            "isRound"                          => "N",
                            "insuranceType"                    => "1",
                            "rateClassFlag"                    => "14",
                            "rateChannelAdjustFlag"            => "",
                            "channelAdjustPoudndageRate"       => "",
                            "channelAdjustDeploitationFeeRate" => "",
                            "channeladjustPromptingFee"        => "",
                            "isaccommodation"                  => "N",
                            "totalAgreePremium"                => "",
                            "totalDiscountCommercial"          => "",
                            "totalActualPremium"               => "",
                            "totalStandardPremium"             => "",
                            "lastPolicyNo"                     => "",
                        ),
                        "c01ExtendInfo" => Array(
                            "commercialClaimRecord"      => "09",
                            "applyYears"                 => "",
                            "offerLastPolicyFlag"        => "N",
                            "brandDetail"                => "",
                            "ownerVehicleTypeCode"       => $ownerVehicleTypeCode,
                            "useMobileLocation"          => "N",
                            "dealerCode"                 => "",
                            "expectationUnderwriteLimit" => "2",
                            "partnerWorknetCode"         => $writer["partnerWorkNetCode"],
                            "analogyVehicleFlag"         => "0",
                            "documentGroupId"            => "",
                            //"md5Result"                  => "",
                        ),
                        "c51ExtendInfo" => Array(
                            "brandDetail"                => "",
                            "dealerCode"                 => "",
                            "ownerVehicleTypeCode"       => $ownerVehicleTypeCode,
                            "expectationUnderwriteLimit" => "2",
                            "useMobileLocation"          => "N",
                            "partnerWorknetCode"         => $writer["partnerWorkNetCode"],
                            "documentGroupId"            => "",
                            //"md5Result"                  => null,
                        ),
                        "c51BaseInfo" => Array(
                            "calculateResult" => Array(
                                "shortTermRatio" => "1",
                                "beginTime"      => $mvtalci_start_time,
                                "endTime"        => $mvtalci_end_time,
                            ),
                            "insuranceBeginTime"       => $mvtalci_start_time,
                            "insuranceEndTime"         => $mvtalci_end_time,
                            "shortTimeCoefficient"     => "1",
                            "isReportElectronRelation" => "",
                            "renewalTypeCode"          => "0",
                            "quoteTimes"               => "0",
                            "formatType"               => "06",
                            "planCode"                 => "C51",
                            "isCalculateWithoutCirc"   => "N",
                            "departmentCode"           => $writer["departmentCode"],
                            "brokerCode"               => "",
                            "agentCode"                => $writer["agentCode"],
                            "agentName"                => "",
                            "agentAgreementNo"         => "",
                            "supplementAgreementNo"    => "",
                            "disputedSettleModeCode"   => "1",
                            "isRound"                  => "N",
                            "insuranceType"            => "1",
                            "rateClassFlag"            => "14",
                            "totalAgreePremium"        => "",
                            "totalDiscountCommercial"  => "",
                            "totalActualPremium"       => "",
                            "totalStandardPremium"     => "",
                            "lastPolicyNo"             => "",
                        ),
                        "saleInfo" => Array(
                            "opportunityCode"          => "",
                            "opportunityName"          => "",
                            "developFlg"               => "N",
                            "departmentCode"           => $writer["departmentCode"],
                            "businessSourceCode"       => $writer["businessSourceCode"],
                            "businessSourceDetailCode" => $writer["businessSourceDetailCode"],
                            "channelSourceCode"        => $writer["channelSourceCode"],
                            "channelSourceDetailCode"  => $writer["channelSourceDetailCode"],
                            "saleAgentCode"            => $writer["saleAgentCode"],
                        ),
                        "c01DisplayRateFactorList" => Array(
                            Array(
                                "factorCode"      => "F76",
                                "ratingTableNo"   => "I100003001",
                                "factorRatioCOM"  => "",
                                "factorValue"     => "",
                                "factorValueName" => "",
                            ),
                            Array(
                                "factorCode"      => "F34",
                                "ratingTableNo"   => "I100003001",
                                "factorRatioCOM"  => "",
                                "factorValue"     => "",
                                "factorValueName" => "",
                            ),
                            Array(
                                "factorCode"      => "F15",
                                "ratingTableNo"   => "I100003001",
                                "factorValue"     => "0",
                            ),
                            Array(
                                "factorCode"      => "F30",
                                "ratingTableNo"   => "I100003001",
                                "factorRatioCOM"  => "",
                                "factorValue"     => "",
                                "factorValueName" => "",
                            ),
                            Array(
                                "factorCode"      => "F74",
                                "ratingTableNo"   => "I100003001",
                                "factorRatioCOM"  => "",
                                "factorValue"     => "",
                                "factorValueName" => "",
                            ),
                        ),
                        "c01DutyList" => $business_items,
                        "receiverInfo" => Array(
                            "sendWay"         => "03",
                            "country"         => "01",
                            "province"        => "",
                            "receiveTimeZone" => "0",
                            "receiveAddress"  => "",
                        ),
                        "vehicleTarget" => Array(
                            "cache" => Array(
                                "brand" => "",
                            ),
                            "energyType"                => "0",
                            "specialCarLicenseChoice"   => "",
                            "specialCarFlag"            => "",
                            "addr" => Array(
                                "country" => "01",
                            ),
                            "ownerVehicleTypeDesc"      => $this->vehTypeCodeName[$ownerVehicleTypeCode],
                            "changeOwnerFlag"           => "0",
                            "transferDate"              => "",
                            "vehicleLossInsuredValue"   => $depreciation,
                            "purchasePriceDefaultValue" => $auto["BUYING_PRICE"],
                            "firstRegisterDate"         => $auto["ENROLL_DATE"],
                            "loanVehicleFlag"           => "0",
                            "vehicleFrameNo"            => $auto["VIN_NO"],
                            "autoModelCode"             => $auto["MODEL_CODE"],
                            "autoModelName"             => $auto["MODEL"],
                            "modifyAutoModelName"       => $auto["MODEL"],
                            "circVehicleModel"          => $auto["MODEL"],
                            "circVehicleChineseBrand"   => $brandName,
                            "vehicleSeats"              => $auto["SEATS"],
                            "vehicleTonnages"           => $auto["TONNAGE"],
                            "exhaustCapability"         => $auto["ENGINE"],
                            "price"                     => $auto["BUYING_PRICE"],
                            "analogyPrice"              => "0",
                            "vehicleTypeCode"           => $vehicleTypeCode,
                            "brandParaOutYear"          => $firstSaleDate,
                            "ownerVehicleTypeCode"      => $ownerVehicleTypeCode,
                            "vehicleTypeName"           => $optionDisplay,
                            "vehicleClassCode"          => $vehicleClass,
                            "vehicleTypeDetailCode"     => "",
                            "licenceTypeCode"           => $this->licenseType[$auto["LICENSE_TYPE"]],
                            "licenceTypeName"           => $this->licenseName[$auto["LICENSE_TYPE"]],
                            "engineNo"                  => $auto["ENGINE_NO"],
                            "vehicleLicenceCode"        => $auto["LICENSE_NO"],
                            "wholeWeight"               => $auto["KERB_MASS"] / 1000,
                            "usageAttributeCode"        => $usAge,
                            "ownershipAttributeCode"    => $this->ownershipAttributeCode[$auto["USE_CHARACTER"]],
                            "brandName"                 => $brandName,
                            "fleetMark"                 => "0",
                            "fleetNo"                   => "",
                            "isMiniVehicle"             => "N",
                            "isAbnormalCar"             => "0",
                            //"verifyCode"                => "",
                        ),
                        "c51DisplayRateFactorList" => Array(
                            Array(
                                    "factorCode"     => "F54",
                                    "factorValue"    => "A4",
                                    "factorRatioCOM" => "1",
                                    "ratingTableNo"  => "",
                                ),
                            Array(
                                    "factorCode"     => "F55",
                                    "factorValue"    => "V4",
                                    "factorRatioCOM" => "",
                                    "ratingTableNo"  => "",
                                ),
                            Array(
                                    "factorCode"     => "F999",
                                    "factorValue"    => "",
                                    "factorRatioCOM" => "0",
                                    "ratingTableNo"  => "",
                                ),
                        ),
                        "c51DutyList" => Array(
                            Array(
                                    "dutyCode"      => "45",
                                    "insuredAmount" => "110000",
                                ),
                            Array(
                                    "dutyCode"      => "46",
                                    "insuredAmount" => "10000",
                                ),
                            Array(
                                    "dutyCode"      => "47",
                                    "insuredAmount" => "2000",
                                ),
                        ),
                        "vehicleTaxInfo" => Array(
                            "taxType"                => "1",
                            "delinquentTaxDue"       => "1",
                            "taxPayerId"             => $auto["IDENTIFY_NO"],
                            "isNeedAddTax"           => "02",
                            "energyType"             => "",
                            "deduction"              => "",
                            "deductionDueProportion" => "",
                            "totalTaxMoney"          => "",
                            //"deductionDueCode"       => "",
                            //"deductionDueType"       => "",
                            "fuelType"               => $this->fueltype,
                        ),
                        "paymentInfo"  => null,
                        "c01SpecialPromiseList" => Array(),
                        "c51SpecialPromiseList" => Array(),
                        //"c01UdwrAttachList"     => Array(),
                        //"c51FleetInfoDTO"       => Array(),
                        //"c51UdwrAttachList"     => Array(),
                        //"propertyList" => Array(),
                        //"saleAgentList" => Array(),
                        /*"thirdCarBusinessInfoDTO" => Array(
                            "agentLicenseNo" => "0",
                            "agentName"      => "",
                            "cardNo"         => "0",
                            "companyCode"    => "",
                            "dateValidBegin" => "",
                            "dateValidEnd"   => "",
                            "saleAddr"       => "",
                            "saleName"       => "",
                            "usbkeyNo"       => "",
                        ),*/
                    ),
                    "c01RateFactorPremCalcResult" => new stdClass(),
                    "c01IsApply"                  => "",
                    "c51IsApply"                  => "",
                    "quoteCompleteTime"           => "",
                    "confirmTime"                 => time()."000",
                    "combineQuotationNo"          => "",
                    "c01CircInfoDTO"              => new stdClass(),
                    "c51CircInfoDTO"              => new stdClass(),
                    "displayNo"                   => "01",
                    "applyPlans"                  => "",
                ),
            ),
        );
        if(!empty($mvtalci)){
            $post_data["quotationList"][0]["voucher"]["c51BaseInfo"]["planCode"] = "C51";
            $post_data["quotationList"][0]["applyPlans"] .= "C51";
        }

        if(!empty($business["POLICY"]["BUSINESS_ITEMS"])){
            $post_data["quotationList"][0]["voucher"]["c01BaseInfo"]["planCode"] = "C01";
            $post_data["quotationList"][0]["applyPlans"] .= "C01";
        }

        /*if(empty($mvtalci) && empty($business["POLICY"]["BUSINESS_ITEMS"]))
        {
            $this->error["errorMsg"] = "交强险和商业险必须选择一个。";
            return false;
        }*/

        $data_json = json_encode($post_data);
        $header = Array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json)
        );
        $options = Array(
            CURLOPT_URL        => $this->_link["APPLY"],
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POST       => 1,
            CURLOPT_POSTFIELDS => $data_json,
        );
        $response = $this->decodeMessage($this->request($options));
        if(!$this->premiumError($response)) return false;
        //提示车型选择的时候获取车辆信息再次提交
        if(isset($response["processType"]) && $response["processType"] == "chooseVehType")
        {
            $post_data["quotationList"][0]["accommodIsOpen"]         = $response["accommodIsOpen"];
            $post_data["quotationList"][0]["applyQueryResult"]       = $response["applyQueryResult"];
            $post_data["quotationList"][0]["confirmTime"]            = null;//$resen["confirmTime"]
            $post_data["quotationList"][0]["isAutoSaveFlag"]         = false;
            $post_data["quotationList"][0]["message"]                = $response["message"];
            $post_data["quotationList"][0]["processType"]            = $response["processType"];
            $post_data["quotationList"][0]["selectCircAutoModelCode"]= $auto["MODEL_CODE"];
            $post_data["quotationList"][0]["applyQueryResult"]["circInfoDTO"]["circCommissionDTO"] = isset($post_data["quotationList"][0]["applyQueryResult"]["circInfoDTO"]["circCommissionDTO"]) ? (object)$post_data["quotationList"][0]["applyQueryResult"]["circInfoDTO"]["circCommissionDTO"] : "";

            $data_json = json_encode($post_data);
            $header = Array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_json)
            );
            $options = Array(
                CURLOPT_URL        => $this->_link["APPLY"],
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POST       => 1,
                CURLOPT_POSTFIELDS => $data_json,
            );
            $response = $this->decodeMessage($this->request($options));
        }
        //提示续保信息选择的时候获取续保信息再次提交
        if(isset($response["processType"]) && $response["processType"] == "renewalAndC51DisTax")
        {
            if(!empty($business["POLICY"]["BUSINESS_ITEMS"]))
            {
                $response["voucher"]["c01BaseInfo"]["renewalTypeCode"] = 1;
                $response["voucher"]["c01BaseInfo"]["lastPolicyNo"]    = isset($response["c01PolicyList"][0]["policyNo"]) ? $response["c01PolicyList"][0]["policyNo"] : "";
            }
            if(!empty($mvtalci))
            {
                $response["voucher"]["c51BaseInfo"]["renewalTypeCode"] = 1;
                $response["voucher"]["c51BaseInfo"]["lastPolicyNo"]    = $response["c51PolicyList"][0]["policyNo"];
            }

            $response["applyQueryResult"]["circInfoDTO"]["circCommissionDTO"] = (object)$response["applyQueryResult"]["circInfoDTO"]["circCommissionDTO"];
            $post_data["quotationList"][0]                = $response;

            $data_json = json_encode($post_data);
            $header = Array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_json)
            );
            $options = Array(
                CURLOPT_URL        => $this->_link["APPLY"],
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POST       => 1,
                CURLOPT_POSTFIELDS => $data_json,
            );
            $response = $this->decodeMessage($this->request($options));
        }
        if(!$this->premiumError($response)) return false;
        if(isset($response["processType"]) && $response["processType"] == "allDone")
        {
            //交强险
            if(!empty($mvtalci))
            {
                $results['MVTALCI']['MVTALCI_PREMIUM']    = "0.00";
                $results['MVTALCI']['TRAVEL_TAX_PREMIUM'] = "0.00";
                $results['MVTALCI']['MVTALCI_DISCOUNT']   = "1.000";
                $results['MVTALCI']['MVTALCI_PREMIUM']    = $response["voucher"]["c51BaseInfo"]["totalActualPremium"];//交强险保费
                $results['MVTALCI']['TRAVEL_TAX_PREMIUM'] = $response["voucher"]["vehicleTaxInfo"]["totalTaxMoney"];//车船税
                $results['MVTALCI']['MVTALCI_DISCOUNT']   = $response["voucher"]["c51BaseInfo"]["totalActualPremium"] / $response["voucher"]["c51BaseInfo"]["totalStandardPremium"];//交强险折扣
                $results['MVTALCI']['MVTALCI_START_TIME'] = $response["voucher"]["c51BaseInfo"]["insuranceBeginTime"];//交强险生效时间
                $results['MVTALCI']['MVTALCI_END_TIME']   = $response["voucher"]["c51BaseInfo"]["insuranceEndTime"];//交强险结束时间
            }
            //商业险
            if(!empty($business["POLICY"]["BUSINESS_ITEMS"]))
            {
                $results['BUSINESS']['BUSINESS_START_TIME']       = $response["voucher"]["c01BaseInfo"]["insuranceBeginTime"];//商业险生效时间
                $results['BUSINESS']['BUSINESS_END_TIME']         = $response["voucher"]["c01BaseInfo"]["insuranceEndTime"];//商业险结束时间
                $results['MESSAGE']                               = $response["message"];//商业险投保信息

                $results['BUSINESS']['BUSINESS_PREMIUM']          = $response["voucher"]["c01BaseInfo"]["totalActualPremium"];//商业险标准保费合计
                foreach ($response["voucher"]["c01DisplayRateFactorList"] as $key => $item)
                {
                    if($item["factorCode"] == "F999")
                    {
                        $results['BUSINESS']['BUSINESS_DISCOUNT'] = $response["voucher"]["c01DisplayRateFactorList"][$key]["factorRatioVHL"];//商业险折扣
                    }
                }
                $results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM'] = $response["voucher"]["c01BaseInfo"]["totalActualPremium"]; //商业险扣后保费合计

                $flipDutyCode = array_flip($this->duty_code);
                foreach ($flipDutyCode as $val) {
                    $results["BUSINESS"]["BUSINESS_ITEMS"][$val]["PREMIUM"] = "0.00";
                }
                foreach ($response["voucher"]["c01DutyList"] as $val) {
                    $results["BUSINESS"]["BUSINESS_ITEMS"][$flipDutyCode[$val["dutyCode"]]]["PREMIUM"] = $val["totalActualPremium"];
                }
            }
        }
        $results['BUSINESS']['INSURANCE_COMPANY'] = self::company;
        return $results;
    }

    /**
     * [getLastError 返回最后一次错误信息]
     * @return [Array] [返回保存的错误信息]
     * (必需)
     */
    public function getLastError()
    {
        return $this->error;
    }


}