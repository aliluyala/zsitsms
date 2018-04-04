<?php
$toolsbar = createToolsbar(Array('search','calendar','calculator','email','sms','phone','export'));
$bar_buttons = createListViewButtons();
$operations = Array();
$operations[] = Array('name'=>getTranslatedString('监听'),
		                      'url'=>"zswitch_callcenter_agent_spy($(this).attr('recordid'));");
$operations[] = Array('name'=>getTranslatedString('挂断'),
		                      'url'=>"zswitch_callcenter_agent_hangup($(this).attr('recordid'));");	
$operations[] = Array('name'=>getTranslatedString('阻塞'),
		                      'url'=>"zswitch_callcenter_agent_break($(this).attr('recordid'));");	
$operations[] = Array('name'=>getTranslatedString('激活'),
		                      'url'=>"zswitch_callcenter_agent_active($(this).attr('recordid'));");		
$operations[] = Array('name'=>getTranslatedString('踢出'),
		                      'url'=>"zswitch_callcenter_agent_kill($(this).attr('recordid'));");								  
require_once(_ROOT_DIR."/common/listView.php");
?>