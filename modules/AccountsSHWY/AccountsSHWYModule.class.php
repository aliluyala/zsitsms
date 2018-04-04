<?php
class AccountsSHWYModule extends BaseModule
{
	public $baseTable = 'accounts_shwy';
	//模块描述
	public $describe = '客户资料管理(上海唯佑)';
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
			'owner'            => Array('7','S',true,'客户名称','',0,255,'160px'),
			'contact'          => Array('7','S',true,'联系人','',0,255,'160px'),
			'telphone'         => Array('60','S',false,'固定电话(5-20位数字)','',5,20),
			'mobile'           => Array('60','S',true,'电话号码(5-20位数字)','',5,20),
			'address'          => Array('5','S',false,'客户的详细地址'),
			'id_code'          => Array('5','S',false,'身份证/机构代码'),
			'lending_bank'     => Array('5','S',false,'贷款银行'),
			'intention'        => Array('20','E',false,'意向程度'),
			'dangtime'         => Array('31','DT',false,'备注时间','0000-00-00 00:00:00'),
			'illegalcoeff'    =>  Array('5','N',false,'违章系数',0),
			'dangerfactor'    =>  Array('5','N',false,'出险系数',0),
			'dangernumber'    =>  Array('5','N',false,'出险次数',0),
			'discountfactor'  =>  Array('5','N',false,'折扣系数',0),
			'levelrisk'       =>  Array('27','S',false,'客户风险级别',''),
			'data_sources'    =>  Array('27','S',true,'数据来源',''),

			'team'             => Array('5','S',false,'品牌团队'),
			'area'             => Array('20','S',false,'片区'),
			'park'             => Array('26','S',false,'园区'),
			'company'          => Array('27','S',false,'保险公司',''),
			'batch'            => Array('20','E',false,'批次'),
			'type'             => Array('20','E',false,'名单类型'),

			'last_policy'      => Array('5','S',false,'去年保单号码'),
			'purchase_price'   => Array('5','N',false,'新车购置价(元)',0,0,999999999),
			'plate_no'         => Array('7','S',true,'车牌号码','',7,7,'160px'),
			'vehicle_type'     => Array('20','S',false,'车辆种类'),
			'use_character'    => Array('20','S',false,'使用性质',''),
			'model'            => Array('5','S',false,'品牌型号'),
			'vin'              => Array('7','S',true,'车架号/车辆识别代码','',1,17,'160px','^[a-zA-Z0-9]{1,17}$','车辆识别码由17位字母数字组成'),
			'engine_no'        => Array('5','S',false,'发动机号'),
			'register_date'    => Array('30','D',false,'注册日期','0000-00-00'),
			'register_address' => Array('5','S',false,'注册地'),
			'seats'            => Array('5','N',false,'核定载人数',0,0,999999999),
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
			'clover_id'        => Array('5','N',false,'四叶草ID',1),
			'remark'           => Array('9','S',false,'备注'),

