<?php
/**
 * 项目:           车险保费在线计算接口
 * 文件名:         Jiangsu_PICCKHYXSC_PC.class.php
 * 版权所有：      成都启点科技有限公司.
 * 作者：          Tang DaYong
 * 版本：          1.0.0
 *
 * 中国人保四川客户营销管理系统算价接口
 *
 **/
//error_reporting(E_ALL ^ E_NOTICE);
class Jiangsu_PICCKHYXSC_PC
{
	const formFile = 'JS_Calculate.tpl';
	const company  = 'PICC';
    private $error    = "";//设置错误信息成员属性（默认值）

    


	private $setItems = array(
		'username' => '登录名',
		'password' => '密码',
	);

	

	//车辆种类转换
	private $vehicle_type = Array(
		            'A01'=>'PASSENGER_CAR'              ,
					'B01'=>'TRUCK'                      ,
					'B02'=>'SEMI_TRAILER_TOWING'        ,
					'B11'=>'THREE_WHEELED'              ,
					'B12'=>'LOW_SPEED_TRUCK'            ,
					'B13'=>'VAN'                        ,
					'B91'=>'DUMP_TRAILER'               ,
		            'C01'=>'FUEL_TANK_CAR'              ,
		            'C02'=>'TANK_CAR'                   ,
		            'C03'=>'THE_LIQUID_TANK'            ,
		            'C04'=>'REFRIGERATED'               ,
		            'C11'=>'TANK_TRAILER'               ,
		            'C20'=>'BULLDOZER'                  ,
		            'C22'=>'WRECKER'                    ,
		            'C23'=>'SWEEPER'                    ,
		            'C24'=>'CLEAN_THE_CAR'              ,
		            'C25'=>'CARRIAGE_HOIST'             ,
		            'C26'=>'LOADING_AND_UNLOADING'      ,
		            'C27'=>'LIFT_TRUCK'                 ,
		            'C28'=>'CONCRETE_MIXER_TRUCK'       ,
		            'C29'=>'MINING_VEHICLE'             ,
		            'C30'=>'PROFESSIONAL_TRAILER'       ,
		            'C31'=>'SPECIAL_TWO_TRAILER'        ,
		            'C39'=>'SPECIAL_TWO_OTHER'          ,
		            'C41'=>'TV_TRUCKS'                  ,
		            'C42'=>'FIRE_ENGINE'                ,
		            'C43'=>'MEDICAL_VEHICLE'            ,
		            'C44'=>'OIL_STEAM'                  ,
		            'C45'=>'ROAD_VEHICLES'              ,
		            'C46'=>'MINE_CAR'                   ,
		            'C47'=>'ARMORED_CAR'                ,
		            'C48'=>'AMBULANCE'                  ,
		            'C49'=>'MONITORING_CAR'             ,
		            'C50'=>'RADAR_VEHICLE'              ,
		            'C51'=>'X_OPTICAL_CAR'              ,
		            'C52'=>'TELECOM_ENGINEERING'        ,
		            'C53'=>'ELECTRICAL_ENGINEERING'     ,
		            'C54'=>'PROFESSIONAL_NET_WATERWHEEL',
		            'C55'=>'INSULATION_CAR'             ,
		            'C56'=>'POSTAL_CAR'                 ,
		            'C57'=>'POLICE_SPECIAL_VEHICLE'     ,
		            'C58'=>'CONCRETE_PUMP_TRUCK'        ,
		            'C61'=>'SPECIAL_THREE_TRAILER'      ,
		            'C69'=>'SPECIAL_THREE_OTHER'        ,
		            'C90'=>'CONTAINER_TRACTORS'         ,
		            'D01'=>'MOTORCYCLE'                 ,
		            'D02'=>'THREE_MOTORCYCLE'           ,
		            'D03'=>'SIDECAR'                    ,
		            'E01'=>'TRACTOR'                    ,
		            'E11'=>'COMBINE_HARVESTER'          ,
		            'Z99'=>'OTHER_VEHICLES'             ,
                    );
	private $use_character = Array(
		            '211' => 'NON_OPERATING_PRIVATE'    ,
		            '212' => 'NON_OPERATING_ENTERPRISE' ,
		            '213' => 'NON_OPERATING_AUTHORITY'  ,
		            '111' => 'OPERATING_LEASE_RENTAL'   ,
		            '112' => 'OPERATING_CITY_BUS'       ,
		            '113' => 'OPERATING_HIGHWAY_BUS'    ,
		            '220' => 'NON_OPERATING_TRUCK'      ,
		            '120' => 'OPERATING_TRUCK'          ,
		            '280' => 'DUAL_PURPOSE_TRACTOR'     ,
		            '180' => 'TRANSPORT_TRACTOR'        ,
					'000' => 'GENERAL'                  ,
                    '190' => 'OPERATING_OTHER'          ,
                    '290' => 'NON_OPERATING_OTHER'
					);

	private $clause_Type = Array(
					'MOTORCYCLE'			   		=>'F42',
					'NON_OPERATING_PRIVATE'    		=>'F42',
					'NON_OPERATING_ENTERPRISE' 		=>'F41',
					'NON_OPERATING_AUTHORITY'  		=>'F41',
					'OPERATING_LEASE_RENTAL'   		=>'F43',
					'OPERATING_CITY_BUS'       		=>'F43',
					'OPERATING_HIGHWAY_BUS'    		=>'F43',
					'NON_OPERATING_TRUCK'      		=>'F41',
					'OPERATING_TRUCK'          		=>'F43',
					'NONE_OPERATING_TRAILER'   		=>'F42',
					'SPECIAL_AUTO'             		=>'F42',
					'DUAL_PURPOSE_TRACTOR'     		=>'F42',
					'OPERATING_LOW_SPEED_TRUCK'		=>'F43',
					'NON_OPERATING_LOW_SPEED_TRUCK' =>'F42',
		);
	 /**
     * 构造函数
     * 参数:
     * @config          必需。配置 数组
     * @cachePath       必需。缓存目录 绝对路径
     **/
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

		$this->loginAarray=array("loginType"=>'0',"j_host"=>"157.122.153.67:9000","j_checkcode"=>"",
		                         "lt"=>"","_eventId"=>"submit","j_username"=>$user,
								 "j_password"=>Sha1($password));//登陆条件
		$this->UrlLogin="http://157.122.153.67:9000/khyx/j_spring_security_check";//登陆处理查询
		$this->UrlOffter="http://157.122.153.67:9000/khyx/qtr/price/quote.do";//车险报价查询
		$this->PurchasePriceUrl="http://157.122.153.67:9000/khyx/vehicle/jyQuery.do";//车辆购置价查询
		$this->calActualValUrl="http://157.122.153.67:9000/khyx/price/calActualVal.do";//折旧价计算
		$this->Url_Cheack="http://157.122.153.67:9000/khyx/vehicle/JSPlatQueryCheck.do";//验证码查询
		$this->Url_Verification="http://157.122.153.67:9000/khyx/vehicle/JSPlatQueryCar.do";//验证码查询交管车辆信息

