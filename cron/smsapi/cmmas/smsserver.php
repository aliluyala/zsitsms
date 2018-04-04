<?php
header('Content-Type: text/html; charset=utf-8');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/../..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$MAS_CONFIG = require(_ROOT_DIR.'/config/cmmas.conf.php');

$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db,$con);
//mysql_query('set names utf8;');

$result = mysql_query('select sid, passport from sms_mas_status ;');
$row = mysql_fetch_row($result);

$sid = $row[0];
$passport = $row[1];

$client = new SoapClient($MAS_CONFIG['wsdl']);	
if(!$client) die('创建soap对象失败');

$ret = $client->sendActive(Array('code'=>'sendActive','sid'=>$sid),$passport);
$sid++;
if($ret->respCode !=0)
{
	$ret = $client->auth(Array('code'=>'auth','sid'=>$sid),$MAS_CONFIG['spid'],$MAS_CONFIG['passwd']);
	$sid++;
	if($ret->respCode  == 0)
	{
		$passport = $ret->respMessage;
		mysql_query("update sms_mas_status set passport='{$passport}'");
	}
	else
	{
		mysql_query("update sms_mas_status set sid={$sid}");
		die('鉴权失败！');
	}
}

$result = mysql_query("select id,calleeid,content from sms_notify where state='wait';");
while($row = mysql_fetch_row($result))
{	
	if(preg_match('/^\d+$/',$row[1]))
	{
		$ret = 	$client->sendSms(Array('code'=>'sendSms','sid'=>$sid,'sourceCode'=>'utf-8'),$passport,$row[0], $MAS_CONFIG['srcid'],Array($row[1]),iconv('UTF-8','UTF-8',$row[2]),false);
		$sid++;
	
		if($ret->respCode  == 0)
		{
			$sql = "update sms_notify set state='success' where id={$row[0]};";
			mysql_query($sql);
		}
		else
		{
			//print_r($ret);
			$sql = "update sms_notify set state='failure' , errmsg='{$ret->respMessage}' where id={$row[0]};";
			mysql_query($sql);
		}
	}
	else
	{
		$sql = "update sms_notify set state='failure' , errmsg='无效号码' where id={$row[0]};";
		mysql_query($sql);		
	}
}

mysql_query("update sms_mas_status set sid={$sid}");
?>