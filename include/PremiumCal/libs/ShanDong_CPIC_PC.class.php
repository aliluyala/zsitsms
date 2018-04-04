<?php

/**
 * 新太平洋山东算价器接口
 * User: xstoop
 * Date: 2017/7/26
 * Time: 11:10
 */
class ShanDong_CPIC_PC
{
    //(必需)算价模板文件
    const formFile = 'Calculate.tpl';

    //(必需)保险公司
    const company = 'CPIC';

    //(必需)设置错误信息成员属性（默认值）
    public $error = array();

    //(必需)算价器设置配置项
    private $setItems = array(
        'username' => '登录名',
        'password' => '密码',
    );

    private $loginInfo = array();

    //声明cURL会话配置
    private $_options = Array();

    //cURL连接资源句柄信息
    private $_info = Array();

    public $loginUrl = 'http://issue.cpic.com.cn/ecar/j_spring_security_check';
    public $verifyCodeUrl = 'http://issue.cpic.com.cn/ecar/auth/getCaptchaImage?8';
    public $queryCarModelByVinCodeUrl = 'http://issue.cpic.com.cn/ecar/ecar/queryCarModelByVinCode';
    public $queryCarModelUrl = 'http://issue.cpic.com.cn/ecar/ecar/queryCarModel';
    public $calculateUrl = 'http://issue.cpic.com.cn/ecar/quickoffer/calculate';

    public $request;
    public $cachePath;

    /**
     * 构造函数
     * 参数:
     * @config          必需。配置 数组
     * @cachePath       必需。缓存目录 绝对路径
     **/
    public function __construct($config, $cachePath = '')
    {
        $this->loginInfo = array(
            'username' => $this->array_get($config, 'username'),
            'password' => $this->array_get($config, 'password')
        );

        if (empty($cachePath)) {
            $this->cachePath = dirname(__FILE__);
        } else {
            $this->cachePath = $cachePath;
        }
        $this->codeCachePath = $this->cachePath . '/' . __CLASS__ . '/';
        if (!is_dir($this->codeCachePath)) {
            mkdir($this->codeCachePath);
        }
        $this->cookieFile = $this->cachePath . '/' . __CLASS__ . '/cookie.txt';
    }

    /**
     * array(getSetItems 获取设置项目)
     * @return array(Array) array(设置项目)
     * (必需)
     */
    public function getSetItems()
    {
        return $this->setItems;
    }

    /**
     * array(getFormFile 获取表单模板文件名)
     * @return array(String) array(模板文件名)
     * (必需)
     */
    public function getFormFile()
    {
        return self::formFile;
    }

    /**
     * array(queryBuyingPrice 新车购置价查询)
     * @param  array $info array(车辆信息)
     * $info = array(
     *      'model' => '奇瑞牌SQR6451T21T7',//型号
     *      'vin_no' => 'LVTDB24B5FC034122',//车架号
     *      'rows' => '10',//每页条数
     *      'page' => '1',//第几页
     *    )
     *
     * @return array(Mixed)         array(成功返回购置价,失败返回false)
     * 成功返回结果
     * $result = array(
     *      'page' => 1,        // 当前第几页
     *      'records' => 10,    // 每页条数
     *      'rows' => array(
     *          array(
     *              'nXhKindpriceWithouttax' => 0,
     *              'szxhTaxedPrice' => 0,
     *              'vehicleAlias' => "瑞虎5 2.0L CVT家悦版",        // 车型别名
     *              'vehicleDisplacement' => 1.971,                 // 排气量
     *              'vehicleId' => 'QRAAFD0092',                    // 车型代码
     *              'vehicleMaker' => '',
     *              'vehicleName' => '奇瑞SQR6451T21T7多用途乘用车',  // 车型名称
     *              'vehiclePrice' => 102900,                       // 新车购置价
     *              'vehicleSeat' => 5,                             // 核定载客人数
     *              'vehicleTonnage' => 0,                          // 核定载质量
     *              'vehicleWeight' => '1.537',                     // 整车质量吨
     *              'vehicleYear' => '201311',                      // 上市年份
     *              'xhKindPrice' => 0
     *          ),
     *          ...
     *      ),
     *      'total' => 2 // 总页数
     * )
     * (必需)
     */
    public function queryBuyingPrice($info = array())
    {
        $checkcode = $this->array_get($info, 'checkcode');
        if (!empty($checkcode)) {
            $this->checkcode = $checkcode;
        }

        if (isset($_GET['verify_refresh'])) {
            $this->saveVerifyCode();
        }

        $response = $this->queryByModel($info);

        if (!$response) {
//            $this->error['errorMsg'] = $this->array_get($response, 'message.message', '接口出错');
            return false;
        }

        $result = $this->array_get($response, 'result', array());

        $page = $this->array_get($info, 'page', 1);
        $totalPage = count($result) == 10 ? $page + 1 : $page;
        $return = array(
            'total' => $totalPage, //总页数
            'page' => $this->array_get($info, 'page', 1),
            'records' => count($result),
        );

        $return['rows'] = array();
        foreach ($result as $item) {
            $return['rows'][] = array(
                'nXhKindpriceWithouttax' => 0,
                'szxhTaxedPrice' => 0,
                'vehicleAlias' => $this->array_get($item, 'remark'),            // 车型别名
                'vehicleDisplacement' => $this->array_get($item, 'engineDesc'), // 排气量
                'vehicleId' => $this->array_get($item, 'moldCharacterCode'),    // 车型代码
                'vehicleMaker' => '',
                'vehicleName' => $this->array_get($item, 'name'),               // 车型名称
                'vehiclePrice' => $this->array_get($item, 'purchaseValue'),     // 新车购置价
                'vehicleSeat' => $this->array_get($item, 'seatCount'),          // 核定载客人数
                'vehicleTonnage' => $this->array_get($item, 'tonnage'),         // 核定载质量
                'vehicleWeight' => $this->array_get($item, 'fullWeight'),       // 整车质量吨
                'vehicleYear' => $this->array_get($item, 'year'),               // 上市年份
                'xhKindPrice' => 0
            );
        }

        return $return;
    }

    /**
     * 通过车架号查询
     *
     * @param $page
     * @param $vin_no
     * @return array
     */
    public function queryByVinCode($page, $vin_no)
    {
        $postFileds = array(
            'meta' => array('pageNo' => $page),
            'redata' => array(
                'carVIN' => $vin_no,
                'plateNo' => '川A12345', // 号牌必须，可写死
            )
        );

        $response = $this->jsonRequest($this->queryCarModelByVinCodeUrl, $postFileds);
        $response['result'] = $this->array_get($response, 'result.models', array());
        return $response;
    }

    /**
     * 通过品牌型号查询
     *
     * @param $info
     * @return string
     */
    public function queryByModel($info)
    {
        /*
        // 通过车架号查到车型列表，在结果中取到车型号品牌。因为前端提供的品牌型号可能与目标不一致
        $page = $this->array_get($info, 'page', 1);
        $response = $this->queryByVinCode($page, $this->array_get($info, 'vin_no'));

        $models = $this->array_get($response, 'result', array());

        $model = $this->array_get(array_shift($models), 'name', $this->array_get($info, 'model'));
        */
        $model = str_replace('牌', '', $this->array_get($info, 'model'));

        $page = $this->array_get($info, 'page', 1);
        $meta = array();
        if ($page != 1) {
            $meta = array("pageNo" => $page);
        }

        $postFileds = array(
            'meta' => $meta,
            'redata' => array(
                'name' => $model
            )
        );

        return $this->jsonRequest($this->queryCarModelUrl, $postFileds);
    }

