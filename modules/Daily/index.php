<?php
date_default_timezone_set('PRC');
//禁止表头按键
$bar_buttons     = FALSE;
//禁止操作按钮
$operation_allow = FALSE;
//禁止复选框
$selecter_allow  = FALSE;
//禁止表头菜单
//$bar_allow    = FALSE;
//允许部分公共按钮
$toolsbar        = createToolsbar(Array('search','calendar','calculator','email','sms','export'));

require_once(_ROOT_DIR."/common/listView.php");
?>
