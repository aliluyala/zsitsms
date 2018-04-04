<?php
class ComxAnalysisModule extends BaseModule
{
	public $baseTable = 'accounts';
	//模块描述
	public $describe = '综合分析报表';
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
			/*
			'userid'              => Array('50','N',false,'关联客户'),
			'groupid'             => Array('50','N',false,'关联组别'),
			'first_dial'          => Array('5','S',false,'首播完成'),
			'appointment'         => Array('5','S',false,'预约完成'),
			'success'             => Array('5','S',false,'成功数'),
			'failure'             => Array('5','S',false,'失败数'),
			'create_date'         => Array('30','D',false,'呼叫时间'),
			*/

			'expiration_month'       => array('20','E',false,'保险到期月份'),
			'register_month'         => array('20','E',false,'注册月份'),
			'expiration_date'        => array('31','DT',false,'保险到期时间'),
			'register_date'          => array('30','D',false,'注册日期'),

			'user_name'              => Array('50','N',false,'工号'),
			'batch'            		 => Array('20','E',false,'批次'),
			'name'                   => Array('50','N',false,'姓名'),
			'groupname'              => Array('50','N',false,'组'),
			'total'                  => Array('5','S',false,'总名单'),
			'valid_total'            => Array('5','S',false,'目标名单'),
			'first_dial_complate'    => Array('5','S',false,'首拨完成'),
			'first_dial_process'     => Array('5','S',false,'首拨进度'),
			'invalid_num'            => Array('5','S',false,'无效名单'),
			'failed_num'             => Array('5','S',false,'失败数'),
			'quotation_num'          => Array('5','S',false,'报价数'),
			'quotation_rating'       => Array('5','S',false,'报价率'),
			'success_num'            => Array('5','S',false,'意向成功数'),
			'appointment_num'        => Array('5','S',false,'预约数'),
			'appointment_total'      => Array('5','S',false,'总预约'),

			'appointment_proportion' => Array('5','S',false,'预约占比'),
			'track_failed_num'       => Array('5','S',false,'失败通序'),
			'track_appointment_num'  => Array('5','S',false,'总通序'),
			'track_success_num'      => Array('5','S',false,'成功通序'),

