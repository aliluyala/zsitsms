<?php
class BaseModule
{
	//模块数据表名,子类中必须定义
	//public $baseTable = 'baseTable';
	
	//模块描述,子类中必须定义
	//public  $describe = 'BaseMoudle';
	
	//模块方法,子类中必须定义
	/*public $actions = Array(
			'index' => '浏览',
			'detailView' => '详情',
			'editView' => '编辑',
			'createView' => '新建',
			'copyView' => '复制',
			'save' => '保存',
			'delete' => '删除'
			);
	*/		
	//字段定义,子类中必须定义
	//'字段名'=>Array('UI类型','数据类型','是否强制','提示信息','默认值','最小值/长度','最大值/长度')	
	//public $fields = Array('field_name' => Array('5','S',true,'字段名','',6,20));
	
	//安全字段,可以控制权限,子类中定义,如果没有定义取成员fields
	//public $safeFields = Array('field_name');
	
	//列表字段,子类中定义,如果没有定义取成员fields
	//public $listFields = Array('field_name');
	
	//编辑字段,子类中定义,如果没有定义取成员fields
	//public $editFields = Array('field_name');	
	
	//允许批量修改字段,子类中定义,如果没有定义为空
	//public $batchEditFields = Array('field_name');
	
	//允许miss编辑字段
	//public $missFields = Array('field_name');
	
	//可排序字段,子类中定义,如果没有定义取成员listFields
	//public $orderbyFields = Array('field_name');
	
	//默认排序,子类中定义,如果没有定义取成员orderbyFields第一个字段,排序方式为ASC
	//public $defaultOrder = Array('field_name','ASC');
	
	//详情入口字段,子类中定义,如果没有定义取成员listFields第一个字段
	//public $enteryField = 'field_name';
	
	//列表最大行数,子类中可重新定义
	public $listMaxRows = 20;
	
	//详细/编辑视图默认列数,子类中可重新定义
	public $defaultColumns = 3;
	
	//分栏定义,子类中定义
	//'分栏标签'=>Array('栏内列数',是否展开,Array(栏内字段列表))
	//public $blocks = Array('LBL_USER_BASE'=>Array('3',true,Array('user_name')));
	
	//枚举字段值,子类中定义
	//'字段名'=>Array(枚举值列表);
	//public $picklist = Array('field_name' => Array('值1','值2'),);
	
	//字段关联,子类中定义
	//public $associateTo = Array();
	
	//模块关联
	//public $associateBy = Array(
		//'name1' => Array('module','byfield'),	
	//);
	
