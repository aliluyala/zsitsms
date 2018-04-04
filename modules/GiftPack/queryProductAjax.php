<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
require(_ROOT_DIR.'/modules/Product/ProductModule.class.php');
$mod = new ProductModule();
$res = $mod->getOneRecordset($_GET['productid'],null,null);
$info['code'] = $res[0]['code'];
$info['name'] = $res[0]['name'];
$info['standard'] = $res[0]['standard'];
return_ajax('success',$info);
?>