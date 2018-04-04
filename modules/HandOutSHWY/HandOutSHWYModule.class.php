<?php
class HandOutSHWYModule extends BaseModule
{
	public $baseTable = 'accounts_shwy';
	//关联表
	public $associateTable = Array(
		"TRACK"  => "account_track",
		"POLICY" => "policy_draft_com",
	);
	//模块描述
	public $describe = '分发管理(上海唯佑专用)';
	//需要控制访问权限的模块方法
    public $actions = array(
			'index'        => '筛选',
			'step2'		   => '操作',
			'step3'		   => '完成',
			'handout'	   => '分发',
			'recycle'      => '回收',
			'delete'	   => '删除',
			);
	//字段定义
	//'字段名'=>array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = array(

			'team'                => array('5','S',false,'品牌团队'),
			'company'             => array('27','S',false,'保险公司'),
			'area'                => array('20','E',false,'片区'),
			'park'                => array('26','S',false,'园区'),
			'batch'               => array('20','E',false,'批次'),
			'type'                => array('20','E',false,'名单类型'),
			'expiration_date'     => array('31','DT',false,'保险到期开始日期'),
			'expiration_date_end' => array('31','DT',false,'保险到期结束日期'),
			'register_date'       => array('30','D',false,'注册开始日期'),
			'register_date_end'   => array('30','D',false,'注册结束日期'),
			'register_month'      => array('20','E',false,'注册月份'),
			'model'            	  => array('5','S',false,'品牌型号'),
			'status'              => array('20','E',false,'状态'),
			'report'           	  => array('26','S',false,'销售说明'),
			'date_create'         => array('30','D',false,'记录创建的时间'),
			'user_create'         => array('51','N',false,'创建记录的操作员'),
			'user_attach'         => array('51','N',false,'记录归属于用户'),
			'group_attach'        => array('51','N',false,'记录归属于组'),

