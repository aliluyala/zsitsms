<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN)
{
	die('你的权限不够！');
}

$mod_name = $_GET['pmod'];
$pid = $_GET['pid'];
$new_pid = $_GET['new_pid'];
if(empty($new_pid))
{
	$sess_pfx = "{$CURRENT_USER_ID}_{$mod_name}_{$pid}_fields_permission";
}
else
{
	$sess_pfx = "{$CURRENT_USER_ID}_{$mod_name}_{$new_pid}_fields_permission";
}
$share_fields = Array();
if(!empty($_SESSION[$sess_pfx]))
{
	foreach($_SESSION[$sess_pfx] as $field_name=>$info)
	{
		$fld = Array();
		$fld['label'] = getModuleTranslatedString($field_name,$mod_name);
		$fld['show'] = ($info['is_show'] == 'YES');
		$fld['modify'] = ($info['is_modify'] == 'YES');
		$fld['hidden_start'] =  $info['hidden_start'];
		$fld['hidden_end'] =  $info['hidden_end'];
		$fld['hidden'] = ($fld['hidden_start']>=0 && $fld['hidden_end'] > 0);
		$share_fields[$field_name] = $fld;
	}
}

$smarty->assign('SHARE_FIELDS',$share_fields);
$smarty->display('PermissionManager/editFieldView.tpl');
?>
