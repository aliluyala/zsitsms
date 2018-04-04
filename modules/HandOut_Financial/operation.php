<?
    global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
    $smarty = new ZS_Smarty();
    if(!isset($module)) $module = _MODULE;
    if(!isset($action)) $action = _ACTION;
    $module_label =  getTranslatedString($module);
    $action_label =  getTranslatedString($action);
    //操作
    $operation    = $action;

    $smarty->assign('MODULE',$module);
    $smarty->assign('ACTION',$action);
    $smarty->assign('MODULE_LABEL',$module_label);
    $smarty->assign('ACTION_LABEL',$action_label);

    if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
    {
        $smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
        $smarty->display('ErrorMessage1.tpl');
        die();
    }



    global $mod;
    if(!isset($mod) && is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
    {
        require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
        $modclass= "{$module}Module";
        $mod = new $modclass();
    }

    global $operation;
    if(!isset($operation)) $operation = 'edit';
    $smarty->assign('OPERATION',$operation);




    global $return_module;
    if(!isset($return_module))
    {
        if(isset($_GET['return_module'])) $return_module = $_GET['return_module'];
        else $return_module = $module;
    }

    global $return_action;
    if(!isset($return_action))
    {
        if(isset($_GET['return_action'])) $return_action = $_GET['return_action'];
        else $return_action = 'index';
    }

    $smarty->assign('RETURN_MODULE',$return_module);
    $smarty->assign('RETURN_ACTION',$return_action);

    $result = $mod->run($operation);
    $smarty->assign("MOD",$mod_strings);
    $smarty->assign("COUNT",$result);
    $smarty->assign("OPERA",$operation);

    global $tpl_file;
    if(!isset($tpl_file)) $tpl_file = "{$module}/step2.tpl";
    $smarty->display($tpl_file);