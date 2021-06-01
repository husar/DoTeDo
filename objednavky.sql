-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1:3308
-- Čas generovania: Št 21.Jan 2021, 10:07
-- Verzia serveru: 5.7.31
-- Verzia PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `objednavky`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `kabelaze`
--

DROP TABLE IF EXISTS `kabelaze`;
CREATE TABLE IF NOT EXISTS `kabelaze` (
  `id_kabelaz` int(11) NOT NULL AUTO_INCREMENT,
  `vyrobny_prikaz` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `datum_cas` datetime NOT NULL,
  PRIMARY KEY (`id_kabelaz`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sťahujem dáta pre tabuľku `kabelaze`
--

INSERT INTO `kabelaze` (`id_kabelaz`, `vyrobny_prikaz`, `datum_cas`) VALUES
(1, 'VP321021', '2021-01-21 09:44:33'),
(2, 'VP321020', '2021-01-20 07:35:24'),
(7, 'asdasd', '2021-01-21 10:07:56');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `seo_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `module_filename` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sťahujem dáta pre tabuľku `menu`
--

INSERT INTO `menu` (`id`, `name`, `seo_name`, `module_filename`) VALUES
(1, 'Pridať výrobný príkaz', 'pridat-vyrobny-prikaz', 'mod_pridat-vyrobny-prikaz.php'),
(2, 'Výrobné príkazy', 'vyrobne-prikazy', 'mod_vyrobne-prikazy.php');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
