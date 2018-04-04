<?php
/**
 * 项目：          保险数据抓取功能包
 * 文件名:         PICC_IDE_A.class.php
 * 版权所有：      2015 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *
 * 人保财险数据抓取
 **/
//error_reporting(E_ALL);
//ini_set( 'display_errors', 'On' );

class YNPICC_IDE_A
{
	
	private $config,$cookie,$currentUser,$comCode,$premiumData;
	//保单信息列表
	private $policyList = array();
	//违法信息
	private $feccs = array();
	//风险警示信息
	private $risks = array();
	//理赔信息
	private $claims = array();
	//车辆信息
	private $autoInfo = array();
    //联系人信息
	private $cinsured = array();
	
	//险种代码转换
	private $kind_code = array(
	                  '050200' => 'TVDI'        ,
                      '050500' => 'TWCDMVI'     ,
                      '050600' => 'TTBLI'       ,
                      '050701' => 'TCPLI_D'     ,
                      '050702' => 'TCPLI_P'     ,
                      '050310' => 'SLOI'        ,
					  '050210' => 'BSDI',
                      '050911' => 'TVDI_NDSI'   ,
                      '050912' => 'TTBLI_NDSI'  ,
                      '050921' => 'TWCDMVI_NDSI',
                      '050928' => 'TCPLI_D_NDSI',
                      '050929' => 'TCPLI_P_NDSI',
					  '050971' => 'BSDI_NDSI',
					  );
	//risk险种代码转换
	private $risk_kind_code = array(
	                  '200' => 'TVDI'        ,
                      '500' => 'TWCDMVI'     ,
                      '600' => 'TTBLI'       ,
                      '701' => 'TCPLI_D'     ,
                      '702' => 'TCPLI_P'     ,
                      '310' => 'SLOI'        ,
					  '210' => 'BSDI'            ,
                      '911' => 'TVDI_NDSI'   ,
                      '912' => 'TTBLI_NDSI'  ,
                      '921' => 'TWCDMVI_NDSI',
                      '928' => 'TCPLI_D_NDSI',
                      '929' => 'TCPLI_P_NDSI',
					  '971' => 'BSDI_NDSI',
					  );
					  
	//浮动标志转换表
	private	$rate_rloat_flag = array(
		              '00'=>'RATE_RLOAT_00'     ,
		              '01'=>'RATE_RLOAT_01'     ,
					  '02'=>'RATE_RLOAT_02'     ,
		              '03'=>'RATE_RLOAT_03'     ,
		              '04'=>'RATE_RLOAT_04'     ,
		              '05'=>'RATE_RLOAT_05'     ,
		              '06'=>'RATE_RLOAT_06'     ,
				      '07'=>'RATE_RLOAT_07'     ,
		              '08'=>'RATE_RLOAT_08'     ,
		              '09'=>'RATE_RLOAT_09'     ,
		              '99'=>'RATE_RLOAT_99'     ,
		              );
	//号牌类型转换
	private $license_type = array(
		            '00' =>'LARGE_AUTOMOBILE'             ,
		            '01' =>'TRAILER'                      ,
					'02' =>'SMALL_CAR'                    ,
		            '03' =>'EMBASSY_CAR'                  ,
		            '04' =>'CONSULATE_VEHICLE'            ,
		            '05' =>'HK_MACAO_ENTRY_EXIT_CAR'      ,
		            '06' =>'COACH_CAR'                    ,
					'07' =>'POLICE_CAR'                   ,
		            '08' =>'GENERAL_MOTORCYCLE'           ,
		            '09' =>'MOPED'                        ,
		            '10' =>'EMBASSY_MOTORCYCLE'           ,
		            '11' =>'CONSULATE_MOTORCYCLE'         ,
		            '12' =>'COACH_MOTORCYCLE'             ,
		            '13' =>'POLICE_MOTORCYCLE'            ,
		            '14' =>'TEMPORARY_VEHICLE'            ,
					'22' =>'TEMPORARY_VEHICLE_1'          ,            	
		            );
	//证件类型转换					
	private $identify_type = array(
					'01'=>'IDENTITY_CARD'                               ,
					'02'=>'RESIDENCE_BOOKLET'                           ,
					'03'=>'PASSPORT'                                    ,
					'04'=>'MILITARY_DOCUMENTS'                          ,
					'05'=>'DRIVER_LICENSE'                              ,
					'06'=>'HOME_CARD'                                   ,
					'07'=>'HK_IDENTITY_CARD'                            ,
					'08'=>'WORK_NO'                                     ,
					'09'=>'TO_TAIWAN_PASS'                              ,
					'10'=>'HK_MACAO_PASS'                               ,
					'15'=>'SOLDIER_CARD'                                ,
					'25'=>'HK_MACAO_RESIDENTS_TRAVELING_MAINLAND_PASSES',
					'26'=>'TAIWAN_RESIDENTS_TRAVELING_MAINLAND_PASSES'  ,
					'31'=>'ORGANIZATION_CODE_CERTIFICATE'               ,
					'99'=>'OTHER'                                       ,		
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
		            '211' => 'NON_OPERATING_PRIVATE'        	,			
		            '212' => 'NON_OPERATING_ENTERPRISE'     	,			
		            '213' => 'NON_OPERATING_AUTHORITY'      	,			
		            '111' => 'OPERATING_LEASE_RENTAL'       	,			
		            '112' => 'OPERATING_CITY_BUS'           	,			
		            '113' => 'OPERATING_HIGHWAY_BUS'        	,			
		            '220' => 'NON_OPERATING_TRUCK'          	,			
		            '120' => 'OPERATING_TRUCK'              	,			
		            '280' => 'DUAL_PURPOSE_TRACTOR'         	,			
		            '180' => 'TRANSPORT_TRACTOR'            	,			
					'000' => 'GENERAL'                          ,
                    '190' => 'OPERATING_OTHER'                  ,
                    '290' => 'NON_OPERATING_OTHER'  			,		
					);
	private	$origin = Array(
					'01' =>	'DOMESTIC',
					'02' =>	'IMPORTED',
					'03' =>	'JOINT_VENTURE'
					);
	private	$run_area = array(
					'03' => 'THE_PROVINCE',
					'11' =>	'THE_TERRITORY_OF',
					'12' =>	'THE_FIXED_LINE',
					'13' =>	'THE_FLOOR',
					);
 
	private $insured_type = array(
					'1' => 'PERSONAL',
					'2' => 'GROUP',					
					);
					
	/**
	 * 构造函数
	 * 参数:
	 * @config          必需。配置文件
	 * @cachePath       必需。缓存目录
	 **/
	function __construct($config,$cachePath)
	{
		$this->config = $config;
		$this->cookie = $cachePath.'cookie_PICC.txt';
		$this->currentUser = $config['uid'];
		
		$this->premiumData = $cachePath.'JSPICCPremiumCalculate.dat';
		if(!is_file($this->premiumData))
		{
			copy(dirname(__FILE__).'/JSPICCPremiumCalculate.dat',$this->premiumData);
		}
	}

