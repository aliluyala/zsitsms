<?php
class InsuranceModule extends BaseModule
{
	public $baseTable = 'insurance_calculate_log';
	//区域代码(算价费率包)
	public $areaCode = 'sc';
	//模块描述
	public $describe = '算价纪录管理';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'queryPurchasePrice' => '新车购置价查询',
			'index'              => '浏览',
			'detailView'         => '详情',
			'createView'         => '新建',
			'save'               => '保存',
			'delete'             => '删除',
			'batchDelete'        => '批量删除',
			'modifyFilter'       => '编辑过滤',
			'lastyearview'       => '查询往年保单',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'calculate_no'                     => Array('5','S',true,'算价纪录编号','CAL'),//auto complete field(prefix:CAL)
			'autoid'                           => Array('50','N',false,'客户id'),
			'floating_rate'                    => Array('20','E',true,'交强险浮动因子'),
			'claim_records'                    => Array('20','E',true,'索赔记录'),
			'years_of_insurance'               => Array('20','E',true,'投保年度'),
			'designated_driver'                => Array('20','E',true,'指定驾驶人'),
			'driver_age'                       => Array('20','E',true,'驾驶人年龄'),
			'driver_sex'                       => Array('20','E',true,'驾驶人性别'),
			'driving_years'                    => Array('20','E',true,'驾驶人驾龄'),
			'driving_area'                     => Array('20','E',true,'行驶区域'),
			'average_annual_mileage'           => Array('20','E',true,'年均行驶里程'),
			'multiple_insurance'               => Array('20','E',true,'多险种优惠'),
			'discount'                         => Array('5','S',false,'折扣'),
			'insurance_company'                => Array('20','E',true,'保险公司'),
			'buy_types'                        => Array('5','S',true,'购买险种列表'),
			'mvtalci_start_time'               => Array('31','DT',false,'交强险生效时间'),
			'mvtalci_months'                   => Array('3','S',false,'交强险有效月数',0,0,2),
			'other_start_time'                 => Array('31','DT',false,'商业险生效时间'),
			'other_months'                     => Array('3','S',false,'商业险有效月数',0,0,2),
			'nieli_insurance_amount'           => Array('3','S',false,'新增设备损失险金额',0,0,999999999),
			'tvdi_insurance_amount'            => Array('3','S',false,'车损险保额',0,0,999999999),
			'doc_amount'                       => Array('3','S',false,'车损险免赔额',0,0,999999999),
			'ttbli_insurance_amount'           => Array('20','E',false,'三都险保额'),
			'twcdmvi_insurance_amount'         => Array('3','S',false,'盗抢险保额',0,0,999999999),
			'tcpli_insurance_driver_amount'    => Array('3','S',false,'座位险(司机)保额',0,0,999999999),
			'tcpli_insurance_passenger_amount' => Array('3','S',false,'座位险(乘客)保额',0,0,999999999),
			'passengers'                       => Array('3','S',false,'座位险乘客数量',0,0,999999999),
			'bsdi_insurance_amount'            => Array('20','E',false,'划痕险保额'),
			'bgai_insurance_amount'            => Array('3','S',false,'玻璃险保额',0,0,999999999),
			'glass_origin'                     => Array('20','E',false,'玻璃类型'),
			'stsfs_rate'                       => Array('3','S',false,'上浮比例',0,0,2),//指定专修厂特约条款

			'mvtalci_sum'                      => Array('17','S',false,'交强险总额'),
			'travel_tax_sum'                   => Array('17','S',false,'车船税总额'),
			'commercial_sum'                   => Array('17','S',false,'商业险合计'),
			//'net_sales'                        => Array('17','S',false,'网销合计'),
			'total_sum'		 	 	 	 	   => Array('17','S',false,'合计'),

