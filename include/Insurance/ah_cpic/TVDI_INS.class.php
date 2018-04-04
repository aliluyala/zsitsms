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
	public         $Version = 'CSX-2009(c)-AH-WF-DX-CPIC';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,0 ,12,616.3,0.01471),
						array(0,5,12,48,582.3,0.01386),
						array(0,5,48,72,580.6,0.01377),
						array(0,5,72,96,598.4,0.01428),
						array(0,5,96, 0xffff,598.4,0.01428),
						
				  		array(6,0xffff,0 ,12,738.7,0.01471),
						array(6,0xffff,12,48,699.6,0.01386),
						array(6,0xffff,48,72,697,0.01377),
						array(6,0xffff,72,96,718.3,0.01428),
						array(6,0xffff,96, 0xffff,718.3,0.01428),						
				  	),  


				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,0 ,12,266.1,0.0102),
						array(0,1.9999,12,48,251.6,0.00969),
						array(0,1.9999,48,72,249.9,0.00969),
						array(0,1.9999,72,96,258.4,0.00995),
						array(0,1.9999,96, 0xffff,258.4,0.00995),
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