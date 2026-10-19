-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2026 at 01:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `consultease`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `LogID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Action` varchar(255) NOT NULL,
  `Timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`LogID`, `UserID`, `Action`, `Timestamp`) VALUES
(1, 5, 'ADD - Added request #5 (slot 2 on 2026-10-20)', '2026-10-19 19:00:56'),
(2, 5, 'DELETE - Deleted request #5', '2026-10-19 19:01:05'),
(3, 5, 'UPDATE - Updated request #2 (status: Pending)', '2026-10-19 19:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `consultation_requests`
--

CREATE TABLE `consultation_requests` (
  `RequestID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `SlotID` int(11) NOT NULL,
  `ConsultationDate` date NOT NULL,
  `Purpose` varchar(255) NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Pending',
  `DateRequested` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation_requests`
--

INSERT INTO `consultation_requests` (`RequestID`, `StudentID`, `SlotID`, `ConsultationDate`, `Purpose`, `Status`, `DateRequested`) VALUES
(1, 2, 1, '2025-11-02', 'tuition', 'Pending', '2026-08-26'),
(2, 2, 2, '2026-09-26', '2131232', 'Pending', '2026-09-02'),
(3, 1, 1, '2026-09-11', 'awdawdaw', 'Pending', '2026-09-02'),
(4, 1, 2, '2026-09-05', 'awawdwadaw', 'Pending', '2026-09-02');

-- --------------------------------------------------------

--
-- Table structure for table `consultation_slots`
--

CREATE TABLE `consultation_slots` (
  `SlotID` int(11) NOT NULL,
  `FacultyID` int(11) NOT NULL,
  `DayOfWeek` varchar(20) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation_slots`
--

INSERT INTO `consultation_slots` (`SlotID`, `FacultyID`, `DayOfWeek`, `StartTime`, `EndTime`, `Status`) VALUES
(1, 1, 'Monday', '09:00:00', '10:00:00', 'Open'),
(2, 1, 'Wednesday', '13:00:00', '14:00:00', 'Open'),
(3, 2, 'Tuesday', '10:00:00', '11:00:00', 'Open');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `DepartmentID` int(11) NOT NULL,
  `DepartmentName` varchar(100) NOT NULL,
  `Building` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`DepartmentID`, `DepartmentName`, `Building`) VALUES
(1, 'College of Computer Studies', 'Main Building');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `FacultyID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DepartmentID` int(11) NOT NULL,
  `Position` varchar(50) DEFAULT NULL,
  `OfficeLocation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`FacultyID`, `UserID`, `DepartmentID`, `Position`, `OfficeLocation`) VALUES
(1, 1, 1, 'Dean / Adviser', 'Faculty Room 101'),
(2, 2, 1, 'Instructor', 'Faculty Room 102');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `NotificationID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Message` varchar(255) NOT NULL,
  `DateSent` date NOT NULL,
  `IsRead` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Course` varchar(50) DEFAULT NULL,
  `YearLevel` varchar(20) DEFAULT NULL,
  `Section` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`StudentID`, `UserID`, `Course`, `YearLevel`, `Section`) VALUES
(1, 3, 'BSIT', '3rd Year', 'A'),
(2, 4, 'BSIT', '3rd Year', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `Email`, `PasswordHash`, `Role`) VALUES
(1, 'Dr. Jonel Gelig', 'jgelig@crmc.edu.ph', 'hashed_pw_1', 'faculty'),
(2, 'Prof. Marjorie Reso', 'mreso@crmc.edu.ph', 'hashed_pw_2', 'faculty'),
(3, 'Kevin Ramos', 'kramos@crmc.edu.ph', 'hashed_pw_3', 'student'),
(4, 'Ana Bautista', 'abautista@crmc.edu.ph', 'hashed_pw_4', 'student'),
(5, 'Winston Susvilla', 'susvillawinstonlee@gmail.com', '$2y$10$BeIdp53vnqRI5KO.ZrjMeeyLrHC3L8vS7y2sc.NzqlSqOhdpX2.LS', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `consultation_requests`
--
ALTER TABLE `consultation_requests`
  ADD PRIMARY KEY (`RequestID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `SlotID` (`SlotID`);

--
-- Indexes for table `consultation_slots`
--
ALTER TABLE `consultation_slots`
  ADD PRIMARY KEY (`SlotID`),
  ADD KEY `FacultyID` (`FacultyID`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`FacultyID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `DepartmentID` (`DepartmentID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `consultation_requests`
--
ALTER TABLE `consultation_requests`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `consultation_slots`
--
ALTER TABLE `consultation_slots`
  MODIFY `SlotID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `FacultyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `consultation_requests`
--
ALTER TABLE `consultation_requests`
  ADD CONSTRAINT `consultation_requests_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultation_requests_ibfk_2` FOREIGN KEY (`SlotID`) REFERENCES `consultation_slots` (`SlotID`) ON DELETE CASCADE;

--
-- Constraints for table `consultation_slots`
--
ALTER TABLE `consultation_slots`
  ADD CONSTRAINT `consultation_slots_ibfk_1` FOREIGN KEY (`FacultyID`) REFERENCES `faculty` (`FacultyID`) ON DELETE CASCADE;

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `faculty_ibfk_2` FOREIGN KEY (`DepartmentID`) REFERENCES `departments` (`DepartmentID`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
