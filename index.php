<?php
$INSTALL = false;
//是云模式
$CLOUD_TYPE = false;
session_start();
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname']);

define('_INDEX_FILE',$finfo['basename']);
//定义URL常量
$spos = strpos($_SERVER['REQUEST_URI'],_INDEX_FILE);
if(!$spos)
{
	define('_ROOT_URL',$_SERVER['REQUEST_URI']);
}
else
{
	define('_ROOT_URL',substr($_SERVER['REQUEST_URI'],0,$spos));
}
define('_INDEX_URL',_ROOT_URL._INDEX_FILE);


if($INSTALL)
{
	header('Location: '._ROOT_URL.'install.php');
}


//编码格式utf-8
header('Content-Type: text/html; charset=utf-8');
//检查php版本
if(version_compare(PHP_VERSION,'5.2.0','<'))
{
	echo '本系统要求PHP版本大于等于5.2.0 ,当前服务器的PHP版本 :'.PHP_VERSION.'</br>';
	echo '你可点击这里<a href="'._ROOT_URL.'checkEnv.php">检测你的服务器环境</a></br>';
	die();
}
require(_ROOT_DIR.'/common/utils.php');


require(_CURRENT_MODULE_FILE);






?>
