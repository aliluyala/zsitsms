<?php
class ContractsModule extends BaseModule
{
	public $baseTable = 'contracts';
	//模块描述
	public $describe = '合同档案管理';
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
			'accountid'               =>Array('50','S',false,'客户名称'),
            'contract_name'           =>Array('5','S',true,'合同名称'),
			'contract_no'             =>Array('5','S',true,'合同编号'),
            'contract_type'           =>Array('20','E',true,'合同分类'),
			'contract_target'         =>Array('5','S',true,'合同的标的'),
			'start_date'              =>Array('30','D',true,'合同生效日期'),
            'end_date'                =>Array('30','S',true,'合同中止日期'),
			'sum_money'               =>Array('6','N',true,'合同总金额(元)',0,0.0,999999999.0),
			'mode_parment'            =>Array('20','E',true,'付款方式'),
			'first_bank_name'         =>Array('5','S',false,'甲方开户银行'),
			'first_bank_account'      =>Array('5','S',false,'甲方银行帐号'),
			'second_bank_name'        =>Array('5','S',false,'乙方开户银行'),
			'second_bank_account'     =>Array('5','S',false,'乙方银行帐号'),
			'first_party'             =>Array('5','S',true,'甲方名称'),
			'first_deputy'            =>Array('5','S',true,'甲方代表'),
			'second_party'            =>Array('5','S',true,'乙方名称'),
			'second_deputy'           =>Array('5','S',true,'乙方代表'),
			'third_party'             =>Array('5','S',false,'丙方名称'),
			'third_deputy'            =>Array('5','S',false,'丙方代表'),
			'summary'                 =>Array('10','S',true,'合同内容摘要'),
			'date_signing'            =>Array('30','D',true,'签约日期(YYYY-mm-dd)'),
			'remark'                  =>Array('9','S',false,'备注信息'),
			'user_attach'             =>Array('55','N',true,'记录归属于组或用户，将决定其它用户访问此记录的权限'),
			'date_create'             =>Array('35','DT',true,'记录创建的时间'),
			'user_create'             =>Array('51','N',true,'创建记录的操作员'),         
			'date_modify'             =>Array('36','DT',false,'最后一次修改记录的时间'),
			'user_modify'             =>Array('52','N',false,'最后一次修改记录的操作员'),
			);
	//安全字段,可以控制权限
	public $safeFields = Array(
			'accountid'          ,
			'contract_name'      ,
			'contract_no'        ,
			'contract_type'      ,
			'contract_target'    ,
			'start_date'         ,
			'end_date'           ,
			'sum_money'          ,
			'mode_parment'       ,
			'first_bank_name'    ,
			'first_bank_account' ,
			'second_bank_name'   ,
			'second_bank_account',
			'first_party'        ,
			'first_deputy'       ,
			'second_party'       ,
			'second_deputy'      ,
			'third_party'        ,
			'third_deputy'       ,
			'summary'            ,
			'date_signing'       ,
			'remark'             ,
			'user_attach'        ,
			'date_create'        ,
			'user_create'        ,
			'date_modify'        ,
			'user_modify'        ,			
			);
	//列表字段
	public $listFields = Array(
			'contract_no'        ,
			'contract_name'      ,			
			'contract_type'      ,
			'contract_target'    ,
			'start_date'         ,
			'end_date'           ,
			'sum_money'          ,
			'date_signing'       ,
			);
	//编辑字段
	public $editFields = Array(
			'accountid'          ,
			'contract_name'      ,
			'contract_no'        ,
			'contract_type'      ,
			'contract_target'    ,
			'start_date'         ,
			'end_date'           ,
			'sum_money'          ,
			'mode_parment'       ,
			'first_bank_name'    ,
			'first_bank_account' ,
			'second_bank_name'   ,
			'second_bank_account',
			'first_party'        ,
			'first_deputy'       ,
			'second_party'       ,
			'second_deputy'      ,
			'third_party'        ,
			'third_deputy'       ,
			'summary'            ,
			'date_signing'       ,
			'remark'             ,
			'user_attach'        ,			
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'accountid'          ,
			'contract_name'      ,
			'contract_no'        ,
			'contract_type'      ,
			'start_date'         ,
			'end_date'           ,
			'sum_money'          ,
			'mode_parment'       ,
			'date_signing'		 ,
			'user_attach' 		 ,
			'date_create' 		 ,
			'user_create' 		 ,
			'date_modify' 		 ,
			'user_modify' 		 ,	
			);
	//默认排序
	public $defaultOrder = Array('contract_no','ASC');
	//详情入口字段
	public $enteryField = 'contract_no';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	public $blocks = Array('LBL_CONTRACT_BASE'=>Array('4',true,Array(
																	'contract_no'        ,																	
																	'contract_name'      ,
																	'accountid'          ,
																	'first_party'        ,																	
																	'second_party'       ,																	
																	'third_party'        ,
																	'first_deputy'       ,
																	'second_deputy'      ,
																	'third_deputy'       ,
																	'contract_type'      ,
																	'contract_target'    ,
																	'sum_money'          ,
																	'start_date'         ,
																	'end_date'           ,																	
																	'date_signing'       ,
																	'mode_parment'       ,
																	'date_create'        ,
																	'date_modify'        ,
																	'user_attach' 		 ,																	
																	'user_create'        ,																	
																	'user_modify'        ,
																	)),
						   'LBL_CONTRACT_BANKINFO'=>Array('4',true,Array(
																	'first_bank_name'    ,
																	'first_bank_account' ,
																	'second_bank_name'   ,
																	'second_bank_account',
																	)),	
						   'LBL_CONTRACT_SUMMARY'=>Array('1',true,Array(
																	'summary'            ,																	
																	)),
						   'LBL_CONTRACT_REMARK'=>Array('1',true,Array('remark')),												
						);
	//枚举字段值
	public $picklist = Array(
		'contract_type' => Array('service','sale'),
		'mode_parment'  =>   Array('once_cash','once_transfer','monthly_cash','monthly_transfer'),
	);
	
	//字段关联
	public $associateTo = Array(
		'user_create' => Array('MODULE','User','detailView','id','user_name'),
		'user_modify' => Array('MODULE','User','detailView','id','user_name'),    
		'accountid'   => Array('MODULE','Accounts','detailView','id','account_name'),
	);
	//模块关联
	public $associateBy = Array(
		'associate_contract_income_info' => Array('ContractIncome','contractid','flow_no','money','payee','payee_time','payer','payer_time'),
	);
	//记录权限关联字段名
	public $shareField = 'user_attach';

};



?>