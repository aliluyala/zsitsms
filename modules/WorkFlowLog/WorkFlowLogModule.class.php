<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$APP_ADODB->Execute("SET NAMES utf8;");
class WorkFlowLogModule extends BaseModule
{
	public $baseTable = 'workflow_log';
	//模块描述,子类中必须定义
	public  $describe = '流程日志';

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
		 'process'              => Array('5','S',false,'流程'),
		 'task'                 => Array('5','S',false,'任务'),
		 'cindex'               => Array('8','N',false,'序号'),
		 'case_id'              => Array('8','N',false,'流水号'),
		 'case_title'           => Array('5','S',false,'标题'),
		 'start_time'           => Array('31','DT',false,'开始时间'),
		 'end_time'             => Array('31','DT',false,'完成时间'),
		 'delegated_user'       => Array('5','S',false,'处理人'),
		 'delegated_user_name'  => Array('5','S',false,'处理人姓名'),
		 'operation'            => Array('5','S',false,'操作'),
		 'suggestion'           => Array('9','S',false,'意见'),
     	);
	//分栏定义
	public $blocks = Array(
	    'LBL_WORKFLOWLOG_BASE'=>Array('4',true,Array(
	              'process'             ,
	              'task'                ,
	              'cindex'              ,
	              'case_id'             ,
	              'case_title'          ,
	              'start_time'          ,
	              'end_time'            ,
	              'delegated_user'      ,
	              'delegated_user_name' ,
	              'operation'           ,
		          )),
		'LBL_WORKFLOWLOG_SUGGESTION'=>Array('1',true,Array(
			      'suggestion',
                  )),
		);
	public $editFields = Array();
	//详情入口字段
	public $enteryField = 'case_id';
	//可排序字段
	public $orderbyFields = Array('case_id' ,'cindex' ,'process','task' ,'start_time','end_time','delegated_user_name','delegated_user' );

	public $picklist = Array();
	public $listFields = Array('case_id' ,'cindex' ,'process','task' ,'start_time','end_time','delegated_user_name','suggestion');
	public $associateTo = Array();
	//public $shareField = '';
}
?>