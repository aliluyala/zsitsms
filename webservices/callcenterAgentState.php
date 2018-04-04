<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/..');
require(_ROOT_DIR.'/common/ajax.php');
if(empty($_GET['agent']))
{
	return_ajax('faluire','');
	die();
}	

$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db,$con);

$state = '';
$status = '';
$sql = "select state,status from zswitch_cc_agent_state where name='{$_GET['agent']}' limit 1;";
$result = mysql_query($sql,$con);
if($result && mysql_num_rows($result)>0)
{
	$row = mysql_fetch_object($result);
	$state = $row->state;
	$status = $row->status;
}
if(empty($state))
{
	return_ajax(1,'faluire');
	die();	
}
return_ajax(0,Array('state'=>$state,'status'=>$status));
?>