<?php
class ContractIncomeModule extends BaseModule
{
	public $baseTable = 'contract_income';
	//模块描述
	public $describe = '合同收款记录';
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
			'flow_no'                 =>Array('17','S',true,'收款流水号','CI'),
			'contractid'       		  =>Array('50','S',true,'合同名称'),
            'money'            		  =>Array('6','N',true,'收款金额(元)',0,0.0,999999999.0),
			'mode_payment'     		  =>Array('20','E',true,'付款方式'),
            'payee'            		  =>Array('5','S',true,'收款人'),
			'payee_bank'       		  =>Array('5','S',false,'收款银行'),
			'payee_account'    		  =>Array('5','S',false,'收款银行帐号'),
            'payee_time'       		  =>Array('31','DT',true,'到帐时间'),
			'payer'            		  =>Array('5','S',true,'付款人'),
			'payer_bank'       		  =>Array('5','S',false,'付款银行'),
			'payer_account'    		  =>Array('5','S',false,'付款银行帐号'),
			'payer_time'       		  =>Array('31','DT',true,'付款时间'),			   
			'user_attach'      		  =>Array('55','N',true,'记录归属于组或用户，将决定其它用户访问此记录的权限'),
			'date_create'             =>Array('35','DT',true,'记录创建的时间'),
			'user_create'             =>Array('51','N',true,'创建记录的操作员'),         
			'date_modify'             =>Array('36','DT',false,'最后一次修改记录的时间'),
			'user_modify'             =>Array('52','N',false,'最后一次修改记录的操作员'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'flow_no'        ,
			'contractid'     ,
			'money'          ,
			'mode_payment'   ,
			'payee'          ,
			'payee_bank'     ,
			'payee_account'  ,
			'payee_time'     ,
			'payer'          ,
			'payer_bank'     ,
			'payer_account'  ,
			'payer_time'     ,
			'user_attach'    ,
			'date_create'    ,
			'user_create'    ,
			'date_modify'    ,
			'user_modify'    ,
			);
	//列表字段
	public $listFields = Array(
			'flow_no'        ,
			'contractid'     ,
			'money'          ,
			'mode_payment'   ,
			'payee'          ,
			'payee_time'     ,
			'payer'          ,
			'payer_time'     ,
			'user_attach'    ,
			);
	//编辑字段
	public $editFields = Array(
			'flow_no'       ,
			'contractid'    ,
			'money'         ,
			'mode_payment'  ,
			'payee'         ,
			'payee_bank'    ,
			'payee_account' ,
			'payee_time'    ,
			'payer'         ,
			'payer_bank'    ,
			'payer_account' ,
			'payer_time'    ,
			'user_attach'   ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'flow_no'       ,
			'contractid'    ,
			'money'         ,
			'mode_payment'  ,
			'payee'         ,
			'payee_time'    ,
			'payer'         ,
			'payer_time'    ,
			'user_attach' 	,
			'date_create' 	,
			'user_create' 	,
			'date_modify' 	,
			'user_modify' 	,	
			);
	//默认排序
	public $defaultOrder = Array('flow_no','ASC');
	//详情入口字段
	public $enteryField = 'flow_no';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	public $blocks = Array('LBL_CONTRACT_INCOME_INFO'=>Array('3',true,Array(
																	'flow_no'       ,																	
																	'contractid'    ,
																	'money'         ,
																	'mode_payment'  ,																	
																	'payee'         ,																	
																	'payee_bank'    ,
																	'payee_account' ,
																	'payee_time'    ,
																	'payer'         ,
																	'payer_bank'    ,
																	'payer_account' ,
																	'payer_time'    ,
																	'user_attach' 	,			
																	)),
						   'LBL_RECORDSET_INFO'=>Array('2',true,Array(																	
																	'date_create' 	,
																	'user_create' 	,
																	'date_modify' 	,
																	'user_modify' 	,
																	)),	
						);
	//枚举字段值
	public $picklist = Array(
		'mode_payment'  =>   Array('cash','transfer'),
	);
	
	//字段关联
	public $associateTo = Array(
		'user_create' => Array('MODULE','User','detailView','id','user_name'),
		'user_modify' => Array('MODULE','User','detailView','id','user_name'),    
		'contractid'   => Array('MODULE','Contracts','detailView','id','contract_name'),
	);
	//模块关联
	public $associateBy = Array(
		//'associate_loginlog_info' => Array('LoginLog','userid','user_name','ip_address','oper_time','state'),
	);
	
	//记录权限关联字段名
	public $shareField = 'user_attach';
};



?>