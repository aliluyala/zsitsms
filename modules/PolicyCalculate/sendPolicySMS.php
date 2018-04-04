<?php
error_reporting(E_ALL);
ini_set( 'display_errors', 'On' );
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;

$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','发送短信失败！你的权限不能进行该项操作。');
	die();
}

$smstpl = require(_ROOT_DIR."/config/sms_tpl.conf.php");

if(empty($smstpl) || !array_key_exists('policy_calculate_smstpl',$smstpl) || empty($smstpl['policy_calculate_smstpl']))
{
	
	return_ajax('error','发送短信失败！没有发现短信模板，请配置短信模板。');
	die();
}

if(!array_key_exists('form',$_POST))
{
	
	return_ajax('error','发送短信失败！没有提供保单信息。');
	die();
}

$form = $_POST['form'];

$form['AUTO']['LICENSE_NO'] = substr($form['AUTO']['LICENSE_NO'],-4);


if($form['POLICY']['TTBLI_INSURANCE_AMOUNT'] == '100+')
{
	$form['POLICY']['TTBLI_INSURANCE_AMOUNT'] = $form['POLICY']['TTBLI_INSURANCE_AMOUNT_EXT'];
}


$vin_no = $form['AUTO']['VIN_NO'];
$sql = "select * from accounts_shwy where vin='{$vin_no}';";
$result = $APP_ADODB->Execute($sql);
$mobile = '';
if(!$result || !$result->EOF)
{
	$mobile = $result->fields['mobile'] ;
}

if(empty($mobile))
{	
	return_ajax('error','发送短信失败！客户手机号码不正确，请检查客户档案。');
	die();
}
$sql = "select * from users where id = {$CURRENT_USER_ID};";
$result = $APP_ADODB->Execute($sql);

$CURRENT_USER_WORKNO = $result->fields['agent_workno'];

$content = eval('return "'.$smstpl['policy_calculate_smstpl'].'";');

//require(_ROOT_DIR.'/common/sms/smsUtils.php');

//submitSMS($mobile,$content);

return_ajax('success',array('callee'=>$mobile,'content'=>$content));

?>