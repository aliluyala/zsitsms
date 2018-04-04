<?php
$module = _MODULE;
$bar_buttons = createListViewButtons(Array('delete','modify','today_appointment','tomorrow_appointment','after_tomorrow_appointment','total_show'));
$tpl_file = "{$module}/ListViewA.tpl";
require_once(_ROOT_DIR."/common/listViewA.php");
?>