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
	public         $Version = 'DSZZRX-2009(c)-AH-WF-DX-CPIC';	
	/**
	 * 主险代码
	 **/
	public         $MainInsurance = null;
	/**
	 * 保险费率	
	 **/
	private	      $Rate = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,5,5,789.7),
						array(0,5,10,1140.7),
						array(0,5,15,1300.5),
						array(0,5,20,1413.6),
						array(0,5,30,1595.5),
						array(0,5,50,1914.2),
						array(0,5,100,2493.9),
						
					
						array(6,0xffff,5,  772.7 ),
						array(6,0xffff,10, 1088 ),
						array(6,0xffff,15, 1230.8),
						array(6,0xffff,20, 1325.2),
						array(6,0xffff,30, 1485.8),
						array(6,0xffff,50, 1768),
						array(6,0xffff,100,2302.7),		
				  	),  

				  	'NON_OPERATING_TRUCK' => array(								
				  		array(0,1.9999,5,877.2),
						array(0,1.9999,10,1235.9),
						array(0,1.9999,15,1396.6),
						array(0,1.9999,20,1505.4),
						array(0,1.9999,30,1686.4),
						array(0,1.9999,50,2007.7),
						array(0,1.9999,100,2615.5),
						

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

		if($insAmount>100)
		{
			$cost_50 = 0;
			$cost_100 = 0;
			foreach($this->Rate[$type] as $row )
			{
				if($row[0] <= $sl && $sl <= $row[1] && $row[2] == 50 )
				{
					$cost_50 = $row[3] ;
				}
				elseif($row[0] <= $sl && $sl <= $row[1] && $row[2] == 100 )
				{
					$cost_100 = $row[3] ;
				}
			}
			$cost = $cost_100 + 0.9*floor(($insAmount-100)/50)*($cost_100-$cost_50);
		}
		else
		{
			foreach($this->Rate[$type] as $row )
			{
				if($row[0] <= $sl && $sl <= $row[1] && $row[2] == $insAmount )
				{
					$cost = $row[3] ;
					break;
				}
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