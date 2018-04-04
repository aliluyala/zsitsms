<?php
class LibraryPostModule extends BaseModule
{
    public $baseTable = 'library_post';
    //模块描述
    public $describe = '文章';
    //需要控制访问权限的模块方法
    public $actions = Array(
            'index'        => '浏览',
            'detailView'   => '详情',
            'editView'     => '编辑',
            'createView'   => '新建',
            'copyView'     => '复制',
            'save'         => '保存',
            'delete'       => '删除',
            'batchDelete'  => '批量删除',
            'modifyFilter' => '编辑过滤',
            );
    //字段定义
    //'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')
    public $fields = Array(
            'title'                   =>Array('5','S',true,'标题','',1,200),
            'categoryid'              =>Array('50','N',false,'所属分类',0),
            'status'                  =>Array('20','E',true,'文章状态','PUBLISH'),
            'content'                 =>Array('10','S',false,'文章内容',''),
            'date_create'             =>Array('35','DT',true,'创建时间'),
            'user_create'             =>Array('51','N',true,'发布人'),
            'date_modify'             =>Array('36','DT',false,'最后一次修改记录的时间'),
            'user_modify'             =>Array('52','N',false,'最后一次修改记录的操作员'),
            );
    //安全字段,可以控制权限
    public $safeFields = Array(
            'title'         ,
            'categoryid'    ,
            'status'        ,
            'content'       ,
            'date_create'   ,
            'user_create'   ,
            'date_modify'   ,
            'user_modify'   ,
            );
    //列表字段
    public $listFields = Array(
            'title'         ,
            'categoryid'    ,
            'user_create'   ,
            'status'        ,
            'date_create'   ,
            );
    //编辑字段
    public $editFields = Array(
            'title'         ,
            'categoryid'    ,
            'status'        ,
            'content'       ,
            );
    //miss编辑字段
    public $missEditFields = Array(
            'title'         ,
            'categoryid'    ,
            'status'        ,
            'content'       ,
            'date_create'   ,
            'user_create'   ,
            'date_modify'   ,
            'user_modify'   ,
            );
    //列表最大行数
    public $listMaxRows = 20;
    //可排序字段
    public $orderbyFields = Array(
            'title'         ,
            'categoryid'    ,
            'status'        ,
            'date_create'   ,
            'user_create'   ,
            'date_modify'   ,
            'user_modify'   ,
            );
    //允许批量修改字段
    public $batchEditFields = Array(
            'title'         ,
            'categoryid'    ,
            'status'        ,
            'user_create'   ,
    );
    //默认排序
    public $defaultOrder = Array('date_create','DESC');
    //详情入口字段
    public $enteryField = 'title';
    //详细/编辑视图默认列数
    public $defaultColumns = 3;

    //分栏定义
    public $blocks = Array('LBL_POSTS_BASE'   => Array('3',true,Array(
                                                                    'title'          ,
                                                                    'categoryid'     ,
                                                                    'status'         ,
                                                                    )),
                           'LBL_POSTS_CONTENT'=> Array('1',true,Array(
                                                                    'content'        ,
                                                                    )),
                           'LBL_POSTS_ADD'    => Array('2',true,Array(
                                                                    'user_create'   ,
                                                                    'user_modify'   ,
                                                                    'date_create'   ,
                                                                    'date_modify'   ,
                                                    )),
                        );
    //枚举字段值
    public $picklist = Array(
            'status'  =>  Array('PUBLISH','DRAFT'),
    );

    //字段关联
    public $associateTo = Array(
        'categoryid'  => Array('MODULE','LibraryCategory','detailView','id','name'),
        'user_create' => Array('MODULE','User','detailView','id','user_name'),
        'user_modify' => Array('MODULE','User','detailView','id','user_name'),
    );
    //模块关联
    public $associateBy = Array(
        //'associate_loginlog_info' => Array('LoginLog','userid','user_name','ip_address','oper_time','state'),
    );

    //记录权限关联字段名
    public $shareField = 'user_create';

    //根据条件返回列表记录总数
    public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids)
    {
        global $APP_ADODB,$CURRENT_USER_ID;
        $where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
        if($where != '') $where = ' AND '.$where;
        $sql = "SELECT COUNT(*) FROM {$this->baseTable} WHERE (status = 'PUBLISH' OR user_create = {$CURRENT_USER_ID}) {$where}";
        $result = $APP_ADODB->Execute($sql);
        if($result) return $result->fields[0];
        return false;
    }
    //根据条件返回列表显示的记录集
    public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows)
    {
        global $APP_ADODB,$CURRENT_USER_ID;
        $where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
        if($where != '') $where = ' AND '.$where;
        $order = $this->_getSqlOrderbyString($orderby,$order);
        $sql = "SELECT * FROM {$this->baseTable} WHERE (status = 'PUBLISH' OR user_create = {$CURRENT_USER_ID}) {$where} {$order} LIMIT {$start},{$maxRows}";
        $result = $APP_ADODB->Execute($sql);
        if($result) return $result->getArray();
        return false;
    }
    //获取sql记录共享条件字符串
    private function _getSqlShareWhereString($userids,$groupids)
    {
        global $CURRENT_IS_ADMIN,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
        if($CURRENT_IS_ADMIN) return '';
        if(empty($userids) && empty($groupids) ) return '';
        $swhere = '';
        if(isset($this->shareField))
        {
            if(empty($userids))
            {
                $swhere = " ({$this->shareField} IN ({$groupids})) ";
            }
            elseif(empty($groupids))
            {
                $swhere = " ({$this->shareField} IN ({$userids})) ";
            }
            else
            {
                $swhere = " (({$this->shareField} IN ({$groupids})) OR ({$this->shareField} IN ({$userids}))) ";
            }
        }

        return $swhere;
    }

    //获取sql查询的条件字符串
    private function _getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids)
    {
        global $CURRENT_IS_ADMIN,$CURRENT_USER_ID,$CURRENT_USER_GROUPID;
        $qwhere = formatSqlWhere($queryWhere,$this->fields);
        $fwhere = formatSqlWhere($filterWhere,$this->fields);
        $where = '';
        if($qwhere != '' && $fwhere != '') $where = "  {$qwhere} AND {$fwhere} ";
        elseif($qwhere != '') $where = "  {$qwhere} ";
        elseif($fwhere != '') $where = "  {$fwhere} ";

        $swhere = $this->_getSqlShareWhereString($userids,$groupids);
        if($where != '' && $swhere != '') $where = " {$where} AND {$swhere} ";
        elseif($swhere != '') $where = " {$swhere} ";

        return $where;
    }
    //获取sql命令的排序字符串
    private function _getSqlOrderbyString($orderby,$order)
    {
        $sqlorder = '';
        if($orderby != '' && ($order == 'ASC' || $order == 'DESC'))
        {
            $sqlorder = " order by {$orderby} {$order} ";
        }
        return $sqlorder;
    }
};



?>