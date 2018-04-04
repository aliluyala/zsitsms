<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$APP_ADODB->Execute("SET NAMES utf8;");
class InsuranceOrderModule extends BaseModule
{
	public $baseTable = 'insurance_order';

	//模块描述
	public $describe = '保险订单管理';
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
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(
	        'order_no'=>                   Array('5','S',false,'订单号',''),
			'holder'=>                     Array('5','S',false,'投保人',''),
			'license_no'=>                 Array('5','S',false,'车牌号码',''),
			'vin_no'=>                     Array('5','S',false,'车辆识别码',''),
			'business_policy_no'=>         Array('5','S',false,'商业险保单号',''),
			'business_standard_premium'=>  Array('5','S',false,'商业险标准保费',''),
			'business_discount'=>          Array('5','S',false,'商业险折扣',''),
			'business_premium'=>           Array('5','S',false,'商业险折后保费',''),
			'business_end_time'=>          Array('5','S',false,'商业险结束时间',''),
			'business_start_time'=>        Array('5','S',false,'商业险生效时间',''),
			'mvtalci_policy_no'=>          Array('5','S',false,'交强险保单号',''),
			'mvtalci_standard_premium'=>   Array('5','S',false,'交强险标准保费',''),
			'mvtalci_discount'=>           Array('5','S',false,'交强险折扣',''),
			'mvtalci_premium'=>            Array('5','S',false,'交强险折后保费',''),
			'mvtalci_end_time'=>           Array('5','S',false,'交强险结束时间',''),
			'mvtalci_start_time'=>         Array('5','S',false,'交强险生效时间',''),
			'travel_tax_premium'=>         Array('5','S',false,'车船税',''),
			'total_premium'=>              Array('5','S',false,'折后总费用',''),
			'total_receivable_amount'=>    Array('5','S',false,'实收款合计',''),
			'receiver'=>                   Array('5','S',false,'收件人',''),
			'receiver_mobile'=>            Array('5','S',false,'联系电话',''),
			'receiver_addr'=>              Array('5','S',false,'收件地址',''),
			'case_id'=>                    Array('5','S',false,'流程流水号',''),
			'status'=>                     Array('5','S',false,'状态',''),
			'gift'=>                       Array('9','S',false,'礼品',''),
			'remarks'=>                    Array('9','S',false,'备注',''),
			'levelrisk'=>                  Array('5','S',false,'风险级别',''),
			'auditor'=>                    Array('5','S',false,'审核人',''),
			'auditor_levelrisk'=>          Array('5','S',false,'核定风险级别',''),
			'complete_time'=>              Array('31','S',false,'订单完成时间',''),
			'create_time'=>                Array('31','S',false,'创建时间',''),
			'create_userid'=>              Array('50','S',false,'创建人',''),
			'create_user'=>                Array('5','S',false,'业务员',''),
			'insurance_company'         => Array('5','S',false,'保险公司',''),
			
			);
			
	//安全字段,可以控制权限
	public $safeFields = Array(
			'order_no'                    ,
			'holder'                      ,
			'license_no'                  ,
			'vin_no'                      ,
			'business_policy_no'          ,
			'business_standard_premium'   ,
			'business_discount'           ,
			'business_premium'            ,
			'business_end_time'           ,
			'business_start_time'         ,
			'mvtalci_policy_no'           ,
			'mvtalci_standard_premium'    ,
			'mvtalci_discount'            ,
			'mvtalci_premium'             ,
			'mvtalci_end_time'            ,
			'mvtalci_start_time'          ,
			'travel_tax_premium'          ,
			'total_premium'               ,
			'total_receivable_amount'     ,
			'receiver'                    ,
			'receiver_mobile'             ,
			'receiver_addr'               ,
			'case_id'                     ,
			'status'                      ,
			'gift'                        ,
			'remarks'                     ,
			'levelrisk'                   ,
			'auditor'                     ,
			'auditor_levelrisk'           ,
			'complete_time'               ,
			'create_time'                 ,
			'create_userid'	              ,
			'create_user'                 ,
	
			);
	//列表字段
	public $listFields = Array(
			'order_no'                    ,
			'holder'                      ,
			'license_no'                  ,
			'business_policy_no'          ,
			'business_premium'            ,
			'mvtalci_policy_no'           ,
			'mvtalci_premium'             ,
			'travel_tax_premium'          ,
			'total_premium'               ,
			'total_receivable_amount'     ,
			'create_user'                 ,
			'case_id'                     ,
			'status'                      ,
			'create_time'                 ,
			);
	//编辑字段
	public $editFields = Array(
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
			'order_no'                    ,
			'holder'                      ,
			'license_no'                  ,
			'business_policy_no'          ,
			'business_premium'            ,
			'mvtalci_policy_no'           ,
			'mvtalci_premium'             ,
			'travel_tax_premium'          ,
			'total_premium'               ,
			'total_receivable_amount'     ,
			'create_user'                 ,
			'case_id'                     ,
			'status'                      ,
			'auditor'                     ,
			'complete_time'               ,	
			'create_time'                 ,
			'create_user'                 ,
			'insurance_company'			  ,
			);

	//允许批量修改字段
	public $batchEditFields = Array();
	//允许miss编辑字段
	public $missEditFields = Array(
			);
	//默认排序
	public $defaultOrder = Array('create_time','DESC');
	//详情入口字段
	public $enteryField = 'order_no' ;
	//详细/编辑视图默认列数
	public $defaultColumns = 3;

	//分栏定义
	public $blocks = Array(
		'LBL_INSURANCE_ORDER_BASE' => Array(3,true,array(
			'order_no'                    ,
			'case_id'                     ,
			'status'                      ,			
			'total_receivable_amount'     ,
			'receiver'                    ,
			'receiver_mobile'             ,
			'receiver_addr'               ,
			'levelrisk'                   ,
			'auditor'                     ,
			'auditor_levelrisk'           ,
			'complete_time'               ,
			'create_time'                 ,
			'create_userid'	              ,
			'create_user'                 ,	
			
		)),
		'LBL_INSURANCE_INFO' => Array(3,true,array(
			'holder'                      ,
			'license_no'                  ,
			'vin_no'                      ,
			'business_policy_no'          ,
			'business_standard_premium'   ,
			'business_discount'           ,
			'business_premium'            ,
			'business_end_time'           ,
			'business_start_time'         ,
			'mvtalci_policy_no'           ,
			'mvtalci_standard_premium'    ,
			'mvtalci_discount'            ,
			'mvtalci_premium'             ,
			'mvtalci_end_time'            ,
			'mvtalci_start_time'          ,
			'travel_tax_premium'          ,
			'total_premium'               ,	
			'insurance_company'			  ,	
		)),
		'LBL_GIFT_INFO' => Array(1,true,array(
			'gift'                        ,
		)),
		'LBL_REMARKS_INFO' => Array(1,true,array(
			'remarks'                     ,
		)),		
	
	
	);
	//枚举字段值
	public $picklist = Array(
		
	);

	//字段关联
	public $associateTo = Array(
		'create_userid' => Array('MODULE','User','detailView','id','user_name'),
	);
	//模块关联
	public $associateBy = Array();
	//记录权限关联字段名
	public $shareField = 'create_userid';


};



?>