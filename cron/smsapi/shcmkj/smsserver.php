<?php
header('Content-Type: text/html; charset=utf-8');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/../../..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SMS_CONFIG = require(_ROOT_DIR.'/config/shcmkj_sms.conf.php');

$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db);
mysql_query('set names GBK;');

$smsac = $SMS_CONFIG['ac'];
$smskey = $SMS_CONFIG['key'];
$smscgid = $SMS_CONFIG['cgid'];
$smscsid = $SMS_CONFIG['csid'];
$sign = $SMS_CONFIG['sign'];
$url = "http://smsapi.c123.cn/OpenPlatform/OpenApi?action=sendOnce&ac={$smsac}&authkey={$smskey}&cgid={$smscgid}&csid={$smscsid}";
$reset = mysql_query("select id,calleeid,content from sms_notify where state='wait';");
while($row = mysql_fetch_row($reset))
{
	$url_full ='';
	$mobile = $row[1];
	$content = urlencode(iconv('GBK','UTF-8',$row[2])); // urlencode($row[2].$sign);//
	$url_full = $url.'&c='.$content.'&m='.$mobile; 
	$result = file_get_contents($url_full);
	$res = @simplexml_load_string($result,NULL,LIBXML_NOCDATA);
	$res = json_decode(json_encode($res),true);
	$status = $res['@attributes']['result'];
	if($status ==1)
	{
		$sql = "update sms_notify set state='success' where id={$row[0]};";
		mysql_query($sql);
	}
	else
	{
		$sql = "update sms_notify set state='failure' , errmsg='{$status}' where id={$row[0]};";
		mysql_query($sql);
	}
}
?>