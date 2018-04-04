<?php
class GroupManagerModule extends BaseModule
{
	//模块表
	public $baseTable = 'groups';
	//模块描述
	public $describe = '组管理模块';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'editView' => '编辑',
			'createView' => '新建',
			'copyView' => '复制',
			'save' => '保存',
			'delete' => '删除',
			'import' =>  '导入',
			'export' =>  '导出',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
					'guid' => Array('101','S',false,'GUID',''),
					'name' => Array('110','S',true,'组的名称','','index.php?module=GroupManager&action=checkNameAvailable','150px','DisableModify'),
					'description' => Array('5','S',false,'对组的描述说明',''),
					);
	//列表字段
	public $listFields = Array('name','description');

	//编辑字段
	public $editFields = Array('guid','name','description');


	//详情入口字段
	public $enteryField = 'name';
	//详细/编辑视图默认列数
	public $defaultColumns = 2;
	//记录权限关联字段名
	public $shareField = 'id';

	//模块关联
	public $associateBy = Array(
		'associate_user_info' => Array('User','groupid','user_name','name','is_admin'),
	);


	public function checkNameAvailable($id,$name)
	{

		$count = $this->getListQueryRecordCount(array(array('name','=',$name,'')),null,null,null);
		if($count == 0)
		{
			return true;
		}
		if($count == 1)
		{
			$result = $this->getOneRecordset($id,null,null);
			if($result && $result[0]['name'] == $name)
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
		$ret = parent::updateOneRecordset($id,$userids,$groupids,$data);
		$this->updateWFGroup('modify',$data['guid'],$data['name']);
		return $ret;
	}

	//插入一条记录
	public function insertOneRecordset($id,$data)
	{
		if(!$this->checkNameAvailable($id,$data['name']))
		{
			return '工作组名称重复，保存失败！';
		}
		$guid = $this->updateWFGroup('create','',$data['name']);
		if(!empty($guid))
		{
			$data['guid'] = $guid;
		}
		$ret = parent::insertOneRecordset($id,$data);
		return $ret;
	}

	//删除一条记录
	public function deleteOneRecordset($id,$userids,$groupids)
	{
		$data = $this->getOneRecordset($id,$userids,$groupids);
		if($data && !empty($data[0]))
		{
			$this->updateWFGroup('delete',$data[0]['guid']);
		}
		$ret = parent::deleteOneRecordset($id,$userids,$groupids);
		return $ret;
	}

	//更新工作流角色数据
	public function updateWFGroup($oper,$guid,$name='')
	{
		if(!is_file(_ROOT_DIR.'/config/workflow.conf.php') || !is_file(_ROOT_DIR.'/include/workflow/PMApi.class.php'))
		{
			return false;
		}

		$wfconf = require(_ROOT_DIR.'/config/workflow.conf.php');
		if(!array_key_exists('enable',$wfconf) || !$wfconf['enable']) return false;
		require_once(_ROOT_DIR.'/include/workflow/PMApi.class.php');
		if(!class_exists('PMApi')) return false;
		$pm = new PMApi($wfconf);

		if($oper == 'create' && !empty($name))
		{
			return $pm->createGroup($name);
		}
		elseif($oper == 'modify' && !empty($guid) && !empty($name))
		{
			return $pm->modifyGroup($guid,$name);
		}
		elseif($oper == 'delete' && !empty($guid))
		{
			return $pm->deleteGroup($guid);
		}

		return false;
	}

};



?>