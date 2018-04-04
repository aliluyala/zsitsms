<?php
class HomeModule extends BaseModule
{
	public $baseTable = 'homes';
	//模块描述
	public $describe = '首页管理';
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
			'batchDelete' => '批量删除',
			'selfSetting'=> '我的首页'
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'userid'            =>  Array('50','N',true,'首页归属用户'),
			'name'              =>  Array('5','S',true,'首页名称'),
	        'cols'              =>  Array('6','N',true,'首页布局的列数,最多4列','3',1,4),
	        'rows'              =>  Array('6','N',true,'首页布局的行数,最多3行','1',1,3),
			'default_home'      =>  Array('21','E',true,'是否作为所有用户的默认首页','NO'),
	        'cell1_title'       =>  Array('5','S',false,'子框1的标题'),
	        'cell1_url'    	    =>  Array('5','S',false,'子框1的URL'),
	        'cell2_title'       =>  Array('5','S',false,'子框2的标题'),
	        'cell2_url'         =>  Array('5','S',false,'子框2的URL'),
	        'cell3_title'       =>  Array('5','S',false,'子框3的标题'),
	        'cell3_url'         =>  Array('5','S',false,'子框3的URL'),
	        'cell4_title'       =>  Array('5','S',false,'子框4的标题'),
	        'cell4_url'         =>  Array('5','S',false,'子框4的URL'),
	        'cell5_title'       =>  Array('5','S',false,'子框5的标题'),
	        'cell5_url'         =>  Array('5','S',false,'子框5的URL'),
	        'cell6_title'       =>  Array('5','S',false,'子框6的标题'),
	        'cell6_url'         =>  Array('5','S',false,'子框6的URL'),
	        'cell7_title'       =>  Array('5','S',false,'子框7的标题'),
	        'cell7_url'         =>  Array('5','S',false,'子框7的URL'),
	        'cell8_title'       =>  Array('5','S',false,'子框8的标题'),
	        'cell8_url'         =>  Array('5','S',false,'子框8的URL'),
	        'cell9_title'       =>  Array('5','S',false,'子框9的标题'),
	        'cell9_url'         =>  Array('5','S',false,'子框9的URL'),
	        'cell10_title'      =>  Array('5','S',false,'子框10的标题'),
	        'cell10_url'        =>  Array('5','S',false,'子框10的URL'),
	        'cell11_title'      =>  Array('5','S',false,'子框11的标题'),
	        'cell11_url'        =>  Array('5','S',false,'子框11的URL'),
			'cell12_title' 	    =>  Array('5','S',false,'子框12的标题'),	
			'cell12_url'   	    =>  Array('5','S',false,'子框12的URL'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'userid'       ,
			'name'         ,
			'cols'         ,
			'rows'         ,
			'default_home' ,
			'cell1_title'  ,
			'cell1_url'    ,
			'cell2_title'  ,
			'cell2_url'    ,
	        'cell3_title'  ,
	        'cell3_url'    ,
	        'cell4_title'  ,
	        'cell4_url'    ,
	        'cell5_title'  ,
	        'cell5_url'    ,
	        'cell6_title'  ,
	        'cell6_url'    ,
	        'cell7_title'  ,
	        'cell7_url'    ,
	        'cell8_title'  ,
	        'cell8_url'    ,
	        'cell9_title'  ,
	        'cell9_url'    ,
	        'cell10_title' ,
	        'cell10_url'   ,
	        'cell11_title' ,
	        'cell11_url'   ,
	        'cell12_title' ,
	        'cell12_url'   ,
			);
	//列表字段
	public $listFields = Array(
			'name'         ,
			'userid'       ,
			'cols'         ,
			'rows'         ,
			'default_home' ,
			'cell1_title'  ,
			'cell1_url'    ,
			);
	//编辑字段
	public $editFields = Array(
			'userid'       ,
			'name'         ,
			'cols'         ,
			'rows'         ,
			'default_home' ,
			'cell1_title'  ,
			'cell1_url'    ,
			'cell2_title'  ,
			'cell2_url'    ,
	        'cell3_title'  ,
	        'cell3_url'    ,
	        'cell4_title'  ,
	        'cell4_url'    ,
	        'cell5_title'  ,
	        'cell5_url'    ,
	        'cell6_title'  ,
	        'cell6_url'    ,
	        'cell7_title'  ,
	        'cell7_url'    ,
	        'cell8_title'  ,
	        'cell8_url'    ,
	        'cell9_title'  ,
	        'cell9_url'    ,
	        'cell10_title' ,
	        'cell10_url'   ,
	        'cell11_title' ,
	        'cell11_url'   ,
	        'cell12_title' ,
	        'cell12_url'   ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'userid'       ,
			'name'         ,
			'cols'         ,
			'rows'         ,
			'default_home' ,	
			);
	//默认排序
	public $defaultOrder = Array('userid','ASC');
	//详情入口字段
	public $enteryField = 'name';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	public $blocks = Array(
		'LBL_HOME_BASE'=>Array('3',true,Array(
										'userid'       ,
										'name'         ,
										'cols'         ,
										'rows'         ,
										'default_home' ,
		)),
		'LBL_HOME_CELL_SETTING'=>Array('2',true,Array(
										'cell1_title'  ,
										'cell1_url'    ,
										'cell2_title'  ,
										'cell2_url'    ,
										'cell3_title'  ,
										'cell3_url'    ,
										'cell4_title'  ,
										'cell4_url'    ,
										'cell5_title'  ,
										'cell5_url'    ,
										'cell6_title'  ,
										'cell6_url'    ,
										'cell7_title'  ,
										'cell7_url'    ,
										'cell8_title'  ,
										'cell8_url'    ,
										'cell9_title'  ,
										'cell9_url'    ,
										'cell10_title' ,
										'cell10_url'   ,
										'cell11_title' ,
										'cell11_url'   ,
										'cell12_title' ,
										'cell12_url'   ,
		)),	
	
	);
	//枚举字段值
	public $picklist = Array(
		'default_home'=>Array('NO','YES'),	
	);
	
	//字段关联
	public $associateTo = Array(
		'userid' => Array('MODULE','User','detailView','id','user_name'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'userid';
};



?>