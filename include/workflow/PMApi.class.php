<?php
/**
 * 项目:           工作流引擎接口
 * 文件名:         PMApi.class.php
 * 版权所有：      2015 成都启点科技有限公司.
 * 作者：          Tang DaYong
 * 版本：          1.0.0
 *
 * 说明：
 *     此接口是访问processmaker 2.5.x 工作流引擎的接口。
 *
 * 修改记录：
 *     2016-02-23 唐大勇    修正newCase方法不路由到下一步问题。
 **/
 ini_set("soap.wsdl_cache_enabled", 0);

 /*变量结构*/
 class PMVariableStruct
 {
   public $name;
   public $value;
 }

 /*接口类*/
 class PMApi
 {

	// 版本号
	const     Version = '1.0.0';

	//SoapClient 对象
	private   $SoapObj = null;

	/*PM管理员帐号*/
	private   $PMAdmin = null;

	/*PM管理员密码*/
	private   $PMPassword = null;

	/*PM会话ID*/
	private   $SessionId = null;

	/*错误代码*/
	private   $ErrorCode = 0;

	/*错误信息*/
	private   $ErrorInfo =  '';

	//工作空间
	private   $Workspace = '';

	//流程管理员权限名
	private $ProcessAmdinRole = '';

	//流程操作员权限名
	private $ProcessOperatorRole = '';

	/*数据库联接*/
	private   $DBCon = null;

	/*构造函数*/
	function __construct(array $Config)
	{

		if(!empty($Config['host']) && !empty($Config['port']) && !empty($Config['workspace']) && !empty($Config['lang']))
		{
			//$this->SoapObj = new SoapClient("http://{$Config['host']}:{$Config['port']}/sys{$Config['workspace']}/{$Config['lang']}/classic/services/wsdl2");

            $this->SoapObj = new SoapClient(dirname(__FILE__).'/pmos2.wsdl',array('connection_timeout'=>5));
		}

		if(!empty($Config['admin_user']))
		{
			$this->PMAdmin = $Config['admin_user'];
		}

		if(!empty($Config['admin_pwd']))
		{
			$this->PMPassword = $Config['admin_pwd'];
		}

		if(!empty($Config['workspace']))
		{
			$this->Workspace = $Config['workspace'];
		}
		else
		{
			$this->Workspace = 'workflow';
		}

		if(!empty($Config['process_amdin_role']))
		{
			$this->ProcessAmdinRole = $Config['process_amdin_role'];
		}

		if(!empty($Config['process_operator_role']))
		{
			$this->ProcessOperatorRole = $Config['process_operator_role'];
		}

		$this->SoapObj->__setLocation("http://{$Config['host']}:{$Config['port']}/sys{$Config['workspace']}/{$Config['lang']}/classic/services/soap2");

		$this->DBCon = @new mysqli($Config['DB_host'],$Config['DB_user'],$Config['DB_password'],'',$Config['DB_port']);
		$this->DBCon->query('set names utf8;');

	}

	/*登录*/
	public function login($PMUser=null,$PMPassword=null,$IsAdmin=false)
	{
		if(empty($this->SoapObj)) return false;

		if($IsAdmin)
		{
			$params = array('userid'=>$this->PMAdmin, 'password'=>$this->PMPassword);
		}
		else
		{
			$params = array('userid'=>$PMUser, 'password'=>$PMPassword);
		}

		$result = $this->SoapObj->__SoapCall('login', array($params));


		if($result->status_code != 0)
		{
			return false;
		}
		elseif($result->version != '2.0')
		{
			return false;
		}

		return $this->SessionId = $result->message;

	}

	/*返回SESSIONID*/
	public function getSessionId()
	{
		return $this->SessionId;
	}

	/*返回工作组列表*/
	public function getGroupList($goupid=null)
	{
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "select GROUPWF.GRP_UID  ,CONTENT.CON_VALUE as NAME  from GROUPWF left join CONTENT on  GROUPWF.GRP_UID = CONTENT.CON_ID ";
			$sql .= "where CONTENT.CON_LANG='en' AND  CONTENT.CON_CATEGORY='GRP_TITLE' ";
			if(!empty($goupid))
			{
				$sql .= " AND GROUPWF.GRP_UID = '{$goupid}' ;";
			}

			$result = $this->DBCon->query($sql);
			if(!$result || $result->num_rows<=0)	return false;

			while($row = $result->fetch_array())
			{
				$grpList[] = array('guid'=>$row['GRP_UID'],'name'=>$row['NAME']);
			}

			return $grpList;

		}
		return false;

	}

	/*新建工作组*/
	public function createGroup($name)
	{
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql = "select CON_ID from CONTENT where CON_VALUE ='{$name}' AND CON_LANG='en' AND  CON_CATEGORY='GRP_TITLE'; ";
			$result = $this->DBCon->query($sql);
			if(!$result)	return false;
			if($row = $result->fetch_array())
			{
				return $row['CON_ID'];
			}

			$guid = md5($name);

			$sql = "insert GROUPWF(GRP_UID)  values('{$guid}');";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows <= 0) return false;

			$sql = "insert CONTENT(CON_CATEGORY,CON_ID,CON_LANG , CON_VALUE)  values('GRP_TITLE','{$guid}','en','{$name}') ;";
			$this->DBCon->query($sql);
			$sql = "insert CONTENT(CON_CATEGORY,CON_ID,CON_LANG , CON_VALUE)  values('GRP_TITLE','{$guid}','zh-CN','{$name}') ;";
			$this->DBCon->query($sql);
			$sql = "insert CONTENT(CON_CATEGORY,CON_ID,CON_LANG , CON_VALUE)  values('GRP_TITLE','{$guid}','zh','{$name}') ;";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return $guid;
		}
		return false;
	}

	/*修改工作组*/
	public function modifyGroup($guid,$name)
	{
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql = "update CONTENT set CON_VALUE ='{$name}' where CON_ID='{$guid}'; ";
			$result = $this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0)	return true;
		}
		return false;
	}


	/*删除工作组*/
	public function deleteGroup($guid)
	{
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql = "delete from  CONTENT  where CON_ID='{$guid}'; ";
			$this->DBCon->query($sql);
			$sql = "delete from  GROUPWF  where GRP_UID='{$guid}'; ";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows <= 0)	return false;
			$sql = "delete from GROUP_USER where GRP_UID='{$guid}' ";
			$this->DBCon->query($sql);
			$sql = "delete from PROCESS_USER where USR_UID='{$guid}' and PU_TYPE ='GROUP_SUPERVISOR' ";
			$this->DBCon->query($sql);
			$sql = "delete from TASK_USER where USR_UID='{$guid}' and  TU_RELATION =2 ";
			$this->DBCon->query($sql);
			$sql = "DELETE FROM OBJECT_PERMISSION WHERE USR_UID='{$guid}'";
			$this->DBCon->query($sql);
			return true;
		}
		return false;
	}



	/*返回用户列表*/
	public function getUserList($userid=null)
	{
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "select USR_UID,USR_USERNAME,USR_PASSWORD ,USR_FIRSTNAME,USR_LASTNAME,USR_ROLE,USR_STATUS from USERS where USR_USERNAME <> '' ";
			if(!empty($userid))
			{
				$sql .= "where USR_UID='{$userid}' AND USR_USERNAME <> ''; ";
			}

			$result = $this->DBCon->query($sql);
			if(!$result)	return false;
			$usrList =  array();
			while($row = $result->fetch_array())
			{
				$usrList[] = array('guid'=>$row['USR_UID'],
				                   'username'=>$row['USR_USERNAME'],
								   'password'=>$row['USR_PASSWORD'],
								   'name'=>$row['USR_FIRSTNAME'],
								   'status'=>$row['USR_STATUS'],
								   'role'=>($row['USR_ROLE']=='PROCESSMAKER_OPERATOR')?0:1  ,
								   );
			}

			return $usrList;

		}
		return false;
	}

	/*新建用户*/
	public function createUser($usr_name,$name,$usr_password)
	{
		if(!$this->DBCon->connect_errno)
		{
			$guid = md5($usr_name);
			$this->DBCon->select_db('rb_'.$this->Workspace);

			$sql = "select USR_UID,USR_STATUS from USERS where USR_USERNAME = '{$usr_name}' ;";

			$result = $this->DBCon->query($sql);
			if(!$result) return false;
			if($result->num_rows>0)
			{
				$row = $result->fetch_array();
				return $row['USR_UID'];
			}

			$clearsql = "delete from USERS where USR_UID='{$guid}';";
			$sql = "select ROL_UID from  ROLES where ROL_CODE='{$this->ProcessOperatorRole}';";
			$result = $this->DBCon->query($sql);
			if($result->num_rows<=0) return false;
			$row = $result->fetch_array();
			$ruid = $row['ROL_UID'];

			$this->DBCon->query($clearsql);
			$sql  = "INSERT INTO USERS (USR_UID,USR_USERNAME,USR_PASSWORD,USR_FIRSTNAME,USR_LASTNAME,USR_EMAIL,";
			$sql .= "USR_DUE_DATE,USR_CREATE_DATE,USR_UPDATE_DATE,USR_STATUS,USR_AUTH_USER_DN)";
			$sql .= " VALUES ('{$guid}','{$usr_name}','{$usr_password}','{$name}',' ','{$usr_name}@zswitch800.com',";
			$dueDate = date('Y-m-d',strtotime('20 years'));
			$curTime = date('Y-m-d H:i:s');
			$sql .= "'{$dueDate}','{$curTime}','{$curTime}',1,'')";
			if(!$this->DBCon->query($sql)) return false;

			$sql = "INSERT INTO USERS_ROLES (USR_UID,ROL_UID) VALUES ('{$guid}','{$ruid}')";
			if(!$this->DBCon->query($sql)) return false;

			$this->DBCon->select_db('wf_'.$this->Workspace);
			$this->DBCon->query($clearsql);
			$sql  = "insert USERS(USR_UID,USR_USERNAME,USR_PASSWORD ,USR_FIRSTNAME,USR_LASTNAME,USR_ROLE,";
			$sql .= "USR_EMAIL, USR_DUE_DATE ,USR_CREATE_DATE, USR_UPDATE_DATE ) ";
			$sql .= "  values('{$guid}','{$usr_name}','{$usr_password}','{$name}',' ','{$this->ProcessOperatorRole}',";
			$sql .= "'{$usr_name}@zswitch800.com','{$dueDate}','{$curTime}','{$curTime}');";
			if($this->DBCon->query($sql)) return $guid;
		}
		return false;
	}

	/*修改用户
		@params 数组KEY是要修改参数名
		usrname: 用户名
        name: 用户姓名
        password:密码
		role:权限 , 0是流程操作员 1是流程管理员

	*/
	public function modifyUser($guid,$params = array())
	{
		if(!$this->DBCon->connect_errno)
		{
			$role = '';
			$sql = "update USERS set ";
			$fieldstr = "";
			$fieldstra = "";

			foreach($params as $field => $value)
			{
				if(!empty($fieldstr) && $field != 'role' )
				{
					$fieldstr .= ',';
					$fieldstra .= ',';
				}

				if($field == 'usrname')
				{
					$fieldstr .= "USR_USERNAME = '{$value}' ";
					$fieldstra .= "USR_USERNAME = '{$value}' ";
				}
				elseif($field == 'name')
				{
					$fieldstr .= " USR_FIRSTNAME = '{$value}' ,USR_LASTNAME =' '  ";
					$fieldstra .= " USR_FIRSTNAME = '{$value}' ,USR_LASTNAME =' '  ";
				}
				elseif($field == 'password')
				{
					$fieldstr .= " USR_PASSWORD = '{$value}' ";
					$fieldstra .= " USR_PASSWORD = '{$value}' ";
				}
				elseif($field == 'status')
				{
					$pmstatus = $value=='Active'?'ACTIVE':'INACTIVE';
					$fieldstr .= " USR_STATUS = '{$pmstatus}' ";
					$pmstatusa = $value=='Active'?1:0;
					$fieldstra .= " USR_STATUS = {$pmstatusa} ";
				}
				elseif($field == 'role')
				{
					$role = ($value == 0)?'PROCESSMAKER_OPERATOR':'PROCESSMAKER_MANAGER';
				}
			}
			$this->DBCon->select_db('rb_'.$this->Workspace);
			$this->DBCon->query("{$sql} {$fieldstra} ,USR_UPDATE_DATE = now() where USR_UID = '{$guid}' ;");
			if($this->DBCon->affected_rows <= 0) return false;
			if(!empty($role))
			{
				$result = $this->DBCon->query("select ROL_UID from ROLES  where ROL_CODE = '{$role}' ;");
				if($row = $result->fetch_array())
				{
					$newRoleUid = $row['ROL_UID'];
					$this->DBCon->query("update USERS_ROLES set  ROL_UID='{$newRoleUid}' where  USR_UID='{$guid}' ;");
				}
			}
          	$this->DBCon->select_db('wf_'.$this->Workspace);
			if(!empty($role))
			{
				$wfsql = "{$sql} {$fieldstr},USR_ROLE = '{$role}' ,USR_UPDATE_DATE = now() where USR_UID = '{$guid}' ;";
			}
			else
			{
				$wfsql = "{$sql} {$fieldstr},USR_UPDATE_DATE = now() where USR_UID = '{$guid}' ;";
			}
			$this->DBCon->query($wfsql);
			if($this->DBCon->affected_rows > 0) return true;
		}
		return false;
	}


	/*删除用户*/
	public function deleteUser($guid)
	{
		if($guid == '00000000000000000000000000000001') return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "SELECT count(APPLICATION.APP_UID) FROM APPLICATION LEFT JOIN APP_DELEGATION ON (APPLICATION.APP_UID=APP_DELEGATION.APP_UID) ";
			$sql .= "WHERE  APP_DELEGATION.USR_UID='{$guid}' AND APP_DELEGATION.DEL_FINISH_DATE IS NULL ";

			$result = $this->DBCon->query($sql."AND APPLICATION.APP_STATUS='TO_DO' ");
			$row = $result->fetch_array();
			if($row[0] > 0)	return 1;

			$result = $this->DBCon->query($sql."AND APPLICATION.APP_STATUS='DRAFT' ");
			$row = $result->fetch_array();
			if($row[0] > 0)	return 1;

			$result = $this->DBCon->query($sql."AND APPLICATION.APP_STATUS='COMPLETED' ");
			$row = $result->fetch_array();
			if($row[0] > 0)	return 1;

			$result = $this->DBCon->query($sql."AND APPLICATION.APP_STATUS='CANCELLED' ");
			$row = $result->fetch_array();
			if($row[0] > 0)	return 1;

			$sql = "delete from  USERS  where USR_UID='{$guid}'; ";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows <= 0)	return false;
			$sql = "delete from GROUP_USER where USR_UID ='{$guid}' ;";
			$this->DBCon->query($sql);
			$sql = "delete from PROCESS_USER where USR_UID='{$guid}' ;";
			$this->DBCon->query($sql);
			$sql = "delete from TASK_USER where USR_UID='{$guid}' ;";
			$this->DBCon->query($sql);
			$sql = "DELETE FROM DASHLET_INSTANCE WHERE DAS_INS_OWNER_TYPE='USER' AND DAS_INS_OWNER_UID='{$guid}'";
			$this->DBCon->query($sql);


			$this->DBCon->select_db('rb_'.$this->Workspace);
			$sql = "delete from  USERS  where USR_UID='{$guid}'; ";
			$this->DBCon->query($sql);
			$sql = "delete from  USERS_ROLES  where USR_UID='{$guid}'; ";
			$this->DBCon->query($sql);

			return true;
		}
		return false;
	}

	/*返回流程列表*/
	public function getProcessList()
	{

		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "select PROCESS.PRO_UID ,CONTENT.CON_VALUE as NAME  from PROCESS left join CONTENT on  PROCESS.PRO_UID = CONTENT.CON_ID ";
			$sql .= "where CONTENT.CON_LANG='en' AND  CONTENT.CON_CATEGORY='PRO_TITLE'; ";

			$result = $this->DBCon->query($sql);
			if(!$result)	return false;
			$proList =  array();
			while($row = $result->fetch_array())
			{
				$proList[] = array('guid'=>$row['PRO_UID'],'name'=>$row['NAME']);
			}

			return $proList;

		}
		return false;
	}

	/*返回流程的任务列表*/
	public function getTaskList($processId=null)
	{

		if(!$this->DBCon->connect_errno)
		{
            $this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "select TASK.PRO_UID,TASK.TAS_UID,TASK.TAS_START ,CONTENT.CON_VALUE as NAME   from TASK left join CONTENT on  TASK.TAS_UID = CONTENT.CON_ID ";
			$sql .= "where CONTENT.CON_LANG='en' AND  CONTENT.CON_CATEGORY='TAS_TITLE'; ";

			$result = $this->DBCon->query($sql);
			if(!$result)	return false;
			$taskList =  array();
			while($row = $result->fetch_array())
			{
				if(empty($processId) || $processId == $row['PRO_UID'])
				{
					$taskList[] = array('pro_uid'=>$row['PRO_UID'],'guid'=>$row['TAS_UID'],'is_start'=>$row['TAS_START'],'name'=>$row['NAME']);
				}
			}

			return $taskList;

		}

		return false;
	}

	/*返回任务关联的用户*/
	public function getTaskAsUsers($taskId)
	{
		if(empty($taskId)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "SELECT TASK_USER.TAS_UID ,TASK_USER.USR_UID  , USERS.USR_USERNAME,USERS.USR_FIRSTNAME ,USERS.USR_STATUS from TASK_USER ";
			$sql .= "LEFT JOIN  USERS ON  TASK_USER.USR_UID = USERS.USR_UID where TASK_USER.TAS_UID = '{$taskId}' AND ";
			$sql .= "TASK_USER.TU_RELATION =1 ;";
			$result = $this->DBCon->query($sql);
			if(!$result)	return false;
			$taskAsUsers = array();
			while($row = $result->fetch_array())
			{

				$taskAsUsers[] = array('tas_uid'=>$row['TAS_UID'],'usr_uid'=>$row['USR_UID'],'status'=>$row['USR_STATUS'],
					                   'usr_username'=>$row['USR_USERNAME'],'usr_name'=>$row['USR_FIRSTNAME']);

			}

			return $taskAsUsers;

		}
		return false;
	}

	/*返回任务关联的用户组*/
	public function getTaskAsGroups($taskId)
	{
		if(empty($taskId)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "SELECT TASK_USER.TAS_UID ,TASK_USER.USR_UID AS GRP_UID, CONTENT.CON_VALUE AS GRP_NAME  from TASK_USER ";
			$sql .= "LEFT JOIN  CONTENT ON  TASK_USER.USR_UID = CONTENT.CON_ID where TASK_USER.TAS_UID = '{$taskId}' AND ";
			$sql .= "TASK_USER.TU_RELATION =2 AND CONTENT.CON_LANG='en' AND  CONTENT.CON_CATEGORY='GRP_TITLE';";
			$result = $this->DBCon->query($sql);
			if(!$result)	return false;
			$taskAsGrps = array();
			while($row = $result->fetch_array())
			{
				$taskAsGrps[] = array('tas_uid'=>$row['TAS_UID'],'grp_uid'=>$row['GRP_UID'],'grp_name'=>$row['GRP_NAME']);
			}

			return $taskAsGrps;

		}
		return false;
	}

	/*返回流程关联的用户*/
	public function getProcessAsUsers($proId)
	{
		if(empty($proId)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "SELECT  PROCESS_USER.PRO_UID , PROCESS_USER.USR_UID  , USERS.USR_USERNAME,USERS.USR_FIRSTNAME  ,USERS.USR_STATUS from  PROCESS_USER ";
			$sql .= "LEFT JOIN  USERS ON  PROCESS_USER.USR_UID = USERS.USR_UID where PROCESS_USER.PRO_UID = '{$proId}' AND ";
			$sql .= "PROCESS_USER.PU_TYPE = 'SUPERVISOR' ;";
			$result = $this->DBCon->query($sql);
			if(!$result)	return false;
			$proAsUsers = array();
			while($row = $result->fetch_array())
			{

				$proAsUsers[] = array('pro_uid'=>$row['PRO_UID'],'usr_uid'=>$row['USR_UID'],'status'=>$row['USR_STATUS'],
					                   'usr_username'=>$row['USR_USERNAME'],'usr_name'=>$row['USR_FIRSTNAME']);

			}

			return $proAsUsers;

		}
		return false;
	}

	/*返回流程关联的用户组*/
	public function getProcessAsGroups($proId)
	{
		if(empty($proId)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  = "SELECT PROCESS_USER.PRO_UID ,PROCESS_USER.USR_UID AS GRP_UID, CONTENT.CON_VALUE AS GRP_NAME  from PROCESS_USER ";
			$sql .= "LEFT JOIN  CONTENT ON PROCESS_USER.USR_UID = CONTENT.CON_ID where PROCESS_USER.PRO_UID = '{$proId}' AND ";
			$sql .= "PROCESS_USER.PU_TYPE = 'GROUP_SUPERVISOR' AND CONTENT.CON_LANG='en' AND  CONTENT.CON_CATEGORY='GRP_TITLE';";
			$result = $this->DBCon->query($sql);
			if(!$result)	return false;
			$proAsGrps = array();
			while($row = $result->fetch_array())
			{
				$proAsGrps[] = array('pro_uid'=>$row['PRO_UID'],'grp_uid'=>$row['GRP_UID'],'grp_name'=>$row['GRP_NAME']);
			}

			return $proAsGrps;

		}
		return false;
	}


	/*关联用户至工作组*/
	public function assignUserToGroup($usr_uid,$grp_uid)
	{
		if(empty($usr_uid) || empty($grp_uid)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql = "select 1 from GROUP_USER where USR_UID ='{$usr_uid}' and GRP_UID='{$grp_uid}' ;";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;
			$sql = "insert GROUP_USER(USR_UID,GRP_UID) values('{$usr_uid}','{$grp_uid}'); ";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;
		}
		return false;
	}

	/*删除工作组用户*/
	public function unassignUserFromGroup($usr_uid,$grp_uid)
	{
		if(empty($usr_uid) || empty($grp_uid)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql = "delete from GROUP_USER where USR_UID ='{$usr_uid}' and GRP_UID='{$grp_uid}' ;";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;
		}
		return false;
	}

	/*增加流程管理员
	 * @usr_uid 用户或工作组UID
	 * @usr_type 类型 0:用户,1:工作组
	 * @pro_uid 流程UID
	 */
	public function addProcessManager($usr_uid,$usr_type,$pro_uid)
	{
		if(empty($usr_uid)  || empty($pro_uid)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$pu_type = ($usr_type == 0)?'SUPERVISOR':'GROUP_SUPERVISOR';
			$sql  =  "select 1 from  PROCESS_USER where USR_UID ='{$usr_uid}' and PRO_UID='{$pro_uid}' and PU_TYPE='{$pu_type}';";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;
			$pu_uid  = md5($usr_uid.$pu_type.$pro_uid);
			$sql = "insert PROCESS_USER(PU_UID,PRO_UID,USR_UID,PU_TYPE) values('{$pu_uid}','{$pro_uid}','{$usr_uid}','{$pu_type}');";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;
		}
		return false;
	}

	/*删除流程管理员
	 * @usr_uid 用户或工作组UID
	 * @usr_type 类型 0:用户,1:工作组
	 * @pro_uid 流程UID
	 */
	public function removeProcessManager($usr_uid,$usr_type,$pro_uid)
	{
		if(empty($usr_uid) || empty($pro_uid)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  =  "delete from  PROCESS_USER where USR_UID ='{$usr_uid}' and PRO_UID='{$pro_uid}' and PU_TYPE='";
			$sql .=  ($usr_type == 0)?'SUPERVISOR':'GROUP_SUPERVISOR';
			$sql .= "'";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;
		}
		return false;
	}

	/*增加任务用户
	 * @usr_uid 用户或工作组UID
	 * @usr_type 类型 0:用户,1:工作组
	 * @tsk_uid 任务UID
	 */
	public function addTaskUser($usr_uid,$usr_type,$tsk_uid)
	{
		if(empty($usr_uid)  || empty($tsk_uid)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$tu_type = ($usr_type == 0)?1:2;
			$sql  =  "select 1 from  TASK_USER where USR_UID = '{$usr_uid}' and TAS_UID='{$tsk_uid}' and TU_RELATION={$tu_type};";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;

			$sql = "insert TASK_USER(TAS_UID,USR_UID,TU_RELATION) values('{$tsk_uid}','{$usr_uid}',{$tu_type});";
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;
		}
		return false;
	}

	/* 删除流程用户
	 * @usr_uid 用户或工作组UID
	 * @usr_type 类型 0:用户,1:工作组
	 * @tsk_uid 任务UID
	 */
	public function removeTaskUser($usr_uid,$usr_type,$tsk_uid)
	{
		if(empty($usr_uid) || empty($tsk_uid)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql  =  "delete from  TASK_USER where USR_UID ='{$usr_uid}' and TAS_UID='{$tsk_uid}' and TU_RELATION=";
			$sql .=  ($usr_type == 0)?1:2;
			$this->DBCon->query($sql);
			if($this->DBCon->affected_rows > 0) return true;
		}
		return false;
	}

	/* 返回工作组关联用户列表
	 * @groupid 工作组UID
	 */
	public function getGroupUsers($groupid)
	{
		if(empty($groupid)) return false;
		if(!$this->DBCon->connect_errno)
		{
			$this->DBCon->select_db('wf_'.$this->Workspace);
			$sql =  "select USR_UID from  GROUP_USER where GRP_UID ='{$groupid}';";

			$ret = array();
			$result = $this->DBCon->query($sql);
			while($row = $result->fetch_array())
			{
				$ret[] = $row['USR_UID'];
			}
			return $ret;
		}
		return false;
	}

	private function createToVars($arr)
	{
		$vars = array();
		foreach($arr as $key=>$val)
		{
			$obj = new PMVariableStruct();
			$obj->name = $key;
			$obj->value = $val;
			$vars[] = $obj;
		}
		return $vars;
	}

	/* 新建一个流程实例
	 * @processname    流程名称
	 * @variables      流程变量
	 */
	public function newCase($processname,$variables = array(),$routeNext = true)
	{
		if(empty($processname) || !is_array($variables)) return false;
		$prolist = $this->getProcessList();
		if(!$prolist) return false;
		$prouid = '';
		foreach($prolist as $pro)
		{
			if($pro['name'] == $processname)
			{
				$prouid = $pro['guid'];
				break;
			}
		}

		if(empty($prouid)) return false;
		$tasks = $this->getTaskList($prouid);
		if(!$tasks) return false;
		$taskuid = '';
		foreach($tasks as $task)
		{
			if($task['is_start'] == 'TRUE')
			{
				$taskuid = $task['guid'];
				break;
			}
		}
		if(empty($taskuid)) return false;
		$vars = $this->createToVars($variables);
		$params = array(array('sessionId'=>$this->SessionId, 'processId'=>$prouid,
							  'taskId'=>$taskuid, 'variables'=>$vars));
		$result = $this->SoapObj->__SoapCall('newCase', $params);

		if ($result->status_code != 0) return false;

		if(!$routeNext) return $result->caseNumber;

		$caseid = $result->caseId;
		$caseNumber = $result->caseNumber;

		$params = array(array('sessionId'=>$this->SessionId));
		$result = $this->SoapObj->__SoapCall('caseList', $params);
		$casesArray = $result->cases;
		if ($casesArray == (object) NULL) return false;
		$ret = $caseNumber;
		foreach ($casesArray as $case)
		{
			if($case->guid == $caseid)
			{
				$params = array(array('sessionId'=>$this->SessionId,'caseId'=>$caseid, 'delIndex'=>$case->delIndex));
				$result = $this->SoapObj->__SoapCall('routeCase', $params);
				if ($result->status_code != 0) $ret = false;
				break;
			}
		}
		return $ret;
	}

	//清除角色
	public function clearRoles()
	{
		$groups = $this->getGroupList();
		$users = $this->getUserList();
		foreach($users as $user)
		{
			$this->deleteUser($user['guid']);
		}
		foreach($groups as $group)
		{
			$this->deleteGroup($group['guid']);
		}
	}

	/**
	 * [sendVariables 传递参数到实例]
	 * @param  [String] $caseId   [实例guid]
	 * @param  [Array]  $variables [参数数组]
	 * @return [Boolean]
	 */
	public function sendVariables($caseId, $variables) {
		if(!is_array($variables)) return false;
		$vars   = $this->createToVars($variables);
		$params = Array(
	        "sessionId" => $this->SessionId,
	        "caseId"    => $caseId,
	        "variables" => $vars,
	    );
		$response = $this->SoapObj->sendVariables($params);
		return $response->status_code === 0 ? true : false;
	}

	/**
	 * [vars2Array 将getVariables的结果格式化为数组]
	 * @param  [Array] $variables [getVariables返回的参数]
	 * @return [Array]
	 */
	private function vars2Array($variables){
		$vars = Array();
		if(is_object($variables)) {
			$vars[$variables->name] = $variables->value;
		}
		elseif (is_array($variables)) {
			foreach ($variables as $key => $val) {
				$vars[$val->name] = $val->value;
			}
		}
		else {
			return false;
		}
		return $vars;
	}

	/**
	 * [getVariables 获取实例中的参数]
	 * @param  [String] $caseId    [实例guid]
	 * @param  [Array]  $variables [参数数组]
	 * @return [Boolean/Array]     [成功返回数组,失败返回false]
	 */
	public function getVariables($caseId, $variables) {
		if(!is_array($variables)) return false;
		$vars   = $this->createToVars($variables);
		$params = Array(
	        "sessionId" => $this->SessionId,
	        "caseId"    => $caseId,
	        "variables" => $vars,
	    );
		$response = $this->SoapObj->getVariables($params);
		return $response->status_code === 0 ? $this->vars2Array($response->variables) : false;
	}

	/**
	 * [routeCase 路由实例到下一个任务]
	 * @param  [String] $caseId   [实例guid]
	 * @param  [Int]    $delIndex [实例委派编号]
	 * @return [Boolean]
	 */
	public function routeCase($caseId, $delIndex) {
		$params = Array(
	        "sessionId" => $this->SessionId,
	        "caseId"    => $caseId,
	        "delIndex"  => $delIndex,
	    );
		$response = $this->SoapObj->routeCase($params);
		return $response->status_code === 0 ? true : false;
	}

	/**
	 * [caseList 获取实例列表]
	 * @return [Boolean/Array]
	 */
	public function caseList() {
		$caseList = Array();
		$params = Array(
	        "sessionId" => $this->SessionId,
	    );
	    $response = $this->SoapObj->caseList($params);
	    if ($response == (object) NULL) return false;
	    if(is_array($response->cases)){
	    	foreach ($response->cases as $val) {
				$case             = Array();
				$case["guid"]     = $val->guid;
				$case["name"]     = $val->name;
				$case["delIndex"] = $val->delIndex;
				$case["status"]   = $val->status;
				$caseList[]       = $case;
	        }
	    }else {
	    	$caseList[] = Array(
				"guid"     => $response->cases->guid,
				"name"     => $response->cases->name,
				"delIndex" => $response->cases->delIndex,
				"status"   => $response->cases->status,
	    	);
	    }
	    return $caseList;
	}



 }




?>