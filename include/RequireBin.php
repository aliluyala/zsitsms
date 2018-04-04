<?php
function require_bin($name,$bin_path)
{
	require($name);
	$tag = strtoupper(str_replace('.','_',basename($name)));
	if(!defined($tag))
	{
		apc_delete_file($name);
		if(empty($bin_path))
		{
			$bin_path = _ROOT_DIR.'/bin';
		}		
		elseif(substr($bin_path,-1) == '/')
		{
			$bin_path = substr($bin_path,0,strlen($bin_path)-1);
		}
		$binfile = $bin_path.'/'.$tag.'.bin';
		if(is_file($binfile))
		{	
			$ret = apc_bin_loadfile($binfile);
			require($name);
		}	
		
		if(!defined($tag))
		{
			trigger_error('Require bin file error!',E_USER_ERROR);
		}		
		
	}	
}
?>