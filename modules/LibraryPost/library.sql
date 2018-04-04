SET NAMES latin1;

DROP TABLE IF EXISTS `library_post`;
CREATE TABLE `library_post` (
    `id`              INT(11)     NOT NULL,
    `title`           CHAR(200)   NOT NULL                                     COMMENT '标题',
    `content`         LONGTEXT                                                 COMMENT '内容',
    `categoryid`      INT(11)     NOT NULL                                     COMMENT '分类id',
    `status`          CHAR(10)    NOT NULL      DEFAULT 'PUBLISH'              COMMENT '文章状态',
    `user_attach`     INT(11)     NOT NULL      DEFAULT '-1'                   COMMENT '归属组/用户',
    `user_create`     INT(11)     NOT NULL      DEFAULT '-1'                   COMMENT '创建人',
    `user_modify`     INT(11)     NOT NULL      DEFAULT '-1'                   COMMENT '修改人',
    `date_create`     TIMESTAMP   NOT NULL      DEFAULT CURRENT_TIMESTAMP      COMMENT '创建时间',
    `date_modify`     TIMESTAMP   NOT NULL      DEFAULT '0000-00-00 00:00:00'  COMMENT '修改时间',
    PRIMARY KEY (`id`),
    KEY (`title`),
    KEY (`categoryid`),
    KEY (`status`),
    KEY (`user_create`),
    KEY (`user_modify`),
    KEY (`date_create`),
    KEY (`date_modify`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `library_post_seq`;
CREATE TABLE `library_post_seq` (
  `id`          INT(11)     DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO library_post_seq VALUES(1);



DROP TABLE IF EXISTS `library_category`;
CREATE TABLE `library_category` (
    `id`              INT(11)     NOT NULL,
    `name`            CHAR(100)   NOT NULL                                     COMMENT '分类名称',
    `parent`          INT(11)     NOT NULL      DEFAULT 0                      COMMENT '父级id',
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`),
    KEY (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `library_category_seq`;
CREATE TABLE `library_category_seq` (
  `id`          INT(11)     DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO library_category_seq VALUES(1);