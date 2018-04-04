<?php

global $APP_ADODB, $CURRENT_USER_NAME, $CURRENT_USER_ID, $CURRENT_USER_GROUPID, $CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if (!isset($module)) {
    $module = _MODULE;
}

if (!isset($action)) {
    $action = _ACTION;
}

require_once _ROOT_DIR . "/modules/{$module}/{$module}Module.class.php";
$classname = "{$module}Module";
$mod = new $classname();

if($_POST['status'] == 1)
{

	foreach($_POST['checkcode'] as $key => $val)
	{
		if($key == 'DZA')
		{
			$smarty->assign('dza_demandNo', $val['demandNo']);
			$smarty->assign('dza_checkCode', $val['checkcode']);
		}
		if($key == 'DAA')
		{
			$smarty->assign('daa_demandNo', $val['demandNo']);
			$smarty->assign('daa_checkCode', $val['checkcode']);
		}
	}

	$smarty->display("{$module}/Protect.tpl");
}






