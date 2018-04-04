<?php
class CallOutReportSHWYModule extends BaseModule
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
			'userid'           =>Array('50','N',false,'关联用户'),
			'agent_name'       =>Array('5','S',false,'座席号码'),
			'name'       	   =>Array('5','S',false,'坐席姓名'),
			'group_name'       =>Array('50','N',false,'业务组'),
			//'dir'              =>Array('20','E',false,'方向'),
			'created_datetime' =>Array('31','DT',false,'呼叫时间'),
			'total_talk_timed' =>Array('3','N',false,'通话时长(秒)'),
			'talk_avg_timed'   =>Array('3','N',false,'平均时长(秒)'),
			'total_num'        =>Array('3','N',false,'总记录数'),
			'total_answered'   =>Array('3','N',false,'总应答数'),
			'lte_one'          =>Array('3','N',false,'T<=1'),
			'lt_three_gt_one'  =>Array('3','N',false,'1<T<3'),
			'gte_three'        =>Array('3','N',false,'T>=3'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'userid'           ,
            'agent_name'       ,
            'group_name'       ,
            'name'			   ,
            //'dir'              ,
            'created_datetime' ,
            'talk_avg_timed'   ,
			'total_talk_timed' ,
			'total_num'        ,
			'total_answered'   ,
			'lte_one'          ,
			'lt_three_gt_one'  ,
			'gte_three'        ,
			);
	//列表字段
	public $listFields = Array(
			'agent_name'       ,
            'group_name'       ,
            'name'			   ,
            'userid'           ,
            //'dir'              ,
			'total_num'        ,
			'total_answered'   ,
			'total_talk_timed' ,
            'talk_avg_timed'   ,
			'lte_one'          ,
			'lt_three_gt_one'  ,
			'gte_three'        ,
			);
	//编辑字段
	public $editFields = Array();
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'agent_name'       ,
            'group_name'       ,
           // 'dir'              ,
            'userid'           ,
            'created_datetime' ,
			'total_num'        ,
			'total_answered'   ,
            'talk_avg_timed'   ,
			'total_talk_timed' ,
			'lte_one'          ,
			'lt_three_gt_one'  ,
			'gte_three'        ,
			);
	//默认排序
	public $defaultOrder = Array('agent_name','ASC');
	//详情入口字段
	public $enteryField = '';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array(
		//'dir' => Array('callin','callout'),
	);

	//字段关联
	public $associateTo = Array(
		'userid' => Array('MODULE','User','detailView','id','user_name'),
		'group_name' => Array('MODULE','GroupManager','detailView','id','name'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'cdr.userid';

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


				if($field == "agent_name"){
					$field = "cdr.agent_name";
					$sqlwhere .= empty($link) ? "({$field} {$cond} {$val})" : "({$field} {$cond} {$val}){$link}";
				}elseif($field == "group_name"){
					$field = "gs.id";
					$sqlwhere .= empty($link) ? "({$field} {$cond} {$val})" : "({$field} {$cond} {$val}){$link}";
				}elseif($field == "userid"){
					$field = "cdr.userid";
					$sqlwhere .= empty($link) ? "({$field} {$cond} {$val})" : "({$field} {$cond} {$val}){$link}";
				}elseif($field == "created_datetime" && ($cond == "=" || $cond == "!=")){
					$field = "DATE_FORMAT(cdr.created_datetime,'%Y-%m-%d')";
					$val = "DATE_FORMAT(".$val.",'%Y-%m-%d')";
					$sqlwhere .= empty($link) ? "({$field} {$cond} {$val})" : "({$field} {$cond} {$val}){$link}";
				}elseif($field == "created_datetime"){
					$field = "cdr.created_datetime";
					$sqlwhere .= empty($link) ? "({$field} {$cond} {$val})" : "({$field} {$cond} {$val}){$link}";
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

	//根据条件返加列表记录总数
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids){
		global $CURRENT_IS_ADMIN,$APP_ADODB;
		$addFields = ",gs.id group_name ";//组名
		$addFields .= ",us.name name ";//坐席姓名
		$addFields .= ",COUNT(*) total_num ";//总记录数
		$addFields .= ",SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 THEN 1 ELSE 0 END) total_answered ";//总应答数
		$addFields .= ",SUM(talk_timed) total_talk_timed ";//通话时长
		$addFields .= ",COALESCE(FORMAT(SUM(talk_timed)/SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 THEN 1 ELSE 0 END),2),0.00) talk_avg_timed ";
		$addFields .= ",SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 AND TIME_TO_SEC(hangup_datetime) - TIME_TO_SEC(answered_datetime) <= 120 THEN 1 ELSE 0 END) lte_one ";//T<=2
		$addFields .= ",SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 AND TIME_TO_SEC(hangup_datetime) - TIME_TO_SEC(answered_datetime) > 120 AND TIME_TO_SEC(hangup_datetime) - TIME_TO_SEC(answered_datetime) < 300 THEN 1 ELSE 0 END) lt_three_gt_one ";//2<T<5
		$addFields .= ",SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 AND TIME_TO_SEC(hangup_datetime) - TIME_TO_SEC(answered_datetime) >= 300 THEN 1 ELSE 0 END) gte_three ";//T>=5
		$where = '';
		$having = '';
		list($qwhere,$qhaving) = $this->formatSqlWhere($queryWhere,$this->fields);
		list($fwhere,$fhaving) = $this->formatSqlWhere($filterWhere,$this->fields);
		$shareWhere = $this->_getSqlShareWhereString($userids,$groupids);
		//where sql
		if($qwhere != '' && $fwhere != '') $where .= " where {$qwhere} and {$fwhere} ";
		elseif($qwhere != '') $where .= " where {$qwhere} ";
		elseif($fwhere != '') $where .= " where {$fwhere} ";
		if($shareWhere != '' AND $where != '') $where .= "AND {$shareWhere} ";
		elseif($shareWhere != '' AND $where == '') $where .= "where {$shareWhere} ";
		//having sql
		if($qhaving != '' && $fhaving != '') $having .= " having {$qhaving} and {$fhaving} ";
		elseif($qhaving != '') $having .= " having {$qhaving} ";
		elseif($fhaving != '') $having .= " having {$fhaving} ";
		$sql = "SELECT COUNT(*) FROM (SELECT cdr.*{$addFields} FROM {$this->baseTable} cdr LEFT JOIN users us ON cdr.agent_name = us.agent_workno LEFT JOIN groups gs ON gs.id = us.groupid {$where} group by agent_name,dir {$having}) cdr";
		$result = $APP_ADODB->Execute($sql);
		if($result->RecordCount()) return $result->fields[0];
		return 0;
	}

	//根据条件返回列表显示的记录集
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows){
		global $CURRENT_IS_ADMIN,$APP_ADODB;
		$addFields = ",gs.id group_name ";//组名
		$addFields .= ",us.name name ";//坐席姓名
		$addFields .= ",COUNT(*) total_num ";//总记录数
		$addFields .= ",SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 THEN 1 ELSE 0 END) total_answered ";//总应答数
		$addFields .= ",SUM(talk_timed) total_talk_timed ";//通话时长
		$addFields .= ",COALESCE(FORMAT(SUM(talk_timed)/SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 THEN 1 ELSE 0 END),2),0.00) talk_avg_timed ";
		$addFields .= ",SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 AND TIME_TO_SEC(hangup_datetime) - TIME_TO_SEC(answered_datetime) <= 120 THEN 1 ELSE 0 END) lte_one ";//T<=2
		$addFields .= ",SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 AND TIME_TO_SEC(hangup_datetime) - TIME_TO_SEC(answered_datetime) > 120 AND TIME_TO_SEC(hangup_datetime) - TIME_TO_SEC(answered_datetime) < 300 THEN 1 ELSE 0 END) lt_three_gt_one ";//2<T<5
		$addFields .= ",SUM(CASE WHEN TIME_TO_SEC(answered_datetime) <> 0 AND TIME_TO_SEC(hangup_datetime) - TIME_TO_SEC(answered_datetime) >= 300 THEN 1 ELSE 0 END) gte_three ";//T>=5
		$where = '';
		$having = '';
		list($qwhere,$qhaving) = $this->formatSqlWhere($queryWhere,$this->fields);
		list($fwhere,$fhaving) = $this->formatSqlWhere($filterWhere,$this->fields);
		$shareWhere = $this->_getSqlShareWhereString($userids,$groupids);
		//where sql
		if($qwhere != '' && $fwhere != '') $where .= " where {$qwhere} and {$fwhere} ";
		elseif($qwhere != '') $where .= " where {$qwhere} ";
		elseif($fwhere != '') $where .= " where {$fwhere} ";
		if($shareWhere != '' AND $where != '') $where .= "AND {$shareWhere} ";
		elseif($shareWhere != '' AND $where == '') $where .= "where {$shareWhere} ";
		//having sql
		if($qhaving != '' && $fhaving != '') $having .= " having {$qhaving} and {$fhaving} ";
		elseif($qhaving != '') $having .= " having {$qhaving} ";
		elseif($fhaving != '') $having .= " having {$fhaving} ";
		$sqlorder = '';
		if($orderby != '' && ($order == 'ASC' || $order == 'DESC'))
			$sqlorder = " order by {$orderby} {$order} ";
		$sql = "SELECT cdr.*{$addFields} FROM {$this->baseTable} cdr LEFT JOIN users us ON cdr.agent_name = us.agent_workno LEFT JOIN groups gs ON gs.id = us.groupid {$where}";
		$result = $APP_ADODB->Execute($sql);
		if($result->RecordCount()) return $result->getArray();
		return false;
	}

};



?>
