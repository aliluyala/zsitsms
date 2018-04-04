<?php
class AccountsModule extends BaseModule
{
	public $baseTable = 'accounts';
	//模块描述
	public $describe = '客户资料管理';
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
			'batchDelete'  => '批量删除',
			'batchModify'  => '批量修改',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'owner'            => Array('5','S',true,'客户名称'),
			'contact'          => Array('5','S',false,'联系人'),
			'telphone'         => Array('60','S',false,'固定电话(5-20位数字)','',5,20),
			'mobile'           => Array('60','S',false,'电话号码(5-20位数字)','',5,20),
			'address'          => Array('5','S',false,'客户的详细地址'),
			'id_code'          => Array('5','S',false,'身份证/机构代码'),
			'lending_bank'     => Array('5','S',false,'贷款银行'),
			'intention'        => Array('20','E',true,'意向程度'),


			'team'             => Array('5','S',false,'品牌团队'),
			'area'             => Array('20','E',false,'片区'),
			'park'             => Array('26','S',false,'园区'),
			'company'          => Array('27','S',false,'保险公司',''),
			'batch'            => Array('20','E',false,'批次'),
			'type'             => Array('20','E',false,'名单类型'),

			'last_policy'      => Array('5','S',false,'去年保单号码'),
			'purchase_price'   => Array('3','S',false,'新车购置价(元)',0,0,999999999),
			'plate_no'         => Array('5','S',false,'车牌号'),
			'vehicle_type'     => Array('20','E',false,'车辆种类'),
			'use_character'    => Array('20','E',false,'使用性质'),
			'model'            => Array('5','S',false,'品牌型号'),
			'vin'              => Array('5','S',true,'车架号/车辆识别代码'),
			'engine_no'        => Array('5','S',false,'发动机号'),
			'register_date'    => Array('30','D',false,'注册日期'),
			'register_address' => Array('5','S',false,'注册地'),
			'seats'            => Array('3','S',false,'核定载人数',0,0,999999999),
			'kerb_mass'        => Array('3','S',false,'整备质量(KG)',0,0,999999999),
			'total_mass'       => Array('3','S',false,'总质量(KG)',0,0,999999999),
			'ratify_load'      => Array('3','S',false,'核定载量(KG)',0,0,999999999),
			'tow_mass'         => Array('3','S',false,'准牵总质量',0,0,999999999),
			'engine'           => Array('3','S',false,'发动机排气量(ML)',0,0,999999999),
			'power'            => Array('3','S',false,'功率(KW)',0,0,999999999),
			'body_size'        => Array('5','S',false,'车身尺寸'),
			'body_color'       => Array('5','S',false,'车身颜色'),
			'origin'           => Array('20','E',false,'产地'),
			'status'           => Array('20','E',true,'状态'),
			'report'           => Array('26','S',false,'销售说明'),
			'remark'           => Array('9','S',false,'备注'),

			'deleted'		   => Array('22','E',false,'是否删除',0),
			'distribute_date'  => Array('31','DT',false,'操作时间'),
			'distribute_user'  => Array('50','N',false,'操作员'),
			'distribute_option'=> Array('20','E',false,'操作'),
			'last_user_attach' => Array('55','N',false,'上次归属于'),

