<?php
global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax(10,'not permission!');
	die();
}

$uuid = '';
$agent = '';
$spynumber = '';
$result = $APP_ADODB->Execute("select * from zswitch_cc_agent_state where userid = {$CURRENT_USER_ID}  limit 1;");
if($result && !$result->EOF)
{
	$agent = $result->fields['name'];
}	
if(!empty($_GET['recordid']))
{
	$result = $APP_ADODB->Execute("select * from zswitch_cc_member_state where id = {$_GET['recordid']}  limit 1;");

	if($result && !$result->EOF)
	{
		$uuid =  $result->fields['uuid'];
		$spynumber = $result->fields['caller_number'];
	}	
}

if(!empty($uuid) && !empty($agent))
{
	require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
	$com = new ZSwitchComm();
	if($com->spy($agent,$uuid,$spynumber))
	{
		return_ajax(0,'success');
	}
	else
	{
		return_ajax(3,'failure');
	}
	die();
}

return_ajax(1,'fairult!');
die();




?>