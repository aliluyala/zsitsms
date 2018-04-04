<?php
$toolsbar = createToolsbar(Array('search','calendar','calculator','email','sms','phone','export','import'));
$bar_buttons = createListViewButtons();
$operation_allow = false;
/*$operations = Array();
$operations[] = Array('name'=>getTranslatedString('删除'),
		                      'url'=>"zswitch_listview_operation_delete('InsuranceOrderCom',$(this).attr('recordid'));");*/
require_once(_ROOT_DIR."/common/listViewA.php");
?>