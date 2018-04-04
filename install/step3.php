<?php
//ini_set('display_errors',0);
$srv_info = 'mysql';
$host = '127.0.0.1';
if(isset($_POST['host'])) $host = $_POST['host'];
$port = '3306';
if(isset($_POST['post'])) $port = $_POST['port'];
$user = 'root';
if(isset($_POST['user'])) $user = $_POST['user'];
$password = '';
if(isset($_POST['password'])) $password = $_POST['password'];
$database = 'zsitsms12';
if(isset($_POST['database'])) $database = $_POST['database'];
$drop_db = false;
if(isset($_POST['delete_database']) && $_POST['delete_database'] == 'YES' ) $drop_db = true;

$error_info = "";
$error = false;
$conn = mysql_connect("{$host}:{$port}",$user,$password);

if($conn)
{
	mysql_query('set names utf8;',$conn);
	$srv_info = 'mysql '.mysql_get_server_info($conn);
	$db_exits = mysql_select_db($database,$conn);
	if($db_exits && $drop_db)
	{
		$sql = "DROP DATABASE {$database} ; ";
		if(!mysql_query($sql,$conn))
		{
			$error_info = "删除数据库失败!";
			$error = true;
		}
		else
		{
			$db_exits = false;
		}		
	}
	
	if(!$error && !$db_exits)
	{
		$sql = "CREATE DATABASE {$database} CHARACTER SET = utf8; ";
		if(!mysql_query($sql,$conn))
		{
			$error_info = "创建数据库失败！";
			$error = true;
		}
		mysql_select_db($database,$conn);
	}	

	if(!$error)
	{
		$conffile = file_get_contents(_ROOT_DIR."/config/config.php");
		$conffile = preg_replace('/\'DBHost\'\s*=>.*/',"'DBHost' => '{$host}',",$conffile);
		$conffile = preg_replace('/\'DBUserName\'\s*=>.*/',"'DBUserName' => '{$user}',\r\n",$conffile);
		$conffile = preg_replace('/\'DBPort\'\s*=>.*/',"'DBPort' => '{$port}',\r\n",$conffile);
		$conffile = preg_replace('/\'DBPassword\'\s*=>.*/',"'DBPassword' => '{$password}',\r\n",$conffile);
		$conffile = preg_replace('/\'Database\'\s*=>.*/',"'Database' => '{$database}',\r\n",$conffile);
		file_put_contents(_ROOT_DIR."/config/config.php",$conffile );
		require(_ROOT_DIR."/install/installDB.php");
	}	
}
$smarty = new ZS_Smarty();
$smarty->assign('PRODUCT',_APP_PRODUCT_NAME.' '._APP_VERSION);
if($error)
{
	$check_pass = true;
	$smarty->assign('DB_SERVER_INFO',$srv_info);
	$smarty->assign('DATABASE_EXIST',$db_exits);
	$smarty->assign('DB_SERVER_INFO',$srv_info);	
	$smarty->assign('DB_HOST',$host);	
	$smarty->assign('DB_PORT',$port);	
	$smarty->assign('DB_USER',$user);	
	$smarty->assign('DB_PASSWORD',$password);	
	$smarty->assign('DB_DATABASE',$database);	
	$smarty->assign('CHECK_PASS',$check_pass);
	$smarty->assign('ERROR_INFO',$error_info);	
	$smarty->display('Install/step2.tpl');
	die();
}	
$zones = timezone_identifiers_list();
$timezones = array();
foreach($zones as $zone)
{
	$timezones[$zone] = $zone;
}
$default_timezone = date_default_timezone_get();

$smarty->assign('DEFAULT_TIMEZONE',$default_timezone);
$smarty->assign('TIMEZONES',$timezones);
$smarty->display('Install/step3.tpl');
		
?>
