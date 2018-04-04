<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
$toolsbar = createToolsbar(Array('wfrole_sync'));

$smarty->assign('TOOLSBAR',$toolsbar);
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);
$smarty->assign('TITLEBAR_SHOW_MODULE_LABEL',false);

if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}
require(_ROOT_DIR.'/include/workflow/PMApi.class.php');
$pmconf = require(_ROOT_DIR.'/config/workflow.conf.php');

$pmws = new PMApi($pmconf);

if($pmws->login(null,null,true))
{
	$prole = array();
	$processes = $pmws->getProcessList();	

	foreach($processes as $ps)
	{
		$p = array();
		$p['guid'] = $ps['guid'];
		$p['name'] = $ps['name'];
		$tasks = $pmws->getTaskList($ps['guid']);
		
		$p['tasks'] = array();
		
		foreach($tasks as $task)
		{
			$tname = $task['name'];
			$p['tasks'][] = array('guid'=> $task['guid'],'name'=>$tname );
		}
		$prole[] = $p;
	}

	$smarty->assign('PROCESSES_ROLE',$prole);
	$smarty->display('WFRole/index.tpl');
}
else
{
	$smarty->assign('ERROR_MESSAGE','工作流引擎故障！');
	$smarty->display('ErrorMessage.tpl');
}



?>