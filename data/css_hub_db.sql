-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `css_hub_db`
--
CREATE DATABASE IF NOT EXISTS `css_hub_db`;

USE `css_hub_db`;

-- --------------------------------------------------------
-- Disable foreign key checks for clean drop
SET
  FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `event_registrations`;

DROP TABLE IF EXISTS `event_suggestions`;

DROP TABLE IF EXISTS `form_responses`;

DROP TABLE IF EXISTS `form_questions`;

DROP TABLE IF EXISTS `forms`;

DROP TABLE IF EXISTS `help_messages`;

DROP TABLE IF EXISTS `merch_orders`;

DROP TABLE IF EXISTS `resource_usage`;

DROP TABLE IF EXISTS `resources`;

DROP TABLE IF EXISTS `events`;

DROP TABLE IF EXISTS `users`;

SET
  FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Table structure for `users`
--
CREATE TABLE
  `users` (
    `email` varchar(100) NOT NULL,
    `mac_id` varchar(50) NOT NULL,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `password_hash` varchar(255) NOT NULL,
    PRIMARY KEY (`email`),
    UNIQUE KEY `mac_id` (`mac_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `users` (passwords: 'password123', 'password456', 'password789' hashed with SHA256)
--
INSERT INTO
  `users` (
    `email`,
    `mac_id`,
    `first_name`,
    `last_name`,
    `password_hash`
  )
VALUES
  (
    'gbea@mcmaster.ca',
    'gbea',
    'Amanda',
    'Gbe',
    'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f'
  ),
  (
    'hlak@mcmaster.ca',
    'hlak',
    'Harshini',
    'Lakshmanan',
    'c6ba91b90d922e159893f46c387e5dc1b3dc5c101a5a4522f03b987177a24a91'
  ),
  (
    'dyu@mcmaster.ca',
    'dyu',
    'Daniel',
    'Yu',
    '5efc2b017da4f7736d192a74dde5891369e0685d4d38f2a455b6fcdab282df9c'
  );

-- --------------------------------------------------------
-- Table structure for `events`
--
CREATE TABLE
  `events` (
    `event_id` int (11) NOT NULL AUTO_INCREMENT,
    `event_title` varchar(100) NOT NULL,
    `event_description` text NOT NULL,
    `event_date` date NOT NULL,
    `event_time` time NOT NULL,
    `location` varchar(100) NOT NULL,
    `stream_link` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`event_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `events`
--
INSERT INTO
  `events` (
    `event_id`,
    `event_title`,
    `event_description`,
    `event_date`,
    `event_time`,
    `location`,
    `stream_link`
  )
VALUES
  (
    1,
    'Intro to CSS',
    'A beginner-friendly workshop on CSS fundamentals.',
    '2026-09-10',
    '10:00:00',
    'Room 101',
    'https://streaminglink.com/intro-css'
  ),
  (
    2,
    'CSS Workshop',
    'Learn the basics of CSS in this hands-on workshop.',
    '2026-10-15',
    '14:00:00',
    'Room 101',
    'https://streaminglink.com/css-workshop'
  ),
  (
    3,
    'Web Development Seminar',
    'Join us for a seminar on modern web development practices.',
    '2026-11-20',
    '16:00:00',
    'Auditorium',
    'https://streaminglink.com/web-dev-seminar'
  );

-- --------------------------------------------------------
-- Table structure for `forms`
--
CREATE TABLE
  `forms` (
    `form_id` int (11) NOT NULL AUTO_INCREMENT,
    `form_title` varchar(100) NOT NULL,
    `form_type` varchar(50) DEFAULT NULL,
    `form_description` text DEFAULT NULL,
    `create_date` date DEFAULT NULL,
    PRIMARY KEY (`form_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--
INSERT INTO
  `forms` (
    `form_id`,
    `form_title`,
    `form_type`,
    `form_description`,
    `create_date`
  )
VALUES
  (
    1,
    'CSS Membership Form',
    'membership',
    'Use this form to apply for CSS membership.',
    '2024-06-01'
  ),
  (
    2,
    'Club Application Form',
    'club_application',
    'Apply to start a new club on campus.',
    '2024-06-01'
  ),
  (
    3,
    'Event Request Form',
    'event_request',
    'Use this form to request an event.',
    '2024-06-01'
  );

-- --------------------------------------------------------
-- Table structure for `form_questions`
--
CREATE TABLE
  `form_questions` (
    `question_id` int (11) NOT NULL AUTO_INCREMENT,
    `form_id` int (11) NOT NULL,
    `question_text` text NOT NULL,
    `question_type` varchar(50) DEFAULT NULL,
    `question_order` int (11) DEFAULT NULL,
    PRIMARY KEY (`question_id`),
    KEY `form_id` (`form_id`),
    CONSTRAINT `form_questions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`form_id`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `form_questions`
--
INSERT INTO
  `form_questions` (
    `question_id`,
    `form_id`,
    `question_text`,
    `question_type`,
    `question_order`
  )
VALUES
  (1, 1, 'What is your full name?', 'text', 1),
  (2, 1, 'What program are you in?', 'text', 2),
  (3, 1, 'Why do you want to join CSS?', 'text', 3),
  (
    4,
    2,
    'What is the name of your proposed club?',
    'text',
    1
  ),
  (
    5,
    2,
    'What is the purpose of your club?',
    'text',
    2
  ),
  (
    6,
    2,
    'How many members do you expect to have?',
    'text',
    3
  ),
  (
    7,
    3,
    'What is the name of your event?',
    'text',
    1
  ),
  (
    8,
    3,
    'What is the date of your event?',
    'text',
    2
  ),
  (
    9,
    3,
    'Provide a brief description of your event.',
    'text',
    3
  );

-- --------------------------------------------------------
-- Table structure for `form_responses`
--
CREATE TABLE
  `form_responses` (
    `response_id` int (11) NOT NULL AUTO_INCREMENT,
    `email` varchar(100) NOT NULL,
    `form_id` int (11) NOT NULL,
    `question_id` int (11) NOT NULL,
    `response_text` text DEFAULT NULL,
    `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`response_id`),
    KEY `email` (`email`),
    KEY `form_id` (`form_id`),
    KEY `question_id` (`question_id`),
    CONSTRAINT `form_responses_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE,
    CONSTRAINT `form_responses_ibfk_2` FOREIGN KEY (`form_id`) REFERENCES `forms` (`form_id`) ON DELETE CASCADE,
    CONSTRAINT `form_responses_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `form_questions` (`question_id`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `form_responses`
--
INSERT INTO
  `form_responses` (
    `response_id`,
    `email`,
    `form_id`,
    `question_id`,
    `response_text`,
    `submitted_at`
  )
VALUES
  (
    1,
    'dyu@mcmaster.ca',
    1,
    1,
    'First Last',
    '2026-04-02 10:10:46'
  ),
  (
    2,
    'dyu@mcmaster.ca',
    1,
    2,
    'Program',
    '2026-04-02 10:10:46'
  ),
  (
    3,
    'dyu@mcmaster.ca',
    1,
    3,
    'Reason',
    '2026-04-02 10:10:46'
  );

-- --------------------------------------------------------
-- Table structure for `resources`
--
CREATE TABLE
  `resources` (
    `resource_id` int (11) NOT NULL AUTO_INCREMENT,
    `resource_title` varchar(100) NOT NULL,
    `resource_type` varchar(50) DEFAULT NULL,
    `resource_description` text DEFAULT NULL,
    `file_link` varchar(255) DEFAULT NULL,
    `course_code` varchar(20) DEFAULT NULL,
    `upload_date` date DEFAULT NULL,
    PRIMARY KEY (`resource_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--
INSERT INTO
  `resources` (
    `resource_id`,
    `resource_title`,
    `resource_type`,
    `resource_description`,
    `file_link`,
    `course_code`,
    `upload_date`
  )
VALUES
  (
    1,
    'COMPSCI 1XD3 – Textbook',
    'Textbook',
    'Complete notes covering all modules',
    'https://ecampusontario.pressbooks.pub/webdev/front-matter/introduction/',
    '1XD3',
    '2026-04-02'
  ),
  (
    2,
    'COMPSCI 1XC3 – Practice Test',
    'Practice material',
    'Practice test for midterm preparation',
    '../uploads/1XC3_practice_test.pdf',
    '1XC3',
    '2026-04-02'
  ),
  (
    3,
    'Software Tools Guide',
    'guide',
    'Setup instructions for Git, VS Code, XAMPP',
    'tools_guide.php',
    '1XD3',
    '2026-04-02'
  );

-- --------------------------------------------------------
-- Table structure for `event_registrations`
--
CREATE TABLE
  `event_registrations` (
    `registration_id` int (11) NOT NULL AUTO_INCREMENT,
    `email` varchar(100) NOT NULL,
    `event_id` int (11) NOT NULL,
    `registration_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`registration_id`),
    UNIQUE KEY `email_event` (`email`, `event_id`),
    KEY `event_id` (`event_id`),
    CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE,
    CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--
INSERT INTO
  `event_registrations` (
    `registration_id`,
    `email`,
    `event_id`,
    `registration_date`
  )
VALUES
  (1, 'dyu@mcmaster.ca', 3, '2026-04-02 10:09:01');

-- --------------------------------------------------------
-- Table structure for `event_suggestions`
--
CREATE TABLE
  `event_suggestions` (
    `suggestion_id` int (11) NOT NULL AUTO_INCREMENT,
    `email` varchar(100) NOT NULL,
    `subject` varchar(100) NOT NULL,
    `message` text NOT NULL,
    `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `status` enum ('pending', 'approved', 'rejected') DEFAULT 'pending',
    PRIMARY KEY (`suggestion_id`),
    KEY `email` (`email`),
    CONSTRAINT `event_suggestions_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `event_suggestions`
--
INSERT INTO
  `event_suggestions` (
    `suggestion_id`,
    `email`,
    `subject`,
    `message`,
    `submitted_at`,
    `status`
  )
VALUES
  (
    1,
    'dyu@mcmaster.ca',
    'TestSubject1',
    'TestMessage1',
    '2026-04-02 10:10:14',
    'pending'
  );

-- --------------------------------------------------------
-- Table structure for `merch_orders`
--
CREATE TABLE
  `merch_orders` (
    `order_id` int (11) NOT NULL AUTO_INCREMENT,
    `email` varchar(100) NOT NULL,
    `item_name` varchar(100) NOT NULL,
    `size` varchar(20) DEFAULT NULL,
    `quantity` int (11) NOT NULL,
    `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `status` varchar(50) DEFAULT 'pending',
    PRIMARY KEY (`order_id`),
    KEY `email` (`email`),
    CONSTRAINT `merch_orders_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `merch_orders`
--
INSERT INTO
  `merch_orders` (
    `order_id`,
    `email`,
    `item_name`,
    `size`,
    `quantity`,
    `order_date`,
    `status`
  )
VALUES
  (
    1,
    'dyu@mcmaster.ca',
    'Hoodie',
    'L',
    1,
    '2026-04-02 08:51:22',
    'paid'
  ),
  (
    2,
    'hlak@mcmaster.ca',
    'T-shirt',
    'L',
    1,
    '2026-04-02 10:05:16',
    'paid'
  ),
  (
    3,
    'gbea@mcmaster.ca',
    'T-shirt',
    'M',
    2,
    '2026-04-02 10:08:17',
    'pending'
  ),
  (
    4,
    'hlak@mcmaster.ca',
    'T-shirt',
    'M',
    2,
    '2026-04-02 12:18:51',
    'pending'
  );

-- --------------------------------------------------------
-- Table structure for `resource_usage`
--
CREATE TABLE
  `resource_usage` (
    `usage_id` int (11) NOT NULL AUTO_INCREMENT,
    `email` varchar(100) NOT NULL,
    `resource_id` int (11) NOT NULL,
    `date_accessed` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`usage_id`),
    KEY `email` (`email`),
    KEY `resource_id` (`resource_id`),
    CONSTRAINT `resource_usage_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE,
    CONSTRAINT `resource_usage_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `resource_usage`
--
INSERT INTO
  `resource_usage` (
    `usage_id`,
    `email`,
    `resource_id`,
    `date_accessed`
  )
VALUES
  (1, 'gbea@mcmaster.ca', 2, '2026-04-02 12:40:53'),
  (2, 'dyu@mcmaster.ca', 1, '2026-04-02 12:40:55');

-- --------------------------------------------------------
-- Table structure for `help_messages`
--
CREATE TABLE
  `help_messages` (
    `help_id` int (11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `subject` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`help_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `help_messages` (empty)
--
-- --------------------------------------------------------
-- Commit transaction
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;