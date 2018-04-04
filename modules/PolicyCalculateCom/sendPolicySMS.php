<?php
error_reporting(E_ALL);
ini_set( 'display_errors', 'On' );
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','发送短信失败！你的权限不能进行该项操作。');
	die();
}
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();
$pcconf = $mod->getPCConfig();
$set = $mod->getPCSetting();
if(empty($set))
{
	return_ajax('error','算价器设置错误,请在在“设置”>>“算价器设置”中重新设置！');
	die();
}

$info = array();
foreach($mod->fields as $key=>$v)
{
	$keycount = preg_match_all('/\[([0-9a-zA-Z_]+)\]/',$key,$out,PREG_SET_ORDER);
	if(substr($key,0,4)=='form' && $keycount>0)
	{
		if($keycount == 1)
		{
			if(!empty($_POST['form'][$out[0][1]]))
			{
				$info[$out[0][1]] = $_POST['form'][$out[0][1]];
			}
			else
			{
				$info[$out[0][1]] = '';
			}
		}
		else
		{
			if(!array_key_exists($out[0][1],$info))
			{
				$info[$out[0][1]] = array();
			}
			if(!empty($_POST['form'][$out[0][1]][$out[1][1]]))
			{
				$info[$out[0][1]][$out[1][1]] = $_POST['form'][$out[0][1]][$out[1][1]];
			}
			else
			{
				$info[$out[0][1]][$out[1][1]] = '';
			}			
		}
	}
}


if(!empty($pcconf['vehicle']['module']) && !empty($pcconf['vehicle']['fieldmap']['VIN_NO']))
{
	$accmodname =  $pcconf['vehicle']['module'];
	$accmodclass = $accmodname.'Module';
	@include(_ROOT_DIR."/modules/{$accmodname}/{$accmodclass}.class.php");
	if(class_exists($accmodclass))
	{
		$accmod = new $accmodclass();
		
		$wh = array(array($pcconf['vehicle']['fieldmap']['VIN_NO'],'=',$info['AUTO']['VIN_NO'],''));
		$rset = $accmod->getListQueryRecord($wh,array(),null,null,null,null,0,1);		
	}			
}

if(!empty($rset))
{

	$fm = $pcconf['vehicle']['fieldmap'];
	$r = $rset[0];

	if(array_key_exists('MOBILE',$fm) && array_key_exists($fm['MOBILE'],$r))
	{
		$info['AUTO']['MOBILE']      = $r[$fm['MOBILE']]      ;    
	}
	if(array_key_exists('TELPHONE',$fm) && array_key_exists($fm['TELPHONE'],$r))
	{
		$info['AUTO']['TELPHONE']      = $r[$fm['TELPHONE']]      ;    
	}
	if(array_key_exists('ADDRESS',$fm) && array_key_exists($fm['ADDRESS'],$r))
	{
		$info['AUTO']['ADDRESS']      = $r[$fm['ADDRESS']]      ;    
	}	
} 		


if(empty($info['AUTO']['MOBILE']))
{	
	return_ajax('error','发送短信失败！客户手机号码不正确，请检查客户档案。');
	die();
}

//短信组合算价信息
$caculate_data = '';
if(isset($_POST['form']['POLICY']['TOTAL_PREMIUM']) && intval($_POST['form']['POLICY']['TOTAL_PREMIUM']) >0){
	$caculate_data['user_name'] =$_SESSION[_SESSION_KEY]['logined_username'];
	$caculate_data['owner'] = $_POST['form']['AUTO']['OWNER'];
	$caculate_data['company'] = $_POST['form']['OTHER']['PREMIUM_RATE_TABLE'];
	$caculate_data['license_no'] = $_POST['form']['AUTO']['LICENSE_NO'];
	$caculate_data['MODEL'] = $_POST['form']['AUTO']['MODEL'];
	$caculate_data['premium_result'] = $_POST['form']['POLICY'];
	$caculate_data['premium_result']['MVTALCI_SELECT'] = isset($_POST['form']['MVTALCI_SELECT'])?'YES':'NO';
	$caculate_data = json_encode($caculate_data);
}

$info['USER'] = array();
$info['USER']['NAME'] = '';
$info['USER']['WORKNO'] = '';
$sql = "select * from users where id = {$CURRENT_USER_ID};";
$result = $APP_ADODB->Execute($sql);
$info['USER']['WORKNO'] = $result->fields['agent_workno'];
$info['USER']['NAME'] = $result->fields['name'];
$info['USER']['PHONE'] = $result->fields['phone_mobile'];
$cachepath = _ROOT_DIR.'/cache/sms_tpl';
if(!is_dir($cachepath))
{
	mkdir($cachepath,0777,true);
}
$tpl = $cachepath.'/policy_sms.tpl';
if(!is_file($tpl))
{
	file_put_contents($tpl,$set['sms_tpl']);
}
$tpl1 = $cachepath.'/short_sms_before.tpl';
if(!is_file($tpl1))
{
	file_put_contents($tpl1,$set['short_sms_before']);
}
$tpl2 = $cachepath.'/short_sms_after.tpl';
if(!is_file($tpl2))
{
	file_put_contents($tpl2,$set['short_sms_after']);
}

$smarty->assign('INFO',$info);
$content = $smarty->fetch($tpl);
$short_sms_before = $smarty->fetch($tpl1);
$short_sms_after = $smarty->fetch($tpl2);
return_ajax('success',array('callee'=>$info['AUTO']['MOBILE'],'content'=>$content,'caculate_data'=>$caculate_data,'short_sms_before'=>$short_sms_before,'short_sms_after'=>$short_sms_after));

?>