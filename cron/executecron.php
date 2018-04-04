<?php
//if($_SERVER['REMOTE_ADDR'] != '127.0.0.1') die('Access forbidden!');
date_default_timezone_set('Asia/Chongqing');
$rpath = dirname(__FILE__);
$rpath = str_replace('/cron','',$rpath);
define('_ROOT_DIR',$rpath);
require(_ROOT_DIR.'/include/RequireBin.php');
if(empty($_GET['module']))
{
	die('Not Find module name!<br/>');
}
$modfile = str_replace('.','/',$_GET['module']).'.php';
require_bin(_ROOT_DIR.'/cron/'.$modfile,_ROOT_DIR.'/bin');

?>