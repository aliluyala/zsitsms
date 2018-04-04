<?php
class  AccountEvaluateModule extends BaseModule
{
	public $baseTable = 'zswitch_cc_account_evaluate';
	//模块描述
	public $describe = '呼叫中心客户评价记录';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',   
			'detailView' => '详情',
			'delete' => '删除',			
			'export' => '导出',
			'batchDelete' => '批量删除',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')

	public $fields = Array(
					'userid'            =>Array('50','N',false,'关联用户'),	
					'agent'             =>Array('5','S',false,'座席号码'),	
					'caller'        	=>Array('5','S',false,'主叫号码'),										
					'callee'             =>Array('5','S',false,'接入号码'),										
					'ptime'      		=>Array('31','DT',false,'时间'),										
					'dtmf'              =>Array('5','S',false,'按键值'),										
					'uuid'           	=>Array('5','S',false,'UUID'),		
					);
	//安全字段,可以控制权限
	public $safeFields = Array(
					'userid'  ,
					'agent'   ,
	                'caller'  ,
	                'callee'   ,
	                'ptime'   ,
	                'dtmf'    ,
	                'uuid'    ,
					);
	//列表字段
	public $listFields = Array(
	                'caller'  ,	
					'userid'  ,
					'agent'   ,
	                'callee'   ,
	                'ptime'   ,
	                'dtmf'    ,
					);
	//编辑字段
	public $editFields = Array();

	//可排序字段
	public $orderbyFields = Array(
					'userid'  ,
					'agent'   ,
	                'caller'  ,
	                'callee'   ,
	                'ptime'   ,
	                'dtmf'    ,
	);
	//默认排序
	public $defaultOrder = Array('ptime','DESC');
	//详情入口字段
	public $enteryField = 'caller';

	//枚举字段值
	public $picklist = Array(
	);
	//字段关联
	public $associateTo = Array(
		'userid' => Array('MODULE','User','detailView','id','user_name'),
	);
	//记录权限关联字段名
	public $shareField = 'userid';	
};



?>