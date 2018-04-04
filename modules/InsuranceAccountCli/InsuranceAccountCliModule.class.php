<?php
class InsuranceAccountCliModule extends BaseModule
{
	public $baseTable = 'insurance_account_cli';
	//模块描述
	public $describe = '保险公司帐号管理(用户端)';
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
			'modifyFilter' => '编辑过滤',
			);
	//字段定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
	public $fields = Array(					

					'uid'                              => Array('5','S',true,'登录ID'),
					'pwd'                              => Array('5','S',true,'密码'),
					'insurance_company'                => Array('20','E',true,'保险公司代码','PICC'),					
					'create_time'                      => Array('35','DT',true,'创建时间'),					
					'create_userid'                    => Array('51','N',true,'创建人'),
					'modify_time'                      => Array('36','DT',false,'修改时间'),
					'modify_userid'                    => Array('52','N',false,'修改人'),								
					);
	//安全字段,可以控制权限
	public $safeFields = Array(
	                'insurance_company'            ,
	                'uid'                          ,
	                'pwd'                          ,

					);
	//列表字段
	public $listFields = Array(
	                'uid'                          ,
	                'pwd'                          ,
	                'insurance_company'            ,					
					);
	//编辑字段
	public $editFields = Array(
	                'insurance_company'            ,
	                'uid'                          ,
	                'pwd'                          ,

					);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = Array(
	                'uid'                          ,
	                'insurance_company'            ,					
                    'create_time'                  ,       
                    'create_userid'                ,     
                    'modify_time'                  ,       
                    'modify_userid'                , 										
					);
	//默认排序
	public $defaultOrder = Array('create_time','ASC');
	//详情入口字段
	public $enteryField =  'uid';
	//详细/编辑视图默认列数
	public $defaultColumns = 3;
	
	//分栏定义
	public $blocks = Array(
							'LBL_InsuranceAccountCli_BASE'=>Array('3',true,Array(
															  'insurance_company'            ,
															  'uid'                          ,
															  'pwd'                          ,
  
											                  )),

						   'LBL_RECORD_INFO'=>Array('3',true,Array(
															 'create_time'       ,
															 'create_userid'     ,
															 'modify_time'       ,
															 'modify_userid'     ,   
															 )),	
						);
						
				
						
						
						
	//枚举字段值
	public $picklist = Array(
		'insurance_company' => array(
						'PICC',
		                ),
		'state' => array('VALID','DISABLE'),				
	);
	
	//字段关联
	public $associateTo = Array(	
       'create_userid' => Array('MODULE','User','detailView','id','user_name'),		
		'modify_userid' => Array('MODULE','User','detailView','id','user_name'),		
	);
	
	//模块关联
	public $associateBy = Array(

	);
	//记录权限关联字段名
	public $shareField = 'create_userid';	
	
	//允许miss编辑字段
	public $missEditFields = Array(
	                'insurance_company'            ,
	                'uid'                          ,
	                'pwd'                          ,
	);
	//允许批量修改字段		
	public $batchEditFields = Array(
	
	);

};



?>