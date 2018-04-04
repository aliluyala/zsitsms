<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','你的权限不能进行该项操作！');
	die();
}

$pmconf = require(_ROOT_DIR.'/config/workflow.conf.php');
if(!$pmconf || !$pmconf['enable'])
{
	return_ajax('error','系统不支持流程！');
	die();	
}
if(!array_key_exists('recordid',$_POST))
{
	return_ajax('error','参数错误！');
	die();	
}
$recordid = $_POST['recordid'];

require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$mod = new PolicyCalculateModule();
require(_ROOT_DIR.'/include/workflow/PMApi.class.php');
$pmws = new PMApi($pmconf);
$wfname = '车险电销保单审核流程';

$result = $APP_ADODB->Execute("select user_password from users where id={$CURRENT_USER_ID};");

if(!$result || $result->EOF)
{
	return_ajax('error','系统错误！');
	die();	
}
$pwd = $result->fields['user_password'];

$wflogin = false;
if($CURRENT_USER_ID != 1)
{
	$wflogin = $pmws->login($CURRENT_USER_NAME,'md5:'.$pwd,false);	
}
else
{
	$wflogin = $pmws->login(null,null,true);
}	
if(!$wflogin)
{
	return_ajax('error','登录工作流引擎失败！请重置你的登录密码后再试！<br/>或联系系统管理员！');
	die();	
}

$reset = $mod->getOneRecordset($recordid,null,null);
if(empty($reset[0]['content']))
{
	return_ajax('error','没有找到算价记录,请确认你已经保存算价记录！');
	die();	
}

$data  = json_decode($reset[0]['content'],true);

$vars = array();
$vars['CAL_NO'] = $data['NO'];

foreach($data['INSURANT'] as $key => $val )
{
	$vars[$key] =  urldecode($val);
}

foreach($data['AUTO'] as $key => $val )
{
	$vars[$key] =  urldecode($val);
}
foreach($data['POLICY'] as $key => $val )
{
	$vars[$key] =  urldecode($val);
}
foreach($data['OTHER'] as $key => $val )
{
	$vars[$key] =  urldecode($val);
}

if(array_key_exists('BUSINESS_ITEMS',$data))
{
	$vars['BUSINESS_ITEMS'] = '';
	
	foreach($data['BUSINESS_ITEMS'] as $item)
	{
		if(!empty($vars['BUSINESS_ITEMS'])) $vars['BUSINESS_ITEMS'] .= '|';
		$vars['BUSINESS_ITEMS'] .= $item;
	}
}
if($vars['TTBLI_INSURANCE_AMOUNT'] == '100+')
{
	$vars['TTBLI_INSURANCE_AMOUNT'] = $vars['TTBLI_INSURANCE_AMOUNT_EXT'];
}
$vars['BUSINESS_SELECTED_ITEMS'] = $vars['BUSINESS_ITEMS'];	

$vars['DESIGNATED_DRIVER1'] = 'NO';
if(array_key_exists('DESIGNATED_DRIVER1',$data)) $vars['DESIGNATED_DRIVER1'] = 'YES';
$vars['DESIGNATED_DRIVER1_STATUS'] = $vars['DESIGNATED_DRIVER1'] ;
$vars['DESIGNATED_DRIVER2'] = 'NO';
if(array_key_exists('DESIGNATED_DRIVER2',$data)) $vars['DESIGNATED_DRIVER2'] = 'YES';
$vars['DESIGNATED_DRIVER2_STATUS'] = $vars['DESIGNATED_DRIVER2'] ;
$vars['DESIGNATED_DRIVER3'] = 'NO';
if(array_key_exists('DESIGNATED_DRIVER3',$data)) $vars['DESIGNATED_DRIVER3'] = 'YES';
$vars['DESIGNATED_DRIVER3_STATUS'] = $vars['DESIGNATED_DRIVER3'] ;
$vars['MVTALCI_SELECT'] = 'NO';
if(array_key_exists('MVTALCI_SELECT',$data))
{
	$vars['MVTALCI_SELECT'] = 'YES';
}	
$vars['MVTALCI_SELECT_STATUS'] = $vars['MVTALCI_SELECT'];