			'associate_userid'                 => Array('55','N',true,'记录归属于组或用户，将决定其它用户访问此记录的权限'),
			'create_userid'                    => Array('51','N',true,'创建记录的操作员'),
			'modify_userid'                    => Array('52','N',false,'最后一次修改记录的操作员'),
			'create_time'                      => Array('35','DT',true,'记录创建的时间'),
			'modify_time'                      => Array('36','DT',false,'最后一次修改记录的时间'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'calculate_no'					  ,
			'autoid'						  ,
			'floating_rate'					  ,
			'claim_records'					  ,
			'years_of_insurance'			  ,
			'designated_driver' 			  ,
			'driver_age'					  ,
			'driver_sex'					  ,
			'driving_years'					  ,
			'driving_area'					  ,
			'average_annual_mileage'		  ,
			'multiple_insurance'			  ,
			'discount'						  ,
			'insurance_company'				  ,
			'buy_types'						  ,
			'mvtalci_start_time'			  ,
			'mvtalci_months'				  ,
			'other_start_time'				  ,
			'other_months'					  ,
			'nieli_insurance_amount'		  ,
			'tvdi_insurance_amount'			  ,
			'doc_amount'					  ,
			'ttbli_insurance_amount'		  ,
			'twcdmvi_insurance_amount'		  ,
			'tcpli_insurance_driver_amount'	  ,
			'tcpli_insurance_passenger_amount',
			'passengers'					  ,
			'bsdi_insurance_amount'			  ,
			'bgai_insurance_amount'		  	  ,
			'glass_origin'					  ,
			'stsfs_rate'					  ,
			'mvtalci_sum'  					  ,
			'travel_tax_sum' 				  ,
			'commercial_sum'				  ,
			//'net_sales'						  ,
			'total_sum'						  ,
			'associate_userid'				  ,
			'create_userid'					  ,
			'modify_userid'					  ,
			'create_time'					  ,
			'modify_time'					  ,
			);
	//列表字段
	public $listFields = Array(
			'calculate_no'		,
			'autoid'            ,
			'create_time'		,
			'modify_time'		,
			);
	//编辑字段
	public $editFields = Array(
			'calculate_no'					  ,
			'autoid'						  ,
			'floating_rate'					  ,
			'claim_records'					  ,
			'years_of_insurance'			  ,
			'designated_driver' 			  ,
			'driver_age'					  ,
			'driver_sex'					  ,
			'driving_years'					  ,
			'driving_area'					  ,
			'average_annual_mileage'		  ,
			'multiple_insurance'			  ,
			'insurance_company'				  ,
			'buy_types'						  ,
			'mvtalci_start_time'			  ,
			'mvtalci_months'				  ,
			'other_start_time'				  ,
			'other_months'					  ,
			'nieli_insurance_amount'		  ,
			'tvdi_insurance_amount'			  ,
			'doc_amount'					  ,
			'ttbli_insurance_amount'		  ,
			'twcdmvi_insurance_amount'		  ,
			'tcpli_insurance_driver_amount'	  ,
			'tcpli_insurance_passenger_amount',
			'passengers'					  ,
			'bsdi_insurance_amount'			  ,
			'bgai_insurance_amount'		  	  ,
			'glass_origin'					  ,
			'stsfs_rate'					  ,
			'associate_userid'				  ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'calculate_no'		,
			'autoid'            ,
			'create_userid'		,
			'create_time'		,
			'modify_time'		,
			);

