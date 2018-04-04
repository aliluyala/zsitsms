<?php
/**
 * 项目:           车险保费在线计算接口
 * 文件名:         PICCNEISC_PC.class.php
 * 版权所有：      成都启点科技有限公司.
 * 作者：          Liang YuLin
 * 版本：          1.0.0
 *
 * 中国人保四川客户三代管理系统算价接口
 *
 **/
set_time_limit(0);      /*设置超时时间*/
session_start();        /*开启session*/
class PICCNEISC_PC
{

    const formFile = 'Calculate.tpl';
    private $error = "";/*设置错误信息成员属性*/
    private $setItems = array(
        'username' => '人保三代账号',//'A519400237',
        'password' => '人保三代密码',//'JULE0136',
    );


    /*使用性质 000不区分营业非营业 111出租租凭 112城市公交 113公路客运 114旅游客运 120营业货车 121 营业挂车 180 运输型拖拉机 190 其他营业车辆 211 家庭自用汽车  212 非营业企业客车 213 非营业机关，事业团体客车 220 非营业货车 221 非营业挂车 280 兼用型拖拉机 290 其他非营业车辆*/

    /*使用性质代码转换*/
    private $use_character = Array(
                    'NON_OPERATING_PRIVATE'           =>Array(0=>'家庭自用汽车',1=>'211'),
                    'NON_OPERATING_ENTERPRISE'        =>Array(0=>'非营业企业客车',1=>'212'),
                    'NON_OPERATING_AUTHORITY'         =>Array(0=>'非营业机关，事业团体客车',1=>'213'),
                    'OPERATING_LEASE_RENTAL'          =>Array(0=>'出租租凭',1=>'111'),
                    'OPERATING_CITY_BUS'              =>Array(0=>'城市公交',1=>'112'),
                    'OPERATING_HIGHWAY_BUS'           =>Array(0=>'公路客运',1=>'113'),
                    'NON_OPERATING_TRUCK'             =>Array(0=>'非营业货车',1=>'220'),
                    'OPERATING_TRUCK'                 =>Array(0=>'营业货车',1=>'120'),
                    'DUAL_PURPOSE_TRACTOR'            =>Array(0=>'兼用型拖拉机',1=>'280'),
                    'TRANSPORT_TRACTOR'               =>Array(0=>'运输型拖拉机',1=>'180'),
                    'GENERAL'                         =>Array(0=>'不区分营业非营业',1=>'000'),
                    'OPERATING_OTHER'                 =>Array(0=>'其他营业车辆',1=>'190'),
                    'NON_OPERATING_OTHER'             =>Array(0=>'其他非营业车辆',1=>'290'),
                    );
    /*条款类型 F41 机动车综合条款（非营业用汽车产品） F42 机动车综合条款（家庭自用汽车产品） F43 机动车综合条款（营业用汽车产品）F44 摩托车、拖拉机条款（摩托车产品） F45 摩托车、拖拉机条款（拖拉机产品）F46 特种车产品*/
    private $clause_Type = Array(
                    'NON_OPERATING_PRIVATE'             =>'F42',
                    'NON_OPERATING_ENTERPRISE'          =>'F41',
                    'NON_OPERATING_AUTHORITY'           =>'F41',
                    'OPERATING_LEASE_RENTAL'            =>'F43',
                    'OPERATING_CITY_BUS'                =>'F43',
                    'OPERATING_HIGHWAY_BUS'             =>'F43',
                    'NON_OPERATING_TRUCK'               =>'F41',
                    'OPERATING_TRUCK'                   =>'F43',
                    'NONE_OPERATING_TRAILER'            =>'F42',
                    'SPECIAL_AUTO'                      =>'F42',
                    'DUAL_PURPOSE_TRACTOR'              =>'F42',
                    'OPERATING_LOW_SPEED_TRUCK'         =>'F43',
                    'NON_OPERATING_LOW_SPEED_TRUCK'     =>'F41',
        );

    /*车辆种类 A01客车 B01 货车 B02半挂牵引车 B11三轮汽车 B12低速货车 B13客货两用车 B21 自卸货车 B91 货车挂车 C01 油罐车 C02气罐车 C03液罐车 C04冷藏车  C11罐车挂车 C20 推土车 C22清障车 C23清扫车 C24清洁车 C25起重车 C26装卸车 C27升降车  C28混凝土搅拌车 C29挖掘车 C30专业拖车 C31特种车二挂车 C39特种车二类其他 C41 电视转播车 C42消防车 C43医疗车 C44油气田操作用车 C45压路车 C46矿山车 C47运钞车 C48 救护车 C49 救护车 C50雷达车 C51 X光检查车 C52电信抢修车/电信工程车 C53 电力抢修车/电力工程车 C54 专业净水车*/
    /*车辆种类转换*/
    private $vehicle_type = Array(
                    'PASSENGER_CAR'        =>Array(0=>'A01',1=>'客车'),
                    'TRUCK'                =>Array(0=>'B01',1=>'货车'),
                    'SEMI_TRAILER_TOWING'  =>Array(0=>'B02',1=>'半挂牵引车'),
                    'THREE_WHEELED'        =>Array(0=>'B11',1=>'三轮汽车'),
                    'LOW_SPEED_TRUCK'      =>Array(0=>'B12',1=>'低速货车'),
                    'VAN'                  =>Array(0=>'B13',1=>'客货两用车'),
                    'DUMP_TRAILER'         =>Array(0=>'B91',1=>'货车挂车'),
                    'FUEL_TANK_CAR'        =>Array(0=>'C01',1=>'油罐车'),
                    'TANK_CAR'             =>Array(0=>'C02',1=>'气罐车'),
                    'THE_LIQUID_TANK'      =>Array(0=>'C03',1=>'液罐车'),
                    'REFRIGERATED'         =>Array(0=>'C04',1=>'冷藏车'),
                    'TANK_TRAILER'         =>Array(0=>'C11',1=>'罐车挂车'),
                    'BULLDOZER'            =>Array(0=>'C20',1=>'推土车'),
                    'WRECKER'              =>Array(0=>'C22',1=>'清障车'),
                    'SWEEPER'              =>Array(0=>'C23',1=>'清扫车'),
                    'CLEAN_THE_CAR'        =>Array(0=>'C24',1=>'清洁车'),
                    'CARRIAGE_HOIST'       =>Array(0=>'C25',1=>'起重车'),
                    'LOADING_AND_UNLOADING'=>Array(0=>'C26',1=>'装卸车'),
                    'LIFT_TRUCK'           =>Array(0=>'C27',1=>'升降车'),
                    'CONCRETE_MIXER_TRUCK' =>Array(0=>'C28',1=>'混凝土搅拌车'),
                    'MINING_VEHICLE'       =>Array(0=>'C29',1=>'挖掘车'),
                    'PROFESSIONAL_TRAILER' =>Array(0=>'C30',1=>'专业拖车'),
                    'SPECIAL_TWO_TRAILER'  =>Array(0=>'C31',1=>'特种车二挂车'),
                    'SPECIAL_TWO_OTHER'    =>Array(0=>'C39',1=>'特种车二类其他'),
                    'TV_TRUCKS'            =>Array(0=>'C41',1=>'电视转播车'),
                    'FIRE_ENGINE'          =>Array(0=>'C42',1=>'消防车'),
                    'MEDICAL_VEHICLE'      =>Array(0=>'C43',1=>'医疗车'),
                    'OIL_STEAM'            =>Array(0=>'C44',1=>'油气田操作用车'),
                    'ROAD_VEHICLES'        =>Array(0=>'C45',1=>'压路车'),
                    'MINE_CAR'             =>Array(0=>'C46',1=>'矿山车'),
                    'ARMORED_CAR'          =>Array(0=>'C47',1=>'运钞车'),
                    'AMBULANCE'            =>Array(0=>'C48',1=>'救护车'),
                    'MONITORING_CAR'       =>Array(0=>'C49',1=>'救护车'),
                    'RADAR_VEHICLE'        =>Array(0=>'C50',1=>'雷达车'),
                    'X_OPTICAL_CAR'        =>Array(0=>'C51',1=>'X光检查车'),
                    'TELECOM_ENGINEERING'  =>Array(0=>'C52',1=>'电信抢修车/电信工程车'),
                    'ELECTRICAL_ENGINEERING'=>Array(0=>'C53',1=>'电力抢修车/电力工程车'),
                    'PROFESSIONAL_NET_WATERWHEEL'=>Array(0=>'C54',1=>'专业净水车'),
                    'INSULATION_CAR'       =>Array(0=>'C55',1=>'保温车'),
                    'POSTAL_CAR'           =>Array(0=>'C56',1=>'邮电车'),
                    'POLICE_SPECIAL_VEHICLE'=>Array(0=>'C57',1=>'警用特种车'),
                    'CONCRETE_PUMP_TRUCK'  =>Array(0=>'C58',1=>'混凝土泵车'),
                    'SPECIAL_THREE_TRAILER'=>Array(0=>'C61',1=>'特种车三类挂车'),
                    'SPECIAL_THREE_OTHER'  =>Array(0=>'C69',1=>'特种车三类其它'),
                    'CONTAINER_TRACTORS'   =>Array(0=>'C90',1=>'集装箱拖头'),
                    'MOTORCYCLE'           =>Array(0=>'D01',1=>'摩托车'),
                    'THREE_MOTORCYCLE'     =>Array(0=>'D02',1=>'正三轮摩托车'),
                    'SIDECAR'              =>Array(0=>'D03',1=>'侧三轮摩托车'),
                    'TRACTOR'              =>Array(0=>'E01',1=>'拖拉机'),
                    'COMBINE_HARVESTER'    =>Array(0=>'E11',1=>'联合收割机'),
                    'OTHER_VEHICLES'       =>Array(0=>'Z99',1=>'其它车辆'),
                    );
        /*车牌类型转换*/
        /*（号牌类型）     01 大型 02小型 03使馆04领馆 05境外 06外籍 07两，三轮摩托车 08轻便 09使馆摩托车 10领馆摩托车 11境外摩托车 12 外籍摩托车 13 农用运输车 14拖拉机 15 挂车 16 教练汽车  17教练摩托车 18试验汽车 19试验摩托车 20临时入境汽车 21临时入境摩托车 22临时行驶车 80警用汽车 81 警用摩托车 82 公安民用 83 武警 84 军队*/
        private $license_type = Array(
                    'SMALL_CAR'                =>Array(0=>'02',1=>'小型汽车号牌'),
                    'LARGE_AUTOMOBILE'         =>Array(0=>'01',1=>'大型汽车号牌'),
                    'TRAILER'                  =>Array(0=>'15',1=>'挂车号牌'),
                    'EMBASSY_CAR'              =>Array(0=>'03',1=>'使馆汽车号牌'),
                    'CONSULATE_VEHICLE'        =>Array(0=>'04',1=>'领馆汽车号牌'),
                    'HK_MACAO_ENTRY_EXIT_CAR'  =>Array(0=>'01',1=>'大型汽车号牌'),
                    'COACH_CAR'                =>Array(0=>'16',1=>'教练汽车号牌'),
                    'POLICE_CAR'               =>Array(0=>'80',1=>'警用汽车号牌'),
                    'GENERAL_MOTORCYCLE'       =>Array(0=>'07',1=>'两，三轮摩托车号牌'),
                    'MOPED'                    =>Array(0=>'08',1=>'轻便号牌'),
                    'EMBASSY_MOTORCYCLE'       =>Array(0=>'09',1=>'使馆摩托车号牌'),
                    'CONSULATE_MOTORCYCLE'     =>Array(0=>'10',1=>'领馆摩托车号牌'),
                    'COACH_MOTORCYCLE'         =>Array(0=>'17',1=>'教练摩托车号牌'),
                    'POLICE_MOTORCYCLE'        =>Array(0=>'81',1=>'警用摩托车号牌'),
                    'TEMPORARY_VEHICLE'        =>Array(0=>'20',1=>'临时入境汽车号牌'),
            );



     /**
      * [__construct 构造函数]
      * @AuthorHTL
      * @DateTime  2016-12-15T11:42:24+0800
      * @param     [type]                   $config    [传递配置目录]
      * @param     [type]                   $cachePath [cookie存放地址]
      */
    function __construct($config,$cachePath)
    {
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

        $this->loginAarray=array("PTAVersion"=>"","toSign"=>"","key"=>"no","errorKey"=>"no","Signature"=>"","rememberFlag"=>"0","userMac"=>"","loginMethod"=>"nameAndPwd","username"=>$user,"password" =>$password,"_eventId"=>"submit","pcguid" =>"","button.x"=>"20","button.y"=>"0");//登陆条件
        $this->URL="http://10.134.138.16:8000";
        $this->Add_Idcard_URL="http://10.134.138.16:8300";
        $this->UrlLogin="https://10.134.138.16:8888/casserver/login?service=http%3A%2F%2F10.134.138.16%3A80%2Fportal%2Findex.jsp";/*登陆处理查询*/
        $this->IdCardUrl=$this->URL."/prpall/custom/customAmountQueryP.do";/*身份信息查询*/
        $this->Token_url=$this->Add_Idcard_URL."/cif/customperson/prepareAdd.do";//获取Token值
        $this->Add_IdCardUrl=$this->Add_Idcard_URL."/cif/customperson/add.do";/*增加身份信息*/
        $this->UrlOffter=$this->URL."/prpall/business/caculatePremiunForFG.do";/*车险报价查询*/
        $this->Plat_Url= $this->URL."/prpall/business/queryTaxAbateForPlat.do";/*查询是否是节能减排车型*/
        $this->PurchasePriceUrl=$this->URL."/prpall/vehicle/vehicleQuery.do";/*车辆购置价查询*/
        $this->calActualValUrl=$this->URL."/prpall/business/calActualValue.do";
        $this->calDeviceActualValue=$this->URL."/prpall/business/calDeviceActualValue.do";/*新增设备条件*/
        $this->calAnciInfo_Url=$this->URL."/prpall/undwrtassist/calAnciInfo.do";/*计算辅助核保*/
        $this->checkAgentUrl =$this->URL."/prpall/business/queryPayForSCMS.do";/*计算跟单费用*/
        $this->channel_Url=$this->URL."/prpall/business/prepareEdit.do?bizType=PROPOSAL&editType=NEW";/*获取渠道代码*/
        $this->checkBefores_URL= $this->URL."/prpall/business/selectProposal.do";/*查询历史保单*/
        $this->deleteProposal_Url=$this->URL."/prpall/business/deleteProposal.do";/*删除保险公司暂存单*/
        $this->insert_URL=$this->URL."/prpall/business/insert.do";/*生成暂存单号*/

        $this->editCheckFlag=$this->URL."/prpall/business/editCheckFlag.do";
        $this->editSubmitUndwrt=$this->URL."/prpall/business/editSubmitUndwrt.do";
        $this->showUndwrtMsg=$this->URL."/prpall/business/showUndwrtMsg.do";



        if(empty($cachePath))
        {
            $this->cookie_file = dirname(__FILE__).'/piccneisc_cookie.txt';/*读取COOKIE文件存放地址*/
        }
        else
        {
            $this->cookie_file = $cachePath.'/piccneisc_cookie.txt';  /*COOKIE设置文件存放地址*/
        }

    }


    /**
     * [getSetItems 获取设置项目]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:47:37+0800
     * @return    [type]                   [返回设置项]
     */
    public function getSetItems()
    {
        return $this->setItems;
    }
    /**
     * [getFormFile 获取表单模板文件名]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:47:51+0800
     * @return    [type]                   [返回模板文件名]
     */
    public function getFormFile()
    {
        return self::formFile;
    }
    /**
     * [getLastError 返回最后一次错误信息]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:48:05+0800
     * @return    [type]                   [返回错误提示]
     */
    public function getLastError()
    {

        return $this->error;

    }
    /**
     * [requestPostData CURL---POST请求（带登陆）]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:48:22+0800
     * @param     [type]                   $url  [传递的链接地址]
     * @param     [type]                   $post [传递的POST数组]
     * @param     boolean                  $head [请求的头部]
     * @param     integer                  $foll [description]
     * @param     boolean                  $ref  [description]
     * @return    [type]                         [description]
     */
    private function requestPostData($url,$post,$head=false,$foll=1,$ref=false)
    {

        $ret=self::post($url,$post,$head,$foll);
        $str='/<input[^<]*type="hidden"[^<]* name="lt"[^<]* value="([^<]*)"[^<]*>/';
        preg_match($str, $ret, $stx);
        if(!empty($stx[1]) && is_array($stx))
        {
            $this->loginAarray['lt']=self::trimall($stx[1]);
            $apps = self::post($this->UrlLogin,$this->loginAarray);//执行登录
            preg_match($str, $apps, $sty);
            if(empty($sty) && is_array($sty))
            {
                $ret =  self::post($url,$post,$head,$foll);
            }
            else
            {
                return false;
            }
        }


        return $ret;

    }
    /**
     * [requestGetData CURL---GET请求（带登陆）]
     * @AuthorHTL
     * @DateTime  2016-12-15T16:52:38+0800
     * @param     [type]                   $url  [description]
     * @param     boolean                  $head [description]
     * @param     integer                  $foll [description]
     * @param     boolean                  $ref  [description]
     * @return    [type]                         [description]
     */
    private function requestGetData($url,$head=false,$foll=1,$ref=false)
    {
        $apps = self::post($this->UrlLogin,$this->loginAarray);//执行登录
        $ret = self::get($url,$head,$foll,$ref);
        $str='/<input[^<]*type="hidden"[^<]* name="lt"[^<]* value="([^<]*)"[^<]*>/';
        preg_match($str, $ret, $stx);
        if(isset($stx[1]) && $stx[1]!="")
        {
            $this->loginAarray['lt']=$stx[1];
            $apps = self::post($this->UrlLogin,$this->loginAarray);//执行登录
            preg_match($str, $apps, $sty);
            if(isset($sty[1]) && $sty!="")
            {
                return false;
            }
        }

            $ret =  self::get($url,$head,$foll,$ref);
        return $ret;
    }
    /**
     * [post CURL---POST请求]
     * @AuthorHTL
     * @DateTime  2016-12-15T13:57:51+0800
     * @param     [type]                   $url  [description]
     * @param     [type]                   $post [description]
     * @param     boolean                  $head [description]
     * @param     integer                  $foll [description]
     * @param     boolean                  $ref  [description]
     * @return    [type]                         [description]
     */
    private function post($url,$post,$head=false,$foll=1,$ref=false){
        $curl = curl_init(); // 启动一个CURL会话
        if($head){
        curl_setopt($curl,CURLOPT_HTTPHEADER,$head);//模似请求头
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)');
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate'));
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,$foll); // 使用自动跳转
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post)); // Post提交的数据包
        //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_file); // 存放Cookie信息的文件名称
        curl_setopt($curl, CURLOPT_COOKIEFILE,$this->cookie_file); // 读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');//解释gzip
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环
         if($foll==1){
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
         }else{
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
         }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $tmpInfo = curl_exec($curl); // 执行操作
        return $tmpInfo;
    }
    /**
     * [get CURL---GET请求]
     * @AuthorHTL
     * @DateTime  2016-12-15T13:58:30+0800
     * @param     [type]                   $url       [description]
     * @param     boolean                  $head      [description]
     * @param     boolean                  $refer     [description]
     * @param     [type]                   $encodeing [description]
     * @return    [type]                              [description]
     */
    private function get($url,$head=false,$refer=false,$encodeing = null){

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)');
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate'));
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,1); // 使用自动跳转
        //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_file); // 存放Cookie信息的文件名称
        curl_setopt($curl, CURLOPT_COOKIEFILE,$this->cookie_file); // 读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');//解释gzip
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        if(!empty($refer))curl_setopt($curl, CURLOPT_REFERER, $refer);
        if(!empty($encodeing))curl_setopt($curl, CURLOPT_ENCODING, $encodeing);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        return $tmpInfo;
    }




    /**
     * [premium 人保三代报价]
     * @AuthorHTL
     * @DateTime  2016-12-15T13:58:56+0800
     * @param     array                    $auto     [传递数组]
     * @param     array                    $business [传递数组]
     * @param     array                    $mvtalci  [传递数组]
     * @return    [type]                             [成功返回算价结果，否则返回错误信息]
     */
    public  function  premium($auto=array(),$business=array(),$mvtalci=array())
    {


                $channel_result= self::channel();//获取人保渠道
                $this->error['errorMsg']="";
                if(!$channel_result)
                {
                    $this->error['errorMsg']="请确认登录账号是否正确";
                    return false;
                }

                $data=self::datas($auto,$business,$mvtalci);
                $result=self::idcard($auto,$business,$mvtalci);//身份查询

                if(!$result)
                {
                    $this->error['errorMsg']="请提供客户信息身份证号码";
                    return false;
                }
                else if($result==2)
                {
                    $this->error['errorMsg']="身份证号码格式错误，必须是15位或18位。";
                    return false;        
                           
                }
                else
                {
                           $data["prpCinsureds[0].insuredCode"]=$result['data'][0]['insuredCode'];
                           $_SESSION["idcar"]=$result['data'][0]['identifyNumber'];
                           $_SESSION["id_cord"]=$result['data'][0]['insuredCode'];
                }


                                unset($_SESSION['INSURANCE']);//删除历史session险种
                                unset($_SESSION['DISCOUNT_PREMIUM']);
                                unset($_SESSION['COUNT_PREMIUM']);
                                unset($_SESSION['DISCOUNT']);
                                unset($_SESSION['Total_Discount_Amount']);
                                unset($_SESSION['NET_PREMIUM']);
                                unset($_SESSION['TAX']);
                                unset($_SESSION['DOCUMENG_NUMBER']);
                                unset($_SESSION['NetPremium']);
                                unset($_SESSION['MVTALCI_TAX_PREMIUM']);
                                unset($_SESSION['MVTALCI_Query_Code']);
                                unset($_SESSION['BASE_PREMIUM']);
                                unset($_SESSION['BASEPREMIUM']);
                                $resen=json_decode(self::requestPostData($this->UrlOffter,$data),true);

                                if(isset($resen['msg']) && $resen['totalRecords']==0)
                                {
                                    $errors['errorMsg'] = str_replace("0,","",$resen['msg']);
                                    $this->error['errorMsg']=str_replace("0,","",$resen['msg']);
                                    return false;
                                }

                                if(isset($resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandDAA']['remark']) && $resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandDAA']['remark']!="")
                                {
                                    $this->error['errorMsg']=$resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandDAA']['remark'];
                                    return false;    
                                }

                                 if(count($resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandRepets'])>1)
                                 {
                                       
                                        if($resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['remark']!="成功")
                                        {
                                            
                                                $this->error['errorMsg']= $resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['remark']."<br />";
                                                $this->error['errorMsg'].="发生重复投保:"."<br />";
                                                $this->error['errorMsg'].="保险公司:".$resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandRepets'][0]['insurerCode'];
                                                $this->error['errorMsg'].="车牌号:".$resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandRepets'][0]['licenseNo'];
                                                $this->error['errorMsg'].="发动机号:".$resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandRepets'][0]['engineNo'];
                                                $this->error['errorMsg'].="投保单号:".$resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandRepets'][0]['id']['demandNo'];
                                                $this->error['errorMsg'].="投保险种:"."<br />";

                                                foreach($resen['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandRepets'] as $pi=>$li)
                                                {
                                                        $this->error['errorMsg'].=$li['kindName']."<br />";
                                                }
                                                return false;

                                            }

                                    }
                                       
                                        
                                       
                                        

                                        //$Plat_whe["modelCode"]=!isset($auto['MODEL_CODE'])?"":$auto['MODEL_CODE'];
                                        //$Plat_whe["prpCitemCar.licenseType"]="02";//小型车牌
                                        //$Plat_whe["comCode"]=!isset($_SESSION['comCode'])?"":$_SESSION['comCode'];
                                        //$Plat_whe["prpCitemCar.enrollDate"]=!isset($auto['ENROLL_DATE'])?"":$auto['ENROLL_DATE'];
                                        // $app_Result=$this->requestPostData($this->Plat_Url,$Plat_whe);//节能减排车型
                                        // $Plat_Result= json_decode($app_Result,true);

                                $results['MVTALCI'] = array();
                                $results['MESSAGE'] = "";
                                $results['MVTALCI']['TRAVEL_TAX_PREMIUM']= '0.00';
                                $results['MVTALCI']['BASE_PREMIUM']      = '0.00';
                                $results['MVTALCI']['MVTALCI_PREMIUM']   = '0.00';
                                $results['MVTALCI']['MVTALCI_DISCOUNT']  = '1.000';
                                $results['MVTALCI']['MVTALCI_START_TIME']= '';
                                $results['MVTALCI']['MVTALCI_END_TIME']  = '';
                                $_SESSION['BUSINESS']['INSURANCES']="";
                                $results['MESSAGE'] = $resen['data'][0]['biInsuredemandVoList'][0]['prpCfixations'][0]['operationInfo'];//提示信息
                                if(!empty($mvtalci))
                                {
                                        /*******************交强险********************/
                                       
                                        // if($Plat_Result['totalRecords']==1)//如果查询有值，代表是节能减排车型，车船税减半
                                        // {
                                        //      $results['MVTALCI']['TRAVEL_TAX_PREMIUM']=$resen['data'][0]['ciInsureVOList'][0]['ciInsureTax']['ciInsureAnnualTaxes'][0]['unitRate']/2;
                                        // }
                                        // else
                                        // {

                                        //     $results['MVTALCI']['TRAVEL_TAX_PREMIUM']= $resen['data'][0]['ciInsureVOList'][0]['ciInsureTax']['ciInsureAnnualTaxes'][0]['unitRate'];
                                        // }
                                    $results['MVTALCI']['TRAVEL_TAX_PREMIUM']= $resen['data'][0]['ciInsureVOList'][0]['ciInsureTax']['ciInsureAnnualTaxes'][0]['unitRate'];    
                                    $_SESSION['MVTALCI_Query_Code']=$resen['data'][0]['ciInsureVOList'][0]['ciInsureTax']['demandNo'];   
                                    $results['MVTALCI']['MVTALCI_PREMIUM']   = $resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['premium'];//$resen['ciPremium'];           //交强险保费
                                    $results['MVTALCI']['MVTALCI_DISCOUNT']  = 1+$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['claimCoeff'];//$resen['ciDiscount'];          //交强险折扣
                                    $results['MVTALCI']['MVTALCI_START_TIME']= $mvtalci['MVTALCI_START_TIME'];       //交强险生效时间
                                    $results['MVTALCI']['MVTALCI_END_TIME']  = date('Y-m-d H:i:s',strtotime('+1 years -1 seconds',strtotime($mvtalci['MVTALCI_START_TIME'])));         //交强险结束时间

                                    $_SESSION["NetPremium"]=$resen['data'][0]['ciInsureVOList'][0]['prpCitemKinds'][0]['netPremium'];
                                    $_SESSION["MVTALCI_TAX_PREMIUM"]=$resen['data'][0]['ciInsureVOList'][0]['prpCitemKinds'][0]['taxPremium'];

                                    $_SESSION["DOCUMENG_NUMBER"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureTax']['ciInsureAnnualTaxes'][0]['taxDocumentNumber'];

                                    $_SESSION["BASE_PREMIUM"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['basePremium'];//标准保费

                                    $_SESSION["BASEPREMIUM"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['basePremium']-$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['premium'];//总折扣金额
                                    $_SESSION['BUSINESS']['INSURANCES']="050100";
                                 }
                                 

                                  /*******************商业险********************/

                                 $results['BUSINESS']['BUSINESS_START_TIME'] = $business['BUSINESS_START_TIME'];       //商业险生效时间
                                 $results['BUSINESS']['BUSINESS_END_TIME'] = date('Y-m-d H:i:s',strtotime('+1 years -1 seconds',strtotime($business['BUSINESS_START_TIME'])));//商业险结束时间
                                 $results['BUSINESS']['BUSINESS_DISCOUNT']="0.00";
                                 $results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']="0.00";
                                 $results['BUSINESS']['BUSINESS_PREMIUM']="0.00";
                                  /*******************投保项目保费二维数组********************/
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']                  = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['NET_PREMIUM']              = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['TAX']                      = '0.00';

                                 $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']               = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['TAX']              = '0.00';

                                 $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']                 = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['TAX']              = '0.00';

                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']          = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['TAX']              = '0.00';

                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']       = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['NET_PREMIUM']           = '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['TAX']              = '0.00';

                                 $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']                  = '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['NET_PREMIUM']           = '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['TAX']              = '0.00';



                                 $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']                  = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['TAX']              = '0.00';


                                 $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']                 = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['NET_PREMIUM']           = '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['TAX']              = '0.00';

                                  $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']                 = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['TAX']              = '0.00';


                                 $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']                  = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['TAX']              = '0.00';


                                 $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']                 = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['TAX']              = '0.00';



                                 $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']                 = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['TAX']              = '0.00';

                                 $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['STANDARD_PREMIUM']         = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']            = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['TAX']              = '0.00';


                                 $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['STANDARD_PREMIUM']              = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['PREMIUM']                  = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['TAX']              = '0.00';


                                  $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['STANDARD_PREMIUM']              = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']             = '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['TAX']              = '0.00';


                                  $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['STANDARD_PREMIUM']              = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']            = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['TAX']              = '0.00';

                                 $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['STANDARD_PREMIUM']              = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']          = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['TAX']              = '0.00';


                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['STANDARD_PREMIUM']              = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']     = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['TAX']              = '0.00';


                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['STANDARD_PREMIUM']      = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM']  = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['TAX']              = '0.00';



                                  $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['STANDARD_PREMIUM']= '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']            = '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['TAX']              = '0.00';

                                  $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['STANDARD_PREMIUM']= '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']            = '0.00';
                                  $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['TAX']              = '0.00';


                                 $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['STANDARD_PREMIUM']= '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']            = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['TAX']              = '0.00';

                                 $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['STANDARD_PREMIUM']= '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']            = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['NET_PREMIUM']           = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['TAX']              = '0.00';
                                 
                                if(count($business['POLICY']['BUSINESS_ITEMS'])>0)
                                {    
                                 foreach($resen['data'][0]['biInsuredemandVoList'][0]['prpCitemKinds'] as $k=>$vs)
                                 {
                                        switch ($vs['kindCode'])
                                          {
                                                case '050202':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']         = $vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['NET_PREMIUM']     = $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['TAX']             = $vs['taxPremium'];
                                                    
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050202";
                                                    $_SESSION['INSURANCE']['TVDI']['PURERISK_PREMIUM']=$vs['pureRiskPremium'];
                                                    $_SESSION['INSURANCE']['TVDI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['TVDI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['TVDI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['TVDI']['TAX']=$vs['taxPremium'];

                                                    break;
                                                case '050501':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050501";
                                                    $_SESSION['INSURANCE']['TWCDMVI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['TWCDMVI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['TWCDMVI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['TWCDMVI']['TAX']=$vs['taxPremium'];

                                                    break;
                                                case '050602':
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']=$vs['premium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['NET_PREMIUM']= $vs['netPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['TAX']        = $vs['taxPremium'];
                                                     $_SESSION['BUSINESS']['INSURANCES'].=" 050602";
                                                     $_SESSION['INSURANCE']['TTBLI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                     $_SESSION['INSURANCE']['TTBLI']['PREMIUM']=$vs['premium'];
                                                     $_SESSION['INSURANCE']['TTBLI']['NET_PREMIUM']=$vs['netPremium'];
                                                     $_SESSION['INSURANCE']['TTBLI']['TAX']=$vs['taxPremium'];
                                                break;

                                                case '050711':
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']=$vs['premium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['NET_PREMIUM']= $vs['netPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['TAX']        = $vs['taxPremium'];
                                                     $_SESSION['BUSINESS']['INSURANCES'].=" 050711";
                                                     $_SESSION['INSURANCE']['TCPLI_DRIVER']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                     $_SESSION['INSURANCE']['TCPLI_DRIVER']['PREMIUM']=$vs['premium'];
                                                     $_SESSION['INSURANCE']['TCPLI_DRIVER']['NET_PREMIUM']=$vs['netPremium'];
                                                     $_SESSION['INSURANCE']['TCPLI_DRIVER']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050712':
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']=$vs['premium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['NET_PREMIUM']= $vs['netPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['TAX']        = $vs['taxPremium'];
                                                     $_SESSION['BUSINESS']['INSURANCES'].=" 050712";
                                                     $_SESSION['INSURANCE']['TCPLI_PASSENGER']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                     $_SESSION['INSURANCE']['TCPLI_PASSENGER']['PREMIUM']=$vs['premium'];
                                                     $_SESSION['INSURANCE']['TCPLI_PASSENGER']['NET_PREMIUM']=$vs['netPremium'];
                                                     $_SESSION['INSURANCE']['TCPLI_PASSENGER']['TAX']=$vs['taxPremium'];
                                                break;

                                                case '050211':
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']=$vs['premium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['NET_PREMIUM']= $vs['netPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['TAX']        = $vs['taxPremium'];
                                                     $_SESSION['BUSINESS']['INSURANCES'].=" 050211";
                                                    $_SESSION['INSURANCE']['BSDI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['BSDI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['BSDI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['BSDI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050232':
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']=$vs['premium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['NET_PREMIUM']= $vs['netPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['TAX']        = $vs['taxPremium'];
                                                     $_SESSION['BUSINESS']['INSURANCES'].=" 050232";
                                                    $_SESSION['INSURANCE']['BGAI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['BGAI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['BGAI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['BGAI']['TAX']=$vs['taxPremium'];
                                                break;

                                                case '050311':
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']=$vs['premium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['NET_PREMIUM']= $vs['netPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['TAX']        = $vs['taxPremium'];
                                                     $_SESSION['BUSINESS']['INSURANCES'].=" 050311";
                                                    $_SESSION['INSURANCE']['SLOI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['SLOI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['SLOI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['SLOI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050461':
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']=$vs['premium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['NET_PREMIUM']= $vs['netPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['TAX']        = $vs['taxPremium'];
                                                     $_SESSION['BUSINESS']['INSURANCES'].=" 050461";
                                                    $_SESSION['INSURANCE']['VWTLI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['VWTLI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['VWTLI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['VWTLI']['TAX']=$vs['taxPremium'];

                                                break;

                                                // case '050917':
                                                //   //$results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                //   $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']=$vs['premium'];
                                                // break;

                                                case '050930':
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']=$vs['premium'];
                                                      $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                     $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['TAX']        = $vs['taxPremium'];
                                                     $_SESSION['BUSINESS']['INSURANCES'].=" 050930";
                                                    $_SESSION['INSURANCE']['TVDI_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['TVDI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['TVDI_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['TVDI_NDSI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050931':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050931"; 
                                                    $_SESSION['INSURANCE']['TTBLI_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['TTBLI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['TTBLI_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['TTBLI_NDSI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050932':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050932"; 
                                                    $_SESSION['INSURANCE']['TWCDMVI_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['TWCDMVI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['TWCDMVI_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['TWCDMVI_NDSI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050933':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050933"; 
                                                    $_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050934':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050934"; 
                                                    $_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050937':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050937";
                                                    $_SESSION['INSURANCE']['BSDI_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['BSDI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['BSDI_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['BSDI_NDSI']['TAX']=$vs['taxPremium'];
                                                break;

                                                case '050938':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050938";
                                                    $_SESSION['INSURANCE']['VWTLI_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['VWTLI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['VWTLI_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['VWTLI_NDSI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050935':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050935";

                                                    $_SESSION['INSURANCE']['SLOI_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['SLOI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['SLOI_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['SLOI_NDSI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050253':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050253";
                                                    $_SESSION['INSURANCE']['STSFS']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['STSFS']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['STSFS']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['STSFS']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050441':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050441";
                                                    $_SESSION['INSURANCE']['RDCCI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['RDCCI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['RDCCI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['RDCCI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050451':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050451";
                                                    $_SESSION['INSURANCE']['MVLINFTPSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['MVLINFTPSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['MVLINFTPSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['MVLINFTPSI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050643':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050643";
                                                    $_SESSION['INSURANCE']['LIDI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['LIDI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['LIDI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['LIDI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050261':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050261";
                                                    $_SESSION['INSURANCE']['NIELI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['NIELI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['NIELI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['NIELI']['TAX']=$vs['taxPremium'];

                                                break;

                                                case '050936':
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['STANDARD_PREMIUM'] =$vs['benchMarkPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['NET_PREMIUM']= $vs['netPremium'];
                                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['TAX']        = $vs['taxPremium'];
                                                    $_SESSION['BUSINESS']['INSURANCES'].=" 050936";
                                                    $_SESSION['INSURANCE']['NIELI_NDSI']['STANDARD_PREMIUM']=$vs['benchMarkPremium'];
                                                    $_SESSION['INSURANCE']['NIELI_NDSI']['PREMIUM']=$vs['premium'];
                                                    $_SESSION['INSURANCE']['NIELI_NDSI']['NET_PREMIUM']=$vs['netPremium'];
                                                    $_SESSION['INSURANCE']['NIELI_NDSI']['TAX']=$vs['taxPremium'];

                                                break;

                                            }


                                 }
                                            $DISCOUNT_PREMIUM="";/*折扣前保费*/
                                            $COUNT_PREMIUM="";/*含税总保费*/
                                            $NET_PREMIUM="";/*净保费*/
                                            $TAX="";/*总税额*/
                                            

                                            foreach($results['BUSINESS']['BUSINESS_ITEMS'] as $ks)
                                            {
                                                $DISCOUNT_PREMIUM += $ks['STANDARD_PREMIUM'];
                                            }

                                            $_SESSION['DISCOUNT_PREMIUM']=$DISCOUNT_PREMIUM;

                                            foreach($results['BUSINESS']['BUSINESS_ITEMS'] as $ks)
                                            {
                                                $COUNT_PREMIUM += $ks['PREMIUM'];
                                            }
                                            $_SESSION['COUNT_PREMIUM']=$COUNT_PREMIUM;

                                            foreach($results['BUSINESS']['BUSINESS_ITEMS'] as $ks)
                                            {
                                                $NET_PREMIUM += $ks['NET_PREMIUM'];
                                            }

                                            $_SESSION['NET_PREMIUM']=$NET_PREMIUM;

                                            foreach($results['BUSINESS']['BUSINESS_ITEMS'] as $ks)
                                            {
                                                $TAX += $ks['TAX'];
                                            }
                                            $_SESSION['TAX']=$TAX;


                                            $_SESSION['DISCOUNT']=$resen['data'][0]['biInsuredemandVoList'][0]['prpCitemKinds'][0]['disCount'];
                                            
                                            $results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']=$_SESSION['DISCOUNT_PREMIUM'];//商业险折扣前保费合计
                                            $results['BUSINESS']['BUSINESS_PREMIUM']= $_SESSION['COUNT_PREMIUM'];//$resen['biPremium'];
                                            $Total_Discount_Amount= $_SESSION['DISCOUNT_PREMIUM']-$_SESSION['COUNT_PREMIUM'];
                                            $_SESSION['Total_Discount_Amount']=$Total_Discount_Amount;
                                            $results['BUSINESS']['BUSINESS_DISCOUNT']=$_SESSION['DISCOUNT'];
                                        }
                                        // echo "<pre>";
                                        // print_r($_SESSION);

                                            return $results;
                                                


        }




    /**
     * [depreciation 车辆折旧价]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:05:32+0800
     * @param     [type]                   $info [传递数组]
     * @return    [type]                         [返回折旧价]
     */
    public function depreciation($info)
    {


/*         if($info['BUYING_PRICE']!="" && $info['ENROLL_DATE']!="" && $info['BUSINESS_START_TIME']!="")
          {
                    $ENROLLhour = date('Y',strtotime($info['ENROLL_DATE']))*12+(date('m',strtotime($info['ENROLL_DATE'])));
                    $START_TIMEhour = date('Y',strtotime($info['BUSINESS_START_TIME']))*12+(date('m',strtotime($info['BUSINESS_START_TIME'])));
                    $Month=$START_TIMEhour-$ENROLLhour;


                    if(date('d',strtotime($info['BUSINESS_START_TIME']))<date('d',strtotime($info['ENROLL_DATE'])))
                    {
                         $Month--;//比较日期
                    }
                    if($Month < 0)
                     {
                              $Month = 0;//如果相差为负数时，设置为0，否则为引起险别重新计算保额错误

                     }
                     if($info['USE_CHARACTER']=="NON_OPERATING_PRIVATE" && $info['VEHICLE_TYPE']=="PASSENGER_CAR") //判断是否是家用车
                     {
                         return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.006;

                     }
                     else if($info['VEHICLE_TYPE']=="TRUCK")//货车类型
                     {

                         if($info['USE_CHARACTER']== 'OPERATING_TRUCK')
                         {

                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.009;

                         }

                         else if($info['USE_CHARACTER']== 'NON_OPERATING_TRUCK')
                         {

                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.009;
                         }
                         else if($info['USE_CHARACTER']== 'NON_OPERATING_LOW_SPEED_TRUCK')
                         {
                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.011;
                         }
                         else if($info['USE_CHARACTER']== 'OPERATING_LOW_SPEED_TRUCK')
                         {
                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.014;

                         }

                     }
                     else if($info['VEHICLE_TYPE']=="PASSENGER_CAR")//客车类型
                     {

                        if($info['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE" || $info['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
                         {
                               return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.006;

                         }
                         else if($info['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
                         {

                              return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.011;

                         }
                         else if($info['USE_CHARACTER']=="OPERATING_CITY_BUS" || $info['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
                         {

                              return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.009;
                         }


                     }

                     else if($info['VEHICLE_TYPE']=='THREE_WHEELED')
                              {
                                   return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.011;
                              }
                     return false;

          }*/

        $data = array();
        $data["prpCmain.startDate"]=$info['BUSINESS_START_TIME'];
        $data["prpCmain.startHour"]="0";
        $data["prpCmain.endDate"]=$info['BUSINESS_END_TIME'];
        $data["prpCmain.endHour"]="24";
        $data["prpCmainCI.startDate"]=$info['MVTALCI_START_TIME'];
        $data["prpCmainCI.startHour"]="0";
        $data["prpCmainCI.endDate"]=$info['MVTALCI_END_TIME'];
        $data["prpCmainCI.endHour"]="24";
        $data["prpCitemCar.id.itemNo"]="1";
        $data["prpCitemCar.licenseNo"]="川";

        foreach($this->license_type as $k=>$v){
                    if($k==$info['LICENSE_TYPE']){
                        $data["prpCitemCar.licenseType"]=$v[0];
                    }
            }
        foreach($this->vehicle_type as $key=>$val){
                if($key==$info['VEHICLE_TYPE']){
                    $data["prpCitemCar.carKindCode"]=$val[0];
                    $data["CarKindCodeDes"]=$val[1];
                    $data["carKindCodeBak"]=$val[0];
                }
        }
        foreach($this->use_character as $ke=>$ve){
                if($ke==$info['USE_CHARACTER']){
                    $data["prpCitemCar.useNatureCode"]=$ve[1];
                    $data["useNatureCodeBak"]=$ve[1];
                    $data["useNatureCodeTrue"]=$ve[1];
                }
        }
        foreach($this->clause_Type as $k=>$v)
        {
                if(array_key_exists($info['USE_CHARACTER'],$this->clause_Type))
                {
                            $data["prpCitemCar.clauseType"]=$v;
                            $data["clauseTypeBak"]=$v;
                }
        }

        if(!isset($info['ENROLL_DATE'])) return false;
        $data["prpCitemCar.enrollDate"] = $info['ENROLL_DATE'];
        $data["prpCitemCar.useYears"]=date("Y",time())-date("Y",strtotime($info['ENROLL_DATE']));
        if(!isset($info['MODEL_CODE'])) return false;
        $data["prpCitemCar.modelCode"]=$info['MODEL_CODE'];
        if(!isset($info['MODEL'])) return false;
        $data["prpCitemCar.brandName"]=$info['MODEL'];
        $data["PurchasePriceScal"]="10";
        if(!isset($info['BUYING_PRICE'])) return false;
        $data["prpCitemCar.purchasePrice"]=$info['BUYING_PRICE'];
        $data["prpCmainCar.agreeDriverFlag"]="";
        $value=self::requestPostData($this->calActualValUrl,$data);
        if(isset($value) && $value!="")
        {
            return $value;
        }
        else
        {
            return false;
        }    
        
    }


    /**
     * [deviceDepreciation 设备折旧价]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:09:48+0800
     * @param     array                    $info [传递数组]
     * @return    [type]                         [返回折旧价数组]
     */
    public function deviceDepreciation($info=array())
    {
        /*$data = array();
        $data["bizType"]="PROPOSAL";
        $data["prpCmain.startDate"]=$info['BUSINESS_START_TIME'];  //商业保险起止日期
        $data["prpCmain.startHour"]="0";  //商业保险起止日期(时)
        $data["prpCmain.endDate"]=$info['BUSINESS_END_TIME'];  //商业保险终止日期
        $data["prpCmain.endHour"]="24";//商业保险终止日期(时)
        $data["prpCitemCar.id.itemNo"]="1";
        $data["prpCmainCar.newDeviceFlag"]="";
        $data["prpCitemCar.licenseNo"]=$info['LICENSE_NO'];  //号牌号码
        foreach($this->vehicle_type as $key=>$val){
                if($key==$info['VEHICLE_TYPE']){
                    $data["prpCitemCar.carKindCode"]=$val[0];
                    $data["CarKindCodeDes"]=$val[1];
                    $data["carKindCodeBak"]=$val[0];
                }
        }
        if(!isset($info['TIAOKUAN_TYPE'])) return false;

        foreach($this->clause_Type as $k=>$v)
        {
                if(array_key_exists($info['USE_CHARACTER'],$this->clause_Type))
                {
                            $data["prpCitemCar.clauseType"]=$v;
                }
        }

        if(!isset($info['DEVICE_LIST'])) return false;
        foreach($info['DEVICE_LIST'] as $k =>$v)
        {
            $data["prpCcarDevices[$k].deviceName"]= $v['NAME'];
            $data["prpCcarDevices[$k].id.itemNo"]=$k+1;
            $data["prpCcarDevices[$k].id.proposalNo"]="";
            $data["prpCcarDevices[$k].id.serialNo"]=$k+1;
            $data["prpCcarDevices[$k].flag"]="";
            $data["prpCcarDevices[$k].quantity"]= $v['COUNT'];
            $data["prpCcarDevices[$k].purchasePrice"]=$v['BUYING_PRICE'];
            $data["prpCcarDevices[$k].buyDate"]=$v['BUYING_DATE'];
            $data["prpCcarDevices[$k].actualValue"]="";
        }
        $results =json_decode($this->requestPostData($this->calDeviceActualValue,$data),true);
        if($results['totalRecords']=="0" && !isset($results['data']))
        {
                return false;
        }

        $retarr=$info['DEVICE_LIST'];
        foreach($results['data'] as $dev)
        {
            $retarr[$dev['id']['serialNo']-1]['DEPRECIATION'] = $dev['actualValue'];
        }
        return $retarr;*/


        if(empty($info) || !isset($info)){
               $this->error="参数信息不能为空";
               return false;
          }

          foreach($info['DEVICE_LIST'] as $k =>$v)
          {
               if(!isset($v['NAME']))
               {
                    $this->error="设备名称不能为空";
                    return false;
               }

               if(!isset($v['BUYING_PRICE']))
               {
                    $this->error="新购价格不能为空";
                    return false;
               }

               if(!isset($v['COUNT']))
               {
                    $this->error="数量不能为空";
                    return false;
               }

               if(!isset($v['BUYING_DATE']))
               {
                    $this->error="购置日期不能空";
                    return false;
               }

               if(!isset($info['BUSINESS_START_TIME']))
               {
                    $this->error="商业险日期不能为空";
                    return false;
               }

               if(!isset($info['VEHICLE_TYPE']))
               {
                    $this->error="车辆种类不能为空";
                    return false;
               }


               if(!isset($info['USE_CHARACTER']))
               {
                    $this->error="使用性质不能为空";
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

                    if($info['USE_CHARACTER']=="NON_OPERATING_PRIVATE" && $info['VEHICLE_TYPE']=="PASSENGER_CAR") //判断是否是家用车
                     {
                         $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.006;

                     }
                     else if($info['VEHICLE_TYPE']=="TRUCK")//货车类型
                     {

                         if($info['USE_CHARACTER']== 'OPERATING_TRUCK')
                         {

                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;

                         }

                         else if($info['USE_CHARACTER']== 'NON_OPERATING_TRUCK')
                         {

                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;
                         }
                         else if($info['USE_CHARACTER']== 'NON_OPERATING_LOW_SPEED_TRUCK')
                         {
                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;
                         }
                         else if($info['USE_CHARACTER']== 'OPERATING_LOW_SPEED_TRUCK')
                         {
                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.014;

                         }

                     }
                     else if($v['VEHICLE_TYPE']=="PASSENGER_CAR")//客车类型
                     {

                        if($v['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE" || $v['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
                         {
                               $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.006;

                         }
                         else if($v['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
                         {

                              $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;

                         }
                         else if($v['USE_CHARACTER']=="OPERATING_CITY_BUS" || $v['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
                         {

                              $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.009;
                         }


                     }

                     else if($v['VEHICLE_TYPE']=='THREE_WHEELED')
                              {
                                   $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.011;
                              }

                    $data[$k]['BUYING_DATE']=$v['BUYING_DATE'];
                    $data[$k]['BUYING_PRICE']=$v['BUYING_PRICE'];
                    $data[$k]['COUNT']=$v['COUNT'];
                    $data[$k]['DEPRECIATION']=$BUYING_PRICE;//$BUYING_PRICE*$v['COUNT']设备小计
                    $data[$k]['NAME']=$v['NAME'];

          }

                    return $data;

    }




     /**
      * [queryBuyingPrice 购置价查询]
      * @AuthorHTL
      * @DateTime  2016-04-18T16:13:42+0800
      * @param     array                    $info [传递数组]
      * @return    [type]                         [成功 返回数组 失败返回false]
      */

    public function queryBuyingPrice($info=array())
    {
        if($info['model']=="")
        {
            return "车辆品牌不能为空";
        }
            $page =1;
            if(!empty($info['page']) && $info['page']!="0")
            {
                $page=$info['page'];
            }
            $rows = 10;
            $where['pageSize']=$rows;
            $where['pageNo']=$page;
            $data["riskCode"]="";
            $data["totalRecords_"]="";
            $data["taxFlag"]="0";
            $data["comCode"]="";
            $data["carShipTaxPlatFormFlag"]="";
            $data["TCVehicleVO.searchCode"]="";
            $data["TCVehicleVO.vehicleAlias"]="";
            $data["TCVehicleVO.vehicleId"]="";
            $data["TCVehicleVO.brandId"]="";
            $data["TCVehicleVO.brandName"]="";
            //$model= str_replace("牌", "", $model);
            $data["TCVehicleVO.vehicleName"]="*".iconv('UTF-8','GBK',str_replace("牌", "", $info['model']))."*";
            $data["brandName"]="";
            $data["jumpToPage"]="";
            $data["quotationFlag"]="";
            $result=self::requestPostData($this->PurchasePriceUrl.'?'.http_build_query($data),$where);
            $array= json_decode($result,true);
            $count="";
            if($array['totalRecords']>=0)
            {
                $_SESSION['count']= $array['totalRecords'];
            }
            if(is_array($array) && array_key_exists('data',$array))
            {
                $retdata = array('total'=>ceil($_SESSION['count']/$rows),'page'=>$array['startIndex'],'records'=>$_SESSION['count'],'rows'=>array());
                foreach($array['data'] as $row)
                {

                    $line = array();
                    $line['vehicleId']             = $row['vehicleId'];
                    $line['vehicleName']           = $row['vehicleName'];
                    $line['vehicleAlias']          = $row['vehicleAlias'];
                    //$line['vehicleMaker']          = $row['vehicleMaker'];
                    //$line['vehicleWeight']         = $row['vehicleWeight'];
                    $line['vehicleDisplacement']   = $row['vehicleExhaust'];
                    $line['vehicleTonnage']        = $row['vehicleTonnage'];
                    $line['vehiclePrice']          = $row['priceT'];
                    $line['szxhTaxedPrice']        = 0;
                    $line['xhKindPrice']           = 0;
                    $line['nXhKindpriceWithouttax']= 0;
                    $line['vehicleSeat']           = $row['vehicleSeat'];
                    $line['vehicleYear']           = $row['vehicleYear'];
                    $retdata['rows'][] = $line;
                }
                return $retdata;
            }

        return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());




    }


    /**
     * [datas 请求人保数组]
     * @AuthorHTL
     * @DateTime  2016-04-18T16:22:17+0800
     * @param     array                    $auto     [必须配置数组]
     * @param     array                    $business [必须配置数组]
     * @param     array                    $mvtalci  [必须配置数组]
     * @return    [type]                             [description]
     */
    private function  datas($auto=array(),$business=array(),$mvtalci=array())
    {

        $data["initemKind_Flag"]="1";
        $data["carShipTaxPlatFormFlag"]="";
        $data["randomProposalNo"]="2344552471482289763421";
        $data["editType"]="NEW";
        $data["bizType"]="PROPOSAL";
        $data["ABflag"]="";
        if(!empty($mvtalci))
        {
          $data["isBICI"]="01";   //10单商业 01单交强  11双险
        }
         if(count($business['POLICY']['BUSINESS_ITEMS'])>0)
        {
            $data["isBICI"]="10";
        }
         if(!empty($mvtalci) && count($business['POLICY']['BUSINESS_ITEMS'])>0)
        {
            $data["isBICI"]="11";
        }
        $data["prpCmain.renewalFlag"]="03";
        $data["GuangdongSysFlag"]="";
        $data["GDREALTIMECARFlag"]="";
        $data["GDREALTIMEMOTORFlag"]="";
        $data["GDCANCIINFOFlag"]="0";
        $data["prpCmain.checkFlag"]="";
        $data["prpCmain.othFlag"]="";
        $data["prpCmain.dmFlag"]="";
        $data["prpCmainCI.dmFlag"]="";
        $data["prpCmain.underWriteCode"]="";
        $data["prpCmain.underWriteName"]="";
        $data["prpCmain.underWriteEndDate"]="";
        $data["prpCmain.underWriteFlag"]="0";
        $data["prpCmainCI.checkFlag"]="";
        $data["prpCmainCI.underWriteFlag"]="";
        $data["bizNo"]="";
        $data["applyNo"]="";
        $data["oldPolicyNo"]="";
        $data["bizNoBZ"]="";
        $data["bizNoCI"]="";
        $data["prpPhead.endorDate"]="";
        $data["prpPhead.validDate"]="";
        $data["prpPhead.comCode"]="";
        $data["sumAmountBI"]="80800";
        $data["isTaxDemand"]="1";
        $data["cIInsureFlag"]="1";
        $data["bIInsureFlag"]="1";
        $data["ciInsureSwitchKindCode"]="E01,E11,E12,D01,D02,D03,B11,B12";
        $data["ciInsureSwitchValues"]="00000000";
        $data["cIInsureMotorFlag"]="1";
        $data["mtPlatformTime"]="";
        $data["noPermissionsCarKindCode"]="NO";
        $data["isTaxFlag"]="";
        $data["rePolicyNo"]="";
        $data["oldPolicyType"]="";
        $data["ZGRS_PURCHASEPRICE"]="200000";
        $data["ZGRS_LOWESTPREMIUM"]="0";
        $data["clauseFlag"]="";
        $data["prpCinsuredOwn_Flag"]="0";
        $data["prpCinsuredDiv_Flag"]="0";
        $data["prpCinsuredBon_Flag"]="0";
        $data["relationType"]="";
        $data["ciLimitDays"]="90";
        $data["udFlag"]="0";
        $data["kbFlag"]="0";
        $data["sbFlag"]="0";
        $data["xzFlag"]="0";
        $data["userType"]="02";
        $data["noNcheckFlag"]="0";
        $data["planFlag"]="0";
        $data["R_SWITCH"]="1";
        $data["biStartDate"]=date("Y-m-d",time());
        $data["ciStartDate"]=date("Y-m-d",time());
        $data["ciStartHour"]="0";
        $data["ciEndDate"]="2017-08-04";
        $data["ciEndHour"]="24";
        $data["AGENTSWITCH"]="1";
        $data["JFCDSWITCH"]="12";
        $data["carShipTaxFlag"]="11";
        $data["commissionFlag"]="";
        $data["ICCardCHeck"]="1";
        $data["riskWarningFlag"]="0";
        $data["comCodePrefix"]="51";
        $data["DAGMobilePhoneNum"]="";
        $data["scanSwitch"]="1000000000";
        $data["haveScanFlag"]="0";
        $data["diffDay"]="90";
        $data["cylinderFlag"]="";
        $data["ciPlateVersion"]="5.6.1";
        $data["biPlateVersion"]="7.0.4";
        $data["criterionFlag"]="1";
        $data["isQuotatonFlag"]="1";
        $data["quotationRisk"]="DAA";
        $data["getReplenishfactor"]="";
        $data["useYear"]="9";
        $data["FREEINSURANCEFLAG"]="001111";
        $data["isMotoDrunkDriv"]="";
        $data["immediateFlag"]="1";
        $data["immediateFlagCI"]="1";
        $data["claimAmountReason"]="";
        $data["isQueryCarModelFlag"]="";
        $data["isDirectFee"]="";
        $data["userCode"]="A510102128";
        $data["comCode"]="51010307";
        $data["chgProfitFlag"]="";
        $data["ciPlatTask"]="0000000000";
        $data["biPlatTask"]="0000000000";
        $data["upperCostRateBI"]="";
        $data["upperCostRateCI"]="";
        $data["rescueFundRate"]="";
        $data["resureFundFee"]="";
        $data["useCarshiptaxFlag"]="1";
        $data["taxFreeLicenseNo"]="";
        $data["isTaxFree"]="0";
        $data["premiumChangeFlag"]="1";
        $data["operationTimeStamp"]="2016-03-17 13:50:32";
        $data["VEHICLEPLAT"]="0";
        $data["MOTORFASTTRACK"]="";
        $data["motorFastTrack_flag"]="";
        $data["MOTORFASTTRACK_INSUREDCODE"]="";
        $data["currentDate"]=date("Y-m-d",time());
        $data["vinModifyFlag"]="";
        $data["addPolicyProjectCode"]="";
        $data["isAddPolicy"]="0";
        $data["commissionView"]="0";
        $data["specialflag"]="";
        $data["accountCheck"]="2";
        $data["projectBak"]="";
        $data["projectCodeBT"]="";
        $data["projectCodeBTback"]="";
        $data["checkTimeFlag"]="";
        $data["checkUndwrt"]="0";
        $data["carDamagedNum"]="";
        $data["insurePayTimes"]="0";
        $data["claimAdjustValue"]="0";
        $data["operatorProjectCode"]="";
        $data["lossFlagKind"]="";
        $data["chooseFlagCI"]="0";
        $data["unitedSaleRelatioStr"]="";
        $data["purchasePriceU"]="";
        $data["countryNatureU"]="";
        $data["insurancefee_reform"]="1";
        $data["operateDateForFG"]="";
        $data["prpCmainCommon.clauseIssue"]="2";
        $data["amountFloat"]="30";
        $data["purchasePriceUFlag"]="";
        $data["startDateU"]="";
        $data["endDateU"]="";
        $data["biCiFlagU"]="";
        $data["biCiFlagIsChange"]="";
        $data["biCiDateIsChange"]="";
        $data["switchFlag"]="0";
        $data["relatedFlag"]="0";
        $data["riskCode"]="DAA";
        $data["prpCmain.riskCode"]="";
        $data["riskName"]="";
        $data["prpCproposalVo.checkFlag"]="";
        $data["prpCproposalVo.underWriteFlag"]="";
        $data["prpCproposalVo.strStartDate"]="";
        $data["prpCproposalVo.othFlag"]="";
        $data["prpCproposalVo.checkUpCode"]="";
        $data["prpCproposalVo.operatorCode1"]="";
        $data["prpCproposalVo.businessNature"]="";
        $data["agentCodeValidType"]="";
        $data["agentCodeValidValue"]="";
        $data["agentCodeValidIPPer"]="";
        $data["qualificationNo"]="";
        $data["qualificationName"]="";
        $data["OLD_STARTDATE_CI"]="";
        $data["OLD_ENDDATE_CI"]="";
        $data["prpCmainCommon.greyList"]="";
        $data["reinComPany"]="";
        $data["reinPolicyNo"]="";
        $data["reinStartDate"]="";
        $data["reinEndDate"]="";
        //$data["prpCmain.proposalNo"]=""; //商业投保单
        $data["prpCmain.policyNo"]="";//商业保单
        $data["prpCmainCI.proposalNo"]="";//交强投保单
        $data["prpCmainCI.policyNo"]=""; //交强保单
        $data["prpPhead.applyNo"]="";
        $data["prpPhead.endorseNo"]="";
        $data["prpPheadCI.applyNo"]="";
        $data["prpPheadCI.endorseNo"]="";
        $data["prpCmain.comCode"]="51010307";
        $data["comCodeDes"]="成都市天府支公司中介业务二部"; //归属部门
        $data["prpCmain.handler1Code"]="08032118";
        $data["handler1CodeDes"]="唐宇"; //归属人
        $data["homePhone"]="87738951";
        $data["officePhone"]="87738951";
        $data["moblie"]="";
        $data["checkHandler1Code"]="1";
        $data["handler1CodeDesFlag"]="A";
        $data["handler1Info"]="";
        $data["prpCmainCommon.handler1code_uni"]="1251010232";
        $data["prpCmain.handlerCode"]="08032118";
        $data["handlerCodeDes"]="唐宇"; //经办人
        $data["homePhonebak"]="";
        $data["officePhonebak"]="";
        $data["mobliebak"]="";
        $data["handler1CodeDesFlagbak"]="";
        $data["prpCmainCommon.handlercode_uni"]="1251010232";
        $data["handlerInfo"]="";
        $data["prpCmain.businessNature"]="0";
        $data["businessNatureTranslation"]="传统直销业务";//业务来源
        $data["prpCmain.agentCode"]="000001000001";//渠道代码
        $data["prpCmainagentName"]="柜台业务";
        $data["agentType"]="010000";
        $data["agentCode"]="";
        $data["tempAgentCode"]="010000";
        $data["sumPremiumChgFlag"]="1";
        $data["prpCmain.sumPremium1"]=""; //总保费
        $data["sumPayTax1"]="0.00"; //总税额
        $data["prpCmain.contractNo"]=""; //团单号
        $data["prpCmain.operateDate"]=date("Y-m-d",time()); //
        $data["Today"]=date("Y-m-d",time());
        $data["OperateDate"]=date("Y-m-d",time());
        $data["prpCmain.makeCom"]="51010307";
        $data["makeComDes"]="成都市天府支公司中介业务二部"; //出单机构
        $data["prpCmain.startDate"]=!isset($business['BUSINESS_START_TIME'])?$mvtalci['MVTALCI_START_TIME']:$business['BUSINESS_START_TIME'];  //商业保险起止日期
        $data["prpCmain.startHour"]="0";  //商业保险起止日期(时)
        $data["prpCmain.endDate"]=!isset($business['BUSINESS_END_TIME'])?$mvtalci['MVTALCI_END_TIME']:$business['BUSINESS_END_TIME'];  //商业保险终止日期
        $data["prpCmain.endHour"]="24";//商业保险终止日期(时)
        $data["prpCmain.checkUpCode"]="";
        $data["prpCmainCI.startDate"]=!isset($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];  //交强保险起止日期
        $data["prpCmainCI.startHour"]="0";//交强保险起止日期(时)
        $data["prpCmainCI.endDate"]=!isset($mvtalci['MVTALCI_END_TIME'])?$business['BUSINESS_END_TIME']:$mvtalci['MVTALCI_END_TIME']; //交强保险终止日期
        $data["prpCmainCI.endHour"]="24";////交强保险终止日期(时)
        $data["carPremium"]="0.0";
        $data["insuredChangeFlag"]="0";
        $data["refreshEadFlag"]="1";
        $data["imageAdjustPixels"]="20";
        $data["prpBatchVehicle.id.contractNo"]="";
        $data["prpBatchVehicle.id.serialNo"]="";
        $data["prpBatchVehicle.motorCadeNo"]="";
        $data["prpBatchVehicle.licenseNo"]="";
        $data["prpBatchVehicle.licenseType"]="";
        $data["prpBatchVehicle.carKindCode"]="";
        $data["prpBatchVehicle.proposalNo"]="";
        $data["prpBatchVehicle.policyNo"]="";
        $data["prpBatchVehicle.sumAmount"]="";
        $data["prpBatchVehicle.sumPremium"]="";
        $data["prpBatchVehicle.prpProjectCode"]="";
        $data["prpBatchVehicle.coinsProjectCode"]="";
        $data["prpBatchVehicle.profitProjectCode"]="";
        $data["prpBatchVehicle.facProjectCode"]="";
        $data["prpBatchVehicle.flag"]="";
        $data["prpBatchVehicle.carId"]="";
        $data["prpBatchVehicle.versionNo"]="";
        $data["prpBatchMain.discountmode"]="";
        $data["minusFlag"]="";
        $data["paramIndex"]="";
        $data["batchCIFlag"]="";
        $data["batchBIFlag"]="";
        $data["pageEndorRecorder.endorFlags"]="";
        $data["endorDateEdit"]="";
        $data["validDateEdit"]="";
        $data["endDateEdit"]="";
        $data["endorType"]="";
        $data["prpPhead.endorType"]="";
        $data["generatePtextFlag"]="0";
        $data["generatePtextAgainFlag"]="0";
        $data["quotationNo"]="";
        $data["quotationFlag"]="";
        $data["customerCode"]="";
        $data["customerFlag"]="";
        $data["compensateNo"]="";
        $data["dilutiveType"]="";


        $data["prpCfixationTemp.discount"]="";
        $data["prpCfixationTemp.id.riskCode"]="DAA";
        $data["prpCfixationTemp.profits"]="";
        $data["prpCfixationTemp.cost"]="";
        $data["prpCfixationTemp.taxorAppend"]="";
        $data["prpCfixationTemp.payMentR"]="";
        $data["prpCfixationTemp.basePayMentR"]="";
        $data["prpCfixationTemp.poundAge"]="";
        $data["prpCfixationTemp.basePremium"]="";
        $data["prpCfixationTemp.riskPremium"]="";
        $data["prpCfixationTemp.riskSumPremium"]="";
        $data["prpCfixationTemp.signPremium"]="";
        $data["prpCfixationTemp.isQuotation"]="";
        $data["prpCfixationTemp.riskClass"]="B";
        $data["prpCfixationTemp.operationInfo"]="";
        $data["prpCfixationTemp.realDisCount"]="";
        $data["prpCfixationTemp.realProfits"]="";
        $data["prpCfixationTemp.realPayMentR"]="";
        $data["prpCfixationTemp.remark"]="";
        $data["prpCfixationTemp.responseCode"]="";
        $data["prpCfixationTemp.errorMessage"]="";
        $data["prpCfixationTemp.profitClass"]="A";
        $data["prpCfixationTemp.costRate"]="35";


        $data["prpCfixationCITemp.discount"]="70";
        $data["prpCfixationCITemp.id.riskCode"]="DZA";
        $data["prpCfixationCITemp.profits"]="";
        $data["prpCfixationCITemp.cost"]="";
        $data["prpCfixationCITemp.taxorAppend"]="";
        $data["prpCfixationCITemp.payMentR"]="";
        $data["prpCfixationCITemp.basePayMentR"]="";
        $data["prpCfixationCITemp.poundAge"]="";
        $data["prpCfixationCITemp.basePremium"]="";
        $data["prpCfixationCITemp.riskPremium"]="";
        $data["prpCfixationCITemp.riskSumPremium"]="";
        $data["prpCfixationCITemp.signPremium"]="";
        $data["prpCfixationCITemp.isQuotation"]="";
        $data["prpCfixationCITemp.riskClass"]="B";
        $data["prpCfixationCITemp.operationInfo"]="交强险";
        $data["prpCfixationCITemp.realDisCount"]="";
        $data["prpCfixationCITemp.realProfits"]="";
        $data["prpCfixationCITemp.realPayMentR"]="";
        $data["prpCfixationCITemp.remark"]="";
        $data["prpCfixationCITemp.responseCode"]="";
        $data["prpCfixationCITemp.errorMessage"]="";
        $data["prpCfixationCITemp.profitClass"]="A";
        $data["prpCfixationCITemp.costRate"]="";



        $data["prpCsalesFixes_[0].id.proposalNo"]="";
        $data["prpCsalesFixes_[0].id.serialNo"]="";
        $data["prpCsalesFixes_[0].comCode"]="";
        $data["prpCsalesFixes_[0].businessNature"]="";
        $data["prpCsalesFixes_[0].riskCode"]="";
        $data["prpCsalesFixes_[0].version"]="";
        $data["prpCsalesFixes_[0].isForMal"]="";
        $data["kindAndAmount"]="";
        $data["isSpecialFlag"]="";
        $data["specialEngage"]="";
        $data["licenseNoCar"]="";
        $data["prpCitemCar.carLoanFlag"]="";
        $data["carModelPlatFlag"]="0";
        $data["updateQuotation"]="";
        $data["prpCitemCar.licenseNo1"]="";
        $data["prpCitemCar.monopolyFlag"]="0";
        $data["prpCitemCar.monopolyCode"]="";
        $data["prpCitemCar.monopolyName"]="";
        $data["prpCitemCar.id.itemNo"]="1";
        $data["oldClauseType"]="";//$auto['TIAOKUAN_TYPE'];
        $data["prpCitemCar.carId"]="";
        $data["prpCitemCar.versionNo"]="";
        $data["prpCmainCar.newDeviceFlag"]="";
        $data["prpCitemCar.otherNature"]="";
        $data["prpCitemCar.flag"]="";
        $data["newCarFlagValue"]="2";
        $data["prpCitemCar.discountType"]="";
        $data["prpCitemCar.colorCode"]="";
        $data["prpCitemCar.safeDevice"]="";
        $data["prpCitemCar.coefficient1"]="1";
        $data["prpCitemCar.coefficient2"]="1";
        $data["prpCitemCar.coefficient3"]="0.1";
        $data["prpCitemCar.startSiteName"]="";
        $data["prpCitemCar.endSiteName"]="";
        $data["prpCitemCar.newCarFlag"]="1";
        $data["prpCitemCar.noNlocalFlag"]="0";
        $data["prpCitemCar.licenseFlag"]="1";
        $data["prpCitemCar.licenseNo"]=$auto['LICENSE_NO'];  //号牌号码
        $data["codeLicenseType"]="LicenseType01,04,LicenseType02,01,LicenseType03,02,LicenseType04,02,LicenseType05,02,LicenseType06,02,LicenseType07,04,LicenseType08,04,LicenseType09,01,LicenseType10,01,LicenseType11,01,LicenseType12,01,LicenseType13,04,LicenseType14,04,LicenseType15,04,   LicenseType16,04,LicenseType17,04,LicenseType18,01,LicenseType19,01,LicenseType20,01,LicenseType21,01,LicenseType22,01,LicenseType23,03,LicenseType24,01,LicenseType25,01,LicenseType31,03,LicenseType32,03,LicenseType90,02";


            foreach($this->license_type as $k=>$v){
                    if($k==$auto['LICENSE_TYPE']){
                        $data["prpCitemCar.licenseType"]=$v[0];
                        $data["LicenseTypeDes"]=$v[1];
                    }
            }

        $data["prpCitemCar.licenseColorCode"]="01"; //号牌底色        01 蓝 02 黑 03 白 04 黄 05 白蓝 99其他
        $data["LicenseColorCodeDes"]="";
        $data["prpCitemCar.engineNo"]=$auto['ENGINE_NO']; //发动机号
        $data["prpCitemCar.vinNo"]=$auto['VIN_NO'];  //VIN码
        $data["prpCitemCar.frameNo"]=$auto['VIN_NO']; //车架号

        foreach($this->vehicle_type as $key=>$val){
                if($key==$auto['VEHICLE_TYPE']){
                    $data["prpCitemCar.carKindCode"]=$val[0];
                    $data["CarKindCodeDes"]=$val[1];
                    $data["carKindCodeBak"]=$val[0];
                }
        }

        foreach($this->use_character as $ke=>$ve){
                if($ke==$auto['USE_CHARACTER']){
                    $data["prpCitemCar.useNatureCode"]=$ve[1];
                    $data["useNatureCodeBak"]=$ve[1];
                    $data["useNatureCodeTrue"]=$ve[1];
                }
        }
        //使用性质 000不区分营业非营业 111出租租凭 112城市公交 113公路客运 114旅游客运 120营业货车 121 营业挂车 180 运输型拖拉机 190 其他营业车辆 211 家庭自用汽车  212 非营业企业客车 213 非营业机关，事业团体客车 220 非营业货车 221 非营业挂车 280 兼用型拖拉机 290 其他非营业车辆
        //条款类型 F41 机动车综合条款（非营业用汽车产品） F42 机动车综合条款（家庭自用汽车产品） F43 机动车综合条款（营业用汽车产品）F44 摩托车、拖拉机条款（摩托车产品） F45 摩托车、拖拉机条款（拖拉机产品）F46 特种车产品
        /********************使用性质代码转换*******************************/

                if($auto['USE_CHARACTER']=="NON_OPERATING_PRIVATE"){
                    $result['USE_CHARACTER']="211";
                    $result['TIAOKUAN_TYPE']="F42";
                }else if($auto['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE"){
                    $result['USE_CHARACTER']="212";
                    $result['TIAOKUAN_TYPE']="F41";
                }else if($auto['USE_CHARACTER']=="NON_OPERATING_AUTHORITY"){
                    $result['USE_CHARACTER']="213";
                    $result['TIAOKUAN_TYPE']="F41";
                }else if($auto['USE_CHARACTER']=="OPERATING_LEASE_RENTAL"){
                    $result['USE_CHARACTER']="111";
                    $result['TIAOKUAN_TYPE']="F43";
                }else if($auto['USE_CHARACTER']=="OPERATING_CITY_BUS"){
                    $result['USE_CHARACTER']="112";
                    $result['TIAOKUAN_TYPE']="F43";
                }else if($auto['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS"){
                    $result['USE_CHARACTER']="113";
                    $result['TIAOKUAN_TYPE']="F43";
                }else if($auto['USE_CHARACTER']=="NON_OPERATING_TRUCK"){
                    $result['USE_CHARACTER']="220";
                    $result['TIAOKUAN_TYPE']="F41";
                }else if($auto['USE_CHARACTER']=="OPERATING_TRUCK"){
                    $result['USE_CHARACTER']="120";
                    $result['TIAOKUAN_TYPE']="F43";
                }else if($auto['USE_CHARACTER']=="NONE_OPERATING_TRAILER"){
                    $result['USE_CHARACTER']="221";
                    $result['TIAOKUAN_TYPE']="F42";
                }else if($auto['USE_CHARACTER']=="OPERATING_TRAILER"){
                    $result['USE_CHARACTER']="121";
                }else if($auto['USE_CHARACTER']=="SPECIAL_AUTO"){
                    $result['USE_CHARACTER']="290";
                    $result['TIAOKUAN_TYPE']="F42";
                }else if($auto['USE_CHARACTER']=="MOTORCYCLE"){
                    $result['USE_CHARACTER']="000";
                }else if($auto['USE_CHARACTER']=="DUAL_PURPOSE_TRACTOR"){
                    $result['USE_CHARACTER']="280";
                    $result['TIAOKUAN_TYPE']="F42";
                }else if($auto['USE_CHARACTER']=="TRANSPORT_TRACTOR"){
                    $result['USE_CHARACTER']="180";
                }else if($auto['USE_CHARACTER']=="OPERATING_LOW_SPEED_TRUCK"){
                    $result['USE_CHARACTER']="120";
                    $result['TIAOKUAN_TYPE']="F43";
                }else if($auto['USE_CHARACTER']=="NON_OPERATING_LOW_SPEED_TRUCK"){
                    $result['USE_CHARACTER']="220";
                    $result['TIAOKUAN_TYPE']="F41";
                }

        $data["prpCitemCar.clauseType"]=$result['TIAOKUAN_TYPE'];
        $data["clauseTypeBak"]=$result['TIAOKUAN_TYPE'];
        $data["prpCitemCar.enrollDate"]=$auto['ENROLL_DATE'];//初登日期
        $data["tbSelYear1"]="2015";
        $data["tbSelMonth1"]="9";
        $data["enrollDateTrue"]="";
        $busin_time= strtotime($business['BUSINESS_START_TIME']);
        $data["prpCitemCar.useYears"]=date("Y",$busin_time)-date("Y",strtotime($auto['ENROLL_DATE']));  //实际使用年数
        $data["prpCitemCar.runMiles"]="";
        $data["taxAbateForPlat"]="";
        $data["taxAbateForPlatCarModel"]="";
        $data["prpCitemCar.modelDemandNo"]="";
        $data["owner"]="";
        $data["prpCitemCar.remark"]="";
        $data["prpCitemCar.modelCode"]=$auto['MODEL_CODE'];  //车型编码
        $data["prpCitemCar.brandName"]=$auto['MODEL']; //车型名称
        $data["PurchasePriceScal"]="10";
        $data["prpCitemCar.purchasePrice"]=$auto['BUYING_PRICE']; //新车购置价格
        $data["CarActualValueTrue"]=$auto['BUYING_PRICE'];
        $data["CarActualValueTrue1"]="";
        $data["SZpurchasePriceUp"]="";
        $data["SZpurchasePriceDown"]="";
        $data["purchasePriceF48"]="200000";
        $data["purchasePriceUp"]="200";
        $data["purchasePriceDown"]="30";
        $data["purchasePriceOld"]=$auto['BUYING_PRICE'];
        $data["prpCitemCar.actualValue"]=$auto['DISCOUNT_PRICE']; //参考实际价值
        $data["prpCitemCar.tonCount"]="0.00";//$auto['tonCount'];  //核定载质量(千克)
        $data["prpCitemCar.exhaustScale"]=$auto['ENGINE'];   //排量/功率(升)
        $data["prpCitemCar.seatCount"]=$auto['SEATS'];         //核定载客量(人)
        $data["seatCountTrue"]="";
        $data["prpCitemCar.runAreaCode"]="11";   //行驶区域  03 省内  11 中华人民共和国境内(不含港澳台) 12 有固定行驶路线  13 场内
        $data["prpCitemCar.carInsuredRelation"]="1";   //被保险人和车辆关系 1所有  2使用 3管理
        $data["prpCitemCar.countryNature"]="01";  //进口/国产类 01 国产 02进口 03合资
        $data["prpCitemCar.cylinderCount"]="";
        $data["prpCitemCar.loanVehicleFlag"]="0";  //是否未还清贷款 1是 0否
        $data["prpCitemCar.transferVehicleFlag"]="0";//是否为过户车 1是 0否
        $data["prpCitemCar.transferDate"]="";  //过户日期
        $data["prpCitemCar.modelCodeAlias"]="";  //车型别名
        $data["prpCitemCar.carLotEquQuality"]=$auto['KERB_MASS']; //整备质量(千克)
        $data["isQuotation"]="1";
        $data["prpCmainCommon.queryArea"]="510000"; //指定区域 110000北京市 120000天津市 130000 河北省 140000 山西省 150000内蒙古自治区 210000辽宁省 220000 吉林省 230000黑龙江省 310000上海市 320000江苏省 330000 浙江省 340000 安徽省 350000 福建省 360000江西省 370000 山东省 410000 河南省 420000 湖北省 430000 湖南省 440000 广东省 450000 广西壮族自治区
        $data["queryArea"]="四川省";//指定查询区域
        $data["vehiclePricer"]=$auto['BUYING_PRICE']; //类比车型价格
        $data["prpCitemCar.isDropinVisitInsure"]="0";
        $data["prpCitemCar.energyType"]="0";  //能源种类 0燃油 1纯动力 2燃料电池 3插电式混合动力 4其他混合动力
        $data["prpCmainChannel.assetAgentName"]="";
        $data["prpCmainChannel.assetAgentCode"]="";
        $data["prpCmainChannel.assetAgentPhone"]="";
        $data["SYFlag"]="0";
        $data["MTFlag"]="0";
        $data["BMFlag"]="0";
        $data["STFlag"]="0";
        $data["prpCcarDevices_[0].deviceName"]="";
        $data["prpCcarDevices_[0].id.itemNo"]="1";
        $data["prpCcarDevices_[0].id.proposalNo"]="";
        $data["prpCcarDevices_[0].id.serialNo"]="";
        $data["prpCcarDevices_[0].flag"]="";
        $data["prpCcarDevices_[0].quantity"]="";
        $data["prpCcarDevices_[0].purchasePrice"]="";
        $data["prpCcarDevices_[0].buyDate"]="";
        $data["prpCcarDevices_[0].actualValue"]="";
        $data["hidden_index_citemcar"]="0";
        $data["editFlag"]="1";
        $data["prpCmainCommon.ext2"]="";
        $data["configedRepeatTimesLocal"]="1";
        $data["prpCinsureds_[0].insuredFlag"]="11100000000000000000000000000A";
        $data["iinsuredFlag"]="投保人/被保险人/车主";
        $data["iinsuredType"]="个人";
        $data["iinsuredCode"]="";//$business['DESIGNATED_DRIVER'][0]['iinsuredCode'];
        $data["iinsuredName"]="王刘勋";//$business['DESIGNATED_DRIVER']['DRIVER_NAME'];
        $data["iunitType"]="";
        $data["iidentifyType"]="身份证";
        $data["iidentifyNumber"]=$auto['IDENTIFY_NO'];
        $data["iinsuredAddress"]="";//"四川省资阳市乐至县石湍镇民兴寺村5组";
        $data["iphoneNumber"]="";
        $data["prpCinsureds_[0].id.serialNo"]="1";
        $data["prpCinsureds_[0].insuredType"]="1";
        $data["prpCinsureds_[0].insuredNature"]="1";
        $data["prpCinsureds_[0].insuredCode"]="";//"5100100006666881";
        $data["prpCinsureds_[0].insuredName"]="王刘勋";//$business['DESIGNATED_DRIVER'][0]['DRIVER_NAME'];
        $data["prpCinsureds_[0].unitType"]="";
        $data["prpCinsureds_[0].identifyType"]="01";
        $data["prpCinsureds_[0].identifyNumber"]=$auto['IDENTIFY_NO'];
        $data["prpCinsureds_[0].insuredAddress"]="";//"四川省资阳市乐至县石湍镇民兴寺村5组";
        $data["prpCinsureds_[0].phoneNumber"]="";
        $data["prpCinsureds_[0].drivingYears"]="";
        $data["prpCinsureds_[0].mobile"]="";//"15882009900";//手机号码
        $data["prpCinsureds_[0].postCode"]="636000";
        $data["prpCinsureds_[0].versionNo"]="";//"1";
        $data["prpCinsureds_[0].auditStatus"]="";//"2";
        $data["prpCinsureds_[0].sex"]="";//"2";

        $data["prpCinsureds_[0].flag"]="";
        $data["prpCinsureds_[0].age"]="";//$business['DESIGNATED_DRIVER'][0]['DRIVER_AGE'];
        $data["prpCinsureds_[0].drivingLicenseNo"]=$auto['IDENTIFY_NO'];
        $data["prpCinsureds_[0].drivingCarType"]="";
        $data["prpCinsureds_[0].appendPrintName"]="";
        $data["prpCinsureds_[0].causetroubleTimes"]="";
        $data["prpCinsureds_[0].acceptLicenseDate"]="";
        $data["isCheckRepeat_[0]"]="";
        $data["configedRepeatTimes_[0]"]="";
        $data["repeatTimes_[0]"]="";
        $data["prpCinsureds_[0].unifiedSocialCreditCode"]="";
        $data["imobile"]="";//"158****9900";
        $data["iauditStatus"]="2";
        $data["iversionNo"]="1";
        $data["hidden_index_insured"]="0";
        $data["prpCinsureds[0].insuredFlag"]="11100000000000000000000000000A";
        $data["iinsuredFlag"]="投保人/被保险人/车主";
        $data["iinsuredType"]="个人";
        $data["iinsuredCode"]="";//_COOKIE['id_cord'];
        $data["iinsuredName"]="王刘勋";//$business['DESIGNATED_DRIVER'][0]['DRIVER_NAME'];
        $data["iunitType"]="";
        $data["iidentifyType"]="身份证";
        $data["iidentifyNumber"]=$auto['IDENTIFY_NO'];
        $data["iinsuredAddress"]="";//"四川省资阳市乐至县石湍镇民兴寺村5组";
        $data["iphoneNumber"]="";
        $data["prpCinsureds[0].id.serialNo"]="1";
        $data["prpCinsureds[0].insuredType"]="1";
        $data["prpCinsureds[0].insuredNature"]="1";
        $data["prpCinsureds[0].insuredCode"]="";//$_SESSION['id_cord'];
        $data["prpCinsureds[0].insuredName"]="王刘勋";//$business['DESIGNATED_DRIVER']['DRIVER_NAME'];5100100046989814
        $data["prpCinsureds[0].unitType"]="";
        $data["prpCinsureds[0].identifyType"]="01";
        $data["prpCinsureds[0].identifyNumber"]=$auto['IDENTIFY_NO'];
        $data["prpCinsureds[0].insuredAddress"]="";//"四川省资阳市乐至县石湍镇民兴寺村5组";
        $data["prpCinsureds[0].phoneNumber"]="";
        $data["prpCinsureds[0].drivingYears"]="";
        $data["prpCinsureds[0].mobile"]="";//"15882009900";
        $data["prpCinsureds[0].postCode"]="";//"636000";
        $data["prpCinsureds[0].versionNo"]="1";
        $data["prpCinsureds[0].auditStatus"]="2";
        $data["prpCinsureds[0].sex"]="2";

        $data["prpCinsureds[0].flag"]="";
        $data["prpCinsureds[0].age"]="";//$business['DESIGNATED_DRIVER']['DRIVER_AGE'];
        $data["prpCinsureds[0].drivingLicenseNo"]=$auto['IDENTIFY_NO'];
        $data["prpCinsureds[0].drivingCarType"]="";
        $data["prpCinsureds[0].appendPrintName"]="";
        $data["prpCinsureds[0].causetroubleTimes"]="";
        $data["prpCinsureds[0].acceptLicenseDate"]="";
        $data["isCheckRepeat[0]"]="";
        $data["configedRepeatTimes[0]"]="";
        $data["repeatTimes[0]"]="";
        $data["prpCinsureds[0].unifiedSocialCreditCode"]="";
        $data["imobile"]="";//"158****9900";
        $data["iauditStatus"]="2";
        $data["iversionNo"]="1";
        $data["_insuredFlag_hide"]="投保人";
        $data["_insuredFlag_hide"]="被保险人";
        $data["_insuredFlag_hide"]="车主";
        $data["_insuredFlag_hide"]="指定驾驶人";
        $data["_insuredFlag_hide"]="受益人";
        $data["_insuredFlag_hide"]="港澳车车主";
        $data["_insuredFlag_hide"]="联系人";
        $data["_insuredFlag"]="0";
        $data["_insuredFlag_hide"]="委托人";
        $data["_resident"]="A";
        $data["_insuredType"]="1"; //类型 1个人 2团体
        $data["_insuredCode"]="";//"5100100006666881";  //客户代码
        $data["_insuredName"]="王刘勋";//$business['DESIGNATED_DRIVER'][0]['DRIVER_NAME'];  //名称
        $data["customerURL"]="http://10.134.138.16:8300/cif";
        $data["_isCheckRepeat"]="";
        $data["_configedRepeatTimes"]="";
        $data["_repeatTimes"]="";
        $data["_identifyNumber"]=$auto['IDENTIFY_NO']; //证件号码
        $data["_unifiedSocialCreditCode"]="";
        $data["_mobile"]="";//"15882009900";
        $data["_mobile1"]="";//"158****9900"; //移动电话(加密后)
        $data["_age"]="";//$business['DESIGNATED_DRIVER'][0]['DRIVER_AGE'];//年龄
        $data["_drivingYears"]=""; //驾龄
        $data["_insuredAddress"]="";//"四川省资阳市乐至县石湍镇民兴寺村5组";//地址
        $data["_postCode"]="";//"636000";//邮编
        $data["_appendPrintName"]="";
        $data["group_code"]="";
        $data["_auditStatus"]="2";
        $data["_auditStatusDes"]="审批通过";
        $data["_versionNo"]="1"; //版本号
        $data["_drivingLicenseNo"]="";//$business['DESIGNATED_DRIVER']['DRIVING_LICENCE_NO'];//驾驶证号码
        $data["_drivingCarType"]="";
        $data["CarKindLicense"]=""; //准驾车型
        $data["_causetroubleTimes"]="";//上年违章次数
        $data["_acceptLicenseDate"]="";//初次领证日期
        $data["prpCmainCar.agreeDriverFlag"]="";
        $data["updateIndex"]="-1";
        $data["prpBatchProposal.profitType"]="";
        $data["motorFastTrack_Amount"]="";
        $data["insurancefee_reform"]="1";
        $data["prpCmainCommon.clauseIssue"]="2";
        $data["prpCprofitDetailsTemp_[0].chooseFlag"]="on";
        $data["prpCprofitDetailsTemp_[0].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp_[0].condition"]="自主核保优惠系数";
        $data["profitRateTemp_[0]"]="1.00588235";
        $data["prpCprofitDetailsTemp_[0].profitRate"]="100.588235";
        $data["prpCprofitDetailsTemp_[0].profitRateMin"]="85";
        $data["prpCprofitDetailsTemp_[0].profitRateMax"]="115";
        $data["prpCprofitDetailsTemp_[0].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp_[0].id.itemKindNo"]="2";
        $data["prpCprofitDetailsTemp_[0].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp_[0].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp_[0].id.profitType"]="1";
        $data["prpCprofitDetailsTemp_[0].kindCode"]="050930";
        $data["prpCprofitDetailsTemp_[0].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp_[0].flag"]="";
        $data["prpCprofitFactorsTemp_[0].chooseFlag"]="on";
        $data["serialNo_[0]"]="3";
        $data["prpCprofitFactorsTemp_[0].profitName"]="自主核保优惠系数";
        $data["prpCprofitFactorsTemp_[0].condition"]="自主核保优惠系数";
        $data["rateTemp_[0]"]="1.00588235";
        $data["prpCprofitFactorsTemp_[0].rate"]="100.588235";
        $data["prpCprofitFactorsTemp_[0].lowerRate"]="85";
        $data["prpCprofitFactorsTemp_[0].upperRate"]="115";
        $data["prpCprofitFactorsTemp_[0].id.profitCode"]="C03";
        $data["prpCprofitFactorsTemp_[0].id.conditionCode"]="C03";
        $data["prpCprofitFactorsTemp_[0].flag"]="";
        $data["prpCprofitFactorFixesTemp_[0].id.profitCode"]="";
        $data["prpCprofitFactorFixesTemp_[0].id.conditionCode"]="";
        $data["prpCprofitFactorFixesTemp_[0].id.profitCodeForFather"]="";
        $data["prpCprofitFactorFixesTemp_[0].id.conditionCodeForFather"]="";
        $data["prpCprofitFactorFixesTemp_[0].profitName"]="";
        $data["prpCprofitFactorFixesTemp_[0].condition"]="";
        $data["prpCprofitFactorFixesTemp_[0].upperRate"]="";
        $data["prpCprofitFactorFixesTemp_[0].lowerRate"]="";
        $data["prpCprofitFactorFixesTemp_[0].rate"]="";
        $data["prpCprofitFactorFixesTemp_[0].chooseFlag"]="";
        $data["prpCprofitFactorFixesTemp_[0].flag"]="";
        $data["prpCitemKind.shortRateFlag"]="2";
        $data["prpCitemKind.shortRate"]="100";//短期
        $data["prpCitemKind.currency"]="CNY";//币别
        $data["sumBenchPremium"]=""; //标准保费
        $data["prpCmain.discount"]="";//总折扣
        $data["prpCmain.sumPremium"]="";
        $data["premiumF48"]="";
        $data["prpCmainCommon.groupFlag"]="0";
        $data["prpCmain.preDiscount"]="";//特殊折扣申请
        $data["passengersSwitchFlag"]="";
        $_SESSION['KindsTemp_amount']="";
    if(count($business['POLICY']['BUSINESS_ITEMS'])>0)
    {
        foreach($business['POLICY']['BUSINESS_ITEMS'] as $u=>$value){
                    switch ($value) {
                        case 'TVDI':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[0].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[0].min"]="";
                            $data["prpCitemKindsTemp[0].max"]="";
                            $data["prpCitemKindsTemp[0].itemKindNo"]="1";
                            $data["prpCitemKindsTemp[0].clauseCode"]="050051";
                            $data["prpCitemKindsTemp[0].kindCode"]="050202";
                            $data["prpCitemKindsTemp[0].kindName"]="机动车损失保险";
                            $data["prpCitemKindsTemp[0].deductible"]="0.00";
                            $data["prpCitemKindsTemp[0].deductibleRate"]="0.0000";
                            $data["prpCitemKindsTemp[0].pureRiskPremium"]="0.000000";
                            $data["prpCitemKindsTemp[0].amount"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[0].calculateFlag"]="Y";
                            $data["prpCitemKindsTemp[0].startDate"]="2017-02-02";
                            $data["prpCitemKindsTemp[0].startHour"]="0";
                            $data["prpCitemKindsTemp[0].endDate"]="2018-02-01";
                            $data["prpCitemKindsTemp[0].endHour"]="24";
                            $data["relateSpecial[0]"]="050930";
                            $data["prpCitemKindsTemp[0].flag"]="0011000";
                            $data["prpCitemKindsTemp[0].basePremium"]="0.00";
                            $data["prpCitemKindsTemp[0].riskPremium"]="0.00";
                            $data["prpCitemKindsTemp[0].rate"]="";
                            $data["prpCitemKindsTemp[0].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[0].disCount"]="";
                            $data["prpCitemKindsTemp[0].premium"]="";
                            $data["prpCitemKindsTemp[0].netPremium"]="";
                            $data["prpCitemKindsTemp[0].taxPremium"]="";
                            $data["prpCitemKindsTemp[0].taxRate"]="6.00000";
                            $data["prpCitemKindsTemp[0].dutyFlag"]="2";
                            break;
                        case 'TTBLI':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['TTBLI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[2].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[2].min"]="";
                            $data["prpCitemKindsTemp[2].max"]="";
                            $data["prpCitemKindsTemp[2].itemKindNo"]="";
                            $data["prpCitemKindsTemp[2].clauseCode"]="050052";
                            $data["prpCitemKindsTemp[2].kindCode"]="050602";
                            $data["prpCitemKindsTemp[2].kindName"]="第三者责任保险";
                            $data["prpCitemKindsTemp[2].unitAmount"]="";
                            $data["prpCitemKindsTemp[2].quantity"]="";
                            if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="5")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="50000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="10")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="100000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="15")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="150000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="20")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="200000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="30")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="300000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="50")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="500000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="100")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="1000000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="150")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="1500000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="200")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="2000000";
                            }

                            $data["prpCitemKindsTemp[2].calculateFlag"]="Y21Y000";
                            $data["prpCitemKindsTemp[2].startDate"]="";
                            $data["prpCitemKindsTemp[2].startHour"]="";
                            $data["prpCitemKindsTemp[2].endDate"]="";
                            $data["prpCitemKindsTemp[2].endHour"]="";
                            $data["relateSpecial[2]"]="050931";
                            $data["prpCitemKindsTemp[2].flag"]=" 100000";
                            $data["prpCitemKindsTemp[2].basePremium"]="";
                            $data["prpCitemKindsTemp[2].riskPremium"]="";
                            $data["prpCitemKindsTemp[2].rate"]="";
                            $data["prpCitemKindsTemp[2].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[2].disCount"]="";
                            $data["prpCitemKindsTemp[2].premium"]="";
                            break;
                        case 'TWCDMVI':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[1].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[1].min"]="";
                            $data["prpCitemKindsTemp[1].max"]="";
                            $data["prpCitemKindsTemp[1].itemKindNo"]="";
                            $data["prpCitemKindsTemp[1].clauseCode"]="050054";
                            $data["prpCitemKindsTemp[1].kindCode"]="050501";
                            $data["prpCitemKindsTemp[1].kindName"]="盗抢险";
                            $data["prpCitemKindsTemp[1].unitAmount"]="";
                            $data["prpCitemKindsTemp[1].quantity"]="";
                            $data["prpCitemKindsTemp[1].amount"]=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[1].calculateFlag"]="N11Y000";
                            $data["prpCitemKindsTemp[1].startDate"]="";
                            $data["prpCitemKindsTemp[1].startHour"]="";
                            $data["prpCitemKindsTemp[1].endDate"]="";
                            $data["prpCitemKindsTemp[1].endHour"]="";
                            $data["relateSpecial[1]"]="050932";
                            $data["prpCitemKindsTemp[1].flag"]=" 100000";
                            $data["prpCitemKindsTemp[1].basePremium"]="";
                            $data["prpCitemKindsTemp[1].riskPremium"]="";
                            $data["prpCitemKindsTemp[1].rate"]="";
                            $data["prpCitemKindsTemp[1].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[1].disCount"]="";
                            $data["prpCitemKindsTemp[1].premium"]="";
                            break;
                        case 'TCPLI_DRIVER':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
                            $data["prpCitemKindsTemp[3].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[3].min"]="";
                            $data["prpCitemKindsTemp[3].max"]="";
                            $data["prpCitemKindsTemp[3].itemKindNo"]="";
                            $data["prpCitemKindsTemp[3].clauseCode"]="050053";
                            $data["prpCitemKindsTemp[3].kindCode"]="050711";
                            $data["prpCitemKindsTemp[3].kindName"]="车上人员责任险（司机）";
                            $data["prpCitemKindsTemp[3].unitAmount"]="";
                            $data["prpCitemKindsTemp[3].quantity"]="";
                            $data["prpCitemKindsTemp[3].amount"]=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
                            $data["prpCitemKindsTemp[3].calculateFlag"]="Y21Y00";
                            $data["prpCitemKindsTemp[3].startDate"]="";
                            $data["prpCitemKindsTemp[3].startHour"]="";
                            $data["prpCitemKindsTemp[3].endDate"]="";
                            $data["prpCitemKindsTemp[3].endHour"]="";
                            $data["relateSpecial[3]"]="050933";
                            $data["prpCitemKindsTemp[3].flag"]="";
                            $data["prpCitemKindsTemp[3].basePremium"]="";
                            $data["prpCitemKindsTemp[3].riskPremium"]="";
                            $data["prpCitemKindsTemp[3].rate"]="";
                            $data["prpCitemKindsTemp[3].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[3].disCount"]="";
                            $data["prpCitemKindsTemp[3].premium"]="";
                            break;
                        case 'TCPLI_PASSENGER':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']*$business['POLICY']['TCPLI_PASSENGER_COUNT'];
                            $data["prpCitemKindsTemp[4].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[4].min"]="";
                            $data["prpCitemKindsTemp[4].max"]="";
                            $data["prpCitemKindsTemp[4].itemKindNo"]="";
                            $data["prpCitemKindsTemp[4].clauseCode"]="050053";
                            $data["prpCitemKindsTemp[4].kindCode"]="050712";
                            $data["prpCitemKindsTemp[4].kindName"]="车上人员责任险（乘客）";
                            $data["prpCitemKindsTemp[4].unitAmount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']; //每位乘客保额
                            $data["prpCitemKindsTemp[4].quantity"]=$business['POLICY']['TCPLI_PASSENGER_COUNT'];//共几座
                            $data["prpCitemKindsTemp[4].amount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']*$business['POLICY']['TCPLI_PASSENGER_COUNT'];//保额/限额
                            $data["prpCitemKindsTemp[4].calculateFlag"]="Y21Y00";
                            $data["prpCitemKindsTemp[4].startDate"]="";
                            $data["prpCitemKindsTemp[4].startHour"]="";
                            $data["prpCitemKindsTemp[4].endDate"]="";
                            $data["prpCitemKindsTemp[4].endHour"]="";
                            $data["relateSpecial[4]"]="050934";
                            $data["prpCitemKindsTemp[4].flag"]="";
                            $data["prpCitemKindsTemp[4].basePremium"]="";
                            $data["prpCitemKindsTemp[4].riskPremium"]="";
                            $data["prpCitemKindsTemp[4].rate"]="";
                            $data["prpCitemKindsTemp[4].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[4].disCount"]="";
                            $data["prpCitemKindsTemp[4].premium"]="";
                            break;
                        case 'BSDI':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['BSDI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[5].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[5].min"]="";
                            $data["prpCitemKindsTemp[5].max"]="";
                            $data["prpCitemKindsTemp[5].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[5].clauseCode"]="050059";
                            $data["prpCitemKindsTemp[5].kindCode"]="050211";
                            $data["relateSpecial[5]"]="050937";
                            $data["prpCitemKindsTemp[5].kindName"]="车身划痕损失险";
                            $data["prpCitemKindsTemp[5].amount"]=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[5].calculateFlag"]="N12Y000";
                            $data["prpCitemKindsTemp[5].startDate"]="";
                            $data["prpCitemKindsTemp[5].startHour"]="";
                            $data["prpCitemKindsTemp[5].endDate"]="";
                            $data["prpCitemKindsTemp[5].endHour"]="";
                            $data["prpCitemKindsTemp[5].flag"]=" 200000";
                            $data["prpCitemKindsTemp[5].basePremium"]="";
                            $data["prpCitemKindsTemp[5].riskPremium"]="";
                            $data["prpCitemKindsTemp[5].rate"]="";
                            $data["prpCitemKindsTemp[5].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[5].disCount"]="";
                            $data["prpCitemKindsTemp[5].premium"]="";
                            break;
                        case 'BGAI':
                            $data["prpCitemKindsTemp[6].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[6].min"]="";
                            $data["prpCitemKindsTemp[6].max"]="";
                            $data["prpCitemKindsTemp[6].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[6].clauseCode"]="050056";
                            $data["prpCitemKindsTemp[6].kindCode"]="050232";
                            $data["relateSpecial[6]"]="";
                            $data["prpCitemKindsTemp[6].kindName"]="玻璃单独破碎险";

                            switch ($business['POLICY']['GLASS_ORIGIN'])
                            {
                                case 'DOMESTIC':
                                    $data["prpCitemKindsTemp[6].modeCode"]="10";
                                    break;
                                case 'DOMESTIC_SPECIAL':
                                    $data["prpCitemKindsTemp[6].modeCode"]="11";
                                    break;
                                case 'IMPORTED':
                                    $data["prpCitemKindsTemp[6].modeCode"]="20";
                                    break;
                                case 'DOMESTIC_SPECIAL':
                                    $data["prpCitemKindsTemp[6].modeCode"]="21";
                                    break;
                            }
                            $data["prpCitemKindsTemp[6].amount"]="0";
                            $data["prpCitemKindsTemp[6].calculateFlag"]="N32N000";
                            $data["prpCitemKindsTemp[6].startDate"]="";
                            $data["prpCitemKindsTemp[6].startHour"]="";
                            $data["prpCitemKindsTemp[6].endDate"]="";
                            $data["prpCitemKindsTemp[6].endHour"]="";
                            $data["prpCitemKindsTemp[6].flag"]="200000";
                            $data["prpCitemKindsTemp[6].basePremium"]="0";
                            $data["prpCitemKindsTemp[6].riskPremium"]="0";
                            $data["prpCitemKindsTemp[6].rate"]="";
                            $data["prpCitemKindsTemp[6].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[6].disCount"]="";
                            $data["prpCitemKindsTemp[6].premium"]="";
                            break;
                        case 'STSFS':
                            $data["prpCitemKindsTemp[7].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[7].min"]="";
                            $data["prpCitemKindsTemp[7].max"]="";
                            $data["prpCitemKindsTemp[7].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[7].clauseCode"]="050065";
                            $data["prpCitemKindsTemp[7].kindCode"]="050253";
                            $data["relateSpecial[7]"]="";
                            $data["prpCitemKindsTemp[7].kindName"]="指定修理厂险";
                            $data["prpCitemKindsTemp[7].amount"]="0";
                            $data["prpCitemKindsTemp[7].calculateFlag"]="N32N000";
                            $data["prpCitemKindsTemp[7].startDate"]="";
                            $data["prpCitemKindsTemp[7].startHour"]="";
                            $data["prpCitemKindsTemp[7].endDate"]="";
                            $data["prpCitemKindsTemp[7].endHour"]="";
                            $data["prpCitemKindsTemp[7].flag"]="200000";
                            $data["prpCitemKindsTemp[7].basePremium"]="";
                            $data["prpCitemKindsTemp[7].riskPremium"]="";
                            $data["prpCitemKindsTemp[7].rate"]="";
                            $data["prpCitemKindsTemp[7].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[7].disCount"]="";
                            $data["prpCitemKindsTemp[7].premium"]="";
                            break;
                        case 'VWTLI':
                            $data["prpCitemKindsTemp[9].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[9].min"]="";
                            $data["prpCitemKindsTemp[9].max"]="";
                            $data["prpCitemKindsTemp[9].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[9].clauseCode"]="050060";
                            $data["prpCitemKindsTemp[9].kindCode"]="050461";
                            $data["relateSpecial[9]"]="050938";
                            $data["prpCitemKindsTemp[9].kindName"]="发动机涉水损失险";
                            $data["prpCitemKindsTemp[9].amount"]="";
                            $data["prpCitemKindsTemp[9].calculateFlag"]="N32Y000";
                            $data["prpCitemKindsTemp[9].startDate"]="";
                            $data["prpCitemKindsTemp[9].startHour"]="";
                            $data["prpCitemKindsTemp[9].endDate"]="";
                            $data["prpCitemKindsTemp[9].endHour"]="";
                            $data["prpCitemKindsTemp[9].flag"]="200000";
                            $data["prpCitemKindsTemp[9].basePremium"]="";
                            $data["prpCitemKindsTemp[9].riskPremium"]="";
                            $data["prpCitemKindsTemp[9].rate"]="";
                            $data["prpCitemKindsTemp[9].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[9].disCount"]="";
                            $data["prpCitemKindsTemp[9].premium"]="";
                            break;
                        case 'NIELI':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['NIELI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[25].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[25].itemKindNo"]="";
                            $data["prpCitemKindsTemp[25].startDate"]="";
                            $data["prpCitemKindsTemp[25].kindCode"]="050261";
                            $data["prpCitemKindsTemp[25].kindName"]="新增设备损失险";
                            $data["prpCitemKindsTemp[25].startHour"]="";
                            $data["prpCitemKindsTemp[25].endDate"]="";
                            $data["prpCitemKindsTemp[25].endHour"]="";
                            $data["prpCitemKindsTemp[25].calculateFlag"]="N12Y000";
                            $data["relateSpecial[25]"]="050936";
                            $data["prpCitemKindsTemp[25].clauseCode"]="050058";
                            $data["prpCitemKindsTemp[25].flag"]="200000";
                            $data["prpCitemKindsTemp[25].basePremium"]="";
                            $data["prpCitemKindsTemp[25].riskPremium"]="";
                            $data["prpCitemKindsTemp[25].amount"]=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[25].rate"]="";
                            $data["prpCitemKindsTemp[25].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[25].disCount"]="";
                            $data["prpCitemKindsTemp[25].premium"]="";
                            $data["subKindCode[25]"]="050261";
                            $data["subKindName[25]"]="新增设备损失险";
                            $data["relateSpecial[25]"]="050936";

                            if(!empty($business['POLICY']['NIELI_DEVICE_LIST']))
                            {
                                $devcount = count($business['POLICY']['NIELI_DEVICE_LIST']);
                                for($idx = 0; $idx<$devcount; $idx++)
                                {

                                $data["prpCcarDevices[$idx].deviceName"]= $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['NAME'];
                                $data["prpCcarDevices[$idx].id.itemNo"]=$idx+1;
                                $data["prpCcarDevices[$idx].id.proposalNo"]="";
                                $data["prpCcarDevices[$idx].id.serialNo"]=$idx+1;
                                $data["prpCcarDevices[$idx].flag"]="";
                                $data["prpCcarDevices[$idx].quantity"]= $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['COUNT'];
                                $data["prpCcarDevices[$idx].purchasePrice"]=$business['POLICY']['NIELI_DEVICE_LIST'][$idx]['BUYING_PRICE'];
                                $data["prpCcarDevices[$idx].buyDate"]=$business['POLICY']['NIELI_DEVICE_LIST'][$idx]['BUYING_DATE'];
                                $data["prpCcarDevices[$idx].actualValue"]="";
                                }
                            }
                            break;
                        case 'SLOI':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['SLOI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[8].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[8].min"]="";
                            $data["prpCitemKindsTemp[8].max"]="";
                            $data["prpCitemKindsTemp[8].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[8].clauseCode"]="050057";
                            $data["prpCitemKindsTemp[8].kindCode"]="050311";
                            $data["relateSpecial[8]"]="050935";
                            $data["prpCitemKindsTemp[8].kindName"]="自燃损失险";
                            $data["prpCitemKindsTemp[8].amount"]=$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[8].calculateFlag"]="N12Y000";
                            $data["prpCitemKindsTemp[8].startDate"]="";
                            $data["prpCitemKindsTemp[8].startHour"]="";
                            $data["prpCitemKindsTemp[8].endDate"]="";
                            $data["prpCitemKindsTemp[8].endHour"]="";
                            $data["prpCitemKindsTemp[8].flag"]="200000";
                            $data["prpCitemKindsTemp[8].basePremium"]="";
                            $data["prpCitemKindsTemp[8].riskPremium"]="";
                            $data["prpCitemKindsTemp[8].rate"]="";
                            $data["prpCitemKindsTemp[8].disCount"]="";
                            $data["prpCitemKindsTemp[8].premium"]="";
                            break;
                        case 'RDCCI':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['RDCCI_INSURANCE_QUANTITY']*$business['POLICY']['RDCCI_INSURANCE_UNIT'];
                            $data["prpCitemKindsTemp[10].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[10].itemKindNo"]="";
                            $data["prpCitemKindsTemp[10].startDate"]="";
                            $data["prpCitemKindsTemp[10].kindCode"]="050441";
                            $data["prpCitemKindsTemp[10].kindName"]="修理期间费用补偿险";
                            $data["prpCitemKindsTemp[10].unitAmount"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
                            $data["prpCitemKindsTemp[10].quantity"]=$business['POLICY']['RDCCI_INSURANCE_UNIT'];
                            $data["prpCitemKindsTemp[10].startHour"]="";
                            $data["prpCitemKindsTemp[10].endDate"]="";
                            $data["prpCitemKindsTemp[10].endHour"]="";
                            $data["prpCitemKindsTemp[10].calculateFlag"]="N12N000";
                            $data["relateSpecial[10]"]="";
                            $data["prpCitemKindsTemp[10].clauseCode"]="050061";
                            $data["prpCitemKindsTemp[10].flag"]="200000";
                            $data["prpCitemKindsTemp[10].basePremium"]="0";
                            $data["prpCitemKindsTemp[10].riskPremium"]="0";
                            $data["prpCitemKindsTemp[10].amount"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY']*$business['POLICY']['RDCCI_INSURANCE_UNIT'];
                            $data["prpCitemKindsTemp[10].rate"]="";
                            $data["prpCitemKindsTemp[10].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[10].disCount"]="";
                            break;
                        case 'MVLINFTPSI':
                            $data["prpCitemKindsTemp[19].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[19].itemKindNo"]="";
                            $data["prpCitemKindsTemp[19].startDate"]="";
                            $data["prpCitemKindsTemp[19].kindCode"]="050451";
                            $data["prpCitemKindsTemp[19].kindName"]="机动车损失保险无法找到第三方特约险";
                            $data["prpCitemKindsTemp[19].startHour"]="";
                            $data["prpCitemKindsTemp[19].endDate"]="";
                            $data["prpCitemKindsTemp[19].endHour"]="";
                            $data["prpCitemKindsTemp[19].calculateFlag"]="N32N000";
                            $data["relateSpecial[19]"]="";
                            $data["prpCitemKindsTemp[19].clauseCode"]="050064";
                            $data["prpCitemKindsTemp[19].flag"]="200000";
                            $data["prpCitemKindsTemp[19].basePremium"]="";
                            $data["prpCitemKindsTemp[19].riskPremium"]="";
                            $data["prpCitemKindsTemp[19].amount"]="";
                            $data["prpCitemKindsTemp[19].rate"]="";
                            $data["prpCitemKindsTemp[19].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[19].disCount"]="";
                            $data["prpCitemKindsTemp[19].premium"]="";
                            break;
                        case 'LIDI':
                            $_SESSION['KindsTemp_amount']+= $business['POLICY']['LIABILITY_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[20].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[20].itemKindNo"]="";
                            $data["prpCitemKindsTemp[20].startDate"]="";
                            $data["prpCitemKindsTemp[20].kindCode"]="050643";
                            $data["prpCitemKindsTemp[20].kindName"]="精神损害抚慰金责任险";
                            $data["prpCitemKindsTemp[20].startHour"]="";
                            $data["prpCitemKindsTemp[20].endDate"]="";
                            $data["prpCitemKindsTemp[20].endHour"]="";
                            $data["prpCitemKindsTemp[20].calculateFlag"]="N12Y000";
                            $data["relateSpecial[20]"]="050917";
                            $data["prpCitemKindsTemp[20].clauseCode"]="050063";
                            $data["prpCitemKindsTemp[20].flag"]="200000";
                            $data["prpCitemKindsTemp[20].basePremium"]="";
                            $data["prpCitemKindsTemp[20].riskPremium"]="";
                            $data["prpCitemKindsTemp[20].amount"]=$business['POLICY']['LIABILITY_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[20].rate"]="";
                            $data["prpCitemKindsTemp[20].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[20].disCount"]="";
                            $data["prpCitemKindsTemp[20].premium"]="";
                            break;
                        case 'TVDI_NDSI':
                            $data["prpCitemKindsTemp[13].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[13].itemKindNo"]="";
                            $data["prpCitemKindsTemp[13].startDate"]="";
                            $data["prpCitemKindsTemp[13].kindCode"]="050930";
                            $data["prpCitemKindsTemp[13].kindName"]="不计免赔险（车损险）";
                            $data["prpCitemKindsTemp[13].startHour"]="";
                            $data["prpCitemKindsTemp[13].endDate"]="";
                            $data["prpCitemKindsTemp[13].endHour"]="";
                            $data["prpCitemKindsTemp[13].calculateFlag"]="N33N000";
                            $data["relateSpecial[13]"]="";
                            $data["prpCitemKindsTemp[13].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[13].flag"]=" 200000";
                            $data["prpCitemKindsTemp[13].basePremium"]="0";
                            $data["prpCitemKindsTemp[13].riskPremium"]="0";
                            $data["prpCitemKindsTemp[13].amount"]="0";
                            $data["prpCitemKindsTemp[13].rate"]="15.0000";
                            $data["prpCitemKindsTemp[13].benchMarkPremium"]="268.15";
                            $data["prpCitemKindsTemp[13].disCount"]="0.85500000";
                            $data["prpCitemKindsTemp[0].specialFlag"]="on";
                            break;
                        case 'TTBLI_NDSI':
                            $data["prpCitemKindsTemp[2].specialFlag"]="on";
                            $data["prpCitemKindsTemp[12].itemKindNo"]="";
                            $data["prpCitemKindsTemp[12].startDate"]="";
                            $data["prpCitemKindsTemp[12].kindCode"]="050931";
                            $data["prpCitemKindsTemp[12].kindName"]="不计免赔险（三者险）";
                            $data["prpCitemKindsTemp[12].startHour"]="";
                            $data["prpCitemKindsTemp[12].endDate"]="";
                            $data["prpCitemKindsTemp[12].endHour"]="";
                            $data["prpCitemKindsTemp[12].calculateFlag"]="N33N000";
                            $data["relateSpecial[12]"]="";
                            $data["prpCitemKindsTemp[12].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[12].flag"]= "200000";
                            $data["prpCitemKindsTemp[12].basePremium"]="0";
                            $data["prpCitemKindsTemp[12].riskPremium"]="0";
                            $data["prpCitemKindsTemp[12].amount"]="0";
                            $data["prpCitemKindsTemp[12].rate"]="15.0000";
                            $data["prpCitemKindsTemp[12].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[12].disCount"]="0.85500000";
                            $data["prpCitemKindsTemp[12].chooseFlag"]="on";
                            break;
                        case 'TWCDMVI_NDSI':
                            $data["prpCitemKindsTemp[1].specialFlag"]="on";
                            $data["prpCitemKindsTemp[11].itemKindNo"]="";
                            $data["prpCitemKindsTemp[11].startDate"]="";
                            $data["prpCitemKindsTemp[11].kindCode"]="050932";
                            $data["prpCitemKindsTemp[11].kindName"]="不计免赔险（盗抢险）";
                            $data["prpCitemKindsTemp[11].startHour"]="";
                            $data["prpCitemKindsTemp[11].endDate"]="";
                            $data["prpCitemKindsTemp[11].endHour"]="";
                            $data["prpCitemKindsTemp[11].calculateFlag"]="N33N000";
                            $data["relateSpecial[11]"]="";
                            $data["prpCitemKindsTemp[11].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[11].flag"]=" 200000";
                            $data["prpCitemKindsTemp[11].basePremium"]="0";
                            $data["prpCitemKindsTemp[11].riskPremium"]="0";
                            $data["prpCitemKindsTemp[11].amount"]="0";
                            $data["prpCitemKindsTemp[11].rate"]="20.0000";
                            $data["prpCitemKindsTemp[11].benchMarkPremium"]="110.04";
                            $data["prpCitemKindsTemp[11].disCount"]="0.85500000";
                            $data["prpCitemKindsTemp[11].chooseFlag"]="on";
                            break;
                        case 'TCPLI_DRIVER_NDSI':
                            $data["prpCitemKindsTemp[3].specialFlag"]="on";
                            $data["prpCitemKindsTemp[14].itemKindNo"]="";
                            $data["prpCitemKindsTemp[14].startDate"]="";
                            $data["prpCitemKindsTemp[14].kindCode"]="050933";
                            $data["prpCitemKindsTemp[14].kindName"]="不计免赔险（车上人员（司机））";
                            $data["prpCitemKindsTemp[14].startHour"]="";
                            $data["prpCitemKindsTemp[14].endDate"]="";
                            $data["prpCitemKindsTemp[14].endHour"]="";
                            $data["prpCitemKindsTemp[14].calculateFlag"]="N33N000";
                            $data["relateSpecial[14]"]="";
                            $data["prpCitemKindsTemp[14].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[14].flag"]="";
                            $data["prpCitemKindsTemp[14].basePremium"]="0";
                            $data["prpCitemKindsTemp[14].riskPremium"]="0";
                            $data["prpCitemKindsTemp[14].amount"]="0";
                            $data["prpCitemKindsTemp[14].rate"]="";
                            $data["prpCitemKindsTemp[14].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[14].disCount"]="";
                            $data["prpCitemKindsTemp[14].chooseFlag"]="on";
                            break;
                        case 'TCPLI_PASSENGER_NDSI':
                            $data["prpCitemKindsTemp[4].specialFlag"]="on";
                            $data["prpCitemKindsTemp[15].itemKindNo"]="";
                            $data["prpCitemKindsTemp[15].startDate"]="";
                            $data["prpCitemKindsTemp[15].kindCode"]="050934";
                            $data["prpCitemKindsTemp[15].kindName"]="不计免赔险（车上人员（乘客））";
                            $data["prpCitemKindsTemp[15].startHour"]="";
                            $data["prpCitemKindsTemp[15].endDate"]="";
                            $data["prpCitemKindsTemp[15].endHour"]="";
                            $data["prpCitemKindsTemp[15].calculateFlag"]="N33N000";
                            $data["relateSpecial[15]"]="";
                            $data["prpCitemKindsTemp[15].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[15].flag"]=" 200000";
                            $data["prpCitemKindsTemp[15].basePremium"]="0";
                            $data["prpCitemKindsTemp[15].riskPremium"]="0";
                            $data["prpCitemKindsTemp[15].amount"]="0";
                            $data["prpCitemKindsTemp[15].rate"]="";
                            $data["prpCitemKindsTemp[15].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[15].disCount"]="";
                            $data["prpCitemKindsTemp[15].chooseFlag"]="on";
                            break;
                        case 'BSDI_NDSI':
                            $data["prpCitemKindsTemp[5].specialFlag"]="on";
                            $data["prpCitemKindsTemp[16].itemKindNo"]="";
                            $data["prpCitemKindsTemp[16].startDate"]="";
                            $data["prpCitemKindsTemp[16].kindCode"]="050937";
                            $data["prpCitemKindsTemp[16].kindName"]="不计免赔险（车身划痕损失险）";
                            $data["prpCitemKindsTemp[16].startHour"]="";
                            $data["prpCitemKindsTemp[16].endDate"]="";
                            $data["prpCitemKindsTemp[16].endHour"]="";
                            $data["prpCitemKindsTemp[16].calculateFlag"]="N33N000";
                            $data["relateSpecial[16]"]="";
                            $data["prpCitemKindsTemp[16].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[16].flag"]=" 200000";
                            $data["prpCitemKindsTemp[16].basePremium"]="";
                            $data["prpCitemKindsTemp[16].riskPremium"]="";
                            $data["prpCitemKindsTemp[16].amount"]="";
                            $data["prpCitemKindsTemp[16].rate"]="";
                            $data["prpCitemKindsTemp[16].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[16].disCount"]="";
                            $data["prpCitemKindsTemp[16].premium"]="";
                            $data["prpCitemKindsTemp[16].chooseFlag"]="on";
                            break;
                        case 'SLOI_NDSI':
                            $data["prpCitemKindsTemp[8].specialFlag"]="on";
                            $data["prpCitemKindsTemp[17].itemKindNo"]="";
                            $data["prpCitemKindsTemp[17].startDate"]="";
                            $data["prpCitemKindsTemp[17].kindCode"]="050935";
                            $data["prpCitemKindsTemp[17].kindName"]="不计免赔险（自燃损失险）";
                            $data["prpCitemKindsTemp[17].startHour"]="";
                            $data["prpCitemKindsTemp[17].endDate"]="";
                            $data["prpCitemKindsTemp[17].endHour"]="";
                            $data["prpCitemKindsTemp[17].calculateFlag"]="N33N000";
                            $data["relateSpecial[17]"]="";
                            $data["prpCitemKindsTemp[17].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[17].flag"]="200000";
                            $data["prpCitemKindsTemp[17].basePremium"]="";
                            $data["prpCitemKindsTemp[17].riskPremium"]="";
                            $data["prpCitemKindsTemp[17].amount"]="";
                            $data["prpCitemKindsTemp[17].rate"]="";
                            $data["prpCitemKindsTemp[17].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[17].disCount"]="";
                            $data["prpCitemKindsTemp[17].premium"]="";
                            $data["prpCitemKindsTemp[17].chooseFlag"]="on";
                            break;
                        case 'VWTLI_NDSI':
                            $data["prpCitemKindsTemp[9].specialFlag"]="on";
                            $data["prpCitemKindsTemp[21].itemKindNo"]="";
                            $data["prpCitemKindsTemp[21].startDate"]="";
                            $data["prpCitemKindsTemp[21].kindCode"]="050938";
                            $data["prpCitemKindsTemp[21].kindName"]="不计免赔险（发动机涉水损失险）";
                            $data["prpCitemKindsTemp[21].startHour"]="";
                            $data["prpCitemKindsTemp[21].endDate"]="";
                            $data["prpCitemKindsTemp[21].endHour"]="";
                            $data["prpCitemKindsTemp[21].calculateFlag"]="N33N000";
                            $data["relateSpecial[21]"]="";
                            $data["prpCitemKindsTemp[21].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[21].flag"]="200000";
                            $data["prpCitemKindsTemp[21].basePremium"]="";
                            $data["prpCitemKindsTemp[21].riskPremium"]="";
                            $data["prpCitemKindsTemp[21].amount"]="";
                            $data["prpCitemKindsTemp[21].rate"]="";
                            $data["prpCitemKindsTemp[21].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[21].disCount"]="";
                            $data["prpCitemKindsTemp[21].premium"]="";
                            $data["prpCitemKindsTemp[21].chooseFlag"]="on";
                            break;
                        case 'LIDI_NDSI':
                            //后续增加精神险
                            // $data["prpCitemKindsTemp[20].specialFlag"]="on";
                            // $data["prpCitemKindsTemp[21].chooseFlag"]="on";
                        case 'NIELI_NDSI':
                            $data["prpCitemKindsTemp[26].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[26].itemKindNo"]="";
                            $data["prpCitemKindsTemp[26].startDate"]="";
                            $data["prpCitemKindsTemp[26].kindCode"]="050936";
                            $data["prpCitemKindsTemp[26].kindName"]="不计免赔险（新增设备损失险）";
                            $data["prpCitemKindsTemp[26].startHour"]="";
                            $data["prpCitemKindsTemp[26].endDate"]="";
                            $data["prpCitemKindsTemp[26].endHour"]="";
                            $data["prpCitemKindsTemp[26].calculateFlag"]="N33N000";
                            $data["relateSpecial[26]"]="";
                            $data["prpCitemKindsTemp[26].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[26].flag"]="200000";
                            $data["prpCitemKindsTemp[26].basePremium"]="";
                            $data["prpCitemKindsTemp[26].riskPremium"]="";
                            $data["prpCitemKindsTemp[26].amount"]="";
                            $data["prpCitemKindsTemp[26].rate"]="";
                            $data["prpCitemKindsTemp[26].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[26].disCount"]="";
                            $data["prpCitemKindsTemp[26].premium"]="";
                            $data["prpCitemKindsTemp[25].specialFlag"]="on";
                    }
        }

}
        $data["prpCitemKindCI.shortRate"]="100";
        $data["prpCitemKindCI.familyNo"]="1";
        $data["cIBPFlag"]="1";
        $data["prpCitemKindCI.unitAmount"]="122000";//$mvtalci['MVTALCI_AMOUNT'];
        $data["prpCitemKindCI.id.itemKindNo"]="1";
        $data["prpCitemKindCI.kindCode"]="050100";
        $data["prpCitemKindCI.clauseCode"]="050001";
        $data["prpCitemKindCI.riskPremium"]="0";
        $data["prpCitemKindCI.kindName"]="机动车交通事故强制责任保险";
        $data["prpCitemKindCI.calculateFlag"]="Y";
        $data["prpCitemKindCI.basePremium"]="";
        $data["prpCitemKindCI.quantity"]="1";
        $data["prpCitemKindCI.amount"]="122000";//$mvtalci['MVTALCI_AMOUNT'];
        $data["prpCitemKindCI.deductible"]="0";
        $data["prpCitemKindCI.adjustRate"]="0.9";
        $data["prpCitemKindCI.rate"]="0";
        $data["prpCitemKindCI.benchMarkPremium"]="950.00";
        $data["prpCitemKindCI.disCount"]="1";
        $data["prpCitemKindCI.premium"]="";
        $data["prpCitemKindCI.flag"]="";
        $data["strCarShipFlag"]="1";
        $data["prpCcarShipTax.taxType"]="1";
        $data["prpCcarShipTax.calculateMode"]="C1";
        $data["prpCcarShipTax.taxPayerIdentNo"]="511022197402121480";
        $data["prpCcarShipTax.taxPayerNumber"]="511022197402121480";
        $data["prpCcarShipTax.carLotEquQuality"]="1280";
        $data["prpCcarShipTax.id.itemNo"]="1";
        $data["prpCcarShipTax.taxPayerNature"]="3";
        $data["prpCcarShipTax.taxPayerName"]=$auto['OWNER'];
        $data["prpCcarShipTax.taxAbateType"]="1";
        $data["prpCcarShipTax.taxUnitAmount"]="";  //税
        $data["prpCcarShipTax.prePayTaxYear"]="2015";//前次缴税年度
        $data["prpCcarShipTax.prePolicyEndDate"]="";
        $data["payTimes"]="1";
        $data["prpCplanTemps_[0].payNo"]="";
        $data["prpCplanTemps_[0].serialNo"]="";
        $data["prpCplanTemps_[0].endorseNo"]="";
        $data["cplan_[0].payReasonC"]="";
        $data["prpCplanTemps_[0].payReason"]="";
        $data["prpCplanTemps_[0].planDate"]="";
        $data["prpCplanTemps_[0].currency"]="";
        $data["description_[0].currency"]="";
        $data["prpCplanTemps_[0].planFee"]="";
        $data["cplans_[0].planFee"]="";
        $data["cplans_[0].backPlanFee"]="";
        $data["prpCplanTemps_[0].delinquentFee"]="";
        $data["prpCplanTemps_[0].flag"]="";
        $data["prpCplanTemps_[0].subsidyRate"]="";
        $data["prpCplanTemps_[0].isBICI"]="";
        $data["iniPrpCplan_Flag"]="";
        $data["loadFlag9"]="";
        $data["planfee_index"]="0";
        $data["planStr"]="";
        $data["planPayTimes"]="";
        $data["prpCmainCar.flag"]="1";
        $data["prpCmainCarFlag"]="1";
        $data["coinsSchemeCode"]="";
        $data["coinsSchemeName"]="";
        $data["mainPolicyNo"]="";
        $data["prpCcoinsMains_[0].id.serialNo"]="1";
        $data["prpCcoinsMains_[0].coIdentity"]="1";
        $data["prpCcoinsMains_[0].coinsCode"]="002";
        $data["prpCcoinsMains_[0].coinsName"]="人保财产";
        $data["prpCcoinsMains_[0].coinsRate"]="";
        $data["prpCcoinsMains_[0].id.currency"]="CNY";
        $data["prpCcoinsMains_[0].coinsAmount"]="";
        $data["prpCcoinsMains_[0].coinsPremium"]="";
        $data["prpCcoinsMains_[0].coinsPremium"]="";
        $data["iniPrpCcoins_Flag"]="";
        $data["hidden_index_ccoins"]="0";
        $data["prpCpayeeAccountBIs_[0].id.proposalNo"]="";
        $data["prpCpayeeAccountBIs_[0].id.serialNo"]="";
        $data["prpCpayeeAccountBIs_[0].itemNo"]="";
        $data["prpCpayeeAccountBIs_[0].payReason"]="";
        $data["prpCpayeeAccountBIs_[0].payeeInfoid"]="";
        $data["prpCpayeeAccountBIs_[0].accountName"]="";
        $data["prpCpayeeAccountBIs_[0].basicBankCode"]="";
        $data["prpCpayeeAccountBIs_[0].basicBankName"]="";
        $data["prpCpayeeAccountBIs_[0].recBankAreaCode"]="";
        $data["prpCpayeeAccountBIs_[0].recBankAreaName"]="";
        $data["prpCpayeeAccountBIs_[0].bankCode"]="";
        $data["prpCpayeeAccountBIs_[0].bankName"]="";
        $data["prpCpayeeAccountBIs_[0].cnaps"]="";
        $data["prpCpayeeAccountBIs_[0].accountNo"]="";
        $data["prpCpayeeAccountBIs_[0].isPrivate"]="";
        $data["prpCpayeeAccountBIs_[0].cardType"]="";
        $data["prpCpayeeAccountBIs_[0].paySumFee"]="";
        $data["prpCpayeeAccountBIs_[0].payType"]="";
        $data["prpCpayeeAccountBIs_[0].intention"]="支付他方保费";
        $data["prpCpayeeAccountBIs_[0].sendSms"]="";
        $data["prpCpayeeAccountBIs_[0].identifyType"]="";
        $data["prpCpayeeAccountBIs_[0].identifyNo"]="";
        $data["prpCpayeeAccountBIs_[0].telephone"]="";
        $data["prpCpayeeAccountBIs_[0].sendMail"]="";
        $data["prpCpayeeAccountBIs_[0].mailAddr"]="";
        $data["prpCpayeeAccountCIs_[0].id.proposalNo"]="";
        $data["prpCpayeeAccountCIs_[0].id.serialNo"]="";
        $data["prpCpayeeAccountCIs_[0].itemNo"]="";
        $data["prpCpayeeAccountCIs_[0].payReason"]="";
        $data["prpCpayeeAccountCIs_[0].payeeInfoid"]="";
        $data["prpCpayeeAccountCIs_[0].accountName"]="";
        $data["prpCpayeeAccountCIs_[0].basicBankCode"]="";
        $data["prpCpayeeAccountCIs_[0].basicBankName"]="";
        $data["prpCpayeeAccountCIs_[0].recBankAreaCode"]="";
        $data["prpCpayeeAccountCIs_[0].recBankAreaName"]="";
        $data["prpCpayeeAccountCIs_[0].bankCode"]="";
        $data["prpCpayeeAccountCIs_[0].bankName"]="";
        $data["prpCpayeeAccountCIs_[0].cnaps"]="";
        $data["prpCpayeeAccountCIs_[0].accountNo"]="";
        $data["prpCpayeeAccountCIs_[0].isPrivate"]="";
        $data["prpCpayeeAccountCIs_[0].cardType"]="";
        $data["prpCpayeeAccountCIs_[0].paySumFee"]="";
        $data["prpCpayeeAccountCIs_[0].payType"]="";
        $data["prpCpayeeAccountCIs_[0].intention"]="支付他方保费";
        $data["prpCpayeeAccountCIs_[0].sendSms"]="";
        $data["prpCpayeeAccountCIs_[0].identifyType"]="";
        $data["prpCpayeeAccountCIs_[0].identifyNo"]="";
        $data["prpCpayeeAccountCIs_[0].telephone"]="";
        $data["prpCpayeeAccountCIs_[0].sendMail"]="";
        $data["prpCpayeeAccountCIs_[0].mailAddr"]="";
        $data["iReinsCode"]="";
        $data["prpCspecialFacs_[0].reinsCode"]="001";
        $data["iFReinsCode"]="";
        $data["iPayCode"]="";
        $data["iShareRate"]="";
        $data["iCommRate"]="";
        $data["iTaxRate"]="";
        $data["iOthRate"]="";
        $data["iCommission"]="";
        $data["iOthPremium"]="";
        $data["prpCspecialFacs_[0].id.reinsNo"]="1";
        $data["prpCspecialFacs_[0].freinsCode"]="001";
        $data["prpCspecialFacs_[0].payCode"]="001";
        $data["prpCspecialFacs_[0].shareRate"]="001";
        $data["prpCspecialFacs_[0].sharePremium"]="001";
        $data["prpCspecialFacs_[0].commRate"]="001";
        $data["prpCspecialFacs_[0].taxRate"]="001";
        $data["prpCspecialFacs_[0].tax"]="001";
        $data["prpCspecialFacs_[0].othRate"]="001";
        $data["prpCspecialFacs_[0].commission"]="001";
        $data["prpCspecialFacs_[0].othPremium"]="001";
        $data["prpCspecialFacs_[0].reinsName"]="001";
        $data["prpCspecialFacs_[0].freinsName"]="001";
        $data["prpCspecialFacs_[0].payName"]="001";
        $data["prpCspecialFacs_[0].remark"]="001";
        $data["prpCspecialFacs_[0].flag"]="";
        $data["hidden_index_specialFac"]="0";
        $data["updateIndex"]="-1";
        $data["iniCspecialFac_Flag"]="";
        $data["_ReinsCode"]="";
        $data["loadFlag8"]="";
        $data["_FReinsCode"]="";
        $data["_PayCode"]="";
        $data["_ReinsName"]="";
        $data["_FReinsName"]="";
        $data["_PayName"]="";
        $data["_CommRate"]="";
        $data["_OthRate"]="";
        $data["_ShareRate"]="";
        $data["_Commission"]="";
        $data["_OthPremium"]="";
        $data["_SharePremium"]="";
        $data["_TaxRate"]="";
        $data["_Tax"]="";
        $data["_Remark"]="";
        $data["prpCsettlement.buyerUnitRank"]="3";
        $data["prpCsettlement.buyerPreFee"]="1757.75";
        $data["prpCsettlement.buyerUnitCode"]="";
        $data["prpCsettlement.buyerUnitName"]="";
        $data["prpCsettlement.upperUnitCode"]="";
        $data["upperUnitName"]="";
        $data["prpCsettlement.buyerUnitAddress"]="";
        $data["prpCsettlement.buyerLinker"]="";
        $data["prpCsettlement.buyerPhone"]="";
        $data["prpCsettlement.buyerMobile"]="";
        $data["prpCsettlement.buyerFax"]="";
        $data["prpCsettlement.buyerUnitNature"]="1";
        $data["prpCsettlement.buyerProvince"]="51000000";
        $data["buyerProvinceDes"]="人保财险四川省分公司";
        $data["prpCsettlement.buyerBusinessSort"]="01";
        $data["prpCsettlement.linkerCode"]="";
        $data["linkerName"]="";
        $data["linkerPhone"]="";
        $data["linkerMobile"]="";
        $data["linkerFax"]="";
        $data["prpCsettlement.comCode"]="";
        $data["prpCsettlement.fundForm"]="1";
        $data["prpCsettlement.flag"]="";
        $data["settlement_Flag"]="";
        $data["prpCcontriutions_[0].id.serialNo"]="1";
        $data["prpCcontriutions_[0].contribType"]="F";
        $data["prpCcontriutions_[0].contribCode"]="";
        $data["prpCcontriutions_[0].contribName"]="";
        $data["prpCcontriutions_[0].contribCode_uni"]="";
        $data["prpCcontriutions_[0].contribPercent"]="";
        $data["prpCcontriutions_[0].contribPremium"]="";
        $data["prpCcontriutions_[0].remark"]="";
        $data["hidden_index_ccontriutions"]="0";
        $data["userCode"]="A510103792";
        $data["iProposalNo"]="";
        $data["CProposalNo"]="";
        $data["timeFlag"]="";
        $data["prpCremarks_[0].id.proposalNo"]="";
        $data["prpCremarks_[0].id.serialNo"]="";
        $data["prpCremarks_[0].operatorCode"]="A510103792";
        $data["prpCremarks_[0].remark"]="";
        $data["prpCremarks_[0].flag"]="";
        $data["prpCremarks_[0].insertTimeForHis"]="";
        $data["hidden_index_remark"]="0";
        $data["ciInsureDemandCheckVo.demandNo"]="";
        $data["ciInsureDemandCheckVo.checkQuestion"]="";
        $data["ciInsureDemandCheckVo.checkAnswer"]="";
        $data["ciInsureDemandCheckCIVo.demandNo"]="";
        $data["ciInsureDemandCheckCIVo.checkQuestion"]="";
        $data["ciInsureDemandCheckCIVo.checkAnswer"]="";
        $data["ciInsureDemandCheckVo.flag"]="DEMAND";
        $data["ciInsureDemandCheckVo.riskCode"]="";
        $data["ciInsureDemandCheckCIVo.flag"]="DEMAND";
        $data["ciInsureDemandCheckCIVo.riskCode"]="";
        $data["flagCheck"]="00";
        if($_SESSION['userCode']!="" && isset($_SESSION['userCode']))
        {
            $data["userCode"]=$_SESSION['userCode'];
            $data["comCode"]=$_SESSION['comCode'];
            $data["prpCmain.comCode"]=$_SESSION['comCode'];  //不能为空
            $data["prpCmain.makeCom"]=$_SESSION['comCode'];
            $data["prpCmain.handler1Code"]=$_SESSION['handler1Code'];//$_SESSION['handler1Code'];//业务归属人员  不能为空
            $data["prpCmain.agentCode"]=$_SESSION['agentCode'];//$agentCode[1][0];//销管合同
            $data["agentCode"]=$_SESSION['agentCode'];
            $data["prpCmain.handlerCode"]=$_SESSION['handler1Code'];
            $data["prpCmain.businessNature"]=$_SESSION['Nature'];
        }
        $arr=array();
        foreach($data as $k=>$v)
        {
            $whex= iconv("UTF-8","GBK",$v);
            $arr[$k]=$whex;
        }
        return $arr;
    }
    /**
     * [documentary_cost 通过不同代理商计算跟单费用以及生成暂存单号]
     * @AuthorHTL
     * @DateTime  2016-11-16T11:28:03+0800
     * 参数:
     * @auto            必需,数组
     *    数组结构如下:
     *    array(
     *      'VEHICLE_TYPE' =>'' , //车辆类别
     *      'USE_CHARACTER'=>'' , //使用性质
     *     )
     *----------------------------------------------------------------------------
     * @business        必需,数组。
     *     数组结构如下:
     *     array(
     *       'Policy'             => array(
     *                      'BUSINESS_ITEMS'=>array(
     *                              'MVTALCI'  =>$prieum,
     *                              'TVDI' =>$prieum,
     *                          ......
     *                      )//投保险种及保费
     *
     *              ),
     *      'Total' =>array(
     *                  'discount'=>'',         //总折扣
     *                  'sumPremium'=>'',       //含税总保费
     *       )
     *       ''
     *      )
     * @return    [type]                   [description]
     */
    public function documentary_cost($auto=array(),$business=array(),$mvtalci=array())
    {	

    unset($_SESSION['Counter_Fee']);
    $data=self::get_items($business);//分配险种信息并配置渠道机构
    if($_SESSION['userCode']!="" && isset($_SESSION['userCode']))
    {
        $data["prpCmain.underWriteCode"]=$_SESSION['userCode'];
        $data["userCode"]=$_SESSION['userCode'];
        $data["comCode"]=$_SESSION['comCode'];
        $data["prpCmain.comCode"]=$_SESSION['comCode'];  //不能为空
        $data["prpCmain.makeCom"]=$_SESSION['comCode'];
        $data["prpCmain.handler1Code"]=$_SESSION['handler1Code'];//$_SESSION['handler1Code'];//业务归属人员  不能为空
        $data["prpCmain.agentCode"]=$_SESSION['agentCode'];//$agentCode[1][0];//销管合同
        $data["agentCode"]=$_SESSION['agentCode'];
        $data["prpCmain.handlerCode"]=$_SESSION['handler1Code'];
        $data["prpCmain.businessNature"]=$_SESSION['Nature'];
    }

        $data["randomProposalNo"]="";
        $data["initemKind_Flag"]="0";
        $data["editType"]="UPDATE";
        $data["bizType"]="PROPOSAL";
        $data["prpCmain.underWriteName"]="陈燕";
        $data["prpCmain.underWriteEndDate"]=date('Y-m-d H:i:s',time());
        $data["prpCmain.underWriteFlag"]="0";
        $data["bizNo"]="";
        $data["oldPolicyNo"]="";
        $data["bizNoCI"]="";
        $data["sumAmountBI"]=empty($_SESSION['KindsTemp_amount'])?"0":$_SESSION['KindsTemp_amount'];
        $data["isTaxDemand"]="1";
        $data["cIInsureFlag"]="1";
        $data["bIInsureFlag"]="1";
        $data["ciInsureSwitchKindCode"]="E01,E11,E12,D01,D02,D03,B11,B12";
        $data["ciInsureSwitchValues"]="00000000";
        $data["cIInsureMotorFlag"]="1";
        $data["noPermissionsCarKindCode"]="NO";
        $data["ZGRS_PURCHASEPRICE"]="200000";
        $data["ZGRS_LOWESTPREMIUM"]="0";
        $data["ciLimitDays"]="90";
        $data["noNcheckFlag"]="0";
        $data["planFlag"]="0";
        $data["R_SWITCH"]="1";
        $data["biStartDate"]="";//$business['BUSINESS_START_TIME'];
        $data["ciStartDate"]="";//$business['BUSINESS_START_TIME'];
        $data["ciStartHour"]="0";
        $data["ciEndDate"]="";//$business['BUSINESS_END_TIME'];
        $data["ciEndHour"]="24";
        $data["AGENTSWITCH"]="1";
        $data["JFCDSWITCH"]="12";
        $data["carShipTaxFlag"]="11";
        $data["commissionFlag"]="";
        $data["ICCardCHeck"]="1";
        $data["riskWarningFlag"]="0";
        $data["comCodePrefix"]="51";
        $data["haveScanFlag"]="0";
        $data["diffDay"]="90";
        $data["ciPlateVersion"]="6.0.0";
        $data["biPlateVersion"]="7.0.7";
        $data["criterionFlag"]="1";
        $data["isQuotatonFlag"]="1";
        $data["quotationRisk"]="DAA";
        $data["getReplenishfactor"]="";
        $data["useYear"]="";
        $data["FREEINSURANCEFLAG"]="001111";
        $data["isMotoDrunkDriv"]="";
        $data["immediateFlag"]="1";
        $data["immediateFlagCI"]="1";
        $data["claimAmountReason"]="";
        $data["isQueryCarModelFlag"]="1";
        $data["chgProfitFlag"]="";
        $data["ciPlatTask"]="0000000000";
        $data["biPlatTask"]="0000000000";
        $data["useCarshiptaxFlag"]="1";
        $data["isTaxFree"]="0";
        $data["premiumChangeFlag"]="0";
        $data["operationTimeStamp"]=date('Y-m-d H:i:s',time());
        $data["VEHICLEPLAT"]="0";
        $data["currentDate"]=date("Y-m-d",time());
        $data["isAddPolicy"]="0";
        $data["commissionView"]="0";
        $data["accountCheck"]="2";
        $data["checkUndwrt"]="0";
        $data["chooseFlagCI"]="0";
        $data["insurancefee_reform"]="1";
        $data["prpCmainCommon.clauseIssue"]="2";
        $data["amountFloat"]="30";
        $data["vat_switch"]="1";
        $data["isNetFlag"]="1";
        $data["switchFlag"]="1";
        $data["relatedFlag"]="1";
        $data["riskCode"]="DAA";
        $data["prpCmain.riskCode"]="DAA";
        $data["prpCmain.proposalNo"]="";
        $data["prpCmainCI.proposalNo"]="";
        $data["comCodeDes"]="";
        $data["handler1CodeDes"]="";
        $data["homePhone"]="";
        $data["officePhone"]="";
        $data["checkHandler1Code"]="1";
        $data["handler1CodeDesFlag"]="A";
        $data["prpCmain.operateDate"]=date("Y-m-d",time());
        $data["Today"]=date("Y-m-d",time());
        $data["makeComDes"]="";
        $data["prpCmain.startDate"]=empty($business['BUSINESS_START_TIME'])?$mvtalci['MVTALCI_START_TIME']:$business['BUSINESS_START_TIME'];
        $data["prpCmain.startHour"]="0";
        $data["prpCmain.endDate"]=empty($business['BUSINESS_END_TIME'])?$mvtalci['MVTALCI_END_TIME']:$business['BUSINESS_END_TIME'];
        $data["prpCmain.endHour"]="24";
        $data["prpCmainCI.startDate"]=empty($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];
        $data["prpCmainCI.startHour"]="0";
        $data["prpCmainCI.endDate"]=empty($mvtalci['MVTALCI_END_TIME'])?$business['BUSINESS_END_TIME']:$mvtalci['MVTALCI_END_TIME'];
        $data["prpCmainCI.endHour"]="24";
        $data["carPremium"]="0.0";
        $data["insuredChangeFlag"]="0";
        $data["refreshEadFlag"]="1";
        $data["imageAdjustPixels"]="20";



        $data["carModelPlatFlag"]="0";
        $data["updateQuotation"]="1";
        $data["prpCitemCar.monopolyFlag"]="0";
        $data["prpCitemCar.monopolyCode"]="";
        $data["prpCitemCar.monopolyName"]="";
        $data["prpCitemCar.id.itemNo"]="1";
        $data["oldClauseType"]="";
        $data["prpCitemCar.carId"]="";
        $data["prpCitemCar.versionNo"]="";
        $data["prpCmainCar.newDeviceFlag"]="";
        $data["prpCitemCar.otherNature"]="";
        $data["prpCitemCar.flag"]="";
        $data["newCarFlagValue"]="2";
        $data["prpCitemCar.coefficient1"]="1.0000";
        $data["prpCitemCar.coefficient2"]="1.0000";
        $data["prpCitemCar.coefficient3"]="0.1000";
        $data["prpCitemCar.newCarFlag"]="0";
        $data["prpCitemCar.noNlocalFlag"]="0";
        $data["prpCitemCar.licenseFlag"]="1";
        $data["prpCitemCar.licenseNo"]=$auto['LICENSE_NO'];
        $data["prpCitemCar.licenseType"]="02";
        $data["LicenseTypeDes"]="小型汽车号牌";
        $data["prpCitemCar.licenseColorCode"]="01";
        $data["LicenseColorCodeDes"]="6";
        $data["prpCitemCar.engineNo"]=$auto['ENGINE_NO'];
        $data["prpCitemCar.vinNo"]=$auto['VIN_NO'];
        $data["prpCitemCar.frameNo"]=$auto['VIN_NO'];
        $data["prpCitemCar.carKindCode"]="A01";
        $data["CarKindCodeDes"]="客车";
        $data["carKindCodeBak"]="A01";
        $data["prpCitemCar.useNatureCode"]="211";
        $data["useNatureCodeBak"]="211";
        $data["useNatureCodeTrue"]="211";
        $data["prpCitemCar.clauseType"]="F42";
        $data["clauseTypeBak"]="F42";
        $data["prpCitemCar.enrollDate"]=$auto['ENROLL_DATE'];
        $data["enrollDateTrue"]=$auto['ENROLL_DATE'];
        $busin_time= strtotime($business['BUSINESS_START_TIME']);
        $data["prpCitemCar.useYears"]=date("Y",$busin_time)-date("Y",strtotime($auto['ENROLL_DATE']));  //实际使用年数
        $data["prpCitemKind.shortRateFlag"]="2";
        $data["prpCitemKind.shortRate"]="100.0000";
        $data["prpCitemKind.currency"]="CNY";
        $data["prpCmainCommon.groupFlag"]="0";
        $data["prpCmain.preDiscount"]="";
        $data["sumBenchPremium"]=round($_SESSION['COUNT_PREMIUM']/$_SESSION['DISCOUNT'],2);//标准保费
        $data["prpCmain.discount"]=$_SESSION['DISCOUNT'];//总折扣
        $data["prpCmain.sumPremium"]=$_SESSION['COUNT_PREMIUM'];//含税总保费
        $data["premiumF48"]="5000";
        $data["prpCmain.sumNetPremium"]=$_SESSION['NET_PREMIUM'];
        $data["prpCmain.sumTaxPremium"]=$_SESSION['TAX'];
        $data["passengersSwitchFlag"]="";
        $data["prpCitemKindCI.shortRate"]="100.0000";
        if($mvtalci['MVTALCI_PREMIUM']!="")
        {
           $data["prpCitemKindCI.familyNo"]="1"; 
        }
        else
        {
           $data["prpCitemKindCI.familyNo"]="0"; 
        }
        $data["cIBPFlag"]="1";
        $data["prpCitemKindCI.unitAmount"]="122000.00";
        $data["prpCitemKindCI.id.itemKindNo"]="1";
        $data["prpCitemKindCI.kindCode"]="050100";
        $data["prpCitemKindCI.clauseCode"]="050001";
        $data["prpCitemKindCI.riskPremium"]="0.00";
        $data["prpCitemKindCI.kindName"]="机动车交通事故强制责任保险";
        $data["prpCitemKindCI.calculateFlag"]="Y";
        $data["prpCitemKindCI.basePremium"]="";
        $data["prpCitemKindCI.quantity"]="1";
        $data["prpCitemKindCI.amount"]="122000.00";
        $data["prpCitemKindCI.deductible"]="0.00";
        $data["prpCitemKindCI.adjustRate"]=empty($mvtalci['MVTALCI_COUNT'])?"":$mvtalci['MVTALCI_COUNT'];
        $data["prpCitemKindCI.rate"]="0";
        $data["prpCitemKindCI.benchMarkPremium"]=empty($_SESSION['BASE_PREMIUM'])?"":$_SESSION['BASE_PREMIUM'];
        $data["prpCitemKindCI.disCount"]="1";
        $data["prpCitemKindCI.premium"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data["prpCitemKindCI.flag"]="100000";
        $data["prpCitemKindCI.netPremium"]=empty($_SESSION['NetPremium'])?"":$_SESSION['NetPremium'];
        $data["prpCitemKindCI.taxPremium"]=empty($_SESSION['MVTALCI_TAX_PREMIUM'])?"":$_SESSION['MVTALCI_TAX_PREMIUM'];
        $data["prpCitemKindCI.taxRate"]="6.00000";
        $data["prpCitemKindCI.dutyFlag"]="2";
        $data["prpCagents[0].roleType"]="";
        $data["roleTypeName[0]"]="代理人";
        $data["prpCagents[0].id.roleCode"]="";
        $data["prpCagents[0].roleCode_uni"]="";
        $data["prpCagents[0].roleName"]="";
        $data["prpCagents[0].costRate"]="";
        $data["prpCagents[0].costFee"]="";
        $data["prpCagents[0].flag"]="";
        $data["prpCagents[0].businessNature"]="";
        $data["prpCagents[0].isMain"]="";
        $data["prpCagentCIs[0].roleType"]="";
        $data["roleTypeNameCI[0]"]="代理人";
        $data["prpCagentCIs[0].id.roleCode"]="";
        $data["prpCagentCIs[0].roleCode_uni"]="";
        $data["prpCagentCIs[0].roleName"]="";
        $data["prpCagentCIs[0].costRate"]="";
        $data["prpCagentCIs[0].costFee"]="";
        $data["prpCagentCIs[0].flag"]="";
        $data["prpCagentCIs[0].businessNature"]="";
        $data["prpCagentCIs[0].isMain"]="";
        $data["scmIsOpen"]="";
        $data["prpCagents_[0].roleType"]="";
        $data["roleTypeName_[0]"]="代理人";
        $data["prpCagents_[0].id.roleCode"]="";
        $data["prpCagents_[0].roleCode_uni"]="";
        $data["prpCagents_[0].roleName"]="";
        $data["prpCagents_[0].costRate"]="";
        $data["prpCagents_[0].costFee"]="";
        $data["prpCagents_[0].flag"]="";
        $data["prpCagents_[0].businessNature"]="";
        $data["prpCagents_[0].isMain"]="1";
        $data["prpCagentCIs_[0].roleType"]="";
        $data["roleTypeNameCI_[0]"]="代理人";
        $get_Caent= self::requestPostData($this->checkAgentUrl,$data); 
        $array=array();
        $apps=json_decode($get_Caent,true);
        if($apps['totalRecords']==0)
        {
            $error['errorMsg']="请先计算保费后在提交核保";
            $error['state']=1;
            return json_encode($error);
        }
        $planStr= explode(",", $apps['data'][0]['planStr']);
        if($apps['totalRecords']==1)
        {

                if($apps['data'][0]['riskCode']=="DAA")
                {

                    $DAA_costRate= $apps['data'][0]['costRate']/100;
                    $array['DAA']['DAA_USER']=$apps['data'][0]['ruleInfos'][0]['scmRuleDetailInfoVos'][0]['agentName'];//分配者名称
                    $array['DAA']['DAA_TAX_AMOUNT']=$business['POLICY']['TOTAL_BUSINESS_PREMIUM']; //$DZZ_Plan[0];
                    $array['DAA']['DAA_AMOUNT']= self::PayForSCMS($_SESSION['NET_PREMIUM'],$DAA_costRate,2);
                    $array['DAA']['Protocol_Number']= $apps['data'][0]['ruleNo'];
                    $array['DAA']['CostRate']= $apps['data'][0]['costRate'];//初始手续费比例
                    $array['DAA']['CostRateUpper']= $apps['data'][0]['costRateDefaultUpper'];//默认上限比例
                }
                else if($apps['data'][0]['riskCode']=="DZA")
                {
 
                    $DZA_costRate= $apps['data'][0]['costRate']/100;
                    $array['DZA']['DZA_USER']=$apps['data'][0]['ruleInfos'][0]['scmRuleDetailInfoVos'][0]['agentName'];//分配者名称
                    $array['DZA']['DZA_TAX_AMOUNT']=$mvtalci['MVTALCI_PREMIUM']; //$DZA_Plan[0];
                    $array['DZA']['DZA_AMOUNT']= self::PayForSCMS($_SESSION['NetPremium'],$DZA_costRate);
                    $array['DZA']['Protocol_Number']= $apps['data'][0]['ruleNo'];
                    $array['DZA']['CostRate']= $apps['data'][0]['costRate'];//初始手续费比例
                    $array['DZA']['CostRateUpper']= $apps['data'][0]['costRateDefaultUpper'];//默认上限比例
                } 
            
        }
        if ($apps['totalRecords']==2) 
        {

            foreach($apps['data'] as $k =>$v)
            {   

                if($v['riskCode']=="DAA")
                {
                   
                    $DAA_costRate= $apps['data'][0]['costRate']/100;
                    $array['DAA']['DAA_USER']=$apps['data'][0]['ruleInfos'][0]['scmRuleDetailInfoVos'][0]['agentName'];//分配者名称
                    $array['DAA']['DAA_TAX_AMOUNT']=$business['POLICY']['TOTAL_BUSINESS_PREMIUM'];
                    $array['DAA']['DAA_AMOUNT']= self::PayForSCMS($_SESSION['NET_PREMIUM'],$DAA_costRate);
                    $array['DAA']['Protocol_Number']= $apps['data'][0]['ruleNo'];
                    $array['DAA']['CostRate']= $apps['data'][0]['costRate'];//初始手续费比例
                    $array['DAA']['CostRateUpper']= $apps['data'][0]['costRateDefaultUpper'];//默认上限比例
                }
                else if($v['riskCode']=="DZA")
                {
                    $DZA_costRate= $apps['data'][1]['costRate']/100;
                    $array['DZA']['DZA_USER']=$apps['data'][1]['ruleInfos'][0]['scmRuleDetailInfoVos'][0]['agentName'];//分配者名称
                    $array['DZA']['DZA_TAX_AMOUNT']=$mvtalci['MVTALCI_PREMIUM'];
                    $array['DZA']['DZA_AMOUNT']= self::PayForSCMS($_SESSION['NetPremium'],$DZA_costRate);
                    $array['DZA']['Protocol_Number']= $apps['data'][1]['ruleNo'];
                    $array['DZA']['CostRate']= $apps['data'][1]['costRate'];//初始手续费比例
                    $array['DZA']['CostRateUpper']= $apps['data'][1]['costRateDefaultUpper'];//默认上限比例
                } 
            }
                
                if($mvtalci['MVTALCI_PREMIUM']=="")
                {
                    unset($array['DZA']);
                    
                }
                
                if(count($business['POLICY']['BUSINESS_ITEMS'])==0)
                {
                    unset($array['DAA']);
                    
                }
                
            
        }
        $_SESSION['Counter_Fee']=$array;
        return self::Company_handling($auto,$business,$mvtalci);


    }



    /**
     * [Company_handling 计算辅助核保]
     * @AuthorHTL
     * @DateTime  2016-12-15T16:37:05+0800
     * @param     array                    $auto     [传递数组]
     * @param     array                    $business [传递数组]
     * @param     array                    $mvtalci  [返回辅助核保信息]
     */
    private function Company_handling($auto=array(),$business=array(),$mvtalci=array())
    {           

        $data=self::get_items($business);//分配险种信息并配置渠道机构
        if($_SESSION['userCode']!="" && isset($_SESSION['userCode']))
        {
            $data["prpCmain.underWriteCode"]=$_SESSION['userCode'];
            $data["userCode"]=$_SESSION['userCode'];
            $data["comCode"]=$_SESSION['comCode'];
            $data["prpCmain.comCode"]=$_SESSION['comCode'];  //不能为空
            $data["prpCmain.makeCom"]=$_SESSION['comCode'];
            $data["prpCmain.handler1Code"]=$_SESSION['handler1Code'];//$_SESSION['handler1Code'];//业务归属人员  不能为空
            $data["prpCmain.agentCode"]=$_SESSION['agentCode'];//$agentCode[1][0];//销管合同
            $data["agentCode"]=$_SESSION['agentCode'];
            $data["prpCmain.handlerCode"]=$_SESSION['handler1Code'];
            $data["prpCmain.businessNature"]=$_SESSION['Nature'];
            $data["prpCmain.operatorCode"]=$_SESSION['userCode'];
        }


$data["initemKind_Flag"]="0";
$data["editType"]="UPDATE";
$data["bizType"]="PROPOSAL";
$data["prpCmain.renewalFlag"]="03";
$data["INTEGRAL_SWITCH"]="1";
$data["GuangdongSysFlag"]="0";
$data["GDCANCIINFOFlag"]="0";
$data["prpCmain.checkFlag"]="0";
$data["prpCmain.othFlag"]="000000YY0011";
$data["prpCmain.dmFlag"]="0";
$data["prpCmain.underWriteName"]="陈燕";
$data["prpCmain.underWriteEndDate"]=date('Y-m-d H:i:s',time());
$data["prpCmain.underWriteFlag"]="0";
$data["sumAmountBI"]=empty($_SESSION['KindsTemp_amount'])?"0":$_SESSION['KindsTemp_amount'];
$data["isTaxDemand"]="1";
$data["cIInsureFlag"]="1";
$data["bIInsureFlag"]="1";
$data["ciInsureSwitchKindCode"]="E01,E11,E12,D01,D02,D03,B11,B12";
$data["ciInsureSwitchValues"]="00000000";
$data["cIInsureMotorFlag"]="1";
$data["noPermissionsCarKindCode"]="NO";
$data["ZGRS_PURCHASEPRICE"]="200000";
$data["ZGRS_LOWESTPREMIUM"]="0";
$data["ciLimitDays"]="90";
$data["noNcheckFlag"]="0";
$data["planFlag"]="0";
$data["R_SWITCH"]="1";
$data["biStartDate"]=$business['BUSINESS_START_TIME'];
$data["ciStartDate"]=$business['BUSINESS_START_TIME'];
$data["ciStartHour"]="0";
$data["ciEndDate"]=$business['BUSINESS_END_TIME'];
$data["ciEndHour"]="24";
$data["AGENTSWITCH"]="1";
$data["JFCDSWITCH"]="12";
$data["carShipTaxFlag"]="11";
$data["commissionFlag"]="";
$data["ICCardCHeck"]="1";
$data["riskWarningFlag"]="0";
$data["comCodePrefix"]="51";
$data["DAGMobilePhoneNum"]="";
$data["scanSwitch"]="";
$data["haveScanFlag"]="0";
$data["diffDay"]="90";
$data["ciPlateVersion"]="6.0.0";
$data["biPlateVersion"]="7.0.7";
$data["criterionFlag"]="1";
$data["isQuotatonFlag"]="1";
$data["quotationRisk"]="DAA";
$data["useYear"]="9";
$data["FREEINSURANCEFLAG"]="001111";
$data["immediateFlag"]="1";
$data["immediateFlagCI"]="1";
$data["claimAmountReason"]="";
$data["isQueryCarModelFlag"]="1";
$data["ciPlatTask"]="0000000000";
$data["biPlatTask"]="0000000000";
$data["useCarshiptaxFlag"]="1";
$data["isTaxFree"]="0";
$data["premiumChangeFlag"]="0";
$data["operationTimeStamp"]=date('Y-m-d H:i:s',time());
$data["VEHICLEPLAT"]="0";
$data["currentDate"]=date("Y-m-d",time());
$data["isAddPolicy"]="0";
$data["commissionView"]="0";
$data["accountCheck"]="2";
$data["checkUndwrt"]="0";
$data["chooseFlagCI"]="0";
$data["insurancefee_reform"]="1";
$data["prpCmainCommon.clauseIssue"]="2";
$data["amountFloat"]="30";
$data["vat_switch"]="1";
$data["isNetFlag"]="1";
$data["switchFlag"]="1";
$data["relatedFlag"]="1";
$data["riskCode"]="DAA";
$data["prpCmain.riskCode"]="DAA";
$data["homePhone"]="15822023360";
$data["officePhone"]="15822023360";
$data["checkHandler1Code"]="1";
$data["handler1CodeDesFlag"]="A";
$data["sumPremiumChgFlag"]="0";

        if($mvtalci['MVTALCI_PREMIUM']=="")
        {   
            $data["prpCmain.sumPremium1"]=$_SESSION['COUNT_PREMIUM'];
        }

        if($_SESSION['COUNT_PREMIUM']=="")
        {
           $data["prpCmain.sumPremium1"]=$mvtalci['MVTALCI_PREMIUM'];
        }

        if($mvtalci['MVTALCI_PREMIUM']!="" &&  $_SESSION['COUNT_PREMIUM']!="")
        {
          $data["prpCmain.sumPremium1"]=$_SESSION['COUNT_PREMIUM']+$mvtalci['MVTALCI_PREMIUM'];  
        } 


$data["sumPayTax1"]=$mvtalci['TAX_PREMIUM'];
$data["prpCmain.operateDate"]=date("Y-m-d",time());
$data["Today"]=date("Y-m-d",time());
$data["prpCmain.startDate"]=empty($business['BUSINESS_START_TIME'])?$mvtalci['MVTALCI_START_TIME']:$business['BUSINESS_START_TIME'];
$data["prpCmain.startHour"]="0";
$data["prpCmain.endDate"]=empty($business['BUSINESS_END_TIME'])?$mvtalci['MVTALCI_END_TIME']:$business['BUSINESS_END_TIME'];
$data["prpCmain.endHour"]="24";
$data["prpCmainCI.startDate"]=empty($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];
$data["prpCmainCI.startHour"]="0";
$data["prpCmainCI.endDate"]=empty($mvtalci['MVTALCI_END_TIME'])?$business['BUSINESS_END_TIME']:$mvtalci['MVTALCI_END_TIME'];
$data["prpCmainCI.endHour"]="24";
$data["carPremium"]="0.0";
$data["insuredChangeFlag"]="0";
$data["refreshEadFlag"]="1";
$data["imageAdjustPixels"]="20";
$data["generatePtextFlag"]="0";
$data["generatePtextAgainFlag"]="0";
$data["IS_LOAN_MODIFY"]="20";
$data["isCarinfoPlat"]="20";
$data["carModelPlatFlag"]="0";
$data["updateQuotation"]="1";
$data["prpCitemCar.monopolyFlag"]="0";
$data["prpCitemCar.id.itemNo"]="1";
$data["newCarFlagValue"]="2";
$data["prpCitemCar.coefficient1"]="1.0000";
$data["prpCitemCar.coefficient2"]="1.0000";
$data["prpCitemCar.coefficient3"]="0.1000";
$data["prpCitemCar.newCarFlag"]="0";
$data["prpCitemCar.noNlocalFlag"]="0";
$data["prpCitemCar.licenseFlag"]="1";
$data["prpCitemCar.licenseNo"]=$auto['LICENSE_NO'];
$data["codeLicenseType"]="";
$data["prpCitemCar.licenseType"]="02";
$data["LicenseTypeDes"]="小型汽车号牌";
$data["prpCitemCar.licenseColorCode"]="01";
$data["LicenseColorCodeDes"]="6";
$data["prpCitemCar.engineNo"]=$auto['ENGINE_NO'];
$data["prpCitemCar.vinNo"]=$auto['VIN_NO'];
$data["prpCitemCar.frameNo"]=$auto['VIN_NO'];
$data["prpCitemCar.carKindCode"]="A01";
$data["CarKindCodeDes"]="客车";
$data["carKindCodeBak"]="A01";
$data["prpCitemCar.useNatureCode"]="211";
$data["useNatureCodeBak"]="211";
$data["useNatureCodeTrue"]="211";
$data["prpCitemCar.clauseType"]="F42";
$data["clauseTypeBak"]="F42";
$data["prpCitemCar.enrollDate"]=$auto['ENROLL_DATE'];
$data["enrollDateTrue"]=$auto['ENROLL_DATE'];
$busin_time= strtotime($business['BUSINESS_START_TIME']);
$data["prpCitemCar.useYears"]=date("Y",$busin_time)-date("Y",strtotime($auto['ENROLL_DATE']));  //实际使用年数
$data["prpCitemCar.modelCode"]=$auto['MODEL_CODE'];  //车型编码
$data["prpCitemCar.brandName"]=$auto['MODEL']; //车型名称
$data["PurchasePriceScal"]="10";
$data["prpCitemCar.purchasePrice"]=$auto['BUYING_PRICE'];
$data["CarActualValueTrue"]=$auto['BUYING_PRICE'];
$data["CarActualValueTrue1"]=$auto['BUYING_PRICE'];
$data["purchasePriceF48"]="200000";
$data["purchasePriceUp"]="200";
$data["purchasePriceDown"]="30";
$data["purchasePriceOld"]=$auto['BUYING_PRICE'];
$data["prpCitemCar.actualValue"]=$auto['DISCOUNT_PRICE']; //参考实际价值
$data["prpCitemCar.tonCount"]="0";
$data["prpCitemCar.exhaustScale"]=$auto['ENGINE'];
$data["prpCitemCar.seatCount"]=$auto['SEATS'];
$data["seatCountTrue"]="5";
$data["prpCitemCar.runAreaCode"]="11";
$data["prpCitemCar.carInsuredRelation"]="1";
$data["prpCitemCar.countryNature"]="01";
$data["prpCitemCar.cylinderCount"]="";
$data["prpCitemCar.loanVehicleFlag"]="0";
$data["prpCitemCar.transferVehicleFlag"]="0";
$data["prpCitemCar.transferDate"]="";
$data["prpCitemCar.modelCodeAlias"]="";
$data["prpCitemCar.carLotEquQuality"]=$auto['KERB_MASS']; //整备质量(千克)
$data["isQuotation"]="1";
$data["prpCmainCommon.queryArea"]="510000";
$data["queryArea"]="四川省";
$data["prpCitemCar.isDropinVisitInsure"]="0";
$data["prpCitemCar.energyType"]="0";
$data["SYFlag"]="0";
$data["MTFlag"]="0";
$data["BMFlag"]="0";
$data["STFlag"]="0";
$data["hidden_index_citemcar"]="0";
$data["editFlag"]="1";
$data["prpCmainCommon.ext2"]="";
$data["configedRepeatTimesLocal"]="1";
$data["prpCinsureds_[0].insuredFlag"]="1";
$data["iinsuredFlag"]="001";
$data["iinsuredType"]="001";
$data["iinsuredCode"]="001";
$data["iinsuredName"]="001";
$data["iunitType"]="001";
$data["iidentifyType"]="001";
$data["iidentifyNumber"]="001";
$data["iinsuredAddress"]="001";
$data["iphoneNumber"]="001";
$data["imobile"]="001";
$data["iauditStatus"]="001";
$data["iversionNo"]="001";
$data["prpCinsureds[0].insuredFlag"]="11100000000000000000000000000A";
$data["display_insuredFlag"]="投保人/被保险人/车主";
$data["prpCinsureds[0].id.serialNo"]="1";
$data["display_InsuredNature"]="个人";
$data["prpCinsureds[0].insuredType"]="1";
$data["prpCinsureds[0].insuredNature"]="3";
$data["prpCinsureds[0].insuredCode"]="5100100006346538";
$data["prpCinsureds[0].insuredName"]="杨治会";
$data["display_identifyType"]="身份证";
$data["prpCinsureds[0].identifyType"]="01";
$data["prpCinsureds[0].identifyNumber"]="532127197408170023";
$data["prpCinsureds[0].insuredAddress"]=!isset($business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'])?"":$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'];
$data["prpCinsureds[0].sex"]="2";
$data["prpCinsureds[0].versionNo"]="1";
$data["prpCinsureds[0].auditStatus"]="2";
$data["prpCinsureds[0].countryCode"]="CHN";
$data["prpCinsureds[0].age"]="39";
$data["prpCinsureds[0].drivingLicenseNo"]="532127197408170023";
$data["hidden_index_insured"]="1";
$data["_insuredFlag_hide"]="投保人";
$data["_insuredFlag_hide"]="被保险人";
$data["_insuredFlag_hide"]="车主";
$data["_insuredFlag_hide"]="指定驾驶人";
$data["_insuredFlag_hide"]="受益人";
$data["_insuredFlag_hide"]="港澳车车主";
$data["_insuredFlag_hide"]="联系人";
$data["_insuredFlag"]="0";
$data["_insuredFlag_hide"]="委托人";
$data["_insuredType"]="1";
$data["customerURL"]="http://10.134.138.16:8300/cif";
$data["_identifyType"]="01";
$data["_sex"]="0";
$data["_countryCode"]="CHN";
$data["_soldierRelations"]="0";
$data["_soldierIdentifyType"]="000";
$data["updateIndex"]="-1";
$data["insurancefee_reform"]="1";
$data["claimAdjustReason"]="";
$data["prpCmainCommon.clauseIssue"]="2";
$data["prpCitemKind.shortRateFlag"]="2";
$data["prpCitemKind.shortRate"]="100.0000";
$data["prpCitemKind.currency"]="CNY";
$data["prpCmainCommon.groupFlag"]="0";
$data["prpCmain.preDiscount"]="";
$data["sumBenchPremium"]=round($_SESSION['COUNT_PREMIUM']/$_SESSION['DISCOUNT'],2);//标准保费
$data["prpCmain.discount"]=$_SESSION['DISCOUNT'];//总折扣
$data["prpCmain.sumPremium"]=$_SESSION['COUNT_PREMIUM'];//含税总保费
$data["premiumF48"]="5000";
$data["prpCmain.sumNetPremium"]=$_SESSION['NET_PREMIUM'];
$data["prpCmain.sumTaxPremium"]=$_SESSION['TAX'];
$data["passengersSwitchFlag"]="";



$data["switchFlag"]="0";
$data["actProfitRate"]="";
$data["prpCitemCarExt.lastDamagedBI"]="0";
$data["lastDamagedBITemp"]="0";
$data["prpCitemCarExt.thisDamagedBI"]="0";
$data["prpCitemCarExt.noDamYearsBI"]="3";
$data["noDamYearsBINumber"]="3";
$data["prpCitemKindCI.shortRate"]="100.0000";


        if($mvtalci['MVTALCI_PREMIUM']!="")
        {
            $data["prpCitemKindCI.familyNo"]="1"; 
            $data["prpCcommissionsTemp[0].costType"]="";
            $data["prpCcommissionsTemp[0].riskCode"]="DZA";
            $data["prpCcommissionsTemp[0].currency"]="CNY";
            $data["prpCcommissionsTemp[0].adjustFlag"]="0";
            $data["prpCcommissionsTemp[0].upperFlag"]="0";
            $data["prpCcommissionsTemp[0].auditRate"]="";
            $data["prpCcommissionsTemp[0].auditFlag"]="1";
            $data["prpCcommissionsTemp[0].sumPremium"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
            $data["prpCcommissionsTemp[0].costRate"]=empty($_SESSION['Counter_Fee']['DZA']['CostRate'])?"":$_SESSION['Counter_Fee']['DZA']['CostRate'];
            $data["prpCcommissionsTemp[0].costRateUpper"]=empty($_SESSION['Counter_Fee']['DZA']['CostRateUpper'])?"":$_SESSION['Counter_Fee']['DZA']['CostRateUpper'];
            $data["prpCcommissionsTemp[0].coinsRate"]="100.0000";
            $data["prpCcommissionsTemp[0].coinsDeduct"]="1";
            $data["prpCcommissionsTemp[0].costFee"]=empty($_SESSION['Counter_Fee']['DZA']['DZA_AMOUNT'])?"":$_SESSION['Counter_Fee']['DZA']['DZA_AMOUNT'];
            $data["prpCcommissionsTemp[0].agreementNo"]="RULE20125100000000015";
            $data["prpCcommissionsTemp[0].configCode"]="PUB";
        }


        if($mvtalci['MVTALCI_PREMIUM']=="")
        {
           $data["prpCitemKindCI.familyNo"]="0"; 
        }
        else
        {
           $data["prpCitemKindCI.familyNo"]="1"; 
        }    

$data["cIBPFlag"]="1";
$data["prpCitemKindCI.unitAmount"]="122000.00";
$data["prpCitemKindCI.id.itemKindNo"]="1";
$data["prpCitemKindCI.kindCode"]="050100";
$data["prpCitemKindCI.clauseCode"]="050001";
$data["prpCitemKindCI.riskPremium"]="0.00";
$data["prpCitemKindCI.kindName"]="机动车交通事故强制责任保险";
$data["prpCitemKindCI.calculateFlag"]="Y";
$data["prpCitemKindCI.basePremium"]="";
$data["prpCitemKindCI.quantity"]="1";
$data["prpCitemKindCI.amount"]="122000.00";
$data["prpCitemKindCI.deductible"]="0.00";
$data["prpCitemKindCI.adjustRate"]=empty($mvtalci['MVTALCI_COUNT'])?"":$mvtalci['MVTALCI_COUNT'];
$data["prpCitemKindCI.rate"]="0";
$data["prpCitemKindCI.benchMarkPremium"]=empty($_SESSION['BASE_PREMIUM'])?"":$_SESSION['BASE_PREMIUM'];
$data["prpCitemKindCI.disCount"]="1";
$data["prpCitemKindCI.premium"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
$data["prpCitemKindCI.flag"]="100000";
$data["prpCitemKindCI.netPremium"]=empty($_SESSION['NetPremium'])?"":$_SESSION['NetPremium'];
$data["prpCitemKindCI.taxPremium"]=empty($_SESSION['MVTALCI_TAX_PREMIUM'])?"":$_SESSION['MVTALCI_TAX_PREMIUM'];
$data["prpCitemKindCI.taxRate"]="6.00000";
$data["prpCitemKindCI.dutyFlag"]="2";


if(!empty($business['POLICY']))
{
    $data["prpCcommissionsTemp[1].costType"]="";
    $data["prpCcommissionsTemp[1].riskCode"]="DAA";
    $data["prpCcommissionsTemp[1].currency"]="CNY";
    $data["prpCcommissionsTemp[1].adjustFlag"]="0";
    $data["prpCcommissionsTemp[1].upperFlag"]="0";
    $data["prpCcommissionsTemp[1].auditRate"]="";
    $data["prpCcommissionsTemp[1].auditFlag"]="1";
    $data["prpCcommissionsTemp[1].sumPremium"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
    $data["prpCcommissionsTemp[1].costRate"]=empty($_SESSION['Counter_Fee']['DAA']['CostRate'])?"":$_SESSION['Counter_Fee']['DAA']['CostRate'];
    $data["prpCcommissionsTemp[1].costRateUpper"]=empty($_SESSION['Counter_Fee']['DAA']['CostRateUpper'])?"":$_SESSION['Counter_Fee']['DAA']['CostRateUpper'];
    $data["prpCcommissionsTemp[1].coinsRate"]="100.0000";
    $data["prpCcommissionsTemp[1].coinsDeduct"]="1";
    $data["prpCcommissionsTemp[1].costFee"]=empty($_SESSION['Counter_Fee']['DAA']['DAA_AMOUNT'])?"":$_SESSION['Counter_Fee']['DAA']['DAA_AMOUNT'];
    $data["prpCcommissionsTemp[1].agreementNo"]="RULE20145194000000018";
    $data["prpCcommissionsTemp[1].configCode"]="PUB";
}
    $data["hidden_index_commission"]="2";
    $data["prpCagents[0].roleType"]="";
    $data["prpAnciInfo.operSellExpensesRateBI"]="15";//商业险比例
    $arr=array();
    foreach($data as $k=>$v)
    {
            $whex= iconv("UTF-8","GBK",$v);
            $arr[$k]=$whex;
    }
        $get_Caent= self::requestPostData($this->calAnciInfo_Url,$arr);
        $get_s= json_decode($get_Caent,true);
        if($get_s['totalRecords']>0)
        {
            $Company_handling['data']=$get_s['data'][0];
        }
        else
        {
                $this->error['errorMsg']="计算辅助核保失败,请稍后再试";
                return false;
        }
        return self::Preservation($auto,$business,$mvtalci,$Company_handling);

    }

     /**
      * [Preservation 新增或更新暂存单号]
      * @AuthorHTL
      * @DateTime  2016-12-15T16:37:57+0800
      * @param     array                    $auto             [传递数组]
      * @param     array                    $business         [传递数组]
      * @param     array                    $mvtalci          [传递数组]
      * @param     array                    $Company_handling [传递数组]
      */
    private function Preservation($auto=array(),$business=array(),$mvtalci=array(),$Company_handling=array())
    {

                if(isset($business['BUSINESS_NUMBER_INSURED']) && $business['BUSINESS_NUMBER_INSURED']!="" && !isset($mvtalci['MVTALCI_NUMBER_INSURED']) && $mvtalci['MVTALCI_NUMBER_INSURED']=="")
                {
                   $axp[0]=self::checkBefore($auto,$business,$mvtalci);//查询商业险保险暂存单 
                }

                if(isset($mvtalci['MVTALCI_NUMBER_INSURED']) && $mvtalci['MVTALCI_NUMBER_INSURED']!="" && !isset($business['BUSINESS_NUMBER_INSURED']) && $business['BUSINESS_NUMBER_INSURED']=="")
                {
                   $axp[1]=self::checkBefore($auto,$business,$mvtalci);//查询交强险保险暂存单 
                }

                if(isset($mvtalci['MVTALCI_NUMBER_INSURED']) && $mvtalci['MVTALCI_NUMBER_INSURED']!="" && isset($business['BUSINESS_NUMBER_INSURED']) && $business['BUSINESS_NUMBER_INSURED']!="")
                {

                   $axp[]=self::checkBefore($auto,$business,$mvtalci);//查询商业险保险暂存单 


                }
                
                if(!empty($mvtalci['MVTALCI_PREMIUM']) && $mvtalci['MVTALCI_PREMIUM']!="")
                {
                    if($mvtalci['MVTALCI_NUMBER_INSURED']=="")
                    {
                        if(!isset($axp['TDZA']) && $axp['TDZA']=="")
                        {
                            $data_where["prpCmainCI.proposalNo"]="";//如果没有交强险单号，就新增
                        }
                        else
                        {
                            $data_where["prpCmainCI.proposalNo"]=$mvtalci['MVTALCI_NUMBER_INSURED'];//如果有交强险单号，就更新
                        }    
                        
                    }
                    else
                    {
                        $data_where["prpCmainCI.proposalNo"]=$mvtalci['MVTALCI_NUMBER_INSURED']; //$checkBefores['TDZA'];
                    }    
                    
                }

                if(empty($mvtalci['MVTALCI_PREMIUM']) && $mvtalci['MVTALCI_PREMIUM']=="")
                {
                    if($mvtalci['MVTALCI_NUMBER_INSURED']=="")
                    {    
                        if(!isset($axp['TDZA']) && $axp['TDZA']=="")
                        {
                            $data_where["prpCmainCI.proposalNo"]="";//交强险投保单号  
                        }
                        else
                        {
                            $data_where["prpCmainCI.proposalNo"]=$mvtalci['MVTALCI_NUMBER_INSURED'];//交强险投保单号
                        }
                    }
                    else
                    {
                            $data_where["prpCmainCI.proposalNo"]=$mvtalci['MVTALCI_NUMBER_INSURED'];//交强险投保单号
                    }        
                    
                }



                    
                if(count($business['POLICY']['BUSINESS_ITEMS'])>0)
                {

                    if($business['BUSINESS_NUMBER_INSURED']=="")
                    {
                        if(!isset($axp['TDAA']) && $axp['TDAA']=="")
                        {
                            $data_where["prpCmain.proposalNo"]="";
                        }
                        else
                        {
                            $data_where["prpCmain.proposalNo"]=$axp['TDAA'];
                        }    
                        
                    }
                    else
                    {
                        $data_where["prpCmain.proposalNo"]=$business['BUSINESS_NUMBER_INSURED']; //商业险投保单号
                    }    

                    
                }

                if(count($business['POLICY']['BUSINESS_ITEMS'])==0)
                {
                    if($business['BUSINESS_NUMBER_INSURED']=="")
                    {
                        if(!isset($axp['TDAA']) && $axp['TDAA']=="")
                        {
                           $data_where["prpCmain.proposalNo"]=""; 
                        }
                        else
                        {
                            $data_where["prpCmain.proposalNo"]=$axp['TDAA'];
                        }
                    }
                    else
                    {
                            $data_where["prpCmain.proposalNo"]=$business['BUSINESS_NUMBER_INSURED'];
                    }        
                    
                }   

        $data_where["carShipTaxPlatFormFlag"]="";
        $data_where["randomProposalNo"]="3441024221479970448984";
        $data_where["initemKind_Flag"]="1";
        $data_where["editType"]="UPDATE";
        $data_where["bizType"]="PROPOSAL";
        $data_where["ABflag"]="";
        $data_where["isBICI"]="";
        $data_where["prpCmain.renewalFlag"]="03";
        $data_where["activityFlag"]="";
        $data_where["INTEGRAL_SWITCH"]="1";
        $data_where["GuangdongSysFlag"]="0";
        $data_where["GDREALTIMECARFlag"]="";
        $data_where["GDREALTIMEMOTORFlag"]="";
        $data_where["GDCANCIINFOFlag"]="0";
        $data_where["prpCmain.checkFlag"]="0";
        $data_where["prpCmain.othFlag"]="000000YY0011";
        $data_where["prpCmain.dmFlag"]="0";
        $data_where["prpCmainCI.dmFlag"]="";
        $data_where["prpCmain.underWriteCode"]=$_SESSION['userCode'];
        $data_where["prpCmain.underWriteName"]="陈燕";
        $data_where["prpCmain.underWriteEndDate"]=date('Y-m-d H:i:s',time());
        $data_where["prpCmain.underWriteFlag"]="2";
        $data_where["prpCmainCI.checkFlag"]="";
        $data_where["prpCmainCI.underWriteFlag"]="";
        $data_where["bizNo"]="";//$checkBefores['TDAA'];
        $data_where["applyNo"]="";
        $data_where["oldPolicyNo"]="";//$checkBefores['TDAA'];
        $data_where["bizNoBZ"]="";
        $data_where["bizNoCI"]="";//$checkBefores['TDZA'];
        $data_where["prpPhead.endorDate"]="";
        $data_where["prpPhead.validDate"]="";
        $data_where["prpPhead.comCode"]="";
        $data_where["sumAmountBI"]=empty($_SESSION['KindsTemp_amount'])?"0":$_SESSION['KindsTemp_amount'];
        $data_where["isTaxDemand"]="1";
        $data_where["cIInsureFlag"]="1";
        $data_where["bIInsureFlag"]="1";
        $data_where["ciInsureSwitchKindCode"]="E01,E11,E12,D01,D02,D03,B11,B12";
        $data_where["ciInsureSwitchValues"]="00000000";
        $data_where["cIInsureMotorFlag"]="1";
        $data_where["mtPlatformTime"]="";
        $data_where["noPermissionsCarKindCode"]="NO";
        $data_where["isTaxFlag"]="";
        $data_where["rePolicyNo"]="";
        $data_where["oldPolicyType"]="";
        $data_where["ZGRS_PURCHASEPRICE"]="200000";
        $data_where["ZGRS_LOWESTPREMIUM"]="0";
        $data_where["clauseFlag"]="";
        $data_where["prpCinsuredOwn_Flag"]="0";
        $data_where["prpCinsuredDiv_Flag"]="0";
        $data_where["prpCinsuredBon_Flag"]="0";
        $data_where["relationType"]="";
        $data_where["ciLimitDays"]="90";
        $data_where["udFlag"]="0";
        $data_where["kbFlag"]="0";
        $data_where["sbFlag"]="0";
        $data_where["xzFlag"]="1";
        $data_where["userType"]="";
        $data_where["noNcheckFlag"]="0";
        $data_where["planFlag"]="1";
        $data_where["R_SWITCH"]="1";
        $data_where["biStartDate"]=$business['BUSINESS_START_TIME'];
        $data_where["ciStartDate"]=$business['BUSINESS_START_TIME'];
        $data_where["ciStartHour"]="0";
        $data_where["ciEndDate"]=$business['BUSINESS_END_TIME'];
        $data_where["ciEndHour"]="24";
        $data_where["AGENTSWITCH"]="1";
        $data_where["JFCDSWITCH"]="12";
        $data_where["carShipTaxFlag"]="11";
        $data_where["commissionFlag"]="";
        $data_where["ICCardCHeck"]="1";
        $data_where["riskWarningFlag"]="0";
        $data_where["comCodePrefix"]="51";
        $data_where["DAGMobilePhoneNum"]="";
        $data_where["scanSwitch"]="";
        $data_where["haveScanFlag"]="0";
        $data_where["diffDay"]="90";
        $data_where["cylinderFlag"]="";
        $data_where["ciPlateVersion"]="6.0.0";
        $data_where["biPlateVersion"]="7.0.7";
        $data_where["criterionFlag"]="1";
        $data_where["isQuotatonFlag"]="1";
        $data_where["quotationRisk"]="DAA";
        $data_where["getReplenishfactor"]="";
        $data_where["useYear"]="9";
        $data_where["FREEINSURANCEFLAG"]="001111";
        $data_where["isMotoDrunkDriv"]="";
        $data_where["immediateFlag"]="1";
        $data_where["immediateFlagCI"]="1";
        $data_where["claimAmountReason"]="";
        $data_where["isQueryCarModelFlag"]="1";
        $data_where["isDirectFee"]="";
        $data_where["chgProfitFlag"]="";
        $data_where["ciPlatTask"]="0000000000";
        $data_where["biPlatTask"]="0000000000";
        $data_where["upperCostRateBI"]="";
        $data_where["upperCostRateCI"]="";
        $data_where["rescueFundRate"]="";
        $data_where["resureFundFee"]="";
        $data_where["useCarshiptaxFlag"]="1";
        $data_where["taxFreeLicenseNo"]="";
        $data_where["isTaxFree"]="0";
        $data_where["premiumChangeFlag"]="0";
        $data_where["operationTimeStamp"]=date('Y-m-d H:i:s',time());
        $data_where["VEHICLEPLAT"]="0";
        $data_where["MOTORFASTTRACK"]="";
        $data_where["motorFastTrack_flag"]="";
        $data_where["MOTORFASTTRACK_INSUREDCODE"]="";
        $data_where["currentDate"]=date("Y-m-d",time());
        $data_where["vinModifyFlag"]="";
        $data_where["addPolicyProjectCode"]="";
        $data_where["isAddPolicy"]="0";
        $data_where["commissionView"]="0";
        $data_where["specialflag"]="";
        $data_where["accountCheck"]="2";
        $data_where["projectBak"]="";
        $data_where["projectCodeBT"]="";
        $data_where["projectCodeBTback"]="";
        $data_where["checkTimeFlag"]="0";
        $data_where["checkUndwrt"]="0";
        $data_where["carDamagedNum"]="";
        $data_where["insurePayTimes"]="";
        $data_where["claimAdjustValue"]="";
        $data_where["operatorProjectCode"]="";
        $data_where["lossFlagKind"]="";
        $data_where["chooseFlagCI"]="0";
        $data_where["unitedSaleRelatioStr"]="";
        $data_where["purchasePriceU"]="";
        $data_where["countryNatureU"]="";
        $data_where["insurancefee_reform"]="1";
        $data_where["operateDateForFG"]="";
        $data_where["prpCmainCommon.clauseIssue"]="2";
        $data_where["prpCmainCommon.key1"]="";
        $data_where["amountFloat"]="30";
        $data_where["vat_switch"]="1";
        $data_where["pm_vehicle_switch"]="";
        $data_where["isNetFlagEad"]="";
        $data_where["isNetFlag"]="1";
        $data_where["netCommission_SwitchEad"]="";
        $data_where["BiLastPolicyFlag"]="";
        $data_where["CiLastPolicyFlag"]="";
        $data_where["CiLastEffectiveDate"]="";
        $data_where["CiLastExpireDate"]="";
        $data_where["benchMarkPremium"]="";
        $data_where["BiLastEffectiveDate"]="";
        $data_where["BiLastExpireDate"]="";
        $data_where["lastTotalPremium"]="";
        $data_where["purchasePriceUFlag"]="";
        $data_where["startDateU"]="";
        $data_where["endDateU"]="";
        $data_where["biCiFlagU"]="";
        $data_where["biCiFlagIsChange"]="";
        $data_where["biCiDateIsChange"]="";
        $data_where["switchFlag"]="1";
        $data_where["relatedFlag"]="1";
        $data_where["riskCode"]="DAA";
        $data_where["prpCmain.riskCode"]="DAA";
        $data_where["riskName"]="";
        $data_where["prpCproposalVo.checkFlag"]="";
        $data_where["prpCproposalVo.underWriteFlag"]="";
        $data_where["prpCproposalVo.strStartDate"]="";
        $data_where["prpCproposalVo.othFlag"]="";
        $data_where["prpCproposalVo.checkUpCode"]="";
        $data_where["prpCproposalVo.operatorCode1"]="";
        $data_where["prpCproposalVo.businessNature"]="";
        $data_where["agentCodeValidType"]="";
        $data_where["agentCodeValidValue"]="";
        $data_where["agentCodeValidIPPer"]="";
        $data_where["qualificationNo"]="";
        $data_where["qualificationName"]="";
        $data_where["OLD_STARTDATE_CI"]="";
        $data_where["OLD_ENDDATE_CI"]="";
        $data_where["prpCmainCommon.greyList"]="";
        $data_where["prpCmainCommon.image"]="";
        $data_where["reinComPany"]="";
        $data_where["reinPolicyNo"]="";
        $data_where["reinStartDate"]="";
        $data_where["reinEndDate"]="";
        $data_where["prpCmain.policyNo"]="";
        $data_where["prpCmainCI.policyNo"]="";
        $data_where["prpPhead.applyNo"]="";
        $data_where["prpPhead.endorseNo"]="";
        $data_where["prpPheadCI.applyNo"]="";
        $data_where["prpPheadCI.endorseNo"]="";
        $data_where["prpCmain.comCode"]=$_SESSION['comCode'];
        $data_where["comCodeDes"]="车险代理业务部";
        $data_where["prpCmain.handler1Code"]=$_SESSION['handler1Code'];
        $data_where["handler1CodeDes"]="吴东";
        $data_where["homePhone"]="13402816181";
        $data_where["officePhone"]="13402816181";
        $data_where["moblie"]="";
        $data_where["checkHandler1Code"]="1";
        $data_where["handler1CodeDesFlag"]="A";
        $data_where["handler1Info"]="08025773_FIELD_SEPARATOR_吴东_FIELD_SEPARATOR_13402816181_FIELD_SEPARATOR_13402816181_FIELD_SEPARATOR__FIELD_SEPARATOR_A_FIELD_SEPARATOR_1251032923";
        $data_where["prpCmainCommon.handler1code_uni"]="1251032923";
        $data_where["prpCmain.handlerCode"]=$_SESSION['userCode'];
        $data_where["handlerCodeDes"]="陈燕";
        $data_where["homePhonebak"]="";
        $data_where["officePhonebak"]="";
        $data_where["mobliebak"]="";
        $data_where["handler1CodeDesFlagbak"]="";
        $data_where["prpCmainCommon.handlercode_uni"]="";
        $data_where["handlerInfo"]="A519400237_FIELD_SEPARATOR_陈燕_FIELD_SEPARATOR__FIELD_SEPARATOR__FIELD_SEPARATOR__FIELD_SEPARATOR__FIELD_SEPARATOR_";
        $data_where["prpCmain.businessNature"]="3";
        $data_where["businessNatureTranslation"]="兼业代理业务";
        $data_where["prpCmain.agentCode"]=$_SESSION['agentCode'];
        $data_where["prpCmainagentName"]="成都市驹乐车友服务有限责任公司";//_SESSION['Counter_Fee']['DAA']['DAA_USER'];
        $data_where["agentType"]="010000";
        $data_where["agentCode"]=$_SESSION['agentCode'];
        $data_where["tempAgentCode"]="010000";
        $data_where["sumPremiumChgFlag"]="0";
        if($mvtalci['MVTALCI_PREMIUM']=="" && $_SESSION['COUNT_PREMIUM']!="")
        {   
            $data_where["prpCmain.sumPremium1"]=$_SESSION['COUNT_PREMIUM'];
        }

        if($_SESSION['COUNT_PREMIUM']=="" && $mvtalci['MVTALCI_PREMIUM']!="")
        {
           $data_where["prpCmain.sumPremium1"]=$mvtalci['MVTALCI_PREMIUM'];
        }

        if($mvtalci['MVTALCI_PREMIUM']!="" &&  $_SESSION['COUNT_PREMIUM']!="")
        {
          $data_where["prpCmain.sumPremium1"]=$_SESSION['COUNT_PREMIUM']+$mvtalci['MVTALCI_PREMIUM'];  
        } 
        
                    
        $data_where["sumPayTax1"]=$mvtalci['TAX_PREMIUM'];
        $data_where["prpCmain.contractNo"]="";
        $data_where["prpCmain.operateDate"]=date("Y-m-d",time());
        $data_where["Today"]=date("Y-m-d",time());
        $data_where["OperateDate"]="";
        $data_where["prpCmain.makeCom"]=$_SESSION['comCode'];
        $data_where["makeComDes"]="车险代理业务部";
        $data_where["prpCmain.startDate"]=empty($business['BUSINESS_START_TIME'])?$mvtalci['MVTALCI_START_TIME']:$business['BUSINESS_START_TIME'];
        $data_where["prpCmain.startHour"]="0";
        $data_where["prpCmain.endDate"]=empty($business['BUSINESS_END_TIME'])?$mvtalci['MVTALCI_END_TIME']:$business['BUSINESS_END_TIME'];
        $data_where["prpCmain.endHour"]="24";
        $data_where["prpCmain.checkUpCode"]="";
        $data_where["prpCmainCI.startDate"]=empty($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];
        $data_where["prpCmainCI.startHour"]="0";
        $data_where["prpCmainCI.endDate"]=empty($mvtalci['MVTALCI_END_TIME'])?$business['BUSINESS_END_TIME']:$mvtalci['MVTALCI_END_TIME'];
        $data_where["prpCmainCI.endHour"]="24";
        $data_where["carPremium"]="0.0";
        $data_where["insuredChangeFlag"]="0";
        $data_where["refreshEadFlag"]="1";
        $data_where["imageAdjustPixels"]="20";
        $data_where["prpBatchVehicle.id.contractNo"]="";
        $data_where["prpBatchVehicle.id.serialNo"]="";
        $data_where["prpBatchVehicle.motorCadeNo"]="";
        $data_where["prpBatchVehicle.licenseNo"]="";
        $data_where["prpBatchVehicle.licenseType"]="";
        $data_where["prpBatchVehicle.carKindCode"]="";
        $data_where["prpBatchVehicle.proposalNo"]="";
        $data_where["prpBatchVehicle.policyNo"]="";
        $data_where["prpBatchVehicle.sumAmount"]="";
        $data_where["prpBatchVehicle.sumPremium"]="";
        $data_where["prpBatchVehicle.prpProjectCode"]="";
        $data_where["prpBatchVehicle.coinsProjectCode"]="";
        $data_where["prpBatchVehicle.profitProjectCode"]="";
        $data_where["prpBatchVehicle.facProjectCode"]="";
        $data_where["prpBatchVehicle.flag"]="";
        $data_where["prpBatchVehicle.carId"]="";
        $data_where["prpBatchVehicle.versionNo"]="";
        $data_where["prpBatchMain.discountmode"]="";
        $data_where["minusFlag"]="";
        $data_where["paramIndex"]="";
        $data_where["batchCIFlag"]="";
        $data_where["batchBIFlag"]="";
        $data_where["pageEndorRecorder.endorFlags"]="";
        $data_where["endorDateEdit"]="";
        $data_where["validDateEdit"]="";
        $data_where["endDateEdit"]="";
        $data_where["endorType"]="";
        $data_where["prpPhead.endorType"]="";
        $data_where["generatePtextFlag"]="0";
        $data_where["generatePtextAgainFlag"]="0";
        $data_where["quotationNo"]="";
        $data_where["quotationFlag"]="";
        $data_where["customerCode"]="";
        $data_where["customerFlag"]="";
        $data_where["compensateNo"]="";
        $data_where["dilutiveType"]="";

        $data_where["prpCfixationTemp.discount"]="43.35000";
        $data_where["prpCfixationTemp.id.riskCode"]="DAA";
        $data_where["prpCfixationTemp.profits"]="0.00";
        $data_where["prpCfixationTemp.cost"]="8.00";
        $data_where["prpCfixationTemp.taxorAppend"]="5.58";
        $data_where["prpCfixationTemp.payMentR"]="62.86";
        $data_where["prpCfixationTemp.basePayMentR"]="44.00";
        $data_where["prpCfixationTemp.poundAge"]="20.00";
        $data_where["prpCfixationTemp.basePremium"]="147110075.61";
        $data_where["prpCfixationTemp.riskPremium"]="63992735.20";
        $data_where["prpCfixationTemp.riskSumPremium"]="0.00";
        $data_where["prpCfixationTemp.signPremium"]="117654589.11";
        $data_where["prpCfixationTemp.isQuotation"]="";
        $data_where["prpCfixationTemp.riskClass"]="B";
        $data_where["prpCfixationTemp.operationInfo"]="家用车连续三年及以上未出险";
        $data_where["prpCfixationTemp.realDisCount"]="43.35000";
        $data_where["prpCfixationTemp.realProfits"]="-15.08";
        $data_where["prpCfixationTemp.realPayMentR"]="101.50";
        $data_where["prpCfixationTemp.remark"]="";
        $data_where["prpCfixationTemp.responseCode"]="";
        $data_where["prpCfixationTemp.errorMessage"]="";
        $data_where["prpCfixationTemp.profitClass"]="E";
        $data_where["prpCfixationTemp.costRate"]="35.00";


        $data_where["prpCfixationCITemp.discount"]="70.00000";
        $data_where["prpCfixationCITemp.id.riskCode"]="DZA";
        $data_where["prpCfixationCITemp.profits"]="0.00";
        $data_where["prpCfixationCITemp.cost"]="8.00";
        $data_where["prpCfixationCITemp.taxorAppend"]="5.58";
        $data_where["prpCfixationCITemp.payMentR"]="62.86";
        $data_where["prpCfixationCITemp.basePayMentR"]="44.00";
        $data_where["prpCfixationCITemp.poundAge"]="20.00";
        $data_where["prpCfixationCITemp.basePremium"]="147110075.61";
        $data_where["prpCfixationCITemp.riskPremium"]="63992735.20";
        $data_where["prpCfixationCITemp.riskSumPremium"]="0.00";
        $data_where["prpCfixationCITemp.signPremium"]="117654589.11";
        $data_where["prpCfixationCITemp.isQuotation"]="";
        $data_where["prpCfixationCITemp.riskClass"]="B";
        $data_where["prpCfixationCITemp.operationInfo"]="交强险";
        $data_where["prpCfixationCITemp.realDisCount"]="70.00000";
        $data_where["prpCfixationCITemp.realProfits"]="32.266";
        $data_where["prpCfixationCITemp.realPayMentR"]="62.86";
        $data_where["prpCfixationCITemp.remark"]="";
        $data_where["prpCfixationCITemp.responseCode"]="";
        $data_where["prpCfixationCITemp.errorMessage"]="";
        $data_where["prpCfixationCITemp.profitClass"]="A";
        $data_where["prpCfixationCITemp.costRate"]="0.00";




        $data_where["prpCsalesFixes_[0].id.proposalNo"]="";
        $data_where["prpCsalesFixes_[0].id.serialNo"]="";
        $data_where["prpCsalesFixes_[0].comCode"]="";
        $data_where["prpCsalesFixes_[0].businessNature"]="";
        $data_where["prpCsalesFixes_[0].riskCode"]="";
        $data_where["prpCsalesFixes_[0].version"]="";
        $data_where["prpCsalesFixes_[0].isForMal"]="";
        $data_where["IS_LOAN_MODIFY"]="10";
        $data_where["isCarinfoPlat"]="10";
        $data_where["kindAndAmount"]="";
        $data_where["isSpecialFlag"]="";
        $data_where["specialEngage"]="";
        $data_where["licenseNoCar"]="";
        $data_where["prpCitemCar.carLoanFlag"]="";
        $data_where["carModelPlatFlag"]="0";
        $data_where["updateQuotation"]="1";
        $data_where["prpCitemCar.licenseNo1"]="";
        $data_where["pmCarOwner"]="";
        $data_where["prpCitemCar.monopolyFlag"]="0";
        $data_where["prpCitemCar.monopolyCode"]="";
        $data_where["prpCitemCar.monopolyName"]="";
        $data_where["prpCitemCar.id.itemNo"]="1";
        $data_where["oldClauseType"]="F42";
        $data_where["prpCitemCar.carId"]="";
        $data_where["prpCitemCar.versionNo"]="";
        $data_where["prpCmainCar.newDeviceFlag"]="1";
        $data_where["prpCitemCar.otherNature"]="";
        $data_where["prpCitemCar.flag"]="";
        $data_where["newCarFlagValue"]="2";
        $data_where["prpCitemCar.discountType"]="";
        $data_where["prpCitemCar.colorCode"]="";
        $data_where["prpCitemCar.safeDevice"]="";
        $data_where["prpCitemCar.coefficient1"]="1.0000";
        $data_where["prpCitemCar.coefficient2"]="1.0000";
        $data_where["prpCitemCar.coefficient3"]="0.1000";
        $data_where["prpCitemCar.startSiteName"]="";
        $data_where["prpCitemCar.endSiteName"]="";
        $data_where["prpCitemCar.newCarFlag"]="0";
        $data_where["prpCitemCar.noNlocalFlag"]="0";
        $data_where["prpCitemCar.licenseFlag"]="1";
        $data_where["prpCitemCar.licenseNo"]=$auto['LICENSE_NO'];//iconv("utf-8","gbk",$auto['LICENSE_NO']);
        $data_where["codeLicenseType"]="";
        $data_where["prpCitemCar.licenseType"]="02";
        $data_where["LicenseTypeDes"]="小型汽车号牌";
        $data_where["prpCitemCar.licenseColorCode"]="01";
        $data_where["LicenseColorCodeDes"]="6";
        $data_where["prpCitemCar.engineNo"]=$auto['ENGINE_NO'];
        $data_where["prpCitemCar.vinNo"]=$auto['VIN_NO'];
        $data_where["prpCitemCar.frameNo"]=$auto['VIN_NO'];
        $data_where["prpCitemCar.carKindCode"]="A01";
        $data_where["CarKindCodeDes"]="客车";
        $data_where["carKindCodeBak"]="A01";
        $data_where["prpCitemCar.useNatureCode"]="211";
        $data_where["useNatureCodeBak"]="211";
        $data_where["useNatureCodeTrue"]="211";
        $data_where["prpCitemCar.clauseType"]="F42";
        $data_where["clauseTypeBak"]="F42";
        $data_where["prpCitemCar.enrollDate"]=$auto['ENROLL_DATE'];
        $data_where["enrollDateTrue"]=$auto['ENROLL_DATE'];
        $busin_time= strtotime($business['BUSINESS_START_TIME']);
        $data_where["prpCitemCar.useYears"]=date("Y",$busin_time)-date("Y",strtotime($auto['ENROLL_DATE']));  //实际使用年数
        $data_where["prpCitemCar.runMiles"]="";
        $data_where["taxAbateForPlat"]="";
        $data_where["taxAbateForPlatCarModel"]="";
        $data_where["prpCitemCar.modelDemandNo"]="";
        $data_where["owner"]="";
        $data_where["prpCitemCar.remark"]="";
        $data_where["prpCitemCar.modelCode"]=$auto['MODEL_CODE'];
        $data_where["prpCitemCar.brandName"]=$auto['MODEL'];
        $data_where["modelCodes"]="";
        $data_where["PurchasePriceScal"]="10";
        $data_where["prpCitemCar.purchasePrice"]=$auto['BUYING_PRICE'];
        $data_where["CarActualValueTrue"]=$auto['BUYING_PRICE'];
        $data_where["CarActualValueTrue1"]=$auto['BUYING_PRICE'];
        $data_where["SZpurchasePriceUp"]="";
        $data_where["SZpurchasePriceDown"]="";
        $data_where["purchasePriceF48"]="200000";
        $data_where["purchasePriceUp"]="200";
        $data_where["purchasePriceDown"]="30";
        $data_where["purchasePriceOld"]=$auto['BUYING_PRICE'];
        $data_where["prpCitemCar.actualValue"]=$auto['DISCOUNT_PRICE'];
        $data_where["prpCitemCar.tonCount"]="0";
        $data_where["prpCitemCar.exhaustScale"]=$auto['ENGINE'];
        $data_where["prpCitemCar.seatCount"]=$auto['SEATS'];
        $data_where["seatCountTrue"]="5";
        $data_where["prpCitemCar.runAreaCode"]="11";
        $data_where["prpCitemCar.carInsuredRelation"]="1";
        $data_where["prpCitemCar.countryNature"]="03";
        $data_where["prpCitemCar.cylinderCount"]="";
        $data_where["prpCitemCar.loanVehicleFlag"]="0";
        $data_where["prpCitemCar.transferVehicleFlag"]="0";
        $data_where["prpCitemCar.transferDate"]="";
        $data_where["prpCitemCar.modelCodeAlias"]="";
        $data_where["prpCitemCar.carLotEquQuality"]=$auto['KERB_MASS'];
        $data_where["isQuotation"]="1";
        $data_where["prpCmainCommon.queryArea"]="510000";
        $data_where["queryArea"]="四川省";
        $data_where["vehiclePricer"]=$auto['BUYING_PRICE']; //类比车型价格
        $data_where["prpCitemCar.isDropinVisitInsure"]="0";
        $data_where["prpCitemCar.energyType"]="0";
        $data_where["prpCmainChannel.assetAgentName"]="";
        $data_where["prpCmainChannel.assetAgentCode"]="";
        $data_where["prpCmainChannel.assetAgentPhone"]="";
        $data_where["SYFlag"]="0";
        $data_where["MTFlag"]="0";
        $data_where["BMFlag"]="0";
        $data_where["STFlag"]="0";
        $data_where["prpCcarDevices_[0].deviceName"]="";
        $data_where["prpCcarDevices_[0].id.itemNo"]="1";
        $data_where["prpCcarDevices_[0].id.proposalNo"]="";
        $data_where["prpCcarDevices_[0].id.serialNo"]="";
        $data_where["prpCcarDevices_[0].flag"]="";
        $data_where["prpCcarDevices_[0].quantity"]="";
        $data_where["prpCcarDevices_[0].purchasePrice"]="";
        $data_where["prpCcarDevices_[0].buyDate"]="";
        $data_where["prpCcarDevices_[0].actualValue"]="";
        if(!empty($business['POLICY']['NIELI_DEVICE_LIST']) && $business['POLICY']['NIELI_DEVICE_LIST']!="")
        {
            $devcount= json_decode(urldecode($business['POLICY']['NIELI_DEVICE_LIST']),true);
            $count = count($devcount);
                for($idx = 0; $idx<$count; $idx++)
                {

                    $data["prpCcarDevices[$idx].deviceName"]= $devcount[$idx]['NAME'];
                    $data["prpCcarDevices[$idx].id.itemNo"]=$idx+1;
                    $data["prpCcarDevices[$idx].id.proposalNo"]="";
                    $data["prpCcarDevices[$idx].id.serialNo"]=$idx+1;
                    $data["prpCcarDevices[$idx].flag"]="";
                    $data["prpCcarDevices[$idx].quantity"]= $devcount[$idx]['COUNT'];
                    $data["prpCcarDevices[$idx].purchasePrice"]=$devcount[$idx]['BUYING_PRICE'];
                    $data["prpCcarDevices[$idx].buyDate"]=$devcount[$idx]['BUYING_DATE'];
                    $data["prpCcarDevices[$idx].actualValue"]="";
                }
        }
        $data_where["hidden_index_citemcar"]="2";
        $data_where["editFlag"]="1";
        $data_where["prpCmainCommon.ext2"]="";
        $data_where["configedRepeatTimesLocal"]="1";
        $data_where["prpCinsureds_[0].insuredFlag"]="1";
        $data_where["iinsuredFlag"]="001";
        $data_where["iinsuredType"]="001";
        $data_where["iinsuredCode"]="001";
        $data_where["iinsuredName"]="001";
        $data_where["iunitType"]="001";
        $data_where["iidentifyType"]="001";
        $data_where["iidentifyNumber"]=$auto['IDENTIFY_NO'];
        $data_where["iinsuredAddress"]=!isset($business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'])?"":$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'];
        $data_where["iphoneNumber"]=$auto['MOBILE'];
        $data_where["prpCinsureds_[0].id.serialNo"]="1";
        $data_where["prpCinsureds_[0].insuredType"]="1";
        $data_where["prpCinsureds_[0].insuredNature"]="1";
        $data_where["prpCinsureds_[0].insuredCode"]="001";
        $data_where["prpCinsureds_[0].insuredName"]="1";
        $data_where["prpCinsureds_[0].unitType"]="1";
        $data_where["prpCinsureds_[0].identifyType"]="1";
        $data_where["prpCinsureds_[0].identifyNumber"]=$auto['IDENTIFY_NO'];
        $data_where["prpCinsureds_[0].insuredAddress"]=!isset($business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'])?"":$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'];
        $data_where["prpCinsureds_[0].phoneNumber"]=$auto['MOBILE'];
        $data_where["prpCinsureds_[0].drivingYears"]="";
        $data_where["prpCinsureds_[0].mobile"]=//$auto['MOBILE'];
        $data_where["prpCinsureds_[0].postCode"]="1";
        $data_where["prpCinsureds_[0].versionNo"]="1";
        $data_where["prpCinsureds_[0].auditStatus"]="1";
        $data_where["prpCinsureds_[0].sex"]="1";
        $data_where["prpCinsureds_[0].countryCode"]="1";
        $data_where["prpCinsureds_[0].flag"]="";
        $data_where["prpCinsureds_[0].age"]="";
        $data_where["prpCinsureds_[0].drivingLicenseNo"]="";
        $data_where["prpCinsureds_[0].drivingCarType"]="";
        $data_where["prpCinsureds_[0].appendPrintName"]="";
        $data_where["prpCinsureds_[0].causetroubleTimes"]="";
        $data_where["prpCinsureds_[0].acceptLicenseDate"]="";
        $data_where["isCheckRepeat_[0]"]="";
        $data_where["configedRepeatTimes_[0]"]="";
        $data_where["repeatTimes_[0]"]="";
        $data_where["prpCinsureds_[0].unifiedSocialCreditCode"]="";
        $data_where["prpCinsureds_[0].soldierRelations"]="";
        $data_where["prpCinsureds_[0].soldierIdentifyType"]="";
        $data_where["prpCinsureds_[0].soldierIdentifyNumber"]="";
        $data_where["imobile"]="13408569691";
        $data_where["iauditStatus"]="001";
        $data_where["iversionNo"]="001";
        $data_where["prpCinsureds[0].insuredFlag"]="11100000000000000000000000000A";
        $data_where["display_insuredFlag"]="投保人/被保险人/车主";
        $data_where["prpCinsureds[0].id.serialNo"]="1";
        $data_where["display_InsuredNature"]="个人";
        $data_where["prpCinsureds[0].insuredType"]="1";
        $data_where["prpCinsureds[0].insuredNature"]="3";
        $data_where["prpCinsureds[0].insuredCode"]=$_SESSION['id_cord'];
        $data_where["prpCinsureds[0].insuredName"]=$auto['OWNER'];//iconv("utf-8","gbk",$auto['OWNER']);
        $data_where["unitTypeText"]="";
        $data_where["iinsuredAddress"]=!isset($business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'])?"":$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'];
        $data_where["prpCinsureds[0].unitType"]="";
        $data_where["display_identifyType"]="身份证";
        $data_where["prpCinsureds[0].identifyType"]="01";
        $data_where["prpCinsureds[0].identifyNumber"]=$auto['IDENTIFY_NO'];
        $data_where["prpCinsureds[0].insuredAddress"]=!isset($business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'])?"":$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'];//$business['DESIGNATED_DRIVER'][0]['ADDRESS'];
        $data_where["phoneNumber[0]"]=$auto['MOBILE'];
        $data_where["prpCinsureds[0].phoneNumber"]=$auto['MOBILE'];
        $data_where["prpCinsureds[0].sex"]="2";
        $data_where["prpCinsureds[0].drivingYears"]="";
        $data_where["prpCinsureds[0].postCode"]="";
        $data_where["prpCinsureds[0].versionNo"]="1";
        $data_where["prpCinsureds[0].auditStatus"]="2";
        $data_where["prpCinsureds[0].countryCode"]="CHN";
        $data_where["prpCinsureds[0].flag"]="";
        $data_where["prpCinsureds[0].age"]="39";
        $data_where["prpCinsureds[0].drivingLicenseNo"]=$auto['IDENTIFY_NO'];
        $data_where["prpCinsureds[0].appendPrintName"]="";
        $data_where["prpCinsureds[0].drivingCarType"]="";
        $data_where["reLoadFlag[0]"]="";
        $data_where["prpCinsureds[0].causetroubleTimes"]="";
        $data_where["prpCinsureds[0].acceptLicenseDate"]="";
        $data_where["isCheckRepeat[0]"]="";
        $data_where["configedRepeatTimes[0]"]="";
        $data_where["repeatTimes[0]"]="";
        $data_where["prpCinsureds[0].unifiedSocialCreditCode"]="";
        $data_where["prpCinsureds[0].soldierRelations"]="";
        $data_where["prpCinsureds[0].soldierIdentifyType"]="";
        $data_where["prpCinsureds[0].soldierIdentifyNumber"]="";
        $data_where["mobile[0]"]="139****4654";//13995774654 
        $data_where["prpCinsureds[0].mobile"]=$auto['MOBILE'];
        $data_where["hidden_index_insured"]="1";
        $data_where["_insuredFlag"]="";
        $data_where["_insuredFlag_hide"]="投保人";
        $data_where["_insuredFlag"]="";
        $data_where["_insuredFlag_hide"]="被保险人";
        $data_where["_insuredFlag"]="";
        $data_where["_insuredFlag_hide"]="车主";
        $data_where["_insuredFlag_hide"]="指定驾驶人";
        $data_where["_insuredFlag_hide"]="受益人";
        $data_where["_insuredFlag_hide"]="港澳车车主";
        $data_where["_insuredFlag_hide"]="联系人";
        $data_where["_insuredFlag"]="0";
        $data_where["_insuredFlag_hide"]="委托人";
        $data_where["_resident"]="";
        $data_where["_insuredType"]="1";
        $data_where["_insuredCode"]="";
        $data_where["_insuredName"]="";
        $data_where["customerURL"]="http://10.134.138.16:8300/cif";
        $data_where["_isCheckRepeat"]="";
        $data_where["_configedRepeatTimes"]="";
        $data_where["_repeatTimes"]="";
        $data_where["_identifyType"]="01";
        $data_where["_identifyNumber"]="";
        $data_where["_unifiedSocialCreditCode"]="";
        $data_where["_mobile"]="";
        $data_where["_mobile1"]="";
        $data_where["_sex"]="2";
        $data_where["_age"]="";
        $data_where["_drivingYears"]="";
        $data_where["_countryCode"]="CHN";
        $data_where["_insuredAddress"]="";
        $data_where["_postCode"]="";
        $data_where["_appendPrintName"]="";
        $data_where["group_code"]="";
        $data_where["_auditStatus"]="";
        $data_where["_auditStatusDes"]="";
        $data_where["_versionNo"]="";
        $data_where["_drivingLicenseNo"]="";
        $data_where["_soldierRelations"]="0";
        $data_where["_soldierIdentifyType"]="000";
        $data_where["_soldierIdentifyNumber"]="";
        $data_where["_drivingCarType"]="";
        $data_where["CarKindLicense"]="";
        $data_where["_causetroubleTimes"]="";
        $data_where["_acceptLicenseDate"]="";
        $data_where["prpCmainCar.agreeDriverFlag"]="0";
        $data_where["updateIndex"]="-1";
        $data_where["prpBatchProposal.profitType"]="";
        $data_where["motorFastTrack_Amount"]="";
        $data_where["insurancefee_reform"]="1";
        $data_where["prpCmainCommon.clauseIssue"]="2";
        $data_where["prpCprofitDetailsTemp_[0].chooseFlag"]="on";
        $data_where["prpCprofitDetailsTemp_[0].profitName"]="";
        $data_where["prpCprofitDetailsTemp_[0].condition"]="";
        $data_where["profitRateTemp_[0]"]="";
        $data_where["prpCprofitDetailsTemp_[0].profitRate"]="";
        $data_where["prpCprofitDetailsTemp_[0].profitRateMin"]="";
        $data_where["prpCprofitDetailsTemp_[0].profitRateMax"]="";
        $data_where["prpCprofitDetailsTemp_[0].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp_[0].id.itemKindNo"]="";
        $data_where["prpCprofitDetailsTemp_[0].id.profitCode"]="";
        $data_where["prpCprofitDetailsTemp_[0].id.serialNo"]="1";
        $data_where["prpCprofitDetailsTemp_[0].id.profitType"]="";
        $data_where["prpCprofitDetailsTemp_[0].kindCode"]="";
        $data_where["prpCprofitDetailsTemp_[0].conditionCode"]="";
        $data_where["prpCprofitDetailsTemp_[0].flag"]="";
        $data_where["prpCprofitFactorsTemp_[0].chooseFlag"]="on";
        $data_where["serialNo_[0]"]="";
        $data_where["prpCprofitFactorsTemp_[0].profitName"]="";
        $data_where["prpCprofitFactorsTemp_[0].condition"]="";
        $data_where["rateTemp_[0]"]="";
        $data_where["prpCprofitFactorsTemp_[0].rate"]="";
        $data_where["prpCprofitFactorsTemp_[0].lowerRate"]="";
        $data_where["prpCprofitFactorsTemp_[0].upperRate"]="";
        $data_where["prpCprofitFactorsTemp_[0].id.profitCode"]="";
        $data_where["prpCprofitFactorsTemp_[0].id.conditionCode"]="";
        $data_where["prpCprofitFactorsTemp_[0].flag"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].id.profitCode"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].id.conditionCode"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].id.profitCodeForFather"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].id.conditionCodeForFather"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].profitName"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].condition"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].upperRate"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].lowerRate"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].rate"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].chooseFlag"]="";
        $data_where["prpCprofitFactorFixesTemp_[0].flag"]="";
        $data_where["prpCitemKind.shortRateFlag"]="2";
        $data_where["prpCitemKind.shortRate"]="100.0000";
        $data_where["prpCitemKind.currency"]="CNY";
        $data_where["prpCmainCommon.groupFlag"]="0";
        $data_where["prpCmain.preDiscount"]="";


        $data_where["sumBenchPremium"]=round($_SESSION['COUNT_PREMIUM']/$_SESSION['DISCOUNT'],2);
        $data_where["prpCmain.discount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCmain.sumPremium"]=$_SESSION['COUNT_PREMIUM'];
        $data_where["premiumF48"]="5000";
        $data_where["prpCmain.sumNetPremium"]=$_SESSION['NET_PREMIUM'];
        $data_where["prpCmain.sumTaxPremium"]=$_SESSION['TAX'];
        $data_where["passengersSwitchFlag"]="";
        


        foreach($business['POLICY']['BUSINESS_ITEMS'] as $u=>$value){
                            switch ($u) {

                                case 'TVDI':
                                    $data_where["prpCitemKindsTemp[0].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[0].pureRiskPremium"]=$_SESSION['INSURANCE']['TVDI']['PURERISK_PREMIUM'];/**增加纯风险保费**/
                                    $data_where["prpCitemKindsTemp[0].premium"]=$_SESSION['INSURANCE']['TVDI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[0].benchMarkPremium"]=$_SESSION['INSURANCE']['TVDI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[0].netPremium"]=$_SESSION['INSURANCE']['TVDI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[0].taxPremium"]=$_SESSION['INSURANCE']['TVDI']['TAX'];
                                    break;
                                case 'TTBLI':
                                    $data_where["prpCitemKindsTemp[2].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[2].premium"]=$_SESSION['INSURANCE']['TTBLI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[2].benchMarkPremium"]=$_SESSION['INSURANCE']['TTBLI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[2].netPremium"]=$_SESSION['INSURANCE']['TTBLI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[2].taxPremium"]=$_SESSION['INSURANCE']['TTBLI']['TAX'];
                                    break;
                                case 'TWCDMVI':
                                    $data_where["prpCitemKindsTemp[1].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[1].premium"]=$_SESSION['INSURANCE']['TWCDMVI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[1].benchMarkPremium"]=$_SESSION['INSURANCE']['TWCDMVI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[1].netPremium"]=$_SESSION['INSURANCE']['TWCDMVI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[1].taxPremium"]=$_SESSION['INSURANCE']['TWCDMVI']['TAX'];
                                    break;
                                case 'TCPLI_DRIVER':
                                    $data_where["prpCitemKindsTemp[3].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[3].premium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[3].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER']['STANDARD_PREMIUM'];
                                     $data_where["prpCitemKindsTemp[3].netPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[3].taxPremium"] =$_SESSION['INSURANCE']['TCPLI_DRIVER']['TAX'];
                                    break;
                                case 'TCPLI_PASSENGER':
                                    $data_where["prpCitemKindsTemp[4].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[4].premium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[4].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[4].netPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[4].taxPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['TAX'];
                                    break;
                                case 'BSDI':
                                    $data_where["prpCitemKindsTemp[5].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[5].premium"]=$_SESSION['INSURANCE']['BSDI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[5].benchMarkPremium"]=$_SESSION['INSURANCE']['BSDI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[5].netPremium"]=$_SESSION['INSURANCE']['BSDI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[5].taxPremium"]=$_SESSION['INSURANCE']['BSDI']['TAX'];

                                    break;
                                case 'BGAI':
                                    $data_where["prpCitemKindsTemp[6].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[6].premium"]=$_SESSION['INSURANCE']['BGAI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[6].benchMarkPremium"]=$_SESSION['INSURANCE']['BGAI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[6].netPremium"]=$_SESSION['INSURANCE']['BGAI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[6].taxPremium"]=$_SESSION['INSURANCE']['BGAI']['TAX'];
                                    break;
                                case 'STSFS':
                                    $data_where["prpCitemKindsTemp[7].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[7].premium"]=$_SESSION['INSURANCE']['STSFS']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[7].benchMarkPremium"]=$_SESSION['INSURANCE']['STSFS']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[7].netPremium"]=$_SESSION['INSURANCE']['STSFS']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[7].taxPremium"]=$_SESSION['INSURANCE']['STSFS']['TAX'];
                                    break;
                                case 'VWTLI':
                                    $data_where["prpCitemKindsTemp[23].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[23].premium"]=$_SESSION['INSURANCE']['VWTLI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[23].benchMarkPremium"]=$_SESSION['INSURANCE']['VWTLI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[23].netPremium"]=$_SESSION['INSURANCE']['VWTLI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[23].taxPremium"]=$_SESSION['INSURANCE']['VWTLI']['TAX'];
                                    break;
                                case 'NIELI':
                                    $data_where["prpCitemKindsTemp[8].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[8].premium"]=$_SESSION['INSURANCE']['NIELI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[8].benchMarkPremium"]=$_SESSION['INSURANCE']['NIELI']['STANDARD_PREMIUM'];
                                    $data_where["prpCengageTemps[0].id.serialNo"]="1";
                                    $data_where["prpCengageTemps[0].clauseCode"]="000034";
                                    $data_where["prpCengageTemps[0].clauseName"]="新增设备特别约定";
                                    $data_where["clauses[0]"]="投保新增设备，详见《新增设备明细表》。";
                                    $data_where["prpCengageTemps[0].maxCount"]="";
                                    $data_where["prpCengageTemps[0].clauses"]="投保新增设备，详见《新增设备明细表》。";
                                    $data_where["prpCengageTemps[0].flag"]="";
                                    $data_where["prpCengageTemps[0].engageFlag"]="0";
                                    $data_where["prpCitemKindsTemp[8].netPremium"]=$_SESSION['INSURANCE']['NIELI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[8].taxPremium"]=$_SESSION['INSURANCE']['NIELI']['TAX'];
                                    break;
                                case 'SLOI':
                                    $data_where["prpCitemKindsTemp[9].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[9].premium"]=$_SESSION['INSURANCE']['SLOI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[9].benchMarkPremium"]=$_SESSION['INSURANCE']['SLOI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[9].netPremium"]=$_SESSION['INSURANCE']['SLOI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[9].taxPremium"]=$_SESSION['INSURANCE']['SLOI']['TAX'];
                                    break;
                                case 'RDCCI':
                                    $data_where["prpCitemKindsTemp[10].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[10].premium"]=$_SESSION['INSURANCE']['RDCCI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[10].benchMarkPremium"]=$_SESSION['INSURANCE']['RDCCI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[10].netPremium"]=$_SESSION['INSURANCE']['RDCCI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[10].taxPremium"]=$_SESSION['INSURANCE']['RDCCI']['TAX'];
                                    break;
                                case 'MVLINFTPSI':
                                    $data_where["prpCitemKindsTemp[11].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[11].premium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[11].benchMarkPremium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[11].netPremium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[11].taxPremium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['TAX'];
                                    break;
                                case 'LIDI':
                                    $data_where["prpCitemKindsTemp[20].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[20].premium"]=$value;

                                    break;
                                case 'TVDI_NDSI':
                                    $data_where["prpCitemKindsTemp[13].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[0].specialFlag"]="on";
                                    $data_where["prpCitemKindsTemp[13].premium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[13].benchMarkPremium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[13].netPremium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[13].taxPremium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['TAX'];
                                    break;
                                case 'TTBLI_NDSI':
                                    $data_where["prpCitemKindsTemp[2].specialFlag"]="on";
                                    $data_where["prpCitemKindsTemp[12].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[12].premium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[12].benchMarkPremium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[12].netPremium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[12].taxPremium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['TAX'];
                                    break;
                                case 'TWCDMVI_NDSI':
                                    $data_where["prpCitemKindsTemp[1].specialFlag"]="on";
                                    $data_where["prpCitemKindsTemp[15].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[15].premium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[15].benchMarkPremium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[15].netPremium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[15].taxPremium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['TAX'];
                                    break;
                                case 'TCPLI_DRIVER_NDSI':
                                    $data_where["prpCitemKindsTemp[3].specialFlag"]="on";
                                    $data_where["prpCitemKindsTemp[16].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[16].premium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[16].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[16].netPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[16].taxPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['TAX'];
                                    break;
                                case 'TCPLI_PASSENGER_NDSI':
                                    $data_where["prpCitemKindsTemp[4].specialFlag"]="on";
                                    $data_where["prpCitemKindsTemp[17].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[17].premium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[17].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[17].netPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[17].taxPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['TAX'];
                                    break;
                                case 'BSDI_NDSI':
                                    $data_where["prpCitemKindsTemp[5].specialFlag"]="on";
                                    $data_where["prpCitemKindsTemp[20].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[20].premium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[20].benchMarkPremium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[20].netPremium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[20].taxPremium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['TAX'];
                                    break;
                                case 'SLOI_NDSI':
                                    $data_where["prpCitemKindsTemp[9].specialFlag"]="on";
                                    $data_where["prpCitemKindsTemp[18].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[18].premium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[18].benchMarkPremium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[18].netPremium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[18].taxPremium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['TAX'];
                                    break;
                                case 'VWTLI_NDSI':
                                    $data_where["prpCitemKindsTemp[23].specialFlag"]="on";
                                    $data_where["prpCitemKindsTemp[22].chooseFlag"]="on";
                                    $data_where["prpCitemKindsTemp[22].premium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['PREMIUM'];
                                    $data_where["prpCitemKindsTemp[22].benchMarkPremium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['STANDARD_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[22].netPremium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['NET_PREMIUM'];
                                    $data_where["prpCitemKindsTemp[22].taxPremium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['TAX'];
                                    break;
                                case 'LIDI_NDSI':
                                    //$data_where["prpCitemKindsTemp[20].specialFlag"]="on";
                                    //$data_where["prpCitemKindsTemp[21].chooseFlag"]="on";
                                case 'NIELI_NDSI':
                                $data_where["prpCitemKindsTemp[8].specialFlag"]="on";
                                $data_where["prpCitemKindsTemp[19].chooseFlag"]="on";
                                $data_where["prpCitemKindsTemp[19].premium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['PREMIUM'];
                                $data_where["prpCitemKindsTemp[19].benchMarkPremium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['STANDARD_PREMIUM'];
                                $data_where["prpCitemKindsTemp[19].netPremium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['NET_PREMIUM'];
                                $data_where["prpCitemKindsTemp[19].taxPremium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['TAX'];
                                break;
                            }
                }







        $data_where["prpCitemKindsTemp[0].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[0].dutyFlag"]="2";
        $data_where["prpCitemKindsTemp[0].min"]="";
        $data_where["prpCitemKindsTemp[0].max"]="";
        $data_where["prpCitemKindsTemp[0].itemKindNo"]="1";
        $data_where["prpCitemKindsTemp[0].clauseCode"]="050051";
        $data_where["prpCitemKindsTemp[0].kindCode"]="050202";
        $data_where["prpCitemKindsTemp[0].kindName"]="机动车损失保险";
        $data_where["prpCitemKindsTemp[0].deductible"]="0.00";
        $data_where["prpCitemKindsTemp[0].deductibleRate"]="0.0000";
        $data_where["prpCitemKindsTemp[0].amount"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
        $data_where["prpCitemKindsTemp[0].calculateFlag"]="Y";
        $data_where["prpCitemKindsTemp[0].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[0].startHour"]="0";
        $data_where["prpCitemKindsTemp[0].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[0].endHour"]="24";
        $data_where["relateSpecial[0]"]="050930";
        $data_where["prpCitemKindsTemp[0].flag"]="1001000";
        $data_where["prpCitemKindsTemp[0].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[0].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[0].rate"]="";
        $data_where["prpCitemKindsTemp[0].disCount"]=$_SESSION['DISCOUNT'];


        $data_where["prpCprofitDetailsTemp[0].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[0].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[0].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[0]"]="85.00000000";
        $data_where["prpCprofitDetailsTemp[0].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[0].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[0].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[0].kindCode"]="050202";
        $data_where["prpCprofitDetailsTemp[0].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[0].flag"]="";
        $data_where["prpCprofitDetailsTemp[0].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[0].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[0].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[0].id.itemKindNo"]="1";
        $data_where["prpCprofitDetailsTemp[0].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[1].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[1].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[1].condition"]="新保或上年赔款次数在2次以下";
        $data_where["profitRateTemp[1]"]="1.0000000";
        $data_where["prpCprofitDetailsTemp[1].profitRate"]="100.000000";
        $data_where["prpCprofitDetailsTemp[1].profitRateMin"]="100.000000";
        $data_where["prpCprofitDetailsTemp[1].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[1].kindCode"]="050202";
        $data_where["prpCprofitDetailsTemp[1].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[1].flag"]="";
        $data_where["prpCprofitDetailsTemp[1].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[1].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[1].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[1].id.itemKindNo"]="1";
        $data_where["prpCprofitDetailsTemp[1].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[2].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[2].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[2].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[2]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[2].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[2].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[2].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[2].kindCode"]="050202";
        $data_where["prpCprofitDetailsTemp[2].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[2].flag"]="";
        $data_where["prpCprofitDetailsTemp[2].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[2].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[2].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[2].id.itemKindNo"]="1";
        $data_where["prpCprofitDetailsTemp[2].id.serialNo"]="0";
        $data_where["prpCitemKindsTemp[1].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[1].dutyFlag"]="2";
        $data_where["prpCitemKindsTemp[1].min"]="";
        $data_where["prpCitemKindsTemp[1].max"]="";
        $data_where["prpCitemKindsTemp[1].itemKindNo"]="2";
        $data_where["prpCitemKindsTemp[1].clauseCode"]="050054";
        $data_where["prpCitemKindsTemp[1].kindCode"]="050501";
        $data_where["prpCitemKindsTemp[1].kindName"]="盗抢险";
        $data_where["prpCitemKindsTemp[1].unitAmount"]="0.00";
        $data_where["prpCitemKindsTemp[1].quantity"]="";
        $data_where["prpCitemKindsTemp[1].amount"]=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
        $data_where["prpCitemKindsTemp[1].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[1].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[1].startHour"]="0";
        $data_where["prpCitemKindsTemp[1].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[1].endHour"]="24";
        $data_where["relateSpecial[1]"]="050932";
        $data_where["prpCitemKindsTemp[1].flag"]="1001000";
        $data_where["prpCitemKindsTemp[1].basePremium"]="78.00";
        $data_where["prpCitemKindsTemp[1].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[1].rate"]="0.3185";
        $data_where["prpCitemKindsTemp[1].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCprofitDetailsTemp[3].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[3].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[3].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[3]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[3].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[3].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[3].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[3].kindCode"]="050501";
        $data_where["prpCprofitDetailsTemp[3].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[3].flag"]="";
        $data_where["prpCprofitDetailsTemp[3].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[3].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[3].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[3].id.itemKindNo"]="2";
        $data_where["prpCprofitDetailsTemp[3].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[4].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[4].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[4].condition"]="新保或上年赔款次数在2次以下";
        $data_where["profitRateTemp[4]"]="1.0000000";
        $data_where["prpCprofitDetailsTemp[4].profitRate"]="100.000000";
        $data_where["prpCprofitDetailsTemp[4].profitRateMin"]="100.000000";
        $data_where["prpCprofitDetailsTemp[4].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[4].kindCode"]="050501";
        $data_where["prpCprofitDetailsTemp[4].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[4].flag"]="";
        $data_where["prpCprofitDetailsTemp[4].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[4].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[4].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[4].id.itemKindNo"]="2";
        $data_where["prpCprofitDetailsTemp[4].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[5].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[5].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[5].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[5]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[5].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[5].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[5].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[5].kindCode"]="050501";
        $data_where["prpCprofitDetailsTemp[5].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[5].flag"]="";
        $data_where["prpCprofitDetailsTemp[5].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[5].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[5].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[5].id.itemKindNo"]="2";
        $data_where["prpCprofitDetailsTemp[5].id.serialNo"]="0";
        $data_where["prpCitemKindsTemp[2].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[2].dutyFlag"]="2";
        $data_where["prpCitemKindsTemp[2].min"]="";
        $data_where["prpCitemKindsTemp[2].max"]="";
        $data_where["prpCitemKindsTemp[2].itemKindNo"]="3";
        $data_where["prpCitemKindsTemp[2].clauseCode"]="050052";
        $data_where["prpCitemKindsTemp[2].kindCode"]="050602";
        $data_where["prpCitemKindsTemp[2].kindName"]="第三者责任保险";
        $data_where["prpCitemKindsTemp[2].unitAmount"]="0.00";
        $data_where["prpCitemKindsTemp[2].quantity"]="";
                            if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="5")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="50000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="10")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="100000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="15")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="150000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="20")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="200000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="30")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="300000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="50")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="500000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="100")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="1000000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="150")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="1500000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="200")
                            {
                                $data_where["prpCitemKindsTemp[2].amount"]="2000000";
                            }
        $data_where["prpCitemKindsTemp[2].calculateFlag"]="Y";
        $data_where["prpCitemKindsTemp[2].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[2].startHour"]="0";
        $data_where["prpCitemKindsTemp[2].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[2].endHour"]="24";
        $data_where["relateSpecial[2]"]="050931";
        $data_where["prpCitemKindsTemp[2].flag"]="1001000";
        $data_where["prpCitemKindsTemp[2].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[2].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[2].rate"]="";
        $data_where["prpCitemKindsTemp[2].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCprofitDetailsTemp[6].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[6].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[6].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[6]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[6].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[6].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[6].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[6].kindCode"]="050602";
        $data_where["prpCprofitDetailsTemp[6].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[6].flag"]="";
        $data_where["prpCprofitDetailsTemp[6].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[6].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[6].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[6].id.itemKindNo"]="3";
        $data_where["prpCprofitDetailsTemp[6].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[7].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[7].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[7].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[7]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[7].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[7].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[7].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[7].kindCode"]="050602";
        $data_where["prpCprofitDetailsTemp[7].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[7].flag"]="";
        $data_where["prpCprofitDetailsTemp[7].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[7].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[7].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[7].id.itemKindNo"]="3";
        $data_where["prpCprofitDetailsTemp[7].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[8].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[8].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[8].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[8]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[8].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[8].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[8].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[8].kindCode"]="050602";
        $data_where["prpCprofitDetailsTemp[8].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[8].flag"]="";
        $data_where["prpCprofitDetailsTemp[8].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[8].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[8].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[8].id.itemKindNo"]="3";
        $data_where["prpCprofitDetailsTemp[8].id.serialNo"]="0";
        $data_where["prpCitemKindsTemp[3].min"]="";
        $data_where["prpCitemKindsTemp[3].max"]="";
        $data_where["prpCitemKindsTemp[3].itemKindNo"]="4";
        $data_where["prpCitemKindsTemp[3].clauseCode"]="050053";
        $data_where["prpCitemKindsTemp[3].kindCode"]="050711";
        $data_where["prpCitemKindsTemp[3].kindName"]="车上人员责任险（司机）";
        $data_where["prpCitemKindsTemp[3].unitAmount"]="0.00";
        $data_where["prpCitemKindsTemp[3].quantity"]="";
        $data_where["prpCitemKindsTemp[3].amount"]=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
        $data_where["prpCitemKindsTemp[3].calculateFlag"]="Y";
        $data_where["prpCitemKindsTemp[3].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[3].startHour"]="0";
        $data_where["prpCitemKindsTemp[3].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[3].endHour"]="24";
        $data_where["relateSpecial[3]"]="050933";
        $data_where["prpCitemKindsTemp[3].flag"]="1001000";
        $data_where["prpCitemKindsTemp[3].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[3].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[3].rate"]="";
        $data_where["prpCitemKindsTemp[3].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[3].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[3].dutyFlag"]="2";
        $data_where["prpCprofitDetailsTemp[9].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[9].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[9].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[9]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[9].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[9].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[9].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[9].kindCode"]="050711";
        $data_where["prpCprofitDetailsTemp[9].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[9].flag"]="";
        $data_where["prpCprofitDetailsTemp[9].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[9].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[9].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[9].id.itemKindNo"]="4";
        $data_where["prpCprofitDetailsTemp[9].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[10].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[10].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[10].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[10]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[10].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[10].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[10].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[10].kindCode"]="050711";
        $data_where["prpCprofitDetailsTemp[10].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[10].flag"]="";
        $data_where["prpCprofitDetailsTemp[10].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[10].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[10].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[10].id.itemKindNo"]="4";
        $data_where["prpCprofitDetailsTemp[10].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[11].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[11].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[11].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[11]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[11].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[11].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[11].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[11].kindCode"]="050711";
        $data_where["prpCprofitDetailsTemp[11].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[11].flag"]="";
        $data_where["prpCprofitDetailsTemp[11].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[11].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[11].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[11].id.itemKindNo"]="4";
        $data_where["prpCprofitDetailsTemp[11].id.serialNo"]="0";
        $data_where["prpCitemKindsTemp[4].min"]="";
        $data_where["prpCitemKindsTemp[4].max"]="";
        $data_where["prpCitemKindsTemp[4].itemKindNo"]="5";
        $data_where["prpCitemKindsTemp[4].clauseCode"]="050053";
        $data_where["prpCitemKindsTemp[4].kindCode"]="050712";
        $data_where["prpCitemKindsTemp[4].kindName"]="车上人员责任险（乘客）";
        $data_where["prpCitemKindsTemp[4].unitAmount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
        $data_where["prpCitemKindsTemp[4].quantity"]=$business['POLICY']['TCPLI_PASSENGER_COUNT'];
        $data_where["prpCitemKindsTemp[4].amount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']*$business['POLICY']['TCPLI_PASSENGER_COUNT'];
        $data_where["prpCitemKindsTemp[4].calculateFlag"]="Y";
        $data_where["prpCitemKindsTemp[4].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[4].startHour"]="0";
        $data_where["prpCitemKindsTemp[4].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[4].endHour"]="24";
        $data_where["relateSpecial[4]"]="050934";
        $data_where["prpCitemKindsTemp[4].flag"]="1001000";
        $data_where["prpCitemKindsTemp[4].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[4].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[4].rate"]="0.1690";
        $data_where["prpCitemKindsTemp[4].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[4].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[4].dutyFlag"]="2";
        $data_where["prpCprofitDetailsTemp[12].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[12].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[12].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[12]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[12].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[12].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[12].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[12].kindCode"]="050712";
        $data_where["prpCprofitDetailsTemp[12].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[12].flag"]="";
        $data_where["prpCprofitDetailsTemp[12].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[12].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[12].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[12].id.itemKindNo"]="5";
        $data_where["prpCprofitDetailsTemp[12].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[13].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[13].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[13].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[13]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[13].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[13].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[13].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[13].kindCode"]="050712";
        $data_where["prpCprofitDetailsTemp[13].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[13].flag"]="";
        $data_where["prpCprofitDetailsTemp[13].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[13].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[13].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[13].id.itemKindNo"]="5";
        $data_where["prpCprofitDetailsTemp[13].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[14].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[14].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[14].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[14]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[14].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[14].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[14].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[14].kindCode"]="050712";
        $data_where["prpCprofitDetailsTemp[14].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[14].flag"]="";
        $data_where["prpCprofitDetailsTemp[14].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[14].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[14].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[14].id.itemKindNo"]="5";
        $data_where["prpCprofitDetailsTemp[14].id.serialNo"]="0";



        $data_where["prpCitemKindsTemp[5].min"]="";
        $data_where["prpCitemKindsTemp[5].max"]="";
        $data_where["prpCitemKindsTemp[5].itemKindNo"]="6";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[5].clauseCode"]="050059";
        $data_where["prpCitemKindsTemp[5].kindCode"]="050211";
        $data_where["relateSpecial[5]"]="050937";
        $data_where["prpCitemKindsTemp[5].kindName"]="车身划痕损失险";
        $data_where["prpCitemKindsTemp[5].amount"]=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
        $data_where["prpCitemKindsTemp[5].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[5].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[5].startHour"]="0";
        $data_where["prpCitemKindsTemp[5].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[5].endHour"]="24";
        $data_where["prpCitemKindsTemp[5].flag"]="2001000";
        $data_where["prpCitemKindsTemp[5].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[5].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[5].rate"]="";
        $data_where["prpCitemKindsTemp[5].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[5].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[5].dutyFlag"]="2";



        $data_where["prpCprofitDetailsTemp[15].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[15].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[15].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[15]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[15].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[15].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[15].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[15].kindCode"]="050211";
        $data_where["prpCprofitDetailsTemp[15].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[15].flag"]="";
        $data_where["prpCprofitDetailsTemp[15].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[15].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[15].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[15].id.itemKindNo"]="6";
        $data_where["prpCprofitDetailsTemp[15].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[16].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[16].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[16].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[16]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[16].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[16].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[16].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[16].kindCode"]="050211";
        $data_where["prpCprofitDetailsTemp[16].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[16].flag"]="";
        $data_where["prpCprofitDetailsTemp[16].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[16].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[16].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[16].id.itemKindNo"]="6";
        $data_where["prpCprofitDetailsTemp[16].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[17].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[17].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[17].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[17]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[17].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[17].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[17].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[17].kindCode"]="050211";
        $data_where["prpCprofitDetailsTemp[17].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[17].flag"]="";
        $data_where["prpCprofitDetailsTemp[17].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[17].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[17].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[17].id.itemKindNo"]="6";
        $data_where["prpCprofitDetailsTemp[17].id.serialNo"]="0";



        $data_where["prpCitemKindsTemp[6].min"]="";
        $data_where["prpCitemKindsTemp[6].max"]="";
        $data_where["prpCitemKindsTemp[6].itemKindNo"]="7";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[6].clauseCode"]="050056";
        $data_where["prpCitemKindsTemp[6].kindCode"]="050232";
        $data_where["relateSpecial[6]"]="";
        $data_where["prpCitemKindsTemp[6].kindName"]="玻璃单独破碎险";
        $data_where["prpCitemKindsTemp[6].modeCode"]="10";
        $data_where["prpCitemKindsTemp[6].amount"]="0.00";
        $data_where["prpCitemKindsTemp[6].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[6].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[6].startHour"]="0";
        $data_where["prpCitemKindsTemp[6].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[6].endHour"]="24";
        $data_where["prpCitemKindsTemp[6].flag"]="2000000";
        $data_where["prpCitemKindsTemp[6].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[6].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[6].rate"]="0.1235";
        $data_where["prpCitemKindsTemp[6].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[6].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[6].dutyFlag"]="2";




        $data_where["prpCprofitDetailsTemp[18].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[18].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[18].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[18]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[18].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[18].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[18].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[18].kindCode"]="050232";
        $data_where["prpCprofitDetailsTemp[18].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[18].flag"]="";
        $data_where["prpCprofitDetailsTemp[18].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[18].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[18].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[18].id.itemKindNo"]="7";
        $data_where["prpCprofitDetailsTemp[18].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[19].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[19].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[19].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[19]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[19].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[19].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[19].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[19].kindCode"]="050232";
        $data_where["prpCprofitDetailsTemp[19].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[19].flag"]="";
        $data_where["prpCprofitDetailsTemp[19].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[19].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[19].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[19].id.itemKindNo"]="7";
        $data_where["prpCprofitDetailsTemp[19].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[20].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[20].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[20].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[20]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[20].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[20].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[20].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[20].kindCode"]="050232";
        $data_where["prpCprofitDetailsTemp[20].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[20].flag"]="";
        $data_where["prpCprofitDetailsTemp[20].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[20].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[20].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[20].id.itemKindNo"]="7";
        $data_where["prpCprofitDetailsTemp[20].id.serialNo"]="0";




        $data_where["prpCitemKindsTemp[7].min"]="";
        $data_where["prpCitemKindsTemp[7].max"]="";
        $data_where["prpCitemKindsTemp[7].itemKindNo"]="8";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[7].clauseCode"]="050065";
        $data_where["prpCitemKindsTemp[7].kindCode"]="050253";
        $data_where["relateSpecial[7]"]="";
        $data_where["prpCitemKindsTemp[7].kindName"]="指定修理厂险";
        $data_where["prpCitemKindsTemp[7].amount"]="0.00";
        $data_where["prpCitemKindsTemp[7].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[7].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[7].startHour"]="0";
        $data_where["prpCitemKindsTemp[7].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[7].endHour"]="24";
        $data_where["prpCitemKindsTemp[7].flag"]="2000000";
        $data_where["prpCitemKindsTemp[7].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[7].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[7].rate"]="10.0000";
        $data_where["prpCitemKindsTemp[7].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[7].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[7].dutyFlag"]="2";




        $data_where["prpCprofitDetailsTemp[21].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[21].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[21].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[21]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[21].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[21].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[21].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[21].kindCode"]="050253";
        $data_where["prpCprofitDetailsTemp[21].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[21].flag"]="";
        $data_where["prpCprofitDetailsTemp[21].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[21].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[21].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[21].id.itemKindNo"]="8";
        $data_where["prpCprofitDetailsTemp[21].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[22].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[22].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[22].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[22]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[22].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[22].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[22].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[22].kindCode"]="050253";
        $data_where["prpCprofitDetailsTemp[22].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[22].flag"]="";
        $data_where["prpCprofitDetailsTemp[22].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[22].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[22].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[22].id.itemKindNo"]="8";
        $data_where["prpCprofitDetailsTemp[22].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[23].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[23].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[23].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[23]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[23].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[23].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[23].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[23].kindCode"]="050253";
        $data_where["prpCprofitDetailsTemp[23].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[23].flag"]="";
        $data_where["prpCprofitDetailsTemp[23].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[23].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[23].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[23].id.itemKindNo"]="8";
        $data_where["prpCprofitDetailsTemp[23].id.serialNo"]="0";
                      



        $data_where["prpCitemKindsTemp[8].min"]="";
        $data_where["prpCitemKindsTemp[8].max"]="";
        $data_where["prpCitemKindsTemp[8].itemKindNo"]="9";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[8].clauseCode"]="050058";
        $data_where["prpCitemKindsTemp[8].kindCode"]="050261";
        $data_where["relateSpecial[8]"]="050936";
        $data_where["prpCitemKindsTemp[8].kindName"]="新增设备损失险";
        $data_where["prpCitemKindsTemp[8].amount"]=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
        $data_where["prpCitemKindsTemp[8].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[8].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[8].startHour"]="0";
        $data_where["prpCitemKindsTemp[8].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[8].endHour"]="24";
        $data_where["prpCitemKindsTemp[8].flag"]="2001000";
        $data_where["prpCitemKindsTemp[8].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[8].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[8].rate"]="1.2968";
        $data_where["prpCitemKindsTemp[8].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[8].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[8].dutyFlag"]="2";


        $data_where["prpCprofitDetailsTemp[24].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[24].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[24].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[24]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[24].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[24].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[24].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[24].kindCode"]="050261";
        $data_where["prpCprofitDetailsTemp[24].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[24].flag"]="";
        $data_where["prpCprofitDetailsTemp[24].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[24].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[24].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[24].id.itemKindNo"]="9";
        $data_where["prpCprofitDetailsTemp[24].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[25].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[25].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[25].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[25]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[25].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[25].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[25].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[25].kindCode"]="050261";
        $data_where["prpCprofitDetailsTemp[25].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[25].flag"]="";
        $data_where["prpCprofitDetailsTemp[25].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[25].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[25].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[25].id.itemKindNo"]="9";
        $data_where["prpCprofitDetailsTemp[25].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[26].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[26].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[26].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[26]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[26].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[26].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[26].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[26].kindCode"]="050261";
        $data_where["prpCprofitDetailsTemp[26].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[26].flag"]="";
        $data_where["prpCprofitDetailsTemp[26].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[26].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[26].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[26].id.itemKindNo"]="9";
        $data_where["prpCprofitDetailsTemp[26].id.serialNo"]="0";



        $data_where["prpCitemKindsTemp[9].min"]="";
        $data_where["prpCitemKindsTemp[9].max"]="";
        $data_where["prpCitemKindsTemp[9].itemKindNo"]="10";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[9].clauseCode"]="050057";
        $data_where["prpCitemKindsTemp[9].kindCode"]="050311";
        $data_where["relateSpecial[9]"]="050935";
        $data_where["prpCitemKindsTemp[9].kindName"]="自燃损失险";
        $data_where["prpCitemKindsTemp[9].amount"]=$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
        $data_where["prpCitemKindsTemp[9].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[9].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[9].startHour"]="0";
        $data_where["prpCitemKindsTemp[9].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[9].endHour"]="24";
        $data_where["prpCitemKindsTemp[9].flag"]="2001000";
        $data_where["prpCitemKindsTemp[9].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[9].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[9].rate"]="0.0780";
        $data_where["prpCitemKindsTemp[9].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[9].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[9].dutyFlag"]="2";


        $data_where["prpCprofitDetailsTemp[27].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[27].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[27].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[27]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[27].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[27].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[27].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[27].kindCode"]="050311";
        $data_where["prpCprofitDetailsTemp[27].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[27].flag"]="";
        $data_where["prpCprofitDetailsTemp[27].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[27].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[27].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[27].id.itemKindNo"]="10";
        $data_where["prpCprofitDetailsTemp[27].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[28].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[28].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[28].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[28]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[28].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[28].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[28].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[28].kindCode"]="050311";
        $data_where["prpCprofitDetailsTemp[28].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[28].flag"]="";
        $data_where["prpCprofitDetailsTemp[28].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[28].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[28].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[28].id.itemKindNo"]="10";
        $data_where["prpCprofitDetailsTemp[28].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[29].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[29].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[29].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[29]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[29].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[29].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[29].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[29].kindCode"]="050311";
        $data_where["prpCprofitDetailsTemp[29].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[29].flag"]="";
        $data_where["prpCprofitDetailsTemp[29].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[29].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[29].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[29].id.itemKindNo"]="10";
        $data_where["prpCprofitDetailsTemp[29].id.serialNo"]="0";



        $data_where["prpCitemKindsTemp[10].min"]="";
        $data_where["prpCitemKindsTemp[10].max"]="";
        $data_where["prpCitemKindsTemp[10].itemKindNo"]="11";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[10].clauseCode"]="050061";
        $data_where["prpCitemKindsTemp[10].kindCode"]="050441";
        $data_where["relateSpecial[10]"]="";
        $data_where["prpCitemKindsTemp[10].kindName"]="修理期间费用补偿险";
        $data_where["prpCitemKindsTemp[10].unitAmount"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
        $data_where["prpCitemKindsTemp[10].quantity"]=$business['POLICY']['RDCCI_INSURANCE_UNIT'];
        $data_where["prpCitemKindsTemp[10].amount"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY']*$business['POLICY']['RDCCI_INSURANCE_UNIT'];
        $data_where["prpCitemKindsTemp[10].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[10].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[10].startHour"]="0";
        $data_where["prpCitemKindsTemp[10].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[10].endHour"]="24";
        $data_where["prpCitemKindsTemp[10].flag"]="2000000";
        $data_where["prpCitemKindsTemp[10].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[10].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[10].rate"]="6.5000";
        $data_where["prpCitemKindsTemp[10].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[10].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[10].dutyFlag"]="2";



        $data_where["prpCprofitDetailsTemp[30].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[30].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[30].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[30]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[30].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[30].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[30].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[30].kindCode"]="050441";
        $data_where["prpCprofitDetailsTemp[30].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[30].flag"]="";
        $data_where["prpCprofitDetailsTemp[30].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[30].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[30].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[30].id.itemKindNo"]="11";
        $data_where["prpCprofitDetailsTemp[30].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[31].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[31].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[31].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[31]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[31].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[31].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[31].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[31].kindCode"]="050441";
        $data_where["prpCprofitDetailsTemp[31].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[31].flag"]="";
        $data_where["prpCprofitDetailsTemp[31].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[31].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[31].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[31].id.itemKindNo"]="11";
        $data_where["prpCprofitDetailsTemp[31].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[32].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[32].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[32].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[32]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[32].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[32].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[32].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[32].kindCode"]="050441";
        $data_where["prpCprofitDetailsTemp[32].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[32].flag"]="";
        $data_where["prpCprofitDetailsTemp[32].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[32].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[32].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[32].id.itemKindNo"]="11";
        $data_where["prpCprofitDetailsTemp[32].id.serialNo"]="0";

        $data_where["prpCitemKindsTemp[11].min"]="";
        $data_where["prpCitemKindsTemp[11].max"]="";
        $data_where["prpCitemKindsTemp[11].itemKindNo"]="12";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[11].clauseCode"]="050064";
        $data_where["prpCitemKindsTemp[11].kindCode"]="050451";
        $data_where["relateSpecial[11]"]="";
        $data_where["prpCitemKindsTemp[11].kindName"]="机动车损失保险无法找到第三方特约险";
        $data_where["prpCitemKindsTemp[11].amount"]="0.00";
        $data_where["prpCitemKindsTemp[11].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[11].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[11].startHour"]="0";
        $data_where["prpCitemKindsTemp[11].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[11].endHour"]="24";
        $data_where["prpCitemKindsTemp[11].flag"]="2000000";
        $data_where["prpCitemKindsTemp[11].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[11].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[11].rate"]="2.5000";
        $data_where["prpCitemKindsTemp[11].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[11].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[11].dutyFlag"]="2";


        $data_where["prpCprofitDetailsTemp[33].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[33].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[33].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[33]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[33].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[33].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[33].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[33].kindCode"]="050451";
        $data_where["prpCprofitDetailsTemp[33].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[33].flag"]="";
        $data_where["prpCprofitDetailsTemp[33].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[33].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[33].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[33].id.itemKindNo"]="12";
        $data_where["prpCprofitDetailsTemp[33].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[34].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[34].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[34].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[34]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[34].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[34].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[34].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[34].kindCode"]="050451";
        $data_where["prpCprofitDetailsTemp[34].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[34].flag"]="";
        $data_where["prpCprofitDetailsTemp[34].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[34].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[34].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[34].id.itemKindNo"]="12";
        $data_where["prpCprofitDetailsTemp[34].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[35].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[35].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[35].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[35]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[35].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[35].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[35].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[35].kindCode"]="050451";
        $data_where["prpCprofitDetailsTemp[35].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[35].flag"]="";
        $data_where["prpCprofitDetailsTemp[35].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[35].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[35].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[35].id.itemKindNo"]="12";
        $data_where["prpCprofitDetailsTemp[35].id.serialNo"]="0";



        $data_where["prpCitemKindsTemp[23].min"]="";
        $data_where["prpCitemKindsTemp[23].max"]="";
        $data_where["prpCitemKindsTemp[23].itemKindNo"]="13";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[23].clauseCode"]="050060";
        $data_where["prpCitemKindsTemp[23].kindCode"]="050461";
        $data_where["relateSpecial[23]"]="050938";
        $data_where["prpCitemKindsTemp[23].kindName"]="发动机涉水损失险";
        $data_where["prpCitemKindsTemp[23].amount"]="0.00";
        $data_where["prpCitemKindsTemp[23].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[23].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[23].startHour"]="0";
        $data_where["prpCitemKindsTemp[23].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[23].endHour"]="24";
        $data_where["prpCitemKindsTemp[23].flag"]="2001000";
        $data_where["prpCitemKindsTemp[23].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[23].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[23].rate"]="5.0000";
        $data_where["prpCitemKindsTemp[23].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[23].netPremium"]="35.20";
        $data_where["prpCitemKindsTemp[23].taxPremium"]="2.11";
        $data_where["prpCitemKindsTemp[23].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[23].dutyFlag"]="2";
        $data_where["prpCitemKindsTemp[23].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[23].dutyFlag"]="2";


        $data_where["prpCprofitDetailsTemp[36].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[36].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[36].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[36]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[36].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[36].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[36].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[36].kindCode"]="050461";
        $data_where["prpCprofitDetailsTemp[36].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[36].flag"]="";
        $data_where["prpCprofitDetailsTemp[36].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[36].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[36].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[36].id.itemKindNo"]="13";
        $data_where["prpCprofitDetailsTemp[36].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[37].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[37].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[37].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[37]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[37].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[37].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[37].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[37].kindCode"]="050461";
        $data_where["prpCprofitDetailsTemp[37].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[37].flag"]="";
        $data_where["prpCprofitDetailsTemp[37].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[37].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[37].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[37].id.itemKindNo"]="13";
        $data_where["prpCprofitDetailsTemp[37].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[38].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[38].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[38].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[38]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[38].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[38].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[38].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[38].kindCode"]="050461";
        $data_where["prpCprofitDetailsTemp[38].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[38].flag"]="";
        $data_where["prpCprofitDetailsTemp[38].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[38].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[38].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[38].id.itemKindNo"]="13";
        $data_where["prpCprofitDetailsTemp[38].id.serialNo"]="0";

        $data_where["prpCitemKindsTemp.itemKindSpecialSumPremium"]="";
        $data_where["kindcodesub"]="";


        $data_where["prpCitemKindsTemp[13].kindName"]="不计免赔险（车损险）";
        $data_where["prpCitemKindsTemp[13].amount"]="0.00";
        $data_where["prpCitemKindsTemp[13].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[13].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[13].startHour"]="0";
        $data_where["prpCitemKindsTemp[13].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[13].endHour"]="24";
        $data_where["prpCitemKindsTemp[13].flag"]="2000000";
        $data_where["prpCitemKindsTemp[13].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[13].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[13].rate"]="15.0000";
        $data_where["prpCitemKindsTemp[13].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[13].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[13].kindCode"]="050930";
        $data_where["prpCitemKindsTemp[13].itemKindNo"]="14";
        $data_where["prpCitemKindsTemp[13].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[13].dutyFlag"]="2";



        $data_where["prpCprofitDetailsTemp[39].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[39].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[39].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[39]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[39].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[39].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[39].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[39].kindCode"]="050930";
        $data_where["prpCprofitDetailsTemp[39].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[39].flag"]="";
        $data_where["prpCprofitDetailsTemp[39].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[39].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[39].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[39].id.itemKindNo"]="14";
        $data_where["prpCprofitDetailsTemp[39].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[40].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[40].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[40].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[40]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[40].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[40].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[40].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[40].kindCode"]="050930";
        $data_where["prpCprofitDetailsTemp[40].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[40].flag"]="";
        $data_where["prpCprofitDetailsTemp[40].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[40].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[40].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[40].id.itemKindNo"]="14";
        $data_where["prpCprofitDetailsTemp[40].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[41].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[41].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[41].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[41]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[41].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[41].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[41].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[41].kindCode"]="050930";
        $data_where["prpCprofitDetailsTemp[41].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[41].flag"]="";
        $data_where["prpCprofitDetailsTemp[41].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[41].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[41].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[41].id.itemKindNo"]="14";
        $data_where["prpCprofitDetailsTemp[41].id.serialNo"]="0";




        $data_where["prpCitemKindsTemp[12].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[12].kindCode"]="050931";
        $data_where["prpCitemKindsTemp[12].itemKindNo"]="15";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[12].kindName"]="不计免赔险（三者险）";
        $data_where["prpCitemKindsTemp[12].amount"]="0.00";
        $data_where["prpCitemKindsTemp[12].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[12].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[12].startHour"]="0";
        $data_where["prpCitemKindsTemp[12].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[12].endHour"]="24";
        $data_where["prpCitemKindsTemp[12].flag"]="2000000";
        $data_where["prpCitemKindsTemp[12].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[12].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[12].rate"]="15.0000";
        $data_where["prpCitemKindsTemp[12].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[12].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[12].dutyFlag"]="2";


        $data_where["prpCprofitDetailsTemp[42].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[42].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[42].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[42]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[42].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[42].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[42].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[42].kindCode"]="050931";
        $data_where["prpCprofitDetailsTemp[42].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[42].flag"]="";
        $data_where["prpCprofitDetailsTemp[42].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[42].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[42].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[42].id.itemKindNo"]="15";
        $data_where["prpCprofitDetailsTemp[42].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[43].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[43].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[43].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[43]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[43].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[43].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[43].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[43].kindCode"]="050931";
        $data_where["prpCprofitDetailsTemp[43].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[43].flag"]="";
        $data_where["prpCprofitDetailsTemp[43].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[43].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[43].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[43].id.itemKindNo"]="15";
        $data_where["prpCprofitDetailsTemp[43].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[44].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[44].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[44].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[44]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[44].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[44].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[44].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[44].kindCode"]="050931";
        $data_where["prpCprofitDetailsTemp[44].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[44].flag"]="";
        $data_where["prpCprofitDetailsTemp[44].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[44].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[44].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[44].id.itemKindNo"]="15";
        $data_where["prpCprofitDetailsTemp[44].id.serialNo"]="0";




        $data_where["prpCitemKindsTemp[15].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[15].kindCode"]="050932";
        $data_where["prpCitemKindsTemp[15].itemKindNo"]="16";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[15].kindName"]="不计免赔险（盗抢险）";
        $data_where["prpCitemKindsTemp[15].amount"]="0.00";
        $data_where["prpCitemKindsTemp[15].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[15].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[15].startHour"]="0";
        $data_where["prpCitemKindsTemp[15].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[15].endHour"]="24";
        $data_where["prpCitemKindsTemp[15].flag"]="2000000";
        $data_where["prpCitemKindsTemp[15].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[15].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[15].rate"]="20.0000";
        $data_where["prpCitemKindsTemp[15].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[15].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[15].dutyFlag"]="2";



        $data_where["prpCprofitDetailsTemp[45].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[45].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[45].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[45]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[45].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[45].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[45].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[45].kindCode"]="050932";
        $data_where["prpCprofitDetailsTemp[45].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[45].flag"]="";
        $data_where["prpCprofitDetailsTemp[45].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[45].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[45].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[45].id.itemKindNo"]="16";
        $data_where["prpCprofitDetailsTemp[45].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[46].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[46].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[46].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[46]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[46].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[46].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[46].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[46].kindCode"]="050932";
        $data_where["prpCprofitDetailsTemp[46].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[46].flag"]="";
        $data_where["prpCprofitDetailsTemp[46].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[46].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[46].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[46].id.itemKindNo"]="16";
        $data_where["prpCprofitDetailsTemp[46].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[47].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[47].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[47].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[47]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[47].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[47].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[47].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[47].kindCode"]="050932";
        $data_where["prpCprofitDetailsTemp[47].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[47].flag"]="";
        $data_where["prpCprofitDetailsTemp[47].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[47].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[47].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[47].id.itemKindNo"]="16";
        $data_where["prpCprofitDetailsTemp[47].id.serialNo"]="0";


        $data_where["prpCitemKindsTemp[16].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[16].kindCode"]="050933";
        $data_where["prpCitemKindsTemp[16].itemKindNo"]="17";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[16].kindName"]="不计免赔险（车上人员（司机））";
        $data_where["prpCitemKindsTemp[16].amount"]="0.00";
        $data_where["prpCitemKindsTemp[16].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[16].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[16].startHour"]="0";
        $data_where["prpCitemKindsTemp[16].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[16].endHour"]="24";
        $data_where["prpCitemKindsTemp[16].flag"]="2000000";
        $data_where["prpCitemKindsTemp[16].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[16].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[16].rate"]="15.0000";
        $data_where["prpCitemKindsTemp[16].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[16].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[16].dutyFlag"]="2";


        $data_where["prpCprofitDetailsTemp[48].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[48].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[48].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[48]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[48].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[48].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[48].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[48].kindCode"]="050933";
        $data_where["prpCprofitDetailsTemp[48].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[48].flag"]="";
        $data_where["prpCprofitDetailsTemp[48].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[48].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[48].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[48].id.itemKindNo"]="17";
        $data_where["prpCprofitDetailsTemp[48].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[49].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[49].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[49].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[49]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[49].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[49].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[49].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[49].kindCode"]="050933";
        $data_where["prpCprofitDetailsTemp[49].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[49].flag"]="";
        $data_where["prpCprofitDetailsTemp[49].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[49].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[49].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[49].id.itemKindNo"]="17";
        $data_where["prpCprofitDetailsTemp[49].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[50].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[50].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[50].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[50]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[50].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[50].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[50].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[50].kindCode"]="050933";
        $data_where["prpCprofitDetailsTemp[50].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[50].flag"]="";
        $data_where["prpCprofitDetailsTemp[50].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[50].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[50].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[50].id.itemKindNo"]="17";
        $data_where["prpCprofitDetailsTemp[50].id.serialNo"]="0";


        $data_where["prpCitemKindsTemp[17].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[17].kindCode"]="050934";
        $data_where["prpCitemKindsTemp[17].itemKindNo"]="18";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[17].kindName"]="不计免赔险（车上人员（乘客））";
        $data_where["prpCitemKindsTemp[17].amount"]="0.00";
        $data_where["prpCitemKindsTemp[17].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[17].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[17].startHour"]="0";
        $data_where["prpCitemKindsTemp[17].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[17].endHour"]="24";
        $data_where["prpCitemKindsTemp[17].flag"]="2000000";
        $data_where["prpCitemKindsTemp[17].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[17].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[17].rate"]="15.0000";
        $data_where["prpCitemKindsTemp[17].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[17].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[17].dutyFlag"]="2";



        $data_where["prpCprofitDetailsTemp[51].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[51].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[51].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[51]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[51].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[51].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[51].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[51].kindCode"]="050934";
        $data_where["prpCprofitDetailsTemp[51].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[51].flag"]="";
        $data_where["prpCprofitDetailsTemp[51].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[51].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[51].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[51].id.itemKindNo"]="18";
        $data_where["prpCprofitDetailsTemp[51].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[52].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[52].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[52].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[52]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[52].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[52].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[52].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[52].kindCode"]="050934";
        $data_where["prpCprofitDetailsTemp[52].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[52].flag"]="";
        $data_where["prpCprofitDetailsTemp[52].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[52].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[52].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[52].id.itemKindNo"]="18";
        $data_where["prpCprofitDetailsTemp[52].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[53].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[53].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[53].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[53]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[53].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[53].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[53].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[53].kindCode"]="050934";
        $data_where["prpCprofitDetailsTemp[53].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[53].flag"]="";
        $data_where["prpCprofitDetailsTemp[53].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[53].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[53].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[53].id.itemKindNo"]="18";
        $data_where["prpCprofitDetailsTemp[53].id.serialNo"]="0";




        $data_where["prpCitemKindsTemp[18].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[18].kindCode"]="050935";
        $data_where["prpCitemKindsTemp[18].itemKindNo"]="19";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[18].kindName"]="不计免赔险（自燃损失险）";
        $data_where["prpCitemKindsTemp[18].amount"]="0.00";
        $data_where["prpCitemKindsTemp[18].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[18].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[18].startHour"]="0";
        $data_where["prpCitemKindsTemp[18].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[18].endHour"]="24";
        $data_where["prpCitemKindsTemp[18].flag"]="2000000";
        $data_where["prpCitemKindsTemp[18].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[18].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[18].rate"]="20.0000";
        $data_where["prpCitemKindsTemp[18].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[18].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[18].dutyFlag"]="2";



        $data_where["prpCprofitDetailsTemp[54].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[54].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[54].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[54]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[54].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[54].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[54].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[54].kindCode"]="050935";
        $data_where["prpCprofitDetailsTemp[54].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[54].flag"]="";
        $data_where["prpCprofitDetailsTemp[54].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[54].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[54].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[54].id.itemKindNo"]="19";
        $data_where["prpCprofitDetailsTemp[54].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[55].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[55].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[55].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[55]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[55].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[55].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[55].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[55].kindCode"]="050935";
        $data_where["prpCprofitDetailsTemp[55].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[55].flag"]="";
        $data_where["prpCprofitDetailsTemp[55].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[55].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[55].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[55].id.itemKindNo"]="19";
        $data_where["prpCprofitDetailsTemp[55].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[56].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[56].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[56].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[56]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[56].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[56].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[56].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[56].kindCode"]="050935";
        $data_where["prpCprofitDetailsTemp[56].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[56].flag"]="";
        $data_where["prpCprofitDetailsTemp[56].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[56].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[56].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[56].id.itemKindNo"]="19";
        $data_where["prpCprofitDetailsTemp[56].id.serialNo"]="0";


        $data_where["prpCitemKindsTemp[19].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[19].kindCode"]="050936";
        $data_where["prpCitemKindsTemp[19].itemKindNo"]="20";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[19].kindName"]="不计免赔险（新增设备损失险）";
        $data_where["prpCitemKindsTemp[19].amount"]="0.00";
        $data_where["prpCitemKindsTemp[19].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[19].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[19].startHour"]="0";
        $data_where["prpCitemKindsTemp[19].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[19].endHour"]="24";
        $data_where["prpCitemKindsTemp[19].flag"]="2000000";
        $data_where["prpCitemKindsTemp[19].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[19].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[19].rate"]="15.0000";
        $data_where["prpCitemKindsTemp[19].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[19].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[19].dutyFlag"]="2";


        $data_where["prpCprofitDetailsTemp[57].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[57].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[57].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[57]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[57].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[57].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[57].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[57].kindCode"]="050936";
        $data_where["prpCprofitDetailsTemp[57].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[57].flag"]="";
        $data_where["prpCprofitDetailsTemp[57].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[57].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[57].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[57].id.itemKindNo"]="20";
        $data_where["prpCprofitDetailsTemp[57].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[58].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[58].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[58].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[58]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[58].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[58].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[58].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[58].kindCode"]="050936";
        $data_where["prpCprofitDetailsTemp[58].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[58].flag"]="";
        $data_where["prpCprofitDetailsTemp[58].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[58].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[58].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[58].id.itemKindNo"]="20";
        $data_where["prpCprofitDetailsTemp[58].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[59].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[59].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[59].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[59]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[59].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[59].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[59].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[59].kindCode"]="050936";
        $data_where["prpCprofitDetailsTemp[59].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[59].flag"]="";
        $data_where["prpCprofitDetailsTemp[59].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[59].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[59].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[59].id.itemKindNo"]="20";
        $data_where["prpCprofitDetailsTemp[59].id.serialNo"]="0";




        $data_where["prpCitemKindsTemp[20].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[20].kindCode"]="050937";
        $data_where["prpCitemKindsTemp[20].itemKindNo"]="21";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[20].kindName"]="不计免赔险（车身划痕损失险）";
        $data_where["prpCitemKindsTemp[20].amount"]="0.00";
        $data_where["prpCitemKindsTemp[20].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[20].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[20].startHour"]="0";
        $data_where["prpCitemKindsTemp[20].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[20].endHour"]="24";
        $data_where["prpCitemKindsTemp[20].flag"]="2000000";
        $data_where["prpCitemKindsTemp[20].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[20].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[20].rate"]="15.0000";
        $data_where["prpCitemKindsTemp[20].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[20].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[20].dutyFlag"]="2";


        $data_where["prpCprofitDetailsTemp[60].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[60].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[60].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[60]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[60].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[60].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[60].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[60].kindCode"]="050937";
        $data_where["prpCprofitDetailsTemp[60].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[60].flag"]="";
        $data_where["prpCprofitDetailsTemp[60].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[60].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[60].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[60].id.itemKindNo"]="21";
        $data_where["prpCprofitDetailsTemp[60].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[61].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[61].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[61].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[61]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[61].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[61].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[61].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[61].kindCode"]="050937";
        $data_where["prpCprofitDetailsTemp[61].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[61].flag"]="";
        $data_where["prpCprofitDetailsTemp[61].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[61].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[61].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[61].id.itemKindNo"]="21";
        $data_where["prpCprofitDetailsTemp[61].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[62].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[62].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[62].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[62]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[62].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[62].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[62].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[62].kindCode"]="050937";
        $data_where["prpCprofitDetailsTemp[62].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[62].flag"]="";
        $data_where["prpCprofitDetailsTemp[62].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[62].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[62].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[62].id.itemKindNo"]="21";
        $data_where["prpCprofitDetailsTemp[62].id.serialNo"]="0";




        $data_where["prpCitemKindsTemp[22].clauseCode"]="050066";
        $data_where["prpCitemKindsTemp[22].kindCode"]="050938";
        $data_where["prpCitemKindsTemp[22].itemKindNo"]="22";
        $data_where["kindcodesub"]="";
        $data_where["prpCitemKindsTemp[22].kindName"]="不计免赔险（发动机涉水损失险）";
        $data_where["prpCitemKindsTemp[22].amount"]="0.00";
        $data_where["prpCitemKindsTemp[22].calculateFlag"]="N";
        $data_where["prpCitemKindsTemp[22].startDate"]=$business['BUSINESS_START_TIME'];
        $data_where["prpCitemKindsTemp[22].startHour"]="0";
        $data_where["prpCitemKindsTemp[22].endDate"]=$business['BUSINESS_END_TIME'];
        $data_where["prpCitemKindsTemp[22].endHour"]="24";
        $data_where["prpCitemKindsTemp[22].flag"]="2000000";
        $data_where["prpCitemKindsTemp[22].basePremium"]="0.00";
        $data_where["prpCitemKindsTemp[22].riskPremium"]="0.00";
        $data_where["prpCitemKindsTemp[22].rate"]="15.0000";
        $data_where["prpCitemKindsTemp[22].disCount"]=$_SESSION['DISCOUNT'];
        $data_where["prpCitemKindsTemp[22].taxRate"]="6.00000";
        $data_where["prpCitemKindsTemp[22].dutyFlag"]="2";



        $data_where["prpCprofitDetailsTemp[63].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[63].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitDetailsTemp[63].condition"]="自主核保优惠系数";
        $data_where["profitRateTemp[63]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[63].profitRate"]="";
        $data_where["prpCprofitDetailsTemp[63].profitRateMin"]="";
        $data_where["prpCprofitDetailsTemp[63].profitRateMax"]="";
        $data_where["prpCprofitDetailsTemp[63].kindCode"]="050938";
        $data_where["prpCprofitDetailsTemp[63].conditionCode"]="C03";
        $data_where["prpCprofitDetailsTemp[63].flag"]="";
        $data_where["prpCprofitDetailsTemp[63].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[63].id.profitCode"]="C03";
        $data_where["prpCprofitDetailsTemp[63].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[63].id.itemKindNo"]="22";
        $data_where["prpCprofitDetailsTemp[63].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[64].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[64].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitDetailsTemp[64].condition"]="连续3年没有发生赔款";
        $data_where["profitRateTemp[64]"]="0.60000000";
        $data_where["prpCprofitDetailsTemp[64].profitRate"]="60.000000";
        $data_where["prpCprofitDetailsTemp[64].profitRateMin"]="60.000000";
        $data_where["prpCprofitDetailsTemp[64].profitRateMax"]="200.000000";
        $data_where["prpCprofitDetailsTemp[64].kindCode"]="050938";
        $data_where["prpCprofitDetailsTemp[64].conditionCode"]="C0101";
        $data_where["prpCprofitDetailsTemp[64].flag"]="";
        $data_where["prpCprofitDetailsTemp[64].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[64].id.profitCode"]="C01";
        $data_where["prpCprofitDetailsTemp[64].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[64].id.itemKindNo"]="22";
        $data_where["prpCprofitDetailsTemp[64].id.serialNo"]="0";
        $data_where["prpCprofitDetailsTemp[65].chooseFlag"]="1";
        $data_where["prpCprofitDetailsTemp[65].profitName"]="自主渠道系数";
        $data_where["prpCprofitDetailsTemp[65].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["profitRateTemp[65]"]="0.85000000";
        $data_where["prpCprofitDetailsTemp[65].profitRate"]="85.000000";
        $data_where["prpCprofitDetailsTemp[65].profitRateMin"]="85.000000";
        $data_where["prpCprofitDetailsTemp[65].profitRateMax"]="115.000000";
        $data_where["prpCprofitDetailsTemp[65].kindCode"]="050938";
        $data_where["prpCprofitDetailsTemp[65].conditionCode"]="C0206";
        $data_where["prpCprofitDetailsTemp[65].flag"]="";
        $data_where["prpCprofitDetailsTemp[65].id.proposalNo"]="";
        $data_where["prpCprofitDetailsTemp[65].id.profitCode"]="C02";
        $data_where["prpCprofitDetailsTemp[65].id.profitType"]="1";
        $data_where["prpCprofitDetailsTemp[65].id.itemKindNo"]="22";
        $data_where["prpCprofitDetailsTemp[65].id.serialNo"]="0";




        $data_where["hidden_index_itemKind"]="22";
        $data_where["hidden_index_profitDetial"]="66";
        $data_where["prpCitemKindsTemp_[0].chooseFlag"]="on";
        $data_where["prpCitemKindsTemp_[0].itemKindNo"]="";
        $data_where["prpCitemKindsTemp_[0].startDate"]="";
        $data_where["prpCitemKindsTemp_[0].kindCode"]="";
        $data_where["prpCitemKindsTemp_[0].kindName"]="";
        $data_where["prpCitemKindsTemp_[0].startHour"]="";
        $data_where["prpCitemKindsTemp_[0].endDate"]="";
        $data_where["prpCitemKindsTemp_[0].endHour"]="";
        $data_where["prpCitemKindsTemp_[0].calculateFlag"]="";
        $data_where["relateSpecial_[0]"]="";
        $data_where["prpCitemKindsTemp_[0].clauseCode"]="";
        $data_where["prpCitemKindsTemp_[0].flag"]="";
        $data_where["prpCitemKindsTemp_[0].basePremium"]="";
        $data_where["prpCitemKindsTemp_[0].riskPremium"]="";
        $data_where["prpCitemKindsTemp_[0].amount"]="";
        $data_where["prpCitemKindsTemp_[0].rate"]="";
        $data_where["prpCitemKindsTemp_[0].benchMarkPremium"]="";
        $data_where["prpCitemKindsTemp_[0].disCount"]="";
        $data_where["prpCitemKindsTemp_[0].premium"]="";
        $data_where["prpCitemKindsTemp_[0].netPremium"]="";
        $data_where["prpCitemKindsTemp_[0].taxPremium"]="";
        $data_where["prpCitemKindsTemp_[0].taxRate"]="";
        $data_where["prpCitemKindsTemp_[0].dutyFlag"]="";
        $data_where["prpCitemKindsTemp_[0].unitAmount"]="";
        $data_where["prpCitemKindsTemp_[0].quantity"]="";
        $data_where["prpCitemKindsTemp_[0].value"]="";
        $data_where["prpCitemKindsTemp_[0].value"]="50";
        $data_where["prpCitemKindsTemp_[0].unitAmount"]="";
        $data_where["prpCitemKindsTemp_[0].quantity"]="";
        $data_where["prpCitemKindsTemp_[0].modeCode"]="10";
        $data_where["prpCitemKindsTemp_[0].modeCode"]="1";
        $data_where["prpCitemKindsTemp_[0].modeCode"]="1";
        $data_where["prpCitemKindsTemp_[0].value"]="1000";
        $data_where["prpCitemKindsTemp_[0].amount"]="2000";
        $data_where["prpCitemKindsTemp_[0].amount"]="2000";
        $data_where["prpCitemKindsTemp_[0].amount"]="10000";
        $data_where["prpCitemKindsTemp_[0].unitAmount"]="";
        $data_where["prpCitemKindsTemp_[0].quantity"]="60";
        $data_where["prpCitemKindsTemp_[0].unitAmount"]="";
        $data_where["prpCitemKindsTemp_[0].quantity"]="90";
        $data_where["prpCitemKindsTemp_[0].amount"]="";
        $data_where["prpCitemKindsTemp_[0].amount"]="50000.00";
        $data_where["prpCitemKindsTemp_[0].amount"]="10000.00";
        $data_where["prpCitemKindsTemp_[0].amount"]="5000.00";
        $data_where["itemKindLoadFlag"]="";
        $data_where["prpCprofitFactorsTemp[0].chooseFlag"]="1";
        $data_where["serialNo[0]"]="1";
        $data_where["prpCprofitFactorsTemp[0].profitName"]="无赔款优待及上年赔款记录";
        $data_where["prpCprofitFactorsTemp[0].condition"]="连续3年没有发生赔款";
        $data_where["rateTemp[0]"]="0.60000000";
        $data_where["prpCprofitFactorsTemp[0].rate"]="60.00000000";
        $data_where["prpCprofitFactorsTemp[0].lowerRate"]="60.000000";
        $data_where["prpCprofitFactorsTemp[0].upperRate"]="200.000000";
        $data_where["prpCprofitFactorsTemp[0].id.profitCode"]="C01";
        $data_where["prpCprofitFactorsTemp[0].id.conditionCode"]="C0101";
        $data_where["prpCprofitFactorsTemp[0].flag"]="";
        $data_where["prpCprofitFactorsTemp[1].chooseFlag"]="1";
        $data_where["serialNo[1]"]="2";
        $data_where["prpCprofitFactorsTemp[1].profitName"]="自主渠道系数";
        $data_where["prpCprofitFactorsTemp[1].condition"]="经纪及代理渠道业务优惠系数";
        $data_where["rateTemp[1]"]="0.85000000";
        $data_where["prpCprofitFactorsTemp[1].rate"]="85.00000000";
        $data_where["prpCprofitFactorsTemp[1].lowerRate"]="85.000000";
        $data_where["prpCprofitFactorsTemp[1].upperRate"]="115.000000";
        $data_where["prpCprofitFactorsTemp[1].id.profitCode"]="C02";
        $data_where["prpCprofitFactorsTemp[1].id.conditionCode"]="C0206";
        $data_where["prpCprofitFactorsTemp[1].flag"]="";
        $data_where["prpCprofitFactorsTemp[2].chooseFlag"]="1";
        $data_where["serialNo[2]"]="3";
        $data_where["prpCprofitFactorsTemp[2].profitName"]="自主核保优惠系数";
        $data_where["prpCprofitFactorsTemp[2].condition"]="自主核保优惠系数";
        $data_where["rateTemp[2]"]="0.85000000";
        $data_where["prpCprofitFactorsTemp[2].rate"]="85.00000000";
        $data_where["prpCprofitFactorsTemp[2].lowerRate"]="85.000000";
        $data_where["prpCprofitFactorsTemp[2].upperRate"]="115.000000";
        $data_where["prpCprofitFactorsTemp[2].id.profitCode"]="C03";
        $data_where["prpCprofitFactorsTemp[2].id.conditionCode"]="C03";
        $data_where["prpCprofitFactorsTemp[2].flag"]="";
        $data_where["proposalCacheFlagBi"]="1";
        $data_where["BIdemandNo"]="V0101PICC510016001479955380152";
        $data_where["BIdemandTime"]=date("Y-m-d",time());
        $data_where["bIRiskWarningType"]="";
        $data_where["noDamageYearsBIPlat"]="0";
        $data_where["prpCitemCarExt.lastDamagedBI"]="0";
        $data_where["lastDamagedBITemp"]="0";
        $data_where["DAZlastDamagedBI"]="";
        $data_where["prpCitemCarExt.thisDamagedBI"]="0";
        $data_where["prpCitemCarExt.noDamYearsBI"]="3";
        $data_where["noDamYearsBINumber"]="3";
        $data_where["prpCitemCarExt.lastDamagedCI"]="0";
        $data_where["BIDemandClaim_Flag"]="";
        $data_where["BiInsureDemandPay_[0].id.serialNo"]="";
        $data_where["BiInsureDemandPay_[0].payCompany"]="";
        $data_where["BiInsureDemandPay_[0].claimregistrationno"]="";
        $data_where["BiInsureDemandPay_[0].compensateNo"]="";
        $data_where["BiInsureDemandPay_[0].lossTime"]="";
        $data_where["BiInsureDemandPay_[0].endcCaseTime"]="";
        $data_where["PrpCmain_[0].startDate"]="";
        $data_where["PrpCmain_[0].endDate"]="";
        $data_where["BiInsureDemandPay_[0].lossFee"]="";
        $data_where["BiInsureDemandPay_[0].payType"]="";
        $data_where["BiInsureDemandPay_[0].personpayType"]="";
        $data_where["bIRiskWarningClaimItems_[0].id.serialNo"]="";
        $data_where["bIRiskWarningClaimItems_[0].riskWarningType"]="";
        $data_where["bIRiskWarningClaimItems_[0].claimSequenceNo"]="";
        $data_where["bIRiskWarningClaimItems_[0].insurerCode"]="";
        $data_where["bIRiskWarningClaimItems_[0].lossTime"]="";
        $data_where["bIRiskWarningClaimItems_[0].lossArea"]="";
        $data_where["prpCitemKindCI.shortRate"]="100";

        if($mvtalci['MVTALCI_PREMIUM']!="")
        {
            $data_where["prpCitemKindCI.familyNo"]="1"; 
        }
        if($mvtalci['MVTALCI_PREMIUM']=="")
        {

            $data_where["prpCitemKindCI.familyNo"]="0"; 
        }  
        $data_where["cIBPFlag"]="1";
        $data_where["prpCitemKindCI.unitAmount"]="122000.00";
        $data_where["prpCitemKindCI.id.itemKindNo"]="1";
        $data_where["prpCitemKindCI.kindCode"]="050100";
        $data_where["prpCitemKindCI.clauseCode"]="050001";
        $data_where["prpCitemKindCI.riskPremium"]="0.00";
        $data_where["prpCitemKindCI.kindName"]="机动车交通事故强制责任保险";
        $data_where["prpCitemKindCI.calculateFlag"]="Y";
        $data_where["prpCitemKindCI.basePremium"]="";
        $data_where["prpCitemKindCI.quantity"]="1";
        $data_where["prpCitemKindCI.amount"]="122000.00";
        $data_where["prpCitemKindCI.deductible"]="0.00";
        $data_where["prpCitemKindCI.adjustRate"]=empty($mvtalci['MVTALCI_COUNT'])?"":$mvtalci['MVTALCI_COUNT'];
        $data_where["prpCitemKindCI.rate"]="0";
        $data_where["prpCitemKindCI.benchMarkPremium"]=empty($_SESSION['BASE_PREMIUM'])?"":$_SESSION['BASE_PREMIUM'];
        $data_where["prpCitemKindCI.disCount"]="1";
        $data_where["prpCitemKindCI.premium"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data_where["prpCitemKindCI.flag"]="1000000";
        $data_where["prpCitemKindCI.netPremium"]=empty($_SESSION['NetPremium'])?"":$_SESSION['NetPremium'];
        $data_where["prpCitemKindCI.taxPremium"]=empty($_SESSION['MVTALCI_TAX_PREMIUM'])?"":$_SESSION['MVTALCI_TAX_PREMIUM'];

        $data_where["prpCitemKindCI.taxRate"]="6.00000";
        $data_where["prpCitemKindCI.dutyFlag"]="2";
        $data_where["prpCtrafficDetails_[0].trafficType"]="1";
        $data_where["prpCtrafficDetails_[0].accidentType"]="1";
        $data_where["prpCtrafficDetails_[0].indemnityDuty"]="有责";
        $data_where["prpCtrafficDetails_[0].sumPaid"]="";
        $data_where["prpCtrafficDetails_[0].accidentDate"]="";
        $data_where["prpCtrafficDetails_[0].payComCode"]="";
        $data_where["prpCtrafficDetails_[0].flag"]="";
        $data_where["prpCtrafficDetails_[0].id.serialNo"]="";
        $data_where["prpCtrafficDetails_[0].trafficType"]="1";
        $data_where["prpCtrafficDetails_[0].accidentType"]="1";
        $data_where["prpCtrafficDetails_[0].indemnityDuty"]="有责";
        $data_where["prpCtrafficDetails_[0].sumPaid"]="";
        $data_where["prpCtrafficDetails_[0].accidentDate"]="";
        $data_where["prpCtrafficDetails_[0].payComCode"]="";
        $data_where["prpCtrafficDetails_[0].flag"]="";
        $data_where["prpCtrafficDetails_[0].id.serialNo"]="";
        $data_where["prpCitemCarExt_CI.rateRloatFlag"]="01";
        $data_where["prpCitemCarExt_CI.noDamYearsCI"]="3";
        $data_where["prpCitemCarExt_CI.lastDamagedCI"]="0";
        $data_where["prpCitemCarExt_CI.flag"]=",";
        $data_where["prpCitemCarExt_CI.damFloatRatioCI"]="0.0000";
        $data_where["prpCitemCarExt_CI.offFloatRatioCI"]="0.0000";
        $data_where["prpCitemCarExt_CI.thisDamagedCI"]="0";
        $data_where["prpCitemCarExt_CI.flag"]=",";
        $data_where["hidden_index_ctraffic_NOPlat_Drink"]="0";
        $data_where["hidden_index_ctraffic_NOPlat"]="0";
        $data_where["ciInsureDemand.demandNo"]=$_SESSION['MVTALCI_Query_Code'];
        $data_where["ciInsureDemand.demandTime"]=date("Y-m-d",time());
        $data_where["ciInsureDemand.restricFlag"]="";
        $data_where["ciInsureDemand.preferentialDay"]="";
        $data_where["ciInsureDemand.preferentialPremium"]="";
        $data_where["ciInsureDemand.preferentialFormula"]="";
        $data_where["ciInsureDemand.lastyearenddate"]="";
        $data_where["proposalCacheFlagCi"]="1";
        $data_where["prpCitemCar.noDamageYears"]="0";
        $data_where["ciInsureDemand.rateRloatFlag"]="00";
        $data_where["ciInsureDemand.claimAdjustReason"]="A3";
        $data_where["ciInsureDemand.peccancyAdjustReason"]="V1";
        $data_where["cIRiskWarningType"]="";
        $data_where["CIDemandFecc_Flag"]="";
        $data_where["ciInsureDemandLoss_[0].id.serialNo"]="";
        $data_where["ciInsureDemandLoss_[0].lossTime"]="";
        $data_where["ciInsureDemandLoss_[0].lossDddress"]="";
        $data_where["ciInsureDemandLoss_[0].lossAction"]="";
        $data_where["ciInsureDemandLoss_[0].coeff"]="";
        $data_where["ciInsureDemandLoss_[0].lossType"]="";
        $data_where["ciInsureDemandLoss_[0].identifyType"]="";
        $data_where["ciInsureDemandLoss_[0].identifyNumber"]="";
        $data_where["ciInsureDemandLoss_[0].lossAcceptDate"]="";
        $data_where["ciInsureDemandLoss_[0].processingStatus"]="";
        $data_where["ciInsureDemandLoss_[0].lossActionDesc"]="";
        $data_where["CIDemandClaim_Flag"]="";
        $data_where["ciInsureDemandPay_[0].id.serialNo"]="";
        $data_where["ciInsureDemandPay_[0].payCompany"]="";
        $data_where["ciInsureDemandPay_[0].claimregistrationno"]="";
        $data_where["ciInsureDemandPay_[0].compensateNo"]="";
        $data_where["ciInsureDemandPay_[0].lossTime"]="";
        $data_where["ciInsureDemandPay_[0].endcCaseTime"]="";
        $data_where["ciInsureDemandPay_[0].lossFee"]="";
        $data_where["ciInsureDemandPay_[0].payType"]="";
        $data_where["ciInsureDemandPay_[0].personpayType"]="";
        $data_where["ciRiskWarningClaimItems_[0].id.serialNo"]="";
        $data_where["ciRiskWarningClaimItems_[0].riskWarningType"]="";
        $data_where["ciRiskWarningClaimItems_[0].claimSequenceNo"]="";
        $data_where["ciRiskWarningClaimItems_[0].insurerCode"]="";
        $data_where["ciRiskWarningClaimItems_[0].lossTime"]="";
        $data_where["ciRiskWarningClaimItems_[0].lossArea"]="";
        $data_where["ciInsureDemand.licenseNo"]="";
        $data_where["ciInsureDemand.licenseType"]="";
        $data_where["ciInsureDemand.useNatureCode"]="";
        $data_where["ciInsureDemand.frameNo"]="";
        $data_where["ciInsureDemand.engineNo"]="";
        $data_where["ciInsureDemand.licenseColorCode"]="";
        $data_where["ciInsureDemand.carOwner"]="";
        $data_where["ciInsureDemand.enrollDate"]="";
        $data_where["ciInsureDemand.makeDate"]="";
        $data_where["ciInsureDemand.seatCount"]="";
        $data_where["ciInsureDemand.tonCount"]="";
        $data_where["ciInsureDemand.validCheckDate"]="";
        $data_where["ciInsureDemand.manufacturerName"]="";
        $data_where["ciInsureDemand.modelCode"]="";
        $data_where["ciInsureDemand.brandCName"]="";
        $data_where["ciInsureDemand.brandName"]="";
        $data_where["ciInsureDemand.carKindCode"]="";
        $data_where["ciInsureDemand.checkDate"]="";
        $data_where["ciInsureDemand.endValidDate"]="";
        $data_where["ciInsureDemand.carStatus"]="";
        $data_where["ciInsureDemand.haulage"]="";
        $data_where["AccidentFlag"]="";
        $data_where["rateFloatFlag"]="ND3";
        $data_where["prpCtrafficRecordTemps_[0].id.serialNo"]="";
        $data_where["prpCtrafficRecordTemps_[0].accidentDate"]="";
        $data_where["prpCtrafficRecordTemps_[0].claimDate"]="";
        $data_where["hidden_index_ctraffic"]="0";
        $data_where["_taxUnit"]="";
        $data_where["taxPlatFormTime"]="2013-07-27";
        $data_where["iniPrpCcarShipTax_Flag"]="";
        $data_where["strCarShipFlag"]="1";
        $data_where["prpCcarShipTax.taxType"]="4";
        $data_where["prpCcarShipTax.calculateMode"]="C1";
        $data_where["prpCcarShipTax.leviedDate"]=Date('Y-m-d');
        $data_where["prpCcarShipTax.carKindCode"]="A01";
        $data_where["prpCcarShipTax.model"]="B11";
        $data_where["prpCcarShipTax.taxPayerIdentNo"]=$auto['IDENTIFY_NO'];
        $data_where["prpCcarShipTax.taxPayerNumber"]=$auto['IDENTIFY_NO'];
        $data_where["prpCcarShipTax.carLotEquQuality"]=$auto['KERB_MASS'];
        $data_where["prpCcarShipTax.taxPayerCode"]=$_SESSION['id_cord'];
        $data_where["prpCcarShipTax.id.itemNo"]="1";
        $data_where["prpCcarShipTax.taxPayerNature"]="3";
        $data_where["prpCcarShipTax.taxPayerName"]=$auto['OWNER'];
        $data_where["prpCcarShipTax.taxUnit"]="辆/年";
        $data_where["prpCcarShipTax.taxComCode"]="";
        $data_where["prpCcarShipTax.taxComName"]="四川省地方税务局";
        $data_where["prpCcarShipTax.taxExplanation"]="";
        $data_where["prpCcarShipTax.taxAbateReason"]="";
        $data_where["prpCcarShipTax.dutyPaidProofNo_1"]=empty($_SESSION['DOCUMENG_NUMBER'])?"":$_SESSION['DOCUMENG_NUMBER'];
        $data_where["prpCcarShipTax.dutyPaidProofNo_2"]=empty($_SESSION['DOCUMENG_NUMBER'])?"":$_SESSION['DOCUMENG_NUMBER'];
        $data_where["prpCcarShipTax.dutyPaidProofNo"]=empty($_SESSION['DOCUMENG_NUMBER'])?"":$_SESSION['DOCUMENG_NUMBER'];
        $data_where["prpCcarShipTax.taxAbateRate"]="";
        $data_where["prpCcarShipTax.taxAbateAmount"]="";
        $data_where["prpCcarShipTax.taxAbateType"]="1";
        $data_where["prpCcarShipTax.taxUnitAmount"]=empty($mvtalci['TAX_PREMIUM'])?"":$mvtalci['TAX_PREMIUM'];
        $data_where["prpCcarShipTax.prePayTaxYear"]=date("Y",time())-1;
        $data_where["prpCcarShipTax.prePolicyEndDate"]="";
        $data_where["prpCcarShipTax.payStartDate"]=date("Y",time())."-01-01";//本次缴税起期
        $data_where["prpCcarShipTax.payEndDate"]=date("Y",time())."-12-31";//本次缴税止期
        $data_where["prpCcarShipTax.thisPayTax"]=empty($mvtalci['TAX_PREMIUM'])?"":$mvtalci['TAX_PREMIUM'];
        $data_where["prpCcarShipTax.prePayTax"]="0.00";
        $data_where["prpCcarShipTax.taxItemCode"]="";
        $data_where["prpCcarShipTax.taxItemName"]="";
        $data_where["prpCcarShipTax.baseTaxation"]="";
        $data_where["prpCcarShipTax.taxRelifFlag"]="";
        $data_where["prpCcarShipTax.delayPayTax"]="0.00";
        $data_where["prpCcarShipTax.sumPayTax"]=empty($mvtalci['TAX_PREMIUM'])?"":$mvtalci['TAX_PREMIUM'];
        $data_where["CarShipInit_Flag"]="";
        $data_where["prpCcarShipTax.flag"]="";
        $data_where["quotationtaxPayerCode"]=$_SESSION['id_cord'];//纳税人代码
        $data_where["noBringOutEngage"]="";
        $data_where["prpCengageTemps_[0].id.serialNo"]="";
        $data_where["prpCengageTemps_[0].clauseCode"]="";
        $data_where["prpCengageTemps_[0].clauseName"]="";
        $data_where["clauses_[0]"]="";
        $data_where["prpCengageTemps_[0].flag"]="";
        $data_where["prpCengageTemps_[0].engageFlag"]="";
        $data_where["prpCengageTemps_[0].maxCount"]="";
        $data_where["prpCengageTemps_[0].clauses"]="";
        $data_where["iniPrpCengage_Flag"]="";
        $data_where["hidden_index_engage"]="0";
        $data_where["costRateForPG"]="4.00";
        $data_where["certificateNo"]="76225124-4";
        $data_where["levelMaxRate"]="0.0000";
        $data_where["maxRateScm"]="";
        $data_where["levelMaxRateCi"]="0.0000";
        $data_where["maxRateScmCi"]="4.0000";
        $data_where["isModifyBI"]="";
        $data_where["isModifyCI"]="";
        $data_where["sumBICoinsRate"]="";
        $data_where["sumCICoinsRate"]="";
        $data_where["netCommission_Switch"]="";
        $data_where["agentsRateBI"]="";
        $data_where["agentsRateCI"]="1,51003O100138,1";
        $data_where["prpVisaRecordP.id.visaNo"]="";
        $data_where["prpVisaRecordP.id.visaCode"]="";
        $data_where["prpVisaRecordP.visaName"]="";
        $data_where["prpVisaRecordP.printType"]="101";
        $data_where["prpVisaRecordT.id.visaNo"]="";
        $data_where["prpVisaRecordT.id.visaCode"]="";
        $data_where["prpVisaRecordT.visaName"]="";
        $data_where["prpVisaRecordT.printType"]="103";
        $data_where["prpCmain.sumAmount"]=empty($_SESSION['KindsTemp_amount'])?"":$_SESSION['KindsTemp_amount'];//总保额
        $data_where["prpCmain.sumDiscount"]=empty($_SESSION['Total_Discount_Amount'])?"":$_SESSION['Total_Discount_Amount'];//总折扣金额
        $data_where["prpCstampTaxBI.biTaxRate"]="";
        $data_where["prpCstampTaxBI.biPayTax"]="";
        $data_where["prpCmain.sumPremium"]=empty($_SESSION['COUNT_PREMIUM'])?"0":$_SESSION['COUNT_PREMIUM'];
        $data_where["prpVisaRecordPCI.id.visaNo"]="";
        $data_where["prpVisaRecordPCI.id.visaCode"]="";
        $data_where["prpVisaRecordPCI.visaName"]="";
        $data_where["prpVisaRecordPCI.printType"]="201";
        $data_where["prpVisaRecordTCI.id.visaNo"]="";
        $data_where["prpVisaRecordTCI.id.visaCode"]="";
        $data_where["prpVisaRecordTCI.visaName"]="";
        $data_where["prpVisaRecordTCI.printType"]="203";
        $data_where["prpCmainCI.sumAmount"]="122000.00";//!empty($mvtalci['MVTALCI_COUNT'])?"122000.00":"";
        $data_where["prpCmainCI.sumDiscount"]=empty($_SESSION['BASEPREMIUM'])?"":$_SESSION['BASEPREMIUM'];//总折扣金额
        $data_where["prpCstampTaxCI.ciTaxRate"]="";
        $data_where["prpCstampTaxCI.ciPayTax"]="";
        $data_where["prpCmainCI.sumPremium"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data_where["prpCmainCar.rescueFundRate"]="0.0100";
        $data_where["prpCmainCar.resureFundFee"]=empty($_SESSION['BASEPREMIUM'])?"":$_SESSION['BASEPREMIUM']*0.01;//救助基金金额
        $data_where["prpCmain.projectCode"]="";
        $data_where["projectCode"]="";
        $data_where["costRateUpper"]="";
        $data_where["prpCmainCommon.ext3"]="";
        $data_where["importantProjectCode"]="";
        $data_where["prpCmain.operatorCode"]="";
        $data_where["operatorName"]="陈燕";
        $data_where["operateDateShow"]=date("Y-m-d",time());
        $data_where["prpCmain.coinsFlag"]="00";
        $data_where["coinsFlagBak"]="00";
        $data_where["premium"]="";
        $data_where["prpCmain.language"]="CNY";
        $data_where["prpCmain.policySort"]="1";
        $data_where["prpCmain.policyRelCode"]="";
        $data_where["prpCmain.policyRelName"]="";
        $data_where["subsidyRate"]="0";
        $data_where["policyRel"]="";
        $data_where["prpCmain.reinsFlag"]="0";
        $data_where["prpCmain.agriFlag"]="0";
        $data_where["premium"]="";
        $data_where["prpCmainCar.carCheckStatus"]="0";
        $data_where["prpCmainCar.carChecker"]="";
        $data_where["carCheckerTranslate"]="";
        $data_where["prpCmainCar.carCheckTime"]="";
        $data_where["prpCmainCommon.DBCFlag"]="0";
        $data_where["prpCmain.argueSolution"]="1";
        $data_where["prpCmain.arbitBoardName"]="";
        $data_where["arbitBoardNameDes"]="";
        $data_where["prpCcommissionsTemp_[0].costType"]="";
        $data_where["prpCcommissionsTemp_[0].riskCode"]="";
        $data_where["prpCcommissionsTemp_[0].currency"]="AED";
        $data_where["prpCcommissionsTemp_[0].adjustFlag"]="0";
        $data_where["prpCcommissionsTemp_[0].upperFlag"]="0";
        $data_where["prpCcommissionsTemp_[0].auditRate"]="";
        $data_where["prpCcommissionsTemp_[0].auditFlag"]="1";
        $data_where["prpCcommissionsTemp_[0].sumPremium"]="";
        $data_where["prpCcommissionsTemp_[0].costRate"]="";
        $data_where["prpCcommissionsTemp_[0].costRateUpper"]="";
        $data_where["prpCcommissionsTemp_[0].coinsRate"]="100";
        $data_where["prpCcommissionsTemp_[0].coinsDeduct"]="1";
        $data_where["prpCcommissionsTemp_[0].costFee"]="";
        $data_where["prpCcommissionsTemp_[0].agreementNo"]="";
        $data_where["prpCcommissionsTemp_[0].configCode"]="";
        $data_where["prpCcommissionsTemp[0].costType"]="";
        $data_where["prpCcommissionsTemp[0].riskCode"]="DZA";
        $data_where["prpCcommissionsTemp[0].currency"]="CNY";
        $data_where["prpCcommissionsTemp[0].adjustFlag"]="0";
        $data_where["prpCcommissionsTemp[0].upperFlag"]="0";
        $data_where["prpCcommissionsTemp[0].auditRate"]="";
        $data_where["prpCcommissionsTemp[0].auditFlag"]="1";
        $data_where["prpCcommissionsTemp[0].sumPremium"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data_where["prpCcommissionsTemp[0].costRate"]=empty($_SESSION['Counter_Fee']['DZA']['CostRate'])?"":$_SESSION['Counter_Fee']['DZA']['CostRate'];
        $data_where["prpCcommissionsTemp[0].costRateUpper"]=empty($_SESSION['Counter_Fee']['DZA']['CostRateUpper'])?"":$_SESSION['Counter_Fee']['DZA']['CostRateUpper'];
        $data_where["prpCcommissionsTemp[0].coinsRate"]="100.0000";
        $data_where["prpCcommissionsTemp[0].coinsDeduct"]="1";
        $data_where["prpCcommissionsTemp[0].costFee"]=empty($_SESSION['Counter_Fee']['DZA']['DZA_AMOUNT'])?"":$_SESSION['Counter_Fee']['DZA']['DZA_AMOUNT'];
        $data_where["prpCcommissionsTemp[0].agreementNo"]="RULE20125100000000015";
        $data_where["prpCcommissionsTemp[0].configCode"]="PUB";
        $data_where["prpCcommissionsTemp[1].costType"]="";
        $data_where["prpCcommissionsTemp[1].riskCode"]="DAA";
        $data_where["prpCcommissionsTemp[1].currency"]="CNY";
        $data_where["prpCcommissionsTemp[1].adjustFlag"]="0";
        $data_where["prpCcommissionsTemp[1].upperFlag"]="0";
        $data_where["prpCcommissionsTemp[1].auditRate"]="";
        $data_where["prpCcommissionsTemp[1].auditFlag"]="1";
        $data_where["prpCcommissionsTemp[1].sumPremium"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["prpCcommissionsTemp[1].costRate"]=empty($_SESSION['Counter_Fee']['DAA']['CostRate'])?"":$_SESSION['Counter_Fee']['DAA']['CostRate'];
        $data_where["prpCcommissionsTemp[1].costRateUpper"]=empty($_SESSION['Counter_Fee']['DAA']['CostRateUpper'])?"":$_SESSION['Counter_Fee']['DAA']['CostRateUpper'];
        $data_where["prpCcommissionsTemp[1].coinsRate"]="100.0000";
        $data_where["prpCcommissionsTemp[1].coinsDeduct"]="1";
        $data_where["prpCcommissionsTemp[1].costFee"]=empty($_SESSION['Counter_Fee']['DAA']['DAA_AMOUNT'])?"":$_SESSION['Counter_Fee']['DAA']['DAA_AMOUNT'];
        $data_where["prpCcommissionsTemp[1].agreementNo"]="RULE20145194000000018";
        $data_where["prpCcommissionsTemp[1].configCode"]="PUB";
        $data_where["hidden_index_commission"]="2";
        $data_where["prpCagents[4].roleType"]="";
        $data_where["roleTypeName[4]"]="代理人";
        $data_where["prpCagents[4].id.roleCode"]=$_SESSION['agentCode'];
        $data_where["prpCagents[4].roleCode_uni"]="";
        $data_where["prpCagents[4].roleName"]=empty($_SESSION['Counter_Fee']['DAA']['DAA_USER'])?$_SESSION['Counter_Fee']['DZA']['DAA_USER']:$_SESSION['Counter_Fee']['DAA']['DAA_USER'];
        $data_where["prpCagents[4].costRate"]=empty($_SESSION['Counter_Fee']['DAA']['CostRate'])?"":$_SESSION['Counter_Fee']['DAA']['CostRate'];
        $data_where["prpCagents[4].costFee"]=empty($_SESSION['Counter_Fee']['DAA']['DAA_AMOUNT'])?"":$_SESSION['Counter_Fee']['DAA']['DAA_AMOUNT'];
        $data_where["prpCagents[4].flag"]="undefined";
        $data_where["prpCagents[4].businessNature"]="3";
        $data_where["prpCagents[4].isMain"]="1";
        $data_where["prpCagentCIs[4].roleType"]="";
        $data_where["roleTypeNameCI[4]"]="代理人";
        $data_where["prpCagentCIs[4].id.roleCode"]=$_SESSION['agentCode'];
        $data_where["prpCagentCIs[4].roleCode_uni"]="";
        $data_where["prpCagentCIs[4].roleName"]=empty($_SESSION['Counter_Fee']['DZA']['DAA_USER'])?$_SESSION['Counter_Fee']['DAA']['DAA_USER']:$_SESSION['Counter_Fee']['DZA']['DAA_USER'];
        $data_where["prpCagentCIs[4].costRate"]=empty($_SESSION['Counter_Fee']['DZA']['CostRate'])?"":$_SESSION['Counter_Fee']['DZA']['CostRate'];
        $data_where["prpCagentCIs[4].costFee"]=empty($_SESSION['Counter_Fee']['DZA']['DZA_AMOUNT'])?"":$_SESSION['Counter_Fee']['DZA']['DZA_AMOUNT'];
        $data_where["prpCagentCIs[4].flag"]="undefined";
        $data_where["prpCagentCIs[4].businessNature"]="3";
        $data_where["prpCagentCIs[4].isMain"]="1";
        $data_where["scmIsOpen"]="1111100000";
        $data_where["prpCagents_[0].roleType"]="";
        $data_where["roleTypeName_[0]"]="代理人";
        $data_where["prpCagents_[0].id.roleCode"]=$_SESSION['agentCode'];
        $data_where["prpCagents_[0].roleCode_uni"]="";
        $data_where["prpCagents_[0].roleName"]=empty($_SESSION['Counter_Fee']['DAA']['DAA_USER'])?$_SESSION['Counter_Fee']['DZA']['DAA_USER']:$_SESSION['Counter_Fee']['DAA']['DAA_USER'];
        $data_where["prpCagents_[0].costRate"]=empty($_SESSION['Counter_Fee']['DAA']['CostRate'])?"":$_SESSION['Counter_Fee']['DAA']['CostRate'];
        $data_where["prpCagents_[0].costFee"]=empty($_SESSION['Counter_Fee']['DAA']['DAA_AMOUNT'])?"":$_SESSION['Counter_Fee']['DAA']['DAA_AMOUNT'];
        $data_where["prpCagents_[0].flag"]="undefined";
        $data_where["prpCagents_[0].businessNature"]="3";
        $data_where["prpCagents_[0].isMain"]="1";
        $data_where["prpCagentCIs_[0].roleType"]="";
        $data_where["roleTypeNameCI_[0]"]="代理人";
        $data_where["prpCagentCIs_[0].id.roleCode"]=$_SESSION['agentCode'];
        $data_where["prpCagentCIs_[0].roleCode_uni"]="";
        $data_where["prpCagentCIs_[0].roleName"]=empty($_SESSION['Counter_Fee']['DZA']['DAA_USER'])?$_SESSION['Counter_Fee']['DAA']['DAA_USER']:$_SESSION['Counter_Fee']['DZA']['DAA_USER'];
        $data_where["prpCagentCIs_[0].costRate"]=empty($_SESSION['Counter_Fee']['DZA']['CostRate'])?"":$_SESSION['Counter_Fee']['DZA']['CostRate'];
        $data_where["prpCagentCIs_[0].costFee"]=empty($_SESSION['Counter_Fee']['DZA']['DZA_AMOUNT'])?"":$_SESSION['Counter_Fee']['DZA']['DZA_AMOUNT'];
        $data_where["prpCagentCIs_[0].flag"]="undefined";
        $data_where["prpCagentCIs_[0].businessNature"]="3";
        $data_where["prpCagentCIs_[0].isMain"]="1";
        $data_where["commissionCount"]="";
        $data_where["prpCsaless_[0].salesDetailName"]="";
        $data_where["prpCsaless_[0].riskCode"]="";
        $data_where["prpCsaless_[0].splitRate"]="";
        $data_where["prpCsaless_[0].oriSplitNumber"]="";
        $data_where["prpCsaless_[0].splitFee"]="";
        $data_where["prpCsaless_[0].agreementNo"]="";
        $data_where["prpCsaless_[0].id.salesCode"]="";
        $data_where["prpCsaless_[0].salesName"]="";
        $data_where["prpCsaless_[0].id.proposalNo"]="";
        $data_where["prpCsaless_[0].id.salesDetailCode"]="";
        $data_where["prpCsaless_[0].totalRate"]="";
        $data_where["prpCsaless_[0].splitWay"]="";
        $data_where["prpCsaless_[0].totalRateMax"]="";
        $data_where["prpCsaless_[0].flag"]="";
        $data_where["prpCsaless_[0].remark"]="";
        $data_where["commissionPower"]="";
        $data_where["hidden_index_prpCsales"]="0";
        $data_where["prpCsalesDatils_[0].id.salesCode"]="";
        $data_where["prpCsalesDatils_[0].id.proposalNo"]="";
        $data_where["prpCsalesDatils_[0].id."]="";
        $data_where["prpCsalesDatils_[0].id.roleType"]="";
        $data_where["prpCsalesDatils_[0].id.roleCode"]="";
        $data_where["prpCsalesDatils_[0].currency"]="";
        $data_where["prpCsalesDatils_[0].splitDatilRate"]="";
        $data_where["prpCsalesDatils_[0].splitDatilFee"]="";
        $data_where["prpCsalesDatils_[0].roleName"]="";
        $data_where["prpCsalesDatils_[0].splitWay"]="";
        $data_where["prpCsalesDatils_[0].flag"]="";
        $data_where["prpCsalesDatils_[0].remark"]="";
        $data_where["hidden_index_prpCsalesDatil"]="0";
        $data_where["csManageSwitch"]="1";
        $data_where["prpCmainChannel.agentCode"]="";
        $data_where["prpCmainChannel.agentName"]="";
        $data_where["prpCmainChannel.organCode"]="";
        $data_where["prpCmainChannel.organCName"]="";
        $data_where["comCodeType"]="";
        $data_where["prpCmainChannel.identifyNumber"]="";
        $data_where["prpCmainChannel.identifyType"]="";
        $data_where["prpCmainChannel.manOrgCode"]="";
        $data_where["prpCmain.remark"]="";
        $data_where["prpDdismantleDetails_[0].id.agreementNo"]="";
        $data_where["prpDdismantleDetails_[0].flag"]="";
        $data_where["prpDdismantleDetails_[0].id.configCode"]="";
        $data_where["prpDdismantleDetails_[0].id.assignType"]="";
        $data_where["prpDdismantleDetails_[0].id.roleCode"]="";
        $data_where["prpDdismantleDetails_[0].roleName"]="";
        $data_where["prpDdismantleDetails_[0].costRate"]="";
        $data_where["prpDdismantleDetails_[0].roleFlag"]="";
        $data_where["prpDdismantleDetails_[0].businessNature"]="";
        $data_where["prpDdismantleDetails_[0].roleCode_uni"]="";
        $data_where["hidden_index_prpDdismantleDetails"]="0";
        $data_where["payTimes"]="1";
        $data_where["prpCplanTemps_[0].payNo"]="1";
        $data_where["prpCplanTemps_[0].serialNo"]="0";
        $data_where["prpCplanTemps_[0].endorseNo"]="";
        $data_where["cplan_[0].payReasonC"]="收保费";
        $data_where["prpCplanTemps_[0].payReason"]="R21";
        $data_where["prpCplanTemps_[0].planDate"]=empty($business['BUSINESS_END_TIME'])?"":$business['BUSINESS_END_TIME'];//截止日期
        $data_where["prpCplanTemps_[0].currency"]="CNY";
        $data_where["description_[0].currency"]="人民币";
        $data_where["prpCplanTemps_[0].planFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["cplans_[0].planFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["cplans_[0].backPlanFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["prpCplanTemps_[0].netPremium"]=empty($_SESSION['NET_PREMIUM'])?"":$_SESSION['NET_PREMIUM'];
        $data_where["prpCplanTemps_[0].taxPremium"]=empty($_SESSION['TAX'])?"":$_SESSION['TAX'];
        $data_where["prpCplanTemps_[0].delinquentFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["prpCplanTemps_[0].flag"]="";
        $data_where["prpCplanTemps_[0].subsidyRate"]="0";
        $data_where["prpCplanTemps_[0].isBICI"]="BI";
        $data_where["iniPrpCplan_Flag"]="";
        $data_where["loadFlag9"]="";
        $data_where["planfee_index"]="1";

        $data_where["prpCplanTemps[0].payNo"]="1";
        $data_where["prpCplanTemps[0].serialNo"]="0";
        $data_where["prpCplanTemps[0].endorseNo"]="";
        $data_where["cplan[0].payReasonC"]="强制收保费";
        $data_where["prpCplanTemps[0].payReason"]="R29";
        $data_where["prpCplanTemps[0].planDate"]=empty($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];
        $data_where["prpCplanTemps[0].currency"]="CNY";
        $data_where["description[0].currency"]="人民币";
        $data_where["prpCplanTemps[0].planFee"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data_where["cplans[0].planFee"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data_where["cplans[0].backPlanFee"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data_where["prpCplanTemps[0].netPremium"]=empty($_SESSION['NetPremium'])?"":$_SESSION['NetPremium'];
        $data_where["prpCplanTemps[0].taxPremium"]=empty($_SESSION['MVTALCI_TAX_PREMIUM'])?"":$_SESSION['MVTALCI_TAX_PREMIUM'];
        $data_where["prpCplanTemps[0].delinquentFee"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data_where["prpCplanTemps[0].flag"]="";
        $data_where["prpCplanTemps[0].subsidyRate"]="0";
        $data_where["prpCplanTemps[0].isBICI"]="CI";


        $data_where["prpCplanTemps[1].payNo"]="1";
        $data_where["prpCplanTemps[1].serialNo"]="0";
        $data_where["prpCplanTemps[1].endorseNo"]="";
        $data_where["cplan[1].payReasonC"]="收保费";
        $data_where["prpCplanTemps[1].payReason"]="R21";
        $data_where["prpCplanTemps[1].planDate"]=empty($business['BUSINESS_START_TIME'])?"":$business['BUSINESS_START_TIME'];
        $data_where["prpCplanTemps[1].currency"]="CNY";
        $data_where["description[1].currency"]="人民币";
        $data_where["prpCplanTemps[1].planFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["cplans[1].planFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["cplans[1].backPlanFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["prpCplanTemps[1].netPremium"]=empty($_SESSION['NET_PREMIUM'])?"":$_SESSION['NET_PREMIUM'];
        $data_where["prpCplanTemps[1].taxPremium"]=empty($_SESSION['TAX'])?"":$_SESSION['TAX'];
        $data_where["prpCplanTemps[1].delinquentFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
        $data_where["prpCplanTemps[1].flag"]="";
        $data_where["prpCplanTemps[1].subsidyRate"]="0";
        $data_where["prpCplanTemps[1].isBICI"]="BI";
        $data_where["planStr"]="";//"1,R21,CNY,3986.42;1,R29,CNY,855.00";
        $data_where["planPayTimes"]="";
        $data_where["prpCplanTemps[2].payNo"]="1";
        $data_where["prpCplanTemps[2].serialNo"]="0";
        $data_where["prpCplanTemps[2].endorseNo"]="";
        $data_where["cplan[2].payReasonC"]="代收车船税";
        $data_where["prpCplanTemps[2].payReason"]="RM9";
        $data_where["prpCplanTemps[2].planDate"]=empty($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];
        $data_where["prpCplanTemps[2].currency"]="CNY";
        $data_where["description[2].currency"]="人民币";
        $data_where["prpCplanTemps[2].planFee"]=$mvtalci['TAX_PREMIUM'];//$mvtalci[];
        $data_where["cplans[2].planFee"]=$mvtalci['TAX_PREMIUM'];
        $data_where["cplans[2].backPlanFee"]=$mvtalci['TAX_PREMIUM'];
        $data_where["prpCplanTemps[2].netPremium"]="";
        $data_where["prpCplanTemps[2].taxPremium"]="";
        $data_where["prpCplanTemps[2].delinquentFee"]=$mvtalci['TAX_PREMIUM'];
        $data_where["prpCplanTemps[2].flag"]="";
        $data_where["prpCplanTemps[2].subsidyRate"]="0";
        $data_where["prpCplanTemps[2].isBICI"]="CShip";
        $data_where["prpAnciInfo.sellExpensesRate"]=$Company_handling['data']['discountRateCIUp'];
        $data_where["prpAnciInfo.sellExpensesAmount"]=$Company_handling['data']['sellExpensesAmount'];
        $data_where["prpAnciInfo.sellExpensesRateCIUp"]=$Company_handling['data']['sellExpensesRateCIUp'];
        $data_where["prpAnciInfo.sellExpensesCIUpAmount"]=$Company_handling['data']['sellExpensesCIUpAmount'];
        $data_where["prpAnciInfo.sellExpensesRateBIUp"]=$Company_handling['data']['operCommRateBIUp'];
        $data_where["prpAnciInfo.sellExpensesBIUpAmount"]=$Company_handling['data']['sellExpensesBIUpAmount'];
        $data_where["prpAnciInfo.operSellExpensesRate"]=$Company_handling['data']['operSellExpensesRate'];
        $data_where["prpAnciInfo.operSellExpensesAmount"]=$Company_handling['data']['operSellExpensesAmount'];
        $data_where["prpAnciInfo.operSellExpensesRateCI"]=$Company_handling['data']['operateCommRateCI'];
        $data_where["prpAnciInfo.operSellExpensesAmountCI"]=$Company_handling['data']['operSellExpensesAmountCI'];
        $data_where["prpAnciInfo.operSellExpensesRateBI"]=$Company_handling['data']['operSellExpensesRateBI'];
        $data_where["prpAnciInfo.operSellExpensesAmountBI"]=$Company_handling['data']['operSellExpensesAmountBI'];
        $data_where["prpAnciInfo.operCommRateCIUp"]=$Company_handling['data']['operCommRateCIUp'];
        $data_where["operCommRateCIUpAmount"]=$Company_handling['data']['operSellExpensesAmountCI'];
        $data_where["prpAnciInfo.operCommRateBIUp"]=$Company_handling['data']['operCommRateBIUp'];
        $data_where["operCommRateBIUpAmount"]=$Company_handling['data']['operSellExpensesAmountBI'];
        $data_where["prpAnciInfo.operCommRate"]=$Company_handling['data']['operCommRate'];
        $data_where["prpAnciInfo.operCommRateAmount"]=$Company_handling['data']['operCommRateAmount'];
        $data_where["prpAnciInfo.operateCommRateCI"]=$Company_handling['data']['operateCommRateCI'];
        $data_where["prpAnciInfo.operateCommCI"]=$Company_handling['data']['operateCommCI'];
        $data_where["prpAnciInfo.operateCommRateBI"]=$Company_handling['data']['operateCommRateBI'];
        $data_where["prpAnciInfo.operateCommBI"]=$Company_handling['data']['operateCommBI'];
        $data_where["prpAnciInfo.discountRateUp"]=$Company_handling['data']['discountRateUp'];
        $data_where["prpAnciInfo.discountRateUpAmount"]=$Company_handling['data']['discountRateUpAmount'];
        $data_where["prpAnciInfo.discountRateCIUp"]=$Company_handling['data']['discountRateCIUp'];
        $data_where["prpAnciInfo.discountRateCIUpAmount"]="";
        $data_where["prpAnciInfo.profitRateBIUp"]=$Company_handling['data']['profitRateBIUp'];
        $data_where["prpAnciInfo.discountRateBIUpAmountp"]=$Company_handling['data']['discountRateBIUpAmount'];
        $data_where["prpAnciInfo.discountRate"]=$Company_handling['data']['discountRate'];
        $data_where["prpAnciInfo.discountRateAmount"]=$Company_handling['data']['discountRateAmount'];
        $data_where["prpAnciInfo.discountRateCI"]=$Company_handling['data']['discountRateCI'];
        $data_where["prpAnciInfo.discountRateCIAmount"]=$Company_handling['data']['discountRateCIAmount'];
        $data_where["prpAnciInfo.discountRateBI"]=$Company_handling['data']['discountRateBI'];
        $data_where["prpAnciInfo.discountRateBIAmount"]=$Company_handling['data']['discountRateBIAmount'];
        $data_where["prpAnciInfo.riskCode"]="DAA";
        $data_where["prpAnciInfo.standPayRate"]="0";
        $data_where["prpAnciInfo.operatePayRate"]="0";
        $data_where["prpAnciInfo.busiStandardBalanRate"]=$Company_handling['data']['busiStandardBalanRate'];
        $data_where["prpAnciInfo.busiBalanRate"]=$Company_handling['data']['busiBalanRate'];
        $data_where["prpAnciInfo.busiRiskRate"]=$Company_handling['data']['busiRiskRate'];
        $data_where["prpAnciInfo.averProfitRate"]=$Company_handling['data']['averProfitRate'];
        $data_where["prpAnciInfo.averageRate"]=$Company_handling['data']['averageRate'];
        $data_where["prpAnciInfo.minNetSumPremiumBI"]=round($Company_handling['data']['minNetSumPremiumBI'],2);
        $data_where["prpAnciInfo.minNetSumPremiumCI"]=round($Company_handling['data']['minNetSumPremiumCI'],2);
        $data_where["prpAnciInfo.baseActBusiType"]="";
        $data_where["prpAnciInfo.baseExpBusiType"]="";
        $data_where["prpAnciInfo.operateProfitRate"]=$Company_handling['data']['operateProfitRate'];
        $data_where["prpAnciInfo.breakEvenValue"]=$Company_handling['data']['breakEvenValue'];
        $data_where["prpAnciInfo.profitRateBIUp"]=$Company_handling['data']['profitRateBIUp'];
        $data_where["prpAnciInfo.proCommRateBIUp"]=$Company_handling['data']['proCommRateBIUp'];
        $data_where["prpAnciInfo.busiTypeCommBIUp"]=$Company_handling['data']['busiTypeCommBIUp'];
        $data_where["prpAnciInfo.busiTypeCommCIUp"]=$Company_handling['data']['busiTypeCommCIUp'];
        $type_of=trim($_SESSION['BUSINESS']['INSURANCES']);
        $standbyField1="";
        $standbyField1= $Company_handling['data']['sellExpensesCIUpAmount'].",";
        $standbyField1.=round($Company_handling['data']['sellExpensesBIUpAmount'],2).",";
        $standbyField1.=round($Company_handling['data']['operSellExpensesRate']+$Company_handling['data']['discountRate'],2).",";
        $standbyField1.=round($Company_handling['data']['operSellExpensesRate']+$Company_handling['data']['discountRate'],2)-$Company_handling['data']['actProCommRate'].",";
        $standbyField1.=",".$Company_handling['data']['standbyField1'].",";

        $standbyField1.=$type_of." ,,,,";
        $data_where["prpAnciInfo.standbyField1"]=$standbyField1;//"34.2,2417.67,48.78,-11.22,,详见总公司盈利监控模型,050100 050202 050501 050602 050711 050712 050451 050930 050931 050932 050933 050934 ,,,,";
        $data_where["switchFlag"]="0";
        $data_where["actProfitRate"]=round($Company_handling['data']['operSellExpensesRate']+$Company_handling['data']['discountRate'],2);//折扣和销售费用实际比率(%)
        $data_where["prpAnciInfo.businessCode"]="";//业务政策代码
        $data_where["prpAnciInfo.minNetSumPremium"]=$Company_handling['data']['minNetSumPremium'];
        $data_where["prpAnciInfo.origBusiType"]=$Company_handling['data']['origBusiType'];
        $data_where["prpAnciInfo.expProCommRateUp"]=$Company_handling['data']['actProCommRate'];
        $data_where["expProCommRateUp_Disc"]=round($Company_handling['data']['operSellExpensesRate']+$Company_handling['data']['discountRate']-$Company_handling['data']['actProCommRate'],2);//差值
        $data_where["prpAnciInfo.expBusiType"]=$Company_handling['data']['expBusiType'];
        $data_where["prpAnciInfo.actProCommRateUp"]="";
        $data_where["actProCommRateUp_Disc"]="";
        $data_where["prpAnciInfo.actBusiType"]=$Company_handling['data']['actBusiType'];
        $data_where["expRiskNote"]="详见总公司盈利监控模型";
        $data_where["kindBusiTypeA"]=$type_of;//$Company_handling['data']['standbyField1'];
        $data_where["kindBusiTypeB"]="";
        $data_where["kindBusiTypeC"]="";//$Company_handling['data']['strKindBusiTypeC'];
        $data_where["kindBusiTypeD"]="";
        $data_where["kindBusiTypeE"]="";
        $data_where["prpCmainCar.flag"]="1";
        $data_where["prpCmainCarFlag"]="1";
        $data_where["coinsSchemeCode"]="";
        $data_where["coinsSchemeName"]="";
        $data_where["mainPolicyNo"]="";
        $data_where["prpCcoinsMains_[0].id.serialNo"]="1";
        $data_where["prpCcoinsMains_[0].coIdentity"]="1";
        $data_where["prpCcoinsMains_[0].coinsCode"]="002";
        $data_where["prpCcoinsMains_[0].coinsName"]="人保财产";
        $data_where["prpCcoinsMains_[0].coinsRate"]="";
        $data_where["prpCcoinsMains_[0].id.currency"]="CNY";
        $data_where["prpCcoinsMains_[0].coinsAmount"]="";
        $data_where["prpCcoinsMains_[0].coinsPremium"]="";
        $data_where["prpCcoinsMains_[0].coinsPremium"]="";
        $data_where["iniPrpCcoins_Flag"]="";
        $data_where["hidden_index_ccoins"]="0";
        $data_where["prpCpayeeAccountBIs_[0].id.proposalNo"]="";
        $data_where["prpCpayeeAccountBIs_[0].id.serialNo"]="";
        $data_where["prpCpayeeAccountBIs_[0].itemNo"]="";
        $data_where["prpCpayeeAccountBIs_[0].payReason"]="";
        $data_where["prpCpayeeAccountBIs_[0].payeeInfoid"]="";
        $data_where["prpCpayeeAccountBIs_[0].accountName"]="";
        $data_where["prpCpayeeAccountBIs_[0].basicBankCode"]="";
        $data_where["prpCpayeeAccountBIs_[0].basicBankName"]="";
        $data_where["prpCpayeeAccountBIs_[0].recBankAreaCode"]="";
        $data_where["prpCpayeeAccountBIs_[0].recBankAreaName"]="";
        $data_where["prpCpayeeAccountBIs_[0].bankCode"]="";
        $data_where["prpCpayeeAccountBIs_[0].bankName"]="";
        $data_where["prpCpayeeAccountBIs_[0].cnaps"]="";
        $data_where["prpCpayeeAccountBIs_[0].accountNo"]="";
        $data_where["prpCpayeeAccountBIs_[0].isPrivate"]="";
        $data_where["prpCpayeeAccountBIs_[0].cardType"]="";
        $data_where["prpCpayeeAccountBIs_[0].paySumFee"]="";
        $data_where["prpCpayeeAccountBIs_[0].payType"]="";
        $data_where["prpCpayeeAccountBIs_[0].intention"]="支付他方保费";
        $data_where["prpCpayeeAccountBIs_[0].sendSms"]="";
        $data_where["prpCpayeeAccountBIs_[0].identifyType"]="";
        $data_where["prpCpayeeAccountBIs_[0].identifyNo"]="";
        $data_where["prpCpayeeAccountBIs_[0].telephone"]="";
        $data_where["prpCpayeeAccountBIs_[0].sendMail"]="";
        $data_where["prpCpayeeAccountBIs_[0].mailAddr"]="";
        $data_where["prpCpayeeAccountCIs_[0].id.proposalNo"]="";
        $data_where["prpCpayeeAccountCIs_[0].id.serialNo"]="";
        $data_where["prpCpayeeAccountCIs_[0].itemNo"]="";
        $data_where["prpCpayeeAccountCIs_[0].payReason"]="";
        $data_where["prpCpayeeAccountCIs_[0].payeeInfoid"]="";
        $data_where["prpCpayeeAccountCIs_[0].accountName"]="";
        $data_where["prpCpayeeAccountCIs_[0].basicBankCode"]="";
        $data_where["prpCpayeeAccountCIs_[0].basicBankName"]="";
        $data_where["prpCpayeeAccountCIs_[0].recBankAreaCode"]="";
        $data_where["prpCpayeeAccountCIs_[0].recBankAreaName"]="";
        $data_where["prpCpayeeAccountCIs_[0].bankCode"]="";
        $data_where["prpCpayeeAccountCIs_[0].bankName"]="";
        $data_where["prpCpayeeAccountCIs_[0].cnaps"]="";
        $data_where["prpCpayeeAccountCIs_[0].accountNo"]="";
        $data_where["prpCpayeeAccountCIs_[0].isPrivate"]="";
        $data_where["prpCpayeeAccountCIs_[0].cardType"]="";
        $data_where["prpCpayeeAccountCIs_[0].paySumFee"]="";
        $data_where["prpCpayeeAccountCIs_[0].payType"]="";
        $data_where["prpCpayeeAccountCIs_[0].intention"]="支付他方保费";
        $data_where["prpCpayeeAccountCIs_[0].sendSms"]="";
        $data_where["prpCpayeeAccountCIs_[0].identifyType"]="";
        $data_where["prpCpayeeAccountCIs_[0].identifyNo"]="";
        $data_where["prpCpayeeAccountCIs_[0].telephone"]="";
        $data_where["prpCpayeeAccountCIs_[0].sendMail"]="";
        $data_where["prpCpayeeAccountCIs_[0].mailAddr"]="";
        $data_where["iReinsCode"]="";
        $data_where["prpCspecialFacs_[0].reinsCode"]="001";
        $data_where["iFReinsCode"]="";
        $data_where["iPayCode"]="";
        $data_where["iShareRate"]="";
        $data_where["iCommRate"]="";
        $data_where["iTaxRate"]="";
        $data_where["iOthRate"]="";
        $data_where["iCommission"]="";
        $data_where["iOthPremium"]="";
        $data_where["prpCspecialFacs_[0].id.reinsNo"]="1";
        $data_where["prpCspecialFacs_[0].freinsCode"]="001";
        $data_where["prpCspecialFacs_[0].payCode"]="001";
        $data_where["prpCspecialFacs_[0].shareRate"]="001";
        $data_where["prpCspecialFacs_[0].sharePremium"]="001";
        $data_where["prpCspecialFacs_[0].commRate"]="001";
        $data_where["prpCspecialFacs_[0].taxRate"]="001";
        $data_where["prpCspecialFacs_[0].tax"]="001";
        $data_where["prpCspecialFacs_[0].othRate"]="001";
        $data_where["prpCspecialFacs_[0].commission"]="001";
        $data_where["prpCspecialFacs_[0].othPremium"]="001";
        $data_where["prpCspecialFacs_[0].reinsName"]="001";
        $data_where["prpCspecialFacs_[0].freinsName"]="001";
        $data_where["prpCspecialFacs_[0].payName"]="001";
        $data_where["prpCspecialFacs_[0].remark"]="001";
        $data_where["prpCspecialFacs_[0].flag"]="";
        $data_where["hidden_index_specialFac"]="0";
        $data_where["updateIndex"]="-1";
        $data_where["iniCspecialFac_Flag"]="";
        $data_where["_ReinsCode"]="";
        $data_where["loadFlag8"]="";
        $data_where["_FReinsCode"]="";
        $data_where["_PayCode"]="";
        $data_where["_ReinsName"]="";
        $data_where["_FReinsName"]="";
        $data_where["_PayName"]="";
        $data_where["_CommRate"]="";
        $data_where["_OthRate"]="";
        $data_where["_ShareRate"]="";
        $data_where["_Commission"]="";
        $data_where["_OthPremium"]="";
        $data_where["_SharePremium"]="";
        $data_where["_TaxRate"]="";
        $data_where["_Tax"]="";
        $data_where["_Remark"]="";
        $data_where["prpCsettlement.buyerUnitRank"]="3";
        $data_where["prpCsettlement.buyerPreFee"]=$_SESSION['COUNT_PREMIUM']+$mvtalci['MVTALCI_PREMIUM'];
        $data_where["prpCsettlement.buyerUnitCode"]="";
        $data_where["prpCsettlement.buyerUnitName"]="";
        $data_where["prpCsettlement.upperUnitCode"]="";
        $data_where["upperUnitName"]="";
        $data_where["prpCsettlement.buyerUnitAddress"]="";
        $data_where["prpCsettlement.buyerLinker"]="";
        $data_where["prpCsettlement.buyerPhone"]="";
        $data_where["prpCsettlement.buyerMobile"]="";
        $data_where["prpCsettlement.buyerFax"]="";
        $data_where["prpCsettlement.buyerUnitNature"]="1";
        $data_where["prpCsettlement.buyerProvince"]="51000000";
        $data_where["buyerProvinceDes"]="人保财险四川省分公司";
        $data_where["prpCsettlement.buyerBusinessSort"]="01";
        $data_where["prpCsettlement.comCname"]="";
        $data_where["prpCsettlement.linkerCode"]="";
        $data_where["linkerName"]="";
        $data_where["linkerPhone"]="";
        $data_where["linkerMobile"]="";
        $data_where["linkerFax"]="";
        $data_where["prpCsettlement.comCode"]="";
        $data_where["prpCsettlement.fundForm"]="1";
        $data_where["prpCsettlement.flag"]="0";
        $data_where["settlement_Flag"]="";
        $data_where["prpCcontriutions_[0].id.serialNo"]="1";
        $data_where["prpCcontriutions_[0].contribType"]="F";
        $data_where["prpCcontriutions_[0].contribCode"]="";
        $data_where["prpCcontriutions_[0].contribName"]="";
        $data_where["prpCcontriutions_[0].contribCode_uni"]="";
        $data_where["prpCcontriutions_[0].contribPercent"]="";
        $data_where["prpCcontriutions_[0].contribPremium"]="";
        $data_where["prpCcontriutions_[0].remark"]="";
        $data_where["hidden_index_ccontriutions"]="0";
        $data_where["iProposalNo"]="";
        $data_where["CProposalNo"]="";
        $data_where["timeFlag"]="";
        $data_where["prpCremarks_[0].id.proposalNo"]="";
        $data_where["prpCremarks_[0].id.serialNo"]="";
        $data_where["prpCremarks_[0].operatorCode"]="";
        $data_where["prpCremarks_[0].remark"]="";
        $data_where["prpCremarks_[0].flag"]="";
        $data_where["prpCremarks_[0].insertTimeForHis"]="";
        $data_where["hidden_index_remark"]="0";
        $data_where["ciInsureDemandCheckVo.demandNo"]="";
        $data_where["ciInsureDemandCheckVo.checkQuestion"]="";
        $data_where["ciInsureDemandCheckVo.checkAnswer"]="";
        $data_where["ciInsureDemandCheckCIVo.demandNo"]="";
        $data_where["ciInsureDemandCheckCIVo.checkQuestion"]="";
        $data_where["ciInsureDemandCheckCIVo.checkAnswer"]="";
        $data_where["ciInsureDemandCheckVo.flag"]="DEMAND";
        $data_where["ciInsureDemandCheckVo.riskCode"]="";
        $data_where["ciInsureDemandCheckCIVo.flag"]="DEMAND";
        $data_where["ciInsureDemandCheckCIVo.riskCode"]="";
        $data_where["flagCheck"]="00";
        if($_SESSION['userCode']!="" && isset($_SESSION['userCode']))
        {
                $data_where["userCode"]=$_SESSION['userCode'];
                $data_where["prpCmain.operatorCode"]=$_SESSION['userCode'];
                $data_where["comCode"]=$_SESSION['comCode'];
                $data_where["prpCmain.comCode"]=$_SESSION['comCode'];  //不能为空
                $data_where["prpCmain.makeCom"]=$_SESSION['comCode'];
                $data_where["prpCmain.handler1Code"]=$_SESSION['handler1Code'];//$_SESSION['handler1Code'];//业务归属人员  不能为空
                $data_where["prpCmain.agentCode"]=$_SESSION['agentCode'];//$agentCode[1][0];//销管合同
                $data_where["agentCode"]=$_SESSION['agentCode'];
                $data_where["prpCmain.handlerCode"]=$_SESSION['handler1Code'];
                $data_where["prpCmain.businessNature"]=$_SESSION['Nature'];
        }
        $arr=array();
        foreach($data_where as $k=>$v)
        {
            $whex= iconv("UTF-8","GBK",$v);
            $arr[$k]=$whex;
        }

        $where_result = self::requestPostData($this->insert_URL,$arr);
        if(strstr($where_result,"errorMessage")=="")
        {
               $Preservation= explode(",",$where_result);
               $result_json=array();
               foreach($Preservation as $kk =>$vv)
               {
                    if(strstr($vv, "TDAA"))
                    {
                        $result_json["TDAA"]=$vv;
                    }
                    
                    if(strstr($vv, "TDZA"))
                    {
                        $result_json["TDZA"]=$vv;
                    }

               }
                    $result_json['state']="0";
                    return json_encode($result_json);
               

        }
        else
        {       
                $this->error['errorMsg']=$where_result;
                $this->error['state']="1";
                return json_encode($this->error);
        }

    }



    /**
     * [trimall 删除字符空格]
     * @AuthorHTL
     * @DateTime  2016-08-04T16:20:36+0800
     * @param     [type]                   $str [传递字符串]
     * @return    [type]                        [description]
     */
    private function trimall($str)//删除空格
    {
        $qian=array(" ","　","\t","\n","\r");
        $hou=array("","","","","");
        return str_replace($qian,$hou,$str);
    }


    /**
     * [checkBefore 查询是否存在历史保单]
     * @AuthorHTL
     * @DateTime  2016-12-15T16:53:59+0800
     * @param     array                    $auto     [传递数组]
     * @param     array                    $business [传递数组]
     * @param     array                    $mvtalci  [传递数组]
     * @return    [type]                             [返回保单号]
     */
    private  function checkBefore($auto=array(),$business=array(),$mvtalci=array())
    {
        
        $data["comCode"]=$_SESSION['comCode'];
        $data["riskCode"]="DAA";
        $data["prpCproposalVo.checkFlag"]="";
        $data["prpCproposalVo.underWriteFlag"]="";
        $data["prpCproposalVo.strStartDate"]="";
        $data["prpCproposalVo.othFlag"]="";
        $data["prpCproposalVo.checkUpCode"]="";
        $data["prpCproposalVo.operatorCode1"]="";
        $data["prpCproposalVo.businessNature"]="";
        $data["noNcheckFlag"]="0";
        $data["jfcdURL"]="http://10.134.138.16:8100/cbc";
        $data["prpallURL"]="http://10.134.138.16:8000/prpall";
        $data["bizNoZ"]="";
        $data["pageNo_"]="1";
        $data["pageSize_"]="10";
        $data["scmIsOpen"]="1111100000";
        $data["searchConditionSwitch"]="0";
        $data["queryinterval"]="01";
        
        $data["prpCproposalVo.policyNo"]="";
        $data["prpCproposalVo.licenseNo"]=!isset($auto['LICENSE_NO'])?"":$auto['LICENSE_NO'];
        $data["prpCproposalVo.vinNo"]=!isset($auto['VIN_NO'])?"":$auto['VIN_NO'];
        $data["prpCproposalVo.insuredCode"]="";
        $data["prpCproposalVo.insuredName"]=!isset($auto['OWNER'])?"":$auto['OWNER'];
        $data["prpCproposalVo.contractNo"]="";
        $data["prpCproposalVo.operateDate"]="";//签单日期
        $data["prpCproposalVo.operateDate2"]="";
        $data["prpCproposalVo.startDate"]="";//起保日期
        $data["prpCproposalVo.startDate2"]="";
        $data["prpCproposalVo.dmFlag"]="all";
        $data["prpCproposalVo.underWriteFlagC"]="";
        $data["prpCproposalVo.brandName"]="";
        $data["prpCproposalVo.engineNo"]="";
        $data["prpCproposalVo.frameNo"]="";
        $data["prpCproposalVo.riskCode"]="DAA,DZA";

        $data["prpCproposalVo.appliCode"]="";
        $data["prpCproposalVo.apliName"]="";
        $data["prpCproposalVo.makeCom"]="";
        $data["makeComDes"]="";
        $data["prpCproposalVo.operatorCode"]="";
        $data["operatorCodeDes"]="";
        $data["prpCproposalVo.comCode"]="";
        $data["comCodeDes"]="";
        $data["prpCproposalVo.handlerCode"]="";
        $data["handlerCodeDes"]="";
        $data["prpCproposalVo.handler1Code"]="";
        $data["handler1CodeDes"]="";
        $data["prpCproposalVo.endDate"]="";
        $data["prpCproposalVo.endDate2"]="";
        $data["prpCproposalVo.underWriteEndDate"]="";
        $data["prpCproposalVo.underWriteEndDate2"]="";
        $where["pageSize"]="10";
        $where["pageNo"]="1";
        if(isset($business['BUSINESS_NUMBER_INSURED']) && $business['BUSINESS_NUMBER_INSURED']!="" && $mvtalci['MVTALCI_NUMBER_INSURED']=="")
        {
                $data["prpCproposalVo.proposalNo"]=$business['BUSINESS_NUMBER_INSURED'];
                $data["prpCproposalVo.riskCode"]="DAA";
        }

        if(isset($mvtalci['MVTALCI_NUMBER_INSURED']) && $mvtalci['MVTALCI_NUMBER_INSURED']!="" && $business['BUSINESS_NUMBER_INSURED']=="")
        {
                $data["prpCproposalVo.proposalNo"]=$mvtalci['MVTALCI_NUMBER_INSURED'];
                $data["prpCproposalVo.riskCode"]="DZA";
        }

        if(isset($mvtalci['MVTALCI_NUMBER_INSURED']) && $mvtalci['MVTALCI_NUMBER_INSURED']!="" && $business['BUSINESS_NUMBER_INSURED']!="")
        {
                $arr_where=array();
                $data["prpCproposalVo.proposalNo"]=$mvtalci['MVTALCI_NUMBER_INSURED'];
                $arr=array();
                foreach($data as $k=>$v)
                {
                    $whex= iconv("UTF-8","GBK",$v);
                    $arr[$k]=$whex;
                }
                $str = self::requestPostData($this->checkBefores_URL."?".http_build_query($where),$arr);
                $result= json_decode($str,true);
                if($result['totalRecords']>0)
                {
                    foreach($result['data'] as $k=>$v)
                    {
                            if(strstr($v['proposalNo'],"TDAA"))
                            {
                                $arrays["TDAA"]=$v['proposalNo'];
                            }
                            if(strstr($v['proposalNo'],"TDZA"))
                            {
                                $arrays["TDZA"]=$v['proposalNo'];
                            }

                    }
                    return $arrays;
                }
                else
                {
                    $this->error['errorMsg']="查询保单失败,请稍后再试";
                    return false;
                }

        }

        $arr=array();
        foreach($data as $k=>$v)
        {
            $whex= iconv("UTF-8","GBK",$v);
            $arr[$k]=$whex;
        }


        $str= self::requestPostData($this->checkBefores_URL."?".http_build_query($where),$arr);
        $result= json_decode($str,true);
        if($result['totalRecords']>0)
        {
            foreach($result['data'] as $k=>$v)
            {
                    if(strstr($v['proposalNo'],"TDAA"))
                    {
                        $arrays["TDAA"]=$v['proposalNo'];
                    }
                    if(strstr($v['proposalNo'],"TDZA"))
                    {
                        $arrays["TDZA"]=$v['proposalNo'];
                    }

            }
            return $arrays;
        }
        else
        {
            $this->error['errorMsg']="查询保单失败,请稍后再试";
            return false;
        } 

    }
    /**
     * [channel 查询渠道机构]
     * @AuthorHTL
     * @DateTime  2016-12-15T14:03:31+0800
     * @return    [type]                   [成功返回true，否则返回false]
     */
    private function channel()
    {
        $channel_result = self::requestGetData($this->channel_Url);//获取渠道参数
        $axp= iconv("GBK", "UTF-8", $channel_result);
        $d='|value="(.*)"|isU';
        preg_match_all($d, $axp, $businessNature);

        if($businessNature[1][1]!="" && $businessNature[1][1]!="")
        {
            $_SESSION['comCode']=$businessNature[1][1];
            $_SESSION['userCode']=$businessNature[1][215];
            $_SESSION['handler1Code']=$businessNature[1][210];
            $_SESSION['agentCode']=$businessNature[1][221];
            $_SESSION['Nature']=$businessNature[1][219];
            return true;
        }
        else
        {
            $this->error['errorMsg']="请确认保险公司账号是否正确";
            return false;
        }


    }

    /**
     * [getAgeBy 通过身份证号码获取年龄]
     * @AuthorHTL
     * @DateTime  2016-12-15T16:57:14+0800
     * @param     string                   $Id_card [传递的身份证号]
     * @return    [type]                            [description]
     */
    private function getAgeBy($Id_card="")
    {

            //过了这年的生日才算多了1周岁
                    if(empty($Id_card) || $Id_card=="") return "";
                    $date=strtotime(substr($Id_card,6,8));
            //获得出生年月日的时间戳
                    $today=strtotime('today');
            //获得今日的时间戳
                    $diff=floor(($today-$date)/86400/365);
            //得到两个日期相差的大体年数
            //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
                    $age=strtotime(substr($Id_card,6,8).' +'.$diff.'years')>$today?($diff+1):$diff;
                    return $age;



    }

    /**
     * [getAgeBy 通过身份证号码获取身份证号]
     * @AuthorHTL
     * @DateTime  2016-12-15T16:57:14+0800
     * @param     string                   $IDCard [传递的身份证号]
     * @return    [type]                            [description]
     */
	 private function getIDCardInfo($IDCard,$format=1)
	 { 
	 // $result['error']=0;//0：未知错误，1：身份证格式错误，2：无错误 
	 // $result['flag']='';//0标示成年，1标示未成年 
	 // $result['tdate']='';//生日，格式如：2012-11-15 
	 if(!preg_match("/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/",$IDCard)){ 
	  $result->error['errorMsg']="身份证格式错误"; 
	  return $result; 
	 }else{ 
	  if(strlen($IDCard)==18)
	  { 
	   $tyear=intval(substr($IDCard,6,4)); 
	   $tmonth=intval(substr($IDCard,10,2)); 
	   $tday=intval(substr($IDCard,12,2)); 
	  }
	  elseif(strlen($IDCard)==15)
	  { 
	   $tyear=intval("19".substr($IDCard,6,2)); 
	   $tmonth=intval(substr($IDCard,8,2)); 
	   $tday=intval(substr($IDCard,10,2)); 
	  }
	    
	  if($tyear>date("Y")||$tyear<(date("Y")-100))
	  { 
	    $flag=0; 
	   }
	   elseif($tmonth<0||$tmonth>12)
	   { 
	    $flag=0; 
	   }
	   elseif($tday<0||$tday>31)
	   { 
	    $flag=0; 
	   }else
	   { 
	    if($format)
	    {
	     $tdate=$tyear."-".$tmonth."-".$tday; 
	    }
	    else
	    {
	     $tdate=$tmonth."-".$tday; 
	    }
	      
	    if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60)
	    { 
	     $flag=0; 
	    }
	    else
	    { 
	     $flag=1; 
	    } 
	   }  
	 } 
	 $result['error']=2;//0：未知错误，1：身份证格式错误，2：无错误 
	 $result['isAdult']=$flag;//0标示成年，1标示未成年 
	 $result['birthday']=$tdate;//生日日期 
	 return $result; 
	}
    /**
     * [idcard 身份信息查询]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:27:38+0800
     * @param     array                    $auto     [传递数组]
     * @param     array                    $business [传递数组]
     * @param     array                    $mvtalci  [传递数组]
     * @return    [type]                             [成功返回数组，否则返回false]
     */
    private function idcard($auto=array(),$business=array(),$mvtalci=array())
    {

       
        if($auto['IDENTIFY_NO']!="" )
        {
                $isIDCard2="/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/"; 
                preg_match($isIDCard2,$auto['IDENTIFY_NO'], $matches);
                if(empty($matches))
                {
                    return 2;
                }

                $card_where['_identifyType']='01';
                $card_where['_insuredName']='';
                $card_where['_insuredCode']='';
                $card_where['_identifyNumber']=$auto['IDENTIFY_NO'];
                $array = self::requestGetData($this->IdCardUrl."?".http_build_query($card_where));
                $result =json_decode($array,true);
                if(isset($result['data'][0]['insuredCode']) && $result['data'][0]['insuredCode']!="")
                {
                    return $result;
                }
                else
                {
                    $token_where['customerCName']="";
                    $token_where['identifyType']="01";
                    $token_where['identifyNumber']=$auto['IDENTIFY_NO'];
                    $token_where['syscode']="prpall";
                    $token_result=self::requestGetData($this->Token_url."?".http_build_query($token_where));
                    $token_get='/<input type="hidden" name="token" value="(.*)"\/>/';
                    preg_match_all($token_get, $token_result, $matches);//token值
                    $id_identify= self::getIDCardInfo($auto['IDENTIFY_NO']);
                    $data["struts.token.name"]="token";
                    $data["token"]=$matches[1][0];
                    $data["syscode"]="prpall";
                    $data["checkFlag"]="";
                    $data["prpDcustomerPerson.validStatus"]="1";
                    $data["prpDcustomerPerson.customerCode"]="";
                    $data["prpDcustomerPerson.versionNo"]="0";
                    $data["prpDcustomerPerson.customerCName"]=$auto['OWNER'];
                    $data["prpDcustomerPerson.customerFullEName"]="";
                    $data["prpDcustomerPerson.identifyType"]="01";
                    $data["prpDcustomerPerson.identifyNumber"]=$auto['IDENTIFY_NO'];
                    $data["prpDcustomerPerson.gender"]="1";
                    $data["prpDcustomerPerson.birthDate"]=$id_identify['birthday'];
                    $data["prpDcustomerPerson.nationality"]="CHN";
                    $data["prpDcustomerPerson.dateValid"]="2025-12-16";
                    $data["prpDcustomerPerson.resident"]="A";
                    $data["prpDcustomerRisk.creditLevel"]="3";
                    $data["prpDcustomerPerson.marriage"]="";
                    $data["prpDcustomerPerson.favourite"]="";
                    $data["prpDcustomerPerson.educationCode"]="";
                    $data["prpDcustomerPerson.health"]="";
                    $data["prpDcustomerPerson.stature"]="";
                    $data["prpDcustomerPerson.weight"]="";
                    $data["prpDcustomerPerson.bloodType"]="";
                    $data["prpDcustomerPerson.deathDate"]="";
                    $data["prpDcustomerPerson.customerKind"]="";
                    $data["prpDcustomerPerson.auditType"]="新增";
                    $data["prpDcustomerPerson.submitDescription"]="";
                    $data["PhonePersonNo"]="1";
                    $data["phoneNumber1"]="136****2299";
                    $data["phoneType2"]="1";
                    $data["bondingDate"]=date("Y-m-d",time());
                    $data["bondingReason"]="";
                    $data["bondingPerson"]="陈燕";
                    $data["bondingOrganization"]=$_SESSION['comCode'];
                    $data["bondingApply"]="moren";
                    $data["removebondingApply"]="";
                    $data["bondingFlag"]="1";
                    $data["phoneType"]="1";
                    $data["phoneNumber"]=$auto['MOBILE'];//$business['DESIGNATED_DRIVER'][0]['IPONE'];
                    $data["phoneproperties"]="01";
                    $data["customerRelations"]="";
                    $data["bestRelationDT"]="";
                    $data["phonevalidstatus"]="1";
                    $data["isDefaultView"]="on";
                    $data["isDefault"]="1";
                    $data["AddressPersonNo"]="1";
                    $data["adresstype"]="1";
                    $data["province"]="510000";
                    $data["city"]="510100";
                    $data["area"]="510112";
                    $data["addresscname"]='';//地址
                    $data["addressename"]='';
                    $data["postcode"]="";
                    $data["addressvalidstatus"]="1";
                    $data["isAddressDefaultView"]="on";
                    $data["isAddressDefault"]="1";
                    $data["prpDcustomerPerson.email"]="";
                    $data["prpDcustomerPerson.imType"]="";
                    $data["prpDcustomerPerson.imNo"]="";
                    $data["prpDcustomerPerson.weiChat"]="";
                    $data["prpDcustomerPerson.qq"]="";
                    $data["prpDcustomerPerson.selfMonthIncome"]="";
                    $data["prpDcustomerPerson.selfMonthIncomeCurrency"]="CNY";
                    $data["prpDcustomerPerson.familyMonthIncome"]="";
                    $data["prpDcustomerPerson.familyMonthIncomeCurrency"]="CNY";
                    $data["FamliyPersonNo"]="";
                    $data["relationType1"]="";
                    $data["name1"]="";
                    $data["birthDate1"]="";
                    $data["identifyNumber1"]="";
                    $data["1unit"]="";
                    $data["duty1"]="";
                    $data["phoneType1"]="";
                    $data["phone1"]="";
                    $data["AccountPersonNo"]="";
                    $data["bank"]="";
                    $data["bankView"]="";
                    $data["branchBank"]="";
                    $data["account"]="";
                    $data["accountType"]="";
                    $data["accountvaildstatus"]="1";
                    $data["mainAccountValid"]="";
                    $data["UnitPersonNo"]="";
                    $data["unitType"]="";
                    $data["unit"]="";
                    $data["unitAddress"]="";
                    $data["occupationCode"]="";
                    $data["occupationCodeView"]="";
                    $data["dutyLevel"]="";
                    $data["dutyStatus"]="";
                    $data["prpDcustomerPerson.agriFlag"]="0";
                    $data["prpDcstprf.comCitycode"]="";
                    $data["prpDcstprf.comCountycode"]="";
                    $data["prpDcstprf.salsDepartcode"]="";
                    $data["prpDcstprf.serStationcode"]="";
                    $data["prpDcstprf.serPointcode"]="";
                    $data["prpDcstprf.famliyMembersno"]="";
                    $data["prpDcstprf.ageLt18no"]="";
                    $data["prpDcstprf.ageGt65no"]="";
                    $data["prpDcstprf.cultivatedLand"]="";
                    $data["prpDcstprf.workerQuantity"]="";
                    $data["prpDcstprf.woodLand"]="";
                    $data["prpDcstprf.pigNum"]="";
                    $data["prpDcstprf.cattleNum"]="";
                    $data["prpDcstprf.sheepNum"]="";
                    $data["prpDcstprf.otherNum"]="";
                    $data["prpDcstprf.bankNo"]="";
                    $data["prpDcstprf.accountName"]="";
                    $data["prpDcstprf.bankName"]="";
                    $data["prpDcstprf.householdIncome"]="";
                    $data["prpDcstprf.houseNum"]="";
                    $data["prpDcstprf.buildStructure"]="";
                    $data["prpDcstprf.liaisonmanCode"]="";
                    $data["prpDcstprf.describes"]="";
                    $data["CarPersonNo"]="";
                    $data["licenseNo"]="";
                    $data["vehicleName"]="";
                    $data["engineNo"]="";
                    $data["vinNo"]="";
                    $data["enrollDate"]="";
                    $data["policyEndDate"]="";
                    $data["insureCompany"]="";
                    $arr=array();
                    foreach($data as $k=>$v)
                    {
                        $whex= iconv("UTF-8","GBK",$v);
                        $arr[$k]=$whex;
                    }

                    self::requestPostData($this->Add_IdCardUrl,$arr);
                    $add = self::requestGetData($this->IdCardUrl."?".http_build_query($card_where));
                    $add_idcard =json_decode($add,true);
                    return $add_idcard;
                }

        }
        else
        {
                    return false;
        }



    }


    /**
     * [get_items 通过分配险种，返回具体参数]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:20:11+0800
     * @param     [type]                   $business [传递数组]
     * @return    [type]                             [description]
     */
    private  function get_items($business){
        $data= self::check_code();//配置渠道机构//MVTALCI_PREMIUM
        if(is_array($data))
        {
            if(!empty($business['POLICY']))
            {
                foreach($business['POLICY']['BUSINESS_ITEMS'] as $u=>$value)
                {
                    if($value=="")
                    {
                        $this->error['errorMsg']="请重新计算保费，然后在提交核保";
                        return false;
                    }

                    switch ($u) {

                        case 'TVDI':
                            $data["prpCitemKindsTemp[0].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[0].premium"]=$value;
                            $data["prpCitemKindsTemp[0].benchMarkPremium"]=$_SESSION['INSURANCE']['TVDI']['STANDARD_PREMIUM']; //isset($_SESSION['DISCOUNT'])?round($value/$_SESSION['DISCOUNT'],2):"";
                            $data["prpCitemKindsTemp[0].min"]="";
                            $data["prpCitemKindsTemp[0].max"]="";
                            $data["prpCitemKindsTemp[0].itemKindNo"]="";
                            $data["prpCitemKindsTemp[0].clauseCode"]="050051";
                            $data["prpCitemKindsTemp[0].kindCode"]="050202";
                            $data["prpCitemKindsTemp[0].kindName"]="机动车损失保险";
                            $data["prpCitemKindsTemp[0].deductible"]="";//$business['POLICY']['DOC_AMOUNT'];//可选免赔额
                            $data["prpCitemKindsTemp[0].deductibleRate"]="0";
                            $data["prpCitemKindsTemp[0].pureRiskPremium"]=$_SESSION['INSURANCE']['TVDI']['PURERISK_PREMIUM'];/**增加纯风险保费**/
                            $data["prpCitemKindsTemp[0].amount"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT']; //保额/限额
                            $data["prpCitemKindsTemp[0].calculateFlag"]="Y11Y000";
                            $data["prpCitemKindsTemp[0].startDate"]="";
                            $data["prpCitemKindsTemp[0].startHour"]="";
                            $data["prpCitemKindsTemp[0].endDate"]="";
                            $data["prpCitemKindsTemp[0].endHour"]="";
                            $data["relateSpecial[0]"]="050930";
                            $data["prpCitemKindsTemp[0].flag"]="100000";
                            $data["prpCitemKindsTemp[0].basePremium"]="0";
                            $data["prpCitemKindsTemp[0].riskPremium"]="0";
                            $data["prpCitemKindsTemp[0].rate"]="";//费率(%)
                            $data["prpCitemKindsTemp[0].disCount"]=""; //折扣
                            break;
                        case 'TTBLI':
                            $data["prpCitemKindsTemp[2].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[2].premium"]=$value;
                            $data["prpCitemKindsTemp[2].benchMarkPremium"]=$_SESSION['INSURANCE']['TTBLI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[2].min"]="";
                            $data["prpCitemKindsTemp[2].max"]="";
                            $data["prpCitemKindsTemp[2].itemKindNo"]="";
                            $data["prpCitemKindsTemp[2].clauseCode"]="050052";
                            $data["prpCitemKindsTemp[2].kindCode"]="050602";
                            $data["prpCitemKindsTemp[2].kindName"]="第三者责任保险";
                            $data["prpCitemKindsTemp[2].unitAmount"]="";
                            $data["prpCitemKindsTemp[2].quantity"]="";
                            if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="5")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="50000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="10")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="100000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="15")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="150000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="20")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="200000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="30")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="300000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="50")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="500000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="100")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="1000000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="150")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="1500000";
                            }
                            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="200")
                            {
                                $data["prpCitemKindsTemp[2].amount"]="2000000";
                            }
                            $data["prpCitemKindsTemp[2].calculateFlag"]="Y21Y000";
                            $data["prpCitemKindsTemp[2].startDate"]="";
                            $data["prpCitemKindsTemp[2].startHour"]="";
                            $data["prpCitemKindsTemp[2].endDate"]="";
                            $data["prpCitemKindsTemp[2].endHour"]="";
                            $data["relateSpecial[2]"]="050931";
                            $data["prpCitemKindsTemp[2].flag"]=" 100000";
                            $data["prpCitemKindsTemp[2].basePremium"]="";
                            $data["prpCitemKindsTemp[2].riskPremium"]="";
                            $data["prpCitemKindsTemp[2].rate"]="";
                            $data["prpCitemKindsTemp[2].disCount"]="";
                            break;
                        case 'TWCDMVI':
                            $data["prpCitemKindsTemp[1].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[1].premium"]=$value;
                            $data["prpCitemKindsTemp[1].benchMarkPremium"]=$_SESSION['INSURANCE']['TWCDMVI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[1].min"]="";
                            $data["prpCitemKindsTemp[1].max"]="";
                            $data["prpCitemKindsTemp[1].itemKindNo"]="";
                            $data["prpCitemKindsTemp[1].clauseCode"]="050054";
                            $data["prpCitemKindsTemp[1].kindCode"]="050501";
                            $data["prpCitemKindsTemp[1].kindName"]="盗抢险";
                            $data["prpCitemKindsTemp[1].unitAmount"]="";
                            $data["prpCitemKindsTemp[1].quantity"]="";
                            $data["prpCitemKindsTemp[1].amount"]=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[1].calculateFlag"]="N11Y000";
                            $data["prpCitemKindsTemp[1].startDate"]="";
                            $data["prpCitemKindsTemp[1].startHour"]="";
                            $data["prpCitemKindsTemp[1].endDate"]="";
                            $data["prpCitemKindsTemp[1].endHour"]="";
                            $data["relateSpecial[1]"]="050932";
                            $data["prpCitemKindsTemp[1].flag"]=" 100000";
                            $data["prpCitemKindsTemp[1].basePremium"]="";
                            $data["prpCitemKindsTemp[1].riskPremium"]="";
                            $data["prpCitemKindsTemp[1].rate"]="";
                            $data["prpCitemKindsTemp[1].disCount"]="";
                            break;
                        case 'TCPLI_DRIVER':
                            $data["prpCitemKindsTemp[3].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[3].premium"]=$value;
                            $data["prpCitemKindsTemp[3].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[3].min"]="";
                            $data["prpCitemKindsTemp[3].max"]="";
                            $data["prpCitemKindsTemp[3].itemKindNo"]="";
                            $data["prpCitemKindsTemp[3].clauseCode"]="050053";
                            $data["prpCitemKindsTemp[3].kindCode"]="050711";
                            $data["prpCitemKindsTemp[3].kindName"]="车上人员责任险（司机）";
                            $data["prpCitemKindsTemp[3].unitAmount"]="";
                            $data["prpCitemKindsTemp[3].quantity"]="";
                            $data["prpCitemKindsTemp[3].amount"]=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
                            $data["prpCitemKindsTemp[3].calculateFlag"]="Y21Y00";
                            $data["prpCitemKindsTemp[3].startDate"]="";
                            $data["prpCitemKindsTemp[3].startHour"]="";
                            $data["prpCitemKindsTemp[3].endDate"]="";
                            $data["prpCitemKindsTemp[3].endHour"]="";
                            $data["relateSpecial[3]"]="050933";
                            $data["prpCitemKindsTemp[3].flag"]="";
                            $data["prpCitemKindsTemp[3].basePremium"]="";
                            $data["prpCitemKindsTemp[3].riskPremium"]="";
                            $data["prpCitemKindsTemp[3].rate"]="";
                            $data["prpCitemKindsTemp[3].disCount"]="";
                            break;
                        case 'TCPLI_PASSENGER':
                            $data["prpCitemKindsTemp[4].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[4].premium"]=$value;
                            $data["prpCitemKindsTemp[4].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[4].min"]="";
                            $data["prpCitemKindsTemp[4].max"]="";
                            $data["prpCitemKindsTemp[4].itemKindNo"]="";
                            $data["prpCitemKindsTemp[4].clauseCode"]="050053";
                            $data["prpCitemKindsTemp[4].kindCode"]="050712";
                            $data["prpCitemKindsTemp[4].kindName"]="车上人员责任险（乘客）";
                            $data["prpCitemKindsTemp[4].unitAmount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']; //每位乘客保额
                            $data["prpCitemKindsTemp[4].quantity"]=$business['POLICY']['TCPLI_PASSENGER_COUNT'];//共几座
                            $data["prpCitemKindsTemp[4].amount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']*$business['POLICY']['TCPLI_PASSENGER_COUNT'];//保额/限额
                            $data["prpCitemKindsTemp[4].calculateFlag"]="Y21Y00";
                            $data["prpCitemKindsTemp[4].startDate"]="";
                            $data["prpCitemKindsTemp[4].startHour"]="";
                            $data["prpCitemKindsTemp[4].endDate"]="";
                            $data["prpCitemKindsTemp[4].endHour"]="";
                            $data["relateSpecial[4]"]="050934";
                            $data["prpCitemKindsTemp[4].flag"]="";
                            $data["prpCitemKindsTemp[4].basePremium"]="";
                            $data["prpCitemKindsTemp[4].riskPremium"]="";
                            $data["prpCitemKindsTemp[4].rate"]="";
                            $data["prpCitemKindsTemp[4].disCount"]="";
                            break;
                        case 'BSDI':
                            $data["prpCitemKindsTemp[5].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[5].premium"]=$value;
                            $data["prpCitemKindsTemp[5].benchMarkPremium"]=$_SESSION['INSURANCE']['BSDI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[5].min"]="";
                            $data["prpCitemKindsTemp[5].max"]="";
                            $data["prpCitemKindsTemp[5].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[5].clauseCode"]="050059";
                            $data["prpCitemKindsTemp[5].kindCode"]="050211";
                            $data["relateSpecial[5]"]="050937";
                            $data["prpCitemKindsTemp[5].kindName"]="车身划痕损失险";
                            $data["prpCitemKindsTemp[5].amount"]=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[5].calculateFlag"]="N12Y000";
                            $data["prpCitemKindsTemp[5].startDate"]="";
                            $data["prpCitemKindsTemp[5].startHour"]="";
                            $data["prpCitemKindsTemp[5].endDate"]="";
                            $data["prpCitemKindsTemp[5].endHour"]="";
                            $data["prpCitemKindsTemp[5].flag"]=" 200000";
                            $data["prpCitemKindsTemp[5].basePremium"]="";
                            $data["prpCitemKindsTemp[5].riskPremium"]="";
                            $data["prpCitemKindsTemp[5].rate"]="";
                            $data["prpCitemKindsTemp[5].disCount"]="";
                            break;
                        case 'BGAI':
                            $data["prpCitemKindsTemp[6].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[6].premium"]=$value;
                            $data["prpCitemKindsTemp[6].benchMarkPremium"]=$_SESSION['INSURANCE']['BGAI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[6].min"]="";
                            $data["prpCitemKindsTemp[6].max"]="";
                            $data["prpCitemKindsTemp[6].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[6].clauseCode"]="050056";
                            $data["prpCitemKindsTemp[6].kindCode"]="050232";
                            $data["relateSpecial[6]"]="";
                            $data["prpCitemKindsTemp[6].kindName"]="玻璃单独破碎险";
                            $data["prpCitemKindsTemp[6].modeCode"]="10";
                            $data["prpCitemKindsTemp[6].amount"]="";
                            $data["prpCitemKindsTemp[6].calculateFlag"]="N32N000";
                            $data["prpCitemKindsTemp[6].startDate"]="";
                            $data["prpCitemKindsTemp[6].startHour"]="";
                            $data["prpCitemKindsTemp[6].endDate"]="";
                            $data["prpCitemKindsTemp[6].endHour"]="";
                            $data["prpCitemKindsTemp[6].flag"]="200000";
                            $data["prpCitemKindsTemp[6].basePremium"]="0";
                            $data["prpCitemKindsTemp[6].riskPremium"]="0";
                            $data["prpCitemKindsTemp[6].rate"]="0.1235";
                            $data["prpCitemKindsTemp[6].disCount"]="";
                            break;
                        case 'STSFS':
                            $data["prpCitemKindsTemp[7].premium"]=$value;
                            $data["prpCitemKindsTemp[7].benchMarkPremium"]=$_SESSION['INSURANCE']['STSFS']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[7].min"]="";
                            $data["prpCitemKindsTemp[7].max"]="";
                            $data["prpCitemKindsTemp[7].itemKindNo"]="8";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[7].clauseCode"]="050065";
                            $data["prpCitemKindsTemp[7].kindCode"]="050253";
                            $data["relateSpecial[7]"]="";
                            $data["prpCitemKindsTemp[7].kindName"]="指定修理厂险";
                            $data["prpCitemKindsTemp[7].amount"]="0.00";
                            $data["prpCitemKindsTemp[7].calculateFlag"]="N";
                            $data["prpCitemKindsTemp[7].startDate"]=$business['BUSINESS_START_TIME'];
                            $data["prpCitemKindsTemp[7].startHour"]="0";
                            $data["prpCitemKindsTemp[7].endDate"]=$business['BUSINESS_END_TIME'];
                            $data["prpCitemKindsTemp[7].endHour"]="24";
                            $data["prpCitemKindsTemp[7].flag"]="2000000";
                            $data["prpCitemKindsTemp[7].basePremium"]="0.00";
                            $data["prpCitemKindsTemp[7].riskPremium"]="0.00";
                            $data["prpCitemKindsTemp[7].rate"]="10.0000";
                            $data["prpCitemKindsTemp[7].disCount"]=$_SESSION['DISCOUNT'];
                            $data["prpCitemKindsTemp[7].taxRate"]="6.00000";
                            $data["prpCitemKindsTemp[7].dutyFlag"]="2";
                            break;
                        case 'VWTLI':
                            $data["prpCitemKindsTemp[9].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[9].premium"]=$value;
                            $data["prpCitemKindsTemp[9].benchMarkPremium"]=$_SESSION['INSURANCE']['VWTLI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[9].min"]="";
                            $data["prpCitemKindsTemp[9].max"]="";
                            $data["prpCitemKindsTemp[9].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[9].clauseCode"]="050060";
                            $data["prpCitemKindsTemp[9].kindCode"]="050461";
                            $data["relateSpecial[9]"]="050938";
                            $data["prpCitemKindsTemp[9].kindName"]="发动机涉水损失险";
                            $data["prpCitemKindsTemp[9].amount"]="";
                            $data["prpCitemKindsTemp[9].calculateFlag"]="N32Y000";
                            $data["prpCitemKindsTemp[9].startDate"]="";
                            $data["prpCitemKindsTemp[9].startHour"]="";
                            $data["prpCitemKindsTemp[9].endDate"]="";
                            $data["prpCitemKindsTemp[9].endHour"]="";
                            $data["prpCitemKindsTemp[9].flag"]="200000";
                            $data["prpCitemKindsTemp[9].basePremium"]="";
                            $data["prpCitemKindsTemp[9].riskPremium"]="";
                            $data["prpCitemKindsTemp[9].rate"]="";
                            $data["prpCitemKindsTemp[9].disCount"]="";
                            break;
                        case 'NIELI':
                            $data["prpCitemKindsTemp[25].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[25].premium"]=$value;
                            $data["prpCitemKindsTemp[25].benchMarkPremium"]=$_SESSION['INSURANCE']['NIELI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[25].itemKindNo"]="";
                            $data["prpCitemKindsTemp[25].startDate"]="";
                            $data["prpCitemKindsTemp[25].kindCode"]="050261";
                            $data["prpCitemKindsTemp[25].kindName"]="新增设备损失险";
                            $data["prpCitemKindsTemp[25].startHour"]="";
                            $data["prpCitemKindsTemp[25].endDate"]="";
                            $data["prpCitemKindsTemp[25].endHour"]="";
                            $data["prpCitemKindsTemp[25].calculateFlag"]="N12Y000";
                            $data["relateSpecial[25]"]="050936";
                            $data["prpCitemKindsTemp[25].clauseCode"]="050058";
                            $data["prpCitemKindsTemp[25].flag"]="200000";
                            $data["prpCitemKindsTemp[25].basePremium"]="";
                            $data["prpCitemKindsTemp[25].riskPremium"]="";
                            $data["prpCitemKindsTemp[25].amount"]=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[25].rate"]="";
                            $data["prpCitemKindsTemp[25].disCount"]="";
                            $data["subKindCode[25]"]="050261";
                            $data["subKindName[25]"]="新增设备损失险";
                            $data["relateSpecial[25]"]="050936";
                            if(!empty($business['POLICY']['NIELI_DEVICE_LIST']) && $business['POLICY']['NIELI_DEVICE_LIST']!="")
                            {
                                $devcount= json_decode(urldecode($business['POLICY']['NIELI_DEVICE_LIST']),true);
                                $count = count($devcount);
                                for($idx = 0; $idx<$count; $idx++)
                                {

                                $data["prpCcarDevices[$idx].deviceName"]= $devcount[$idx]['NAME'];
                                $data["prpCcarDevices[$idx].id.itemNo"]=$idx+1;
                                $data["prpCcarDevices[$idx].id.proposalNo"]="";
                                $data["prpCcarDevices[$idx].id.serialNo"]=$idx+1;
                                $data["prpCcarDevices[$idx].flag"]="";
                                $data["prpCcarDevices[$idx].quantity"]= $devcount[$idx]['COUNT'];
                                $data["prpCcarDevices[$idx].purchasePrice"]=$devcount[$idx]['BUYING_PRICE'];
                                $data["prpCcarDevices[$idx].buyDate"]=$devcount[$idx]['BUYING_DATE'];
                                $data["prpCcarDevices[$idx].actualValue"]="";
                                }
                            }
                            break;
                        case 'SLOI':
                            $data["prpCitemKindsTemp[8].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[8].premium"]=$value;
                            $data["prpCitemKindsTemp[8].benchMarkPremium"]=$_SESSION['INSURANCE']['SLOI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[8].min"]="";
                            $data["prpCitemKindsTemp[8].max"]="";
                            $data["prpCitemKindsTemp[8].itemKindNo"]="";
                            $data["kindcodesub"]="";
                            $data["prpCitemKindsTemp[8].clauseCode"]="050057";
                            $data["prpCitemKindsTemp[8].kindCode"]="050311";
                            $data["relateSpecial[8]"]="050935";
                            $data["prpCitemKindsTemp[8].kindName"]="自燃损失险";
                            $data["prpCitemKindsTemp[8].amount"]=$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[8].calculateFlag"]="N12Y000";
                            $data["prpCitemKindsTemp[8].startDate"]="";
                            $data["prpCitemKindsTemp[8].startHour"]="";
                            $data["prpCitemKindsTemp[8].endDate"]="";
                            $data["prpCitemKindsTemp[8].endHour"]="";
                            $data["prpCitemKindsTemp[8].flag"]="200000";
                            $data["prpCitemKindsTemp[8].basePremium"]="";
                            $data["prpCitemKindsTemp[8].riskPremium"]="";
                            $data["prpCitemKindsTemp[8].rate"]="";
                            $data["prpCitemKindsTemp[8].disCount"]="";
                            break;
                        case 'RDCCI':
                            $data["prpCitemKindsTemp[10].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[10].premium"]=$value;
                            $data["prpCitemKindsTemp[10].benchMarkPremium"]=$_SESSION['INSURANCE']['RDCCI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[10].itemKindNo"]="";
                            $data["prpCitemKindsTemp[10].startDate"]="";
                            $data["prpCitemKindsTemp[10].kindCode"]="050441";
                            $data["prpCitemKindsTemp[10].kindName"]="修理期间费用补偿险";
                            $data["prpCitemKindsTemp[10].unitAmount"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
                            $data["prpCitemKindsTemp[10].quantity"]=$business['POLICY']['RDCCI_INSURANCE_UNIT'];
                            $data["prpCitemKindsTemp[10].startHour"]="";
                            $data["prpCitemKindsTemp[10].endDate"]="";
                            $data["prpCitemKindsTemp[10].endHour"]="";
                            $data["prpCitemKindsTemp[10].calculateFlag"]="N12N000";
                            $data["relateSpecial[10]"]="";
                            $data["prpCitemKindsTemp[10].clauseCode"]="050061";
                            $data["prpCitemKindsTemp[10].flag"]="200000";
                            $data["prpCitemKindsTemp[10].basePremium"]="0";
                            $data["prpCitemKindsTemp[10].riskPremium"]="0";
                            $data["prpCitemKindsTemp[10].amount"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY']*$business['POLICY']['RDCCI_INSURANCE_UNIT'];
                            $data["prpCitemKindsTemp[10].rate"]="";
                            $data["prpCitemKindsTemp[10].disCount"]="";
                            break;
                        case 'MVLINFTPSI':
                            $data["prpCitemKindsTemp[19].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[19].premium"]=$value;
                            $data["prpCitemKindsTemp[19].benchMarkPremium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[19].itemKindNo"]="";
                            $data["prpCitemKindsTemp[19].startDate"]="";
                            $data["prpCitemKindsTemp[19].kindCode"]="050451";
                            $data["prpCitemKindsTemp[19].kindName"]="机动车损失保险无法找到第三方特约险";
                            $data["prpCitemKindsTemp[19].startHour"]="";
                            $data["prpCitemKindsTemp[19].endDate"]="";
                            $data["prpCitemKindsTemp[19].endHour"]="";
                            $data["prpCitemKindsTemp[19].calculateFlag"]="N32N000";
                            $data["relateSpecial[19]"]="";
                            $data["prpCitemKindsTemp[19].clauseCode"]="050064";
                            $data["prpCitemKindsTemp[19].flag"]="200000";
                            $data["prpCitemKindsTemp[19].basePremium"]="";
                            $data["prpCitemKindsTemp[19].riskPremium"]="";
                            $data["prpCitemKindsTemp[19].amount"]="";
                            $data["prpCitemKindsTemp[19].rate"]="";
                            $data["prpCitemKindsTemp[19].disCount"]="";
                            break;
                        case 'LIDI':
                            $data["prpCitemKindsTemp[20].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[20].premium"]=$value;
                            $data["prpCitemKindsTemp[20].itemKindNo"]="";
                            $data["prpCitemKindsTemp[20].startDate"]="";
                            $data["prpCitemKindsTemp[20].kindCode"]="050643";
                            $data["prpCitemKindsTemp[20].kindName"]="精神损害抚慰金责任险";
                            $data["prpCitemKindsTemp[20].startHour"]="";
                            $data["prpCitemKindsTemp[20].endDate"]="";
                            $data["prpCitemKindsTemp[20].endHour"]="";
                            $data["prpCitemKindsTemp[20].calculateFlag"]="N12Y000";
                            $data["relateSpecial[20]"]="050917";
                            $data["prpCitemKindsTemp[20].clauseCode"]="050063";
                            $data["prpCitemKindsTemp[20].flag"]="200000";
                            $data["prpCitemKindsTemp[20].basePremium"]="";
                            $data["prpCitemKindsTemp[20].riskPremium"]="";
                            $data["prpCitemKindsTemp[20].amount"]="";//$business['POLICY']['LIABILITY_INSURANCE_AMOUNT'];
                            $data["prpCitemKindsTemp[20].rate"]="";
                            $data["prpCitemKindsTemp[20].disCount"]="";
                            break;
                        case 'TVDI_NDSI':
                            $data["prpCitemKindsTemp[13].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[0].specialFlag"]="on";
                            $data["prpCitemKindsTemp[13].premium"]=$value;
                            $data["prpCitemKindsTemp[13].benchMarkPremium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[13].itemKindNo"]="";
                            $data["prpCitemKindsTemp[13].startDate"]="";
                            $data["prpCitemKindsTemp[13].kindCode"]="050930";
                            $data["prpCitemKindsTemp[13].kindName"]="不计免赔险（车损险）";
                            $data["prpCitemKindsTemp[13].startHour"]="";
                            $data["prpCitemKindsTemp[13].endDate"]="";
                            $data["prpCitemKindsTemp[13].endHour"]="";
                            $data["prpCitemKindsTemp[13].calculateFlag"]="N33N000";
                            $data["relateSpecial[13]"]="";
                            $data["prpCitemKindsTemp[13].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[13].flag"]=" 200000";
                            $data["prpCitemKindsTemp[13].basePremium"]="0";
                            $data["prpCitemKindsTemp[13].riskPremium"]="0";
                            $data["prpCitemKindsTemp[13].amount"]="0";
                            $data["prpCitemKindsTemp[13].rate"]="15.0000";
                            $data["prpCitemKindsTemp[13].disCount"]="0.85500000";
                            break;
                        case 'TTBLI_NDSI':
                            $data["prpCitemKindsTemp[2].specialFlag"]="on";
                            $data["prpCitemKindsTemp[12].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[12].premium"]=$value;
                            $data["prpCitemKindsTemp[12].benchMarkPremium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[12].itemKindNo"]="";
                            $data["prpCitemKindsTemp[12].startDate"]="";
                            $data["prpCitemKindsTemp[12].kindCode"]="050931";
                            $data["prpCitemKindsTemp[12].kindName"]="不计免赔险（三者险）";
                            $data["prpCitemKindsTemp[12].startHour"]="";
                            $data["prpCitemKindsTemp[12].endDate"]="";
                            $data["prpCitemKindsTemp[12].endHour"]="";
                            $data["prpCitemKindsTemp[12].calculateFlag"]="N33N000";
                            $data["relateSpecial[12]"]="";
                            $data["prpCitemKindsTemp[12].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[12].flag"]= "200000";
                            $data["prpCitemKindsTemp[12].basePremium"]="0";
                            $data["prpCitemKindsTemp[12].riskPremium"]="0";
                            $data["prpCitemKindsTemp[12].amount"]="0";
                            $data["prpCitemKindsTemp[12].rate"]="15.0000";
                            $data["prpCitemKindsTemp[12].disCount"]="0.85500000";
                            break;
                        case 'TWCDMVI_NDSI':
                            $data["prpCitemKindsTemp[1].specialFlag"]="on";
                            $data["prpCitemKindsTemp[11].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[11].premium"]=$value;
                            $data["prpCitemKindsTemp[11].benchMarkPremium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[11].itemKindNo"]="";
                            $data["prpCitemKindsTemp[11].startDate"]="";
                            $data["prpCitemKindsTemp[11].kindCode"]="050932";
                            $data["prpCitemKindsTemp[11].kindName"]="不计免赔险（盗抢险）";
                            $data["prpCitemKindsTemp[11].startHour"]="";
                            $data["prpCitemKindsTemp[11].endDate"]="";
                            $data["prpCitemKindsTemp[11].endHour"]="";
                            $data["prpCitemKindsTemp[11].calculateFlag"]="N33N000";
                            $data["relateSpecial[11]"]="";
                            $data["prpCitemKindsTemp[11].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[11].flag"]=" 200000";
                            $data["prpCitemKindsTemp[11].basePremium"]="0";
                            $data["prpCitemKindsTemp[11].riskPremium"]="0";
                            $data["prpCitemKindsTemp[11].amount"]="0";
                            $data["prpCitemKindsTemp[11].rate"]="20.0000";
                            $data["prpCitemKindsTemp[11].disCount"]="0.85500000";
                            break;
                        case 'TCPLI_DRIVER_NDSI':
                            $data["prpCitemKindsTemp[3].specialFlag"]="on";
                            $data["prpCitemKindsTemp[14].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[14].premium"]=$value;
                            $data["prpCitemKindsTemp[14].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[14].itemKindNo"]="";
                            $data["prpCitemKindsTemp[14].startDate"]="";
                            $data["prpCitemKindsTemp[14].kindCode"]="050933";
                            $data["prpCitemKindsTemp[14].kindName"]="不计免赔险（车上人员（司机））";
                            $data["prpCitemKindsTemp[14].startHour"]="";
                            $data["prpCitemKindsTemp[14].endDate"]="";
                            $data["prpCitemKindsTemp[14].endHour"]="";
                            $data["prpCitemKindsTemp[14].calculateFlag"]="N33N000";
                            $data["relateSpecial[14]"]="";
                            $data["prpCitemKindsTemp[14].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[14].flag"]="";
                            $data["prpCitemKindsTemp[14].basePremium"]="0";
                            $data["prpCitemKindsTemp[14].riskPremium"]="0";
                            $data["prpCitemKindsTemp[14].amount"]="0";
                            $data["prpCitemKindsTemp[14].rate"]="";
                            $data["prpCitemKindsTemp[14].disCount"]="";
                            break;
                        case 'TCPLI_PASSENGER_NDSI':
                            $data["prpCitemKindsTemp[4].specialFlag"]="on";
                            $data["prpCitemKindsTemp[15].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[15].premium"]=$value;
                            $data["prpCitemKindsTemp[15].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[15].itemKindNo"]="";
                            $data["prpCitemKindsTemp[15].startDate"]="";
                            $data["prpCitemKindsTemp[15].kindCode"]="050934";
                            $data["prpCitemKindsTemp[15].kindName"]="不计免赔险（车上人员（乘客））";
                            $data["prpCitemKindsTemp[15].startHour"]="";
                            $data["prpCitemKindsTemp[15].endDate"]="";
                            $data["prpCitemKindsTemp[15].endHour"]="";
                            $data["prpCitemKindsTemp[15].calculateFlag"]="N33N000";
                            $data["relateSpecial[15]"]="";
                            $data["prpCitemKindsTemp[15].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[15].flag"]=" 200000";
                            $data["prpCitemKindsTemp[15].basePremium"]="0";
                            $data["prpCitemKindsTemp[15].riskPremium"]="0";
                            $data["prpCitemKindsTemp[15].amount"]="0";
                            $data["prpCitemKindsTemp[15].rate"]="";
                            $data["prpCitemKindsTemp[15].disCount"]="";
                            break;
                        case 'BSDI_NDSI':
                            $data["prpCitemKindsTemp[5].specialFlag"]="on";
                            $data["prpCitemKindsTemp[16].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[16].premium"]=$value;
                            $data["prpCitemKindsTemp[16].benchMarkPremium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[16].itemKindNo"]="";
                            $data["prpCitemKindsTemp[16].startDate"]="";
                            $data["prpCitemKindsTemp[16].kindCode"]="050937";
                            $data["prpCitemKindsTemp[16].kindName"]="不计免赔险（车身划痕损失险）";
                            $data["prpCitemKindsTemp[16].startHour"]="";
                            $data["prpCitemKindsTemp[16].endDate"]="";
                            $data["prpCitemKindsTemp[16].endHour"]="";
                            $data["prpCitemKindsTemp[16].calculateFlag"]="N33N000";
                            $data["relateSpecial[16]"]="";
                            $data["prpCitemKindsTemp[16].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[16].flag"]=" 200000";
                            $data["prpCitemKindsTemp[16].basePremium"]="";
                            $data["prpCitemKindsTemp[16].riskPremium"]="";
                            $data["prpCitemKindsTemp[16].amount"]="";
                            $data["prpCitemKindsTemp[16].rate"]="";
                            $data["prpCitemKindsTemp[16].disCount"]="";
                            break;
                        case 'SLOI_NDSI':
                            $data["prpCitemKindsTemp[8].specialFlag"]="on";
                            $data["prpCitemKindsTemp[17].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[17].premium"]=$value;
                            $data["prpCitemKindsTemp[17].benchMarkPremium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[17].itemKindNo"]="";
                            $data["prpCitemKindsTemp[17].startDate"]="";
                            $data["prpCitemKindsTemp[17].kindCode"]="050935";
                            $data["prpCitemKindsTemp[17].kindName"]="不计免赔险（自燃损失险）";
                            $data["prpCitemKindsTemp[17].startHour"]="";
                            $data["prpCitemKindsTemp[17].endDate"]="";
                            $data["prpCitemKindsTemp[17].endHour"]="";
                            $data["prpCitemKindsTemp[17].calculateFlag"]="N33N000";
                            $data["relateSpecial[17]"]="";
                            $data["prpCitemKindsTemp[17].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[17].flag"]="200000";
                            $data["prpCitemKindsTemp[17].basePremium"]="";
                            $data["prpCitemKindsTemp[17].riskPremium"]="";
                            $data["prpCitemKindsTemp[17].amount"]="";
                            $data["prpCitemKindsTemp[17].rate"]="";
                            $data["prpCitemKindsTemp[17].disCount"]="";
                            break;
                        case 'VWTLI_NDSI':
                            $data["prpCitemKindsTemp[8].specialFlag"]="on";
                            $data["prpCitemKindsTemp[21].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[21].premium"]=$value;
                            $data["prpCitemKindsTemp[21].benchMarkPremium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[21].itemKindNo"]="";
                            $data["prpCitemKindsTemp[21].startDate"]="";
                            $data["prpCitemKindsTemp[21].kindCode"]="050917";
                            $data["prpCitemKindsTemp[21].kindName"]="不计免赔险（发动机涉水损失险）";
                            $data["prpCitemKindsTemp[21].startHour"]="";
                            $data["prpCitemKindsTemp[21].endDate"]="";
                            $data["prpCitemKindsTemp[21].endHour"]="";
                            $data["prpCitemKindsTemp[21].calculateFlag"]="N33N000";
                            $data["relateSpecial[21]"]="";
                            $data["prpCitemKindsTemp[21].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[21].flag"]="200000";
                            $data["prpCitemKindsTemp[21].basePremium"]="";
                            $data["prpCitemKindsTemp[21].riskPremium"]="";
                            $data["prpCitemKindsTemp[21].amount"]="";
                            $data["prpCitemKindsTemp[21].rate"]="";
                            $data["prpCitemKindsTemp[21].disCount"]="";
                            break;
                        case 'LIDI_NDSI':
                            $data["prpCitemKindsTemp[20].specialFlag"]="on";
                            //$data["prpCitemKindsTemp[21].chooseFlag"]="on";
                        case 'NIELI_NDSI':
                            $data["prpCitemKindsTemp[26].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[25].specialFlag"]="on";
                            $data["prpCitemKindsTemp[26].premium"]=$value;
                            $data["prpCitemKindsTemp[26].benchMarkPremium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['STANDARD_PREMIUM'];
                            $data["prpCitemKindsTemp[26].itemKindNo"]="";
                            $data["prpCitemKindsTemp[26].startDate"]="";
                            $data["prpCitemKindsTemp[26].kindCode"]="050936";
                            $data["prpCitemKindsTemp[26].kindName"]="不计免赔险（新增设备损失险）";
                            $data["prpCitemKindsTemp[26].startHour"]="";
                            $data["prpCitemKindsTemp[26].endDate"]="";
                            $data["prpCitemKindsTemp[26].endHour"]="";
                            $data["prpCitemKindsTemp[26].calculateFlag"]="N33N000";
                            $data["relateSpecial[26]"]="";
                            $data["prpCitemKindsTemp[26].clauseCode"]="050066";
                            $data["prpCitemKindsTemp[26].flag"]="200000";
                            $data["prpCitemKindsTemp[26].basePremium"]="";
                            $data["prpCitemKindsTemp[26].riskPremium"]="";
                            $data["prpCitemKindsTemp[26].amount"]="";
                            $data["prpCitemKindsTemp[26].rate"]="";
                            $data["prpCitemKindsTemp[26].disCount"]="";    
                                }
                }
                            return $data;
            } 
            else
            {
            				$data["prpCitemKindsTemp[0].chooseFlag"]="on";
                            $data["prpCitemKindsTemp[0].premium"]=$value;
                            $data["prpCitemKindsTemp[0].benchMarkPremium"]=isset($_SESSION['DISCOUNT'])?round($value/$_SESSION['DISCOUNT'],2):"";

            }                           
        }
        else
        {
                            $this->error['errorMsg']="请重新计算保费，然后在提交核保";
                            return false;
        }
}

    /**
     * [PayForSCMS 计算人保手续费用]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:23:31+0800
     * @param     [type]                   $num      [传递保费]
     * @param     [type]                   $costRate [传递手续折扣]
     */
    private function PayForSCMS($num,$costRate)
    {
        if($num!="")
        {
            $str=round($num*$costRate,2);
            return $str;
        }
        else
        {
            return false;
        }
    }


    /**
     * [check_code 获取SESSION人保配置项]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:21:45+0800
     * @return    [type]                   [成功返回数组，否则返回提示信息]
     */
    private function check_code()
    {

        if(isset($_SESSION['userCode']) && $_SESSION['userCode']!="")
        {

                $data["userCode"]=$_SESSION['userCode'];
                $data["comCode"]=$_SESSION['comCode'];
                $data["prpCmain.comCode"]=$_SESSION['comCode'];  //不能为空
                $data["prpCmain.makeCom"]=$_SESSION['comCode'];
                $data["prpCmain.handler1Code"]=$_SESSION['handler1Code'];//$_SESSION['handler1Code'];//业务归属人员  不能为空
                $data["prpCmain.agentCode"]=$_SESSION['agentCode'];//$agentCode[1][0];//销管合同
                $data["agentCode"]=$_SESSION['agentCode'];
                $data["prpCmain.handlerCode"]=$_SESSION['handler1Code'];
                $data["prpCmain.businessNature"]=$_SESSION['Nature'];
                return $data;
        }
        else
        {

                return false;
        }

    }


    /**
     * [deleite_Certificate 删除投保暂存单]
     * @AuthorHTL
     * @DateTime  2016-12-28T09:55:45+0800
     * @param     [type]                   $Certificate_num [description]
     * @return    [type]                                    [description]
     */
    private function deleite_Certificate($Certificate_num)
    {
        if(isset($Certificate_num) && $Certificate_num!="")
        {

                $data["bizNo"]=$Certificate_num;
                $data["comCode"]=$_SESSION['comCode'];
                $data["riskCode"]="DAA";
                $data["prpCproposalVo.checkFlag"]="";
                $data["prpCproposalVo.underWriteFlag"]="";
                $data["prpCproposalVo.strStartDate"]="";
                $data["prpCproposalVo.othFlag"]="";
                $data["prpCproposalVo.checkUpCode"]="";
                $data["prpCproposalVo.operatorCode1"]="";
                $data["prpCproposalVo.businessNature"]="";
                $data["noNcheckFlag"]="0";
                $data["jfcdURL"]="http://10.134.138.16:8100/cbc";
                $data["prpallURL"]="http://10.134.138.16:8000/prpall";
                $data["bizNoZ"]="";
                $data["pageNo_"]="1";
                $data["pageSize_"]="10";
                $data["scmIsOpen"]="1111100000";
                $data["searchConditionSwitch"]="0";
                $data["queryinterval"]="01";
                $data["prpCproposalVo.proposalNo"]="";
                $data["prpCproposalVo.policyNo"]="";
                $data["prpCproposalVo.licenseNo"]="";
                $data["prpCproposalVo.vinNo"]="";
                $data["prpCproposalVo.insuredCode"]="";
                $data["prpCproposalVo.insuredName"]="";
                $data["prpCproposalVo.contractNo"]="";
                $data["prpCproposalVo.operateDate"]="2016-12-22";//签单日期起止
                $data["prpCproposalVo.operateDate2"]="2016-12-28";////签单日期终止
                $data["prpCproposalVo.startDate"]="2016-12-23";//起保日期起止
                $data["prpCproposalVo.startDate2"]="2016-12-29";//起保日期终止
                $data["prpCproposalVo.dmFlag"]="all";
                $data["prpCproposalVo.underWriteFlagC"]="";
                $data["prpCproposalVo.brandName"]="";
                $data["prpCproposalVo.engineNo"]="";
                $data["prpCproposalVo.frameNo"]="";
                $data["prpCproposalVo.riskCode"]="DAA,DZA";
                $data["prpCproposalVo.appliCode"]="";
                $data["prpCproposalVo.apliName"]="";
                $data["prpCproposalVo.makeCom"]="";
                $data["makeComDes"]="";
                $data["prpCproposalVo.operatorCode"]="";
                $data["operatorCodeDes"]="";
                $data["prpCproposalVo.comCode"]="";
                $data["comCodeDes"]="";
                $data["prpCproposalVo.handlerCode"]="";
                $data["handlerCodeDes"]="";
                $data["prpCproposalVo.handler1Code"]="";
                $data["handler1CodeDes"]="";
                $data["prpCproposalVo.endDate"]="";
                $data["prpCproposalVo.endDate2"]="";
                $data["prpCproposalVo.underWriteEndDate"]="";
                $data["prpCproposalVo.underWriteEndDate2"]="";
                self::requestGetData($this->deleteProposal_Url."?".http_build_query($data));
                return true;
        }
        else
        {
                return false;
        }    



    }

    /**
     * [Submit_underwriting 提交核保]
     * @param [type] $bizNo [传递投保单号]
     */
    private function Submit_underwriting($bizNo)
    {


        //?bizNo=TDZA201751940000012030&bizType=PROPOSAL


    }


}
?>