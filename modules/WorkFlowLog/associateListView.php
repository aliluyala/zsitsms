<?php
if(!empty($_GET['associateField'])&&$_GET['associateField']=='insuranceOrder_id')
{
	require_once(_ROOT_DIR.'/modules/InsuranceOrderCom/InsuranceOrderComModule.class.php');
	$iomod = new InsuranceOrderComModule();
	$result = $iomod->getOneRecordset($_GET['fieldvalue'],null,null);
	if(!empty($result[0]))
	{
		$_GET['associateField'] = 'case_id';
		$_GET['fieldvalue'] = $result[0]['case_id'];
	}
}

require_once(_ROOT_DIR.'/common/associateListView.php');
?>