<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','你的权限不能进行该项操作！');
	die();
}

if(empty($_GET['module_name']) || empty($_GET['field_list']) || empty($_GET['options_text']) )
{
	return_ajax('error','参数错误！');
	die();	
}
$modname = $_GET['module_name'];
$field = $_GET['field_list'];
$options_text = $_GET['options_text'];
$lines = explode("\r\n",$options_text);
$group = '';
$APP_ADODB->Execute("delete from dropdown where module_name='{$modname}' and field = '{$field}';");
foreach($lines as $line)
{
	if(substr($line,0,5) == '-----')
	{
		if(preg_match('/^\-{5,}([^\-]+)/',$line,$out))
		{
			$group = $out[1];			
		}
	}	
	else
	{
		if(preg_match('/^\s*([^\s]+)\s*([^\s]*)/',$line,$out))
		{
			$save_value = $out[1];
			if(empty($out[2]))
			{
				$show_value = $out[1];
			}
			else
			{
				$show_value = $out[2];
			}			
			$sql  = "insert into dropdown(module_name,field,group_name,save_value,show_value) ";
			$sql .= " values('{$modname}','{$field}','{$group}','{$save_value}','{$show_value}');";
			$APP_ADODB->Execute($sql);				
		}		
	}		
}
return_ajax('success','保存成功！');
?>



