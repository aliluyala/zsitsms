<?php
global $APP_ADODB,$CURRENT_USER_ID;
$agent_number = session_get('current_user_agent_number');
if($agent_number && !empty($agent_number))
{
	$result = $APP_ADODB->Execute("select agent_queue from users where id={$CURRENT_USER_ID};");
	require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
	$com = new ZSwitchComm();
	$queues = explode(',',$result->fields['agent_queue']);
	foreach($queues as $queue)
	{	
		if(!empty($queue) && $queue !='NONE')
		{
			$com->login($agent_number,$queue,'NO');	
		}	
	}	
	$com->agentChangeStatus($agent_number,"OFFLINE");
	$APP_ADODB->Execute("update zswitch_cc_agent_state set userid =-1,workno='' where name='{$agent_number}' limit 1;");	
	session_get('current_user_agent_number','');
}

?>