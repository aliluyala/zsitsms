<?php
class  AgentStateModule extends BaseModule
{
	public $baseTable = 'zswitch_cc_agent_state';
	//模块描述
	public $describe = '呼叫中心座席状态';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',   
			'detailView' => '详情',		
			'export' => '导出',
			'modifyFilter' => '编辑过滤',
			'spy' => '监听',
			'callout' => '呼出',
			'hangup' => '挂断',
			'activeAgent' => '激活',
			'breakAgent' => '阻塞',
			'killAgent' => '踢出',
			'transfer' => '转接',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')

	public $fields = Array(
					'userid'                      => Array('50','N',true,'使用些座席用户的登录名'),
					'workno'                      => Array('5','s',true,'用户的工号'),
					'name'                        => Array('5','S',true,'座席名(座席号码)'),
					//'contact'                     => Array('5','N',true,'内部联接串'),
					'status'                      => Array('20','E',true,'工作状态'),
					'state'                       => Array('20','E',true,'通话状态'),
					'queue'                       => Array('5','S',true,'最近呼入队列'),
					'uuid'                        => Array('5','S',true,'最近通话UUID'),
					'other_uuid'                  => Array('5','S',true,'最近通话b脚UUID'),
                    'other_number'                => Array('5','S',true,'最近通话对方号码'),
                    'dir'                         => Array('20','E',true,'最近通话的呼叫方向'),
                    'start_time'                  => Array('31','DT',true,'最近呼叫开始时间'),
                    'answer_time'                 => Array('31','DT',true,'最近呼叫应答时间'),
					'hangup_time'                 => Array('31','DT',true,'最近呼叫挂机时间'),
					'hangup_case'                 => Array('20','E',true,'最近呼叫挂机原因'),
                    'total_callins_answered'      => Array('6','N',true,'累计呼入应答次数'),
                    'total_callins_no_answer'     => Array('6','N',true,'累计呼入未应答次数'),
                    'today_callins_answered'      => Array('6','N',true,'今日呼入应答次数'),
                    'today_callins_no_answer'     => Array('6','N',true,'今日呼入未应答次数'),
                    'total_callin_talk_time'      => Array('6','N',true,'累计呼入通话时长(秒)'),
                    'today_callin_talk_time'      => Array('6','N',true,'今日呼入通话时长(秒)'),
                    'total_callouts_answered'     => Array('6','N',true,'累计呼出应答次数'),
                    'total_callouts_no_answer'    => Array('6','N',true,'累计呼出未应答次数'),
                    'today_callouts_answered'     => Array('6','N',true,'今日呼出应答次数'),
                    'today_callouts_no_answer'    => Array('6','N',true,'今日呼出未应答次数'),
                    'total_callout_talk_time'     => Array('6','N',true,'累计呼出通话时长(秒)'),
                    'today_callout_talk_time'     => Array('6','N',true,'今日呼出通话时长(秒)'),
                    'total_calls'                 => Array('6','N',true,'累计呼叫次数'),
                    'today_calls'                 => Array('6','N',true,'今日呼叫次数'),
                    'total_talk_time'             => Array('6','N',true,'累计通话时长(秒)'),
                    'today_talk_time'             => Array('6','N',true,'今日通话时长(秒)'),

					);
	//安全字段,可以控制权限
	public $safeFields = Array(
					'userid'                   ,
					'workno'                   ,
					'name'                     ,
					//'contact'                  ,
					'status'                   ,
					'state'                    ,
					'queue'                    ,
					'uuid'                     ,
					'other_uuid'               ,
					'other_number'             ,
					'dir'                      ,
					'start_time'               ,
					'answer_time'              ,
					'total_callins_answered'   ,
					'total_callins_no_answer'  ,
					'today_callins_answered'   ,
					'today_callins_no_answer'  ,
					'total_callin_talk_time'   ,
					'today_callin_talk_time'   ,
					'total_callouts_answered'  ,
					'total_callouts_no_answer' ,
					'today_callouts_answered'  ,
					'today_callouts_no_answer' ,
					'total_callout_talk_time'  ,
					'today_callout_talk_time'  ,
					'total_calls'              ,
					'today_calls'              ,
					'total_talk_time'          ,
					'today_talk_time'          ,
					);	
	//列表字段
	public $listFields = Array(
					'name'                     ,
					'queue'		               ,
					'userid'                   ,
					'workno'                   ,  
					'dir'                      ,
					'status'                   ,
					'state'                    ,
					'other_number' 			   ,
					'start_time'               ,
					'answer_time'              ,
					'total_calls'              ,
					'today_calls'              ,
					);                         
	//编辑字段
	public $editFields = Array();
	
	//可排序字段
	public $orderbyFields = Array(
					'name'                     ,
					'queue'		               ,
					'userid'                   ,
					'workno'                   ,  
					'dir'                      ,
					'status'                   ,
					'state'                    ,
					'other_number' 			   ,
					'start_time'               ,
					'answer_time'              ,
					'total_calls'              ,
					'today_calls'              ,
					);
	//默认排序
	public $defaultOrder = Array('name','ASC');
	//详情入口字段
	public $enteryField = 'name';
	//分栏定义
	public $blocks = Array('LBL_AGENT_STATE_INFO'=>Array('3',true,Array(																	
																	'name'                     ,
																	'userid'                   ,
																	'workno'                   ,  
																	'queue'                    ,
																	//'contact'                  ,
																	'status'                   ,
																	'state'                    ,																	
																	'uuid'                     ,
																	'other_uuid'               ,
																	'other_number'             ,
																	'dir'                      ,
																	'start_time'               ,
																	'answer_time'              ,
																	)),
						   'LBL_AGENT_STATISTICS_INFO'=>Array('3',true,Array(																	
																	'total_callins_answered'   ,
																	'total_callins_no_answer'  ,
																	'today_callins_answered'   ,
																	'today_callins_no_answer'  ,
																	'total_callin_talk_time'   ,
																	'today_callin_talk_time'   ,
																	'total_callouts_answered'  ,
																	'total_callouts_no_answer' ,
																	'today_callouts_answered'  ,
																	'today_callouts_no_answer' ,
																	'total_callout_talk_time'  ,
																	'today_callout_talk_time'  ,
																	'total_calls'              ,
																	'today_calls'              ,
																	'total_talk_time'          ,
																	'today_talk_time'          ,
																	)),	
						);
	
	
	//枚举字段值
	public $picklist = Array(
		'state'       => Array('Waiting','callin_ringing','callin_talking','callout_ringing','callout_talking'),
		'status'      => Array('Logged Out','Available','Available (On Demand)','On Break'),
		'dir'         => Array('callin','callout'),
		'hangup_case' => Array(),
	);
	//字段关联
	public $associateTo = Array(
		'userid' => Array('MODULE','User','detailView','id','user_name'),
	);
	//记录权限关联字段名
	public $shareField = 'userid';
	
};



?>