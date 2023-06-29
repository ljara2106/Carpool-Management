-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 29, 2023 at 06:39 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sample_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `inqueue`
--

CREATE TABLE `inqueue` (
  `queue_id` int(50) NOT NULL,
  `student_id` int(50) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `grade` int(15) NOT NULL,
  `teacher_name` varchar(255) DEFAULT NULL,
  `teacher_id` int(2) DEFAULT NULL,
  `datetime_added` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `picked_up` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pickedup`
--

CREATE TABLE `pickedup` (
  `pickedup_id` int(50) NOT NULL,
  `student_id` int(50) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `grade` int(15) NOT NULL,
  `teacher_name` varchar(255) DEFAULT NULL,
  `teacher_id` int(2) DEFAULT NULL,
  `datetime_added` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `return` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(50) NOT NULL,
  `student_id` int(50) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `grade` varchar(15) NOT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `teacher_id` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `first_name`, `last_name`, `grade`, `teacher_name`, `teacher_id`) VALUES
(1, 1001, 'hazel', 'jara', '2', 'fairclough', 2),
(2, 1002, 'sophia', 'apple', '2', 'king', 5),
(4, 1003, 'Valeria', 'Holmes', '2', 'woodard', 1),
(5, 1004, 'Hailey', 'Allen', '2', 'king', 5),
(6, 1005, 'Roman', 'Lloyd', '3', 'fairclough', 2),
(7, 1006, 'Tyler', 'Morgan', '3', 'woodard', 1),
(8, 1007, 'Tyler', 'Henderson', '4', 'king', 5),
(9, 1008, 'Robert', 'Fowler', '2', 'fairclough', 2),
(10, 1009, 'Belinda', 'Bennett', '2', 'woodard', 1),
(11, 1010, 'Edwin', 'Warren', '3', 'king', 5),
(12, 1011, 'Luke', 'Cooper', '2', 'fairclough', 2),
(13, 1012, 'Haris', 'Hunt', '1', 'woodard', 1),
(14, 1013, 'Fiona', 'Fowler', '1', 'king', 5),
(15, 1014, 'Ryan', 'Phillips', '2', 'fairclough', 2),
(16, 1015, 'Madaline', 'Martin', '2', 'woodard', 1),
(17, 1016, 'Darcy', 'Miller', '3', 'king', 5),
(18, 1017, 'Darcy', 'Rogers', '3', 'fairclough', 2),
(19, 1018, 'Darcy', 'Roberts', '4', 'woodard', 1),
(20, 1019, 'Isabella', 'Farrell', '2', 'king', 5),
(21, 1020, 'Alford', 'Thompson', '2', 'fairclough', 2),
(22, 1021, 'Nicholas', 'Russell', '3', 'woodard', 1),
(23, 1022, 'Aston', 'Cooper', '2', 'king', 5),
(24, 1023, 'Byron', 'Cameron', '1', 'fairclough', 2),
(25, 1024, 'Emily', 'Crawford', '1', 'woodard', 1),
(26, 1025, 'Ada', 'Fowler', '2', 'king', 5),
(27, 1026, 'Jared', 'Barrett', '2', 'fairclough', 2),
(28, 1027, 'Adrianna', 'Brooks', '3', 'woodard', 1),
(29, 1028, 'Adelaide', 'Walker', '3', 'king', 5),
(30, 1029, 'Darcy', 'Lloyd', '4', 'fairclough', 2),
(31, 1030, 'Kirsten', 'Holmes', '2', 'woodard', 1),
(32, 1031, 'Daniel', 'Ellis', '2', 'king', 5),
(33, 1032, 'Lilianna', 'Ferguson', '3', 'fairclough', 2),
(34, 1033, 'Kelsey', 'Crawford', '2', 'woodard', 1),
(35, 1034, 'Tony', 'Jones', '1', 'king', 5),
(36, 1035, 'Max', 'Taylor', '1', 'fairclough', 2),
(37, 1036, 'Anna', 'Fowler', '2', 'woodard', 1),
(38, 1037, 'Ashton', 'Murphy', '2', 'king', 5),
(39, 1038, 'Rosie', 'Riley', '3', 'fairclough', 2),
(40, 1039, 'Rubie', 'Andrews', '3', 'woodard', 1),
(41, 1040, 'Luke', 'Douglas', '4', 'king', 5),
(42, 1041, 'Anna', 'Harper', '2', 'fairclough', 2),
(43, 1042, 'Alina', 'Rogers', '2', 'woodard', 1),
(44, 1043, 'Lucia', 'Dixon', '3', 'king', 5),
(45, 1044, 'Audrey', 'Harper', '2', 'fairclough', 2),
(46, 1045, 'Vincent', 'Hawkins', '1', 'woodard', 1),
(47, 1046, 'Paige', 'Bailey', '1', 'king', 5),
(48, 1047, 'James', 'Reed', '2', 'fairclough', 2),
(49, 1048, 'Connie', 'Richards', '2', 'woodard', 1),
(50, 1049, 'Grace', 'Hall', '3', 'king', 5),
(51, 1050, 'Vincent', 'Johnson', '3', 'fairclough', 2),
(52, 1051, 'Lucy', 'Riley', '4', 'woodard', 1),
(53, 1052, 'Oscar', 'Campbell', '2', 'king', 5),
(54, 1053, 'John', 'Casey', '2', 'fairclough', 2),
(55, 1054, 'Ryan', 'Sullivan', '3', 'woodard', 1),
(56, 1055, 'Heather', 'Morgan', '2', 'king', 5),
(57, 1056, 'Anna', 'Evans', '1', 'fairclough', 2),
(58, 1057, 'Alberta', 'Roberts', '1', 'woodard', 1),
(59, 1058, 'Dainton', 'Owens', '2', 'king', 5),
(60, 1059, 'Alford', 'Chapman', '2', 'fairclough', 2),
(61, 1060, 'Sydney', 'Lloyd', '3', 'woodard', 1),
(62, 1061, 'Alford', 'Richards', '3', 'king', 5),
(63, 1062, 'Cherry', 'Richards', '4', 'fairclough', 2),
(64, 1063, 'Andrew', 'Riley', '2', 'woodard', 1),
(65, 1064, 'Lily', 'Murray', '2', 'king', 5),
(66, 1065, 'Oscar', 'Wells', '3', 'fairclough', 2),
(67, 1066, 'Garry', 'Harper', '2', 'woodard', 1),
(68, 1067, 'Maddie', 'Wright', '1', 'king', 5),
(69, 1068, 'Sophia', 'Gray', '1', 'fairclough', 2),
(70, 1069, 'Eric', 'Dixon', '2', 'woodard', 1),
(71, 1070, 'Julian', 'Russell', '2', 'king', 5),
(72, 1071, 'Samantha', 'Richards', '3', 'fairclough', 2),
(73, 1072, 'Abraham', 'Harper', '3', 'woodard', 1),
(74, 1073, 'Stuart', 'Ferguson', '4', 'king', 5),
(75, 1074, 'Thomas', 'Lloyd', '2', 'fairclough', 2),
(76, 1075, 'Sawyer', 'Moore', '2', 'woodard', 1),
(77, 1076, 'Arthur', 'Hill', '3', 'king', 5),
(78, 1077, 'Rebecca', 'Wright', '2', 'fairclough', 2),
(79, 1078, 'Carina', 'Wells', '1', 'woodard', 1),
(80, 1079, 'Kelvin', 'Nelson', '1', 'king', 5),
(81, 1080, 'Eleanor', 'Tucker', '2', 'fairclough', 2),
(82, 1081, 'Alexander', 'Henderson', '2', 'woodard', 1),
(83, 1082, 'George', 'Harrison', '3', 'king', 5),
(84, 1083, 'Joyce', 'Murphy', '3', 'fairclough', 2),
(85, 1084, 'Kellan', 'Morgan', '4', 'woodard', 1),
(86, 1085, 'Alan', 'Holmes', '2', 'king', 5),
(87, 1086, 'Chloe', 'Walker', '2', 'fairclough', 2),
(88, 1087, 'Rosie', 'Brooks', '3', 'woodard', 1),
(89, 1088, 'Eleanor', 'Farrell', '2', 'king', 5),
(90, 1089, 'Ellia', 'Moore', '1', 'fairclough', 2),
(91, 1090, 'Agata', 'Kelly', '1', 'woodard', 1),
(92, 1091, 'Edwin', 'Williams', '2', 'king', 5),
(93, 1092, 'Arianna', 'Wells', '2', 'fairclough', 2),
(94, 1093, 'Jenna', 'Perkins', '3', 'woodard', 1),
(95, 1094, 'Maya', 'Murphy', '3', 'king', 5),
(96, 1095, 'Amber', 'Riley', '4', 'fairclough', 2),
(97, 1096, 'Rebecca', 'Hall', '2', 'woodard', 1),
(98, 1097, 'Eric', 'Martin', '2', 'king', 5),
(99, 1098, 'Nicholas', 'Perkins', '3', 'fairclough', 2),
(100, 1099, 'Adam', 'Kelley', '2', 'woodard', 1),
(101, 1100, 'Chloe', 'Watson', '1', 'king', 5),
(102, 1101, 'Ned', 'Hunt', '1', 'fairclough', 2),
(103, 1102, 'Madaline', 'Moore', '2', 'woodard', 1),
(104, 1103, 'Henry', 'Cunningham', '2', 'king', 5),
(105, 1104, 'Eleanor', 'Adams', '3', 'fairclough', 2),
(106, 1105, 'Hailey', 'Reed', '3', 'woodard', 1),
(107, 1106, 'Alina', 'Murray', '4', 'king', 5),
(108, 1107, 'Deanna', 'Casey', '2', 'fairclough', 2),
(109, 1108, 'Lucy', 'Payne', '2', 'woodard', 1),
(110, 1109, 'Byron', 'Hall', '3', 'king', 5),
(111, 1110, 'April', 'Craig', '2', 'fairclough', 2),
(112, 1111, 'Tony', 'Cunningham', '1', 'woodard', 1),
(113, 1112, 'Lydia', 'Murphy', '1', 'king', 5),
(114, 1113, 'Lilianna', 'Murray', '2', 'fairclough', 2),
(115, 1114, 'Victor', 'Anderson', '2', 'woodard', 1),
(116, 1115, 'Lily', 'Wells', '3', 'king', 5),
(117, 1116, 'Haris', 'Wells', '3', 'fairclough', 2),
(118, 1117, 'Tony', 'Taylor', '4', 'woodard', 1),
(119, 1118, 'Max', 'Carroll', '2', 'king', 5),
(120, 1119, 'Darcy', 'Riley', '2', 'fairclough', 2),
(121, 1120, 'Adam', 'Mitchell', '3', 'woodard', 1),
(122, 1121, 'Lenny', 'Jones', '2', 'king', 5),
(123, 1122, 'Preston', 'Morrison', '1', 'fairclough', 2),
(124, 1123, 'Alexander', 'Wells', '1', 'woodard', 1),
(125, 1124, 'Jasmine', 'Riley', '4', 'king', 5),
(126, 1125, 'Henry', 'Johnston', '5', 'fairclough', 2),
(127, 1126, 'Cherry', 'Riley', '5', 'woodard', 1),
(128, 1127, 'Maria', 'Robinson', '5', 'king', 5),
(129, 1128, 'Sam', 'Farrell', '1', 'fairclough', 2),
(130, 5861, 'Test', 'Test', '2', 'britt', 6);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `teacher_id` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password_hash`, `teacher_id`) VALUES
(6, 'admin@email.com', 'admin@email.com', '$2y$10$3tP9PQRt0twTsa3g.MEaGufBhBdzHVSUKV./4zHoGlCLCvkraK80m', NULL),
(7, 'testme', 'test@email.com', '$2y$10$jrbzW8bHBaZgUmwMr5eOoueMw26fjEltpqbwcnEt05LtdRqdczj6C', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inqueue`
--
ALTER TABLE `inqueue`
  ADD PRIMARY KEY (`queue_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `pickedup`
--
ALTER TABLE `pickedup`
  ADD PRIMARY KEY (`pickedup_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inqueue`
--
ALTER TABLE `inqueue`
  MODIFY `queue_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `pickedup`
--
ALTER TABLE `pickedup`
  MODIFY `pickedup_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
