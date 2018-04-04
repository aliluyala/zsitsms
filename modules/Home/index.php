<?php
global $CURRENT_IS_ADMIN;
if($CURRENT_IS_ADMIN)
{
	$bar_buttons = createListViewButtons();
	require_once(_ROOT_DIR."/common/listView.php");
}
else
{
	require_once('selfSetting.php');
}	
?>