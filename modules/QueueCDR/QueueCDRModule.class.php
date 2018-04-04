<?php
class  QueueCDRModule extends BaseModule
{
	public $baseTable = 'zswitch_cc_queue_cdr';
	//模块描述
	public $describe = '呼叫中心队列呼叫记录';
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
					'uuid'           =>Array('5','S',false,'UUID'),										
					'queue'          =>Array('5','S',false,'队列'),										
					'caller_number'  =>Array('5','S',false,'主叫号码'),										
					'agent_name'     =>Array('5','S',false,'座席'),										
					'joined_time'    =>Array('5','S',false,'呼入时间'),										
					'bridge_time'  	 =>Array('5','S',false,'应答时间'),									    
					'end_time'       =>Array('5','S',false,'结束时'),										
					'state'          =>Array('20','E',false,'结束状态'),
					'total_timed'	 =>Array('3','N',false,'总时长(秒)'),
					'wait_timed'	 =>Array('3','N',false,'等待时长(秒)'),
					'talk_timed'	 =>Array('3','N',false,'通话时长(秒)'),
					);
	//安全字段,可以控制权限
	public $safeFields = Array(
					'uuid'          ,
	                'queue'         ,
	                'caller_number' ,
	                'agent_name'    ,
	                'joined_time'   ,
	                'bridge_time'  	,
	                'end_time'      ,
	                'state'         ,
					'total_timed'	,
					'wait_timed'	,
					'talk_timed'	,
					
					);
	//列表字段
	public $listFields = Array(
					'caller_number' ,
	                'queue'         ,
	                'agent_name'    ,
	                'joined_time'   ,
	                'bridge_time'  	,
	                'end_time'      ,
	                'state'         ,
					'talk_timed'	,
					);
	//编辑字段
	public $editFields = Array();

	//可排序字段
	public $orderbyFields = Array(
	                'queue'         ,
	                'caller_number' ,
	                'agent_name'    ,
	                'joined_time'   ,
	                'bridge_time'  	,
	                'end_time'      ,
	                'state'         ,
					'total_timed'	,
					'wait_timed'	,
					'talk_timed'	,
	);
	//默认排序
	public $defaultOrder = Array('joined_time','DESC');
	//详情入口字段
	public $enteryField = 'caller_number';

	//枚举字段值
	public $picklist = Array(
		'state' => Array('SUCCESS','Terminated','NONE','TIMEOUT','NO_AGENT_TIMEOUT','BREAK_OUT'),
	);


	
};



?>