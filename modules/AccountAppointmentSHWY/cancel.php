<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$smarty->assign('MODULE',$module);
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();	
}
if(!empty($_GET['recordid'])) 
{
	$mod->updateOneRecordset($_GET['recordid'],
							 $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
							 $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module),
							 Array('state'=>'Cancel','user_handle'=>$CURRENT_USER_ID,'date_handle'=>date('Y-m-d H:i:s')));
}

?>