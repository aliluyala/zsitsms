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
$account; 
if(is_file(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php"))
{
	require_once(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php");
	$modclass= "AccountsModule";
	$account = new $modclass(); 
}

$finfo = pathinfo(__FILE__); 
//定义目录常量
// define('_ROOT_DIR',$finfo['dirname'].'/..');
$conf = require(_ROOT_DIR.'/config/JiangSu_insurance.conf.php');
$loginUrl=$conf['baseUrl'] .'/sinoiais/';
$loginInUrl = $conf['baseUrl'] .'/sinoiais/checklogin/checkLoginInfo.do';
$imageCodeUrl = $conf['baseUrl'] .'/sinoiais/pages/login/RandomNumUtil.jsp';
$searchUrl = $conf['baseUrl'] .'/sinoiais/showall/query.do';

$isLogin = 0;
$rs=$mod->getSearchInfo($searchUrl,array('vin'=>'LGBG2NE27BY003670','dimensionSelect'=>'02'));
if($rs!=-1){
	$isLogin=1;
}else{
	$mod->login($loginUrl);
}
$image_name='';
if(!isset($_GET['method'])){
	if($isLogin ==0){
		$image_name = $mod->getImage($imageCodeUrl,array(time()));
	}
}
if(isset($_GET['method']) && $_GET['method'] == 'imageReload' ){
	$image_name = $mod->getImage($imageCodeUrl,array(time())); 
	echo $image_name;
	exit;
	
}

if(isset($_GET['method']) && $_GET['method'] == 'search' ){
	if(isset($_POST['image']) && $isLogin ==0){
		$loginData['sysUserCode'] = $conf['userCode'];
		$loginData['sysPassWord'] = $conf['passWord'];
		$loginData['random'] = $_POST['image'];
		$loginMsg = $mod->loginin($loginInUrl,$loginData);
		if($loginMsg != 1){
			return_ajax('error',$loginMsg);
			exit;
		}
	} 
	$info = $mod->getSearchInfo($searchUrl,$_POST);
	return_ajax('success',$info); 
	exit;
}
$userids = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module);
$groupids = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module);
$id='';
if($_GET['id']){
	$id = $_GET['id'];
}
$accountInfo = $account->getOneRecordset($id,$userids,$groupids); //print_R($accountInfo);
$smarty->assign('isLogin',$isLogin);
$smarty->assign('method','search');
$smarty->assign('imageSrc',$image_name);
$smarty->assign('accountInfo',$accountInfo[0]); 

$smarty->display("Insurance/jiangsuInsurance.tpl");
?>


