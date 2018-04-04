<?php

/**
 * 项目:           锦泰车险保费在线计算接口
 * 文件名:         JingTai_PC.class.php
 * 版权所有：      成都启点科技有限公司.
 * 作者：          LiangYuLin
 * 版本：          1.0.0
 *
 * 锦泰保险财险业务平台算价接口
 *
 **/

class JingTai_PC
{

	const formFile = 'Calculate.tpl';
	private $error = "";//设置错误信息成员属性
	private $setItems = array(
		'username' => '锦泰帐号',
		'password' => '密码',
	);


//（号牌类型）     01 大型 02小型 03使馆04领馆 05境外 06外籍 07两，三轮摩托车 08轻便 09使馆摩托车 10领馆摩托车 11境外摩托车 12 外籍摩托车 13 农用运输车 14拖拉机 15 挂车 16 教练汽车  17教练摩托车 18试验汽车 19试验摩托车 20临时入境汽车 21临时入境摩托车 22临时行驶车 80警用汽车 81 警用摩托车 82 公安民用 83 武警 84 军队
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

		$this->UrlLogin="http://pcis.jtxt.ejintai.com/cbs_auto_policy/j_spring_security_check";//登陆处理查询
		$this->RepeatinSuranceUrl="http://pcis.jtxt.ejintai.com/cbs_auto_policy/iaQuery0326.do?operate=undefined?time=".time();
	    $this->UrlOffterUrl="http://pcis.jtxt.ejintai.com/cbs_auto_policy/calculate0326.do?operate=&time=".time();//查询保费

	    //查询车船税
	    $this->carurl="http://pcis.jtxt.ejintai.com/cbs_auto_policy/queryVhlTaxInfo.do";

