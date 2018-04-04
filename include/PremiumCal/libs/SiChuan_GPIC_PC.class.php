<?php

/**
 * 中国人寿财产保险股份有限公司
 *
 * 国寿财的各种请求都是原始的GET POST 请求。没有使用JSON类型等
 *
 * User: xstoop
 * Date: 2017/6/15
 * Time: 9:51
 */
class SiChuan_GPIC_PC
{
    const formFile = 'Calculate.tpl';
    const company = 'GPIC';

    public $error = array();
    public $loginInfo = array();
    public $config = array();

    private $setItems = array(
        'username' => '登录名',
        'password' => '密码',
//        'channelType' => '销售渠道',
        'department' => '归属部门代码',
        'handlerCode' => '业务员/产险专员代码',
        'businessNature' => '业务来源代码', // 1.个人代理，2.专业代理
        'agentCode' => '代理人/经纪人/寿险机构代码',
        'agreementNo' => '代理协议号',
    );

    private $comInfo = array();

    /**
     * CURL默认的头信息
     *
     * @var array
     */
    private $curlDefaultHeaders = array(
        'Accept' => '*/*;',
        'Accept-Encoding' => 'gzip, deflate',
        'Accept-Language' => 'zh-CN',
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Host' => '9.0.6.69:7001',
        'Pragma' => 'no-cache',
    );

    /**
     * curl默认的设置项
     *
     * @var array
     */
    private $curlDefaultOptions = array(
        CURLOPT_USERAGENT => "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E; InfoPath.2)",
        CURLOPT_SSL_VERIFYPEER => false,                // 关闭SSL验证
        CURLOPT_SSL_VERIFYHOST => 0,                    // 关闭SSL名称验证
        CURLOPT_ENCODING => 'gzip,deflate',             // 解释gzip
        CURLOPT_TIMEOUT => 30,                          // 设置超时时间
        CURLOPT_CONNECTTIMEOUT => 30,                   // 设置连接超时时间
        CURLOPT_RETURNTRANSFER => 1,                    // 获取的信息以文件流的形式返回
        CURLINFO_HEADER_OUT => 1,                       // 打印请求头信息
//        CURLOPT_FOLLOWLOCATION => 1,
//        CURLOPT_PROXY => '192.168.2.120',               // 设置代理
//        CURLOPT_PROXYPORT => 808
    );

    public $_info = Array();

    public $cachePath;

    public $cookieFile;

    /**
     * SiChuan_GPIC_PC constructor.
     *
     * @param array $config
     * @param string $cachePath
     */
    public function __construct($config, $cachePath = '')
    {
        $this->config = $config;
        $this->loginInfo = array(
            'username' => $this->array_get($config, 'username'),
            'password' => $this->array_get($config, 'password'),
            'channelType' => $this->array_get($config, 'channelType'),
            'department' => $this->array_get($config, 'department'),
            'handlerCode' => $this->array_get($config, 'handlerCode'),
            'businessNature' => $this->array_get($config, 'businessNature'),
            'agentCode' => $this->array_get($config, 'agentCode'),
            'agreementNo' => $this->array_get($config, 'agreementNo'),
        );

        if (empty($cachePath)) {
            $this->cachePath = dirname(__FILE__);
        } else {
            $this->cachePath = $cachePath;
        }

        $this->cookieFile = $this->cachePath . '/' . __CLASS__ . '/cookie.txt';
        $this->codeCachePath = $this->cachePath . '/' . __CLASS__ . '/';
        if (!is_dir($this->codeCachePath)) {
            mkdir($this->codeCachePath);
        }
    }

    public function getSetItems()
    {
        return $this->setItems;
    }

    public function getFormFile()
    {
        return self::formFile;
    }

    public function getLastError()
    {
        return $this->error;
    }

    /**
     * 车辆信息查询
     *
     * @param array $info
     * @return array|false
     */
    public function queryBuyingPrice($info = array())
    {
        $model = $this->array_get($info, 'model', '');
        $vinNo = $this->array_get($info, 'vin_no', '');
        $page = $this->array_get($info, 'page', 1);

        $items = $this->queryCarByModel($model, $vinNo, 1); // 默认为第一页。第一页的数据会大于10

        if (false === $items) {
            return false;
        }
        if (empty($items)) {
            return array(
                'total' => 0, //总页数
                'page' => $page,
                'records' => 0,
                'rows' => array()
            );
        }
        $recordsTotal = count($items);
        $pageTotal = ceil($recordsTotal / 10);
        $items = array_slice($items, ($page - 1) * 10, 10); // 手动分页取数据
        $result = array();

        foreach ($items as $key => $item) {
//            list($modelName, $modelCode, $exhaustScale, $seatCount, $tonCount, $carActualValue, $purchasePrice, $countryNature, $curbWeightMin, $curbWeightMax, $series_Id, $carModelID, $LocalPurchasePrice, $riskType, $FUELCODE, $platModelCode, $platModelName, $platStandardName, $FCVEHICLE, $carBrand, $newClassCode, $newClassName, $riskOtherFlag, $riskOtherName)
            // 取值下标不同地区可能会有变化
            $result[] = array(
                'nXhKindpriceWithouttax' => 0,
                'szxhTaxedPrice' => 0,
                'vehicleAlias' => $item[16],                                  // 车型别名
                'vehicleDisplacement' => $item[2],                            // 排气量
                'vehicleId' => $item[1],                                      // 车型代码 *
                'vehicleMaker' => '',
                'vehicleName' => $item[11],                                   // 车型名称
                'vehiclePrice' => $item[6],                                   // 新车购置价 *
                'vehicleSeat' => $item[3],                                    // 核定载客人数 *
                'vehicleTonnage' => '',                                       // 核定载质量
                'vehicleWeight' => $item[8],                                  // 整车质量吨
                'vehicleYear' => '',                                          // 上市年份
                'xhKindPrice' => 0
            );
        }

        return array(
            'total' => $pageTotal, //总页数
            'page' => $page,
            'records' => $recordsTotal, // 总条数
            'rows' => $result
        );
    }

    /**
     * 车辆折旧价计算
     *
     * @param array $info
     * @return bool|mixed
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
     * 设备折旧价计算
     *
     * @param  array $info
     * @return Mixed
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
     * 保费计算
     *
     * @param array $auto
     * @param array $business
     * @param array $mvtalci
     * @return array|false
     */
    public function premium($auto = array(), $business = array(), $mvtalci = array())
    {
        $result = array(
            'BUSINESS' => array(),
            'MVTALCI' => array(),
            'MESSAGE' => '',
        );

        if (!empty($business['POLICY']['BUSINESS_ITEMS']) || !empty($mvtalci)) {
            // 参数预组装
            $buildResult = $this->buildPremium($auto, $business, $mvtalci);
            if (false === $buildResult) {
                return false;
            }
            $message = '';

            //商业险计算
            if (!empty($business['POLICY']['BUSINESS_ITEMS'])) {
                // 先进行平台查询 获取一些参数，以及该车辆的投保消息。
                $prePremiumResult = $this->prePremium();
                if (false === $prePremiumResult) {
                    return false;
                }

                // 在用获取到的参数组装保费计算的参数 发起商业险保费计算
                $businessPremiumResult = $this->doBusinessPremium($business, $prePremiumResult);
                if (false === $businessPremiumResult) {
                    return false;
                }
                $result['BUSINESS'] = $businessPremiumResult;
                $message = $prePremiumResult['message'];
            }

            // 交强险计算
            if (!empty($mvtalci)) {
                $mvtalciPremiumResult = $this->doMvtalciPremium($mvtalci);
                if ($mvtalciPremiumResult === false) {
                    return false;
                }
                $message = empty($message) ? $mvtalciPremiumResult['message'] : $message . '<br/>' . $mvtalciPremiumResult['message'];
                unset($mvtalciPremiumResult['message']);
                $result['MVTALCI'] = $mvtalciPremiumResult;
            }
            $result['MESSAGE'] = $message;
        }
        return $result;
    }

    /**
     * 平台查询与保费计算的参数
     *
     * @var string
     */
    private $premiumParams;

    /**
     * 发起保费计算器前的平台查询请求
     *
     * @return mixed
     */
    public function prePremium()
    {
        if (!empty($this->premiumParams)) {
            $url = 'http://9.0.6.69:7001/prpall/indiv/ci/tbcbpg/UIPrPoEnGetSinoProfit.jsp';
            $result = $this->postCheckLogin($url, $this->premiumParams);
            return $this->resolvePrePremium($result);
        }
        $this->error['errorMsg'] = '参数未组装-1';
        return false;
    }

    /**
     * 解析平台查询结果
     *
     * @param string $html
     * @return array|bool
     */
    public function resolvePrePremium($html)
    {
        if (empty($html)) {
            $this->error['errorMsg'] = '商业险 - 平台查询结果获取失败';
            return false;
        }
        $result = array();

        // 获取所有赋值信息
        $pattern = '/mainfm\.(.*?)\.value[\s]*=[\s]*[\'\"](.*?)[\'\"]\;/'; // mainfm.finalRatio.value='0.6141';
        if (preg_match_all($pattern, $html, $result1)) {
            $result = array_combine($result1[1], $result1[2]);
        } else {
            $this->error['errorMsg'] = '商业险 - 平台查询结果解析失败';
            return false;
        }

        $result['message'] = '';
        // 获取所有提示信息
        $pattern = '/alert\([\'\"](.*?)[\'\"]\)\;/s';
        if (preg_match_all($pattern, $html, $result2)) {
            $result['message'] = '商业险 - ' . str_replace(
                    array('\n', '"', '+'),
                    array('<br/>', "", ""),
                    implode('<br/>', $result2[1])
                );
        }
        return $result;
    }

    /**
     * 发起交强险保费计算请求
     *
     * @param $mvtalci
     * @return array|bool
     */
    public function doMvtalciPremium($mvtalci)
    {
        if (!empty($this->premiumParams)) {
            $url = 'http://9.0.6.69:7001/prpall/indiv/ci/tbcbpg/UIPrPoEnCIDemandInputSubmit.jsp';
            $result = $this->postCheckLogin($url, $this->premiumParams);
            return $this->resolveMvtalciPremium($mvtalci, $result);
        }
        $this->error['errorMsg'] = '参数未组装-2';
        return false;
    }

    /**
     * 解析交强险的算价结果
     *
     * @param array $mvtalci 交强险参数
     * @param string $html 交强险返回的页面
     * @return array|bool
     */
    public function resolveMvtalciPremium($mvtalci, $html)
    {
        if (empty($html)) {
            $this->error['errorMsg'] = '交强险算价结果获取失败';
            return false;
        }

        $mvtalciResult = array(
            'MVTALCI_DISCOUNT' => 1,
            'MVTALCI_END_TIME' => $mvtalci['MVTALCI_END_TIME'], // 交强险结束时间
            'MVTALCI_PREMIUM' => 0,  // 交强险保费
            'MVTALCI_START_TIME' => $mvtalci['MVTALCI_START_TIME'],// 交强险生效时间
            'TRAVEL_TAX_PREMIUM' => 0, // 车船税
            'message' => '',
        );

        // 获取所有赋值信息
        $pattern = '/mainfm\.(.*?)\.value[\s]*=[\s]*[\'\"](.*?)[\'\"]\;/'; // mainfm.finalRatio.value='0.6141';
        if (preg_match_all($pattern, $html, $result1)) {
            $result = array_combine($result1[1], $result1[2]);
            $mvtalciResult['MVTALCI_DISCOUNT'] = $this->array_get($result, 'AdjustRateCI', 1);
            $mvtalciResult['MVTALCI_PREMIUM'] = $this->array_get($result, 'PremiumCountCI', 0);
            $mvtalciResult['TRAVEL_TAX_PREMIUM'] = $this->array_get($result, 'TaxActual', 0);
        }

        // 获取所有提示信息
        $pattern = '/alert\([\'\"](.*?)[\'\"]\)\;/s';
        if (preg_match_all($pattern, $html, $result2)) {
            $messageTemp = implode('', $result2[1]);
            if (strlen($messageTemp) > 20) {
                $mvtalciResult['message'] = '<br/><br/>交强险 - ' . implode('', $result2[1]);
            }
        }

        return $mvtalciResult;
    }

    /**
     * 发起商业险保费计算请求
     *
     * @param $business
     * @return array|bool
     */
    public function doBusinessPremium($business, $prePremiumResult)
    {
        $premiumParams = $this->premiumParams;

        unset($prePremiumResult['message']);
        unset($prePremiumResult['BasePremium[j]']);
        // 使用prePremium返回的结果 替换参数
        foreach ($prePremiumResult as $key => $value) {
            $pattern = '/&' . $key . '=.*?&/';
            $replace = '&' . $key . '=' . $this->encodeString($value) . '&';
            $premiumParams = preg_replace($pattern, $replace, $premiumParams);
        }

        $url = 'http://9.0.6.69:7001/prpall/common/tbcbpg/UIPrPoEnCaculate.jsp';
        $result = $this->postCheckLogin($url, $premiumParams);

        return $this->resolveBusinessPremium($business, $result);
    }

    /**
     * 解析商业险算价结果
     *
     * @param string $html
     * @param array $business
     * @return array|bool
     */
    public function resolveBusinessPremium($business, $html)
    {
        if (empty($html)) {
            $this->error['errorMsg'] = '商业险算价结果获取失败';
            return false;
        }
        $businessResult = array();

        // 获取提示信息
        $pattern = '/alert\([\'\"](.*?)[\'\"]\)\;/s';
        if (!strpos($html, 'alert("--"+DautoClauseCodeList2)') && preg_match_all($pattern, $html, $result)) {
            $this->error['errorMsg'] = '商业险计算错误 - ' . implode('<br/>', $result[1]);
            return false;
        }

        // 返回的商业险数组结构
        $businessReturnTemplate = array(
            'BUSINESS_DISCOUNT',         // 商业险折扣 0.6141  mainfm.finalRatio.value='0.6141';
            'BUSINESS_DISCOUNT_PREMIUM', // 商业险扣后保费合计 2693.07 mainfm.Fg_SumPremium.value
            'BUSINESS_PREMIUM',         // 保费总计 2693.07
            'BUSINESS_START_TIME' => $business['BUSINESS_START_TIME'],    // 开始时间 !
            'BUSINESS_END_TIME' => $business['BUSINESS_END_TIME'],      // 结束时间 !
            'BUSINESS_ITEMS' => array(               // 商业险条目
                'TVDI',   // 车辆损失险 mainfm.Premium[vRowIndex].value
                'TVDI_NDSI',            // 车辆损失险 不计免赔 mainfm.Premium[vRowIndex].value
                'TWCDMVI',              // 盗抢
                'TWCDMVI_NDSI',         // 盗抢 不计免赔
                'TTBLI',                // 第三方责任险
                'TTBLI_NDSI',           // 第三方责任险 不计免赔
                'TCPLI_DRIVER',         // 车上人员责任险司机
                'TCPLI_DRIVER_NDSI',    // 车上人员责任险司机 不计免赔
                'TCPLI_PASSENGER',      // 车上人员责任险乘客
                'TCPLI_PASSENGER_NDSI', // 车上人员责任险乘客 不计免赔
                'BSDI',         // 车身划痕险
                'BSDI_NDSI',    // 车身划痕险 不计免赔
                'BGAI',         // 玻璃单独破碎险
                'NIELI',       // 新增设备损失险
                'NIELI_NDSI',  // 新增设备损失险 不计免赔
                'VWTLI',       // 发动机涉水险
                'VWTLI_NDSI',  // 发动机涉水险 不计免赔
                'SLOI',        // 自燃损失险
                'SLOI_NDSI',   // 自燃损失险 不计免赔
                'STSFS',       // 指定专修厂
                'RDCCI',       // 修理期间费用补偿险
                'MVLINFTPSI',   // 第三方特约险
            )
        );
        $businessKeyArray = $this->getBusinessOrderKey($business['POLICY']['BUSINESS_ITEMS']);

        foreach ($businessReturnTemplate as $itemKey) {
            if (is_array($itemKey)) {
                foreach ($itemKey as $item) {
                    if (isset($businessKeyArray[$item])) {
                        $key = $businessKeyArray[$item];
                        $businessResult['BUSINESS_ITEMS'][$item]['PREMIUM'] = $this->regularPremium($key, $html);
                    } else {
                        $businessResult['BUSINESS_ITEMS'][$item]['PREMIUM'] = 0.00;
                    }
                }
            } else {
                if (isset($businessKeyArray[$itemKey])) {
                    $key = $businessKeyArray[$itemKey];
                    $businessResult[$itemKey] = $this->regularPremium($key, $html);
                }
            }
        }

        return $businessResult;
    }

    /**
     *  获取GPIC的险种顺序对应的key，算价结果是按照这个顺序返回的，根据这个顺序来进行解析
     *
     * @param array $business 算价的险种信息
     * @return array
     */
    public function getBusinessOrderKey($business)
    {
        // 总价信息对应的Key
        $totalKey = array(
            'BUSINESS_DISCOUNT' => 'finalRatio',
            'BUSINESS_DISCOUNT_PREMIUM' => 'Fg_SumPremium',
            'BUSINESS_PREMIUM' => 'Fg_SumPremium',
        );
        // GPIC的险种顺序
        $oldOrder = array(
            'TVDI' => 1,
            'TTBLI' => 2,
            'TCPLI_DRIVER' => 3,
            'TCPLI_PASSENGER' => 4,
            'TWCDMVI' => 5,
            'BSDI' => 6,
            'BGAI' => 7,
            'SLOI' => 8,
            'VWTLI' => 9,
            'TVDI_NDSI' => 10,
            'TTBLI_NDSI' => 11,
            'TCPLI_DRIVER_NDSI' => 12,
            'TCPLI_PASSENGER_NDSI' => 13,
            'TWCDMVI_NDSI' => 14,
            'BSDI_NDSI' => 15,
            'SLOI_NDSI' => 16,
            'VWTLI_NDSI' => 17,
            'NIELI' => 18,   // 新增设备损失险 没找到
            'MVLINFTPSI' => 19,
            'NIELI_NDSI' => 20,
            'STSFS' => 21, // 指定专修厂 在第一个页面，先导信息里
            '车上货物责任险' => 22,
            '精神损害抚慰金责任险' => 23,
            'RDCCI' => 24, // 修理期间费用补偿险 没找到
            '车上货物责任险不计免赔' => 25,
            '精神损害抚慰金责任险不计免赔' => 26,
        );
        // 上面几个中文的key在zistsms中没有

        foreach ($oldOrder as $key => $value) {
            if (!in_array($key, $business)) {
                unset($oldOrder[$key]);
            }
        }

        $i = 1;
        foreach ($oldOrder as $key => $item) {
            $oldOrder[$key] = $i;
            $i++;
        }

        return array_merge($totalKey, $oldOrder);
    }

    /**
     * 正则匹配页面中报价项目结果
     *
     * @param string|int $premiumKey
     * @param string $premiumHtml
     * @return  int
     */
    public function regularPremium($premiumKey, $premiumHtml)
    {
        if (is_string($premiumKey)) {
            $pattern = '/mainfm\.' . $premiumKey . '\.value[\s]*=[\s]*\'(.*?)\'\;/'; // mainfm.finalRatio.value='0.6141';
        } elseif (is_numeric($premiumKey)) {
            $pattern = '/getRowIndex\(\'' . $premiumKey . '\'\)\;[\s]*vCount.*?mainfm\.Premium\[vRowIndex\]\.value[\s]*=[\s]*\'(.*?)\'\;/s';
        } else {
            return 0;
        }
        if (preg_match($pattern, $premiumHtml, $result)) {
            if (!empty($result[1])) {
                return $result[1];
            }
        }
        return 0;
    }

    /**
     * 组装GPIC算价的各项参数,返回urlencode 在GBK字符集下编码的字符串
     *
     * @param $auto
     * @param $business
     * @param $mvtalci
     * @return string | bool
     */
    public function buildPremium($auto, $business, $mvtalci)
    {
        // 身份证号码验证
        if (empty($auto['IDENTIFY_NO'])) {
            $auto['IDENTIFY_NO'] = $this->loginInfo['username'];
//            $this->error['errorMsg'] = '客户身份证号码不能为空！';
//            return false;
        }

        if (!$this->isCreditNo($auto['IDENTIFY_NO'])) {
            $this->error['errorMsg'] = '客户身份证号码检验失败，不是有效的身份证号码！';
            return false;
        }

        //先导信息 807 - 3452  strCertiNo -》 otherFoldFlag
        //投保人与被保险信息 3452-4429 UtiPower -》 BIInsureSZ104_isHistory
        //车辆信息 4805 - 10208 EndorseItemCar -》 Today
        //商业险信息 10349 - 12742 DemendNoNew -》 PlanFee2Charge（12706）   （button_ChargeMain_Delete 12712 - button_Charge_Insert12742 这中间的几个inpu没有被提交）
        // 其他 12742 - 13101  SchemeProtocolCode -》 shenzhenM7ComCode
        //交强险信息 13101 -16063 TaxRelifFlag -》 PlanFee2ChargeCI 16026  （button_ChargeMain_Delete 16032 - button_Charge_Insert 16062 这中间的几个inpu没有被提交）
        // 16063 - end 其他保险 SingleIdentityCI-》 end
        $paramString = '';
        for ($i = 1; $i <= 7; $i++) {
            $className = 'buildFormParams' . $i;
            $temp = $this->$className($auto, $business, $mvtalci);
            if ($temp === false) {
                return false;
            }
            $temp = trim($temp);
            if ($i == 1) {
                $paramString = $temp;
            } else {
                $paramString = $paramString . '&' . $temp;
            }
        }
        $this->premiumParams = $paramString;
        return $paramString;
    }