	/**
	 * 测式承保平台可用
	 *
	 **/
	public function test()
	{
		$indexURL = 'http://10.134.131.112';
			
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $indexURL);  
	    curl_setopt($curl, CURLOPT_SSLVERSION, 4);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
	    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)'); 
		curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate'));
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
	    curl_setopt($curl, CURLOPT_POST, 0); 
	    curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie); 
	    curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie); 
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
	    curl_setopt($curl, CURLOPT_HEADER, 0); 
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($curl, CURLOPT_REFERER, '');
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		$htmlStr = curl_exec($curl); 
		$retinfo = curl_getinfo($curl);
		
		if(curl_errno($curl) || $retinfo['http_code'] != '200')
		{
			curl_close($curl);
			return false;
		}
		return true;	
	}	
	
	private function post($url,$data,$refer,$encodeing = null,$addheader=array())
	{
		$header = array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate');
		if(!empty($addheader)) $header = array_merge($header,$addheader);
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);  
	    curl_setopt($curl, CURLOPT_SSLVERSION, 4);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 		
	    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; chromeframe/30.0.1599.101; EmbeddedWB 14.52 from: http://www.bsalsa.com/ EmbeddedWB 14.52)'); 
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
	    curl_setopt($curl, CURLOPT_POST, 1); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie); 
	    curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie); 
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
	    curl_setopt($curl, CURLOPT_HEADER, 0); 
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 	    
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);	
		if(!empty($refer)) curl_setopt($curl, CURLOPT_REFERER, $refer);		
		if(!empty($encodeing)) curl_setopt($curl, CURLOPT_ENCODING ,$encodeing);
		$htmlStr = curl_exec($curl);
		$retinfo = curl_getinfo($curl);
		if(curl_errno($curl))
		{	
			curl_close($curl);
			return -2;
		}
		
		if($retinfo['http_code'] != '200')
		{
			curl_close($curl);
			return -3;
		}
		
		if(!$this->checkLogin($retinfo))
		{
			$loginCount = 0;
			$loginsucc = false;
			while(true)
			{
				if($loginCount>3) break;
				$loginsucc = $this->login();
				if($loginsucc) break;
				
				$loginCount++;
				sleep(3);
				
			}
			if(!$loginsucc)
			{
				curl_close($curl);
				return -1;
			}
			else
			{
				curl_setopt($curl, CURLOPT_URL, $url);  
				curl_setopt($curl, CURLOPT_POST, 1); 
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);		
				if(!empty($encodeing)) curl_setopt($curl, CURLOPT_ENCODING ,$encodeing);				
				$htmlStr = curl_exec($curl);					
			}	
		}

		curl_close($curl);
		return $htmlStr;
	}
	
	private function get($url,$refer=null,$encodeing = null,$addheader=array())
	{
		$header = array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate');
		if(!empty($addheader)) $header = array_merge($header,$addheader);		
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);  
	    curl_setopt($curl, CURLOPT_SSLVERSION, 4);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
	    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; chromeframe/30.0.1599.101; EmbeddedWB 14.52 from: http://www.bsalsa.com/ EmbeddedWB 14.52)'); 
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
	    curl_setopt($curl, CURLOPT_POST, 0); 
	    curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie); 
	    curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie); 
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
	    curl_setopt($curl, CURLOPT_HEADER, 0); 
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 	    
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);	
		if(!empty($refer)) curl_setopt($curl, CURLOPT_REFERER, $refer);		
		if(!empty($encodeing)) curl_setopt($curl, CURLOPT_ENCODING ,$encodeing);
		$htmlStr = curl_exec($curl);
		$retinfo = curl_getinfo($curl);

		if(curl_errno($curl))
		{
			curl_close($curl);
			return -2;
		}
		
		if($retinfo['http_code'] != '200')
		{
			curl_close($curl);
			return -3;
		}
		
		if(!$this->checkLogin($retinfo))
		{
			$loginCount = 0;
			$loginsucc = false;
			while(true)
			{
				if($loginCount>3) break;
				$loginsucc = $this->login();
				if($loginsucc) break;
				$loginCount++;
				sleep(3);				
			}
			if(!$loginsucc)
			{
				curl_close($curl);
				return -1;
			}
			else
			{
				curl_setopt($curl, CURLOPT_URL, $url);  
				curl_setopt($curl, CURLOPT_POST, 0); 
				if(!empty($encodeing)) curl_setopt($curl, CURLOPT_ENCODING ,$encodeing);						
				$htmlStr = curl_exec($curl);					
			}	
		}

		curl_close($curl);
		return $htmlStr;
	}	
	
	/**
	 * 检查是否是未登录
	 * 参数:
	 * @curlInfo        必需。curl_getinfo返回结果
	 * 返回值:登录 true,未登录 false
	 **/	
	private function checkLogin($curlInfo)
	{
		if(strstr($curlInfo['url'],'https://10.134.131.112:8888'))
		{
			return false;
		}
		return true;
	}
	

	/**
	 * 登录WEB
	 * 参数:
	 * 无
	 * 返回值:成功 true,失败 false
	 **/	
	private function  login()
	{
		$indexURL = 'http://10.134.131.112';
		$loginURL = 'https://10.134.131.112:8888/casserver/login?service=http%3A%2F%2F10.134.131.112%3A80%2Fportal%2Findex.jsp';
		//$refURL   = 'http://10.134.131.112:8000/prpall/business/editRenewalSearch.do';
		$refURL = 'http://10.134.131.112:8000/prpall/business/prepareEdit.do?bizType=PROPOSAL&editType=NEW';
		
			
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $indexURL);  
	    curl_setopt($curl, CURLOPT_SSLVERSION, 4);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
	    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)'); 
		curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate'));
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
	    curl_setopt($curl, CURLOPT_POST, 0); 
	    curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie); 
	    curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie); 
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
	    curl_setopt($curl, CURLOPT_HEADER, 0); 
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($curl, CURLOPT_REFERER, '');
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);

		$htmlStr = curl_exec($curl); 
		$htmlStr = mb_convert_encoding($htmlStr,'UTF-8','GB2312');
		$retinfo = curl_getinfo($curl);

		if(curl_errno($curl) || $retinfo['http_code'] != '200')
		{
			curl_close($curl);
			return false;
		}
		if(strstr($retinfo['url'] , 'http://10.134.131.112/portal/'))
		{
			
			curl_close($curl);
			return true;
		}
		else
		{
			$inputs = extraction_html_inputs($htmlStr);
			$lt = '';
			if(array_key_exists('lt',$inputs))
			{
				$lt = $inputs['lt']['value'];  
			}
			$this->currentUser = $this->config['uid'];
			$data = "PTAVersion=&toSign=&Signature=&rememberFlag=0&pcguid=&loginMethod=nameAndPwd&username={$this->config['uid']}&password={$this->config['pwd']}";
			$data .= "&userMac=&key=no&errorKey=no&_eventId=submit&button.x=20&button.y=0&lt={$lt}";
		    curl_setopt($curl, CURLOPT_URL, $loginURL);
		    curl_setopt($curl, CURLOPT_REFERER, $loginURL);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
		    curl_setopt($curl, CURLOPT_POST, 1); 
			curl_exec($curl); 
			$retinfo = curl_getinfo($curl);	

			if(!curl_errno($curl) && $retinfo['http_code'] == 200 && strstr($retinfo['url'] , 'http://10.134.131.112'))
			{
				curl_setopt($curl, CURLOPT_URL, $refURL);
				curl_setopt($curl, CURLOPT_POST, 0);
				curl_setopt($curl, CURLOPT_ENCODING ,'gzip');

				$htmlStr = curl_exec($curl);
				$htmlStr = mb_convert_encoding($htmlStr,'UTF-8','GB2312');				
			
				$retinfo = curl_getinfo($curl);	
				
				if(!curl_errno($curl) && $retinfo['http_code'] == '200' || strstr($retinfo['url'] , 'http://10.134.131.112'))
				{
					
					$inputs = extraction_html_inputs_a($htmlStr);
					$selects = extraction_html_selects($htmlStr);					
					$srcarr = array_merge($inputs,$selects);
					$rplarr = array();
					$ncfields = array(
						'randomProposalNo',
						'userCode',
						'comCode',
						'prpCmain.comCode',
						'prpCmain.handler1Code',
						'prpCmain.agentCode',
						'agentCode','prpCmain.makeCom');
					$cfields = array(	
						'comCodeDes',						
						'handler1CodeDes',
						'handlerCodeDes',
						'handlerInfo',
						'prpCmain.businessNature',
						'businessNatureTranslation',
						'makeComDes');	
					foreach($srcarr as $el)
					{
						if(array_key_exists('name',$el))
						{
							if(in_array($el['name'],$ncfields)) 
							{
								$rplarr[] = $el;
							}
							elseif( in_array($el['name'],$cfields)) 
							{
								$el['value']=urlencode(iconv('UTF-8','GBK',$el['value']));
								$rplarr[] = $el;
							}
						}						
					}

					$postdata = file_get_contents(dirname(__FILE__).'/JSPICCPremiumCalculate.dat');
					$postdata = extraction_urlparam_replace($postdata,$rplarr);
					file_put_contents($this->premiumData,$postdata);
					curl_close($curl);
					return true;
				}	
			}			
		}
		curl_close($curl);
		return false;
	}
	
	/**
	 * 查询顾客信息
	 * 参数:
	 * @info                     必需。查询参数
	 *     identify_type         证件类型
     *     identify_number 	     证件号码
	 *     insured_code          客户代码
	 * 返回值:失败 false,成功返回数组
	 **/			
	private function queryCustomInfo($info)
	{		
		$url = "http://10.134.131.112:8000/prpall/custom/customAmountQueryP.do?";
		$refUrl = "http://10.134.131.112:8000/prpall/business/prepareEdit.do?bizType=PROPOSAL&editType=NEW";
		if(array_key_exists('insured_code',$info))
		{
			$url .= "_identifyType=&_insuredName=&_identifyNumber=&_insuredCode={$info['insured_code']}";
		}
		elseif(array_key_exists('identify_type',$info) && array_key_exists('identify_number',$info))
		{
			$url .= "_identifyType={$info['identify_type']}&_insuredName=&_identifyNumber={$info['identify_number']}&_insuredCode=";
		}	
		else
		{
			return false;
		}
		
		$htmlstr = $this->get($url,$refUrl,'gzip');
		if(!is_string($htmlstr)) return $htmlstr;
		$cutmsters = json_decode($htmlstr,true);
		if(!empty($cutmsters) && $cutmsters['totalRecords']>0)
		{
			return $cutmsters['data'][0];
		}	
		return false;	
	}
	
	
	
	/**
	 * 查询人保保单
	 * 参数:
	 * @info                     必需。查询参数
	 *     license_no            车牌号
     *     license_type 	     号牌类型
	 * 返回值:失败 false,成功返回数组
	 **/		
	public function queryPolicyList($info = array())
	{
		if(empty($info))
		{
			return false;
		}
		$plate_type = array();
		if(!empty($info['license_type']))
		{
			foreach($this->license_type as $ptc=>$val)
			{
				if($val == $info['license_type'])
				{
					$plate_type[] = $ptc;
					break;
				}
			}
		}
		if(empty($plate_type))
		{
			//$plate_type = array('02','01','03','04','05','06','07','08','09');
			$plate_type = array('02');
		}

		
		$queryURL = 'http://10.134.131.112:8000/prpall/business/selectRenewal.do?pageSize=10&pageNo=1';
		$refURL   = 'http://10.134.131.112:8000/prpall/business/editRenewalSearch.do';
	    $data     = 'prpCrenewalVo.policyNo=&prpCrenewalVo.othFlag=&prpCrenewalVo.licenseColorCode=&validateCodeInput=';
		$data  .= "&prpCrenewalVo.vinNo=";
		$data  .= "&prpCrenewalVo.licenseNo=";
		//$lo = mb_convert_encoding($info['license_no'],'GB2312','UTF-8');

		if(!empty($info['engine_no']) && !empty($info['vin_no']))
		{
			$data  .= "&prpCrenewalVo.engineNo={$info['engine_no']}&prpCrenewalVo.frameNo={$info['vin_no']}";
			$plate_type = array('');
		}
		elseif(isset($info['license_no']) && mb_strlen($info['license_no'],'UTF8')==7)
		{
			$data  .= mb_convert_encoding($info['license_no'],'GB2312','UTF-8');
			$data  .= "&prpCrenewalVo.engineNo=&prpCrenewalVo.frameNo=";			
		}
		else
		{
			return false;
		}
		$ret = array();
		foreach($plate_type as $pt)
		{
			$tmp = "&prpCrenewalVo.licenseType={$pt}";
			$htmlstr = $this->post($queryURL,$data.$tmp,$refURL,'gzip');

			if(is_string($htmlstr))
			{			
			    $retdata = json_decode($htmlstr,true);
			    if(is_array($retdata) && $retdata['totalRecords'] > 0)
			    {
			    	foreach($retdata['data'] as $row)
			    	{
			    		$policy = array();
			    		$policy['policy_no'] = $row['policyNo'];

			    		$policy['license_no'] = $row['licenseNo'];
						
						if($row['riskCode'] == 'DZA')
			    		{
			    			$policy['kind_type'] = 'mvtalci';
			    		}
			    		else
			    		{
			    			$policy['kind_type'] = 'business';
			    		}
			    		$policy['vin_no'] = $row['frameNo'];
			    		$policy['end_date_timestamp'] = $row['endDate']['time']/1000-1;
						
			    		$policy['engine_no'] = $row['engineNo'];						

						if($row['riskCode'] == 'DZA')
						{
							$policy['last_damaged'] = $row['lastDamagedCI'];
							$policy['no_dam_years'] = $row['noDamYearsCI'];
						}
						else
						{
							$policy['last_damaged'] = $row['lastDamagedBI'];
							$policy['no_dam_years'] = $row['noDamYearsBI'];							
						}
						$policy['insurance_company'] = 'PICC';
			    		$ret[$row['policyNo']] = $policy;
			    	}
					$this->policyList = $ret;
			    	break;
			    }
			}
			else
			{
				$ret = $htmlstr;
			}	
		}
		return $ret;	
	}


	
	/**
	 * 查询其它保险公司保单
	 * 参数:
	 * @info                     必需。查询参数
	 *    license_no             车牌号
     *    vin_no       	         车辆识别号
	 *     
	 * 返回值:失败 false,成功返回数组
	 **/		
	public function queryOtherPolicyList($info = array())
	{
		$info['engine_no'] = 'C';
		$info['license_no'] = '';

		if(empty($info) || empty($info['vin_no'])) return false;
		
		$data = file_get_contents($this->premiumData);
		
		$newParams = array();
		
		$newParams[]= array('name'=>'prpCitemCar.frameNo','value'=>$info['vin_no']);
		$newParams[]= array('name'=>'prpCitemCar.vinNo','value'=>$info['vin_no']);
		
		if(isset($info['license_no']) && mb_strlen($info['license_no'],'UTF8')==7)
		{			
			$newParams[]=array('name'=>'prpCitemCar.licenseNo','value'=>$info['license_no']);
		}
		else
		{
			$newParams[]=array('name'=>'prpCitemCar.licenseNo','value'=>'川AAAAAA');
			
		}
		
		if(!empty($info['engine_no']))
		{
			$newParams[]=array('name'=>'prpCitemCar.engineNo','value'=>$info['engine_no']);
		}	
		else
		{
			$newParams[]=array('name'=>'prpCitemCar.engineNo','value'=>'');
		}
		
		if(!empty($info['model_code']))
		{
			$newParams[]=array('name'=>'prpCitemCar.modelCode','value'=>$info['model_code']);
		}	
		else
		{
			$newParams[]=array('name'=>'prpCitemCar.modelCode','value'=>'');
		}

		if(!empty($info['model']))
		{
			$newParams[]=array('name'=>'prpCitemCar.brandName','value'=>$info['model']);
		}	
		else
		{
			$newParams[]=array('name'=>'prpCitemCar.brandName','value'=>'');
		}		
		
		$cstartDate =  date('Y-m-d',strtotime('1 days'));
		$cendDate = date('Y-m-d',strtotime('1 years'));
		$currDate =  date('Y-m-d');

		$newParams[]=array('name'=>'biStartDate','value'=>$cstartDate);
		$newParams[]=array('name'=>'ciStartDate','value'=>$cstartDate);
		$newParams[]=array('name'=>'ciEndDate','value'=>$cendDate);
		$newParams[]=array('name'=>'currentDate','value'=>$currDate);
		$newParams[]=array('name'=>'operationTimeStamp','value'=>date('Y-m-d H:i:s'));
		$newParams[]=array('name'=>'prpCmain.operateDate','value'=>$currDate);
		$newParams[]=array('name'=>'Today','value'=>$currDate);
		$newParams[]=array('name'=>'OperateDate','value'=>$currDate);
		$newParams[]=array('name'=>'prpCitemCar.enrollDate','value'=>date('Y-m-d',strtotime('-2 years')));
		$newParams[]=array('name'=>'tbSelYear1','value'=>date('Y',strtotime('-2 years')));
        $newParams[]=array('name'=>'tbSelMonth1','value'=>date('m',strtotime('-2 years')));
		$newParams[]=array('name'=>'prpCitemCar.useYears','value'=>'2');
        $newParams[]=array('name'=>'prpCmain.startDate','value'=>$cstartDate);
		$newParams[]=array('name'=>'prpCmain.endDate','value'=>$cendDate);
        $newParams[]=array('name'=>'prpCmainCI.startDate','value'=>$cstartDate);
		$newParams[]=array('name'=>'prpCmainCI.endDate','value'=>$cendDate);
		
		
		/*
		$info['identify_no'] = '513027196308010134';		
		$custom = $this->queryCustomInfo(array('identify_type'=>'01','identify_number'=>$info['identify_no']));		
		if(!is_array($custom)) return $custom;
		if(empty($custom['insuredCode'])) return false;	
		var_dump($custom);
		$newParams[]=array('name'=>'iinsuredCode'                     ,'value'=>$custom['insuredCode']);
		$newParams[]=array('name'=>'iinsuredName'                     ,'value'=>$custom['insuredName']);
		$newParams[]=array('name'=>'iidentifyNumber'                  ,'value'=>$custom['identifyNumber']);
		$newParams[]=array('name'=>'iinsuredAddress'                  ,'value'=>$custom['insuredAddress']);
		
		
		$newParams[]=array('name'=>'prpCinsureds_[0].insuredCode'     ,'value'=>$custom['insuredCode']);
		$newParams[]=array('name'=>'prpCinsureds_[0].insuredName'     ,'value'=>$custom['insuredName']);
		$newParams[]=array('name'=>'prpCinsureds_[0].identifyNumber'  ,'value'=>$custom['identifyNumber']);
		$newParams[]=array('name'=>'prpCinsureds_[0].insuredAddress'  ,'value'=>$custom['insuredAddress']);                                                                     
		$newParams[]=array('name'=>'prpCinsureds_[0].drivingLicenseNo','value'=>$custom['identifyNumber']);
		
		$newParams[]=array('name'=>'prpCinsureds[0].insuredCode'      ,'value'=>$custom['insuredCode']);
		$newParams[]=array('name'=>'prpCinsureds[0].insuredName'      ,'value'=>$custom['insuredName']);
		$newParams[]=array('name'=>'prpCinsureds[0].identifyNumber'   ,'value'=>$custom['identifyNumber']);
		$newParams[]=array('name'=>'prpCinsureds[0].insuredAddress'   ,'value'=>$custom['insuredAddress']);                                                                      
		$newParams[]=array('name'=>'prpCinsureds[0].drivingLicenseNo' ,'value'=>$custom['identifyNumber']);
                                                                     
		$newParams[]=array('name'=>'prpCcarShipTax.taxPayerIdentNo'   ,'value'=>$custom['identifyNumber']);
		$newParams[]=array('name'=>'prpCcarShipTax.taxPayerNumber'    ,'value'=>$custom['identifyNumber']);
		$newParams[]=array('name'=>'prpCcarShipTax.taxPayerCode'      ,'value'=>$custom['insuredCode']);
		$newParams[]=array('name'=>'prpCcarShipTax.taxPayerName'      ,'value'=>$custom['insuredName']);
		*/
		

		$URL = 'http://10.134.131.112:8000/prpall/business/caculatePremiunForFG.do';
		
		foreach($newParams as $idx=>$el)
		{
			$newParams[$idx]['value'] = urlencode(iconv('UTF-8','GBK',$el['value']));
		} 
		$data = extraction_urlparam_replace($data,$newParams);		
		$htmlstr = $this->post($URL,$data,$URL,'gzip');
   

		if(!is_string($htmlstr)) return $htmlstr;		
		$retdata = json_decode($htmlstr,true);
		if(!is_array($retdata)) return false;
		if(array_key_exists('msg',$retdata) && $retdata['totalRecords'] == 0 ) return $retdata['msg'];
		
		if(empty($retdata['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandRepets']))
		{
			return false;			
		}
		
		$srcdata = $retdata['data'][0]['biInsuredemandVoList'][0]['ciInsureDemandRepets'];
		$ret = array();
		
		if(count($srcdata)>0)
		{	
			$pol = array();	
			foreach($srcdata as $pitem)
			{	
				if(empty($pitem['policyNo'])) continue;
				if(empty($pol['policy_no']))
				{
					$pol['policy_no']   = $pitem['policyNo'];
					if(!empty($pitem['carkindCode']) &&
						array_key_exists($pitem['carkindCode'],$this->license_type))
					{
						$pol['license_type'] = $this->license_type[$pitem['carkindCode']];
					}
					else
					{
						$pol['license_type'] = '';
					}
					
					$pol['license_no'] = $pitem['licenseNo'];
					
					if(isset($info['license_no']) && mb_strlen($info['license_no'],'UTF8')==7 
					&& mb_strlen($pitem['licenseNo'],'UTF8')!=7)
					{
						$pol['license_no'] = $info['license_no'];
					}	
					$pol['engine_no']            = $pitem['engineNo'];
					$pol['insurance_company']    = $pitem['insurerCode'];
					$pol['end_date_timestamp']   = $pitem['endDate']['time']/1000-1;
					$pol['start_date_timestamp'] = $pitem['startDate']['time']/1000;
					$pol['vin_no']               = $pitem['vinNo'];
					$pol['kind_type'] = 'business';
				}
				$pol['item'] = array();

				if(!empty($pitem['kindName']) )
				{
					$riskcode = substr($pitem['riskCode'],-3);
					$kind_code =  array_key_exists($riskcode,$this->risk_kind_code)?$this->risk_kind_code[$riskcode]:$riskcode;
					$pol['item'][] = array('kind_code'=>$kind_code,'kind_name'=>$pitem['kindName']);

				}
			}
			$ret[$pol['policy_no']] = $pol;
		}
		
		
		return $ret;
	}

	
	/**
	 * 查询交强险保单信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功返回true
	 **/			
	private function queryCIPolicyInfo($policyNo)
	{
		
		if(empty($policyNo)) return false;
		$URL = 'http://10.134.131.112:8000/prpall/business/browseRenewalPolicy.do?bizNo='.$policyNo;
		$refURL = 'http://10.134.131.112:8000/prpall/business/browseRenewalPolicy.do?bizNo='.$policyNo;

		$htmlstr = $this->get($URL,$refURL,'gzip');
		
		
		if(!is_string($htmlstr)) return $htmlstr;	
		
		if(!array_key_exists($policyNo,$this->policyList))
		{
			$this->policyList[$policyNo] = array();
		}
		$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');
		$inputs = extraction_html_inputs($htmlstr);
		
		if(!$inputs) return false;

		//保额
		if(array_key_exists('prpCitemKindCI.amount',$inputs))
		{
			$this->policyList[$policyNo]['amount'] = floatval($inputs['prpCitemKindCI.amount']['value']);
		}	
        //免赔额
		if(array_key_exists('prpCitemKindCI.deductible',$inputs))
		{
			$this->policyList[$policyNo]['deductible'] = floatval($inputs['prpCitemKindCI.deductible']['value']);
		}			
 
        //调节系数
		if(array_key_exists('prpCitemKindCI.adjustRate',$inputs))
		{
			$this->policyList[$policyNo]['adjust_rate'] = floatval($inputs['prpCitemKindCI.adjustRate']['value']);
		}			
        
        //费率
		if(array_key_exists('prpCitemKindCI.rate',$inputs))
		{
			$this->policyList[$policyNo]['rate'] = floatval($inputs['prpCitemKindCI.rate']['value']);
		}		
        
        //标准保费
		if(array_key_exists('prpCitemKindCI.benchMarkPremium',$inputs))
		{
			$this->policyList[$policyNo]['bench_premium'] = floatval($inputs['prpCitemKindCI.benchMarkPremium']['value']);
		}		
        		
        
        //折扣
		if(array_key_exists('prpCitemKindCI.disCount',$inputs))
		{
			$this->policyList[$policyNo]['discount'] = floatval($inputs['prpCitemKindCI.disCount']['value']);
		}			
        
        //应交保费
		if(array_key_exists('prpCitemKindCI.premium',$inputs))
		{
			$this->policyList[$policyNo]['premium'] = floatval($inputs['prpCitemKindCI.premium']['value']);
		}			

		$selects = extraction_html_selects($htmlstr);
		//浮动标识
		$this->policyList[$policyNo]['rate_rloat_flag'] = '';
		//事故浮动
		$this->policyList[$policyNo]['claim_adjust_reason'] = '';
		//违法浮动
		$this->policyList[$policyNo]['peccancy_adjust_reason'] = '';
		foreach($selects as $sel)
		{
			if($sel['name'] == 'ciInsureDemand.rateRloatFlag' && array_key_exists($sel['value'],$this->rate_rloat_flag))
			{
				$this->policyList[$policyNo]['rate_rloat_flag'] = $this->rate_rloat_flag[$sel['value']] ;
			}
			elseif(preg_match('/^A\d+$/',$sel['value']))
			{
				$this->policyList[$policyNo]['claim_adjust_reason'] = $sel['value'] ;
			}
			elseif(preg_match('/^V\d+$/',$sel['value']))
			{
				$this->policyList[$policyNo]['peccancy_adjust_reason'] = $sel['value'] ;
			}			
		}

		//违法信息列表
		$this->feccs[$policyNo] = array();
		$count = 0;
		while(true)
		{		    
		    $rowtag = 'ciInsureDemandLoss_['.$count.']';
     		if(!array_key_exists($rowtag.'.id.serialNo',$inputs)) break;
			if(empty($inputs[$rowtag.'.id.serialNo']['value'])) break;
      		$fe = array();
			$fe['policy_no'] = $policyNo;
			$fe['serial_no'] = $inputs[$rowtag.'.id.serialNo']['value'];
        	$fe['loss_time'] = $inputs[$rowtag.'.lossTime']['value'];
        	$fe['loss_address'] = $inputs[$rowtag.'.lossDddress']['value'];
        	$fe['loss_action'] = $inputs[$rowtag.'.lossAction']['value'];
        	$fe['coeff'] = $inputs[$rowtag.'.coeff']['value'];
        	$fe['loss_type'] = $inputs[$rowtag.'.lossType']['value'];

			$fe['identify_type'] = $inputs[$rowtag.'.identifyType']['value'];
				
        	$fe['identify_number'] = $inputs[$rowtag.'.identifyNumber']['value'];
        	$fe['loss_accept_date'] = $inputs[$rowtag.'.lossAcceptDate']['value'];
        	$fe['loss_action_desc'] = $inputs[$rowtag.'.lossActionDesc']['value'];	
			$this->feccs[$policyNo][$fe['serial_no']] = $fe;				
		  
		    $count++;
		}
 
 		
		//上年理赔信息列表
		$this->claims[$policyNo] = array();
		$count = 0;
		while(true)
		{		   
		    $rowtag = 'ciInsureDemandPay_['.$count.']';
     		if(!array_key_exists($rowtag.'.id.serialNo',$inputs)) break;
			if(empty($inputs[$rowtag.'.id.serialNo']['value'])) break;
			/*
			$cl = array();
			$cl['policy_no'] = $policyNo;
			$cl['serial_no'] = $inputs[$rowtag.'.id.serialNo']['value'];
			$cl['pay_company'] = $inputs[$rowtag.'.payCompany']['value'];
			$cl['claim_registration_no'] = $inputs[$rowtag.'.claimregistrationno']['value'];
			$cl['compensate_no'] = $inputs[$rowtag.'.compensateNo']['value'];
			$cl['loss_time'] = $inputs[$rowtag.'.lossTime']['value'];
			$cl['end_case_time'] = $inputs[$rowtag.'.endcCaseTime']['value'];
			$cl['loss_fee'] = floatval($inputs[$rowtag.'.lossFee']['value']);
			$cl['pay_type'] = $inputs[$rowtag.'.payType']['value'];
			$cl['personpay_type'] = $inputs[$rowtag.'.personpayType']['value'];
			$this->claims[$policyNo][$cl['serial_no']] = $cl;		
		   */
		    $count++;
		}		
		
				
		//风险警示列表
		$this->risks[$policyNo] = array();
		$count = 0;
		while(true)
		{		   
		    $rowtag = 'ciRiskWarningClaimItems_['.$count.']';
     		if(!array_key_exists($rowtag.'.id.serialNo',$inputs)) break;
			if(empty($inputs[$rowtag.'.id.serialNo']['value'])) break;
       		$ri = array();
			$ri['policy_no'] = $policyNo; 
			$ri['serial_no'] = $inputs[$rowtag.'.id.serialNo']['value'];
        	$ri['risk_type'] = $inputs[$rowtag.'.riskWarningType']['value'];
        	$ri['claim_no'] = $inputs[$rowtag.'.claimSequenceNo']['value'];
        	$ri['insurer_code'] = $inputs[$rowtag.'.insurerCode']['value'];
        	$ri['loss_time'] = $inputs[$rowtag.'.lossTime']['value'];
        	$ri['loss_area'] = $inputs[$rowtag.'.lossArea']['value'];
     		$this->risks[$ri['serial_no']] = $ri;	
		  
		    $count++;
		}				

		$browseURL = 'http://10.134.131.112:8000/prpall/business/browsePolicyNo.do?bizNo='.$policyNo;
		
		$tmpstr = $this->get($browseURL,$browseURL,'gzip');
		
		
		if(is_string($tmpstr))
		{
			
			$tmpstr = mb_convert_encoding($tmpstr,'UTF-8','GB2312');
			$inputs = extraction_html_inputs($tmpstr);			
			
			if($inputs && array_key_exists('prpCmainCI.startDate',$inputs))
			{
				$stdate = $inputs['prpCmainCI.startDate']['value'];				
				$this->policyList[$policyNo]['start_date'] = $stdate.' 00:00:00';
			}	
			
			if($inputs &&  array_key_exists('prpCmainCI.endDate',$inputs));
			{
				$enddate = $inputs['prpCmainCI.endDate']['value'];
				$this->policyList[$policyNo]['end_date'] = $enddate.' 23:59:59';
			}	
	
		}
		else
		{
			return $tmpstr;
		}
		
		return true;
	}

	/**
	 * 添加车辆信息
	 *
	 **/	
	private function addAutoInfo($policyNo,$inputs=array())
	{
		if(empty($policyNo) || empty($inputs)) return false;
		
		$this->autoInfo[$policyNo] = array();		

		$this->autoInfo[$policyNo]['license_no'] = '';
		if(array_key_exists('prpCitemCar.licenseNo',$inputs))
		{
			$this->autoInfo[$policyNo]['license_no'] = $inputs['prpCitemCar.licenseNo']['value'];
		}
		$this->autoInfo[$policyNo]['license_type'] = '';
		if(array_key_exists('prpCitemCar.licenseType',$inputs))
		{
			$lic = trim($inputs['prpCitemCar.licenseType']['value']);			
			if(array_key_exists($lic,$this->license_type))
			{
				$this->autoInfo[$policyNo]['license_type'] = $this->license_type[$lic];
			}	
		}
		
		$this->autoInfo[$policyNo]['license_type_name'] = '';
		if(array_key_exists('LicenseTypeDes',$inputs))
		{
			$this->autoInfo[$policyNo]['license_type_name'] =  $inputs['LicenseTypeDes']['value'];	
		}
		
		$this->autoInfo[$policyNo]['license_color_code'] = '';
		if(array_key_exists('prpCitemCar.licenseColorCode',$inputs))
		{		
			$this->autoInfo[$policyNo]['license_color_code'] =  $inputs['prpCitemCar.licenseColorCode']['value'];	
		}	
		
		$this->autoInfo[$policyNo]['license_color_name'] = '';
		if(array_key_exists('LicenseColorCodeDes',$inputs))
		{		
			$this->autoInfo[$policyNo]['license_color_name'] =  $inputs['LicenseColorCodeDes']['value'];	
		}
		
		$this->autoInfo[$policyNo]['engine_no'] = '';
		if(array_key_exists('prpCitemCar.engineNo',$inputs))
		{				
			$this->autoInfo[$policyNo]['engine_no'] =  $inputs['prpCitemCar.engineNo']['value'];	
		}	

		$this->autoInfo[$policyNo]['vin_no'] = '';
		if(array_key_exists('prpCitemCar.vinNo',$inputs))
		{		
			$this->autoInfo[$policyNo]['vin_no'] =  $inputs['prpCitemCar.vinNo']['value'];
		}
		
		$this->autoInfo[$policyNo]['frame_no'] = '';
		if(array_key_exists('prpCitemCar.frameNo',$inputs))
		{			
			$this->autoInfo[$policyNo]['frame_no'] =  $inputs['prpCitemCar.frameNo']['value'];
		}
		
		$this->autoInfo[$policyNo]['vehicle_type'] = '';
		if(array_key_exists('prpCitemCar.carKindCode',$inputs))
		{	
			if(array_key_exists( $inputs['prpCitemCar.carKindCode']['value'],$this->vehicle_type))
			{
				$this->autoInfo[$policyNo]['vehicle_type'] =  $this->vehicle_type[$inputs['prpCitemCar.carKindCode']['value']];
			}	
		}
		
		$this->autoInfo[$policyNo]['vehicle_type_name'] = '';
		if(array_key_exists('CarKindCodeDes',$inputs))
		{		
			$this->autoInfo[$policyNo]['vehicle_type_name'] =  $inputs['CarKindCodeDes']['value'];
		}

		$this->autoInfo[$policyNo]['enroll_date'] = '';
		if(array_key_exists('prpCitemCar.enrollDate',$inputs))
		{			
			$this->autoInfo[$policyNo]['enroll_date'] =  $inputs['prpCitemCar.enrollDate']['value'];
		}
		
		$this->autoInfo[$policyNo]['model_code'] = '';
		if(array_key_exists('prpCitemCar.modelCode',$inputs))
		{					
			$this->autoInfo[$policyNo]['model_code'] =  $inputs['prpCitemCar.modelCode']['value'];
		}

		$this->autoInfo[$policyNo]['brand_name'] = '';
		if(array_key_exists('prpCitemCar.brandName',$inputs))
		{		
			$this->autoInfo[$policyNo]['brand_name'] =  $inputs['prpCitemCar.brandName']['value'];
		}
		
		$this->autoInfo[$policyNo]['buying_price'] = 0;
		if(array_key_exists('prpCitemCar.purchasePrice',$inputs))
		{		
			$this->autoInfo[$policyNo]['buying_price'] =  floatval($inputs['prpCitemCar.purchasePrice']['value']);
		}
		
		$this->autoInfo[$policyNo]['load_mass']  = 0;
		if(array_key_exists('prpCitemCar.tonCount',$inputs))
		{		
			$this->autoInfo[$policyNo]['load_mass'] =  floatval($inputs['prpCitemCar.tonCount']['value']);
		}	

		$this->autoInfo[$policyNo]['engine'] = 0;
		if(array_key_exists('prpCitemCar.exhaustScale',$inputs))
		{		
			$this->autoInfo[$policyNo]['engine'] =  floatval($inputs['prpCitemCar.exhaustScale']['value'])*1000;
		}

		$this->autoInfo[$policyNo]['seats'] = 0;
		if(array_key_exists('prpCitemCar.seatCount',$inputs))
		{		
			$this->autoInfo[$policyNo]['seats'] =  intval($inputs['prpCitemCar.seatCount']['value']);
		}

		$this->autoInfo[$policyNo]['kerb_mass'] = 0;
		if(array_key_exists('prpCitemCar.carLotEquQuality',$inputs))
		{		
			$this->autoInfo[$policyNo]['kerb_mass'] =  floatval($inputs['prpCitemCar.carLotEquQuality']['value']);
		}
		
		$this->autoInfo[$policyNo]['user_nature_code'] = '';	
		$this->autoInfo[$policyNo]['clause_type'] = '';
		$this->autoInfo[$policyNo]['run_area_code'] = '';
		$this->autoInfo[$policyNo]['origin'] = '';
		$count = 0;
		$selects = $inputs;
		foreach($selects as $sel)
		{
			if($count >= 4) break;
			if(!array_key_exists('name',$sel)) continue;
			if($sel['name'] == 'prpCitemCar.useNatureCode' )
			{
				if(!empty($sel['value']) && array_key_exists($sel['value'],$this->use_character))
				{
					$this->autoInfo[$policyNo]['use_character'] = $this->use_character[$sel['value']];
				}
				$count++;	
			}
			elseif($sel['name'] == 'prpCitemCar.clauseType' )
			{
				if(!empty($sel['value']))
				{
					$this->autoInfo[$policyNo]['clause_type'] = $sel['value'];
				}
				$count++;	
			}
			elseif($sel['name'] == 'prpCitemCar.countryNature' )
			{
				if(!empty($sel['value']) && array_key_exists($sel['value'],$this->origin))
				{
					$this->autoInfo[$policyNo]['origin'] = $this->origin[$sel['value']];
				}
				$count++;	
			}			
			elseif($sel['name'] == 'prpCitemCar.runAreaCode' )
			{
				if(!empty($sel['value']) && array_key_exists($sel['value'],$this->run_area))
				{
					$this->autoInfo[$policyNo]['run_area'] = $this->run_area[$sel['value']];
				}
				$count++;	
			}					
		}
		return true;	
	}

	/**
	 * 增加商业险保单
	 *
	 **/
	private function addBiPolicy($policyNo,$inputs=array())
	{
		if(empty($policyNo) || empty($inputs)) return false;		
		if(!array_key_exists($policyNo,$this->policyList))
		{
			$this->policyList[$policyNo] = array();
		}
		
		//短期 	
		if(array_key_exists('prpCitemKind.shortRate',$inputs))	
		{
			$this->policyList[$policyNo]['short_rate'] = floatval($inputs['prpCitemKind.shortRate']['value']);
		}
		//总标准保费
		if(array_key_exists('sumBenchPremium',$inputs))	
		{
			$this->policyList[$policyNo]['bench_premium'] = floatval($inputs['sumBenchPremium']['value']);
		}		
		//总折扣
		if(array_key_exists('prpCmain.discount',$inputs))	
		{
			$this->policyList[$policyNo]['discount'] = floatval($inputs['prpCmain.discount']['value']);
		}			
		//保单保费
		if(array_key_exists('prpCmain.sumPremium',$inputs))	
		{
			$this->policyList[$policyNo]['premium'] = floatval($inputs['prpCmain.sumPremium']['value']);
		}			
		//保险项目
		$this->policyList[$policyNo]['item'] = array();
		$startDate = '';
		$endDate = '';
		$count = 0;
	
		while(true)
		{	
			$itemtag = 'prpCitemKindsTemp['.$count.']';
			if(!array_key_exists($itemtag.'.chooseFlag',$inputs)) break;
			if(!empty($inputs[$itemtag.'.chooseFlag']['checked']))
			{
				$item = array();
				$item['choose_flag'] = true;
				if(empty($startDate))
				{					
					$elname = $itemtag.'.startDate';
					if(array_key_exists($elname,$inputs))
					{
						$startDate = $inputs[$elname]['value'];
					}
					$elname = $itemtag.'.startHour';
					if(array_key_exists($elname,$inputs))
					{
						$startDate .= ' '.$inputs[$elname]['value'].':00:00';
					}					
				}
				if(empty($endDate))
				{		
					$elname = $itemtag.'.endDate';
					if(array_key_exists($elname,$inputs))
					{
						$endDate = $inputs[$elname]['value'];
					}
					$elname = $itemtag.'.endHour';
					if(array_key_exists($elname,$inputs))
					{
						if($inputs[$elname]['value'] == 24)
						{
							$endDate .= ' '.'23:59:59';
						}
						else
						{
							$endDate .= ' '.$inputs[$elname]['value'].':00:00';
						}
					}					
				}				
				
				if(array_key_exists($itemtag.'.kindCode',$inputs))
				{
					$item['kind_code'] = $inputs[$itemtag.'.kindCode']['value'];
					if(array_key_exists($item['kind_code'],$this->kind_code))
					{
						$item['kind_code'] = $this->kind_code[$item['kind_code']];
					}
				}
				if(array_key_exists($itemtag.'.kindName',$inputs))
				{
					$item['kind_name'] = $inputs[$itemtag.'.kindName']['value'];
				}
				$item['unit_amount'] = 0;	
				if(array_key_exists($itemtag.'.unitAmount',$inputs))
				{
					$item['unit_amount'] = floatval($inputs[$itemtag.'.unitAmount']['value']);
				}	
				$item['quantity'] = 0;
				if(array_key_exists($itemtag.'.quantity',$inputs))
				{
					$item['quantity'] = floatval($inputs[$itemtag.'.quantity']['value']);
				}	
				if(array_key_exists($itemtag.'.amount',$inputs))
				{
					$item['amount'] = floatval($inputs[$itemtag.'.amount']['value']);
				}	
				if(array_key_exists($itemtag.'.rate',$inputs))
				{
					$item['rate'] = floatval($inputs[$itemtag.'.rate']['value']);
				}		
				if(array_key_exists($itemtag.'.benchMarkPremium',$inputs))
				{
					$item['bench_premium'] = floatval($inputs[$itemtag.'.benchMarkPremium']['value']);
					$this->policyList[$policyNo]['bench_premium'] += $item['bench_premium'] ;
				}	
				if(array_key_exists($itemtag.'.disCount',$inputs))
				{
					$item['discount'] = floatval($inputs[$itemtag.'.disCount']['value']);
				}	
				if(array_key_exists($itemtag.'.premium',$inputs))
				{
					$item['premium'] = floatval($inputs[$itemtag.'.premium']['value']);
					$this->policyList[$policyNo]['premium'] += $item['premium'];
				}
				$this->policyList[$policyNo]['item'][] = $item;		
			}
			
			$count++;
		}
		$this->policyList[$policyNo]['start_date'] = $startDate;
		$this->policyList[$policyNo]['end_date'] = $endDate;
		//调节系数
		$this->policyList[$policyNo]['factor'] = array();
		$count = 0;
		while(true)	
		{
			$itemtag = 'prpCprofitFactorsTemp['.$count.']';
			if(!array_key_exists($itemtag.'.chooseFlag',$inputs)) break;
			if(!empty($inputs[$itemtag.'.chooseFlag']['checked']))
			{
				$item = array();
				$item['choose_flag'] = true;
				if(array_key_exists($itemtag.'.profitName',$inputs))
				{
					$item['profit_name'] = $inputs[$itemtag.'.profitName']['value'];
				}
				if(array_key_exists($itemtag.'.condition',$inputs))
				{
					$item['condition'] = $inputs[$itemtag.'.condition']['value'];
				}
				if(array_key_exists($itemtag.'.rate',$inputs))
				{
					$item['rate'] = floatval($inputs[$itemtag.'.rate']['value']);
				}	
				if(array_key_exists($itemtag.'.id.profitCode',$inputs))
				{
					$item['profit_code'] = $inputs[$itemtag.'.id.profitCode']['value'];
				}	
				if(array_key_exists($itemtag.'.id.conditionCode',$inputs))
				{
					$item['condition_code'] = $inputs[$itemtag.'.id.conditionCode']['value'];
				}	

				$this->policyList[$policyNo]['factor'][] = $item;		
			}

			$count++;
		}		
		return true;		
	}
	
	/**
	 * 增加关系人	
	 *
	 **/
	private function addCinsured($policyNo,$inputs=array())
	{
		if(empty($policyNo) || empty($inputs)) return false;		
		if(!array_key_exists($policyNo,$this->cinsured))
		{
			$this->cinsured[$policyNo] = array();
		}
		
		$count = 0;
		while(true)
		{
			$itemtag = 'prpCinsureds['.$count.']';			
			if(!array_key_exists($itemtag.'.insuredFlag',$inputs)) break;
			if(empty($inputs[$itemtag.'.insuredFlag']['value'])) break;
			$item = array();
			$item['insured_flag'] = $inputs[$itemtag.'.insuredFlag']['value'];
			$item['insured_type'] = '';
			if(array_key_exists($itemtag.'.insuredType',$inputs) 
			   && array_key_exists($inputs[$itemtag.'.insuredType']['value'],$this->insured_type))
			{
				$item['insured_type'] = $this->insured_type[$inputs[$itemtag.'.insuredType']['value']];
			}

			if(array_key_exists($itemtag.'.insuredName',$inputs)  )
			{
				$item['insured_name'] = $inputs[$itemtag.'.insuredName']['value'];
			}
			
			if(array_key_exists($itemtag.'.unitType',$inputs))
			{
				$item['unit_type'] = $inputs[$itemtag.'.unitType']['value'];
			}
			$item['identify_type'] = '';
			if(array_key_exists($itemtag.'.identifyType',$inputs) &&
			   array_key_exists($inputs[$itemtag.'.identifyType']['value'],$this->identify_type))
			{
				$item['identify_type'] = $this->identify_type[$inputs[$itemtag.'.identifyType']['value']];
			}
			
			if(array_key_exists($itemtag.'.identifyNumber',$inputs))
			{
				$item['identify_number'] = $inputs[$itemtag.'.identifyNumber']['value'];
			}
			
			if(array_key_exists($itemtag.'.insuredAddress',$inputs))
			{
				$item['insured_address'] = $inputs[$itemtag.'.insuredAddress']['value'];
			}	
			$this->cinsured[$policyNo][] = $item;
			$count++;
		}		
		return true;		
	}
	 
	/**
	 * 查询上年商业险保单信息
	 * 参数:
	 * @licenseNo                 必需。车牌号
	 * 返回值:失败 false,成功返回true
	 **/			
	public function queryLastYearBI($licenseNo)
	{
		if(empty($licenseNo)) return false;
		$lno = mb_convert_encoding($licenseNo,'GB2312','UTF-8');
		//$url = "http://10.134.131.112:8000/prpall/business/selectRenewalPolicyNo.do?licenseNo=\264\250A7K0X9&licenseFlag=1&licenseType=02";
		$refurl = "http://10.134.131.112:8000/prpall/business/prepareEdit.do?bizType=PROPOSAL&editType=NEW";
		$url = "http://10.134.131.112:8000/prpall/business/selectRenewalPolicyNo.do?licenseFlag=1&licenseType=02&licenseNo={$lno}";
		$this->get($refurl,$refurl,'gzip',array('x-requested-with: XMLHttpRequest'));
		$htmlstr = $this->post($url,'',$refurl,'gzip');	
		if(!is_string($htmlstr)) return $htmlstr;
		$ret = json_decode($htmlstr,true);
		if(!is_array($ret)) return;
		if($ret['totalRecords']>0)
		{
			$pno = $ret['data'][0]['policyNo'];
			$refurl = "http://10.134.131.112:8000/prpall/menu/showMenu.do?systemCode=prpall&userCode=A519400237";
			$url = "http://10.134.131.112:8000/prpall/business/editRenewalCopy.do?bizNo={$pno}";
			$htmlstr = $this->get($url,$refurl,'gzip');
			$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');		
			$inputs = extraction_html_inputs($htmlstr);
			
			$sdate = '';
			if(is_array($inputs) && array_key_exists('biStartDate',$inputs))
			{
				$sdate = $inputs['biStartDate']['value'];
			}
			$edate = '';
			if(is_array($inputs) && array_key_exists('ciEndDate',$inputs))
			{
				$edate = $inputs['ciEndDate']['value'];
			}
			$shour = '0';
			if(is_array($inputs) &&  array_key_exists('ciStartHour',$inputs) && !empty($inputs['ciStartHour']['value']))
			{
				$shour = $inputs['ciStartHour']['value'];
			}
			$ehour = '';
			if(is_array($inputs) &&  array_key_exists('ciEndHour',$inputs))
			{
				$ehour = $inputs['ciEndHour']['value'];
			}
			$refurl = $url;
			$operdate = date('Y-m-d');
			$rnd826 = date('D M d H:i:s e O Y');
			$url  = "http://10.134.131.112:8000/prpall/business/editCitemCar.do?editType=RENEWAL";
			$url .= "&bizType=PROPOSAL&bizNo={$pno}&riskCode=DAA&applyNo=&startDate={$sdate}";
			$url .= "&endDate={$edate}&startHour={$shour}&endHour={$ehour}&endorType=&taskID_Ppms=&prpallLinkPpmsFlag=";
			$url .= "&operateDate={$operdate}&motorFastTrack=&operatorProjectCode=&reload=&rnd826={$rnd826}";
			$htmlstr = $this->get($url,$refurl,'gzip',array('x-requested-with: XMLHttpRequest'));
			$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');		
			$inputs = extraction_html_inputs($htmlstr);
			$selects = extraction_html_selects($htmlstr);
			if(is_array($inputs) && is_array($selects) )
				$this->addAutoInfo($pno,array_merge($inputs,$selects));			
			
			$url  = "http://10.134.131.112:8000/prpall/business/editCitemKind.do?editType=RENEWAL";
			$url .= "&bizType=PROPOSAL&bizNo={$pno}&riskCode=DAA&applyNo=&startDate={$sdate}";
			$url .= "&endDate={$edate}&startHour={$shour}&endHour={$ehour}&endorType=&taskID_Ppms=&prpallLinkPpmsFlag=";
			$url .= "&operateDate={$operdate}&motorFastTrack=&operatorProjectCode=&reload=&rnd454={$rnd826}";
			$htmlstr = $this->get($url,$refurl,'gzip');
			$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');		
			$inputs = extraction_html_inputs($htmlstr);
			$this->addBiPolicy($pno,$inputs);
			
			$url  = "http://10.134.131.112:8000/prpall/business/editCinsured.do?editType=RENEWAL";
			$url .= "&bizType=PROPOSAL&bizNo={$pno}&riskCode=DAA&applyNo=&startDate={$sdate}";
			$url .= "&endDate={$edate}&startHour={$shour}&endHour={$ehour}&endorType=&taskID_Ppms=&prpallLinkPpmsFlag=";
			$url .= "&operateDate={$operdate}&motorFastTrack=&operatorProjectCode=&reload=&rnd826={$rnd826}";
			$htmlstr = $this->get($url,$refurl,'gzip');
			$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');		
			$inputs = extraction_html_inputs($htmlstr);
			$this->addCinsured($pno,$inputs);
			if(array_key_exists($pno,$this->policyList))
				return $this->policyList[$pno];
		}	
		return false;
	}
	
	

	
	/**
	 * 查询商业险保单信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功返回true
	 **/			
	private function queryBIPolicyInfo($policyNo)
	{
		if(empty($policyNo)) return false;
		$URL = 'http://10.134.131.112:8000/prpall/business/browseRenewalPolicy.do?bizNo='.$policyNo;
		$refURL = 'http://10.134.131.112:8000/prpall/business/browsePolicyNo.do?bizNo='.$policyNo;
		
		$htmlstr = $this->get($URL,$URL,'gzip');	
		
		if(!is_string($htmlstr)) return $htmlstr;
		
		if(!array_key_exists($policyNo,$this->policyList))
		{
			$this->policyList[$policyNo] = array();
		}

		$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');
		
		$inputs = extraction_html_inputs($htmlstr);
		
		if(!$inputs) return false;	
		

		
		//短期 	
		if(array_key_exists('prpCitemKind.shortRate',$inputs))	
		{
			$this->policyList[$policyNo]['short_rate'] = floatval($inputs['prpCitemKind.shortRate']['value']);
		}
		//总标准保费
		if(array_key_exists('sumBenchPremium',$inputs))	
		{
			$this->policyList[$policyNo]['bench_premium'] = floatval($inputs['sumBenchPremium']['value']);
		}		
		//总折扣
		if(array_key_exists('prpCmain.discount',$inputs))	
		{
			$this->policyList[$policyNo]['discount'] = floatval($inputs['prpCmain.discount']['value']);
		}			
		//保单保费
		if(array_key_exists('prpCmain.sumPremium',$inputs))	
		{
			$this->policyList[$policyNo]['premium'] = floatval($inputs['prpCmain.sumPremium']['value']);
		}			
		//保险项目
		$this->policyList[$policyNo]['item'] = array();
		$startDate = '';
		$endDate = '';
		$count = 0;
	
		while(true)
		{	
			$itemtag = 'prpCitemKindsTemp['.$count.']';
			if(!array_key_exists($itemtag.'.chooseFlag',$inputs)) break;
			if(!empty($inputs[$itemtag.'.chooseFlag']['checked']))
			{
				$item = array();
				$item['choose_flag'] = true;
				if(empty($startDate))
				{					
					$elname = $itemtag.'.startDate';
					if(array_key_exists($elname,$inputs))
					{
						$startDate = $inputs[$elname]['value'];
					}
					$elname = $itemtag.'.startHour';
					if(array_key_exists($elname,$inputs))
					{
						$startDate .= ' '.$inputs[$elname]['value'].':00:00';
					}					
				}
				if(empty($endDate))
				{		
					$elname = $itemtag.'.endDate';
					if(array_key_exists($elname,$inputs))
					{
						$endDate = $inputs[$elname]['value'];
					}
					$elname = $itemtag.'.endHour';
					if(array_key_exists($elname,$inputs))
					{
						if($inputs[$elname]['value'] == 24)
						{
							$endDate .= ' '.'23:59:59';
						}
						else
						{
							$endDate .= ' '.$inputs[$elname]['value'].':00:00';
						}
					}					
				}				
				
				if(array_key_exists($itemtag.'.kindCode',$inputs))
				{
					$item['kind_code'] = $inputs[$itemtag.'.kindCode']['value'];
					if(array_key_exists($item['kind_code'],$this->kind_code))
					{
						$item['kind_code'] = $this->kind_code[$item['kind_code']];
					}
				}
				if(array_key_exists($itemtag.'.kindName',$inputs))
				{
					$item['kind_name'] = $inputs[$itemtag.'.kindName']['value'];
				}
				$item['unit_amount'] = 0;	
				if(array_key_exists($itemtag.'.unitAmount',$inputs))
				{
					$item['unit_amount'] = floatval($inputs[$itemtag.'.unitAmount']['value']);
				}	
				$item['quantity'] = 0;
				if(array_key_exists($itemtag.'.quantity',$inputs))
				{
					$item['quantity'] = floatval($inputs[$itemtag.'.quantity']['value']);
				}	
				if(array_key_exists($itemtag.'.amount',$inputs))
				{
					$item['amount'] = floatval($inputs[$itemtag.'.amount']['value']);
				}	
				if(array_key_exists($itemtag.'.rate',$inputs))
				{
					$item['rate'] = floatval($inputs[$itemtag.'.rate']['value']);
				}		
				if(array_key_exists($itemtag.'.benchMarkPremium',$inputs))
				{
					$item['bench_premium'] = floatval($inputs[$itemtag.'.benchMarkPremium']['value']);
				}	
				if(array_key_exists($itemtag.'.disCount',$inputs))
				{
					$item['discount'] = floatval($inputs[$itemtag.'.disCount']['value']);
				}	
				if(array_key_exists($itemtag.'.premium',$inputs))
				{
					$item['premium'] = floatval($inputs[$itemtag.'.premium']['value']);
				}
				$this->policyList[$policyNo]['item'][] = $item;		
			}
			
			$count++;
		}
		$this->policyList[$policyNo]['start_date'] = $startDate;
		$this->policyList[$policyNo]['end_date'] = $endDate;
		//调节系数
		$this->policyList[$policyNo]['factor'] = array();
		$count = 0;
		while(true)	
		{
			$itemtag = 'prpCprofitFactorsTemp['.$count.']';
			if(!array_key_exists($itemtag.'.chooseFlag',$inputs)) break;
			if(!empty($inputs[$itemtag.'.chooseFlag']['checked']))
			{
				$item = array();
				$item['choose_flag'] = true;
				if(array_key_exists($itemtag.'.profitName',$inputs))
				{
					$item['profit_name'] = $inputs[$itemtag.'.profitName']['value'];
				}
				if(array_key_exists($itemtag.'.condition',$inputs))
				{
					$item['condition'] = $inputs[$itemtag.'.condition']['value'];
				}
				if(array_key_exists($itemtag.'.rate',$inputs))
				{
					$item['rate'] = floatval($inputs[$itemtag.'.rate']['value']);
				}	
				if(array_key_exists($itemtag.'.id.profitCode',$inputs))
				{
					$item['profit_code'] = $inputs[$itemtag.'.id.profitCode']['value'];
				}	
				if(array_key_exists($itemtag.'.id.conditionCode',$inputs))
				{
					$item['condition_code'] = $inputs[$itemtag.'.id.conditionCode']['value'];
				}	

				$this->policyList[$policyNo]['factor'][] = $item;		
			}

			$count++;
		}		

		//上年理赔列表信息
		$this->claims[$policyNo] = array();
		$count = 0;
		while(true)
		{
			$itemtag = 'BiInsureDemandPays['.$count.']';
			$elname = $itemtag.'.id.serialNo';
			if(!array_key_exists($elname,$inputs)) break;
			if(empty($inputs[$elname]['value'])) break;
			/*
			$item = array();
			$item['policy_no'] = $policyNo;			
			$item['serial_no']= $inputs[$elname]['value'];
			
			$elname = $itemtag.'.payCompany';
			if(array_key_exists($elname,$inputs))
			{
				$item['pay_company'] = $inputs[$elname]['value'];
			}	
			
			$elname = $itemtag.'.claimregistrationno';
			if(array_key_exists($elname,$inputs))
			{
				$item['claim_registration_no']= $inputs[$elname]['value'];
			}
			
			$elname = $itemtag.'.compensateNo';
			if(array_key_exists($elname,$inputs))
			{
				$item['compensate_no']= $inputs[$elname]['value'];
			}	
			
			$elname = $itemtag.'.lossTime';
			if(array_key_exists($elname,$inputs))
			{
				$item['loss_time']= $inputs[$elname]['value'];
			}	
			
			$elname = $itemtag.'.endcCaseTime';
			if(array_key_exists($elname,$inputs))
			{
				$item['end_case_time']= $inputs[$elname]['value'];
			}	
			
			$elname = $itemtag.'.lossFee';
			if(array_key_exists($elname,$inputs))
			{
				$item['loss_fee']= floatval($inputs[$elname]['value']);
			}
			
			$elname = $itemtag.'.payType';
			if(array_key_exists($elname,$inputs))
			{
				$item['pay_type']= $inputs[$elname]['value'];
			}		

			$elname = $itemtag.'.personpayType';
			if(array_key_exists($elname,$inputs))
			{
				$item['personpay_type']= $inputs[$elname]['value'];
			}				
				
            $this->claims[$policyNo][$item['serial_no']] = $item;	
			*/	
			$count++;
		}
		
		$this->policyList[$policyNo]['claims'] = $count;
		return true;
	}	
	
	/**
	 * 查询车辆信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功返回true
	 **/			
	private function queryAutoInfo($policyNo)
	{
		if(empty($policyNo)) return false;

		$URL = "http://10.134.131.112:8000/prpall/business/editRenewalCopy.do?bizNo=".$policyNo;
		$htmlstr = $this->get($URL,$URL,'gzip');
		$inputs = extraction_html_inputs($htmlstr);
				
		$sdate = '';
		if(is_array($inputs) && array_key_exists('biStartDate',$inputs))
		{
			$sdate = $inputs['biStartDate']['value'];
		}
		$edate = '';
		if(is_array($inputs) && array_key_exists('ciEndDate',$inputs))
		{
			$edate = $inputs['ciEndDate']['value'];
		}
		$shour = '0';
		if(is_array($inputs) &&  array_key_exists('ciStartHour',$inputs) && !empty($inputs['ciStartHour']['value']))
		{
			$shour = $inputs['ciStartHour']['value'];
		}
		$ehour = '';
		if(is_array($inputs) &&  array_key_exists('ciEndHour',$inputs))
		{
			$ehour = $inputs['ciEndHour']['value'];
		}
		//$refurl = $url;
		$operdate = date('Y-m-d');
		$rnd587 = date('D M d H:i:s e O Y');
		$url  = "http://10.134.131.112:8000/prpall/business/editCitemCar.do?editType=RENEWAL";
		$url .= "&bizType=PROPOSAL&bizNo={$policyNo}&riskCode=DAA&applyNo=&startDate={$sdate}";
		$url .= "&endDate={$edate}&startHour={$shour}&endHour={$ehour}&endorType=&taskID_Ppms=&prpallLinkPpmsFlag=";
		$url .= "&operateDate={$operdate}&motorFastTrack=&operatorProjectCode=&reload=&rnd587={$rnd587}";
		$htmlstr = $this->get($url,$URL,'gzip',array('x-requested-with: XMLHttpRequest'));
		if(!is_string($htmlstr)) return $htmlstr;
		
		$this->autoInfo[$policyNo] = array();
		
		
		$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');

		$inputs = extraction_html_inputs($htmlstr);
		if(!$inputs) return false;
		$selects = extraction_html_selects($htmlstr);
		if(!$selects) return false;
		
		$this->autoInfo[$policyNo]['license_no'] = '';
		if(array_key_exists('prpCitemCar.licenseNo',$inputs))
		{
			$this->autoInfo[$policyNo]['license_no'] = $inputs['prpCitemCar.licenseNo']['value'];
		}
		$this->autoInfo[$policyNo]['license_type'] = '';
		if(array_key_exists('prpCitemCar.licenseType',$inputs))
		{
			$lic = trim($inputs['prpCitemCar.licenseType']['value']);			
			if(array_key_exists($lic,$this->license_type))
			{
				$this->autoInfo[$policyNo]['license_type'] = $this->license_type[$lic];
			}	
		}
		
		$this->autoInfo[$policyNo]['license_type_name'] = '';
		if(array_key_exists('LicenseTypeDes',$inputs))
		{
			$this->autoInfo[$policyNo]['license_type_name'] =  $inputs['LicenseTypeDes']['value'];	
		}
		
		$this->autoInfo[$policyNo]['license_color_code'] = '';
		if(array_key_exists('prpCitemCar.licenseColorCode',$inputs))
		{		
			$this->autoInfo[$policyNo]['license_color_code'] =  $inputs['prpCitemCar.licenseColorCode']['value'];	
		}	
		
		$this->autoInfo[$policyNo]['license_color_name'] = '';
		if(array_key_exists('LicenseColorCodeDes',$inputs))
		{		
			$this->autoInfo[$policyNo]['license_color_name'] =  $inputs['LicenseColorCodeDes']['value'];	
		}
		
		$this->autoInfo[$policyNo]['engine_no'] = '';
		if(array_key_exists('prpCitemCar.engineNo',$inputs))
		{				
			$this->autoInfo[$policyNo]['engine_no'] =  $inputs['prpCitemCar.engineNo']['value'];	
		}	

		$this->autoInfo[$policyNo]['vin_no'] = '';
		if(array_key_exists('prpCitemCar.vinNo',$inputs))
		{		
			$this->autoInfo[$policyNo]['vin_no'] =  $inputs['prpCitemCar.vinNo']['value'];
		}
		
		$this->autoInfo[$policyNo]['frame_no'] = '';
		if(array_key_exists('prpCitemCar.frameNo',$inputs))
		{			
			$this->autoInfo[$policyNo]['frame_no'] =  $inputs['prpCitemCar.frameNo']['value'];
		}
		
		$this->autoInfo[$policyNo]['vehicle_type'] = '';
		if(array_key_exists('prpCitemCar.carKindCode',$inputs))
		{	
			if(array_key_exists( $inputs['prpCitemCar.carKindCode']['value'],$this->vehicle_type))
			{
				$this->autoInfo[$policyNo]['vehicle_type'] =  $this->vehicle_type[$inputs['prpCitemCar.carKindCode']['value']];
			}	
		}
		
		$this->autoInfo[$policyNo]['vehicle_type_name'] = '';
		if(array_key_exists('CarKindCodeDes',$inputs))
		{		
			$this->autoInfo[$policyNo]['vehicle_type_name'] =  $inputs['CarKindCodeDes']['value'];
		}

		$this->autoInfo[$policyNo]['enroll_date'] = '';
		if(array_key_exists('prpCitemCar.enrollDate',$inputs))
		{			
			$this->autoInfo[$policyNo]['enroll_date'] =  $inputs['prpCitemCar.enrollDate']['value'];
		}
		
		$this->autoInfo[$policyNo]['model_code'] = '';
		if(array_key_exists('prpCitemCar.modelCode',$inputs))
		{					
			$this->autoInfo[$policyNo]['model_code'] =  $inputs['prpCitemCar.modelCode']['value'];
		}

		$this->autoInfo[$policyNo]['brand_name'] = '';
		if(array_key_exists('prpCitemCar.brandName',$inputs))
		{		
			$this->autoInfo[$policyNo]['brand_name'] =  $inputs['prpCitemCar.brandName']['value'];
		}
		
		$this->autoInfo[$policyNo]['buying_price'] = 0;
		if(array_key_exists('prpCitemCar.purchasePrice',$inputs))
		{		
			$this->autoInfo[$policyNo]['buying_price'] =  floatval($inputs['prpCitemCar.purchasePrice']['value']);
		}
		
		$this->autoInfo[$policyNo]['load_mass']  = 0;
		if(array_key_exists('prpCitemCar.tonCount',$inputs))
		{		
			$this->autoInfo[$policyNo]['load_mass'] =  floatval($inputs['prpCitemCar.tonCount']['value']);
		}	

		$this->autoInfo[$policyNo]['engine'] = 0;
		if(array_key_exists('prpCitemCar.exhaustScale',$inputs))
		{		
			$this->autoInfo[$policyNo]['engine'] =  floatval($inputs['prpCitemCar.exhaustScale']['value'])*1000;
		}

		$this->autoInfo[$policyNo]['seats'] = 0;
		if(array_key_exists('prpCitemCar.seatCount',$inputs))
		{		
			$this->autoInfo[$policyNo]['seats'] =  intval($inputs['prpCitemCar.seatCount']['value']);
		}

		$this->autoInfo[$policyNo]['kerb_mass'] = 0;
		if(array_key_exists('prpCitemCar.carLotEquQuality',$inputs))
		{		
			$this->autoInfo[$policyNo]['kerb_mass'] =  floatval($inputs['prpCitemCar.carLotEquQuality']['value']);
		}
		
		$this->autoInfo[$policyNo]['user_nature_code'] = '';	
		$this->autoInfo[$policyNo]['clause_type'] = '';
		$this->autoInfo[$policyNo]['run_area_code'] = '';
		$this->autoInfo[$policyNo]['origin'] = '';
		$count = 0;
		foreach($selects as $sel)
		{
			if($count >= 4) break;
			if($sel['name'] == 'prpCitemCar.useNatureCode' )
			{
				if(!empty($sel['value']) && array_key_exists($sel['value'],$this->use_character))
				{
					$this->autoInfo[$policyNo]['use_character'] = $this->use_character[$sel['value']];
				}
				$count++;	
			}
			elseif($sel['name'] == 'prpCitemCar.clauseType' )
			{
				if(!empty($sel['value']))
				{
					$this->autoInfo[$policyNo]['clause_type'] = $sel['value'];
				}
				$count++;	
			}
			elseif($sel['name'] == 'prpCitemCar.countryNature' )
			{
				if(!empty($sel['value']) && array_key_exists($sel['value'],$this->origin))
				{
					$this->autoInfo[$policyNo]['origin'] = $this->origin[$sel['value']];
				}
				$count++;	
			}			
			elseif($sel['name'] == 'prpCitemCar.runAreaCode' )
			{
				if(!empty($sel['value']) && array_key_exists($sel['value'],$this->run_area))
				{
					$this->autoInfo[$policyNo]['run_area'] = $this->run_area[$sel['value']];
				}
				$count++;	
			}					
		}
		
		return true;
		
	}	
	
	/**
	 * 查询关系人信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功true
	 **/		
	private function queryCinsuredInfo($policyNo)
	{
		$this->cinsured[$policyNo] = array();
		return true;
		
		if(empty($policyNo)) return false;

		$URL = 'http://10.134.131.112:8000/prpall/business/showCinsured.do?editType=SHOW_POLICY&bizType=POLICY&bizNo='.$policyNo;
		$refURL = 'http://10.134.131.112:8000/prpall/business/browsePolicyNo.do?bizNo='.$policyNo;
		
		$htmlstr = $this->get($URL,$refURL,'gzip');

		
		if(!is_string($htmlstr)) return $htmlstr;

		$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');
		
		$inputs = extraction_html_inputs($htmlstr);

		if(!$inputs) return false;
		$this->cinsured[$policyNo] = array();
		$count = 0;
		while(true)
		{
			$itemtag = 'prpCinsureds['.$count.']';			
			if(!array_key_exists($itemtag.'.insuredFlag',$inputs)) break;
			if(empty($inputs[$itemtag.'.insuredFlag']['value'])) break;
			$item = array();
			$item['insured_flag'] = $inputs[$itemtag.'.insuredFlag']['value'];
			$item['insured_type'] = '';
			if(array_key_exists($itemtag.'.insuredType',$inputs) 
			   && array_key_exists($inputs[$itemtag.'.insuredType']['value'],$this->insured_type))
			{
				$item['insured_type'] = $this->insured_type[$inputs[$itemtag.'.insuredType']['value']];
			}

			if(array_key_exists($itemtag.'.insuredName',$inputs)  )
			{
				$item['insured_name'] = $inputs[$itemtag.'.insuredName']['value'];
			}
			
			if(array_key_exists($itemtag.'.unitType',$inputs))
			{
				$item['unit_type'] = $inputs[$itemtag.'.unitType']['value'];
			}
			$item['identify_type'] = '';
			if(array_key_exists($itemtag.'.identifyType',$inputs) &&
			   array_key_exists($inputs[$itemtag.'.identifyType']['value'],$this->identify_type))
			{
				$item['identify_type'] = $this->identify_type[$inputs[$itemtag.'.identifyType']['value']];
			}
			
			if(array_key_exists($itemtag.'.identifyNumber',$inputs))
			{
				$item['identify_number'] = $inputs[$itemtag.'.identifyNumber']['value'];
			}
			
			if(array_key_exists($itemtag.'.insuredAddress',$inputs))
			{
				$item['insured_address'] = $inputs[$itemtag.'.insuredAddress']['value'];
			}	
			$this->cinsured[$policyNo][] = $item;
			$count++;
		}		
		
		
		return true;
		
	}		

	/**
	 * 查询保单当期理赔信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功返回数组
	 **/		
	private function queryPolicyClaimInfo($policyNo)
	{
		if(empty($policyNo)) return false;

		$URL = 'http://10.134.131.112:8000/prpall/business/queryClaimsMsg.do?bizNo='.$policyNo;
		$refURL = 'http://10.134.131.112:8000/prpall/business/browsePolicyNo.do?bizNo='.$policyNo;
		
		$htmlstr = $this->get($URL,$refURL,'gzip');
				
		if(!is_string($htmlstr)) return $htmlstr;

		$htmlstr = mb_convert_encoding($htmlstr,'UTF-8','GB2312');
		
		$inputs = extraction_html_inputs($htmlstr);
		if(!$inputs) return false;
		$this->claims[$policyNo] = array();
		$count = 0;
		while(true)
		{
			$itemtag = 'caseInfoVoX['.$count.']';
			$elname = $itemtag.'.registNo';
			if(!array_key_exists($elname,$inputs)) break;
			if(empty($inputs[$elname]['value'])) break;
			
			$item = array();
			$item['policy_no'] = $policyNo;			
			$item['serial_no']= $count;

			$item['claim_registration_no'] = $inputs[$elname]['value'];
			
			$elname = $itemtag.'.claimNo';
			if(array_key_exists($elname,$inputs))
			{
				$item['compensate_no']= $inputs[$elname]['value'];
			}	
			
			$elname = $itemtag.'.damageDate';
			if(array_key_exists($elname,$inputs))
			{
				$item['loss_time']= $inputs[$elname]['value'];
			}	
			
			$elname = $itemtag.'.endCaseDate';
			if(array_key_exists($elname,$inputs))
			{
				$item['end_case_time']= $inputs[$elname]['value'];
			}	
			
			
			$elname = $itemtag.'.sumEstiPaid';
			if(array_key_exists($elname,$inputs))
			{
				$item['liability_indemnity']= floatval($inputs[$elname]['value']);
			}
			
			$elname = $itemtag.'.sumPaid';
			if(array_key_exists($elname,$inputs))
			{
				$item['loss_fee']= floatval($inputs[$elname]['value']);
			}
			
			$elname = $itemtag.'.address';
			if(array_key_exists($elname,$inputs))
			{
				$item['address']= $inputs[$elname]['value'];
			}		
			$item['pay_type'] = '';

			$item['personpay_type'] = '';
			$item['pay_company'] = '';
			$item['vin_no'] = '';    
			$item['license_no'] = '';
			$count++;	
            $this->claims[$policyNo][] = $item;	
		
		}
			
		return true;
	}
	
	/**
	 * 返回保单信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功返回数组
	 **/		
	public function getPolicyInfo($policyNo,$otherInfo=null)
	{
		if(empty($policyNo)) return false;
		if(!array_key_exists($policyNo,$this->policyList))
		{
			if(substr($policyNo,0,4) == 'PDZA')
			{
				$this->queryCIPolicyInfo($policyNo);
			}
			else
			{
				$this->queryBIPolicyInfo($policyNo);
			}		
			
		}
		if(!array_key_exists($policyNo,$this->policyList)) return false;
		$ret = true;
		if(!array_key_exists('premium',$this->policyList[$policyNo]) || empty($this->policyList[$policyNo]['premium']))
		{
			if($this->policyList[$policyNo]['kind_type'] == 'mvtalci')
			{
				$ret = $this->queryCIPolicyInfo($policyNo);
				
			}
			else
			{
				$ret = $this->queryBIPolicyInfo($policyNo);
				
				
			}
		}
		if($ret === -1) return -1;
		if(!$ret) return false;
		
		
		$ret = true;
		if(!array_key_exists($policyNo,$this->cinsured))
		{
			$ret = $this->queryCinsuredInfo($policyNo);
			
		}
		if($ret === -1) return -1;
		if(!$ret) return false;		
		
		if(!array_key_exists('policy_holder',$this->policyList[$policyNo]))
		{
			$comp = 0;
			foreach($this->cinsured[$policyNo] as $ci)
			{
				if(preg_match('/^1\d+/',$ci['insured_flag']))
				{
					$this->policyList[$policyNo]['policy_holder']  =  $ci['insured_name'];
					$this->policyList[$policyNo]['policy_holder_id_type'] = $ci['identify_type'];
					$this->policyList[$policyNo]['policy_holder_id_number'] = $ci['identify_number'];
					$comp++;
				}
				if(preg_match('/^\d1\d+/',$ci['insured_flag']))
				{
					$this->policyList[$policyNo]['insurant']  =  $ci['insured_name'];
					$this->policyList[$policyNo]['insurant_id_type'] = $ci['identify_type'];
					$this->policyList[$policyNo]['insurant_id_number'] = $ci['identify_number'];
					$comp++;
				}
				if($comp>=2) break;	
			}	
		}    
		
		return 	$this->policyList[$policyNo];
		
	}
	


	/**
	 * 返回车辆信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功返回数组
	 **/		
	public function getAutoInfo($policyNo,$otherInfo=null)
	{
		$ret = true;
		if(!array_key_exists($policyNo,$this->autoInfo))
		{
			$ret = $this->queryAutoInfo($policyNo);
		
			
		}
		if($ret < 0) return -1;
		if(!$ret) return false;
		$ret = true;
		if(!array_key_exists($policyNo,$this->cinsured))
		{
			$ret = $this->queryCinsuredInfo($policyNo);
			
		}	
		if($ret < 0) return -1;
		if(!$ret) return false;		
		
		if(!array_key_exists('owner',$this->autoInfo[$policyNo]))
		{
			foreach($this->cinsured[$policyNo] as $ci)
			{
				if(preg_match('/^\d\d1\d+/',$ci['insured_flag']))
				{
					$this->autoInfo[$policyNo]['owner']  =  $ci['insured_name'];
					$this->autoInfo[$policyNo]['identify_type'] = $ci['identify_type'];
					$this->autoInfo[$policyNo]['identify_no'] = $ci['identify_number'];
					$this->autoInfo[$policyNo]['contact']  =  $ci['insured_name'];
					break;
				}
			}	
			
		}
		return $this->autoInfo[$policyNo];
	}		

	/**
	 * 返回关系人信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功返回数组
	 **/		
	public function getCinsuredInfo($policyNo,$otherInfo=null)
	{
		$ret = true;
		if(!array_key_exists($policyNo,$this->cinsured))
		{
			$ret = $this->queryCinsuredInfo($policyNo);
			
		}	
		if($ret === -1) return -1;
		if(!$ret) return false;			
		return $this->cinsured[$policyNo];
	}		
	
	/**
	 * 返回理赔信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * 返回值:失败 false,成功返回数组
	 **/		
	public function getClaimsInfo($policyNo,$otherInfo=null)
	{
		return false ;
		
		$ret = $this->queryPolicyClaimInfo($policyNo);
		if($ret === -1) return -1;
		if(!$ret) return false;

		$ret = true;
		if(!array_key_exists($policyNo,$this->autoInfo))
		{
			$ret = $this->queryAutoInfo($policyNo);
		}
		if($ret === -1) return -1;
		if(array_key_exists($policyNo,$this->autoInfo))
		{
			foreach($this->claims[$policyNo] as $key => $cl)
			{
				$this->claims[$policyNo][$key]['vin_no']     = $this->autoInfo[$policyNo]['vin_no'];
				$this->claims[$policyNo][$key]['license_no'] = $this->autoInfo[$policyNo]['license_no'];
			}	
		}
		
		return $this->claims[$policyNo];
	}		
	
	/**
	 * 查询车型信息
	 * 参数:
	 * @info                     必需。查询参数
	 *    model                  品牌型号
     *    model_code       	     型号代码
	 *     
	 * 返回值:失败 false,成功返回数组
	 **/		
	public function queryVehicle($info)
	{
		$URL = 'http://10.134.131.112:8000/prpall/vehicle/vehicleQuery.do?pageSize=50&pageNo=1';

		if(empty($this->comCode))
		{
			$postdata = file_get_contents($this->premiumData);
			preg_match('/comCode=(\d+)&/',$postdata,$out);
			if(!empty($out[1])) $this->comCode = $out[1];			
		}
		if(empty($this->comCode)) return false;
		$model_code = '';
		$model = '';
		if(!empty($info['model_code']))
		{
			$model_code = $info['model_code'];
		}	
		elseif(!empty($info['model']))
		{
			$model = str_replace( "TOYOTA", " ", $info['model']);
			if(preg_match('/[A-Z]{2,3}\d{4}[A-Z0-9]*/',$model,$out))
			{
				$model = $out[0];
			}
		}
		$model = urlencode(iconv('UTF-8','GBK',$model));
		if(empty($model_code) && empty($model)) return false;
		$data = "pageNo_=1&riskCode=DAA&totalRecords_=&pageSize_=50&taxFlag=0&";
		$data .= "comCode={$this->comCode}&";
		$data .= "pm_vehicle_switch=&carShipTaxPlatFormFlag=&TCVehicleVO.searchCode=&";
		$data .= "TCVehicleVO.vehicleAlias=&TCVehicleVO.vehicleId={$model_code}&TCVehicleVO.brandId=&TCVehicleVO.brandName=&";
		$data .= "TCVehicleVO.vehicleName={$model}&";
		$data .= "brandName=&pageSizeSelect=50&quotationFlag=";		
		$htmlstr = $this->post($URL,$data,$URL,'gzip');
		$ret = json_decode($htmlstr,true);
		if(is_array($ret) && array_key_exists('totalRecords',$ret) && $ret['totalRecords']>0 && array_key_exists('data',$ret))
		{
			return $ret['data'];
		}
		return false;
	}
	
	/**
	 * 查询车型信息
	 * 参数:
	 * @info                     必需。查询参数
	 *    model                  品牌型号
     *    model_code       	     型号代码
	 *     
	 * 返回值:失败 false,成功返回数组
	 **/
	public function queryVehicleByVIN($vin)
	{
		if(empty($vin)) return false;
		$URL = 'http://10.134.131.112:8000//prpall/business/queryVehicleByPrefillVIN.do?licenseNo=\264\250&enrollDate=1900-01-01';
		$URL .= "&vinNo={$vin}";
		$htmlstr = $this->post($URL,'',$URL,'gzip');
		$ret = json_decode($htmlstr,true);
		if(!is_array($ret) || !array_key_exists('msg',$ret) || empty($ret['msg']) ) return false;
		$codes = explode(',',$ret['msg']);
		$ret = array();
		foreach($codes as $code)
		{
			$veh = $this->queryVehicle(array('model_code'=>$code));
			if(is_array($veh)) $ret = array_merge($ret,$veh);
		}
		return $ret;		
	}
	
	/**
	 * 查询客户信息
	 * 参数:
	 * @idno                 必需。身份证号码
	 * 返回值:失败 false,成功返回数组
	 **/
	public function queryCustom($idno)
	{		
		if(empty($idno)) return false;
		$URL = "http://10.134.131.112:8000/prpall/custom/customAmountQueryP.do?_identifyType=01&_insuredName=&_identifyNumber={$idno}&_insuredCode=";
		$ref = "http://10.134.131.112:8000/prpall/business/prepareEdit.do?bizType=PROPOSAL&editType=NEW";
		$htmlstr = $this->get($URL,$ref,'gzip');
		$ret = json_decode($htmlstr,true);
		if(!is_array($ret) || !array_key_exists('data',$ret)) return false;	
		return $ret['data'];
	}	
	 
}
?>