<?php
require_once(_ROOT_DIR."/modules/User/UserModule.class.php");
$mod =  new UserModule();
$check = $mod->checkNameAvailable($_GET['recordid'],$_GET['value']);
if($check)
{
	echo return_ajax('success','用户名有效');
}
else
{
	echo return_ajax('failure','用户已经存在！');
}

?>