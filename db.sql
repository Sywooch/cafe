/*
SQLyog Community v12.02 (32 bit)
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

insert  into `auth_assignment`(`item_name`,`user_id`,`created_at`) values ('admin','1',1413231535),('seller','5',1415575921),('seller','6',1416868900);

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

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `category_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_title` varchar(64) DEFAULT NULL,
  `category_skin` varchar(64) DEFAULT NULL,
  `category_ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `category` */

insert  into `category`(`category_id`,`category_title`,`category_skin`,`category_ordering`) values (1,'Чай','tea',10),(2,'Кофе','coffee',0),(3,'Вкусняшки','food',20);

/*Table structure for table `discount` */

DROP TABLE IF EXISTS `discount`;

CREATE TABLE `discount` (
  `discount_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `discount_title` varchar(32) DEFAULT NULL,
  `discount_description` text,
  `discount_rule` text,
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `discount` */

insert  into `discount`(`discount_id`,`discount_title`,`discount_description`,`discount_rule`) values (1,'Студентам 500 мл за 100 руб','','');

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
  `discount_id` bigint(20) unsigned DEFAULT NULL,
  `discount_title` varchar(32) DEFAULT NULL,
  `order_seller_comission` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`),
  KEY `pos` (`pos_id`),
  KEY `seller` (`seller_id`),
  KEY `sysuser` (`sysuser_id`),
  KEY `odt` (`order_datetime`),
  KEY `dscnt` (`discount_id`),
  CONSTRAINT `dscnt` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`discount_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pos6` FOREIGN KEY (`pos_id`) REFERENCES `pos` (`pos_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `seller6` FOREIGN KEY (`seller_id`) REFERENCES `seller` (`seller_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sysuser6` FOREIGN KEY (`sysuser_id`) REFERENCES `sysuser` (`sysuser_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

/*Data for the table `order` */

insert  into `order`(`order_id`,`pos_id`,`seller_id`,`sysuser_id`,`order_datetime`,`order_day_sequence_number`,`order_total`,`order_discount`,`order_payment_type`,`order_hash`,`discount_id`,`discount_title`,`order_seller_comission`) values (1,1,1,5,'2014-11-01 22:20:45',1,1234,10,'cash','1234556789',NULL,NULL,0),(2,1,2,1,'2014-11-02 20:35:20',1,15,NULL,'cach','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(3,1,2,1,'2014-11-02 20:36:12',2,15,NULL,'cach','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(4,1,2,1,'2014-11-02 21:28:28',3,15,NULL,'cach','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(5,1,2,1,'2014-11-02 21:38:34',4,15,NULL,'cach','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(6,1,2,1,'2014-11-05 20:37:01',1,81,NULL,'cach','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(7,1,2,1,'2014-11-05 20:39:32',2,32,NULL,'card','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(8,1,2,1,'2014-11-13 20:28:02',1,81,NULL,'card','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(9,1,2,1,'2014-11-13 22:37:57',2,20,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(10,1,2,1,'2014-11-13 22:38:33',3,65,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(11,1,2,1,'2014-11-13 22:40:16',4,81,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(12,1,2,1,'2014-11-13 22:41:23',5,36,NULL,'card','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(13,1,2,1,'2014-11-16 22:17:31',1,36,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(14,1,2,1,'2014-11-16 22:18:06',2,20,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(15,1,2,1,'2014-11-16 22:21:10',3,20,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(16,1,2,1,'2014-11-16 22:23:58',4,65,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(17,1,2,1,'2014-11-16 22:29:54',5,36,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(18,1,2,1,'2014-11-16 22:30:20',6,32,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(19,1,2,1,'2014-11-16 22:32:07',7,60,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(20,1,2,1,'2014-11-16 22:33:09',8,48,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(21,1,2,1,'2014-11-16 22:40:13',9,52,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(22,1,2,1,'2014-11-16 22:48:56',10,48,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(23,1,2,1,'2014-11-16 22:51:52',11,68,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(25,1,2,1,'2014-11-16 22:56:41',13,60,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(26,1,2,1,'2014-11-16 22:57:20',14,48,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(27,1,2,1,'2014-11-16 23:00:45',15,48,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(28,1,2,1,'2014-11-16 23:03:48',16,64,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(29,1,2,1,'2014-11-16 23:08:22',17,48,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(30,1,2,1,'2014-11-16 23:13:01',18,48,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(31,1,2,1,'2014-11-16 23:15:20',19,117,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(32,1,2,1,'2014-11-16 23:19:10',20,56,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(33,1,2,1,'2014-11-16 23:21:30',21,108,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(34,1,2,1,'2014-11-16 23:21:48',22,268,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(35,1,2,1,'2014-11-16 23:22:32',23,90,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(36,1,2,1,'2014-11-16 23:23:37',24,97,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(37,1,2,1,'2014-11-16 23:26:03',25,52,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(38,1,2,1,'2014-11-16 23:26:55',26,76,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(39,1,2,1,'2014-11-16 23:27:25',27,133,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(40,1,2,1,'2014-11-16 23:29:27',28,60,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(41,1,2,1,'2014-11-16 23:30:05',29,60,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(42,1,2,1,'2014-11-16 23:31:08',30,126,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0),(43,1,2,1,'2014-11-23 19:59:16',1,81,NULL,'card','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,4.05),(44,1,2,1,'2014-11-23 20:13:29',2,125,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,6.25),(45,1,2,1,'2014-11-23 20:14:29',3,80,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,4),(46,1,2,1,'2014-11-24 23:18:53',1,17,NULL,'cash','356a192b7913b04c54574d18c28d46e6395428ab',NULL,NULL,0.85);

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
  CONSTRAINT `order9` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pack9` FOREIGN KEY (`packaging_id`) REFERENCES `packaging` (`packaging_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `order_packaging` */

insert  into `order_packaging`(`order_id`,`packaging_id`,`packaging_title`,`packaging_price`,`order_packaging_number`) values (1,1,'Кофе со сливками',15,2),(2,1,'Кофе со сливками, 200 г',15,1),(3,1,'Кофе со сливками, 200 г',15,1),(4,1,'Кофе со сливками, 200 г',15,1),(5,1,'Кофе со сливками, 200 г',15,1),(6,2,'Кофе, 200 г',16,1),(6,3,'Кофе, 100 г',20,1),(6,6,'Кофе, 500 мл',45,1),(7,4,'Сахар',3,4),(7,5,'Сливки, 5 г.',4,5),(8,2,'Кофе, 200 г',16,1),(8,3,'Кофе, 100 г',20,1),(8,6,'Кофе, 500 мл',45,1),(9,3,'Кофе, 100 г',20,1),(10,3,'Кофе, 100 г',20,1),(10,6,'Кофе, 500 мл',45,1),(11,2,'Кофе, 200 г',16,1),(11,3,'Кофе, 100 г',20,1),(11,6,'Кофе, 500 мл',45,1),(12,2,'Кофе, 200 г',16,1),(12,3,'Кофе, 100 г',20,1),(13,2,'Кофе, 200 г',16,1),(13,3,'Кофе, 100 г',20,1),(14,3,'Кофе, 100 г',20,1),(15,3,'Кофе, 100 г',20,1),(16,3,'Кофе, 100 г',20,1),(16,6,'Кофе, 500 мл',45,1),(17,2,'Кофе, 200 г',16,1),(17,3,'Кофе, 100 г',20,1),(18,2,'Кофе, 200 г',16,2),(19,3,'Кофе, 100 г',20,3),(20,2,'Кофе, 200 г',16,3),(21,2,'Кофе, 200 г',16,2),(21,3,'Кофе, 100 г',20,1),(22,2,'Кофе, 200 г',16,3),(23,2,'Кофе, 200 г',16,3),(23,3,'Кофе, 100 г',20,1),(25,3,'Кофе, 100 г',20,3),(26,2,'Кофе, 200 г',16,3),(27,2,'Кофе, 200 г',16,3),(28,2,'Кофе, 200 г',16,4),(29,2,'Кофе, 200 г',16,3),(30,2,'Кофе, 200 г',16,3),(31,2,'Кофе, 200 г',16,2),(31,3,'Кофе, 100 г',20,2),(31,6,'Кофе, 500 мл',45,1),(32,2,'Кофе, 200 г',16,1),(32,3,'Кофе, 100 г',20,2),(33,2,'Кофе, 200 г',16,3),(33,3,'Кофе, 100 г',20,3),(34,1,'Кофе со сливками, 200 г',15,3),(34,2,'Кофе, 200 г',16,3),(34,3,'Кофе, 100 г',20,2),(34,6,'Кофе, 500 мл',45,3),(35,1,'Кофе со сливками, 200 г',15,6),(36,1,'Кофе со сливками, 200 г',15,3),(36,2,'Кофе, 200 г',16,2),(36,3,'Кофе, 100 г',20,1),(37,2,'Кофе, 200 г',16,2),(37,3,'Кофе, 100 г',20,1),(38,2,'Кофе, 200 г',16,1),(38,3,'Кофе, 100 г',20,3),(39,1,'Кофе со сливками, 200 г',15,3),(39,2,'Кофе, 200 г',16,3),(39,3,'Кофе, 100 г',20,2),(40,3,'Кофе, 100 г',20,3),(41,1,'Кофе со сливками, 200 г',15,4),(42,1,'Кофе со сливками, 200 г',15,3),(42,2,'Кофе, 200 г',16,1),(42,3,'Кофе, 100 г',20,1),(42,6,'Кофе, 500 мл',45,1),(43,2,'Кофе, 200 г',16,1),(43,3,'Кофе, 100 г',20,1),(43,6,'Кофе, 500 мл',45,1),(44,3,'Кофе, 100 г',20,4),(44,6,'Кофе, 500 мл',45,1),(45,2,'Кофе, 200 г',16,5),(46,1,'Кофе со сливками, 200 г',17,1);

/*Table structure for table `packaging` */

DROP TABLE IF EXISTS `packaging`;

CREATE TABLE `packaging` (
  `packaging_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `packaging_icon` varchar(1024) DEFAULT NULL,
  `packaging_title` varchar(32) DEFAULT NULL,
  `packaging_price` double DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `packaging_is_additional` tinyint(1) NOT NULL DEFAULT '0',
  `packaging_is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `packaging_ordering` int(11) DEFAULT '1000',
  PRIMARY KEY (`packaging_id`),
  KEY `catg` (`category_id`),
  CONSTRAINT `catg` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `packaging` */

insert  into `packaging`(`packaging_id`,`packaging_icon`,`packaging_title`,`packaging_price`,`category_id`,`packaging_is_additional`,`packaging_is_visible`,`packaging_ordering`) values (1,'packaging1.jpg','Кофе со сливками, 200 г',15,1,0,1,10),(2,'packaging2.jpg','Кофе, 200 г',16,2,0,1,20),(3,'packaging3.jpg','Кофе, 100 г',20,2,0,1,30),(4,'packaging4.jpg','Сахар',3,3,1,1,40),(5,'packaging5.jpg','Сливки, 5 г.',4,3,1,1,50),(6,NULL,'Кофе, 500 мл',45,2,0,1,60),(7,'packaging7.jpg','Шоколадка',12,3,0,1,70);

/*Table structure for table `packaging_product` */

DROP TABLE IF EXISTS `packaging_product`;

CREATE TABLE `packaging_product` (
  `packaging_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `packaging_product_quantity` double DEFAULT NULL,
  PRIMARY KEY (`packaging_id`,`product_id`),
  KEY `packaging2` (`packaging_id`),
  KEY `product2` (`product_id`),
  CONSTRAINT `pack10` FOREIGN KEY (`packaging_id`) REFERENCES `packaging` (`packaging_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prod10` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `packaging_product` */

insert  into `packaging_product`(`packaging_id`,`product_id`,`packaging_product_quantity`) values (1,1,0.003),(1,2,0.005),(1,3,0.2),(2,1,0.005),(2,3,0.2),(3,1,0.005),(3,3,0.1),(4,4,0.005),(5,2,0.005),(6,1,0.021),(6,2,0.015),(6,3,0.5),(6,4,0.015),(7,1,0.01);

/*Table structure for table `pos` */

DROP TABLE IF EXISTS `pos`;

CREATE TABLE `pos` (
  `pos_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pos_title` varchar(1024) DEFAULT NULL,
  `pos_address` varchar(1024) DEFAULT NULL,
  `pos_timetable` varchar(1024) DEFAULT NULL,
  `pos_printer_url` varchar(1024) DEFAULT NULL,
  `pos_printer_template` text,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `pos` */

insert  into `pos`(`pos_id`,`pos_title`,`pos_address`,`pos_timetable`,`pos_printer_url`,`pos_printer_template`) values (1,'Точка А','Адрес точки А','Какой-то график','http://localhost:9000/','[max_str_size]40[/max_str_size]\r\n[center]Coffee Time[/center]\r\n[center]Чек №[order_day_sequence_number/][/center]\r\n[center]Кассир [sysuser_lastname/][/center]\r\n[foreach packaging][justify][packaging_title/][/].[/][order_packaging_number/] x [packaging_price/]руб[/justify][/foreach]\r\n[justify][/]=[/][/justify]\r\n[justify]Всего[/].[/][order_total/]руб[/justify]\r\n[justify]ККМ 00000000 ИНН 0000000000[/].[/]№[order_day_sequence_number/][/justify]\r\n[justify][order_datetime/][/] [/][sysuser_lastname/][/justify]\r\n[justify][order_payment_type/][/] [/][order_total/]руб[/justify]\r\n[justify]Итог:[/] [/][order_total/]руб[/justify]\r\n\r\n');

/*Table structure for table `pos_packaging` */

DROP TABLE IF EXISTS `pos_packaging`;

CREATE TABLE `pos_packaging` (
  `pos_id` bigint(20) NOT NULL,
  `packaging_id` bigint(20) NOT NULL,
  `pos_packaging_price` double DEFAULT NULL,
  PRIMARY KEY (`pos_id`,`packaging_id`),
  KEY `pack100` (`packaging_id`),
  CONSTRAINT `pack100` FOREIGN KEY (`packaging_id`) REFERENCES `packaging` (`packaging_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pos100` FOREIGN KEY (`pos_id`) REFERENCES `pos` (`pos_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `pos_packaging` */

insert  into `pos_packaging`(`pos_id`,`packaging_id`,`pos_packaging_price`) values (1,1,17);

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

insert  into `pos_product`(`pos_id`,`product_id`,`pos_product_quantity`,`pos_product_min_quantity`) values (1,1,2.098,3),(1,2,9.685,11),(1,3,-19,0),(1,4,2.8,0);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `product` */

insert  into `product`(`product_id`,`product_title`,`product_icon`,`product_quantity`,`product_unit`,`product_min_quantity`,`product_unit_price`) values (1,'Кофе молотый, Арабика','1.jpg',14,'кг',12,1000),(2,'Сливки',NULL,21,'кг',20,35),(3,'Вода','product3.jpg',50,'л',20,1),(4,'Сахар','4.jpg',13,'кг',10,57);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `seller` */

insert  into `seller`(`seller_id`,`sysuser_id`,`pos_id`,`seller_salary`,`seller_commission_fee`) values (1,5,1,1000,5),(2,1,1,1,5);

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
  `sysuser_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sysuser_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `sysuser` */

insert  into `sysuser`(`sysuser_id`,`sysuser_fullname`,`sysuser_login`,`sysuser_password`,`sysuser_role`,`sysuser_telephone`,`sysuser_token`,`sysuser_active`) values (1,'admin','admin','TlK/nt2ZJff/k','admin','123','K9P9GKDD_-tLPPfXzwmFEjbziMUm5wLP',1),(5,'test2 test2','test2','TlSKN8E5rPR9Q','seller','test234','96FCb7KBgN5PO56g7BISYvAHvG696ab9',1),(6,'test','test','TlEX9mHYwwVF2','seller','123','0SqhM6E9e9toMR8rZyy6EIhcWCbBFGE4',1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
