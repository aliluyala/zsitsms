<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         SLOI_INS.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 机动车自燃损失险保费计算.
 * 
 **/ 
 
class SLOI_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'ZRSSX-2008(A)-HB-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = 'TVDI';
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,12,0.0015),
						array(12,24,0.0018),
						array(24,36,0.002),
						array(36,48,0.002),
						array(48,72,0.002),
						array(72,0xffff,0.0023),
						
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,12,0),
						array(12,24,0),
						array(24,36,0),
						array(36,48,0),
						array(48,72,0),
						array(72,0xffff,0),		
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,12,0),
						array(12,24,0),
						array(24,36,0),
						array(36,48,0),
						array(48,72,0),
						array(72,0xffff,0),				
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,12,0.003),
						array(12,24,0.003),
						array(24,36,0.003),
						array(36,48,0.003),
						array(48,72,0.003),
						array(72,0xffff,0.003),			
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(0,12,0.003),
						array(12,24,0.003),
						array(24,36,0.003),
						array(36,48,0.003),
						array(48,72,0.003),
						array(72,0xffff,0.003),			
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(0,12,0.003),
						array(12,24,0.003),
						array(24,36,0.003),
						array(36,48,0.003),
						array(48,72,0.003),
						array(72,0xffff,0.003),			
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,12,0),
						array(12,24,0),
						array(24,36,0),
						array(36,48,0),
						array(48,72,0),
						array(72,0xffff,0),		
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,12,0.003),
						array(12,24,0.003),
						array(24,36,0.003),
						array(36,48,0.003),
						array(48,72,0.003),
						array(72,0xffff,0.003),			
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,12,0),
						array(12,24,0),
						array(24,36,0),
						array(36,48,0),
						array(48,72,0),
						array(72,0xffff,0),		
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,12,0),
						array(12,24,0),
						array(24,36,0),
						array(36,48,0),
						array(48,72,0),
						array(72,0xffff,0),		
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(0,12,0),
						array(12,24,0),
						array(24,36,0),
						array(36,48,0),
						array(48,72,0),
						array(72,0xffff,0),		
				  	),									
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
	  *	手续费率
	  **/			  
	private       $FeeRate = 0.03;
	
	/**
	 * 保费计算.
	 * @params  保险变量.
	 *          键值是变量名,变量名如下:
	 * 			TYPE_AUTO           车辆类型.
	 * 			MONTHS              保险期间(月份数,1-12).
	 *          COTY                车龄(月).
	 *          DEPRECIATION_PRICE  新车折旧价(元).   
	 **/ 
	public function buy( Array $params = array() )
	{
		if(!array_key_exists('TYPE_AUTO',$params) || 
		   !array_key_exists($params['TYPE_AUTO'],$this->Rate))
		{	
			return false;
		}	
		$type = $params['TYPE_AUTO'];

		if(!array_key_exists('MONTHS',$params) || 
			$params['MONTHS'] < 1 || 
			$params['MONTHS'] > 12 )
		{
			return false;
		}
		$months = $params['MONTHS'];
		
		if(!array_key_exists('DEPRECIATION_PRICE',$params))
		{
			return false;
		}
		$insAmount = $params['DEPRECIATION_PRICE'];
		
		$coty = 0;
		if(array_key_exists('COTY',$params))
		{
			$coty = $params['COTY'];
		}
		
		$cost = 0;
		foreach($this->Rate[$type] as $row )
		{
			if( $row[0] <= $coty && $coty < $row[1])
			{
				$cost =  $insAmount*$row[2] ;
				break;
			}
		}
		
		return round($cost *  $this->ShortRate[$months],2) ;		
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