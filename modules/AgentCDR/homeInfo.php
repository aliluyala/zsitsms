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
if(!class_exists('AgentCDRModule'))
{
	require(_ROOT_DIR."/modules/AgentCDR/AgentCDRModule.class.php");
	$mod = new AgentCDRModule();	
}

$listFields = Array(
				'other_number'     ,				
	            'dir'              ,
				'agent_name'       ,				
	            'created_datetime' ,
	            'hangup_cause'     ,				
			);
$headers = createListViewHeaders($listFields,Array(),'','','','');
$userids = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module);
$groupids = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module);
$list_data = Array();
$result = $mod->getListQueryRecord(Array(Array('userid','=',$CURRENT_USER_ID,'','')),Array(),'created_datetime','DESC',$userids,$groupids,0,19);
if($result)
{
	if(!$CURRENT_IS_ADMIN) $result = validationFieldsShowPermission($module,$result);
	$list_data = formatListDatas($result,'AgentCDR','id',$mod->fields,$listFields,'other_number',$mod->picklist,Array());
}
$smarty->assign('LIST_DATA',$list_data);
$smarty->assign('HEADERS',$headers);
$smarty->display('AgentCDR/HomeInfo.tpl');

?>