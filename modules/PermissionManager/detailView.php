<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
global $tpl_file;
if(!isset($tpl_file)) $tpl_file = 'PermissionManager/detailView.tpl';
global $operation;
if(!isset($operation)) $operation = 'detail';
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
if(empty($_GET['recordid'])) die(); 
$permission_id = $_GET['recordid'];
$permission_name = '';
$permission_description = '';
$permission_info =  Array();
$new_id = $permission_id;
if($operation == 'copy')
{
	$new_id = getNewModuleSeq('permission');
}
$result = $APP_ADODB->Execute("select * from permission where id={$permission_id} limit 1;");
if($result && !$result->EOF)
{
	$permission_name = $result->fields['name'];
	$permission_description = $result->fields['description'];
	$result1 = $APP_ADODB->Execute("select * from permission_modules where permissionid={$permission_id} ;");
	while(!$result1->EOF)
	{
	    $mod = Array();
		$mod_name = $result1->fields['module_name'];
	    $mod['label'] = getTranslatedString($mod_name);
	    $mod['access'] = ($result1->fields['is_allow'] == 'YES')?true:false ;
	    $mod['actions'] = Array();	
		$result_action = $APP_ADODB->Execute("select * from permission_actions where permissionid={$permission_id} and  module_name = '{$mod_name}' order by action_name ASC;");
		while(!$result_action->EOF)
		{
			$action_name = $result_action->fields['action_name'];
			$mod['actions'][$action_name] = Array();
			$mod['actions'][$action_name]['access'] = ($result_action->fields['is_allow'] == 'YES')?true:false;
			$mod['actions'][$action_name]['label'] = getModuleTranslatedString($action_name,$mod_name);
			$result_action->MoveNext();	
		}		
		if($operation == 'edit' || $operation == 'copy')
		{
			$_SESSION["{$CURRENT_USER_ID}_{$mod_name}_{$new_id}_users_permission"] = $result1->fields['recordset_users'];
			$_SESSION["{$CURRENT_USER_ID}_{$mod_name}_{$new_id}_groups_permission"] = $result1->fields['recordset_groups'];
			$result_field = $APP_ADODB->Execute("select * from permission_fields where permissionid={$permission_id} and module_name = '{$mod_name}';");
			$flds = Array();
			while(!$result_field->EOF)
			{
				$fld = Array();
				$fld['is_show'] = $result_field->fields['is_show'];
				$fld['is_modify'] = $result_field->fields['is_modify'];
				$fld['hidden_start'] =  $result_field->fields['hidden_start'];
				$fld['hidden_end'] =  $result_field->fields['hidden_end'];
				$flds[$result_field->fields['field_name']] = $fld;
				$result_field->MoveNext();
			}
			$_SESSION["{$CURRENT_USER_ID}_{$mod_name}_{$new_id}_fields_permission"] = $flds;		
		}		
		$permission_info[$mod_name] = $mod;
		$result1->MoveNext();
	}		
}
if($operation == 'copy')
{
	$smarty->assign('NEW_ID',$new_id);
}	
$smarty->assign('OPERATION',$operation);
$smarty->assign('PERMISSION_ID',$permission_id);
$smarty->assign('PERMISSION_INFO',$permission_info);
$smarty->assign('PERMISSION_NAME',$permission_name);
$smarty->assign('PERMISSION_DESCRIPTION',$permission_description);
$smarty->assign('ERROR',false);
$smarty->display($tpl_file);

?>