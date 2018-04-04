<?php
//字段UI处理函数


/*****************************************************************

 *数据类型
 *1) S	 字符串
 *2) DT 日期时间
 *3) D  日期
 *4) T  时间
 *5) N  整数
 *6) E  枚举类型
 *7) P  密码
 *8) IMG 图像
 *9）F[~4.2]  浮点数~后是

 *****************************************************************/


//从日期时间字符串返回时间戳
//时间字符串格式:2013-07-07 12:12:00
function passerDateTimeString($dateTimeStr)
{
	$tmp1 = explode(' ',trim($dateTimeStr));
	$tmp2 = explode('-',$tmp1[0]);
	$tmp3 = explode(':',$tmp1[1]);
	print_r($tmp2);
	print_r($tmp3);
	return mktime(intval($tmp3[0]),intval($tmp3[1]),intval($tmp3[2]),intval($tmp2[1]),intval($tmp2[2]),intval($tmp3[0]));
}

//从日期字符串返回时间戳
//日期字符串:2013-07-07
function passerDateString($dateStr)
{
	$tmp1 = explode(' ',trim($dateStr));
	$tmp2 = explode('-',$tmp1[0]);
	$tmp3 = explode(':',$tmp1[1]);
	print_r($tmp2);
	print_r($tmp3);
	return mktime(0,0,0,intval($tmp2[1]),intval($tmp2[2]),intval($tmp2[0]));
}

//从时间字符串返回时间戳
//时间字符串:12:00:00
function passerTimeString($timeStr)
{
	$tmp1 = explode(' ',trim($timeStr));
	$tmp2 = explode('-',$tmp1[0]);
	$tmp3 = explode(':',$tmp1[1]);
	return mktime(intval($tmp3[0]),intval($tmp3[1]),intval($tmp3[2]),1,1,1970);
}



//格式化字段值
function formatFieldValue($value,$datatype,$formatstr,$picklist = null)
{
	$out = $value;
	if($datatype == 'S' && isset($formatstr) && !empty($formatstr))
	{

	}
	elseif($datatype == 'N' && isset($formatstr) && !empty($formatstr))
	{
		$out = sprintf($formatstr,$value);
	}
	elseif($datatype == 'E' )
	{
		if(is_array($picklist) && in_array($value,$picklist))
		{
			$out = getTranslatedString($value);
		}
	}
	elseif($datatype == 'DT' && isset($formatstr) && !empty($formatstr))
	{
		//$out = date($formatstr,passerDateTimeString($value));
	}
	elseif($datatype == 'D' && isset($formatstr) && !empty($formatstr))
	{
		//$out = date($formatstr,passerDateString($value));
	}
	elseif($datatype == 'T' && isset($formatstr) && !empty($formatstr))
	{
		//$out = date($formatstr,passerTimeString($value));
	}
	return $out;
}
//获取关联字段
function getAssociateShowValue($module,$showField,$id)
{
	$modClass = "{$module}Module";
	$modClassFile = _ROOT_DIR."/modules/{$module}/{$modClass}.class.php";
	if(!class_exists($modClass))
	{
		require_once($modClassFile);
	}
	if(!class_exists($modClass)) return null;
	$mod = new $modClass();
	$result = $mod->getOneRecordset($id,null,null);
	if(count($result)>0)
	{
		$result = $result[0];
		if(isset($result[$showField])) return  $result[$showField];
	}

	return null;
}

