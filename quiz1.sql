-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 24, 2012 at 02:04 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quiz1`
--

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `imageID` int(11) NOT NULL AUTO_INCREMENT,
  `imageSource` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `thumbSource` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`imageID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`imageID`, `imageSource`, `thumbSource`, `userID`) VALUES
(14, 'images/Koala.jpg', 'images/thumb_Koala.jpg', 1),
(15, 'images/Chrysanthemum.jpg', 'images/thumb_Chrysanthemum.jpg', 10),
(17, 'images/Jellyfish.jpg', 'images/thumb_Jellyfish.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `questionID` int(11) NOT NULL AUTO_INCREMENT,
  `quizID` int(11) NOT NULL,
  `content` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `alt1` varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  `altX` varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  `alt2` varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  `correct` varchar(4) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`questionID`),
  KEY `quizID` (`quizID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`questionID`, `quizID`, `content`, `alt1`, `altX`, `alt2`, `correct`) VALUES
(1, 1, 'hur snygg kan en groda bli?', 'alltid ful', 'ganska snygg', 'som en prins', 'alt2'),
(2, 1, 'vad heter grodan på latin?', 'grodis hoppidus', 'grönis fjantilus', 'typus som paddius', 'altX'),
(3, 1, 'är du en groda?', 'ja', 'nej', 'alla är vi grodor innerst inne', 'alt2'),
(4, 1, 'vilken färg har grodan?', 'beror på', 'beror inte på', 'grön', 'alt1'),
(5, 1, 'grodans bästa mat?', 'spaggethi', 'spuggothi', 'en liten, liten falafel', 'altX'),
(6, 2, 'vem har största skon?', 'jätten', 'en kille med stora fötter', 'din mamma', 'alt2'),
(7, 2, 'vad gör man skor av?', 'allt möjligt', 'allt omöjligt', 'skor växer på träd', 'alt1'),
(8, 2, 'vad bli sko baklänges?', 'osk', 'oks', 'strumpa', 'altX'),
(9, 2, 'vilken sko tar man på först?', 'höger', 'vänster', 'jag har inte råd med skor', 'alt1'),
(10, 2, 'hur urskiljer man en sko från en stövel?', 'det står stövel på skon', 'det står sko på stöveln', 'man frågar sin ömma moder', 'alt2'),
(13, 8, 'vad är ett paj?', 'ett saj', 'ett laj', 'ett kaj', 'alt2'),
(14, 8, 'vem är ett paj?', 'ett daj', 'ett maj', 'ett tjaj', 'alt2'),
(15, 9, 'apa?', 'lapa', 'dapa', 'sapa', 'alt1'),
(16, 9, 'mapa', 'gapa', 'rapa', 'napa', 'alt2'),
(17, 9, 'vapa', 'snapa', 'knapa', 'drapa', 'altX'),
(18, 10, 'hur är det att vara på toppen', 'toppen', 'koppen', 'sjoppen', 'alt1'),
(20, 12, 'missar man quiz miss i diss?', 'missiliss', 'inismiais', 'soidfjuu', 'alt2');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
  `quizID` int(11) NOT NULL AUTO_INCREMENT,
  `quizName` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  `userID` int(11) NOT NULL,
  `description` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`quizID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quizID`, `quizName`, `created`, `userID`, `description`) VALUES
(1, 'grodquiz', '2012-10-18 18:00:00', 1, 'detta quiz handlar om de fantastiska djur vi kallar grodor'),
(2, 'toffelquiz', '2012-10-18 19:00:00', 2, 'till skillnad från vad många tror handlar inte toffelquiz om tofflor utan om skor.'),
(8, 'pajquiz', '2012-10-22 19:32:17', 1, 'handlar om pajer och gott'),
(9, 'apquiz', '2012-10-22 19:38:52', 2, 'handlar om apor'),
(10, 'toppquiz', '2012-10-22 22:13:15', 3, 'handlar om hur det är att vara på toppen'),
(12, 'missquiz', '2012-10-23 19:18:29', 3, 'quiz där man får rätt om man svarar fel.');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `resultID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `answer` varchar(4) COLLATE utf8_swedish_ci NOT NULL,
  `start` bigint(12) NOT NULL,
  PRIMARY KEY (`resultID`),
  KEY `userID` (`userID`,`questionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=65 ;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`resultID`, `userID`, `questionID`, `answer`, `start`) VALUES
(36, 1, 1, 'altX', 201012002042),
(37, 1, 2, 'altX', 201012002042),
(38, 1, 3, 'altX', 201012002042),
(39, 1, 4, 'alt2', 201012002042),
(40, 1, 5, 'alt1', 201012002042),
(41, 1, 1, 'alt2', 201012003222),
(42, 1, 2, 'altX', 201012003222),
(43, 1, 3, 'alt2', 201012003222),
(44, 1, 4, 'alt1', 201012003222),
(45, 1, 5, 'altX', 201012003222),
(46, 1, 6, 'alt1', 211012125842),
(47, 1, 7, 'alt2', 211012125842),
(48, 1, 8, 'altX', 211012125842),
(49, 1, 9, 'alt2', 211012125842),
(50, 1, 10, 'alt2', 211012125842),
(51, 1, 15, 'alt1', 231012124001),
(57, 1, 16, 'altX', 231012124001),
(58, 1, 17, 'altX', 231012124001),
(59, 2, 13, 'altX', 231012130009),
(60, 2, 14, 'alt1', 231012130009),
(61, 10, 18, 'alt1', 231012133323),
(62, 10, 13, 'altX', 231012171234),
(63, 10, 14, 'altX', 231012171234),
(64, 3, 20, 'alt1', 231012172937);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `password` char(128) COLLATE utf8_swedish_ci NOT NULL,
  `joined` date NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `userName`, `password`, `joined`) VALUES
(1, 'pelle', 'polle', '2012-10-19'),
(2, 'olle', 'dolle', '2012-10-20'),
(3, 'tinke', 'dinke', '2012-10-22'),
(9, 'pette', 'mette', '2012-10-22'),
(10, 'melle', 'molle', '2012-10-23');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