    /**
     * 组装 先导信息 807 - 3452  strCertiNo -》 otherFoldFlag
     * !!! 所有buildFormParams函数中的提交的POST数据中：字符串中除了 -_. 之外包含所有非字母数字的字符 的数据项，都需要使用$this->encodeString($str)函数。
     * 替换成GBK编码下的urlencode(百分号（%）后跟两位十六进制数)。
     * 因为国寿财的保险平台的所有html都是GBK编码。否则会返回无法解析数据的错误。
     *
     * @return string
     */
    public function buildFormParams1()
    {
        $EditType = 'NEW';// 编辑类型(默认new) : NEW 新保,COPY_PROPOSAL 复制投保单,COPY_POLICY 复制保单,RENEWAL 在本公司续保,DIFFCOPY_PROPOSAL 复制不同险种的投保单,DIFFCOPY_POLICY,复制不同险种的保单
        $CIInsurerType = '1'; // 是否在本公司投保强制保险(默认1)：1 是 ，0 否
        $CIArmyFlag = '0';  // 是否是部队、武警、场院车辆和挂车(默认0)：1 是 ，0 否
        $CIInsuredNewFlag = '1';// 是否同步投保强制保险(默认1)：1 是 ，0 否

        $BusinessNature = $this->array_get($this->loginInfo, 'businessNature', 2); // 业务来源：代码 默认2
        $BusinessNatureName = ''; // 业务来源：名称 默认专业代理
        $ChannelType = $this->array_get($this->loginInfo, 'channelType', '09');  // 销售渠道(默认09): 09 普通中介, 08 个代, 07 银保, 06 车商, 05 非车/重客/综拓,03 互动
        $AgentCode = $this->array_get($this->loginInfo, 'agentCode');  // 代理人/经纪人/寿险机构:代码
        $AgentName = ''; // 代理人/经纪人/寿险机构:名称

        $AgentDays = '';  // ?代理人剩余有效天数
        $PerendDate = ''; // ?代理人有效期终止日期
        $AgentStartSysDays = ''; // ?代理人startdate-sysdate

        $comInfo = $this->getLoginComInfo();

        if (false === $comInfo) {
            return false;
        }

        $ComCodeSession = $MakeCom = $LoginComCode = $comInfo['riskCode'];

        $ComCode = $this->array_get($this->loginInfo, 'department', '09'); // 归属部门代码
        $ComName = ''; // 归属部门名称

//        $GroupType = $this->getGroupType($comInfo['code']);
        $GroupType = '';
        if (false === $GroupType) {
            return false;
        }

        $AgreementNo = $this->array_get($this->loginInfo, 'agreementNo', '09'); // 代理协议号

        $hander = $this->getHander();

        if (false === $hander) {
            return false;
        }

        $Handler1Code = $hander['code']; //'500234199012251864';  // 业务员/产险专员 代码
        $Handler1Name = $this->encodeString($hander['name']); // 业务员/产险专员 名称

        $MainRemark = ''; // 备　注：
        $ArgueSolution = '1'; // 争议解决方式(默认1)： 1 诉讼, 2 仲裁

        $CarChecker = $hander['code'];  // 验车人代码：同 业务员/产险专员
        $CarCheckerName = $this->encodeString($hander['name']);   // 验车人姓名：同 业务员/产险专员
        $CarCheckTime = $this->encodeString(date('Y-m-d H:i')); // 验车时间默认去当前时间：格式2017-06-23 15:03
        $IsTJRepairFactory = '1'; // 是否推荐修理厂(默认是)：1 是，2 否
        $IPAddress = ''; //9.144.140.48

        $paramString = <<<EOF
strCertiNo=&isProvisonal=0&SaveOnlyCIFlag=0&buttonCancel=%C8%A1+%CF%FB&SumPayTaxAndPremium=&resultsourceflag=&EditType=$EditType&BizNo=&JfeePayType=2&CIInsurerType=$CIInsurerType&CIArmyFlag=$CIArmyFlag&CIInsuredNewFlag=$CIInsuredNewFlag&CIUndwrtPriorityFlag=1&CIInsurerBizNo=&CIInsurerCompany=&undefind=&CIINSUREV602=false&BIINSUREV7014=true&LSValidStatusFlag=1&ControlOpenPoundageCom=true&ControlPoundageCom=true&SetRuleEngine=false&idcard=false&FeiGaiCompanyFlag=true&PlatformCacheColumnConfig=0&PlatformCacheColumnConfigNew=1&CaculateAllStep=0&CaculateStep=&RuleRequestType=N&PlatformDemendNo=&hisFlag=&insuProKey=&sellProKey=&claProKey=&provisonalEdit=&isAutoAppliFlag=&IsUpdateMotorProfit=&SameToApplicantTemp=&SameToInsuredTemp=&IsUsbFlag=1&PolicynoRenewal=&PolicyCityCode=&PolicyAreaCode=&ChargingBasicsType=&protocolType=&protocolNo1=&motorCadeBusiness=&signMarking=&insuProKeyFlag=0&sellProKeyFlag=0&claProKeyFlag=0&resultUseTypeFlag=+&UnionModifyType=&EDITTYPE=NEW&BIZTYPE=PROPOSAL&RULEOFNEWCOMMISSION_MOTORCADE=false&CommonRisk=&checkFlag=1&orderStatus=&OldPolicyNo=&ClassCode=05&ManyAgentToOneFlag=true&carTaxpayerInput=1&TransferEndorseType=&CarToMotorcadeEndorType=&MotorcadeToCarEndorType=&CIINSURE_V300=true&CIINSURE_V300_MOTOPlat=false&CIINSURE_V300_MOTOPlat_OFF=false&KtaxComIsShow=false&ShowCarOwner=true&PaidFreeCertificateIsShow=false&CIINSURE_V300_TractorPlat=false&HistoryPolicyCIInsureV300=0&CARSHIPTAX2012=true&BIInsureV212=false&ReturnTaxActualFlag=false&NoDrawbackCarshipTaxFlag=false&isCIRenFlag=0&CIInsureV262=true&CIInsureV264=false&CIInsureBJV295=false&IsInputCertifyDate=true&CARSHIPTAXTJSWITCH=1&CARSHIPTAXTJWSCSWITCH=1&InOperatorPower=true&LoginComCode=$LoginComCode&LoginUserCode={$this->loginInfo['username']}&LoginComCode1=$ComCode&AwardFactor=0&IsBIInsure=true&ControlTransferDate=false&DriverEnage=true&CIZJIAZJIU=true&IsBIVersion=true&BICarModelFlag=false&ZJH1OnLineDate=2010-05-28&NBMotorOnLineDate=2010-11-15&JSMotorOnLineDate=2010-06-29&HLJMotorOnLineDate=2012-12-14&CIInsuredPlatComcode=43%2C32%2C33%2C34%2C37%2C36%2C4403%2C3302%2C23%2C14%2C41%2C13%2C15%2C15%2C13%2C3502%2C44%2C51%2C53%2C61%2C45%2C35%2C46%2C52%2C62%2C50%2C12%2C65%2C42%2C21%2C3702%2C22%2C2102%2C%2C63%2C%2C64%2C11%2C&PurchasePriceNotFloatingComcode=%2C&GXTaxedPriceOnlineDate=2010-08-30&JHZJMode=1&policyInputDate=&compareInputDate=&BIInsureSZ=false&historyPolicySZ=2011-02-22&historyPolicySZFlag=0&historyCXPolicySZFlag=0&historyCXPolicyJSFlag=0&historyCXPolicy37Flag=0&historyCXPolicyFlag=0&historyPolicyGXFlag=0&businessNatureLink=&LifeInsuranceFlag=false&CarShipTaxPlatCalFlag=true&CIINSURE_SINOSOFT_CARSHIPTAX=true&TJCarshiptaxNew=2012-09-20&SalesCallDisrate=false&ComcodeManageRate=true&ChannelManageRate=%2C03%2C&compareEnrollDate=2015-09-11&AssociateFlag=&PolicyClaimGuid=&isRelationInsurance=true&isChoiceFlag=1&isHDflagBI=1&isHDflagCI=1&IsArmyFlag=&chgOwnerUnionFlag=&PolicyCINo=&ContinueDealFlag=&ChgOwnerCIDemandNo=&oldPtextCI=&agentFlag=&agentCodeNew=&agentNameNew=&Handler2profNoNew=&Handler2NameNew=&handler2PhoneNumberNew=&handler2Flag=&Handler3profNoNew=&AgreementNoNew=&IsRenewal=null&isGuaCarMain=false&RFQueryButton=true&CIV555CarshipTax=true&checkCredentialNo=false&CarAddressCodeTJFlag=1&IsUseMShowFlag=Y&checkCarUsingFlag=false&AcceptFullEndorPolicyNo=805112016441322000802%2C805112016410100015552%2C805112016231099000776%2C805112016411622000325%2C805112016370881000625%2C805112016430130002068%2C805112016441397003487%2C805112016220322000244%2C805112016410103012639%2C805112016410103012690%2C805112016410181001501%2C805112016129971007886%2C805112016150627000585%2C805112016431103000316%2C805112016129700010083%2C805112016511301000359%2C805112016410100031043%2C805112016410112008406%2C805112016340800001096%2C805112016411525001487%2C805112016410182001165%2C805112016129981003073%2C805112016440605005402%2C805112016230898000047%2C805112016511097000520%2C805112016411625000365%2C805112016411627000360%2C805112016430732000323%2C805112016411403000318%2C805112016500106001440%2C805112016440697021947%2C805112016230283000075%2C805112016231100000670%2C805112016442001002862%2C805112016341021001557%2C805112016370883003138%2C805112016330300000091%2C805112016440797011727%2C805112016129971010986%2C805112015450231000330%2C805112016340481000743%2C805112016440705000408%2C805122016610114000006%2C805112016341791003744%2C805112016230502000273%2C805112016430130003332%2C805112016129700001360%2C805112016440697009487%2C805112016150602001056%2C805112016610626000446%2C805112016411600004571%2C805112016340292001245%2C805112016500117001387%2C805112015440697000174%2C805112015230881000288%2C805112015371000004100%2C805112016411522000012%2C805112016340390001177%2C805112016341090001755%2C805112016220100001673%2C805112016410110037708%2C805112016421197000267%2C805112016431022000913%2C805112016370261000176%2C805112016610697000181%2C805112016610825000062%2C805112016431302000426%2C805112016640197000093%2C805112016420197001173%2C805112016370397001117%2C805112016430732000163%2C805112016129700000414%2C805112016370281001175%2C805112016500199009345%2C805112016430783000208%2C805112016370697001489%2C805112016500199019876%2C805112016610300000157%2C805112016341621000271%2C805112016220897000199%2C805112016510105001961%2C805112016129981001723%2C805112016129981001728%2C805112016371421000858%2C805112016430104002638%2C805112016440190016464%2C805112016530191000255%2C805112016522728000077%2C805112016321297000653%2C805112016321284001439%2C805112016440190024280%2C805112016320593001548%2C805112016230106001320%2C805112016320190000194%2C805112016231003000769%2C805112016321103000212%2C805112016410325000421&AcceptFullEndorSwitch=true&BIVERSION7012=false&VINQuerySwitch=true&checkBIMO=false&hasAgentBankComCode=false&VipFlag=true&CarShiptTaxTJFlag=false&ProfessionalFlag=false&BIInsureV200=false&BIInsureV325=false&BIInsureV325_onLineTime=2013-12-28&NeedCarModel=false&BIInsureSZ103=false&businessNaturePrintJS=false&businessNaturePrintJSLink=&BIInsureV142=true&FactorPlaceFlag=0&GTFloatRateModel=false&NoApproveFlag=&ProposalLevel=&EndorseOption=null&OldPolicy=&ValidDate=null&EndorDate=null&pComCode=null&EndorseType=&oldPtext=&EndorseAppli=null&BaseAmount=72900.00&MotorCadeNature=&MotorCadeCoeff=&MainVisaCode=&LocTypeCode=null&VisaSerialNo=null&VisaHandlerCode=null&ALLOW_DISPLAY_SPECIALITEM=2&dlfalg=&isCIInsure=1&underWriteFlag=&oneKindCodeOfFlag=&JFeeFlag=&ExchangeRate=&TEIniTime=2017-06-23+15%3A03%3A45&OPRemarkID=20170623150345248528387&StopTimes=&ssOrderNo=&ssOrderNoCI=&vipIntSize=&vipPID=&vipPSNNAME=&vipPSNLEVEL1=&vipPSNLEVEL2=&vipCARDNO=&vipCARDTYPE=&vipPSNTYPE=&vipImagePath=&vipStartDate=&vipEndDate=&ProtocolCode=&CalType=&CIINSUREV600=true&CIINSUREJSV600=false&UsbKeyFlag=0&UsbKeyReadFlag=&UsbKeyReadSucFlag=&ICCardFlag=N&ICCardSucFlag=&ICCardNo=&ICCompanyCode=&ICAgentName=&ICAgentLicNo=&ICChuDanDianName=&ICChuDanDianAddr=&ICValidBeginYear=&ICValidBeginMonth=&ICValidBeginDay=&ICValidEndYear=&ICValidEndMonth=&ICValidEndDay=&ICCardProTime=&IntermediarybusinessAddIPAddr=false&IPAddress=$IPAddress&UsbKey0503Distance=&BusinessNature=$BusinessNature&BusinessNatureName=$BusinessNatureName&BusinessNature_Flag=&ChannelTypeLink=%2C07%2C05%2C01%2C%2C08%2C09%2C&ChannelType=$ChannelType&AgentCode=$AgentCode&AgentName=$AgentName&AgentDays=$AgentDays&PerendDate=$PerendDate&AgentStartSysDays=$AgentStartSysDays&ComCodeSession=$ComCodeSession&AgentCodeOfPdagentSub=&btSelectAgentProtocol=%D1%A1%D4%F1%D0%AD%D2%E9&hidden=&ComCode=$ComCode&ComName=$ComName&GroupType=$GroupType&AgreementNo=$AgreementNo&Agreement=%B4%FA%C0%ED%D0%AD%D2%E9%BA%C5%A3%BA&AgreeDays=250.00&AgreementEndDate=2018-02-28&AgreeStartSysDays=-539.00&AgreementFlag=N&GroupNatureFlag=&GroupNature01Flag=1&Handler1Code=$Handler1Code&Handler1Name=$Handler1Name&HandlerCode=$Handler1Code&UserTypeDomain=04&HandlerName=$Handler1Name&Handler1profNo=&SalesCompanyCode=&FactoryCode=&FactoryName=&Handler3profNo=&Handler2Name=&Handler2profNo=undefined&handler2PhoneNumber=undefined&ApproveCodeBJ=&ApproveNameBJ=&FactorPlaceCode=&FactorPlaceName=&RiskCode=0511&RiskName=%BB%FA%B6%AF%B3%B5%D7%DB%BA%CF%C9%CC%D2%B5%B1%A3%CF%D5&ContractNo=&GroupCode=&ProposalNo=&EndorseNo=&ProposalNo1=&PolicyNo=&PrintNo_Flag=&PrintNo=null&PrintNoCI=&MainVisaCodeCI=&MainHead_Flag=&GoalInsuredFlag=0&PolicySort=0&SystemSource=&BankCode=&BankCName=&IsComCode=&RenewalFlag=0&RenewalFlagName=%B7%C7%D0%F8%B1%A3&OldPolicyNoRenewal=&Language=C&ProjectsFlag=0&ShareHolderFlag=0&ShareHolderName=0&AgriType=0&DisRateX=&agentPersonName=&identifyType=01&AgentidentifyNumber=&telephoneNumber=&zipCode=&agentPersonAddress=&SQDBUnitTaxRegisterNumberStartDate=&SQDBUnitTaxRegisterNumberEndDate=&foldFlag=1&MakeCom=$MakeCom&PTAccountFlag=0&AppliNameother=&Tail_Flag=&CheckEmail=&ProducerProposalCheckEmail=&DefaultCarChecker={$this->loginInfo['username']}&checkUseQueryButton=true&MainRemark=$MainRemark&ArgueSolution=$ArgueSolution&ArbitBoardCode=&ArbitBoardName=&CarCheckStatus=1&CarCheckReason=1&CarChecker=$CarChecker&CarCheckerName=$CarCheckerName&CarCheckTime=$CarCheckTime&IsTJRepairFactory=$IsTJRepairFactory&TJRepairFactoryOrderNo=&showColumn=&TJRepairFactoryName=&ReinsFlag=0&OtherNature4=1&Other_Flag=&OperatorCodeFC=&IsFocus=0&BusinessID1=&GetWay=1&InsureMode=1&GetWayDesc=&optfxqky=0&OperatorCodeFC=&BankbusinessFlag=0&BankbusinessBankCode=&BankbusinessBankName=&otherFoldFlag=1
EOF;
        return $paramString;
    }

    /**
     * 投保人与被保险信息 3452-4429 UtiPower -》 BIInsureSZ104_isHistory
     *
     * @return string|false
     */
    public function buildFormParams2($auto, $business, $mvtalci)
    {
        $userInfo = array(
            'identify' => $auto['IDENTIFY_NO'], // $auto['IDENTIFY_NO']
            'name' => $auto['OWNER'],        // $auto['OWNER']
            'phone' => $auto['MOBILE'], // $auto['MOBILE']
            'address' => 'xxx'  // xxxx
        );
        $identify = $userInfo['identify'];
        $name = $this->encodeString($userInfo['name']);
        $phone = $userInfo['phone'];

        $gpicClientInfo = $this->getClientInfo($userInfo);
        if (false === $gpicClientInfo) {
            return false;
        }

        $address = $this->encodeString($gpicClientInfo['prpDcustomerIdvLinkAddress']);

        $prpDcustomerIdvIdentifyType = '01';
        $prpDcustomerIdvIdentifyNumber = $identify;

        // 投保人信息
        // $CustomerTypeAppli = '1'; // 客户类型 1个人，2组织。
        // $prpDcustomerIdvIdentifyType0 = '01';  // 证件类型 01身份证
        $prpDcustomerIdvIdentifyNumber0 = $identify; // 身份证号码
        // $prpDcustomerUnitOrganizeIdentifyType0 = '07'; // 组织机构证件类型
        $AppliMobile = $phone; // 手机号码
        $AppliName = $name; // 投保人名称/姓名
        $AppliSex = substr($identify, -2, 1) % 2 == 0 ? '2' : '1'; // 性别 1男 2女;
        $AppliAge = date('Y') - (int)substr($identify, 6, 4);
        $AppliIdentifyNumber = $identify;
        $AppliAddress = $address; // 投保人地址
        $AppliLinkerName = $AppliName;
        $AppliCode = $gpicClientInfo['prpDcustomerIdvCustomerCode'];
        $AppliRegistID = $gpicClientInfo['RegistID'];
        $AppliBirthDay = $gpicClientInfo['BirthDate'];
        $InsuredRegistID = $AppliRegistID;
        $IdentifyNumber = $identify;

        // 被保险人信息
        // $CustomerTypeInsure = '1'; // 客户类型 1个人，2组织
        // $prpDcustomerIdvIdentifyType1 = '01'; // 证件类型 01身份证
        $prpDcustomerIdvIdentifyNumber1 = $identify; // 身份证号码
        $InsuredMobile = $phone; // 被保险人手机号码
        $InsuredName = $name; // 姓名
        $InsuredAddress = $address; // 被保险人住所
        $InsuredLinkerName = $name;
        $InsuredCode = $gpicClientInfo['prpDcustomerIdvCustomerCode'];
        $InsuredAge = date('Y') - (int)substr($identify, 6, 4);

        // 车主信息
        //$CarInsuredRelation = '1'; // 与被保险人车辆的关系 1所有，2使用，3管理
        //$VehicleOwnerNature = '7'; // 车主性质代码 7个人
        $CredentialNo = $identify; // 身份证号码
        $MailingAddress = $address; // 通讯地址
        $CarOwner = $name; // 车 主姓名
        // $CredentialCode = '01'; // 证件类型代码 01身份证
        $CarOwnerPhoneNo = $phone; // 手机号码

        $InsuredBirthDay = $gpicClientInfo['BirthDate'];

        return <<<EOF
UtiPower=1&CarShipFlag=1&FunType=02&FloatFlag=0&FloatName=UICentralControl&MinusFlag=null&ProvisonalNo=&vipCustomIntSize=&vipCustomPID=&vipCustomPSNNAME=&vipCustomPSNLEVEL1=&vipCustomPSNLEVEL2=&vipCustomCARDNO=&vipCustomCARDTYPE=&vipCustomPSNTYPE=&vipCustomImagePath=&vipCustomStartDate=&vipCustomEndDate=&vipInsuredIntSize=&vipInsuredPID=&vipInsuredPSNNAME=&vipInsuredPSNLEVEL1=&vipInsuredPSNLEVEL2=&vipInsuredCARDNO=&vipInsuredCARDTYPE=&vipInsuredPSNTYPE=&vipInsuredImagePath=&vipInsuredStartDate=&vipInsuredEndDate=&SynChronize_flag=&CustomerTypeCode=&AppliBusiLicense=&AppliLicenseStartDate=&AppliLicenseEndDate=&InsuredBusiLicense=&InsuredLicenseStartDate=&InsuredLicenseEndDate=&CustomerTypeHidden=1&ACTIONTYPE=3&UPDATE_CUSTOMER_FLAG=&InsuredQueryFlag=0&AppliOrInsured=0&strFlag=&prpDcustomerIdvCustomerCode=&prpDcustomerIdvIdentifyType=$prpDcustomerIdvIdentifyType&prpDcustomerIdvIdentifyNumber=$prpDcustomerIdvIdentifyNumber&prpDcustomerUnitOrganizeIdentifyType=&prpDcustomerUnitOrganizeCode=&prpDcustomerUnitCustomerCode=&prpDcustomerUnitCustomerCName=&AppliOrInsuredUpdateFlag=&IdentifyAppliOrInsuredName=&UseImagePlat=true&AppliMobileQuery=&AppliPhoneNumberQuery=&InsuredMobileQuery=&InsuredPhoneNumberQuery=&AppliMessage=&InsuredMessage=&TelephoneRealyTypeFlag=0&ElectronicPolicyUtiPower=false&CIElectronicPolicyUtiPower=false&ElectronicProposalUtiPower=false&UnuseField=&GuangXiCustomerFlag=true&MobileFlag=&BusinessNatureFlag=&CVR_IDCARD=false&mustVerfiyFlag=false&PrpslPoliFlag=false&CardCancelFlag=&historyDateFlag=&CustomerTypeAppli=1&prpDcustomerIdvCustomerCode0=&prpDcustomerIdvIdentifyType0=01&CertificateName=&prpDcustomerUnitOrganizeIdentifyType0=07&AppliName=$AppliName&AppliSex=$AppliSex&prpDcustomerUnitCustomerCName0=&prpDcustomerUnitCustomerCode0=&prpDcustomerUnitOrganizeCode0=&prpDcustomerIdvIdentifyNumber0=$prpDcustomerIdvIdentifyNumber0&AppliAddress=$AppliAddress&AppliIdentifyType=01&AppliIdentifyNumber=$AppliIdentifyNumber&AppliCertificadeName=&AppliAge=$AppliAge&AppliJob=&AppliJobCode=&AppliBirthDay=$AppliBirthDay&AppliRegistID=$AppliRegistID&AppliCountryCode=CHN&AppliNationCode=&AppliCUnitOratypeCode=&AppliCUnitOratypeCodeEnt=&AppliBusinessSourceCode=&AppliMobile=$AppliMobile&AppliReTimes=0&AppliPhoneNumber=&RelationDeal=0&FocusName=InsuredCode&AppliCode=$AppliCode&CustomerType=1&NationFlag=1&AppliInsuredAccount=&AccountCodeZize=&AppliLinkerName=$AppliLinkerName&AppliEmail=&Appli_Flag=&AppliInsuredBank=&AppliPostCode=&SQDBUnitTaxRegisterNo=&SameToApplicant=on&SameToApplicantInput=%CD%AC%CD%B6%B1%A3%C8%CB&UnuseField2=&InsuredCountryCode=CHN&InsuredBirthDay=$InsuredBirthDay&InsuredNationCode=&InsuredRegistID=$InsuredRegistID&InsuredCUnitOratypeCode=&InsuredCUnitOratypeCodeEnt=&InsuredBusinessSourceCode=&InsuredNature=3&CustomerTypeInsure=1&prpDcustomerIdvCustomerCode1=&prpDcustomerIdvIdentifyType1=01&CertificateName1=&prpDcustomerUnitOrganizeIdentifyType1=07&InsuredName=$InsuredName&prpDcustomerUnitCustomerCName1=&prpDcustomerUnitCustomerCode1=&prpDcustomerUnitOrganizeCode1=&prpDcustomerIdvIdentifyNumber1=$prpDcustomerIdvIdentifyNumber1&InsuredAddress=$InsuredAddress&BusinessSort=15&InsuredMobile=$InsuredMobile&InsuredReTimes=0&InsuredPhoneNumber=&SQDBUnitTaxRegisterNo1=&InsuredLinkerName=$InsuredLinkerName&InsuredCode=$InsuredCode&CustomerType1=1&InsuredSex=1&InsuredJob=&InsuredJobCode=&InsuredAge=$InsuredAge&InsuredMarriage=1&InsuredBank=&InsuredEmail=&Insured_Flag=&InsuredIdentifyType=01&InsuredCertificadeName=&IdentifyNumber=$IdentifyNumber&InsuredAccount=&InsuredPostCode=&CarOwnerVehiclePriceQuery=&SameToInsured=on&SameToInsuredInput=%CD%AC%B1%BB%B1%A3%CF%D5%C8%CB&CarInsuredRelation=1&CarOwner=$CarOwner&IsHisPolicyBJV295=&IsHisPolicy=&VehicleOwnerNature=7&CarOwnerInfo_Flag=&CredentialCode=01&CredentialNo=$CredentialNo&CarOwnerPhoneNo=$CarOwnerPhoneNo&MailingAddress=$MailingAddress&CarOwnerCountryCode=&VehicleOwnerNatureOld=&BIInsureSZ104_value=false&BIInsureSZ104_isHistory=
EOF;
    }

    /**
     * 车型信息与车辆信息 4805 - 10208 EndorseItemCar -》 Today
     * 该函数目前只组装出家用轿车的参数,
     * 新增设备险种在这里组装！（暂时未作）
     *
     * @return string
     */
    public function buildFormParams3($auto, $business, $mvtalci)
    {
        // 根据车型代码找到车型信息
        $modelCode = $this->encodeString($auto['MODEL_CODE']); // 车型代码
        // 通过车型代码再次查询车辆信息，以获取国寿财需要的一些额外参数，再次查询的原因在于第一次查询出了车型过后，提交给算价的参数中并不能全部包含国寿财算价的所有参数。
        $carList = $this->queryCarInfoByModelCode($modelCode);

        if (isset($carList[0]) && is_array($carList[0]) && !empty($carList[0])) {
            // 不同地区的赋值可能会有变化 !!!
            list($modelName, $modelCode, $exhaustScale, $seatCount, $tonCount, $carActualValue, $purchasePrice, $countryNature, $curbWeightMin, $curbWeightMax, $series_Id, $carModelID, $localPurchasePrice, $riskType, $fuelCode, $platModelCode, $platModelName, $platStandardName, $FCVEHICLE, $carBrand, $newClassCode, $newClassName, $riskOtherFlag, $riskOtherName) = $carList[0];
        } else {
            $this->error['errorMsg'] = '车辆信息组装失败';
            return false;
        }

        $UserComCode = $this->getloginComInfo('riskCode');
        if (false === $UserComCode) {
            return false;
        }
        // 车辆信息
        $LicenseNo = $this->encodeString($auto['LICENSE_NO']); // 车牌号码 $auto['LICENSE_NO']
        $vin = $auto['VIN_NO']; // $auto['VIN_NO']
        $EngineNo = $auto['ENGINE_NO']; // $auto['ENGINE_NO']
        $CertifyDate = $EnrollDate = $auto['ENROLL_DATE']; // $auto['ENROLL_DATE'] 注册日期

        // 折旧价
        if (isset($auto['DISCOUNT_PRICE'])) {
            $discountPrice = $auto['DISCOUNT_PRICE'];
        } else if ($business['POLICY']['TVDI_INSURANCE_AMOUNT']) {
            $discountPrice = $business['POLICY']['TVDI_INSURANCE_AMOUNT'];
        } else {
            $discountPrice = $this->depreciation(array(
                'BUYING_PRICE' => $auto['BUYING_PRICE'],
                'BUSINESS_START_TIME' => $business['BUSINESS_START_TIME'],
                'ENROLL_DATE' => $auto['ENROLL_DATE'],
                'VEHICLE_TYPE' => 'PASSENGER_CAR',
                'USE_CHARACTER' => 'NON_OPERATING_PRIVATE'
            ));
        }

        $ReferenceValue = $CarActualValue = $discountPrice;

        $EffectiveImmediatelyFlag = 'N'; // 交强险是否及时生效
        $CIStartDate = $this->array_get($mvtalci, 'MVTALCI_START_TIME', ''); // 交强险起保日期  $mvtalci['MVTALCI_START_TIME']
        $CIEndDate = date('Y-m-d 59:59:59', strtotime('+1 year -1 day', strtotime($CIStartDate)));

        $NewStartDate = ''; //交强险即时生效 起保日期
        $NewEndDate = ''; //交强险即时生效 终保日期

        $BIEffectiveImmediatelyFlag = 'N'; // 商业险是否及时生效
        $StartDate = $business['BUSINESS_START_TIME']; // 商业险起保日期  $business['BUSINESS_START_TIME']
        $EndDate = date('Y-m-d 59:59:59', strtotime('+1 year -1 day', strtotime($StartDate)));

        $BINewStartDate = ''; //商业险即时生效 起保日期
        $BINewEndDate = ''; //商业险即时生效 终保日期
        $OperateDate = date('Y-m-d'); // 操作日期
        $Today = date('Y-m-d');

        $UseYears = round((strtotime($Today) - strtotime($CertifyDate)) / 31536000); // 使用年份，影响自燃险费率（一年31536000秒)

        return <<<EOF
EndorseItemCar=&OtherNature7=1&OtherNature8=0&LicenseNo=$LicenseNo&CountryNature=$countryNature&LicenseColorCode=01&LicenseColorName=%C0%B6&LicenseKindCode=02&LicenseKindName=%D0%A1%D0%CD%C6%FB%B3%B5%BA%C5%C5%C6&TonCount=$tonCount&CarKindCode=A0&CarKindName=%BF%CD%B3%B5&ExhaustScale=$exhaustScale&UseNatureCode=8A&UseNatureName=%BC%D2%CD%A5%D7%D4%D3%C3&SeatCount=$seatCount&VehicleStyle=K33&VehicleStyleName=%BD%CE%B3%B5&WholeWeight=&BrandName={$this->encodeString($modelName)}&Series_Id=$series_Id&ModelName=&ModelCode=$modelCode&RegistModelCode=&carPriceType=0&PurchasePrice1=$purchasePrice&PurchasePrice=$purchasePrice&CarActualValueTrue=$carActualValue&ReferenceValue=$ReferenceValue&CarActualValueNew=0.00&PurchasePrice_BJ=&Car_Flag=&PurchasePriceMaxRate=0&PurchasePriceMinRate=0&FairMarketValueMaxRate=10000&FairMarketValueMinRate=100&CarActualValue=$CarActualValue&FrameNo=$vin&VINNo=$vin&EngineNo=$EngineNo&VehicleBrand={$this->encodeString($carBrand)}&EnrollDate=$EnrollDate&VehicleStyleDesc=&RunMiles=&VehicleCode=$platModelCode&CarModelID=$this->encodeString($carModelID)&FCVEHICLE=$FCVEHICLE&platModelCode=$platModelCode&platModelName={$this->encodeString($platModelName)}&platStandardName=$platStandardName&FUELCODE=$fuelCode&isciInsureCarMarkFlag=&VehicleCodeFlag=1&newClassCode=$newClassCode&newClassName={$this->encodeString($newClassName)}&riskOtherFlag=$riskOtherFlag&riskOtherName=$riskOtherName&IsUpdatProfitFlags=&CarBuyDate=&CertifyDate=$CertifyDate&XmlContentCarModelQuery=&VEHICLEQUERYNO=&serialNo=&FuelType=0&FuelTypeName=%C8%BC%D3%CD&PoliceFindDate=&UseYears=$UseYears&isUpdatProfitFlag=0&IsFullEndor=0&ColorCode=&ColorName=&AddonCount=&AddonCountName=&AddonCount_EBao=&DamagedFactorGrade=11&DamagedFactorGradeName=%C9%CF%C4%EA%C3%BB%D3%D0%B7%A2%C9%FA%C5%E2%BF%EE&DamagedFactorGradeOld=&DamagedFactorGrade_EBao=11&RunAreaCode=04&RunAreaName=%D6%D0%BB%AA%C8%CB%C3%F1%B9%B2%BA%CD%B9%FA%BE%B3%C4%DA%28%B2%BB%BA%AC%B8%DB%A1%A2%B0%C4%A1%A2%CC%A8%B5%D8%C7%F8%29&specialCarFlag=0&QueryAreaCode=&QueryAreaName=&RestricFlag=0&RestricType=&OtherNature2=0&OtherNature2_EBao=&NewTypeLicenseFlag=0&SecondHandCarFlag=0&SecondHandCarPrice=&ChgOwnerFlag=0&LoanVehicleFlag=0&PmQueryNo=&VehicleCode_SZ=&VehicleName_SZ=&TransferDate=&RunAreaDesc=&SpecialTractorFlag=0&FlType=FT1&FloatingType=FT1&NoDamageYears=0&SafeDevice=&SafeDeviceName=&NoClaimFavorType=&NoClaimFavorName=&ParkingSite=&ParkingSiteName=&CarGoodsType=&CarGoodsName=&OtherNature5=&FixFactoryName=&LightViolated=&SeriousViolated=&CarUsage=&CarUsageName=&OtherNature3=0&RateCode=20&MJCaculateFlag=1&SearchSequenceNo=&VehicleSerialNo=0&LocalPurchasePrice=$localPurchasePrice&GxTractorOnlineDate=2011-09-15%3E&GxPolicyInputDate=&OldOrNewCar_temp=1&licenseno_temp=&licenseKindCode_temp=&LocalModelCode=&PlatSeatCount=&RenewalLicenseNo=&RenewalLicenseKindCode=&RenewalFrameNo=&RenewalEngineNo=&InsLogoVisaCode=&InsLogoVisaSerialNo=&MotorTypeCode=&pmMotorUsageTypeCode=&feigaiFlag=&checkComFlag=true&checkValid=0&LastModelCode=&LastModel=&LastReplacementValue=&IsCheckCarInfo0507=false&Beneficiary=&CarDeviceDeviceName=&CarDevice_Flag=&CarDeviceSerialNo=&CarDeviceQuantity=&CarDevicePurchasePrice=&CarDeviceBuyDate=&CarDeviceActualValue=&CarDeviceQuantityCount=&CarDevicePurchasePriceCount=&CarDeviceActualValueCount=&=&isHisPolicyFlag=&isBIInsured=true&CarDriver_Flag=&CarDriverDriverTypeName=&CarDriverDriverName=&CarDriverDrivingLicenseNo=&CarDriverSex=&CarDriverSexCname=&CarDriverAge=&CarDriverMarriage=1&AppliYear=1&DrivingLicenseType=A1&DrivingYears=&CarDriverAcceptLicenseDate=&LicenseStatus=1&CarDriver_Flag=&CarDriverDriverTypeName=%D6%F7%BC%DD%CA%BB%D4%B1&CarDriverDriverName=&CarDriverDrivingLicenseNo=&CarDriverSex=1&CarDriverSexCname=%C4%D0&CarDriverAge=&CarDriverMarriage=1&AppliYear=1&DrivingLicenseType=A1&DrivingYears=&CarDriverAcceptLicenseDate=&LicenseStatus=1&CarDriver_Flag=&CarDriverDriverTypeName=%B4%D3%BC%DD%CA%BB%D4%B11&CarDriverDriverName=&CarDriverDrivingLicenseNo=&CarDriverSex=1&CarDriverSexCname=%C4%D0&CarDriverAge=&CarDriverMarriage=1&AppliYear=1&DrivingLicenseType=A1&DrivingYears=&CarDriverAcceptLicenseDate=&LicenseStatus=1&CarDriver_Flag=&CarDriverDriverTypeName=%B4%D3%BC%DD%CA%BB%D4%B12&CarDriverDriverName=&CarDriverDrivingLicenseNo=&CarDriverSex=1&CarDriverSexCname=%C4%D0&CarDriverAge=&CarDriverMarriage=1&AppliYear=1&DrivingLicenseType=A1&DrivingYears=&CarDriverAcceptLicenseDate=&LicenseStatus=1&tableOtherNature_Flag=&OtherNature1=1&OtherNature1=0&OtherNature6=1&OtherNature6=0&UserComCode=$UserComCode&BooleanEffectiveImmediatelyFlag=true&EffectiveImmediatelyFlag=$EffectiveImmediatelyFlag&BIEffectiveImmediatelyFlag=$BIEffectiveImmediatelyFlag&RENEWAL_CIFLag=&CIStartDate=$CIStartDate&CIEndDate=$CIEndDate&NewStartDate=$NewStartDate&NewEndDate=$NewEndDate&StartDate=$StartDate&EndDate=$EndDate&Period_Flag=&CBackFlag=36%2C52%2C45%2C&CIDaoQianDanFlag=true&BIDaoQianDanFlag=true&BINewStartDate=$BINewStartDate&BINewEndDate=$BINewEndDate&XuBaoCIStartDate=&XuBaoCIDate=&XuBaoBIStartDate=&XuBaoBIDate=&tdXuBaoCIStartDate=&tdXuBaoBIStartDate=&tdXuBaoCIDate=&tdXuBaoBIDate=&tdNewXuBaoCIStartDate=&tdNewXuBaoBIStartDate=&tdNewXuBaoCIDate=&tdNewXuBaoBIDate=&OperateDate=$OperateDate&intCount=3&Today=$Today
EOF;
    }

