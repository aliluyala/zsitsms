<?php
class  ZSwitchManagerModule extends BaseModule
{
	public $baseTable = 'zswitch_call_details';
	//模块描述
	public $describe = '呼叫记录';
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
					'direction' => Array('20','E',false,'方向'),
					'caller_id_number' => Array('5','S',false,'主叫号码'),
					'callee_id_number' => Array('5','S',false,'被叫号码'),
					'destination_number' => Array('5','S',false,'目的号码'),
					'uuid' => Array('5','S',false,'UUID'),
					'context' => Array('5','S',false,'上下文'),
					'channel_name' => Array('5','S',false,'通道名'),
					'channel_created_datetime' => Array('31','DT',false,'开始时间'),
					'channel_answered_datetime' => Array('31','DT',false,'结束时间'),	
					'channel_hangup_datetime' => Array('31','DT',false,'挂机时间'),
					'bleg_uuid' => Array('5','S',false,'b脚UUID'),
					'hangup_cause' => Array('20','E',false,'挂机原因'),
					);
	//安全字段,可以控制权限
	public $safeFields = Array('direction','caller_id_number','callee_id_number','destination_number','uuid','context','channel_name',
							   'channel_created_datetime','channel_answered_datetime','channel_hangup_datetime','bleg_uuid','hangup_cause');
	//列表字段
	public $listFields = Array('uuid','direction','caller_id_number','destination_number','callee_id_number','channel_created_datetime',
							   'channel_answered_datetime','channel_hangup_datetime','hangup_cause');
	//编辑字段
	public $editFields = Array();

	//可排序字段
	public $orderbyFields = Array('direction','caller_id_number','callee_id_number','destination_number','context','channel_name',
							      'channel_created_datetime','channel_answered_datetime','channel_hangup_datetime','hangup_cause');
	//默认排序
	public $defaultOrder = Array('channel_created_datetime','DESC');
	//详情入口字段
	public $enteryField = 'uuid';

	//枚举字段值
	public $picklist = Array(
		'direction' => Array('inbound','outbound'),
		'hangup_cause' => Array('NORMAL_CLEARING','USER_NOT_REGISTERED','USER_BUSY','ORIGINATOR_CANCEL','NO_ANSWER'),
	);
	

	
};



?>