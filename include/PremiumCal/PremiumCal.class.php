<?php
/**
 * 项目:           车险保费在线计算接口
 * 文件名:         PremiumCal.class.php
 * 版权所有：      成都启点科技有限公司.
 * 作者：          Tang DaYong
 * 版本：          1.0.0
 *
 * 主入口类。
 *
 **/

class PremiumCal
{
	private $config,$cachePath,$libsPath,$currentApi,$api;

	/**
	 * 构造函数
	 * 参数:
	 * @config          必需。配置文件
	 * @cachePath       必需。用于保存cookie等临时文件的目录
	 **/
	function __construct($config,$cachePath,$currentApi=null)
	{
		if(empty($config) || !array_key_exists('available_api',$config) || !is_array($config['available_api']) ||
		   !array_key_exists('default_api',$config) || !is_string($config['default_api'])	||
		   !array_key_exists('api_configs',$config) || !is_array($config['api_configs']) )
		{
			trigger_error('PremiumCal config invalid!',E_USER_ERROR);
		}
		if(empty($cachePath))
		{
			trigger_error('No cache directory specified!',E_USER_ERROR);
		}

		if(!is_dir($cachePath))
		{
			trigger_error('Invalid cache directory!',E_USER_ERROR);
		}

		$this->config = $config;
		$this->cachePath = $cachePath;
		$this->libsPath = dirname(__FILE__).'/libs/';
		$this->currentApi = '';
		if(!empty($currentApi) || in_array($currentApi,$config['available_api']))
		{
			$this->currentApi = $currentApi;
		}
		elseif(in_array($config['default_api'],$config['available_api']))
		{
			$this->currentApi = $config['default_api'];
		}
		$classname = $this->currentApi.'_PC';
		$classfile = $this->libsPath.$classname.'.class.php';
		if(!is_file($classfile))
		{
			trigger_error("API '{$this->currentApi}' not found!",E_USER_ERROR);
		}
		require_once($classfile);

		if(!class_exists($classname))
		{
			trigger_error("API '{$this->currentApi}' not found!",E_USER_ERROR);
		}

		$apiconf = array();
		if(array_key_exists($this->currentApi,$config['api_configs']))
		{
			$apiconf = $config['api_configs'][$this->currentApi];
		}

		$this->api = new $classname($apiconf,$this->cachePath);

	}


