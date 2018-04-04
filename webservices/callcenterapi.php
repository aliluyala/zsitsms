<?
if(empty($_GET['Method'])) die(json_encode(Array('Code'=>1,'Describe'=>'参数错误','Data'=>null)));
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/..');

$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$SS_CONFIG = require(_ROOT_DIR.'/config/zswitch.conf.php');
$CCAPI_CONFIG = require(_ROOT_DIR.'/config/callcenterapi.conf.php');
require(_ROOT_DIR.'/common/zswitch/ZSwitchComm.class.php');
require(_ROOT_DIR.'/include/IPAddress.php');

$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];

if(empty($_GET['AccessPwd']) || !in_array($_GET['AccessPwd'],$CCAPI_CONFIG['ClientAccessPwds'])) 
{
	die(json_encode(Array('Code'=>2,'Describe'=>'验证失败','Data'=>null)));
}
$clip = new IPAddress($_SERVER['REMOTE_ADDR']);

$allow = false;
foreach($CCAPI_CONFIG['Acls'] as $acl)
{
	if($clip->isAcl($acl))
	{
		$allow = true;
		break;
	}
}

if(!$allow)
{
	die(json_encode(Array('Code'=>2,'Describe'=>'验证失败','Data'=>null)));
}

if(!empty($_GET['ZSwitchID']) && $_GET['ZSwitchID'] != $CCAPI_CONFIG['ZSwitchID'] )
{
	die(json_encode(Array('Code'=>2,'Describe'=>'验证失败','Data'=>null)));
}

$conn = mysql_connect($dbs,$user,$pw);
if(!$conn)
{
	die(json_encode(Array('Code'=>9,'Describe'=>'系统错误','Data'=>null)));
}

if(!mysql_select_db($db,$conn))
{
	die(json_encode(Array('Code'=>9,'Describe'=>'系统错误','Data'=>null)));	
}


$retjson = Array('Code'=>1,'Describe'=>'参数错误','Data'=>null);

