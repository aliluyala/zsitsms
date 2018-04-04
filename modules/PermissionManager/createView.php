<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$module_label =  getTranslatedString(_MODULE);
$action_label =  getTranslatedString(_ACTION);
$toolsbar = createToolsbar(Array('calendar','calculator','email','sms','phone'));
$smarty->assign('TOOLSBAR',$toolsbar);
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);
$smarty->assign('TITLEBAR_SHOW_MODULE_LABEL',true);
if(!$CURRENT_IS_ADMIN)
{
	$smarty->assign('ERROR',true);
	$smarty->assign('ERROR_MESSAGE','你的权限不够！“权限管理”操作需要“管理员”权限！');
	$smarty->display('ErrorMessage.tpl');
	die();
}
$have_p_mod = searchPermissionModules();
$modules = Array();
$sql = 'select module_name from modules ;';
$moddb = $APP_ADODB->Execute($sql);
while(!$moddb->EOF)
{	
	if( in_array($moddb->fields['module_name'],$have_p_mod)) 
	{
		$modules[] = $moddb->fields['module_name'];
	}	
	$moddb->MoveNext();
}

$permission_id = getNewModuleSeq('permission');
$permission_name = '';
$permission_description = '';
$permission_info =  Array();

foreach($modules as $mod_name)
{
	$mod = Array();
	$mod['label'] = getTranslatedString($mod_name);
	$mod['access'] = true;

	$mod['actions'] = Array();	
	$_SESSION["{$CURRENT_USER_ID}_{$mod_name}_{$permission_id}_groups_permission"] = 'allgroup';
	$_SESSION["{$CURRENT_USER_ID}_{$mod_name}_{$permission_id}_users_permission"] = 'alluser';
	$safeactions = getPermissionModuleActions($mod_name);
	if(!empty($safeactions))
	{
		foreach($safeactions as $action_name => $action_desc)
		{		
			$mod['actions'][$action_name] = Array('access'=>true,'label'=>getModuleTranslatedString($action_name,$mod_name));
		}
	}	
	$safefields = getPermissionModuleFields($mod_name);
	$mod_fields = Array();
	if(!empty($safefields))
	{
		foreach($safefields as $field)
		{
			$mod_fields[$field] = Array('is_show'=>true,
										'is_modify'=>true,
										'hidden_start'=>0,
										'hidden_end'=>0
										);
		}
	}
	$_SESSION["{$CURRENT_USER_ID}_{$mod_name}_{$permission_id}_fields_permission"]	= $mod_fields;
	$permission_info[$mod_name] = $mod;
}
$smarty->assign('TITLEBAR_SHOW_MODULE_LABEL',true);
$smarty->assign('OPERATION','create');
$smarty->assign('PERMISSION_ID',$permission_id);
$smarty->assign('PERMISSION_INFO',$permission_info);
$smarty->assign('PERMISSION_NAME',$permission_name);
$smarty->assign('PERMISSION_DESCRIPTION',$permission_description);
$smarty->assign('ERROR',false);
$smarty->display('PermissionManager/editView.tpl');

?>