<?php
$mod_strings = Array();
$app_strings = Array();
//翻译字符串
function getTranslatedString($str)
{

	global $mod_strings,$app_strings;
	if(!isset($str) || empty($str)) return '';
	
	if(isset($mod_strings[$str]))	{
		
		return $mod_strings[$str];
	}
	elseif(isset($app_strings[$str]))
	{
		return $app_strings[$str];
	}
	return $str;
}

//加载系统字符串
function loadApplationStrings()
{
	global $app_strings;
	if(!isset($app_strings)) return false;
	if(is_file(_ROOT_DIR.'/common/lang/'._LANG_TYPE.'.lang.php'))
	{
		$app_strings = require(_ROOT_DIR.'/common/lang/'._LANG_TYPE.'.lang.php');
		return true;
	}
	return false;
}

//加载模块字符串
function loadModuleStrings()
{
	global $mod_strings;
	if(!isset($mod_strings)) return false;
	if(is_file(_ROOT_DIR.'/modules/'._MODULE.'/lang/'._LANG_TYPE.'.lang.php'))
	{
		$mod_strings = require(_ROOT_DIR.'/modules/'._MODULE.'/lang/'._LANG_TYPE.'.lang.php');
		return true;
	}
	return false;
}

//追加系统字符串
function appendApplationStrings($arr)
{
	global $app_strings;
	if(!isset($arr) || !is_array($arr)) return false;	
	$app_strings = array_merge($app_strings,$arr);
	return true;
}

//追加模块字符串
function appendModuleStrings($arr)
{
	global $mod_strings;
	if(!isset($arr) || !is_array($arr)) return false;	
	$mod_strings = array_merge($mod_strings,$arr);
	return true;	
}

//从指定模块翻译字符串
function getModuleTranslatedString($str,$module)
{
	if(is_file(_ROOT_DIR.'/modules/'.$module.'/lang/'._LANG_TYPE.'.lang.php'))
	{
		$mod_strings = require(_ROOT_DIR.'/modules/'.$module.'/lang/'._LANG_TYPE.'.lang.php');
		if(isset($mod_strings[$str]))
			return $mod_strings[$str];
	}
	return $str;
}
?>