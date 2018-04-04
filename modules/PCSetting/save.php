<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','你的权限不能进行该项操作！');
	die();
}
global $mod;
if(!isset($mod) && is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();
}
$cachepath = _ROOT_DIR.'/cache/sms_tpl';
if(!is_dir($cachepath))
{
	mkdir($cachepath,0777,true);
}
$tpl = $cachepath.'/policy_sms.tpl';
file_put_contents($tpl,str_replace("'",'"',$_POST['sms_tpl']));
$tpl1 = $cachepath.'/short_sms_before.tpl';
file_put_contents($tpl1, str_replace("'",'"',$_POST['short_sms_before']));
$tpl2 = $cachepath.'/short_sms_after.tpl';
file_put_contents($tpl2, str_replace("'",'"',$_POST['short_sms_after']));


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


/**新增接口更改客户端账号**/
$Insurances = $qidianSdk->getCliectSdk('Insurance',array(),'GET');
if(!$Insurances)
{
	$error = $qidianSdk->getErrorMessage();
	return_ajax('error','保存失败,'.$error);
	die();
}

$setDefaults['insurance'] = $_POST['default_api'];
$default_result = $qidianSdk->getCliectSdk('Insurance.setDefault',$setDefaults,'POST');

if(!$default_result)
{
	$error = $qidianSdk->getErrorMessage();
	return_ajax('error','保存失败,'.$error);
	die();
}
if(empty($default_result) || $default_result['code'] > 0)
{
	return_ajax('error','保存失败,'.$default_result['describe']);
	die();
}

$_POST['sms_tpl'] = urlencode(str_replace("'",'"',$_POST['sms_tpl']));
$where['default_api']=$_POST['default_api'];
$where['allow_apis']= "";
$where['api_setitems']= array();
$where['sms_tpl']=$_POST['sms_tpl'];
$where['INSURANCES']=$_POST['INSURANCES'];
$where['short_sms_before'] = urlencode(str_replace("'",'"',$_POST['short_sms_before']));
$where['short_sms_after'] = urlencode(str_replace("'",'"',$_POST['short_sms_after']));


$result= json_encode($where);
$state_result= $APP_ADODB->Execute("select * from policy_calculate_setting");
if(!empty($state_result->fields))
{
	if($CURRENT_USER_GROUPID!=-1)//如果不是管理員
	{
			return_ajax('error','只有管理员才能修改通用算价');
			die();
	}
	else
	{
		$results = $APP_ADODB->Execute("update policy_calculate_setting set setting='".$result."'");
	}
}
else
{
	$results = $APP_ADODB->Execute("insert into policy_calculate_setting (setting) values('".$result."')");

}


if(!$results)
{
	return_ajax('error','保存失败！');
	die();
}
return_ajax('success','保存成功！');
?>