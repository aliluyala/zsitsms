<?php
global $APP_ADODB;
if(!isset($_GET['table_name'])) die();
if(!isset($_GET['value_field'])) die();
if(!isset($_GET['filter_field'])) die();
if(!isset($_GET['filter_value'])) die();
$sql = "select {$_GET['value_field']} from  {$_GET['table_name']} where {$_GET['filter_field']}='{$_GET['filter_value']}';" ;
$result = $APP_ADODB->Execute($sql);
$picklist = Array();
if($result && $result->RecordCount()>0)
{
	foreach($result as $row)
	{
		$picklist[] = $row[0];
	}
}

return_ajax('data',$picklist);

?>