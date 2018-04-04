<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$module_label =  getTranslatedString(_MODULE);
$action_label =  getTranslatedString(_ACTION);

$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);

if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error',"你的权限不能进行该操作！");
    die();
}

require_once(_ROOT_DIR.'/common/sms/smsUtils.php');
if(empty($_POST['calleeid']))
{
	return_ajax('error',"你没有填写接收号码！<br/>如果有多个号码，请用逗号、分号或空格分隔。");
	die();
}
if(empty($_POST['content']))
{
	return_ajax('error',"短信内容为空！");
	die();
}
$_POST['calleeid'] = trim($_POST['calleeid'] );
$calleeids = preg_split('/[\s,;]/',$_POST['calleeid']);
if(isset($_POST['caculate_data'])){
	if(count($calleeids)>1){
		return_ajax('error',"短链接报价短信不能一次发送多个号码");
		die();
	}
	$caculate_data_arr = json_decode($_POST['caculate_data'],true);
	if(intval($caculate_data_arr['premium_result']['TOTAL_PREMIUM'])<=0){
		return_ajax('error',"未成功算价，不能发送短链接短信");
		die();
	}
	
	$result = shortSubmitSMS($calleeids[0],$_POST);
	if($result['result'] == false){
		return_ajax('error',$result['message']);
		die();
	}	
	
	return_ajax('success',$result['message']);
	die();
}else{
	foreach($calleeids as $ce)
	{
		submitSMS($ce,$_POST['content']);	
	}
	return_ajax('success',"接交成功！");
	die();
}
?>