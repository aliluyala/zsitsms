<?php
header('Content-Type: text/html; charset=utf-8');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/../../..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SMS_CONFIG = require(_ROOT_DIR.'/config/zyctd_sms.conf.php');

$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db,$con);
//mysql_query('set names utf8;');

$url = $SMS_CONFIG['url'];
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL ,$url);
curl_setopt($ch,CURLOPT_HEADER ,0); 
curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE); 
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_TIMEOUT,5);

$reset = mysql_query("select id,calleeid,content from sms_notify where state='wait';");
while($row = mysql_fetch_row($reset))
{
	$params = "do=sendsm&mobiles={$row[1]}&content={$row[2]}&smkey={$SMS_CONFIG['smkey']}";
	curl_setopt($ch,CURLOPT_POSTFIELDS, $params); 
	$result = curl_exec($ch);
	if($result  == "1")
	{
		$sql = "update sms_notify set state='success' where id={$row[0]};";
		mysql_query($sql);
	}
	else
	{
		//print_r($ret);
		$sql = "update sms_notify set state='failure' , errmsg='{$result}' where id={$row[0]};";
		mysql_query($sql);
	}
}
curl_close($ch);
?>