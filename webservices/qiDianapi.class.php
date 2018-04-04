<?php

/**
 * @name      qiDianapi  		系统接口请求类
 * @var  	  $error    		申明返回错误变量
 * @abstract  getErrorMessage   返回错误提示
 * @abstract  getIsapiConfig    获取配置信息
 * @abstract  getIsapiToken     获取Token
 * @abstract  getCliectSdk      请求接口
 */
class qiDianapi{
	/**
	 * [$error 定义错误返回信息]
	 * @var [type]
	 */
	public $error;

	/**
	 * getErrorMessage 返回错误提示
	 * @dateTime 2017-11-13
	 * @license  license
	 * @return   [string]     [返回错误提示]
	 */
	public function getErrorMessage()
	{
		return $this->error;
	}

	private $option = array(
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_USERAGENT=> 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)',
		CURLOPT_HTTPHEADER => array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*','Accept-Encoding: gzip, deflate'),
		CURLOPT_FOLLOWLOCATION => 1,
		CURLOPT_ENCODING => 'gzip,deflate',
		CURLOPT_TIMEOUT => 90,
		CURLOPT_RETURNTRANSFER => 1
	);

	public function sendClient($url,$parms = null,$client = 'GET')
	{
		$curl = curl_init();
		$this->option[CURLOPT_URL] = $url;
		if(is_array($parms) && !empty($parms))
		{
			$this->option[CURLOPT_POSTFIELDS] = http_build_query($parms);
		}
		else
		{
			$this->option[CURLOPT_POSTFIELDS] = $parms;
		}
		if($client == 'POST')
		{
			$this->option[CURLOPT_POST] = 1;
		}
		curl_setopt_array($curl, $this->option);
		$result = curl_exec($curl);
		if(curl_errno($curl) != 0)
		{
			$getinfo = curl_getinfo($curl);
			$error = array(
							'create_time' => date('Y-m-d H:i:s',time()),
							'curl_errno' => curl_errno($curl),
							'http_code' => $getinfo['http_code'],
							'url' => $getinfo['url'],
							'parms' => $this->option[CURLOPT_POSTFIELDS],
							'header_size' => $getinfo['header_size'],
							'request_size' => $getinfo['request_size']
			);
			$errno = json_encode($error);
			$this->putLog($errno);
			$this->error = '接口请求失败,请联系管理员!';
			curl_close($curl);
			return false;
		}
		curl_close($curl);
		return $result;
	}


	/**
	 * 写入错误日志。
	 * @param string $log 日志内容。
	 * @return void
	 */
	private function putLog( $log )
	{
		$logDir = dirname( __FILE__ ).'/../cache/Isapi/';
		if ( !file_exists( $logDir ))
		{
			mkdir($logDir,0775,true);
		}
		file_put_contents( $logDir.'error_log.txt', $log."\r\n", FILE_APPEND );

	}

	/**
	 * getIsapiConfig 获取配置信息
	 * @dateTime 2017-11-13
	 * @license  license
	 * @return   [array]     [返回配置信息]
	 */
	public function getIsapiConfig()
	{
		if(file_exists(_ROOT_DIR."/config/isapi.conf.php"))
		{
			$isapi = require(_ROOT_DIR."/config/isapi.conf.php");
			if($isapi['url'] == "" ||  $isapi['user'] == "" || $isapi['password'] == "")
			{
				$this->error = '请设置<isapi.config>配置信息';
				return false;
			}
			return $isapi;
		}
		else
		{
			$this->error = 'isapi.config配置信息文件不存在!';
			return false;
		}
	}
	/**
	 * getIsapiToken 获取Token
	 * @dateTime 2017-11-10
	 * @license  license
	 * @return   [array]     [返回Token凭证]
	 */
	public function getIsapiToken()
	{
		$config = $this->getIsapiConfig();
		if(!$config)
		{
			return false;
		}
		$token = apc_fetch('token');
		$url  = apc_fetch('url');
		$user = apc_fetch('user');
		$password = apc_fetch('password');
		if(isset($config) && $config['url'] == $url && $config['user'] == $user && $config['password'] == $password)
		{
			if(empty($token))
			{
				return $this->createToekn($config);
			}
			else
			{
				$apcfetch['token'] = $token;
				$apcfetch['url'] = $url;
				$apcfetch['user'] = $user;
				$apcfetch['password'] = $password;
				return $apcfetch;
			}
		}
		else
		{
			return $this->createToekn($config);
		}
	}
	/**
	 * createToekn 创建Token
	 * @dateTime 2017-11-21
	 * @license  license
	 * @param    array      $config [description]
	 * @return   [type]             [description]
	 */
	public function createToekn($config = array())
	{
		$params = array(
					'login_id' => $config['user'],
					'password' => $config['password'],
				);
		$login_url = $config['url']."/?s=User.Login";
		$curlstr = $this->sendClient($login_url,$params,'POST');
		if(!$curlstr)
		{
			return false;
		}
		$curl_login  = json_decode($curlstr,true);
		if(is_array($curl_login) && $curl_login['code'] == 0)
		{
			apc_store('token', $curl_login['data']['token'], '1000');//设置token缓存
			apc_store('url', $config['url'], '3600');//设置token缓存
			apc_store('user', $config['user'], '3600');//设置token缓存
			apc_store('password', $config['password'], '3600');//设置token缓存
			$apcfetch['token'] = $curl_login['data']['token'];
			$apcfetch['url'] = $config['url'];
			$apcfetch['user'] = $config['user'];
			$apcfetch['password'] = $config['password'];
			return $apcfetch;
		}
		else
		{
			$this->error = $curl_login['describe'];
			return false;
		}
	}
	/**
	 * getCliectSdk 请求接口
	 * @dateTime 2017-11-13
	 * @license  license
	 * @param    [string]     $sdk     [请求方法]
	 * @param    [array]     $params  [请求参数]
	 * @param    string     $request [请求方式]
	 * @return   [array]              [返回数据]
	 */
	public function getCliectSdk($sdk,$params = null,$request = 'GET')
	{
		if(!isset($sdk) && $sdk == "")
		{
			$this->error = "请配置请求信息";
			return false;
		}
		$getToken = $this->getIsapiToken();
		if(!$getToken)
		{
			return false;
		}
		$inResult = $this->cliectSdk($sdk,$params,$request,$getToken);
		if(!$inResult)
		{
			return false;
		}
		if($inResult['code']  == 3)
		{
			$config = $this->getIsapiConfig();
			if(!$config)
			{
				return false;
			}
			$Token = $this->createToekn($config);
			$inResult = $this->cliectSdk($sdk,$params,$request,$Token);
		}

		if($inResult['code'] > 0  && $inResult['code'] != 4 )
		{
			$this->error = $inResult['describe'];
			return false;
		}
		return $inResult;
	}

	private function cliectSdk($sdk,$params = null,$request = 'GET',$getToken = array())
	{

		if(strtoupper($request) == 'GET')
		{
			if(!empty($params))
			{
				$inResult = $this->sendClient($getToken['url']."?s=".$sdk."&token=".$getToken['token'],http_build_query($params));
			}
			else
			{
				$inResult = $this->sendClient($getToken['url']."?s=".$sdk."&token=".$getToken['token']);
			}
		}
		if(strtoupper($request) == "POST")
		{
			if(!is_array($params))
			{
				$this->error = "请求参数必须是数组形式！";
				return false;
			}
			$inResult = $this->sendClient($getToken['url']."?s=".$sdk."&token=".$getToken['token'],$params,'POST');
		}
		if(!$inResult)
		{
			return false;
		}
		return json_decode($inResult,true);
	}
}
