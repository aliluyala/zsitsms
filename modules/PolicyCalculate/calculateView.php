<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
require_once(_ROOT_DIR."/modules/PolicyCalculate/PolicyCalculateModule.class.php");
$mod = new PolicyCalculateModule();

$recordid = '';
if(!empty($_GET['recordid'])) $recordid = $_GET['recordid'];

$form = array();

$form['NO'] = '';
$form['OTHER']['PREMIUM_RATE_TABLE']='';
$form['OTHER']['INSURANCE_COMPANY']='';

$form['INSURANT']['HOLDER'] = '';
$form['INSURANT']['HOLDER_IDENTIFY_TYPE']='';
$form['INSURANT']['HOLDER_ADDRESS']='';
$form['INSURANT']['HOLDER_IDENTIFY_NO']='';

$form['INSURANT']['INSURANT'] = '';
$form['INSURANT']['IDENTIFY_TYPE']='';
$form['INSURANT']['ADDRESS']='';
$form['INSURANT']['IDENTIFY_NO']='';

$form['AUTO']['LICENSE_NO']='';
$form['AUTO']['LICENSE_TYPE']='';
$form['AUTO']['OWNER']='';
$form['AUTO']['VIN_NO']='';
$form['AUTO']['ENGINE_NO']='';
$form['AUTO']['MODEL']='';
$form['AUTO']['ENGINE']='';
$form['AUTO']['SEATS']='';
$form['AUTO']['KERB_MASS']='';
$form['AUTO']['MODEL_CODE']='';
$form['AUTO']['BUYING_PRICE']='';
$form['AUTO']['ENROLL_DATE']='';
$form['AUTO']['VEHICLE_TYPE']='';
$form['AUTO']['USE_CHARACTER']='';
$form['AUTO']['ORIGIN']='';

$form['POLICY']['TOTAL_PREMIUM']='';
$form['POLICY']['TOTAL_STANDARD_PREMIUM']='';
$form['POLICY']['TOTAL_BUSINESS_PREMIUM'] = ''; 
$form['POLICY']['TOTAL_MVTALCI_PREMIUM'] ='';  
$form['POLICY']['TOTAL_TRAVEL_TAX_PREMIUM'] ='';



$form['POLICY']['TRAVEL_TAX_PREMIUM']='';
$form['POLICY']['MVTALCI_SELECT']='';
$form['POLICY']['MVTALCI_PREMIUM']='';
$form['POLICY']['FLOATING_RATE']='';
$form['POLICY']['MVTALCI_START_TIME']='';
$form['POLICY']['MVTALCI_END_TIME']='';

$form['POLICY']['BUSINESS_PREMIUM']='';
$form['POLICY']['BUSINESS_DISCOUNT']='';
$form['POLICY']['BUSINESS_STANDARD_PREMIUM']='';

$form['POLICY']['BUSINESS_START_TIME']='';
$form['POLICY']['BUSINESS_END_TIME']='';

$form['POLICY']['DESIGNATED_DRIVER1']='';
$form['POLICY']['DRIVER_NAME1']='';
$form['POLICY']['DRIVING_LICENCE_NO1']='';
$form['POLICY']['DRIVER_ALLOW_DRIVE1']='';
$form['POLICY']['DRIVER_SEX1']='';
$form['POLICY']['DRIVER_AGE1']='';
$form['POLICY']['DRIVING_YEARS1']='';


$form['POLICY']['DESIGNATED_DRIVER2']='';
$form['POLICY']['DRIVER_NAME2']='';
$form['POLICY']['DRIVING_LICENCE_NO2']='';
$form['POLICY']['DRIVER_ALLOW_DRIVE2']='';
$form['POLICY']['DRIVER_SEX2']='';
$form['POLICY']['DRIVER_AGE2']='';
$form['POLICY']['DRIVING_YEARS2']='';

$form['POLICY']['DESIGNATED_DRIVER3']='';
$form['POLICY']['DRIVER_NAME3']='';
$form['POLICY']['DRIVING_LICENCE_NO3']='';
$form['POLICY']['DRIVER_ALLOW_DRIVE3']='';
$form['POLICY']['DRIVER_SEX3']='';
$form['POLICY']['DRIVER_AGE3']='';
$form['POLICY']['DRIVING_YEARS3']='';