			'user_attach'      => Array('55','N',true,'记录归属于组或用户，将决定其它用户访问此记录的权限'),
			'user_create'      => Array('51','N',true,'创建记录的操作员'),
			'user_modify'      => Array('52','N',false,'最后一次修改记录的操作员'),
			'date_create'      => Array('35','DT',true,'记录创建的时间'),
			'date_modify'      => Array('36','DT',false,'最后一次修改记录的时间'),
			'expiration_date'  => Array('31','DT',false,'保险到期日期',"0000-00-00 00:00:00"),
			'preset_time'      => Array('31','DT',false,'预约日期',"0000-00-00 00:00:00"),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'owner'           ,
			'contact'         ,
			'telphone'        ,
			'mobile'          ,
			'address'         ,
			'id_code'         ,
			'lending_bank'    ,
			'intention'       ,
			'team'            ,
			'area'            ,
			'park'            ,
			'company'         ,
			'batch'           ,
			'type'            ,
			'last_policy'     ,
			'purchase_price'  ,
			'plate_no'        ,
			'vehicle_type'    ,
			'use_character'   ,
			'model'           ,
			'vin'             ,
			'engine_no'       ,
			'register_date'   ,
			'register_address',
			'seats'           ,
			'kerb_mass'       ,
			'total_mass'      ,
			'ratify_load'     ,
			'tow_mass'        ,
			'engine'          ,
			'power'           ,
			'body_size'       ,
			'body_color'      ,
			'origin'          ,
			'status'          ,
			'report'          ,
			'user_attach'     ,
			'remark'   		  ,
			'user_create'     ,
			'user_modify'     ,
			'date_create'     ,
			'date_modify'     ,
			'expiration_date' ,
			'preset_time'     ,
			'deleted'		  ,
			'distribute_date' ,
			'distribute_user' ,
			'distribute_option',
			'last_user_attach',
			);
	//列表字段
	public $listFields = Array(
			'owner'				,
			'mobile'            ,
			'model'				,
			'plate_no'	        ,
			'register_date'	    ,
			'company'			,
			'expiration_date'	,
			'preset_time'	    ,
			'batch'	            ,
			'intention'         ,
			'type'              ,
			'team'              ,
			'status'            ,
			'report'            ,
			'remark'			,
			'user_attach'       ,
			);
	//编辑字段
	public $editFields = Array(
			'owner'           ,
			'contact'         ,
			'telphone'        ,
			'mobile'          ,
			'address'         ,
			'id_code'         ,
			'lending_bank'    ,
			'intention'       ,
			'team'            ,
			'area'            ,
			'park'            ,
			'company'         ,
			'batch'           ,
			'type'            ,
			'last_policy'     ,
			'purchase_price'  ,
			'plate_no'        ,
			'vehicle_type'    ,
			'use_character'   ,
			'model'           ,
			'vin'             ,
			'engine_no'       ,
			'register_date'   ,
			'register_address',
			'seats'           ,
			'kerb_mass'       ,
			'total_mass'      ,
			'ratify_load'     ,
			'tow_mass'        ,
			'engine'          ,
			'power'           ,
			'body_size'       ,
			'body_color'      ,
			'origin'          ,
			'status'          ,
			'report'          ,
			'user_attach'     ,
			'remark'   		  ,
			'expiration_date' ,
			'preset_time'     ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'owner'				,
			'plate_no'	        ,
			'model'				,
			'mobile'            ,
			'vin'               ,
			'register_date'	    ,
			'company'			,
			'team'              ,
			'expiration_date'	,
			'preset_time'	    ,
			'batch'	            ,
			'intention'         ,
			'type'              ,
			'status'            ,
			'report'            ,
			'user_attach'       ,
			'remark'   		    ,
			);

	//允许批量修改字段
	public $batchEditFields = Array(
			'lending_bank'    ,
			'batch'           ,
			'type'            ,
			'expiration_date' ,
			'preset_time'     ,
			'company'         ,
			'seats'           ,
			'engine'          ,
			'origin'          ,
			'purpose'         ,
			'status'          ,
			'report'          ,
			'user_attach'     ,
			'remark'   		  ,
			);
	//允许miss编辑字段
	public $missEditFields = Array(
			'owner'           ,
			'contact'         ,
			'telphone'        ,
			'mobile'          ,
			'address'         ,
			'id_code'         ,
			'lending_bank'    ,
			'intention'       ,
			'team'            ,
			'area'            ,
			'park'            ,
			'company'         ,
			'batch'           ,
			'type'            ,
			'last_policy'     ,
			'purchase_price'  ,
			'plate_no'        ,
			'vehicle_type'    ,
			'use_character'   ,
			'model'           ,
			'vin'             ,
			'engine_no'       ,
			'register_date'   ,
			'register_address',
			'seats'           ,
			'kerb_mass'       ,
			'total_mass'      ,
			'ratify_load'     ,
			'tow_mass'        ,
			'engine'          ,
			'power'           ,
			'body_size'       ,
			'body_color'      ,
			'origin'          ,
			'status'          ,
			'report'          ,
			'user_attach'     ,
			'remark'   		  ,
			'expiration_date' ,
			'preset_time'     ,
			);
	//默认排序
	public $defaultOrder = Array('owner','ASC');
	//详情入口字段
	public $enteryField = 'owner';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array('LBL_ACCOUNT_BASE'=>Array('4',true,Array(
									'owner'           ,
									'contact'         ,
									'telphone'        ,
									'mobile'          ,
									'address'         ,
									'id_code'         ,
									'lending_bank'    ,
									'intention'       ,
							)),
						   'LBL_ACCOUNT_INFO'=>Array('4',true,Array(
			   						'team'            ,
									'area'            ,
									'park'            ,
									'company'         ,
									'batch'           ,
									'type'            ,
									'expiration_date' ,
									'preset_time'	  ,
									'status'          ,
									'report'          ,
						   	)),
						   'LBL_ACCOUNT_CAR'=>Array('4',true,Array(
									'last_policy'     ,
									'purchase_price'  ,
									'plate_no'        ,
									'model'           ,
									'vin'             ,
									'engine_no'       ,
									'use_character'   ,
									'vehicle_type'    ,
									'register_date'   ,
									'register_address',
									'seats'           ,
									'kerb_mass'       ,
									'total_mass'      ,
									'ratify_load'     ,
									'tow_mass'        ,
									'engine'          ,
									'power'           ,
									'body_size'       ,
									'body_color'      ,
									'origin'          ,
							)),
						   'LBL_ACCOUNT_REMARK'=>Array('1',true,Array(
									'remark'   		  ,
						   	)),
						   'LBL_ACCOUNT_ADDR'=>Array('1',true,Array(
									'user_attach'     ,
							)),
						   'LBL_ACCOUNT_OPTION' => Array('3',true,Array(
									'last_user_attach',
									'deleted'		  ,
									'distribute_date' ,
									'distribute_user' ,
									'distribute_option',
									'date_create'     ,
									'user_create'     ,
						   	))
						   );
	//枚举字段值
	public $picklist = Array(
		'intention'			=> Array('HIGH','MIDDLE','LOW'),
		'use_character'		=> Array('','OPERATING','NON_OPERATING'),
		'origin'			=> Array('IMPORT','DOMESTIC'),
		'batch'				=> Array('A','B','C','D','E','F','G','H'),
		'area'				=> Array('','CHENGDU','ZONE','PERIPHERY'),
		'park'				=> Array('picklist_define'=>Array('area','park','park','area')),
		'type'				=> Array('FIRST_YEAR','RENEWAL','ORPHAN','LOAN_RENEWAL'),
		'status'			=> Array('FIRST_DIAL','FAILED','INVALID','APPOINTMENT_QUOTATION','APPOINTMENT_NON_QUOTATION','SUCCESS'),
		'report'			=> Array('picklist_define'=>array('status','result_report','report','status')),
		'vehicle_type'		=> Array('PRIVATE','ENTERPRISE','AUTHORITY','LEASE_RENTAL','CITY_BUS','HIGHWAY_BUS','TRUCK','TRAILER','SPECIAL_AUTO','MOTORCYCLE','DUAL_PURPOSE_TRACTOR','TRANSPORT_TRACTOR','LOW_SPEED_TRUCK'),

		'deleted'			=> Array('NO','YES'),
		'distribute_option' => Array('HANDOUT','HANDOUT_DELETE','HANDOUT_RECYCLE','HANDOUT_TRANSFER','BATCH_MODIFY','BATCH_DELETE','MODIFY','DELETE','RECYCLE','BATCH_RECYCLE'),
	);

	//字段关联
	public $associateTo = Array(
		'user_create'	=> array('MODULE','User','detailView','id','user_name'),
		'user_modify'	=> array('MODULE','User','detailView','id','user_name'),

		//'last_user_attach' => Array('MODULE','User','detailView','id','user_name'),
		'distribute_user'  => Array('MODULE','User','detailView','id','user_name'),
	);
	//模块关联
	public $associateBy = Array(
		'ASSOCIATE_TRACKRECORD_INFO'      => array('AccountTrack','accountid','status','report','remark','user_create','preset_time','date_create'),
		'ASSOCIATE_INSURANCE_CREATE_INFO' => array('PolicyCalculateCom','vin'),
	);
	//记录权限关联字段名
	public $shareField = 'user_attach';

	//防止PHP notice
	function _get($str){
		$val = isset($_REQUEST[$str]) ? $_REQUEST[$str] : NULL;
		return $val;
	}

	public function isRepeat($vin,$recordid){
		$status = "error";
		$repeat = "车架号重复!相同车架号客户为:";
		$attachModule = Array(Array("NAME" => "Accounts","LABEL" => "客户资料"),Array("NAME" => "Recycle","LABEL" => "回收站"));
		$result = $this->getAccountByVin($vin,$recordid);
		if($result){
			foreach ($result as $item) {
				$repeat .= "<a href='index.php?module=Index&action=index#index.php?module={$attachModule[$item["deleted"]]["NAME"]}&action=detailView&recordid={$item["id"]}&return_module={$attachModule[$item["deleted"]]["NAME"]}&return_action=index'>";
		    	$repeat .= "<strong style='color:#1E90FF;border:none;'>{$item['owner']}</strong>({$attachModule[$item["deleted"]]["LABEL"]})</a>";
			}
		}else{
			$status = "success";
			$repeat = "车架号可用。";
		}
		return_ajax($status, $repeat);
	}
	public function getUserAttach($name){
		global $APP_ADODB,$CURRENT_USER_ID;
		$sql = "SELECT id FROM users WHERE user_name = '$name' union SELECT id FROM groups WHERE name = '$name'";
		$result = $APP_ADODB->Execute($sql);
		return $result->RecordCount() ? $result->fields["id"] : $CURRENT_USER_ID;
	}
	public function getAccountByMobile($mobile,$recordid = 0){
		$queryWhere  = Array(Array("mobile","=",$mobile,"AND",""),Array("id","!=",$recordid,"",""));
		$filterWhere = Array();
		$orderby 	 = "";
		$order 		 = "";
		$userids     = NULL;
		$groupids    = NULL;
		$start 		 = 0;
		$maxRows     = 1;
		/*$maxRows     = parent::getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
		if(!$maxRows) return FALSE;*/
		return parent::getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows);
	}
	public function getAccountByVin($vin,$recordid = 0){
		$queryWhere  = Array(Array("vin","=",$vin,"AND",""),Array("id","!=",$recordid,"",""));
		$filterWhere = Array();
		$orderby 	 = "";
		$order 		 = "";
		$userids     = NULL;
		$groupids    = NULL;
		$start 		 = 0;
		$maxRows     = 1;
		/*$maxRows     = parent::getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
		if(!$maxRows) return FALSE;*/
		return parent::getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows);
	}
	public function getOneRecordsetForInsurance($id,$userids,$groupids){
		$result = parent::getOneRecordset($id,$userids,$groupids);
		if(!isset($result) || empty($result))
			return NULL;
    	return $result[0];
	}
	//去掉指定字段前后空格
	public function trimData($data){
		if(array_key_exists('vin',$data))
		{
			$data['vin'] = trim($data['vin']);
		}
		if(array_key_exists('plate_no',$data))
		{
			$data['plate_no'] = trim($data['plate_no']);
		}
		if(array_key_exists('engine_no',$data))
		{
			$data['engine_no'] = trim($data['engine_no']);
		}
		if(array_key_exists('register_date',$data))
		{
			$data['register_date'] = trim($data['register_date']);
		}
		if(array_key_exists('telphone',$data))
		{
			$data['telphone'] = trim($data['telphone']);
		}
		if(array_key_exists('mobile',$data))
		{
			$data['mobile'] = trim($data['mobile']);
		}
		if(array_key_exists('id_code',$data))
		{
			$data['id_code'] = trim($data['id_code']);
		}
		if(array_key_exists('model',$data))
		{
			$data['model'] = trim($data['model']);
		}
		return $data;
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
	//查询中添加未进入回收站条件
	private function setDeletedWhere($where){
		if(!empty($where)){
			$where[sizeof($where) - 1][3] = "and";
		}
		$where[] = Array("deleted","=",0,"","");
		return $where;
	}
	//根据条件返回列表记录总数
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids)
	{
		$queryWhere = $this->setDeletedWhere($queryWhere);
		return parent::getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids);
	}
	//根据条件返回列表显示的记录集
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows)
	{
		$queryWhere = $this->setDeletedWhere($queryWhere);
		return parent::getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows);
	}
	//返回上一条记录
	public function getPrevOneRecordsetID($id,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$operation='prev')
	{
		$queryWhere = $this->setDeletedWhere($queryWhere);
		return parent::getPrevOneRecordsetID($id,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$operation);
	}
	//返回下一条记录
	public function getNextOneRecordsetID($id,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids)
	{
		return $this->getPrevOneRecordsetID($id,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$operation='next');
	}
	//返回一条记录
	public function getOneRecordset($id,$userids,$groupids)
	{
		$data = parent::getOneRecordset($id,$userids,$groupids);
		return $data && $data[0]['deleted'] == 1 ? false : $data;
	}
	//插入一条记录
	public function insertOneRecordset($id,$data)
	{
		$data = $this->trimData($data);
		return parent::insertOneRecordset($id,$data);
	}
	//更新一条记录
	public function updateOneRecordset($id,$userids,$groupids,$data)
	{
		array_push($this->editFields, 'deleted','distribute_date','distribute_user','distribute_option','last_user_attach');
		$data = $this->trimData($data);
		$data = $this->setDistributeOption("MODIFY",$data);
		return parent::updateOneRecordset($id,$userids,$groupids,$data);
	}
	//删除一条记录
	public function deleteOneRecordset($id,$userids,$groupids)
	{
		array_push($this->editFields, 'deleted','distribute_date','distribute_user','distribute_option','last_user_attach');
		$data = $this->setDistributeOption("DELETE");
		return parent::updateOneRecordset($id,$userids,$groupids,$data);
	}
	//批量修改
	function batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount=true,$datas=Array())
	{
		array_push($modFields, 'deleted','distribute_date','distribute_user','distribute_option','last_user_attach');
		$queryWhere = $this->setDeletedWhere($queryWhere);
		$datas = $this->setDistributeOption("BATCH_MODIFY",$datas);
		return parent::batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount,$datas);
	}
	//批量删除
	function batchDelete($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$deleteAssociateby,$iscount=true)
	{
		$modFields = $this->batchEditFields;
		array_push($modFields, 'deleted','distribute_date','distribute_user','distribute_option','last_user_attach');
		$queryWhere = $this->setDeletedWhere($queryWhere);
		$datas = $this->setDistributeOption("BATCH_DELETE");
		return parent::batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount,$datas);
	}
};



?>