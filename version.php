<?php
//不要随意修改本文件内容，这可能导至系统不能正常运行

define('_APP_PRODUCT_NAME','ZSitsms');
define('_APP_VERSION' , '1.3.0');
define('_APP_DEPUTY_VERSION' , 'Call center 1.3.0');
define('_APP_DEVELOPER','TANG DAYONG');
define('_APP_COPYRIGHT_INFO','Copyright Chengdu Qidian Technology Co. Ltd.');

function checkAppVersion()
{
	global $APP_ADODB;
	$app_ver = session_get('APP_DB_VERSION');
	if($app_ver) 
	{
		if(version_compare($app_ver , _APP_VERSION, '!=')) 
		{		
			die('代码版本不匹配，请联系系统管理员！');
		}
	}
	else
	{
		$rs = $APP_ADODB->Execute("SELECT * FROM app_version LIMIT 1");
		if(!$rs) die('数据库错误，请联系系统管理员！');
		$dbversion = $rs->Fields('current_version');
		if(version_compare($dbversion, _APP_VERSION, '=')) 
		{
			session_set('APP_DB_VERSION',_APP_VERSION);
		}
		else
		{
			die('数据库版本不匹配，请联系系统管理员！');
		}
	}
	
}

?>