<?php
class HandOut_FinancialModule extends BaseModule
{
	public $baseTable = 'accounts_financial';
	//关联表
	public $associateTable = Array(
		"TRACK"  => "account_track",
		"POLICY" => "policy_draft_com",
	);
	//模块描述
	public $describe = '分发管理';
	//需要控制访问权限的模块方法
    public $actions = array(
			'index'        => '筛选',
			'step1'		   => '操作',
			'step2'		   => '完成',
			'handout'	   => '分发',
			'recycle'      => '回收',
			'transfer'     => '转移',
			'delete'	   => '删除',
			);
	//字段定义
	//'字段名'=>array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = array(
			'owner_total'		  => array('5','S',false,'拥有数量'),
			'area'                => array('20','E',false,'片区'),
			'park'                => array('26','S',false,'园区'),
			'batch'               => array('20','E',false,'批次'),
			'type'                => array('20','E',false,'名单类型'),
			'register_date'       => array('30','D',false,'注册开始日期'),
			'register_date_end'   => array('30','D',false,'注册结束日期'),
			'register_month'      => array('20','E',false,'注册月份'),
			'status'              => array('20','E',false,'状态'),
			'report'           	  => array('26','S',false,'销售说明'),
            'preset_time'         => Array('31','DT',false,'预约日期',"0000-00-00 00:00:00"),
			'date_create'         => array('31','DT',false,'记录创建的时间'),
			'date_create_end'     => array('31','DT',false,'记录创建的结束时间'),
			'user_create'         => array('51','N',false,'创建记录的操作员'),
			'user_attach'         => array('51','N',false,'记录归属于用户'),
			'group_attach'        => array('51','N',false,'记录归属于组'),

			'handout_wait'        => array('17','S',false,'待分发名单数',0,0,999999),
			'handout_num'         => array('6','S',true,'分配数量',0,0,999999),
			'handout_way'         => array('21','E',false,'分发方式','HANDOUT_BY_SIT'),
			'handout_sit'         => array('101','S',false,'分发人员'),
			'handout_group'       => array('50','N',false,'按组分发'),

