<?php
header('Content-Type: text/html; charset=utf-8');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/../../..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SMS_CONFIG = require(_ROOT_DIR.'/config/cdlk_sms.conf.php');

$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db,$con);
mysql_query('set names utf8;');

$smsuid = urlencode($SMS_CONFIG['uid']);
$smspwd = urlencode($SMS_CONFIG['pwd']);
$sign = $SMS_CONFIG['sign'];
$url = "http://mb345.com:999/ws/batchSend.aspx?CorpID={$smsuid}&Pwd={$smspwd}&Cell=&SendTime=";

$reset = mysql_query("select id,calleeid,content from sms_notify where state='wait';");
while($row = mysql_fetch_row($reset))
{
	$mobile = urlencode($row[1]);
	
	$content = urlencode(mb_convert_encoding($row[2].$sign,'GB2312','UTF-8'));
	$params = "&Mobile={$mobile}&Content={$content}";
	
	$result = file_get_contents($url.$params);
	
	if(  $result == 0 || $result == 1)
	{
		$sql = "update sms_notify set state='success' where id={$row[0]};";
		mysql_query($sql);
	}
	else
	{
		
		$sql = "update sms_notify set state='failure' , errmsg='{$result}' where id={$row[0]};";
		mysql_query($sql);
	}
	
}

?>