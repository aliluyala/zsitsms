<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;

$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);

if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	$smarty->assign('ERROR_MESSAGE','你的权限不能进行该操作！');
	$smarty->display('ErrorMessage.tpl');
	die();
}

global $mod;
if(!isset($mod) && is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();
}

require_once(_ROOT_DIR."/modules/RepertoryLog/RepertoryLogModule.class.php");
$rlogmod = new RepertoryLogModule();

if(empty($_GET['operation']))
{
	$fields = $mod->fields;
	$fields['supplier'] = Array('7','S',true,'供应商名称','',1,250,'250px','^.+$','');
	$fields['salesman'] = Array('7','S',true,'经办人','',1,50,'160px','^.+$','');
	$editFields = $mod->editFields;
	$editFields[] =  'supplier';
	$editFields[] =  'salesman';
	$editview_datas = createEditViewUI($module,$fields,$editFields,$editFields,$mod->defaultColumns,
									   $mod->blocks,$mod->associateTo,$mod->picklist,array('id'=>'-1'),'create');
	$smarty->assign('EDITVIEW_DATAS',$editview_datas);
	$tpl_file = 'Product/EntryView.tpl';
	$smarty->display($tpl_file);
}
elseif($_GET['operation'] == 'entry')
{	
	$where = array(
				array('code','=',$_POST['code'],'and'),
				array('repertory','=',$_POST['repertory'],'')
			 );	
	$reset = $mod->getListQueryRecord($where,null,null,null,null,null,0,10);
	$result = 1;
	
	$logdata = array(
		'code'       => $_POST['code'],
		'name'       => $_POST['name'],
	    'price'      => $_POST['price'],
	    'count'      => $_POST['count'],
	    'sum'        => 0,
	    'repertory'  => $_POST['repertory'],
	    'operation'  => 'ENTRY_REPERTORY',
	    'time'       => date('Y-m-d H:s:i'),
	    'userid'     => $CURRENT_USER_ID,
		'other'      => $_POST['supplier'],
		'salesman'   => $_POST['salesman'],
	);

	
	if($reset && count($reset)>0)
	{
		$id = $reset[0]['id'];
		$count = $reset[0]['count'];
		$price = $reset[0]['price'];
		$sum = $_POST['count']*$_POST['price'];
		$logdata['sum'] = $sum;
		$sum += $count*$price;
		$count += $_POST['count'];
		$price = $sum/$count;
		$_POST['count'] = $count;
		$_POST['price'] = round($price,2);	
		$_POST['name']     =  $reset[0]['name'];
		$_POST['standard'] =  $reset[0]['standard'];
        $logdata['name']      =  $reset[0]['name'];
		$logdata['standard']  =  $reset[0]['standard'];
		$result = $mod->updateOneRecordset($id,null,null,$_POST);
		
		
	}
	else
	{
		if(empty($_POST['name'] ) || empty($_POST['standard']))
		{
			return_ajax('error','新入库商品必须填写“名称”和“规格”！');
			die();
		}	
		$id  = getNewModuleSeq($mod->baseTable);
		$result = $mod->insertOneRecordset($id,$_POST);
		$logdata['sum'] = $_POST['count']*$_POST['price'];
		
	}
	$rlogmod->log($logdata);
	
	if($result === 0)
	{
		return_ajax('success',"入库成功");
		die();
	}
	else
	{
		return_ajax('error','写数据库失败！');
		die();		
	}
}

?>