    /**
     * 商业险信息参数组装 10349 - 12742 DemendNoNew -》 PlanFee2Charge（12706）   （button_ChargeMain_Delete 12712 - button_Charge_Insert12742 这中间的几个inpu没有被提交）
     * 1.DemendNoNew - 第一个ItemKind_NewEndDate 125  为险种前的参数信息
     * 2.第一个KindCodeCheckBox - OpeanFlag前一个参数  为主险险种的参数信息
     * 3.主险374个参数 机动车损失保险74  第三者责任保险74 车上人员责任保险(驾驶人)74 车上人员责任保险(乘客)74 全车盗抢保险74
     * 4.OpeanFlag - buttonCheckFlag 154 为主险与附加险之间的参数信息
     * 5.buttonCheckFlag下一参数 - isT0060Flag的前一个参数 为附加险参数信息
     * 6.isT0060Flag-PlanFee2Charge 96 为剩余的参数信息
     *
     * @return string
     */
    public function buildFormParams4($auto, $business, $mvtalci)
    {
        $businessPolicy = $business['POLICY'];
        $businessItem = $businessPolicy['BUSINESS_ITEMS'];

        $paramString = '';

        $ComCodeForCal = $this->getLoginComInfo('riskCode');
        if (false === $ComCodeForCal) {
            return false;
        }
        $BillDate = Date('Y-m-d');
        $VehicleActualValue = $businessPolicy['TVDI_INSURANCE_AMOUNT']; // 折旧价
        $OldClauseType = $ClauseType = '0511M0000001'; // 未知

        // 1. 险种前的参数
        $paramString .= <<<EOF
DemendNoNew=&M1ValueCheck=5&DemendNoNewFlag=&SumPremiumNew=&SumPremiumNewFlag=&WithdrawItemKindNo=&GlassTypeModel=1_FIELD_SEPARATOR_%B9%FA%B2%FA%B2%A3%C1%A7_GROUP_SEPARATOR_2_FIELD_SEPARATOR_%BD%F8%BF%DA%B2%A3%C1%A7&RadiusTypeModel=1_FIELD_SEPARATOR_%B0%EB%BE%B6%CE%AA200%B9%AB%C0%EF_GROUP_SEPARATOR_2_FIELD_SEPARATOR_%B0%EB%BE%B6%CE%AA500%B9%AB%C0%EF_GROUP_SEPARATOR_3_FIELD_SEPARATOR_%B0%EB%BE%B6%CE%AA1000%B9%AB%C0%EF&DeductibleSWITCH=&CarClauseChgDate=&ComCodeForCal=$ComCodeForCal&RenewalFlagJZ=&KindMSelectFlag=true&KindMSelectFlag0510=0&KindMSelectOnLineDate=true&BillDate=$BillDate&GTFlag=&ShortRateFlag=2&tableShortRate_Flag=&Currency=CNY&CurrencyName=%C8%CB%C3%F1%B1%D2&ShortRate=100.0000&LastProducerCodeName=%BC%E6%D2%B5%B4%FA%C0%ED&LastProducerCode=52&vehiclePriceType=1&VehicleActualValue=$VehicleActualValue&FairMarketValue=&SelectClauseType_Flag=&OldClauseType=$OldClauseType&ClauseType=$ClauseType&PurchasePriceScal=25&LastDamagedA=&LastDamagedB=&ThisDamagedA=&ThisDamagedB=&tableDiscountSZ_Flag=&VehicleModelAdjust=&VehicleModelAdjustLower=&VehicleModelAdjustUpper=&ManagementAdjust=&ManagementAdjustLower=&ManagementAdjustUpper=&ExperienceAdjust=&ExperienceAdjustLower=&ExperienceAdjustUpper=&ChooseFlag=&ProfitCode=&ProfitRate=&ProfitDetail_ItemKindNo=&ProfitDetail_FieldValue=&ProfitDetailEncode=&ProfitDetail_Flag=&ProfitDetail_ConditionCode=&ItemKind_Flag=&Withdraw_Flag=&ItemKindNo=&MS_Flag=&ItemKindFlag6Flag=&KindCodeCheckFlag=&ProfitDetailFlag=&KindCode=&KindName=&PreUnitAmountName=&UnitAmount=&UnitAmountName=%D4%AA%2F%D7%F9&Quantity=&QuantityName=%D7%F9&AdjustRate1=&Model=&ValueTitle=%BF%C9%D1%A1%C3%E2%C5%E2%BD%F0%B6%EE&Value=300&DeductibleRate1Title=%BE%F8%B6%D4%C3%E2%C5%E2%CF%B5%CA%FD&DeductibleRate1=1&thirdPeopleAmountName=%B5%DA%C8%FD%D5%DF&thirdPeopleAmount=&GuestamountName=%B3%CB%BF%CD&GuestAmount=&DriveramountName=%BC%DD%CA%BB%C8%CB&DriverAmount=&RUnitAmountName=%C3%BF%B4%CE%CA%C2%B9%CA%C3%BF%C8%CB%D4%F0%C8%CE%CF%DE%B6%EE&RUnitAmount=&XDTitle=%D0%AD%B6%A8%B1%C8%C0%FD&Value1=50&riskLevelTitle=%B6%E0%B4%CE%CA%C2%B9%CA%C3%E2%C5%E2%C2%CA%CC%D8%D4%BC&riskLevel=0&M1ValueTitle=%BF%C9%D1%A1%BE%F8%B6%D4%C3%E2%C5%E2%B6%EE&M1Value=0&M1Premium=&M1ValueRate=&lastCompanyTitle=%D4%AD%D4%F0%C8%CE%B9%AB%CB%BE&lastCompany=1&lastAmountTitle=%D4%AD%D4%F0%C8%CE%CF%DE%B6%EE&lastAmount=&TotalProfit1=0&TotalProfit2=0&ItemKind_StartDate=&ItemKind_EndDate=&ItemKindCalculateFlag=&ItemKindFlag3To4=&AttachFlag=&ItemKindFlag5Flag=&KindPremiumM=&kindBenchmarkPremiumm=&Deductible=&DeductibleRate=&PureRiskPremium1=&PureRiskBenchMarkPremium=&Currency1=CNY&CurrencyNameMain=%C8%CB%C3%F1%B1%D2&ExchangeRateMain=&Amount=0&ItemKind_ActualValue=&ItemKind_ActualValue_Orign=&Rate=&BenchMarkPremium=&BasePremium=0&KindShortRate=&KindShortRateFlag=&platBenchMarkPremium=&Discount=1&selectDiscount=%C8%AB+%D1%A1&unSelectDiscount=%C8%A1%CF%FB%C8%AB%D1%A1&AdjustRate=&Premium=&ItemKind_NewStartDate=&ItemKind_NewEndDate=&ItemKindFlag5AllCheck=
EOF;

        // 2.第一个KindCodeCheckBox - OpeanFlag前一个参数  为主险险种的参数信息
        $contrast = $this->getBusinessContrast();
        $template = $this->businessTemplate();
        $mainBusiness = array('001', '002', '003', '006', '007');// 主险种代码
        $otherBusiness = array('203', '308', '211', '208', '215', '207', '311', '315'); // 附加险中的 很稀有的险种
        $businessNumber = 1;
        // 用于判断是否组装STSFS、RDCCI、NIELI这些险种的参数
        $isNeedOtherBusiss = in_array('STSFS', $businessItem) || in_array('RDCCI', $businessItem) || in_array('NIELI', $businessItem);

        // 组装各险种的参数信息
        // 3.主险374个参数 机动车损失保险74  第三者责任保险74 车上人员责任保险(驾驶人)74 车上人员责任保险(乘客)74 全车盗抢保险74
        foreach ($contrast as $item) {
            $params = $template;
            $zswtichCode = $item['ZswtichCode'];
            $kindCode = $item['KindCode'];

            if (in_array($kindCode, $otherBusiness) && !$isNeedOtherBusiss) { //如果没有指定这些险种，就不组装对应的参数
                continue;
            }

            if (in_array($zswtichCode, $businessItem)) { // 设置了该险种
                $params['ItemKindNo'] = $businessNumber;
                $businessNumber++;
                $params['KindCodeCheckFlag'] = '1';

                if (in_array($zswtichCode . '_NDSI', $businessItem)) { // 勾选了不计免赔 为1，没有勾选则为空
                    $params['ItemKindFlag5Flag'] = '1';
                    $params['ItemKindFlag5'] = 'on';
                } else {
                    unset($params['ItemKindFlag5']); // 当勾选了不计免赔后才有这个key,
                }

                switch ($zswtichCode) {
                    case 'TVDI': // 机动车损失险
                        $params['BasePremium'] = '1141.482160';
                        $params['Amount'] = $businessPolicy['TVDI_INSURANCE_AMOUNT'];
                        break;
                    case 'TTBLI': // 第三者责任险
                        $params['Amount'] = (int)$businessPolicy['TTBLI_INSURANCE_AMOUNT'] * 10000;
                        break;
                    case 'TTBLI_NDSI':
                    case 'TCPLI_PASSENGER_NDSI':
                    case 'BSDI_NDSI':
                    case 'SLOI_NDSI':
                    case 'VWTLI_NDSI':
                        $params['ItemKindFlag5Flag'] = '0';
                        break;
                    case 'TCPLI_PASSENGER': // 乘客座位险
                        $params['UnitAmount'] = $businessPolicy['TCPLI_INSURANCE_PASSENGER_AMOUNT'];// 乘客座位险每个座位的保额
                        $params['Quantity'] = $businessPolicy['TCPLI_PASSENGER_COUNT'];// 乘客座位数
                        $params['Amount'] = (int)$params['UnitAmount'] * (int)$params['Quantity']; // 保额
                        break;
                    case 'RDCCI': // 修理期间费用补偿
                        $params['UnitAmount'] = $businessPolicy['RDCCI_INSURANCE_QUANTITY'];// 修理期间费用补偿的每天费用
                        $params['UnitAmountName'] = '元x';// UnitAmount的单位，默认为元/座， 费用补偿为元x
                        $params['Quantity'] = $businessPolicy['RDCCI_INSURANCE_QUANTITY'];// 修理期间费用补偿的天数
                        $params['QuantityName'] = '天';// Quantity的单位 乘客座位险为座，修理期间费用为天
                        $params['Amount'] = (int)$params['UnitAmount'] * (int)$params['Quantity'];
                        break;
                    case 'BGAI': // 玻璃破碎险玻璃的类型，1 国产，2 进口，其他为空
                        if ($businessPolicy['GLASS_ORIGIN'] == 'DOMESTIC' || $businessPolicy['GLASS_ORIGIN'] == 'DOMESTIC_SPECIAL') {
                            $params['Model'] = '1';
                        } else {
                            $params['Model'] = '2';
                        }
                        $params['ItemKindFlag5Flag'] = '0';
                        break;
                    case 'TCPLI_DRIVER':
                        $params['Amount'] = $businessPolicy['TCPLI_INSURANCE_DRIVER_AMOUNT']; // 险种对应保额
                        break;
                    case 'NIELI':
                        $params['Amount'] = $this->array_get($businessPolicy, 'NIELI_INSURANCE_AMOUNT', 0);
                        break;
                    default :
                        if ($zswtichCode != 'MVLINFTPSI' && $zswtichCode != 'STSFS' && !strpos($zswtichCode, 'NDSI')) { // 第三方特约险\不计免赔没有保额
                            $params['Amount'] = $businessPolicy[$zswtichCode . '_INSURANCE_AMOUNT']; // 险种对应保额
                        }
                        break;
                }
            } else {
                $params['ItemKindFlag5Flag'] = ''; // 该险种选择时才有值
                unset($params['ItemKindFlag5']); // 该险种选择时才有这个key,
                unset($params['KindCodeCheckBox']); // 该险种选择时才有该key
                $params['KindCodeCheckFlag'] = '0';
            }

            $params['MS_Flag'] = in_array($kindCode, $mainBusiness) ? 'M' : 'S';
            $params['KindCode'] = $kindCode;
            $params['KindName'] = $item['KindName'];
            $params['AttachFlag'] = $item['AttachFlag'];
            $params['ItemKindCalculateFlag'] = in_array($kindCode, $mainBusiness) ? 'Y' : 'N';
            $paramString = $paramString . '&' . $this->encodeArray($params);

            // 4.OpeanFlag - buttonCheckFlag 154 为主险与附加险之间的参数信息
            if ($zswtichCode == 'TWCDMVI') {
                // 这三个值由平台查询 得到，在平台查询请求中这三个值是空的，根据平台查询返回的结果可以得到这三个值
                // 不知道有什么用,!!!但是这个参数会直接影响算价的结果。如果没有设置值，算价结果会偏高！！！
                $DemendNo = ''; // =V0101GPIC510017001499054157788
                $XmlContentBIQuery = '';
                $PureRiskPremium = '';
                $paramString .= <<<EOF
&OpeanFlag=&PSeqNum=&AdjustProfitCode_ProfitName=&UpperProfitRate=&LowerProfitRate=&AdjustProfitRate=&artificialAdjustRatio=0&Fg_RatioId=&Fg_DemandNo=&Fg_CertiType=&Fg_CertiNo=&Fg_CiCacheItemCarId=&Fg_ClauseType=&Fg_CombosCode=GP00000000&Fg_ClaimAdjustValue=0&Fg_PeccancyAdjustValue=0&Fg_ActuarialAutonomyRatio=0&Fg_Motorcaderatio=0&Fg_UseRatio=&Fg_SinglePlateRatio=0&Fg_SingleBusinessNatureRatio=0&Fg_ActuarialChannelRatio=0&Fg_ActuarialRatio=0&Fg_ActuarialPremium=&Fg_UnderwriteAdjustRatio=0&Fg_UnderwriteAutonomyRatio=0&Fg_UnderwriteChannelRatio=0&Fg_FinalRatioU=&Fg_DefaultTotalRatio=0&Fg_FinalRatioD=&Fg_DefaultPremium=&Fg_ArtificialAutonomyRatio=0&Fg_ArtificialChannelRatio=0&Fg_SumPremium=&Fg_Flag=&Fg_InsertTime=&Fg_LastUpdateTime=&Fg_ArtificialUnderwriteRatio=0&Fg_SendMotifyRatio=0&ActuarialRatio=0&PureRiskPremium=$PureRiskPremium&PureRiskPremiumFlag=1-%B1%EA%D7%BC%B3%B5%D0%CD%B1%A3%B7%D1&Fg_BenechmarkPremium=&Fg_ExpensePremium=&Fg_ProfitRatePremiun=&Fg_ActuarialPrice=&Fg_SumBenechmarkPremium=&Fg_OverProfitRatePremium=&Fg_DocumentaryPremium=&Fg_ExpectProfitRatePremium=&Fg_ActuarialRatio2=&finalRatio=0&Fg_RatioId1=&Fg_SerialNo=&Fg_RatioTypeCode=&Fg_FactorTypeCode=&Fg_FactorValue=&Fg_FactorValueDesc=&Fg_Flag1=&Fg_InsertTime1=&Fg_LastUpdateTime1=&Fg_kindCode=&FI_ResultId=&FI_DemandNo=&FI_ProposalNo=&FI_PolicyNo=&FI_CertiType=&FI_SalesPoundageRatio=&FI_IndividualPerformance=&FI_IntermediaryIndustry=&FI_TeamManagement=&FI_ManagementAllowance=&FI_RewardCoefficient=&FI_FeeType=&FI_InsertTime=&FI_Flag=&FI_SalesPoundageRatioMax=&FI_Field1=&FI_Field2=&Result_ResultId=0&Result_clauseType=0&Result_combosCode=0&Result_kindcode=0&Result_basicPremium=0&Result_factorRatio=0&Result_expenseRate=0&Result_profitRate=0&Result_benechmarkPremium=0&Result_premium=0&Result_finalPremium=0&Result_underwriteRatio=0&Result_benechmarkRatio=0&Result_localRatio=0&Result_trendRaito=0&Result_expensePremium=0&Result_profitRatePremiun=0&Result_predictiveLossRate=0&Result_predictiveCostRate=0&Result_claimExpenseRate=0&Result_Itemkindno=0&poundage_ConstantExpenseRatio=0&poundage_ConstantExpense=0&poundage_BusinessTaxRatio=0&poundage_BusinessTax=0&poundage_NonSalesLaborRatio=0&poundage_NonSalesLabor=0&poundage_SalesLaborRatio=0&poundage_SalesLabor=0&poundage_BranchReserveFeeRatio=0&poundage_BranchReserveFee=0&poundage_VariableExpenseRatio=0&poundage_VariableExpense=0&poundage_SalesExpenseHigh=0&poundage_PoundageRatio=0&poundage_PoundageFee=0&poundage_PoundageType=0&poundage_RunPoundageFee=0&poundage_RunPoundageRatio=0&poundage_PaymentRatio=0&poundage_PoundagePool=0&poundage_TotalCostRatio=0&poundage_TotalCost=0&poundage_ProfitPremium=0&poundage_ClaimExpensePremium=0&poundage_BusinessExpenseRate=0&poundage_BusinessExpenseRatePremium=0&poundage_BusinessClaimRate=0&poundage_BusinessClaimRatePremium=0&poundage_BusinessGuideRate=0&poundage_ResultId=0&AmountCount=&BenchMarkPremiumCount=&PremiumDisCount=&PremiumCount=&DiscountPremiumCount=&SumPremium=&SumPremiumOld=&totalPureRiskPremium=&ArtificialUnderwritePremium=&SendMotifyPremium=&SendMotifyPremiumOld=&ArtificialUnderwriteRatio=&SendMotifyRatio=&AdjustRateOfMain=1&AdjustOfMain=0.00&ProfitScale=1&AdjustPremiumCount=&checkboxChooseFlagAll=&AdjustRateAll=&DemendNo=$DemendNo&DemendNoOld=&XmlContentBIQuery=&XmlContentBICheck=$XmlContentBIQuery&CheckCode=A&buttonCheckFlag=0
EOF;
            }
            // 5.buttonCheckFlag下一参数 - isT0060Flag的前一个参数 为附加险参数信息，这里体现在进入下一次循环后就组装附加险的参数
        }
        // 6.isT0060Flag-PlanFee2Charge 96 为剩余的参数信息
        $paramString .= <<<EOF
&isT0060Flag=false&ClauseName=&ChangeEngageFlag=0&defaultClausesContext=&Engage_Flag=&AutoClauseFlag=0&EngageSerialNo=&ClauseCode=&Clauses=&ClausesContext=&clauseTemplate=&clauseActiveContent=&PlanOneTimes=1&PayTimes=1&Plan_Flag=&PayReason=&PayNo=&PlanStartDate=&PlanDate=&PrpPlanCurrency=&PrpPlanCurrencyName=&PlanFee=&DelinquentFee=&RealPayFee=&AccountCode=&CustomBankName=&CertificateCode=&OwnerName=&AccountType=&CustomBankCode=&OwnerPhoneNo=&AccountCurrency=&OwnerShip=&PrpapaymentAccountOwnerType=&PrpapaymentAccountAppliName=&PrpapaymentAccountCustomerCode=&PrpapaymentAccountUserCode=&PrpapaymentAccountVehicleComCode=&isPoundageFlag=0&DisRate=36.0&PremiumDisRate=&Expenses_Flag=&PreDisRate=&MaxDisRate=36.0&FinalDisRate=36.0&IsPoundageType=&PoundageRatio=&PoundageFee=&businessGuideRate=&salesExpenseHigh=&AssignnoCheckedNew=&Agent_Id_Flag=&isSplitFeeFlag=&isSpecialFlag=0&Agent_Id_IsSplit_Flag=&Agent_IdSerialNo=&SplitFeeAgentCode=&SplitFeeAgentName=&SplitFeeRatePayRefReason=&SplitFeeRatePayRefReasonCode=&SplitFeeCommissionRate=&SplitFeeCommissionRateSale=&SplitFeeCommissionScale=&CompanyRateUpper=&ClaimEffectReason=&NewVehicleEffecReason=&ProducerEffecReason=&IsAgentCodeFlag=0&IsAgentTypeNoFlag=0&IsAgentMaxCommissionFlag=0&IsDisRateFlag=0&OriginalDisRate=&OriginalAgentTypeNo=&AgentTypeNo_Expen=&AgentTypeNoName_Expen=&MaxDisRate_Expen=&ChangePremiumDisRate=&PureRate=&ExpensesFlag2=1&MaxManageFeeRate=&ManageFeeRate=&DisRate1=&PremiumDisRate1=&ChangePremiumDisRate1=&TopCommission=&ChargeSerialNo=&ChargeRiskCode=0511&Charge_Flag=&RefreshFlagCharge=&ChargeCode=&ChargeName=&Currency2Charge=CNY&CurrencyNameCharge=%C8%CB%C3%F1%B1%D2&ExchangeRateCharge=&BaseAmountCharge=10000&ChargeRate=0.0000&PlanFee2Charge=
EOF;

        return $paramString;
    }

