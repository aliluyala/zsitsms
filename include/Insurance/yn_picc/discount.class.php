<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         discount.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 保费折扣计算.
 * 
 **/ 
 
class discount 
{
	/**
	 * 版本
	 **/
	public         $Version = 'BXZK-2009(A)-YN-PICCP&C';	
	/**
	 * 折扣系数	
	 **/
	private	      $Ratio = array(
					'CLAIM_RECORDS' => array(
						'MORE_TWO_YEARS_NO_CLAIM'      => 0.7,
						'TWO_YEAR_NO_CLAIM'            => 0.8,
						'LAST_YEAR_NO_CLAIM'           => 0.9,  
						'FIRST_YEAR_INSURANCE'         => 1,
						'LAST_YEAR_CLAIM_ONE'          => 1,
						'LAST_YEAR_CLAIM_TWO'          => 1,
						'LAST_YEAR_CLAIM_THREE'        => 1.1,
						'LAST_YEAR_CLAIM_FOUR'         => 1.2,
						'LAST_YEAR_CLAIM_FIVE_ABOVE'   => 1.3,						
					),
					'YEARS_OF_INSURANCE' => array(
						'FIRST_YEAR_INSURANCE'         => 1,
						'RENEWAL_OF_INSURANCE'         => 0.9,
					),
					'DESIGNATED_DRIVER' => array(
						'NO_DESIGNATED_DRIVER'         => 0.9,
						'DESIGNATED_DRIVER'            => 1,	
					),
					'DRIVER_AGE'     => array(
						'LESS_25_AGE'                  => 1.05,
						'25_30_AGE'                    => 1,
						'30_40_AGE'                    => 0.95,
						'40_60_AGE'                    => 1,
						'GREATER_60_AGE'               => 1.05,
					),
					'DRIVER_SEX'    => array(
						'MALE'                         => 1,
						'FEMALE'					   => 0.95,	
					),
					'DRIVING_YEARS' => array(
						'LESS_1_YEARS'                 => 1.05,
						'1_3_YEARS'                    => 1.02,	
						'GREATER_3_YEARS'              => 1,
					),
					'DRIVING_AREA' => array(
						'CHINA_TERRITORY'              => 1,
						'THE_PROVINCE'                 => 0.95,
					),
					'MULTIPLE_INSURANCE' => array(
						'MULTIPLE_INSURANCE_1'         => 1, 
						'MULTIPLE_INSURANCE_0.99'      => 0.99,
						'MULTIPLE_INSURANCE_0.98'      => 0.98,
						'MULTIPLE_INSURANCE_0.97'      => 0.97,
						'MULTIPLE_INSURANCE_0.96'      => 0.96,
						'MULTIPLE_INSURANCE_0.95'      => 0.95,
					),
					'AVERAGE_ANNUAL_MILEAGE' => array(
						'LESS_30000_KM'                => 0.9,
						'30000_50000_KM'               => 1,
						'GREATER_50000_KM_1.1'         => 1.1,	
						'GREATER_50000_KM_1.2'         => 1.2,
						'GREATER_50000_KM_1.3'         => 1.3,	
					),
				  );	
	/**
	 *	最低折扣
     **/	 
	const         MinDiscount = 0.7;
	/**
	 * 折扣计算.
	 * @params  保险变量.
	 *          键值是变量名,变量名如下:
	 * 			CLAIM_RECORDS              索赔记录.
	 * 			YEARS_OF_INSURANCE         投保年度.
	 * 			DESIGNATED_DRIVER          指定驾驶人.
	 * 			DRIVER_AGE                 驾驶人年龄.
	 *          DRIVER_SEX                 驾驶人性别.   
	 * 			DRIVING_YEARS              驾驶人驾龄.
	 * 			DRIVING_AREA               车辆行驶区域.
	 * 			MULTIPLE_INSURANCE         多险种投保.
	 *          AVERAGE_ANNUAL_MILEAGE     年平均行驶里程.	 
	 **/ 
	public function calculate( Array $params = array() )
	{
		$discount = 1;
		if(array_key_exists('CLAIM_RECORDS',$params) && 
		   array_key_exists($params['CLAIM_RECORDS'],$this->Ratio['CLAIM_RECORDS']))
		{	
			$discount *= $this->Ratio['CLAIM_RECORDS'][$params['CLAIM_RECORDS']];
		}	

		if(array_key_exists('YEARS_OF_INSURANCE',$params) && 
		   array_key_exists($params['YEARS_OF_INSURANCE'],$this->Ratio['YEARS_OF_INSURANCE']))
		{	
			$discount *= $this->Ratio['YEARS_OF_INSURANCE'][$params['YEARS_OF_INSURANCE']];
		}	

		if(array_key_exists('DESIGNATED_DRIVER',$params) && 
		   array_key_exists($params['DESIGNATED_DRIVER'],$this->Ratio['DESIGNATED_DRIVER']))
		{	
			$discount *= $this->Ratio['DESIGNATED_DRIVER'][$params['DESIGNATED_DRIVER']];
		}	

		if(array_key_exists('DRIVER_AGE',$params) && 
		   array_key_exists($params['DRIVER_AGE'],$this->Ratio['DRIVER_AGE']))
		{	
			$discount *= $this->Ratio['DRIVER_AGE'][$params['DRIVER_AGE']];
		}

		if(array_key_exists('DRIVER_SEX',$params) && 
		   array_key_exists($params['DRIVER_SEX'],$this->Ratio['DRIVER_SEX']))
		{	
			$discount *= $this->Ratio['DRIVER_SEX'][$params['DRIVER_SEX']];
		}		

		if(array_key_exists('DRIVING_YEARS',$params) && 
		   array_key_exists($params['DRIVING_YEARS'],$this->Ratio['DRIVING_YEARS']))
		{	
			$discount *= $this->Ratio['DRIVING_YEARS'][$params['DRIVING_YEARS']];
		}	

		if(array_key_exists('DRIVING_AREA',$params) && 
		   array_key_exists($params['DRIVING_AREA'],$this->Ratio['DRIVING_AREA']))
		{	
			$discount *= $this->Ratio['DRIVING_AREA'][$params['DRIVING_AREA']];
		}		

		if(array_key_exists('MULTIPLE_INSURANCE',$params) && 
		   array_key_exists($params['MULTIPLE_INSURANCE'],$this->Ratio['MULTIPLE_INSURANCE']))
		{	
			$discount *= $this->Ratio['MULTIPLE_INSURANCE'][$params['MULTIPLE_INSURANCE']];
		}		

		if(array_key_exists('AVERAGE_ANNUAL_MILEAGE',$params) && 
		   array_key_exists($params['AVERAGE_ANNUAL_MILEAGE'],$this->Ratio['AVERAGE_ANNUAL_MILEAGE']))
		{	
			$discount *= $this->Ratio['AVERAGE_ANNUAL_MILEAGE'][$params['AVERAGE_ANNUAL_MILEAGE']];
		}				
		if($discount < self::MinDiscount) return self::MinDiscount;
		return round($discount,2) ;		
	}
	

}
 


?>