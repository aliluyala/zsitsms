<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
if(!$CURRENT_IS_ADMIN) 
{
	return_ajax('error','你无权进行该项操作!');
	die();
}
	
cleanPermissionfile();
buildPermissionFile();	
	
return_ajax('error','权限重建完成!');	
?>