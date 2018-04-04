<?php
/**
 * 云接口
 **/
class cloud
{	
	public $iscloud = False;
	private $clouddb = Null;
	public $cloudid = Null;
	
	function __construct($cloudid,$conf)
	{
		$this->cloudid = $cloudid;
		$this->iscloud = True;			
		$this->clouddb = mysql_connect($conf['DBHost'],$conf['DBUserName'],$conf['DBPassword'],$conf['Database'],$conf['DBPort']); 
		mysql_query('set names utf8',$this->clouddb);
		mysql_select_db($conf['Database'],$this->clouddb);	
		$this->init($this->cloudid);
						
	}	
	
	public function init($cloudid)
	{
		if(empty($cloudid)) $cloudid = $this->cloudid;
		$this->cloudid = $cloudid;
		if(empty($cloudid)) return false;
		if(mysql_errno($this->clouddb)) return false;
		$sql = "select cloud_accounts.*,cloud_configure.* from cloud_accounts left join  cloud_configure on cloud_accounts.id=cloud_configure.id where cloud_accounts.cloud_id = '{$cloudid}';";
		$result = mysql_query($sql,$this->clouddb);
		if(!$result) return false;
		$row = mysql_fetch_assoc($result);
		if(empty($row)) return false;
		$this->info = $row;
		$this->info['features'] = explode(',',$this->info['features']);
		return true;
	}
	
	/*是否在云工作模式*/
	public function isCloud()
	{
		return $this->iscloud;
	}

	/*云用户名称*/
	public function name()
	{
		if(empty($this->info)) return false;
		return $this->info['name'];
	}
	
	/*当前云id*/
	public function cloudid()
	{
		return $this->cloudid;
	}
	
	/*当前实例状态*/
	public function status()
	{
		if(empty($this->info)) return false;
		return $this->info['status'];		
	}
	
	/*是否允许指定功能*/
	public function hasFeature($feature)
	{
		if(empty($this->info)) return false;
		return in_array($feature,$this->info['features']);
	}
	
	/*云实例数据名*/
	public function dbName()
	{
		if(empty($this->info)) return false;
		return $this->info['db_name'];
	}
	/*云实例最大用户*/
	public function maxUsers()
	{
		if(empty($this->info)) return false;
		return $this->info['max_users'];
	}
	/*云实例最大座席*/
	public function maxAgents()
	{
		if(empty($this->info)) return false;
		return $this->info['max_agents'];
	}	
	/*云实例存储路径*/
	public function storagePath()
	{
		if(empty($this->info)) return false;
		return $this->info['storage_path'];
	}

	/*获取资源访问接口*/
	public function getResource($resource,$params=null)
	{
		$path = dirname(__file__).'/resources/';
		$res = require($path.'resources.php');
		if(!array_key_exists($resource,$res)) return false;
		$file = $path.$res[$resource];
		if(is_file($file))
		{
			require($path.$res[$resource]);
			if(class_exists($resource))
			{
				$api = new $resource($this,$params);
				return $api;
			}			
		}
		return false;
	}
	
}















?>