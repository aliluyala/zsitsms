<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage1.tpl');
	die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();
}

$step = '1';
if(isset($_GET['step']))
{
	$step = $_GET['step'];
}

if($CURRENT_IS_ADMIN)
{
	$modFields = $mod->batchEditFields;
}
else
{
	$modFields = getFieldsModifyPermission($module);
	if($modFields === true)
	{
		$modFields = $mod->batchEditFields;
	}
	elseif($modFields === false)
	{
		$modFields = Array();
	}
	foreach($modFields as $idx => $field_name)
	{
		if(!in_array($field_name,$mod->batchEditFields)) unset($modFields[$idx]);
	}
}

if($step == '1')
{
	$editview_datas = createEditViewUI($module,$mod->fields,$mod->batchEditFields,$modFields,1,
									   Array(),$mod->associateTo,$mod->picklist,null,'edit');

	$smarty->assign('EDITVIEW_DATAS',$editview_datas);
	$smarty->display('BatchModifyView1.tpl');
}
if($step == '2')
{
	if(isset($_POST['modify_fileds']) && is_array($_POST['modify_fileds']))
	{
		foreach($_POST['modify_fileds'] as $idx => $field_name)
		{
			if(!in_array($field_name,$modFields)) unset($_POST['modify_fileds'][$idx]);
		}
		$modFields = $_POST['modify_fileds'];
	}
	else
	{
		$modFields = Array();
	}
	$datas = Array();

	foreach($modFields as $field_name)
	{
		$datas[$field_name] = $_POST[$field_name];
	}

	if(!$CURRENT_IS_ADMIN)
	{
		$datas = validationFieldsModifyPermission($module,$datas);
	}

	$queryWhere = Array();
	if($_POST['modify_query_where'] == 'current_select' )
	{
		$queryWhere = Array(
			Array('id','IN',"({$_POST['select_recordid_list']})",''),
		);
	}
	elseif($_POST['modify_query_where'] == 'current_search' )
	{
		if(isset($_SESSION[_SESSION_KEY]['listview_query_where'][$module]))
		{
			$queryWhere = $_SESSION[_SESSION_KEY]['listview_query_where'][$module];
		}
	}
	$filterWhere = Array();
	if(isset($_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]))
	{
		$filterWhere = getListViewFilterWhere($_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]);
	}
	$limit = NULL;
	if(isset($_POST['modify_count']))
	{
		$limit = $_POST['modify_count'];
	}
	$orderby = '';
	$order = 'NONE';
	if(isset($_SESSION[_SESSION_KEY]['listview_order_by'][$module]) && isset($_SESSION[_SESSION_KEY]['listview_order'][$module]))
	{
		$orderby = $_SESSION[_SESSION_KEY]['listview_order_by'][$module];
		$order = $_SESSION[_SESSION_KEY]['listview_order'][$module];
	}
	$userids = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module);
	$groupids = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module);
	if(!isset($_SESSION[_SESSION_KEY]['listview_batch_modify_params']))
	{
		$_SESSION[_SESSION_KEY]['listview_batch_modify_params'] = Array();
	}
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module] = Array();
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['queryWhere'] = $queryWhere;
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['filterWhere'] = $filterWhere;
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['orderby'] = $orderby;
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['order'] = $order;
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['userids'] = $userids;
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['groupids'] = $groupids;
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['datas'] = $datas;
	$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['limit'] = $limit;
	$count = $mod->batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit);
	$smarty->assign('MODULE_LABEL',getTranslatedString($module));
	$smarty->assign('MODIFY_COUNT',$count);
	$smarty->display('BatchModifyView2.tpl');
}
elseif($step == '3')
{
	if(isset($_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]) &&
		is_array($_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]))
	{
		$queryWhere=$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['queryWhere'] ;
		$filterWhere=$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['filterWhere'] ;
		$orderby=$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['orderby'];
		$order=$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['order'] ;
		$userids=$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['userids'] ;
		$groupids=$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['groupids'] ;
		$limit=$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['limit'];
		$datas=$_SESSION[_SESSION_KEY]['listview_batch_modify_params'][$module]['datas'];
		$count = $mod->batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,false,$datas);
		$smarty->assign('MODULE_LABEL',getTranslatedString($module));
		$smarty->assign('MODIFY_COUNT',$count);
		$smarty->display('BatchModifyView3.tpl');
	}
	else
	{
		$smarty->assign('ERROR_MESSAGE','系统错误，操作失败！');
		$smarty->display('ErrorMessage1.tpl');
	}
}


?>