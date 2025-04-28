-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2024 at 05:33 PM
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
-- Database: `idpsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `staffnum` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `salt` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `name`, `staffnum`, `email`, `pass`, `salt`) VALUES
(1, 'admin', 'adm01', 'admin@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10'),
(2, 'admintwo', '02222', 'admin22@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10');

-- --------------------------------------------------------

--
-- Table structure for table `committee`
--

CREATE TABLE `committee` (
  `cid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `staffnum` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `verify` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `committee`
--

INSERT INTO `committee` (`cid`, `name`, `staffnum`, `email`, `pass`, `salt`, `verify`) VALUES
(1, 'committee', 'C01', 'committee@gmail.com', '$2y$10$HKVgVz96sVFmt0zDdo549.Q5ccyED5u2eQs/HVhmlwmeu2aVTLMn2', '5fdb71a944edc8f9bc1ac9732c1d8a15', 1),
(2, 'committee02', 'c02', 'committee02@gmail.com', 'committee02', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `context`
--

CREATE TABLE `context` (
  `contid` int(11) NOT NULL,
  `context` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `context`
--

INSERT INTO `context` (`contid`, `context`) VALUES
(1, 'Synopsis'),
(2, 'Minute Meetings'),
(3, 'Proposal'),
(4, 'Final Report'),
(5, 'Video and Poster');

-- --------------------------------------------------------

--
-- Table structure for table `duedate`
--

CREATE TABLE `duedate` (
  `dueid` int(11) NOT NULL,
  `dueDateTime` varchar(50) NOT NULL,
  `contid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duedate`
--

INSERT INTO `duedate` (`dueid`, `dueDateTime`, `contid`) VALUES
(41, '09/20/2024 2:41 PM', 2),
(44, '06/14/2024 2:17 PM', 4),
(45, '05/09/2024 4:58 PM', 1),
(46, '07/11/2024 6:54 PM', 3),
(47, '08/17/2024 2:46 PM', 5);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `eventid` int(11) NOT NULL,
  `weekNo` int(11) NOT NULL,
  `event` text NOT NULL,
  `date` text NOT NULL,
  `startTime` text NOT NULL,
  `endTime` text NOT NULL,
  `venue` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`eventid`, `weekNo`, `event`, `date`, `startTime`, `endTime`, `venue`) VALUES
(16, 1, 'IDP Seminar Intro', '2024-05-21', '13:55', '14:56', 'DK1'),
(17, 1, 'IDP Orientation', '2024-05-14', '12:55', '13:56', 'DK1'),
(18, 2, 'IDP Industrial Talk', '2024-06-04', '00:04', '01:04', 'DK 5'),
(21, 4, 'Testing123', '2024-06-11', '15:03', '16:04', 'Dk 5'),
(22, 4, 'Test', '2024-05-30', '16:04', '19:07', 'DK 4'),
(23, 1, 'Testing123', '2024-06-14', '04:26', '05:27', 'DK 5'),
(24, 5, 'testing', '2024-06-05', '05:43', '05:43', 'DK1'),
(26, 5, 'Testing123', '2024-05-29', '15:44', '16:45', 'DK 5'),
(27, 15, 'Testing111111', '2024-06-20', '15:46', '15:46', 'DK 5'),
(28, 5, 'Testing', '2024-06-28', '15:47', '15:47', 'DK 5');

-- --------------------------------------------------------

--
-- Table structure for table `grpmark`
--

CREATE TABLE `grpmark` (
  `grpmarkid` int(11) NOT NULL,
  `grpid` int(11) NOT NULL,
  `reportMarkSV` float NOT NULL,
  `reportMarkExaminer` float NOT NULL,
  `idpexMarkSV` float NOT NULL,
  `idpexMarkPanel` float NOT NULL,
  `proposalMark` float NOT NULL,
  `mmMarkComm` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grpmark`
--

INSERT INTO `grpmark` (`grpmarkid`, `grpid`, `reportMarkSV`, `reportMarkExaminer`, `idpexMarkSV`, `idpexMarkPanel`, `proposalMark`, `mmMarkComm`) VALUES
(7, 12, 16, 17.4, 15, 14.1, 8.75, 5),
(8, 13, 9.6, 17.8, 12, 0, 9.75, 2);

-- --------------------------------------------------------

--
-- Table structure for table `idpexeval`
--

CREATE TABLE `idpexeval` (
  `idpexevalid` int(11) NOT NULL,
  `evaluatorid` int(11) NOT NULL,
  `evalType` text NOT NULL,
  `grpid` int(11) NOT NULL,
  `trait1` int(11) NOT NULL,
  `trait2` int(11) NOT NULL,
  `trait3` int(11) NOT NULL,
  `trait4` int(11) NOT NULL,
  `trait5` int(11) NOT NULL,
  `trait6` int(11) NOT NULL,
  `trait7` int(11) NOT NULL,
  `trait8` int(11) NOT NULL,
  `comment` text NOT NULL,
  `submitTime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `idpexeval`
--

INSERT INTO `idpexeval` (`idpexevalid`, `evaluatorid`, `evalType`, `grpid`, `trait1`, `trait2`, `trait3`, `trait4`, `trait5`, `trait6`, `trait7`, `trait8`, `comment`, `submitTime`) VALUES
(23, 2, 'supervisor', 12, 5, 5, 5, 5, 5, 5, 5, 5, '', '07/08/2024 11:16 PM'),
(24, 1, 'panel', 12, 5, 4, 5, 5, 4, 5, 4, 5, '', '07/08/2024 11:17 PM');

-- --------------------------------------------------------

--
-- Table structure for table `idpfile`
--

CREATE TABLE `idpfile` (
  `fileid` int(11) NOT NULL,
  `filename` text NOT NULL,
  `filesize` int(200) NOT NULL,
  `filetype` varchar(100) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `type` varchar(200) NOT NULL,
  `comment` varchar(8000) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `submitted` tinyint(1) NOT NULL,
  `grpid` int(11) NOT NULL,
  `submitTime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `idpfile`
--

INSERT INTO `idpfile` (`fileid`, `filename`, `filesize`, `filetype`, `upload_date`, `type`, `comment`, `approved`, `submitted`, `grpid`, `submitTime`) VALUES
(66, 'PROPOSALG1.pdf', 15698, 'application/pdf', '2024-07-08 02:11:43', 'proposal', '', 1, 1, 12, '07/08/2024 2:11 AM'),
(67, 'FINAL RPT G1.pdf', 15585, 'application/pdf', '2024-07-08 02:12:03', 'finalreport', '', 1, 1, 12, '07/08/2024 2:12 AM'),
(68, 'PROPOSAL G2.pdf', 15662, 'application/pdf', '2024-07-08 02:16:23', 'proposal', '', 1, 1, 13, '07/08/2024 2:16 AM'),
(69, 'FR  G2.pdf', 15857, 'application/pdf', '2024-07-08 02:16:44', 'finalreport', '', 1, 1, 13, '07/08/2024 2:16 AM'),
(70, 'POSTER G2.pdf', 15745, 'application/pdf', '2024-07-08 02:17:38', 'vidposter', '', 1, 1, 13, '07/08/2024 2:17 AM'),
(71, 'poster g1.pdf', 15747, 'application/pdf', '2024-07-08 02:22:56', 'vidposter', '', 0, 1, 12, '07/08/2024 2:22 AM');

-- --------------------------------------------------------

--
-- Table structure for table `idpgroup`
--

CREATE TABLE `idpgroup` (
  `grpid` int(11) NOT NULL,
  `grpnum` int(11) NOT NULL,
  `themeid` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `idpexPanel` tinyint(1) NOT NULL,
  `idpexSV` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `idpgroup`
--

INSERT INTO `idpgroup` (`grpid`, `grpnum`, `themeid`, `active`, `idpexPanel`, `idpexSV`) VALUES
(12, 1, 1, 1, 1, 0),
(13, 2, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `idpsynopsis`
--

CREATE TABLE `idpsynopsis` (
  `synid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `synopsis` varchar(8000) NOT NULL,
  `objectives` varchar(8000) NOT NULL,
  `comment` text NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `submitted` tinyint(1) NOT NULL,
  `grpid` int(11) NOT NULL,
  `submitTime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `idpsynopsis`
--

INSERT INTO `idpsynopsis` (`synid`, `title`, `synopsis`, `objectives`, `comment`, `approved`, `submitted`, `grpid`, `submitTime`) VALUES
(40, 'Super Web', 'This college essay tip is by Charles Maynard, Oxford and Stanford University Graduate and founder of Going Merry, which is a one-stop shop for applying to college scholarships', '1. Your admissions essay should go through several stages of revision. \r\n2. And by revisions, we don’t mean quick proofreads. \r\n3. Ask your parents, teachers, high school counselors or friends for their eyes and edits. It should be people who know you best and want you to succeed. Take their constructive criticism in the spirit for which they intend—your benefit.', '', 1, 1, 12, '07/08/2024 2:13 AM'),
(41, 'Hello Website 2.0', 'Written for the Common App college application essays \"Tell us your story\" prompt. This essay could work for prompts 1 and 7 for the Common App.\r\n\r\nThey covered the precious mahogany coffin with a brown amalgam of rocks, decomposed organisms, and weeds. It was my turn to take the shovel, but I felt too ashamed to dutifully send her off when I had not properly said goodbye. I refused to throw dirt on her. I refused to let go of my grandmother, to accept a death I had not seen coming, to believe that an illness could not only interrupt, but steal a beloved life.', '1. When my parents finally revealed to me that my grandmother had been battling liver cancer, I was twelve and I was angry--mostly with myself. They had wanted to protect me\r\n2. only six years old at the time from the complex and morose concept of death. \r\n3. However, when the end inevitably arrived, I wasn’t trying to comprehend what dying was', '', 1, 1, 13, '07/08/2024 2:14 AM');

-- --------------------------------------------------------

--
-- Table structure for table `minmeet`
--

CREATE TABLE `minmeet` (
  `mmid` int(11) NOT NULL,
  `meetdate` date NOT NULL,
  `weekNo` int(11) NOT NULL,
  `taker` varchar(200) NOT NULL,
  `attendee1` varchar(200) NOT NULL,
  `attendee2` varchar(200) NOT NULL,
  `attendee3` varchar(200) NOT NULL,
  `summary` text NOT NULL,
  `comment` varchar(8000) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `submitted` tinyint(1) NOT NULL,
  `grpid` int(11) NOT NULL,
  `submitTime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `minmeet`
--

INSERT INTO `minmeet` (`mmid`, `meetdate`, `weekNo`, `taker`, `attendee1`, `attendee2`, `attendee3`, `summary`, `comment`, `approved`, `submitted`, `grpid`, `submitTime`) VALUES
(53, '2024-07-08', 1, 'Alex', 'Aisha', 'Chang Yan Hui', 'Muhammad', 'No one\'s idea of a good time is writing a college essay, I know. But if sitting down to write your essay feels like a chore, and you\'re bored by what you\'re saying, you can imagine how the person reading your essay will feel. On the other hand, if you\'re writing about something you love, something that excites you, something that you\'ve thought deeply about, chances are I\'m going to set down your application feeling excited, too—and feeling like I\'ve gotten to know you.', '', 1, 1, 12, '07/08/2024 2:08 AM'),
(54, '2024-07-10', 2, 'Alex', 'Aisha', '', '', '\"Don\'t bury the lede!\" The first few sentences must capture the reader\'s attention, provide a gist of the story, and give a sense of where the essay is heading. Think about any article you\'ve read—how do you decide to read it? You read the first few sentences and then decide. The same goes for college essays. A strong lede (journalist parlance for \"lead\") will place your reader in the \"accept\" mindset from the beginning of the essay. A weak lede will have your reader thinking \"reject\"—a mindset from which it\'s nearly impossible to recover.', '', 1, 1, 12, '07/08/2024 2:09 AM'),
(55, '2024-07-11', 3, 'Aisha', 'Chang Yan Hui', '', '', 'If you already have, erase them from memory and write the story you want colleges to hear. The truth is, admission reviewers rarely know—or care—which prompt you are responding to. They are curious to discover what you choose to show them about who you are, what you value, and why. Even the most fluid writers are often stifled by fitting their narrative neatly into a category and the essay quickly loses authentic voice. Write freely and choose a prompt later. Spoiler alert...one prompt is \"Share an essay on any topic of your choice. It can be one you\'ve already written, one that responds to a different prompt, or one of your own design. \" So have at it.', '', 1, 1, 12, '07/08/2024 2:09 AM'),
(56, '2024-07-12', 14, 'Muhammad', 'Chang Yan Hui', '', '', 'Adding feelings to your essays can be much more powerful than just listing your achievements. It allows reviewers to connect with you and understand your personality and what drives you. In particular, be open to showing vulnerability. Nobody expects you to be perfect and acknowledging times in which you have felt nervous or scared shows maturity and self-awareness.', '', 0, 1, 12, '07/08/2024 2:09 AM'),
(57, '2024-07-08', 1, 'Arvind', 'Fang Kai Xiang', 'Naj', 'Lily', 'I became desperately devoted to my education because I saw knowledge as the key to freeing myself from the chains of ignorance. While learning about cancer in school I promised myself that I would memorize every fact and absorb every detail in textbooks and online medical journals. And as I began to consider my future, I realized that what I learned in school would allow me to silence that which had silenced my grandmother. However, I was focused not with learning itself, but with good grades and high test scores. I started to believe that academic perfection would be the only way to redeem myself in her eyes--to make up for what I had not done as a granddaughter. ', '', 1, 1, 13, '07/08/2024 2:15 AM'),
(58, '2024-07-09', 10, 'Arvind', 'Fang Kai Xiang', '', '', 'However, a simple walk on a hiking trail behind my house made me open my own eyes to the truth. Over the years, everything--even honoring my grandmother--had become second to school and grades. As my shoes humbly tapped against the Earth, the towering trees blackened by the forest fire a few years ago, the faintly colorful pebbles embedded in the sidewalk, and the wispy white clouds hanging in the sky reminded me of my small though nonetheless significant part in a larger whole that is humankind and this Earth. Before I could resolve my guilt, I had to broaden my perspective of the world as well as my responsibilities to my fellow humans.   ', '', 0, 1, 13, '07/08/2024 2:15 AM');

-- --------------------------------------------------------

--
-- Table structure for table `panel`
--

CREATE TABLE `panel` (
  `panelid` int(11) NOT NULL,
  `name` varchar(8000) NOT NULL,
  `panelnum` text NOT NULL,
  `email` text NOT NULL,
  `pass` varchar(5000) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `verify` tinyint(1) NOT NULL,
  `groupAssign` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `panel`
--

INSERT INTO `panel` (`panelid`, `name`, `panelnum`, `email`, `pass`, `salt`, `verify`, `groupAssign`) VALUES
(1, 'panel1', '12345', 'test1234@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 'G1'),
(3, 'panel2', 'wefwef', 'ewfff', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 'G2'),
(4, 'alex', '123456', 'alex88@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `peermark`
--

CREATE TABLE `peermark` (
  `peermarkid` int(11) NOT NULL,
  `studentid` int(11) NOT NULL,
  `peersvMark` float NOT NULL,
  `peerstuMark` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peermark`
--

INSERT INTO `peermark` (`peermarkid`, `studentid`, `peersvMark`, `peerstuMark`) VALUES
(11, 1, 7.1, 4.5),
(12, 4, 4.8, 4.5),
(13, 10, 1.9, 3.75),
(14, 17, 4.6, 5.09);

-- --------------------------------------------------------

--
-- Table structure for table `peerstudent`
--

CREATE TABLE `peerstudent` (
  `peerid` int(11) NOT NULL,
  `grader` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  `trait1` int(11) NOT NULL,
  `trait2` int(11) NOT NULL,
  `trait4` int(11) NOT NULL,
  `trait5` int(11) NOT NULL,
  `trait6` int(11) NOT NULL,
  `trait7` int(11) NOT NULL,
  `leadtrait` int(11) NOT NULL,
  `grpid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `timeSubmit` varchar(500) NOT NULL,
  `mark` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peerstudent`
--

INSERT INTO `peerstudent` (`peerid`, `grader`, `member`, `trait1`, `trait2`, `trait4`, `trait5`, `trait6`, `trait7`, `leadtrait`, `grpid`, `comment`, `timeSubmit`, `mark`) VALUES
(34, 1, 1, 3, 0, 3, 3, 3, 3, 3, 12, 'Just Me', '07/08/2024 8:43 PM', 4.5),
(35, 1, 4, 3, 3, 3, 3, 3, 3, 0, 12, 'No good', '07/08/2024 8:43 PM', 4.5),
(36, 1, 10, 3, 3, 3, 3, 3, 3, 0, 12, 'Average', '07/08/2024 8:43 PM', 4.5),
(37, 1, 17, 3, 3, 3, 3, 3, 3, 0, 12, 'Bad', '07/08/2024 8:43 PM', 4.5),
(38, 4, 1, 3, 0, 3, 3, 3, 3, 3, 12, 'Yes', '07/08/2024 8:44 PM', 4.5),
(39, 4, 4, 3, 3, 3, 3, 3, 3, 0, 12, '', '07/08/2024 8:44 PM', 4.5),
(40, 4, 10, 3, 3, 3, 3, 3, 3, 0, 12, '', '07/08/2024 8:44 PM', 4.5),
(41, 4, 17, 3, 3, 3, 3, 3, 3, 0, 12, '', '07/08/2024 8:44 PM', 4.5),
(42, 10, 1, 3, 0, 3, 3, 3, 3, 3, 12, 'Best', '07/08/2024 8:45 PM', 4.5),
(43, 10, 4, 3, 3, 3, 3, 3, 3, 0, 12, '', '07/08/2024 8:45 PM', 4.5),
(44, 10, 10, 3, 3, 3, 3, 3, 3, 0, 12, '', '07/08/2024 8:45 PM', 4.5),
(45, 10, 17, 3, 3, 3, 3, 3, 3, 0, 12, '', '07/08/2024 8:45 PM', 4.5),
(46, 17, 1, 3, 0, 3, 3, 3, 3, 3, 12, 'No1', '07/08/2024 8:56 PM', 4.5),
(47, 17, 4, 3, 3, 3, 3, 3, 3, 0, 12, '', '07/08/2024 8:56 PM', 4.5),
(48, 17, 10, 1, 1, 1, 1, 1, 1, 0, 12, '', '07/08/2024 8:56 PM', 1.5),
(49, 17, 17, 5, 5, 5, 5, 3, 3, 0, 12, '', '07/08/2024 8:57 PM', 6.86);

-- --------------------------------------------------------

--
-- Table structure for table `peersv`
--

CREATE TABLE `peersv` (
  `peersvid` int(11) NOT NULL,
  `svid` int(11) NOT NULL,
  `studentid` int(11) NOT NULL,
  `trait1` int(11) NOT NULL,
  `trait2` int(11) NOT NULL,
  `trait3` int(11) NOT NULL,
  `trait4` int(11) NOT NULL,
  `trait5` int(11) NOT NULL,
  `leadtrait` int(11) NOT NULL,
  `grpid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `timeSubmit` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peersv`
--

INSERT INTO `peersv` (`peersvid`, `svid`, `studentid`, `trait1`, `trait2`, `trait3`, `trait4`, `trait5`, `leadtrait`, `grpid`, `comment`, `timeSubmit`) VALUES
(10, 1, 1, 0, 4, 5, 5, 4, 5, 12, 'good', '07/08/2024 9:16 PM'),
(11, 1, 4, 4, 3, 4, 2, 1, 0, 12, 'Okay', '07/08/2024 9:16 PM'),
(12, 1, 10, 1, 2, 1, 2, 1, 0, 12, 'Haiya', '07/08/2024 9:16 PM'),
(13, 1, 17, 4, 3, 2, 3, 1, 0, 12, 'Average', '07/08/2024 9:16 PM');

-- --------------------------------------------------------

--
-- Table structure for table `proposalmark`
--

CREATE TABLE `proposalmark` (
  `proposalid` int(11) NOT NULL,
  `grpid` int(11) NOT NULL,
  `trait1` int(11) NOT NULL,
  `trait2` int(11) NOT NULL,
  `trait3` int(11) NOT NULL,
  `trait4` int(11) NOT NULL,
  `submitTime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposalmark`
--

INSERT INTO `proposalmark` (`proposalid`, `grpid`, `trait1`, `trait2`, `trait3`, `trait4`, `submitTime`) VALUES
(5, 12, 4, 3, 4, 3, '07/08/2024 11:23 PM'),
(6, 13, 4, 4, 4, 3, '07/08/2024 11:24 PM');

-- --------------------------------------------------------

--
-- Table structure for table `reporteval`
--

CREATE TABLE `reporteval` (
  `reportevalid` int(11) NOT NULL,
  `svid` int(11) NOT NULL,
  `grpid` int(11) NOT NULL,
  `trait1` int(11) NOT NULL,
  `trait2` int(11) NOT NULL,
  `trait3` int(11) NOT NULL,
  `trait4` int(11) NOT NULL,
  `trait5` int(11) NOT NULL,
  `trait6` int(11) NOT NULL,
  `trait7` int(11) NOT NULL,
  `trait8` int(11) NOT NULL,
  `comment` text NOT NULL,
  `submitTime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reporteval`
--

INSERT INTO `reporteval` (`reportevalid`, `svid`, `grpid`, `trait1`, `trait2`, `trait3`, `trait4`, `trait5`, `trait6`, `trait7`, `trait8`, `comment`, `submitTime`) VALUES
(18, 1, 12, 4, 4, 4, 4, 4, 4, 4, 4, '', '07/08/2024 11:22 PM'),
(19, 1, 13, 4, 5, 4, 5, 4, 5, 4, 5, '', '07/08/2024 11:22 PM'),
(20, 2, 12, 4, 5, 4, 5, 4, 4, 5, 4, 'good', '07/08/2024 11:24 PM'),
(21, 2, 13, 2, 4, 2, 2, 2, 3, 2, 2, 'average', '07/08/2024 11:24 PM');

-- --------------------------------------------------------

--
-- Table structure for table `rubricfile`
--

CREATE TABLE `rubricfile` (
  `fileid` int(11) NOT NULL,
  `filename` text NOT NULL,
  `filesize` int(200) NOT NULL,
  `filetype` varchar(100) NOT NULL,
  `submitTime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rubricfile`
--

INSERT INTO `rubricfile` (`fileid`, `filename`, `filesize`, `filetype`, `submitTime`) VALUES
(14, 'rubric_peerstudent.pdf', 109980, 'application/pdf', '06/15/2024 10:34 PM'),
(15, 'rubric_report.pdf', 109980, 'application/pdf', '06/15/2024 10:35 PM'),
(16, 'rubric_minute.pdf', 109980, 'application/pdf', '06/15/2024 10:35 PM'),
(17, 'rubric_poster.pdf', 109980, 'application/pdf', '06/15/2024 10:35 PM'),
(18, 'rubric_idpex.pdf', 109980, 'application/pdf', '06/15/2024 10:35 PM'),
(19, 'rubric_peersv.pdf', 109980, 'application/pdf', '06/15/2024 10:35 PM'),
(20, 'rubric_proposal.pdf', 94177, 'application/pdf', '06/24/2024 5:14 PM');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `ic` varchar(200) NOT NULL,
  `matric` varchar(200) NOT NULL,
  `prog` varchar(25) NOT NULL,
  `race` varchar(20) NOT NULL,
  `gender` text NOT NULL,
  `cgpa` float NOT NULL,
  `phnum` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `verify` tinyint(1) NOT NULL,
  `grpid` int(11) DEFAULT NULL,
  `leader` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentid`, `name`, `ic`, `matric`, `prog`, `race`, `gender`, `cgpa`, `phnum`, `email`, `pass`, `salt`, `verify`, `grpid`, `leader`) VALUES
(1, 'Alex', '320620-40-7616', '221083063', 'UR6523002', 'International', 'Male', 3.25, '0124648933', 'alex@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 12, 1),
(2, 'Abdul', '271101-22-7194', '221024608', 'UR6523002', 'MELAY', 'MALE', 3.66, '0123245667', 'abdul@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(3, 'Ahmad', '420911-46-6495', '221057425', 'UR6523002', 'MELAY', 'Male', 3.55, '0165659586', 'ahmad@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(4, 'Aisha', '210119-45-4334', '221024693', 'RK22', 'MELAY', 'FEMALE', 4, '0173215645', 'aisha@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 12, 0),
(5, 'Firdaus', '941111-06-4175', '221063736', 'RK50', 'MALAY', 'MALE', 3.56, '014566465', 'firdaus@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(6, 'Fatimah', '310811-21-4160', '221045005', 'UR6523002', 'MELAY', 'FEMALE', 2.53, '0145646413', 'fatimah@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(7, 'Vihaan', '201124-37-1517', '221074297', 'UR6523002', 'INDIAN', 'MALE', 3.98, '015646545', 'vihaan@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(8, 'Arvind', '781201-16-9127', '221086144', 'UR6523002', 'INDIAN', 'MALE', 4, '0135664663', 'arvind@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 13, 1),
(9, 'Fang Kai Xiang', '120117-14-7433', '221022000', 'UR6523002', 'CINA', 'MALE', 3, '0123456789', 'fang@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 13, 0),
(10, 'Chang Yan Hui', '831014-25-3109', '221022001', 'UR6523002', 'CINA', 'MALE', 2.36, '0123456788', 'chang@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 12, 0),
(11, 'Redny Neysha', '520204-54-5620', '221022002', 'UR6523002', 'IBAN', 'FEMALE', 3.99, '0123456787', 'redny@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(12, 'Iman Daniel', '110906-50-8607', '221083063', 'UR6523002', 'MELAY', 'MALE', 3.85, '0123456786', 'iman@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(13, 'Naj', '161002-05-5017', '221006301', 'RK50', 'INDIAN', 'MALE', 2, '0123215645', 'naj@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 13, 0),
(14, 'Nafisha', '590710-11-9588', '221022206', 'RK22', 'MELAY', 'FEMALE', 1.53, '0123456786', 'nafisha@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(15, 'Tan Yu Zhe', '740301-52-4903', '221022007', 'UR6523002', 'IBAN', 'MALE', 2.6, '0125445665', 'tan@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(16, 'Au Wai Lik', '040419-57-9557', '221022010', 'UR6523002', 'CINA', 'MALE', 2.51, '0123456782', 'au@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(17, 'Muhammad', '780822-30-8077', '221022011', 'RK50', 'MELAY', 'MALE', 3.69, '01234567814', 'muham@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 12, 0),
(18, 'Tharvin', '750104-29-1405', '221022201', 'UR6523002', 'INDIAN', 'MALE', 4, '0123456789', 'tharvin@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(19, 'Raja', '110504-52-7053', '221022020', 'RK50', 'MELAY', 'MALE', 2.86, '0123456781', 'raja@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(20, 'girl UUM', '000000-00-0200', '221022220', 'UR6523002', 'MALAYSIA', 'FEMALE', 4, '0123456786', 'girlUnveri@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 0, NULL, 0),
(21, 'Devi', '840105-10-8758', '221099414', 'UR6523002', 'INDIAN', 'FEMALE', 1.35, '0135645894', 'devi@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(22, 'Adam', '461229-21-4573', '221018542', 'RK50', 'INTERNATIONAL', 'Male', 1.24, '0123456777', 'adam@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0),
(23, 'Lily', '460305-55-3970', '221065462', 'RK22', 'INTERNATIONAL', 'FEMALE', 3.69, '0123215677', 'lily@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 13, 0),
(32, 'test', '010129086897', '221022223', 'UR6523002', 'Chinese', 'Male', 3.62, '0125019191', 'test@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 0, NULL, 0),
(33, 'AlexFtest', '01010101010101', '123123', '122131', 'Malay', 'Male', 3.62, '0125019191', 'kxfan1@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `supervisor`
--

CREATE TABLE `supervisor` (
  `svid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `staffnum` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `verify` tinyint(1) NOT NULL,
  `grpid` int(11) DEFAULT NULL,
  `y3` int(11) NOT NULL,
  `y2` int(11) NOT NULL,
  `y1` int(11) NOT NULL,
  `y0` int(11) NOT NULL,
  `evalid` int(11) DEFAULT NULL,
  `groupAssign` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisor`
--

INSERT INTO `supervisor` (`svid`, `name`, `staffnum`, `email`, `pass`, `salt`, `verify`, `grpid`, `y3`, `y2`, `y1`, `y0`, `evalid`, `groupAssign`) VALUES
(1, 'DR NOBODYs', 'SV001', 'nobody@gmail.comm', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 12, 2, 4, 3, 1, 13, 'G2'),
(2, 'Dr Hasneeza', 'SV01', 'drhasneeza@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, 13, 3, 4, 15, 1, 12, 'G1'),
(3, 'Dr Hafizah', 'SV02', 'drhafizah@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 4, 5, 6, 2, NULL, 'G3G4'),
(4, 'ASSOC. PROF. DR. YUFRIDIN BIN WAHAB', 'SV03', 'dryufridin@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 3, 2, 0, 1, NULL, ''),
(5, 'ASSOC. PROF. DR. ASRAL BIN BAHARI JAMBEK', 'SV04', 'drasral@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 6, 1, 0, 2, NULL, ''),
(6, 'ASSOC. PROF. DR. SHAHRIR RIZAL BIN KASJOO', 'SV05', 'drshahrir@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 1, 3, 0, 5, NULL, ''),
(7, 'DR. SHAIFUL NIZAM BIN MOHYAR', 'SV05', 'drshaiful@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 3, 2, 0, 6, NULL, ''),
(8, 'DR. SANNA BINTI TAKING', 'SV06', 'drsanna@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 1, 0, 0, 2, NULL, ''),
(9, 'DR ILMAN', 'SV07', 'drilman@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 2, 0, 0, 4, NULL, ''),
(10, 'DR HAYATI', 'SV08', 'drhayati@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 4, 0, 0, 5, NULL, ''),
(11, 'DR RAZEL', 'SV09', 'drrazel@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 4, 0, 0, 0, NULL, ''),
(12, 'DR FAZREEN', 'SV10', 'drfazreen@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0, 0, 0, 0, NULL, ''),
(13, 'ASSOC TS DR ZURAIDAH', 'SV11', 'drzuraidah@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0, 0, 0, 0, NULL, ''),
(15, 'DRnobody', '10010', 'drnobody2@gmail.com', '$2y$10$/DvurGsbAwlcXW9mHowuReetqRBNemBB2p5KgJLNHqI7UxCnm7ugq', '39c2bcde8db1ceff06e54b48d47a1c10', 1, NULL, 0, 0, 0, 0, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE `theme` (
  `themeid` int(11) NOT NULL,
  `title` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theme`
--

INSERT INTO `theme` (`themeid`, `title`) VALUES
(1, 'Website Development'),
(2, 'Medical Application'),
(3, 'Agricultural Application'),
(4, 'Green Technology Application'),
(5, 'Military Application'),
(6, 'Smart City'),
(15, 'haha');

-- --------------------------------------------------------

--
-- Table structure for table `uploadedexceldata`
--

CREATE TABLE `uploadedexceldata` (
  `studentid` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `ic` varchar(20) NOT NULL,
  `matric` varchar(20) NOT NULL,
  `prog` varchar(50) NOT NULL,
  `race` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `cgpa` float NOT NULL,
  `phnum` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videolink`
--

CREATE TABLE `videolink` (
  `vidid` int(11) NOT NULL,
  `videolink` text NOT NULL,
  `submitted` tinyint(1) NOT NULL,
  `grpid` int(11) NOT NULL,
  `submitTime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videolink`
--

INSERT INTO `videolink` (`vidid`, `videolink`, `submitted`, `grpid`, `submitTime`) VALUES
(5, 'https://www.youtube.com/watch?v=IvUf4Zgtb2w', 1, 13, '07/08/2024 2:17 AM'),
(6, 'https://www.youtube.com/watch?v=yAxAOGgV04g', 1, 12, '07/08/2024 2:22 AM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `committee`
--
ALTER TABLE `committee`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `context`
--
ALTER TABLE `context`
  ADD PRIMARY KEY (`contid`);

--
-- Indexes for table `duedate`
--
ALTER TABLE `duedate`
  ADD PRIMARY KEY (`dueid`),
  ADD KEY `contid` (`contid`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventid`);

--
-- Indexes for table `grpmark`
--
ALTER TABLE `grpmark`
  ADD PRIMARY KEY (`grpmarkid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `idpexeval`
--
ALTER TABLE `idpexeval`
  ADD PRIMARY KEY (`idpexevalid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `idpfile`
--
ALTER TABLE `idpfile`
  ADD PRIMARY KEY (`fileid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `idpgroup`
--
ALTER TABLE `idpgroup`
  ADD PRIMARY KEY (`grpid`),
  ADD KEY `themeid` (`themeid`);

--
-- Indexes for table `idpsynopsis`
--
ALTER TABLE `idpsynopsis`
  ADD PRIMARY KEY (`synid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `minmeet`
--
ALTER TABLE `minmeet`
  ADD PRIMARY KEY (`mmid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `panel`
--
ALTER TABLE `panel`
  ADD PRIMARY KEY (`panelid`);

--
-- Indexes for table `peermark`
--
ALTER TABLE `peermark`
  ADD PRIMARY KEY (`peermarkid`),
  ADD KEY `studentid` (`studentid`);

--
-- Indexes for table `peerstudent`
--
ALTER TABLE `peerstudent`
  ADD PRIMARY KEY (`peerid`),
  ADD KEY `grader` (`grader`,`member`,`grpid`),
  ADD KEY `member` (`member`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `peersv`
--
ALTER TABLE `peersv`
  ADD PRIMARY KEY (`peersvid`),
  ADD KEY `svid` (`svid`,`studentid`),
  ADD KEY `studentid` (`studentid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `proposalmark`
--
ALTER TABLE `proposalmark`
  ADD PRIMARY KEY (`proposalid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `reporteval`
--
ALTER TABLE `reporteval`
  ADD PRIMARY KEY (`reportevalid`),
  ADD KEY `svid` (`svid`,`grpid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `rubricfile`
--
ALTER TABLE `rubricfile`
  ADD PRIMARY KEY (`fileid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentid`),
  ADD KEY `groupid` (`grpid`);

--
-- Indexes for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD PRIMARY KEY (`svid`),
  ADD KEY `groupid` (`grpid`);

--
-- Indexes for table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`themeid`);

--
-- Indexes for table `videolink`
--
ALTER TABLE `videolink`
  ADD PRIMARY KEY (`vidid`),
  ADD KEY `grpid` (`grpid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `committee`
--
ALTER TABLE `committee`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `context`
--
ALTER TABLE `context`
  MODIFY `contid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `duedate`
--
ALTER TABLE `duedate`
  MODIFY `dueid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `eventid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `grpmark`
--
ALTER TABLE `grpmark`
  MODIFY `grpmarkid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `idpexeval`
--
ALTER TABLE `idpexeval`
  MODIFY `idpexevalid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `idpfile`
--
ALTER TABLE `idpfile`
  MODIFY `fileid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `idpgroup`
--
ALTER TABLE `idpgroup`
  MODIFY `grpid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `idpsynopsis`
--
ALTER TABLE `idpsynopsis`
  MODIFY `synid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `minmeet`
--
ALTER TABLE `minmeet`
  MODIFY `mmid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `panel`
--
ALTER TABLE `panel`
  MODIFY `panelid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `peermark`
--
ALTER TABLE `peermark`
  MODIFY `peermarkid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `peerstudent`
--
ALTER TABLE `peerstudent`
  MODIFY `peerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `peersv`
--
ALTER TABLE `peersv`
  MODIFY `peersvid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `proposalmark`
--
ALTER TABLE `proposalmark`
  MODIFY `proposalid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reporteval`
--
ALTER TABLE `reporteval`
  MODIFY `reportevalid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `rubricfile`
--
ALTER TABLE `rubricfile`
  MODIFY `fileid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `studentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `supervisor`
--
ALTER TABLE `supervisor`
  MODIFY `svid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `theme`
--
ALTER TABLE `theme`
  MODIFY `themeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `videolink`
--
ALTER TABLE `videolink`
  MODIFY `vidid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `duedate`
--
ALTER TABLE `duedate`
  ADD CONSTRAINT `duedate_ibfk_1` FOREIGN KEY (`contid`) REFERENCES `context` (`contid`);

--
-- Constraints for table `grpmark`
--
ALTER TABLE `grpmark`
  ADD CONSTRAINT `grpmark_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `idpexeval`
--
ALTER TABLE `idpexeval`
  ADD CONSTRAINT `idpexeval_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `idpfile`
--
ALTER TABLE `idpfile`
  ADD CONSTRAINT `idpfile_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `idpgroup`
--
ALTER TABLE `idpgroup`
  ADD CONSTRAINT `idpgroup_ibfk_1` FOREIGN KEY (`themeid`) REFERENCES `theme` (`themeid`);

--
-- Constraints for table `idpsynopsis`
--
ALTER TABLE `idpsynopsis`
  ADD CONSTRAINT `idpsynopsis_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `minmeet`
--
ALTER TABLE `minmeet`
  ADD CONSTRAINT `minmeet_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `peermark`
--
ALTER TABLE `peermark`
  ADD CONSTRAINT `peermark_ibfk_1` FOREIGN KEY (`studentid`) REFERENCES `student` (`studentid`);

--
-- Constraints for table `peerstudent`
--
ALTER TABLE `peerstudent`
  ADD CONSTRAINT `peerstudent_ibfk_1` FOREIGN KEY (`grader`) REFERENCES `student` (`studentid`),
  ADD CONSTRAINT `peerstudent_ibfk_2` FOREIGN KEY (`member`) REFERENCES `student` (`studentid`),
  ADD CONSTRAINT `peerstudent_ibfk_3` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `peersv`
--
ALTER TABLE `peersv`
  ADD CONSTRAINT `peersv_ibfk_1` FOREIGN KEY (`svid`) REFERENCES `supervisor` (`svid`),
  ADD CONSTRAINT `peersv_ibfk_2` FOREIGN KEY (`studentid`) REFERENCES `student` (`studentid`),
  ADD CONSTRAINT `peersv_ibfk_3` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `proposalmark`
--
ALTER TABLE `proposalmark`
  ADD CONSTRAINT `proposalmark_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `reporteval`
--
ALTER TABLE `reporteval`
  ADD CONSTRAINT `reporteval_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`),
  ADD CONSTRAINT `reporteval_ibfk_2` FOREIGN KEY (`svid`) REFERENCES `supervisor` (`svid`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD CONSTRAINT `supervisor_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);

--
-- Constraints for table `videolink`
--
ALTER TABLE `videolink`
  ADD CONSTRAINT `videolink_ibfk_1` FOREIGN KEY (`grpid`) REFERENCES `idpgroup` (`grpid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
