<?php
/**
 * 短信接口服务程序
 * 版权所有：      2014 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *
 * 中国联通一信通接口(ums.zj165.com)
 **/



header('Content-Type: text/html; charset=utf-8');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/../../..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SMS_CONFIG = require(_ROOT_DIR.'/config/ums86_sms.conf.php');

$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db,$con);
//mysql_query('set names gb2312;');

$smsuid = urlencode($SMS_CONFIG['uid']);
$smspwd = urlencode($SMS_CONFIG['pwd']);
$smsspcode = $SMS_CONFIG['spcode'];
$sign = $SMS_CONFIG['sign'];

$url = "http://js.ums86.com:8899/sms/Api/Send.do?SpCode={$smsspcode}&LoginName={$smsuid}&Password={$smspwd}&SerialNumber=&ScheduleTime=&f=1";

$reset = mysql_query("select id,calleeid,content from sms_notify where state='wait';");
while($row = mysql_fetch_row($reset))
{
	$mobile = urlencode($row[1]);
	
	$content = urlencode(mb_convert_encoding($row[2],'GB2312','UTF-8'));
	$params = "&UserNumber={$mobile}&MessageContent={$content}";
	$result = file_get_contents($url.$params);
	$result = mb_convert_encoding($result,'UTF-8','GB2312');
	if( preg_match('/result=(\d+)&/',$result,$out) && $out[1] == '0' )
	{
		$sql = "update sms_notify set state='success' where id={$row[0]};";
		mysql_query($sql);
	}
	else
	{	
		$errmsg = '';
		if(preg_match('/description=(.+?)&/',$result,$out))
		{
			$errmsg = $out[1];
		}
		$sql = "update sms_notify set state='failure' , errmsg='{$errmsg}' where id={$row[0]};";
		mysql_query($sql);
	}
	
}

?>