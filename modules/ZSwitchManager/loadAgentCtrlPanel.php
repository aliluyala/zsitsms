<?php
global $APP_ADODB,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$result = $APP_ADODB->Execute("select agent_login,agent_popup,agent_status from users where id={$CURRENT_USER_ID};");


$smarty->assign('AGENT_POPUP',$result->fields['agent_popup']);
$smarty->assign('AGENT_STATUS',$result->fields['agent_status']);
$smarty->display('ZSwitchManager/AgentCtrlPanel.tpl');
?>