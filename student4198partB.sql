-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: webpagesdb.it.auth.gr:3306
-- Χρόνος δημιουργίας: 15 Δεκ 2024 στις 15:17:40
-- Έκδοση διακομιστή: 10.1.48-MariaDB-0ubuntu0.18.04.1
-- Έκδοση PHP: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Διαγραφή χρήστη αν υπάρχει
DROP USER IF EXISTS 'profileUSER'@'localhost';

-- Δημιουργία νέου χρήστη
CREATE USER 'profileUSER'@'localhost' IDENTIFIED BY 'Testing1001@';

-- Δημιουργία της βάσης δεδομένων (αν δεν υπάρχει)
CREATE DATABASE IF NOT EXISTS student4198partB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Ανάθεση δικαιωμάτων στον χρήστη για τη βάση δεδομένων
GRANT ALL PRIVILEGES ON student4198partB.* TO 'profileUSER'@'localhost';

-- Εφαρμογή αλλαγών
FLUSH PRIVILEGES;
-- Επιλογή της βάσης δεδομένων
USE student4198partB;
--
-- Δομή πίνακα για τον πίνακα `Anakainoseis`
--

CREATE TABLE `Anakainoseis` (
  `id` int(11) NOT NULL,
  `imerominia` date NOT NULL,
  `thema` varchar(255) NOT NULL,
  `keimeno` text NOT NULL,
  `link` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `Anakainoseis`
--

INSERT INTO `Anakainoseis` (`id`, `imerominia`, `thema`, `keimeno`, `link`) VALUES
(1, '2024-11-27', 'Ανακοίνωση 1', 'Τα μαθήματα θα ξεκινήσουν την Δευτέρα 1 Δεκεμβρίου 2024. ', ''),
(2, '2024-11-20', 'Ανακοίνωση 2', 'Οι πρώτες δύο εργασίες έχουν ανακοινωθεί στην ιστοσελίδα:', 'https://novatsidi.webpages.auth.gr/4198partB/homework.php');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `Eggrafa`
--

CREATE TABLE `Eggrafa` (
  `id` int(11) NOT NULL,
  `titlos` varchar(255) NOT NULL,
  `perigrafi` text,
  `arxeio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `Eggrafa`
--

INSERT INTO `Eggrafa` (`id`, `titlos`, `perigrafi`, `arxeio`) VALUES
(12, 'Έγγραφο 1', 'Στο πρώτο έγγραφο περιλαμβάνονται λεπτομέρειες για το πρόγραμμα μαθημάτων.', 'uploads/file1.doc'),
(13, 'Έγγραφο 2', 'Το δεύτερο έγγραφο περιέχει παραδείγματα ασκήσεων για εξάσκηση.', 'uploads/file2.doc');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `Ergasies`
--

CREATE TABLE `Ergasies` (
  `id` int(11) NOT NULL,
  `stoxoi` text NOT NULL,
  `ekfonisi` varchar(255) NOT NULL,
  `paradotea` text NOT NULL,
  `imerominia_paradosis` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `Ergasies`
--

INSERT INTO `Ergasies` (`id`, `stoxoi`, `ekfonisi`, `paradotea`, `imerominia_paradosis`) VALUES
(12, 'Κατανόηση βασικών εννοιών HTML και CSS.\r\nΥλοποίηση ενός στατικού ιστοχώρου για την υποστήριξη ενός προπτυχιακού μαθήματος.\r\nΔημιουργία πολλαπλών σελίδων με συνδεδεμένο περιεχόμενο (Ανακοινώσεις, Επικοινωνία, Έγγραφα, Εργασίες).', 'uploads_homework/ergasia1.doc', 'Αρχεία HTML και CSS που υλοποιούν τον στατικό ιστοχώρο.\r\nΒοηθητικά αρχεία (π.χ. εικόνες που χρησιμοποιούνται για τα κουμπιά).\r\nΜικρή αναφορά (μέχρι 1 σελίδα) σε Word που επεξηγεί τη λειτουργία του ιστοχώρου.', '2025-02-06'),
(13, 'Μετατροπή στατικού ιστοχώρου σε δυναμικό.\r\nΔημιουργία βάσης δεδομένων για την αποθήκευση ανακοινώσεων, εγγράφων και εργασιών.\r\nΥλοποίηση πιστοποίησης χρηστών με PHP και MySQL.', 'uploads_homework/ergasia2.doc', 'Δυναμική ιστοσελίδα με PHP scripts.\r\nΒάση δεδομένων σε μορφή .sql.\r\nΑναφορά τεκμηρίωσης (μέχρι 5 σελίδες).', '2025-02-15');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `Xristes`
--

CREATE TABLE `Xristes` (
  `id` int(11) NOT NULL,
  `onoma` varchar(255) NOT NULL,
  `eponymo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rolos` enum('Tutor','Student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `Xristes`
--

INSERT INTO `Xristes` (`id`, `onoma`, `eponymo`, `email`, `password`, `rolos`) VALUES
(1, 'Γιάννης', 'Παπαδόπουλος', 'giannis@gmail.com', 'password123', 'Student'),
(2, 'Μαρία', 'Αγγελοπούλου', 'maria@yahoo.com', 'password456', 'Student'),
(4, 'Athanasios', 'Novatsidis', 'nova.thanos@gmail.com', 'password789', 'Tutor'),
(7, 'Nikos', 'Papadopoulos', 'nova.nikos@gmail.com', 'nikos1001', 'Student');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `Anakainoseis`
--
ALTER TABLE `Anakainoseis`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `Eggrafa`
--
ALTER TABLE `Eggrafa`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `Ergasies`
--
ALTER TABLE `Ergasies`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `Xristes`
--
ALTER TABLE `Xristes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `Anakainoseis`
--
ALTER TABLE `Anakainoseis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT για πίνακα `Eggrafa`
--
ALTER TABLE `Eggrafa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT για πίνακα `Ergasies`
--
ALTER TABLE `Ergasies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT για πίνακα `Xristes`
--
ALTER TABLE `Xristes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
