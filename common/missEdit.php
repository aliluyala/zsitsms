<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage1.tpl');
	die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();	
}
if(empty($_GET['field']) || empty($_GET['recordid']))
{
	echo 'aaa';
	$smarty->assign('ERROR_MESSAGE','系统错误，操作失败！');
	$smarty->display('ErrorMessage1.tpl');
	die();
}

$field = $_GET['field'];
$recordid = $_GET['recordid'];

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

if(!in_array($field,$modFields) || !in_array($field,$mod->missEditFields))
{
	$smarty->assign('ERROR_MESSAGE','你没有权限修改此字段！');
	$smarty->display('ErrorMessage1.tpl');	
	die();
}


$result = $mod->getOneRecordset($recordid,
							    $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
								$CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
								);	
if(isset($result) && isset($result[0]))
{
	$result = $result[0];
}
else
{
	$smarty->assign('ERROR_MESSAGE','系统错误，操作失败！');
	$smarty->display('ErrorMessage1.tpl');
	die();
}
$picklist = null;
$associateTo = null;
if(isset($mod->associateTo[$field]))
{
	$associateTo = $mod->associateTo[$field];
}
if(isset($mod->picklist[$field]))
{
	$picklist = $mod->picklist[$field];
}
								
$fieldui = createFieldUI($field,$mod->fields[$field],$result[$field],$picklist,$associateTo,'edit',$recordid);
$smarty->assign('RECORDID',$recordid);
$smarty->assign('FIELD',$fieldui);
$smarty->display('missEditView.tpl');
?>