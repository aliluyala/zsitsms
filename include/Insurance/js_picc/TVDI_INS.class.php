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
	public         $Version = 'CSX-2008(A)-JS-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,0 ,12,603,0.0143),
						array(0,5,12,24,575,0.0137),
						array(0,5,24,72,569,0.0135),
						array(0,5,72, 0xffff,586,0.0139),
						
				  		array(6,9,0 ,12,724,0.0143),
						array(6,9,12,24,689,0.0137),
						array(6,9,24,72,683,0.0135),
						array(6,9,72, 0xffff,703,0.0139),	
						
				  		array(10,0xffff,0 ,12,724,0.0143),
						array(10,0xffff,12,24,689,0.0137),
						array(10,0xffff,24,72,683,0.0135),
						array(10,0xffff,72, 0xffff,703,0.0139),						
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
						
				  		array(20,0xffff,0 ,12,461,0.0124),
						array(20,0xffff,12,24,439,0.0118),
						array(20,0xffff,24,72,434,0.0117),
						array(20,0xffff,72, 0xffff,447,0.0121),		
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,5,0 ,12,285,0.0095),
						array(0,5,12,24,272,0.0090),
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
				  		array(0,5,0 ,12,912,0.0273),
						array(0,5,12,24,903,0.027),
						array(0,5,24,72,894,0.0268),
						array(0,5,72, 0xffff,912,0.0273),
						
				  		array(6,9,0 ,12,1035,0.0214),
						array(6,9,12,24,1024,0.0212),
						array(6,9,24,72,1014,0.021),
						array(6,9,72, 0xffff,1035,0.0214),
						
				  		array(10,19,0 ,12,1067,0.0199),
						array(10,19,12,24,1056,0.0197),
						array(10,19,24,72,1045,0.0195),
						array(10,19,72, 0xffff,1067,0.0199),

				  		array(20,35,0 ,12,898,0.0171),
						array(20,35,12,24,887,0.017),
						array(20,35,24,72,878,0.0168),
						array(20,35,72, 0xffff,896,0.0171),
						
				  		array(36,0xffff,0 ,12,2583,0.0204),
						array(36,0xffff,12,24,2557,0.0202),
						array(36,0xffff,24,72,2531,0.02),
						array(36,0xffff,72, 0xffff,2883,0.0204),		
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,0 ,12,883,0.0178),
						array(6,9,12,24,874,0.0178),
						array(6,9,24,72,865,0.0174),
						array(6,9,72, 0xffff,883,0.0178),
						
				  		array(10,19,0 ,12,909,0.0165),
						array(10,19,12,24,900,0.0163),
						array(10,19,24,72,891,0.0162),
						array(10,19,72, 0xffff,909,0.0165),

				  		array(20,35,0 ,12,767,0.0142),
						array(20,35,12,24,750,0.0141),
						array(20,35,24,72,752,0.0139),
						array(20,35,72, 0xffff,767,0.0142),
						
				  		array(36,0xffff,0 ,12,2178,0.0159),
						array(36,0xffff,12,24,2156,0.0167),
						array(36,0xffff,24,72,2134,0.0166),
						array(36,0xffff,72, 0xffff,2178,0.0169),	
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,0 ,12,999,0.0206),
						array(6,9,12,24,989,0.0204),
						array(6,9,24,72,979,0.0202),
						array(6,9,72, 0xffff,999,0.0206),
						
				  		array(10,19,0 ,12,1029,0.0191),
						array(10,19,12,24,1018,0.0189),
						array(10,19,24,72,1008,0.0187),
						array(10,19,72, 0xffff,1029,0.0191),

				  		array(20,35,0 ,12,866,0.0164),
						array(20,35,12,24,857,0.0163),
						array(20,35,24,72,848,0.0161),
						array(20,35,72, 0xffff,866,0.0164),
						
				  		array(36,0xffff,0 ,12,2488,0.0196),
						array(36,0xffff,12,24,2463,0.0194),
						array(36,0xffff,24,72,2438,0.0192),
						array(36,0xffff,72, 0xffff,2488,0.0196),	
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,12,246,0.0095),
						array(0,1.9999,12,24,235,0.009),
						array(0,1.9999,24,72,232,0.0089),
						array(0,1.9999,72, 0xffff,239,0.0092),

				  		array(2,4.9999,0 ,12,318,0.0122),
						array(2,4.9999,12,24,303,0.0116),
						array(2,4.9999,24,72,300,0.0115),
						array(2,4.9999,72, 0xffff,309,0.0119),

					  	array(5,9.9999,0 ,12,348,0.0134),
						array(5,9.9999,12,24,331,0.0127),
						array(5,9.9999,24,72,328,0.0126),
						array(5,9.9999,72, 0xffff,338,0.013),
						
					  	array(10,0xffff,0 ,12,229,0.0162),
						array(10,0xffff,12,24,218,0.0155),
						array(10,0xffff,24,72,216,0.0153),
						array(10,0xffff,72, 0xffff,223,0.0158),
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,12,835,0.0193),
						array(0,1.9999,12,24,827,0.0191),
						array(0,1.9999,24,72,819,0.0189),
						array(0,1.9999,72, 0xffff,835,0.0193),

				  		array(2,4.9999,0 ,12,1179,0.0237),
						array(2,4.9999,12,24,1168,0.0235),
						array(2,4.9999,24,72,1156,0.0232),
						array(2,4.9999,72, 0xffff,1179,0.0237),

					  	array(5,9.9999,0 ,12,1321,0.0236),
						array(5,9.9999,12,24,1308,0.0234),
						array(5,9.9999,24,72,1295,0.0232),
						array(5,9.9999,72, 0xffff,1321,0.0236),
						
					  	array(10,0xffff,0 ,12,2097,0.0252),
						array(10,0xffff,12,24,2076,0.025),
						array(10,0xffff,24,72,2055,0.0247),
						array(10,0xffff,72, 0xffff,2097,0.0252),
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,1.9999,0 ,12,123.0,0.00475),
						array(0,1.9999,12,24,117.5,0.0045),
						array(0,1.9999,24,72,116.0,0.00445),
						array(0,1.9999,72, 0xffff,119.5,0.0046),

				  		array(2,4.9999,0 ,12,      159.0,0.0061),
						array(2,4.9999,12,24,      151.5,0.0058),
						array(2,4.9999,24,72,      150.0,0.00575),
						array(2,4.9999,72, 0xffff, 154.5,0.00595),
                                                 
					  	array(5,9.9999,0 ,12,      174.0,0.0067),
						array(5,9.9999,12,24,      165.5,0.00635),
						array(5,9.9999,24,72,      164.0,0.0063),
						array(5,9.9999,72, 0xffff, 169.0,0.0065),
						                         
					  	array(10,0xffff,0 ,12,     114.5,0.0081),
						array(10,0xffff,12,24,     109.0,0.00775),
						array(10,0xffff,24,72,     108.0,0.00765),
						array(10,0xffff,72, 0xffff,111.5,0.0079),
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,1.9999,0 ,12,      417.5,0.00965),
						array(0,1.9999,12,24,      413.5,0.00955),
						array(0,1.9999,24,72,      409.5,0.00945),
						array(0,1.9999,72, 0xffff, 417.5,0.00965),
                                                 
				  		array(2,4.9999,0 ,12,      589.5,0.01185),
						array(2,4.9999,12,24,      584.0,0.01175),
						array(2,4.9999,24,72,      578.0,0.0116),
						array(2,4.9999,72, 0xffff, 589.5,0.01185),
                                                 
					  	array(5,9.9999,0 ,12,      660.5,0.0118),
						array(5,9.9999,12,24,      654.0,0.0117),
						array(5,9.9999,24,72,      647.5,0.0116),
						array(5,9.9999,72, 0xffff, 660.5,0.0118),
						                         
					  	array(10,0xffff,0 ,12,     1048.5,0.0126),
						array(10,0xffff,12,24,     1038.0,0.0125),
						array(10,0xffff,24,72,     1027.5,0.01235),
						array(10,0xffff,72, 0xffff,1048.5,0.0126),
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,0 ,12,1179,0.0237),
						array(1,1,12,24,1188,0.0235),
						array(1,1,24,72,1156,0.0232),
						array(1,1,72, 0xffff,1179,0.0237),

				  		array(2,2,0 ,12,418,0.0078),
						array(2,2,12,24,414,0.0077),
						array(2,2,24,72,410,0.0076),
						array(2,2,72, 0xffff,418,0.0078),

					  	array(3,3,0 ,12,362,0.0068),
						array(3,3,12,24,358,0.0067),
						array(3,3,24,72,354,0.0066),
						array(3,3,72, 0xffff,362,0.0068),
						
					  	array(4,4,0 ,12,917,0.0172),
						array(4,4,12,24,908,0.017),
						array(4,4,24,72,899,0.0168),
						array(4,4,72, 0xffff,917,0.0172),
				  	),	
				  	'MOTORCYCLE' => array(								
				  		array(0,50,0,1,15,0.021),
				  		array(50.0001,250,0,1,21,0.0276),
				  		array(250.0001,0xffff,0,1,30,0.0415),
				  	),	
				  	'DUAL_PURPOSE_TRACTOR' => array(								
				  		array(0,14.7,0,1,33,0.0071),
				  		array(14.701,0xffff,0,1,78,0.0169),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,0,1,56,0.0122),
				  		array(14.7,0xffff,0,1,81,0.0178),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0 ,12,210,0.0081),
						array(0,0,12,24,200,0.0077),
						array(0,0,24,72,198,0.0076),
						array(0,0,72, 0xffff,204,0.0078),
				  	),	
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0 ,12,710,0.0164),
						array(0,0,12,24,703,0.0162),
						array(0,0,24,72,698,0.0161),
						array(0,0,72, 0xffff,710,0.0164),
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