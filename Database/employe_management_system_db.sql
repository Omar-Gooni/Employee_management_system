-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307:3307
-- Generation Time: Jan 29, 2025 at 05:36 PM
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
-- Database: `employe_management_system_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AssignEmployeeToProject` (IN `emp_id` INT, IN `proj_id` INT)   BEGIN
    INSERT INTO employee_projects(employee_id, project_id)
    VALUES (emp_id, proj_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertAttendance` (IN `emp_id` INT, IN `emp_status` ENUM('Present','Absent'), IN `emp_check_in` DATETIME, IN `emp_check_out` DATETIME)   BEGIN
    INSERT INTO Attendance (employee_id, status, check_in_time, check_out_time, created_date)
    VALUES (emp_id, emp_status, emp_check_in, emp_check_out, CURRENT_TIMESTAMP);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertDepartment` (IN `_Department_Name` VARCHAR(100), IN `_Manager_ID` VARCHAR(11))   BEGIN
    INSERT INTO departments (Department_Name, Manager_ID)
    VALUES (_Department_Name, _Manager_ID);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertEmployee` (IN `emp_name` VARCHAR(100), IN `emp_email` VARCHAR(100), IN `emp_password` VARCHAR(100), IN `emp_job_title` VARCHAR(100), IN `emp_salary` DECIMAL(10,2), IN `emp_dep_id` INT)   BEGIN
    INSERT INTO employees (Name, Email, Password, Job_Title, Salary, Dep_ID)
    VALUES (emp_name, emp_email, emp_password, emp_job_title, emp_salary, emp_dep_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertProject` (IN `proj_name` VARCHAR(100), IN `proj_start_date` DATE, IN `proj_end_date` DATE, IN `proj_budget` DECIMAL(15,2), IN `proj_department_id` INT)   BEGIN
    INSERT INTO Projects (project_name, start_date, end_date, budget, department_id)
    VALUES (proj_name, proj_start_date, proj_end_date, proj_budget, proj_department_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAttendance` (IN `emp_id` INT, IN `status` VARCHAR(20), IN `check_in` DATETIME, IN `check_out` DATETIME)   BEGIN
    UPDATE attendance
    SET status = status, check_in_time = check_in, check_out_time = check_out
    WHERE employee_id = emp_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `Name` varchar(250) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `Name`, `Email`, `password`, `role`, `created_at`) VALUES
(1, 'Omar Mohamuud Gooni', 'Omar12@gmail.com', '2182', 'admin', '2025-01-11 10:23:38'),
(2, 'Sahra Ahmed Shaakir', 'sahra1@gmail.com', '1122', 'admin', '2025-01-29 14:20:09');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` enum('Present','Absent') NOT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `employee_id`, `status`, `check_in_time`, `check_out_time`, `created_date`) VALUES
(1, 2, 'Present', '2025-01-16 17:16:15', '2025-01-16 17:16:15', '2025-01-16 14:16:15'),
(2, 3, 'Present', '2025-01-16 07:16:15', '2025-01-16 12:16:15', '2025-01-16 14:41:48'),
(8, 9, 'Absent', NULL, NULL, '2025-01-29 16:27:51');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `ID` int(11) NOT NULL,
  `Department_Name` varchar(100) NOT NULL,
  `Manager_ID` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`ID`, `Department_Name`, `Manager_ID`, `created_at`) VALUES
(1, 'Department A', 2, '2025-01-05 11:33:33'),
(5, 'Department C', 9, '2025-01-11 08:08:40'),
(6, 'Department D', 8, '2025-01-11 08:09:09'),
(16, 'Department I', 5, '2025-01-29 16:00:34'),
(17, 'Department B', 9, '2025-01-29 16:05:03');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Job_title` varchar(255) NOT NULL,
  `Salary` decimal(10,2) NOT NULL,
  `Dep_ID` int(11) NOT NULL,
  `Created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`ID`, `Name`, `Email`, `Password`, `Job_title`, `Salary`, `Dep_ID`, `Created_date`) VALUES
(2, 'Amino Ahmed ', 'amin@gmail.com', '123', 'Employee', 200.00, 5, '2025-01-28 10:34:58'),
(5, 'Mohamed Faarah Ahmed', 'MohamedFaarah@gmail.com', '2182', 'Manager', 2000.00, 6, '2025-01-28 13:08:43'),
(6, 'Egland Omar', 'Egland@gmail.com', '5602', 'Manager', 10000.00, 5, '2025-01-28 13:10:43'),
(7, 'geedi hassan', 'geedi@gmail.com', '1122', 'Manager', 2000.00, 1, '2025-01-29 16:01:30'),
(8, 'muuse hassan', 'muuse@gmail.com', '2211', 'Employee', 10000.00, 16, '2025-01-29 16:02:40'),
(9, 'Haliimo Faarah', 'haliimo@gmail.com', '1111', 'Employee', 10000.00, 5, '2025-01-29 16:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `employee_projects`
--

CREATE TABLE `employee_projects` (
  `Employee_Projects_ID` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_projects`
--

INSERT INTO `employee_projects` (`Employee_Projects_ID`, `employee_id`, `project_id`) VALUES
(13, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_name`, `start_date`, `end_date`, `budget`, `department_id`) VALUES
(1, 'project A', '2025-01-01', '2025-02-01', 1000.00, 1),
(3, 'project B', '2025-03-01', '2025-04-01', 2000.00, 5),
(5, 'project C', '2025-01-28', '2025-02-28', 1000.00, 5),
(6, 'project Y', '2025-05-28', '2025-06-28', 1000.00, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_email` (`Email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Manager_ID` (`Manager_ID`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Dep_ID` (`Dep_ID`);

--
-- Indexes for table `employee_projects`
--
ALTER TABLE `employee_projects`
  ADD PRIMARY KEY (`Employee_Projects_ID`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee_projects`
--
ALTER TABLE `employee_projects`
  MODIFY `Employee_Projects_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`ID`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`Manager_ID`) REFERENCES `employees` (`ID`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`Dep_ID`) REFERENCES `departments` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `employee_projects`
--
ALTER TABLE `employee_projects`
  ADD CONSTRAINT `employee_projects_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`ID`),
  ADD CONSTRAINT `employee_projects_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
