<?php
global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax(10,'not permission!');
	die();
}

if(empty($_GET['chideNumber'])||empty($_GET['cmodule'])||empty($_GET['cfield'])||empty($_GET['crecordid']))
{
	return_ajax(3,'failure');
	die();
}

$result = $APP_ADODB->Execute("select * from zswitch_cc_agent_state where userid = {$CURRENT_USER_ID}  limit 1;");
if($result && !$result->EOF)
{
	if($result->fields['state'] != 'Waiting')
	{
		return_ajax(2,'agent busy£¡');
		die();
	}
	require_once(_ROOT_DIR."/modules/{$_GET['cmodule']}/{$_GET['cmodule']}Module.class.php");
	$modclass = "{$_GET['cmodule']}Module";
	$mod = new $modclass();
	$reset = $mod->getOneRecordset($_GET['crecordid'],'','');
	$number = '';
	if(!empty($reset) && !empty($reset[0]) && !empty($reset[0][$_GET['cfield']]))
	{
		$number = $reset[0][$_GET['cfield']];
	}
	
	if(empty($number))
	{
		return_ajax(1,'number empty!');
		die();		
	}
	
	require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
	$chideNumber = $number;
	if(isset($_GET['chideNumber']))
	{
		$chideNumber = $_GET['chideNumber'];
	}
	$com = new ZSwitchComm();
	if($com->agentCalloutA($result->fields['name'],$number,$_GET['chideNumber']))
	{
		return_ajax(0,'success');
	}
	else
	{
		return_ajax(3,'failure');
	}

	die();
}

return_ajax(3,'agnet not login£¡');
?>