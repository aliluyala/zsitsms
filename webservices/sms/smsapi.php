<?php
//发送一条模块短信
function sendTplSms($tplid,$otherNumber,$agent,$queue,$call_time,$answer_time,$hangup_time)
{
	$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
	$SS_CONFIG = require(_ROOT_DIR.'/config/zswitch.conf.php');
	$SMS_TPL = require(_ROOT_DIR.'/config/sms_tpl.conf.php');
	$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
	$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
	$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
	$db = $APP_CONFIG['DBServers']['master']['Database'];
	if(empty($SMS_TPL[$tplid])) return;
	$con = mysql_connect($dbs,$user,$pw);
	if(!$con) return;
	if(!mysql_select_db($db,$con)) return;
	$sql = "select * from zswitch_cc_agent_state where name='{$agent}';";
	$result = mysql_query($sql,$con);
	$workno = '';
	$userid = -1;
	if($result && mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_object($result);
		$workno = $row->workno;
		$userid = $row->userid;
	}
	$user_name = '';

	$sql = "select * from users where id={$userid};";
	$result = mysql_query($sql,$con);
	if($result && mysql_num_rows($result)>0)
	{	
		$row = mysql_fetch_object($result);
		$user_name = $row->name;
	}	
	$sms_content = eval('return "'.$SMS_TPL[$tplid].'";');
	$sms_content = str_replace("'","\\'",$sms_content);
	
	$id = -1;
	$result =  mysql_query("LOCK TABLES sms_notify_seq  WRITE;",$con);
	if(!$result) return ;
	$result =   mysql_query("select id from  sms_notify_seq limit 1;",$con);
	if(!$result)
	{
		mysql_query("UNLOCK TABLES;",$con);
		return ;
	}
	$row = mysql_fetch_object($result);
	$id = $row->id;
	mysql_query("update sms_notify_seq set id=id+1;");
	mysql_query("UNLOCK TABLES;",$con);		
	
	$sql = "insert into sms_notify(id,userid,dir,callerid,calleeid,send_time,content,state) ";
	$sql .= "values({$id},{$userid},'send','{$agent}','{$otherNumber}',now(),'{$sms_content}','wait');";
	mysql_query($sql,$con);
}	
?>