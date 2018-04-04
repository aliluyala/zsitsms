<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
if(!$CURRENT_IS_ADMIN) 
{
	return_ajax('error','你无权进行该项操作!');
	die();
}
refurbishAllPermissionData();
return_ajax('error','权限刷新完成!');
?>