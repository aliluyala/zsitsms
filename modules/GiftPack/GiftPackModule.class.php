<?php
class GiftPackModule extends BaseModule
{
	public $baseTable = 'gift_packs';
	//模块描述,子类中必须定义
	public  $describe = '礼品包管理';
	
	//模块方法,子类中必须定义
	public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'delete' => '删除',			
			'export' => '导出',
			'batchDelete' => '批量删除',
			'modifyFilter' => '编辑过滤',
			);
			
	public $fields = Array(
	        'name'                  => Array('7','S',true,'礼品包名称','',1,160,'160px','^.+$',''),
	        //'descr'              => Array('7','S',true,'描述','',1,250,'250px','^.+$',''),
			'descr'              => Array('9','S',true,'描述',''),
			'class'                 => Array('27','S',true,'类别',''),
	        'start_date'            => Array('30','DT',true,'生效日期','0000-00-00'),
	        'end_date'              => Array('30','DT',true,'失效时期','0000-00-00'),
			'state'                 => Array('20','E',true,'状态',''),			
	        'content'               => Array('101','S',false,'内容',''),
	        'create_time'           => Array('35','DT',true,'记录创建的时间'),
	        'create_userid'         => Array('51','N',true,'创建记录的操作员'),
	        'modify_time'           => Array('36','DT',false,'最后一次修改记录的时间'),
	        'modify_userid'         => Array('52','N',false,'最后一次修改记录的操作员'),
	);
	public $safeFields = Array(
			'name'           ,
	        'descr'       ,
	        'class'          ,
	        'start_date'     ,
	        'end_date'       ,
	        'state'          ,
	        'content'        ,
	        'create_time'    ,
	        'create_userid'  ,
	        'modify_time'    ,
	        'modify_userid'  ,		
	);
	public $editFields = Array(
			'name'           ,
	        'descr'       ,
	        'class'          ,
	        'start_date'     ,
	        'end_date'       ,
	        'state'          ,
	        'content'        ,
	);

	public $listFields = Array(
			'name'          ,
			'class'         ,
	        'state'         ,
	        'start_date'    ,
	        'end_date'      ,
	        'create_time'   ,
	        'create_userid' ,
	        'modify_time'   ,
	        'modify_userid' ,	
	);
	
	//可排序字段
	public $orderbyFields = Array(
			'class'         ,
	        'state'         ,
	        'name'          ,
	        'start_date'    ,
	        'end_date'      ,
	        'create_time'   ,
	        'create_userid' ,
	        'modify_time'   ,
	        'modify_userid' ,		
	
	);
	
	//默认排序
	public $defaultOrder = Array('name','ASC');	
	
	//列表最大行数
	public $listMaxRows = 20;
	
	//详情入口字段
	public $enteryField = 'name';
	//详细/编辑视图默认列数
	public $defaultColumns = 1;	
	
	public $picklist = Array('state' => Array('NORMAL','FORBIDDEN','OUT_OF_STOCK','EXPIRE'));	
	public $associateTo = Array(
		'create_userid'	=> array('MODULE','User','detailView','id','user_name'),
		'modify_userid'	=> array('MODULE','User','detailView','id','user_name'),
	);	
	public $shareField = '';
	

}
?>	