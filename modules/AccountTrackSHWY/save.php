<?php
global $APP_ADODB;
//dailyanalysis
if(is_file(_ROOT_DIR."/modules/DailyAnalysisSHWY/DailyAnalysisSHWYModule.class.php"))
{
    require_once(_ROOT_DIR."/modules/DailyAnalysisSHWY/DailyAnalysisSHWYModule.class.php");
    $daily      = new DailyAnalysisSHWYModule();
}
$daily->setDailyAnalysis($_POST['accountid'],$_POST['status']);
//accounts
if(is_file(_ROOT_DIR."/modules/AccountsSHWY/AccountsSHWYModule.class.php"))
{
    require_once(_ROOT_DIR."/modules/AccountsSHWY/AccountsSHWYModule.class.php");
    $focus      = new AccountsSHWYModule();
}
$account_datas = Array();
foreach($focus->editFields as $field)
{
    if(isset($_POST[$field]) && $field != "remark") $account_datas[$field] = $_POST[$field];
}
$focus->updateOneRecordset($_POST['accountid'],
                           $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission("AccountsSHWY"),
                           $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission("AccountsSHWY"),
                           $account_datas);
require_once(_ROOT_DIR.'/common/save.php');
?>