$form['POLICY']['YEARS_OF_INSURANCE']='';
$form['POLICY']['CLAIM_RECORDS']='';
$form['POLICY']['DRIVING_AREA']='';
$form['POLICY']['AVERAGE_ANNUAL_MILEAGE']='';
$form['POLICY']['MULTIPLE_INSURANCE']='';
$form['POLICY']['BUSINESS_DISCOUNT_SHWY']='';

$form['POLICY']['TVDI_INSURANCE_AMOUNT']='';
$form['POLICY']['DOC_AMOUNT']='';
$form['POLICY']['TVDI_PREMIUM']='';
$form['POLICY']['TVDI_DISCOUNT_PREMIUM']='';
$form['POLICY']['TVDI_CHECK_RATIFY_PREMIUM']='';
$form['POLICY']['TTBLI_INSURANCE_AMOUNT']='';
$form['POLICY']['TTBLI_INSURANCE_AMOUNT_EXT']='';
$form['POLICY']['TTBLI_PREMIUM']='';

$form['POLICY']['TTBLI_DISCOUNT_PREMIUM']='';
$form['POLICY']['TTBLI_CHECK_RATIFY_PREMIUM']='';
$form['POLICY']['TWCDMVI_INSURANCE_AMOUNT']='';
$form['POLICY']['TWCDMVI_PREMIUM']='';
$form['POLICY']['TWCDMVI_DISCOUNT_PREMIUM']='';
$form['POLICY']['TWCDMVI_CHECK_RATIFY_PREMIUM']='';
$form['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT']='';
$form['POLICY']['TCPLI_DRIVER_PREMIUM']='';
$form['POLICY']['TCPLI_DRIVER_DISCOUNT_PREMIUM']='';
$form['POLICY']['TCPLI_PASSENGER_COUNT']='';
$form['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT']='';
$form['POLICY']['TCPLI_PASSENGER_PREMIUM']='';
$form['POLICY']['TCPLI_PASSENGER_DISCOUNT_PREMIUM']='';
$form['POLICY']['BSDI_INSURANCE_AMOUNT']='';
$form['POLICY']['BSDI_PREMIUM']='';
$form['POLICY']['BSDI_DISCOUNT_PREMIUM']='';
$form['POLICY']['SLOI_INSURANCE_AMOUNT']='';
$form['POLICY']['SLOI_PREMIUM']='';
$form['POLICY']['SLOI_DISCOUNT_PREMIUM']='';
$form['POLICY']['GLASS_ORIGIN']='';
$form['POLICY']['BGAI_PREMIUM']='';
$form['POLICY']['BGAI_DISCOUNT_PREMIUM']='';
$form['POLICY']['NIELI_INSURANCE_AMOUNT']='';
$form['POLICY']['NIELI_PREMIUM']='';
$form['POLICY']['NIELI_DISCOUNT_PREMIUM']='';
$form['POLICY']['STSFS_RATE']='';
$form['POLICY']['STSFS_PREMIUM']='';
$form['POLICY']['STSFS_DISCOUNT_PREMIUM']='';
$form['POLICY']['TVDI_NDSI_PREMIUM']='';
$form['POLICY']['TVDI_NDSI_DISCOUNT_PREMIUM']='';
$form['POLICY']['TTBLI_NDSI_PREMIUM']='';
$form['POLICY']['TTBLI_NDSI_DISCOUNT_PREMIUM']='';
$form['POLICY']['TWCDMVI_NDSI_PREMIUM']='';
$form['POLICY']['TWCDMVI_NDSI_DISCOUNT_PREMIUM']='';
$form['POLICY']['TCPLI_DRIVER_NDSI_PREMIUM']='';
$form['POLICY']['TCPLI_DRIVER_NDSI_DISCOUNT_PREMIUM']='';
$form['POLICY']['TCPLI_PASSENGER_NDSI_PREMIUM']='';
$form['POLICY']['TCPLI_PASSENGER_NDSI_DISCOUNT_PREMIUM']='';
$form['POLICY']['BSDI_NDSI_PREMIUM']='';
$form['POLICY']['BSDI_NDSI_DISCOUNT_PREMIUM']='';
$form['POLICY']['SLOI_NDSI_PREMIUM']='';
$form['POLICY']['BGAI_NDSI_PREMIUM']='';
$form['POLICY']['NIELI_NDSI_PREMIUM']='';
$form['POLICY']['STSFS_NDSI_PREMIUM']='';
$form['POLICY']['WADING_INSURANCE_AMOUNT']='';       
$form['POLICY']['WADING_PREMIUM']='';               
$form['POLICY']['WADING_DISCOUNT_PREMIUM']='';      
$form['POLICY']['WADING_NDSI_PREMIUM']='';           
$form['POLICY']['WADING_NDSI_DISCOUNT_PREMIUM']='' ;   
$form['POLICY']['CUSTOM1_INSURANCE_AMOUNT']='' ;    
$form['POLICY']['CUSTOM1_INSURANCE_NAME']='' ;    
$form['POLICY']['CUSTOM1_PREMIUM']='' ;                
$form['POLICY']['CUSTOM1_DISCOUNT_PREMIUM']='';        
$form['POLICY']['CUSTOM1_NDSI_PREMIUM']=''   ;         
$form['POLICY']['CUSTOM1_NDSI_DISCOUNT_PREMIUM']='' ;  
$form['POLICY']['CUSTOM2_INSURANCE_NAME']='' ; 
$form['POLICY']['CUSTOM2_INSURANCE_AMOUNT']=''  ;      
$form['POLICY']['CUSTOM2_PREMIUM']=''    ;             
$form['POLICY']['CUSTOM2_DISCOUNT_PREMIUM']=''  ;      
$form['POLICY']['CUSTOM2_NDSI_PREMIUM']=''   ;         
$form['POLICY']['CUSTOM2_NDSI_DISCOUNT_PREMIUM']='' ; 

