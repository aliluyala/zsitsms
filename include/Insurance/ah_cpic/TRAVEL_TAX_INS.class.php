<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         TRAVEL_TAX_INS.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *                 
 * 车船税计算.
 * 
 **/ 
 
class TRAVEL_TAX_INS 
{
	/**
	 * 版本
	 **/
	public         $Version = 'CCS-2012-AH';	

	/**
	 * 税率	
	 **/
	private	      $Rate = array(
				  	'CAR' => array(
				  		array(0,1000,180),
						array(1000,1600,300),
						array(1600,2000,360),
						array(2000,2500,660),
						array(2500,3000,1200),
				  		array(3000,4000,2700),
						array(4000,0xffff,3900),	
				  	),  
		
				  );	

	/**
	 * 税额计算.
	 * @params  变量.
	 *          键值是变量名,变量名如下:
	 * 			TYPE_AUTO           车辆类型.
	 * 			SEATS               座位数(客车).
	 * 			LOAD                载重(货车,吨).
	 * 			ENGINE              发动机排量(cc).
	 *          KERB_MASS           整备质量(吨). 
	 **/ 
	public function buy( Array $params = array() )
	{
		if(!array_key_exists('TYPE_AUTO',$params) )
		{	
			return false;
		}	
		$type = $params['TYPE_AUTO'];
		
		$tax = 0;
		switch($type)
		{
			case 'NON_OPERATING_PRIVATE'   :
			case 'NON_OPERATING_ENTERPRISE':
			case 'NON_OPERATING_AUTHORITY' :
			case 'OPERATING_LEASE_RENTAL'  :
			case 'OPERATING_CITY_BUS'      :
			case 'OPERATING_HIGHWAY_BUS'   :
				 if(array_key_exists('SEATS',$params) && $params['SEATS']<10 && array_key_exists('ENGINE',$params) ) 
				 {
				 	foreach($this->Rate['CAR'] as $row)
				 	{
				 		if($row[0] < $params['ENGINE'] && $row[1] >= $params['ENGINE'])
				 		{
				 			$tax = $row[2];
				 			break;
				 		}
				 	}
				 	
				 }
				 elseif(array_key_exists('SEATS',$params) && $params['SEATS']>=10 )
				 {
				 	foreach($this->Rate['BUS'] as $row)
				 	{
				 		if($row[0] <= $params['SEATS'] && $row[1] >= $params['SEATS'])
				 		{
				 			$tax = $row[2];
				 			break;
				 		}
				 	}					
				 }	
				 break;
            case 'NON_OPERATING_TRUCK'     :
            case 'OPERATING_TRUCK'         :
				 if(array_key_exists('KERB_MASS',$params))
				 {
					$tax = $this->Rate['TRUCK'] * ceil($params['KERB_MASS']);
				 }	
				 break;
            case 'NONE_OPERATING_TRAILER'  :
            case 'OPERATING_TRAILER'       :
				 if(array_key_exists('KERB_MASS',$params))
				 {
					$tax = $this->Rate['TRAILER'] * ceil($params['KERB_MASS']);
				 }	
				 break;
            case 'SPECIAL_AUTO'            :
				 if(array_key_exists('KERB_MASS',$params))
				 {
					$tax = $this->Rate['SPECIAL_AUTO'] * ceil($params['KERB_MASS']);
				 }	
				 break;
            case 'MOTORCYCLE'              :
				 $tax = $this->Rate['MOTORCYCLE'];
				 break;
		}			
		
		return $tax;		
	}
	

	
}
 


?>