<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','你的权限不能进行该项操作！');
	die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();	
}

$cal_no   = $_POST['form']['NO'];
$vin_no   = $_POST['form']['AUTO']['VIN_NO'];
$summary  = "商业险{$_POST['form']['POLICY']['TOTAL_BUSINESS_PREMIUM']}元,";
$summary .= "交强险{$_POST['form']['POLICY']['TOTAL_MVTALCI_PREMIUM']}元,";
$summary .= "车船税{$_POST['form']['POLICY']['TOTAL_TRAVEL_TAX_PREMIUM']}元,";
$summary .= "总计{$_POST['form']['POLICY']['TOTAL_PREMIUM']}元。";


foreach($_POST['form']['INSURANT'] as $key => $val )
{
	$_POST['form']['INSURANT'][$key] =  urlencode($val);
}

$updatesql = '';
if(!empty($vin_no ))
{
	$updatesql = 'update accounts_shwy set ';
}
$updatesql = '';
$setfieldstr = '';
foreach($_POST['form']['AUTO'] as $key => $val )
{
	if(!empty($updatesql))
	{
		$field = '';
		if($key=='LICENSE_NO')                              
		{                                                           
			$field = "plate_no='{$_POST['form']['AUTO'][$key]}'";                                             
		}                                                  
		elseif($key=='LICENSE_TYPE' )	                                                         
		{
			$field = "plate_no='{$_POST['form']['AUTO'][$key]}'";
		}
		elseif($key=='OWNER')
		{
			$field = "owner='{$_POST['form']['AUTO'][$key]}'";
		}
		elseif($key=='ENGINE_NO')
        {
			$field = "engine_no='{$_POST['form']['AUTO'][$key]}'";
		}		                                          
		elseif($key=='MODEL')
		{
			$field = "model='{$_POST['form']['AUTO'][$key]}'";
		}	                                                      
		elseif($key=='ENGINE')
		{
			$field = "engine={$_POST['form']['AUTO'][$key]}";
		}		
		elseif($key=='SEATS')
		{
			$field = "seats={$_POST['form']['AUTO'][$key]}";
		}																     
		elseif($key=='KERB_MASS')
		{
			$field = "kerb_mass={$_POST['form']['AUTO'][$key]}";
		}		       
		elseif($key=='MODEL_CODE')
		{
			//$field = "model_code={$_POST['form']['AUTO'][$key]}";
		}	
		elseif($key=='BUYING_PRICE')
		{
			$field = "purchase_price={$_POST['form']['AUTO'][$key]}";
		}	
		elseif($key=='ENROLL_DATE')
		{
			$field = "register_date='{$_POST['form']['AUTO'][$key]}'";
		}		
		elseif($key=='VEHICLE_TYPE') 
		{
			$field = "vehicle_type='{$_POST['form']['AUTO'][$key]}'";
		}	
		elseif($key=='USE_CHARACTER')
		{
			$field = "use_character='{$_POST['form']['AUTO'][$key]}'";
		}
		elseif($key=='ORIGIN')
		{
			$field = "origin='{$_POST['form']['AUTO'][$key]}'";
		}
		if(!empty($field))
		{	
			if(empty($setfieldstr))
			{
				$setfieldstr = $field;
			}
			else
			{
				$setfieldstr .= ','.$field;
			}
		}		
	}	
	
	$_POST['form']['AUTO'][$key] =  urlencode($val);	
}

if(!empty($setfieldstr))
{
	$updatesql .= " {$setfieldstr} where vin='{$vin_no}';";
	$APP_ADODB->Execute($updatesql);
}


foreach($_POST['form']['POLICY'] as $key => $val )
{
	$_POST['form']['POLICY'][$key] =  urlencode($val);
}
foreach($_POST['form']['OTHER'] as $key => $val )
{
	$_POST['form']['OTHER'][$key] =  urlencode($val);
}


$content = json_encode($_POST['form']);


$save_datas = array();
$save_datas['cal_no'] = $cal_no;
$save_datas['vin_no'] = $vin_no;           
$save_datas['summary'] = $summary;           
$save_datas['content'] = $content;


$operation = '';
if(isset($_POST['operation']))	$operation = $_POST['operation'];

 

$result = 0;
if($operation == 'edit')
{
	$save_datas['modify_userid'] = $CURRENT_USER_ID;
	$save_datas['modify_time'] = date('Y-m-d H:i:s');
	
	$recordid = $_POST['recordid'];
	$result = $mod->updateOneRecordset($recordid,
							 NULL,
							 NULL,
		                     $save_datas);
}
elseif($operation == 'create' )
{
	$save_datas['associate_userid'] = $CURRENT_USER_ID;
	$save_datas['create_userid'] = $CURRENT_USER_ID;
	$save_datas['modify_time'] =   date('Y-m-d H:i:s');
	$save_datas['modify_userid'] = $CURRENT_USER_ID;
	$recordid = getNewModuleSeq('policy_draft');
	$result = $mod->insertOneRecordset($recordid,$save_datas);
}

return_ajax('success',$recordid);
?>



