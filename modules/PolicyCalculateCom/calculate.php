<?php
global $APP_ADODB, $CURRENT_USER_NAME, $CURRENT_USER_ID, $CURRENT_USER_GROUPID, $CURRENT_IS_ADMIN;
$smarty = new ZS_Smarty();
if (!isset($module)) {
    $module = _MODULE;
}

if (!isset($action)) {
    $action = _ACTION;
}

require_once _ROOT_DIR . "/modules/{$module}/{$module}Module.class.php";
$classname = "{$module}Module";
$mod = new $classname();

if (!array_key_exists('form', $_POST) || !array_key_exists('AUTO', $_POST['form'])) {
    return_ajax('error', '算价参数错误！');
    die();
}

if (!array_key_exists('BUSINESS_ITEMS', $_POST['form']) && !array_key_exists('MVTALCI_SELECT', $_POST['form'])) {
    die();
}

$auto = $_POST['form']['AUTO'];
$mvtalci = array();

$auto['MODEL_ALIAS'] = $_POST['form']['AUTO']['MODEL_ALIAS'];


$auto['IDENTIFY_NO'] = '';
if (!empty($_POST['form']['HOLDER']['HOLDER_IDENTIFY_NO'])) {
    $auto['IDENTIFY_NO'] = $_POST['form']['HOLDER']['HOLDER_IDENTIFY_NO'];
}

if (empty($_POST['form']['AUTO']['LICENSE_NO'])) {
    return_ajax('error', '车牌号不能是空！');
    die();
}
if (empty($_POST['form']['HOLDER']['HOLDER'])) {
    return_ajax('error', '被保险人不能是空！');
    die();
} else {
    $auto['OWNER'] = $_POST['form']['HOLDER']['HOLDER'];
}
if (empty($_POST['form']['AUTO']['VIN_NO'])) {
    return_ajax('error', '车辆识别码不能是空！');
    die();
}
if (empty($_POST['form']['AUTO']['ENGINE_NO'])) {
    return_ajax('error', '发动机号不能是空！');
    die();
}
if (empty($_POST['form']['AUTO']['ENGINE'])) {
    return_ajax('error', '排量不能是空！');
    die();
}
if (empty($_POST['form']['AUTO']['SEATS'])) {
    return_ajax('error', '核定载客不能是空！');
    die();
}
if (empty($_POST['form']['AUTO']['MODEL_CODE'])) {
    return_ajax('error', '型号代码不能是空！');
    die();
}
if (empty($_POST['form']['AUTO']['BUYING_PRICE'])) {
    return_ajax('error', '新车购置价不能是空！');
    die();
}
if (empty($_POST['form']['AUTO']['ENROLL_DATE'])) {
    return_ajax('error', '注册时间不能是空！');
    die();
}




if (array_key_exists('MVTALCI_SELECT', $_POST['form'])) {
    if (isset($_POST['form']['POLICY']['FLOATING_RATE'])) {
        $mvtalci['FLOATING_RATE'] = $_POST['form']['POLICY']['FLOATING_RATE'];
    }

    $mvtalci['MVTALCI_START_TIME'] = $_POST['form']['POLICY']['MVTALCI_START_TIME'];
    $mvtalci['MVTALCI_END_TIME'] = $_POST['form']['POLICY']['MVTALCI_END_TIME'];
    if (strtotime($mvtalci['MVTALCI_START_TIME']) < time()) {
        return_ajax('error', '交强险开始时间不能在当前时间以前！');
        die();
    }
    if (strtotime($mvtalci['MVTALCI_START_TIME']) > strtotime($mvtalci['MVTALCI_END_TIME'])) {
        return_ajax('error', '交强险开始时间不能在结束时间以后！');
        die();
    }
}

$business = array(
    'DESIGNATED_DRIVER' => array(),
    'DISCOUNT_VARS' => array(),
    'POLICY' => array(),
    'BUSINESS_START_TIME' => '',
    'BUSINESS_END_TIME' => '',
);

for ($idx = 1; $idx <= 3; $idx++) {
    if (array_key_exists('DESIGNATED_DRIVER' . $idx, $_POST['form']) && $_POST['form']['DESIGNATED_DRIVER' . $idx] == 'YES') {
        $business['DESIGNATED_DRIVER'][] = array(
            'DRIVER_NAME' => $_POST['form']['POLICY']['DRIVER_NAME' . $idx],
            'DRIVING_LICENCE_NO' => $_POST['form']['POLICY']['DRIVING_LICENCE_NO' . $idx],
            'DRIVER_ALLOW_DRIVE' => $_POST['form']['POLICY']['DRIVER_ALLOW_DRIVE' . $idx],
            'DRIVER_SEX' => $_POST['form']['POLICY']['DRIVER_SEX' . $idx],
            'DRIVER_AGE' => $_POST['form']['POLICY']['DRIVER_AGE' . $idx],
            'DRIVING_YEARS' => $_POST['form']['POLICY']['DRIVING_YEARS' . $idx],
        );
    }
}

