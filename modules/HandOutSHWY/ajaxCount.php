<?
    $module = _MODULE;
    $action = _ACTION;
    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
    $modclass= "{$module}Module";
    $mod = new $modclass();
    return_ajax('count',$mod->getHandoutCount());