<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();

$smarty->assign("MODULE",$module);
$recordid = '';
$accountid ='';
if(!empty($_GET['recordid'])) $recordid = $_GET['recordid'];

$pcconf = $mod->getPCConfig();

$set = $mod->getPCSetting();

if(empty($set))
{
	$smarty->assign('ERROR_MESSAGE','算价器设置错误,请在“设置”>>“算价器设置”中重新设置！');
	$smarty->display('ErrorMessage1.tpl');
	die();
}
$allow_apis = $set['allow_apis'];
$allow_insurances = $set['INSURANCES'];


if(isset($_GET['rate_table']) && !empty($_GET['rate_table'])){
	$default_api = $_GET['rate_table'];
}else{
	$default_api = $set['default_api'];
}
$currApi = $default_api;
$startCalculate = false;


if(isset($_POST['form']['OTHER']['PREMIUM_RATE_TABLE']))
{
	$currApi = $_POST['form']['OTHER']['PREMIUM_RATE_TABLE'];
	$startCalculate = true;
}

$smarty->assign('START_CALCULATE',$startCalculate);
/*$pc = $mod->createPCObj($currApi);
$formFile = $pc->getFormFile();*/
$apiPicklist = array();
$formFile = "Calculate.tpl";

global $qidianSdk;
if(is_file(_ROOT_DIR."/webservices/qiDianapi.class.php"))
{
	require_once(_ROOT_DIR."/webservices/qiDianapi.class.php");
	$qidianmod= "qiDianapi";
	$qidianSdk = new $qidianmod();
}
else
{
	$smarty->assign('ERROR_MESSAGE','系统发生错误');
	$smarty->display('ErrorMessage1.tpl');
	die();
}


/**
 * [$insurant_url 通过接口获取保险公司账号]
 * @var [type]
 */
$Insurance_pc = apc_fetch('Insurance');
if(empty($Insurance_pc))
{

	$Insurance_pc = $qidianSdk->getCliectSdk('Insurance');
	if(!$Insurance_pc)
	{
		$errmessage = $qidianSdk->getErrorMessage();
		$smarty->assign('ERROR_MESSAGE',$errmessage);
		$smarty->display('ErrorMessage1.tpl');
		die();
	}
	apc_store('Insurance', $Insurance_pc, '600');//设置token缓存
}
foreach($Insurance_pc['data'] as $key => $val)
{
		appendModuleStrings(array($val['code']=>$val['name']));
		$apiPicklist[] = $val['code'];
}
$mod->picklist['form[OTHER][PREMIUM_RATE_TABLE]'] = $apiPicklist;

$form = array();
$form['NO'] = '';
$form['OTHER']['PREMIUM_RATE_TABLE']='';
$form['OTHER']['INSURANCE_COMPANY']='';

$form['OTHER']['DZA_DEMANDNOS']='';
$form['OTHER']['DZA_CHECKCODES']='';
$form['OTHER']['DAA_DEMANDNOS']='';
$form['OTHER']['DAA_CHECKCODES']='';




$form['HOLDER']['HOLDER'] = '';
$form['HOLDER']['HOLDER_IDENTIFY_TYPE'] = '';
$form['HOLDER']['HOLDER_ADDRESS'] = '';
$form['HOLDER']['HOLDER_IDENTIFY_NO'] = '';
$form['INSURANT']['INSURANT'] = '';
$form['INSURANT']['INSURANT_IDENTIFY_TYPE'] = '';
$form['INSURANT']['INSURANT_ADDRESS'] = '';
$form['INSURANT']['INSURANT_IDENTIFY_NO'] = '';

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
$form['AUTO']['TONNAGE']='';
$form['AUTO']['MOBILE']='';
$form['AUTO']['INDUSTY_MODEL_CODE']='';
$form['AUTO']['INDUSTY_CODE']='';
$form['AUTO']['DISCOUNT_PRICE']='';
$form['AUTO']['MODEL_ALIAS']='';//新增车型别名

