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

-- DROP TABLE IF EXISTS `students`;

-- CREATE TABLE `students` (
--     `id` int(11) NOT NULL AUTO_INCREMENT,
--     `email` varchar(255) NOT NULL,
--     `password` varchar(255) NOT NULL,
--     `admin` boolean NOT NULL DEFAULT 0,
--     PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- LOCK TABLES `students` WRITE;
-- INSERT INTO `students` VALUES (1,'john.doe@willamette.edu','$2y$10$DikEmuYHZMt5rm/Je70rtuxZMeUsNINyDQ0dOXW1DHswO8E4Fud2S'),(2,'jane.smith@willamette.edu','$2y$10$92IXUNpkj0fDZcXHJPvcIZwiDhlJnKzQW07gY8g669M7C344V4lqG');
-- UNLOCK TABLES;

-- CREATE TABLE `staff` (
--     `id` int(11) NOT NULL AUTO_INCREMENT,
--     `email` varchar(255) NOT NULL,
--     `password` varchar(255) NOT NULL,
--     `admin` boolean NOT NULL DEFAULT 1,
--     PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- LOCK TABLES `staff` WRITE;
-- INSERT INTO `staff` VALUES (1,'admin@willamette.edu','$2y$10$DikEmuYHZMt5rm/Je70rtuxZMeUsNINyDQ0dOXW1DHswO8E4Fud2S');
-- UNLOCK TABLES;


CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `admin` boolean NOT NULL,
    `first` varchar(255) NOT NULL,
    `last` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'admin@willamette.edu','$2y$10$DikEmuYHZMt5rm/Je70rtuxZMeUsNINyDQ0dOXW1DHswO8E4Fud2S',1,'Admin','User'),(2,'john.doe@willamette.edu','$2y$10$DikEmuYHZMt5rm/Je70rtuxZMeUsNINyDQ0dOXW1DHswO8E4Fud2S',0,'John','Doe');
UNLOCK TABLES;

CREATE TABLE `appointments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `staff_id` int(11) NOT NULL,
    `student_id` int(11) NOT NULL,
    'slot_id' int(11) NOT NULL,
    `booked` datetime NOT NULL,
    `description` text,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`staff_id`) REFERENCES `users`(`email`),
    FOREIGN KEY (`student_id`) REFERENCES `users`(`email`),
    FOREIGN KEY (`slot_id`) REFERENCES `slots`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `appointments` WRITE;
INSERT INTO `appointments` VALUES (1,1,2,'2025-30-03','10:00:00','Initial advising appointment');
UNLOCK TABLES;

CREATE TABLE 'slots' (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `staff_id` int(11) NOT NULL,
    `date` date NOT NULL,
    `time` time NOT NULL,
    `is_booked` boolean NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`staff_id`) REFERENCES `users`(`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `slots` WRITE;
INSERT INTO `slots` VALUES (1,1,'2025-30-03','10:00:00');
UNLOCK TABLES;
