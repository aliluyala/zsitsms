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
	public         $Version = 'DSZZRX-2009(A)-YN-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,5,698),
						array(0,5,10,1007),
						array(0,5,15,1148),
						array(0,5,20,1248),
						array(0,5,30,1408),
						array(0,5,50,1690),
						array(0,5,100,2201),
						
				  		array(6,9,5  ,718),
						array(6,9,10 ,1012),
						array(6,9,15 ,1144),
						array(6,9,20 ,1234),
						array(6,9,30 ,1382),
						array(6,9,50 ,1645),
						array(6,9,100,2142),
						
						array(10,0xffff,5  ,718),
						array(10,0xffff,10 ,1012),
						array(10,0xffff,15 ,1144),
						array(10,0xffff,20 ,1234),
						array(10,0xffff,30 ,1382),
						array(10,0xffff,50 ,1645),
						array(10,0xffff,100,2142),	
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,5,5,758),
						array(0,5,10,1067),
						array(0,5,15,1206),
						array(0,5,20,1301),
						array(0,5,30,1456),
						array(0,5,50,1734),
						array(0,5,100,2258),
						
				  		array(6,9,5,730),
						array(6,9,10,1039),
						array(6,9,15,1178),
						array(6,9,20,1275),
						array(6,9,30,1432),
						array(6,9,50,1711),
						array(6,9,100,2227),

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
				  		array(0,5,5,639),
						array(0,5,10,900),
						array(0,5,15,1018),
						array(0,5,20,1097),
						array(0,5,30,1229),
						array(0,5,50,1463),
						array(0,5,100,1905),
						
				  		array(6,9,5,612),
						array(6,9,10,862),
						array(6,9,15,974),
						array(6,9,20,1050),
						array(6,9,30,1177),
						array(6,9,50,1401),
						array(6,9,100,1824),

				  		array(10,19,5,730),
						array(10,19,10,1028),
						array(10,19,15,1163),
						array(10,19,20,1253),
						array(10,19,30,1404),
						array(10,19,50,1671),
						array(10,19,100,2176),						
						
						array(20,0xffff,5,938),
						array(20,0xffff,10,1321),
						array(20,0xffff,15,1494),
						array(20,0xffff,20,1611),
						array(20,0xffff,30,1804),
						array(20,0xffff,50,2148),
						array(20,0xffff,100,2797),			
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,5,5,1495),
						array(0,5,10,2257),
						array(0,5,15,2623),
						array(0,5,20,2870),
						array(0,5,30,3330),
						array(0,5,50,4220),
						array(0,5,100,5551),
						
				  		array(6,9,5,1410),
						array(6,9,10,2128),
						array(6,9,15,2473),
						array(6,9,20,2707),
						array(6,9,30,3140),
						array(6,9,50,3980),
						array(6,9,100,5234),

				  		array(10,19,5,1491),
						array(10,19,10,2287),
						array(10,19,15,2672),
						array(10,19,20,2939),
						array(10,19,30,3428),
						array(10,19,50,4366),
						array(10,19,100,5743),						

				  		array(20,35,5,2004),
						array(20,35,10,3166),
						array(20,35,15,3734),
						array(20,35,20,4145),
						array(20,35,30,4877),
						array(20,35,50,6266),
						array(20,35,100,8242),										
						
						array(36,0xffff,5,2998),
						array(36,0xffff,10,4630),
						array(36,0xffff,15,5423),
						array(36,0xffff,20,5978),
						array(36,0xffff,30,6986),
						array(36,0xffff,50,8920),
						array(36,0xffff,100,11733),			
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,5,1383),
						array(6,9,10,2086),
						array(6,9,15,2426),
						array(6,9,20,2653),
						array(6,9,30,3079),
						array(6,9,50,3901),
						array(6,9,100,5133),

				  		array(10,19,5,1540),
						array(10,19,10,2324),
						array(10,19,15,2701),
						array(10,19,20,2956),
						array(10,19,30,3429),
						array(10,19,50,4346),
						array(10,19,100,5717),						

				  		array(20,35,5,2135),
						array(20,35,10,3282),
						array(20,35,15,3838),
						array(20,35,20,4225),
						array(20,35,30,4931),
						array(20,35,50,6286),
						array(20,35,100,8268),										
						
						array(36,0xffff,5,2746),
						array(36,0xffff,10,4337),
						array(36,0xffff,15,5115),
						array(36,0xffff,20,5678),
						array(36,0xffff,30,6681),
						array(36,0xffff,50,8584),
						array(36,0xffff,100,11292),		
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,5,1353),
						array(6,9,10,2042),
						array(6,9,15,2373),
						array(6,9,20,2597),
						array(6,9,30,3013),
						array(6,9,50,3819),
						array(6,9,100,5023),

				  		array(10,19,5,1507),
						array(10,19,10,2275),
						array(10,19,15,2644),
						array(10,19,20,2893),
						array(10,19,30,3356),
						array(10,19,50,4253),
						array(10,19,100,5594),						

				  		array(20,35,5,2218),
						array(20,35,10,3346),
						array(20,35,15,3890),
						array(20,35,20,4257),
						array(20,35,30,4938),
						array(20,35,50,6259),
						array(20,35,100,8232),										
						
						array(36,0xffff,5,3098),
						array(36,0xffff,10,4676),
						array(36,0xffff,15,5435),
						array(36,0xffff,20,5947),
						array(36,0xffff,30,6899),
						array(36,0xffff,50,8744),
						array(36,0xffff,100,11501),		
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,5,805),
						array(0,1.9999,10,1133),
						array(0,1.9999,15,1280),
						array(0,1.9999,20,1381),
						array(0,1.9999,30,1547),
						array(0,1.9999,50,1841),
						array(0,1.9999,100,2398),
						
						array(2,4.9999,5,1052),
						array(2,4.9999,10,1521),
						array(2,4.9999,15,1734),
						array(2,4.9999,20,1886),
						array(2,4.9999,30,2129),
						array(2,4.9999,50,2554),
						array(2,4.9999,100,3327),	
						
						array(5,9.9999,5,1324),
						array(5,9.9999,10,1887),
						array(5,9.9999,15,2143),
						array(5,9.9999,20,2319),
						array(5,9.9999,30,2607),
						array(5,9.9999,50,3116),
						array(5,9.9999,100,4058),	
						
						array(10,0xffff,5,1758),
						array(10,0xffff,10,2477),
						array(10,0xffff,15,2801),
						array(10,0xffff,20,3020),
						array(10,0xffff,30,3382),
						array(10,0xffff,50,4027),
						array(10,0xffff,100,5243),	
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,5,1061),
						array(0,1.9999,10,1655),
						array(0,1.9999,15,1946),
						array(0,1.9999,20,2143),
						array(0,1.9999,30,2524),
						array(0,1.9999,50,3164),
						array(0,1.9999,100,4132),
						
						array(2,4.9999,5,1707),
						array(2,4.9999,10,2663),
						array(2,4.9999,15,3133),
						array(2,4.9999,20,3450),
						array(2,4.9999,30,4062),
						array(2,4.9999,50,5092),
						array(2,4.9999,100,6651),	
						
						array(5,9.9999,5,1960),
						array(5,9.9999,10,3057),
						array(5,9.9999,15,3596),
						array(5,9.9999,20,3960),
						array(5,9.9999,30,4662),
						array(5,9.9999,50,5846),
						array(5,9.9999,100,7635),	
						
						array(10,0xffff,5,2686),
						array(10,0xffff,10,4190),
						array(10,0xffff,15,4929),
						array(10,0xffff,20,5427),
						array(10,0xffff,30,6389),
						array(10,0xffff,50,8011),
						array(10,0xffff,100,10462),	
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,1.9999,5  ,805 *0.3),
						array(0,1.9999,10 ,1133*0.3),
						array(0,1.9999,15 ,1280*0.3),
						array(0,1.9999,20 ,1381*0.3),
						array(0,1.9999,30 ,1547*0.3),
						array(0,1.9999,50 ,1841*0.3),
						array(0,1.9999,100,2398*0.3),
						
						array(2,4.9999,5  ,1052*0.3),
						array(2,4.9999,10 ,1521*0.3),
						array(2,4.9999,15 ,1734*0.3),
						array(2,4.9999,20 ,1886*0.3),
						array(2,4.9999,30 ,2129*0.3),
						array(2,4.9999,50 ,2554*0.3),
						array(2,4.9999,100,3327*0.3),	
						
						array(5,9.9999,5  ,1324*0.3),
						array(5,9.9999,10 ,1887*0.3),
						array(5,9.9999,15 ,2143*0.3),
						array(5,9.9999,20 ,2319*0.3),
						array(5,9.9999,30 ,2607*0.3),
						array(5,9.9999,50 ,3116*0.3),
						array(5,9.9999,100,4058*0.3),	
						
						array(10,0xffff,5  ,1758*0.3),
						array(10,0xffff,10 ,2477*0.3),
						array(10,0xffff,15 ,2801*0.3),
						array(10,0xffff,20 ,3020*0.3),
						array(10,0xffff,30 ,3382*0.3),
						array(10,0xffff,50 ,4027*0.3),
						array(10,0xffff,100,5243*0.3),	
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,1.9999,5  ,1061*0.3),
						array(0,1.9999,10 ,1655*0.3),
						array(0,1.9999,15 ,1946*0.3),
						array(0,1.9999,20 ,2143*0.3),
						array(0,1.9999,30 ,2524*0.3),
						array(0,1.9999,50 ,3164*0.3),
						array(0,1.9999,100,4132*0.3),
						
						array(2,4.9999,5  ,1707*0.3),
						array(2,4.9999,10 ,2663*0.3),
						array(2,4.9999,15 ,3133*0.3),
						array(2,4.9999,20 ,3450*0.3),
						array(2,4.9999,30 ,4062*0.3),
						array(2,4.9999,50 ,5092*0.3),
						array(2,4.9999,100,6651*0.3),	
						
						array(5,9.9999,5  ,1960*0.3),
						array(5,9.9999,10 ,3057*0.3),
						array(5,9.9999,15 ,3596*0.3),
						array(5,9.9999,20 ,3960*0.3),
						array(5,9.9999,30 ,4662*0.3),
						array(5,9.9999,50 ,5846*0.3),
						array(5,9.9999,100,7635*0.3),	
						
						array(10,0xffff,5 ,2686*0.3),
						array(10,0xffff,10,4190*0.3),
						array(10,0xffff,15,4929*0.3),
						array(10,0xffff,20,5427*0.3),
						array(10,0xffff,30,6389*0.3),
						array(10,0xffff,50,8011*0.3),
						array(10,0xffff,100,10462*0.3),				
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,5,2667),
						array(1,1,10,3952),
						array(1,1,15,4690),
						array(1,1,20,5208),
						array(1,1,30,6183),
						array(1,1,50,7817),
						array(1,1,100,10209),
						
				  		array(2,2,5,953),
						array(2,2,10,1227),
						array(2,2,15,1386),
						array(2,2,20,1533),
						array(2,2,30,1858),
						array(2,2,50,2434),
						array(2,2,100,3586),
						
				  		array(3,3,5,437),
						array(3,3,10,571),
						array(3,3,15,648),
						array(3,3,20,720),
						array(3,3,30,876),
						array(3,3,50,1151),
						array(3,3,100,1687),
						
				  		array(4,4,5,2344),
						array(4,4,10,3754),
						array(4,4,15,4456),
						array(4,4,20,5208),
						array(4,4,30,6492),
						array(4,4,50,8208),
						array(4,4,100,10719),
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
				  		array(0,14.7,5,77),
						array(0,14.7,10,97),
						array(0,14.7,15,108),
						array(0,14.7,20,116),
						array(0,14.7,30,127),
						array(0,14.7,50,148),
						array(0,14.7,100,193),
						
				  		array(14.701,0xffff,5,211),
						array(14.701,0xffff,10,268),
						array(14.701,0xffff,15,301),
						array(14.701,0xffff,20,325),
						array(14.701,0xffff,30,358),
						array(14.701,0xffff,50,420),
						array(14.701,0xffff,100,547),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,5,186),
						array(0,14.7,10,232),
						array(0,14.7,15,259),
						array(0,14.7,20,279),
						array(0,14.7,30,306),
						array(0,14.7,50,357),
						array(0,14.7,100,463),
						
				  		array(14.701,0xffff,5,305),
						array(14.701,0xffff,10,387),
						array(14.701,0xffff,15,435),
						array(14.701,0xffff,20,469),
						array(14.701,0xffff,30,517),
						array(14.701,0xffff,50,606),
						array(14.701,0xffff,100,791),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,5,683),
						array(0,0,10,962),
						array(0,0,15,1089),
						array(0,0,20,1174),
						array(0,0,30,1314),
						array(0,0,50,1566),
						array(0,0,100,2039),
				  	),	
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,5,903),
						array(0,0,10,1407),
						array(0,0,15,1656),
						array(0,0,20,1823),
						array(0,0,30,2147),
						array(0,0,50,2691),
						array(0,0,100,3514),
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