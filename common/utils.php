<?php

//加载配置
$APP_CONFIG = require_once(_ROOT_DIR.'/config/config.php');
//设置调试
if(isset($APP_CONFIG['debug'])) 
{
	define('_DEBUG',$APP_CONFIG['debug']);
}
else
{
	define('_DEBUG',1);
} 

//设置时区
if(isset($APP_CONFIG['timezone'])) date_default_timezone_set($APP_CONFIG['timezone']);
//初始化语言配置
if(isset($APP_CONFIG['autoLang']))
{	
	define('_AUTO_LANG',$APP_CONFIG['autoLang']);
}
else	
{
	define('_AUTO_LANG',0);
}
if(_AUTO_LANG == 0)
{
	if(isset($APP_CONFIG['langType'])) define('_LANG_TYPE',strtolower($APP_CONFIG['langType']));
}
if(!defined('_LANG_TYPE'))
{
	define('_LANG_TYPE',strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']));
}		
//模块路由
if(!isset($_GET['module']))
{
	define('_MODULE','Index');
}
else
{
	define('_MODULE',$_GET['module']);
}

if(!is_dir(_ROOT_DIR.'/modules/'._MODULE))
{
	die('无效模块名：'._MODULE);
}

if(!isset($_GET['action']))
{
	define('_ACTION','index');
}
else
{
	define('_ACTION',$_GET['action']);
}

define('_CURRENT_MODULE_FILE', _ROOT_DIR.'/modules/'._MODULE.'/'._ACTION.'.php');
if(!is_file(_CURRENT_MODULE_FILE))
{
	die('无效方法：'._ACTION);
}
require_once(_ROOT_DIR.'/version.php');	
require_once(_ROOT_DIR.'/common/session.php');
require_once(_ROOT_DIR.'/common/database.php');
//checkAppVersion();
require_once(_ROOT_DIR.'/common/certificate.php');

require_once(_ROOT_DIR.'/common/cloudmgr.php');

//缓存目录
if(empty($CUURENT_CLOUDID))
{
	define('_CACHE_DIR',_ROOT_DIR.'/cache/');
}		
else
{
	define('_CACHE_DIR',_ROOT_DIR.'/cache/'.$CUURENT_CLOUDID.'/');
}

//检查许可证
/*
if(!checkCertificate() && _MODULE != 'CertManger')
{
	header('Location: '._INDEX_URL.'?module=CertManager&action=index');
	die('系统许可证无效，或者许可证已经过期，请联系<a href="http://www.zswitch800.com">成都启点科技有限公司</a>。<br/>');
}
*/
//检查用户登录

if((!session_get('authentication_logined') || !session_get('logined_userid') || !session_get('logined_username')) &&
	 _MODULE != 'User' && (_ACTION != 'login'  || _ACTION != 'logout' || _ACTION != 'verifyCode'))
{
	header('Location: '._INDEX_URL.'?module=User&action=login');
	die('你还没有登录。');
}
//当前用户信息变量
$CURRENT_USER_ID = session_get('logined_userid');
$CURRENT_USER_NAME = session_get('logined_username');
$CURRENT_NAME = session_get('logined_name');
$CURRENT_IS_ADMIN = session_get('logined_is_admin');	
$CURRENT_USER_GROUPID = session_get('logined_user_groupid');	
	
	
require_once(_ROOT_DIR.'/common/language.php');
loadApplationStrings();
loadModuleStrings();
require_once(_ROOT_DIR.'/common/permission.php');
require_once(_ROOT_DIR.'/Smarty_setup.php');
require_once(_ROOT_DIR.'/common/titlebar.php');
require_once(_ROOT_DIR.'/common/listViewUtil.php');
require_once(_ROOT_DIR.'/common/detailViewUtil.php');
require_once(_ROOT_DIR.'/common/fieldUI.php');
require_once(_ROOT_DIR.'/common/editViewUtil.php');
require_once(_ROOT_DIR.'/common/ajax.php');
require_once(_ROOT_DIR.'/common/BaseModule.class.php');
?>