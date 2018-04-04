<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','你的权限不能进行该项操作！');
	die();
}

if(!array_key_exists('module_name',$_GET))
{
	return_ajax('error','参数错误！');
	die();	
}
include_once(_ROOT_DIR."/modules/Dropdown/DropdownModule.class.php");
$mod = new DropdownModule();
$modname = $_GET['module_name'];
$field = $_GET['field'];
$sql = "select * from dropdown where module_name='{$modname}' and field='{$field}';";
$result = $APP_ADODB->Execute($sql);
$options_text = '';
$options = array();
while(!$result->EOF)
{
	if(!array_key_exists($result->fields['group_name'],$options))
	{
		$options[$result->fields['group_name']] = array();
	}
	$options[$result->fields['group_name']][] = array($result->fields['save_value'],$result->fields['show_value']);
	$result->MoveNext();
}
foreach($options as $grp => $opts)
{
	if(!empty($grp))
	{
		$options_text .= "-----{$grp}-----\r\n";
	}
	foreach($opts as $opt)
	{
		$options_text .= "{$opt[0]}  {$opt[1]}\r\n";
	}
}
return_ajax('success',$options_text);
?>