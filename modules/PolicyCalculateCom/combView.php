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
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$modclass = "{$module}Module";
$mod = new $modclass();

$api = '';
if(isset($_GET['rate_table']) && !empty($_GET['rate_table'])){
	$api = $_GET['rate_table'];
}
$pcconf = $mod->getPCConfig($api);
$accountid = '';
if(!empty($_GET['accountid'])){
	$accountid = $_GET['accountid'];
}
$vin_no = '';
if(!empty($_GET['vin_no']))
{
	$vin_no = $_GET['vin_no'];
}
elseif(!empty($_GET['accountid']) && !empty($pcconf['vehicle']['module']) && !empty($pcconf['vehicle']['fieldmap']['VIN_NO']))
{
	$accmodname =  $pcconf['vehicle']['module'];
	$accmodclass = "{$accmodname}Module";
	$vinFiled = $pcconf['vehicle']['fieldmap']['VIN_NO'];
	include_once(_ROOT_DIR."/modules/{$accmodname}/{$accmodclass}.class.php");
	if(class_exists($accmodclass))
	{
		$accmod = new $accmodclass();
		$record = $accmod->getOneRecordset($_GET['accountid'],null,null);
		if(!empty($record[0][$vinFiled]))
		{
			$vin_no = $record[0][$vinFiled];
		}
	}
}
/*
$pclist = array();
$sql = "select * from {$mod->baseTable} where vin_no='{$vin_no}' order by modify_time DESC ;";
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
*/
$smarty->assign('VIN_NO',$vin_no);
$smarty->assign('ACCOUNTID',$accountid);
$smarty->display("{$module}/combView.tpl");



?>