$form['POLICY']['TOTAL_PREMIUM']='';
$form['POLICY']['TOTAL_STANDARD_PREMIUM']='';
$form['POLICY']['TOTAL_BUSINESS_PREMIUM'] = '';
$form['POLICY']['TOTAL_MVTALCI_PREMIUM'] ='';
$form['POLICY']['TOTAL_TRAVEL_TAX_PREMIUM'] ='';



$form['POLICY']['TRAVEL_TAX_PREMIUM']='';
$form['POLICY']['MVTALCI_SELECT']='';
$form['POLICY']['MVTALCI_PREMIUM']='';
$form['POLICY']['MVTALCI_DISCOUNT']='';
$form['POLICY']['FLOATING_RATE']='';
$form['POLICY']['MVTALCI_START_TIME']='';
$form['POLICY']['MVTALCI_END_TIME']='';

$form['POLICY']['BUSINESS_PREMIUM']='';
$form['POLICY']['BUSINESS_DISCOUNT']='';
$form['POLICY']['BUSINESS_CUSTOM_DISCOUNT']='';
$form['POLICY']['BUSINESS_DISCOUNT_PREMIUM']='';
$form['POLICY']['TOTAL_DEDUCTIBLE']='';
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
$form['POLICY']['NIELI_DEVICE_LIST']='';
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
$form['POLICY']['VWTLI_INSURANCE_AMOUNT']='';
$form['POLICY']['VWTLI_PREMIUM']='';
$form['POLICY']['VWTLI_DISCOUNT_PREMIUM']='';
$form['POLICY']['VWTLI_NDSI_PREMIUM']='';
$form['POLICY']['VWTLI_NDSI_DISCOUNT_PREMIUM']='' ;

$form['POLICY']['RDCCI_PREMIUM']='' ;
$form['POLICY']['RDCCI_DISCOUNT_PREMIUM']='';


$form['POLICY']['RDCCI_INSURANCE_UNIT']='';
$form['POLICY']['RDCCI_INSURANCE_QUANTITY']='';
$form['POLICY']['RDCCI_INSURANCE_AMOUNT']='';


$form['POLICY']['MVLINFTPSI_PREMIUM']=''    ;
$form['POLICY']['MVLINFTPSI_DISCOUNT_PREMIUM']=''  ;

$form['POLICY']['TTBLI_DOUBLE_PREMIUM']=''    ;//新增第三方节假日翻倍险


$form['POLICY']['BUSINESS']='';

$outform  = array();

$data = array(
		'HOLDER'   => array(),
		'INSURANT' => array(),
		'AUTO'     => array(),
		'POLICY'   => array(),
		'OTHER'    => array(),
        );

$data['OTHER']['PREMIUM_RATE_TABLE'] = $currApi;