	//记录权限关联字段名,子类中定义
	//public $shareField = 'shareid';
	

	
	/*成员函数定义*/
	function __construct()
	{
		if(!isset($this->baseTable)) die('模块类没有定义属性：baseTable。');
		if(!isset($this->fields)) die('模块类没有定义属性：fields。');
		if(!isset($this->listFields))
		{
			$this->listFields = array_keys($this->fields);
		}
		if(!isset($this->safeFields))
		{
			$this->safeFields = array_keys($this->fields);
		}
		if(!isset($this->editFields))
		{
			$this->editFields = array_keys($this->fields);
		}
		if(!isset($this->orderbyFields))
		{
			$this->orderbyFields = $this->listFields;
		}
		if(!isset($this->defaultOrder))
		{
			$this->defaultOrder = Array($this->orderbyFields[0],'ASC');
		}
		if(!isset($this->enteryField))
		{
			$this->enteryField = $this->listFields[0];
		}
		if(!isset($this->picklist))
		{
			$this->picklist = Array();
		}
		if(!isset($this->associateTo))
		{
			$this->associateTo = Array();
		}
		if(!isset($this->associateBy))
		{
			$this->associateBy = Array();
		}
		if(!isset($this->blocks))
		{
			$this->blocks = Array();
		}
		if(!isset($this->batchEditFields))
		{
			$this->batchEditFields = Array();
		}
		if(!isset($this->missEditFields))
		{
			$this->missEditFields = Array();
		}
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
		if($qwhere != '' && $fwhere != '') $where = "  (({$qwhere}) AND ({$fwhere})) ";
		elseif($qwhere != '') $where = "  ({$qwhere}) ";
		elseif($fwhere != '') $where = "  ({$fwhere}) ";	

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
	
	//获取查询记录数的sql命令
	private function _getSqlCountString($queryWhere,$filterWhere,$userids,$groupids)
	{
		
		$sql = "select count(*) from  {$this->baseTable} ";		
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
		if($where != '')
		{
			$sql .= " where {$where};";
		}	
		return $sql;
	}
	
	//获取查询记录的sql命令
	private function _getSqlQueryString($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows)
	{
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
		$order = $this-> _getSqlOrderbyString($orderby,$order);
		if($where != '') $where = ' where '.$where;
		$sql ="select * from  {$this->baseTable}  {$where} {$order} limit {$start},{$maxRows};"; 
		return sql;
	}
	
	//获取记录ID序列
	public function getRecordIDS($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows)
	{
		global $APP_ADODB;
		$order = $this-> _getSqlOrderbyString($orderby,$order);
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
		if($where != '') $where = ' where '.$where;
		$sql ="select id from  {$this->baseTable} {$where} {$order}  limit {$start},{$maxRows};"; 
		$result = $APP_ADODB->Execute($sql);
		$ids = '';
		if($result)
		{
			while(!$result->EOF)
			{
				if($ids == '')
				{
					$ids = $result->fields['id'];
				}
				else
				{
					$ids .= ','.$result->fields['id'];
				}	
				$result->MoveNext();	
			}
		}		
		return $ids;
	}
	
	//根据条件返回列表记录总数
	public function getListQueryRecordCount($queryWhere,$filterWhere,$userids,$groupids)
	{
		global $APP_ADODB;
		$result = $APP_ADODB->Execute($this->_getSqlCountString($queryWhere,$filterWhere,$userids,$groupids));
		if($result) return $result->fields[0];
		return false;
	}
	//根据条件返回列表显示的记录集
	public function getListQueryRecord($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows)
	{	
		global $APP_ADODB;
		$ids = $this->getRecordIDS($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$start,$maxRows);
		if(empty($ids)) return false;
		$order = $this-> _getSqlOrderbyString($orderby,$order);
		$sql = "select * from {$this->baseTable} where id IN ({$ids}) {$order};";
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getArray();
		return false;		
	}

	//返回一条记录
	public function getOneRecordset($id,$userids,$groupids)
	{
		global $APP_ADODB;
		$swhere = $this->_getSqlShareWhereString($userids,$groupids);
		if(empty($swhere))
		{
			$sql = "select * from   {$this->baseTable} where id = {$id} limit 1;";		
		}
		else
		{
			$sql = "select * from   {$this->baseTable} where id = {$id} AND ({$swhere}) limit 1;";		
		}	
		$result = $APP_ADODB->Execute($sql);
		if($result) return $result->getArray();
		return false;				
	}
	
	//返回上一条记录
	public function getPrevOneRecordsetID($id,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$operation='prev')
	{
		global $APP_ADODB;		
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);		
		$awhere = '';
		if(!empty($where))
		{
			$awhere = 'where '.$where;
			$where = 'AND '.$where;	
		}
		
 		$succ = false;	
		$sql = '';
		$cond = '<';
		$gorder  = 'ORDER BY id DESC';
		if($operation == 'next')
		{
			$cond = '>';
			$gorder  = 'ORDER BY id ASC';
		}
		
		if(empty($orderby) || empty($order) )
		{
			$sql = "select id from {$this->baseTable} where id{$cond}{$id}  {$where} {$gorder} limit 1;";
			$succ = true;	
		}
		elseif($order != 'ASC' && $order != 'DESC')
		{
			$sql = "select id from {$this->baseTable} where id{$cond}{$id}  {$where} {$gorder} limit 1;";
			$succ = true;	
		}
		elseif(isset($_SESSION[_SESSION_KEY]['detialview_record_current_offset'][_MODULE]))
		{
			if($operation == 'next')
			{
				$start = $_SESSION[_SESSION_KEY]['detialview_record_current_offset'][_MODULE] + 1;
			}
			else
			{
				$start = $_SESSION[_SESSION_KEY]['detialview_record_current_offset'][_MODULE] - 1;
			}
			$_SESSION[_SESSION_KEY]['detialview_record_current_offset'][_MODULE] = $start;
			$sql = "select id from {$this->baseTable}  {$awhere} ORDER BY {$orderby} {$order} limit {$start},1;";
			$succ = true;				
		}
		else
		{
			$sql1 = "select {$orderby} from {$this->baseTable} where id = {$id}";
			$result  = $APP_ADODB->Execute($sql1);
			if($result && !$result->EOF)
			{
				$val = $result->fields[$orderby];
				if($order == 'ASC')
				{
					$cond = '<=';
					//$gorder  = "ORDER BY {$orderby} DESC";
					if($operation == 'next')
					{
						$cond = '>=';
					//	$gorder  = "ORDER BY {$orderby} ASC";
					}					
					$gwhere = formatSqlWhere(Array(Array($orderby,$cond,$val,'')),$this->fields);
					$sql = "select count(*) from {$this->baseTable} where {$gwhere}  {$where} ;";					
				}
				else
				{
					$cond = '>=';
					//$gorder  = "ORDER BY {$orderby} ASC";
					if($operation == 'next') 
					{
						$cond = '<=';
						//$gorder  = "ORDER BY {$orderby} DESC";
					}	
					$gwhere = formatSqlWhere(Array(Array($orderby,$cond,$val,'')),$this->fields);
					$sql = "select count(*) from {$this->baseTable} where {$gwhere}  {$where} ;";					
				}

				$result = $APP_ADODB->Execute($sql);
				$bcount = $result->fields[0];
				if($operation == 'next')
				{
					
					$result = $APP_ADODB->Execute("select count(*) from {$this->baseTable}  {$awhere} ;");
					$acount = $result->fields[0];
					$start = $acount - $bcount;	
				}
				else
				{
					$start = $bcount -10;
					if($start<0) $start = 0;
				}
				
				while(true)
				{					
					$sql = "select id from {$this->baseTable}   {$awhere} ORDER BY {$orderby} {$order} limit {$start},10;";
					$result = $APP_ADODB->Execute($sql);
					if(!$result || $result->EOF) break;
					$offsetx = 0;
					while(!$result->EOF)
					{
						if($result->fields['id'] == $id)
						{	
							if($operation == 'next')
							{	
								$start += $offsetx +1;
							}
							else
							{
								$start += $offsetx -1;
							}
							$succ = true;
							break;	
						}
						$offsetx ++;
						$result->MoveNext();
					}
					
					if($succ)
					{
						if(!isset($_SESSION[_SESSION_KEY]['detialview_record_current_offset'])) $_SESSION[_SESSION_KEY]['detialview_record_current_offset'] = Array();
						$_SESSION[_SESSION_KEY]['detialview_record_current_offset'][_MODULE] = $start;
						$sql = "select id from {$this->baseTable}   {$awhere} ORDER BY {$orderby} {$order} limit {$start},1;";
						break;
					}	
					if($operation=='next')
					{
						$start +=10;
					}
					else
					{
						$start -=10;
					}
				}
				
				
			}
		
		}
		if(!$succ) return $id;

		$result = $APP_ADODB->Execute($sql);
		if($result && !$result->EOF)
		{
			return 	$result->fields['id'];
		}
		elseif(isset($_SESSION[_SESSION_KEY]['detialview_record_current_offset'][_MODULE]))
		{
			if($operation == 'next')
			{
				$_SESSION[_SESSION_KEY]['detialview_record_current_offset'][_MODULE] -- ;
			}
			else
			{
				$_SESSION[_SESSION_KEY]['detialview_record_current_offset'][_MODULE] ++;
			}			
			
		}
		return $id;
	}
	
