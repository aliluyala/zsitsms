<?php
/**
 * 项目:           车险保费在线计算接口
 * 文件名:         Chongqin_PICCKHYXSC_PC.class.php
 * 版权所有：      成都启点科技有限公司.
 * 作者：          Liang YuLin
 * 版本：          1.0.0
 *
 * 中国人保重庆客户营销管理系统算价接口
 *
 **/
class Chongqin_PICCKHYXSC_PC
{
	const formFile = 'Calculate.tpl';
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
			$this->cookie_file = dirname(__FILE__).'/Chongqin_picckhyxsc_cookie.txt';
		}
		else
		{
			$this->cookie_file = $cachePath.'/Chongqin_picckhyxsc_cookie.txt';  //COOKIE文件存放地址
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

	private function requestPostData($url,$post,$head=false,$foll=1,$ref=false)
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


	private function requestGetData($url,$post,$head=false,$foll=1,$ref=false)
	{
		$ret = $this->get($url,$head,$foll,$ref);
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
	 * [get description]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T16:14:52+0800
	 * @param     [type]                   $url       [请求URL]
	 * @param     boolean                  $head      [请求参数]
	 * @param     boolean                  $refer     [请求头部]
	 * @param     [type]                   $encodeing [description]
	 * @return    [type]                              [成功返回数组，失败返回false]
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
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
	    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	    if(!empty($refer))curl_setopt($curl, CURLOPT_REFERER, $refer);
	    if(!empty($encodeing))curl_setopt($curl, CURLOPT_ENCODING, $encodeing);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
	    $tmpInfo = curl_exec($curl); // 执行操作
	    $tmpInfo=mb_convert_encoding($tmpInfo, "UTF-8","gb2312");
		return $tmpInfo;
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

		$data=array();
		$data=$this->datas($auto,$business,$mvtalci);//请求参数
		if(isset($business['POLICY']['BUSINESS_ITEMS'])){

 						    $picc_result = $this->requestPostData($this->UrlOffter,$data);
 						    $resen = json_decode($picc_result,true);
							if(empty($resen) || !isset($resen['zhuanbaobires']['queryseuqenceno']) || !isset($resen['zhuanbaocires']['queryseuqenceno'])){
								$this->error=$resen;
                                return false;
                             }else{
                      
                            	foreach($resen as $k =>$v)
 						    	{
 						    		$str[] = $this->check_data($v['quotation']);
 						    	}
	
 						    	if(isset($resen['zhuanbaobires']['queryseuqenceno']) && $resen['zhuanbaobires']['queryseuqenceno']!="")
 						    	{
 						    			
 						    			$auto["zhuanbaobianswer.queryseuqenceno"]=$resen['zhuanbaobires']['queryseuqenceno'];
										$auto["zhuanbaobianswer.answer"]=$str[0];
 						    	}
	
 						    	if(isset($resen['zhuanbaocires']['queryseuqenceno']) && $resen['zhuanbaocires']['queryseuqenceno']!="")
 						    	{
 						    			$auto["zhuanbaocianswer.queryseuqenceno"]=$resen['zhuanbaocires']['queryseuqenceno'];
										$auto["zhuanbaocianswer.answer"]=$str[1];
 						    	}

 						    $data=$this->datas($auto,$business,$mvtalci);//请求参数
 						    $picc_result = $this->requestPostData($this->UrlOffter,$data);
 						    $resen = json_decode($picc_result,true);
 						    if(empty($resen) || !isset($resen['itemKindTempList']) || !isset($resen['ciPremium']))
 						    {
 						    	$this->error=$resen;
                                return false;
 						    }
                                 $results['MESSAGE'] = '';
								 $results['MVTALCI'] = 	array();
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
		$htmlstr = $this->requestPostData($this->NEW_EQRIPNENT,$data);
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
		$result = $this->requestPostData($this->calActualValUrl,$data);
		if(preg_match('/^\d+\.?\d*$/',$result))
		{
			return $result;
		}
		return false;
	}



	/**
	 * [check_data 验证码处理]
	 * @AuthorHTL
	 * @DateTime  2016-07-20T16:37:38+0800
	 * @param     array                    $info [验证码]
	 * @return    [type]                         [交管车辆查询编号]
	 */
	public function check_data($str)
	{
		$arry1=array('0'=>'零','1'=>'壹','2'=>'贰','3'=>'叁','4'=>'肆','5'=>'伍','6'=>'陆','7'=>'柒','8'=>'捌','9'=>'玖');
		$arry2=array('1'=>'一','2'=>'二','3'=>'三','4'=>'四','5'=>'五','6'=>'六','7'=>'七','8'=>'八','9'=>'九','十');
		$int=array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9');
		$str= str_replace("请选择：", "", $str);
		$str= str_replace(" ", "", $str);
		$str= str_replace("  ", "", $str);
		$arr= explode("?", $str);
		$arr= str_replace("加", "+", $arr);
		$arr= str_replace("减", "-", $arr);
		$arr= str_replace("等于", "", $arr);
		$arr= str_replace("=", "", $arr);

		$result= explode(";",$arr[1]);//答案

		if(strstr($arr[0],"+"))
		{
			$aox= explode("+", $arr[0]);
		}
		else if(strstr($arr[0],"-"))
		{
			$aox= explode("-", $arr[0]);
		}

		foreach($aox as $k)
		{
			if(array_search($k, $arry1))
			{
				$axc[]   =  array_search($k, $arry1);
			}
			if(array_search($k, $arry2))
			{
				$axc[]   =  array_search($k, $arry2);
			}
			if(array_search($k, $int))
			{
				$axc[]   =  array_search($k, $int);
			}	
		}

		if(strstr($arr[0],"+"))
		{
			$yes=$axc[0]+$axc[1];
			$results=array();
			$results['A']=preg_replace('|[a-zA-Z/]+|','',$result[0]);
			$results['B']=preg_replace('|[a-zA-Z/]+|','',$result[1]);
			$results['C']=preg_replace('|[a-zA-Z/]+|','',$result[2]);
			$results['D']=preg_replace('|[a-zA-Z/]+|','',$result[3]);
			
			return array_search($yes, $results);
		}
		else if(strstr($arr[0],"-"))
		{
			$yes=$axc[0]-$axc[1];
			$results=array();
		 	$results['A']=preg_replace('|[a-zA-Z/]+|','',$result[0]);
			$results['B']=preg_replace('|[a-zA-Z/]+|','',$result[1]);
			$results['C']=preg_replace('|[a-zA-Z/]+|','',$result[2]);
			$results['D']=preg_replace('|[a-zA-Z/]+|','',$result[3]);
			return array_search($yes, $results);
		}

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
		$array = $this->requestPostData($this->PurchasePriceUrl.'?'.http_build_query($data),$PostData);
		$array = json_decode($array,true);
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
		else
		{
			$vin_len = strlen($info['vin_no']);
			if($vin_len<17)
			{
				return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());
			}

			$url= "http://autoagent.cpic.com.cn/jy1/search?orgCode=6020100&vehicleCode=&usetypeCode=101&insuranceCode=AUTOCOMPRENHENSIVEINSURANCE2014PRODUCT&vinCode=".$info['vin_no']."&requestSource=&searchCode=&returnUrl=https://116.228.143.164/CPIC09Auto/ZccxServletInvoke&#";
 			$resluts= $this->get($url);
			if(strpos($resluts, "没有找到符合条件的数据"))
			{
				return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());
			}
			preg_match_all("/<table .*>(.*)<\/table>/Us",$resluts,$result);
			$qian=array(" ","??","\t","\n","\r","<",">");
			$hou=array("","","","","","","");
			$app= $this->get_td_array($result[0][4]);
			$error= str_replace($qian,$hou,$app[1][0]);
			$conunt=$this->get_td_array($result[0][5]);
			preg_match_all('/[0-9]?[0-9]/',$conunt[0][1],$Page_Count);
			if($Page_Count[0][2]=="0")//判断是否有查询结果
			{

				$datas["fieldName"]="";
				$datas["orderByType"]="";
				$datas["qtype"]="2";
				$datas["strqcpp"]="";
				$datas["cxid"]="";
				$datas["strqccx"]="";
				$datas["fsearchCode"]="";
				$datas["cxingmc"]=$info['model'];
				$datas["brand"]="";
				$datas["family"]="";
				$datas["cxingbm"]="";
				$datas["cxingcj"]="";
				$datas["importFlag"]="";
				$datas["vehicleClass"]="";
				$datas["fvinRoot"]="";
				$datas["gotoPage"]="";
				$datas["requestSource"]="";
				$PostData["pageno"]=$page;
				$PostData["pagesize"]=$rows;
				$model_result = $this->requestPostData($this->model_price.'?'.http_build_query($datas),$PostData);
				preg_match_all("/<table .*>(.*)<\/table>/Us",$model_result,$model_s);
				$app= $this->get_td_array($model_s[0][4]);
				$error= str_replace($qian,$hou,$app[1][0]);
				$conunt=$this->get_td_array($model_s[0][5]);
				preg_match_all('/[0-9]?[0-9]/',$conunt[0][1],$Page_Count);
				$car['startIndex']=$Page_Count[0][0];
				$car['total']=$Page_Count[0][1];
				if($Page_Count[0][2]=="0")//判断是否有查询结果
				{
					return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());
				}
			}
			else
			{
				$car['startIndex']=$Page_Count[0][0];
				$car['total']=$Page_Count[0][1];
			}

				array_shift($app);
				$arr=array();
				foreach ($app as $key => $item) 
				{
					$arr['rows'][]=$app[$key];
				}
				$i=0;
				foreach ($arr['rows'] as $key) 
				{
				    $car['rows'][$i]['vehicleName']=str_replace($qian,$hou,$key[1]);//车辆名称
				    $car['rows'][$i]['vehicleId']=str_replace($qian,$hou,$key[2]);//车辆代码
				    $car['rows'][$i]['vehicleSeat']=str_replace($qian,$hou,$key[4]);//车辆
				    $car['rows'][$i]['vehicleAlias']=str_replace($qian,$hou,$key[11]);
				    	if(str_replace($qian,$hou,$key[6])=="")
				    	{
				    		$car['rows'][$i]['vehicleWeight']="0.00";
				    	}
				    	else
				    	{
				    		$car['rows'][$i]['vehicleWeight']=str_replace($qian,$hou,$key[6]);
				    	}	
				   
				    $car['rows'][$i]['priceP']=str_replace($qian,$hou,$key[7]);
				    $car['rows'][$i]['vehicleYear']=str_replace($qian,$hou,$key[9]);
				    if(str_replace($qian,$hou,$key[5])=="")
				    {
				    	$car['rows'][$i]['vehicleDisplacement']="0.00";
				    }
				    else
				    {
				    	$car['rows'][$i]['vehicleDisplacement']=str_replace($qian,$hou,$key[5]);
				    }
				    
				    $i++;
				}

				if(is_array($car) && array_key_exists('rows',$car))
				{
						$retdata = array('total'=>ceil($car['total']/$rows),'page'=>$car['startIndex'],'records'=>$car['total'],'rows'=>array());
						foreach($car['rows'] as $row)
						{

							$line = array();
							$line['vehicleId']             = $row['vehicleId'];
							$line['vehicleName']           = $row['vehicleName'];
			                $line['vehicleAlias']          = $row['vehicleAlias'];
							//$line['vehicleMaker']          = $row['vehicleMaker'];
							$line['vehicleWeight']         = $row['vehicleWeight'];
							$line['vehicleDisplacement']   = $row['vehicleDisplacement'];
							$line['vehicleTonnage']   	   = "0.00";
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


		}

		return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());
	}

/**
 * [get_td_array 将table表格字符转换成数组]
 * @AuthorHTL
 * @DateTime  2016-09-28T10:57:44+0800
 * @param     [type]                   $table [请求表格字符]
 * @return    [type]                          [description]
 */
private function get_td_array($table) {
  $table = preg_replace("'<table[^>]*?>'si","",$table);
  $table = preg_replace("'<tr[^>]*?>'si","",$table);
  $table = preg_replace("'<td[^>]*?>'si","",$table);
  $table = str_replace("</tr>","{tr}",$table);
  $table = str_replace("</td>","{td}",$table);
  $table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table);
  $table = preg_replace("'([rn])[s]+'","",$table);
  $table = str_replace(" ","",$table);
  $table = str_replace(" ","",$table);
  $table = explode('{tr}', $table);
  array_pop($table);
  foreach ($table as $key=>$tr) {
    $td = explode('{td}', $tr);
    array_pop($td);
    $td_array[] = $td;
  }
  return $td_array;
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
	public function  datas($result,$business,$mvtalci){

				

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
if(!empty($mvtalci))
		{
			$business['POLICY']['BUSINESS_ITEMS'][] = 'MVTALCI';
		}
foreach($business['POLICY']['BUSINESS_ITEMS'] as $key =>$val){
						if($val== "MVTALCI"){
							$dataes["prpCitemKindsTemp[0].kindCode"]="050100";
							$dataes["prpCitemKindsTemp[0].kindName"]="交强";
							$dataes["prpCitemKindsTemp[0].rate"]="";
							$dataes["prpCitemKindsTemp[0].benchMarkPremium"]="";
							$dataes["prpCitemKindsTemp[0].disCount"]="";
							$dataes["prpCitemKindsTemp[0].premium"]="";
							$dataes["prpCitemKindsTemp[0].amount"]="";
							$dataes["mainKindValue[0]"]='122000';
							$dataes["mainKindName[0]"]="交强";
							$dataes["prpCitemKindVos[0].kindCode"] = "050100";
							$dataes["prpCitemKindVos[0].kindName"] = "交强";
							$dataes["prpCitemKindVos[0].amount"] = "122000";
							$dataes["prpCitemKindVos[0].chooseFlag"] = "true";

						}else if($val== "TVDI"){
							$dataes["mainKindName[1]"]="车损";
							$dataes["relateSpecial[1]"]="050930";
							$dataes["prpCitemKindsTemp[1].kindCode"] = "050202";
							$dataes["prpCitemKindsTemp[1].kindName"]="车损";
							$dataes["prpCitemKindsTemp[1].rate"] = "";
							$dataes["prpCitemKindsTemp[1].benchMarkPremium"] = "";
							$dataes["prpCitemKindsTemp[1].disCount"] = "";
							$dataes["prpCitemKindsTemp[1].premium"] = "";
							$dataes["prpCitemKindsTemp[1].amount"] = "";
							$dataes["mainKindValue[1]"] = $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
							$dataes["mainKindCompare[1]"] = $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[1].kindCode"] = "050202";
							$dataes["prpCitemKindVos[1].kindName"] = "车损";
							$dataes["prpCitemKindVos[1].amount"] = $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[1].chooseFlag"] = "true";

						}else if($val== "TTBLI"){
							$dataes["mainKindName[3]"]="三者";
							$dataes["relateSpecial[3]"]="050931";
							$dataes["prpCitemKindsTemp[3].kindCode"] = "050602";
							$dataes["prpCitemKindsTemp[3].kindName"]="三者";
							$dataes["prpCitemKindsTemp[3].rate"] = "";
							$dataes["prpCitemKindsTemp[3].benchMarkPremium"] = "";
							$dataes["prpCitemKindsTemp[3].disCount"] = "";
							$dataes["prpCitemKindsTemp[3].premium"] = "";
							$dataes["prpCitemKindsTemp[3].amount"] = "";
							$dataes["mainKindValue[3]"] = $business['POLICY']['TTBLI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[3].amount"]=$business['POLICY']['TTBLI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[3].kindCode"]="050602";
							$dataes["prpCitemKindVos[3].kindName"]="三者";
							$dataes["prpCitemKindVos[3].chooseFlag"]="true";


						}else if($val== "TWCDMVI"){
							$dataes["mainKindName[2]"]="盗抢";
							$dataes["relateSpecial[2]"]="050932";
							$dataes["prpCitemKindsTemp[2].kindCode"] = "050501";
							$dataes["prpCitemKindsTemp[2].kindName"]="盗抢";
							$dataes["prpCitemKindsTemp[2].rate"] = "";
							$dataes["prpCitemKindsTemp[2].benchMarkPremium"] = "";
							$dataes["prpCitemKindsTemp[2].disCount"] = "";
							$dataes["prpCitemKindsTemp[2].premium"] = "";
							$dataes["prpCitemKindsTemp[2].amount"] = "";
							$dataes["mainKindValue[2]"] = $business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[2].kindCode"]="050501";
							$dataes["prpCitemKindVos[2].kindName"]="盗抢";
							$dataes["prpCitemKindVos[2].amount"]=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[2].chooseFlag"]="true";


						}else if($val== "TCPLI_DRIVER"){
						    $dataes["mainKindName[4]"]="车上(司机)";
							$data['relateSpecial[4]']="050933";
							$dataes["prpCitemKindsTemp[4].kindCode"] = "050711";
							$dataes["prpCitemKindsTemp[4].kindName"]="车上(司机)";
							$dataes["prpCitemKindsTemp[4].rate"] = "";
							$dataes["prpCitemKindsTemp[4].benchMarkPremium"] = "";
							$dataes["prpCitemKindsTemp[4].disCount"] = "";
							$dataes["prpCitemKindsTemp[4].premium"] = "";
							$dataes["prpCitemKindsTemp[4].amount"] = "";
							$dataes["mainKindValue[4]"] = $business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
							$dataes["prpCitemKindVos[4].kindCode"]="050711";
							$dataes["prpCitemKindVos[4].kindName"]="车上(司机)";
							$dataes["prpCitemKindVos[4].amount"]=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
							$dataes["prpCitemKindVos[4].chooseFlag"]="true";


						}else if($val== "TCPLI_PASSENGER"){
							$dataes["mainKindName[5]"]="车上(乘客)";
							$data['relateSpecial[5]']="050934";
							$dataes["prpCitemKindsTemp[5].kindCode"] = "050712";
							$dataes["prpCitemKindsTemp[5].kindName"]="车上(乘客)";
							$dataes["prpCitemKindsTemp[5].rate"] = "";
							$dataes["prpCitemKindsTemp[5].benchMarkPremium"] = "";
							$dataes["prpCitemKindsTemp[5].disCount"] = "";
							$dataes["prpCitemKindsTemp[5].premium"] = "";
							$dataes["prpCitemKindsTemp[5].amount"] = "";
							$dataes["mainKindValue[5]"] = $business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
							$dataes["quantity"] = $business['POLICY']['TCPLI_PASSENGER_COUNT'];
							$dataes["prpCitemKindVos[5].kindCode"]="050712";
							$dataes["prpCitemKindVos[5].kindName"]="车上(乘客)";
							$dataes["prpCitemKindVos[5].amount"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
							$dataes["prpCitemKindVos[5].chooseFlag"]="true";


						}else if($val== "BSDI"){
							$dataes["subKindName[6]"]	 = "车身划痕";
							$dataes["relateSpecial[6]"] = "";
							$dataes["prpCitemKindVos[6].rate"] = "";
							$dataes["prpCitemKindVos[6].benchMarkPremium"] = "";
							$dataes["prpCitemKindVos[6].disCount"] = "";
							$dataes["prpCitemKindVos[6].premium"] = "";
							$dataes["prpCitemKindVos[6].amount"] = $business['POLICY']['BSDI_INSURANCE_AMOUNT'];
							$dataes["subKindValue[6]"] = $business['POLICY']['BSDI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[6].kindCode"]="050211";
							$dataes["prpCitemKindVos[6].kindName"]="车身划痕";
							$dataes["prpCitemKindVos[6].chooseFlag"]="true";

						}else if($val== "BGAI"){
								$dataes["subKindName[7]"] = "玻璃破碎";
								$dataes["relateSpecial[7]"] = "";
								$dataes["prpCitemKindVos[7].rate"] = "";
								$dataes["prpCitemKindVos[7].benchMarkPremium"] = "";
								$dataes["prpCitemKindVos[7].disCount"] = "";
								$dataes["prpCitemKindVos[7].premium"] = "";
								$dataes["prpCitemKindVos[7].amount"] = "";
								if($business['POLICY']['GLASS_ORIGIN']=="DOMESTIC"){
											$dataes["prpCitemKindVos[7].modeCode"] = "10";//默认国产玻璃
								}else if($business['POLICY']['GLASS_ORIGIN']=="DOMESTIC_SPECIAL"){
											$dataes["prpCitemKindVos[7].modeCode"] = "11";
								}else if($business['POLICY']['GLASS_ORIGIN']=="IMPORTED"){
											$dataes["prpCitemKindVos[7].modeCode"] = "20";
								}else if($business['POLICY']['GLASS_ORIGIN']=="IMPORTED_SPECIAL"){
											$dataes["prpCitemKindVos[7].modeCode"] = "21";
								}
								$dataes["subKindValue[7]"] = "";
								$dataes["prpCitemKindVos[7].chooseFlag"]="true";
								$dataes["prpCitemKindVos[7].kindCode"]="050232";
								$dataes["prpCitemKindVos[7].kindName"]="玻璃破碎";
						}else if($val== "NIELI"){
							$dataes["prpCitemKindVos[8].rate"]="";
							$dataes["prpCitemKindVos[8].benchMarkPremium"]="";
							$dataes["prpCitemKindVos[8].disCount"]="";
							$dataes["prpCitemKindVos[8].amount"]=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[8].premium"]="";
							$dataes["subKindCode[8]"]="050261";
							$dataes["subKindName[8]"]="新增设备损失险";
							$dataes["relateSpecial[8]"]="null";
							$dataes["prpCitemKindVos[8].chooseFlag"]="true";
							$dataes["prpCitemKindVos[8].kindCode"]="050261";
							$dataes["prpCitemKindVos[8].kindName"]="新增设备损失险";
							if(!empty($business['POLICY']['NIELI_DEVICE_LIST']))
							{
								$devcount = count($business['POLICY']['NIELI_DEVICE_LIST']);
								for($idx = 0 ; $idx<$devcount ; $idx++)
								{
									$dataes["deviceList[{$idx}].devicename"]   = $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['NAME'];
									$dataes["deviceList[{$idx}].quantity"]     = $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['COUNT'];
									$dataes["deviceList[{$idx}].purchaseprice"]= $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['BUYING_PRICE'];
									$dataes["deviceList[{$idx}].buydate"]      = $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['BUYING_DATE'];
									$dataes["deviceList[{$idx}].actualvalue"]  = $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['DEPRECIATION'];
									$dataes["deviceList[{$idx}].serialno"]     = $idx+1;
								}
							}


						}else if($val== "VWTLI"){
							$dataes["mainKindName[9]"]="发动机涉水损失险";
							$dataes["prpCitemKindsTemp[9].kindCode"] = "050461";
							$dataes["prpCitemKindsTemp[9].rate"] = "";
							$dataes["prpCitemKindsTemp[9].benchMarkPremium"] = "";
							$dataes["prpCitemKindsTemp[9].disCount"] = "";
							$dataes["prpCitemKindsTemp[9].premium"] = "";
							$dataes["prpCitemKindsTemp[9].amount"] = "";
							$dataes["mainKindValue[9]"] = "";//$business['POLICY']['VWTLI_INSURANCE_AMOUNT'];
							$dataes["subKindValue[9]"] = "";
							$dataes["prpCitemKindVos[9].chooseFlag"]="true";
							$dataes["prpCitemKindVos[9].kindCode"]="050461";
							$dataes["prpCitemKindVos[9].kindName"]="发动机涉水损失险";
						}else if($val== "SLOI"){
							$dataes["subKindName[10]"]   = "自燃损失";
							$dataes["relateSpecial[10]"] = "";
							$dataes["prpCitemKindVos[10].rate"] = "";
							$dataes["prpCitemKindVos[10].benchMarkPremium"] = "";
							$dataes["prpCitemKindVos[10].disCount"] = "";
							$dataes["prpCitemKindVos[10].premium"] = "";
							$dataes["prpCitemKindVos[10].amount"]=$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
							$dataes["subKindValue[10]"] = $business['POLICY']['SLOI_INSURANCE_AMOUNT'];
							$dataes["prpCitemKindVos[10].chooseFlag"]="true";
							$dataes["prpCitemKindVos[10].kindCode"]="050311";
							$dataes["prpCitemKindVos[10].kindName"]="自燃损失";
						}else if($val== "STSFS"){
							$dataes["subKindName[11]"]	  = "指定修理厂";
							$dataes["relateSpecial[11]"] = "";
							$dataes["prpCitemKindVos[11].rate"] = "";
							$dataes["prpCitemKindVos[11].benchMarkPremium"] = "";
							$dataes["prpCitemKindVos[11].disCount"] = "";
							$dataes["prpCitemKindVos[11].premium"] = "";
							if($business['POLICY']['STSFS_RATE'] == 'DOMESTIC')
							{
								$dataes["repairFactory"] = '01';
							}
							elseif($business['POLICY']['STSFS_RATE'] == 'IMPORTED')
							{
								$dataes["repairFactory"] = '02';
							}
							elseif($business['POLICY']['STSFS_RATE'] == 'JOINT_VENTURE')
							{
								$dataes["repairFactory"] = '03';
							}
							$dataes["subKindValue[11]"] = "2000";
							$dataes["prpCitemKindVos[11].chooseFlag"]="true";
							$dataes["prpCitemKindVos[11].kindCode"]="050253";
							$dataes["prpCitemKindVos[11].kindName"]="指定修理厂";
							$dataes["prpCitemKindVos[11].modeCode"]=$dataes["repairFactory"];
						}else if($val== "RDCCI"){
                            $dataes["prpCitemKindVos[12].unitAmount"]=$business['POLICY']['RDCCI_INSURANCE_UNIT'];
                            $dataes["prpCitemKindVos[12].quantity"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
                            $dataes["prpCitemKindVos[12].rate"]="";
                            $dataes["prpCitemKindVos[12].benchMarkPremium"]="";
                            $dataes["prpCitemKindVos[12].disCount"]="";
                            $dataes["prpCitemKindVos[12].amount"]=$business['POLICY']['RDCCI_INSURANCE_UNIT']*$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
                            $dataes["prpCitemKindVos[12].premium"]="";
                            $dataes["subKindCode[12]"]="050441";
                            $dataes["subKindName[12]"]="修理期间费用补偿险";
                            $dataes["relateSpecial[12]"]="";
                            $dataes["subKindValue[12]"]=$business['POLICY']['RDCCI_INSURANCE_UNIT']*$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
                            $dataes["prpCitemKindVos[12].rate"]="";
                            $dataes["prpCitemKindVos[12].benchMarkPremium"]="";
                            $dataes["prpCitemKindVos[12].disCount"]="";
                            $dataes["prpCitemKindVos[12].premium"]="";
                            $dataes["prpCitemKindVos[12].chooseFlag"]="true";
                            $dataes["prpCitemKindVos[12].kindCode"]="050441";
                            $dataes["prpCitemKindVos[12].kindName"]="修理期间费用补偿险";
                        }else if($val== "MVLINFTPSI"){
                            $dataes["prpCitemKindVos[13].rate"]="";
                            $dataes["prpCitemKindVos[13].benchMarkPremium"]="";
                            $dataes["prpCitemKindVos[13].disCount"]="";
                            $dataes["prpCitemKindVos[13].amount"]="";
                            $dataes["prpCitemKindVos[13].premium"]="";
                            $dataes["subKindCode[13]"]="050451";
                            $dataes["subKindName[13]"]="第三方特约险";
                            $dataes["relateSpecial[13]"]="null";
                            $dataes["subKindValue[13]"]="";
                            $dataes["prpCitemKindVos[13].chooseFlag"]="true";
                            $dataes["prpCitemKindVos[13].kindCode"]="050451";
                            $dataes["prpCitemKindVos[13].kindName"]="机动车损失保险无法找到第三方特约险";
                        }else if($val== "TVDI_NDSI"){
                        	$dataes["prpCitemKindsTemp[43].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[43].id.itemKindNo"]="";
							$dataes["prpCitemKindsTemp[43].startDate"]="";
							$dataes["prpCitemKindsTemp[43].kindCode"]="050930";
							$dataes["prpCitemKindsTemp[43].kindName"]="";
							$dataes["prpCitemKindsTemp[43].startHour"]="";
							$dataes["prpCitemKindsTemp[43].endDate"]="";
							$dataes["prpCitemKindsTemp[43].endHour"]="";
							$dataes["prpCitemKindsTemp[43].calculateFlag"]="N33N000";
							$dataes["relateSpecial[43]"]="";
							$dataes["prpCitemKindsTemp[43].flag"]="null";
							$dataes["prpCitemKindsTemp[43].amount"]="";
							$dataes["prpCitemKindsTemp[43].rate"]="";
							$dataes["prpCitemKindsTemp[43].benchMarkPremium"]="";
							$dataes["prpCitemKindsTemp[43].disCount"]="";
							$dataes["prpCitemKindsTemp[43].premium"]="";
							//$dataes["rpCitemKindsTemp[43].chooseFlag"]="on";
							//$dataes["prpCitemKindsTemp[43].kindCode"]="050930";
							//$dataes["prpCitemKindsTemp[43].calculateFlag"]="N33N000";

						}else if($val== "TTBLI_NDSI"){
							$dataes["prpCitemKindsTemp[2].specialFlag"]="on";
							$dataes["prpCitemKindsTemp[44].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[44].kindCode"]="050931";
							$dataes["prpCitemKindsTemp[44].kindName"]="";
							$dataes["prpCitemKindsTemp[44].startHour"]="";
							$dataes["prpCitemKindsTemp[44].endDate"]="";
							$dataes["prpCitemKindsTemp[44].endHour"]="";
							$dataes["prpCitemKindsTemp[44].calculateFlag"]="N33Y000";
							$dataes["relateSpecial[44]"]="";
							$dataes["prpCitemKindsTemp[44].flag"]="null";
							$dataes["prpCitemKindsTemp[44].amount"]="";
							$dataes["prpCitemKindsTemp[44].rate"]="";
							$dataes["prpCitemKindsTemp[44].benchMarkPremium"]="";
							$dataes["prpCitemKindsTemp[44].disCount"]="";
							$dataes["prpCitemKindsTemp[44].premium"]="";
						}else if($val== "TWCDMVI_NDSI"){
							$dataes["prpCitemKindsTemp[3].specialFlag"]="on";
							$dataes["prpCitemKindsTemp[45].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[45].id.itemKindNo"]="";
							$dataes["prpCitemKindsTemp[45].startDate"]="";
							$dataes["prpCitemKindsTemp[45].kindCode"]="050932";
							$dataes["prpCitemKindsTemp[45].startHour"]="";
							$dataes["prpCitemKindsTemp[45].endDate"]="";
							$dataes["prpCitemKindsTemp[45].endHour"]="";
							$dataes["prpCitemKindsTemp[45].calculateFlag"]="N33Y000";
							$dataes["relateSpecial[45]"]="";
							$dataes["prpCitemKindsTemp[45].flag"]="null";
							$dataes["prpCitemKindsTemp[45].amount"]="";
							$dataes["prpCitemKindsTemp[45].rate"]="";
							$dataes["prpCitemKindsTemp[45].benchMarkPremium"]="";
							$dataes["prpCitemKindsTemp[45].disCount"]="";
							$dataes["prpCitemKindsTemp[45].premium"]="";
						}else if($val== "TCPLI_DRIVER_NDSI"){
							$dataes["prpCitemKindsTemp[4].specialFlag"]="on";
							$dataes["prpCitemKindsTemp[46].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[46].id.itemKindNo"]="";
							$dataes["prpCitemKindsTemp[46].startDate"]="";
							$dataes["prpCitemKindsTemp[46].kindCode"]="050933";
							$dataes["prpCitemKindsTemp[46].startHour"]="";
							$dataes["prpCitemKindsTemp[46].endDate"]="";
							$dataes["prpCitemKindsTemp[46].endHour"]="";
							$dataes["prpCitemKindsTemp[46].calculateFlag"]="N33Y000";
							$dataes["relateSpecial[46]"]="";
							$dataes["prpCitemKindsTemp[46].flag"]="null";
						}else if($val== "TCPLI_PASSENGER_NDSI"){
							$dataes["prpCitemKindsTemp[5].specialFlag"]="on";
							$dataes["prpCitemKindsTemp[47].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[47].id.itemKindNo"]="";
							$dataes["prpCitemKindsTemp[47].startDate"]="";
							$dataes["prpCitemKindsTemp[47].kindCode"]="050934";
							$dataes["prpCitemKindsTemp[47].startHour"]="";
							$dataes["prpCitemKindsTemp[47].endDate"]="";
							$dataes["prpCitemKindsTemp[47].endHour"]="";
							$dataes["prpCitemKindsTemp[47].calculateFlag"]="N33Y000";
							$dataes["relateSpecial[47]"]="";
							$dataes["prpCitemKindsTemp[47].flag"]="null";

						}else if($val== "BSDI_NDSI"){
							$dataes["prpCitemKindVos[6].specialFlag"]="on";
							$dataes["prpCitemKindsTemp[48].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[48].id.itemKindNo"]="";
							$dataes["prpCitemKindsTemp[48].startDate"]="";
							$dataes["prpCitemKindsTemp[48].kindCode"]="050937";
							$dataes["prpCitemKindsTemp[48].startHour"]="";
							$dataes["prpCitemKindsTemp[48].endDate"]="";
							$dataes["prpCitemKindsTemp[48].endHour"]="";
							$dataes["prpCitemKindsTemp[48].calculateFlag"]="N33Y000";
							$dataes["prpCitemKindsTemp[48].flag"]="null";
							$dataes["prpCitemKindsTemp[48].amount"]="";
							$dataes["prpCitemKindsTemp[48].rate"]="";
							$dataes["prpCitemKindsTemp[48].benchMarkPremium"]="";
							$dataes["prpCitemKindsTemp[48].disCount"]="";
							$dataes["prpCitemKindsTemp[48].premium"]="";
						}else if($val== "SLOI_NDSI"){
							$dataes["prpCitemKindVos[10].specialFlag"]='on';
							$dataes["prpCitemKindsTemp[49].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[49].id.itemKindNo"]="";
							$dataes["prpCitemKindsTemp[49].startDate"]="";
							$dataes["prpCitemKindsTemp[49].kindCode"]="050935";
							$dataes["prpCitemKindsTemp[49].startHour"]="";
							$dataes["prpCitemKindsTemp[49].endDate"]="";
							$dataes["prpCitemKindsTemp[49].endHour"]="";
							$dataes["prpCitemKindsTemp[49].calculateFlag"]="N33Y000";
							$dataes["prpCitemKindsTemp[49].flag"]="null";
							$dataes["prpCitemKindsTemp[49].amount"]="";
							$dataes["prpCitemKindsTemp[49].rate"]="";
							$dataes["prpCitemKindsTemp[49].benchMarkPremium"]="";
							$dataes["prpCitemKindsTemp[49].disCount"]="";
							$dataes["prpCitemKindsTemp[49].premium"]="";
						}else if($val== "NIELI_NDSI"){
							$dataes["prpCitemKindVos[8].specialFlag"]='on';
							$dataes["prpCitemKindsTemp[50].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[50].id.itemKindNo"]="";
							$dataes["prpCitemKindsTemp[50].startDate"]="";
							$dataes["prpCitemKindsTemp[50].kindCode"]="050936";
							$dataes["prpCitemKindsTemp[50].startHour"]="";
							$dataes["prpCitemKindsTemp[50].endDate"]="";
							$dataes["prpCitemKindsTemp[50].endHour"]="";
							$dataes["prpCitemKindsTemp[50].calculateFlag"]="N33Y000";
							$dataes["prpCitemKindsTemp[50].flag"]="null";
							$dataes["prpCitemKindsTemp[50].amount"]="";
							$dataes["prpCitemKindsTemp[50].rate"]="";
							$dataes["prpCitemKindsTemp[50].benchMarkPremium"]="";
							$dataes["prpCitemKindsTemp[50].disCount"]="";
							$dataes["prpCitemKindsTemp[50].premium"]="";
						}else if($val== "VWTLI_NDSI"){
							$dataes["prpCitemKindVos[9].specialFlag"]='on';
							$dataes["prpCitemKindsTemp[51].chooseFlag"]="on";
							$dataes["prpCitemKindsTemp[51].id.itemKindNo"]="";
							$dataes["prpCitemKindsTemp[51].startDate"]="";
							$dataes["prpCitemKindsTemp[51].kindCode"]="050938";
							$dataes["prpCitemKindsTemp[51].startHour"]="";
							$dataes["prpCitemKindsTemp[51].endDate"]="";
							$dataes["prpCitemKindsTemp[51].endHour"]="";
							$dataes["prpCitemKindsTemp[51].calculateFlag"]="N33Y000";
							$dataes["prpCitemKindsTemp[51].flag"]="null";
							$dataes["prpCitemKindsTemp[51].amount"]="";
							$dataes["prpCitemKindsTemp[51].rate"]="";
							$dataes["prpCitemKindsTemp[51].benchMarkPremium"]="";
							$dataes["prpCitemKindsTemp[51].disCount"]="";
							$dataes["prpCitemKindsTemp[51].premium"]="";
						}

			}

$dataes["isCqp"]="1";
$dataes["noDamYearsCI"]="0";
$dataes["lastDamagedCI"]="0";
$dataes["pageType"]="NEW";
$dataes["carDeviceSubKindIndex"]="";
$dataes["xinXuZhuanFlag"]="0";
$dataes["riskCode"]="DAA";
$dataes["comCode"]="50011200";
$dataes["prpCmain.comCode"]="50011200";
$dataes["platRadio"]="1";
$dataes["bizType"]="";
$dataes["editTypeCheck"]="new";
$dataes["editType"]="NEW";
$dataes["carDamageAmountFloat"]="30";
$dataes["carP"]="";
$dataes["kindP"]="";
$dataes["JSCustomerId"]="";
$dataes["prpCmain.businesNature"]="";
$dataes["prpCmain.agentCode"]="";
$dataes["prpCmain.handler1Code"]="";
$dataes["prpCmain.handlerCode"]="";
$dataes["prpCmain.operateCode"]="";
$dataes["operateCode"]="00025675";
$dataes["selectedBusinessnature"]="2";
$dataes["purchasePriceRangeValidation"]="0";
$dataes["prpCmain.proposalNo"]="";
$dataes["prpCmainCI.proposalNo"]="";
$dataes["smsTemplate"]="尊敬的{carOwner}客户，人保车险管家为您服务。{licenseNo} 保险{lastPolicyEndDate}即将到期，现为您报价：交强险{ciPremium}元，商业险{biPremium}元，总计{sumPremium}元。
其中{premiumDetailText}。以上项目供您参考，更多优惠活动请来电咨询。客户经理张焰，电话13808341168，关注重庆人保财险微信公众号，及时获取活动信息，掌握理赔进度。祝您生活愉快！"; 
$dataes["emsTemplate"]="nihao";
$dataes["vechileQueryFromPlat"]="1";
$dataes["vehicleplat"]="0";
$dataes["instantGuarantee"]="0";
$dataes["renewed"]="0";
$dataes["transfer"]="0";
$dataes["zhuanbaobianswer.queryseuqenceno"]=empty($result["zhuanbaobianswer.queryseuqenceno"])?"":$result["zhuanbaobianswer.queryseuqenceno"];
$dataes["zhuanbaobianswer.answer"]=empty($result["zhuanbaobianswer.answer"])?"":$result["zhuanbaobianswer.answer"];
$dataes["zhuanbaocianswer.queryseuqenceno"]=empty($result["zhuanbaocianswer.queryseuqenceno"])?"":$result["zhuanbaocianswer.queryseuqenceno"];
$dataes["zhuanbaocianswer.answer"]=empty($result["zhuanbaocianswer.answer"])?"":$result["zhuanbaocianswer.answer"];
$dataes["insuredPageNum"]="1";
$dataes["eadinfo.isEAD"]="0";
$dataes["eadinfo.flag"]="";
$dataes["eadinfo.eachCopies"]="";
$dataes["eadinfo.totalCopies"]="";
$dataes["eadinfo.insuredCount"]="";
$dataes["custPhoneNo"]="";
$dataes["custAddress"]="";
$dataes["custCertificateNo"]="";
$dataes["damageTimes"]="";
$dataes["netuniqueId"]="";
$dataes["vehicleBJ"]="-1";
$dataes["oldLicenceNo"]="渝C2S270";
$dataes["vehiclePlatNoDataBJ"]="";
$dataes["vehicleJS"]="";
$dataes["vehicleJS.purchasePrice"]="";
$dataes["vehicleJS.carLotEquQuality"]="";
$dataes["vehicleJS.tonCount"]="";
$dataes["vehicleJS.seatCount"]="";
$dataes["vehicleJS.exhaustScale"]="";
$dataes["vehicleJS.searchcode"]="";
$dataes["searchcodeJS"]="";
$dataes["updateOpConfAfterQuoteFlag"]="0";
$dataes["lastIdentifyNo"]="";
$dataes["netSaleTest"]="0";
$dataes["poundageCalculate"]="0";
$dataes["operateConfigPrpList"]="[com.picc.qt.schema.vo.QtOperateConfigVo@4c0fea5]";
$dataes["operateConfigNetList"]="[]";
$dataes["selectedOperateConfigId"]="";
$dataes["selectedNetOperateConfigId"]="";
$dataes["ecifUserTypeCode"]="";
$dataes["giftPackageId"]="";
$dataes["giftPackageComCode"]="";
$dataes["replaceableNum"]="0";
$dataes["clubGiftPackageDesStr"]="";
$dataes["memPhone"]="";
$dataes["contactIdForClub"]="";
$dataes["customerID"]="";
$dataes["KhstCustId"]="";
$dataes["registerOrNot"]="";
$dataes["clubGiftNum"]="0";
$dataes["smsForClubTemplate"]="";
$dataes["prpCitemCar.searchseqno"]="";
$dataes["isQuoteRenewByLicenseNo"]="1";
$dataes["isOwner"]="0";
$dataes["comboFLag"]="0";
$dataes["source"]="1";
$dataes["platFlag"]="1";
$dataes["carType"]="1";
$dataes["selectedOperateConfig"]="QT500000001470819610769";
$dataes["lastPolicyNo"]="";
$dataes["engineNo4Renew1"]="";
$dataes["frameNo4Renew1"]="";
$dataes["identifyNo4Renew1"]="";
$dataes["licenseNo4Renew"]="";
$dataes["licenseType4Renew"]="02";
$dataes["engineNo4Renew2"]="";
$dataes["frameNo4Renew2"]="";
$dataes["identifyNo4Renew2"]="";
$dataes["prpCitemCar.licenseNo"]=$result['LICENSE_NO'];
$dataes["prpCitemCar.licenseType"]=$result['LICENSE_TYPE'];
$dataes["prpCitemCar.engineNo"]=$result['ENGINE_NO'];
$dataes["prpCitemCar.vinNo"]=$result['VIN_NO'];
$dataes["prpCitemCar.frameNo"]="";
$dataes["carKindPopFlag_quickChange"]="true";
$dataes["carKindPopFlag_quickChange"]="true";
$dataes["prpCitemCar.carKindCode"]=$result['VEHICLE_TYPE'];
$dataes["prpCitemCar.useNatureCode"]=$result['USE_CHARACTER'];
$dataes["carOwner"]=$result['OWNER'];
$dataes["prpCitemCar.enrollDate"]=$result['ENROLL_DATE'];
$dataes["prpCitemCar.useYears"]="0";
$dataes["prpCmain.startDate"]=$business['BUSINESS_START_TIME'];
$dataes["prpCmain.startDateCI"]=empty($mvtalci)?'':$mvtalci['MVTALCI_START_TIME'];
$dataes["prpCitemCar.brandName"]=str_replace( "牌", "", $result['MODEL']);
$dataes["hidddenbrandName"]="";
$dataes["prpCitemcar.modelDemandNo"]="";
$dataes["prpCitemCar.vehicleMaker"]="";
$dataes["prpCitemCar.familyId"]="";
$dataes["prpCitemCar.brandId"]="FTA";
$dataes["carFamilyName"]="";
$dataes["carBrandName"]="";
$dataes["carGroupId"]="";
$dataes["carGroupName"]="";
$dataes["carModelInfo"]="";
$dataes["purchasePriceUpStrFromDb"]="";
$dataes["purchasePriceDownStrFromDb"]="";
$dataes["SZpurchasePriceUp"]="";
$dataes["SZpurchasePriceDown"]="";
$dataes["purchasePriceUp"]="NaN";
$dataes["purchasePriceDown"]="NaN";
$dataes["prpCitemCar.modelCode"]=$result['MODEL_CODE'];
$dataes["prpCitemCar.modelCodeAlias"]="";
$dataes["bjmpCheck"]="";
$dataes["quantity"]="4";
$dataes["sumPremium"]="0";
$dataes["biPremium"]="0";
$dataes["ciPremium"]="855.00";
$dataes["sumDiscount"]="0";
$dataes["biDiscount"]="0";
$dataes["ciDiscount"]="1.0000";
$dataes["damageTimesBI"]="0";
$dataes["damageTimesCI"]="0";
$dataes["sumPayTax"]="0";
$dataes["thisPayTax"]="0";
$dataes["prePayTax"]="0";
$dataes["delayPayTax"]="0";
$dataes["quotationId"]="QT5001097011472269444531";
$dataes["quotationNoBI"]="";
$dataes["quotationNoCI"]="";
$dataes["proposalNoBI"]="";
$dataes["proposalNoCI"]="";
$dataes["bizNo"]="";
$dataes["bizNoCI"]="";
$dataes["premium[0]"]="400.85";
$dataes["premium"]="1587.48";
$dataes["premium"]="428.88";
$dataes["premium"]="512.98";
$dataes["premium"]="855.00";
$dataes["netbirthday"]="";
$dataes["agriflag"]="0";
$dataes["carcheckstatus"]="";
$dataes["carchecker"]="";
$dataes["carchecktime"]="";
$dataes["subPremium"]="1587.48";
$dataes["subPremium"]="428.88";
$dataes["subPremium"]="512.98";
$dataes["subPremium"]="855.00";
$dataes["subPremium"]="400.85";
$dataes["subPremium"]="1.00";
$dataes["subPremium"]="0.85";
$dataes["subPremium"]="0.85";
$dataes["PurchasePriceScal"]="10";
$dataes["prpCitemCar.purchasePrice"]=$result['BUYING_PRICE'];
$dataes["prpCitemCar.actualValue"]="";
$dataes["actualKindValue"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
$dataes["prpCitemCar.seatCount"]=$result['SEATS'];
$dataes["prpCitemCar.tonCount"]=$result['TONNAGE'];
$dataes["prpCitemCar.countryNature"]="03";
$dataes["countryNature"]="";
$dataes["prpCitemCar.exhaustScale"]=$result['ENGINE'];
$dataes["prpCitemCar.runMiles"]="";
$dataes["prpCitemCar.runAreaCode"]="11";
$dataes["prpCitemCar.carLotEquQuality"]=$result['KERB_MASS'];
$dataes["prpCcarShipTax.taxComCode"]="";
$dataes["TaxComCodeDes"]="";
$dataes["prpCitemCarExt.lastDamagedCI"]="";
$dataes["prpCitemCarExt.noDamYearsBI"]="";
$dataes["prpCitemCarExt.thisDamagedBI"]="";
$dataes["prpCitemCarExt.lastDamagedBI"]="";
$dataes["prpQmainVo.ext3"]="";
$dataes["importantProjectCode"]="";
$dataes["prpCitemCarExt.lastDamagedA"]="";
$dataes["prpCmain.projectCode"]="";
$dataes["projectCodeDes"]="";
$dataes["prpCcarShipTax.leviedDate"]=date('Y-m-d',time());
$dataes["prpCitemCar.energyType"]="0";
$dataes["prpCitemCar.referenceActualValue"]=$result['BUYING_PRICE']."00";
$dataes["transferDate"]="";
$dataes["prpCitemCar.queryArea"]="500000";
$dataes["queryAreaDesc"]="";
$dataes["prpCitemCar.carInsuredRelation"]="所有";
$dataes["prpCitemCar.nonlocalFlag"]="0";
$dataes["prpCitemCar.loanVehicleFlag"]="0";
$dataes["prpCitemCar.loanName"]="";
$dataes["prpCitemCar.clauseType"]=$result['TIAOKUAN_TYPE'];
$dataes["prpCitemCar.cylindercount"]="";
$dataes["prpCitemCar.licenseColorCode"]="01";
$dataes["prpCitemCar.netWeifaFlag"]="0";
$dataes["modelCodeAlias"]="";
$dataes["Calculatemode"]="C1";
$dataes["_insuredFlag"]="";
$dataes["_insuredFlag_hide"]="投保人";
$dataes["insuredCodeOld"]="";
$dataes["_insuredFlag"]="";
$dataes["_insuredFlag_hide"]="被保险人";
$dataes["_insuredFlag"]="";
$dataes["_insuredFlag_hide"]="车主";
$dataes["_insuredFlag_hide"]="联系人";
$dataes["prpCinsureds[0].insuredType"]="1";
$dataes["prpCinsureds[0].insuredName"]="";
$dataes["prpCinsureds[0].insuredCode"]="";
$dataes["prpCinsureds[0].identifyType"]="01";
$dataes["prpCinsureds[0].unitType"]="100";
$dataes["prpCinsureds[0].identifyNumber"]="";
$dataes["prpCinsureds[0].dateValid"]="";
$dataes["prpCinsureds[0].nationality"]="CHN";
$dataes["prpCinsureds[0].resident"]="A";
$dataes["prpCinsureds[0].mobile"]="";
$dataes["prpCinsureds[0].province"]="500000";
$dataes["prpCinsureds[0].city"]="";
$dataes["prpCinsureds[0].area"]="";
$dataes["prpCinsureds[0].insuredAddress"]="";
$dataes["prpCinsureds[0].postcode"]="";
$dataes["prpCinsureds[0].phonenumber"]="";
$dataes["prpCinsureds[0].sex"]="1";
$dataes["prpCinsureds[0].birthday"]="";
$dataes["prpCinsureds[0].age"]="";
$dataes["prpCinsureds[0].drivingcartype"]="C1";
$dataes["prpCinsureds[0].drivinglicenseno"]="";
$dataes["prpCinsureds[0].drivingyears"]="";
$dataes["prpCinsureds[0].causetroubletimes"]="";
$dataes["prpCinsureds[0].acceptlicensedate"]="";
$dataes["prpCinsureds[0].insuredFlag"]="11100000";
$dataes["prpCinsureds[0].insuredCode"]="";
$dataes["prpCinsureds[0].versionNo"]="";
$dataes["prpCinsureds[0].auditStatus"]="";
$dataes["copies"]="1";
$dataes["eadnumbers"]="2";
$dataes["eachcount"]="1";
$dataes["mainAndSubKindCount"]="11";
$dataes["subUseFlag"]="";
$dataes["tmpSubCount"]="";
$dataes["hiddenSubCode"]="";
$dataes["hiddenSubValue"]="";
$dataes["hiddenSubMode"]="";
$dataes["hiddenSubUnit"]="";
$dataes["hiddenSubOtherValue"]="";
$dataes["hiddenSubQuantity"]="";
$dataes["hiddenSubSpecial"]="";
$dataes["hidden_index_itemKind"]="42";
$dataes["specialKindIndex"]="45";
$dataes["taskId"]="";
$dataes["nolocalflag"]="0";		
return $dataes;

	
	}









}




?>