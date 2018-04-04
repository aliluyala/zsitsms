<?php
class LevelModule extends BaseModule
{
	public $baseTable = 'level';
	//模块描述
	public $describe = '客户风险等级管理';
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
					'levelname'  => Array('5','S',true,'客户'),
					'levelcontent'  => Array('9','S',false,'客户风险等级说明'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
		'levelname',
		'levelcontent',
			);
	//列表字段
	public $listFields = Array(
		'levelname',
		'levelcontent',
			);
	//编辑字段
	public $editFields = Array(
		'levelname',
		'levelcontent',
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
		'levelname',
		'levelcontent',
			);

	//允许批量修改字段
	public $batchEditFields = Array(
		'levelname',
		'levelcontent',
			);
	//允许miss编辑字段
	public $missEditFields = Array(
		'levelname',
		'levelcontent',
			);
	//默认排序
	public $defaultOrder = Array('levelname','ASC');
	//详情入口字段
	public $enteryField = 'levelname';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array('LBL_ACCOUNT_BASE'=>Array('1',true,Array(
		'levelname',
		'levelcontent',
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
	public $shareField = 'levelname';

	//防止PHP notice
	function _get($str){
		$val = isset($_REQUEST[$str]) ? $_REQUEST[$str] : NULL;
		return $val;
	}


	public function insertOneRecordset($id,$data,$isImport = FALSE){
		global $APP_ADODB,$CURRENT_USER_ID;
		parent::insertOneRecordset($id,$data);
		return $APP_ADODB->Affected_Rows();
	}

		public function updateOneRecordset($id,$userids,$groupids,$data,$isImport = FALSE){
		global $APP_ADODB;
		parent::updateOneRecordset($id,$userids,$groupids,$data);
		return $APP_ADODB->Affected_Rows();
	}





};



?>