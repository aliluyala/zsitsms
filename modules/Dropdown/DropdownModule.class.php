<?php
class DropdownModule extends BaseModule
{
	//模块表
	public $baseTable = 'dropdown';
	//模块描述
	public $describe = '下拉框设置';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '设置',
			'save' => '保存',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'module_name'     => Array('7','S',true,'模块','',0,50,'100px'),   
			'field'           => Array('7','S',true,'字段','',0,50,'100px'),  
			'save_value'      => Array('7','S',true,'保存值','',0,50,'100px'),  
			'show_value'      => Array('7','S',true,'显示值','',0,50,'100px'),  
			'group_name'      => Array('7','S',true,'组名','',0,50,'100px'),  
			);
	//列表字段
	public $listFields = Array('field','module_name','field','group_name','save_value','show_value');

	//编辑字段
	public $editFields = Array('field','module_name','group_name','save_value','show_value');

	
	//详情入口字段
	public $enteryField = 'field';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//模块关联
	public $associateBy = Array(
		
	);
	

	
	
};



?>