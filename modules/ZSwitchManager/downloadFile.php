<?php
$zswitchconf = require_once(_ROOT_DIR.'/config/zswitch.conf.php');
$srcfile = $zswitchconf['recordfile_path'].$_GET['subdir'].'/'.$_GET['srcfile'];

if(is_file($srcfile))
{
	$pathinfo = pathinfo($_GET['srcfile']);
	$mime = '';
	switch($pathinfo['extension'])
	{
		case 'mp3':
			$mime = 'audio/mpeg';
			break;
		case 'm4a':	
			$mime = 'audio/mp4';
			break;
		case 'ogg':
			$mime = 'audio/ogg';
			break;
		case 'oga':	
			$mime = 'audio/ogg';
			break;
		case 'webma':	
			$mime = 'audio/webm';
			break;
		case 'wav':	
			$mime = 'audio/wav';
			break;
		case 'mp4':	
			$mime = 'video/mp4';
			break;
		case 'm4v':	
			$mime = 'video/mp4';
			break;
		case 'ogv':	
			$mime = 'video/ogg';
			break;
		case 'webm':	
			$mime = 'video/webm';
			break;
		case 'webmv':	
			$mime = 'video/webm';
			break;

	}
	header("Content-type:{$mime}");
	header("Content-Disposition:attachment;filename={$_GET['filename']}");
	readfile($srcfile);
}
?>