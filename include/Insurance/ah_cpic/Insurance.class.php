<?php
/**
 * 项目:           保险保费计算功能包
 * 文件名:         Insurance.class.php
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 * 	
 * 保险分类代码如下.
 *
 * 基本险:                
 * MVTALCI         				机动车交通事故责任强制险.
 * TVDI            				车辆损失险.
 * TTBLI           				第三方责任险.
 * TWCDMVI         				全车盗抢险.
 * TCPLI           				车上人员责任险(座位险).
 *				
 * 附加险:				
 * BSDI            				车身划痕险.(主险:TVDI)
 * BGAI            				玻璃单独破碎险.(主险:TVDI)
 * NIELI                        新增设备损失险.(主险:TVDI)
 * VWTLI                        车辆涉水损失险.(主险:TVDI) 
 * SLOI                         自燃损失险.(主险:TVDI)
 * STSFS                        指定专修厂特约条款.(主险:TVDI)
 *				
 * 不计免赔保险:				
 * TVDI_NDSI       				车损险不计免赔.  
 * TTBLI_NDSI      				第三方责任险不计免赔. 
 * TWCDMVI_NDSI    				全车盗抢险不计免赔
 * TCPLI_NDSI      				车上人员责任险不计免赔.
 * BSDI_NDSI       				车身划痕险不计免赔.
 *
 *
 * 其它代码：
 * TRAVEL_TAX                   车船税.
 * 	
 *
 * 机动分类代码:
 * NON_OPERATING_PRIVATE        非营运私有客车.
 * NON_OPERATING_ENTERPRISE     非营运企业客车.
 * NON_OPERATING_AUTHORITY      非营运机关客车. 
 * OPERATING_LEASE_RENTAL       营运出租租赁客车.
 * OPERATING_CITY_BUS           营运城市公交车. 
 * OPERATING_HIGHWAY_BUS        营运公路客车.
 * NON_OPERATING_TRUCK          非营运货车.
 * OPERATING_TRUCK              营运货车.
 * NONE_OPERATING_TRAILER       非营运挂车.
 * OPERATING_TRAILER            营运挂画.
 * SPECIAL_AUTO                 特种车辆.
 * MOTORCYCLE                   摩托车.
 * DUAL_PURPOSE_TRACTOR         兼用型拖拉机.
 * TRANSPORT_TRACTOR            运输型拖拉机.
 * OPERATING_LOW_SPEED_TRUCK    运营低速载货汽车.
 * NON_OPERATING_LOW_SPEED_TRUCK非运营低速载货汽车.
 *
 * 保险变量:
 * TYPE_AUTO                    车辆分类.
 * SEATS                        座位数.
 * LOAD                         载重量.
 * ENGINE                       发动机排量.
 * POWER                        发动机功率.
 * KERB_MASS                    整备质量. 
 * TYPE_SPECIAL_AUTO            特种车辆分类.
 * MONTHS                       保险期间.
 * FLOATING_RATE                浮动标准.
 * MAIN_INSURANCE_CAST          主险保费.
 * 
 **/ 


class Insurance 
{
	/**
	 * 版本号	
	 **/
	const     Version = '1.0.1';
	
	/**
	 * 计算保费.
	 * @types   要计算保费的保险代码集合,数组.
	 * @params  保险变的集合,数组.
	 **/ 
	public function buy( Array $types = array(), Array $params = array() )
	{
		$total_cast = 0;
		$result = array();
		$nocal = $types;
		reset($nocal);
		
		if(!array_key_exists('DEPRECIATION_PRICE',$params))
		{
			$params['DEPRECIATION_PRICE'] = $this->depreciation($params);
		}	
		$result['DEPRECIATION_PRICE'] = $params['DEPRECIATION_PRICE'];
		
		while(!empty($nocal))
		{
			$key = key($nocal);
			$type = current($nocal);
						
			if(array_key_exists($type,$result))
			{							
				if(!next($nocal)) reset($nocal);
				unset($nocal[$key]);
				continue ;
			}			
			$cname = $type.'_INS';
			$fname = dirname(__FILE__).'/'.$cname.'.class.php';
			if(!is_file($fname))
			{				
				if(!next($nocal)) reset($nocal);
				unset($nocal[$key]);
				continue;
			}	
			
			include_once($fname);
			if(!class_exists($cname))
			{
						
				if(!next($nocal)) reset($nocal);
				unset($nocal[$key]);	
				continue;
			}
			
			$ins = new $cname();

			if(!empty($ins->MainInsurance))
			{				
				
				if(array_key_exists($ins->MainInsurance,$result))
				{
					
					$params['MAIN_INSURANCE_CAST'] = $result[$ins->MainInsurance];
					$result[$type] = $ins->buy($params);	
					$total_cast += $result[$type];
					unset($nocal[$key]);						
				}
				elseif(in_array($ins->MainInsurance,$nocal))
				{
					if(!next($nocal)) reset($nocal);
					continue;
				}
				else
				{					
					if(!next($nocal)) reset($nocal);	
					unset($nocal[$key]);	
					continue;					
				}
			}
			else
			{
				$result[$type] = $ins->buy($params);	
				$total_cast += $result[$type];
				unset($nocal[$key]);	
			}
			
			if(!next($nocal)) reset($nocal);			
		}
		$result['TOTAL_CAST'] = $total_cast;
		return $result;	
	}

	/**
	 * 退还保险费计算.
	 * @type     保险代码
	 * @params   保险变量
	 **/ 	
	public function refund( $type , Array $params = array() )
	{
		$cname = $type.'_INS';
		$fname = dirname(__FILE__).'/'.$cname.'.class.php';
		if(!is_file($fname)) return false;
		include_once($fname);
		if(!class_exists($cname)) return false;
		$ins = new $cname();
		return $ins->refund($params);		
	}

	/**
	 * 折扣计算.
	 *
	 * @params   变量
	 **/ 	
	public function discount( Array $params = array() )
	{
		if(!is_file(dirname(__FILE__).'/'.'discount.class.php')) return false;
		include_once(dirname(__FILE__).'/'.'discount.class.php');
		if(!class_exists('discount')) return false;
		$ins = new discount();
		return $ins->calculate($params);		
	}

	/**
	 *   新车折旧价计算.
	 *
	 * @params   变量
	 **/ 
	public function depreciation( Array $params = array() ) 
	{
		$result = false;
		
		if(!is_file(dirname(__FILE__).'/'.'depreciation.class.php')) return false;		

		include_once(dirname(__FILE__).'/'.'depreciation.class.php');
		if(class_exists('depreciation'))
		{
			$depre = new depreciation();
			$result = $depre->calculate($params);
		}
				
		return $result;	
	}

}



?>