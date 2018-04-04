<?php
//创建编辑视图UI

function createEditViewUI($module,$fields,$editFields,$modFields,$defaultColumns,$blocks,$associateTo,$picklist,$data,$oper='edit')
{

	$viewUIs = Array();
	if(isset($blocks) && is_array($blocks) && count($blocks)>0 )
	{
		$viewUIs['have_block'] = true;
		$viewUIs['blocks'] = Array();
		foreach($blocks as $block_name => $block_info)
		{
			$block = Array();
			$block['label'] = getTranslatedString($block_name);
			$block['cols'] = $block_info[0];
			$block['active'] = $block_info[1];
			$block['datas'] = Array();
			$bcol = 0;
			$brow = Array();
			foreach($block_info[2] as $field_name)
			{
				if(!in_array($field_name,$editFields) || !isset($fields[$field_name])) continue;
				$value = null;
				if(isset($data[$field_name])) $value = $data[$field_name];
				$pick = null;
				if(isset($picklist[$field_name])) $pick = $picklist[$field_name];
				$associate = null;
				if(isset($associateTo[$field_name])) $associate = $associateTo[$field_name];
				$field = createFieldUI($field_name,$fields[$field_name],$value,$pick,$associate,$oper,$data['id']);				
				enableEditUI($field,in_array($field_name,$modFields));

				$brow[]=$field;
				if($field['UI'] != '101') $bcol++;
				if($bcol >= $block['cols'])
				{
					$block['datas'][] = $brow;
					$brow = Array();
					$bcol = 0;
				}				
				
			}
			if(count($brow)<$block['cols']) $block['datas'][] = $brow;
			$viewUIs['blocks'][$block_name] = $block;			
		}		
	}
	else
	{
		$viewUIs['have_block'] = false;
		$viewUIs['cols'] = $defaultColumns;
		$viewUIs['datas'] = Array();
		$bcol = 0;
		$brow = Array();
		
		foreach($editFields as $field_name)
		{
			
		  	if(!isset($fields[$field_name])) continue;
			$value = null;
			if(isset($data[$field_name])) $value = $data[$field_name];
			$pick = null;
			if(isset($picklist[$field_name])) $pick = $picklist[$field_name];					
			$associate = null;
			if(isset($associateTo[$field_name])) $associate = $associateTo[$field_name];
			$field = createFieldUI($field_name,$fields[$field_name],$value,$pick,$associate,$oper,$data['id']);				
			enableEditUI($field,in_array($field_name,$modFields));

			$brow[]=$field;
			if($field['UI'] != '101') $bcol++;
			
			if($bcol >= $viewUIs['cols'])
			{
				$viewUIs['datas'][] = $brow;
				$brow = Array();
				$bcol = 0;
			}						
		}
		if(count($brow)<=$viewUIs['cols']) $viewUIs['datas'][] = $brow;	
	}
	
	return $viewUIs;
}

//格式化保存sql语名
function formatDataToUpdateSetSql($data,$fields,$editFields)
{
	$sql = '';
	foreach($data as $field=>$val)
	{
		if(in_array($field,$editFields) && isset($fields[$field]))
		{
			$tmp = '';
			if($fields[$field][1] == 'N')
			{
				if(empty($val) && $val != '0' && $val !=0)
				{
					$tmp = "{$field}=null";
				}
				else
				{
					$tmp = "{$field}={$val}";
				}	
			}
			else
			{
				$tmp1 = str_replace("'","\'",$val);
				$tmp = "{$field}='{$tmp1}'";
			}
			if($sql == '') $sql = $tmp;
			else $sql .= ','.$tmp; 
		}
	}
	return $sql;	
}

//格式化保存sql语名
function formatDataToInsertValuesSql($data,$fields,$editFields)
{
	$sql1 = '';
	$sql = '';
	foreach($editFields as $field)
	{
		$tmp = 'null';
		$add = false;
		if(array_key_exists($field,$data) && isset($fields[$field]))
		{			
			if($fields[$field][1] == 'N')
			{
				if(empty($data[$field]) && $data[$field] != '0' && $data[$field] != 0 ) $tmp ='null';
				else $tmp = "{$data[$field]}";
			}
			else
			{
				$tmp1 = str_replace("'","\'",$data[$field]);
				$tmp = "'{$tmp1}'";
			}
			$add = true;
		}
		elseif($field == 'id')
		{
			$tmp = "{$data[$field]}";
			$add = true;
		}
		if($add)
		{
			if($sql == '') $sql = $tmp;
			else $sql .= ','.$tmp; 
		
			if($sql1 == '') $sql1 = $field;
			else $sql1 .= ','.$field;
		}
	}
	return "({$sql1}) values({$sql})";	
}
?>