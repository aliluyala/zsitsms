<?php
    $module = _MODULE;
    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
    $modclass= "{$module}Module";
    $mod = new $modclass();
    $mod->isRepeat($mod->_get("vin"),$mod->_get("recordid"));
?>