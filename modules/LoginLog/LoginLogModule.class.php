<?php
class LoginLogModule extends BaseModule
{
	public $baseTable = 'user_login_log';
	//模块描述,子类中必须定义
	public  $describe = '登录日志';
	
	//模块方法,子类中必须定义
	public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'delete' => '删除',			
			'export' => '导出',
			'batchDelete' => '批量删除',
			'modifyFilter' => '编辑过滤',
			);
			
	public $fields = Array(
		'user_name' => Array('1','S',false,'用户名'),
	    'userid' => Array('50','N',false,'用户'),
		'state' => Array('20','E',false,'操作状态'),
	    'oper_time' => Array('31','DT',false,'操作时间'),    
        'ip_address' => Array('1','S',false,'客户端IP'),    
		'user_agent' => Array('1','S',false,'浏览器信息'),       
	);
	public $editFields = Array();
	public $picklist = Array('state' => Array('LOGIN','LOGOUT'));
	public $listFields = Array('user_name','ip_address','oper_time','state');
	public $associateTo = Array(
		'userid' => Array('MODULE','User','detailView','id','user_name'),	
	);	
	public $shareField = 'userid';
}
?>	