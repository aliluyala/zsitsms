<?php
//安装数据库
global $conn;
/*版本信息 */
$sql = "CREATE TABLE IF NOT EXISTS app_version(
	current_version    VARCHAR(20),
	product_name       VARCHAR(50)
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql = "insert into app_version(current_version,product_name) values('"._APP_VERSION."','"._APP_PRODUCT_NAME."')";
mysql_query($sql,$conn);

/*用户信息*/

$sql ="CREATE TABLE IF NOT EXISTS users(
	id                       INT NOT NULL,
	user_name                CHAR(20)  NOT NULL,
	user_password            CHAR(40)  NOT NULL DEFAULT '',
	is_admin                 ENUM('NO','YES')  NOT NULL DEFAULT 'NO',
	name                     CHAR(50)  DEFAULT '',
	description              TEXT      DEFAULT NULL,
    date_created             timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified            timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    modified_user_id         INT       DEFAULT -1,
    title                    char(50)  DEFAULT NULL,
    department               CHAR(50)  DEFAULT NULL,
	post                     CHAR(50)  DEFAULT NULL,
    phone_home               char(50)  DEFAULT NULL,
    phone_mobile             char(50)  DEFAULT NULL,
    phone_work               char(50)  DEFAULT NULL,
    phone_other              char(50)  DEFAULT NULL,
    phone_fax                char(50)  DEFAULT NULL,
	email                    char(50)  DEFAULT NULL,
	qq_number                char(50)  DEFAULT NULL,
	status                   ENUM('Active','Activing','Invalid') DEFAULT 'Active',
	agent_have               ENUM('NO','YES') DEFAULT 'NO',
	agent_number             char(50)  DEFAULT '',
	agent_login              ENUM('NO','YES') DEFAULT 'NO',
	agent_popup              ENUM('NO','YES') DEFAULT 'NO',
    agent_status             ENUM('ONLINE','OFFLINE') DEFAULT 'ONLINE',
	agent_queue              CHAR(50)  DEFAULT 'NONE',
	agent_workno             CHAR(50)  DEFAULT  '',
	accesskey                char(50)  DEFAULT NULL,
	address_street           char(150) DEFAULT NULL,
    address_city             char(100) DEFAULT NULL,
    address_state            char(100) DEFAULT NULL,
    address_country          char(25)  DEFAULT NULL,
    address_postalcode       char(9)   DEFAULT NULL,
    imagename                char(255) DEFAULT NULL,
	user_preferences         TEXT      DEFAULT NULL,
	birthday                 date ,
	groupid                  INT       DEFAULT -1,
	permissionid             INT       DEFAULT -1,
	guid                     CHAR(40)  DEFAULT '',
	session_id               char(100) DEFAULT '',
    activity_time            timestamp  NOT NULL DEFAULT '0000-00-00 00:00:00',
    client_address           char(50) DEFAULT '',	
	PRIMARY KEY(id),
	KEY(groupid),
	KEY(permissionid),
	KEY user_user_name_idx (user_name),
	KEY user_agent_number_idx (agent_number)
) ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);


$sql="CREATE TABLE IF NOT EXISTS users_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="insert into users_seq(id) values(2);";
mysql_query($sql,$conn);

/*用户组*/
$sql="CREATE TABLE IF NOT EXISTS groups(
	id                       INT    NOT NULL,
	name                     CHAR(50),
	description              TEXT      DEFAULT NULL,
	guid                     CHAR(32)  DEFAULT '',
	PRIMARY KEY(id)
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE IF NOT EXISTS groups_seq(
	id              INT DEFAULT 1
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="insert into groups_seq(id) values(1000000);";
mysql_query($sql,$conn);


/*权限策略*/
$sql="CREATE TABLE IF NOT EXISTS permission(
	id                     INT       NOT NULL,
	name                   CHAR(50)  NOT NULL,
	description            TEXT      DEFAULT NULL,
	PRIMARY KEY(id)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE IF NOT EXISTS permission_seq(
	id                    INT       DEFAULT 1
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="insert into permission_seq(id) values(1);";
mysql_query($sql,$conn);

/*字段权限*/
$sql="CREATE TABLE IF NOT EXISTS permission_fields(
	permissionid          INT       NOT NULL,
	module_name           CHAR(50)  NOT NULL,
	field_name            CHAR(50)  NOT NULL,
	is_show               ENUM('YES','NO') DEFAULT 'YES',
	is_modify             ENUM('YES','NO') DEFAULT 'YES',
	hidden_start          INT       DEFAULT NULL,
	hidden_end            INT       DEFAULT NULL,
	KEY (permissionid),
	KEY (module_name),
	KEY (field_name)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

/*模块权限*/
$sql="CREATE TABLE  IF NOT EXISTS permission_modules(
	permissionid          INT       NOT NULL,
	module_name           CHAR(50)  NOT NULL,
	is_allow              ENUM('YES','NO') DEFAULT 'YES',
	recordset_groups      TEXT      DEFAULT NULL,
	recordset_users       TEXT      DEFAULT NULL,
	KEY (permissionid),
	KEY (module_name)
)ENGINE= MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

/*模块方法权限*/
$sql="CREATE TABLE  IF NOT EXISTS permission_actions(
	permissionid          INT       NOT NULL,
	module_name           CHAR(50)  NOT NULL,
	action_name           CHAR(50)  NOT NULL,
	is_allow              ENUM('YES','NO') DEFAULT 'YES',
	KEY(permissionid),
	KEY(module_name),
	KEY(action_name)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

/*登录日志*/
$sql = "CREATE TABLE IF NOT EXISTS user_login_log(
	id                    INT       NOT NULL AUTO_INCREMENT,
	userid                INT       NOT NULL,
	user_name             CHAR(20)  NOT NULL,
	state                 ENUM('LOGIN','LOGOUT')  NOT NULL ,
	oper_time             TIMESTAMP NOT NULL ,
	ip_address            CHAR(30)  DEFAULT NULL,
	user_agent            CHAR(255) DEFAULT NULL,
	PRIMARY KEY(id),
    KEY(userid),
    KEY(user_name),
    KEY(oper_time)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

/*操作日志*/
$sql="CREATE TABLE user_operation_log(
	id                    INT       NOT NULL AUTO_INCREMENT,
	userid                INT       NOT NULL,
	user_name             CHAR(20)  NOT NULL,
	module_name           CHAR(50)  NOT NULL,
    action_name           CHAR(50)  NOT NULL,
	oper_time             TIMESTAMP NOT NULL,
	log_info              TEXT      DEFAULT NULL,
	PRIMARY KEY(id),
    KEY(userid),
    KEY(user_name),
    KEY(oper_time)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

/*菜单*/
$sql="CREATE TABLE menus(
	id                   INT        NOT NULL,
	name                 CHAR(50)   NOT NULL,
	title                CHAR(100)  DEFAULT NULL,
	seq                  INT        DEFAULT 0,
	action               ENUM('SUB_MENU','OPEN_MODULE','OPEN_WINDOWS') DEFAULT 'SUB_MENU',
	target               CHAR(100)  DEFAULT NULL,
	PRIMARY KEY(id),
	KEY(seq)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql ="INSERT INTO `menus` VALUES (2,'客户管理','客户管理',1,'SUB_MENU',NULL),(3,'销售管理','销售管理',2,'SUB_MENU',NULL),
(4,'统计报表','统计报表',3,'SUB_MENU',NULL),(5,'呼叫中心','呼叫中心',4,'SUB_MENU',NULL),
(6,'工作流','工作流',5,'SUB_MENU',NULL),(7,'工具','工具',6,'SUB_MENU',NULL),
(8,'设置','设置',7,'SUB_MENU',NULL),(10,'数据管理','数据管理',5,'SUB_MENU',''),
(11,'首页','首页',0,'OPEN_MODULE','HomeShow');";
mysql_query($sql,$conn);


$sql="CREATE TABLE menus_seq(
	id                   INT NOT NULL DEFAULT 10
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="insert into menus_seq(id) values(50);";
mysql_query($sql,$conn);

/*模块*/

$sql="CREATE TABLE modules(
	id                  INT        NOT NULL AUTO_INCREMENT,
	menuid             	INT        NOT NULL,
	module_name         CHAR(50)   NOT NULL,
    module_describe     CHAR(250)  DEFAULT NULL,
    default_action      CHAR(50)   NOT NULL DEFAULT 'index',
	seq                 INT        DEFAULT 0,
	PRIMARY KEY(id),
    KEY(menuid),
    KEY(seq)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);


$sql = "INSERT INTO `modules` VALUES
(1,8,'User',NULL,'index',0),
(2,8,'MenuManager',NULL,'index',0),
(3,8,'ModuleManager',NULL,'index',0),
(4,8,'GroupManager',NULL,'index',0),
(5,8,'Dropdown',NULL,'index',0),
(6,8,'Home',NULL,'index',0),
/*(7,6,'WFRole','流程角色管理','index',0),*/
/*(8,6,'WorkFlowLog','流程日志','index',0),*/
/*(9,6,'MyWorkFlow','我的工作','index',0),*/
/*(15,5,'AccountEvaluate','呼叫中心客户评价记录','index',0),*/
/*(12,3,'ReceivableRecord','收款记录','index',0),*/
(13,3,'InsuranceOrder','保险订单管理','index',0),
(16,2,'AccountTrack','客户跟踪记录','index',0),
(17,2,'Accounts','客户资料管理','index',0),
(18,5,'AgentCDR','呼叫中心座席呼叫记录','index',0),
(19,5,'AgentState','呼叫中心座席状态','index',0),
(20,4,'CallOutAnalysis','呼叫统计','index',0),
(21,4,'CallOutReport','呼叫统计','index',0),
(22,4,'CallOutTrendChart','呼叫统计走势图','index',0),
(24,4,'ComxAnalysis','综合分析报表','index',0),
(26,4,'Daily','日监视表','index',0),
(27,7,'HandOut','分发管理','index',0),
(28,-1,'HomeShow','','showHome',0),
(29,4,'SMSReport','短信统计','index',0),
(31,7,'LibraryCategory','知识库分类','index',0),
(32,7,'LibraryPost','文章','index',0),
(33,10,'ListData','名单数据管理','index',0),
(34,10,'InsuranceAccountCli','保险公司帐号管理(用户端)','index',0),
(35,10,'ListMatch','名单匹配任务','realStatus',0),
(36,8,'LoginLog','登录日志','index',0),
/*(37,5,'MemberState','呼叫中心队列成员状态','index',0),*/
(38,7,'Notices','系统公告','index',0),
(39,8,'PCSetting','保单算价器设置','index',0),
(40,8,'PermissionManager','权限管理模块','index',0),
(41,2,'Park','园区','index',0),
(42,3,'PolicyCalculateCom','算价器(新版通用)','index',0),
/*(43,5,'QueueCDR','呼叫中心队列呼叫记录','index',0),*/
/*(44,5,'QueueState','呼叫中心队列状态','index',0),*/
(45,2,'ResultReport','销售说明','index',0),
(46,7,'SMSNotify','短信通知','index',0),
(47,3,'Telemarketing','电销业务','index',0),
(48,2,'Recycle','回收站','index',0);";

