<?php
class UserModule extends BaseModule
{
	public $baseTable = 'users';
	//模块描述
	public $describe = '用户管理模块';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'        => '浏览',
			'detailView'   => '详情',
			'editView'     => '编辑',
			'createView'   => '新建',
			'copyView'     => '复制',
			'save'         => '保存',
			'delete'       => '删除',
			'import'       => '导入',
			'export'       => '导出',
			'setPassword'  => '修改密码',
			'modifyFilter' => '编辑过滤',
			'selfSetting'  => '个人设置',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
				    'guid' => Array('101','S',false,'GUID',''),
					'user_name' => Array('110','S',true,'用户的登录名帐号','','index.php?module=User&action=checkNameAvailable','150px','DisableModify'),
					'user_password' => Array('70','P',true,'用户登录密码','',6,20),
					'name' => Array('5','S',true,'用户的姓名',''),
					'birthday' =>  Array('30','D',true,'生日','0000-00-00'),
					'description' => Array('5','S',false,'描述信息',''),
					'is_admin' => Array('21','E',true,'用户是否具有管理员权限','NO'),
					'groupid' => Array('50','N',false,'组','-1'),
					'permissionid' => Array('50','N',false,'权限','-1'),
					'department' => Array('5','S',false,'工作部门名称',''),
					'post' =>  Array('5','S',false,'职务',''),
					'status' => Array('20','E',true,'帐号状态','Active'),
					'activity_time' => Array('5','S',false,'最近活动时间',''),
					'client_address' => Array('5','S',false,'登陆地址',''),
					'accesskey' => Array('5','S',true,'访问码',),
					'title' =>  Array('5','S',false,'浏览器标题',''),
					'phone_home' =>  Array('5','S',false,'住宅电话号码',''),
					'phone_mobile' =>  Array('5','S',false,'手机号码',''),
					'phone_work' =>  Array('5','S',false,'办公电话号码',''),
					'phone_other' =>  Array('5','S',false,'其它电话号码',''),
					'phone_fax' =>  Array('5','S',false,'传真机号码',''),
					'email' =>  Array('5','S',false,'电子邮件',''),
					'qq_number' =>  Array('5','S',false,'QQ号码',''),
					'agent_have' =>  Array('21','E',true,'用户是否具备呼叫中心座席功能','NO'),
					'agent_number' =>  Array('5','S',false,'用户的默认座席号码',''),
					'agent_login' =>  Array('21','E',true,'用户登录时座席登入呼叫中心队列','NO'),
					'agent_popup' =>  Array('21','E',true,'是否允许弹屏','NO'),
					'agent_status' =>  Array('20','E',true,'用户登录时座席的工作状态','OFFLINE'),
					'agent_queue' =>  Array('5','E',true,'用户接收呼叫中心来电的队列名','NONE'),
					'agent_workno' =>  Array('5','E',true,'用户的工号',''),
					'address_country' =>  Array('5','S',false,'国家',''),
					'address_state' =>  Array('5','S',false,'省份',''),
					'address_city' =>  Array('5','S',false,'城市',''),
					'address_street' =>  Array('5','S',false,'街道',''),
					'address_postalcode' =>  Array('5','S',false,'邮编',''),
					'date_created' => Array('30','DT',false,'创建时间',''),
					'date_modified' => Array('5','DT',false,'修改时间',''),
					'modified_user_id' => Array('50','N',false,'修改人',''),
					);
	//安全字段,可以控制权限
	public $safeFields = Array('guid','user_name','name','birthday','is_admin','groupid','permissionid',
								'department','post','title','phone_home','phone_mobile','status',
								'phone_work','phone_other','phone_fax','email','qq_number',
								'agent_have','agent_number',
								'agent_login','agent_queue','agent_workno',
								'agent_popup','activity_time','client_address',
								'agent_status','address_country','address_state','address_city',
								'address_street','address_postalcode','imagename');
	//列表字段
	public $listFields = Array('user_name','name','is_admin','permissionid','groupid','status');
	//编辑字段
	public $editFields = Array('guid','user_name','name','birthday','is_admin','groupid','permissionid',
								'department','post','title','phone_home','phone_mobile','status',
								'phone_work','phone_other','phone_fax','email','qq_number',
								'agent_have','agent_queue','agent_workno','agent_number',
								'agent_login',
								'agent_popup',
								'agent_status','address_country','address_state','address_city',
								'address_street','address_postalcode','imagename');
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array('user_name','name','is_admin','groupid','permissionid','department','status','agent_have','agent_number');
	//默认排序
	public $defaultOrder = Array('user_name','ASC');
	//详情入口字段
	public $enteryField = 'user_name';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array('LBL_USER_BASE'=>Array('3',true,Array('guid','user_name','user_password','name',
																	 'birthday','department','post',
																	 'is_admin','permissionid','groupid',
							                                         'status','activity_time','client_address',
																	 'date_created','date_modified',
																	 'modified_user_id')),
						   'LBL_USER_comaddr'=>Array('3',true,Array('phone_home','phone_mobile','phone_work',
																		'phone_other','phone_fax','email',
																		'qq_number','address_country','address_state',
																		'address_city','address_street','address_postalcode')),
						   'LBL_USER_agent'=>Array('3',true,Array('agent_have','agent_queue','agent_workno','agent_number',
																	   'agent_login',
																	   'agent_popup','agent_status')),
						);
	//枚举字段值
	public $picklist = Array(
		'is_admin' => Array('YES','NO'),
		'status' => Array('Active','Activing','Invalid'),
		'agent_have' => Array('NO','YES'),
		'agent_login' => Array('NO','YES'),
		'agent_popup' => Array('NO','YES'),
		'agent_status' => Array('ONLINE','OFFLINE'),
	);

	//字段关联
	public $associateTo = Array(
		'groupid' => Array('MODULE','GroupManager','detailView','id','name'),
		'permissionid' => Array('MODULE','PermissionManager','detailView','id','name'),
		'modified_user_id' => Array('MODULE','User','detailView','id','user_name'),
	);
	//模块关联
	public $associateBy = Array(
		'associate_loginlog_info' => Array('LoginLog','userid','user_name','ip_address','oper_time','state'),
	);
	//记录权限关联字段名
	public $shareField = 'id';


	public function checkNameAvailable($id,$name)
	{

		$count = $this->getListQueryRecordCount(array(array('user_name','=',$name,'')),null,null,null);
		if($count == 0)
		{
			return true;
		}
		if($count == 1)
		{
			$result = $this->getOneRecordset($id,null,null);
			if($result && $result[0]['user_name'] == $name)
			{
				return true;
			}
			return false;
		}
		return false;
	}


	//更新记录
	public function updateOneRecordset($id,$userids,$groupids,$data)
	{
		global $CURRENT_USER_ID,$APP_ADODB;
		$this->editFields[] = 'modified_user_id';
		$this->editFields[] = 'date_modified';

		$data['modified_user_id'] = $CURRENT_USER_ID;
		$data['date_modified'] = date('Y-m-d H:i:s');
		if(isset($data['permissionid']) && empty($data['permissionid'])) $data['permissionid'] = -1;
		if(isset($data['groupid']) && empty($data['groupid'])) $data['groupid'] = -1;

		$oldData = $this->getOneRecordset($id,$userids,$groupids);
		$oldGroupUID = '';
		$newGroupUID = '';

		if(!empty($oldData[0]['groupid']))
		{
			$result = $APP_ADODB->Execute("select guid from groups where id='{$oldData[0]['groupid']}';");
			if($result && !$result->EOF)
			{
				$oldGroupUID = $result->fields['guid'];
			}
		}
		if(!empty($data['groupid']))
		{
			$result = $APP_ADODB->Execute("select guid from groups where id='{$data['groupid']}';");
			if($result && !$result->EOF)
			{
				$newGroupUID = $result->fields['guid'];
			}
		}

		$this->changeWFGroup($data['guid'],$oldGroupUID,$newGroupUID);
		$this->updateWFRole('modify',$data);
		$ret = parent::updateOneRecordset($id,$userids,$groupids,$data);
		array_pop($this->editFields);
		array_pop($this->editFields);
		cleanPermissionfile();
		buildPermissionFile();

		//工作流


		return $ret;
	}

	//插入一条记录
	public function insertOneRecordset($id,$data)
	{
		global $APP_ADODB;
		$password = md5('123456');
		$data['user_password'] = $password;
		$guid = $this->updateWFRole('create',$data);
		if($guid)
		{
			$data['guid'] = $guid;
			if(!empty($data['groupid']))
			{
				$result = $APP_ADODB->Execute("select guid from groups where id='{$data['groupid']}';");
				if($result && !$result->EOF)
				{
					$grpUID = $result->fields['guid'];
					$this->changeWFGroup($guid,-1,$grpUID);
				}

			}
		}

		$ret = parent::insertOneRecordset($id,$data);
		$this->modifyPassword($id,$password);
		cleanPermissionfile();
		buildPermissionFile();
		return $ret;
	}

	//删除一条记录
	public function deleteOneRecordset($id,$userids,$groupids)
	{
		if($id == '1') return '系统帐号,不能删除！';

		$data = $this->getOneRecordset($id,$userids,$groupids);

		if($data && !empty($data[0]))
		{
			$ret = $this->updateWFRole('delete',$data[0]);
			if($ret === 1)
			{
				return '此帐号的工作流中还有工作，不能删除。<br/>如果确实需要删除，请联系技术支持。';
			}
		}


		$ret = parent::deleteOneRecordset($id,$userids,$groupids);
		cleanPermissionfile();
		buildPermissionFile();
		return $ret;
	}

	//修改密码
	public function modifyPassword($id,$password,$userids = NULL,$groupids = NULL)
	{
		global $APP_ADODB;
		$this->editFields[] = 'user_password';
		$data = $this->getOneRecordset($id,$userids,$groupids);

		if($data && !empty($data[0]))
		{
			$info['guid'] = $data[0]['guid'];
			$info['user_password'] = $password;
			$ret = $this->updateWFRole('modify',$info);
			if(!$ret)
			{
				$info['user_name'] = $data[0]['user_name'];
				$info['name'] = $data[0]['name'];
				$guid = $this->updateWFRole('create',$info);
				if($guid)
				{
					parent::updateOneRecordset($id,null,null,array('guid'=>$guid));
					if(!empty($data[0]['groupid']))
					{
						$result = $APP_ADODB->Execute("select guid from groups where id='{$data[0]['groupid']}';");
						if($result && !$result->EOF)
						{
							$grpUID = $result->fields['guid'];
							$this->changeWFGroup($guid,-1,$grpUID);
						}
					}
				}
			}
		}
		$ret = parent::updateOneRecordset($id,$userids,$groupids,array('user_password'=>$password));
		array_pop($this->editFields);
		return $ret;
	}

	//更新工作流角色数据
	public function updateWFRole($oper,$info)
	{
		if(!is_file(_ROOT_DIR.'/config/workflow.conf.php') ||
		   !is_file(_ROOT_DIR.'/include/workflow/PMApi.class.php') )
		{
			return -1;
		}

		$wfconf = require(_ROOT_DIR.'/config/workflow.conf.php');
		if(!array_key_exists('enable',$wfconf) || !$wfconf['enable']) return -1;
		require_once(_ROOT_DIR.'/include/workflow/PMApi.class.php');
		if(!class_exists('PMApi')) return -1;
		$pm = new PMApi($wfconf);
		if($oper == 'create' && array_key_exists('user_name',$info) && array_key_exists('name',$info) && array_key_exists('user_password',$info))
		{
			return $pm->createUser($info['user_name'],$info['name'],$info['user_password']);
		}
		elseif($oper == 'modify' && array_key_exists('guid',$info))
		{
			$params = array();
			if(array_key_exists('user_name',$info))
			{
				$params['usrname'] = $info['user_name'];
			}
			if(array_key_exists('name',$info))
			{
				$params['name'] = $info['name'];
			}
			if(array_key_exists('user_password',$info))
			{
				$params['password'] = $info['user_password'];
			}
			if(array_key_exists('status',$info))
			{
				$params['status'] = $info['status'];
			}
			return $pm->modifyUser($info['guid'],$params);
		}
		elseif($oper == 'delete' && array_key_exists('guid',$info))
		{
			return $pm->deleteUser($info['guid']);
		}
		return false;
	}

	//工作流用户工作组改变
	public function changeWFGroup($usrUID,$oldGrpUID,$newGrpUID)
	{

		if( $oldGrpUID == $newGrpUID)
		{
			return false;
		}
		if(!is_file(_ROOT_DIR.'/config/workflow.conf.php') ||
		   !is_file(_ROOT_DIR.'/include/workflow/PMApi.class.php') )
		{
			return -1;
		}

		$wfconf = require(_ROOT_DIR.'/config/workflow.conf.php');
		if(!array_key_exists('enable',$wfconf) || !$wfconf['enable']) return -1;
		require_once(_ROOT_DIR.'/include/workflow/PMApi.class.php');
		if(!class_exists('PMApi')) return -1;
		$pm = new PMApi($wfconf);

		$pm->unassignUserFromGroup($usrUID,$oldGrpUID);
		return $pm->assignUserToGroup($usrUID,$newGrpUID);
	}

};



?>
