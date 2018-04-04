<?php
if(empty($_GET['tplid']) || empty($_GET['otherNumber'])) die('1|param no find!');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/..');
require(_ROOT_DIR.'/webservices/sms/smsapi.php');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SS_CONFIG = require(_ROOT_DIR.'/config/zswitch.conf.php');
$acl = $SS_CONFIG['webservicesACL'];
$clientIP = $_SERVER['REMOTE_ADDR'];
if(empty($acl) || !in_array($clientIP,$acl))
{
	die("2|client's ip not in ACL!");
}

$tplid = $_GET['tplid'];
$otherNumber = $_GET['otherNumber'];
$agent = '';
if(isset($_GET['agent'])) $agent = $_GET['agent'];  
$queue = '';
if(isset($_GET['queue'])) $agent = $_GET['queue'];
$call_time = '';
if(isset($_GET['call_time'])) $call_time = $_GET['call_time'];
$answer_time = '';
if(isset($_GET['answer_time'])) $answer_time = $_GET['answer_time'];
$hangup_time = '';
if(isset($_GET['hangup_time'])) $hangup_time = $_GET['hangup_time'];

sendTplSms($tplid,$otherNumber,$agent,$queue,$call_time,$answer_time,$hangup_time);

die("0|success!");
?>