	//允许批量修改字段
	public $batchEditFields = Array();
	//允许miss编辑字段
	public $missEditFields = Array(
			'calculate_no'					  ,
			'autoid'						  ,
			'floating_rate'					  ,
			'claim_records'					  ,
			'years_of_insurance'			  ,
			'designated_driver' 			  ,
			'driver_age'					  ,
			'driver_sex'					  ,
			'driving_years'					  ,
			'driving_area'					  ,
			'average_annual_mileage'		  ,
			'multiple_insurance'			  ,
			'discount'						  ,
			'insurance_company'				  ,
			'buy_types'						  ,
			'mvtalci_start_time'			  ,
			'mvtalci_months'				  ,
			'other_start_time'				  ,
			'other_months'					  ,
			'nieli_insurance_amount'		  ,
			'tvdi_insurance_amount'			  ,
			'doc_amount'					  ,
			'ttbli_insurance_amount'		  ,
			'twcdmvi_insurance_amount'		  ,
			'tcpli_insurance_driver_amount'	  ,
			'tcpli_insurance_passenger_amount',
			'passengers'					  ,
			'bsdi_insurance_amount'			  ,
			'bgai_insurance_amount'		  	  ,
			'glass_origin'					  ,
			'stsfs_rate'					  ,
			'mvtalci_sum'  					  ,
			'travel_tax_sum' 				  ,
			'commercial_sum'				  ,
			//'net_sales'						  ,
			'total_sum'						  ,
			'associate_userid'				  ,
			);
	//默认排序
	public $defaultOrder = Array('create_time','ASC');
	//详情入口字段
	public $enteryField = '';//calculate_no
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array(
		'floating_rate'          => Array('A4','A1','A2','A3','A5','A6'),
		'claim_records'          => Array('MORE_TWO_YEARS_NO_CLAIM','TWO_YEAR_NO_CLAIM','LAST_YEAR_NO_CLAIM','FIRST_YEAR_INSURANCE','LAST_YEAR_CLAIM_ONE','LAST_YEAR_CLAIM_TWO','LAST_YEAR_CLAIM_THREE','LAST_YEAR_CLAIM_FOUR','LAST_YEAR_CLAIM_FIVE_ABOVE'),
		'years_of_insurance'     => Array('FIRST_YEAR_INSURANCE','RENEWAL_OF_INSURANCE'),
		'designated_driver'      => Array('DESIGNATED_DRIVER','NO_DESIGNATED_DRIVER'),
		'driver_age'             => Array('LESS_25_AGE','25_30_AGE','30_40_AGE','40_60_AGE','GREATER_60_AGE'),
		'driver_sex'             => Array('MALE','FEMALE'),
		'driving_years'          => Array('LESS_1_YEARS','1_3_YEARS','GREATER_3_YEARS'),
		'driving_area'           => Array('CHINA_TERRITORY','THE_PROVINCE'),
		'average_annual_mileage' => Array('LESS_30000_KM','30000_50000_KM','GREATER_50000_KM_1.1','GREATER_50000_KM_1.2','GREATER_50000_KM_1.3'),
		'multiple_insurance'     => Array('MULTIPLE_INSURANCE_0.95','MULTIPLE_INSURANCE_0.96','MULTIPLE_INSURANCE_0.97','MULTIPLE_INSURANCE_0.98','MULTIPLE_INSURANCE_0.99','MULTIPLE_INSURANCE_1'),
		'ttbli_insurance_amount' => Array('50000','100000','150000','200000','300000','500000','1000000'),
		'bsdi_insurance_amount'  => Array('2000','5000','10000','20000'),

		'use_character'          => Array('','NON_OPERATING','OPERATING'),
		'origin'                 => Array('DOMESTIC','IMPORT'),
		'glass_origin'           => Array('DOMESTIC','IMPORTED'),
		'vehicle_type'           => Array('PRIVATE','ENTERPRISE','AUTHORITY','LEASE_RENTAL','CITY_BUS','HIGHWAY_BUS','TRUCK','TRAILER','SPECIAL_AUTO','MOTORCYCLE','DUAL_PURPOSE_TRACTOR','TRANSPORT_TRACTOR','LOW_SPEED_TRUCK'),

		'insurance_company'      => Array('PICC','PINGAN','CPIC'),
	);

