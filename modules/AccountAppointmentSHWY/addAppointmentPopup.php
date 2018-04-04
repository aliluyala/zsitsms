<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$smarty->assign('MODULE',$module);
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();	
}
$accountid = -1;
if(!empty($_GET['accountid'])) $accountid = $_GET['accountid'];

$recordid = getNewModuleSeq($mod->baseTable);
$appointment_time = createFieldUI('appointment_time',$mod->fields['appointment_time'],null,null,Array());
$remark = createFieldUI('remark',$mod->fields['remark'],null,null,Array());
$smarty->assign('ACCOUNTID',$accountid);
$smarty->assign('RECORDID',$recordid);
$smarty->assign('APPOINTMENT_TIME_FIELD',$appointment_time);
$smarty->assign('REMARK_FIELD',$remark);
$smarty->display('{$module}/AddAppointmentPopup.tpl');
?>