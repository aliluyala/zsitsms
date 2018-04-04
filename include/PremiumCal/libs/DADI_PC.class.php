<?php
/**
 * 项目:           车险保费在线计算接口
 * 文件名:         DADI_PC.class.php
 * 版权所有：      成都启点科技有限公司.
 * 作者：          Liang YuLin
 * 版本：          1.0.0
 *
 * 中国大地保险系统算价接口
 *
 **/

//include(_ROOT_DIR."/include/PremiumCal/CommonPremium.class.php");

/*******关闭notice错误******/
error_reporting(E_ALL^E_NOTICE);

/*******开启SESSION******/
session_start();

class DADI_PC  
{
	/*******定义模板常量******/
	const formFile = 'JS_Calculate.tpl';


	/*******定义保险公司常量******/
	const company  = 'DADI';

	/*******定义置错误信息常量******/
    private $error    = "";//设置错误信息成员属性（默认值）


    /**
     * [$setItems 定义账号入口]
     * @var array
     */
	private $setItems = array(
		'username'  => '归属经办人编号',
		'password'  => '归属经办人密码',
		'AgentCode' =>'代理人代码编号'
	);

	 /**
     * 构造函数
     * 参数:
     * @config          必需。配置 数组
     * @cachePath       必需。缓存目录 绝对路径
     **/
	function __construct($config,$cachePath)
	{ 
		$this->user = '';
		if(array_key_exists('username',$config))
		{
			$this->user = $config['username'];
		}
		$this->password = '';
		if(array_key_exists('password',$config))
		{
			$this->password = $config['password'];
		}
		$this->AgentCode = '';
		if(array_key_exists('AgentCode',$config))
		{
			$this->AgentCode = $config['AgentCode'];
		}

		$this->loginAarray=array(
						'txtMACAddr'=>'txtMACAddr=283A92%3A4A%3A53%3A69%3A75_84%3A4B%3AF5%3A8C%3AA1%3A15_00%3A50%3A56%3AC0%3A00%3A01_00%3A50%3A56%3AC0%3A00%3A08_00%3AFF%3A7D%3AC1%3A17%3AA7_',
						'flag' =>'0',
						'UserCode'=> $this->user,
						'Password'=> $this->password,
						'RiskCode'=>'DDH-%BB%FA%B6%AF%B3%B5%C1%BE%B1%A3%CF%D5%2807%B0%E6%29&',
						'bln_Login.x'=>'31',
						'bln_Login.y'=>'24'

		);//登陆条件
        $this->cache_image='./cache/jiangsuImage/';//存放验证码的目录
		$this->check_filename = $this->cache_image.rand().'_check.jpg';

		$url=array(
				"Index_Url" => "http://10.1.111.99:25011",

				"Price_Url" => "http://10.1.111.99:8015",
			);

		/************登录处理***************/
		$this->check_url= $url['Index_Url']."/ddccallweb/CheckOutImageServlet";
		$this->UrlLogin = $url['Index_Url']."/ddccallweb/common/pub/UILogonSubmit.jsp";

		
		/*******查询基本信息********/
		$this->Url_Verification=$url['Index_Url']."/ddccallweb/indiv/qg/jiaoguan/UIQueryJiaoGuanNewAjaxInquiry.jsp";//验证码查询交管车辆信息
		$this->Url_UIQueryCheck=$url['Index_Url']."/ddccallweb/indiv/qg/jiaoguan/UIQueryJiaoGuanNewAjaxCheck.jsp";//确定车型
		$this->Caculate=$url['Index_Url']."/ddccallweb/NEW_DDG/tbcbpg/UIPrPoEnDDGNewCaculate.jsp";//计算保费
		$this->UICodeGet= $url['Index_Url']."/ddccallweb/common/pub/UICodeGet.jsp";//查询归属机构
		$this->UIPrPoEnAgentAgreementInput= $url['Index_Url']."/ddccallweb/common/tbcbpg/UIPrPoEnAgentAgreementInput.jsp";//查询代理资格与协议
		$this->InputNext=$url['Index_Url']."/ddccallweb/DDG/tbcbpg/UIPrPoEnDDGInputNext.jsp";//保存投保单



		/************查询车型信息*************/
		$this->AcceptResultForBrandName_Url= $url['Price_Url']."/zccx/search?regionCode=32010000&riskCode=DDG&businessNature=0000&operatorCode=8000504539&returnUrl=http://app.ccic-net.com.cn:25011/ddccallweb/common/jy/AcceptResultForBrandName.jsp";
		$this->getVehicle=$url['Price_Url']."/zccx/getVehicle.shtml";
		$this->PurchasePriceUrl= $url['Price_Url']."/zccx/vinDecodingList.shtml";//车辆购置价查询
		$this->modelrice="http://114.251.1.161/zccx/vehicleList.shtml";//品牌名称查询购置价
		$this->search="http://116.228.143.164/jy1/search";//车架号查询购置价
		$this->ZccxServletInvoke="https://116.228.143.164/CPIC09Auto/ZccxServletInvoke&#";
		
		/************设置COOKIE信息*************/
		if(empty($cachePath))
		{
			$this->cookie_file = dirname(__FILE__).'/DADI_cookie.txt';
		}
		else
		{
			$this->cookie_file = $cachePath.'/DADI_cookie.txt';  //COOKIE文件存放地址
		}
		


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

	/**
	 * [requestPostData POST登录以及操作]
	 * @param  [type]  $url  [传递的URL]
	 * @param  [type]  $post [传递的数据变量]
	 * @param  boolean $head [请求头部]
	 * @param  integer $foll [description]
	 * @param  boolean $ref  [description]
	 * @return [type]        [返回数据]
	 */
	private function requestPostData($url,$post,$head=false,$foll=1,$ref=false)
	{
		
		$ret = $this->post($url,$post,$head,$foll,$ref);

		if($ret === -1)
		{
			$check= self::check();

			if(file_exists($check))
			{
				$result= self::POST_ERROR($url,$post);
				if(!$result)
				{
					return 0;
				}
				else
				{
					return $result;
				}
			}
			else
			{
				return 0;
			}	
			
		}
		return $ret;
	}

	/**
	 * [requestGetData GET操作]
	 * @param  [type]  $url  [description]
	 * @param  boolean $head [description]
	 * @param  integer $foll [description]
	 * @param  boolean $ref  [description]
	 * @return [type]        [description]
	 */
	private function requestGetData($url,$head=false,$foll=1,$ref=false)
	{
		$ret = $this->get($url,$head,$foll,$ref);
		return $ret;
	}
	/**
	 * [post    POST_CURL]
	 * @param  [type]  $url  [description]
	 * @param  [type]  $post [description]
	 * @param  boolean $head [description]
	 * @param  integer $foll [description]
	 * @param  boolean $ref  [description]
	 * @return [type]        [description]
	 */
	public function post($url,$post,$head=false,$foll=1,$ref=false)
	{

		//return parent::post($url,$post,$this->cookie_file);



	    $curl = curl_init(); // 启动一个CURL会话
	    if($head){
	    curl_setopt($curl,CURLOPT_HTTPHEADER,$head);//模似请求头
	    }

	    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)');
	    curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate','DNT:1'));
	    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION,$foll); // 使用自动跳转
	    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	    
	    if(!is_array($post))
	    {
	    	curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
	    }
	    else
	    {
	    	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post)); // Post提交的数据包
	    }	
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
	    $result=iconv("GBK", "UTF-8", $tmpInfo);
	    if(strpos($result,"登录超时")!="")
	    {
	    	return -1;
	    }
	    if(empty($tmpInfo))
	    {
	    	return -1;
	    }
	    return $tmpInfo;
	}

   /**
	 * [get GET_CURL]
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
	 * [check 获取登录验证码]
	 * @return [type] [description]
	 */
	public function check()
    {
        
            $result= self::requestGetData($this->check_url);
            if(!file_exists($this->cache_image))
            {
               mkdir($this->cache_image);
            }
             
            self::deldir($this->cache_image);
            file_put_contents($this->check_filename, $result);
            if(file_exists($this->check_filename))
            {
                return $this->check_filename;
            }
    }


    /**
     * [check_login 登录操作]
     * @param  array  $info [description]
     * @return [type]       [description]
     */
	public function check_login($info=array())
	{
		if(isset($info['checkCode']) && $info['checkCode']!="")
		{
			$this->loginAarray['checkCode']=$info['checkCode'];
			$result =self::post($this->UrlLogin,$this->loginAarray);
			$stutes= iconv("GBK", "UTF-8", $result);
			preg_match_all("/alert\(\"(.*)\"\);/", $stutes, $login_error);
			
			if(isset($login_error[0][1]) && strpos($login_error[0][1],"登录成功")!="")
			{
				$json= self::requestPostData($_SESSION['DADI_URL'],$_SESSION['DADI_POST']);
				$jsons= json_decode($json,true);
				$arr=array();
				$checkCodeData= str_replace("\/", "", $jsons['checkCodeData']);
				$arr['checkcode']=$checkCodeData;
				$arr['checkno']=$jsons['checkNo'];
				return $arr;
			}
			else
			{
				$this->error['errorMsg']=$login_error[1][1];
				return false;
			}	
		}
		else
		{
				$this->error['errorMsg']="验证码查询交管车辆信息为空";
				return false;
		}	


	}
  

	/**
	 * [premium 请求算价]
	 * @param  array  $auto     [description]
	 * @param  array  $business [description]
	 * @param  array  $mvtalci  [description]
	 * @return [type]           [description]
	 */
	public  function  premium($auto=array(),$business=array(),$mvtalci=array())
	{

			//查询正确车型
			$data["licenseNo"]="";
			$data["licenseType"]="02";
			$data["vinNo"]=$auto['VIN_NO'];
			$data["engineNo"]=$auto['ENGINE_NO'];
			$data["enrollDate"]=$auto['ENROLL_DATE'];
			$data["vehicleModel"]=$auto['model'];
			$data["businessNature"]="0101";
			$data["startDate"]=$business['BUSINESS_START_TIME'];
			$data["useNatureCode"]="85";
			$data["carKindCode"]="A0";
			$data["noLicenseFlag"]="0";
			$data["ecdemicVehicleFlag"]="0";
			$data["industryModelCode"]=$auto['INDUSTY_CODE'];
			$data["tonCount"]="0";
			$data["seatCount"]="0";
			$data["exhaustScale"]=$auto['ENGINE'];
			$data["powerScale"]="89.0";
			$data["purchasePrice"]=$auto['BUYING_PRICE'];
		
			$where=array();
			foreach($data as $k=>$v)
			{
				$where[$k]=iconv("UTF-8", "GB2312", $v);
			}
			
			$resu= self::requestPostData($this->Url_UIQueryCheck,$where);
			$QueryCheck= json_decode(iconv("GBK", "UTF-8", $resu),true);

			if($QueryCheck['carStyle']!="2")
			{
				$_SESSION['car_traffic']['carStyle']="B";
			}
			else
			{
				$_SESSION['car_traffic']['carStyle']="A";
			}	


			if(isset($QueryCheck['errorMessage']))
			{
				$this->error['errorMsg']=$QueryCheck['errorMessage'];
				return false;
			}

			if($QueryCheck['vehicleHyCode']!=$auto['INDUSTY_CODE'])
			{	
				unset($_SESSION['car_traffic']['carModels']);
				$this->error['errorMsg']="当前车型行业代码与录入的行业车险代码不同，以自动切换正确车型，请重新查询购置价";
				foreach($QueryCheck['carModels'] as $k =>$v)
				{

					$_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleId']=$v['vehicleCode'];//车辆代码
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleName']=$v['vehicleName'];//车辆名称
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleAlias']=$v['vehicleHyName'];//车辆别名
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleSeat']=$v['seat'];//额定载客
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleExhaust']=$v['displacement'];//车辆排量
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleWeight']=$v['fullWeight'];//车辆额定载质量
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleTonnage']=$v['tonnage'];//车辆额定载质量
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleYear']=$v['marketDate'];//上市年份
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehiclePrice']=$v['price'];//车辆新车购置价
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['szxhTaxedPrice']=$v['purchasePriceTax'];//车辆（含税）新车购置价
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['industryModelCode']=$v['vehicleHyCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['priceType']=$v['priceType'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['airbagNum']=$v['airbagNum'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['antiTheft']=$v['antiTheft'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['brandCode']=$v['brandCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['brandName']=$v['brandName'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]['factoryCode']=$v['factoryCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["factoryName"]=$v['factoryName'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["familyCode"]=$v['familyCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["familyName"]=$v['familyName'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["fitsRiskRate"]=$v['fitsRiskRate'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["fullWeight"]=$v['fullWeight'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["gearboxType"]=$v['gearboxType'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["groupFitsRate"]=$v['groupFitsRate'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["groupRepairRate"]=$v['groupRepairRate'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["hfCode"]=$v['hfCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["hfName"]=$v['hfName'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["industryVehicleCode"]=$v['vehicleHyCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["power"]=$v['power'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["pricetype"]=$v['pricetype'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["purchasePriceTax"]=$v['purchasePriceTax'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["rate"]=$v['rate'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["remark"]=$v['remark'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["riskFlags"]=$v['riskFlags'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["searchCode"]=$v['searchCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["stopFlag"]=$v['stopFlag'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleClassCode"]=$v['vehicleClassCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleClassName"]=$v['vehicleClassName'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleHyCode"]=$v['vehicleHyCode'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleHyName"]=$v['vehicleHyName'];
		            $_SESSION['car_traffic']['carModels']['rows'][$k]["carRemark"]=$v['carRemark'];
				}


					return false;
			}



			unset($_SESSION['BUSINESS']);//删除历史险种信息
            unset($_SESSION['Auditing']);
            unset($_SESSION['MVTALCI']);//删除交强险信息
			$arr= self::datas($auto,$business,$mvtalci);

			
			$result= self::requestPostData($this->Caculate,$arr);
			$results= iconv("GB2312", "UTF-8", $result);

			$preg="/alert\((.*)\)/";//匹配
			preg_match_all($preg, $results, $matches);
			
			if(isset($matches[1][0]) && $matches[1][0]!="")
			{
				
				$this->error['errorMsg']=$matches[1][0];
				return false;
			}
			
			$peg="/[^=] parent.fraInterface.fm.BenchMarkPremium(.*)/";
			preg_match_all($peg, $results, $matches);

			$succ=array();
			foreach($matches[1] as $k=>$v)
			{
			    $succ[]=explode("=", self::trimall(trim($v)));
			}

			 //设置算价返回默认值
            $PREMIUMS=array();
            $PREMIUMS['MESSAGE']                      = '';
            $PREMIUMS['MVTALCI'] = array();
            $PREMIUMS['MVTALCI']['TRAVEL_TAX_PREMIUM']= '0.00';
            $PREMIUMS['MVTALCI']['MVTALCI_PREMIUM']   = '0.00';
            $PREMIUMS['MVTALCI']['MVTALCI_DISCOUNT']  = '1.000';
            $PREMIUMS['MVTALCI']['MVTALCI_START_TIME']= '';
            $PREMIUMS['MVTALCI']['MVTALCI_END_TIME']  = '';
            $BUSINESS_PREMIUM="";
            $STANDARD_PREMIUM="";
            if(!empty($mvtalci))
            {

               /*******************交强险********************/
               $AnnualTaxDue="/parent.fraInterface.fm.AnnualTaxDue.value =(.*)/";
               preg_match_all($AnnualTaxDue, $results, $AnnualTaxDues);
               if($AnnualTaxDues[1][0]!="")
               {

	               	if($_SESSION['car_traffic']['carModels']['rows'][0]["hfName"]=="减半")
	               	{
	               		$PREMIUMS['MVTALCI']['TRAVEL_TAX_PREMIUM']= self::trimall($AnnualTaxDues[1][0])/2;       //车船税
	               	}
	               	else
	               	{
	               		$PREMIUMS['MVTALCI']['TRAVEL_TAX_PREMIUM']= self::trimall($AnnualTaxDues[1][0]);       //车船税
	               	}	
	               	
               		$_SESSION['MVTALCI']['TRAVEL_TAX_PREMIUM']=$PREMIUMS['MVTALCI']['TRAVEL_TAX_PREMIUM'];
               }

                $quotationNoCI="/parent.fraInterface.fm.quotationNoCI.value=\"(.*)\"/";
                preg_match_all($quotationNoCI, $results, $NoCI_matches);
                if($NoCI_matches[1][0]!="")
                {
                    $PREMIUMS['MESSAGE'] .="交强险报价单号：".$NoCI_matches[1][0]."<br/>";//商业险投保信息
                    $_SESSION['Auditing']['NoCI']=$NoCI_matches[1][0];
                }

                $DemandNo="/parent.fraInterface.fm.DemandNo.value=\"(.*)\"/";
                preg_match_all($DemandNo, $results, $DemandNo_matches);
                if($DemandNo_matches[1][0]!="")
                {
                    $PREMIUMS['MESSAGE'] .="交强险投保查询码:".$DemandNo_matches[1][0]."<br/>";
                    $_SESSION['Auditing']['DemandNo']=$DemandNo_matches[1][0];
                }
               //unset($_SESSION['car_traffic']);

                preg_match_all("/parent.fraInterface.fm.BasePremium\[1\].value = \'(.*)\';/", $results, $BasePremiums);//交强险折旧前保费
                $_SESSION['MVTALCI']['BasePremium']= $BasePremiums[1][0];
                preg_match_all("/parent.fraInterface.fm.AdjustRate\[1\].value = '(.*)';/", $results, $AdjustRates);//交强险折旧率
                $_SESSION['MVTALCI']['DISCOUT_COUNT']= $AdjustRates[1][0];
                preg_match_all("/parent.fraInterface.fm.BZClaim.value = '(.*)';/", $results, $BZClaims);
                $_SESSION['MVTALCI']['BZClaim']= $BZClaims[1][0];
                preg_match_all("/parent.fraInterface.fm.Rate       \[1\].value = '(.*)';/", $results,$MVTALCI_DISCOUT);
                preg_match_all("/parent.fraInterface.fm.BZClaim.value = '(.*)';/", $results,$BZClaims);
 
                $BenchMarkPremium="/parent.fraInterface.fm.BenchMarkPremium\[1\].value = '(.*)'/";//交强险折前保费
			    preg_match_all($BenchMarkPremium, $results, $BenchMarkPremiums);
			    $jq_discout="/parent.fraInterface.fm.BZClaim.value = '(.*)'/";//交强险折扣  -100
			    preg_match_all($jq_discout, $results, $jq_discouts);




			    $PREMIUMS['MVTALCI']['MVTALCI_PREMIUM'] = round($BenchMarkPremiums[1][0]*($jq_discouts[1][0]+100)/100,2);
			    $_SESSION['MVTALCI']['MVTALCI_PREMIUM'] = round($BenchMarkPremiums[1][0]*($jq_discouts[1][0]+100)/100,2);
			    
			    $_SESSION['MVTALCI']['DISCOUT'] = $MVTALCI_DISCOUT[1][0];
			    $_SESSION['MVTALCI']['BZClaim'] = $BZClaims[1][0];
                $PREMIUMS['MVTALCI']['MVTALCI_DISCOUNT']  = ($jq_discouts[1][0]+100)/100;//$resen['ciDiscount'];          //交强险折扣
                $PREMIUMS['MVTALCI']['MVTALCI_START_TIME']= $mvtalci['MVTALCI_START_TIME'];       //交强险生效时间
                $PREMIUMS['MVTALCI']['MVTALCI_END_TIME']  = $mvtalci['MVTALCI_END_TIME'];       //交强险结束时间
            }

            preg_match_all("/parent.fraInterface.fm.completeCFeeRate.value='(.*)';/", $results, $completeCFeeRates);
            $_SESSION['MVTALCI']['completeCFeeRate'] = $completeCFeeRates[1][0];


            preg_match_all("/parent.fraInterface.fm.commercialFeeCRate.value='(.*)';/", $results, $commercialFeeCRates);
            $_SESSION['MVTALCI']['commercialFeeCRates'] = $commercialFeeCRates[1][0];

            preg_match_all("/parent.fraInterface.fm.compulsoryFeeCRate.value='(.*)';/", $results, $compulsoryFeeCRates);
            $_SESSION['MVTALCI']['compulsoryFeeCRates'] = $compulsoryFeeCRates[1][0];

            preg_match_all("/parent.fraInterface.fm.feeBusinessType.value='(.*)';/", $results, $feeBusinessTypes);
            $_SESSION['MVTALCI']['feeBusinessTypes'] = $feeBusinessTypes[1][0];
            
            preg_match_all("/parent.fraInterface.fm.completeCFee.value='(.*)';/", $results, $completeCFees);
            $_SESSION['MVTALCI']['completeCFees'] = $completeCFees[1][0];

            preg_match_all("/parent.fraInterface.fm.commercialCFee.value='(.*)';/", $results, $commercialCFee);
            $_SESSION['MVTALCI']['commercialCFee'] = $commercialCFee[1][0];

            preg_match_all("/parent.fraInterface.fm.compulsoryCFee.value='(.*)';/", $results, $compulsoryCFees);
            $_SESSION['MVTALCI']['compulsoryCFee'] = $compulsoryCFees[1][0];
            
            
            preg_match_all("/parent.fraInterface.fm.configValue.value='(.*)';/", $results, $configValues);
            $_SESSION['MVTALCI']['configValue'] = $configValues[1][0];
            preg_match_all("/parent.fraInterface.fm.tniSumPremium.value = '(.*)';/", $results, $tniSumPremiums);
            $_SESSION['Auditing']['tniSumPremiums'] = $tniSumPremiums[1][0];//实交保费合计(不含税)


            preg_match_all("/Profit.Rate = '(.*)';/", $results, $Profit_Rate);
            
            $_SESSION['Auditing']['no_claim_bonus'] = $Profit_Rate[1][0];//无赔款优待系数调整比例％
            $_SESSION['Auditing']['Premium_Discount'] = floor($Profit_Rate[1][2])+floor($Profit_Rate[1][3]);//保费折扣％


             $PREMIUMS['BUSINESS']=array();
             $PREMIUMS['BUSINESS']['TOTAL_DEDUCTIBLE']="0.00";//新增不计免赔总保费  //大地保险因为没有不计免赔的明细，只有总保费
             $PREMIUMS['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']='0.00';
             $PREMIUMS['BUSINESS']['BUSINESS_PREMIUM']='0.00';
             $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']=array();
             $PREMIUMS['BUSINESS']['BUSINESS_DISCOUNT']="0.00";//$Discount[21];//$resen['biDiscount'];         //商业险折扣
             $PREMIUMS['BUSINESS']['BUSINESS_PREMIUM']="0.00";//round($results['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']*$Discount[21],2);          //商业险标准保费合计
             $PREMIUMS['BUSINESS']['BUSINESS_START_TIME'] = $business['BUSINESS_START_TIME'];       //商业险生效时间
             $PREMIUMS['BUSINESS']['BUSINESS_END_TIME'] = $business['BUSINESS_END_TIME'];//商业险结束时间
             
             /*******************投保项目保费二维数组********************/
			 $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']                 = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']              = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']                = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']         = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']      = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']                 = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']                 = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']                = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']                = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']                 = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']                = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']                = '0.00';
             $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']           = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TVDI_NDSI']['PREMIUM']            = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TTBLI_NDSI']['PREMIUM']           = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI_NDSI']['PREMIUM']         = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER_NDSI']['PREMIUM']    = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER_NDSI']['PREMIUM'] = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['BSDI_NDSI']['PREMIUM']            = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['SLOI_NDSI']['PREMIUM']            = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['VWTLI_NDSI']['PREMIUM']           = '0.00';
		     $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['NIELI_NDSI']['PREMIUM']           = '0.00';

            if(isset($business['POLICY']['BUSINESS_ITEMS']) && !empty($business['POLICY']['BUSINESS_ITEMS']))
            {
                 $BenchMarkPremium="/parent.fraInterface.fm.BenchMarkPremium\[17\].value = '(.*)'/";//不计免赔保费
                 preg_match_all($BenchMarkPremium, $results, $BenchMark_pregs);
                 $ReferenceDiscount="/var ReferenceDiscount=(.*);/";
                 preg_match_all($ReferenceDiscount, $results, $Discount_matches);
                 $_SESSION['Auditing']['BUSINESS_DISCOUT_COUNT']=$Discount_matches[1][0];
                 $discount=1-($Discount_matches[1][0]/100);
                 $_SESSION['BUSINESS']['DISCOUT_COUNT'] = $discount;

                 foreach($business['POLICY']['BUSINESS_ITEMS'] as $u=>$value)
                 {

                        if(strpos($value, "NDSI"))
                        {
            
                            $PREMIUMS['BUSINESS']['TOTAL_DEDUCTIBLE'] = round($BenchMark_pregs[1][0]*$discount,2);
                            $_SESSION['BUSINESS']['TOTAL_DEDUCTIBLE'] = round($BenchMark_pregs[1][0]*$discount,2);
                        }


                }
                 

                 $quotationNoBI="/parent.fraInterface.fm.quotationNoBI.value=\"(.*)\"/";
                 preg_match_all($quotationNoBI, $results, $NoBI_matches);
                 if($NoBI_matches[1][0]!="")
                 {
                    $PREMIUMS['MESSAGE'] .="商业险报价单号:".$NoBI_matches[1][0]."<br/>";
                    $_SESSION['Auditing']['NoBI']=$NoBI_matches[1][0];

                 }

                 
                 $DemandNoForCIP="/parent.fraInterface.fm.DemandNoForCIP.value=\"(.*)\"/";
                 preg_match_all($DemandNoForCIP, $results, $ForCIP_matches);
                 if($ForCIP_matches[1][0]!="")
                 {  
                    $PREMIUMS['MESSAGE'] .="商业险投保查询码".$ForCIP_matches[1][0];
                    $_SESSION['Auditing']['ForCIP']=$ForCIP_matches[1][0];
                 }
                 
                 


                  
                  foreach($succ as $k=>$v)
                  {
                        if($v[0]=="[2].value")
                        {
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[2\].value = '(.*)';/", $results, $TVDI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[2\].value = '(.*)';/", $results,$TVDI_DISCOUT);

                            $_SESSION['BUSINESS']['TVDI']['BenchMarkPremium']=$TVDI[1][0];
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TVDI']['PREMIUM']= round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['TVDI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['TVDI']['DISCOUT']=$TVDI_DISCOUT[1][0];
                        }
                        else if($v[0]=="[6].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TTBLI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[6\].value = '(.*)';/", $results, $TTBLI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[6\].value = '(.*)';/", $results,$TTBLI_DISCOUT);
                            $_SESSION['BUSINESS']['TTBLI']['BenchMarkPremium']=$TTBLI[1][0];
                            $_SESSION['BUSINESS']['TTBLI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['TTBLI']['DISCOUT']=$TTBLI_DISCOUT[1][0];
                        }
                        else if($v[0]=="[9].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TWCDMVI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[9\].value = '(.*)';/", $results, $TWCDMVI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[9\].value = '(.*)';/", $results,$TWCDMVI_DISCOUT);
                            $_SESSION['BUSINESS']['TWCDMVI']['BenchMarkPremium']=$TWCDMVI[1][0];
                            $_SESSION['BUSINESS']['TWCDMVI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['TWCDMVI']['DISCOUT']=$TWCDMVI_DISCOUT[1][0];
                        }
                        else if($v[0]=="[10].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TCPLI_DRIVER']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[10\].value = '(.*)';/", $results, $TCPLI_DRIVER);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[10\].value = '(.*)';/", $results,$TCPLI_DRIVER_DISCOUT);
                            $_SESSION['BUSINESS']['TCPLI_DRIVER']['BenchMarkPremium']=$TCPLI_DRIVER[1][0];
                            $_SESSION['BUSINESS']['TCPLI_DRIVER']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['TCPLI_DRIVER']['DISCOUT']=$TCPLI_DRIVER_DISCOUT[1][0];
                        }
                        else if($v[0]=="[11].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['TCPLI_PASSENGER']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[11\].value = '(.*)';/", $results, $TCPLI_PASSENGER);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[11\].value = '(.*)';/", $results,$TCPLI_PASSENGER_DISCOUT);

                            $_SESSION['BUSINESS']['TCPLI_PASSENGER']['BenchMarkPremium']=$TCPLI_PASSENGER[1][0];
                            $_SESSION['BUSINESS']['TCPLI_PASSENGER']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['TCPLI_PASSENGER']['DISCOUT']=$TCPLI_PASSENGER_DISCOUT[1][0];
                        }
                        else if($v[0]=="[16].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['BGAI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[16\].value = '(.*)';/", $results, $BGAI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[16\].value = '(.*)';/", $results,$BGAI_DISCOUT);
                            $_SESSION['BUSINESS']['BGAI']['BenchMarkPremium']=$BGAI[1][0];
                            $_SESSION['BUSINESS']['BGAI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['BGAI']['DISCOUT']=$BGAI_DISCOUT[1][0];
                        }
                        else if($v[0]=="[18].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['RDCCI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[18\].value = '(.*)';/", $results, $RDCCI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[18\].value = '(.*)';/", $results,$RDCCI_DISCOUT);
                            $_SESSION['BUSINESS']['RDCCI']['BenchMarkPremium']=$RDCCI[1][0];
                            $_SESSION['BUSINESS']['RDCCI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['RDCCI']['DISCOUT']=$RDCCI_DISCOUT[1][0];
                        }
                        else if($v[0]=="[19].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['BSDI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[19\].value = '(.*)';/", $results, $BSDI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[19\].value = '(.*)';/", $results,$BSDI_DISCOUT);
                            $_SESSION['BUSINESS']['BSDI']['BenchMarkPremium']=$BSDI[1][0];
                            $_SESSION['BUSINESS']['BSDI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['BSDI']['DISCOUT']=$BSDI_DISCOUT[1][0];

                        }
                        else if($v[0]=="[28].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['NIELI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[28\].value = '(.*)';/", $results, $NIELI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[28\].value = '(.*)';/", $results,$NIELI_DISCOUT);
                            $_SESSION['BUSINESS']['NIELI']['BenchMarkPremium']=$NIELI[1][0];
                            $_SESSION['BUSINESS']['NIELI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['NIELI']['DISCOUT']=$NIELI_DISCOUT[1][0];

                        }
                        else if($v[0]=="[29].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['SLOI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[29\].value = '(.*)';/", $results, $SLOI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[29\].value = '(.*)';/", $results,$SLOI_DISCOUT);
                            $_SESSION['BUSINESS']['SLOI']['BenchMarkPremium']=$SLOI[1][0];
                            $_SESSION['BUSINESS']['SLOI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['SLOI']['DISCOUT']=$SLOI_DISCOUT[1][0];
                        }
                        else if($v[0]=="[33].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['VWTLI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[33\].value = '(.*)';/", $results, $VWTLI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[33\].value = '(.*)';/", $results,$VWTLI_DISCOUT);
                            $_SESSION['BUSINESS']['VWTLI']['BenchMarkPremium']=$VWTLI[1][0];
                            $_SESSION['BUSINESS']['VWTLI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['VWTLI']['DISCOUT']=$VWTLI_DISCOUT[1][0];
                        }
                        else if($v[0]=="[37].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['STSFS']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[37\].value = '(.*)';/", $results, $STSFS);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[37\].value = '(.*)';/", $results,$STSFS_DISCOUT);
                            $_SESSION['BUSINESS']['STSFS']['BenchMarkPremium']=$STSFS[1][0];
                            $_SESSION['BUSINESS']['STSFS']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['STSFS']['DISCOUT']=$STSFS_DISCOUT[1][0];
                        }
                        else if($v[0]=="[40].value")
                        {
                            $BUSINESS_PREMIUM += round($v[1]*$discount,2);
                            $STANDARD_PREMIUM += $v[1];
                            $PREMIUMS['BUSINESS']['BUSINESS_ITEMS']['MVLINFTPSI']['PREMIUM']= round($v[1]*$discount,2);
                            preg_match_all("/parent.fraInterface.fm.BenchMarkPremium\[40\].value = '(.*)';/", $results, $MVLINFTPSI);
                            preg_match_all("/parent.fraInterface.fm.Rate       \[40\].value = '(.*)';/", $results,$MVLINFTPSI_DISCOUT);
                            $_SESSION['BUSINESS']['MVLINFTPSI']['BenchMarkPremium']=$MVLINFTPSI[1][0];
                            $_SESSION['BUSINESS']['MVLINFTPSI']['Premium']=round($v[1]*$discount,2);
                            $_SESSION['BUSINESS']['MVLINFTPSI']['DISCOUT']=$MVLINFTPSI_DISCOUT[1][0];
                        }

                  }
                    
                    $_SESSION['Auditing']['STANDARD_PREMIUM'] = $STANDARD_PREMIUM+$BasePremiums[1][0]+$BenchMark_pregs[1][0];
                    $_SESSION['Auditing']['DISCOUT_PREMIUM_COUNT'] = round($STANDARD_PREMIUM+$_SESSION['BUSINESS']['TOTAL_DEDUCTIBLE']*$discount,1);/*round(($STANDARD_PREMIUM-$BasePremiums[1][0])*($Discount_matches[1][0]/100),2);*/

                    $_SESSION['Auditing']['BUSINESS_PREMIUM'] = $BUSINESS_PREMIUM+$PREMIUMS['MVTALCI']['MVTALCI_PREMIUM']+$_SESSION['BUSINESS']['TOTAL_DEDUCTIBLE'];
                    $_SESSION['MVTALCI']['TOTAI_PREMIUM'] = $_SESSION['Auditing']['STANDARD_PREMIUM']-$_SESSION['Auditing']['BUSINESS_PREMIUM']-$_SESSION['Auditing']['DISCOUT_PREMIUM_COUNT']; //优惠保费合计

                    $PREMIUMS['BUSINESS']['BUSINESS_DISCOUNT_PREMIUM']="";  //商业险扣后保费合计
                    $PREMIUMS['BUSINESS']['BUSINESS_DISCOUNT']=$discount;//$resen['biDiscount'];         //商业险折扣
                    $PREMIUMS['BUSINESS']['BUSINESS_PREMIUM']=$BUSINESS_PREMIUM+$PREMIUMS['BUSINESS']['TOTAL_DEDUCTIBLE'];//$resen['biPremium'];          //商业险标准保费合计


            }
		     
             	$PREMIUMS['BUSINESS']['INSURANCE_COMPANY'] = self::company;//当前保险公司
             	return $PREMIUMS;
			

	}


	/**
	 * [documentary_cost 保险公司生成暂存单]
	 * @param  array  $auto     [description]
	 * @param  array  $business [description]
	 * @param  array  $mvtalci  [description]
	 * @return [type]           [description]
	 */
	public function documentary_cost($auto=array(),$business=array(),$mvtalci=array())
	{

            $datas=array();
			$auto['switch']="1";//投保标志开关
			$datas= self::datas($auto,$business,$mvtalci);
			if(!$datas)
			{
				return false;
			}
			/****配置提交核保开关****/
			$result= self::requestPostData($this->InputNext,$datas);
			$results= iconv("GBK", "UTF-8", $result);
            preg_match_all("/alert(.*)/",$results,$alerts);
            if(isset($alerts[1][0]) && $alerts[1][0]!="")
            {
            	$this->error['errorMsg']=$alerts[1][0];
                $this->error['state']="1";
                return json_encode($error);
            }


            preg_match_all("/<input type=\"hidden\" name=\"certino\" value=(.*) >/", $results, $certinos);
            if(isset($certinos[1][0]) && $certinos[1][0]!="")//匹配得到投保单号
            {
                $result_json['TDZA'] = $certinos[1][0];
                $result_json['TDAA'] = $certinos[1][0];
                $result_json['state']="0";
            	return json_encode($result_json);
            }
            else
            {
            	$this->error['errorMsg']="提交失败";
                $this->error['state']="1";
                return json_encode($error);
            }	
            

	}



	/**
	 * [deviceDepreciation 设备折旧价计算]
	 * @param  array  $info [description]
	 * @return [type]       [description]
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
	 * [depreciation 车辆折旧价计算]
	 * @param  array  $info [description]
	 * @return [type]       [description]
	 */
	public function depreciation($info=array())
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
      * [queryBuyingPrice 购置价查询]
      * @AuthorHTL
      * @DateTime  2016-05-26T16:16:42+0800
      * @param     array                    $info [参数数组]
      * @return    [type]                         [成功返回数组，失败返回false]
      */
	public function queryBuyingPrice($info=array())
	{
		
		$page = 1;
		if(!empty($info['page']))
		{
				$page = $info['page'];
		}

		$rows=10;
		if(isset($_SESSION['car_traffic']) && is_array($_SESSION['car_traffic']) && array_key_exists('rows',$_SESSION['car_traffic']['carModels']))
		{
			
			$total= count($_SESSION['car_traffic']['carModels']['rows']);
			$retdata = array('total'=>ceil($total/$rows),'page'=>$page,'records'=>$total,'rows'=>array());
			foreach($_SESSION['car_traffic']['carModels']['rows'] as $row)
			{

				$line = array();
				$line['vehicleId']             = $row['vehicleId'];
				$line['vehicleName']           = $row['vehicleName'];
                $line['vehicleAlias']          = $row['vehicleAlias'];
				$line['vehicleMaker']          = $row['vehicleMaker'];
				$line['industryModelCode']	   = $row['industryModelCode'];
				$line['vehicleWeight']         = $row['vehicleWeight'];
				$line['vehicleDisplacement']   = $row['vehicleExhaust'];
				$line['vehicleTonnage']   	   = $row['vehicleTonnage'];
				$line['vehiclePrice']          = $row['vehiclePrice'];
				$line['szxhTaxedPrice']        = $row['szxhTaxedPrice'];
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
			$auto_model= iconv("UTF-8", "GBK", str_replace("牌","",$info['model']));
              $model_url="";
              $model_url.="orgCode=6020100&";
              $model_url.="vehicleCode=&";
              $model_url.="usetypeCode=101&";
              $model_url.="insuranceCode=AUTOCOMPRENHENSIVEINSURANCE2014PRODUCT&";
              $model_url.="requestSource=&";
              $model_url.="searchCode=&";
              $model_url="pagesize=".$rows."&";
              $model_url.="pageno=".$page."&";
              $select_model.="vinCode={$info['vin_no']}&";
              $select_model.="returnUrl={$this->ZccxServletInvoke}";
        	  $model_result= $this->requestGetData($this->search."?".$model_url.$select_model);
              preg_match_all("/<table .*>(.*)<\/table>/Us",$model_result,$model_s);
              preg_match_all("/<TD .*>(.*)<\/TD>/Us",$model_s[0][6],$TDS);
              $TDS_WHERE= urlencode(trim(str_replace("&nbsp;", "", $TDS[1][14])));
              $TDS_model.="cxingmc={$TDS_WHERE}&";
              $TDS_model.="returnUrl={$this->ZccxServletInvoke}";
              $TDS_result= iconv("GBK", "UTF-8", $this->requestGetData($this->search."?".$model_url.$TDS_model));
              preg_match_all("/<table .*>(.*)<\/table>/Us",$TDS_result,$TDS_results);
              $app= $this->get_td_array($TDS_results[0][4]);
              $TDS_conunt=$this->get_td_array($TDS_results[0][5]);
              preg_match_all('/[0-9]?[0-9]/',$TDS_conunt[0][1],$Page_Count);
              if($Page_Count[0][2]=="0")
              {
                 return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());
              }
              else
              {
              		$car['startIndex']=$Page_Count[0][0];
                    $car['total']=$Page_Count[0][1];
                    array_shift($app);
                    $arr=array();
                    foreach ($app as $key => $item) {
                         $arr['rows'][]=$app[$key];
                    }

                    $i=0;
                    foreach ($arr['rows'] as $key) {
                        $car['rows'][$i]['vehicleName']=$this->trimall($key[1]);
                        $car['rows'][$i]['vehicleId']=$this->trimall($key[2]); 
                        $car['rows'][$i]['vehicleSeat']=$this->trimall($key[4]);
                        $car['rows'][$i]['vehicleAlias']=$this->trimall($key[11]);
                        $car['rows'][$i]['vehicleWeight']=$this->trimall($key[6]);
                        $car['rows'][$i]['priceP']=$this->trimall($key[7]);
                        $car['rows'][$i]['vehicleYear']=$this->trimall($key[9]);
                        $car['rows'][$i]['vehicleDisplacement']=$this->trimall($key[5]);
                        $i++;
                    }

                         if(is_array($car) && array_key_exists('rows',$car))
                         {
                              $retdata = array('total'=>ceil($Page_Count[0][2]/$rows),'page'=>intval($car['startIndex']),'records'=>intval($Page_Count[0][2]),'rows'=>array());
                              foreach($car['rows'] as $row)
                              {

                                   $line = array();
                                   $line['vehicleId']             = $row['vehicleId'];
                                   $line['vehicleName']           = $row['vehicleName'];
                                   $line['vehicleAlias']          = $row['vehicleAlias'];
                                   //$line['vehicleMaker']        = $row['vehicleMaker'];
                                   $line['vehicleWeight']         = $row['vehicleWeight'];
                                   $line['vehicleDisplacement']   = $row['vehicleDisplacement'];
                                   //$line['vehicleTonnage']        = $row['vehicleTonnage'];
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

			// require_once(_ROOT_DIR.'/include/PremiumCal/libs/YUNNAN_PINGANKHYXYN_PC.class.php');
			// $arr['username']="HBCXBX-00001";
			// $arr['password']="Cxbxdl168";
			// $arr['fueltype']="0";
			// $cachepath = _ROOT_DIR.'/cache/PremiumCal';
			// $pingan=NEW YUNNAN_PINGANKHYXYN_PC($arr,$cachepath);
			// $where['page'] = '1';
		 //    $where['vin_no'] = $info['vin_no'];
			// $result= $pingan->queryBuyingPriceByVIN($where);
			// if(is_array($result) && count($result['rows'])>0)
			// {
			// 	return 	$result;		
			// }
			
		}	


					return array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());

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

		if(trim($info['VIN_NO'])=="")
		{
			$this->error['errorMsg']="车架号不能为空";
			return false;
		}

		if(!isset($info['ENROLL_DATE'])) $info['ENROLL_DATE']="";
		$data["licenseNo"]="";
		$data["licenseType"]="02";
		$data["vinNo"]=$info['VIN_NO'];
		$data["engineNo"]="";
		$data["enrollDate"]=$info['ENROLL_DATE'];
		$data["vehicleModel"]="";
		$data["businessNature"]="2401";
		$data["startDate"]="";
		$data["useNatureCode"]="85";
		$data["carKindCode"]="A0";
		$data["noLicenseFlag"]="0";
		$data["ecdemicVehicleFlag"]="0";
		$data["isCarInfoPreFill"]="1";
		$data["cacheable"]="0";
		$check_result= $this->requestPostData($this->Url_Verification,$data);
		
		
		if(isset($check_result['error']) && $check_result['error']==3)
		{
			$arr['url']=$this->check_filename;
			return json_encode($arr);
		}
		else
		{
			$arr=array();
			$checks= iconv("GBK", "UTF-8", $check_result);
			$where=json_decode($checks,true);

			if($where['errorMessage']!="")
			{
				$this->error['errorMsg']=$where['errorMessage'];
				 return false;
				
			}

			$arr['checkno']=$where['checkNo'];
			$arr['checkcode']=$where['checkCodeData'];
			return json_encode($arr);
		}	
		
		

		
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

		$data["licenseNo"]="";
		$data["licenseType"]="02";
		$data["vinNo"]=$info['vin_no'];
		$data["engineNo"]="";
		$data["enrollDate"]="";
		$data["vehicleModel"]="";
		$data["businessNature"]="2401";
		$data["startDate"]="";
		$data["useNatureCode"]="85";
		$data["carKindCode"]="A0";
		$data["noLicenseFlag"]="0";
		$data["ecdemicVehicleFlag"]="0";
		$data["isCarInfoPreFill"]="1";
		$data["checkNo"]=$info['checkno'];
		$data["checkCode"]=$info['checkcode'];

		$result = $this->requestPostData($this->Url_Verification,$data);

		/*$url="http://10.1.111.99:25011/ddccallweb/indiv/qg/jiaoguan/UIQueryJiaoGuanAjax.jsp";
		$where["licenseNo"]="";
		$where["vinNo"]=$info['vin_no'];
		$where["checkNo"]=$info['checkno'];
		$where["checkCode"]=$info['checkcode'];*/


		//$result = $this->requestPostData($url,$where);
		$check_result= iconv("GBK", "UTF-8", $result);
		$arr = json_decode($check_result,true);

		unset($_SESSION['car_traffic']['carModels']);
		if(isset($arr['errorMessage']) && $arr['errorMessage']!="")
		{
			$this->error['errorMsg']=$arr['errorMessage'];
			return false;
		}
		
		$_SESSION['car_traffic']["licenseno"]=$arr['licenseNo'];
		$_SESSION['car_traffic']["licensetype"]=$arr['licenseType'];
		$_SESSION['car_traffic']["engineno"]=$arr['engineNo'];		
		$_SESSION['car_traffic']["owner"]=$arr['carOwner'];
		$_SESSION['car_traffic']["enrolldate"]=$arr['enrollDate'];
		$_SESSION['car_traffic']["vehiclemodel"]=$arr['model'];
		$_SESSION['car_traffic']["vehiclestyle"]=$arr['vehicleStyle'];
		$rs=array();

		foreach($arr['carModels'] as $k =>$v)
		{

			$_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleId']=$v['vehicleCode'];//车辆代码
            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleName']=$v['vehicleName'];//车辆名称
            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleAlias']=$v['industryVehicleName'];//车辆别名
            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleSeat']=$v['seat'];//额定载客
            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleExhaust']=$v['displacement'];//车辆排量
            $_SESSION['car_traffic']['carModels']['rows'][$k]['carloteququality']=!isset($v['wholeWeight'])?"":$v['wholeWeight'];//车辆额定载质量
            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleTonnage']=$v['tonnage'];//车辆额定载质量
            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehicleYear']=$v['marketDate'];//上市年份
            $_SESSION['car_traffic']['carModels']['rows'][$k]['vehiclePrice']=$v['price'];//车辆新车购置价
            $_SESSION['car_traffic']['carModels']['rows'][$k]['szxhTaxedPrice']=$v['purchasePriceTax'];//车辆（含税）新车购置价
            $_SESSION['car_traffic']['carModels']['rows'][$k]['industryModelCode']=$v['industryModelCode'];//车辆行业模型代码
            $_SESSION['car_traffic']['carModels']['rows'][$k]['priceType']=$v['priceType'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]['airbagNum']=$v['airbagNum'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]['antiTheft']=$v['antiTheft'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]['brandCode']=$v['brandCode'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]['brandName']=$v['brandName'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]['factoryCode']=$v['factoryCode'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["factoryName"]=$v['factoryName'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["familyCode"]=$v['familyCode'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["familyName"]=$v['familyName'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["fitsRiskRate"]=$v['fitsRiskRate'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleWeight"]=$v['fullWeight'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["gearboxType"]=$v['gearboxType'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["groupFitsRate"]=$v['groupFitsRate'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["groupRepairRate"]=$v['groupRepairRate'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["hfCode"]=$v['hfCode'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["hfName"]=$v['hfName'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["industryVehicleCode"]=$v['industryVehicleCode'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["power"]=$v['power'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["pricetype"]=$v['pricetype'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["purchasePriceTax"]=$v['purchasePriceTax'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["rate"]=$v['rate'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["remark"]=$v['remark'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["riskFlags"]=$v['riskFlags'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["searchCode"]=$v['searchCode'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["stopFlag"]=$v['stopFlag'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleClassCode"]=$v['vehicleClassCode'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleClassName"]=$v['vehicleClassName'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleHyCode"]=$v['vehicleHyCode'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["vehicleHyName"]=$v['vehicleHyName'];
            $_SESSION['car_traffic']['carModels']['rows'][$k]["carRemark"]=$v['carRemark'];
		}

		$rs[0]['exhaustcapacity']=$arr['displacement']/1000;
		$rs[0]['carloteququality']=$arr['wholeWeight'];
		$rs[0]['engineno']=$arr['engineNo'];
		$rs[0]['enrolldate']=$arr['enrollDate'];
		$rs[0]['licenseno']=$arr['licenseNo'];
		$rs[0]['licensetype']=$arr['licenseType'];
		$rs[0]['owner']=$arr['carOwner'];
		$rs[0]['vehiclemodel']=$arr['model'];
		$rs[0]['vehiclestyle']=$arr['vehicleStyle'];
		$rs[0]['rackno']=$info['vin_no'];

		return json_encode($rs);


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


  /**
   * [format_number 格式化字符串]
   * @param  [type] $num [description]
   * @param  [type] $cut [description]
   * @return [type]      [description]
   */
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

	
    /**
     * [POST_ERROR 保留上次URL和POST请求数据]
     * @param [type] $url  [description]
     * @param [type] $post [description]
     */
    private function POST_ERROR($url,$post)
    {
    			if(isset($url) && isset($post))
    			{
    				$_SESSION['DADI_URL']=$url;
    				$_SESSION['DADI_POST']=$post;
    				$login['error']=3;
					return $login;
    			}
    			else
    			{
    				return false;
    			}	
    			
				
				

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
     * [Channel_organization description]
     */
    private function Channel_organization()
    {

    	if(!isset($this->user) && $this->user=="")
		{
					$this->error['errorMsg']="归属经办人编号不能为空。请前往算价器中设置";
					return false;
		}

		if(!isset($this->AgentCode) && $this->AgentCode=="")
		{
					$this->error['errorMsg']="代理人代码编号不能为空。请前往算价器中设置";
					return false;
		}

			$where=array(
				"querytype"=>"always",
				"codemethod"=>"select",
				"codetype"=>"Handler2Code",
				"coderelation"=>"1,2,3,-2,-1",
				"codelimit"=>"clear",
				"codeclass"=>"codecode",
				"codevalue"=>$this->user,
				"codeindex"=>"83",
				"riskcode"=>"DDG",
				"language"=>"C",
				"codeother"=>"",
				"fieldsign"=>"1493025293255",
				"code1value"=>"",
				);

			$values= self::requestPostData($this->UICodeGet,$where);
			$appx= iconv("GBK", "UTF-8", $values);
			preg_match_all("/<option value='(.*)'>/", $appx, $matches);
			if(isset($matches) && $matches[1][0]!="")
			{
				$arr= explode("_", $matches[1][0]);
				$Input=array(
	    			'ReqSystem'=>'01',
					'RequestType'=>'01',
					'ComCode'=>$arr[6],
					'BusinessNature'=>'2401',
					'RiskCode'=>'DDG',
					'InputTime'=>'',
					'AgentCode'=>$this->AgentCode,
					'AgentName'=>'',
					'AgreementNo'=>'',
					'AgreementName'=>'',
					'mouseX'=>'419',
					'mouseY'=>'563',
					'comFlag'=>'K',
					'icCardArea'=>'',
					'iccardAreaRiskCode'=>'DDH,DDM,DDG',
					'Handler1Code'=>$this->user,
					'Handler1Name'=>$arr[9],
			);

	    		$AgreementInput= self::requestPostData($this->UIPrPoEnAgentAgreementInput,$Input);
	    		$reqs= iconv("GBK", "UTF-8", $AgreementInput);
	    		if(isset($reqs) && $reqs!="")
	    		{

		    		preg_match_all("/AgentCode.value        = '(.*)';/", $reqs, $AgentCode);
		    		preg_match_all("/AgentName.value        = '(.*)';/", $reqs, $AgentName);
		    		preg_match_all("/AgreementNo.value      = '(.*)';/", $reqs, $AgreementNo);
		    		preg_match_all("/AgreementName.value    = '(.*)';/", $reqs, $AgreementName);
		    		preg_match_all("/QueryTime.value   = '(.*)';/", $reqs, $QueryTime);
		    		preg_match_all("/PermitNo.value   = '(.*)';/", $reqs, $PermitNo);
		    		preg_match_all("/ValidStartDate.value   = '(.*)';/", $reqs, $ValidStartDate);
		    		preg_match_all("/ValidEndDate.value   = '(.*)';/", $reqs, $ValidEndDate);

		    			$Channels=array(
							'HandlerName' => $arr[3],
							'ComCode' => $arr[6],
							'ComName' =>$arr[9],
							'HandlerCode'=>$arr[0],
							'AgentCode' =>$AgentCode[1][0],
							'AgentName' =>$AgentName[1][0],
							'AgreementNo' =>$AgreementNo[1][0],
							'AgreementName' =>$AgreementName[1][0],
							'QueryTime' => $QueryTime[1][0],
							'PermitNo' =>$PermitNo[1][0],//代理人资格证
							'ValidStartDate' => $ValidStartDate[1][0],
							'ValidEndDate' => $ValidEndDate[1][0]
						);
					return $Channels;	
		    			
		    	}
		    	else
		    	{
		    		$this->error['errorMsg']="大地核心系统正在维护";
		    		return false;
		    	}	
		    	

			}
			


    }

    /**
     * [delFile 删除指定目录下的文件，不删除目录文件夹]
     * @param  [type] $dirName [要删除的目录文件]
     * @return [type]          [description]
     */
    private function deldir($dir)
    {
        //删除目录下的文件：
        $dh=opendir($dir);
        
        while ($file=readdir($dh)) 
        {
            if($file!="." && $file!="..") 
            {
                $fullpath=$dir."/".$file;
                
                if(!is_dir($fullpath))
                {
                    unlink($fullpath);
                } 
                else
                {
                    deldir($fullpath);
                }
            }
        }
     
        closedir($dh);
    }
 	 /**
 	  * [trimall 过滤字符串空格]
 	  * @param  [type] $str [description]
 	  * @return [type]      [description]
 	  */
     private function trimall($str)//删除空格
     {
         $qian=array(" ","　","\t","\n","\r","??","<",">","&nbsp;",";","\"","'");
         $hou=array("","","","","","","","","","","","");
         return str_replace($qian,$hou,$str);
     }

     /**
      * [datas 组装算价参数]
      * @param  array  $auto     [车辆信息]
      * @param  array  $business [商业险]
      * @param  array  $mvtalci  [交强险]
      * @return [type]           [返回参数]
      */
     private function datas($auto=array(),$business=array(),$mvtalci=array())
     {

     	$Channels= self::Channel_organization();
		if(!$Channels)
		{
			return false;
		}

     	$UseYears= self::diffBetweenTwoDays($business['BUSINESS_START_TIME'],$auto['ENROLL_DATE']);//计算使用了多少年
		$wheres['BUYING_PRICE']=floor($auto['BUYING_PRICE']);
		$wheres['ENROLL_DATE']=$auto['ENROLL_DATE'];
		$wheres['BUSINESS_START_TIME']=$business['BUSINESS_START_TIME'];
		$wheres['USE_CHARACTER']="NON_OPERATING_PRIVATE";
		$wheres['VEHICLE_TYPE']="PASSENGER_CAR";

		if(!isset($mvtalci['MVTALCI_START_TIME']))
		{
			$mv['start_time']=date("Y-m-d",strtotime($business['BUSINESS_START_TIME']));
		}
		else
		{
			$mv['start_time']=date("Y-m-d",strtotime($mvtalci['MVTALCI_START_TIME']));
		}

		if(!isset($mvtalci['MVTALCI_END_TIME']))
		{
			$mv['end_time']=date("Y-m-d",strtotime($business['BUSINESS_END_TIME']));
		}
		else
		{
			$mv['end_time']=date("Y-m-d",strtotime($mvtalci['MVTALCI_END_TIME']));
		}	
		$price= $this->depreciation($wheres);//当没有传递折扣价时，系统自动计算折扣价
		$where=array();

						if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="5")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="50000";
			            }
			            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="10")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="100000";
			            }
			            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="15")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="150000";
			            }
			            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="20")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="200000";
			            }
			            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="30")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="300000";
			            }
			            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="50")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="500000";
			            }
			            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="100")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="1000000";
			            }
			            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="150")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="1500000";
			            }
			            else if($business['POLICY']['TTBLI_INSURANCE_AMOUNT']=="200")
			            {
			                $business['POLICY']['TTBLI_INSURANCE_AMOUNT']="2000000";
			            }


						foreach($_SESSION['car_traffic']['carModels']['rows'] as $k=>$v)
						{

							if($v['vehicleId']==$auto['MODEL_CODE'])
							{

					            $where['vehicleName']=$v['vehicleName'];//车辆名称
					            $where['vehicleAlias']=$v['industryVehicleName'];//车辆别名
					            $where['vehicleSeat']=$v['seat'];//额定载客
					            $where['vehicleDisplacement']=$v['displacement'];//车辆排量
					            $where['carloteququality']=$v['wholeWeight'];//车辆额定载质量
					            $where['vehicleTonnage']=$v['tonnage'];//车辆额定载质量
					            $where['vehicleYear']=$v['marketDate'];//上市年份
					            $where['vehiclePrice']=$v['price'];//车辆新车购置价
					            $where['szxhTaxedPrice']=$v['purchasePriceTax'];//车辆（含税）新车购置价
					            $where['industryModelCode']=$v['industryModelCode'];//车辆（含税）新车购置价
					            $where['priceType']=$v['priceType'];
					            $where['airbagNum']=$v['airbagNum'];
					            $where['antiTheft']=$v['antiTheft'];
					            $where['brandCode']=$v['brandCode'];
					            $where['brandName']=$v['brandName'];
					            $where['factoryCode']=$v['factoryCode'];
					            $where["factoryName"]=$v['factoryName'];
					            $where["familyCode"]=$v['familyCode'];
					            $where["familyName"]=$v['familyName'];
					            $where["fitsRiskRate"]=$v['fitsRiskRate'];
					            $where["fullWeight"]=$v['fullWeight'];
					            $where["gearboxType"]=$v['gearboxType'];
					            $where["groupFitsRate"]=$v['groupFitsRate'];
					            $where["groupRepairRate"]=$v['groupRepairRate'];
					            $where["hfCode"]=$v['hfCode'];
					            $where["hfName"]=$v['hfName'];
					            $where["industryVehicleCode"]=$v['industryVehicleCode'];
					            $where["power"]=$v['power'];
					            $where["pricetype"]=$v['pricetype'];
					            $where["purchasePriceTax"]=$v['purchasePriceTax'];
					            $where["rate"]=$v['rate'];
					            $where["remark"]=$v['remark'];
					            $where["riskFlags"]=$v['riskFlags'];
					            $where["searchCode"]=$v['searchCode'];
					            $where["stopFlag"]=$v['stopFlag'];
					            $where["vehicleClassCode"]=$v['vehicleClassCode'];
					            $where["vehicleClassName"]=$v['vehicleClassName'];
					            $where["vehicleHyCode"]=$v['vehicleHyCode'];
					            $where["vehicleHyName"]=$v['vehicleHyName'];
					            $where["carRemark"]=$v['carRemark'];


							}


						}
    
    if(!isset($_SESSION['Auditing']['BUSINESS_PREMIUM']))
    {
       $busin['PlanFee']= 0;
    }  
    if(!isset($_SESSION['MVTALCI']['MVTALCI_PREMIUM']) && $_SESSION['MVTALCI']['MVTALCI_PREMIUM']=="")
    {
       $_SESSION['MVTALCI']['MVTALCI_PREMIUM'] = 0;
       $busin['PlanFee']= $_SESSION['Auditing']['BUSINESS_PREMIUM'];
    } 

    if(isset($_SESSION['Auditing']['BUSINESS_PREMIUM']) && isset($_SESSION['MVTALCI']['MVTALCI_PREMIUM']))
    {
        $busin['PlanFee']= $_SESSION['Auditing']['BUSINESS_PREMIUM']-$_SESSION['MVTALCI']['MVTALCI_PREMIUM'];
    }
	
	$BasePremius= $_SESSION['MVTALCI']['BasePremium']-$_SESSION['MVTALCI']['MVTALCI_PREMIUM'];

	if($_SESSION['BUSINESS']['TOTAL_DEDUCTIBLE']==0)
	{
		$TOTAL_DEDUCTIBLE = "";
	}
	else
	{
		$TOTAL_DEDUCTIBLE = round($_SESSION['BUSINESS']['TOTAL_DEDUCTIBLE']/$_SESSION['BUSINESS']['DISCOUT_COUNT'],2);
	}

	$arr= Array
	(
    0 =>'qgPlatformCode=L,V,K,M,G,F,R,Q,Z,d,H,N,U,E,I,D,S,T,C,P,W,b,Y,B,f,a,X,O,h,g,c,j,n',
    1 =>'ebaoPlatformCode=A,J',
    2 =>'comFlag=K',
    3 =>'isNewDDGInputPage=yes',
    4 =>'EDITTYPE=NEW',
    5 =>'BIZTYPE=PROPOSAL',
    6 =>'OldPolicyNo=',
    7 =>'ClassCode=D',
    8 =>'ClaimTimes=0',
    9 =>'ClaimKindCount=0',
    10 =>'IsCarKindB=0',
    11 =>'TransactionNo=',
    12 =>'Main_Flag=',
    13 =>'PolicySort=',
    14 =>'SumEPremium=',
    15 =>'hiddenToCpiFlag=true',
    16 =>'bzAnswer=',
    17 =>'ciAnswer=',
    18 =>'isRateReformFlag=true',
    19 =>'uniteFlag=true',
    20 =>'carFlag=',
    21 =>'SingleCIPArea=L,K,Q,H,U,F,M,E,I,D,S,V,T,C,R,G,W,Z,d,b,Y,B,f,a,X,O,P,h,g,c,N,j,n',
    22 =>'CommissionArea=3',
    23 =>'BusinessAttribute=0000',
    24 =>'Com_Flag=K',
    25 =>'bzClaim_comCode=O,S,N',
    26 =>'ThirdHandlerPrint=',
    27 =>'EndorseOption=null',
    28 =>'OldPolicy=',
    29 =>'ValidDate=null',
    30 =>'EndorDate=null',
    31 =>'pComCode=null',
    32 =>'EndorseTime=',
    33 =>'EndorseType=',
    34 =>'oldPtext=',
    35 =>'RiskCode=DDG',
    36 =>'BaseAmount='.floor(floor($auto['BUYING_PRICE'])),
    37 =>'othFlag=',
    38 =>'CIPLatestVersion=L,K,Q,H,U,F,M,E,I,D,S,V,T,C,R,G,W,Z,d,b,Y,B,f,a,X,O,P,h,g,c,N,j,n',
    39 =>'QueryJingYou=L,d,K,R,V',
    40 =>'AgentCodeForTBQuery=',
    41 =>'AgreementNoForTBQuery=',
    42 =>'ServerType=IN-PRO',
    43 =>'EKindCode=',
    44 =>'txtMACAddr=98:5F:D3:D2:E9:A7_00:FF:E1:91:16:D5_00:50:56:C0:00:01_00:50:56:C0:00:08_',
    45 =>'IPAddress=192.168.1.110_11.9.0.14_192.168.204.1_192.168.209.1_',
    46 =>'EndorseNo=',
    47 =>'ProposalNo1=',
    48 =>'ProposalNo=',
    49 =>'PolicyNo=',
    50 =>'MainHead_Flag=',
    51 =>'PrintNo_Flag=',
    52 =>'createEPolicy_Flag=',
    53 =>'PrintNo=',
    54 =>'ContractNo=',
    55 =>'GroupCode=',
    56 =>'GroupNo=',
    57 =>'quotationNoCI='.$_SESSION['Auditing']['NoCI'],
    58 =>'quotationNoBI='.$_SESSION['Auditing']['NoBI'],
    59 =>'DemandNo='.$_SESSION['Auditing']['DemandNo'],
    60 =>'ValidNo=',
    61 =>'PGDemandNo=',
    62 =>'PGValidNo=',
    63 =>'ThirdCalFlag=',
    64 =>'FglCalFlag=',
    65 =>'ThirdAmount=',
    66 =>'ThirdChgPremium=',
    67 =>'ThirdPtext=',
    68 =>'CheckFlag=',
    69 =>'oldDemandNo=',
    70 =>'PolicyProposalType=',
    71 =>'DemandNoForCIP='.$_SESSION['Auditing']['ForCIP'],
    72 =>'ValidNoForCIP=',
    73 =>'PGDemandNoForCIP=',
    74 =>'PGValidNoForCIP=',
    75 =>'BusinessNature1=2400',
    76 =>'BusinessNature1_Flag=',
    77 =>'BusinessNature=2401',
    78 =>'Tail_Flag=',
    79 =>'HandlerCode='.$Channels['HandlerCode'],
    80 =>'HandlerName='.$Channels['HandlerName'],
    81 =>'Handler1Code='.$Channels['HandlerCode'],
    82 =>'Handler1Name='.$Channels['HandlerName'],
    83 =>'ComCode='.$Channels['ComCode'],
    84 =>'ComName='.$Channels['ComName'],
    85 =>'ExhibitionNo='.$Channels['PermitNo'],
    86 =>'HandlerCodeForSD='.$Channels['HandlerCode'],
    87 =>'HandlerNameForSD='.$Channels['HandlerName'],
    88 =>'Handler=',
    89 =>'ThirdPartyHandlerNo=',
    90 =>'ThirdPartyHandlerName=',
    91 =>'AgentCode=P32010500446',
    92 =>'AgentName=武宵',
    93 =>'AgreementNo='.$Channels['AgreementNo'],
    94 =>'AgreementName='.$Channels['AgreementName'],
    95 =>'OldHandler2Code=',
    96 =>'Handler2Code=',
    97 =>'Handler2Name=',
    98 =>'BusinessNature2=',
    99 =>'TeamCode=',
    100 =>'AgentPermitNo='.$Channels['PermitNo'],
    101 =>'SalePolicyAddr=',
    102 =>'RepairChannelCode=',
    103 =>'ProjectSerialNo=',
    104 =>'DZprintName=',
    105 =>'AutoInsurancePackage=',
    106 =>'AutoInsurancePackage2=',
    107 =>'RepairChannelName=',
    108 =>'ProjectName=',
    109 =>'Car_BrandName1_Flag=',
    110 =>'Factory='.$where['factoryName'],//上海通用汽车有限公司
    111 =>'QueryModelCode=',
    112 =>'isCarTaxQG=true',
    113 =>'PTCarKindCode=12',
    114 =>'VehPriceFloat=0.01',
    115 =>'searchSequenceNo=',
    116 =>'EndorseItemCar=',
    117 =>'EndorType=',
    118 =>'CarInsuredRelation=1',
    119 =>'HKLicenseNo=',
    120 =>'FrameNo='.$auto['VIN_NO'],
    121 =>'EnrollDate='.$auto['ENROLL_DATE'],
    122 =>'EngineNo='.$auto['ENGINE_NO'],
    123 =>'BrandName='.$auto['MODEL'],
    124 =>'IsNewCarFlag=0',
    125 =>'NoLicenseFlag=0',
    126 =>'LicenseNo='.$auto['LICENSE_NO'],
    127 =>'LicenseColorCode=01',
    128 =>'licenseType=02',
    129 =>'VINNo='.$auto['VIN_NO'],
    130 =>'CarKindCode=A0',
    131 =>'UseNatureCode=85',
    132 =>'CarClass=A类',
    133 =>'PrintUseNature=',
    134 =>'BrandName1='.$auto['MODEL'],
    135 =>'newModelCode='.$where['industryModelCode'],
    136 =>'CarName='.$where['vehicleHyName'],
    137 =>'NoticeType='.$where['industryVehicleCode'],
    138 =>'ModelName=',
    139 =>'ModelCode='.$auto['MODEL_CODE'],
    140 =>'specialModelFlag=0',
    141 =>'SeatCount='.$auto['SEATS'],
    142 =>'TonCount=0.0',
    143 =>'ExhaustScale='.$auto['ENGINE']*1000,
    144 =>'PowerScale=123',
    145 =>'UseYears='.$UseYears,
    146 =>'CarActualValue='.$price,
    147 =>'PurchasePrice='.floor($auto['BUYING_PRICE']),
    148 =>'OldPurchasePrice='.floor($auto['BUYING_PRICE']),
    149 =>'changePurchasePrice='.floor($auto['BUYING_PRICE']),
    150 =>'HighRiskFlag=',
    151 =>'LastestCommercePlatform=',
    152 =>'LastestCommercePlatformV2=',
    153 =>'Net='.$auto['KERB_MASS'],
    154 =>'CarStyle='.$_SESSION['car_traffic']['carStyle'],
    155 =>'SortCode=',
    156 =>'VehicleStyle='.$_SESSION['car_traffic']["vehiclestyle"],
    157 =>'VehicleStyle1=',//.$_SESSION['car_traffic']["vehiclestyle"],
    158 =>'EcdemicVehicleFlag=0',
    159 =>'Period_Flag=',
    160 =>'StartDate='.date("Y-m-d",strtotime($business['BUSINESS_START_TIME'])),
    161 =>'OriStartDate=',
    162 =>'startHour=0',
    163 =>'EndDate='.date("Y-m-d",strtotime($business['BUSINESS_END_TIME'])),
    164 =>'endHour=24',
    165 =>'StartDateCIP='.date("Y-m-d",strtotime($business['BUSINESS_START_TIME'])),//商业险日期
    166 =>'startHourCIP=0',
    167 =>'EndDateCIP='.date("Y-m-d",strtotime($business['BUSINESS_END_TIME'])),
    168 =>'endHourCIP=24',
    169 =>'StartDateBZ='.$mv['start_time'],//交强险日期
    170 =>'startHourBZ=0',
    171 =>'EndDateBZ='.$mv['end_time'],//.date("Y-m-d",strtotime($mvtalci['MVTALCI_END_TIME'])),
    172 =>'endHourBZ=24',
    173 =>'NoDamageYears=0',
    174 =>'LoanVehicleFlag=0',
    175 =>'ChgOwnerFlag=0',
    176 =>'SpecialCarFlag=',
    177 =>'TransferDate=',
    178 =>'CarPeccancy=',
    179 =>'LoanOrRentFlag=',
    180 =>'Beneficiary=',
    181 =>'AppointAreaCode=',
    182 =>'RunAreaCode=11',
    183 =>'RunMiles=',
    184 =>'FUEL_TYPE=0',
    185 =>'OtherNature5=0',
    186 =>'OtherNature6=2',
    187 =>'ColorCode=',
    188 =>'CarLoanFlag=0',
    189 =>'Car_Flag=',
    190 =>'ClauseType=01',
    191 =>'CarDevice_Flag=',
    192 =>'CarDeviceSerialNo=',
    193 =>'CarDeviceDeviceName=',
    194 =>'CarDeviceQuantity=',
    195 =>'CarDevicePurchasePrice=',
    196 =>'CarDeviceActualValue=',
    197 =>'CarDeviceQuantityCount=',
    198 =>'CarDevicePurchasePriceCount=',
    199 =>'CarDeviceActualValueCount=0.00',
    200 =>'validDate=',
    201 =>'countDriver=',
    202 =>'CarDriver_Flag=',
    203 =>'CarLicenseChanged_Flag=',
    204 =>'CarDriverDriverTypeName=',
    205 =>'CarDriverDriverName=',
    206 =>'CarDriverDrivingLicenseNo=',
    207 =>'CarDriverSex=1',
    208 =>'CarDriverAge=',
    209 =>'DrivingYears=',
    210 =>'CarDriverAcceptLicenseDate=',
    211 =>'CarDriver_Flag=',
    212 =>'CarLicenseChanged_Flag=',
    213 =>'CarDriverDriverTypeName=主驾驶员',
    214 =>'CarDriverDriverName=',
    215 =>'CarDriverDrivingLicenseNo=',
    216 =>'CarDriverSex=1',
    217 =>'CarDriverAge=',
    218 =>'DrivingYears=',
    219 =>'CarDriverAcceptLicenseDate=',
    220 =>'CarDriver_Flag=',
    221 =>'CarLicenseChanged_Flag=',
    222 =>'CarDriverDriverTypeName=从驾驶员',
    223 =>'CarDriverDriverName=',
    224 =>'CarDriverDrivingLicenseNo=',
    225 =>'CarDriverSex=1',
    226 =>'CarDriverAge=',
    227 =>'DrivingYears=',
    228 =>'CarDriverAcceptLicenseDate=',
    229 =>'CarDriver_Flag=',
    230 =>'CarLicenseChanged_Flag=',
    231 =>'CarDriverDriverTypeName=从驾驶员',
    232 =>'CarDriverDriverName=',
    233 =>'CarDriverDrivingLicenseNo=',
    234 =>'CarDriverSex=1',
    235 =>'CarDriverAge=',
    236 =>'DrivingYears=',
    237 =>'CarDriverAcceptLicenseDate=',
    238 =>'Peccancy=',
    239 =>'tablePeccancy_Flag=',
    240 =>'CarOwner='.$auto['OWNER'],
    241 =>'BusinessClassCode=3',
    242 =>'carIdentifyType=07',
    243 =>'identityNumber='.$auto['IDENTIFY_NO'],
    244 =>'vehicleCode='.$auto['MODEL_CODE'],
    245 =>'vehicleName='.$auto['MODEL'],
    246 =>'factoryCode='.$where['factoryCode'],//MK0677
    247 =>'factoryName='.$where['factoryName'],//上海通用汽车有限公司
    248 =>'brandCode='.$where['brandCode'],//BKA1
    249 =>'brandName='.$where['brandName'],//上汽通用别克
    250 =>'familyCode='.$where['familyCode'],//BKA1AM
    251 =>'familyName='.$where['familyName'],//君威
    252 =>'vehicleClassCode='.$where['vehicleClassCode'],//IC01
    253 =>'vehicleClassName='.$where['vehicleClassName'],//轿车类
    254 =>'vehicleType=1',
    255 =>'displacement='.$auto['ENGINE'],//1.998
    256 =>'seat='.$auto['SEATS'],
    257 =>'tonnage=',
    258 =>'gearboxType='.$where['gearboxType'],//手自一体
    259 =>'price='.floor($auto['BUYING_PRICE']),
    260 =>'priceType='.$where['priceType'],
    261 =>'antiTheft='.$where['antiTheft'],
    262 =>'airbagNum='.$where['airbagNum'],
    263 =>'marketDate=201011',
    264 =>'riskFlags='.$where['riskFlags'],
    265 =>'stopFlag='.$where['stopFlag'],
    266 =>'fullWeight='.$auto['KERB_MASS'],//整备质量
    267 =>'rate='.$where['rate'],
    268 =>'searchCode='.$where['searchCode'],//BK-SGM7201EYAA
    269 =>'power='.$where['power'],//108.0
    270 =>'fitsRiskRate='.$where['fitsRiskRate'],
    271 =>'groupFitsRate='.$where['groupFitsRate'],
    272 =>'groupRepairRate='.$where['groupRepairRate'],
    273 =>'jYCarType=',
    274 =>'bdRiskFlag1=null',
    275 =>'bdRiskFlag2=null',
    276 =>'bdRiskFlag3=null',
    277 =>'bdRiskFlag4=null',
    278 =>'remark=null',
    279 =>'flag=null',
    280 =>'carRemark='.$where['carRemark'],//手自一体 领先时尚型 京5国Ⅳ
    281 =>'purchasePriceTax='.$where['purchasePriceTax'],//含税购置价 161600
    282 =>'purchasePrice='.floor($auto['BUYING_PRICE']),//不含税购置价
    283 =>'hfCode='.$where['hfCode'],
    284 =>'hfName='.$where['hfName'],//正常
    285 =>'vehicleHyCode='.$where['industryModelCode'],//行业车型编码
    286 =>'vehicleHyName='.$where['vehicleHyName'],//车型别名
    287 =>'CheckStatusFor459=',
    288 =>'CallBackFlag=',
    289 =>'IsCommonEndorse=null',
    290 =>'clsLevelAppli=',
    291 =>'AppliCustOld=',
    292 =>'clsLevelInsure=',
    293 =>'InsuredCustOld=',
    294 =>'clsLevelAppliOld=',
    295 =>'clsLevelInsureOld=',
    296 =>'CustCarStatisticsJsonAppli=',
    297 =>'CustCarStatisticsJsonInsured=',
    298 =>'mobileUserTimes=',
    299 =>'InsuredPhoneNumberOld=',
    300 =>'InsuredMobileOld=',
    301 =>'InsuredNameOld=',
    302 =>'IdentifyNumberOld=',
    303 =>'MachineCode=',
    304 =>'MachineCodeInsured=',
    305 =>'CollectionID_AreaFlag=false',
    306 =>'CheckIdentifyAppli=',
    307 =>'CheckIdentifyInsured=',
    308 =>'IsFilledInsuredName=',
    309 =>'InsuredNatureOld=',
    310 =>'AppliNatureFlag=',
    311 =>'AppliIdentifyTypeDiffer=',
    312 =>'AppliNature=3',
    313 =>'AppliNotice=注意：大陆居民请使用身份证进行投保',
    314 =>'FocusName=',
    315 =>'AppliCode=9999999999999999',
    316 =>'CustomerType=',
    317 =>'AppliName='.$auto['OWNER'],
    318 =>'AppliIdentifyType=01',
    319 =>'AppliSex=',
    320 =>'AppliNation=',
    321 =>'AppliBirthday=',
    322 =>'AppliIdentifyAddress=',
    323 =>'AppliIdentifyCom=',
    324 =>'AppliIdentifyStartDate=',
    325 =>'AppliIdentifyEndDate=',
    326 =>'AppliIdentifyTypeOrg=21',
    327 =>'AppliIdentifyNumber='.$auto['IDENTIFY_NO'],
    328 =>'AppliBusinessOccup=02',
    329 =>'AppliBusinessSort=104',
    330 =>'AppliLinkerName=',
    331 =>'AppliPhoneNumber=',
    332 =>'AppliMobile='.$auto['MOBILE'],
    333 =>'FlagForAppliMobile=0',
    334 =>'AppliAddress=',
    335 =>'AppliPostCode=',
    336 =>'AppliEmail=',
    337 =>'Appli_Flag=',
    338 =>'AppliAccountName=',
    339 =>'AppliAccountNo=',
    340 =>'AppliBank=',
    341 =>'PerMajorShow=',
    342 =>'PerMajorFlag=',
    343 =>'PerMajorLevel=',
    344 =>'Appli_AcceptSMFlag=1',
    345 =>'AppliChkEcif=1',
    346 =>'AppliCust=80194012605',
    347 =>'AppliNationalityCar=CHN',
    348 =>'CheckStatusForBJ=',
    349 =>'AgreeAppli=on',
    350 =>'InsuredNature=3',
    351 =>'InsuredNotice=注意：大陆居民请使用身份证进行投保',
    352 =>'InsuredCode=9999999999999999',
    353 =>'CustomerType1=',
    354 =>'InsuredName='.$auto['OWNER'],
    355 =>'IdentifyType=01',
    356 =>'InsuredSex=',
    357 =>'InsuredNation=',
    358 =>'InsuredBirthday=',
    359 =>'InsuredIdentifyAddress=',
    360 =>'InsuredIdentifyCom=',
    361 =>'InsuredIdentifyStartDate=',
    362 =>'InsuredIdentifyEndDate=',
    363 =>'IdentifyTypeOrg=21',
    364 =>'IdentifyNumber='.$auto['IDENTIFY_NO'],
    365 =>'BusinessOccup=02',
    366 =>'BusinessSort=104',
    367 =>'InsuredLinkerName=',
    368 =>'InsuredPhoneNumber=',
    369 =>'InsuredMobile='.$auto['MOBILE'],
    370 =>'InsuredAddress=',
    371 =>'InsuredPostCode=',
    372 =>'InsuredEmail=',
    373 =>'Insured_Flag=',
    374 =>'EntMajorShow=',
    375 =>'EntMajorFlag=',
    376 =>'EntMajorLevel=',
    377 =>'Insured_AcceptSMFlag=1',
    378 =>'InsuredChkEcif=1',
    379 =>'InsuredCust=80194012605',
    380 =>'ECIF_FLAG=true',
    381 =>'InsuredNationalityCar=CHN',
    382 =>'VatInfoInputDate=',
    383 =>'VatInfo_Flag=',
    384 =>'VatInfoTaxCompanyAs=1',
    385 =>'VatInfoVerifyFlag=',
    386 =>'VatInfoVerifyUserCode=',
    387 =>'VatInfoVerifyDate=',
    388 =>'VatInfoClientType=1',
    389 =>'VatInfoTaxPayerType=4',
    390 =>'VatInfoInvoiceType=2',
    391 =>'VatInfoBankName=',
    392 =>'VatInfoBankAccount=',
    393 =>'VatInfoTaxCompanyName='.$auto['OWNER'],
    394 =>'VatInfoTaxPayerIdentification=',
    395 =>'VatInfoTaxRegistryAddress=',
    396 =>'VatInfoTaxRegistryPhone=',
    397 =>'VatInfoMobile='.$auto['MOBILE'],
    398 =>'VatInfoEmail=',
    399 =>'oldLicenseNoForClaimDetail=',
    400 =>'oldVinNoForClaimDetail=',
    401 =>'oldStartDateForClaimDetail=',
    402 =>'ClaimDetailUnSupport=true',
    403 =>'isNewCarForClaimDetail=false',
    404 =>'tableShortRate_Flag=',
    405 =>'Currency=CNY',
    406 =>'CurrencyName=人民币',
    407 =>'BZShortRateFlag=2',
    408 =>'ShortRateFlag=2',
    409 =>'BZShortRate=100.0000',
    410 =>'ShortRate=100.0000',
    411 =>'Additionalcostrate=35.0',
    412 =>'PeccancyAdjust=10.0',
    413 =>'HbAdjust=14.9999',
    414 =>'HbAdjustNonPercent=0.850001',
    415 =>'ChannelAdjust=15.0',
    416 =>'ChannelAdjustNonPercent=0.85',
    417 =>'ItemKind_Flag=',
    418 =>'KindName=',
    419 =>'KindCode=Temp',
    420 =>'Value=0',
    421 =>'Quantity=0',
    422 =>'UnitAmount=0',
    423 =>'TotalProfit1=0',
    424 =>'TotalProfit2=0',
    425 =>'DeductibleRate=1.00',
    426 =>'ItemKindStartDate=',
    427 =>'ItemKindStartHour=0',
    428 =>'ItemKindEndDate=',
    429 =>'ItemKindEndHour=24',
    430 =>'KindTypeFlag=',
    431 =>'ItemKindFlag2Flag=',
    432 =>'ItemKindCalculateFlag=',
    433 =>'ItemKindFlag3To4=',
    434 =>'ItemKindFlag5Flag=',
    435 =>'ItemKindFlag6Flag=',
    436 =>'Amount=',
    437 =>'Rate=',
    438 =>'BenchMarkPremium=',
    439 =>'BasePremium=0',
    440 =>'DiscountShow=',
    441 =>'Discount=',
    442 =>'AdjustRateShow=',
    443 =>'AdjustRate=',
    444 =>'Premium=',
    445 =>'AmountCount='.round($_SESSION['KindTypeFlag']['AmountCount'],2),
    446 =>'BenchMarkPremiumCount='.$_SESSION['Auditing']['STANDARD_PREMIUM'],//折前总保费
    447 =>'PremiumCount='.$_SESSION['Auditing']['BUSINESS_PREMIUM'],//折后总保费
    448 =>'DiscountjqShow=',
    449 =>'Discountjq='.$_SESSION['MVTALCI']['DISCOUT_COUNT'],//交强险总折扣
    450 =>'DiscountsyShow='.$_SESSION['BUSINESS']['DISCOUT_COUNT'],
    451 =>'Discountsy='.$_SESSION['Auditing']['BUSINESS_DISCOUT_COUNT'],
    452 =>'DiscountPremiumCount=',
    453 =>'ReferenceDiscount='.$_SESSION['Auditing']['BUSINESS_DISCOUT_COUNT']."%",
    454 =>'isReferenceDiscount=H0_90_PUB,H0_91_PUB,H0_93_PUB,H0_99_PUB,I0_91_PUB,I0_91_PUB,I0_93_PUB,I0_99_PUB,I1_91_PUB,I1_93_PUB,I1_99_PUB,ZZ_91_PUB,L0_91_PUB,L0_93_PUB,G0_91_PUB,G0_93_PUB,G0_99_PUBH0_84_PUB,I0_84_PUB,I1_84_PUB,L0_84_PUB,ZZ_84_PUB,G0_84_PUBT0_83_PUB,T0_84_PUB,T0_90_PUB,T0_91_PUB,T0_93_PUB,T0_94_PUB,T0_99_PUB,T1_83_PUB,T1_84_PUB,T1_90_PUB,T1_91_PUB,T1_93_PUB,T1_94_PUB,T1_99_PUBT2_83_PUB,T2_84_PUB,T2_90_PUB,T2_91_PUB,T2_93_PUB,T2_94_PUB,T2_99_PUB,T3_83_PUB,T3_84_PUB,T3_90_PUB,T3_91_PUB,T3_93_PUB,T3_94_PUB,T3_99_PUBT4_83_PUB,T4_84_PUB,T4_90_PUB,T4_91_PUB,T4_93_PUB,T4_94_PUB,T4_99_PUB,T5_83_PUB,T5_84_PUB,T5_90_PUB,T5_91_PUB,T5_93_PUB,T5_94_PUB,T5_99_PUBT6_83_PUB,T6_84_PUB,T6_90_PUB,T6_91_PUB,T6_93_PUB,T6_94_PUB,T6_99_PUB,T7_83_PUB,T7_84_PUB,T7_90_PUB,T7_91_PUB,T7_93_PUB,T7_94_PUB,T7_99_PUBT8_83_PUB,T8_84_PUB,T8_90_PUB,T8_91_PUB,T8_93_PUB,T8_94_PUB,T8_99_PUB,T9_83_PUB,T9_84_PUB,T9_90_PUB,T9_91_PUB,T9_93_PUB,T9_94_PUB,T9_99_PUBTA_83_PUB,TA_84_PUB,TA_90_PUB,TA_91_PUB,TA_93_PUB,TA_94_PUB,TA_99_PUB,TB_83_PUB,TB_84_PUB,TB_90_PUB,TB_91_PUB,TB_93_PUB,TB_94_PUB,TB_99_PUBTC_83_PUB,TC_84_PUB,TC_90_PUB,TC_91_PUB,TC_93_PUB,TC_94_PUB,TC_99_PUB,TD_83_PUB,TD_84_PUB,TD_90_PUB,TD_91_PUB,TD_93_PUB,TD_94_PUB,TD_99_PUBTE_83_PUB,TE_84_PUB,TE_90_PUB,TE_91_PUB,TE_93_PUB,TE_94_PUB,TE_99_PUB,TF_83_PUB,TF_84_PUB,TF_90_PUB,TF_91_PUB,TF_93_PUB,TF_94_PUB,TF_99_PUBTG_83_PUB,TG_84_PUB,TG_90_PUB,TG_91_PUB,TG_93_PUB,TG_94_PUB,TG_99_PUB,TH_83_PUB,TH_84_PUB,TH_90_PUB,TH_91_PUB,TH_93_PUB,TH_94_PUB,TH_99_PUBTI_83_PUB,TI_84_PUB,TI_90_PUB,TI_91_PUB,TI_93_PUB,TI_94_PUB,TI_99_PUB,TJ_83_PUB,TJ_84_PUB,TJ_90_PUB,TJ_91_PUB,TJ_93_PUB,TJ_94_PUB,TJ_99_PUBTK_83_PUB,TK_84_PUB,TK_90_PUB,TK_91_PUB,TK_93_PUB,TK_94_PUB,TK_99_PUB,TL_83_PUB,TL_84_PUB,TL_90_PUB,TL_91_PUB,TL_93_PUB,TL_94_PUB,TL_99_PUBTM_83_PUB,TM_84_PUB,TM_90_PUB,TM_91_PUB,TM_93_PUB,TM_94_PUB,TM_99_PUB,TN_83_PUB,TN_84_PUB,TN_90_PUB,TN_91_PUB,TN_93_PUB,TN_94_PUB,TN_99_PUBTO_83_PUB,TO_84_PUB,TO_90_PUB,TO_91_PUB,TO_93_PUB,TO_94_PUB,TO_99_PUB,TP_83_PUB,TP_84_PUB,TP_90_PUB,TP_91_PUB,TP_93_PUB,TP_94_PUB,TP_99_PUBTQ_83_PUB,TQ_84_PUB,TQ_90_PUB,TQ_91_PUB,TQ_93_PUB,TQ_94_PUB,TQ_99_PUB,TR_83_PUB,TR_84_PUB,TR_90_PUB,TR_91_PUB,TR_93_PUB,TR_94_PUB,TR_99_PUBTS_83_PUB,TS_84_PUB,TS_90_PUB,TS_91_PUB,TS_93_PUB,TS_94_PUB,TS_99_PUB,TT_83_PUB,TT_84_PUB,TT_90_PUB,TT_91_PUB,TT_93_PUB,TT_94_PUB,TT_99_PUBTU_83_PUB,TU_84_PUB,TU_90_PUB,TU_91_PUB,TU_93_PUB,TU_94_PUB,TU_99_PUB,TV_83_PUB,TV_84_PUB,TV_90_PUB,TV_91_PUB,TV_93_PUB,TV_94_PUB,TV_99_PUBTW_83_PUB,TW_84_PUB,TW_90_PUB,TW_91_PUB,TW_93_PUB,TW_94_PUB,TW_99_PUB,TX_83_PUB,TX_84_PUB,TX_90_PUB,TX_91_PUB,TX_93_PUB,TX_94_PUB,TX_99_PUBTY_83_PUB,TY_84_PUB,TY_90_PUB,TY_91_PUB,TY_93_PUB,TY_94_PUB,TY_99_PUB,TZ_83_PUB,TZ_84_PUB,TZ_90_PUB,TZ_91_PUB,TZ_93_PUB,TZ_94_PUB,TZ_99_PUBA0_84_16,A0_84_81,A0_84_82,B0_84_16,B0_84_81,B0_84_82A0_82_PUB,A0_86_PUB,A0_87_PUB,A0_88_PUB,A0_89_PUB,A0_90_PUB,A0_91_PUB,A0_92_PUB,A0_93_PUB,B0_82_PUB,B0_86_PUB,B0_87_PUB,B0_88_PUB,B0_89_PUB,B0_90_PUB,B0_91_PUB,B0_92_PUB,B0_93_PUBA0_84_100,B0_84_100,A0_84_101,B0_84_101,A0_84_102,B0_84_102,A0_84_103,B0_84_103,A0_84_104,B0_84_104,A0_84_84,B0_84_84,A0_84_85,B0_84_85',
    455 =>'AdjustPremiumCount=723.47',
    456 =>'PTNonClaimAdjust=',
    457 =>'ClaimAdjustValue=',
    458 =>'LoyaltyAdjustValue=',
    459 =>'ManagementAdjustUpper=',
    460 =>'ManagementAdjustLower=',
    461 =>'VehicleModelAdjustUpper=',
    462 =>'VehicleModelAdjustLower=',
    463 =>'ExperienceAdjustUpper=',
    464 =>'ExperienceAdjustLower=',
    465 =>'NoDiscountFlag=',
    466 =>'isM=true',
    467 =>'expectProfitValue=',
    468 =>'SumPremium='.$_SESSION['Auditing']['BUSINESS_PREMIUM'],
    469 =>'tniSumPremium='.$_SESSION['Auditing']['tniSumPremiums'],
    470 =>'queryTimesLimit=20',
    471 =>'queryTimesCI=3',
    472 =>'queryTimesBI=4',
    473 =>'queryTimesLimitBS=',
    474 =>'queryTimesBIAndCI=',
    475 =>'completeCFeeRate='.$_SESSION['MVTALCI']['completeCFeeRate'],
    476 =>'commercialFeeCRate='.$_SESSION['MVTALCI']['commercialFeeCRates'],
    477 =>'compulsoryFeeCRate=0.0',
    478 =>'feeBusinessType='.$_SESSION['MVTALCI']['feeBusinessTypes'],
    479 =>'completeCFee='.$_SESSION['MVTALCI']['completeCFees'],
    480 =>'commercialCFee='.$_SESSION['MVTALCI']['completeCFees'],
    481 =>'compulsoryCFee=0.00',
    482 =>'completeC1FeeRate='.$_SESSION['MVTALCI']['completeCFeeRate'],
    483 =>'commercialFeeC1Rate='.$_SESSION['MVTALCI']['commercialFeeCRates'],
    484 =>'compulsoryFeeC1Rate=0.0',
    485 =>'completeC1Fee='.$_SESSION['MVTALCI']['commercialCFee'],
    486 =>'commercialFeeC1='.$_SESSION['MVTALCI']['commercialCFee'],
    487 =>'compulsoryFeeC1=0.00',
    488 =>'configValue='.$_SESSION['MVTALCI']['configValue'],
    489 =>'beforeSubsidyCFeeRateBI=',
    490 =>'beforeSubsidyCFeeRateCI=',
    491 =>'cSubsidyAmountBI=',
    492 =>'cSubsidyAmountCI=',
    493 =>'beforeSubsidyC1FeeRateBI=',
    494 =>'beforeSubsidyC1FeeRateCI=',
    495 =>'c1SubsidyAmountBI=',
    496 =>'c1SubsidyAmountCI=',
    497 =>'beforeSubsidyPremiumBI=',
    498 =>'beforeSubsidyPremiumCI=',
    499 =>'subsidyFlag=',
    500 =>'ItemKind_Flag=',
    501 =>'KindType=on',
    502 =>'KindName=强制责任保险',
    503 =>'BZPecc=0.00',
    504 =>'BZClaim='.$_SESSION['MVTALCI']['BZClaim'],
    505 =>'BZDrinkCount=0',
    506 =>'BZDrinkAdjustPerRate=0',
    507 =>'BZDrunkCount=0',
    508 =>'BZDrunkAdjustPerRate=0',
    509 =>'BZDrinkAdjustRate=',
    510 =>'BZDrunkAdjustRate=',
    511 =>'BZWineAdjustMaxRate=',
    512 =>'BZWineAdjustRateSupport=',
    513 =>'BZDrinkProingCount=0',
    514 =>'BZDrinkProingAdPRate=0',
    515 =>'BZDrunkProingCount=0',
    516 =>'BZDrunkProingAdPRate=0',
    517 =>'BZDrinkProedCount=0',
    518 =>'BZDrinkProedAdPRate=0',
    519 =>'BZDrunkProedCount=0',
    520 =>'BZDrunkProedAdPRate=0',
    521 =>'BZDrinkProingAdRate=',
    522 =>'BZDrunkProingAdRate=',
    523 =>'BZDrinkProedAdRate=',
    524 =>'BZDrunkProedAdRate=',
    525 =>'BZWineProAdMaxRate=',
    526 =>'BZWineProAdRateSupport=',
    527 =>'KindCode=BZ',
    528 =>'Value=0',
    529 =>'Quantity=1',
    530 =>'UnitAmount=122000.0',
    531 =>'TotalProfit1=0',
    532 =>'TotalProfit2='.$BasePremius,
    533 =>'DeductibleRate=1.00',
    534 =>'ItemKindStartDate=',
    535 =>'ItemKindStartHour=0',
    536 =>'ItemKindEndDate=',
    537 =>'ItemKindEndHour=24',
    538 =>'KindTypeFlag=0',
    539 =>'ItemKindFlag2Flag=1N',
    540 =>'ItemKindCalculateFlag=Y',
    541 =>'ItemKindFlag3To4= ',
    542 =>'ItemKindFlag5Flag=0',
    543 =>'ItemKindFlag6Flag=1',
    544 =>'Amount=122000.0',
    545 =>'Rate=0.0',
    546 =>'BenchMarkPremium='.$_SESSION['MVTALCI']['BasePremium'],
    547 =>'BasePremium='.$_SESSION['MVTALCI']['BasePremium'],
    548 =>'DiscountShow=',
    549 =>'Discount=0.0',
    550 =>'AdjustRateShow=',
    551 =>'AdjustRate='.$_SESSION['MVTALCI']['DISCOUT_COUNT'],
    552 =>'Premium='.$_SESSION['MVTALCI']['MVTALCI_PREMIUM'],
    553 =>'ItemKind_Flag=',
    554 =>'KindType=01',
    555 =>'KindName=车辆损失保险',
    556 =>'Value=0.00',
    557 =>'DeductibleRate=1.00',
    558 =>'KindCode=A',
    559 =>'Quantity=',
    560 =>'UnitAmount=',
    561 =>'TotalProfit1=917.0593654500001',
    562 =>'TotalProfit2=255.74859518250002',
    563 =>'ItemKindStartDate=',
    564 =>'ItemKindStartHour=0',
    565 =>'ItemKindEndDate=',
    566 =>'ItemKindEndHour=24',
    567 =>'isInsure_A=0',
    568 =>'KindTypeFlag=0',
    569 =>'ItemKindFlag2Flag=1Y',
    570 =>'ItemKindCalculateFlag=Y',
    571 =>'ItemKindFlag3To4= ',
    572 =>'ItemKindFlag5Flag=0',
    573 =>'ItemKindFlag6Flag=',
    574 =>'Amount='.$business['POLICY']['TVDI_INSURANCE_AMOUNT'],
    575 =>'Rate=0.0',
    576 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['TVDI']['BenchMarkPremium'],
    577 =>'BasePremium=0.00',
    578 =>'DiscountShow=',
    579 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    580 =>'AdjustRateShow=',
    581 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    582 =>'Premium='.$_SESSION['BUSINESS']['TVDI']['Premium'],
    583 =>'ItemKind_Flag=',
    584 =>'KindName=车辆损失保险基本险',
    585 =>'Value=0.00',
    586 =>'DeductibleRate=1.00',
    587 =>'KindCode=A1',
    588 =>'Quantity=',
    589 =>'UnitAmount=',
    590 =>'TotalProfit1=0',
    591 =>'TotalProfit2=0',
    592 =>'ItemKindStartDate=',
    593 =>'ItemKindStartHour=0',
    594 =>'ItemKindEndDate=',
    595 =>'ItemKindEndHour=24',
    596 =>'KindTypeFlag=0',
    597 =>'ItemKindFlag2Flag=1Y',
    598 =>'ItemKindCalculateFlag=Y',
    599 =>'ItemKindFlag3To4= ',
    600 =>'ItemKindFlag5Flag=0',
    601 =>'ItemKindFlag6Flag=',
    602 =>'Amount=',
    603 =>'Rate=',
    604 =>'BenchMarkPremium=',
    605 =>'BasePremium=0',
    606 =>'DiscountShow=',
    607 =>'Discount=',
    608 =>'AdjustRateShow=',
    609 =>'AdjustRate=',
    610 =>'Premium=',
    611 =>'ItemKind_Flag=',
    612 =>'KindName=车对车碰撞损失特约条款',
    613 =>'KindCode=A2',
    614 =>'Value=0',
    615 =>'Quantity=',
    616 =>'UnitAmount=',
    617 =>'TotalProfit1=0',
    618 =>'TotalProfit2=0',
    619 =>'DeductibleRate=1.00',
    620 =>'ItemKindStartDate=',
    621 =>'ItemKindStartHour=0',
    622 =>'ItemKindEndDate=',
    623 =>'ItemKindEndHour=24',
    624 =>'KindTypeFlag=0',
    625 =>'ItemKindFlag2Flag=1Y',
    626 =>'ItemKindCalculateFlag=Y',
    627 =>'ItemKindFlag3To4= ',
    628 =>'ItemKindFlag5Flag=0',
    629 =>'ItemKindFlag6Flag=',
    630 =>'Amount=',
    631 =>'Rate=',
    632 =>'BenchMarkPremium=',
    633 =>'BasePremium=0',
    634 =>'DiscountShow=',
    635 =>'Discount=',
    636 =>'AdjustRateShow=',
    637 =>'AdjustRate=',
    638 =>'Premium=',
    639 =>'ItemKind_Flag=',
    640 =>'KindName=车辆全部损失特约条款',
    641 =>'KindCode=A3',
    642 =>'Value=0',
    643 =>'Quantity=',
    644 =>'UnitAmount=',
    645 =>'TotalProfit1=0',
    646 =>'TotalProfit2=0',
    647 =>'DeductibleRate=1.00',
    648 =>'ItemKindStartDate=',
    649 =>'ItemKindStartHour=0',
    650 =>'ItemKindEndDate=',
    651 =>'ItemKindEndHour=24',
    652 =>'KindTypeFlag=0',
    653 =>'ItemKindFlag2Flag=1Y',
    654 =>'ItemKindCalculateFlag=Y',
    655 =>'ItemKindFlag3To4= ',
    656 =>'ItemKindFlag5Flag=0',
    657 =>'ItemKindFlag6Flag=',
    658 =>'Amount=',
    659 =>'Rate=',
    660 =>'BenchMarkPremium=',
    661 =>'BasePremium=0',
    662 =>'DiscountShow=',
    663 =>'Discount=',
    664 =>'AdjustRateShow=',
    665 =>'AdjustRate=',
    666 =>'Premium=',
    667 =>'ItemKind_Flag=',
    668 =>'KindType=on',
    669 =>'KindName=第三者责任保险',
    670 =>'KindCode=B',
    671 =>'Value=0',
    672 =>'Quantity=',
    673 =>'UnitAmount=',
    674 =>'TotalProfit1=493.84558799999996',
    675 =>'TotalProfit2=137.72316179999998',
    676 =>'DeductibleRate=1.00',
    677 =>'ItemKindStartDate=',
    678 =>'ItemKindStartHour=0',
    679 =>'ItemKindEndDate=',
    680 =>'ItemKindEndHour=24',
    681 =>'KindTypeFlag=0',
    682 =>'ItemKindFlag2Flag=1Y',
    683 =>'ItemKindCalculateFlag=Y',
    684 =>'ItemKindFlag3To4= ',
    685 =>'ItemKindFlag5Flag=0',
    686 =>'ItemKindFlag6Flag=',
    687 =>'Amount='.$business['POLICY']['TTBLI_INSURANCE_AMOUNT'],
    688 =>'Rate=0.0',
    689 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['TTBLI']['BenchMarkPremium'],
    690 =>'BasePremium=0.00',
    691 =>'DiscountShow=',
    692 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    693 =>'AdjustRateShow=',
    694 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    695 =>'Premium='.$_SESSION['BUSINESS']['TTBLI']['Premium'],
    696 =>'ItemKind_Flag=',
    697 =>'KindName=第三者责任险人身伤害保险',
    698 =>'KindCode=B1',
    699 =>'Value=0',
    700 =>'Quantity=',
    701 =>'UnitAmount=',
    702 =>'TotalProfit1=0',
    703 =>'TotalProfit2=0',
    704 =>'DeductibleRate=1.00',
    705 =>'ItemKindStartDate=',
    706 =>'ItemKindStartHour=0',
    707 =>'ItemKindEndDate=',
    708 =>'ItemKindEndHour=24',
    709 =>'KindTypeFlag=0',
    710 =>'ItemKindFlag2Flag=1Y',
    711 =>'ItemKindCalculateFlag=Y',
    712 =>'ItemKindFlag3To4= ',
    713 =>'ItemKindFlag5Flag=0',
    714 =>'ItemKindFlag6Flag=',
    715 =>'Amount=',
    716 =>'Rate=',
    717 =>'BenchMarkPremium=',
    718 =>'BasePremium=0',
    719 =>'DiscountShow=',
    720 =>'Discount=',
    721 =>'AdjustRateShow=',
    722 =>'AdjustRate=',
    723 =>'Premium=',
    724 =>'ItemKind_Flag=',
    725 =>'KindName=第三者责任险财产损失保险',
    726 =>'KindCode=B2',
    727 =>'Value=0',
    728 =>'Quantity=',
    729 =>'UnitAmount=',
    730 =>'TotalProfit1=0',
    731 =>'TotalProfit2=0',
    732 =>'DeductibleRate=1.00',
    733 =>'ItemKindStartDate=',
    734 =>'ItemKindStartHour=0',
    735 =>'ItemKindEndDate=',
    736 =>'ItemKindEndHour=24',
    737 =>'KindTypeFlag=0',
    738 =>'ItemKindFlag2Flag=1Y',
    739 =>'ItemKindCalculateFlag=Y',
    740 =>'ItemKindFlag3To4= ',
    741 =>'ItemKindFlag5Flag=0',
    742 =>'ItemKindFlag6Flag=',
    743 =>'Amount=',
    744 =>'Rate=',
    745 =>'BenchMarkPremium=',
    746 =>'BasePremium=0',
    747 =>'DiscountShow=',
    748 =>'Discount=',
    749 =>'AdjustRateShow=',
    750 =>'AdjustRate=',
    751 =>'Premium=',
    752 =>'ItemKind_Flag=',
    753 =>'KindName=全车盗抢保险',
    754 =>'KindCode=G1',
    755 =>'Value=0',
    756 =>'Quantity=',
    757 =>'UnitAmount=',
    758 =>'TotalProfit1=0',
    759 =>'TotalProfit2=0',
    760 =>'DeductibleRate=1.00',
    761 =>'ItemKindStartDate=',
    762 =>'ItemKindStartHour=0',
    763 =>'ItemKindEndDate=',
    764 =>'ItemKindEndHour=24',
    765 =>'KindTypeFlag=0',
    766 =>'ItemKindFlag2Flag=1Y',
    767 =>'ItemKindCalculateFlag=Y',
    768 =>'ItemKindFlag3To4= ',
    769 =>'ItemKindFlag5Flag=0',
    770 =>'ItemKindFlag6Flag=',
    771 =>'Amount='.$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'],
    772 =>'Rate=',
    773 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['TWCDMVI']['BenchMarkPremium'],
    774 =>'BasePremium=0',
    775 =>'DiscountShow=',
    776 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    777 =>'AdjustRateShow=',
    778 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    779 =>'Premium='.$_SESSION['BUSINESS']['TWCDMVI']['Premium'],
    780 =>'ItemKind_Flag=',
    781 =>'KindName=车上人员责任保险（司机）',
    782 =>'KindCode=D3',
    783 =>'Value=0',
    784 =>'Quantity=',
    785 =>'UnitAmount=',
    786 =>'TotalProfit1=0',
    787 =>'TotalProfit2=0',
    788 =>'DeductibleRate=1.00',
    789 =>'ItemKindStartDate=',
    790 =>'ItemKindStartHour=0',
    791 =>'ItemKindEndDate=',
    792 =>'ItemKindEndHour=24',
    793 =>'KindTypeFlag=0',
    794 =>'ItemKindFlag2Flag=1Y',
    795 =>'ItemKindCalculateFlag=Y',
    796 =>'ItemKindFlag3To4= ',
    797 =>'ItemKindFlag5Flag=0',
    798 =>'ItemKindFlag6Flag=',
    799 =>'Amount='.$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'],
    800 =>'Rate=',
    801 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['TCPLI_DRIVER']['BenchMarkPremium'],
    802 =>'BasePremium=0',
    803 =>'DiscountShow=',
    804 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    805 =>'AdjustRateShow=',
    806 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    807 =>'Premium='.$_SESSION['BUSINESS']['TCPLI_DRIVER']['Premium'],
    808 =>'ItemKind_Flag=',
    809 =>'KindName=车上人员责任保险（乘客）',
    810 =>'KindCode=D4',
    811 =>'Quantity='.$business['POLICY']['TCPLI_PASSENGER_COUNT'],
    812 =>'UnitAmount='.$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'],
    813 =>'Value=0',
    814 =>'TotalProfit1=0',
    815 =>'TotalProfit2=0',
    816 =>'DeductibleRate=1.00',
    817 =>'ItemKindStartDate=',
    818 =>'ItemKindStartHour=0',
    819 =>'ItemKindEndDate=',
    820 =>'ItemKindEndHour=24',
    821 =>'KindTypeFlag=0',
    822 =>'ItemKindFlag2Flag=1Y',
    823 =>'ItemKindCalculateFlag=Y',
    824 =>'ItemKindFlag3To4= ',
    825 =>'ItemKindFlag5Flag=0',
    826 =>'ItemKindFlag6Flag=',
    827 =>'Amount='.$business['POLICY']['TCPLI_PASSENGER_COUNT']*$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'],
    828 =>'Rate=',
    829 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['TCPLI_PASSENGER']['BenchMarkPremium'],
    830 =>'BasePremium=0',
    831 =>'DiscountShow=',
    832 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    833 =>'AdjustRateShow=',
    834 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    835 =>'Premium='.$_SESSION['BUSINESS']['TCPLI_PASSENGER']['Premium'],
    836 =>'ItemKind_Flag=',
    837 =>'KindName=车上人员责任保险（乘客）',
    838 =>'KindCode=D4',
    839 =>'Quantity=',
    840 =>'UnitAmount=',
    841 =>'Value=0',
    842 =>'TotalProfit1=0',
    843 =>'TotalProfit2=0',
    844 =>'DeductibleRate=1.00',
    845 =>'ItemKindStartDate=',
    846 =>'ItemKindStartHour=0',
    847 =>'ItemKindEndDate=',
    848 =>'ItemKindEndHour=24',
    849 =>'KindTypeFlag=0',
    850 =>'ItemKindFlag2Flag=1Y',
    851 =>'ItemKindCalculateFlag=Y',
    852 =>'ItemKindFlag3To4= ',
    853 =>'ItemKindFlag5Flag=0',
    854 =>'ItemKindFlag6Flag=',
    855 =>'Amount=',
    856 =>'Rate=',
    857 =>'BenchMarkPremium=',
    858 =>'BasePremium=0',
    859 =>'DiscountShow=',
    860 =>'Discount=',
    861 =>'AdjustRateShow=',
    862 =>'AdjustRate=',
    863 =>'Premium=',
    864 =>'ItemKind_Flag=',
    865 =>'KindName=车上货物责任险',
    866 =>'Value=0',
    867 =>'KindCode=D2',
    868 =>'Quantity=',
    869 =>'UnitAmount=',
    870 =>'TotalProfit1=0',
    871 =>'TotalProfit2=0',
    872 =>'DeductibleRate=1.00',
    873 =>'ItemKindStartDate=',
    874 =>'ItemKindStartHour=0',
    875 =>'ItemKindEndDate=',
    876 =>'ItemKindEndHour=24',
    877 =>'KindTypeFlag=0',
    878 =>'ItemKindFlag2Flag=2Y',
    879 =>'ItemKindCalculateFlag=N',
    880 =>'ItemKindFlag3To4= ',
    881 =>'ItemKindFlag5Flag=0',
    882 =>'ItemKindFlag6Flag=',
    883 =>'Amount=',
    884 =>'Rate=',
    885 =>'BenchMarkPremium=',
    886 =>'BasePremium=0',
    887 =>'DiscountShow=',
    888 =>'Discount=',
    889 =>'AdjustRateShow=',
    890 =>'AdjustRate=',
    891 =>'Premium=',
    892 =>'ItemKind_Flag=',
    893 =>'KindName=火灾爆炸损失险',
    894 =>'KindCode=E1',
    895 =>'Value=0',
    896 =>'Quantity=',
    897 =>'UnitAmount=',
    898 =>'TotalProfit1=0',
    899 =>'TotalProfit2=0',
    900 =>'DeductibleRate=1.00',
    901 =>'ItemKindStartDate=',
    902 =>'ItemKindStartHour=0',
    903 =>'ItemKindEndDate=',
    904 =>'ItemKindEndHour=24',
    905 =>'KindTypeFlag=0',
    906 =>'ItemKindFlag2Flag=2N',
    907 =>'ItemKindCalculateFlag=N',
    908 =>'ItemKindFlag3To4= ',
    909 =>'ItemKindFlag5Flag=0',
    910 =>'ItemKindFlag6Flag=',
    911 =>'Amount=',
    912 =>'Rate=',
    913 =>'BenchMarkPremium=',
    914 =>'BasePremium=0',
    915 =>'DiscountShow=',
    916 =>'Discount=',
    917 =>'AdjustRateShow=',
    918 =>'AdjustRate=',
    919 =>'Premium=',
    920 =>'ItemKind_Flag=',
    921 =>'KindName=自然灾害损失险',
    922 =>'KindCode=E2',
    923 =>'Value=0',
    924 =>'Quantity=',
    925 =>'UnitAmount=',
    926 =>'TotalProfit1=0',
    927 =>'TotalProfit2=0',
    928 =>'DeductibleRate=1.00',
    929 =>'ItemKindStartDate=',
    930 =>'ItemKindStartHour=0',
    931 =>'ItemKindEndDate=',
    932 =>'ItemKindEndHour=24',
    933 =>'KindTypeFlag=0',
    934 =>'ItemKindFlag2Flag=1N',
    935 =>'ItemKindCalculateFlag=Y',
    936 =>'ItemKindFlag3To4= ',
    937 =>'ItemKindFlag5Flag=0',
    938 =>'ItemKindFlag6Flag=',
    939 =>'Amount=',
    940 =>'Rate=',
    941 =>'BenchMarkPremium=',
    942 =>'BasePremium=0',
    943 =>'DiscountShow=',
    944 =>'Discount=',
    945 =>'AdjustRateShow=',
    946 =>'AdjustRate=',
    947 =>'Premium=',
    948 =>'ItemKind_Flag=',
    949 =>'KindName=玻璃单独破碎险',
    950 =>'KindCode=F',
    951 =>'Quantity=',
    952 =>'UnitAmount=',
    953 =>'Model=0',
    954 =>'Value=0',
    955 =>'TotalProfit1=0',
    956 =>'TotalProfit2=0',
    957 =>'DeductibleRate=1.00',
    958 =>'ItemKindStartDate=',
    959 =>'ItemKindStartHour=0',
    960 =>'ItemKindEndDate=',
    961 =>'ItemKindEndHour=24',
    962 =>'KindTypeFlag=0',
    963 =>'ItemKindFlag2Flag=2N',
    964 =>'ItemKindCalculateFlag=N',
    965 =>'ItemKindFlag3To4= ',
    966 =>'ItemKindFlag5Flag=0',
    967 =>'ItemKindFlag6Flag=',
    968 =>'Amount=',
    969 =>'Rate=',
    970 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['BGAI']['BenchMarkPremium'],
    971 =>'BasePremium=0',
    972 =>'DiscountShow=',
    973 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    974 =>'AdjustRateShow=',
    975 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    976 =>'Premium='.$_SESSION['BUSINESS']['BGAI']['Premium'],
    977 =>'ItemKind_Flag=',
    978 =>'KindName=不计免赔特约',
    979 =>'Value=0',
    980 =>'KindCode=M',
    981 =>'Quantity=',
    982 =>'UnitAmount=',
    983 =>'TotalProfit1=0',
    984 =>'TotalProfit2=0',
    985 =>'DeductibleRate=1.00',
    986 =>'ItemKindStartDate=',
    987 =>'ItemKindStartHour=0',
    988 =>'ItemKindEndDate=',
    989 =>'ItemKindEndHour=24',
    990 =>'KindTypeFlag=0',
    991 =>'ItemKindFlag2Flag=2N',
    992 =>'ItemKindCalculateFlag=N',
    993 =>'ItemKindFlag3To4= ',
    994 =>'ItemKindFlag5Flag=0',
    995 =>'ItemKindFlag6Flag=',
    996 =>'Amount=0.00',
    997 =>'Rate=0.0',
    998 =>'BenchMarkPremium='.$TOTAL_DEDUCTIBLE,
    999 =>'BasePremium=0',
    1000 =>'DiscountShow=',
    1001 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    1002 =>'AdjustRateShow=',
    1003 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    1004 =>'Premium='.$_SESSION['BUSINESS']['TOTAL_DEDUCTIBLE'],
    1005 =>'ItemKind_Flag=',
    1006 =>'KindName=修理期间费用补偿险',
    1007 =>'KindCode=T',
    1008 =>'UnitAmount='.$business['POLICY']['RDCCI_INSURANCE_UNIT'],
    1009 =>'Quantity='.$business['POLICY']['RDCCI_INSURANCE_QUANTITY'],
    1010 =>'Value=0',
    1011 =>'TotalProfit1=0',
    1012 =>'TotalProfit2=0',
    1013 =>'DeductibleRate=1.00',
    1014 =>'ItemKindStartDate=',
    1015 =>'ItemKindStartHour=0',
    1016 =>'ItemKindEndDate=',
    1017 =>'ItemKindEndHour=24',
    1018 =>'KindTypeFlag=0',
    1019 =>'ItemKindFlag2Flag=2N',
    1020 =>'ItemKindCalculateFlag=N',
    1021 =>'ItemKindFlag3To4= ',
    1022 =>'ItemKindFlag5Flag=0',
    1023 =>'ItemKindFlag6Flag=',
    1024 =>'Amount='.$business['POLICY']['RDCCI_INSURANCE_QUANTITY']*$business['POLICY']['RDCCI_INSURANCE_UNIT'],
    1025 =>'Rate=',
    1026 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['RDCCI']['BenchMarkPremium'],
    1027 =>'BasePremium=0',
    1028 =>'DiscountShow=',
    1029 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    1030 =>'AdjustRateShow=',
    1031 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    1032 =>'Premium='.$_SESSION['BUSINESS']['RDCCI']['Premium'],
    1033 =>'ItemKind_Flag=',
    1034 =>'KindName=车身划痕损失险',
    1035 =>'Value=0',
    1036 =>'KindCode=L',
    1037 =>'Quantity=',
    1038 =>'UnitAmount=',
    1039 =>'TotalProfit1=0',
    1040 =>'TotalProfit2=0',
    1041 =>'DeductibleRate=1.00',
    1042 =>'ItemKindStartDate=',
    1043 =>'ItemKindStartHour=0',
    1044 =>'ItemKindEndDate=',
    1045 =>'ItemKindEndHour=24',
    1046 =>'KindTypeFlag=0',
    1047 =>'ItemKindFlag2Flag=2Y',
    1048 =>'ItemKindCalculateFlag=N',
    1049 =>'ItemKindFlag3To4= ',
    1050 =>'ItemKindFlag5Flag=0',
    1051 =>'ItemKindFlag6Flag=',
    1052 =>'Amount='.$business['POLICY']['BSDI_INSURANCE_AMOUNT'],
    1053 =>'Rate=',
    1054 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['BSDI']['BenchMarkPremium'],
    1055 =>'BasePremium=0',
    1056 =>'DiscountShow=',
    1057 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    1058 =>'AdjustRateShow=',
    1059 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    1060 =>'Premium='.$_SESSION['BUSINESS']['BSDI']['Premium'],
    1061 =>'ItemKind_Flag=',
    1062 =>'KindName=精神损害抚慰金责任险',
    1063 =>'KindCode=R',
    1064 =>'Quantity=',
    1065 =>'UnitAmount=',
    1066 =>'Value=0',
    1067 =>'TotalProfit1=0',
    1068 =>'TotalProfit2=0',
    1069 =>'DeductibleRate=1.00',
    1070 =>'ItemKindStartDate=',
    1071 =>'ItemKindStartHour=0',
    1072 =>'ItemKindEndDate=',
    1073 =>'ItemKindEndHour=24',
    1074 =>'KindTypeFlag=0',
    1075 =>'ItemKindFlag2Flag=2Y',
    1076 =>'ItemKindCalculateFlag=N',
    1077 =>'ItemKindFlag3To4= ',
    1078 =>'ItemKindFlag5Flag=0',
    1079 =>'ItemKindFlag6Flag=',
    1080 =>'Amount=',
    1081 =>'Rate=',
    1082 =>'BenchMarkPremium=',
    1083 =>'BasePremium=0',
    1084 =>'DiscountShow=',
    1085 =>'Discount=',
    1086 =>'AdjustRateShow=',
    1087 =>'AdjustRate=',
    1088 =>'Premium=',
    1089 =>'ItemKind_Flag=',
    1090 =>'KindName=机动车辆污染责任险',
    1091 =>'Value=0',
    1092 =>'KindCode=V',
    1093 =>'Quantity=',
    1094 =>'UnitAmount=',
    1095 =>'TotalProfit1=0',
    1096 =>'TotalProfit2=0',
    1097 =>'DeductibleRate=1.00',
    1098 =>'ItemKindStartDate=',
    1099 =>'ItemKindStartHour=0',
    1100 =>'ItemKindEndDate=',
    1101 =>'ItemKindEndHour=24',
    1102 =>'KindTypeFlag=0',
    1103 =>'ItemKindFlag2Flag=2N',
    1104 =>'ItemKindCalculateFlag=N',
    1105 =>'ItemKindFlag3To4= ',
    1106 =>'ItemKindFlag5Flag=0',
    1107 =>'ItemKindFlag6Flag=',
    1108 =>'Amount=',
    1109 =>'Rate=',
    1110 =>'BenchMarkPremium=',
    1111 =>'BasePremium=0',
    1112 =>'DiscountShow=',
    1113 =>'Discount=',
    1114 =>'AdjustRateShow=',
    1115 =>'AdjustRate=',
    1116 =>'Premium=',
    1117 =>'ItemKind_Flag=',
    1118 =>'KindName=教练车附加险',
    1119 =>'Value=0',
    1120 =>'KindCode=Y',
    1121 =>'Quantity=',
    1122 =>'UnitAmount=',
    1123 =>'TotalProfit1=0',
    1124 =>'TotalProfit2=0',
    1125 =>'DeductibleRate=1.00',
    1126 =>'ItemKindStartDate=',
    1127 =>'ItemKindStartHour=0',
    1128 =>'ItemKindEndDate=',
    1129 =>'ItemKindEndHour=24',
    1130 =>'KindTypeFlag=0',
    1131 =>'ItemKindFlag2Flag=2N',
    1132 =>'ItemKindCalculateFlag=N',
    1133 =>'ItemKindFlag3To4= ',
    1134 =>'ItemKindFlag5Flag=0',
    1135 =>'ItemKindFlag6Flag=',
    1136 =>'Amount=',
    1137 =>'Rate=',
    1138 =>'BenchMarkPremium=',
    1139 =>'BasePremium=0',
    1140 =>'DiscountShow=',
    1141 =>'Discount=',
    1142 =>'AdjustRateShow=',
    1143 =>'AdjustRate=',
    1144 =>'Premium=',
    1145 =>'ItemKind_Flag=',
    1146 =>'KindName=附加机动车辆救助特约险',
    1147 =>'Value=0',
    1148 =>'KindCode=J',
    1149 =>'Quantity=',
    1150 =>'UnitAmount=',
    1151 =>'TotalProfit1=0',
    1152 =>'TotalProfit2=0',
    1153 =>'DeductibleRate=1.00',
    1154 =>'ItemKindStartDate=',
    1155 =>'ItemKindStartHour=0',
    1156 =>'ItemKindEndDate=',
    1157 =>'ItemKindEndHour=24',
    1158 =>'KindTypeFlag=0',
    1159 =>'ItemKindFlag2Flag=2N',
    1160 =>'ItemKindCalculateFlag=N',
    1161 =>'ItemKindFlag3To4= ',
    1162 =>'ItemKindFlag5Flag=0',
    1163 =>'ItemKindFlag6Flag=',
    1164 =>'Amount=',
    1165 =>'Rate=',
    1166 =>'BenchMarkPremium=',
    1167 =>'BasePremium=0',
    1168 =>'DiscountShow=',
    1169 =>'Discount=',
    1170 =>'AdjustRateShow=',
    1171 =>'AdjustRate=',
    1172 =>'Premium=',
    1173 =>'ItemKind_Flag=',
    1174 =>'KindName=零配件更换特约险',
    1175 =>'Value=0',
    1176 =>'KindCode=N',
    1177 =>'Quantity=',
    1178 =>'UnitAmount=',
    1179 =>'TotalProfit1=0',
    1180 =>'TotalProfit2=0',
    1181 =>'DeductibleRate=1.00',
    1182 =>'ItemKindStartDate=',
    1183 =>'ItemKindStartHour=0',
    1184 =>'ItemKindEndDate=',
    1185 =>'ItemKindEndHour=24',
    1186 =>'KindTypeFlag=0',
    1187 =>'ItemKindFlag2Flag=2N',
    1188 =>'ItemKindCalculateFlag=N',
    1189 =>'ItemKindFlag3To4= ',
    1190 =>'ItemKindFlag5Flag=0',
    1191 =>'ItemKindFlag6Flag=',
    1192 =>'Amount=',
    1193 =>'Rate=',
    1194 =>'BenchMarkPremium=',
    1195 =>'BasePremium=0',
    1196 =>'DiscountShow=',
    1197 =>'Discount=',
    1198 =>'AdjustRateShow=',
    1199 =>'AdjustRate=',
    1200 =>'Premium=',
    1201 =>'ItemKind_Flag=',
    1202 =>'KindName=代步车费用特约险',
    1203 =>'KindCode=C',
    1204 =>'UnitAmount=',
    1205 =>'Quantity=',
    1206 =>'Model=1',
    1207 =>'Value=0',
    1208 =>'TotalProfit1=0',
    1209 =>'TotalProfit2=0',
    1210 =>'DeductibleRate=1.00',
    1211 =>'ItemKindStartDate=',
    1212 =>'ItemKindStartHour=0',
    1213 =>'ItemKindEndDate=',
    1214 =>'ItemKindEndHour=24',
    1215 =>'KindTypeFlag=0',
    1216 =>'ItemKindFlag2Flag=2N',
    1217 =>'ItemKindCalculateFlag=N',
    1218 =>'ItemKindFlag3To4= ',
    1219 =>'ItemKindFlag5Flag=0',
    1220 =>'ItemKindFlag6Flag=',
    1221 =>'Amount=',
    1222 =>'Rate=',
    1223 =>'BenchMarkPremium=',
    1224 =>'BasePremium=0',
    1225 =>'DiscountShow=',
    1226 =>'Discount=',
    1227 =>'AdjustRateShow=',
    1228 =>'AdjustRate=',
    1229 =>'Premium=',
    1230 =>'ItemKind_Flag=',
    1231 =>'KindName=整车更换特约险',
    1232 =>'KindCode=S',
    1233 =>'Value=',
    1234 =>'Quantity=',
    1235 =>'UnitAmount=',
    1236 =>'TotalProfit1=0',
    1237 =>'TotalProfit2=0',
    1238 =>'DeductibleRate=1.00',
    1239 =>'ItemKindStartDate=',
    1240 =>'ItemKindStartHour=0',
    1241 =>'ItemKindEndDate=',
    1242 =>'ItemKindEndHour=24',
    1243 =>'KindTypeFlag=0',
    1244 =>'ItemKindFlag2Flag=2N',
    1245 =>'ItemKindCalculateFlag=N',
    1246 =>'ItemKindFlag3To4= ',
    1247 =>'ItemKindFlag5Flag=0',
    1248 =>'ItemKindFlag6Flag=',
    1249 =>'Amount=',
    1250 =>'Rate=',
    1251 =>'BenchMarkPremium=',
    1252 =>'BasePremium=0',
    1253 =>'DiscountShow=',
    1254 =>'Discount=',
    1255 =>'AdjustRateShow=',
    1256 =>'AdjustRate=',
    1257 =>'Premium=',
    1258 =>'ItemKind_Flag=',
    1259 =>'KindName=附加无过失责任险',
    1260 =>'Value=0',
    1261 =>'KindCode=W',
    1262 =>'Quantity=',
    1263 =>'UnitAmount=',
    1264 =>'TotalProfit1=0',
    1265 =>'TotalProfit2=0',
    1266 =>'DeductibleRate=1.00',
    1267 =>'ItemKindStartDate=',
    1268 =>'ItemKindStartHour=0',
    1269 =>'ItemKindEndDate=',
    1270 =>'ItemKindEndHour=24',
    1271 =>'KindTypeFlag=0',
    1272 =>'ItemKindFlag2Flag=2N',
    1273 =>'ItemKindCalculateFlag=N',
    1274 =>'ItemKindFlag3To4= ',
    1275 =>'ItemKindFlag5Flag=0',
    1276 =>'ItemKindFlag6Flag=',
    1277 =>'Amount=',
    1278 =>'Rate=',
    1279 =>'BenchMarkPremium=',
    1280 =>'BasePremium=0',
    1281 =>'DiscountShow=',
    1282 =>'Discount=',
    1283 =>'AdjustRateShow=',
    1284 =>'AdjustRate=',
    1285 =>'Premium=',
    1286 =>'ItemKind_Flag=',
    1287 =>'KindName=新增加设备损失险',
    1288 =>'Value=0',
    1289 =>'KindCode=X',
    1290 =>'Quantity=',
    1291 =>'UnitAmount=',
    1292 =>'TotalProfit1=0',
    1293 =>'TotalProfit2=0',
    1294 =>'DeductibleRate=1.00',
    1295 =>'ItemKindStartDate=',
    1296 =>'ItemKindStartHour=0',
    1297 =>'ItemKindEndDate=',
    1298 =>'ItemKindEndHour=24',
    1299 =>'KindTypeFlag=0',
    1300 =>'ItemKindFlag2Flag=2Y',
    1301 =>'ItemKindCalculateFlag=N',
    1302 =>'ItemKindFlag3To4= ',
    1303 =>'ItemKindFlag5Flag=0',
    1304 =>'ItemKindFlag6Flag=',
    1305 =>'Amount='.$business['POLICY']['NIELI_INSURANCE_AMOUNT'],
    1306 =>'Rate=',
    1307 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['NIELI']['BenchMarkPremium'],
    1308 =>'BasePremium=0',
    1309 =>'DiscountShow=',
    1310 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    1311 =>'AdjustRateShow=',
    1312 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    1313 =>'Premium='.$_SESSION['BUSINESS']['NIELI']['Premium'],
    1314 =>'ItemKind_Flag=',
    1315 =>'KindName=自燃损失险',
    1316 =>'Value=0',
    1317 =>'KindCode=Z',
    1318 =>'Quantity=',
    1319 =>'UnitAmount=',
    1320 =>'TotalProfit1=0',
    1321 =>'TotalProfit2=0',
    1322 =>'DeductibleRate=1.00',
    1323 =>'ItemKindStartDate=',
    1324 =>'ItemKindStartHour=0',
    1325 =>'ItemKindEndDate=',
    1326 =>'ItemKindEndHour=24',
    1327 =>'KindTypeFlag=0',
    1328 =>'ItemKindFlag2Flag=2Y',
    1329 =>'ItemKindCalculateFlag=N',
    1330 =>'ItemKindFlag3To4= ',
    1331 =>'ItemKindFlag5Flag=0',
    1332 =>'ItemKindFlag6Flag=',
    1333 =>'Amount='.$business['POLICY']['SLOI_INSURANCE_AMOUNT'],
    1334 =>'Rate=',
    1335 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['SLOI']['BenchMarkPremium'],
    1336 =>'BasePremium=0',
    1337 =>'DiscountShow=',
    1338 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    1339 =>'AdjustRateShow=',
    1340 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    1341 =>'Premium='.$_SESSION['BUSINESS']['SLOI']['Premium'],
    1342 =>'ItemKind_Flag=',
    1343 =>'KindName=起重、装卸、挖掘车辆损失扩展条款',
    1344 =>'Value=0',
    1345 =>'KindCode=K1',
    1346 =>'Quantity=',
    1347 =>'UnitAmount=',
    1348 =>'TotalProfit1=0',
    1349 =>'TotalProfit2=0',
    1350 =>'DeductibleRate=1.00',
    1351 =>'ItemKindStartDate=',
    1352 =>'ItemKindStartHour=0',
    1353 =>'ItemKindEndDate=',
    1354 =>'ItemKindEndHour=24',
    1355 =>'KindTypeFlag=0',
    1356 =>'ItemKindFlag2Flag=2N',
    1357 =>'ItemKindCalculateFlag=N',
    1358 =>'ItemKindFlag3To4= ',
    1359 =>'ItemKindFlag5Flag=0',
    1360 =>'ItemKindFlag6Flag=',
    1361 =>'Amount=0',
    1362 =>'Rate=',
    1363 =>'BenchMarkPremium=',
    1364 =>'BasePremium=0',
    1365 =>'DiscountShow=',
    1366 =>'Discount=',
    1367 =>'AdjustRateShow=',
    1368 =>'AdjustRate=',
    1369 =>'Premium=',
    1370 =>'ItemKind_Flag=',
    1371 =>'KindName=特种车辆固定设备、仪器损坏扩展条款',
    1372 =>'Value=0',
    1373 =>'KindCode=K2',
    1374 =>'Quantity=',
    1375 =>'UnitAmount=',
    1376 =>'TotalProfit1=0',
    1377 =>'TotalProfit2=0',
    1378 =>'DeductibleRate=1.00',
    1379 =>'ItemKindStartDate=',
    1380 =>'ItemKindStartHour=0',
    1381 =>'ItemKindEndDate=',
    1382 =>'ItemKindEndHour=24',
    1383 =>'KindTypeFlag=0',
    1384 =>'ItemKindFlag2Flag=2N',
    1385 =>'ItemKindCalculateFlag=N',
    1386 =>'ItemKindFlag3To4= ',
    1387 =>'ItemKindFlag5Flag=0',
    1388 =>'ItemKindFlag6Flag=',
    1389 =>'Amount=0',
    1390 =>'Rate=',
    1391 =>'BenchMarkPremium=',
    1392 =>'BasePremium=0',
    1393 =>'DiscountShow=',
    1394 =>'Discount=',
    1395 =>'AdjustRateShow=',
    1396 =>'AdjustRate=',
    1397 =>'Premium=',
    1398 =>'ItemKind_Flag=',
    1399 =>'KindName=特种车辆固定机具、设备损坏扩展条款',
    1400 =>'Value=0',
    1401 =>'KindCode=K3',
    1402 =>'Quantity=',
    1403 =>'UnitAmount=',
    1404 =>'TotalProfit1=0',
    1405 =>'TotalProfit2=0',
    1406 =>'DeductibleRate=1.00',
    1407 =>'ItemKindStartDate=',
    1408 =>'ItemKindStartHour=0',
    1409 =>'ItemKindEndDate=',
    1410 =>'ItemKindEndHour=24',
    1411 =>'KindTypeFlag=0',
    1412 =>'ItemKindFlag2Flag=2N',
    1413 =>'ItemKindCalculateFlag=N',
    1414 =>'ItemKindFlag3To4= ',
    1415 =>'ItemKindFlag5Flag=0',
    1416 =>'ItemKindFlag6Flag=',
    1417 =>'Amount=0',
    1418 =>'Rate=',
    1419 =>'BenchMarkPremium=',
    1420 =>'BasePremium=0',
    1421 =>'DiscountShow=',
    1422 =>'Discount=',
    1423 =>'AdjustRateShow=',
    1424 =>'AdjustRate=',
    1425 =>'Premium=',
    1426 =>'ItemKind_Flag=',
    1427 =>'KindName=发动机涉水损失险',
    1428 =>'Value=0',
    1429 =>'KindCode=X1',
    1430 =>'Quantity=',
    1431 =>'UnitAmount=',
    1432 =>'TotalProfit1=0',
    1433 =>'TotalProfit2=0',
    1434 =>'DeductibleRate=1.00',
    1435 =>'ItemKindStartDate=',
    1436 =>'ItemKindStartHour=0',
    1437 =>'ItemKindEndDate=',
    1438 =>'ItemKindEndHour=24',
    1439 =>'KindTypeFlag=0',
    1440 =>'ItemKindFlag2Flag=2Y',
    1441 =>'ItemKindCalculateFlag=N',
    1442 =>'ItemKindFlag3To4= ',
    1443 =>'ItemKindFlag5Flag=0',
    1444 =>'ItemKindFlag6Flag=',
    1445 =>'Amount=',
    1446 =>'Rate=',
    1447 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['VWTLI']['BenchMarkPremium'],
    1448 =>'BasePremium=0',
    1449 =>'DiscountShow=',
    1450 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    1451 =>'AdjustRateShow=',
    1452 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    1453 =>'Premium='.$_SESSION['BUSINESS']['VWTLI']['Premium'],
    1454 =>'ItemKind_Flag=',
    1455 =>'KindName=随车行李物品损失保险',
    1456 =>'Value=0',
    1457 =>'KindCode=X2',
    1458 =>'Quantity=',
    1459 =>'UnitAmount=',
    1460 =>'TotalProfit1=0',
    1461 =>'TotalProfit2=0',
    1462 =>'DeductibleRate=1.00',
    1463 =>'ItemKindStartDate=',
    1464 =>'ItemKindStartHour=0',
    1465 =>'ItemKindEndDate=',
    1466 =>'ItemKindEndHour=24',
    1467 =>'KindTypeFlag=0',
    1468 =>'ItemKindFlag2Flag=2N',
    1469 =>'ItemKindCalculateFlag=N',
    1470 =>'ItemKindFlag3To4= ',
    1471 =>'ItemKindFlag5Flag=0',
    1472 =>'ItemKindFlag6Flag=',
    1473 =>'Amount=',
    1474 =>'Rate=',
    1475 =>'BenchMarkPremium=',
    1476 =>'BasePremium=0',
    1477 =>'DiscountShow=',
    1478 =>'Discount=',
    1479 =>'AdjustRateShow=',
    1480 =>'AdjustRate=',
    1481 =>'Premium=',
    1482 =>'ItemKind_Flag=',
    1483 =>'KindName=附加车轮单独损坏保险',
    1484 =>'Value=0',
    1485 =>'KindCode=X3',
    1486 =>'Quantity=',
    1487 =>'UnitAmount=',
    1488 =>'TotalProfit1=0',
    1489 =>'TotalProfit2=0',
    1490 =>'DeductibleRate=1.00',
    1491 =>'ItemKindStartDate=',
    1492 =>'ItemKindStartHour=0',
    1493 =>'ItemKindEndDate=',
    1494 =>'ItemKindEndHour=24',
    1495 =>'KindTypeFlag=0',
    1496 =>'ItemKindFlag2Flag=2N',
    1497 =>'ItemKindCalculateFlag=N',
    1498 =>'ItemKindFlag3To4= ',
    1499 =>'ItemKindFlag5Flag=0',
    1500 =>'ItemKindFlag6Flag=',
    1501 =>'Amount=',
    1502 =>'Rate=',
    1503 =>'BenchMarkPremium=',
    1504 =>'BasePremium=0',
    1505 =>'DiscountShow=',
    1506 =>'Discount=',
    1507 =>'AdjustRateShow=',
    1508 =>'AdjustRate=',
    1509 =>'Premium=',
    1510 =>'ItemKind_Flag=',
    1511 =>'KindName=附加机动车出境保险',
    1512 =>'KindCode=P1',
    1513 =>'Quantity=',
    1514 =>'UnitAmount=',
    1515 =>'Model=1',
    1516 =>'Value=0',
    1517 =>'TotalProfit1=0',
    1518 =>'TotalProfit2=0',
    1519 =>'DeductibleRate=1.00',
    1520 =>'ItemKindStartDate=',
    1521 =>'ItemKindStartHour=0',
    1522 =>'ItemKindEndDate=',
    1523 =>'ItemKindEndHour=24',
    1524 =>'KindTypeFlag=0',
    1525 =>'ItemKindFlag2Flag=2N',
    1526 =>'ItemKindCalculateFlag=N',
    1527 =>'ItemKindFlag3To4= ',
    1528 =>'ItemKindFlag5Flag=0',
    1529 =>'ItemKindFlag6Flag=',
    1530 =>'Amount=',
    1531 =>'Rate=',
    1532 =>'BenchMarkPremium=',
    1533 =>'BasePremium=0',
    1534 =>'DiscountShow=',
    1535 =>'Discount=',
    1536 =>'AdjustRateShow=',
    1537 =>'AdjustRate=',
    1538 =>'Premium=',
    1539 =>'ItemKind_Flag=',
    1540 =>'KindName=指定修理厂险',
    1541 =>'KindCode=A4',
    1542 =>'Quantity=',
    1543 =>'UnitAmount=',
    1544 =>'DeductibleRate=10',
    1545 =>'Value=0',
    1546 =>'TotalProfit1=0',
    1547 =>'TotalProfit2=0',
    1548 =>'ItemKindStartDate=',
    1549 =>'ItemKindStartHour=0',
    1550 =>'ItemKindEndDate=',
    1551 =>'ItemKindEndHour=24',
    1552 =>'KindTypeFlag=0',
    1553 =>'ItemKindFlag2Flag=2N',
    1554 =>'ItemKindCalculateFlag=N',
    1555 =>'ItemKindFlag3To4= ',
    1556 =>'ItemKindFlag5Flag=0',
    1557 =>'ItemKindFlag6Flag=',
    1558 =>'Amount=',
    1559 =>'Rate=',
    1560 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['STSFS']['BenchMarkPremium'],
    1561 =>'BasePremium=0',
    1562 =>'DiscountShow=',
    1563 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    1564 =>'AdjustRateShow=',
    1565 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    1566 =>'Premium='.$_SESSION['BUSINESS']['STSFS']['Premium'],
    1567 =>'ItemKind_Flag=',
    1568 =>'KindName=多次出险增加免赔率特约险',
    1569 =>'KindCode=A5',
    1570 =>'Quantity=',
    1571 =>'UnitAmount=',
    1572 =>'DeductibleRate=-2.00',
    1573 =>'Value=0',
    1574 =>'TotalProfit1=0',
    1575 =>'TotalProfit2=0',
    1576 =>'ItemKindStartDate=',
    1577 =>'ItemKindStartHour=0',
    1578 =>'ItemKindEndDate=',
    1579 =>'ItemKindEndHour=24',
    1580 =>'KindTypeFlag=0',
    1581 =>'ItemKindFlag2Flag=2N',
    1582 =>'ItemKindCalculateFlag=N',
    1583 =>'ItemKindFlag3To4= ',
    1584 =>'ItemKindFlag5Flag=0',
    1585 =>'ItemKindFlag6Flag=',
    1586 =>'Amount=',
    1587 =>'Rate=',
    1588 =>'BenchMarkPremium=',
    1589 =>'BasePremium=0',
    1590 =>'DiscountShow=',
    1591 =>'Discount=',
    1592 =>'AdjustRateShow=',
    1593 =>'AdjustRate=',
    1594 =>'Premium=',
    1595 =>'ItemKind_Flag=',
    1596 =>'KindName=机动车法律费用特约险',
    1597 =>'KindCode=U',
    1598 =>'Quantity=',
    1599 =>'UnitAmount=',
    1600 =>'Value=0',
    1601 =>'TotalProfit1=0',
    1602 =>'TotalProfit2=0',
    1603 =>'DeductibleRate=1.00',
    1604 =>'ItemKindStartDate=',
    1605 =>'ItemKindStartHour=0',
    1606 =>'ItemKindEndDate=',
    1607 =>'ItemKindEndHour=24',
    1608 =>'KindTypeFlag=0',
    1609 =>'ItemKindFlag2Flag=2N',
    1610 =>'ItemKindCalculateFlag=N',
    1611 =>'ItemKindFlag3To4= ',
    1612 =>'ItemKindFlag5Flag=0',
    1613 =>'ItemKindFlag6Flag=',
    1614 =>'Amount=',
    1615 =>'Rate=',
    1616 =>'BenchMarkPremium=',
    1617 =>'BasePremium=0',
    1618 =>'DiscountShow=',
    1619 =>'Discount=',
    1620 =>'AdjustRateShow=',
    1621 =>'AdjustRate=',
    1622 =>'Premium=',
    1623 =>'ItemKind_Flag=',
    1624 =>'KindName=车辆损失保险无法找到第三方特约险',
    1625 =>'KindCode=A6',
    1626 =>'Quantity=',
    1627 =>'UnitAmount=',
    1628 =>'Value=0',
    1629 =>'TotalProfit1=0',
    1630 =>'TotalProfit2=0',
    1631 =>'DeductibleRate=1.00',
    1632 =>'ItemKindStartDate=',
    1633 =>'ItemKindStartHour=0',
    1634 =>'ItemKindEndDate=',
    1635 =>'ItemKindEndHour=24',
    1636 =>'KindTypeFlag=0',
    1637 =>'ItemKindFlag2Flag=2N',
    1638 =>'ItemKindCalculateFlag=N',
    1639 =>'ItemKindFlag3To4= ',
    1640 =>'ItemKindFlag5Flag=0',
    1641 =>'ItemKindFlag6Flag=',
    1642 =>'Amount=',
    1643 =>'Rate=',
    1644 =>'BenchMarkPremium='.$_SESSION['BUSINESS']['MVLINFTPSI']['BenchMarkPremium'],
    1645 =>'BasePremium=0',
    1646 =>'DiscountShow=',
    1647 =>'Discount='.$_SESSION['Auditing']['Premium_Discount'],
    1648 =>'AdjustRateShow=',
    1649 =>'AdjustRate='.$_SESSION['Auditing']['no_claim_bonus'],
    1650 =>'Premium='.$_SESSION['BUSINESS']['MVLINFTPSI']['Premium'],
    1651 =>'ProfitPageCode=',
    1652 =>'ProfitPageSerialNo=',
    1653 =>'ProfitPageRate=',
    1654 =>'ProfitPageName=',
    1655 =>'ProfitPagePeriod=',
    1656 =>'ProfitPageFieldValue=',
    1657 =>'ProfitPageCondition=',
    1658 =>'A_ProfitRateShow=',
    1659 =>'A_ProfitRate=',
    1660 =>'A_ProfitCode=',
    1661 =>'A_ProfitSerialNo=',
    1662 =>'A_ProfitTypeFlag=',
    1663 =>'B_ProfitRateShow=',
    1664 =>'B_ProfitRate=',
    1665 =>'B_ProfitCode=',
    1666 =>'B_ProfitSerialNo=',
    1667 =>'B_ProfitTypeFlag=',
    1668 =>'G1_ProfitRateShow=',
    1669 =>'G1_ProfitRate=',
    1670 =>'G1_ProfitCode=',
    1671 =>'G1_ProfitSerialNo=',
    1672 =>'G1_ProfitTypeFlag=',
    1673 =>'L_ProfitRateShow=',
    1674 =>'L_ProfitRate=',
    1675 =>'L_ProfitCode=',
    1676 =>'L_ProfitSerialNo=',
    1677 =>'L_ProfitTypeFlag=',
    1678 =>'A4_ProfitRateShow=',
    1679 =>'A4_ProfitRate=',
    1680 =>'A4_ProfitCode=',
    1681 =>'A4_ProfitSerialNo=',
    1682 =>'A4_ProfitTypeFlag=',
    1683 =>'O_ProfitRateShow=',
    1684 =>'O_ProfitRate=',
    1685 =>'O_ProfitCode=',
    1686 =>'O_ProfitSerialNo=',
    1687 =>'O_ProfitTypeFlag=',
    1688 =>'ProfitAffirmFlag=1',
    1689 =>'Range14Order=2',
    1690 =>'Range11Order=0',
    1691 =>'A_SumProfitRateShow=0.552713',
    1692 =>'A_SumProfitRate=44.7287',
    1693 =>'A_DisCount=34.9749',
    1694 =>'A_DisCount1=44.7287',
    1695 =>'A_Adjust=15',
    1696 =>'A_NoRangeDiscount=0.85',
    1697 =>'A_AutoRangeFlag=0',
    1698 =>'B_SumProfitRateShow=0.552713',
    1699 =>'B_SumProfitRate=44.7287',
    1700 =>'B_DisCount=34.9749',
    1701 =>'B_DisCount1=44.7287',
    1702 =>'B_Adjust=15',
    1703 =>'B_NoRangeDiscount=0.85',
    1704 =>'B_AutoRangeFlag=0',
    1705 =>'G1_SumProfitRateShow=1',
    1706 =>'G1_SumProfitRate=0',
    1707 =>'G1_DisCount=0',
    1708 =>'G1_DisCount1=0',
    1709 =>'G1_Adjust=0',
    1710 =>'G1_NoRangeDiscount=1',
    1711 =>'G1_AutoRangeFlag=0',
    1712 =>'L_SumProfitRateShow=1',
    1713 =>'L_SumProfitRate=0',
    1714 =>'L_DisCount=0',
    1715 =>'L_DisCount1=0',
    1716 =>'L_Adjust=0',
    1717 =>'L_NoRangeDiscount=1',
    1718 =>'L_AutoRangeFlag=0',
    1719 =>'A4_SumProfitRateShow=1',
    1720 =>'A4_SumProfitRate=0',
    1721 =>'A4_DisCount=0',
    1722 =>'A4_DisCount1=0',
    1723 =>'A4_Adjust=0',
    1724 =>'A4_NoRangeDiscount=1',
    1725 =>'A4_AutoRangeFlag=0',
    1726 =>'O_SumProfitRateShow='.$_SESSION['BUSINESS']['DISCOUT_COUNT'],
    1727 =>'O_SumProfitRate='.$_SESSION['Auditing']['BUSINESS_DISCOUT_COUNT'],
    1728 =>'O_DisCount=0',
    1729 =>'O_DisCount1='.$_SESSION['Auditing']['BUSINESS_DISCOUT_COUNT'],
    1730 =>'O_Adjust=0',
    1731 =>'O_NoRangeDiscount=1',
    1732 =>'O_AutoRangeFlag=0',
    1733 =>'ProfitPageCode=13',
    1734 =>'ProfitPageSerialNo=1',
    1735 =>'ProfitPageRate=15.0',
    1736 =>'ProfitPageName=无赔款优待系数',
    1737 =>'ProfitPagePeriod=1',
    1738 =>'ProfitPageFieldValue=',
    1739 =>'ProfitPageCondition=无赔款优待系数',
    1740 =>'A_ProfitType=on',
    1741 =>'A_ProfitRateShow=0.85',
    1742 =>'A_ProfitRate=15.0',
    1743 =>'A_ProfitCode=13',
    1744 =>'A_ProfitSerialNo=1',
    1745 =>'A_ProfitTypeFlag=1',
    1746 =>'B_ProfitType=',
    1747 =>'B_ProfitRateShow=0.85',
    1748 =>'B_ProfitRate=15.0',
    1749 =>'B_ProfitCode=13',
    1750 =>'B_ProfitSerialNo=1',
    1751 =>'B_ProfitTypeFlag=1',
    1752 =>'G1_ProfitRateShow=0.85',
    1753 =>'G1_ProfitRate=15.0',
    1754 =>'G1_ProfitCode=13',
    1755 =>'G1_ProfitSerialNo=1',
    1756 =>'G1_ProfitTypeFlag=',
    1757 =>'L_ProfitRateShow=0.85',
    1758 =>'L_ProfitRate=15.0',
    1759 =>'L_ProfitCode=13',
    1760 =>'L_ProfitSerialNo=1',
    1761 =>'L_ProfitTypeFlag=',
    1762 =>'A4_ProfitRateShow=0.85',
    1763 =>'A4_ProfitRate=15.0',
    1764 =>'A4_ProfitCode=13',
    1765 =>'A4_ProfitSerialNo=1',
    1766 =>'A4_ProfitTypeFlag=',
    1767 =>'O_ProfitRateShow=0.85',
    1768 =>'O_ProfitRate=15.0',
    1769 =>'O_ProfitCode=13',
    1770 =>'O_ProfitSerialNo=1',
    1771 =>'O_ProfitTypeFlag=',
    1772 =>'ProfitPageCode=14',
    1773 =>'ProfitPageSerialNo=1',
    1774 =>'ProfitPageRate=10.0',
    1775 =>'ProfitPageName=交通违法系数',
    1776 =>'ProfitPagePeriod=1',
    1777 =>'ProfitPageFieldValue=',
    1778 =>'ProfitPageCondition=交通违法系数',
    1779 =>'A_ProfitType=on',
    1780 =>'A_ProfitRateShow=0.9',
    1781 =>'A_ProfitRate=10.0',
    1782 =>'A_ProfitCode=14',
    1783 =>'A_ProfitSerialNo=1',
    1784 =>'A_ProfitTypeFlag=1',
    1785 =>'B_ProfitType=',
    1786 =>'B_ProfitRateShow=0.9',
    1787 =>'B_ProfitRate=10.0',
    1788 =>'B_ProfitCode=14',
    1789 =>'B_ProfitSerialNo=1',
    1790 =>'B_ProfitTypeFlag=1',
    1791 =>'G1_ProfitRateShow=0.9',
    1792 =>'G1_ProfitRate=10.0',
    1793 =>'G1_ProfitCode=14',
    1794 =>'G1_ProfitSerialNo=1',
    1795 =>'G1_ProfitTypeFlag=',
    1796 =>'L_ProfitRateShow=0.9',
    1797 =>'L_ProfitRate=10.0',
    1798 =>'L_ProfitCode=14',
    1799 =>'L_ProfitSerialNo=1',
    1800 =>'L_ProfitTypeFlag=',
    1801 =>'A4_ProfitRateShow=0.9',
    1802 =>'A4_ProfitRate=10.0',
    1803 =>'A4_ProfitCode=14',
    1804 =>'A4_ProfitSerialNo=1',
    1805 =>'A4_ProfitTypeFlag=',
    1806 =>'O_ProfitRateShow=0.9',
    1807 =>'O_ProfitRate=10.0',
    1808 =>'O_ProfitCode=14',
    1809 =>'O_ProfitSerialNo=1',
    1810 =>'O_ProfitTypeFlag=',
    1811 =>'ProfitPageCode=22',
    1812 =>'ProfitPageSerialNo=1',
    1813 =>'ProfitPageRate=14.9999',
    1814 =>'ProfitPageName=承保组合',
    1815 =>'ProfitPagePeriod=1',
    1816 =>'ProfitPageFieldValue=',
    1817 =>'ProfitPageCondition=承保组合',
    1818 =>'A_ProfitType=on',
    1819 =>'A_ProfitRateShow=0.850001',
    1820 =>'A_ProfitRate=14.9999',
    1821 =>'A_ProfitCode=22',
    1822 =>'A_ProfitSerialNo=1',
    1823 =>'A_ProfitTypeFlag=1',
    1824 =>'B_ProfitType=',
    1825 =>'B_ProfitRateShow=0.850001',
    1826 =>'B_ProfitRate=14.9999',
    1827 =>'B_ProfitCode=22',
    1828 =>'B_ProfitSerialNo=1',
    1829 =>'B_ProfitTypeFlag=1',
    1830 =>'G1_ProfitRateShow=0.850001',
    1831 =>'G1_ProfitRate=14.9999',
    1832 =>'G1_ProfitCode=22',
    1833 =>'G1_ProfitSerialNo=1',
    1834 =>'G1_ProfitTypeFlag=',
    1835 =>'L_ProfitRateShow=0.850001',
    1836 =>'L_ProfitRate=14.9999',
    1837 =>'L_ProfitCode=22',
    1838 =>'L_ProfitSerialNo=1',
    1839 =>'L_ProfitTypeFlag=',
    1840 =>'A4_ProfitRateShow=0.850001',
    1841 =>'A4_ProfitRate=14.9999',
    1842 =>'A4_ProfitCode=22',
    1843 =>'A4_ProfitSerialNo=1',
    1844 =>'A4_ProfitTypeFlag=',
    1845 =>'O_ProfitRateShow=0.850001',
    1846 =>'O_ProfitRate=14.9999',
    1847 =>'O_ProfitCode=22',
    1848 =>'O_ProfitSerialNo=1',
    1849 =>'O_ProfitTypeFlag=',
    1850 =>'ProfitPageCode=39',
    1851 =>'ProfitPageSerialNo=1',
    1852 =>'ProfitPageRate=15.0',
    1853 =>'ProfitPageName=渠道',
    1854 =>'ProfitPagePeriod=1',
    1855 =>'ProfitPageFieldValue=',
    1856 =>'ProfitPageCondition=渠道',
    1857 =>'A_ProfitType=on',
    1858 =>'A_ProfitRateShow=0.85',
    1859 =>'A_ProfitRate=15.0',
    1860 =>'A_ProfitCode=39',
    1861 =>'A_ProfitSerialNo=1',
    1862 =>'A_ProfitTypeFlag=1',
    1863 =>'B_ProfitType=',
    1864 =>'B_ProfitRateShow=0.85',
    1865 =>'B_ProfitRate=15.0',
    1866 =>'B_ProfitCode=39',
    1867 =>'B_ProfitSerialNo=1',
    1868 =>'B_ProfitTypeFlag=1',
    1869 =>'G1_ProfitRateShow=0.85',
    1870 =>'G1_ProfitRate=15.0',
    1871 =>'G1_ProfitCode=39',
    1872 =>'G1_ProfitSerialNo=1',
    1873 =>'G1_ProfitTypeFlag=',
    1874 =>'L_ProfitRateShow=0.85',
    1875 =>'L_ProfitRate=15.0',
    1876 =>'L_ProfitCode=39',
    1877 =>'L_ProfitSerialNo=1',
    1878 =>'L_ProfitTypeFlag=',
    1879 =>'A4_ProfitRateShow=0.85',
    1880 =>'A4_ProfitRate=15.0',
    1881 =>'A4_ProfitCode=39',
    1882 =>'A4_ProfitSerialNo=1',
    1883 =>'A4_ProfitTypeFlag=',
    1884 =>'O_ProfitRateShow=0.85',
    1885 =>'O_ProfitRate=15.0',
    1886 =>'O_ProfitCode=39',
    1887 =>'O_ProfitSerialNo=1',
    1888 =>'O_ProfitTypeFlag=',
    1889 =>'ProfitPageCode=27',
    1890 =>'ProfitPageSerialNo=1',
    1891 =>'ProfitPageRate=0',
    1892 =>'ProfitPageName=续保',
    1893 =>'ProfitPagePeriod=1',
    1894 =>'ProfitPageFieldValue=1',
    1895 =>'ProfitPageCondition=续保',
    1896 =>'A_ProfitRateShow=0.9',
    1897 =>'A_ProfitRate=10',
    1898 =>'A_ProfitCode=27',
    1899 =>'A_ProfitSerialNo=1',
    1900 =>'A_ProfitTypeFlag=0',
    1901 =>'B_ProfitRateShow=0.9',
    1902 =>'B_ProfitRate=10',
    1903 =>'B_ProfitCode=27',
    1904 =>'B_ProfitSerialNo=1',
    1905 =>'B_ProfitTypeFlag=0',
    1906 =>'G1_ProfitRateShow=0.9',
    1907 =>'G1_ProfitRate=10',
    1908 =>'G1_ProfitCode=27',
    1909 =>'G1_ProfitSerialNo=1',
    1910 =>'G1_ProfitTypeFlag=0',
    1911 =>'L_ProfitRateShow=0.9',
    1912 =>'L_ProfitRate=10',
    1913 =>'L_ProfitCode=27',
    1914 =>'L_ProfitSerialNo=1',
    1915 =>'L_ProfitTypeFlag=0',
    1916 =>'A4_ProfitRateShow=0.9',
    1917 =>'A4_ProfitRate=10',
    1918 =>'A4_ProfitCode=27',
    1919 =>'A4_ProfitSerialNo=1',
    1920 =>'A4_ProfitTypeFlag=0',
    1921 =>'O_ProfitRateShow=0.9',
    1922 =>'O_ProfitRate=10',
    1923 =>'O_ProfitCode=27',
    1924 =>'O_ProfitSerialNo=1',
    1925 =>'O_ProfitTypeFlag=0',
    1926 =>'PlanOneTimes=1',
    1927 =>'PayTimes=1',
    1928 =>'PayNo=',
    1929 =>'PayReason=',
    1930 =>'PlanDate=',
    1931 =>'PrpPlanCurrency=',
    1932 =>'PrpPlanCurrencyName=',
    1933 =>'PlanFee=',
    1934 =>'DelinquentFee=',
    1935 =>'CompulsoryFlag=',
    1936 =>'PayNo=1',
    1937 =>'PayReason=R10',
    1938 =>'PlanDate='.$mv['start_time'],
    1939 =>'PrpPlanCurrency=CNY',
    1940 =>'PrpPlanCurrencyName=人民币',
    1941 =>'PlanFee='.$_SESSION['MVTALCI']['MVTALCI_PREMIUM'],
    1942 =>'DelinquentFee='.$_SESSION['MVTALCI']['MVTALCI_PREMIUM'],
    1943 =>'CompulsoryFlag=1',
    1944 =>'PayNo=1',
    1945 =>'PayReason=R10',
    1946 =>'PlanDate='.$mv['start_time'],
    1947 =>'PrpPlanCurrency=CNY',
    1948 =>'PrpPlanCurrencyName=人民币',
    1949 =>'PlanFee='.$busin['PlanFee'],
    1950 =>'DelinquentFee='.$busin['PlanFee'],
    1951 =>'CompulsoryFlag=0',
    1952 =>'checkCarTaxFlag=CarTaxQG',
    1953 =>'TAX_CONDITION_CODE=T',
    1954 =>'TAX_PAYER_IDENTIFICATION_CODE=',
    1955 =>'TAX_PAYER_NAME='.$auto['OWNER'],
    1956 =>'DEDUCTION_DUE_CODE=',
    1957 =>'DEDUCTION_DUE_TYPE=',
    1958 =>'DEDUCTION_DOCUMENT_NUMBER=',
    1959 =>'TAX_DEPARTMENT_DECU=',
    1960 =>'DEDUCTION_DUE_PROPORTION=',
    1961 =>'DEDUCTION_DUE=',
    1962 =>'TAX_DEPARTMENT=',
    1963 =>'TAX_DOCUMENT_NUMBER=',
    1964 =>'cartax_VehicleStyle=',
    1965 =>'TaxTermTypeCode=08',
    1966 =>'IsCarTaxQGNew=yes',
    1967 =>'TaxConditionCode=T',
    1968 =>'TaxPayerIdentificationCode=',
    1969 =>'TaxPayerName='.$auto['OWNER'],
    1970 =>'AnnualTaxDue='.$_SESSION['MVTALCI']['TRAVEL_TAX_PREMIUM'],
    1971 =>'SumTaxDefault=0.00',
    1972 =>'SumOverdue=0.00',
    1973 =>'SumTax='.$_SESSION['MVTALCI']['TRAVEL_TAX_PREMIUM'],
    1974 =>'TaxRegistryNumber=32108476651913X',
    1975 =>'DeclareDate=',
    1976 =>'DeclareStatusIA=0',
    1977 =>'TaxAmountFlag=1',
    1978 =>'CalcTaxFlag=2',
    1979 =>'TaxLocationCode=320000',
    1980 =>'TaxStartDate=2017-01-01',
    1981 =>'TaxEndDate=2017-12-31',
    1982 =>'TaxUnitTypeCode=1',
    1983 =>'UnitRate='.$_SESSION['MVTALCI']['TRAVEL_TAX_PREMIUM'],
    1984 =>'AnnualTaxAmount='.$_SESSION['MVTALCI']['TRAVEL_TAX_PREMIUM'],
    1985 =>'TaxDue='.$_SESSION['MVTALCI']['TRAVEL_TAX_PREMIUM'],
    1986 =>'ExceedDate=',
    1987 =>'ExceedDaysCount=0',
    1988 =>'OverDue=0.00',
    1989 =>'TotalAmount='.$_SESSION['MVTALCI']['TRAVEL_TAX_PREMIUM'],
    1990 =>'TaxDocumentNumber=',
    1991 =>'TaxDepartmentCode=',
    1992 =>'TaxDepartment=',
    1993 =>'DeductionDueCode=',
    1994 =>'DeductionDueType=',
    1995 =>'DeductionDueProportion=',
    1996 =>'Deduction=',
    1997 =>'DeductionDocumentNumber=',
    1998 =>'TaxDepartmentCode_Decu=',
    1999 =>'TaxDepartment_Decu=',
    2000 =>'TCICommissionKindCode=BZ',
    2001 =>'TCICommission=0.0',
    2002 =>'TCITopCommission=0.0',
    2003 =>'TCIOriginCommission=0.0',
    2004 =>'TCIRuleEngineCommission=',
    2005 =>'TCICommissionFlag=0',
    2006 =>'CommissionKindCode=B',
    2007 =>'Commission=40.0',
    2008 =>'TopCommission=42.0',
    2009 =>'OriginCommission=40.0',
    2010 =>'RuleEngineCommission=',
    2011 =>'CommissionFlag=0',
    2012 =>'InputTime=2017-05-16 09:54:12',
    2013 =>'QueryTime=2017-05-16 09:54:12',
    2014 =>'PermitNo=321084199501151119',
    2015 =>'ValidStartDate=2016-08-03',
    2016 =>'ValidEndDate=2019-08-02',
    2017 =>'AgentCodeClick=true',
    2018 =>'Today=2017-05-16',
    2019 =>'MakeCom=32010535',
    2020 =>'ArgueSolution=1',
    2021 =>'ArbitBoardCode=',
    2022 =>'ArbitBoardName=',
    2023 =>'CarChecker=',
    2024 =>'CarCheckTime=',
    2025 =>'BusinessContry=0',
    2026 =>'IsAboutAgri=0',
    2027 =>'DZprintNameNew=',
    2028 =>'AutoInsurancePackageNew=',
    2029 =>'AutoInsurancePackage2New=',
    2030 =>'RepairChannelNameNew=',
    2031 =>'ProjectNameNew=',
    2032 =>'DealerCode=',
    2033 =>'DealerName=',
    2034 =>'carDealerActivityOldFlag=',
    2035 =>'isMainPlus=true',
    2036 =>'userMobile=18852556608',
    2037 =>'isAddProno=false',
    2038 =>'intermediaryAddrForAgent=',
    2039 =>'isNotReadOnlyDirect=null',
    2040 =>'salesManNameInfo=武宵',
    2041 =>'mobileInfo=18852556608',
    2042 =>'OccupationIDForAgent=',
    2043 =>'InputDate=2017-05-16',
    2044 =>'OperateDate=2017-05-16',
    2045 =>'OperateSite=',
    2046 =>'ICcardArea=',
    2047 =>'ExtranetSingleFlag=',
    2048 =>'AgentAccountFlag=',
    2049 =>'ICAgentName=',
    2050 =>'ICAgentLicNo=',
    2051 =>'ICValidBeginDate=',
    2052 =>'ICValidEndDate=',
    2053 =>'intCount=0',
    2054 =>'EndorseTimes=',
    2055 =>'ReinsFlag=0',
    2056 =>'OtherNature2=0',
    2057 =>'OtherNature4=1',
    2058 =>'Other_Flag=',
    2059 =>'Renewall85AdjustRate_Flag=',
    2060 =>'CommissionRateUpperForBZ=',
    2061 =>'CommissionRateUpper=',
    2062 =>'CheckNB=',
    2063 =>'AgentCode1=',
    2064 =>'AgreementNo1=',
    2065 =>'RelationPolicyNo=',
    2066 =>'VerifyCode=',
    2067 =>'isverity=0',
    2068 =>'certiNo=',
    2069 =>'riskCode=',
    2070 =>'natureIdentityAppli=3',
    2071 =>'natureIdentityInsure=3',
    2072 =>'nationalityAppli=',
    2073 =>'sexAppli=',
    2074 =>'unitNatureAppli=',
    2075 =>'professionAppli=',
    2076 =>'positionAppli=',
    2077 =>'identityTypeAppli=',
    2078 =>'identityEndDateAppli=',
    2079 =>'agentTypeAppli=',
    2080 =>'agentNameAppli=',
    2081 =>'agentPhoneAppli=',
    2082 =>'agentIdentityTypeAppli=',
    2083 =>'agentIdentityNoAppli=',
    2084 =>'agentidentityEDateAppli=',
    2085 =>'isformalitiesCompleteAppli=',
    2086 =>'ageAppli=',
    2087 =>'isIdentityCheckAppli=',
    2088 =>'taxRegistryNoAppli=',
    2089 =>'businessLicenseNoAppli=',
    2090 =>'businessRangeAppli=',
    2091 =>'licensenoStartDateAppli=',
    2092 =>'licensenoEndDateAppli=',
    2093 =>'legalRepresentativeAppli=',
    2094 =>'controlshareholderAppli=',
    2095 =>'authorizationManagerAppli=',
    2096 =>'legalRepresentativeIDTypeAppli=',
    2097 =>'controlshareholderIDTypeAppli=',
    2098 =>'authorizationManagerIDTypeAppli=',
    2099 =>'legalRepresentativeIDNoAppli=',
    2100 =>'controlshareholderIDNoAppli=',
    2101 =>'authorizationManagerIDNoAppli=',
    2102 =>'legalRepresentativeIDEDAppli=',
    2103 =>'controlshareholderIDEDAppli=',
    2104 =>'authorizationManagerIDEDAppli=',
    2105 =>'shareholderTypeAppli=',
    2106 =>'flagAppli=100000',
    2107 =>'nationalityInsure=',
    2108 =>'sexInsure=',
    2109 =>'unitNatureInsure=',
    2110 =>'professionInsure=',
    2111 =>'positionInsure=',
    2112 =>'identityTypeInsure=',
    2113 =>'identityEndDateInsure=',
    2114 =>'agentTypeInsure=',
    2115 =>'agentNameInsure=',
    2116 =>'agentPhoneInsure=',
    2117 =>'agentIdentityTypeInsure=',
    2118 =>'agentIdentityNoInsure=',
    2119 =>'agentidentityEDateInsure=',
    2120 =>'isformalitiesCompleteInsure=',
    2121 =>'ageInsure=',
    2122 =>'isIdentityCheckInsure=',
    2123 =>'taxRegistryNoInsure=',
    2124 =>'businessLicenseNoInsure=',
    2125 =>'businessRangeInsure=',
    2126 =>'licensenoStartDateInsure=',
    2127 =>'licensenoEndDateInsure=',
    2128 =>'legalRepresentativeInsure=',
    2129 =>'controlshareholderInsure=',
    2130 =>'authorizationManagerInsure=',
    2131 =>'legalRepresentativeIDTypeInsure=',
    2132 =>'controlshareholderIDTypeInsure=',
    2133 =>'authorizationManagerIDTypeInsure=',
    2134 =>'legalRepresentativeIDNoInsure=',
    2135 =>'controlshareholderIDNoInsure=',
    2136 =>'authorizationManagerIDNoInsure=',
    2137 =>'legalRepresentativeIDEDInsure=',
    2138 =>'controlshareholderIDEDInsure=',
    2139 =>'authorizationManagerIDEDInsure=',
    2140 =>'shareholderTypeInsure=',
    2141 =>'flagInsure=010000',
    2142 =>'RemarkValue=',
    2143 =>'realTimeFlagForBZ=112',
    2144 =>'realTimeFlagForCIP=102',
    2145 =>'hasCorrentDateConfig=0',
    2146 =>'saveNotice=true',
    2147 =>'ClauseName=',
    2148 =>'BJClauseCode=',
    2149 =>'clickIndex=0',
    2150 =>'defaultClausesContext=',
    2151 =>'Engage_Flag=',
    2152 =>'EngageSerialNo=',
    2153 =>'ClauseCode=',
    2154 =>'Clauses=',
    2155 =>'ClausesContext=',
    2156 =>'RiskCodeE=',
    2157 =>'ClassCodeE=',
    2158 =>'RiskCodeN=',
    2159 =>'ClassCodeN=',
    2160 =>'RateFormulaFlagE=',
    2161 =>'CarNatureE=',
    2162 =>'AgentPermitNoE=',
    2163 =>'CheckmainloanE=',
    2164 =>'uniteNFlag=1',
    2165 =>'BusinessNature1E=',
    2166 =>'InsuredNameE=',
    2167 =>'IdentifyTypeE=01',
    2168 =>'IdentifyNumberE=',
    2169 =>'FormulaCodeE=00',
    2170 =>'quantityE=1',
    2171 =>'KindCodeMainE=',
    2172 =>'KindNameMainE=',
    2173 =>'TopAmountE=',
    2174 =>'LowAmountE=',
    2175 =>'TopRateE=',
    2176 =>'LowRateE=',
    2177 =>'ItemCodeMainE=',
    2178 =>'ItemNameMainE=',
    2179 =>'UnitAmountMainE=',
    2180 =>'AmountMainE=',
    2181 =>'TopDayRateE=',
    2182 =>'LowDayRateE=',
    2183 =>'DayRateE=',
    2184 =>'RateInforE=',
    2185 =>'RateMainE=',
    2186 =>'CalculateFlagMainE=',
    2187 =>'PremiumMainE=',
    2188 =>'TeamCode=',
    2189 =>'loanCertificateNo=',
    2190 =>'loanBankCode=102',
    2191 =>'loanAmount=',
    2192 =>'repaidType=0',
    2193 =>'loanDate=',
    2194 =>'repaidDate=',
    2195 =>'SubKindCodeMainE=',
    2196 =>'SubKindNameMainE=',
    2197 =>'SubTopRateE=',
    2198 =>'SubLowRateE=',
    2199 =>'MaxDaysE=',
    2200 =>'SubItemCodeMainE=',
    2201 =>'SubItemNameMainE=',
    2202 =>'TopDayAmountE=',
    2203 =>'DayAmountE=',
    2204 =>'SubDaysE=',
    2205 =>'SubUnitAmountMainE=',
    2206 =>'SubAmountMainE=',
    2207 =>'SubTopDayRateE=',
    2208 =>'SubLowDayRateE=',
    2209 =>'SubDayRateE=',
    2210 =>'SubRateInforE=',
    2211 =>'SubRateMainE=',
    2212 =>'SubCalculateFlagMainE=',
    2213 =>'SubPremiumMainE=',
    2214 =>'AgentCodeE=',
    2215 =>'AgentNameE=',
    2216 =>'AgreementNoE=',
    2217 =>'AgreementNameE=',
    2218 =>'AgentCodeEClick=',
    2219 =>'CommissionE=',
    2220 =>'TopCommissionE=',
    2221 =>'OriginCommissionE=',
    2222 =>'RuleEngineCommissionE=',
    2223 =>'InputTimeE=',
    2224 =>'QueryTimeE=',
    2225 =>'CommissionFlagE=',
    2226 =>'CommissionN=',
    2227 =>'TopCommissionN=',
    2228 =>'OriginCommissionN=',
    2229 =>'RuleEngineCommissionN=',
    2230 =>'InputTimeN=',
    2231 =>'QueryTimeN=',
    2232 =>'CommissionFlagN=',
    2233 =>'BusinessNature1N=',
    2234 =>'AgentCodeN=',
    2235 =>'AgentNameN=',
    2236 =>'AgentPermitNoN=',
    2237 =>'AgentCodeNClick=',
    2238 =>'AgreementNoN=',
    2239 =>'AgreementNameN=',
    2240 =>'Handler2CodeN=',
    2241 =>'Handler2NameN=',
    2242 =>'BusinessNature2N=',
    2243 =>'RiskKindN=90',
    2244 =>'RiskKindNameN=一般风险',
    2245 =>'FormulaCodeN=00',
    2246 =>'quantityN=1',
    2247 =>'quantityMaxN=',
    2248 =>'KindCodeMainN=',
    2249 =>'KindNameMainN=',
    2250 =>'ItemCodeMainN=',
    2251 =>'ItemNameMainN=',
    2252 =>'DefaultAmountMainN=',
    2253 =>'AmountMainN=',
    2254 =>'DefaultPremiumMainN=',
    2255 =>'CalculateFlagMainN=',
    2256 =>'PremiumMainN=',
    2257 =>'BusinessNature1H=',
    2258 =>'RiskCodeH=WAE',
    2259 =>'ClassCodeH=',
    2260 =>'CommissionH=',
    2261 =>'TopCommissionH=',
    2262 =>'OriginCommissionH=',
    2263 =>'RuleEngineCommissionH=',
    2264 =>'InputTimeH=',
    2265 =>'QueryTimeH=',
    2266 =>'CommissionFlagH=',
    2267 =>'AgentCodeHClick=',
    2268 =>'AgentCodeH=',
    2269 =>'AgentNameH=',
    2270 =>'AgentPermitNoH=',
    2271 =>'AgentCodeHClick=',
    2272 =>'AgreementNoH=',
    2273 =>'AgreementNameH=',
    2274 =>'Handler2CodeH=',
    2275 =>'Handler2NameH=',
    2276 =>'BusinessNature2H=',
    2277 =>'InsuredNameH=',
    2278 =>'IdentifyTypeH=01',
    2279 =>'IdentifyNumberH='.$auto['IDENTIFY_NO'],
    2280 =>'insuredageH=',
    2281 =>'FormulaCodeH=00',
    2282 =>'quantityH=1',
    2283 =>'maxpiece=',
    2284 =>'Maxage=',
    2285 =>'Minage=',
    2286 =>'KindCodeMainH=',
    2287 =>'KindNameMainH=',
    2288 =>'TopAmountH=',
    2289 =>'LowAmountH=',
    2290 =>'TopRateH=',
    2291 =>'LowRateH=',
    2292 =>'ItemCodeMainH=',
    2293 =>'ItemNameMainH=',
    2294 =>'UnitAmountMainH=',
    2295 =>'AmountMainH=',
    2296 =>'RateInforH=',
    2297 =>'RateMainH=',
    2298 =>'CalculateFlagMainH=',
    2299 =>'UnitPremiumMainH=',
    2300 =>'PremiumMainH=',
    2301 =>'TeamCodeH=',
    2302 =>'BusinessNature1JAB=',
    2303 =>'RiskCodeJAB=JAB',
    2304 =>'ClassCodeJAB=',
    2305 =>'CommissionJAB=',
    2306 =>'TopCommissionJAB=',
    2307 =>'OriginCommissionJAB=',
    2308 =>'RuleEngineCommissionJAB=',
    2309 =>'InputTimeJAB=',
    2310 =>'QueryTimeJAB=',
    2311 =>'CommissionFlagJAB=',
    2312 =>'AgentCodeJABClick=',
    2313 =>'AgentCodeJAB=',
    2314 =>'AgentNameJAB=',
    2315 =>'AgentPermitNoJAB=',
    2316 =>'AgreementNoJAB=',
    2317 =>'AgreementNameJAB=',
    2318 =>'InsuredNameJAB=',
    2319 =>'IdentifyTypeJAB=01',
    2320 =>'IdentifyNumberJAB=',
    2321 =>'AddressNameJAB=',
    2322 =>'Handler2CodeJAB=',
    2323 =>'Handler2NameJAB=',
    2324 =>'BusinessNature2JAB=',
    2325 =>'FormulaCodeJAB=00',
    2326 =>'QuantityJAB=1',
    2327 =>'quantityMaxJAB=',
    2328 =>'KindNameMainJAB=',
    2329 =>'KindCodeMainJAB=',
    2330 =>'ItemNameMainJAB=',
    2331 =>'ItemCodeMainJAB=',
    2332 =>'RateJAB=',
    2333 =>'CalculateFlagMainJAB=',
    2334 =>'UnitAmountMainJAB=',
    2335 =>'AmountMainJAB=',
    2336 =>'KindQuantityJAB=',
    2337 =>'PremiumMainJAB=',
    2338 =>'UnitPremiumMainJAB=',
    2339 =>'SUMAmountJAB=',
    2340 =>'SUMPremiumJAB=',
    2341 =>'comCodeU=32010535',
    2342 =>'BusinessNature1U=',
    2343 =>'AgentCodeU=',
    2344 =>'AgentNameU=',
    2345 =>'AgentPermitNoU=',
    2346 =>'OccupationIDU=',
    2347 =>'CommissionU=',
    2348 =>'TopCommissionU=',
    2349 =>'OriginCommissionU=',
    2350 =>'RuleEngineCommissionU=',
    2351 =>'InputTimeU=',
    2352 =>'QueryTimeU=',
    2353 =>'CommissionFlagU=',
    2354 =>'AgentCodeUClick=0',
    2355 =>'AgreementNoU=',
    2356 =>'AgreementNameU=',
    2357 =>'Handler2CodeU=',
    2358 =>'Handler2NameU=',
    2359 =>'BusinessNature2U=',
    2360 =>'InsuredNameU=',
    2361 =>'IdentifyTypeU=01',
    2362 =>'IdentifyNumberU=',
    2363 =>'totalAmountU=',
    2364 =>'totalPremiumU=',
    2365 =>'FormulaNameU=',
    2366 =>'FormulaNumberU=',
    2367 =>'engageCode=',
    2368 =>'engageCname=',
    2369 =>'engageType=',
    2370 =>'engageDesc=',
    2371 =>'FormulaCodeU=',
    2372 =>'isFixedFlag=',
    2373 =>'insureDaysFlag=',
    2374 =>'insuredaysType=',
    2375 =>'insuredays=',
    2376 =>'backDay=',
    2377 =>'maxCount=',
    2378 =>'carUseNature=',
    2379 =>'ageGroup=',
    2380 =>'planCode=',
    2381 =>'MergerPrintUHidden=0',
    2382 =>'QuantityU=1',
    2383 =>'BusinessNature1U=',
    2384 =>'AgentCodeU=',
    2385 =>'AgentNameU=',
    2386 =>'AgentPermitNoU=',
    2387 =>'OccupationIDU=',
    2388 =>'CommissionU=',
    2389 =>'TopCommissionU=',
    2390 =>'OriginCommissionU=',
    2391 =>'RuleEngineCommissionU=',
    2392 =>'InputTimeU=',
    2393 =>'QueryTimeU=',
    2394 =>'CommissionFlagU=',
    2395 =>'AgentCodeUClick=0',
    2396 =>'AgreementNoU=',
    2397 =>'AgreementNameU=',
    2398 =>'Handler2CodeU=',
    2399 =>'Handler2NameU=',
    2400 =>'BusinessNature2U=',
    2401 =>'InsuredNameU=',
    2402 =>'IdentifyTypeU=01',
    2403 =>'IdentifyNumberU=',
    2404 =>'totalAmountU=',
    2405 =>'totalPremiumU=',
    2406 =>'FormulaNumberU=',
    2407 =>'engageCode=',
    2408 =>'engageCname=',
    2409 =>'engageType=',
    2410 =>'engageDesc=',
    2411 =>'FormulaCodeU=',
    2412 =>'FormulaNameU=',
    2413 =>'isFixedFlag=',
    2414 =>'insureDaysFlag=',
    2415 =>'insuredaysType=',
    2416 =>'insuredays=',
    2417 =>'backDay=',
    2418 =>'maxCount=',
    2419 =>'carUseNature=',
    2420 =>'ageGroup=',
    2421 =>'planCode=',
    2422 =>'MergerPrintUHidden=0',
    2423 =>'QuantityU=1',
    2424 =>'isChannelSpecialist=false',
    2425 =>'identityVeri=0',
    2426 =>'NoApproveFlag=0',
    2427 =>'PlatFormFlag=1'

);

	


        if(isset($business['BUSINESS_NUMBER_INSURED']) && $business['BUSINESS_NUMBER_INSURED']!="" && isset($mvtalci['MVTALCI_NUMBER_INSURED']) && $mvtalci['MVTALCI_NUMBER_INSURED']!="")
        {
            $arr['4']="EDITTYPE=UPDATE";//如果流程存在投保单，那么就更新
            $arr['48']="ProposalNo=".$business['BUSINESS_NUMBER_INSURED']; 
        }
        else
        {
            $arr['4']="EDITTYPE=NEW";//如果流程存在投保单，那么就更新
            $arr['48']="ProposalNo="; //不传递投保单号，代表新增
        }  

		$AmountCount="";

			if(isset($mvtalci['MVTALCI_START_TIME']) && $mvtalci['MVTALCI_START_TIME']!="")
			{
				$arr['538']='KindTypeFlag=1';
				$AmountCount += "122000";

			}

			if(isset($business['POLICY']['BUSINESS_ITEMS']) && count($business['POLICY']['BUSINESS_ITEMS'])>0)
		    {
		    	
		        foreach($business['POLICY']['BUSINESS_ITEMS'] as $u=>$value)
		        {

		        	
		        if(isset($auto['switch']) && $auto['switch']=="1")//判断是否是提交核保
		        {
		        	$s=$u;
		        }
		        else
		        {
		        	$s=$value;
		        }

		        if(strpos($s, "NDSI"))
		        {
		        	$arr['990']='KindTypeFlag=1';
		        }	
		        $_SESSION['KindTypeFlag']['AmountCount']="";
		        	switch ($s) {
		        		case 'TVDI':
		        			$arr['568']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
		        			break;
		        		case 'TTBLI':
		        			$arr['681']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['TTBLI_INSURANCE_AMOUNT'];
		        			break;
		        		case 'TWCDMVI':
		        			$arr['765']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'];
		        			break;
		        		case 'TCPLI_DRIVER':
		        			$arr['793']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'];
		        			break;
		        		case 'TCPLI_PASSENGER':
		        			$arr['821']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['TCPLI_PASSENGER_COUNT']*$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'];
		        			break;
		        		case 'BSDI':
		        			$arr['1046']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['BSDI_INSURANCE_AMOUNT'];
		        			break;
		        		case 'SLOI':
		        			$arr['1327']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['SLOI_INSURANCE_AMOUNT'];
		        			break;
		        		case 'BGAI':
		        			$arr['962']='KindTypeFlag=1';
		        			break;
		        		case 'NIELI':
		        			$arr['1299']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['NIELI_INSURANCE_AMOUNT'];
		        			break;
		        		case 'VWTLI':
		        			$arr['1439']='KindTypeFlag=1';
		        			break;
		        		case 'STSFS':
		        			$arr['1552']='KindTypeFlag=1';
		        			break;	
		        		case 'RDCCI':
		        			$arr['1018']='KindTypeFlag=1';
		        			$AmountCount += $business['POLICY']['RDCCI_INSURANCE_QUANTITY']*$business['POLICY']['RDCCI_INSURANCE_UNIT'];
		        			break;	
		        		case 'MVLINFTPSI':
		        			$arr['1636']='KindTypeFlag=1';
		        			break;	
		        		
		        		case 'TVDI_NDSI':
		        			$arr['572']='ItemKindFlag5Flag=1';
		        			break;
		        		case 'TTBLI_NDSI':
		        			$arr['685']='ItemKindFlag5Flag=1';
		        			break;
		        		case 'TWCDMVI_NDSI':
		        			$arr['769']='ItemKindFlag5Flag=1';
		        			break;
		        		case 'TCPLI_DRIVER_NDSI':
		        			$arr['797']='ItemKindFlag5Flag=1';
		        			break;
		        		case 'TCPLI_PASSENGER_NDSI':
		        			$arr['825']='ItemKindFlag5Flag=1';
		        			break;
		        		case 'BSDI_NDSI':
		        			$arr['1050']='ItemKindFlag5Flag=1';
		        			break;
		        		case 'SLOI_NDSI':
		        			$arr['1331']='ItemKindFlag5Flag=1';
		        			break;
		        		case 'NIELI_NDSI':
		        			$arr['1303']='ItemKindFlag5Flag=1';
		        			break;
		        		case 'VWTLI_NDSI':
		        			$arr['1443']='ItemKindFlag5Flag=1';
		        			break;
		        				
		        	}

		        }


		    }
            $_SESSION['KindTypeFlag']['AmountCount']= $AmountCount;//总保额

		    if($business['POLICY']['GLASS_ORIGIN']=="DOMESTIC")
		    {
		    	  $arr['953'] ='Model=1';
		    }
		    else
		    {
		    	  $arr['953'] ='Model=2';
		    }	

		    
			$str="";
			foreach($arr as $k=>$v)
			{
				$str.=iconv("UTF-8", "GBK", $v)."&";
			}

			return $str;

     }

}




?>