$form['POLICY']['BUSINESS']='';

$form['OTHER']['SHIYEICAO_PRODUCT'] = '';
$form['OTHER']['GIFT'] = '';
$form['OTHER']['REMARKS'] = '';

$outform  = array();

$data = array(
		'INSURANT' => array(),
		'AUTO'     => array(),
		'POLICY'   => array(),
		'OTHER'    => array(),
        );

		
if(!empty($recordid))
{
	$oper = 'edit';
	$reset = $mod->getOneRecordset($recordid,null,null);
	if(!empty($reset[0]['content']))
	{
		$data  = json_decode($reset[0]['content'],true);
	}
	
	foreach($data['INSURANT'] as $key => $val )
	{
		$data['INSURANT'][$key] =  urldecode($val);
	}
	
	foreach($data['AUTO'] as $key => $val )
	{
		$data['AUTO'][$key] =  urldecode($val);
	}
	foreach($data['POLICY'] as $key => $val )
	{
		$data['POLICY'][$key] =  urldecode($val);
	}
	foreach($data['OTHER'] as $key => $val )
	{
		$data['OTHER'][$key] =  urldecode($val);
	}
	
	
	
}
else
{
	$oper = 'create';
	$data['BUSINESS_ITEMS'] = array('TVDI','TTBLI','TVDI_NDSI','TTBLI_NDSI');
	$data['MVTALCI_SELECT'] = 'YES';
	$data['POLICY']['MVTALCI_START_TIME'] = date('Y-m-d 00:00:00',time()+86400);
	$data['POLICY']['BUSINESS_START_TIME'] = $data['POLICY']['MVTALCI_START_TIME'];
	
	if(array_key_exists('vin_no',$_GET))
	{
		$vin_no = $_GET['vin_no'];

		$sql = "select * from accounts_shwy where vin='{$vin_no}';";
		$result = $APP_ADODB->Execute($sql);			
		
		if($result && !$result->EOF)
		{
			$data['INSURANT']['INSURANT']    = $result->fields['owner']         ;  
			$data['INSURANT']['ADDRESS']     = $result->fields['address']       ;  
			$data['INSURANT']['IDENTIFY_NO'] = $result->fields['id_code']       ;  
			$data['INSURANT']['HOLDER']      = $result->fields['owner']         ;  
			$data['INSURANT']['HOLDER_ADDRESS']     = $result->fields['address']       ;  
			$data['INSURANT']['HOLDER_IDENTIFY_NO'] = $result->fields['id_code']       ;  			
			
			$data['AUTO']['LICENSE_NO']      = $result->fields['plate_no']      ;     
			$data['AUTO']['LICENSE_TYPE']    = 'SMALL_CAR';//$result->fields['license_type']  ;   
			$data['AUTO']['OWNER']           = $result->fields['owner']         ;   
			$data['AUTO']['VIN_NO']          = $result->fields['vin']           ;      
			$data['AUTO']['ENGINE_NO']       = $result->fields['engine_no']     ;   
			$data['AUTO']['MODEL']           = $result->fields['model']         ;   
			$data['AUTO']['ENGINE']          = $result->fields['engine']        ;   
			$data['AUTO']['SEATS']           = $result->fields['seats']         ;   
			$data['AUTO']['KERB_MASS']       = $result->fields['kerb_mass']     ;   
			$data['AUTO']['MODEL_CODE']      = $result->fields['model']         ;   
			$data['AUTO']['BUYING_PRICE']    = $result->fields['purchase_price'] ;   
			$data['AUTO']['ENROLL_DATE']     = $result->fields['register_date'] ; 
			$data['AUTO']['VEHICLE_TYPE']    = $result->fields['vehicle_type']  ;   
			$data['AUTO']['USE_CHARACTER']   = $result->fields['use_character'] ;   
			$data['AUTO']['ORIGIN']          = $result->fields['origin']        ;
			$data['OTHER']['INSURANCE_COMPANY']=$result->fields['company']      ;
			
			$data['POLICY']['TVDI_INSURANCE_AMOUNT'] = $result->fields['purchase_price'] ;
			if($result->fields['seats']>1)
			{
				$data['POLICY']['TCPLI_PASSENGER_COUNT'] = $result->fields['seats']-1;
			}
			
			
			if($result->fields['expiration_date'] !='0000-00-00 00:00:00' )
			{				
				$data['POLICY']['MVTALCI_START_TIME'] = date('Y-m-d 00:00:00',strtotime($result->fields['expiration_date'])+86400);
				$data['POLICY']['BUSINESS_START_TIME'] = $data['POLICY']['MVTALCI_START_TIME'];
			}
			
		}                                            
			                                                                  
	}                                                                         
	                                                                          
}                                                                             
                                                                              
