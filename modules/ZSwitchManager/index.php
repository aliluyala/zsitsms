<?php
$toolsbar = createToolsbar(Array('search','calendar','calculator','email','sms','phone','export'));
$bar_buttons = createListViewButtons(Array('delete'));
$operations = Array();
$operations[] = Array('name'=>getTranslatedString('删除'),
		                      'url'=>"zswitch_listview_operation_delete('ZSwitchManager',$(this).attr('recordid'));");
$operations[] = Array('name'=>getTranslatedString('录音'),
		                      'url'=>"zswitch_listview_zswitch_recordfile($(this).attr('recordid'));");
require_once(_ROOT_DIR."/common/listView.php");
?>