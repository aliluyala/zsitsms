<?php
global $APP_CONFIG;
require(_ROOT_DIR.'/include/adodb/adodb.inc.php');
if(!isset($APP_CONFIG['DBType'])) 
{
	$db_driver = 'mysql';
}
else
{
	$db_driver = $APP_CONFIG['DBType'];
}

$APP_ADODB = ADONewConnection($db_driver);
if(_DEBUG)
{
//	$APP_ADODB->debug = true;
}
else
{
	$APP_ADODB->debug = false;
}
if(!session_get('APP_CURRENT_DB_SERVER'))
	session_set('APP_CURRENT_DB_SERVER','master');
$dbs = session_get('APP_CURRENT_DB_SERVER');
if(!isset($APP_CONFIG['DBServers'])||
	!isset($APP_CONFIG['DBServers'][$dbs])) 
	die('没有配置数据库服务器信息，请在config.php中配置。</br>');
	
$dbc = 0 ;
$db_connected = false;	
while($dbc < 2)
{	
	$db_conf = $APP_CONFIG['DBServers'][$dbs];
	if(!isset($db_conf['DBHost'])) $db_conf['DBHost'] = '127.0.0.1';
	if(!isset($db_conf['DBUserName'])) $db_conf['DBUserName'] = 'root';
	if(!isset($db_conf['DBPassword'])) $db_conf['DBPassword'] = '';
	if(!isset($db_conf['Database'])) $db_conf['Database'] = 'zswitch';
	$dbc += 1;
	if($APP_ADODB->Connect($db_conf['DBHost'],$db_conf['DBUserName'],$db_conf['DBPassword'],$db_conf['Database']))
	{	
		session_set('APP_CURRENT_DB_SERVER',$dbs);
		$db_connected = true;
		//如果升级使用原有数据库出现乱码可以注释此条
		$APP_ADODB->Execute("SET NAMES utf8;");
		break;
	}
	else
	{
		$dbs =($dbs == 'master')?'slave':'master';
		if(!isset($APP_CONFIG['DBServers'][$dbs])) break;		
	}
}

if(!$db_connected) die('联接系统数据库失败，请与系统管理员联系！</br>');

//创建一个数据库联接
function createDBConnect($host,$user,$password,$database,$driver='mysql')
{
	$adodb = ADONewConnection($driver);
	if(!$adodb->Connect($host,$user,$password,$database)) return false;
	$adodb->Execute("SET NAMES utf8;");
	return $adodb;
}
//获取新seq值
function getNewModuleSeq($module)
{
	global $APP_ADODB;
	$table = strtolower($module)."_seq";
	$result = $APP_ADODB->Execute("LOCK TABLES {$table}  WRITE;");
	if(!$result) return false;
	$result =  $APP_ADODB->Execute("select id from  {$table} limit 1;");
	if(!$result || $result->EOF)
	{
		$APP_ADODB->Execute("UNLOCK TABLES;");
		return false;
	}
	$seq = $result->fields['id'];
	$APP_ADODB->Execute("update {$table} set id=id+1;");
	$APP_ADODB->Execute("UNLOCK TABLES;");
	return $seq;
}

?>