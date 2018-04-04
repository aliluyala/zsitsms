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
	$flds = $mod->fields;
	$flds['account']  = Array('7','S',true,'客户信息','',1,250,'250px','^.+$','');
	$flds['salesman'] = Array('7','S',true,'经办人','',1,50,'160px','^.+$','');
	$editFields = $mod->editFields;
	$editFields[] =  'account';
	$editFields[] =  'salesman';
	unset($flds['name']);
	unset($flds['standard']);
	unset($flds['price']);

	$editview_datas = createEditViewUI($module,$flds,$editFields,$editFields,$mod->defaultColumns,
									   $mod->blocks,$mod->associateTo,$mod->picklist,array('id'=>'-1'),'create');
	$smarty->assign('EDITVIEW_DATAS',$editview_datas);
	$tpl_file = 'Product/OutView.tpl';
	$smarty->display($tpl_file);
}
elseif($_GET['operation'] == 'out')
{
	$where = array(
				array('code','=',$_POST['code'],'and'),
				array('repertory','=',$_POST['repertory'],'')
			 );
	$reset = $mod->getListQueryRecord($where,null,null,null,null,null,0,10);
	$result = 1;

	$logdata = array(
		'code'       => $_POST['code'],
		'name'       => '',
	    'price'      => '',
	    'count'      => $_POST['count'],
	    'sum'        => 0,
	    'repertory'  => $_POST['repertory'],
	    'operation'  => 'OUT_REPERTORY',
	    'time'       => date('Y-m-d H:s:i'),
	    'userid'     => $CURRENT_USER_ID,
		'other'      => $_POST['account'],
		'salesman'   => $_POST['salesman'],
	);


	if($reset && count($reset)>0)
	{
		$id = $reset[0]['id'];
		$name  = $reset[0]['name'];
		$count = $reset[0]['count'];
		$price = $reset[0]['price'];
		$state = $reset[0]['state'];
		if($count < $_POST['count'] && $state == "NORMAL")
		{
			return_ajax('error',"库存数量不足,当前库存:{$count}.");
			die();
		}
		if($state == "FORBIDDEN")
		{
			return_ajax('error',"商品:{$name}已被禁用.");
			die();
		}


		$logdata['sum'] = $_POST['count']*$price;
		$count -= $_POST['count'];
		$_POST['count'] = $count;
		if($state == "UNLIMITED") {
			$_POST['count'] = 0;
		}
		$_POST['price'] = $price;
		$_POST['name']     =  $reset[0]['name'];
		$_POST['standard'] =  $reset[0]['standard'];
		$_POST['state']    =  $state;
		$logdata['price'] = $price;
        $logdata['name']      =  $reset[0]['name'];
		$logdata['standard']  =  $reset[0]['standard'];
		$result = $mod->updateOneRecordset($id,null,null,$_POST);


	}
	else
	{
		return_ajax('error',"编码{$_POST['code']}的库存商品不存在!");
		die();
	}

	$rlogmod->log($logdata);

	if($result === 0)
	{
		return_ajax('success',"出库成功");
		die();
	}
	else
	{
		return_ajax('error','写数据库失败！');
		die();
	}
}

?>