<?php
header('Content-Type: text/html; charset=utf-8');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/../../..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SMS_CONFIG = require(_ROOT_DIR.'/config/shcmkjnew_sms.conf.php');

$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db);
mysql_query('set names GBK;');
$apiKey = $SMS_CONFIG['apiKey'];
$apiSecret = $SMS_CONFIG['apiSecret'];
$sign = $SMS_CONFIG['sign'];

require(_ROOT_DIR.'/cron/smsapi/shcmkjnew/SendSMS.php');
$sms = new SendSMS($apiKey,$apiSecret,$sign);
$reset = mysql_query("select id,calleeid,content from sms_notify where state='wait';");
while($row = mysql_fetch_row($reset))
{
	$url_full ='';
	$mobile = $row[1];
	$content = $sign . iconv('GBK','UTF-8',$row[2]) . ' 回T退订';
	$result = $sms->send($mobile, $content);
	$result = json_decode($result,true);
	if($result['success'] == true)
	{
		$sql = "update sms_notify set state='success' where id={$row[0]};";
		mysql_query($sql);
	}
	else
	{
		$sql = "update sms_notify set state='failure' , errmsg='{$result['r']}' where id={$row[0]};";
		mysql_query($sql);
	}
}
?>