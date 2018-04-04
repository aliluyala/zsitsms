<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
if(!$CURRENT_IS_ADMIN) 
{
	return_ajax('error','你无权进行该项操作!');
	die();
}	
if(empty($_GET['recordid']))
{
	return_ajax('error','记录ID不存在,删除失败!');
	die();
}
$id = $_GET['recordid'];
$result = $APP_ADODB->Execute("select user_name from users where permissionid = {$id};");
if($result && $result->RecordCount()>0)
{
	$cou =  $result->RecordCount();
	$users = '';
	$count = 0;
	while(!$result->EOF)
	{
		$count ++;
		if($count > 10 ) 
		{
			$users .= ".........<br/>";
			break;
		}
		else
		{
			$users .= $result->fields['user_name']."<br/>";
		}	
		$result->MoveNext();
	}
	return_ajax('error',sprintf("你要删除的权限仍有以下%d个用户关联:<br/>%s<br/>因些不能删除！",$cou,$users));
	die();	
}

$APP_ADODB->Execute("delete from permission_fields where permissionid = {$id};");
$APP_ADODB->Execute("delete from permission_actions where permissionid = {$id};");
$APP_ADODB->Execute("delete from permission_modules where permissionid = {$id};");
$APP_ADODB->Execute("delete from permission where id = {$id};");
cleanPermissionfile();	
buildPermissionFile($id);
return_ajax('rediect',"index.php?module=PermissionManager&action=index");
?>