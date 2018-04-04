<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         TVDI_INS.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 机动车车辆损失险保费计算.
 * 
 **/ 
 
class TVDI_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'CSX-2009(c)-HB-CPIC';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,0 ,12,593,0.0141),
						array(0,5,12,48,559,0.0133),
						array(0,5,48,72,564,0.0134),
						array(0,5,72,96,576,0.0137),
						array(0,5,96, 0xffff,582,0.0138),
						
				  		array(6,9,0 ,12,711,0.0141),
						array(6,9,12,48,670,0.0133),
						array(6,9,48,72,677,0.0134),
						array(6,9,72,96,691,0.0137),
						array(6,9,96, 0xffff,698,0.0138),	
						
				  		array(10,0xffff,0 ,12,711,0.0141),
						array(10,0xffff,12,48,670,0.0133),
						array(10,0xffff,48,72,677,0.0134),
						array(10,0xffff,72,96,691,0.0137),
						array(10,0xffff,96, 0xffff,698,0.0138),						
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,5,0 ,12,328,0.0109),
						array(0,5,12,48,309,0.0102),
						array(0,5,48,72,313,0.0103),
						array(0,5,72,96,319,0.0106),
						array(0,5,96, 0xffff,322,0.0107),
						
				  		array(6,9,0 ,12,394,0.0103),
						array(6,9,12,48,371,0.0097),
						array(6,9,48,72,375,0.0098),
						array(6,9,72,96,383,0.01),
						array(6,9,96, 0xffff,387,0.0101),
						
				  		array(10,19,0 ,12,394,0.0111),
						array(10,19,12,48,371,0.0105),
						array(10,19,48,72,375,0.0106),
						array(10,19,72,96,383,0.0108),
						array(10,19,96, 0xffff,387,0.0109),
						
				  		array(20,0xffff,0 ,12,410,0.0111),
						array(20,0xffff,12,48,387,0.0105),
						array(20,0xffff,48,72,391,0.0106),
						array(20,0xffff,72,96,399,0.0108),	
						array(20,0xffff,96, 0xffff,403,0.0109),		
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,5,0 ,12,254,0.0084),
						array(0,5,12,48,240,0.0080),
						array(0,5,48,72,242,0.0080),
						array(0,5,72,96,247,0.0082),
						array(0,5,96, 0xffff,249,0.0083),
						
				  		array(6,9,0 ,12,305,0.008),
						array(6,9,12,48,288,0.0076),
						array(6,9,48,72,290,0.0076),
						array(6,9,72,96,296,0.0078),
						array(6,9,96, 0xffff,299,0.0079),
						
				  		array(10,19,0 ,12,305,0.0084),
						array(10,19,12,48,288,0.008),
						array(10,19,48,72,290,0.008),
						array(10,19,72,96,296,0.0082),
						array(10,19,96, 0xffff,299,0.0083),
						
				  		array(20,0xffff,0 ,12,318,0.0084),
						array(20,0xffff,12,48,300,0.008),
						array(20,0xffff,48,72,303,0.008),
						array(20,0xffff,72,96,309,0.0082),
						array(20,0xffff,96, 0xffff,312,0.0083),		
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,5,0 ,12,994,0.0305),
						array(0,5,12,48,984,0.0302),
						array(0,5,48,72,994,0.0305),
						array(0,5,72,96,994,0.0305),
						array(0,5,96, 0xffff,994,0.0305),
						
				  		array(6,9,0 ,12,1089,0.0225),
						array(6,9,12,48,1078,0.0223),
						array(6,9,48,72,1089,0.0225),
						array(6,9,72,96,1089,0.0225),
						array(6,9,96, 0xffff,1089,0.0225),
						
				  		array(10,19,0 ,12,1097,0.02),
						array(10,19,12,48,1086,0.0198),
						array(10,19,48,72,1097,0.02),
						array(10,19,72,96,1097,0.02),
						array(10,19,96, 0xffff,1097,0.02),

				  		array(20,35,0 ,12,991,0.0197),
						array(20,35,12,48,981,0.0195),
						array(20,35,48,72,991,0.0197),
						array(20,35,72,96,991,0.0197),
						array(20,35,96, 0xffff,991,0.0197),
						
				  		array(36,0xffff,0 ,12,2842,0.0227),
						array(36,0xffff,12,48,2814,0.0225),
						array(36,0xffff,48,72,2842,0.0227),
						array(36,0xffff,72,96,2842,0.0227),
						array(36,0xffff,96, 0xffff,2842,0.0227),		
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,0 ,12,927,0.0187),
						array(6,9,12,48,918,0.0185),
						array(6,9,48,72,927,0.0187),
						array(6,9,72,96,927,0.0187),
						array(6,9,96, 0xffff,927,0.0187),
						
				  		array(10,19,0 ,12,935,0.0166),
						array(10,19,12,48,925,0.0164),
						array(10,19,48,72,935,0.0166),
						array(10,19,72,96,935,0.0166),
						array(10,19,96, 0xffff,935,0.0166),

				  		array(20,35,0 ,12,847,0.0163),
						array(20,35,12,48,838,0.0161),
						array(20,35,48,72,847,0.0163),
						array(20,35,72,96,847,0.0163),
						array(20,35,96, 0xffff,847,0.0163),
						
				  		array(36,0xffff,0 ,12,2393,0.0189),
						array(36,0xffff,12,48,2369,0.0187),
						array(36,0xffff,48,72,2393,0.0189),
						array(36,0xffff,72,96,2393,0.0189),
						array(36,0xffff,96, 0xffff,2393,0.0189),	
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,0 ,12,1051,0.0216),
						array(6,9,12,48,1040,0.0214),
						array(6,9,48,72,1051,0.0216),
						array(6,9,72,96,1051,0.0216),
						array(6,9,96, 0xffff,1051,0.0216),
						
				  		array(10,19,0 ,12,1059,0.0192),
						array(10,19,12,48,1048,0.019),
						array(10,19,48,72,1059,0.0192),
						array(10,19,72,96,1059,0.0192),
						array(10,19,96, 0xffff,1059,0.0192),

				  		array(20,35,0 ,12,957,0.0189),
						array(20,35,12,48,948,0.0187),
						array(20,35,48,72,957,0.0189),
						array(20,35,72,96,957,0.0189),
						array(20,35,96, 0xffff,957,0.0189),
						
				  		array(36,0xffff,0 ,12,2737,0.0218),
						array(36,0xffff,12,48,2709,0.0216),
						array(36,0xffff,48,72,2737,0.0218),
						array(36,0xffff,72,96,2737,0.0218),
						array(36,0xffff,96, 0xffff,2737,0.0218),	
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,12,236,0.0091),
						array(0,1.9999,12,48,223,0.0086),
						array(0,1.9999,48,72,225,0.0086),
						array(0,1.9999,72,96,230,0.0086),
						array(0,1.9999,96, 0xffff,230,0.0088),

				  		array(2,4.9999,0 ,12,305,0.0117),
						array(2,4.9999,12,48,287,0.011),
						array(2,4.9999,48,72,290,0.0112),
						array(2,4.9999,72,96,296,0.0114),
						array(2,4.9999,96, 0xffff,299,0.0115),

					  	array(5,9.9999,0 ,12,333,0.0128),
						array(5,9.9999,12,48,314,0.0121),
						array(5,9.9999,48,72,317,0.0122),
						array(5,9.9999,72,96,324,0.0124),
						array(5,9.9999,96, 0xffff,327,0.0126),
						
					  	array(10,0xffff,0 ,12,220,0.0156),
						array(10,0xffff,12,48,207,0.0147),
						array(10,0xffff,48,72,209,0.0148),
						array(10,0xffff,72,96,213,0.0151),
						array(10,0xffff,96, 0xffff,216,0.0153),
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,12,1033,0.0251),
						array(0,1.9999,12,48,1023,0.0248),
						array(0,1.9999,48,72,1033,0.0251),
						array(0,1.9999,72,96,1033,0.0251),
						array(0,1.9999,96, 0xffff,1033,0.0251),

				  		array(2,4.9999,0 ,12,1155,0.0228),
						array(2,4.9999,12,48,1143,0.0225),
						array(2,4.9999,48,72,1155,0.0228),
						array(2,4.9999,72,96,1155,0.0228),
						array(2,4.9999,96, 0xffff,1155,0.0228),

					  	array(5,9.9999,0 ,12,1357,0.0235),
						array(5,9.9999,12,48,1343,0.0233),
						array(5,9.9999,48,72,1357,0.0235),
						array(5,9.9999,72,96,1357,0.0235),
						array(5,9.9999,96, 0xffff,1357,0.0235),
						
					  	array(10,0xffff,0 ,12,2170,0.0262),
						array(10,0xffff,12,48,2148,0.026),
						array(10,0xffff,48,72,2170,0.0262),
						array(10,0xffff,72,96,2170,0.0262),
						array(10,0xffff,96, 0xffff,2170,0.0262),
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,1.9999,0 ,12,118,0.0046),
						array(0,1.9999,12,48,112,0.0043),
						array(0,1.9999,48,72,113,0.0043),
						array(0,1.9999,72,96,115,0.0044),
						array(0,1.9999,96, 0xffff,116,0.0045),

				  		array(2,4.9999,0 ,12,153,0.0059),
						array(2,4.9999,12,48,144,0.0055),
						array(2,4.9999,48,72,145,0.0056),
						array(2,4.9999,72,96,148,0.0057),
						array(2,4.9999,96, 0xffff,150,0.0058),

					  	array(5,9.9999,0 ,12,167,0.0064),
						array(5,9.9999,12,48,157,0.0061),
						array(5,9.9999,48,72,159,0.0061),
						array(5,9.9999,72,96,162,0.0062),
						array(5,9.9999,96, 0xffff,164,0.0063),
						
					  	array(10,0xffff,0 ,12,110,0.0078),
						array(10,0xffff,12,48,104,0.0074),
						array(10,0xffff,48,72,105,0.0074),
						array(10,0xffff,72,96,107,0.0076),
						array(10,0xffff,96, 0xffff,108,0.0077),
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,1.9999,0 ,12,517,0.0126),
						array(0,1.9999,12,48,512,0.0124),
						array(0,1.9999,48,72,517,0.0126),
						array(0,1.9999,72,96,517,0.0126),
						array(0,1.9999,96, 0xffff,517,0.0126),

				  		array(2,4.9999,0 ,12,578,0.0114),
						array(2,4.9999,12,48,572,0.0113),
						array(2,4.9999,48,72,578,0.0114),
						array(2,4.9999,72,96,578,0.0114),
						array(2,4.9999,96, 0xffff,578,0.0114),

					  	array(5,9.9999,0 ,12,679,0.0118),
						array(5,9.9999,12,48,672,0.0117),
						array(5,9.9999,48,72,679,0.0118),
						array(5,9.9999,72,96,679,0.0118),
						array(5,9.9999,96, 0xffff,679,0.0118),
						
					  	array(10,0xffff,0 ,12,1085,0.0131),
						array(10,0xffff,12,48,1074,0.013),
						array(10,0xffff,48,72,1085,0.0131),
						array(10,0xffff,72,96,1085,0.0131),
						array(10,0xffff,96, 0xffff,1085,0.0131),
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,0 ,12,1155,0.0228),
						array(1,1,12,48,1143,0.0225),
						array(1,1,48,72,1155,0.0228),
						array(1,1,72,96,1155,0.0228),
						array(1,1,96, 0xffff,1155,0.0228),

				  		array(2,2,0 ,12,555,0.0103),
						array(2,2,12,48,549,0.0102),
						array(2,2,48,72,555,0.0103),
						array(2,2,72,96,555,0.0103),
						array(2,2,96, 0xffff,555,0.0103),

					  	array(3,3,0 ,12,480,0.009),
						array(3,3,12,48,475,0.0089),
						array(3,3,48,72,480,0.009),
						array(3,3,72,96,480,0.009),
						array(3,3,96, 0xffff,480,0.009),
						
					  	array(4,4,0 ,12,1217,0.0228),
						array(4,4,12,48,1205,0.0226),
						array(4,4,48,72,1217,0.0228),
						array(4,4,72,96,1217,0.0228),
						array(4,4,96, 0xffff,1217,0.0228),
				  	),	
				  	'MOTORCYCLE' => array(								
				  		array(0,50,0,1,15,0.0209),
				  		array(50.0001,250,0,1,21,0.0276),
				  		array(250.0001,0xffff,0,1,30,0.0414),
				  	),	
				  	'DUAL_PURPOSE_TRACTOR' => array(								
				  		array(0,14.7,0,1,41,0.0089),
				  		array(14.701,0xffff,0,1,97,0.0211),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,0,1,70,0.0153),
				  		array(14.7,0xffff,0,1,101,0.0223),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0 ,12,201,0.0077),
						array(0,0,12,48,189,0.0073),
						array(0,0,48,72,191,0.0074),
						array(0,0,72,96,195,0.0075),
						array(0,0,96, 0xffff,197,0.0076),
				  	),	
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0 ,12,878,0.0213),
						array(0,0,12,48,869,0.0211),
						array(0,0,48,72,878,0.0213),
						array(0,0,72,96,878,0.0213),
						array(0,0,96, 0xffff,878,0.0213),
				  	),									
				  );	
	/**
	  * 可选免赔额特约条款拆扣
      **/	  
	private       $DOCRate = array(
						300 => array(
							array(0,5,0.92),
							array(5,10,0.94),
							array(10,20,0.95),
							array(20,30,0.96),
							array(30,50,0.97),
							array(50,0xffff,0.98),
						),
						500 => array(
							array(0,5,0.84),
							array(5,10,0.89),
							array(10,20,0.92),
							array(20,30,0.94),
							array(30,50,0.96),
							array(50,0xffff,0.97),
						),		
						1000 => array(
							array(0,5,0.75),
							array(5,10,0.82),
							array(10,20,0.87),
							array(20,30,0.89),
							array(30,50,0.91),
							array(50,0xffff,0.94),
						),		
						2000 => array(
							array(0,5,0.54),
							array(5,10,0.58),
							array(10,20,0.7),
							array(20,30,0.78),
							array(30,50,0.86),
							array(50,0xffff,0.89),
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
	 * 			SEATS               座位数.
	 * 			LOAD                载重(吨).
	 * 			ENGINE              发动机排量(cc).
	 * 			POWER               发动机功率(KW).
	 * 			TYPE_SPECIAL_AUTO   特种车分类(1,2,3,4).
	 * 			MONTHS              保险期间(月份数,1-12).
	 *          COTY                车龄.
	 *          DOC_AMOUNT          可选免赔额(元,300,500,1000,2000).
	 *          PURCHASE_PRICE      新车购置价(元).   
	 *          
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
		
		$insAmount = 0;
		
		$purchasePrice = 0;
		if(array_key_exists('PURCHASE_PRICE',$params))
		{
			$purchasePrice = $params['PURCHASE_PRICE']/10000;
			$insAmount = $params['PURCHASE_PRICE'];
		}
		
		
		$docAmount = 0;
		if(array_key_exists('DOC_AMOUNT',$params))
		{
			$docAmount = $params['DOC_AMOUNT'];		
		}
		
		
		$cost = 0;
		$sl = 0;
		$coty = 0;
		if(array_key_exists('COTY',$params))
		{
			$coty = $params['COTY'];
		}
		
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
			if($row[0] <= $sl && $sl <= $row[1] && $row[2] <= $coty && $coty < $row[3])
			{
				$cost = $row[4] + $insAmount*$row[5] ;
				break;
			}
		}
		
		$docR = 1;
		if($docAmount > 0 && array_key_exists($docAmount,$this->DOCRate))
		{
			foreach($this->DOCRate[$docAmount] as $row)
			{
				if($row[0] <= $purchasePrice && $purchasePrice < $row[1])
				{
					$docR = $row[2];
					break;
				}
			}
		}
		
		return round($cost * $docR * $this->ShortRate[$months],2) ;		
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