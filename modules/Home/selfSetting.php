<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$action = 'selfSetting';
$show_module_label = false;

if(!class_exists('HomeModule'))
{
	require('HomeModule.class.php');
}
$mod = new HomeModule();
$homeset = $mod->getListQueryRecord(Array(Array('userid','=',$CURRENT_USER_ID,'')),Array(),'','',NULL,NULL,0,1);

global $recordid;
if($homeset && count($homeset)>0)
{

	$recordid = $homeset[0]['id'];
}
else
{
	$recordid  = getNewModuleSeq($mod->baseTable);
	$homeset = $mod->getListQueryRecord(Array(Array('default_home','=','YES','')),Array(),'','',NULL,NULL,0,1);
	if($homeset && count($homeset)>0)
	{
		$homeset[0]['userid'] = $CURRENT_USER_ID;
		$homeset[0]['name'] = $CURRENT_USER_NAME.'的首页';
		$mod->insertOneRecordset($recordid,$homeset[0]);
	}
	else
	{
		$home = Array('userid'=>$CURRENT_USER_ID,
					  'name'=>$CURRENT_USER_NAME.'的首页',
					  'cols'=>3,
					  'rows'=>1,
					  'default_home'=>'NO');
		$mod->insertOneRecordset($recordid,$home);			  
	}
}
unset($mod->fields['userid']);
unset($mod->fields['default_home']);
if(isset($_GET['operation']) && $_GET['operation'] == 'edit')
{
	$return_module = 'Home';
	$return_action = 'selfSetting';
	require_once(_ROOT_DIR."/common/editView.php");
}
else
{
	$custom_buttons = Array();
	$custom_buttons[0] =  Array('label'=>'修改','title'=>'修改个设置','command'=>"zswitch_load_client_view('index.php?module=Home&action=selfSetting&operation=edit');");
	$detailview_buttons = createDetailviewButtons(Array());
	require_once(_ROOT_DIR."/common/detailView.php");
}
?>