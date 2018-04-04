<?php
class PermissionManagerModule
{
	//模块描述
	public $describe = '权限管理模块';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览视图',
			'detailsView' => '详情视图',
			'editView' => '编辑视图',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','格式(php)','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
					'name' => Array('5','S','',true,'用户的登录名帐号','',6,20),
					'description' => Array('5','s','',false,'用户的登录名帐号',''),
					);
	//安全字段,可以控制权限
	public $safeFields = Array('name','description');
	//列表字段
	public $listFields = Array('name','description');
	//编辑字段
	public $editFields = Array('name','description');
	//列表最大行数
	public $listMaxRows = 100;
	//可排序字段
	public $orderbyFields = Array('name');
	//默认排序
	public $defaultOrder = Array('name','ASC');
	//详情入口字段
	public $enteryField = 'name';
	//详细/编辑视图默认列数
	public $defaultColumns = 2;
	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array();
	
	//字段关联
	public $associateTo = Array();
	
	//模块关联
	public $associateBy = Array();

	
	//根据条件返加列表记录总数
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids)
	{
		global $CURRENT_IS_ADMIN,$APP_ADODB;
		$sql = 'select count(*) from permission ';
        $qwhere = formatSqlWhere($queryWhere,$this->fields);
		$fwhere = formatSqlWhere($filterWhere,$this->fields);
		if($qwhere != '' && $fwhere != '') $sql .= " where {$qwhere} and {$fwhere} ";
		elseif($qwhere != '') $sql .= " where {$qwhere} ";
		elseif($fwhere != '') $sql .= " where {$fwhere} ";		

		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->fields[0];
		return 0;
	}
	//根据条件返回列表显示的记录集
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows)
	{
		global $CURRENT_IS_ADMIN,$APP_ADODB;
		$where = '';
        $qwhere = formatSqlWhere($queryWhere,$this->fields);
		$fwhere = formatSqlWhere($filterWhere,$this->fields);
		if($qwhere != '' && $fwhere != '') $where .= " where {$qwhere} and {$fwhere} ";
		elseif($qwhere != '') $where .= " where {$qwhere} ";
		elseif($fwhere != '') $where .= " where {$fwhere} ";		
		$sqlorder = '';	
		if($orderby != '' && ($order == 'ASC' || $order == 'DESC')) 
			$sqlorder = " order by {$orderby} {$order} ";			
		$sql = " select * from permission  {$where} {$sqlorder} limit {$start},{$maxRows};";	
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getArray();
		return false;		
	}
	//返加一条记录
	public function getOneRecordset($id,$userids,$groupids)
	{
		global $CURRENT_IS_ADMIN,$APP_ADODB,$CURRENT_USER_ID;
		$sql = "select * from permission where id={$id}  ";
		
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getArray();
		return false;				
	}
	//
	public function saveOneRecordset($id,$userids,$groupids,$data)
	{
		//buildPermissionFile($data['permissionid'],$id);
		return 0;
	}
	public function deleteOneRecordset($id,$userids,$groupids)
	{
		return 0;
	}

};



?>