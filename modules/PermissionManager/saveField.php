<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
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

if(!empty($_SESSION[$sess_pfx]))
{
	foreach($_SESSION[$sess_pfx] as $field_name=>$info)
	{
		if(isset($_POST["field_show_{$field_name}"]) && $_POST["field_show_{$field_name}"] == 'YES')
		{
			$_SESSION[$sess_pfx][$field_name]['is_show'] = 'YES';
			if(isset($_POST["field_modify_{$field_name}"]) && $_POST["field_modify_{$field_name}"] == 'YES')
			{
				$_SESSION[$sess_pfx][$field_name]['is_modify'] = 'YES';
			}
			else
			{
				$_SESSION[$sess_pfx][$field_name]['is_modify'] = 'NO';
			}
			if(isset($_POST["field_hidden_{$field_name}"]) && $_POST["field_hidden_{$field_name}"] == 'YES')
			{
				$_SESSION[$sess_pfx][$field_name]['hidden_start'] = $_POST["field_hidden_start_{$field_name}"];
				$_SESSION[$sess_pfx][$field_name]['hidden_end'] = $_POST["field_hidden_end_{$field_name}"];
			}
			else
			{
				$_SESSION[$sess_pfx][$field_name]['hidden_start'] = 0;
				$_SESSION[$sess_pfx][$field_name]['hidden_end'] = 0;
			}
		}
		else
		{
			$_SESSION[$sess_pfx][$field_name]['is_show'] = 'NO';
			$_SESSION[$sess_pfx][$field_name]['is_modify'] = 'NO';
			$_SESSION[$sess_pfx][$field_name]['hidden_start'] = -1;
			$_SESSION[$sess_pfx][$field_name]['hidden_end'] = -1;
		}
	}
}
?>