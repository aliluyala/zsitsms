<?php
class ListMatchModule extends BaseModule
{
	public $baseTable = 'list_match';
	//模块描述
	public $describe = '名单匹配任务';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'editView' => '编辑',
			'createView' => '新建',
			'copyView' => '复制',
			'save' => '保存',
			'delete' => '删除',
			'import' => '导入',
			'export' => '导出',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
					'name'               => Array('5','S',true,'任务名称','',1,50),
					'batch'              => Array('5','S',true,'名单批次','',1,50),
					'state'              => Array('20','E',true,'状态','RUNING'),					
					'start_time'         => Array('31','DT',true,'任务启动时间'),
					'ins_end_st'         => Array('31','DT',true,'查询保单时段的起始时间'),
					'ins_end_end'        => Array('31','DT',true,'查询保单时段的结束时间'),
					'timed1_start'       => Array('32','T',false,'时段1起点','00:00:00'),
					'timed1_end'         => Array('32','T',false,'时段1终点','23:59:59'),
					'timed2_start'       => Array('32','T',false,'时段2起点'),
					'timed2_end'         => Array('32','T',false,'时段2终点'),
					'timed3_start'       => Array('32','T',false,'时段3起点'),
					'timed3_end'         => Array('32','T',false,'时段3终点'),
					'timed4_start'       => Array('32','T',false,'时段4起点'),
					'timed4_end'         => Array('32','T',false,'时段4终点'),
					'tag'                => Array('20','E',false,'运行标志','EXECUTING'),
					'request_complete'   => Array('20','E',false,'提交完成','NO'),
					'last_time'          => Array('31','DT',false,'最近执行时间'),
					'complete_count'     => Array('6','N',false,'完成数',0,0,999999999),
					'request_count'      => Array('6','N',false,'提交数',0,0,999999999),
					'success_count'      => Array('6','N',false,'成功数',0,0,999999999),
					'failure_count'      => Array('6','N',false,'失败数',0,0,999999999),
					'part_count'         => Array('6','N',false,'部分匹配数',0,0,999999999),
					'error_info'         => Array('5','S',false,'错误信息','',1,100),
					'create_time'        => Array('35','DT',true,'创建时间'),					
					'create_userid'      => Array('51','N',true,'创建人'),
					'modify_time'        => Array('36','DT',false,'修改时间'),
					'modify_userid'      => Array('52','N',false,'修改人'),

					);
	//安全字段,可以控制权限
	public $safeFields = Array(
	                'name'                ,
					'batch'               ,
	                'state'               ,
	                'request_complete'    ,
					'error_info'          ,
	                'start_time'          ,
	                'ins_end_st'          ,
	                'ins_end_end'         ,
	                'timed1_start'        ,
	                'timed1_end'          ,
	                'timed2_start'        ,
	                'timed2_end'          ,
	                'timed3_start'        ,
	                'timed3_end'          ,
	                'timed4_start'        ,
	                'timed4_end'          ,
	                'tag'                 ,
	                'last_time'           ,
					'complete_count'      ,
	                'request_count'       ,
	                'success_count'       ,
	                'failure_count'       ,
	                'part_count'          ,

					);
	//列表字段
	public $listFields = Array(
	                'name'                ,
					'batch'               ,
	                'state'               ,
	                'tag'                 ,
	                'last_time'           ,
	                'request_count'       ,
	                'success_count'       ,
	                'failure_count'       ,
	                'part_count'          ,
					);
	//编辑字段
	public $editFields = Array(
	                'name'                ,
					'batch'               ,
	                'state'               ,
	                'start_time'          ,
	                'ins_end_st'          ,
	                'ins_end_end'         ,
	                'timed1_start'        ,
	                'timed1_end'          ,
	                'timed2_start'        ,
	                'timed2_end'          ,
	                'timed3_start'        ,
	                'timed3_end'          ,
	                'timed4_start'        ,
	                'timed4_end'          ,

					);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
	                'name'                ,
					'batch'               ,
	                'state'               ,
	                'start_time'          ,
					'create_time'         ,       
					'create_userid'       ,     
					'modify_time'         ,       
					'modify_userid'       ,  
					);
	//默认排序
	public $defaultOrder = Array('create_time','ASC');
	//详情入口字段
	public $enteryField = 'name';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	public $blocks = Array('LBL_MATCH_TASK_BASE'=>Array('4',true,Array(
															  'name'                ,
															  'batch'               ,
															  'state'               ,
															  'start_time'          ,
															  'timed1_start'        ,
															  'timed1_end'          ,
															  'timed2_start'        ,
															  'timed2_end'          ,
															  'timed3_start'        ,
															  'timed3_end'          ,
															  'timed4_start'        ,
															  'timed4_end'          ,
															  'ins_end_st'          ,
															  'ins_end_end'         ,															  
											                  )),
						   'LBL_MATCH_TASK_STATE_INFO'=>Array('4',true,Array(	
															 'request_count'       ,
															 'complete_count'      ,
															 'success_count'       ,
															 'failure_count'       ,
															 'part_count'          ,
															 'request_complete'    ,
															 'tag'                 ,
															 'last_time'           ,
															 'error_info'          ,															 
						                                      )),
						   'LBL_RECORD_INFO'=>Array('4',true,Array(															 
															 'create_time'       ,
															 'create_userid'     ,
															 'modify_time'       ,
															 'modify_userid'     ,   
															 )),
									 
						);
						
				
						
						
						
	//枚举字段值
	public $picklist = Array(
		'state' => array(
		                'RUNING'           ,
						'STOP'             ,
		                ),
		'tag' => array(
						'EXECUTING'        ,
						'WAITING'          ,
						'COMPLETE'         ,
						),
		'request_complete' => array(
						'NO'   ,
						'YES'  ,
						),				

	);
	
	//字段关联
	public $associateTo = Array(
		//'user_attach' => Array('MODULE','User','detailView','id','user_name'),		
        'create_userid' => Array('MODULE','User','detailView','id','user_name'),		
		'modify_userid' => Array('MODULE','User','detailView','id','user_name'),
	);
	
	//模块关联
	public $associateBy = Array(

	);
	//记录权限关联字段名
	public $shareField = null;	
	
	//允许批量修改字段		
	public $batchEditFields = Array(
				//'user_attach'      ,		
	
	);	

	//允许miss编辑字段
	public $missEditFields = Array(
	                'name'                ,
					'batch'               ,
	                'state'               ,	                
	                'start_time'          ,
	                'ins_end_st'          ,
	                'ins_end_end'         ,
	                'timed1_start'        ,
	                'timed1_end'          ,
	                'timed2_start'        ,
	                'timed2_end'          ,
	                'timed3_start'        ,
	                'timed3_end'          ,
	                'timed4_start'        ,
	                'timed4_end'          ,
	
	);
};



?>