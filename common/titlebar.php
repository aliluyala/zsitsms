<?php
//创建工具条
function createToolsbar($tools=Array())
{
	$toolsbar = Array(
		'create' => 'no',
		'search' => 'no',
		'calendar' => 'no',
		'calculator' => 'no',
		'email' => 'no',
		'sms' => 'no',
		'phone' => 'no',
		'import' => 'no',
		'export' => 'no',
		
	);
	if(is_string($tools) && $tools == 'all')
	{
		foreach($toolsbar as $key=> $v)
		{
			$toolsbar[$key] = 'yes';
		}
	}
	else
	{
		foreach($tools as $item)
		{
			$toolsbar[$item] = 'yes';
		}
	}	
	return $toolsbar;
}
//打开一个工具条按键
function enableToolsbar($toolsbar,$tool)
{
	if(is_array($toolsbar) && is_string($tool))
	{
		$toolsbar[$tool] = 'yes';
	}
	return $toolsbar;
}



?>