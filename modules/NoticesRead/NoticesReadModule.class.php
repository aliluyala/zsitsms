<?php
class NoticesReadModule extends BaseModule
{
	public $baseTable = 'notices_read';
	//模块描述
	public $describe = '即时消息阅读记录';
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
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'notice_id'                      =>Array('50','S',false,'消息标题'),         
			'create_time' 			         =>Array('35','DT',true,'创建时间'),
			'create_user'                    =>Array('51','DT',true,'创建者'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'notice_id'            ,
			);
	//列表字段
	public $listFields = Array(
	        'notice_id'              ,
			'create_time'            , 
			'create_user'            ,  
			);
	//编辑字段
	public $editFields = Array( 
	        'notice_id'            ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'notice_id'            ,
			);
	//默认排序
	public $defaultOrder = Array('notice_id','ASC');
	//详情入口字段
	public $enteryField = 'notice_id';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	public $blocks = Array(
						 											
						);
	//枚举字段值
	public $picklist = Array(
		
	);
	
	//字段关联
	public $associateTo = Array(
		'notice_id' => Array('MODULE','Notices','detailView','id','title'),
		'create_user' => Array('MODULE','User','detailView','id','user_name'),	
	);
	//模块关联
	public $associateBy = Array(
		
	);
	//记录权限关联字段名
	//public $shareField = '';
    
  

}
?>