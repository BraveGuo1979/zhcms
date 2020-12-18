/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50529
Source Host           : localhost:3306
Source Database       : zpsc

Target Server Type    : MYSQL
Target Server Version : 50529
File Encoding         : 65001

Date: 2020-09-01 02:14:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zhmvc_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_admin_menu`;
CREATE TABLE `zhmvc_admin_menu` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `pid` int(6) DEFAULT NULL,
  `menuname` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_admin_menu
-- ----------------------------
INSERT INTO `zhmvc_admin_menu` VALUES ('1', '0', 'system_', '系统管理', '/manager/admin_config.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('2', '1', 'system_', '系统设置', '/manager/admin_config.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('3', '1', 'system_', '管理员管理', '/manager/admin_master.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('4', '1', 'system_', '模块管理', '/manager/admin_module.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('5', '1', 'system_', '插件属性管理', '/manager/admin_plus_attribute.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('6', '1', 'system_', '插件接口管理', '/manager/admin_plus_interface.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('7', '1', 'system_', '插件管理', '/manager/admin_plus.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('8', '1', 'system_', '模型管理', '/manager/admin_models.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('9', '1', 'system_', '选项分类管理', '/manager/admin_optionsclass.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('10', '1', 'system_', '选项管理', '/manager/admin_options.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('11', '0', 'zpsc_', '照片管理', '/zpsc/admin/admin_zpsc.php');
INSERT INTO `zhmvc_admin_menu` VALUES ('12', '11', 'zpsc_', '照片管理', '/zpsc/admin/admin_zpsc.php');

-- ----------------------------
-- Table structure for zhmvc_config
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_config`;
CREATE TABLE `zhmvc_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '键名称',
  `value` varchar(255) DEFAULT NULL COMMENT '键值',
  `description` tinytext COMMENT '键描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- ----------------------------
-- Records of zhmvc_config
-- ----------------------------
INSERT INTO `zhmvc_config` VALUES ('1', 'SiteName', 'ZH猎奇', null);
INSERT INTO `zhmvc_config` VALUES ('2', 'SiteUrl', 'http://www.zhmvc.com/', null);
INSERT INTO `zhmvc_config` VALUES ('3', 'SiteQQ', '835197167', null);
INSERT INTO `zhmvc_config` VALUES ('4', 'SiteEmail', '835197167@qq.com', null);
INSERT INTO `zhmvc_config` VALUES ('5', 'SiteTel', '15309315389', null);
INSERT INTO `zhmvc_config` VALUES ('6', 'SiteBeian', '陇ICP备14000080号-2', null);
INSERT INTO `zhmvc_config` VALUES ('7', 'SiteLogo', '/upload/2018/10/10/20181010658593246.png', null);
INSERT INTO `zhmvc_config` VALUES ('8', 'SiteCity', '兰州', null);
INSERT INTO `zhmvc_config` VALUES ('9', 'SiteStat', '', null);
INSERT INTO `zhmvc_config` VALUES ('10', 'SiteSeoName', 'ZH猎奇', null);
INSERT INTO `zhmvc_config` VALUES ('11', 'SiteKeywords', 'ZH猎奇,猎奇网,猎奇天下,奇闻异事,世界未解之谜,猎奇谜,ufo', null);
INSERT INTO `zhmvc_config` VALUES ('12', 'SiteDescription', 'ZH猎奇，天下奇闻异事一网猎尽，汇集全球UFO事件、灵异事件、女尸图片、谜案追踪、军情解码、考古发现。精选热门奇异图片垒砌庞大的猎奇图库，为您带来无尽的视觉盛宴。', null);
INSERT INTO `zhmvc_config` VALUES ('13', 'cfg_if_rewrite', '0', null);
INSERT INTO `zhmvc_config` VALUES ('14', 'cfg_tpl_dir', '', null);
INSERT INTO `zhmvc_config` VALUES ('15', 'cfg_if_site_open', '0', null);
INSERT INTO `zhmvc_config` VALUES ('16', 'cfg_site_open_reason', '', null);
INSERT INTO `zhmvc_config` VALUES ('17', 'cfg_page_line', '', null);
INSERT INTO `zhmvc_config` VALUES ('18', 'cfg_backup_dir', '', null);
INSERT INTO `zhmvc_config` VALUES ('19', 'cfg_join_othersys', '', null);
INSERT INTO `zhmvc_config` VALUES ('20', 'cfg_editor', '1', null);
INSERT INTO `zhmvc_config` VALUES ('21', 'cfg_if_member_register', '0', null);
INSERT INTO `zhmvc_config` VALUES ('22', 'cfg_if_member_log_in', '0', null);
INSERT INTO `zhmvc_config` VALUES ('23', 'cfg_member_perpost_consume', '0', null);
INSERT INTO `zhmvc_config` VALUES ('24', 'cfg_member_upgrade_index_top', '0', null);
INSERT INTO `zhmvc_config` VALUES ('25', 'cfg_member_upgrade_list_top', '0', null);
INSERT INTO `zhmvc_config` VALUES ('26', 'cfg_upimg_type', 'jpg,gif', null);
INSERT INTO `zhmvc_config` VALUES ('27', 'cfg_upimg_size', '100', null);
INSERT INTO `zhmvc_config` VALUES ('28', 'cfg_upimg_watermark', '0', null);
INSERT INTO `zhmvc_config` VALUES ('29', 'cfg_upimg_watermark_value', 'ZH猎奇', null);
INSERT INTO `zhmvc_config` VALUES ('30', 'cfg_upimg_watermark_position', '1', null);
INSERT INTO `zhmvc_config` VALUES ('31', 'cfg_upimg_watermark_color', '1', null);
INSERT INTO `zhmvc_config` VALUES ('32', 'cfg_arealoop_style', '0', null);
INSERT INTO `zhmvc_config` VALUES ('33', 'cfg_info_if_img', '0', null);
INSERT INTO `zhmvc_config` VALUES ('34', 'cfg_allow_post_area', '', null);
INSERT INTO `zhmvc_config` VALUES ('35', 'cfg_forbidden_post_ip', '', null);
INSERT INTO `zhmvc_config` VALUES ('36', 'cfg_upimg_number', '', null);
INSERT INTO `zhmvc_config` VALUES ('37', 'cfg_if_nonmember_info', '0', null);
INSERT INTO `zhmvc_config` VALUES ('38', 'cfg_if_nonmember_info_box', '0', null);
INSERT INTO `zhmvc_config` VALUES ('39', 'cfg_nonmember_perday_post', '', null);
INSERT INTO `zhmvc_config` VALUES ('40', 'cfg_if_info_verify', '0', null);
INSERT INTO `zhmvc_config` VALUES ('41', 'cfg_if_comment_verify', '0', null);
INSERT INTO `zhmvc_config` VALUES ('42', 'cfg_if_comment_open', '0', null);
INSERT INTO `zhmvc_config` VALUES ('43', 'cfg_if_guestbook_verify', '0', null);

-- ----------------------------
-- Table structure for zhmvc_master
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_master`;
CREATE TABLE `zhmvc_master` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `userpassword` varchar(32) DEFAULT NULL,
  `column_setting` longtext,
  `seting` longtext,
  `lastime` datetime DEFAULT NULL,
  `lastIP` varchar(15) DEFAULT NULL,
  `cookiess` varchar(10) DEFAULT NULL,
  `state` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_master
