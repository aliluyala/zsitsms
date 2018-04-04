<?php
global $APP_ADODB;	
$id = session_get('logined_userid');
$user_name = session_get('logined_username'); 
session_destroy();
$sqlstr = 'insert into user_login_log(userid,user_name,state,oper_time,ip_address,user_agent) ';
$sqlstr .= "values({$id},'{$user_name}','LOGOUT',now(),'{$_SERVER['REMOTE_ADDR']}','{$_SERVER['HTTP_USER_AGENT']}')";
$APP_ADODB->Execute($sqlstr);
$session_id  = session_id(); 
$sqlstr = "update users set session_id='',client_address='' where id={$id} and session_id='{$session_id}';";
$APP_ADODB->Execute($sqlstr);	

//$APP_ADODB->Execute("update users set agent_number ='' where id={$id};");
header('Location: '._INDEX_URL.'?module=User&action=login');

?>