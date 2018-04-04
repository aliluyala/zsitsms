<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();
$isapi = require(_ROOT_DIR."/config/isapi.conf.php");
if(empty($isapi) || !is_array($isapi))
{
	echo '接口配置文件未配置！';
	die();
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
$Insurance_pc = $qidianSdk->getCliectSdk('Insurance',array(),'GET');
if(!$Insurance_pc)
{
	$error = $qidianSdk->getErrorMessage();
	return_ajax('error',$error);
	die();
}
apc_store('Insurance', $Insurance_pc, '600');//设置token缓存

if($Insurance_pc['code'] == 0 && $Insurance_pc['describe'] == 'success')
{
	return_ajax('success',1);
	die();
}
else
{
	return_ajax('error',$Insurance_pc['describe']);
	die();
}
