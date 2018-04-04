<?php
global $APP_ADODB,$CURRENT_USER_ID;
$agent_number = session_get('current_user_agent_number');

$result = $APP_ADODB->Execute("select  agent_login,agent_workno,agent_queue,agent_popup,agent_status from users where id={$CURRENT_USER_ID};");
if($agent_number && !empty($agent_number) && $result &&  !$result->EOF && $result->fields['agent_login'] =='YES')
{
	$APP_ADODB->Execute("update zswitch_cc_agent_state set userid ={$CURRENT_USER_ID},workno='{$result->fields['agent_workno']}' where name='{$agent_number}' limit 1;");
	require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
	$com = new ZSwitchComm();
	$queues = explode(',',$result->fields['agent_queue']);
	foreach($queues as $queue)
	{
		
		if(!empty($queue) && $queue !='NONE')
		{
			$com->login($agent_number,$queue,'YES');
		}	
	}	
	$com->agentChangeStatus($agent_number,"ONLINE");	
}

?>