switch($_GET['Method'])
{
	case 'AgentLogin':
		if(!empty($_GET['Agent']) && !empty($_GET['UserName']))
		{
			$sql = "select * from zswitch_cc_agent_state where name='{$_GET['Agent']}';";
			$result = mysql_query($sql,$conn);
			if(!$result || mysql_num_rows($result) <= 0)
			{
				$retjson['Code'] = 3;
				$retjson['Describe'] = '座席号码不存在';				
				die(json_encode($retjson));
				break;
			}
			$row = mysql_fetch_object($result);
			if(!empty($row->workno))
			{
				$retjson['Code'] = 4;
				$retjson['Describe'] = '座席已被其他用户登录';				
				die(json_encode($retjson));	
				break;
			}
			$sql = "update  zswitch_cc_agent_state set workno='{$_GET['UserName']}' where name='{$_GET['Agent']}';";
			 mysql_query($sql,$conn);
			$comm = new ZSwitchComm();
			$comm->login($_GET['Agent'],$CCAPI_CONFIG['Queue']);
			$retjson['Code'] = 0;
			$retjson['Describe'] = '执行成功';				
			die(json_encode($retjson));	
			break;	
		}
		break;
	case 'AgentLogout':
		if( !empty($_GET['UserName']))
		{
			$sql = "select * from zswitch_cc_agent_state where workno='{$_GET['UserName']}';";
			$result = mysql_query($sql,$conn);
			if(!$result || mysql_num_rows($result) <= 0)
			{
				$retjson['Code'] = 3;
				$retjson['Describe'] = '没有登录座席';				
				die(json_encode($retjson));
				break;
			}
			$row = mysql_fetch_object($result);

			$sql = "update  zswitch_cc_agent_state set workno='' where name='{$row->name}';";
			 mysql_query($sql,$conn);
			$comm = new ZSwitchComm();
			$comm->login($_GET['Agent'],$CCAPI_CONFIG['Queue'],'NO');
			$retjson['Code'] = 0;
			$retjson['Describe'] = '执行成功';				
			die(json_encode($retjson));	
			break;	
		}
	
		break;
	case 'QueryAgentState':
		$sql = 'select * from  zswitch_cc_agent_state ';
		if(!empty($_GET['Agent']))
		{
			$sql .= "where name = '{$_GET['Agent']}';";
		}
		$result = mysql_query($sql,$conn);
		if(!$result || mysql_num_rows($result) <= 0)
		{
			$retjson['Code'] = 3;
			$retjson['Describe'] = '座席没有发现';				
			die(json_encode($retjson));
			break;
		}
		$data = Array();	
		while($row = mysql_fetch_object($result))
		{
			$e = Array();
			$e['Agent'] = $row->name;
			$e['UserName'] = $row->workno;
			$e['State'] = $row->state;
			$e['OtherNumbe'] = $row->other_number;
			$e['Dir'] = $row->dir;
			$e['CallTime'] = $row->start_time;
			$e['AnswerTime'] = $row->answer_time;
			$data[] = $e;
		}
		$retjson['Code'] = 0;
		$retjson['Describe'] = '执行成功';	
		$retjson['Data'] = $data;
		die(json_encode($retjson));					
		break;
	case 'SpyAgent':
		if(!empty($_GET['Agent']) && !empty($_GET['ByAgent']))
		{
			$sql = "select * from zswitch_cc_agent_state where name = '{$_GET['Agent']}';";
			$result =  mysql_query($sql,$conn);
			if(!$result || mysql_num_rows($result) <= 0)
			{
				$retjson['Code'] = 3;
				$retjson['Describe'] = '监听人座席不存在';				
				die(json_encode($retjson));	
				break;	
			}
			$sql = "select * from zswitch_cc_agent_state where name = '{$_GET['ByAgent']}';";
			$result =  mysql_query($sql,$conn);
			if(!$result || mysql_num_rows($result) <= 0)
			{
				$retjson['Code'] = 4;
				$retjson['Describe'] = '被监听座席不存在';				
				die(json_encode($retjson));	
				break;	
			}
			$row = 	mysql_fetch_object($result);
			if($row->state != 'callin_talking' || $row->state != 'callout_talking' )
			{
				$retjson['Code'] = 5;
				$retjson['Describe'] = '被监听座席不在通话状态';				
				die(json_encode($retjson));	
				break;					
			}
			$comm = new ZSwitchComm();
			
			if(!$comm->spy($_GET['Agent'],$row->uuid,$_GET['ByAgent']))
			{
				$retjson['Code'] = 5;
				$retjson['Describe'] = '被监听座席不在通话状态';				
				die(json_encode($retjson));	
				break;									
			}
			$retjson['Code'] = 0;
			$retjson['Describe'] = '执行成功';				
			die(json_encode($retjson));				
		}
		break;
	case 'AddVIPNumber':
		if(!empty($_GET['Number']) && !empty($_GET['Level']))
		{
			$level = intval($_GET['Level']);
			if($level<=0)
			{
				break;
			}
			$sql = "select * from  zswitch_cc_vipnumber where number = '{$_GET['Number']}';" ;
			$result =  mysql_query($sql,$conn);
			if(!$result || mysql_num_rows($result) <= 0)
			{
				$sql = "insert into zswitch_cc_vipnumber(number,level) values('{$_GET['Number']}',{$level});";				
			}
			else
			{
				$sql = "update zswitch_cc_vipnumber set level = {$level} where number = '{$_GET['Number']}';";
			}
			mysql_query($sql,$conn);
			$retjson['Code'] = 0;
			$retjson['Describe'] = '执行成功';				
			die(json_encode($retjson));	
		}
		break;
	case 'DelVIPNumber':
		if(!empty($_GET['Number']))
		{
			$sql = "delete from  zswitch_cc_vipnumber where number = '{$_GET['Number']}';" ;
			mysql_query($sql,$conn);
			$retjson['Code'] = 0;
			$retjson['Describe'] = '执行成功';				
			die(json_encode($retjson));	
		}	
		break;
	case 'AgentCallOut':
		if(!empty($_GET['UserName']) && !empty($_GET['Number']))
		{
			$sql = "select * from zswitch_cc_agent_state where workno ='{$_GET['UserName']}';";
			$result = mysql_query($sql,$conn);
			if(!$result || mysql_num_rows($result) <= 0)
			{
				break;	
			}
			$comm = new ZSwitchComm();
			$row = mysql_fetch_object($result);
			if($comm->agentCallout($row->name,$_GET['Number']))
			{
				$retjson['Code'] = 0;
				$retjson['Describe'] = '执行成功';				
				die(json_encode($retjson));	
				break;					
			}
			$retjson['Code'] = 3;
			$retjson['Describe'] = '呼叫失败';				
			die(json_encode($retjson));	
		}
		break;
	case 'DownloadRecord':
		if(!empty($_GET['UUID']))
		{
			$sql = "select * from zswitch_cc_agent_cdr where uuid = '{$_GET['UUID']}';";
			$result = mysql_query($sql,$conn);
			if(!$result || mysql_num_rows($result) <= 0)
			{
				break;	
			}
			$row = mysql_fetch_object($result);
			if($row->answered_datetime != '0000-00-00 00:00:00')
			{
				$filepath = $SS_CONFIG['recordfile_path'].Date('Y-m-d',strtotime($row->answered_datetime)).'/';
				$filepath .= $row->agent_name.'/';
				$file_name1 = $row->agent_name.'_'.$row->other_number.'_'.Date('YmdHis',strtotime($row->answered_datetime)).'.wav';
				$file = $row->uuid.'_'.$file_name1;
				$filepath .=$file;
				if(file_exists(_ROOT_DIR.'/'.$filepath))
				{
					$file = fopen(_ROOT_DIR.'/'.$filepath,"r"); 
					header("Content-type: application/octet-stream"); 
					header("Accept-Ranges: bytes"); 
					header("Accept-Length: ".filesize(_ROOT_DIR.'/'.$filepath)); 
					header("Content-Disposition: attachment; filename=" . $file_name1); 
					echo fread($file,filesize(_ROOT_DIR.'/'.$filepath)); 
					fclose($file); 				
					exit;
				}				
			}
		}
	
		break;
	default:
		break;
}
die(json_encode($retjson));
?>