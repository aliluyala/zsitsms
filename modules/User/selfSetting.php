<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$show_module_label = false;
$recordid = $CURRENT_USER_ID;
if(isset($_GET['operation']) && $_GET['operation'] == 'edit')
{
	$return_module = 'User';
	$return_action = 'selfSetting';
	if(!class_exists('UserModule'))
	{
		require(_ROOT_DIR.'/modules/User/UserModule.class.php');
	}
	$mod = new UserModule();
	$mod->editFields = Array('name','birthday',
							 'department','post','title','phone_home','phone_mobile',
							 'phone_work','phone_other','phone_fax','email','qq_number',
							 'agent_number','agent_login','agent_popup',
							 'agent_status','address_country','address_state','address_city',
							 'address_street','address_postalcode','imagename');
	
	require_once(_ROOT_DIR."/common/editView.php");
}
else
{
	$custom_buttons = Array();
	$custom_buttons[0] =  Array('label'=>'修改','title'=>'修改个设置','command'=>"zswitch_load_client_view('index.php?module=User&action=selfSetting&operation=edit');");
	$detailview_buttons = createDetailviewButtons(Array());
	require_once(_ROOT_DIR."/common/detailView.php");
}
?>