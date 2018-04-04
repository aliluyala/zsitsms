<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();

 if(array_key_exists('checkcode',$_GET))
 {
 	$checkcode = $_GET['checkcode'];
 }
 if(array_key_exists('checkno',$_GET))
 {
 	$checkno = $_GET['checkno'];
 }

 if(array_key_exists('vin_no',$_GET))
 {
 	$vin_no = $_GET['vin_no'];
 }

 $rate_table = '';
 if(array_key_exists('rate_table',$_GET))
 {
 	$rate_table = $_GET['rate_table'];
 }


 $pc = $mod->createPCObj($rate_table);
 if(!$pc)
 {
 	$smarty->assign('ERROR_MESSAGE','算价器设置错误,请在在“设置”>>“算价器设置”中重新设置！');
 	$smarty->display('ErrorMessage.tpl');
 	die();
 }

$data['checkcode']=$checkcode;
$data['checkno']=$checkno;
$data['vin_no']=$vin_no;
$list = $pc->check_data($data);

if(!$list)
{
	$error = $pc->getLastError();
	return_ajax("error",$error['errorMsg']);
	die();
}
$app= json_decode($list,true);
return_ajax("success",$app);





?>