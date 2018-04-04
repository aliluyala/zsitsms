<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN)
{
	die('你的权限不够！');
}

$mod_name = $_GET['pmod'];
$pid = $_GET['pid'];
$new_pid = $_GET['new_pid'];
if(empty($new_pid))
{
	$session_pfx = "{$CURRENT_USER_ID}_{$mod_name}_{$pid}_";
}
else
{
	$session_pfx = "{$CURRENT_USER_ID}_{$mod_name}_{$new_pid}_";
}


$share_users_range = 'select';
$share_groups_range = 'select';
$share_groups = Array();
$share_users = Array();
$current_groups = Array();
$current_users = Array();

if(empty($_SESSION["{$session_pfx}groups_permission"]))
{
	$_SESSION["{$session_pfx}groups_permission"] = 'allgroup';
	$share_groups_range = 'allgroup';
}
elseif($_SESSION["{$session_pfx}groups_permission"] == 'allgroup' || $_SESSION["{$session_pfx}groups_permission"] == 'selfgroup')
{
	$share_groups_range = $_SESSION["{$session_pfx}groups_permission"];
}
else
{
	$current_groups = explode(',', $_SESSION["{$session_pfx}groups_permission"]);
}


if(empty($_SESSION["{$session_pfx}users_permission"]))
{
	$_SESSION["{$session_pfx}users_permission"] = 'alluser';
	$share_users_range = 'alluser';
}
elseif($_SESSION["{$session_pfx}users_permission"] == 'alluser' || $_SESSION["{$session_pfx}users_permission"] == 'selfuser')
{
	$share_users_range = $_SESSION["{$session_pfx}users_permission"];
}
else
{
	$current_users = explode(',', $_SESSION["{$session_pfx}users_permission"]);
}


$result = $APP_ADODB->Execute("select id,name from groups");
while(!$result->EOF)
{
	$g = Array('label'=>$result->fields['name'],'shared'=>false);
	if(in_array($result->fields['id'],$current_groups)) $g['shared'] = true;
	$share_groups[$result->fields['id']] = $g;
	$result->MoveNext();
}
$result = $APP_ADODB->Execute("select id,user_name from users;");
while(!$result->EOF)
{
	$u = Array('label'=>$result->fields['user_name'],'shared'=>false);
	if(in_array($result->fields['id'],$current_users)) $u['shared'] = true;
	$share_users[$result->fields['id']] = $u;
	$result->MoveNext();
}

$smarty->assign('PMOD',$_GET['pmod']);
$smarty->assign('PERMISSIONID',$_GET['pid']);
$smarty->assign('SHARE_GROUPS_RANGE',$share_groups_range);
$smarty->assign('SHARE_USERS_RANGE',$share_users_range);
$smarty->assign('SHARE_GROUPS',$share_groups);
$smarty->assign('SHARE_USERS',$share_users);
$smarty->display('PermissionManager/editRecordsetView.tpl');
?>