<?php
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
require("Smarty_setup.php");
require("version.php");

header('Content-Type: text/html; charset=utf-8');
$indexFile = strtolower(file_get_contents(_ROOT_DIR."/index.php"));

if(!preg_match('/install\s*=\s*true/',$indexFile,$out))
{
	header('Location: '._ROOT_URL);	
}

if(!empty($_GET['step']))
{
	require(_ROOT_DIR."/install/{$_GET['step']}.php");
}
else
{
	header('Location: '._INDEX_URL."?step=step1");
}







?>