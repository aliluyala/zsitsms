<?php
global $APP_ADODB;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();	
}

$sms_calleeid_field = createFieldUI('calleeid',$mod->fields['calleeid'],null,null,Array());
$sms_content_field = createFieldUI('content',$mod->fields['content'],null,null,Array());
$self_define_content = createFieldUI('self_define_content',$mod->fields['self_define_content'],null,null,Array());

$smarty->assign('SELF_DEFINE_CONTENT',$self_define_content);
$smarty->assign('SMS_CALLEEID_FIELD',$sms_calleeid_field);
$smarty->assign('SMS_CONTENT_FIELD',$sms_content_field);
$smarty->display('SMSNotify/SendSMSView.tpl');
?>