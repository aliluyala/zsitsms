<?php
$toolsbar = createToolsbar(Array('search','calendar','calculator','email','sms','phone','export'));
$bar_buttons = createListViewButtons();
$operations = Array();
$operations[] = Array('name'=>getTranslatedString('监听'),
		                      'url'=>"javascript:zswitch_callcenter_member_spy($(this).attr('recordid'));");
$operations[] = Array('name'=>getTranslatedString('挂断'),
		                      'url'=>"javascript:zswitch_callcenter_member_hangup($(this).attr('recordid'));");							  
require_once(_ROOT_DIR."/common/listView.php");
?>