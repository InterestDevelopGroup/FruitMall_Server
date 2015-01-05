/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : fruit

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-01-05 10:27:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `fruit_address`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_address`;
CREATE TABLE `fruit_address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `consignee` varchar(30) NOT NULL COMMENT '收货人',
  `phone` char(11) NOT NULL COMMENT '收货人手机',
  `province` varchar(50) NOT NULL COMMENT '省份',
  `city` varchar(50) NOT NULL COMMENT '城市',
  `district` varchar(50) DEFAULT NULL COMMENT '区',
  `community` varchar(255) DEFAULT NULL COMMENT '小区',
  `road_number` varchar(255) DEFAULT NULL COMMENT '路牌号',
  `building` varchar(255) DEFAULT NULL COMMENT '栋、期、座',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `_consignee` varchar(50) DEFAULT NULL COMMENT '备用收货人',
  `_phone` char(11) DEFAULT NULL COMMENT '备用收货人手机',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除（0：否，1：是）',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='地址表';

-- ----------------------------
-- Records of fruit_address
-- ----------------------------
INSERT INTO `fruit_address` VALUES ('4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001', '0', '1415789535', '1416883529');
INSERT INTO `fruit_address` VALUES ('5', '1', '中国移动', '13800138000', '广东省', '广州市', '天河区', null, null, null, '广州市天河区中山大道羊城花园康苑八座401', '中国联通', '13800138001', '0', '1415790242', null);
INSERT INTO `fruit_address` VALUES ('6', '1', '张三', '13610004889', '山东省', '潍坊市', '某区', '某小区', null, null, '山东省潍坊市某区某小区120号', null, null, '0', '1418875171', null);
INSERT INTO `fruit_address` VALUES ('7', '8', '帅哥', '13437563074', '广东省', '广州市', '越秀区', '某小区', null, null, '测试地址', null, null, '0', '1419305877', null);

-- ----------------------------
-- Table structure for `fruit_admin_priv`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_admin_priv`;
CREATE TABLE `fruit_admin_priv` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) NOT NULL COMMENT '管理员ID',
  `priv` text NOT NULL COMMENT '管理员权限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='管理员权限表';

-- ----------------------------
-- Records of fruit_admin_priv
-- ----------------------------
INSERT INTO `fruit_admin_priv` VALUES ('1', '1', 'all');
INSERT INTO `fruit_admin_priv` VALUES ('4', '4', 'index|all,login|all,member|index,goods|index,goods|add,goods|delete,goods|update,goods|advertisement,goods|update_status,goods|update_tag,goods|update_priority,category|parent_index,category|child_index,tag|index,package|index,courier|index,shipping|index,branch|index,coupon|rule,coupon|usage,coupon|add_usage,coupon|update_usage,order|index,order|history,notification|index,version|android,task|purchase,feedback|index,returns|index');
INSERT INTO `fruit_admin_priv` VALUES ('5', '5', 'index|all,login|all,branch|index,branch|update,order|cancels,order|index,order|history,order|delete,order|update_status,order|sure,order|print_order,order|distribute');
INSERT INTO `fruit_admin_priv` VALUES ('7', '7', 'index|all,login|all');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='后台管理员表';

-- ----------------------------
-- Records of fruit_admin_user
-- ----------------------------
INSERT INTO `fruit_admin_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', '0', '1420007627', '1', '1', '系统管理员，勿删！');
INSERT INTO `fruit_admin_user` VALUES ('4', 'test', 'e10adc3949ba59abbe56e057f20f883e', 'test', null, '1415763083', '1419757295', '0', '1', null);
INSERT INTO `fruit_admin_user` VALUES ('5', 'demo', 'e10adc3949ba59abbe56e057f20f883e', 'demo', null, '1417593149', '1419757352', '0', '1', null);
INSERT INTO `fruit_admin_user` VALUES ('7', 'test1', 'e10adc3949ba59abbe56e057f20f883e', 'ceshi', null, '1417676700', null, '0', '1', null);

-- ----------------------------
-- Table structure for `fruit_advertisement`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_advertisement`;
CREATE TABLE `fruit_advertisement` (
  `advertisement_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `package_id` int(11) DEFAULT NULL COMMENT '套餐ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`advertisement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='广告表';

-- ----------------------------
-- Records of fruit_advertisement
-- ----------------------------
INSERT INTO `fruit_advertisement` VALUES ('4', null, '19', '1416895575');
INSERT INTO `fruit_advertisement` VALUES ('5', '1', null, '1417764422');
INSERT INTO `fruit_advertisement` VALUES ('6', '3', null, '1418103307');
INSERT INTO `fruit_advertisement` VALUES ('7', '4', null, '1418103308');

-- ----------------------------
-- Table structure for `fruit_blacklist`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_blacklist`;
CREATE TABLE `fruit_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='黑名单用户表';

-- ----------------------------
-- Records of fruit_blacklist
-- ----------------------------
INSERT INTO `fruit_blacklist` VALUES ('2', '8', '1418121476');

-- ----------------------------
-- Table structure for `fruit_branch`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_branch`;
CREATE TABLE `fruit_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '分店名称',
  `admin_id` int(11) NOT NULL COMMENT '分店管理员ID',
  `remark` text COMMENT '备注',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='分店表';

-- ----------------------------
-- Records of fruit_branch
-- ----------------------------
INSERT INTO `fruit_branch` VALUES ('8', '测试分店1', '4', '这是一个测试', '1417332317', '1417335657');
INSERT INTO `fruit_branch` VALUES ('9', 'demo测试', '5', 'demo测试分店', '1417594348', null);
INSERT INTO `fruit_branch` VALUES ('11', '分店4', '7', '测试', '1417674464', '1417676707');
INSERT INTO `fruit_branch` VALUES ('12', '测试分店5', '4', '新分店', '1417928119', '1417928272');

-- ----------------------------
-- Table structure for `fruit_branch_courier`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_branch_courier`;
CREATE TABLE `fruit_branch_courier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `branch_id` int(11) NOT NULL COMMENT '分店ID',
  `courier_id` int(11) NOT NULL COMMENT '送货员ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='分店送货人员表';

-- ----------------------------
-- Records of fruit_branch_courier
-- ----------------------------
INSERT INTO `fruit_branch_courier` VALUES ('12', '8', '3', '1417335657');
INSERT INTO `fruit_branch_courier` VALUES ('13', '9', '4', '1417594348');
INSERT INTO `fruit_branch_courier` VALUES ('24', '11', '5', '1417676707');
INSERT INTO `fruit_branch_courier` VALUES ('25', '11', '6', '1417676707');
INSERT INTO `fruit_branch_courier` VALUES ('27', '12', '7', '1417928272');

-- ----------------------------
-- Table structure for `fruit_branch_shipping_address`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_branch_shipping_address`;
CREATE TABLE `fruit_branch_shipping_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `branch_id` int(11) NOT NULL COMMENT '分店ID',
  `shipping_address_id` int(11) NOT NULL COMMENT '送货地址ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='分店送货地址表';

-- ----------------------------
-- Records of fruit_branch_shipping_address
-- ----------------------------
INSERT INTO `fruit_branch_shipping_address` VALUES ('12', '8', '2', '1417335657');
INSERT INTO `fruit_branch_shipping_address` VALUES ('13', '9', '3', '1417594348');
INSERT INTO `fruit_branch_shipping_address` VALUES ('22', '11', '4', '1417676707');
INSERT INTO `fruit_branch_shipping_address` VALUES ('23', '11', '5', '1417676707');
INSERT INTO `fruit_branch_shipping_address` VALUES ('25', '12', '6', '1417928272');
INSERT INTO `fruit_branch_shipping_address` VALUES ('26', '12', '7', '1417928272');

