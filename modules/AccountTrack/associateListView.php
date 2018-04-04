<?php

global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$module_label =  getTranslatedString(_MODULE);
$action_label =  getTranslatedString(_ACTION);

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
    $modclass= "{$module}Module";
    $mod = new $modclass();
}


$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);

if(!isset($operation_allow)) $operation_allow = false;
if(!isset($operations)) $operations = Array();

if(!isset($_GET['associateField']) || !isset($_GET['listfields'])) die();
$associateField = $_GET['associateField'];
$listfields = explode(',',$_GET['listfields']);
$assfieldvalue = '';
if(isset($_GET['fieldvalue'])) $assfieldvalue = $_GET['fieldvalue'];

$smarty->assign('ASSOCIATE_FIELD',$associateField);
$smarty->assign('ASSOCIATE_VALUE',$assfieldvalue);
$smarty->assign('LIST_FIELDS',$_GET['listfields']);


if(!isset($_SESSION[_SESSION_KEY]['associate_listview_order_by'])) $_SESSION[_SESSION_KEY]['associate_listview_order_by'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['associate_listview_order'])) $_SESSION[_SESSION_KEY]['associate_listview_order'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['associate_listview_record_page'])) $_SESSION[_SESSION_KEY]['associate_listview_record_page'] = Array();



//表头
$order_by = '';
$order = 'NONE';
if(!isset($headers))
{
    if(isset($_POST['order_by']) && isset($_POST['order']))
    {
        $headers = createListViewHeaders($listfields,$mod->orderbyFields,$mod->defaultOrder[0],
                        $mod->defaultOrder[1],$_POST['order_by'],$_POST['order']);
        $order_by = $_POST['order_by'];
        $order = $_POST['order'];
    }
    elseif(isset($_SESSION[_SESSION_KEY]['associate_listview_order_by'][$module]) && isset($_SESSION[_SESSION_KEY]['associate_listview_order'][$module]))
    {
        $headers = createListViewHeaders($listfields,$mod->orderbyFields,$mod->defaultOrder[0],
                        $mod->defaultOrder[1],$_SESSION[_SESSION_KEY]['associate_listview_order_by'][$module],$_SESSION[_SESSION_KEY]['associate_listview_order'][$module]);
        $order_by = $_SESSION[_SESSION_KEY]['associate_listview_order_by'][$module];
        $order = $_SESSION[_SESSION_KEY]['associate_listview_order'][$module];
    }
    else
    {
        $headers = createListViewHeaders($listfields,$mod->orderbyFields,$mod->defaultOrder[0],
                        $mod->defaultOrder[1],null,null);
    }
}
$smarty->assign('LISTVIEW_HEADERS', $headers);




global $list_max_rows;
if(!isset($list_max_rows))
{
    if(isset($mod->listMaxRows)) $list_max_rows = $mod->listMaxRows;
    else $list_max_rows = 25;
}
global $keyName;
if(!isset($keyName)) $keyName = 'id';

global $list_data,$record_count,$record_start,$record_end,$record_pagecount,$record_page;
if(!isset($list_data))
{
    $list_max_rows = $mod->listMaxRows;
    $record_total = $mod->getListQueryRecordCount(
                                    Array(Array($associateField,'=',$assfieldvalue,'')),
                                    Array(),
                                    NULL,
                                    NULL
                                    );
    $list_data = Array();

    if($record_total > 0)
    {
        $record_pagecount = floor(($record_total-1)/$list_max_rows)+1;
        $record_page = 1;
        if(isset($_POST['record_page']))
        {
            $record_page = $_POST['record_page'];
        }
        elseif(isset( $_SESSION[_SESSION_KEY]['associate_listview_record_page'][$module]))
        {
            $record_page =  $_SESSION[_SESSION_KEY]['associate_listview_record_page'][$module];
        }

        if($record_page > $record_pagecount) $record_page = $record_pagecount;
        elseif($record_page < 1) $record_page = 1;
        $record_start = ($record_page-1) * $list_max_rows ;

        $result = $mod->getListQueryRecord(
                                    Array(Array($associateField,'=',$assfieldvalue,'')),
                                    Array(),
                                    $order_by,
                                    $order,
                                    NULL,
                                    NULL,
                                    $record_start,
                                    $list_max_rows);
        if($result)
        {

            $record_count = count($result);
            $record_end = $record_start + $record_count - 1;
            if(!$CURRENT_IS_ADMIN) $result = validationFieldsShowPermission(_MODULE,$result);
            $list_data = formatListDatas($result,$module,$keyName,$mod->fields,$listfields,$mod->enteryField,$mod->picklist,$mod->associateTo);
            }
        else
        {

            $record_end = $record_start;
        }
    }
    else
    {
        $record_pagecount = 0;
        $record_page = 0;
        $record_start =0 ;
        $record_end = 0 ;
    }
}

$_SESSION[_SESSION_KEY]['associate_listview_order_by'][$module] = $order_by;
$_SESSION[_SESSION_KEY]['associate_listview_order'][$module] = $order;
$_SESSION[_SESSION_KEY]['associate_listview_record_page'][$module] = $record_page;

$smarty->assign('ASSOCIATE_LISTVIEW_OPERATION_ALLOW',$operation_allow);
$smarty->assign('ASSOCIATE_LISTVIEW_OPERATIONS',$operations);
$smarty->assign('LISTVIEW_DATA',$list_data);
$smarty->assign('LISTVIEW_RECORD_START',$record_start);
$smarty->assign('LISTVIEW_RECORD_TOTAL',$record_total);
$smarty->assign('LISTVIEW_RECORD_END',$record_end);
$smarty->assign('LISTVIEW_RECORD_PAGE',$record_page);
$smarty->assign('LISTVIEW_RECORD_PAGECOUNT',$record_pagecount);
$smarty->assign('LISTVIEW_ORDER_BY',$order_by);
$smarty->assign('LISTVIEW_ORDER',$order);

$smarty->display('AssociateListView.tpl');
?>