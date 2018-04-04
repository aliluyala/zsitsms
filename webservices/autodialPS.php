<?php
//自动拨号 点击取号webservice
if(empty($_GET['action'])) die("1|Not find param:action!");
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SS_CONFIG = require(_ROOT_DIR.'/config/zswitch.conf.php');
$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
$acl = $SS_CONFIG['webservicesACL'];
$clientIP = $_SERVER['REMOTE_ADDR'];
if(empty($acl) || !in_array($clientIP,$acl))
{
	die("2|client's ip not in ACL!");
}
$con = mysql_connect($dbs,$user,$pw);
if(!$con)
{
	die("2|Connect DB failure!");
}
if(!mysql_select_db($db,$con))
{
	die("2|select database '{$db}' failure!");
}
$code = 0;
$msg = 'sussess!';
$action = $_GET['action'];
if($action == 'getNumber')
{
	if(empty($_GET['groupid']) || empty($_GET['userid']) || empty($_GET['agent']))
	{
		$code = 3;
		$msg = 'Params error!';
	}
	else
	{
		$sql = "select * from zswitch_ps_autodial_tasks where groupid={$_GET['groupid']} and state='Runing'; ";
		$result = mysql_query($sql,$con);
		if($result && mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_object($result);
			$taskid = $row->id;
			$sql = "select id,number,accountid from zswitch_ps_autodial_number where taskid={$taskid} and status='Waiting' ;";
			$result = mysql_query($sql,$con);
			if($result && mysql_num_rows($result)>0)
			{
				$count = mysql_num_rows($result);
				$numberid = -1;	
				$number = '';
				$accountid =-1;	
				$succ = false;                                                         
				while($row = mysql_fetch_object($result))                              
				{
					$numberid = $row->id;
					$number = $row->number;
					$accountid = $row->accountid;	
					$sql = "update zswitch_ps_autodial_number set status = 'Handling',call_time=now(),agent='{$_GET['agent']}',userid={$_GET['userid']} where id={$numberid} and status='Waiting';";
					mysql_query($sql,$con);
					if(mysql_affected_rows($con)>0)
					{
						$succ = true;
						break;
					}	
				}
				if($succ)
				{
					$code =0;
					$msg = "{$number},{$numberid},{$accountid}";
				}
				else
				{
					$code = 6;
					$msg = 'Not find number!';								
				}				
			}
			else
			{
				$code = 6;
				$msg = 'Not find number!';	
			}
		}
		else
		{
			$code = 5;
			$msg = 'Not auto dial task!';
		}
	}
}
elseif($action == 'startCall')
{
	if(empty($_GET['numberid']) || empty($_GET['userid']) || empty($_GET['number']))
	{
		$code = 3;
		$msg = 'Params error!';		
	}
	else
	{
		$sql = "update zswitch_ps_autodial_job set number={$_GET['number']},numberid={$_GET['numberid']},state='calling' where userid={$_GET['userid']};";
		mysql_query($sql,$con);
	}
}
elseif($action == 'answered')
{
	if(empty($_GET['userid']))
	{
		$code = 3;
		$msg = 'Params error!';		
	}
	else
	{
		$sql = "update zswitch_ps_autodial_job set state='answered' where userid={$_GET['userid']};";
		mysql_query($sql,$con);
	}	
}
elseif($action == 'hangup')
{
	if(empty($_GET['numberid']) ||empty($_GET['userid'])||empty($_GET['result']))
	{
		$code = 3;
		$msg = 'Params error!';		
	}
	else
	{
		$cause = 'Other';
		if($_GET['result']=='NORMAL_CLEARING' || $_GET['result']=='SUCCESS')
		{
			$cause = 'Talk';
		}
		elseif($_GET['result']=='USER_BUSY')
		{
			$cause = 'Busy';
		}
		elseif($_GET['result']=='NO_ANSWER')
		{
			$cause = 'No answer';
		}
		elseif($_GET['result'] == 'UNALLOCATED_NUMBER' )
		{
			$cause = 'Empty number';
		}
		$sql = "update zswitch_ps_autodial_number set status='Handled' ,result='{$cause}' where id={$_GET['numberid']};";
		mysql_query($sql,$con);
	}	
}
elseif($action == 'stop')
{
	if(empty($_GET['userid']))
	{
		$code = 3;
		$msg = 'Params error!';		
	}
	else
	{
		$sql = "delete from zswitch_ps_autodial_job  where userid={$_GET['userid']};";
		mysql_query($sql,$con);		
	}	
}
else
{
	$code=3;
	$msg="Invalid action:{$action}!";
}

die("{$code}|{$msg}\r\n");


?>