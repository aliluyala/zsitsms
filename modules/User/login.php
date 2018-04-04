<?php
global $APP_ADODB,$APP_CONFIG;

$smarty = new ZS_Smarty();
$login_error = '';
session_uset('authentication_logined');
session_uset('logined_userid');
session_uset('logined_username');
session_uset('logined_name');
session_uset('logined_is_admin');

$cloud_auth = true;
if($CLOUD_TYPE)
{
	$cloud_auth = false;
	if(!empty($_POST['cloudid']))
	{
		$conf = require(_ROOT_DIR.'/config/cloud.conf.php');
		$CLOUDMGR = new cloud($_POST['cloudid'],$conf);
		$status = $CLOUDMGR->status();
		if( $status == 'USING')
		{			
			$dbname = $CLOUDMGR->dbName();
			if($APP_ADODB->Execute("use {$dbname};"))
			{
				setcookie('cloudid',$_POST['cloudid'],time()+10*365*24*3600);
				session_set('logined_cloudid',$_POST['cloudid']);
				session_set('logined_cloud_name',$CLOUDMGR->name());
				$APP_ADODB->Execute("set names utf8;");
				$cloud_auth = true;	
			}
			else
			{
				$login_error = '系统错误！';
			}
		}
		elseif($status === false)
		{
			$login_error = '云ID不存！';
		}
		elseif( $status == 'AUDITING')
		{
			$login_error = "你登录的云ID在“审核中”！";
		}
		elseif( $status == 'LOCKED')
		{
			$login_error = "你登录的云ID已被锁定！";
		}
		elseif( $status == 'STOPED')
		{
			$login_error = "你登录的云ID已停用！";
		}
		elseif( $status == 'EXPIRED')
		{
			$login_error = "你登录的云ID已过期！";
		}			
	}

}

