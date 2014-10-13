/*
SQLyog Community v11.52 (32 bit)
MySQL - 5.5.38-0+wheezy1 : Database - cafe
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `auth_assignment` */

DROP TABLE IF EXISTS `auth_assignment`;

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `auth_assignment` */

insert  into `auth_assignment`(`item_name`,`user_id`,`created_at`) values ('admin','1',1413231535),('seller','5',1413231619);

/*Table structure for table `auth_item` */

DROP TABLE IF EXISTS `auth_item`;

CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `auth_item` */

insert  into `auth_item`(`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) values ('admin',1,NULL,NULL,NULL,1413229782,1413229782),('createOrder',2,'Create a order',NULL,NULL,1413229783,1413229783),('manageOrder',2,'Manage orders',NULL,NULL,1413229783,1413229783),('managePackaging',2,'Manage packaging',NULL,NULL,1413229783,1413229783),('managePos',2,'Manage points of sale',NULL,NULL,1413229783,1413229783),('manageProduct',2,'Manage products',NULL,NULL,1413229783,1413229783),('manageSeller',2,'Manage sellers',NULL,NULL,1413229783,1413229783),('manageSupply',2,'Manage supply',NULL,NULL,1413229783,1413229783),('manageSysuser',2,'Manage users',NULL,NULL,1413229783,1413229783),('seller',1,NULL,NULL,NULL,1413229782,1413229782),('viewPackaging',2,'View packaging',NULL,NULL,1413229783,1413229783);

/*Table structure for table `auth_item_child` */

DROP TABLE IF EXISTS `auth_item_child`;

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `auth_item_child` */

insert  into `auth_item_child`(`parent`,`child`) values ('admin','createOrder'),('seller','createOrder'),('admin','manageOrder'),('admin','managePackaging'),('admin','managePos'),('admin','manageProduct'),('admin','manageSeller'),('admin','manageSupply'),('admin','manageSysuser'),('admin','viewPackaging'),('seller','viewPackaging');

/*Table structure for table `auth_rule` */

DROP TABLE IF EXISTS `auth_rule`;

CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `auth_rule` */

/*Table structure for table `order` */

DROP TABLE IF EXISTS `order`;

CREATE TABLE `order` (
  `order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pos_id` bigint(20) NOT NULL,
  `seller_id` bigint(20) NOT NULL,
  `sysuser_id` bigint(20) NOT NULL,
  `order_datetime` datetime DEFAULT NULL,
  `order_day_sequence_number` int(11) DEFAULT NULL,
  `order_total` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `order_payment_type` varchar(32) DEFAULT NULL,
  `order_hash` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `pos` (`pos_id`),
  KEY `seller` (`seller_id`),
  KEY `sysuser` (`sysuser_id`),
  KEY `odt` (`order_datetime`),
  CONSTRAINT `pos6` FOREIGN KEY (`pos_id`) REFERENCES `pos` (`pos_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `seller6` FOREIGN KEY (`seller_id`) REFERENCES `seller` (`seller_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sysuser6` FOREIGN KEY (`sysuser_id`) REFERENCES `sysuser` (`sysuser_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `order` */

/*Table structure for table `order_packaging` */

DROP TABLE IF EXISTS `order_packaging`;

CREATE TABLE `order_packaging` (
  `order_id` bigint(20) NOT NULL,
  `packaging_id` bigint(20) NOT NULL,
  `packaging_title` varchar(32) DEFAULT NULL,
  `packaging_price` double DEFAULT NULL,
  `order_packaging_number` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_id`,`packaging_id`),
  KEY `order1` (`order_id`),
  KEY `packaging1` (`packaging_id`),
  CONSTRAINT `order9` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  CONSTRAINT `pack9` FOREIGN KEY (`packaging_id`) REFERENCES `packaging` (`packaging_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `order_packaging` */

/*Table structure for table `packaging` */

DROP TABLE IF EXISTS `packaging`;

CREATE TABLE `packaging` (
  `packaging_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `packaging_icon` varchar(1024) DEFAULT NULL,
  `packaging_title` varchar(32) DEFAULT NULL,
  `packaging_price` double DEFAULT NULL,
  PRIMARY KEY (`packaging_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `packaging` */

/*Table structure for table `packaging_product` */

DROP TABLE IF EXISTS `packaging_product`;

CREATE TABLE `packaging_product` (
  `packaging_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `packaging_product_quantity` double DEFAULT NULL,
  PRIMARY KEY (`packaging_id`,`product_id`),
  KEY `packaging2` (`packaging_id`),
  KEY `product2` (`product_id`),
  CONSTRAINT `pack10` FOREIGN KEY (`packaging_id`) REFERENCES `packaging` (`packaging_id`),
  CONSTRAINT `prod10` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `packaging_product` */

/*Table structure for table `pos` */

DROP TABLE IF EXISTS `pos`;

CREATE TABLE `pos` (
  `pos_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pos_title` varchar(1024) DEFAULT NULL,
  `pos_address` varchar(1024) DEFAULT NULL,
  `pos_timetable` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pos` */

/*Table structure for table `pos_product` */

DROP TABLE IF EXISTS `pos_product`;

CREATE TABLE `pos_product` (
  `pos_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `pos_product_quantity` double NOT NULL,
  `pos_product_min_quantity` double NOT NULL,
  PRIMARY KEY (`pos_id`,`product_id`),
  KEY `pos4` (`pos_id`),
  KEY `product4` (`product_id`),
  CONSTRAINT `pos11` FOREIGN KEY (`pos_id`) REFERENCES `pos` (`pos_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prod11` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pos_product` */

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `product_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_title` varchar(1024) DEFAULT NULL,
  `product_icon` varchar(1024) DEFAULT NULL,
  `product_quantity` double DEFAULT NULL,
  `product_unit` varchar(32) DEFAULT NULL,
  `product_min_quantity` double DEFAULT NULL,
  `product_unit_price` double DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `product` */

/*Table structure for table `seller` */

DROP TABLE IF EXISTS `seller`;

CREATE TABLE `seller` (
  `seller_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sysuser_id` bigint(20) DEFAULT NULL,
  `pos_id` bigint(20) DEFAULT NULL,
  `seller_salary` double DEFAULT NULL,
  `seller_commission_fee` double DEFAULT NULL,
  PRIMARY KEY (`seller_id`),
  KEY `sysuser5` (`sysuser_id`),
  KEY `pos12` (`pos_id`),
  CONSTRAINT `pos12` FOREIGN KEY (`pos_id`) REFERENCES `pos` (`pos_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `sysuser5` FOREIGN KEY (`sysuser_id`) REFERENCES `sysuser` (`sysuser_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `seller` */

/*Table structure for table `supply` */

DROP TABLE IF EXISTS `supply`;

CREATE TABLE `supply` (
  `pos_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `supply_quantity` double DEFAULT NULL,
  PRIMARY KEY (`pos_id`,`product_id`),
  KEY `pos3` (`pos_id`),
  KEY `product3` (`product_id`),
  CONSTRAINT `pos13` FOREIGN KEY (`pos_id`) REFERENCES `pos` (`pos_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prod13` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `supply` */

/*Table structure for table `sysuser` */

DROP TABLE IF EXISTS `sysuser`;

CREATE TABLE `sysuser` (
  `sysuser_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sysuser_fullname` varchar(512) DEFAULT NULL,
  `sysuser_login` varchar(64) DEFAULT NULL,
  `sysuser_password` varchar(128) DEFAULT NULL,
  `sysuser_role` varchar(32) DEFAULT NULL,
  `sysuser_telephone` varchar(64) DEFAULT NULL,
  `sysuser_token` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`sysuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `sysuser` */

insert  into `sysuser`(`sysuser_id`,`sysuser_fullname`,`sysuser_login`,`sysuser_password`,`sysuser_role`,`sysuser_telephone`,`sysuser_token`) values (1,'admin','admin','admin','admin','','1'),(5,'test2 test2','test2','TlSKN8E5rPR9Q','seller','test234','96FCb7KBgN5PO56g7BISYvAHvG696ab9');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
