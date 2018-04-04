<?php
set_time_limit(0); 
ini_set('memory_limit','512M');
require_once(_ROOT_DIR.'/include/PHPExcel.php');
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( 'memoryCacheSize'  => '48MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

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
$targetPath = _ROOT_DIR.'/uploads'; 
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

global $mod_table;
if(!isset($mod_table))
{
	$mod_table = $mod->baseTable;
}

$step = '1';
if(!empty($_GET['step'])) $step = $_GET['step'];
if($step == '1')
{
	$smarty->display('Import1.tpl');
}
elseif($step == 'uploadfile')
{
	$error = "";
	$msg = "";
	$fileElementName = 'import_upload_file';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = '上传文件大小超过 php.ini 设定值！';
				break;
			case '2':
				$error = '上传文件大小超过浏览器 MAX_FILE_SIZE 设定值！';
				break;
			case '3':
				$error = '文件只上传完成部分！';
				break;
			case '4':
				$error = '没有文件被上传！';
				break;
			case '6':
				$error = '临时目录不存在！';
				break;
			case '7':
				$error = '写磁盘失败！';
				break;
			case '8':
				$error = '文件上传意外中止！';
				break;
			case '999':
			default:
				$error = '不知名错误！';
		}
	}
	elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
	{
		$error = '文件没有上传完成..';
	}
	else 
	{		
		$tempFile = $_FILES[$fileElementName]['tmp_name'];				
		$fileTypes = array('txt','xls','xlsx','csv'); 
		$fileParts = pathinfo($_FILES[$fileElementName]['name']);
		$filesavename =  md5($fileParts['basename']).'.'.$fileParts['extension'];
		$targetFile = $targetPath . '/' .$filesavename;
		$fext = strtolower($fileParts['extension']);
		if (in_array($fext,$fileTypes)) 
		{
			@move_uploaded_file($tempFile,$targetFile);
			$msg = $filesavename;
		} else 
		{
			$error = '无效文件类型。';
		}		
	}		
	echo json_encode(Array('error'=>$error,'msg'=>$msg));
}
elseif($step == "2")
{
	if(empty($_GET['datafile']))
	{
		die( '没有指定导入数据文件！');
		
	}
	$datafile = $targetPath."/{$_GET['datafile']}"; 
	if(!is_file($datafile))
	{
		die('数据文件不存！');
	}
	$file_type_is_execl = false;
	$file_type_is_text = false;
	$fileParts = pathinfo($_GET['datafile']);
	$fext = strtolower($fileParts['extension']);
	if($fext == 'xls' || $fext == 'xlsx') 
	{
		$file_type_is_execl = true;
		$execl = PHPExcel_IOFactory::load($datafile);
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
		$file = fopen($datafile,'r');
		if(!$file)
		{
			die('读取文件失败');
		}
		$file_text_content = '';
		$rows = 10;
		while(!feof($file))
		{
			$file_text_content .= fgets($file);
			$rows--;
			if($rows<=0) break;
		}
		fclose($file);
		$code = mb_detect_encoding($file_text_content,array('ASCII','GB2312','GBK','UTF-8'));
		//$code = mb_detect_encoding($file_text_content);
		if($code && $code != 'UTF-8')
		{
			$file_text_content = mb_convert_encoding($file_text_content,'UTF-8',$code); 
		}
				
		$smarty->assign('FILE_TEXT_CONTENT',$file_text_content);
	}
	else
	{
		die('文件类型不正确');
	}
	$smarty->assign('FILE_TYPE_IS_EXECL',$file_type_is_execl);
	$smarty->assign('FILE_TYPE_IS_TEXT',$file_type_is_text);
	$smarty->display('Import2.tpl');
}
elseif($step == "3" )
{
	if(empty($_GET['datafile']))
	{
		die( '没有指定导入数据文件！');
		
	}
	$datafile = $targetPath."/{$_GET['datafile']}"; 
	if(!is_file($datafile))
	{
		die('数据文件不存！');
	}
	if(empty($mod->fields)) die('系统错误！模块不支持导入功能。');
	$field_list = Array();
	foreach($mod->fields as $fieldName=>$fieldInfo)
	{
		$field_list[$fieldName] = getTranslatedString($fieldName);
	}
	$fileParts = pathinfo($_GET['datafile']);
	$fext = strtolower($fileParts['extension']);
	$firstline = 'NO';
	if(isset($_POST['firstline'])) $firstline = $_POST['firstline'];
	$worksheet = '';
	if(isset($_POST['worksheet'])) $worksheet = $_POST['worksheet'];
	$separator = ',';
	if(isset($_POST['separator'])) $separator = $_POST['separator'];
	$titleIsFiled = false;
	if(isset($_POST['title_is_filed']) && $_POST['title_is_filed'] == 'YES') $titleIsFiled = true;
	//$startline = 1;
	//if($firstline == 'YES')
	//{
	//	$startline = 2;		
	//}		
	$data_cols = 0;
	$datas = Array();
	$col_infos = Array();
	
	
	if($fext == 'xls' || $fext == 'xlsx') 
	{
		if(empty($worksheet)) die('没有工作表！');
		$execl = PHPExcel_IOFactory::load($datafile);
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
		$file = fopen($datafile,'r');
		if(!$file)
		{
			die('读取文件失败');
		}
		$rows = 10;
		$data_cols = 0;
		//if($firstline == 'YES')
		//{
		//	$row = fgets($file);
		//}	
		$line = 0;
		$code = 0;
		while(!feof($file))
		{
			$lstr = fgets($file);
			if($code==0)
			{
				$code = mb_detect_encoding($lstr,array('ASCII','GB2312','GBK','UTF-8'));
			}
			if($code && $code != 'UTF-8')
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
	
	$smarty->assign('COL_INFOS',$col_infos);
	$smarty->assign('FIELD_LIST',$field_list);
	$smarty->assign('FIRSTLINE',$firstline);
	$smarty->assign('WORKSHEET',$worksheet);
	$smarty->assign('SEPARATOR',$separator);
	$smarty->assign('DATA_COLS',$data_cols);
	$smarty->assign('DATAS',$datas);
	$smarty->display('Import3.tpl');
	
}
elseif($step == 4)
{
	if(empty($_GET['datafile']))
	{
		die( '没有指定导入数据文件！');		
	}
	$datafile = $targetPath."/{$_GET['datafile']}"; 
	if(!is_file($datafile))
	{
		die('数据文件不存！');
	}
	if(empty($mod->fields)) die('系统错误！模块不支持导入功能。');

	$fileParts = pathinfo($_GET['datafile']);
	$fext = strtolower($fileParts['extension']);
	$firstline = 'NO';
	if(isset($_POST['firstline'])) $firstline = $_POST['firstline'];
	$worksheet = '';
	if(isset($_POST['worksheet'])) $worksheet = $_POST['worksheet'];
	$separator = ',';
	if(isset($_POST['separator'])) $separator = $_POST['separator'];
	$startline = 1;
	if($firstline == 'YES') $startline = 2;	
	$data_cols = 0;
	$maxRows = 0;
	$fieldsOpt = Array();
	$cols = array();
	if(isset($_POST['import_cols'])) $cols = $_POST['import_cols'];
	foreach($cols as $col)
	{
		if(isset($_POST['col_field_name_'.$col]))
		{
			$field = $_POST['col_field_name_'.$col];
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
			elseif($fdset[0] == '50')
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
		}	
	}

	if(!empty($cols)&&($fext == 'xls' || $fext == 'xlsx')) 
	{
		if(empty($worksheet)) die('没有工作表！');
		$execl = PHPExcel_IOFactory::load($datafile);
		$sheet = $execl->getSheetByName($worksheet);
		$data_cols = PHPExcel_Cell::columnIndexFromString($sheet->getHighestDataColumn());
		$maxRows = $sheet->getHighestDataRow();	
		for($line=$startline;$line<=$maxRows;$line++)
		{
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
					else
					{
						$data[$fieldsOpt[$col]['name']] = $val;
					}					
				}
			}

			$recordid =  getNewModuleSeq($mod_table);
			$mod->insertOneRecordset($recordid,$data);	
		}			
	}
	elseif(!empty($cols)&&($fext == 'txt' || $fext = 'csv'))
	{
		$file = fopen($datafile,'r');
		if(!$file)
		{
			die('读取文件失败');
		}
		$maxRows = 0;
		$data_cols = 0;
		$code=0;
		if($firstline == 'YES') fgets($file);
		while(!feof($file))
		{
			$lstr = fgets($file);
			if($code==0)
			{
				$code = mb_detect_encoding($lstr,array('ASCII','GB2312','GBK','UTF-8'));
			}
			if($code && $code != 'UTF-8')
			{
				$lstr = mb_convert_encoding($lstr,'UTF-8',$code);
			}			
			$lstr = str_replace("\r","",$lstr);
			$lstr = str_replace("\n","",$lstr);
			$row = explode($separator,$lstr);

			$count = count($row);
			if($count>$data_cols) $data_cols = $count;
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
					else
					{
						$data[$fieldsOpt[$col]['name']] = $val;
					}
					
				}				
			
			
			}
			$recordid =  getNewModuleSeq($mod_table);
			$mod->insertOneRecordset($recordid,$data);	
			$maxRows++;			
		}
		fclose($file);	
	}	
	@unlink($datafile);
	$smarty->assign('DATA_LINES',$maxRows);
	$smarty->display('Import4.tpl');
}
?>