CREATE TABLE policy_draft(
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
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE policy_draft_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;
insert into policy_draft_seq(id) values(2);

CREATE TABLE policy_calculate_setting(
	setting                      TEXT
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE policy_draft_com(
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
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE policy_draft_com_seq(
	id             INT DEFAULT 1
)  ENGINE = MYISAM CHARACTER SET = UTF8;
insert into policy_draft_com_seq(id) values(2);


alter table users  add guid                     CHAR(40)  DEFAULT '';
alter table groups add guid                     CHAR(40)  DEFAULT '';

CREATE TABLE dropdown(
	id                  INT        NOT NULL AUTO_INCREMENT,
	module_name         CHAR(50)   NOT NULL, 
	field               CHAR(50)   NOT NULL,
	save_value          CHAR(255)  NOT NULL,
	show_value          CHAR(255)  NOT NULL,
	group_name          CHAR(50)   NOT NULL DEFAULT '',
    PRIMARY KEY(id),
	KEY(module_name,field)
)ENGINE = MYISAM  CHARACTER SET = UTF8;
CREATE  TABLE dropdown_seq(
	id                   INT NOT NULL 
)ENGINE = MYISAM  CHARACTER SET = UTF8;
INSERT INTO dropdown_seq(id) values(1);

CREATE TABLE  insurance_account_cli(
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
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE  TABLE  insurance_account_cli_seq(
id                   INT NOT NULL 
)ENGINE = MYISAM  CHARACTER SET = UTF8;
INSERT INTO insurance_account_cli_seq(id) values(1);

CREATE TABLE list_data(
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
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE  TABLE  list_data_seq(
id                   INT NOT NULL 
)ENGINE = MYISAM  CHARACTER SET = UTF8;
INSERT INTO list_data_seq(id) values(1);


CREATE TABLE list_match(
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
)ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE  TABLE  list_match_seq(
	id                   INT NOT NULL 
)ENGINE = MYISAM  CHARACTER SET = UTF8;
INSERT INTO  list_match_seq(id) values(1);