			'orphan_num'             => Array('5','S',false,'孤儿单'),
			'renewal_num'            => Array('5','S',false,'续保'),
			'preset_time'            => Array('31','DT',false,'预约时间'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'expiration_month'      ,
			'register_month'        ,
			'expiration_date'       ,
			'register_date'         ,

			'user_name'             ,
			'batch'				    ,
			'name'                  ,
			'groupname'             ,
			'total'                 ,
			'valid_total'           ,
			'first_dial_complate'   ,
			'first_dial_process'    ,
			'invalid_num'           ,
			'failed_num'            ,
			'quotation_num'         ,
			'quotation_rating'      ,
			'success_num'           ,
			'appointment_num'       ,
			'appointment_total'     ,
			

			'appointment_proportion',
			'track_failed_num'      ,
			'track_appointment_num' ,
			'track_success_num'     ,

			'orphan_num'            ,
			'renewal_num'           ,
			'preset_time'           ,
			);
	//列表字段
	public $listFields = Array(
			'user_name'          ,
			'name'               ,
			'groupname'          ,
			'total'              ,
			'valid_total'        ,
			'first_dial_complate',
			'first_dial_process' ,
			'invalid_num'        ,
			'failed_num'         ,
			'quotation_num'      ,
			'quotation_rating'   ,
			'success_num'        ,
			'appointment_num'    ,
			'appointment_total'  ,

			'appointment_proportion',
			'track_failed_num'      ,
			'track_appointment_num' ,
			'track_success_num'     ,
			'renewal_num'        ,
			'orphan_num'         ,
			);
	//编辑字段
	public $editFields = Array();
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'user_name'          ,
			'name'               ,
			'groupname'          ,
			'batch'				 ,
			'expiration_date'    ,
			'expiration_month'   ,
			'register_date'      ,
			'register_month'     ,
			'preset_time'        ,
			);
	//默认排序
	public $defaultOrder = Array('user_name','ASC');
	//详情入口字段
	public $enteryField = '';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array(
		'batch'			   => Array('A','B','C','D','E','F','G','H'),
		'expiration_month' => array('','01','02','03','04','05','06','07','08','09','10','11','12'),
		'register_month'   => array('','01','02','03','04','05','06','07','08','09','10','11','12'),
	);

	//字段关联
	public $associateTo = Array(
		'user_name' => array('MODULE','User','detailView','id','user_name'),
		'name'      => array('MODULE','User','detailView','id','name'),
		'groupname' => array('MODULE','GroupManager','detailView','id','name'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'id';

	public function __construct(){
		global $APP_ADODB;
		$this->adb = $APP_ADODB;
	}

	public function getUsersList($where4user,$order,$start,$maxRows){
		$sql = "SELECT id,id user_name,id name,groupid groupname FROM users {$where4user} {$order} limit {$start},{$maxRows}";
		$result = $this->adb->Execute($sql); 
		if($result) return $result->getarray();
		return false;
	}
	//ALTER TABLE accounts ADD INDEX status_user(status,user_attach);
	//ALTER TABLE accounts ADD INDEX comx_index(expiration_date,status,user_attach,register_date);
	//ALTER TABLE accounts ADD INDEX comx_sue_index(status,user_attach,expiration_date);
	public function getTotalList($data){
		$sql = "SELECT user_attach,";
		$sql .= "COUNT(*) {$data['FIELD']} ";
		$sql .= "FROM accounts ";
		$sql .= "WHERE 1=1 {$data['WHERE']} ";
		$sql .= "GROUP BY user_attach";
		$result = $this->adb->Execute($sql); 
		if($result) return $result->getarray();
		return false;
	}
	public function getTargetList($where4count,$data){
		$sql = "SELECT user_attach,";
		$sql .= "COUNT(*) {$data['FIELD']} ";
		$sql .= "FROM accounts ";
		$sql .= "{$where4count} {$data['WHERE']} ";
		$sql .= "GROUP BY user_attach";// echo $sql; exit;
		$result = $this->adb->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}
	public function getTrackList($where4count,$data){
		$sql = "SELECT at.user_create,";
		$sql .= "COUNT(*) {$data['FIELD']} ";
		$sql .= "FROM account_track at LEFT JOIN accounts ac ON ac.id = at.accountid ";
		$sql .= "{$where4count} {$data['WHERE']} ";
		$sql .= "GROUP BY at.user_create";
		$result = $this->adb->Execute($sql); 
		if($result) return $result->getarray();
		return false;
	}
	public function getCallList($where4count,$data){
		$sql = "SELECT zc.userid,";
		$sql .= "COUNT(*) {$data['FIELD']} ";
		$sql .= "FROM zswitch_cc_agent_cdr zc LEFT JOIN accounts ac ON ac.mobile = zc.other_number ";	
		$sql .= "{$where4count} {$data['WHERE']} ";
		$sql .= "GROUP BY zc.userid";
		$result = $this->adb->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}
	public function combination(){
		$result = Array();
		$args_arr = func_get_args();
		if(isset($args_arr) && !empty($args_arr)){
			foreach($args_arr[0] as $key => $val){
		    	$result[$key] = $val;
				foreach($args_arr as $list){
					if(isset($list[$key]))
						$result[$key] += $list[$key];
				}
			}
		}
		return $result;
	}
	function microtime_float(){
		list($usec,$sec) = explode(" ",microtime());
		return ((float)$usec+(float)$sec);
	}
	/*function microtime_float(){ 
		return microtime(true);
	}*/
	/*public function getTotalList(){
		$sql = "SELECT user_attach,";
		$sql .= "COUNT(*) total,";
		$sql .= "SUM(CASE WHEN status = 'APPOINTMENT_QUOTATION' OR status = 'APPOINTMENT_NON_QUOTATION' THEN 1 ELSE 0 END) appointment_total ";
		$sql .= "FROM accounts GROUP BY user_attach";
		$result = $this->adb->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}*/
	/*public function getTargetList($where4count){
		$sql = "SELECT user_attach,";
		$sql .= "COUNT(*) valid_total,";
		$sql .= "SUM(CASE WHEN status <> 'FIRST_DIAL' THEN 1 ELSE 0 END) first_dial_complate,";
		$sql .= "SUM(CASE WHEN status <> 'FIRST_DIAL' THEN 1 ELSE 0 END) first_dial_process,";
		$sql .= "SUM(CASE WHEN status = 'INVALID' THEN 1 ELSE 0 END) invalid_num,";
		$sql .= "SUM(CASE WHEN status = 'FAILED' THEN 1 ELSE 0 END) failed_num,";
		$sql .= "SUM(CASE WHEN status = 'APPOINTMENT_QUOTATION' THEN 1 ELSE 0 END) quotation_num,";
		$sql .= "SUM(CASE WHEN status <> 'INVALID' THEN 1 ELSE 0 END) quotation_rating,";
		$sql .= "SUM(CASE WHEN status = 'SUCCESS' THEN 1 ELSE 0 END) success_num,";
		$sql .= "SUM(CASE WHEN status = 'APPOINTMENT_QUOTATION' OR status = 'APPOINTMENT_NON_QUOTATION' THEN 1 ELSE 0 END) appointment_num,";
		$sql .= "SUM(CASE WHEN type = 'ORPHAN' THEN 1 ELSE 0 END) orphan_num,";
		$sql .= "SUM(CASE WHEN type = 'RENEWAL' THEN 1 ELSE 0 END) renewal_num ";
		$sql .= "FROM accounts {$where4count} GROUP BY user_attach";
		$result = $this->adb->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}*/
	/*public function getTrackList($where4count){
		$sql = "SELECT at.user_create,";
		$sql .= "SUM(CASE WHEN ac.status = 'FAILED' THEN 1 ELSE 0 END) track_failed_num,";
		$sql .= "SUM(CASE WHEN ac.status = 'APPOINTMENT_QUOTATION' OR ac.status = 'APPOINTMENT_NON_QUOTATION' THEN 1 ELSE 0 END) track_appointment_num,";
		$sql .= "SUM(CASE WHEN ac.status = 'SUCCESS' THEN 1 ELSE 0 END) track_success_num ";
		$sql .= "FROM account_track at LEFT JOIN accounts ac ON ac.id = at.accountid {$where4count} GROUP BY at.user_create";
		$result = $this->adb->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}*/
	/*public function combination($userList,$totalList,$targetList,$trackList){
		$result = array();
		foreach($userList as $key => $val){
		    $result[$key] = $val;
		    if(isset($totalList[$key]))
		        $result[$key] += $totalList[$key];
		    if(isset($targetList[$key]))
		        $result[$key] += $targetList[$key];
		    if(isset($trackList[$key]))
		        $result[$key] += $trackList[$key];
		}
		return $result;
	}*/
	/*public function run($where4user,$where4count,$order,$start,$maxRows){
		$userList   = $this->realign($this->getUsersList($where4user,$order,$start,$maxRows),'user_name');
		$totalList  = $this->realign($this->getTotalList(),'user_attach');
		$targetList = $this->realign($this->getTargetList($where4count),'user_attach');
		$trackList  = $this->realign($this->getTrackList($where4count),'user_create');
		return $this->formatResult($this->combination($userList,$totalList,$targetList,$trackList));
	}*/
	public function realign($data,$field){
		$result = array();
		if(isset($data) && !empty($data)){
			foreach($data as $value){
				foreach($value as $key => $val){
					if(is_string($key)){
						$result[$value[$field]][$key] = $val;
					}
				}
				//$result[$val[$field]] = $val;
			}
		}
		return $result;
	}
	public function formatResult($data){
		foreach($data as $key => $val){
			foreach($this->listFields as $field){
				if(!isset($val[$field])){
					$data[$key][$field] = 0;
				}
			}
		    $data[$key]['quotation_rating']       = isset($data[$key]['quotation_num']) && ($data[$key]['valid_total'] - $data[$key]['quotation_rating'] != 0) ? round($data[$key]['quotation_num']/$data[$key]['quotation_rating'],3) : 0;
            $data[$key]['first_dial_process']     = isset($data[$key]['first_dial_process']) && $data[$key]['valid_total'] != 0 ? round($data[$key]['first_dial_process']/$data[$key]['valid_total'],3) : 0;
            $data[$key]['track_failed_num']       = isset($data[$key]['track_failed_num']) && $data[$key]['failed_num'] != 0 ? round($data[$key]['track_failed_num']/$data[$key]['failed_num'],3) : 0;
            $data[$key]['track_appointment_num']  = isset($data[$key]['track_appointment_num']) && $data[$key]['first_dial_complate'] != 0 ? round($data[$key]['track_appointment_num']/$data[$key]['first_dial_complate'],3) : 0;
            $data[$key]['track_success_num']      = isset($data[$key]['track_success_num']) && $data[$key]['success_num'] != 0 ? round($data[$key]['track_success_num']/$data[$key]['success_num'],3) : 0;
            $data[$key]['appointment_proportion'] = isset($data[$key]['appointment_num']) && $data[$key]['appointment_total'] != 0 ? round($data[$key]['appointment_num']/$data[$key]['appointment_total'],3) : 0;
        }
		return $data;
	}
	public function getWhere($queryWhere,$filterWhere,$userids,$groupids,$isUserList = 1,$shareField = FALSE){
		$queryWhere = $this->formatConditions($this->getListWhere($queryWhere,$isUserList));
		$filterWhere = $this->formatConditions($this->getListWhere($filterWhere,$isUserList));
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids,$shareField);
		if($where != '') $where = ' where '.$where; 
		return $where;
	}
	public function getOrder($orderby,$order){
		return $this->_getSqlOrderbyString($orderby,$order);
	}
	public function getListWhere($where,$isUserList = 1){
		$conditions = array(
							array('expiration_date','expiration_month','register_date','register_month','batch','preset_time'),
							array('user_name','name','groupname'),
							);
		$new_where = array();
		foreach($where as $key => $val){
			if(in_array($val[0], $conditions[$isUserList])){
				$new_where[$key] = $val;
			}
		}
		end($new_where);
		if(isset($new_where[key($new_where)][3]))
			$new_where[key($new_where)][3] = "";
		return $new_where;
	}
	public function formatConditions($where){
		foreach($where as $key => $val){
			if($val[0] == "user_name" || $val[0] == "name")
				$where[$key][0] = "id";
			if($val[0] == "groupname")
				$where[$key][0] = "groupid";
			if($val[0] == "expiration_month")
				$where[$key][0] = "DATE_FORMAT(expiration_date,'%m')";
			if($val[0] == "register_month")
				$where[$key][0] = "DATE_FORMAT(register_date,'%m')";
		}
		return $where;
	}
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids){
		$where  = $this->getWhere($queryWhere,$filterWhere,$userids,$groupids,1);
		$sql = "SELECT COUNT(*) FROM users {$where}"; 
		$result = $this->adb->Execute($sql);
		if($result) return $result->fields[0];
		return false;
	}
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows){
		$where4user  = $this->getWhere($queryWhere,$filterWhere,$userids,$groupids,1,TRUE);
		$where4count = $this->getWhere($queryWhere,$filterWhere,$userids,$groupids,0);
		$order = $this->getOrder($orderby,$order);
		return $this->formatResult($this->run($where4user,$where4count,$order,$start,$maxRows));   
	}
	public function run($where4user,$where4count,$order,$start,$maxRows){
		$userList          = $this->realign($this->getUsersList($where4user,$order,$start,$maxRows),'user_name');
		$total             = $this->realign($this->getTotalList(Array("FIELD"=>"total","WHERE"=>"")),"user_attach");
		$appointment_total = $this->realign($this->getTotalList(Array("FIELD"=>"appointment_total","WHERE"=>"AND status = 'APPOINTMENT_QUOTATION' OR status = 'APPOINTMENT_NON_QUOTATION'")),"user_attach");
		if($where4count == "" || empty($where4count))
			$where4count = "WHERE 1=1 ";
		$valid_total           = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"valid_total","WHERE"=>"")),"user_attach");
		$first_dial_complate   = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"first_dial_complate","WHERE"=>"AND status <> 'FIRST_DIAL'")),"user_attach");
		$first_dial_process    = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"first_dial_process","WHERE"=>"AND status <> 'FIRST_DIAL'")),"user_attach");
		$invalid_num           = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"invalid_num","WHERE"=>"AND status = 'INVALID'")),"user_attach");
		$failed_num            = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"failed_num","WHERE"=>"AND status = 'FAILED'")),"user_attach");
		$quotation_num         = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"quotation_num","WHERE"=>"AND status = 'APPOINTMENT_QUOTATION'")),"user_attach");
		$quotation_rating      = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"quotation_rating","WHERE"=>"AND status <> 'INVALID'")),"user_attach");
		$success_num           = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"success_num","WHERE"=>"AND status = 'SUCCESS'")),"user_attach");
		$appointment_num       = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"appointment_num","WHERE"=>"AND (status = 'APPOINTMENT_QUOTATION' OR status = 'APPOINTMENT_NON_QUOTATION')")),"user_attach");
		$orphan_num            = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"orphan_num","WHERE"=>"AND type = 'ORPHAN'")),"user_attach");
		$renewal_num           = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"renewal_num","WHERE"=>"AND type = 'RENEWAL'")),"user_attach");
		$first_dial_no_num     = $this->realign($this->getTargetList($where4count,Array("FIELD"=>"first_dial_no_num","WHERE"=>"AND status <> 'FIRST_DIAL'")),"user_attach");

		$track_failed_num      = $this->realign($this->getTrackList($where4count,Array("FIELD"=>"track_failed_num","WHERE"=>"AND ac.status = 'FAILED'")),"user_create");
		$track_appointment_num = $this->realign($this->getTrackList($where4count,Array("FIELD"=>"track_appointment_num","WHERE"=>"AND ac.status <> 'FIRST_DIAL'")),"user_create"); 
		$track_success_num     = $this->realign($this->getTrackList($where4count,Array("FIELD"=>"track_success_num","WHERE"=>"AND ac.status = 'SUCCESS'")),"user_create");
		return $this->combination($userList,$total,$appointment_total,$valid_total,$first_dial_complate,$first_dial_process,$invalid_num,$failed_num,$quotation_num,$quotation_rating,$success_num,$appointment_num,$orphan_num,$renewal_num,$track_failed_num,$track_appointment_num,$track_success_num);
	}
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
	private function _getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids,$shareField = FALSE)
	{
		global $CURRENT_IS_ADMIN,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
		$qwhere = formatSqlWhere($queryWhere,$this->fields); 
		$fwhere = formatSqlWhere($filterWhere,$this->fields);
		$where = '';
		if($qwhere != '' && $fwhere != '') $where = "  {$qwhere} AND {$fwhere} ";
		elseif($qwhere != '') $where = "  {$qwhere} ";
		elseif($fwhere != '') $where = "  {$fwhere} ";
		if($shareField){
			$swhere = $this->_getSqlShareWhereString($userids,$groupids);
			if($where != '' && $swhere != '') $where = " {$where} AND {$swhere} ";
			elseif($swhere != '') $where = " {$swhere} ";
		}

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
	

};



?>
