<?php
class Recycle_FinancialModule extends BaseModule
{
	public $baseTable = 'accounts_financial';
	//模块描述
	public $describe = '回收站';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'        => '浏览',
			'detailView'   => '详情',
			'recycle'	   => '恢复',
			'delete'       => '删除',
			'export'       => '导出',
			'batchRecycle' => '批量删除',
			'batchDelete'  => '批量删除',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'owner'            => Array('5','S',true,'客户名称'),
			'gender'		   => Array('21','E',true,'性别','MAN'),
			'age'			   => Array('5','S',true,'年龄','',0,128),
			'profession'	   => Array('5','S',false,'职业'),
			'oicq'	           => Array('5','S',false,'QQ'),
			'wechat'           => Array('5','S',false,'微信号'),
			'id_code'          => Array('5','S',false,'身份证/机构代码'),
			'progress'     	   => Array('50','N',false,'开发进度',0),
			'address'          => Array('5','S',false,'客户的详细地址'),
			'intention'        => Array('20','E',true,'意向程度'),
			'register_date'    => Array('30','D',false,'登记日期','0000-00-00'),
			'telphone'         => Array('60','S',false,'固定电话(5-20位数字)','',5,20),
			'mobile'           => Array('60','S',false,'电话号码(5-20位数字)','',5,20),
			'status'           => Array('20','E',true,'状态'),
			'report'           => Array('26','S',false,'销售说明'),
			'area'             => Array('20','E',false,'片区'),
			'park'             => Array('26','S',false,'园区'),
			'batch'            => Array('20','E',false,'批次'),
			'type'             => Array('20','E',false,'名单类型'),
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
			'preset_time'      => Array('31','DT',false,'预约日期',"0000-00-00 00:00:00"),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'owner'            ,
			'gender'		   ,
			'age'			   ,
			'profession'	   ,
			'oicq'	           ,
			'wechat'           ,
			'id_code'          ,
			'progress'     	   ,
			'address'          ,
			'intention'        ,
			'register_date'	   ,
			'telphone'         ,
			'mobile'           ,
			'status'           ,
			'report'           ,
			'area'             ,
			'park'             ,
			'batch'            ,
			'type'             ,
			'remark'           ,
			'deleted'		   ,
			'distribute_date'  ,
			'distribute_user'  ,
			'distribute_option',
			'last_user_attach' ,
			'user_attach'      ,
			'user_create'      ,
			'user_modify'      ,
			'date_create'      ,
			'date_modify'      ,
			'preset_time'      ,
			);
	//列表字段
	public $listFields = Array(
			'owner'		   ,
			'progress'	   ,
			'oicq'	 	   ,
			'wechat'       ,
			'telphone'	   ,
			'mobile'	   ,
			'batch'	       ,
			'preset_time'  ,
			'register_date',
			'status'       ,
			'report'	   ,
			'user_attach'  ,
			);
	//编辑字段
	public $editFields = Array(
			'owner'		   ,
			'gender'	   ,
			'age'		   ,
			'profession'   ,
			'oicq'	 	   ,
			'progress'     ,
			'wechat'       ,
			'id_code'	   ,
			'telphone'	   ,
			'register_date',
			'mobile'	   ,
			'address'	   ,
			'intention'	   ,
			'area'		   ,
			'park'	 	   ,
			'batch'	   	   ,
			'type'	  	   ,
			'status'	   ,
			'report'	   ,
			'remark'	   ,
			'preset_time'  ,
			'user_attach'  ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'owner'		   ,
			'oicq'	 	   ,
			'progress'     ,
			'wechat'       ,
			'telphone'	   ,
			'mobile'	   ,
			'batch'	       ,
			'id_code'	   ,
			'status'       ,
			'report'	   ,
			'preset_time'  ,
			'register_date',
			'user_attach'  ,
	);

	//允许批量修改字段
	public $batchEditFields = Array(
			'owner'		  ,
			'batch'	      ,
			'intention'   ,
			'status'      ,
			'preset_time' ,
			'user_attach' ,
	);
	//允许miss编辑字段
	public $missEditFields = Array(
			'owner'            ,
			'gender'		   ,
			'age'			   ,
			'profession'	   ,
			'oicq'	           ,
			'wechat'           ,
			'id_code'          ,
			'progress'     	   ,
			'address'          ,
			'intention'        ,
			'register_date'	   ,
			'telphone'         ,
			'mobile'           ,
			'status'           ,
			'report'           ,
			'area'             ,
			'park'             ,
			'batch'            ,
			'type'             ,
			'remark'           ,
			'deleted'		   ,
			'preset_time'      ,
			);
	//默认排序
	public $defaultOrder = Array('owner','ASC');
	//详情入口字段
	public $enteryField = 'owner';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array('LBL_ACCOUNT_BASE'=>Array('4',true,Array(
									'owner'		   ,
									'gender'	   ,
									'age'		   ,
									'profession'   ,
									'oicq'	 	   ,
									'wechat'       ,
									'id_code'	   ,
									'telphone'	   ,
									'mobile'	   ,
									'progress'	   ,
									'address'	   ,
									'register_date',
							)),
						   'LBL_ACCOUNT_INFO'=>Array('4',true,Array(
						   			'intention'	  ,
									'preset_time' ,
						   			'area'		  ,
									'park'	 	  ,
									'batch'	   	  ,
									'type'	  	  ,
									'status'	  ,
									'report'	  ,
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
		'gender'				=> Array('MAN','WOMAN'),
		'intention'			=> Array('HIGH','MIDDLE','LOW'),
		'batch'				=> Array('A','B','C','D','E','F','G','H'),
		'area'				=> Array('','CHENGDU','ZONE','PERIPHERY'),
		'park'				=> Array('picklist_define'=>Array('area','park','park','area')),
		'type'				=> Array('FIRST_YEAR','RENEWAL','ORPHAN','LOAN_RENEWAL'),
		'status'			=> Array('FIRST_DIAL','FAILED','INVALID','APPOINTMENT_QUOTATION','APPOINTMENT_NON_QUOTATION','SUCCESS'),
		'report'			=> Array('picklist_define'=>array('status','result_report','report','status')),
		'deleted'			=> Array('NO','YES'),
		'distribute_option' => Array('HANDOUT','HANDOUT_DELETE','HANDOUT_RECYCLE','HANDOUT_TRANSFER','BATCH_MODIFY','BATCH_DELETE','MODIFY','DELETE','RECYCLE','BATCH_RECYCLE'),
	);

	//字段关联
	public $associateTo = Array(
		'progress'	    => array('MODULE','Progress','detailView','id','name'),
		'user_create'	=> array('MODULE','User','detailView','id','user_name'),
		'user_modify'	=> array('MODULE','User','detailView','id','user_name'),

		//'last_user_attach' => Array('MODULE','User','detailView','id','user_name'),
		'distribute_user'  => Array('MODULE','User','detailView','id','user_name'),
	);
	//模块关联
	public $associateBy = Array(
		'ASSOCIATE_TRACKRECORD_INFO'      => array('AccountTrack','accountid','status','report','remark','user_create','preset_time','date_create'),
	);
	//记录权限关联字段名
	public $shareField = 'user_attach';

	//防止PHP notice
	function _get($str){
		$val = isset($_REQUEST[$str]) ? $_REQUEST[$str] : NULL;
		return $val;
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
		$where[] = Array("deleted","=",1,"","");
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
		return $data && $data[0]['deleted'] != 1 ? false : $data;
	}
	//恢复一条记录
	public function recycleOneRecordset($id,$userids,$groupids){
		array_push($this->editFields, 'deleted','distribute_date','distribute_user','distribute_option','last_user_attach');
		$data = $this->setDistributeOption("RECYCLE");
		return parent::updateOneRecordset($id,$userids,$groupids,$data);
	}
	//批量恢复
	public function batchRecycle($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount=true,$datas=Array()){
		array_push($modFields, 'deleted','distribute_date','distribute_user','distribute_option','last_user_attach');
		$queryWhere = $this->setDeletedWhere($queryWhere);
		$datas = $this->setDistributeOption("BATCH_RECYCLE");
		return parent::batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount,$datas);
	}
	//删除一条记录
	public function deleteOneRecordset($id,$userids,$groupids)
	{
		return parent::deleteOneRecordset($id,$userids,$groupids);
	}
	//批量删除
	function batchDelete($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$deleteAssociateby,$iscount=true)
	{
		$queryWhere = $this->setDeletedWhere($queryWhere);
		return parent::batchDelete($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$deleteAssociateby,$iscount);
	}
};



?>