mysql_query($sql,$conn);

/*列表过滤视图*/
$sql="CREATE TABLE filters(
	id                   INT        NOT NULL,
	userid               INT        NOT NULL,
	name                 CHAR(50)   NOT NULL,
	module_name          CHAR(50)   NOT NULL,
	filter_where         TEXT       NOT NULL,
	PRIMARY KEY(id),
	KEY(module_name),
	KEY(userid)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

/*下拉框设置*/
$sql="CREATE TABLE dropdown(
	id                  INT        NOT NULL AUTO_INCREMENT,
	module_name         CHAR(50)   NOT NULL,
	field               CHAR(50)   NOT NULL,
	save_value          CHAR(255)  NOT NULL,
	show_value          CHAR(255)  NOT NULL,
	group_name          CHAR(50)   NOT NULL DEFAULT '',
    PRIMARY KEY(id),
	KEY(module_name,field)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="CREATE  TABLE dropdown_seq(
	id                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO dropdown_seq(id) values(1);";
mysql_query($sql,$conn);

/*首页*/
$sql="CREATE TABLE homes(
	id                   INT        NOT NULL,
	userid               INT        NOT NULL,
	name                 CHAR(50)   NOT NULL,
	cols                 INT        NOT NULL DEFAULT 3,
	rows                 INT        NOT NULL DEFAULT 2,
	default_home         ENUM('NO','YES') DEFAULT 'NO',
	cell1_title          CHAR(50),
	cell1_url            CHAR(255),
	cell2_title          CHAR(50),
	cell2_url            CHAR(255),
	cell3_title          CHAR(50),
	cell3_url            CHAR(255),
	cell4_title          CHAR(50),
	cell4_url            CHAR(255),
	cell5_title          CHAR(50),
	cell5_url            CHAR(255),
	cell6_title          CHAR(50),
	cell6_url            CHAR(255),
	cell7_title          CHAR(50),
	cell7_url            CHAR(255),
	cell8_title          CHAR(50),
	cell8_url            CHAR(255),
	cell9_title          CHAR(50),
	cell9_url            CHAR(255),
	cell10_title         CHAR(50),
	cell10_url           CHAR(255),
	cell11_title         CHAR(50),
	cell11_url           CHAR(255),
	cell12_title         CHAR(50),
	cell12_url           CHAR(255),
	PRIMARY KEY(id),
	KEY(userid)
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);


$sql="CREATE  TABLE homes_seq(
	id                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO homes_seq(id) values(10);";
mysql_query($sql,$conn);




/*短信通知*/
$sql="CREATE TABLE sms_notify(
id                       INT NOT NULL,
userid                   INT,
groupid                  INT,
dir                      ENUM('send','receive'),
callerid                 CHAR(50),
calleeid                 CHAR(50),
msgid                    INT,
state                    ENUM('wait','success','failure'),
errmsg                   CHAR(255),
send_time                TIMESTAMP,
content                  TEXT,
sms_num                  INT,
self_define_content      TEXT,
PRIMARY KEY(id),
KEY(userid),
KEY(groupid),
KEY(msgid),
KEY(state),
KEY(send_time),
KEY(callerid),
KEY(calleeid),
KEY(sms_num)
)ENGINE = InnoDB CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE sms_notify_seq(
id                       INT NOT NULL
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO sms_notify_seq(id) values(1);";
mysql_query($sql,$conn);

/*mas短信接口状态*/
$sql="CREATE TABLE sms_mas_status(
sid                      INT NOT NULL,
passport                 CHAR(50)
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO sms_mas_status(sid,passport) values(1,'');";
mysql_query($sql,$conn);


/*呼叫事件*/
$sql="CREATE TABLE zswitch_call_event(
id                       INT NOT NULL AUTO_INCREMENT,
callerid                 CHAR(50) NOT NULL DEFAULT '',
calleeid                 CHAR(50) NOT NULL DEFAULT '',
event_time               TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
event_type               ENUM('callin_ringing','answered','hangup','callout_ringing'),
agent                    CHAR(100) NOT NULL DEFAULT '',
queue                    CHAR(100) NOT NULL DEFAULT '',
userid                   INT NOT NULL DEFAULT -1,
uuid                     CHAR(50),
PRIMARY KEY(id),
KEY(userid)
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

/*呼叫记录*/
$sql="CREATE TABLE zswitch_call_details(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
userid                           INT NOT NULL DEFAULT -1,
direction                        CHAR(20),
caller_id_number                 CHAR(100),
callee_id_number                 CHAR(100),
destination_number               CHAR(100),
uuid                             CHAR(50)   NOT NULL,
source                           CHAR(50),
context                          CHAR(50),
channel_name                     CHAR(100),
channel_created_datetime         DATETIME,
channel_answered_datetime        DATETIME,
channel_hangup_datetime          DATETIME,
bleg_uuid                        CHAR(50),
hangup_cause                     CHAR(50),
PRIMARY KEY (id),
KEY(userid),
KEY(direction),
KEY(caller_id_number),
KEY(callee_id_number),
KEY(destination_number),
KEY(source),
KEY(context),
KEY(channel_name),
KEY(channel_created_datetime),
KEY(channel_answered_datetime),
KEY(channel_hangup_datetime),
KEY(hangup_cause)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

/*呼叫中心用户评价表*/
$sql="CREATE TABLE zswitch_cc_account_evaluate(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
userid                           INT NOT NULL DEFAULT -1,
caller                           CHAR(100),
callee                           CHAR(100),
uuid                             CHAR(50)   NOT NULL,
agent                            CHAR(100) NOT NULL DEFAULT '',
ptime                            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
dtmf                             CHAR(5),
PRIMARY KEY (id),
KEY(ptime),
KEY(userid),
KEY(agent),
KEY(caller),
KEY(callee),
KEY(dtmf)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

/*座席状态*/
$sql="CREATE TABLE zswitch_cc_agent_state(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
userid                           INT       NOT NULL DEFAULT -1,
workno                           CHAR(50)  NOT NULL DEFAULT '',
name                             CHAR(100) NOT NULL DEFAULT '',
contact                          CHAR(200) NOT NULL DEFAULT '',
status                           CHAR(20)  NOT NULL DEFAULT '',
state                            CHAR(20)  NOT NULL DEFAULT '',
queue                            CHAR(100) NOT NULL DEFAULT '',
uuid                             CHAR(100) NOT NULL DEFAULT '',
other_uuid                       CHAR(100) NOT NULL DEFAULT '',
other_number                     CHAR(50)  NOT NULL DEFAULT '',
dir                              CHAR(20)  NOT NULL DEFAULT '',
start_time                       TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
answer_time                      TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
hangup_time                      TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
hangup_cause                     CHAR(50)  NOT NULL DEFAULT '',
total_callins_answered           INT       NOT NULL DEFAULT 0,
total_callins_no_answer          INT       NOT NULL DEFAULT 0,
today_callins_answered           INT       NOT NULL DEFAULT 0,
today_callins_no_answer          INT       NOT NULL DEFAULT 0,
total_callin_talk_time           INT       NOT NULL DEFAULT 0,
today_callin_talk_time           INT       NOT NULL DEFAULT 0,
total_callouts_answered          INT       NOT NULL DEFAULT 0,
total_callouts_no_answer         INT       NOT NULL DEFAULT 0,
today_callouts_answered          INT       NOT NULL DEFAULT 0,
today_callouts_no_answer         INT       NOT NULL DEFAULT 0,
total_callout_talk_time          INT       NOT NULL DEFAULT 0,
today_callout_talk_time          INT       NOT NULL DEFAULT 0,
total_calls                      INT       NOT NULL DEFAULT 0,
today_calls                      INT       NOT NULL DEFAULT 0,
total_talk_time                  INT       NOT NULL DEFAULT 0,
today_talk_time                  INT       NOT NULL DEFAULT 0,
PRIMARY KEY (id),
KEY(name),
KEY(userid)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);



/*队列状态*/
$sql="CREATE TABLE zswitch_cc_queue_state(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
name                             CHAR(100) NOT NULL DEFAULT '',
state                            ENUM('ON','OFF')  NOT NULL DEFAULT 'ON',
total_calls_answered             INT       NOT NULL DEFAULT 0,
total_calls_no_answer            INT       NOT NULL DEFAULT 0,
today_calls_answered             INT       NOT NULL DEFAULT 0,
today_calls_no_answer            INT       NOT NULL DEFAULT 0,
total_talk_time                  INT       NOT NULL DEFAULT 0,
today_talk_time                  INT       NOT NULL DEFAULT 0,
current_members                  INT       NOT NULL DEFAULT 0,
PRIMARY KEY (id),
KEY(name)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);


/*成员状态*/
$sql="CREATE TABLE zswitch_cc_member_state(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
queue                            CHAR(100) NOT NULL DEFAULT '',
uuid                             CHAR(100) NOT NULL DEFAULT '',
caller_number                    CHAR(50)  NOT NULL DEFAULT '',
joined_time                      TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
bridge_time                      TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
agent_name                       CHAR(50)  NOT NULL DEFAULT '',
state                            CHAR(50)  NOT NULL DEFAULT '',
PRIMARY KEY (id),
KEY(queue)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

/*队列话单*/
$sql="CREATE TABLE zswitch_cc_queue_cdr(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
uuid                             CHAR(100) NOT NULL DEFAULT '',
queue                            CHAR(100) NOT NULL DEFAULT '',
caller_number                    CHAR(50)  NOT NULL DEFAULT '',
agent_name                       CHAR(50)  NOT NULL DEFAULT '',
joined_time                      TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
bridge_time                      TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
end_time                         TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
state                            CHAR(50)  NOT NULL DEFAULT '',
total_timed                      INT       NOT NULL DEFAULT 0,
wait_timed                       INT       NOT NULL DEFAULT 0,
talk_timed                       INT       NOT NULL DEFAULT 0,
PRIMARY KEY (id),
KEY(queue),
KEY(caller_number),
KEY(agent_name),
KEY(joined_time),
KEY(bridge_time),
KEY(end_time),
KEY(state),
KEY(talk_timed),
KEY(wait_timed),
KEY(total_timed)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

