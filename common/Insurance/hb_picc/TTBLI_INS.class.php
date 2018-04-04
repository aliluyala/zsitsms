<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         TTBLI_INS.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 机动车第三方责任险保费计算.
 * 
 **/ 
 
class TTBLI_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'DSZZRX-2008(A)-HB-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,5,660),
						array(0,5,10,954),
						array(0,5,15,1088),
						array(0,5,20,1182),
						array(0,5,30,1335),
						array(0,5,50,1602),
						array(0,5,100,2086),
						
				  		array(6,9,5,  698 ),
						array(6,9,10, 983 ),
						array(6,9,15, 1112),
						array(6,9,20, 1199),
						array(6,9,30, 1342),
						array(6,9,50, 1598),
						array(6,9,100,2081),
						
						array(10,0xffff,5,  698 ),
						array(10,0xffff,10, 983 ),
						array(10,0xffff,15, 1112),
						array(10,0xffff,20, 1199),
						array(10,0xffff,30, 1342),
						array(10,0xffff,50, 1598),
						array(10,0xffff,100,2081),		
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,5,5,758),
						array(0,5,10,1067),
						array(0,5,15,1206),
						array(0,5,20,1301),
						array(0,5,30,1456),
						array(0,5,50,1734),
						array(0,5,100,2258),
						
				  		array(6,9,5,767),
						array(6,9,10,1091),
						array(6,9,15,1238),
						array(6,9,20,1339),
						array(6,9,30,1504),
						array(6,9,50,1796),
						array(6,9,100,2340),

				  		array(10,19,5,846),
						array(10,19,10,1207),
						array(10,19,15,1370),
						array(10,19,20,1484),
						array(10,19,30,1669),
						array(10,19,50,1995),
						array(10,19,100,2599),						
						
						array(20,0xffff,5,856),
						array(20,0xffff,10,1262),
						array(20,0xffff,15,1449),
						array(20,0xffff,20,1585),
						array(20,0xffff,30,1799),
						array(20,0xffff,50,2172),
						array(20,0xffff,100,2829),		
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,5,5,671),
						array(0,5,10,946),
						array(0,5,15,1069),
						array(0,5,20,1152),
						array(0,5,30,1291),
						array(0,5,50,1537),
						array(0,5,100,2000),
						
				  		array(6,9,5,643),
						array(6,9,10,905),
						array(6,9,15,1024),
						array(6,9,20,1104),
						array(6,9,30,1236),
						array(6,9,50,1472),
						array(6,9,100,1916),

				  		array(10,19,5,766),
						array(10,19,10,1079),
						array(10,19,15,1220),
						array(10,19,20,1316),
						array(10,19,30,1473),
						array(10,19,50,1754),
						array(10,19,100,2284),						
						
						array(20,0xffff,5,938),
						array(20,0xffff,10,1321),
						array(20,0xffff,15,1494),
						array(20,0xffff,20,1611),
						array(20,0xffff,30,1804),
						array(20,0xffff,50,2148),
						array(20,0xffff,100,2797),			
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,5,5,1421),
						array(0,5,10,2145),
						array(0,5,15,2493),
						array(0,5,20,2727),
						array(0,5,30,3164),
						array(0,5,50,4011),
						array(0,5,100,5276),
						
				  		array(6,9,5,1629),
						array(6,9,10,2458),
						array(6,9,15,2857),
						array(6,9,20,3126),
						array(6,9,30,3627),
						array(6,9,50,4596),
						array(6,9,100,6047),

				  		array(10,19,5,1722),
						array(10,19,10,2641),
						array(10,19,15,3086),
						array(10,19,20,3394),
						array(10,19,30,3958),
						array(10,19,50,5042),
						array(10,19,100,6633),						

				  		array(20,35,5,2315),
						array(20,35,10,3656),
						array(20,35,15,4312),
						array(20,35,20,4786),
						array(20,35,30,5632),
						array(20,35,50,7237),
						array(20,35,100,9518),										
						
						array(36,0xffff,5,3155),
						array(36,0xffff,10,4874),
						array(36,0xffff,15,5707),
						array(36,0xffff,20,6292),
						array(36,0xffff,30,7355),
						array(36,0xffff,50,9389),
						array(36,0xffff,100,12349),			
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,5,1521),
						array(6,9,10,2294),
						array(6,9,15,2668),
						array(6,9,20,2919),
						array(6,9,30,3387),
						array(6,9,50,4292),
						array(6,9,100,5644),

				  		array(10,19,5,1694),
						array(10,19,10,2557),
						array(10,19,15,2972),
						array(10,19,20,3251),
						array(10,19,30,3773),
						array(10,19,50,4781),
						array(10,19,100,6288),						

				  		array(20,35,5,2349),
						array(20,35,10,3610),
						array(20,35,15,4221),
						array(20,35,20,4646),
						array(20,35,30,5423),
						array(20,35,50,6913),
						array(20,35,100,9094),										
						
						array(36,0xffff,5,2752),
						array(36,0xffff,10,4347),
						array(36,0xffff,15,5128),
						array(36,0xffff,20,5692),
						array(36,0xffff,30,6697),
						array(36,0xffff,50,8606),
						array(36,0xffff,100,11319),		
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,5,1712),
						array(6,9,10,2583),
						array(6,9,15,3003),
						array(6,9,20,3286),
						array(6,9,30,3812),
						array(6,9,50,4830),
						array(6,9,100,6354),

				  		array(10,19,5,1906),
						array(10,19,10,2877),
						array(10,19,15,3344),
						array(10,19,20,3659),
						array(10,19,30,4245),
						array(10,19,50,5381),
						array(10,19,100,7077),						

				  		array(20,35,5,2806),
						array(20,35,10,4235),
						array(20,35,15,4922),
						array(20,35,20,5386),
						array(20,35,30,6249),
						array(20,35,50,7918),
						array(20,35,100,10417),										
						
						array(36,0xffff,5,3571),
						array(36,0xffff,10,5389),
						array(36,0xffff,15,6265),
						array(36,0xffff,20,6855),
						array(36,0xffff,30,7953),
						array(36,0xffff,50,10079),
						array(36,0xffff,100,13258),		
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,5,800),
						array(0,1.9999,10,1126),
						array(0,1.9999,15,1274),
						array(0,1.9999,20,1373),
						array(0,1.9999,30,1538),
						array(0,1.9999,50,1831),
						array(0,1.9999,100,2385),
						
						array(2,4.9999,5,1082),
						array(2,4.9999,10,1564),
						array(2,4.9999,15,1783),
						array(2,4.9999,20,1939),
						array(2,4.9999,30,2190),
						array(2,4.9999,50,2628),
						array(2,4.9999,100,3423),	
						
						array(5,9.9999,5,1250),
						array(5,9.9999,10,1783),
						array(5,9.9999,15,2023),
						array(5,9.9999,20,2191),
						array(5,9.9999,30,2462),
						array(5,9.9999,50,2943),
						array(5,9.9999,100,3832),	
						
						array(10,0xffff,5,1646),
						array(10,0xffff,10,2319),
						array(10,0xffff,15,2622),
						array(10,0xffff,20,2827),
						array(10,0xffff,30,3166),
						array(10,0xffff,50,3770),
						array(10,0xffff,100,4908),	
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,5,1273),
						array(0,1.9999,10,1985),
						array(0,1.9999,15,2336),
						array(0,1.9999,20,2571),
						array(0,1.9999,30,3028),
						array(0,1.9999,50,3795),
						array(0,1.9999,100,4957),
						
						array(2,4.9999,5,2125),
						array(2,4.9999,10,3313),
						array(2,4.9999,15,3898),
						array(2,4.9999,20,4292),
						array(2,4.9999,30,5053),
						array(2,4.9999,50,6334),
						array(2,4.9999,100,8273),	
						
						array(5,9.9999,5,2348),
						array(5,9.9999,10,3661),
						array(5,9.9999,15,4308),
						array(5,9.9999,20,4743),
						array(5,9.9999,30,5585),
						array(5,9.9999,50,7001),
						array(5,9.9999,100,9144),	
						
						array(10,0xffff,5,3868),
						array(10,0xffff,10,6031),
						array(10,0xffff,15,7097),
						array(10,0xffff,20,7814),
						array(10,0xffff,30,9201),
						array(10,0xffff,50,11534),
						array(10,0xffff,100,15064),	
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,1.9999,5,240),
						array(0,1.9999,10,338),
						array(0,1.9999,15,383),
						array(0,1.9999,20,412),
						array(0,1.9999,30,462),
						array(0,1.9999,50,549),
						array(0,1.9999,100,716),
						
						array(2,4.9999,5,  325),
						array(2,4.9999,10, 469),
						array(2,4.9999,15, 535),
						array(2,4.9999,20, 582),
						array(2,4.9999,30, 657),
						array(2,4.9999,50, 788),
						array(2,4.9999,100,1027),	
						
						array(5,9.9999,5,  375),
						array(5,9.9999,10, 535),
						array(5,9.9999,15, 607),
						array(5,9.9999,20, 657),
						array(5,9.9999,30, 739),
						array(5,9.9999,50, 883),
						array(5,9.9999,100,1150),	
						
						array(10,0xffff,5,  494),
						array(10,0xffff,10, 696),
						array(10,0xffff,15, 787),
						array(10,0xffff,20, 848),
						array(10,0xffff,30, 950),
						array(10,0xffff,50, 1131),
						array(10,0xffff,100,1472),	
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,1.9999,5,  382),
						array(0,1.9999,10, 596),
						array(0,1.9999,15, 701),
						array(0,1.9999,20, 771),
						array(0,1.9999,30, 908),
						array(0,1.9999,50, 1139),
						array(0,1.9999,100,1487),
						
						array(2,4.9999,5,  638),
						array(2,4.9999,10, 994),
						array(2,4.9999,15, 1169),
						array(2,4.9999,20, 1288),
						array(2,4.9999,30, 1516),
						array(2,4.9999,50, 1900),
						array(2,4.9999,100,2482),	
						
						array(5,9.9999,5,  704),
						array(5,9.9999,10, 1098),
						array(5,9.9999,15, 1292),
						array(5,9.9999,20, 1423),
						array(5,9.9999,30, 1676),
						array(5,9.9999,50, 2100),
						array(5,9.9999,100,2743),	
						
						array(10,0xffff,5,  1160),
						array(10,0xffff,10, 1809),
						array(10,0xffff,15, 2129),
						array(10,0xffff,20, 2344),
						array(10,0xffff,30, 2760),
						array(10,0xffff,50, 3460),
						array(10,0xffff,100,4519),			
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,5,3552),
						array(1,1,10,5689),
						array(1,1,15,6752),
						array(1,1,20,7498),
						array(1,1,30,8904),
						array(1,1,50,11256),
						array(1,1,100,14700),
						
				  		array(2,2,5,1276),
						array(2,2,10,1642),
						array(2,2,15,1854),
						array(2,2,20,2051),
						array(2,2,30,2486),
						array(2,2,50,3257),
						array(2,2,100,4801),
						
				  		array(3,3,5,583),
						array(3,3,10,764),
						array(3,3,15,866),
						array(3,3,20,963),
						array(3,3,30,1171),
						array(3,3,50,1540),
						array(3,3,100,2258),
						
				  		array(4,4,5,3375),
						array(4,4,10,5404),
						array(4,4,15,6415),
						array(4,4,20,7498),
						array(4,4,30,9349),
						array(4,4,50,11818),
						array(4,4,100,15436),
				  	),	
				  	'MOTORCYCLE' => array(								
				  		array(0,50,5,37),
						array(0,50,10,48),
						array(0,50,15,55),
						array(0,50,20,61),
						array(0,50,30,73),
						array(0,50,50,96),
						array(0,50,100,139),
						
				  		array(50.001,250,5,51),
						array(50.001,250,10,69),
						array(50.001,250,15,78),
						array(50.001,250,20,88),
						array(50.001,250,30,106),
						array(50.001,250,50,140),
						array(50.001,250,100,205),
						
				  		array(150.001,0xffff,5,88),
						array(150.001,0xffff,10,112),
						array(150.001,0xffff,15,126),
						array(150.001,0xffff,20,140),
						array(150.001,0xffff,30,169),
						array(150.001,0xffff,50,218),
						array(150.001,0xffff,100,318),
				  	),	
				  	'DUAL_PURPOSE_TRACTOR' => array(								
				  		array(0,14.7,5,134),
						array(0,14.7,10,168),
						array(0,14.7,15,188),
						array(0,14.7,20,202),
						array(0,14.7,30,222),
						array(0,14.7,50,258),
						array(0,14.7,100,336),
						
				  		array(14.701,0xffff,5,368),
						array(14.701,0xffff,10,466),
						array(14.701,0xffff,15,524),
						array(14.701,0xffff,20,565),
						array(14.701,0xffff,30,623),
						array(14.701,0xffff,50,731),
						array(14.701,0xffff,100,953),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,5,323),
						array(0,14.7,10,404),
						array(0,14.7,15,451),
						array(0,14.7,20,485),
						array(0,14.7,30,532),
						array(0,14.7,50,621),
						array(0,14.7,100,806),
						
				  		array(14.701,0xffff,5,530),
						array(14.701,0xffff,10,673),
						array(14.701,0xffff,15,757),
						array(14.701,0xffff,20,816),
						array(14.701,0xffff,30,900),
						array(14.701,0xffff,50,1055),
						array(14.701,0xffff,100,1378),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,5,679),
						array(0,0,10,957),
						array(0,0,15,1083),
						array(0,0,20,1167),
						array(0,0,30,1306),
						array(0,0,50,1557),
						array(0,0,100,2027),
				  	),	
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,5,1082),
						array(0,0,10,1687),
						array(0,0,15,1985),
						array(0,0,20,2186),
						array(0,0,30,2573),
						array(0,0,50,3226),
						array(0,0,100,4214),
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
	 *          TTBLI_INSURANCE_AMOUNT    保额(元).
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
		
		if(!array_key_exists('TTBLI_INSURANCE_AMOUNT',$params))
		{
			return false;
		}
		$insAmount = $params['TTBLI_INSURANCE_AMOUNT']/10000;
		
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
			if($row[0] <= $sl && $sl <= $row[1] && $row[2] == $insAmount )
			{
				$cost = $row[3] ;
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