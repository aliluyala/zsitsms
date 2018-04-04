<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$module = _MODULE;
$action = _ACTION;
if(!$CURRENT_IS_ADMIN && !validationActionPermission($module,$action))
{
	return_ajax('error','你的权限不能进行该项操作！');
	die();
}
$smarty = new ZS_Smarty();
global $mod;
if(!isset($mod) && is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
	require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
	$modclass= "{$module}Module";
	$mod = new $modclass();
}

$insurance;
$where = array();
if(isset($_GET['insurance']) && $_GET['insurance'] != "")
{
	$insurance = $_GET['insurance'];
	$where['insurance'] = $insurance;
}
else
{
	return_ajax('error','参数错误');
	die();
}
global $qidianSdk;
if(is_file(_ROOT_DIR."/webservices/qiDianapi.class.php"))
{
	require_once(_ROOT_DIR."/webservices/qiDianapi.class.php");
	$qidianmod= "qiDianapi";
	$qidianSdk = new $qidianmod();
}
else
{
	return_ajax('error','系统发生错误,请联系管理员');
	die();
}
$Insurances = array();
if(!isset($_POST['oper']) || $_POST['oper'] == "")
{
	$Insurances = $qidianSdk->getCliectSdk('Insurance.detail',$where,'GET');

	if(!$Insurances)
	{
		$error = $qidianSdk->getErrorMessage();
		return_ajax('error',$error);
		die();
	}


	$colNames = array();
	foreach($Insurances['data']['account_param'] as $key => $val)
	{
		$colNames[$val['key']]= $val['key'];
	}

	$accounts = array();

	foreach($Insurances['data']['accounts'] as $k => $v)
	{
		$accounts[$k] = $v;
	}

	foreach($accounts as $g => $h)
	{
		if($h['allot'] == 1)
		{
			$accounts[$g]['allot'] = "系统分配";
		}
		else
		{
			$accounts[$g]['allot'] = "自有账号";
		}
	}
	$array= array();
	foreach($accounts as $t => $s)
	{
		$array[$t]['id'] = $s['id'];
		$array[$t]['status'] = $s['status'];
		$array[$t]['allot'] = $s['allot'];
	}

	foreach($colNames as $m => $n)
	{
		foreach($accounts as $ke => $ye)
		{
			if(array_key_exists($m, $ye))
			{
				$array[$ke][$n] = $ye[$m];
			}
			else
			{
				$array[$ke][$n] = '系统默认';
			}
		}
	}
	/**
	 * [关闭系统分配的账号]
	 */
	foreach($array as $ul => $li)
	{
		if($li['allot'] == '系统分配')
		{
			unset($array[$ul]);
		}
	}

	if(empty($array))
	{
		foreach($Insurances['data']['account_param'] as $u => $l)
		{
			$array['id'] = "";
			$array['status'] = "";
			$array['allot'] = "";
			$array[$l['key']] = '';
		}
	}

	$icon['id'] = 'id';
	$icon['status'] = '账号状态';
	$icon['allot'] = '账号类型';
	foreach($Insurances['data']['account_param'] as $u => $l)
	{
			$icon[$l['key']] = $l['name'];
	}

	$smarty->assign("ICONS",json_encode($icon));
	$smarty->assign("DATAS",json_encode($array));
	$smarty->assign("INSURANCES",$_GET['insurance']);
	$smarty->display("PCSetting/InsuranceDetail.tpl");
}
$editwhere = array();
if(isset($_POST['oper']) && $_POST['oper'] == 'edit')
{
	$editwhere['insurance'] = $_GET['insurance'];
	$editwhere['account_id'] = $_POST['id'];
	unset($_POST['id']);
	unset($_POST['status']);
	unset($_POST['allot']);
	unset($_POST['oper']);
	$i = 0;
	foreach($_POST as $key => $val)
	{
		$i++;
		$editwhere['account']['param'.$i] = $val;
	}

	$Insurances = $qidianSdk->getCliectSdk('Insurance.updateAccount',$editwhere,'POST');
	if(!$Insurances)
	{
		$error = $qidianSdk->getErrorMessage();
		return_ajax('error',$error);
		die();
	}
	return_ajax('success',array());
	die();

}
else if(isset($_POST['oper']) && $_POST['oper'] == 'del')
{
	$editwhere['insurance'] = $_GET['insurance'];
	$editwhere['account_id'] = $_POST['delid'];
	$Insurances = $qidianSdk->getCliectSdk('Insurance.delAccount',$editwhere,'POST');
	if(!$Insurances)
	{
		$error = $qidianSdk->getErrorMessage();
		return_ajax('error',$error);
		die();
	}
	return_ajax('success',array());
	die();
}
else if(isset($_POST['oper']) && $_POST['oper'] == 'add')
{
	unset($_POST['id']);
	unset($_POST['status']);
	unset($_POST['allot']);
	unset($_POST['oper']);
	$i = 0;
	foreach($_POST as $key => $val)
	{
		$i++;
		$editwhere['account']['param'.$i] = $val;
	}
	$editwhere['insurance'] = $_GET['insurance'];
	$Insurances = $qidianSdk->getCliectSdk('Insurance.addAccount',$editwhere,'POST');
	if(!$Insurances)
	{
		$error = $qidianSdk->getErrorMessage();
		return_ajax('error',$error);
		die();
	}
	return_ajax('success',array());
	die();

}