if($cloud_auth && isset($_POST['user_name']) && preg_match("/^[^',]+$/",$_POST['user_name']) && isset($_POST['password']) && preg_match("/^[0-9a-f]{32}+$/",$_POST['password'])  &&  isset($_POST['verify_code']))
{
	//session_destroy();
	if(session_get('authentication_verify_code') == md5($_POST['verify_code']))
	{
		$result = $APP_ADODB->Execute("select * from users where user_name ='{$_POST['user_name']}' limit 1;");
		if($result && $result->RecordCount()>0)
		{
			$id       = $result->fields['id'];
			$password = $result->fields['user_password'];
			$name     = $result->fields['name'];
			$is_admin = $result->fields['is_admin'];
			$status   = $result->fields['status'];
			$groupid  = $result->fields['groupid'];
			$defAgent = $result->fields['agent_number'];
			$defHaveAgent = $result->fields['agent_have'];
			$session_id     = $result->fields['session_id'];
			$activity_time  = $result->fields['activity_time'];
			$client_address = $result->fields['client_address'];
			if($status != 'Active')
			{
				$login_error = '帐号没有激活！';
			}
			elseif($APP_CONFIG['multiple_login'] == "reject" && !empty($session_id) && (time()-strtotime($activity_time))<$APP_CONFIG['activity_timeout'])
			{
				$login_error = "帐号已经在'{$client_address}'登录,请稍候再试！";
			}
			elseif($password == $_POST['password'] )
			{
				if($APP_CONFIG['multiple_login'] != 'allow')
				{
					$other_sess_file = "/var/lib/php/session/sess_{$session_id}";
					unlink($other_sess_file);
					$session_id  = session_id();   
					$client_address = $_SERVER['REMOTE_ADDR'];
					$sqlstr = "update users set session_id='{$session_id}', activity_time=now(),";
					$sqlstr .="client_address='{$client_address}' where id={$id};";
					$APP_ADODB->Execute($sqlstr);	
				}                                
				                                
				$agent_again = false;
				if($APP_CONFIG['have_agent'] && $defHaveAgent)
				{
					$agent_number = '';
					if(empty($_POST['agent_number']))
					{
						$agent_number = $defAgent;
					}
					else
					{
						$agent_number = $_POST['agent_number'];
					}
					
					if(!empty($agent_number))
					{
						$sqlstr = "select userid from zswitch_cc_agent_state where name='{$agent_number}' and userid>-1 and userid!={$id};";
						$agents = $APP_ADODB->Execute($sqlstr);
						if($agents && $agents->RecordCount()>0)
						{	
							$sqlstr = "select user_name from users where id={$agents->fields['userid']};";
							$ouser = $APP_ADODB->Execute($sqlstr);
							if($ouser && !$ouser->EOF)
							{
								$agent_again = true;
								$login_error = '座席号码已被以下用户使用：</br>';
								$login_error .= $ouser->fields['user_name'];
							}	
						}
					}
					else
					{
						$agent_again = true;
						$login_error = '必须指定你要使用的座席号码!';
					}
					//$sqlstr = "update users set agent_number ='{$_POST['agent_number']}' where id = {$id};";
					//$APP_ADODB->Execute($sqlstr);
					session_set('current_user_agent_number',$agent_number);
					setcookie('agent_number',$_POST['agent_number'],time()+3600*24*365*10);
				}
				if(!$agent_again)
				{
					session_set('authentication_logined',true);
					session_set('logined_userid',$id);
					session_set('logined_username',$_POST['user_name']);	
					session_set('logined_name', $name);
					session_set('logined_is_admin',($is_admin == 'YES')?true:false);				
					//$groupres = $APP_ADODB->Execute("select groupid groups2users where userid='{$id}' limit 1;");
					//$groupid = -1;
					//if($groupres && !$groupres->EOF)
					//{
					//	$groupid = $groupres->fields['groupid'];
					//}
					session_set('logined_user_groupid',$groupid);
					setcookie('user_name',$_POST['user_name']);	
					$sqlstr = 'insert into user_login_log(userid,user_name,state,oper_time,ip_address,user_agent) ';
					$sqlstr .= "values({$id},'{$_POST['user_name']}','LOGIN',now(),'{$_SERVER['REMOTE_ADDR']}','{$_SERVER['HTTP_USER_AGENT']}')";
					$APP_ADODB->Execute($sqlstr);	

					header('Location: '._INDEX_URL.'?module=Index&action=index');
					exit();
				}
			}
			else
			{
				$login_error = '密码不正确！';
			}

		}
		else
		{
			$login_error = '帐号不存在！';
		}	
	}
	else
    {
		$login_error = '验证码不正确！';
    }	
}

  
if(!empty($_POST['user_name']))
{
	$smarty->assign('USER_NAME',$_POST['user_name']);
}
else
{
	$smarty->assign('USER_NAME','');
}


if($APP_CONFIG['have_agent'])
{
	$smarty->assign('HAVE_AGENT',true);
	if(!empty($_POST['agent_number']))
	{
		$smarty->assign('AGENT_NUMBER',$_POST['agent_number']);
	}
	elseif(!empty($_COOKIE['agent_number']))
	{
		$smarty->assign('AGENT_NUMBER',$_COOKIE['agent_number']);	
	}
	else
	{
		$smarty->assign('AGENT_NUMBER','');	
	}
}
else
{
	$smarty->assign('HAVE_AGENT',false);
}
$smarty->assign('VERSION',_APP_VERSION);
$smarty->assign('LOGIN_ERROR',$login_error);
if($CLOUD_TYPE)
{
	if(empty($_POST['cloudid'])&&!empty($_COOKIE['cloudid']))
	{
		$smarty->assign('CLOUDID',$_COOKIE['cloudid']);
	}
	elseif(!empty($_POST['cloudid']))
	{
		$smarty->assign('CLOUDID',$_POST['cloudid']);
	}
	else
	{
		$smarty->assign('CLOUDID','');
	}		
	$smarty->display('User/login_cloud.tpl');
}
else
{
	$smarty->display('User/login.tpl');
}

?>