<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$mod_name = $_GET['pmod'];
$pid = $_GET['pid'];
$new_pid = $_GET['new_pid'];
if(empty($new_pid))
{
	$session_pfx = "{$CURRENT_USER_ID}_{$mod_name}_{$pid}_";
}
else
{
	$session_pfx = "{$CURRENT_USER_ID}_{$mod_name}_{$new_pid}_";
}

if($_POST['groups_range'] != 'select')
{
	$_SESSION["{$session_pfx}groups_permission"] = $_POST['groups_range'];
}
else
{
	$_SESSION["{$session_pfx}groups_permission"] = '-9999';
	if(!empty($_POST['share_group']))
	{
		foreach($_POST['share_group'] as $groupid)
		{
			if($_SESSION["{$session_pfx}groups_permission"] == '-9999') 
			{
				$_SESSION["{$session_pfx}groups_permission"] = $groupid;
			}	
			else
			{
				$_SESSION["{$session_pfx}groups_permission"] .= ','.$groupid;
			}
		}
	}
}
	
if($_POST['users_range'] != 'select')
{
	$_SESSION["{$session_pfx}users_permission"] = $_POST['users_range'];
}
else
{
	$_SESSION["{$session_pfx}users_permission"] = '-9999';
	if(!empty($_POST['share_user']))
	{
		foreach($_POST['share_user'] as $userid)
		{
			if($_SESSION["{$session_pfx}users_permission"] == '-9999') 
			{
				$_SESSION["{$session_pfx}users_permission"] = $userid;
			}	
			else
			{
				$_SESSION["{$session_pfx}users_permission"] .= ','.$userid;
			}
		}
	}
}	

//echo $_SESSION["{$session_pfx}groups_permission"];
//echo ',';
//echo $_SESSION["{$session_pfx}users_permission"];
?>