<?php
//zswitch interface 
class ZSwitchComm
{
	private $ip = null;
	private $port = null;
	private $user = null;
	private $pwd  = null;
	function __construct($ip=null,$por=null,$user=null,$pwd=null)
	{
		$zsconf = require(_ROOT_DIR."/config/zswitch.conf.php");
		if(!empty($ip))
		{
			$this->ip = $ip;
		}	
		else 
		{
			$this->ip = $zsconf['zswitch_ip'];
		}
		if(!empty($port))
		{
			$this->port = $port;
		}	
		else 
		{
			$this->port = $zsconf['zswitch_port'];
		}	
		if(!empty($user))
		{
			$this->user = $user;
		}	
		else 
		{
			$this->user = $zsconf['zswitch_user'];
		}
		if(!empty($pwd))
		{
			$this->pwd = $pwd;
		}	
		else 
		{
			$this->pwd = $zsconf['zswitch_pwd'];
		}		

	}
	
	private function _request($module,$action,$params)
	{
		$url = "http://{$this->ip}:{$this->port}/api/lua?";
		$par = "{$module}/{$action}.lua "; 
		foreach($params as $arg)
		{
			$par .= $arg.' ';
		}

		$url = $url.rawurlencode($par);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL ,$url);
		curl_setopt($ch,CURLOPT_HEADER ,0); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch,CURLOPT_USERPWD, "{$this->user}:{$this->pwd}");
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		//$result = false;
		$result = curl_exec($ch);
		curl_close($ch);
		if($result && substr($result,0,3)=='+OK')
		{
			return true;
		}		
		return false;	
	}
	public function agentCallout($agent,$number)
	{
		$params = Array($agent,$number);
		return $this->_request('callcenter','callout',$params);
	}
	public function agentCalloutA($agent,$number,$hideNumber)
	{
		$params = Array($agent,$number,$hideNumber);
		return $this->_request('callcenter','calloutA',$params);
	}	
	public function agentHangup($agent,$uuid)
	{
		$params = Array($agent,$uuid);
		return $this->_request('callcenter','hangup',$params);		
	}

	public function agentTransfer($agent,$uuid,$number)
	{
		$params = Array($agent,$uuid,$number,'agent');
		return $this->_request('callcenter','transfer',$params);	
	}
	
	public function spy($agent,$uuid,$spynumber)
	{
		$params = Array($agent,$uuid,$spynumber);
		return $this->_request('callcenter','spy',$params);	
	}

	public function login($agent,$queue,$enable='YES')
	{
		$params = Array($agent,$queue,$enable);
		return $this->_request('callcenter','login',$params);
	}
	
	public function agentChangeStatus($agent,$status)
	{
		$params = Array($agent,$status);
		return $this->_request('callcenter','changeStatus',$params);		
	}
	
	public function agentSendDTMF($agent,$dtmf)
	{
		$params = Array($agent,$dtmf);
		return $this->_request('callcenter','sendDTMF',$params);		
	}
	
	public function agentPlayFile($agent,$file)
	{
		$params = Array($agent,$file);
		return $this->_request('callcenter','playFile',$params);		
	}
	
	public function memberTransger($queue,$member,$number)
	{
		$params = Array($queue,$member,$number);
		return $this->_request('callcenter','transferMember',$params);		
	}
	
	public function memberSpy($queue,$member,$agent)
	{
		$params = Array($queue,$member,$agent);
		return $this->_request('callcenter','spyMember',$params);			
	}
	
	public function autodialPS($userid,$groupid,$agent)
	{
		$params = Array($userid,$groupid,$agent);
		return $this->_request('autodial','startPS',$params);			
	}	
}
?>