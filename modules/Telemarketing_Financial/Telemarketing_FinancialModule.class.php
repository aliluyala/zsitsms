<?php
require_once(_ROOT_DIR."/modules/Accounts_Financial/Accounts_FinancialModule.class.php");
class Telemarketing_FinancialModule extends Accounts_FinancialModule
{
    //模块描述
    public $describe = '电销业务';
    /**
     * [getOnePreset 获取7天前到现在最近的一条预约记录ID]
     * @return [Int] [成功返回记录ID,失败返回0]
     */
    public function getOnePreset() {
        global $CURRENT_IS_ADMIN;
        $currentDate = strtotime(date("Y-m-d"));
        $tenDaysAgo  = $currentDate - 10 * 86400;
        $queryWhere  = Array(
            Array("preset_time",">=",date("Y-m-d",$tenDaysAgo) . " 00:00:00","AND",""),
            Array("preset_time","<=",date("Y-m-d H:i:s"),"AND",""),
            Array("status","!=","FIRST_DIAL","AND",""),
            Array("status","!=","FAILED","AND",""),
            Array("status","!=","INVALID","AND",""),
            Array("status","!=","SUCCESS","",""),
        );
        $filterWhere = Array();
        $userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
        $groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
        $orderby     = "preset_time";
        $order       = "ASC";
        $maxRow      = 1;
        $response    = $this->getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,0,$maxRow);
        return $response ? $response[0]["id"] : FALSE;
        /*global $APP_ADODB,$CURRENT_USER_ID;
        $sql = "SELECT id FROM accounts WHERE user_attach = {$CURRENT_USER_ID} ";
        $sql .= "AND deleted = 0 ";
        $sql .= "AND preset_time BETWEEN ";
        $sql .= "CONCAT(DATE_SUB(DATE_FORMAT(CURRENT_TIMESTAMP(),'%Y-%m-%d'),INTERVAL 10 DAY),' 00:00:00') ";
        $sql .= "AND CURRENT_TIMESTAMP() AND status <> 'FAILED' AND status <> 'INVALID' AND status <> 'SUCCESS' ORDER BY preset_time ASC LIMIT 0,1";
        $result = $APP_ADODB->Execute($sql);
        if($result->RecordCount()) return $result->fields[0];
        return 0;*/
    }
    /**
     * [getNextPreset 当前时间之后的预约记录ID]
     * @return [Int] [成功返回记录ID，失败返回0]
     */
    public function getNextPreset(){
        global $CURRENT_IS_ADMIN;
        $queryWhere  = Array(
            Array("preset_time",">=",date("Y-m-d H:i:s"),"AND",""),
            Array("status","!=","FIRST_DIAL","AND",""),
            Array("status","!=","FAILED","AND",""),
            Array("status","!=","INVALID","AND",""),
            Array("status","!=","SUCCESS","",""),
        );
        $filterWhere = Array();
        $userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
        $groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
        $orderby     = "preset_time";
        $order       = "ASC";
        $maxRow      = 1;
        $response    = $this->getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,0,$maxRow);
        return $response ? $response[0]["id"] : FALSE;
        /*global $APP_ADODB,$CURRENT_USER_ID;
        $sql = "SELECT id FROM accounts WHERE user_attach = {$CURRENT_USER_ID} ";
        $sql .= "AND deleted = 0 ";
        $sql .= "AND preset_time >= CURRENT_TIMESTAMP() ";
        $sql .= "AND status <> 'FAILED' AND status <> 'INVALID' AND status <> 'SUCCESS' ";
        $sql .= "ORDER BY preset_time ASC LIMIT 0,1";
        $result = $APP_ADODB->Execute($sql);
        if($result->RecordCount()) return $result->fields[0];
        return 0;*/
    }
    //'FIRST_DIAL','FAILED','INVALID','APPOINTMENT_QUOTATION','APPOINTMENT_NON_QUOTATION','SUCCESS'
    /**
     * [getOneFirstDial 获取ID最近的一条首拨记录ID]
     * @return [Int] [成功返回记录ID,失败返回0]
     */
    public function getOneFirstDial(){
        global $CURRENT_IS_ADMIN;
        $queryWhere  = Array(
            Array("preset_time","=","0000-00-00 00:00:00","AND",""),
            Array("status","=","FIRST_DIAL","",""),
        );
        $filterWhere = Array();
        $userids     = $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission(_MODULE);
        $groupids    = $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission(_MODULE);
        $orderby     = "id";
        $order       = "ASC";
        $maxRow      = 1;
        $response    = $this->getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,0,$maxRow);
        return $response ? $response[0]["id"] : FALSE;
        /*global $APP_ADODB,$CURRENT_USER_ID;
        $sql = "SELECT id FROM accounts WHERE user_attach = {$CURRENT_USER_ID} ";
        $sql .= "AND deleted = 0 ";
        $sql .= "AND preset_time = '0000-00-00 00:00:00' AND status = 'FIRST_DIAL' ORDER BY id ASC LIMIT 0,1";
        $result = $APP_ADODB->Execute($sql);
        if($result->RecordCount()) return $result->fields[0];
        return 0;*/
    }
    /**
     * [getNextOneRecordsetID 重写下一页函数]
     * @return [Int] [成功返回记录ID,失败返回0]
     */
    public function getNextOneRecordsetID($id,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids){
        $recordid  = $this->getOnePreset();
        $firstDial = $this->getOneFirstDial();
        $recordid  = $recordid ? $recordid : ($firstDial ? $firstDial : $this->getNextPreset());
        return $recordid;
    }
};



?>