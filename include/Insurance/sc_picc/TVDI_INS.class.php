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
	public         $Version = 'CSX-2009(A)-SC-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,0 ,12,566,0.0135),
						array(0,5,12,24,539,0.0128),
						array(0,5,24,72,533,0.0127),
						array(0,5,72, 0xffff,549,0.0131),
						
				  		array(6,9,0 ,12,679,0.0135),
						array(6,9,12,24,646,0.0128),
						array(6,9,24,72,640,0.0127),
						array(6,9,72, 0xffff,659,0.0131),	
						
				  		array(10,0xffff,0 ,12,679,0.0135),
						array(10,0xffff,12,24,646,0.0128),
						array(10,0xffff,24,72,640,0.0127),
						array(10,0xffff,72, 0xffff,659,0.0131),						
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,5,0 ,12,368,0.0122),
						array(0,5,12,24,351,0.0116),
						array(0,5,24,72,347,0.0115),
						array(0,5,72, 0xffff,358,0.0118),
						
				  		array(6,9,0 ,12,442,0.0116),
						array(6,9,12,24,421,0.0110),
						array(6,9,24,72,417,0.0109),
						array(6,9,72, 0xffff,430,0.0113),
						
				  		array(10,19,0 ,12,442,0.0124),
						array(10,19,12,24,421,0.0118),
						array(10,19,24,72,417,0.0117),
						array(10,19,72, 0xffff,430,0.0121),
						
				  		array(20,0xffff,0,1,461,0.0124),
						array(20,0xffff,1,2,439,0.0128),
						array(20,0xffff,2,6,434,0.0127),
						array(20,0xffff,6,0xffff,447,0.0121),		
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,5,0 ,12,285,0.0095),
						array(0,5,12,24,272,0.009),
						array(0,5,24,72,269,0.0089),
						array(0,5,72, 0xffff,277,0.0092),
						
				  		array(6,9,0 ,12,342,0.009),
						array(6,9,12,24,326,0.0086),
						array(6,9,24,72,323,0.0085),
						array(6,9,72, 0xffff,333,0.0087),
						
				  		array(10,19,0 ,12,342,0.0095),
						array(10,19,12,24,326,0.009),
						array(10,19,24,72,323,0.0089),
						array(10,19,72, 0xffff,333,0.0092),
						
				  		array(20,0xffff,0 ,12,357,0.0095),
						array(20,0xffff,12,24,340,0.009),
						array(20,0xffff,24,72,336,0.0089),
						array(20,0xffff,72, 0xffff,346,0.0092),	
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,5,0 ,12,970,0.0295),
						array(0,5,12,24,960,0.029),
						array(0,5,24,72,951,0.0287),
						array(0,5,72, 0xffff,970,0.0293),
						
				  		array(6,9,0 ,12,1058,0.022),
						array(6,9,12,24,1048,0.0218),
						array(6,9,24,72,1037,0.0216),
						array(6,9,72, 0xffff,1058,0.022),
						
				  		array(10,19,0 ,12,1102,0.0207),
						array(10,19,12,24,1091,0.0205),
						array(10,19,24,72,1080,0.0203),
						array(10,19,72, 0xffff,1102,0.0207),

				  		array(20,35,0 ,12,979,0.0197),
						array(20,35,12,24,969,0.0195),
						array(20,35,24,72,959,0.0193),
						array(20,35,72, 0xffff,979,0.0197),
						
				  		array(36,0xffff,0 ,12,2867,0.0222),
						array(36,0xffff,12,24,2838,0.022),
						array(36,0xffff,24,72,2810,0.0218),
						array(36,0xffff,72, 0xffff,2867,0.0222),			
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,0 ,12,902,0.0183),
						array(6,9,12,24,893,0.0181),
						array(6,9,24,72,884,0.0179),
						array(6,9,72, 0xffff,902,0.0183),
						
				  		array(10,19,0 ,12,938,0.0172),
						array(10,19,12,24,929,0.0170),
						array(10,19,24,72,919,0.0169),
						array(10,19,72, 0xffff,938,0.0172),

				  		array(20,35,0 ,12,836,0.0163),
						array(20,35,12,24,828,0.0162),
						array(20,35,24,72,820,0.0160),
						array(20,35,72, 0xffff,836,0.0163),
						
				  		array(36,0xffff,0 ,12,2414,0.0185),
						array(36,0xffff,12,24,2389,0.0183),
						array(36,0xffff,24,72,2365,0.0181),
						array(36,0xffff,72, 0xffff,2414,0.0185),	
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,0 ,12,1022,0.0212),
						array(6,9,12,24,1012,0.0209),
						array(6,9,24,72,1001,0.0207),
						array(6,9,72, 0xffff,1022,0.0212),
						
				  		array(10,19,0 ,12,1063,0.0199),
						array(10,19,12,24,1053,0.0197),
						array(10,19,24,72,1042,0.0195),
						array(10,19,72, 0xffff,1063,0.0199),

				  		array(20,35,0 ,12,945,0.0189),
						array(20,35,12,24,936,0.0187),
						array(20,35,24,72,927,0.0185),
						array(20,35,72, 0xffff,945,0.0189),
						
				  		array(36,0xffff,0 ,12,2760,0.0213),
						array(36,0xffff,12,24,2733,0.0211),
						array(36,0xffff,24,72,2705,0.0209),
						array(36,0xffff,72, 0xffff,2760,0.0213),	
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,12,249,0.0096),
						array(0,1.9999,12,24,237,0.0091),
						array(0,1.9999,24,72,235,0.009),
						array(0,1.9999,72, 0xffff,242,0.0093),

				  		array(2,4.9999,0 ,12,321,0.0123),
						array(2,4.9999,12,24,306,0.0118),
						array(2,4.9999,24,72,303,0.0116),
						array(2,4.9999,72, 0xffff,312,0.012),

					  	array(5,9.9999,0 ,12,351,0.0135),
						array(5,9.9999,12,24,334,0.0129),
						array(5,9.9999,24,72,331,0.0127),
						array(5,9.9999,72, 0xffff,341,0.0131),
						
					  	array(10,0xffff,0 ,12,231,0.0164),
						array(10,0xffff,12,24,220,0.0156),
						array(10,0xffff,24,72,218,0.0155),
						array(10,0xffff,72, 0xffff,225,0.0159),
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,12,824,0.0189),
						array(0,1.9999,12,24,815,0.0187),
						array(0,1.9999,24,72,807,0.0185),
						array(0,1.9999,72, 0xffff,824,0.0189),

				  		array(2,4.9999,0 ,12,1009,0.0195),
						array(2,4.9999,12,24,999,0.0193),
						array(2,4.9999,24,72,989,0.0191),
						array(2,4.9999,72, 0xffff,1009,0.0195),

					  	array(5,9.9999,0 ,12,1184,0.0202),
						array(5,9.9999,12,24,1172,0.02),
						array(5,9.9999,24,72,1160,0.0198),
						array(5,9.9999,72, 0xffff,1184,0.0202),
						
					  	array(10,0xffff,0 ,12,1987,0.0232),
						array(10,0xffff,12,24,1967,0.023),
						array(10,0xffff,24,72,1947,0.0228),
						array(10,0xffff,72, 0xffff,1987,0.0232),
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,1.9999,0 ,12,     124.5,0.00480),
						array(0,1.9999,12,24,     118.5,0.00455),
						array(0,1.9999,24,72,     117.5,0.00450),
						array(0,1.9999,72, 0xffff,121.0,0.00465),

				  		array(2,4.9999,0 ,12,      160.5,0.00615),
						array(2,4.9999,12,24,      153.0,0.00590),
						array(2,4.9999,24,72,      151.5,0.00580),
						array(2,4.9999,72, 0xffff, 156.0,0.00600),
                                                 
					  	array(5,9.9999,0 ,12,      175.5,0.00675),
						array(5,9.9999,12,24,      167.0,0.00645),
						array(5,9.9999,24,72,      165.5,0.00635),
						array(5,9.9999,72, 0xffff, 170.5,0.00655),
						                         
					  	array(10,0xffff,0 ,12,     115.5,0.00820),
						array(10,0xffff,12,24,     110.0,0.00780),
						array(10,0xffff,24,72,     109.0,0.00775),
						array(10,0xffff,72, 0xffff,112.5,0.00795),
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,1.9999,0 ,12,      412.0,0.00945),
						array(0,1.9999,12,24,      407.5,0.00935),
						array(0,1.9999,24,72,      403.5,0.00925),
						array(0,1.9999,72, 0xffff, 412.0,0.00945),
                                                 
				  		array(2,4.9999,0 ,12,      504.5,0.00975),
						array(2,4.9999,12,24,      499.5,0.00965),
						array(2,4.9999,24,72,      494.5,0.00955),
						array(2,4.9999,72, 0xffff, 504.5,0.00975),
                                                 
					  	array(5,9.9999,0 ,12,      592.0,0.01010),
						array(5,9.9999,12,24,      586.0,0.01000),
						array(5,9.9999,24,72,      580.0,0.00990),
						array(5,9.9999,72, 0xffff, 592.0,0.01010),
						                         
					  	array(10,0xffff,0 ,12,     993.5,0.01160),
						array(10,0xffff,12,24,     983.5,0.01150),
						array(10,0xffff,24,72,     973.5,0.01140),
						array(10,0xffff,72, 0xffff,993.5,0.01160),
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,0 ,12,1009,0.0195),
						array(1,1,12,24,999,0.0193),
						array(1,1,24,72,989,0.0191),
						array(1,1,72, 0xffff,1009,0.0195),

				  		array(2,2,0 ,12,501,0.0093),
						array(2,2,12,24,496,0.0092),
						array(2,2,24,72,491,0.0091),
						array(2,2,72, 0xffff,501,0.0093),

					  	array(3,3,0 ,12,433,0.0081),
						array(3,3,12,24,429,0.008),
						array(3,3,24,72,425,0.0079),
						array(3,3,72, 0xffff,433,0.0081),
						
					  	array(4,4,0 ,12,1099,0.0206),
						array(4,4,12,24,1088,0.0204),
						array(4,4,24,72,1077,0.0202),
						array(4,4,72, 0xffff,1099,0.0206),
				  	),	
				  	'MOTORCYCLE' => array(								
				  		array(0,50,0,1,15,0.0209),
				  		array(50.0001,250,0,1,21,0.0275),
				  		array(250.0001,0xffff,0,1,30,0.0413),
				  	),	
				  	'DUAL_PURPOSE_TRACTOR' => array(								
				  		array(0,14.7,0,1,21,0.0046),
				  		array(14.701,0xffff,0,1,50,0.0109),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,0,1,36,0.0079),
				  		array(14.7,0xffff,0,1,52,0.0115),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0 ,12,212,0.0081),
						array(0,0,12,24,202,0.0077),
						array(0,0,24,72,200,0.0077),
						array(0,0,72, 0xffff,206,0.0079),
				  	),	
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0 ,12,700,0.016),
						array(0,0,12,24,693,0.0159),
						array(0,0,24,72,686,0.0157),
						array(0,0,72, 0xffff,700,0.016),
				  	),									
				  );	
	/**
	  * 可选免赔额特约条款拆扣
      **/	  
	private       $DOCRate = array(
						300 => array(
							array(0,5,0.89),
							array(5,10,0.92),
							array(10,20,0.94),
							array(20,30,0.96),
							array(30,50,0.97),
							array(50,0xffff,0.98),
						),
						500 => array(
							array(0,5,0.79),
							array(5,10,0.85),
							array(10,20,0.89),
							array(20,30,0.93),
							array(30,50,0.95),
							array(50,0xffff,0.96),
						),		
						1000 => array(
							array(0,5,0.68),
							array(5,10,0.74),
							array(10,20,0.84),
							array(20,30,0.88),
							array(30,50,0.9),
							array(50,0xffff,0.93),
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
	 *          COTY                车龄(月).
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