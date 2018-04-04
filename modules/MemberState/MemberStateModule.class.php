<?php
class  MemberStateModule extends BaseModule
{
	public $baseTable = 'zswitch_cc_member_state';
	//模块描述
	public $describe = '呼叫中心队列成员状态';
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
					'queue'             => Array('5','S',true,'队列名字'),
					'uuid'              => Array('5','S',true,'UUID'),
					'caller_number'     => Array('6','N',true,'主叫号码'),
					'joined_time'       => Array('6','N',true,'呼入时间'),
					'bridge_time'       => Array('6','N',true,'通话时间'),
					'agent_name'        => Array('6','N',true,'座席'),
					'state'             => Array('20','E',true,'状态'),  
					);
	//安全字段,可以控制权限
	public $safeFields = Array(
					'queue'                  ,
					'uuid'                   ,
					'caller_number'          ,
					'joined_time'            ,
					'bridge_time'            ,
					'agent_name'             ,
					'state'                  ,
					);	
	//列表字段
	public $listFields = Array(
					'caller_number'          ,
					'queue'                  ,
					'joined_time'            ,
					'bridge_time'            ,
					'agent_name'             ,
					'state'                  ,
					);
	//编辑字段
	public $editFields = Array();

	//可排序字段
	public $orderbyFields = Array(
					'queue'                  ,
					'uuid'                   ,
					'caller_number'          ,
					'joined_time'            ,
					'bridge_time'            ,
					'agent_name'             ,
					'state'                  ,
					);
	//默认排序
	public $defaultOrder = Array('caller_number','ASC');
	//详情入口字段
	public $enteryField = 'caller_number';

	//枚举字段值
	public $picklist = Array(
		'state' => Array('waiting','trying','answered'),
	);
	

	
};



?>