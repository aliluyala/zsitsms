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
class BjPICCNEISC_PC
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
        $this->URL="http://10.134.136.48:8000";
        $this->Add_Idcard_URL="http://10.134.136.48:8300";
        $this->UrlLogin="https://10.134.136.48:8888/casserver/login?service=http%3A%2F%2F10.134.136.48%3A80%2Fportal%2Findex.jsp";/*登陆处理查询*/
        $this->IdCardUrl=$this->URL."/prpall/custom/customAmountQueryP.do";/*身份信息查询*/
        $this->Token_url=$this->Add_Idcard_URL."/cif/customperson/prepareAdd.do";//获取Token值
        $this->Add_IdCardUrl=$this->Add_Idcard_URL."/cif/customperson/add.do";/*增加身份信息*/
        $this->UrlOffter=$this->URL."/prpall/business/caculatePremiunForFG.do";/*车险报价查询*/
        $this->Plat_Url= $this->URL."/prpall/business/queryTaxAbateForPlat.do";/*查询是否是节能减排车型*/
        //$this->PurchasePriceUrl=$this->URL."/prpall/vehicle/vehicleQuery.do";/*车辆购置价查询*/
        $this->PurchasePriceUrl=$this->URL."/prpall/carInf/getCarModelInfo.do";/*车辆购置价查询*/
        $this->calActualValUrl=$this->URL."/prpall/business/calActualValue.do";
        $this->calDeviceActualValue=$this->URL."/prpall/business/calDeviceActualValue.do";/*新增设备条件*/
        $this->calAnciInfo_Url=$this->URL."/prpall/undwrtassist/calAnciInfo.do";/*计算辅助核保*/
        //$this->checkAgentUrl =$this->URL."/prpall/business/queryPayForSCMS.do";/*计算跟单费用*/

        $this->checkAgentUrl =$this->URL."/prpall/business/queryPayFor.do?agreementNo=&riskCode=DAA&comCode=11019874&chgCostRate=0";/*计算跟单费用*/

        $this->channel_Url=$this->URL."/prpall/business/prepareEdit.do?bizType=PROPOSAL&editType=NEW";/*获取渠道代码*/
        $this->checkBefores_URL= $this->URL."/prpall/business/selectProposal.do";/*查询历史保单*/
        $this->deleteProposal_Url=$this->URL."/prpall/business/deleteProposal.do";/*删除保险公司暂存单*/
        //$this->insert_URL=$this->URL."/prpall/business/insert.do";/*生成暂存单号*/
        $this->insert_URL=$this->URL."/prpall/business/insert4S.do";/*生成暂存单号*/
        $this->editCheckFlag=$this->URL."/prpall/business/editCheckFlag.do";
        $this->editSubmitUndwrt=$this->URL."/prpall/business/editSubmitUndwrt.do";
        $this->showUndwrtMsg=$this->URL."/prpall/business/showUndwrtMsg.do";
        $this->getDataFromCiCarInfo= $this->URL."/prpall/carInf/getDataFromCiCarInfo.do";

        $this->RenewalCopy=$this->URL."/prpall/business/quickProposalEditRenewalCopy.do?bizNo=PDAA201611010000725438";


        if(empty($cachePath))
        {
            $this->cookie_file  = dirname(__FILE__).'/Bjpiccneisc_cookie.txt';/*读取COOKIE文件存放地址*/
            $this->login_cookie = dirname(__FILE__).'/login_cookie.txt';/*读取COOKIE文件存放地址*/
        }
        else
        {
            $this->cookie_file = $cachePath.'/Bjpiccneisc_cookie.txt';  /*COOKIE设置文件存放地址*/
            $this->login_cookie = $cachePath.'/login_cookie.txt';/*读取COOKIE文件存放地址*/
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
        //$apps = self::post($this->UrlLogin,$this->loginAarray);//执行登录
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
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)');
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate'));
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        if(!is_string($post))
        {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post)); // Post提交的数据包
        }
        else
        {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
        }
        //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        //curl_setopt($curl, CURLOPT_PROXY,"192.168.12.130:808");
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_file); // 存放Cookie信息的文件名称
        curl_setopt($curl, CURLOPT_COOKIEFILE,$this->cookie_file); // 读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');//解释gzip
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        if($foll==1)
        {
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        }
        else
        {
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
        //curl_setopt($curl, CURLOPT_PROXY,"192.168.12.130:808");
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
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



                $data=self::datas($auto,$business,$mvtalci);
                if(!$data)
                {
                    return false;
                }
                //$result=self::idcard($auto,$business,$mvtalci);//身份查询

                /*if(!$result)
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
                }*/


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

                                $_SESSION['car_info']["licenseNo"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['licenseNo'];
                                $_SESSION['car_info']["licenseType"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['licenseType'];
                                $_SESSION['car_info']["useNatureCode"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['useNatureCode'];
                                $_SESSION['car_info']["frameNo"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['frameNo'];
                                $_SESSION['car_info']["engineNo"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['engineNo'];
                                $_SESSION['car_info']["licenseColorCode"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['licenseColorCode'];
                                $_SESSION['car_info']["carOwner"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['carOwner'];
                                $_SESSION['car_info']["enrollDate"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['enrollDate'];
                                $_SESSION['car_info']["makeDate"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['makeDate'];
                                $_SESSION['car_info']["seatCount"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['seatCount'];
                                $_SESSION['car_info']["tonCount"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['tonCount'];
                                $_SESSION['car_info']["validCheckDate"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['validCheckDate'];
                                $_SESSION['car_info']["manufacturerName"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['manufacturerName'];
                                $_SESSION['car_info']["modelCode"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['modelCode'];
                                $_SESSION['car_info']["brandCName"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['brandCName'];
                                $_SESSION['car_info']["brandName"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['brandName'];
                                $_SESSION['car_info']["carKindCode"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['carKindCode'];
                                $_SESSION['car_info']["checkDate"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['checkDate'];
                                $_SESSION['car_info']["endValidDate"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['endValidDate'];
                                $_SESSION['car_info']["carStatus"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['carStatus'];
                                $_SESSION['car_info']["haulage"]=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['haulage'];



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
                                $results['MESSAGE'] = $resen['data'][0]['biInsuredemandVoList'][0]['prpCprofitFactors'][0]['condition'];//$resen['data'][0]['biInsuredemandVoList'][0]['prpCfixations'][0]['operationInfo'];//提示信息
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
                                    $results['MVTALCI']['TRAVEL_TAX_PREMIUM']= $resen['data'][0]['ciInsureVOList'][0]['ciCarShipTax']['thisPayTax'];

                                    $_SESSION['MVTALCI_Query_Code']=$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['demandNo'];
                                    $results['MVTALCI']['MVTALCI_PREMIUM']   = $resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['premium'];//$resen['ciPremium'];           //交强险保费

                                    $results['MVTALCI']['MVTALCI_DISCOUNT']  = $resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['premium']/$resen['data'][0]['ciInsureVOList'][0]['ciInsureDemand']['basePremium'];          //交强险折扣


                                    $results['MVTALCI']['MVTALCI_START_TIME']= $mvtalci['MVTALCI_START_TIME'];       //交强险生效时间

                                    $results['MVTALCI']['MVTALCI_END_TIME']  = $mvtalci['MVTALCI_END_TIME'];        //交强险结束时间


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


         if($info['BUYING_PRICE']!="" && $info['ENROLL_DATE']!="" && $info['BUSINESS_START_TIME']!="")
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

          }

        /*$data = array();
        $data["prpCmain.startDate"]=$info['BUSINESS_START_TIME'];
        $data["prpCmain.startHour"]="0";
        $data["prpCmain.endDate"]=$info['BUSINESS_END_TIME'];
        $data["prpCmain.endHour"]="24";
        $data["prpCmainCI.startDate"]=$info['MVTALCI_START_TIME'];
        $data["prpCmainCI.startHour"]="0";
        $data["prpCmainCI.endDate"]=$info['MVTALCI_END_TIME'];
        $data["prpCmainCI.endHour"]="24";
        $data["prpCitemCar.id.itemNo"]="1";
        $data["prpCitemCar.licenseNo"]="京";

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
        }*/

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
     * [queryCarInfo 通过车牌号查询交警库信息]
     * @author LiangYuLin 2017-08-03
     * @param  [type] $licenseNo [description]
     * @return [type]            [description]
     */
    public function queryCarInfo($licenseNo = null)
    {

        if(empty($licenseNo) || $licenseNo == "")
        {
            return  false;
        }

        $where = array(

            'prpCitemCar.licenseNo' => iconv('utf-8', 'gbk', $licenseNo)

            );

       $car_info =   $this->requestPostData($this->getDataFromCiCarInfo,$where);
       if(!empty($car_info) && is_string($car_info))
       {
            $car = json_decode($car_info,true);
            if(!empty($car) && $car['totalRecords'] > 0)
            {
                $info['engineNo'] = $car['data'][0]['engineNo'];
                $info['enrollDate'] = date('Y-m-d', $car['data'][0]['enrollDate']['time'] / 1000);
                $info['seatCount']  = $car['data'][0]['seatCount'];
                $info['vin_no']  = $car['data'][0]['rackNo'];
                $info['Owner']  = $car['data'][0]['carOwner'];
                $info['model'] = $car['data'][0]['brandName1'];
                return $info;
            }
            else
            {
                $this->error['errorMsg']= '无法查询交警库信息。';
                return false;
            }
       }

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

            $arr['userCode']='980057';
            $arr['comCode']='11019874';
            $arr['prpCitemCar.licenseNo']=!isset($info['license_no'])?"":$info['license_no'];
            $arr['prpCitemCar.modelCodeAlias']=!isset($info['model'])?"":$info['model'];
            $arr['prpCitemCar.engineNo']=!isset($info['engineNo'])?"":$info['engineNo'];
            $arr['prpCitemCar.frameNo']=!isset($info['vin_no'])?"":$info['vin_no'];
            $arr['prpCitemCar.clauseType']='F42';
            $arr['prpCitemCar.enrollDate']=!isset($info['enrollDate'])?"":$info['enrollDate'];
            $arr['prpCitemCar.newCarFlag']='0';
            $arr['prpCitemCar.noNlocalFlag']='0';
            $result=self::requestPostData($this->PurchasePriceUrl,$arr);
            $array= json_decode($result,true);
            $count="";

            if(is_array($array) && count($array['totalRecords'])>0)
            {
                $retdata = array('total'=>1 ,'page'=>1,'records'=>1,'rows'=>array());
                foreach($array['data'] as $row)
                {

                    $line = array();
                    $line['vehicleId']             = $row['refCode1'];
                    $line['vehicleName']           = $row['modelName'];
                    $line['vehicleAlias']          = $row['modelDesc'];
                    //$line['vehicleMaker']          = $row['vehicleMaker'];
                    $line['vehicleWeight']         = $row['wholeWeight'];
                    $line['vehicleDisplacement']   = $row['disPlaceMent']/1000;
                    $line['vehicleTonnage']        = $row['vehicleTonnage'];
                    $line['vehiclePrice']          = $row['replaceMentValue'];
                    $line['szxhTaxedPrice']        = 0;
                    $line['xhKindPrice']           = 0;
                    $line['nXhKindpriceWithouttax']= 0;
                    $line['vehicleSeat']           = $row['rateDPassengercapacity'];
                    $line['vehicleYear']           = $row['marketYear'];
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


    $__tops = $this->__top($auto,$business,$mvtalci);
    $cards= $this->cards($auto,$business,$mvtalci);
    if(!$cards)
    {
        return false;
    }
    $data = array_merge($__tops,$cards);

    $data["prpCitemKind.shortRateFlag"]="2";
    $data["prpCitemKind.shortRate"]="100";
    $data["prpCitemKind.currency"]="CNY";
    $data["prpCmainCommon.groupFlag"]="0";
    $data["prpCmain.preDiscount"]="";
    $data["sumBenchPremium"]="";
    $data["prpCmain.discount"]="";
    $data["prpCmain.sumPremium"]="";
    $data["premiumF48"]="5000";
    $data["prpCmain.sumNetPremium"]="";
    $data["prpCmain.sumTaxPremium"]="";
    $data["passengersSwitchFlag"]="";

    $_SESSION['POLICY']['KindsTemp_amount']= "";

    if(count($business['POLICY']['BUSINESS_ITEMS'])>0)
    {
        foreach($business['POLICY']['BUSINESS_ITEMS'] as $u=>$value){
                    switch ($value) {
                        case 'TVDI':
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
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
                            $data["prpCitemKindsTemp[0].startDate"]="";
                            $data["prpCitemKindsTemp[0].startHour"]="0";
                            $data["prpCitemKindsTemp[0].endDate"]="";
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
                            $_SESSION['POLICY']['KindsTemp_amount']+= $data["prpCitemKindsTemp[2].amount"];
                            $data["prpCitemKindsTemp[2].calculateFlag"]="Y21Y000";
                            $data["prpCitemKindsTemp[2].startDate"]="";
                            $data["prpCitemKindsTemp[2].startHour"]="";
                            $data["prpCitemKindsTemp[2].endDate"]="";
                            $data["prpCitemKindsTemp[2].endHour"]="";
                            $data["relateSpecial[2]"]="050931";
                            $data["prpCitemKindsTemp[2].flag"]="100000";
                            $data["prpCitemKindsTemp[2].basePremium"]="";
                            $data["prpCitemKindsTemp[2].riskPremium"]="";
                            $data["prpCitemKindsTemp[2].rate"]="";
                            $data["prpCitemKindsTemp[2].benchMarkPremium"]="";
                            $data["prpCitemKindsTemp[2].disCount"]="";
                            $data["prpCitemKindsTemp[2].premium"]="";
                            break;
                        case 'TWCDMVI':
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
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
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
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
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']*$business['POLICY']['TCPLI_PASSENGER_COUNT'];
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
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['BSDI_INSURANCE_AMOUNT'];
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
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['NIELI_INSURANCE_AMOUNT'];
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
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['SLOI_INSURANCE_AMOUNT'];
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
                            $data["prpCitemKindsTemp[8].rate"]="0.13";
                            $data["prpCitemKindsTemp[8].disCount"]="";
                            $data["prpCitemKindsTemp[8].premium"]="";
                            break;
                        case 'RDCCI':
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['RDCCI_INSURANCE_QUANTITY']*$business['POLICY']['RDCCI_INSURANCE_UNIT'];
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
                            $_SESSION['POLICY']['KindsTemp_amount']+= $business['POLICY']['LIABILITY_INSURANCE_AMOUNT'];
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
    if(empty($mvtalci))
    {
        $data["prpCitemKindCI.familyNo"]="0";
    }
    else
    {

        $data["prpCitemKindCI.familyNo"]="1";
    }
    $data["cIBPFlag"]="1";
    $data["prpCitemKindCI.unitAmount"]="0";
    $data["prpCitemKindCI.startMinute"]="";
    $data["prpCitemKindCI.endMinute"]="";
    $data["prpCitemKindCI.id.itemKindNo"]="";
    $data["prpCitemKindCI.kindCode"]="050100";
    $data["prpCitemKindCI.clauseCode"]="050001";
    $data["prpCitemKindCI.riskPremium"]="0";
    $data["prpCitemKindCI.kindName"]="机动车交通事故强制责任保险";
    $data["prpCitemKindCI.calculateFlag"]="Y";
    $data["prpCitemKindCI.basePremium"]="";
    $data["prpCitemKindCI.quantity"]="1";
    $data["prpCitemKindCI.amount"]="122000";
    $data["prpCitemKindCI.deductible"]="0";
    $data["prpCitemKindCI.adjustRate"]="0.90";
    $data["prpCitemKindCI.rate"]="0";
    $data["prpCitemKindCI.benchMarkPremium"]="950";
    $data["prpCitemKindCI.disCount"]="1";
    $data["prpCitemKindCI.premium"]="";
    $data["prpCitemKindCI.flag"]="";
    $data["prpCitemKindCI.netPremium"]="";
    $data["prpCitemKindCI.taxPremium"]="";
    $data["prpCitemKindCI.taxRate"]="6.00";
    $data["prpCitemKindCI.taxfee_gb"]="0";
    $data["prpCitemKindCI.taxfee_lb"]="0";
    $data["prpCitemKindCI.allTaxFee"]="";
    $data["prpCitemKindCI.allNetPremium"]="";
    $data["prpCitemKindCI.dutyFlag"]="2";

    $data["prpCitemCarExt_CI.rateRloatFlag"]="01";
    $data["prpCitemCarExt_CI.noDamYearsCI"]="0";
    $data["prpCitemCarExt_CI.lastDamagedCI"]="0";
    $data["prpCitemCarExt_CI.flag"]="";
    $data["prpCitemCarExt_CI.damFloatRatioCI"]="0";
    $data["prpCitemCarExt_CI.offFloatRatioCI"]="0";
    $data["prpCitemCarExt_CI.thisDamagedCI"]="0";
    $data["hidden_index_ctraffic_NOPlat_Drink"]="0";
    $data["hidden_index_ctraffic_NOPlat"]="0";
    $data["ciInsureDemand.demandNo"]="";
    $data["ciInsureDemand.demandTime"]=date("Y-m-d",time());
    $data["ciInsureDemand.restricFlag"]="0001";
    $data["ciInsureDemand.preferentialDay"]="53";
    $data["ciInsureDemand.preferentialPremium"]="";
    $data["ciInsureDemand.preferentialFormula "]="";
    $data["ciInsureDemand.lastyearenddate"]="";
    $data["prpCitemCar.noDamageYears"]="0";
    $data["ciInsureDemand.rateRloatFlag"]="00";
    $data["ciInsureDemand.claimAdjustReason"]="A1";
    $data["ciInsureDemand.peccancyAdjustReason"]="V1";
    $data["cIRiskWarningType"]="";
    $data["CIDemandFecc_Flag"]="";
    $data["CIDemandClaim_Flag"]="";

    $data["AccidentFlag"]="";
    $data["rateFloatFlag"]="ND1";
    $data["hidden_index_ctraffic"]="0";
    $data["_taxUnit"]="";
    $data["taxPlatFormTime"]="";
    $data["iniPrpCcarShipTax_Flag"]="";
    $data["strCarShipFlag"]="1";

    $data["prpCcarShipTax.extendChar2"]="";
    $data["prpCcarShipTax.taxType"]="1";
    $data["prpCcarShipTax.calculateMode"]="C1";
    $data["prpCcarShipTax.leviedDate"]="";
    $data["prpCcarShipTax.carKindCode"]="A01";
    $data["prpCcarShipTax.model"]="B11";
    $data["prpCcarShipTax.taxPayerIdentNo"]="132135197809023118";
    $data["prpCcarShipTax.taxPayerNumber"]="132135197809023118";
    $data["prpCcarShipTax.carLotEquQuality"]="1845";
    $data["prpCcarShipTax.taxPayerCode"]="1100100000552299";
    $data["prpCcarShipTax.id.itemNo"]="1";
    $data["prpCcarShipTax.taxPayerNature"]="3";
    $data["prpCcarShipTax.taxPayerName"]="贝莹莹";
    $data["prpCcarShipTax.taxUnit"]="";
    $data["prpCcarShipTax.taxComCode"]="";
    $data["prpCcarShipTax.taxComName"]="";
    $data["prpCcarShipTax.taxExplanation"]="";
    $data["prpCcarShipTax.taxAbateReason"]="08";
    $data["prpCcarShipTax.dutyPaidProofNo_1"]="";
    $data["prpCcarShipTax.dutyPaidProofNo_2"]="";
    $data["prpCcarShipTax.dutyPaidProofNo"]="";
    $data["prpCcarShipTax.taxAbateRate"]="50";
    $data["prpCcarShipTax.taxAbateAmount"]="";
    $data["prpCcarShipTax.taxAbateType"]="1";
    $data["prpCcarShipTax.taxUnitAmount"]="";
    $data["prpCcarShipTax.prePayTaxYear"]="2016";
    $data["prpCcarShipTax.prePolicyEndDate"]="";
    $data["prpCcarShipTax.payStartDate"]="2017-01-01";
    $data["prpCcarShipTax.payEndDate"]="2017-12-31";
    $data["prpCcarShipTax.thisPayTax"]="400";
    $data["prpCcarShipTax.prePayTax"]="0";
    $data["prpCcarShipTax.taxItemCode"]="";
    $data["prpCcarShipTax.taxItemName"]="";
    $data["prpCcarShipTax.baseTaxation"]="";
    $data["prpCcarShipTax.taxRelifFlag"]="";
    $data["prpCcarShipTax.delayPayTax"]="0";
    $data["prpCcarShipTax.sumPayTax"]="400";
    $data["CarShipInit_Flag"]="";
    $data["prpCcarShipTax.flag"]="";


    $data["quotationtaxPayerCode"]="";
    $data["noBringOutEngage"]="";
    $data["iniPrpCengage_Flag"]="1";
    $data["hidden_index_engage"]="0";
    //$data["prpCengageTemps[0].id.serialNo"]="1";
    //$data["prpCengageTemps[0].clauseCode"]="910012";
    //$data["prpCengageTemps[0].clauseName"]="尾号减免特约";
    //$data["clauses[0]"]="";//"您的车辆不享受奥运限行减免，属按尾号限行范围， 进行交强险保费减免，减免总天数为53天，减免保费116.75元，减免保费";
    //$data["prpCengageTemps[0].flag"]="";
    //$data["prpCengageTemps[0].engageFlag"]="";
    //$data["prpCengageTemps[0].maxCount"]="";
    //$data["prpCengageTemps[0].clauses"]="";//"您的车辆不享受奥运限行减免，属按尾号限行范围， 进行交强险保费减免，减免总天数为53天，减免保费116.75元，减免保费";
    $data["costRateForPG"]="";
    $data["certificateNo"]="79854683-7";
    $data["levelMaxRate"]="";
    $data["maxRateScm"]="45";
    $data["levelMaxRateCi"]="";
    $data["maxRateScmCi"]="4";
    $data["isModifyBI"]="";
    $data["isModifyCI"]="";
    $data["sumBICoinsRate"]="";
    $data["sumCICoinsRate"]="";
    $data["netCommission_Switch"]="";
    $data["agentsRateBI"]="";
    $data["agentsRateCI"]="";
    $data["prpVisaRecordP.id.visaNo"]="";
    $data["prpVisaRecordP.id.visaCode"]="";
    $data["prpVisaRecordP.visaName"]="";
    $data["prpVisaRecordP.printType"]="101";
    $data["prpVisaRecordT.id.visaNo"]="";
    $data["prpVisaRecordT.id.visaCode"]="";
    $data["prpVisaRecordT.visaName"]="";
    $data["prpVisaRecordT.printType"]="103";
    $data["prpCmain.sumAmount"]="";
    $data["prpCmain.sumDiscount"]="";
    $data["prpCstampTaxBI.biTaxRate"]="";
    $data["prpCstampTaxBI.biPayTax"]="0";
    $data["prpCmainCommon.netsales"]="1";
    $data["prpVisaRecordPCI.id.visaNo"]="";
    $data["prpVisaRecordPCI.id.visaCode"]="";
    $data["prpVisaRecordPCI.visaName"]="";
    $data["prpVisaRecordPCI.printType"]="";
    $data["prpVisaRecordTCI.id.visaNo"]="";
    $data["prpVisaRecordTCI.id.visaCode"]="";
    $data["prpVisaRecordTCI.visaName"]="";
    $data["prpVisaRecordTCI.printType"]="";
    $data["prpCmainCI.sumAmount"]="122000";
    $data["prpCmainCI.sumDiscount"]="";
    $data["prpCstampTaxCI.ciTaxRate"]="";
    $data["prpCstampTaxCI.ciPayTax"]="0";
    $data["prpCmainCI.sumPremium"]="";
    $data["prpCmainCar.rescueFundRate"]="";
    $data["prpCmainCar.resureFundFee"]="";
    $data["prpCmainCommonCI.netsales"]="";
    $data["prpCmain.projectCode"]="";
    $data["projectCode"]="";
    $data["costRateUpper"]="";
    $data["prpCmainCommon.ext3"]="";
    $data["importantProjectCode"]="";
    $data["prpCmain.operatorCode"]="980057";
    $data["operatorName"]="耿淑云";
    $data["operateDateShow"]=date("Y-m-d",time());
    $data["prpCmain.coinsFlag"]="00";
    $data["coinsFlagBak"]="00";
    $data["premium"]="";
    $data["prpCmain.language"]="CNY";
    $data["prpCmain.policySort"]="1";
    $data["prpCmain.policyRelCode"]="";
    $data["prpCmain.policyRelName"]="";
    $data["subsidyRate"]="";
    $data["policyRel"]="";
    $data["prpCmain.reinsFlag"]="0";
    $data["prpCmain.agriFlag"]="0";
    $data["prpCmainCar.carCheckStatus"]="0";
    $data["prpCmainCar.carChecker"]="";
    $data["carCheckerTranslate"]="";
    $data["prpCmainCar.carCheckTime"]="";
    $data["prpCmainCommon.DBCFlag"]="0";
    $data["prpCmain.argueSolution"]="1";
    $data["prpCmain.arbitBoardName"]="";
    $data["arbitBoardNameDes"]="";






    $data["prpCmainCar.flag"]="1";
    $data["prpCmainCarFlag"]="1";
    $data["coinsSchemeCode"]="";
    $data["coinsSchemeName"]="";
    $data["mainPolicyNo"]="";
    $data["iniPrpCcoins_Flag"]="";
    $data["hidden_index_ccoins"]="0";
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
    $data["hidden_index_specialFac"]="0";
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
    $data["prpCsettlement.buyerPreFee"]="";
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
    $data["prpCsettlement.buyerProvince"]="11000000";
    $data["buyerProvinceDes"]="人保财险北京市分公司";
    $data["prpCsettlement.buyerBusinessSort"]="01";
    $data["prpCsettlement.comCname"]="";
    $data["prpCsettlement.linkerCode"]="";
    $data["linkerName"]="";
    $data["linkerPhone"]="";
    $data["linkerMobile"]="";
    $data["linkerFax"]="";
    $data["prpCsettlement.comCode"]="";
    $data["prpCsettlement.fundForm"]="1";
    $data["prpCsettlement.flag"]="0";
    $data["settlement_Flag"]="";
    $data["hidden_index_ccontriutions"]="0";
    $data["iProposalNo"]="";
    $data["CProposalNo"]="";
    $data["timeFlag"]="";
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






    private function car_info($auto=array(),$business=array(),$mvtalci=array())
    {



            $data["prpCitemCar.carLoanFlag"]="";
            $data["carModelPlatFlag"]="1";
            $data["updateQuotation"]="";
            $data["prpCitemCar.licenseNo1"]="";
            $data["pmCarOwner"]="";
            $data["prpCitemCar.monopolyFlag"]="1";
            $data["prpCitemCar.monopolyCode"]="1100727000998";
            $data["prpCitemCar.monopolyName"]="北京众志通达汽车修理有限公司";
            $data["queryCarModelInfo"]="车型信息平台交互";
            $data["prpCitemCar.id.itemNo"]="1";
            $data["oldClauseType"]="F42";
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
            $data["prpCitemCar.newCarFlag"]="0";
            $data["prpCitemCar.noNlocalFlag"]="0";
            $data["prpCitemCar.licenseFlag"]="1";
            $data["prpCitemCar.licenseNo"]=$auto['LICENSE_NO'];
            $data["codeLicenseType"]="LicenseType01,04,LicenseType02,01,LicenseType03,02,LicenseType04,02,LicenseType05,02,LicenseType06,02,LicenseType07,04,LicenseType08,04,LicenseType09,01,LicenseType10,01,LicenseType11,01,LicenseType12,01,LicenseType13,04,LicenseType14,04,LicenseType15,04,    LicenseType16,04,LicenseType17,04,LicenseType18,01,LicenseType19,01,LicenseType20,01,LicenseType21,01,LicenseType22,01,LicenseType23,03,LicenseType24,01,LicenseType25,01,LicenseType31,03,LicenseType32,03,LicenseType90,02";
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

            foreach($this->vehicle_type as $key=>$val)
            {
                if($key==$auto['VEHICLE_TYPE'])
                {
                    $data["prpCitemCar.carKindCode"]=$val[0];
                    $data["CarKindCodeDes"]=$val[1];
                    $data["carKindCodeBak"]=$val[0];
                }
            }

            foreach($this->use_character as $ke=>$ve)
            {
                if($ke==$auto['USE_CHARACTER'])
                {
                    $data["prpCitemCar.useNatureCode"]=$ve[1];
                    $data["useNatureCodeBak"]=$ve[1];
                    $data["useNatureCodeTrue"]=$ve[1];
                }
            }

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
            $data["prpCitemCar.enrollDate"]=$auto['ENROLL_DATE'];
            $data["enrollDateTrue"]=$auto['ENROLL_DATE'];
            //$busin_time= strtotime($business['BUSINESS_START_TIME']);
            $data["prpCitemCar.useYears"]= $this->diffBetweenTwoDays($business['BUSINESS_START_TIME'],$auto['ENROLL_DATE']);//date("Y",$busin_time)-date("Y",strtotime($auto['ENROLL_DATE']));  //实际使用年数
            $data["useYear"] =  $this->diffBetweenTwoDays($business['BUSINESS_START_TIME'],$auto['ENROLL_DATE']);
            $data["taxAbateForPlat"]="";
            $data["taxAbateForPlatCarModel"]="";
            $data["prpCitemCar.modelDemandNo"]="";
            $data["owner"]=$auto['OWNER'];
            $data["prpCitemCar.remark"]="";
            $data["prpCitemCar.modelCode"]=$auto['MODEL_CODE'];  //车型编码
            $data["prpCitemCar.brandName"]=$auto['MODEL']; //车型名称
            $data["PurchasePriceScal"]="10";
            $data["prpCitemCar.purchasePrice"]=$auto['BUYING_PRICE']; //新车购置价格
            $data["CarActualValueTrue"]=$auto['BUYING_PRICE'];
            $data["modelCodes"]="";
            $data["CarActualValueTrue1"]="";
            $data["SZpurchasePriceUp"]="";
            $data["SZpurchasePriceDown"]="";
            $data["purchasePriceF48"]="200000";
            $data["purchasePriceUp"]="100";
            $data["purchasePriceDown"]="0";
            $data["purchasePriceOld"]=$auto['BUYING_PRICE'];
            $data["prpCitemCar.actualValue"]= $auto['DISCOUNT_PRICE']; //$this->depreciation($info); //参考实际价值
            $data["prpCitemCar.tonCount"]="0.00";//$auto['tonCount'];  //核定载质量(千克)
            $data["prpCitemCar.exhaustScale"]=$auto['ENGINE'];   //排量/功率(升)
            $data["prpCitemCar.seatCount"]=$auto['SEATS'];         //核定载客量(人)
            $data["seatCountTrue"]=$auto['SEATS'];
            $data["prpCitemCar.runAreaCode"]="11"; //行驶区域  03 省内  11 中华人民共和国境内(不含港澳台) 12 有固定行驶路线  13 场内
            $data["prpCitemCar.carInsuredRelation"]="1";//被保险人和车辆关系 1所有  2使用 3管理
            $data["prpCitemCar.countryNature"]="02";//进口/国产类 01 国产 02进口 03合资
            $data["prpCitemCar.cylinderCount"]="";
            $data["prpCitemCar.loanVehicleFlag"]="0";//是否未还清贷款 1是 0否
            $data["prpCitemCar.transferVehicleFlag"]="0";//是否为过户车 1是 0否
            $data["prpCitemCar.transferDate"]="";//过户日期
            $data["prpCitemCar.modelCodeAlias"]="";//车型别名
            $data["prpCitemCar.carLotEquQuality"]=$auto['KERB_MASS']; //整备质量(千克)
            $data["isQuotation"]="1";
            $data["prpCmainCommon.queryArea"]="110000";//指定区域 110000北京市 120000天津市 130000 河北省 140000 山西省 150000内蒙古自治区 210000辽宁省 220000 吉林省 230000黑龙江省 310000上海市 320000江苏省 330000 浙江省 340000 安徽省 350000 福建省 360000江西省 370000 山东省 410000 河南省 420000 湖北省 430000 湖南省 440000 广东省 450000 广西壮族自治区
            $data["queryArea"]="北京市";
            $data["vehiclePricer"]=$auto['BUYING_PRICE'];
            $data["prpCitemCar.fuelType"]="A";
            $data["prpCitemCar.carProofType"]="01";
            $data["prpCitemCar.isDropinVisitInsure"]="0";
            $data["prpCitemCar.energyType"]="0";  //能源种类 0燃油 1纯动力 2燃料电池 3插电式混合动力 4其他混合动力
            $data["prpCitemCar.carProofNo"]="00853041";
            $data["prpCitemCar.carProofDate"]="";


            return $data;

    }

    /**
     * [diffBetweenTwoDays 计算两个日期相差多少年]
     * @param  [type] $day1 [商业险起保时间]
     * @param  [type] $day2 [注册时间]
     * @return [type]       [description]
     */
    private function diffBetweenTwoDays($day1, $day2)
    {

        $busin_Y = date("Y",strtotime($day1));
        $dangqian_Y= date("Y",strtotime($day2));
        $busin_m = date("m",strtotime($day1));
        $dangqian_m= date("m",strtotime($day2));
        $busin_d = date("d",strtotime($day1));
        $dangqian_d= date("d",strtotime($day2));


        if(intval($busin_m) > intval($dangqian_m))
        {
           return $busin_Y- $dangqian_Y;
        }
        else if(intval($busin_m) == intval($dangqian_m))
        {

            if(($busin_Y - $dangqian_Y)>1)
            {

              if($busin_d < $dangqian_d)
              {

                return $busin_Y- $dangqian_Y-1;
              }
              else
              {
                return $busin_Y- $dangqian_Y;
              }

            }
            else
            {

                return $busin_Y- $dangqian_Y;

            }
        }
        else if(intval($busin_m) < intval($dangqian_m))
        {
             return $busin_Y- $dangqian_Y-1;

        }

    }


    /**
     * [mvtalci 交强险信息]
     * @param  [type] $mvtalci [传递交强险数组]
     * @return [type]          [返回交强险]
     */
    private function mvtalci($mvtalci = array())
    {

                $mvtalci_data = Array(

                        "cIBPFlag"=> "1",
                        "prpCitemKindCI.shortRate"=> "100",
                        "prpCitemKindCI.unitAmount"=> "122000.00",
                        "prpCitemKindCI.id.itemKindNo"=> "1",
                        "prpCitemKindCI.kindCode"=> "050100",
                        "prpCitemKindCI.clauseCode"=> "050001",
                        "prpCitemKindCI.riskPremium"=> "0.00",
                        "prpCitemKindCI.kindName"=> "机动车交通事故强制责任保险",
                        "prpCitemKindCI.calculateFlag"=> "Y",
                        "prpCitemKindCI.basePremium"=> "",
                        "prpCitemKindCI.quantity"=> "1",
                        "prpCitemKindCI.amount"=>"122000.00",
                        "prpCitemKindCI.deductible"=>"0.00",
                        "prpCitemKindCI.adjustRate"=>empty($mvtalci['MVTALCI_COUNT'])?"":$mvtalci['MVTALCI_COUNT'],
                        "prpCitemKindCI.rate"=>"0",
                        "prpCitemKindCI.benchMarkPremium"=>empty($_SESSION['BASE_PREMIUM'])?"":$_SESSION['BASE_PREMIUM'],
                        "prpCitemKindCI.disCount"=>"1",
                        "prpCitemKindCI.premium"=>empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'],
                        "prpCitemKindCI.flag"=>"1000000",
                        "prpCitemKindCI.netPremium"=>empty($_SESSION['NetPremium'])?"":$_SESSION['NetPremium'],
                        "prpCitemKindCI.taxPremium"=>empty($_SESSION['MVTALCI_TAX_PREMIUM'])?"":$_SESSION['MVTALCI_TAX_PREMIUM'],
                        "prpCitemKindCI.taxRate"=>"",
                        "prpCitemKindCI.taxfee_gb"=>"0",
                        "prpCitemKindCI.taxfee_lb"=>"0",
                        "prpCitemKindCI.allTaxFee"=>"",
                        "prpCitemKindCI.allNetPremium"=>"",
                        "prpCitemKindCI.dutyFlag"=>"2",
                );

                if(isset($mvtalci['MVTALCI_PREMIUM']) && $mvtalci['MVTALCI_PREMIUM']!="")
                {
                    $mvtalci_data["prpCitemKindCI.familyNo"]="1";
                }
                else
                {

                    $mvtalci_data["prpCitemKindCI.familyNo"]="0";
                }


                return $mvtalci_data;


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



                $summary_datas = $this->Summary_info($auto,$business,$mvtalci);


                $items_datas  = $this->get_items($business);

                $car_info= $this->car_info($auto,$business,$mvtalci);//车辆信息
                $mvtalci_datas = $this->mvtalci($mvtalci);//交强险信息
                $summary_items = array_merge($summary_datas,$items_datas,$car_info,$mvtalci_datas);
                $company = $this->Company_handling($summary_items);

                $policy = $this->Preservation($auto,$business,$mvtalci,$company);
                return json_encode($policy);


    }



    /**
     * [Company_handling 计算辅助核保]
     * @AuthorHTL
     * @DateTime  2016-12-15T16:37:05+0800
     * @param     array                    $auto     [传递数组]
     * @param     array                    $business [传递数组]
     * @param     array                    $mvtalci  [返回辅助核保信息]
     */
    private function Company_handling($summary_datas=array())
    {


                $datas["prpCitemKind.shortRateFlag"]="2";
                $datas["prpCitemKind.shortRate"]="100";
                $datas["prpCitemKind.currency"]="CNY";
                $datas["prpCmainCommon.groupFlag"]="0";
                $datas["prpCmain.preDiscount"]="";
                $datas["sumBenchPremium"]="";
                $datas["prpCmain.discount"]="";
                $datas["prpCmain.sumPremium"]="";
                $datas["premiumF48"]="5000";
                $datas["prpCmain.sumNetPremium"]="";
                $datas["prpCmain.sumTaxPremium"]="";
                $datas["BIdemandNo"]="";
                $datas["BIdemandTime"]="";
                $datas["bIRiskWarningType"]="";
                $datas["noDamageYearsBIPlat"]="0";
                $datas["prpCitemCarExt.lastDamagedBI"]="0";
                $datas["lastDamagedBITemp"]="0";
                $datas["DAZlastDamagedBI"]="1";
                $datas["prpCitemCarExt.thisDamagedBI"]="0";
                $datas["prpCitemCarExt.noDamYearsBI"]="1";
                $datas["noDamYearsBINumber"]="1";
                $datas["prpCitemCarExt.lastDamagedCI"]="0";
                $datas["BIDemandClaim_Flag"]="";



                $datas["planStr"]="";
                $datas["planPayTimes"]="";
                $datas["prpAnciInfo.sellExpensesRate"]="";
                $datas["prpAnciInfo.sellExpensesAmount"]="";
                $datas["prpAnciInfo.sellExpensesRateCIUp"]="";
                $datas["prpAnciInfo.sellExpensesCIUpAmount"]="";
                $datas["prpAnciInfo.sellExpensesRateBIUp"]="";
                $datas["prpAnciInfo.sellExpensesBIUpAmount"]="";
                $datas["prpAnciInfo.operSellExpensesRate"]="";
                $datas["prpAnciInfo.operSellExpensesAmount"]="";
                $datas["prpAnciInfo.operSellExpensesRateCI"]="";
                $datas["prpAnciInfo.operSellExpensesAmountCI"]="";
                $datas["prpAnciInfo.operSellExpensesRateBI"]="";
                $datas["prpAnciInfo.operSellExpensesAmountBI"]="";
                $datas["prpAnciInfo.operCommRateCIUp"]="";
                $datas["operCommRateCIUpAmount"]="";
                $datas["prpAnciInfo.operCommRateBIUp"]="";
                $datas["operCommRateBIUpAmount"]="";
                $datas["prpAnciInfo.operCommRate"]="";
                $datas["prpAnciInfo.operCommRateAmount"]="";
                $datas["prpAnciInfo.operateCommRateCI"]="";
                $datas["prpAnciInfo.operateCommCI"]="";
                $datas["prpAnciInfo.operateCommRateBI"]="";
                $datas["prpAnciInfo.operateCommBI"]="";
                $datas["prpAnciInfo.discountRateUp"]="";
                $datas["prpAnciInfo.discountRateUpAmount"]="";
                $datas["prpAnciInfo.discountRateCIUp"]="";
                $datas["prpAnciInfo.discountRateCIUpAmount"]="";
                $datas["prpAnciInfo.profitRateBIUp"]="";
                $datas["prpAnciInfo.discountRateBIUpAmountp"]="";
                $datas["prpAnciInfo.discountRate"]="";
                $datas["prpAnciInfo.discountRateAmount"]="";
                $datas["prpAnciInfo.discountRateCI"]="";
                $datas["prpAnciInfo.discountRateCIAmount"]="";
                $datas["prpAnciInfo.discountRateBI"]="";
                $datas["prpAnciInfo.discountRateBIAmount"]="";
                $datas["prpAnciInfo.riskCode"]="";
                $datas["prpAnciInfo.standPayRate"]="";
                $datas["prpAnciInfo.operatePayRate"]="";
                $datas["prpAnciInfo.busiStandardBalanRate"]="";
                $datas["prpAnciInfo.busiBalanRate"]="";
                $datas["prpAnciInfo.busiRiskRate"]="";
                $datas["prpAnciInfo.averProfitRate"]="";
                $datas["prpAnciInfo.averageRate"]="";
                $datas["prpAnciInfo.minNetSumPremiumBI"]="";
                $datas["prpAnciInfo.minNetSumPremiumCI"]="";
                $datas["prpAnciInfo.baseActBusiType"]="";
                $datas["prpAnciInfo.baseExpBusiType"]="";
                $datas["prpAnciInfo.operateProfitRate"]="";
                $datas["prpAnciInfo.breakEvenValue"]="";
                $datas["prpAnciInfo.proCommRateBIUp"]="";
                $datas["prpAnciInfo.busiTypeCommBIUp"]="";
                $datas["prpAnciInfo.busiTypeCommCIUp"]="";
                $datas["prpAnciInfo.standbyField1"]="";
                $datas["actProfitRate"]="";
                $datas["prpAnciInfo.businessCode"]="";
                $datas["prpAnciInfo.minNetSumPremium"]="";
                $datas["prpAnciInfo.origBusiType"]="";
                $datas["prpAnciInfo.expProCommRateUp"]="";
                $datas["expProCommRateUp_Disc"]="";
                $datas["prpAnciInfo.expBusiType"]="";
                $datas["prpAnciInfo.actProCommRateUp"]="";
                $datas["actProCommRateUp_Disc"]="";
                $datas["prpAnciInfo.actBusiType"]="";
                $datas["expRiskNote"]="";
                $datas["kindBusiTypeA"]="";
                $datas["kindBusiTypeB"]="";
                $datas["kindBusiTypeC"]="";
                $datas["kindBusiTypeD"]="";
                $datas["kindBusiTypeE"]="";
                $datas["prpCmainCar.flag"]="1";
                $datas["prpCmainCarFlag"]="1";

                $Data = array_merge($summary_datas,$datas);

                $post_data = json_decode($this->requestPostData($this->calAnciInfo_Url,$Data),true);

                if(isset($post_data['data']) && !empty($post_data['data']))
                {
                    return $post_data;
                }
                else
                {
                    $this->error['errorMsg']="查询辅助核保失败。";
                    return false;
                }

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


                $top_Datas = $this->__top($auto,$business,$mvtalci);
                $id_Datas  = $this->cards($auto,$business,$mvtalci);
                $Summary_info = $this->Summary_info($auto,$business,$mvtalci);

                $data = array_merge($top_Datas,$id_Datas,$Summary_info);

                $data["prpCitemKind.shortRateFlag"]="2";
                $data["prpCitemKind.shortRate"]="100.0000";
                $data["prpCitemKind.currency"]="CNY";
                $data["prpCmainCommon.groupFlag"]="0";
                $data["prpCmain.preDiscount"]="";
                $data["sumBenchPremium"]=round($_SESSION['COUNT_PREMIUM']/$_SESSION['DISCOUNT'],2);
                $data["prpCmain.discount"]=$_SESSION['DISCOUNT'];
                $data["prpCmain.sumPremium"]=$_SESSION['COUNT_PREMIUM'];
                $data["premiumF48"]="5000";
                $data["prpCmain.sumNetPremium"]=$_SESSION['NET_PREMIUM'];
                $data["prpCmain.sumTaxPremium"]=$_SESSION['TAX'];
                $data["passengersSwitchFlag"]="";



                foreach($business['POLICY']['BUSINESS_ITEMS'] as $u=>$value){
                            switch ($u) {

                                case 'TVDI':
                                    $data["prpCitemKindsTemp[0].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[0].pureRiskPremium"]=$_SESSION['INSURANCE']['TVDI']['PURERISK_PREMIUM'];/**增加纯风险保费**/
                                    $data["prpCitemKindsTemp[0].premium"]=$_SESSION['INSURANCE']['TVDI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[0].benchMarkPremium"]=$_SESSION['INSURANCE']['TVDI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[0].netPremium"]=$_SESSION['INSURANCE']['TVDI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[0].taxPremium"]=$_SESSION['INSURANCE']['TVDI']['TAX'];
                                    break;
                                case 'TTBLI':
                                    $data["prpCitemKindsTemp[2].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[2].premium"]=$_SESSION['INSURANCE']['TTBLI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[2].benchMarkPremium"]=$_SESSION['INSURANCE']['TTBLI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[2].netPremium"]=$_SESSION['INSURANCE']['TTBLI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[2].taxPremium"]=$_SESSION['INSURANCE']['TTBLI']['TAX'];
                                    break;
                                case 'TWCDMVI':
                                    $data["prpCitemKindsTemp[1].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[1].premium"]=$_SESSION['INSURANCE']['TWCDMVI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[1].benchMarkPremium"]=$_SESSION['INSURANCE']['TWCDMVI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[1].netPremium"]=$_SESSION['INSURANCE']['TWCDMVI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[1].taxPremium"]=$_SESSION['INSURANCE']['TWCDMVI']['TAX'];
                                    break;
                                case 'TCPLI_DRIVER':
                                    $data["prpCitemKindsTemp[3].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[3].premium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER']['PREMIUM'];
                                    $data["prpCitemKindsTemp[3].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER']['STANDARD_PREMIUM'];
                                     $data["prpCitemKindsTemp[3].netPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[3].taxPremium"] =$_SESSION['INSURANCE']['TCPLI_DRIVER']['TAX'];
                                    break;
                                case 'TCPLI_PASSENGER':
                                    $data["prpCitemKindsTemp[4].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[4].premium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['PREMIUM'];
                                    $data["prpCitemKindsTemp[4].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[4].netPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[4].taxPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER']['TAX'];
                                    break;
                                case 'BSDI':
                                    $data["prpCitemKindsTemp[5].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[5].premium"]=$_SESSION['INSURANCE']['BSDI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[5].benchMarkPremium"]=$_SESSION['INSURANCE']['BSDI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[5].netPremium"]=$_SESSION['INSURANCE']['BSDI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[5].taxPremium"]=$_SESSION['INSURANCE']['BSDI']['TAX'];

                                    break;
                                case 'BGAI':
                                    $data["prpCitemKindsTemp[6].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[6].premium"]=$_SESSION['INSURANCE']['BGAI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[6].benchMarkPremium"]=$_SESSION['INSURANCE']['BGAI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[6].netPremium"]=$_SESSION['INSURANCE']['BGAI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[6].taxPremium"]=$_SESSION['INSURANCE']['BGAI']['TAX'];
                                    break;
                                case 'STSFS':
                                    $data["prpCitemKindsTemp[7].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[7].premium"]=$_SESSION['INSURANCE']['STSFS']['PREMIUM'];
                                    $data["prpCitemKindsTemp[7].benchMarkPremium"]=$_SESSION['INSURANCE']['STSFS']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[7].netPremium"]=$_SESSION['INSURANCE']['STSFS']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[7].taxPremium"]=$_SESSION['INSURANCE']['STSFS']['TAX'];
                                    break;
                                case 'VWTLI':
                                    $data["prpCitemKindsTemp[23].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[23].premium"]=$_SESSION['INSURANCE']['VWTLI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[23].benchMarkPremium"]=$_SESSION['INSURANCE']['VWTLI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[23].netPremium"]=$_SESSION['INSURANCE']['VWTLI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[23].taxPremium"]=$_SESSION['INSURANCE']['VWTLI']['TAX'];
                                    break;
                                case 'NIELI':
                                    $data["prpCitemKindsTemp[8].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[8].premium"]=$_SESSION['INSURANCE']['NIELI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[8].benchMarkPremium"]=$_SESSION['INSURANCE']['NIELI']['STANDARD_PREMIUM'];
                                    $data["prpCengageTemps[0].id.serialNo"]="1";
                                    $data["prpCengageTemps[0].clauseCode"]="000034";
                                    $data["prpCengageTemps[0].clauseName"]="新增设备特别约定";
                                    $data["clauses[0]"]="投保新增设备，详见《新增设备明细表》。";
                                    $data["prpCengageTemps[0].maxCount"]="";
                                    $data["prpCengageTemps[0].clauses"]="投保新增设备，详见《新增设备明细表》。";
                                    $data["prpCengageTemps[0].flag"]="";
                                    $data["prpCengageTemps[0].engageFlag"]="0";
                                    $data["prpCitemKindsTemp[8].netPremium"]=$_SESSION['INSURANCE']['NIELI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[8].taxPremium"]=$_SESSION['INSURANCE']['NIELI']['TAX'];
                                    break;
                                case 'SLOI':
                                    $data["prpCitemKindsTemp[9].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[9].premium"]=$_SESSION['INSURANCE']['SLOI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[9].benchMarkPremium"]=$_SESSION['INSURANCE']['SLOI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[9].netPremium"]=$_SESSION['INSURANCE']['SLOI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[9].taxPremium"]=$_SESSION['INSURANCE']['SLOI']['TAX'];
                                    break;
                                case 'RDCCI':
                                    $data["prpCitemKindsTemp[10].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[10].premium"]=$_SESSION['INSURANCE']['RDCCI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[10].benchMarkPremium"]=$_SESSION['INSURANCE']['RDCCI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[10].netPremium"]=$_SESSION['INSURANCE']['RDCCI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[10].taxPremium"]=$_SESSION['INSURANCE']['RDCCI']['TAX'];
                                    break;
                                case 'MVLINFTPSI':
                                    $data["prpCitemKindsTemp[11].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[11].premium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[11].benchMarkPremium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[11].netPremium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[11].taxPremium"]=$_SESSION['INSURANCE']['MVLINFTPSI']['TAX'];
                                    break;
                                case 'LIDI':
                                    $data["prpCitemKindsTemp[20].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[20].premium"]=$value;

                                    break;
                                case 'TVDI_NDSI':
                                    $data["prpCitemKindsTemp[13].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[0].specialFlag"]="on";
                                    $data["prpCitemKindsTemp[13].premium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[13].benchMarkPremium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[13].netPremium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[13].taxPremium"]=$_SESSION['INSURANCE']['TVDI_NDSI']['TAX'];
                                    break;
                                case 'TTBLI_NDSI':
                                    $data["prpCitemKindsTemp[2].specialFlag"]="on";
                                    $data["prpCitemKindsTemp[12].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[12].premium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[12].benchMarkPremium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[12].netPremium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[12].taxPremium"]=$_SESSION['INSURANCE']['TTBLI_NDSI']['TAX'];
                                    break;
                                case 'TWCDMVI_NDSI':
                                    $data["prpCitemKindsTemp[1].specialFlag"]="on";
                                    $data["prpCitemKindsTemp[15].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[15].premium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[15].benchMarkPremium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[15].netPremium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[15].taxPremium"]=$_SESSION['INSURANCE']['TWCDMVI_NDSI']['TAX'];
                                    break;
                                case 'TCPLI_DRIVER_NDSI':
                                    $data["prpCitemKindsTemp[3].specialFlag"]="on";
                                    $data["prpCitemKindsTemp[16].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[16].premium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[16].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[16].netPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[16].taxPremium"]=$_SESSION['INSURANCE']['TCPLI_DRIVER_NDSI']['TAX'];
                                    break;
                                case 'TCPLI_PASSENGER_NDSI':
                                    $data["prpCitemKindsTemp[4].specialFlag"]="on";
                                    $data["prpCitemKindsTemp[17].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[17].premium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[17].benchMarkPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[17].netPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[17].taxPremium"]=$_SESSION['INSURANCE']['TCPLI_PASSENGER_NDSI']['TAX'];
                                    break;
                                case 'BSDI_NDSI':
                                    $data["prpCitemKindsTemp[5].specialFlag"]="on";
                                    $data["prpCitemKindsTemp[20].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[20].premium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[20].benchMarkPremium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[20].netPremium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[20].taxPremium"]=$_SESSION['INSURANCE']['BSDI_NDSI']['TAX'];
                                    break;
                                case 'SLOI_NDSI':
                                    $data["prpCitemKindsTemp[9].specialFlag"]="on";
                                    $data["prpCitemKindsTemp[18].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[18].premium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[18].benchMarkPremium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[18].netPremium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[18].taxPremium"]=$_SESSION['INSURANCE']['SLOI_NDSI']['TAX'];
                                    break;
                                case 'VWTLI_NDSI':
                                    $data["prpCitemKindsTemp[23].specialFlag"]="on";
                                    $data["prpCitemKindsTemp[22].chooseFlag"]="on";
                                    $data["prpCitemKindsTemp[22].premium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['PREMIUM'];
                                    $data["prpCitemKindsTemp[22].benchMarkPremium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['STANDARD_PREMIUM'];
                                    $data["prpCitemKindsTemp[22].netPremium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['NET_PREMIUM'];
                                    $data["prpCitemKindsTemp[22].taxPremium"]=$_SESSION['INSURANCE']['VWTLI_NDSI']['TAX'];
                                    break;
                                case 'LIDI_NDSI':
                                    //$data["prpCitemKindsTemp[20].specialFlag"]="on";
                                    //$data["prpCitemKindsTemp[21].chooseFlag"]="on";
                                case 'NIELI_NDSI':
                                $data["prpCitemKindsTemp[8].specialFlag"]="on";
                                $data["prpCitemKindsTemp[19].chooseFlag"]="on";
                                $data["prpCitemKindsTemp[19].premium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['PREMIUM'];
                                $data["prpCitemKindsTemp[19].benchMarkPremium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['STANDARD_PREMIUM'];
                                $data["prpCitemKindsTemp[19].netPremium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['NET_PREMIUM'];
                                $data["prpCitemKindsTemp[19].taxPremium"]=$_SESSION['INSURANCE']['NIELI_NDSI']['TAX'];
                                break;
                            }
                }







        $data["prpCitemKindsTemp[0].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[0].dutyFlag"]="2";
        $data["prpCitemKindsTemp[0].min"]="";
        $data["prpCitemKindsTemp[0].max"]="";
        $data["prpCitemKindsTemp[0].itemKindNo"]="1";
        $data["prpCitemKindsTemp[0].clauseCode"]="050051";
        $data["prpCitemKindsTemp[0].kindCode"]="050202";
        $data["prpCitemKindsTemp[0].kindName"]="机动车损失保险";
        $data["prpCitemKindsTemp[0].deductible"]="0.00";
        $data["prpCitemKindsTemp[0].deductibleRate"]="0.0000";
        $data["prpCitemKindsTemp[0].amount"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
        $data["prpCitemKindsTemp[0].calculateFlag"]="Y";
        $data["prpCitemKindsTemp[0].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[0].startHour"]="0";
        $data["prpCitemKindsTemp[0].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[0].endHour"]="24";
        $data["relateSpecial[0]"]="050930";
        $data["prpCitemKindsTemp[0].flag"]="1001000";
        $data["prpCitemKindsTemp[0].basePremium"]="0.00";
        $data["prpCitemKindsTemp[0].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[0].rate"]="";
        $data["prpCitemKindsTemp[0].disCount"]=$_SESSION['DISCOUNT'];


        $data["prpCprofitDetailsTemp[0].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[0].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[0].condition"]="自主核保优惠系数";
        $data["profitRateTemp[0]"]="85.00000000";
        $data["prpCprofitDetailsTemp[0].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[0].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[0].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[0].kindCode"]="050202";
        $data["prpCprofitDetailsTemp[0].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[0].flag"]="";
        $data["prpCprofitDetailsTemp[0].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[0].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[0].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[0].id.itemKindNo"]="1";
        $data["prpCprofitDetailsTemp[0].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[1].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[1].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[1].condition"]="新保或上年赔款次数在2次以下";
        $data["profitRateTemp[1]"]="1.0000000";
        $data["prpCprofitDetailsTemp[1].profitRate"]="100.000000";
        $data["prpCprofitDetailsTemp[1].profitRateMin"]="100.000000";
        $data["prpCprofitDetailsTemp[1].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[1].kindCode"]="050202";
        $data["prpCprofitDetailsTemp[1].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[1].flag"]="";
        $data["prpCprofitDetailsTemp[1].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[1].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[1].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[1].id.itemKindNo"]="1";
        $data["prpCprofitDetailsTemp[1].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[2].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[2].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[2].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[2]"]="0.85000000";
        $data["prpCprofitDetailsTemp[2].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[2].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[2].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[2].kindCode"]="050202";
        $data["prpCprofitDetailsTemp[2].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[2].flag"]="";
        $data["prpCprofitDetailsTemp[2].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[2].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[2].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[2].id.itemKindNo"]="1";
        $data["prpCprofitDetailsTemp[2].id.serialNo"]="0";
        $data["prpCitemKindsTemp[1].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[1].dutyFlag"]="2";
        $data["prpCitemKindsTemp[1].min"]="";
        $data["prpCitemKindsTemp[1].max"]="";
        $data["prpCitemKindsTemp[1].itemKindNo"]="2";
        $data["prpCitemKindsTemp[1].clauseCode"]="050054";
        $data["prpCitemKindsTemp[1].kindCode"]="050501";
        $data["prpCitemKindsTemp[1].kindName"]="盗抢险";
        $data["prpCitemKindsTemp[1].unitAmount"]="0.00";
        $data["prpCitemKindsTemp[1].quantity"]="";
        $data["prpCitemKindsTemp[1].amount"]=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
        $data["prpCitemKindsTemp[1].calculateFlag"]="N";
        $data["prpCitemKindsTemp[1].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[1].startHour"]="0";
        $data["prpCitemKindsTemp[1].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[1].endHour"]="24";
        $data["relateSpecial[1]"]="050932";
        $data["prpCitemKindsTemp[1].flag"]="1001000";
        $data["prpCitemKindsTemp[1].basePremium"]="78.00";
        $data["prpCitemKindsTemp[1].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[1].rate"]="0.3185";
        $data["prpCitemKindsTemp[1].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCprofitDetailsTemp[3].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[3].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[3].condition"]="自主核保优惠系数";
        $data["profitRateTemp[3]"]="0.85000000";
        $data["prpCprofitDetailsTemp[3].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[3].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[3].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[3].kindCode"]="050501";
        $data["prpCprofitDetailsTemp[3].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[3].flag"]="";
        $data["prpCprofitDetailsTemp[3].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[3].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[3].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[3].id.itemKindNo"]="2";
        $data["prpCprofitDetailsTemp[3].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[4].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[4].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[4].condition"]="新保或上年赔款次数在2次以下";
        $data["profitRateTemp[4]"]="1.0000000";
        $data["prpCprofitDetailsTemp[4].profitRate"]="100.000000";
        $data["prpCprofitDetailsTemp[4].profitRateMin"]="100.000000";
        $data["prpCprofitDetailsTemp[4].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[4].kindCode"]="050501";
        $data["prpCprofitDetailsTemp[4].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[4].flag"]="";
        $data["prpCprofitDetailsTemp[4].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[4].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[4].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[4].id.itemKindNo"]="2";
        $data["prpCprofitDetailsTemp[4].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[5].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[5].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[5].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[5]"]="0.85000000";
        $data["prpCprofitDetailsTemp[5].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[5].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[5].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[5].kindCode"]="050501";
        $data["prpCprofitDetailsTemp[5].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[5].flag"]="";
        $data["prpCprofitDetailsTemp[5].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[5].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[5].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[5].id.itemKindNo"]="2";
        $data["prpCprofitDetailsTemp[5].id.serialNo"]="0";
        $data["prpCitemKindsTemp[2].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[2].dutyFlag"]="2";
        $data["prpCitemKindsTemp[2].min"]="";
        $data["prpCitemKindsTemp[2].max"]="";
        $data["prpCitemKindsTemp[2].itemKindNo"]="3";
        $data["prpCitemKindsTemp[2].clauseCode"]="050052";
        $data["prpCitemKindsTemp[2].kindCode"]="050602";
        $data["prpCitemKindsTemp[2].kindName"]="第三者责任保险";
        $data["prpCitemKindsTemp[2].unitAmount"]="0.00";
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
        $data["prpCitemKindsTemp[2].calculateFlag"]="Y";
        $data["prpCitemKindsTemp[2].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[2].startHour"]="0";
        $data["prpCitemKindsTemp[2].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[2].endHour"]="24";
        $data["relateSpecial[2]"]="050931";
        $data["prpCitemKindsTemp[2].flag"]="1001000";
        $data["prpCitemKindsTemp[2].basePremium"]="0.00";
        $data["prpCitemKindsTemp[2].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[2].rate"]="";
        $data["prpCitemKindsTemp[2].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCprofitDetailsTemp[6].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[6].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[6].condition"]="自主核保优惠系数";
        $data["profitRateTemp[6]"]="0.85000000";
        $data["prpCprofitDetailsTemp[6].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[6].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[6].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[6].kindCode"]="050602";
        $data["prpCprofitDetailsTemp[6].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[6].flag"]="";
        $data["prpCprofitDetailsTemp[6].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[6].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[6].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[6].id.itemKindNo"]="3";
        $data["prpCprofitDetailsTemp[6].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[7].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[7].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[7].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[7]"]="0.60000000";
        $data["prpCprofitDetailsTemp[7].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[7].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[7].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[7].kindCode"]="050602";
        $data["prpCprofitDetailsTemp[7].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[7].flag"]="";
        $data["prpCprofitDetailsTemp[7].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[7].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[7].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[7].id.itemKindNo"]="3";
        $data["prpCprofitDetailsTemp[7].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[8].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[8].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[8].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[8]"]="0.85000000";
        $data["prpCprofitDetailsTemp[8].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[8].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[8].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[8].kindCode"]="050602";
        $data["prpCprofitDetailsTemp[8].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[8].flag"]="";
        $data["prpCprofitDetailsTemp[8].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[8].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[8].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[8].id.itemKindNo"]="3";
        $data["prpCprofitDetailsTemp[8].id.serialNo"]="0";
        $data["prpCitemKindsTemp[3].min"]="";
        $data["prpCitemKindsTemp[3].max"]="";
        $data["prpCitemKindsTemp[3].itemKindNo"]="4";
        $data["prpCitemKindsTemp[3].clauseCode"]="050053";
        $data["prpCitemKindsTemp[3].kindCode"]="050711";
        $data["prpCitemKindsTemp[3].kindName"]="车上人员责任险（司机）";
        $data["prpCitemKindsTemp[3].unitAmount"]="0.00";
        $data["prpCitemKindsTemp[3].quantity"]="";
        $data["prpCitemKindsTemp[3].amount"]=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
        $data["prpCitemKindsTemp[3].calculateFlag"]="Y";
        $data["prpCitemKindsTemp[3].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[3].startHour"]="0";
        $data["prpCitemKindsTemp[3].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[3].endHour"]="24";
        $data["relateSpecial[3]"]="050933";
        $data["prpCitemKindsTemp[3].flag"]="1001000";
        $data["prpCitemKindsTemp[3].basePremium"]="0.00";
        $data["prpCitemKindsTemp[3].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[3].rate"]="";
        $data["prpCitemKindsTemp[3].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[3].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[3].dutyFlag"]="2";
        $data["prpCprofitDetailsTemp[9].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[9].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[9].condition"]="自主核保优惠系数";
        $data["profitRateTemp[9]"]="0.85000000";
        $data["prpCprofitDetailsTemp[9].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[9].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[9].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[9].kindCode"]="050711";
        $data["prpCprofitDetailsTemp[9].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[9].flag"]="";
        $data["prpCprofitDetailsTemp[9].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[9].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[9].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[9].id.itemKindNo"]="4";
        $data["prpCprofitDetailsTemp[9].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[10].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[10].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[10].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[10]"]="0.60000000";
        $data["prpCprofitDetailsTemp[10].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[10].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[10].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[10].kindCode"]="050711";
        $data["prpCprofitDetailsTemp[10].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[10].flag"]="";
        $data["prpCprofitDetailsTemp[10].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[10].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[10].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[10].id.itemKindNo"]="4";
        $data["prpCprofitDetailsTemp[10].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[11].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[11].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[11].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[11]"]="0.85000000";
        $data["prpCprofitDetailsTemp[11].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[11].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[11].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[11].kindCode"]="050711";
        $data["prpCprofitDetailsTemp[11].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[11].flag"]="";
        $data["prpCprofitDetailsTemp[11].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[11].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[11].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[11].id.itemKindNo"]="4";
        $data["prpCprofitDetailsTemp[11].id.serialNo"]="0";
        $data["prpCitemKindsTemp[4].min"]="";
        $data["prpCitemKindsTemp[4].max"]="";
        $data["prpCitemKindsTemp[4].itemKindNo"]="5";
        $data["prpCitemKindsTemp[4].clauseCode"]="050053";
        $data["prpCitemKindsTemp[4].kindCode"]="050712";
        $data["prpCitemKindsTemp[4].kindName"]="车上人员责任险（乘客）";
        $data["prpCitemKindsTemp[4].unitAmount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
        $data["prpCitemKindsTemp[4].quantity"]=$business['POLICY']['TCPLI_PASSENGER_COUNT'];
        $data["prpCitemKindsTemp[4].amount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']*$business['POLICY']['TCPLI_PASSENGER_COUNT'];
        $data["prpCitemKindsTemp[4].calculateFlag"]="Y";
        $data["prpCitemKindsTemp[4].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[4].startHour"]="0";
        $data["prpCitemKindsTemp[4].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[4].endHour"]="24";
        $data["relateSpecial[4]"]="050934";
        $data["prpCitemKindsTemp[4].flag"]="1001000";
        $data["prpCitemKindsTemp[4].basePremium"]="0.00";
        $data["prpCitemKindsTemp[4].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[4].rate"]="0.1690";
        $data["prpCitemKindsTemp[4].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[4].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[4].dutyFlag"]="2";
        $data["prpCprofitDetailsTemp[12].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[12].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[12].condition"]="自主核保优惠系数";
        $data["profitRateTemp[12]"]="0.85000000";
        $data["prpCprofitDetailsTemp[12].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[12].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[12].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[12].kindCode"]="050712";
        $data["prpCprofitDetailsTemp[12].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[12].flag"]="";
        $data["prpCprofitDetailsTemp[12].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[12].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[12].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[12].id.itemKindNo"]="5";
        $data["prpCprofitDetailsTemp[12].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[13].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[13].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[13].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[13]"]="0.60000000";
        $data["prpCprofitDetailsTemp[13].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[13].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[13].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[13].kindCode"]="050712";
        $data["prpCprofitDetailsTemp[13].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[13].flag"]="";
        $data["prpCprofitDetailsTemp[13].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[13].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[13].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[13].id.itemKindNo"]="5";
        $data["prpCprofitDetailsTemp[13].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[14].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[14].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[14].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[14]"]="0.85000000";
        $data["prpCprofitDetailsTemp[14].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[14].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[14].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[14].kindCode"]="050712";
        $data["prpCprofitDetailsTemp[14].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[14].flag"]="";
        $data["prpCprofitDetailsTemp[14].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[14].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[14].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[14].id.itemKindNo"]="5";
        $data["prpCprofitDetailsTemp[14].id.serialNo"]="0";



        $data["prpCitemKindsTemp[5].min"]="";
        $data["prpCitemKindsTemp[5].max"]="";
        $data["prpCitemKindsTemp[5].itemKindNo"]="6";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[5].clauseCode"]="050059";
        $data["prpCitemKindsTemp[5].kindCode"]="050211";
        $data["relateSpecial[5]"]="050937";
        $data["prpCitemKindsTemp[5].kindName"]="车身划痕损失险";
        $data["prpCitemKindsTemp[5].amount"]=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
        $data["prpCitemKindsTemp[5].calculateFlag"]="N";
        $data["prpCitemKindsTemp[5].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[5].startHour"]="0";
        $data["prpCitemKindsTemp[5].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[5].endHour"]="24";
        $data["prpCitemKindsTemp[5].flag"]="2001000";
        $data["prpCitemKindsTemp[5].basePremium"]="0.00";
        $data["prpCitemKindsTemp[5].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[5].rate"]="";
        $data["prpCitemKindsTemp[5].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[5].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[5].dutyFlag"]="2";



        $data["prpCprofitDetailsTemp[15].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[15].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[15].condition"]="自主核保优惠系数";
        $data["profitRateTemp[15]"]="0.85000000";
        $data["prpCprofitDetailsTemp[15].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[15].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[15].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[15].kindCode"]="050211";
        $data["prpCprofitDetailsTemp[15].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[15].flag"]="";
        $data["prpCprofitDetailsTemp[15].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[15].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[15].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[15].id.itemKindNo"]="6";
        $data["prpCprofitDetailsTemp[15].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[16].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[16].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[16].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[16]"]="0.60000000";
        $data["prpCprofitDetailsTemp[16].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[16].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[16].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[16].kindCode"]="050211";
        $data["prpCprofitDetailsTemp[16].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[16].flag"]="";
        $data["prpCprofitDetailsTemp[16].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[16].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[16].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[16].id.itemKindNo"]="6";
        $data["prpCprofitDetailsTemp[16].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[17].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[17].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[17].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[17]"]="0.85000000";
        $data["prpCprofitDetailsTemp[17].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[17].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[17].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[17].kindCode"]="050211";
        $data["prpCprofitDetailsTemp[17].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[17].flag"]="";
        $data["prpCprofitDetailsTemp[17].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[17].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[17].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[17].id.itemKindNo"]="6";
        $data["prpCprofitDetailsTemp[17].id.serialNo"]="0";



        $data["prpCitemKindsTemp[6].min"]="";
        $data["prpCitemKindsTemp[6].max"]="";
        $data["prpCitemKindsTemp[6].itemKindNo"]="7";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[6].clauseCode"]="050056";
        $data["prpCitemKindsTemp[6].kindCode"]="050232";
        $data["relateSpecial[6]"]="";
        $data["prpCitemKindsTemp[6].kindName"]="玻璃单独破碎险";
        $data["prpCitemKindsTemp[6].modeCode"]="10";
        $data["prpCitemKindsTemp[6].amount"]="0.00";
        $data["prpCitemKindsTemp[6].calculateFlag"]="N";
        $data["prpCitemKindsTemp[6].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[6].startHour"]="0";
        $data["prpCitemKindsTemp[6].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[6].endHour"]="24";
        $data["prpCitemKindsTemp[6].flag"]="2000000";
        $data["prpCitemKindsTemp[6].basePremium"]="0.00";
        $data["prpCitemKindsTemp[6].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[6].rate"]="0.1235";
        $data["prpCitemKindsTemp[6].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[6].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[6].dutyFlag"]="2";




        $data["prpCprofitDetailsTemp[18].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[18].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[18].condition"]="自主核保优惠系数";
        $data["profitRateTemp[18]"]="0.85000000";
        $data["prpCprofitDetailsTemp[18].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[18].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[18].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[18].kindCode"]="050232";
        $data["prpCprofitDetailsTemp[18].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[18].flag"]="";
        $data["prpCprofitDetailsTemp[18].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[18].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[18].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[18].id.itemKindNo"]="7";
        $data["prpCprofitDetailsTemp[18].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[19].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[19].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[19].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[19]"]="0.60000000";
        $data["prpCprofitDetailsTemp[19].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[19].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[19].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[19].kindCode"]="050232";
        $data["prpCprofitDetailsTemp[19].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[19].flag"]="";
        $data["prpCprofitDetailsTemp[19].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[19].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[19].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[19].id.itemKindNo"]="7";
        $data["prpCprofitDetailsTemp[19].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[20].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[20].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[20].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[20]"]="0.85000000";
        $data["prpCprofitDetailsTemp[20].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[20].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[20].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[20].kindCode"]="050232";
        $data["prpCprofitDetailsTemp[20].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[20].flag"]="";
        $data["prpCprofitDetailsTemp[20].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[20].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[20].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[20].id.itemKindNo"]="7";
        $data["prpCprofitDetailsTemp[20].id.serialNo"]="0";




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




        $data["prpCprofitDetailsTemp[21].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[21].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[21].condition"]="自主核保优惠系数";
        $data["profitRateTemp[21]"]="0.85000000";
        $data["prpCprofitDetailsTemp[21].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[21].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[21].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[21].kindCode"]="050253";
        $data["prpCprofitDetailsTemp[21].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[21].flag"]="";
        $data["prpCprofitDetailsTemp[21].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[21].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[21].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[21].id.itemKindNo"]="8";
        $data["prpCprofitDetailsTemp[21].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[22].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[22].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[22].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[22]"]="0.60000000";
        $data["prpCprofitDetailsTemp[22].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[22].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[22].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[22].kindCode"]="050253";
        $data["prpCprofitDetailsTemp[22].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[22].flag"]="";
        $data["prpCprofitDetailsTemp[22].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[22].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[22].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[22].id.itemKindNo"]="8";
        $data["prpCprofitDetailsTemp[22].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[23].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[23].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[23].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[23]"]="0.85000000";
        $data["prpCprofitDetailsTemp[23].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[23].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[23].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[23].kindCode"]="050253";
        $data["prpCprofitDetailsTemp[23].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[23].flag"]="";
        $data["prpCprofitDetailsTemp[23].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[23].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[23].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[23].id.itemKindNo"]="8";
        $data["prpCprofitDetailsTemp[23].id.serialNo"]="0";




        $data["prpCitemKindsTemp[8].min"]="";
        $data["prpCitemKindsTemp[8].max"]="";
        $data["prpCitemKindsTemp[8].itemKindNo"]="9";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[8].clauseCode"]="050058";
        $data["prpCitemKindsTemp[8].kindCode"]="050261";
        $data["relateSpecial[8]"]="050936";
        $data["prpCitemKindsTemp[8].kindName"]="新增设备损失险";
        $data["prpCitemKindsTemp[8].amount"]=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
        $data["prpCitemKindsTemp[8].calculateFlag"]="N";
        $data["prpCitemKindsTemp[8].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[8].startHour"]="0";
        $data["prpCitemKindsTemp[8].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[8].endHour"]="24";
        $data["prpCitemKindsTemp[8].flag"]="2001000";
        $data["prpCitemKindsTemp[8].basePremium"]="0.00";
        $data["prpCitemKindsTemp[8].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[8].rate"]="1.2968";
        $data["prpCitemKindsTemp[8].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[8].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[8].dutyFlag"]="2";


        $data["prpCprofitDetailsTemp[24].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[24].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[24].condition"]="自主核保优惠系数";
        $data["profitRateTemp[24]"]="0.85000000";
        $data["prpCprofitDetailsTemp[24].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[24].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[24].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[24].kindCode"]="050261";
        $data["prpCprofitDetailsTemp[24].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[24].flag"]="";
        $data["prpCprofitDetailsTemp[24].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[24].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[24].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[24].id.itemKindNo"]="9";
        $data["prpCprofitDetailsTemp[24].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[25].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[25].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[25].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[25]"]="0.60000000";
        $data["prpCprofitDetailsTemp[25].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[25].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[25].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[25].kindCode"]="050261";
        $data["prpCprofitDetailsTemp[25].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[25].flag"]="";
        $data["prpCprofitDetailsTemp[25].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[25].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[25].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[25].id.itemKindNo"]="9";
        $data["prpCprofitDetailsTemp[25].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[26].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[26].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[26].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[26]"]="0.85000000";
        $data["prpCprofitDetailsTemp[26].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[26].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[26].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[26].kindCode"]="050261";
        $data["prpCprofitDetailsTemp[26].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[26].flag"]="";
        $data["prpCprofitDetailsTemp[26].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[26].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[26].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[26].id.itemKindNo"]="9";
        $data["prpCprofitDetailsTemp[26].id.serialNo"]="0";



        $data["prpCitemKindsTemp[9].min"]="";
        $data["prpCitemKindsTemp[9].max"]="";
        $data["prpCitemKindsTemp[9].itemKindNo"]="10";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[9].clauseCode"]="050057";
        $data["prpCitemKindsTemp[9].kindCode"]="050311";
        $data["relateSpecial[9]"]="050935";
        $data["prpCitemKindsTemp[9].kindName"]="自燃损失险";
        $data["prpCitemKindsTemp[9].amount"]=$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
        $data["prpCitemKindsTemp[9].calculateFlag"]="N";
        $data["prpCitemKindsTemp[9].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[9].startHour"]="0";
        $data["prpCitemKindsTemp[9].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[9].endHour"]="24";
        $data["prpCitemKindsTemp[9].flag"]="2001000";
        $data["prpCitemKindsTemp[9].basePremium"]="0.00";
        $data["prpCitemKindsTemp[9].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[9].rate"]="0.0780";
        $data["prpCitemKindsTemp[9].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[9].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[9].dutyFlag"]="2";


        $data["prpCprofitDetailsTemp[27].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[27].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[27].condition"]="自主核保优惠系数";
        $data["profitRateTemp[27]"]="0.85000000";
        $data["prpCprofitDetailsTemp[27].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[27].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[27].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[27].kindCode"]="050311";
        $data["prpCprofitDetailsTemp[27].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[27].flag"]="";
        $data["prpCprofitDetailsTemp[27].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[27].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[27].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[27].id.itemKindNo"]="10";
        $data["prpCprofitDetailsTemp[27].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[28].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[28].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[28].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[28]"]="0.60000000";
        $data["prpCprofitDetailsTemp[28].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[28].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[28].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[28].kindCode"]="050311";
        $data["prpCprofitDetailsTemp[28].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[28].flag"]="";
        $data["prpCprofitDetailsTemp[28].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[28].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[28].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[28].id.itemKindNo"]="10";
        $data["prpCprofitDetailsTemp[28].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[29].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[29].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[29].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[29]"]="0.85000000";
        $data["prpCprofitDetailsTemp[29].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[29].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[29].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[29].kindCode"]="050311";
        $data["prpCprofitDetailsTemp[29].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[29].flag"]="";
        $data["prpCprofitDetailsTemp[29].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[29].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[29].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[29].id.itemKindNo"]="10";
        $data["prpCprofitDetailsTemp[29].id.serialNo"]="0";



        $data["prpCitemKindsTemp[10].min"]="";
        $data["prpCitemKindsTemp[10].max"]="";
        $data["prpCitemKindsTemp[10].itemKindNo"]="11";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[10].clauseCode"]="050061";
        $data["prpCitemKindsTemp[10].kindCode"]="050441";
        $data["relateSpecial[10]"]="";
        $data["prpCitemKindsTemp[10].kindName"]="修理期间费用补偿险";
        $data["prpCitemKindsTemp[10].unitAmount"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
        $data["prpCitemKindsTemp[10].quantity"]=$business['POLICY']['RDCCI_INSURANCE_UNIT'];
        $data["prpCitemKindsTemp[10].amount"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY']*$business['POLICY']['RDCCI_INSURANCE_UNIT'];
        $data["prpCitemKindsTemp[10].calculateFlag"]="N";
        $data["prpCitemKindsTemp[10].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[10].startHour"]="0";
        $data["prpCitemKindsTemp[10].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[10].endHour"]="24";
        $data["prpCitemKindsTemp[10].flag"]="2000000";
        $data["prpCitemKindsTemp[10].basePremium"]="0.00";
        $data["prpCitemKindsTemp[10].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[10].rate"]="6.5000";
        $data["prpCitemKindsTemp[10].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[10].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[10].dutyFlag"]="2";



        $data["prpCprofitDetailsTemp[30].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[30].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[30].condition"]="自主核保优惠系数";
        $data["profitRateTemp[30]"]="0.85000000";
        $data["prpCprofitDetailsTemp[30].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[30].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[30].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[30].kindCode"]="050441";
        $data["prpCprofitDetailsTemp[30].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[30].flag"]="";
        $data["prpCprofitDetailsTemp[30].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[30].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[30].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[30].id.itemKindNo"]="11";
        $data["prpCprofitDetailsTemp[30].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[31].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[31].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[31].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[31]"]="0.60000000";
        $data["prpCprofitDetailsTemp[31].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[31].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[31].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[31].kindCode"]="050441";
        $data["prpCprofitDetailsTemp[31].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[31].flag"]="";
        $data["prpCprofitDetailsTemp[31].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[31].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[31].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[31].id.itemKindNo"]="11";
        $data["prpCprofitDetailsTemp[31].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[32].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[32].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[32].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[32]"]="0.85000000";
        $data["prpCprofitDetailsTemp[32].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[32].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[32].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[32].kindCode"]="050441";
        $data["prpCprofitDetailsTemp[32].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[32].flag"]="";
        $data["prpCprofitDetailsTemp[32].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[32].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[32].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[32].id.itemKindNo"]="11";
        $data["prpCprofitDetailsTemp[32].id.serialNo"]="0";

        $data["prpCitemKindsTemp[11].min"]="";
        $data["prpCitemKindsTemp[11].max"]="";
        $data["prpCitemKindsTemp[11].itemKindNo"]="12";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[11].clauseCode"]="050064";
        $data["prpCitemKindsTemp[11].kindCode"]="050451";
        $data["relateSpecial[11]"]="";
        $data["prpCitemKindsTemp[11].kindName"]="机动车损失保险无法找到第三方特约险";
        $data["prpCitemKindsTemp[11].amount"]="0.00";
        $data["prpCitemKindsTemp[11].calculateFlag"]="N";
        $data["prpCitemKindsTemp[11].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[11].startHour"]="0";
        $data["prpCitemKindsTemp[11].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[11].endHour"]="24";
        $data["prpCitemKindsTemp[11].flag"]="2000000";
        $data["prpCitemKindsTemp[11].basePremium"]="0.00";
        $data["prpCitemKindsTemp[11].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[11].rate"]="2.5000";
        $data["prpCitemKindsTemp[11].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[11].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[11].dutyFlag"]="2";


        $data["prpCprofitDetailsTemp[33].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[33].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[33].condition"]="自主核保优惠系数";
        $data["profitRateTemp[33]"]="0.85000000";
        $data["prpCprofitDetailsTemp[33].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[33].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[33].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[33].kindCode"]="050451";
        $data["prpCprofitDetailsTemp[33].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[33].flag"]="";
        $data["prpCprofitDetailsTemp[33].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[33].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[33].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[33].id.itemKindNo"]="12";
        $data["prpCprofitDetailsTemp[33].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[34].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[34].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[34].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[34]"]="0.60000000";
        $data["prpCprofitDetailsTemp[34].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[34].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[34].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[34].kindCode"]="050451";
        $data["prpCprofitDetailsTemp[34].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[34].flag"]="";
        $data["prpCprofitDetailsTemp[34].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[34].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[34].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[34].id.itemKindNo"]="12";
        $data["prpCprofitDetailsTemp[34].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[35].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[35].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[35].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[35]"]="0.85000000";
        $data["prpCprofitDetailsTemp[35].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[35].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[35].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[35].kindCode"]="050451";
        $data["prpCprofitDetailsTemp[35].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[35].flag"]="";
        $data["prpCprofitDetailsTemp[35].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[35].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[35].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[35].id.itemKindNo"]="12";
        $data["prpCprofitDetailsTemp[35].id.serialNo"]="0";



        $data["prpCitemKindsTemp[23].min"]="";
        $data["prpCitemKindsTemp[23].max"]="";
        $data["prpCitemKindsTemp[23].itemKindNo"]="13";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[23].clauseCode"]="050060";
        $data["prpCitemKindsTemp[23].kindCode"]="050461";
        $data["relateSpecial[23]"]="050938";
        $data["prpCitemKindsTemp[23].kindName"]="发动机涉水损失险";
        $data["prpCitemKindsTemp[23].amount"]="0.00";
        $data["prpCitemKindsTemp[23].calculateFlag"]="N";
        $data["prpCitemKindsTemp[23].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[23].startHour"]="0";
        $data["prpCitemKindsTemp[23].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[23].endHour"]="24";
        $data["prpCitemKindsTemp[23].flag"]="2001000";
        $data["prpCitemKindsTemp[23].basePremium"]="0.00";
        $data["prpCitemKindsTemp[23].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[23].rate"]="5.0000";
        $data["prpCitemKindsTemp[23].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[23].netPremium"]="35.20";
        $data["prpCitemKindsTemp[23].taxPremium"]="2.11";
        $data["prpCitemKindsTemp[23].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[23].dutyFlag"]="2";
        $data["prpCitemKindsTemp[23].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[23].dutyFlag"]="2";


        $data["prpCprofitDetailsTemp[36].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[36].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[36].condition"]="自主核保优惠系数";
        $data["profitRateTemp[36]"]="0.85000000";
        $data["prpCprofitDetailsTemp[36].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[36].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[36].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[36].kindCode"]="050461";
        $data["prpCprofitDetailsTemp[36].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[36].flag"]="";
        $data["prpCprofitDetailsTemp[36].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[36].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[36].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[36].id.itemKindNo"]="13";
        $data["prpCprofitDetailsTemp[36].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[37].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[37].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[37].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[37]"]="0.60000000";
        $data["prpCprofitDetailsTemp[37].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[37].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[37].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[37].kindCode"]="050461";
        $data["prpCprofitDetailsTemp[37].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[37].flag"]="";
        $data["prpCprofitDetailsTemp[37].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[37].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[37].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[37].id.itemKindNo"]="13";
        $data["prpCprofitDetailsTemp[37].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[38].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[38].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[38].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[38]"]="0.85000000";
        $data["prpCprofitDetailsTemp[38].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[38].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[38].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[38].kindCode"]="050461";
        $data["prpCprofitDetailsTemp[38].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[38].flag"]="";
        $data["prpCprofitDetailsTemp[38].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[38].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[38].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[38].id.itemKindNo"]="13";
        $data["prpCprofitDetailsTemp[38].id.serialNo"]="0";

        $data["prpCitemKindsTemp.itemKindSpecialSumPremium"]="";
        $data["kindcodesub"]="";


        $data["prpCitemKindsTemp[13].kindName"]="不计免赔险（车损险）";
        $data["prpCitemKindsTemp[13].amount"]="0.00";
        $data["prpCitemKindsTemp[13].calculateFlag"]="N";
        $data["prpCitemKindsTemp[13].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[13].startHour"]="0";
        $data["prpCitemKindsTemp[13].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[13].endHour"]="24";
        $data["prpCitemKindsTemp[13].flag"]="2000000";
        $data["prpCitemKindsTemp[13].basePremium"]="0.00";
        $data["prpCitemKindsTemp[13].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[13].rate"]="15.0000";
        $data["prpCitemKindsTemp[13].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[13].clauseCode"]="050066";
        $data["prpCitemKindsTemp[13].kindCode"]="050930";
        $data["prpCitemKindsTemp[13].itemKindNo"]="14";
        $data["prpCitemKindsTemp[13].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[13].dutyFlag"]="2";



        $data["prpCprofitDetailsTemp[39].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[39].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[39].condition"]="自主核保优惠系数";
        $data["profitRateTemp[39]"]="0.85000000";
        $data["prpCprofitDetailsTemp[39].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[39].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[39].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[39].kindCode"]="050930";
        $data["prpCprofitDetailsTemp[39].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[39].flag"]="";
        $data["prpCprofitDetailsTemp[39].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[39].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[39].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[39].id.itemKindNo"]="14";
        $data["prpCprofitDetailsTemp[39].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[40].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[40].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[40].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[40]"]="0.60000000";
        $data["prpCprofitDetailsTemp[40].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[40].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[40].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[40].kindCode"]="050930";
        $data["prpCprofitDetailsTemp[40].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[40].flag"]="";
        $data["prpCprofitDetailsTemp[40].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[40].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[40].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[40].id.itemKindNo"]="14";
        $data["prpCprofitDetailsTemp[40].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[41].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[41].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[41].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[41]"]="0.85000000";
        $data["prpCprofitDetailsTemp[41].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[41].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[41].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[41].kindCode"]="050930";
        $data["prpCprofitDetailsTemp[41].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[41].flag"]="";
        $data["prpCprofitDetailsTemp[41].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[41].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[41].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[41].id.itemKindNo"]="14";
        $data["prpCprofitDetailsTemp[41].id.serialNo"]="0";




        $data["prpCitemKindsTemp[12].clauseCode"]="050066";
        $data["prpCitemKindsTemp[12].kindCode"]="050931";
        $data["prpCitemKindsTemp[12].itemKindNo"]="15";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[12].kindName"]="不计免赔险（三者险）";
        $data["prpCitemKindsTemp[12].amount"]="0.00";
        $data["prpCitemKindsTemp[12].calculateFlag"]="N";
        $data["prpCitemKindsTemp[12].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[12].startHour"]="0";
        $data["prpCitemKindsTemp[12].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[12].endHour"]="24";
        $data["prpCitemKindsTemp[12].flag"]="2000000";
        $data["prpCitemKindsTemp[12].basePremium"]="0.00";
        $data["prpCitemKindsTemp[12].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[12].rate"]="15.0000";
        $data["prpCitemKindsTemp[12].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[12].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[12].dutyFlag"]="2";


        $data["prpCprofitDetailsTemp[42].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[42].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[42].condition"]="自主核保优惠系数";
        $data["profitRateTemp[42]"]="0.85000000";
        $data["prpCprofitDetailsTemp[42].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[42].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[42].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[42].kindCode"]="050931";
        $data["prpCprofitDetailsTemp[42].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[42].flag"]="";
        $data["prpCprofitDetailsTemp[42].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[42].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[42].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[42].id.itemKindNo"]="15";
        $data["prpCprofitDetailsTemp[42].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[43].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[43].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[43].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[43]"]="0.60000000";
        $data["prpCprofitDetailsTemp[43].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[43].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[43].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[43].kindCode"]="050931";
        $data["prpCprofitDetailsTemp[43].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[43].flag"]="";
        $data["prpCprofitDetailsTemp[43].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[43].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[43].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[43].id.itemKindNo"]="15";
        $data["prpCprofitDetailsTemp[43].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[44].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[44].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[44].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[44]"]="0.85000000";
        $data["prpCprofitDetailsTemp[44].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[44].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[44].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[44].kindCode"]="050931";
        $data["prpCprofitDetailsTemp[44].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[44].flag"]="";
        $data["prpCprofitDetailsTemp[44].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[44].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[44].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[44].id.itemKindNo"]="15";
        $data["prpCprofitDetailsTemp[44].id.serialNo"]="0";




        $data["prpCitemKindsTemp[15].clauseCode"]="050066";
        $data["prpCitemKindsTemp[15].kindCode"]="050932";
        $data["prpCitemKindsTemp[15].itemKindNo"]="16";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[15].kindName"]="不计免赔险（盗抢险）";
        $data["prpCitemKindsTemp[15].amount"]="0.00";
        $data["prpCitemKindsTemp[15].calculateFlag"]="N";
        $data["prpCitemKindsTemp[15].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[15].startHour"]="0";
        $data["prpCitemKindsTemp[15].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[15].endHour"]="24";
        $data["prpCitemKindsTemp[15].flag"]="2000000";
        $data["prpCitemKindsTemp[15].basePremium"]="0.00";
        $data["prpCitemKindsTemp[15].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[15].rate"]="20.0000";
        $data["prpCitemKindsTemp[15].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[15].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[15].dutyFlag"]="2";



        $data["prpCprofitDetailsTemp[45].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[45].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[45].condition"]="自主核保优惠系数";
        $data["profitRateTemp[45]"]="0.85000000";
        $data["prpCprofitDetailsTemp[45].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[45].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[45].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[45].kindCode"]="050932";
        $data["prpCprofitDetailsTemp[45].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[45].flag"]="";
        $data["prpCprofitDetailsTemp[45].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[45].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[45].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[45].id.itemKindNo"]="16";
        $data["prpCprofitDetailsTemp[45].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[46].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[46].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[46].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[46]"]="0.60000000";
        $data["prpCprofitDetailsTemp[46].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[46].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[46].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[46].kindCode"]="050932";
        $data["prpCprofitDetailsTemp[46].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[46].flag"]="";
        $data["prpCprofitDetailsTemp[46].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[46].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[46].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[46].id.itemKindNo"]="16";
        $data["prpCprofitDetailsTemp[46].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[47].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[47].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[47].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[47]"]="0.85000000";
        $data["prpCprofitDetailsTemp[47].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[47].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[47].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[47].kindCode"]="050932";
        $data["prpCprofitDetailsTemp[47].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[47].flag"]="";
        $data["prpCprofitDetailsTemp[47].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[47].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[47].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[47].id.itemKindNo"]="16";
        $data["prpCprofitDetailsTemp[47].id.serialNo"]="0";


        $data["prpCitemKindsTemp[16].clauseCode"]="050066";
        $data["prpCitemKindsTemp[16].kindCode"]="050933";
        $data["prpCitemKindsTemp[16].itemKindNo"]="17";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[16].kindName"]="不计免赔险（车上人员（司机））";
        $data["prpCitemKindsTemp[16].amount"]="0.00";
        $data["prpCitemKindsTemp[16].calculateFlag"]="N";
        $data["prpCitemKindsTemp[16].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[16].startHour"]="0";
        $data["prpCitemKindsTemp[16].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[16].endHour"]="24";
        $data["prpCitemKindsTemp[16].flag"]="2000000";
        $data["prpCitemKindsTemp[16].basePremium"]="0.00";
        $data["prpCitemKindsTemp[16].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[16].rate"]="15.0000";
        $data["prpCitemKindsTemp[16].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[16].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[16].dutyFlag"]="2";


        $data["prpCprofitDetailsTemp[48].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[48].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[48].condition"]="自主核保优惠系数";
        $data["profitRateTemp[48]"]="0.85000000";
        $data["prpCprofitDetailsTemp[48].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[48].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[48].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[48].kindCode"]="050933";
        $data["prpCprofitDetailsTemp[48].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[48].flag"]="";
        $data["prpCprofitDetailsTemp[48].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[48].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[48].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[48].id.itemKindNo"]="17";
        $data["prpCprofitDetailsTemp[48].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[49].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[49].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[49].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[49]"]="0.60000000";
        $data["prpCprofitDetailsTemp[49].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[49].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[49].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[49].kindCode"]="050933";
        $data["prpCprofitDetailsTemp[49].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[49].flag"]="";
        $data["prpCprofitDetailsTemp[49].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[49].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[49].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[49].id.itemKindNo"]="17";
        $data["prpCprofitDetailsTemp[49].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[50].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[50].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[50].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[50]"]="0.85000000";
        $data["prpCprofitDetailsTemp[50].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[50].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[50].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[50].kindCode"]="050933";
        $data["prpCprofitDetailsTemp[50].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[50].flag"]="";
        $data["prpCprofitDetailsTemp[50].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[50].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[50].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[50].id.itemKindNo"]="17";
        $data["prpCprofitDetailsTemp[50].id.serialNo"]="0";


        $data["prpCitemKindsTemp[17].clauseCode"]="050066";
        $data["prpCitemKindsTemp[17].kindCode"]="050934";
        $data["prpCitemKindsTemp[17].itemKindNo"]="18";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[17].kindName"]="不计免赔险（车上人员（乘客））";
        $data["prpCitemKindsTemp[17].amount"]="0.00";
        $data["prpCitemKindsTemp[17].calculateFlag"]="N";
        $data["prpCitemKindsTemp[17].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[17].startHour"]="0";
        $data["prpCitemKindsTemp[17].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[17].endHour"]="24";
        $data["prpCitemKindsTemp[17].flag"]="2000000";
        $data["prpCitemKindsTemp[17].basePremium"]="0.00";
        $data["prpCitemKindsTemp[17].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[17].rate"]="15.0000";
        $data["prpCitemKindsTemp[17].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[17].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[17].dutyFlag"]="2";



        $data["prpCprofitDetailsTemp[51].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[51].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[51].condition"]="自主核保优惠系数";
        $data["profitRateTemp[51]"]="0.85000000";
        $data["prpCprofitDetailsTemp[51].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[51].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[51].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[51].kindCode"]="050934";
        $data["prpCprofitDetailsTemp[51].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[51].flag"]="";
        $data["prpCprofitDetailsTemp[51].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[51].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[51].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[51].id.itemKindNo"]="18";
        $data["prpCprofitDetailsTemp[51].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[52].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[52].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[52].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[52]"]="0.60000000";
        $data["prpCprofitDetailsTemp[52].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[52].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[52].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[52].kindCode"]="050934";
        $data["prpCprofitDetailsTemp[52].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[52].flag"]="";
        $data["prpCprofitDetailsTemp[52].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[52].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[52].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[52].id.itemKindNo"]="18";
        $data["prpCprofitDetailsTemp[52].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[53].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[53].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[53].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[53]"]="0.85000000";
        $data["prpCprofitDetailsTemp[53].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[53].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[53].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[53].kindCode"]="050934";
        $data["prpCprofitDetailsTemp[53].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[53].flag"]="";
        $data["prpCprofitDetailsTemp[53].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[53].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[53].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[53].id.itemKindNo"]="18";
        $data["prpCprofitDetailsTemp[53].id.serialNo"]="0";




        $data["prpCitemKindsTemp[18].clauseCode"]="050066";
        $data["prpCitemKindsTemp[18].kindCode"]="050935";
        $data["prpCitemKindsTemp[18].itemKindNo"]="19";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[18].kindName"]="不计免赔险（自燃损失险）";
        $data["prpCitemKindsTemp[18].amount"]="0.00";
        $data["prpCitemKindsTemp[18].calculateFlag"]="N";
        $data["prpCitemKindsTemp[18].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[18].startHour"]="0";
        $data["prpCitemKindsTemp[18].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[18].endHour"]="24";
        $data["prpCitemKindsTemp[18].flag"]="2000000";
        $data["prpCitemKindsTemp[18].basePremium"]="0.00";
        $data["prpCitemKindsTemp[18].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[18].rate"]="20.0000";
        $data["prpCitemKindsTemp[18].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[18].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[18].dutyFlag"]="2";



        $data["prpCprofitDetailsTemp[54].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[54].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[54].condition"]="自主核保优惠系数";
        $data["profitRateTemp[54]"]="0.85000000";
        $data["prpCprofitDetailsTemp[54].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[54].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[54].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[54].kindCode"]="050935";
        $data["prpCprofitDetailsTemp[54].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[54].flag"]="";
        $data["prpCprofitDetailsTemp[54].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[54].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[54].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[54].id.itemKindNo"]="19";
        $data["prpCprofitDetailsTemp[54].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[55].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[55].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[55].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[55]"]="0.60000000";
        $data["prpCprofitDetailsTemp[55].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[55].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[55].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[55].kindCode"]="050935";
        $data["prpCprofitDetailsTemp[55].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[55].flag"]="";
        $data["prpCprofitDetailsTemp[55].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[55].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[55].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[55].id.itemKindNo"]="19";
        $data["prpCprofitDetailsTemp[55].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[56].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[56].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[56].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[56]"]="0.85000000";
        $data["prpCprofitDetailsTemp[56].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[56].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[56].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[56].kindCode"]="050935";
        $data["prpCprofitDetailsTemp[56].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[56].flag"]="";
        $data["prpCprofitDetailsTemp[56].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[56].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[56].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[56].id.itemKindNo"]="19";
        $data["prpCprofitDetailsTemp[56].id.serialNo"]="0";


        $data["prpCitemKindsTemp[19].clauseCode"]="050066";
        $data["prpCitemKindsTemp[19].kindCode"]="050936";
        $data["prpCitemKindsTemp[19].itemKindNo"]="20";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[19].kindName"]="不计免赔险（新增设备损失险）";
        $data["prpCitemKindsTemp[19].amount"]="0.00";
        $data["prpCitemKindsTemp[19].calculateFlag"]="N";
        $data["prpCitemKindsTemp[19].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[19].startHour"]="0";
        $data["prpCitemKindsTemp[19].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[19].endHour"]="24";
        $data["prpCitemKindsTemp[19].flag"]="2000000";
        $data["prpCitemKindsTemp[19].basePremium"]="0.00";
        $data["prpCitemKindsTemp[19].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[19].rate"]="15.0000";
        $data["prpCitemKindsTemp[19].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[19].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[19].dutyFlag"]="2";


        $data["prpCprofitDetailsTemp[57].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[57].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[57].condition"]="自主核保优惠系数";
        $data["profitRateTemp[57]"]="0.85000000";
        $data["prpCprofitDetailsTemp[57].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[57].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[57].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[57].kindCode"]="050936";
        $data["prpCprofitDetailsTemp[57].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[57].flag"]="";
        $data["prpCprofitDetailsTemp[57].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[57].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[57].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[57].id.itemKindNo"]="20";
        $data["prpCprofitDetailsTemp[57].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[58].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[58].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[58].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[58]"]="0.60000000";
        $data["prpCprofitDetailsTemp[58].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[58].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[58].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[58].kindCode"]="050936";
        $data["prpCprofitDetailsTemp[58].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[58].flag"]="";
        $data["prpCprofitDetailsTemp[58].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[58].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[58].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[58].id.itemKindNo"]="20";
        $data["prpCprofitDetailsTemp[58].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[59].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[59].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[59].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[59]"]="0.85000000";
        $data["prpCprofitDetailsTemp[59].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[59].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[59].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[59].kindCode"]="050936";
        $data["prpCprofitDetailsTemp[59].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[59].flag"]="";
        $data["prpCprofitDetailsTemp[59].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[59].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[59].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[59].id.itemKindNo"]="20";
        $data["prpCprofitDetailsTemp[59].id.serialNo"]="0";




        $data["prpCitemKindsTemp[20].clauseCode"]="050066";
        $data["prpCitemKindsTemp[20].kindCode"]="050937";
        $data["prpCitemKindsTemp[20].itemKindNo"]="21";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[20].kindName"]="不计免赔险（车身划痕损失险）";
        $data["prpCitemKindsTemp[20].amount"]="0.00";
        $data["prpCitemKindsTemp[20].calculateFlag"]="N";
        $data["prpCitemKindsTemp[20].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[20].startHour"]="0";
        $data["prpCitemKindsTemp[20].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[20].endHour"]="24";
        $data["prpCitemKindsTemp[20].flag"]="2000000";
        $data["prpCitemKindsTemp[20].basePremium"]="0.00";
        $data["prpCitemKindsTemp[20].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[20].rate"]="15.0000";
        $data["prpCitemKindsTemp[20].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[20].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[20].dutyFlag"]="2";


        $data["prpCprofitDetailsTemp[60].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[60].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[60].condition"]="自主核保优惠系数";
        $data["profitRateTemp[60]"]="0.85000000";
        $data["prpCprofitDetailsTemp[60].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[60].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[60].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[60].kindCode"]="050937";
        $data["prpCprofitDetailsTemp[60].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[60].flag"]="";
        $data["prpCprofitDetailsTemp[60].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[60].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[60].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[60].id.itemKindNo"]="21";
        $data["prpCprofitDetailsTemp[60].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[61].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[61].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[61].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[61]"]="0.60000000";
        $data["prpCprofitDetailsTemp[61].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[61].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[61].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[61].kindCode"]="050937";
        $data["prpCprofitDetailsTemp[61].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[61].flag"]="";
        $data["prpCprofitDetailsTemp[61].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[61].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[61].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[61].id.itemKindNo"]="21";
        $data["prpCprofitDetailsTemp[61].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[62].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[62].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[62].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[62]"]="0.85000000";
        $data["prpCprofitDetailsTemp[62].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[62].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[62].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[62].kindCode"]="050937";
        $data["prpCprofitDetailsTemp[62].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[62].flag"]="";
        $data["prpCprofitDetailsTemp[62].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[62].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[62].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[62].id.itemKindNo"]="21";
        $data["prpCprofitDetailsTemp[62].id.serialNo"]="0";




        $data["prpCitemKindsTemp[22].clauseCode"]="050066";
        $data["prpCitemKindsTemp[22].kindCode"]="050938";
        $data["prpCitemKindsTemp[22].itemKindNo"]="22";
        $data["kindcodesub"]="";
        $data["prpCitemKindsTemp[22].kindName"]="不计免赔险（发动机涉水损失险）";
        $data["prpCitemKindsTemp[22].amount"]="0.00";
        $data["prpCitemKindsTemp[22].calculateFlag"]="N";
        $data["prpCitemKindsTemp[22].startDate"]=$business['BUSINESS_START_TIME'];
        $data["prpCitemKindsTemp[22].startHour"]="0";
        $data["prpCitemKindsTemp[22].endDate"]=$business['BUSINESS_END_TIME'];
        $data["prpCitemKindsTemp[22].endHour"]="24";
        $data["prpCitemKindsTemp[22].flag"]="2000000";
        $data["prpCitemKindsTemp[22].basePremium"]="0.00";
        $data["prpCitemKindsTemp[22].riskPremium"]="0.00";
        $data["prpCitemKindsTemp[22].rate"]="15.0000";
        $data["prpCitemKindsTemp[22].disCount"]=$_SESSION['DISCOUNT'];
        $data["prpCitemKindsTemp[22].taxRate"]="6.00000";
        $data["prpCitemKindsTemp[22].dutyFlag"]="2";



        $data["prpCprofitDetailsTemp[63].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[63].profitName"]="自主核保优惠系数";
        $data["prpCprofitDetailsTemp[63].condition"]="自主核保优惠系数";
        $data["profitRateTemp[63]"]="0.85000000";
        $data["prpCprofitDetailsTemp[63].profitRate"]="";
        $data["prpCprofitDetailsTemp[63].profitRateMin"]="";
        $data["prpCprofitDetailsTemp[63].profitRateMax"]="";
        $data["prpCprofitDetailsTemp[63].kindCode"]="050938";
        $data["prpCprofitDetailsTemp[63].conditionCode"]="C03";
        $data["prpCprofitDetailsTemp[63].flag"]="";
        $data["prpCprofitDetailsTemp[63].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[63].id.profitCode"]="C03";
        $data["prpCprofitDetailsTemp[63].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[63].id.itemKindNo"]="22";
        $data["prpCprofitDetailsTemp[63].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[64].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[64].profitName"]="无赔款优待及上年赔款记录";
        $data["prpCprofitDetailsTemp[64].condition"]="连续3年没有发生赔款";
        $data["profitRateTemp[64]"]="0.60000000";
        $data["prpCprofitDetailsTemp[64].profitRate"]="60.000000";
        $data["prpCprofitDetailsTemp[64].profitRateMin"]="60.000000";
        $data["prpCprofitDetailsTemp[64].profitRateMax"]="200.000000";
        $data["prpCprofitDetailsTemp[64].kindCode"]="050938";
        $data["prpCprofitDetailsTemp[64].conditionCode"]="C0101";
        $data["prpCprofitDetailsTemp[64].flag"]="";
        $data["prpCprofitDetailsTemp[64].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[64].id.profitCode"]="C01";
        $data["prpCprofitDetailsTemp[64].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[64].id.itemKindNo"]="22";
        $data["prpCprofitDetailsTemp[64].id.serialNo"]="0";
        $data["prpCprofitDetailsTemp[65].chooseFlag"]="1";
        $data["prpCprofitDetailsTemp[65].profitName"]="自主渠道系数";
        $data["prpCprofitDetailsTemp[65].condition"]="经纪及代理渠道业务优惠系数";
        $data["profitRateTemp[65]"]="0.85000000";
        $data["prpCprofitDetailsTemp[65].profitRate"]="85.000000";
        $data["prpCprofitDetailsTemp[65].profitRateMin"]="85.000000";
        $data["prpCprofitDetailsTemp[65].profitRateMax"]="115.000000";
        $data["prpCprofitDetailsTemp[65].kindCode"]="050938";
        $data["prpCprofitDetailsTemp[65].conditionCode"]="C0206";
        $data["prpCprofitDetailsTemp[65].flag"]="";
        $data["prpCprofitDetailsTemp[65].id.proposalNo"]="";
        $data["prpCprofitDetailsTemp[65].id.profitCode"]="C02";
        $data["prpCprofitDetailsTemp[65].id.profitType"]="1";
        $data["prpCprofitDetailsTemp[65].id.itemKindNo"]="22";
        $data["prpCprofitDetailsTemp[65].id.serialNo"]="0";


                $data["hidden_index_itemKind"]="15";
                $data["hidden_index_profitDetial"]="0";
                $data["itemKindLoadFlag"]="";
                $data["prpCprofitFactorsTemp[0].chooseFlag"]="on";
                $data["serialNo[0]"]="1";
                $data["prpCprofitFactorsTemp[0].profitName"]="无赔款优待及上年赔款记录";
                $data["prpCprofitFactorsTemp[0].condition"]="连续1年没有发生赔款";
                $data["rateTemp[0]"]="0.85000000";
                $data["prpCprofitFactorsTemp[0].rate"]="85";
                $data["prpCprofitFactorsTemp[0].lowerRate"]="85";
                $data["prpCprofitFactorsTemp[0].upperRate"]="85";
                $data["prpCprofitFactorsTemp[0].id.profitCode"]="C01";
                $data["prpCprofitFactorsTemp[0].id.conditionCode"]="C0105";
                $data["prpCprofitFactorsTemp[0].flag"]="";
                $data["prpCprofitFactorsTemp[1].chooseFlag"]="on";
                $data["serialNo[1]"]="2";
                $data["prpCprofitFactorsTemp[1].profitName"]="自主渠道系数";
                $data["prpCprofitFactorsTemp[1].condition"]="未定义渠道优惠系数";
                $data["rateTemp[1]"]="0.85000000";
                $data["prpCprofitFactorsTemp[1].rate"]="85";
                $data["prpCprofitFactorsTemp[1].lowerRate"]="85";
                $data["prpCprofitFactorsTemp[1].upperRate"]="115";
                $data["prpCprofitFactorsTemp[1].id.profitCode"]="C02";
                $data["prpCprofitFactorsTemp[1].id.conditionCode"]="C0208";
                $data["prpCprofitFactorsTemp[1].flag"]="";
                $data["prpCprofitFactorsTemp[2].chooseFlag"]="on";
                $data["serialNo[2]"]="3";
                $data["prpCprofitFactorsTemp[2].profitName"]="自主核保优惠系数";
                $data["prpCprofitFactorsTemp[2].condition"]="自主核保优惠系数";
                $data["rateTemp[2]"]="0.85000000";
                $data["prpCprofitFactorsTemp[2].rate"]="85";
                $data["prpCprofitFactorsTemp[2].lowerRate"]="85";
                $data["prpCprofitFactorsTemp[2].upperRate"]="115";
                $data["prpCprofitFactorsTemp[2].id.profitCode"]="C03";
                $data["prpCprofitFactorsTemp[2].id.conditionCode"]="C03";
                $data["prpCprofitFactorsTemp[2].flag"]="";
                $data["prpCprofitFactorsTemp[3].chooseFlag"]="on";
                $data["serialNo[3]"]="4";
                $data["prpCprofitFactorsTemp[3].profitName"]="交通违法浮动系数";
                $data["prpCprofitFactorsTemp[3].condition"]="交通违法系数";
                $data["rateTemp[3]"]="1.00000000";
                $data["prpCprofitFactorsTemp[3].rate"]="100";
                $data["prpCprofitFactorsTemp[3].lowerRate"]="100";
                $data["prpCprofitFactorsTemp[3].upperRate"]="100";
                $data["prpCprofitFactorsTemp[3].id.profitCode"]="C04";
                $data["prpCprofitFactorsTemp[3].id.conditionCode"]="C04";
                $data["prpCprofitFactorsTemp[3].flag"]="";
                $data["BIdemandNo"]="01PICC02170000000001118385513E";
                $data["BIdemandTime"]="2017-06-21";
                $data["bIRiskWarningType"]="";
                $data["noDamageYearsBIPlat"]="0";
                $data["prpCitemCarExt.lastDamagedBI"]="0";
                $data["lastDamagedBITemp"]="0";
                $data["DAZlastDamagedBI"]="1";
                $data["prpCitemCarExt.thisDamagedBI"]="0";
                $data["prpCitemCarExt.noDamYearsBI"]="1";
                $data["noDamYearsBINumber"]="1";
                $data["prpCitemCarExt.lastDamagedCI"]="0";
                $data["BIDemandClaim_Flag"]="";
                $data["prpCitemKindCI.shortRate"]="100";
                if(isset($mvtalci['MVTALCI_PREMIUM']) && $mvtalci['MVTALCI_PREMIUM']!="")
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
                $data["prpCitemKindCI.flag"]="1000000";
                $data["prpCitemKindCI.netPremium"]=empty($_SESSION['NetPremium'])?"":$_SESSION['NetPremium'];
                $data["prpCitemKindCI.taxPremium"]=empty($_SESSION['MVTALCI_TAX_PREMIUM'])?"":$_SESSION['MVTALCI_TAX_PREMIUM'];



                $data["prpCitemKindCI.taxRate"]="6.00";
                $data["prpCitemKindCI.taxfee_gb"]="0";
                $data["prpCitemKindCI.taxfee_lb"]="0";
                $data["prpCitemKindCI.allTaxFee"]="41.79";
                $data["prpCitemKindCI.allNetPremium"]="696.46";
                $data["prpCitemKindCI.dutyFlag"]="2";

                $data["prpCitemCarExt_CI.rateRloatFlag"]="01";
                $data["prpCitemCarExt_CI.noDamYearsCI"]="0";
                $data["prpCitemCarExt_CI.lastDamagedCI"]="0";
                $data["prpCitemCarExt_CI.flag"]="";
                $data["prpCitemCarExt_CI.damFloatRatioCI"]="0.0000";
                $data["prpCitemCarExt_CI.offFloatRatioCI"]="0.0000";
                $data["prpCitemCarExt_CI.thisDamagedCI"]="0";
                $data["hidden_index_ctraffic_NOPlat_Drink"]="0";
                $data["hidden_index_ctraffic_NOPlat"]="0";
                $data["ciInsureDemand.demandNo"]=$_SESSION['MVTALCI_Query_Code'];
                $data["ciInsureDemand.demandTime"]=date("Y-m-d",time());
                $data["ciInsureDemand.restricFlag"]="0001";
                $data["ciInsureDemand.preferentialDay"]="53";
                $data["ciInsureDemand.preferentialPremium"]="116.75";
                $data["ciInsureDemand.preferentialFormula "]="减免保费";
                $data["ciInsureDemand.lastyearenddate"]="2017-08-22";
                $data["prpCitemCar.noDamageYears"]="0";
                $data["ciInsureDemand.rateRloatFlag"]="00";
                $data["ciInsureDemand.claimAdjustReason"]="A1";
                $data["ciInsureDemand.peccancyAdjustReason"]="V1";
                $data["cIRiskWarningType"]="";
                $data["CIDemandFecc_Flag"]="";
                $data["CIDemandClaim_Flag"]="";

                $data["ciInsureDemand.licenseNo"]=$_SESSION['car_info']["licenseNo"];
                $data["ciInsureDemand.licenseType"]=$_SESSION['car_info']["licenseType"];
                $data["ciInsureDemand.useNatureCode"]=$_SESSION['car_info']["useNatureCode"];
                $data["ciInsureDemand.frameNo"]=$_SESSION['car_info']["frameNo"];
                $data["ciInsureDemand.engineNo"]=$_SESSION['car_info']["engineNo"];
                $data["ciInsureDemand.licenseColorCode"]=$_SESSION['car_info']["licenseColorCode"];
                $data["ciInsureDemand.carOwner"]=$_SESSION['car_info']["carOwner"];
                $data["ciInsureDemand.enrollDate"]=$_SESSION['car_info']["enrollDate"];
                $data["ciInsureDemand.makeDate"]=$_SESSION['car_info']["makeDate"];
                $data["ciInsureDemand.seatCount"]=$_SESSION['car_info']["seatCount"];
                $data["ciInsureDemand.tonCount"]=$_SESSION['car_info']["tonCount"];
                $data["ciInsureDemand.validCheckDate"]=$_SESSION['car_info']["validCheckDate"];
                $data["ciInsureDemand.manufacturerName"]=$_SESSION['car_info']["manufacturerName"];
                $data["ciInsureDemand.modelCode"]=$_SESSION['car_info']["modelCode"];
                $data["ciInsureDemand.brandCName"]=$_SESSION['car_info']["brandCName"];
                $data["ciInsureDemand.brandName"]=$_SESSION['car_info']["brandName"];
                $data["ciInsureDemand.carKindCode"]=$_SESSION['car_info']["carKindCode"];
                $data["ciInsureDemand.checkDate"]=$_SESSION['car_info']["checkDate"];
                $data["ciInsureDemand.endValidDate"]=$_SESSION['car_info']["endValidDate"];
                $data["ciInsureDemand.carStatus"]=$_SESSION['car_info']["carStatus"];
                $data["ciInsureDemand.haulage"]=$_SESSION['car_info']["haulage"];



                $data["AccidentFlag"]="";
                $data["rateFloatFlag"]="ND1";
                $data["hidden_index_ctraffic"]="0";
                $data["prpCcarShipTax.extendChar2"]="";
                $data["_taxUnit"]="";
                $data["taxPlatFormTime"]="";
                $data["iniPrpCcarShipTax_Flag"]="";
                $data["strCarShipFlag"]="1";
                $data["prpCcarShipTax.taxType"]="1";
                $data["prpCcarShipTax.calculateMode"]="C1";
                $data["prpCcarShipTax.leviedDate"]="";
                $data["prpCcarShipTax.carKindCode"]="A01";
                $data["prpCcarShipTax.model"]="B11";


                $data["prpCcarShipTax.taxPayerIdentNo"]="132825197804240020";
                $data["prpCcarShipTax.taxPayerNumber"]="132825197804240020";
                $data["prpCcarShipTax.carLotEquQuality"]=$auto['KERB_MASS'];
                $data["prpCcarShipTax.taxPayerCode"]="1100100002821644";
                $data["prpCcarShipTax.id.itemNo"]="1";
                $data["prpCcarShipTax.taxPayerNature"]="3";
                $data["prpCcarShipTax.taxPayerName"]=$auto['OWNER'];
                $data["prpCcarShipTax.taxUnit"]="";
                $data["prpCcarShipTax.taxComCode"]="";
                $data["prpCcarShipTax.taxComName"]="";
                $data["prpCcarShipTax.taxExplanation"]="";
                $data["prpCcarShipTax.taxAbateReason"]="08";
                $data["prpCcarShipTax.dutyPaidProofNo_1"]="";
                $data["prpCcarShipTax.dutyPaidProofNo_2"]="";
                $data["prpCcarShipTax.dutyPaidProofNo"]="";
                $data["prpCcarShipTax.taxAbateRate"]="50";
                $data["prpCcarShipTax.taxAbateAmount"]="";
                $data["prpCcarShipTax.taxAbateType"]="1";
                $data["prpCcarShipTax.taxUnitAmount"]="";
                $data["prpCcarShipTax.prePayTaxYear"]=date("Y",time())-1;
                $data["prpCcarShipTax.prePolicyEndDate"]="";
                $data["prpCcarShipTax.payStartDate"]=date("Y",time())."-01-01";//本次缴税起期
                $data["prpCcarShipTax.payEndDate"]=date("Y",time())."-12-31";//本次缴税止期
                $data["prpCcarShipTax.thisPayTax"]=empty($mvtalci['TAX_PREMIUM'])?"":$mvtalci['TAX_PREMIUM'];
                $data["prpCcarShipTax.prePayTax"]="0";
                $data["prpCcarShipTax.taxItemCode"]="";
                $data["prpCcarShipTax.taxItemName"]="";
                $data["prpCcarShipTax.baseTaxation"]="";
                $data["prpCcarShipTax.taxRelifFlag"]="";
                $data["prpCcarShipTax.delayPayTax"]="0";
                $data["prpCcarShipTax.sumPayTax"]=empty($mvtalci['TAX_PREMIUM'])?"":$mvtalci['TAX_PREMIUM'];
                $data["CarShipInit_Flag"]="";
                $data["prpCcarShipTax.flag"]="";



                $data["iniPrpCengage_Flag"]="1";
                $data["hidden_index_engage"]="0";
/*                $data["prpCengageTemps[0].id.serialNo"]="1";
                $data["prpCengageTemps[0].clauseCode"]="000026";
                $data["prpCengageTemps[0].clauseName"]="车主约定";
                $data["clauses[0]"]="本车车主为（".$auto['OWNER']."）。";
                $data["prpCengageTemps[0].flag"]="";
                $data["prpCengageTemps[0].engageFlag"]="0";
                $data["prpCengageTemps[0].maxCount"]="";
                $data["prpCengageTemps[0].clauses"]="本车车主为（".$auto['OWNER']."）。";
                $data["prpCengageTemps[1].id.serialNo"]="2";
                $data["prpCengageTemps[1].clauseCode"]="910012";
                $data["prpCengageTemps[1].clauseName"]="尾号减免特约";
                $data["clauses[1]"]="";//"您的车辆不享受奥运限行减免，属按尾号限行范围， 进行交强险保费减免，减免总天数为53天，减免保费116.75元，减免保费";
                $data["prpCengageTemps[1].flag"]="";
                $data["prpCengageTemps[1].engageFlag"]="";
                $data["prpCengageTemps[1].maxCount"]="";
                $data["prpCengageTemps[1].clauses"]="";//"您的车辆不享受奥运限行减免，属按尾号限行范围， 进行交强险保费减免，减免总天数为53天，减免保费116.75元，减免保费";*/
               /* $data["costRateForPG"]="23.00";
                $data["certificateNo"]="79854683-7";
                $data["levelMaxRate"]="";
                $data["maxRateScm"]="45";
                $data["levelMaxRateCi"]="";
                $data["maxRateScmCi"]="4";
                $data["isModifyBI"]="";
                $data["isModifyCI"]="";
                $data["sumBICoinsRate"]="";
                $data["sumCICoinsRate"]="";
                $data["netCommission_Switch"]="";
                $data["agentsRateBI"]="";
                $data["agentsRateCI"]="";*/
                $data["prpVisaRecordP.id.visaNo"]="";
                $data["prpVisaRecordP.id.visaCode"]="";
                $data["prpVisaRecordP.visaName"]="";
                $data["prpVisaRecordP.printType"]="101";
                $data["prpVisaRecordT.id.visaNo"]="";
                $data["prpVisaRecordT.id.visaCode"]="";
                $data["prpVisaRecordT.visaName"]="";
                $data["prpVisaRecordT.printType"]="";
                $data["prpCmain.sumAmount"]="";
                $data["prpCmain.sumDiscount"]="";
                $data["prpCstampTaxBI.biTaxRate"]="";
                $data["prpCstampTaxBI.biPayTax"]="0";
                $data["prpCmainCommon.netsales"]="1";
                $data["prpVisaRecordPCI.id.visaNo"]="";
                $data["prpVisaRecordPCI.id.visaCode"]="";
                $data["prpVisaRecordPCI.visaName"]="";
                $data["prpVisaRecordPCI.printType"]="201";
                $data["prpVisaRecordTCI.id.visaNo"]="";
                $data["prpVisaRecordTCI.id.visaCode"]="";
                $data["prpVisaRecordTCI.visaName"]="";
                $data["prpVisaRecordTCI.printType"]="203";

                $data["prpCmainCI.sumAmount"]="122000";
                $data["prpCmainCI.sumDiscount"]="211.75";
                $data["prpCstampTaxCI.ciTaxRate"]="";
                $data["prpCstampTaxCI.ciPayTax"]="0";
                $data["prpCmainCI.sumPremium"]="5";
                $data["prpCmainCar.rescueFundRate"]="";
                $data["prpCmainCar.resureFundFee"]="";
                $data["prpCmainCommonCI.netsales"]="1";
                $data["prpCmain.projectCode"]="";
                $data["projectCode"]="";
                $data["costRateUpper"]="";
                $data["prpCmainCommon.ext3"]="";
                $data["importantProjectCode"]="";
                $data["prpCmain.operatorCode"]="980057";
                $data["operatorName"]="耿淑云";
                $data["operateDateShow"]="2017-06-21";
                $data["prpCmain.coinsFlag"]="00";
                $data["coinsFlagBak"]="00";
                $data["premium"]="";
                $data["prpCmain.language"]="CNY";
                $data["prpCmain.policySort"]="1";
                $data["prpCmain.policyRelCode"]="";
                $data["prpCmain.policyRelName"]="";
                $data["subsidyRate"]="";
                $data["policyRel"]="";
                $data["prpCmain.reinsFlag"]="0";
                $data["prpCmain.agriFlag"]="0";
                $data["prpCmainCar.carCheckStatus"]="0";
                $data["prpCmainCar.carChecker"]="";
                $data["carCheckerTranslate"]="";
                $data["prpCmainCar.carCheckTime"]="";
                $data["prpCmainCommon.DBCFlag"]="0";
                $data["prpCmain.argueSolution"]="1";
                $data["prpCmain.arbitBoardName"]="";
                $data["arbitBoardNameDes"]="";


                $data["prpDdismantleDetails[0].id.agreementNo"]="RULE20130000000023074";
                $data["prpDdismantleDetails[0].flag"]="DAA";
                $data["prpDdismantleDetails[0].id.configCode"]="PUB";
                $data["prpDdismantleDetails[0].id.assignType"]="1";
                $data["prpDdismantleDetails[0].id.roleCode"]="11003O101351";
                $data["prpDdismantleDetails[0].roleName"]="北京众志通达汽车修理有限公司";
                $data["prpDdismantleDetails[0].costRate"]="100";
                $data["prpDdismantleDetails[0].roleFlag"]="1";
                $data["prpDdismantleDetails[0].businessNature"]="3";
                $data["prpDdismantleDetails[0].roleCode_uni"]="";
                $data["hidden_index_prpDdismantleDetails"]="0";
                $data["prpDdismantleDetails[1].id.agreementNo"]="RULE20130000000023075";
                $data["prpDdismantleDetails[1].flag"]="DZA";
                $data["prpDdismantleDetails[1].id.configCode"]="PUB";
                $data["prpDdismantleDetails[1].id.assignType"]="1";
                $data["prpDdismantleDetails[1].id.roleCode"]="11003O101351";
                $data["prpDdismantleDetails[1].roleName"]="北京众志通达汽车修理有限公司";
                $data["prpDdismantleDetails[1].costRate"]="100";
                $data["prpDdismantleDetails[1].roleFlag"]="1";
                $data["prpDdismantleDetails[1].businessNature"]="3";
                $data["prpDdismantleDetails[1].roleCode_uni"]="";
                $data["payTimes"]="1";
                $data["iniPrpCplan_Flag"]="";
                $data["loadFlag9"]="";
                $data["planfee_index"]="1";


                $data["prpCplanTemps[0].payNo"]="1";
                $data["prpCplanTemps[0].serialNo"]="0";
                $data["prpCplanTemps[0].endorseNo"]="";
                $data["cplan[0].payReasonC"]="(强制)收保费";
                $data["prpCplanTemps[0].payReason"]="R29";
                $data["prpCplanTemps[0].planDate"]=empty($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];
                $data["prpCplanTemps[0].currency"]="CNY";
                $data["description[0].currency"]="人民币";
                $data["prpCplanTemps[0].planFee"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
                $data["cplans[0].planFee"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
                $data["cplans[0].backPlanFee"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
                $data["prpCplanTemps[0].netPremium"]=empty($_SESSION['NetPremium'])?"":$_SESSION['NetPremium'];
                $data["prpCplanTemps[0].taxPremium"]=empty($_SESSION['MVTALCI_TAX_PREMIUM'])?"":$_SESSION['MVTALCI_TAX_PREMIUM'];
                $data["prpCplanTemps[0].delinquentFee"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
                $data["prpCplanTemps[0].flag"]="";
                $data["prpCplanTemps[0].subsidyRate"]="0";
                $data["prpCplanTemps[0].isBICI"]="CI";


                $data["prpCplanTemps[1].payNo"]="1";
                $data["prpCplanTemps[1].serialNo"]="0";
                $data["prpCplanTemps[1].endorseNo"]="";
                $data["cplan[1].payReasonC"]="代收车船税";
                $data["prpCplanTemps[1].payReason"]="RM9";
                $data["prpCplanTemps[1].planDate"]=empty($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];
                $data["prpCplanTemps[1].currency"]="CNY";
                $data["description[1].currency"]="人民币";
                $data["prpCplanTemps[1].planFee"]=$mvtalci['TAX_PREMIUM'];//$mvtalci[];
                $data["cplans[1].planFee"]=$mvtalci['TAX_PREMIUM'];
                $data["cplans[1].backPlanFee"]=$mvtalci['TAX_PREMIUM'];
                $data["prpCplanTemps[1].netPremium"]="";
                $data["prpCplanTemps[1].taxPremium"]="";
                $data["prpCplanTemps[1].delinquentFee"]=$mvtalci['TAX_PREMIUM'];
                $data["prpCplanTemps[1].flag"]="";
                $data["prpCplanTemps[1].subsidyRate"]="0";
                $data["prpCplanTemps[1].isBICI"]="CShip";


                $data["prpCplanTemps[2].payNo"]="1";
                $data["prpCplanTemps[2].serialNo"]="0";
                $data["prpCplanTemps[2].endorseNo"]="";
                $data["cplan[2].payReasonC"]="收保费";
                $data["prpCplanTemps[2].payReason"]="R21";
                $data["prpCplanTemps[2].planDate"]=empty($business['BUSINESS_START_TIME'])?"":$business['BUSINESS_START_TIME'];
                $data["prpCplanTemps[2].currency"]="CNY";
                $data["description[2].currency"]="人民币";
                $data["prpCplanTemps[2].planFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
                $data["cplans[2].planFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
                $data["cplans[2].backPlanFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
                $data["prpCplanTemps[2].netPremium"]=empty($_SESSION['NET_PREMIUM'])?"":$_SESSION['NET_PREMIUM'];
                $data["prpCplanTemps[2].taxPremium"]=empty($_SESSION['TAX'])?"":$_SESSION['TAX'];
                $data["prpCplanTemps[2].delinquentFee"]=empty($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
                $data["prpCplanTemps[2].flag"]="";
                $data["prpCplanTemps[2].subsidyRate"]="0";
                $data["prpCplanTemps[2].isBICI"]="BI";
                $data["planStr"]="";
                $data["planPayTimes"]="";
                $data["prpAnciInfo.sellExpensesRate"]=$Company_handling['data'][0]['discountRateCIUp'];
                $data["prpAnciInfo.sellExpensesAmount"]=$Company_handling['data'][0]['sellExpensesAmount'];
                $data["prpAnciInfo.sellExpensesRateCIUp"]=$Company_handling['data'][0]['sellExpensesRateCIUp'];
                $data["prpAnciInfo.sellExpensesCIUpAmount"]=$Company_handling['data'][0]['sellExpensesCIUpAmount'];
                $data["prpAnciInfo.sellExpensesRateBIUp"]=$Company_handling['data'][0]['operCommRateBIUp'];
                $data["prpAnciInfo.sellExpensesBIUpAmount"]=$Company_handling['data'][0]['sellExpensesBIUpAmount'];
                $data["prpAnciInfo.operSellExpensesRate"]=$Company_handling['data'][0]['operSellExpensesRate'];
                $data["prpAnciInfo.operSellExpensesAmount"]=$Company_handling['data'][0]['operSellExpensesAmount'];
                $data["prpAnciInfo.operSellExpensesRateCI"]=$Company_handling['data'][0]['operateCommRateCI'];
                $data["prpAnciInfo.operSellExpensesAmountCI"]=$Company_handling['data'][0]['operSellExpensesAmountCI'];
                $data["prpAnciInfo.operSellExpensesRateBI"]=$Company_handling['data'][0]['operSellExpensesRateBI'];
                $data["prpAnciInfo.operSellExpensesAmountBI"]=$Company_handling['data'][0]['operSellExpensesAmountBI'];
                $data["prpAnciInfo.operCommRateCIUp"]=$Company_handling['data'][0]['operCommRateCIUp'];
                $data["operCommRateCIUpAmount"]=$Company_handling['data'][0]['operSellExpensesAmountCI'];
                $data["prpAnciInfo.operCommRateBIUp"]=$Company_handling['data'][0]['operCommRateBIUp'];
                $data["operCommRateBIUpAmount"]=$Company_handling['data'][0]['operSellExpensesAmountBI'];
                $data["prpAnciInfo.operCommRate"]=$Company_handling['data'][0]['operCommRate'];
                $data["prpAnciInfo.operCommRateAmount"]=$Company_handling['data'][0]['operCommRateAmount'];
                $data["prpAnciInfo.operateCommRateCI"]=$Company_handling['data'][0]['operateCommRateCI'];
                $data["prpAnciInfo.operateCommCI"]=$Company_handling['data'][0]['operateCommCI'];
                $data["prpAnciInfo.operateCommRateBI"]=$Company_handling['data'][0]['operateCommRateBI'];
                $data["prpAnciInfo.operateCommBI"]=$Company_handling['data'][0]['operateCommBI'];
                $data["prpAnciInfo.discountRateUp"]=$Company_handling['data'][0]['discountRateUp'];
                $data["prpAnciInfo.discountRateUpAmount"]=$Company_handling['data'][0]['discountRateUpAmount'];
                $data["prpAnciInfo.discountRateCIUp"]=$Company_handling['data'][0]['discountRateCIUp'];
                $data["prpAnciInfo.discountRateCIUpAmount"]="";
                $data["prpAnciInfo.profitRateBIUp"]=$Company_handling['data'][0]['profitRateBIUp'];
                $data["prpAnciInfo.discountRateBIUpAmountp"]=$Company_handling['data'][0]['discountRateBIUpAmount'];
                $data["prpAnciInfo.discountRate"]=$Company_handling['data'][0]['discountRate'];
                $data["prpAnciInfo.discountRateAmount"]=$Company_handling['data'][0]['discountRateAmount'];
                $data["prpAnciInfo.discountRateCI"]=$Company_handling['data'][0]['discountRateCI'];
                $data["prpAnciInfo.discountRateCIAmount"]=$Company_handling['data'][0]['discountRateCIAmount'];
                $data["prpAnciInfo.discountRateBI"]=$Company_handling['data'][0]['discountRateBI'];
                $data["prpAnciInfo.discountRateBIAmount"]=$Company_handling['data'][0]['discountRateBIAmount'];
                $data["prpAnciInfo.riskCode"]="DAA";
                $data["prpAnciInfo.standPayRate"]="0";
                $data["prpAnciInfo.operatePayRate"]="0";
                $data["prpAnciInfo.busiStandardBalanRate"]=$Company_handling['data'][0]['busiStandardBalanRate'];
                $data["prpAnciInfo.busiBalanRate"]=$Company_handling['data'][0]['busiBalanRate'];
                $data["prpAnciInfo.busiRiskRate"]=$Company_handling['data'][0]['busiRiskRate'];
                $data["prpAnciInfo.averProfitRate"]=$Company_handling['data'][0]['averProfitRate'];
                $data["prpAnciInfo.averageRate"]=$Company_handling['data'][0]['averageRate'];
                $data["prpAnciInfo.minNetSumPremiumBI"]=round($Company_handling['data'][0]['minNetSumPremiumBI'],2);
                $data["prpAnciInfo.minNetSumPremiumCI"]=round($Company_handling['data'][0]['minNetSumPremiumCI'],2);
                $data["prpAnciInfo.baseActBusiType"]="";
                $data["prpAnciInfo.baseExpBusiType"]="";
                $data["prpAnciInfo.operateProfitRate"]=$Company_handling['data'][0]['operateProfitRate'];
                $data["prpAnciInfo.breakEvenValue"]=$Company_handling['data'][0]['breakEvenValue'];
                $data["prpAnciInfo.profitRateBIUp"]=$Company_handling['data'][0]['profitRateBIUp'];
                $data["prpAnciInfo.proCommRateBIUp"]=$Company_handling['data'][0]['proCommRateBIUp'];
                $data["prpAnciInfo.busiTypeCommBIUp"]=$Company_handling['data'][0]['busiTypeCommBIUp'];
                $data["prpAnciInfo.busiTypeCommCIUp"]=$Company_handling['data'][0]['busiTypeCommCIUp'];
                $type_of=trim($_SESSION['BUSINESS']['INSURANCES']);
                $standbyField1 ="";
                $standbyField1 = $Company_handling['data'][0]['sellExpensesCIUpAmount'].",";
                $standbyField1.=round($Company_handling['data'][0]['sellExpensesBIUpAmount'],2).",";
                $standbyField1.=round($Company_handling['data'][0]['operSellExpensesRate']+$Company_handling['data'][0]['discountRate'],2).",";
                $standbyField1.=round($Company_handling['data'][0]['operSellExpensesRate']+$Company_handling['data'][0]['discountRate'],2)-$Company_handling['data'][0]['actProCommRate'].",";
                $standbyField1.=",".$Company_handling['data'][0]['standbyField1'].",";
                $standbyField1.=$type_of." ,,,,";
                $data["prpAnciInfo.standbyField1"]=$standbyField1;
                $data["actProfitRate"]=round($Company_handling['data'][0]['operSellExpensesRate']+$Company_handling['data'][0]['discountRate'],2);//折扣和销售费用实际比率(%)
                $data["prpAnciInfo.businessCode"]="";//业务政策代码
                $data["prpAnciInfo.minNetSumPremium"]=$Company_handling['data'][0]['minNetSumPremium'];
                $data["prpAnciInfo.origBusiType"]=$Company_handling['data'][0]['origBusiType'];
                $data["prpAnciInfo.expProCommRateUp"]=$Company_handling['data'][0]['actProCommRate'];
                $data["expProCommRateUp_Disc"]=round($Company_handling['data'][0]['operSellExpensesRate']+$Company_handling['data'][0]['discountRate']-$Company_handling['data'][0]['actProCommRate'],2);//差值
                $data["prpAnciInfo.expBusiType"]=$Company_handling['data'][0]['expBusiType'];
                $data["prpAnciInfo.actProCommRateUp"]="";
                $data["actProCommRateUp_Disc"]="";
                $data["prpAnciInfo.actBusiType"]=$Company_handling['data'][0]['actBusiType'];
                $data["expRiskNote"]=$Company_handling['data'][0]['standbyField1'];
                $data["kindBusiTypeA"]=$type_of;
                $data["kindBusiTypeB"]="";
                $data["kindBusiTypeC"]="";
                $data["kindBusiTypeD"]="";
                $data["kindBusiTypeE"]="";
                $data["prpCmainCar.flag"]="1";
                $data["prpCmainCarFlag"]="1";
                $data["coinsSchemeCode"]="";
                $data["coinsSchemeName"]="";
                $data["mainPolicyNo"]="";
                $data["iniPrpCcoins_Flag"]="";
                $data["hidden_index_ccoins"]="0";
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
                $data["hidden_index_specialFac"]="0";
                $data["iniCspecialFac_Flag"]="";
                $data["_ReinsCode"]="";
                $data["loadFlag8"]="";
                $data["prpCsettlement.buyerUnitRank"]="3";
                $data["prpCsettlement.buyerPreFee"]=$_SESSION['COUNT_PREMIUM']+$mvtalci['MVTALCI_PREMIUM'];
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
                $data["prpCsettlement.buyerProvince"]="11000000";
                $data["buyerProvinceDes"]="人保财险北京市分公司";
                $data["prpCsettlement.buyerBusinessSort"]="01";
                $data["prpCsettlement.comCname"]="";
                $data["prpCsettlement.linkerCode"]="";
                $data["linkerName"]="";
                $data["linkerPhone"]="";
                $data["linkerMobile"]="";
                $data["linkerFax"]="";
                $data["prpCsettlement.comCode"]="";
                $data["prpCsettlement.fundForm"]="1";
                $data["prpCsettlement.flag"]="0";
                $data["settlement_Flag"]="";
                $data["hidden_index_ccontriutions"]="0";
                $data["iProposalNo"]="";
                $data["CProposalNo"]="";
                $data["timeFlag"]="";
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

                $arr=array();

                foreach($data as $k =>$v)
                {
                    $arr[$k]= iconv("utf-8", "gbk", $v);

                }
                $insert_Data =  $this->requestPostData($this->insert_URL,$arr);

                if(strstr($insert_Data,"TDAA") || strstr($insert_Data,"TDZA"))
                {

                    if(strstr($insert_Data,","))
                    {
                       $policy = explode(",", $insert_Data);
                       foreach($policy as $k => $v)
                        {
                            if(strstr($v,"TDAA"))
                            {
                                $policys['TDAA'] = $v;
                            }
                            else
                            {
                                $policys['TDZA'] = $v;
                            }

                        }
                    }
                    else
                    {
                         if(strstr($insert_Data,"TDAA"))
                         {
                            $policys['TDAA'] = $insert_Data;
                         }
                         if(strstr($insert_Data,"TDZA"))
                         {
                            $policys['TDZA'] = $insert_Data;
                         }

                    }
                        return $policys;
                }
                else
                {
                    $this->error['errorMsg']="提交暂存单失败";
                    return false;
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


        if(empty($_SESSION['comCode']) && $_SESSION['comCode']=="")
        {
            $channel_result = self::requestGetData($this->channel_Url);//获取渠道参数
            $axp= iconv("GBK", "UTF-8", $channel_result);
            $comCode="/<input[^<]*type=\"hidden\"[^<]*name=\"comCode\"[^<]*value=\"([^<]*)\"[^<]*>/";
            preg_match_all($comCode, $axp, $comCodes);
            if($comCodes[1][0]!="" && $comCodes[1][0]!="")
            {
                $userCode="/<input[^<]*type=\"hidden\"[^<]*name=\"userCode\"[^<]*value=\"([^<]*)\"[^<]*>/";
                preg_match_all($userCode, $axp, $userCodes);
                $handler1Code="/<input[^<]*type=\"hidden\"[^<]*name=\"prpCmain.handler1Code\"[^<]*value=\"([^<]*)\"[^<]*>/";
                preg_match_all($handler1Code, $axp, $handler1Codes);
                $handler1Codes=explode("\"", $handler1Codes[1][0]);
                $agentCode="/<input[^<]*type=\"hidden\"[^<]*name=\"agentCode\"[^<]*value=\"([^<]*)\"[^<]*>/";
                preg_match_all($agentCode, $axp, $agentCodess);
                $businessNature="/<input[^<]*type=\"text\"[^<]*name=\"prpCmain.businessNature\"[^<]*value=\"([^<]*)\"[^<]*>/";
                preg_match_all($businessNature, $axp, $businessNatures);
                $Nature=explode("\"", $businessNatures[1][0]);

                $_SESSION['comCode']=$comCodes[1][0];
                $_SESSION['userCode']=$userCodes[1][0];
                $_SESSION['handler1Code']=$handler1Codes[0];
                $_SESSION['agentCode']=$agentCodess[1][0];
                $_SESSION['Nature']=$Nature[0];
                return true;
            }
            else
            {
                $this->error['errorMsg']="请确认保险公司账号是否正确";
                return false;
            }
        }
        else
        {
            return true;
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
                    $id_res = $this->getIDCardInfo($auto['IDENTIFY_NO']);
                    if($id_res['error']!=2)
                    {
                        $this->error['errorMsg']="身份证格式错误。";
                        return false;
                    }
                     $result = Array(

                            "struts.token.name"=>"token",
                            "token"=>"",
                            "syscode"=>"prpall",
                            "checkFlag"=>"",
                            "prpDcustomerPerson.validStatus"=>"1",
                            "prpDcustomerPerson.customerCode"=>"",
                            "prpDcustomerPerson.versionNo"=>"0",
                            "prpDcustomerPerson.customerCName"=>$auto['OWNER'],
                            "prpDcustomerPerson.customerFullEName"=>"",
                            "prpDcustomerPerson.identifyType"=>"01",
                            "prpDcustomerPerson.identifyNumber"=>$auto['IDENTIFY_NO'],
                            "prpDcustomerPerson.gender"=>"1",
                            "prpDcustomerPerson.birthDate"=>$id_res['birthday'],
                            "prpDcustomerPerson.nationality"=>"CHN",
                            "country"=>"中国",
                            "prpDcustomerPerson.dateValid"=>"2029-06-27",
                            "prpDcustomerPerson.resident"=>"A",
                            "prpDcustomerRisk.creditLevel"=>"3",
                            "prpDcustomerPerson.marriage"=>"",
                            "prpDcustomerPerson.favourite"=>"",
                            "prpDcustomerPerson.educationCode"=>"",
                            "prpDcustomerPerson.health"=>"",
                            "prpDcustomerPerson.stature"=>"",
                            "prpDcustomerPerson.weight"=>"",
                            "prpDcustomerPerson.bloodType"=>"",
                            "prpDcustomerPerson.deathDate"=>"",
                            "prpDcustomerPerson.customerKind"=>"",
                            "prpDcustomerPerson.auditType"=>"新增",
                            "prpDcustomerPerson.submitDescription"=>"",
                            "PhonePersonNo"=>"",
                            "phoneNumber1"=>"",
                            "phoneType2"=>"",
                            "bondingDate"=>"",
                            "bondingReason"=>"",
                            "bondingPerson"=>"",
                            "bondingOrganization"=>"",
                            "bondingApply"=>"",
                            "removebondingApply"=>"",
                            "bondingFlag"=>"",
                            "phoneType"=>"1",
                            "phoneNumber"=>"",
                            "phoneproperties"=>"01",
                            "customerRelations"=>"",
                            "bestRelationDT"=>"",
                            "phonevalidstatus"=>"1",
                            "isDefaultView"=>"on",
                            "isDefault"=>"",
                            "AddressPersonNo"=>"",
                            "adresstype"=>"1",
                            "province"=>"",
                            "city"=>"",
                            "area"=>"",
                            "addresscname"=>"",
                            "addressename"=>"",
                            "postcode"=>"",
                            "addressvalidstatus"=>"1",
                            "isAddressDefault"=>"",
                            "prpDcustomerPerson.email"=>"",
                            "prpDcustomerPerson.imType"=>"",
                            "prpDcustomerPerson.imNo"=>"",
                            "prpDcustomerPerson.weiChat"=>"",
                            "prpDcustomerPerson.qq"=>"",
                            "prpDcustomerPerson.selfMonthIncome"=>"",
                            "prpDcustomerPerson.selfMonthIncomeCurrency"=>"CNY",
                            "prpDcustomerPerson.familyMonthIncome"=>"",
                            "prpDcustomerPerson.familyMonthIncomeCurrency"=>"CNY",
                            "FamliyPersonNo"=>"",
                            "relationType1"=>"",
                            "name1"=>"",
                            "birthDate1"=>"",
                            "identifyNumber1"=>"",
                            "unit1"=>"",
                            "duty1"=>"",
                            "phoneType1"=>"",
                            "phone1"=>"",
                            "AccountPersonNo"=>"",
                            "bank"=>"",
                            "bankView"=>"",
                            "branchBank"=>"",
                            "account"=>"",
                            "accountType"=>"",
                            "accountvaildstatus"=>"1",
                            "mainAccountValid"=>"",
                            "UnitPersonNo"=>"",
                            "unitType"=>"",
                            "unit"=>"",
                            "unitAddress"=>"",
                            "occupationCode"=>"",
                            "occupationCodeView"=>"",
                            "dutyLevel"=>"",
                            "dutyStatus"=>"",
                            "prpDcustomerPerson.agriFlag"=>"0",
                            "prpDcstprf.comCitycode"=>"",
                            "prpDcstprf.comCountycode"=>"",
                            "prpDcstprf.salsDepartcode"=>"",
                            "prpDcstprf.serStationcode"=>"",
                            "prpDcstprf.serPointcode"=>"",
                            "prpDcstprf.famliyMembersno"=>"",
                            "prpDcstprf.ageLt18no"=>"",
                            "prpDcstprf.ageGt65no"=>"",
                            "prpDcstprf.cultivatedLand"=>"",
                            "prpDcstprf.workerQuantity"=>"",
                            "prpDcstprf.woodLand"=>"",
                            "prpDcstprf.pigNum"=>"",
                            "prpDcstprf.cattleNum"=>"",
                            "prpDcstprf.sheepNum"=>"",
                            "prpDcstprf.otherNum"=>"",
                            "prpDcstprf.bankNo"=>"",
                            "prpDcstprf.accountName"=>"",
                            "prpDcstprf.bankName"=>"",
                            "prpDcstprf.householdIncome"=>"",
                            "prpDcstprf.houseNum"=>"",
                            "prpDcstprf.buildStructure"=>"",
                            "prpDcstprf.liaisonmanCode"=>"",
                            "prpDcstprf.liaisonmanName"=>"",
                            "prpDcstprf.chargePersonCode"=>"",
                            "prpDcstprf.chargePersonName"=>"",
                            "prpDcstprf.describes"=>"",
                            "CarPersonNo"=>"",
                            "licenseNo"=>"",
                            "vehicleName"=>"",
                            "engineNo"=>"",
                            "vinNo"=>"",
                            "enrollDate"=>"",
                            "policyEndDate"=>"",
                            "insureCompany"=>"",

                    );


                    $token_where['customerCName']="";
                    $token_where['identifyType']="01";
                    $token_where['identifyNumber']=$auto['IDENTIFY_NO'];
                    $token_where['syscode']="prpall";
                    $token_result=self::requestGetData($this->Token_url."?".http_build_query($token_where));

                    $token_get='/<input type="hidden" name="token" value="(.*)"\/>/';
                    preg_match_all($token_get, $token_result, $matches);//token值
                    $result['token']=$matches[1][0];


                    $arr=array();
                    foreach($result as $k=>$v)
                    {
                        $whex= iconv("UTF-8","GBK",$v);
                        $arr[$k]=$whex;
                    }

                    $add_idcard =  iconv("GBK", "UTF-8", self::requestPostData($this->Add_IdCardUrl,$arr));
                    preg_match_all("/<td width=\"30%\" class=\"right3\">(.*)<\/td>/Us", $add_idcard, $id_matches);
                    if(trim($id_matches[1][0]) !="")
                    {
                         $id_info['data'][0]['insuredCode'] = $id_matches[1][0];
                         if(strstr($str,"男性"))
                         {
                            $id_info['data'][0]['sex'] = 1;
                         }
                         else
                         {
                            $id_info['data'][0]['sex'] = 2;
                         }
                            return $id_info;

                    }
                    else
                    {
                        $this->error['errorMsg']="增加身份证失败。";
                        return false;
                    }
                }

        }
        else
        {
                    return false;
        }



    }

    private function Summary_info($auto=array(),$business=array(),$mvtalci=array())
    {



        $get_items = $this->get_items($business);

        if(!$get_items)
        {
            return false;
        }

        $car_info= $this->car_info($auto,$business,$mvtalci);
        $top_datas = $this->__top($auto,$business,$mvtalci);
        $card = $this->cards($auto,$business,$mvtalci);
        $data = array_merge($get_items,$car_info,$top_datas,$card);



        $data["prpCitemKind.shortRateFlag"]="2";
        $data["prpCitemKind.shortRate"]="100.0000";
        $data["prpCitemKind.currency"]="CNY";
        $data["prpCmainCommon.groupFlag"]="0";
        $data["prpCmain.preDiscount"]="";
        $data["sumBenchPremium"]=round($_SESSION['COUNT_PREMIUM']/$_SESSION['DISCOUNT'],2);
        $data["prpCmain.discount"]=$_SESSION['DISCOUNT'];
        $data["prpCmain.sumPremium"]=$_SESSION['COUNT_PREMIUM'];
        $data["premiumF48"]="5000";
        $data["prpCmain.sumNetPremium"]=$_SESSION['NET_PREMIUM'];
        $data["prpCmain.sumTaxPremium"]=$_SESSION['TAX'];
        $data["passengersSwitchFlag"]="";


        $data["prpCitemKindCI.shortRate"]="100";
        if($mvtalci['MVTALCI_PREMIUM']=="")
        {
            $data["prpCitemKindCI.familyNo"]="0";
        }
        else
        {
            $data["prpCitemKindCI.familyNo"]="1";
        }

        $data["cIBPFlag"]="1";
        $data["prpCitemKindCI.unitAmount"]="0";
        $data["prpCitemKindCI.startMinute"]="";
        $data["prpCitemKindCI.endMinute"]="";
        $data["prpCitemKindCI.id.itemKindNo"]="";
        $data["prpCitemKindCI.kindCode"]="050100";
        $data["prpCitemKindCI.clauseCode"]="050001";
        $data["prpCitemKindCI.riskPremium"]="0";
        $data["prpCitemKindCI.kindName"]="机动车交通事故强制责任保险";
        $data["prpCitemKindCI.calculateFlag"]="Y";
        $data["prpCitemKindCI.basePremium"]="";
        $data["prpCitemKindCI.quantity"]="1";
        $data["prpCitemKindCI.amount"]="122000";
        $data["prpCitemKindCI.deductible"]="0";
        $data["prpCitemKindCI.adjustRate"]=empty($mvtalci['MVTALCI_COUNT'])?"":$mvtalci['MVTALCI_COUNT'];
        $data["prpCitemKindCI.rate"]="0";
        $data["prpCitemKindCI.benchMarkPremium"]=empty($_SESSION['BASE_PREMIUM'])?"":$_SESSION['BASE_PREMIUM'];
        $data["prpCitemKindCI.disCount"]="1";
        $data["prpCitemKindCI.premium"]=empty($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
        $data["prpCitemKindCI.flag"]="";
        $data["prpCitemKindCI.netPremium"]=empty($_SESSION['NetPremium'])?"":$_SESSION['NetPremium'];
        $data["prpCitemKindCI.taxPremium"]=empty($_SESSION['MVTALCI_TAX_PREMIUM'])?"":$_SESSION['MVTALCI_TAX_PREMIUM'];
        $data["prpCitemKindCI.taxRate"]="6.00";
        $data["prpCitemKindCI.taxfee_gb"]="0";
        $data["prpCitemKindCI.taxfee_lb"]="0";
        $data["prpCitemKindCI.allTaxFee"]="";
        $data["prpCitemKindCI.allNetPremium"]="";
        $data["prpCitemKindCI.dutyFlag"]="2";




        $data["prpCcarShipTax.extendChar2"]="";
        $data["prpCcarShipTax.taxType"]="1";
        $data["prpCcarShipTax.calculateMode"]="C1";
        $data["prpCcarShipTax.leviedDate"]="";
        $data["prpCcarShipTax.carKindCode"]="A01";
        $data["prpCcarShipTax.model"]="B11";
        $data["prpCcarShipTax.taxPayerIdentNo"]="132825197804240020";
        $data["prpCcarShipTax.taxPayerNumber"]="132825197804240020";
        $data["prpCcarShipTax.carLotEquQuality"]=$auto['KERB_MASS'];
        $data["prpCcarShipTax.taxPayerCode"]="1100100002821644";
        $data["prpCcarShipTax.id.itemNo"]="1";
        $data["prpCcarShipTax.taxPayerNature"]="3";
        $data["prpCcarShipTax.taxPayerName"]="王桂宁";
        $data["prpCcarShipTax.taxUnit"]="";
        $data["prpCcarShipTax.taxComCode"]="";
        $data["prpCcarShipTax.taxComName"]="";
        $data["prpCcarShipTax.taxExplanation"]="";
        $data["prpCcarShipTax.taxAbateReason"]="08";
        $data["prpCcarShipTax.dutyPaidProofNo_1"]="";
        $data["prpCcarShipTax.dutyPaidProofNo_2"]="";
        $data["prpCcarShipTax.dutyPaidProofNo"]="";
        $data["prpCcarShipTax.taxAbateRate"]="50";
        $data["prpCcarShipTax.taxAbateAmount"]="";
        $data["prpCcarShipTax.taxAbateType"]="1";
        $data["prpCcarShipTax.taxUnitAmount"]="";
        $data["prpCcarShipTax.prePayTaxYear"]="2016";
        $data["prpCcarShipTax.prePolicyEndDate"]="";
        $data["prpCcarShipTax.payStartDate"]="2017-01-01";
        $data["prpCcarShipTax.payEndDate"]="2017-12-31";
        $data["prpCcarShipTax.thisPayTax"]=empty($mvtalci['TAX_PREMIUM'])?"":$mvtalci['TAX_PREMIUM'];
        $data["prpCcarShipTax.prePayTax"]="0";
        $data["prpCcarShipTax.taxItemCode"]="";
        $data["prpCcarShipTax.taxItemName"]="";
        $data["prpCcarShipTax.baseTaxation"]="";
        $data["prpCcarShipTax.taxRelifFlag"]="";
        $data["prpCcarShipTax.delayPayTax"]="0";
        $data["prpCcarShipTax.sumPayTax"]=empty($mvtalci['TAX_PREMIUM'])?"":$mvtalci['TAX_PREMIUM'];
        $data["prpCcarShipTax.flag"]="";

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


        $results = json_decode($this->requestPostData($this->checkAgentUrl,$data),true);
        if($results['totalRecords'] > 0)
        {
            foreach($results['data'][0]['prpDpayForPolicies'] as $k =>$v)
            {
                if($v['riskCode']=="DZA")
                {
                        if(!empty($mvtalci))
                        {
                            $CI_rate = $v['costRate']/100;
                            $sumaty_info["prpCcommissionsTemp_[0].costType"]="2";
                            $sumaty_info["prpCcommissionsTemp_[0].riskCode"]="DZA";
                            $sumaty_info["prpCcommissionsTemp_[0].adjustFlag"]="0";
                            $sumaty_info["prpCcommissionsTemp_[0].auditRate"]="";
                            $sumaty_info["prpCcommissionsTemp_[0].sumPremium"]=!isset($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
                            $sumaty_info["prpCcommissionsTemp_[0].costRate"]=$v['costRate'];
                            $sumaty_info["prpCcommissionsTemp_[0].costRateUpper"]=$v['costRateUpper'];
                            $sumaty_info["prpCcommissionsTemp_[0].coinsRate"]="100";
                            $sumaty_info["prpCcommissionsTemp_[0].coinsDeduct"]="1";
                            $sumaty_info["prpCcommissionsTemp_[0].costFee"]= !isset($_SESSION['NetPremium'])?"":$this->PayForSCMS($_SESSION['NetPremium'],$CI_rate);
                            $sumaty_info["prpCcommissionsTemp_[0].agreementNo"]=$v['id']['agreementNo'];
                            $sumaty_info["prpCcommissionsTemp_[0].configCode"]=$v['id']['configCode'];
                            $sumaty_info["prpCcommissionsTemp[3].costType"]="2";
                            $sumaty_info["prpCcommissionsTemp[3].riskCode"]="DZA";
                            $sumaty_info["prpCcommissionsTemp[3].adjustFlag"]=$v['adjustFlag'];
                            $sumaty_info["prpCcommissionsTemp[3].auditRate"]="";
                            $sumaty_info["prpCcommissionsTemp[3].sumPremium"]=!isset($mvtalci['MVTALCI_PREMIUM'])?"":$mvtalci['MVTALCI_PREMIUM'];
                            $sumaty_info["prpCcommissionsTemp[3].costRate"]="4";
                            $sumaty_info["prpCcommissionsTemp[3].costRateUpper"]="4";
                            $sumaty_info["prpCcommissionsTemp[3].coinsRate"]="100";
                            $sumaty_info["prpCcommissionsTemp[3].costFee"]=!isset($_SESSION['NetPremium'])?"":$this->PayForSCMS($_SESSION['NetPremium'],$CI_rate);
                            $sumaty_info["prpCcommissionsTemp[3].agreementNo"]=$v['id']['agreementNo'];
                            $sumaty_info["prpCcommissionsTemp[3].configCode"]=$v['id']['configCode'];

                        }
                }
                if($v['riskCode']=="DAA")
                {
                        if(!empty($business['POLICY']))
                        {
                            $BI_rate = $v['costRate']/100;
                            $sumaty_info["prpCcommissionsTemp[2].costType"]="2";
                            $sumaty_info["prpCcommissionsTemp[2].riskCode"]="DAA";
                            $sumaty_info["prpCcommissionsTemp[2].adjustFlag"]=$v['adjustFlag'];
                            $sumaty_info["prpCcommissionsTemp[2].auditRate"]="";
                            $sumaty_info["prpCcommissionsTemp[2].sumPremium"]=!isset($_SESSION['COUNT_PREMIUM'])?"":$_SESSION['COUNT_PREMIUM'];
                            $sumaty_info["prpCcommissionsTemp[2].costRate"]=$v['costRate'];
                            $sumaty_info["prpCcommissionsTemp[2].costRateUpper"]=$v['costRateUpper'];
                            $sumaty_info["prpCcommissionsTemp[2].coinsRate"]="100";
                            $sumaty_info["prpCcommissionsTemp[2].costFee"]=!isset($_SESSION['NET_PREMIUM'])?"":$this->PayForSCMS($_SESSION['NET_PREMIUM'],$BI_rate);
                            $sumaty_info["prpCcommissionsTemp[2].agreementNo"]=$v['id']['agreementNo'];
                            $sumaty_info["prpCcommissionsTemp[2].configCode"]=$v['id']['configCode'];

                        }
                }
            }
        }

        return $sumaty_info;

    }


    /**
     * [get_items 通过分配险种，返回具体参数]
     * @AuthorHTL
     * @DateTime  2016-12-15T11:20:11+0800
     * @param     [type]                   $business [传递数组]
     * @return    [type]                             [description]
     */
    private  function get_items($business = Array())
    {

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
                            $data["prpCitemKindsTemp[0].benchMarkPremium"]=$_SESSION['INSURANCE']['TVDI']['STANDARD_PREMIUM'];
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
                            $data["prpCitemKindsTemp[0].startHour"]="0";
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
                            $data["prpCitemKindsTemp[2].flag"]="100000";
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
                            $data["prpCitemKindsTemp[1].flag"]="100000";
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
                            $data["prpCitemKindsTemp[5].flag"]="200000";
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
                            $data["prpCitemKindsTemp[11].flag"]="200000";
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
            $comCode = $this->channel();
            if(!$comCode)
            {
                return false;
            }
            else
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





    private function __top($auto=Array(),$business=Array(),$mvtalci=Array())
    {


                $car= $this->car_info($auto,$business,$mvtalci);


                $data["carShipTaxPlatFormFlag"]="1";
                $data["randomProposalNo"]="2205344881497857768459";
                $data["initemKind_Flag"]="1";

                $data["editType"]="UPDATE";
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

                if(!empty($mvtalci['MVTALCI_PREMIUM']) && $mvtalci['MVTALCI_PREMIUM']!="")
                {
                    if($mvtalci['MVTALCI_NUMBER_INSURED']=="")
                    {
                       $data["prpCmainCI.proposalNo"]="";//交强险投保单号
                    }
                    else
                    {
                        $data["prpCmainCI.proposalNo"]=$mvtalci['MVTALCI_NUMBER_INSURED'];//交强险投保单号
                    }
                }


                if(count($business['POLICY']['BUSINESS_ITEMS'])>0)
                {
                    if($business['BUSINESS_NUMBER_INSURED']=="")
                    {
                       $data["prpCmain.proposalNo"]="";//商业险投保单号
                    }
                    else
                    {
                        $data["prpCmain.proposalNo"]=$business['BUSINESS_NUMBER_INSURED'];//商业险投保单号
                    }
                }


                $data["prpCmain.renewalFlag"]="";
                $data["activityFlag"]="0";
                $data["INTEGRAL_SWITCH"]="0";
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
                $data["sumAmountBI"]="";
                $data["isTaxDemand"]="1";
                $data["cIInsureFlag"]="1";
                $data["bIInsureFlag"]="1";
                $data["ciInsureSwitchKindCode"]="E01,E11,E12,D01,D02,D03";
                $data["ciInsureSwitchValues"]="1111111";
                $data["cIInsureMotorFlag"]="1";
                $data["mtPlatformTime"]="";
                $data["noPermissionsCarKindCode"]="E12";
                $data["isTaxFlag"]="1";
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
                $data["planFlag"]="1";
                $data["R_SWITCH"]="1";
                $data["biStartDate"]=!isset($business['BUSINESS_START_TIME'])?$mvtalci['MVTALCI_START_TIME']:$business['BUSINESS_START_TIME'];  //商业保险起止日期
                $data["ciStartDate"]=!isset($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];  //交强保险起止日期
                $data["ciStartHour"]="0";
                $data["ciStartMinute"]="0";
                $data["ciEndDate"]=!isset($mvtalci['MVTALCI_END_TIME'])?$business['BUSINESS_END_TIME']:$mvtalci['MVTALCI_END_TIME'];  //交强保险起止日期
                $data["ciEndHour"]="24";
                $data["ciEndMinute id"]="";
                $data["AGENTSWITCH"]="1";
                $data["JFCDSWITCH"]="19";
                $data["carShipTaxFlag"]="11";
                $data["commissionFlag"]="";
                $data["ICCardCHeck"]="";
                $data["riskWarningFlag"]="";
                $data["comCodePrefix"]="11";
                $data["DAGMobilePhoneNum"]="";
                $data["scanSwitch"]="1000000000";
                $data["haveScanFlag"]="0";
                $data["diffDay"]="90";
                $data["cylinderFlag"]="0";
                $data["ciPlateVersion"]="";
                $data["biPlateVersion"]="";
                $data["criterionFlag"]="0";
                $data["isQuotatonFlag"]="2";
                $data["quotationRisk"]="DAA";
                $data["getReplenishfactor"]="";
                $data["FREEINSURANCEFLAG"]="011111";
                $data["isMotoDrunkDriv"]="0";
                $data["immediateFlag"]="1";
                $data["immediateFlagCI"]="0";
                $data["claimAmountReason"]="";
                $data["isQueryCarModelFlag"]="1";
                $data["isDirectFee"]="";
                $data["userCode"]="980057";
                $data["comCode"]="11019874";
                $data["chgProfitFlag"]="00";
                $data["ciPlatTask"]="";
                $data["biPlatTask"]="";
                $data["upperCostRateBI"]="";
                $data["upperCostRateCI"]="";
                $data["rescueFundRate"]="0.01";
                $data["resureFundFee"]="7.38";
                $data["useCarshiptaxFlag"]="1";
                $data["taxFreeLicenseNo"]="";
                $data["isTaxFree"]="0";
                $data["premiumChangeFlag"]="";
                $data["operationTimeStamp"]=date("Y-m-d H:i:s");
                $data["VEHICLEPLAT"]="1";
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
                $data["checkTimeFlag"]="0";
                $data["checkUndwrt"]="0";
                $data["carDamagedNum"]="";
                $data["insurePayTimes"]="0";
                $data["claimAdjustValue"]="0.85";
                $data["operatorProjectCode"]="1-4079,2-4079,4-4079,5-4079";
                $data["lossFlagKind"]="";
                $data["chooseFlagCI"]="1";
                $data["unitedSaleRelatioStr"]="";
                $data["purchasePriceU"]="";
                $data["countryNatureU"]="";
                $data["insurancefee_reform"]="1";
                $data["operateDateForFG"]="";
                $data["prpCmainCommon.clauseIssue"]="2";
                $data["prpCmainCommon.key1"]="cd9c4aacbad7750d671b1a538173e9c3";
                $data["amountFloat"]="30";
                $data["vat_switch"]="1";
                $data["pm_vehicle_switch"]="";
                $data["electronicPolicyFlag"]="1";
                $data["isNetFlagEad"]="";
                $data["isNetFlag"]="1";
                $data["netCommission_SwitchEad"]="";
                $data["BiLastPolicyFlag"]="0";
                $data["CiLastPolicyFlag"]="0";
                $data["CiLastEffectiveDate"]="";
                $data["CiLastExpireDate"]="";
                $data["benchMarkPremium"]="";
                $data["BiLastEffectiveDate"]="";
                $data["BiLastExpireDate"]="";
                $data["lastTotalPremium"]="";
                $data["purchasePriceUFlag"]="";
                $data["startDateU"]="";
                $data["endDateU"]="";
                $data["biCiFlagU"]="";
                $data["biCiFlagIsChange"]="";
                $data["biCiDateIsChange"]="";
                $data["switchFlag"]="0";
                $data["relatedFlag"]="0";
                $data["riskCode"]="DAA";
                $data["prpCmain.riskCode"]="DAA";
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
                $data["qualificationNo"]="110111798546837001";
                $data["qualificationName"]="北京众志通达汽车修理有限公司";
                $data["OLD_STARTDATE_CI"]="";
                $data["OLD_ENDDATE_CI"]="";
                $data["prpCmainCommon.greyList"]="";
                $data["prpCmainCommon.image"]="";
                $data["reinComPany"]="";
                $data["reinPolicyNo"]="";
                $data["reinStartDate"]="";
                $data["reinEndDate"]="";
                $data["prpCmain.policyNo"]="";
                $data["prpCmainCI.policyNo"]="";
                $data["prpPhead.applyNo"]="";
                $data["prpPhead.endorseNo"]="";
                $data["prpPheadCI.applyNo"]="";
                $data["prpPheadCI.endorseNo"]="";
                $data["prpCmain.comCode"]="11019874";
                $data["comCodeDes"]="北京市燕山支公司车商业务二部";
                $data["prpCmain.handler1Code"]="09034148";
                $data["handler1CodeDes"]="张明明";
                $data["homePhone"]="13810009478";
                $data["officePhone"]="13810009478";
                $data["moblie"]="";
                $data["checkHandler1Code"]="1";
                $data["handler1CodeDesFlag"]="B";
                $data["handler1Info"]="09034148_FIELD_SEPARATOR_张明明_FIELD_SEPARATOR_13810009478_FIELD_SEPARATOR_13810009478_FIELD_SEPARATOR__FIELD_SEPARATOR_B_FIELD_SEPARATOR_1211012479";
                $data["prpCmainCommon.handler1code_uni"]="1211012479";
                $data["prpCmain.handlerCode"]="09034148";
                $data["handlerCodeDes"]="张明明";
                $data["homePhonebak"]="";
                $data["officePhonebak"]="";
                $data["mobliebak"]="";
                $data["handler1CodeDesFlagbak"]="";
                $data["prpCmainCommon.handlercode_uni"]="1211012479";
                $data["handlerInfo"]="09034148_FIELD_SEPARATOR_张明明_FIELD_SEPARATOR__FIELD_SEPARATOR__FIELD_SEPARATOR_FIELD_SEPARATOR__FIELD_SEPARATOR_1211012479";
                $data["prpCmain.businessNature"]="3";
                $data["businessNatureTranslation"]="兼业代理业务";
                $data["prpCmain.agentCode"]="11003O101351";
                $data["prpCmainagentName"]="北京众志通达汽车修理有限公司";
                $data["agentType"]="3O1000";
                $data["agentCode"]="11003O101351";
                $data["tempAgentCode"]="3O1000";
                $data["sumPremiumChgFlag"]="1";
                $data["prpCmain.sumPremium1"]="";
                $data["sumPayTax1"]="";
                $data["prpCmain.contractNo"]="";
                $data["prpCmain.operateDate"]=date("Y-m-d",time());
                $data["Today"]=date("Y-m-d",time());
                $data["OperateDate"]=date("Y-m-d",time());
                $data["prpCmain.makeCom"]="11019873";
                $data["makeComDes"]="北京市燕山支公司车险直销业务二部";
                $data["prpCmain.startDate"]=!isset($business['BUSINESS_START_TIME'])?$mvtalci['MVTALCI_START_TIME']:$business['BUSINESS_START_TIME'];  //商业保险起止日期
                $data["prpCmain.startHour"]="0";
                $data["prpCmain.startMinute"]="0";
                $data["prpCmain.endDate"]=!isset($business['BUSINESS_END_TIME'])?$mvtalci['MVTALCI_END_TIME']:$business['BUSINESS_END_TIME'];  //商业保险终止日期
                $data["prpCmain.endHour"]="24";
                $data["prpCmain.endMinute"]="0";
                $data["prpCmain.checkUpCode"]="";
                $data["prpCmainCI.startDate"]=!isset($mvtalci['MVTALCI_START_TIME'])?$business['BUSINESS_START_TIME']:$mvtalci['MVTALCI_START_TIME'];  //交强保险起止日期
                $data["prpCmainCI.startHour"]="0";
                $data["prpCmainCI.startMinute"]="0";
                $data["prpCmainCI.endDate"]=!isset($mvtalci['MVTALCI_END_TIME'])?$business['BUSINESS_END_TIME']:$mvtalci['MVTALCI_END_TIME']; //交强保险终止日期
                $data["prpCmainCI.endHour"]="24";
                $data["prpCmainCI.endMinute"]="0";
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
                $data["prpCfixationTemp.discount"]="20";
                $data["prpCfixationTemp.id.riskCode"]="DAA";
                $data["prpCfixationTemp.profits"]="-40.08";
                $data["prpCfixationTemp.cost"]="8";
                $data["prpCfixationTemp.taxorAppend"]="7";
                $data["prpCfixationTemp.payMentR"]="79.83";
                $data["prpCfixationTemp.basePayMentR"]="138.1";
                $data["prpCfixationTemp.poundAge"]="22";
                $data["prpCfixationTemp.basePremium"]="645.75";
                $data["prpCfixationTemp.riskPremium"]="883.8";
                $data["prpCfixationTemp.riskSumPremium"]="0";
                $data["prpCfixationTemp.signPremium"]="610.27";
                $data["prpCfixationTemp.isQuotation"]="";
                $data["prpCfixationTemp.riskClass"]="A547";
                $data["prpCfixationTemp.operationInfo"]="547";
                $data["prpCfixationTemp.realDisCount"]="72.25";
                $data["prpCfixationTemp.realProfits"]="-126.14";
                $data["prpCfixationTemp.realPayMentR"]="191.14";
                $data["prpCfixationTemp.remark"]="1101980043-3687";
                $data["prpCfixationTemp.responseCode"]="";
                $data["prpCfixationTemp.errorMessage"]="";
                $data["prpCfixationTemp.profitClass"]="E";
                $data["prpCfixationTemp.costRate"]="35";
                $data["prpCfixationTemp.unstandDiscount"]="0";
                $data["prpCfixationTemp.targetPayMentr"]="0";
                $data["prpCfixationTemp.targetPoundage"]="0";
                $data["prpCfixationTemp.targetProfitsClass"]="";
                $data["prpCfixationTemp.pricingModel"]="11";
                $data["prpCfixationCITemp.discount"]="50";
                $data["prpCfixationCITemp.id.riskCode"]="DZA";
                $data["prpCfixationCITemp.profits"]="17.92";
                $data["prpCfixationCITemp.cost"]="8";
                $data["prpCfixationCITemp.taxorAppend"]="7";
                $data["prpCfixationCITemp.payMentR"]="45.08";
                $data["prpCfixationCITemp.basePayMentR"]="22.54";
                $data["prpCfixationCITemp.poundAge"]="22";
                $data["prpCfixationCITemp.basePremium"]="24000";
                $data["prpCfixationCITemp.riskPremium"]="5410";
                $data["prpCfixationCITemp.riskSumPremium"]="0";
                $data["prpCfixationCITemp.signPremium"]="12000";
                $data["prpCfixationCITemp.isQuotation"]="";
                $data["prpCfixationCITemp.riskClass"]="A1";
                $data["prpCfixationCITemp.operationInfo"]="跟单规则1";
                $data["prpCfixationCITemp.realDisCount"]="77.71000000000001";
                $data["prpCfixationCITemp.realProfits"]="33.99";
                $data["prpCfixationCITemp.realPayMentR"]="29.01";
                $data["prpCfixationCITemp.remark"]="110000003-1";
                $data["prpCfixationCITemp.responseCode"]="";
                $data["prpCfixationCITemp.errorMessage"]="";
                $data["prpCfixationCITemp.profitClass"]="A";
                $data["prpCfixationCITemp.costRate"]="0";
                $data["prpCfixationCITemp.unstandDiscount"]="0";
                $data["prpCfixationCITemp.targetPayMentr"]="0";
                $data["prpCfixationCITemp.targetPoundage"]="0";
                $data["prpCfixationCITemp.targetProfitsClass"]="";
                $data["prpCfixationCITemp.pricingModel"]="11";
                $data["prpCsalesFixes[1].id.proposalNo"]="";
                $data["prpCsalesFixes[1].id.serialNo"]="35";
                $data["prpCsalesFixes[1].comCode"]="11019800";
                $data["prpCsalesFixes[1].businessNature"]="3";
                $data["prpCsalesFixes[1].riskCode"]="DAA";
                $data["prpCsalesFixes[1].version"]="11-43";
                $data["prpCsalesFixes[1].isForMal"]="1";
                $data["IS_LOAN_MODIFY"]="00";
                $data["isCarinfoPlat"]="00";
                $data["vehicleCode"]="";
                $data["kindAndAmount"]="";
                $data["isSpecialFlag"]="";
                $data["specialEngage"]="";
                $data["licenseNoCar"]="";
                $tops_Data = array_merge($car,$data);
                return $tops_Data;

    }



    private function cards($auto=Array(),$business=Array(),$mvtalci=Array())
    {

            if(!isset($auto['IDENTIFY_NO']) || $auto['IDENTIFY_NO']=="")
            {

                $this->error['errorMsg']="请设置客户身份证号码。";
                return false;

            }

                $id_info= $this->idcard($auto,$business,$mvtalci);
                if(!id_info)
                {
                    return false;
                }

                $data["prpCmainChannel.assetAgentName"]="";
                $data["prpCmainChannel.assetAgentCode"]="";
                $data["prpCmainChannel.assetAgentPhone"]="";
                $data["SYFlag"]="0";
                $data["MTFlag"]="0";
                $data["BMFlag"]="0";
                $data["STFlag"]="0";
                $data["hidden_index_citemcar"]="0";
                $data["editFlag"]="1";
                $data["prpCmainCommon.ext2"]="";
                $data["configedRepeatTimesLocal"]="5";
                $data["prpCinsureds_[0].insuredFlag"]="11100000000000000000000000000A";
                $data["iinsuredFlag"]="投保人/被保险人/车主";
                $data["iinsuredType"]="个人";
                $data["iinsuredCode"]=$id_info['data'][0]['insuredCode'];
                $data["iinsuredName"]=$auto['OWNER'];
                $data["iunitType"]="";
                $data["iidentifyType"]="身份证";
                $data["iidentifyNumber"]=!isset($auto['IDENTIFY_NO'])?"":$auto['IDENTIFY_NO'];
                $data["iinsuredAddress"]=!isset($business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'])?"":$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'];
                $data["iemail"]="";
                $data["iphoneNumber"]="";
                $data["imobile"]=$auto['MOBILE'];
                $data["iauditStatus"]="2";
                $data["iversionNo"]="3";
                $data["hidden_index_insured"]="0";
                $data["prpCinsureds[1].insuredFlag"]="11100000000000000000000000000A";
                $data["prpCinsureds[1].id.serialNo"]="1";
                $data["prpCinsureds[1].insuredType"]="1";
                $data["prpCinsureds[1].insuredNature"]="1";
                $data["prpCinsureds[1].insuredCode"]=$id_info['data'][0]['insuredCode'];
                $data["prpCinsureds[1].insuredName"]=$auto['OWNER'];
                $data["prpCinsureds[1].unitType"]="";
                $data["prpCinsureds[1].identifyType"]="01";
                $data["prpCinsureds[1].identifyNumber"]=$auto['IDENTIFY_NO'];
                $data["prpCinsureds[1].insuredAddress"]=!isset($business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'])?"":$business['DESIGNATED_DRIVER'][0]['DRIVER_ADDRESS'];
                $data["prpCinsureds[1].email"]="";
                $data["prpCinsureds[1].phoneNumber"]="";
                $data["prpCinsureds[1].drivingYears"]="";
                $data["prpCinsureds[1].mobile"]=$auto['MOBILE'];
                $data["prpCinsureds[1].postCode"]="";
                $data["prpCinsureds[1].versionNo"]="3";
                $data["prpCinsureds[1].auditStatus"]="2";
                $data["prpCinsureds[1].sex"]=$id_info['data'][0]['sex'];
                $data["prpCinsureds[1].countryCode"]="CHN";
                $data["prpCinsureds[1].flag"]="";
                $data["prpCinsureds[1].age"]="";
                $data["prpCinsureds[1].drivingLicenseNo"]=$auto['IDENTIFY_NO'];
                $data["prpCinsureds[1].drivingCarType"]="";
                $data["prpCinsureds[1].appendPrintName"]="";
                $data["prpCinsureds[1].causetroubleTimes"]="";
                $data["prpCinsureds[1].acceptLicenseDate"]="";
                $data["isCheckRepeat[1]"]="";
                $data["configedRepeatTimes[1]"]="";
                $data["repeatTimes[1]"]="";
                $data["prpCinsureds[1].unifiedSocialCreditCode"]="";
                $data["prpCinsureds[1].soldierRelations"]="";
                $data["prpCinsureds[1].soldierIdentifyType"]="";
                $data["prpCinsureds[1].soldierIdentifyNumber"]="";
                $data["idCardCheckInfo[1].insuredcode"]="";
                $data["idCardCheckInfo[1].insuredFlag"]="";
                $data["idCardCheckInfo[1].mobile"]="";
                $data["idCardCheckInfo[1].idcardCode"]="";
                $data["idCardCheckInfo[1].name"]="";
                $data["idCardCheckInfo[1].nation"]="";
                $data["idCardCheckInfo[1].birthday"]="";
                $data["idCardCheckInfo[1].sex"]=$id_info['data'][0]['sex'];
                $data["idCardCheckInfo[1].address"]="";
                $data["idCardCheckInfo[1].issure"]="";
                $data["idCardCheckInfo[1].validStartDate"]="";
                $data["idCardCheckInfo[1].validEndDate"]="";
                $data["idCardCheckInfo[1].samCode"]="";
                $data["idCardCheckInfo[1].samType"]="";
                $data["idCardCheckInfo[1].flag"]="0";
                $data["_insuredFlag_hide"]="委托人";
                $data["_insuredFlag"]="0";
                $data["_resident"]="";
                $data["_insuredType"]="1";
                $data["_insuredCode"]="";
                $data["_insuredName"]="";
                $data["customerURL"]="http://10.134.136.48:8300/cif";
                $data["_isCheckRepeat"]="";
                $data["_configedRepeatTimes"]="";
                $data["_repeatTimes"]="";
                $data["_identifyType"]="01";
                $data["_identifyNumber"]=$auto['IDENTIFY_NO']; //证件号码
                $data["_unifiedSocialCreditCode"]="";
                $data["_mobile"]="";
                $data["_mobile1"]="";
                $data["_sex"]="1";
                $data["_age"]="";
                $data["_drivingYears"]="";
                $data["_countryCode"]="CHN";
                $data["_insuredAddress"]="";
                $data["_postCode"]="";
                $data["_appendPrintName"]="";
                $data["group_code"]="";
                $data["_auditStatus"]="";
                $data["_auditStatusDes"]="";
                $data["_versionNo"]="";
                $data["_drivingLicenseNo"]="";
                $data["_soldierRelations"]="0";
                $data["_soldierIdentifyType"]="000";
                $data["_soldierIdentifyNumber"]="";
                $data["idCardCheckInfo.idcardCode"]="";
                $data["idCardCheckInfo.name"]="";
                $data["idCardCheckInfo.nation"]="";
                $data["idCardCheckInfo.birthday"]="";
                $data["idCardCheckInfo.sex"]="";
                $data["idCardCheckInfo.address"]="";
                $data["idCardCheckInfo.issure"]="";
                $data["idCardCheckInfo.validStartDate"]="";
                $data["idCardCheckInfo.validEndDate"]="";
                $data["idCardCheckInfo.samCode"]="";
                $data["idCardCheckInfo.samType"]="";
                $data["idCardCheckInfo.flag"]="0";
                $data["_drivingCarType"]="";
                $data["CarKindLicense"]="";
                $data["_causetroubleTimes"]="";
                $data["_acceptLicenseDate"]="";
                $data["_email"]="";
                $data["prpCmainCar.agreeDriverFlag"]="0";
                $data["updateIndex"]="-1";
                $data["prpBatchProposal.profitType"]="";
                $data["motorFastTrack_Amount"]="";
                $data["claimAdjustReason"]="B46";
                return $data;


    }


}
?>