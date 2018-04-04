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

$modname = $_GET['module_name'];
$modc = "{$modname}Module";
@include_once(_ROOT_DIR."/modules/{$modname}/{$modc}.class.php");

if(!class_exists($modc))
{
	return_ajax('error',"模块不存在！");
	die();		
}

if(!property_exists($modc,'fields') )
{
	return_ajax('error',"模块没有字段可设置！");
	die();		
}

$modobj = new $modc();
$fields = array();

foreach($modobj->fields as $fld => $set)
{
	if($set[0] == '27')
	{
		$fields[] = array($fld,getModuleTranslatedString($fld,$modname));
	}
}
return_ajax('success',array('module_name'=>$modname,'module_show_name'=>getTranslatedString($modname),'fields'=>$fields));
?>