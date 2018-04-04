<?php
class IPAddress
{
	private $iparr = Array(0,0,0,0);
	private $ipbin = 0;
	function __construct($ip)
	{
		if(!empty($ip))
		{
			$this->fromStr($ip);
		}
	}
	
	private function _strToArr($ip)
	{
		$arr = explode('.',$ip);
		if(count($arr) != 4) return false;
		return Array(intval($arr[0]),intval($arr[1]),intval($arr[2]),intval($arr[3]));
	}	
	
	private function _arrToBin($iparr)
	{
		if(count($iparr)!=4) return 0;
		return ($iparr[0] << 24)|($iparr[1] << 16)|($iparr[2] << 8)|$iparr[3];
	}
	
	public function fromStr($ip)
	{
		$v = $this->_strToArr($ip);
		if(!$v) return false;
		$this->iparr = $v;
		$this->ipbin = 	$this->_arrToBin($v);
		return true;
	}
	
	private function _getMask($addressRang)
	{
		$v = explode('/',$addressRang);
		if(count($v) == 1) return 0xffffffff;
		if(count($v) == 2)
		{
			$i = intval($v[1]);
			if( $i>0 && $i<32)
			{
				return (pow(2,$i)-1)<<(32-$i);
			}
			elseif($i == 32)
			{
				return 0xffffffff;
			}	
		}
		return false;
	}
	
	public function isAcl($acl)
	{
		$mask = $this->_getMask($acl);
		if(!$mask) return false;
		$v = explode('/',$acl);
		
		$pfx = $this->_arrToBin($this->_strToArr($v[0])) & $mask;
		return $pfx === ($this->ipbin&$mask);
	}

}

?>