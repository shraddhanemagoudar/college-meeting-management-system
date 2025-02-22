-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2024 at 08:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meeting_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'CSE'),
(2, 'AI&DS'),
(3, 'ECE'),
(4, 'Mechanical'),
(5, 'civil'),
(6, 'Robotics and automation'),
(7, 'EEE');

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `agenda` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `principal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `agenda`, `date`, `time`, `venue`, `created_at`, `principal_id`) VALUES
(1, 'exam', '2024-07-08', '17:06:00', 'meeting room', '2024-07-06 10:53:40', NULL),
(2, 'exam', '2024-07-08', '17:06:00', 'meeting room', '2024-07-06 11:20:23', NULL),
(3, 'exam', '2024-07-08', '17:06:00', 'meeting room', '2024-07-06 11:25:40', NULL),
(9, 'Cancer', '2024-07-16', '00:00:00', 'Boys Hostel', '2024-07-15 06:45:00', NULL),
(10, 'Cancer', '2024-07-16', '00:00:00', 'Boys Hostel', '2024-07-15 06:45:03', NULL),
(11, 'exam', '2024-07-17', '16:37:00', 'meeting room', '2024-07-16 09:07:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meeting_history`
--

CREATE TABLE `meeting_history` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `attended` tinyint(1) DEFAULT NULL,
  `outcome` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_history`
--

INSERT INTO `meeting_history` (`id`, `meeting_id`, `staff_id`, `attended`, `outcome`, `created_at`) VALUES
(1, 1, 2, 1, 'good', '2024-07-09 17:25:00'),
(2, 2, 2, 1, 'excellent', '2024-07-14 16:17:18'),
(3, 1, 2, 0, 'whdbuculoewjgcy', '2024-07-15 06:14:57'),
(4, 1, 2, 0, 'whdbuculoewjgcy', '2024-07-15 06:15:37');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `department_id` int(11) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `email`, `department`, `department_id`, `role`) VALUES
(2, 'shraddha', 'shraddhanemagoudar23@example.com', '', 1, 'staff'),
(3, 'khushi', 'khushitagoudar@example.com', '', 2, 'staff'),
(4, 'anusha', 'belavianusha65@example.com', '', 2, 'staff'),
(5, 'abhishekh', 'abhishinde@gmail.com', 'CSE', 1, 'Staff'),
(6, 'abhishekh', 'abhishinde@gmail.com', 'CSE', 1, 'Staff'),
(7, 'pooja', 'pooja@gmail.com', 'CSE', 1, 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text DEFAULT NULL,
  `assigned_to` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_name`, `task_description`, `assigned_to`, `due_date`, `status`) VALUES
(1, 'exam preparation', 'extra class for questions paper solving', 'all departments', '2024-07-14', 'Completed'),
(2, 'question paper', 'print 100 papers to cse', 'CSE department', '2024-07-15', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Username` varchar(255) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `role` enum('Principal','HOD','Staff','Admin') NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Username`, `Email`, `Password`, `role`, `id`) VALUES
('shraddha', 'shraddha@gmail.com', '$2y$10$FR4eaOAyzuzV45VMTfdyyOSsudxRKouT9fCwYacTGMLqdXCntlEty', 'Principal', 4),
('khushi', 'khushitagoudar@gmail.c', '$2y$10$2E86zvzRzJOSEO.oyxxfYu5BOw4VQy/jzpo/ajISQ1/nwOKlLvpVe', 'HOD', 5),
('poonamn', 'poonam@gmail.com', '$2y$10$Lyz1Ei6V6BtVDbp9P/hj0O/6lbbZKS2Tvncb5D.Jr7mLizbSEJcye', 'Admin', 14),
('abhi', 'abhishinde2@gmail.com', '$2y$10$WTxa3kA1xk0UQws8XMN3lOwfS6I.PwUUmhEcWmBPOqdqCkYbHy.Ae', 'Staff', 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_meetings_principal` (`principal_id`);

--
-- Indexes for table `meeting_history`
--
ALTER TABLE `meeting_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_department` (`department_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `meeting_history`
--
ALTER TABLE `meeting_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meeting_history`
--
ALTER TABLE `meeting_history`
  ADD CONSTRAINT `meeting_history_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`),
  ADD CONSTRAINT `meeting_history_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
