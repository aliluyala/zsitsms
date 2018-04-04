<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
if(!$CURRENT_IS_ADMIN)
{
	die('你无权修改！');	
}
if(isset($_GET['recordid']))
{
	$APP_ADODB->Execute("delete from modules where id={$_GET['recordid']}");
	echo "-1";
}
?>