/*座席详单*/
$sql="CREATE TABLE zswitch_cc_agent_cdr(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
userid                           INT              NOT NULL DEFAULT -1,
queue                            CHAR(100)        NOT NULL DEFAULT '',
agent_name                       CHAR(50)         NOT NULL DEFAULT '',
dir                              CHAR(20)         NOT NULL DEFAULT 'callin',
other_number                     CHAR(50)         NOT NULL DEFAULT '',
other_area_code                  CHAR(20)         NOT NULL DEFAULT '',
uuid                             CHAR(50)         NOT NULL,
source                           CHAR(50)         DEFAULT '',
context                          CHAR(50)         DEFAULT '',
channel_name                     CHAR(100)        DEFAULT '',
created_datetime                 TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
answered_datetime                TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
hangup_datetime                  TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
bleg_uuid                        CHAR(50)         DEFAULT '',
hangup_cause                     CHAR(50)         DEFAULT '',
total_timed                      INT       NOT NULL DEFAULT 0,
talk_timed                       INT       NOT NULL DEFAULT 0,
PRIMARY KEY (id),
KEY(userid),
KEY(agent_name),
KEY(dir),
KEY(uuid),
KEY(other_number),
KEY(created_datetime),
KEY(answered_datetime),
KEY(hangup_datetime),
KEY(hangup_cause),
KEY(talk_timed),
KEY(total_timed)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

/*客户资料*/
/*$sql="CREATE TABLE accounts(
id                               INT         NOT NULL,
account_no                       CHAR(20)    NOT NULL,
account_name                     CHAR(100)   NOT NULL,
birthday                         DATE,
sexy                             ENUM('Man','Woman'),
marriage                         ENUM('Married','Unmarried'),
profession                       ENUM('Civil','Medical','Teacher','Lawyer','Accountant','Auditors','SOE Staff','Foreign Staff','Private Staff','Professional managers','Freelancers','Investors','Self-employed','Soldier','Student','Other'),
industry                         ENUM('Public Administration','Social Organizations','Research','Culture','Health','Education','Financial','Energy','Telecommunications','Real estate','Internet','Software','Logistics','Traffic','Manufacture','Building','Service','Other'),
post                             ENUM('Staff','Department Manager','General Manager +','Cadres','Section-level cadres','Level cadres','Bureau-level cadres +','Other'),
ID_number                        CHAR(18)    NOT NULL DEFAULT '',
company                          CHAR(200)   NOT NULL DEFAULT '',
area                             CHAR(255)   NOT NULL DEFAULT '',
assets                           CHAR(255)   NOT NULL DEFAULT '',
intention                        ENUM('High','Medium','Low') DEFAULT 'Low',
sales_result                     ENUM('Failure','Follow','Success') DEFAULT 'Follow',
bank_name1                       CHAR(200)   NOT NULL DEFAULT '',
bank_account1                    CHAR(50)    NOT NULL DEFAULT '',
bank_name2                       CHAR(200)   NOT NULL DEFAULT '',
bank_account2                    CHAR(50)    NOT NULL DEFAULT '',
contact                          CHAR(50)    NOT NULL DEFAULT '',
telphone                         CHAR(50)    NOT NULL DEFAULT '',
mobile                           CHAR(50)    NOT NULL DEFAULT '',
fax                              CHAR(50)    NOT NULL DEFAULT '',
email                            CHAR(100)   NOT NULL DEFAULT '',
website                          CHAR(200)   NOT NULL DEFAULT '',
address                          CHAR(255)   NOT NULL DEFAULT '',
postcode                         CHAR(10)    NOT NULL DEFAULT '',
status                           CHAR(50)    NOT NULL DEFAULT '',
remark                           TEXT        NOT NULL DEFAULT '',
user_attach                      INT       NOT NULL DEFAULT -1,
date_create                      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
user_create                      INT       NOT NULL DEFAULT -1,
date_modify                      TIMESTAMP,
user_modify                      INT       NOT NULL DEFAULT -1,
PRIMARY KEY (id),
KEY(account_no),
KEY(account_name),
KEY(company),
KEY(birthday    ),
KEY(sexy        ),
KEY(marriage    ),
KEY(profession  ),
KEY(industry    ),
KEY(post        ),
KEY(ID_number   ),
KEY(company     ),
KEY(area        ),
KEY(assets      ),
KEY(intention   ),
KEY(sales_result),
KEY(contact     ),
KEY(telphone    ),
KEY(mobile      ),
KEY(fax         ),
KEY(email       ),
KEY(address     ),
KEY(postcode    ),
KEY(date_create),
KEY(date_modify),
KEY(status),
KEY(user_create),
KEY(user_attach),
KEY(user_modify)

)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);


$sql="CREATE TABLE accounts_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO accounts_seq(id) values(1);";
mysql_query($sql,$conn);*/

/*合同信息*/
$sql="CREATE TABLE contracts(
id                             INT        NOT NULL,
accountid                      INT        NOT NULL DEFAULT -1,
contract_name                  CHAR(200)  NOT NULL,
contract_no                    CHAR(100)  NOT NULL,
contract_type                  CHAR(100)  NOT NULL DEFAULT'',
contract_target                CHAR(255)  NOT NULL,
start_date                     DATE       NOT NULL,
end_date                       DATE       NOT NULL,
sum_money                      FLOAT      NOT NULL DEFAULT 0,
mode_parment                   CHAR(100)  NOT NULL DEFAULT '',
first_bank_name                CHAR(200),
first_bank_account             CHAR(100),
second_bank_name               CHAR(200),
second_bank_account            CHAR(100),
first_party                    CHAR(100)  NOT NULL,
first_deputy                   CHAR(100)  NOT NULL,
second_party                   CHAR(100)  NOT NULL,
second_deputy                  CHAR(100)  NOT NULL,
third_party                    CHAR(100),
third_deputy                   CHAR(100),
summary                        TEXT,
date_signing                   DATE       NOT NULL DEFAULT '0000-00-00',
remark                         TEXT,
user_attach                    INT        NOT NULL DEFAULT -1,
date_create                    TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP,
user_create                    INT        NOT NULL DEFAULT -1,
date_modify                    TIMESTAMP,
user_modify                    INT NOT NULL DEFAULT -1,
PRIMARY KEY (id),
KEY(accountid),
KEY(contract_name),
KEY(contract_no),
KEY(contract_type),
KEY(start_date),
KEY(end_date),
KEY(sum_money),
KEY(first_party),
KEY(second_party),
KEY(mode_parment)

)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);


$sql="CREATE TABLE contracts_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO contracts_seq(id) values(1);";
mysql_query($sql,$conn);

/*合同收款记录*/
$sql="CREATE TABLE contract_income(
id                              INT        NOT  NULL,
flow_no                         CHAR(50)   NOT  NULL,
contractid                      INT        NOT  NULL DEFAULT -1,
money                           FLOAT      NOT  NULL DEFAULT 0,
mode_payment                    CHAR(100)  NOT  NULL DEFAULT 'transfer',
payee                           CHAR(200)  NOT  NULL DEFAULT '',
payee_bank                      CHAR(200),
payee_account                   CHAR(100),
payee_time                      TIMESTAMP  NOT  NULL DEFAULT '0000-00-00 00:00:00',
payer                           CHAR(200)  NOT  NULL DEFAULT '',
payer_bank                      CHAR(200),
payer_account                   CHAR(100),
payer_time                      TIMESTAMP  NOT  NULL DEFAULT '0000-00-00 00:00:00',
user_attach                     INT        NOT  NULL DEFAULT -1,
date_create                     TIMESTAMP  NOT  NULL DEFAULT CURRENT_TIMESTAMP,
user_create                     INT        NOT  NULL DEFAULT -1,
date_modify                     TIMESTAMP,
user_modify                     INT NOT NULL DEFAULT -1,
PRIMARY KEY(id),
KEY(contractid),
KEY(mode_payment),
KEY(money),
KEY(payee),
KEY(payee_time),
KEY(payer)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE contract_income_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO contract_income_seq(id) values(1);";
mysql_query($sql,$conn);

/*系统公告*/
$sql="CREATE TABLE notices(
id                              INT        NOT  NULL,
title                           CHAR(60)   NOT  NULL,
tag                             ENUM('general','important','emergent','timely'),
contant                         TEXT,
date_create                     TIMESTAMP  NOT  NULL DEFAULT CURRENT_TIMESTAMP,
user_create                     INT        NOT  NULL DEFAULT -1,
PRIMARY KEY(id),
KEY(date_create),
KEY(title),
KEY(tag),
KEY(user_create)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE notices_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO notices_seq(id) values(1);";
mysql_query($sql,$conn);

/*即时消息阅读记录*/
$sql="CREATE TABLE notices_read(
id                              INT         NOT NULL,
notice_id                       INT         NOT NULL,
create_user                     INT         NOT NULL    DEFAULT '-1',
create_time                     TIMESTAMP   NOT NULL    DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY(id),
KEY(create_user),
KEY(notice_id)
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE notices_read_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO notices_read_seq(id) values(1);";
mysql_query($sql,$conn);

/*客户预约记录*/
$sql="CREATE TABLE account_appointment(
id                              INT        NOT  NULL,
accountid                       INT        NOT  NULL,
appointment_time                TIMESTAMP  NOT  NULL DEFAULT '0000-00-00 00:00:00',
state                           ENUM('Waiting','Handled','Cancel'),
remark                          CHAR(255)  NOT  NULL DEFAULT '',
user_handle                     INT        NOT  NULL DEFAULT -1,
date_handle                     TIMESTAMP  NOT  NULL DEFAULT '0000-00-00 00:00:00',
date_create                     TIMESTAMP  NOT  NULL DEFAULT CURRENT_TIMESTAMP,
user_create                     INT        NOT  NULL DEFAULT -1,
PRIMARY KEY(id),
KEY(accountid),
KEY(user_handle),
KEY(date_handle),
KEY(appointment_time),
KEY(state),
KEY(date_create),
KEY(user_create)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE account_appointment_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO account_appointment_seq(id) values(1);";
mysql_query($sql,$conn);

