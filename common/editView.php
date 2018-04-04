<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
global $toolsbar;
if(!isset($toolsbar)) $toolsbar = createToolsbar(Array('calendar','calculator','email','sms','phone'));

$smarty->assign('TOOLSBAR',$toolsbar);
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);
global $show_module_label;
if(!isset($show_module_label)) $show_module_label = true;
$smarty->assign('TITLEBAR_SHOW_MODULE_LABEL',$show_module_label);
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}



global $mod;
if(!isset($mod) && is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();
}

global $operation,$recordid,$new_recordid;
if(!isset($operation)) $operation = 'edit';
$smarty->assign('OPERATION',$operation);

if($operation == 'create' && !isset($recordid))
{
	$recordid  = getNewModuleSeq($mod->baseTable);
}

if($operation == 'copy' && !isset($new_recordid))
{
	$new_recordid = getNewModuleSeq($mod->baseTable);
}

//记录ID

if(!isset($recordid))
{
 	$recordid = $_GET['recordid'];
}
$smarty->assign('RECORDID',$recordid);


if(!isset($new_recordid)) $new_recordid = $recordid;
$smarty->assign('NEW_RECORDID',$new_recordid);



global $return_module;
if(!isset($return_module))
{
	if(isset($_GET['return_module'])) $return_module = $_GET['return_module'];
	else $return_module = $module;
}

global $return_action;
if(!isset($return_action))
{
	if(isset($_GET['return_action'])) $return_action = $_GET['return_action'];
	else $return_action = 'index';
}

global $return_recordid;
if(!isset($return_recordid))
{
	if(isset($_GET['return_recordid'])) $return_recordid = $_GET['return_recordid'];
	else $return_recordid = $recordid;
}

$smarty->assign('RETURN_MODULE',$return_module);
$smarty->assign('RETURN_ACTION',$return_action);
$smarty->assign('RETURN_RECORDID',$return_recordid);

$fieldrecordid = -1;
if($operation == 'edit' || $operation == 'create')
{
	$fieldrecordid = $recordid;
}
elseif($operation == 'copy')
{
	$fieldrecordid = $new_recordid;
}	


global $editview_datas;
if(!isset($editview_datas))
{
	$result = null;
	if($operation == 'edit' || $operation == 'copy')
	{
		$result = $mod->getOneRecordset($recordid,
									    $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
								 	    $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
									   );
	}
	elseif($operation == 'create')
	{
		$result = Array($_GET);
		unset($result[0]['module']);
		unset($result[0]['action']);
	}

	if(!$CURRENT_IS_ADMIN)
	{
		$result = validationFieldsShowPermission($module,$result);

	}
	if(isset($result) && isset($result[0]))
	{
		$result = $result[0];
	}

	if($CURRENT_IS_ADMIN)
	{
		$modFields = $mod->editFields;
	}
	else
	{
		$modFields = getFieldsModifyPermission($module);
		if($modFields === true)
		{
			$modFields = $mod->editFields;
		}
		elseif($modFields === false)
		{
			$modFields = Array();
		}
		foreach($modFields as $idx => $field_name)
		{
			if(!in_array($field_name,$mod->editFields)) unset($modFields[$idx]);
		}
	}
	$result['id'] = $fieldrecordid;
	$editview_datas = createEditViewUI($module,$mod->fields,$mod->editFields,$modFields,$mod->defaultColumns,
									   $mod->blocks,$mod->associateTo,$mod->picklist,$result,$operation);

}

$smarty->assign('EDITVIEW_DATAS',$editview_datas);
global $tpl_file;
if(!isset($tpl_file)) $tpl_file = 'EditView.tpl';
$smarty->display($tpl_file);
?>