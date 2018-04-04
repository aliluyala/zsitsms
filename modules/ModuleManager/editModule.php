<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
if(!$CURRENT_IS_ADMIN)
{
	$smarty->assign('ERROR',true);
	$smarty->assign('ERROR_MESSAGE','你的权限不够！“模块管理”操作需要“管理员”权限！');
	$smarty->display('ModuleManager/editModule.tpl');
	die();
}

$menu_lists = Array();
$result = $APP_ADODB->Execute("select id,name from menus where action ='SUB_MENU'");
while($result && !$result->EOF)
{
	$menu_lists[$result->fields['id']] = getTranslatedString($result->fields['name']);
	$result->MoveNext();
}
$menu_lists[-1] = '"无"';	
if(isset($_GET['recordid']) && $_GET['recordid'] != '-1')
{
	$result = $APP_ADODB->Execute("select * from modules where id={$_GET['recordid']}");
}
else
{
	$smarty->assign('ERROR',true);
	$smarty->assign('ERROR_MESSAGE','你没有选择“模块”，或模块还未激活！');
	$smarty->display('ModuleManager/editModule.tpl');
	die();	
}

$module_id = -1;
$module_name = '';
$module_describe = '';
$module_action = '';
$menu_item_selected = -1;

if($result && !$result->EOF)
{
	$module_id = $result->fields['id'];
	$module_name = $result->fields['module_name'];
	$module_describe = $result->fields['module_describe'];
	$module_action = $result->fields['default_action'];
	$menu_item_selected = $result->fields['menuid'];
}

$smarty->assign('MODULE_ID',$module_id);
$smarty->assign('MODULE_NAME',$module_name);
$smarty->assign('MODULE_DESCRIBE',$module_describe);
$smarty->assign('MODULE_ACTION',$module_action);
$smarty->assign('MENU_LISTS',$menu_lists);
$smarty->assign('MENU_ITEM_SELECTED',$menu_item_selected);
$smarty->assign('ERROR',false);
$smarty->display('ModuleManager/editModule.tpl');
?>