	/*****************************************************************************
	 * 保费计算
	 * 参数:
	 * @auto            必需,数组。车辆信息
	 *    数组结构如下:
	 *    array(
     *      'LICENSE_NO'   =>'' , //车牌号码
     *      'LICENSE_TYPE' =>'' , //号牌类别
     *      'OWNER'        =>'' , //拥有人
     *      'VIN_NO'       =>'' , //车辆识别码
     *      'ENGINE_NO'    =>'' , //发动机号码
     *      'MODEL'        =>'' , //品牌型号
     *      'ENGINE'       =>'' , //排量
     *      'SEATS'        =>'' , //核定载客
     *      'KERB_MASS'    =>'' , //整备质量
     *      'MODEL_CODE'   =>'' , //型号代码
     *      'BUYING_PRICE' =>'' , //新车购置价
     *      'ENROLL_DATE'  =>'' , //注册日期
     *      'VEHICLE_TYPE' =>'' , //车辆类别
     *      'USE_CHARACTER'=>'' , //使用性质
     *      'ORIGIN'       =>'' , //产地
	 *     )
	 *----------------------------------------------------------------------------
     * @business        必需,数组。商业险变量
	 *     数组结构如下:
	 *     array(
	 *       'DESIGNATED_DRIVER'  => array(), //指定驾驶人,二维数组,结构见下
     *		 'DISCOUNT_VARS'      => array(), //折扣系数,结构见下
     *       'POLICY'             => array(), //投保参数,结构见下
     *       'BUSINESS_START_TIME'=> '',      //保险生效时间
	 *	     'BUSINESS_END_TIME'  => '',	  //保险结束时间
	 *      )
	 *
	 *     指定驾驶人数组结构,二维数组,一个元素一个驾驶人
	 *     array(
	 *	     array(
	 *	   	    'DRIVER_NAME'=>'',         //驾驶人姓名
	 *	   	    'DRIVING_LICENCE_NO'=>'',  //驾驶证号
	 *	   	    'DRIVER_ALLOW_DRIVE'=>'',  //准驾代码
	 *	   	    'DRIVER_SEX'=>'',          //性别
	 *	   	    'DRIVER_AGE'=>'',          //年龄
	 *	   	    'DRIVING_YEARS'=>'',       //驾龄
	 *	        ),
	 *       .......
	 *     )
	 *
	 *     折扣系数
	 *     array(
	 *       'YEARS_OF_INSURANCE'=>'',     //投保年度
     *       'CLAIM_RECORDS'=>'',          //赔款记录
     *       'DRIVING_AREA'=>'',           //约定行驶区域
     *       'AVERAGE_ANNUAL_MILEAGE'=>'', //平均行驶里程
     *       'MULTIPLE_INSURANCE'=>'',     //多险种优惠
	 *     )
	 *
	 *     投保参数
	 *     array(
	 *       'BUSINESS_ITEMS'=>array('TVDI','TTBLI',...),  //投保险种代码列表
     *       'TVDI_INSURANCE_AMOUNT'=>'',                  //车损险保额
     *       'DOC_AMOUNT'=>'',                             //车损险可选免赔额
     *       'TTBLI_INSURANCE_AMOUNT'=>'',                 //三者险保额
     *       'TWCDMVI_INSURANCE_AMOUNT'=>'',               //盗抢险保额
     *       'TCPLI_INSURANCE_DRIVER_AMOUNT'=>'',          //座位险(司机)保额
     *       'TCPLI_PASSENGER_COUNT'=>'',                  //座位险(乘客)数量
     *       'TCPLI_INSURANCE_PASSENGER_AMOUNT'=>'',       //座位险(乘客)单位保额
     *       'BSDI_INSURANCE_AMOUNT'=>'',                  //划痕险保额
     *       'SLOI_INSURANCE_AMOUNT'=>'',                  //自燃险保额
     *       'GLASS_ORIGIN'=>'',                           //玻璃险玻璃产地
     *       'NIELI_INSURANCE_AMOUNT'=>'',                 //新增设备损失险保额
     *       'STSFS_RATE'=>'',                             //指定专修厂上浮比例
     *       'WADING_INSURANCE_AMOUNT'=>'', 			   //涉水险保额
	 *     )
	 *
	 *----------------------------------------------------------------------------
	 *
     * @mvtalci         必需,数组。交强险变量
	 *     数组结构如下:
	 *    array(
	 *      'FLOATING_RATE'=>'',                           //交强险浮动标准
	 *      'MVTALCI_INSURANCE_AMOUNT'
     *      'MVTALCI_START_TIME'=>'',                      //交强险生效时间
     *      'MVTALCI_END_TIME'=>'',                        //交强险结束时间
     *    )
	 *
	 * 返回值
     * 	 计算失败返回false,成功返回数组
	 *   array(
	 *        'MVTALCI'	=> array(
	                       'TRAVEL_TAX_PREMIUM'=>0,        //车船税
						   'MVTALCI_PREMIUM'=>0,           //交强险保费
						   'MVTALCI_DISCOUNT'=>0,          //交强险折扣
						   'MVTALCI_START_TIME'=>'',       //交强险生效时间
						   'MVTALCI_END_TIME'=>'',         //交强险结束时间
						   ),
              'BUSINESS' => array(
						   'BUSINESS_DISCOUNT_PREMIUM'=>'', //商业险扣后保费合计
			               'BUSINESS_DISCOUNT'=>'',         //商业险折扣
			               'BUSINESS_PREMIUM'=>'',          //商业险标准保费合计
			               'BUSINESS_START_TIME'=>'',       //商业险生效时间
			               'BUSINESS_END_TIME'=>'',         //商业险结束时间
			               'BUSINESS_ITEMS' => array(       //投保项目保费二维数组,
						            'TVDI' => array('PREMIUM'=>0,'DISCOUNT_PREMIUM'=>0),
						        ),

			               )
	 *   )
	 **/
	public  function  premium($auto=array(),$business=array(),$mvtalci=array())
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'premium')) return false;
		return $this->api->premium($auto,$business,$mvtalci);
	}

	/*
	 * 车辆折旧价计算
	 * @info              参数信息
	 * 返回值 失败 false ,成功折旧价
	 */
	public function depreciation($info=array())
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'depreciation')) return false;
		return $this->api->depreciation($info);
	}

	/*
	 * 设备折旧价计算
	 * @info              参数信息
	 * 返回值 失败 false ,成功折旧价
	 */
	public function deviceDepreciation($info=array())
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'deviceDepreciation')) return false;
		return $this->api->deviceDepreciation($info);
	}

	/*
	 * 新车购置价查询
	 * @info              信息
	 * 返回值 失败 false ,成功返回数组,查询列表
	 */
	public function queryBuyingPrice($info=array())
	{

		if($this->api == null) return false;
		$retdata = array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());
		if(method_exists($this->api,'queryBuyingPrice'))
		{

			$retdata = $this->api->queryBuyingPrice($info);
		}
		else
		{
			$model = NULL;
			if(!empty($info['model'])) $model = $info['model'];
			$page = 1;
			if(!empty($info['page'])) $page = $info['page'];
			require_once(dirname(__FILE__).'/VehicleInfo.class.php');
			$vei = new VehicleInfo();
			$model = $vei->resolveModel($model);
			$post_data = array(
				'vehicleName'       => "{$model}",
				'_search'           => false,
				'nd'                => '1423048648369',
				'rows'              => 10,
				'page'              => $page,
				'sidx'              => 'vehiclePrice',//按新车购置价排序,默认为空
				'sord'              => 'asc',
				'searchCode'        => '',
				'vehiclePriceBegin' => '',
				'vehiclePriceEnd'   => '',
				'vehicleBrand'      => '',
				'vehicleId'         => '',
				'vinCode'           => '',
				'vehicleSeries'     => '',
				'vehicleMaker'      => '',
			);
			$array = $vei->go($post_data);

			$retdata = array('total'=>$array['totalPage'],'page'=>$array['page'],'records'=>$array['total'],'rows'=>array());
			foreach($array['vhlList'] as $row)
			{

				$line = array();
				$line['vehicleId']             = $row['vehicleId']             ;
				$line['vehicleName']           = $row['vehicleName']           ;
				$line['vehicleMaker']          = $row['vehicleMaker']          ;
				$line['vehicleDisplacement']   = $row['vehicleDisplacement']   ;
				$line['vehiclePrice']          = $row['vehiclePrice']          ;
				$line['szxhTaxedPrice']        = $row['szxhTaxedPrice']        ;
				$line['xhKindPrice']           = $row['xhKindPrice']           ;
				$line['nXhKindpriceWithouttax']= $row['nXhKindpriceWithouttax'];
				$line['vehicleSeat']           = $row['vehicleSeat']           ;
				$line['vehicleYear']           = $row['vehicleYear']           ;
				$retdata['rows'][] = $line;
			}

		}
		return $retdata;
	}

	/**
	 * 获取设置项目
	 **/
	public function getSetItems()
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'getSetItems')) return false;
		return $this->api->getSetItems();
	}

	/**
	 * 获取表单模板文件名
	 **/
	public function getFormFile()
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'getFormFile')) return false;
		return $this->api->getFormFile();
	}

	/**
	 *获取最后一次错误信息
	 */
	public function getLastError()
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'getLastError')) return false;
		return $this->api->getLastError();
	}

	/**
	 *返回暂存单号
	 */
	public function documentary_cost($auto=array(),$business=array(),$mvtalci=array())
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'documentary_cost')) 
		{
			$error['errorMsg']="此费率表没有提交核保功能模块,请切换费率表";
			$error['state']="2";
			return json_encode($error);
		}
		
		return $this->api->documentary_cost($auto,$business,$mvtalci);
	}
	
	/**
	*返回交管车辆验证码
	*/
	public function checkcode($info=array())
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'checkcode')) return false;
		return $this->api->checkcode($info);	
		
	}
	
    /**
	*返回交管车辆信息
	*/
	public function check_data($info=array())
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'check_data')) return false;
		return $this->api->check_data($info);	
		
	}
	/**
	 * [check_login 登录验证]
	 * @param  [type] $info [description]
	 * @return [type]       [description]
	 */
	public function check_login($info=array())
	{
		if($this->api == null) return false;
		if(!method_exists($this->api,'check_login')) return false;
		return $this->api->check_login($info);
	}

    /**
     * 是否需要输入登录验证码
     */
    public function needVerification()
    {
        return isset($this->api->verification) && $this->api->verification;
    }

    /**
     * 获取验证码
     */
    public function getVerifyCode()
    {
        if($this->api == null) return false;
        if(!method_exists($this->api,'getVerifyCode')) return false;
        return $this->api->getVerifyCode();
    }
};






?>