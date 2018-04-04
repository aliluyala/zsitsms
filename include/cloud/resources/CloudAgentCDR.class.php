<?php
/**
* 云呼叫中心坐席话单接口
*
**/
require_once(dirname(__file__) . '/../resource.class.php');
class CloudAgentCDR extends resource {

    protected $baseTable  = 'zswitch_cc_agent_cdr';

    protected $shareField = 'userid';

    private   $permission = Array(
        'counter'=> 'calllog',
        'lists'  => 'calllog',
        'detail' => 'calllog',
        'record' => 'record',
    );

    public function __construct($cloud, $params)
    {
        parent::__construct($cloud, $params);
    }

    private function counter($queryWhere, $filterWhere, $userids, $groupids)
    {
        return parent::getListQueryRecordCount($queryWhere, $filterWhere, $userids, $groupids);
    }

    private function lists($queryWhere, $filterWhere, $orderby, $order, $userids, $groupids, $start, $maxRows)
    {
        return parent::getListQueryRecord($queryWhere, $filterWhere, $orderby, $order, $userids, $groupids, $start, $maxRows);
    }

    private function detail($id, $userids, $groupids)
    {
        return parent::getOneRecordset($id, $userids, $groupids);
    }

    private function record($id, $userids, $groupids)
    {
        $response = $this->detail($id, $userids, $groupids);
        if($response && $response[0]['answered_datetime'] != '0000-00-00 00:00:00')
        {
            if($path = $this->cloud->storagePath())
            {
                $file = $response[0]['uuid'] . '_' . $response[0]['agent_name'] . '_' . $response[0]['other_number'] . '_' . Date('YmdHis',strtotime($response[0]['answered_datetime'])) . '.wav';

                $path .= '/recording/' . $response[0]['agent_name'] . '/' . $file;
            }
            if(file_exists($path))
                return $path;
        }
        return false;
    }

    public function agentCDR($request_method, $params = NULL)
    {
        if(!isset($this->permission[$request_method]))
        {
            $this->_log($request_method, 'NOTFOUND');
            return false;
        }
        if($this->cloud->hasFeature($this->permission[$request_method]))
        {
            if(method_exists($this, $request_method))
            {
                $response = call_user_func_array(Array($this, $request_method), $params);
            }
        }
        else
        {
            $this->_log($request_method, 'DENIED');
            return false;
        }
        $result   = $response ? 'SUCCESS' : 'FAILED';
        $this->_log($request_method, $result);
        return $response;
    }

}

?>