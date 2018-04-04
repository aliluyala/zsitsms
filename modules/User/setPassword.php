<?php
global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_IS_ADMIN;
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require(_ROOT_DIR.'/modules/User/UserModule.class.php');
$mod = new UserModule();
if(!isset($_POST['recordid']) || !isset($_POST['user_password']))
{
	return_ajax('error','密码设置错误!');
	die();
}
$id =$_POST['recordid'];



if($CURRENT_IS_ADMIN || ($id == $CURRENT_USER_ID) || validationActionPermission($module,$action))
{
    $userids  = $CURRENT_IS_ADMIN || $id == $CURRENT_USER_ID ? NULL : getRecordset2UsersPermission($module);
    $groupids = $CURRENT_IS_ADMIN || $id == $CURRENT_USER_ID ? NULL : getRecordset2GroupsPermission($module);
	$mod->modifyPassword($id,$_POST['user_password'], $userids, $groupids);
	return_ajax('message','密码设置成功。');
}
else
{
	return_ajax('message','你无权修改密码!');
}
?>