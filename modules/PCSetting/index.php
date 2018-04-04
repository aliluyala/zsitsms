<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
global $toolsbar;
if(!isset($toolsbar)) $toolsbar = createToolsbar(array('import','export'));

$smarty->assign('TOOLSBAR',$toolsbar);
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);
global $show_module_label;
if(!isset($show_module_label)) $show_module_label = true;
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

global $qidianSdk;
if(is_file(_ROOT_DIR."/webservices/qiDianapi.class.php"))
{
	require_once(_ROOT_DIR."/webservices/qiDianapi.class.php");
	$qidianmod= "qiDianapi";
	$qidianSdk = new $qidianmod();
}
else
{
	$smarty->assign('ERROR_MESSAGE','系统发生错误');
	$smarty->display('ErrorMessage1.tpl');
	die();
}

$apis = $qidianSdk->getCliectSdk('Insurance',array(),'GET');

if(!$apis)
{
	$errmessage = $qidianSdk->getErrorMessage();
	$smarty->assign('ERROR_MESSAGE',$errmessage);
	$smarty->display('ErrorMessage1.tpl');
	die();
}

if($apis['code'] == 0 && $apis['describe'] == 'success')
{
	$parmas = array();
	$apisetting = array();
	foreach($apis['data'] as $key => $val)
	{
		$parmas['insurance'] = $val['code'];
		$apisetting[] = $qidianSdk->getCliectSdk('Insurance.detail',$parmas,'GET');
		if(!$apisetting)
		{
			$errmessage = $qidianSdk->getErrorMessage();
			$smarty->assign('ERROR_MESSAGE',$errmessage);
			$smarty->display('ErrorMessage1.tpl');
			die();
		}
	}
}

$allow_apis = array();
$allow_insurances = array("TVDI","TTBLI","TWCDMVI","TCPLI_DRIVER","TCPLI_PASSENGER","BSDI","SLOI",
				          "BGAI","NIELI","VWTLI","STSFS","CUSTOM1","CUSTOM2",'MVTALCI','TTBLI_DOUBLE');
$default_api = '';
$sms_tpl = '';
$state="select * from policy_calculate_setting";
$state_result = $APP_ADODB->Execute($state);
$set = json_decode($state_result->fields['setting'],true);
if(!empty($set))
{
	if(array_key_exists('INSURANCES',$set))
	{
		$allow_insurances = $set['INSURANCES'];
	}
	if(array_key_exists('sms_tpl',$set))
	{
		$sms_tpl = urldecode($set['sms_tpl']);
	}
}
$short_sms_before = '';
$short_sms_after = ''; 
if(array_key_exists('short_sms_before',$set))
{
        $short_sms_before = urldecode($set['short_sms_before']);
}
if(array_key_exists('short_sms_after',$set))
{
        $short_sms_after = urldecode($set['short_sms_after']);
}

$smarty->assign('SHORT_SMS_BEFORE',$short_sms_before);
$smarty->assign('SHORT_SMS_AFTER',$short_sms_after);

$smarty->assign('CURRENT_USER_ID',$CURRENT_USER_GROUPID);
$smarty->assign('APIS',$apis);//接口列表
$smarty->assign('API_SETITEMS',$apisetting);
$smarty->assign('ALLOW_INSURANCES',json_encode($allow_insurances));
$smarty->assign('ALLOW_APIS',json_encode($allow_apis));//设置默认接口
$smarty->assign('DEFAULT_API',$default_api);
$smarty->assign('SMS_TPL',$sms_tpl);
$smarty->assign('HAVE_QUERY_WHERE', false);
$smarty->display('PCSetting/index.tpl');
?>