if(!empty($recordid))
{
	$oper = 'edit';
	$reset = $mod->getOneRecordset($recordid,null,null);
	if(!empty($reset[0]['content']))
	{
		$data  = json_decode($reset[0]['content'],true);
	}
	foreach($data['HOLDER'] as $key => $val )
	{
		$data['HOLDER'][$key] =  urldecode($val);
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
		$refer = $mod->getPolicyRefer($_GET['vin_no']);

		if($refer)
		{
			$data['BUSINESS_ITEMS'] 	= $refer['items'];
			if(isset($refer['auto_info']['model_code']) && $refer['auto_info']['model_code'] != "")
			{
				$data['AUTO']['MODEL_CODE'] = $refer['auto_info']['model_code'];
			}
			if(isset($refer['auto_info']['buying_price']) && $refer['auto_info']['buying_price'] != "")
			{
				$data['AUTO']['BUYING_PRICE'] = $refer['auto_info']['buying_price'];
			}
			$data['POLICY']['MVTALCI_START_TIME'] = $refer['start_time'];
			$data['POLICY']['BUSINESS_START_TIME'] = $refer['start_time'];
		}

		if(!empty($pcconf['vehicle']['module']) && !empty($pcconf['vehicle']['fieldmap']['VIN_NO']))
		{
			$accmodname =  $pcconf['vehicle']['module'];
			$accmodclass = $accmodname.'Module';
			@include(_ROOT_DIR."/modules/{$accmodname}/{$accmodclass}.class.php");
			if(class_exists($accmodclass))
			{
				$accmod = new $accmodclass();
				if(isset($_GET['accountid']) && !empty($_GET['accountid'])){
					$accountid = $_GET['accountid'];
					$wh = array(
				         array($pcconf['vehicle']['fieldmap']['VIN_NO'],'=',$_GET['vin_no'],'and'),
						 array('id','=',$_GET['accountid'])
					  );
				}else{
					$wh = array(array($pcconf['vehicle']['fieldmap']['VIN_NO'],'=',$_GET['vin_no'],''));
				}
				$rset = $accmod->getListQueryRecord($wh,array(),null,null,null,null,0,1);
			}
		}

		if(!empty($rset))
		{

			$fm = $pcconf['vehicle']['fieldmap'];
			$r = $rset[0];

			if(array_key_exists('MOBILE',$fm) && array_key_exists($fm['MOBILE'],$r))
			{
				$data['AUTO']['MOBILE'] = $r[$fm['MOBILE']]  ;
			}

			if(array_key_exists('ADDRESS',$fm) && array_key_exists($fm['ADDRESS'],$r))
			{
				$data['HOLDER']['HOLDER_ADDRESS'] = $r[$fm['ADDRESS']]  ;
				$data['INSURANT']['INSURANT_ADDRESS'] = $r[$fm['ADDRESS']]  ;
			}

			if(array_key_exists('IDENTIFY_NO',$fm) && array_key_exists($fm['IDENTIFY_NO'],$r))
			{
				$data['HOLDER']['HOLDER_IDENTIFY_NO'] = $r[$fm['IDENTIFY_NO']]  ;
				$data['INSURANT']['INSURANT_IDENTIFY_NO'] = $r[$fm['IDENTIFY_NO']]  ;
			}
			if(array_key_exists('DZA_DEMANDNOS',$fm) && array_key_exists($fm['DZA_DEMANDNOS'],$r))
			{
				$data['HOLDER']['DZA_DEMANDNOS'] = $r[$fm['DZA_DEMANDNOS']]  ;
				$data['HOLDER']['DZA_CHECKCODES'] = $r[$fm['DZA_CHECKCODES']]  ;
			}

			if(array_key_exists('DAA_DEMANDNOS',$fm) && array_key_exists($fm['DAA_DEMANDNOS'],$r))
			{
				$data['HOLDER']['DAA_DEMANDNOS'] = $r[$fm['DAA_DEMANDNOS']]  ;
				$data['HOLDER']['DAA_CHECKCODES'] = $r[$fm['DAA_CHECKCODES']]  ;
			}

			if(array_key_exists('DZA_DEMANDNOS',$fm) && array_key_exists($fm['DZA_DEMANDNOS'],$r))
			{
				$data['HOLDER']['DZA_DEMANDNOS'] = $r[$fm['DZA_DEMANDNOS']]  ;
				$data['HOLDER']['DZA_CHECKCODES'] = $r[$fm['DZA_CHECKCODES']]  ;
			}

			if(array_key_exists('DZA_DEMANDNOS',$fm) && array_key_exists($fm['DZA_DEMANDNOS'],$r))
			{
				$data['HOLDER']['DZA_DEMANDNOS'] = $r[$fm['DZA_DEMANDNOS']]  ;
				$data['HOLDER']['DZA_CHECKCODES'] = $r[$fm['DZA_CHECKCODES']]  ;
			}


			if(array_key_exists('LICENSE_NO',$fm) && array_key_exists($fm['LICENSE_NO'],$r))
			{
				$data['AUTO']['LICENSE_NO']      = $r[$fm['LICENSE_NO']]      ;
			}
			if(array_key_exists('LICENSE_TYPE',$fm) && array_key_exists($fm['LICENSE_TYPE'],$r))
			{
				$data['AUTO']['LICENSE_TYPE']      = $r[$fm['LICENSE_TYPE']]      ;
			}
			if(array_key_exists('OWNER',$fm) && array_key_exists($fm['OWNER'],$r))
			{
				$data['AUTO']['OWNER']        = $r[$fm['OWNER']]      ;
				$data['HOLDER']['HOLDER']     = $r[$fm['OWNER']]      ;
				$data['INSURANT']['INSURANT'] = $r[$fm['OWNER']]      ;
			}
			if(array_key_exists('VIN_NO',$fm) && array_key_exists($fm['VIN_NO'],$r))
			{
				$data['AUTO']['VIN_NO']      = $r[$fm['VIN_NO']]      ;
			}

			if(array_key_exists('MODEL_ALIAS',$fm) && array_key_exists($fm['MODEL_ALIAS'],$r))
			{
				$data['AUTO']['MODEL_ALIAS']      = $r[$fm['MODEL_ALIAS']]      ;
			}

			if(array_key_exists('ENGINE_NO',$fm) && array_key_exists($fm['ENGINE_NO'],$r))
			{
				$data['AUTO']['ENGINE_NO']      = $r[$fm['ENGINE_NO']]      ;
			}
			if(array_key_exists('MODEL',$fm) && array_key_exists($fm['MODEL'],$r))
			{
				$data['AUTO']['MODEL']      = $r[$fm['MODEL']]      ;
			}
			if(array_key_exists('ENGINE',$fm) && array_key_exists($fm['ENGINE'],$r))
			{
				$data['AUTO']['ENGINE']      = $r[$fm['ENGINE']]      ;
				if($data['AUTO']['ENGINE']>50)
				{
					$data['AUTO']['ENGINE'] = sprintf('%.3f',$data['AUTO']['ENGINE']/1000);
				}
			}
			if(array_key_exists('SEATS',$fm) && array_key_exists($fm['SEATS'],$r))
			{
				$data['AUTO']['SEATS']      = $r[$fm['SEATS']]      ;
				if($data['AUTO']['SEATS']>1)
				{
					$data['POLICY']['TCPLI_PASSENGER_COUNT'] = $data['AUTO']['SEATS']-1;
				}
			}
			if(array_key_exists('KERB_MASS',$fm) && array_key_exists($fm['KERB_MASS'],$r))
			{
				$data['AUTO']['KERB_MASS']      = $r[$fm['KERB_MASS']]      ;
			}
			if(array_key_exists('INDUSTY_MODEL_CODE',$fm) && array_key_exists($fm['INDUSTY_MODEL_CODE'],$r))
			{
				$data['AUTO']['INDUSTY_MODEL_CODE']      = $r[$fm['INDUSTY_MODEL_CODE']]      ;
			}
			if(array_key_exists('INDUSTY_CODE',$fm) && array_key_exists($fm['INDUSTY_CODE'],$r))
			{
				$data['AUTO']['INDUSTY_CODE']      = $r[$fm['INDUSTY_CODE']]      ;
			}

			if(array_key_exists('DISCOUNT_PRICE',$fm) && array_key_exists($fm['DISCOUNT_PRICE'],$r))
			{
				$data['AUTO']['DISCOUNT_PRICE']      = $r[$fm['DISCOUNT_PRICE']]      ;
			}
			if(array_key_exists('TONNAGE',$fm) && array_key_exists($fm['TONNAGE'],$r))
			{
				$data['AUTO']['TONNAGE']      = $r[$fm['TONNAGE']]      ;
			}
			if(array_key_exists('MODEL_CODE',$fm) && array_key_exists($fm['MODEL_CODE'],$r))
			{
				$data['AUTO']['MODEL_CODE']      = $r[$fm['MODEL_CODE']]      ;
			}
			if(array_key_exists('BUYING_PRICE',$fm) && array_key_exists($fm['BUYING_PRICE'],$r))
			{
				$data['AUTO']['BUYING_PRICE']      =  empty($refer['auto_info']['buying_price'])?'':$refer['auto_info']['buying_price'];
			}
			if(array_key_exists('ENROLL_DATE',$fm) && array_key_exists($fm['ENROLL_DATE'],$r))
			{
				$data['AUTO']['ENROLL_DATE']      = $r[$fm['ENROLL_DATE']]      ;
			}
			if(array_key_exists('VEHICLE_TYPE',$fm) && array_key_exists($fm['VEHICLE_TYPE'],$r))
			{
				$data['AUTO']['VEHICLE_TYPE']      = $r[$fm['VEHICLE_TYPE']]      ;
			}
			if(array_key_exists('USE_CHARACTER',$fm) && array_key_exists($fm['USE_CHARACTER'],$r))
			{
				$data['AUTO']['USE_CHARACTER']      = $r[$fm['USE_CHARACTER']]      ;
			}
			if(array_key_exists('ORIGIN',$fm) && array_key_exists($fm['ORIGIN'],$r))
			{
				$data['AUTO']['ORIGIN']      = $r[$fm['ORIGIN']]      ;
			}

			$data['POLICY']['TVDI_INSURANCE_AMOUNT'] = '';

		}

	}

}

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
		if(isset($_GET['rate_table']) && $_GET['rate_table'] != "")
		{
			$value = $_GET['rate_table'];
		}
		else
		{

			foreach($Insurance_pc['data'] as $keys => $vals)
			{
				if($vals['default'])
				{
					$value = $vals['code'];
				}
			}
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



foreach($form['HOLDER'] as $key => $v)
{
	$outform[$key] = '';
	if(is_array($v)) continue;


	$rkey = "form[HOLDER][{$key}]";

	if(array_key_exists($rkey,$mod->fields))
	{
		$value=null;
		$picklist=array();
		$associateTo=null;
		if(!empty($data['HOLDER']) && array_key_exists($key,$data['HOLDER']))
		{
			$value = $data['HOLDER'][$key];
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


	$rkey = "form[INSURANT][{$key}]";

	if(array_key_exists($rkey,$mod->fields))
	{
		$value=null;
		$picklist=array();
		$associateTo=null;
		if(!empty($data['INSURANT']) && array_key_exists($key,$data['INSURANT']))
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
		$associateTo=null;

		if(array_key_exists($key,$data['OTHER']))
		{
			$value = $data['OTHER'][$key];
		}

		if(array_key_exists($rkey,$mod->associateTo))
		{
			$associateTo = $mod->associateTo[$rkey];
		}

		$uiset = createFieldUI($rkey,$mod->fields[$rkey],$value,$apiPicklist,$associateTo,$oper);

		$smarty->assign('FIELDINFO',$uiset);
		$outform[$key] = $smarty->fetch("UI/{$mod->fields[$rkey][0]}.UI.tpl");
	}
}

$smarty->assign('form',$outform);
$selected_insurances = array();
if(array_key_exists('BUSINESS_ITEMS',$data)) $selected_insurances = $data['BUSINESS_ITEMS'];
if(array_key_exists('MVTALCI_SELECT',$data)) $selected_insurances[] = 'MVTALCI';
//print_R($data);

$designated_driver = array();
if(array_key_exists('DESIGNATED_DRIVER1',$data)) $designated_driver[] = '1';
if(array_key_exists('DESIGNATED_DRIVER2',$data)) $designated_driver[] = '2';
if(array_key_exists('DESIGNATED_DRIVER3',$data)) $designated_driver[] = '3';

$smarty->assign('MODEL',$pcconf['model']);
$smarty->assign('ALLOW_INSURANCES',json_encode($allow_insurances));
$smarty->assign('SELECTED_INSURANCES',json_encode($selected_insurances));
$smarty->assign('DESIGNATED_DRIVER',json_encode($designated_driver));

$smarty->assign('recordid',$recordid);
$smarty->assign('accountid',$accountid);
$smarty->assign('operation',$oper);
$smarty->display("{$module}/{$formFile}");
?>