    /**
     * array(depreciation 车辆折旧价计算)
     * @param  array $info array(参数信息)
     * $info = array(
     *      'MOBILE' => 13548151101,
     *      'LICENSE_NO' => '川AG11A2',
     *      'LICENSE_TYPE' => 'SMALL_CAR',
     *      'OWNER' => '代志强',
     *      'VIN_NO' => 'LVTDB24B5FC034122',
     *      'ENGINE_NO' => 'TAFC05761',
     *      'MODEL' => '奇瑞SQR6451T21T7多用途乘用车',
     *      'ENGINE' => 1.971,
     *      'SEATS' => 5,
     *      'KERB_MASS' => 1537,
     *      'MODEL_CODE' => 'QRAAFD0092',
     *      'BUYING_PRICE' => 102900.00, // 购置价
     *      'ENROLL_DATE' => '2015-05-21', // 注册日期
     *      'VEHICLE_TYPE' => 'PASSENGER_CAR',
     *      'USE_CHARACTER' => 'NON_OPERATING_PRIVATE',
     *      'ORIGIN' => 'DOMESTIC',
     *      'TONNAGE' => 0,
     *      'INDUSTY_MODEL_CODE' => 0,
     *      'DISCOUNT_PRICE' => 0.00,
     *      'BUSINESS_START_TIME' => '2017-05-22 00:00:00' // 商业险开始生效日期
     * )
     * @return array(Mixed)         array(成功返回折旧价intger,失败返回false)
     * (必需)
     */
    public function depreciation($info = array())
    {
        $price = $info['BUYING_PRICE'];// 购置价
        $month = $this->getIntervalMonth($info['BUSINESS_START_TIME'], $info['ENROLL_DATE']);
        $rate = $this->getDepreciationRate($info['VEHICLE_TYPE'], $info['USE_CHARACTER']);

        if ($rate) {
            return $price - $price * $month * $rate;
        }
        $this->error['errorMsg'] = '折旧率获取失败';

        return false;
    }

    /**
     * array(deviceDepreciation 设备折旧价计算)
     * @param  array $info array(参数信息)
     * @return array(Mixed)         array(成功返回折旧价,失败返回false)
     * 平安算价未找到设备折旧价,该折旧价计算方式拷贝至JingTai_PC.class.php
     * (必需)
     */
    public function deviceDepreciation($info = array())
    {
        if (empty($info) || !isset($info)) {
            $this->error['errorMsg'] = "参数信息不能为空";
            return false;
        }

        foreach ($info['DEVICE_LIST'] as $k => $v) {
            if (!isset($v['NAME'])) {
                $this->error['errorMsg'] = "设备名称不能为空";
                return false;
            }

            if (!isset($v['BUYING_PRICE'])) {
                $this->error['errorMsg'] = "新购价格不能为空";
                return false;
            }

            if (!isset($v['COUNT'])) {
                $this->error['errorMsg'] = "数量不能为空";
                return false;
            }

            if (!isset($v['BUYING_DATE'])) {
                $this->error['errorMsg'] = "购置日期不能空";
                return false;
            }
        }

        $return = array();
        foreach ($info["DEVICE_LIST"] as $item) {
            $infoArray = array_merge($info, array(
                'BUYING_PRICE' => $item['BUYING_PRICE'],
                'ENROLL_DATE' => $item['BUYING_DATE'],
            ));

            $return[] = array(
                'BUYING_DATE' => $item['BUYING_PRICE'],
                'ENROLL_DATE' => $item['BUYING_DATE'],
                'COUNT' => $item['COUNT'],
                'DEPRECIATION' => $this->depreciation($infoArray), // 单个设备的折旧价
                'NAME' => $item['NAME'],
            );
        }
        return $return;
    }

    /**
     * array(premium 获取算价信息)
     * @param  array $auto array(车辆信息)
     * @param  array $business array(商业险信息)
     * @param  array $mvtalci array(交强险信息)
     * @return array(Mixed)          array(成功返回算价结果数组,失败返回false)
     *
     *    /*****************************************************************************
     * 保费计算
     * 参数:
     * @auto            必需,数组。车辆信息
     *    数组结构如下:
     *    array(
     *      'LICENSE_NO'   =>'' , //车牌号码
     *      'LICENSE_TYPE' =>'' , //号牌类别
     *      'OWNER'        =>'' , //拥有人
     *      'VIN_NO'       =>'' , //车辆识别码
     *      'ENGINE_NO'    =>'' , //发动机号码
     *      'MODEL'        =>'' , //品牌型号
     *      'ENGINE'       =>'' , //排量
     *      'SEATS'        =>'' , //核定载客
     *      'KERB_MASS'    =>'' , //整备质量
     *      'MODEL_CODE'   =>'' , //型号代码
     *      'BUYING_PRICE' =>'' , //新车购置价
     *      'ENROLL_DATE'  =>'' , //注册日期
     *      'VEHICLE_TYPE' =>'' , //车辆类别
     *      'USE_CHARACTER'=>'' , //使用性质
     *      'ORIGIN'       =>'' , //产地
     *     )
     *----------------------------------------------------------------------------
     * @business        必需,数组。商业险变量
     *     数组结构如下:
     *     array(
     *       'DESIGNATED_DRIVER'  => array(), //指定驾驶人,二维数组,结构见下
     *         'DISCOUNT_VARS'      => array(), //折扣系数,结构见下
     *       'POLICY'             => array(), //投保参数,结构见下
     *       'BUSINESS_START_TIME'=> '',      //保险生效时间
     *         'BUSINESS_END_TIME'  => '',      //保险结束时间
     *      )
     *
     *     指定驾驶人数组结构,二维数组,一个元素一个驾驶人
     *     array(
     *         array(
     *            'DRIVER_NAME'=>'',         //驾驶人姓名
     *            'DRIVING_LICENCE_NO'=>'',  //驾驶证号
     *            'DRIVER_ALLOW_DRIVE'=>'',  //准驾代码
     *            'DRIVER_SEX'=>'',          //性别
     *            'DRIVER_AGE'=>'',          //年龄
     *            'DRIVING_YEARS'=>'',       //驾龄
     *            ),
     *       .......
     *     )
     *
     *     折扣系数
     *     array(
     *       'YEARS_OF_INSURANCE'=>'',     //投保年度
     *       'CLAIM_RECORDS'=>'',          //赔款记录
     *       'DRIVING_AREA'=>'',           //约定行驶区域
     *       'AVERAGE_ANNUAL_MILEAGE'=>'', //平均行驶里程
     *       'MULTIPLE_INSURANCE'=>'',     //多险种优惠
     *     )
     *
     *     投保参数
     *     array(
     *       'BUSINESS_ITEMS'=>array('TVDI','TTBLI',...),  //投保险种代码列表
     *       'TVDI_INSURANCE_AMOUNT'=>'',                  //车损险保额
     *       'DOC_AMOUNT'=>'',                             //车损险可选免赔额
     *       'TTBLI_INSURANCE_AMOUNT'=>'',                 //三者险保额
     *       'TWCDMVI_INSURANCE_AMOUNT'=>'',               //盗抢险保额
     *       'TCPLI_INSURANCE_DRIVER_AMOUNT'=>'',          //座位险(司机)保额
     *       'TCPLI_PASSENGER_COUNT'=>'',                  //座位险(乘客)数量
     *       'TCPLI_INSURANCE_PASSENGER_AMOUNT'=>'',       //座位险(乘客)单位保额
     *       'BSDI_INSURANCE_AMOUNT'=>'',                  //划痕险保额
     *       'SLOI_INSURANCE_AMOUNT'=>'',                  //自燃险保额
     *       'GLASS_ORIGIN'=>'',                           //玻璃险玻璃产地
     *       'NIELI_INSURANCE_AMOUNT'=>'',                 //新增设备损失险保额
     *       'STSFS_RATE'=>'',                             //指定专修厂上浮比例
     *       'WADING_INSURANCE_AMOUNT'=>'',               //涉水险保额
     *     )
     *
     *----------------------------------------------------------------------------
     *
     * @mvtalci         必需,数组。交强险变量
     *     数组结构如下:
     *    array(
     *      'FLOATING_RATE'=>'',                           //交强险浮动标准
     *      'MVTALCI_INSURANCE_AMOUNT'
     *      'MVTALCI_START_TIME'=>'',                      //交强险生效时间
     *      'MVTALCI_END_TIME'=>'',                        //交强险结束时间
     *    )
     *
     **/
    public function premium($auto = array(), $business = array(), $mvtalci = array())
    {
        // 没有勾选商业险与交强险的返回
        if (empty($business['POLICY']['BUSINESS_ITEMS']) && empty($mvtalci)) {
            return $this->resolveResult(array());
        }

        $postFileds = $this->premiumQuery($auto, $business, $mvtalci);

        if (!$postFileds) {
            return false;
        }

        $response = $this->jsonRequest($this->calculateUrl, $postFileds);

        if ($this->array_get($response, 'message.code') != "success") {
            $this->error['errorMsg'] = $this->array_get($response, 'message.message', '接口请求出错');
            return false;
        }

        $result = $this->resolveResult($this->array_get($response, 'result', array()));

        if (empty($result)) {
            $this->error['errorMsg'] = '没有获取到返回数据';
            return false;
        }

        return $result;
    }

