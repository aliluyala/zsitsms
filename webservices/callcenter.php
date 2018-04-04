<?php
if(empty($_GET['action'])) die("1|Not find param:action!");
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/..');
require(_ROOT_DIR.'/webservices/sms/smsapi.php');
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
mysql_select_db($db,$con);
$code = 0;
$msg = 'sussess!';
$action = $_GET['action'];
if(!empty($_GET['otherNumber']) && substr($_GET['otherNumber'],0,3) == '+86')
{
	if(strlen($_GET['otherNumber']) == 14 && substr($_GET['otherNumber'],0,4) == '+861')
	{
		$_GET['otherNumber'] = substr($_GET['otherNumber'],3);
	}
	elseif(strlen($_GET['otherNumber']) == 13 && substr($_GET['otherNumber'],0,5) == '+8621')
	{
		$_GET['otherNumber'] = substr($_GET['otherNumber'],5);
	}
	else
	{
		$_GET['otherNumber'] = '0'.substr($_GET['otherNumber'],3);
	}
	
}
if($action == 'InitSystem')
{
	if(!mysql_query("delete from zswitch_cc_agent_state;",$con))
	{
		$code=2;
		$msg="DB ERROR!";
	}	
	if(!mysql_query("delete from zswitch_cc_queue_state;",$con))
	{
		$code=2;
		$msg="DB ERROR!";
	}
	if(!mysql_query("delete from zswitch_cc_member_state;",$con))
	{
		$code=2;
		$msg="DB ERROR!";
	}	
}
elseif($action == 'AddQueue')
{
	if(empty($_GET['name']) || empty($_GET['state']))
	{
		$code = 4;
		$msg  = 'param error!';
	}
	else
	{
		$sql = "insert into zswitch_cc_queue_state(name,state) values('{$_GET['name']}','{$_GET['state']}');";
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}
	}
}
elseif($action == 'AddAgent')
{
	if(empty($_GET['name']) || empty($_GET['state']) || empty($_GET['contact']) || empty($_GET['status']))
 	{
  		$code = 4;
		$msg  = 'param error!';
	}
	else
	{
		//$result = mysql_query("select id from users where agent_number = '{$_GET['name']}' and agent_have='YES' and agent_login='YES';",$con);
		$userid = -1;
		//if($result && mysql_num_rows($result)>0)
		//{
		//	$row = mysql_fetch_object($result);
		//	$userid = $row->id;
		//}
		
		$sql = "insert into zswitch_cc_agent_state(userid,name,state,status,contact)";
		$sql .= " values({$userid},'{$_GET['name']}','{$_GET['state']}','{$_GET['status']}','{$_GET['contact']}');";
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}
	}	
}
elseif($action == 'AgentStatusChange')
{
	if(isset($_GET['agent']) && isset($_GET['status']))
	{
		$sql = "update zswitch_cc_agent_state set status ='{$_GET['status']}' where name='{$_GET['agent']}' limit 1;";
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}		
	}
}
elseif($action == 'MemberJoin')
{
	if(isset($_GET['caller']) && isset($_GET['queue']) && isset($_GET['UUID']))                       
	{                                                                                               
		$sql = "insert into zswitch_cc_member_state(queue,uuid,caller_number,joined_time,state) values('{$_GET['queue']}','{$_GET['UUID']}','{$_GET['caller']}',now(),'waiting');";
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}
		$sql = "update zswitch_cc_queue_state set current_members=current_members+1 where name='{$_GET['queue']}' limit 1";	
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}		
	}	                                                                                    
}
elseif($action == 'MemberLeave')
{
	if(isset($_GET['caller']) && isset($_GET['queue']) && isset($_GET['UUID']) && isset($_GET['cause']) &&
		isset($_GET['joinTime']) && isset($_GET['leaveTime']) && isset($_GET['agentAnswerTime'])) 
	{
		$agent = '';
		$result = mysql_query("select agent_name from zswitch_cc_member_state where uuid='{$_GET['UUID']}' limit 1;",$con);
		if($result && mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_object($result);
			$agent = $row->agent_name;
		}	
		$sql = "delete from zswitch_cc_member_state where uuid='{$_GET['UUID']}';";
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}
		$total_timed = strtotime($_GET['leaveTime'])-strtotime($_GET['joinTime']);
		if($_GET['agentAnswerTime'] == '0000-00-00 00:00:00')
		{
			$wait_timed = $total_timed;
			$talk_timed = 0;
			$answered = 0;
			$noanswer = 1;
		}
		else
		{
			$wait_timed = strtotime($_GET['agentAnswerTime'])-strtotime($_GET['joinTime']);
			$talk_timed = strtotime($_GET['leaveTime'])-strtotime($_GET['agentAnswerTime']);
			$noanswer = 0;
			$answered = 1;	
		}	
		$sql = "insert into zswitch_cc_queue_cdr(uuid,queue,caller_number,agent_name,joined_time,bridge_time,end_time,state,total_timed,wait_timed,talk_timed) ";
		$sql .= "values('{$_GET['UUID']}','{$_GET['queue']}','{$_GET['caller']}','{$agent}','{$_GET['joinTime']}','{$_GET['agentAnswerTime']}'";
		$sql .= ",'{$_GET['leaveTime']}','{$_GET['cause']}',{$total_timed},{$wait_timed},{$talk_timed});";	
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}
		$sql = "update zswitch_cc_queue_state set total_calls_answered=total_calls_answered+{$answered},total_calls_no_answer=total_calls_no_answer+{$noanswer},";
		$sql .= "today_calls_answered=today_calls_answered+{$answered},today_calls_no_answer=today_calls_no_answer+{$noanswer},";
		$sql .= "total_talk_time=total_talk_time+{$talk_timed},today_talk_time=today_talk_time+{$talk_timed},current_members=current_members-1 where name='{$_GET['queue']}' limit 1;";
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}
		
		if($answered == 1 && isset($SS_CONFIG['call_event_sms']['queue_answered']))
		{
			sendTplSms($SS_CONFIG['call_event_sms']['queue_answered'],$_GET['caller'],$agent,$_GET['queue'],$_GET['joinTime'],$_GET['agentAnswerTime'],$_GET['leaveTime']);
		}
		elseif($answered == 0 && isset($SS_CONFIG['call_event_sms']['queue_noanswer']))
		{
			sendTplSms($SS_CONFIG['call_event_sms']['queue_noanswer'],$_GET['caller'],$agent,$_GET['queue'],$_GET['joinTime'],$_GET['agentAnswerTime'],$_GET['leaveTime']);
		}
	}
}
elseif($action == 'AgentCallinRinging')
{
	if(isset($_GET['agent']) && isset($_GET['queue']) && isset($_GET['otherNumber'])&& isset($_GET['UUID']) && isset($_GET['blegUUID']) && isset($_GET['startTime']))
	{
		if(!empty($_GET['queue']))
		{
			$sql = "update zswitch_cc_member_state set agent_name = '{$_GET['agent']}',state='trying' where caller_number='{$_GET['otherNumber']}' limit 1;";
			if(!mysql_query($sql,$con))
			{
				$code = 2;
				$msg="DB ERROR!";
			}
		}	
		$starttime = $_GET['startTime'];
		$sql = "update zswitch_cc_agent_state set queue = '{$_GET['queue']}',uuid='{$_GET['UUID']}',other_uuid='{$_GET['blegUUID']}',other_number='{$_GET['otherNumber']}'";
		$sql .= ",dir='callin',start_time='{$starttime}',answer_time='0000-00-00 00:00:00',hangup_time='0000-00-00 00:00:00',hangup_cause='' ";
		$sql .= ",state = 'callin_ringing' where name='{$_GET['agent']}' limit 1;";
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}
		if(is_file(_ROOT_DIR.'/config/callcenterapi.conf.php'))
		{
			$CCAPI_CONFIG = require(_ROOT_DIR.'/config/callcenterapi.conf.php');
			if(is_array($CCAPI_CONFIG) && is_array($CCAPI_CONFIG['EventUrls']))
			{
				$UserName = '';
				$sql = "select * from zswitch_cc_agent_state where name='{$_GET['agent']}';";
				$result = mysql_query($sql,$con);
				if($result && mysql_num_rows($result) > 0 )
				{
					$row = mysql_fetch_object($result);
					$UserName = $row->workno;
				}
				$isVIP = 'NO';
				$sql = "select * from zswitch_cc_vipnumber where number = '{$_GET['otherNumber']}';";
				$result = mysql_query($sql,$con);
				if($result && mysql_num_rows($result) > 0 )
				{
					$isVIP = 'YES';
				}	
				$ch = curl_init();				
				curl_setopt($ch,CURLOPT_HEADER ,0); 
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE); 
				curl_setopt($ch,CURLOPT_TIMEOUT,5);
				curl_setopt($ch, CURLOPT_POST, TRUE);
				$data = 'Event=AgentCallin';
				$data .= '&UserName='.urlencode($UserName);
				$data .= '&Agent='.urlencode($_GET['agent']);
				$data .= '&OtherNumber='.urlencode($_GET['otherNumber']);
				$data .= '&UUID='.urlencode($_GET['UUID']);
				$data .= '&CallTime='.urlencode($_GET['startTime']);
				$data .= '&IsVIP='.$isVIP;
				$data .= '&ZSwitchID='.$CCAPI_CONFIG['ZSwitchID'];
				
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);					
				foreach($CCAPI_CONFIG['EventUrls'] as $url)
				{
					
					curl_setopt($ch,CURLOPT_URL ,$url);
					curl_exec($ch);
				}
				curl_close($ch);
			}
		}	
			
	}
	
}
elseif($action == 'AgentCallinAndwered')
{
	if(isset($_GET['agent']) && isset($_GET['queue']) && isset($_GET['UUID'])  && isset($_GET['otherNumber']) && isset($_GET['blegUUID']) && isset($_GET['answerTime']))
	{
		$answertime = $_GET['answerTime'];
		if(!empty($_GET['queue']))
		{
			$sql = "update zswitch_cc_member_state set state='answered',agent_name = '{$_GET['agent']}' , bridge_time = '{$answertime}'  where caller_number='{$_GET['otherNumber']}' limit 1;";
			if(!mysql_query($sql,$con))
			{
				$code = 2;
				$msg="DB ERROR!";
			}
			$sql = "select workno from zswitch_cc_agent_state where name='{$_GET['agent']}' limit 1;";
			$result = mysql_query($sql,$con);
			if($result && mysql_num_rows($result)>0)
			{
				$row = mysql_fetch_object($result);
				$msg = $row->workno;
				//$starttime = $row->start_time;
			}			
		}	
		$sql = "update zswitch_cc_agent_state set uuid='{$_GET['UUID']}',state='callin_talking'";
		$sql .= ",answer_time='{$answertime}',other_number='{$_GET['otherNumber']}' where name='{$_GET['agent']}' limit 1;";
		if(!mysql_query($sql,$con))
		{
			$code = 2;
			$msg="DB ERROR!";
		}	
	}	

}
elseif($action == 'AgentCallinHangup')
{
	$userid = '-1';
	//$starttime = '0000-00-00 00:00:00';
	$sql = "select userid  from zswitch_cc_agent_state  where name='{$_GET['agent']}' limit 1;";
	$result = mysql_query($sql,$con);
	if($result && mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_object($result);
		$userid = $row->userid;
		//$starttime = $row->start_time;
	}
	
	if($SS_CONFIG['allow_cdr_defagent'] && $userid == '-1')
	{
		$sql = "select id from users where agent_number='{$_GET['agent']}'; ";
		$result = mysql_query($sql,$con);
		if($result && mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_object($result);
			$userid = $row->id;
		}
	}
	
	$starttime  = $_GET['startTime'];
	$answertime = $_GET['answerTime'];
	$hanguptime = $_GET['hangupTime'];
	$total_time = strtotime($hanguptime) - strtotime($starttime);
	$talk_time  = 0;
	$noAnswered = 1;
	$answered   = 0;
	if($_GET['answerTime']!='0000-00-00 00:00:00')
	{
		$talk_time = strtotime($hanguptime) - strtotime($answertime);
		$answered  = 1;
		$noAnswered = 0;
	}	

	$sql = "insert into zswitch_cc_agent_cdr(userid,queue,agent_name,dir,other_number,uuid,created_datetime";
	$sql .= ",answered_datetime,hangup_datetime,bleg_uuid,hangup_cause,total_timed,talk_timed) "; 
	$sql .= "values({$userid},'{$_GET['queue']}','{$_GET['agent']}','callin','{$_GET['otherNumber']}','{$_GET['UUID']}','{$starttime}'";
	$sql .= ",'{$answertime}','{$hanguptime}','{$_GET['blegUUID']}','{$_GET['hangupCase']}',";	
	$sql .= "{$total_time},{$talk_time});";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}

	$sql = "update zswitch_cc_agent_state set ";
	$sql .=	"total_callins_answered  = total_callins_answered  +{$answered},";
	$sql .=	"today_callins_answered  = today_callins_answered  +{$answered},";
	$sql .=	"total_callin_talk_time  = total_callin_talk_time  +{$talk_time},";
	$sql .=	"today_callin_talk_time  = today_callin_talk_time  +{$talk_time},";
	$sql .=	"total_calls             = total_calls             +1,";
	$sql .=	"today_calls             = today_calls             +1,";
	$sql .=	"total_talk_time         = total_talk_time         +{$talk_time},";
	$sql .=	"today_talk_time         = today_talk_time         +{$talk_time}, ";
	$sql .= "total_callins_no_answer = total_callins_no_answer +{$noAnswered},";
	$sql .= "today_callins_no_answer = today_callins_no_answer +{$noAnswered}";
	$sql .=	" where name='{$_GET['agent']}' limit 1;";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}		

	$sql = "update zswitch_cc_agent_state set queue = '',uuid='',other_uuid='',other_number='',state='Waiting'";
	$sql .= ",dir='',start_time='0000-00-00 00:00:00',answer_time='0000-00-00 00:00:00',hangup_time='0000-00-00 00:00:00',hangup_cause='' where name='{$_GET['agent']}' limit 1;";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}
	if($answered == 1 && isset($SS_CONFIG['call_event_sms']['agent_callin_answered']))
	{
		sendTplSms($SS_CONFIG['call_event_sms']['agent_callin_answered'],$_GET['otherNumber'],$_GET['agent'],$_GET['queue'],$starttime,$answertime,$hanguptime);
	}
	elseif($answered == 0 && isset($SS_CONFIG['call_event_sms']['agent_callin_noanswer']))
	{
		sendTplSms($SS_CONFIG['call_event_sms']['agent_callin_noanswer'],$_GET['otherNumber'],$_GET['agent'],$_GET['queue'],$starttime,$answertime,$hanguptime);
	}
	
	if(is_file(_ROOT_DIR.'/config/callcenterapi.conf.php'))
	{
		$CCAPI_CONFIG = require(_ROOT_DIR.'/config/callcenterapi.conf.php');
		if(is_array($CCAPI_CONFIG) && is_array($CCAPI_CONFIG['EventUrls']))
		{
			$UserName = '';
			$sql = "select * from zswitch_cc_agent_state where name='{$_GET['agent']}';";
			$result = mysql_query($sql,$con);
			if($result && mysql_num_rows($result) > 0 )
			{
				$row = mysql_fetch_object($result);
				$UserName = $row->workno;
			}
			$isVIP = 'NO';
			$sql = "select * from zswitch_cc_vipnumber where number = '{$_GET['otherNumber']}';";
			$result = mysql_query($sql,$con);
			if($result && mysql_num_rows($result) > 0 )
			{
				$isVIP = 'YES';
			}	
			$ch = curl_init();				
			curl_setopt($ch,CURLOPT_HEADER ,0); 
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE); 
			curl_setopt($ch,CURLOPT_TIMEOUT,5);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			$data = 'Event=AgentCallin';
			$data .= '&UserName='.urlencode($UserName);
			$data .= '&Agent='.urlencode($_GET['agent']);
			$data .= '&OtherNumber='.urlencode($_GET['otherNumber']);
			$data .= '&UUID='.urlencode($_GET['UUID']);
			$data .= '&CallTime='.urlencode($_GET['startTime']);
			$data .= '&AnswerTime='.urlencode($_GET['answerTime']);
			$data .= '&HangupTime='.urlencode($_GET['hangupTime']);
			$data .= '&IsVIP='.$isVIP;
			$data .= '&ZSwitchID='.$CCAPI_CONFIG['ZSwitchID'];
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);					
			foreach($CCAPI_CONFIG['EventUrls'] as $url)
			{
				
				curl_setopt($ch,CURLOPT_URL ,$url);
				curl_exec($ch);
			}
			curl_close($ch);
		}
	}	
	
}
elseif($action == 'AgentCalloutRinging')
{
	$starttime = $_GET['startTime'];
	$sql = "update zswitch_cc_agent_state set queue = '',uuid='{$_GET['UUID']}',other_uuid='{$_GET['blegUUID']}',other_number='{$_GET['otherNumber']}'";
	$sql .= ",dir='callout',start_time='{$starttime}',answer_time='0000-00-00 00:00:00',hangup_time='0000-00-00 00:00:00',hangup_cause='' ";
	$sql .= ",state = 'callout_ringing' where name='{$_GET['agent']}' limit 1;";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}	

}
elseif($action == 'AgentCalloutAndwered')
{
	$answertime = $_GET['answerTime'];
	$sql = "update zswitch_cc_agent_state set uuid='{$_GET['UUID']}',state='callout_talking'";
	$sql .= ",answer_time='{$answertime}',other_number='{$_GET['otherNumber']}' where name='{$_GET['agent']}' limit 1;";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}	
}
elseif($action == 'AgentCalloutHangup')
{
	$userid = '-1';
	$sql = "select userid  from zswitch_cc_agent_state  where name='{$_GET['agent']}' limit 1;";
	$result = mysql_query($sql,$con);
	if($result && mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_object($result);
		$userid = $row->userid;

	}
	if($SS_CONFIG['allow_cdr_defagent'] && $userid == '-1')
	{
		$sql = "select id from users where agent_number='{$_GET['agent']}'; ";
		$result = mysql_query($sql,$con);
		if($result && mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_object($result);
			$userid = $row->id;
		}
	}
	
	$starttime = $_GET['startTime'];
	$answertime = $_GET['answerTime'];
	$hanguptime = $_GET['hangupTime'];
	$total_time = strtotime($hanguptime) - strtotime($starttime);
	$talk_time = 0;
	$noAnswered = 1;
	$answered = 0;
	if($_GET['answerTime'] != '0000-00-00 00:00:00')
	{
		$talk_time = strtotime($hanguptime) - strtotime($answertime);
		$answered = 1;
		$noAnswered = 0;
	}	

	$otherNum = $_GET['otherNumber'];
	$curl = curl_init("http://112.74.96.208/zsidms/webservices/queryHCode.php?mobile={$otherNum}");
	curl_setopt($curl, CURLOPT_TIMEOUT, 10); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 	    
	$result = curl_exec($curl);
	$area_code = '';
	if(is_string($result) && preg_match('/(\d+),(\d+)/',$result,$out))
	{
		$area_code = $out[2];
	}
	
	$sql = "insert into zswitch_cc_agent_cdr(userid,queue,agent_name,dir,other_number,uuid,created_datetime";
	$sql .= ",answered_datetime,hangup_datetime,bleg_uuid,hangup_cause,total_timed,talk_timed, other_area_code ) "; 
	$sql .= "values({$userid},'{$_GET['queue']}','{$_GET['agent']}','callout','{$_GET['otherNumber']}','{$_GET['UUID']}','{$starttime}'";
	$sql .= ",'{$answertime}','{$hanguptime}','{$_GET['blegUUID']}','{$_GET['hangupCase']}',";	
	$sql .= "{$total_time},{$talk_time},'{$area_code}');";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}

	$sql = "update zswitch_cc_agent_state set ";
	$sql .=	"total_callouts_answered  = total_callouts_answered  +{$answered},";
	$sql .=	"today_callouts_answered  = today_callouts_answered  +{$answered},";
	$sql .=	"total_callout_talk_time  = total_callout_talk_time  +{$talk_time},";
	$sql .=	"today_callout_talk_time  = today_callout_talk_time  +{$talk_time},";
	$sql .=	"total_calls             = total_calls             +1,";
	$sql .=	"today_calls             = today_calls             +1,";
	$sql .=	"total_talk_time         = total_talk_time         +{$talk_time},";
	$sql .=	"today_talk_time         = today_talk_time         +{$talk_time}, ";
	$sql .= "total_callouts_no_answer = total_callouts_no_answer +{$noAnswered},";
	$sql .= "today_callouts_no_answer = today_callouts_no_answer +{$noAnswered}";
	$sql .=	" where name='{$_GET['agent']}' limit 1;";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}		

	$sql = "update zswitch_cc_agent_state set queue = '',uuid='',other_uuid='',other_number='',state='Waiting'";
	$sql .= ",dir='',start_time='0000-00-00 00:00:00',answer_time='0000-00-00 00:00:00',hangup_time='0000-00-00 00:00:00',hangup_cause='' where name='{$_GET['agent']}' limit 1;";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}
	
	if($answered == 1 && isset($SS_CONFIG['call_event_sms']['agent_callout_answered']))
	{
		sendTplSms($SS_CONFIG['call_event_sms']['agent_callout_answered'],$_GET['otherNumber'],$_GET['agent'],$_GET['queue'],$starttime,$answertime,$hanguptime);
	}
	elseif($answered == 0 && isset($SS_CONFIG['call_event_sms']['agent_callout_noanswer']))
	{
		sendTplSms($SS_CONFIG['call_event_sms']['agent_callout_noanswer'],$_GET['otherNumber'],$_GET['agent'],$_GET['queue'],$starttime,$answertime,$hanguptime);
	}
	

	
}
elseif($action == 'ResetToadyStatistics')
{
	$sql = "update zswitch_cc_agent_state set ";
	$sql .=	"today_callouts_answered  = 0,";
	$sql .=	"today_callout_talk_time  = 0,";
	$sql .=	"today_calls              = 0,";
	$sql .=	"today_talk_time          = 0, ";
	$sql .= "today_callouts_no_answer = 0 ;";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}	
	$sql = "update zswitch_cc_queue_state set ";
	$sql .= "today_calls_answered=0,today_calls_no_answer=0,";
	$sql .= "total_talk_time=0,today_talk_time=0 ;";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}	
}
elseif($action == 'MemberEvaluate')
{
	$userid = -1;
	$sql = "select userid  from zswitch_cc_agent_state  where name='{$_GET['agent']}' limit 1;";
	$result = mysql_query($sql,$con);
	if($result && mysql_num_rows($result)>0)
	{
		$row = mysql_fetch_object($result);
		$userid = $row->userid;

	}	
	$sql = "insert into zswitch_cc_account_evaluate(userid,caller,callee,uuid,agent,dtmf,ptime) ";
	$sql .= "values({$userid},'{$_GET['caller']}','{$_GET['callee']}','{$_GET['uuid']}','{$_GET['agent']}','{$_GET['dtmf']}',now());";
	if(!mysql_query($sql,$con))
	{
		$code = 2;
		$msg="DB ERROR!";
	}		
}
elseif($action == 'GetVIPNumberLevel')
{
	$code = 0;
	$msg = "";
	if(!empty($_GET['number']))
	{
		$sql = "select * from zswitch_cc_vipnumber where number='{$_GET['number']}';";
		$result = mysql_query($sql,$con);
		if($result && mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_object($result);
			$code = $row->level;
		}
	}
}
else
{
	$code=3;
	$msg="Invalid actiion:{$action}!";
}

die("{$code}|{$msg}\r\n");


?>