$mod->picklist['form[OTHER][PREMIUM_RATE_TABLE]'] = array('默认');

/*
$sql = "select * from company;";
$result = $APP_ADODB->Execute($sql);
$company = array();
if($result)
{
	while(!$result->EOF)
	{
		$company[] = $result->fields['name'];
		$result->MoveNext();
	}
}

$mod->picklist['form[OTHER][INSURANCE_COMPANY]'] = $company;
*/
/*

$sql = "select * from products;";
$result = $APP_ADODB->Execute($sql);
$shiyeicao = array();
if($result)
{
	while(!$result->EOF)
	{
		$shiyeicao[] = $result->fields['productnames'];
		$result->MoveNext();
	}
}

$mod->picklist['form[OTHER][SHIYEICAO_PRODUCT]'] = $shiyeicao;
*/

foreach($form as $key => $v)
{
	$outform[$key] = '';
	if(is_array($v)) continue;
	
	$rkey = "form[{$key}]";
	
	if(array_key_exists($rkey,$mod->fields))
	{
		$value=null;
		$picklist=array();
		$associateTo=null;
		if(array_key_exists($key,$data))
		{
			$value = $data[$key];
		}
		
		if(array_key_exists($rkey,$mod->picklist))
		{
			$picklist = $mod->picklist[$rkey];
		}
		
		if(array_key_exists($rkey,$mod->associateTo))
		{
			$associateTo = $mod->associateTo[$rkey];
		}
		
		$uiset = createFieldUI($rkey,$mod->fields[$rkey],$value,$picklist,$associateTo,$oper);
		$smarty->assign('FIELDINFO',$uiset);
		$outform[$key] = $smarty->fetch("UI/{$mod->fields[$rkey][0]}.UI.tpl");
	}	
}

foreach($form['INSURANT'] as $key => $v)
{
	$outform[$key] = '';
	if(is_array($v)) continue;
	//$smarty->clearAllAssign();
	
	$rkey = "form[INSURANT][{$key}]";
	
	//echo $rkey.'<br/>';
	if(array_key_exists($rkey,$mod->fields))
	{
		$value=null;
		$picklist=array();
		$associateTo=null;
		if(array_key_exists($key,$data['INSURANT']))
		{
			$value = $data['INSURANT'][$key];
		}
		
		if(array_key_exists($rkey,$mod->picklist))
		{
			$picklist = $mod->picklist[$rkey];
		}
		
		if(array_key_exists($rkey,$mod->associateTo))
		{
			$associateTo = $mod->associateTo[$rkey];
		}
		
		$uiset = createFieldUI($rkey,$mod->fields[$rkey],$value,$picklist,$associateTo,$oper);
		$smarty->assign('FIELDINFO',$uiset);
		$outform[$key] = $smarty->fetch("UI/{$mod->fields[$rkey][0]}.UI.tpl");
	}	
}

