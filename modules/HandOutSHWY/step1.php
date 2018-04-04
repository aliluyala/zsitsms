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

    $handout_wait        = createFieldUI('handout_wait',$mod->fields['handout_wait'],$mod->getHandoutCount(),null,array());
    $area                = createFieldUI('area',$mod->fields['area'],$mod->_get('area'),$mod->picklist['area'],array());
    $park                = createFieldUI('park',$mod->fields['park'],$mod->_get('park'),$mod->picklist['park'],array());
    $team                = createFieldUI('team',$mod->fields['team'],$mod->_get('team'),null,array());
    $company             = createFieldUI('company',$mod->fields['company'],$mod->_get('company'),null,Array());
    $company['options'][]  = Array('value' => '','show'  => '');
    $company['options'] = array_reverse($company['options']);
    $batch               = createFieldUI('batch',$mod->fields['batch'],$mod->_get('batch'),$mod->picklist['batch'],array());
    $type                = createFieldUI('type',$mod->fields['type'],$mod->_get('type'),$mod->picklist['type'],array());
    $expiration_date     = createFieldUI('expiration_date',$mod->fields['expiration_date'],$mod->_get('expiration_date'),null,array());
    $expiration_date_end = createFieldUI('expiration_date_end',$mod->fields['expiration_date_end'],$mod->_get('expiration_date_end'),null,array());
    $register_date       = createFieldUI('register_date',$mod->fields['register_date'],$mod->_get('register_date'),null,array());
    $register_date_end   = createFieldUI('register_date_end',$mod->fields['register_date_end'],$mod->_get('register_date_end'),null,array());
    $register_month      = createFieldUI('register_month',$mod->fields['register_month'],$mod->_get('register_month'),$mod->picklist['register_month'],array());
    $date_create         = createFieldUI('date_create',$mod->fields['date_create'],$mod->_get('date_create'),null,array());
    $user_create         = createFieldUI('user_create',$mod->fields['user_create'],$mod->_get('user_create'),null,$mod->associateTo['user_create']);
    $user_attach         = createFieldUI('user_attach',$mod->fields['user_attach'],$mod->_get('user_attach'),null,$mod->associateTo['user_attach']);
    $group_attach        = createFieldUI('group_attach',$mod->fields['group_attach'],$mod->_get('group_attach'),null,$mod->associateTo['group_attach']);
    $model               = createFieldUI('model',$mod->fields['model'],$mod->_get('model'),null,array());
    $status              = createFieldUI('status',$mod->fields['status'],$mod->_get('status'),$mod->picklist['status'],array());
    $report              = createFieldUI('report',$mod->fields['report'],$mod->_get('report'),$mod->picklist['report'],array());
    $smarty->assign('OPERATION_HANDOUT_WAIT_FIELD',$handout_wait);
    $smarty->assign('OPERATION_AREA_FIELD',$area);
    $smarty->assign('OPERATION_PARK_FIELD',$park);
    $smarty->assign('OPERATION_TEAM_FIELD',$team);
    $smarty->assign('OPERATION_COMPANY_FIELD',$company);
    $smarty->assign('OPERATION_BATCH_FIELD',$batch);
    $smarty->assign('OPERATION_TYPE_FIELD',$type);
    $smarty->assign('OPERATION_EXPIRATION_DATE_FIELD',$expiration_date);
    $smarty->assign('OPERATION_EXPIRATION_DATE_END_FIELD',$expiration_date_end);
    $smarty->assign('OPERATION_REGISTER_DATE_FIELD',$register_date);
    $smarty->assign('OPERATION_REGISTER_DATE_END_FIELD',$register_date_end);
    $smarty->assign('OPERATION_REGISTER_MONTH_FIELD',$register_month);
    $smarty->assign('OPERATION_USER_FIELD',$user_attach);
    $smarty->assign('OPERATION_GROUP_FIELD',$group_attach);
    $smarty->assign('OPERATION_USER_CREATE_FIELD',$user_create);
    $smarty->assign('OPERATION_DATE_CREATE_FIELD',$date_create);
    $smarty->assign('OPERATION_MODEL_FIELD',$model);
    $smarty->assign('OPERATION_STATUS_FIELD',$status);
    $smarty->assign('OPERATION_REPORT_FIELD',$report);

    global $tpl_file;
    if(!isset($tpl_file)) $tpl_file = "{$module}/step1.tpl";
    $smarty->display($tpl_file);