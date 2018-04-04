<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
    $smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
    $smarty->display('ErrorMessage1.tpl');
    die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
    $modclass= "{$module}Module";
    $mod = new $modclass();
}

$step = '1';
if(isset($_GET['step']))
{
    $step = $_GET['step'];
}

if($step == '1')
{
    unset($_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]);
    $smarty->display($module . '/BatchDeleteView1.tpl');
}
elseif($step == '2')
{
    $queryWhere = Array();
    if($_POST['delete_query_where'] == 'current_select' )
    {
        $queryWhere = Array(
            Array('id','IN',"({$_POST['select_recordid_list']})",''),
        );
    }
    elseif($_POST['delete_query_where'] == 'current_search' )
    {
        if(isset($_SESSION[_SESSION_KEY]['listview_query_where'][$module]))
        {
            $queryWhere = $_SESSION[_SESSION_KEY]['listview_query_where'][$module];
        }
    }
    $filterWhere = Array();
    if(isset($_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]))
    {
        $filterWhere = getListViewFilterWhere($_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]);
    }
    $limit = NULL;
    if(isset($_POST['delete_count']))
    {
        $limit = $_POST['delete_count'];
    }
    $orderby = '';
    $order = 'NONE';
    if(isset($_SESSION[_SESSION_KEY]['listview_order_by'][$module]) && isset($_SESSION[_SESSION_KEY]['listview_order'][$module]))
    {
        $orderby = $_SESSION[_SESSION_KEY]['listview_order_by'][$module];
        $order = $_SESSION[_SESSION_KEY]['listview_order'][$module];
    }
    $userids = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module);
    $groupids = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module);
    if(!isset($_SESSION[_SESSION_KEY]['listview_batch_delete_params']))
    {
        $_SESSION[_SESSION_KEY]['listview_batch_delete_params'] = Array();
    }
    $_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module] = Array();
    $_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['queryWhere'] = $queryWhere;
    $_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['filterWhere'] = $filterWhere;
    $_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['orderby'] = $orderby;
    $_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['order'] = $order;
    $_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['userids'] = $userids;
    $_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['groupids'] = $groupids;
    $_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['limit'] = $limit;

    $count = $mod->batchDelete($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,true,true);
    $smarty->assign('MODULE_LABEL',getTranslatedString($module));
    $smarty->assign('DELETE_COUNT',$count);
    $smarty->display($module . '/BatchDeleteView2.tpl');
}
elseif($step == '3')
{
    if(isset($_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]) &&
        is_array($_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]))
    {
        $queryWhere=$_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['queryWhere'] ;
        $filterWhere=$_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['filterWhere'] ;
        $orderby=$_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['orderby'];
        $order=$_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['order'] ;
        $userids=$_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['userids'] ;
        $groupids=$_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['groupids'] ;
        $limit=$_SESSION[_SESSION_KEY]['listview_batch_delete_params'][$module]['limit'];
        $count = $mod->batchDelete($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,true,false);
        $smarty->assign('MODULE_LABEL',getTranslatedString($module));
        $smarty->assign('DELETE_COUNT',$count);
        $smarty->display($module . '/BatchDeleteView3.tpl');
    }
    else
    {
        $smarty->assign('ERROR_MESSAGE','系统错误，操作失败！');
        $smarty->display('ErrorMessage1.tpl');
    }
}
?>