    /**
     * GPIC 组装商业险的险种参数模板
     *
     * @return array
     */
    private function businessTemplate()
    {
        return array(
            'KindCodeCheckBox' => '1',          // 该险种选择时才有该key
            'ItemKind_Flag' => '',              // 为空
            'Withdraw_Flag' => '',              // 为空
            'ItemKindNo' => '',                 // 选择了的险种增长的序号，没有设置的为空
            'MS_Flag' => 'M',                   // 主险为M ,其他险种为S
            'ItemKindFlag6Flag' => '',          // 为空
            'KindCodeCheckFlag' => '1',         // 1选中了该险种，0未选中
            'ProfitDetailFlag' => '',           // 为空
            'KindCode' => '001',                // 险种代码 see $KindCode
            'KindName' => '机动车损失保险',       // see $KindCode
            'PreUnitAmountName' => '',          // 为空
            'UnitAmount' => '',                 // 乘客座位险每个座位的保额，修理期间费用补偿的每天费用
            'UnitAmountName' => '元/座',         // UnitAmount的单位，默认为元/座， 费用补偿为元x
            'Quantity' => '',                   // 乘客座位险的座位数，修理期间费用补偿的天数
            'QuantityName' => '座',              // Quantity的单位 乘客座位险为座，修理期间费用为天
            'AdjustRate1' => '',                // 为空
            'Model' => '',                      // 玻璃破碎险玻璃的类型，1 国产，2 进口，其他为空
            'ValueTitle' => '可选免赔金额',       // 保持，都一样
            'Value' => '300',                   // 保持，都一样
            'DeductibleRate1Title' => '绝对免赔系数', // 保持，都一样
            'DeductibleRate1' => '1',           // 保持，都一样
            'thirdPeopleAmountName' => '第三者', // 保持，都一样
            'thirdPeopleAmount' => '',          // 保持，都一样
            'GuestamountName' => '乘客',         // 保持，都一样
            'GuestAmount' => '',                // 保持，都一样
            'DriveramountName' => '驾驶人',      // 保持，都一样
            'DriverAmount' => '',               // 保持，都一样
            'RUnitAmountName' => '每次事故每人责任限额',  // 保持，都一样
            'RUnitAmount' => '',                // 保持，都一样
            'XDTitle' => '协定比例',             // 保持，都一样
            'Value1' => '50',                   // 保持，都一样
            'riskLevelTitle' => '多次事故免赔率特约',    // 保持，都一样
            'riskLevel' => '0',                 // 保持，都一样
            'M1ValueTitle' => '可选绝对免赔额',   // 保持，都一样
            'M1Value' => '0',                   // 保持，都一样
            'M1Premium' => '',                  // 保持，都一样
            'M1ValueRate' => '',                // 保持，都一样
            'lastCompanyTitle' => '原责任公司',  // 保持，都一样
            'lastCompany' => '1',               // 保持，都一样
            'lastAmountTitle' => '原责任限额',   // 保持，都一样
            'lastAmount' => '',                 // 保持，都一样
            'TotalProfit1' => '0',              // 保持，都一样
            'TotalProfit2' => '0',              // 保持，都一样
            'ItemKind_StartDate' => '',         // 保持，都一样
            'ItemKind_EndDate' => '',           // 保持，都一样
            'ItemKindCalculateFlag' => 'Y',     // 主险为Y，其他为N
            'ItemKindFlag3To4' => '',           // 保持，都一样
            'AttachFlag' => '',                 // 不同险种对应的值不同see $AttachFlag
            'ItemKindFlag5Flag' => '0',         // 勾选了不计免赔 为1，没有勾选则为空
            'KindPremiumM' => '',               // 保持，都一样
            'kindBenchmarkPremiumm' => '',      // 保持，都一样
            'Deductible' => '',                 // 保持，都一样
            'DeductibleRate' => '',             // 保持，都一样
            'PureRiskPremium1' => '',           // 保持，都一样
            'PureRiskBenchMarkPremium' => '',   // 保持，都一样
            'ItemKindFlag5' => 'on',              // 当勾选了不计免赔后才有这个key,都为空??
            'Currency1' => 'CNY',               // 保持，都一样
            'CurrencyNameMain' => '人民币',      // 保持，都一样
            'ExchangeRateMain' => '',           // 保持，都一样
            'Amount' => '',                     // 险种对应保额
            'ItemKind_ActualValue' => '',       // 保持，都一样
            'ItemKind_ActualValue_Orign' => '', // 保持，都一样
            'Rate' => '',                       // 保持，都一样
            'BenchMarkPremium' => '',           // 保持，都一样
            'BasePremium' => '0',               // 只有机动车损失险有值... 其他取值0 ，1202.297200但不知1202.297200怎么来的
            'KindShortRate' => '',              // 保持，都一样
            'KindShortRateFlag' => '',          // 保持，都一样
            'platBenchMarkPremium' => '',       // 保持，都一样
            'Discount' => '',                   // 保持，都一样
            'selectDiscount' => '全 选',         // 保持，都一样
            'unSelectDiscount' => '取消全选',    // 保持，都一样
            'AdjustRate' => '',                 // 保持，都一样
            'Premium' => '',                    // 保持，都一样
            'ItemKind_NewStartDate' => '',      // 保持，都一样
            'ItemKind_NewEndDate' => '',        // 保持，都一样
        );
    }

    /**
     * GPIC商业险对照数据
     *
     * @return array
     */
    private function getBusinessContrast()
    {
        return array(
            array(
                'KindCode' => '001',
                'KindName' => '机动车损失保险',
                'AttachFlag' => '',
                'ZswtichCode' => 'TVDI',
            ),
            array(
                'KindCode' => '002',
                'KindName' => '第三者责任保险',
                'AttachFlag' => '',
                'ZswtichCode' => 'TTBLI',
            ),
            array(
                'KindCode' => '003',
                'KindName' => '车上人员责任保险(驾驶人)',
                'AttachFlag' => '',
                'ZswtichCode' => 'TCPLI_DRIVER',
            ),
            array(
                'KindCode' => '006',
                'KindName' => '车上人员责任保险(乘客)',
                'AttachFlag' => '',
                'ZswtichCode' => 'TCPLI_PASSENGER',
            ),
            array(
                'KindCode' => '007',
                'KindName' => '全车盗抢保险',
                'AttachFlag' => '',
                'ZswtichCode' => 'TWCDMVI',
            ),
            array(
                'KindCode' => '205',
                'KindName' => '车身划痕损失险',
                'AttachFlag' => '001',
                'ZswtichCode' => 'BSDI',
            ),
            array(
                'KindCode' => '201',
                'KindName' => '玻璃单独破碎险',
                'AttachFlag' => '001',
                'ZswtichCode' => 'BGAI',
            ),
            array(
                'KindCode' => '202',
                'KindName' => '自燃损失险',
                'AttachFlag' => '001',
                'ZswtichCode' => 'SLOI',
            ),
            array(
                'KindCode' => '206',
                'KindName' => '发动机涉水损失险',
                'AttachFlag' => '001',
                'ZswtichCode' => 'VWTLI',
            ),
            array(
                'KindCode' => '301',
                'KindName' => '不计免赔率险（机动车损失保险）',
                'AttachFlag' => '001',
                'ZswtichCode' => 'TVDI_NDSI',
            ),
            array(
                'KindCode' => '302',
                'KindName' => '不计免赔率险（第三者责任保险）',
                'AttachFlag' => '002',
                'ZswtichCode' => 'TTBLI_NDSI',
            ),
            array(
                'KindCode' => '303',
                'KindName' => '不计免赔率险（车上人员责任保险 - 驾驶人）',
                'AttachFlag' => '003',
                'ZswtichCode' => 'TCPLI_DRIVER_NDSI',
            ),
            array(
                'KindCode' => '305',
                'KindName' => '不计免赔率险（车上人员责任保险 - 乘客）',
                'AttachFlag' => '006',
                'ZswtichCode' => 'TCPLI_PASSENGER_NDSI',
            ),
            array(
                'KindCode' => '306',
                'KindName' => '不计免赔率险（全车盗抢保险）',
                'AttachFlag' => '007',
                'ZswtichCode' => 'TWCDMVI_NDSI',
            ),
            array(
                'KindCode' => '309',
                'KindName' => '不计免赔率险（车身划痕损失险）',
                'AttachFlag' => '205',
                'ZswtichCode' => 'BSDI_NDSI',
            ),
            array(
                'KindCode' => '307',
                'KindName' => '不计免赔率险（自燃损失险）',
                'AttachFlag' => '202',
                'ZswtichCode' => 'SLOI_NDSI',
            ),
            array(
                'KindCode' => '310',
                'KindName' => '不计免赔率险（发动机涉水损失险）',
                'AttachFlag' => '206',
                'ZswtichCode' => 'VWTLI_NDSI',
            ),
            array(
                'KindCode' => '203',
                'KindName' => '新增设备损失保险',
                'AttachFlag' => '001',
                'ZswtichCode' => 'NIELI',
            ),
            array(
                'KindCode' => '210',
                'KindName' => '机动车损失保险无法找到第三方特约险',
                'AttachFlag' => '001',
                'ZswtichCode' => 'MVLINFTPSI',
            ),
            array(
                'KindCode' => '308',
                'KindName' => '不计免赔率险（新增设备损失保险）',
                'AttachFlag' => '203',
                'ZswtichCode' => 'NIELI_NDSI',
            ),
            array(
                'KindCode' => '211',
                'KindName' => '指定修理厂险',
                'AttachFlag' => '001',
                'ZswtichCode' => 'STSFS',
            ),
            array(
                'KindCode' => '208',
                'KindName' => '车上货物责任险',
                'AttachFlag' => '002',
                'ZswtichCode' => '',
            ),
            array(
                'KindCode' => '215',
                'KindName' => '精神损害抚慰金责任险',
                'AttachFlag' => '002,003,006',
                'ZswtichCode' => '',
            ),
            array(
                'KindCode' => '207',
                'KindName' => '修理期间费用补偿险',
                'AttachFlag' => '001',
                'ZswtichCode' => 'RDCCI',
            ),
            array(
                'KindCode' => '311',
                'KindName' => '不计免赔率险（车上货物责任险）',
                'AttachFlag' => '208',
                'ZswtichCode' => '',
            ),
            array(
                'KindCode' => '315',
                'KindName' => '不计免赔率险（精神损害抚慰金责任险）',
                'AttachFlag' => '215',
                'ZswtichCode' => '',
            ),
        );
    }

    /**
     * 其他参数 12742 - 13101  SchemeProtocolCode -》 shenzhenM7ComCode
     *
     * @return string
     */
    public function buildFormParams5()
    {
        $shenzhenM7ComCode = $this->getLoginComInfo('riskCode');
        if (false === $shenzhenM7ComCode) {
            return false;
        }
        return <<<EOF
SchemeProtocolCode=&SchemeCoinsSchemeId=&SchemeCoinsSchemeName=&SchemeValidStatus=&SchemeCoinsgFlag=&SchemeCoinsLFlag=&SchemeProportionFlag=&SchemeStartDate=&SchemeEndDate=&SchemeOperatorCode=&SchemeChargeType=&SchemeChargeValue=&SchemeIsClaimRateSame=&SchemeUseFlag=&TocCoinsSchemeID=&TocOrderID=&TocCoinsType=&TocSignIdentity=&TocCoinsIdentify=&TocChiefFlag=&TocCoinsCode=&TocChannelType=&TocComCode=&TocHandler1code=&TocCoinsCodeName=&TocChannelTypeName=&TocComCodeName=&TocHandler1codeName=&TocInputDate=&TocCoinsRate=&TocAgentFee=&TocOperateRate=&SingleIdentity=%B3%F6%B5%A5%C9%ED%B7%DD&CoinsTypeG=%B9%B2%B1%A3%C8%CB%C9%ED%B7%DD&CoinsNameL=%B9%B2%B1%A3%C8%CB%C3%FB%B3%C6&ChannelTypeL=&ComCodeL=&Handler1codeL=&CoinsRateG=%B9%B2%B1%A3%B7%DD%B6%EE&CoinsAmount=%B7%DD%B6%EE%B1%A3%B6%EE&CoinsPremium=%B7%DD%B6%EE%B1%A3%B7%D1&AgentFee=%CA%D6%D0%F8%B7%D1&ProportionFee=%B3%F6%B5%A5%B7%D1&prppcoinsdetailflag=&CarShipFlagNew=0&shenzhenM7ComCode=$shenzhenM7ComCode
EOF;
    }

    /**
     * 交强险信息参数 13101 -16063 TaxRelifFlag -》 PlanFee2ChargeCI 16026  （button_ChargeMain_Delete 16032 - button_Charge_Insert 16062 这中间的几个inpu没有被提交）
     *
     * @return string
     */
    public function buildFormParams6()
    {
        $TaxRegistryNumber = 123123; // 四川税务登记证号
        return <<<EOF
TaxRelifFlag=1N&TaxRegistryNumber=$TaxRegistryNumber&ProvinceCode=&ProvinceName=&TaxComCode=&TaxComName=&CityCode=&CityName=&RegisterArea=&CodeDistrict=&DistrictName=&TaxpayerNameType=1&TaxpayerName=&CarShipTax_Flag=&TaxpayerCode=&TaxpayerIdentifier=&TaxPayerAddress=&BaseTaxation=&RelifReason=&TaxRelief=&FreeRate=&FreeRateText=&PaidFreeCertificate=&PayStartDate=&PayEndDate=&KTaxComCode=&KTaxComName=&TaxUnit=&TaxUnitText=1&TaxDue=&TaxItemCode=&TaxItemName=&TaxItemDetailCode=&TaxItemDetailName=&PayLastYear=2016&HisPolicyEndDate=&TaxActual=&PreviousPay=&LateFee=&SumPayTax=&count=0&CommissionRate=2&CommissionTax=&SinoSoftPlatCarShipTaxFlag=true&FinalFlag=N&ClosingDate=&ExtendChar1=&ExtendChar2=&ExtendDate1=&ExtendDate2=&AccountNo=&DistrictCode=&TaxUseNature=&TaxUseStatus=&SpecialCarKind=&RenewalEndDate=&FeeRateOrgin=&TaxReliefOrgin=&WithdrawItemKindNoCI=&GlassTypeModelCI=&RadiusTypeModelCI=&DeductibleSWITCHCI=&CarClauseChgDateCI=&ComCodeForCalCI=&CIBillDate=&RenewalFlagCIJZ=&UnionEndorseCIRenewalFlag=&UnionEndorseCIPolicyNoRenewal=&SumPremiumCIPRO=&ItemKind_FlagCI=&Withdraw_FlagCI=&ItemKindNoCI=&MS_FlagCI=&ItemKindFlag6FlagCI=&KindCodeCI=BZ&KindNameCI=%BB%FA%B6%AF%B3%B5%BD%BB%CD%A8%CA%C2%B9%CA%D4%F0%C8%CE%C7%BF%D6%C6%CF%D5&TotalProfit1CI=0&TotalProfit2CI=0&ItemKind_StartDateCI=&ItemKind_EndDateCI=&ItemKind_NewStartDateCI=&ItemKind_NewEndDateCI=&ItemKindFlag5CI=&ItemKindCalculateFlagCI=&ItemKindFlag3To4CI=&AttachFlagCI=&ItemKindFlag5FlagCI=&DeductibleCI=&DeductibleRateCI=&CurrencyCI=CNY&CurrencyNameCI=%C8%CB%C3%F1%B1%D2&RateCI=&PureRiskPremium1CI=&PureRiskBenchMarkPremiumCI=&AmountCI=122000&BenchMarkPremiumCI=&BasePremiumCI=0&DiscountCI=1.0&AdjustRateCI=1&PremiumCI=&DiedAmountLimitCI=110000.00&CureChargeAmountLimitCI=10000.00&PropertyLossAmountLimitCI=2000.00&NoDutyDiedAmountLimitCI=11000.00&NoDutyCureChargeAmountLimitCI=1000.00&NoDutyPropertyLossAmountLimitCI=100.00&AmountCountCI=&BenchMarkPremiumCountCI=&PremiumDisCountCI=&PremiumCountCI=&DiscountPremiumCountCI=&SumPremiumCI=&totalPureRiskPremiumCI=&CI_RatioId=&CI_DemandNo=&CI_CertiType=&CI_CertiNo=&CI_CiCacheItemCarId=&CI_ClauseType=&CI_CombosCode=&CI_ClaimAdjustValue=&CI_ActuarialAutonomyRatio=&CI_ActuarialChannelRatio=&CI_ActuarialRatio=&CI_ActuarialRatio2=&CI_ActuarialPremium=&CI_UnderwriteAdjustRatio=&CI_UnderwriteAutonomyRatio=&CI_UnderwriteChannelRatio=&CI_DefaultTotalRatio=&CI_DefaultPremium=&CI_ArtificialAutonomyRatio=&CI_ArtificialChannelRatio=&CI_FinalRatio=&CI_ArtificialAdjustRatio=&CI_SumPremium=&CI_Flag=&CI_InsertTime=&CI_LastUpdateTime=&CI_ArtificialUnderwriteRatio=&CI_SendMotifyRatio=&CI_ArtificialUnderwritePremium=&CI_SendMotifyPremium=&CI_FinalRatioU=&CI_FinalRatioD=&CI_MotorCadeRatio=&CI_IlogNo=&CI_BenechmarkPremium=&CI_ExpensePremium=&CI_ProfitRatePremiun=&CI_ActuarialPrice=&CI_SumBenechmarkPremium=&CI_OverProfitRatePremium=&CI_DocumentaryPremium=&CI_ExpectProfitRatePremium=&CI_PeccancyAdjustValue=&CI_singleBusinessNatureRatio=&CI_singlePlateRatio=&CI_useRatio=&CI_RatioId1=&CI_SerialNo=&CI_RatioTypeCode=&CI_FactorTypeCode=&CI_FactorValue=&CI_FactorValueDesc=&CI_Flag1=&CI_InsertTime1=&CI_LastUpdateTime1=&CI_KindCode1=&CI_clauseType2=&CI_combosCode2=&CI_kindCode2=&CI_basicPremium=&CI_factorRatio=&CI_expenseRate=&CI_profitRate=&CI_benechmarkPremium2=&CI_premium=&CI_finalPremium=&CI_underwriteRatio=&CI_benechmarkRatio=&CI_localRatio=&CI_trendRaito=&CI_expensePremium2=&CI_profitRatePremiun2=&CI_predictiveLossRate=&CI_predictiveCostRate=&CI_itemkindno=&CI_claimExpenseRate=&CI_demandNo2=&CI_ResultId2=&CI_Flag2=&AdjustRateOfMainCI=1&SumRealPremium=&ZJtimes=0&JHZJAdjustValue=&ZJtimesMax=0&JHtimes=0&JHtimesMax=0&ShortRate_CI=100.0000&LastBillDate=&tableShortRate_CI_Flag=&Currency_CI=CNY&CurrencyName_CI=%C8%CB%C3%F1%B1%D2&FloatReason=&ClaimReason=&ShortRateFlag_CI=2&PeccReason=&LastDamagedACI=&LastDamagedBCI=&ThisDamagedACI=&ThisDamagedBCI=&ChooseFlagCI=&ProfitCodeCI=&ProfitRateCI=&ProfitDetail_ItemKindNoCI=&ProfitDetail_FieldValueCI=&ProfitDetailEncodeCI=&ProfitDetail_FlagCI=&ClauseNameCI=&defaultClausesContextCI=&EngageCI_Flag=&AutoClauseCIFlag=0&EngageSerialNoCI=&ClauseCodeCI=&ClausesCI=&ClausesContextCI=&clauseTemplateCI=&clauseActiveContentCI=&Query_sequence_no=&XmlContentCIQuery=&Query_sequence_noPRO=&Vehicle_type=&XmlContentCICheck=&CheckCodeCI=A&CIbuttonCheckFlag=0&Car_mark=&Rack_no=&Use_type=&Color=&Engine_no=&Vehicle_register_date=&Owner=&Limit_load_person=&Made_date=&Ineffectual_date=&Limit_load=&Vehicle_model=&Made_factory=&Vehicle_brand_2=&Vehicle_brand_1=&Last_check_date=&Vehicle_style=&Status=&Reject_date=&Haulage=&PreferentialFormula=&AdjustStart=&AdjustEnd=&PeccancyCoeff=&ClaimCoeff=&DemandCarKindCode=&DemandUseNatureCode=&CIDemandFecc_Flag=&CIDemandFeccTypeName=&CIDemandPeccancy_time=&CIDemandPeccancy_place=&CIDemandPeccancy_code=&CIDemandAdjust_rate=&CIDemandPeccancy_type=&CIDemandCerti_type=&CIDemandCerti_code=&CIDemandLossAccept_date=&CIDemandLossAction_desc=&CIDemandLossAction_desc=&Driver_Name=&Peccancy_Detail=&lossCount=0&CIDemandClaim_Flag=&CIDemandClaimTypeName=&CIDemandCompany_no=&CIDemandClaim_no=&CIDemandAccident_time=&CIDemandEndCase_time=&CIDemandClaim_amount=&CIDemandClaim_type=&CIDemandDeath_Flag=&payCount=0&FloatTypeShow=&PreOpeDate=&PreInsuredComCode=&PreInsuredComName=&PrePolicyNo=&PreYestOpeDate=&PreYestInsuredComCode=&PreYestInsuredComName=&PreYestPolicyNo=&PrePreYestOpeDate=&PrePreYestInsuredComCode=&PrePreYestInsuredComName=&PrePreYestPolicyNo=&GetDataFlag=&NoAccident=ND1&AccidentFloat=&AcciSerialNo=&AccidentDate=&ClaimDate=&DeathFlagFlag=&AccidentFloatFlagFlag=&disableCIClaimFlag=2&ModifyJQDataFlag=&NoOffens=OD1&OffensFloat=&OffensSerialNo=&OffensDate=&OffensFactor=F1&OffensFloatFlagFlag=&PlanOneTimesCI=1&PayTimesCI=1&EndorseNoCI=&PlanCI_Flag=&PayReasonCI=&PayNoCI=&PlanStartDateCI=&PlanDateCI=&PrpPlanCurrencyCI=&PrpPlanCurrencyNameCI=&PlanFeeCI=&DelinquentFeeCI=&RealPayFeeCI=&AccountCodeCI=&CustomBankNameCI=&CertificateCodeCI=&OwnerNameCI=&AccountTypeCI=&CustomBankCodeCI=&OwnerPhoneNoCI=&AccountCurrencyCI=&OwnerShipCI=&PrpapaymentAccountOwnerTypeCI=&PrpapaymentAccountAppliNameCI=&PrpapaymentAccountCustomerCodeCI=&PrpapaymentAccountUserCodeCI=&PrpapaymentAccountVehicleComCodeCI=&Expenses_FlagCI=&MaxDisRateCI=4.0&DisRateCI=4.0&PremiumDisRateCI=&AssignnoNewCI=&Agent_Id_CI_Flag=&isSplitFeeFlagCI=&isSpecialFlagCI=0&Agent_Id_CI_IsSplit_Flag=&CISplitFeeAgentCode=&CISplitFeeAgentName=&CISplitFeeRatePayRefReason=&CISplitFeeRatePayRefReasonCode=&CISplitFeeCommissionRate=&CISplitFeeCommissionRateSale=&CISplitFeeCommissionScale=&ChangePremiumDisRateCI=&PureRateCI=&ExpensesFlag2CI=1&MaxManageFeeRateCI=&ManageFeeRateCI=&DisRate1CI=&PremiumDisRate1CI=&ChangePremiumDisRate1CI=&TopCommissionCI=&ChargeSerialNoCI=&ChargeRiskCodeCI=0511&ChargeCI_Flag=&RefreshFlagChargeCI=&ChargeCodeCI=&ChargeNameCI=&Currency2ChargeCI=CNY&CurrencyNameChargeCI=%C8%CB%C3%F1%B1%D2&ExchangeRateChargeCI=&BaseAmountChargeCI=10000&ChargeRateCI=0.0000&PlanFee2ChargeCI=
EOF;
    }

