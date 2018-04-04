<?php
/*
详情视图处理函数
*/
//详细视图按钮
function createDetailviewButtons($buts = Array())
{
	
	$buttons = Array(
		'return' => false,
		'back' => false,
		'next' => false,
		'edit' => false,
		'delete' => false,
		'copy' => false,
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

//创建被关联表
function createDetailviewAssociateTabs($associateBy,$id)
{
	$out = Array();
	if(!isset($associateBy) || !is_array($associateBy) || !isset($id)) return $out;	
	foreach($associateBy as $name => $ass)
	{
		$out[$name] = Array();
		$out[$name]['label'] = getTranslatedString($name);
		$listfields = '';
		$count = count($ass);
		if($count>2)
		{			
			for($x = 2;$x<$count;$x++)
			{
				if($listfields == '')
					$listfields = $ass[$x];
				else
					$listfields .= ','.$ass[$x];	
			}
		}
		$out[$name]['url'] = "index.php?module={$ass[0]}&action=associateListView&associateField={$ass[1]}&fieldvalue={$id}&listfields={$listfields}";	
	}
	return $out;
}


//格式化详情视图数据
function formatDetailviewDatas($data,$module,$keyname,$fields,$modFields,$defaultColumns,$picklist,$associateTo,$blocks)
{
	$datas['id'] = -1;
	if(isset($data[$keyname])) $datas['id'] = $data[$keyname];
	if(isset($blocks) && is_array($blocks) && count($blocks)>0 )
	{
		$datas['have_block'] = true;
		$datas['blocks'] = Array();		
		foreach($blocks as $blockName => $blockSet)
		{
			$block = Array();
			$block['label'] = getTranslatedString($blockName);
			$block['cols'] = $blockSet[0];
			$block['active'] = $blockSet[1];
			$block['datas'] = Array();
			$bcol = 0;
			$brow = Array();
			foreach($blockSet[2] as $field_name)
			{
				if(!isset($fields[$field_name])) continue;
				
				$value = ' ';
				if(isset($data[$field_name])) $value = $data[$field_name];
				$pick = null;
				if(isset($picklist[$field_name])) $pick = $picklist[$field_name];
				$associate = null;
				if(isset($associateTo[$field_name])) $associate = $associateTo[$field_name];

				$field = createFieldUI($field_name,$fields[$field_name],$value,$pick,$associate,'detail',$datas['id'],$module);				
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
			$datas['blocks'][$blockName] = $block;
		}
	}
	else
	{	
		$datas['have_block'] = false;
		$datas['cols'] = $defaultColumns;
		$datas['datas'] = Array();
		$bcol = 0;
		$brow = Array();
		foreach($fields as $field_name => $field_info)
		{
				//if(!isset($data[$field_name])) continue;
				
				$value = ' ';
				if(isset($data[$field_name])) $value = $data[$field_name];
				$pick = null;
				if(isset($picklist[$field_name])) $pick = $picklist[$field_name];
				$associate = null;
				if(isset($associateTo[$field_name])) $associate = $associateTo[$field_name];
				$field = createFieldUI($field_name,$field_info,$value,$pick,$associate,'detail',$datas['id'],$module);				
				enableEditUI($field,in_array($field_name,$modFields));				

				$brow[]=$field;
				if($field['UI'] != '101') $bcol++;
				if($bcol >= $datas['cols'])
				{
					$datas['datas'][] = $brow;
					$brow = Array();
					$bcol = 0;
				}							
		}
		if(count($brow)<$datas['cols']) $datas['datas'][] = $brow;
	}
	return $datas;	
}


?>