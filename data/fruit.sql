/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : fruit

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2014-12-09 14:50:01
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
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `_consignee` varchar(50) DEFAULT NULL COMMENT '备用收货人',
  `_phone` char(11) DEFAULT NULL COMMENT '备用收货人手机',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='地址表';

-- ----------------------------
-- Records of fruit_address
-- ----------------------------
INSERT INTO `fruit_address` VALUES ('4', '1', '中国电信', '13800000000', '广东省', '广州市', '天河区', '羊城花园', '广州市天河区中山大道羊城花园康苑八座402', '中国联通', '13800138001', '1415789535', '1416883529');
INSERT INTO `fruit_address` VALUES ('5', '1', '中国移动', '13800138000', '广东省', '广州市', '天河区', null, '广州市天河区中山大道羊城花园康苑八座401', '中国联通', '13800138001', '1415790242', null);

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
INSERT INTO `fruit_admin_priv` VALUES ('4', '4', 'index|all,login|all,member|index,goods|index,goods|add,goods|delete,goods|update,goods|advertisement,goods|update_status,goods|update_priority,category|parent_index,category|child_index,tag|index,package|index,courier|index,shipping|index,branch|index,coupon|rule,coupon|usage,coupon|add_usage,coupon|update_usage,order|index,order|history,notification|index,version|android,feedback|index,returns|index');
INSERT INTO `fruit_admin_priv` VALUES ('5', '5', 'index|all,login|all,branch|index,branch|update');
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
INSERT INTO `fruit_admin_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', '0', '1418094119', '1', '1', '系统管理员，勿删！');
INSERT INTO `fruit_admin_user` VALUES ('4', 'test', 'e10adc3949ba59abbe56e057f20f883e', 'test', null, '1415763083', '1417853763', '0', '1', null);
INSERT INTO `fruit_admin_user` VALUES ('5', 'demo', 'e10adc3949ba59abbe56e057f20f883e', 'demo', null, '1417593149', '1417597300', '0', '1', null);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='黑名单用户表';

-- ----------------------------
-- Records of fruit_blacklist
-- ----------------------------

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
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='子分类表';

-- ----------------------------
-- Records of fruit_child_category
-- ----------------------------
INSERT INTO `fruit_child_category` VALUES ('1', '1', '測試小分類1', '1415615797', '1415615815');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='水果劵表';

-- ----------------------------
-- Records of fruit_coupon
-- ----------------------------
INSERT INTO `fruit_coupon` VALUES ('1', '1', '4', '2', '1417073018', '1419609600');
INSERT INTO `fruit_coupon` VALUES ('2', '8', '10', '1', '1417073018', '1419609600');
INSERT INTO `fruit_coupon` VALUES ('3', '1', '5', '4', '1417073039', '1417881600');
INSERT INTO `fruit_coupon` VALUES ('5', '1', '5', '4', '1417577956', null);

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='送货人员表';

-- ----------------------------
-- Records of fruit_courier
-- ----------------------------
INSERT INTO `fruit_courier` VALUES ('3', '测试送货员', '13800138000', '1417335640', null);
INSERT INTO `fruit_courier` VALUES ('4', 'demo送货员', '13900000000', '1417593388', null);
INSERT INTO `fruit_courier` VALUES ('5', 'test', '13412345678', '1417669712', '1417669776');
INSERT INTO `fruit_courier` VALUES ('6', 'test1', '13812345678', '1417669753', '1417669770');
INSERT INTO `fruit_courier` VALUES ('7', '阿茂', '13912345678', '1417928082', null);

-- ----------------------------
-- Table structure for `fruit_custom`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_custom`;
CREATE TABLE `fruit_custom` (
  `custom_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(30) NOT NULL COMMENT '定制名称',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`custom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='我的定制表';

-- ----------------------------
-- Records of fruit_custom
-- ----------------------------
INSERT INTO `fruit_custom` VALUES ('1', '1', '我的定制', '1416906869');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='定制商品表';

-- ----------------------------
-- Records of fruit_custom_goods
-- ----------------------------
INSERT INTO `fruit_custom_goods` VALUES ('1', '1', '1', '10', '1416906869');
INSERT INTO `fruit_custom_goods` VALUES ('2', '1', '2', '8', '1416906869');
INSERT INTO `fruit_custom_goods` VALUES ('3', '1', '3', '6', '1416907013');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='默认地址表';

-- ----------------------------
-- Records of fruit_default_address
-- ----------------------------
INSERT INTO `fruit_default_address` VALUES ('1', '1', '4', '1415845607');

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
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户反馈表';

-- ----------------------------
-- Records of fruit_feedback
-- ----------------------------
INSERT INTO `fruit_feedback` VALUES ('1', '1', '14111410253561', '1', '1', '1', 'postscript testing', '1416219320');

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
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '商品状态（0：下架，1：上架）',
  `unit` varchar(20) NOT NULL COMMENT '价格单位',
  `tag` int(11) DEFAULT NULL COMMENT '商品标签',
  `amount` int(11) DEFAULT NULL COMMENT '每盒个数',
  `weight` int(11) DEFAULT NULL COMMENT '每盒重量',
  `thumb` varchar(255) NOT NULL COMMENT '商品缩略图',
  `priority` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `image_1` varchar(255) NOT NULL COMMENT '商品图片1',
  `image_2` varchar(255) NOT NULL COMMENT '商品图片2',
  `image_3` varchar(255) NOT NULL COMMENT '商品图片3',
  `image_4` varchar(255) NOT NULL COMMENT '商品图片4',
  `image_5` varchar(255) NOT NULL COMMENT '商品图片5',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除（0：否，1：是）',
  `description` text COMMENT '商品简介',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `pct` (`p_cate_id`,`c_cate_id`,`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of fruit_goods
-- ----------------------------
INSERT INTO `fruit_goods` VALUES ('1', '1', '1', '測試商品1', '12.00', '24.00', '1', '元/斤', null, '12', '300', '/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg', '11', '/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg', '/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg', '/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg', '/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg', '/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg', '0', '<p>測試商品1</p>', '1415615863', null);
INSERT INTO `fruit_goods` VALUES ('2', '1', '1', '測試商品2', '12.00', '42.00', '1', '元/斤', '1', '20', '400', '/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg', '21', '/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg', '/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg', '/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg', '/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg', '/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg', '0', '<p>測試商品2</p>', '1415615924', null);
INSERT INTO `fruit_goods` VALUES ('3', '1', '1', '測試商品3', '1.00', '2.00', '1', '元/斤', '1', '12', '300', '/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg', '10', '/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg', '/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg', '/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg', '/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg', '/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg', '1', '<p>測試商品3</p>', '1415859221', '1415859538');
INSERT INTO `fruit_goods` VALUES ('4', '1', '1', '測試商品4', '12.00', '29.00', '1', '元/斤', '1', '12', '200', '/uploads/2014/11/13/913f61608dc1fe5ccfdd70db828ffe3dcbb921d5.jpg', '13', '/uploads/2014/11/13/9c0b367ab7be91a64ab3eedb3db9569372a7f14b.jpg', '/uploads/2014/11/13/9feef9d4e6b4da9c282129e2bb4a6608964324b9.jpg', '/uploads/2014/11/13/44a3ba953a5ae2e230a4340762eab757aae6d5df.jpg', '/uploads/2014/11/13/9f11b7a0433dd4aa9ac0312668c26cfd5d1197af.jpg', '/uploads/2014/11/13/f3edeefa2ca2509bc74fee5fe11884e8b5e3a34d.jpg', '1', '<p>測試商品4</p>', '1415859581', null);

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
INSERT INTO `fruit_member` VALUES ('1', '13800138000', 'e10adc3949ba59abbe56e057f20f883e', 'CMCC', '中国移动', '/uploads/2014/11/13/913f61608dc1fe5ccfdd70db828ffe3dcbb921d5.jpg', '1', '测试1', '1415763408', '1416971613');
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
  `status` tinyint(1) NOT NULL COMMENT '订单状态（1：待确定，2：配送中，3：已收货，4：拒收）',
  `shipping_time` char(11) DEFAULT NULL COMMENT '开始送货时间',
  `shipping_fee` decimal(10,2) NOT NULL COMMENT '送货费',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `coupon` int(11) DEFAULT NULL COMMENT '使用水果劵',
  `total_amount` decimal(10,2) NOT NULL COMMENT '订单金额',
  `courier_id` int(11) DEFAULT NULL COMMENT '送货员ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`order_id`),
  KEY `us` (`user_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of fruit_order
-- ----------------------------
INSERT INTO `fruit_order` VALUES ('1', '1', '4', '14111410253561', '2', '12:00-18:00', '12.50', 'nothing', null, '216.00', '5', '1415960111', '1417766759');
INSERT INTO `fruit_order` VALUES ('3', '1', '4', '14112049535657', '2', null, '42.50', null, null, '228.00', '5', '1416473921', '1417766629');
INSERT INTO `fruit_order` VALUES ('4', '1', '4', '14120154499810', '1', '12:00-18:00', '15.00', 'nothing', null, '252.00', '5', '1417402742', '1417853577');
INSERT INTO `fruit_order` VALUES ('5', '1', '5', '14120310055100', '2', null, '15.00', null, '6', '252.00', '5', '1417587149', '1417845408');
INSERT INTO `fruit_order` VALUES ('7', '1', '4', '14120454981015', '2', null, '12.00', null, null, '264.00', '5', '1417678902', '1417845408');

-- ----------------------------
-- Table structure for `fruit_order_custom`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_custom`;
CREATE TABLE `fruit_order_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `custom_id` int(11) NOT NULL COMMENT '定制ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `amount` int(11) NOT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='订单定制表';

-- ----------------------------
-- Records of fruit_order_custom
-- ----------------------------
INSERT INTO `fruit_order_custom` VALUES ('1', '1', '4', '1');
INSERT INTO `fruit_order_custom` VALUES ('2', '1', '5', '1');

-- ----------------------------
-- Table structure for `fruit_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_goods`;
CREATE TABLE `fruit_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `amount` int(11) NOT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='订单商品表';

-- ----------------------------
-- Records of fruit_order_goods
-- ----------------------------
INSERT INTO `fruit_order_goods` VALUES ('1', '1', '1', '10');
INSERT INTO `fruit_order_goods` VALUES ('2', '2', '1', '8');
INSERT INTO `fruit_order_goods` VALUES ('4', '1', '3', '10');
INSERT INTO `fruit_order_goods` VALUES ('5', '2', '3', '8');
INSERT INTO `fruit_order_goods` VALUES ('6', '3', '4', '6');
INSERT INTO `fruit_order_goods` VALUES ('7', '3', '5', '6');
INSERT INTO `fruit_order_goods` VALUES ('10', '1', '7', '10');
INSERT INTO `fruit_order_goods` VALUES ('11', '2', '7', '12');

-- ----------------------------
-- Table structure for `fruit_order_package`
-- ----------------------------
DROP TABLE IF EXISTS `fruit_order_package`;
CREATE TABLE `fruit_order_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `package_id` int(11) NOT NULL COMMENT '套餐ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `amount` int(11) NOT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='订单套餐表';

-- ----------------------------
-- Records of fruit_order_package
-- ----------------------------
INSERT INTO `fruit_order_package` VALUES ('2', '20', '3', '1');
INSERT INTO `fruit_order_package` VALUES ('3', '20', '4', '2');
INSERT INTO `fruit_order_package` VALUES ('4', '20', '5', '2');

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
  `image_2` varchar(255) NOT NULL COMMENT '介绍图片2',
  `image_3` varchar(255) NOT NULL COMMENT '介绍图片3',
  `image_4` varchar(255) NOT NULL COMMENT '介绍图片4',
  `image_5` varchar(255) NOT NULL COMMENT '介绍图片5',
  `description` text COMMENT '套餐介绍',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除（0：否，1：是）',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='套餐表';

-- ----------------------------
-- Records of fruit_package
-- ----------------------------
INSERT INTO `fruit_package` VALUES ('19', '測試套餐1', '12.00', '34.00', '/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg', '/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg', '/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg', '/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg', '/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg', '/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg', '<p>這是套餐測試</p>', '1', '1416567703', null);
INSERT INTO `fruit_package` VALUES ('20', '測試套餐2', '12.00', '20.00', '/uploads/2014/11/21/9b370b6be335abd01c70ba6f5c69e061f214c913.jpg', '/uploads/2014/11/21/3faeb831305b752a4660a6fc052dac4db1d1735d.jpg', '/uploads/2014/11/21/f5967c6e4bb31f97035754123106ba0b7bad9e12.jpg', '/uploads/2014/11/21/b80d84b413ecefff68b6803ecc6c2bc11130a09f.jpg', '/uploads/2014/11/21/200711d5b532929bec1c03889289eebf31080560.jpg', '/uploads/2014/11/21/c60415485dc372eb1957dc155bbbad334e36ab6b.jpg', '<p>這是一個測試套餐</p>', '1', '1416567758', '1416567773');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='套餐商品表';

-- ----------------------------
-- Records of fruit_package_goods
-- ----------------------------
INSERT INTO `fruit_package_goods` VALUES ('7', '19', '1', '1');
INSERT INTO `fruit_package_goods` VALUES ('8', '19', '2', '2');
INSERT INTO `fruit_package_goods` VALUES ('11', '20', '3', '2');
INSERT INTO `fruit_package_goods` VALUES ('12', '20', '4', '3');
INSERT INTO `fruit_package_goods` VALUES ('13', '20', '1', '10');

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
INSERT INTO `fruit_parent_category` VALUES ('1', '測試大分類1', '1415615785', '1415615808');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='退货表';

-- ----------------------------
-- Records of fruit_returns
-- ----------------------------
INSERT INTO `fruit_returns` VALUES ('1', '1', '14111752489899', '有烂果', '/uploads/2014/11/18/a7628e2eb56c480e1b5dd1aa7daaa47f3c17cbb0.png', '/uploads/2014/11/18/5eadf5ad6542cf4e5e2278909d901b752281066a.png', null, '补充说明', '1416279982');
INSERT INTO `fruit_returns` VALUES ('2', '1', '14120154499810', '不喜欢', null, null, null, null, '1417413308');
INSERT INTO `fruit_returns` VALUES ('3', '1', '14120154499810', '货不对板', null, null, null, null, '1417603509');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='发送历史表';

-- ----------------------------
-- Records of fruit_send_history
-- ----------------------------
INSERT INTO `fruit_send_history` VALUES ('1', '1', '1', '测试', '这是一个测试', '1415768418');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='配送地址表';

-- ----------------------------
-- Records of fruit_shipping_address
-- ----------------------------
INSERT INTO `fruit_shipping_address` VALUES ('2', '广东省', '廣州市', '天河區', '189號', '羊城花園', '康苑八座401', '10.00', '20.00', '1415874727', '1417927564');
INSERT INTO `fruit_shipping_address` VALUES ('3', '广东省', '广州市', '天河区', '211', '蓝天花园', '12', '14.00', '12.00', '1417593342', '1417927576');
INSERT INTO `fruit_shipping_address` VALUES ('4', '广东省', '广州市', '越秀区', '120', '某小区', '8', '12.00', '13.00', '1417671083', '1417927583');
INSERT INTO `fruit_shipping_address` VALUES ('5', '广东省', '广州市', '白云区', '14', '某小区', '4', '12.00', '13.00', '1417673983', '1417927591');
INSERT INTO `fruit_shipping_address` VALUES ('6', '广东省', '江门市', '新会区', '120', '新会花园', '12', '20.00', '20.00', '1417927625', null);
INSERT INTO `fruit_shipping_address` VALUES ('7', '广东省', '江门市', '新会区', '98', '江门花园', '20', '20.00', '20.00', '1417928236', null);

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
  `image` varchar(255) NOT NULL COMMENT '标签图片',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='标签表';

-- ----------------------------
-- Records of fruit_tag
-- ----------------------------
INSERT INTO `fruit_tag` VALUES ('1', '測試標籤1', '/uploads/2014/11/10/8f6548b8bdb6beae0a0271cebd2ec0f92b5793a4.jpg', '1415615885', null);

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
