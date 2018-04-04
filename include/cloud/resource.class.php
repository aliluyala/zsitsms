<?php
//云资源接口

class resource {

    private $request_params  = Array('user_id' => 0, 'user_name' => '');

	public function __construct($cloud,$params)
    {
        if(isset($params['user_id']))
            $this->request_params['user_id']   = $params['user_id'];
        if(isset($params['user_name']))
            $this->request_params['user_name'] = $params['user_name'];

		$this->cloud = $cloud;
		$this->params = $params;
	}

    /**
     * [get_type 获取字段类型]
     * @param  [type] $query  [description]
     * @param  [type] $fields [description]
     * @return [type]         [description]
     */
    protected function get_type($query, $fields = NULL)
    {
        if(is_array($fields) && !empty($fields))
        {
            return $fields[1];
        }
        else
        {
            return is_string($query) ? 'S' : 'N';
        }
    }

    /**
     * [formatQueryWhere 格式化查询条件]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    protected function formatQueryWhere($query)
    {
        $sqlwhere = '';
        foreach ($query as $item) {
            if($item[1] == 'like_start')
            {
                $item[1] = 'like';
                $item[2] = $item[2] . '%';
            }
            elseif($item[1] == 'like_end')
            {
                $item[1] = 'like';
                $item[2] = '%' . $item[2];
            }
            elseif($item[1] == 'like_contain')
            {
                $item[1] = 'like';
                $item[2] = '%' . $item[2] . '%';
            }
            elseif($item[1] == 'like_no_contain')
            {
                $item[1] = 'not like';
                $item[2] = '%' . $item[2] . '%';
            }

            if($this->get_type($item[2]) == 'S')
            {
                $tmp1 = str_replace("'", "\'", $item[2]);
                $item[2] = "'" . $tmp1 . "'";
            }

            $sqlwhere .= empty($item[3]) ? "($item[0] $item[1] $item[2])" : "($item[0] $item[1] $item[2])$item[3]";
        }
        return $sqlwhere;
    }

    /**
     * [_getSqlOrderbyString 格式化排序条件]
     * @param  [type] $orderby [description]
     * @param  [type] $order   [description]
     * @return [type]          [description]
     */
    private function _getSqlOrderbyString($orderby, $order)
    {
        $sqlorder = '';
        if($orderby != '' && ($order == 'ASC' || $order == 'DESC'))
        {
            $sqlorder = " order by {$orderby} {$order} ";
        }
        return $sqlorder;
    }

    /**
     * [_getSqlShareWhereString 格式化用户、组查询条件]
     * @param  [type] $userids  [description]
     * @param  [type] $groupids [description]
     * @return [type]           [description]
     */
    private function _getSqlShareWhereString($userids, $groupids)
    {
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

    /**
     * [_getSqlWhereString 获取where条件]
     * @param  [type] $queryWhere  [description]
     * @param  [type] $filterWhere [description]
     * @param  [type] $userids     [description]
     * @param  [type] $groupids    [description]
     * @return [type]              [description]
     */
    private function _getSqlWhereString($queryWhere, $filterWhere, $userids, $groupids)
    {
        $qwhere = $this->formatQueryWhere($queryWhere);
        $fwhere = $this->formatQueryWhere($filterWhere);
        $where = '';
        if($qwhere != '' && $fwhere != '') $where = "  (({$qwhere}) AND ({$fwhere})) ";
        elseif($qwhere != '') $where = "  ({$qwhere}) ";
        elseif($fwhere != '') $where = "  ({$fwhere}) ";

        $swhere = $this->_getSqlShareWhereString($userids, $groupids);
        if($where != '' && $swhere != '') $where = " {$where} AND {$swhere} ";
        elseif($swhere != '') $where = " {$swhere} ";

        return $where;
    }

    /**
     * [getListQueryRecordCount 根据条件统计记录数]
     * @param  [type] $queryWhere  [description]
     * @param  [type] $filterWhere [description]
     * @param  [type] $userids     [description]
     * @param  [type] $groupids    [description]
     * @return [type]              [description]
     */
    protected function getListQueryRecordCount($queryWhere, $filterWhere, $userids, $groupids)
    {
        $where = $this->_getSqlWhereString($queryWhere, $filterWhere, $userids, $groupids);
        if($where != '') $where = "WHERE " . $where;
        $sql = "SELECT COUNT(*) FROM {$this->baseTable} " . $where;
        $res = FALSE;
        if($result = mysql_query($sql))
        {
            $res = mysql_fetch_row($result);
            $res = $res[0];
        }
        return $res;
    }

    /**
     * [getListQueryRecord 获取列表记录]
     * @param  [type] $queryWhere  [description]
     * @param  [type] $filterWhere [description]
     * @param  [type] $orderby     [description]
     * @param  [type] $order       [description]
     * @param  [type] $userids     [description]
     * @param  [type] $groupids    [description]
     * @param  [type] $start       [description]
     * @param  [type] $maxRows     [description]
     * @return [type]              [description]
     */
    protected function getListQueryRecord($queryWhere, $filterWhere, $orderby, $order, $userids, $groupids, $start, $maxRows)
    {
        $where = $this->_getSqlWhereString($queryWhere, $filterWhere, $userids, $groupids);
        if($where != '') $where = "WHERE " . $where;
        $order = $this->_getSqlOrderbyString($orderby, $order);
        $sql = "SELECT * FROM {$this->baseTable} " . $where . $order . 'limit ' . $start . ',' . $maxRows;
        $res = FALSE;
        if($result = mysql_query($sql))
        {
            while ($row = mysql_fetch_assoc($result)) {
                $res[] = $row;
            }
        }
        return $res;
    }

    /**
     * [getOneRecordset 通过id获取该记录详情]
     * @param  [int] $id       [description]
     * @param  [int] $userids  [description]
     * @param  [int] $groupids [description]
     * @return [mixed]         [description]
     */
    protected function getOneRecordset($id, $userids, $groupids)
    {
        $where = $this->_getSqlShareWhereString($userids,$groupids);
        $sql = "SELECT * FROM {$this->baseTable} WHERE id = {$id} ";
        $sql .= empty($where) ? 'limit 1' : $where . 'limit 1';
        $res = FALSE;
        if($result = mysql_query($sql))
            $res[] = mysql_fetch_assoc($result);
        return $res;
    }

    /**
     * [_log 日志记录]
     * @param  [type] $request_method [description]
     * @param  [type] $user_id        [description]
     * @param  [type] $user_name      [description]
     * @param  [type] $remote_addr    [description]
     * @param  [type] $result         [description]
     * @return [type]                 [description]
     */
    protected function _log($request_method, $result)
    {
        $cloud_id   = $this->cloud->cloudid();
        $cloud_name = $this->cloud->name();
        $sql = 'INSERT INTO cloud_operation_log(request_method, cloud_id, cloud_name, client_user_id, client_user_name, remote_addr, result) ';
        $sql .= "VALUES ('{$request_method}','{$cloud_id}','{$cloud_name}',{$this->request_params['user_id']},'{$this->request_params['user_name']}','{$_SERVER['REMOTE_ADDR']}','{$result}')";
        mysql_query($sql);
    }

}



?>