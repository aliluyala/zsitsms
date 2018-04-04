<?php
//创建主菜单
function createMenus()
{
	global $APP_ADODB,$CURRENT_IS_ADMIN;
	$menudb = $APP_ADODB->Execute('select * from menus order by seq ASC;');
	$menus = Array();
	while(!$menudb->EOF)
	{
		$item = Array();
		$item['name'] = getTranslatedString($menudb->fields['name']);
		$item['title'] = getTranslatedString($menudb->fields['title']);
		$item['action'] = $menudb->fields['action'];
		if($item['action'] == 'OPEN_WINDOWS')
		{
			$item['target'] = "window.open('{$menudb->fields['target']}');";
			$menus[] = $item;
		}
		elseif($item['action'] == 'OPEN_MODULE')
		{
			$module = $menudb->fields['target'];
			if($CURRENT_IS_ADMIN || validationModulePermission($module))
			{
				$moduledb = $APP_ADODB->Execute("select * from modules where module_name ='{$module}' limit 1;");
				if(!$moduledb->EOF)
				{
					$targetUrl = _INDEX_URL."?module={$module}&action={$moduledb->fields['default_action']}";
					$item['target'] = "javascript:zswitch_load_client_view('{$targetUrl}');";
					$menus[] = $item;
				}
			}	
		}
		elseif($item['action'] == 'SUB_MENU')
		{
			$modulesdb = $APP_ADODB->Execute("select * from modules where menuid={$menudb->fields['id']} order by seq");
			$submenu = Array();
			while(!$modulesdb->EOF)
			{
				$module = $modulesdb->fields['module_name'];
				if($CURRENT_IS_ADMIN || validationModulePermission($module))
				{
					$subitem = Array();
					$targetUrl = _INDEX_URL."?module={$module}&action={$modulesdb->fields['default_action']}";
					$subitem['name'] = getTranslatedString($module);
					$subitem['title'] = getTranslatedString($modulesdb->fields['module_describe']);
					$subitem['target'] = "javascript:zswitch_load_client_view('{$targetUrl}');";
					$submenu[] = $subitem;
				}
				$modulesdb->MoveNext();
			}
			if(count($submenu) > 0)
			{
				$item['submenu'] = $submenu;
				$menus[] = $item;
			}	
		}
	
		$menudb->MoveNext();
	}
	return $menus;	
}
?>