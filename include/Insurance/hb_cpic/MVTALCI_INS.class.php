<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         MVTALCI_INS.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 机动车交通事故责任强制险保费计算.
 * 
 **/ 
 
class MVTALCI_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'JQX-2008-HB-CPIC';	
	/**
	 * 主险代码
	 **/
	public        $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,950),
				  		array(6,0xffff,1100),							
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,5,1000),
				  		array(6,9,1130),
				  		array(10,19,1220),
				  		array(20,0xffff,1270),	
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,5,950),
				  		array(6,9,1070),
				  		array(10,19,1140),
				  		array(20,0xffff,1320),	
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,5,1800),
				  		array(6,9,2360),
				  		array(10,19,2400),
				  		array(20,35,2560),
				  		array(36,0xffff,3530),	
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,2250),
				  		array(10,19,2520),
				  		array(20,35,3020),
				  		array(36,0xffff,3140),	
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,2350),
				  		array(10,19,2620),
				  		array(20,35,3420),
				  		array(36,0xffff,4690),	
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,1200),
				  		array(2,4.9999,1470),
				  		array(5,9.9999,1650),
				  		array(10,0xffff,2220),	
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,1850),
				  		array(2,4.9999,3070),
				  		array(5,9.9999,3450),
				  		array(10,0xffff,4480),	
				  	),  
				  	'NON_OPERATING_TRAILER' => array(								
				  		array(0,1.9999,400),
				  		array(2,4.9999,490),
				  		array(5,9.9999,550),
				  		array(10,0xffff,740),	
				  	),
				  	'OPERATING_TRAILER' => array(								
				  		array(0,1.9999,616.67),
				  		array(2,4.9999,1023.34),
				  		array(5,9.9999,1150),
				  		array(10,0xffff,1493.34),	
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,3710),
				  		array(2,2,2430),
				  		array(3,3,1080),
				  		array(4,4,3980),	
				  	),	
				  	'MOTORCYCLE' => array(								
				  		array(0,50,80),
				  		array(50.001,250,120),
				  		array(250.001,0xffff,400),
				  	),	
				  	'DUAL_PURPOSE_TRACTOR' => array(								
				  		array(0,14.7,60),
				  		array(14.701,0xffff,90),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,400),
				  		array(14.701,0xffff,560),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,560),
				  	),
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,560),
				  	),								
				  );	
	/**
	 *	浮动标准
	 **/  	
	private       $FloatStandard = array(
				  	'A1' => -0.1,
				  	'A2' => -0.2,
				  	'A3' => -0.3,
				  	'A4' => 0,
				  	'A5' => 0.1,
				  	'A6' => 0.3,	
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
	 * 			FLOATING_RATE       费率浮动标准(A1,A2,A3,A4,A5,A6).
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
		
		if(!array_key_exists('FLOATING_RATE',$params) || 
		   !array_key_exists($params['FLOATING_RATE'],$this->FloatStandard))
		{	
			return false;
		}			
		$float = $params['FLOATING_RATE'];
		
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
				$cost = $row[2] ;
				break;
			}
		}
		
		return round($cost * $this->ShortRate[$months] * (1+$this->FloatStandard[$float]),1);		
	}
	
	/**
	 * 退还保险费计算.
	 * @params   保险变量
	 *           键值是变量名,变量名如下:
	 *           START_TIME         保险生效时间
	 *           END_TIME           保险结束时间
	 *           COST               保费总额
     *           STOP_TIME	        终止保险时间
	 **/ 	
	public function refund( Array $params = array() )
	{
		if(!array_key_exists('START_TIME',$params) ||
		   !array_key_exists('END_TIME',$params) ||
           !array_key_exists('COST',$params) ||
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
		return $params['COST'] * ( 1 - $pastDays/$totalDays);		
	}
	
}
 


?>