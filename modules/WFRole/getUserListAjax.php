<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$data = array('total'=>0,'page'=>0,'records'=>0,'rows'=>array());

if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	die(json_encode($data));
}

require(_ROOT_DIR.'/include/workflow/PMApi.class.php');
$pmconf = require(_ROOT_DIR.'/config/workflow.conf.php');
$pmws = new PMApi($pmconf);

if(!array_key_exists('role_type',$_POST) || !array_key_exists('guid',$_POST) || !array_key_exists('data_type',$_GET))
{
	die(json_encode($data));
}

$data_type = $_GET['data_type'];
$role_type = $_POST['role_type'];
$guid = $_POST['guid'];

$page = 1;
if(array_key_exists('page',$_POST)) $page = $_POST['page'];
$data['page'] = $page;
$total = 0;
$records = 0;	
$rows = 200;
if(array_key_exists('rows',$_POST)) $rows = $_POST['rows'];
$sidx = '';
if(array_key_exists('sidx',$_POST)) $sidx = $_POST['sidx'];
$sord = '';
if(array_key_exists('sord',$_POST)) $sord = $_POST['sord'];

//$order = '';
//if(!empty($sidx) && !empty($sord) )
//{
//	$order = " order by {$sidx} {$sord} ";
//}

if(!$pmws->login(null,null,true))
{
	die(json_encode($data));	
}

if($data_type == 'validated')
{
	$userList = $pmws->getUserList();
	$groupList =  $pmws->getGroupList();	
	
	if(!$userList) $userList = array();
	if(!$groupList) $groupList = array();
	
	$excludeGroups = array();
	$excludeUsers = array();
	if($role_type == 'PRO')
	{
		$excludeGroups = $pmws->getProcessAsGroups($guid);
		$excludeUsers = $pmws->getProcessAsUsers($guid);
	}
	elseif($role_type == 'TAS')
	{
		$excludeGroups = $pmws->getTaskAsGroups($guid);
		$excludeUsers = $pmws->getTaskAsUsers($guid);		
	}
	
	if(!$excludeGroups) $excludeGroups = array();
	if(!$excludeUsers) $excludeUsers = array();
	
	$exgrp = array();
	foreach($excludeGroups as $v)
	{
		$exgrp[] = $v['grp_uid'];
	}
	$exusr = array();
	foreach($excludeUsers as $v)
	{
		$exusr[] = $v['usr_uid'];
	}
	
	$groups = array();	
	foreach($groupList as  $g)
	{
		if(!in_array($g['guid'],$exgrp)) $groups[] = $g;
	}
	
	
	foreach($excludeGroups as $eg)
	{
		$gusers = $pmws->getGroupUsers($eg['grp_uid']);		
		if(!empty($gusers)) $exusr = array_merge($exusr , $gusers) ;
	}
    
	
	$users = array();
	foreach($userList as $u )
	{
		if(!in_array($u['guid'],$exusr)) $users[] = $u;
	}
	
	$userCount = count($users);
	$groupCount = count($groups);
	
	
	
	$records = $userCount + $groupCount;
	$total = ceil($records/$rows);
	$data['total'] = $total;
	$data['records'] = 	$records;
	
	$offset = $rows * ($page-1);
	
	
	
	if($groupCount > $offset)
	{
		$gresult = array_slice($groups,$offset,$rows);	
		$rows = $rows - count($gresult);		
		$line = current($gresult);
		while($line )
		{
			$r = array();
			$r['id'] = $line['guid'];
			$cell = array();
			
			$cell[] = $line['name'];
			$cell[] = '工作组';
			$cell[] = '';
			$r['cell'] = $cell;
			$data['rows'][] = $r;			
			$line = next($gresult);			
		}
		$offset = 0;
	}
	else
	{
		$offset = $offset - $groupCount;
	}
	
	if($rows>0)
	{		
		$uresult = array_slice($users,$offset,$rows);	
		$rows = $rows - count($uresult);		
		$line = current($uresult);		
		while($line)
		{
			$r = array();
			$r['id'] = $line['guid'];
			$cell = array();
			
			$cell[] = $line['username']."(".$line['name'].")";
			$cell[] = '用户';
			$cell[] = $line['status'] == 'ACTIVE'?'激活':'不可用';
			$r['cell'] = $cell;
			$data['rows'][] = $r;
			$line = next($uresult);			
		}		
	}	
	
}
elseif($data_type == 'selected')
{
	$selectedGroups = array();
	$selectedUsers = array();
	if($role_type == 'PRO')
	{
		$selectedGroups = $pmws->getProcessAsGroups($guid);
		$selectedUsers = $pmws->getProcessAsUsers($guid);
	}
	elseif($role_type == 'TAS')
	{
		$selectedGroups = $pmws->getTaskAsGroups($guid);
		$selectedUsers = $pmws->getTaskAsUsers($guid);		
	}
	
	if(!$selectedGroups) $selectedGroups = array();
	if(!$selectedUsers) $selectedUsers = array();
	
	$userCount = count($selectedUsers) ;
	$groupCount = count($selectedGroups);	
	
	$records = $userCount + $groupCount;
	$total = ceil($records/$rows);
	$data['total'] = $total;
	$data['records'] = 	$records;
	$offset = $rows * ($page-1);

	
	if($groupCount > $offset)
	{
		$gresult = array_slice($selectedGroups,$offset,$rows);	
		$rows = $rows - count($gresult);		
		$line = current($gresult);		
		while($line)
		{
			$r = array();
			$r['id'] = $line['grp_uid'];
			$cell = array();
			
			$cell[] = $line['grp_name'];
			$cell[] = '工作组';
			$cell[] = '';
			$r['cell'] = $cell;
			$data['rows'][] = $r;
			$line = next($gresult);			
		}
		$offset = 0;
	}
	else
	{
		$offset = $offset - $groupCount;
	}
	
	if($rows>0)
	{
		$uresult = array_slice($selectedUsers,$offset,$rows);	
		$rows = $rows - count($uresult);		
		$line = current($uresult);		
		while($line)
		{
			
			$r = array();
			$r['id'] = $line['usr_uid'];
			$cell = array();
			
			$cell[] = $line['usr_username']."(".$line['usr_name'].")";
			$cell[] = '用户';
			$cell[] = $line['status'] == 'ACTIVE'?'激活':'不可用';
			$r['cell'] = $cell;
			$data['rows'][] = $r;
			$line = next($uresult);			
		}		
	}	
	
	
}


die(json_encode($data));
?>