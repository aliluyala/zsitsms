<?php 
global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax(10,'not permission!');
	die();
}

$agent = '';
$uuid = '';
$result = $APP_ADODB->Execute("select * from zswitch_cc_agent_state where id = {$_GET['recordid']};");
if($result && !$result->EOF)
{	
	$agent = $result->fields['name'];
}	

if(!empty($agent))
{
	require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
	$com = new ZSwitchComm();
	if($com->agentChangeStatus($agent,'BREAK'))
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