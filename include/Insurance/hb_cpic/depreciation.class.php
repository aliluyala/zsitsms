<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         depreciation.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 新车折旧价计算.
 * 
 **/ 
 
class depreciation 
{
	/**
	 * 版本
	 **/
	public         $Version = 'XCZJJ-2009(C)-HB-CPIC';	
	/**
	 * 折旧系数	
	 **/
	private	      $Ratio = array(
				  	'NON_OPERATING_PRIVATE' => array(
				  		array(0,9,0.006),
						array(10,0xffff,0.009),
					),  
				  	'NON_OPERATING_ENTERPRISE' => array(
				  		array(0,9,0.006),
						array(10,0xffff,0.009),
				  	),  	
				  	'NON_OPERATING_AUTHORITY' => array(
				  		array(0,9,0.006),
						array(10,0xffff,0.009),
				  	),  
				  	'OPERATING_LEASE_RENTAL' => array(
				  		array(0,9,0.09),
						array(10,0xffff,0.09),	
				  	), 
				  	'OPERATING_CITY_BUS' => array(								
				  		array(0,9,0.009),
						array(10,0xffff,0.009),			
				  	),  
				  	'OPERATING_HIGHWAY_BUS' => array(								
				  		array(0,9,0.009),
						array(10,0xffff,0.009),		
				  	),  
				  	'NON_OPERATING_TRUCK' => array(								
						array(0,4499.99,0.012),
				  		array(4500.01,0xffff,0.009),		
				  	),
				  	'OPERATING_TRUCK' => array(	
						array(0,4499.99,0.012),
				  		array(4500,0xffff,0.009),			
				  	),  
				  	'NON_OPERATING_TRAILER' => array(
						array(0,0,0.012),	
				  	),
				  	'OPERATING_TRAILER' => array(
				  		array(0,0,0.012),		
				  	),  							
				  	'SPECIAL_AUTO' => array(								
				  		array(0,0,0.009),	
				  	),	
					'NON_OPERATING_LOW_SPEED_TRUCK' => array(
						array(0,0,0.009),
					),
					'OPERATING_LOW_SPEED_TRUCK' => array(
						array(0,0,0.009),
					),					
					'MOTORCYCLE' => array(								
				  		array(0,0,0.009),
				  	),	
				  	'DUAL_PURPOSE_TRACTOR' => array(								
				  		array(0,0,0.009),
				  	),	
				  	'TRANSPORT_TRACTOR' => array(								
				  		array(0,0,0.009),
				  	),	
	
				  );	
				  
	/**
	 *	最低折扣
     **/	 
	const         MinDepreciation = 0.8;
	/**
	 * 折扣计算.
	 * @params  保险变量.
	 *          键值是变量名,变量名如下:
	 * 			TYPE_AUTO           车辆类型.
	 * 			SEATS               座位数.
	 *          COTY                车龄(月).	
     *          TOTAL_MASS          总质量(KG).	 
	 *          PURCHASE_PRICE      新车购置价(元).  	 
	 **/ 
	public function calculate( Array $params = array() )
	{
		
		if(!array_key_exists('TYPE_AUTO',$params) || 
		   !array_key_exists($params['TYPE_AUTO'],$this->Ratio))
		{	
			return false;
		}	
		
		$type = $params['TYPE_AUTO'];
		
		$sl = 0;
		switch($type)
		{
			case 'NON_OPERATING_PRIVATE':
			case 'NON_OPERATING_ENTERPRISE':
			case 'NON_OPERATING_AUTHORITY':
			case 'OPERATING_LEASE_RENTAL':
			case 'OPERATING_CITY_BUS':
			case 'OPERATING_HIGHWAY_BUS':
				$sl = $params['SEATS']; 
				break;
			case 'NON_OPERATING_TRUCK':
			case 'OPERATING_TRUCK':
				$sl = $params['TOTAL_MASS']; 
				break;
			default:
				$sl = 0;
		}

		
		if(!array_key_exists('COTY',$params) || 
			$params['COTY'] < 0 )
		{
			return false;
		}
		
		if(!array_key_exists('PURCHASE_PRICE',$params))
		{
			return false;
		}		
		$price = $params['PURCHASE_PRICE'];
		
	
		
		$depre  =  $params['COTY'];
		foreach($this->Ratio[$type] as $row )
		{
			if($row[0] <= $sl && $sl <= $row[1]  )
			{
				$depre = $depre * $row[2];
				break;
			}
		}
		
		if($depre > self::MinDepreciation) $depre = self::MinDepreciation ;
	
		return round($price * (1 - $depre),2) ;		
	}
	

}
 


?>