    /**
     * array(getLastError 返回最后一次错误信息)
     * @return array(Array) array(返回保存的错误信息)
     * (必需)
     */
    public function getLastError()
    {
        return $this->error;
    }

    /**
     * 登录验证码
     * @var
     */
    private $checkcode;

    /**
     * 判断是否登录
     *
     * @return bool
     */
    public function isLogin()
    {
        if ($this->_info['http_code'] == 401) {
            if (isset($this->checkcode) && !empty($this->checkcode)) {
                return $this->login();
            } else {

                if (file_exists($this->codeCachePath . 'verifyCode.text')) {
                    $storeCodeContent = file_get_contents($this->codeCachePath . 'verifyCode.text');
                    $storeCode = json_decode($storeCodeContent, true);
                } else {
                    $storeCode = array();
                }

                if (empty($storeCode)) {
                    $this->verification = true;
                    $this->saveVerifyCode();
                    return false;
                }

                if ((time() - $storeCode['time'] < 60)) { // 60秒之内不重新获取验证码
                    if ($storeCode['status'] == 'success') { // 已经验证成功
                        return true;
                    }
                    $this->verification = true;
                    return false;
                } else {
                    $this->verification = true;
                    $this->saveVerifyCode();
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 执行登录
     *
     * @return bool
     */
    public function login()
    {
        $storeCodeContent = file_get_contents($this->codeCachePath . 'verifyCode.text');
        $storeCode = json_decode($storeCodeContent, true);
        if ($storeCode['status'] == 'success') {
            return true;
        }

        $verifyCode = isset($this->checkcode) ? $this->checkcode : '';

        if (strlen($verifyCode) != 4) {
            $this->error['errorMsg'] = '验证码必须为4位';
            return false;
        }

        $username = $this->loginInfo['username'];
        $password = $this->loginInfo['password'];

        $loginPostArray = array(
            'j_username' => $username,
            'j_password' => hash('sha256', $password . $username),
            'verify_code' => $verifyCode
        );
        // 进行账号密码验证码登录
        $result = $this->decodeRequest(array(
            CURLOPT_URL => $this->loginUrl,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($loginPostArray),
            CURLOPT_HTTPHEADER => array(
                'X-Requested-With: XMLHttpRequest',
                'Accept: text/plain, */*; q=0.01',
                'Referer: http://issue.cpic.com.cn/ecar/view/portal/page/common/login.html',
                'RA-Sid: s_4605_r2x9ak474125_760',
                'RA-Ver: 3.1.4'
            )
        ));

        if ($this->array_get($result, 'authentication', 'false') == 'true') {

            $storeContent = json_decode(file_get_contents($this->codeCachePath . 'verifyCode.text'), true);
            $storeContent['status'] = 'success';
            file_put_contents($this->codeCachePath . 'verifyCode.text', json_encode($storeContent));

            // 进行二次登录机构信息获取
            $queryFastLoginInfo = $this->decodeRequest(array(
                CURLOPT_URL => 'http://issue.cpic.com.cn/ecar/auth/queryFastLoginInfo',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => '{"meta":{},"redata":{}}',
                CURLOPT_HTTPHEADER => array(
                    'X-Requested-With: XMLHttpRequest',
                    'Accept: application/json, text/javascript, */*; q=0.01',
                    'Referer: http://issue.cpic.com.cn/ecar/view/portal/page/common/partnerselect.html',
                    'Content-Type: application/json;charset=UTF-8',
                    'RA-Sid: s_4605_r2x9ak474125_760',
                    'RA-Ver: 3.1.4'
                )
            ));


            $userAuthVos = $this->array_get($queryFastLoginInfo, 'result.userAuthVos', array());
            if (!empty($userAuthVos)) {
                $userAuthVos = array_shift($userAuthVos); // 默认获取第一个机构信息
            }
            $agentAuthVos = $this->array_get($userAuthVos, 'agentAuthVos', array());
            if (!empty($agentAuthVos)) {
                $agentAuthVos = array_shift($agentAuthVos);
            }
            $loginFastPostArray = array(
                'access_token' => $this->array_get($userAuthVos, 'accessToken'),
                'partner_code' => $this->array_get($userAuthVos, 'partnerCode'),
                'j_username' => $username,
                'agent_code' => $this->array_get($agentAuthVos, 'agentCode'),
            );

            // 进行机构信息登录
            $loginFastResult = $this->decodeRequest(array(
                CURLOPT_URL => $this->loginUrl,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => http_build_query($loginFastPostArray),
                CURLOPT_HTTPHEADER => array(
                    'X-Requested-With: XMLHttpRequest',
                    'Accept: text/plain, */*; q=0.01',
                    'Referer: http://issue.cpic.com.cn/ecar/view/portal/page/common/partnerselect.html',
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'RA-Sid: s_4605_r2x9ak474125_760',
                    'RA-Ver: 3.1.4'
                )
            ));

            if ($loginFastResult['authentication'] == "true") {
                return true;
            } else {
                $this->error['errorMsg'] = $this->array_get($loginFastResult, 'errMsg', '接口登录出错');
                return false;
            }
        }

        $this->error['errorMsg'] = $this->array_get($result, 'errMsg', '验证码出错');
        return false;
    }

    /**
     * 保存验证码
     */
    public function saveVerifyCode()
    {
        $image = $this->request(array(
            CURLOPT_URL => $this->verifyCodeUrl
        ));

        file_put_contents($this->codeCachePath . 'code.jpg', $image);
        file_put_contents($this->codeCachePath . 'verifyCode.text',
            json_encode(
                array(
                    'time' => time(),
                    'status' => 'wait'
                )
            )
        );
    }

    /**
     * 返回保存的验证码图片的base64字符串
     *
     * @return bool|string
     */
    public function getVerifyCode($info = array())
    {
        $img = $this->codeCachePath . 'code.jpg';
        if (file_exists($img)) {
            return $this->img2Base64($img);
        }
        $this->error['errorMsg'] = '验证码图片不存在';
        return false;
    }

    /**
     * 获取解密后的json
     *
     * @param $options
     * @return array
     */
    private function decodeRequest($options)
    {
        $response = $this->request($options);

        $response = str_replace(array('\\r\\n', '\\r', '\\n'), '', $response);

        return json_decode($response, true);
    }

    /**
     * 发送xml 、 Post json的请求
     *
     * @param $url
     * @param array $postFileds
     * @return array
     */
    private function jsonRequest($url, $postFileds)
    {
        $postString = $this->arrayToJson($postFileds);
        $response = $this->decodeRequest(array(
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postString,
            CURLOPT_HTTPHEADER => array(
                'Accept:application/json, text/javascript, */*; q=0.01',
                'Accept-Encoding:gzip, deflate',
                'Accept-Language:zh-CN,zh;q=0.8',
                'Cache-Control:no-cache',
                'Connection:keep-alive',
                'Content-Length:' . strlen($postString),
                'Content-Type:application/json;charset=UTF-8',
                'Host:issue.cpic.com.cn',
                'Origin:http://issue.cpic.com.cn',
                'Pragma:no-cache',
                'Referer:http://issue.cpic.com.cn/ecar/view/portal/page/quick_quotation/quick_quotation.html',
                'X-Requested-With:XMLHttpRequest',
            )
        ));

        if ($this->isLogin()) {
            return $response;
        }

        return array();
    }

    public $verification = false;

    /**
     * 组装CURL请求，以及返回请求结果
     *
     * @param $options
     * @return bool|mixed
     */
    private function request($options)
    {
        //启动CURL会话
        $curl = curl_init();

        $this->setOptions($options);
        curl_setopt_array($curl, $this->_options);

        $response = curl_exec($curl);
        $this->_info = curl_getinfo($curl);
        if (curl_errno($curl)) {
            $this->error['errorMsg'] = curl_error($curl);
        }
        curl_close($curl);

        return $response;
    }

    /**
     * array(setOptions 设置cURL参数)
     *
     * @param $options array(cURL请求参数)
     */
    private function setOptions($options)
    {
        $def_options = Array(
            CURLOPT_USERAGENT => "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)",
            CURLOPT_HTTPHEADER => array(                         // 请求头信息
                'Accept-Language: zh-CN',
                'Accept: text/plain, application/xhtml+xml, */*',
                'Accept-Encoding: gzip, deflate',
                'Host: issue.cpic.com.cn'
            ),
            CURLOPT_SSL_VERIFYPEER => false,                // 关闭SSL验证
            CURLOPT_SSL_VERIFYHOST => 0,                    // 关闭SSL名称验证
            CURLOPT_COOKIEJAR => $this->cookieFile,       // 设置cookie存放地址
            CURLOPT_COOKIEFILE => $this->cookieFile,      // 设置cookie读取地址
            CURLOPT_ENCODING => 'gzip,deflate',             // 解释gzip
            CURLOPT_TIMEOUT => 60,                          // 设置超时时间
            CURLOPT_CONNECTTIMEOUT => 30,                   // 设置连接超时时间
            CURLOPT_RETURNTRANSFER => 1,                    // 获取的信息以文件流的形式返回
            CURLINFO_HEADER_OUT => 1,                       // 打印请求头信息
        );
        $this->_options = $options + $def_options;          // 追加默认配置
    }

    /**
     * 获取数组中的指定键的值
     *
     * @param $array
     * @param $key
     * @param string $default
     * @return mixed
     */
    private function array_get($array, $key, $default = null)
    {
        if (!is_array($array)) {
            return $default;
        }

        if (is_null($array)) {
            return $array;
        }

        if (array_key_exists($key, $array)) {
            if (is_string($array[$key])) {
                return trim($array[$key]);
            }
            return $array[$key];
        }

        $segments = array_filter(explode('.', $key));
        foreach ($segments as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        if (is_string($array)) {
            return trim($array);
        }
        return $array;
    }

    /**
     * 获取两个日期间隔的月数
     *
     * @param $date1
     * @param $date2
     * @return integer
     */
    private function getIntervalMonth($date1, $date2)
    {
        $tims1 = date("Y", strtotime($date1)) * 12 + date("m", strtotime($date1));
        $tims2 = date("Y", strtotime($date2)) * 12 + date("m", strtotime($date2));
        // 计算两个日期的月数
        $month = abs(($tims1 - $tims2));
        $day1 = date("d", strtotime($date1));
        $day2 = date("d", strtotime($date2));
        // 天数不满一个月按照减去一个月
        if (strtotime($date1) < strtotime($date2)) {
            if ($day1 > $day2) {
                $month--;
            }
        } else if (strtotime($date1) > strtotime($date2)) {
            if ($day1 < $day2) {
                $month--;
            }
        }

        return $month < 0 ? 0 : $month;
    }

    /**
     * 获取指定车辆种类的折旧费率
     *
     * @param $vehicleType
     * @param string $useCharacter
     * @return bool|integer
     */
    private function getDepreciationRate($vehicleType, $useCharacter = '')
    {
        $rate = array(
            'PASSENGER_CAR' => array(                        // 客车
                'NON_OPERATING_PRIVATE' => 0.006,       // 家庭自用汽车
                'NON_OPERATING_ENTERPRISE' => 0.006,    // 非营业企客车
                'NON_OPERATING_AUTHORITY' => 0.006,     // 非营业机关/事业客车
                'OPERATING_LEASE_RENTAL' => 0.011,      // 营业出租租赁
            ),
            'TRUCK' => array(                                // 货车
                'OPERATING_TRUCK' => 0.009,             // 营业货车
                'NON_OPERATING_TRUCK' => 0.009,         // 非营业货车
                'NON_OPERATING_LOW_SPEED_TRUCK' => 0.011, // 非营业低速货车
                'OPERATING_LOW_SPEED_TRUCK' => 0.014,   // 营业低速货车
                'OPERATING_CITY_BUS' => 0.009,          // 营业城市客车
                'OPERATING_HIGHWAY_BUS' => 0.009        // 营业公路客车
            ),
            'THREE_WHEELED' => 0.011                    // 三轮汽车
        );

        return $this->array_get($rate, $vehicleType . '.' . $useCharacter, false);
    }

    /**
     * 组装算价的参数
     *
     * @param $auto
     * @param $business
     * @param $mvtalci
     * @return array
     */
    private function premiumQuery($auto = array(), $business = array(), $mvtalci = array())
    {
        $identifyNo = $this->array_get($auto, 'IDENTIFY_NO');
        if (!$identifyNo) {
            $this->error['errorMsg'] = '身份证号码不能为空，请设置身份证号码!';
            return false;
        }

        $return['meta'] = array();
        $return['redata']['quotationNo'] = '';

        // 车辆信息
        $ecarvo = $this->buildEcarvo($auto, $business);
        if (!$ecarvo) {
            $this->error['errorMsg'] = '车辆信息获取失败';
            return false;
        }
        $return['redata']['ecarvo'] = $ecarvo;

        $return['redata']['inType'] = null;

        $return['redata']['insuredVo'] = array(
            'relationship' => "1",
            'name' => $this->array_get($auto, 'OWNER'),
            'certificateType' => '1',
            'certificateCode' => $identifyNo,
        );

        if (empty($business['POLICY']['BUSINESS_ITEMS'])) {
            $return['redata']['commercial'] = false;              // 商业险复选框
        } else {
            $return['redata']['commercial'] = true;              // 商业险复选框
            // 商业险整体信息
            $return['redata']['commercialInsuransVo'] = $this->buildCommercial($business);
            // 商业险具体项目
            $return['redata']['quoteInsuranceVos'] = $this->buildQuoteInsuranceVos($business);
        }

        if (empty($mvtalci)) {
            $return['redata']['compulsory'] = false;              // 交强险复选框
        } else {
            $return['redata']['compulsory'] = true;              // 交强险复选框
            // 交强险
            $return['redata']['compulsoryInsuransVo'] = $this->buildCompulsory($mvtalci);
        }

//        $return['redata']['accident'] = false;

        return $return;
    }

    /**
     * 组装算价车辆信息数组
     *
     * @param array $auto
     *
     * @return array
     */
    private function buildEcarvo($auto, $business)
    {
        $queryEcarvoArray = array(
            'meta' => array(),
            'redata' => array('moldCharacterCode' => $this->array_get($auto, 'MODEL_CODE'))
        );

        $queryEcarvoInfo = $this->jsonRequest('http://issue.cpic.com.cn/ecar/ecar/queryEcarVoByJY', $queryEcarvoArray);

        if ($this->array_get($queryEcarvoInfo, 'message.code', 'faile') !== 'success') {
            return false;
        }

        $ecarvoInfo = $this->array_get($queryEcarvoInfo, 'result');
        $seats = $this->array_get($auto, 'SEATS');
        $vehicleType = '01';
        if ($seats > 5 && $seats < 10) {
            $vehicleType = '02';
        } else if ($seats > 10 && $seats < 20) {
            $vehicleType = '03';
        }

        $discountPrice = $this->array_get($auto, 'DISCOUNT_PRICE');
        if (empty($discountPrice)) {
            $discountPrice = $this->depreciation(array(
                'BUYING_PRICE' => $auto['BUYING_PRICE'],
                'BUSINESS_START_TIME' => $business['BUSINESS_START_TIME'],
                'ENROLL_DATE' => $auto['ENROLL_DATE'],
                'VEHICLE_TYPE' => $auto['VEHICLE_TYPE'],
                'USE_CHARACTER' => $auto['USE_CHARACTER'],
            ));
        }
        $ecarvo = array(
            'plateNo' => $this->array_get($auto, 'LICENSE_NO'),                     // 号牌号码
            'plateType' => '02',                                                         // 号牌类型02 小型汽车号牌
            'plateColor' => '1',                                                         // 号牌底色1   蓝色
            'carVIN' => $this->array_get($auto, 'VIN_NO'),                           // 车架号
            'engineNo' => $this->array_get($auto, 'ENGINE_NO'),                      // 发动机号
            'stRegisterDate' => $this->array_get($auto, 'ENROLL_DATE'),              // 初登日期
            'usage' => '101',                                                             // 使用性质 101 家庭自用车 201党政机关用车 202事业团体用车 301企业非营业用车 401出租车 402租赁车 501城市公交 502公路客运 601营业货车 701非营业特种车 702营业特种车
            'vehicleType' => $vehicleType,                                                // 车辆种类 01 6座以下客车 02 6座及10座以下客车 03 10座及20座以下客车 04 20座及36座以下客车 05 36座及36座以上客车
            'vehiclePurpose' => '01',                                                     // 车辆用途 01 9座以下客车
            'modelType' => $this->array_get($ecarvoInfo, 'moldName'),                // 厂牌型号
            'moldCharacterCode' => $this->array_get($auto, 'MODEL_CODE'),
            'factoryType' => $this->array_get($ecarvoInfo, 'moldName'),
            'negotiatedValue' => $discountPrice,                                          // 协商价值 折扣价
            'loan' => '0',                                                                // 多年车贷
            'specialVehicleIden' => '',                                                   // 特殊车标识
            'fuelType' => '0',                                                            // 能源类型 0燃油 1纯电动 2燃料电池 3插电式混合动力 4其他混合动力
            'tpyRiskflagCode' => $this->array_get($ecarvoInfo, 'tpyRiskflagCode'),   // 太平洋风险标识
            'stChangeRegisterDate' => '',                                                 // 转移登记日期
            'ownerName' => $this->array_get($auto, 'OWNER'),                         // 车主姓名
            'ownerProp' => '1',                                                           // 车主性质 1个人 2机关 3企业
            'certType' => '1',                                                            // 证件类型 1身份证 2.....
            'certNo' => $this->array_get($auto, 'IDENTIFY_NO'),                      // 证件号码
            'holderTelphone' => '',                                                       // 手机号码
            'seatCount' => $seats,                                                        // 核定载客量(座)
            'engineCapacity' => $this->array_get($ecarvoInfo, 'engineCapacity'),     // 排量(升)
            'tonnage' => '',                                                              // 核定载质量(吨)
            'emptyWeight' => $this->array_get($ecarvoInfo, 'emptyWeight'),           // 整备质量(千克)
            'stCertificationDate' => '',        // new
            'jqVehicleClaimType' => '',         // new
            'syVehicleClaimType' => '',// new
            'power' => $this->array_get($ecarvoInfo, 'power'),
            'shortcutCode' => $this->array_get($ecarvoInfo, 'shortcutCode'),
            'producingArea' => $this->array_get($ecarvoInfo, 'producingArea'),
            'reductionType' => $this->array_get($ecarvoInfo, 'reductionType'),
            'vehiclePowerJY' => $this->array_get($ecarvoInfo, 'vehiclePowerJY'),
            'oriEngineCapacity' => $this->array_get($ecarvoInfo, 'oriEngineCapacity'),
            'address' => '',
            'jyFuelType' => $this->array_get($ecarvoInfo, 'jyFuelType'),
//            'carModelRiskLevel' => $this->array_get($ecarvoInfo, 'carModelRiskLevel'),
            'carModelRiskLevel' => "",
            'inType' => '',
            'lastyearPurchaseprice' => '',// new
            'lastyearModeltype' => '',// new
            'lastyearModelcode' => '',// new
            'transferCompanyName' => '',// new
//            'plateless' => $this->array_get($ecarvoInfo, 'plateless'),              //!!!
            'plateless' => false,              //!!!
            'tpyRiskflagName' => $this->array_get($ecarvoInfo, 'tpyRiskflagName'),
            'purchasePrice' => $this->array_get($ecarvoInfo, 'purchasePrice'),      // 新车购置价
            'actualValue' => $discountPrice                                             // 行业实际价值,折旧后的价
        );

        return $ecarvo;
    }

    /**
     * 组装交强险参数信息
     *
     * @param $mvtalci
     * @return array|bool
     */
    private function buildCompulsory($mvtalci)
    {
        $startTime = date('Y-m-d 00:00', strtotime($this->array_get($mvtalci, 'MVTALCI_START_TIME')));
        $endTime = date('Y-m-d 00:00', strtotime('+1 year', strtotime($startTime)));
        // compulsory交强险信息
        $compulsoryInsuransVo = array(
            'stBackStartDate' => '',
            'stBackEndDate' => '',
            'aidingFundProportion' => '',
            'aidingFund' => '',
            'stStartDate' => $startTime,                   // 保险期限 开始生效日期
            'stEndDate' => $endTime,                       // 结束日期
            'insuranceQueryCode' => '',                     // !!!
            'ecompensationRate' => '',                  // new
            'taxType' => '3',                              // 纳税类型 山东账号登入进来是  3正常缴税 4减税 2免税 1已完税 0拒缴
            'taxBureauName' => '',                          // 税务机关名称
            'stTaxStartDate' => date('Y-01-01'),    // 车船税起期
            'stTaxEndDate' => date('Y-12-31'),      // 车船税止期
            'stTaxBackAmount' => "",
            'cipremium' => null,
            'taxAmount' => null,
            'vehicleClaimType' => '',
        );

        return $compulsoryInsuransVo;
    }

    /**
     * 组装商业险整体数信息
     *
     * @param $business
     * @return array|bool
     */
    private function buildCommercial($business)
    {
        $startTime = date('Y-m-d 00:00', strtotime($this->array_get($business, 'BUSINESS_START_TIME')));
        $endTime = date('Y-m-d 00:00', strtotime('+1 year', strtotime($startTime)));
        $commercialInsuransVo = array(
            'aidingFundProportion' => '',
            'aidingFund' => '',
            'vehicleRiskLevel' => '',
            'stStartDate' => $startTime,          // 商业险期限开始日期
            'stEndDate' => $endTime,              // 商业险期限结束日期
            'premiumRatio' => null,               // 实际折扣率
            'insuranceQueryCode' => '',
            'ecompensationRate' => '',
            'stMaxPremRatio' => '',
            'stMinPremRatio' => '',
            'stMaxSuggestRatio' => '',
            'stMinSuggestRatio' => '',
            'stSchemeId' => '',                   // 自定义radio
            'nonClaimDiscountRate' => '',
            'trafficTransgressRate' => '',
            'underwritingRate' => '',
            'channelRate' => '',                  // !!!
            'standardPremium' => null,
            'premium' => null,
            'poudage' => null,
            'vehicleClaimType' => "",
        );

        return $commercialInsuransVo;
    }

    /**
     * 组装商业险条目数信息
     *
     * @param $business
     * @return array|bool
     */
    private function buildQuoteInsuranceVos($business)
    {
        // 商业险模板数组
        $arrayTemplate = array(
            'one' => array(
                'amount' => '',                            // 保额
                'insuranceType' => '1',
                'insuranceCode' => '',                                              // 险种对应代码
                'nonDeductible' => null,
                'standardPremium' => null,
                'premium' => null,
            ),
            'two' => array(
                'insuranceCode' => '',
                'nonDeductible' => null,
                'standardPremium' => null // new
            ),
            'three' => array(
                'amount' => '',                                                     // 保额
                'insuranceType' => '1',
                'insuranceCode' => '',                                              // 险种对应代码
                'nonDeductible' => null,
                'factorVos' => array(                                                    // 额外 信息
                    array(
                        'factorKey' => 'seat',
                        'factorValue' => '4',
                    )
                ),
                'standardPremium' => null,
                'premium' => null,
            ),
            'four' => array(
                'amount' => '',
                'insuranceType' => '1',
                'insuranceCode' => '',
                'nonDeductible' => null,
                'equipmentVos' => array(
                    array(
                        'toolName' => '',
                        'productArea' => 0,
                        'brand' => '',
                        'actualValue' => '',
                        'stPurchaseDate' => '',
                        'memo' => ''
                    )
                ),
            ),
            'items' => array(
                'DAMAGELOSSCOVERAGE' => 'one',                                      // 机动车损失险
                'DAMAGELOSSEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',                 // 车损不计免赔
                'THIRDPARTYLIABILITYCOVERAGE' => 'one',                             // 机动车第三者责任保险
                'THIRDPARTYLIABILITYEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',        // 机动车第三者责任保险不计免赔
                'THEFTCOVERAGE' => 'one',                                           // 机动车全车盗抢保险
                'THEFTCOVERAGEEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',              // 机动车全车盗抢保险 不计免赔
                'INCARDRIVERLIABILITYCOVERAGE' => 'one',                            // 机动车车上人员责任保险-司机
                'INCARDRIVERLIABILITYEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',       // 机动车车上人员责任保险-司机 不计免赔
                'INCARPASSENGERLIABILITYCOVERAGE' => 'three',                       // 机动车车上人员责任保险-乘客
                'INCARPASSENGERLIABILITYEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',    // 机动车车上人员责任保险-乘客 不计免赔
                // 附加险
                'GLASSBROKENCOVERAGE' => 'three',                                   // 玻璃单独破碎险
                'SELFIGNITECOVERAGE' => 'one',                                      // 自燃损失险
                'SELFIGNITEEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',                 // 自燃损失险 不计免赔
                'CARBODYPAINTCOVERAGE' => 'one',                                    // 车身划痕损失险
                'CARBODYPAINTEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',               // 车身划痕损失险 不计免赔
                'PADDLEDAMAGECOVERAGE' => 'one',                                    // 发动机涉水损失险
                'PADDLEDAMAGEEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',               // 发动机涉水损失险 不计免赔
                'NEWEQUIPMENTCOVERAGE' => 'four',                                   // 新增设备损失险
                'NEWEQUIPMENTEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',               // 新增设备损失险   不计免赔
                'SPIRITDAMAGELIABILITYCOVERAGE' => 'one',                           // 精神损害抚慰金责任险
                'SPIRITDAMAGELIABILITYEXEMPTDEDUCTIBLESPECIALCLAUSE' => 'two',      // 精神损害抚慰金责任险 不计免赔
                'REPAIRPERIODCOMPENSATIONSPECIALCLAUSE' => 'three',                 // 修理期间费用补偿险
                'APPOINTEDREPAIRFACTORYSPECIALCLAUSE' => 'three',                   // 指定修理厂险
                'DAMAGELOSSCANNOTFINDTHIRDSPECIALCOVERAGE' => 'one',                // 机动车损失保险无法找到第三方特约险
            )
        );

        $businessPolicy = $this->array_get($business, 'POLICY', array());
        $businessItem = $this->array_get($business, 'POLICY.BUSINESS_ITEMS', array());
        $templateItems = $arrayTemplate['items'];
        $quoteInsuranceVos = array();

        foreach ($businessItem as $itemKey) {
            $policyKey = $this->transfromBusiness($itemKey);
            $templateKey = $this->array_get($templateItems, $policyKey);
            $template = $this->array_get($arrayTemplate, $templateKey);
            if (!$template) {
                return false;
            }

            $template['insuranceCode'] = $policyKey;
            switch ($templateKey) {
                case 'one':
                    $template['amount'] = $this->array_get($businessPolicy, $itemKey . '_INSURANCE_AMOUNT', 0);
                    if ($itemKey == 'TTBLI') { // 第三方责任险
                        $template['amount'] = (string)((int)$template['amount'] * 10000);
                    } elseif ($itemKey == 'TCPLI_DRIVER') { // 车上人员责任险司机
                        $template['amount'] = $this->array_get($businessPolicy, 'TCPLI_INSURANCE_DRIVER_AMOUNT', 0);
                    }
                    break;
                case 'two':
                    break;
                case 'three':
                    $template['amount'] = $this->array_get($businessPolicy, $itemKey . '_INSURANCE_AMOUNT', 0);

                    if ($itemKey == 'BGAI') { // 玻璃险
                        $template['factorVos'][0]['factorKey'] = 'producingArea';
                        $glassType = $this->array_get($businessPolicy, 'GLASS_ORIGIN', 'DOMESTIC');// 进口or国产
                        $template['factorVos'][0]['factorValue'] = $glassType == 'DOMESTIC' ? 0 : 1;// 1进口 or 0国产
                    } elseif ($itemKey == 'TCPLI_PASSENGER') {    // 乘客座位险
                        $template['amount'] = $this->array_get($businessPolicy, 'TCPLI_INSURANCE_PASSENGER_AMOUNT', 0); // 每个座位的保额
                        $template['factorVos'][0]['factorKey'] = 'seat';
                        $template['factorVos'][0]['factorValue'] = $this->array_get($businessPolicy, 'TCPLI_PASSENGER_COUNT', 4); // 座位数
                    } elseif ($itemKey == 'RDCCI') { // 修理期间费用补偿险
                        $template['amount'] = $this->array_get($businessPolicy, 'RDCCI_INSURANCE_UNIT', 0); // 每天多少钱
                        $template['factorVos'][0]['factorKey'] = 'maxClaimDays';
                        $template['factorVos'][0]['factorValue'] = $this->array_get($businessPolicy, 'RDCCI_INSURANCE_QUANTITY', 1); // 天数
                    } elseif ($itemKey == 'STSFS') {
                        $template['factorVos'][0]['factorKey'] = 'repairFactorRate';
                        $rateType = $this->array_get($businessPolicy, 'STSFS_RATE', 'DOMESTIC'); // 国产 or 进口  默认国产
                        $rate = $rateType == 'DOMESTIC' ? 0.1 : 0.3; // // 国产费率0.1 or 进口0.3  默认国产
                        $template['factorVos'][0]['factorValue'] = $rate; // 费率
                    }
                    break;
                case 'four':
                    $template['amount'] = 0; // 总保额 所有设备的折扣价之和
                    $newDeviceList = $this->array_get($businessPolicy, 'NIELI_DEVICE_LIST', array());
                    $equipmentVos = array();
                    foreach ($newDeviceList as $device) {
                        $actualValue = $this->array_get($device, 'DEPRECIATION', 0);
                        $equipmentVos[] = array(
                            'toolName' => $this->array_get($device, 'NAME', ''), // 设备名称
                            'productArea' => 0,                                              // 0 国产 1 进口。 这里前端传过来的只有国产
                            'brand' => '',                                                   // 品牌 前端无参数
                            'actualValue' => $actualValue,                                   // 折扣价
                            'stPurchaseDate' => $this->array_get($device, 'BUYING_DATE'),// 购买时间
                            'memo' => ''                                                 // 备注信息
                        );
                        $template['amount'] = $template['amount'] + $actualValue;
                    }
                    break;
                default:
                    break;
            }
            $quoteInsuranceVos[] = $template;
        }
        return $quoteInsuranceVos;
    }

    /**
     * 解析返回的数据
     *
     * @param array $result
     * @return array
     *  array(
     *      'MESSAGE' => '', // 返回的提示信息
     *      'MVTALCI' => array(
     *          'MVTALCI_DISCOUNT' =>    ,          // 交强险折扣
     *          'MVTALCI_END_TIME' => '',           // 交强险结束时间
     *          'MVTALCI_PREMIUM'  => 0,            // 交强险保费
     *          'MVTALCI_START_TIME' => '',         // 交强险生效时间
     *          'TRAVEL_TAX_PREMIUM' =>0,           // 车船税
     *      ),
     *      'BUSINESS' => array(
     *          'BUSINESS_DISCOUNT' => int,         // 商业险折扣
     *          'BUSINESS_DISCOUNT_PREMIUM' => int, // 商业险扣后保费合计
     *          ‘BUSINESS_PREMIUM’  => int,         // 保费总计
     *          'BUSINESS_START_TIME' => string,    // 开始时间
     *          'BUSINESS_END_TIME' => string,      // 结束时间
     *          ‘BUSINESS_ITEMS’ => array(               // 商业险条目
     *              ‘TVDI’ => array('PREMIUM' => float),                 // 车辆损失险
     *              ‘TVDI_NDSI’ => array('PREMIUM' => float),            // 车辆损失险 不计免赔
     *              ‘TWCDMVI’ => array('PREMIUM' => float),              // 盗抢
     *              ‘TWCDMVI_NDSI’ => array('PREMIUM' => float),         // 盗抢 不计免赔
     *              ‘TTBLI’ => array('PREMIUM' => float),                // 第三方责任险
     *              ‘TTBLI_NDSI’ => array('PREMIUM' => float),           // 第三方责任险 不计免赔
     *              ‘TCPLI_DRIVER’ => array('PREMIUM' => float),         // 车上人员责任险司机
     *              ‘TCPLI_DRIVER_NDSI’ => array('PREMIUM' => float),    // 车上人员责任险司机 不计免赔
     *              ‘TCPLI_PASSENGER’ => array('PREMIUM' => float),      // 车上人员责任险乘客
     *              ‘TCPLI_PASSENGER_NDSI’ => array('PREMIUM' => float), // 车上人员责任险乘客 不计免赔
     *              ‘BSDI’，         // 车身划痕险
     *              ‘BSDI_NDSI’，    // 车身划痕险 不计免赔
     *              ‘BGAI’，         // 玻璃单独破碎险
     *              ‘NIELI’，        // 新增设备损失险
     *              ‘NIELI_NDSI’，   // 新增设备损失险 不计免赔
     *              ‘VWTLI’，        // 发动机涉水险
     *              ‘VWTLI_NDSI’，   // 发动机涉水险 不计免赔
     *              ‘STSFS’，        // 指定专修厂
     *              ‘RDCCI’，        // 修理期间费用补偿险
     *              ‘MVLINFTPSI’，   // 第三方特约险
     *          )
     *      )
     * )
     */
    private function resolveResult(array $result)
    {
        $return = array();
        $message = $this->array_get($result, 'message', '');
        $questionAnswer = $this->array_get($result, 'checkInfoVo.questionAnswer', '');
        if (!empty($questionAnswer)) {
            $message = $message . '<br/><br/>' . $questionAnswer;
        }

        $return['MESSAGE'] = $message;
        $compulsoryInsuransVo = $this->array_get($result, 'compulsoryInsuransVo', array());

        $return['MVTALCI'] = array(
            'MVTALCI_DISCOUNT' => 1,          // 交强险折扣:未找到折旧
            'MVTALCI_PREMIUM' => $this->array_get($compulsoryInsuransVo, 'cipremium', 0),             // 交强险保费
            'MVTALCI_END_TIME' => $this->formatDate($this->array_get($compulsoryInsuransVo, 'stEndDate')),           // 交强险结束时间
            'MVTALCI_START_TIME' => $this->formatDate($this->array_get($compulsoryInsuransVo, 'stStartDate')),         // 交强险生效时间
            'TRAVEL_TAX_PREMIUM' => $this->array_get($compulsoryInsuransVo, 'payableAmount', 0),           // 车船税
        );

        $commercialInsuransVo = $this->array_get($result, 'commercialInsuransVo', array());

        $return['BUSINESS'] = array(
            'BUSINESS_DISCOUNT' => $this->array_get($commercialInsuransVo, 'premiumRatio', 0),         // 商业险折扣
            'BUSINESS_DISCOUNT_PREMIUM' => $this->array_get($commercialInsuransVo, 'premium', 0),      // 商业险扣后保费合计
            'BUSINESS_PREMIUM' => $this->array_get($commercialInsuransVo, 'premium', 0),         // 保费总计
            'BUSINESS_START_TIME' => $this->formatDate($this->array_get($commercialInsuransVo, 'stStartDate')),    // 开始时间
            'BUSINESS_END_TIME' => $this->formatDate($this->array_get($commercialInsuransVo, 'stEndDate')),      // 结束时间
            'BUSINESS_ITEMS' => array()
        );
        // 商业险项目
        $quoteInsuranceVos = $this->array_get($result, 'quoteInsuranceVos', array());
        $businessItem = array();

        foreach ($quoteInsuranceVos as $value) {
            $zsKey = $this->transfromBusiness($value['insuranceCode']);
            if (strpos($zsKey, 'NDSI')) {
                $businessItem[$zsKey] = array(
                    'PREMIUM' => $value['nonDeductible']
                );
            } else {
                $businessItem[$zsKey] = array(
                    'PREMIUM' => $value['premium']
                );
            }
        }

        $return['BUSINESS']['BUSINESS_ITEMS'] = $businessItem;

        return $return;
    }

    /**
     * 转换商业险代码
     *
     * @param $search
     * @return mixed
     */
    public function transfromBusiness($search)
    {
        $transfromArray = array(
            'TVDI' => 'DAMAGELOSSCOVERAGE',                                                         // 车辆损失险
            'TVDI_NDSI' => 'DAMAGELOSSEXEMPTDEDUCTIBLESPECIALCLAUSE',                               // 车辆损失险 不计免赔
            'TWCDMVI' => 'THEFTCOVERAGE',                                                           // 盗抢
            'TWCDMVI_NDSI' => 'THEFTCOVERAGEEXEMPTDEDUCTIBLESPECIALCLAUSE',                         // 盗抢 不计免赔
            'TTBLI' => 'THIRDPARTYLIABILITYCOVERAGE',                                               // 第三方责任险
            'TTBLI_NDSI' => 'THIRDPARTYLIABILITYEXEMPTDEDUCTIBLESPECIALCLAUSE',                     // 第三方责任险 不计免赔
            'TCPLI_DRIVER' => 'INCARDRIVERLIABILITYCOVERAGE',                                       // 车上人员责任险司机
            'TCPLI_DRIVER_NDSI' => 'INCARDRIVERLIABILITYEXEMPTDEDUCTIBLESPECIALCLAUSE',             // 车上人员责任险司机 不计免赔
            'TCPLI_PASSENGER' => 'INCARPASSENGERLIABILITYCOVERAGE',                                 // 车上人员责任险乘客
            'TCPLI_PASSENGER_NDSI' => 'INCARPASSENGERLIABILITYEXEMPTDEDUCTIBLESPECIALCLAUSE',       // 车上人员责任险乘客 不计免赔
            'SLOI' => 'SELFIGNITECOVERAGE',                                                         // 自燃损失险
            'SLOI_NDSI' => 'SELFIGNITEEXEMPTDEDUCTIBLESPECIALCLAUSE',                               // 自燃损失险 不计免赔
            'BSDI' => 'CARBODYPAINTCOVERAGE',                                                       // 车身划痕险
            'BSDI_NDSI' => 'CARBODYPAINTEXEMPTDEDUCTIBLESPECIALCLAUSE',                             // 车身划痕险 不计免赔
            'BGAI' => 'GLASSBROKENCOVERAGE',                                                        // 玻璃单独破碎险
            'NIELI' => 'NEWEQUIPMENTCOVERAGE',                                                      // 新增设备损失险
            'NIELI_NDSI' => 'NEWEQUIPMENTEXEMPTDEDUCTIBLESPECIALCLAUSE',                            // 新增设备损失险 不计免赔
            'VWTLI' => 'PADDLEDAMAGECOVERAGE',                                                      // 发动机涉水险
            'VWTLI_NDSI' => 'PADDLEDAMAGEEXEMPTDEDUCTIBLESPECIALCLAUSE',                            // 发动机涉水险 不计免赔
            'STSFS' => 'APPOINTEDREPAIRFACTORYSPECIALCLAUSE',                                       // 指定专修厂
            'RDCCI' => 'REPAIRPERIODCOMPENSATIONSPECIALCLAUSE',                                     // 修理期间费用补偿险
            'MVLINFTPSI' => 'DAMAGELOSSCANNOTFINDTHIRDSPECIALCOVERAGE'                              // 第三方特约险
        );

        $s = array_search($search, $transfromArray);
        if (!$s) {
            $s = array_search($search, array_flip($transfromArray));
        }

        return $s;
    }

    /**
     * 将array转换为json,并不编码中文
     *
     * @param array $array
     * @return string
     */
    private function arrayToJson(array $array)
    {
        array_walk_recursive($array, array(__CLASS__, 'encodeString'));

        return str_replace('"meta":[]', '"meta":{}', urldecode(json_encode($array)));
    }

    private function formatDate($stringDate = "")
    {
        return empty($stringDate) ? "" : date('Y-m-d H:i:s', strtotime($stringDate));
    }

    /**
     * 将图片进行编码
     *
     * @param $imageFile
     * @return string
     */
    public function img2Base64($imageFile = '')
    {
        $img_info = getimagesize($imageFile);
        // 取得图片的大小，类型等
        $fp = fopen($imageFile, "r");     //图片是否可读权限
        if ($fp) {
            $file_content = chunk_split(base64_encode(fread($fp, filesize($imageFile))));//base64编码
            switch ($img_info[2]) {  //判读图片类型
                case 1:
                    $img_type = "gif";
                    break;
                case 2:
                    $img_type = "jpg";
                    break;
                case 3:
                    $img_type = "png";
                    break;
            }
            $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码
            fclose($fp);
            return $img_base64;
        }
    }

    public static function encodeString(&$value)
    {
        if ($value && $value !== true) { // 避免将null false true转换
            $value = urlencode($value);
        }
        return $value;
    }

    /**
     * 保存内容到缓存目录，用于调试
     *
     * @param $fileName
     * @param $needle
     */
    public function saveCache($fileName, $needle)
    {
        if (is_array($needle) or is_object($needle)) {
            $needle = serialize($needle);
        }
        file_put_contents($this->codeCachePath . $fileName, $needle);
    }
}
