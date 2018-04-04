<?php
global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax(10,'not permission!');
	die();
}

if(empty($_GET['number']))
{
	return_ajax(1,'number empty£¡');
	die();
}
$result = $APP_ADODB->Execute("select * from zswitch_cc_agent_state where userid = {$CURRENT_USER_ID}  limit 1;");
if($result && !$result->EOF)
{
	if($result->fields['state'] != 'Waiting')
	{
		return_ajax(2,'agent busy£¡');
		die();
	}
	require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
	$com = new ZSwitchComm();
	if($com->agentCallout($result->fields['name'],$_GET['number']))
	{
		return_ajax(0,'success');
	}
	else
	{
		return_ajax(3,'failure');
	}

	die();
}

return_ajax(3,'agnet not login£¡');
?>