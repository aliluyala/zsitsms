<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         STSFS_INS.class.php
 * 版权所有：      2015 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 指定专修厂特约条款保费计算.
 * 
 **/ 
 
class STSFS_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'ZDZXCTYTK-2008(A)-HB-PICCP&C';	
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
	 *          STSFS_RATE          上浮比例.
	 *          MAIN_INSURANCE_CAST 主险保费.
	 **/ 
	public function buy( Array $params = array() )
	{
		if(!array_key_exists('MAIN_INSURANCE_CAST',$params))
		{
			return false;
		}
		$mainCast = $params['MAIN_INSURANCE_CAST'];
		
		if(!array_key_exists('STSFS_RATE',$params))
		{
			return false;
		}
		$stsfs = $params['STSFS_RATE'];		

		return round($mainCast * $stsfs,2) ;		
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