    /**
     * 16063 - end 其他保险 SingleIdentityCI-》 end 直接返回不做任何处理
     *
     * @return string
     */
    public function buildFormParams7()
    {
        return <<<EOF
SingleIdentityCI=&CoinsTypeGCI=&CoinsNameLCI=&ChannelTypeLCI=&ComCodeLCI=&Handler1codeLCI=&CoinsRateGCI=&CoinsAmountCI=&CoinsPremiumCI=&AgentFeeCI=&ProportionFeeCI=&riskFlagCarRelation=JYX&riskFlagCarRelationList=JYX%2CJCX%2CGYX%2C&riskCNameCarRelationList=%BB%FA%B6%AF%B3%B5%BC%DD%CA%BB%C8%CB%D4%B1%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%2C%BB%FA%B6%AF%B3%B5%BC%DD%B3%CB%C8%CB%D4%B1%CD%C5%CC%E5%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%2C%BD%BB%CD%A8%B9%A4%BE%DF%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%2C&isProposalCarRelationJYX=0&RiskCNameCarRelationJYX=%BB%FA%B6%AF%B3%B5%BC%DD%CA%BB%C8%CB%D4%B1%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5&RiskCodeCarRelationJYX=2703&CoinsuranceFlagCarRelationJYX=0&CoinsFlagCarRelationJYX=0&RefreshFlagCoinsCarRelationJYX=0&ProportionFlag1CarRelationJYX=&ProportionFlag2CarRelationJYX=&ProposalNoCarRelationJYX=&dlfalgCarRelationJYX=&unUpdateCarRelationJYX=&CoinslinkFlagCarRelationJYX=0&BusinessNature_FlagCarRelationJYX=&UserTypeDomainCarRelationJYX=&ChannelTypeCarRelationJYX=03&RelateComCode_FlagCarRelationJYX=false&HandlerCodeCarRelationJYX=null&HandlerNameCarRelationJYX=null&Handler1CodeCarRelationJYX=&Handler1NameCarRelationJYX=&testCarRelationJYX=&MakeComCarRelationJYX=&ComCodeCarRelationJYX=&ComNameCarRelationJYX=&GroupTypeCarRelationJYX=&BusinessNatureCarRelationJYX=&BusinessNatureNameCarRelationJYX=&AgentCodeCarRelationJYX=&AgentNameCarRelationJYX=&AgentDaysCarRelationJYX=&PerendDateCarRelationJYX=&AgentStartSysDaysCarRelationJYX=&AgreementCarRelationJYX=%B4%FA%C0%ED%D0%AD%D2%E9%BA%C5%A3%BA&AgreementNoCarRelationJYX=&AgreeDaysCarRelationJYX=&AgreementEndDateCarRelationJYX=&AgreeStartSysDaysCarRelationJYX=&AgreementFlagCarRelationJYX=&button_SelectAgreement_Insert1CarRelationJYX=%D1%A1%D4%F1%D0%AD%D2%E9&button_SelectAgreement_InsertCarRelationJYX=&FactorPlaceCodeCarRelationJYX=&FactorPlaceNameCarRelationJYX=&FactoryCodeCarRelationJYX=&FactoryNameCarRelationJYX=&psnlevel1CarRelationJYX=&psnlevel2CarRelationJYX=&LifeInsuranceFlagCarRelationJYX=false&SalesCompanyCodeCarRelationJYX=&AgentprofenoCarRelationJYX=&AgentprofenameCarRelationJYX=&handler2PhoneNumberCarRelationJYX=&vipCustomIntSizeCarRelationJYX=&isCIInsureCarRelationJYX=&vipIntSizeCarRelationJYX=&VipFlagCarRelationJYX=false&InOperatorPowerCarRelationJYX=true&CheckEmailCarRelationJYX=&ProtocolCodeCarRelationJYX=&vipCustomPIDCarRelationJYX=&vipCustomPSNNAMECarRelationJYX=&vipCustomPSNLEVEL1CarRelationJYX=&vipCustomPSNLEVEL2CarRelationJYX=&vipCustomCARDNOCarRelationJYX=&vipCustomCARDTYPECarRelationJYX=&vipCustomPSNTYPECarRelationJYX=&vipCustomImagePathCarRelationJYX=&vipCustomStartDateCarRelationJYX=&vipCustomEndDateCarRelationJYX=&vipInsuredIntSizeCarRelationJYX=&vipInsuredPIDCarRelationJYX=&vipInsuredPSNNAMECarRelationJYX=&vipInsuredPSNLEVEL1CarRelationJYX=&vipInsuredPSNLEVEL2CarRelationJYX=&vipInsuredCARDNOCarRelationJYX=&vipInsuredCARDTYPECarRelationJYX=&vipInsuredPSNTYPECarRelationJYX=&vipInsuredImagePathCarRelationJYX=&vipInsuredStartDateCarRelationJYX=&vipInsuredEndDateCarRelationJYX=&SynChronize_flagCarRelationJYX=&CustomerTypeCodeCarRelationJYX=&AppliBusiLicenseCarRelationJYX=&AppliLicenseStartDateCarRelationJYX=&AppliLicenseEndDateCarRelationJYX=&InsuredBusiLicenseCarRelationJYX=&InsuredLicenseStartDateCarRelationJYX=&InsuredLicenseEndDateCarRelationJYX=&CustomerTypeHiddenCarRelationJYX=&ACTIONTYPECarRelationJYX=&UPDATE_CUSTOMER_FLAGCarRelationJYX=&InsuredQueryFlagCarRelationJYX=0&AppliOrInsuredCarRelationJYX=&strFlagCarRelationJYX=&prpDcustomerIdvCustomerCodeCarRelationJYX=&prpDcustomerIdvIdentifyTypeCarRelationJYX=&prpDcustomerIdvIdentifyNumberCarRelationJYX=&prpDcustomerUnitOrganizeIdentifyTypeCarRelationJYX=&prpDcustomerUnitOrganizeCodeCarRelationJYX=&prpDcustomerUnitCustomerCodeCarRelationJYX=&prpDcustomerUnitCustomerCNameCarRelationJYX=&AppliOrInsuredUpdateFlagCarRelationJYX=&IdentifyAppliOrInsuredNameCarRelationJYX=&UseImagePlatCarRelationJYX=true&AppliMobileQueryCarRelationJYX=&AppliPhoneNumberQueryCarRelationJYX=&InsuredMobileQueryCarRelationJYX=&InsuredPhoneNumberQueryCarRelationJYX=&AppliMessageCarRelationJYX=&InsuredMessageCarRelationJYX=&TelephoneRealyTypeFlagCarRelationJYX=0&ElectronicPolicyUtiPowerCarRelationJYX=false&CIElectronicPolicyUtiPowerCarRelationJYX=false&ElectronicProposalUtiPowerCarRelationJYX=false&UnuseFieldCarRelationJYX=&GuangXiCustomerFlagCarRelationJYX=false&MobileFlagCarRelationJYX=&BusinessNatureFlagCarRelationJYX=&CVR_IDCARDCarRelationJYX=false&mustVerfiyFlagCarRelationJYX=false&PrpslPoliFlagCarRelationJYX=false&CardCancelFlagCarRelationJYX=&historyDateFlagCarRelationJYX=&CustomerTypeAppliCarRelationJYX=1&prpDcustomerIdvCustomerCode0CarRelationJYX=&prpDcustomerIdvIdentifyType0CarRelationJYX=01&CertificateNameCarRelationJYX=&prpDcustomerUnitOrganizeIdentifyType0CarRelationJYX=07&AppliNameCarRelationJYX=&AppliSexCarRelationJYX=&prpDcustomerUnitCustomerCName0CarRelationJYX=&prpDcustomerUnitCustomerCode0CarRelationJYX=&prpDcustomerUnitOrganizeCode0CarRelationJYX=&prpDcustomerIdvIdentifyNumber0CarRelationJYX=&AppliAddressCarRelationJYX=&AppliIdentifyTypeCarRelationJYX=&AppliIdentifyNumberCarRelationJYX=&AppliCertificadeNameCarRelationJYX=&AppliAgeCarRelationJYX=&AppliJobCarRelationJYX=&AppliJobCodeCarRelationJYX=&AppliBirthDayCarRelationJYX=&AppliRegistIDCarRelationJYX=&AppliCountryCodeCarRelationJYX=&AppliNationCodeCarRelationJYX=&AppliCUnitOratypeCodeCarRelationJYX=&AppliCUnitOratypeCodeEntCarRelationJYX=&AppliBusinessSourceCodeCarRelationJYX=&AppliMobileCarRelationJYX=&AppliReTimesCarRelationJYX=&AppliPhoneNumberCarRelationJYX=&RelationDealCarRelationJYX=0&FocusNameCarRelationJYX=&AppliCodeCarRelationJYX=&CustomerTypeCarRelationJYX=&NationFlagCarRelationJYX=1&AppliInsuredAccountCarRelationJYX=&AccountCodeZizeCarRelationJYX=&AppliLinkerNameCarRelationJYX=&AppliEmailCarRelationJYX=&Appli_FlagCarRelationJYX=&AppliInsuredBankCarRelationJYX=&AppliPostCodeCarRelationJYX=&SQDBUnitTaxRegisterNoCarRelationJYX=&SameToApplicantCarRelationJYX=on&SameToApplicantInputCarRelationJYX=%CD%AC%CD%B6%B1%A3%C8%CB&UnuseField2CarRelationJYX=&InsuredCountryCodeCarRelationJYX=&InsuredBirthDayCarRelationJYX=&InsuredNationCodeCarRelationJYX=&InsuredRegistIDCarRelationJYX=&InsuredCUnitOratypeCodeCarRelationJYX=&InsuredCUnitOratypeCodeEntCarRelationJYX=&InsuredBusinessSourceCodeCarRelationJYX=&InsuredNatureCarRelationJYX=3&CustomerTypeInsureCarRelationJYX=1&prpDcustomerIdvCustomerCode1CarRelationJYX=&prpDcustomerIdvIdentifyType1CarRelationJYX=01&CertificateName1CarRelationJYX=&prpDcustomerUnitOrganizeIdentifyType1CarRelationJYX=07&InsuredNameCarRelationJYX=&prpDcustomerUnitCustomerCName1CarRelationJYX=&prpDcustomerUnitCustomerCode1CarRelationJYX=&prpDcustomerUnitOrganizeCode1CarRelationJYX=&prpDcustomerIdvIdentifyNumber1CarRelationJYX=&InsuredAddressCarRelationJYX=&BusinessSortCarRelationJYX=99&InsuredMobileCarRelationJYX=&InsuredReTimesCarRelationJYX=&InsuredPhoneNumberCarRelationJYX=&SQDBUnitTaxRegisterNo1CarRelationJYX=&InsuredLinkerNameCarRelationJYX=&InsuredCodeCarRelationJYX=&CustomerType1CarRelationJYX=&InsuredSexCarRelationJYX=1&InsuredJobCarRelationJYX=&InsuredJobCodeCarRelationJYX=&InsuredAgeCarRelationJYX=&InsuredMarriageCarRelationJYX=1&InsuredBankCarRelationJYX=&InsuredEmailCarRelationJYX=&Insured_FlagCarRelationJYX=&InsuredIdentifyTypeCarRelationJYX=&InsuredCertificadeNameCarRelationJYX=&IdentifyNumberCarRelationJYX=&InsuredAccountCarRelationJYX=&InsuredPostCodeCarRelationJYX=&IsHisPolicyBJV295CarRelationJYX=&IsHisPolicyCarRelationJYX=&VehicleOwnerNatureCarRelationJYX=7&CarOwnerInfo_FlagCarRelationJYX=&CredentialCodeCarRelationJYX=01&CredentialNoCarRelationJYX=&CarOwnerPhoneNoCarRelationJYX=&MailingAddressCarRelationJYX=&CarOwnerCountryCodeCarRelationJYX=&VehicleOwnerNatureOldCarRelationJYX=&BIInsureSZ104_valueCarRelationJYX=false&BIInsureSZ104_isHistoryCarRelationJYX=&productCodeCarRelationJYX=27030094&DrivingLicenseTypeCarRelationJYX=&ProposalNumberCarRelationJYX=1&ItemKindMain_FlagCarRelationJYX=&KindCodeMainCarRelationJYX=&KindNameMainCarRelationJYX=&ItemKindNoMainCarRelationJYX=&StartDateMainCarRelationJYX=&EndDateMainCarRelationJYX=&CalculateFlagMainCarRelationJYX=Y&CalculatorMainCarRelationJYX=1000&ItemCodeMainCarRelationJYX=&ItemDetailNameMainCarRelationJYX=&ItemNameMainCarRelationJYX=&ItemNoMainCarRelationJYX=&CurrencyMainCarRelationJYX=CNY&CurrencyNameMainCarRelationJYX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationJYX=&AmountMainShowCarRelationJYX=0.00&PremiumMainCarRelationJYX=0.00&RateMainCarRelationJYX=0.00000&AmountMainCarRelationJYX=0.00&UnitAmountMainCarRelationJYX=0.00&QuantityMainCarRelationJYX=0&ItemKindSub_FlagCarRelationJYX=&KindNameSubCarRelationJYX=&KindCodeSubCarRelationJYX=&ItemKindNoSubCarRelationJYX=&CalculateFlagSubCarRelationJYX=N&ItemCodeSubCarRelationJYX=9999&ItemDetailNameSubCarRelationJYX=&ItemCodeSubCarRelationJYX=0019&ItemNameSubCarRelationJYX=&CurrencySubCarRelationJYX=CNY&CurrencyNameSubCarRelationJYX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationJYX=&DeductibleSubCarRelationJYX=&BenefitRateSubCarRelationJYX=&AmountSubShowCarRelationJYX=0.00&PremiumSubCarRelationJYX=0.00&CurrencySubCarRelationJYX=CNY&CurrencyNameSubCarRelationJYX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationJYX=&AmountSubCarRelationJYX=0.00&RateSubCarRelationJYX=0.00000&UnitAmountSubCarRelationJYX=0.00&QuantitySubCarRelationJYX=0&ClauseCodeCarRelationJYX=T2703100&ClauseHeadCarRelationJYX=%BB%FA%B6%AF%B3%B5%BC%DD%CA%BB%C8%CB%D4%B1%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%CC%D8%D4%BC&ClauseContextCarRelationJYX=++++%B1%BB%B1%A3%CF%D5%C8%CB%CB%F9%D6%A7%B3%F6%B5%C4%B1%D8%D2%AA%BA%CF%C0%ED%B5%C4%A1%A2%B7%FB%BA%CF%B5%B1%B5%D8%C9%E7%BB%E1%D2%BD%C1%C6%B1%A3%CF%D5%D6%F7%B9%DC%B2%BF%C3%C5%B9%E6%B6%A8%BF%C9%B1%A8%CF%FA%B5%C4%D2%BD%C1%C6%B7%D1%D3%C3%A3%AC%B1%A3%CF%D5%C8%CB%BF%DB%B3%FD%C8%CB%C3%F1%B1%D2100%D4%AA%C3%E2%C5%E2%B6%EE%BA%F3%A3%AC%D4%DA%B1%A3%CF%D5%BD%F0%B6%EE%B7%B6%CE%A7%C4%DA%A3%AC%B0%B480%25%B1%C8%C0%FD%B8%F8%B8%B6%D2%BD%C1%C6%B1%A3%CF%D5%BD%F0&ItemKindMain_FlagCarRelationJYX=&KindCodeMainCarRelationJYX=9901669&KindNameMainCarRelationJYX=%BB%FA%B6%AF%B3%B5%BC%DD%CA%BB%C8%CB%D4%B1%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%A3%A8C%A3%A9%CC%F5%BF%EE&ItemKindNoMainCarRelationJYX=1&StartDateMainCarRelationJYX=&EndDateMainCarRelationJYX=&CalculateFlagMainCarRelationJYX=Y&CalculatorMainCarRelationJYX=1000&ItemCodeMainCarRelationJYX=0019&ItemDetailNameMainCarRelationJYX=%CB%C0%CD%F6%C9%CB%B2%D0&ItemNameMainCarRelationJYX=%CB%C0%CD%F6%C9%CB%B2%D0&ItemNoMainCarRelationJYX=1&CurrencyMainCarRelationJYX=CNY&CurrencyNameMainCarRelationJYX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationJYX=&AmountMainShowCarRelationJYX=500%2C000.00&PremiumMainCarRelationJYX=240.00&RateMainCarRelationJYX=0.48&AmountMainCarRelationJYX=500000.00&UnitAmountMainCarRelationJYX=500000.0&QuantityMainCarRelationJYX=1.0&ItemKindSub_FlagCarRelationJYX=&KindNameSubCarRelationJYX=%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%B8%BD%BC%D3%D2%E2%CD%E2%C9%CB%BA%A6%D2%BD%C1%C6%B7%D1%D3%C3%B1%A3%CF%D5%A3%A8B%A3%A9%CC%F5%BF%EE&KindCodeSubCarRelationJYX=9001562&ItemKindNoSubCarRelationJYX=2&CalculateFlagSubCarRelationJYX=N&ItemCodeSubCarRelationJYX=9999&ItemDetailNameSubCarRelationJYX=%CB%C0%CD%F6%C9%CB%B2%D0&ItemCodeSubCarRelationJYX=&ItemNameSubCarRelationJYX=%CB%C0%CD%F6%C9%CB%B2%D0&CurrencySubCarRelationJYX=CNY&CurrencyNameSubCarRelationJYX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationJYX=&DeductibleSubCarRelationJYX=+100&BenefitRateSubCarRelationJYX=80&AmountSubShowCarRelationJYX=50%2C000.00&PremiumSubCarRelationJYX=60.00&CurrencySubCarRelationJYX=CNY&CurrencyNameSubCarRelationJYX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationJYX=&AmountSubCarRelationJYX=50000.00&RateSubCarRelationJYX=1.2&UnitAmountSubCarRelationJYX=50000.0&QuantitySubCarRelationJYX=1.0&SumPremiumCarRelationJYX=300.00&SumPremiumShowCarRelationJYX=300.00+%D4%AA+%2C%C8%CB%C3%F1%B1%D2%B4%F3%D0%B4%C8%FE%B0%DB%D4%AA%D5%FB&SumAmountCarRelationJYX=500000.00&StartDateCarRelationJYX=2017-07-01&StartHourCarRelationJYX=00&StartMinutesCarRelationJYX=00&EndDateCarRelationJYX=2018-06-30&EndHourCarRelationJYX=24&EndMinutesCarRelationJYX=00&riskFlagCarRelationJYX=JYX&riskCNameCarRelationJYX=%BB%FA%B6%AF%B3%B5%BC%DD%CA%BB%C8%CB%D4%B1%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5&BenefRelateSerialNoCarRelationJYX=1&BenefSerialNoCarRelationJYX=&BenefInsured_FlagCarRelationJYX=&BenefInsuredTypeCarRelationJYX=1&BenefOccupationCodeCarRelationJYX=&BenefOccupationNameCarRelationJYX=&BenefOccupationFlagCarRelationJYX=&BenefInsuredNatureCarRelationJYX=3&BenefBusinessSourceCarRelationJYX=&BenefBusinessSourceNameCarRelationJYX=&BenefBusinessSortCarRelationJYX=&BenefBusinessSortNameCarRelationJYX=&BenefInsuredAddressCarRelationJYX=&BenefBankCarRelationJYX=&BenefAccountCarRelationJYX=&BenefLinkerNameCarRelationJYX=&BenefPostAddressCarRelationJYX=&BenefPostCodeCarRelationJYX=&BenefPhoneNumberCarRelationJYX=&BenefNAgeCarRelationJYX=&BenefNBirthdayCarRelationJYX=&BenefNHealthCarRelationJYX=&BenefNMarriageCarRelationJYX=1&BenefNUnitCarRelationJYX=&BenefNUnitPostCodeCarRelationJYX=&BenefNUnitTypeCarRelationJYX=00&BenefNOccupationCodeCarRelationJYX=&BenefNOccupationNameCarRelationJYX=&BenefNLocalPoliceStationCarRelationJYX=&BenefNUnitCarRelationJYX=&BenefNRoomAddressCarRelationJYX=&BenefNRoomPostCodeCarRelationJYX=&BenefNRoomPhoneCarRelationJYX=&BenefALeaderNameCarRelationJYX=&BenefALeaderIDCarRelationJYX=&BenefAPhoneNumberCarRelationJYX=&BenefAPostCodeCarRelationJYX=&BenefABusinessCodeCarRelationJYX=&BenefARevenueRegistNoCarRelationJYX=&BenefPrpInsuredInsuredCodeCarRelationJYX=&BenefBenefitFlagCarRelationJYX=&BenefPrpInsuredInsuredNameCarRelationJYX=&BenefInsuredIdentityCarRelationJYX=01&BenefNSexCarRelationJYX=1&BenefIdentifyTypeCarRelationJYX=01&BenefIdentifyNumberCarRelationJYX=&BenefNBrithdayCarRelationJYX=&BenefBenefitRateCarRelationJYX=&button_BenefInsured_Insert2CarRelationJYX=%D1%A1+%D4%F1&Expenses_FlagCarRelationJYX=&RefreshFlagExpensesCarRelationJYX=&DisProportionFlagCarRelationJYX=&SelfRateCarRelationJYX=&MaxDisRateCarRelationJYX=&DisRateCarRelationJYX=&PremiumDisRateCarRelationJYX=0.00&ChangePremiumDisRateCarRelationJYX=&ExpensesFlag2CarRelationJYX=&MaxManageFeeRateCarRelationJYX=&ManageFeeRateCarRelationJYX=&DisProportionFlag1CarRelationJYX=&SelfRate1CarRelationJYX=&DisRate1CarRelationJYX=&PremiumDisRate1CarRelationJYX=&ChangePremiumDisRate1CarRelationJYX=&ChargeSerialNoCarRelationJYX=&ChargeRiskCodeCarRelationJYX=&ChargeFlagCarRelationJYX=&RefreshFlagChargeCarRelationJYX=&Currency2FeeJYX=CNY&ChargeCodeCarRelationJYX=&ChargeNameCarRelationJYX=&Currency2ChargeCarRelationJYX=CNY&CurrencyNameChargeCarRelationJYX=%C8%CB%C3%F1%B1%D2&ExchangeRateChargeCarRelationJYX=&BaseAmountCarRelationJYX=10000&ChargeRateCarRelationJYX=0.0000&PlanFee2ChargeCarRelationJYX=&isProposalCarRelationJCX=0&RiskCNameCarRelationJCX=%BB%FA%B6%AF%B3%B5%BC%DD%B3%CB%C8%CB%D4%B1%CD%C5%CC%E5%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5&RiskCodeCarRelationJCX=2703&CoinsuranceFlagCarRelationJCX=0&CoinsFlagCarRelationJCX=0&RefreshFlagCoinsCarRelationJCX=0&ProportionFlag1CarRelationJCX=&ProportionFlag2CarRelationJCX=&ProposalNoCarRelationJCX=&dlfalgCarRelationJCX=&unUpdateCarRelationJCX=&CoinslinkFlagCarRelationJCX=0&BusinessNature_FlagCarRelationJCX=&UserTypeDomainCarRelationJCX=&ChannelTypeCarRelationJCX=03&RelateComCode_FlagCarRelationJCX=false&HandlerCodeCarRelationJCX=null&HandlerNameCarRelationJCX=null&Handler1CodeCarRelationJCX=&Handler1NameCarRelationJCX=&testCarRelationJCX=&MakeComCarRelationJCX=&ComCodeCarRelationJCX=&ComNameCarRelationJCX=&GroupTypeCarRelationJCX=&BusinessNatureCarRelationJCX=&BusinessNatureNameCarRelationJCX=&AgentCodeCarRelationJCX=&AgentNameCarRelationJCX=&AgentDaysCarRelationJCX=&PerendDateCarRelationJCX=&AgentStartSysDaysCarRelationJCX=&AgreementCarRelationJCX=%B4%FA%C0%ED%D0%AD%D2%E9%BA%C5%A3%BA&AgreementNoCarRelationJCX=&AgreeDaysCarRelationJCX=&AgreementEndDateCarRelationJCX=&AgreeStartSysDaysCarRelationJCX=&AgreementFlagCarRelationJCX=&button_SelectAgreement_Insert1CarRelationJCX=%D1%A1%D4%F1%D0%AD%D2%E9&button_SelectAgreement_InsertCarRelationJCX=&FactorPlaceCodeCarRelationJCX=&FactorPlaceNameCarRelationJCX=&FactoryCodeCarRelationJCX=&FactoryNameCarRelationJCX=&psnlevel1CarRelationJCX=&psnlevel2CarRelationJCX=&LifeInsuranceFlagCarRelationJCX=false&SalesCompanyCodeCarRelationJCX=&AgentprofenoCarRelationJCX=&AgentprofenameCarRelationJCX=&handler2PhoneNumberCarRelationJCX=&vipCustomIntSizeCarRelationJCX=&isCIInsureCarRelationJCX=&vipIntSizeCarRelationJCX=&VipFlagCarRelationJCX=false&InOperatorPowerCarRelationJCX=true&CheckEmailCarRelationJCX=&ProtocolCodeCarRelationJCX=&vipCustomPIDCarRelationJCX=&vipCustomPSNNAMECarRelationJCX=&vipCustomPSNLEVEL1CarRelationJCX=&vipCustomPSNLEVEL2CarRelationJCX=&vipCustomCARDNOCarRelationJCX=&vipCustomCARDTYPECarRelationJCX=&vipCustomPSNTYPECarRelationJCX=&vipCustomImagePathCarRelationJCX=&vipCustomStartDateCarRelationJCX=&vipCustomEndDateCarRelationJCX=&vipInsuredIntSizeCarRelationJCX=&vipInsuredPIDCarRelationJCX=&vipInsuredPSNNAMECarRelationJCX=&vipInsuredPSNLEVEL1CarRelationJCX=&vipInsuredPSNLEVEL2CarRelationJCX=&vipInsuredCARDNOCarRelationJCX=&vipInsuredCARDTYPECarRelationJCX=&vipInsuredPSNTYPECarRelationJCX=&vipInsuredImagePathCarRelationJCX=&vipInsuredStartDateCarRelationJCX=&vipInsuredEndDateCarRelationJCX=&SynChronize_flagCarRelationJCX=&CustomerTypeCodeCarRelationJCX=&AppliBusiLicenseCarRelationJCX=&AppliLicenseStartDateCarRelationJCX=&AppliLicenseEndDateCarRelationJCX=&InsuredBusiLicenseCarRelationJCX=&InsuredLicenseStartDateCarRelationJCX=&InsuredLicenseEndDateCarRelationJCX=&CustomerTypeHiddenCarRelationJCX=&ACTIONTYPECarRelationJCX=&UPDATE_CUSTOMER_FLAGCarRelationJCX=&InsuredQueryFlagCarRelationJCX=0&AppliOrInsuredCarRelationJCX=&strFlagCarRelationJCX=&prpDcustomerIdvCustomerCodeCarRelationJCX=&prpDcustomerIdvIdentifyTypeCarRelationJCX=&prpDcustomerIdvIdentifyNumberCarRelationJCX=&prpDcustomerUnitOrganizeIdentifyTypeCarRelationJCX=&prpDcustomerUnitOrganizeCodeCarRelationJCX=&prpDcustomerUnitCustomerCodeCarRelationJCX=&prpDcustomerUnitCustomerCNameCarRelationJCX=&AppliOrInsuredUpdateFlagCarRelationJCX=&IdentifyAppliOrInsuredNameCarRelationJCX=&UseImagePlatCarRelationJCX=true&AppliMobileQueryCarRelationJCX=&AppliPhoneNumberQueryCarRelationJCX=&InsuredMobileQueryCarRelationJCX=&InsuredPhoneNumberQueryCarRelationJCX=&AppliMessageCarRelationJCX=&InsuredMessageCarRelationJCX=&TelephoneRealyTypeFlagCarRelationJCX=0&ElectronicPolicyUtiPowerCarRelationJCX=false&CIElectronicPolicyUtiPowerCarRelationJCX=false&ElectronicProposalUtiPowerCarRelationJCX=false&UnuseFieldCarRelationJCX=&GuangXiCustomerFlagCarRelationJCX=false&MobileFlagCarRelationJCX=&BusinessNatureFlagCarRelationJCX=&CVR_IDCARDCarRelationJCX=false&mustVerfiyFlagCarRelationJCX=false&PrpslPoliFlagCarRelationJCX=false&CardCancelFlagCarRelationJCX=&historyDateFlagCarRelationJCX=&CustomerTypeAppliCarRelationJCX=1&prpDcustomerIdvCustomerCode0CarRelationJCX=&prpDcustomerIdvIdentifyType0CarRelationJCX=01&CertificateNameCarRelationJCX=&prpDcustomerUnitOrganizeIdentifyType0CarRelationJCX=07&AppliNameCarRelationJCX=&AppliSexCarRelationJCX=&prpDcustomerUnitCustomerCName0CarRelationJCX=&prpDcustomerUnitCustomerCode0CarRelationJCX=&prpDcustomerUnitOrganizeCode0CarRelationJCX=&prpDcustomerIdvIdentifyNumber0CarRelationJCX=&AppliAddressCarRelationJCX=&AppliIdentifyTypeCarRelationJCX=&AppliIdentifyNumberCarRelationJCX=&AppliCertificadeNameCarRelationJCX=&AppliAgeCarRelationJCX=&AppliJobCarRelationJCX=&AppliJobCodeCarRelationJCX=&AppliBirthDayCarRelationJCX=&AppliRegistIDCarRelationJCX=&AppliCountryCodeCarRelationJCX=&AppliNationCodeCarRelationJCX=&AppliCUnitOratypeCodeCarRelationJCX=&AppliCUnitOratypeCodeEntCarRelationJCX=&AppliBusinessSourceCodeCarRelationJCX=&AppliMobileCarRelationJCX=&AppliReTimesCarRelationJCX=&AppliPhoneNumberCarRelationJCX=&RelationDealCarRelationJCX=0&FocusNameCarRelationJCX=&AppliCodeCarRelationJCX=&CustomerTypeCarRelationJCX=&NationFlagCarRelationJCX=1&AppliInsuredAccountCarRelationJCX=&AccountCodeZizeCarRelationJCX=&AppliLinkerNameCarRelationJCX=&AppliEmailCarRelationJCX=&Appli_FlagCarRelationJCX=&AppliInsuredBankCarRelationJCX=&AppliPostCodeCarRelationJCX=&SQDBUnitTaxRegisterNoCarRelationJCX=&SameToApplicantCarRelationJCX=on&SameToApplicantInputCarRelationJCX=%CD%AC%CD%B6%B1%A3%C8%CB&UnuseField2CarRelationJCX=&InsuredCountryCodeCarRelationJCX=&InsuredBirthDayCarRelationJCX=&InsuredNationCodeCarRelationJCX=&InsuredRegistIDCarRelationJCX=&InsuredCUnitOratypeCodeCarRelationJCX=&InsuredCUnitOratypeCodeEntCarRelationJCX=&InsuredBusinessSourceCodeCarRelationJCX=&InsuredNatureCarRelationJCX=3&CustomerTypeInsureCarRelationJCX=1&prpDcustomerIdvCustomerCode1CarRelationJCX=&prpDcustomerIdvIdentifyType1CarRelationJCX=01&CertificateName1CarRelationJCX=&prpDcustomerUnitOrganizeIdentifyType1CarRelationJCX=07&InsuredNameCarRelationJCX=&prpDcustomerUnitCustomerCName1CarRelationJCX=&prpDcustomerUnitCustomerCode1CarRelationJCX=&prpDcustomerUnitOrganizeCode1CarRelationJCX=&prpDcustomerIdvIdentifyNumber1CarRelationJCX=&InsuredAddressCarRelationJCX=&BusinessSortCarRelationJCX=99&InsuredMobileCarRelationJCX=&InsuredReTimesCarRelationJCX=&InsuredPhoneNumberCarRelationJCX=&SQDBUnitTaxRegisterNo1CarRelationJCX=&InsuredLinkerNameCarRelationJCX=&InsuredCodeCarRelationJCX=&CustomerType1CarRelationJCX=&InsuredSexCarRelationJCX=1&InsuredJobCarRelationJCX=&InsuredJobCodeCarRelationJCX=&InsuredAgeCarRelationJCX=&InsuredMarriageCarRelationJCX=1&InsuredBankCarRelationJCX=&InsuredEmailCarRelationJCX=&Insured_FlagCarRelationJCX=&InsuredIdentifyTypeCarRelationJCX=&InsuredCertificadeNameCarRelationJCX=&IdentifyNumberCarRelationJCX=&InsuredAccountCarRelationJCX=&InsuredPostCodeCarRelationJCX=&IsHisPolicyBJV295CarRelationJCX=&IsHisPolicyCarRelationJCX=&VehicleOwnerNatureCarRelationJCX=7&CarOwnerInfo_FlagCarRelationJCX=&CredentialCodeCarRelationJCX=01&CredentialNoCarRelationJCX=&CarOwnerPhoneNoCarRelationJCX=&MailingAddressCarRelationJCX=&CarOwnerCountryCodeCarRelationJCX=&VehicleOwnerNatureOldCarRelationJCX=&BIInsureSZ104_valueCarRelationJCX=false&BIInsureSZ104_isHistoryCarRelationJCX=&productCodeCarRelationJCX=27030172&DrivingLicenseTypeCarRelationJCX=&ProposalNumberCarRelationJCX=1&ItemKindMain_FlagCarRelationJCX=&KindCodeMainCarRelationJCX=&KindNameMainCarRelationJCX=&ItemKindNoMainCarRelationJCX=&StartDateMainCarRelationJCX=&EndDateMainCarRelationJCX=&CalculateFlagMainCarRelationJCX=Y&CalculatorMainCarRelationJCX=1000&ItemCodeMainCarRelationJCX=&ItemDetailNameMainCarRelationJCX=&ItemNameMainCarRelationJCX=&ItemNoMainCarRelationJCX=&CurrencyMainCarRelationJCX=CNY&CurrencyNameMainCarRelationJCX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationJCX=&AmountMainShowCarRelationJCX=0.00&PremiumMainCarRelationJCX=0.00&RateMainCarRelationJCX=0.00000&AmountMainCarRelationJCX=0.00&UnitAmountMainCarRelationJCX=0.00&QuantityMainCarRelationJCX=0&ItemKindSub_FlagCarRelationJCX=&KindNameSubCarRelationJCX=&KindCodeSubCarRelationJCX=&ItemKindNoSubCarRelationJCX=&CalculateFlagSubCarRelationJCX=N&ItemCodeSubCarRelationJCX=9999&ItemDetailNameSubCarRelationJCX=&ItemCodeSubCarRelationJCX=&ItemNameSubCarRelationJCX=&CurrencySubCarRelationJCX=CNY&CurrencyNameSubCarRelationJCX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationJCX=&DeductibleSubCarRelationJCX=&BenefitRateSubCarRelationJCX=&AmountSubShowCarRelationJCX=0.00&PremiumSubCarRelationJCX=0.00&CurrencySubCarRelationJCX=CNY&CurrencyNameSubCarRelationJCX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationJCX=&AmountSubCarRelationJCX=0.00&RateSubCarRelationJCX=0.00000&UnitAmountSubCarRelationJCX=0.00&QuantitySubCarRelationJCX=0&ClauseCodeCarRelationJCX=T2703JCX&ClauseHeadCarRelationJCX=%BB%FA%B6%AF%B3%B5%BC%DD%B3%CB%C8%CB%D4%B1%CD%C5%CC%E5%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%CC%D8%D4%BC&ClauseContextCarRelationJCX=++++%B1%BB%B1%A3%CF%D5%C8%CB%CB%F9%D6%A7%B3%F6%B5%C4%B1%D8%D2%AA%BA%CF%C0%ED%B5%C4%A1%A2%B7%FB%BA%CF%B5%B1%B5%D8%C9%E7%BB%E1%D2%BD%C1%C6%B1%A3%CF%D5%D6%F7%B9%DC%B2%BF%C3%C5%B9%E6%B6%A8%BF%C9%B1%A8%CF%FA%B5%C4%D2%BD%C1%C6%B7%D1%D3%C3%A3%AC%B1%A3%CF%D5%C8%CB%BF%DB%B3%FD%C8%CB%C3%F1%B1%D2100%D4%AA%C3%E2%C5%E2%B6%EE%BA%F3%A3%AC%D4%DA%B1%A3%CF%D5%BD%F0%B6%EE%B7%B6%CE%A7%C4%DA%A3%AC%B0%B480%25%B1%C8%C0%FD%B8%F8%B8%B6%D2%BD%C1%C6%B1%A3%CF%D5%BD%F0&ItemKindMain_FlagCarRelationJCX=&KindCodeMainCarRelationJCX=9901704&KindNameMainCarRelationJCX=%BB%FA%B6%AF%B3%B5%BC%DD%B3%CB%C8%CB%D4%B1%CD%C5%CC%E5%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%CC%F5%BF%EE&ItemKindNoMainCarRelationJCX=1&StartDateMainCarRelationJCX=&EndDateMainCarRelationJCX=&CalculateFlagMainCarRelationJCX=Y&CalculatorMainCarRelationJCX=1000&ItemCodeMainCarRelationJCX=0019&ItemDetailNameMainCarRelationJCX=%CB%C0%CD%F6%C9%CB%B2%D0&ItemNameMainCarRelationJCX=%CB%C0%CD%F6%C9%CB%B2%D0&ItemNoMainCarRelationJCX=1&CurrencyMainCarRelationJCX=CNY&CurrencyNameMainCarRelationJCX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationJCX=&AmountMainShowCarRelationJCX=300%2C000.00&PremiumMainCarRelationJCX=75.00&RateMainCarRelationJCX=0.25&AmountMainCarRelationJCX=300000.00&UnitAmountMainCarRelationJCX=300000.0&QuantityMainCarRelationJCX=1.0&ItemKindSub_FlagCarRelationJCX=&KindNameSubCarRelationJCX=%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%B8%BD%BC%D3%D2%E2%CD%E2%C9%CB%BA%A6%D2%BD%C1%C6%B7%D1%D3%C3%B1%A3%CF%D5%A3%A8B%A3%A9%CC%F5%BF%EE&KindCodeSubCarRelationJCX=9001562&ItemKindNoSubCarRelationJCX=2&CalculateFlagSubCarRelationJCX=N&ItemCodeSubCarRelationJCX=9999&ItemDetailNameSubCarRelationJCX=&ItemCodeSubCarRelationJCX=&ItemNameSubCarRelationJCX=&CurrencySubCarRelationJCX=CNY&CurrencyNameSubCarRelationJCX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationJCX=&DeductibleSubCarRelationJCX=+100&BenefitRateSubCarRelationJCX=80&AmountSubShowCarRelationJCX=30%2C000.00&PremiumSubCarRelationJCX=15.00&CurrencySubCarRelationJCX=CNY&CurrencyNameSubCarRelationJCX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationJCX=&AmountSubCarRelationJCX=30000.00&RateSubCarRelationJCX=0.5&UnitAmountSubCarRelationJCX=30000.0&QuantitySubCarRelationJCX=1.0&SumPremiumCarRelationJCX=90.00&SumPremiumShowCarRelationJCX=90.00+%D4%AA+%2C%C8%CB%C3%F1%B1%D2%B4%F3%D0%B4%BE%C1%CA%B0%D4%AA%D5%FB&SumAmountCarRelationJCX=300000.00&StartDateCarRelationJCX=2017-06-24&StartHourCarRelationJCX=0&StartMinutesCarRelationJCX=0&EndDateCarRelationJCX=2018-06-23&EndHourCarRelationJCX=24&EndMinutesCarRelationJCX=00&riskFlagCarRelationJCX=JCX&riskCNameCarRelationJCX=%BB%FA%B6%AF%B3%B5%BC%DD%B3%CB%C8%CB%D4%B1%CD%C5%CC%E5%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5&BenefRelateSerialNoCarRelationJCX=1&BenefSerialNoCarRelationJCX=&BenefInsured_FlagCarRelationJCX=&BenefInsuredTypeCarRelationJCX=1&BenefOccupationCodeCarRelationJCX=&BenefOccupationNameCarRelationJCX=&BenefOccupationFlagCarRelationJCX=&BenefInsuredNatureCarRelationJCX=3&BenefBusinessSourceCarRelationJCX=&BenefBusinessSourceNameCarRelationJCX=&BenefBusinessSortCarRelationJCX=&BenefBusinessSortNameCarRelationJCX=&BenefInsuredAddressCarRelationJCX=&BenefBankCarRelationJCX=&BenefAccountCarRelationJCX=&BenefLinkerNameCarRelationJCX=&BenefPostAddressCarRelationJCX=&BenefPostCodeCarRelationJCX=&BenefPhoneNumberCarRelationJCX=&BenefNAgeCarRelationJCX=&BenefNBirthdayCarRelationJCX=&BenefNHealthCarRelationJCX=&BenefNMarriageCarRelationJCX=1&BenefNUnitCarRelationJCX=&BenefNUnitPostCodeCarRelationJCX=&BenefNUnitTypeCarRelationJCX=00&BenefNOccupationCodeCarRelationJCX=&BenefNOccupationNameCarRelationJCX=&BenefNLocalPoliceStationCarRelationJCX=&BenefNUnitCarRelationJCX=&BenefNRoomAddressCarRelationJCX=&BenefNRoomPostCodeCarRelationJCX=&BenefNRoomPhoneCarRelationJCX=&BenefALeaderNameCarRelationJCX=&BenefALeaderIDCarRelationJCX=&BenefAPhoneNumberCarRelationJCX=&BenefAPostCodeCarRelationJCX=&BenefABusinessCodeCarRelationJCX=&BenefARevenueRegistNoCarRelationJCX=&BenefPrpInsuredInsuredCodeCarRelationJCX=&BenefBenefitFlagCarRelationJCX=&BenefPrpInsuredInsuredNameCarRelationJCX=&BenefInsuredIdentityCarRelationJCX=01&BenefNSexCarRelationJCX=1&BenefIdentifyTypeCarRelationJCX=01&BenefIdentifyNumberCarRelationJCX=&BenefNBrithdayCarRelationJCX=&BenefBenefitRateCarRelationJCX=&button_BenefInsured_Insert2CarRelationJCX=%D1%A1+%D4%F1&Expenses_FlagCarRelationJCX=&RefreshFlagExpensesCarRelationJCX=&DisProportionFlagCarRelationJCX=&SelfRateCarRelationJCX=&MaxDisRateCarRelationJCX=&DisRateCarRelationJCX=&PremiumDisRateCarRelationJCX=0.00&ChangePremiumDisRateCarRelationJCX=&ExpensesFlag2CarRelationJCX=&MaxManageFeeRateCarRelationJCX=&ManageFeeRateCarRelationJCX=&DisProportionFlag1CarRelationJCX=&SelfRate1CarRelationJCX=&DisRate1CarRelationJCX=&PremiumDisRate1CarRelationJCX=&ChangePremiumDisRate1CarRelationJCX=&ChargeSerialNoCarRelationJCX=&ChargeRiskCodeCarRelationJCX=&ChargeFlagCarRelationJCX=&RefreshFlagChargeCarRelationJCX=&Currency2FeeJCX=CNY&ChargeCodeCarRelationJCX=&ChargeNameCarRelationJCX=&Currency2ChargeCarRelationJCX=CNY&CurrencyNameChargeCarRelationJCX=%C8%CB%C3%F1%B1%D2&ExchangeRateChargeCarRelationJCX=&BaseAmountCarRelationJCX=10000&ChargeRateCarRelationJCX=0.0000&PlanFee2ChargeCarRelationJCX=&isProposalCarRelationGYX=0&RiskCNameCarRelationGYX=%BD%BB%CD%A8%B9%A4%BE%DF%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5&RiskCodeCarRelationGYX=2727&CoinsuranceFlagCarRelationGYX=0&CoinsFlagCarRelationGYX=0&RefreshFlagCoinsCarRelationGYX=0&ProportionFlag1CarRelationGYX=&ProportionFlag2CarRelationGYX=&ProposalNoCarRelationGYX=&dlfalgCarRelationGYX=&unUpdateCarRelationGYX=&CoinslinkFlagCarRelationGYX=0&BusinessNature_FlagCarRelationGYX=&UserTypeDomainCarRelationGYX=&ChannelTypeCarRelationGYX=03&RelateComCode_FlagCarRelationGYX=false&HandlerCodeCarRelationGYX=null&HandlerNameCarRelationGYX=null&Handler1CodeCarRelationGYX=&Handler1NameCarRelationGYX=&testCarRelationGYX=&MakeComCarRelationGYX=&ComCodeCarRelationGYX=&ComNameCarRelationGYX=&GroupTypeCarRelationGYX=&BusinessNatureCarRelationGYX=&BusinessNatureNameCarRelationGYX=&AgentCodeCarRelationGYX=&AgentNameCarRelationGYX=&AgentDaysCarRelationGYX=&PerendDateCarRelationGYX=&AgentStartSysDaysCarRelationGYX=&AgreementCarRelationGYX=%B4%FA%C0%ED%D0%AD%D2%E9%BA%C5%A3%BA&AgreementNoCarRelationGYX=&AgreeDaysCarRelationGYX=&AgreementEndDateCarRelationGYX=&AgreeStartSysDaysCarRelationGYX=&AgreementFlagCarRelationGYX=&button_SelectAgreement_Insert1CarRelationGYX=%D1%A1%D4%F1%D0%AD%D2%E9&button_SelectAgreement_InsertCarRelationGYX=&FactorPlaceCodeCarRelationGYX=&FactorPlaceNameCarRelationGYX=&FactoryCodeCarRelationGYX=&FactoryNameCarRelationGYX=&psnlevel1CarRelationGYX=&psnlevel2CarRelationGYX=&LifeInsuranceFlagCarRelationGYX=false&SalesCompanyCodeCarRelationGYX=&AgentprofenoCarRelationGYX=&AgentprofenameCarRelationGYX=&handler2PhoneNumberCarRelationGYX=&vipCustomIntSizeCarRelationGYX=&isCIInsureCarRelationGYX=&vipIntSizeCarRelationGYX=&VipFlagCarRelationGYX=false&InOperatorPowerCarRelationGYX=true&CheckEmailCarRelationGYX=&ProtocolCodeCarRelationGYX=&vipCustomPIDCarRelationGYX=&vipCustomPSNNAMECarRelationGYX=&vipCustomPSNLEVEL1CarRelationGYX=&vipCustomPSNLEVEL2CarRelationGYX=&vipCustomCARDNOCarRelationGYX=&vipCustomCARDTYPECarRelationGYX=&vipCustomPSNTYPECarRelationGYX=&vipCustomImagePathCarRelationGYX=&vipCustomStartDateCarRelationGYX=&vipCustomEndDateCarRelationGYX=&vipInsuredIntSizeCarRelationGYX=&vipInsuredPIDCarRelationGYX=&vipInsuredPSNNAMECarRelationGYX=&vipInsuredPSNLEVEL1CarRelationGYX=&vipInsuredPSNLEVEL2CarRelationGYX=&vipInsuredCARDNOCarRelationGYX=&vipInsuredCARDTYPECarRelationGYX=&vipInsuredPSNTYPECarRelationGYX=&vipInsuredImagePathCarRelationGYX=&vipInsuredStartDateCarRelationGYX=&vipInsuredEndDateCarRelationGYX=&SynChronize_flagCarRelationGYX=&CustomerTypeCodeCarRelationGYX=&AppliBusiLicenseCarRelationGYX=&AppliLicenseStartDateCarRelationGYX=&AppliLicenseEndDateCarRelationGYX=&InsuredBusiLicenseCarRelationGYX=&InsuredLicenseStartDateCarRelationGYX=&InsuredLicenseEndDateCarRelationGYX=&CustomerTypeHiddenCarRelationGYX=&ACTIONTYPECarRelationGYX=&UPDATE_CUSTOMER_FLAGCarRelationGYX=&InsuredQueryFlagCarRelationGYX=0&AppliOrInsuredCarRelationGYX=&strFlagCarRelationGYX=&prpDcustomerIdvCustomerCodeCarRelationGYX=&prpDcustomerIdvIdentifyTypeCarRelationGYX=&prpDcustomerIdvIdentifyNumberCarRelationGYX=&prpDcustomerUnitOrganizeIdentifyTypeCarRelationGYX=&prpDcustomerUnitOrganizeCodeCarRelationGYX=&prpDcustomerUnitCustomerCodeCarRelationGYX=&prpDcustomerUnitCustomerCNameCarRelationGYX=&AppliOrInsuredUpdateFlagCarRelationGYX=&IdentifyAppliOrInsuredNameCarRelationGYX=&UseImagePlatCarRelationGYX=true&AppliMobileQueryCarRelationGYX=&AppliPhoneNumberQueryCarRelationGYX=&InsuredMobileQueryCarRelationGYX=&InsuredPhoneNumberQueryCarRelationGYX=&AppliMessageCarRelationGYX=&InsuredMessageCarRelationGYX=&TelephoneRealyTypeFlagCarRelationGYX=0&ElectronicPolicyUtiPowerCarRelationGYX=false&CIElectronicPolicyUtiPowerCarRelationGYX=false&ElectronicProposalUtiPowerCarRelationGYX=false&UnuseFieldCarRelationGYX=&GuangXiCustomerFlagCarRelationGYX=false&MobileFlagCarRelationGYX=&BusinessNatureFlagCarRelationGYX=&CVR_IDCARDCarRelationGYX=false&mustVerfiyFlagCarRelationGYX=false&PrpslPoliFlagCarRelationGYX=false&CardCancelFlagCarRelationGYX=&historyDateFlagCarRelationGYX=&CustomerTypeAppliCarRelationGYX=1&prpDcustomerIdvCustomerCode0CarRelationGYX=&prpDcustomerIdvIdentifyType0CarRelationGYX=01&CertificateNameCarRelationGYX=&prpDcustomerUnitOrganizeIdentifyType0CarRelationGYX=07&AppliNameCarRelationGYX=&AppliSexCarRelationGYX=&prpDcustomerUnitCustomerCName0CarRelationGYX=&prpDcustomerUnitCustomerCode0CarRelationGYX=&prpDcustomerUnitOrganizeCode0CarRelationGYX=&prpDcustomerIdvIdentifyNumber0CarRelationGYX=&AppliAddressCarRelationGYX=&AppliIdentifyTypeCarRelationGYX=&AppliIdentifyNumberCarRelationGYX=&AppliCertificadeNameCarRelationGYX=&AppliAgeCarRelationGYX=&AppliJobCarRelationGYX=&AppliJobCodeCarRelationGYX=&AppliBirthDayCarRelationGYX=&AppliRegistIDCarRelationGYX=&AppliCountryCodeCarRelationGYX=&AppliNationCodeCarRelationGYX=&AppliCUnitOratypeCodeCarRelationGYX=&AppliCUnitOratypeCodeEntCarRelationGYX=&AppliBusinessSourceCodeCarRelationGYX=&AppliMobileCarRelationGYX=&AppliReTimesCarRelationGYX=&AppliPhoneNumberCarRelationGYX=&RelationDealCarRelationGYX=0&FocusNameCarRelationGYX=&AppliCodeCarRelationGYX=&CustomerTypeCarRelationGYX=&NationFlagCarRelationGYX=1&AppliInsuredAccountCarRelationGYX=&AccountCodeZizeCarRelationGYX=&AppliLinkerNameCarRelationGYX=&AppliEmailCarRelationGYX=&Appli_FlagCarRelationGYX=&AppliInsuredBankCarRelationGYX=&AppliPostCodeCarRelationGYX=&SQDBUnitTaxRegisterNoCarRelationGYX=&SameToApplicantCarRelationGYX=on&SameToApplicantInputCarRelationGYX=%CD%AC%CD%B6%B1%A3%C8%CB&UnuseField2CarRelationGYX=&InsuredCountryCodeCarRelationGYX=&InsuredBirthDayCarRelationGYX=&InsuredNationCodeCarRelationGYX=&InsuredRegistIDCarRelationGYX=&InsuredCUnitOratypeCodeCarRelationGYX=&InsuredCUnitOratypeCodeEntCarRelationGYX=&InsuredBusinessSourceCodeCarRelationGYX=&InsuredNatureCarRelationGYX=3&CustomerTypeInsureCarRelationGYX=1&prpDcustomerIdvCustomerCode1CarRelationGYX=&prpDcustomerIdvIdentifyType1CarRelationGYX=01&CertificateName1CarRelationGYX=&prpDcustomerUnitOrganizeIdentifyType1CarRelationGYX=07&InsuredNameCarRelationGYX=&prpDcustomerUnitCustomerCName1CarRelationGYX=&prpDcustomerUnitCustomerCode1CarRelationGYX=&prpDcustomerUnitOrganizeCode1CarRelationGYX=&prpDcustomerIdvIdentifyNumber1CarRelationGYX=&InsuredAddressCarRelationGYX=&BusinessSortCarRelationGYX=99&InsuredMobileCarRelationGYX=&InsuredReTimesCarRelationGYX=&InsuredPhoneNumberCarRelationGYX=&SQDBUnitTaxRegisterNo1CarRelationGYX=&InsuredLinkerNameCarRelationGYX=&InsuredCodeCarRelationGYX=&CustomerType1CarRelationGYX=&InsuredSexCarRelationGYX=1&InsuredJobCarRelationGYX=&InsuredJobCodeCarRelationGYX=&InsuredAgeCarRelationGYX=&InsuredMarriageCarRelationGYX=1&InsuredBankCarRelationGYX=&InsuredEmailCarRelationGYX=&Insured_FlagCarRelationGYX=&InsuredIdentifyTypeCarRelationGYX=&InsuredCertificadeNameCarRelationGYX=&IdentifyNumberCarRelationGYX=&InsuredAccountCarRelationGYX=&InsuredPostCodeCarRelationGYX=&IsHisPolicyBJV295CarRelationGYX=&IsHisPolicyCarRelationGYX=&VehicleOwnerNatureCarRelationGYX=7&CarOwnerInfo_FlagCarRelationGYX=&CredentialCodeCarRelationGYX=01&CredentialNoCarRelationGYX=&CarOwnerPhoneNoCarRelationGYX=&MailingAddressCarRelationGYX=&CarOwnerCountryCodeCarRelationGYX=&VehicleOwnerNatureOldCarRelationGYX=&BIInsureSZ104_valueCarRelationGYX=false&BIInsureSZ104_isHistoryCarRelationGYX=&productCodeCarRelationGYX=27270055&DrivingLicenseTypeCarRelationGYX=&ProposalNumberCarRelationGYX=1&ItemKindMain_FlagCarRelationGYX=&KindCodeMainCarRelationGYX=&KindNameMainCarRelationGYX=&ItemKindNoMainCarRelationGYX=&StartDateMainCarRelationGYX=&EndDateMainCarRelationGYX=&CalculateFlagMainCarRelationGYX=Y&CalculatorMainCarRelationGYX=1000&ItemCodeMainCarRelationGYX=&ItemDetailNameMainCarRelationGYX=&ItemNameMainCarRelationGYX=&ItemNoMainCarRelationGYX=&CurrencyMainCarRelationGYX=CNY&CurrencyNameMainCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationGYX=&AmountMainShowCarRelationGYX=0.00&PremiumMainCarRelationGYX=0.00&RateMainCarRelationGYX=0.00000&AmountMainCarRelationGYX=0.00&UnitAmountMainCarRelationGYX=0.00&QuantityMainCarRelationGYX=0&ItemKindSub_FlagCarRelationGYX=&KindNameSubCarRelationGYX=&KindCodeSubCarRelationGYX=&ItemKindNoSubCarRelationGYX=&CalculateFlagSubCarRelationGYX=N&ItemCodeSubCarRelationGYX=9999&ItemDetailNameSubCarRelationGYX=&ItemCodeSubCarRelationGYX=&ItemNameSubCarRelationGYX=&CurrencySubCarRelationGYX=CNY&CurrencyNameSubCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationGYX=&DeductibleSubCarRelationGYX=&BenefitRateSubCarRelationGYX=&AmountSubShowCarRelationGYX=0.00&PremiumSubCarRelationGYX=0.00&CurrencySubCarRelationGYX=CNY&CurrencyNameSubCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationGYX=&AmountSubCarRelationGYX=0.00&RateSubCarRelationGYX=0.00000&UnitAmountSubCarRelationGYX=0.00&QuantitySubCarRelationGYX=0&ClauseCodeCarRelationGYX=T2727GYX&ClauseHeadCarRelationGYX=%BD%BB%CD%A8%B9%A4%BE%DF%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%CC%D8%D4%BC&ClauseContextCarRelationGYX=++++%B1%BB%B1%A3%CF%D5%C8%CB%CB%F9%D6%A7%B3%F6%B5%C4%B1%D8%D2%AA%BA%CF%C0%ED%B5%C4%A1%A2%B7%FB%BA%CF%B5%B1%B5%D8%C9%E7%BB%E1%D2%BD%C1%C6%B1%A3%CF%D5%D6%F7%B9%DC%B2%BF%C3%C5%B9%E6%B6%A8%BF%C9%B1%A8%CF%FA%B5%C4%D2%BD%C1%C6%B7%D1%D3%C3%A3%AC%B1%A3%CF%D5%C8%CB%BF%DB%B3%FD%C8%CB%C3%F1%B1%D2100%D4%AA%C3%E2%C5%E2%B6%EE%BA%F3%A3%AC%D4%DA%B1%A3%CF%D5%BD%F0%B6%EE%B7%B6%CE%A7%C4%DA%A3%AC%B0%B480%25%B1%C8%C0%FD%B8%F8%B8%B6%D2%BD%C1%C6%B1%A3%CF%D5%BD%F0&ItemKindMain_FlagCarRelationGYX=&KindCodeMainCarRelationGYX=9001880&KindNameMainCarRelationGYX=%BD%BB%CD%A8%B9%A4%BE%DF%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%CC%F5%BF%EE&ItemKindNoMainCarRelationGYX=1&StartDateMainCarRelationGYX=&EndDateMainCarRelationGYX=&CalculateFlagMainCarRelationGYX=Y&CalculatorMainCarRelationGYX=1000&ItemCodeMainCarRelationGYX=0001&ItemDetailNameMainCarRelationGYX=%C3%F1%BA%BD%B7%C9%BB%FA&ItemNameMainCarRelationGYX=%C3%F1%BA%BD%B7%C9%BB%FA&ItemNoMainCarRelationGYX=1&CurrencyMainCarRelationGYX=CNY&CurrencyNameMainCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationGYX=&AmountMainShowCarRelationGYX=1%2C000%2C000.00&PremiumMainCarRelationGYX=50.00&RateMainCarRelationGYX=0.05&AmountMainCarRelationGYX=1000000.00&UnitAmountMainCarRelationGYX=1000000.0&QuantityMainCarRelationGYX=1.0&ItemKindMain_FlagCarRelationGYX=&KindCodeMainCarRelationGYX=9001880&KindNameMainCarRelationGYX=%BD%BB%CD%A8%B9%A4%BE%DF%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%CC%F5%BF%EE&ItemKindNoMainCarRelationGYX=2&StartDateMainCarRelationGYX=&EndDateMainCarRelationGYX=&CalculateFlagMainCarRelationGYX=Y&CalculatorMainCarRelationGYX=1000&ItemCodeMainCarRelationGYX=0002&ItemDetailNameMainCarRelationGYX=%BB%F0%B3%B5%A3%A8%B5%D8%CC%FA%A1%A2%C7%E1%B9%EC%A3%A9&ItemNameMainCarRelationGYX=%BB%F0%B3%B5%A3%A8%B5%D8%CC%FA%A1%A2%C7%E1%B9%EC%A3%A9&ItemNoMainCarRelationGYX=2&CurrencyMainCarRelationGYX=CNY&CurrencyNameMainCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationGYX=&AmountMainShowCarRelationGYX=500%2C000.00&PremiumMainCarRelationGYX=30.00&RateMainCarRelationGYX=0.06&AmountMainCarRelationGYX=500000.00&UnitAmountMainCarRelationGYX=500000.0&QuantityMainCarRelationGYX=1.0&ItemKindMain_FlagCarRelationGYX=&KindCodeMainCarRelationGYX=9001880&KindNameMainCarRelationGYX=%BD%BB%CD%A8%B9%A4%BE%DF%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%CC%F5%BF%EE&ItemKindNoMainCarRelationGYX=3&StartDateMainCarRelationGYX=&EndDateMainCarRelationGYX=&CalculateFlagMainCarRelationGYX=Y&CalculatorMainCarRelationGYX=1000&ItemCodeMainCarRelationGYX=0003&ItemDetailNameMainCarRelationGYX=%C6%FB%B3%B5%A3%A8%B5%E7%B3%B5%A1%A2%D3%D0%B9%EC%B5%E7%B3%B5%A3%A9&ItemNameMainCarRelationGYX=%C6%FB%B3%B5%A3%A8%B5%E7%B3%B5%A1%A2%D3%D0%B9%EC%B5%E7%B3%B5%A3%A9&ItemNoMainCarRelationGYX=3&CurrencyMainCarRelationGYX=CNY&CurrencyNameMainCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationGYX=&AmountMainShowCarRelationGYX=300%2C000.00&PremiumMainCarRelationGYX=74.00&RateMainCarRelationGYX=0.246667&AmountMainCarRelationGYX=300000.00&UnitAmountMainCarRelationGYX=300000.0&QuantityMainCarRelationGYX=1.0&ItemKindMain_FlagCarRelationGYX=&KindCodeMainCarRelationGYX=9001880&KindNameMainCarRelationGYX=%BD%BB%CD%A8%B9%A4%BE%DF%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%CC%F5%BF%EE&ItemKindNoMainCarRelationGYX=4&StartDateMainCarRelationGYX=&EndDateMainCarRelationGYX=&CalculateFlagMainCarRelationGYX=Y&CalculatorMainCarRelationGYX=1000&ItemCodeMainCarRelationGYX=0004&ItemDetailNameMainCarRelationGYX=%BF%CD%B4%AC%A1%A2%B6%C9%B4%AC%A1%A2%D3%CE%B4%AC&ItemNameMainCarRelationGYX=%BF%CD%B4%AC%A1%A2%B6%C9%B4%AC%A1%A2%D3%CE%B4%AC&ItemNoMainCarRelationGYX=4&CurrencyMainCarRelationGYX=CNY&CurrencyNameMainCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateMainCarRelationGYX=&AmountMainShowCarRelationGYX=400%2C000.00&PremiumMainCarRelationGYX=30.00&RateMainCarRelationGYX=0.075&AmountMainCarRelationGYX=400000.00&UnitAmountMainCarRelationGYX=400000.0&QuantityMainCarRelationGYX=1.0&ItemKindSub_FlagCarRelationGYX=&KindNameSubCarRelationGYX=%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5%B8%BD%BC%D3%D2%E2%CD%E2%C9%CB%BA%A6%D2%BD%C1%C6%B7%D1%D3%C3%B1%A3%CF%D5%A3%A8B%A3%A9%CC%F5%BF%EE&KindCodeSubCarRelationGYX=9001562&ItemKindNoSubCarRelationGYX=5&CalculateFlagSubCarRelationGYX=N&ItemCodeSubCarRelationGYX=9999&ItemDetailNameSubCarRelationGYX=&ItemCodeSubCarRelationGYX=&ItemNameSubCarRelationGYX=&CurrencySubCarRelationGYX=CNY&CurrencyNameSubCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationGYX=&DeductibleSubCarRelationGYX=+100&BenefitRateSubCarRelationGYX=80&AmountSubShowCarRelationGYX=30%2C000.00&PremiumSubCarRelationGYX=15.00&CurrencySubCarRelationGYX=CNY&CurrencyNameSubCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateSubCarRelationGYX=&AmountSubCarRelationGYX=30000.00&RateSubCarRelationGYX=0.5&UnitAmountSubCarRelationGYX=30000.0&QuantitySubCarRelationGYX=1.0&SumPremiumCarRelationGYX=199.00&SumPremiumShowCarRelationGYX=199.00+%D4%AA+%2C%C8%CB%C3%F1%B1%D2%B4%F3%D0%B4%D2%BC%B0%DB%BE%C1%CA%B0%BE%C1%D4%AA%D5%FB&SumAmountCarRelationGYX=2200000.00&StartDateCarRelationGYX=2017-06-24&StartHourCarRelationGYX=0&StartMinutesCarRelationGYX=0&EndDateCarRelationGYX=2018-06-23&EndHourCarRelationGYX=24&EndMinutesCarRelationGYX=00&riskFlagCarRelationGYX=GYX&riskCNameCarRelationGYX=%BD%BB%CD%A8%B9%A4%BE%DF%D2%E2%CD%E2%C9%CB%BA%A6%B1%A3%CF%D5&BenefRelateSerialNoCarRelationGYX=1&BenefSerialNoCarRelationGYX=&BenefInsured_FlagCarRelationGYX=&BenefInsuredTypeCarRelationGYX=1&BenefOccupationCodeCarRelationGYX=&BenefOccupationNameCarRelationGYX=&BenefOccupationFlagCarRelationGYX=&BenefInsuredNatureCarRelationGYX=3&BenefBusinessSourceCarRelationGYX=&BenefBusinessSourceNameCarRelationGYX=&BenefBusinessSortCarRelationGYX=&BenefBusinessSortNameCarRelationGYX=&BenefInsuredAddressCarRelationGYX=&BenefBankCarRelationGYX=&BenefAccountCarRelationGYX=&BenefLinkerNameCarRelationGYX=&BenefPostAddressCarRelationGYX=&BenefPostCodeCarRelationGYX=&BenefPhoneNumberCarRelationGYX=&BenefNAgeCarRelationGYX=&BenefNBirthdayCarRelationGYX=&BenefNHealthCarRelationGYX=&BenefNMarriageCarRelationGYX=1&BenefNUnitCarRelationGYX=&BenefNUnitPostCodeCarRelationGYX=&BenefNOccupationCodeCarRelationGYX=&BenefNOccupationNameCarRelationGYX=&BenefNLocalPoliceStationCarRelationGYX=&BenefNUnitCarRelationGYX=&BenefNRoomAddressCarRelationGYX=&BenefNRoomPostCodeCarRelationGYX=&BenefNRoomPhoneCarRelationGYX=&BenefALeaderNameCarRelationGYX=&BenefALeaderIDCarRelationGYX=&BenefAPhoneNumberCarRelationGYX=&BenefAPostCodeCarRelationGYX=&BenefABusinessCodeCarRelationGYX=&BenefARevenueRegistNoCarRelationGYX=&BenefPrpInsuredInsuredCodeCarRelationGYX=&BenefBenefitFlagCarRelationGYX=&BenefPrpInsuredInsuredNameCarRelationGYX=&BenefInsuredIdentityCarRelationGYX=01&BenefNSexCarRelationGYX=1&BenefIdentifyTypeCarRelationGYX=01&BenefIdentifyNumberCarRelationGYX=&BenefNBrithdayCarRelationGYX=&BenefBenefitRateCarRelationGYX=&button_BenefInsured_Insert2CarRelationGYX=%D1%A1+%D4%F1&Expenses_FlagCarRelationGYX=&RefreshFlagExpensesCarRelationGYX=&DisProportionFlagCarRelationGYX=&SelfRateCarRelationGYX=&MaxDisRateCarRelationGYX=&DisRateCarRelationGYX=&PremiumDisRateCarRelationGYX=0.00&ChangePremiumDisRateCarRelationGYX=&ExpensesFlag2CarRelationGYX=&MaxManageFeeRateCarRelationGYX=&ManageFeeRateCarRelationGYX=&DisProportionFlag1CarRelationGYX=&SelfRate1CarRelationGYX=&DisRate1CarRelationGYX=&PremiumDisRate1CarRelationGYX=&ChangePremiumDisRate1CarRelationGYX=&ChargeSerialNoCarRelationGYX=&ChargeRiskCodeCarRelationGYX=&ChargeFlagCarRelationGYX=&RefreshFlagChargeCarRelationGYX=&Currency2FeeGYX=CNY&ChargeCodeCarRelationGYX=&ChargeNameCarRelationGYX=&Currency2ChargeCarRelationGYX=CNY&CurrencyNameChargeCarRelationGYX=%C8%CB%C3%F1%B1%D2&ExchangeRateChargeCarRelationGYX=&BaseAmountCarRelationGYX=10000&ChargeRateCarRelationGYX=0.0000&PlanFee2ChargeCarRelationGYX=&BuzContractFirstClick=1&isFirst=0
EOF;
    }

