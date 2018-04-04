<?php

//创建列表视图表头按键
function createListViewButtons($buts = Array())
{
	$buttons = Array(
		'delete' => false,
		'modify' => false,
		'sendmail' => false,
		'sendsms' => false,
		'sendfax' => false,
	);
	if(is_string($buts) && $buts == 'all')
	{
		foreach($buttons as $key=> $v)
		{
			$buttons[$key] = true;
		}
	}
	else
	{
		foreach($buts as $item)
		{
			$buttons[$item] = true;
		}
	}
	return $buttons;
}

//创建列表视图过滤列表
function createListViewFilters($userid,$groupid,$module)
{
	global $CURRENT_USER_ID,$CURRENT_USER_GROUPID,$APP_ADODB;
	if(!isset($userid) || !isset($grouupid))
	{
		$userid = $CURRENT_USER_ID;
		$groupid = $CURRENT_USER_GROUPID;
	}
	if(!isset($module)) $module = _MODULE;
	$sqlstr  = "select * from filters where module_name='{$module}' and  userid ={$CURRENT_USER_ID};";
	$filters = Array(-1 => getTranslatedString('全部'));
	$filterdb = $APP_ADODB->Execute($sqlstr);
	while($filterdb && !$filterdb->EOF)
	{
		$filters[$filterdb->fields['id']] = getTranslatedString($filterdb->fields['name']);
		$filterdb->MoveNext();
	}
	return $filters;
}

// 获取过滤条件
function getListViewFilterWhere($filterid)
{
	global $APP_ADODB;
	if($filterid<0) return Array();
	$sql= "select filter_where from filters where id={$filterid} ;";
	$filterdb =  $APP_ADODB->Execute($sql);
	if(!$filterdb || $filterdb->EOF) return Array();
	$filterdb->fields['filter_where'];
	return json_decode(urldecode($filterdb->fields['filter_where']),true);
}

//创建列表表头
function createListViewHeaders($listFields,$orderbyFields,$defaultOrderby,$defaultOrder,$currentOrderBy,$currentOrder)
{

	$orderby = '';
	$order = 'NONE';
	if(isset($currentOrderBy) && is_array($orderbyFields) &&
			 in_array($currentOrderBy,$orderbyFields))
	{
		$orderby = $currentOrderBy;
		if(isset($currentOrder))  $order = $currentOrder;
	}
	elseif(is_array($orderbyFields) && isset($defaultOrderby) &&
		   in_array($defaultOrderby,$orderbyFields) && isset($defaultOrder))
	{
		//$orderby = $defaultOrderby;
		//$order = $defaultOrder;
	}
	$headers = Array();
	if(!isset($listFields) || !is_array($listFields)) return false;
	foreach($listFields as $field)
	{
		$headers[$field] = Array();
		$headers[$field]['label'] = getTranslatedString($field);
		$headers[$field]['allow_order'] = false;
		if(isset($orderbyFields) && in_array($field,$orderbyFields))
		{
			$headers[$field]['allow_order'] = true;
			$headers[$field]['order'] = 'NONE';
			if( $orderby == $field)
			{
				$headers[$field]['order'] =  $order;
			}
		}
	}
	return 	$headers;
}

//格式化sql条件
function formatSqlWhere($where,$fields)
{
	if(!isset($where) || !is_array($where)) return false;
	if(!isset($fields) || !is_array($fields)) return false;
	$sqlwhere = '';
	foreach($where as  $w)
	{
		if(isset($w[0]) && isset($w[1]) && isset($w[2]) && isset($w[3]))
		{
			$field = $w[0];
			$cond  = $w[1];
			$val   = $w[2];
			$link  = $w[3];

			if($cond == 'like_start')
			{
				$cond = 'like';
				$val = $val.'%';
			}
			elseif($cond == 'like_end')
			{
				$cond = 'like';
				$val = '%'.$val;
			}
			elseif($cond == 'like_contain')
			{
				$cond = 'like';
				$val = '%'.$val.'%';
			}
			elseif($cond == 'like_no_contain')
			{
				$cond = 'not like';
				$val = '%'.$val.'%';
			}

			$skip = false;
			if(isset($fields[$field]) && isset($fields[$field][1]))
			{
				if($fields[$field][1] != 'N')
				{
					$tmp1 = str_replace("'","\'",$val);
					$val = "'{$tmp1}'";
				}
				elseif( empty($val) && $val != '0' && $val != 0)
				{
					$skip =true;
				}

			}

			if(!$skip)
			{
				if(empty($link))
				{
					$sqlwhere .= "({$field} {$cond} {$val})";
				}
				else
				{
					$sqlwhere .= "({$field} {$cond} {$val}){$link}";
				}
			}
		}
	}
	return $sqlwhere;
}

