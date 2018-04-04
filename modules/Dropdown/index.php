<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
global $toolsbar;
if(!isset($toolsbar)) $toolsbar = createToolsbar(array('import','export'));

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

$result  = $APP_ADODB->Execute("select module_name from modules ;");
$activeMod = array();
while(!$result->EOF)
{
	$activeMod[] = $result->fields['module_name'];
	$result->MoveNext();
}

$module_path = _ROOT_DIR.'/modules/';
$dirs = scandir($module_path);
$modules = array();
foreach($dirs as $idx => $val)
{	
	if($val != '.' && $val != '..' && $val != 'Dropdown' && $val != 'Picklists' && in_array($val,$activeMod))
	{		
		@include_once(_ROOT_DIR."/modules/{$val}/{$val}Module.class.php");
		$modc = "{$val}Module";
		if(class_exists($modc))
		{
			$modobj = new $modc();
			foreach($modobj->fields as $fld)
			{
				if($fld[0] == "27")
				{
					$modinfo = array('MODULE_NAME'=>$val,'NAME'=>getTranslatedString($val),'DESCRIBE'=>$modobj->describe);
					$modules[] = $modinfo;	
					break;	
				}
			}

		}
		
	}
}



$smarty->assign('MODULE_LIST',$modules);

$smarty->assign('HAVE_QUERY_WHERE', false);
$smarty->display('Dropdown/index.tpl');
?>