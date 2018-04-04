<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$module_label =  getTranslatedString(_MODULE);
$action_label =  getTranslatedString(_ACTION);
$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();	
}
global $show_field;
if(!isset($show_field))	$show_field = $_GET['showField'];


global $start;
if(!isset($start))
{
	if(!isset($_GET['start']))
	{
		$start = 0;
	}	
	else 
	{
		$start = $_GET['start'];
	}
}
global $page_maxrows;
if(!isset($page_maxrows)) $page_maxrows = 10;

global $page_ctrl;
if(!isset($page_ctrl)) 
{
	if(isset($_GET['pageCtrl'])) $page_ctrl = $_GET['pageCtrl'];
	else $page_ctrl = '';
}
if($page_ctrl == 'next') 
{
	$start  += 10;
}	
elseif($page_ctrl == 'back')
{
	$start  -= 10;
}

global $search_value;
if(empty($search_value) && !empty($_GET['searchValue']))
{
	$search_value = $_GET['searchValue'];
}

global $list_fields;
if(!isset($list_fields)) 
{
	if(isset($_GET['list_fields']))
	{
		$list_fields = $_GET['list_fields'];
	}
	else
	{
		$list_fields = $show_field;
	}
}

global $list_filter_field;
if(!isset($list_filter_field))
{
	if(isset($_GET['list_filter_field']))
	{
		$list_filter_field = $_GET['list_filter_field'];
	}
	else
	{
		$list_filter_field = '';
	}	
}

global $list_filter_value;
if(!isset($list_filter_value))
{
	if(isset($_GET['list_filter_value']))
	{
		$list_filter_value = $_GET['list_filter_value'];
	}
	else
	{
		$list_filter_value = '';
	}	
}

$list_fields_arr = explode(',',$list_fields);
global $list_headers;
if(!isset($list_headers))
{
	$list_headers = Array();
	foreach($list_fields_arr as $fldname)
	{
		$list_headers[$fldname] = getTranslatedString($fldname);
	}
} 

global $where;
if(!isset($where))
{
	$where = Array();
	if(!empty($search_value))
	{
		$where[0] = Array($show_field,'like_contain',"{$search_value}",'');
	}
	if(!empty($list_filter_field) )
	{
		if(empty($where))
		{
			$where[0] = Array($list_filter_field,'=',"{$list_filter_value}",'');
		}
		else
		{
			$where[0][3] = 'and';
			$where[1] = Array($list_filter_field,'=',"{$list_filter_value}",'');
		}	
		
	}
}
$record_total = $mod->getListQueryRecordCount(
									$where,
									Array(),
									$CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
									$CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
									);
if($start<0) $start =0 ;
if($start>=$record_total)
{
	$start = floor($record_total/$page_maxrows)*$page_maxrows;
}


$associate_list = Array();
if($record_total>0)
{
	$result = $mod->getListQueryRecord($where,
								 Array(),
								 '','',
								 $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
								 $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module),
								 $start,$page_maxrows);								
	
	$associate_list = formatListDatas($result,$module,'id',$mod->fields,$list_fields_arr,null,$mod->picklist,$mod->associateTo);

}
$smarty->assign('LIST_FILTER_FIELD',$list_filter_field);
$smarty->assign('LIST_FILTER_VALUE',$list_filter_value);
$smarty->assign('LIST_FIELDS',$list_fields);
$smarty->assign('LIST_HEADERS',$list_headers);
$smarty->assign('SEARCHDLGID',$_GET['searchdlgid']);
$smarty->assign('RECORD_START',$start);
$smarty->assign('MODULE',$module);
$smarty->assign('ASSOCIATE_LIST',$associate_list);
$smarty->assign('SHOW_FIELD',$show_field);
$smarty->assign('SEARCH_VALUE',$search_value);
$smarty->assign('SHOWFIELD_LABEL',getTranslatedString($show_field));
$smarty->display('AssociateSelecter.tpl');
?>