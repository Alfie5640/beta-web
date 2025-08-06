-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 06, 2025 at 09:36 PM
-- Server version: 11.8.1-MariaDB-4
-- PHP Version: 8.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beta_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `Calendar`
--

CREATE TABLE `Calendar` (
  `calendarId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CalendarEntry`
--

CREATE TABLE `CalendarEntry` (
  `entryId` int(11) NOT NULL,
  `calendarId` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ClimberProfile`
--

CREATE TABLE `ClimberProfile` (
  `userId` int(11) NOT NULL,
  `goals` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE `Comments` (
  `commentId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `commentText` varchar(250) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `isAnalysis` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `EndUser`
--

CREATE TABLE `EndUser` (
  `userId` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` enum('climber','instructor') NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Favourites`
--

CREATE TABLE `Favourites` (
  `userId` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `InstructorProfile`
--

CREATE TABLE `InstructorProfile` (
  `userId` int(11) NOT NULL,
  `availability` enum('available','unavailable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Labels`
--

CREATE TABLE `Labels` (
  `labelId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `videoId` int(11) NOT NULL,
  `label_text` varchar(40) NOT NULL,
  `label_type` varchar(30) NOT NULL,
  `label_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Videos`
--

CREATE TABLE `Videos` (
  `videoId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL,
  `file_path` varchar(300) NOT NULL,
  `privacy` enum('public','private') DEFAULT NULL,
  `upload_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Calendar`
--
ALTER TABLE `Calendar`
  ADD PRIMARY KEY (`calendarId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `CalendarEntry`
--
ALTER TABLE `CalendarEntry`
  ADD PRIMARY KEY (`entryId`),
  ADD KEY `calendarId` (`calendarId`);

--
-- Indexes for table `ClimberProfile`
--
ALTER TABLE `ClimberProfile`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `Comments`
--
ALTER TABLE `Comments`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `videoId` (`videoId`);

--
-- Indexes for table `EndUser`
--
ALTER TABLE `EndUser`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `Favourites`
--
ALTER TABLE `Favourites`
  ADD PRIMARY KEY (`userId`,`videoId`),
  ADD KEY `videoId` (`videoId`);

--
-- Indexes for table `InstructorProfile`
--
ALTER TABLE `InstructorProfile`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `Labels`
--
ALTER TABLE `Labels`
  ADD PRIMARY KEY (`labelId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `videoId` (`videoId`);

--
-- Indexes for table `Videos`
--
ALTER TABLE `Videos`
  ADD PRIMARY KEY (`videoId`),
  ADD KEY `userId` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Calendar`
--
ALTER TABLE `Calendar`
  MODIFY `calendarId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `CalendarEntry`
--
ALTER TABLE `CalendarEntry`
  MODIFY `entryId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Comments`
--
ALTER TABLE `Comments`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `EndUser`
--
ALTER TABLE `EndUser`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Labels`
--
ALTER TABLE `Labels`
  MODIFY `labelId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Videos`
--
ALTER TABLE `Videos`
  MODIFY `videoId` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Calendar`
--
ALTER TABLE `Calendar`
  ADD CONSTRAINT `Calendar_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `EndUser` (`userId`);

--
-- Constraints for table `CalendarEntry`
--
ALTER TABLE `CalendarEntry`
  ADD CONSTRAINT `CalendarEntry_ibfk_1` FOREIGN KEY (`calendarId`) REFERENCES `Calendar` (`calendarId`);

--
-- Constraints for table `ClimberProfile`
--
ALTER TABLE `ClimberProfile`
  ADD CONSTRAINT `ClimberProfile_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `EndUser` (`userId`);

--
-- Constraints for table `Comments`
--
ALTER TABLE `Comments`
  ADD CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `EndUser` (`userId`),
  ADD CONSTRAINT `Comments_ibfk_2` FOREIGN KEY (`videoId`) REFERENCES `Videos` (`videoId`);

--
-- Constraints for table `Favourites`
--
ALTER TABLE `Favourites`
  ADD CONSTRAINT `Favourites_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `EndUser` (`userId`),
  ADD CONSTRAINT `Favourites_ibfk_2` FOREIGN KEY (`videoId`) REFERENCES `Videos` (`videoId`);

--
-- Constraints for table `InstructorProfile`
--
ALTER TABLE `InstructorProfile`
  ADD CONSTRAINT `InstructorProfile_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `EndUser` (`userId`);

--
-- Constraints for table `Labels`
--
ALTER TABLE `Labels`
  ADD CONSTRAINT `Labels_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `EndUser` (`userId`),
  ADD CONSTRAINT `Labels_ibfk_2` FOREIGN KEY (`videoId`) REFERENCES `Videos` (`videoId`);

--
-- Constraints for table `Videos`
--
ALTER TABLE `Videos`
  ADD CONSTRAINT `Videos_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `EndUser` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
