<?php
/*
 * 项目:           APC上传文件
 * 文件名:         APCUpload.class.php
 * 版权所有：      2015 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *           
 * APC文件上传服务端处理类
 */

class APCUpload{
	
	private $uploadDir,$fileTypes;
	
	/**
	 * 构造函数
	 **/
	function __construct($uploadDir,$fileTypes)
	{
		$this->uploadDir = $uploadDir;
		$this->fileTypes = $fileTypes;
	}
	
	/**
	 * 是否支持APC
	 * 
	 **/
	public function isHaveAPC()
	{
		if(!ini_get('apc.enabled') || !ini_get('apc.rfc1867')) return false;
		return true;
	}		
	
	
	/**
	 * 获取上传文件名
	 * 
	 **/
	public function getFileName()
	{
		$apckey = $this->getAPCKEY();
		if(array_key_exists($apckey,$_FILES))
		{
			return $_FILES[$apckey]['name'];
		}
		return false;
	}	
	
	/**
	 *	获取apc rfc1867 prefix
	 *
	 **/
	public function getAPCPrefix()
	{
		if(!$this->isHaveAPC()) return false;
		
		return ini_get('apc.rfc1867_prefix');
	}

	/**
	 *	获取apc rfc1867 name
	 *
	 **/
	public function getAPCName()
	{
		if(!$this->isHaveAPC()) return false;
		
		return ini_get('apc.rfc1867_name');
	}
	
	/**
	 *	获取file key
	 *
	 **/
	public function getAPCKEY()
	{
		if(!$this->isHaveAPC()) return false;
		$apcid = $this->getAPCName();
		if(!array_key_exists($apcid,$_POST)) return false;
		return $_POST[$apcid];
	}
	
	/**
	 *	获取错误
	 *
	 **/
	public function getError()
	{
		$apckey = $this->getAPCKEY();
		
		if(!array_key_exists($apckey,$_FILES)) return 99;
		if(empty($_FILES[$apckey]['tmp_name']) || 
		   $_FILES[$apckey]['tmp_name'] == 'none')
		   return 98;
		if($_FILES[$apckey]['error'] == UPLOAD_ERR_OK && !$this->checkFileType())
		{
			return 97;
		}		
		return 	$_FILES[$apckey]['error'];		
	}
	
	/**
	 *	获取错误信息(json)
	 *
	 **/
	public function getErrorJson()
	{

		$error = $this->getError();
		$msg = '';	
		switch($error)
		{
			case UPLOAD_ERR_OK:
				$msg = '上传完成';
				break;
			case UPLOAD_ERR_INI_SIZE:
				$msg = '文件大小超过限制1:'.ini_get('upload_max_filesize').'!';
				break;	
			case UPLOAD_ERR_FORM_SIZE:
				$msg = '文件大小超过限制2:'.$_POST('UPLOAD_ERR_FORM_SIZE').'!';
				break;	
			case UPLOAD_ERR_PARTIAL:
				$msg = '文件只完成部分上传!';
				break;	
			case UPLOAD_ERR_NO_FILE:
				$msg = '没有文件被上传!';
				break;	
			case 97:
				$msg = '文件类型不被接受!';
				break;					
			default:
				$msg = '系统错误!';
				break;
		}
		
		$file = $this->getFileName();
		return 	json_encode(Array('file'=>$file,'error'=>$error,'msg'=>$msg));
	}	
	
	/**
	 * 检查文件类型是否允许
	 *
	 **/	
	public function checkFileType()
	{
		if(empty($this->fileTypes)) return true;
		$apckey = $this->getAPCKEY();
		
		if(array_key_exists($apckey,$_FILES))
		{
			$pathinfo = pathinfo($_FILES[$apckey]['name']);
			if(array_key_exists('extension',$pathinfo)&&in_array($pathinfo['extension'],$this->fileTypes))
			{
				return true;
			}
		}
		return false;
	}
	

	
	/**
	 * 完成文件上传
	 * 返回值: 成功返回文件路径,失败false	
	 **/	
	public function completeUpload()
	{
		if($this->getError() !== UPLOAD_ERR_OK) return false;
		$apckey = $this->getAPCKEY();
		$filename = $this->getFileName();
		if(move_uploaded_file($_FILES[$apckey]['tmp_name'],$this->uploadDir.$filename))
		{
			return true;
		}
		return false;		
	}
	
	/**
	 * 获取查询进度
	 * 返回值: 成功返回文件路径,失败false	
	 **/	
	public function getProgress($apckey)
	{
		
		$info = apc_fetch($this->getAPCPrefix().$apckey);
		$info['apckey'] = $apckey;
		return $info;
	}

	/**
	 * 获取查询进度JSON
	 * 返回值: 成功返回文件路径,失败false	
	 **/	
	public function getProgressJson($apckey)
	{
		$info = apc_fetch($this->getAPCPrefix().$apckey);
		$info['apckey'] = $apckey;
		return json_encode($info);
	}	
	
}

?>