//格式化列表数据
function formatListDatas($data,$module,$keyName,$fields,$listFields,$enteryField = null,$picklist = Array(),$associateTo = Array())
{
	$listData = Array();
	foreach($data as $row)
	{
		//print_r($row);
		$newRow = Array();
		$id = -1;
		if(isset($row[$keyName]))
		{
			$id = $row[$keyName];
			foreach($listFields as $fieldName )
			{
				if(array_key_exists($fieldName,$row))
				{
					//echo "{$fieldName}=>{$row[$fieldName]}";
					$newRow[$fieldName] = Array();

					if(isset($fields[$fieldName]) && isset($fields[$fieldName][1]) && isset($fields[$fieldName][2]))
					{
						if(isset($picklist[$fieldName]))
						{
							if($fields[$fieldName][0] == '22')
							{
								$newRow[$fieldName]['value'] = $picklist[$fieldName][$row[$fieldName]];
							}
							else
							{
								$newRow[$fieldName]['value'] = formatFieldValue($row[$fieldName],$fields[$fieldName][1],null,$picklist[$fieldName]);
							}
						}
						elseif($fields[$fieldName][0] == '9')
						{
							$newRow[$fieldName]['value'] = mb_substr($row[$fieldName],0,40,'utf-8');
						}
						elseif($fields[$fieldName][0] == '27')
						{
							global $APP_ADODB;
							$sql = "select show_value from dropdown where module_name='{$module}' and field='{$fieldName}' and save_value='{$row[$fieldName]}';";
							$result = $APP_ADODB->Execute($sql);
							if($result && !$result->EOF)
							{
								$newRow[$fieldName]['value'] = $result->fields['show_value'];
							}
							else
							{
								$newRow[$fieldName]['value'] =  $row[$fieldName];
							}		
						}
						else
						{
							$newRow[$fieldName]['value'] = formatFieldValue($row[$fieldName],$fields[$fieldName][1],null);
						}
					}
					else
					{
						$newRow[$fieldName]['value'] = $row[$fieldName];
					}
					$newRow[$fieldName]['have_associate'] = false;
					//$newRow[$fieldName]['associate_title'] = '';
					if($enteryField == $fieldName)
					{
						//详情入口字段
						$url = "index.php?module={$module}&action=detailView&recordid={$id}";
						$url .= "&return_module={$module}&return_action=index";
						$newRow[$fieldName]['associate_to'] = "javascript:zswitch_load_client_view('{$url}')";
						$newRow[$fieldName]['have_associate'] = true;
						$newRow[$fieldName]['title'] = '';
						//$newRow[$fieldName]['associate_title'] = getTranslatedString('ASSOCIATE_TO_TITLE_DETAILVIEW_ENRTY');
					}
					elseif(isset($associateTo[$fieldName]) && is_array($associateTo[$fieldName]))
					{
						//关联字段
						if( $associateTo[$fieldName][0] == "MODULE")
						{
							//模块关联
							$url = "index.php?module={$associateTo[$fieldName][1]}&action={$associateTo[$fieldName][2]}&recordid={$row[$fieldName]}";
							$url .= "&return_module={$module}&return_action=index";
							$newRow[$fieldName]['associate_to'] = "javascript:zswitch_load_client_view('{$url}')";
							$associateModClass = "{$associateTo[$fieldName][1]}Module";
							$newRow[$fieldName]['have_associate'] = true;
							$newRow[$fieldName]['value']=getAssociateShowValue($associateTo[$fieldName][1],$associateTo[$fieldName][4],$row[$fieldName]);
							//$newRow[$fieldName]['associate_title'] = getTranslatedString('ASSOCIATE_TO_MODULE_TITLE_'.$fieldName);
						}
						elseif( $associateTo[$fieldName][0] == 'URL')
						{
							$newRow[$fieldName]['associate_to'] = "javascript:window.open({$row[$fieldName]})";
							$newRow[$fieldName]['have_associate'] = true;
							//$newRow[$fieldName]['associate_title'] = getTranslatedString('ASSOCIATE_TO_URL_TITLE_'.$fieldName);
						}
						elseif($associateTo[$fieldName][0] == 'SCRIPT')
						{
							$newRow[$fieldName]['associate_to'] = $associateTo[$fieldName][1];
							$newRow[$fieldName]['have_associate'] = true;
							//$newRow[$fieldName]['associate_title'] = getTranslatedString('ASSOCIATE_TO_SCRIPT_TITLE_'.$fieldName);
						}
						$newRow[$fieldName]['title'] = '';
					}
					elseif($fields[$fieldName][0] == 55)
					{
						if($newRow[$fieldName]['value'] >= 1000000)
						{
							$url = "index.php?module=GroupManager&action=detailView&recordid={$newRow[$fieldName]['value']}";
							$newRow[$fieldName]['value']=getAssociateShowValue('GroupManager','name',$row[$fieldName]);
						}
						else
						{
							$url = "index.php?module=User&action=detailView&recordid={$newRow[$fieldName]['value']}";
							$newRow[$fieldName]['value']=getAssociateShowValue('User','user_name',$row[$fieldName]);
						}
						$url .= "&return_module={$module}&return_action=index";
						$newRow[$fieldName]['associate_to'] = "javascript:zswitch_load_client_view('{$url}')";
						$newRow[$fieldName]['title'] = '';
						$newRow[$fieldName]['have_associate'] = true;
					}
					elseif($fields[$fieldName][0] == 60)
					{
						$zsconf = require(_ROOT_DIR.'/config/zswitch.conf.php');
						$phonenumber = trim($newRow[$fieldName]['value']);
						if($zsconf['agent_type'] == 'sipurl')
						{
							$newRow[$fieldName]['associate_to'] = "javascript:zswitch_callcenter_click_call_b('{$phonenumber}');";
						}
						elseif($zsconf['agent_type'] == 'callback_hide')
						{
							$newRow[$fieldName]['associate_to'] = "javascript:zswitch_callcenter_click_call_a(\"{$module}\",\"{$id}\",\"{$fieldName}\",\"{$phonenumber}\");";	
						}
						else
						{					
							$newRow[$fieldName]['associate_to'] = "javascript:zswitch_callcenter_click_call('{$phonenumber}');";
						}
						$newRow[$fieldName]['have_associate'] = true;
						$newRow[$fieldName]['title'] = "点击呼叫:{$phonenumber}";
					}
					elseif($fields[$fieldName][0] == 61)
					{
						$phonenumber = trim($newRow[$fieldName]['value']);
						$newRow[$fieldName]['associate_to'] = "javascript:zswitch_callcenter_click_call_a('{$module}',{$id},'{$fieldName}','{$phonenumber}');";
						$newRow[$fieldName]['have_associate'] = true;
						$newRow[$fieldName]['title'] = "点击呼叫:{$phonenumber}";
					}
				}
			}
			$listData[$id] = $newRow;
			//print_r($newRow);
		}
	}
	return $listData;
}


//创建搜索UI
function createListSearchUI($searchFields,$fields,$picklists = Array(),$associateTos = Array())
{
	$uis = Array();
	foreach($searchFields as $field)
	{
		if(isset($fields[$field]))
		{
			$pick = null;
			if(isset($picklists[$field])) $pick = $picklists[$field];
			$associate = null;
			if(isset($associateTos[$field])) $associate = $associateTos[$field];
			$uis[$field]=createFieldUI($field,$fields[$field],null,$pick,$associate);
		}
	}
	return $uis;
}

?>