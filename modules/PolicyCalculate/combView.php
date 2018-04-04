<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);

$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);

if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}

$vin_no = '';

if(array_key_exists('vin_no',$_GET))
{
	$accid = $_GET['vin_no'];
	$sql = "select * from accounts_shwy where id={$accid};";
	$result = $APP_ADODB->Execute($sql);
	if($result && !$result->EOF)
	{
		$vin_no = $result->fields['vin'];
	}
	
}

$pclist = array();

$sql = "select * from policy_draft where vin_no='{$vin_no}' order by modify_time DESC ;";

$result = $APP_ADODB->Execute($sql);


if($result)
{
	while(!$result->EOF)
	{
		
		$pclist[] = array('ID'=>$result->fields['id'],'CAL_NO'=>$result->fields['cal_no'],'SUMMARY'=>$result->fields['summary'],'MODIFY_TIME'=>$result->fields['create_time']);
		$result->MoveNext();
	}	
}


$smarty->assign('POLICY_CALCULATE_LIST',$pclist);

$smarty->assign('VIN_NO',$vin_no);
$smarty->display('PolicyCalculate/combView.tpl');



?>