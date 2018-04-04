<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         BSDI_INS.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 机动车车身划痕险保费计算.
 * 
 **/ 
 
class BSDI_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'CSHHX-2008(A)-HB-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = 'TVDI';
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
					array(0,24,0,30,2000,400),
					array(0,24,0,30,5000,570),
					array(0,24,0,30,10000,760),
					array(0,24,0,30,20000,1140),	

					array(0,24,30,50,2000,585),
					array(0,24,30,50,5000,900),
					array(0,24,30,50,10000,1170),
					array(0,24,30,50,20000,1780),

					array(0,24,50,0xffff,2000,850),
					array(0,24,50,0xffff,5000,1100),
					array(0,24,50,0xffff,10000,1500),
					array(0,24,50,0xffff,20000,2250),	

					array(24,0xffff,0,30,2000,610),
					array(24,0xffff,0,30,5000,850),
					array(24,0xffff,0,30,10000,1300),
					array(24,0xffff,0,30,20000,1900),	
                     
					array(24,0xffff,30,50,2000,900),
					array(24,0xffff,30,50,5000,1350),
					array(24,0xffff,30,50,10000,1800),
					array(24,0xffff,30,50,20000,2600),
                         
					array(24,0xffff,50,0xffff,2000,1100),
					array(24,0xffff,50,0xffff,5000,1500),
					array(24,0xffff,50,0xffff,10000,2000),
					array(24,0xffff,50,0xffff,20000,3000),						
				  );	
	
	/**
	  *	短期费率
	  **/	  
	private       $ShortRate = array(
				  	1  => 0.1 ,
				  	2  => 0.2 ,
				  	3  => 0.3 ,
				  	4  => 0.4 ,
				  	5  => 0.5 ,
				  	6  => 0.6 ,
				  	7  => 0.7 ,
				  	8  => 0.8 ,
				  	9  => 0.85,
				  	10 => 0.9 ,
				  	11 => 0.95,
				  	12 => 1   ,
				  );
	/**
	  *	手续费费率
	  **/			  
	private       $FeeRate = 0.03;
	
	/**
	 * 保费计算.
	 * @params  保险变量.
	 *          键值是变量名,变量名如下:
	 * 			MONTHS              保险期间(月份数,1-12).
	 *          COTY                车龄.	 
	 *          BSDI_INSURANCE_AMOUNT    保额.
	 *          PURCHASE_PRICE      新车购置价(元).   
	 **/ 
	public function buy( Array $params = array() )
	{

		if(!array_key_exists('MONTHS',$params) || 
			$params['MONTHS'] < 1 || 
			$params['MONTHS'] > 12 )
		{
			return false;
		}
		$months = $params['MONTHS'];
		
		if(!array_key_exists('BSDI_INSURANCE_AMOUNT',$params))
		{
			return false;
		}
		$insAmount = $params['BSDI_INSURANCE_AMOUNT'];
		
		if(!array_key_exists('PURCHASE_PRICE',$params))
		{
			return false;
		}
		$purchasePrice = $params['PURCHASE_PRICE']/10000;

		if(!array_key_exists('COTY',$params))
		{
			return false;
		}
		$coty = $params['COTY'];		
		
		$cost = 0;
		
		foreach($this->Rate as $row )
		{
			if($row[0] <= $coty && $coty < $row[1] && 
			   $row[2] <= $purchasePrice && $purchasePrice < $row[3] && 
			   $row[4] == $insAmount)
			{
				$cost = $row[5];
				break;
			}
		}
		
		return round($cost * $this->ShortRate[$months],2) ;		
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