    /**
     * 根据车型名称查询车辆列表
     *
     * @param string $standName 车型名称
     * @param string $vinNo vin_no码
     * @param int $page 页数
     * @return array|false
     */
    public function queryCarByModel($standName, $vinNo, $page = 1)
    {
        // 进口车型的处理 || 车型名称为空
        if ((!empty($vinNo) && strtoupper(substr($vinNo, 0, 1)) != 'L') || empty($standName)) {
            $standName = $this->getCarStandNameFormJY($vinNo);
        }

        if (empty($standName)) {
            return array();
        }
        $standName = preg_replace("/[\x80-\xff]/", '', $standName);
        $params = $this->defaultQueryCarParams();
        if (false === $params) {
            return false;
        }

        $params['StandardName'] = $standName;
        $params['PageNum'] = $page;
        $url = 'http://9.0.6.69:7001/prpall/common/pub/UIModelCodeQueryInputJY.jsp';
        $result = $this->postCheckLogin($url, $params);

        if (false === $result) {
            return false;
        }

        $item = $this->resolveCarList($result);

        if (!empty($item)) return $item;

        // 查询进口车时当用车型名称standName查询没有结果的时候，尝试使用将standName当作车型代码进行查询。
        // 这样做的原因是：国寿财的用户在查询进口车的时候用 standName 查询不到，会通过第三方软件查询出车型代码进行查询，所以这时standName 其实是车型代码。
        // 虽然针对进口车已经使用了中华联合查询 standName来优化，但这不能保证优化后的standName能完全查询出所有的进口车。
        return $this->queryCarInfoByModelCode($standName);
    }