	//返回下一条记录
	public function getNextOneRecordsetID($id,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids)
	{
		return $this->getPrevOneRecordsetID($id,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$operation='next');
	}	
	
	//更新一条记录
	public function updateOneRecordset($id,$userids,$groupids,$data)
	{
		global $APP_ADODB,$CURRENT_USER_ID;	
		$editFields = $this->editFields;  
		foreach($this->fields as $field=>$finfo)
		{
			if($finfo[0] == '36') 
			{
				$editFields[] = $field;
				$data[$field] = Date('Y-m-d H:i:s');
			}
			elseif($finfo[0] == '52')
			{
				$editFields[] = $field;
				$data[$field] = $CURRENT_USER_ID;				
			}
		}
		
		$sqlset = formatDataToUpdateSetSql($data,$this->fields,$editFields);
		$swhere = $this->_getSqlShareWhereString($userids,$groupids);
		if(empty($swhere))
		{
			$sql = "update {$this->baseTable} set {$sqlset} where id={$id} limit 1;";
		}
		else
		{
			$sql = "update {$this->baseTable} set {$sqlset} where id={$id} AND ({$swhere}) limit 1;";
		}	
		
		$ret = $APP_ADODB->Execute($sql);
		if(!$ret) return 1;
		return 0;
	}
	