			'user_attach'      => Array('55','N',true,'记录归属于组或用户，将决定其它用户访问此记录的权限',1),
			'user_create'      => Array('51','N',true,'创建记录的操作员'),
			'user_modify'      => Array('52','N',false,'最后一次修改记录的操作员'),
			'date_create'      => Array('35','DT',true,'记录创建的时间','0000-00-00'),
			'date_modify'      => Array('36','DT',false,'最后一次修改记录的时间','0000-00-00'),
			'expiration_date'  => Array('31','DT',false,'保险到期日期','0000-00-00 00:00:00'),
			'preset_time'      => Array('31','DT',false,'预约日期','0000-00-00 00:00:00'),
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
			'dangtime'        ,
			'illegalcoeff'    ,
			'dangerfactor'    ,
			'dangernumber'    ,
			'discountfactor'  ,
			'levelrisk'       ,
			'data_sources'    ,
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
			'clover_id'       ,
			);
	//列表字段
	public $listFields = Array(
			'owner'				,
			'mobile'            ,
			//'model'				,
			'plate_no'	        ,
			'register_date'	    ,
			'company'			,
			'expiration_date'	,
			'preset_time'	    ,
			'batch'	            ,
			'intention'         ,
			'type'              ,
			'status'            ,
			'report'            ,
			'remark'			,
			'user_attach'       ,
			/*'dangtime'        ,
			'illegalcoeff'    ,
			'dangerfactor'    ,
			'dangernumber'    ,
			'discountfactor'  ,*/
			'levelrisk'       ,
			'clover_id'       ,
			//'date_create'     ,
			'data_sources'	,
			'date_modify'	,
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
			'dangtime'        ,
			'illegalcoeff'    ,
			'dangerfactor'    ,
			'dangernumber'    ,
			'discountfactor'  ,
			'levelrisk'       ,
			'clover_id'       ,
			'data_sources'    ,
			//'date_create'     ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'owner'				,
			'plate_no'	        ,
			'model'				,
			'mobile'            ,
			'vin'             ,
			'register_date'	    ,
			'company'			,
			'expiration_date'	,
			'preset_time'	    ,
			'batch'	            ,
			'intention'         ,
			'data_sources'    ,
			'type'              ,
			'status'            ,
			'report'            ,
			'user_attach'       ,
			'remark'   		    ,
			'dangtime'        ,
			'illegalcoeff'    ,
			'dangerfactor'    ,
			'dangernumber'    ,
			'discountfactor'  ,
			'levelrisk'       ,
			//'clover_id'       ,
			'date_create'     ,
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
			'dangtime'        ,
			'illegalcoeff'    ,
			'dangerfactor'    ,
			'dangernumber'    ,
			'discountfactor'  ,
			'levelrisk'       ,
			'data_sources'    ,
			'clover_id'       ,
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
			'dangtime'        ,
			'illegalcoeff'    ,
			'dangerfactor'    ,
			'dangernumber'    ,
			'discountfactor'  ,
			'levelrisk'       ,
			'data_sources'    ,
			'clover_id'       ,
			'date_create'     ,
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
									'data_sources'    ,

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
						   'LBL_ACCOUNT_REMARK'=>Array('2',true,Array(
						   			'clover_id'       ,
									'remark'   		  ,
									'dangtime'        ,
									'illegalcoeff'    ,
									'dangerfactor'    ,
									'dangernumber'    ,
									'discountfactor'  ,
									'levelrisk'       ,
						   	)),
						   'LBL_ACCOUNT_ADDR'=>Array('1',true,Array(
									'user_attach'       ,
							)),);
	//枚举字段值
	public $picklist = Array(
		'intention'			=> Array('HIGH','MIDDLE','LOW'),
		'use_character'		=> Array('','OPERATING','NON_OPERATING'),
		'origin'			=> Array('IMPORT','DOMESTIC'),
		'batch'				=> Array('A','B','C','D','E','F','G','H'),
		'area'				=> Array('','CHENGDU','ZONE','PERIPHERY'),
		'park'				=> Array('picklist_define'=>Array('area','park','park','area')),
		'type'				=> Array('FIRST_YEAR','RENEWAL','ORPHAN','LOAN_RENEWAL'),
		'status'			=> Array('NOT_CONNECTED','REJECT','TO_BE_TRACKED','NON_BUSINESS_SCOPE','INVALID','SUCCESS','FIRST_DIAL','FAILED'),
		'report'			=> Array('picklist_define'=>array('status','result_report','report','status')),
		'vehicle_type'		=> Array('PRIVATE','ENTERPRISE','AUTHORITY','LEASE_RENTAL','CITY_BUS','HIGHWAY_BUS','TRUCK','TRAILER','SPECIAL_AUTO','MOTORCYCLE','DUAL_PURPOSE_TRACTOR','TRANSPORT_TRACTOR','LOW_SPEED_TRUCK'),
	);

	//字段关联
	public $associateTo = Array(
		//'levelrisk'     => array('MODULE','Level','detailView','id','levelname'),
		//'user_attach'   => array('MODULE','User','detailView','id','user_name'),
		//'company'		=> array('MODULE','Company','detailView','id','name'),
		'user_create'	=> array('MODULE','User','detailView','id','user_name'),
		'user_modify'	=> array('MODULE','User','detailView','id','user_name'),
	);
	//模块关联
	public $associateBy = Array(
		'ASSOCIATE_TRACKRECORD_INFO'      => array('AccountTrackSHWY','accountid','status','report','remark','user_create','preset_time','date_create'),
	    //'ASSOCIATE_INSURANCE_INFO'  => array('Insurance','autoid','calculate_no','create_time','create_userid'),
		'ASSOCIATE_INSURANCE_CREATE_INFO'  => array('PolicyCalculate','vin'),
	);
	//记录权限关联字段名
	public $shareField = 'user_attach';

	//防止PHP notice
	function _get($str){
		$val = isset($_REQUEST[$str]) ? $_REQUEST[$str] : NULL;
		return $val;
	}

	public function isRepeat($clover_id,$recordid){
		$result = $this->getAccountByVin($clover_id,$recordid);
		if($result){
		    $repeat = "<a href='index.php?module=Index&action=index#index.php?module=AccountsSHWY&action=detailView&recordid={$result["id"]}&return_module=AccountsSHWY&return_action=index'>";
		    $repeat .= "<strong style='color:#1E90FF;border:none;'>{$result['owner']}</strong></a>";
		    return_ajax('error','车架号重复!相同车架号ID客户为:'.$repeat);
		}else{
		    return_ajax('success',"ID可用。");
		}
	}
	public function getUserAttach($name){
		global $APP_ADODB,$CURRENT_USER_ID;
		$sql = "SELECT id FROM users WHERE user_name = '$name' union SELECT id FROM groups WHERE name = '$name'";
		$result = $APP_ADODB->Execute($sql);
		return $result->RecordCount() ? $result->fields["id"] : $CURRENT_USER_ID;
	}
	public function getAccountByMobile($mobile,$recordid = 0){
		global $APP_ADODB,$CURRENT_USER_ID;
		$sqlAffix = $recordid ? "AND id <> {$recordid}" : "";
		$sql = "SELECT id,owner FROM {$this->baseTable} WHERE mobile = '{$mobile}' {$sqlAffix} LIMIT 1";
		$result = $APP_ADODB->Execute($sql);
		return $result->RecordCount() ? array("id" => $result->fields["id"] , "owner" => $result->fields['owner']) : 0;
	}
	public function getAccountByVin($vin,$recordid = 0){

		global $APP_ADODB,$CURRENT_USER_ID;
		$sqlAffix = $recordid ? "AND id <> {$recordid}" : "";
		$sql = "SELECT id,owner FROM {$this->baseTable}  WHERE vin = '{$vin}' {$sqlAffix} LIMIT 1";
		$result = $APP_ADODB->Execute($sql);
		return $result->RecordCount() ? array("id" => $result->fields["id"] , "owner" => $result->fields['owner']) : 0;
	}


	/*public function getAccountByclover($clover_id,$recordid = 0){
		global $APP_ADODB,$CURRENT_USER_ID;
		$sqlAffix = $recordid ? "AND id <> {$recordid}" : "";
		$sql = "SELECT id,owner FROM {$this->baseTable} WHERE clover_id = '{$clover_id}' {$sqlAffix} LIMIT 1";
		$result = $APP_ADODB->Execute($sql);
		return $result->RecordCount() ? array("id" => $result->fields["id"] , "owner" => $result->fields['owner']) : 0;
	}*/
	public function insertOneRecordset($id,$data){

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
		return parent::insertOneRecordset($id,$data);
	}

	public function updateOneRecordset($id,$userids,$groupids,$data)
	{
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
		return parent::updateOneRecordset($id,$userids,$groupids,$data);
	}

	public function getOneRecordsetForInsurance($id,$userids,$groupids){
		$result = parent::getOneRecordset($id,$userids,$groupids);
		if(!isset($result) || empty($result))
			return NULL;
    	return $result[0];
	}
};



?>