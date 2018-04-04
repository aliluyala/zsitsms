<?php
class NoticesModule extends BaseModule
{
	public $baseTable = 'notices';
	//模块描述
	public $describe = '系统公告';
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
			'batchDelete' => '批量删除',
			'modifyFilter' => '编辑过滤',
			'homeInfo' => '首页信息',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'title'               	  =>Array('5','S',true,'公告标题','',1,60),
			'tag'             		  =>Array('22','E',true,'标志','general'),
            'contant'         		  =>Array('10','S',false,'公告内容',''),
			'date_create'             =>Array('35','DT',true,'公告发布时间'),
			'user_create'             =>Array('51','N',true,'公告发布人'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'title'          ,
			'tag'            ,
			'contant'        ,
			'date_create'    ,
			'user_create'    ,			
			);
	//列表字段
	public $listFields = Array(
			'tag'            ,
			'title'          ,
			'date_create'    ,
			'user_create'    ,			
			);
	//编辑字段
	public $editFields = Array(
			'title'          ,
			'tag'            ,
			'contant'        ,
			);
	//miss编辑字段
	public $missEditFields = Array(
			'title'          ,
			'tag'            ,
			'contant'        ,
			);	
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'title'          ,
			'tag'            ,
			'date_create'    ,
			'user_create'    ,			
			);
	//默认排序
	public $defaultOrder = Array('date_create','DESC');
	//详情入口字段
	public $enteryField = 'title';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	public $blocks = Array('LBL_NOTICES_BASE'=>Array('2',true,Array(
																	'title'          ,																	
																	'tag'            ,
																	'date_create'    ,
																	'user_create'    ,	
																	)),
						   'LBL_NOTICES_CONTANT'=>Array('1',true,Array(																	
																	'contant'        ,	
																	)),	
						);
	//枚举字段值
	public $picklist = Array(
			'tag'  =>   Array('general'=>'<img src="public/images/flag_mark_gray.png" style="width:16px;height:16px;margin:3px 0px 0px 3px;"/>',
						  'important'=>'<img src="public/images/flag_mark_yellow.png" style="width:16px;height:16px;margin:3px 0px 0px 3px;"/>',
						  'emergent'=>'<img src="public/images/flag_mark_red.png" style="width:16px;height:16px;margin:3px 0px 0px 3px;"/>',
                          'timely'=>'<img src="public/images/flag_mark_violet.png" style="width:16px;height:16px;margin:3px 0px 0px 3px;"/>',						 
						 ),
	);
	
	//字段关联
	public $associateTo = Array(
		'user_create' => Array('MODULE','User','detailView','id','user_name'),
	);
	//模块关联
	public $associateBy = Array(
		//'associate_loginlog_info' => Array('LoginLog','userid','user_name','ip_address','oper_time','state'),
	);
	
	//记录权限关联字段名
	//public $shareField = '';
};



?>