	//插入一条记录
	public function insertOneRecordset($id,$data)
	{
		global $APP_ADODB,$CURRENT_USER_ID;
		$editFields = $this->editFields;  
		foreach($this->fields as $field=>$finfo)
		{
			if($finfo[0] == '35') 
			{
				$editFields[] = $field;
				$data[$field] = Date('Y-m-d H:i:s');
			}
			elseif($finfo[0] == '51')
			{
				$editFields[] = $field;
				$data[$field] = $CURRENT_USER_ID;				
			}
		}		
		$editFields[] = 'id';
		$data['id'] = $id;	
		$sqlset = formatDataToInsertValuesSql($data,$this->fields,$editFields);	
	    $sql = "insert into {$this->baseTable}{$sqlset};";
		$ret = $APP_ADODB->Execute($sql);
		if(!$ret) return 1;
		return 0;
	}
	
	//删除一条记录
	public function deleteOneRecordset($id,$userids,$groupids)
	{		
		global $APP_ADODB;		
		if(!empty($this->associateBy))
		{
			$rettext = '';
			foreach($this->associateBy as $assinfo)
			{
				$modname = $assinfo[0];
				$assfield = $assinfo[1];
				$modclass = "{$modname}Module";
				if(!class_exists($modclass))
				{
					if(is_file(_ROOT_DIR."/modules/{$modname}/{$modname}Module.class.php"))
					{
						require_once(_ROOT_DIR."/modules/{$modname}/{$modname}Module.class.php");
					}
				}
				if(class_exists($modclass))
				{
					$mod = new $modclass();
					$count = $mod->getListQueryRecordCount(Array(Array($assfield,'=',$id,'')),Array(),'','');
					if($count>0)
					{
						$rettext .= '<span style="color:#1E90FF;">'.getTranslatedString($modname)."</span>:{$count}条记录<br/>";
					}	
				}				
			}
			if(!empty($rettext))
			{
				return "你要删除的记录还有以下模块关联，不能被删除！<br/>".$rettext;
			}
		}
		
		$swhere = $this->_getSqlShareWhereString($userids,$groupids);
		if(empty($swhere))
		{
			$sql = "delete from {$this->baseTable} where id = {$id} limit 1;";
		}
		else
		{
			$sql = "delete from  {$this->baseTable} where id = {$id} AND ({$swhere}) limit 1;";
		}		
		$APP_ADODB->Execute($sql);
		return 0;
	}
	
