<?php
global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
$home = Array();
$home['cols'] = 0;
$home['rows'] = 0;
$home['width'] = 0;
$home['height'] = 0;
$home['cells'] = Array();

$sql = "select * from homes where userid={$CURRENT_USER_ID};";
$result = $APP_ADODB->Execute($sql);
if($result->EOF)
{
	$sql = "select * from homes where default_home='YES' limit 1;";
	$result = $APP_ADODB->Execute($sql);	
}

if(!$result->EOF)
{
	$home['cols'] = $result->fields['cols'];
	$home['rows'] = $result->fields['rows'];
	$home['width'] = floor(1.0/$result->fields['cols']*100);
	$home['height'] = floor(500/$result->fields['rows']);
	$home['cells'] = Array();
	
	for($i=1;$i<=$home['rows'];$i++)
	{
		$home['cells'][$i] = Array();
		for($x=1;$x<=$home['cols'];$x++)
		{
			$cellCount = ($i-1)*$home['cols']+$x;
			$home['cells'][$i][$x] = Array();
			$home['cells'][$i][$x]['title'] = $result->fields['cell'.$cellCount.'_title']; 
			$home['cells'][$i][$x]['url'] = $result->fields['cell'.$cellCount.'_url']; 
		}
	}
}
$smarty->assign('HOME',$home);
$smarty->display('HomeShow/Home.tpl');
?>