		if(empty($cachePath))
		{
			$this->cookie_file = dirname(__FILE__).'/picckhyxsc_cookie.txt';
		}
		else
		{
			$this->cookie_file = $cachePath.'/picckhyxsc_cookie.txt';  //COOKIE文件存放地址
		}
		$this->NEW_EQRIPNENT="http://157.122.153.67:9000/khyx/qtr/price/calDeviceActualValue.do";//新增设备条件

	}

	/**
	 * 获取设置项目
	 **/
	public function getSetItems()
	{
		return $this->setItems;
	}

	/**
	 * 获取表单模板文件名
	 **/
	public function getFormFile()
	{
		return self::formFile;
	}

	private function requestData($url,$post,$head=false,$foll=1,$ref=false)
	{
		$ret = $this->post($url,$post,$head,$foll,$ref);
		if($ret === -1)
		{
			$ret = $this->post($this->UrlLogin,$this->loginAarray);
			if($ret === -1)
			{
				$ret = false;
			}
			else
			{
				$ret = $ret = $this->post($url,$post,$head,$foll,$ref);
			}
		}
		return $ret;
	}

	private function post($url,$post,$head=false,$foll=1,$ref=false){
	    $curl = curl_init(); // 启动一个CURL会话
	    if($head){
	    curl_setopt($curl,CURLOPT_HTTPHEADER,$head);//模似请求头
	    }

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
	    curl_setopt($curl, CURLOPT_TIMEOUT, 120); // 设置超时限制防止死循环

		if($foll==1){
			curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		}else{
			curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		}
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		curl_setopt($curl,CURLINFO_HEADER_OUT,1);
	    $tmpInfo = curl_exec($curl); // 执行操作

		if(empty($tmpInfo)) return -1;
		$curlInfo = curl_getinfo($curl);

		if(stripos($curlInfo['url'],'http://157.122.153.67:9000/khyx/login.jsp') === false)
		{
			return $tmpInfo;
		}
		//没有登录
	    return -1;
	}
	/**
	 * [getLastError 返回最后一次错误信息]
	 * @AuthorHTL
	 * @DateTime  2016-03-29T15:24:59+0800
	 * @return    [error]  返回保存的错误信息
	 */
	public function getLastError()
	{
		return $this->error;
	}



   /**
     * 请求人保查价
     * 参数:
     * @$auto()   business()     mvtalci()    必需。配置 数组
	 *  成功 返回数组 失败返回false
     **/


	public  function  premium($auto=array(),$business=array(),$mvtalci=array())
	{
		//$data=array();
		$data=$this->datas($auto,$business,$mvtalci);//请求参数
		if(!empty($mvtalci))
		{
			$business['POLICY']['BUSINESS_ITEMS'][] = 'MVTALCI';
		}

		if(isset($business['POLICY']['BUSINESS_ITEMS'])){
			foreach($business['POLICY']['BUSINESS_ITEMS'] as $key =>$val){
						if($val== "MVTALCI"){
							$data["prpCitemKindsTemp[0].kindCode"]="050100";
							$data["prpCitemKindsTemp[0].kindName"]="交强";
							$data["prpCitemKindsTemp[0].rate"]="";
							$data["prpCitemKindsTemp[0].benchMarkPremium"]="";
							$data["prpCitemKindsTemp[0].disCount"]="";
							$data["prpCitemKindsTemp[0].premium"]="";
							$data["prpCitemKindsTemp[0].amount"]="";
							$data["mainKindValue[0]"]='122000';
							$data["mainKindName[0]"]="交强";
							$data["prpCitemKindVos[0].kindCode"] = "050100";
							$data["prpCitemKindVos[0].kindName"] = "交强";
							$data["prpCitemKindVos[0].amount"] = "122000";
							$data["prpCitemKindVos[0].chooseFlag"] = "true";

						}else if($val== "TVDI"){
							$data["mainKindName[1]"]="车损";
							$data["relateSpecial[1]"]="050930";
							$data["prpCitemKindsTemp[1].kindCode"] = "050202";
							$data["prpCitemKindsTemp[1].kindName"]="车损";
							$data["prpCitemKindsTemp[1].rate"] = "";
							$data["prpCitemKindsTemp[1].benchMarkPremium"] = "";
							$data["prpCitemKindsTemp[1].disCount"] = "";
							$data["prpCitemKindsTemp[1].premium"] = "";
							$data["prpCitemKindsTemp[1].amount"] = "";
							$data["mainKindValue[1]"] = $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[1].kindCode"] = "050202";
							$data["prpCitemKindVos[1].kindName"] = "车损";
							$data["prpCitemKindVos[1].amount"] = $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[1].chooseFlag"] = "true";

						}else if($val== "TTBLI"){
							$data["mainKindName[3]"]="三者";
							$data["relateSpecial[3]"]="050931";
							$data["prpCitemKindsTemp[3].kindCode"] = "050602";
							$data["prpCitemKindsTemp[3].kindName"]="三者";
							$data["prpCitemKindsTemp[3].rate"] = "";
							$data["prpCitemKindsTemp[3].benchMarkPremium"] = "";
							$data["prpCitemKindsTemp[3].disCount"] = "";
							$data["prpCitemKindsTemp[3].premium"] = "";
							$data["prpCitemKindsTemp[3].amount"] = "";
							$data["mainKindValue[3]"] = $business['POLICY']['TTBLI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[3].amount"]=$business['POLICY']['TTBLI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[3].kindCode"]="050602";
							$data["prpCitemKindVos[3].kindName"]="三者";
							$data["prpCitemKindVos[3].chooseFlag"]="true";


						}else if($val== "TWCDMVI"){
							$data["mainKindName[2]"]="盗抢";
							$data["relateSpecial[2]"]="050932";
							$data["prpCitemKindsTemp[2].kindCode"] = "050501";
							$data["prpCitemKindsTemp[2].kindName"]="盗抢";
							$data["prpCitemKindsTemp[2].rate"] = "";
							$data["prpCitemKindsTemp[2].benchMarkPremium"] = "";
							$data["prpCitemKindsTemp[2].disCount"] = "";
							$data["prpCitemKindsTemp[2].premium"] = "";
							$data["prpCitemKindsTemp[2].amount"] = "";
							$data["mainKindValue[2]"] = $business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[2].kindCode"]="050501";
							$data["prpCitemKindVos[2].kindName"]="盗抢";
							$data["prpCitemKindVos[2].amount"]=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[2].chooseFlag"]="true";


						}else if($val== "TCPLI_DRIVER"){
						    $data["mainKindName[4]"]="车上(司机)";
							$data['relateSpecial[4]']="050933";
							$data["prpCitemKindsTemp[4].kindCode"] = "050711";
							$data["prpCitemKindsTemp[4].kindName"]="车上(司机)";
							$data["prpCitemKindsTemp[4].rate"] = "";
							$data["prpCitemKindsTemp[4].benchMarkPremium"] = "";
							$data["prpCitemKindsTemp[4].disCount"] = "";
							$data["prpCitemKindsTemp[4].premium"] = "";
							$data["prpCitemKindsTemp[4].amount"] = "";
							$data["mainKindValue[4]"] = $business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
							$data["prpCitemKindVos[4].kindCode"]="050711";
							$data["prpCitemKindVos[4].kindName"]="车上(司机)";
							$data["prpCitemKindVos[4].amount"]=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
							$data["prpCitemKindVos[4].chooseFlag"]="true";


						}else if($val== "TCPLI_PASSENGER"){
							$data["mainKindName[5]"]="车上(乘客)";
							$data['relateSpecial[5]']="050934";
							$data["prpCitemKindsTemp[5].kindCode"] = "050712";
							$data["prpCitemKindsTemp[5].kindName"]="车上(乘客)";
							$data["prpCitemKindsTemp[5].rate"] = "";
							$data["prpCitemKindsTemp[5].benchMarkPremium"] = "";
							$data["prpCitemKindsTemp[5].disCount"] = "";
							$data["prpCitemKindsTemp[5].premium"] = "";
							$data["prpCitemKindsTemp[5].amount"] = "";
							$data["mainKindValue[5]"] = $business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
							$data["quantity"] = $business['POLICY']['TCPLI_PASSENGER_COUNT'];
							$data["prpCitemKindVos[5].kindCode"]="050712";
							$data["prpCitemKindVos[5].kindName"]="车上(乘客)";
							$data["prpCitemKindVos[5].amount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
							$data["prpCitemKindVos[5].chooseFlag"]="true";


						}else if($val== "BSDI"){
							$data["subKindName[6]"]	 = "车身划痕";
							$data["relateSpecial[6]"] = "";
							$data["prpCitemKindVos[6].rate"] = "";
							$data["prpCitemKindVos[6].benchMarkPremium"] = "";
							$data["prpCitemKindVos[6].disCount"] = "";
							$data["prpCitemKindVos[6].premium"] = "";
							$data["prpCitemKindVos[6].amount"] = $business['POLICY']['BSDI_INSURANCE_AMOUNT'];
							$data["subKindValue[6]"] = $business['POLICY']['BSDI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[6].kindCode"]="050211";
							$data["prpCitemKindVos[6].kindName"]="车身划痕";
							$data["prpCitemKindVos[6].chooseFlag"]="true";

						}else if($val== "BGAI"){
								$data["subKindName[7]"] = "玻璃破碎";
								$data["relateSpecial[7]"] = "";
								$data["prpCitemKindVos[7].rate"] = "";
								$data["prpCitemKindVos[7].benchMarkPremium"] = "";
								$data["prpCitemKindVos[7].disCount"] = "";
								$data["prpCitemKindVos[7].premium"] = "";
								$data["prpCitemKindVos[7].amount"] = "";
								if($business['POLICY']['GLASS_ORIGIN']=="DOMESTIC"){
											$data["prpCitemKindVos[7].modeCode"] = "10";//默认国产玻璃
								}else if($business['POLICY']['GLASS_ORIGIN']=="DOMESTIC_SPECIAL"){
											$data["prpCitemKindVos[7].modeCode"] = "11";
								}else if($business['POLICY']['GLASS_ORIGIN']=="IMPORTED"){
											$data["prpCitemKindVos[7].modeCode"] = "20";
								}else if($business['POLICY']['GLASS_ORIGIN']=="IMPORTED_SPECIAL"){
											$data["prpCitemKindVos[7].modeCode"] = "21";
								}
								$data["subKindValue[7]"] = "";
								$data["prpCitemKindVos[7].chooseFlag"]="true";
								$data["prpCitemKindVos[7].kindCode"]="050232";
								$data["prpCitemKindVos[7].kindName"]="玻璃破碎";
						}else if($val== "NIELI"){
							$data["prpCitemKindVos[8].rate"]="";
							$data["prpCitemKindVos[8].benchMarkPremium"]="";
							$data["prpCitemKindVos[8].disCount"]="";
							$data["prpCitemKindVos[8].amount"]=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[8].premium"]="";
							$data["subKindCode[8]"]="050261";
							$data["subKindName[8]"]="新增设备损失险";
							$data["relateSpecial[8]"]="null";
							$data["prpCitemKindVos[8].chooseFlag"]="true";
							$data["prpCitemKindVos[8].kindCode"]="050261";
							$data["prpCitemKindVos[8].kindName"]="新增设备损失险";
							if(!empty($business['POLICY']['NIELI_DEVICE_LIST']))
							{
								$devcount = count($business['POLICY']['NIELI_DEVICE_LIST']);
								for($idx = 0 ; $idx<$devcount ; $idx++)
								{
									$data["deviceList[{$idx}].devicename"]   = $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['NAME'];
									$data["deviceList[{$idx}].quantity"]     = $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['COUNT'];
									$data["deviceList[{$idx}].purchaseprice"]= $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['BUYING_PRICE'];
									$data["deviceList[{$idx}].buydate"]      = $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['BUYING_DATE'];
									$data["deviceList[{$idx}].actualvalue"]  = $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['DEPRECIATION'];
									$data["deviceList[{$idx}].serialno"]     = $idx+1;
								}
							}


						}else if($val== "VWTLI"){
							$data["mainKindName[9]"]="发动机涉水损失险";
							$data["prpCitemKindsTemp[9].kindCode"] = "050461";
							$data["prpCitemKindsTemp[9].rate"] = "";
							$data["prpCitemKindsTemp[9].benchMarkPremium"] = "";
							$data["prpCitemKindsTemp[9].disCount"] = "";
							$data["prpCitemKindsTemp[9].premium"] = "";
							$data["prpCitemKindsTemp[9].amount"] = "";
							$data["mainKindValue[9]"] = "";//$business['POLICY']['VWTLI_INSURANCE_AMOUNT'];
							$data["subKindValue[9]"] = "";
							$data["prpCitemKindVos[9].chooseFlag"]="true";
							$data["prpCitemKindVos[9].kindCode"]="050461";
							$data["prpCitemKindVos[9].kindName"]="发动机涉水损失险";
						}else if($val== "SLOI"){
							$data["subKindName[10]"]   = "自燃损失";
							$data["relateSpecial[10]"] = "";
							$data["prpCitemKindVos[10].rate"] = "";
							$data["prpCitemKindVos[10].benchMarkPremium"] = "";
							$data["prpCitemKindVos[10].disCount"] = "";
							$data["prpCitemKindVos[10].premium"] = "";
							$data["prpCitemKindVos[10].amount"]=$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
							$data["subKindValue[10]"] = $business['POLICY']['SLOI_INSURANCE_AMOUNT'];
							$data["prpCitemKindVos[10].chooseFlag"]="true";
							$data["prpCitemKindVos[10].kindCode"]="050311";
							$data["prpCitemKindVos[10].kindName"]="自燃损失";
						}else if($val== "STSFS"){
							$data["subKindName[11]"]	  = "指定修理厂";
							$data["relateSpecial[11]"] = "";
							$data["prpCitemKindVos[11].rate"] = "";
							$data["prpCitemKindVos[11].benchMarkPremium"] = "";
							$data["prpCitemKindVos[11].disCount"] = "";
							$data["prpCitemKindVos[11].premium"] = "";
							if($business['POLICY']['STSFS_RATE'] == 'DOMESTIC')
							{
								$data["repairFactory"] = '01';
							}
							elseif($business['POLICY']['STSFS_RATE'] == 'IMPORTED')
							{
								$data["repairFactory"] = '02';
							}
							elseif($business['POLICY']['STSFS_RATE'] == 'JOINT_VENTURE')
							{
								$data["repairFactory"] = '03';
							}
							$data["subKindValue[11]"] = "2000";
							$data["prpCitemKindVos[11].chooseFlag"]="true";
							$data["prpCitemKindVos[11].kindCode"]="050253";
							$data["prpCitemKindVos[11].kindName"]="指定修理厂";
							$data["prpCitemKindVos[11].modeCode"]=$data["repairFactory"];
						}else if($val== "RDCCI"){
                            $data["prpCitemKindVos[12].unitAmount"]=$business['POLICY']['RDCCI_INSURANCE_UNIT'];
                            $data["prpCitemKindVos[12].quantity"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
                            $data["prpCitemKindVos[12].rate"]="";
                            $data["prpCitemKindVos[12].benchMarkPremium"]="";
                            $data["prpCitemKindVos[12].disCount"]="";
                            $data["prpCitemKindVos[12].amount"]=$business['POLICY']['RDCCI_INSURANCE_UNIT']*$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
                            $data["prpCitemKindVos[12].premium"]="";
                            $data["subKindCode[12]"]="050441";
                            $data["subKindName[12]"]="修理期间费用补偿险";
                            $data["relateSpecial[12]"]="";
                            $data["subKindValue[12]"]=$business['POLICY']['RDCCI_INSURANCE_UNIT']*$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
                            $data["prpCitemKindVos[12].rate"]="";
                            $data["prpCitemKindVos[12].benchMarkPremium"]="";
                            $data["prpCitemKindVos[12].disCount"]="";
                            $data["prpCitemKindVos[12].premium"]="";
                            $data["prpCitemKindVos[12].chooseFlag"]="true";
                            $data["prpCitemKindVos[12].kindCode"]="050441";
                            $data["prpCitemKindVos[12].kindName"]="修理期间费用补偿险";
                        }else if($val== "MVLINFTPSI"){
                            $data["prpCitemKindVos[13].rate"]="";
                            $data["prpCitemKindVos[13].benchMarkPremium"]="";
                            $data["prpCitemKindVos[13].disCount"]="";
                            $data["prpCitemKindVos[13].amount"]="";
                            $data["prpCitemKindVos[13].premium"]="";
                            $data["subKindCode[13]"]="050451";
                            $data["subKindName[13]"]="第三方特约险";
                            $data["relateSpecial[13]"]="null";
                            $data["subKindValue[13]"]="";
                            $data["prpCitemKindVos[13].chooseFlag"]="true";
                            $data["prpCitemKindVos[13].kindCode"]="050451";
                            $data["prpCitemKindVos[13].kindName"]="机动车损失保险无法找到第三方特约险";
                        }else if($val== "TVDI_NDSI"){

							$data["rpCitemKindsTemp[43].chooseFlag"]="on";
							$data["prpCitemKindsTemp[43].kindCode"]="050930";
							$data["prpCitemKindsTemp[43].calculateFlag"]="N33Y000";

						}else if($val== "TTBLI_NDSI"){
							$data["prpCitemKindsTemp[2].specialFlag"]="on";
							$data["prpCitemKindsTemp[44].chooseFlag"]="on";
							$data["prpCitemKindsTemp[44].kindCode"]="050931";
							$data["prpCitemKindsTemp[44].kindName"]="";
							$data["prpCitemKindsTemp[44].startHour"]="";
							$data["prpCitemKindsTemp[44].endDate"]="";
							$data["prpCitemKindsTemp[44].endHour"]="";
							$data["prpCitemKindsTemp[44].calculateFlag"]="N33Y000";
							$data["relateSpecial[44]"]="";
							$data["prpCitemKindsTemp[44].flag"]="null";
							$data["prpCitemKindsTemp[44].amount"]="";
							$data["prpCitemKindsTemp[44].rate"]="";
							$data["prpCitemKindsTemp[44].benchMarkPremium"]="";
							$data["prpCitemKindsTemp[44].disCount"]="";
							$data["prpCitemKindsTemp[44].premium"]="";
						}else if($val== "TWCDMVI_NDSI"){
							$data["prpCitemKindsTemp[3].specialFlag"]="on";
							$data["prpCitemKindsTemp[45].chooseFlag"]="on";
							$data["prpCitemKindsTemp[45].id.itemKindNo"]="";
							$data["prpCitemKindsTemp[45].startDate"]="";
							$data["prpCitemKindsTemp[45].kindCode"]="050932";
							$data["prpCitemKindsTemp[45].startHour"]="";
							$data["prpCitemKindsTemp[45].endDate"]="";
							$data["prpCitemKindsTemp[45].endHour"]="";
							$data["prpCitemKindsTemp[45].calculateFlag"]="N33Y000";
							$data["relateSpecial[45]"]="";
							$data["prpCitemKindsTemp[45].flag"]="null";
							$data["prpCitemKindsTemp[45].amount"]="";
							$data["prpCitemKindsTemp[45].rate"]="";
							$data["prpCitemKindsTemp[45].benchMarkPremium"]="";
							$data["prpCitemKindsTemp[45].disCount"]="";
							$data["prpCitemKindsTemp[45].premium"]="";
						}else if($val== "TCPLI_DRIVER_NDSI"){
							$data["prpCitemKindsTemp[4].specialFlag"]="on";
							$data["prpCitemKindsTemp[46].chooseFlag"]="on";
							$data["prpCitemKindsTemp[46].id.itemKindNo"]="";
							$data["prpCitemKindsTemp[46].startDate"]="";
							$data["prpCitemKindsTemp[46].kindCode"]="050933";
							$data["prpCitemKindsTemp[46].startHour"]="";
							$data["prpCitemKindsTemp[46].endDate"]="";
							$data["prpCitemKindsTemp[46].endHour"]="";
							$data["prpCitemKindsTemp[46].calculateFlag"]="N33Y000";
							$data["relateSpecial[46]"]="";
							$data["prpCitemKindsTemp[46].flag"]="null";
						}else if($val== "TCPLI_PASSENGER_NDSI"){
							$data["prpCitemKindsTemp[5].specialFlag"]="on";
							$data["prpCitemKindsTemp[47].chooseFlag"]="on";
							$data["prpCitemKindsTemp[47].id.itemKindNo"]="";
							$data["prpCitemKindsTemp[47].startDate"]="";
							$data["prpCitemKindsTemp[47].kindCode"]="050934";
							$data["prpCitemKindsTemp[47].startHour"]="";
							$data["prpCitemKindsTemp[47].endDate"]="";
							$data["prpCitemKindsTemp[47].endHour"]="";
							$data["prpCitemKindsTemp[47].calculateFlag"]="N33Y000";
							$data["relateSpecial[47]"]="";
							$data["prpCitemKindsTemp[47].flag"]="null";

						}else if($val== "BSDI_NDSI"){
							$data["prpCitemKindVos[6].specialFlag"]="on";
							$data["prpCitemKindsTemp[48].chooseFlag"]="on";
							$data["prpCitemKindsTemp[48].id.itemKindNo"]="";
							$data["prpCitemKindsTemp[48].startDate"]="";
							$data["prpCitemKindsTemp[48].kindCode"]="050937";
							$data["prpCitemKindsTemp[48].startHour"]="";
							$data["prpCitemKindsTemp[48].endDate"]="";
							$data["prpCitemKindsTemp[48].endHour"]="";
							$data["prpCitemKindsTemp[48].calculateFlag"]="N33Y000";
							$data["prpCitemKindsTemp[48].flag"]="null";
							$data["prpCitemKindsTemp[48].amount"]="";
							$data["prpCitemKindsTemp[48].rate"]="";
							$data["prpCitemKindsTemp[48].benchMarkPremium"]="";
							$data["prpCitemKindsTemp[48].disCount"]="";
							$data["prpCitemKindsTemp[48].premium"]="";
						}else if($val== "SLOI_NDSI"){
							$data["prpCitemKindVos[10].specialFlag"]='on';
							$data["prpCitemKindsTemp[49].chooseFlag"]="on";
							$data["prpCitemKindsTemp[49].id.itemKindNo"]="";
							$data["prpCitemKindsTemp[49].startDate"]="";
							$data["prpCitemKindsTemp[49].kindCode"]="050935";
							$data["prpCitemKindsTemp[49].startHour"]="";
							$data["prpCitemKindsTemp[49].endDate"]="";
							$data["prpCitemKindsTemp[49].endHour"]="";
							$data["prpCitemKindsTemp[49].calculateFlag"]="N33Y000";
							$data["prpCitemKindsTemp[49].flag"]="null";
							$data["prpCitemKindsTemp[49].amount"]="";
							$data["prpCitemKindsTemp[49].rate"]="";
							$data["prpCitemKindsTemp[49].benchMarkPremium"]="";
							$data["prpCitemKindsTemp[49].disCount"]="";
							$data["prpCitemKindsTemp[49].premium"]="";
						}else if($val== "NIELI_NDSI"){
							$data["prpCitemKindVos[8].specialFlag"]='on';
							$data["prpCitemKindsTemp[50].chooseFlag"]="on";
							$data["prpCitemKindsTemp[50].id.itemKindNo"]="";
							$data["prpCitemKindsTemp[50].startDate"]="";
							$data["prpCitemKindsTemp[50].kindCode"]="050936";
							$data["prpCitemKindsTemp[50].startHour"]="";
							$data["prpCitemKindsTemp[50].endDate"]="";
							$data["prpCitemKindsTemp[50].endHour"]="";
							$data["prpCitemKindsTemp[50].calculateFlag"]="N33Y000";
							$data["prpCitemKindsTemp[50].flag"]="null";
							$data["prpCitemKindsTemp[50].amount"]="";
							$data["prpCitemKindsTemp[50].rate"]="";
							$data["prpCitemKindsTemp[50].benchMarkPremium"]="";
							$data["prpCitemKindsTemp[50].disCount"]="";
							$data["prpCitemKindsTemp[50].premium"]="";
						}else if($val== "VWTLI_NDSI"){
							$data["prpCitemKindVos[9].specialFlag"]='on';
							$data["prpCitemKindsTemp[51].chooseFlag"]="on";
							$data["prpCitemKindsTemp[51].id.itemKindNo"]="";
							$data["prpCitemKindsTemp[51].startDate"]="";
							$data["prpCitemKindsTemp[51].kindCode"]="050938";
							$data["prpCitemKindsTemp[51].startHour"]="";
							$data["prpCitemKindsTemp[51].endDate"]="";
							$data["prpCitemKindsTemp[51].endHour"]="";
							$data["prpCitemKindsTemp[51].calculateFlag"]="N33Y000";
							$data["prpCitemKindsTemp[51].flag"]="null";
							$data["prpCitemKindsTemp[51].amount"]="";
							$data["prpCitemKindsTemp[51].rate"]="";
							$data["prpCitemKindsTemp[51].benchMarkPremium"]="";
							$data["prpCitemKindsTemp[51].disCount"]="";
							$data["prpCitemKindsTemp[51].premium"]="";
						}

			}

							
 						    $picc_result = $this->requestData($this->UrlOffter,$data);
 							$resen= json_decode($picc_result,true);
							if(empty($resen) || !isset($resen['itemKindTempList']) || !isset($resen['ciPremium']) ){
								$this->error=$resen;
                                return false;
                             }else{
                                 $results['MESSAGE']                      = '';
								 $results['MVTALCI'] = array();
								 $results['MVTALCI']['TRAVEL_TAX_PREMIUM']= '0.00';
								 $results['MVTALCI']['MVTALCI_PREMIUM']   = '0.00';
								 $results['MVTALCI']['MVTALCI_DISCOUNT']  = '1.000';
								 $results['MVTALCI']['MVTALCI_START_TIME']= '';
								 $results['MVTALCI']['MVTALCI_END_TIME']  = '';
								 if(!empty($mvtalci)){
                             		    /*******************交强险********************/
									$results['MVTALCI']['TRAVEL_TAX_PREMIUM']= $resen['sumPayTax'];        //车船税
									$results['MVTALCI']['MVTALCI_PREMIUM']   = $resen['ciPremium'];           //交强险保费
									$results['MVTALCI']['MVTALCI_DISCOUNT']  = $resen['ciDiscount'];          //交强险折扣
									$results['MVTALCI']['MVTALCI_START_TIME']= $mvtalci['MVTALCI_START_TIME'];       //交强险生效时间
									$results['MVTALCI']['MVTALCI_END_TIME']  = date('Y-m-d H:i:s',strtotime('+1 years -1 seconds',strtotime($mvtalci['MVTALCI_START_TIME'])));         //交强险结束时间
                                 }
								  /*******************商业险********************/
                                 $results['MESSAGE']  = $resen['operateinfostr'];  //商业险投保信息
                                 $results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']= round($resen['biPremium']*$resen['biDiscount'],2); //商业险扣后保费合计
                                 $results['BUSINESS']['BUSINESS_DISCOUNT']=$resen['biDiscount'];         //商业险折扣
                                 $results['BUSINESS']['BUSINESS_PREMIUM']=$resen['biPremium'];          //商业险标准保费合计
                                 $results['BUSINESS']['BUSINESS_START_TIME'] = $business['BUSINESS_START_TIME'];       //商业险生效时间
                                 $results['BUSINESS']['BUSINESS_END_TIME'] = date('Y-m-d H:i:s',strtotime('+1 years -1 seconds',strtotime($business['BUSINESS_START_TIME'])));//商业险结束时间
                                  /*******************投保项目保费二维数组********************/
								 $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']                 = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']              = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']                = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']         = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']      = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']                 = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']                 = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']                = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']                = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']                 = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']                = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']                = '0.00';
                                 $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']           = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']            = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']           = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']         = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']    = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM'] = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']            = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']            = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']           = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']           = '0.00';
                                 foreach($resen['itemKindTempList'] as $k =>$v){
                                    if($v['kindName']=="机动车损失保险"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="盗抢险"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="第三者责任保险"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="车上人员责任险（司机）"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="车上人员责任险（乘客）"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="车身划痕损失险"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="玻璃单独破碎险"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="新增设备损失险"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="发动机涉水损失险"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="自燃损失险"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="指定修理厂"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']= $v['premium'];
		                              }else if($v['kindName']=="修理期间费用补偿险"){
                                            $results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']= $v['premium'];
                                      }else if($v['kindName']=="机动车损失保险无法找到第三方特约险"){
                                            $results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']= $v['premium'];
                                      }else if($v['kindName']=="不计免赔险（车损险）"){
		                                     $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM'] = $v['premium'];
		                              }else if($v['kindName']=="不计免赔险（三者险）"){
		                                   $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM'] = $v['premium'];
		                              }else if($v['kindName']=="不计免赔险（盗抢险）"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM'] = $v['premium'];
		                              }else if($v['kindName']=="不计免赔险（车上人员（司机））"){
		                                   $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM'] = $v['premium'];
		                              }else if($v['kindName']=="不计免赔险（车上人员（乘客））"){
		                                   $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM'] = $v['premium'];
		                              }else if($v['kindName']=="不计免赔险（车身划痕损失险）"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM'] = $v['premium'];
		                              }else if($v['kindName']=="不计免赔险（自燃损失险）"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM'] = $v['premium'];
		                              }else if($v['kindName']=="不计免赔险（发动机涉水损失险）"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM'] = $v['premium'];
		                              }else if($v['kindName']=="不计免赔险（新增设备损失险）"){
		                                    $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM'] = $v['premium'];
		                              }

                            	}
                                $results['BUSINESS']['INSURANCE_COMPANY'] = self::company;
								return $results;

                             }


		}

		return false;
	}



	/*
	 * 设备折旧价计算
	 * @info              参数信息
	 * 返回值 失败 false ,成功折旧价
	 */
	public function deviceDepreciation($info=array())
	{
		if(empty($info)) return false;


		foreach($this->clause_Type as $k=>$v)
		{
				if($info['USE_CHARACTER']==$k)
				{
					$data['clauseType']=$v;
			    }
		}


		if(!isset($info['VEHICLE_TYPE'])) return false;
		$data["carKindCode"] = array_search($info['VEHICLE_TYPE'],$this->vehicle_type);
		if(!isset($info['SEATS'])) return false;
		$data["seatCount"] = $info['SEATS'];
		$data["tonCount"] = 0;
		if(!isset($info['ENROLL_DATE'])) return false;
		$data["enrollDate"] = $info['ENROLL_DATE'];
		if(!isset($info['USE_CHARACTER'])) return false;
		$data["useNatureCode"] = array_search($info['USE_CHARACTER'],$this->use_character);
		if(!isset($info['BUSINESS_START_TIME'])) return false;
		$data["startDateBI"] = $info['BUSINESS_START_TIME'];
		if(!isset($info['BUYING_PRICE'])) return false;
		$data["purchasePrice"] = $info['BUYING_PRICE'];
		if(!isset($info['DEVICE_LIST'])) return false;

		foreach($info['DEVICE_LIST'] as $idx =>  $dev)
		{
			$data["deviceList[{$idx}].devicename"]    = $dev['NAME']        ;
			$data["deviceList[{$idx}].quantity"]      = $dev['COUNT']       ;
			$data["deviceList[{$idx}].purchaseprice"] = $dev['BUYING_PRICE'];
			$data["deviceList[{$idx}].buydate"]       = $dev['BUYING_DATE'] ;
			$data["deviceList[{$idx}].actualvalue"]   = 0                   ;
			$data["deviceList[{$idx}].serialno"]      = $idx+1              ;
		}
		$htmlstr = $this->requestData($this->NEW_EQRIPNENT,$data);
		$devlist = json_decode($htmlstr,true);
		if(!is_array($devlist)) return false;

		$retarr = $info['DEVICE_LIST'];
		foreach($devlist as $dev)
		{
			$retarr[$dev['serialno']-1]['DEPRECIATION'] = $dev['actualvalue'];
		}
		return 	$retarr;
	}


	/*
	 * 车辆折旧价计算
	 * @info              参数信息
	 * 返回值 失败 false ,成功折旧价
	 */
	public function depreciation($info=array())
	{
		$data = array();

		foreach($this->clause_Type as $k=>$v)
		{
				if($info['USE_CHARACTER']==$k)
				{
					$data['clauseType']=$v;
			    }
		}


		if(!array_key_exists('VEHICLE_TYPE',$info)) return false;
		$key = array_search($info['VEHICLE_TYPE'],$this->vehicle_type);
		if(!$key) return false;
		$data['carKindCode'] = $key;
		if(!array_key_exists('SEATS',$info)) return false;
		$data['seatCount'] = $info['SEATS'];
        $data['tonCount']='0';
		if(!array_key_exists('ENROLL_DATE',$info)) return false;
		$data['enrollDate'] = $info['ENROLL_DATE'];
		if(!array_key_exists('USE_CHARACTER',$info)) return false;
		$key = array_search($info['USE_CHARACTER'],$this->use_character);
		if(!$key)  return false;
		$data['useNatureCode'] = $key;
		if(!array_key_exists('BUSINESS_START_TIME',$info)) return false;
        $data['startDateBI'] = $info['BUSINESS_START_TIME'];
		if(!array_key_exists('BUYING_PRICE',$info)) return false;
        $data['purchasePrice'] = $info['BUYING_PRICE'];
		$result = $this->requestData($this->calActualValUrl,$data);
		if(preg_match('/^\d+\.?\d*$/',$result))
		{
			return $result;
		}
		return false;
	}


	/**
	 * [checkcode 交管车辆验证码查询]
	 * @AuthorHTL
	 * @DateTime  2016-07-20T15:56:58+0800
	 * @param     array                    $info [车牌号]
	 * @return    [type]                         [车架号]
	 */
	public function checkcode($info=array())
	{ 
		if(trim($info['LICENSE_NO'])=="" || trim($info['VIN_NO'])=="")
		{
			$this->error['errorMsg']="车牌号或车架号不能为空";
			return false;
		}
		$cheack_data['licenseNo']=$info['LICENSE_NO'];
		$cheack_data['vin']=$info['VIN_NO'];
		return $this->requestData($this->Url_Cheack,$cheack_data);

	}

	/**
	 * [check_data 交管信息查询]
	 * @AuthorHTL
	 * @DateTime  2016-07-20T16:37:38+0800
	 * @param     array                    $info [验证码]
	 * @return    [type]                         [交管车辆查询编号]
	 */
	public function check_data($info=array())
	{
		
		if(trim($info['checkno'])=="" || trim($info['checkcode'])=="")
		{
			$this->error['errorMsg']="交管车辆查询编号或验证码不能为空";
			return false;
		}
		$cheack_data['checkno']=$info['checkno'];
		$cheack_data['checkcode']=$info['checkcode'];
		$result = $this->requestData($this->Url_Verification,$cheack_data);
		$arr = json_decode($result,true);
		$_SESSION['car_traffic']["licenseno"]=$arr[0]['licenseno'];
		$_SESSION['car_traffic']["licensetype"]=$arr[0]['licensetype'];
		$_SESSION['car_traffic']["rackno"]=$arr[0]['rackno'];
		$_SESSION['car_traffic']["engineno"]=$arr[0]['engineno'];
		$_SESSION['car_traffic']["color"]=$arr[0]['color'];
		$_SESSION['car_traffic']["owner"]=$arr[0]['owner'];
		$_SESSION['car_traffic']["enrolldate"]=$arr[0]['enrolldate'];
		$_SESSION['car_traffic']["seatcount"]=$arr[0]['seatcount'];
		$_SESSION['car_traffic']["ineffectualdate"]=$arr[0]['ineffectualdate'];
		$_SESSION['car_traffic']["madefactory"]=$arr[0]['madefactory'];
		$_SESSION['car_traffic']["vehiclemodel"]=$arr[0]['vehiclemodel'];
		$_SESSION['car_traffic']["vehiclebarand1"]=$arr[0]['vehiclebarand1'];
		$_SESSION['car_traffic']["vehiclestyle"]=$arr[0]['vehiclestyle'];
		$_SESSION['car_traffic']["vehiclebarand2"]=$arr[0]['vehiclebarand2'];
		$_SESSION['car_traffic']["lastcheckdate"]=$arr[0]['lastcheckdate'];
		$_SESSION['car_traffic']["rejectdate"]=$arr[0]['rejectdate'];
		$_SESSION['car_traffic']["status"]=$arr[0]['status'];
		$_SESSION['car_traffic']["haulage"]=$arr[0]['haulage'];
		$_SESSION['car_traffic']["carloteququality"]=$arr[0]['carloteququality'];
		$_SESSION['car_traffic']["exhaustcapacity"]=$arr[0]['exhaustcapacity'];
		$_SESSION['car_traffic']["transferdate"]=$arr[0]['transferdate'];
		$_SESSION['car_traffic']["producertype"]=$arr[0]['producertype'];
		$_SESSION['car_traffic']["saleprice"]=$arr[0]['saleprice'];
		$_SESSION['car_traffic']["pmfueltype"]=$arr[0]['pmfueltype'];
		$_SESSION['car_traffic']["vehiclecategory"]=$arr[0]['vehiclecategory'];
		$_SESSION['car_traffic']["usetype"]=$arr[0]['usetype'];
		return $result;


	}


	 /**
     * 购置价查询
     * 参数:
     *      @info    必需。配置 数组
     *  成功 返回数组 失败返回false
     **/
	public function queryBuyingPrice($info=array())
	{

		
		$model = '*';
		if(!empty($info['model']))
		{
			$model = "*".str_replace( "牌", "", $info['model'])."*";
		}

		$page = 1;
		if(!empty($info['page']))
		{
			$page = $info['page'];
		}
		$rows = 10;

		/*************请求数组****************/
		$data["jyVehicleRequest.resources"]="0524";
		$data["jyVehicleRequest.currentpageno"]="";
		$data["operateType"]="quote";
		$data["operateFlag"]="";
		$data["enrollDate"]="";
		$data["jyVehicleRequest.searchCode"]="";//快速查询码
		$data["jyVehicleRequest.vehicleAlias"]="";//车型别名
		$data["jyVehicleRequest.vehicleId"]="";//车型编码
		$data["jyVehicleRequest.brandId"]="";//车辆品牌型号
		$data["jyVehicleRequest.brandName"]="";//车辆品牌名称
		$data['jyVehicleRequest.vehicleName'] = $model;//车型名称
		$data["licenseno"]="";
		$data["engineno"]="";
		$PostData['page'] = $page;
		$PostData['rows'] = $rows;
		$array = $this->requestData($this->PurchasePriceUrl.'?'.http_build_query($data),$PostData);


		$array = json_decode($array,true);
		/*if(strstr($array['errorMsg']),"无数据返回")
		{
			$model = "*".str_replace( "牌", "", $info['model'])."*";
			$array = $this->requestData($this->PurchasePriceUrl.'?'.http_build_query($data),$PostData);
			$array = json_decode($array,true);
		}*/
		if(is_array($array) && array_key_exists('rows',$array))
		{
			$retdata = array('total'=>ceil($array['total']/$rows),'page'=>$array['startIndex'],'records'=>$array['total'],'rows'=>array());
			foreach($array['rows'] as $row)
			{

				$line = array();
				$line['vehicleId']             = $row['vehicleId'];
				$line['vehicleName']           = $row['vehicleName'];
                $line['vehicleAlias']          = $row['vehicleAlias'];
				$line['vehicleMaker']          = $row['vehicleMaker'];
				$line['vehicleWeight']         = $row['vehicleWeight'];
				$line['vehicleDisplacement']   = $row['vehicleExhaust'];
				$line['vehicleTonnage']   	   = $row['vehicleTonnage'];
				$line['vehiclePrice']          = $row['priceP'];
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


	private  function url_encode($str) 
	   {  
	    if(is_array($str)) 
	    {  
	        foreach($str as $key=>$value) 
	        {  
	            $str[urlencode($key)] = $this->url_encode($value);  
	        }  
	    } 
	    else
	    {  
	        $str = urlencode($str);  
	    }  
	      
	    return $str;  
	  }
	/**
     * 请求数组字符
     * 参数: $result
     * @$result    $business  $mvtalci   必需。配置 数组
     **/
	public function  datas($result,$business,$mvtalci,$nolocalflag){


		/********************车牌类型代码转换*******************************/
				if($result['LICENSE_TYPE']=="SMALL_CAR"){
					$result['LICENSE_TYPE']="02";
				}else if($result['LICENSE_TYPE']=="LARGE_AUTOMOBILE"){
					$result['LICENSE_TYPE']="01";
				}else if($result['LICENSE_TYPE']=="TRAILER"){
					$result['LICENSE_TYPE']="15";
				}else if($result['LICENSE_TYPE']=="EMBASSY_CAR"){
					$result['LICENSE_TYPE']="03";
				}else if($result['LICENSE_TYPE']=="CONSULATE_VEHICLE"){
					$result['LICENSE_TYPE']="04";
				}else if($result['LICENSE_TYPE']=="HK_MACAO_ENTRY_EXIT_CAR"){
					$result['LICENSE_TYPE']="01";
				}else if($result['LICENSE_TYPE']=="COACH_CAR"){
					$result['LICENSE_TYPE']="16";
				}else if($result['LICENSE_TYPE']=="POLICE_CAR"){
					$result['LICENSE_TYPE']="80";
				}else if($result['LICENSE_TYPE']=="GENERAL_MOTORCYCLE"){
					$result['LICENSE_TYPE']="07";
				}else if($result['LICENSE_TYPE']=="MOPED"){
					$result['LICENSE_TYPE']="08";
				}else if($result['LICENSE_TYPE']=="EMBASSY_MOTORCYCLE"){
					$result['LICENSE_TYPE']="09";
				}else if($result['LICENSE_TYPE']=="CONSULATE_MOTORCYCLE"){
					$result['LICENSE_TYPE']="10";
				}else if($result['LICENSE_TYPE']=="COACH_MOTORCYCLE"){
					$result['LICENSE_TYPE']="17";
				}else if($result['LICENSE_TYPE']=="POLICE_MOTORCYCLE"){
					$result['LICENSE_TYPE']="81";
				}else if($result['LICENSE_TYPE']=="TEMPORARY_VEHICLE"){
					$result['LICENSE_TYPE']="22";
				}
				/********************车辆种类代码转换*******************************/
				if($result['VEHICLE_TYPE']=="PASSENGER_CAR"){
						$result['VEHICLE_TYPE']="A01";
				}else if($result['VEHICLE_TYPE']=="TRUCK"){
						$result['VEHICLE_TYPE']="B01";
				}else if($result['VEHICLE_TYPE']=="SEMI_TRAILER_TOWING"){
						$result['VEHICLE_TYPE']="B02";
				}else if($result['VEHICLE_TYPE']=="THREE_WHEELED"){
						$result['VEHICLE_TYPE']="B11";
				}else if($result['VEHICLE_TYPE']=="LOW_SPEED_TRUCK"){
						$result['VEHICLE_TYPE']="B12";
				}else if($result['VEHICLE_TYPE']=="TRUCK"){
						$result['VEHICLE_TYPE']="B01";
				}else if($result['VEHICLE_TYPE']=="VAN"){
						$result['VEHICLE_TYPE']="B13";
				}else if($result['VEHICLE_TYPE']=="DUMP_TRAILER"){
						$result['VEHICLE_TYPE']="B21";
				}else if($result['VEHICLE_TYPE']=="FUEL_TANK_CAR"){
						$result['VEHICLE_TYPE']="C01";
				}else if($result['VEHICLE_TYPE']=="TANK_CAR"){
						$result['VEHICLE_TYPE']="C02";
				}else if($result['VEHICLE_TYPE']=="THE_LIQUID_TANK"){
						$result['VEHICLE_TYPE']="C03";
				}else if($result['VEHICLE_TYPE']=="REFRIGERATED"){
						$result['VEHICLE_TYPE']="C04";
				}else if($result['VEHICLE_TYPE']=="TANK_TRAILER"){
						$result['VEHICLE_TYPE']="C11";
				}else if($result['VEHICLE_TYPE']=="BULLDOZER"){
						$result['VEHICLE_TYPE']="C20";
				}else if($result['VEHICLE_TYPE']=="WRECKER"){
						$result['VEHICLE_TYPE']="C22";
				}else if($result['VEHICLE_TYPE']=="SWEEPER"){
						$result['VEHICLE_TYPE']="C23";
				}else if($result['VEHICLE_TYPE']=="CLEAN_THE_CAR"){
						$result['VEHICLE_TYPE']="C24";
				}else if($result['VEHICLE_TYPE']=="CARRIAGE_HOIST"){
						$result['VEHICLE_TYPE']="C25";
				}else if($result['VEHICLE_TYPE']=="LOADING_AND_UNLOADING"){
						$result['VEHICLE_TYPE']="C26";
				}else if($result['VEHICLE_TYPE']=="LIFT_TRUCK"){
						$result['VEHICLE_TYPE']="C27";
				}else if($result['VEHICLE_TYPE']=="CONCRETE_MIXER_TRUCK"){
						$result['VEHICLE_TYPE']="C28";
				}else if($result['VEHICLE_TYPE']=="MINING_VEHICLE"){
						$result['VEHICLE_TYPE']="C29";
				}else if($result['VEHICLE_TYPE']=="PROFESSIONAL_TRAILER"){
						$result['VEHICLE_TYPE']="C30";
				}else if($result['VEHICLE_TYPE']=="SPECIAL_TWO_TRAILER"){
						$result['VEHICLE_TYPE']="C31";
				}else if($result['VEHICLE_TYPE']=="SPECIAL_TWO_OTHER"){
						$result['VEHICLE_TYPE']="C39";
				}else if($result['VEHICLE_TYPE']=="TV_TRUCKS"){
						$result['VEHICLE_TYPE']="C41";
				}else if($result['VEHICLE_TYPE']=="FIRE_ENGINE"){
						$result['VEHICLE_TYPE']="C42";
				}else if($result['VEHICLE_TYPE']=="MEDICAL_VEHICLE"){
						$result['VEHICLE_TYPE']="C43";
				}else if($result['VEHICLE_TYPE']=="OIL_STEAM"){
						$result['VEHICLE_TYPE']="C44";
				}else if($result['VEHICLE_TYPE']=="ROAD_VEHICLES"){
						$result['VEHICLE_TYPE']="C45";
				}else if($result['VEHICLE_TYPE']=="MINE_CAR"){
						$result['VEHICLE_TYPE']="C46";
				}else if($result['VEHICLE_TYPE']=="ARMORED_CAR"){
						$result['VEHICLE_TYPE']="C47";
				}else if($result['VEHICLE_TYPE']=="AMBULANCE"){
						$result['VEHICLE_TYPE']="C48";
				}else if($result['VEHICLE_TYPE']=="MONITORING_CAR"){
						$result['VEHICLE_TYPE']="C49";
				}else if($result['VEHICLE_TYPE']=="RADAR_VEHICLE"){
						$result['VEHICLE_TYPE']="C50";
				}else if($result['VEHICLE_TYPE']=="X_OPTICAL_CAR"){
						$result['VEHICLE_TYPE']="C51";
				}else if($result['VEHICLE_TYPE']=="TELECOM_ENGINEERING"){
						$result['VEHICLE_TYPE']="C52";
				}else if($result['VEHICLE_TYPE']=="ELECTRICAL_ENGINEERING"){
						$result['VEHICLE_TYPE']="C53";
				}else if($result['VEHICLE_TYPE']=="PROFESSIONAL_NET_WATERWHEEL"){
						$result['VEHICLE_TYPE']="C54";
				}else if($result['VEHICLE_TYPE']=="INSULATION_CAR"){
						$result['VEHICLE_TYPE']="C55";
				}else if($result['VEHICLE_TYPE']=="POSTAL_CAR"){
						$result['VEHICLE_TYPE']="C56";
				}else if($result['VEHICLE_TYPE']=="POLICE_SPECIAL_VEHICLE"){
						$result['VEHICLE_TYPE']="C57";
				}else if($result['VEHICLE_TYPE']=="CONCRETE_PUMP_TRUCK"){
						$result['VEHICLE_TYPE']="C58";
				}else if($result['VEHICLE_TYPE']=="SPECIAL_THREE_TRAILER"){
						$result['VEHICLE_TYPE']="C61";
				}else if($result['VEHICLE_TYPE']=="SPECIAL_THREE_OTHER"){
						$result['VEHICLE_TYPE']="C69";
				}else if($result['VEHICLE_TYPE']=="CONTAINER_TRACTORS"){
						$result['VEHICLE_TYPE']="C90";
				}else if($result['VEHICLE_TYPE']=="MOTORCYCLE"){
						$result['VEHICLE_TYPE']="D01";
				}else if($result['VEHICLE_TYPE']=="THREE_MOTORCYCLE"){
						$result['VEHICLE_TYPE']="D02";
				}else if($result['VEHICLE_TYPE']=="SIDECAR"){
						$result['VEHICLE_TYPE']="D03";
				}else if($result['VEHICLE_TYPE']=="TRACTOR"){
						$result['VEHICLE_TYPE']="E01";
				}else if($result['VEHICLE_TYPE']=="COMBINE_HARVESTER"){
						$result['VEHICLE_TYPE']="E11";
				}else if($result['VEHICLE_TYPE']=="OTHER_VEHICLES"){
						$result['VEHICLE_TYPE']="Z99";
				}
				/********************使用性质代码转换*******************************/

				if($result['USE_CHARACTER']=="NON_OPERATING_PRIVATE"){
					$result['USE_CHARACTER']="211";
					$result['TIAOKUAN_TYPE']="F42";
				}else if($result['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE"){
					$result['USE_CHARACTER']="212";
					$result['TIAOKUAN_TYPE']="F41";
				}else if($result['USE_CHARACTER']=="NON_OPERATING_AUTHORITY"){
					$result['USE_CHARACTER']="213";
					$result['TIAOKUAN_TYPE']="F41";
				}else if($result['USE_CHARACTER']=="OPERATING_LEASE_RENTAL"){
					$result['USE_CHARACTER']="111";
					$result['TIAOKUAN_TYPE']="F43";
				}else if($result['USE_CHARACTER']=="OPERATING_CITY_BUS"){
					$result['USE_CHARACTER']="112";
					$result['TIAOKUAN_TYPE']="F43";
				}else if($result['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS"){
					$result['USE_CHARACTER']="113";
					$result['TIAOKUAN_TYPE']="F43";
				}else if($result['USE_CHARACTER']=="NON_OPERATING_TRUCK"){
					$result['USE_CHARACTER']="220";
					$result['TIAOKUAN_TYPE']="F41";
				}else if($result['USE_CHARACTER']=="OPERATING_TRUCK"){
					$result['USE_CHARACTER']="120";
					$result['TIAOKUAN_TYPE']="F43";
				}else if($result['USE_CHARACTER']=="NONE_OPERATING_TRAILER"){
					$result['USE_CHARACTER']="221";
					$result['TIAOKUAN_TYPE']="F42";
				}else if($result['USE_CHARACTER']=="OPERATING_TRAILER"){
					$result['USE_CHARACTER']="121";
				}else if($result['USE_CHARACTER']=="SPECIAL_AUTO"){
					$result['USE_CHARACTER']="290";
					$result['TIAOKUAN_TYPE']="F42";
				}else if($result['USE_CHARACTER']=="MOTORCYCLE"){
					$result['USE_CHARACTER']="000";
				}else if($result['USE_CHARACTER']=="DUAL_PURPOSE_TRACTOR"){
					$result['USE_CHARACTER']="280";
					$result['TIAOKUAN_TYPE']="F42";
				}else if($result['USE_CHARACTER']=="TRANSPORT_TRACTOR"){
					$result['USE_CHARACTER']="180";
				}else if($result['USE_CHARACTER']=="OPERATING_LOW_SPEED_TRUCK"){
					$result['USE_CHARACTER']="120";
					$result['TIAOKUAN_TYPE']="F43";
				}else if($result['USE_CHARACTER']=="NON_OPERATING_LOW_SPEED_TRUCK"){
					$result['USE_CHARACTER']="220";
					$result['TIAOKUAN_TYPE']="F42";
				}
$data["isCqp"]="1";
$data["noDamYearsCI"]="0";
$data["lastDamagedCI"]="0";
$data["pageType"]="NEW";
$data["carDeviceSubKindIndex"]="";
$data["xinXuZhuanFlag"]="0";
$data["riskCode"]="DAA";
$data["comCode"]="32058216";
$data["prpCmain.comCode"]="32058216";
$data["platRadio"]="1";
$data["bizType"]="";
$data["editTypeCheck"]="new";
$data["editType"]="NEW";
$data["carDamageAmountFloat"]="30";
$data["carP"]="";
$data["kindP"]="";
$data["JSCustomerId"]="";
$data["prpCmain.businesNature"]="";
$data["prpCmain.agentCode"]="";
$data["prpCmain.handler1Code"]="";
$data["prpCmain.handlerCode"]="";
$data["prpCmain.operateCode"]="";
$data["operateCode"]="";
$data["purchasePriceRangeValidation"]="0";
$data["prpCmain.proposalNo"]="";
$data["prpCmainCI.proposalNo"]="";
$data["smsTemplate"]="尊敬的客户您好，人保车险为您爱车{licenseNo}报价：交强险{ciPremium}元，商业险{biPremium}元，总计{sumPremium}元。其中{premiumDetailText}。祝您生活愉快！客户经理奚辛晨 、电话。";
$data["emsTemplate"]="尊敬的客户，人保财险为您的爱车{licenseNo}报价：交强险{ciPremium}元，商业险{biPremium}元，总计{sumPremium}元。其中{premiumDetailText}。以上价格仅供参考，最终价格以出单为准。祝您生活愉快！";
$data["vechileQueryFromPlat"]="1";
$data["vehicleplat"]="0";
$data["instantGuarantee"]="1";
$data["renewed"]="0";
$data["transfer"]="0";
$data["zhuanbaobianswer.queryseuqenceno"]="";
$data["zhuanbaobianswer.answer"]="";
$data["zhuanbaocianswer.queryseuqenceno"]="";
$data["zhuanbaocianswer.answer"]="";
$data["insuredPageNum"]="1";
$data["eadinfo.isEAD"]="0";
$data["eadinfo.flag"]="";
$data["eadinfo.eachCopies"]="";
$data["eadinfo.totalCopies"]="";
$data["eadinfo.insuredCount"]="";
$data["custPhoneNo"]="";
$data["custAddress"]="";
$data["custCertificateNo"]="";
$data["damageTimes"]="";
$data["netuniqueId"]="";
$data["vehicleBJ"]="-1";
$data[]="苏A9DE15";
$data["vehiclePlatNoDataBJ"]="";
$data["vehicleJS"]="1";
$data["vehicleJS.purchasePrice"]="0";
$data["vehicleJS.carLotEquQuality"]="1";
$data["vehicleJS.tonCount"]="0";
$data["vehicleJS.seatCount"]="1";
$data["vehicleJS.exhaustScale"]="1";
$data["vehicleJS.searchcode"]="1";
$data["searchcodeJS"]="CAF7180B48";
$data["updateOpConfAfterQuoteFlag"]="0";
$data["lastIdentifyNo"]="";
$data["netSaleTest"]="0";
$data["poundageCalculate"]="0";
$data["operateConfigPrpList"]="[com.picc.qt.schema.vo.QtOperateConfigVo@b299c9e]";
$data["operateConfigNetList"]="[]";
$data["selectedOperateConfigId"]="";
$data["selectedNetOperateConfigId"]="";
$data["ecifUserTypeCode"]="";
$data["giftPackageId"]="";
$data["giftPackageComCode"]="";
$data["replaceableNum"]="0";
$data["clubGiftPackageDesStr"]="";
$data["memPhone"]="";
$data["contactIdForClub"]="";
$data["customerID"]="";
$data["KhstCustId"]="";
$data["registerOrNot"]="";
$data["clubGiftNum"]="0";
$data["smsForClubTemplate"]="";
$data["prpCitemCar.searchseqno"]="";
$data["isQuoteRenewByLicenseNo"]="0";
$data["isOwner"]="0";
$data["source"]="1";
$data["platFlag"]="1";
$data["carType"]="1";
$data["selectedOperateConfig"]="QT320000001467267020533";
$data["lastPolicyNo"]="";
$data["engineNo4Renew1"]="";
$data["frameNo4Renew1"]="";
$data["identifyNo4Renew1"]="";
$data["licenseNo4Renew"]="";
$data["licenseType4Renew"]="02";
$data["engineNo4Renew2"]="";
$data["frameNo4Renew2"]="";
$data["identifyNo4Renew2"]="";
$data["prpCitemCar.licenseNo"]=$result['LICENSE_NO'];
$data["prpCitemCar.licenseType"]=$result['LICENSE_TYPE'];
$data["prpCitemCar.engineNo"]=$result['ENGINE_NO'];
$data["prpCitemCar.vinNo"]=$result['VIN_NO'];
$data["prpCitemCar.frameNo"]="";
$data["carKindPopFlag_quickChange"]="true";
$data["carKindPopFlag_quickChange"]="true";
$data["prpCitemCar.carKindCode"]=$result['VEHICLE_TYPE'];
$data["prpCitemCar.useNatureCode"]=$result['USE_CHARACTER'];
$data["carOwner"]=$result['OWNER'];
$data["prpCitemCar.enrollDate"]=$result['ENROLL_DATE'];
$data["prpCitemCar.useYears"]="0";
$data["prpCmain.startDate"]=$business['BUSINESS_START_TIME'];
$data["prpCmain.starthourbi"]="0";
$data["prpCmain.startDateCI"]=empty($mvtalci)?'':$mvtalci['MVTALCI_START_TIME'];
$data["prpCmain.starthourci"]="0";
$data["prpCitemCar.brandName"]=str_replace( "牌", "", $result['MODEL'])."*";
$data["hidddenbrandName"]="";
$data["prpCitemcar.modelDemandNo"]="";
$data["prpCitemCar.vehicleMaker"]="";
$data["prpCitemCar.familyId"]="";
$data["prpCitemCar.brandId"]="DZA";
$data["carFamilyName"]="";
$data["carBrandName"]="";
$data["carGroupId"]="";
$data["carGroupName"]="";
$data["carModelInfo"]="";
$data["purchasePriceUpStrFromDb"]="";
$data["purchasePriceDownStrFromDb"]="";
$data["SZpurchasePriceUp"]="";
$data["SZpurchasePriceDown"]="";
$data["purchasePriceUp"]="NaN";
$data["purchasePriceDown"]="NaN";
$data["prpCitemCar.modelCode"]=$result['MODEL_CODE'];
$data["prpCitemCar.modelCodeAlias"]="";
$data["bjmpCheck"]="";

$data["quantity"]="4";
$data["sumPremium"]="2228.54";
$data["biPremium"]="1563.54";
$data["ciPremium"]="665.00";
$data["sumDiscount"]="0.6375";
$data["biDiscount"]="0.6142";
$data["ciDiscount"]="0.7000";
$data["damageTimesBI"]="0";
$data["damageTimesCI"]="0";
$data["sumPayTax"]="300.00";
$data["thisPayTax"]="300.00";
$data["prePayTax"]="0";
$data["delayPayTax"]="0";
$data["quotationId"]="QT3205821611468813166931";
$data["quotationNoBI"]="FDAA201632050000135399";
$data["quotationNoCI"]="FDZA201632050000111435";
$data["proposalNoBI"]="";
$data["proposalNoCI"]="";
$data["bizNo"]="";
$data["bizNoCI"]="";
$data["premium[0]"]="215.24";
$data["premium"]="1088.28";
$data["premium"]="260.02";
$data["premium"]="665.00";
$data["netbirthday"]="";
$data["agriflag"]="0";
$data["carcheckstatus"]="";
$data["carchecker"]="";
$data["carchecktime"]="";
$data["subPremium"]="1088.28";
$data["subPremium"]="260.02";
$data["subPremium"]="665.00";
$data["subPremium"]="215.24";
$data["subPremium"]="0.85";
$data["subPremium"]="0.85";
$data["subPremium"]="0.85";
$data["subPremium"]="1.00";
$data["PurchasePriceScal"]="10";
$data["prpCitemCar.purchasePrice"]=$result['BUYING_PRICE'];
$data["prpCitemCar.actualValue"]="";
$data["actualKindValue"]=$result['BUYING_PRICE'].".00";
$data["prpCitemCar.seatCount"]=$result['SEATS'];
$data["prpCitemCar.tonCount"]=$result['TONNAGE'];
$data["prpCitemCar.countryNature"]="03";
$data["countryNature"]="";
$data["prpCitemCar.exhaustScale"]=$result['ENGINE'];
$data["prpCitemCar.runMiles"]="";
$data["prpCitemCar.runAreaCode"]="11";
$data["prpCitemCar.carLotEquQuality"]=$result['KERB_MASS'];
$data["prpCcarShipTax.taxComCode"]="";
$data["TaxComCodeDes"]="";
$data["prpCitemCarExt.lastDamagedCI"]="";
$data["prpCitemCarExt.noDamYearsBI"]="";
$data["prpCitemCarExt.thisDamagedBI"]="";
$data["prpCitemCarExt.lastDamagedBI"]="";
$data["prpQmainVo.ext3"]="";
$data["importantProjectCode"]="";
$data["prpCitemCarExt.lastDamagedA"]="";
$data["prpCmain.projectCode"]="";
$data["projectCodeDes"]="";
$data["prpCcarShipTax.leviedDate"]=date('Y-m-d H:i:s');
$data["prpCitemCar.energyType"]="0";
$data["prpCitemCar.referenceActualValue"]=$result['BUYING_PRICE']."00";
$data["transferDate"]="";
$data["prpCitemCar.queryArea"]="320000";
$data["queryAreaDesc"]="";
$data["prpCitemCar.carInsuredRelation"]="所有";
$data["prpCitemCar.nonlocalFlag"]="0";
$data["prpCitemCar.loanVehicleFlag"]="0";
$data["prpCitemCar.loanName"]="";
$data["prpCitemCar.clauseType"]=$result['TIAOKUAN_TYPE'];
$data["prpCitemCar.cylindercount"]="";
$data["prpCitemCar.licenseColorCode"]="01";
$data["prpCitemCar.netWeifaFlag"]="0";
$data["modelCodeAlias"]="";
$data["Calculatemode"]="C1";
$data["_insuredFlag"]="";
$data["_insuredFlag_hide"]="投保人";
$data["insuredCodeOld"]="";
$data["_insuredFlag"]="";
$data["_insuredFlag_hide"]="被保险人";
$data["_insuredFlag"]="";
$data["_insuredFlag_hide"]="车主";
$data["_insuredFlag_hide"]="联系人";
$data["prpCinsureds[0].insuredType"]="1";
$data["prpCinsureds[0].insuredName"]="";
$data["prpCinsureds[0].insuredCode"]="";
$data["prpCinsureds[0].identifyType"]="01";
$data["prpCinsureds[0].unitType"]="100";
$data["prpCinsureds[0].identifyNumber"]="";
$data["prpCinsureds[0].dateValid"]="";
$data["prpCinsureds[0].nationality"]="CHN";
$data["prpCinsureds[0].resident"]="A";
$data["prpCinsureds[0].mobile"]="";
$data["prpCinsureds[0].province"]="320000";
$data["prpCinsureds[0].city"]="";
$data["prpCinsureds[0].area"]="";
$data["prpCinsureds[0].insuredAddress"]="";
$data["prpCinsureds[0].postcode"]="";
$data["prpCinsureds[0].phonenumber"]="";
$data["prpCinsureds[0].sex"]="1";
$data["prpCinsureds[0].birthday"]="";
$data["prpCinsureds[0].age"]="";
$data["prpCinsureds[0].drivingcartype"]="C1";
$data["prpCinsureds[0].drivinglicenseno"]="";
$data["prpCinsureds[0].drivingyears"]="";
$data["prpCinsureds[0].causetroubletimes"]="";
$data["prpCinsureds[0].acceptlicensedate"]="";
$data["prpCinsureds[0].insuredFlag"]="11100000";
$data["prpCinsureds[0].insuredCode"]="";
$data["prpCinsureds[0].versionNo"]="";
$data["prpCinsureds[0].auditStatus"]="";
$data["copies"]="1";
$data["eadnumbers"]="2";
$data["eachcount"]="1";
$data["relateSpecial_[0]"]="";
$data["prpCitemKindsTemp_[0].flag"]="";
$data["prpCitemKindsTemp_[0].amount"]="";
$data["prpCitemKindsTemp_[0].rate"]="";
$data["prpCitemKindsTemp_[0].benchMarkPremium"]="";
$data["prpCitemKindsTemp_[0].disCount"]="";
$data["prpCitemKindsTemp_[0].premium"]="";
$data["mainAndSubKindCount"]="11";
$data["subUseFlag"]="";
$data["tmpSubCount"]="";
$data["hiddenSubCode"]="";
$data["hiddenSubValue"]="";
$data["hiddenSubMode"]="";
$data["hiddenSubUnit"]="";
$data["hiddenSubOtherValue"]="";
$data["hiddenSubQuantity"]="";
$data["hiddenSubSpecial"]="";
$data["hidden_index_itemKind"]="42";
$data["specialKindIndex"]="44";
$data["taskId"]="";

$data["nolocalflag"]="0";
$data["prpJsCarinfo"]="";
$data["prpJsCarinfo.licenseno"]=$_SESSION["car_traffic"]["licenseno"];
$data["prpJsCarinfo.licensetype"]=$_SESSION["car_traffic"]["licensetype"];
$data["prpJsCarinfo.usernature"]="";
$data["prpJsCarinfo.rackno"]=$_SESSION["car_traffic"]["rackno"];
$data["prpJsCarinfo.engineno"]=$_SESSION["car_traffic"]["engineno"];
$data["prpJsCarinfo.color"]=$_SESSION["car_traffic"]["color"];
$data["prpJsCarinfo.owner"]=$_SESSION["car_traffic"]["owner"];
$data["prpJsCarinfo.enrolldate"]=$_SESSION["car_traffic"]["enrolldate"];
$data["prpJsCarinfo.seatcount"]=$_SESSION["car_traffic"]["seatcount"];
$data["prpJsCarinfo.limitload"]="0";
$data["prpJsCarinfo.ineffectualdate"]=$_SESSION["car_traffic"]["ineffectualdate"];
$data["prpJsCarinfo.madefactory"]=$_SESSION["car_traffic"]["madefactory"];
$data["prpJsCarinfo.vehiclemodel"]=$_SESSION["car_traffic"]["vehiclemodel"];
$data["prpJsCarinfo.vehiclebarand1"]=$_SESSION["car_traffic"]["vehiclebarand1"];
$data["prpJsCarinfo.vehiclebarand2"]=$_SESSION["car_traffic"]["vehiclebarand2"];
$data["prpJsCarinfo.vehiclestyle"]=$_SESSION["car_traffic"]["vehiclestyle"];
$data["prpJsCarinfo.lastcheckdate"]=$_SESSION["car_traffic"]["lastcheckdate"];
$data["prpJsCarinfo.rejectdate"]=$_SESSION["car_traffic"]["rejectdate"];
$data["prpJsCarinfo.status"]=$_SESSION["car_traffic"]["status"];
$data["prpJsCarinfo.haulage"]=$_SESSION["car_traffic"]["haulage"];
$data["prpJsCarinfo.carloteququality"]=$_SESSION["car_traffic"]["carloteququality"];
$data["prpJsCarinfo.exhaustcapacity"]=$_SESSION["car_traffic"]["exhaustcapacity"];
$data["prpJsCarinfo.transferdate"]=$_SESSION["car_traffic"]["transferdate"];
$data["prpJsCarinfo.producertype"]=$_SESSION["car_traffic"]["producertype"];
$data["prpJsCarinfo.saleprice"]=$_SESSION["car_traffic"]["saleprice"];
$data["prpJsCarinfo.pmfueltype"]=$_SESSION["car_traffic"]["pmfueltype"];
$data["prpJsCarinfo.vehiclecategory"]=$_SESSION["car_traffic"]["vehiclecategory"];
$data["prpJsCarinfo.usetype"]=$_SESSION["car_traffic"]["usetype"];
$data["prpCcarShipTax.taxType"]="1";
$data["prpCcarShipTax.calculateMode"]="C1";
$data["prpCcarShipTax.taxcomcode"]="32000000";
$data["prpCcarShipTax.taxcomname"]="江苏省地方税务局";
$data["prpCcarShipTax.dutyPaidProofNo"]="";
$data["prpCcarShipTax.taxAbateReason"]="01";
$data["prpCcarShipTax.taxAbateProportion"]="";
$data["prpCcarShipTax.taxAbateAmount"]="";
$data["prpCcarShipTax.taxAbateType"]="1";
return $data;

	
	}









}




?>