<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
$classname = "{$module}Module";
$mod = new $classname();

 if(array_key_exists('vin',$_GET))
 {
 	$vin = $_GET['vin'];
 }elseif (array_key_exists('vin_no',$_GET)){
     $vin = $_GET['vin_no'];
 }
 if(array_key_exists('license_no',$_GET))
 {
 	$license_no = $_GET['license_no'];
 }


 $rate_table = '';
 if(array_key_exists('rate_table',$_GET))
 {
 	$rate_table = $_GET['rate_table'];
 }


 $pc = $mod->createPCObj($rate_table);
 if(!$pc)
 {
 	$smarty->assign('ERROR_MESSAGE','算价器设置错误,请在在“设置”>>“算价器设置”中重新设置！');
 	$smarty->display('ErrorMessage.tpl');
 	die();
 }

$data['VIN_NO']=$vin;
$data['LICENSE_NO']=$license_no;

// PS：新太平洋的登录流程(新太平洋的登录需要手动输入验证码)
$list = $pc->checkcode($data);

if($pc->needVerification()){
    $code = $pc->getVerifyCode();
    $smarty->assign('verifyCode', $code);
    $smarty->assign('vin_no', $vin);
    $smarty->assign('model', '');
    $smarty->assign('page', 1);
    $smarty->assign('action', $action);
    $smarty->assign('rate_table', $rate_table);
    $smarty->display("{$module}/verifyCode.tpl");
    die();
}


$check = json_decode($list,true);

 if(array_key_exists('checkcode',$check) || $check['checkno'] != '')
 {
 	header("Content-type: image/png");
 	$image = base64_decode(str_replace(" ","+",$check['checkcode']));
 	file_put_contents("./cache/check.png",$image);
 	$check['checkcode']=str_replace("／", "/", $check['checkcode']);
 	$smarty->assign('CHECK',$check);
 	$data['rate_table']=$rate_table;
 	$smarty->assign('DATA',$data);
 	$smarty->display("{$module}/check.tpl");
 	die();
 

}
else
{
	$smarty->assign('check',$check['url']);
 	$smarty->display("{$module}/check.tpl");
 	die();
}

?>