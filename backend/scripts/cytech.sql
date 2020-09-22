-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2020 at 03:21 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cytech`
--
CREATE DATABASE IF NOT EXISTS `cytech` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cytech`;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `UserID` int(10) UNSIGNED NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `TravelDateStart` date NOT NULL,
  `TravelDateEnd` date NOT NULL,
  `TravelReason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `FirstName`, `LastName`, `Email`, `TravelDateStart`, `TravelDateEnd`, `TravelReason`) VALUES
(6, 'Stavros', 'Vagionitis', 'stavros.vagionitis@gmail.com', '2020-09-20', '2020-09-26', 'Alice was beginning to get very tired of sitting by her sister on the\r\nbank, and of having nothing to do: once or twice she had peeped into\r\nthe book her sister was reading, but it had no pictures or\r\nconversations in it, “and what is the use of a book,” thought Alice\r\n“without pictures or conversations?”'),
(7, 'Manousos', 'Aligizakis', 'manousos.aligizakis@heraklion.gr', '2020-09-01', '2020-09-05', 'So she was considering in her own mind (as well as she could, for the\r\nhot day made her feel very sleepy and stupid), whether the pleasure of\r\nmaking a daisy-chain would be worth the trouble of getting up and\r\npicking the daisies, when suddenly a White Rabbit with pink eyes ran\r\nclose by her.'),
(8, 'Manolis', 'Aligizakis', 'manolis.aligizakis@hania.net', '2020-09-24', '2020-09-30', 'There was nothing so _very_ remarkable in that; nor did Alice think it\r\nso _very_ much out of the way to hear the Rabbit say to itself, “Oh\r\ndear! Oh dear! I shall be late!” (when she thought it over afterwards,\r\nit occurred to her that she ought to have wondered at this, but at the\r\ntime it all seemed quite natural); but when the Rabbit actually _took a\r\nwatch out of its waistcoat-pocket_, and looked at it, and then hurried\r\non, Alice started to her feet, for it flashed across her mind that she\r\nhad never before seen a rabbit with either a waistcoat-pocket, or a\r\nwatch to take out of it, and burning with curiosity, she ran across the\r\nfield after it, and fortunately was just in time to see it pop down a\r\nlarge rabbit-hole under the hedge.'),
(9, 'Manousos', 'Arhontakis', 'manousos.arhontakis@rethymno.gr', '2020-09-08', '2020-09-12', 'In another moment down went Alice after it, never once considering how\r\nin the world she was to get out again.\r\n\r\nThe rabbit-hole went straight on like a tunnel for some way, and then\r\ndipped suddenly down, so suddenly that Alice had not a moment to think\r\nabout stopping herself before she found herself falling down a very\r\ndeep well.\r\n\r\nEither the well was very deep, or she fell very slowly, for she had\r\nplenty of time as she went down to look about her and to wonder what\r\nwas going to happen next. First, she tried to look down and make out\r\nwhat she was coming to, but it was too dark to see anything; then she\r\nlooked at the sides of the well, and noticed that they were filled with\r\ncupboards and book-shelves; here and there she saw maps and pictures\r\nhung upon pegs. She took down a jar from one of the shelves as she\r\npassed; it was labelled “ORANGE MARMALADE”, but to her great\r\ndisappointment it was empty: she did not like to drop the jar for fear\r\nof killing somebody underneath, so managed to put it into one of the\r\ncupboards as she fell past it.'),
(10, 'Nikos', 'Alifierakis', 'nikos.alifierakis@sitia.net', '2020-09-15', '2020-09-18', '“Well!” thought Alice to herself, “after such a fall as this, I shall\r\nthink nothing of tumbling down stairs! How brave they’ll all think me\r\nat home! Why, I wouldn’t say anything about it, even if I fell off the\r\ntop of the house!” (Which was very likely true.)\r\n\r\nDown, down, down. Would the fall _never_ come to an end? “I wonder how\r\nmany miles I’ve fallen by this time?” she said aloud. “I must be\r\ngetting somewhere near the centre of the earth. Let me see: that would\r\nbe four thousand miles down, I think—” (for, you see, Alice had learnt\r\nseveral things of this sort in her lessons in the schoolroom, and\r\nthough this was not a _very_ good opportunity for showing off her\r\nknowledge, as there was no one to listen to her, still it was good\r\npractice to say it over) “—yes, that’s about the right distance—but\r\nthen I wonder what Latitude or Longitude I’ve got to?” (Alice had no\r\nidea what Latitude was, or Longitude either, but thought they were nice\r\ngrand words to say.)'),
(12, 'Pantelis', 'Thalassinos', 'pantelis.thalassinos@kriti.tv', '2020-09-21', '2020-09-27', 'Mpla Mpla Mpla Mplou Mplou Mplou');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`) USING BTREE;
ALTER TABLE `user` ADD FULLTEXT KEY `FirstName` (`FirstName`);
ALTER TABLE `user` ADD FULLTEXT KEY `LastName` (`LastName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;


--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata for table user
--

--
-- Metadata for database cytech
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