	//字段关联
	public $associateTo = Array(
		'create_userid'	=> array('MODULE','User','detailView','id','user_name'),
		'modify_userid'	=> array('MODULE','User','detailView','id','user_name'),
		'autoid'		=> array('MODULE','Accounts','detailView','id','owner'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'create_userid';

	public function autoCompleteFieldValue($field,$pfx)
	{
		if($field == 'calculate_no')
		{
			global $APP_ADODB;
			$sql = "SELECT ID FROM  {$this->baseTable}_seq  LIMIT 1;";
			$result = $APP_ADODB->Execute($sql);
			if($result && !$result->EOF)
			{
				return  $pfx.sprintf("%012d",$result->fields['id']);
			}
		}
		return parent::autoCompleteFieldValue($field,$pfx);
	}
	function _getData($str,$data){
		return isset($data[$str]) ? $data[$str] : NULL;
	}
	//获取车龄(当前日期-注册日期)
	/*function getCOTY($current_date,$register_date,$ceil = true){
        $years = (date("m",strtotime($current_date)) - date("m",strtotime($register_date)))/12 + (date("Y",strtotime($current_date)) - date("Y",strtotime($register_date)));
        $years = $ceil ? ceil($years) : $years;
        return $years > 0 ? $years : 1;
    }*/
    //获取车龄(取月)
    function getCOTY($other_start_time,$register_date){
    	$month = (date("m",strtotime($other_start_time)) - date("m",strtotime($register_date))) + (date("Y",strtotime($other_start_time)) - date("Y",strtotime($register_date)))*12 - 1;
    	return $month < 1 ? 1 : $month;
    }
    //获取车龄(取月) for 车损险
    function getCOTY_tvdi($other_start_time,$register_date){
    	$month = (date("m",strtotime($other_start_time)) - date("m",strtotime($register_date))) + (date("Y",strtotime($other_start_time)) - date("Y",strtotime($register_date)))*12;
    	return $month < 1 ? 1 : $month;
    }
    //计算折后价
    function getDiscountPrice($data,$discount){
    	foreach ($data as $key => $val) {
    		if($key != "MVTALCI" && $key != "TRAVEL_TAX" && $key != "DEPRECIATION_PRICE" && $key != "TOTAL_CAST"){
    			$data[$key] = ROUND($val * $discount,2);
    		}
    	}
    	return $data;
    }
    function getTotalSUM($data){
    	$sum = 0;
    	foreach ($data as $key => $val) {
    		if($key != "DEPRECIATION_PRICE" && $key != "TOTAL_CAST"){
    			$sum += $val;
    		}
    	}
    	return $sum;
    }
    function getCommercialSUM($data){
    	$sum = 0;
    	foreach ($data as $key => $val) {
    		if($key != "MVTALCI" && $key != "TRAVEL_TAX" && $key != "DEPRECIATION_PRICE" && $key != "TOTAL_CAST"){
    			$sum += $val;
    		}
    	}
    	return $sum;
    }
    function getLastRecordID(){
    	global $APP_ADODB;
    	$sql = "SELECT id FROM insurance_calculate_log ORDER BY create_time DESC,modify_time DESC LIMIT 1;";
    	$result = $APP_ADODB->Execute($sql);
    	return $result->RecordCount() ? $result->fields['id'] : 0;
    }
    function getVehicleObj(){
    	require_once(_ROOT_DIR."/modules/Insurance/VehicleInfo.class.php");
    	return new VehicleInfo();
    }
    function getOneRecordset($id,$userids,$groupids){
    	if(!isset($id) || empty($id))
    		return NULL;
    	$result = parent::getOneRecordset($id,$userids,$groupids);
    	return $result[0];
    }
	
	   //生成验证码
    public function getImage($url, $data)
    {
        $imageDir  = './cache/jiangsuImage';
        $imageCode = $this->get($url, $data);
        if (!is_dir($imageDir)) {
            mkdir($imageDir, true);
            chmod($imageDir, 0775);
        }
        $image_name = $imageDir . '/image_' . time() . '.png';
        if (file_exists($_SESSION['JSIMG'])) {

            unlink($_SESSION['JSIMG']);
        }
        $_SESSION['JSIMG'] = $image_name;
        file_put_contents($image_name, $imageCode);
        chmod($image_name, 0775);
        return $image_name;
    }

    public function get($url, $post, $head = false, $foll = 1, $ref = false)
    {
        $curl = curl_init(); // 启动一个CURL会话
        if ($head) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $head); //模似请求头
        }
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)');
        //  curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url . "?" . http_build_query($post)); // 要访问的地址
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $foll); // 使用自动跳转
        //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_COOKIEJAR, './confirmImage.txt'); // 存放Cookie信息的文件名称
        curl_setopt($curl, CURLOPT_COOKIEFILE, './confirmImage.txt'); // 读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); //解释gzip
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环

        if ($foll == 1) {
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        } else {
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
        $tmpInfo = curl_exec($curl); // 执行操作

        if (empty($tmpInfo)) {
            return -1;
        }

        $curlInfo = curl_getinfo($curl);
        return $tmpInfo;
    }

    public function post($url, $post, $head = false, $foll = 1, $ref = false)
    {
        $curl = curl_init(); // 启动一个CURL会话
        if ($head) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $head); //模似请求头
        }

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept-Language: zh-CN', 'Accept: text/html, application/xhtml+xml, */*', 'Accept-Encoding: gzip, deflate'));
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $foll); // 使用自动跳转
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post)); // Post提交的数据包
        //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_COOKIEJAR, './confirmImage.txt'); // 存放Cookie信息的文件名称
        curl_setopt($curl, CURLOPT_COOKIEFILE, './confirmImage.txt'); // 读取上面所储存的Cookie信息
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); //解释gzip
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环

        if ($foll == 1) {
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        } else {
            curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
        $tmpInfo = curl_exec($curl); // 执行操作
        $getinfo = curl_getinfo($curl);
        // if(strstr($getinfo['url'],$this->modelrice) || strstr($getinfo['url'],$this->modelUrl))
        // {
        $tmpInfo = mb_convert_encoding($tmpInfo, "UTF-8", "gb2312");
        // }
        return $tmpInfo;
    }
    //查询
    public function getSearchInfo($url, $postData)
    {
        $dimensionSelect = isset($postData['dimensionSelect']) ? $postData['dimensionSelect'] : '02';
        if (empty($dimensionSelect)) {
            return -1;
        }
        $url  = $url . '?dimensionSelect=' . $dimensionSelect . '&';
        $data = array(
            'queryLicensetype'    => '',
            'queryCredentialcode' => '01', //??
            'queryChecked'        => '',
            'policyNo'            => '',
            'vin'                 => '',
            'licensetype'         => '',
            'carmark'             => '',
            'credentialcode'      => '01', //??
            'credentialno'        => '',
            'cacciid'             => '',
            'taccidt'             => '',
            'dname'               => '',
            'lastNo'              => '',
        );
        $selectArray = array(
            '01' => array('policyNo'),
            '02' => array('vin'),
            '03' => array('licensetype', 'carmark'),
            '04' => array('credentialcode', 'credentialno'),
            '05' => array('cacciid', 'caccidt'),
            '06' => array('dname', 'lastNo'),
        );
        foreach ($selectArray[$dimensionSelect] as $k => $v) {
            if (array_key_exists($v, $postData)) {
                $data[$v] = iconv('utf8', 'gb2312', $postData[$v]);
            }
        }
        // print_r($data);
        $url = $url . http_build_query($data);

        $CheckBoxGroup1 = array(
            '02' => '交强险承保',
            '03' => '交强险理赔',
            '04' => '商业险承保',
            '08' => '商业险理赔',
            //'05' => '交管车辆车主',
            //'06' => '交管驾驶员',
            '07' => '交管违法',
            //'09' => '交管事故',
        ); //查询信息
        foreach ($CheckBoxGroup1 as $k => $v) {
            $url .= '&CheckboxGroup1=' . $k;
        }
        // echo $url.'<br>';
        $rs = $this->post($url, array()); //var_dump($rs);
        if (strpos($rs, "window.parent.location = '/sinoiais'")) {
            return -1; //没有登录
        }
        return $this->preg($rs);
    }

    public function login($url)
    {
        $rs = $this->get($url, array());
    }
    public function loginin($url, $data)
    {
        $rs = self::post($url, $data);
        $rs = json_decode($rs, true);

        switch ($rs['msg']) {
            case 'success':return 1;
                break;
            case 'userCodeError':return '用户名错误';
                break;
            case 'passwordError':return '密码错误';
                break;
            case 'randomError':return '验证码错误';
                break;
        }

    }
    public function preg($str)
    {
       // echo $str;
        $pregs = "/<div.*class=\"box\">(.*)<\/table>.*<\/div>/Us";
        preg_match_all($pregs, $str, $arr);
        if (empty($arr)) {
            return -1;
        }
        $htmls          = $arr[1];
        $preg_title     = "/<h1>(.*?)<\/h1>/"; //匹配历年理赔标题
        $preg_name      = "/<th>(.*)<\/th>/"; //匹配各个理赔标题的详细标题
        $preg_value     = "/<tr class=\"tr1\">(.*)<\/tr>/Us"; //匹配各个理赔的详情条数
        $preg_per_value = '/<td>(.*)<\/td>/'; //匹配各个理赔详细值
        $names          = array();
        $values         = array();
        $rt             = array();
        foreach ($htmls as $k => $v) {
            preg_match_all($preg_title, $v, $arr_title);
            $titles[]        = $arr_title[1][0];
            $rt[$k]['title'] = $arr_title[1][0];

            preg_match_all($preg_name, $v, $arr_name);
            $names[]         = $arr_name[1];
            $rt[$k]['names'] = $arr_name[1];

            preg_match_all($preg_value, $v, $arr_value);
            foreach ($arr_value[1] as $vk => $vv) {
                $right_values_html = preg_replace("/<!--(.*)-->/", '', $vv); //去掉被注销的代码
                
                $right_values_html = preg_replace("/<input(.*)>/", '', $vv); //去掉按钮

                preg_match_all($preg_per_value, $right_values_html, $arr_per_value);

                $values[$k][] = $arr_per_value[1];

                if($k==2 && $vk==0){
                    //最近一期商业险购买险种
                    $xz_str = $this->post('http://10.103.7.232:88/sinoiais/insurance/viewIAPMMain.do?confirmSequenceNo='.$values[$k][$vk][0].'&riskType=2', array());
                    $preg_xz_value = "/<th nowrap width=\"5%\">(.*)<\/th>/si"; //匹配
                    preg_match_all($preg_xz_value, $xz_str, $xz_arr);
                    $preg_xz_arr = explode('，', mb_substr(str_replace(array("\r","\n","\r\n"), '', strip_tags($xz_arr[1][0])),3));
                    array_push($arr_per_value[1], $preg_xz_arr);
                }
                if (empty($arr_per_value[1])) {
                    $rt[$k]['values'] = '';
                } else {
                    $rt[$k]['values'][] = $arr_per_value[1];
                }
            }
        }
        return $rt;
    }
};



?>