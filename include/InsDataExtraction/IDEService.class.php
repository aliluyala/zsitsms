<?php
/**
 * 项目：          保险数据服务接口
 * 文件名:         IDEService.class.php
 * 版权所有：      成都启点科技有限公司
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *
 * 保险数据服务接口类
 **/
class IDEService 
{
	private $url,$user,$pwd;
	/**
	 * 构造函数
	 * 参数:
	 * @config          必需。配置
	 * 
	 **/	
	function __construct($config = null)
	{
		if(is_array($config) && !empty($config['url']) && !empty($config['user'])&&array_key_exists($config['password']))
		{
			$this->url = $config['url'].'?module=IDEServiceA';
			$this->user = $config['user'];
			$this->pwd = $config['password'];
			return ;
		}
		elseif(is_file(_ROOT_DIR.'/config/IDEService.conf.php'))
		{
			$conf = require(_ROOT_DIR.'/config/IDEService.conf.php');	
			if(is_array($conf)&& !empty($conf['url']) && !empty($conf['user'])&&array_key_exists('password',$conf))
			{
				$this->url = $conf['url'].'?module=IDEServiceA';
				$this->user = $conf['user'];
				$this->pwd = $conf['password'];
				return;
			}
		}
		trigger_error('Please check IDE service config.',E_USER_ERROR);
	}

	/**
	 * 提交请求
	 * 参数:
	 * @method          必需。方法
	 * @params          参数
	 **/		
	public function request($method,$params=array())
	{
		
		if(empty($this->url)) return false;
		$url = "{$this->url}&method={$method}&user={$this->user}&password={$this->pwd}";
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);  
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
	    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)'); 
		curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*'));
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
	    curl_setopt($curl, CURLOPT_POST, 1); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
	    curl_setopt($curl, CURLOPT_HEADER, 0); 
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 	    
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);			
		$htmlStr = curl_exec($curl);
		$retinfo = curl_getinfo($curl);
		
		if(curl_errno($curl) || $retinfo['http_code'] != '200')
		{
			curl_close($curl);
			return false;
		}
		return json_decode($htmlStr,true);		
	}
	
	/**
	 * 查询保单详情
	 * 参数:
	 * @policyNo          必需。保单号
	 * 
	 **/			
	public function queryPolicy($policyNo)
	{
		$params = array('policy_no'=>$policyNo);
		return $this->request('queryPolicy',$params);
	}

	/**
	 * 根据vin码查询保单列表
	 * 参数:
	 * @vin          必需。车架号
	 * 
	 **/			
	public function queryPolicyListByVin($vin)
	{
		$params = array('vin_no'=>$vin,'return_multrows'=>'true');
		return $this->request('queryPolicy',$params);
	}

	/**
	 * 根据车牌号查询保单列表
	 * 参数:
	 * @licenseNo          必需。车牌号
	 * 
	 **/		
	public function queryPolicyListByLicense($licenseNo)
	{
		$params = array('license_no'=>$licenseNo,'return_multrows'=>'true');
		return $this->request('queryPolicy',$params);
	}
	
	/**
	 * 根据vin码查询最近保单
	 * 参数:
	 * @vin          必需。车架号
	 * 
	 **/		
	public function queryLastYearPolicyByVin($vin)
	{
		$params = array('vin_no'=>$vin);
		return $this->request('queryPolicy',$params);
	}

	/**
	 * 根据车牌号查询最近保单
	 * 参数:
	 * @licenseNo          必需。车牌号
	 * 
	 **/		
	public function queryLastYearPolicyByLicense($licenseNo)
	{
		$params = array('license_no'=>$licenseNo);
		return $this->request('queryPolicy',$params);		
	}

	/**
	 * 获得保单算价参考
	 * 参数:
	 * @vin          必需。vin码
	 * 
	 **/			
	public function getPolicyRefer($vin)
	{
		$params = array('vin_no'=>$vin);
		return $this->request('getPolicyRefer',$params);				
	}
}



?>