/*客户跟踪记录*/
/*$sql="CREATE TABLE account_track(
id                              INT        NOT  NULL,
title                           CHAR(100)  NOT  NULL,
accountid                       INT        NOT  NULL,
agent                           CHAR(50)   NOT  NULL DEFAULT '',
call_time                       TIMESTAMP  NOT  NULL DEFAULT '0000-00-00 00:00:00',
remark                          CHAR(255)  NOT  NULL DEFAULT '',
date_create                     TIMESTAMP  NOT  NULL DEFAULT CURRENT_TIMESTAMP,
user_create                     INT        NOT  NULL DEFAULT -1,
PRIMARY KEY(id),
KEY(accountid),
KEY(date_create),
KEY(user_create)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);*/
$sql = "CREATE TABLE account_track(
id                              INT        NOT  NULL,
accountid                       INT        NOT  NULL,
user_create                     INT        NOT  NULL DEFAULT -1,
status                          CHAR(30)   NOT  NULL,
report                          CHAR(30)   NOT  NULL,
remark                          CHAR(255)  NOT  NULL DEFAULT '',
date_create                     TIMESTAMP  NOT  NULL DEFAULT CURRENT_TIMESTAMP,
preset_time                     DATETIME   DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY(id),
KEY(accountid),
KEY(date_create),
KEY(user_create),
KEY(status),
KEY(report)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE account_track_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO account_track_seq(id) values(1);";
mysql_query($sql,$conn);

$sql = "CREATE TABLE account_track_financial(
id                              INT        NOT  NULL,
accountid                       INT        NOT  NULL,
user_create                     INT        NOT  NULL DEFAULT -1,
status                          CHAR(30)   NOT  NULL,
report                          CHAR(30)   NOT  NULL,
remark                          CHAR(255)  NOT  NULL DEFAULT '',
date_create                     TIMESTAMP  NOT  NULL DEFAULT CURRENT_TIMESTAMP,
preset_time                     DATETIME   DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY(id),
KEY(accountid),
KEY(date_create),
KEY(user_create),
KEY(status),
KEY(report)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE account_track_financial_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO account_track_financial_seq(id) values(1);";
mysql_query($sql,$conn);


/*电销拨号任务*/
$sql="CREATE TABLE zswitch_ps_autodial_tasks(
id                              INT        NOT  NULL,
name                            CHAR(255)  NOT  NULL,
state                           ENUM('Stop','Runing') DEFAULT 'Stop',
groupid                         INT        NOT  NULL DEFAULT -1,
date_create                     TIMESTAMP  NOT  NULL DEFAULT CURRENT_TIMESTAMP,
user_create                     INT        NOT  NULL DEFAULT -1,
date_modify                     TIMESTAMP,
user_modify                     INT NOT NULL DEFAULT -1,
PRIMARY KEY(id),
KEY(groupid)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE zswitch_ps_autodial_tasks_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO zswitch_ps_autodial_tasks_seq(id) values(1);";
mysql_query($sql,$conn);

/*电销外呼号码*/
$sql="CREATE TABLE zswitch_ps_autodial_number(
id                              INT        NOT  NULL,
number                          CHAR(50)   NOT  NULL,
accountid                       INT        DEFAULT -1,
taskid                          INT        NOT  NULL DEFAULT -1,
status                          ENUM('Waiting','Handling','Handled') DEFAULT 'Waiting',
result                          ENUM('Talk','No answer','Busy','Empty number','Other','No call'),
call_time                       TIMESTAMP  DEFAULT '0000-00-00 00:00:00',
agent                           CHAR(50)   DEFAULT '',
userid                          INT        DEFAULT -1,
date_create                     TIMESTAMP  NOT  NULL DEFAULT CURRENT_TIMESTAMP,
user_create                     INT        NOT  NULL DEFAULT -1,
date_modify                     TIMESTAMP,
user_modify                     INT NOT NULL DEFAULT -1,
PRIMARY KEY(id),
KEY(number),
KEY(taskid),
KEY(status),
KEY(result),
KEY(call_time),
KEY(userid),
KEY(accountid)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE zswitch_ps_autodial_number_seq(
id                              INT
)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="INSERT INTO zswitch_ps_autodial_number_seq(id) values(1);";
mysql_query($sql,$conn);

/*电销点击取号工作表*/
$sql="CREATE TABLE zswitch_ps_autodial_job(
userid                           INT        NOT  NULL,
groupid                          INT        NOT  NULL,
agent                            CHAR(50)   NOT  NULL,
number                           CHAR(50)   DEFAULT '',
numberid                         INT        DEFAULT -1,
state                            ENUM('calling','answered'),
PRIMARY KEY(userid)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);


/*VIP 号码表*/
$sql="CREATE TABLE zswitch_cc_vipnumber(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
number                           CHAR(50)         NOT  NULL,
level                            INT              DEFAULT 1,
PRIMARY KEY(id),
KEY(number)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);




/*保险车辆信息*/
$sql="CREATE TABLE insurance_auto_info(
id                               INT UNSIGNED     NOT NULL AUTO_INCREMENT,
plate_no                         CHAR(20)         NOT NULL,

owner                            CHAR(100)        NOT NULL,
id_code                          CHAR(100)        NOT NULL,
contact                          CHAR(50)         NOT NULL DEFAULT '',
telphone                         CHAR(50)         NOT NULL DEFAULT '',
mobile                           CHAR(50)         NOT NULL DEFAULT '',
address                          CHAR(255)        NOT NULL DEFAULT '',

buying_price                     INT              NOT NULL DEFAULT 0,
vehicle_type                     CHAR(20)         NOT NULL,
use_character                    CHAR(20)         NOT NULL,
model                            CHAR(50)         NOT NULL,
vin                              CHAR(50)         NOT NULL,
engine_no                        CHAR(50)         DEFAULT '',
register_date                    DATE             DEFAULT '0000-00-00',
register_address                 CHAR(100)        DEFAULT '',
seats                            INT              DEFAULT 0,
kerb_mass                        INT              DEFAULT 0,
total_mass                       INT              DEFAULT 0,
load                             INT              DEFAULT 0,
tow_mass                         INT              DEFAULT 0,
engine                           INT              DEFAULT 0,
power                            INT              DEFAULT 0,
body_size                        CHAR(50)         DEFAULT '',
body_color                       CHAR(10)         DEFAULT '',
origin                           CHAR(20)         NOT NULL DEFAULT 'DOMESTIC',

associate_userid                 INT               DEFAULT -1,
create_time                      DATETIME          NOT  NULL DEFAULT CURRENT_TIMESTAMP,
create_userid                    INT               NOT  NULL DEFAULT -1,
modify_time                      DATETIME          NOT  NULL DEFAULT '0000-00-00 00:00:00',
modify_userid                    INT               NOT NULL DEFAULT -1,

PRIMARY KEY(id),
UNIQUE  KEY(plate_no),
KEY(owner),
KEY(id_code),
KEY(vehicle_type),
KEY(use_character),
KEY(vin),
KEY(engine_no),

KEY(associate_userid),
KEY(create_time),
KEY(create_userid),
KEY(modify_time),
KEY(modify_userid)

)ENGINE=MYISAM  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

/*保险算价记录*/
$sql ="CREATE TABLE insurance_calculate_log(
id                               INT UNSIGNED      NOT NULL AUTO_INCREMENT,
autoid                           INT               NOT NULL DEFAULT -1,
floating_rate                    CHAR(10)          NOT NULL DEFAULT 'A4',
claim_records                    CHAR(20)          NOT NULL DEFAULT 'LAST_YEAR_CLAIM_ONE',
years_of_insurance               CHAR(20)          NOT NULL DEFAULT 'RENEWAL_OF_INSURANCE',
designated_driver                CHAR(20)          NOT NULL DEFAULT 'NO_DESIGNATED_DRIVER',
driver_age                       CHAR(20)          NOT NULL DEFAULT '25_30_AGE',
driver_sex                       CHAR(10)          NOT NULL DEFAULT 'MALE',
driving_years                    CHAR(20)          NOT NULL DEFAULT 'GREATER_3_YEARS',
driving_area                     CHAR(20)          NOT NULL DEFAULT 'CHINA_TERRITORY' ,
average_annual_mileage           CHAR(20)          NOT NULL DEFAULT 'LESS_30000_KM',
multiple_insurance               CHAR(20)          NOT NULL DEFAULT 'MULTIPLE_INSURANCE_1',

buy_types                        CHAR(255)         NOT NULL DEFAULT '',
mvtalci_start_time               DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
mvtalci_months                   INT               NOT NULL DEFAULT 12,
other_start_time                 DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
other_months                     INT               NOT NULL DEFAULT 12,

tvdi_amount                      INT               NOT NULL DEFAULT 0,
doc_amount                       INT               NOT NULL DEFAULT 0,
ttbli_amount                     INT               NOT NULL DEFAULT 0,
twcdmvi_amount                   INT               NOT NULL DEFAULT 0,
tcpli_driver_amount              INT               NOT NULL DEFAULT 0,
tcpli_passenger_amount           INT               NOT NULL DEFAULT 0,
tcpli_passenger_count            INT               NOT NULL DEFAULT 0,
bsdi_amount                      INT               NOT NULL DEFAULT 0,
bgai_amount                      INT               NOT NULL DEFAULT 0,

associate_userid                 INT               DEFAULT -1,
create_time                      DATETIME          NOT  NULL DEFAULT CURRENT_TIMESTAMP,
create_userid                    INT               NOT  NULL DEFAULT -1,
modify_time                      DATETIME          NOT  NULL DEFAULT '0000-00-00 00:00:00',
modify_userid                    INT               NOT NULL DEFAULT -1,

PRIMARY KEY(id),
KEY(autoid),
KEY(associate_userid),
KEY(create_time),
KEY(create_userid),
KEY(modify_time),
KEY(modify_userid)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);



