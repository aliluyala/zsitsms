<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$APP_ADODB->Execute("SET NAMES utf8;");
class InsuranceOrderComModule extends BaseModule
{
    public $baseTable = 'insurance_order_com';

    //模块描述
    public $describe = '保险订单管理(四川通用)';
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
            'order_no'                  => Array('5','S',false,'订单号',''),
            'holder'                    => Array('5','S',false,'投保人',''),
            'license_no'                => Array('7','S',false,'','',7,7,'','^[\u4e00-\u9fa5][a-zA-Z][a-zA-Z0-9]{5}$','车牌号码格式如：川A12345'),
            'vin_no'                    => Array('5','S',false,'车辆识别码',''),
            'pre_business_no'           => Array('5','S',false,'商业险投保单号',''),
            'business_policy_no'        => Array('5','S',false,'商业险保单号',''),
            'business_discount_premium' => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//商业险折后保费合计
            'business_discount'         => Array('8','N',false,'','1.0000',0,2,'','',4),//商业险保司折扣
            'business_custom_discount'  => Array('8','N',false,'','1.0000',0,2,'','',4),//商业险自定折扣
            'business_premium'          => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//商业险保费合计
            'business_end_time'         => Array('31','DT',false,'商业险结束时间',''),
            'business_start_time'       => Array('31','DT',false,'商业险生效时间',''),
            'pre_mvtalci_no'            => Array('5','S',false,'交强险投保单号',''),
            'mvtalci_policy_no'         => Array('5','S',false,'交强险保单号',''),
            'mvtalci_discount'          => Array('8','N',false,'','1.0000',0,2,'','',4),//交强险折扣
            'mvtalci_premium'           => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//交强险保费
            'mvtalci_end_time'          => Array('31','DT',false,'交强险结束时间',''),
            'mvtalci_start_time'        => Array('31','DT',false,'交强险生效时间',''),
            'mvtalci_notice_no'         => Array('5','S',false,'交强险缴费通知单号',''),
            'business_notice_no'        => Array('5','S',false,'商业险缴费通知单号',''),
            'travel_tax_premium'        => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//车船税
            'total_premium'             => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//折后总费用
            'advance_premium'           => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//预收保费
            'total_receivable_amount'   => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//实收款合计
            'receiver'                  => Array('5','S',false,'收件人',''),
            'receiver_mobile'           => Array('5','S',false,'联系电话',''),
            'receiver_addr'             => Array('5','S',false,'收件地址',''),
            'case_id'                   => Array('5','S',false,'流程流水号',''),
            'status'                    => Array('20','E',false,'状态',''),
            'remarks'                   => Array('9','S',false,'备注',''),
            'gift_total_price'          => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//总额
            'gift'                      => Array('9','S',false,'礼品',''),
            'auditor'                   => Array('5','S',false,'审核人',''),
            'complete_time'             => Array('31','DT',false,'订单完成时间',''),
            'create_time'               => Array('31','DT',false,'创建时间',''),
            'create_userid'             => Array('50','N',false,'创建人',''),
            'create_user'               => Array('5','S',false,'业务员',''),
            'user_mobile'               => Array('5','S',false,'业务员电话',''),
            'leader_name'               => Array('5','S',false,'团队长',''),
            'leader_mobile'             => Array('5','S',false,'团队长电话',''),
            'send_time'                 => Array('31','DT',false,'派件时间',''),
            'require_time'              => Array('31','DT',false,'约定送达时间',''),
            'insurance_company'         => Array('27','S',false,'承保保险公司',''),
            'model'                     => Array('7','S',false,'','',0,255,'193px'),
            'engine_no'                 => Array('7','S',false,'','',0,255,'160px'),
            'engine'                    => Array('8','N',false,'','0.0000',0,500,'40px','L',4),
            'enroll_date'               => Array('30','D',false,'注册日期'),
            'submit_time'               => Array('31','DT',false,'提交核保时间',''),
            'print_time'                => Array('31','DT',false,'打单时间',''),
            'express'                   => Array('50','N',false,'物流公司',''),
            'courier'                   => Array('50','N',false,'配送人员',''),
            'courier_mobile'            => Array('60','S',false,'电话号码(5-20位数字)','',5,20),
            'waybill_number'            => Array('5','S',false,'运单号',''),
            'sign'                      => Array('5','S',false,'签收人',''),
            'sign_time'                 => Array('31','DT',false,'签收时间',''),
            'payment_type'              => Array('20','E',false,'收款方式'),
            'reason'                    => Array('9','S',false,'退保原因'),
            'current_receivable_amount' => Array('8','N',false,'','0.00',0,1000000000,'','元',2),//实收金额
            );

    //安全字段,可以控制权限
    public $safeFields = Array(
            'order_no'                    ,
            'holder'                      ,
            'license_no'                  ,
            'vin_no'                      ,
            'business_policy_no'          ,
            'business_discount_premium'   ,
            'business_discount'           ,
            'business_custom_discount'    ,
            'business_premium'            ,
            'business_end_time'           ,
            'business_start_time'         ,
            'mvtalci_policy_no'           ,
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
            'remarks'                     ,
            'gift'                        ,
            'auditor'                     ,
            'complete_time'               ,
            'create_time'                 ,
            'create_userid'               ,
            'create_user'                 ,
            'user_mobile'                 ,
            'leader_name'                 ,
            'leader_mobile'               ,
            'send_time'                   ,
            'require_time'                ,
            'model'                       ,
            'engine_no'                   ,
            'engine'                      ,
            'enroll_date'                 ,
            'submit_time'                 ,
            'print_time'                  ,
            'pre_business_no'             ,
            'pre_mvtalci_no'              ,
            'mvtalci_notice_no'           ,
            'business_notice_no'          ,
            'gift_total_price'            ,
            'advance_premium'             ,
            'express'                     ,
            'courier'                     ,
            'courier_mobile'              ,
            'waybill_number'              ,
            'sign'                        ,
            'sign_time'                   ,
            'payment_type'                ,
            'reason'                      ,
            );
    //列表字段
    public $listFields = Array(
            'order_no'                    ,
            'case_id'                     ,
            'holder'                      ,
            'license_no'                  ,
            'insurance_company'           ,
            'business_policy_no'          ,
            'business_premium'            ,
            'mvtalci_policy_no'           ,
            'mvtalci_premium'             ,
            'travel_tax_premium'          ,
            'total_premium'               ,
            'advance_premium'             ,
            'total_receivable_amount'     ,
            'create_user'                 ,
            'user_mobile'                 ,
            'create_userid'               ,
            'create_time'                 ,
            'status'                      ,
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
            'mvtalci_policy_no'           ,
            'case_id'                     ,
            'status'                      ,
            'auditor'                     ,
            'complete_time'               ,
            'create_time'                 ,
            'create_userid'               ,
            'create_user'                 ,
            'user_mobile'                 ,
            'leader_name'                 ,
            'leader_mobile'               ,
            'send_time'                   ,
            'require_time'                ,
            'insurance_company'           ,
            'model'                       ,
            'engine_no'                   ,
            'engine'                      ,
            'enroll_date'                 ,
            'submit_time'                 ,
            'print_time'                  ,
            'pre_business_no'             ,
            'pre_mvtalci_no'              ,
            'mvtalci_notice_no'           ,
            'business_notice_no'          ,
            'gift_total_price'            ,
            'express'                     ,
            'courier'                     ,
            'courier_mobile'              ,
            'waybill_number'              ,
            'sign'                        ,
            'sign_time'                   ,
            'payment_type'                ,
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
            'payment_notice_no'           ,
            'advance_premium'             ,
            'total_receivable_amount'     ,
            'payment_type'                ,
            'receiver'                    ,
            'receiver_mobile'             ,
            'receiver_addr'               ,
            'auditor'                     ,
            'create_time'                 ,
            'submit_time'                 ,
            'print_time'                  ,
            'complete_time'               ,
            'create_userid'               ,
            'create_user'                 ,
            'user_mobile'                 ,
            'send_time'                   ,
            'leader_name'                 ,
            'leader_mobile'               ,
            'require_time'                ,

        )),
        'LBL_INSURANCE_INFO' => Array(3,true,array(
            'holder'                      ,
            'license_no'                  ,
            'vin_no'                      ,
            'total_premium'               ,
            'insurance_company'           ,
            'model'                       ,
            'engine_no'                   ,
            'engine'                      ,
            'enroll_date'                 ,
        )),
        'LBL_INSURANCEBI_INFO' => Array(3,true,array(
            'pre_business_no'             ,
            'business_notice_no'          ,
            'business_policy_no'          ,
            'business_start_time'         ,
            'business_end_time'           ,
            'business_premium'            ,
            'business_discount'           ,
            'business_custom_discount'    ,
            'business_discount_premium'   ,
        )),
        'LBL_INSURANCECI_INFO' => Array(3,true,array(
            'pre_mvtalci_no'              ,
            'mvtalci_notice_no'           ,
            'mvtalci_policy_no'           ,
            'mvtalci_start_time'          ,
            'mvtalci_end_time'            ,
            'mvtalci_premium'             ,
            'mvtalci_discount'            ,
            'travel_tax_premium'          ,

        )),
        'LBL_EXPRESS_INFO' => Array(3,true,array(
            'express'                     ,
            'courier'                     ,
            'courier_mobile'              ,
            'waybill_number'              ,
            'sign'                        ,
            'sign_time'                   ,
        )),
        'LBL_GIFT_INFO' => Array(1,true,array(
            'gift_total_price'            ,
            'gift'                        ,
        )),

        'LBL_REMARKS_INFO' => Array(1,true,array(
            'remarks'                     ,
        )),


    );
    //枚举字段值
    public $picklist = Array(
        "payment_type" => Array("CASH","POS","VIREMENT","WECHAT","ALIPAY"),
        "status"       => Array("DRAFT","AUDIT","REVOKE","WAIT_CONFIRM","REVISE","WAIT_PRINT","WAIT_DELIVERY","OUT_FOR_DELIVERY","WAIT_WITHDRAW","WAIT_COLLECTION","COMPLETE","WITHDRAW_COMPLETE"),
    );
    //预定义配送情况表头
    public $pre_distribution = Array(
        'order_no'               ,
        'express'                ,
        'courier'                ,
        'waybill_number'         ,
        'status'                 ,
        'sign'                   ,
        'sign_time'              ,
        'reason'                 ,
        'advance_premium'        ,
        'current_receivable_amount',
        'payment_type'           ,
    );
    private $pre_variables = Array(
        "OUT_FOR_DELIVERY" => Array(
            "SIGN"      => "sign",
            "SIGN_TIME" => "sign_time",
            "OPERATION" => "status",
            "REASON"    => "reason",
        ),
        "WAIT_COLLECTION"  => Array(
            "CURRENT_RECEIVABLE_AMOUNT" => "current_receivable_amount",
            "CURRENT_RECEIVABLE_TYPE"   => "payment_type",
            "CURRENT_PAY_USER"          => "sign",
            "OPERATION"                 => "status",
        ),
    );
    //字段关联
    public $associateTo = Array(
        "create_userid" => Array("MODULE","User","detailView","id","user_name"),
        "express"       => Array("MODULE","Express","detailView","id","name"),
        "courier"       => Array("MODULE","Courier","detailView","id","name"),
    );
    //模块关联
    public $associateBy = Array(
        'ASSOCIATE_WORKFLOWLOG_INFO'      => array('WorkFlowLog','insuranceOrder_id','cindex','task','start_time','end_time','delegated_user_name','suggestion'),
    );
    //记录权限关联字段名
    public $shareField = 'create_userid';

    private $PMApi;

    private $caseList;

    /**
     * [PM_init 加载流程配置与PM接口类]
     */
    public function PM_init() {
        $pmconf = require(_ROOT_DIR.'/config/workflow.conf.php');
        if(!$pmconf || !$pmconf['enable']) return false;
        require(_ROOT_DIR.'/include/workflow/PMApi.class.php');
        $this->PMApi = new PMApi($pmconf);
        return true;
    }

    /**
     * [PM_login PM登录]
     */
    public function PM_login() {
        global $APP_ADODB, $CURRENT_USER_ID, $CURRENT_USER_NAME;
        $sql = "select user_password from users where id={$CURRENT_USER_ID};";
        $result = $APP_ADODB->Execute($sql);
        if(!$result || $result->EOF) return false;
        $pwd = $result->fields['user_password'];
        $response = $CURRENT_USER_ID != 1 ? $this->PMApi->login($CURRENT_USER_NAME,'md5:'.$pwd,false) : $this->PMApi->login(null,null,true);
        return $response ? true : false;
    }

    /**
     * [PM_caseList 获取case列表]
     */
    public function PM_caseList() {
        $response = Array();
        $caseList = $this->PMApi->caseList();
        if($caseList) {
            foreach ($caseList as $val) {
                $response[$val["name"]] = $val;
            }
            $this->caseList = $response;
            return true;
        }
        return false;
    }

    /**
     * [PM_routeCase 路由case到下一task]
     * @param [String]  $order_no  订单号]
     * @param [Boolean] $variables [true/false]
     */
    public function PM_routeCase($order_no, $variables) {
        $response = false;
        $case = $this->getCaseSummary($order_no);
        if(!isset($this->caseList[$case[0]["case_id"]])) return false;
        $caseSummary = $this->caseList[$case[0]["case_id"]];
        if($case !== false && array_key_exists($case[0]["status"], $this->pre_variables)) {
            $params = Array();
            foreach ($this->pre_variables[$case[0]["status"]] as $key => $val) {
                $params[$key] = $variables[$val];
            }
            $response = $this->PMApi->sendVariables($caseSummary["guid"], $params);
        }
        return $response !== false ? $this->PMApi->routeCase($caseSummary["guid"], $caseSummary["delIndex"]) : false;
    }

    /**
     * [getCaseSummary 获取订单号对应的case信息]
     * @param  [String] $order_no [订单号]
     * @return [Array/Boolean]    [成功返回case信息,失败返回false]
     */
    public function getCaseSummary($order_no) {
        global $CURRENT_IS_ADMIN;
        $queryWhere  = Array(Array("order_no", "=", $order_no, "", ""));
        $filterWhere = Array();
        $userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
        $groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
        $count       = 1;
        return $this->getListQueryRecord($queryWhere,$filterWhere,'','',$userids,$groupids,0,$count);
    }
};



?>