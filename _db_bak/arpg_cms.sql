/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50612
 Source Host           : localhost
 Source Database       : arpg_cms

 Target Server Type    : MySQL
 Target Server Version : 50612
 File Encoding         : utf-8

 Date: 08/30/2014 12:49:24 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `arpg_cms_article`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_cms_article`;
CREATE TABLE `arpg_cms_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) unsigned NOT NULL,
  `title` varchar(40) NOT NULL COMMENT '文章标题',
  `cover_img` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `content` text NOT NULL COMMENT '正文内容',
  `created_uid` int(11) NOT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `app` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1正常 2删除 3审核',
  `comment_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `view_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数',
  `tags` varchar(255) NOT NULL DEFAULT '' COMMENT '标签',
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`,`created_uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_cms_article`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_cms_article` VALUES ('1', '1', '欢迎来到 ARPG CMS', '', '<p>&nbsp;&nbsp;&nbsp;&nbsp;欢迎来到 ARPG CMS, 这是你的第一篇文章！</p>', '1', '1409373343', '1', '0', '0', 'CMS,');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_cms_category`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_cms_category`;
CREATE TABLE `arpg_cms_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(15) NOT NULL,
  `upid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `app` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可见 0 不可见 1可见',
  `path_name` varchar(25) NOT NULL COMMENT '域名PATH',
  PRIMARY KEY (`id`),
  KEY `upid` (`upid`,`path_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_cms_category`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_cms_category` VALUES ('1', '默认分类', '0', '0', '1', 'category');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_cms_comment`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_cms_comment`;
CREATE TABLE `arpg_cms_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(200) NOT NULL COMMENT '评论内容 200字内',
  `created_uid` int(11) unsigned NOT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `created_ip` char(15) NOT NULL DEFAULT '127.0.0.1' COMMENT '发布者IP',
  `re_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级评论',
  `app` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `aid` int(11) NOT NULL COMMENT '文章ID',
  PRIMARY KEY (`id`),
  KEY `created_uid` (`created_uid`,`re_id`,`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `arpg_members`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_members`;
CREATE TABLE `arpg_members` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `email` char(40) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `company_id` int(11) NOT NULL DEFAULT '0' COMMENT '0个人  商家ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groupid` tinyint(1) unsigned NOT NULL DEFAULT '6' COMMENT '6 个人',
  `question` tinyint(1) NOT NULL DEFAULT '0',
  `answer` varchar(255) CHARACTER SET gbk NOT NULL DEFAULT '',
  `regip` char(15) CHARACTER SET gbk NOT NULL COMMENT '注册IP地址',
  `regdate` int(10) NOT NULL COMMENT '注册日期',
  `forgot_salt` varchar(255) CHARACTER SET gbk NOT NULL DEFAULT '' COMMENT 'SALT for forgot password',
  `forgot_date` int(10) NOT NULL DEFAULT '0' COMMENT '找回时间',
  `fotgot_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '找回状态  一次有效',
  `ban` tinyint(1) NOT NULL DEFAULT '1' COMMENT '2为BAN 1正常',
  PRIMARY KEY (`uid`),
  KEY `status` (`status`,`groupid`,`ban`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_members`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_members` VALUES ('1', 'admin', '', '21232f297a57a5a743894a0e4a801fc3', '', '0', '1', '2', '0', '', '127.0.0.1', '1', '', '0', '0', '1');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_members_competence`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_members_competence`;
CREATE TABLE `arpg_members_competence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(50) NOT NULL COMMENT '权限描述',
  `action` varchar(100) NOT NULL DEFAULT '',
  `upid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `action` (`action`,`upid`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_members_competence`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_members_competence` VALUES ('1', '权限设置', 'Set_Set_competence', '41'), ('53', 'AJAX_POST', 'Set_Ajax_setReginfo', '52'), ('4', '角色管理', 'Set_Set_group', '41'), ('5', '会员管理', 'Set_Set_user', '41'), ('6', '添加/编辑 权限', 'Set_Dialog_editCompetence', '1'), ('25', 'AJAX_POST', 'Set_Ajax_setEditCompetence', '6'), ('26', '添加/编辑 角色', 'Set_Dialog_editGroup', '4'), ('27', 'AJAX_POST', 'Set_Ajax_setEditGroup', '26'), ('30', '删除 权限', 'Set_Ajax_setDelCompetence', '1'), ('29', '删除 角色', 'Set_Ajax_setDelGroup', '4'), ('31', '添加 会员', 'Set_Dialog_addUser', '5'), ('32', '编辑 会员', 'Set_Dialog_editUser', '5'), ('33', '禁止/恢复 会员', 'Set_Ajax_setBan', '5'), ('34', 'AJAX_POST', 'Set_Ajax_setAddUser', '31'), ('35', 'AJAX_POST', 'Set_Ajax_setEditUser', '32'), ('36', '网站设置', 'Set_Set_setting', '41'), ('37', '导航设置', 'Set_Set_nav', '42'), ('38', '添加/编辑 导航', 'Set_Dialog_editNav', '37'), ('39', 'AJAX_POST', 'Set_Ajax_setEditNav', '38'), ('40', '批量编辑 导航', 'Set_Ajax_setEditAllNav', '37'), ('41', '全局', 'setPart1', '0'), ('42', '界面', 'setPart2', '0'), ('43', '主题管理', 'Set_Set_themes', '42'), ('44', '添加 新主题', 'Set_Dialog_addThemes', '43'), ('45', 'AJAX_POST', 'Set_Ajax_setAddThemes', '44'), ('46', '清理缓存', 'Set_Dialog_rmCache', '78'), ('47', 'AJAX_POST', 'Set_Ajax_rmCache', '46'), ('48', '批量编辑 主题', 'Set_Ajax_setEditThemes', '43'), ('70', '管理中心入口', 'Set_Set_index', '0'), ('50', 'AJAX_POST', 'Set_Ajax_setSetting', '36'), ('51', '全站超级访问权限', 'Common_view', '0'), ('52', '注册控制', 'Set_Set_reginfo', '41'), ('54', '内容', 'setPart3', '0'), ('55', '分类管理', 'Set_Set_category', '54'), ('56', '文章管理', 'Set_Set_article', '54'), ('57', '添加/编辑 分类', 'Set_Dialog_editCategory', '55'), ('58', 'AJAX_POST', 'Set_Ajax_setEditCategory', '57'), ('59', '批量删除 分类', 'Set_Ajax_setEditAllCat', '55'), ('60', '添加/编辑 文章', 'Set_Set_editArticle', '56'), ('61', 'AJAX_POST', 'Set_Ajax_setEditArticle', '60'), ('62', '批量操作 文章', 'Set_Ajax_setBatchArticle', '56'), ('63', '上传封面图片', 'Set_Dialog_articleUpImg', '60'), ('64', '正在上传动画显示...', 'Set_Dialog_uploading', '54'), ('65', '开始上传', 'Set_Ajax_uploadImg', '63'), ('66', '切割/处理图片', 'Set_Ajax_cutImg', '65'), ('67', '评论管理', 'Set_Set_comment', '54'), ('68', '批量操作 评论', 'Set_Ajax_setBatchComment', '67'), ('69', '文章管理 超级权限', 'Common_article', '56'), ('71', '滚动图片', 'Set_Set_slide', '42'), ('72', '上传图片', 'Set_Ajax_uploadSlide', '71'), ('73', '批量操作 滚动图片', 'Set_Ajax_setEditAllSlide', '71'), ('75', '接口', 'setPart4', '0'), ('76', 'OSS上传设置', 'Set_Set_oss', '75'), ('77', '更新设置 OSS', 'Set_Ajax_setOss', '76'), ('78', '工具', 'setPart5', '0');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_members_group`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_members_group`;
CREATE TABLE `arpg_members_group` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `value` char(15) NOT NULL DEFAULT '',
  `note` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `in_actions` text NOT NULL COMMENT '允许的操作S',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_members_group`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_members_group` VALUES ('1', '注册会员', '注册组禁删', 'a:1:{i:0;s:9:\"s_service\";}'), ('2', '系统管理员', '', 'a:55:{i:0;s:8:\"setPart1\";i:1;s:18:\"Set_Set_competence\";i:2;s:25:\"Set_Dialog_editCompetence\";i:3;s:26:\"Set_Ajax_setEditCompetence\";i:4;s:25:\"Set_Ajax_setDelCompetence\";i:5;s:13:\"Set_Set_group\";i:6;s:20:\"Set_Dialog_editGroup\";i:7;s:21:\"Set_Ajax_setEditGroup\";i:8;s:20:\"Set_Ajax_setDelGroup\";i:9;s:12:\"Set_Set_user\";i:10;s:18:\"Set_Dialog_addUser\";i:11;s:19:\"Set_Ajax_setAddUser\";i:12;s:19:\"Set_Dialog_editUser\";i:13;s:20:\"Set_Ajax_setEditUser\";i:14;s:15:\"Set_Ajax_setBan\";i:15;s:15:\"Set_Set_setting\";i:16;s:19:\"Set_Ajax_setSetting\";i:17;s:15:\"Set_Set_reginfo\";i:18;s:19:\"Set_Ajax_setReginfo\";i:19;s:8:\"setPart2\";i:20;s:11:\"Set_Set_nav\";i:21;s:18:\"Set_Dialog_editNav\";i:22;s:19:\"Set_Ajax_setEditNav\";i:23;s:22:\"Set_Ajax_setEditAllNav\";i:24;s:14:\"Set_Set_themes\";i:25;s:20:\"Set_Dialog_addThemes\";i:26;s:21:\"Set_Ajax_setAddThemes\";i:27;s:22:\"Set_Ajax_setEditThemes\";i:28;s:13:\"Set_Set_slide\";i:29;s:20:\"Set_Ajax_uploadSlide\";i:30;s:24:\"Set_Ajax_setEditAllSlide\";i:31;s:11:\"Common_view\";i:32;s:8:\"setPart3\";i:33;s:16:\"Set_Set_category\";i:34;s:23:\"Set_Dialog_editCategory\";i:35;s:24:\"Set_Ajax_setEditCategory\";i:36;s:22:\"Set_Ajax_setEditAllCat\";i:37;s:15:\"Set_Set_article\";i:38;s:19:\"Set_Set_editArticle\";i:39;s:23:\"Set_Ajax_setEditArticle\";i:40;s:23:\"Set_Dialog_articleUpImg\";i:41;s:18:\"Set_Ajax_uploadImg\";i:42;s:15:\"Set_Ajax_cutImg\";i:43;s:24:\"Set_Ajax_setBatchArticle\";i:44;s:14:\"Common_article\";i:45;s:20:\"Set_Dialog_uploading\";i:46;s:15:\"Set_Set_comment\";i:47;s:24:\"Set_Ajax_setBatchComment\";i:48;s:13:\"Set_Set_index\";i:49;s:8:\"setPart4\";i:50;s:11:\"Set_Set_oss\";i:51;s:15:\"Set_Ajax_setOss\";i:52;s:8:\"setPart5\";i:53;s:18:\"Set_Dialog_rmCache\";i:54;s:16:\"Set_Ajax_rmCache\";}');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_members_loginlog`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_members_loginlog`;
CREATE TABLE `arpg_members_loginlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `loginip` char(15) NOT NULL,
  `logindate` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_members_loginlog`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_members_loginlog` VALUES ('1', '1', '127.0.0.1', '1409373201');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_members_profile`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_members_profile`;
CREATE TABLE `arpg_members_profile` (
  `uid` int(11) NOT NULL,
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 1男 2女',
  `country_id` int(11) NOT NULL DEFAULT '-1' COMMENT '国家',
  `country_name` char(10) NOT NULL DEFAULT '未知区域' COMMENT '国家',
  `province_id` int(11) NOT NULL DEFAULT '0' COMMENT '省',
  `province_name` char(10) NOT NULL DEFAULT '' COMMENT '省',
  `city_id` int(11) NOT NULL DEFAULT '0' COMMENT '城市',
  `city_name` char(10) NOT NULL DEFAULT '' COMMENT '市',
  `age` int(2) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
  `fm` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_members_profile`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_members_profile` VALUES ('1', '管理员', '1', '-1', '未知区域', '0', '', '0', '', '1', '');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_members_status`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_members_status`;
CREATE TABLE `arpg_members_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `work_day` int(11) NOT NULL DEFAULT '0' COMMENT '当前天',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `arpg_service_nav`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_service_nav`;
CREATE TABLE `arpg_service_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(20) NOT NULL COMMENT '导航名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '导航的 TITLE',
  `link` varchar(255) NOT NULL COMMENT '导航地址',
  `upid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `app` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为启用 0不启用',
  `sort` tinyint(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 新窗口打开 0 本窗口打开',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  PRIMARY KEY (`id`),
  KEY `upid` (`upid`,`level`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_service_nav`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_service_nav` VALUES ('1', '首页', '', '/', '0', '1', '0', '0', '0'), ('2', '默认分类', '', '/category', '0', '1', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_service_reginfo`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_service_reginfo`;
CREATE TABLE `arpg_service_reginfo` (
  `id` int(11) unsigned NOT NULL,
  `username_true` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `username_reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `username_must` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email_true` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email_reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email_must` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phone_true` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phone_reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phone_must` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `realname_true` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `realname_reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `realname_must` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gender_true` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gender_reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gender_must` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `age_true` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `age_reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `age_must` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `area_true` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `area_reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `area_must` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_service_reginfo`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_service_reginfo` VALUES ('1', '1', '0', '0', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '0', '1', '1', '0', '1', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_service_setting`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_service_setting`;
CREATE TABLE `arpg_service_setting` (
  `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(50) NOT NULL,
  `site_url` varchar(100) NOT NULL,
  `mail_server` varchar(100) NOT NULL,
  `mail_username` varchar(20) NOT NULL,
  `mail_password` varchar(20) NOT NULL,
  `img_url` varchar(25) NOT NULL,
  `img_avatar` varchar(25) NOT NULL,
  `analytics` varchar(255) NOT NULL,
  `siteopen` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 开启网站  1关闭网站',
  `site_themes` varchar(15) NOT NULL DEFAULT 'default' COMMENT '默认模板',
  `site_home` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '导航首页',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_service_setting`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_service_setting` VALUES ('1', 'ARPG内容管理系统', 'http://127.0.0.1:9982', 'smtp.exmail.qq.com', 'members@ripppple.com', 'AyFW6nsD', '/uploads/img/', '/uploads/avatar/', '', '1', 'default', '1');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_service_slide`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_service_slide`;
CREATE TABLE `arpg_service_slide` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `img_url` varchar(255) NOT NULL COMMENT '图片路径',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1为新窗口',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_service_slide`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_service_slide` VALUES ('1', '/uploads/img/2014/08/30/14093740871459.jpg', '/article/1/欢迎来到%20ARPG%20CMS', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `arpg_service_themes`
-- ----------------------------
DROP TABLE IF EXISTS `arpg_service_themes`;
CREATE TABLE `arpg_service_themes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL COMMENT '主题名称 ',
  `path_name` varchar(15) NOT NULL COMMENT '主题 文件夹名称 ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `arpg_service_themes`
-- ----------------------------
BEGIN;
INSERT INTO `arpg_service_themes` VALUES ('1', '默认主题', 'default');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
