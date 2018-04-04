<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();

 if(array_key_exists('vin',$_GET))
 {
 	$vin = $_GET['vin'];
 }
 if(array_key_exists('license_no',$_GET))
 {
 	$license_no = $_GET['license_no'];
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

$data['VIN_NO']=$vin;
$data['LICENSE_NO']=$license_no;
$list = $pc->checkcode($data);

$check = json_decode($list,true);


if(!array_key_exists('checkcode',$check) || $check['checkno'] != '')
{
	 	$datas['checkcode']=str_replace("／", "/", $check['checkcode']);
	 	$datas['checkno']=$check['checkno'];
	 	return_ajax("success",$datas);
}


?>