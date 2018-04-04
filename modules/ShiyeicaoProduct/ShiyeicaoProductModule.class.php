<?php
class ShiyeicaoProductModule extends BaseModule
{
	public $baseTable = 'products';
	//模块描述
	public $describe = '产品管理';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'        => '浏览',
			'detailView'   => '详情',
			'editView'     => '编辑',
			'createView'   => '新建',
			'copyView'     => '复制',
			'save'         => '保存',
			'delete'       => '删除',
			'import'       => '导入',
			'export'       => '导出',
			'batchDelete'  => '批量删除',
			'batchModify'  => '批量修改',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
					'productnames'  => Array('5','S',true,'产品名称'),
					'productcontents'  => Array('9','S',false,'产品名称说明'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
		'productnames',
		'productcontents',
			);
	//列表字段
	public $listFields = Array(
		'productnames',
		'productcontents',
			);
	//编辑字段
	public $editFields = Array(
		'productnames',
		'productcontents',
			);
	//列表最大行数
	public $listMaxRows = 2;
	//可排序字段
	public $orderbyFields = Array(
		'productnames',
		'productcontents',
			);

	//允许批量修改字段
	public $batchEditFields = Array(
		'productnames',
		'productcontents',
			);
	//允许miss编辑字段
	public $missEditFields = Array(
		'productnames',
		'productcontents',
			);
	//默认排序
	public $defaultOrder = Array('productnames','ASC');
	//详情入口字段
	public $enteryField = 'productnames';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array('LBL_ACCOUNT_BASE'=>Array('1',true,Array(
		'productnames',
		'productcontents',
							)),
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
	public $shareField = 'productnames';

	//防止PHP notice
	function _get($str){
		$val = isset($_REQUEST[$str]) ? $_REQUEST[$str] : NULL;
		return $val;
	}




}



?>