-- ----------------------------
-- Table structure for `fruit_child_category`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_child_category`;
CREATE TABLE `fruit_child_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL COMMENT '父类ID',
  `name` varchar(60) NOT NULL COMMENT '分类名称',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `description` text COMMENT '描述',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='子分类表';

-- ----------------------------
-- Records of fruit_child_category
-- ----------------------------
INSERT INTO `fruit_child_category` VALUES ('1', '1', '測試小分類1', '0', '测试一下', '1415615797', '1418374763');
INSERT INTO `fruit_child_category` VALUES ('2', '1', '测试小分类2', '0', null, '1418374030', '1418374767');

-- ----------------------------
-- Table structure for `fruit_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_coupon`;
CREATE TABLE `fruit_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `score` int(11) NOT NULL COMMENT '水果劵分数',
  `type` tinyint(1) NOT NULL COMMENT '水果劵类型（1：注册，2：推荐，3：满X送N，4：手动赠送）',
  `publish_time` int(11) NOT NULL COMMENT '发劵时间',
  `expire_time` int(11) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='水果劵表';

-- ----------------------------
-- Records of fruit_coupon
-- ----------------------------
INSERT INTO `fruit_coupon` VALUES ('1', '1', '4', '2', '1417073018', '1419609600');
INSERT INTO `fruit_coupon` VALUES ('2', '8', '10', '1', '1417073018', '1419609600');
INSERT INTO `fruit_coupon` VALUES ('3', '1', '5', '4', '1417073039', '1417881600');
INSERT INTO `fruit_coupon` VALUES ('5', '1', '5', '4', '1417577956', null);
INSERT INTO `fruit_coupon` VALUES ('6', '1', '12', '4', '1418112151', '1420646400');
INSERT INTO `fruit_coupon` VALUES ('7', '8', '12', '4', '1418112151', '1420646400');

-- ----------------------------
-- Table structure for `fruit_coupon_rule`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_coupon_rule`;
CREATE TABLE `fruit_coupon_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `description` varchar(30) NOT NULL COMMENT '描述',
  `type` tinyint(1) NOT NULL COMMENT '类型（1：注册，2：推荐，3：满X送N）',
  `score` int(11) NOT NULL COMMENT '面值',
  `condition` int(11) DEFAULT NULL COMMENT '满X送N条件',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `expire_time` int(10) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='水果劵规则';

-- ----------------------------
-- Records of fruit_coupon_rule
-- ----------------------------
INSERT INTO `fruit_coupon_rule` VALUES ('5', '推荐', '2', '10', null, '1417011945', '1417012481', '30');
INSERT INTO `fruit_coupon_rule` VALUES ('6', '满500送10', '3', '10', '500', '1417012042', '1417012528', '60');
INSERT INTO `fruit_coupon_rule` VALUES ('7', '注册', '1', '10', null, '1417012714', null, '30');

-- ----------------------------
-- Table structure for `fruit_coupon_rule_content`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_coupon_rule_content`;
CREATE TABLE `fruit_coupon_rule_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(1) NOT NULL COMMENT '类型（1：获取规则，2：使用规则）',
  `content` text NOT NULL COMMENT '规则内容',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='规则内容表';

-- ----------------------------
-- Records of fruit_coupon_rule_content
-- ----------------------------
INSERT INTO `fruit_coupon_rule_content` VALUES ('1', '1', '<p>獲取規則</p>', '1417077998', '1417078330');
INSERT INTO `fruit_coupon_rule_content` VALUES ('2', '2', '<p>使用規則</p>', '1417078269', null);

-- ----------------------------
-- Table structure for `fruit_coupon_usage`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_coupon_usage`;
CREATE TABLE `fruit_coupon_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `description` varchar(30) NOT NULL COMMENT '描述',
  `condition` int(11) NOT NULL COMMENT '条件',
  `score` int(11) NOT NULL COMMENT '使用金额',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='水果劵使用规则表';

-- ----------------------------
-- Records of fruit_coupon_usage
-- ----------------------------
INSERT INTO `fruit_coupon_usage` VALUES ('1', '满100用5', '100', '3', '1417576653', '1417576872');
INSERT INTO `fruit_coupon_usage` VALUES ('2', '满200用10', '200', '6', '1417579508', '1417583443');
INSERT INTO `fruit_coupon_usage` VALUES ('3', '满300用10', '300', '10', '1417583457', '1417588311');

-- ----------------------------
-- Table structure for `fruit_courier`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_courier`;
CREATE TABLE `fruit_courier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `real_name` varchar(30) NOT NULL COMMENT '真实姓名',
  `phone` char(11) NOT NULL COMMENT '手机',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='送货人员表';

-- ----------------------------
-- Records of fruit_courier
-- ----------------------------
INSERT INTO `fruit_courier` VALUES ('3', '测试送货员', '13800138000', '1417335640', null);
INSERT INTO `fruit_courier` VALUES ('4', 'demo送货员', '13900000001', '1417593388', '1418375381');
INSERT INTO `fruit_courier` VALUES ('5', 'test', '13412345678', '1417669712', '1417669776');
INSERT INTO `fruit_courier` VALUES ('6', 'test1', '13812345678', '1417669753', '1417669770');
INSERT INTO `fruit_courier` VALUES ('7', '阿茂', '13912345678', '1417928082', null);
INSERT INTO `fruit_courier` VALUES ('8', 'demo送货员', '13900000000', '1418375394', null);

-- ----------------------------
-- Table structure for `fruit_custom`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_custom`;
CREATE TABLE `fruit_custom` (
  `custom_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(30) NOT NULL COMMENT '定制名称',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除（0：否，1：是）',
  PRIMARY KEY (`custom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='我的定制表';

-- ----------------------------
-- Records of fruit_custom
-- ----------------------------
INSERT INTO `fruit_custom` VALUES ('1', '1', '我的定制', '1416906869', '0');
INSERT INTO `fruit_custom` VALUES ('2', '1', '定制测试2', '1419821724', '0');

-- ----------------------------
-- Table structure for `fruit_custom_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_custom_goods`;
CREATE TABLE `fruit_custom_goods` (
  `custom_goods_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `custom_id` int(11) NOT NULL COMMENT '定制ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `quantity` int(11) NOT NULL COMMENT '数量',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`custom_goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='定制商品表';

-- ----------------------------
-- Records of fruit_custom_goods
-- ----------------------------
INSERT INTO `fruit_custom_goods` VALUES ('1', '1', '1', '10', '1416906869');
INSERT INTO `fruit_custom_goods` VALUES ('2', '1', '2', '8', '1416906869');
INSERT INTO `fruit_custom_goods` VALUES ('3', '1', '3', '6', '1416907013');
INSERT INTO `fruit_custom_goods` VALUES ('4', '2', '2', '5', '1419821724');
INSERT INTO `fruit_custom_goods` VALUES ('5', '2', '5', '2', '1419821724');

-- ----------------------------
-- Table structure for `fruit_default_address`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_default_address`;
CREATE TABLE `fruit_default_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `address_id` int(11) NOT NULL COMMENT '地址ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='默认地址表';

-- ----------------------------
-- Records of fruit_default_address
-- ----------------------------
INSERT INTO `fruit_default_address` VALUES ('1', '1', '4', '1415845607');
INSERT INTO `fruit_default_address` VALUES ('2', '8', '7', '1419305965');

-- ----------------------------
-- Table structure for `fruit_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_feedback`;
CREATE TABLE `fruit_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `order_number` char(14) NOT NULL COMMENT '订单号',
  `shipping_service` tinyint(1) NOT NULL COMMENT '送货服务（0：踩，1：赞）',
  `quality` tinyint(1) NOT NULL COMMENT '水果质量（0：踩，1：赞）',
  `price` tinyint(1) NOT NULL COMMENT '水果价格（0：踩，1：赞）',
  `postscript` varchar(255) DEFAULT NULL COMMENT '补充说明',
  `result` varchar(30) DEFAULT NULL COMMENT '处理结果',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户反馈表';

-- ----------------------------
-- Records of fruit_feedback
-- ----------------------------
INSERT INTO `fruit_feedback` VALUES ('1', '1', '14111410253561', '1', '1', '1', 'postscript testing', '测试', '1416219320');

-- ----------------------------
-- Table structure for `fruit_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_goods`;
CREATE TABLE `fruit_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `p_cate_id` int(11) NOT NULL COMMENT '大分类ID',
  `c_cate_id` int(11) NOT NULL COMMENT '小分类ID',
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT '商品总价',
  `_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `single_price` decimal(10,2) NOT NULL COMMENT '商品单价',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '商品状态（0：下架，1：上架）',
  `unit` varchar(20) NOT NULL COMMENT '价格单位',
  `single_unit` varchar(20) NOT NULL COMMENT '单价单位',
  `tag` int(11) DEFAULT NULL COMMENT '商品标签',
  `amount` int(11) DEFAULT NULL COMMENT '每盒个数',
  `weight` int(11) DEFAULT NULL COMMENT '每盒重量',
  `thumb` varchar(255) NOT NULL COMMENT '商品缩略图',
  `priority` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `image_1` varchar(255) NOT NULL COMMENT '商品图片1',
  `image_2` varchar(255) DEFAULT NULL COMMENT '商品图片2',
  `image_3` varchar(255) DEFAULT NULL COMMENT '商品图片3',
  `image_4` varchar(255) DEFAULT NULL COMMENT '商品图片4',
  `image_5` varchar(255) DEFAULT NULL COMMENT '商品图片5',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除（0：否，1：是）',
  `description` text COMMENT '商品简介',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `pct` (`p_cate_id`,`c_cate_id`,`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of fruit_goods
-- ----------------------------
INSERT INTO `fruit_goods` VALUES ('1', '1', '1', '苹果5（原测试商品1）', '12.00', '24.00', '6.00', '0', '元/斤', '元/斤', '1', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '0', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '0', '<p>測試商品1</p>', '1415615863', '1419739655');
INSERT INTO `fruit_goods` VALUES ('2', '1', '1', '測試商品2', '12.00', '42.00', '0.00', '1', '元/斤', '元/斤', '2', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '21', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '0', '<p>測試商品2</p>', '1415615924', '1418457770');
INSERT INTO `fruit_goods` VALUES ('3', '1', '1', '測試商品3', '1.00', '2.00', '0.00', '1', '元/斤', '', '1', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '0', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '1', '<p>測試商品3</p>', '1415859221', '1415859538');
INSERT INTO `fruit_goods` VALUES ('4', '1', '1', '測試商品4', '12.00', '29.00', '0.00', '1', '元/斤', '', '1', '12', '200', '/uploads/2014/11/13/913f61608dc1fe5ccfdd70db828ffe3dcbb921d5.jpg', '0', '/uploads/2014/11/13/9c0b367ab7be91a64ab3eedb3db9569372a7f14b.jpg', '/uploads/2014/11/13/9feef9d4e6b4da9c282129e2bb4a6608964324b9.jpg', '/uploads/2014/11/13/44a3ba953a5ae2e230a4340762eab757aae6d5df.jpg', '/uploads/2014/11/13/9f11b7a0433dd4aa9ac0312668c26cfd5d1197af.jpg', '/uploads/2014/11/13/f3edeefa2ca2509bc74fee5fe11884e8b5e3a34d.jpg', '1', '<p>測試商品4</p>', '1415859581', null);
INSERT INTO `fruit_goods` VALUES ('5', '1', '2', '测试商品5', '50.00', '60.00', '20.00', '1', '元/斤', '元/斤', '1', '20', '400', '/uploads/2014/12/13/5720a00408371ad16fbcac8a3f0670ff36d806ed.png', '10', '/uploads/2014/12/13/a92f7bcc0489e51f85552be72b43d4090aaea92a.jpg', '/uploads/2014/12/13/1df38f575ff2609a50065076c0df734324f63240.jpg', '/uploads/2014/12/13/5b8a283638c295a79cf46b7bb08d294067377be0.jpg', '/uploads/2014/12/13/017e76222d2a3e96f8ced63f8c6e47184abf5264.jpg', '/uploads/2014/12/13/93f65eb76fc80900f8c56f8f4b54bb82a3bae62a.jpg', '0', '<p>测试商品5</p>', '1418457079', '1418614163');
INSERT INTO `fruit_goods` VALUES ('6', '1', '1', '测试商品6', '12.00', '123.00', '11.00', '1', '元/斤', '元/斤', '1', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '11', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '0', '<p>测试</p>', '1418568910', null);
INSERT INTO `fruit_goods` VALUES ('7', '1', '1', '测试商品7', '12.00', '12.00', '12.00', '1', '元/斤', '元/斤', '1', '12', '12', '/uploads/2014/12/15/664c5611d33142e3b76dedc6a4017be274f225f8.jpg', '14', '/uploads/2014/12/15/849678800bfeaaab266e7f9455fb8691d73e395a.jpg', '/uploads/2014/12/15/ba3533fd7a1adb9c209dfed02c61694445eb7fc6.jpg', null, null, null, '0', '<p>商品图片测试</p>', '1418612775', '1418613864');
INSERT INTO `fruit_goods` VALUES ('8', '1', '1', '测试商品8', '12.00', '12.00', '12.00', '1', '测试', '测试', '1', '11', '112', '/uploads/2014/12/15/f52cfcd3108a6faa7a15b7d66922cf59f04073d0.jpg', '12', '/uploads/2014/12/15/7cf3cd8756cc95630a7467e5e2fff7748f94b0ee.jpg', '/uploads/2014/12/15/f989af07b8fdd561f9e5642fab3f666a03db72ab.jpg', '/uploads/2014/12/15/c5faf0e46d799a35cfcddec72cfd52f8cae6bcb0.jpg', null, null, '0', '<p>测试商品图片上传数量</p>', '1418613921', '1418613930');

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
  `remark` varchar(30) DEFAULT NULL COMMENT '用户备注',
  `register_time` int(10) NOT NULL COMMENT '注册时间',
  `last_time` int(10) DEFAULT NULL COMMENT '上一次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of fruit_member
-- ----------------------------
INSERT INTO `fruit_member` VALUES ('1', '13800138000', 'e10adc3949ba59abbe56e057f20f883e', 'CMCC', '中国移动', '/uploads/2014/11/13/913f61608dc1fe5ccfdd70db828ffe3dcbb921d5.jpg', '1', '测试1', '1415763408', '1418454903');
INSERT INTO `fruit_member` VALUES ('8', '13437563074', 'e10adc3949ba59abbe56e057f20f883e', null, null, null, '0', '测试2', '1417073018', null);

-- ----------------------------
-- Table structure for `fruit_notification`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_notification`;
CREATE TABLE `fruit_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(60) NOT NULL COMMENT '标题',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='推送消息表';

-- ----------------------------
-- Records of fruit_notification
-- ----------------------------
INSERT INTO `fruit_notification` VALUES ('4', '测试', '测试一下', '1417358086', '1417358417');

-- ----------------------------
-- Table structure for `fruit_order`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order`;
CREATE TABLE `fruit_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `address_id` int(11) NOT NULL COMMENT '收获地址ID',
  `order_number` char(14) NOT NULL COMMENT '订单号',
  `status` tinyint(1) NOT NULL COMMENT '订单状态（1：待确定，2：配送中，3：已收货，4：拒收，5：取消，6：待退货，7：同意退货，8：不同意退货）',
  `shipping_time` char(11) DEFAULT NULL COMMENT '开始送货时间',
  `shipping_fee` decimal(10,2) NOT NULL COMMENT '送货费',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `coupon` int(11) DEFAULT NULL COMMENT '使用水果劵',
  `total_amount` decimal(10,2) NOT NULL COMMENT '订单金额',
  `branch_id` int(11) DEFAULT NULL COMMENT '分店ID',
  `courier_id` int(11) DEFAULT NULL COMMENT '送货员ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`order_id`),
  KEY `us` (`user_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of fruit_order
-- ----------------------------
INSERT INTO `fruit_order` VALUES ('1', '1', '4', '14122410248511', '7', '12:00-18:00', '12.50', 'this is a test', null, '376.50', '8', '3', '1419392079', '1419690243');
INSERT INTO `fruit_order` VALUES ('2', '1', '4', '14122498545297', '6', '12:00-18:00', '12.50', 'this is a test', null, '376.50', '8', '3', '1419392779', '1419690175');
INSERT INTO `fruit_order` VALUES ('3', '1', '4', '14122410148975', '2', '12:00-18:00', '12.50', 'this is a test', null, '376.50', '8', '3', '1419392782', '1419392804');
INSERT INTO `fruit_order` VALUES ('4', '1', '4', '14122410298491', '2', '12:00-18:00', '12.50', 'this is a test', null, '376.50', '8', '3', '1419392783', '1419392804');
INSERT INTO `fruit_order` VALUES ('5', '1', '4', '14122449521029', '2', '12:00-18:00', '12.50', 'this is a test', null, '376.50', '8', '3', '1419392785', '1419392804');
INSERT INTO `fruit_order` VALUES ('6', '1', '4', '14122454515251', '2', '12:00-18:00', '12.50', 'this is a test', null, '388.50', '9', '4', '1419394102', '1419398288');
INSERT INTO `fruit_order` VALUES ('7', '1', '4', '14122757545110', '2', '12:00-18:00', '12.50', null, null, '714.50', '11', '5', '1419685881', '1419686618');
INSERT INTO `fruit_order` VALUES ('8', '1', '4', '14122797535410', '2', '12:00-18:00', '12.50', null, null, '744.50', '12', '7', '1419686794', '1419686816');
INSERT INTO `fruit_order` VALUES ('9', '1', '4', '14122757505797', '6', '12:00-18:00', '12.50', null, null, '894.50', null, null, '1419687353', '1419690148');
INSERT INTO `fruit_order` VALUES ('10', '1', '4', '14122849102495', '1', '12:00-18:00', '12.50', null, null, '474.50', '8', null, '1419756849', null);
INSERT INTO `fruit_order` VALUES ('11', '1', '4', '1419821734523', '1', '12:00-18:00', '12.50', null, null, '508.50', '8', null, '1419821734', null);

-- ----------------------------
-- Table structure for `fruit_order_address`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_address`;
CREATE TABLE `fruit_order_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `address_id` int(11) NOT NULL COMMENT '地址ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `consignee` varchar(30) NOT NULL COMMENT '收货人',
  `phone` char(11) NOT NULL COMMENT '收货人手机',
  `province` varchar(50) NOT NULL COMMENT '省份',
  `city` varchar(50) NOT NULL COMMENT '城市',
  `district` varchar(50) DEFAULT NULL COMMENT '区',
  `community` varchar(255) DEFAULT NULL COMMENT '小区',
  `road_number` varchar(255) DEFAULT NULL COMMENT '路牌号',
  `building` varchar(255) DEFAULT NULL COMMENT '栋、期、座',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `_consignee` varchar(50) DEFAULT NULL COMMENT '备用收货人',
  `_phone` char(11) DEFAULT NULL COMMENT '备用收货人手机',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='订单地址表';

-- ----------------------------
-- Records of fruit_order_address
-- ----------------------------
INSERT INTO `fruit_order_address` VALUES ('1', '1', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('2', '2', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('3', '3', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('4', '4', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('5', '5', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('6', '6', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('7', '7', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('8', '8', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('9', '9', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('10', '10', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');
INSERT INTO `fruit_order_address` VALUES ('11', '11', '4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '12号', '8座1102', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001');

-- ----------------------------
-- Table structure for `fruit_order_custom`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_custom`;
CREATE TABLE `fruit_order_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `order_quantity` int(11) NOT NULL COMMENT '订单数量',
  `order_price` decimal(10,2) NOT NULL COMMENT '定制价格',
  `custom_id` int(11) NOT NULL COMMENT '定制ID',
  `name` varchar(255) NOT NULL COMMENT '定制名字',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='订单定制表';

-- ----------------------------
-- Records of fruit_order_custom
-- ----------------------------
INSERT INTO `fruit_order_custom` VALUES ('1', '1', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('2', '2', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('3', '3', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('4', '4', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('5', '5', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('6', '6', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('7', '7', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('8', '8', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('9', '9', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('10', '10', '1', '222.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('11', '11', '1', '96.00', '1', '我的定制');
INSERT INTO `fruit_order_custom` VALUES ('12', '11', '1', '160.00', '2', '定制测试2');

-- ----------------------------
-- Table structure for `fruit_order_custom_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_custom_goods`;
CREATE TABLE `fruit_order_custom_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `custom_id` int(11) NOT NULL COMMENT '定制ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `goods_quantity` int(11) NOT NULL COMMENT '套餐商品数量',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL COMMENT '商品总价',
  `_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `single_price` decimal(10,2) NOT NULL COMMENT '商品单价',
  `unit` varchar(20) NOT NULL COMMENT '价格单位',
  `single_unit` varchar(20) NOT NULL COMMENT '单价单位',
  `amount` int(11) DEFAULT NULL COMMENT '每盒个数',
  `weight` int(11) DEFAULT NULL COMMENT '每盒重量',
  `thumb` varchar(255) NOT NULL COMMENT '商品缩略图',
  `image_1` varchar(255) NOT NULL COMMENT '商品图片1',
  `image_2` varchar(255) DEFAULT NULL COMMENT '商品图片2',
  `image_3` varchar(255) DEFAULT NULL COMMENT '商品图片3',
  `image_4` varchar(255) DEFAULT NULL COMMENT '商品图片4',
  `image_5` varchar(255) DEFAULT NULL COMMENT '商品图片5',
  `description` text COMMENT '商品简介',
  `parent_category` varchar(60) NOT NULL COMMENT '父分类名',
  `child_category` varchar(60) NOT NULL COMMENT '子分类名',
  `tag_name` varchar(60) NOT NULL COMMENT '标签名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='订单定制商品表';

-- ----------------------------
-- Records of fruit_order_custom_goods
-- ----------------------------
INSERT INTO `fruit_order_custom_goods` VALUES ('1', '1', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('2', '1', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('3', '1', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('4', '2', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('5', '2', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('6', '2', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('7', '3', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('8', '3', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('9', '3', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('10', '4', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('11', '4', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('12', '4', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('13', '5', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('14', '5', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('15', '5', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('16', '6', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('17', '6', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('18', '6', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('19', '7', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('20', '7', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('21', '7', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('22', '8', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('23', '8', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('24', '8', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('25', '9', '1', '1', '10', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('26', '9', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('27', '9', '1', '3', '6', '測試商品3', '1.00', '2.00', '0.00', '元/斤', '', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '<p>測試商品3</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_custom_goods` VALUES ('28', '10', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('29', '11', '1', '2', '8', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('30', '11', '2', '2', '5', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_custom_goods` VALUES ('31', '11', '2', '5', '2', '测试商品5', '50.00', '60.00', '20.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/12/13/5720a00408371ad16fbcac8a3f0670ff36d806ed.png', '/uploads/2014/12/13/a92f7bcc0489e51f85552be72b43d4090aaea92a.jpg', '/uploads/2014/12/13/1df38f575ff2609a50065076c0df734324f63240.jpg', '/uploads/2014/12/13/5b8a283638c295a79cf46b7bb08d294067377be0.jpg', '/uploads/2014/12/13/017e76222d2a3e96f8ced63f8c6e47184abf5264.jpg', '/uploads/2014/12/13/93f65eb76fc80900f8c56f8f4b54bb82a3bae62a.jpg', '<p>测试商品5</p>', '測試大分類1', '测试小分类2', '測試標籤1');

-- ----------------------------
-- Table structure for `fruit_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_goods`;
CREATE TABLE `fruit_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `order_quantity` int(11) NOT NULL COMMENT '订单商品数量',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL COMMENT '商品总价',
  `_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `single_price` decimal(10,2) NOT NULL COMMENT '商品单价',
  `unit` varchar(20) NOT NULL COMMENT '价格单位',
  `single_unit` varchar(20) NOT NULL COMMENT '单价单位',
  `amount` int(11) DEFAULT NULL COMMENT '每盒个数',
  `weight` int(11) DEFAULT NULL COMMENT '每盒重量',
  `thumb` varchar(255) NOT NULL COMMENT '商品缩略图',
  `image_1` varchar(255) NOT NULL COMMENT '商品图片1',
  `image_2` varchar(255) DEFAULT NULL COMMENT '商品图片2',
  `image_3` varchar(255) DEFAULT NULL COMMENT '商品图片3',
  `image_4` varchar(255) DEFAULT NULL COMMENT '商品图片4',
  `image_5` varchar(255) DEFAULT NULL COMMENT '商品图片5',
  `description` text COMMENT '商品简介',
  `parent_category` varchar(60) NOT NULL COMMENT '父分类名',
  `child_category` varchar(60) NOT NULL COMMENT '子分类名',
  `tag_name` varchar(60) DEFAULT NULL COMMENT '标签',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='订单商品表';

-- ----------------------------
-- Records of fruit_order_goods
-- ----------------------------
INSERT INTO `fruit_order_goods` VALUES ('1', '1', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('2', '2', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('3', '3', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('4', '4', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('5', '5', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('6', '6', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('7', '7', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('8', '7', '10', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_goods` VALUES ('9', '8', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('10', '8', '10', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_goods` VALUES ('11', '9', '10', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('12', '9', '10', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_goods` VALUES ('13', '10', '10', '1', '苹果5（原测试商品1）', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_goods` VALUES ('14', '11', '10', '1', '苹果5（原测试商品1）', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');

-- ----------------------------
-- Table structure for `fruit_order_package`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_package`;
CREATE TABLE `fruit_order_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `order_quantity` int(11) NOT NULL COMMENT '订单数量',
  `package_id` int(11) NOT NULL COMMENT '套餐ID',
  `name` varchar(255) NOT NULL COMMENT '套餐名字',
  `price` decimal(10,2) NOT NULL COMMENT '套餐价格',
  `_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `image_1` varchar(255) NOT NULL COMMENT '介绍图片1',
  `image_2` varchar(255) DEFAULT NULL COMMENT '介绍图片2',
  `image_3` varchar(255) DEFAULT NULL COMMENT '介绍图片3',
  `image_4` varchar(255) DEFAULT NULL COMMENT '介绍图片4',
  `image_5` varchar(255) DEFAULT NULL COMMENT '介绍图片5',
  `description` text COMMENT '套餐介绍',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='订单套餐表';

-- ----------------------------
-- Records of fruit_order_package
-- ----------------------------
INSERT INTO `fruit_order_package` VALUES ('1', '1', '1', '19', '測試套餐1', '12.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>');
INSERT INTO `fruit_order_package` VALUES ('2', '2', '1', '19', '測試套餐1', '12.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>');
INSERT INTO `fruit_order_package` VALUES ('3', '3', '1', '19', '測試套餐1', '12.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>');
INSERT INTO `fruit_order_package` VALUES ('4', '4', '1', '19', '測試套餐1', '12.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>');
INSERT INTO `fruit_order_package` VALUES ('5', '5', '1', '19', '測試套餐1', '12.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>');
INSERT INTO `fruit_order_package` VALUES ('6', '6', '2', '19', '測試套餐1', '12.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>');
INSERT INTO `fruit_order_package` VALUES ('7', '7', '1', '23', '测试套餐8', '120.00', '170.00', '/uploads/2014/12/18/4696c2f9ba339f00a54be68025a0bc5b956b0ab0.jpg', '/uploads/2014/12/18/3e99390d580cdd7868cc7316669081e6e1be5f26.jpg', null, null, null, null, '<p>阿大使</p>');
INSERT INTO `fruit_order_package` VALUES ('8', '7', '1', '22', '测试套餐5', '120.00', '160.00', '/uploads/2014/12/18/e78e6eacde5ba0b08b9a2109ec25c8ffb16ed08d.jpg', '/uploads/2014/12/18/d4e2b421422c72fb90edc3003c011d1905a39ebb.jpg', null, null, null, null, '<p>测试</p>');
INSERT INTO `fruit_order_package` VALUES ('9', '8', '1', '23', '测试套餐8', '150.00', '170.00', '/uploads/2014/12/18/4696c2f9ba339f00a54be68025a0bc5b956b0ab0.jpg', '/uploads/2014/12/18/3e99390d580cdd7868cc7316669081e6e1be5f26.jpg', null, null, null, null, '<p>阿大使</p>');
INSERT INTO `fruit_order_package` VALUES ('10', '8', '1', '22', '测试套餐5', '120.00', '160.00', '/uploads/2014/12/18/e78e6eacde5ba0b08b9a2109ec25c8ffb16ed08d.jpg', '/uploads/2014/12/18/d4e2b421422c72fb90edc3003c011d1905a39ebb.jpg', null, null, null, null, '<p>测试</p>');
INSERT INTO `fruit_order_package` VALUES ('11', '9', '2', '23', '测试套餐8', '150.00', '170.00', '/uploads/2014/12/18/4696c2f9ba339f00a54be68025a0bc5b956b0ab0.jpg', '/uploads/2014/12/18/3e99390d580cdd7868cc7316669081e6e1be5f26.jpg', null, null, null, null, '<p>阿大使</p>');
INSERT INTO `fruit_order_package` VALUES ('12', '9', '1', '22', '测试套餐5', '120.00', '160.00', '/uploads/2014/12/18/e78e6eacde5ba0b08b9a2109ec25c8ffb16ed08d.jpg', '/uploads/2014/12/18/d4e2b421422c72fb90edc3003c011d1905a39ebb.jpg', null, null, null, null, '<p>测试</p>');
INSERT INTO `fruit_order_package` VALUES ('13', '10', '2', '19', '測試套餐1', '60.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>');
INSERT INTO `fruit_order_package` VALUES ('14', '11', '2', '19', '測試套餐1', '60.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>');

-- ----------------------------
-- Table structure for `fruit_order_package_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_package_goods`;
CREATE TABLE `fruit_order_package_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `package_id` int(11) NOT NULL COMMENT '套餐ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `goods_quantity` int(11) NOT NULL COMMENT '套餐商品数量',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL COMMENT '商品总价',
  `_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `single_price` decimal(10,2) NOT NULL COMMENT '商品单价',
  `unit` varchar(20) NOT NULL COMMENT '价格单位',
  `single_unit` varchar(20) NOT NULL COMMENT '单价单位',
  `amount` int(11) DEFAULT NULL COMMENT '每盒个数',
  `weight` int(11) DEFAULT NULL COMMENT '每盒重量',
  `thumb` varchar(255) NOT NULL COMMENT '商品缩略图',
  `image_1` varchar(255) NOT NULL COMMENT '商品图片1',
  `image_2` varchar(255) DEFAULT NULL COMMENT '商品图片2',
  `image_3` varchar(255) DEFAULT NULL COMMENT '商品图片3',
  `image_4` varchar(255) DEFAULT NULL COMMENT '商品图片4',
  `image_5` varchar(255) DEFAULT NULL COMMENT '商品图片5',
  `description` text COMMENT '商品简介',
  `parent_category` varchar(60) NOT NULL COMMENT '父分类名',
  `child_category` varchar(60) NOT NULL COMMENT '子分类名',
  `tag_name` varchar(60) NOT NULL COMMENT '标签名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='订单套餐商品表';

-- ----------------------------
-- Records of fruit_order_package_goods
-- ----------------------------
INSERT INTO `fruit_order_package_goods` VALUES ('1', '1', '19', '1', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('2', '1', '19', '2', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_package_goods` VALUES ('3', '2', '19', '1', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('4', '2', '19', '2', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_package_goods` VALUES ('5', '3', '19', '1', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('6', '3', '19', '2', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_package_goods` VALUES ('7', '4', '19', '1', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('8', '4', '19', '2', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_package_goods` VALUES ('9', '5', '19', '1', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('10', '5', '19', '2', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_package_goods` VALUES ('11', '6', '19', '1', '1', '測試商品1', '12.00', '24.00', '6.00', '元/斤', '元/斤', '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '<p>測試商品1</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('12', '6', '19', '2', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_package_goods` VALUES ('13', '7', '23', '6', '10', '测试商品6', '12.00', '123.00', '11.00', '元/斤', '元/斤', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '<p>测试</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('14', '7', '23', '5', '1', '测试商品5', '50.00', '60.00', '20.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/12/13/5720a00408371ad16fbcac8a3f0670ff36d806ed.png', '/uploads/2014/12/13/a92f7bcc0489e51f85552be72b43d4090aaea92a.jpg', '/uploads/2014/12/13/1df38f575ff2609a50065076c0df734324f63240.jpg', '/uploads/2014/12/13/5b8a283638c295a79cf46b7bb08d294067377be0.jpg', '/uploads/2014/12/13/017e76222d2a3e96f8ced63f8c6e47184abf5264.jpg', '/uploads/2014/12/13/93f65eb76fc80900f8c56f8f4b54bb82a3bae62a.jpg', '<p>测试商品5</p>', '測試大分類1', '测试小分类2', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('15', '7', '22', '6', '5', '测试商品6', '12.00', '123.00', '11.00', '元/斤', '元/斤', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '<p>测试</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('16', '7', '22', '5', '2', '测试商品5', '50.00', '60.00', '20.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/12/13/5720a00408371ad16fbcac8a3f0670ff36d806ed.png', '/uploads/2014/12/13/a92f7bcc0489e51f85552be72b43d4090aaea92a.jpg', '/uploads/2014/12/13/1df38f575ff2609a50065076c0df734324f63240.jpg', '/uploads/2014/12/13/5b8a283638c295a79cf46b7bb08d294067377be0.jpg', '/uploads/2014/12/13/017e76222d2a3e96f8ced63f8c6e47184abf5264.jpg', '/uploads/2014/12/13/93f65eb76fc80900f8c56f8f4b54bb82a3bae62a.jpg', '<p>测试商品5</p>', '測試大分類1', '测试小分类2', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('17', '8', '23', '6', '10', '测试商品6', '12.00', '123.00', '11.00', '元/斤', '元/斤', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '<p>测试</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('18', '8', '23', '5', '1', '测试商品5', '50.00', '60.00', '20.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/12/13/5720a00408371ad16fbcac8a3f0670ff36d806ed.png', '/uploads/2014/12/13/a92f7bcc0489e51f85552be72b43d4090aaea92a.jpg', '/uploads/2014/12/13/1df38f575ff2609a50065076c0df734324f63240.jpg', '/uploads/2014/12/13/5b8a283638c295a79cf46b7bb08d294067377be0.jpg', '/uploads/2014/12/13/017e76222d2a3e96f8ced63f8c6e47184abf5264.jpg', '/uploads/2014/12/13/93f65eb76fc80900f8c56f8f4b54bb82a3bae62a.jpg', '<p>测试商品5</p>', '測試大分類1', '测试小分类2', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('19', '8', '22', '6', '5', '测试商品6', '12.00', '123.00', '11.00', '元/斤', '元/斤', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '<p>测试</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('20', '8', '22', '5', '2', '测试商品5', '50.00', '60.00', '20.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/12/13/5720a00408371ad16fbcac8a3f0670ff36d806ed.png', '/uploads/2014/12/13/a92f7bcc0489e51f85552be72b43d4090aaea92a.jpg', '/uploads/2014/12/13/1df38f575ff2609a50065076c0df734324f63240.jpg', '/uploads/2014/12/13/5b8a283638c295a79cf46b7bb08d294067377be0.jpg', '/uploads/2014/12/13/017e76222d2a3e96f8ced63f8c6e47184abf5264.jpg', '/uploads/2014/12/13/93f65eb76fc80900f8c56f8f4b54bb82a3bae62a.jpg', '<p>测试商品5</p>', '測試大分類1', '测试小分类2', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('21', '9', '23', '6', '10', '测试商品6', '12.00', '123.00', '11.00', '元/斤', '元/斤', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '<p>测试</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('22', '9', '23', '5', '1', '测试商品5', '50.00', '60.00', '20.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/12/13/5720a00408371ad16fbcac8a3f0670ff36d806ed.png', '/uploads/2014/12/13/a92f7bcc0489e51f85552be72b43d4090aaea92a.jpg', '/uploads/2014/12/13/1df38f575ff2609a50065076c0df734324f63240.jpg', '/uploads/2014/12/13/5b8a283638c295a79cf46b7bb08d294067377be0.jpg', '/uploads/2014/12/13/017e76222d2a3e96f8ced63f8c6e47184abf5264.jpg', '/uploads/2014/12/13/93f65eb76fc80900f8c56f8f4b54bb82a3bae62a.jpg', '<p>测试商品5</p>', '測試大分類1', '测试小分类2', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('23', '9', '22', '6', '5', '测试商品6', '12.00', '123.00', '11.00', '元/斤', '元/斤', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '<p>测试</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('24', '9', '22', '5', '2', '测试商品5', '50.00', '60.00', '20.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/12/13/5720a00408371ad16fbcac8a3f0670ff36d806ed.png', '/uploads/2014/12/13/a92f7bcc0489e51f85552be72b43d4090aaea92a.jpg', '/uploads/2014/12/13/1df38f575ff2609a50065076c0df734324f63240.jpg', '/uploads/2014/12/13/5b8a283638c295a79cf46b7bb08d294067377be0.jpg', '/uploads/2014/12/13/017e76222d2a3e96f8ced63f8c6e47184abf5264.jpg', '/uploads/2014/12/13/93f65eb76fc80900f8c56f8f4b54bb82a3bae62a.jpg', '<p>测试商品5</p>', '測試大分類1', '测试小分类2', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('25', '10', '19', '2', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_package_goods` VALUES ('26', '10', '19', '6', '10', '测试商品6', '12.00', '123.00', '11.00', '元/斤', '元/斤', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '<p>测试</p>', '測試大分類1', '測試小分類1', '測試標籤1');
INSERT INTO `fruit_order_package_goods` VALUES ('27', '11', '19', '2', '2', '測試商品2', '12.00', '42.00', '0.00', '元/斤', '元/斤', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '<p>測試商品2</p>', '測試大分類1', '測試小分類1', '测试标签');
INSERT INTO `fruit_order_package_goods` VALUES ('28', '11', '19', '6', '10', '测试商品6', '12.00', '123.00', '11.00', '元/斤', '元/斤', '11', '12', '/uploads/2014/12/14/add6a1471bd86827d216449336ec9c93d7000f6f.jpg', '/uploads/2014/12/14/e2920df132ee39a1ad98fb1118307819294ea1e8.jpg', '/uploads/2014/12/14/6c76b9e65d38eef00dfd0c9acd6acd880acf788a.jpg', '/uploads/2014/12/14/810fae9d15b1d6535e282b68388d3f501b4dedca.jpg', '/uploads/2014/12/14/a413d12615c8cd1fc44428c54daab933337ac266.jpg', '/uploads/2014/12/14/c66dc2c5cff79e2d1607d2ad4a72d5f487528edb.jpg', '<p>测试</p>', '測試大分類1', '測試小分類1', '測試標籤1');

-- ----------------------------
-- Table structure for `fruit_package`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_package`;
CREATE TABLE `fruit_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '套餐名字',
  `price` decimal(10,2) NOT NULL COMMENT '套餐价格',
  `_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `image_1` varchar(255) NOT NULL COMMENT '介绍图片1',
  `image_2` varchar(255) DEFAULT NULL COMMENT '介绍图片2',
  `image_3` varchar(255) DEFAULT NULL COMMENT '介绍图片3',
  `image_4` varchar(255) DEFAULT NULL COMMENT '介绍图片4',
  `image_5` varchar(255) DEFAULT NULL COMMENT '介绍图片5',
  `description` text COMMENT '套餐介绍',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除（0：否，1：是）',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='套餐表';

-- ----------------------------
-- Records of fruit_package
-- ----------------------------
INSERT INTO `fruit_package` VALUES ('19', '測試套餐1', '60.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>', '0', '1416567703', '1419745634');
INSERT INTO `fruit_package` VALUES ('20', '測試套餐2', '12.00', '20.00', '/uploads/2014/11/21/9b370b6be335abd01c70ba6f5c69e061f214c913.jpg', '/uploads/2014/11/21/3faeb831305b752a4660a6fc052dac4db1d1735d.jpg', '/uploads/2014/11/21/f5967c6e4bb31f97035754123106ba0b7bad9e12.jpg', '/uploads/2014/11/21/b80d84b413ecefff68b6803ecc6c2bc11130a09f.jpg', '/uploads/2014/11/21/200711d5b532929bec1c03889289eebf31080560.jpg', '/uploads/2014/11/21/c60415485dc372eb1957dc155bbbad334e36ab6b.jpg', '<p>這是一個測試套餐</p>', '1', '1416567758', '1416567773');
INSERT INTO `fruit_package` VALUES ('21', '测试套餐3', '12.00', '12.00', '/uploads/2014/12/15/bdecfbd139191dc86f04a748678603780452c458.jpg', '/uploads/2014/12/15/9c224bc9a43ac4191940781746649dcf38b22ed3.jpg', '/uploads/2014/12/15/64267f14c5cbd6c08ff5e5da1afd64692b9e5b6f.jpg', null, null, null, '<p>测试</p>', '0', '1418615122', '1418615430');
INSERT INTO `fruit_package` VALUES ('22', '测试套餐5', '120.00', '160.00', '/uploads/2014/12/18/e78e6eacde5ba0b08b9a2109ec25c8ffb16ed08d.jpg', '/uploads/2014/12/18/d4e2b421422c72fb90edc3003c011d1905a39ebb.jpg', null, null, null, null, '<p>测试</p>', '0', '1418873921', null);
INSERT INTO `fruit_package` VALUES ('23', '测试套餐8', '150.00', '170.00', '/uploads/2014/12/18/4696c2f9ba339f00a54be68025a0bc5b956b0ab0.jpg', '/uploads/2014/12/18/3e99390d580cdd7868cc7316669081e6e1be5f26.jpg', null, null, null, null, '<p>阿大使</p>', '0', '1418873966', '1419686761');

-- ----------------------------
-- Table structure for `fruit_package_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_package_goods`;
CREATE TABLE `fruit_package_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `package_id` int(11) NOT NULL COMMENT '套餐ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `amount` int(11) NOT NULL COMMENT '商品数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='套餐商品表';

-- ----------------------------
-- Records of fruit_package_goods
-- ----------------------------
INSERT INTO `fruit_package_goods` VALUES ('11', '20', '3', '2');
INSERT INTO `fruit_package_goods` VALUES ('12', '20', '4', '3');
INSERT INTO `fruit_package_goods` VALUES ('13', '20', '1', '10');
INSERT INTO `fruit_package_goods` VALUES ('15', '21', '5', '2');
INSERT INTO `fruit_package_goods` VALUES ('16', '22', '5', '2');
INSERT INTO `fruit_package_goods` VALUES ('17', '22', '6', '5');
INSERT INTO `fruit_package_goods` VALUES ('24', '23', '5', '1');
INSERT INTO `fruit_package_goods` VALUES ('25', '23', '6', '10');
INSERT INTO `fruit_package_goods` VALUES ('29', '19', '1', '1');
INSERT INTO `fruit_package_goods` VALUES ('30', '19', '2', '2');
INSERT INTO `fruit_package_goods` VALUES ('31', '19', '6', '10');

-- ----------------------------
-- Table structure for `fruit_parent_category`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_parent_category`;
CREATE TABLE `fruit_parent_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(60) NOT NULL COMMENT '分类名称',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `description` text COMMENT '描述',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='父分类表';

-- ----------------------------
-- Records of fruit_parent_category
-- ----------------------------
INSERT INTO `fruit_parent_category` VALUES ('1', '測試大分類1', '0', '测试一下', '1415615785', '1418367827');
INSERT INTO `fruit_parent_category` VALUES ('2', '测试大分类2', '0', null, '1418367803', '1418367837');

-- ----------------------------
-- Table structure for `fruit_purchase`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_purchase`;
CREATE TABLE `fruit_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `is_purchase` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否采购（0：否，1：是）',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `branch_id` int(11) DEFAULT NULL COMMENT '分店',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `quantity` int(11) NOT NULL COMMENT '数量',
  `order_time` int(10) NOT NULL,
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='采购表';

-- ----------------------------
-- Records of fruit_purchase
-- ----------------------------
INSERT INTO `fruit_purchase` VALUES ('3', '0', '3', '8', '1', '10', '1419392782', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('4', '0', '4', '8', '1', '10', '1419392783', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('5', '0', '5', '8', '1', '10', '1419392785', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('6', '0', '6', '9', '1', '10', '1419394102', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('11', '0', '3', '8', '1', '1', '1419392782', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('12', '0', '3', '8', '2', '2', '1419392782', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('13', '0', '4', '8', '1', '1', '1419392783', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('14', '0', '4', '8', '2', '2', '1419392783', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('15', '0', '5', '8', '1', '1', '1419392785', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('16', '0', '5', '8', '2', '2', '1419392785', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('17', '0', '6', '9', '1', '2', '1419394102', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('18', '0', '6', '9', '2', '4', '1419394102', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('25', '0', '3', '8', '1', '10', '1419392782', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('26', '0', '3', '8', '2', '8', '1419392782', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('27', '0', '3', '8', '3', '6', '1419392782', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('28', '0', '4', '8', '1', '10', '1419392783', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('29', '0', '4', '8', '2', '8', '1419392783', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('30', '0', '4', '8', '3', '6', '1419392783', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('31', '0', '5', '8', '1', '10', '1419392785', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('32', '0', '5', '8', '2', '8', '1419392785', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('33', '0', '5', '8', '3', '6', '1419392785', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('34', '0', '6', '9', '1', '10', '1419394102', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('35', '0', '6', '9', '2', '8', '1419394102', '1419398403');
INSERT INTO `fruit_purchase` VALUES ('36', '0', '6', '9', '3', '6', '1419394102', '1419398403');

-- ----------------------------
-- Table structure for `fruit_returns`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_returns`;
CREATE TABLE `fruit_returns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `order_number` char(14) NOT NULL COMMENT '订单号',
  `reason` varchar(255) NOT NULL COMMENT '退货原因',
  `image_1` varchar(255) DEFAULT NULL COMMENT '图片1',
  `image_2` varchar(255) DEFAULT NULL COMMENT '图片2',
  `image_3` varchar(255) DEFAULT NULL COMMENT '图片3',
  `postscript` varchar(255) DEFAULT NULL COMMENT '补充说明',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='退货表';

-- ----------------------------
-- Records of fruit_returns
-- ----------------------------
INSERT INTO `fruit_returns` VALUES ('1', '1', '14111752489899', '有烂果', '/uploads/2014/11/18/a7628e2eb56c480e1b5dd1aa7daaa47f3c17cbb0.png', '/uploads/2014/11/18/5eadf5ad6542cf4e5e2278909d901b752281066a.png', null, '补充说明', '1416279982');
INSERT INTO `fruit_returns` VALUES ('2', '1', '14120154499810', '不喜欢', null, null, null, null, '1417413308');
INSERT INTO `fruit_returns` VALUES ('3', '1', '14120154499810', '货不对板', null, null, null, null, '1417603509');
INSERT INTO `fruit_returns` VALUES ('4', '1', '14122410248511', '好难吃', null, null, null, null, '1419690103');

-- ----------------------------
-- Table structure for `fruit_send_history`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_send_history`;
CREATE TABLE `fruit_send_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sendno` int(10) NOT NULL COMMENT '发送编号',
  `notification_id` int(11) NOT NULL COMMENT '消息ID',
  `title` varchar(60) NOT NULL COMMENT '标题',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `send_time` int(10) NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='发送历史表';

-- ----------------------------
-- Records of fruit_send_history
-- ----------------------------
INSERT INTO `fruit_send_history` VALUES ('1', '1', '1', '测试', '这是一个测试', '1415768418');
INSERT INTO `fruit_send_history` VALUES ('2', '1418379157', '4', '测试', '测试一下', '1418379158');

-- ----------------------------
-- Table structure for `fruit_shipping_address`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_shipping_address`;
CREATE TABLE `fruit_shipping_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province` varchar(255) NOT NULL COMMENT '省份',
  `city` varchar(255) NOT NULL COMMENT '市',
  `district` varchar(255) DEFAULT NULL COMMENT '区',
  `road_number` varchar(255) DEFAULT NULL COMMENT '路牌号',
  `community` varchar(255) DEFAULT NULL COMMENT '小区（社区）、建筑名',
  `building` varchar(255) DEFAULT NULL COMMENT '栋、几期、座',
  `shipping_fee` decimal(10,2) NOT NULL COMMENT '送货费',
  `discount` decimal(4,2) DEFAULT NULL COMMENT '价格调整比例',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `sf` (`shipping_fee`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='配送地址表';

-- ----------------------------
-- Records of fruit_shipping_address
-- ----------------------------
INSERT INTO `fruit_shipping_address` VALUES ('2', '广东省', '广州市', '天河区', '189號', '羊城花园', '康苑八座401', '10.00', '20.00', '1415874727', '1417927564');
INSERT INTO `fruit_shipping_address` VALUES ('3', '广东省', '广州市', '天河区', '211', '蓝天花园', '12', '14.00', '12.00', '1417593342', '1417927576');
INSERT INTO `fruit_shipping_address` VALUES ('4', '广东省', '广州市', '越秀区', '120', '某小区', '8', '12.00', '13.00', '1417671083', '1417927583');
INSERT INTO `fruit_shipping_address` VALUES ('5', '广东省', '广州市', '白云区', '14', '某小区', '4', '12.00', '13.00', '1417673983', '1417927591');
INSERT INTO `fruit_shipping_address` VALUES ('6', '广东省', '江门市', '新会区', '120', '新会花园', '12', '20.00', '20.00', '1417927625', null);
INSERT INTO `fruit_shipping_address` VALUES ('7', '广东省', '江门市', '新会区', '98', '江门花园', '20', '20.00', '20.00', '1417928236', null);
INSERT INTO `fruit_shipping_address` VALUES ('8', '广东省', '阳江市', '东城区', '120', '怡景花园', '四座', '12.00', '12.00', '1418799915', null);

-- ----------------------------
-- Table structure for `fruit_shopping_car`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_shopping_car`;
CREATE TABLE `fruit_shopping_car` (
  `shopping_car_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '购物车ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `package_id` int(11) DEFAULT NULL COMMENT '套餐ID',
  `custom_id` int(11) DEFAULT NULL COMMENT '定制ID',
  `quantity` int(11) NOT NULL COMMENT '数量',
  `add_time` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`shopping_car_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='购物车表';

-- ----------------------------
-- Records of fruit_shopping_car
-- ----------------------------
INSERT INTO `fruit_shopping_car` VALUES ('1', '1', '1', null, null, '10', '1416910517');
INSERT INTO `fruit_shopping_car` VALUES ('2', '1', null, '20', null, '2', '1416910517');
INSERT INTO `fruit_shopping_car` VALUES ('3', '1', null, null, '1', '1', '1416910517');

-- ----------------------------
-- Table structure for `fruit_tag`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_tag`;
CREATE TABLE `fruit_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(60) NOT NULL COMMENT '标签名称',
  `description` text COMMENT '标签描述',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='标签表';

-- ----------------------------
-- Records of fruit_tag
-- ----------------------------
INSERT INTO `fruit_tag` VALUES ('1', '測試標籤1', '/uploads/2014/11/10/8f6548b8bdb6beae0a0271cebd2ec0f92b5793a4.jpg', '1415615885', null);
INSERT INTO `fruit_tag` VALUES ('2', '测试标签', '测试一下', '1418364168', '1418364825');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='App版本管理表';

-- ----------------------------
-- Records of fruit_version
-- ----------------------------
INSERT INTO `fruit_version` VALUES ('1', '1.0.1', 'http://www.example.com/', '0', '1416885482');
