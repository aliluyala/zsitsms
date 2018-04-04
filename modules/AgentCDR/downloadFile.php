<?php
global $APP_ADODB;
$zswitchconf = require_once(_ROOT_DIR.'/config/zswitch.conf.php');
$filepath = '';
$have_file = false;
if(!empty($_GET['recordid']))
{
	$sql = "select * from zswitch_cc_agent_cdr where id={$_GET['recordid']};";
	$result = $APP_ADODB->Execute($sql);
	
	if($result && !$result->EOF && $result->fields['answered_datetime'] != '0000-00-00 00:00:00')
	{
		$filepath = $zswitchconf['recordfile_path'].Date('Y-m-d',strtotime($result->fields['answered_datetime'])).'/';
		$filepath .= $result->fields['agent_name'].'/';
		$file_name1 = $result->fields['agent_name'].'_'.$result->fields['other_number'].'_'.Date('YmdHis',strtotime($result->fields['answered_datetime'])).'.wav';
		$file = $result->fields['uuid'].'_'.$file_name1;
		$filepath .=$file;
		if(file_exists($filepath))
		{
			$have_file = true;
			$file_name = $file_name1;
			//$recordid = $result->fields['id'];
		}
	}
	
	if($have_file)
	{
		//$mime = 'audio/wav';
		$mime = 'application/octet-stream';
		header("Content-type:{$mime}");
		header("Accept-Ranges: bytes");
		header("Accept-Length: ".filesize($filepath));
		header("Content-Disposition:attachment;filename={$file_name}");
		ob_end_flush();
		$f = fopen($filepath,"r");
		$pos = 0;
		while(!feof($f))
		{
			
			fseek($f,$pos);		
			$buf = fread($f,1024*1024);
			
			if(!$buf) break;
			echo $buf;
			ob_flush();
			flush();
			if( 0 != connection_status()) break;
			$pos += 1024*1024;            
			usleep(100000); 	
		}
		fclose($f);
		//@readfile($filepath);
		die();
	}
}
echo '录音文件不存！';
?>