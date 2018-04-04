<?php

global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
//工具条
global $toolsbar;
if(!isset($toolsbar)) $toolsbar = createToolsbar(Array('calendar','calculator','email','sms','phone'));
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


//记录ID
global $recordid;
if(!isset($recordid)) 	$recordid = $_GET['recordid'];
//翻页控制
if(!empty($_GET['operation']))
{
	$qwhere = Array();
	$filter_where = Array();
	$orderby = '';
	$order = '';
	if(isset($_SESSION[_SESSION_KEY]['listview_query_where'][$module]))
	{
		$qwhere = $_SESSION[_SESSION_KEY]['listview_query_where'][$module];
	}

	if(isset($_SESSION[_SESSION_KEY]['listview_order_by'][$module]))
	{
		$orderby = $_SESSION[_SESSION_KEY]['listview_order_by'][$module];
	}

	if(isset($_SESSION[_SESSION_KEY]['listview_order'][$module]))
	{
		$order = $_SESSION[_SESSION_KEY]['listview_order'][$module];
	}
	if(isset($_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]) and $_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]>=0)
	{
		$filter_where = getListViewFilterWhere($selected_filter);
	}


	if($_GET['operation'] == 'prev')
	{
		$recordid = $mod->getPrevOneRecordsetID($recordid,
										  $qwhere,
										  $filter_where,
										  $orderby,
										  $order,
										  $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
										  $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
										  );
	}
	elseif($_GET['operation'] == 'next')
	{
		$recordid = $mod->getNextOneRecordsetID($recordid,
										  $qwhere,
										  $filter_where,
										  $orderby,
										  $order,
										  $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
										  $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
										  );

	}
}

$smarty->assign('DETAILVIEW_RECORDID',$recordid);
//模块详情选项页标签
global $detailview_label;
if(!isset($detailview_label)) $detailview_label = getTranslatedString(_MODULE.'_DETAILVIEW_LABEL');
$smarty->assign('MODULE_DETAILVIEW_LABEL',$detailview_label);
//右侧操作按钮
global $detailview_buttons;
if(!isset($detailview_buttons)) $detailview_buttons = createDetailviewButtons('all');
$smarty->assign('DETAILVIEW_BUTTONS',$detailview_buttons);

global $return_module;
if(!isset($return_module))
{
	if(isset($_GET['return_module'])) $return_module = $_GET['return_module'];
	else $return_module = $module;
}

global $return_action;
if(!isset($return_action))
{
	if(isset($_GET['return_action'])) $return_action = $_GET['return_action'];
	else $return_action = 'index';
}

global $return_recordid;
if(!isset($return_recordid))
{
	if(isset($_GET['return_recordid'])) $return_recordid = $_GET['return_recordid'];
	else $return_recordid = $recordid;
}

$smarty->assign('RETURN_MODULE',$return_module);
$smarty->assign('RETURN_ACTION',$return_action);
$smarty->assign('RETURN_RECORDID',$return_recordid);

//模块关联信息
global $associateby;
if(!isset($associateby))
{
	if(!isset($mod->associateBy)) $associateby = createDetailviewAssociateTabs(null,null);
	else $associateby = createDetailviewAssociateTabs($mod->associateBy,$recordid);
}
$smarty->assign('MODULE_DETAILVIEW_ASSOCIATEBY',$associateby);

global $key_name;
if(!isset($key_name)) $key_name = 'id';

global $details_datas;
if(!isset($details_datas))
{

	$result = $mod->getOneRecordset($recordid,
									$CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
									$CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
									);

	if($result)
	{
		if($CURRENT_IS_ADMIN)
		{
			$mod_fields = $mod->missEditFields;
			$result = $result[0];
		}
		else
		{
			$mod_fields = getFieldsModifyPermission($module);
			if($mod_fields === true)
			{
				$mod_fields = $mod->missEditFields;
			}
			elseif($mod_fields === false)
			{
				$mod_fields = Array();
			}
			else
			{
				foreach($mod_fields as $idx => $field_name)
				{
					if(!in_array($field_name,$mod->missEditFields)) unset($mod_fields[$idx]);
				}
			}
			$result = validationFieldsShowPermission($module,$result);
			$result = $result[0];
		}
		$details_datas = formatDetailviewDatas($result,$module,$key_name,$mod->fields,$mod_fields,$mod->defaultColumns,$mod->picklist,$mod->associateTo,$mod->blocks);
	}
}

global $custom_buttons;
if(!isset($custom_buttons)) $custom_buttons = Array();
$smarty->assign('DETAILVIEW_CUSTOM_BUTTONS',$custom_buttons);

global $auto_execute;
if(!isset($auto_execute)) $auto_execute = '';
$smarty->assign('DETAILVIEW_AUTO_EXECUTE',$auto_execute);

$smarty->assign('DETAILVIEW_DATAS',$details_datas);
global $tpl_file;
if(!isset($tpl_file)) $tpl_file = 'DetailView.tpl';
$smarty->display($tpl_file);
?>