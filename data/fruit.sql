/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : fruit

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2014-11-04 18:55:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for fruit_admin_priv
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
-- Table structure for fruit_admin_user
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
INSERT INTO `fruit_admin_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', '0', '1415071086', '1', '1', '系统管理员，勿删！');
INSERT INTO `fruit_admin_user` VALUES ('2', 'test', 'e10adc3949ba59abbe56e057f20f883e', 'test', null, '1414987294', '1414998108', '0', '1', null);

-- ----------------------------
-- Table structure for fruit_child_category
-- ----------------------------
DROP TABLE IF EXISTS `fruit_child_category`;
CREATE TABLE `fruit_child_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL COMMENT '父类ID',
  `name` varchar(60) NOT NULL COMMENT '分类名称',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='子分类表';

-- ----------------------------
-- Records of fruit_child_category
-- ----------------------------
INSERT INTO `fruit_child_category` VALUES ('2', '1', '小分類測試1', '1415077861', '1415077871');

-- ----------------------------
-- Table structure for fruit_goods
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
  `description` varchar(255) DEFAULT NULL COMMENT '商品简介',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of fruit_goods
-- ----------------------------

-- ----------------------------
-- Table structure for fruit_goods_image
-- ----------------------------
DROP TABLE IF EXISTS `fruit_goods_image`;
CREATE TABLE `fruit_goods_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `image` varchar(255) NOT NULL COMMENT '商品图片',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品图片表';

-- ----------------------------
-- Records of fruit_goods_image
-- ----------------------------

-- ----------------------------
-- Table structure for fruit_member
-- ----------------------------
DROP TABLE IF EXISTS `fruit_member`;
CREATE TABLE `fruit_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account` varchar(30) NOT NULL COMMENT '会员帐号',
  `password` varchar(32) NOT NULL COMMENT '会员密码',
  `phone` varchar(30) NOT NULL COMMENT '会员电话',
  `real_name` varchar(30) DEFAULT NULL COMMENT '真实姓名',
  `shop_name` varchar(255) DEFAULT NULL COMMENT '店铺名称',
  `wechat` varchar(255) DEFAULT NULL COMMENT '微信帐号',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `sex` tinyint(1) DEFAULT NULL COMMENT '用户性别（1：男0：女）',
  `level` int(6) NOT NULL DEFAULT '1' COMMENT '用户等级',
  `email` varchar(255) DEFAULT NULL COMMENT '会员邮箱',
  `service` int(11) DEFAULT NULL COMMENT '对应客服',
  `qq_open_id` varchar(50) DEFAULT NULL COMMENT 'QQ第三方登陆openid',
  `sina_open_id` varchar(50) DEFAULT NULL COMMENT '新浪微博openId',
  `register_time` int(10) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_time` int(10) DEFAULT NULL COMMENT '上一次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of fruit_member
-- ----------------------------

-- ----------------------------
-- Table structure for fruit_parent_category
-- ----------------------------
DROP TABLE IF EXISTS `fruit_parent_category`;
CREATE TABLE `fruit_parent_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(60) NOT NULL COMMENT '分类名称',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='父分类表';

-- ----------------------------
-- Records of fruit_parent_category
-- ----------------------------
INSERT INTO `fruit_parent_category` VALUES ('1', '大分類測試1', '1415012373', '1415073133');
