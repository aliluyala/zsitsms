<?php
set_time_limit(0);
ini_set('memory_limit','640M');
require_once(_ROOT_DIR.'/include/PHPExcel.php');
require(_ROOT_DIR.'/include/APCUpload.class.php');
$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
$cacheSettings = array( 'memoryCacheSize'  => '512MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
//execl过滤类
class PreReadFilter implements PHPExcel_Reader_IReadFilter
{
    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= 1 && $row <= 10)
        {
            return true;
        }
        return false;
    }
}



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
global $mod;
if(!isset($mod))
{
    if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
    {
        require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
        $modclass= "{$module}Module";
        $mod = new $modclass();
    }
}
global $mod_table;
if(!isset($mod_table))
{
    $mod_table = $mod->baseTable;
}

global $importFilePath;
if(!isset($importFilePath))
{
    $importFilePath = _ROOT_DIR."/cache/import/{$module}/{$CURRENT_USER_NAME}/";
}

$step = '1';
if(!empty($_GET['step'])) $step = $_GET['step'];
if($step == '1')
{
    $fileList = array();
    if(is_dir($importFilePath))
    {
        $d = opendir($importFilePath);
        while($file = readdir($d))
        {
            if($file == '.' || $file ==  '..' || is_dir($importFilePath.$file)) continue;
            $fileList[$file] = $file;
        }
    }
    $smarty->assign("FILE_LIST",$fileList);
    $smarty->display('Import1A.tpl');
    die();
}
elseif($step == 'uploadFile')
{
    if(!is_dir($importFilePath)) @mkdir($importFilePath,0777,true);
    $apcupload = new APCUpload($importFilePath,
                               array('csv','txt','xls','xlsx')
                               );
    if($apcupload->getError() === 0)
    {
        $apcupload->completeUpload();
    }
    echo $apcupload->getErrorJson();
    die();
}
elseif($step == 'uploadFileProgress')
{
    $apcupload = new APCUpload('',array());
    $apckey = $_GET['apckey'];
    echo  $apcupload->getProgressJson($apckey);
    die();
}
elseif($step == 'clearFile')
{
    if(is_dir($importFilePath))
    {
        $d = opendir($importFilePath);
        while($file = readdir($d))
        {
            if($file == '.' || $file ==  '..' || is_dir($importFilePath.$file)) continue;
            echo $importFilePath.$file;
            @unlink($importFilePath.$file);
        }

    }
    die();
}
elseif($step == "2")
{
    if(empty($_GET['dataFile']))
    {
        die( '没有指定导入数据文件！');

    }
    $dataFile = $importFilePath."/{$_GET['dataFile']}";
    if(!is_file($dataFile))
    {
        die('数据文件不存！');
    }
    $file_type_is_execl = false;
    $file_type_is_text = false;
    $fileParts = pathinfo($_GET['dataFile']);
    $fext = strtolower($fileParts['extension']);
    if($fext == 'xls' || $fext == 'xlsx')
    {
        $file_type_is_execl = true;

        //$execl = PHPExcel_IOFactory::load($dataFile);
        if($fext == 'xls')
        {
            $reader = new PHPExcel_Reader_Excel5();
        }
        else
        {
            $reader = new PHPExcel_Reader_Excel2007();
        }
        $reader->setReadDataOnly(true);
        $reader->setReadFilter( new PreReadFilter() );
        $execl = $reader->load($dataFile);


        $sheetnames = $execl->getSheetNames();
        if(empty($sheetnames)) die('没有工作表！');
        $execl_datas = Array();
        foreach($sheetnames as $sh)
        {
            $sheet = $execl->getSheetByName($sh);
            $headers = Array();
            $maxColIndex = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
            for($idx=0;$idx<=$maxColIndex;$idx++)
            {
                $headers[] = PHPExcel_Cell::stringFromColumnIndex($idx);
            }
            $datas = Array();
            $maxRows = $sheet->getHighestDataRow();
            for($line=1;$line<=10;$line++)
            {
                if($line>$maxRows) break;
                $datas[$line] = Array();
                for($col=0;$col<$maxColIndex;$col++)
                {
                    $cell = $sheet->getCellByColumnAndRow($col,$line);
                    if($cell)
                    {
                        $datas[$line][] = $cell->getValue();
                    }
                    else
                    {
                        $datas[$line][] = '';
                    }
                }
            }

            $execl_datas[$sh] = Array();
            $execl_datas[$sh]['headers'] = $headers;
            $execl_datas[$sh]['datas'] = $datas;
        }
        $smarty->assign('EXECL_SHEETS',$sheetnames);
        $smarty->assign('EXECL_DATAS',$execl_datas);
    }
    elseif($fext == 'txt' || $fext = 'csv')
    {
        $file_type_is_text = true;
        $file = fopen($dataFile,'r');
        if(!$file)
        {
            die('读取文件失败');
        }
        $file_text_content = '';
        $rows = 10;
        $separators = array('   ',',',';',' ','|');
        $septest = array(1,1,1,1,1);
        $seppre = array(0,0,0,0,0);
        while(!feof($file))
        {
            $linestr = fgets($file);
            foreach($separators as $key=>$sep)
            {
                $sepcount = substr_count($linestr,$sep);
                if( $seppre[$key] === 0 )
                {
                    $seppre[$key] = $sepcount;
                }

                if( $sepcount ===0 )
                {
                    $septest[$key] = 0;
                }
                elseif( $seppre[$key] !== 0 )
                {
                    $septest[$key] = $septest[$key] * ( $sepcount/$seppre[$key] );
                }
            }
            $file_text_content .= $linestr;
            $rows--;
            if($rows<=0) break;
        }
        fclose($file);

        $code = mb_detect_encoding($file_text_content,array('ASCII','GB2312','GBK','UTF-8'));
        if($code && $code != 'UTF-8')
        {
            $file_text_content = mb_convert_encoding($file_text_content,'UTF-8',$code);
        }
        $preSeparator = 'comma';
        foreach($septest as $key => $val)
        {
            if($val === 1)
            {
                switch($key)
                {
                    case 0:
                        $preSeparator = 'tab';
                        break;
                    case 1:
                        $preSeparator = 'comma';
                        break;
                    case 2:
                        $preSeparator = 'semicolon';
                        break;
                    case 3:
                        $preSeparator = 'space';
                        break;
                    case 4:
                        $preSeparator = 'vertical';
                        break;
                }
            }
        }
        $smarty->assign('PRE_SEPARATOR',$preSeparator);
        $smarty->assign('FILE_TEXT_CONTENT',$file_text_content);
    }
    else
    {
        die('文件类型不正确');
    }

    $crr_field_list =  array();
    foreach($mod->orderbyFields as $fn)
    {
        $crr_field_list[$fn] = getTranslatedString($fn);
    }

    if(!isset($default_crr_field))
    {
        $default_crr_field = '';
    }

    $smarty->assign('CHECK_REPEAT_FIELD_LIST',$crr_field_list);
    $smarty->assign('DEFAULT_CHECK_REPEAT_FILED',$default_crr_field);
    $smarty->assign('FILE_TYPE_IS_EXECL',$file_type_is_execl);
    $smarty->assign('FILE_TYPE_IS_TEXT',$file_type_is_text);
    $smarty->assign('DATA_FILE',$_GET['dataFile']);
    $smarty->display("{$module}/Import2A.tpl");
}
elseif($step == "3" )
{
    if(empty($_GET['dataFile']))
    {
        die( '没有指定导入数据文件！');

    }
    $dataFile = $importFilePath."/{$_GET['dataFile']}";
    if(!is_file($dataFile))
    {
        die('数据文件不存！');
    }
    if(empty($mod->fields)) die('系统错误！模块不支持导入功能。');
    $field_list = Array();
    foreach($mod->fields as $fieldName=>$fieldInfo)
    {
        $field_list[$fieldName] = getTranslatedString($fieldName);
    }
    $fileParts = pathinfo($_GET['dataFile']);
    $fext = strtolower($fileParts['extension']);
    $firstline = 'NO';
    if(isset($_GET['firstline'])) $firstline = $_GET['firstline'];
    $worksheet = '';
    if(isset($_GET['worksheet'])) $worksheet = $_GET['worksheet'];
    $separator = ',';
    $titleIsFiled = false;
    if(isset($_GET['title_is_filed']) && $_GET['title_is_filed'] == 'YES') $titleIsFiled = true;
    $crr_field = '';
    if(!empty($_GET['check_repeat_record_field'])) $crr_field = $_GET['check_repeat_record_field'];

    $data_cols = 0;
    $datas = Array();
    $col_infos = Array();
    $_GET['repeat_record_handle'] =  'update';

    if($fext == 'xls' || $fext == 'xlsx')
    {
        if(empty($worksheet)) die('没有工作表！');
        $execl = PHPExcel_IOFactory::load($dataFile);
        $sheet = $execl->getSheetByName($worksheet);
        $data_cols = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
        $maxRows = $sheet->getHighestDataRow();

        for($line=1;$line<=10;$line++)
        {
            if($line>$maxRows) break;
            $datas[$line] = Array();
            for($col=0;$col<$data_cols;$col++)
            {
                $cell = $sheet->getCellByColumnAndRow($col,$line);

                if($cell)
                {
                    if($firstline == 'YES' && $line==1)
                    {
                        $col_infos[$col]['name'] = $cell->getValue();
                    }
                    else
                    {
                        $datas[$line][] = $cell->getValue();
                    }
                }
                else
                {
                    $datas[$line][] = '';
                }
            }
        }
    }
    elseif($fext == 'txt' || $fext = 'csv')
    {
        if(isset($_GET['col_separator']))
        {
            switch($_GET['col_separator'])
            {
                case "comma":
                    $separator = ',';
                    break;
                case "tab":
                    $separator = '  ';
                    break;
                case "space":
                    $separator = ' ';
                    break;
                case "semicolon":
                    $separator = ';';
                    break;
                case "vertical" :
                    $separator = '|';
                    break;
            }

        }


        $file = fopen($dataFile,'r');
        if(!$file)
        {
            die('读取文件失败');
        }
        $rows = 10;
        $data_cols = 0;

        $line = 0;
        $code = '';
        while(!feof($file))
        {
            $lstr = fgets($file);
            if(empty($code))
            {
                $code = mb_detect_encoding($lstr,array('ASCII','GB2312','GBK','UTF-8'));
            }
            if(!empty($code) && $code != 'UTF-8')
            {
                $lstr = mb_convert_encoding($lstr,'UTF-8',$code);
            }
            $lstr = str_replace("\r","",$lstr);
            $lstr = str_replace("\n","",$lstr);
            $row = explode($separator,$lstr);
            $count = count($row);
            if($count>$data_cols) $data_cols = $count;
            if($firstline == 'YES' && $line == 0)
            {
                $col = 0;
                foreach($row as $col_name)
                {
                    $col_infos[$col] = Array('name'=>$col_name);
                    $col++;
                }
                $line++;
            }
            else
            {
                $datas[] = $row;
                $rows--;
            }
            if($rows<=0) break;
        }
        fclose($file);
    }

    for($col=0;$col<$data_cols;$col++)
    {
        if(!isset($col_infos[$col]))
        {
            $col_infos[$col] = array('name'=>null,'field'=>null);
        }
        else
        {
            $field = false;
            if($titleIsFiled)
            {
                $field = array_search($col_infos[$col]['name'],$field_list);
            }
            if($field === false)
            {
                $col_infos[$col]['field'] = null;
            }
            else
            {
                $col_infos[$col]['field'] = $field;
            }
        }
    }

    $smarty->assign('REPEAT_RECORD_HANDLE',$_GET['repeat_record_handle']);
    $smarty->assign('CHECK_REPEAT_FIELD',$crr_field);
    $smarty->assign('DATA_FILE',$_GET['dataFile']);
    $smarty->assign('COL_INFOS',$col_infos);
    $smarty->assign('FIELD_LIST',$field_list);
    $smarty->assign('FIRSTLINE',$firstline);
    $smarty->assign('WORKSHEET',$worksheet);
    $smarty->assign('SEPARATOR',$separator);
    $smarty->assign('DATA_COLS',$data_cols);
    $smarty->assign('DATAS',$datas);
    $smarty->display('Import3A.tpl');

}
elseif($step == 4)
{
    if(empty($_GET['dataFile']))
    {
        die( '没有指定导入数据文件！');
    }
    $dataFile = $importFilePath."/{$_GET['dataFile']}";
    if(!is_file($dataFile))
    {
        die('数据文件不存！');
    }
    $cols = array();
    $selFields = array();
    if(isset($_GET['import_cols'])) $cols = $_GET['import_cols'];

    foreach($cols as $col)
    {
        if(isset($_GET['col_field_name_'.$col]))
        {
            $selFields[$col] = $_GET['col_field_name_'.$col];
        }
    }
    if(empty($mod->fields)) die('系统错误！模块不支持导入功能。');
    $defFields = array();
    foreach($mod->fields as $fName => $fSet)
    {
        if(!in_array($fName,$selFields) && $fSet[2] && $fSet[0] != '17' && $fSet[0] != '35' &&
            $fSet[0] != '36' && $fSet[0] != '51' && $fSet[0] != '52' )
        {
            $defFields[] =  $fName;
        }
    }

    $haveDef = false;
    $editview_datas = array();
    if(!empty($defFields))
    {
        $haveDef = true;
        $editview_datas = createEditViewUI($module,$mod->fields,$defFields,$defFields,1,
                                           Array(),$mod->associateTo,$mod->picklist,null,'edit');
    }

    $firstline = 'NO';
    if(isset($_GET['firstline'])) $firstline = $_GET['firstline'];
    $worksheet = '';
    if(isset($_GET['worksheet'])) $worksheet = $_GET['worksheet'];
    $separator = ',';
    if(isset($_GET['col_separator'])) $separator = $_GET['col_separator'];
    $crr_field = '';
    if(isset($_GET['check_repeat_field'])) $crr_field = $_GET['check_repeat_field'];

    $smarty->assign('REPEAT_RECORD_HANDLE',$_GET['repeat_record_handle']);
    $smarty->assign('CHECK_REPEAT_FIELD',$crr_field);
    $smarty->assign('DATA_FILE',$_GET['dataFile']);
    $smarty->assign('COL_TO_FIELD',urlencode(json_encode($selFields)));
    $smarty->assign('HAVE_DEFAULT_FIELD',$haveDef);
    $smarty->assign('FIRSTLINE',$firstline);
    $smarty->assign('WORKSHEET',$worksheet);
    $smarty->assign('SEPARATOR',$separator);
    $smarty->assign('EDITVIEW_DATAS',$editview_datas );
    $smarty->display('Import4A.tpl');

    die();
}
elseif($step == 5)
{
    $apckey=md5(time().$_GET['import_option_dataFile']);
    $importProgressUrl = _INDEX_URL."?module={$module}&action=import&step=importProgress&key={$apckey}";
    $importDataUrl = str_replace('step=5','step=importData',$_SERVER["REQUEST_URI"])."&key={$apckey}";
    apc_store('rows_'    .$apckey,0,86400);
    apc_store('inserts_' .$apckey,0,86400);
    apc_store('updates_' .$apckey,0,86400);
    apc_store('discards_'.$apckey,0,86400);
    apc_store('repeats_' .$apckey,0,86400);
    apc_store('invalids_'.$apckey,0,86400);
    $smarty->assign('APCKEY',$apckey);
    $smarty->assign('IMPORT_DATA_URL',$importDataUrl);
    $smarty->assign('IMPORT_PROGRESS_URL',$importProgressUrl);
    $smarty->display('Import5A.tpl');
}
elseif($step == "importProgress")
{
    $key = $_GET['key'];
    $data = array(
        'rows'    =>apc_fetch('rows_'    .$key),
        'inserts' =>apc_fetch('inserts_' .$key),
        'updates' =>apc_fetch('updates_' .$key),
        'discards'=>apc_fetch('discards_'.$key),
        'repeats' =>apc_fetch('repeats_' .$key),
        'invalids'=>apc_fetch('invalids_'.$key),
        'key'     =>$key,

    );
    echo return_ajax('success',$data);
    die();
}
elseif($step == "importData")
{
    session_write_close();
    $apckey = $_GET['key'];

    if(empty($_GET['import_option_dataFile']))
    {
        die( '没有指定导入数据文件！');
    }
    $dataFile = $importFilePath."/{$_GET['import_option_dataFile']}";
    if(!is_file($dataFile))
    {
        die('数据文件不存！');
    }
    if(empty($mod->fields)) die('系统错误！模块不支持导入功能。');

    $fileParts = pathinfo($_GET['import_option_dataFile']);
    $fext = strtolower($fileParts['extension']);
    $firstline = 'NO';
    if(isset($_GET['import_option_firstline'])) $firstline = $_GET['import_option_firstline'];
    $worksheet = '';
    if(isset($_GET['import_option_worksheet'])) $worksheet = $_GET['import_option_worksheet'];
    $separator = ',';
    if(isset($_GET['import_option_col_separator'])) $separator = $_GET['import_option_col_separator'];
    $colToField = array();
    if(isset($_GET['import_option_colToField'])) $colToField = json_decode(urldecode($_GET['import_option_colToField']),true);
    $crr_field = "";
    $_GET['import_option_check_repeat_field'] = "order_no";
    if(isset($_GET['import_option_check_repeat_field'])) $crr_field = $_GET['import_option_check_repeat_field'];


    $startline = 1;
    if($firstline == 'YES') $startline = 2;
    $data_cols = 0;
    $maxRows = 0;
    $fieldsOpt = Array();


    $defFieldValues = array();
    $autoFields =  array();
    foreach($mod->fields as $fName => $fSet)
    {
        if(!in_array($fName,$colToField) && $fSet[2] && $fSet[0] != '35' &&
            $fSet[0] != '36' && $fSet[0] != '51' && $fSet[0] != '52' )
        {
            if($fSet[0] == '17' )
            {
                $autoFields[] = $fName;
            }
            elseif(!empty($_GET[$fName]))
            {
                $defFieldValues[$fName] =  $_GET[$fName];
            }
        }
    }


    foreach($colToField as $col => $field)
    {
        //if(isset($_POST['col_field_name_'.$col]))
        //{
            //$field = $_POST['col_field_name_'.$col];
            $fieldsOpt[$col] = Array();
            $fieldsOpt[$col]['name'] = $field;

            $fdset = $mod->fields[$field];
            if($fdset[0] == '20' || $fdset[0] == '21' )
            {
                $fieldsOpt[$col]['options'] = Array();
                if(isset($mod->picklist[$field]))
                {
                    foreach($mod->picklist[$field] as $item)
                    {
                        $tstr = getTranslatedString($item);
                        $fieldsOpt[$col]['options'][$tstr] = $item;
                    }
                }
            }
            elseif($fdset[0] == '25')
            {
                $fieldsOpt[$col]['options'] = Array();
                if(isset($mod->picklist[$field]))
                {
                    foreach($mod->picklist[$field] as $key => $group)
                    {
                        if($key != 'picklist_define')
                        {
                            foreach($group as $item)
                            {
                                $tstr = getTranslatedString($item);
                                $fieldsOpt[$col]['options'][$tstr] = $item;
                            }
                        }
                    }
                }
            }
            elseif($fdset[0] == '50' || $fdset[0] == '51' || $fdset[0] == '52')
            {
                if(isset($mod->associateTo[$field]))
                {
                    $ass_module = $mod->associateTo[$field][1];
                    if(is_file(_ROOT_DIR."/modules/{$ass_module}/{$ass_module}Module.class.php"))
                    {
                        require_once(_ROOT_DIR."/modules/{$ass_module}/{$ass_module}Module.class.php");
                        $modclass= "{$ass_module}Module";
                        $fieldsOpt[$col]['assto_module'] = new $modclass();
                    }
                    $fieldsOpt[$col]['assto_ass_field'] = $mod->associateTo[$field][3];
                    $fieldsOpt[$col]['assto_show_field'] = $mod->associateTo[$field][4];
                }
            }
            elseif($fdset[0] == '55')
            {
                if(is_file(_ROOT_DIR."/modules/GroupManager/GroupManagerModule.class.php"))
                {
                    require_once(_ROOT_DIR."/modules/GroupManager/GroupManagerModule.class.php");
                    $fieldsOpt[$col]['attach_module']['GROUP'] = new GroupManagerModule();
                    $fieldsOpt[$col]['attach_ass_field']['GROUP']  = "id";
                    $fieldsOpt[$col]['attach_show_field']['GROUP'] = "name";
                }
                if(is_file(_ROOT_DIR."/modules/User/UserModule.class.php"))
                {
                    require_once(_ROOT_DIR."/modules/User/UserModule.class.php");
                    $fieldsOpt[$col]['attach_module']['USER'] = new UserModule();
                    $fieldsOpt[$col]['attach_ass_field']['USER']  = "id";
                    $fieldsOpt[$col]['attach_show_field']['USER'] = "user_name";
                }
            }
        //}
    }

    $mod->PM_init();
    $mod->PM_login();
    $mod->PM_caseList();

    if(!empty($fieldsOpt)&&($fext == 'xls' || $fext == 'xlsx'))
    {
        if(empty($worksheet)) die('没有工作表！');
        $execl = PHPExcel_IOFactory::load($dataFile);
        $sheet = $execl->getSheetByName($worksheet);
        $data_cols = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
        $maxRows = $sheet->getHighestDataRow();
        for($line=$startline;$line<=$maxRows;$line++)
        {
            apc_inc('rows_'.$apckey);
            $data = Array();
            for($col=0;$col<$data_cols;$col++)
            {
                if(array_key_exists($col,$fieldsOpt))
                {
                    $cell = $sheet->getCellByColumnAndRow($col,$line);
                    $val = '';
                    if($cell)
                    {
                        $val = $cell->getValue();
                    }

                    if(array_key_exists('options',$fieldsOpt[$col]))
                    {
                        if(isset($fieldsOpt[$col]['options'][$val])) $data[$fieldsOpt[$col]['name']] = $fieldsOpt[$col]['options'][$val];
                        else $data[$fieldsOpt[$col]['name']] = '';
                    }
                    elseif(array_key_exists('assto_module',$fieldsOpt[$col]))
                    {

                        $result = $fieldsOpt[$col]['assto_module']->getListQueryRecord(
                                                            Array(Array($fieldsOpt[$col]['assto_show_field'],'=',$val,'')),
                                                            null,null,null,null,null,0,1
                                                        );

                        if($result && !empty($result[0]))
                        {
                            $data[$fieldsOpt[$col]['name']] = $result[0][$fieldsOpt[$col]['assto_ass_field']];
                        }
                        else
                        {
                            $data[$fieldsOpt[$col]['name']] = -1;
                        }
                    }
                    elseif (array_key_exists('attach_module',$fieldsOpt[$col])) {

                        $USER  = $fieldsOpt[$col]['attach_module']['USER']->getListQueryRecord(
                                                            Array(Array($fieldsOpt[$col]['attach_show_field']['USER'],'=',$val,'')),
                                                            null,null,null,null,null,0,1
                                                        );
                        if ($USER && !empty($USER[0])) {
                            $data[$fieldsOpt[$col]['name']] = $USER[0][$fieldsOpt[$col]['attach_ass_field']['USER']];
                        }else {
                            $GROUP = $fieldsOpt[$col]['attach_module']['GROUP']->getListQueryRecord(
                                                            Array(Array($fieldsOpt[$col]['attach_show_field']['GROUP'],'=',$val,'')),
                                                            null,null,null,null,null,0,1
                                                        );
                            $data[$fieldsOpt[$col]['name']] = $GROUP && !empty($GROUP[0]) ? $GROUP[0][$fieldsOpt[$col]['attach_ass_field']['GROUP']] : $data[$fieldsOpt[$col]['name']] = $CURRENT_USER_ID;
                        }
                    }
                    else
                    {
                        $data[$fieldsOpt[$col]['name']] = $val;
                    }
                }
            }


            $data = array_merge($defFieldValues,$data);
            foreach($autoFields as $field)
            {
                $data[$field] = $mod->autoCompleteFieldValue($field,$mod->fields[$field][4]);
            }


            $isrepeat = false;
            $queryWhere = null;
            if(!empty($crr_field) && !empty($data[$crr_field]))
            {
                $queryWhere = array(array($crr_field,'=',$data[$crr_field],''));
                if($mod->getListQueryRecordCount($queryWhere,null,null,null) > 0    )
                {
                    $isrepeat = true;
                    apc_inc('repeats_'.$apckey);
                }

            }



            if($isrepeat  && $_GET['repeat_record_handle'] == 'update' && !empty($queryWhere))
            {
                if($mod->PM_routeCase($data["order_no"], $data)) {
                    apc_inc('updates_' .$apckey);
                }else{
                    apc_inc('invalids_'.$apckey);
                }
            }
            else
            {
                apc_inc('discards_'.$apckey);
            }

        }
    }
    elseif(!empty($fieldsOpt)&&($fext == 'txt' || $fext = 'csv'))
    {
        $file = fopen($dataFile,'r');
        if(!$file)
        {
            die('读取文件失败');
        }
        $maxRows = 0;
        $data_cols = 0;
        $code='';

        if($firstline == 'YES') fgets($file);
        while(!feof($file))
        {
            $lstr = fgets($file);
            apc_inc('rows_'.$apckey);
            if(empty($code))
            {
                $code = mb_detect_encoding($lstr,array('ASCII','GB2312','GBK','UTF-8'));
            }
            if(!empty($code) && $code != 'UTF-8')
            {
                $lstr = mb_convert_encoding($lstr,'UTF-8',$code);
            }
            $lstr = str_replace("\r","",$lstr);
            $lstr = str_replace("\n","",$lstr);
            $row = explode($separator,$lstr);

            $count = count($row);
            if($count>$data_cols) $data_cols = $count;
            $data = array();
            foreach($row as $col => $val)
            {
                if(array_key_exists($col,$fieldsOpt))
                {

                    if(array_key_exists('options',$fieldsOpt[$col]))
                    {
                        if(isset($fieldsOpt[$col]['options'][$val])) $data[$fieldsOpt[$col]['name']] = $fieldsOpt[$col]['options'][$val];
                        else $data[$fieldsOpt[$col]['name']] = '';
                    }
                    elseif(array_key_exists('assto_module',$fieldsOpt[$col]))
                    {

                        $result = $fieldsOpt[$col]['assto_module']->getListQueryRecord(
                                                            Array(Array($fieldsOpt[$col]['assto_show_field'],'=',$val,'')),
                                                            null,null,null,null,null,0,1
                                                        );

                        if($result && !empty($result[0]))
                        {
                            $data[$fieldsOpt[$col]['name']] = $result[0][$fieldsOpt[$col]['assto_ass_field']];
                        }
                        else
                        {
                            $data[$fieldsOpt[$col]['name']] = '';
                        }
                    }
                    elseif (array_key_exists('attach_module',$fieldsOpt[$col])) {

                        $USER  = $fieldsOpt[$col]['attach_module']['USER']->getListQueryRecord(
                                                            Array(Array($fieldsOpt[$col]['attach_show_field']['USER'],'=',$val,'')),
                                                            null,null,null,null,null,0,1
                                                        );
                        if ($USER && !empty($USER[0])) {
                            $data[$fieldsOpt[$col]['name']] = $USER[0][$fieldsOpt[$col]['attach_ass_field']['USER']];
                        }else {
                            $GROUP = $fieldsOpt[$col]['attach_module']['GROUP']->getListQueryRecord(
                                                            Array(Array($fieldsOpt[$col]['attach_show_field']['GROUP'],'=',$val,'')),
                                                            null,null,null,null,null,0,1
                                                        );
                            $data[$fieldsOpt[$col]['name']] = $GROUP && !empty($GROUP[0]) ? $GROUP[0][$fieldsOpt[$col]['attach_ass_field']['GROUP']] : $data[$fieldsOpt[$col]['name']] = $CURRENT_USER_ID;
                        }
                    }
                    else
                    {
                        $data[$fieldsOpt[$col]['name']] = $val;
                    }

                }


            }


            $data = array_merge($defFieldValues,$data);
            foreach($autoFields as $field)
            {
                $data[$field] = $mod->autoCompleteFieldValue($field,$mod->fields[$field][4]);
            }


            $queryWhere = null;
            $isrepeat = false;
            if(!empty($crr_field) && !empty($data[$crr_field]))
            {
                $queryWhere = array(array($crr_field,'=',$data[$crr_field],''));
                if($mod->getListQueryRecordCount($queryWhere,null,null,null) > 0    )
                {
                    $isrepeat = true;
                    apc_inc('repeats_'.$apckey);
                }

            }


            if($isrepeat  && $_GET['repeat_record_handle'] == 'update' && !empty($queryWhere))
            {
                $ret = $mod->batchModify($mod->editFields,$queryWhere,null,null,null,null,null,null,false,$data);
                if($ret>0)
                {
                    apc_inc('updates_' .$apckey);
                }
                else
                {
                    apc_inc('invalids_'.$apckey);
                }

            }
            elseif(!$isrepeat )
            {
                $recordid =  getNewModuleSeq($mod_table);
                $ret = $mod->insertOneRecordset($recordid,$data);
                if($ret === 0)
                {
                    apc_inc('inserts_' .$apckey);
                }
                else
                {
                    apc_inc('invalids_'.$apckey);
                }
            }
            else
            {
                apc_inc('discards_'.$apckey);
            }
            $maxRows++;
        }
        fclose($file);
    }
    $smarty->assign('ROWS'    ,apc_fetch('rows_'    .$apckey));
    $smarty->assign('INSERTS' ,apc_fetch('inserts_' .$apckey));
    $smarty->assign('UPDATES' ,apc_fetch('updates_' .$apckey));
    $smarty->assign('DISCARDS',apc_fetch('discards_'.$apckey));
    $smarty->assign('REPEATS' ,apc_fetch('repeats_' .$apckey));
    $smarty->assign('INVALIDS',apc_fetch('invalids_'.$apckey));

    $smarty->display("{$module}/Import6A.tpl");
}
?>