<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$smarty->assign('MODULE',$module);
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();
}
$accountid = -1;
if(!empty($_GET['recordid'])) $accountid = $_GET['recordid'];

$focus;
if(is_file(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php"))
{
    require_once(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php");
    $modclass= "AccountsModule";
    $focus = new $modclass();
}


global $details_datas;
if(!isset($details_datas))
{

	$result = $focus->getOneRecordset($accountid,
									$CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
									$CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
									);

}

//IDE配置
$IDE_CONFIG = require(_ROOT_DIR.'/config/IDEService.conf.php');
$url = $IDE_CONFIG['url'].'?module=IDEServiceA&user='.$IDE_CONFIG['user'].'&password='.$IDE_CONFIG['password'].'&method=queryPolicy';

$params = "vin_no=".$result[0]['vin'];
//根据车牌号查询

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);  
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; Trident/7.0; rv:11.0) like Gecko'); 
curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*'));
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
curl_setopt($curl, CURLOPT_POST, 1); 
curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
curl_setopt($curl, CURLOPT_HEADER, 0); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 	    
curl_setopt($curl, CURLINFO_HEADER_OUT, true);
$curlstr  = curl_exec($curl);


//结果是json对象		
$curlstr=json_decode($curlstr,true);
if(isset($curlstr['data']) && !empty($curlstr['data']['policy_no'])){
	$curlstr['data']['policy_no'] =substr_replace($curlstr['data']['policy_no'],'********',6,8);
}

$smarty->assign('CURLSTR',$curlstr);
$smarty->display("Insurance/lastyearView.tpl");
?>