-- ----------------------------
INSERT INTO `zhmvc_master` VALUES ('1', 'admin', 'c33367701511b4f6020ec61ded352059', '11,12,13,14,121,122,123,124,141,142,143,144,151,152,153,154,131,132,133,134,111,112,113,114,161,162,163,164,171,172,173,174,191,192,193,194,221,222,223,224,231,232,233,234,181,182,183,184,201,202,203,204,211,212,213,214,251,252,253,254,261,262,263,264,', 'system_/manager/admin_config.php,system_/manager/admin_master.php,system_/manager/admin_module.php,system_/manager/admin_plus_attribute.php,system_/manager/admin_plus_interface.php,system_/manager/admin_plus.php,system_/manager/admin_models.php,system_/manager/admin_optionsclass.php,system_/manager/admin_options.php,zpsc_/zpsc/admin/admin_zpsc.php,', '2020-09-01 00:52:24', '127.0.0.1', '271', '1');

-- ----------------------------
-- Table structure for zhmvc_mbindm
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_mbindm`;
CREATE TABLE `zhmvc_mbindm` (
  `moduleid` int(11) NOT NULL COMMENT '模块',
  `modelsid` varchar(100) DEFAULT NULL COMMENT '模型',
  `islist` enum('0','1') DEFAULT '0' COMMENT ' 是否在底层绑定，0是只在当前绑定，1是在子栏目中重新绑定',
  PRIMARY KEY (`moduleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块和模型的绑定';

