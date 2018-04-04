<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;

if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','你没有权限');
	die();
}


require(_ROOT_DIR.'/include/workflow/PMApi.class.php');
$pmconf = require(_ROOT_DIR.'/config/workflow.conf.php');
$pmws = new PMApi($pmconf);

$pmws->clearRoles();

$sql = 'select id,name  from groups;';
$result = $APP_ADODB->Execute($sql);
$grps = array();
while(!$result->EOF)
{
	$row = array();
	$id = $result->fields['id'];
	$name = $result->fields['name'];
	$guid = $pmws->createGroup($name);
	$grps[$id] = $guid;
	$APP_ADODB->Execute("update groups set guid='{$guid}' where id={$id};");
	$result->MoveNext();
}

$sql = 'select id,user_name,name,user_password,groupid  from users;';
$result = $APP_ADODB->Execute($sql);

while(!$result->EOF)
{
	$row = array();
	$id = $result->fields['id'];
	$usr = $result->fields['user_name'];
	$name = $result->fields['name'];
	$password = $result->fields['user_password'];
	$grpid = $result->fields['groupid'];
	$guid = $pmws->createUser($usr,$name,$password);
	$APP_ADODB->Execute("update users set guid='{$guid}' where id={$id};");
	if(array_key_exists($grpid,$grps))
	{
		$pmws->assignUserToGroup($guid,$grps[$grpid]);
	}
	$result->MoveNext();
}
return_ajax('success','同步成功');




?>