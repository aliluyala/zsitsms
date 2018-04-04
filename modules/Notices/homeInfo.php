<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage1.tpl');
	die();
}

global $mod;
if(!class_exists('NoticesModule'))
{
	require(_ROOT_DIR."/modules/Notices/NoticesModule.class.php");
	$mod = new NoticesModule();	
}

$listFields = Array(
			'tag'            ,
			'title'          ,
			'date_create'    ,
			);
$headers = createListViewHeaders($listFields,Array(),'','','','');
$userids = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module);
$groupids = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module);
$list_data = Array();
$result = $mod->getListQueryRecord(Array(),Array(),'date_create','DESC',$userids,$groupids,0,25);
if($result)
{
	if(!$CURRENT_IS_ADMIN) $result = validationFieldsShowPermission($module,$result);
	$list_data = formatListDatas($result,'Notices','id',$mod->fields,$listFields,'title',$mod->picklist,Array());
}
$smarty->assign('LIST_DATA',$list_data);
$smarty->assign('HEADERS',$headers);
$smarty->display('Notices/HomeInfo.tpl');
?>