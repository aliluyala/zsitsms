<?php
global $APP_ADODB;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	die('你没有权限播放录音！');
}

$zswitchconf = require_once(_ROOT_DIR.'/config/zswitch.conf.php');
$smarty = new ZS_Smarty();
$id = $_GET['recordid'];
$file_name = '录音文件不存在';
$recordid = '';
$have_file = false;

$sql = "select * from zswitch_call_details where id={$id};";
$result = $APP_ADODB->Execute($sql);
if(!$result->EOF && $result->fields['channel_answered_datetime'] != '0000-00-00 00:00:00')
{
	$filepath = $zswitchconf['recordfile_path'].Date('Y-m-d',strtotime($result->fields['channel_answered_datetime'])).'/';
	$filepath .= $result->fields['caller_id_number'].'/';
	
	$file_name1 = $result->fields['caller_id_number'].'_'.$result->fields['callee_id_number'].'_'.Date('YmdHis',strtotime($result->fields['channel_answered_datetime'])).'.wav';
	
	$file = $result->fields['uuid'].'_'.$file_name1;
	$filepath .=$file;
	if(file_exists(_ROOT_DIR.'/'.$filepath))
	{
		$have_file = true;
		$file_name = $file_name1;
		$recordid = $result->fields['id'];
	}
}


//确定客户端浏览器类型
$brower_is_msie = false;
if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE') || strstr($_SERVER['HTTP_USER_AGENT'],'Trident'))
{
	$brower_is_msie = true;
}

$smarty->assign('BROWER_IS_MSIE',$brower_is_msie);
$smarty->assign("HAVE_FILE",$have_file);
$smarty->assign('DOWNLOAD_FILE',_ROOT_URL.$filepath);
$smarty->assign('RECORDID',$recordid);
$smarty->assign('FILE_NAME',$file_name);
$smarty->display('ZSwitchManager/PlayRecordView.tpl');
?>