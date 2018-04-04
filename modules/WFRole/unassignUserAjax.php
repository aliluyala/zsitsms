<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;


if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','你没有权限');
	die();
}

if(!array_key_exists('role_guid',$_GET) || !array_key_exists('role_type',$_GET) ||
   !array_key_exists('user_id',$_GET) || !array_key_exists('user_type',$_GET))
{
	return_ajax('error','参数错误');
	die();
}
$role_guid = $_GET['role_guid'];
$role_type = $_GET['role_type'];

$user_id = $_GET['user_id'];
$user_type = $_GET['user_type'];

require(_ROOT_DIR.'/include/workflow/PMApi.class.php');
$pmconf = require(_ROOT_DIR.'/config/workflow.conf.php');
$pmws = new PMApi($pmconf);


if($user_type == '用户')
{
	
	if($role_type == 'PRO')
	{
	
		if($pmws->removeProcessManager($user_id,0,$role_guid))
		{
			return_ajax('success','成功');			
		}
		else
		{
			return_ajax('error','失败');
			
		}
	}
	elseif($role_type == 'TAS')
	{
		
		if($pmws->removeTaskUser($user_id,0,$role_guid))
		{
			return_ajax('success','成功');			
		}
		else
		{
			return_ajax('error','失败');
			
		}		
	}
}
elseif($user_type == '工作组')
{
	if($role_type == 'PRO')
	{
		if($pmws->removeProcessManager($user_id,1,$role_guid))
		{
			return_ajax('success','成功');
			
		}
		else
		{
			return_ajax('error','失败');
			
		}
	}
	elseif($role_type == 'TAS')
	{
		if($pmws->removeTaskUser($user_id,1,$role_guid))
		{
			return_ajax('success','成功');
			
		}
		else
		{
			return_ajax('error','失败');
			
		}		
	}	
}




?>