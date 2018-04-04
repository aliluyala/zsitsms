<?php
/**
 * ��Ŀ��          �������ݷ���ӿ�
 * �ļ���:         IDEService.class.php
 * ��Ȩ���У�      �ɶ�����Ƽ����޹�˾
 * ���ߣ�          Tang DaYong  
 * �汾��          1.0.1
 *
 * �������ݷ���ӿ���
 **/
class IDEService 
{
	private $url,$user,$pwd;
	/**
	 * ���캯��
	 * ����:
	 * @config          ���衣����
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
	 * �ύ����
	 * ����:
	 * @method          ���衣����
	 * @params          ����
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
	 * ��ѯ��������
	 * ����:
	 * @policyNo          ���衣������
	 * 
	 **/			
	public function queryPolicy($policyNo)
	{
		$params = array('policy_no'=>$policyNo);
		return $this->request('queryPolicy',$params);
	}

	/**
	 * ����vin���ѯ�����б�
	 * ����:
	 * @vin          ���衣���ܺ�
	 * 
	 **/			
	public function queryPolicyListByVin($vin)
	{
		$params = array('vin_no'=>$vin,'return_multrows'=>'true');
		return $this->request('queryPolicy',$params);
	}

	/**
	 * ���ݳ��ƺŲ�ѯ�����б�
	 * ����:
	 * @licenseNo          ���衣���ƺ�
	 * 
	 **/		
	public function queryPolicyListByLicense($licenseNo)
	{
		$params = array('license_no'=>$licenseNo,'return_multrows'=>'true');
		return $this->request('queryPolicy',$params);
	}
	
	/**
	 * ����vin���ѯ�������
	 * ����:
	 * @vin          ���衣���ܺ�
	 * 
	 **/		
	public function queryLastYearPolicyByVin($vin)
	{
		$params = array('vin_no'=>$vin);
		return $this->request('queryPolicy',$params);
	}

	/**
	 * ���ݳ��ƺŲ�ѯ�������
	 * ����:
	 * @licenseNo          ���衣���ƺ�
	 * 
	 **/		
	public function queryLastYearPolicyByLicense($licenseNo)
	{
		$params = array('license_no'=>$licenseNo);
		return $this->request('queryPolicy',$params);		
	}

	/**
	 * ��ñ�����۲ο�
	 * ����:
	 * @vin          ���衣vin��
	 * 
	 **/			
	public function getPolicyRefer($vin)
	{
		$params = array('vin_no'=>$vin);
		return $this->request('getPolicyRefer',$params);				
	}
}



?>