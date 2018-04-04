<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();

$fields = array(
	'product_id'                               => Array('50','N',true,'商品ID'),
	'product_code'                             => Array('5','S',true,'商品编码','',5,20,'160px','^[0-9a-zA-Z\-_]+$','商品代码由字母数字及-,_组成。'),
	'product_name'                             => Array('7','S',false,'商品名称','',1,250,'250px','^.+$',''),
	'product_standard'                         => Array('7','S',false,'商品规格','',1,250,'300px','^.+$',''),
	'product_price'                            => Array('8','N',false,'商品单价','0.00',0,999999999999,'70px','元',2),
	'product_count'                            => Array('8','N',true,'数量','0',0,999999999999,'70px','',0),
);
$editFields = array(
	'product_id'          ,
	//'product_code'        , 
	//'product_name'      , 
	//'product_standard'  , 
	//'product_price'     , 
	'product_count'       , 

);

$lang = array(
	'product_id'       => '商品名称',    
    'product_code'     => '编码', 
    'product_name'     => '名称', 
    'product_standard' => '规格', 
    'product_price'    => '单价', 
    'product_count'    => '数量', 
);

appendModuleStrings($lang);

$associateTo = array(
	'product_id' => array('MODULE','Product','detailView','id','name' ,array(),array('code','name','standard','price')),
);

$result = array('id'=>0,'product_count'=>1);			
$editview_datas = createEditViewUI('GiftPack',$fields,$editFields,$editFields,1,
									   null,$associateTo,null,$result,'edit');


$smarty->assign('EDITVIEW_DATAS',$editview_datas);
$smarty->display('GiftPack/AddProductView.tpl');
?>