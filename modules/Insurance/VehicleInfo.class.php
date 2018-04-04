<?
    class VehicleInfo{
        protected $user_agent,$url,$refrer,$port,$cookie_path,$cacert;
        function __construct(){
            $this->url         = "https://ipartner.yongcheng.com/ipartner/queryVehicleList.json";
            $this->refrer      = "https://ipartner.yongcheng.com/ipartner/queryVehicleListPage";
            $this->port        = 14443;
            //$this->cacert    = './cacert.pem';//dirname(__FILE__).'/cacert.pem';
            $this->user_agent  = $_SERVER['HTTP_USER_AGENT'];
            $this->cookie_path = $this->getCookieFile();
        }
        protected function getCookieFile(){
            $cookie_path = "";
            if(isset($_SESSION['COOKIE_PATH'])){
                $cookie_path = $_SESSION['COOKIE_PATH'];
            }else{
                $cookie_path = tempnam('./cache/cookie/', 'cookie');
                $_SESSION['COOKIE_PATH'] = $cookie_path;
            }
            return $cookie_path;
        }
        /**
         * [getDefOpt 设置curl默认选项]
         * @param  array  $post_data  [post提交内容]
         * @return [array]            [curl参数]
         */
        protected function getDefOpt($post_data = array()){
            return array(
                    CURLOPT_URL            => $this->url,
                    CURLOPT_REFERER        => $this->refrer,
                    CURLOPT_USERAGENT      => $this->user_agent,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    //CURLOPT_CAINFO         => $this->cacert,
                    CURLOPT_SSL_VERIFYHOST => FALSE,
                    CURLOPT_POST           => 1,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_PORT           => $this->port,
                    //CURLOPT_HEADER       => 1,
                    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_0,
                    CURLOPT_POSTFIELDS     => $post_data,
                    CURLOPT_COOKIEFILE     => $this->cookie_path,
                    CURLOPT_COOKIEJAR      => $this->cookie_path,
             );
        }
        /**
         * [createCURL 创建curl句柄]
         * @param  [array] $options   [curl参数]
         * @return [resource]         [curl句柄]
         */
        protected function createCURL($options){
            $curl = curl_init();
            curl_setopt_array($curl,$options);
            //$data = curl_exec($curl);
            return $curl;
        }
        /**
         * [closeCURL 关闭curl回话]
         * @param  [resource] $curl [curl句柄]
         */
        protected function closeCURL($curl){
            curl_close($curl);
        }
        /**
         * [json2Array json转array]
         * @param  [string] $json_data [json数据]
         * @return [array]             [array数组]
         */
        protected function json2Array($json_data){
            return json_decode($json_data,TRUE);
        }
        function resolveModel($model){
            preg_match_all("/[A-Za-z\d\ \- \_\.]+/", $model, $result);
            if(isset($result[0][0]))
                $model = $result[0][0];
            return $model;
        }
        function getPageNum($page,$totalPage){
            $prepage = $page - 1 < 1 ? 1 : $page - 1;
            $nextpage = $page + 1 > $totalPage ? $totalPage : $page + 1;
            return array("prePage" => $prepage,"nextPage" => $nextpage);
        }
        function go($post_data){
            $curl = $this->createCURL($this->getDefOpt($post_data));
            $data = curl_exec($curl);
            $this->closeCURL($curl);
            return $this->json2Array($data);
        }
    }