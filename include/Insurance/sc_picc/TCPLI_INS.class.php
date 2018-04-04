<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         TCPLI_INS.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 机动车车上人员责任险保费计算.
 * 
 **/ 
 
class TCPLI_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'CSRYZRX-2009(A)-SC-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,0.0041,0.0026),
				  		array(6,9,0.0039,0.0025),
						array(10,0xffff,0.0039,0.0025),
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,5,0.0041,0.0025),
				  		array(6,9,0.0039,0.0023),
				  		array(10,19,0.0039,0.0023),
						array(20,0xffff,0.0041,0.0025),
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,5,0.0039,0.0024),
						array(6,9,0.0036,0.0022),
						array(10,19,0.0037,0.0022),
						array(20,0xffff,0.0038,0.0023),

				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,5,0.005,0.0031),
				  		array(6,9,0.004,0.0024),
				  		array(10,19,0.0042,0.0026),
				  		array(20,35,0.0042,0.0026),
						array(36,0xffff,0.0042,0.0026),
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,0.0042,0.0025),
				  		array(10,19,0.0044,0.0027),
				  		array(20,35,0.005,0.0031),
						array(36,0xffff,0.005,0.0031),
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,0.0042,0.0025),
						array(10,19,0.0044,0.0027),
						array(20,35,0.005,0.0031),
						array(36,0xffff,0.005,0.0031),

				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0.0046,0.0028),
						array(2,4.9999,0.0046,0.0028),
						array(5,9.9999,0.0046,0.0028),
						array(10,0xffff,0.0046,0.0028),
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0.0077,0.0048),
						array(2,4.9999,0.0077,0.0048),
						array(5,9.9999,0.0077,0.0048),
						array(10,0xffff,0.0077,0.0048),
				  	),  
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,0.0058,0.0039),
				  		array(2,2,0.0058,0.0039),
				  		array(3,3,0.0058,0.0039),
						array(4,4,0.0058,0.0039),
				  	),	
				  	'MOTORCYCLE' => array(								
				  		array(0,50,0.005,0.005),
				  		array(50.001,250,0.005,0.005),
				  		array(150.001,0xffff,0.005,0.005),
				  	),	
				  	'DUAL_PURPOSE_TRACTOR' => array(								
				  		array(0,14.7,0.005,0.005),
				  		array(14.701,0xffff,0.005,0.005),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,0.005,0.005),
				  		array(14.701,0xffff,0.005,0.005),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0.0046,0.0028),
				  	),	
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0.0077,0.0048),
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
	  *	手续费费率
	  **/			  
	private       $FeeRate = 0.03;
	
	/**
	 * 保费计算.
	 * @params  保险变量.
	 *          键值是变量名,变量名如下:
	 * 			TYPE_AUTO           车辆类型.
	 * 			SEATS               座位数.
	 * 			LOAD                载重(吨).
	 * 			ENGINE              发动机排量(cc).
	 * 			POWER               发动机功率(KW).
	 * 			TYPE_SPECIAL_AUTO   特种车分类(1,2,3,4).
	 * 			MONTHS              保险期间(月份数,1-12).
	 *          PASSENGERS          乘客数量.
	 *          TCPLI_INSURANCE_PASSENGER_AMOUNT   乘客保额.
	 *          TCPLI_INSURANCE_DRIVER_AMOUNT   驾驶人保额.
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
		
		if(!array_key_exists('TCPLI_INSURANCE_PASSENGER_AMOUNT',$params))
		{
			return false;
		}
		$insPassengerAmount = $params['TCPLI_INSURANCE_PASSENGER_AMOUNT'];

		if(!array_key_exists('TCPLI_INSURANCE_DRIVER_AMOUNT',$params))
		{
			return false;
		}
		$insDriverAmount = $params['TCPLI_INSURANCE_DRIVER_AMOUNT'];
			
		
		$passengrs = 0;
		if(array_key_exists('PASSENGERS',$params))
		{
			$passengrs = $params['PASSENGERS'];
		}
		
		$cost = 0;
		$sl = 0;

		
		switch($type)
		{
			case 'NON_OPERATING_PRIVATE'   :
			case 'NON_OPERATING_ENTERPRISE':
			case 'NON_OPERATING_AUTHORITY' :
			case 'OPERATING_LEASE_RENTAL'  :
			case 'OPERATING_CITY_BUS'      :
			case 'OPERATING_HIGHWAY_BUS'   :
				if(array_key_exists('SEATS',$params)) $sl = $params['SEATS'];					
				break;
            case 'NON_OPERATING_TRUCK'     :
            case 'OPERATING_TRUCK'         :
            case 'NONE_OPERATING_TRAILER'  :
            case 'OPERATING_TRAILER'       :
				if(array_key_exists('LOAD',$params)) $sl = $params['LOAD'];		
				break;
            case 'SPECIAL_AUTO'            :
				if(array_key_exists('TYPE_SPECIAL_AUTO',$params)) $sl = $params['TYPE_SPECIAL_AUTO'];		
				break;
            case 'MOTORCYCLE'              :
				if(array_key_exists('ENGINE',$params)) $sl = $params['ENGINE'];		
				break;
            case 'DUAL_PURPOSE_TRACTOR'    :
			case 'TRANSPORT_TRACTOR'       :
				if(array_key_exists('POWER',$params)) $sl = $params['POWER'];		
				break;
            case 'NON_OPERATING_LOW_SPEED_TRUCK':
			case 'OPERATING_LOW_SPEED_TRUCK':
				$sl = 0;
				break;	
		}	

		
		foreach($this->Rate[$type] as $row )
		{
			if($row[0] <= $sl && $sl <= $row[1] )
			{
				$cost = $insDriverAmount * $row[2] + $insPassengerAmount * $row[3] * $passengrs;
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