<?php
date_default_timezone_set('PRC');
//禁止表头按键
$bar_buttons     = FALSE;
//禁止操作按钮
$operation_allow = FALSE;
//禁止复选框
$selecter_allow  = FALSE;
//禁止表头菜单
//$bar_allow    = FALSE;
//允许部分公共按钮
$toolsbar        = createToolsbar(Array('search','calendar','calculator','email','sms','export'));
//设置模板路径
//$tpl_file        = 'CallOutAnalysis/ListView.tpl';
/*if(!isset($_SESSION[_SESSION_KEY]['listview_selected_filter'])) $_SESSION[_SESSION_KEY]['listview_selected_filter'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['listview_query_where'])) $_SESSION[_SESSION_KEY]['listview_query_where'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['listview_order_by'])) $_SESSION[_SESSION_KEY]['listview_order_by'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['listview_order'])) $_SESSION[_SESSION_KEY]['listview_order'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['listview_record_page'])) $_SESSION[_SESSION_KEY]['listview_record_page'] = Array();
if(!isset($_SESSION[_SESSION_KEY]['detialview_record_current_offset'])) $_SESSION[_SESSION_KEY]['detialview_record_current_offset'] = Array();
if(!isset($module)) $module = _MODULE;
global $query_where ;
if(!isset($query_where))
{
    if(isset($_POST['query_where']))
    {
        $query_where = json_decode($_POST['query_where']);
    }
    elseif(isset($_SESSION[_SESSION_KEY]['listview_query_where'][$module]))
    {
        $query_where = $_SESSION[_SESSION_KEY]['listview_query_where'][$module];
    }
    else
    {
        $query_where = Array();
    }
}
if(empty($query_where)){
	//禁止表头菜单
    $bar_allow    = FALSE;
    $list_data    = 1;
    $record_total = 0;
}*/
require_once(_ROOT_DIR."/common/listView.php");
?>