    /**
     * 根据车型代码查询车辆列表
     *
     * @param string $modelCode 车型代码
     * @return array|false
     */
    public function queryCarInfoByModelCode($modelCode)
    {
        $params = $this->defaultQueryCarParams();
        if (false === $params) {
            return false;
        }

        $params['RBCode'] = $modelCode;
        $url = 'http://9.0.6.69:7001/prpall/common/pub/UIModelCodeQueryInputJY.jsp';
        $result = $this->postCheckLogin($url, $params);

        if (false === $result) {
            return false;
        }

        return $this->resolveCarList($result);
    }

    /**
     * 查询车辆列表参数数组
     *
     * @return array|false
     */
    public function defaultQueryCarParams()
    {
        $comCode = $this->getLoginComInfo('riskCode');

        if (false === $comCode) {
            return false;
        }
        return array(
            'BrandName' => '',
            'BrandNameTemp' => '',
            'ComCode' => $comCode,
            'CompanyName' => '',
            'FamilyName' => '',
            'FamilyNameTemp' => '',
            'ImportFlag' => '-1',
            'ImportFlagTemp' => '',
            'PageCount' => '1',
            'PageNum' => '1',
            'Personal' => '1',
            'RBCode' => '',
            'RBCodeTemp' => '',
            'RiskCode' => '0511',
            'SearchCode' => '',
            'SearchCodeTemp' => '',
            'SpuerWherePart' => '1=1 Order By BrandName,marketdate,PurchasePrice ',
            'StandardName' => '',
            'StandardNameTemp' => '',
            'strCompanyNameTemp' => '',
            'VehicleClass' => '-1',
            'VehicleClassTemp' => '',
            'VINCode' => '',
            'VINCodeTemp' => '',
        );
    }

    /**
     * 解析车辆查询列表页面
     *
     * @param $html
     * @return array
     */
    public function resolveCarList($html)
    {
        $item = array();
        $existTemp = array();
        if (preg_match_all('/submitForm\((.*?)\)[\s];/', $html, $match)) {
            foreach ($match[1] as $value) {
                $temp = md5($value);
                if (!in_array($temp, $existTemp)) { // 解析的结果中会有多条重复的记录。这样去重
                    $existTemp[] = $temp;
                    $value = str_replace("'", "", $value);
                    $item[] = explode(',', $value);
                }
            }
        }

        return $item;
    }

    /**
     * 使用vin_no从中华联合的精友车型库查询车险名称
     * @param string $vinNo
     * @return string
     */
    public function getCarStandNameFormJY($vinNo)
    {
        $url = 'http://114.251.1.161/zccx/search?regionCode=00000000&jyFlag=0&businessNature=A&operatorCode=0000000000' .
            '&returnUrl=http://carply.cic.cn/pcis/offerAcceptResult&vname=&searchVin=' . $vinNo
            . '&vinflag=1&validNo=653eb5cdac40e71bf0d954b2e0fe3eef';

        $response = file_get_contents($url);

        $pattern = '/\<td[\s]onclick=\"selectRadio\(1\)\"\>(.*?)\<\/td\>/s';
        if (preg_match_all($pattern, $response, $result)) {
            return trim(str_replace('&nbsp;', '', $result[1][0]));
        }
        return '';
    }

    /**
     * 获取GPIC系统中注册客户信息
     *
     * @param array $userInfo
     * @return bool | array
     */
    public function getClientInfo(array $userInfo)
    {
        $identify = $userInfo['identify'];

        $AgentCode = $this->array_get($this->loginInfo, 'agentCode');  // 代理人/经纪人/寿险机构:代码
//        $AgentName = $this->encodeString($this->array_get($this->loginInfo, 'agentName')); // 代理人/经纪人/寿险机构:名称
        $AgentName = ''; // 代理人/经纪人/寿险机构:名称
        $BirthDate = substr($identify, 6, 4) . '-' . substr($identify, 10, 2) . '-' . substr($identify, 12, 2);
        $InsuredAge = date('Y') - (int)substr($identify, 6, 4);

        $paramString = <<<EOF
validateGSFlag=false&MobileFlag=&CustomerFlag=true&CVR_IDCARD=false&mustVerfiyFlag=false&PrpslPoliFlag=false&CardCancelFlag=null&AgentCode=$AgentCode&AgentName=$AgentName&CurrentCustomer=null&SameToApplicant=on&SameToInsured=on&EditType=I&TelephoneRealyTypeFlag=null&CustomerTypeHidden=1&ChgFlagIdv=0&UPDATE_CUSTOMER_FLAG=&ACTIONTYPE=3&RegistID=&ElectronicPolicyUtiPower=false&CIElectronicPolicyUtiPower=false&ElectronicProposalUtiPower=false&InsuredQueryFlag=1&CustomerType=1&CAPITALPLAT=1&prpDcustomerIdvIdentifyTypeHidden=&prpDcustomerIdvIdentifyTypeori=&prpDcustomerIdvIdentifyType=01&CertificateName=%C9%ED%B7%DD%D6%A4&prpDcustomerIdvIdentifyNumber=$identify&checkFlag=&checkQueryCustomerFlag=0&prpDcustomerIdvCustomerCode=&prpDcustomerIdvCustomerCName=&prpDcustomerIdvCustomerCNameori=&prpDcustomerIdvCustomerEName=&prpDcustomerIdvCustomerENameori=&FXQIdvIdentifyStartDate=&FXQIdvIdentifyEndDate=&FXQIdvIdentifyStartDateori=&FXQIdvIdentifyEndDateori=&RelationFlagIdv=&NationalityCode=CHN&Nationality=%D6%D0%B9%FA&NationalityCodeori=&Nationalityori=&FXQIdvSex=1&FXQIdvSexori=&BirthDate=$BirthDate&BirthDateori=&InsuredAge=$InsuredAge&InsuredAgeori=&customerClassIdv=&NationCode=&NationName=&NationCodeori=&NationNameori=&FXQIdvOccupationCode=&FXQIdvOccupationName=&FXQIdvOccupationNameori=&FXQIdvOccupationCodeori=&AutoLevelIdv=&AutoLevelCodeIdv=&RiskIdIdv=&AutoruleCodeIdv=&RiskLevelIdv=3&RuleCodeIdv=&prpDcustomerIdvMobile=&prpDcustomerIdvMobileSelect=&prpDcustomerIdvMobileori=&prpDcustomerIdvPhoneNumber=&prpDcustomerIdvPhoneNumberSelect=&prpDcustomerIdvPhoneNumberori=&prpDcustomerIdvEmail=&prpDcustomerIdvPostCode=&prpDcustomerIdvPostCodeori=&prpDcustomerIdvLinkAddress=&prpDcustomerIdvLinkAddressori=&prpDcustomerIdvTelephoneRealyType=&prpDcustomerIdvTelephoneRealyTypeori=&prpDcustomerIdvAddressCName=&prpDcustomerIdvAddressCNameori=&prpDcustomerIdvAddressEName=&prpDcustomerIdvEmailori=&prpDcustomerIdvLowerViewFlagHidden=&prpDcustomerIdvLowerViewFlag=0&CustomerIdvID=&prpDcustomerIdvAccount=&prpDcustomerIdvAccountori=&AccountIdvName=&AccountIdvProperty=1&AccountIdvOwnerProperty=1&BankIdvName=&BankIdvCode=&prpDcustomerIdvBank=&prpDcustomerIdvBankName=&prpDcustomerIdvBankCapital=&strAccountIdv=&AccSizeIdv=&AccountIdvCurrency=CNY&prpDcustomerIdvBankProvince=&prpdpaymentaccountDtoProvinceCode=&prpDcustomerIdvBankCity=&prpdpaymentaccountDtoCityCode=&AccountProperty=0&AccountIdvRemark=&Flag=0&ChgFlagUnit=0&prpDcustomerUnitCustomerCode=&RelationFlagUnit=&RelationFlagUnitori=&CommonCustomerUnitCodeFlag=0&UnitFlag=0&prpDcustomerUnitCustomerCName=&prpDcustomerUnitCustomerCNameori=&prpDcustomerUnitOrganizeIdentifyType=07&prpDcustomerUnitOrganizeCode=&prpDcustomerUnitOrganizeCodeori=&OrganizeCodeStartDate=&OrganizeCodeStartDateori=&OrganizeCodeEndDate=&OrganizeCodeEndDateori=&prpDcustomerUnitCustomerEName=&prpDcustomerUnitCustomerENameori=&FXQUnitBusinessSourceCode=&FXQUnitBusinessSourceName=&FXQUnitBusinessSourceCodeori=&FXQUnitBusinessSourceNameori=&FXQUnitBusinessRange=&FXQUnitBusinessRangeori=&prpDcustomerUnitAddressEName=&prpDcustomerUnitAddressCName=&prpDcustomerUnitAddressCNameori=&UnitNationalityCode=CHN&UnitNationality=%D6%D0%B9%FA&UnitNationalityCodeori=&UnitNationalityori=&FXQOrganizationTypeCode=&FXQOrganizationTypeName=&FXQOrganizationTypeName1=&FXQOrganizationTypeCodeori=&OrganizationTypeCodeEnt=&FXQOrganizationTypeNameori=&OrganizationTypeCodeEntori=&BusiLicenseUnit=&BusiLicenseUnitori=&FXQUnitIdentifyStartDate=&FXQUnitIdentifyStartDateori=&FXQUnitIdentifyEndDate=&FXQUnitIdentifyEndDateori=&customerClassUnit=&AutoLevelUnit=&AutoLevelCodeUnit=&RiskIdUnit=&AutoruleCodeUnit=&RiskLevelUnit=3&RuleCodeUnit=&FXQUnitTaxRegisterNumber=&FXQUnitTaxRegisterNumberori=&BusiLicenseUnitori=&SQDBUnitTaxRegisterNoStartDate=&FXQUnitIdentifyStartDateori=&SQDBUnitTaxRegisterNoEndDate=&FXQUnitIdentifyEndDateori=&prpDcustomerUnitCustomerShortName=&prpDcustomerUnitCustomerShortNameori=&prpDcustomerUnitPostAddress=&prpDcustomerUnitPostAddressori=&prpDcustomerUnitPostCode=&prpDcustomerUnitPostCodeori=&prpDcustomerUnitLinkerName=&prpDcustomerUnitLinkerNameori=&prpDcustomerUnitLowerViewFlagHidden=&prpDcustomerUnitLowerViewFlag=0&FXQUnitPhoneNumber=&FXQUnitPhoneNumberSelect=&FXQUnitPhoneNumberori=&PhoneNumberUnit=&PhoneNumberUnitSelect=&PhoneNumberUnitori=&FXQUnitIdentifyType=&FXQUnitIdentifyName=&FXQUnitIdentifyStartDate1=&FXQUnitIdentifyEndDate1=&FaxNumberUnit=&FaxNumberUnitori=&EmailAddressUnit=&EmailAddressUnitori=&AccountUnitOwnerProperty=2&BankUnitName=&BankUnitCode=&prpDcustomerUnitBank=&prpDcustomerUnitBankName=&prpDcustomerUnitBankCapital=&strAccountUnit=&AccSizeUnit=&AccountUnitProperty=1&prpDcustomerUnitAccount=&AccountUnitName=&AccountUnitCurrency=CNY&prpDcustomerUnitBankProvince=&prpdpaymentaccountDtoProvinceCodeUnit=&prpDcustomerUnitBankCity=&prpdpaymentaccountDtoCityCodeUnit=&AccountUnitRemark=&FXQUnitShareHolderName=&FXQUnitShareHolderIdentifyType=&FXQUnitShareHolderIdentifyName=&FXQUnitShareHolderIdentifyNumber=&FXQUnitShareHolderIdentifyStartDate=&FXQUnitShareHolderIdentifyEndDate=&FXQUnitLeaderName=&FXQUnitLeaderIdentifyType=&FXQUnitLeaderIdentifyNumber=&FXQUnitLeaderIdentifyStartDate=&FXQUnitLeaderIdentifyEndDate=&FXQUnitPrincipalName=&FXQUnitPrincipalIdentifyType=&FXQUnitPrincipalIdentifyNumber=&FXQUnitPrincipalIdentifyStartDate=&FXQUnitPrincipalIdentifyEndDate=&RelationDeal=Y&RelationOrganizeCode=&OrganizeCodeTemp=&RelationCustomerCode=&RelationCustomerCodeTemp=&RelationCustomerCName=&CustomerCNameTemp=&UseImagePlat=true
EOF;

        $response = $this->postCheckLogin('http://9.0.6.69:7001/prpall/common/ecif/UICustomerEcifCar.jsp?TelephoneRealyTypeFlag=null&CUSTOMERACTIONTYPE=undefined', $paramString);

        // 查询到多个客户资料
        if (strpos($response, 'UICustomerEcifList')) {
            $list = $this->postCheckLogin('http://9.0.6.69:7001/prpall/common/ecif/UICustomerEcifList.jsp?CustomerTypeHidden=1');
            // 匹配第一个客户的ID
            if (preg_match('/queryCustoemer\(\'(\d{12}?)/', $list, $result)) {
                $id = $result[1];
            } else {
                $this->error['errorMsg'] = '客户信息ID匹配失败';
                return false;
            }
            // 组装参数重新查询
            $paramString = str_replace('prpDcustomerIdvCustomerCode=&', 'prpDcustomerIdvCustomerCode='.$id.'&', $paramString);
            $response = $this->postCheckLogin('http://9.0.6.69:7001/prpall/common/ecif/UICustomerEcifCar.jsp?TelephoneRealyTypeFlag=null&CUSTOMERACTIONTYPE=undefined', $paramString);
        } else if (strpos($response, '未查询到客户信息')) {
            // 新增客户信息
            if ($this->registUser($userInfo)) {
                $response = $this->postCheckLogin('http://9.0.6.69:7001/prpall/common/ecif/UICustomerEcifCar.jsp?TelephoneRealyTypeFlag=null&CUSTOMERACTIONTYPE=undefined', $paramString);
            } else {
                return false;
            }
        }

        $pattern = '/mfm\.(.*?)\.value[\s]+=?[\s]+\"(.*?)\"\;/';
        if (preg_match_all($pattern, $response, $result)) {
            return array_combine(array_values($result[1]), array_values($result[2]));
        } else {
            $this->error['errorMsg'] = '客户信息查询失败！请确认客户身份证号码是否正确或者更换身份证号码！';
            return false;
        }
    }

