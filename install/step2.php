<?php
ini_set('display_errors',0);
$srv_info = 'mysql';
$host = '127.0.0.1';
if(isset($_POST['host'])) $host = $_POST['host'];
$port = '3306';
if(isset($_POST['post'])) $port = $_POST['port'];
$user = 'root';
if(isset($_POST['user'])) $user = $_POST['user'];
$password = '';
if(isset($_POST['password'])) $password = $_POST['password'];

$database = 'zsitsms'.str_replace('.','',_APP_VERSION);

if(isset($_POST['database'])) $database = $_POST['database'];

$check_pass = false;
$conn = mysql_connect("{$host}:{$port}",$user,$password);
$db_exits = false;
if($conn)
{	mysql_query('set names utf8;',$conn);
	if(mysql_select_db($database,$conn)) $db_exits = true;
	$srv_info = 'mysql '.mysql_get_server_info($conn);
	$check_pass = true;
}

$smarty = new ZS_Smarty();
$smarty->assign('PRODUCT',_APP_PRODUCT_NAME.' '._APP_VERSION);
$smarty->assign('DATABASE_EXIST',$db_exits);
$smarty->assign('ERROR_INFO','');
$smarty->assign('DB_SERVER_INFO',$srv_info);	
$smarty->assign('DB_HOST',$host);	
$smarty->assign('DB_PORT',$port);	
$smarty->assign('DB_USER',$user);	
$smarty->assign('DB_PASSWORD',$password);	
$smarty->assign('DB_DATABASE',$database);	
$smarty->assign('CHECK_PASS',$check_pass);	
$smarty->display('Install/step2.tpl');
		
?>
