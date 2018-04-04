<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
global $toolsbar;
if(!isset($toolsbar)) $toolsbar = createToolsbar('all');

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

//表头控制条
global $bar_allow;
if(!isset($bar_allow)) $bar_allow = true;
$smarty->assign('LISTVIEW_BAR_ALLOW',$bar_allow);
if($bar_allow)
{
	//表头按键
	global $bar_buttons;
	if(!isset($bar_buttons)) $bar_buttons = createListViewButtons('all');
	$smarty->assign('LISTVIEW_BUTTONS',$bar_buttons);
}

global $selecter_allow;
if(!isset($selecter_allow)) $selecter_allow = true;
$smarty->assign('LISTVIEW_SELECTER_ALLOW',$selecter_allow);

if(!isset($_SESSION[_SESSION_KEY]['listview_selected_filter'])) $_SESSION[_SESSION_KEY]['listview_selected_filter'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['listview_query_where'])) $_SESSION[_SESSION_KEY]['listview_query_where'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['listview_order_by'])) $_SESSION[_SESSION_KEY]['listview_order_by'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['listview_order'])) $_SESSION[_SESSION_KEY]['listview_order'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['listview_record_page'])) $_SESSION[_SESSION_KEY]['listview_record_page'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['detialview_record_current_offset'])) $_SESSION[_SESSION_KEY]['detialview_record_current_offset'] = Array();

//过滤
global $selected_filter;
if(!isset($selected_filter)) 
{	
	if(isset($_POST['filterid'])) 
	{
		$selected_filter = $_POST['filterid'];
		$_SESSION[_SESSION_KEY]['listview_selected_filter'][$module] =  $_POST['filterid'];
	}	
	elseif(isset($_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]))
	{
		$selected_filter = $_SESSION[_SESSION_KEY]['listview_selected_filter'][$module];
	}
	else 
	{
		$selected_filter = -1;
	}	
}	

$smarty->assign('LISTVIEW_SELECTED_FILTER',$selected_filter);
global $filter_list ;
if(!isset($filter_list))
{
	$filter_list = createListViewFilters($CURRENT_USER_ID,$CURRENT_USER_GROUPID,$module);
}
$smarty->assign('LISTVIEW_FILTER_LIST',$filter_list);


//记录操作
global $operation_allow ,$operations ;
if(!isset($operation_allow)) $operation_allow =true;
$smarty->assign('LISTVIEW_OPERATION_ALLOW',$operation_allow);

if(!isset($operations))
{
	$operations = Array();
	if($operation_allow)
	{
		$operations[] = Array('name'=>getTranslatedString('编辑'),
							  'url'=>"zswitch_listview_operation_edit('{$module}',$(this).attr('recordid'));");
		$operations[] = Array('name'=>getTranslatedString('删除'),
		                      'url'=>"zswitch_listview_operation_delete('{$module}',$(this).attr('recordid'));");
	}
}
$smarty->assign('LISTVIEW_OPERATIONS',$operations);
//表头
$order_by = '';
$order = 'NONE';
if(!isset($headers))
{
	if(isset($_POST['order_by']) && isset($_POST['order']))
	{	
		$headers = createListViewHeaders($mod->listFields,$mod->orderbyFields,$mod->defaultOrder[0],
						$mod->defaultOrder[1],$_POST['order_by'],$_POST['order']);
		$order_by = $_POST['order_by'];
		$order = $_POST['order'];
	}
	elseif(isset($_SESSION[_SESSION_KEY]['listview_order_by'][$module]) && isset($_SESSION[_SESSION_KEY]['listview_order'][$module]))
	{
		$headers = createListViewHeaders($mod->listFields,$mod->orderbyFields,$mod->defaultOrder[0],
						$mod->defaultOrder[1],$_SESSION[_SESSION_KEY]['listview_order_by'][$module],$_SESSION[_SESSION_KEY]['listview_order'][$module]);
		$order_by = $_SESSION[_SESSION_KEY]['listview_order_by'][$module];
		$order = $_SESSION[_SESSION_KEY]['listview_order'][$module];		
	}
	else
	{
		$headers = createListViewHeaders($mod->listFields,$mod->orderbyFields,$mod->defaultOrder[0],
						$mod->defaultOrder[1],null,null);		
	}
}
$smarty->assign('LISTVIEW_HEADERS', $headers);


global $query_where ;
if(!isset($query_where))
{
	if(isset($_POST['query_where']))
	{
		$query_where = json_decode($_POST['query_where']);
	}
	elseif(isset($_SESSION[_SESSION_KEY]['listview_query_where'][$module]))
	{
		$query_where = $_SESSION[_SESSION_KEY]['listview_query_where'][$module];
	}
	else
	{
		$query_where = Array();
	}		
}	

