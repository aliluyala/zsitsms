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
	public         $Version = 'DSZZRX-2008(A)-JS-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,5,666),
						array(0,5,10,961),
						array(0,5,15,1097),
						array(0,5,20,1191),
						array(0,5,30,1345),
						array(0,5,50,1614),
						array(0,5,100,2102),
						
				  		array(6,9,5,  616 ),
						array(6,9,10, 869 ),
						array(6,9,15, 982),
						array(6,9,20, 1059),
						array(6,9,30, 1188),
						array(6,9,50, 1412),
						array(6,9,100,1839),
						
						array(10,0xffff,5,  616 ),
						array(10,0xffff,10, 869 ),
						array(10,0xffff,15, 982 ),
						array(10,0xffff,20, 1059),
						array(10,0xffff,30, 1188),
						array(10,0xffff,50, 1412),
						array(10,0xffff,100,1839),		
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,5,5,758),
						array(0,5,10,1067),
						array(0,5,15,1206),
						array(0,5,20,1301),
						array(0,5,30,1456),
						array(0,5,50,1734),
						array(0,5,100,2258),
						
				  		array(6,9,5, 713 ),
						array(6,9,10,1014),
						array(6,9,15,1150),
						array(6,9,20,1245),
						array(6,9,30,1398),
						array(6,9,50,1669),
						array(6,9,100,2175),

				  		array(10,19,5,841),
						array(10,19,10,1200),
						array(10,19,15,1364),
						array(10,19,20,1476),
						array(10,19,30,1660),
						array(10,19,50,1985),
						array(10,19,100,2585),						
						
						array(20,0xffff,5,856),
						array(20,0xffff,10,1262),
						array(20,0xffff,15,1449),
						array(20,0xffff,20,1585),
						array(20,0xffff,30,1799),
						array(20,0xffff,50,2172),
						array(20,0xffff,100,2829),		
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,5,5,624),
						array(0,5,10,879),
						array(0,5,15,994),
						array(0,5,20,1071),
						array(0,5,30,1199),
						array(0,5,50,1428),
						array(0,5,100,1881),
						
				  		array(6,9,5,597),
						array(6,9,10,841),
						array(6,9,15,951),
						array(6,9,20,1026),
						array(6,9,30,1149),
						array(6,9,50,1368),
						array(6,9,100,1781),

				  		array(10,19,5,712),
						array(10,19,10,1004),
						array(10,19,15,1135),
						array(10,19,20,1224),
						array(10,19,30,1370),
						array(10,19,50,1631),
						array(10,19,100,2124),						
						
						array(20,0xffff,5,938),
						array(20,0xffff,10,1321),
						array(20,0xffff,15,1494),
						array(20,0xffff,20,1611),
						array(20,0xffff,30,1804),
						array(20,0xffff,50,2148),
						array(20,0xffff,100,2797),			
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,5,5,1491),
						array(0,5,10,2250),
						array(0,5,15,2616),
						array(0,5,20,2861),
						array(0,5,30,3320),
						array(0,5,50,4208),
						array(0,5,100,5535),
						
				  		array(6,9,5,1406),
						array(6,9,10,2121),
						array(6,9,15,2465),
						array(6,9,20,2698),
						array(6,9,30,3131),
						array(6,9,50,3967),
						array(6,9,100,5219),

				  		array(10,19,5,1487),
						array(10,19,10,2279),
						array(10,19,15,2664),
						array(10,19,20,2930),
						array(10,19,30,3417),
						array(10,19,50,4353),
						array(10,19,100,5726),						

				  		array(20,35,5,1998),
						array(20,35,10,3157),
						array(20,35,15,3723),
						array(20,35,20,4132),
						array(20,35,30,4862),
						array(20,35,50,6247),
						array(20,35,100,8217),										
						
						array(36,0xffff,5,3209),
						array(36,0xffff,10,4955),
						array(36,0xffff,15,5804),
						array(36,0xffff,20,6399),
						array(36,0xffff,30,7478),
						array(36,0xffff,50,9547),
						array(36,0xffff,100,12558),			
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,5,1378),
						array(6,9,10,2080),
						array(6,9,15,2418),
						array(6,9,20,2646),
						array(6,9,30,3069),
						array(6,9,50,3891),
						array(6,9,100,5117),

				  		array(10,19,5,1535),
						array(10,19,10,2317),
						array(10,19,15,2692),
						array(10,19,20,2947),
						array(10,19,30,3418),
						array(10,19,50,4333),
						array(10,19,100,5700),						

				  		array(20,35,5,2129),
						array(20,35,10,3273),
						array(20,35,15,3627),
						array(20,35,20,4214),
						array(20,35,30,4917),
						array(20,35,50,6268),
						array(20,35,100,8243),										
						
						array(36,0xffff,5,2939),
						array(36,0xffff,10,4642),
						array(36,0xffff,15,5475),
						array(36,0xffff,20,6077),
						array(36,0xffff,30,7151),
						array(36,0xffff,50,9168),
						array(36,0xffff,100,12067),		
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,5,1348),
						array(6,9,10,2036),
						array(6,9,15,2366),
						array(6,9,20,2590),
						array(6,9,30,3004),
						array(6,9,50,3807),
						array(6,9,100,5008),

				  		array(10,19,5,1502),
						array(10,19,10,2268),
						array(10,19,15,2635),
						array(10,19,20,2884),
						array(10,19,30,3346),
						array(10,19,50,4240),
						array(10,19,100,5577),						

				  		array(20,35,5,2211),
						array(20,35,10,3337),
						array(20,35,15,3879),
						array(20,35,20,4244),
						array(20,35,30,4923),
						array(20,35,50,6240),
						array(20,35,100,8207),										
						
						array(36,0xffff,5,3316),
						array(36,0xffff,10,5005),
						array(36,0xffff,15,5817),
						array(36,0xffff,20,6365),
						array(36,0xffff,30,7385),
						array(36,0xffff,50,9360),
						array(36,0xffff,100,12311),		
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,5,775),
						array(0,1.9999,10,1092),
						array(0,1.9999,15,1235),
						array(0,1.9999,20,1332),
						array(0,1.9999,30,1491),
						array(0,1.9999,50,1775),
						array(0,1.9999,100,2312),
						
						array(2,4.9999,5,1014),
						array(2,4.9999,10,1468),
						array(2,4.9999,15,1671),
						array(2,4.9999,20,1817),
						array(2,4.9999,30,2052),
						array(2,4.9999,50,2463),
						array(2,4.9999,100,3208),	
						
						array(5,9.9999,5,1277),
						array(5,9.9999,10,1619),
						array(5,9.9999,15,2066),
						array(5,9.9999,20,2235),
						array(5,9.9999,30,2513),
						array(5,9.9999,50,3004),
						array(5,9.9999,100,3912),	
						
						array(10,0xffff,5,1646),
						array(10,0xffff,10,2319),
						array(10,0xffff,15,2622),
						array(10,0xffff,20,2827),
						array(10,0xffff,30,3166),
						array(10,0xffff,50,3770),
						array(10,0xffff,100,4908),	
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,5,1072),
						array(0,1.9999,10,1672),
						array(0,1.9999,15,1988),
						array(0,1.9999,20,2167),
						array(0,1.9999,30,2551),
						array(0,1.9999,50,3198),
						array(0,1.9999,100,4177),
						
						array(2,4.9999,5,1728),
						array(2,4.9999,10,2691),
						array(2,4.9999,15,3167),
						array(2,4.9999,20,3486),
						array(2,4.9999,30,4105),
						array(2,4.9999,50,5145),
						array(2,4.9999,100,6719),	
						
						array(5,9.9999,5,1981),
						array(5,9.9999,10,3089),
						array(5,9.9999,15,3635),
						array(5,9.9999,20,4002),
						array(5,9.9999,30,4711),
						array(5,9.9999,50,5906),
						array(5,9.9999,100,7714),	
						
						array(10,0xffff,5,2715),
						array(10,0xffff,10,4233),
						array(10,0xffff,15,4980),
						array(10,0xffff,20,5485),
						array(10,0xffff,30,6457),
						array(10,0xffff,50,8094),
						array(10,0xffff,100,10571),	
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,1.9999,5,  233),
						array(0,1.9999,10, 328),
						array(0,1.9999,15, 371),
						array(0,1.9999,20, 400),
						array(0,1.9999,30, 447),
						array(0,1.9999,50, 533),
						array(0,1.9999,100,694),
						
						array(2,4.9999,5,  304),
						array(2,4.9999,10, 440),
						array(2,4.9999,15, 501),
						array(2,4.9999,20, 545),
						array(2,4.9999,30, 616),
						array(2,4.9999,50, 739),
						array(2,4.9999,100,962),	
						
						array(5,9.9999,5,  383),
						array(5,9.9999,10, 486),
						array(5,9.9999,15, 620),
						array(5,9.9999,20, 671),
						array(5,9.9999,30, 754),
						array(5,9.9999,50, 901),
						array(5,9.9999,100,1174),	
						
						array(10,0xffff,5,  494),
						array(10,0xffff,10, 696),
						array(10,0xffff,15, 787),
						array(10,0xffff,20, 848),
						array(10,0xffff,30, 950),
						array(10,0xffff,50, 1131),
						array(10,0xffff,100,1472),	
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,1.9999,5,  322),
						array(0,1.9999,10, 502),
						array(0,1.9999,15, 596),
						array(0,1.9999,20, 650),
						array(0,1.9999,30, 765),
						array(0,1.9999,50, 959),
						array(0,1.9999,100,1253),
						
						array(2,4.9999,5,  518),
						array(2,4.9999,10, 807),
						array(2,4.9999,15, 950),
						array(2,4.9999,20, 1046),
						array(2,4.9999,30, 1232),
						array(2,4.9999,50, 1544),
						array(2,4.9999,100,2016),	
						
						array(5,9.9999,5,  594),
						array(5,9.9999,10, 927),
						array(5,9.9999,15, 1091),
						array(5,9.9999,20, 1201),
						array(5,9.9999,30, 1413),
						array(5,9.9999,50, 1772),
						array(5,9.9999,100,2314),	
						
						array(10,0xffff,5,  815),
						array(10,0xffff,10, 1270),
						array(10,0xffff,15, 1494),
						array(10,0xffff,20, 1646),
						array(10,0xffff,30, 1937),
						array(10,0xffff,50, 2428),
						array(10,0xffff,100,3171),			
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,5,2493),
						array(1,1,10,3982),
						array(1,1,15,4738),
						array(1,1,20,5283),
						array(1,1,30,6249),
						array(1,1,50,7899),
						array(1,1,100,10316),
						
				  		array(2,2,5,2482),
						array(2,2,10,3208),
						array(2,2,15,3625),
						array(2,2,20,4009),
						array(2,2,30,4859),
						array(2,2,50,6365),
						array(2,2,100,9380),
						
				  		array(3,3,5,1141),
						array(3,3,10,1492),
						array(3,3,15,1692),
						array(3,3,20,1883),
						array(3,3,30,2290),
						array(3,3,50,3010),
						array(3,3,100,4411),
						
				  		array(4,4,5,2368),
						array(4,4,10,3793),
						array(4,4,15,4501),
						array(4,4,20,5263),
						array(4,4,30,6561),
						array(4,4,50,8294),
						array(4,4,100,10832),
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
				  		array(0,14.7,5,192),
						array(0,14.7,10,240),
						array(0,14.7,15,266),
						array(0,14.7,20,288),
						array(0,14.7,30,316),
						array(0,14.7,50,369),
						array(0,14.7,100,479),
						
				  		array(14.701,0xffff,5,524),
						array(14.701,0xffff,10,665),
						array(14.701,0xffff,15,746),
						array(14.701,0xffff,20,805),
						array(14.701,0xffff,30,888),
						array(14.701,0xffff,50,1042),
						array(14.701,0xffff,100,1358),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,5,461),
						array(0,14.7,10,576),
						array(0,14.7,15,643),
						array(0,14.7,20,691),
						array(0,14.7,30,758),
						array(0,14.7,50,885),
						array(0,14.7,100,1150),
						
				  		array(14.701,0xffff,5,756),
						array(14.701,0xffff,10,959),
						array(14.701,0xffff,15,1079),
						array(14.701,0xffff,20,1163),
						array(14.701,0xffff,30,1282),
						array(14.701,0xffff,50,1504),
						array(14.701,0xffff,100,1963),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,5,  659 ),
						array(0,0,10, 928),
						array(0,0,15, 1049),
						array(0,0,20, 1132),
						array(0,0,30, 1267),
						array(0,0,50, 1508),
						array(0,0,100,1964),
				  	),	
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,5,  911 ),
						array(0,0,10, 1422),
						array(0,0,15, 1673),
						array(0,0,20, 1842),
						array(0,0,30, 2169),
						array(0,0,50, 2719),
						array(0,0,100,3551),
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