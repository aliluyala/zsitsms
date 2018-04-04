<?php
class SMSReportModule extends BaseModule
{
	public $baseTable = 'sms_notify';
	//模块描述
	public $describe = '短信统计';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'        => '浏览',
			'detailView'   => '详情',
			'export'       => '导出',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'userid'        => Array('50','N',true,'用户'),
			'groupid'       => Array('50','N',false,'组','-1'),
			'callerid' 		=> Array('5','S',false,'发送号码'),
			'dir'           => Array('20','E',false,'方向'),
			'send_time'     => Array('31','DT',false,'发送时间'),
			'state'         => Array('20','E',false,'状态'),
			'total_sms_num' => Array('5','S',false,'短信条数'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'userid'    	,
			'groupid'		,
			'dir'       	,
			'send_time' 	,
			'state' 		,
			'total_sms_num'	,
			);
	//列表字段
	public $listFields = Array(
			'userid'    	,
			'callerid'		,
			'groupid'		,
			'total_sms_num'	,
			);
	//编辑字段
	public $editFields = Array();
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'userid'    	,
			'groupid'		,
			'dir'       	,
			'send_time' 	,
			'state' 		,
			);
	//默认排序
	public $defaultOrder = Array('groupid','ASC');
	//详情入口字段
	public $enteryField = '';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array(
		'dir'   => Array('send','receive'),
		'state' => Array('wait','success','failure'),
	);

	//字段关联
	public $associateTo = Array(
		'userid'  => Array('MODULE','User','detailView','id','user_name'),
		'groupid' => Array('MODULE','GroupManager','detailView','id','name'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'userid';

	//获取sql记录共享条件字符串
	private function _getSqlShareWhereString($userids,$groupids)
	{
		global $CURRENT_IS_ADMIN,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		if($CURRENT_IS_ADMIN) return '';
		if(empty($userids) && empty($groupids) ) return '';
		$swhere = '';
		if(isset($this->shareField))
		{
			if(empty($userids))
			{
				$swhere = " ({$this->shareField} IN ({$groupids})) ";
			}
			elseif(empty($groupids))
			{
				$swhere = " ({$this->shareField} IN ({$userids})) ";
			}
			else
			{
				$swhere = " (({$this->shareField} IN ({$groupids})) OR ({$this->shareField} IN ({$userids}))) ";
			}
		}

		return $swhere;
	}
	//获取sql查询的条件字符串
	private function _getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids)
	{
		global $CURRENT_IS_ADMIN,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		$qwhere = formatSqlWhere($queryWhere,$this->fields);
		$fwhere = formatSqlWhere($filterWhere,$this->fields);
		$where = '';
		if($qwhere != '' && $fwhere != '') $where = "  (({$qwhere}) AND ({$fwhere})) ";
		elseif($qwhere != '') $where = "  ({$qwhere}) ";
		elseif($fwhere != '') $where = "  ({$fwhere}) ";

		$swhere = $this->_getSqlShareWhereString($userids,$groupids);
		if($where != '' && $swhere != '') $where = " {$where} AND {$swhere} ";
		elseif($swhere != '') $where = " {$swhere} ";

		return $where;
	}
	//获取sql命令的排序字符串
	private function _getSqlOrderbyString($orderby,$order)
	{
		$sqlorder = '';
		if($orderby != '' && ($order == 'ASC' || $order == 'DESC'))
		{
			$sqlorder = " order by {$orderby} {$order} ";
		}
		return $sqlorder;
	}
	//根据条件返回列表记录总数
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids)
	{
		global $APP_ADODB;
		$sql = "select count(distinct userid) from  {$this->baseTable} ";
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
		if($where != '') $sql .= " where {$where}";
		//$sql .= " group by userid ";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->fields[0];
		return false;
	}
	//根据条件返回列表显示的记录集
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows)
	{
		global $APP_ADODB;
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
		$order = $this-> _getSqlOrderbyString($orderby,$order);
		if($where != '') $where = ' where '.$where;
		$sql = "select *,sum(sms_num) total_sms_num from {$this->baseTable} {$where} group by userid {$order} limit {$start},{$maxRows};";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getArray();
		return false;
	}

};



?>
