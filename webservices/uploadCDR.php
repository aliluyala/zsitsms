<?php
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SS_CONFIG = require(_ROOT_DIR.'/config/zswitch.conf.php');
$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db,$con);

$cdrsql ="insert into zswitch_call_details(";
$cdrsql .= "userid,direction,caller_id_number";
$cdrsql .= ",callee_id_number,destination_number,uuid,source,context,channel_name";
$cdrsql .= ",channel_created_datetime,channel_answered_datetime,channel_hangup_datetime";
$cdrsql .= ",bleg_uuid,hangup_cause) ";              
$cdrsql .= "values(%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');";
try  
{
	$xml_cdr = simplexml_load_string($_POST['cdr']);
} 
catch(Exception $e) 
{
	header("HTTP/1.1 400 bad request ");
	return;
}
$arr = array();	
$arr[0]= -1;
$arr[1]= (string)$xml_cdr->channel_data->direction;
$arr[2]= (string)$xml_cdr->callflow[0]->caller_profile->caller_id_number;
$arr[3]= (string)$xml_cdr->callflow[0]->caller_profile->callee_id_number;
$arr[4]= (string)$xml_cdr->callflow[0]->caller_profile->destination_number;
$arr[5]= (string)$xml_cdr->callflow[0]->caller_profile->uuid;
$arr[6]= (string)$xml_cdr->callflow[0]->caller_profile->source;
$arr[7]= (string)$xml_cdr->callflow[0]->caller_profile->context;
$arr[8]= (string)$xml_cdr->callflow[0]->caller_profile->chan_name;
$arr[9]= urldecode((string)$xml_cdr->variables->start_stamp);
$arr[10]= urldecode((string)$xml_cdr->variables->answer_stamp);
$arr[11]= urldecode((string)$xml_cdr->variables->end_stamp);
$arr[12]= (string)$xml_cdr->variables->bridge_uuid;
$arr[13]= (string)$xml_cdr->variables->hangup_cause;

$filter = false;
if(!$filter && !empty($SS_CONFIG['CDR_filter']['direction']) &&
	preg_match($SS_CONFIG['CDR_filter']['direction'],$arr[1])>0)
{
	$filter = true;
}

if(!$filter && !empty($SS_CONFIG['CDR_filter']['caller_id_number']) &&
	preg_match($SS_CONFIG['CDR_filter']['caller_id_number'],$arr[2])>0)
{
	$filter = true;
}

if(!$filter && !empty($SS_CONFIG['CDR_filter']['callee_id_number']) &&
	preg_match($SS_CONFIG['CDR_filter']['callee_id_number'],$arr[3])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['CDR_filter']['destination_number']) &&
	preg_match($SS_CONFIG['CDR_filter']['destination_number'],$arr[4])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['CDR_filter']['source']) &&
	preg_match($SS_CONFIG['CDR_filter']['source'],$arr[6])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['CDR_filter']['context']) &&
	preg_match($SS_CONFIG['CDR_filter']['context'],$arr[7])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['CDR_filter']['channel_name']) &&
	preg_match($SS_CONFIG['CDR_filter']['channel_name'],$arr[8])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['CDR_filter']['channel_created_datetime']) &&
	preg_match($SS_CONFIG['CDR_filter']['channel_created_datetime'],$arr[9])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['CDR_filter']['channel_answered_datetime']) &&
	preg_match($SS_CONFIG['CDR_filter']['channel_answered_datetime'],$arr[10])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['CDR_filter']['channel_hangup_datetime']) &&
	preg_match($SS_CONFIG['CDR_filter']['channel_hangup_datetime'],$arr[11])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['CDR_filter']['hangup_cause']) &&
	preg_match($SS_CONFIG['CDR_filter']['hangup_cause'],$arr[13])>0)
{
	$filter = true;
}
if(!$filter)
{
	$result = mysql_query("select id from users where agent_number = '{$arr[3]}' or agent_number = '{$arr[4]}' limit 1;",$con);
	if($result && mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_object($result);
		$arr[0] = $row->id;
	}
	$sql = vsprintf($cdrsql,$arr);
	//file_put_contents('/tmp/cdr.sql',$sql);
	mysql_query($sql,$con);
}	
mysql_close($con);
?> 