    /**
     * 新增用户
     *
     * @param array $userInfo
     * @return bool
     */
    public function registUser(array $userInfo)
    {
        $AgentCode = $this->array_get($this->loginInfo, 'agentCode');  // 代理人/经纪人/寿险机构:代码
        $AgentName = $this->encodeString($this->array_get($this->loginInfo, 'agentName')); // 代理人/经纪人/寿险机构:名称
        $identify = $userInfo['identify'];
        $BirthDate = substr($identify, 6, 4) . '-' . substr($identify, 10, 2) . '-' . substr($identify, 12, 2);
        $prpDcustomerIdvIdentifyNumber = $identify; // 身份证
        $prpDcustomerIdvCustomerCName = $this->encodeString($userInfo['name']); // 客户名称
        $phone = $userInfo['phone'];
        if (strlen($phone) == 12) {
            $phone = substr($phone, 1);
        }
        $prpDcustomerIdvMobile = $phone; // 手机号码
        $prpDcustomerIdvLinkAddress = $this->encodeString($userInfo['address']); // 联系地址
        $prpDcustomerIdvAddressCName = $prpDcustomerIdvLinkAddress;
        // 倒数第二位 奇数为男性，偶数为女性
        $FXQIdvSex = substr($prpDcustomerIdvIdentifyNumber, -2, 1) % 2 == 0 ? '2' : '1'; // 性别 1男 2女
        $InsuredAge = date('Y') - (int)substr($identify, 6, 4);

        $paramString = <<<EOF
validateGSFlag=false&MobileFlag=&CustomerFlag=true&CVR_IDCARD=false&mustVerfiyFlag=false&PrpslPoliFlag=false&CardCancelFlag=null&AgentCode=$AgentCode&AgentName=$AgentName&CurrentCustomer=null&SameToApplicant=on&SameToInsured=on&EditType=I&TelephoneRealyTypeFlag=null&CustomerTypeHidden=1&ChgFlagIdv=1&UPDATE_CUSTOMER_FLAG=&ACTIONTYPE=1&RegistID=&ElectronicPolicyUtiPower=false&CIElectronicPolicyUtiPower=false&ElectronicProposalUtiPower=false&InsuredQueryFlag=1&CustomerType=1&CAPITALPLAT=1&prpDcustomerIdvIdentifyTypeHidden=&prpDcustomerIdvIdentifyTypeori=&prpDcustomerIdvIdentifyType=01&CertificateName=%C9%ED%B7%DD%D6%A4&prpDcustomerIdvIdentifyNumber=$prpDcustomerIdvIdentifyNumber&checkFlag=&checkQueryCustomerFlag=0&prpDcustomerIdvCustomerCode=&prpDcustomerIdvCustomerCName=$prpDcustomerIdvCustomerCName&prpDcustomerIdvCustomerCNameori=&prpDcustomerIdvCustomerEName=&prpDcustomerIdvCustomerENameori=&FXQIdvIdentifyStartDate=&FXQIdvIdentifyEndDate=&FXQIdvIdentifyStartDateori=&FXQIdvIdentifyEndDateori=&RelationFlagIdv=&NationalityCode=CHN&Nationality=%D6%D0%B9%FA&NationalityCodeori=&Nationalityori=&FXQIdvSex=$FXQIdvSex&FXQIdvSexori=&BirthDate=$BirthDate&BirthDateori=&InsuredAge=$InsuredAge&InsuredAgeori=&customerClassIdv=&NationCode=&NationName=&NationCodeori=&NationNameori=&FXQIdvOccupationCode=&FXQIdvOccupationName=&FXQIdvOccupationNameori=&FXQIdvOccupationCodeori=&AutoLevelIdv=&AutoLevelCodeIdv=&RiskIdIdv=&AutoruleCodeIdv=&RiskLevelIdv=3&RuleCodeIdv=&prpDcustomerIdvMobile=$prpDcustomerIdvMobile&prpDcustomerIdvMobileori=&prpDcustomerIdvPhoneNumber=&prpDcustomerIdvPhoneNumberSelect=&prpDcustomerIdvPhoneNumberori=&prpDcustomerIdvEmail=&prpDcustomerIdvPostCode=&prpDcustomerIdvPostCodeori=&prpDcustomerIdvLinkAddress=$prpDcustomerIdvLinkAddress&prpDcustomerIdvLinkAddressori=&prpDcustomerIdvTelephoneRealyType=&prpDcustomerIdvTelephoneRealyTypeori=&prpDcustomerIdvAddressCName=$prpDcustomerIdvAddressCName&prpDcustomerIdvAddressCNameori=&prpDcustomerIdvAddressEName=&prpDcustomerIdvEmailori=&prpDcustomerIdvLowerViewFlagHidden=&prpDcustomerIdvLowerViewFlag=0&CustomerIdvID=&prpDcustomerIdvAccount=&prpDcustomerIdvAccountori=&AccountIdvName=&AccountIdvProperty=1&AccountIdvOwnerProperty=1&BankIdvName=&BankIdvCode=&prpDcustomerIdvBank=&prpDcustomerIdvBankName=&prpDcustomerIdvBankCapital=&strAccountIdv=&AccSizeIdv=&AccountIdvCurrency=CNY&prpDcustomerIdvBankProvince=&prpdpaymentaccountDtoProvinceCode=&prpDcustomerIdvBankCity=&prpdpaymentaccountDtoCityCode=&AccountProperty=0&AccountIdvRemark=&Flag=1&ChgFlagUnit=0&prpDcustomerUnitCustomerCode=&RelationFlagUnit=&RelationFlagUnitori=&CommonCustomerUnitCodeFlag=0&UnitFlag=0&prpDcustomerUnitCustomerCName=&prpDcustomerUnitCustomerCNameori=&prpDcustomerUnitOrganizeIdentifyType=07&prpDcustomerUnitOrganizeCode=&prpDcustomerUnitOrganizeCodeori=&OrganizeCodeStartDate=&OrganizeCodeStartDateori=&OrganizeCodeEndDate=&OrganizeCodeEndDateori=&prpDcustomerUnitCustomerEName=&prpDcustomerUnitCustomerENameori=&FXQUnitBusinessSourceCode=&FXQUnitBusinessSourceName=&FXQUnitBusinessSourceCodeori=&FXQUnitBusinessSourceNameori=&FXQUnitBusinessRange=&FXQUnitBusinessRangeori=&prpDcustomerUnitAddressEName=&prpDcustomerUnitAddressCName=&prpDcustomerUnitAddressCNameori=&UnitNationalityCode=CHN&UnitNationality=%D6%D0%B9%FA&UnitNationalityCodeori=&UnitNationalityori=&FXQOrganizationTypeCode=&FXQOrganizationTypeName=&FXQOrganizationTypeName1=&FXQOrganizationTypeCodeori=&OrganizationTypeCodeEnt=&FXQOrganizationTypeNameori=&OrganizationTypeCodeEntori=&BusiLicenseUnit=&BusiLicenseUnitori=&FXQUnitIdentifyStartDate=&FXQUnitIdentifyStartDateori=&FXQUnitIdentifyEndDate=&FXQUnitIdentifyEndDateori=&customerClassUnit=&AutoLevelUnit=&AutoLevelCodeUnit=&RiskIdUnit=&AutoruleCodeUnit=&RiskLevelUnit=3&RuleCodeUnit=&FXQUnitTaxRegisterNumber=&FXQUnitTaxRegisterNumberori=&BusiLicenseUnitori=&SQDBUnitTaxRegisterNoStartDate=&FXQUnitIdentifyStartDateori=&SQDBUnitTaxRegisterNoEndDate=&FXQUnitIdentifyEndDateori=&prpDcustomerUnitCustomerShortName=&prpDcustomerUnitCustomerShortNameori=&prpDcustomerUnitPostAddress=&prpDcustomerUnitPostAddressori=&prpDcustomerUnitPostCode=&prpDcustomerUnitPostCodeori=&prpDcustomerUnitLinkerName=&prpDcustomerUnitLinkerNameori=&prpDcustomerUnitLowerViewFlagHidden=&prpDcustomerUnitLowerViewFlag=0&FXQUnitPhoneNumber=&FXQUnitPhoneNumberSelect=&FXQUnitPhoneNumberori=&PhoneNumberUnit=&PhoneNumberUnitSelect=&PhoneNumberUnitori=&FXQUnitIdentifyType=&FXQUnitIdentifyName=&FXQUnitIdentifyStartDate1=&FXQUnitIdentifyEndDate1=&FaxNumberUnit=&FaxNumberUnitori=&EmailAddressUnit=&EmailAddressUnitori=&AccountUnitOwnerProperty=2&BankUnitName=&BankUnitCode=&prpDcustomerUnitBank=&prpDcustomerUnitBankName=&prpDcustomerUnitBankCapital=&strAccountUnit=&AccSizeUnit=&AccountUnitProperty=1&prpDcustomerUnitAccount=&AccountUnitName=&AccountUnitCurrency=CNY&prpDcustomerUnitBankProvince=&prpdpaymentaccountDtoProvinceCodeUnit=&prpDcustomerUnitBankCity=&prpdpaymentaccountDtoCityCodeUnit=&AccountUnitRemark=&FXQUnitShareHolderName=&FXQUnitShareHolderIdentifyType=&FXQUnitShareHolderIdentifyName=&FXQUnitShareHolderIdentifyNumber=&FXQUnitShareHolderIdentifyStartDate=&FXQUnitShareHolderIdentifyEndDate=&FXQUnitLeaderName=&FXQUnitLeaderIdentifyType=&FXQUnitLeaderIdentifyNumber=&FXQUnitLeaderIdentifyStartDate=&FXQUnitLeaderIdentifyEndDate=&FXQUnitPrincipalName=&FXQUnitPrincipalIdentifyType=&FXQUnitPrincipalIdentifyNumber=&FXQUnitPrincipalIdentifyStartDate=&FXQUnitPrincipalIdentifyEndDate=&RelationDeal=Y&RelationOrganizeCode=&OrganizeCodeTemp=&RelationCustomerCode=&RelationCustomerCodeTemp=&RelationCustomerCName=&CustomerCNameTemp=&UseImagePlat=true
EOF;
        $response = $this->postCheckLogin('http://9.0.6.69:7001/prpall/common/ecif/UICustomerEcif.jsp', $paramString);
        if (strpos($response, '新建客户信息成功')) {
            return true;
        } else {
            $this->error['errorMsg'] = '新建客户信息失败';
            // 获取所有错误提示信息
            $pattern = '/alert\([\'\"](.*?)[\'\"]\)\;/s';
            if (preg_match_all($pattern, $response, $result2)) {
                $this->error['errorMsg'] .= ': ' . implode('-', $result2[1]);
            }
            return false;
        }
    }

    /**
     * 获取代理协议号
     *
     * @return string
     */
    public function getAgreementNo()
    {
        /*
         http://9.0.6.69:7001/prpall/common/tbcbpg/UIPrPoEnGetAgreement.jsp?ChannelType=09&BusinessNature=2&ComCode=5100979991&AgentCode=56762518-3-M&&RiskCode=0511&AgentDays=870.00&Type=GET
         <?xml version="1.0" encoding="GBK"?><root>[1,56762518-3-M-01,36.0,246.00,2018-02-28,-543.00,1,56762518-3-M,四川裕承保险代理有限责任公司,870.00,,,N,N,56762518-3,四川裕承保险代理有限责任公司,0,执行固定手续费率,,,]</root>

         http://9.0.6.69:7001/prpall/common/tbcbpg/UIPrPoEnGetAgreement.jsp?ChannelType=09&BusinessNature=2&ComCode=5100979991&AgentCode=56762518-3-M&strIs0507=Y&AgentDays=870.00&Type=GET
        <?xml version="1.0" encoding="GBK"?><root>[1,56762518-3-M-01,4.0,246.00,2018-02-28,-543.00,1,56762518-3-M,四川裕承保险代理有限责任公司,870.00,,,N,N,56762518-3,四川裕承保险代理有限责任公司,0,执行固定手续费率,,,]</root>
        */
//        $AgreementNo = '56762518-3-M-01'; // 代理协议号

        return $this->array_get($this->loginInfo, 'agentCode') . '-01';
    }

    /**
     * 获取GroupType
     *
     * @param string $code
     * @return mixed
     */
    public function getGroupType($code)
    {
        $response = $this->postCheckLogin('http://9.0.6.69:7001/prpall/commonship/pub/UIGetGroupType.jsp?comCode=' . $code);

        $pattern = '/root>([\d]*?)<\/root/';
        if (preg_match($pattern, $response, $result)) {
            return $result[1];
        } else {
            $this->error['errorMsg'] = '获取GroupType参数失败';
            return false;
        }
    }

    /**
     * 获取业务员/产险专员，默认返回第一个业务员
     *
     * @return bool | array
     */
    public function getHander()
    {
        $setHandlerCode = $this->array_get($this->loginInfo, 'handlerCode');
        if (!empty($setHandlerCode)) {
            return array(
                'code' => $setHandlerCode,
                'name' => '',
            );
        }
        $fieldExt = $this->getLoginComInfo('comCode');
        if (false === $fieldExt) {
            return false;
        }

        $response = $this->postCheckLogin('http://9.0.6.69:7001/prpall/common/pub/UICodeGetNew.jsp', array(
            'querytype' => 'always',
            'codemethod' => 'select',
            'codetype' => 'Handler2Code',
            'coderelation' => '1,-11,-11,2,3,4,5',
            'codelimit' => 'clear',
            'codeclass' => 'codecode',
            'codevalue' => '',
            'codeindex' => '273',
            'riskcode' => '0511',
            'Handler1Code' => '',
            'language' => 'C',
            'codeother' => '',
            'fieldext' => $fieldExt,
            'ComCode' => '',
            'AgentCode' => '',
            'subriskcode' => '',
            'fieldsign' => '',
            'groupnature' => '',
            'unionfactor' => '',
            'grouptype' => '',
            'userType' => '',
            'BrandName' => '',
            'ChannelType' => '09',
            'CarKindCode' => '',
            'protocolType' => '',
            'ProtocolType' => '',
            'codeselect' => '',
            'SelectIt' => '',
        ));

        $select = $this->resolveSelect($response);
        $handerCode = $this->array_get($this->config, 'handerCode');
        foreach ($select as $item) {
            list($code, $name, $com, $comName, , , ,) = explode('_FIELD_SEPARATOR_', $item);
            if (!empty($handerCode)) { // 指定了业务员
                if ($code == $handerCode) {
                    return array(
                        'code' => $code,
                        'name' => $name,
                        'com' => $com,
                        'comName' => $comName,
                    );
                }
            } else { // 没有指定业务员则返回第一个
                return array(
                    'code' => $code,
                    'name' => $name,
                    'com' => $com,
                    'comName' => $comName,
                );
            }
        }

        $this->error['errorMsg'] = '业务员数据获取失败';
        return false;
    }

    /**
     * 登录前获取机构代码
     *
     * @return bool
     */
    public function getComCode()
    {
        $url = 'http://9.0.6.69:7001/prpall/processCodeInput.do?actionType=query';
        $response = $this->post($url, array(
            'callBackMethod' => '',
            'codeMethod' => 'select',
            'codeRelation' => '0',
            'codeType' => 'comCodeByUserCode',
            'fieldIndex' => '5',
            'fieldValue' => '',
            'getDataMethod' => '',
            'isClear' => 'Y',
            'otherCondition' => 'userCode=' . $this->loginInfo['username'],
        ));

        if (empty($response)) {
            $this->error['errorMsg'] = '请求失败，请检查网络';
            return false;
        }

        $select = $this->resolveSelect($response);
        if (preg_match('/\d+/', $select[0], $codeResult) && isset($codeResult[0])) {
            return $codeResult[0];
        }
        $this->error['errorMsg'] = '获取机构代码失败';
        return false;
    }

    /**
     * 获取登录机构信息
     *
     * @param string $key code,机构代码，name机构名称，riskCode另一个代码
     * @return bool | array
     */
    public function getLoginComInfo($key = '')
    {
        if (!empty($this->comInfo)) {
            $comInfo = $this->comInfo;
        } else {
            $fileName = $this->codeCachePath . $this->loginInfo['username'] . '_comInfo';
            if (!file_exists($fileName) && !$this->login()) {
                return false;
            }
            $comInfo = unserialize(file_get_contents($fileName));
        }

        if (!empty($key)) {
            if (in_array($key, array('comCode', 'loginComCode', 'riskCode'))) {
                return $this->array_get($comInfo, $key);
            } else {
                $this->error['errorMsg'] = '机构信息获取失败 - 指定key错误';
                return false;
            }
        }

        $comCode = $this->array_get($comInfo, 'comCode', false);
        $loginComCode = $this->array_get($comInfo, 'loginComCode', false);
        $riskCode = $this->array_get($comInfo, 'riskCode', false);

        if (!$comCode || !$loginComCode || !$riskCode) {
            $this->error['errorMsg'] = '机构信息获取失败';
            return false;
        }

        return array(
            'code' => $comCode,
            'name' => $loginComCode,
            'riskCode' => $riskCode,
        );
    }

    /**
     * 判断是否登录
     *
     * @return bool
     */
    public function isLogin()
    {
        if (!file_exists($this->cookieFile)) {
            $this->error['errorMsg'] = 'cookie文件不存在';
            return false;
        }
        $cookie = file_get_contents($this->cookieFile);
        if (empty($cookie)) {
            $this->error['errorMsg'] = 'cookie内容获取失败';
            return false;
        }

        $this->get('http://9.0.6.69:7001/prpall/common/pub/UICodeGet.jsp'); // 登录返回200状态，未登录返回500
        return $this->_info['http_code'] == 200;
    }

    /**
     * 执行登录
     *
     * @return bool
     */
    public function login()
    {
        $comCode = $this->getComCode();
        if (!$comCode) {
            return false;
        }

        $userCode = $this->array_get($this->loginInfo, 'username');
        $password = $this->array_get($this->loginInfo, 'password');

        $loginParam = array(
            'sessionUserCode' => '',  //        sessionUserCode=
            'sessionUserName' => '',  //         sessionUserName
            'sessionComCode' => '',   //        sessionComCode=
            'UserCode' => $userCode,        // UserCode
            'Password' => $password,        // Password
            'ComCode' => $comCode,      // ComCode
            'RiskCode' => '0511',     // RiskCode
            'ClassCode' => '',          // ClassCode
            'ClassCodeSelect' => '05',    // ClassCodeSelect
            'RiskCodeSelect' => '0511',       // RiskCodeSelect
            'USE0509COM' => ',12,',         // USE0509COM
            'CILIFESPECIALCITY' => ',2102,3302,3502,3702,4402,', //CILIFESPECIALCITY
            'image.x' => 117,
            'image.y' => 28, // <input type='image'> 提交时鼠标的坐标
        );

        $loginUrl = 'http://9.0.6.69:7001/prpall/UICentralControl?SelfPage=/common/pub/UILogonInput.jsp';

        $response = $this->request(array(
            CURLOPT_URL => $loginUrl,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $this->iconvArrayUTF8ToGBK($loginParam),
            CURLOPT_FOLLOWLOCATION => 1, // 自动重定向
            CURLOPT_HTTPHEADER => $this->makeHeaders(array())
        ));

        $html = $this->iconvStrGBKToUTF8($response);

        if (strpos($this->_info['url'], 'UIErrorPage')) {
            $this->error['errorMsg'] = '登录失败';
            $this->saveCache('GPIC_loginFail.html', $html);
            $this->saveCache('GPIC_loginFail.info', $this->_info);

            // 获取错误提示信息
            $pattern = '/\<pre\>.*?Exception\:(.*?)at/s';
            if (preg_match($pattern, $html, $result)) {
                $this->error['errorMsg'] .= ' : ' . $result[1];
            }
            return false;
        } else {
            $this->saveLoginComInfo($html);
        }
        return true;
    }

    /**
     * 登陆后获取当前账号的riskCode、ComCode、LoginComCode 机构信息
     *
     * @param $html
     */
    public function saveLoginComInfo($html = '')
    {
        if (empty($html)) {
            return;
        }
        // 获取当前账号的riskCode、ComCode、LoginComCode 机构信息
        $riskCode = $loginComCode = $comCode = '';
        if (preg_match('/riskCodeFilter\(\"(.*?)\"/', $html, $match1)) {
            $riskCode = $match1[1];
        }
        if (preg_match('/setLoginComCodeOption\(\'ComCodeSelectOne\'\,\"(.*?)\"/', $html, $match2)) {
            if (!empty($match2[1])) {
                $temp = explode('_FIELD_SEPARATOR_', $match2[1]);
                $loginComCode = array_pop($temp);
            }
        }
        if (preg_match('/setOptionComCodeSelectOne\(\"(.*?)\"/', $html, $match3)) {
            $comCode = $match3[1];
        }
        $saveContent = array(
            'riskCode' => $riskCode,
            'loginComCode' => $loginComCode,
            'comCode' => $comCode,
        );

        $this->saveCache($this->loginInfo['username'] . '_comInfo', $saveContent);
    }

    /**
     * 使用curl发起一个请求
     *
     * @param $options array  curl配置数组
     * @return mixed
     */
    private function request($options)
    {
        $curl = curl_init();
        curl_setopt_array($curl, $this->setOptions($options));

        $response = curl_exec($curl);
        $this->_info = curl_getinfo($curl);
        if (curl_errno($curl)) {
            $this->error['errorMsg'] = curl_error($curl);
        }
        curl_close($curl);

        return $response;
    }

    /**
     * 配置curl
     *
     * @param array $options
     * @return array
     */
    private function setOptions($options = array())
    {
        $options[CURLOPT_COOKIEJAR] = $this->cookieFile;        // 设置cookie存放文件
        $options[CURLOPT_COOKIEFILE] = $this->cookieFile;       // 设置cookie读取文件

        return $this->curlDefaultOptions + $options;
    }

    /**
     * 发送GET的请求
     *
     * @param $url string
     * @param $queryFiled array
     * @param $headers array
     * @return mixed
     */
    private function get($url, $queryFiled = array(), $headers = array())
    {
        return $this->request(array(
            CURLOPT_URL => $this->makeUrl($url, $queryFiled),
            CURLOPT_HTTPHEADER => array_merge($headers, $this->curlDefaultHeaders)
        ));
    }

    /**
     * 发送Post的请求
     *
     * @param $url
     * @param array|string $postFiled
     * @param array $headers
     * @return string
     */
    private function post($url, $postFiled, $headers = array())
    {
        // 提交的数据要是ＧＢＫ编码的，将设置的数组编码转换为ＧＢＫ
        $postString = is_array($postFiled) ? $this->iconvArrayUTF8ToGBK($postFiled) : $postFiled;

        $headers['Content-Length'] = strlen($postString);
        $result = $this->request(array(
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $postString,
                CURLOPT_HTTPHEADER => $this->makeHeaders($headers)
            )
        );

        return $this->iconvStrGBKToUTF8($result); // 返回的数据是GBK编码的需要转码
    }

    /**
     * 将数组中每一项从UTF8编码转换为GBK
     *
     * @param $array
     * @return string
     */
    public function iconvArrayUTF8ToGBK($array)
    {
        return http_build_query(
            eval('return ' . iconv('UTF-8', 'GBK', var_export($array, true) . ';'))
        );
    }

    /**
     * 将字符串从GBK编码转换为UTF8
     *
     * @param $string
     * @return string
     */
    public function iconvStrGBKToUTF8($string)
    {
        return iconv("GBK", "UTF-8", $string);
    }

    /**
     * 发送get请求之前检查是否登录
     *
     * @param $url
     * @param array $postFiled
     * @param array $headers
     * @return string | false
     */
    private function getCheckLogin($url, $postFiled = array(), $headers = array())
    {
        if (!$this->isLogin() && !$this->login()) {
            return false;
        }
        return $this->get($url, $postFiled, $headers);
    }

    /**
     * 发送post请求之前检查是否登录
     *
     * @param $url
     * @param array $postFiled
     * @param array $headers
     * @return string | false
     */
    private function postCheckLogin($url, $postFiled = array(), $headers = array())
    {
        if (!$this->isLogin() && !$this->login()) {
            return false;
        }
        return $this->post($url, $postFiled, $headers);
    }

    /**
     * format headers
     *
     * @param array $headers
     * @return array
     */
    private function makeHeaders(array $headers = array())
    {
        $array = array_merge($this->curlDefaultHeaders, $headers);

        $heas = array();
        foreach ($array as $item => $value) {
            $heas[] = $item . ': ' . $value;
        }
        return $heas;
    }

    /**
     * 给url上添加GET请求参数
     *
     * @param $url string 要添加的url连接
     * @param $queryParams array 要添加的参数
     * @return string
     */
    private function makeUrl($url, $queryParams = array())
    {
        if (empty($queryParams)) {
            return $url;
        }
        $entry = parse_url($url);
        $query = http_build_query($queryParams);

        return isset($entry['query']) ? $url . '&' . $query : $url . '?' . $query;
    }

    /**
     * 判断是否为合法的身份证号码
     *
     * @param string $vStr
     * @return bool
     */
    private function isCreditNo($vStr)
    {
        if (empty($vStr)) {
            return false;
        }
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) {
            return false;
        }
        if (!in_array(substr($vStr, 0, 2), $vCity)) {
            return false;
        }

        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        // 身份证编码规范验证
        if ($vLength == 18) {
            $vSum = 0;
            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
            if ($vSum % 11 != 1) return false;
        }
        return true;
    }

    /**
     * 获取数组中的指定键的值
     *
     * @param $array array
     * @param $key string
     * @param $default string|null
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
     * 输出GBK 编码下的urlencode
     *
     * @param array $array
     * @return string
     */
    private function encodeArray(array $array)
    {
        $s = array();
        foreach ($array as $k => $v) {
            $v = $this->encodeString($v);
            $s[] = $k . '=' . $v;
        }
        return implode('&', $s);
    }

    /**
     * 输出GBK 编码下的urlencode
     *
     * @param string $string
     * @return string
     */
    private function encodeString($string)
    {
        return urlencode(iconv("UTF-8", "GBK", $string));
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
     * 解析html中的表单
     *
     * @param $content
     * @return array
     */
    public function resolveSelect($content)
    {
        $pattern = '/option[\s]+value=[\'\"](.*?)[\'\"]\>/';
        if (preg_match_all($pattern, $content, $result)) {
            if (!empty($result[1][0])) {
                return $result[1];
            } elseif (!empty($result[2][0])) {
                return $result[2];
            } elseif (!empty($result[3][0])) {
                return $result[3];
            } elseif (!empty($result[4][0])) {
                return $result[4];
            }
        }
        return array();
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
