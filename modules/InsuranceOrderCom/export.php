<?php
require_once(_ROOT_DIR.'/include/PHPExcel.php');
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$module_label =  getTranslatedString(_MODULE);
$action_label =  getTranslatedString(_ACTION);

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
$mod;
if(!isset($mod) && is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
    $modclass= "{$module}Module";
    $mod = new $modclass();
}
$step = '1';
if(!empty($_GET['step'])) $step = $_GET['step'];

if($step == '1')
{
    $field_list = Array();
    foreach($mod->fields as $field => $info)
    {
        $field_list[$field] = getTranslatedString($field);
    }
    $smarty->assign('PRE_DISTRIBUTION', implode(",", $mod->pre_distribution));
    $smarty->assign('FIELD_LIST',$field_list);
    $smarty->display("{$module}/Export1.tpl");
}
elseif($step == '2')
{
    $fileName = "{$CURRENT_USER_NAME}_{$module}_export.".$_POST['export_file_format'];
    $downloadURL = "index.php?module={$module}&action=export&step=3&filename={$fileName}";
    @unlink(_ROOT_DIR.'/uploads/'.$fileName);
    $filterWhere = Array();
    if($_POST['export_query_where'] == 'current_search' &&
       isset($_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]))
    {
        $filterWhere = getListViewFilterWhere($_SESSION[_SESSION_KEY]['listview_selected_filter'][$module]);
    }
    $queryWhere = Array();
    if($_POST['export_query_where'] == 'current_search' &&
       isset($_SESSION[_SESSION_KEY]['listview_query_where'][$module]))
    {
        $queryWhere = $_SESSION[_SESSION_KEY]['listview_query_where'][$module];
    }
    $userids =  $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module);
    $groupids = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module);
    $orderBy = '';
    $order = '';
    if(isset($_SESSION[_SESSION_KEY]['listview_order_by'][$module]))
    {
        $orderBy = $_SESSION[_SESSION_KEY]['listview_order_by'][$module];
    }
    if(isset($_SESSION[_SESSION_KEY]['listview_order'][$module]))
    {
        $order = $_SESSION[_SESSION_KEY]['listview_order'][$module];
    }
    global $keyName;
    if(!isset($keyName)) $keyName = 'id';

    $recordCount = $mod->getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
    $exportCount = 0;
    $recordStart = 0;
    $recordEnd = 0;
    $perCount = 20;
    if(isset($_POST['export_all_record']) && $_POST['export_all_record'] == 'YES')
    {
        $recordStart = 0;
        $recordEnd = $recordCount - 1;
    }
    elseif(isset($_POST['export_start_count']) && isset($_POST['export_end_count']))
    {
        $recordStart = $_POST['export_start_count'];
        $recordEnd =  $_POST['export_end_count'];
        if($recordStart < 0)
        {
            $recordStart = 0;
        }
        elseif($recordStart > $recordCount)
        {
            $recordStart = $recordCount;
        }

        if($recordEnd < 0)
        {
            $recordEnd = 0;
        }
        elseif($recordEnd > $recordCount)
        {
            $recordEnd = $recordCount;
        }
    }

    if($recordCount>0)
    {
        $exportFields = Array();
        $listFields = Array();
        foreach($_POST['export_fields'] as $field)
        {
            $exportFields[$field] = getTranslatedString($field);
            if($field != 'reason' && $field != 'current_receivable_amount') $listFields[] = $field ;
        }

        if($_POST['export_file_format'] == 'csv')
        {
            $f = fopen(_ROOT_DIR.'/uploads/'.$fileName,'w');
            if(!$f) die('创建导出文件失败！');

            fputcsv($f,$exportFields,',');
            while(true)
            {
                $result = $mod->getListQueryRecord($queryWhere,$filterWhere,$orderBy,$order,$userids,$groupids,$recordStart,$perCount);
                if(count($result)<=0) break;
                if(!$CURRENT_IS_ADMIN) $result = validationFieldsShowPermission($module,$result);
                $list_data = formatListDatas($result,$module,$keyName,$mod->fields,$listFields,'',$mod->picklist,$mod->associateTo);
                foreach($list_data as $row)
                {
                    $line = '';
                    foreach($listFields as $field)
                    {
                        $line .= "\t" . $row[$field]['value'] . ",";
                        /*if(empty($line)) $line = $row[$field]['value'];
                        else $line .= ','. "\t" .$row[$field]['value'];//Add tabs(\t) to avoid scientific counting.*/
                    }
                    $line = substr($line, 0, -1);//Delete the last comma
                    fputs($f,$line."\r\n");
                    $exportCount++;
                    $recordStart++;
                    if($recordStart>$recordEnd) break;
                }
                if($recordStart>$recordEnd) break;
            }
            fclose($f);
        }
        if($_POST['export_file_format'] == 'xlsx' || $_POST['export_file_format'] == 'xls')
        {
            require_once(_ROOT_DIR.'/include/PHPExcel.php');
            $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
            $cacheSettings = array( 'memoryCacheSize'  => '16MB');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                ->setCreator('成都启点科技有限公司 ZSwitch CRM')
                ->setLastModifiedBy('成都启点科技有限公司 ZSwitch CRM')
                ->setTitle("{$module_label}-导出数据")
                ->setSubject("{$module_label}-导出数据")
                ->setDescription("些文件是ZSwitch CRM 系统导出数据。")
                ->setKeywords("ZSwitch CRM 导出")
                ->setCategory("导出文件");
            $ws = $objPHPExcel->getActiveSheet();
            $ws->setTitle($module_label);
            $col = 0;
            foreach($exportFields as $fieldName)
            {
                $ws->setCellValueByColumnAndRow($col,1,$fieldName);
                $col++;
            }
            while(true)
            {
                $result = $mod->getListQueryRecord($queryWhere,$filterWhere,$orderBy,$order,$userids,$groupids,$recordStart,$perCount);
                if(count($result)<=0) break;
                if(!$CURRENT_IS_ADMIN) $result = validationFieldsShowPermission($module,$result);
                $list_data = formatListDatas($result,$module,$keyName,$mod->fields,$listFields,'',$mod->picklist,$mod->associateTo);
                foreach($list_data as $row)
                {
                    //$col = 0;
                    //Make sure all of column to be text.
                    $col = "A";
                    foreach($listFields as $field)
                    {
                        $line = $exportCount+2;
                        $postion = $col.$line;
                        $ws->setCellValueExplicit($postion, $row[$field]['value'], PHPExcel_Cell_DataType::TYPE_STRING);
                        //$ws->setCellValueByColumnAndRow($col,$exportCount+2,$row[$field]['value'],true)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
                        $col++;
                    }
                    $exportCount++;
                    $recordStart++;
                    if($exportCount>=65534) break;
                    if($recordStart>$recordEnd) break;
                }
                if($exportCount>=65534) break;
                if($recordStart>$recordEnd) break;
            }

            if($_POST['export_file_format'] == 'xlsx')
            {
                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save(_ROOT_DIR.'/uploads/'.$fileName);
            }
            else
            {
                $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                $objWriter->save(_ROOT_DIR.'/uploads/'.$fileName);
            }
        }

    }
    $smarty->assign('RECORD_COUNT',$recordCount);
    $smarty->assign('EXPORT_COUNT',$exportCount);
    $smarty->assign('DOWNLOAD_URL',$downloadURL);
    $smarty->assign('FILE_NAME',$fileName);
    $smarty->display('Export2.tpl');
}
elseif($step == '3')
{
    if(isset($_GET['filename']))
    {
        header("application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename={$_GET['filename']}");
        readfile(_ROOT_DIR.'/uploads/'.$_GET['filename']);
    }

}

?>