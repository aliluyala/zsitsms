<?php
//ini_set('display_errors',0);
if(isset($_POST['user']))
{	
	$conf = require(_ROOT_DIR."/config/config.php");	
	$dbhost = $conf['DBServers']['master']['DBHost'];
	$dbport = $conf['DBServers']['master']['DBPort'];
	$dbuser = $conf['DBServers']['master']['DBUserName'];
	$dbpassword = $conf['DBServers']['master']['DBPassword'];
	$database = $conf['DBServers']['master']['Database'];
	
	$conn = mysql_connect("{$dbhost}:{$dbport}",$dbuser,$dbpassword);
	mysql_query('set names utf8;',$conn);
	mysql_select_db($database,$conn);
	$user = $_POST['user'];
	$password = $_POST['password'];
	mysql_query("insert into users(id,user_name,name,is_admin,user_password,date_created) values(1,'{$user}','{$user}','YES',md5('{$password}'),now());");	
	$zone = $_POST['timezone'];
	$conffile = file_get_contents(_ROOT_DIR."/config/config.php");
	$conffile = preg_replace('/\'timezone\'\s*=>.*/',"'timezone' => '{$zone}',",$conffile);
	file_put_contents(_ROOT_DIR."/config/config.php",$conffile );
	
	$indexfile = file_get_contents(_ROOT_DIR."/index.php");
	$indexfile = preg_replace('/\$INSTALL\s*=.*/','$INSTALL = false;',$indexfile);
	file_put_contents(_ROOT_DIR."/index.php",$indexfile );
}
$smarty = new ZS_Smarty();
$smarty->assign('SYS_URL','http://'.$_SERVER['SERVER_ADDR']._ROOT_URL);
$smarty->assign('PRODUCT',_APP_PRODUCT_NAME.' '._APP_VERSION);
$smarty->display('Install/step4.tpl');
		
?>
