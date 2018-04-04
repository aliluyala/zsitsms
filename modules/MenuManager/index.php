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
	$smarty->assign('ERROR_MESSAGE','你的权限不够！“定制菜单”操作需要“管理员”权限！');
	$smarty->display('MenuManager/index.tpl');
	die();
}

$menus_items = Array();
$menu_name = '';
$menu_title = '';
$menu_seq = 0;
$menu_action = 'SUB_MENU';
$menu_target = '';
$menu_current_item = null;
if(isset($_POST['menuitem'])) $menu_current_item = $_POST['menuitem'];
$operation = null;
if(isset($_GET['operation'])) $operation = $_GET['operation'];

if($operation == 'save')
{
	$sql = "update menus set name='{$_POST['name']}',title='{$_POST['title']}',seq={$_POST['seq']},action='{$_POST['action']}',target='{$_POST['target']}' where id={$menu_current_item}";
	$APP_ADODB->Execute($sql);
}
elseif($operation == 'delete')
{
	$APP_ADODB->Execute("delete from menus where id={$menu_current_item}");
	$menu_current_item = null;
}
elseif($operation == 'add')
{
	$result = $APP_ADODB->Execute('select * from menus_seq limit 1;');
	$menu_current_item = $result->fields['id'];
	$APP_ADODB->Execute('update menus_seq set id = id+1;');
    $APP_ADODB->Execute("insert into menus(id,name) values({$menu_current_item},'新建菜单');");
}

$result = $APP_ADODB->Execute('select * from menus order by seq;');

if($result)
{	
	while(!$result->EOF)
	{
		if($result->fields['name'] != 'setting')
		{
			if(!isset($menu_current_item))
			{			
				$menu_current_item = $result->fields['id'];			
			}
			$menus_items[] = Array('id'=>$result->fields['id'],'name'=>getTranslatedString($result->fields['name']));		
			if($menu_current_item == $result->fields['id'])
			{
				$menu_name = $result->fields['name'];
				$menu_title = $result->fields['title'];
				$menu_seq = $result->fields['seq'];
				$menu_action = $result->fields['action'];
				$menu_target = $result->fields['target'];
			}
		}	
		$result->MoveNext();
	}
}
$smarty->assign('ERROR',false);
$smarty->assign('MENUMANAGER_ITEMS',$menus_items);
$smarty->assign('MENU_CURRENT_ITEM',$menu_current_item);
$smarty->assign('MENU_NAME',$menu_name);
$smarty->assign('MENU_TITLE',$menu_title);
$smarty->assign('MENU_ACTION',$menu_action);
$smarty->assign('MENU_TARGET',$menu_target);
$smarty->assign('MENU_SEQ',$menu_seq);
$smarty->display('MenuManager/index.tpl');
?>