			'handout_wait'        => array('17','S',false,'待分发名单数',0,0,999999),
			'handout_num'         => array('6','S',true,'分配数量',0,0,999999),
			'handout_way'         => array('21','E',false,'分发方式','HANDOUT_BY_SIT'),
			'handout_sit'         => array('17','S',false,'分发人员'),
			'handout_group'       => array('50','N',false,'按组分发'),
			);
	//安全字段,可以控制权限
	public $safeFields = array(
			'team'			 ,
			'company'	     ,
			'area'			 ,
			'park'			 ,
			'batch'			 ,
			'type'			 ,
			'expiration_date',
			'register_date'  ,
			'register_month' ,
			'date_create'	 ,
			'status'		 ,
			'report'         ,
			'user_create'	 ,
			);
	//列表字段
	public $listFields = array(
			'team'			 ,
			'company'	     ,
			'area'			 ,
			'park'			 ,
			'batch'			 ,
			'type'			 ,
			'expiration_date',
			'register_date'  ,
			'register_month' ,
			'date_create'	 ,
			'status'		 ,
			'report'         ,
			'user_create'	 ,
			'user_attach'	 ,
			'group_attach'	 ,
			);
	//编辑字段
	public $editFields = array(
			'user_create'	 ,
			'user_attach'	 ,
			'group_attach'	 ,
			'date_create'	 ,
			'team'			 ,
			'company'	     ,
			'area'			 ,
			'park'			 ,
			'batch'			 ,
			'type'			 ,
			'expiration_date',
			'register_date'  ,
			'register_month' ,
			'status'		 ,
			'report'         ,
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
	public $shareField = '';

	/**
	 * [getRequest 根据条件返回对应条件sql]
	 * @return [String] [sql where条件语句]
	 */
	function getRequest(){
		$sql = "";
		if(isset($_REQUEST['group_attach']) && $_REQUEST['group_attach'] != '')
			$sql = "AND {$this->baseTable}.user_attach = {$_REQUEST['group_attach']} ";
		if(isset($_REQUEST['user_attach']) && $_REQUEST['user_attach'] != '')
			$sql = "AND {$this->baseTable}.user_attach = {$_REQUEST['user_attach']} ";
		if(isset($_REQUEST['user_create']) && $_REQUEST['user_create'] != '')
			$sql .= "AND {$this->baseTable}.user_create = {$_REQUEST['user_create']} ";
		if(isset($_REQUEST['date_create']) && $_REQUEST['date_create'] != '')
			$sql .= "AND DATE_FORMAT({$this->baseTable}.date_create,'%Y-%m-%d') = '{$_REQUEST['date_create']}' ";
		if(isset($_REQUEST['area']) && $_REQUEST['area'] != "")
			$sql .= "AND {$this->baseTable}.area = '{$_REQUEST['area']}'";
		if(isset($_REQUEST['park']) && $_REQUEST['park'] != "")
			$sql .= "AND {$this->baseTable}.park = '{$_REQUEST['park']}' ";
		if(isset($_REQUEST['team']) && $_REQUEST['team'] != "")
			$sql .= "AND {$this->baseTable}.team = '{$_REQUEST['team']}' ";
		if(isset($_REQUEST['company']) && $_REQUEST['company'] != '')
			$sql .= "AND {$this->baseTable}.company = '{$_REQUEST['company']}' ";
		if(isset($_REQUEST['batch']) && $_REQUEST['batch'] != "")
			$sql .= "AND {$this->baseTable}.batch = '{$_REQUEST['batch']}' ";
		if(isset($_REQUEST['type']) && $_REQUEST['type'] != "")
			$sql .= "AND {$this->baseTable}.type = '{$_REQUEST['type']}' ";
		if(isset($_REQUEST['expiration_date']) && $_REQUEST['expiration_date'] != '')
			$sql .= "AND {$this->baseTable}.expiration_date >= '{$_REQUEST['expiration_date']}' ";
		if(isset($_REQUEST['expiration_date_end']) && $_REQUEST['expiration_date_end'] != '')
			$sql .= "AND {$this->baseTable}.expiration_date <= '{$_REQUEST['expiration_date_end']}' ";
		if(isset($_REQUEST['register_date']) && $_REQUEST['register_date'] != '')
			$sql .= "AND {$this->baseTable}.register_date >= '{$_REQUEST['register_date']}' ";
		if(isset($_REQUEST['register_date_end']) && $_REQUEST['register_date_end'] != '')
			$sql .= "AND {$this->baseTable}.register_date <= '{$_REQUEST['register_date_end']}' ";
		if(isset($_REQUEST['register_month']) && $_REQUEST['register_month'] != '')
			$sql .= "AND DATE_FORMAT({$this->baseTable}.register_date,'%m') = '{$_REQUEST['register_month']}' ";
		if(isset($_REQUEST['model']) && $_REQUEST['model'] != ''){
			$sql .= "AND {$this->baseTable}.model LIKE '%{$_REQUEST['model']}%' ";
		}
		if(isset($_REQUEST['status']) && $_REQUEST['status'] != "")
			$sql .= "AND {$this->baseTable}.status = '{$_REQUEST['status']}' ";
		if(isset($_REQUEST['report']) && $_REQUEST['report'] != "")
			$sql .= "AND {$this->baseTable}.report = '{$_REQUEST['report']}' ";
		return $sql;
	}
	/**
	 * [getSource 获取用户对应名单数量]
	 * @return [array] [用户对应名单数量]
	 */
	function getSource(){
		global $APP_ADODB;
		$sql = "SELECT us.user_name,us.name,count(*) sum FROM {$this->baseTable} LEFT JOIN users us ON {$this->baseTable}.user_attach = us.id WHERE 1=1 ";
		$sql .= $this->getRequest();
		$sql .= $this->getUserList();
		$sql .= " GROUP BY us.id";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}
	/**
	 * [getUserList 获取非admin且有工作组用户组内坐席ID]
	 * @return [String]
	 */
	function getUserList(){
		global $CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
		$userList = "";
		if(!$CURRENT_IS_ADMIN && $CURRENT_USER_GROUPID != -1){
			$groupUser = $this->getGroupUser($CURRENT_USER_GROUPID);
			foreach($groupUser as $val){
				$userList .= $val['id'] . ",";
			}
			$userList = " AND {$this->baseTable}.user_attach in (" . substr($userList, 0,-1) . ")";
		}
		return $userList;
	}
	/**
	 * [getHandoutCount 根据条件返回客户数量]
	 * @return [Int/Bool] [成功返回客户数量,失败返回false]
	 */
	function getHandoutCount(){
		global $APP_ADODB;
		$sql = "SELECT COUNT(*) FROM {$this->baseTable} WHERE 1=1 ";
		$sql .= $this->getRequest();
		$sql .= $this->getUserList();
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->fields[0];
		return 0;
	}
	/**
	 * [getHandoutId 根据条件返回客户id]
	 * @return [array/Bool] [成功返回客户id,失败返回false]
	 */
	function getHandoutId(){
		global $APP_ADODB;
		$sql = "SELECT id FROM {$this->baseTable} WHERE 1=1 ";
		$sql .= $this->getRequest();
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}
	/**
	 * [initIDList 格式化id数字]
	 * @param  [array] $idList [id数组]
	 * @return [array]         [格式化后id数组]
	 */
	function initIDList($idList,$field = "id"){
		$result = array();
		foreach($idList as $val){
			$result[] = $val[$field];
		}
		return $result;
	}
	/**
	 * [getWorkGroup 获取工作组]
	 * @return [array/Bool] [成功返回工作组,失败返回false]
	 */
	function getWorkGroup(){
		global $APP_ADODB,$CURRENT_USER_GROUPID;
		$sql = "SELECT id,name FROM groups ";
		if($CURRENT_USER_GROUPID != -1)
			$sql .= "WHERE id = {$CURRENT_USER_GROUPID} ";
		$sql .= "ORDER BY id";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}
	/**
	 * [getFirGroup 按id排序获取第一个工作组]
	 * @return [Int/Bool] [成功返回第一个工作组id,失败返回false]
	 */
	function getFirGroup(){
		global $APP_ADODB,$CURRENT_USER_GROUPID;
		$sql = "SELECT id FROM groups ";
		if($CURRENT_USER_GROUPID != "-1")
			$sql .= "WHERE id = {$CURRENT_USER_GROUPID} ";
		$sql .= "ORDER BY id LIMIT 0,1";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->fields[0];
		return false;
	}
	/**
	 * [getGroupUser 根据工作组id获取该工作组下的座席名单]
	 * @param  integer $groupid [工作组id]
	 * @return [array/Bool]           [成功返回座席名单,失败返回false]
	 */
	function getGroupUser($groupid = 0){
		global $APP_ADODB,$CURRENT_USER_ID;
		if(empty($groupid) || $groupid == 0)
			$groupid = $this->getFirGroup();
		$sql = "SELECT id,user_name,name FROM users WHERE groupid = {$groupid} ";
		//$sql .= "AND id != {$CURRENT_USER_ID}";//排除分发人员
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getarray();
		return false;
	}
	/**
	 * [getVIN 获取id对应的车架号]
	 * @param  [string] $idList [id列表逗号分隔]
	 * @return [array]          [车架号]
	 */
	function getVIN($idList){
		global $APP_ADODB;
		$sql = "SELECT vin FROM {$this->baseTable} WHERE id IN ({$idList})";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $this->initIDList($result->getarray(),"vin");
		return false;
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
            $index = $index+$length;
        }
        return $result;
	}
	/**
	 * [modifyAttach 批量更新客户数据]
	 * @param  [array] $idMap [sit=>Array(accountid)]
	 * @return [array]        [执行结果]
	 */
	function modifyAttach($idMap){
		global $APP_ADODB,$CURRENT_USER_ID;
		$result = Array("BASE" => 0,"SKIP" => 0,"SKIPSIT" => 0,"TRACK" => 0,"POLICY" => 0);
		$date_modify = Date('Y-m-d H:i:s');
		foreach ($idMap as $key => $val) {
			if($val){
				$sql = "UPDATE {$this->baseTable} SET status = 'FIRST_DIAL',report = NULL,preset_time = '0000-00-00 00:00:00',date_modify = '{$date_modify}',user_modify = {$CURRENT_USER_ID},user_attach = {$key} WHERE id IN({$this->array2String($val)})";
				$APP_ADODB->Execute($sql);
				$affect_num = $APP_ADODB->Affected_Rows();
				$result['SKIP'] += sizeof($val) - $affect_num;
				$result['BASE'] += $affect_num;
			}else{
				$result['SKIPSIT']++;
			}
		}
		return $result;
	}
	/**
	 * [delPolicy 删除算价记录]
	 * @param  [string] $idList [逗号分隔的客户id字符串]
	 * @return [int]            [影响行数]
	 */
	function delPolicy($idList){
		global $APP_ADODB;
		$vinList = $this->array2String($this->getVIN($idList),TRUE);
		$sql = "DELETE FROM {$this->associateTable['POLICY']} WHERE vin_no IN ({$vinList})";
		$APP_ADODB->Execute($sql);
		return $APP_ADODB->Affected_Rows();
	}
	/**
	 * [delTrack 删除跟踪记录]
	 * @param  [string] $accString [逗号分隔的客户id字符串]
	 * @return [int]               [影响行数]
	 */
	function delTrack($accString){
		global $APP_ADODB;
		$sql = "DELETE FROM {$this->associateTable['TRACK']} WHERE accountid IN ({$accString})";
		$APP_ADODB->Execute($sql);
		return $APP_ADODB->Affected_Rows();
	}
	/**
	 * [delData 删除客户资料]
	 * @param  [string] $accString [逗号分隔的客户id字符串]
	 * @return [int]               [影响行数]
	 */
	function delBaseData($accString){
		global $APP_ADODB;
		$sql = "DELETE FROM {$this->baseTable} WHERE id IN ({$accString})";
		$APP_ADODB->Execute($sql);
		return $APP_ADODB->Affected_Rows();
	}
	/**
	 * [delData 删除客户数据]
	 * @param  [array] $accarray [要删除的客户id]
	 * @return [array]           [执行结果]
	 */
	function delData($accarray){
		$accString = $this->array2String($accarray);
		return array(
				'TRACK'		 => $this->delTrack($accString) + 0,
				'POLICY'	 => $this->delPolicy($accString) + 0,
				'BASE'   	 => $this->delBaseData($accString) + 0,
				);
	}
	/**
	 * [hdoutData 分发]
	 * @param  [array] $accarray [要分发的客户id]
	 * @param  [array] $sitarray [坐席id]
	 * @param  [int]   $length   [分配数量]
	 * @return [array]           [执行结果]
	 */
	function hdoutData($accarray,$sitarray,$length){
		$idMap = $this->initHandout($accarray,$sitarray,$length);
		$result   = $this->modifyAttach($idMap);
		return $result;
	}
	/**
	 * [recycle 回收]
	 * @param  [array] $accarray [要回收的客户id]
	 * @param  [int]   $num      [回收数量]
	 * @param  [array] $sitarray [坐席id]
	 * @return [array]           [执行结果]
	 */
	function recycle($accarray,$sitarray,$num){
		global $CURRENT_USER_ID;
		if(!empty($sitarray)){
			return $this->hdoutData($accarray,$sitarray,$num);
		}else{
			$accarray = array_slice($accarray, 0, $num);
			$idMap = array($CURRENT_USER_ID => $accarray);
			$accString = $this->array2String($accarray);
			$track  = $this->delTrack($accString) + 0;
			$policy = $this->delPolicy($accString) + 0;
			$acc = $this->modifyAttach($idMap);
			$acc['TRACK'] = $track;
			$acc['POLICY'] = $policy;
			return $acc;
		}
	}
	function run($mode){
		$sitarray = array();
		$accarray = $this->initIDList($this->getHandoutId());
		if($_REQUEST['handout_way'] == 'HANDOUT_BY_SIT'){
			if($_REQUEST['handout_sit']){
				$sitarray = $this->string2array($_REQUEST['handout_sit']);
			}
		}else{
			if($_REQUEST['handout_group']){
				$sitarray = $this->initIDList($this->getGroupUser($_REQUEST['handout_group']));
			}
		}
		if($mode == 'handout'){
			return $this->hdoutData($accarray,$sitarray,$_REQUEST['handout_num']);
		}elseif($mode == 'recycle'){
			return $this->recycle($accarray,$sitarray,$_REQUEST['handout_num']);
		}elseif ($mode == 'delete') {
			return $this->delData($accarray);
		}
		return false;
	}
	//防止PHP notice
	function _get($str){
		$val = isset($_REQUEST[$str]) ? $_REQUEST[$str] : NULL;
		return $val;
	}

};



?>