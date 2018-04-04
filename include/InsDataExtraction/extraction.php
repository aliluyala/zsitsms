<?php
/**
 * 项目：数据提取函数库
 * 文件名:         extraction.php
 * 版权所有：      2015 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *
 * 从文本(html等)数据中提取数据的函数集合。
 * 
 *     此函数库在不断丰富完善中。你如果你有兴趣提供其它特定的提取方法，请写成函数
 * 放入此文件，并在此签上大名。  
 * 
 * 注意：此函数库的优势是"速度"，因此文本分析方法以正则表达式和字符串查找为主，
 *       并配合优秀的查找算法。如果速度没有PHP的XML、DOM等扩展库快请不要放入。
 **/	

 
 
 
 
/**
 * 函数: extraction_html_inputs 同名标签会覆盖。
 * 功能:从html文本提取input元素
 *
 * 参数:
 * @html 必需。原始html文本,UTF-8编码
 * 
 * 返回值:
 * 成功返回数组,失败返回false。
 *   Array(
 *			'name' => Array('value'=>'','type'=>''),
 *			.....
 *	      )
 * 
 **/ 
function extraction_html_inputs($html)
{
	if(preg_match_all('/<input[^>]+name="(.+?)"[^>]+>/',$html,$out,PREG_SET_ORDER))
	{
		$result = array();
		foreach($out as $item)
		{
			$result[$item[1]] = array();
			$result[$item[1]]['type'] = '';
			if(preg_match('/type\s*=\s*"(.*?)"/',$item[0],$val))
			{
				$result[$item[1]]['type'] = $val[1];
			}
			$result[$item[1]]['value'] = '';
			if(preg_match('/value\s*=\s*"(.*?)"|value\s*=\s*(.*?)\s+|value\s*=\s*(.*?)>/',$item[0],$val))
			{	
				if(!empty($val[1]))
				{
					$result[$item[1]]['value'] = $val[1];
				}
				elseif(!empty($val[2]))
				{
					$result[$item[1]]['value'] = $val[2];
				}
				elseif(!empty($val[3]))
				{
					$result[$item[1]]['value'] = $val[3];
				}				
			}
			$result[$item[1]]['checked'] = '';
			if(preg_match('/checked\s*=\s*"(.*?)"/',$item[0],$val))
			{
				$result[$item[1]]['checked'] = $val[1];
			}			
		}
		return $result;
	}

	return false;
}


/**
 * 函数: extraction_html_inputs_a,同名标签不会覆盖。
 * 功能:从html文本提取input元素
 *
 * 参数:
 * @html 必需。原始html文本,UTF-8编码
 * 
 * 返回值:
 * 成功返回数组,失败返回false。 
 *   Array(
 *			Array('name'=>'','value'=>'','type'=>''),
 *			.....
 *	      )
 *  
 **/ 
function extraction_html_inputs_a($html)
{
	if(preg_match_all('/<input[^>]+>/',$html,$out,PREG_SET_ORDER))
	{
		$result = array();
		$count = 0;
		
		foreach($out as $item)
		{
			$result[$count] = array();
			$name = '';
			if(preg_match('/name\s*=\s*"(.*?)"/',$item[0],$val))
			{
				$result[$count]['name'] = $val[1];
			}		
			
			$result[$count]['type'] = '';
			if(preg_match('/type\s*=\s*"(.*?)"/',$item[0],$val))
			{
				$result[$count]['type'] = $val[1];
			}
			$result[$count]['value'] = '';
			if(preg_match('/value\s*=\s*"(.*?)"/',$item[0],$val))
			{
				$result[$count]['value'] = $val[1];
			}
			$result[$count]['checked'] = '';
			if(preg_match('/checked\s*=\s*"(.*?)"/',$item[0],$val))
			{
				$result[$count]['checked'] = $val[1];
			}	
			$count++;	
		}
		return $result;
	}

	return false;
}

/**
 * 函数: extraction_html_selects 
 * 功能:从html文本提取select元素
 *
 * 参数:
 * @html 必需。原始html文本,UTF-8编码
 * 
 * 返回值:
 * 成功返回键数组,失败返回false。 
 *   Array(
 *			Array('name'=>'','value'=>''),
 *			.....
 *	      )
 *   
 **/ 
function extraction_html_selects($html)
{
	if(empty($html)) return false;
	$result = array();
	$offset = 0;
	while(true)
	{
		$st = strpos($html,'<select',$offset);
		if($st === false) break;
		$end = strpos($html,'select>',$st);
		if($end === false) break;
		$offset = $end+7;
		
		$tmpstr = substr($html,$st,$offset-$st);
		$item = array();
		$item['name'] = '';
		if(preg_match('/<select[^>]+name="(.*?)"[^>]+>/',$tmpstr,$out))
		{
			$item['name'] = $out[1];
		}
		$item['value'] = '';
		if(preg_match('/<option\s+value="([^>]*?)"\s+selected\s*>/',$tmpstr,$out))
		{
			$item['value'] = $out[1];
		}		
		$result[]=$item;
		
	}
	return 	$result;
}


/**
 * 函数: extraction_urlparam_replace 
 * 功能:用数组内容替换url中参数值
 *
 * 参数:
 *
 * @url     必需。原始url文本
 * @params   必需。二维数组，'name'：参数名，'value'：参数值
 * 
 * 返回值:
 * 返回替换后url。 
 *   
 **/ 
 function extraction_urlparam_replace($url,$params=array())
 {
	if(empty($url)) return false;
	if(empty($params)) return $url;
	foreach($params as  $p)
	{	
		
		if( !array_key_exists('value',$p) || !array_key_exists('name',$p) )
		{
			continue;
		}
		if( array_key_exists('type',$p) && $p['type'] == 'button'  )
		{
			continue;
		}
		$name= $p['name'];
		$value = $p['value'];
		$count = preg_match_all('/&('.$name.'=.*?&)/',$url,$out,PREG_SET_ORDER);
		if($count>0)
		{
			foreach($out as $src)
			{
				$repl = $name.'='.$value.'&';
				$url = str_replace($src[1],$repl,$url);
			}
		}			
	}
	return $url; 
 }


?>