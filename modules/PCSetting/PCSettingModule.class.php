<?php
class PCSettingModule extends BaseModule
{
	//模块表
	public $baseTable = 'policy_calculate_setting';
	//模块描述
	public $describe = '保单算价器设置';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index' => '设置',
			'save' => '保存',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
			'setting'     => Array('5','S',true,'设置信息','',0,65535,'100px'),
			'groupid'     => Array('50','N',false,'组','-1'),
			);
	//列表字段
	public $listFields = Array('setting','groupid');

	//编辑字段
	public $editFields = Array('setting','groupid');


	//详情入口字段
	public $enteryField = 'setting';
	//详细/编辑视图默认列数
	public $defaultColumns = 1;

	//分栏定义
	public $blocks = Array(

		);
	//字段关联
	public $associateTo = Array(

	);

	//模块关联
	public $associateBy = Array(

	);
}
?>