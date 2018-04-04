<?php
class AccountAppointmentModule extends BaseModule
{
	public $baseTable = 'account_appointment';
	//模块描述
	public $describe = '客户预约记录';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'editView' => '编辑',
			'createView' => '新建',
			'save' => '保存',
			'delete' => '删除',
			'export' => '导出',
			'batchDelete' => '批量删除',
			'missEdit'=>'快捷修改',
			'modifyFilter' => '编辑过滤',
			'addAppointmentPopup' => '添加',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'accountid'       =>Array('50','N',false,'关联客户'),  
	        'appointment_time'=>Array('31','DT',false,'预约时间'),        
	        'state'           =>Array('21','E',false,'状态','Waiting'),    
	        'remark'          =>Array('5','S',false,'备注信息'),    
	        'date_handle'     =>Array('36','DT',false,'处理预约的时间'),     
	        'user_handle'     =>Array('52','N',false,'处理预约的操作员'),    
	        'date_create'     =>Array('35','DT',false,'记录创建的时间'),    
	        'user_create'     =>Array('51','N',false,'创建记录的操作员'),    
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'accountid'       ,
			'appointment_time',
			'state'           ,
			'remark'          ,
			'user_handle'     ,
			'date_handle'     ,
			'date_create'     ,
			'user_create' 	  ,	
			);
	//列表字段
	public $listFields = Array(
			'appointment_time',
			'accountid'       ,
			'state'           ,
			'remark'          ,
			'user_handle'     ,
			'date_handle'     ,
			);
	//编辑字段
	public $editFields = Array(
			'accountid'       ,
			'appointment_time',
			'state'           ,
			'remark'          ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'accountid'       ,
			'appointment_time',
			'state'           ,
			'user_handle'     ,
			'date_handle'     ,
			'date_create'     ,
			'user_create' 	  ,	
			);
			
	//允许批量修改字段		
	public $batchEditFields = Array(
			);
	//允许miss编辑字段
	public $missEditFields = Array(
			'appointment_time',
			'state'           ,
			'remark'          ,
			);
	
	
	//默认排序
	public $defaultOrder = Array('appointment_time','ASC');
	//详情入口字段
	public $enteryField = 'appointment_time';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	//public $blocks = Array();
	//枚举字段值
	public $picklist = Array(
		'state'          => Array('Waiting','Handled','Cancel'),
	);
	
	//字段关联
	public $associateTo = Array(
		'user_create' => Array('MODULE','User','detailView','id','user_name'),
		'user_handle' => Array('MODULE','User','detailView','id','user_name'), 
		'accountid' => Array('MODULE','Accounts','detailView','id','account_name'),	
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
};



?>