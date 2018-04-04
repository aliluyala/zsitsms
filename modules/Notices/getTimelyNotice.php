<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage1.tpl');
	die();
}

global $mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();
}
global $notices_read;
if(is_file(_ROOT_DIR."/modules/NoticesRead/NoticesReadModule.class.php"))
{
	require_once(_ROOT_DIR."/modules/NoticesRead/NoticesReadModule.class.php");
	$modclass= "NoticesReadModule";
	$notices_read = new $modclass();
}
$userids = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module);
$groupids = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module);
//查询60秒内的及时消息
$queryWhere = array();
$queryWhere[] = array('date_create','>',date('Y-m-d H:i:s',time()-60),' and ');
$queryWhere[] = array('user_create','!=',$CURRENT_USER_ID,' and ');
$queryWhere[] = array('tag','=','timely','');
$count = $mod->getListQueryRecordCount($queryWhere,Array(),'','',$userids,$groupids);
$result = $mod->getListQueryRecord($queryWhere,Array(),'','',$userids,$groupids,0,$count);
if(!$result){
	 return_ajax('fail','');
	 die();	
}
//查看是否已读
foreach($result as $v){
	$queryWhere1 = array();
    $queryWhere1[] = array('create_user','=',$CURRENT_USER_ID,' and ');
	$queryWhere1[] = array('notice_id','=',$v['id'],'');
	$read = $notices_read->getListQueryRecord($queryWhere1,Array(),'','',$userids,$groupids,0,1);
	if(empty($read)){
		$ids[] = $v['id'];
	}
}
if(empty($ids)){
	 return_ajax('fail','');
	 die();
}
 return_ajax('success',implode(',',$ids));
?>



