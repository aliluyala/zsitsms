<?php
class CallOutAnalysisModule extends BaseModule
{
	public $baseTable = 'zswitch_cc_agent_cdr';
	//模块描述
	public $describe = '呼叫统计';
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
			'create_datetime'  => Array('30','D',false,'呼叫时间'),
			'hours'            => Array('3','N',false,'时间'),
			'num'          	   => Array('3','N',false,'通次'),
			'num_valid'        => Array('3','N',false,'有效通次'),
			'talk_time'        => Array('3','N',false,'通时'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'create_datetime' ,
            'hours'           ,
			'num'             ,
			'num_valid'		  ,
            'talk_time'       ,
			);
	//列表字段
	public $listFields = Array(
			'create_datetime' ,
            'hours'           ,
			'num'             ,
			'num_valid'		  ,
            'talk_time'       ,
			);
	//编辑字段
	public $editFields = Array();
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'create_datetime' ,
            'hours'           ,
			'num'             ,
			'num_valid'		  ,
            'talk_time'       ,
			);
	//默认排序
	public $defaultOrder = Array('hours','ASC');
	//详情入口字段
	public $enteryField = '';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array();

	//字段关联
	public $associateTo = Array();
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'userid';

	//生成虚拟字段id
	public function setVirtualKey($list){
		$key = 0;
		$result = array();
		foreach($list as $val){
			$val['id'] = $key;
			$result[] = $val;
			$key++;
		}
		return $result;
	}
	//格式化sql条件
	function formatSqlWhere($where,$fields)
	{
		if(!isset($where) || !is_array($where)) return false;
		if(!isset($fields) || !is_array($fields)) return false;
		$sqlwhere = '';
		$sqlhaving = '';
		foreach($where as  $k => $w)
		{
			if(isset($w[0]) && isset($w[1]) && isset($w[2]) && isset($w[3]))
			{
				$field = $w[0];
				$cond  = $w[1];
				$val   = $w[2];
				$link  = $w[3];

				if($cond == 'like_start')
				{
					$cond = 'like';
					$val = $val.'%';
				}
				elseif($cond == 'like_end')
				{
					$cond = 'like';
					$val = '%'.$val;
				}
				elseif($cond == 'like_contain')
				{
					$cond = 'like';
					$val = '%'.$val.'%';
				}
				elseif($cond == 'like_no_contain')
				{
					$cond = 'not like';
					$val = '%'.$val.'%';
				}

				if(isset($fields[$field]) && isset($fields[$field][1]))
				{
					if($fields[$field][1] != 'N')
					{
						$val = "'{$val}'";
					}
				}

				if($field == "create_datetime"){
					$field = "created_datetime";
					$sqlwhere .= empty($link) ? "(date_format({$field},'%Y-%m-%d') {$cond} {$val})" : "(date_format({$field},'%Y-%m-%d') {$cond} {$val}){$link}";
				}else{
					$sqlhaving .= empty($link) ? "({$field} {$cond} {$val})" : "({$field} {$cond} {$val}){$link}";
				}
			}
		}
		return Array($this->delSuffix($sqlwhere),$this->delSuffix($sqlhaving));
	}

	function delSuffix($sql){
		if(substr($sql,-3, 3) == 'and')
			$sql = substr($sql,0,strlen($sql)-3);
		if(substr($sql, -2, 2) == 'or')
			$sql = substr($sql,0,strlen($sql)-2);
		return $sql;
	}

	//根据条件返加列表记录总数
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids){
		global $CURRENT_IS_ADMIN,$APP_ADODB;
		$fields = "DATE_FORMAT(created_datetime,'%Y-%m-%d') create_datetime ";
		$fields .= ",COUNT(*) num ";
		$fields .= ",SUM(CASE WHEN talk_timed <> 0 THEN 1 ELSE 0 END) num_valid ";
		$fields .= ",SUM(talk_timed) talk_time ";
		$fields .= ",DATE_FORMAT(created_datetime,'%l:00 %p') hours ";
		$where = '';
		$having = '';
		list($qwhere,$qhaving) = $this->formatSqlWhere($queryWhere,$this->fields);
		list($fwhere,$fhaving) = $this->formatSqlWhere($filterWhere,$this->fields);
		$shareWhere = $this->_getSqlShareWhereString($userids,$groupids);
		//where sql
		if($qwhere != '' && $fwhere != '') $where .= " AND {$qwhere} AND {$fwhere} ";
		elseif($qwhere != '') $where .= " AND {$qwhere} ";
		elseif($fwhere != '') $where .= " AND {$fwhere} ";
		if($shareWhere != '' AND $where != '') $where .= "AND {$shareWhere} ";
		elseif($shareWhere != '' AND $where == '') $where .= "AND {$shareWhere} ";
		//having sql
		if($qhaving != '' && $fhaving != '') $having .= " having {$qhaving} AND {$fhaving} ";
		elseif($qhaving != '') $having .= " having {$qhaving} ";
		elseif($fhaving != '') $having .= " having {$fhaving} ";
		if(empty($where)){
			 $where = "and date_format(created_datetime,'%Y-%m-%d') = current_date()";
		}
		$sql = "select count(*) from (select {$fields} from zswitch_cc_agent_cdr where dir = 'callout' {$where} group by create_datetime,hours {$having}) cdr;";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->fields[0];
		return false;
	}

	//根据条件返回列表显示的记录集
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows){
		global $CURRENT_IS_ADMIN,$APP_ADODB;
		$fields = "DATE_FORMAT(created_datetime,'%Y-%m-%d') create_datetime ";
		$fields .= ",COUNT(*) num ";
		$fields .= ",SUM(CASE WHEN talk_timed <> 0 THEN 1 ELSE 0 END) num_valid ";
		$fields .= ",SUM(talk_timed) talk_time ";
		$fields .= ",DATE_FORMAT(created_datetime,'%l:00 %p') hours ";
		$where = '';
		$having = '';
		list($qwhere,$qhaving) = $this->formatSqlWhere($queryWhere,$this->fields);
		list($fwhere,$fhaving) = $this->formatSqlWhere($filterWhere,$this->fields);
		$shareWhere = $this->_getSqlShareWhereString($userids,$groupids);
		//where sql
		if($qwhere != '' && $fwhere != '') $where .= " AND {$qwhere} AND {$fwhere} ";
		elseif($qwhere != '') $where .= " AND {$qwhere} ";
		elseif($fwhere != '') $where .= " AND {$fwhere} ";
		if($shareWhere != '' AND $where != '') $where .= "AND {$shareWhere} ";
		elseif($shareWhere != '' AND $where == '') $where .= "AND {$shareWhere} ";
		//having sql
		if($qhaving != '' && $fhaving != '') $having .= " having {$qhaving} AND {$fhaving} ";
		elseif($qhaving != '') $having .= " having {$qhaving} ";
		elseif($fhaving != '') $having .= " having {$fhaving} ";
		$sqlorder = ' ORDER BY created_datetime ASC';
		if($orderby != '' && ($order == 'ASC' || $order == 'DESC'))
			$sqlorder = " order by {$orderby} {$order} ";
		if(empty($where)){
			 $where = "and date_format(created_datetime,'%Y-%m-%d') = current_date()";
		}
		$sql = "select {$fields} from zswitch_cc_agent_cdr where dir = 'callout' {$where} group by create_datetime,hours {$having} {$sqlorder} limit {$start},{$maxRows};";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $this->setVirtualKey($result->getArray());
		return false;
	}

	//获取sql记录共享条件字符串
	function _getSqlShareWhereString($userids,$groupids)
	{
		global $CURRENT_IS_ADMIN,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		if($CURRENT_IS_ADMIN) return '';
		if(empty($userids) && empty($groupids) ) return '';
		if($groupids = -1) $groupids = '';//屏蔽userid = -1
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

};



?>
