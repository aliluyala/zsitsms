<?
    //禁止表头按键
    $bar_buttons     = FALSE;
    //禁止操作按钮
    $operation_allow = FALSE;
    //禁止复选框
    $selecter_allow  = FALSE;
    //允许部分公共按钮
    $toolsbar        = createToolsbar(Array('calendar','calculator','email','sms'));
    //操作
    $operation       = "next";


    global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
    $smarty = new ZS_Smarty();
    if(!isset($module)) $module = _MODULE;
    if(!isset($action)) $action = _ACTION;
    $module_label =  getTranslatedString($module);
    $action_label =  getTranslatedString($action);
    global $toolsbar;
    if(!isset($toolsbar)) $toolsbar = createToolsbar(Array('calendar','calculator','email','sms','phone'));

    $smarty->assign('TOOLSBAR',$toolsbar);
    $smarty->assign('MODULE',$module);
    $smarty->assign('ACTION',$action);
    $smarty->assign('MODULE_LABEL',$module_label);
    $smarty->assign('ACTION_LABEL',$action_label);
    global $show_module_label;
    if(!isset($show_module_label)) $show_module_label = true;
      $smarty->assign('TITLEBAR_SHOW_MODULE_LABEL',$show_module_label);
    if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
    {
        $smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
        $smarty->display('ErrorMessage.tpl');
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

    $num = $mod->getHandoutCount();

    global $CURRENT_USER_GROUPID;
    if($CURRENT_USER_GROUPID != -1)
        $mod->associateTo['handout_group'] =   Array('MODULE','GroupManager','detailView','id','name',Array("id"=>$CURRENT_USER_GROUPID),Array("name"));
    $operation_handout_wait_field      =   createFieldUI('handout_wait',$mod->fields['handout_wait'],$mod->getHandoutCount(),null,array());
    $operation_handout_num_field       =   createFieldUI('handout_num',$mod->fields['handout_num'],null,null,array());
    $operation_handout_way_field       =   createFieldUI('handout_way',$mod->fields['handout_way'],'HANDOUT_BY_SIT',$mod->picklist['handout_way'],array());
    $operation_handout_sit_field       =   createFieldUI('handout_sit',$mod->fields['handout_sit'],null,null,array());
    $operation_handout_group_field     =   createFieldUI('handout_group',$mod->fields['handout_group'],null,null,$mod->associateTo['handout_group']);

    $smarty->assign('OPERATION_HANDOUT_WAIT_FIELD',$operation_handout_wait_field);
    $smarty->assign('REQUEST',$_REQUEST);
    $smarty->assign('NUM',$num);
    $smarty->assign('MOD',$mod_strings);
    $smarty->assign('OPERATION_HANDOUT_WAIT_FIELD',$operation_handout_wait_field);
    $smarty->assign('OPERATION_HANDOUT_NUM_FIELD',$operation_handout_num_field);
    $smarty->assign('OPERATION_HANDOUT_WAY_FIELD',$operation_handout_way_field);
    $smarty->assign('OPERATION_HANDOUT_SIT_FIELD',$operation_handout_sit_field);
    $smarty->assign('OPERATION_HANDOUT_GROUP_FIELD',$operation_handout_group_field);
    $handout_premiss = !$CURRENT_IS_ADMIN && !validationActionPermission($module,"handout") ? FALSE : TRUE;
    $recycle_premiss = !$CURRENT_IS_ADMIN && !validationActionPermission($module,"recycle") ? FALSE : TRUE;
    $delete_premiss =  !$CURRENT_IS_ADMIN && !validationActionPermission($module,"delete") ? FALSE : TRUE;
    $smarty->assign('HANDOUT_PREMISS',$handout_premiss);
    $smarty->assign('RECYCLE_PREMISS',$recycle_premiss);
    $smarty->assign('DELETE_PREMISS',$delete_premiss);

    global $tpl_file;
    if(!isset($tpl_file)) $tpl_file = "{$module}/step2.tpl";
    $smarty->display($tpl_file);