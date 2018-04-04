<?php
    require_once(_ROOT_DIR."/modules/Accounts_Financial/Accounts_FinancialModule.class.php");
    $modclass= "Accounts_FinancialModule";
    $mod = new $modclass();
    $mod->isRepeat($mod->_get("vin"),$mod->_get("recordid"));
?>