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
	public         $Version = 'DSZZRX-2009(A)-SC-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,5,710),
						array(0,5,10,1026),
						array(0,5,15,1169),
						array(0,5,20,1270),
						array(0,5,30,1434),
						array(0,5,50,1721),
						array(0,5,100,2242),
						
				  		array(6,9,5,659),
						array(6,9,10,928),
						array(6,9,15,1048),
						array(6,9,20,1131),
						array(6,9,30,1266),
						array(6,9,50,1507),
						array(6,9,100,1963),
						
						array(10,0xffff,5,659),
						array(10,0xffff,10,928),
						array(10,0xffff,15,1048),
						array(10,0xffff,20,1131),
						array(10,0xffff,30,1266),
						array(10,0xffff,50,1507),
						array(10,0xffff,100,1963),		
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
						array(6,9,15,1179),
						array(6,9,20,1275),
						array(6,9,30,1433),
						array(6,9,50,1711),
						array(6,9,100,2228),

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
				  		array(0,5,5,1579),
						array(0,5,10,2382),
						array(0,5,15,2769),
						array(0,5,20,3029),
						array(0,5,30,3516),
						array(0,5,50,4454),
						array(0,5,100,5860),
						
				  		array(6,9,5,1489),
						array(6,9,10,2246),
						array(6,9,15,2610),
						array(6,9,20,2857),
						array(6,9,30,3314),
						array(6,9,50,4201),
						array(6,9,100,5528),

				  		array(10,19,5,1574),
						array(10,19,10,2414),
						array(10,19,15,2821),
						array(10,19,20,3102),
						array(10,19,30,3618),
						array(10,19,50,4608),
						array(10,19,100,6061),						

				  		array(20,35,5,2116),
						array(20,35,10,3342),
						array(20,35,15,3941),
						array(20,35,20,4375),
						array(20,35,30,5147),
						array(20,35,50,6614),
						array(20,35,100,8700),										
						
						array(36,0xffff,5,3331),
						array(36,0xffff,10,5144),
						array(36,0xffff,15,6024),
						array(36,0xffff,20,6641),
						array(36,0xffff,30,7763),
						array(36,0xffff,50,9910),
						array(36,0xffff,100,13035),			
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,5,1459),
						array(6,9,10,2203),
						array(6,9,15,2560),
						array(6,9,20,2801),
						array(6,9,30,3250),
						array(6,9,50,4118),
						array(6,9,100,5417),

				  		array(10,19,5,1625),
						array(10,19,10,2453),
						array(10,19,15,2851),
						array(10,19,20,3120),
						array(10,19,30,3620),
						array(10,19,50,4587),
						array(10,19,100,6035),						

				  		array(20,35,5,2253),
						array(20,35,10,3465),
						array(20,35,15,4052),
						array(20,35,20,4460),
						array(20,35,30,5206),
						array(20,35,50,6636),
						array(20,35,100,8728),										
						
						array(36,0xffff,5,3051),
						array(36,0xffff,10,4819),
						array(36,0xffff,15,5684),
						array(36,0xffff,20,6309),
						array(36,0xffff,30,7423),
						array(36,0xffff,50,9538),
						array(36,0xffff,100,12546),		
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,5,1429),
						array(6,9,10,2155),
						array(6,9,15,2505),
						array(6,9,20,2741),
						array(6,9,30,3180),
						array(6,9,50,4031),
						array(6,9,100,5302),

				  		array(10,19,5,1591),
						array(10,19,10,2401),
						array(10,19,15,2791),
						array(10,19,20,3053),
						array(10,19,30,3542),
						array(10,19,50,4489),
						array(10,19,100,5905),						

				  		array(20,35,5,2341),
						array(20,35,10,3533),
						array(20,35,15,4106),
						array(20,35,20,4494),
						array(20,35,30,5213),
						array(20,35,50,6607),
						array(20,35,100,8691),										
						
						array(36,0xffff,5,3443),
						array(36,0xffff,10,5195),
						array(36,0xffff,15,6039),
						array(36,0xffff,20,6607),
						array(36,0xffff,30,7666),
						array(36,0xffff,50,9715),
						array(36,0xffff,100,12780),		
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
				  		array(0,1.9999,5,1120),
						array(0,1.9999,10,1746),
						array(0,1.9999,15,2055),
						array(0,1.9999,20,2262),
						array(0,1.9999,30,2664),
						array(0,1.9999,50,3340),
						array(0,1.9999,100,4362),
						
						array(2,4.9999,5,1802),
						array(2,4.9999,10,2811),
						array(2,4.9999,15,3307),
						array(2,4.9999,20,3642),
						array(2,4.9999,30,4288),
						array(2,4.9999,50,5376),
						array(2,4.9999,100,7021),	
						
						array(5,9.9999,5,2069),
						array(5,9.9999,10,3226),
						array(5,9.9999,15,3796),
						array(5,9.9999,20,4180),
						array(5,9.9999,30,4921),
						array(5,9.9999,50,6171),
						array(5,9.9999,100,8059),	
						
						array(10,0xffff,5,2835),
						array(10,0xffff,10,4422),
						array(10,0xffff,15,5203),
						array(10,0xffff,20,5729),
						array(10,0xffff,30,6745),
						array(10,0xffff,50,8455),
						array(10,0xffff,100,11043),	
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,1.9999,5,  241.5),
						array(0,1.9999,10, 339.9),
						array(0,1.9999,15, 384),
						array(0,1.9999,20, 414.3),
						array(0,1.9999,30, 464.1),
						array(0,1.9999,50, 552.3),
						array(0,1.9999,100,719.4),
						
						array(2,4.9999,5,  315.6),
						array(2,4.9999,10, 456.3),
						array(2,4.9999,15, 520.2),
						array(2,4.9999,20, 565.8),
						array(2,4.9999,30, 638.7),
						array(2,4.9999,50, 766.2),
						array(2,4.9999,100,998.1),	
						
						array(5,9.9999,5,  375),
						array(5,9.9999,10, 534.9),
						array(5,9.9999,15, 606.9),
						array(5,9.9999,20, 657.3),
						array(5,9.9999,30, 738.6),
						array(5,9.9999,50, 882.9),
						array(5,9.9999,100,1149.6),	
						
						array(10,0xffff,5,  493.8),
						array(10,0xffff,10, 695.7),
						array(10,0xffff,15, 786.6),
						array(10,0xffff,20, 848.1),
						array(10,0xffff,30, 949.8),
						array(10,0xffff,50, 1131),
						array(10,0xffff,100,1472.4),	
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,1.9999,5,  336),
						array(0,1.9999,10, 523.8),
						array(0,1.9999,15, 616.5),
						array(0,1.9999,20, 678.6),
						array(0,1.9999,30, 799.2),
						array(0,1.9999,50, 1002),
						array(0,1.9999,100,1308.6),
						
						array(2,4.9999,5,  540.6),
						array(2,4.9999,10, 843.3),
						array(2,4.9999,15, 992.1),
						array(2,4.9999,20, 1092.6),
						array(2,4.9999,30, 1286.4),
						array(2,4.9999,50, 1612.8),
						array(2,4.9999,100,2106.3),	
						
						array(5,9.9999,5,  620.7),
						array(5,9.9999,10, 967.8),
						array(5,9.9999,15, 1138.8),
						array(5,9.9999,20, 1254),
						array(5,9.9999,30, 1476.3),
						array(5,9.9999,50, 1851.3),
						array(5,9.9999,100,2417.7),	
						
						array(10,0xffff,5,  850.5),
						array(10,0xffff,10, 1326.6),
						array(10,0xffff,15, 1560.9),
						array(10,0xffff,20, 1718.7),
						array(10,0xffff,30, 2023.5),
						array(10,0xffff,50, 2536.5),
						array(10,0xffff,100,3312.9),				
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,5,2604),
						array(1,1,10,4171),
						array(1,1,15,4950),
						array(1,1,20,5498),
						array(1,1,30,6527),
						array(1,1,50,8251),
						array(1,1,100,10777),
						
				  		array(2,2,5,1319),
						array(2,2,10,1699),
						array(2,2,15,1919),
						array(2,2,20,2123),
						array(2,2,30,2572),
						array(2,2,50,3369),
						array(2,2,100,4966),
						
				  		array(3,3,5,604),
						array(3,3,10,790),
						array(3,3,15,896),
						array(3,3,20,997),
						array(3,3,30,1212),
						array(3,3,50,1594),
						array(3,3,100,2335),
						
				  		array(4,4,5,2474),
						array(4,4,10,3963),
						array(4,4,15,4703),
						array(4,4,20,5498),
						array(4,4,30,6853),
						array(4,4,50,8664),
						array(4,4,100,11315),
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
				  		array(0,14.7,5,120),
						array(0,14.7,10,150),
						array(0,14.7,15,168),
						array(0,14.7,20,181),
						array(0,14.7,30,198),
						array(0,14.7,50,231),
						array(0,14.7,100,300),
						
				  		array(14.701,0xffff,5,328),
						array(14.701,0xffff,10,417),
						array(14.701,0xffff,15,468),
						array(14.701,0xffff,20,505),
						array(14.701,0xffff,30,557),
						array(14.701,0xffff,50,653),
						array(14.701,0xffff,100,852),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,5,289),
						array(0,14.7,10,361),
						array(0,14.7,15,403),
						array(0,14.7,20,433),
						array(0,14.7,30,475),
						array(0,14.7,50,554),
						array(0,14.7,100,721),
						
				  		array(14.701,0xffff,5,474),
						array(14.701,0xffff,10,601),
						array(14.701,0xffff,15,676),
						array(14.701,0xffff,20,729),
						array(14.701,0xffff,30,804),
						array(14.701,0xffff,50,943),
						array(14.701,0xffff,100,1231),
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
				  		array(0,0,5,953),
						array(0,0,10,1486),
						array(0,0,15,1748),
						array(0,0,20,1925),
						array(0,0,30,2266),
						array(0,0,50,2840),
						array(0,0,100,3710),
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