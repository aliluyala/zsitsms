<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         NIELI_INS.class.php
 * 版权所有：      2015 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 机动车新增设备损失险保费计算.
 * 
 **/ 
 
class NIELI_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'XZSBSSX-2009(c)-HB-CPIC';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = 'TVDI';
	
	/**
	 * 保险费率	
	 **/
	private	      $Rate = 0;	
	

	/**
	  *	手续费率
	  **/			  
	private       $FeeRate = 0.03;
	
	/**
	 * 保费计算.
	 * @params  保险变量.
	 *          键值是变量名,变量名如下:
	 *          MAIN_INSURANCE_CAST 车损险保费.
	 *          PURCHASE_PRICE      新车购置价(车损险保额).
	 *          NIELI_INSURANCE_AMOUNT 新增设备损失险保额.   
	 **/ 
	public function buy( Array $params = array() )
	{
		if(!array_key_exists('MAIN_INSURANCE_CAST',$params) || 
		   !array_key_exists('PURCHASE_PRICE',$params) || 
		   !array_key_exists('NIELI_INSURANCE_AMOUNT',$params))
		{
			return false;
		}

		return round(($params['MAIN_INSURANCE_CAST']/$params['PURCHASE_PRICE']) * $params['NIELI_INSURANCE_AMOUNT'],2);		
	}
	
	/**
	 * 退还保险费计算.
	 * @params   保险变量
	 *           键值是变量名,变量名如下:
	 *           START_TIME         保险生效时间
	 *           END_TIME           保险结束时间
	 *           PREMIUM            签单保费
	 *           PAID_PREMIUM       实收保费	 
     *           STOP_TIME	        终止保险时间
	 *           
	 **/ 	
	public function refund( Array $params = array() )
	{
		if(!array_key_exists('PREMIUM',$param) &&
		   !array_key_exists('PAID_PREMIUM',$param))
		{
			return 0;
		}	
		$premium = $params['PREMIUM'];
		$paidPremium = $params['PAID_PREMIUM'];
		
		if(array_key_exists('IS_FULL',$param) && 
		   $params['IS_FULL'] == 'YES' )
		{
			return $paidPremium - $premium * $this->FeeRate;
		}
		
		if(!array_key_exists('START_TIME',$params) ||
		   !array_key_exists('END_TIME',$params) ||
		   !array_key_exists('STOP_TIME',$params))
		{
			return 0;
		}
		$stime = strtotime($params['START_TIME']);
		$etime = strtotime($params['END_TIME']);
		$otime = strtotime($params['STOP_TIME']);
		if(!$stime || !$etime || !otime) return 0;
		$totalDays = ceil(abs($etime-$stime)/86400);
		$pastDays  = ceil(abs($otime-$stime)/86400);
		return $paidPremium - $premium * ($pastDays/$totalDays); 
	}
	
}
 


?>