<?php
if(!isset($_GET['uuid'])||
   !isset($_GET['callerid'])||
   !isset($_GET['calleeid'])||
   !isset($_GET['state'])||
   !isset($_GET['agent'])||
   !isset($_GET['queue'])||
   !isset($_GET['dir']))
   die();


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

$envsql ="insert into zswitch_call_event(userid,uuid,callerid,calleeid,event_time, event_type,agent,queue)";
$envsql .= " values(%d,'%s','%s','%s',now(),'%s','%s','%s');";

$arr = array();	
$arr[0]= -1;
$arr[1]= $_GET['uuid'];
$arr[2]= $_GET['callerid'];
$arr[3]= $_GET['calleeid'];
$arr[4]= $_GET['state'];
$arr[5]= $_GET['agent'];
$arr[6]= $_GET['queue'];
$dir = $_GET['dir'];

$filter = false;
if(!$filter && !empty($SS_CONFIG['call_event_filter']['dir']) &&
	preg_match($SS_CONFIG['call_event_filter']['dir'],$dir)>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['call_event_filter']['callerid']) &&
	preg_match($SS_CONFIG['call_event_filter']['callerid'],$arr[2])>0)
{
	$filter = true;
}

if(!$filter && !empty($SS_CONFIG['call_event_filter']['calleeid']) &&
	preg_match($SS_CONFIG['call_event_filter']['calleeid'],$arr[3])>0)
{
	$filter = true;
}

if(!$filter && !empty($SS_CONFIG['call_event_filter']['state']) &&
	preg_match($SS_CONFIG['call_event_filter']['state'],$arr[4])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['call_event_filter']['queue']) &&
	preg_match($SS_CONFIG['call_event_filter']['queue'],$arr[6])>0)
{
	$filter = true;
}
if(!$filter && !empty($SS_CONFIG['call_event_filter']['agent']) &&
	preg_match($SS_CONFIG['call_event_filter']['agent'],$arr[5])>0)
{
	$filter = true;
}


if(!$filter)
{
	if($dir == 'outbound')
	{
		if($arr[4] == 'ringing')
		{
			$arr[4] = 'callin_ringing';
		}
		$result = mysql_query("select id from users where agent_number = '{$arr[3]}';",$con);
	}
	else
	{
		if($arr[4] == 'ringing')
		{
			$arr[4] = 'callout_ringing';
		}	
		
		$result = mysql_query("select id from users where agent_number = '{$arr[2]}';",$con);
	}
	
	if($result && mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_object($result);
		$arr[0] = $row->id;
		$sql = vsprintf($envsql,$arr);
		mysql_query($sql,$con);
	}

}	
$valid_time = time() - 3600;
mysql_query('delete from zswitch_call_event where event_time<={$valid_time};',$con);
mysql_close($con);
?> 