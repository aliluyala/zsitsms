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
$userid = -1;
$result = $APP_ADODB->Execute("select * from zswitch_cc_agent_state where id = {$_GET['recordid']}  limit 1;");
if($result && !$result->EOF)
{	
	$agent = $result->fields['name'];
	$userid = $result->fields['userid'];
}	

if(!empty($agent))
{	
	$result = $APP_ADODB->Execute("select agent_queue from users where id={$userid};");
	$APP_ADODB->Execute("update zswitch_cc_agent_state set userid =-1,workno='' where name='{$agent}' limit 1;");
	$queues = explode(',',$result->fields['agent_queue']);	
	require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
	$com = new ZSwitchComm();
	if($com->agentChangeStatus($agent,'OFFLINE'))
	{
		return_ajax(0,'success');
	}
	else
	{
		return_ajax(3,'failure');
	}
	
	foreach($queues as $queue)
	{	
		if(!empty($queue) && $queue !='NONE')
		{
			$com->login($agent,$queue,'NO');	
		}	
	}	
	die();
}

return_ajax(1,'fairult!');
die();
?>