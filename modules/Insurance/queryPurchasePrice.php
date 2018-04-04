<?php
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
//工具条
global $toolsbar;
if(!isset($toolsbar)) $toolsbar = createToolsbar(Array('calendar','calculator','email','sms','phone'));
$smarty->assign('TOOLSBAR',$toolsbar);
$smarty->assign('MODULE',$module);
$smarty->assign('ACTION',$action);
$smarty->assign('MODULE_LABEL',$module_label);
$smarty->assign('ACTION_LABEL',$action_label);
global $show_module_label;
if(!isset($show_module_label)) $show_module_label = true;
$smarty->assign('TITLEBAR_SHOW_MODULE_LABEL',$show_module_label);
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
$focus = $mod->getVehicleObj();
$model = NULL;
if(!empty($_GET['model'])) $model = $_GET['model'];
$page = 1;
if(!empty($_GET['page'])) $page = $_GET['page'];
$model = $focus->resolveModel($model);
$post_data = array(
        'vehicleName'       => "{$model}",
        '_search'           => false,
        'nd'                => '1423048648369',
        'rows'              => 15,
        'page'              => $page,
        'sidx'              => 'vehiclePrice',//按新车购置价排序,默认为空
        'sord'              => 'asc',
        'searchCode'        => '',
        'vehiclePriceBegin' => '',
        'vehiclePriceEnd'   => '',
        'vehicleBrand'      => '',
        'vehicleId'         => '',
        'vinCode'           => '',
        'vehicleSeries'     => '',
        'vehicleMaker'      => '',
    );
$info = $focus->go($post_data);
$vehicleInfo = $info ? $info : array('total' => 0,'page' => 0,'totalPage' => 0);
$vehicleInfo = array_merge($vehicleInfo,$focus->getPageNum($vehicleInfo['page'],$vehicleInfo['totalPage']));
$smarty->assign("SEARCH_VALUE",$model);
$smarty->assign("VEHICLEINFO",$vehicleInfo);

$smarty->display('Insurance/queryPurchasePrice.tpl');
?>