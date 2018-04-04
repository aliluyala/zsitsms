<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;

if(empty($_GET['deviceList']))
{
	return_ajax('error','参数错误！');
	die();
}

$deviceList = json_decode(urldecode($_GET['deviceList']),true);
if(empty($deviceList))
{
	return_ajax('error','没有设备！');
	die();
}

require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();

$info = array();

if(!isset($_POST['form']['POLICY']['BUSINESS_START_TIME']))
{
	return_ajax('error','没有设定商业险开始时间！');
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
	return_ajax('error','系统发生错误');
	die();
}

foreach($deviceList as $key =>$val)
{
	foreach($val as $k => $v)
	{
		$lowerDeviceList[$key][strtolower($k)] = $v;
	}
}

$info['device_list'] = $lowerDeviceList;
$info['insurance'] = $_POST['form']['OTHER']['PREMIUM_RATE_TABLE'];
$info['business_start_date'] = $_POST['form']['POLICY']['BUSINESS_START_TIME'];
$repreciation = $qidianSdk->getCliectSdk('deviceDepreciation',$info,'POST');
if(!$repreciation)
{
	$errmessage = $qidianSdk->getErrorMessage();
	return_ajax('error',$errmessage);
	die();
}
if($repreciation['code'] == 0 && $repreciation['describe'] == 'success')
{
	foreach($repreciation['data'] as $key =>$val)
	{
		foreach($val as $k => $v)
		{
			$lowerDeviceList[$key][strtoupper($k)] = $v;
		}
	}
	return_ajax('success',$lowerDeviceList);
}

?>