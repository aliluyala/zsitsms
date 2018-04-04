<?php
global $APP_ADODB;
//dailyanalysis
if(is_file(_ROOT_DIR."/modules/DailyAnalysis/DailyAnalysisModule.class.php"))
{
    require_once(_ROOT_DIR."/modules/DailyAnalysis/DailyAnalysisModule.class.php");
    $daily      = new DailyAnalysisModule();
}
$daily->setDailyAnalysis($_POST['accountid'],$_POST['status']);
//accounts
if(is_file(_ROOT_DIR."/modules/Accounts_Financial/Accounts_FinancialModule.class.php"))
{
    require_once(_ROOT_DIR."/modules/Accounts_Financial/Accounts_FinancialModule.class.php");
    $focus      = new Accounts_FinancialModule();
}
$account_datas = Array();
foreach($focus->editFields as $field)
{
    if(isset($_POST[$field]) && $field != "remark") $account_datas[$field] = $_POST[$field];
}
$focus->updateOneRecordset($_POST['accountid'],
                           $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission("Accounts_Financial"),
                           $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission("Accounts_Financial"),
                           $account_datas);
require_once(_ROOT_DIR.'/common/save.php');
?>



