<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$show_module_label = false;
$toolsbar = createToolsbar(Array('calendar','calculator','email','sms'));
$oper = '';
if(isset($_GET['operation'])) $oper = $_GET['operation'];

if($oper == "popup" && !empty($_GET['numberid']))
{
	$sql = "select accountid,number from  zswitch_ps_autodial_number where id={$_GET['numberid']};";
	$result = $APP_ADODB->Execute($sql);
	$number = '';
	$accountid = -1;
	if(!$result->EOF)
	{
		$number = $result->fields['number'];
		$accountid = $result->fields['accountid'];
	}

	if($accountid > -1)
	{

		$recordid = $accountid;
		require_once("detailView.php");
	}
	else
	{
		$sql =  "select id from accounts where telphone='{$number}' limit 1;";
		$result = $APP_ADODB->Execute($sql);
		if(!$result->EOF)
		{
			$recordid = $result->fields['id'];
			require_once("detailView.php");
		}
		else
		{
			$_POST['telphone'] = $number;
			$operation = 'create';
			$return_action = 'detailView';
			require_once(_ROOT_DIR."/common/editView.php");
		}
	}	
}
else
{

	$smarty = new ZS_Smarty();
	$module = _MODULE;
	$action = _ACTION;
	$module_label =  getTranslatedString($module);
	$action_label =  getTranslatedString($action);
	
	$smarty->assign('TOOLSBAR',$toolsbar);
	$smarty->assign('MODULE',$module);
	$smarty->assign('ACTION',$action);
	$smarty->assign('MODULE_LABEL',$module_label);
	$smarty->assign('ACTION_LABEL',$action_label);
	$smarty->assign('TITLEBAR_SHOW_MODULE_LABEL',$show_module_label);
	if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
	{
		$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
		$smarty->display('ErrorMessage.tpl');
		die();
	}
	global $mod;
	if(!isset($mod) && is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
	{
		require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
		$modclass= "{$module}Module";
		$mod = new $modclass();	
	}
	
	$smarty->display('PhoneSalesGJS/index.tpl');
}


?>