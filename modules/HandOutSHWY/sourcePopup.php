<?
	global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$mod_strings;
	$smarty = new ZS_Smarty();
	$module = _MODULE;
	$action = _ACTION;
	$smarty->assign('MODULE',$module);
	$smarty->assign('ACTION',$action);
	$mod;
	if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
	{
	    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	    $modclass= "{$module}Module";
	    $mod = new $modclass();
	}
	$smarty->assign("SOURCELIST",$mod->getSource());
	$smarty->display("{$module}/sourceList.tpl");