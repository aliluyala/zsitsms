<?php
global $APP_ADODB;

/*$return_module = "Accounts";
$return_action = "detailView";
$return_recordid = $_POST['autoid'];*/

//accounts
if(is_file(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php"))
{
    require_once(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php");
    $focus      = new AccountsModule();
}
$account_datas = Array();
foreach($focus->editFields as $field)
{
    if(isset($_POST[$field]) && $field != "remark") $account_datas[$field] = $_POST[$field];
}
$focus->updateOneRecordset($_POST['autoid'],
                           $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission("Accounts"),
                           $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission("Accounts"),
                           $account_datas);



if(is_array($_POST['buy_types'])){
    $_POST['buy_types'] = implode(",", $_POST['buy_types']);
}
//$_POST['autoid'] = $_POST['accountid'];
require_once(_ROOT_DIR.'/common/save.php');
?>



