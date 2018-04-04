<?php
header('Content-Type: text/html; charset=utf-8');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/../../..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SMS_CONFIG = require(_ROOT_DIR.'/config/ytd_sms.conf.php');

$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db);
mysql_query('set names utf8;');

$smsuid = $SMS_CONFIG['uid'];
$smsaccount= $SMS_CONFIG['account'];
$smspwd = $SMS_CONFIG['pwd'];
$smssign = $SMS_CONFIG['sign'];
$url = "http://120.76.213.253:8888/SmsWebService.asmx?wsdl";

$soap = new SoapClient($url);

$reset = mysql_query("select id,calleeid,content from sms_notify where state='wait';");
while($row = mysql_fetch_row($reset))
{
	$mobile = $row[1];
	$content = $row[2]; // urlencode($row[2].$sign);//
	
	$result = $soap->SendSms(
		array(
			'userid' => $smsuid ,
			'account' => $smsaccount,
			'password' => $smspwd,
			'mobile' => $mobile,
			'content' => $content . '【' . $smssign . '】',
			'sendTime' => '',
			'extno' => ''
	    )
	 ); 

//print_R($result);//exit;	
	if($result->SendSmsResult->ReturnStatus =='Success')
	{
		$sql = "update sms_notify set state='success' where id={$row[0]};";
		mysql_query($sql);
	}
	else
	{
		$sql = "update sms_notify set state='failure' , errmsg='".$result->SendSmsResult->Message."' where id={$row[0]};";
		mysql_query($sql);
	}
}
?>