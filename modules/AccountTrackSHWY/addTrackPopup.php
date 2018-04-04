<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$smarty->assign('MODULE',$module);
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();
}
$accountid = -1;
if(!empty($_GET['accountid'])) $accountid = $_GET['accountid'];

$focus;
if(is_file(_ROOT_DIR."/modules/AccountsSHWY/AccountsSHWYModule.class.php"))
{
    require_once(_ROOT_DIR."/modules/AccountsSHWY/AccountsSHWYModule.class.php");
    $modclass= "AccountsSHWYModule";
    $focus = new $modclass();
}
$accountInfo = $focus->getOneRecordset($accountid,
                                        $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
                                        $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
                                       );
/*if($accountInfo[0]['status'] == "APPOINTMENT_QUOTATION")
    array_splice($mod->picklist['status'], 1, 1);*/
$recordid = getNewModuleSeq($mod->baseTable);
$status_val = isset($accountInfo[0]['status']) ? $accountInfo[0]['status'] : null;
$report_val = isset($accountInfo[0]['report']) ? $accountInfo[0]['report'] : null;
//$title = createFieldUI('title',$mod->fields['title'],null,null,Array());
$intention   = createFieldUI('intention',$mod->fields['intention'],null,$mod->picklist['intention'],array());
$status      = createFieldUI('status',$mod->fields['status'],$status_val,$mod->picklist['status'],array());
$report      = createFieldUI('report',$mod->fields['report'],$report_val,$mod->picklist['report'],array());
$preset_time = createFieldUI('preset_time',$mod->fields['preset_time'],Date('Y-m-d',strtotime("tomorrow")) . Date(" H:i:s"),null,array());
$remark      = createFieldUI('remark',$mod->fields['remark'],null,null,Array());
$smarty->assign('ACCOUNTID',$accountid);
$smarty->assign('RECORDID',$recordid);
//$smarty->assign('TITLE_FIELD',$title);
$smarty->assign('INTENTION_FIELD',$intention);
$smarty->assign('STATUS_FIELD',$status);
$smarty->assign('DESC_FIELD',$report);
$smarty->assign('PRESET_TIME_FIELD',$preset_time);
$smarty->assign('REMARK_FIELD',$remark);
$smarty->display("{$module}/AddTrackPopup.tpl");
?>