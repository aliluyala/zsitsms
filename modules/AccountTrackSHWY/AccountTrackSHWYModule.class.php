<?php
class AccountTrackSHWYModule extends BaseModule
{
	public $baseTable = 'account_track';
	//模块描述
	public $describe = '客户跟踪记录(上海唯佑专用)';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'         => '浏览',
			'detailView'    => '详情',
			'editView'      => '编辑',
			'createView'    => '新建',
			'save'          => '保存',
			'delete'        => '删除',
			'export'        => '导出',
			'batchDelete'   => '批量删除',
			'missEdit'      => '快捷修改',
			'modifyFilter'  => '编辑过滤',
			'addTrackPopup' => '添加跟踪记录(弹窗)',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
	        'accountid'            => array('50','N',false,'关联客户',0),
			'intention'            => array('20','E',true,'意向程度'),
	        'status'          	   => array('20','E',true,'状态'),
	        'report'               => array('26','S',true,'销售说明'),
	        'remark'               => array('9','S',false,'记录事项'),
	        'preset_time'          => array('31','DT',false,'预约日期'),
	        'date_create'          => array('35','DT',false,'记录时间'),
	        'user_create'          => array('51','N',false,'记录人'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'accountid'		,
			'status'		,
			'report'		,
			'remark'		,
			'preset_time'	,
			'date_create'	,
			'user_create'	,
			);
	//列表字段
	public $listFields = Array(
			'status'		,
			'accountid'   	,
			'report'		,
			'preset_time'	,
			'date_create' 	,
			'user_create' 	,
			);
	//编辑字段
	public $editFields = Array(
			'accountid'   	,
			'status'		,
			'report'		,
			'remark'		,
			'preset_time'	,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'accountid'   	,
			'status'		,
			'report'		,
			'remark'		,
			'preset_time'	,
			'date_create'	,
			);

	//允许批量修改字段
	public $batchEditFields = Array(
			);
	//允许miss编辑字段
	public $missEditFields = Array(
			);


	//默认排序
	public $defaultOrder = Array('date_create','ASC');
	//详情入口字段
	public $enteryField = 'status';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array(
							'LBL_ACCOUNT_TRACK_BASE'=>Array('2',true,Array(
																	'accountid'   ,
																	'status'	  ,
																	'report'	  ,
																	'preset_time' ,
																	'date_create' ,
																	'user_create' ,
																	)),
						   'LBL_ACCOUNT_TRACK_INFO'=>Array('1',true,Array(
																	'remark',
																	)),
						);
	//枚举字段值
	public $picklist = Array(
		'intention'			=> array('HIGH','MIDDLE','LOW'),
		'status'			=> array('NOT_CONNECTED','REJECT','TO_BE_TRACKED','NON_BUSINESS_SCOPE','INVALID','SUCCESS','FIRST_DIAL','FAILED'),
		'report'			=> array('picklist_define'=>array('status','result_report','report','status')),
	);

	//字段关联
	public $associateTo = Array(
		'user_create' => Array('MODULE','User','detailView','id','user_name'),
		'accountid' => Array('MODULE','AccountsSHWY','detailView','id','owner'),
	);
	//模块关联
	public $associateBy = Array(
	);
	//记录权限关联字段名
	public $shareField = 'user_create';

	//
	public function autoCompleteFieldValue($field,$pfx)
	{
		return parent::autoCompleteFieldValue($field,$pfx);
	}
	public function insertOneRecordset($id,$data){
		return parent::insertOneRecordset($id,$data);
	}
};



?>