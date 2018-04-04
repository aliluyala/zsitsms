<?php
class ProductModule extends BaseModule
{
    public $baseTable = 'product';
    //模块描述
    public $describe = '商品库存管理';
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
            'code'          => Array('7','S',true,'商品编码','',5,20,'160px','^[0-9a-zA-Z\-_]+$','商品代码由字母数字及-,_组成。'),
            'name'          => Array('7','S',false,'商品名称','',1,250,'250px','^.+$',''),
            'class'         => Array('27','S',true,'类别',''),
            'standard'      => Array('7','S',false,'商品规格','',1,250,'300px','^.+$',''),
            'price'         => Array('8','N',true,'商品单价','0.00',0,999999999999,'70px','元',2),
            'count'         => Array('8','N',true,'数量','0',0,999999999999,'70px','',0),
            'repertory'     => Array('27','E',true,'仓库',''),
            'state'         => Array('20','E',true,'状态',''),
            'create_time'   => Array('35','DT',true,'记录创建的时间'),
            'create_userid' => Array('51','N',true,'创建记录的操作员'),
            'modify_time'   => Array('36','DT',false,'最后一次修改记录的时间'),
            'modify_userid' => Array('52','N',false,'最后一次修改记录的操作员'),
            );
    //安全字段,可以控制权限
    public $safeFields = array(
            'code'       ,
            'name'       ,
            'class'      ,
            'standard'   ,
            'price'      ,
            'count'      ,
            'repertory'  ,
            'state'          ,
            'create_time'    ,
            'create_userid'  ,
            'modify_time'    ,
            'modify_userid'  ,
            );
    //列表字段
    public $listFields = array(
            'code'       ,
            'name'       ,
            'class'      ,
            'standard'   ,
            'price'      ,
            'count'      ,
            'repertory'  ,
            'state'      ,
            );
    //编辑字段
    public $editFields = array(
            'code'       ,
            'name'       ,
            'class'      ,
            'repertory'  ,
            'count'      ,
            'price'      ,
            'standard'   ,
            'state'      ,
            );
    //列表最大行数
    public $listMaxRows = 20;
    //可排序字段
    public $orderbyFields = array(
            'code'       ,
            'name'       ,
            'class'      ,
            'price'      ,
            'count'      ,
            'repertory'  ,
            'state'      ,
            'create_time'    ,
            'create_userid'  ,
            'modify_time'    ,
            'modify_userid'  ,
            );
    //默认排序
    public $defaultOrder = array('code','ASC');
    //详情入口字段
    public $enteryField = 'code';
    //详细/编辑视图默认列数
    public $defaultColumns = 2;

    //分栏定义
    public $blocks = Array(
    	/*"LBL_PRODUCT_INFO"  => Array("2", true, Array("code", "name", "class", "price", "count", "repertory", "standard", "state")),
    	"LBL_PRODUCT_AFFIX" => Array("2", true, Array("create_time", "create_userid", "modify_time", "modify_userid")),*/
    );
    //枚举字段值
    public $picklist = Array('state' => Array('NORMAL','FORBIDDEN','UNLIMITED'));

    //字段关联
    public $associateTo = array(
        'create_userid' => array('MODULE','User','detailView','id','user_name'),
        'modify_userid' => array('MODULE','User','detailView','id','user_name'),
    );
    //模块关联
    public $associateBy = array();
    //记录权限关联字段名
    public $shareField = 'create_userid';

};



?>