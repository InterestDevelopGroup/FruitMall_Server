/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : fruit

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2014-11-10 14:28:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `fruit_admin_priv`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_admin_priv`;
CREATE TABLE `fruit_admin_priv` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) NOT NULL COMMENT '管理员ID',
  `priv` text NOT NULL COMMENT '管理员权限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员权限表';

-- ----------------------------
-- Records of fruit_admin_priv
-- ----------------------------
INSERT INTO `fruit_admin_priv` VALUES ('1', '1', 'all');
INSERT INTO `fruit_admin_priv` VALUES ('2', '2', 'index|all,login|all,administrator|management,member|index');

-- ----------------------------
-- Table structure for `fruit_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_admin_user`;
CREATE TABLE `fruit_admin_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(30) NOT NULL COMMENT '帐号',
  `password` char(32) NOT NULL COMMENT '密码',
  `real_name` varchar(20) NOT NULL COMMENT '真实姓名',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `last_time` int(11) DEFAULT NULL COMMENT '上一次登录时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型（1：系统管理员，0：普通管理员）',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(1：正常，0：禁用)',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台管理员表';

-- ----------------------------
-- Records of fruit_admin_user
-- ----------------------------
INSERT INTO `fruit_admin_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', '0', '1415600667', '1', '1', '系统管理员，勿删！');
INSERT INTO `fruit_admin_user` VALUES ('2', 'test', 'e10adc3949ba59abbe56e057f20f883e', 'test', null, '1414987294', '1415254071', '0', '1', null);

-- ----------------------------
-- Table structure for `fruit_child_category`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_child_category`;
CREATE TABLE `fruit_child_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL COMMENT '父类ID',
  `name` varchar(60) NOT NULL COMMENT '分类名称',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='子分类表';

-- ----------------------------
-- Records of fruit_child_category
-- ----------------------------
INSERT INTO `fruit_child_category` VALUES ('1', '1', '測試小分類1', '1415600694', null);

-- ----------------------------
-- Table structure for `fruit_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_goods`;
CREATE TABLE `fruit_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `p_cate_id` int(11) NOT NULL COMMENT '大分类ID',
  `c_cate_id` int(11) NOT NULL COMMENT '小分类ID',
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT '商品单价',
  `_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `unit` varchar(20) NOT NULL COMMENT '价格单位',
  `amount` int(11) DEFAULT NULL COMMENT '每盒个数',
  `weight` int(11) DEFAULT NULL COMMENT '每盒重量',
  `thumb` varchar(255) NOT NULL COMMENT '商品缩略图',
  `image_1` varchar(255) NOT NULL COMMENT '商品图片1',
  `image_2` varchar(255) NOT NULL COMMENT '商品图片2',
  `image_3` varchar(255) NOT NULL COMMENT '商品图片3',
  `image_4` varchar(255) NOT NULL COMMENT '商品图片4',
  `image_5` varchar(255) NOT NULL COMMENT '商品图片5',
  `description` text COMMENT '商品简介',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of fruit_goods
-- ----------------------------
INSERT INTO `fruit_goods` VALUES ('1', '1', '1', '測試商品1', '12.00', '23.00', '元/斤', '10', '200', '/uploads/2014/11/10/d2a7a51590fcb2380a2a39c89b0b5861bdb8c566.jpg', '/uploads/2014/11/10/b6361125a1a78d364be7e895e648b9047d09ed4d.jpg', '/uploads/2014/11/10/c8cc115d79013f761e783b057b77a3287b086cc9.jpg', '/uploads/2014/11/10/4c0e8566cd7a2292af187b1ca3d30e724e4ce83a.jpg', '/uploads/2014/11/10/de7dc9f652e94501d76c77edfbff0bc9c48bcc43.jpg', '/uploads/2014/11/10/957d081b214cc401513755ac8f511c026983dcca.jpg', '<p>這是一個測試商品</p>', '1415600743', null);

-- ----------------------------
-- Table structure for `fruit_member`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_member`;
CREATE TABLE `fruit_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `phone` char(11) NOT NULL COMMENT '用户手机',
  `password` varchar(32) NOT NULL COMMENT '用户密码',
  `username` varchar(30) DEFAULT NULL COMMENT '用户名',
  `real_name` varchar(30) DEFAULT NULL COMMENT '真实姓名',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `sex` tinyint(1) DEFAULT '0' COMMENT '用户性别（0：保密，1：男，2：女）',
  `register_time` int(10) NOT NULL COMMENT '注册时间',
  `last_time` int(10) DEFAULT NULL COMMENT '上一次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of fruit_member
-- ----------------------------

-- ----------------------------
-- Table structure for `fruit_parent_category`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_parent_category`;
CREATE TABLE `fruit_parent_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(60) NOT NULL COMMENT '分类名称',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='父分类表';

-- ----------------------------
-- Records of fruit_parent_category
-- ----------------------------
INSERT INTO `fruit_parent_category` VALUES ('1', '測試大分類1', '1415600686', null);

-- ----------------------------
-- Table structure for `fruit_version`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_version`;
CREATE TABLE `fruit_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(10) NOT NULL,
  `download_url` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '版本类型（0：android，1：ios）',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='App版本管理表';

-- ----------------------------
-- Records of fruit_version
-- ----------------------------
