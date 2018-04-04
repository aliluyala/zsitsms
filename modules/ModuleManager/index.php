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
	$smarty->assign('ERROR_MESSAGE','你的权限不够！“模块管理”操作需要“管理员”权限！');
	$smarty->display('ModuleManager/index.tpl');
	die();
}
//扫描模块目录
$modules_info = Array();

$module_current_item_id = -1;
if(!empty($_GET['recordid'])) $module_current_item_id = $_GET['recordid'];
$result = $APP_ADODB->Execute('select * from modules order by seq');
while(!$result->EOF)
{
	$name = $result->fields['module_name'];
	if($name !='Index' && $name !='MenuManager' && $name != 'ModuleManager' && $name != 'Picklists')
	{
		if($module_current_item_id == -1) $module_current_item_id = $result->fields['id'];
		$modules_info[$name] = Array('id' => $result->fields['id'],
									 'seq' => $result->fields['seq'],
                                     'actived' => true,
                                     'describe' => $result->fields['module_describe'], 									 
		);
	}	
	$result->MoveNext();
}

$modPath = _ROOT_DIR.'/modules/';
$dirs = scandir($modPath);
foreach($dirs as $p)
{
	if(is_dir($modPath.$p) && $p != '.' && $p != '..' && $p !='MenuManager' && $p != 'Picklists' &&
		$p != 'ModuleManager' && $p != 'Index' && !isset($modules_info[$p]))
	{
	
		$modules_info[$p] = Array('id'=>-1,'seq'=>null,'describe'=>'','actived'=>false);
		$moduleClassName = "{$p}Module";
		$moduleClassFile = $modPath."{$p}/{$p}Module.class.php";
		if(is_file($moduleClassFile)) require_once($moduleClassFile);
		if(class_exists($moduleClassName))
		{
			$mod = new $moduleClassName();
			if(isset($mod->describe)) $modules_info[$p]['describe'] = $mod->describe;
		}
	}
}
$smarty->assign('MODULE_CURRENT_ITEM_ID',$module_current_item_id);
$smarty->assign('MODULES_INFO',$modules_info);
$smarty->assign('ERROR',false);
$smarty->display('ModuleManager/index.tpl');
?>