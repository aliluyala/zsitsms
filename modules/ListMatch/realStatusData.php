<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;

require_once(_ROOT_DIR."/modules/ListMatch/ListMatchModule.class.php");
$modclass= "ListMatchModule";
$mod = new $modclass();	

if(empty($_GET['oper']))
{
	die(return_ajax('error',array()));
}

$oper = $_GET['oper'];
if($oper == 'query')
{
	$reset = $mod->getListQueryRecord(array(),array(),null,null,null,null,0,100);
	$data = array();
	foreach($reset as $r)
	{
		$row = array();
		$row['id'] = $r['id'];
		$row['name'] = $r['name'];
		$row['batch'] = $r['batch'];
		$row['state'] = $r['state'];
		$row['tag'] = $r['tag'];
		$row['last_time'] = $r['last_time'];
		$row['last_time'] = $r['last_time'];
		$row['complete_count'] = $r['complete_count'];
		$row['request_count']  = $r['request_count'] ;
		$row['success_count']  = $r['success_count'] ;
		$row['failure_count']  = $r['failure_count'] ;
		$row['request_complete']  = $r['request_complete'] ;
		$row['error_info']  = $r['error_info'] ;
		$data[] = $row;	
	}
	die(return_ajax('success',$data));
}

if(empty($_GET['recordid']))
{
	die(return_ajax('error',array()));
}
$recordid = $_GET['recordid'];
if($oper == 'stop')
{
	$mod->updateOneRecordset($recordid,null,null,array('state'=>'STOP'));
}
elseif($oper == 'run')
{
	$mod->updateOneRecordset($recordid,null,null,array('state'=>'RUNING'));
}


?>
