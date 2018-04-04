<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
if(!$CURRENT_IS_ADMIN)
{
	return_ajax('error','你无权进行该项操作!');
	die();
}	

$operation = '';
if(!isset($_POST['operation']))
{
	return_ajax('error','operation错误!');
	die();
}	
$operation = $_POST['operation'];
if(!isset($_POST['id']))
{
	return_ajax('error','没有记录ID!');
	die();
}	
$id = $_POST['id'];
if(!isset($_POST['name']))
{
	return_ajax('error','权限名无效');
	die();
}	
$name = $_POST['name'];
$description = '';
if(isset($_POST['description'])) $description = $_POST['description'];

if($operation == 'create')
{
	$sql = "insert into permission(id,name,description) values({$id},'{$name}','{$description}');";
	$APP_ADODB->Execute($sql);
}
elseif($operation == 'copy')
{
	$id = $_POST['new_id'];
	$sql = "insert into permission(id,name,description) values({$id},'{$name}','{$description}');";
	$APP_ADODB->Execute($sql);
}
else
{
	$sql = "update permission set name='{$name}' , description='{$description}' where id={$id};";
	$APP_ADODB->Execute($sql);
}

$have_p_mod = searchPermissionModules();
$sql = 'select module_name from modules ;';
$moddb = $APP_ADODB->Execute($sql);

while(!$moddb->EOF)
{
	$mod = $moddb->fields['module_name'];
	if(!in_array($mod,$have_p_mod))
	{
		$moddb->MoveNext();
		continue;
	}
	
	$allow = 'NO';
	if(in_array($mod,$_POST['module_access'])) $allow = 'YES';
	$groups = '-1';
	$users = '-1';
	if($allow == 'YES')
	{
		if(!empty($_SESSION["{$CURRENT_USER_ID}_{$mod}_{$id}_groups_permission"]))
		{
			$groups = $_SESSION["{$CURRENT_USER_ID}_{$mod}_{$id}_groups_permission"];
		}
		if(!empty($_SESSION["{$CURRENT_USER_ID}_{$mod}_{$id}_users_permission"]))
		{
			$users = $_SESSION["{$CURRENT_USER_ID}_{$mod}_{$id}_users_permission"];
		}		
	}
	if($operation == 'create' || $operation == 'copy')
	{
		$sql = 'insert into permission_modules(permissionid,module_name,is_allow,recordset_groups,recordset_users) ';
		$sql .= "values({$id},'{$mod}','{$allow}','{$groups}','{$users}');";
	}
	else
	{
		$sql = "update permission_modules  set is_allow='{$allow}',recordset_groups='{$groups}',recordset_users='{$users}' ";
		$sql .= "where permissionid = {$id} and module_name='{$mod}';";
	}
	$APP_ADODB->Execute($sql);
	
	$actions = getPermissionModuleActions($mod);
	if($actions)
	{
		foreach($actions as $action_name => $desc)
		{
			$action_allow = 'NO';
			if($allow == 'YES' && !empty($_POST["{$mod}_actions"]) && in_array($action_name,$_POST["{$mod}_actions"]))
			{
				$action_allow = 'YES';
			}
	
			if($operation == 'create' || $operation == 'copy')
			{
				$sql = "insert into permission_actions(permissionid,module_name,action_name,is_allow) values({$id},'{$mod}','{$action_name}','{$action_allow}');";
			}
			else
			{
				$sql = "update permission_actions set is_allow = '{$action_allow}' where permissionid={$id} and module_name='{$mod}' and action_name ='{$action_name}';";
			}
			$APP_ADODB->Execute($sql);
		}
	}

	$sess_field_pfx = "{$CURRENT_USER_ID}_{$mod}_{$id}_fields_permission";
	$fields = getPermissionModuleFields($mod);
	if(!empty($fields))
	{
		foreach($fields as $field)
		{
			if($operation == 'create' || $operation == 'copy')
			{
				$sql = 'insert into permission_fields(permissionid,module_name,field_name,is_show,is_modify,hidden_start,hidden_end) ';
				$sql .= "values({$id},'{$mod}','{$field}','%s','%s',%d,%d);";
			}
			else
			{
				$sql = "update permission_fields set is_show='%s',is_modify='%s',hidden_start=%d,hidden_end=%d ";
				$sql .= "where permissionid={$id} and module_name='{$mod}' and field_name='{$field}';";				
			}
			
			if($allow == 'YES' && array_key_exists($field,$_SESSION[$sess_field_pfx]))
			{
				$sql = sprintf($sql,$_SESSION[$sess_field_pfx][$field]['is_show'],
							   $_SESSION[$sess_field_pfx][$field]['is_modify'],
							   $_SESSION[$sess_field_pfx][$field]['hidden_start'],
							   $_SESSION[$sess_field_pfx][$field]['hidden_end']
							   );
			}
			else
			{	
				$sql = sprintf($sql,'NO','NO',-1,-1);

			}
			$APP_ADODB->Execute($sql);
		}
	}
	
	$moddb->MoveNext();
}
//cleanPermissionfile();	
buildPermissionFile($id);
return_ajax('rediect',"index.php?module=PermissionManager&action=detailView&recordid={$id}");

?>