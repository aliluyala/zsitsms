<?php
$toolsbar = createToolsbar(Array('create','calendar','calculator','email','sms','phone'));
$bar_buttons= Array('rebuild'=>true,'refurbish'=>true);

$tpl_file = 'PermissionManager/ListView.tpl';
require_once(_ROOT_DIR."/common/listView.php");
?>