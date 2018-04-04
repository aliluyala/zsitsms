<?php
class PSTaskGJSModule extends BaseModule
{
	public $baseTable = 'zswitch_ps_autodial_tasks';
	//模块描述
	public $describe = '电销拨号任务管理(贵金属行业版)';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'editView' => '编辑',
			'createView' => '新建',
			'copyView' => '复制',
			'save' => '保存',
			'delete' => '删除',
			'missEdit'=>'快捷修改',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'name'            =>Array('5','S',true,'任务名'),
			'groupid'         =>Array('50','N',true,'归属工作组'),
			'state'           =>Array('21','E',true,'任务状态','Stop'),
			'date_create'     =>Array('35','DT',true,'创建时间'),
			'user_create'     =>Array('51','N',true,'创建人'),
			'date_modify'     =>Array('36','DT',true,'修改时间'),
			'user_modify'     =>Array('52','N',true,'修改人'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'name'          ,
			'groupid'       ,
			'state'         ,
			'date_create'   ,
			'user_create'   ,
			'date_modify'   ,
			'user_modify'   ,
			);
	//列表字段
	public $listFields = Array(
			'name'          ,
			'groupid'       ,
			'state'         ,
			'date_create'   ,
			'user_create'   ,
			);
	//编辑字段
	public $editFields = Array(
			'name'          ,
			'groupid'       ,
			'state'         ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'name'          ,
			'groupid'       ,
			'state'         ,
			'date_create'   ,
			'user_create'   ,
			'date_modify'   ,
			'user_modify'   ,			
			);
			
	//允许批量修改字段		
	public $batchEditFields = Array(
			);
	//允许miss编辑字段
	public $missEditFields = Array(
			'name'          ,
			'groupid'       ,
			'state'         ,
			);
	
	
	//默认排序
	public $defaultOrder = Array('date_create','ASC');
	//详情入口字段
	public $enteryField = 'name';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	public $blocks = Array(										
						);
	//枚举字段值
	public $picklist = Array(
		'state'         => Array('Stop','Runing'),	
	);
	
	//字段关联
	public $associateTo = Array(
		'user_create' => Array('MODULE','User','detailView','id','user_name'),
		'user_modify' => Array('MODULE','User','detailView','id','user_name'), 
		'groupid'     => Array('MODULE','GroupManager','detailView','id','name'), 	
	);
	//模块关联
	public $associateBy = Array(
		'ASSOCIATE_NUMBER_INFO' => Array('PSNumberGJS','taskid','number','status','result','call_time','userid'),
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