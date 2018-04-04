<?php
class PSNumberGJSModule extends BaseModule
{
	public $baseTable = 'zswitch_ps_autodial_number';
	//模块描述
	public $describe = '电销拨号号码管理(贵金属行业版)';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'editView' => '编辑',
			'createView' => '新建',
			'copyView' => '复制',
			'save' => '保存',
			'delete' => '删除',
			'import' => '导入',
			'export' => '导出',
			'batchDelete' => '批量删除',
			'batchModify' => '批量修改',
			'missEdit'=>'快捷修改',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'number'       		=>Array('3','S',true,'外呼号码','',3,18),
	        'accountid'    		=>Array('50','N',false,'关联客户',-1),
	        'taskid'            =>Array('50','N',true,'拨号任务'),
	        'status'            =>Array('20','E',true,'状态','Waiting'),
	        'result'            =>Array('20','E',false,'呼叫结果','No call'),
	        'call_time'         =>Array('31','DT',false,'呼叫时间'),
	        'agent'             =>Array('5','S',false,'座席号码'),
	        'userid'            =>Array('50','N',false,'处理人',-1),
			'date_create'    	=>Array('35','DT',true,'记录创建的时间'),
			'user_create'    	=>Array('51','N',true,'创建记录的操作员'),
			'date_modify'    	=>Array('36','DT',false,'最后一次修改记录的时间'),
			'user_modify'    	=>Array('52','N',false,'最后一次修改记录的操作员'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'number'       ,
			'accountid'    ,
			'taskid'       ,
			'status'       ,
			'result'       ,
			'call_time'    ,
			'agent'        ,
			'userid'       ,
			'date_create'  ,
			'user_create'  ,
			'date_modify'  ,
			'user_modify'  ,
			);
	//列表字段
	public $listFields = Array(
			'number'       ,
			'accountid'    ,
			'taskid'       ,
			'status'       ,
			'result'       ,
			'call_time'    ,
			'userid'       ,
			);
	//编辑字段
	public $editFields = Array(
			'number'       ,
			'accountid'    ,
			'taskid'       ,
			'status'       ,
			'result'       ,
			'call_time'    ,
			'agent'        ,
			'userid'       ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'number'       ,
			'accountid'    ,
			'taskid'       ,
			'status'       ,
			'result'       ,
			'call_time'    ,
			'agent'        ,
			'userid'       ,
			'date_create'  ,
			'user_create'  ,
			'date_modify'  ,
			'user_modify'  ,
			);
			
	//允许批量修改字段		
	public $batchEditFields = Array(
			'number'       ,
			'accountid'    ,
			'taskid'       ,
			'status'       ,
			'result'       ,
			'call_time'    ,
			'agent'        ,
			'userid'       ,
			);
	//允许miss编辑字段
	public $missEditFields = Array(
			'number'       ,
			'accountid'    ,
			'taskid'       ,
			'status'       ,
			'result'       ,
			'call_time'    ,
			'agent'        ,
			'userid'       ,
			);
	
	
	//默认排序
	public $defaultOrder = Array('number','ASC');
	//详情入口字段
	public $enteryField = 'number';
	//详细/编辑视图默认列数
	public $defaultColumns = 4;
	
	//分栏定义
	public $blocks = Array(								
						);
	//枚举字段值
	public $picklist = Array(
		'status'         => Array('Waiting','Handling','Handled'),
		'result'         => Array('Talk','No answer','Busy','Empty number','Other','No call'),
	);
	
	//字段关联
	public $associateTo = Array(
		'user_create' => Array('MODULE','User','detailView','id','user_name'),
		'user_modify' => Array('MODULE','User','detailView','id','user_name'),
		'accountid'   => Array('MODULE','Accounts','detailView','id','account_name'),	
		'taskid'      => Array('MODULE','PSTaskGJS','detailView','id','name'),	
		'userid'      => Array('MODULE','User','detailView','id','user_name'),	
	);
	//模块关联
	public $associateBy = Array(
	);
	//记录权限关联字段名
	public $shareField = 'user_create';
	
	//
	public function autoCompleteFieldValue($field,$pfx)
	{
	}
};



?>