if (isset($_POST['form']['POLICY']['YEARS_OF_INSURANCE'])) {
    $business['DISCOUNT_VARS']['YEARS_OF_INSURANCE'] = $_POST['form']['POLICY']['YEARS_OF_INSURANCE'];
    $business['DISCOUNT_VARS']['CLAIM_RECORDS'] = $_POST['form']['POLICY']['CLAIM_RECORDS'];
    $business['DISCOUNT_VARS']['DRIVING_AREA'] = $_POST['form']['POLICY']['DRIVING_AREA'];
    $business['DISCOUNT_VARS']['AVERAGE_ANNUAL_MILEAGE'] = $_POST['form']['POLICY']['AVERAGE_ANNUAL_MILEAGE'];
    $business['DISCOUNT_VARS']['MULTIPLE_INSURANCE'] = $_POST['form']['POLICY']['MULTIPLE_INSURANCE'];
}

$business['POLICY']['BUSINESS_ITEMS'] = array();
if (array_key_exists('BUSINESS_ITEMS', $_POST['form'])) {
    $business['POLICY']['BUSINESS_ITEMS'] = $_POST['form']['BUSINESS_ITEMS'];
}
$business['BUSINESS_START_TIME'] = $_POST['form']['POLICY']['BUSINESS_START_TIME'];
$business['BUSINESS_END_TIME'] = $_POST['form']['POLICY']['BUSINESS_END_TIME'];
if (strtotime($business['BUSINESS_START_TIME']) < time()) {
    return_ajax('error', '商业险开始时间不能在当前时间以前！');
    die();
}
if (strtotime($business['BUSINESS_START_TIME']) > strtotime($business['BUSINESS_END_TIME'])) {
    return_ajax('error', '商业险开始时间不能在结束时间以后！');
    die();
}

$business['POLICY']['TVDI_INSURANCE_AMOUNT'] = '';
$business['POLICY']['DOC_AMOUNT'] = '';
$business['POLICY']['TTBLI_INSURANCE_AMOUNT'] = '';
$business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'] = '';
$business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'] = '';
$business['POLICY']['TCPLI_PASSENGER_COUNT'] = '';
$business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'] = '';
$business['POLICY']['BSDI_INSURANCE_AMOUNT'] = '';
$business['POLICY']['SLOI_INSURANCE_AMOUNT'] = '';
$business['POLICY']['GLASS_ORIGIN'] = '';
$business['POLICY']['NIELI_INSURANCE_AMOUNT'] = '';
$business['POLICY']['STSFS_RATE'] = '';
$business['POLICY']['VWTLI_INSURANCE_AMOUNT'] = '';

$business['POLICY']['RDCCI_INSURANCE_AMOUNT'] = '';
$business['POLICY']['RDCCI_INSURANCE_QUANTITY'] = '';
$business['POLICY']['RDCCI_INSURANCE_UNIT'] = '';

foreach ($business['POLICY'] as $key => $v) {
    if (array_key_exists($key, $_POST['form']['POLICY'])) {
        $business['POLICY'][$key] = $_POST['form']['POLICY'][$key];
    }
}
if (!empty($_POST['form']['POLICY']['NIELI_DEVICE_LIST'])) {
    $business['POLICY']['NIELI_DEVICE_LIST'] = json_decode(urldecode($_POST['form']['POLICY']['NIELI_DEVICE_LIST']), true);
}