$sql = "select  levelrisk , mobile from accounts_shwy where vin = '{$vars['VIN_NO']}';";
$result = $APP_ADODB->Execute($sql);
$vars['LEVELRISK'] = $result->fields['levelrisk'];

$vars['RECEIVER'] = $vars['HOLDER'];
$vars['RECEIVER_MOBILE'] = $result->fields['mobile'];
$vars['RECEIVER_ADDR'] = $vars['HOLDER_ADDRESS'];

$vars['NO'] = getNewModuleSeq('insurance_order');
$vars['AUTHOR'] = $CURRENT_NAME;
$vars['IS_POLICYCAL_SUBMIT'] = "YES";

if($result = $pmws->newCase($wfname,$vars,false))
{

	$sql = "insert into insurance_order(id,order_no,holder,license_no,vin_no,business_policy_no,business_standard_premium,business_discount,";
	$sql .= "business_premium,business_end_time,";              
	$sql .= "business_start_time,mvtalci_policy_no,mvtalci_standard_premium,mvtalci_discount,mvtalci_premium,mvtalci_end_time,mvtalci_start_time,";
	$sql .= "travel_tax_premium,total_premium,total_receivable_amount,receiver,receiver_mobile,receiver_addr,case_id,status,gift,remarks,levelrisk,";
	$sql .= "auditor,auditor_levelrisk,complete_time,create_time,create_userid,create_user,insurance_company) values(";    
	
	$sql .= "{$vars['NO']},";
	$sql .= "'{$vars['NO']}',";
	$sql .= "'{$vars['HOLDER']}',";
	$sql .= "'{$vars['LICENSE_NO']}',";
	$sql .= "'{$vars['VIN_NO']}',";
	$sql .= "'',";
	$sql .= "{$vars['BUSINESS_STANDARD_PREMIUM']},";
	$sql .= "{$vars['BUSINESS_DISCOUNT']},";
	$sql .= "{$vars['BUSINESS_PREMIUM']},";
	$sql .= "'{$vars['BUSINESS_END_TIME']}',";
	$sql .= "'{$vars['BUSINESS_START_TIME']}',";
	$sql .= "'',";
	$sql .= "{$vars['MVTALCI_PREMIUM']},";
	$sql .= "{$vars['FLOATING_RATE']},";
	$sql .= "{$vars['MVTALCI_PREMIUM']},";
	$sql .= "'{$vars['MVTALCI_END_TIME']}',";
	$sql .= "'{$vars['MVTALCI_START_TIME']}',";
	$sql .= "{$vars['TRAVEL_TAX_PREMIUM']},";
	$sql .= "{$vars['TOTAL_PREMIUM']},";
	$sql .= "0,";
	$sql .= "'{$vars['RECEIVER']}',";
	$sql .= "'{$vars['RECEIVER_MOBILE']}',";
	$sql .= "'{$vars['RECEIVER_ADDR']}',";
	$sql .= "{$result},";
	$sql .= "'待提交',";
	$sql .= "'{$vars['GIFT']}',";
	$sql .= "'{$vars['REMARKS']}',";
	$sql .= "'{$vars['LEVELRISK']}',";
	$sql .= "'',";
	$sql .= "'',";
	$sql .= "'0000-00-00 00:00:00',";
	$sql .= "now(),";
	$sql .= "{$CURRENT_USER_ID},";
	$sql .= "'{$CURRENT_NAME}','{$vars['INSURANCE_COMPANY']}');";
	$APP_ADODB->Execute("SET NAMES utf8;");
	$APP_ADODB->Execute($sql);


	return_ajax('success',"订单已成功创建,订单号：{$vars['NO']}，流程流水号：{$result}。<br/>请到'工作流'->'我的工作'->'待提交'下确认并提交订单。");
	die();		
}
return_ajax('error','提交订单失败！请联系系统管理员确认你的工作流角色！');
die();

?>