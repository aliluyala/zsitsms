<?php
$toolsbar = createToolsbar(Array('search','calendar','calculator','email','sms','phone','export','import'));
$bar_buttons = createListViewButtons(Array('delete'));
$operations = Array();
$operations[] = Array('name'=>getTranslatedString('删除'),
		                      'url'=>"zswitch_listview_operation_delete('AgentCDR',$(this).attr('recordid'));");
$operations[] = Array('name'=>getTranslatedString('录音'),
		                      'url'=>"zswitch_callcenter_agent_recordfile($(this).attr('recordid'));");
//$operations[] = Array('name'=>getTranslatedString('下载录音'),
//		                      'url'=>"zswitch_callcenter_agent_recordfile($(this).attr('recordid'));");							  
require_once(_ROOT_DIR."/common/listViewA.php");
?>