foreach($form['AUTO'] as $key => $v)
{
	$outform[$key] = '';
	if(is_array($v)) continue;
	
	
	$rkey = "form[AUTO][{$key}]";

	if(array_key_exists($rkey,$mod->fields))
	{
		$value=null;
		$picklist=array();
		$associateTo=null;
		if(array_key_exists($key,$data['AUTO']))
		{
			$value = $data['AUTO'][$key];
		}
		
		if(array_key_exists($rkey,$mod->picklist))
		{
			$picklist = $mod->picklist[$rkey];
		}
		
		if(array_key_exists($rkey,$mod->associateTo))
		{
			$associateTo = $mod->associateTo[$rkey];
		}
		
		$uiset = createFieldUI($rkey,$mod->fields[$rkey],$value,$picklist,$associateTo,$oper);
		$smarty->assign('FIELDINFO',$uiset);
		$outform[$key] = $smarty->fetch("UI/{$mod->fields[$rkey][0]}.UI.tpl");
	}	
}

foreach($form['POLICY'] as $key => $v)
{
	$outform[$key] = '';
	if(is_array($v)) continue;
	
	
	$rkey = "form[POLICY][{$key}]";
	
	if(array_key_exists($rkey,$mod->fields))
	{
		$value=null;
		$picklist=array();
		$associateTo=null;
		if(array_key_exists($key,$data['POLICY']))
		{
			$value = $data['POLICY'][$key];
		}
		
		if(array_key_exists($rkey,$mod->picklist))
		{
			$picklist = $mod->picklist[$rkey];
		}
		
		if(array_key_exists($rkey,$mod->associateTo))
		{
			$associateTo = $mod->associateTo[$rkey];
		}
		
		$uiset = createFieldUI($rkey,$mod->fields[$rkey],$value,$picklist,$associateTo,$oper);
		$smarty->assign('FIELDINFO',$uiset);
		$outform[$key] = $smarty->fetch("UI/{$mod->fields[$rkey][0]}.UI.tpl");
	}	
}

foreach($form['OTHER'] as $key => $v)
{
	$outform[$key] = '';
	if(is_array($v)) continue;

	
	$rkey = "form[OTHER][{$key}]";

	if(array_key_exists($rkey,$mod->fields))
	{
		$value=null;
		$picklist=array();
		$associateTo=null;
		if(array_key_exists($key,$data['OTHER']))
		{
			$value = $data['OTHER'][$key];
		}
		
		if(array_key_exists($rkey,$mod->picklist))
		{
			$picklist = $mod->picklist[$rkey];
		}
		
		if(array_key_exists($rkey,$mod->associateTo))
		{
			$associateTo = $mod->associateTo[$rkey];
		}
		
		$uiset = createFieldUI($rkey,$mod->fields[$rkey],$value,$picklist,$associateTo,$oper);

		$smarty->assign('FIELDINFO',$uiset);
		$outform[$key] = $smarty->fetch("UI/{$mod->fields[$rkey][0]}.UI.tpl");
	}	
}

$smarty->assign('form',$outform);


$business_items = array();
if(array_key_exists('BUSINESS_ITEMS',$data)) $business_items = json_encode($data['BUSINESS_ITEMS']);
$designated_driver1 = 'NO';
if(array_key_exists('DESIGNATED_DRIVER1',$data)) $designated_driver1 = 'YES';
$designated_driver2 = 'NO';
if(array_key_exists('DESIGNATED_DRIVER2',$data)) $designated_driver2 = 'YES';
$designated_driver3 = 'NO';
if(array_key_exists('DESIGNATED_DRIVER3',$data)) $designated_driver3 = 'YES';
$mvtalci_select = 'NO';
if(array_key_exists('MVTALCI_SELECT',$data)) $mvtalci_select = 'YES';

$smarty->assign('BUSINESS_ITEMS',$business_items);
$smarty->assign('DESIGNATED_DRIVER1',$designated_driver1);
$smarty->assign('DESIGNATED_DRIVER2',$designated_driver2);
$smarty->assign('DESIGNATED_DRIVER3',$designated_driver2);
$smarty->assign('MVTALCI_SELECT',$mvtalci_select);

$smarty->assign('recordid',$recordid);
$smarty->assign('operation',$oper);


$smarty->display('PolicyCalculate/Policy.tpl');
?>