/*保险公司帐号(客户端)*/
$sql ="CREATE TABLE  insurance_account_cli(
id                               INT UNSIGNED     NOT NULL,
insurance_company                CHAR(20)         DEFAULT '',
uid                              CHAR(50)         DEFAULT '',
pwd                              CHAR(50)         DEFAULT '',
create_time                      DATETIME         NOT  NULL ,
create_userid                    INT              NOT  NULL DEFAULT -1,
modify_time                      DATETIME         DEFAULT '0000-00-00 00:00:00',
modify_userid                    INT              NOT NULL DEFAULT -1,
PRIMARY KEY(id),
KEY(insurance_company)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE  TABLE  insurance_account_cli_seq(
id                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO insurance_account_cli_seq(id) values(1);";
mysql_query($sql,$conn);


/*名单数据*/
$sql="CREATE TABLE list_data(
id                               INT UNSIGNED     NOT NULL,
license_no                       CHAR(20)         NOT NULL,
license_type                     CHAR(50)         DEFAULT 'SMALL_CAR',
owner                            CHAR(100)        DEFAULT '',
identify_type                    CHAR(50)         DEFAULT 'IDENTITY_CARD',
identify_no                      CHAR(100)        DEFAULT '',
telphone                         CHAR(50)         DEFAULT '',
mobile                           CHAR(50)         DEFAULT '',
address                          CHAR(255)        DEFAULT '',
model                            CHAR(50)         DEFAULT '',
model_code                       CHAR(50)         DEFAULT '',
vin_no                           CHAR(50)         DEFAULT '',
engine_no                        CHAR(50)         DEFAULT '',
enroll_date                      DATE             DEFAULT '0000-00-00',
seats                            INT              DEFAULT 0,
kerb_mass                        INT              DEFAULT 0,
total_mass                       INT              DEFAULT 0,
load_mass                        INT              DEFAULT 0,
tow_mass                         INT              DEFAULT 0,
engine                           INT              DEFAULT 0,
power                            FLOAT            DEFAULT 0,
origin                           CHAR(20)         DEFAULT 'DOMESTIC',
run_area                         CHAR(50)         DEFAULT 'THE_TERRITORY_OF',
buying_price                     FLOAT            DEFAULT 0,

policy_no                        CHAR(50)         DEFAULT '',
insurance_company                CHAR(20)         DEFAULT '',
start_date                       TIMESTAMP        DEFAULT '0000-00-00 00:00:00',
end_date                         TIMESTAMP        DEFAULT '0000-00-00 00:00:00',
prev_claims                      INT              DEFAULT 0,
current_claims                   INT              DEFAULT 0,
discount                         FLOAT            DEFAULT 1,
premium                          FLOAT            DEFAULT 0,

tvdi                             FLOAT            DEFAULT 0,
twcdmvi                          FLOAT            DEFAULT 0,
ttbli                            FLOAT            DEFAULT 0,
tcpli_d                          FLOAT            DEFAULT 0,
tcpli_p                          FLOAT            DEFAULT 0,
sloi                             FLOAT            DEFAULT 0,
bsdi                             FLOAT            DEFAULT 0,
bgai                             FLOAT            DEFAULT 0,
nieli                            FLOAT            DEFAULT 0,
vwtli                            FLOAT            DEFAULT 0,
stsfs                            FLOAT            DEFAULT 0,

tvdi_ndsi                        FLOAT            DEFAULT 0,
ttbli_ndsi                       FLOAT            DEFAULT 0,
twcdmvi_ndsi                     FLOAT            DEFAULT 0,
tcpli_d_ndsi                     FLOAT            DEFAULT 0,
tcpli_p_ndsi                     FLOAT            DEFAULT 0,
bsdi_ndsi                        FLOAT            DEFAULT 0,
other_ins                        FLOAT            DEFAULT 0,

batch                            CHAR(50)         NOT NULL,
state                            CHAR(50)         NOT NULL DEFAULT 'WAITING',
request_time                     TIMESTAMP        DEFAULT 0,
respone_time                     TIMESTAMP        DEFAULT 0,
logid                            INT              DEFAULT -1,

user_attach                      INT              DEFAULT -1,
create_time                      DATETIME         NOT  NULL ,
create_userid                    INT              NOT  NULL DEFAULT -1,
modify_time                      DATETIME         DEFAULT '0000-00-00 00:00:00',
modify_userid                    INT              NOT NULL DEFAULT -1,

PRIMARY KEY(id),
KEY(license_no),
KEY(vin_no),
KEY(start_date),
KEY(end_date),
KEY(batch),
KEY(owner),
KEY(identify_no),
KEY(state),
KEY(policy_no),
KEY(request_time),
KEY(respone_time),
KEY(insurance_company),
KEY(prev_claims),
KEY(current_claims),
KEY(enroll_date),
KEY(buying_price),
KEY(premium),
KEY(user_attach),
KEY(create_time),
KEY(create_userid),
KEY(modify_time),
KEY(modify_userid),
KEY batch_state(batch,state)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE  TABLE  list_data_seq(
id                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO list_data_seq(id) values(1);";
mysql_query($sql,$conn);


/*名单匹配任务表*/
$sql="CREATE TABLE list_match(
id                               INT UNSIGNED      NOT NULL,
name                             CHAR(100)         NOT NULL,
batch                            CHAR(50)          NOT NULL,
state                            CHAR(50)          NOT NULL,
start_time                       DATETIME          DEFAULT '0000-00-00 00:00:00',
ins_end_st                       DATETIME          DEFAULT NULL,
ins_end_end                      DATETIME          DEFAULT NULL,
timed1_start                     TIME              DEFAULT '00:00:00',
timed1_end                       TIME              DEFAULT '23:59:59',
timed2_start                     TIME              DEFAULT NULL,
timed2_end                       TIME              DEFAULT NULL,
timed3_start                     TIME              DEFAULT NULL,
timed3_end                       TIME              DEFAULT NULL,
timed4_start                     TIME              DEFAULT NULL,
timed4_end                       TIME              DEFAULT NULL,
tag                              CHAR(20)          NOT NULL DEFAULT 'WAITING',
request_complete                 CHAR(4)           NOT NULL DEFAULT 'NO',
error_info                       CHAR(100)         NOT NULL DEFAULT ' ',
last_time                        TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
complete_count                   INT               DEFAULT 0,
request_count                    INT               DEFAULT 0,
success_count                    INT               DEFAULT 0,
failure_count                    INT               DEFAULT 0,
part_count                       INT               DEFAULT 0,
create_time                      DATETIME          NOT  NULL DEFAULT '0000-00-00 00:00:00',
create_userid                    INT               NOT  NULL DEFAULT -1,
modify_time                      DATETIME          NOT  NULL DEFAULT '0000-00-00 00:00:00',
modify_userid                    INT               NOT  NULL DEFAULT -1,
PRIMARY KEY(id),
KEY(name),
KEY(state),
KEY(start_time),

KEY(create_time),
KEY(create_userid),
KEY(modify_time),
KEY(modify_userid)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE  TABLE  list_match_seq(
	id                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO  list_match_seq(id) values(1);";
mysql_query($sql,$conn);

/*流程日志*/
$sql="CREATE TABLE workflow_log(
id                               INT UNSIGNED      NOT NULL AUTO_INCREMENT,
process                          CHAR(100)         NOT NULL,
task                             CHAR(100)         NOT NULL,
cindex                           INT               NOT NULL,
case_id                          INT               NOT NULL,
case_title                       CHAR(100)         NOT NULL,
transfer_time                    DATETIME          NOT NULL DEFAULT '0000-00-00 00:00:00',
start_time                       DATETIME          NOT NULL DEFAULT '0000-00-00 00:00:00',
end_time                         DATETIME          NOT NULL DEFAULT '0000-00-00 00:00:00',
delegated_user                   CHAR(50)          NOT NULL DEFAULT '',
delegated_user_name              CHAR(50)          NOT NULL DEFAULT '',
operation                        CHAR(50)          NOT NULL DEFAULT '',
suggestion                       TEXT              ,
PRIMARY KEY(id),
KEY(case_id,cindex)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

/*客户资料，上海唯佑专用*/
$sql=" CREATE TABLE `accounts_shwy` (
  `id` int(11) NOT NULL,
  `owner` char(100) NOT NULL ,
  `contact` char(50) NOT NULL DEFAULT '' ,
  `telphone` char(50) NOT NULL DEFAULT '' ,
  `mobile` char(20) DEFAULT NULL ,
  `address` char(255) DEFAULT NULL ,
  `id_code` char(100) DEFAULT NULL ,
  `lending_bank` char(50) DEFAULT NULL ,
  `intention` char(10) DEFAULT NULL ,
  `last_policy` char(100) DEFAULT NULL ,
  `team` char(100) DEFAULT NULL ,
  `area` char(50) DEFAULT NULL ,
  `park` char(50) DEFAULT NULL ,
  `purchase_price` int(11) NOT NULL DEFAULT '0' ,
  `plate_no` char(20) DEFAULT NULL ,
  `vehicle_type` char(20) DEFAULT NULL ,
  `use_character` char(20) DEFAULT NULL,
  `model` char(50) DEFAULT NULL,
  `vin` char(50) NOT NULL ,
  `engine_no` char(50) DEFAULT '' ,
  `register_date` date DEFAULT '0000-00-00' ,
  `register_address` char(100) DEFAULT '' ,
  `seats` int(11) DEFAULT '0' ,
  `kerb_mass` int(11) DEFAULT '0' ,
  `total_mass` int(11) DEFAULT '0' ,
  `ratify_load` int(11) DEFAULT '0' ,
  `tow_mass` int(11) DEFAULT '0' ,
  `engine` int(11) DEFAULT '0' ,
  `power` int(11) DEFAULT '0' ,
  `body_size` char(50) DEFAULT '' ,
  `body_color` char(10) DEFAULT '' ,
  `origin` char(20) NOT NULL DEFAULT 'DOMESTIC' ,
  `company` CHAR(50) DEFAULT '' ,
  `batch` char(10) NOT NULL DEFAULT 'A' ,
  `type` char(20) NOT NULL DEFAULT 'FIRST_YEAR' ,
  `status` char(30) NOT NULL DEFAULT 'FIRST_DIAL' ,
  `user_attach` int(11) NOT NULL DEFAULT '-1' ,
  `user_create` int(11) NOT NULL DEFAULT '-1' ,
  `user_modify` int(11) NOT NULL DEFAULT '-1' ,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `expiration_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `preset_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `remark` text,
  `report` char(30) DEFAULT NULL,
  `levelrisk` CHAR(50) DEFAULT '',
  `dangtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dangernumber` int(11) unsigned DEFAULT '0',
  `illegalcoeff` float(10,2) unsigned DEFAULT '0.00',
  `discountfactor` float(10,2) unsigned DEFAULT '0.00',
  `dangerfactor` float(10,2) unsigned DEFAULT '0.00',
  `clover_id` int(11) NOT NULL,
  `data_sources` char(50) default '',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `mobile` (`mobile`),
  KEY `id_code` (`id_code`),
  KEY `intention` (`intention`),
  KEY `lending_bank` (`lending_bank`),
  KEY `plate_no` (`plate_no`),
  KEY `engine_no` (`engine_no`),
  KEY `vehicle_type` (`vehicle_type`),
  KEY `use_character` (`use_character`),
  KEY `telphone` (`telphone`),
  KEY `company` (`company`),
  KEY `batch` (`batch`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`),
  KEY `register_date` (`register_date`),
  KEY `expiration_date` (`expiration_date`),
  KEY `preset_time` (`preset_time`),
  KEY `status_user` (`status`,`user_attach`),
  KEY `data_sources` (`data_sources`),
  KEY `comx_index` (`expiration_date`,`status`,`user_attach`,`register_date`),
  KEY `comx_sue_index` (`status`,`user_attach`,`expiration_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
mysql_query($sql,$conn);

$sql="CREATE  TABLE  accounts_shwy_seq(
	id                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO  accounts_shwy_seq(id) values(1);";
mysql_query($sql,$conn);

/*保单算价记录*/
$sql="CREATE TABLE policy_draft(
id                               INT UNSIGNED      NOT NULL,
cal_no                           CHAR(50)          NOT NULL,
vin_no                           CHAR(50)          NOT NULL,
summary                          CHAR(255)         NOT NULL,
content                          TEXT              ,
associate_userid                 INT               DEFAULT -1,
create_time                      TIMESTAMP         NOT  NULL DEFAULT CURRENT_TIMESTAMP,
create_userid                    INT               NOT  NULL DEFAULT -1,
modify_time                      TIMESTAMP         NOT  NULL DEFAULT '0000-00-00 00:00:00',
modify_userid                    INT               NOT NULL DEFAULT -1,

PRIMARY KEY(id),
KEY(cal_no),
KEY(vin_no),
KEY(associate_userid)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE policy_draft_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="insert into policy_draft_seq(id) values(2);";
mysql_query($sql,$conn);


/*保单算价设置*/
$sql="CREATE TABLE policy_calculate_setting(
	setting                      TEXT
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE policy_draft_com(
id                               INT UNSIGNED      NOT NULL,
cal_no                           CHAR(50)          NOT NULL,
vin_no                           CHAR(50)          NOT NULL,
summary                          CHAR(255)         NOT NULL,
content                          TEXT              ,
associate_userid                 INT               DEFAULT -1,
create_time                      TIMESTAMP         NOT  NULL DEFAULT CURRENT_TIMESTAMP,
create_userid                    INT               NOT  NULL DEFAULT -1,
modify_time                      TIMESTAMP         NOT  NULL DEFAULT '0000-00-00 00:00:00',
modify_userid                    INT               NOT NULL DEFAULT -1,

PRIMARY KEY(id),
KEY(cal_no),
KEY(vin_no),
KEY(associate_userid)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE policy_draft_com_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="insert into policy_draft_com_seq(id) values(2);";
mysql_query($sql,$conn);


/*保险订单*/
$sql="CREATE TABLE insurance_order(
id                               INT UNSIGNED      NOT NULL,
order_no                         CHAR(20)          NOT NULL,
holder                           CHAR(100)         NOT NULL,
license_no                       CHAR(20)          NOT NULL,
vin_no                           CHAR(50)          NOT NULL,
business_policy_no               CHAR(50)          NOT NULL,
business_standard_premium        FLOAT             DEFAULT 0,
business_discount                FLOAT             DEFAULT 1,
business_premium                 FLOAT             DEFAULT 0,
business_end_time                TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
business_start_time              TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
mvtalci_policy_no                CHAR(50)          NOT NULL,
mvtalci_standard_premium         FLOAT             DEFAULT 0,
mvtalci_discount                 FLOAT             DEFAULT 1,
mvtalci_premium                  FLOAT             DEFAULT 0,
mvtalci_end_time                 TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
mvtalci_start_time               TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
insurance_company                CHAR(20)          DEFAULT '',
travel_tax_premium               FLOAT             DEFAULT 0,
total_premium                    FLOAT             DEFAULT 0,
total_receivable_amount          FLOAT             DEFAULT 0,
receiver                         CHAR(50)          NOT NULL DEFAULT '',
receiver_mobile                  CHAR(50)          NOT NULL DEFAULT '',
receiver_addr                    CHAR(255)         NOT NULL,
case_id                          INT               NOT NULL,
status                           CHAR(20)          NOT NULL,
gift                             CHAR(255)         DEFAULT '',
remarks                          CHAR(255)         DEFAULT '',
levelrisk                        CHAR(20)          NOT NULL DEFAULT '',
auditor                          CHAR(20)          DEFAULT '',
auditor_levelrisk                CHAR(20)          NOT NULL DEFAULT '',
complete_time                    TIMESTAMP         NOT NULL DEFAULT  '0000-00-00 00:00:00',
create_time                      TIMESTAMP         NOT NULL DEFAULT  CURRENT_TIMESTAMP,
create_userid                    INT               NOT NULL DEFAULT 1,
create_user                      CHAR(50)          DEFAULT '',
PRIMARY KEY(id),
KEY(order_no),
KEY(vin_no),
KEY(license_no)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE insurance_order_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="insert into insurance_order_seq(id) values(100000000);";
mysql_query($sql,$conn);

//
$sql="CREATE TABLE receivable_record(
id                               INT UNSIGNED      NOT NULL AUTO_INCREMENT,
order_no                         CHAR(20)          NOT NULL ,
receivabler                      CHAR(50)          NOT NULL ,
receivable_amount                FLOAT             NOT NULL DEFAULT 0,
receivable_time                  TIMESTAMP         NOT NULL ,
receivable_type                  CHAR(50)          NOT NULL ,
receivable_bank                  CHAR(50)          DEFAULT '' ,
receivable_account               CHAR(50)          DEFAULT '' ,
pay_bank                         CHAR(50)          DEFAULT '' ,
pay_account                      CHAR(50)          DEFAULT '' ,
pay_user                         CHAR(50)          NOT NULL ,
PRIMARY KEY(id),
KEY(order_no)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);


$sql="CREATE TABLE `park` (
  `id` int(11) NOT NULL,
  `area` char(50) NOT NULL COMMENT '片区',
  `park` char(50) NOT NULL COMMENT '园区',
  PRIMARY KEY (`id`),
  KEY `area` (`area`),
  KEY `park` (`park`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql ="CREATE TABLE `park_seq` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);
$sql = "INSERT INTO `park_seq` VALUES (1);";
mysql_query($sql,$conn);


$sql="CREATE TABLE `daily_analysis` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL DEFAULT '-1',
  `first_dial` int(11) DEFAULT '0',
  `appointment` int(11) DEFAULT '0',
  `success` int(11) DEFAULT '0',
  `failure` int(11) DEFAULT '0',
  `create_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`,`create_date`),
  KEY `groupid` (`groupid`),
  KEY `first_dial` (`first_dial`),
  KEY `appointment` (`appointment`),
  KEY `success` (`success`),
  KEY `failure` (`failure`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql ="CREATE TABLE `daily_analysis_seq` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);
$sql = "INSERT INTO daily_analysis_seq(id) values(1);";
mysql_query($sql,$conn);

$sql ="CREATE TABLE result_report (
	id                     int(11)       NOT NULL,
	status                 CHAR(100)     NOT NULL,
	report                 CHAR(255)     NOT NULL DEFAULT '',
	PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);
$sql="CREATE TABLE result_report_seq(
    id                     int(11)       NOT NULL
)ENGINE=MyISAM DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);
$sql="INSERT INTO result_report_seq(id) values(1);";
mysql_query($sql,$conn);

/*$sql ="CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `owner` char(100) NOT NULL ,
  `contact` char(50) NOT NULL DEFAULT '' ,
  `telphone` char(50) NOT NULL DEFAULT '' ,
  `mobile` char(20) DEFAULT NULL ,
  `address` char(255) DEFAULT NULL ,
  `id_code` char(100) DEFAULT NULL ,
  `lending_bank` char(50) DEFAULT NULL ,
  `intention` char(10) DEFAULT NULL ,
  `last_policy` char(100) DEFAULT NULL ,
  `team` char(100) DEFAULT NULL ,
  `area` char(50) DEFAULT NULL ,
  `park` char(50) DEFAULT NULL ,
  `purchase_price` int(11) NOT NULL DEFAULT '0' ,
  `plate_no` char(20) DEFAULT NULL ,
  `vehicle_type` char(20) DEFAULT NULL ,
  `use_character` char(20) DEFAULT NULL,
  `model` char(50) DEFAULT NULL,
  `vin` char(50) NOT NULL ,
  `engine_no` char(50) DEFAULT '' ,
  `register_date` date DEFAULT '0000-00-00' ,
  `register_address` char(100) DEFAULT '' ,
  `seats` int(11) DEFAULT '0' ,
  `kerb_mass` int(11) DEFAULT '0' ,
  `total_mass` int(11) DEFAULT '0' ,
  `ratify_load` int(11) DEFAULT '0' ,
  `tow_mass` int(11) DEFAULT '0' ,
  `engine` int(11) DEFAULT '0' ,
  `power` int(11) DEFAULT '0' ,
  `body_size` char(50) DEFAULT '' ,
  `body_color` char(10) DEFAULT '' ,
  `origin` char(20) NOT NULL DEFAULT 'DOMESTIC' ,
  `company` CHAR(50) DEFAULT '' ,
  `batch` char(10) NOT NULL DEFAULT 'A' ,
  `type` char(20) NOT NULL DEFAULT 'FIRST_YEAR' ,
  `status` char(30) NOT NULL DEFAULT 'FIRST_DIAL' ,
  `user_attach` int(11) NOT NULL DEFAULT '-1' ,
  `user_create` int(11) NOT NULL DEFAULT '-1' ,
  `user_modify` int(11) NOT NULL DEFAULT '-1' ,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `date_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `expiration_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `preset_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `remark` text,
  `report` char(30) DEFAULT NULL,
  `levelrisk` CHAR(50) DEFAULT '',
  `dangtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dangernumber` int(11) unsigned DEFAULT '0',
  `illegalcoeff` float(10,2) unsigned DEFAULT '0.00',
  `discountfactor` float(10,2) unsigned DEFAULT '0.00',
  `dangerfactor` float(10,2) unsigned DEFAULT '0.00',
  `clover_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `mobile` (`mobile`),
  KEY `id_code` (`id_code`),
  KEY `intention` (`intention`),
  KEY `lending_bank` (`lending_bank`),
  KEY `plate_no` (`plate_no`),
  KEY `engine_no` (`engine_no`),
  KEY `vehicle_type` (`vehicle_type`),
  KEY `use_character` (`use_character`),
  KEY `telphone` (`telphone`),
  KEY `company` (`company`),
  KEY `batch` (`batch`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`),
  KEY `register_date` (`register_date`),
  KEY `expiration_date` (`expiration_date`),
  KEY `preset_time` (`preset_time`),
  KEY `status_user` (`status`,`user_attach`),
  KEY `comx_index` (`expiration_date`,`status`,`user_attach`,`register_date`),
  KEY `comx_sue_index` (`status`,`user_attach`,`expiration_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";*/
$sql = "CREATE TABLE `accounts` (
  `id`                INT(11)           NOT NULL                                                        COMMENT 'ID号',
  `owner`             CHAR(100)         NOT NULL                                                        COMMENT '客户姓名',
  `contact`           CHAR(50)                            DEFAULT ''                                    COMMENT '联系人',
  `telphone`          CHAR(50)          NOT NULL          DEFAULT ''                                    COMMENT '固定电话',
  `mobile`            CHAR(20)                            DEFAULT NULL                                  COMMENT '手机号码',
  `address`           CHAR(255)                           DEFAULT NULL                                  COMMENT '地址',
  `id_code`           CHAR(100)                           DEFAULT NULL                                  COMMENT '身份证/机构代码',
  `lending_bank`      CHAR(50)                            DEFAULT NULL                                  COMMENT '贷款银行',
  `intention`         CHAR(10)                            DEFAULT NULL                                  COMMENT '意向程度',
  `last_policy`       CHAR(100)                           DEFAULT NULL                                  COMMENT '去年保单号码',
  `team`              CHAR(100)                           DEFAULT NULL                                  COMMENT '品牌团队',
  `area`              CHAR(50)                            DEFAULT NULL                                  COMMENT '片区',
  `park`              CHAR(50)                            DEFAULT NULL                                  COMMENT '园区',
  `purchase_price`    INT(11)           NOT NULL          DEFAULT '0'                                   COMMENT '新车购置价',
  `plate_no`          CHAR(20)                            DEFAULT NULL                                  COMMENT '车牌号',
  `vehicle_type`      CHAR(20)                            DEFAULT NULL                                  COMMENT '车辆种类',
  `use_character`     CHAR(20)                            DEFAULT NULL                                  COMMENT '使用性质',
  `model`             CHAR(50)                            DEFAULT NULL                                  COMMENT '品牌型号',
  `vin`               CHAR(50)          NOT NULL                                                        COMMENT '车架号/车辆识别代码',
  `engine_no`         CHAR(50)                            DEFAULT ''                                    COMMENT '发动机号',
  `register_date`     DATE                                DEFAULT '0000-00-00'                          COMMENT '注册日期',
  `register_address`  CHAR(100)                           DEFAULT ''                                    COMMENT '注册地',
  `seats`             INT(11)                             DEFAULT '0'                                   COMMENT '核定载人数',
  `kerb_mass`         INT(11)                             DEFAULT '0'                                   COMMENT '整备质量',
  `total_mass`        INT(11)                             DEFAULT '0'                                   COMMENT '总质量',
  `ratify_load`       INT(11)                             DEFAULT '0'                                   COMMENT '核定载量',
  `tow_mass`          INT(11)                             DEFAULT '0'                                   COMMENT '准牵总质量',
  `engine`            INT(11)                             DEFAULT '0'                                   COMMENT '发动机排气量',
  `power`             INT(11)                             DEFAULT '0'                                   COMMENT '功率',
  `body_size`         CHAR(50)                            DEFAULT ''                                    COMMENT '车身尺寸',
  `body_color`        CHAR(10)                            DEFAULT ''                                    COMMENT '车身颜色',
  `origin`            CHAR(20)          NOT NULL          DEFAULT 'DOMESTIC'                            COMMENT '产地',
  `company`           CHAR(50)                            DEFAULT ''                                    COMMENT '保险公司',
  `batch`             CHAR(10)          NOT NULL          DEFAULT 'A'                                   COMMENT '批次',
  `type`              CHAR(20)          NOT NULL          DEFAULT 'FIRST_YEAR'                          COMMENT '名单类型',
  `status`            CHAR(30)          NOT NULL          DEFAULT 'FIRST_DIAL'                          COMMENT '状态',
  `user_attach`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '归属于',
  `user_create`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '创建记录的操作员',
  `user_modify`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '最后一次修改记录的操作员',
  `date_create`       TIMESTAMP         NOT NULL          DEFAULT CURRENT_TIMESTAMP                     COMMENT '记录创建的时间',
  `date_modify`       TIMESTAMP         NOT NULL          DEFAULT '0000-00-00 00:00:00'                 COMMENT '最后一次修改记录的时间',
  `expiration_date`   TIMESTAMP                           DEFAULT '0000-00-00 00:00:00'                 COMMENT '保险到期日期',
  `preset_time`       TIMESTAMP         NOT NULL          DEFAULT '0000-00-00 00:00:00'                 COMMENT '预约日期',
  `remark`            TEXT                                                                              COMMENT '备注',
  `report`            CHAR(30)                            DEFAULT NULL                                  COMMENT '销售说明',
  `deleted`           TINYINT           NOT NULL          DEFAULT 0                                     COMMENT '是否删除',
  `distribute_date`   DATETIME                            DEFAULT '0000-00-00 00:00:00'                 COMMENT '操作时间',
  `distribute_user`   INT(11)                             DEFAULT '-1'                                  COMMENT '操作人员',
  `distribute_option` CHAR(20)                                                                          COMMENT '操作类型',
  `last_user_attach`  INT(11)                             DEFAULT '-1'                                  COMMENT '上次归属于',
  /*`levelrisk`         CHAR(50)                            DEFAULT ''                                    COMMENT '',
  `dangtime`          TIMESTAMP         NOT NULL          DEFAULT '0000-00-00 00:00:00'                 COMMENT '',
  `dangernumber`      INT(11)           unsigned          DEFAULT '0'                                   COMMENT '',
  `illegalcoeff`      FLOAT(10,2)       unsigned          DEFAULT '0.00'                                COMMENT '',
  `discountfactor`    float(10,2)       unsigned          DEFAULT '0.00'                                COMMENT '',
  `dangerfactor`      float(10,2)       unsigned          DEFAULT '0.00'                                COMMENT '',
  `clover_id`         int(11)           NOT NULL                                                        COMMENT '',*/
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `mobile` (`mobile`),
  KEY `vin` (`vin`),
  KEY `id_code` (`id_code`),
  KEY `intention` (`intention`),
  KEY `lending_bank` (`lending_bank`),
  KEY `plate_no` (`plate_no`),
  KEY `engine_no` (`engine_no`),
  KEY `vehicle_type` (`vehicle_type`),
  KEY `use_character` (`use_character`),
  KEY `telphone` (`telphone`),
  KEY `company` (`company`),
  KEY `batch` (`batch`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`),
  KEY `register_date` (`register_date`),
  KEY `expiration_date` (`expiration_date`),
  KEY `preset_time` (`preset_time`),
  KEY `deleted` (`deleted`),
  KEY `distribute_date` (`distribute_date`),
  KEY `distribute_user` (`distribute_user`),
  KEY `distribute_option` (`distribute_option`),
  KEY `last_user_attach` (`last_user_attach`),
  KEY `status_user` (`status`,`user_attach`),
  KEY `comx_index` (`expiration_date`,`status`,`user_attach`,`register_date`),
  KEY `comx_sue_index` (`status`,`user_attach`,`expiration_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql ="CREATE  TABLE  accounts_seq(
	id                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql ="INSERT INTO  accounts_seq(id) values(1);";
mysql_query($sql,$conn);

$sql = "CREATE TABLE `accounts_financial` (
  `id`                INT(11)           NOT NULL                                                        COMMENT 'ID号',
  `owner`             CHAR(100)         NOT NULL                                                        COMMENT '客户姓名',
  `gender`            CHAR(10)          NOT NULL          DEFAULT 'MAN'                                 COMMENT '性别',
  `age`               TINYINT                             DEFAULT 0                                     COMMENT '年龄',
  `profession`        CHAR(100)                                                                         COMMENT '职业',
  `oicq`              CHAR(50)                                                                          COMMENT 'QQ',
  `wechat`            CHAR(50)                                                                          COMMENT '微信号',
  `id_code`           CHAR(100)                           DEFAULT NULL                                  COMMENT '身份证/机构代码',
  `progress`          INT               NOT NULL          DEFAULT 0                                     COMMENT '开发进度',
  `address`           CHAR(255)                           DEFAULT NULL                                  COMMENT '地址',
  `intention`         CHAR(10)                            DEFAULT NULL                                  COMMENT '意向程度',
  `register_date`     DATE                                DEFAULT '0000-00-00'                          COMMENT '登记日期',
  `telphone`          CHAR(50)          NOT NULL          DEFAULT ''                                    COMMENT '固定电话',
  `mobile`            CHAR(20)                            DEFAULT NULL                                  COMMENT '手机号码',
  `status`            CHAR(30)          NOT NULL          DEFAULT 'FIRST_DIAL'                          COMMENT '状态',
  `report`            CHAR(30)                            DEFAULT NULL                                  COMMENT '销售说明',
  `area`              CHAR(50)                            DEFAULT NULL                                  COMMENT '片区',
  `park`              CHAR(50)                            DEFAULT NULL                                  COMMENT '园区',
  `batch`             CHAR(10)          NOT NULL          DEFAULT 'A'                                   COMMENT '批次',
  `type`              CHAR(20)          NOT NULL          DEFAULT 'FIRST_YEAR'                          COMMENT '名单类型',
  `user_attach`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '归属于',
  `user_create`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '创建记录的操作员',
  `user_modify`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '最后一次修改记录的操作员',
  `date_create`       TIMESTAMP         NOT NULL          DEFAULT CURRENT_TIMESTAMP                     COMMENT '记录创建的时间',
  `date_modify`       TIMESTAMP         NOT NULL          DEFAULT '0000-00-00 00:00:00'                 COMMENT '最后一次修改记录的时间',
  `preset_time`       TIMESTAMP         NOT NULL          DEFAULT '0000-00-00 00:00:00'                 COMMENT '预约日期',
  `remark`            TEXT                                                                              COMMENT '备注',
  `deleted`           TINYINT           NOT NULL          DEFAULT 0                                     COMMENT '是否删除',
  `distribute_date`   DATETIME                            DEFAULT '0000-00-00 00:00:00'                 COMMENT '操作时间',
  `distribute_user`   INT(11)                             DEFAULT '-1'                                  COMMENT '操作人员',
  `distribute_option` CHAR(20)                                                                          COMMENT '操作类型',
  `last_user_attach`  INT(11)                             DEFAULT '-1'                                  COMMENT '上次归属于',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `mobile` (`mobile`),
  KEY `id_code` (`id_code`),
  KEY `intention` (`intention`),
  KEY `telphone` (`telphone`),
  KEY `batch` (`batch`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `gender` (`gender`),
  KEY `oicq` (`oicq`),
  KEY `wechat` (`wechat`),
  KEY `progress` (`progress`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`),
  KEY `preset_time` (`preset_time`),
  KEY `deleted` (`deleted`),
  KEY `distribute_date` (`distribute_date`),
  KEY `distribute_user` (`distribute_user`),
  KEY `distribute_option` (`distribute_option`),
  KEY `last_user_attach` (`last_user_attach`),
  KEY `status_user` (`status`,`user_attach`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql ="CREATE TABLE IF NOT EXISTS accounts_financial_seq(
  id              INT DEFAULT 1
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql ="INSERT INTO accounts_financial_seq VALUES(1);";
mysql_query($sql,$conn);

$sql = "CREATE TABLE `progress` (
  `id`                INT(11)           NOT NULL                                                        COMMENT 'ID号',
  `name`              CHAR(100)         NOT NULL                                                        COMMENT '名字',
  `description`       CHAR(200)                                                                         COMMENT '简介',
  `user_attach`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '归属于',
  `user_create`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '创建记录的操作员',
  `user_modify`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '最后一次修改记录的操作员',
  `date_create`       TIMESTAMP         NOT NULL          DEFAULT CURRENT_TIMESTAMP                     COMMENT '记录创建的时间',
  `date_modify`       TIMESTAMP         NOT NULL          DEFAULT '0000-00-00 00:00:00'                 COMMENT '最后一次修改记录的时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql ="CREATE TABLE IF NOT EXISTS progress_seq(
  id              INT DEFAULT 1
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql ="INSERT INTO progress_seq VALUES(1);";
mysql_query($sql,$conn);

$sql="CREATE TABLE `express` (
  `id`                INT(11)           NOT NULL                                                        COMMENT 'ID号',
  `name`              CHAR(100)         NOT NULL                                                        COMMENT '名字',
  `description`       CHAR(200)                                                                         COMMENT '简介',
  `user_attach`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '归属于',
  `user_create`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '创建记录的操作员',
  `user_modify`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '最后一次修改记录的操作员',
  `date_create`       TIMESTAMP         NOT NULL          DEFAULT CURRENT_TIMESTAMP                     COMMENT '记录创建的时间',
  `date_modify`       TIMESTAMP         NOT NULL          DEFAULT '0000-00-00 00:00:00'                 COMMENT '最后一次修改记录的时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE IF NOT EXISTS express_seq(
  id              INT DEFAULT 1
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO express_seq VALUES(1);";
mysql_query($sql,$conn);


$sql="CREATE TABLE `courier` (
  `id`                INT(11)           NOT NULL                                                        COMMENT 'ID号',
  `name`              CHAR(100)         NOT NULL                                                        COMMENT '名字',
  `mobile`            CHAR(20)                            DEFAULT NULL                                  COMMENT '手机号码',
  `express`           INT               NOT NULL                                                        COMMENT '快递公司',
  `description`       CHAR(200)                                                                         COMMENT '简介',
  `user_attach`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '归属于',
  `user_create`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '创建记录的操作员',
  `user_modify`       INT(11)           NOT NULL          DEFAULT '-1'                                  COMMENT '最后一次修改记录的操作员',
  `date_create`       TIMESTAMP         NOT NULL          DEFAULT CURRENT_TIMESTAMP                     COMMENT '记录创建的时间',
  `date_modify`       TIMESTAMP         NOT NULL          DEFAULT '0000-00-00 00:00:00'                 COMMENT '最后一次修改记录的时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `user_attach` (`user_attach`),
  KEY `user_create` (`user_create`),
  KEY `user_modify` (`user_modify`),
  KEY `date_create` (`date_create`),
  KEY `date_modify` (`date_modify`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql="CREATE TABLE IF NOT EXISTS courier_seq(
  id              INT DEFAULT 1
)ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql="INSERT INTO courier_seq VALUES(1);";
mysql_query($sql,$conn);

$sql = "CREATE TABLE match_cron_state(
cron_name                        CHAR(50)          NOT NULL ,
last_time                        INT               NOT NULL DEFAULT 0,
state                            INT               NOT NULL DEFAULT 0
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);
$sql="INSERT INTO match_cron_state(cron_name,last_time,state) VALUES('match_client','0000-00-00 00:00:00',0);";
mysql_query($sql,$conn);


$sql ="CREATE TABLE insurance_order_com(
`id`                               INT UNSIGNED      NOT NULL,
`order_no`                         CHAR(20)          NOT NULL,
`holder`                           CHAR(100)         NOT NULL,
`license_no`                       CHAR(20)          NOT NULL,
`vin_no`                           CHAR(50)          NOT NULL,
`pre_business_no`                  CHAR(50),
`business_policy_no`               CHAR(50)          NOT NULL,
`business_discount_premium`        FLOAT             DEFAULT 0,
`business_custom_discount`         FLOAT             DEFAULT 1,
`business_discount`                FLOAT             DEFAULT 1,
`business_premium`                 FLOAT             DEFAULT 0,
`business_end_time`                TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
`business_start_time`              TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
`pre_mvtalci_no`                   CHAR(50),
`mvtalci_policy_no`                CHAR(50)          NOT NULL,
`mvtalci_discount`                 FLOAT             DEFAULT 1,
`mvtalci_premium`                  FLOAT             DEFAULT 0,
`mvtalci_end_time`                 TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
`mvtalci_start_time`               TIMESTAMP         DEFAULT '0000-00-00 00:00:00',
`mvtalci_notice_no`                CHAR(50),
`business_notice_no`               CHAR(50),
`insurance_company`                CHAR(20)          DEFAULT '',
`travel_tax_premium`               FLOAT             DEFAULT 0,
`total_premium`                    FLOAT             DEFAULT 0,
`advance_premium`                  FLOAT             DEFAULT 0,
`total_receivable_amount`          FLOAT             DEFAULT 0,
`receiver`                         CHAR(50)          NOT NULL DEFAULT '',
`receiver_mobile`                  CHAR(50)          NOT NULL DEFAULT '',
`receiver_addr`                    CHAR(255)         NOT NULL,
`case_id`                          INT               NOT NULL,
`status`                           CHAR(20)          NOT NULL,
`gift_total_price`                 DECIMAL           DEFAULT 0,
`gift`                             TEXT,
`remarks`                          CHAR(255)         DEFAULT '',
`auditor`                          CHAR(20)          DEFAULT '',
`model`                            CHAR(50)          DEFAULT NULL,
`engine_no`                        CHAR(50)          DEFAULT '',
`engine`                           FLOAT             DEFAULT '0',
`enroll_date`                      DATE              DEFAULT '0000-00-00',
`submit_time`                      TIMESTAMP         NOT NULL DEFAULT  '0000-00-00 00:00:00',
`print_time`                       TIMESTAMP         NOT NULL DEFAULT  '0000-00-00 00:00:00',
`complete_time`                    TIMESTAMP         NOT NULL DEFAULT  '0000-00-00 00:00:00',
`create_time`                      TIMESTAMP         NOT NULL DEFAULT  CURRENT_TIMESTAMP,
`create_userid`                    INT               NOT NULL DEFAULT 1,
`create_user`                      CHAR(50)          DEFAULT '',
`user_mobile`                  	   CHAR(50)          DEFAULT '',
`leader_name`                      CHAR(50)          DEFAULT '',
`leader_mobile`                    CHAR(50)          DEFAULT '',
`send_time`                        DATETIME          DEFAULT '0000-00-00 00:00:00',
`require_time`                     DATETIME          DEFAULT '0000-00-00 00:00:00',
`express`                          INT               DEFAULT 0,
`courier`                          INT               DEFAULT 0,
`courier_mobile`                   CHAR(20)          DEFAULT '',
`waybill_number`                   CHAR(50)          DEFAULT '',
`sign`							   CHAR(50) 		 DEFAULT '',
`sign_time`                        DATETIME          DEFAULT '0000-00-00 00:00:00',
`payment_type`					   CHAR(20) 		 DEFAULT '',
PRIMARY KEY(`id`),
KEY(`order_no`),
KEY(`vin_no`),
KEY(`license_no`)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
mysql_query($sql,$conn);

$sql = "CREATE TABLE insurance_order_com_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql ="insert into insurance_order_com_seq(id) values(100000000);";
mysql_query($sql,$conn);


/* 商品库存 */
$sql = "CREATE TABLE  product(
`id`                               INT UNSIGNED      NOT NULL,
`code`                             CHAR(20)          NOT NULL,
`name`                             CHAR(250)         NOT NULL,
`standard`                         CHAR(250)         DEFAULT '',
`price`                            FLOAT             NOT NULL,
`count`                            INT               NOT NULL,
`repertory`                        CHAR(100)         NOT NULL,
`class`                            CHAR(20)          NOT NULL,
`state`                            CHAR(20)          NOT NULL,
`create_time`                      DATETIME          NOT  NULL DEFAULT '0000-00-00 00:00:00',
`create_userid`                    INT               NOT  NULL DEFAULT -1,
`modify_time`                      DATETIME          NOT  NULL DEFAULT '0000-00-00 00:00:00',
`modify_userid`                    INT               NOT  NULL DEFAULT -1,
PRIMARY KEY (`id`),
KEY(`code`),
KEY(`class`),
KEY(`state`),
KEY(`create_time`),
KEY(`create_userid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
mysql_query($sql,$conn);

$sql = "CREATE  TABLE  product_seq(
`id`                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql ="INSERT INTO  product_seq(id) values(1);";
mysql_query($sql,$conn);

$sql = "CREATE TABLE blocklist(
`id`                               INT UNSIGNED      NOT  NULL,
`phone_number`                     CHAR(50)          NOT  NULL,
`descr`                            CHAR(255)         DEFAULT '',
`match_type`                       CHAR(20)          DEFAULT 'SUFFIX', /*FULL | PREFIX | SUFFIX*/
`create_time`                      DATETIME          NOT  NULL DEFAULT '0000-00-00 00:00:00',
`create_userid`                    INT               NOT  NULL DEFAULT -1,
`modify_time`                      DATETIME          NOT  NULL DEFAULT '0000-00-00 00:00:00',
`modify_userid`                    INT               NOT  NULL DEFAULT -1,
PRIMARY KEY (id),
KEY(phone_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";

mysql_query($sql,$conn);


$sql = "CREATE  TABLE  blocklist_seq(
id                   INT NOT NULL
)ENGINE = MYISAM  CHARACTER SET = UTF8;";
mysql_query($sql,$conn);

$sql = "INSERT INTO  blocklist_seq(id) values(1);";
mysql_query($sql,$conn);



?>