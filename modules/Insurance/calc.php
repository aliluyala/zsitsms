<?php
    require_once(_ROOT_DIR."/modules/Insurance/InsuranceModule.class.php");
    $focus = new InsuranceModule();
    $type = "picc";
    if(isset($_POST['insurance_company'])){
        $type = strtolower($_POST['insurance_company']);
    }
    require_once("common/Insurance/". $focus->areaCode ."_" . $type . "/Insurance.class.php");
    $mins  = new Insurance();
    $types = array();
    $types = $focus->_getData('buy_types',$_POST);
    $params = array();
    $params = array_change_key_case($_POST,CASE_UPPER);//将一维数组中的key转换为大写
    //覆盖部分参数
    $params['TYPE_AUTO']             = $focus->_getData('use_character',$_POST)."_".$focus->_getData('vehicle_type',$_POST);
    $params['MONTHS']                = 12;//默认为12月
    $params['COTY']                  = $focus->getCOTY(date("Y-m-d",strtotime($focus->_getData('other_start_time',$_POST))),$focus->_getData('register_date',$_POST));//车辆使用年限(车龄)
    $params['BGAI_INSURANCE_AMOUNT'] = $focus->_getData('purchase_price',$_POST);
    $params['LOAD']                  = $focus->_getData('ratify_load',$_POST);
    $params['STSFS_RATE']            = $focus->_getData('stsfs_rate',$_POST)/100;
    $params['DEPRECIATION_PRICE']    = round($mins->depreciation($params));
    $params['FLOATING_RATE']         = 'A4';//默认A4
    //UPDATE insurance_calculate_log SET tcpli_insurance_driver_amount = tcpli_insurance_driver_amount/10000,tcpli_insurance_passenger_amount = tcpli_insurance_passenger_amount/10000;
    $params['TCPLI_INSURANCE_DRIVER_AMOUNT']    = $focus->_getData('tcpli_insurance_driver_amount',$_POST) * 10000;
    $params['TCPLI_INSURANCE_PASSENGER_AMOUNT'] = $focus->_getData('tcpli_insurance_passenger_amount',$_POST) * 10000;

    $params['KERB_MASS'] = $focus->_getData('kerb_mass',$_POST) / 1000;

    //未指定驾驶人时 驾龄,性别,年龄不生效(因算价包中将未指定/指定驾驶人写反,所以lang中将DESIGNATED_DRIVER翻译为未指定)
    if($focus->_getData('designated_driver',$_POST) == "DESIGNATED_DRIVER"){
        $params['DRIVING_YEARS']     = "GREATER_3_YEARS";
        $params['DRIVER_AGE']        = "25_30_AGE";
        $params['DRIVER_SEX']        = "GREATER_3_YEARS";
    }

    $params['COTY']                  = $focus->getCOTY_tvdi(date("Y-m-d",strtotime($focus->_getData('other_start_time',$_POST))),$focus->_getData('register_date',$_POST));//车辆使用年限(车龄) for 车损险

    $data = array();
    if(!empty($types)){
        $data['BEFORE'] = $mins->buy($types,$params);
    }

    $data['DISCOUNT'] = $mins->discount($params);
    $data['DEPRECIATION_PRICE'] = $params['DEPRECIATION_PRICE'];
    $params['FLOATING_RATE'] = $focus->_getData('floating_rate',$_POST);
    if(!empty($types)){
        $data['AFTER'] = $focus->getDiscountPrice($mins->buy($types,$params),$data['DISCOUNT']);
        $data['COMMERCIAL_SUM'] = $focus->getCommercialSUM($data['AFTER']);
        $data['NET_SALES'] = round($data['COMMERCIAL_SUM'] * 0.85,2);
        $data['TOTAL_SUM'] = $focus->getTotalSUM($data['AFTER']);
        if(isset($data['AFTER']['MVTALCI']))
            $data['MVTALCI_SUM'] = $data['AFTER']['MVTALCI'];
        if(isset($data['AFTER']['TRAVEL_TAX']))
            $data['TRAVEL_TAX_SUM'] = $data['AFTER']['TRAVEL_TAX'];
    }

    return_ajax('result',$data);