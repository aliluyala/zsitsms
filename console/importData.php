#!/usr/bin/php -q
<?php
    class MSG {
        //运行系统是否是Windows
        private $isWindows;
        //前景色偏移量
        private $_fore_color_offset = 10;
        //输出颜色配置
        private $_color = Array(
            "SUCCESS" => 32,
            "FAILURE" => 31,
            "WARNING" => 33,
            "NOTICE"  => 36,
            "INFO"    => 37,
        );
        public function __construct(){
            //检查系统是否为Windows
            $this->isWindows = $this->isWindows();
        }
        /**
         * [isWindows 判断是否是Windows系统]
         * @return boolean [Windows系统返回TRUE,否则返回FALSE]
         */
        public function isWindows(){
            return strtoupper(substr(PHP_OS,0,3)) == "WIN" ? TRUE : FALSE;
        }
        /**
         * [getStatusColor 获取颜色状态]
         * @param  String  $status  [状态]
         * @return String           [状态]
         */
        private function getStatusColor($status){
            $status = strtoupper($status);
            if(!array_key_exists($status, $this->_color))
                $status = "INFO";
            return $status;
        }
        /**
         * [__c 设置信息颜色]
         * @param  string $message [信息]
         * @param  string $status  [颜色]
         * @return string          [terminal带颜色的信息]
         */
        public function __c($message, $status = "INFO", $tag = TRUE, $fore_color = FALSE){
            if($this->isWindows) return $message;
            $status = $this->getStatusColor($status);
            $color_code = $fore_color ? $this->_color[$status] + $this->_fore_color_offset : $this->_color[$status];
            return $tag === TRUE ? chr(27) . "[0" . $color_code . "m" . "[{$status}] " . chr(27) . "[0m" . $message : chr(27) . "[0" . $color_code . "m" . $message . chr(27) . "[0m";
        }
        /**
         * [__l 输出信息到terminal]
         * @param  string  $message [信息]
         * @param  boolean $override    [是否覆盖显示]
         */
        public function __l($message, $status = "INFO", $tag = TRUE, $override = FALSE, $fore_color = FALSE){
            $message  = $this->__c($message, $status, $tag, $fore_color);
            $override = $override ? "\r" : "";
            fwrite(STDOUT, $override . $message);
        }
        /**
         * [__nl 换行]
         */
        public function __nl(){
            fwrite(STDOUT, "\n");
        }
    }
    class DataTransfer {
        //单列实例变量
        private static $_transfer;
        //数据库句柄
        private static $conn;
        //配置文件中数据库名称,目标数据库
        private static $TARGET_DB;
        //目标数据库编码
        private static $TARGET_DB_CHARSET = "UTF8";
        //数据库源
        private static $SOURCE_DB;
        //数据库源编码(默认编码:UTF8)
        private static $SOURCE_DB_CHARSET = "UTF8";
        //当前链接数据库
        private static $CURRENT_DB;
        //系统路径
        private static $APP_DIR;
        //配置文件路径
        private static $CONFIG_FILE;
        //配置文件内容
        private static $CONFIG = NULL;
        //latin1与utf8编码版本分界
        private static $_VERSION_LIMIT = "1.2.0";
        //小于版本分界编码
        private static $_LT_VERSION_CHARSET  = "LATIN1";
        //大于等于版本分界编码
        private static $_GTE_VERSION_CHARSET = "UTF8";
        //默认读取行数
        private static $defRow = 1000;
        //终端输出
        private static $MSG;
        //输出颜色配置
        private static $_color = Array(
            "SUCCESS" => 32,
            "FAILURE" => 31,
            "WARNING" => 33,
            "NOTICE"  => 36,
            "INFO"    => 37,
        );
        //将要转移的表 source table => target table
        private static $tables = Array(
            'park'                        => 'park'                          ,
            'park_seq'                    => 'park_seq'                      ,
            'account_appointment'         => 'account_appointment'           ,
            'account_appointment_seq'     => 'account_appointment_seq'       ,
            'account_track'               => 'account_track'                 ,
            'account_track_seq'           => 'account_track_seq'             ,
            'accounts'                    => 'accounts'                      ,
            'accounts_seq'                => 'accounts_seq'                  ,
            'daily_analysis'              => 'daily_analysis'                ,
            'daily_analysis_seq'          => 'daily_analysis_seq'            ,
            'filters'                     => 'filters'                       ,
            'groups'                      => 'groups'                        ,
            'groups_seq'                  => 'groups_seq'                    ,
            'homes'                       => 'homes'                         ,
            'homes_seq'                   => 'homes_seq'                     ,
            'insurance_account_cli'       => 'insurance_account_cli'         ,
            'insurance_account_cli_seq'   => 'insurance_account_cli_seq'     ,
            'list_data'                   => 'list_data'                     ,
            'list_data_seq'               => 'list_data_seq'                 ,
            'list_match'                  => 'list_match'                    ,
            'list_match_seq'              => 'list_match_seq'                ,
            'notices'                     => 'notices'                       ,
            'notices_seq'                 => 'notices_seq'                   ,
            'permission'                  => 'permission'                    ,
            'permission_actions'          => 'permission_actions'            ,
            'permission_fields'           => 'permission_fields'             ,
            'permission_modules'          => 'permission_modules'            ,
            'permission_seq'              => 'permission_seq'                ,
            'result_report'               => 'result_report'                 ,
            'result_report_seq'           => 'result_report_seq'             ,
            'sms_mas_status'              => 'sms_mas_status'                ,
            'sms_notify'                  => 'sms_notify'                    ,
            'sms_notify_seq'              => 'sms_notify_seq'                ,
            'users'                       => 'users'                         ,
            'users_seq'                   => 'users_seq'                     ,
            'zswitch_cc_account_evaluate' => 'zswitch_cc_account_evaluate'   ,
            'zswitch_cc_agent_cdr'        => 'zswitch_cc_agent_cdr'          ,
            'zswitch_cc_queue_cdr'        => 'zswitch_cc_queue_cdr'          ,
            'zswitch_cc_vipnumber'        => 'zswitch_cc_vipnumber'          ,
            //version:1.2.3版本表
            'policy_draft_com'            => 'policy_draft_com'              ,
            'policy_draft_com_seq'        => 'policy_draft_com_seq'          ,
            'policy_draft'                => 'policy_draft'                  ,
            'policy_draft_seq'            => 'policy_draft_seq'              ,
            'policy_calculate_setting'    => 'policy_calculate_setting'      ,
        );
        //source table表中有唯一字段
        private static $unique_table = Array(
            'id'           => Array(
                'park'                          ,
                'account_appointment'           ,
                'account_track'                 ,
                'accounts'                      ,
                'daily_analysis'                ,
                'filters'                       ,
                'groups'                        ,
                'homes'                         ,
                'insurance_account_cli'         ,
                'list_data'                     ,
                'list_match'                    ,
                'notices'                       ,
                'permission'                    ,
                'result_report'                 ,
                'sms_notify'                    ,
                'users'                         ,
                'zswitch_cc_account_evaluate'   ,
                'zswitch_cc_agent_cdr'          ,
                'zswitch_cc_queue_cdr'          ,
                'zswitch_cc_vipnumber'          ,
            ),
            //permissionid不是唯一字段
            /*'permissionid' => Array(
                'permission_modules'            ,
                'permission_fields'             ,
                'permission_actions'            ,
            ),*/
        );
        //非字符字段类型
        private static $notchar = array('int','float','tinyint','smallint','mediumint','bigint','double','decimal');

        private function __construct(){
            self::$MSG = new MSG();
        }
        private static function readyToTransfer(){
            //检查是否在命令行运行
            self::isTerminal() or die("<p style='color:red;'>This script can only run in the command line mode！</p>");
            //设置系统目录
            self::$APP_DIR     = dirname(__FILE__)."/..";
            //加载配置文件
            self::loadConfig();
            //链接数据库
            self::connectDB();
        }
        private static function startToTransfer($argv){
            if(!empty($argv[1])) {
                self::$SOURCE_DB = $argv[1];
            }else {
                self::$MSG->__l("Please input source database name:","NOTICE");
                self::$SOURCE_DB = self::getInput();
            }
            self::$SOURCE_DB != self::$TARGET_DB or die (self::$MSG->__l("Source database cannot be same as the target database! \n", "FAILURE"));
            self::setSourceDBCharset();
            self::transferTable();
        }
        /**
         * [loadConfig 载入配置文件]
         * @return [type] [description]
         */
        private static function loadConfig(){
            //设置配置文件目录
            self::$CONFIG_FILE = self::$APP_DIR . '/config/config.php';
            //检查配置文件是否存在
            if(is_file(self::$CONFIG_FILE)){
                self::$MSG->__l("Configuration files have been found! \n","SUCCESS");
            }else{
                die(self::$MSG->__l("Config file is not exists in: " . self::$CONFIG_FILE . " !\n","FAILURE"));
            }
            //载入配置文件
            self::$CONFIG      = require(self::$CONFIG_FILE);
            //检查配置文件完整性
            if(self::checkConfig()){
                self::$MSG->__l("Configuration is right. \n","SUCCESS");
            }else{
                die(self::$MSG->__l("Configuration is not correct!\nPlease check the configuration file: " . self::$CONFIG_FILE . " !\n","FAILURE"));
            }
        }
        /**
         * [getInput 返回用户输入内容]
         * @return [String] [用户输入内容]
         */
        private static function getInput(){
            return chop(fgets(STDIN));
        }
        /**
         * [connectDB 链接数据库]
         * @return [type] [description]
         */
        private static function connectDB(){
            $DBHost          = self::$CONFIG['DBServers']['master']['DBHost'    ];
            $DBPort          = self::$CONFIG['DBServers']['master']['DBPort'    ];
            $DBUserName      = self::$CONFIG['DBServers']['master']['DBUserName'];
            $DBPassword      = self::$CONFIG['DBServers']['master']['DBPassword'];
            self::$TARGET_DB = self::$CONFIG['DBServers']['master']['Database'  ];
            self::$conn      = mysql_connect("{$DBHost}:{$DBPort}",$DBUserName,$DBPassword) or die (self::$MSG->__l("Could not connect to {$DBHost}:{$DBPort}:".mysql_error()."\n","FAILURE"));
            self::getDBVersion(self::$TARGET_DB);
        }
        /**
         * [setCurrentDBCharset 设置当前链接数据库的编码]
         * @param String $charset [编码]
         */
        private static function setCurrentDBCharset($charset){
            mysql_query("SET NAMES {$charset}");
            //self::$MSG->__l("SET Database charset to: {$charset} \n");
        }
        /**
         * [isTarget 检查当前数据库是否是目标数据库]
         * @return boolean [是返回TRUE,否返回FALSE]
         */
        private static function isTarget(){
            return self::$CURRENT_DB === self::$TARGET_DB;
        }
        /**
         * [isTableExists 检查表是否存在]
         * @param  String  $table [表名]
         * @return boolean        [存在返回TRUE,不存在返回FALSE]
         */
        private static function isTableExists($table){
            $result = mysql_query("SHOW TABLES LIKE '{$table}';",self::$conn);
            if(mysql_num_rows($result) == 0){
                self::$MSG->__l("Table {$table} is not found in the " . self::$CURRENT_DB . " database \n","FAILURE");
                return FALSE;
            }
            return TRUE;
        }
        /**
         * [clearTable 清除指定表数据]
         * @param  String $table [表名]
         * @return boolean       [成功返回TRUE,失败返回FALSE]
         */
        private static function clearTable($table){
            //mysql_query("DELETE FROM {$table};");//DELETE FROM {table}无法清除high watermark,且运行速度慢于truncate   drop > truncate > delete
            self::$MSG->__l("Clear table '{$table}'  in the " . self::$CURRENT_DB . " database \n","NOTICE");
            return self::execRecordSet("TRUNCATE {$table};");
        }
        /**
         * [getRecordCount 获取表中数据量]
         * @param  String $table [表名]
         * @return Int           [数据条数]
         */
        private static function getRecordCount($table){
            $result = mysql_query("SELECT COUNT(*) FROM {$table};",self::$conn);
            if(!$result){
                self::$MSG->__l("Table '{$table}' is not found in the " . self::$CURRENT_DB . " database \n","FAILURE");
                return FALSE;
            }
            $row = mysql_fetch_row($result);
            if($row[0] == 0){
                self::$MSG->__l("Table '{$table}' is empty! Skip. \n","WARNING");
                return FALSE;
            }
            self::$MSG->__l("Table '{$table}' have records:{$row[0]} in " . self::$CURRENT_DB . "\n");
            return $row[0];
        }
        /**
         * [getRecords 通过limit获取表中数据(limit查询数据量大的情况下速度会越来越慢,在有id的情况下尽量使用getRecordsByMaxId函数)]
         * @param  String $table  [表名]
         * @param  Int    $offset [limit起始值]
         * @return Array          [数据数组]
         */
        private static function getRecords($table,$offset){
            $data = Array();
            $result = mysql_query("SELECT * FROM {$table} LIMIT {$offset}," . self::$defRow, self::$conn);
            while ($row = mysql_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }
        /**
         * [getRecordsByMaxId 通过id于limit获取表中数据]
         * @param  String $table     [表名]
         * @param  String $unique_id [唯一id字段]
         * @param  Int    $id        [id起始值]
         * @return Array             [数据数组]
         */
        private static function getRecordsByMaxId($table,$unique_id,$id){
            $data = Array();
            $result = mysql_query("SELECT * FROM {$table} WHERE {$unique_id} > {$id} ORDER BY {$unique_id} ASC LIMIT " . self::$defRow, self::$conn);
            while ($row = mysql_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }
        /**
         * [getRecordsSql 遍历数组构造sql语句]
         * @param  String $table     [表名]
         * @param  Array  $data      [数据]
         * @param  Array  $cols      [表中字段]
         * @return String            [sql语句]
         */
        private static function getRecordsSql($table,$data,$cols){
            $sqlcol = "";
            $sqlvalues = "";
            $count = 0;
            foreach ($data as $k => $row) {
                $sqlvalues .= "(";
                foreach ($row as $key => $value) {
                    if(array_key_exists($key,$cols))
                    {
                        if($count === 0) $sqlcol .= $key . ",";
                        $val = '';
                        if(in_array($cols[$key],self::$notchar))
                        {
                            $val = $value;
                            if(empty($val)) $val='null';
                        }
                        else
                        {
                            //对',",\,NULL进行转义
                            $value = get_magic_quotes_gpc() ? $value : addslashes ($value);
                            $val = "'{$value}'";
                        }
                        $sqlvalues .= $val . ",";
                    }
                }
                $count++;
                $sqlvalues = substr($sqlvalues, 0, -1) . "),";
            }
            $sqlcol = substr($sqlcol, 0, -1);
            $sqlvalues = substr($sqlvalues, 0, -1);
            $sql = "INSERT INTO {$table}({$sqlcol}) VALUES{$sqlvalues};";
            return $sql;
        }
        /**
         * [execRecordSet 执行sql语句,返回执行结果]
         * @param  String $sql [sql语句]
         * @return boolean     [执行结果]
         */
        private static function execRecordSet($sql){
            return mysql_query($sql,self::$conn);
        }
        /**
         * [getOffet 获取总页数]
         * @param  Int  $totalCount [数据总条数]
         * @return int              [总页数(向上取整)]
         */
        private static function getOffet($totalCount){
            return ceil($totalCount/self::$defRow);
        }
        /**
         * [getUniqueId 获取表唯一id字段]
         * @param  String  $source_table [表名]
         * @return String                [成功返回id字段,失败返回NULL]
         */
        private static function getUniqueId($source_table){
            $unique_id = NULL;
            foreach (self::$unique_table as $id => $table) {
                $unique_id = in_array($source_table, $table) ? $id : $unique_id;
            }
            return $unique_id;
        }
        /**
         * [transferTable 转移表数据]
         */
        private static function transferTable(){
            foreach (self::$tables as $srcTable => $trgtTable) {
                $recordCount = 0;
                if(self::isTableExists($srcTable) === FALSE) continue;
                $count = self::getRecordCount($srcTable);
                if($count === FALSE) continue;
                self::switchDB();
                if(self::isTableExists($trgtTable) === FALSE) continue;
                self::clearTable($trgtTable);
                $totalOffset = self::getOffet($count);
                $data = Array();
                $unique_id = self::getUniqueId($srcTable);

                for ($i=0; $i < $totalOffset; $i++) {
                    self::switchDB();
                    $t1 = microtime(true);
                    if(empty($unique_id)){
                        $data = self::getRecords($srcTable,$i * self::$defRow);
                    }else{
                        $max_id = isset($data[sizeof($data) - 1][$unique_id]) ? $data[sizeof($data) - 1][$unique_id] : 0;
                        $data = self::getRecordsByMaxId($srcTable,$unique_id,$max_id);
                    }
                    $t2 = microtime(true);
                    self::switchDB();
                    $t3 = microtime(true);
                    $recordCount += self::execRecordSet(self::getRecordsSql($srcTable,$data,self::getTableCols($srcTable))) ? mysql_affected_rows() : 0;
                    $t4 = microtime(true);
                    self::$MSG->__l("Import table {$trgtTable}:{$recordCount}". self::$MSG->__c("    QUERY: " . round($t2-$t1,3) . "    ", "NOTICE", FALSE) . self::$MSG->__c("    INSERT: " .round($t4-$t3,3), "NOTICE", FALSE),"SUCCESS",TRUE,TRUE);
                }
                self::$MSG->__nl();
                self::switchDB();
            }
            self::$MSG->__l("Import data complete! \n", "SUCCESS");
        }
        /**
         * [updateData 更新数据]
         */
        private static function updateData(){
            $affect = Array("dd_ac" => 0,"dd_re" => 0,"dd_te" => 0,"dd_hd" => 0,"ud_ac" => 0);
            self::$MSG->__l("initialization data,please wait... \n");
            $result = mysql_query("SELECT id,name FROM company;",self::$conn);
            self::switchDB();
            if(!$result) {
                self::$MSG->__l("Company table does not exists or have not data.Skip! \n", "WARNING");
            }else {
                self::$MSG->__l("Company have records: " . mysql_num_rows($result) . "\n");
                while ($row = mysql_fetch_assoc($result)) {
                    $sql = "INSERT INTO dropdown(module_name,field,save_value,show_value) ";
                    $sql .= "VALUES('accounts','company','{$row['name']}','{$row['name']}')";
                    $affect["dd_ac"] += self::execRecordSet($sql) ? mysql_affected_rows() : 0;
                    $sql = "INSERT INTO dropdown(module_name,field,save_value,show_value) ";
                    $sql .= "VALUES('Recycle','company','{$row['name']}','{$row['name']}')";
                    $affect["dd_re"] += self::execRecordSet($sql) ? mysql_affected_rows() : 0;
                    $sql = "INSERT INTO dropdown(module_name,field,save_value,show_value) ";
                    $sql .= "VALUES('Telemarketing','company','{$row['name']}','{$row['name']}')";
                    $affect["dd_te"] += self::execRecordSet($sql) ? mysql_affected_rows() : 0;
                    $sql = "INSERT INTO dropdown(module_name,field,save_value,show_value) ";
                    $sql .= "VALUES('HandOut','company','{$row['name']}','{$row['name']}')";
                    $affect["dd_hd"] += self::execRecordSet($sql) ? mysql_affected_rows() : 0;
                    $sql = "UPDATE accounts SET company='{$row['name']}' WHERE company='{$row['id']}';";
                    $affect["ud_ac"]   += self::execRecordSet($sql) ? mysql_affected_rows() : 0;
                    self::$MSG->__l("Dropdown: Accounts({$affect['dd_ac']})  Recycle({$affect['dd_re']})  Telemarketing({$affect['dd_te']})  HandOut({$affect['dd_hd']})        Update: Accounts({$affect['ud_ac']})", "SUCCESS", TRUE, TRUE);
                }
            }
            self::$MSG->__nl();
        }
        /**
         * [switchDB 切换数据库]
         * @param  String $switchDB [数据库名称]
         * @return [type]           [description]
         */
        private static function switchDB($switchDB = NULL,$charset = NULL){
            $isTarget = self::isTarget();
            $switchDB = empty($switchDB) ? ($isTarget ? self::$SOURCE_DB : self::$TARGET_DB) : $switchDB;
            $charset  = empty($charset)  ? ($isTarget ? self::$SOURCE_DB_CHARSET : self::$TARGET_DB_CHARSET) : $charset;
            if(mysql_select_db($switchDB)){
                self::$CURRENT_DB = $switchDB;
                //self::$MSG->__l("Connecting database to: {$switchDB} \n");
            }else{
                die (self::$MSG->__l("Database:{$switchDB} does not exist!\n","FAILURE"));
            }
            self::setCurrentDBCharset($charset);
        }
        /**
         * [getTableCols 获取表字段]
         * @param  String $table [表名]
         * @return Array         [字段内容]
         */
        private static function getTableCols($table)
        {
            $cols = array();
            $result = mysql_query("SHOW FULL COLUMNS FROM {$table};",self::$conn);
            while($row = mysql_fetch_assoc($result))
            {

                $type = preg_replace('/\(\.+\)/','',$row['Type']);
                $cols[$row['Field']] = $type;
            }
            return $cols;
        }
        /**
         * [versionCompare 源库版本与分界版本号比较]
         * @return boolean [小于分界版本返回TRUE,大于等于分界版本返回FALSE]
         */
        private static function versionCompare(){
            $source_db_version = self::getDBVersion(self::$SOURCE_DB);
            return version_compare($source_db_version, self::$_VERSION_LIMIT, '<');
        }
        /**
         * [getDBVersion 获取指定库版本]
         * @param  String $db [数据库名称]
         * @return String     [版本号]
         */
        private static function getDBVersion($db){
            self::switchDB($db);
            $sql = "SELECT * FROM app_version LIMIT 1";
            $rs = mysql_query($sql,self::$conn);
            $row = mysql_fetch_assoc($rs);
            self::$MSG->__l("Database {$db} app version is: " . self::$MSG->__c($row['current_version'], "SUCCESS", FALSE) . "\n");
            return $row['current_version'];
        }
        /**
         * [setSourceDBCharset 设置源库编码]
         */
        private static function setSourceDBCharset(){
            self::$SOURCE_DB_CHARSET = self::versionCompare() ? self::$_LT_VERSION_CHARSET : self::$_GTE_VERSION_CHARSET;
        }
        /**
         * [checkConfig 检查配置文件必要参数完整性]
         * @return boolean [完整返回TRUE,否则返回FALSE]
         */
        private static function checkConfig(){
            return !is_array(self::$CONFIG) || !array_key_exists('DBServers',self::$CONFIG) ||
                   !array_key_exists('master'    ,self::$CONFIG['DBServers']) ||
                   !array_key_exists('DBHost'    ,self::$CONFIG['DBServers']['master']) ||
                   !array_key_exists('DBPort'    ,self::$CONFIG['DBServers']['master']) ||
                   !array_key_exists('DBUserName',self::$CONFIG['DBServers']['master']) ||
                   !array_key_exists('DBPassword',self::$CONFIG['DBServers']['master']) ||
                   !array_key_exists('Database'  ,self::$CONFIG['DBServers']['master'])
                   ? FALSE : TRUE;
        }
        /**
         * [isTerminal 判断是否在终端运行]
         * @return boolean [是返回TRUE,否返回FALSE]
         */
        private static function isTerminal(){
            return PHP_SAPI != 'cli' ? FALSE : TRUE;
        }
        public static function getInstance(){
            if(!isset(self::$_transfer))
            {
                $c = __CLASS__;
                self::$_transfer=new $c;
            }
            return self::$_transfer;
        }
        public function go($argv){
            self::readyToTransfer();
            self::startToTransfer($argv);
            self::updateData();
            self::$MSG->__l("All done. \n", "SUCCESS");
        }
    }
    $focus = DataTransfer::getInstance();
    $focus->go($argv);