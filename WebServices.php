<?php
date_default_timezone_set('Asia/Chongqing');
define('_ROOT_DIR','/var/www/html/zsidms');
require(_ROOT_DIR.'/include/RequireBin.php');
if(empty($_GET['module']))
{
	die('Not Find module name!<br/>');
}

require_bin(_ROOT_DIR.'/webservices/'.$_GET['module'].'.php',_ROOT_DIR.'/bin');



?>