		$this->loginAarray=array("j_username"=>$user,"j_password" =>$password );//登陆条件
		$this->vehiclePricerice="http://pcis.jtxt.ejintai.com/cbs_auto_policy/queryVhl.do";//查询购置价
		if(empty($cachePath))
		{
			$this->cookie_file = dirname(__FILE__).'/jingtai_cookie.txt';
		}
		else
		{
			$this->cookie_file = $cachePath.'/jingtai_cookie.txt';  //COOKIE文件存放地址
		}
		$this->InsuranceUrl="http://pcis.jtxt.ejintai.com/cbs_auto_policy/iaQuery0320.do?operate=&time=".time();//查询交强险保费
		$this->checkUrl="http://pcis.jtxt.ejintai.com/cbs_auto_policy/verifyCode.do?rdn=".time();
		$this->checkdateUrl="http://pcis.jtxt.ejintai.com/cbs_auto_policy/validateCodeCompare.do";

	}


	/**
	 * [getSetItems 获取设置项目]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T12:15:43+0800
	 * @return    [type]                   [description]
	 */
	public function getSetItems()
	{
		return $this->setItems;
	}
	/**
	 * [getFormFile 获取表单模板文件名]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T12:15:31+0800
	 * @return    [type]                   [description]
	 */
	public function getFormFile()
	{
		return self::formFile;
	}
	/**
	 * [getLastError 返回最后一次错误说明]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T12:15:11+0800
	 * @return    [type]                   [description]
	 */
	public function getLastError()
	{

		return $this->error;

	}
	/**
	 * [requestPostData 封装POST提交（带登陆）]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T12:15:50+0800
	 * @param     [type]                   $url  [请求URL]
	 * @param     [type]                   $post [请求参数]
	 * @param     boolean                  $head [请求头部]
	 * @param     integer                  $foll [description]
	 * @param     boolean                  $ref  [description]
	 * @return    [type]                         [成功返回数组，失败返回false]
	 */
	private function requestPostData($url,$post,$head=false,$foll=1,$ref=false)
	{

		$ret = $this->post($url,$post,$head,$foll,$ref);
		preg_match('/<span.*>(.*)<\/span>/isU',$ret,$arr);

		if(is_array($arr) && count($arr)>1 && strrchr($arr[1],'跳转'))
		{
			$ret = $this->post($this->UrlLogin,$this->loginAarray);
			$ret = $this->post($url,$post,$head,$foll,$ref);
		}
		

			
		return $ret;

	}
	/**
	 * [requestGetData 封装GET提交（带登陆）]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T12:16:33+0800
	 * @param     [type]                   $url  [请求URL]
	 * @param     boolean                  $head [请求参数]
	 * @param     integer                  $foll [请求头部]
	 * @param     boolean                  $ref  [description]
	 * @return    [type]                         [成功返回数组，失败返回false]
	 */
	private function requestGetData($url,$head=false,$foll=1,$ref=false)
	{
		$ret = $this->get($url,$head,$foll,$ref);
		preg_match('/<span.*>(.*)<\/span>/isU',$ret,$arr);

		if(is_array($arr) && count($arr)>1 && strrchr($arr[1],'跳转'))
		{
			$ret = $this->post($this->UrlLogin,$this->loginAarray);
			$ret = $this->get($url,$head,$foll,$ref);
		}

		return $ret;
	}
	/**
	 * [post description]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T12:17:16+0800
	 * @param     [type]                   $url  [请求URL]
	 * @param     [type]                   $post [请求参数]
	 * @param     boolean                  $head [请求头部]
	 * @param     integer                  $foll [description]
	 * @param     boolean                  $ref  [description]
	 * @return    [type]                         [成功返回数组，失败返回false]
	 */
	private function post($url,$post,$head=false,$foll=1,$ref=false)
	{
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
	    curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_file); // 存放Cookie信息的文件名称
	    curl_setopt($curl, CURLOPT_COOKIEFILE,$this->cookie_file); // 读取上面所储存的Cookie信息
	    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');//解释gzip
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环

		if($foll==1){
			curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		}else{
			curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		}
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		curl_setopt($curl,CURLINFO_HEADER_OUT,1);
	    $tmpInfo = curl_exec($curl); // 执行操作
	    $curlInfo = curl_getinfo($curl);
	    if(stripos($curlInfo['url'],'http://pcis.jtxt.ejintai.com/cbs_auto_policy/logon') === false)
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
	   	if(empty($tmpInfo)) return -1;
		$curlInfo = curl_getinfo($curl);

		if(stripos($curlInfo['url'],'http://pcis.jtxt.ejintai.com/cbs_auto_policy/logon') === false)
		{
			return $tmpInfo;
		}
		//没有登录
	    return -1;
	}


   	/**
   	 * [premium 请求人保查价]
   	 * @AuthorHTL
   	 * @DateTime  2016-05-26T16:15:24+0800
   	 * @param     array                    $auto     [参数数组]
   	 * @param     array                    $business [参数数组]
   	 * @param     array                    $mvtalci  [参数数组]
   	 * @return    [type]                             [成功返回数组，失败返回false]
   	 */
	public  function  premium($auto=array(),$business=array(),$mvtalci=array())
	{

		/******修复如果没有传递车损折扣，导致计算不出价格**************/
		
		if($business['POLICY']['TVDI_INSURANCE_AMOUNT']=="" || !isset($business['POLICY']['TVDI_INSURANCE_AMOUNT']))
		{
				$info['BUYING_PRICE']=$auto['BUYING_PRICE'];
				$info['ENROLL_DATE']=$auto['ENROLL_DATE'];
				$info['BUSINESS_START_TIME']=$business['BUSINESS_START_TIME'];
				$info['USE_CHARACTER']=$auto['USE_CHARACTER'];
				$info['VEHICLE_TYPE']=$auto['VEHICLE_TYPE'];
				$business['POLICY']['TVDI_INSURANCE_AMOUNT']= $this->depreciation($info);
		}
		$match=array();
		$arr=array();
		$remg='|value="(.*)"|isU';
		$textarea = '%<textarea.*?>(.*?)</textarea>%si';
		//查询交强险保费
		$requertresult=$this->requestPostData($this->InsuranceUrl,$this->InsuranceData($auto,$business,$mvtalci));
		preg_match_all($remg,$requertresult,$requert_results);
		preg_match($textarea, $requertresult, $matches);
		if($matches[1]=="")
		{
			file_put_contents($this->cookie_file,'');
			$requertresult=$this->requestPostData($this->InsuranceUrl,$this->InsuranceData($auto,$business,$mvtalci));
			preg_match_all($remg,$requertresult,$requert_results);
		    preg_match($textarea, $requertresult, $matches);
		   	if($matches[1]=="")
		   	{
		   		$errors['errorMsg']="网络故障，请稍后在重试！";
	    		$this->error=$errors;
	    		return false;
		   	}
		}
		
		$data= $this->repeat_insurance($auto,$business,$mvtalci);
		$ret=$this->requestPostData($this->RepeatinSuranceUrl,$data);//查询商业选重复投保

		$UrlOffterdata= $this->datas($auto,$business,$mvtalci);
	    preg_match($textarea,$ret,$match);
	    preg_match_all($remg,$ret,$result);
	    if(isset($result[1]))//商业险系数表
	    {
	    	$UrlOffterdata['appDtoList[0].appCvrgList[0].nBasePrm']=round($result[1][356],2);//算价基本保费
			$UrlOffterdata["appDtoList[0].appCvrgList[0].nPureRiskPremium"]=round($result[1][356],2);
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.nAgoClmRec']=$result[1][832];
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.nAgoClmRecPlat']=$result[1][832];
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.cAgoClmRecNdisCnm']=$result[1][835];
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.nTraffIrr']="1.0";
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.cTrafIrrNdiscRsnCnm']="";//交通违法记录
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.nHannelAdjustValue']=$result[1][832];//自主渠道系数
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.nAutomyAdjustValue']=$result[1][832];//自主核保系数
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.nAccidentCount']=$result[1][841];
			$UrlOffterdata['appDtoList[0].appPrmCoefDTO.nAccidentAmt']=$result[1][842];

	    }

				$Postdata=$this->requestPostData($this->UrlOffterUrl,$UrlOffterdata);//查询保费
				preg_match_all($remg,$Postdata,$arr);
	    		if($matches[1]!="" && strstr($matches[1], "计算失败"))
	    		{
	    			$errors['errorMsg']=$matches[1];
	    			$this->error=$errors;
	    			return false;
	    		}

							    	$results = array();
									$results['MVTALCI']['MVTALCI_PREMIUM']   = '0.00';
									$results['MVTALCI']['MVTALCI_DISCOUNT']  = '1.000';
									$results['MVTALCI']['MVTALCI_START_TIME']= '';
									$results['MVTALCI']['MVTALCI_END_TIME']  = '';

				if($requert_results[1][994]!="")
								{
                             		    /*******************交强险********************/
                             		$results['MVTALCI_MESSAGE'] = $matches[1];//交强险重复投保信息
                             		$cardas=$this->cardata($auto);//查询车船税
                             		if(!$cardas)
                             		{
                             			$results['MVTALCI']['TRAVEL_TAX_PREMIUM']="0.00";
                             		}
                             		else
                             		{
										 //preg_match_all('/\d+/',$cardas[1][75],$travel);
										 //$join = join('',$travel[0]);
                             			 $results['MVTALCI']['TRAVEL_TAX_PREMIUM']=round(str_replace('&nbsp;','',$cardas[1][75]));
                             			 
                             		}
									        //车船税
									$results['MVTALCI']['MVTALCI_PREMIUM']   = $requert_results[1][994];//$resen['ciPremium'];           //交强险保费
									$results['MVTALCI']['MVTALCI_DISCOUNT']  = $requert_results[1][943]+1;//$resen['ciDiscount'];          //交强险折扣
									$results['MVTALCI']['MVTALCI_START_TIME']= $mvtalci['MVTALCI_START_TIME'];     //交强险生效时间
									$results['MVTALCI']['MVTALCI_END_TIME']  = $mvtalci['MVTALCI_END_TIME'];
                                 }


                 				/*******************商业险********************/
                                 $results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']= "0.00";//商业险扣后保费合计
                                 $results['BUSINESS']['BUSINESS_DISCOUNT']="0.00";//$resen['biDiscount'];         //商业险折扣
                                 $results['BUSINESS']['BUSINESS_PREMIUM']="0.00";//	$resen['biPremium'];          //商业险标准保费合计
                                 $results['BUSINESS']['BUSINESS_START_TIME'] = "";       //商业险生效时间
                                 $results['BUSINESS']['BUSINESS_END_TIME'] = "";//商业险结束时间
								 $results['MESSAGE']   = '';////商业险重复投保信息
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
		                         $results['BUSINESS']['BUSINESS_ITEMS']['CIDI']['PREMIUM']                 = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['SRDI']['PREMIUM']                 = '0.00';
								 $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['PREMIUM']                 = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']            = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']           = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']         = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']    = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM'] = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']            = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']            = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']           = '0.00';
		                         $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']           = '0.00';
					if($arr[1][870]!="")
					{

                                 $results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']= round($arr[1][870]/$arr[1][855],2);//round($resen['biPremium']*$resen['biDiscount'],2); //商业险扣后保费合计
                                 $results['BUSINESS']['BUSINESS_DISCOUNT']= $arr[1][855];//$resen['biDiscount'];         //商业险折扣
                                 $results['BUSINESS']['BUSINESS_PREMIUM']=  $arr[1][870];//$resen['biPremium'];          //商业险标准保费合计
                                 $results['BUSINESS']['BUSINESS_START_TIME'] = $business['BUSINESS_START_TIME'];       //商业险生效时间
                                 $results['BUSINESS']['BUSINESS_END_TIME'] = date('Y-m-d H:i:s',strtotime('+1 years -1 seconds',strtotime($business['BUSINESS_START_TIME'])));//商业险结束时间
                                 $results['MESSAGE']   		   = "商业险正常报价信息：".trim($arr[1][835]).",出险".$arr[1][841]."次<br>".trim($match[1])."<br>本年车船税金额:".$cardas[1][69].",合计金额:".$cardas[1][75];
                                 $results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']                 = $arr[1][338];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']              = $arr[1][437];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']                = $arr[1][366];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']         = $arr[1][396];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']      = $arr[1][416];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']                 = $arr[1][456];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']                 = $arr[1][480];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']                = $arr[1][518];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']                = $arr[1][537];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']                 = $arr[1][499];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']                = $arr[1][575];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['CIDI']['PREMIUM']                 = $arr[1][609];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['SRDI']['PREMIUM']                 = $arr[1][627];
								 $results['BUSINESS']['BUSINESS_ITEMS']['LIDI']['PREMIUM']                 = $arr[1][592];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']            = $arr[1][643];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']           = $arr[1][659];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']         = $arr[1][707];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']    = $arr[1][675];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM'] = $arr[1][691];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']            = $arr[1][739];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']            = $arr[1][723];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']           = $arr[1][755];
		                         $results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']           = $arr[1][787];

					}
		                         return $results;

	}

	/**
	 * [depreciation 车辆折旧价计算]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T16:15:54+0800
	 * @param     [type]                   $info [参数数组]
	 * @return    [type]                         [成功返回数组，失败返回false]
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


	}

    /**
     * [deviceDepreciation 设备折旧价计算]
     * @AuthorHTL
     * @DateTime  2016-05-26T16:16:19+0800
     * @param     array                    $info [参数数组]
     * @return    [type]                         [成功返回数组，失败返回false]
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
      * [queryBuyingPrice 购置价查询]
      * @AuthorHTL
      * @DateTime  2016-05-26T16:16:42+0800
      * @param     array                    $info [参数数组]
      * @return    [type]                         [成功返回数组，失败返回false]
      */
	public function queryBuyingPrice($info=array())
	{
		$veryfirl = '*';
		if(!empty($info['model']))
		{			
			if(preg_match('/([a-zA-Z0-9]{10})\s*$/',$info['model'],$out)>0)
			{
				$model = $out[1];
			}
			else
			{
				$model = str_replace('牌','',$info['model']);
			}
		}		
		$page = 1;
		if(!empty($info['page']))
		{
			$page = $info['page'];
		}
		$rows = 10;
		$data["pageSize"]="10";//每页显示数量
		$data["pageNo"]=$page;//当前第几页
		$data["count"]="3";
		$data["maxPage"]="1";
		$data["vehicleAlias"]=$model;
		$data["cProdNoGroup"]="A330";

		$arr = $this->requestPostData($this->vehiclePricerice,$data);
		$array = json_decode($arr,true);
		if(is_array($array) && array_key_exists('result',$array) && $array['count'] >0)
		{
			$retdata = array('total'=>ceil($array['count']/$rows),'page'=>$array['pageNo'],'records'=>$array['count'],'rows'=>array());
			foreach($array['result'] as $row)
			{
				$line = array();
				$line['vehicleId']             = $row['vehicleId'];//车型代码
				$line['vehicleName']           = $row['vehicleName'];//车型名称
				$line['vehicleAlias']          = $row['vehicleAlias'];//车型别名
				$line['vehicleDisplacement']   = $row['vehicleExhaust'];//排量
				$line['vehiclePrice']          = $row['vehiclePrice'];//不含税购置价
				$line['szxhTaxedPrice']        = $row['vehiclePriceTax'];//含税购置价
				$line['vehicleSeat']           = $row['vehicleSeat'];//核载人数
				$line['vehicleYear']           = $row['marketaDate'];//出厂日期
				$line['vehicleWeight']		   = $row['qualityMax'];//整备质量
				$retdata['rows'][] = $line;
			}
			return $retdata;
		}
		return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());

	}

	/**
	 * [repeat_insurance 商业险重复投保查询]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T16:18:14+0800
	 * @param     array                    $auto     [参数数组]
	 * @param     array                    $business [参数数组]
	 * @param     array                    $mvtalci  [参数数组]
	 * @return    [type]                             [成功返回数组，失败返回false]
	 */
		//商业险重复投保查询

	private function repeat_insurance($auto=array(),$business=array(),$mvtalci=array())
	{
$data["c_undr_opn"]="";
$data["c_undr_mrk"]="";
$data["btnAttachment"]="影像信息";
$data["btnAppSave"]="暂存";
$data["appDtoList[0].appBaseDTO.cAppNo"]="";
$data["appDtoList[1].appBaseDTO.cAppNo"]="";
$data["appDtoList[0].appBaseDTO.cRenewalFlag"]="";
$data["appDtoList[1].appBaseDTO.cRenewalFlag"]="";
$data["appDtoList[0].appBaseDTO.checkCodeIa"]="";
$data["appDtoList[1].appBaseDTO.checkCodeIa"]="";
$data["appDtoList[0].appBaseDTO.checkCode"]="";
$data["appDtoList[1].appBaseDTO.checkCode"]="";
$data["appDtoList[0].appBaseDTO.cProdNoGroup"]="A330";
$data["appDtoList[0].appBaseDTO.cAppTyp"]="A";
$data["appDtoList[0].appBaseDTO.cSecondLevDptCde"]="0201";
$data["startTime"]="";
$data["appDtoList[0].appBaseDTO.cCarAppNo"]="";
$data["appDtoList[0].appBaseDTO.cPlyNo"]="";
$data["appDtoList[0].appBaseDTO.nCalcPrm"]="";
$data["appDtoList[1].appBaseDTO.cPlyNo"]="";
$data["appDtoList[1].appBaseDTO.nCalcPrm"]="";
$data["appDtoList[0].appBaseDTO.channelCode"]="025002";
$data["appDtoList[0].appBaseDTO.cDptCde"]="0201010119";
$data["appDtoList[0].appBaseDTO.cAgtAgrNo"]="U000201010030";
$data["appDtoList[0].appBaseDTO.cBrkrCde"]="WI047";
$data["appDtoList[0].appBaseDTO.cBsnsTyp"]="027009";
$data["appDtoList[0].appBaseDTO.cSlsCde"]="02002188";
$data["appDtoList[0].appBaseDTO.cSlsId"]="";
$data["appDtoList[0].appBaseDTO.cSlsNme"]="王欢";
$data["appDtoList[0].appBaseDTO.cBatchCode"]="0002666";
$data["appDtoList[0].appBaseDTO.cOprCde"]="02001963";
$data["appDtoList[0].appBaseDTO.tAppTm"]=date('Y-m-d H:i:s',time());
$data["appDtoList[0].appBaseDTO.cAgriMrk"]="0";
$data["appDtoList[0].appBaseDTO.cOrgCde"]="02010101";
$data["applicantDTO.cAppCode"]="";
$data["applicantDTO.cAppNme"]=$auto['OWNER'];
$data["applicantDTO.cClntMrk"]="1";
$data["applicantDTO.cCertfCls"]="10";
$data["applicantDTO.cCertfCde"]="";
$data["applicantDTO.cCusLvl"]="";
$data["applicantDTO.cClntCls"]="1";
$data["applicantDTO.cCntrNme"]="";
$data["applicantDTO.cMobile"]="";
$data["applicantDTO.cTel"]="";
$data["appDtoList[0].appBaseDTO.cRepFactoryCde"]="";
$data["applicantDTO.cEmail"]="";
$data["applicantDTO.cClntAddr"]="同投保人";
$data["appInsuredDTO.cInsuredCde"]="";
$data["appInsuredDTO.cInsuredNme"]=$auto['OWNER'];
$data["appInsuredDTO.cClntMrk"]="1";
$data["appInsuredDTO.cCertfCls"]="";
$data["appInsuredDTO.cCertfCde"]="";
$data["appInsuredDTO.cCusLvl"]="";
$data["appInsuredDTO.cClntCls"]="1";
$data["appInsuredDTO.cCntrNme"]="";
$data["appInsuredDTO.cTel"]="";
$data["appInsuredDTO.cMobile"]="";
$data["appInsuredDTO.cEmail"]="";
$data["appInsuredDTO.cClntAddr"]="同投保人";
$data["appVhlOwnerDTO.cOwnerCde"]="";
$data["appVhlOwnerDTO.cOwnerNme"]=$auto['OWNER'];
$data["appVhlOwnerDTO.cCertfCls"]="";
$data["appVhlOwnerDTO.cCertfCde"]="";
$data["appVhlOwnerDTO.cOwnerCls"]="1";
$data["appVhlDTO.cVhlRelCde"]="01";
$data["appVhlDTO.cNewMrk"]="0";
$data["appVhlDTO.cPlateNo"]=$auto['LICENSE_NO'];
$data["appVhlDTO.cEngNo"]=$auto['ENGINE_NO'];
$data["appVhlDTO.cFrmNo"]=$auto['VIN_NO'];
$data["appVhlDTO.cFstRegYm"]=$auto['ENROLL_DATE'];
$data["appVhlDTO.vhlAge"]=date("Y",time())-date("Y",strtotime($auto['ENROLL_DATE']));
$data["appVhlDTO.cModelNme.select"]="";
$data["appVhlDTO.cModelNme"]=$auto['MODEL'];
$data["appVhlDTO.cCurModelNme"]=$auto['MODEL'];
$data["appVhlDTO.cModelCde"]=$auto['MODEL_CODE'];
$data["appVhlDTO.cPlatModelCde"]="";//$auto['MODEL_CODE'];
$data["appVhlDTO.nNewPurchaseValue"]=$auto['BUYING_PRICE'];
$data["appVhlDTO.balancePrice"]="0.00";
$data["appVhlDTO.nRealityPurchaseValue"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
$data["appVhlDTO.nConsultRealityPurchaseValue"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];

/*********使用性质*********/
// switch ($auto['USE_CHARACTER'])
// {
// 	case 'NON_OPERATING_PRIVATE':
// 	case 'NON_OPERATING_ENTERPRISE':
// 	case 'NON_OPERATING_AUTHORITY':
// 	case 'NON_OPERATING_LOW_SPEED_TRUCK':
// 	case 'NON_OPERATING_TRUCK':
// 		$data["appVhlDTO.cVhlCateGoryCde"]="3A0002";
// 		break;
// 	case 'OPERATING_LEASE_RENTAL':
// 	case 'OPERATING_CITY_BUS':
// 	case 'OPERATING_HIGHWAY_BUS':
// 	case 'OPERATING_TRUCK':
// 	case 'OPERATING_TRAILER':
// 	case 'OPERATING_LOW_SPEED_TRUCK':
// 		$data["appVhlDTO.cVhlCateGoryCde"]="3A0001";
// 		break;
// }
// /****非营业***/
// if($auto['USE_CHARACTER']=="NON_OPERATING_PRIVATE")
// {
// $data["appVhlDTO.cUsageCde"]="364001";
// if($auto['SEATS']<6)
// 	{
// 		$data["appVhlDTO.cVhlTyp"]="365001";
// 	}
// 	else if($auto['SEATS']>=6 || $auto['SEATS']<=10)
// 	{
// 		$data["appVhlDTO.cVhlTyp"]="365002";
// 	}
// 	else if($auto['SEATS']>=10 || $auto['SEATS']<=20)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365004";
// 	}
// 	else if($auto['SEATS']>=20 || $auto['SEATS']<=36)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365011";
// 	}
// 	else if($auto['SEATS']>36)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365012";
// 	}
// }
// else if($auto['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE")
// {
// $data["appVhlDTO.cUsageCde"]="364002";
// }
// else if($auto['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
// {
// $data["appVhlDTO.cUsageCde"]="364003";
// }
// else if($auto['USE_CHARACTER']=="NON_OPERATING_TRUCK")//非营业货车
// {
// $data["appVhlDTO.cUsageCde"]="364004";
// if($auto['TONNAGE']<2)
// 	{
// 		$data["appVhlDTO.cVhlTyp"]="365006";
// 	}
// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
// 	{
// 		$data["appVhlDTO.cVhlTyp"]="365007";
// 	}
// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365008";
// 	}
// 	else if($auto['TONNAGE']>=10)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365009";
// 	}
// }

// /*****营业****/

// if($auto['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
// {
// $data["appVhlDTO.cUsageCde"]="364005";
// }
// else if($auto['USE_CHARACTER']=="OPERATING_CITY_BUS")
// {
// $data["appVhlDTO.cUsageCde"]="364006";
// }
// else if($auto['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
// {
// $data["appVhlDTO.cUsageCde"]="364007";
// }
// else if($auto['USE_CHARACTER']=="OPERATING_TRUCK")/*****营业货车*****/
// {
// $data["appVhlDTO.cUsageCde"]="364008";

// if($auto['TONNAGE']<2)
// 	{
// 		$data["appVhlDTO.cVhlTyp"]="365006";
// 	}
// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
// 	{
// 		$data["appVhlDTO.cVhlTyp"]="365007";
// 	}
// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365008";
// 	}
// 	else if($auto['TONNAGE']>=10)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365009";
// 	}
// }

// /****营业低速货车*****/
// if($auto['USE_CHARACTER']=="OPERATING_LOW_SPEED_TRUCK")
// {
// $data["appVhlDTO.cVhlTyp"]="365010";
// }
// /***营业挂车****/
// if($auto['USE_CHARACTER']=="OPERATING_TRAILER")
// {
// 	if($auto['TONNAGE']<2)
// 	{
// 		$data["appVhlDTO.cVhlTyp"]="365025";
// 	}
// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
// 	{
// 		$data["appVhlDTO.cVhlTyp"]="365026";
// 	}
// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365027";
// 	}
// 	else if($auto['TONNAGE']>=10)
// 	{
// 	$data["appVhlDTO.cVhlTyp"]="365028";
// 	}

// }

// if($auto['SEATS']<6)
// {
// $data["appVhlDTO.cVhlTyp"]="365001";
// }
// else if($auto['SEATS']>=6 && $auto['SEATS']<=10)
// {
// $data["appVhlDTO.cVhlTyp"]="365002";
// }
// else if($auto['SEATS']>=10 && $auto['SEATS']<=20)
// {
// $data["appVhlDTO.cVhlTyp"]="365004";
// }
// else if($auto['SEATS']>=20 && $auto['SEATS']<=36)
// {
// $data["appVhlDTO.cVhlTyp"]="365011";
// }
// else if($auto['SEATS']>=36)
// {
// $data["appVhlDTO.cVhlTyp"]="365012";
// }





// //$data["appVhlDTO.cVhlTyp"]="365001";   //车辆类型（精友） 365001: 6座以下  365002:6-10座  365004:10-20座   365011: 20－36座  365012:36座以上
// $data["appVhlDTO.nSeatNum"]=$auto['SEATS']; //座位数

// foreach($this->license_type as $k=>$v){
// 					if($k==$auto['LICENSE_TYPE'])
// 					{
// 						$data["appVhlDTO.cPlateTyp"]=$v[0];// 号牌种类
// 					}
// 			}
$data["appVhlDTO.cVhlCateGoryCde"]="3A0002";
        /****非营业***/
$data["appVhlDTO.cUsageCde"]="364001";
$data["appVhlDTO.cVhlTyp"]="365001";
$data["appVhlDTO.nSeatNum"]=$auto['SEATS']; //座位数     
$data["appVhlDTO.cPlateTyp"]="02";// 号牌种类

//产地代码转换
if($auto['ORIGIN']=="DOMESTIC")
{
$data["appVhlDTO.cProdPlace"]="0";
}
else if($auto['ORIGIN']=="IMPORTED")
{
$data["appVhlDTO.cProdPlace"]="1";
}
else if($auto['ORIGIN']=="JOINT_VENTURE")
{
$data["appVhlDTO.cProdPlace"]="2";
}

$data["appVhlDTO.cProdPlace"]="0";//进口/国产  0国产 1进口 2合资
$data["appVhlDTO.cRegVhlTyp.text"]="";


$data["appVhlDTO.cRegVhlTyp"]="K33";//$auto['clauseType'];//"K33";//交管车辆类型


$data["appVhlDTO.nTonage"]=$auto['TONNAGE'];//吨位
$data["appVhlDTO.nDisplacement"]=$auto['ENGINE'];//排量
$data["appVhlDTO.cLoanVehicleFlag"]="0";//车贷多年投保标志 0否 1是
$data["appVhlDTO.cChgOwnerFlag"]="0";//过户车辆标志 0否 1是
$data["appVhlDTO.tTransferDate"]="";
$data["appVhlDTO.cEcdemicMrk"]="0";//是否外地车  0 否 1是

$data["appDtoList[0].appBaseDTO.cProdNo"]="0330";
$data["appDtoList[0].appBaseDTO.tInsrncBgnTm"]=$business['BUSINESS_START_TIME'];
$data["appDtoList[0].appBaseDTO.tInsrncEndTm"]=$business['BUSINESS_END_TIME'];
$data["appDtoList[0].appBaseDTO.cRatioTyp"]="2";
$data["appDtoList[0].appBaseDTO.nRatioCoef"]="1.000000";
$data["appDtoList[0].appBaseDTO.cRiFacMrk"]="0";
$data["appDtoList[0].appBaseDTO.cRenewMrk"]="0";
$data["appDtoList[0].appBaseDTO.cOrigPlyNo"]="";



$data["appDtoList[0].appCvrgList[0].cCvrgNo"]="030006";
$data["appDtoList[0].appCvrgList[0].nCalcPrm"]="";
$data["appDtoList[0].appCvrgList[0].nCalcAnnPrm"]="";
$data["appDtoList[0].appCvrgList[0].nPrmVar"]="";
$data["appDtoList[0].appCvrgList[0].nBefEdrPrm"]="";
$data["appDtoList[0].appCvrgList[0].nAmtVar"]="";
$data["appDtoList[0].appCvrgList[0].cPureRiskPremiumFlag"]="";
$data["appDtoList[0].appCvrgList[0].nPureRiskPremium"]="";
$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="";
$data["appDtoList[0].appCvrgList[0].nPreferential"]="";
$data["appDtoList[0].appCvrgList[0].noFranchise"]="369003";
$data["appDtoList[0].appCvrgList[0].nAmt"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
$data["appDtoList[0].appCvrgList[0].nRate"]="";
$data["appDtoList[0].appCvrgList[0].nBasePrm"]="";
$data["appDtoList[0].appCvrgList[0].nBefPrm"]="";
$data["appDtoList[0].appCvrgList[0].nDisCoef"]="";
$data["appDtoList[0].appCvrgList[0].nPrm"]="";
$data["appDtoList[0].appCvrgList[0].nDductPrm"]="";
$data["appDtoList[0].appCvrgList[0].nDductCalcPrm"]="";
$data["appDtoList[0].appCvrgList[0].tBgnTm"]=$business['BUSINESS_START_TIME'];
$data["appDtoList[0].appCvrgList[0].tEndTm"]=$business['BUSINESS_END_TIME'];



$data["appDtoList[0].appPrmCoefDTO.cAppointAreaCode"]="";
$data["appDtoList[0].appPrmCoefDTO.cAgoClmRec"]="";
$data["appDtoList[0].appPrmCoefDTO.nAgoClmRec"]="";
$data["appDtoList[0].appPrmCoefDTO.nAgoClmRecPlat"]="";
$data["appDtoList[0].appPrmCoefDTO.cAgoNoClmRec"]="";
$data["appDtoList[0].appPrmCoefDTO.cAgoClmRecNdisCnm"]="";
$data["appDtoList[0].appPrmCoefDTO.cTrafIrr"]="";
$data["appDtoList[0].appPrmCoefDTO.nTraffIrr"]="";
$data["appDtoList[0].appPrmCoefDTO.cTrafIrrNdiscRsnCnm"]="";
$data["appDtoList[0].appPrmCoefDTO.nAccidentCount"]="0";
$data["appDtoList[0].appPrmCoefDTO.nAccidentAmt"]="0";
$data["appDtoList[0].appPrmCoefDTO.nHannelAdjustValue"]="";
$data["appDtoList[0].appPrmCoefDTO.cChaAtt"]="";
$data["appDtoList[0].appPrmCoefDTO.nAutomyAdjustValue"]="";
$data["appDtoList[0].appBaseDTO.cUnfixSpc"]="";
$data["appDtoList[0].appBaseDTO.tResvTm2"]="";
$data["appDtoList[0].appBaseDTO.cQryCde"]="";
$data["appDtoList[0].appBaseDTO.tIaSendTm"]="";
$data["appDtoList[0].appBaseDTO.tIaEndTm"]="";
$data["appDtoList[0].appBaseDTO.cIaCacheNo"]="";
$data["appDtoList[0].appBaseDTO.cQryCdeIaHidden"]="";
$data["appDtoList[0].appVhlDTO.cAffirmCde"]="";
$data["appDtoList[0].appPrmCoefDTO.vhlInsDis"]="";
$data["appDtoList[0].appPrmCoefDTO.otherInsDis"]="";
$data["appDtoList[0].appBaseDTO.cGbQuotnCde"]="";
$data["appDtoList[0].appBaseDTO.cOtherGbIaCacheNo"]="";
$data["appDtoList[0].appGbReturnInfoDTO.COMCostPrem"]="";
$data["appDtoList[0].appGbReturnInfoDTO.CTPCostPrem"]="";
$data["appDtoList[0].appGbReturnInfoDTO.entireCostDiscount"]="";
$data["appDtoList[0].appGbReturnInfoDTO.entireRecommenDiscount"]="";
$data["appDtoList[0].appGbReturnInfoDTO.entireExpDiscount"]="";
$data["appDtoList[0].appGbReturnInfoDTO.entireUWritingDiscount"]="";
$data["appDtoList[0].appPrmCoefDTO.cDiscFlag"]="2";
$data["appDtoList[0].appPrmCoefDTO.totDisc"]="";
$data["appDtoList[0].appPrmCoefDTO.nExpectDisc"]="";
$data["appDtoList[0].appBaseDTO.nAmt"]="";
$data["appDtoList[0].appBaseDTO.nPrm"]="";
$data["appDtoList[1].appBaseDTO.cProdNo"]="0320";
$data["appDtoList[1].appBaseDTO.tInsrncBgnTm"]=$business['BUSINESS_START_TIME'];
$data["appDtoList[1].appBaseDTO.tInsrncEndTm"]=$business['BUSINESS_END_TIME'];
$data["appDtoList[1].appBaseDTO.cRatioTyp"]="2";
$data["appDtoList[1].appBaseDTO.nRatioCoef"]="1.000000";
$data["appDtoList[1].appBaseDTO.cRenewMrk"]="0";
$data["appDtoList[1].appBaseDTO.cOrigPlyNo"]="";
$data["appDtoList[1].appVsTaxDTO.cPaytaxTyp"]="3T5001";
$data["appVhlDTO.wholeWeight"]=$auto['KERB_MASS'];//$auto['KERB_MASS'];//整备质量
$data["appVhlDTO.nDisplacementOther"]=$auto['ENGINE'];
$data["appDtoList[1].appVsTaxDTO.cTaxdptVhltyp"]="";
$data["appDtoList[1].appVsTaxDTO.tTaxEffBgnTm"]="";
$data["appDtoList[1].appVsTaxDTO.tTaxEffEndTm"]="";
$data["appDtoList[1].appVsTaxDTO.nAnnUnitTaxAmt"]="0.00";
$data["appDtoList[1].appVsTaxDTO.nTaxableAmt"]="0.00";
$data["appDtoList[1].appVsTaxDTO.nLastYear"]="0.00";
$data["appDtoList[1].appVsTaxDTO.nOverdueAmt"]="0.00";
$data["appDtoList[1].appVsTaxDTO.cAbateAmt"]="";
$data["appDtoList[1].appVsTaxDTO.cAbateProp"]="";
$data["appDtoList[1].appVsTaxDTO.cTaxAuthorities"]="";
$data["appDtoList[1].appVsTaxDTO.cTaxpayerId"]="";
$data["appDtoList[1].appVsTaxDTO.cTaxPaymentRecptNo"]="";
$data["appDtoList[1].appVsTaxDTO.cTaxfreeVhltyp"]="";
$data["appVhlDTO.cTfiSpecialMrk"]="";
$data["appDtoList[1].appVsTaxDTO.tLastSaliEndDate"]="";
$data["appDtoList[1].appVsTaxDTO.cfuelType"]="0";
$data["appDtoList[1].appVsTaxDTO.cTaxAbaMethodConType"]="";
$data["appDtoList[1].appVsTaxDTO.cCalcTaxFlag"]="";
$data["appDtoList[1].appVsTaxDTO.cTaxPrintNo"]="";
$data["nTotalAggTax"]="0.00";
$data["autoAccPlyBaseDTO.isUsed"]="";
$data["autoAccPlyBaseDTO.isShow"]="";
$data["autoAccPlyBaseDTO.cAIRelCde"]="";
$data["autoAccPlyBaseDTO.nInsuredNum"]="5";
$data["autoAccPlyBaseDTO.cProdKindId"]="";
$data["autoAccPlyBaseDTO.cProCode"]="";
$data["autoAccPlyBaseDTO.nSharNum"]="";
$data["autoAccPlyBaseDTO.nPrm"]="";
$data["autoAccPlyBaseDTO.nAmt"]="";
$data["autoAccPlyBaseDTO.nAmtTotal"]="";
$data["autoAccPlyBaseDTO.cUnfixSpc"]="";
$data["autoAccPlyBaseDTO.cAppNo"]="";
$data["autoAccPlyBaseDTO.cPlyNo"]="";
$data["autoAccPlyBaseDTO.cCarAppNo"]="";
$data["autoAccPlyBaseDTO.pkId"]="";
$data["autoAccPlyBaseDTO.nPrmTotal"]="";
$data["appDtoList[0].appBaseDTO.cPrmCur"]="01";
$data["appDtoList[0].appBaseDTO.moneyType"]="01";
$data["appDtoList[0].appBaseDTO.nPrmRmbExch"]="1";
$data["appDtoList[0].appBaseDTO.cFinTyp"]="1";
$data["appDtoList[0].appBaseDTO.cDisptSttlCde"]="007001";
$data["appDtoList[0].appBaseDTO.cDisptSttlOrg"]="";
$data["appDtoList[0].appBaseDTO.cRemark"]="";
return $data;

	}


	/**
	 * [InsuranceData 查询交强险保费]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T16:19:05+0800
	 * @param     array                    $auto     [参数数组]
	 * @param     array                    $business [参数数组]
	 * @param     array                    $mvtalci  [参数数组]
	 */
	private function InsuranceData($auto=array(),$business=array(),$mvtalci=array())
	{

		$data["c_undr_opn"]="";
		$data["c_undr_mrk"]="";
		$data["btnAttachment"]="影像信息";
		$data["btnAppSave"]="暂存";
		$data["appDtoList[0].appBaseDTO.cAppNo"]="";
		$data["appDtoList[1].appBaseDTO.cAppNo"]="";
		$data["appDtoList[0].appBaseDTO.cRenewalFlag"]="";
		$data["appDtoList[1].appBaseDTO.cRenewalFlag"]="";
		$data["appDtoList[0].appBaseDTO.checkCodeIa"]="";
		$data["appDtoList[1].appBaseDTO.checkCodeIa"]="";
		$data["appDtoList[0].appBaseDTO.checkCode"]="";
		$data["appDtoList[1].appBaseDTO.checkCode"]="";
		$data["appDtoList[0].appBaseDTO.cProdNoGroup"]="A330";
		$data["appDtoList[0].appBaseDTO.cAppTyp"]="A";
		$data["appDtoList[0].appBaseDTO.cSecondLevDptCde"]="0201";
		$data["startTime"]="";
		$data["appDtoList[0].appBaseDTO.cCarAppNo"]="";
		$data["appDtoList[0].appBaseDTO.cPlyNo"]="";
		$data["appDtoList[0].appBaseDTO.nCalcPrm"]="";
		$data["appDtoList[1].appBaseDTO.cPlyNo"]="";
		$data["appDtoList[1].appBaseDTO.nCalcPrm"]="";
		$data["appDtoList[0].appBaseDTO.channelCode"]="025002";
		$data["appDtoList[0].appBaseDTO.cDptCde"]="0201010119";
		$data["appDtoList[0].appBaseDTO.cAgtAgrNo"]="U000201010030";
		$data["appDtoList[0].appBaseDTO.cBrkrCde"]="WI047";
		$data["appDtoList[0].appBaseDTO.cBsnsTyp"]="027009";
		$data["appDtoList[0].appBaseDTO.cSlsCde"]="02002188";
		$data["appDtoList[0].appBaseDTO.cSlsId"]="";
		$data["appDtoList[0].appBaseDTO.cSlsNme"]="王欢";
		$data["appDtoList[0].appBaseDTO.cBatchCode"]="0002666";
		$data["appDtoList[0].appBaseDTO.cOprCde"]="02001963";
		$data["appDtoList[0].appBaseDTO.tAppTm"]=date('Y-m-d H:i:s',time());
		$data["appDtoList[0].appBaseDTO.cAgriMrk"]="0";
		$data["appDtoList[0].appBaseDTO.cOrgCde"]="02010101";
		$data["applicantDTO.cAppCode"]="";
		$data["applicantDTO.cAppNme"]=$auto['OWNER'];
		$data["applicantDTO.cClntMrk"]="1";//"1";
		$data["applicantDTO.cCertfCls"]="10";
		$data["applicantDTO.cCertfCde"]="";
		$data["applicantDTO.cCusLvl"]="";
		$data["applicantDTO.cClntCls"]="1";
		$data["applicantDTO.cCntrNme"]="";
		$data["applicantDTO.cMobile"]="";
		$data["applicantDTO.cTel"]="";
		$data["appDtoList[0].appBaseDTO.cRepFactoryCde"]="";
		$data["applicantDTO.cEmail"]="";
		$data["applicantDTO.cClntAddr"]="同投保人";
		$data["appInsuredDTO.cInsuredCde"]="";
		$data["appInsuredDTO.cInsuredNme"]=$auto['OWNER'];
		$data["appInsuredDTO.cClntMrk"]="1";
		$data["appInsuredDTO.cCertfCls"]="";
		$data["appInsuredDTO.cCertfCde"]="";
		$data["appInsuredDTO.cCusLvl"]="";
		$data["appInsuredDTO.cClntCls"]="1";
		$data["appInsuredDTO.cCntrNme"]="";
		$data["appInsuredDTO.cTel"]="";
		$data["appInsuredDTO.cMobile"]="";
		$data["appInsuredDTO.cEmail"]="";
		$data["appInsuredDTO.cClntAddr"]="同投保人";
		$data["appVhlOwnerDTO.cOwnerCde"]="";
		$data["appVhlOwnerDTO.cOwnerNme"]=$auto['OWNER'];
		$data["appVhlOwnerDTO.cCertfCls"]="";
		$data["appVhlOwnerDTO.cCertfCde"]="";//'';
		$data["appVhlOwnerDTO.cOwnerCls"]="1";
		$data["appVhlDTO.cVhlRelCde"]="01";
		$data["appVhlDTO.cNewMrk"]="0";
		$data["appVhlDTO.cPlateNo"]=$auto['LICENSE_NO'];
		$data["appVhlDTO.cEngNo"]=$auto['ENGINE_NO'];
		$data["appVhlDTO.cFrmNo"]=$auto['VIN_NO'];
		$data["appVhlDTO.cFstRegYm"]=$auto['ENROLL_DATE'];
		$data["appVhlDTO.vhlAge"]=date("Y",time())-date("Y",strtotime($auto['ENROLL_DATE']));
		$data["appVhlDTO.cModelNme.select"]="";
		$data["appVhlDTO.cModelNme"]=$auto['MODEL'];
		$data["appVhlDTO.cCurModelNme"]=$auto['MODEL'];
		$data["appVhlDTO.cPlatModelCde"]=$auto['MODEL_CODE'];//"BSHCLZUC0053";
		$data["appVhlDTO.nNewPurchaseValue"]=$auto['BUYING_PRICE'];
		$data["appVhlDTO.balancePrice"]="0.00";
		$data["appVhlDTO.nRealityPurchaseValue"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
		$data["appVhlDTO.nConsultRealityPurchaseValue"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
		
		/*********使用性质*********/
		// switch ($auto['USE_CHARACTER'])
		// {
		// 	case 'NON_OPERATING_PRIVATE':
		// 	case 'NON_OPERATING_ENTERPRISE':
		// 	case 'NON_OPERATING_AUTHORITY':
		// 	case 'NON_OPERATING_LOW_SPEED_TRUCK':
		// 	case 'NON_OPERATING_TRUCK':
		// 		$data["appVhlDTO.cVhlCateGoryCde"]="3A0002";
		// 		break;
		// 	case 'OPERATING_LEASE_RENTAL':
		// 	case 'OPERATING_CITY_BUS':
		// 	case 'OPERATING_HIGHWAY_BUS':
		// 	case 'OPERATING_TRUCK':
		// 	case 'OPERATING_TRAILER':
		// 	case 'OPERATING_LOW_SPEED_TRUCK':
		// 		$data["appVhlDTO.cVhlCateGoryCde"]="3A0001";
		// 		break;
		// }
		// /****非营业***/
		// if($auto['USE_CHARACTER']=="NON_OPERATING_PRIVATE")
		// {
		// $data["appVhlDTO.cUsageCde"]="364001";
		// if($auto['SEATS']<6)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365001";
		// 	}
		// 	else if($auto['SEATS']>=6 || $auto['SEATS']<=10)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365002";
		// 	}
		// 	else if($auto['SEATS']>=10 || $auto['SEATS']<=20)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365004";
		// 	}
		// 	else if($auto['SEATS']>=20 || $auto['SEATS']<=36)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365011";
		// 	}
		// 	else if($auto['SEATS']>36)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365012";
		// 	}
		// }
		// else if($auto['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE")
		// {
		// $data["appVhlDTO.cUsageCde"]="364002";
		// }
		// else if($auto['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
		// {
		// $data["appVhlDTO.cUsageCde"]="364003";
		// }
		// else if($auto['USE_CHARACTER']=="NON_OPERATING_TRUCK")//非营业货车
		// {
		// $data["appVhlDTO.cUsageCde"]="364004";
		// if($auto['TONNAGE']<2)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365006";
		// 	}
		// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365007";
		// 	}
		// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365008";
		// 	}
		// 	else if($auto['TONNAGE']>=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365009";
		// 	}
		// }
		
		// /*****营业****/
		
		// if($auto['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
		// {
		// $data["appVhlDTO.cUsageCde"]="364005";
		// }
		// else if($auto['USE_CHARACTER']=="OPERATING_CITY_BUS")
		// {
		// $data["appVhlDTO.cUsageCde"]="364006";
		// }
		// else if($auto['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
		// {
		// $data["appVhlDTO.cUsageCde"]="364007";
		// }
		// else if($auto['USE_CHARACTER']=="OPERATING_TRUCK")/*****营业货车*****/
		// {
		// $data["appVhlDTO.cUsageCde"]="364008";
		
		// if($auto['TONNAGE']<2)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365006";
		// 	}
		// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365007";
		// 	}
		// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365008";
		// 	}
		// 	else if($auto['TONNAGE']>=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365009";
		// 	}
		// }
		
		// /****营业低速货车*****/
		// if($auto['USE_CHARACTER']=="OPERATING_LOW_SPEED_TRUCK")
		// {
		// $data["appVhlDTO.cVhlTyp"]="365010";
		// }
		// /***营业挂车****/
		// if($auto['USE_CHARACTER']=="OPERATING_TRAILER")
		// {
		// 	if($auto['TONNAGE']<2)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365025";
		// 	}
		// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365026";
		// 	}
		// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365027";
		// 	}
		// 	else if($auto['TONNAGE']>=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365028";
		// 	}
		
		// }
		
		// if($auto['SEATS']<6)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365001";
		// }
		// else if($auto['SEATS']>=6 && $auto['SEATS']<=10)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365002";
		// }
		// else if($auto['SEATS']>=10 && $auto['SEATS']<=20)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365004";
		// }
		// else if($auto['SEATS']>=20 && $auto['SEATS']<=36)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365011";
		// }
		// else if($auto['SEATS']>=36)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365012";
		// }
		// $data["appVhlDTO.nSeatNum"]=$auto['SEATS']; //座位数
		
		// foreach($this->license_type as $k=>$v){
		// 					if($k==$auto['LICENSE_TYPE']){
		// 						$data["appVhlDTO.cPlateTyp"]=$v[0];// 号牌种类
		// 					}
		// 			}
		$data["appVhlDTO.cVhlCateGoryCde"]="3A0002";
        /****非营业***/
		$data["appVhlDTO.cUsageCde"]="364001";
		$data["appVhlDTO.cVhlTyp"]="365001";
		$data["appVhlDTO.nSeatNum"]=$auto['SEATS']; //座位数     
		$data["appVhlDTO.cPlateTyp"]="02";// 号牌种类
		//产地代码转换
		if($auto['ORIGIN']=="DOMESTIC")
		{
		$data["appVhlDTO.cProdPlace"]="0";
		}
		else if($auto['ORIGIN']=="IMPORTED")
		{
		$data["appVhlDTO.cProdPlace"]="1";
		}
		else if($auto['ORIGIN']=="JOINT_VENTURE")
		{
		$data["appVhlDTO.cProdPlace"]="2";
		}
		$data["appVhlDTO.cRegVhlTyp.text"]="";
		$data["appVhlDTO.cRegVhlTyp"]="K33";//$auto['clauseType'];//"K33";//交管车辆类型   默认只能算家用车。。后面再逐步更新
		$data["appVhlDTO.nTonage"]=$auto['TONNAGE']?"0":$auto['TONNAGE'];
		$data["appVhlDTO.nDisplacement"]=$auto['ENGINE']!=""?$auto['ENGINE']:"";//排量
		$data["appVhlDTO.cLoanVehicleFlag"]="0";//车贷多年投保标志 0否 1是
		$data["appVhlDTO.cChgOwnerFlag"]="0";//过户车辆标志 0否 1是
		$data["appVhlDTO.tTransferDate"]="";
		$data["appVhlDTO.cEcdemicMrk"]="0";//是否外地车  0 否 1是
		
		$data["appVhlDTO.cInspectionCde"]="305005003";
		$data["appVhlDTO.isExamine"]="";
		$data["appVhlDTO.cInspectTm"]="";
		$data["appVhlDTO.cInspectRec"]="";
		$data["appVhlDTO.nYearApp"]="";
		$data["appVhlDTO.cInspectorNme"]="";
		$data["appVhlDTO.newVehicleFlag"]="0";
		$data["appVhlDTO.cMfgYear"]="201105";
		$data["appDtoList[0].appBaseDTO.cProdNo"]="0330";
		$data["appDtoList[0].appBaseDTO.tInsrncBgnTm"]=$mvtalci['MVTALCI_START_TIME'];
		$data["appDtoList[0].appBaseDTO.tInsrncEndTm"]=$mvtalci['MVTALCI_END_TIME'];
		$data["appDtoList[0].appBaseDTO.cRatioTyp"]="2";
		$data["appDtoList[0].appBaseDTO.nRatioCoef"]="1.000000";
		$data["appDtoList[0].appBaseDTO.cRiFacMrk"]="0";
		$data["appDtoList[0].appBaseDTO.cRenewMrk"]="0";
		$data["appDtoList[0].appBaseDTO.cOrigPlyNo"]="";
		$data["appDtoList[0].appCvrgList[0].cCvrgNo"]="030006";
		$data["appDtoList[0].appCvrgList[0].nCalcPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nCalcAnnPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nPrmVar"]="";
		$data["appDtoList[0].appCvrgList[0].nBefEdrPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nAmtVar"]="";
		
		$data["appDtoList[0].appCvrgList[0].cPureRiskPremiumFlag"]="1";
		$data["appDtoList[0].appCvrgList[0].nPureRiskPremium"]="";//"3127.038200";
		$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="";
		$data["appDtoList[0].appCvrgList[0].nPreferential"]="";
		$data["appDtoList[0].appCvrgList[0].noFranchise"]="369003";
		$data["appDtoList[0].appCvrgList[0].nAmt"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
		$data["appDtoList[0].appCvrgList[0].nRate"]="";
		$data["appDtoList[0].appCvrgList[0].nBasePrm"]="";//"3127.038200";
		$data["appDtoList[0].appCvrgList[0].nBefPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nDisCoef"]="";
		$data["appDtoList[0].appCvrgList[0].nPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nDductPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nDductCalcPrm"]="";
		$data["appDtoList[0].appCvrgList[0].tBgnTm"]=$mvtalci['MVTALCI_START_TIME'];
		$data["appDtoList[0].appCvrgList[0].tEndTm"]=$mvtalci['MVTALCI_END_TIME'];
		$data["appDtoList[0].appPrmCoefDTO.cAppointAreaCode"]="";
		$data["appDtoList[0].appPrmCoefDTO.cAgoClmRec"]="376104";
		$data["appDtoList[0].appPrmCoefDTO.nAgoClmRec"]="1.0";
		$data["appDtoList[0].appPrmCoefDTO.nAgoClmRecPlat"]="1.0";
		$data["appDtoList[0].appPrmCoefDTO.cAgoNoClmRec"]="";
		$data["appDtoList[0].appPrmCoefDTO.cAgoClmRecNdisCnm"]="";
		$data["appDtoList[0].appPrmCoefDTO.cTrafIrr"]="";
		$data["appDtoList[0].appPrmCoefDTO.nTraffIrr"]="1.0";
		$data["appDtoList[0].appPrmCoefDTO.cTrafIrrNdiscRsnCnm"]="";
		$data["appDtoList[0].appPrmCoefDTO.nAccidentCount"]="1";
		$data["appDtoList[0].appPrmCoefDTO.nAccidentAmt"]="";
		$data["appDtoList[0].appPrmCoefDTO.nHannelAdjustValue"]="";
		$data["appDtoList[0].appPrmCoefDTO.cChaAtt"]="";
		$data["appDtoList[0].appPrmCoefDTO.nAutomyAdjustValue"]="";
		$data["appDtoList[0].appBaseDTO.cUnfixSpc"]="";
		$data["Input2"]="自动生成特约";
		$data["Input2"]="选择特别约定";
		$data["Input2"]="删除";
		$data["appDtoList[0].appBaseDTO.tResvTm2"]="";
		$data["appDtoList[0].appBaseDTO.cQryCde"]="";
		$data["appDtoList[0].appBaseDTO.tIaSendTm"]="";
		$data["appDtoList[0].appBaseDTO.tIaEndTm"]="";
		$data["appDtoList[0].appBaseDTO.cIaCacheNo"]="";
		$data["appDtoList[0].appBaseDTO.cQryCdeIaHidden"]="";
		$data["appDtoList[0].appVhlDTO.cAffirmCde"]="商业险投保查询";
		$data["appDtoList[0].appPrmCoefDTO.vhlInsDis"]="";
		$data["appDtoList[0].appPrmCoefDTO.otherInsDis"]="";
		$data["appDtoList[0].appBaseDTO.cGbQuotnCde"]="";
		$data["appDtoList[0].appBaseDTO.cOtherGbIaCacheNo"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.COMCostPrem"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.CTPCostPrem"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.entireCostDiscount"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.entireRecommenDiscount"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.entireExpDiscount"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.entireUWritingDiscount"]="";
		$data["appDtoList[0].appPrmCoefDTO.cDiscFlag"]="2";
		$data["appDtoList[0].appPrmCoefDTO.totDisc"]="";
		$data["appDtoList[0].appPrmCoefDTO.nExpectDisc"]="";
		$data["appDtoList[0].appBaseDTO.nAmt"]="0.00";
		$data["appDtoList[0].appBaseDTO.nPrm"]="";
		$data["appDtoList[1].appBaseDTO.cProdNo"]="0320";
		$data["appDtoList[1].appBaseDTO.tInsrncBgnTm"]=$mvtalci['MVTALCI_START_TIME'];
		$data["appDtoList[1].appBaseDTO.tInsrncEndTm"]=$mvtalci['MVTALCI_END_TIME'];
		$data["appDtoList[1].appBaseDTO.cRatioTyp"]="2";
		$data["appDtoList[1].appBaseDTO.nRatioCoef"]="1.000000";
		$data["appDtoList[1].appBaseDTO.cRenewMrk"]="0";
		$data["appDtoList[1].appBaseDTO.cOrigPlyNo"]="";
		$data["appDtoList[1].appVsTaxDTO.cPaytaxTyp"]="3T5001";
		$data["appVhlDTO.wholeWeight"]=$auto['KERB_MASS'];//$auto['KERB_MASS'];
		$data["appVhlDTO.nDisplacementOther"]=$auto['ENGINE'];
		$data["appDtoList[1].appVsTaxDTO.cTaxdptVhltyp"]="";
		$data["appDtoList[1].appVsTaxDTO.tTaxEffBgnTm"]="";
		$data["appDtoList[1].appVsTaxDTO.tTaxEffEndTm"]="";
		$data["appDtoList[1].appVsTaxDTO.nAnnUnitTaxAmt"]="0.00";
		$data["appDtoList[1].appVsTaxDTO.nTaxableAmt"]="0.00";
		$data["appDtoList[1].appVsTaxDTO.nLastYear"]="0.00";
		$data["appDtoList[1].appVsTaxDTO.nOverdueAmt"]="0.00";
		$data["appDtoList[1].appVsTaxDTO.cAbateAmt"]="";
		$data["appDtoList[1].appVsTaxDTO.cAbateProp"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxAuthorities"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxpayerId"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxPaymentRecptNo"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxfreeVhltyp"]="";
		$data["appVhlDTO.cTfiSpecialMrk"]="";
		$data["appDtoList[1].appVsTaxDTO.tLastSaliEndDate"]="";
		$data["appDtoList[1].appVsTaxDTO.cfuelType"]="0";
		$data["appDtoList[1].appVsTaxDTO.cTaxAbaMethodConType"]="";
		$data["appDtoList[1].appVsTaxDTO.cCalcTaxFlag"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxPrintNo"]="";
		$data["cTaxInfoQuery"]="车船税信息查询";
		$data["nTotalAggTax"]="0.00";
		$data["appDtoList[1].appPrmCoefDTO.roadSafetyScale"]="";
		$data["appDtoList[1].appPrmCoefDTO.cTrafIrr"]="";
		$data["appDtoList[1].appPrmCoefDTO.roadIrreScale"]="";
		$data["appDtoList[1].appPrmCoefDTO.peccancyAdjustreason"]="";
		$data["appDtoList[1].appPrmCoefDTO.potaDriveIrre"]="";
		$data["appDtoList[1].appPrmCoefDTO.potaDveIrreValue"]="";
		$data["appDtoList[1].appPrmCoefDTO.drunkDriveIrre"]="";
		$data["appDtoList[1].appPrmCoefDTO.drunkDveIrreValue"]="";
		$data["appDtoList[1].appPrmCoefDTO.totParmValue"]="";
		$data["appDtoList[1].appPrmCoefDTO.cNdiscRsn"]="";
		$data["appDtoList[1].appBaseDTO.cUnfixSpc"]="";
		$data["Input2"]="自动生成特约";
		$data["Input2"]="选择特别约定";
		$data["Input2"]="删除";
		$data["appDtoList[1].appBaseDTO.tResvTm2"]="";
		$data["appDtoList[1].appBaseDTO.cQryCde"]="";
		$data["appDtoList[1].appBaseDTO.tIaSendTm"]="";
		$data["appDtoList[1].appBaseDTO.tIaEndTm"]="";
		$data["appDtoList[1].appBaseDTO.cIaCacheNo"]="";
		$data["appDtoList[1].appBaseDTO.cQryCdeIaHidden"]="";
		$data["appDtoList[1].appVhlDTO.cAffirmCde"]="";
		$data["Input2"]="交强险保费计算";
		$data["appDtoList[1].appBaseDTO.nAmt"]="122000";
		$data["appDtoList[1].appBaseDTO.nPrm"]="";
		$data["appDtoList[1].appVsTaxDTO.nAggTax"]="";
		$data["autoAccPlyBaseDTO.isUsed"]="";
		$data["autoAccPlyBaseDTO.isShow"]="";
		$data["autoAccPlyBaseDTO.cAIRelCde"]="";
		$data["autoAccPlyBaseDTO.nInsuredNum"]="5";
		$data["autoAccPlyBaseDTO.cProdKindId"]="";
		$data["autoAccPlyBaseDTO.cProCode"]="";
		$data["autoAccPlyBaseDTO.nSharNum"]="";
		$data["autoAccPlyBaseDTO.nPrm"]="";
		$data["autoAccPlyBaseDTO.nAmt"]="";
		$data["autoAccPlyBaseDTO.nAmtTotal"]="";
		$data["autoAccPlyBaseDTO.cUnfixSpc"]="";
		$data["autoAccPlyBaseDTO.cAppNo"]="";
		$data["autoAccPlyBaseDTO.cPlyNo"]="";
		$data["autoAccPlyBaseDTO.cCarAppNo"]="";
		$data["autoAccPlyBaseDTO.pkId"]="";
		$data["autoAccPlyBaseDTO.nPrmTotal"]="0.00";
		$data["appDtoList[0].appBaseDTO.cPrmCur"]="01";
		$data["appDtoList[0].appBaseDTO.moneyType"]="01";
		$data["appDtoList[0].appBaseDTO.nPrmRmbExch"]="1";
		$data["appDtoList[0].appBaseDTO.cFinTyp"]="1";
		$data["appDtoList[0].appBaseDTO.cDisptSttlCde"]="007001";
		$data["appDtoList[0].appBaseDTO.cDisptSttlOrg"]="";
		$data["appDtoList[0].appBaseDTO.cRemark"]="";
		return $data;

	}

/**
 * [cardata 查询车船税]
 * @AuthorHTL
 * @DateTime  2016-05-26T16:19:23+0800
 * @param     array                    $info [参数数组]
 * @return    [type]                         [成功返回数组，失败返回空]
 */
	private function cardata($info=array())
	{
		if(!isset($info['LICENSE_NO'])) return false;
	   	$data["carMark"]="";//$info['LICENSE_NO'];
	   	$data["vhlTyp"]="02";
	   	if(!isset($info['VIN_NO'])) return false;
	   	$data["rackNo"]=$info['VIN_NO'];
	   	if(!isset($info['ENGINE_NO'])) return false;
	   	$data["engineNo"]=$info['ENGINE_NO'];
	   	$data["cDptCde"]="0201010119";
	   	$data["ownerName"]="";//$auto['OWNER'];
	   	$data["cUsageCde"]="364001";
	   	$data["cProdNo"]="0320";
	   	$result= $this->requestGetData($this->carurl."?".http_build_query($data));
	   	$td='%<td.*?>(.*?)</td>%si';
	   	preg_match_all($td,$result,$match);
	   	if($match[1][75]=="")
	   	{
	   		return "";

	   	}
   		return $match;

	}

	/**
	 * [datas 查询保费请求数组]
	 * @AuthorHTL
	 * @DateTime  2016-05-26T16:19:49+0800
	 * @param     array                    $auto     [参数数组]
	 * @param     array                    $business [参数数组]
	 * @param     array                    $mvtalci  [参数数组]
	 * @return    [type]                             [成功返回数组，失败返回false]
	 */
	private function  datas($auto=array(),$business=array(),$mvtalci=array())
	{
		$data["appDtoList[0].appBaseDTO.cAppNo"]="";
		$data["appDtoList[1].appBaseDTO.cAppNo"]="";
		$data["appDtoList[0].appBaseDTO.cRenewalFlag"]="";
		$data["appDtoList[1].appBaseDTO.cRenewalFlag"]="";
		$data["appDtoList[0].appBaseDTO.checkCodeIa"]="";
		$data["appDtoList[1].appBaseDTO.checkCodeIa"]="";
		$data["appDtoList[0].appBaseDTO.checkCode"]="";
		$data["appDtoList[1].appBaseDTO.checkCode"]="";
		$data["appDtoList[0].appBaseDTO.cProdNoGroup"]="A330";
		$data["appDtoList[0].appBaseDTO.cAppTyp"]="A";
		$data["appDtoList[0].appBaseDTO.cSecondLevDptCde"]="0201";
		$data["startTime"]="";
		$data["appDtoList[0].appBaseDTO.cCarAppNo"]="";
		$data["appDtoList[0].appBaseDTO.cPlyNo"]="";
		$data["appDtoList[0].appBaseDTO.nCalcPrm"]="";
		$data["appDtoList[1].appBaseDTO.cPlyNo"]="";
		$data["appDtoList[1].appBaseDTO.nCalcPrm"]="";
		$data["appDtoList[0].appBaseDTO.channelCode"]="025002";
		$data["appDtoList[0].appBaseDTO.cDptCde"]="0201010119";
		$data["appDtoList[0].appBaseDTO.cAgtAgrNo"]="U000201010030";
		$data["appDtoList[0].appBaseDTO.cBrkrCde"]="WI047";
		$data["appDtoList[0].appBaseDTO.cBsnsTyp"]="027009";
		$data["appDtoList[0].appBaseDTO.cSlsCde"]="02002188";
		$data["appDtoList[0].appBaseDTO.cSlsId"]="";
		$data["appDtoList[0].appBaseDTO.cSlsNme"]="王欢";
		$data["appDtoList[0].appBaseDTO.cBatchCode"]="0002666";
		$data["appDtoList[0].appBaseDTO.cOprCde"]="02001963";
		$data["appDtoList[0].appBaseDTO.tAppTm"]=date("Y-m-d H:i:s",time());
		$data["appDtoList[0].appBaseDTO.cAgriMrk"]="0";
		$data["appDtoList[0].appBaseDTO.cOrgCde"]="02010101";
		$data["applicantDTO.cAppCode"]="";
		$data["applicantDTO.cAppNme"]=$auto['OWNER'];
		$data["applicantDTO.cClntMrk"]="1";//"1";
		$data["applicantDTO.cCertfCls"]="";
		$data["applicantDTO.cCertfCde"]="";
		$data["applicantDTO.cCusLvl"]="";
		$data["applicantDTO.cClntCls"]="1";
		$data["applicantDTO.cCntrNme"]="";
		$data["applicantDTO.cMobile"]="";
		$data["applicantDTO.cTel"]="";
		$data["appDtoList[0].appBaseDTO.cRepFactoryCde"]="";
		$data["applicantDTO.cEmail"]="";
		$data["applicantDTO.cClntAddr"]="";
		$data["appInsuredDTO.cInsuredCde"]="";
		$data["appInsuredDTO.cInsuredNme"]=$auto['OWNER'];
		$data["appInsuredDTO.cClntMrk"]="1";
		$data["appInsuredDTO.cCertfCls"]="";
		$data["appInsuredDTO.cCertfCde"]="";
		$data["appInsuredDTO.cCusLvl"]="";
		$data["appInsuredDTO.cClntCls"]="1";
		$data["appInsuredDTO.cCntrNme"]="";
		$data["appInsuredDTO.cTel"]="";
		$data["appInsuredDTO.cMobile"]="";
		$data["appInsuredDTO.cEmail"]="";
		$data["appInsuredDTO.cClntAddr"]="同投保人";
		$data["appVhlOwnerDTO.cOwnerCde"]="";
		$data["appVhlOwnerDTO.cOwnerNme"]=$auto['OWNER'];
		$data["appVhlOwnerDTO.cCertfCls"]="";
		$data["appVhlOwnerDTO.cCertfCde"]="";//'';
		$data["appVhlOwnerDTO.cOwnerCls"]="1";
		$data["appVhlDTO.cVhlRelCde"]="01";
		$data["appVhlDTO.cNewMrk"]="0";
		$data["appVhlDTO.cPlateNo"]=$auto['LICENSE_NO'];
		$data["appVhlDTO.cEngNo"]=$auto['ENGINE_NO'];
		$data["appVhlDTO.cFrmNo"]=$auto['VIN_NO'];
		$data["appVhlDTO.cFstRegYm"]=$auto['ENROLL_DATE'];
		$data["appVhlDTO.vhlAge"]=date("Y",time())-date("Y",strtotime($auto['ENROLL_DATE']));
		$data["appVhlDTO.cModelNme.select"]="";
		$data["appVhlDTO.cModelNme"]=$auto['MODEL'];
		$data["appVhlDTO.cCurModelNme"]=$auto['MODEL'];
		$data["appVhlDTO.cModelCde"]="DZABCD0062";
		$data["appVhlDTO.cPlatModelCde"]=$auto['MODEL_CODE'];//"BSHCLZUC0053";
		$data["appVhlDTO.nNewPurchaseValue"]=$auto['BUYING_PRICE'];
		$data["appVhlDTO.balancePrice"]="0.00";
		$data["appVhlDTO.nRealityPurchaseValue"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
		$data["appVhlDTO.nConsultRealityPurchaseValue"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
		/*********使用性质*********/
		// switch ($auto['USE_CHARACTER'])
		// {
		// 	case 'NON_OPERATING_PRIVATE':
		// 	case 'NON_OPERATING_ENTERPRISE':
		// 	case 'NON_OPERATING_AUTHORITY':
		// 	case 'NON_OPERATING_LOW_SPEED_TRUCK':
		// 	case 'NON_OPERATING_TRUCK':
		// 		$data["appVhlDTO.cVhlCateGoryCde"]="3A0002";
		// 		break;
		// 	case 'OPERATING_LEASE_RENTAL':
		// 	case 'OPERATING_CITY_BUS':
		// 	case 'OPERATING_HIGHWAY_BUS':
		// 	case 'OPERATING_TRUCK':
		// 	case 'OPERATING_TRAILER':
		// 	case 'OPERATING_LOW_SPEED_TRUCK':
		// 		$data["appVhlDTO.cVhlCateGoryCde"]="3A0001";
		// 		break;
		// }
		// /****非营业***/
		// if($auto['USE_CHARACTER']=="NON_OPERATING_PRIVATE")
		// {
		// $data["appVhlDTO.cUsageCde"]="364001";
		// if($auto['SEATS']<6)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365001";
		// 	}
		// 	else if($auto['SEATS']>=6 || $auto['SEATS']<=10)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365002";
		// 	}
		// 	else if($auto['SEATS']>=10 || $auto['SEATS']<=20)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365004";
		// 	}
		// 	else if($auto['SEATS']>=20 || $auto['SEATS']<=36)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365011";
		// 	}
		// 	else if($auto['SEATS']>36)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365012";
		// 	}
		// }
		// else if($auto['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE")
		// {
		// $data["appVhlDTO.cUsageCde"]="364002";
		// }
		// else if($auto['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
		// {
		// $data["appVhlDTO.cUsageCde"]="364003";
		// }
		// else if($auto['USE_CHARACTER']=="NON_OPERATING_TRUCK")//非营业货车
		// {
		// $data["appVhlDTO.cUsageCde"]="364004";
		// if($auto['TONNAGE']<2)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365006";
		// 	}
		// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365007";
		// 	}
		// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365008";
		// 	}
		// 	else if($auto['TONNAGE']>=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365009";
		// 	}
		// }
		
		// /*****营业****/
		
		// if($auto['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
		// {
		// $data["appVhlDTO.cUsageCde"]="364005";
		// }
		// else if($auto['USE_CHARACTER']=="OPERATING_CITY_BUS")
		// {
		// $data["appVhlDTO.cUsageCde"]="364006";
		// }
		// else if($auto['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
		// {
		// $data["appVhlDTO.cUsageCde"]="364007";
		// }
		// else if($auto['USE_CHARACTER']=="OPERATING_TRUCK")/*****营业货车*****/
		// {
		// $data["appVhlDTO.cUsageCde"]="364008";
		
		// if($auto['TONNAGE']<2)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365006";
		// 	}
		// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365007";
		// 	}
		// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365008";
		// 	}
		// 	else if($auto['TONNAGE']>=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365009";
		// 	}
		// }
		
		// /****营业低速货车*****/
		// if($auto['USE_CHARACTER']=="OPERATING_LOW_SPEED_TRUCK")
		// {
		// $data["appVhlDTO.cVhlTyp"]="365010";
		// }
		// /***营业挂车****/
		// if($auto['USE_CHARACTER']=="OPERATING_TRAILER")
		// {
		// 	if($auto['TONNAGE']<2)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365025";
		// 	}
		// 	else if($auto['TONNAGE']>=2 || $auto['TONNAGE']<=5)
		// 	{
		// 		$data["appVhlDTO.cVhlTyp"]="365026";
		// 	}
		// 	else if($auto['TONNAGE']>=5 || $auto['TONNAGE']<=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365027";
		// 	}
		// 	else if($auto['TONNAGE']>=10)
		// 	{
		// 	$data["appVhlDTO.cVhlTyp"]="365028";
		// 	}
		
		// }
		
		// if($auto['SEATS']<6)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365001";
		// }
		// else if($auto['SEATS']>=6 && $auto['SEATS']<=10)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365002";
		// }
		// else if($auto['SEATS']>=10 && $auto['SEATS']<=20)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365004";
		// }
		// else if($auto['SEATS']>=20 && $auto['SEATS']<=36)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365011";
		// }
		// else if($auto['SEATS']>=36)
		// {
		// $data["appVhlDTO.cVhlTyp"]="365012";
		// }
		// $data["appVhlDTO.nSeatNum"]=$auto['SEATS']; //座位数
		
		// foreach($this->license_type as $k=>$v){
		// 					if($k==$auto['LICENSE_TYPE']){
		// 						$data["appVhlDTO.cPlateTyp"]=$v[0];// 号牌种类
		// 					}
		// 			}
		$data["appVhlDTO.cVhlCateGoryCde"]="3A0002";
        /****非营业***/
		$data["appVhlDTO.cUsageCde"]="364001";
		$data["appVhlDTO.cVhlTyp"]="365001";
		$data["appVhlDTO.nSeatNum"]=$auto['SEATS']; //座位数     
		$data["appVhlDTO.cPlateTyp"]="02";// 号牌种类
		
		$data["appVhlDTO.cProdPlace"]="0";//"2";
		$data["appVhlDTO.cRegVhlTyp.text"]="";
		$data["appVhlDTO.cRegVhlTyp"]="K33";//$auto['clauseType'];///"K33";
		$data["appVhlDTO.nTonage"]=$auto['TONNAGE']?"0":$auto['TONNAGE'];
		$data["appVhlDTO.nDisplacement"]=$auto['ENGINE']!=""?$auto['ENGINE']:"";
		$data["appVhlDTO.cLoanVehicleFlag"]="0";
		$data["appVhlDTO.cChgOwnerFlag"]="0";
		$data["appVhlDTO.tTransferDate"]="";
		$data["appVhlDTO.cEcdemicMrk"]="0";
		$data["appVhlDTO.cInspectionCde"]="305005003";
		$data["appVhlDTO.isExamine"]="";
		$data["appVhlDTO.cInspectTm"]="";
		$data["appVhlDTO.cInspectRec"]="";
		$data["appVhlDTO.nYearApp"]="";
		$data["appVhlDTO.cInspectorNme"]="";
		$data["appVhlDTO.newVehicleFlag"]="0";
		$data["appVhlDTO.cMfgYear"]="201302";
		
		
		
		$data["appDtoList[0].appBaseDTO.cProdNo"]="0330";
		$data["appDtoList[0].appBaseDTO.tInsrncBgnTm"]=$business['BUSINESS_START_TIME'];//$business['BUSINESS_START_TIME'];
		$data["appDtoList[0].appBaseDTO.tInsrncEndTm"]=$business['BUSINESS_END_TIME'];//$business['BUSINESS_END_TIME'];
		$data["appDtoList[0].appBaseDTO.cRatioTyp"]="";//"2";
		$data["appDtoList[0].appBaseDTO.nRatioCoef"]="1.000000";
		$data["appDtoList[0].appBaseDTO.cRiFacMrk"]="0";
		$data["appDtoList[0].appBaseDTO.cRenewMrk"]="0";
		$data["appDtoList[0].appBaseDTO.cOrigPlyNo"]="";
		
		
		
		//车辆损失险
		$data["appDtoList[0].appCvrgList[0].cCvrgNo"]="030006";
		$data["appDtoList[0].appCvrgList[0].nCalcPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nCalcAnnPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nPrmVar"]="";
		$data["appDtoList[0].appCvrgList[0].nBefEdrPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nAmtVar"]="";
		$data["appDtoList[0].appCvrgList[0].cPureRiskPremiumFlag"]="";
		
		switch ($business['POLICY']['DOC_AMOUNT'])
		{
			case '0':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375001";
				break;
			case '300':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375002";
				break;
			case '500':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375003";
				break;
			case '1000':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375004";
				break;
			case '2000':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375005";
				break;
			default:
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375001";
				break;
		}
		
		
		$data["appDtoList[0].appBaseDTO.cProdNo"]="0330";
		$data["appDtoList[0].appBaseDTO.tInsrncBgnTm"]=$business['BUSINESS_START_TIME'];
		$data["appDtoList[0].appBaseDTO.tInsrncEndTm"]=$business['BUSINESS_END_TIME'];
		$data["appDtoList[0].appBaseDTO.cRatioTyp"]="2";
		$data["appDtoList[0].appBaseDTO.nRatioCoef"]="1.000000";
		$data["appDtoList[0].appBaseDTO.cRiFacMrk"]="0";
		$data["appDtoList[0].appBaseDTO.cRenewMrk"]="0";
		$data["appDtoList[0].appBaseDTO.cOrigPlyNo"]="";
		
		
		foreach($business['POLICY']['BUSINESS_ITEMS'] as $use=>$value)
		{
							switch ($value) {

								case 'TTBLI':
									//商业第三者责任保险
									$data["appDtoList[0].appCvrgList[1].cCvrgNo"]="030018";
									$data["appDtoList[0].appCvrgList[1].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[1].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[1].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[1].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[1].nAmtVar"]="";
									switch ($business['POLICY']['TTBLI_INSURANCE_AMOUNT']) {
										case '5':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006004";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="50000";
											break;
										case '10':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006005";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="100000";
											break;
										case '15':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006010";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="150000";
											break;
										case '20':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006006";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="200000";
											break;
										case '30':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006007";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="300000";
											break;
										case '50':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006009";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="500000";
											break;
										case '100':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006014";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="1000000";
											break;
										case '150':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006016";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="1500000";
											break;
										case '200':
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006017";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="2000000";
											break;
		
										default:
											$data["appDtoList[0].appCvrgList[1].cIndemLmtLvl"]="306006004";
											$data["appDtoList[0].appCvrgList[1].nAmt"]="50000";
											break;
									}
		
									$data["appDtoList[0].appCvrgList[1].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[1].nRate"]="";
									$data["appDtoList[0].appCvrgList[1].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[1].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[1].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[1].nPrm"]="";
									$data["appDtoList[0].appCvrgList[1].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[1].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[1].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[1].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
		
								case 'TWCDMVI':
									$data["appDtoList[0].appCvrgList[4].cCvrgNo"]="030061";
									$data["appDtoList[0].appCvrgList[4].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[4].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[4].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[4].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[4].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[4].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[4].nAmt"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
									$data["appDtoList[0].appCvrgList[4].nRate"]="";
									$data["appDtoList[0].appCvrgList[4].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[4].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[4].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[4].nPrm"]="";
									$data["appDtoList[0].appCvrgList[4].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[4].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[4].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[4].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
		
								case 'TCPLI_DRIVER':
									//车上人员责任险（司机）
									$data["appDtoList[0].appCvrgList[2].cCvrgNo"]="030001";
									$data["appDtoList[0].appCvrgList[2].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[2].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[2].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[2].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[2].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[2].nLiabDaysLmt"]="1";
									$data["appDtoList[0].appCvrgList[2].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[2].nAmt"]=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
									$data["appDtoList[0].appCvrgList[2].nRate"]="";
									$data["appDtoList[0].appCvrgList[2].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[2].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[2].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[2].nPrm"]="";
									$data["appDtoList[0].appCvrgList[2].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[2].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[2].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[2].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'TCPLI_PASSENGER':
									//车上人员责任险（乘客）
									$data["appDtoList[0].appCvrgList[3].cCvrgNo"]="030009";
									$data["appDtoList[0].appCvrgList[3].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[3].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[3].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[3].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[3].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[3].nLiabDaysLmt"]=$business['POLICY']['TCPLI_PASSENGER_COUNT'];
									$data["appDtoList[0].appCvrgList[3].nPerAmt"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
									$data["appDtoList[0].appCvrgList[3].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[3].nAmt"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']*$business['POLICY']['TCPLI_PASSENGER_COUNT'];
									$data["appDtoList[0].appCvrgList[3].nRate"]="";
									$data["appDtoList[0].appCvrgList[3].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[3].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[3].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[3].nPrm"]="";
									$data["appDtoList[0].appCvrgList[3].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[3].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[3].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[3].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'BSDI':
									//车身划痕损失险
									$data["appDtoList[0].appCvrgList[5].cCvrgNo"]="032601";
									$data["appDtoList[0].appCvrgList[5].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[5].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[5].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[5].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[5].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[5].cIndemLmtLvl"]="N03001001";
									$data["appDtoList[0].appCvrgList[5].noFranchise"]="369003";
		
									$data["appDtoList[0].appCvrgList[5].nAmt"]=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
									$data["appDtoList[0].appCvrgList[5].nRate"]="";
									$data["appDtoList[0].appCvrgList[5].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[5].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[5].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[5].nPrm"]="";
									$data["appDtoList[0].appCvrgList[5].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[5].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[5].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[5].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'BGAI':
									//玻璃单独破碎险
									$data["appDtoList[0].appCvrgList[6].cCvrgNo"]="030004";
									$data["appDtoList[0].appCvrgList[6].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[6].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[6].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[6].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[6].nAmtVar"]="";
		
									switch ($business['POLICY']['GLASS_ORIGIN']) {
										case 'DOMESTIC':
											$data["appDtoList[0].appCvrgList[6].glassType"]="303011001";
											break;
										case 'IMPORTED':
											$data["appDtoList[0].appCvrgList[6].glassType"]="303011002";
											break;
									}
									$data["appDtoList[0].appCvrgList[6].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[6].nRate"]="";
									$data["appDtoList[0].appCvrgList[6].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[6].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[6].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[6].nPrm"]="";
									$data["appDtoList[0].appCvrgList[6].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[6].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
		
								case 'STSFS':
									//指定专修厂险
									$data["appDtoList[0].appCvrgList[11].cCvrgNo"]="033003";
									$data["appDtoList[0].appCvrgList[11].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[11].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[11].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[11].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[11].nAmtVar"]="";
									switch ($business['POLICY']['STSFS_RATE']) {
										case '国产':
											$data["appDtoList[0].appCvrgList[11].vhlDamage"]="0.1";
											break;
										case '进口':
											$data["appDtoList[0].appCvrgList[11].vhlDamage"]="0.1";
											break;
										default:
											# code...
											break;
									}
		
									$data["appDtoList[0].appCvrgList[11].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[11].nRate"]="";
									$data["appDtoList[0].appCvrgList[11].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[11].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[11].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[11].nPrm"]="";
									$data["appDtoList[0].appCvrgList[11].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[11].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'VWTLI':
									//发动机涉水损失险
									$data["appDtoList[0].appCvrgList[9].cCvrgNo"]="033001";
									$data["appDtoList[0].appCvrgList[9].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[9].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[9].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[9].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[9].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[9].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[9].nAmt"]="";
									$data["appDtoList[0].appCvrgList[9].nRate"]="";
									$data["appDtoList[0].appCvrgList[9].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[9].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[9].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[9].nPrm"]="";
									$data["appDtoList[0].appCvrgList[9].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[9].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[9].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[9].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'NIELI':
									//新增加设备损失险
									$data["appDtoList[0].appCvrgList[8].cCvrgNo"]="030021";
									$data["appDtoList[0].appCvrgList[8].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[8].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[8].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[8].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[8].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[8].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[8].nAmt"]=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
									$data["appDtoList[0].appCvrgList[8].nRate"]="";
									$data["appDtoList[0].appCvrgList[8].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[8].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[8].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[8].nPrm"]="";
									$data["appDtoList[0].appCvrgList[8].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[8].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[8].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[8].tEndTm"]=$business['BUSINESS_END_TIME'];
		
									if(!empty($business['POLICY']['NIELI_DEVICE_LIST']))
										{
											$devcount = count($business['POLICY']['NIELI_DEVICE_LIST']);
											for($idx = 0; $idx<$devcount; $idx++)
											{
												$data["appDtoList[0].appTgtObjDTO[$idx].cPkId"]="";
												$data["appDtoList[0].appTgtObjDTO[$idx].nSeqNo"]="";
												$data["appDtoList[0].appTgtObjDTO[$idx].cTgtObjName"]= $business['POLICY']['NIELI_DEVICE_LIST'][$idx]['NAME'];
												$data["appDtoList[0].appTgtObjDTO[$idx].nTgtObjNumber"]=$business['POLICY']['NIELI_DEVICE_LIST'][$idx]['COUNT'];
												$data["appDtoList[0].appTgtObjDTO[$idx].nTgtObjPrice"]=$business['POLICY']['NIELI_DEVICE_LIST'][$idx]['BUYING_PRICE'];
												$data["appDtoList[0].appTgtObjDTO[$idx].tTgtObjTm"]=$business['POLICY']['NIELI_DEVICE_LIST'][$idx]['BUYING_DATE'];
												$data["appDtoList[0].appTgtObjDTO[$idx].nTgtObjPriceSubtotal"]=$business['POLICY']['NIELI_DEVICE_LIST'][$idx]['DISCOUNT_PRICE'];
											}
										}
		
									break;
								case 'SLOI':
									//自燃损失险
									$data["appDtoList[0].appCvrgList[7].cCvrgNo"]="030012";
									$data["appDtoList[0].appCvrgList[7].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[7].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[7].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[7].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[7].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[7].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[7].nAmt"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
									$data["appDtoList[0].appCvrgList[7].nRate"]="";
									$data["appDtoList[0].appCvrgList[7].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[7].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[7].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[7].nPrm"]="";
									$data["appDtoList[0].appCvrgList[7].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[7].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[7].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[7].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'CIDI':
									//修理期间费用补偿险
									$data["appDtoList[0].appCvrgList[13].cCvrgNo"]="033007";
									$data["appDtoList[0].appCvrgList[13].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[13].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[13].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[13].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[13].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[13].nPerAmt"]=$business['POLICY']['DAILY_LIMIT'];
									$data["appDtoList[0].appCvrgList[13].nLiabDaysLmt"]=$business['POLICY']['INSURANCE_DAYS'];
									$data["appDtoList[0].appCvrgList[13].nAmt"]="1500";
									$data["appDtoList[0].appCvrgList[13].nRate"]="";
									$data["appDtoList[0].appCvrgList[13].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[13].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[13].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[13].nPrm"]="";
									$data["appDtoList[0].appCvrgList[13].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[13].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'SRDI':
									//车辆损失险无法找到第三方特约险
									$data["appDtoList[0].appCvrgList[14].cCvrgNo"]="033008";
									$data["appDtoList[0].appCvrgList[14].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[14].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[14].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[14].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[14].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[14].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[14].nRate"]="";
									$data["appDtoList[0].appCvrgList[14].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[14].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[14].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[14].nPrm"]="";
									$data["appDtoList[0].appCvrgList[14].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[14].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'LIDI':
									//精神损害抚慰金责任险
									$data["appDtoList[0].appCvrgList[12].cCvrgNo"]="033004";
									$data["appDtoList[0].appCvrgList[12].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[12].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[12].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[12].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[12].nAmtVar"]="";
									$data["appDtoList[0].appCvrgList[12].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[12].nAmt"]=$business['POLICY']['LIABILITY_INSURANCE_AMOUNT'];
									$data["appDtoList[0].appCvrgList[12].nRate"]="";
									$data["appDtoList[0].appCvrgList[12].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[12].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[12].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[12].nPrm"]="";
									$data["appDtoList[0].appCvrgList[12].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[12].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
									
								case 'TTBLI_NDSI':
								//不计免赔率险（第三者责任保险）
								$data["appDtoList[0].appCvrgList[17].cCvrgNo"]="033010";
								$data["appDtoList[0].appCvrgList[17].nCalcPrm"]="";
								$data["appDtoList[0].appCvrgList[17].nCalcAnnPrm"]="";
								$data["appDtoList[0].appCvrgList[17].nPrmVar"]="";
								$data["appDtoList[0].appCvrgList[17].nBefEdrPrm"]="";
								$data["appDtoList[0].appCvrgList[17].nAmtVar"]="0";
								$data["appDtoList[0].appCvrgList[17].nAmt"]="0";
								$data["appDtoList[0].appCvrgList[17].nRate"]="0";
								$data["appDtoList[0].appCvrgList[17].nBasePrm"]="";
								$data["appDtoList[0].appCvrgList[17].nBefPrm"]="";
								$data["appDtoList[0].appCvrgList[17].nDisCoef"]="";
								$data["appDtoList[0].appCvrgList[17].nPrm"]="";
								$data["appDtoList[0].appCvrgList[17].tBgnTm"]=$business['BUSINESS_START_TIME'];
								$data["appDtoList[0].appCvrgList[17].tEndTm"]=$business['BUSINESS_END_TIME'];
		
									break;
								case 'TWCDMVI_NDSI':
									//不计免赔率险（全车盗抢保险）
									$data["appDtoList[0].appCvrgList[20].cCvrgNo"]="033013";
									$data["appDtoList[0].appCvrgList[20].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[20].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[20].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[20].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[20].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[20].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[20].nRate"]="0";
									$data["appDtoList[0].appCvrgList[20].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[20].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[20].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[20].nPrm"]="";
									$data["appDtoList[0].appCvrgList[20].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[20].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'TCPLI_DRIVER_NDSI':
									//不计免赔率险（车上人员责任保险（司机））
									$data["appDtoList[0].appCvrgList[18].cCvrgNo"]="033011";
									$data["appDtoList[0].appCvrgList[18].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[18].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[18].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[18].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[18].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[18].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[18].nRate"]="0";
									$data["appDtoList[0].appCvrgList[18].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[18].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[18].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[18].nPrm"]="";
									$data["appDtoList[0].appCvrgList[18].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[18].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'TCPLI_PASSENGER_NDSI':
									//不计免赔率险（车上人员责任保险（乘客））
									$data["appDtoList[0].appCvrgList[19].cCvrgNo"]="033012";
									$data["appDtoList[0].appCvrgList[19].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[19].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[19].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[19].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[19].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[19].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[19].nRate"]="0";
									$data["appDtoList[0].appCvrgList[19].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[19].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[19].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[19].nPrm"]="";
									$data["appDtoList[0].appCvrgList[19].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[19].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'BSDI_NDSI':
									//不计免赔率险（车身划痕损失险）
									$data["appDtoList[0].appCvrgList[21].cCvrgNo"]="033015";
									$data["appDtoList[0].appCvrgList[21].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[21].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[21].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[21].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[21].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[21].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[21].nRate"]="0";
									$data["appDtoList[0].appCvrgList[21].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[21].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[21].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[21].nPrm"]="";
									$data["appDtoList[0].appCvrgList[21].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[21].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'SLOI_NDSI':
									//不计免赔率险（自燃损失险）
									$data["appDtoList[0].appCvrgList[22].cCvrgNo"]="033014";
									$data["appDtoList[0].appCvrgList[22].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[22].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[22].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[22].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[22].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[22].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[22].nRate"]="0";
									$data["appDtoList[0].appCvrgList[22].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[22].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[22].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[22].nPrm"]="";
									$data["appDtoList[0].appCvrgList[22].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[22].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'VWTLI_NDSI':
									//不计免赔率险（发动机涉水损失险）
									$data["appDtoList[0].appCvrgList[23].cCvrgNo"]="033016";
									$data["appDtoList[0].appCvrgList[23].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[23].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[23].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[23].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[23].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[23].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[23].nRate"]="0";
									$data["appDtoList[0].appCvrgList[23].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[23].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[23].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[23].nPrm"]="";
									$data["appDtoList[0].appCvrgList[23].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[23].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'LIDI_NDSI':
									//不计免赔率险(精神损害抚慰金责任险)
									$data["appDtoList[0].appCvrgList[25].cCvrgNo"]="033019";
									$data["appDtoList[0].appCvrgList[25].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[25].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[25].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[25].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[25].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[25].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[25].nRate"]="0";
									$data["appDtoList[0].appCvrgList[25].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[25].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[25].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[25].nPrm"]="";
									$data["appDtoList[0].appCvrgList[25].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[25].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
								case 'NIELI_NDSI':
									//不计免赔率险（新增加设备损失险）
									$data["appDtoList[0].appCvrgList[24].cCvrgNo"]="033018";
									$data["appDtoList[0].appCvrgList[24].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[24].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[24].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[24].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[24].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[24].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[24].nRate"]="0";
									$data["appDtoList[0].appCvrgList[24].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[24].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[24].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[24].nPrm"]="";
									$data["appDtoList[0].appCvrgList[24].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[24].tEndTm"]=$business['BUSINESS_END_TIME'];
									break;
							}
				}

									$data["appDtoList[0].appCvrgList[0].cCvrgNo"]="030006";
									$data["appDtoList[0].appCvrgList[0].nPreferential"]="";
									$data["appDtoList[0].appCvrgList[0].noFranchise"]="369003";
									$data["appDtoList[0].appCvrgList[0].nAmt"]=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
									$data["appDtoList[0].appCvrgList[0].nRate"]="";
									$data["appDtoList[0].appCvrgList[0].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[0].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[0].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[0].nPrm"]="";
									$data["appDtoList[0].appCvrgList[0].nDductPrm"]="";
									$data["appDtoList[0].appCvrgList[0].nDductCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[0].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[0].tEndTm"]=$business['BUSINESS_END_TIME'];
									$data["appDtoList[0].appCvrgList[15].cCvrgNo"]="033009";
									$data["appDtoList[0].appCvrgList[15].nCalcPrm"]="";
									$data["appDtoList[0].appCvrgList[15].nCalcAnnPrm"]="";
									$data["appDtoList[0].appCvrgList[15].nPrmVar"]="";
									$data["appDtoList[0].appCvrgList[15].nBefEdrPrm"]="";
									$data["appDtoList[0].appCvrgList[15].nAmtVar"]="0";
									$data["appDtoList[0].appCvrgList[15].nAmt"]="0";
									$data["appDtoList[0].appCvrgList[15].nRate"]="0";
									$data["appDtoList[0].appCvrgList[15].nBasePrm"]="";
									$data["appDtoList[0].appCvrgList[15].nBefPrm"]="";
									$data["appDtoList[0].appCvrgList[15].nDisCoef"]="";
									$data["appDtoList[0].appCvrgList[15].nPrm"]="";
									$data["appDtoList[0].appCvrgList[15].tBgnTm"]=$business['BUSINESS_START_TIME'];
									$data["appDtoList[0].appCvrgList[15].tEndTm"]=$business['BUSINESS_END_TIME'];

		$data["appDtoList[0].appCvrgList[0].nCalcPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nCalcAnnPrm"]="";
		$data["appDtoList[0].appCvrgList[0].nPrmVar"]="";
		$data["appDtoList[0].appCvrgList[0].nBefEdrPrm"]="";
		$data["appDtoList[0].appCvrgList[0].cPureRiskPremiumFlag"]="";//"1";
		$data["appDtoList[0].appCvrgList[0].nPureRiskPremium"]="";
		
		switch ($business['POLICY']['DOC_AMOUNT']) {
			case '0':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375001";
				break;
			case '300':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375002";
				break;
			case '500':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375003";
				break;
			case '1000':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375004";
				break;
			case '2000':
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375005";
				break;
			default:
				$data["appDtoList[0].appCvrgList[0].cVhlFranchiseCde"]="375001";
				break;
		}
		
		$data["appDtoList[0].appPrmCoefDTO.cAppointAreaCode"]="";
		$data["appDtoList[0].appPrmCoefDTO.cAgoClmRec"]="376104";
		$data["appDtoList[0].appPrmCoefDTO.nAgoClmRec"]="";//"1.0";
		$data["appDtoList[0].appPrmCoefDTO.nAgoClmRecPlat"]="";//"1.0";
		$data["appDtoList[0].appPrmCoefDTO.cAgoNoClmRec"]="";
		$data["appDtoList[0].appPrmCoefDTO.cAgoClmRecNdisCnm"]="";
		$data["appDtoList[0].appPrmCoefDTO.cTrafIrr"]="";
		$data["appDtoList[0].appPrmCoefDTO.nTraffIrr"]="";//"1.0";
		$data["appDtoList[0].appPrmCoefDTO.cTrafIrrNdiscRsnCnm"]="";
		$data["appDtoList[0].appPrmCoefDTO.nAccidentCount"]="";//"1";
		$data["appDtoList[0].appPrmCoefDTO.nAccidentAmt"]="";
		$data["appDtoList[0].appPrmCoefDTO.nHannelAdjustValue"]="";
		$data["appDtoList[0].appPrmCoefDTO.cChaAtt"]="";
		$data["appDtoList[0].appPrmCoefDTO.nAutomyAdjustValue"]="";
		$data["appDtoList[0].appBaseDTO.cUnfixSpc"]="";
		$data["Input2"]="自动生成特约";
		$data["Input2"]="选择特别约定";
		$data["Input2"]="删除";
		
		
		$data["appDtoList[0].appBaseDTO.tResvTm2"]="2016-05-16 20:28:22";
		$data["appDtoList[0].appBaseDTO.cQryCde"]="V0101JTIC510016001463402204753";
		$data["appDtoList[0].appBaseDTO.tIaSendTm"]="2016-05-16 20:38:14";
		$data["appDtoList[0].appBaseDTO.tIaEndTm"]="2016-05-26 20:36:00";
		$data["appDtoList[0].appBaseDTO.cIaCacheNo"]="456f3ac2bd2a48788bec260aadaf013e";
		$data["appDtoList[0].appBaseDTO.cQryCdeIaHidden"]="V0101JTIC510016001463402204753";
		$data["appDtoList[0].appVhlDTO.cAffirmCde"]="";
		$data["appDtoList[0].appPrmCoefDTO.vhlInsDis"]="0.5273";
		$data["appDtoList[0].appPrmCoefDTO.otherInsDis"]="0.5273";
		$data["appDtoList[0].appBaseDTO.cGbQuotnCde"]="2160e18311fe4101ba0b4c5dcfaa82";
		
		
		$data["appDtoList[0].appBaseDTO.cOtherGbIaCacheNo"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.COMCostPrem"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.CTPCostPrem"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.entireCostDiscount"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.entireRecommenDiscount"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.entireExpDiscount"]="";
		$data["appDtoList[0].appGbReturnInfoDTO.entireUWritingDiscount"]="";
		$data["appDtoList[0].appPrmCoefDTO.cDiscFlag"]="2";
		$data["appDtoList[0].appPrmCoefDTO.totDisc"]="";
		$data["appDtoList[0].appPrmCoefDTO.nExpectDisc"]="";
		$data["appDtoList[0].appBaseDTO.nAmt"]="0.00";
		$data["appDtoList[0].appBaseDTO.nPrm"]="";
		$data["appDtoList[1].appBaseDTO.cProdNo"]="0320";
		$data["appDtoList[1].appBaseDTO.tInsrncBgnTm"]=$business['BUSINESS_START_TIME'];
		$data["appDtoList[1].appBaseDTO.tInsrncEndTm"]=$business['BUSINESS_END_TIME'];
		$data["appDtoList[1].appBaseDTO.cRatioTyp"]="2";
		$data["appDtoList[1].appBaseDTO.nRatioCoef"]="1.000000";
		$data["appDtoList[1].appBaseDTO.cRenewMrk"]="0";
		$data["appDtoList[1].appBaseDTO.cOrigPlyNo"]="";
		$data["appDtoList[1].appVsTaxDTO.cPaytaxTyp"]="3T5002";
		$data["appVhlDTO.wholeWeight"]=$auto['KERB_MASS'];
		$data["appVhlDTO.nDisplacementOther"]=$auto['ENGINE'];
		$data["appDtoList[1].appVsTaxDTO.cTaxdptVhltyp"]="3T1018";
		$data["appDtoList[1].appVsTaxDTO.tTaxEffBgnTm"]="2016-01-01";
		$data["appDtoList[1].appVsTaxDTO.tTaxEffEndTm"]="2016-12-31";
		$data["appDtoList[1].appVsTaxDTO.nAnnUnitTaxAmt"]="0.00";
		$data["appDtoList[1].appVsTaxDTO.nTaxableAmt"]="0.00";
		$data["appDtoList[1].appVsTaxDTO.nLastYear"]="0.00";
		$data["appDtoList[1].appVsTaxDTO.nOverdueAmt"]="0.00";
		$data["appDtoList[1].appVsTaxDTO.cAbateAmt"]="";
		$data["appDtoList[1].appVsTaxDTO.cAbateProp"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxAuthorities"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxpayerId"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxPaymentRecptNo"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxfreeVhltyp"]="";
		$data["appVhlDTO.cTfiSpecialMrk"]="";
		$data["appDtoList[1].appVsTaxDTO.tLastSaliEndDate"]="";
		$data["appDtoList[1].appVsTaxDTO.cfuelType"]="0";
		$data["appDtoList[1].appVsTaxDTO.cTaxAbaMethodConType"]="";
		$data["appDtoList[1].appVsTaxDTO.cCalcTaxFlag"]="";
		$data["appDtoList[1].appVsTaxDTO.cTaxPrintNo"]="";
		$data["cTaxInfoQuery"]="车船税信息查询";
		$data["nTotalAggTax"]="0.00";
		$data["appDtoList[1].appPrmCoefDTO.roadSafetyScale"]="";
		$data["appDtoList[1].appPrmCoefDTO.cTrafIrr"]="";
		$data["appDtoList[1].appPrmCoefDTO.roadIrreScale"]="";
		$data["appDtoList[1].appPrmCoefDTO.peccancyAdjustreason"]="";
		$data["appDtoList[1].appPrmCoefDTO.potaDriveIrre"]="";
		$data["appDtoList[1].appPrmCoefDTO.potaDveIrreValue"]="";
		$data["appDtoList[1].appPrmCoefDTO.drunkDriveIrre"]="";
		$data["appDtoList[1].appPrmCoefDTO.drunkDveIrreValue"]="";
		$data["appDtoList[1].appPrmCoefDTO.totParmValue"]="";
		$data["appDtoList[1].appPrmCoefDTO.cNdiscRsn"]="";
		$data["appDtoList[1].appBaseDTO.cUnfixSpc"]="";
		$data["Input2"]="自动生成特约";
		$data["Input2"]="选择特别约定";
		$data["Input2"]="删除";
		$data["appDtoList[1].appBaseDTO.tResvTm2"]="";
		$data["appDtoList[1].appBaseDTO.cQryCde"]="";
		$data["appDtoList[1].appBaseDTO.tIaSendTm"]="";
		$data["appDtoList[1].appBaseDTO.tIaEndTm"]="";
		$data["appDtoList[1].appBaseDTO.cIaCacheNo"]="";
		$data["appDtoList[1].appBaseDTO.cQryCdeIaHidden"]="";
		$data["appDtoList[1].appVhlDTO.cAffirmCde"]="";
		$data["Input2"]="交强险保费计算";
		$data["appDtoList[1].appBaseDTO.nAmt"]="122000.00";
		$data["appDtoList[1].appBaseDTO.nPrm"]="";
		$data["appDtoList[1].appVsTaxDTO.nAggTax"]="";
		$data["autoAccPlyBaseDTO.isUsed"]="";
		$data["autoAccPlyBaseDTO.isShow"]="";
		$data["autoAccPlyBaseDTO.cAIRelCde"]="";
		$data["autoAccPlyBaseDTO.nInsuredNum"]="5";
		$data["autoAccPlyBaseDTO.cProdKindId"]="";
		$data["autoAccPlyBaseDTO.cProCode"]="";
		$data["autoAccPlyBaseDTO.nSharNum"]="";
		$data["autoAccPlyBaseDTO.nPrm"]="";
		$data["autoAccPlyBaseDTO.nAmt"]="";
		$data["autoAccPlyBaseDTO.nAmtTotal"]="";
		$data["autoAccPlyBaseDTO.cUnfixSpc"]="";
		$data["autoAccPlyBaseDTO.cAppNo"]="";
		$data["autoAccPlyBaseDTO.cPlyNo"]="";
		$data["autoAccPlyBaseDTO.cCarAppNo"]="";
		$data["autoAccPlyBaseDTO.pkId"]="";
		$data["autoAccPlyBaseDTO.nPrmTotal"]="0.00";
		$data["appDtoList[0].appBaseDTO.cPrmCur"]="01";
		$data["appDtoList[0].appBaseDTO.moneyType"]="01";
		$data["appDtoList[0].appBaseDTO.nPrmRmbExch"]="1";
		$data["appDtoList[0].appBaseDTO.cFinTyp"]="1";
		$data["appDtoList[0].appBaseDTO.cDisptSttlCde"]="007001";
		$data["appDtoList[0].appBaseDTO.cDisptSttlOrg"]="";
		$data["appDtoList[0].appBaseDTO.cRemark"]="";
		return $data;

    }



}
?>