<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN)
{
	die('你的权限不够！');
}
$share_groups = Array();
$share_users = Array();

$pid = $_GET['pid'];
$pmod = $_GET['pmod'];
$sql = "select * from permission_modules where permissionid={$pid} and module_name='{$pmod}' limit 1;";

$result = $APP_ADODB->Execute($sql);

if($result->fields['recordset_users']!='alluser' && $result->fields['recordset_users']!='selfuser')
{
	$share_users_range = 'select'; 
	$has_users = explode(',',$result->fields['recordset_users']);
	$sql = 'select id,user_name from users ;';
	$result1 = $APP_ADODB->Execute($sql);
	while(!$result1->EOF)
	{
		$u =  Array('shared'=>false,'label'=>$result1->fields['user_name']);
		if(in_array($result1->fields['id'],$has_users))
		{
			$u['shared'] = true;
		}
		$share_users[$result1->fields['id']] = $u;
		$result1->MoveNext();
	}
}
else
{
	$share_users_range = $result->fields['recordset_users'];
}

if($result->fields['recordset_groups']!='allgroup' && $result->fields['recordset_groups']!='selfgroup')
{
	$share_groups_range = 'select'; 
	$has_groups = explode(',',$result->fields['recordset_groups']);
	$sql = 'select id,name from groups ;';
	$result2 = $APP_ADODB->Execute($sql);
	while(!$result2->EOF)
	{
		$g =  Array('shared'=>false,'label'=>$result2->fields['name']);
		if(in_array($result2->fields['id'],$has_groups))
		{
			$g['shared'] = true;
		}
		$share_groups[$result2->fields['id']] = $g;
		$result2->MoveNext();
	}
}
else
{
	 $share_groups_range = $result->fields['recordset_groups'];
}

$smarty->assign('SHARE_USERS_RANGE',$share_users_range);
$smarty->assign('SHARE_GROUPS_RANGE',$share_groups_range);
$smarty->assign('SHARE_GROUPS',$share_groups);
$smarty->assign('SHARE_USERS',$share_users);
$smarty->display('PermissionManager/detailRecordsetView.tpl');
?>