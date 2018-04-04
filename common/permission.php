<?php
//权限管理函数

function requirePermissionFile($userName,$module)
{
	$permissionFile = _ROOT_DIR."/cache/permission/permission_{$userName}_{$module}.php";
	if(is_file($permissionFile))
	{
		require($permissionFile);
	}	
}

//验证模块访问权限
function validationModulePermission($module = '')
{
	global $CURRENT_USER_NAME,$CURRENT_IS_ADMIN;
	
	if($module == 'MenuManager' || $module == 'ModuleManager' ||
		$module == 'PermissionManager' )
	{
		
		if($CURRENT_IS_ADMIN) return true;
		else return false;
	}	
	if(!isset($CURRENT_USER_NAME)) return false;
	if(empty($module)) $module = _MODULE;
	if(!is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php")) return true;	
	$permiss_class = "permission_{$CURRENT_USER_NAME}_{$module}";
	if(!class_exists($permiss_class))
	{
		requirePermissionFile($CURRENT_USER_NAME,$module);
	}
	if(!class_exists($permiss_class)) return false;
	$permiss = new $permiss_class();
	if(isset($permiss->module) && $permiss->module) return true;
	return false;	
}

//验证模块方法访问权限
function validationActionPermission($module = '' , $action = '')
{
	global $CURRENT_USER_NAME;
	if(!isset($CURRENT_USER_NAME)) return false;
	if(empty($module)) $module = _MODULE;
	if(empty($action)) $action = _ACTION;
	//if(!is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php")) return true;
	$permiss_class = "permission_{$CURRENT_USER_NAME}_{$module}";
	if(!class_exists($permiss_class))
	{
		requirePermissionFile($CURRENT_USER_NAME,$module);
	}
	if(!class_exists($permiss_class)) return false;
	$permiss = new $permiss_class();	

	if(isset($permiss->actions) && isset($permiss->actions[$action]))
	{
		if($permiss->actions[$action])
		{
			return true;
		}
	}
	else
	{
	   return true;
	}
	return false;		
}

//验证字段查看权限 
function validationFieldsShowPermission($module = '',$recordset = Array())
{
	global $CURRENT_USER_NAME;
	if(!isset($CURRENT_USER_NAME)) return false;
	if(empty($module)) $module = _MODULE;
	//if(!is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php")) return $recordset;
	
	$permiss_class = "permission_{$CURRENT_USER_NAME}_{$module}";
	if(!class_exists($permiss_class))
	{
		requirePermissionFile($CURRENT_USER_NAME,$module);
	}
	if(!class_exists($permiss_class)) return false;
	$permiss = new $permiss_class();	

	if(isset($permiss->fields_show))
	{
		foreach($recordset as $rows => $record)
		{
			foreach($record as $fieldname => $valo)
			{
				$val = strval($valo);
				if(isset($permiss->fields_show[$fieldname]) &&
				   isset($permiss->fields_show[$fieldname][0]) &&
				   isset($permiss->fields_show[$fieldname][1]))
				{   
					if($permiss->fields_show[$fieldname][0] == -1 &&
				       $permiss->fields_show[$fieldname][1] == -1)
					{
						$recordset[$rows][$fieldname] = str_repeat('*',mb_strlen($val,'UTF-8'));
					}
					elseif($permiss->fields_show[$fieldname][0] >= 0 &&
				           $permiss->fields_show[$fieldname][1] > 0 )
					{
						$tmpstr1 = mb_substr($val,0,$permiss->fields_show[$fieldname][0],'UTF-8');
						$leng2 = mb_strlen($val,'UTF-8') - $permiss->fields_show[$fieldname][0]+$permiss->fields_show[$fieldname][1];
						$tmpstr2 = mb_substr($val,$permiss->fields_show[$fieldname][0]+$permiss->fields_show[$fieldname][1],$leng2,'UTF-8');
						$recordset[$rows][$fieldname] = $tmpstr1.str_repeat('*',$permiss->fields_show[$fieldname][1]).$tmpstr2;
					}	
				
				}
			}
		}
	}
	return $recordset;
}

//验证字段修改权限
function validationFieldsModifyPermission($module = '',$data = Array())
{
	global $CURRENT_USER_NAME;
	if(!isset($CURRENT_USER_NAME)) return false;
	if(empty($module)) $module = _MODULE;
	//if(!is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php")) return $data;

	$permiss_class = "permission_{$CURRENT_USER_NAME}_{$module}";
	if(!class_exists($permiss_class))
	{
		requirePermissionFile($CURRENT_USER_NAME,$module);
	}
	if(!class_exists($permiss_class)) return false;
	$permiss = new $permiss_class();		

	if(isset($permiss->fields_modify))
	{
		foreach($data as $fieldname => $val)
		{
			if(isset($permiss->fields_modify[$fieldname]) &&
			   !$permiss->fields_modify[$fieldname])
			{
				unset($data[$fieldname]);
			}
		}
	}
	return $data;	
}

//返回字段修改权限
function getFieldsModifyPermission($module = '')
{
	global $CURRENT_USER_NAME;
	if(!isset($CURRENT_USER_NAME)) return false;
	if(empty($module)) $module = _MODULE;
	//if(!is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php")) return true;
	$permiss_class = "permission_{$CURRENT_USER_NAME}_{$module}";
	if(!class_exists($permiss_class))
	{
		requirePermissionFile($CURRENT_USER_NAME,$module);
	}
	if(!class_exists($permiss_class)) return false;
	$permiss = new $permiss_class();			

	if(isset($permiss->fields_modify))
	{
		$mod_fields = Array();
		foreach($permiss->fields_modify as $field_name => $is_modify)
		{
			if($is_modify) $mod_fields[] = $field_name ;
		}
		return $mod_fields;
	}
	return true;	
}


//获取记录关联组id列表
function getRecordset2GroupsPermission($module = '')
{
	global $CURRENT_USER_NAME;
	if(!isset($CURRENT_USER_NAME)) return '-9999';
	if(empty($module)) $module = _MODULE;
	$permiss_class = "permission_{$CURRENT_USER_NAME}_{$module}";
	if(!class_exists($permiss_class))
	{
		requirePermissionFile($CURRENT_USER_NAME,$module);
	}
	if(!class_exists($permiss_class)) return '-9999';
	$permiss = new $permiss_class();	
	if(!isset($permiss->recordset_groups))  return '-9999';	
	return $permiss->recordset_groups;
}

//获取记录关联用户id列表
function getRecordset2UsersPermission($module = '')
{	
	global $CURRENT_USER_NAME;
	if(!isset($CURRENT_USER_NAME)) return '-9999';
	if(empty($module)) $module = _MODULE;
	$permiss_class = "permission_{$CURRENT_USER_NAME}_{$module}";
	if(!class_exists($permiss_class))
	{
		requirePermissionFile($CURRENT_USER_NAME,$module);
	}
	if(!class_exists($permiss_class)) return '-9999';
	$permiss = new $permiss_class();
	if(!isset($permiss->recordset_users))  return '-9999';
	return $permiss->recordset_users;		
}

//重建权限控制文件
function buildPermissionFile($pid = null,$uid = null)
{
	ini_set( 'display_errors', 'Off' );
	global $APP_ADODB;
	$whereuser = '';
	$wherepermission = '';
	if(!empty($uid))
	{
		$whereuser = " AND id = {$uid} ";		
	}
	if(!empty($pid))
	{
		$wherepermission = " WHERE id = {$pid} ";
	}
	//$allgroupsarr = Array();
	$allgroupsstr = '';
	$dballgroups = $APP_ADODB->Execute("select id from groups;");
	while(!$dballgroups->EOF)
	{
		//$allgroupsarr[] = $dballgroups->fields['id'];
		if($allgroupsstr == '') $allgroupsstr = $dballgroups->fields['id'];
		else $allgroupsstr .= ','.$dballgroups->fields['id'];
		$dballgroups->MoveNext();
	}
	if($allgroupsstr == '') $allgroupsstr = '-9999';
	
	$allusersstr = '';
	$allusersarr = Array();	
	$dballusers = $APP_ADODB->Execute("select id,groupid from users;");
	while(!$dballusers->EOF)
	{
		if(!array_key_exists($dballusers->fields['groupid'],$allusersarr))
		{
			$allusersarr[$dballusers->fields['groupid']] = Array();
			$allusersarr[$dballusers->fields['groupid']][] = $dballusers->fields['id'];
		}
		else
		{
			$allusersarr[$dballusers->fields['groupid']][] = $dballusers->fields['id'];
		}

		if($allusersstr == '') $allusersstr = $dballusers->fields['id'];
		else $allusersstr .= ','.$dballusers->fields['id'];
		$dballusers->MoveNext();
	}	
	if($allusersstr == '') $allusersstr = '-9999';
	//echo $allusersstr;
	$permissions = $APP_ADODB->Execute("select id from  permission {$wherepermission};");
	if(!$permissions) return false;
	while(!$permissions->EOF)
	{
		$permissionInfo = Array();
		$permissionId = $permissions->fields['id'];
		$permissionModules = $APP_ADODB->Execute("select * from permission_modules where permissionid = {$permissionId};");
		while(!$permissionModules->EOF)
		{
			$mn = $permissionModules->fields['module_name'];
			$permissionInfo[$mn] = Array(); 
			$permissionInfo[$mn]['is_allow'] = $permissionModules->fields['is_allow'];
			$permissionInfo[$mn]['recordset_groups'] = $permissionModules->fields['recordset_groups'];
			$permissionInfo[$mn]['recordset_users'] = $permissionModules->fields['recordset_users'];			
			$permissionModules->MoveNext();
		}
		$permissionActions = $APP_ADODB->Execute("select * from permission_actions where permissionid = {$permissionId};");
		while(!$permissionActions->EOF)
		{
			$mn = $permissionActions->fields['module_name'];
			$tmp = "'".$permissionActions->fields['action_name']."'=>";
			if($permissionActions->fields['is_allow'] == 'YES')
			{
				$tmp .= "true";
			}
			else
			{
				$tmp .= "false";
			}
			if(!isset($permissionInfo[$mn]['actions'])) 
			{
				$permissionInfo[$mn]['actions'] = $tmp;
			}
			else
			{
				$permissionInfo[$mn]['actions'] .= ','.$tmp;
			}			
			$permissionActions->MoveNext();
		}
		//print_r($permissionInfo);
		$permissionFields = $APP_ADODB->Execute("select * from permission_fields where permissionid = {$permissionId};");
		while(!$permissionFields->EOF)
		{
		    //
			$mn = $permissionFields->fields['module_name'];
			$modify = "'".$permissionFields->fields['field_name']."'=>";
			if($permissionFields->fields['is_modify'] == 'YES')
			{
				$modify .= "true";
			}
			else
			{
				$modify .= "false";
			}
			if(!isset($permissionInfo[$mn]['modifys'])) 
			{
				$permissionInfo[$mn]['modifys'] = $modify;
			}
			else
			{
				$permissionInfo[$mn]['modifys'] .= ','.$modify;
			}

			$show = "'".$permissionFields->fields['field_name']."'=>";
			if($permissionFields->fields['is_show'] == 'YES')
			{
				$show .=  sprintf('Array(%d,%d)',$permissionFields->fields['hidden_start'],$permissionFields->fields['hidden_end']);
			}
			else
			{
				$show .= "Array(-1,-1)";
			}
			if(!isset($permissionInfo[$mn]['shows'])) 
			{
				$permissionInfo[$mn]['shows'] = $show;
			}
			else
			{
				$permissionInfo[$mn]['shows'] .= ','.$show;
			}			
			$permissionFields->MoveNext();
		}
		
		
		$users = $APP_ADODB->Execute("select id,groupid,user_name from users where  permissionid = {$permissionId} {$whereuser};");
		//echo "select id,groupid,user_name from users where  permissionid = {$permissionId} {$whereuser};";
		while(!$users->EOF)
		{
			$un = $users->fields['user_name'];
			foreach($permissionInfo as $m => $v)
			{				
				if($v['recordset_groups'] == 'allgroup')
				{
					$v['recordset_groups'] = $allgroupsstr.',-1';
				}
				elseif($v['recordset_groups'] == 'selfgroup')
				{
					$v['recordset_groups'] = $users->fields['groupid'];
				}
				elseif(empty($v['recordset_groups']))
				{
					$v['recordset_groups'] = '-9999';
				}
				
				if($v['recordset_users'] == 'alluser')
				{
					$v['recordset_users'] = $allusersstr.',-1';
				}
				elseif($v['recordset_users'] == 'selfuser')
				{
					$v['recordset_users'] = $users->fields['id'];
				}
				elseif(empty($v['recordset_users']))
				{
					$v['recordset_users'] = '-9999';
				}
								
				$allgroupsarr = explode(',',$v['recordset_groups']);
				$allusersarr_a = explode(',',$v['recordset_users']);

				foreach($allgroupsarr as $gid)
				{
					if($gid>0 && array_key_exists($gid,$allusersarr))
					{
						$allusersarr_a = array_merge($allusersarr_a,$allusersarr[$gid]);
					}	
				}
				$allusersarr_a = array_unique($allusersarr_a);
				$v['recordset_users'] = '';
				foreach($allusersarr_a as $uid)
				{
					if($v['recordset_users'] == '') $v['recordset_users'] = $uid;
					else $v['recordset_users'] .= ','.$uid;
				}
				
				$f = fopen(_ROOT_DIR."/cache/permission/permission_{$un}_{$m}.php","w");

				$tmp = "<?php\n";
				$tmp .= "class permission_{$un}_{$m}{\n";				
				$tmp .= 'public $module=';
				$tmp .= ($v['is_allow'] == 'YES')?"true;\n":"false;\n";
				if($v['is_allow'] == 'YES')
				{
					$tmp .=	'public $recordset_groups=';
					$tmp .= '\''.$v['recordset_groups']."';\n";
					$tmp .=	'public $recordset_users=';
					$tmp .= '\''.$v['recordset_users']."';\n";				
					$tmp .=	'public $actions=Array(';
					$tmp .= $v['actions'];
					$tmp .= ");\n";
					$tmp .=	'public $fields_show=Array(';
					$tmp .= $v['shows'].");\n";	
					$tmp .=	'public $fields_modify=Array(';
					$tmp .= $v['modifys'].");\n";	
				}	
				$tmp .= "}?>\n";
				//echo $tmp;
				fwrite($f,$tmp);				
				fclose($f);
			}
			$users->MoveNext();
		}		
		$permissions->MoveNext();
	}
}

//清除权限控制文件
function cleanPermissionfile($user='',$module='')
{
	if(empty($user) && empty($module))
	{
		$dirs = scandir(_ROOT_DIR.'/cache/permission/');
		foreach($dirs as $p)
		{
			if(!is_dir(_ROOT_DIR.'/cache/permission/'.$p) && $p != '.' && $p != '..')
			{
				unlink(_ROOT_DIR.'/cache/permission/'.$p);
			}
		}
		return ;
	}
	if(!empty($user))
	{
		$dirs = scandir(_ROOT_DIR.'/cache/permission/');
		$pfx = "permission_{$user}_";
		foreach($dirs as $f)
		{
			if(substr($f,0,strlen($pfx)) == $pfx )
			{
				unlink(_ROOT_DIR.'/cache/permission/'.$f);
			}
		}
		
	}	
	return ;
}


//扫描有权限管理的模块
function searchPermissionModules()
{
	$dirs = scandir(_ROOT_DIR.'/modules/');
	if(!$dirs) return false;
	$modules = Array();
	foreach($dirs as $p)
	{
		if($p == 'Index' || $p== 'MenuManager' || 
		   $p == 'ModuleManager' || $p == 'PermissionManager' ) continue;	
		if(is_dir(_ROOT_DIR.'/modules/'.$p) && $p != '.' && $p != '..')
		{
			if(is_file(_ROOT_DIR."/modules/{$p}/{$p}Module.class.php"))
			{
				$modules[] = $p;
			}
		}
	}
	return $modules;
}

//获取模块需要权限控制的方法
function getPermissionModuleActions($module)
{
	if(!isset($module)) return false;
	$moduleClass = "{$module}Module";
	if(!class_exists($moduleClass))
	{
		$moduleClassFile = _ROOT_DIR."/modules/{$module}/{$moduleClass}.class.php";
		if(is_file($moduleClassFile))
		{
			require($moduleClassFile);
		}
	}
	if(!class_exists($moduleClass)) return false;
	$m = new $moduleClass();
	if(isset($m->actions) && is_array($m->actions))
	{
		return $m->actions;
	}
	return false;
}

//获取模块需要权限控制的字段
function getPermissionModuleFields($module)
{
	if(!isset($module)) return false;
	$moduleClass = "{$module}Module";
	if(!class_exists($moduleClass))
	{
		$moduleClassFile = _ROOT_DIR."/modules/{$module}/{$moduleClass}.class.php";
		if(is_file($moduleClassFile))
		{
			require($moduleClassFile);
		}
	}
	if(!class_exists($moduleClass)) return false;
	$m = new $moduleClass();
	if(isset($m->safeFields) && is_array($m->safeFields))
	{
		return $m->safeFields;
	}
	return false;
}


//刷新权限数据
function refurbishPermissionData($id)
{
	global $APP_ADODB;
	$have_p_mod = searchPermissionModules();
	$activeModlist = Array();
	$sql = 'select module_name from modules ;';
	$moddb = $APP_ADODB->Execute($sql);
	while(!$moddb->EOF)
	{
		if(in_array($moddb->fields['module_name'],$have_p_mod))
		{
			$activeModlist[] = $moddb->fields['module_name'];
		}	
		$moddb->MoveNext();
	}
	$result = $APP_ADODB->Execute("select module_name from permission_modules where permissionid={$id};");
	$oldmodules = Array();
	while(!$result->EOF)
	{
		$oldmodules[] = $result->fields['module_name'];
		$result->MoveNext();
	}
	foreach($oldmodules as $module)
	{
		if(!in_array($module,$activeModlist))
		{
			echo $sql = "delete from permission_modules where permissionid={$id} and module_name = '{$module}';";
			$APP_ADODB->Execute($sql);
			echo $sql = "delete from permission_actives where permissionid={$id} and module_name = '{$module}';";
			$APP_ADODB->Execute($sql);
			echo $sql = "delete from permission_fields where permissionid={$id} and module_name = '{$module}';";
			$APP_ADODB->Execute($sql);			
		}
	}
	
	foreach($activeModlist as $module)
	{
		$sql = "select 1 from permission_modules where permissionid={$id} and module_name ='{$module}';";
		$result= $APP_ADODB->Execute($sql);
		if($result->RecordCount()<1 )
		{
			$sql = "insert into permission_modules(permissionid ,module_name,is_allow) values({$id},'{$module}','YES')";
			$result= $APP_ADODB->Execute($sql);
		}
		unset($mod);
		$moduleClass = "{$module}Module";
		if(!class_exists($moduleClass))
		{
			$moduleClassFile = _ROOT_DIR."/modules/{$module}/{$moduleClass}.class.php";
			if(is_file($moduleClassFile))
			{
				require($moduleClassFile);
			}
		}
		if(class_exists($moduleClass))
		{
			$mod = new $moduleClass();
		}		
		
		$sql = "select action_name from permission_actions where permissionid={$id} and module_name ='{$module}';";
		$result= $APP_ADODB->Execute($sql);
		$oldactions = Array();
		while(!$result->EOF)
		{
			$oldactions[] = $result->fields['action_name'];
			$result->MoveNext();
		}
		$newactions =  Array();
		if(isset($mod->actions))
		{
			foreach($mod->actions as $key => $desc)
			{
				$newactions[] = $key;
			}			
		}

		foreach($newactions as $action)
		{
			if(!in_array($action,$oldactions))
			{
				$sql = "insert into permission_actions(permissionid ,module_name,action_name,is_allow) values({$id},'{$module}','{$action}','YES');";
				$APP_ADODB->Execute($sql);
			}
		}
		
		foreach($oldactions as $action)
		{
			if(!in_array($action,$newactions))
			{
				$sql = "delete from permission_actions where permissionid={$id} and module_name ='{$module}' and action_name='{$action}';";
				$APP_ADODB->Execute($sql);				
			}
		}

		$sql = "select field_name from permission_fields where permissionid={$id} and module_name ='{$module}';"	;
		$result= $APP_ADODB->Execute($sql);	
		$oldfields = Array();
		while(!$result->EOF)
		{
			$oldfields[] = $result->fields['field_name'];
			$result->MoveNext();
		}
		$newfields = Array();
		if(isset($mod->safeFields))
		{
			$newfields = $mod->safeFields;			
		}	

		foreach($newfields as $field)                                                  
		{                                                                             
			if(!in_array($field,$oldfields))                                         
			{
				$sql = "insert into permission_fields(permissionid ,module_name,field_name,is_show,is_modify,hidden_start,hidden_end)";
				$sql .= "values({$id},'{$module}','{$field}','YES','YES',0,0);";
				$APP_ADODB->Execute($sql);
			}
		}
		foreach($oldfields as $field)
		{
			if(!in_array($field,$newfields))
			{
				$sql = "delete from permission_fields where permissionid={$id} and module_name ='{$module}' and field_name='{$field}';";
				$APP_ADODB->Execute($sql);				
			}
		}		
				
	}
	
}

//刷新所有权限数据
function refurbishAllPermissionData()
{
	global $APP_ADODB;
	$result = $APP_ADODB->Execute('select id from permission;');
	while(!$result->EOF)
	{
		refurbishPermissionData($result->fields['id']);	
		$result->MoveNext();
	}
	
}

?>