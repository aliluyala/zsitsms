<?php
/**
 * 项目：          保险数据抓取功能包
 * 文件名:         InsDataExtractionYNPicc.class.php
 * 版权所有：      2015 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *
 * 数据抓取picc查询代理主类
 **/
error_reporting(0);
$finfo = pathinfo(__FILE__);
//定义目录常量
ini_set( 'display_errors', 'Off' );
require($finfo['dirname'].'/YNPICC_IDE_A.class.php');
class InsDataExtractionYNPicc
{
	private $cookie,$config;
	private $lib;
	/**
	 * 构造函数
	 * 参数:
	 * @configFile      必需。配置文件
	 * @cachePath       必需。缓存目录
	 **/
	 
	function __construct($config,$cachePath)
	{
		$this->config = $config;
		if(!is_dir($cachePath)) die('You specify the cache path does not exist!<br/>');
		$cookiePath = $cachePath.'/InsDataExtraction/cookie/';
		if(!is_dir($cookiePath)) mkdir($cookiePath,true);
		$this->cookie = $cookiePath;
		$this->lib = new YNPICC_IDE_A($this->config,$this->cookie);

	}

	/**
	 * 查询保单
	 * 参数:
	 * @info            必需。车辆信息,数组类型
	 *			license_no          *车牌号码
	 *          license_type         号牌分类代码   
	 *          license_color        号牌底色
	 *          owner               *所有人
	 *          identify_type        证件类型
	 *          identify_number     *证件号码
	 *          contact              联系人
	 *          telphone            *固话号码
	 *          mobile              *手机号码
	 *          address             *地址
	 *          buying_price         新车购置价
	 *          vehicle_type         车辆种类
	 *          use_character        使用性质
	 *          model               *品牌型号
	 *          vin_no              *车架号
	 *          engine_no           *发动机号
	 *          register_date       *注册时间
	 *          register_address     注册地
	 *          seats                核定载人数
	 *          kerb_mass            整备质量(KG)
	 *          total_mass           总质量(KG)
	 *          load                 核定载重量(KG) 
	 *          tow_mass             准牵总质量(KG)  
	 *          engine              *发动机排气量(ML)
	 *          power               *发动机功率(KW) 
	 *          body_size            车身尺寸   
	 *          body_color           车身颜色       
	 *          origin               产地
	 *                               
	 * 返回值:失败返回false,成功返回数组:
     *
	 **/ 
	 
	public function queryPolicyList($info = array())
	{		
		$ide = $this->lib;
		$policyList_o = array();
		$policyList =  $ide->queryPolicyList($info);
		if(!is_array($policyList) &&  $policyList !== false ) return $policyList;
		$stD = time();
		$succ = false;
		if($policyList)
		{			
			foreach($policyList as $po)
			{
				$policyList_o[] = $po;
				
				if($po['end_date_timestamp'] > $stD)
				{
					$succ = true;
				}					
			}
		}
		if($succ) return $policyList_o;
		
		$succ = false;
		$modelCa = false;
		$mqcount = 0;
		$vinfo = $info;
		while($mqcount<2)
		{
			$vechicles = $ide->queryVehicle($vinfo);
			if($vechicles === false && array_key_exists('vin_no',$vinfo))
			{			
				$vechicles = $ide->queryVehicleByVIN($vinfo['vin_no']);
			}
			if(is_array($vechicles))
			{				
				foreach($vechicles as $vec)
				{					
					$info['model'] = $vec['vehicleName'];				
					$info['model_code'] = $vec['vehicleId'];
					$policyList =  $ide->queryOtherPolicyList($info);
					if(is_string($policyList))
					{
						$msg = $policyList;
						if(strstr($msg,'您录入的车型与平台返回的车型不一致') && !$modelCa)
						{
							$newModel = '';
							if(preg_match('/[A-Z]{2,3}\d{4}[A-Z0-9]*/',$msg,$out))
							{								
								$newModel = $out[0];
							}
							else
							{
								$pos = strripos($msg,'：');
								$newModel = substr($msg,$pos+strlen('：'));
								$modelarr = explode(' ',$newModel);
								$wordCount = count($modelarr);								
								$endWord = $modelarr[$wordCount-1];
								if(strstr($endWord,'版') || strstr($endWord,'型') || strstr($endWord,'款') )
								{
									array_pop($modelarr);
								}
								$newModel = '';
								$words = 3;
								foreach($modelarr as $word)
								{
									if(empty($newModel)) $newModel = $word;
									else $newModel .= ' '.$word;
									$words--;
									if($words<1) break;
								}								
							}							
							
							$modelCa = true;
							$vinfo['model'] = $newModel;
							break;
							
						}
						else
						{
							$policyList =  false;
						}						
					}						
					
					if(is_array($policyList) )
					{
						$succ = true;	
						break;	
					}								
				}
			}
			else
			{
				$info['model'] = '大众汽车牌SVW71611DM';				
			}
			
			if($succ) break;
			$mqcount++;
		}	
		
		if(!is_array($policyList) &&  $policyList !== false ) return $policyList;
		if($policyList)
		{	

			$cpo = current($policyList);			
			if(is_array($policyList) && count($policyList)>0 && 
			   !empty($cpo['insurance_company']) && 
			   strtolower($cpo['insurance_company']) == 'picc')
			{
				$info['engine_no'] = $cpo['engine_no'];
				$info['vin_no'] = $cpo['vin_no'];
				$info['license_no'] = '';
				$policyList =  $ide->queryPolicyList($info);
				if($policyList === -1) return -1;
			}	
			
			foreach($policyList as $po)
			{
				$policyList_o[] = $po;				
			}			
		}	
		return $policyList_o;	
	}
	/**
	 * 返回保单信息
	 * 参数:
	 * @policyNo                 必需。保单号
	 * @$otherInfo               可选。其它信息
	 * 返回值:失败 false,成功返回数组
	 **/	
	 
	public function getPolicyInfo($policyNo,$otherInfo = array())
	{
		return $this->lib->getPolicyInfo($policyNo,$otherInfo);
	}	

	
	/**
	 * 查询车辆信息
	 * 参数:
	 * @policyNo                必需。保单号	
	 * @$otherInfo              可选。其它信息
	 * 返回值:失败 false,成功返回数组	 
	 **/
	 
	public function getAutoInfo($policyNo,$otherInfo = array())
	{
		return $this->lib->getAutoInfo($policyNo,$otherInfo);		
	}	

	/**
	 * 查询关系人
	 * 参数:
	 * @policyNo                必需。保单号	
	 * @$otherInfo              可选。其它信息
	 * 返回值:失败 false,成功返回数组	 
	 **/
	 
	public function getCinsuredInfo($policyNo,$otherInfo = array())
	{
		return $this->lib->getCinsuredInfo($policyNo,$otherInfo);		
	}		
	
	/**
	 * 查询理赔信息
	 * 参数:
	 * @policyNo                必需。保单号	
	 * @$otherInfo              可选。其它信息
	 * 返回值:失败 false,成功返回数组	 
	 **/
	 
	public function getClaimsInfo($policyNo,$otherInfo = array())
	{
		return $this->lib->getClaimsInfo($policyNo,$otherInfo);		
	}		
	
	/**
	 * 查询上年商业险保单信息
	 * 参数:
	 * @licenseNo                 必需。车牌号
	 * 返回值:失败 false,成功返回true
	 **/			
	public function queryLastYearBI($licenseNo)
	{
		return $this->lib->queryLastYearBI($licenseNo);
	}	
	
};
 


?>