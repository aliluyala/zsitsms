<?php
$operation = 'edit';
$tpl_file  = 'Insurance/EditView.tpl';
//`````````````````
$return_module = "Accounts";
$return_action = "detailView";
$return_recordid = $_GET['fieldvalue'];
//`````````````````
global $APP_ADODB,$CURRENT_USER_NAME,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if(!isset($module)) $module = _MODULE;
if(!isset($action)) $action = _ACTION;
$module_label =  getTranslatedString($module);
$action_label =  getTranslatedString($action);
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



global $mod;
if(!isset($mod) && is_file(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php"))
{
    require_once(_ROOT_DIR."/modules/{$module}/{$module}Module.class.php");
    $modclass= "{$module}Module";
    $mod = new $modclass();
}
$operation = isset($_GET['recordid']) && !empty($_GET['recordid']) ? 'edit' : 'create';

global $operation,$recordid,$new_recordid;
if(!isset($operation)) $operation = 'edit';
$smarty->assign('OPERATION',$operation);

if($operation == 'create' && !isset($recordid))
{
    $recordid  = getNewModuleSeq($mod->baseTable);
}

if($operation == 'copy' && !isset($new_recordid))
{
    $new_recordid = getNewModuleSeq($mod->baseTable);
}

//记录ID

if(!isset($recordid))
{
    $recordid = $_GET['recordid'];
}
$smarty->assign('RECORDID',$recordid);


if(!isset($new_recordid)) $new_recordid = $recordid;
$smarty->assign('NEW_RECORDID',$new_recordid);



global $return_module;
if(!isset($return_module))
{
    if(isset($_GET['return_module'])) $return_module = $_GET['return_module'];
    else $return_module = $module;
}

global $return_action;
if(!isset($return_action))
{
    if(isset($_GET['return_action'])) $return_action = $_GET['return_action'];
    else $return_action = 'index';
}

global $return_recordid;
if(!isset($return_recordid))
{
    if(isset($_GET['return_recordid'])) $return_recordid = $_GET['return_recordid'];
    else $return_recordid = $recordid;
}

$smarty->assign('RETURN_MODULE',$return_module);
$smarty->assign('RETURN_ACTION',$return_action);
$smarty->assign('RETURN_RECORDID',$return_recordid);

global $focus;
if(!isset($focus) && is_file(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php"))
{
    require_once(_ROOT_DIR."/modules/Accounts/AccountsModule.class.php");
    $focus   = new AccountsModule();
}
$info = array(array());
$accountid = 0;
if(isset($_GET['fieldvalue']) && !empty($_GET['fieldvalue'])){
    $accountid = $_GET['fieldvalue'];
    $info  = $focus->getOneRecordset($_GET['fieldvalue'],
                                     $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission("Accounts"),
                                     $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission("Accounts")
                                    );
}
$result = array(array());
//$mod->getLastRecordID();//获取最近一条算价纪录ID

if(isset($_GET['recordid']) && !empty($_GET['recordid'])){
    $result = $mod->getOneRecordset($_GET['recordid'],
                                    $CURRENT_IS_ADMIN?NULL:getRecordset2UsersPermission($module),
                                    $CURRENT_IS_ADMIN?NULL:getRecordset2GroupsPermission($module)
                                   );
}

$calculate_no_val                 = $mod->_getData("calculate_no",$result[0]);
$calculate_no_val                 = $calculate_no_val ? $calculate_no_val : $mod->autoCompleteFieldValue($mod->fields['calculate_no'],$mod->fields['calculate_no'][4]);

$autoid                           = createFieldUI('autoid',$mod->fields['autoid'],$accountid,null,$mod->associateTo['autoid']);
$calculate_no                     = createFieldUI('calculate_no',$mod->fields['calculate_no'],$calculate_no_val,null,Array());

$last_policy                      = createFieldUI('last_policy',$focus->fields['last_policy'],$mod->_getData('last_policy',$info[0]),null,Array());
$purchase_price                   = createFieldUI('purchase_price',$focus->fields['purchase_price'],$mod->_getData('purchase_price',$info[0]),null,Array());
$plate_no                         = createFieldUI('plate_no',$focus->fields['plate_no'],$mod->_getData('plate_no',$info[0]),null,Array());
$vehicle_type                     = createFieldUI('vehicle_type',$focus->fields['vehicle_type'],$mod->_getData('vehicle_type',$info[0]),$mod->picklist['vehicle_type'],Array());
$use_character                    = createFieldUI('use_character',$focus->fields['use_character'],$mod->_getData('use_character',$info[0]),$mod->picklist['use_character'],Array());
$model                            = createFieldUI('model',$focus->fields['model'],$mod->_getData('model',$info[0]),null,Array());
$vin                              = createFieldUI('vin',$focus->fields['vin'],$mod->_getData('vin',$info[0]),null,Array());
$engine_no                        = createFieldUI('engine_no',$focus->fields['engine_no'],$mod->_getData('engine_no',$info[0]),null,Array());
$register_date                    = createFieldUI('register_date',$focus->fields['register_date'],$mod->_getData('register_date',$info[0]),null,Array());
$register_address                 = createFieldUI('register_address',$focus->fields['register_address'],$mod->_getData('register_address',$info[0]),null,Array());
$seats                            = createFieldUI('seats',$focus->fields['seats'],$mod->_getData('seats',$info[0]),null,Array());
$kerb_mass                        = createFieldUI('kerb_mass',$focus->fields['kerb_mass'],$mod->_getData('kerb_mass',$info[0]),null,Array());
$total_mass                       = createFieldUI('total_mass',$focus->fields['total_mass'],$mod->_getData('total_mass',$info[0]),null,Array());
$ratify_load                      = createFieldUI('ratify_load',$focus->fields['ratify_load'],$mod->_getData('ratify_load',$info[0]),null,Array());
$tow_mass                         = createFieldUI('tow_mass',$focus->fields['tow_mass'],$mod->_getData('tow_mass',$info[0]),null,Array());
$engine                           = createFieldUI('engine',$focus->fields['engine'],$mod->_getData('engine',$info[0]),null,Array());
$power                            = createFieldUI('power',$focus->fields['power'],$mod->_getData('power',$info[0]),null,Array());
$body_size                        = createFieldUI('body_size',$focus->fields['body_size'],$mod->_getData('body_size',$info[0]),null,Array());
$body_color                       = createFieldUI('body_color',$focus->fields['body_color'],$mod->_getData('body_color',$info[0]),null,Array());
$origin                           = createFieldUI('origin',$focus->fields['origin'],$mod->_getData('origin',$info[0]),$mod->picklist['origin'],Array());



$mvtalci_sum                      = createFieldUI('mvtalci_sum',$mod->fields['mvtalci_sum'],null,null,Array());
$travel_tax_sum                   = createFieldUI('travel_tax_sum',$mod->fields['travel_tax_sum'],null,null,Array());
$commercial_sum                   = createFieldUI('commercial_sum',$mod->fields['commercial_sum'],null,null,Array());
$net_sales                        = createFieldUI('net_sales',$mod->fields['net_sales'],null,null,Array());



$insurance_company                = createFieldUI('insurance_company',$mod->fields['insurance_company'],$mod->_getData("insurance_company",$result[0]),$mod->picklist['insurance_company'],Array());
$mvtalci_start_time               = createFieldUI('mvtalci_start_time',$mod->fields['mvtalci_start_time'],$mod->_getData("mvtalci_start_time",$result[0]),null,Array());
$other_start_time_val             = $mod->_getData("other_start_time",$result[0]);
$other_start_time_val             = $other_start_time_val ? $other_start_time_val : date("Y-m-d H:i:s",time());
$other_start_time                 = createFieldUI('other_start_time',$mod->fields['other_start_time'],$other_start_time_val,null,Array());



$floating_rate                    = createFieldUI('floating_rate',$mod->fields['floating_rate'],$mod->_getData("floating_rate",$result[0]),$mod->picklist['floating_rate'],Array());
$designated_driver                = createFieldUI('designated_driver',$mod->fields['designated_driver'],$mod->_getData("designated_driver",$result[0]),$mod->picklist['designated_driver'],Array());
$driving_years                    = createFieldUI('driving_years',$mod->fields['driving_years'],$mod->_getData("driving_years",$result[0]),$mod->picklist['driving_years'],Array());
$driver_age                       = createFieldUI('driver_age',$mod->fields['driver_age'],$mod->_getData("driver_age",$result[0]),$mod->picklist['driver_age'],Array());
$driver_sex                       = createFieldUI('driver_sex',$mod->fields['driver_sex'],$mod->_getData("driver_sex",$result[0]),$mod->picklist['driver_sex'],Array());
$driving_area                     = createFieldUI('driving_area',$mod->fields['driving_area'],$mod->_getData("driving_area",$result[0]),$mod->picklist['driving_area'],Array());
$average_annual_mileage           = createFieldUI('average_annual_mileage',$mod->fields['average_annual_mileage'],$mod->_getData("average_annual_mileage",$result[0]),$mod->picklist['average_annual_mileage'],Array());
$claim_records                    = createFieldUI('claim_records',$mod->fields['claim_records'],$mod->_getData("claim_records",$result[0]),$mod->picklist['claim_records'],Array());
$years_of_insurance               = createFieldUI('years_of_insurance',$mod->fields['years_of_insurance'],$mod->_getData("years_of_insurance",$result[0]),$mod->picklist['years_of_insurance'],Array());
$multiple_insurance               = createFieldUI('multiple_insurance',$mod->fields['multiple_insurance'],$mod->_getData("multiple_insurance",$result[0]),$mod->picklist['multiple_insurance'],Array());


$discount                         = createFieldUI('discount',$mod->fields['discount'],null,null,Array());


$nieli_insurance_amount           = createFieldUI('nieli_insurance_amount',$mod->fields['nieli_insurance_amount'],$mod->_getData("nieli_insurance_amount",$result[0]),null,Array());
$tvdi_insurance_amount            = createFieldUI('tvdi_insurance_amount',$mod->fields['tvdi_insurance_amount'],$mod->_getData('purchase_price',$info[0]),null,Array());
$ttbli_insurance_amount           = createFieldUI('ttbli_insurance_amount',$mod->fields['ttbli_insurance_amount'],$mod->_getData("ttbli_insurance_amount",$result[0]),$mod->picklist['ttbli_insurance_amount'],Array());
$twcdmvi_insurance_amount         = createFieldUI('twcdmvi_insurance_amount',$mod->fields['twcdmvi_insurance_amount'],null,null,Array());
$bsdi_insurance_amount            = createFieldUI('bsdi_insurance_amount',$mod->fields['bsdi_insurance_amount'],$mod->_getData("bsdi_insurance_amount",$result[0]),$mod->picklist['bsdi_insurance_amount'],Array());
//$bgai_insurance_amount                    = createFieldUI('bgai_insurance_amount',$mod->fields['bgai_insurance_amount'],null,null,Array());
$tcpli_insurance_driver_amount    = createFieldUI('tcpli_insurance_driver_amount',$mod->fields['tcpli_insurance_driver_amount'],$mod->_getData("tcpli_insurance_driver_amount",$result[0]),null,Array());
$tcpli_insurance_passenger_amount = createFieldUI('tcpli_insurance_passenger_amount',$mod->fields['tcpli_insurance_passenger_amount'],$mod->_getData("tcpli_insurance_passenger_amount",$result[0]),null,Array());
$passengers                       = createFieldUI('passengers',$mod->fields['passengers'],$mod->_getData("passengers",$result[0]),null,Array());
$glass_origin                     = createFieldUI('glass_origin',$mod->fields['glass_origin'],$mod->_getData("glass_origin",$result[0]),$mod->picklist['glass_origin'],Array());
$stsfs_rate                       = createFieldUI('stsfs_rate',$mod->fields['stsfs_rate'],$mod->_getData("stsfs_rate",$result[0]),null,Array());



$smarty->assign("ACCOUNTID",$accountid);
$smarty->assign("AUTOID",$autoid);



$smarty->assign("NIELI_INSURANCE_AMOUNT",$nieli_insurance_amount);
$smarty->assign("TVDI_INSURANCE_AMOUNT",$tvdi_insurance_amount);
$smarty->assign("TTBLI_INSURANCE_AMOUNT",$ttbli_insurance_amount);
$smarty->assign("TWCDMVI_INSURANCE_AMOUNT",$twcdmvi_insurance_amount);
$smarty->assign("BSDI_INSURANCE_AMOUNT",$bsdi_insurance_amount);
//$smarty->assign("BGAI_INSURANCE_AMOUNT",$bgai_insurance_amount);
$smarty->assign("TCPLI_INSURANCE_DRIVER_AMOUNT",$tcpli_insurance_driver_amount);
$smarty->assign("TCPLI_INSURANCE_PASSENGER_AMOUNT",$tcpli_insurance_passenger_amount);
$smarty->assign("PASSENGERS",$passengers);
$smarty->assign("GLASS_ORIGIN",$glass_origin);
$smarty->assign("STSFS_RATE",$stsfs_rate);



$smarty->assign("DISCOUNT",$discount);


$smarty->assign("MVTALCI_SUM",$mvtalci_sum);
$smarty->assign("TRAVEL_TAX_SUM",$travel_tax_sum);
$smarty->assign("COMMERCIAL_SUM",$commercial_sum);
$smarty->assign("NET_SALES",$net_sales);





$smarty->assign("CALCULATE_NO",$calculate_no);
$smarty->assign("LAST_POLICY",$last_policy);
$smarty->assign("PURCHASE_PRICE",$purchase_price);
$smarty->assign("PLATE_NO",$plate_no);
$smarty->assign("VEHICLE_TYPE",$vehicle_type);
$smarty->assign("USE_CHARACTER",$use_character);
$smarty->assign("MODEL",$model);
$smarty->assign("VIN",$vin);
$smarty->assign("ENGINE_NO",$engine_no);
$smarty->assign("REGISTER_DATE",$register_date);
$smarty->assign("REGISTER_ADDRESS",$register_address);
$smarty->assign("SEATS",$seats);
$smarty->assign("KERB_MASS",$kerb_mass);
$smarty->assign("TOTAL_MASS",$total_mass);
$smarty->assign("RATIFY_LOAD",$ratify_load);
$smarty->assign("TOW_MASS",$tow_mass);
$smarty->assign("ENGINE",$engine);
$smarty->assign("POWER",$power);
$smarty->assign("BODY_SIZE",$body_size);
$smarty->assign("BODY_COLOR",$body_color);
$smarty->assign("ORIGIN",$origin);

$smarty->assign("COMPANY",$insurance_company);
$smarty->assign("MVTALCI_START_TIME",$mvtalci_start_time);
$smarty->assign("OTHER_START_TIME",$other_start_time);

$smarty->assign("FLOATING_RATE",$floating_rate);
$smarty->assign("DESIGNATED_DRIVER",$designated_driver);
$smarty->assign("DRIVING_YEARS",$driving_years);
$smarty->assign("DRIVER_AGE",$driver_age);
$smarty->assign("DRIVER_SEX",$driver_sex);
$smarty->assign("DRIVING_AREA",$driving_area);
$smarty->assign("AVERAGE_ANNUAL_MILEAGE",$average_annual_mileage);
$smarty->assign("CLAIM_RECORDS",$claim_records);
$smarty->assign("YEARS_OF_INSURANCE",$years_of_insurance);
$smarty->assign("MULTIPLE_INSURANCE",$multiple_insurance);

$smarty->assign("BUY_TYPES",$mod->_getData("buy_types",$result[0]));


global $tpl_file;
if(!isset($tpl_file)) $tpl_file = 'EditView.tpl';
$smarty->display($tpl_file);
?>