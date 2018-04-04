<?php
global $APP_ADODB,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$result = $APP_ADODB->Execute("select agent_login,agent_workno,agent_queue,agent_popup,agent_status from users where id={$CURRENT_USER_ID};");

$agent_number = session_get('current_user_agent_number');
if(!$agent_number || empty($agent_number))
{
	$smarty->assign('AGENT_NUMBER','');
	$smarty->assign('NO_AGENT',true);
}
else
{
	$smarty->assign('AGENT_NUMBER',$agent_number);
	$smarty->assign('NO_AGENT',false);
	//echo $sql = "update zswitch_cc_agent_state set userid ={$CURRENT_USER_ID},workno='{$result->fields['agent_workno']}' where name='{$agent_number}' limit 1;";
	$APP_ADODB->Execute("update zswitch_cc_agent_state set userid ={$CURRENT_USER_ID},workno='{$result->fields['agent_workno']}' where name='{$agent_number}' limit 1;");
	require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
	$com = new ZSwitchComm();
	
	$queues = explode(',',$result->fields['agent_queue']);
	if($result->fields['agent_login'] =='YES')
	{
		foreach($queues as $queue)
		{
			if(!empty($queue) && $queue!='NONE')
			{
				$com->login($agent_number,$queue,'YES');
			}	
		}			
	}
	else
	{
		foreach($queues as $queue)
		{	
			if(!empty($queue) && $queue!='NONE')
			{
				$com->login($agent_number,$queue,'NO');	
			}	
		}	
	}
	$com->agentChangeStatus($agent_number,$result->fields['agent_status']);
}

$smarty->assign('AGENT_POPUP',$result->fields['agent_popup']);
$smarty->assign('AGENT_STATUS',$result->fields['agent_status']);
$smarty->display('AgentState/AgentCtrlPanel.tpl');
?>