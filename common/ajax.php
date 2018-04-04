<?php
function return_ajax($type,$data)
{
	$out = Array();
	$out['type'] = $type;
	$out['data'] = $data;
	echo json_encode($out);
	
}

function return_ajaxA($type,$data)
{
	function urlencode_array(&$arr)
	{
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) 
		{
			 die('possible deep recursion attack');
		}
		foreach ($arr as $key => $value) 
		{
			if (is_array($value)) 
			{		
				urlencode_array($arr[$key]);			
			} 
			else 
			{
				$arr[$key] = urlencode($value);	
			}
			if(is_string($key))
			{
				$new_key = urlencode($key);
				if ($new_key != $key)
				{
					$arr[$new_key] = $arr[$key];
					unset($arr[$key]);
				}
			}
			
		}	
	}
	
	if(!is_string($type)) return ;
	$out = Array();
	$out['type'] = urlencode($type);
	if(is_array($data))
	{
		urlencode_array($data);
		$out['data'] = $data;
	}
	else
	{
		$out['data'] = urlencode($data);	
	}
	
	echo json_encode($out);
	
}
?>