if(empty($query_where))
{
	$smarty->assign('HAVE_QUERY_WHERE', false);
}
else
{
	$smarty->assign('HAVE_QUERY_WHERE', true);
}

global $search_ui;
if(!isset($search_ui))
{
	$search_ui = createListSearchUI($mod->orderbyFields,$mod->fields,$mod->picklist,$mod->associateTo);
	foreach($search_ui as $fld => $uie)
	{
		$smarty->assign('FIELDINFO',$uie);
		$search_ui[$fld]['html'] = $smarty->fetch('UI/'.$uie['UI'].'.UI.tpl');
	}	
}

global $filter_where;

if(!isset($filter_where))
{
	
	if($selected_filter>=0)
	{
		
		$filter_where = getListViewFilterWhere($selected_filter);
	}
	else
	{
		$filter_where = Array();
	}	
}	

global $list_max_rows;
if(!isset($list_max_rows))
{
	if(isset($mod->listMaxRows)) $list_max_rows = $mod->listMaxRows;
	else $list_max_rows = 25;
}
global $keyName;
if(!isset($keyName)) $keyName = 'id';

global $list_data,$record_count,$record_start,$record_end,$record_pagecount,$record_page;
if(!isset($list_data))
{
	$userids = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module);
	$groupids = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module);
	$list_max_rows = $mod->listMaxRows;
	$record_total = $mod->getListQueryRecordCount(
									$query_where,
									$filter_where,
									$userids,
									$groupids
									);
	$list_data = Array();

	if($record_total > 0)
	{
		$record_pagecount = floor(($record_total-1)/$list_max_rows)+1;
		$record_page = 1;
		if(isset($_POST['record_page']))
		{
			$record_page = $_POST['record_page'];
		}
		elseif(isset( $_SESSION[_SESSION_KEY]['listview_record_page'][$module]))
		{
			$record_page =  $_SESSION[_SESSION_KEY]['listview_record_page'][$module];
		}
		
		if($record_page > $record_pagecount) $record_page = $record_pagecount;
		elseif($record_page < 1) $record_page = 1;
		$record_start = ($record_page-1) * $list_max_rows ;
		
		$result = $mod->getListQueryRecord(									
									$query_where,
									$filter_where,
									$order_by,
									$order,
									$userids,
									$groupids,
									$record_start,
									$list_max_rows);
		if($result)
		{		
			$record_count = count($result);
			$record_end = $record_start + $record_count - 1;	
			if(!$CURRENT_IS_ADMIN) $result = validationFieldsShowPermission($module,$result);
			$list_data = formatListDatas($result,$module,$keyName,$mod->fields,$mod->listFields,$mod->enteryField,$mod->picklist,$mod->associateTo);
		}
		else
		{
			
			$record_end = $record_start;			
		}		
	}
	else
	{
		$record_pagecount = 0; 	
		$record_page = 0;
		$record_start =0 ;
		$record_end = 0 ;	
	}
}

$_SESSION[_SESSION_KEY]['listview_query_where'][$module] = $query_where;
$_SESSION[_SESSION_KEY]['listview_order_by'][$module] = $order_by;
$_SESSION[_SESSION_KEY]['listview_order'][$module] = $order;
$_SESSION[_SESSION_KEY]['listview_record_page'][$module] = $record_page;
if(isset($_SESSION[_SESSION_KEY]['detialview_record_current_offset'][$module]))
{
	unset($_SESSION[_SESSION_KEY]['detialview_record_current_offset'][$module]);	
}

$smarty->assign('LSITVIEW_SEARCH_UI',$search_ui);
$smarty->assign('LSITVIEW_SEARCH_UI_JSON',rawurlencode(json_encode($search_ui)));
$smarty->assign('LISTVIEW_DATA',$list_data);  
$smarty->assign('LISTVIEW_RECORD_START',$record_start);
$smarty->assign('LISTVIEW_RECORD_TOTAL',$record_total);
$smarty->assign('LISTVIEW_RECORD_END',$record_end);
$smarty->assign('LISTVIEW_RECORD_PAGE',$record_page);
$smarty->assign('LISTVIEW_RECORD_PAGECOUNT',$record_pagecount);
$smarty->assign('LISTVIEW_ORDER_BY',$order_by);
$smarty->assign('LISTVIEW_ORDER',$order);
$smarty->assign('LISTVIEW_QUERY_WHERE',$query_where);
$smarty->assign('LISTVIEW_QUERY_WHERE_JSON',rawurlencode(json_encode($query_where)));
global $tpl_file;
if(!isset($tpl_file)) $tpl_file = 'ListViewA.tpl';
$smarty->display($tpl_file);
?>
