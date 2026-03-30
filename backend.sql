-- SQL to update the databases

-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: mysql502.discountasp.net    Database: mysql5_184896_travel3rd
-- ------------------------------------------------------
-- Server version	5.6.34

DROP DATABASE IF EXISTS `final_project`;
CREATE DATABASE IF NOT EXISTS `final_project`;
USE `final_project`;


--
-- Table structure for table `continents`
--

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'john.doe@willamette.edu','$2y$10$92IXUNpkj0fDZcXHJPvcIZwiDhlJnKzQW07gY8g669M7C344V4lqG'),(2,'jane.smith@willamette.edu','$2y$10$92IXUNpkj0fDZcXHJPvcIZwiDhlJnKzQW07gY8g669M7C344V4lqG');
UNLOCK TABLES;

