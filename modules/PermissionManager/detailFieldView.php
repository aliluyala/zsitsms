<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN)
{
	die('你的权限不够！');
}
$share_fields = Array();
$pid = $_GET['pid'];
$pmod = $_GET['pmod'];
$sql = "select * from permission_fields where permissionid={$pid} and module_name = '{$pmod}'";
$result = $APP_ADODB->Execute($sql);
while(!$result->EOF)
{
	$fld = Array();
	$fld['label'] = getModuleTranslatedString($result->fields['field_name'],$pmod);
	$fld['show'] = ($result->fields['is_show'] == 'YES');
	$fld['modify'] = ($result->fields['is_modify'] == 'YES');
	$fld['hidden_start'] =  $result->fields['hidden_start'];
	$fld['hidden_end'] =  $result->fields['hidden_end'];
	$fld['hidden'] = ($fld['hidden_start']>=0 && $fld['hidden_end'] > 0);
	$share_fields[$result->fields['field_name']] = $fld;
	$result->MoveNext();
}

$smarty->assign('SHARE_FIELDS',$share_fields);
$smarty->display('PermissionManager/detailFieldView.tpl');
?>