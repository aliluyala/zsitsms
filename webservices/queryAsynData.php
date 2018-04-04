<?php

if(!function_exists('apc_fetch'))
{
	die(json_encode(array('state'=>1)));
}

if(array_key_exists('key',$_GET) && !empty($_GET['key']))
{
	die(json_encode(array('state'=>0,'key'=>$_GET['key'],'data'=>apc_fetch($_GET['key']))));
}
die(json_encode(array('state'=>1)));

?>