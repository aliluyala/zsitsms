<?php
class MyWorkFlowModule extends BaseModule
{
	public $baseTable = '';
	//模块描述
	public $describe = '我的工作';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '我的工作',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
						'name'=>array('5','S',false,'名字'),	
					);
	//安全字段,可以控制权限
	public $safeFields = Array(
						'name'
					);
	//列表字段
	public $listFields = Array(
						'name'
					);
	//编辑字段
	public $editFields = Array(
						'name'
					);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
						'name'
					);
	//默认排序
	public $defaultOrder = Array('name');
	//详情入口字段
	public $enteryField = '';
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
	
 
	);
	
	//模块关联
	public $associateBy = Array(

	);
	//记录权限关联字段名
	public $shareField = '';	
	
	//允许批量修改字段		
	public $batchEditFields = Array(

	);	

	//允许miss编辑字段
	public $missEditFields = Array(

	);
};



?>