<?php
require_once(_ROOT_DIR."/modules/GroupManager/GroupManagerModule.class.php");
$mod =  new GroupManagerModule();
$check = $mod->checkNameAvailable($_GET['recordid'],$_GET['value']);
if($check)
{
	echo return_ajax('success','工作组名称有效');
}
else
{
	echo return_ajax('failure','工作组名称重名');
}

?>