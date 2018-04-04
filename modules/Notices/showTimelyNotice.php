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
$queryWhere=array();
$queryWhere[]=array('id','in','('.$_GET['ids'].')','');
$ids = explode(',',$_GET['ids']);
$result = $mod->getListQueryRecord($queryWhere,array(),'','',$userids,$groupids,0,count($ids));
global $keyName;
if(!isset($keyName)) $keyName = 'id';
if($result)
{		
    $listfields=array('title','contant','user_create');	
	if(!$CURRENT_IS_ADMIN) $result = validationFieldsShowPermission($module,$result);
	$list_data = formatListDatas($result,$module,$keyName,$mod->fields,$listfields,$mod->enteryField,$mod->picklist,$mod->associateTo);
}
//添加查看记录 
foreach($ids as $v){
	$id  = getNewModuleSeq('notices_read');
	$data['notice_id'] = $v;
	$notices_read->insertOneRecordset($id,$data);
}
$smarty->assign('LIST_DATA',$list_data);
$smarty->display('Notices/showTimelyNotice.tpl');
?>



