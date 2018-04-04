<?php
global $APP_ADODB;	

$user_name = $_COOKIE['user_name']; 
$my_session_id  = session_id();
$result = $APP_ADODB->Execute("select * from users where user_name ='{$user_name}' ;");
if($result && $result->RecordCount()>0)
{
	$id             = $result->fields['id'];
	$session_id     = $result->fields['session_id'];
	$activity_time  = $result->fields['activity_time'];
	$client_address = $result->fields['client_address'];
	if( $session_id == $my_session_id)
	{
		$sqlstr = "update users set activity_time=now() where id={$id};";
		$APP_ADODB->Execute($sqlstr);
		$data = array();
		$data['state'] = 'login';
		echo return_ajax('success',$data);
		die();
	}
	else
	{
		$data = array();
		$data['state'] = 'extrusion';
		$data['client_address'] = $client_address;
		$data['activity_time'] = $activity_time;
		echo return_ajax('success',$data);
		die();		
	}
}
echo return_ajax('failure','用户不存在！');

?>