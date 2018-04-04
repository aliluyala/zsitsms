<?php
error_reporting(E_ALL);
ini_set( 'display_errors', 'On' );

$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/..');
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
//$SS_CONFIG = require(_ROOT_DIR.'/config/zswitch.conf.php');
$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];

if(empty($_GET['number']) )
{
	die("-1|Number can't empty!\r\n");
}
if(!preg_match('/^(\d+)$/',$_GET['number']))
{
	die("-1|Number format error!\r\n");
}

$number = $_GET['number'];

$con = mysql_connect($dbs,$user,$pw);
mysql_select_db($db,$con);
$sql = "select match_type ,phone_number from blocklist ;";
$result = mysql_query($sql,$con);
if($result && mysql_num_rows($result)>0 )
{
	while($row = mysql_fetch_assoc($result))
	{
		if($row['match_type'] == 'SUFFIX')
		{
			$len = strlen($row['phone_number']);
			if(substr($number,-$len) == $row['phone_number'])
			{
				die("0|true\r\n");
			}
		}
		elseif($row['match_type'] == 'PREFIX')
		{
			$len = strlen($row['phone_number']);
			if(substr($number,0,$len) == $row['phone_number'])
			{
				die("0|true\r\n");
				
			}			
		}
		elseif($row['match_type'] == 'FULL' && $number == $row['phone_number'])
		{
			die("0|true\r\n");			
		}
	}
}

die("0|false\r\n");

?>