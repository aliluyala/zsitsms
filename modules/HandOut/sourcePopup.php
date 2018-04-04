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
	$headers = createListViewHeaders($mod->listFields,$mod->orderbyFields,'','NONE',null,null);
	$result = $mod->getSource();
	$list_data = $result ? formatListDatas($result,$module,"user_attach",$mod->fields,$mod->listFields,$mod->enteryField,$mod->picklist,$mod->associateTo) : NULL;
	$smarty->assign("LISTVIEW_HEADERS",$headers);
	$smarty->assign("LISTVIEW_DATA",$list_data);
	$smarty->display('HandOut/sourceList.tpl');