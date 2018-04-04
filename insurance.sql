/*保单算价记录*/
CREATE TABLE policy_draft(
id                               INT UNSIGNED      NOT NULL,
cal_no                           CHAR(50)          NOT NULL,                                      /*编号*/
vin_no                           CHAR(50)          NOT NULL,                                      /*车辆识别代码*/ 
summary                          CHAR(255)         NOT NULL,                                      /*摘要*/
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
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE policy_draft_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;
insert into policy_draft_seq(id) values(2);


/*保单算价设置*/
CREATE TABLE policy_calculate_setting(
	setting                      TEXT
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE policy_draft_com(
id                               INT UNSIGNED      NOT NULL,
cal_no                           CHAR(50)          NOT NULL,                                      /*编号*/
vin_no                           CHAR(50)          NOT NULL,                                      /*车辆识别代码*/ 
summary                          CHAR(255)         NOT NULL,                                      /*摘要*/
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
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE policy_draft_com_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;
insert into policy_draft_com_seq(id) values(2);


/*保险订单*/
CREATE TABLE insurance_order(
id                               INT UNSIGNED      NOT NULL,
order_no                         CHAR(20)          NOT NULL,                                      /*订单号*/
holder                           CHAR(100)         NOT NULL,                                      /*客户名称*/
license_no                       CHAR(20)          NOT NULL,                                      /*车牌号*/ 
vin_no                           CHAR(50)          NOT NULL,                                      /*车辆识别代码*/
business_policy_no               CHAR(50)          NOT NULL,                                      /*商业险保单号*/
business_standard_premium        FLOAT             DEFAULT 0,                                     /*商业险标准保费*/
business_discount                FLOAT             DEFAULT 1,                                     /*商业险折扣*/
business_premium                 FLOAT             DEFAULT 0,                                     /*商业险折后保费*/
business_end_time                TIMESTAMP         DEFAULT '0000-00-00 00:00:00',                 /*商业险终保日期*/
business_start_time              TIMESTAMP         DEFAULT '0000-00-00 00:00:00',                 /*商业险生效日期*/
mvtalci_policy_no                CHAR(50)          NOT NULL,                                      /*交强险保单号*/
mvtalci_standard_premium         FLOAT             DEFAULT 0,                                     /*交强险标准保费*/
mvtalci_discount                 FLOAT             DEFAULT 1,                                     /*交强险折扣*/
mvtalci_premium                  FLOAT             DEFAULT 0,                                     /*交强险折后保费*/
mvtalci_end_time                 TIMESTAMP         DEFAULT '0000-00-00 00:00:00',                 /*交强险终保日期*/
mvtalci_start_time               TIMESTAMP         DEFAULT '0000-00-00 00:00:00',                 /*交强险生效日期*/
insurance_company                CHAR(20)          DEFAULT '',                                    /*保险公司*/
travel_tax_premium               FLOAT             DEFAULT 0,                                     /*车船税*/
total_premium                    FLOAT             DEFAULT 0,                                     /*应收款合计*/
total_receivable_amount          FLOAT             DEFAULT 0,                                     /*实收款*/
receiver                         CHAR(50)          NOT NULL DEFAULT '',                           /*收件人*/
receiver_mobile                  CHAR(50)          NOT NULL DEFAULT '',                           /*联系电话*/
receiver_addr                    CHAR(255)         NOT NULL,                                      /*收件地址*/
case_id                          INT               NOT NULL,                                      /*流程流水号*/
status                           CHAR(20)          NOT NULL,                                      /*状态*/
gift                             CHAR(255)         DEFAULT '',                                    /*礼品*/
remarks                          CHAR(255)         DEFAULT '',                                    /*备注*/ 
levelrisk                        CHAR(20)          NOT NULL DEFAULT '',                           /*风险级别*/
auditor                          CHAR(20)          DEFAULT '',                                    /*审核人*/   
auditor_levelrisk                CHAR(20)          NOT NULL DEFAULT '',                           /*核定风险级别*/
complete_time                    TIMESTAMP         NOT NULL DEFAULT  '0000-00-00 00:00:00',       /*订单完成时间*/
create_time                      TIMESTAMP         NOT NULL DEFAULT  CURRENT_TIMESTAMP,           /*订单创建时间*/
create_userid                    INT               NOT NULL DEFAULT 1,                            /*订单创建人ID*/
create_user                      CHAR(50)          DEFAULT '',                                    /*业务员*/
PRIMARY KEY(id),
KEY(order_no),
KEY(vin_no),
KEY(license_no)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE insurance_order_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;
insert into insurance_order_seq(id) values(100000000);



/*保险订单*/
CREATE TABLE insurance_order_com(
id                               INT UNSIGNED      NOT NULL,
order_no                         CHAR(20)          NOT NULL,                                      /*订单号*/
holder                           CHAR(100)         NOT NULL,                                      /*客户名称*/
license_no                       CHAR(20)          NOT NULL,                                      /*车牌号*/ 
vin_no                           CHAR(50)          NOT NULL,                                      /*车辆识别代码*/
business_policy_no               CHAR(50)          NOT NULL,                                      /*商业险保单号*/
business_discount_premium        FLOAT             DEFAULT 0,                                     /*商业险折后保费*/
business_custom_discount         FLOAT             DEFAULT 1,                                     /*商业险自定折扣*/
business_discount                FLOAT             DEFAULT 1,                                     /*商业险保司折扣*/
business_premium                 FLOAT             DEFAULT 0,                                     /*商业险保费*/
business_end_time                TIMESTAMP         DEFAULT '0000-00-00 00:00:00',                 /*商业险终保日期*/
business_start_time              TIMESTAMP         DEFAULT '0000-00-00 00:00:00',                 /*商业险生效日期*/
mvtalci_policy_no                CHAR(50)          NOT NULL,                                      /*交强险保单号*/
mvtalci_discount                 FLOAT             DEFAULT 1,                                     /*交强险折扣*/
mvtalci_premium                  FLOAT             DEFAULT 0,                                     /*交强险折后保费*/
mvtalci_end_time                 TIMESTAMP         DEFAULT '0000-00-00 00:00:00',                 /*交强险终保日期*/
mvtalci_start_time               TIMESTAMP         DEFAULT '0000-00-00 00:00:00',                 /*交强险生效日期*/
insurance_company                CHAR(20)          DEFAULT '',                                    /*保险公司*/
travel_tax_premium               FLOAT             DEFAULT 0,                                     /*车船税*/
total_premium                    FLOAT             DEFAULT 0,                                     /*应收款合计*/
total_receivable_amount          FLOAT             DEFAULT 0,                                     /*实收款*/
receiver                         CHAR(50)          NOT NULL DEFAULT '',                           /*收件人*/
receiver_mobile                  CHAR(50)          NOT NULL DEFAULT '',                           /*联系电话*/
receiver_addr                    CHAR(255)         NOT NULL,                                      /*收件地址*/
case_id                          INT               NOT NULL,                                      /*流程流水号*/
status                           CHAR(20)          NOT NULL,                                      /*状态*/
gift                             CHAR(255)         DEFAULT '',                                    /*礼品*/
remarks                          CHAR(255)         DEFAULT '',                                    /*备注*/ 
auditor                          CHAR(20)          DEFAULT '',                                    /*审核人*/   
complete_time                    TIMESTAMP         NOT NULL DEFAULT  '0000-00-00 00:00:00',       /*订单完成时间*/
create_time                      TIMESTAMP         NOT NULL DEFAULT  CURRENT_TIMESTAMP,           /*订单创建时间*/
create_userid                    INT               NOT NULL DEFAULT 1,                            /*订单创建人ID*/
create_user                      CHAR(50)          DEFAULT '',                                    /*业务员*/
PRIMARY KEY(id),
KEY(order_no),
KEY(vin_no),
KEY(license_no)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE insurance_order_com_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;
insert into insurance_order_com_seq(id) values(100000000);


CREATE TABLE receivable_record(
id                               INT UNSIGNED      NOT NULL AUTO_INCREMENT,
order_no                         CHAR(20)          NOT NULL ,                                      /*订单号    */
receivabler                      CHAR(50)          NOT NULL ,                                      /*收款人   */
receivable_amount                FLOAT             NOT NULL DEFAULT 0,                             /*收款金额*/
receivable_time                  TIMESTAMP         NOT NULL ,                                      /*收款时间*/
receivable_type                  CHAR(50)          NOT NULL ,                                      /*收款方式*/
receivable_bank                  CHAR(50)          DEFAULT '' ,                                    /*收款银行*/
receivable_account               CHAR(50)          DEFAULT '' ,                                    /*收款帐号*/
pay_bank                         CHAR(50)          DEFAULT '' ,                                    /*付款银行*/
pay_account                      CHAR(50)          DEFAULT '' ,                                    /*付款帐号*/
pay_user                         CHAR(50)          NOT NULL ,                                      /*付款人   */
PRIMARY KEY(id),
KEY(order_no)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

