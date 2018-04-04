<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
require_once(_ROOT_DIR.'/modules/PolicyCalculateCom/PolicyCalculateComModule.class.php');
if(empty($_GET['vin']))
{
	return_ajax('error','没有提供车架号！');
	die();
}
else
{
	$pc = new PolicyCalculateComModule();
	$list = $pc->getCalList($_GET['vin']);
	return_ajax('success',$list);
}






?>