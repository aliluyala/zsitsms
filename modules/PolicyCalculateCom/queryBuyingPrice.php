<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();
$model = '';
if(array_key_exists('model',$_GET))
{
	$model = $_GET['model'];
}
$rate_table = '';
if(array_key_exists('rate_table',$_GET))
{
	$rate_table = $_GET['rate_table'];
}
$vin = '';
if(array_key_exists('vin_no',$_GET))
{
	$vin = $_GET['vin_no'];
}
$modelcode = '';
if(array_key_exists('modelcode',$_GET))
{
	$modelcode = $_GET['modelcode'];
}
$page = 1;
if(array_key_exists('page',$_POST))
{
    $page = $_POST['page'];
}


global $qidianSdk;
if(is_file(_ROOT_DIR."/webservices/qiDianapi.class.php"))
{
	require_once(_ROOT_DIR."/webservices/qiDianapi.class.php");
	$qidianmod= "qiDianapi";
	$qidianSdk = new $qidianmod();
}
else
{
	$smarty->assign('ERROR_MESSAGE','系统发生错误');
	$smarty->display('ErrorMessage1.tpl');
	die();
}

/**平安传递密码专用开始**/
 if(isset($_GET['checkcode']) && strpos($_GET['rate_table'],'PAIC')){
	 $file = _ROOT_DIR.'/cache/pinganPwd.txt';
	 $encryptPassword = file_get_contents($file);
	 $where['verify']['encryptPassword'] = $encryptPassword;
 }
/**平安传递专用结束**/

if(isset($_GET['checkcode']) && $_GET['checkcode'] != "")
{
	$where['verify']['login'] = $_GET['checkcode'];
}

$where['insurance'] = $rate_table;
$where['model'] = $model;
$where['vin'] = $vin;
$where['modelCode'] = $modelcode;
$where['page'] = $page;
$where['pageSize'] = 30;

$buying_result = $qidianSdk->getCliectSdk('VehicleInfo',$where,'POST');


if(!$buying_result)
{
	$errmessage = $qidianSdk->getErrorMessage();
	$smarty->assign('ERROR_MESSAGE',$errmessage);
	$smarty->display('ErrorMessage1.tpl');
	die();
}
/**平安加密特用开始**/
if(isset($buying_result['data']['verify']['sm2PubX']) && isset($buying_result['data']['verify']['sm2PubY'])){ 
    $whereAccount['insurance'] = $_GET['rate_table'];
    $accountsResult = $qidianSdk->getCliectSdk('Insurance.detail',$whereAccount,'GET');
	$password = false;
	if(count($accountsResult)>0){
		foreach($accountsResult['data']['accounts'] as $k => $v){
		     if($v['status'] =='正常' && !empty($v['password'])){
				 $password = $v['password'];
				 break;
			 }
		}
	}
	if($password == false){
		$errmessage ='需要添加平安账号密码才能使用';
		$smarty->assign('ERROR_MESSAGE',$errmessage);
		$smarty->display('ErrorMessage1.tpl');
		die();
	}
	
	$mod->encryptPwd($buying_result['data']['verify']['sm2PubX'],$buying_result['data']['verify']['sm2PubY'],$password);
}
/**平安加密特用结束**/

if($buying_result['code'] == 4 && isset($buying_result['data']['verify']['login']) && $buying_result['data']['verify']['login'] != "")
{
    $smarty->assign('verifyCode', $buying_result['data']['verify']['login']);
    $smarty->assign('vin_no', $vin);
    $smarty->assign('model', $model);
    $smarty->assign('page', $page);
    $smarty->assign('action', $action);
    $smarty->assign('rate_table', $rate_table);
    $smarty->display("{$module}/verifyCode.tpl");
    die();
}

if(!array_key_exists('oper',$_GET) || $_GET['oper'] != 'queryData' && $buying_result['code'] == 0 && $buying_result['describe'] == 'success')
{
	$smarty->assign('DATAS',json_encode($buying_result['data']));
	$smarty->assign('VIN_NO',$vin);
	$smarty->assign('MODULE',$module);
	$smarty->assign('VEHICLE_MODEL',$model);
	$smarty->assign('RATE_TABLE',$rate_table);
	$smarty->display("{$module}/queryBuyingPrice.tpl");
}




?>