if (in_array('TVDI', $business['POLICY']['BUSINESS_ITEMS'])) {
    if (empty($business['POLICY']['TVDI_INSURANCE_AMOUNT'])) {
        return_ajax('error', '请输入车损险保额！');
        die();
    } elseif ($business['POLICY']['TVDI_INSURANCE_AMOUNT'] > $auto['BUYING_PRICE']) {
        return_ajax('error', '车损险保额不能大于新车购置价！');
        die();
    }
}
if (in_array('TWCDMVI', $business['POLICY']['BUSINESS_ITEMS'])) {
    if (empty($business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'])) {
        return_ajax('error', '请输入盗抢险保额！');
        die();
    } elseif ($business['POLICY']['TWCDMVI_INSURANCE_AMOUNT'] > $auto['BUYING_PRICE']) {
        return_ajax('error', '盗抢险保额不能大于新车购置价！');
        die();
    }
}
if (in_array('TCPLI_DRIVER', $business['POLICY']['BUSINESS_ITEMS'])) {
    if (empty($business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'])) {
        return_ajax('error', '请输入车上人员责任险(司机)保额！');
        die();
    } elseif ($business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'] < 1000 || ($business['POLICY']['TCPLI_INSURANCE_DRIVER_AMOUNT'] % 1000) != 0) {
        return_ajax('error', '车上人员责任险(司机)保额最小1000,并且必须是1000的整数倍！');
        die();
    }
}
if (in_array('TCPLI_PASSENGER', $business['POLICY']['BUSINESS_ITEMS'])) {
    if (empty($business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'])) {
        return_ajax('error', '请输入车上人员责任险(乘客)保额！');
        die();
    } elseif ($business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'] < 1000 || ($business['POLICY']['TCPLI_INSURANCE_PASSENGER_AMOUNT'] % 1000) != 0) {
        return_ajax('error', '车上人员责任险(乘客)保额最小1000,并且必须是1000的整数倍！');
        die();
    }
    if (empty($business['POLICY']['TCPLI_PASSENGER_COUNT'])) {
        return_ajax('error', '请输入乘客数量！');
        die();
    } elseif ($business['POLICY']['TCPLI_PASSENGER_COUNT'] > ($_POST['form']['AUTO']['SEATS']) - 1) {
        return_ajax('error', '车上人员责任险(乘客)乘客数量不能大于核定载客减1！');
        die();
    }
}

if (in_array('SLOI', $business['POLICY']['BUSINESS_ITEMS'])) {
    if (!in_array('TVDI', $business['POLICY']['BUSINESS_ITEMS'])) {
        return_ajax('error', '必须在投保车损队情况下才能投保自燃损失险！');
        die();
    } elseif (empty($business['POLICY']['SLOI_INSURANCE_AMOUNT'])) {
        return_ajax('error', '请输入自燃损失险保额！');
        die();
    } elseif ($business['POLICY']['SLOI_INSURANCE_AMOUNT'] > $auto['BUYING_PRICE']) {
        return_ajax('error', '自燃损失险保额不能大于新车购置价！');
        die();
    }
}
if (in_array('NIELI', $business['POLICY']['BUSINESS_ITEMS'])) {
    if (!in_array('TVDI', $business['POLICY']['BUSINESS_ITEMS'])) {
        return_ajax('error', '必须在投保车损队情况下才能投保新增设备损失险！');
        die();
    } elseif (empty($business['POLICY']['NIELI_INSURANCE_AMOUNT'])) {
        return_ajax('error', '请输入新增设备损失险保额！');
        die();
    }
}

if (in_array('BSDI', $business['POLICY']['BUSINESS_ITEMS']) && !in_array('TVDI', $business['POLICY']['BUSINESS_ITEMS'])) {
    return_ajax('error', '必须在投保车损队情况下才能投保车身划痕险！');
    die();
}
if (in_array('BGAI', $business['POLICY']['BUSINESS_ITEMS']) && !in_array('TVDI', $business['POLICY']['BUSINESS_ITEMS'])) {
    return_ajax('error', '必须在投保车损队情况下才能投保玻璃单独破碎险！');
    die();
}
if (in_array('VWTLI', $business['POLICY']['BUSINESS_ITEMS']) && !in_array('TVDI', $business['POLICY']['BUSINESS_ITEMS'])) {
    return_ajax('error', '必须在投保车损队情况下才能投保发动机涉水损失险！');
    die();
}
if (in_array('STSFS', $business['POLICY']['BUSINESS_ITEMS']) && !in_array('TVDI', $business['POLICY']['BUSINESS_ITEMS'])) {
    return_ajax('error', '必须在投保车损队情况下才能投保指定专修厂！');
    die();
}

if (isset($business['POLICY']['BUSINESS_ITEMS']) && !empty($business['POLICY']['BUSINESS_ITEMS'])) {
    $business['BUSINESS_ITEMS'] = $business['POLICY']['BUSINESS_ITEMS'];
}

if (!empty($business['POLICY']['BUSINESS_ITEMS'])) {
    $amount = $business['POLICY'];
    $business = array_merge($amount, $business);
    unset($business['POLICY']);
    if (isset($business['TTBLI_INSURANCE_AMOUNT']) && !empty($business['TTBLI_INSURANCE_AMOUNT'])) {
        switch ($business['TTBLI_INSURANCE_AMOUNT']) {
            case '5':
                $business['TTBLI_INSURANCE_AMOUNT'] = 50000;
                break;
            case '10':
                $business['TTBLI_INSURANCE_AMOUNT'] = 100000;
                break;
            case '15':
                $business['TTBLI_INSURANCE_AMOUNT'] = 150000;
                break;
            case '20':
                $business['TTBLI_INSURANCE_AMOUNT'] = 200000;
                break;
            case '30':
                $business['TTBLI_INSURANCE_AMOUNT'] = 300000;
                break;
            case '50':
                $business['TTBLI_INSURANCE_AMOUNT'] = 500000;
                break;
            case '100':
                $business['TTBLI_INSURANCE_AMOUNT'] = 1000000;
                break;
            case '150':
                $business['TTBLI_INSURANCE_AMOUNT'] = 1500000;
                break;
            case '200':
                $business['TTBLI_INSURANCE_AMOUNT'] = 2000000;
                break;
        }
    }
}

global $qidianSdk;
if (is_file(_ROOT_DIR . "/webservices/qiDianapi.class.php")) {
    require_once _ROOT_DIR . "/webservices/qiDianapi.class.php";
    $qidianmod = "qiDianapi";
    $qidianSdk = new $qidianmod();
} else {
    $smarty->assign('ERROR_MESSAGE', '系统发生错误');
    $smarty->display('ErrorMessage1.tpl');
    die();
}
if (isset($_GET['checkcode']) && $_GET['checkcode'] != "") {
    $premium_params['verify']['login'] = $_GET['checkcode'];
}

if (isset($_POST['form']['OTHER']['DZA_DEMANDNOS']) && !empty($_POST['form']['OTHER']['DZA_DEMANDNOS'])) {
    if (isset($_POST['form']['OTHER']['DZA_CHECKCODES']) && !empty($_POST['form']['OTHER']['DZA_CHECKCODES'])) {
        $premium_params['verify'][$_POST['form']['OTHER']['DZA_DEMANDNOS']] = $_POST['form']['OTHER']['DZA_CHECKCODES'];
    } else {
        $smarty->assign('ERROR_MESSAGE', '交强险转保验证码不能为空！');
        $smarty->display('ErrorMessage1.tpl');
        die();
    }
}

if (isset($_POST['form']['OTHER']['DAA_DEMANDNOS']) && !empty($_POST['form']['OTHER']['DAA_DEMANDNOS'])) {
    if (isset($_POST['form']['OTHER']['DAA_CHECKCODES']) && !empty($_POST['form']['OTHER']['DAA_CHECKCODES'])) {
        $premium_params['verify'][$_POST['form']['OTHER']['DAA_DEMANDNOS']] = $_POST['form']['OTHER']['DAA_CHECKCODES'];
    } else {
        $smarty->assign('ERROR_MESSAGE', '商业险转保验证码不能为空！');
        $smarty->display('ErrorMessage1.tpl');
        die();
    }
}

$premium_params['vehicle'] = $auto;
$premium_params['compulsory'] = $mvtalci;
$premium_params['business'] = $business;
$premium_params['insurance'] = $_POST['form']['OTHER']['PREMIUM_RATE_TABLE'];
$result = $qidianSdk->getCliectSdk('Premium', $premium_params, 'POST');

if (!$result) {
    $errmessage = $qidianSdk->getErrorMessage();
    return_ajax('error', $errmessage);
    die();
}

if ($result['code'] == 4 && isset($result['data']['verify']['login']) && $result['data']['verify']['login'] != "") {
    $smarty->assign('verifyCode', $result['data']['verify']['login']);
    $smarty->assign('vin_no', $vin);
    $smarty->assign('model', $model);
    $smarty->assign('page', $page);
    $smarty->assign('action', $action);
    $smarty->assign('rate_table', $rate_table);
    $smarty->display("{$module}/verifyCode.tpl");
    die();
}

if (!empty($result['data']['verify']) && is_string($result['data']['verify'])) {
    return_ajax('error', $result['data']['verify']);
    die();
}

if (isset($result['data']['verify']) && !empty($result['data']['verify'])) {
    $errorMsg = array();
    foreach ($result['data']['verify'] as $key => $val) {
        $code = substr($key, 0, 4);
        if (!empty($code) && $code == 'V010') {
            $errorMsg['DAA']['demandNo'] = $key;
            $errorMsg['DAA']['checkcode'] = $val;
        }

        if (!empty($code) && $code == '01PI') {
            $errorMsg['DZA']['demandNo'] = $key;
            $errorMsg['DZA']['checkcode'] = $val;
        }
    }

    return_ajax('protectCheckcode', $errorMsg);
    die();
}

if ($result['code'] == 0 && $result['describe'] == 'success') {
    return_ajax('success', $result['data']);
    die();
}
