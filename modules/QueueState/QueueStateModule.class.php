<?php
class  QueueStateModule extends BaseModule
{
	public $baseTable = 'zswitch_cc_queue_state';
	//模块描述
	public $describe = '呼叫中心队列状态';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',   
			'detailView' => '详情',		
			'export' => '导出',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')

	public $fields = Array(
					'name'                    => Array('5','S',true,'队列名字'),
					'state'                   => Array('20','E',true,'队列状态'),
					'total_calls_answered'    => Array('6','N',true,'系统启动以来累计应答次数'),
					'total_calls_no_answer'   => Array('6','N',true,'系统启动以来累计未应答次数'),
					'today_calls_answered'    => Array('6','N',true,'今天累计应答次数'),
					'today_calls_no_answer'   => Array('6','N',true,'今天累计未应答次数'),
					'total_talk_time'         => Array('6','N',true,'系统启动以来累计通话时长(秒)'),
					'today_talk_time'         => Array('6','N',true,'今天累计通话时长(秒)'),
					'current_members'         => Array('6','N',true,'当前在线数量'),	
					);
	//安全字段,可以控制权限
	public $safeFields = Array(
					'name'                    ,
					'state'                   ,
	                'total_calls_answered'    ,
	                'total_calls_no_answer'   ,
	                'today_calls_answered'    ,
	                'today_calls_no_answer'   ,
	                'total_talk_time'         ,
					'today_talk_time'         ,
					'current_members'         ,
					);	
	//列表字段
	public $listFields = Array(
					'name'                    ,
					'state'                   ,
	                'total_calls_answered'    ,
	                'total_calls_no_answer'   ,
	                'today_calls_answered'    ,
	                'today_calls_no_answer'   ,
	                'total_talk_time'         ,
					'today_talk_time'         ,
					'current_members'         ,
					);
	//编辑字段
	public $editFields = Array();

	//可排序字段
	public $orderbyFields = Array(
					'name'                    ,
					'state'                   ,
	                'total_calls_answered'    ,
	                'total_calls_no_answer'   ,
	                'today_calls_answered'    ,
	                'today_calls_no_answer'   ,
	                'total_talk_time'         ,
					'today_talk_time'         ,
					'current_members'         ,
					);
	//默认排序
	public $defaultOrder = Array('name','ASC');
	//详情入口字段
	public $enteryField = 'name';

	//枚举字段值
	public $picklist = Array(
		'state' => Array('ON','OFF'),
	);
	

	
};



?>