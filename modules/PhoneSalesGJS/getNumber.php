<?php 
global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_IS_ADMIN,$CURRENT_USER_GROUPID;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax(10,'not permission!');
	die();
}

$oper = $_GET['operation'];

if($oper == 'start')
{
	$agent = '';
	$result = $APP_ADODB->Execute("select * from zswitch_cc_agent_state where userid = {$CURRENT_USER_ID}  limit 1;");
	if($result && !$result->EOF)
	{	
		$agent = $result->fields['name'];
	}	
	
	if(!empty($agent))
	{
		$sql = "insert into zswitch_ps_autodial_job(userid,groupid,agent,state) values({$CURRENT_USER_ID},{$CURRENT_USER_GROUPID},'{$agent}','calling');";
		$APP_ADODB->Execute($sql);
		require_once(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
		$com = new ZSwitchComm();
		if($com->autodialPS($CURRENT_USER_ID,$CURRENT_USER_GROUPID,$agent))
		{			
			return_ajax(0,'success');
		}
		else
		{
			return_ajax(3,'failure');
		}
		die();
	}
	
	return_ajax(1,'fairult!');
	die();
}
elseif($oper == 'status')
{
	$result = $APP_ADODB->Execute("select * from zswitch_ps_autodial_job where userid = {$CURRENT_USER_ID}  limit 1;");
	if($result && !$result->EOF)
	{
		$datas = Array();
		$datas['state'] = $result->fields['state'];
		$datas['number'] = $result->fields['number'];
		$datas['numberid'] = $result->fields['numberid'];
		return_ajax(0,$datas);
		die();
	}
	return_ajax(1,'fairult!');
	die();
}	
elseif($oper == 'stop')
{
	$sql = "delete from zswitch_ps_autodial_job where userid={$CURRENT_USER_ID};";
	$APP_ADODB->Execute($sql);
	return_ajax(0,$datas);
	die();
}
?>