	//批量删除
	function batchDelete($queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$deleteAssociateby,$iscount=true)
	{
		global $APP_ADODB;
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
		$assWhere = '';
		if(!$deleteAssociateby)
		{
			foreach($this->associateBy as $assinfo)
			{
				$modname = $assinfo[0];
				$assfield = $assinfo[1];
				$modclass = "{$modname}Module";
				if(!class_exists($modclass))
				{
					if(is_file(_ROOT_DIR."/modules/{$modname}/{$modname}Module.class.php"))
					{
						require_once(_ROOT_DIR."/modules/{$modname}/{$modname}Module.class.php");
					}
				}
				if(class_exists($modclass))
				{
					$mod = new $modclass();
					if(empty($assWhere)) $assWhere = " (id NOT IN (select {$assfield} from {$mod->baseTable})) ";
					else $assWhere .= " AND (id NOT IN (select {$assfield} from {$mod->baseTable})) ";
				}				
			}
		}
		if(!empty($where) && !empty($assWhere)) $where .= 'AND'.$assWhere;
		elseif(!empty($assWhere)) $where = $assWhere;
		$sql = "delete from {$this->baseTable} ";
		$sqlcount = "select count(*) from {$this->baseTable} ";
		if(!empty($where)) 
		{
			$sqlcount .= " where {$where} ";
			$sql .= " where {$where} ";
		}	
		if(!empty($limit))
		{			
			$orderstr = $this->_getSqlOrderbyString($orderby,$order);
			$sql .= " {$orderstr} limit {$limit} ";
		}

		$result = $APP_ADODB->Execute($sqlcount);
		$delcount = $result->fields[0];
		if(!empty($limit))
		{
			if($limit<$delcount) $delcount = $limit;
		}
		if(!$iscount)
		{
			$APP_ADODB->Execute($sql);
		}
		return $delcount;
	}
	
	//批量修改
	function batchModify($modFields,$queryWhere,$filterWhere,$orderby,$order,$userids,$groupids,$limit,$iscount=true,$datas=Array())
	{
		global $APP_ADODB,$CURRENT_USER_ID;	
		$where = $this->_getSqlWhereString($queryWhere,$filterWhere,$userids,$groupids);
		$sqlcount = "select count(*) from {$this->baseTable} ";
		if(!empty($where)) 
		{
			$sqlcount .= " where {$where} ";
		}	
		$result = $APP_ADODB->Execute($sqlcount);
		$modcount = $result->fields[0];
		if(!empty($limit))
		{
			if($limit<$modcount) $modcount = $limit;
		}			
		if($iscount)
		{
			return $modcount;		
		}
		
		foreach($this->fields as $field=>$finfo)
		{
			if($finfo[0] == '36') 
			{
				$modFields[] = $field;
				$datas[$field] = Date('Y-m-d H:i:s');
			}
			elseif($finfo[0] == '52')
			{
				$modFields[] = $field;
				$datas[$field] = $CURRENT_USER_ID;				
			}
		}
		$sqlset = formatDataToUpdateSetSql($datas,$this->fields,$modFields);
		$sql = "update {$this->baseTable} set {$sqlset} ";
		
		if(!empty($where)) 
		{
			$sql .= " where {$where} ";
		}	
		if(!empty($limit))
		{			
			$orderstr = $this->_getSqlOrderbyString($orderby,$order);
			$sql .= " {$orderstr} limit {$limit} ";
		}	
		$APP_ADODB->Execute($sql);
		
		return $APP_ADODB->affected_rows();
	}	


	//自动完成字段
	public function autoCompleteFieldValue($field,$pfx)
	{
		return $pfx.date('YmdHis');
	}
	
}
?>