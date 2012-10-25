-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 25, 2012 at 10:22 AM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`imageID`, `imageSource`, `thumbSource`, `userID`) VALUES
(18, 'images/Desert.jpg', 'images/thumb_Desert.jpg', 9),
(19, 'images/Koala.jpg', 'images/thumb_Koala.jpg', 1),
(20, 'images/Penguins.jpg', 'images/thumb_Penguins.jpg', 15),
(21, 'images/Tulips.jpg', 'images/thumb_Tulips.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `questionID` int(11) NOT NULL AUTO_INCREMENT,
  `quizID` int(11) NOT NULL,
  `content` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `alt1` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `altX` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `alt2` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `correct` varchar(4) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`questionID`),
  KEY `quizID` (`quizID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=24 ;

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
(20, 12, 'missar man quiz miss i diss?', 'missiliss', 'inismiais', 'soidfjuu', 'alt2'),
(21, 13, 'hur långt kan en potatis rulla?', 'ganska långt', 'potatis rullar inte, de hoppar', '3m', 'alt2'),
(22, 13, 'hur mycket väger en potatis?', 'som en halv melon', 'som en hel fjäder', 'precis så mycket som den ska väga', 'alt1'),
(23, 13, 'varför kan man inte se en potatis när det är mörkt?', 'de är så skygga', 'du har dålig syn', 'potatisar är egentligen kameleonter', 'altX');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quizID`, `quizName`, `created`, `userID`, `description`) VALUES
(1, 'grodquiz', '2012-10-18 18:00:00', 1, 'detta quiz handlar om de fantastiska djur vi kallar grodor'),
(2, 'toffelquiz', '2012-10-18 19:00:00', 2, 'till skillnad från vad många tror handlar inte toffelquiz om tofflor utan om skor.'),
(8, 'pajquiz', '2012-10-22 19:32:17', 1, 'handlar om pajer och gott'),
(9, 'apquiz', '2012-10-22 19:38:52', 2, 'handlar om apor'),
(10, 'toppquiz', '2012-10-22 22:13:15', 3, 'handlar om hur det är att vara på toppen'),
(12, 'missquiz', '2012-10-23 19:18:29', 3, 'quiz där man får rätt om man svarar fel.'),
(13, 'potatisquiz', '2012-10-24 23:04:12', 2, 'vad vore livet utan potatis?');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=97 ;

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
(64, 3, 20, 'alt1', 231012172937),
(65, 2, 1, 'alt1', 241012202339),
(66, 2, 2, 'altX', 241012202339),
(67, 2, 3, 'alt2', 241012202339),
(68, 2, 4, 'altX', 241012202339),
(69, 2, 5, 'altX', 241012202339),
(70, 2, 15, 'altX', 241012203159),
(71, 2, 16, 'alt2', 241012203159),
(72, 2, 17, 'alt2', 241012203159),
(73, 2, 18, 'alt2', 241012203303),
(76, 2, 1, 'altX', 241012203818),
(77, 2, 2, 'alt1', 241012203818),
(78, 2, 3, 'alt1', 241012203818),
(79, 2, 4, 'alt2', 241012203818),
(80, 2, 5, 'alt2', 241012203818),
(93, 3, 18, 'alt1', 241012211033),
(94, 15, 21, 'alt2', 251012100837),
(95, 15, 22, 'alt1', 251012100837),
(96, 15, 23, 'altX', 251012100837);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `password` char(128) COLLATE utf8_swedish_ci NOT NULL,
  `joined` date NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `userName`, `password`, `joined`, `admin`) VALUES
(1, 'pelle', '973ac87ce5bda621670536c654826a4f6f2a7524de1e9ffc0ec1f49a2aa5a20c73ecdcad547106263603f91eef65ceb4a4fb7413ef71e5ca7fa71478dbe4b608', '2012-10-19', 1),
(2, 'olle', '2aebf0c916ee79d2b6641a820489663bffc6c8fd68270b5fee4994df2f5fdf75978a75420707eb06c52f081c5bea7de9c783d6459efa1688c17cdb078cd83ca4', '2012-10-20', 1),
(3, 'tinke', 'bc188243cddae989e13cbc625f75e44701d4f47bf3bb8f3f876b448986866798aca952be7fa7d5466700445a2ff057614cbfe0ed2af24b5a9f4f9d374f568151', '2012-10-22', 1),
(15, 'peppe', '40d42b79f032513019edef9c7a29320614ca58abdc05140d1dd88bf7a6cec7a113c4adf8fd66dc1cffc2f556d8c6c1ed11733ad7fe8e0d3a53b777a51b37ade2', '2012-10-24', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
