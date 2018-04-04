<?php
global $APP_ADODB,$CURRENT_USER_NAME,$APP_CONFIG,$CURRENT_USER_ID;

require(_ROOT_DIR.'/common/menu.php');
$smarty = new ZS_Smarty();
$menus = createMenus();
$smarty->assign('HAVE_AGENT',false);
if($APP_CONFIG['have_agent'])
{
	$result = $APP_ADODB->Execute("select agent_have from users where id={$CURRENT_USER_ID};");
	if($result && !$result->EOF)
	{
		if($result->fields['agent_have'] == 'YES')
		{
			$smarty->assign('HAVE_AGENT',true);
		}	
	}	
}
$check_activity=false;
$activity_check_time = 10 ;
if($APP_CONFIG['multiple_login']!='allow')
{
	$check_activity = true;
	$activity_check_time = $APP_CONFIG['activity_check_time'];
}
$smarty->assign('CHECK_ACTIVITY',$check_activity);
$smarty->assign('ACTIVITY_CHECK_TIME',$activity_check_time);	
$smarty->assign('VERSION',_APP_VERSION);
$smarty->assign('MENUS',$menus);
$smarty->assign('USER',$CURRENT_USER_NAME);
$smarty->assign('DEFAULT_JOBVIEW_URL',"index.php?module={$APP_CONFIG['def_module']}&action={$APP_CONFIG['def_action']}");
if($CLOUD_TYPE)
{
	$smarty->assign('CLOUD_USER_NAME',$CLOUDMGR->name());
	$smarty->display('Index/index_cloud.tpl');
}
else
{
	$smarty->display('Index/index.tpl');
}

?>