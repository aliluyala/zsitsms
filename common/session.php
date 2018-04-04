<?php
if(!defined('_APP_PRODUCT_NAME') || !defined('_APP_VERSION'))
{
	die('系统错误！');
}	
define('_SESSION_KEY',_APP_PRODUCT_NAME._APP_VERSION);
if(!isset($_SESSION[_SESSION_KEY])) $_SESSION[_SESSION_KEY] = Array();
function session_get($name)
{
	if(!isset($_SESSION[_SESSION_KEY]) || !isset($_SESSION[_SESSION_KEY][$name])) return false;
	return $_SESSION[_SESSION_KEY][$name];
}


function session_set($name,$val)
{
	if(!isset($_SESSION[_SESSION_KEY]))  $_SESSION[_SESSION_KEY] = Array();
	$_SESSION[_SESSION_KEY][$name] = $val;
}

function session_uset($name)
{
	if(isset($_SESSION[_SESSION_KEY][$name])) unset($_SESSION[_SESSION_KEY][$name]);
}

?>