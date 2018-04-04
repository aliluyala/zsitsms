<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$APP_ADODB->Execute("SET NAMES utf8;");
class ReceivableRecordModule extends BaseModule
{
	public $baseTable = 'receivable_record';

	//模块描述
	public $describe = '收款记录';
	//需要控制访问权限的模块方法
    public $actions = Array(
			'index'        => '浏览',
			'detailView'   => '详情',
			'delete'       => '删除',
			'export'       => '导出',
			'batchDelete'  => '批量删除',
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
	       'order_no'            => Array('5','S',false,'订单号  ',''),
	       'receivabler'         => Array('5','S',false,'收款人  ',''),
	       'receivable_amount'   => Array('5','S',false,'收款金额',''),
	       'receivable_time'     => Array('5','S',false,'收款时间',''),
	       'receivable_type'     => Array('5','S',false,'收款方式',''),
	       'receivable_bank'     => Array('5','S',false,'收款银行',''),
	       'receivable_account'  => Array('5','S',false,'收款帐号',''),
	       'pay_bank'            => Array('5','S',false,'付款银行',''),
	       'pay_account'         => Array('5','S',false,'付款帐号',''),
	       'pay_user'            => Array('5','S',false,'付款人  ',''),

			
			);
			
	//安全字段,可以控制权限
	public $safeFields = Array(
			'order_no'          ,
			'receivabler'       ,
			'receivable_amount' ,
			'receivable_time'   ,
			'receivable_type'   ,
			'receivable_bank'   ,
			'receivable_account',
			'pay_bank'          ,
			'pay_account'       ,
			'pay_user'          ,

	
			);
	//列表字段
	public $listFields = Array(
			'order_no'          ,
			'receivabler'       ,
			'receivable_amount' ,
			'receivable_time'   ,
			'receivable_type'   ,
			'receivable_bank'   ,
			'receivable_account',
			'pay_bank'          ,
			'pay_account'       ,
			'pay_user'          ,
			);
	//编辑字段
	public $editFields = Array(
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'order_no'          ,
			'receivabler'       ,
			'receivable_amount' ,
			'receivable_time'   ,
			'receivable_type'   ,
			'receivable_bank'   ,
			'receivable_account',
			'pay_bank'          ,
			'pay_account'       ,
			'pay_user'          ,
			);

	//允许批量修改字段
	public $batchEditFields = Array();
	//允许miss编辑字段
	public $missEditFields = Array(
			);
	//默认排序
	public $defaultOrder = Array('receivable_time' ,'DESC');
	//详情入口字段
	public $enteryField = 'order_no' ;
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
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = '';


};



?>