			'deleted'		   => Array('22','E',false,'是否删除',0),
			'distribute_date'  => Array('31','DT',false,'操作时间'),
			'distribute_user'  => Array('50','N',false,'操作员'),
			'distribute_option'=> Array('20','E',false,'操作'),
			'last_user_attach' => Array('55','N',false,'上次归属于'),
			);
	//安全字段,可以控制权限
	public $safeFields = array(
			'area'			 ,
			'park'			 ,
			'batch'			 ,
			'type'			 ,
			'register_date'  ,
			'register_month' ,
			'date_create'	 ,
			'date_create_end',
			'status'		 ,
			'report'         ,
			'user_create'	 ,
			);
	//列表字段
	public $listFields = array(
			'user_attach'	 ,
			'owner_total'	 ,
			);
	//编辑字段
	public $editFields = array(
			'user_create'	 ,
			'user_attach'	 ,
			'group_attach'	 ,
			'date_create'	 ,
			'date_create_end',
			'area'			 ,
			'park'			 ,
			'batch'			 ,
			'type'			 ,
			'register_date'  ,
			'register_month' ,
			'status'		 ,
			'report'         ,
			);
	//允许批量修改字段
	public $batchEditFields = Array(
		'user_attach'	 ,
		'status'		 ,
		'report'         ,
		'preset_time'	 ,
		'deleted'  		 ,
		'distribute_date',
		'distribute_user',
		'distribute_option',
		'last_user_attach',
		);
	//允许miss编辑字段
	public $missEditFields = Array();
	public $searchFields = Array(
			'user_attach'			=> Array('user_attach','='),
			'user_create'			=> Array('user_create','='),
			'date_create'			=> Array('date_create','>='),
			'date_create_end'		=> Array('date_create','<='),
			'area'					=> Array('area','='),
			'park'					=> Array('park','='),
			'batch'					=> Array('batch','='),
			'type'					=> Array('type','='),
			'register_date'			=> Array('register_date','>='),
			'register_date_end'		=> Array('register_date','<='),
			'register_month'		=> Array('DATE_FORMAT(register_date,"%m")','='),
			'status'				=> Array('status','='),
			'report'				=> Array('report','='),
	);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = array();
	//默认排序
	public $defaultOrder = array();//'id','ASC'
	//详情入口字段
	public $enteryField = '';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = array();
	//枚举字段值
	public $picklist = array(
		'handout_way'    => array('HANDOUT_BY_SIT','HANDOUT_BY_GROUP'),
		'batch'          => array('','A','B','C','D','E','F','G','H'),
		'area'           => array('','CHENGDU','ZONE','PERIPHERY'),
		'park'           => array('picklist_define'=>array('area','park','park','area')),
		'type'           => array('','FIRST_YEAR','RENEWAL','ORPHAN','LOAN_RENEWAL'),
		'status'         => array('','FIRST_DIAL','FAILED','INVALID','APPOINTMENT_QUOTATION','APPOINTMENT_NON_QUOTATION','SUCCESS'),
		'report'		 => array('picklist_define'=>array('status','result_report','report','status')),
		'register_month' => array('','01','02','03','04','05','06','07','08','09','10','11','12'),
	);

	//字段关联
	public $associateTo = array(
		'user_create'   => array('MODULE','User','detailView','id','user_name'),
		'user_attach'   => array('MODULE','User','detailView','id','user_name'),
		'group_attach'  => array('MODULE','GroupManager','detailView','id','name'),
		'handout_group' => array('MODULE','GroupManager','detailView','id','name'),
	);
	//模块关联
	public $associateBy = array();
	//记录权限关联字段名
	public $shareField = 'user_attach';

	//防止PHP notice
	function _get($str){
		$val = isset($_REQUEST[$str]) ? $_REQUEST[$str] : NULL;
		return $val;
	}
	/**
	 * [initWhere 初始化搜索条件为系统格式]
	 * @return [Array] [搜索条件]
	 */
	public function initWhere(){
		$where = Array();
		foreach ($_REQUEST as $key => $val) {
			if(array_key_exists($key, $this->searchFields) && isset($_REQUEST[$key]) && $_REQUEST[$key] != ''){
				$where[] = Array("{$this->searchFields[$key][0]}","{$this->searchFields[$key][1]}",$val,"and","");
			}
		}
		$where[sizeof($where)] = Array("deleted","=",0,"","");
		//if(!empty($where)) $where[sizeof($where) - 1][3] = "";
		return $where;
	}

	//根据条件返回列表记录总数
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids){
		return parent::getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
	}

	//批量修改
	function batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount=true,$datas=Array()){
		return parent::batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount,$datas);
	}

	//设置操作项
	private function setDistributeOption($option,$data = Array()){
		global $CURRENT_USER_ID;
		$affixData = Array();
		if($option == "DELETE" || $option == "BATCH_DELETE" || $option == "HANDOUT_DELETE"){
			$affixData['deleted'] = 1;
		}elseif ($option == "RECYCLE" || $option == "BATCH_RECYCLE") {
			$affixData['deleted'] = 0;
		}else{
			if(!array_key_exists('user_attach',$data)) return $data;
			else $affixData['last_user_attach']  = "user_attach";
		}
		$affixData['distribute_date']   = Date('Y-m-d H:i:s');
		$affixData['distribute_user']   = $CURRENT_USER_ID;
		$affixData['distribute_option'] = strtoupper($option);
		return array_merge($affixData,$data);
	}

	/**
	 * [getWorkGroup 获取工作组]
	 * @return [array/Bool]          [成功返回工作组,失败返回false]
	 */
	function getWorkGroup(){
		global $CURRENT_IS_ADMIN;
		$module = "GroupManager";
		if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
	    {
	        require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
			$modclass    = "{$module}Module";
			$mod         = new $modclass();
			$queryWhere  = Array();
			$filterWhere = Array();
			$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
			$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
			$count       = $mod->getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
    		if(!$count) return false;
	        return $mod->getListQueryRecord($queryWhere,$filterWhere,'','',$userids,$groupids,0,$count);
	    }
	    return false;
	}

	/**
	 * [getGroupUser 根据工作组id获取该工作组下的座席名单]
	 * @param  [integer]		$groupid 	[工作组id]
	 * @return [array/Bool]					[成功返回座席名单,失败返回false]
	 */
	function getGroupUser($groupid){
		global $CURRENT_IS_ADMIN;
		$module = "User";
		if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
	    {
	        require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
			$modclass    = "{$module}Module";
			$mod         = new $modclass();
			$queryWhere  = Array(Array("groupid","=",$groupid,"",""));
			$filterWhere = Array();
			$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
			$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
			$count       = $mod->getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
    		if(!$count) return false;
	        return $mod->getListQueryRecord($queryWhere,$filterWhere,'','',$userids,$groupids,0,$count);
	    }
	    return false;
	}

	/**
	 * [initList 取出指定字段]
	 * @param  [array]  $idList [数据]
	 * @param  [string] $field  [取出字段]
	 * @return [array]          [格式化后id数组]
	 */
	function initList($idList,$field = "id"){
		$result = array();
		if(!empty($idList)){
			foreach($idList as $val){
				$result[] = $val[$field];
			}
		}
		return $result;
	}

	/**
	 * [getDistributeId 根据条件返回待分发客户数据]
	 * @return [array/bool] [成功返回客户数据,失败返回false]
	 */
	function getDistributeId(){
		global $CURRENT_IS_ADMIN;
		$queryWhere  = $this->initWhere();
		$filterWhere = Array();
		$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
		$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
		$count       = $this->getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
		if(!$count) return false;
		return parent::getListQueryRecord($queryWhere,$filterWhere,'','',$userids,$groupids,0,$count);
	}
	/**
	 * [generateRandomNumber 产生从1到指定最高数的随机数]
	 * @param  [int] 	$high [最高数]
	 * @return [array]        [随机数组]
	 */
	function generateRandomNumber($high){
		$list = range(1, $high);
		return $this->reorder($list);
	}
	/**
	 * [sliceList 获取从0开始指定长度的数组]
	 * @param  [array] $list 	[数据]
	 * @param  [int] 	$len  	[长度]
	 * @return [array]       	[分割后的数据]
	 */
	function sliceList($list,$len){
		return array_slice($list, 0, $len);
	}

	/**
	 * [setDistributeId2Temporary 根据条件返回待分发客户数据]
	 * @return [int] [影响行数]
	 */
	function setDistributeId2Temporary(){
		global $CURRENT_IS_ADMIN,$APP_ADODB;
		$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
		$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
		$queryWhere  = $this->initWhere();
		$where 		 = formatSqlWhere($queryWhere,$this->fields);
		$swhere 	 = $this->_getSqlShareWhereString($userids,$groupids);
		if($where != '' && $swhere != '') $where = " ({$where}) AND ({$swhere}) ";
		elseif($swhere != '') $where = " ({$swhere}) ";
		if($where != '') $where = " where ({$where})";
		$sql = "CREATE TEMPORARY TABLE cosmo(index_id INT PRIMARY KEY AUTO_INCREMENT,id INT) AS SELECT id FROM {$this->baseTable} {$where}";
		$APP_ADODB->Execute($sql);
		return $APP_ADODB->affected_rows();
	}
	/**
	 * [getDistributeIdFromTemporary 获取待分发客户数据id]
	 * @param  [string] 	$idString [逗号分隔的id]
	 * @return [bool/array]           [成功返回客户数据id,失败返回false]
	 */
	function getDistributeIdFromTemporary($idString){
		global $APP_ADODB;
		$sql = "SELECT id FROM cosmo WHERE index_id IN ({$idString})";
		$result = $APP_ADODB->Execute($sql);
		return $result ? $result->getarray() : false;
	}

	/**
	 * [array2String 数组2字符串]
	 * @param  [array] $data [一维数组数据]
	 * @return [string]      [逗号分隔的字符串数据]
	 */
	function array2String($data,$isStr = FALSE){
		return $isStr ? "'".implode("','",$data)."'" : implode(",",$data);
	}
	/**
	 * [string2array 字符串2数组]
	 * @param  [string] $data [逗号分隔的字符串数据]
	 * @return [array]        [一维数组]
	 */
	function string2array($data){
		return explode(",",$data);
	}

	/**
	 * [hdoutData 分发]
	 * @param  [array] $accarray [要分发的客户id]
	 * @param  [array] $sitarray [坐席id]
	 * @param  [int]   $length   [分配数量]
	 * @return [array]           [执行结果]
	 */
	function hdoutData($accarray,$sitarray,$length){
		$idMap  = $this->initHandout($accarray,$sitarray,$length);
		$result = $this->modifyAttach($idMap);
		return $result;
	}

	/**
	 * [delData 删除客户数据]
	 * @param  [array] $accarray [要删除的客户id]
	 * @return [array]           [执行结果]
	 */
	function delData($accarray){
		global $CURRENT_IS_ADMIN;
		if(empty($accarray)) return array('BASE' => 0);
		$accString   = $this->array2String($accarray);
		$modFields   = $this->batchEditFields;
		$queryWhere  = Array(Array("id","in","($accString)","",""));
		$filterWhere = Array();
		$orderby     = '';
		$order       = 'NONE';
		$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
		$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
		$limit       = 0;
		$iscount     = false;
		$datas       = Array();
		$datas       = $this->setDistributeOption("HANDOUT_DELETE",$datas);
		$affect_num  = $this->batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount,$datas);
		return array('BASE'   	 => $affect_num + 0);
	}
	/**
	 * [delPolicy 删除算价记录]
	 * @param  [string] $idList [逗号分隔的客户id字符串]
	 * @return [int]            [影响行数]
	 */
	function delPolicy($vinlist){
		global $CURRENT_IS_ADMIN;
		$module = "PolicyCalculateCom";
		if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
	    {
	        require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
			$modclass    = "{$module}Module";
			$mod         = new $modclass();
			$mod->fields['vin_no'] = array('50','N',false,'车辆识别号码');
			$vinlist     = str_replace(",", "','", "'" . $vinlist . "'");
			$queryWhere  = Array(Array("vin_no","in","($vinlist)","",""));
			$filterWhere = Array();
			$orderby     = '';
			$order 		 = 'NONE';
			$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
			$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
			$limit		 = NULL;
			$deleteAssociateby = true;
			return $mod->batchDelete($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$deleteAssociateby,false);
	    }
	    return FALSE;
	}
	/**
	 * [delTrack 删除跟踪记录]
	 * @param  [string] $accString [逗号分隔的客户id字符串]
	 * @return [int]               [影响行数]
	 */
	function delTrack($accString){
		global $CURRENT_IS_ADMIN;
		$module = "AccountTrack";
		if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
	    {
	        require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
			$modclass    = "{$module}Module";
			$mod         = new $modclass();
			$queryWhere  = Array(Array("accountid","in","($accString)","",""));
			$filterWhere = Array();
			$orderby     = '';
			$order 		 = 'NONE';
			$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
			$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
			$limit		 = NULL;
			$deleteAssociateby = true;
			return $mod->batchDelete($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$deleteAssociateby,false);
	    }
	    return FALSE;
	}

	/**
	 * [transferData 数据转移]
	 * @param  [array] $accarray [要转移的客户id]
	 * @param  [array] $sitarray [坐席id]
	 * @param  [array] $vinarray [要转移的客户vin]
	 * @param  [int]   $num      [转移数量]
	 * @return [array]           [执行结果]
	 */
	function transferData($accarray,$sitarray,$num) {
		global $CURRENT_USER_ID;
		//转移操作不清除算价记录、跟踪记录，名单状态、预约时间不改变，只修改归属于为指定分发人员(分发人员为空将改为当前操作用户)
		$accarray  = array_slice($accarray, 0, $num);
		$idMap     = empty($sitarray) ? array($CURRENT_USER_ID => $accarray) : $this->initHandout($accarray,$sitarray,$num);
		$accString = $this->array2String($accarray);
		$acc       = $this->modifyAttach($idMap, "HANDOUT_TRANSFER", FALSE);
		return $acc;
	}

	/**
	 * [recycleData 数据回收]
	 * @param  [array] $accarray [要回收的客户id]
	 * @param  [array] $sitarray [坐席id]
	 * @param  [array] $vinarray [要回收的客户vin]
	 * @param  [int]   $num      [回收数量]
	 * @return [array]           [执行结果]
	 */
	function recycleData($accarray,$sitarray,$num){
		global $CURRENT_USER_ID;
		//回收操作清除算价记录、跟踪记录，将名单改为首拨，预约时间清零，归属于改为指定分发人员(分发人员为空将改为当前操作用户)
		$track         = 0;
		$policy        = 0;
		$accarray      = array_slice($accarray, 0, $num);
		//$vinarray      = array_slice($vinarray, 0, $num);
		$idMap         = empty($sitarray) ? array($CURRENT_USER_ID => $accarray) : $this->initHandout($accarray,$sitarray,$num);
		$accString     = $this->array2String($accarray);
		//$vinString     = $this->array2String($vinarray);
		if(!empty($accarray)) $track         = $this->delTrack($accString) + 0;
		//if(!empty($vinarray)) $policy        = $this->delPolicy($vinString) + 0;
		$acc           = $this->modifyAttach($idMap,"HANDOUT_RECYCLE");
		$acc['TRACK']  = $track;
		$acc['POLICY'] = $policy;
		return $acc;
		/*
		//分发人员不为空 清除算价记录、跟踪记录，并将名单改为首拨，预约时间清零，归属于修改为分发人员。
		//分发人员为空 不清除算价记录、跟踪记录，直接将名单改为首拨，预约时间清零，归属于改为当前操作用户
		if(!empty($sitarray)){
			return $this->hdoutData($accarray,$sitarray,$num);
		}else{
			$track         = 0;
			$policy        = 0;
			$accarray      = array_slice($accarray, 0, $num);
			$vinarray      = array_slice($vinarray, 0, $num);
			$idMap         = array($CURRENT_USER_ID => $accarray);
			$accString     = $this->array2String($accarray);
			$vinString     = $this->array2String($vinarray);
			if(!empty($accarray)) $track         = $this->delTrack($accString) + 0;
			if(!empty($vinarray)) $policy        = $this->delPolicy($vinString) + 0;
			$acc           = $this->modifyAttach($idMap,"HANDOUT_RECYCLE");
			$acc['TRACK']  = $track;
			$acc['POLICY'] = $policy;
			return $acc;
		}
		*/
	}

	/**
	 * [reorder 对array进行随机排序]
	 * @param  [array] $accarray [客户id]
	 * @return [array]          [随机排序]
	 */
	function reorder($accarray){
		shuffle($accarray);
        return $accarray;
	}

	/**
	 * [initHandout 初始化分发数组]
	 * @param  [array]  $accarray   [客户id]
	 * @param  [array]  $sitarray  [座席id]
	 * @param  [Int]  	$length    [座席分得客户条数]
	 * @param  integer 	$index     [初始位置]
	 * @return [array]             [座席对应客户id]
	 */
	function initHandout($accarray,$sitarray,$length,$index = 0){
		$result = array();
		if(empty($length))
			$length = 0;
        $accarray = $this->reorder($accarray);//对accountid进行随机排序
        foreach ($sitarray as $man) {
            $result[$man] = array_slice($accarray,$index,$length);
            $index = $index + $length;
        }
        return $result;
	}

	/**
	 * [modifyAttach 批量更新客户数据]
	 * @param  [array] $idMap [sit=>Array(accountid)]
	 * @return [array]        [执行结果]
	 */
	function modifyAttach($idMap, $option = "HANDOUT", $isInit = TRUE) {
		global $CURRENT_IS_ADMIN;
		$result      = Array("BASE" => 0, "SKIP" => 0, "SKIPSIT" => 0, "TRACK" => 0, "POLICY" => 0);
		$queryWhere  = Array();
		$filterWhere = Array();
		$datas       = Array();
		$orderby     = '';
		$order       = 'NONE';
		$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
		$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
		$limit       = 0;
		$iscount     = false;
		if($isInit) $datas       = Array("status" => "FIRST_DIAL","report" => NULL,"preset_time" => "0000-00-00 00:00:00");
		foreach ($idMap as $key => $val) {
			if($val){
				$datas["user_attach"] = $key;
				$datas                = $this->setDistributeOption($option,$datas);
				$list                 = $this->array2String($val);
				$modFields            = $this->batchEditFields;
				$queryWhere           = Array(Array("id","in","($list)","",""));
				$affect_num           = $this->batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount,$datas);
				$result['SKIP']       += sizeof($val) - $affect_num;
				$result['BASE']       += $affect_num;
			}else{
				$result['SKIPSIT']++;
			}
		}
		return $result;
	}

	/**
	 * [run 运行分发]
	 * @param  [String] $option [操作]
	 * @return [Array]       	[执行结果]
	 */
	function run($option){
		$sitarray = array();
		//用户填写的分配数量
		$customer_num   = $_REQUEST['handout_num'] ? $_REQUEST['handout_num'] : 0;
		//设置临时表并获取影响行数
		$row_num        = $this->setDistributeId2Temporary();
		//设置分配总数默认值为临时表中所有行数
		$distribute_num = $row_num;
		//$data         = $this->getDistributeId();
		if($_REQUEST['handout_way'] == 'HANDOUT_BY_SIT'){
			if($_REQUEST['handout_sit']){
				$sitarray       = $this->string2array($_REQUEST['handout_sit']);
				$distribute_num = $customer_num * sizeof($sitarray);
			}
		}else{
			if($_REQUEST['handout_group']){
				$sitarray       = $this->initList($this->getGroupUser($_REQUEST['handout_group']));
				$distribute_num = $customer_num;
			}
		}
		//产生指定数量的随机数
        $list     = $this->generateRandomNumber($row_num);
        //取出临时表中指定数量的数据
        $data     = $this->getDistributeIdFromTemporary($this->array2String($this->sliceList($list,$distribute_num)));
        $accarray = $this->initList($data);

		if($option == 'handout'){
			return $this->hdoutData($accarray,$sitarray,$customer_num);
		}elseif($option == 'recycle') {
			//$vinarray 	= $this->initList($data,"vin");
			return $this->recycleData($accarray,$sitarray,$customer_num);
		}elseif ($option == 'transfer') {
			return $this->transferData($accarray,$sitarray,$customer_num);
		}elseif ($option == 'delete') {
			return $this->delData($accarray);
		}
		return false;
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
	/**
	 * [getSource 获取用户对应名单数量]
	 * @return [array] [用户对应名单数量]
	 */
	function getSource(){
		global $APP_ADODB,$CURRENT_IS_ADMIN;
		$userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
		$groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
		$queryWhere  = $this->initWhere();
		$where 		 = formatSqlWhere($queryWhere,$this->fields);
		$swhere 	 = $this->_getSqlShareWhereString($userids,$groupids);
		if($where != '' && $swhere != '') $where = " ({$where}) AND ({$swhere}) ";
		elseif($swhere != '') $where = " ({$swhere}) ";
		if($where != '') $where = " where ({$where})";
		$sql 		 = "SELECT user_attach,count(*) owner_total FROM {$this->baseTable} {$where} GROUP BY user_attach";
		$result 	 = $APP_ADODB->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}

};



?>