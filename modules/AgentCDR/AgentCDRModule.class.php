<?php
class  AgentCDRModule extends BaseModule
{
	public $baseTable = 'zswitch_cc_agent_cdr';
	//模块描述
	public $describe = '呼叫中心座席呼叫记录';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'          => '浏览',
			'detailView'     => '详情',
			'delete'         => '删除',
			'import'         => '导入',
			'export'         => '导出',
			'batchDelete'    => '批量删除',
			'modifyFilter'   => '编辑过滤',
			'playRecordView' =>'播放录音',
			'homeInfo'       =>'首页信息',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')

	public $fields = Array(
					'userid'            => Array('50','N',false,'关联用户'),
					'queue'             => Array('5','S',false,'队列'),
					'agent_name'        => Array('5','S',false,'座席号码'),
					'dir'               => Array('20','E',false,'方向'),
					'other_number'      => Array('5','S',false,'对方号码'),
					'other_area_code'   => Array('5','S',false,'对方区号'),
					'uuid'              => Array('5','S',false,'UUID'),
					'source'            => Array('5','S',false,'呼叫源'),
					'context'           => Array('5','S',false,'拨号计划上下文'),
					'channel_name'      => Array('5','S',false,'通道名'),
					'created_datetime'  => Array('31','DT',false,'呼叫时间'),
					'answered_datetime' => Array('31','DT',false,'应答时间'),
					'hangup_datetime'   => Array('31','DT',false,'挂机时间'),
					'bleg_uuid'         => Array('5','S',false,'b脚UUID'),
					'hangup_cause'      => Array('20','E',false,'挂机原因'),
					'total_timed'       => Array('3','N',false,'总时长(秒)'),
					'talk_timed'        => Array('3','N',false,'通话时长(秒)'),
					);
	//安全字段,可以控制权限
	public $safeFields = Array(
					'userid'           ,
					'queue'            ,
	                'agent_name'       ,
	                'dir'              ,
	                'other_number'     ,
					'other_area_code'  ,
	                'uuid'             ,
	                'source'           ,
	                'context'          ,
	                'channel_name'     ,
	                'created_datetime' ,
	                'answered_datetime',
	                'hangup_datetime'  ,
	                'bleg_uuid'        ,
	                'hangup_cause'     ,
					'total_timed'	   ,
					'talk_timed'	   ,
					);
	//列表字段
	public $listFields = Array(
					'other_number'     ,
					'other_area_code'  ,
					'queue'            ,
	                'agent_name'       ,
	                'dir'              ,
	                'userid'           ,
	                'created_datetime' ,
	                'answered_datetime',
	                'hangup_datetime'  ,
	                'hangup_cause'     ,
					'total_timed'	   ,
					'talk_timed'	   ,
					);
	//编辑字段
	public $editFields = Array();

	//可排序字段
	public $orderbyFields = Array(
					'other_number'     ,					
					'queue'            ,
	                'agent_name'       ,
	                'dir'              ,
	                'userid'           ,
	                'uuid'             ,
	                'created_datetime' ,
	                'answered_datetime',
	                'hangup_datetime'  ,
	                'hangup_cause'     ,
					'total_timed'	   ,
					'talk_timed'	   ,
	);
	//默认排序
	public $defaultOrder = Array('created_datetime','DESC');
	//详情入口字段
	public $enteryField = 'other_number';

	//枚举字段值
	public $picklist = Array(
		'dir' => Array('callin','callout'),
		'hangup_cause' => Array('terminated','NORMAL_CLEARING','USER_NOT_REGISTERED','USER_BUSY','ORIGINATOR_CANCEL','NO_ANSWER',
								'NO_USER_RESPONSE','CALL_REJECTED','INCOMPATIBLE_DESTINATION','NORMAL_UNSPECIFIED','UNALLOCATED_NUMBER','INVALID_NUMBER_FORMAT'),
	);
	//字段关联
	public $associateTo = Array(
		'userid' => Array('MODULE','User','detailView','id','user_name'),
	);

	//记录权限关联字段名
    public $shareField = 'userid';
};



?>