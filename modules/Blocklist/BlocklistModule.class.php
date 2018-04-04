<?php
class BlocklistModule extends BaseModule
{
	public $baseTable = 'blocklist';
	//模块描述
	public $describe = '黑名单管理';
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
			'phone_number'     => Array('7','S',true,'号码','',1,20,'160px','^[0-9]{1,20}$','号码必须是1-20位数字.'),
            'match_type'       => Array('20','E',false,'匹配方式','SUFFIX'),
			'descr'            => Array('5','S',false,'说明','',1,255,'250px'),
			'create_time'      => Array('35','DT',false,'记录创建的时间'),
			'create_userid'    => Array('51','N',false,'创建记录的操作员'),
			'modify_time'      => Array('36','DT',false,'最后一次修改记录的时间'),
			'modify_userid'    => Array('52','N',false,'最后一次修改记录的操作员'),
			);
	//安全字段,可以控制权限
	public $safeFields = array(
			'phone_number'    ,
			'match_type'      ,
			'descr'           ,
			
			);
	//列表字段
	public $listFields = array(
			'phone_number'    ,
			'match_type'      ,
			'descr'           ,
			'create_time'     , 
			);
	//编辑字段
	public $editFields = array(
			'phone_number'    ,
			'match_type'      ,
			'descr'           ,
			);
	//列表最大行数
	public $listMaxRows = 20;
	//可排序字段
	public $orderbyFields = array(
			'phone_number'    ,
			'match_type'      ,
			);
	//允许miss编辑字段
	public $missEditFields = Array(
			'phone_number'    ,
			'match_type'      ,
			'descr'           ,
			);			
	//默认排序
	public $defaultOrder = array('phone_number','ASC');
	//详情入口字段
	public $enteryField = 'phone_number';
	//详细/编辑视图默认列数
	public $defaultColumns = 1;

	//分栏定义
	public $blocks = Array();
	//枚举字段值
	public $picklist = array(
		'match_type' => array('FULL','PREFIX','SUFFIX'),
	);

	//字段关联
	public $associateTo = array(
		'create_userid'	=> array('MODULE','User','detailView','id','user_name'),
		'modify_userid'	=> array('MODULE','User','detailView','id','user_name'),
	
	);
	//模块关联
	public $associateBy = array();
	//记录权限关联字段名
	public $shareField = '';

};



?>