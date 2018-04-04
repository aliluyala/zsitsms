<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
$toolsbar = createToolsbar(Array());

$smarty->assign('TOOLSBAR',$toolsbar);
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);
$smarty->assign('TITLEBAR_SHOW_MODULE_LABEL',false);

if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}

require(_ROOT_DIR.'/include/workflow/PMApi.class.php');
$pmconf = require(_ROOT_DIR.'/config/workflow.conf.php');
$pmws = new PMApi($pmconf);

$webhost = $pmconf['host'].$pmconf['port'];
$cip = $_SERVER['REMOTE_ADDR'];

foreach($pmconf['ext_ip_map'] as $pfx => $whost)
{
	$pfxleng = strpos($pfx,'*');

	if(substr($pfx,0,$pfxleng) == substr($cip,0,$pfxleng))
	{
		$webhost = $whost;
		break;
	}
}


$smarty->assign('PM_HOST',$webhost);
$smarty->assign('PM_WORKSPACE',$pmconf['workspace']);
$smarty->assign('PM_LANG',$pmconf['lang']);
$smarty->assign('PM_SKIN',$pmconf['skin']);

$pmsessionid =  false;
if($CURRENT_USER_ID == 1)
{
	$pmsessionid = $pmws->login(null,null,true);
}
else
{
	$result = $APP_ADODB->Execute("select user_password from users where id={$CURRENT_USER_ID};");
	if(!$result->EOF)
	{
		$pwd = $result->fields['user_password'];
		$pmsessionid = $pmws->login($CURRENT_USER_NAME,'md5:'.$pwd,false);		
	}
}
if($pmsessionid)
{
	$smarty->assign('PM_SESSIONID',$pmsessionid);
	$smarty->display('MyWorkFlow/index.tpl');
}
else
{
	$smarty->assign('ERROR_MESSAGE','工作流引擎故障！');
	$smarty->display('ErrorMessage.tpl');
}

?>