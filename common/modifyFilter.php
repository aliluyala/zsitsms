<?php
global $APP_ADODB,$CURRENT_IS_ADMIN,$CURRENT_USER_ID;
$smarty = new ZS_Smarty();
$module = _MODULE;
$action = _ACTION;
$smarty->assign('MODULE',$module);
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}

$mod;
if(is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();	
}
$filters = Array();
$currentid = -1;
if(isset($_GET['filterid'])) $currentid = $_GET['filterid'];
$filterName = '';
$oper = '';
if(isset($_GET['oper']))
{
	$oper = $_GET['oper'];
}
if($oper == 'add')
{
	$result = $APP_ADODB->Execute('select id from filters order by id DESC limit 1;');
	if($result->EOF) $currentid = 1;
	else $currentid = $result->fields['id'] + 1;
	$queryWhere = Array();
	if(isset($_SESSION[_SESSION_KEY]['listview_query_where'][$module]))
	{
		$queryWhere = $_SESSION[_SESSION_KEY]['listview_query_where'][$module];
	}
	$whereJson = urlencode(json_encode($queryWhere));
	$sql = "insert into filters(id,name,userid,module_name,filter_where) ";
	$sql .= "values({$currentid},'新建过滤',{$CURRENT_USER_ID},'{$module}','{$whereJson}');";
	$APP_ADODB->Execute($sql);	
}
elseif($oper == 'save')
{
	if(!empty($_GET['name']))
	{
		$sql = "update filters set name='{$_GET['name']}' where id = {$currentid};";
		$APP_ADODB->Execute($sql);
	}
}
elseif($oper == 'delete')
{
	$sql = "delete from filters where id = {$currentid};";
	$APP_ADODB->Execute($sql);
	$currentid = -1;
}
$where = Array();
$sql = "select * from filters where userid={$CURRENT_USER_ID} and module_name='{$module}';";
$result = $APP_ADODB->Execute($sql);
while(!$result->EOF)
{
	if($currentid == -1) $currentid = $result->fields['id'];
	if($currentid == $result->fields['id'])
	{
		$filterName = $result->fields['name'];
		$where = json_decode(urldecode($result->fields['filter_where']),true);		
	}
	$filters[$result->fields['id']] = $result->fields['name']; 
	$result->MoveNext();
}
$filterWhere = Array();
foreach($where as $v)
{
	$onec  = Array();
	$onec[0] = getTranslatedString($v[0]);
	switch($v[1])
	{
		case '=':
			$onec[1] = '等于';
			break;			
		case '!=':
			$onec[1] = '不等于';
			break;
		case '>':
			$onec[1] = '大于';
			break;
		case '>=':
			$onec[1] = '大于等于';
			break;
		case '<':
			$onec[1] = '小于';
			break;
		case '<=':
			$onec[1] = '小于等于';
			break;
		case 'like_start':
			$onec[1] = '开始是';
			break;
		case 'like_end':
			$onec[1] = '结束是';
			break;	
		case 'like_contain':
			$onec[1] = '包含';
			break;	
		case 'like_no_contain':
			$onec[1] = '不包含';
			break;
		default:
			$onec[1] = '';
	}   
	if(!empty($v[4]))
	{
		$onec[2] = $v[4];
	}	
	elseif($mod->fields[$v[0]][1] == 'E')
	{
		$onec[2] = getTranslatedString($v[2]);
	}
	else
	{		
		$onec[2] = $v[2];
	}	
	$onec[3] = '';
	if($v[3] == 'and') $onec[3] = '与';
	elseif($v[3] == 'or') $onec[3] = '或';
	$filterWhere[] = $onec;
}      
$smarty->assign('FILTER_WHERE',$filterWhere);
$smarty->assign('FILTER_NAME',$filterName);
$smarty->assign('FILTER_WHERE_LIST',$filters);
$smarty->assign('CURRENT_FILTER_ID',$currentid);
$smarty->display('ModifyFilter.tpl');

?>