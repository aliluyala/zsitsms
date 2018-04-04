<?php
	$extensions = get_loaded_extensions();	
	$chkexts = array('mysql','apc','zip','xml','gd','mbstring','soap','ldap','iconv');
	$env_infos = array();
	$col = 0;
	$line = array();	
	$line[] =  array('label'=>'PHP '.PHP_VERSION,'pass'=>!version_compare(PHP_VERSION,'5.2.0','<'));
	$col++;
	$check_pass = true;
	foreach($chkexts as $ext)
	{
		if(in_array($ext,$extensions))
		{
			$line[] = array('label'=>'php '.$ext,'pass'=>true);
		}
		else
		{
			$line[] = array('label'=>'php '.$ext,'pass'=>false);
			$check_pass = false;
		}
		$col++;
		if($col>=2)
		{
			$env_infos[] = $line;
			$line = array();
			$col = 0 ;
		}
	}
	
	
	$smarty = new ZS_Smarty();
	
	$smarty->assign('PRODUCT',_APP_PRODUCT_NAME.' '._APP_VERSION);
	$smarty->assign('EVN_INFOS',$env_infos);
	$smarty->assign('CHECK_PASS',$check_pass);
	$smarty->assign('WEB_SERVER',$_SERVER["SERVER_SOFTWARE"]);
	$smarty->display('Install/step1.tpl');
		
?>
