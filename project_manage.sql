/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 5.7.25-log : Database - project_manage
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`project_manage` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `project_manage`;

/*Table structure for table `project` */

DROP TABLE IF EXISTS `project`;

CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `remark` text COMMENT '内容',
  `person` varchar(30) DEFAULT NULL COMMENT '负责人',
  `start_time` datetime DEFAULT NULL COMMENT '预计开始时间',
  `end_time` datetime DEFAULT NULL COMMENT '预计结束时间',
  `progress` varchar(30) DEFAULT NULL COMMENT '进度',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `level` varchar(2) DEFAULT NULL COMMENT '优先级(0/一般,1/重要,2/加急)',
  `is_del` int(2) DEFAULT '0' COMMENT '删除状态0/1（1为删除）',
  `city` varchar(50) DEFAULT NULL COMMENT '城市',
  `question_person` varchar(30) DEFAULT NULL COMMENT '问题提出人',
  `question_tel` varchar(30) DEFAULT NULL COMMENT '提出人电话',
  `c_dt` datetime DEFAULT NULL COMMENT '创建时间',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='项目';

/*Data for the table `project` */

insert  into `project`(`id`,`title`,`remark`,`person`,`start_time`,`end_time`,`progress`,`user_id`,`level`,`is_del`,`city`,`question_person`,`question_tel`,`c_dt`,`department`) values 
(7,'项目1','项目1项目1项目1项目1项目1项目1','邵兵','2020-08-04 14:00:00','2020-08-04 18:50:00','1',1,'1',0,NULL,NULL,NULL,'2020-08-04 18:50:00',NULL),
(8,'项目1项目1项目1项目1项目1项目1项目1项目1项目1项目1项目1项目1项目1项目1项目1项目1项目','','','2020-08-04 05:00:00','2020-08-06 06:00:00','0',1,'0',0,NULL,NULL,NULL,'2020-08-04 18:50:00',NULL),
(9,'项目1','','',NULL,NULL,'5',1,'0',0,NULL,NULL,NULL,'2020-08-04 18:50:00',NULL),
(10,'项目2','加盟商推广的物料上小程序和公众号都不能使用，让加盟商十分气氛，说白花钱了做推广了！\n 加急、加急、再加急…','邵兵','2020-08-06 09:00:00',NULL,'0',1,'0',0,NULL,NULL,NULL,'2020-08-04 18:50:00',NULL),
(11,'项目3','','',NULL,'2020-08-05 10:37:47','100',1,'0',0,NULL,NULL,NULL,'2020-08-04 18:50:00',NULL),
(12,'项目4','','邵兵',NULL,'2020-08-05 10:44:01','30',1,'0',0,NULL,NULL,NULL,'2020-08-04 18:50:00',NULL),
(13,'VIP客户新旧系统扣款能同步','123123','',NULL,NULL,'20',1,'0',0,'1232','3123','123','2020-08-08 10:23:05','研发部'),
(14,'企划方案','企划方案企划方案企划方案企划方案企划方案企划方案企划方案','邵兵',NULL,NULL,'100',1,'1',0,'石家庄','张三','13019215317','2020-08-08 10:30:32','企划部'),
(15,'市场营销','市场营销市场营销市场营销市场营销市场营销市场营销','邵兵',NULL,NULL,'0',1,'0',0,'石家庄','张三','123','2020-08-08 10:32:03','销售部');

/*Table structure for table `record` */

DROP TABLE IF EXISTS `record`;

CREATE TABLE `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL COMMENT '项目id',
  `remark` text COMMENT '备注',
  `c_time` datetime DEFAULT NULL COMMENT '创建时间',
  `person` varchar(30) DEFAULT NULL COMMENT '负责人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='项目-记录';

/*Data for the table `record` */

insert  into `record`(`id`,`project_id`,`remark`,`c_time`,`person`) values 
(1,11,'fef','2020-08-05 10:37:45','');

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `role` */

insert  into `role`(`id`,`name`) values 
(1,'超级管理员'),
(2,'开发人'),
(3,'提出人'),
(4,'查看人');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL COMMENT '账号',
  `pwd` varchar(50) NOT NULL COMMENT '密码',
  `operation` varchar(30) NOT NULL COMMENT '权限',
  `name` varchar(30) DEFAULT NULL COMMENT '姓名',
  `role_id` int(11) NOT NULL COMMENT '权限id',
  `c_dt` datetime DEFAULT NULL COMMENT '创建时间',
  `department` varchar(30) DEFAULT NULL COMMENT '部门',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户';

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`pwd`,`operation`,`name`,`role_id`,`c_dt`,`department`) values 
(1,'admin','670b14728ad9902aecba32e22fa4f6bd','超级管理员','管理员',1,'2020-08-04 14:00:00',NULL),
(2,'chakan','670b14728ad9902aecba32e22fa4f6bd','查看人','查看人',4,'2020-08-04 14:00:00',NULL),
(3,'13000000000','670b14728ad9902aecba32e22fa4f6bd','开发人','邵兵',2,'2020-08-08 09:16:11',NULL),
(4,'tichu','670b14728ad9902aecba32e22fa4f6bd','提出人','提出人',3,'2020-08-08 09:50:15',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
