<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();

if(empty($_POST['form']['AUTO']['ENROLL_DATE']))
{
	return_ajax('error','没有填写“注册日期”，请填写“注册日期”后再次查询！');
	die();
}
if(empty($_POST['form']['POLICY']['BUSINESS_START_TIME']))
{
	return_ajax('error','没有指定商业险开始时间，请指定商业险开始时间后再次查询！');
	die();
}

$info = $_POST['form']['AUTO'];
$info['BUSINESS_START_TIME'] = $_POST['form']['POLICY']['BUSINESS_START_TIME'];


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

$checkcode = !empty($_GET['checkcode']) ? $_GET['checkcode'] : '';
if(isset($_GET['checkcode']) && $_GET['checkcode'] != "")
{
	$where['verify']['login'] = $_GET['checkcode'];
}


$where['insurance'] = $_POST['form']['OTHER']['PREMIUM_RATE_TABLE'];
$where['purchase_price'] = $_POST['form']['AUTO']['BUYING_PRICE'];
$where['enroll_date'] = $_POST['form']['AUTO']['ENROLL_DATE'];
$where['business_start_date'] = $info['BUSINESS_START_TIME'];
$repreciation = $qidianSdk->getCliectSdk('Depreciation',$where,'POST');
if(!$repreciation)
{
	$errmessage = $qidianSdk->getErrorMessage();
	return_ajax('error',$errmessage);
	die();
}
if($repreciation['code'] > 0)
{
	return_ajax('error',$repreciation['describe']);
	die();
}

if($repreciation['code'] == 0 && $repreciation['describe'] == 'success')
{
	return_ajax('success',$repreciation['data']);
}
?>