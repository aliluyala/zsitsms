<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();
$rate_table = '';
if(array_key_exists('rate_table',$_GET))
{
 	$rate_table = $_GET['rate_table'];
}
if(array_key_exists('checkname',$_GET))
{
 	$checkname = $_GET['checkname'];
}

$pc = $mod->createPCObj($rate_table);

if(!$pc)
{
 	$smarty->assign('ERROR_MESSAGE','算价器设置错误,请在在“设置”>>“算价器设置”中重新设置！');
 	$smarty->display('ErrorMessage.tpl');
 	die();
}



$arr['checkCode']= $checkname;
if($arr['checkCode']=="")
{
	return_ajax("error","请输入验证码");
}


$result= $pc->check_login($arr);

if(!$result)
{
	$error = $pc->getLastError();
	return_ajax("error",$error['errorMsg']);
	die();
}
return_ajax("success",$result);
?>