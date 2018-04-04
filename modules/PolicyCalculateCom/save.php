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
$summary  = "{$_POST['form']['AUTO']['LICENSE_NO']},";
$summary .= "商业险{$_POST['form']['POLICY']['TOTAL_BUSINESS_PREMIUM']}元,";
$summary .= "交强险{$_POST['form']['POLICY']['TOTAL_MVTALCI_PREMIUM']}元,";
$summary .= "车船税{$_POST['form']['POLICY']['TRAVEL_TAX_PREMIUM']}元,";
$summary .= "总计{$_POST['form']['POLICY']['TOTAL_PREMIUM']}元。";

foreach($_POST['form']['HOLDER'] as $key => $val )
{
	$_POST['form']['HOLDER'][$key] =  urlencode($val);
}

foreach($_POST['form']['INSURANT'] as $key => $val )
{
	$_POST['form']['INSURANT'][$key] =  urlencode($val);
}

foreach($_POST['form']['AUTO'] as $key => $val )
{
	$_POST['form']['AUTO'][$key] =  urlencode($val);
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
	$recordid = getNewModuleSeq('policy_draft_com');
	$result = $mod->insertOneRecordset($recordid,$save_datas);
}

return_ajax('success',$recordid);
?>



