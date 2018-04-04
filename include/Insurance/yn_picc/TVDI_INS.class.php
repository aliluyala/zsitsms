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
	public         $Version = 'CSX-2009(A)-YN-PICCP&C';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,0 ,12,619,0.0147),
						array(0,5,12,24,590,0.0140),
						array(0,5,24,72,584,0.0139),
						array(0,5,72, 0xffff,602,0.0143),
						
				  		array(6,9,0 ,12,743,0.0147),
						array(6,9,12,24,708,0.0140),
						array(6,9,24,72,701,0.0139),
						array(6,9,72, 0xffff,722,0.0143),	
						
				  		array(10,0xffff,0 ,12,743,0.0147),
						array(10,0xffff,12,24,708,0.0140),
						array(10,0xffff,24,72,701,0.0139),
						array(10,0xffff,72, 0xffff,722,0.0143),						
				  	),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,5,0 ,12,409,0.0135),
						array(0,5,12,24,389,0.0129),
						array(0,5,24,72,385,0.0128),
						array(0,5,72, 0xffff,397,0.0131),
						
				  		array(6,9,0 ,12,490,0.0129),
						array(6,9,12,24,467,0.0122),
						array(6,9,24,72,462,0.0121),
						array(6,9,72, 0xffff,476,0.0125),
						
				  		array(10,19,0 ,12,490,0.0138),
						array(10,19,12,24,467,0.0131),
						array(10,19,24,72,462,0.0130),
						array(10,19,72, 0xffff,476,0.0134),
						
				  		array(20,0xffff,0,1,511,0.0138),
						array(20,0xffff,1,2,486,0.0131),
						array(20,0xffff,2,6,482,0.0130),
						array(20,0xffff,6,0xffff,496,0.0134),		
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,5,0 ,12,316,0.0105),
						array(0,5,12,24,301,0.0100),
						array(0,5,24,72,298,0.0099),
						array(0,5,72, 0xffff,307,0.0102),
						
				  		array(6,9,0 ,12,380,0.0100),
						array(6,9,12,24,362,0.0095),
						array(6,9,24,72,358,0.0094),
						array(6,9,72, 0xffff,369,0.0097),
						
				  		array(10,19,0 ,12,380,0.0105),
						array(10,19,12,24,362,0.0100),
						array(10,19,24,72,358,0.0099),
						array(10,19,72, 0xffff,369,0.0102),
						
				  		array(20,0xffff,0 ,12,396,0.0105),
						array(20,0xffff,12,24,377,0.0100),
						array(20,0xffff,24,72,373,0.0099),
						array(20,0xffff,72, 0xffff,384,0.0102),	
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,5,0 ,24,1110,0.0361),
						array(0,5,24,36,1099,0.0357),
						array(0,5,36,48,1088,0.0354),
						array(0,5,48,   0xffff,1110,0.0361),
						
				  		array(6,9,0 ,24,1308,0.0269),
						array(6,9,24,36,1294,0.0266),
						array(6,9,36,48,1281,0.0264),
						array(6,9,48,   0xffff,1308,0.0269),
						
				  		array(10,19,0 ,24,1293,0.0228),
						array(10,19,24,36,1280,0.0226),
						array(10,19,36,48,1267,0.0224),
						array(10,19,48,   0xffff,1293,0.0228),

				  		array(20,35,0 ,24,1153,0.0224),
						array(20,35,24,36,1142,0.0222),
						array(20,35,36,48,1130,0.0220),
						array(20,35,48,   0xffff,1153,0.0224),
						
				  		array(36,0xffff,0 ,24,3146,0.0270),
						array(36,0xffff,24,36,3115,0.0267),
						array(36,0xffff,36,48,3083,0.0265),
						array(36,0xffff,48,   0xffff,3146,0.0270),			
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(6,9,0 ,24,1109,0.0223),
						array(6,9,24,36,1098,0.0221),
						array(6,9,36,48,1087,0.0219),
						array(6,9,48,   0xffff,1109,0.0223),
						
				  		array(10,19,0 ,24,1097,0.0190),
						array(10,19,24,36,1086,0.0188),
						array(10,19,36,48,1075,0.0186),
						array(10,19,48,   0xffff,1097,0.0190),

				  		array(20,35,0 ,24,981,0.0186),
						array(20,35,24,36,971,0.0184),
						array(20,35,36,48,961,0.0182),
						array(20,35,48,   0xffff,981,0.0186),
						
				  		array(36,0xffff,0 ,24,2646,0.0224),
						array(36,0xffff,24,36,2619,0.0222),
						array(36,0xffff,36,48,2593,0.0220),
						array(36,0xffff,48,   0xffff,2646,0.0224),	
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(6,9,0 ,24,1261,0.0258),
						array(6,9,24,36,1248,0.0256),
						array(6,9,36,48,1236,0.0253),
						array(6,9,48,   0xffff,1261,0.0258),
						
				  		array(10,19,0 ,24,1246,0.0219),
						array(10,19,24,36,1234,0.0217),
						array(10,19,36,48,1222,0.0215),
						array(10,19,48,   0xffff,1246,0.0219),

				  		array(20,35,0 ,24,1113,0.0215),
						array(20,35,24,36,1102,0.0213),
						array(20,35,36,48,1090,0.0211),
						array(20,35,48,   0xffff,1113,0.0215),
						
				  		array(36,0xffff,0 ,24,3029,0.0259),
						array(36,0xffff,24,36,2998,0.0257),
						array(36,0xffff,36,48,2968,0.0264),
						array(36,0xffff,48,   0xffff,3029,0.0259),	
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,12,290,0.0111),
						array(0,1.9999,12,24,276,0.0106),
						array(0,1.9999,24,72,273,0.0105),
						array(0,1.9999,72,   0xffff,281,0.0108),

				  		array(2,4.9999,0 ,12,374,0.0144),
						array(2,4.9999,12,24,356,0.0137),
						array(2,4.9999,24,72,352,0.0135),
						array(2,4.9999,72, 0xffff,363,0.0140),

					  	array(5,9.9999,0 ,12,408,0.0157),
						array(5,9.9999,12,24,389,0.0149),
						array(5,9.9999,24,72,385,0.0148),
						array(5,9.9999,72, 0xffff,397,0.0152),
						
					  	array(10,0xffff,0 ,12,269,0.0191),
						array(10,0xffff,12,24,256,0.0182),
						array(10,0xffff,24,72,254,0.0180),
						array(10,0xffff,72, 0xffff,262,0.0185),
				  	),
				  	'OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,24,981,0.0228),
						array(0,1.9999,24,36,971,0.0226),
						array(0,1.9999,36,48,962,0.0224),
						array(0,1.9999,48,   0xffff,981,0.0228),

				  		array(2,4.9999,0 ,24,1318,0.0267),
						array(2,4.9999,24,36,1305,0.0264),
						array(2,4.9999,36,48,1292,0.0262),
						array(2,4.9999,48,   0xffff,1318,0.0267),

					  	array(5,9.9999,0 ,24,1565,0.0275),
						array(5,9.9999,24,36,1549,0.0273),
						array(5,9.9999,36,48,1533,0.0270),
						array(5,9.9999,48,   0xffff,1565,0.0275),
						
					  	array(10,0xffff,0 ,24,2379,0.0291),
						array(10,0xffff,24,36,2355,0.0288),
						array(10,0xffff,36,48,2332,0.0285),
						array(10,0xffff,48,   0xffff,2379,0.0291),
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
				  		array(0,1.9999,0 ,12      ,290*0.5,0.0111*0.5),
						array(0,1.9999,12,24      ,276*0.5,0.0106*0.5),
						array(0,1.9999,24,72      ,273*0.5,0.0105*0.5),
						array(0,1.9999,72,  0xffff,281*0.5,0.0108*0.5),

				  		array(2,4.9999,0 ,12     ,374*0.5,0.0144*0.5),
						array(2,4.9999,12,24     ,356*0.5,0.0137*0.5),
						array(2,4.9999,24,72     ,352*0.5,0.0135*0.5),
						array(2,4.9999,72, 0xffff,363*0.5,0.0140*0.5),

					  	array(5,9.9999,0 ,12     ,408*0.5,0.0157*0.5),
						array(5,9.9999,12,24     ,389*0.5,0.0149*0.5),
						array(5,9.9999,24,72     ,385*0.5,0.0148*0.5),
						array(5,9.9999,72, 0xffff,397*0.5,0.0152*0.5),
						
					  	array(10,0xffff,0 ,12     ,269*0.5,0.0191*0.5),
						array(10,0xffff,12,24     ,256*0.5,0.0182*0.5),
						array(10,0xffff,24,72     ,254*0.5,0.0180*0.5),
						array(10,0xffff,72, 0xffff,262*0.5,0.0185*0.5),
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,1.9999,0 ,24       ,981*0.5,0.0228*0.5),
						array(0,1.9999,24,36       ,971*0.5,0.0226*0.5),
						array(0,1.9999,36,48       ,962*0.5,0.0224*0.5),
						array(0,1.9999,48,   0xffff,981*0.5,0.0228*0.5),

				  		array(2,4.9999,0 ,24       ,1318*0.5,0.0267*0.5),
						array(2,4.9999,24,36       ,1305*0.5,0.0264*0.5),
						array(2,4.9999,36,48       ,1292*0.5,0.0262*0.5),
						array(2,4.9999,48,   0xffff,1318*0.5,0.0267*0.5),

					  	array(5,9.9999,0 ,24       ,1565*0.5,0.0275*0.5),
						array(5,9.9999,24,36       ,1549*0.5,0.0273*0.5),
						array(5,9.9999,36,48       ,1533*0.5,0.0270*0.5),
						array(5,9.9999,48,   0xffff,1565*0.5,0.0275*0.5),
						
					  	array(10,0xffff,0 ,24       ,2379*0.5,0.0291*0.5),
						array(10,0xffff,24,36       ,2355*0.5,0.0288*0.5),
						array(10,0xffff,36,48       ,2332*0.5,0.0285*0.5),
						array(10,0xffff,48,   0xffff,2379*0.5,0.0291*0.5),
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(1,1,0 ,24,1318,0.0267),
						array(1,1,24,36,1305,0.0264),
						array(1,1,36,48,1292,0.0262),
						array(1,1,48,   0xffff,1318,0.0267),

				  		array(2,2,0 ,24,501,0.0093),
						array(2,2,24,36,496,0.0092),
						array(2,2,36,48,491,0.0091),
						array(2,2,48,   0xffff,501,0.0093),

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
				  		array(0,14.7,0,1,38,0.0084),
				  		array(14.701,0xffff,0,1,92,0.0199),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,14.7,0,1,66,0.0145),
				  		array(14.7,0xffff,0,1,95,0.0210),
				  	),		
				  	'NON_OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0 ,12,246,0.0095),
						array(0,0,12,24,234,0.0090),
						array(0,0,24,72,232,0.0089),
						array(0,0,72, 0xffff,239,0.0092),
				  	),	
				  	'OPERATING_LOW_SPEED_TRUCK' => array(								
				  		array(0,0,0 ,24,834,0.0194),
						array(0,0,24,36,826,0.0192),
						array(0,0,36,48,817,0.0190),
						array(0,0,48,   0xffff,834,0.0194),
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