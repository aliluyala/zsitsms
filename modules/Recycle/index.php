<?php
if(!isset($module)) $module = _MODULE;

$toolsbar = createToolsbar(Array('search','export'));

$operations[] = Array('name'=>getTranslatedString('恢复'),
                              'url'=>"account.zswitch_listview_operation_recycle('{$module}',$(this).attr('recordid'));");
$operations[] = Array('name'=>getTranslatedString('删除'),
                              'url'=>"zswitch_listview_operation_delete('{$module}',$(this).attr('recordid'));");

$bar_buttons = createListViewButtons(Array('delete','recycle'));
$tpl_file = 'Recycle/ListView.tpl';
require_once(_ROOT_DIR."/common/listViewA.php");
?>