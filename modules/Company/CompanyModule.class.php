<?php
class CompanyModule extends BaseModule
{
	public $baseTable = 'company';
	//模块描述
	public $describe = '保险公司';
	//需要控制访问权限的模块方法
    public $actions = array(
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
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = array(
			'name'            => array('5','S',true,'保险公司'),
			'description'     => array('9','S',false,'简介'),
			'user_attach'     => array('55','N',true,'记录归属于组或用户，将决定其它用户访问此记录的权限'),
			'user_create'     => array('51','N',true,'创建记录的操作员'),
			'user_modify'     => array('52','N',false,'最后一次修改记录的操作员'),
			'date_create'     => array('35','DT',true,'记录创建的时间'),
			'date_modify'     => array('36','DT',false,'最后一次修改记录的时间'),
			);
	//安全字段,可以控制权限
	public $safeFields = array(
			'name'       ,
			'description',
			'user_attach',
			'user_create',
			'user_modify',
			'date_create',
			'date_modify',
			);
	//列表字段
	public $listFields = array(
			'name'		 ,
			'user_attach',
			'user_create',
			'user_modify',
			'date_create',
			'date_modify',
			);
	//编辑字段
	public $editFields = array(
			'name'       ,
			'description',
			'user_attach',
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = array(
			'name'		 ,
			'user_attach',
			'user_create',
			'user_modify',
			'date_create',
			'date_modify',
			);
	//默认排序
	public $defaultOrder = array('name','ASC');
	//详情入口字段
	public $enteryField = 'name';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array('LBL_INSURANCE_BASE'=>Array('1',true,Array(
																	'name'              ,
																	)),
						   'LBL_INSURANCE_REMARK'=>Array('1',true,Array(
																	'description'       ,
																	)),
						   'LBL_INSURANCE_ADDR'=>Array('1',true,Array(
																	'user_attach'       ,
																	)),
						);
	//枚举字段值
	public $picklist = array();

	//字段关联
	public $associateTo = array();
	//模块关联
	public $associateBy = array();
	//记录权限关联字段名
	public $shareField = 'user_attach';

};



?>