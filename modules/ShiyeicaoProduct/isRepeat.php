<?php
    require_once(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php");
    $modclass= "AccountsModule";
    $mod = new $modclass();
    $mod->isRepeat($mod->_get("vin"),$mod->_get("recordid"));
?>