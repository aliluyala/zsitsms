<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
if(!$CURRENT_IS_ADMIN)
{
	die('你无权修改！');	
}
if(!isset($_GET['name']))
{
	die();
}

$result = $APP_ADODB->Execute("select * from modules where module_name ='{$_GET['name']}'");
if($result && !$result->EOF)
{
	echo $result->fields['id'];
	die();
}

$name = $_GET['name'];
$describe = '';
$action = 'index';
$menuid = -1;

$modPath = _ROOT_DIR.'/modules/';
if(!is_dir($modPath.$name))
{
	die();
}
$moduleClassName = "{$name}Module";
$moduleClassFile = $modPath."{$name}/{$name}Module.class.php";
if(is_file($moduleClassFile)) require_once($moduleClassFile);
if(class_exists($moduleClassName))
{
	$mod = new $moduleClassName();
	if(isset($mod->describe)) $describe = $mod->describe;
}
$APP_ADODB->Execute("insert into modules(menuid,module_name,module_describe) values({$menuid},'{$name}','{$describe}')");
$result = $APP_ADODB->Execute("select id from modules where module_name='{$name}'");
if($result && !$result->EOF)
{
	echo $result->fields['id'];
}
else
{
	echo -1;
}	
?>