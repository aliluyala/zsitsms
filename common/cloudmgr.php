<?php

require(_ROOT_DIR.'/include/cloud/cloud.class.php');
$CLOUDMGR = null;
$CUURENT_CLOUDID = null;
if($CLOUD_TYPE)
{	
	$new_cloudid = null;
	if(array_key_exists('cloudid',$_GET))
	{	
		$new_cloudid = $_GET['cloudid'];
		if($new_cloudid != session_get('logined_cloudid'))
		{			
			session_set('authentication_logined',false);
			session_set('logined_cloudid',null);
			setcookie('cloudid',$new_cloudid,time()+10*365*24*3600);
			header('Location: '._INDEX_URL.'?module=User&action=login');
			die();
		}
	}
	$CUURENT_CLOUDID = session_get('logined_cloudid');
	if(!empty($CUURENT_CLOUDID))
	{		
		$conf = require(_ROOT_DIR.'/config/cloud.conf.php');
		$CLOUDMGR = new cloud($CUURENT_CLOUDID,$conf);

		$changedb = false;
		if($CLOUDMGR->status() == 'USING' )
		{
			$dbname = $CLOUDMGR->dbName();
			if($APP_ADODB->Execute("use {$dbname};"))
			{
				$changedb = true;
				$APP_ADODB->Execute("set names utf8;");				
			}
			
		}
		
		if(!$changedb)
		{
			session_set('logined_cloudid',null);
			$CUURENT_CLOUDID = null;
			$CLOUDMGR =  null;
			session_set('authentication_logined',false);
		}
	}	
}
 


?>