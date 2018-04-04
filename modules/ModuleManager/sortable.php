<?php
global $APP_ADODB,$CURRENT_IS_ADMIN;
if(!$CURRENT_IS_ADMIN)
{
	die('你无权修改！');	
}

if(!empty($_POST['sort']))
{
	$so = explode(',',$_POST['sort']);
	foreach($so as $item)
	{
		if(!empty($item))
		{
			$it = explode(':',$item);
			$APP_ADODB->Execute("update modules set seq ={$it[1]} where id={$it[0]}");
		}		
	}
}



?>