//创建UI对象
//参数 $seting格式 Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
function createFieldUI($name,$seting,$value=null,$picklist=null,$associateTo=null,$oper='edit',$recordid=-1,$module=_MODULE)
{
	global $CURRENT_USER_ID,$CURRENT_IS_ADMIN;
	$ui = Array();
	if(isset($name))
	{
		$ui['name'] = $name;
		$ui['label'] = getTranslatedString($name);
	}
	$ui['UI'] = 1;
	$ui['dataType'] = $seting[1];
	if(isset($seting[0])) $ui['UI'] = $seting[0];
	if(isset($seting[0])) $ui['datetype'] = $seting[1];
	$ui['title'] = '';
	if(isset($seting[3])) $ui['title'] = $seting[3];
	$ui['mandatory'] = false;
	if(isset($seting[2]) && is_bool($seting[2])) $ui['mandatory'] = $seting[2];
	$ui['value'] = '';
	if(!isset($value))
	{
		if($oper == 'create' && isset($seting[4])) $ui['value'] = $seting[4];
	}
	else
	{
		$ui['value'] = $value;
	}
	$ui['inter_value'] = urlencode($ui['value']);
	$ui['edit'] = true;
	if($ui['UI'] == 2 ||
	   $ui['UI'] == 3 ||
	   $ui['UI'] == 4 )
	{
		if(isset($seting[5]) &&  is_numeric($seting[5])) 	$ui['min_len'] = $seting[5];
		if(isset($seting[6]) &&  is_numeric($seting[6])) 	$ui['max_len'] = $seting[6];
	}
	elseif($ui['UI'] == 6)
	{
		if(isset($seting[5])) 	$ui['min'] = $seting[5];
		if(isset($seting[6])) 	$ui['max'] = $seting[6];
	}
	elseif($ui['UI'] == 7)
	{
		$ui['min_len']  = 0;
		$ui['max_len']  = 255;
		$ui['width']    = '100px';
		$ui['regexp']   = '';
		$ui['regtitle'] = '';
		if(isset($seting[5])) 	$ui['min_len']  = $seting[5];
		if(isset($seting[6])) 	$ui['max_len']  = $seting[6];
		if(isset($seting[7])) 	$ui['width']    = $seting[7];
		if(isset($seting[8])) 	$ui['regexp']   = $seting[8];
		if(isset($seting[9])) 	$ui['regtitle'] = $seting[9];
	}
	elseif($ui['UI'] == 8)
	{
		$ui['unit']    = '';
		$ui['decimal'] = '0';
		$ui['min']     = 0;
		$ui['max']     = 0xffffffff;
		$ui['width']   = '100px';

		if(isset($seting[8])) 	$ui['unit']    = $seting[8];
		if(isset($seting[9])) 	$ui['decimal'] = $seting[9];
		if(isset($seting[5])) 	$ui['min']     = $seting[5];
		if(isset($seting[6])) 	$ui['max']     = $seting[6];
		if(isset($seting[7])) 	$ui['width']   = $seting[7];
	}
	elseif($ui['UI']  == 9)
	{
		$ui['rows'] = 5;
		$ui['cols'] = 20;
		if(!empty($seting[5]) &&  is_numeric($seting[5])) 	$ui['rows'] = $seting[5];
		if(!empty($seting[6]) &&  is_numeric($seting[6])) 	$ui['cols'] = $seting[6];
		if($oper == 'detail')
		{
			$ui['value'] = str_replace("\r\n",'<br/>',$ui['value']);
			$ui['value'] = str_replace(" ",'&ensp;',$ui['value']);
		}
	}
	elseif($ui['UI']  == 10)
	{
		if($oper == 'detail')
		{
			$ui['value'] = "<div id=\"ueditor_container_show_{$name}\">{$ui['value']}</div>";
			$ui['value'] .= "<script src=\""._ROOT_URL."pkgs/ueditor/ueditor.parse.min.js\"></script>";
			$ui['value'] .= "<script> $('#ueditor_container_show_{$name}').parent().parent().prev().hide();";
			$ui['value'] .= "uParse('#ueditor_container_show_{$name}', {rootPath: '"._ROOT_URL."pkgs/ueditor/'});</script>";
		}
	}
	elseif($ui['UI']  == 17)
	{
		if($oper == 'create' || $oper == 'copy')
		{
			$modclass = _MODULE.'Module';
			if(!class_exists($modclass))
			{
				require_once(_ROOT_DIR.'/modules/GroupManager/'.$modclass.'.class.php');
			}
			if(class_exists($modclass))
			{
				$mod = new $modclass();
				$ui['value'] = $mod->autoCompleteFieldValue($name,$seting[4]);
			}
		}
	}
	elseif($ui['UI'] == 20||
		   $ui['UI'] == 21)
	{
		if($oper == 'edit' || $oper == 'create' || $oper == 'copy')
		{
			$ui['options']  = Array();
			foreach($picklist as $val)
			{
				$ui['options'][$val] = getTranslatedString($val);
			}
		}
		else
		{
			$ui['value'] = getTranslatedString($ui['value']);
		}
	}
	elseif($ui['UI'] == 22)
	{
		if($oper == 'edit' || $oper == 'create' || $oper == 'copy')
		{
			$ui['options']  = Array();
			foreach($picklist as $key=>$val)
			{
				$ui['options'][$key] = getTranslatedString($key);
			}
		}
		else
		{
			if(isset($picklist[$ui['value']]))
			{
				$ui['value'] = getTranslatedString($picklist[$ui['value']]);
			}
			else
			{
				$ui['value'] = getTranslatedString($ui['value']);
			}
		}

	}
	elseif($ui['UI'] == 23)
	{
		$valarr = explode(',',$ui['value']);
		if($oper == 'edit' || $oper == 'create' || $oper == 'copy')
		{
			$ui['options']  = Array();
			foreach($picklist as $val)
			{
				$ui['options'][$val] = array('label'=>getTranslatedString($val),'checked'=>false);
				if(in_array($val,$valarr)) $ui['options'][$val]['checked'] = true;
			}
		}
		else
		{
			$ui['value'] = '';
			foreach($valarr as $v)
			{
				if(empty($ui['value']))
				{
					$ui['value'] = getTranslatedString($v);
				}
				else
				{
					$ui['value'] .= ' | '.getTranslatedString($v);
				}
			}
		}

	}
	elseif($ui['UI'] == 25)
	{
		if($oper == 'detail')
		{
			$ui['value'] = getTranslatedString($ui['value']);
		}
		elseif(!empty($picklist) && !empty($picklist['picklist_define']) && count($picklist['picklist_define'])>=1)
		{
			$ui['picklist_group_field'] = $picklist['picklist_define'][0];

			$ui['options'] = Array();
			foreach($picklist as $group_name => $group)
			{
				if($group_name != 'picklist_define')
				{
					$ui['options'][$group_name] = Array();
					foreach($group as $val)
					{
						$ui['options'][$group_name][$val] = getTranslatedString($val);
					}
				}
			}
		}
	}
	elseif($ui['UI'] == 26)
	{
		if(!empty($picklist) && !empty($picklist['picklist_define']) && count($picklist['picklist_define'])>=4)
		{
			$ui['picklist_group_field'] = $picklist['picklist_define'][0];
			$ui['picklist_table_name'] = $picklist['picklist_define'][1];
			$ui['picklist_items_field'] = $picklist['picklist_define'][2];
			$ui['picklist_filter_field'] = $picklist['picklist_define'][3];
		}
	}
	elseif($ui['UI'] == 27)
	{
		$ui['module'] = _MODULE;
		if($oper == 'create' && empty($ui['value']) && isset($seting[4]))
		{
			$ui['value'] = $seting[4];
		}
		$ui['options'] = array();
		global $APP_ADODB;
		$result = $APP_ADODB->Execute("select save_value,show_value from dropdown where module_name='{$ui['module']}' and field='{$name}';");
		if($oper == 'detail')
		{
			while(!$result->EOF)
			{
				if($result->fields['save_value']==$ui['value'])
				{
					$ui['value'] = $result->fields['show_value'];
					break;
				}
				$result->MoveNext();
			}

		}
		else
		{
			while($result && !$result->EOF)
			{
				$ui['options'][] = array('value'=>$result->fields['save_value'],'show'=>$result->fields['show_value']);
				$result->MoveNext();
			}
		}
	}
	elseif($ui['UI'] == 30)
	{
		//$ui['value'] = formatFieldValue($ui['value'],$seting[1],'Y-m-d');
	}
	elseif($ui['UI'] == 50 || $ui['UI'] == 51 || $ui['UI'] == 52 )
	{
		$ui['associate_module'] = $associateTo[1];
		$ui['associate_action'] = $associateTo[2];
		$ui['show_field'] = $associateTo[4];
		$ui['list_filter_field'] = '';
		$ui['list_filter_value'] = '';
		$ui['list_fields'] = $associateTo[4];
		if(isset($associateTo[5]) && is_array($associateTo[5]))
		{
			foreach($associateTo[5] as $filter_field => $filter_value)
			{
				$ui['list_filter_field'] = $filter_field;
				$ui['list_filter_value'] = $filter_value;
				break;
			}
		}
		if(isset($associateTo[6]) && is_array($associateTo[6]))
		{
			foreach($associateTo[6] as $f)
			{
				$ui['list_fields'] .= ','.$f;
			}

		}
		if( isset($associateTo[1]) && isset($associateTo[4]))
		{
			$ui['show_value'] = getAssociateShowValue($associateTo[1],$associateTo[4],$ui['value']);
		}
	}
	elseif($ui['UI'] == 55)
	{
		if($oper == 'detail')
		{
			if($ui['value']>=1000000)
			{
				$ui['associate_module'] = 'GroupManager';
				$ui['show_value'] = getAssociateShowValue('GroupManager','name',$ui['value']);
			}
			else
			{
				$ui['associate_module'] = 'User';
				$ui['show_value'] = getAssociateShowValue('User','user_name',$ui['value']);
			}
			$ui['associate_action'] = 'detailView';
		}
		else
		{
			$userids  = $CURRENT_IS_ADMIN ? NULL : getRecordset2UsersPermission("User");
			$groupids = $CURRENT_IS_ADMIN ? NULL : getRecordset2GroupsPermission("GroupManager");
			if(empty($ui['value']))
			{
				global $CURRENT_USER_ID;
				$ui['value'] = $CURRENT_USER_ID;
			}
			$ui['groups'] = Array();
			$ui['users'] = Array();
			if(!class_exists('GroupManagerModule'))
			{
				require_once(_ROOT_DIR."/modules/GroupManager/GroupManagerModule.class.php");
			}
			if(class_exists('GroupManagerModule'))
			{
				$gmod = new GroupManagerModule();
				$gcount = $gmod->getListQueryRecordCount(Array(),Array(),$userids,$groupids);
				$garr = $gmod-> getListQueryRecord(Array(),Array(),'','',$userids,$groupids,0,$gcount);
				if(!$garr) $garr = Array();
				foreach($garr as $row)
				{
					$ui['groups'][$row['id']] = $row['name'];
				}
			}
			if(!class_exists('UserModule'))
			{
				require_once(_ROOT_DIR."/modules/User/UserModule.class.php");
			}
			if(class_exists('UserModule'))
			{
				$umod = new UserModule();
				$ucount = $umod->getListQueryRecordCount(Array(),Array(),$userids,$groupids);
				$uarr = $umod-> getListQueryRecord(Array(),Array(),'','',$userids,$groupids,0,$ucount);
				if(!$uarr) $uarr = Array();
				foreach($uarr as $row)
				{
					$ui['users'][$row['id']] = $row['user_name'];
				}
			}
		}
	}
	elseif( $ui['UI'] == 60 )
	{
		if($oper == 'detail')
		{
			$zsconf = require(_ROOT_DIR.'/config/zswitch.conf.php');
			$phonenumber = trim($ui['value']);
			if($zsconf['agent_type'] == 'sipurl')
			{
				$ui['value'] = "<a href='javascript:zswitch_callcenter_click_call_b(\"{$phonenumber}\");' title='点击呼叫:{$phonenumber}' style='color:#1E90FF'>{$phonenumber}<a/>";
			}
			elseif($zsconf['agent_type'] == 'callback_hide')
			{

				$ui['value'] = "<a href='javascript:zswitch_callcenter_click_call_a(\"{$module}\",\"{$recordid}\",\"{$name}\",\"{$phonenumber}\");' title='点击呼叫:{$phonenumber}' style='color:#1E90FF'>{$phonenumber}<a/>";

			}
			else
			{
				$ui['value'] = "<a href='javascript:zswitch_callcenter_click_call(\"{$phonenumber}\");' title='点击呼叫:{$phonenumber}' style='color:#1E90FF'>{$phonenumber}<a/>";
			}

		}
	}
	elseif($ui['UI'] == 70)
	{
		$ui['min_len'] = 0;
		$ui['max_len'] = 255;
		if(isset($seting[5]) &&  is_numeric($seting[5])) 	$ui['min_len'] = $seting[5];
		if(isset($seting[6]) &&  is_numeric($seting[6])) 	$ui['max_len'] = $seting[6];
	}
	elseif($ui['UI'] == 110)
	{
		$ui['width']    = '70px';
		$ui['validity_url']   = '';
		$ui['mode'] = 'edit';
		$ui['recordid'] = $recordid;
		$ui['source_module'] = $module;
		if(isset($seting[5])) 	$ui['validity_url']  = $seting[5];
		if(isset($seting[6])) 	$ui['width']  = $seting[6];
		if(isset($seting[7]))
		{
			if($oper == 'edit' && $seting[7] == 'DisableModify')
			{
				$ui['mode']  = 'view';;
			}
		}
	}
	return $ui;
}
//允许或禁止编辑
function enableEditUI(&$ui,$enable=true)
{
	$ui['edit'] = $enable;

}



?>