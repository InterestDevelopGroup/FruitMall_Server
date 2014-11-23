CREATE DATABASE  IF NOT EXISTS `fruit` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `fruit`;
-- MySQL dump 10.13  Distrib 5.6.17, for osx10.6 (i386)
--
-- Host: localhost    Database: fruit
-- ------------------------------------------------------
-- Server version	5.6.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `fruit_address`
--

DROP TABLE IF EXISTS `fruit_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `consignee` varchar(30) NOT NULL COMMENT '收货人',
  `phone` char(11) NOT NULL COMMENT '收货人手机',
  `province` varchar(50) NOT NULL COMMENT '省份',
  `city` varchar(50) NOT NULL COMMENT '城市',
  `district` varchar(50) DEFAULT NULL COMMENT '区',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `_consignee` varchar(50) DEFAULT NULL COMMENT '备用收货人',
  `_phone` char(11) DEFAULT NULL COMMENT '备用收货人手机',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='地址表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_address`
--

LOCK TABLES `fruit_address` WRITE;
/*!40000 ALTER TABLE `fruit_address` DISABLE KEYS */;
INSERT INTO `fruit_address` VALUES (4,1,'中国电信','13800000000','广东省','广州市','天河区','广州市天河区中山大道羊城花园康苑八座402','中国联通','13800138001',1415789535,1415790180),(5,1,'中国移动','13800138000','广东省','广州市','天河区','广州市天河区中山大道羊城花园康苑八座401','中国联通','13800138001',1415790242,NULL);
/*!40000 ALTER TABLE `fruit_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_admin_priv`
--

DROP TABLE IF EXISTS `fruit_admin_priv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_admin_priv` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) NOT NULL COMMENT '管理员ID',
  `priv` text NOT NULL COMMENT '管理员权限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理员权限表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_admin_priv`
--

LOCK TABLES `fruit_admin_priv` WRITE;
/*!40000 ALTER TABLE `fruit_admin_priv` DISABLE KEYS */;
INSERT INTO `fruit_admin_priv` VALUES (1,1,'all'),(4,4,'index|all,login|all,goods|index,category|parent_index,category|child_index,tag|index,package|index,shipping|index,order|index,version|android,feedback|index,returns|index');
/*!40000 ALTER TABLE `fruit_admin_priv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_admin_user`
--

DROP TABLE IF EXISTS `fruit_admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='后台管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_admin_user`
--

LOCK TABLES `fruit_admin_user` WRITE;
/*!40000 ALTER TABLE `fruit_admin_user` DISABLE KEYS */;
INSERT INTO `fruit_admin_user` VALUES (1,'admin','e10adc3949ba59abbe56e057f20f883e','admin','admin@admin.com',0,1416749595,1,1,'系统管理员，勿删！'),(4,'test','e10adc3949ba59abbe56e057f20f883e','test',NULL,1415763083,1416714329,0,1,NULL);
/*!40000 ALTER TABLE `fruit_admin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_blacklist`
--

DROP TABLE IF EXISTS `fruit_blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='黑名单用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_blacklist`
--

LOCK TABLES `fruit_blacklist` WRITE;
/*!40000 ALTER TABLE `fruit_blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `fruit_blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_branch`
--

DROP TABLE IF EXISTS `fruit_branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '分店名称',
  `admin_id` int(11) DEFAULT NULL COMMENT '分店管理员ID',
  `courier` text COMMENT '送货人员',
  `range` text COMMENT '配送范围',
  `remark` text COMMENT '备注',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分店表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_branch`
--

LOCK TABLES `fruit_branch` WRITE;
/*!40000 ALTER TABLE `fruit_branch` DISABLE KEYS */;
/*!40000 ALTER TABLE `fruit_branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_child_category`
--

DROP TABLE IF EXISTS `fruit_child_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_child_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL COMMENT '父类ID',
  `name` varchar(60) NOT NULL COMMENT '分类名称',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='子分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_child_category`
--

LOCK TABLES `fruit_child_category` WRITE;
/*!40000 ALTER TABLE `fruit_child_category` DISABLE KEYS */;
INSERT INTO `fruit_child_category` VALUES (1,1,'測試小分類1',1415615797,1415615815);
/*!40000 ALTER TABLE `fruit_child_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_coupon`
--

DROP TABLE IF EXISTS `fruit_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `score` int(11) NOT NULL COMMENT '水果劵分数',
  `type` varchar(20) NOT NULL COMMENT '水果劵类型',
  `publish_time` int(11) NOT NULL COMMENT '发劵时间',
  `expire_time` int(11) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='水果劵表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_coupon`
--

LOCK TABLES `fruit_coupon` WRITE;
/*!40000 ALTER TABLE `fruit_coupon` DISABLE KEYS */;
INSERT INTO `fruit_coupon` VALUES (1,1,10,'recommend',1416725505,1419317505),(2,3,10,'register',1416725505,1419317505);
/*!40000 ALTER TABLE `fruit_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_default_address`
--

DROP TABLE IF EXISTS `fruit_default_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_default_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `address_id` int(11) NOT NULL COMMENT '地址ID',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='默认地址表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_default_address`
--

LOCK TABLES `fruit_default_address` WRITE;
/*!40000 ALTER TABLE `fruit_default_address` DISABLE KEYS */;
INSERT INTO `fruit_default_address` VALUES (1,1,4,1415845607);
/*!40000 ALTER TABLE `fruit_default_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_feedback`
--

DROP TABLE IF EXISTS `fruit_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_feedback`
--

LOCK TABLES `fruit_feedback` WRITE;
/*!40000 ALTER TABLE `fruit_feedback` DISABLE KEYS */;
INSERT INTO `fruit_feedback` VALUES (1,1,'14111410253561',1,1,1,'postscript testing',1416219320);
/*!40000 ALTER TABLE `fruit_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_goods`
--

DROP TABLE IF EXISTS `fruit_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `p_cate_id` int(11) NOT NULL COMMENT '大分类ID',
  `c_cate_id` int(11) NOT NULL COMMENT '小分类ID',
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT '商品单价',
  `_price` decimal(10,2) DEFAULT NULL COMMENT '市场价',
  `unit` varchar(20) NOT NULL COMMENT '价格单位',
  `tag` int(11) DEFAULT NULL COMMENT '商品标签',
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
  PRIMARY KEY (`id`),
  KEY `pct` (`p_cate_id`,`c_cate_id`,`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='商品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_goods`
--

LOCK TABLES `fruit_goods` WRITE;
/*!40000 ALTER TABLE `fruit_goods` DISABLE KEYS */;
INSERT INTO `fruit_goods` VALUES (1,1,1,'測試商品1',12.00,24.00,'元/斤',NULL,12,300,'/uploads/2014/11/10/a8c73d5a30ce2ded99fcf378b620634de47eacc0.jpg','/uploads/2014/11/10/f2f1dd304ae7f5242765e5beac4411e0b0783d59.jpg','/uploads/2014/11/10/0a865c6f2153d774cbc0a79b6142b762659ba0f0.jpg','/uploads/2014/11/10/0710e1974d0dd9b3753fea32198c2d60e0f18615.jpg','/uploads/2014/11/10/eace34d09ab9fc110bd5558c3e080663b98b55a4.jpg','/uploads/2014/11/10/894c2e141988ba0a0d2cb5d1610f2ab8c296a240.jpg','<p>測試商品1</p>',1415615863,NULL),(2,1,1,'測試商品2',12.00,42.00,'元/斤',1,20,400,'/uploads/2014/11/10/3c88ae75196920f4651c2587e063d8051b42b5ac.jpg','/uploads/2014/11/10/aad47186f5f4a961922a814dce4cce8b11eff97c.jpg','/uploads/2014/11/10/296bafebd522d0d69e0a7fc9d400e3e64e1a7f06.jpg','/uploads/2014/11/10/4374eb3b28d3d51dfa4ff0b956a80a44f710de81.jpg','/uploads/2014/11/10/08efced33d98f170345e8b9f1005822600f104e9.jpg','/uploads/2014/11/10/5670aeeba00791418e29162963cc967a2ec2bd8d.jpg','<p>測試商品2</p>',1415615924,NULL),(3,1,1,'測試商品3',1.00,2.00,'元/斤',1,12,300,'/uploads/2014/11/13/79a6fd13d303a0177f83b2948be241f91a9c1777.jpg','/uploads/2014/11/13/2c76012d0edf880afaf44601642a7b6b12402bc4.jpg','/uploads/2014/11/13/113e25470882c86516b9b690987ad0c1e7ef1433.jpg','/uploads/2014/11/13/2acd1f856170bc6d71fd39035b6a1a7ce5c9bde1.jpg','/uploads/2014/11/13/a7f1dbfa61e4dfe6c60b592deb607254b57f9205.jpg','/uploads/2014/11/13/559749228e2efce46cf08f042f01a21c70c65497.jpg','<p>測試商品3</p>',1415859221,1415859538),(4,1,1,'測試商品4',12.00,29.00,'元/斤',1,12,200,'/uploads/2014/11/13/913f61608dc1fe5ccfdd70db828ffe3dcbb921d5.jpg','/uploads/2014/11/13/9c0b367ab7be91a64ab3eedb3db9569372a7f14b.jpg','/uploads/2014/11/13/9feef9d4e6b4da9c282129e2bb4a6608964324b9.jpg','/uploads/2014/11/13/44a3ba953a5ae2e230a4340762eab757aae6d5df.jpg','/uploads/2014/11/13/9f11b7a0433dd4aa9ac0312668c26cfd5d1197af.jpg','/uploads/2014/11/13/f3edeefa2ca2509bc74fee5fe11884e8b5e3a34d.jpg','<p>測試商品4</p>',1415859581,NULL);
/*!40000 ALTER TABLE `fruit_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_member`
--

DROP TABLE IF EXISTS `fruit_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='会员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_member`
--

LOCK TABLES `fruit_member` WRITE;
/*!40000 ALTER TABLE `fruit_member` DISABLE KEYS */;
INSERT INTO `fruit_member` VALUES (1,'13800138000','e10adc3949ba59abbe56e057f20f883e','CMCC','中国移动','/uploads/2014/11/13/913f61608dc1fe5ccfdd70db828ffe3dcbb921d5.jpg',1,1415763408,1416631955),(3,'13437563074','e10adc3949ba59abbe56e057f20f883e',NULL,NULL,NULL,0,1416725505,NULL);
/*!40000 ALTER TABLE `fruit_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_order`
--

DROP TABLE IF EXISTS `fruit_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `address_id` int(11) NOT NULL COMMENT '收获地址ID',
  `order_number` char(14) NOT NULL COMMENT '订单号',
  `status` tinyint(1) NOT NULL COMMENT '订单状态（1：待确定，2：配送中，3：已收货，4：拒收）',
  `shipping_time` char(11) DEFAULT NULL COMMENT '开始送货时间',
  `shipping_fee` decimal(10,2) NOT NULL COMMENT '送货费',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`order_id`),
  KEY `us` (`user_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_order`
--

LOCK TABLES `fruit_order` WRITE;
/*!40000 ALTER TABLE `fruit_order` DISABLE KEYS */;
INSERT INTO `fruit_order` VALUES (1,1,4,'14111410253561',2,'12:00-18:00',12.50,'nothing',1415960111,1416300350),(2,1,5,'14111752489899',4,'12:00-18:00',22.50,'下订单测试',1416193396,1416300829),(3,1,4,'14112049535657',1,NULL,42.50,NULL,1416473921,NULL);
/*!40000 ALTER TABLE `fruit_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_order_goods`
--

DROP TABLE IF EXISTS `fruit_order_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `amount` int(11) NOT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='商品订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_order_goods`
--

LOCK TABLES `fruit_order_goods` WRITE;
/*!40000 ALTER TABLE `fruit_order_goods` DISABLE KEYS */;
INSERT INTO `fruit_order_goods` VALUES (1,1,1,10),(2,2,1,8),(3,1,2,10),(4,1,3,10),(5,2,3,8);
/*!40000 ALTER TABLE `fruit_order_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_order_package`
--

DROP TABLE IF EXISTS `fruit_order_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_order_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `package_id` int(11) NOT NULL COMMENT '套餐ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `amount` int(11) NOT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='订单套餐表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_order_package`
--

LOCK TABLES `fruit_order_package` WRITE;
/*!40000 ALTER TABLE `fruit_order_package` DISABLE KEYS */;
INSERT INTO `fruit_order_package` VALUES (1,3,2,1),(2,20,3,1);
/*!40000 ALTER TABLE `fruit_order_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_package`
--

DROP TABLE IF EXISTS `fruit_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='套餐表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_package`
--

LOCK TABLES `fruit_package` WRITE;
/*!40000 ALTER TABLE `fruit_package` DISABLE KEYS */;
INSERT INTO `fruit_package` VALUES (19,'測試套餐1',12.00,34.00,'/uploads/2014/11/21/870de349e67ead4553db49dd7729d8598abced31.jpg','/uploads/2014/11/21/adcb868fae32b61a3479dc7ffd7b1c7c055bc76d.jpg','/uploads/2014/11/21/4171400fe53991515dae89ce9f39cbd61241c4f1.jpg','/uploads/2014/11/21/e765a800564ea255922319757e0e0e0d7028e271.jpg','/uploads/2014/11/21/bb7e7327487b7fd0691c7d157c7341c65bf83d4b.jpg','/uploads/2014/11/21/b328144233ee466bdea4206f120498732687c871.jpg','<p>這是套餐測試</p>',1416567703,NULL),(20,'測試套餐2',12.00,20.00,'/uploads/2014/11/21/9b370b6be335abd01c70ba6f5c69e061f214c913.jpg','/uploads/2014/11/21/3faeb831305b752a4660a6fc052dac4db1d1735d.jpg','/uploads/2014/11/21/f5967c6e4bb31f97035754123106ba0b7bad9e12.jpg','/uploads/2014/11/21/b80d84b413ecefff68b6803ecc6c2bc11130a09f.jpg','/uploads/2014/11/21/200711d5b532929bec1c03889289eebf31080560.jpg','/uploads/2014/11/21/c60415485dc372eb1957dc155bbbad334e36ab6b.jpg','<p>這是一個測試套餐</p>',1416567758,1416567773);
/*!40000 ALTER TABLE `fruit_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_package_goods`
--

DROP TABLE IF EXISTS `fruit_package_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_package_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `package_id` int(11) NOT NULL COMMENT '套餐ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `amount` int(11) NOT NULL COMMENT '商品数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='套餐商品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_package_goods`
--

LOCK TABLES `fruit_package_goods` WRITE;
/*!40000 ALTER TABLE `fruit_package_goods` DISABLE KEYS */;
INSERT INTO `fruit_package_goods` VALUES (7,19,1,1),(8,19,2,2),(11,20,3,2),(12,20,4,3),(13,20,1,10);
/*!40000 ALTER TABLE `fruit_package_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_parent_category`
--

DROP TABLE IF EXISTS `fruit_parent_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_parent_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(60) NOT NULL COMMENT '分类名称',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='父分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_parent_category`
--

LOCK TABLES `fruit_parent_category` WRITE;
/*!40000 ALTER TABLE `fruit_parent_category` DISABLE KEYS */;
INSERT INTO `fruit_parent_category` VALUES (1,'測試大分類1',1415615785,1415615808);
/*!40000 ALTER TABLE `fruit_parent_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_returns`
--

DROP TABLE IF EXISTS `fruit_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='退货表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_returns`
--

LOCK TABLES `fruit_returns` WRITE;
/*!40000 ALTER TABLE `fruit_returns` DISABLE KEYS */;
INSERT INTO `fruit_returns` VALUES (1,1,'14111752489899','有烂果','/uploads/2014/11/18/a7628e2eb56c480e1b5dd1aa7daaa47f3c17cbb0.png','/uploads/2014/11/18/5eadf5ad6542cf4e5e2278909d901b752281066a.png',NULL,'补充说明',1416279982);
/*!40000 ALTER TABLE `fruit_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_shipping_address`
--

DROP TABLE IF EXISTS `fruit_shipping_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_shipping_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL COMMENT '市',
  `district` varchar(255) DEFAULT NULL COMMENT '区',
  `road_number` varchar(255) DEFAULT NULL COMMENT '路牌号',
  `community` varchar(255) DEFAULT NULL COMMENT '小区（社区）、建筑名',
  `building` varchar(255) DEFAULT NULL COMMENT '栋、几期、座',
  `branch_id` int(11) DEFAULT NULL COMMENT '分店ID',
  `shipping_fee` decimal(10,2) NOT NULL COMMENT '送货费',
  `discount` decimal(4,2) DEFAULT NULL COMMENT '价格调整比例',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='配送地址表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_shipping_address`
--

LOCK TABLES `fruit_shipping_address` WRITE;
/*!40000 ALTER TABLE `fruit_shipping_address` DISABLE KEYS */;
INSERT INTO `fruit_shipping_address` VALUES (2,'廣州市','天河區','189號','羊城花園','康苑八座401',NULL,10.00,20.00,1415874727,1415874737);
/*!40000 ALTER TABLE `fruit_shipping_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_shopping_car`
--

DROP TABLE IF EXISTS `fruit_shopping_car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_shopping_car` (
  `shopping_car_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '购物车ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `package_id` int(11) DEFAULT NULL COMMENT '套餐ID',
  `quantity` int(11) NOT NULL COMMENT '数量',
  `add_time` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`shopping_car_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='购物车表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_shopping_car`
--

LOCK TABLES `fruit_shopping_car` WRITE;
/*!40000 ALTER TABLE `fruit_shopping_car` DISABLE KEYS */;
INSERT INTO `fruit_shopping_car` VALUES (1,1,1,NULL,10,1416751241),(4,1,NULL,19,2,1416751285);
/*!40000 ALTER TABLE `fruit_shopping_car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_tag`
--

DROP TABLE IF EXISTS `fruit_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(60) NOT NULL COMMENT '标签名称',
  `image` varchar(255) NOT NULL COMMENT '标签图片',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='标签表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_tag`
--

LOCK TABLES `fruit_tag` WRITE;
/*!40000 ALTER TABLE `fruit_tag` DISABLE KEYS */;
INSERT INTO `fruit_tag` VALUES (1,'測試標籤1','/uploads/2014/11/10/8f6548b8bdb6beae0a0271cebd2ec0f92b5793a4.jpg',1415615885,NULL);
/*!40000 ALTER TABLE `fruit_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fruit_version`
--

DROP TABLE IF EXISTS `fruit_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fruit_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(10) NOT NULL,
  `download_url` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '版本类型（0：android，1：ios）',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='App版本管理表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruit_version`
--

LOCK TABLES `fruit_version` WRITE;
/*!40000 ALTER TABLE `fruit_version` DISABLE KEYS */;
/*!40000 ALTER TABLE `fruit_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'fruit'
--

--
-- Dumping routines for database 'fruit'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-23 22:10:57
