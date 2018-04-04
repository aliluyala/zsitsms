<?php
class  ResultReportModule extends BaseModule
{
	public $baseTable = 'result_report';
	//模块描述
	public $describe = '销售说明';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'      => '浏览',
			'detailView' => '详情',
			'editView'   => '编辑',
			'createView' => '新建',
			'copyView'   => '复制',
			'save'       => '保存',
			'delete'     => '删除',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
					'status' => Array('20','E',true,'销售结果'),
					'report' => Array('5','S',true,'销售说明'),
					);
	//安全字段,可以控制权限
	public $safeFields = Array('status','report');
	//列表字段
	public $listFields = Array('report','status');
	//编辑字段
	public $editFields = Array('status','report');
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array('status','report');
	//默认排序
	public $defaultOrder = Array('status','ASC');
	//详情入口字段
	public $enteryField = 'report';
	//详细/编辑视图默认列数
	public $defaultColumns = 1;
	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = Array(
		'status'=>Array('FAILED','INVALID','APPOINTMENT_QUOTATION','APPOINTMENT_NON_QUOTATION','SUCCESS'),

	);

	//字段关联
	public $associateTo = Array();

	//模块关联
	public $associateBy = Array();


};



?>