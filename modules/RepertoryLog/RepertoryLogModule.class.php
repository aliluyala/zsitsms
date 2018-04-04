<?php
class RepertoryLogModule extends BaseModule
{
	public $baseTable = 'repertory_log';
	//模块描述,子类中必须定义
	public  $describe = '出入库记录';
	
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
		'code'                       => Array('7','S',true,'商品编码','',5,20,'160px','^[0-9a-zA-Z\-_]+$','商口代码由字母数字及-,_组成。'),
	    'name'                       => Array('7','S',true,'商品名称','',1,250,'250px','^.+$',''),
		'other'                      => Array('7','S',true,'供应商/客户','',1,250,'250px','^.+$',''),
		'price'                      => Array('8','N',true,'商品单价','0.00',0,999999999999,'70px','元',2),
	    'count'                      => Array('8','N',true,'库存数量','0',0,999999999999,'70px','',0),
        'sum'                        => Array('8','N',true,'金额小计','0',0,999999999999,'70px','',0),
		'repertory'                  => Array('27','S',true,'仓库',''),
        'operation'                  => Array('20','E',true,'操作类型','ENTRY_REPERTORY'),
        'time'                       => Array('31','DT',true,'操作时间','0000-00-00 00:00:00'),
		'name'                       => Array('7','S',true,'商品名称','',1,250,'250px','^.+$',''),
		'salesman'                   => Array('7','S',true,'经办人','',1,50,'160px','^.+$',''), 
        'userid'                     => Array('50','N',true,'库管员','-1'),

	);
	public $editFields = Array();
	public $picklist = Array('operation' => Array('ENTRY_REPERTORY','OUT_REPERTORY'));
	public $listFields = Array(
		'code'      ,
	    'name'      ,
		'other'     ,
	    'price'     ,
	    'count'     ,
	    'sum'       ,
	    'repertory' ,
	    'operation' ,
	    'time'      ,
		'salesman'  ,
	    'userid'    ,
	);
	public $associateTo = Array(
		'userid' => Array('MODULE','User','detailView','id','user_name'),	
	);	
	public $shareField = '';
	
	public function log($info)
	{
		$logid  = getNewModuleSeq($this->baseTable);
		$this->editFields = $this->listFields;
		$this->insertOneRecordset($logid,$info);
		$this->editFields = array();	
	}
}
?>	