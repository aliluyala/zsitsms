 <?php

/**
 * 项目:           中华联合车险保费在线计算接口
 * 文件名:         ShanDong_CHINAINSURANCE_PC.class.php
 * 版权所有：      成都启点科技有限公司.
 * 作者：          LiangYuLin
 * 版本：          1.0.0
 *
 * 中华联合系统算价接口
 *
 **/
error_reporting(E_ALL^E_NOTICE^E_WARNING);
session_start();
class SiChuan_CHINAINSURANCE_PC
{

	const formFile = 'Calculate.tpl';
	private $error = "";//设置错误信息成员属性
	private $setItems = array(
		'username' => '中华联合账号',//'ex_wangjing002',
		'password' => '中华联合密码',//'KFCkfc002',
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
      * [__construct 构造函数]
      * @AuthorHTL
      * @DateTime  2016-05-26T16:38:01+0800
      * @param     [type]                   $config    [配置参数]
      * @param     [type]                   $cachePath [缓存目录]
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

		$this->loginAarray=array("username"=>$user,"password" =>$password,"execution"=>"","_eventId"=>"submit");//登陆条件
		if(empty($cachePath))
		{
			$this->cookie_file = dirname(__FILE__).'/chinainsurance_cookie.txt';
		}
		else
		{
			$this->cookie_file = $cachePath.'/chinainsurance_cookie.txt';  //COOKIE文件存放地址
		}

		$this->vinprice="http://114.251.1.161/zccx/vinDecodingList.shtml";//车架号查询购置价
		$this->modelrice="http://114.251.1.161/zccx/vehicleList.shtml";//品牌名称查询购置价
		$this->search="http://114.251.1.161/zccx/search";//车架号查询购置价
		$this->getVehicle="http://114.251.1.161/zccx/getVehicle.shtml";
		$this->actionservice="http://carply.cic.cn/pcis/actionservice.ai";//查询车型代码
		$this->UrlLogin="http://sso.cic.cn/cas/login?service=http%3A%2F%2Fcarply.cic.cn%2Fpcis%2Fj_spring_cas_security_check";//登陆处理查询
	    $this->UrlOffterUrl="http://carply.cic.cn/pcis/policy/universal/quickapp/actionservice.ai";//查询保费
	    $this->modelUrl= "http://114.251.1.161/zccx/getVehicleForReturn.shtml";
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
		$ret =  $this->post($url,$post,$head,$foll);
		$lt='/<input[^<]*type="hidden"[^<]* name="lt"[^<]* value="([^<]*)"[^<]*>/';
		$execution='/<input[^<]*type="hidden"[^<]* name="execution"[^<]* value="([^<]*)"[^<]*>/';
		preg_match($lt, $ret, $stx);
		preg_match($execution, $ret, $str);
		if(strstr($url,$this->modelrice) || strstr($url,$this->modelUrl))
		{
			if(strpos($ret,'重新登录'))
			{
				$result= $this->get('http://sso.cic.cn/cas/login');
				$lt='/<input[^<]*type="hidden"[^<]* name="lt"[^<]* value="([^<]*)"[^<]*>/';
				$execution='/<input[^<]*type="hidden"[^<]* name="execution"[^<]* value="([^<]*)"[^<]*>/';
				preg_match($lt, $result, $stx);
				preg_match($execution, $result, $str);

				if(is_array($stx) && is_array($str))
				{
					$this->loginAarray['lt']=$stx[1];
					$this->loginAarray['execution']=$str[1];
					$sty = $this->post($this->UrlLogin,$this->loginAarray);//执行登录

					if($sty[1]!="")
					{
						return false;
					}

				}
			     $ret =  $this->post($url,$post,$head,$foll);
			}
		}
		elseif($stx[1]!="" && $str[1]!="")
		{

				$this->loginAarray['lt']=$stx[1];
				$this->loginAarray['execution']=$str[1];
				$sty = $this->post($this->UrlLogin,$this->loginAarray);//执行登录
				if($sty[1]!="")
				{
					return false;
				}
				$ret =  $this->post($url,$post,$head,$foll);


		}
		else
		{
			$ret =  $this->post($url,$post,$head,$foll);
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
		$ret =  $this->get($url,$head,$foll,$ref);
		if(strpos($ret,'重新登录'))
		{
			$result= $this->get('http://sso.cic.cn/cas/login');
			$lt='/<input[^<]*type="hidden"[^<]* name="lt"[^<]* value="([^<]*)"[^<]*>/';
			$execution='/<input[^<]*type="hidden"[^<]* name="execution"[^<]* value="([^<]*)"[^<]*>/';
			preg_match($lt, $result, $stx);
			preg_match($execution, $result, $str);

			if(is_array($stx) && is_array($str)  && !empty($stx) && !empty($str))
			{
				$this->loginAarray['lt']=$stx[1];
				$this->loginAarray['execution']=$str[1];
				$sty = $this->post($this->UrlLogin,$this->loginAarray);//执行登录
				if($sty[1]!="")
				{
					return false;
				}

			}
		     $ret =  $this->get($url,$head,$refer,$encodeing);
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
		curl_setopt($curl,CURLINFO_HEADER_OUT,1);
	    $tmpInfo = curl_exec($curl); // 执行操作
	    $getinfo= curl_getinfo($curl);
	    if(strstr($getinfo['url'],$this->modelrice) || strstr($getinfo['url'],$this->modelUrl))
	    {
	    	$tmpInfo=mb_convert_encoding($tmpInfo, "UTF-8","gb2312");
	    }
	    return $tmpInfo;
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
		return $tmpInfo;
	}


   	/**
   	 * [premium 请求查价]
   	 * @AuthorHTL
   	 * @DateTime  2016-05-26T16:15:24+0800
   	 * @param     array                    $auto     [参数数组]
   	 * @param     array                    $business [参数数组]
   	 * @param     array                    $mvtalci  [参数数组]
   	 * @return    [type]                             [成功返回数组，失败返回false]
   	 */
	public  function  premium($auto=array(),$business=array(),$mvtalci=array())
	{

			   $data["ACTION_HANDLE"]="perform";
			   $data["ADAPTER_TYPE"]="JSON_TYPE";
			   $data["BEAN_HANDLE"]="baseAction";
			   $data["BIZ_SYNCH_CONTINUE"]="false";
			   $data["CODE_TYPE"]="CODE_TYPE";
               $data["CUST_DATA"]="contiOfferMrk=1###multiOfferFlagMrk=1";//不需要按照淄博系统规则来查询
			   $data["HELPCONTROLMETHOD"]="common";
			   $data["SCENE"]="UNDEFINED";
			   $data["SERVICE_MOTHOD"]="calcPremium";
			   $data["SERVICE_NAME"]="offerBizAction";
			   $data["SERVICE_TYPE"]="ACTION_SERVIC";


			   $data['DW_DATA']= self::datas($auto,$business,$mvtalci);

			   /*if($data['DW_DATA'] == "0")
			   {
			   	    $this->error['errorMsg']="身份证号码不能为空";
			   	    return false;
			   }*/

			   $DW = $this->requestPostData($this->UrlOffterUrl,$data);

			   if($DW=="")
			   {
			   		 $DW = $this->requestPostData($this->UrlOffterUrl,$data);
			   		 if($DW=="")
			   		 {
			   		 	unset($_SESSION['car']);
			   		 	$this->error['errorMsg']="网络故障，请稍等在试";
			   			return false;
			   		 }
			   }

			   $app = json_decode($DW,true);
			   if(isset($app['RESULT_MSG']) && $app['RESULT_MSG'] !="" && strstr($app['RESULT_MSG'],"车险平台返回信息"))
			   {
				   	unset($_SESSION['car']);
				   	$this->error['errorMsg']=$app['RESULT_MSG'];
				   	return false;
			   }

			   if(strstr($app['RESULT_MSG'],'车型不一致'))
			   {

			   		preg_match_all('/行业车型编码：(.*)/', $app['RESULT_MSG'], $code_matches);
					$code_arr = explode(' ', $code_matches[0][0]);
					$car_code = substr($code_arr[0], 21);
					$car_info = $this->Car_Code($car_code);

					if(!strstr($car_info,'没有找到符合条件的数据'))
					{
						preg_match_all("/<table id=\"zhcx\".*>(.*)<\/table>/Us",$car_info,$result);
						preg_match_all("/<tr class=\"table001-trbg001\".*>(.*)(?=<tr class=\"table001-trbg001\".*>|<cosmo>)/Us",$result[1][0] . "<cosmo>",$result);

						$car_result = $this->car_Data($car_info,$result,1);

						if($car_result)
						{
							$_SESSION['car']= $car_result;
							$this->error['errorMsg'] = '已自动切换成正确车型。请重新查询购置价！';
							return false;
						}
					}

			   }


				$results['MVTALCI'] = array();
				$results['MVTALCI']['TRAVEL_TAX_PREMIUM']= '0.00';
				$results['MVTALCI']['MVTALCI_PREMIUM']   = '0.00';
				$results['MVTALCI']['MVTALCI_DISCOUNT']  = '1.000';
				$results['MVTALCI']['MVTALCI_START_TIME']= '';
				$results['MVTALCI']['MVTALCI_END_TIME']  = '';
				if(!empty($mvtalci))
				{
                    /*******************交强险********************/
                    /*if(strstr($auto['VEHICLEALIAS'],"节能补贴"))
                    {
                    	$VEHICLEALIAS=$app['WEB_DATA'][2]['dataObjVoList'][0]['attributeVoList']['VsTax.NTaxableAmt']['value']/2;
                    }
                    else
                    {
                    	$VEHICLEALIAS=$app['WEB_DATA'][2]['dataObjVoList'][0]['attributeVoList']['VsTax.NTaxableAmt']['value'];
                    }*/

					$results['MVTALCI']['TRAVEL_TAX_PREMIUM']= $app['WEB_DATA'][2]['dataObjVoList'][0]['attributeVoList']['VsTax.NAggTax']['value'];        //车船税
					$results['MVTALCI']['MVTALCI_PREMIUM']   = $app['WEB_DATA'][4]['dataObjVoList'][0]['attributeVoList']['JQ_Base.NPrm']['value'];//$resen['ciPremium'];           //交强险保费
					$results['MVTALCI']['MVTALCI_DISCOUNT']  = $app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['JQ_PrmCoef.NLyRepRiseRat']['value'];//$resen['ciDiscount'];          //交强险折扣
					$results['MVTALCI']['MVTALCI_START_TIME']= $mvtalci['MVTALCI_START_TIME'];       //交强险生效时间
					$results['MVTALCI']['MVTALCI_END_TIME']  = date('Y-m-d H:i:s',strtotime('+1 years -1 seconds',strtotime($mvtalci['MVTALCI_START_TIME'])));         //交强险结束时间
                }

                 /*******************商业险********************/
                                 $results['MESSAGE']  = '交易成功';//$app['RESULT_MSG'];  //商业险投保信息
                                 //$results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']= round($resen['biPremium']*$resen['biDiscount'],2); //商业险扣后保费合计

                                 $results['BUSINESS']['BUSINESS_DISCOUNT']="";//$resen['biDiscount'];         //商业险折扣
                                 $results['BUSINESS']['BUSINESS_PREMIUM'] ="";//$Cvrg_NBefPrm-960;         //商业险标准保费合计
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
		                         $Cvrg_NPerPrm="0.00";
		                         foreach($app['WEB_DATA'][0]['dataObjVoList'] as $k=>$v)
                 				 {

		                         if($v['attributeVoList']!="" && isset($v['attributeVoList']))
		                         {
				                         $results['BUSINESS']['BUSINESS_DISCOUNT']=$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'];//$resen['biDiscount'];         //商业险折扣
		                                 $results['BUSINESS']['BUSINESS_START_TIME'] = $business['BUSINESS_START_TIME'];       //商业险生效时间
		                                 $results['BUSINESS']['BUSINESS_END_TIME'] = date('Y-m-d H:i:s',strtotime('+1 years -1 seconds',strtotime($business['BUSINESS_START_TIME'])));//商业险结束时间
		                                  /*******************投保项目保费二维数组********************/
		                                  	foreach ($business['POLICY']['BUSINESS_ITEMS'] as $key => $value)
		                                  	{
		                                  		switch ($value) {
		                                  			case 'TVDI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036001")//车损险
											   			{

											   					$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];
											   					$results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);

											   					$results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;


		                                  			case 'TTBLI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036002")//三者险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   				$results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;

		                                  			case 'TCPLI_DRIVER':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036003")//司机险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   				$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;

		                                  			case 'TCPLI_PASSENGER':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036004")//乘客险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   				$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;
		                                  			case 'TWCDMVI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036005")//盗抢险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   				$results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;

		                                  			case 'BGAI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036006")//玻璃险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);

											   			}
		                                  				break;
		                                  			case 'SLOI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036007")//自燃险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   				$results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);

											   			}
		                                  				break;
		                                  			case 'NIELI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036008")//新增险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   				$results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;
		                                  			case 'RDCCI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036009")//补偿险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];
											   				$results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;

		                                  			case 'VWTLI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036012")//涉水险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   				$results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;
		                                  			case 'BSDI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036013")//车身划痕险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   				$results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrmShortDuct']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);
											   			}
		                                  				break;
		                                  			case 'STSFS':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036022")//指定专修厂
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);

											   			}
		                                  				break;
		                                  			case 'MVLINFTPSI':
		                                  				if($v['attributeVoList']['Cvrg.CCvrgNo']['value']=="036024")//第三方特约险
											   			{
											   				$Cvrg_NPerPrm+=$v['attributeVoList']['Cvrg.NPrm']['value'];

											   				$results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']=round($v['attributeVoList']['Cvrg.NPerPrm']['value']*$app['WEB_DATA'][5]['dataObjVoList'][0]['attributeVoList']['SY_PrmCoef.NCoef']['value'],2);

											   			}
		                                  				break;

		                                  		}

		                                  	}




									   		if(!in_array("TVDI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']="0.00";
									   		}


									   		if(!in_array("TVDI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']="0.00";
									   		}

									   		if(!in_array("TTBLI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']="0.00";

									   		}

									   		if(!in_array("TTBLI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']="0.00";

									   		}

									   		if(!in_array("TWCDMVI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']="0.00";

									   		}

									   		if(!in_array("TWCDMVI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("TCPLI_DRIVER",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("TCPLI_DRIVER_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("TCPLI_PASSENGER",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("TCPLI_PASSENGER_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("BSDI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("BSDI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("BGAI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("STSFS",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']="0.00";

									   		}

									   		if(!in_array("SLOI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("SLOI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("NIELI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("NIELI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("VWTLI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("VWTLI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("RDCCI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']="0.00";

									   		}
									   		if(!in_array("MVLINFTPSI",$business['POLICY']['BUSINESS_ITEMS']))
									   		{
									   			$results['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']="0.00";

									   		}



									  }
									}

				$results['BUSINESS']['BUSINESS_PREMIUM'] =$Cvrg_NPerPrm;//標準保費
				$results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']=$Cvrg_NPerPrm+$app['WEB_DATA'][2]['dataObjVoList'][0]['attributeVoList']['VsTax.NAggTax']['value']+$app['WEB_DATA'][4]['dataObjVoList'][0]['attributeVoList']['JQ_Base.NPrm']['value'];//保費合計
				unset($_SESSION['car']);
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
					return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.6/100;

				 }
				 elseif($info['VEHICLE_TYPE']=="TRUCK")//货车类型
				 {

				 	if($info['USE_CHARACTER']== 'OPERATING_TRUCK')
				 	{

						 return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.9/100;

				 	}

				 	elseif($info['USE_CHARACTER']== 'NON_OPERATING_TRUCK')
				 	{

				 		 return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.9/100;
				 	}
				 	elseif($info['USE_CHARACTER']== 'NON_OPERATING_LOW_SPEED_TRUCK')
				 	{
				 		 //return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.011/100;
				 	}
				 	elseif($info['USE_CHARACTER']== 'OPERATING_LOW_SPEED_TRUCK')
				 	{
				 		 //return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.014/100;

				 	}

				 }
				 elseif($info['VEHICLE_TYPE']=="PASSENGER_CAR")//客车类型
				 {

				    if($info['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE" || $info['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
				 	{
				 		 return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.6/100;

				 	}
				 	elseif($info['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
				 	{

				 		return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.11/100;

				 	}
				 	elseif($info['USE_CHARACTER']=="OPERATING_CITY_BUS" || $info['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
				 	{

				 		return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.9/100;
				 	}


				 }

				 elseif($info['VEHICLE_TYPE']=='THREE_WHEELED')
				 		{
				 			return $info['BUYING_PRICE'] - $info['BUYING_PRICE'] * $Month * 0.11/100;
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
					$BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.6/100;

				 }
				 elseif($info['VEHICLE_TYPE']=="TRUCK")//货车类型
				 {

				 	if($info['USE_CHARACTER']== 'OPERATING_TRUCK')
				 	{

						 $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.9/100;

				 	}

				 	elseif($info['USE_CHARACTER']== 'NON_OPERATING_TRUCK')
				 	{

				 		$BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.9/100;
				 	}
				 	elseif($info['USE_CHARACTER']== 'NON_OPERATING_LOW_SPEED_TRUCK')
				 	{
				 		 $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.11/100;
				 	}
				 	elseif($info['USE_CHARACTER']== 'OPERATING_LOW_SPEED_TRUCK')
				 	{
				 		 $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.14/100;

				 	}

				 }
				 elseif($v['VEHICLE_TYPE']=="PASSENGER_CAR")//客车类型
				 {

				    if($v['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE" || $v['USE_CHARACTER']=="NON_OPERATING_AUTHORITY")
				 	{
				 		$BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.6/100;

				 	}
				 	elseif($v['USE_CHARACTER']=="OPERATING_LEASE_RENTAL")
				 	{

				 		 $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.11/100;

				 	}
				 	elseif($v['USE_CHARACTER']=="OPERATING_CITY_BUS" || $v['USE_CHARACTER']=="OPERATING_HIGHWAY_BUS")
				 	{

				 		$BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.9/100;
				 	}


				 }

				 elseif($v['VEHICLE_TYPE']=='THREE_WHEELED')
				 {
				 			 $BUYING_PRICE= $v['BUYING_PRICE'] - $v['BUYING_PRICE'] * $Month * 0.11/100;
				 }

				$data[$k]['BUYING_DATE']=$v['BUYING_DATE'];
				$data[$k]['BUYING_PRICE']=$v['BUYING_PRICE'];
				$data[$k]['COUNT']=$v['COUNT'];
				$data[$k]['DEPRECIATION']=strval($BUYING_PRICE);//$BUYING_PRICE*$v['COUNT']设备小计
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


		if(!empty($info['model']))
		{
				if(preg_match('/([a-zA-Z0-9]{10})\s*$/',$info['model'],$out)>0)
				{
					$model = $out[1];
				}
				else
				{
					$model =  str_replace("牌", "", $info['model']);
				}
			}
			if(!isset($info['page']) && $info['page']=="")
			{
				$info['page']=1;
			}



			if(!empty($_SESSION['car']))
			{
				return $_SESSION['car'];
			}

				$string = $this->car_vin($info['vin_no']);
				if(!strstr($string,'没有找到符合条件的数据'))
				{

					preg_match_all("/<table id=\"zhcx\".*>(.*)<\/table>/Us",$string,$result);
					preg_match_all("/<tr class=\"table001-trbg001\".*>(.*)(?=<tr class=\"table001-trbg001\".*>|<cosmo>)/Us",$result[1][1] . "<cosmo>",$result);

				}
				else
				{
					$string = $this->car_model($info['model']);
					if(!strstr($string,'没有找到符合条件的数据'))
					{
						preg_match_all("/<table id=\"zhcx\".*>(.*)<\/table>/Us",$string,$result);
						preg_match_all("/<tr class=\"table001-trbg001\".*>(.*)(?=<tr class=\"table001-trbg001\".*>|<cosmo>)/Us",$result[1][0] . "<cosmo>",$result);
					}
				}


				return  $this->car_Data($string,$result,$info['page']);


	}


	/**
	 * [car_Data 组装车型参数]
	 * @author LiangYuLin 2017-08-02
	 * @param  string $string [description]
	 * @param  array  $result [description]
	 * @param  string $page   [description]
	 * @return [type]         [description]
	 */
	private function car_Data($string= "" , $result=array() ,$page = "")
	{


				preg_match_all("/<table width=\"100%\".*>(.*)<\/table>/Us",$string,$page);
				preg_match_all("/<td .*>(.*)(?=<td .*>|<cosmo>)/Us",$page[0][3] . "<cosmo>",$post);
				$arr = explode("/", substr($post[0][1],1));
				preg_match('/\d+/',$arr[2],$Page_Count);//总页数
				$str = str_replace("<","",explode('总记录数',$arr[2]));
				preg_match('/\d+/',$str[1],$count_limit);//总记录数
				$qian=array("　","\t","\n","\r","&nbsp;");
		    	$hou=array("","","","","");

				foreach ($result[1] as $key => $val)
				{
						preg_match_all("/<td.*>(.*)<\/td>/Us", $val, $result);
						preg_match_all("/<input [^<]* class=\"radiobutton\"[^<]*\/>/Us", $val, $radio);
						$radios=substr($radio[0][0],75,-4);
						$array['rows'][$key]['Industry_model_code']=$radios;
						$array['rows'][$key]['vehicleId']=str_replace($qian,$hou,$result[1][3]);
						$array['rows'][$key]['vehicleName']=str_replace($qian,$hou,$result[1][1]);
						$array['rows'][$key]['vehicleSeat']=str_replace($qian,$hou,$result[1][5]);
						$array['rows'][$key]['vehicleAlias']=str_replace($qian,$hou,$result[1][11]);
						$array['rows'][$key]['vehicleExhaust']= $this->format_number(str_replace($qian,$hou,$result[1][6]),'.');//$this->divis_Num(str_replace($qian,$hou,$result[1][6]));
						$vehiclePrice=explode("【",$result[1][8]);
						$array['rows'][$key]['vehiclePrice']=str_replace($qian,$hou,$vehiclePrice[0]);
						$array['rows'][$key]['vehiclePriceTax']=str_replace($qian,$hou,str_replace("】", "", $vehiclePrice[1]));
						$array['rows'][$key]['marketaDate']=str_replace($qian,$hou,$result[1][10]);
						if(str_replace($qian,$hou,$result[1][7])=="")
						{
								$array['rows'][$key]['qualityMax']="0.00";
						}
						else
						{
								$array['rows'][$key]['qualityMax']=str_replace($qian,$hou,$result[1][7]);
						}

				}

				$array['count']=$count_limit[0];
				$array['pageNo']=$info['page'];
				$rows = 10;

				if(is_array($array) && array_key_exists('rows',$array))
				{
						$retdata = array('total'=>ceil($array['count']/$rows),'page'=>$array['pageNo'],'records'=>$array['count'],'rows'=>array());
						foreach($array['rows'] as $row)
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
							$line['vehicle_modelcode'] 	   = $row['Industry_model_code'];//模型编码
							$retdata['rows'][] = $line;
						}
						return $retdata;
				}
					return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());



	}

	/**
	 * [car_vin 通过车架号查询车型]
	 * @author LiangYuLin 2017-08-02
	 * @param  [type] $vin [description]
	 * @return [type]      [description]
	 */
	public function car_vin($vin = null)
	{

		if($vin != "")
		{

			$vin_where="regionCode=00000000&";
			$vin_where.="jyFlag=0&";
			$vin_where.="businessNature=A&";
			$vin_where.="operatorCode=0000000000&";
			$vin_where.="returnUrl=http://carply.cic.cn/pcis/offerAcceptResult&";
			$vin_where.="vname=&";
			$vin_where.="searchVin=".$vin."&";
			$vin_where.="vinflag=1&";
			$vin_where.="validNo=653eb5cdac40e71bf0d954b2e0fe3eef";

			return  iconv("GBK", "UTF-8", $this->requestGetData($this->search."?".$vin_where));

		}


	}

	/**
	 * [Car_Code 通过车型代码查询车型]
	 * @author LiangYuLin 2017-08-02
	 * @param  [type] $code [description]
	 */
	public function Car_Code($code = null )
	{
			if($code == "")
			{
				return false;
			}

			$datas["fieldName"]="";
			$datas["orderByType"]="";
			$datas["innerFlag"]="";
			$datas["jyFlag"]="";
			$datas["strqcpp"]="";
			$datas["cxid"]="";
			$datas["strqccx"]="";
			$datas["cxppid"]="";
			$datas["cxcxid"]="";
			$datas["fsearchCode"]="";
			$datas["cxingbm"]=$code;
			$datas["cxingcj]"]="";
			$datas["importFlag"]="";
			$datas["vehicleClass"]="";
			$datas["fvinRoot"]="";
			$datas["vehicleId"]="";
			$datas["pagesize"]="15";
			$datas["maxPagesize"]="200";
			$datas["gotoPage"]="";
			$datas["hvinRoot"]="";
			$datas["hvinFlag"]="";
			$datas["requestSource"]="http://carply.cic.cn/pcis/offerAcceptResult";
			$datas["cxingmc"]="";//iconv("UTF8","GB2312",$model);
			$where["qtype"]="2";
			$where["pageno"]=$info['page'];

			return $this->requestPostData($this->modelrice."?".http_build_query($where),$datas);

	}


	/**
	 * [car_model 通过车型名称查询车型]
	 * @author LiangYuLin 2017-08-02
	 * @param  [type] $model [description]
	 * @return [type]        [description]
	 */
	public function car_model($model = null )
	{

			$datas["fieldName"]="";
			$datas["orderByType"]="";
			$datas["innerFlag"]="";
			$datas["jyFlag"]="";
			$datas["strqcpp"]="";
			$datas["cxid"]="";
			$datas["strqccx"]="";
			$datas["cxppid"]="";
			$datas["cxcxid"]="";
			$datas["fsearchCode"]="";
			$datas["cxingbm"]="";
			$datas["cxingcj]"]="";
			$datas["importFlag"]="";
			$datas["vehicleClass"]="";
			$datas["fvinRoot"]="";
			$datas["vehicleId"]="";
			$datas["pagesize"]="15";
			$datas["maxPagesize"]="200";
			$datas["gotoPage"]="";
			$datas["hvinRoot"]="";
			$datas["hvinFlag"]="";
			$datas["requestSource"]="http://carply.cic.cn/pcis/offerAcceptResult";
			$datas["cxingmc"]=iconv("UTF8","GB2312",$model);
			$where["qtype"]="2";
			$where["pageno"]=$info['page'];

			return $this->requestPostData($this->modelrice."?".http_build_query($where),$datas);

	}


	 private function get_td_array($table)
	 {
	  $table = preg_replace("'<table[^>]*?>'si","",$table);
	  $table = preg_replace("'<tr[^>]*?>'si","",$table);
	  $table = preg_replace("'<td[^>]*?>'si","",$table);
	  $table = preg_replace("'<TD[^>]*?>'si","",$table);
	  $table = str_replace("</tr>","{tr}",$table);
	  $table = str_replace("</td>","{td}",$table);
	  $table = str_replace("</TD>","{td}",$table);
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

		$data["SERVICE_TYPE"]="ACTION_SERVIC";
		$data["CODE_TYPE"]="UTF-8";
		$data["BEAN_HANDLE"]="baseAction";
		$data["ACTION_HANDLE"]="perform";
		$data["SERVICE_NAME"]="policyAppBizAction";
		$data["SERVICE_MOTHOD"]="flatVehicleQuerySD";
		$data["DW_DATA"]="<data></data>";
		$data["HELPCONTROLMETHOD"]="common";
		$data["SCENE"]="UNDEFINED";
		$data["BIZ_SYNCH_LOCK"]="";
		$data["BIZ_SYNCH_MODULE_CODE"]="";
		$data["BIZ_SYNCH_NO"]="";
		$data["BIZ_SYNCH_DESC"]="";
		$data["BIZ_SYNCH_CONTINUE"]="false";
		$data["CUST_DATA"]="cFrmNo=".$auto['VIN_NO']."###CEngNo=".$auto['ENGINE_NO']."###CQueryMode=1###prodNo=0360_0332###dptCde=37032307";
		$result= self::requestPostData($this->actionservice,$data);
		$model=self::attribute('ModelCode',$result);

		if(empty($model) && $model=="")
		{
			$vin_where="regionCode=00000000&";
			$vin_where.="jyFlag=0&";
			$vin_where.="businessNature=A&";
			$vin_where.="operatorCode=0000000000&";
			$vin_where.="returnUrl=http://carply.cic.cn/pcis/offerAcceptResult&";
			$vin_where.="vname=&";
			$vin_where.="searchVin=".$auto['VIN_NO']."&";
			$vin_where.="vinflag=1&";
			$vin_where.="validNo=653eb5cdac40e71bf0d954b2e0fe3eef";
			$this->requestGetData($this->search."?".$vin_where);

			$getVehicle=$this->getVehicle."?vehicleId=".$auto['INDUSTY_MODEL_CODE']."&vehicleCode=".$auto['MODEL_CODE']."&aa=1";

			$re= self::requestGetData($getVehicle);
			$str= iconv("GBK", "UTF-8", $re);

			preg_match_all("/<table(.*)class=\"tb\".*>(.*)<\/table>/Us",$str,$result);
			$array= self::get_td_array($result[0][0]);
			$arr=array();
			foreach ($array as $key => $value)
			{
			    $arr[$key]=self::trimall($value);
			}
			$KERB_MASS= $arr[9][3];
			$gCIndustryModelCode= $arr[12][1];
		}
		else
		{
			$gCIndustryModelCode=$model;
		}



		if($business['POLICY']['TVDI_INSURANCE_AMOUNT']=="")
		{
			$business['POLICY']['TVDI_INSURANCE_AMOUNT']=$auto['DISCOUNT_PRICE'];
		}
		if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="")
		{
			$business['POLICY']['TTBLI_INSURANCE_AMOUNT']="50000";
		}
		if($business['POLICY']['TWCDMVI_INSURANCE_AMOUNT']=="")
		{
			$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT']=$auto['DISCOUNT_PRICE'];
		}
		if($business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT']=="")
		{
			$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT']="10000";
		}
		if($business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']=="")
		{
			$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']="10000";
			$business['POLICY']['TCPLI_PASSENGER_COUNT']="4";
		}
		if($business['POLICY']['BSDI_INSURANCE_AMOUNT']=="")
		{
			$business['POLICY']['BSDI_INSURANCE_AMOUNT']="2000";
		}
		if($business['POLICY']['SLOI_INSURANCE_AMOUNT']=="")
		{
			$business['POLICY']['SLOI_INSURANCE_AMOUNT']=$auto['DISCOUNT_PRICE'];
		}
		if($business['POLICY']['NIELI_INSURANCE_AMOUNT']=="")
		{
			$business['POLICY']['NIELI_INSURANCE_AMOUNT']="123";
		}
		if($business['POLICY']['NIELI_INSURANCE_AMOUNT']=="")
		{
			$business['POLICY']['NIELI_INSURANCE_AMOUNT']="123";
		}
		if($business['POLICY']['RDCCI_INSURANCE_AMOUNT']=="")
		{
			$business['POLICY']['RDCCI_INSURANCE_QUANTITY']="5";
			$business['POLICY']['RDCCI_INSURANCE_UNIT']="10";
		}



							if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="5")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="50000";
                            }
                            elseif($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="10")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="100000";
                            }
                            elseif($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="15")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="150000";
                            }
                            elseif($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="20")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="200000";
                            }
                            elseif($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="30")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="300000";
                            }
                            elseif($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="50")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="500000";
                            }
                            elseif($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="100")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="1000000";
                            }
                            elseif($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="150")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="1500000";
                            }
                            elseif($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="200")
                            {
                                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="2000000";
                            }

            $age= $this->id_age($auto['IDENTIFY_NO']);
           /* if($age==0)
            {

            	return 0;
            }*/

			if($age<18)
			{
					$jCOwnerAge="341060";
			}
			elseif($age<=18 && $age<20)
			{
					$jCOwnerAge="341061";
			}
			elseif($age<=20 && $age<25)
			{
					$jCOwnerAge="341062";
			}
			elseif($age<=25 && $age<30)
			{
					$jCOwnerAge="341063";
			}
			elseif($age<=30 && $age<35)
			{
					$jCOwnerAge="341064";
			}
			elseif($age<=35 && $age<40)
			{
					$jCOwnerAge="341065";
			}
			elseif($age<=40 && $age<45)
			{
					$jCOwnerAge="341066";
			}
			elseif($age<=45 && $age<50)
			{
					$jCOwnerAge="341067";
			}
			elseif($age<=50 && $age<55)
			{
					$jCOwnerAge="341068";
			}
			elseif($age<=55 && $age<60)
			{
					$jCOwnerAge="341069";
			}

				/***计算折扣月份**/
				$ENROLLhour = date('Y',strtotime($auto['ENROLL_DATE']))*12+(date('m',strtotime($auto['ENROLL_DATE'])));
				$START_TIMEhour = date('Y',strtotime($business['BUSINESS_START_TIME']))*12+(date('m',strtotime($business['BUSINESS_START_TIME'])));
				$Month=$START_TIMEhour-$ENROLLhour;
				if($Month<"12"){
						$gCCarAge="306001";
				}
				if($Month>="12" && $Month<"24"){
						$gCCarAge="306002";
				}if($Month>="24" && $Month<"36"){
						$gCCarAge="306003";
				}
				if($Month>="36" && $Month<"48"){
						$gCCarAge="306004";
				}
				if($Month>="48" && $Month<"72"){
						$gCCarAge="306005";
				}
				if($Month>="72"){
						$gCCarAge="306007";
				}





			if($auto['VEHICLE_TYPE']=="PASSENGER_CAR" &&  $auto['USE_CHARACTER']=="NON_OPERATING_PRIVATE")//家用车
			{
				if($auto['SEATS']<6)
				{
					$JQ__gCVhlTyp="302001001";//"302001001";
					$SY__gCVhlTyp="302001001";//"302001001";
				}
				if($auto['SEATS']>=6 &&  $auto['SEATS']<10)
				{
					$JQ__gCVhlTyp="302001022";
					$SY__gCVhlTyp="302001008";
				}
				if($auto['SEATS']>=10)
				{
					$JQ__gCVhlTyp="302001022";
					$SY__gCVhlTyp="302001016";
				}

				/*$data[1]['dataObjVoList'][0]['attributeVoList'][54]['_-gCRegVhlTyp']="K33";
				$data[1]['dataObjVoList'][0]['attributeVoList'][55]['_-gCCardDetail']="K33";
				$data[1]['dataObjVoList'][0]['attributeVoList'][48]['JQ__-gCUsageCde']="309001";//车辆性质
				$data[1]['dataObjVoList'][0]['attributeVoList'][51]['SY__-gCUsageCde']="309001";//车辆性质*/

			}

				$sex = $this->sex($business['DESIGNATED_DRIVER']['ID_CARD']);
				if($sex=="男")
				{
						$jCGender="1061";
				}
				else
				{
						$jCGender="1062";
				}


			if($auto['ENGINE']<="1.0")
			{
				$VsTaxCTaxItemCde="398013";//车船税代码
			}
			if($auto['ENGINE']>"1.0" && $auto['ENGINE']<="1.6")
			{

				$VsTaxCTaxItemCde="398014";//车船税代码

			}
			if($auto['ENGINE']>"1.6" && $auto['ENGINE']<="2.0")
			{

				$VsTaxCTaxItemCde="398015";//车船税代码
			}
			if($auto['ENGINE']>"2.0" && $auto['ENGINE']<="2.5")
			{

				$VsTaxCTaxItemCde="398016";//车船税代码

			}
			if($auto['ENGINE']>"2.5" && $auto['ENGINE']<="3.0")
			{
				$VsTaxCTaxItemCde="398017";//车船税代码
			}
			if($auto['ENGINE']>"3.0" && $auto['ENGINE']<="4.0")
			{
				$VsTaxCTaxItemCde="398018";//车船税代码
			}
			if($auto['ENGINE']>"4.0")
			{
				$VsTaxCTaxItemCde="398019";//车船税代码
			}


		$str='[
    {
        "isFilter": "false",
        "dwType": "ONLY_DATA",
        "dwName": "prodDef.vhl.Base_DW",
        "rsCount": "5",
        "pageSize": "8",
        "pageNo": "1",
        "pageCount": "1",
        "maxCount": "undefined",
        "toAddFlag": "false",
        "filterMapList": [

        ],
        "dataObjVoList": [
            {
                "index": "1",
                "selected": "false",
                "status": "UPDATED",
                "attributeVoList": [
                    {
                        _-a"_-fCRenewMrk",
                        _-b""
                    },
                    {
                        _-a"SY__-fCAppNo",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCAppNo",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCOfferNo",
                        _-b""
                    },
                    {
                        _-a"SY__-fCOfferNo",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNRiskCost",
                        _-b""
                    },
                    {
                        _-a"SY__-fNRiskCost",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNTargetPrm",
                        _-b""
                    },
                    {
                        _-a"SY__-fNTargetPrm",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNProfitRatio",
                        _-b""
                    },
                    {
                        _-a"SY__-fNProfitRatio",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNPayRatio",
                        _-b""
                    },
                    {
                        _-a"_-fCPayRatioLevel",
                        _-b""
                    },
                    {
                        _-a"SY__-fNPayRatio",
                        _-b""
                    },
                    {
                        _-a"_-fNDoubleRiskCost",
                        _-b""
                    },
                    {
                        _-a"_-fNDoublePrm",
                        _-b""
                    },
                    {
                        _-a"_-fNDoublePayRatio",
                        _-b""
                    },
                    {
                        _-a"_-fCDoublePayRatioLevel",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNCostRatio",
                        _-b""
                    },
                    {
                        _-a"SY__-fNCostRatio",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNCostRate",
                        _-b""
                    },
                    {
                        _-a"SY__-fNCostRate",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCCostRatioLevel",
                        _-b""
                    },
                    {
                        _-a"SY__-fCCostRatioLevel",
                        _-b""
                    },
                    {
                        _-a"_-fNDoubleCostRate",
                        _-b""
                    },
                    {
                        _-a"_-fNDoubleCostRatio",
                        _-b""
                    },
                    {
                        _-a"_-fCDoubleCostRatioLevel",
                        _-b""
                    },
                    {
                        _-a"_-fCCommonFlag",
                        _-b"0"
                    },
                    {
                        _-a"_-fCAppTyp",
                        _-b"A"
                    },
                    {
                        _-a"SY__-fCPlyNo",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCPlyNo",
                        _-b""
                    },
                    {
                        _-a"_-fNEdrPrjNo",
                        _-b""
                    },
                    {
                        _-a"_-fCRelPlyNo",
                        _-b""
                    },
                    {
                        _-a"_-fCProdNo",
                        _-b"0360_0332"
                    },
                    {
                        _-a"_-fCDptCde",
                        _-b"51978201"
                    },
                    {
                        _-a"_-fCSlsCde",
                        _-b""
                    },
                    {
                        _-a"_-fCHandLe",
                        _-b""
                    },
                    {
                        _-a"_-fCBsnsTyp",
                        _-b"1900101"
                    },
                    {
                        _-a"_-fCChaType",
                        _-b""
                    },
                    {
                        _-a"_-fCBsnsSubtyp",
                        _-b""
                    },
                    {
                        _-a"_-fCBrkrCde",
                        _-b"2014O00515"
                    },
                    {
                        _-a"_-fCBrkrName",
                        _-b"淄博安泰汽车销售服务有限公司"
                    },
                    {
                        _-a"_-fCSaleNme",
                        _-b""
                    },
                    {
                        _-a"_-fCAgtAgrNo",
                        _-b"B201637005765"
                    },
                    {
                        _-a"_-fNSubCoNo",
                        _-b""
                    },
                    {
                        _-a"_-fNCommRate",
                        _-b""
                    },
                    {
                        _-a"_-fNCommissionRateUpper",
                        _-b""
                    },
                    {
                        _-a"SY__-fCRenewMrk",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCRenewMrk",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCTrunMrk",
                        _-b"0"
                    },
                    {
                        _-a"SY__-fCTrunMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-fCPlyTyp",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCOrigPlyNo",
                        _-b""
                    },
                    {
                        _-a"SY__-fCOrigPlyNo",
                        _-b""
                    },
                    {
                        _-a"_-fCOrigInsurer",
                        _-b""
                    },
                    {
                        _-a"_-fCAmtCur",
                        _-b""
                    },
                    {
                        _-a"SY__-fNAmt",
                        _-b"0.00"
                    },
                    {
                        _-a"JQ__-fNAmt",
                        _-b"122000.00"
                    },
                    {
                        _-a"_-fNAmtRmbExch",
                        _-b""
                    },
                    {
                        _-a"_-fCPrmCur",
                        _-b""
                    },
                    {
                        _-a"SY__-fNCalcPrm",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNCalcPrm",
                        _-b""
                    },
                    {
                        _-a"SY__-fNPrm",
                        _-b"0.00"
                    },
                    {
                        _-a"JQ__-fNPrm",
                        _-b"0.00"
                    },
                    {
                        _-a"_-fNPrmRmbExch",
                        _-b"1"
                    },
                    {
                        _-a"_-fNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-fCRatioTyp",
                        _-b"D"
                    },
                    {
                        _-a"SY__-fNRatioCoef",
                        _-b"1"
                    },
                    {
                        _-a"JQ__-fNRatioCoef",
                        _-b"1"
                    },
                    {
                        _-a"_-fNSavingAmt",
                        _-b""
                    },
                    {
                        _-a"_-fCPlySts",
                        _-b""
                    },
                    {
                        _-a"_-fTTermnTm",
                        _-b""
                    },
                    {
                        _-a"_-fCInwdMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCCiMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCCiTyp",
                        _-b""
                    },
                    {
                        _-a"_-fNCiJntAmt",
                        _-b""
                    },
                    {
                        _-a"_-fNCiJntPrm",
                        _-b""
                    },
                    {
                        _-a"_-fCLongTermMrk",
                        _-b""
                    },
                    {
                        _-a"_-fTAppTm",
                        _-b"'.date("Y-m-d").'"
                    },
                    {
                        _-a"_-fCOprTyp",
                        _-b""
                    },
                    {
                        _-a"_-fCPrnNo",
                        _-b""
                    },
                    {
                        _-a"_-fCIcCardId",
                        _-b""
                    },
                    {
                        _-a"SY__-fTInsrncBgnTm",
                        _-b"'.$business['BUSINESS_START_TIME'].'"
                    },
                    {
                        _-a"SY__-fTInsrncEndTm",
                        _-b"'.$business['BUSINESS_END_TIME'].'"
                    },
                    {
                        _-a"SY__-fCTmSysCde",
                        _-b"365"
                    },
                    {
                        _-a"JQ__-fTInsrncBgnTm",
                        _-b"'.$mvtalci['MVTALCI_START_TIME'].'"
                    },
                    {
                        _-a"JQ__-fTInsrncEndTm",
                        _-b"'.$mvtalci['MVTALCI_END_TIME'].'"
                    },
                    {
                        _-a"JQ__-fCTmSysCde",
                        _-b"365"
                    },
                    {
                        _-a"SY__-fCUnfixSpc",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCUnfixSpc",
                        _-b""
                    },
                    {
                        _-a"_-fCGrpMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCListorcolMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCMasterMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCPkgNo",
                        _-b""
                    },
                    {
                        _-a"_-fCRegMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCDecMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCJuriCde",
                        _-b""
                    },
                    {
                        _-a"_-fCAgriMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-fCForeignMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCImporexpMrk",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCManualMrk",
                        _-b""
                    },
                    {
                        _-a"SY__-fCManualMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCManualMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCInstMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCVipMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCOpenCoverNo",
                        _-b""
                    },
                    {
                        _-a"_-fCDisptSttlCde",
                        _-b"007001"
                    },
                    {
                        _-a"_-fCDisptSttlOrg",
                        _-b""
                    },
                    {
                        _-a"_-fCOprCde",
                        _-b"'.$this->loginAarray['username'].'"
                    },
                    {
                        _-a"_-fTOprTm",
                        _-b"'.date("Y-m-d").'"
                    },
                    {
                        _-a"_-fCChkCde",
                        _-b""
                    },
                    {
                        _-a"_-fTIssueTm",
                        _-b""
                    },
                    {
                        _-a"_-fTUdrTm",
                        _-b""
                    },
                    {
                        _-a"_-fCUdrDptCde",
                        _-b""
                    },
                    {
                        _-a"_-fCUdrCde",
                        _-b""
                    },
                    {
                        _-a"_-fCUdrMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCRiFacMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCRiChkCde",
                        _-b""
                    },
                    {
                        _-a"_-fCRiMrk",
                        _-b""
                    },
                    {
                        _-a"_-fTNextEdrBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-fTNextEdrEndTm",
                        _-b""
                    },
                    {
                        _-a"_-fTNextEdrUdrTm",
                        _-b""
                    },
                    {
                        _-a"_-fCRemark",
                        _-b""
                    },
                    {
                        _-a"_-fTEdrAppTm",
                        _-b""
                    },
                    {
                        _-a"_-fTEdrBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-fTEdrEndTm",
                        _-b""
                    },
                    {
                        _-a"_-fCEdrMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCEdrType",
                        _-b""
                    },
                    {
                        _-a"_-fCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-fTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-fCUpdCde",
                        _-b""
                    },
                    {
                        _-a"SY__-fTUpdTm",
                        _-b""
                    },
                    {
                        _-a"JQ__-fTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-fNRate",
                        _-b""
                    },
                    {
                        _-a"_-f_-s1",
                        _-b""
                    },
                    {
                        _-a"_-f_-s2",
                        _-b""
                    },
                    {
                        _-a"_-f_-s3",
                        _-b""
                    },
                    {
                        _-a"_-f_-s4",
                        _-b""
                    },
                    {
                        _-a"_-fCLatestMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCBidMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCPrmSts",
                        _-b""
                    },
                    {
                        _-a"_-fNAmtVar",
                        _-b""
                    },
                    {
                        _-a"_-fNCalcPrmVar",
                        _-b""
                    },
                    {
                        _-a"_-fNPrmVar",
                        _-b""
                    },
                    {
                        _-a"_-fNIndemLmtVar",
                        _-b""
                    },
                    {
                        _-a"_-fCAppPrsnCde",
                        _-b""
                    },
                    {
                        _-a"_-fCAppPrsnNme",
                        _-b""
                    },
                    {
                        _-a"_-fCEdrCtnt",
                        _-b""
                    },
                    {
                        _-a"_-fCOcPlyNo",
                        _-b""
                    },
                    {
                        _-a"_-fCRevertMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCEdrRsnBundleCde",
                        _-b""
                    },
                    {
                        _-a"_-fNBefEdrPrjNo",
                        _-b""
                    },
                    {
                        _-a"_-fNBefEdrAmt",
                        _-b""
                    },
                    {
                        _-a"_-fNBefEdrPrm",
                        _-b""
                    },
                    {
                        _-a"_-fCEdrNo",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCEdrNo",
                        _-b""
                    },
                    {
                        _-a"SY__-fCEdrNo",
                        _-b""
                    },
                    {
                        _-a"_-fNPrmDisc",
                        _-b""
                    },
                    {
                        _-a"_-fNDiscRate",
                        _-b""
                    },
                    {
                        _-a"_-fNMaxFeeProp",
                        _-b""
                    },
                    {
                        _-a"_-fCFinTyp",
                        _-b"001"
                    },
                    {
                        _-a"_-fCGrantDptCde",
                        _-b""
                    },
                    {
                        _-a"_-fCVipCus",
                        _-b""
                    },
                    {
                        _-a"_-fNOrigTimes",
                        _-b""
                    },
                    {
                        _-a"_-fCDptAttr",
                        _-b""
                    },
                    {
                        _-a"_-fCSalegrpCde",
                        _-b""
                    },
                    {
                        _-a"_-fCSlsId",
                        _-b"370323105"
                    },
                    {
                        _-a"_-fCSlsTel",
                        _-b"15853358575"
                    },
                    {
                        _-a"_-fCSlsNme",
                        _-b"朱晓雪"
                    },
                    {
                        _-a"_-fCMinUndrDpt",
                        _-b""
                    },
                    {
                        _-a"_-fCMinUndrCls",
                        _-b""
                    },
                    {
                        _-a"_-fCPkgMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCAppStatus",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCImmeffMrk",
                        _-b""
                    },
                    {
                        _-a"SY__-fCImmeffMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCInsrncTm",
                        _-b""
                    },
                    {
                        _-a"_-fNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-fNAllPrm",
                        _-b""
                    },
                    {
                        _-a"_-fCSusBusiness",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCNewFlg",
                        _-b"1"
                    },
                    {
                        _-a"SY__-fCNewFlg",
                        _-b"1"
                    },
                    {
                        _-a"_-fTInsrncTm",
                        _-b""
                    },
                    {
                        _-a"_-fCOprNm",
                        _-b""
                    },
                    {
                        _-a"_-fCSaleTeam",
                        _-b""
                    },
                    {
                        _-a"_-fCAgantPer",
                        _-b""
                    },
                    {
                        _-a"_-fCVisInsure",
                        _-b""
                    },
                    {
                        _-a"_-fCIsTender",
                        _-b""
                    },
                    {
                        _-a"_-fCTenderNo",
                        _-b""
                    },
                    {
                        _-a"_-fTRepstopExtLastEndTm",
                        _-b""
                    },
                    {
                        _-a"_-fCRepstopextStatus",
                        _-b""
                    },
                    {
                        _-a"_-fTRepStopExtBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-fTRepStopExtEndTm",
                        _-b""
                    },
                    {
                        _-a"_-fCRepStopExtRleAppNo",
                        _-b""
                    },
                    {
                        _-a"_-fTUntilDate",
                        _-b""
                    },
                    {
                        _-a"_-fCMkupFlag",
                        _-b""
                    },
                    {
                        _-a"_-fCGrpBaseMrk",
                        _-b""
                    },
                    {
                        _-a"_-fCComputerIp",
                        _-b""
                    },
                    {
                        _-a"_-fCUsbKey",
                        _-b""
                    },
                    {
                        _-a"_-fCPosNo",
                        _-b""
                    },
                    {
                        _-a"_-fCChaNmeCode",
                        _-b"D"
                    },
                    {
                        _-a"_-fCChannelNme",
                        _-b""
                    },
                    {
                        _-a"_-fCNewChaType",
                        _-b"D02"
                    },
                    {
                        _-a"_-fCNewBsnsTyp",
                        _-b"D0201"
                    },
                    {
                        _-a"_-fCServiceCode",
                        _-b"3703E2001001"
                    },
                    {
                        _-a"_-fCTeamCode",
                        _-b"37032312"
                    },
                    {
                        _-a"_-fCTeamName",
                        _-b"山东分公司淄博中支开发区支公司农网业务部"
                    },
                    {
                        _-a"_-fCServiceId",
                        _-b""
                    },
                    {
                        _-a"_-fCPubNetFlag",
                        _-b""
                    },
                    {
                        _-a"_-fCDeptName",
                        _-b""
                    },
                    {
                        _-a"_-fCAppointAreaCode",
                        _-b""
                    },
                    {
                        _-a"_-fCIsFullEndor",
                        _-b""
                    },
                    {
                        _-a"_-fNAdditionalCostRate",
                        _-b""
                    },
                    {
                        _-a"_-fCOfferPlan",
                        _-b"A"
                    },
                    {
                        _-a"_-fCClauseType",
                        _-b"01"
                    },
                    {
                        _-a"_-fCPrmCalcProTyp",
                        _-b""
                    },
                    {
                        _-a"_-fCPriskPremFlag",
                        _-b""
                    },
                    {
                        _-a"_-fNCarLossPrm",
                        _-b""
                    },
                    {
                        _-a"JQ__-fCOfferUseSpc",
                        _-b""
                    },
                    {
                        _-a"SY__-fCOfferUseSpc",
                        _-b""
                    },
                    {
                        _-a"_-fCOperDpt",
                        _-b""
                    },
                    {
                        _-a"_-fCPayAgreement",
                        _-b""
                    },
                    {
                        _-a"_-fNIncrementRate",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNNoTaxPrm",
                        _-b""
                    },
                    {
                        _-a"SY__-fNNoTaxPrm",
                        _-b""
                    },
                    {
                        _-a"JQ__-fNAddedTax",
                        _-b""
                    },
                    {
                        _-a"SY__-fNAddedTax",
                        _-b""
                    },
                    {
                        _-a"_-fCDataSrc",
                        _-b""
                    },
                    {
                        _-a"_-fNExpectPayrate",
                        _-b""
                    },
                    {
                        _-a"_-fCFiMrk",
                        _-b""
                    },
                    {
                        _-a"_-fNJsPrm",
                        _-b""
                    },
                    {
                        _-a"_-fNJsAmt",
                        _-b""
                    },
                    {
                        _-a"_-fNJcPrm",
                        _-b""
                    },
                    {
                        _-a"_-fNJcAmt",
                        _-b""
                    },
                    {
                        _-a"_-fCPropertyMrk",
                        _-b""
                    },
                    {
                        _-a"_-fNPropertyPrm",
                        _-b""
                    },
                    {
                        _-a"_-fNPropertyAmt",
                        _-b""
                    },
                    {
                        _-a"_-fCCvrgResult",
                        _-b""
                    }
                ]
            }
        ]
    },
    {
        "isFilter": "false",
        "dwType": "ONLY_DATA",
        "dwName": "prodDef.vhl.Vhl_DW",
        "rsCount": "1",
        "pageSize": "10",
        "pageNo": "1",
        "pageCount": "0",
        "maxCount": "1000",
        "toAddFlag": "false",
        "filterMapList": [

        ],
        "dataObjVoList": [
            {
                "index": "1",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-gNNewPurchaseTaxValue",
                        _-b"99919"
                    },
                    {
                        _-a"_-gNKindredPrice",
                        _-b"0"
                    },
                    {
                        _-a"_-gNKindredPriceTax",
                        _-b"0"
                    },
                    {
                        _-a"_-gCVin",
                        _-b"'.$auto['VIN_NO'].'"
                    },
                    {
                        _-a"_-gCMonDespRate",
                        _-b"'.$auto['ENGINE_NO'].'"
                    },
                    {
                        _-a"_-gNActualValue",
                        _-b"'.$auto['DISCOUNT_PRICE'].'"
                    },
                    {
                        _-a"_-gCLoanVehicleFlag",
                        _-b"0"
                    },
                    {
                        _-a"JQ__-gCQryCde",
                        _-b""
                    },
                    {
                        _-a"SY__-gCQryCde",
                        _-b""
                    },
                    {
                        _-a"_-gCVehlcleFamily",
                        _-b""
                    },
                    {
                        _-a"_-gCModelDesc",
                        _-b""
                    },
                    {
                        _-a"_-gRMarketDate",
                        _-b""
                    },
                    {
                        _-a"_-gNAssignPrice",
                        _-b""
                    },
                    {
                        _-a"_-gNOfferPurChasePrice",
                        _-b""
                    },
                    {
                        _-a"_-gNOfferPurChasePriceMax",
                        _-b"122070.0"
                    },
                    {
                        _-a"_-gNOfferPurChasePriceMin",
                        _-b""
                    },
                    {
                        _-a"_-gCSnModifyPrices",
                        _-b"0"
                    },
                    {
                        _-a"_-gCXnModifyPrices",
                        _-b"0"
                    },
                    {
                        _-a"_-gCFleetMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-gCVhlPkgNO",
                        _-b""
                    },
                    {
                        _-a"_-gCIndustryModelCode",
                        _-b"'.$gCIndustryModelCode.'"
                    },
                    {
                        _-a"_-gCIndustryModelName",
                        _-b"'.$auto['MODEL'].'"
                    },
                    {
                        _-a"_-gCNoticeType",
                        _-b"'.self::attribute('NoticeType',$result).'"
                    },
                    {
                        _-a"_-gCProdPlace",
                        _-b"2"
                    },
                    {
                        _-a"_-gCHfcode",
                        _-b"2"
                    },
                    {
                        _-a"_-gCDragWeight",
                        _-b""
                    },
                    {
                        _-a"_-gCFamilyCode",
                        _-b"'.self::attribute('TradeCode',$result).'"
                    },
                    {
                        _-a"_-gCFamilyName",
                        _-b"'.self::attribute('Series',$result).'"
                    },
                    {
                        _-a"_-gCEcdemicMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-gCDevice1Mrk",
                        _-b"0"
                    },
                    {
                        _-a"_-gCNewVhlFlag",
                        _-b"1"
                    },
                    {
                        _-a"_-gCNewMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-gCFstRegYm",
                        _-b"'.$auto['ENROLL_DATE'].'"
                    },
                    {
                        _-a"_-gCFrmNo",
                        _-b"'.$auto['VIN_NO'].'"
                    },
                    {
                        _-a"_-gCModelNme",
                        _-b"'.$auto['MODEL'].'"
                    },
                    {
                        _-a"CarModel",
                        _-b""
                    },
                    {
                        _-a"searcheVehicleModel",
                        _-b""
                    },
                    {
                        _-a"queryPlateCarInfo",
                        _-b""
                    },
                    {
                        _-a"_-gCBrandId",
                        _-b""
                    },
                    {
                        _-a"_-gCModelCde",
                        _-b"'.$auto['MODEL_CODE'].'"
                    },
                    {
                        _-a"_-gCModelCde2",
                        _-b""
                    },
                    {
                        _-a"CarSerachValidate",
                        _-b""
                    },
                    {
                        _-a"CarSerachConfirm",
                        _-b""
                    },
                    {
                        _-a"_-gCSearchCode",
                        _-b""
                    },
                    {
                        _-a"_-gCValidateCode",
                        _-b""
                    },
                    {
                        _-a"_-gCPlateNo",
                        _-b"'.$auto['LICENSE_NO'].'"
                    },
                    {
                        _-a"_-gCEngNo",
                        _-b"'.$auto['ENGINE_NO'].'"
                    },
                    {
                        _-a"_-gNDisplacement",
                        _-b"'.$auto['ENGINE'].'"
                    },
                    {
                        _-a"_-gCPlateTyp",
                        _-b"02"
                    },
                    {
                        _-a"_-gNNewPurchaseValue",
                        _-b"'.$auto['BUYING_PRICE'].'"
                    },
                    {
                        _-a"_-gNDiscussActualValue",
                        _-b"'.$auto['DISCOUNT_PRICE'].'"
                    },
                    {
                        _-a"JQ__-gCUsageCde",
                        _-b"309001"
                    },
                    {
                        _-a"JQ__-gCVhlTyp",
                        _-b"'.$JQ__gCVhlTyp.'"
                    },
                    {
                        _-a"_-gCCarAge",
                        _-b"'.$gCCarAge.'"
                    },
                    {
                        _-a"SY__-gCUsageCde",
                        _-b"309001"
                    },
                    {
                        _-a"SY__-gCVhlTyp",
                        _-b"'.$SY__gCVhlTyp.'"
                    },
                    {
                        _-a"SY__-g_-s6",
                        _-b"11"
                    },
                    {
                        _-a"_-gCRegVhlTyp",
                        _-b"K33"
                    },
                    {
                        _-a"_-gCCardDetail",
                        _-b"K33"
                    },
                    {
                        _-a"_-gCNatOfBusines",
                        _-b"359002"
                    },
                    {
                        _-a"_-gNTonage",
                        _-b"0"
                    },
                    {
                        _-a"_-gNSeatNum",
                        _-b"'.$auto['SEATS'].'"
                    },
                    {
                        _-a"_-gTTransferDate",
                        _-b""
                    },
                    {
                        _-a"_-gCBillDate",
                        _-b""
                    },
                    {
                        _-a"_-gNPoWeight",
                        _-b"'.$KERB_MASS.'"
                    },
                    {
                        _-a"_-gCDisplacementLvl",
                        _-b""
                    },
                    {
                        _-a"_-gCTaxItemCde",
                        _-b""
                    },
                    {
                        _-a"_-gCFuelType",
                        _-b"0"
                    }
                ]
            }
        ]
    },
    {
        "isFilter": "false",
        "dwType": "GRID_CVRG",
        "dwName": "prodDef.vhl.Cvrg_DW",
        "rsCount": "1",
        "pageSize": "10",
        "pageNo": "1",
        "pageCount": "0",
        "maxCount": "1000",
        "toAddFlag": "false",
        "filterMapList": [

        ],
        "dataObjVoList": [
            {
                "index": "1",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"1"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036001"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"'.$business['POLICY']['TVDI_INSURANCE_AMOUNT'].'"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"0"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.15"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b"'.$business['POLICY']['TVDI_INSURANCE_AMOUNT'].'"
                    }
                ]
            },
            {
                "index": "2",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"2"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036002"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"'.$business['POLICY']['TTBLI_INSURANCE_AMOUNT'].'"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b"'.$business['POLICY']['TTBLI_INSURANCE_AMOUNT'].'"
                    },
                    {
                        _-a"_-lNRate",
                        _-b""
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b"306006007"
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.15"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "3",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"3"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036003"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"'.$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'].'"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b"'.$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'].'"
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b"1"
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"0"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.15"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "4",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"4"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036004"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"'.$business['POLICY']['TCPLI_PASSENGER_COUNT']*$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'].'"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b"'.$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'].'"
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b"'.$business['POLICY']['TCPLI_PASSENGER_COUNT'].'"
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"0"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.15"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "5",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"5"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036005"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"'.$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'].'"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"0"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.2"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b"'.$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'].'"
                    }
                ]
            },
            {
                "index": "21",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"21"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"033201"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"122000"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b""
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"0"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b""
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "6",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"6"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036006"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b""
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b""
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"0"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b"303011001"
                    },
                    {
                        _-a"_-l_-s29",
                        _-b"0"
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b""
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "7",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"7"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036007"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"'.$business['POLICY']['SLOI_INSURANCE_AMOUNT'].'"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"2"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.2"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b"'.$business['POLICY']['SLOI_INSURANCE_AMOUNT'].'"
                    }
                ]
            },
            {
                "index": "8",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"8"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036008"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"'.$business['POLICY']['NIELI_INSURANCE_AMOUNT'].'"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b""
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.2"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "9",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"9"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036009"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b""
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b""
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b"'.$business['POLICY']['RDCCI_INSURANCE_UNIT'].'"
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b"'.$business['POLICY']['RDCCI_INSURANCE_QUANTITY'].'"
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b""
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.2"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "12",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"12"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036012"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b""
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"2"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.2"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "13",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"13"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036013"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b"2000"
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369003"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b"'.$business['POLICY']['BSDI_INSURANCE_AMOUNT'].'"
                    },
                    {
                        _-a"_-lNRate",
                        _-b""
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b"N03001001"
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b"0.15"
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "22",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"22"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036022"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b""
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b""
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"0.1"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b""
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            },
            {
                "index": "24",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-lCCancelMrk",
                        _-b"0"
                    },
                    {
                        _-a"_-lNSeqNo",
                        _-b"24"
                    },
                    {
                        _-a"_-lCPkId",
                        _-b""
                    },
                    {
                        _-a"_-lCCvrgNo",
                        _-b"036024"
                    },
                    {
                        _-a"_-lNAmt",
                        _-b""
                    },
                    {
                        _-a"_-lCDductMrk",
                        _-b"369004"
                    },
                    {
                        _-a"_-lNBasePrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNPerAmt",
                        _-b""
                    },
                    {
                        _-a"_-lNLiabDaysLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNIndemLmt",
                        _-b""
                    },
                    {
                        _-a"_-lNRate",
                        _-b"0"
                    },
                    {
                        _-a"_-lCRowId",
                        _-b""
                    },
                    {
                        _-a"_-lCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-lTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDeductible",
                        _-b""
                    },
                    {
                        _-a"_-lCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-lTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-lTBgnTm",
                        _-b""
                    },
                    {
                        _-a"_-lTEndTm",
                        _-b""
                    },
                    {
                        _-a"_-lNDisCoef",
                        _-b""
                    },
                    {
                        _-a"_-l_-s30",
                        _-b""
                    },
                    {
                        _-a"_-l_-s29",
                        _-b""
                    },
                    {
                        _-a"_-l_-s12",
                        _-b""
                    },
                    {
                        _-a"_-l_-s1",
                        _-b""
                    },
                    {
                        _-a"_-lCIndemLmtLvl",
                        _-b""
                    },
                    {
                        _-a"_-lNDductRate",
                        _-b""
                    },
                    {
                        _-a"_-l_-u1",
                        _-b""
                    },
                    {
                        _-a"_-lNPerPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNDductPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNBefPrm",
                        _-b""
                    },
                    {
                        _-a"_-lNVhlActVal",
                        _-b""
                    }
                ]
            }
        ]
    },
    {
        "isFilter": "false",
        "dwType": "ONLY_DATA",
        "dwName": "prodDef.vhl.PrmCoef_DW",
        "rsCount": "1",
        "pageSize": "10",
        "pageNo": "1",
        "pageCount": "0",
        "maxCount": "1000",
        "toAddFlag": "false",
        "filterMapList": [

        ],
        "dataObjVoList": [
            {
                "index": "1",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"JQ__-mNDiscountAmount",
                        _-b""
                    },
                    {
                        _-a"JQ__-mNCoef",
                        _-b""
                    },
                    {
                        _-a"JQ__-mNPriPre",
                        _-b""
                    },
                    {
                        _-a"SY__-mCAppNo",
                        _-b""
                    },
                    {
                        _-a"SY__-mCPlyNo",
                        _-b""
                    },
                    {
                        _-a"JQ__-mCAppNo",
                        _-b""
                    },
                    {
                        _-a"JQ__-mCPlyNo",
                        _-b""
                    },
                    {
                        _-a"_-mCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-mTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-mCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-mTUpdTm",
                        _-b""
                    },
                    {
                        _-a"JQ__-mNClaimTime",
                        _-b""
                    },
                    {
                        _-a"SY__-mNClaimTime",
                        _-b""
                    },
                    {
                        _-a"JQ__-mNTotalClaimAmount",
                        _-b""
                    },
                    {
                        _-a"SY__-mNTotalClaimAmount",
                        _-b""
                    },
                    {
                        _-a"_-mNManualProduct",
                        _-b""
                    },
                    {
                        _-a"_-mNPreChannelFactor",
                        _-b""
                    },
                    {
                        _-a"_-mNPreUnderFactor",
                        _-b""
                    },
                    {
                        _-a"_-mNDrinkDriRiseRat",
                        _-b"0"
                    },
                    {
                        _-a"_-mNProcesseNum",
                        _-b"0"
                    },
                    {
                        _-a"_-mNProcesseNumB",
                        _-b"0"
                    },
                    {
                        _-a"_-mNAllDrinkRiseRat",
                        _-b"0"
                    },
                    {
                        _-a"_-mNLllegalNum",
                        _-b"0"
                    },
                    {
                        _-a"_-mNDrinkDriRiseRatB",
                        _-b"0.15"
                    },
                    {
                        _-a"_-mNLllegalNumB",
                        _-b"0"
                    },
                    {
                        _-a"_-mNUnProcesseNum",
                        _-b""
                    },
                    {
                        _-a"_-mNDrunkDri",
                        _-b"0"
                    },
                    {
                        _-a"_-mNUnProcesseNumB",
                        _-b""
                    },
                    {
                        _-a"_-mNSpeedNum",
                        _-b"0"
                    },
                    {
                        _-a"_-mNBreakRul",
                        _-b"0"
                    },
                    {
                        _-a"_-mNOverloadNum",
                        _-b"0"
                    },
                    {
                        _-a"_-mNNoGood",
                        _-b"0"
                    },
                    {
                        _-a"_-mNOtherNum",
                        _-b"0"
                    },
                    {
                        _-a"JQ__-mCNdiscRsn",
                        _-b"0"
                    },
                    {
                        _-a"_-mNDeathToll",
                        _-b"0"
                    },
                    {
                        _-a"_-mNLyRepRiseRat",
                        _-b"1"
                    },
                    {
                        _-a"_-mNOneYearNoDanger",
                        _-b""
                    },
                    {
                        _-a"_-mNRecordRiseRat",
                        _-b"1"
                    },
                    {
                        _-a"_-mCSafetyViola",
                        _-b"00"
                    },
                    {
                        _-a"_-mCAccidentInfo",
                        _-b"00"
                    },
                    {
                        _-a"_-mCDangerInfo",
                        _-b"00"
                    },
                    {
                        _-a"SY__-mNDiscountAmount",
                        _-b""
                    },
                    {
                        _-a"SY__-mNCoef",
                        _-b""
                    },
                    {
                        _-a"SY__-mNPriPre",
                        _-b""
                    },
                    {
                        _-a"_-mCOfferPlan",
                        _-b"A"
                    },
                    {
                        _-a"_-mNNoLossRat",
                        _-b""
                    },
                    {
                        _-a"SY__-mNTrafficViolateRat",
                        _-b""
                    },
                    {
                        _-a"SY__-mNCarTypeRat",
                        _-b""
                    },
                    {
                        _-a"SY__-mNChannelFactor",
                        _-b""
                    },
                    {
                        _-a"SY__-mNIndeptUnderRat",
                        _-b""
                    }
                ]
            }
        ]
    },
    {
        "isFilter": "false",
        "dwType": "ONLY_DATA",
        "dwName": "prodDef.vhl.Vhlowner_DW",
        "rsCount": "1",
        "pageSize": "10",
        "pageNo": "1",
        "pageCount": "0",
        "maxCount": "1000",
        "toAddFlag": "false",
        "filterMapList": [

        ],
        "dataObjVoList": [
            {
                "index": "1",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-jCOwnerCde",
                        _-b""
                    },
                    {
                        _-a"_-jCCrtCde",
                        _-b""
                    },
                    {
                        _-a"_-jTCrtTm",
                        _-b""
                    },
                    {
                        _-a"_-jCUpdCde",
                        _-b""
                    },
                    {
                        _-a"_-jTUpdTm",
                        _-b""
                    },
                    {
                        _-a"_-j_-s1",
                        _-b"1"
                    },
                    {
                        _-a"_-jCOwnerNme",
                        _-b"孙海月"
                    },
                    {
                        _-a"_-jCOwnerAge",
                        _-b"'.$jCOwnerAge.'"
                    },
                    {
                        _-a"_-jCGender",
                        _-b"'.$jCGender.'"
                    },
                    {
                        _-a"_-jCCertfCls",
                        _-b""
                    },
                    {
                        _-a"_-jCCertfCde",
                        _-b""
                    },
                    {
                        _-a"_-jCCOwnerTyp",
                        _-b"1"
                    }
                ]
            }
        ]
    },
    {
        "isFilter": "false",
        "dwType": "ONLY_DATA",
        "dwName": "prodDef.vhl.Applicant_DW",
        "rsCount": "1",
        "pageSize": "10",
        "pageNo": "1",
        "pageCount": "0",
        "maxCount": "1000",
        "toAddFlag": "false",
        "filterMapList": [

        ],
        "dataObjVoList": [
            {
                "index": "1",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-hCAppNme",
                        _-b"孙海月"
                    }
                ]
            }
        ]
    },
    {
        "isFilter": "false",
        "dwType": "ONLY_DATA",
        "dwName": "prodDef.vhl.Insured_DW",
        "rsCount": "1",
        "pageSize": "10",
        "pageNo": "1",
        "pageCount": "0",
        "maxCount": "1000",
        "toAddFlag": "false",
        "filterMapList": [

        ],
        "dataObjVoList": [
            {
                "index": "1",
                "selected": "true",
                "status": "INSERTED",
                "attributeVoList": [
                    {
                        _-a"_-iCInsuredNme",
                        _-b"孙海月"
                    }
                ]
            }
        ]
    },
    {
        "isFilter": "false",
        "dwType": "ONLY_DATA",
        "dwName": "prodDef.vhl.VsTax_DW",
        "rsCount": "1",
        "pageSize": "10",
        "pageNo": "1",
        "pageCount": "0",
        "maxCount": "1000",
        "toAddFlag": "false",
        "filterMapList": [

        ],
         "dataObjVoList": [
            {
                "index": "1",
                "selected": "false",
                "status": "UPDATED",
                "attributeVoList": [
                    {
                        _-a"VsTax.CAppNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CPlyNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.NEdrPrjNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CEdrNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CVsTaxMrk",
                        _-b"N"
                    },
                    {
                        _-a"VsTax.CAbateMrk",
                        _-b"002"
                    },
                    {
                        _-a"VsTax.CAbateProp",
                        _-b"0"
                    },
                    {
                        _-a"VsTax.CAbateAmt",
                        _-b"0.00"
                    },
                    {
                        _-a"VsTax.NCurbWt",
                        _-b"'.$KERB_MASS.'"
                    },
                    {
                        _-a"VsTax.CTaxpayerId",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxItemCde",
                        _-b"'.$VsTaxCTaxItemCde.'"
                    },
                    {
                        _-a"VsTax.TLastSaliEndDate",
                        _-b"'.date("Y-m-d").'"
                    },
                    {
                        _-a"VsTax.CLastSaliInsurerCde",
                        _-b""
                    },
                    {
                        _-a"VsTax.CLastSaliPlyNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.NTaxableMonths",
                        _-b"12"
                    },
                    {
                        _-a"VsTax.NTaxableAmt",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxYear",
                        _-b"2017"
                    },
                    {
                        _-a"VsTax.CLastTaxYear",
                        _-b"2016"
                    },
                    {
                        _-a"VsTax.NLastYearTaxableMonths",
                        _-b"12"
                    },
                    {
                        _-a"VsTax.NLastYear",
                        _-b""
                    },
                    {
                        _-a"VsTax.COverdueDays",
                        _-b""
                    },
                    {
                        _-a"VsTax.COverdueFineProp",
                        _-b""
                    },
                    {
                        _-a"VsTax.NAggTax",
                        _-b""
                    },
                    {
                        _-a"VsTax.CChargeProp",
                        _-b""
                    },
                    {
                        _-a"VsTax.CChargeAmt",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxPaymentRecptNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxAuthorities",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxReliefCertNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxpayerCertTyp",
                        _-b"01"
                    },
                    {
                        _-a"VsTax.CTaxpayerCertNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxpayerNme",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxpayerAddr",
                        _-b""
                    },
                    {
                        _-a"VsTax.CPostalCode",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTel",
                        _-b""
                    },
                    {
                        _-a"VsTax.CCitizenship",
                        _-b""
                    },
                    {
                        _-a"VsTax.CMicrokitCode",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTransferCarMrk",
                        _-b""
                    },
                    {
                        _-a"VsTax.TTransferDate",
                        _-b""
                    },
                    {
                        _-a"VsTax.CPayTaxMrk",
                        _-b""
                    },
                    {
                        _-a"VsTax.COriginalVhlPlateNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxUseType",
                        _-b""
                    },
                    {
                        _-a"VsTax.CBrandName",
                        _-b""
                    },
                    {
                        _-a"VsTax.CModelCode",
                        _-b""
                    },
                    {
                        _-a"VsTax.CLatestMrk",
                        _-b""
                    },
                    {
                        _-a"VsTax.CCrtCde",
                        _-b""
                    },
                    {
                        _-a"VsTax.TCrtTm",
                        _-b""
                    },
                    {
                        _-a"VsTax.CUpdCde",
                        _-b""
                    },
                    {
                        _-a"VsTax.TUpdTm",
                        _-b""
                    },
                    {
                        _-a"VsTax.CAbateRsn",
                        _-b""
                    },
                    {
                        _-a"VsTax.CNotpayNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CSubTaxItemCde",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxUnit",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxableTyp",
                        _-b""
                    },
                    {
                        _-a"VsTax.NNewcarMon",
                        _-b""
                    },
                    {
                        _-a"VsTax.CShortTyp",
                        _-b""
                    },
                    {
                        _-a"VsTax.NShortMon",
                        _-b""
                    },
                    {
                        _-a"VsTax.TLastSaliBgnDate",
                        _-b""
                    },
                    {
                        _-a"VsTax.NOverdueAmt",
                        _-b"0.00"
                    },
                    {
                        _-a"VsTax.TTaxEffBgnTm",
                        _-b""
                    },
                    {
                        _-a"VsTax.TTaxEffEndTm",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxdptVhltyp",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxBelongTm",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxVchNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxVchTyp",
                        _-b""
                    },
                    {
                        _-a"VsTax.CPaytaxTyp",
                        _-b"T"
                    },
                    {
                        _-a"VsTax.CTaxAuthCde",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxCountryCde",
                        _-b""
                    },
                    {
                        _-a"VsTax.CIsCommissionTax",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxfreeVhltyp",
                        _-b""
                    },
                    {
                        _-a"VsTax.CVehicleNumber",
                        _-b""
                    },
                    {
                        _-a"VsTax.TCertificateDate",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxFreeCertNo",
                        _-b""
                    },
                    {
                        _-a"VsTax.CFreeTaxOrg",
                        _-b""
                    },
                    {
                        _-a"VsTax.NBalanceTax",
                        _-b"0.00"
                    },
                    {
                        _-a"VsTax.NAggTaxVar",
                        _-b"0.00"
                    },
                    {
                        _-a"VsTax.NBefEdrTax",
                        _-b"0.00"
                    },
                    {
                        _-a"VsTax.CAreaCde",
                        _-b""
                    },
                    {
                        _-a"VsTax.CCountry",
                        _-b""
                    },
                    {
                        _-a"VsTax.CProvince",
                        _-b""
                    },
                    {
                        _-a"VsTax.CCity",
                        _-b""
                    },
                    {
                        _-a"VsTax.CCounty",
                        _-b""
                    },
                    {
                        _-a"VsTax.CLastYearTaxTyp",
                        _-b""
                    },
                    {
                        _-a"VsTax.NAnnUnitTaxAmt",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxAddr",
                        _-b""
                    },
                    {
                        _-a"VsTax.NBeforTax",
                        _-b""
                    },
                    {
                        _-a"VsTax.NBeforUnitTax",
                        _-b""
                    },
                    {
                        _-a"VsTax.TBillDate",
                        _-b""
                    },
                    {
                        _-a"VsTax.NOverdueDays",
                        _-b"164"
                    },
                    {
                        _-a"VsTax.NOverdueFineProp",
                        _-b"0.0005"
                    },
                    {
                        _-a"VsTax.NChargeProp",
                        _-b"0.05"
                    },
                    {
                        _-a"VsTax.NChargeAmt",
                        _-b"36"
                    },
                    {
                        _-a"VsTax.NExhaustCapacity",
                        _-b""
                    },
                    {
                        _-a"VsTax.NLimitLoadPerson",
                        _-b""
                    },
                    {
                        _-a"VsTax.CDepartmentNonLocal",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxSign",
                        _-b""
                    },
                    {
                        _-a"VsTax.CPaytaxFlag",
                        _-b""
                    },
                    {
                        _-a"VsTax.CPayID",
                        _-b""
                    },
                    {
                        _-a"VsTax.CUpTaxSignFlag",
                        _-b""
                    },
                    {
                        _-a"VsTax.TSaliAppDate",
                        _-b""
                    },
                    {
                        _-a"VsTax.TVhlRgstDate",
                        _-b""
                    },
                    {
                        _-a"VsTax.CFreeType",
                        _-b""
                    },
                    {
                        _-a"VsTax.CDeclareStatusIA",
                        _-b""
                    },
                    {
                        _-a"VsTax.CCalcTaxFlag",
                        _-b""
                    },
                    {
                        _-a"VsTax.CDrawbackOpr",
                        _-b""
                    },
                    {
                        _-a"VsTax.CDrawbackOprMonth",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxConditionCde",
                        _-b""
                    },
                    {
                        _-a"VsTax.TDeclareDate",
                        _-b""
                    },
                    {
                        _-a"VsTax.NTaxDue",
                        _-b""
                    },
                    {
                        _-a"VsTax.NCurTotalAmount",
                        _-b""
                    },
                    {
                        _-a"VsTax.CTaxOrg",
                        _-b""
                    }
                ]
            }
        ]
    }
]';

	//     $data = json_decode($str,true);
	//    	unset($data[2]['dataObjVoList'][0]);
	//    	unset($data[2]['dataObjVoList'][1]);
	//    	unset($data[2]['dataObjVoList'][2]);
	//    	unset($data[2]['dataObjVoList'][3]);
	//    	unset($data[2]['dataObjVoList'][4]);
	//    	unset($data[2]['dataObjVoList'][5]);
	//    	unset($data[2]['dataObjVoList'][6]);
	//    	unset($data[2]['dataObjVoList'][7]);
	//    	unset($data[2]['dataObjVoList'][8]);
	//    	unset($data[2]['dataObjVoList'][9]);
	//    	unset($data[2]['dataObjVoList'][10]);
	//    	unset($data[2]['dataObjVoList'][11]);
	//    	unset($data[2]['dataObjVoList'][12]);
	//    	unset($data[2]['dataObjVoList'][13]);
	//    	unset($data[2]['dataObjVoList'][14]);
	//    	$i=-1;
	//     if(in_array("TVDI",$business['POLICY']['BUSINESS_ITEMS']))
	//     {

	//     					$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "1";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UPDATED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="1";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036001";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="866.90";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 							 if(in_array("TVDI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}


	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="";

	// 				}



	// 				if(in_array("TTBLI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "2";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="2";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036002";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$TTBLI_AMOUNT;
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="1979.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="1268.07";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][32]['_-lNIndemLmt']=$TTBLI_AMOUNT;
	// 						if(in_array("TTBLI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}

	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="306006009";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.40";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="0.39";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="427.47";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.01";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="1118.650000";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="167.80";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	// 				}
	// 			    if(in_array("TCPLI_DRIVER",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "3";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="3";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036003";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 						if(in_array("TCPLI_DRIVER_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}

	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="1";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]=$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];//"10000";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				 if(in_array("TCPLI_PASSENGER",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "4";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="4";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036004";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']*$business['POLICY']['TCPLI_PASSENGER_COUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 						if(in_array("TCPLI_PASSENGER_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]=$business['POLICY']['TCPLI_PASSENGER_COUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]=$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				 if(in_array("TWCDMVI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "5";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="5";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036005";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 						if(in_array("TWCDMVI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['TCPLI_PASSENGER_COUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				  if(in_array("BGAI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "6";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="6";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036006";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']="0.00";//$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 						if(in_array("BGAI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}

	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['TCPLI_PASSENGER_COUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				 if(in_array("SLOI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "7";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="7";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036007";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 						if(in_array("SLOI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['TCPLI_PASSENGER_COUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']=$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				 if(in_array("NIELI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "8";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="8";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036008";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 						if(in_array("NIELI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['TCPLI_PASSENGER_COUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				 if(in_array("RDCCI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "9";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="9";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036009";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['NIELI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";

	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]=$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]=$business['POLICY']['RDCCI_INSURANCE_UNIT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				 if(in_array("SPIRIT",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "11";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="11";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036011";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['SPIRIT_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";

	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 						if(in_array("SPIRIT_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['RDCCI_INSURANCE_UNIT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				 if(in_array("VWTLI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "12";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="12";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036012";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']="0.00";//$business['POLICY']['SPIRIT_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]="";
	// 						if(in_array("VWTLI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['RDCCI_INSURANCE_UNIT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }

	//    				 if(in_array("BSDI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "13";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="13";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036013";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
	// 						if(in_array("BSDI_NDSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 							}
	// 							else
	// 							{
	// 								$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 							}

	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['RDCCI_INSURANCE_UNIT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }

	//    				 if(in_array("STSFS",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "22";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="22";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036022";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']="0.00";
	// 							switch ($business['POLICY']['STSFS_RATE'])
	// 								{
	// 									case 'DOMESTIC':
	// 										$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0.3";
	// 										break;
	// 									case 'IMPORTED':
	// 										$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0.3";
	// 										break;
	// 									case 'JOINT_VENTURE':
	// 										$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0.3";
	// 										break;
	// 								}
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];

	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369003";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['RDCCI_INSURANCE_UNIT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }
	//    				 if(in_array("MVLINFTPSI",$business['POLICY']['BUSINESS_ITEMS']))
	// 				{

	// 						$i++;
	// 						$data[2]['dataObjVoList'][$i]["index"] = "24";
 //                          	$data[2]['dataObjVoList'][$i]["selected"] = "false";
	// 						$data[2]['dataObjVoList'][$i]["status"] = "UNCHANGED";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][0]["_-lCPkId"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][1]["_-lCAppNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][2]["_-lCPlyNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][3]["_-lNEdrPrjNo"] ="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][4]["_-lCEdrNo"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][5]["_-lNSeqNo"]="24";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][6]["_-lCCvrgNo"]="036024";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][7]['_-lNAmt']="0.00";//$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][8]["_-lNRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][9]["_-lNBasePrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][10]["_-lNBefPrm"]="48.30";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][11]["_-lNDisCoef"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][12]["_-lNCalcPrm"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][13]["_-lNPrm"]="30.95";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][14]["_-lNBefAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][15]["_-lNCalcAnnPrm"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][16]["_-lTPrmChgTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][17]["_-lNDutPrm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][18]["_-lCRemark"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][19]["_-lCCancelMrk"]=" 0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][20]["_-lTBgnTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][21]["_-lTEndTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][22]["_-lNAmtVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][23]["_-lNPrmVar"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][24]["_-lNCalcPrmVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][25]["_-lNIndemVar"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][26]["_-lCLatestMrk"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][27]["_-lCCrtCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][28]["_-lTCrtTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][29]["_-lCUpdCde"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][30]["_-lTUpdTm"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][31]["_-lCRowId"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][32]["_-lNIndemLmt"]=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];

	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][33]["_-lCDductMrk"]="369004";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][34]["_-lCCustClCtnt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][35]["_-lCTgtTyp"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][36]["_-lNTgtQty"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][37]["_-lCIndemLmtLvl"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][38]["_-lNLiabDaysLmt"]="";//$business['POLICY']['RDCCI_INSURANCE_QUANTITY'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][39]["_-lNPerAmt"]="";//$business['POLICY']['RDCCI_INSURANCE_UNIT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][40]["_-lNPerIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][41]["_-lNPerPrm"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][42]["_-lNPerPrmShort"]="1721.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][43]["_-lNPerPrmShortDuct"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][44]["_-lNOnceIndemLmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][45]["_-lNSavingAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][46]["_-lCTgtNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][47]["_-lNDductRate"]="0";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][48]["_-lCDductDesc"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][49]["_-lNDductAmt"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][50]["_-lCCustCvrgNme"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][51]["_-l_-s30"]="303011001";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][52]["_-l_-s29"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][53]["_-l_-u1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][54]["_-lNDductPrm"]="258.15";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][55]["_-l_-s1"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][56]["_-l_-u15"]="1102.67";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][57]["_-l_-u16"]="165.406";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][58]["_-l_-u17"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][59]["_-l_-u18"]="";
	// 						$data[2]['dataObjVoList'][$i]['attributeVoList'][60]['_-lNVhlActVal']="";//$business['POLICY']['SLOI_INSURANCE_AMOUNT'];
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][61]["_-lNCvrgPayRatio"]="1.19";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][62]["_-lNCvrgRiskCost"]="5368.10";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][63]["_-lCCvrgPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][64]["_-lNDductPayRatio"]="0.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][65]["_-lNDductRiskCost"]="1.00";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][66]["_-lCDductPayLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][67]["_-lNCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][68]["_-lNCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][69]["_-lCCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][70]["_-lNDuctCostRate"]="0.41";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][71]["_-lNDuctCostRatio"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][72]["_-lCDuctCostRatioLevel"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][73]["_-lNPureRiskPremium"]="3430.346400";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][74]["_-lNNonDeductPureRiskPrm"]="514.55";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][75]["_-lNDeductible"]="";
	// 						$data[2]['dataObjVoList'][$i]["attributeVoList"][76]["_-lNDeductibleDiscount"]="1.00";

	//    				 }

	//     //return urldecode(json_encode($this->url_encode($data)));

	//     /*****************查询模型代码*****************/
	//     $string='http://114.251.1.161/zccx/search?regionCode=00000000&jyFlag=0&businessNature=A&operatorCode=0000000000&returnUrl=http://carply.cic.cn/pcis/offerAcceptResult&vname=&searchVin=LBEHDAEB4EY242822&vinflag=1&validNo=653eb5cdac40e71bf0d954b2e0fe3eef';
	// 	$this->requestGetData($string);
	//    	$code_DATA['vehicleCode']=$auto['INDUSTY_MODEL_CODE'];
	//     $code_DATA['vehicleId']=$auto['INDUSTY_MODEL_CODE'];
	//     $code_DATA['hvinRoot']="";
	//     $code_DATA['hvinFlag']="";
	// 	$mods =$this->requestPostData($this->modelUrl,$code_DATA);
	// 	preg_match_all("/<input(.*)name(.*)=(.*)'hyVehicleCode'(.*)value(.*)=(.*)'(.*)'(.*)\/>/", $mods, $radio);
	// 	$ax = substr($radio[4][0],8,-48);
	// 	$ax = trim($ax);
	// 	$ax=str_replace("&nbsp;", "", $ax);
	// 	$Codes=str_replace("'", "", $ax);

	// $data[1]['dataObjVoList'][0]['attributeVoList'][20]['_-gCIndustryModelCode']=$Codes;
	// $data[0]['dataObjVoList'][0]['attributeVoList'][82]['SY__-fTInsrncBgnTm']=$business['BUSINESS_START_TIME'];
	// $data[0]['dataObjVoList'][0]['attributeVoList'][83]['SY__-fTInsrncEndTm']=$business['BUSINESS_END_TIME'];
	// $data[0]['dataObjVoList'][0]['attributeVoList'][85]['JQ__-fTInsrncBgnTm']=$mvtalci['MVTALCI_START_TIME'];
	// $data[0]['dataObjVoList'][0]['attributeVoList'][86]['JQ__-fTInsrncEndTm']=$mvtalci['MVTALCI_END_TIME'];

	// $data[0]['dataObjVoList'][0]['attributeVoList'][78]['_-fTAppTm']=date("Y-m-d",time());//"2016-09-18";
	// $data[0]['dataObjVoList'][0]['attributeVoList'][109]['_-fTOprTm']=date("Y-m-d",time());//"2016-09-18";
	// $data[7]['dataObjVoList'][0]['attributeVoList'][11]['VsTax.TLastSaliEndDate']=date("Y-m-d",time());//"2016-09-18";

	// $data[1]['dataObjVoList'][0]['attributeVoList'][42]['_-gCPlateNo']=$auto['LICENSE_NO'];//车牌号
	// $data[1]['dataObjVoList'][0]['attributeVoList'][45]['_-gCPlateTyp']="02";//$auto['LICENSE_NO'];//号牌类别
	// $data[4]['dataObjVoList'][0]['attributeVoList'][6]['_-jCOwnerNme']=$auto['OWNER'];//mb_convert_encoding($auto['OWNER'], "utf8","gb2312");//保险人
	// $data[1]['dataObjVoList'][0]['attributeVoList'][3]['_-gCVin']=$auto['VIN_NO'];//车架号
	// $data[1]['dataObjVoList'][0]['attributeVoList'][31]['_-gCFrmNo']=$auto['VIN_NO'];
	// $data[1]['dataObjVoList'][0]['attributeVoList'][43]['_-gCEngNo']=$auto['ENGINE_NO'];//发动机号
	// $data[1]['dataObjVoList'][0]['attributeVoList'][32]['_-gCModelNme']=$auto['MODEL'];//品牌型号
	// $data[1]['dataObjVoList'][0]['attributeVoList'][44]['_-gNDisplacement']=$auto['ENGINE'];//排量
	// $data[1]['dataObjVoList'][0]['attributeVoList'][58]['_-gNSeatNum']=$auto['SEATS'];//核定载客
	// $data[1]['dataObjVoList'][0]['attributeVoList'][61]['_-gNPoWeight']=$auto['KERB_MASS'];//整备质量
	// $data[7]['dataObjVoList'][0]['attributeVoList'][8]['VsTax.NCurbWt']=$auto['KERB_MASS'];
	// $data[1]['dataObjVoList'][0]['attributeVoList'][57]['_-gNTonage']=$auto['TONNAGE'];//核定载质量
	// $data[1]['dataObjVoList'][0]['attributeVoList'][36]['_-gCModelCde']=$auto['MODEL_CODE'];//型号代码
	// $data[1]['dataObjVoList'][0]['attributeVoList'][46]['_-gNNewPurchaseValue']=$auto['BUYING_PRICE'];//新车购置价
	// $data[1]['dataObjVoList'][0]['attributeVoList'][30]['_-gCFstRegYm']=$auto['ENROLL_DATE'];//注册时间
	// $data[1]['dataObjVoList'][0]['attributeVoList'][5]['_-gNActualValue']=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];
	// $data[1]['dataObjVoList'][0]['attributeVoList'][47]['_-gNDiscussActualValue']=$business['POLICY']['TVDI_INSURANCE_AMOUNT'];













	// 		if($auto['VEHICLE_TYPE']=="PASSENGER_CAR" &&  $auto['USE_CHARACTER']=="NON_OPERATING_PRIVATE")//家用车
	// 		{
	// 			if($auto['SEATS']<6)
	// 			{
	// 				$JQ__-gCVhlTyp="302001001";//"302001001";
	// 				$SY__-gCVhlTyp="302001001";//"302001001";
	// 			}
	// 			elseif($auto['SEATS']>=6 || $auto['SEATS']<10)
	// 			{
	// 				$JQ__-gCVhlTyp="302001022";
	// 				$SY__-gCVhlTyp="302001008";
	// 			}
	// 			elseif($auto['SEATS']>=10)
	// 			{
	// 				$JQ__-gCVhlTyp="302001022";
	// 				$SY__-gCVhlTyp="302001016";
	// 			}

	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][54]['_-gCRegVhlTyp']="K33";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][55]['_-gCCardDetail']="K33";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][48]['JQ__-gCUsageCde']="309001";//车辆性质
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][51]['SY__-gCUsageCde']="309001";//车辆性质

	// 		}
	// 		elseif($auto['VEHICLE_TYPE']=="PASSENGER_CAR" && $auto['USE_CHARACTER']=="NON_OPERATING_ENTERPRISE")
	// 		{

	// 			if($auto['SEATS']<6)
	// 			{
	// 				$JQ__-gCVhlTyp="302001001";//"302001001";
	// 				$SY__-gCVhlTyp="302001001";//"302001001";
	// 			}
	// 			elseif($auto['SEATS']>=6 || $auto['SEATS']<10)
	// 			{
	// 				$JQ__-gCVhlTyp="302001022";
	// 				$SY__-gCVhlTyp="302001008";
	// 			}
	// 			elseif($auto['SEATS']>=10)
	// 			{
	// 				$JQ__-gCVhlTyp="302001022";
	// 				$SY__-gCVhlTyp="302001016";
	// 			}
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][54]['_-gCRegVhlTyp']="K33";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][55]['_-gCCardDetail']="K33";
	// 			/***计算折扣月份**/
	// 			$ENROLLhour = date('Y',strtotime($auto['ENROLL_DATE']))*12+(date('m',strtotime($auto['ENROLL_DATE'])));
	// 			$START_TIMEhour = date('Y',strtotime($business['BUSINESS_START_TIME']))*12+(date('m',strtotime($business['BUSINESS_START_TIME'])));
	// 			$Month=$START_TIMEhour-$ENROLLhour;
	// 			if($Month<"12"){
	// 					$gCCarAge="306001";
	// 			}elseif($Month>="12" && $Month<"24"){
	// 					$gCCarAge="306002";
	// 			}elseif($Month>="24" && $Month<"36"){
	// 					$gCCarAge="306003";
	// 			}elseif($Month>="36" && $Month<"48"){
	// 					$gCCarAge="306004";
	// 			}elseif($Month>="48" && $Month<"72"){
	// 					$gCCarAge="306005";
	// 			}elseif($Month>="72"){
	// 					$gCCarAge="306007";
	// 			}
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][48]['JQ__-gCUsageCde']="309004";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][51]['SY__-gCUsageCde']="309004";
	// 			if($business['POLICY']['BSDI_INSURANCE_AMOUNT']!="")
	// 			{
	// 				$data[2]['dataObjVoList'][11]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
	// 				$data[2]['dataObjVoList'][11]['attributeVoList'][32]['_-lNIndemLmt']=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
	// 			}
	// 			if($business['POLICY']['TWCDMVI_INSURANCE_AMOUNT']!="")
	// 			{
	// 				$data[2]['dataObjVoList'][4]['attributeVoList'][4]['_-lNAmt']=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
	// 				$data[2]['dataObjVoList'][4]['attributeVoList'][31]['_-lNVhlActVal']=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
	// 			}

	// 			$data[2]['dataObjVoList'][0]['attributeVoList'][75]['_-lNDeductible']="0.00";//$business['POLICY']['DOC_AMOUNT'];//可选免赔额

	// 		}
	//  		elseif($auto['VEHICLE_TYPE']=="TRUCK" && $auto['USE_CHARACTER']=="NON_OPERATING_TRUCK")
	//  		{
	//  			$JQ__-gCVhlTyp="302002001";
	// 			$SY__-gCVhlTyp="302002001";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][54]['_-gCRegVhlTyp']="H31";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][55]['_-gCCardDetail']="H31";
	// 			$gCCarAge="306002";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][48]['JQ__-gCUsageCde']="309014";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][51]['SY__-gCUsageCde']="309014";
	// 			$data[1]['dataObjVoList'][0]['attributeVoList'][56]['_-gCNatOfBusines']="359002";
	// 			$data[2]['dataObjVoList'][11]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];
	// 			$data[2]['dataObjVoList'][11]['attributeVoList'][32]['_-lNIndemLmt']=$business['POLICY']['BSDI_INSURANCE_AMOUNT'];

	// 		    if($business['POLICY']['TWCDMVI_INSURANCE_AMOUNT']!="")
	// 		    {
	// 		    	$data[2]['dataObjVoList'][4]['attributeVoList'][7]['_-lNAmt']=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
	// 				$data[2]['dataObjVoList'][4]['attributeVoList'][60]['_-lNVhlActVal']=$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
	// 		    }


	//  		}

	return $str;

    }

	/**
	 * [id_age description]
	 * @AuthorHTL
	 * @DateTime  2016-07-18T13:18:29+0800
	 * @param     [type]                   $id_num [身份证]
	 * @return    [type]                           [实际年龄]
	 */
	private  function id_age($id_num)
   	{
   		/*if($id_num=="")
   		{

   			return 0;
   		}*/
        $date=strtotime(substr($id_num,6,8));//获得出生年月日的时间戳
        $today=strtotime('today');//获得今日的时间戳
        $diff=floor(($today-$date)/86400/365);//得到两个日期相差的大体年数
        //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $age=strtotime(substr($id_num,6,8).' +'.$diff.'years')>$today?($diff+1):$diff;
        return $age;
  	}

  	/**
  	 * [sex description]
  	 * @AuthorHTL
  	 * @DateTime  2016-07-19T12:55:22+0800
  	 * @param     [type]                   $id_num [身份证]
  	 * @return    [type]                           [实际性别]
  	 */
  	private function sex($id_num)
  	{
  		$sexint=substr($id_num,16,1);
  		return $sexint % 2 ===0?'女':'男';

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

   private function format_number($num,$cut)
    {
 		if(strlen($num)>3)
    	{
    		return number_format($num,0,'.',$cut);
    	}
    	else
    	{
    		return "0.".$num;
    	}
	}

	private function attribute($key,$result)
    {

    $ModelCode="/<attribute name=\"{$key}\" value=\"(.*)\">(.*)<\/attribute>/U";
    preg_match_all($ModelCode, $result, $ModelCodes);
    return $ModelCodes[2][0];

    }

     /**
      * [trimall 过滤字符串空格]
      * @param  [type] $str [description]
      * @return [type]      [description]
      */
     private function trimall($str)//删除空格
     {
         $qian=array(" ","　","\t","\n","\r","??","<",">","&nbsp;");
         $hou=array("","","","","","","","","");
         return str_replace($qian,$hou,$str);
     }



}
?>