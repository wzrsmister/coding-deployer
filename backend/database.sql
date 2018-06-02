/*
DROP TABLE IF EXISTS `cd_user`;
CREATE TABLE `cd_user`(
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '用户表';
*/

#用户表
DROP TABLE IF EXISTS `cd_user`;
CREATE TABLE `cd_user`(
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creater_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` char(40) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `login_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `creater_id` (`creater_id`),
  KEY `name` (`name`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '用户表';

#机房表
DROP TABLE IF EXISTS `cd_server_room`;
CREATE TABLE `cd_server_room`(
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creater_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `creater_id` (`creater_id`),
  KEY `name` (`name`),
  KEY `type` (`type`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '机房表';

#服务器分组表
DROP TABLE IF EXISTS `cd_server_group`;
CREATE TABLE `cd_server_group`(
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creater_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `creater_id` (`creater_id`),
  KEY `name` (`name`),
  KEY `type` (`type`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '服务器分组表';

#服务器表
DROP TABLE IF EXISTS `cd_server`;
CREATE TABLE `cd_server` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '机房',
  `creater_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT '外网',
  `inner_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '内网',
  `ssh_private_key` varchar(1000) NOT NULL DEFAULT '' COMMENT '',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `level` varchar(50) NOT NULL DEFAULT '' COMMENT '级别',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `creater_id` (`creater_id`),
  KEY `room_id` (`room_id`),
  KEY `name` (`name`),
  KEY `ip` (`ip`),
  KEY `inner_ip` (`inner_ip`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `level` (`level`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '服务器表';


#项目表分组表
DROP TABLE IF EXISTS `cd_project_group`;
CREATE TABLE `cd_project_group`(
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creater_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `creater_id` (`creater_id`),
  KEY `name` (`name`),
  KEY `type` (`type`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '项目表分组表';

#项目表
DROP TABLE IF EXISTS `cd_project`;
CREATE TABLE `cd_project`(
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creater_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `project_group_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '',
  `repo_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:git,2:svn',
  `repo_address` varchar(1000) NOT NULL DEFAULT '' COMMENT '',
  `repo_account` varchar(255) NOT NULL DEFAULT '' COMMENT '',
  `repo_password` varchar(255) NOT NULL DEFAULT '' COMMENT '',
  `repo_private_key` varchar(1000) NOT NULL DEFAULT '' COMMENT '',
  `repo_branch` varchar(50) NOT NULL DEFAULT '' COMMENT '',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `published_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '',
  `order` int(10) unsigned NOT NULL DEFAULT 0,
  `remark` text COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `creater_id` (`creater_id`),
  KEY `project_group_id` (`project_group_id`),
  KEY `name` (`name`),
  KEY `type` (`type`),
  KEY `repo_type` (`repo_type`),
  KEY `published_at` (`published_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '项目表';

