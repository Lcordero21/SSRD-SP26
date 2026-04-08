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


CREATE TABLE `users` (
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `admin` boolean NOT NULL,
    `first` varchar(255) NOT NULL,
    `last` varchar(255) NOT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES ('admin@willamette.edu','$2y$10$DikEmuYHZMt5rm/Je70rtuxZMeUsNINyDQ0dOXW1DHswO8E4Fud2S',1,'Jane','Does'),('john.doe@willamette.edu','$2y$10$DikEmuYHZMt5rm/Je70rtuxZMeUsNINyDQ0dOXW1DHswO8E4Fud2S',0,'John','Doe');
UNLOCK TABLES;

CREATE TABLE `slots` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `staff_id` varchar(255) NOT NULL,
    `slot_date` date NOT NULL,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    `is_booked` boolean NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`staff_id`) REFERENCES `users`(`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `slots` WRITE;
INSERT INTO `slots` VALUES (1,'admin@willamette.edu','2025-03-30','10:00:00','11:00:00',0),(2,'admin@willamette.edu','2025-03-30','11:00:00','12:00:00',0);
UNLOCK TABLES;

CREATE TABLE `appointments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `staff_id` varchar(255) NOT NULL,
    `student_id` varchar(255) NOT NULL,
    `slot_id` int(11) NOT NULL,
    `booked` datetime NOT NULL,
    `description` text,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`staff_id`) REFERENCES `users`(`email`),
    FOREIGN KEY (`student_id`) REFERENCES `users`(`email`),
    FOREIGN KEY (`slot_id`) REFERENCES `slots`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `appointments` WRITE;
INSERT INTO `appointments` VALUES (1,'admin@willamette.edu','john.doe@willamette.edu',1,'2025-03-30 07:00:00','Initial advising appointment'),(2,'admin@willamette.edu','john.doe@willamette.edu',2,'2025-03-30 08:00:00','Follow-up appointment');
UNLOCK TABLES;


