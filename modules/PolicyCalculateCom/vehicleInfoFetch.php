<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();
$model = '';
if(array_key_exists('model',$_GET))
{
	$model = $_GET['model'];
}
$rate_table = '';
if(array_key_exists('rate_table',$_GET))
{
	$rate_table = $_GET['rate_table'];
}
$vin = '';
if(array_key_exists('vin_no',$_GET))
{
	$vin = $_GET['vin_no'];
}
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

if(isset($_GET['vintype']) && $_GET['vintype'] == 0)
{
	$parmas['vin'] = $vin;
	$vin_result = $qidianSdk->getCliectSdk('vehicleInfo.fetch',$parmas,'POST');
	if(!$vin_result)
	{
		$errmessage = $qidianSdk->getErrorMessage();
		return_ajax('error',$errmessage);
		die();
	}
	return_ajax('success',$vin_result['data']);
	die();
}



?>