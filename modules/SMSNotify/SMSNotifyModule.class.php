<?php
class  SMSNotifyModule extends BaseModule
{
	public $baseTable = 'sms_notify';
	//模块描述
	public $describe = '短信通知';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'        => '浏览',
			'detailView'   => '详情',
			'delete'       => '删除',
			'export'       => '导出',
			'batchDelete'  => '批量删除',
			'modifyFilter' => '编辑过滤',
			'sendSMS'      => '短信发送',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
					'userid'    			=> Array('50','N',true,'用户'),
					'callerid'  			=> Array('5','S',false,'发送号码'),
					'calleeid'  			=> Array('5','S',true,'接收号码'),
					'dir'       			=> Array('20','E',false,'方向'),
					'msgid'     			=> Array('3','N',false,'消息ID'),
					'state'     			=> Array('20','E',false,'状态'),
					'errmsg'    			=> Array('5','S',false,'错误信息'),
					'send_time' 			=> Array('31','DT',false,'发送时间'),
					'content'   			=> Array('9','S',false,'短信内容','',20),
					'self_define_content'   => Array('9','S',false,'自定义内容','',10),
					);
	//安全字段,可以控制权限
	public $safeFields = Array('userid','callerid','calleeid','dir','msgid','send_time','errmsg','content','state');
	//列表字段
	public $listFields = Array('calleeid','callerid','dir','send_time','content','state');
	//编辑字段
	public $editFields = Array('userid','callerid','calleeid','dir','msgid','send_time','content','state');
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array('userid','callerid','calleeid','dir','msgid','state','send_time');
	//默认排序
	public $defaultOrder = Array('send_time','DESC');
	//详情入口字段
	public $enteryField = 'calleeid';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	//分栏定义
	public $blocks = Array('LBL_SMS_NOTIFY_BASE_INFO'=>Array('3',true,Array('userid','callerid','calleeid','dir','msgid','send_time','state','errmsg')),
						   'LBL_SMS_NOTIFY_CONTENT'=>Array('1',true,Array('content')));
	//枚举字段值
	public $picklist = Array(
		'dir'   => Array('send','receive'),
		'state' => Array('wait','success','failure'),
	);

	//字段关联
	public $associateTo = Array(
		'userid' => Array('MODULE','User','detailView','id','name'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'userid';



};



?>