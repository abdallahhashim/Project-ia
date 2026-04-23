-- Booked Beyond (PHP + MySQL) schema
-- Clean import file for the new project

CREATE DATABASE IF NOT EXISTS `booked_beyond` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `booked_beyond`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `books`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(120) NOT NULL,
  `Password` varchar(120) NOT NULL,
  `Email` varchar(190) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(120) NOT NULL,
  `User_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `categories_user_fk` FOREIGN KEY (`User_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `books` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Author` varchar(150) NOT NULL,
  `Category_ID` int(11) NOT NULL,
  `Publish_Year` varchar(20) DEFAULT NULL,
  `Status` enum('Available','Borrowed') NOT NULL DEFAULT 'Available',
  `Description` text DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `User_ID` (`User_ID`),
  KEY `Category_ID` (`Category_ID`),
  CONSTRAINT `books_user_fk` FOREIGN KEY (`User_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `books_category_fk` FOREIGN KEY (`Category_ID`) REFERENCES `categories` (`ID`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`ID`, `Name`, `Password`, `Email`) VALUES
(1, 'demo', '123456', 'demo@booked.local'),
(2, 'abdallah', '123456', 'abdallah@hashem.com');

INSERT INTO `categories` (`ID`, `Name`, `User_ID`) VALUES
(1, 'Programming', 1),
(2, 'Self Improvement', 1),
(3, 'Business', 1);

INSERT INTO `books` (`ID`, `User_ID`, `Title`, `Author`, `Category_ID`, `Publish_Year`, `Status`, `Description`, `Created_At`) VALUES
(1, 1, 'Clean Code', 'Robert C. Martin', 1, '2008', 'Borrowed', 'Guidelines for writing readable and maintainable code.', NOW()),
(2, 1, 'Atomic Habits', 'James Clear', 2, '2018', 'Available', 'Small habits and practical systems for long-term growth.', NOW());

COMMIT;