-- ----------------------------
-- Records of zhmvc_mbindm
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_mbmblist
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_mbmblist`;
CREATE TABLE `zhmvc_mbmblist` (
  `moduleid` int(11) NOT NULL DEFAULT '0' COMMENT '模块id',
  `modelsid` int(11) DEFAULT '0' COMMENT '模型id',
  `listid` int(11) DEFAULT '0' COMMENT '栏目id',
  `otherid1` varchar(100) DEFAULT NULL COMMENT '备用选项1',
  `otherid2` varchar(100) DEFAULT NULL COMMENT '备用选项2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT=' 模块、模型、子栏目的绑定';

-- ----------------------------
-- Records of zhmvc_mbmblist
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_models
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_models`;
CREATE TABLE `zhmvc_models` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `displayorder` tinyint(3) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `options` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_models
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_module
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_module`;
CREATE TABLE `zhmvc_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `biaoqianzhui` varchar(250) DEFAULT NULL COMMENT '数据表前缀',
  `mulu` varchar(250) DEFAULT NULL COMMENT '目录',
  `name` varchar(250) DEFAULT NULL COMMENT '插件名称',
  `modulename` varchar(255) DEFAULT NULL COMMENT '必须不能相同，唯一的自己设定的标识',
  `modulenamespace` varchar(255) DEFAULT NULL COMMENT '命名空间',
  `rpcmainkey` char(32) DEFAULT '本地' COMMENT '如果是远程的话，唯一标识的key',
  `rpctype` enum('远程','本地') DEFAULT '本地' COMMENT 's数据的类型，是本地数据还是远程数据',
  `rpcurl` varchar(255) DEFAULT NULL COMMENT '远程地址',
  `rpchost` varchar(255) DEFAULT NULL,
  `rpcprivatekey` varchar(255) DEFAULT NULL,
  `rpcmoduleid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_module
-- ----------------------------
INSERT INTO `zhmvc_module` VALUES ('1', 'zpsc_', 'zpsc', '照片管理', 'zpsc', 'ZPSC', '', '本地', '', '', '', '1');

-- ----------------------------
-- Table structure for zhmvc_modulerpc
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_modulerpc`;
CREATE TABLE `zhmvc_modulerpc` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `moduleid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '站点名称',
  `mainkey` varchar(255) DEFAULT NULL COMMENT '通信密钥',
  `mainurl` varchar(255) DEFAULT NULL COMMENT '请求数据的站点url,即域名，后期校验用',
  `secretkey` char(32) DEFAULT NULL,
  `privatekey` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_modulerpc
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_modulerpclist
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_modulerpclist`;
CREATE TABLE `zhmvc_modulerpclist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `moduleid` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL COMMENT '提供服务的列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_modulerpclist
-- ----------------------------
INSERT INTO `zhmvc_modulerpclist` VALUES ('1', '1', 'http://www.xiaomizhifu.com/zpsc/rpc/Zpscrpcserver.php');

-- ----------------------------
-- Table structure for zhmvc_moduletable
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_moduletable`;
CREATE TABLE `zhmvc_moduletable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleid` int(11) DEFAULT NULL,
  `classname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_moduletable
-- ----------------------------
INSERT INTO `zhmvc_moduletable` VALUES ('1', '1', 'Zpsc');

-- ----------------------------
-- Table structure for zhmvc_moduletablesub
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_moduletablesub`;
CREATE TABLE `zhmvc_moduletablesub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleid` int(11) DEFAULT NULL,
  `classname` varchar(255) DEFAULT NULL,
  `subname` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `mapcontent` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_moduletablesub
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_module_common
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_module_common`;
CREATE TABLE `zhmvc_module_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulename` varchar(250) DEFAULT NULL,
  `moduletype` varchar(250) DEFAULT NULL,
  `modulepath` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_module_common
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_options
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_options`;
CREATE TABLE `zhmvc_options` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `classid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `identifier` varchar(40) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `rules` mediumtext NOT NULL,
  `available` char(2) NOT NULL,
  `required` char(2) NOT NULL,
  `search` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classid` (`classid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_options
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_optionvalue
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_optionvalue`;
CREATE TABLE `zhmvc_optionvalue` (
  `moduleid` int(6) NOT NULL COMMENT '模块id',
  `modelsid` int(6) DEFAULT NULL COMMENT '模型id',
  `listid` bigint(20) DEFAULT '0' COMMENT ' 栏目id,如果是绑定默认的栏目则为0',
  `articleid` bigint(20) DEFAULT NULL COMMENT '文章id',
  `otherid1` varchar(255) DEFAULT NULL,
  `otherid2` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '键名',
  `value` text COMMENT '键值',
  KEY `moduleid` (`moduleid`),
  KEY `modelsid` (`modelsid`),
  KEY `listid` (`listid`),
  KEY `articleid` (`articleid`),
  KEY `otherid1` (`otherid1`),
  KEY `otherid2` (`otherid2`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_optionvalue
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_plus_attribute
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_plus_attribute`;
CREATE TABLE `zhmvc_plus_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `info` text COMMENT '属性描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_plus_attribute
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_plus_common
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_plus_common`;
CREATE TABLE `zhmvc_plus_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plusname` varchar(250) DEFAULT NULL,
  `plusattribute` varchar(50) DEFAULT NULL,
  `plustype` varchar(50) DEFAULT NULL,
  `pluspath` varchar(250) DEFAULT NULL,
  `plusrootpath` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_plus_common
-- ----------------------------

-- ----------------------------
-- Table structure for zhmvc_plus_interface
-- ----------------------------
DROP TABLE IF EXISTS `zhmvc_plus_interface`;
CREATE TABLE `zhmvc_plus_interface` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attributeid` int(11) DEFAULT NULL COMMENT '属性id',
  `name` varchar(250) DEFAULT NULL COMMENT '接口名称',
  `info` text COMMENT '接口描述 ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zhmvc_plus_interface
-- ----------------------------

-- ----------------------------
-- Table structure for zpsc_zpsc
-- ----------------------------
DROP TABLE IF EXISTS `zpsc_zpsc`;
CREATE TABLE `zpsc_zpsc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mainkey` char(32) NOT NULL COMMENT 'rpc对接key',
  `openid` varchar(250) DEFAULT NULL COMMENT 'openid',
  `name` varchar(250) DEFAULT NULL COMMENT '姓名',
  `xingbie` varchar(250) DEFAULT NULL COMMENT '性别',
  `shenfenzheng` varchar(250) DEFAULT NULL COMMENT '姓名',
  `zhaopian` varchar(250) DEFAULT NULL COMMENT '一寸照片',
  `isok` enum('0','1','2') DEFAULT '0' COMMENT '是否完整',
  `beizhu` varchar(250) DEFAULT NULL COMMENT '反馈',
  `upaddtime` datetime DEFAULT NULL COMMENT '上传时间',
  `fkaddtime` datetime DEFAULT NULL COMMENT '反馈时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zpsc_zpsc
-- ----------------------------
