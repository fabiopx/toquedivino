-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 19-Out-2021 às 10:02
-- Versão do servidor: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toquedivino`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `formation_has_instrument`
--

CREATE TABLE `formation_has_instrument` (
  `formation_idformation` int(11) NOT NULL,
  `instrument_idinstrument` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `formation_has_instrument`
--

INSERT INTO `formation_has_instrument` (`formation_idformation`, `instrument_idinstrument`) VALUES
(1, 13),
(1, 14),
(1, 15),
(2, 2),
(2, 6),
(2, 8),
(2, 9),
(2, 10),
(2, 13),
(2, 14),
(2, 15),
(3, 8),
(3, 9),
(3, 10),
(3, 14),
(4, 8),
(4, 10),
(4, 14),
(5, 10),
(5, 14),
(5, 15),
(6, 10),
(6, 14),
(7, 10),
(7, 15),
(8, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `formation_has_instrument`
--
ALTER TABLE `formation_has_instrument`
  ADD PRIMARY KEY (`formation_idformation`,`instrument_idinstrument`),
  ADD KEY `fk_formation_has_instrument_instrument1_idx` (`instrument_idinstrument`),
  ADD KEY `fk_formation_has_instrument_formation1_idx` (`formation_idformation`);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `formation_has_instrument`
--
ALTER TABLE `formation_has_instrument`
  ADD CONSTRAINT `fk_formation_has_instrument_formation1` FOREIGN KEY (`formation_idformation`) REFERENCES `formation` (`idformation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_formation_has_instrument_instrument1` FOREIGN KEY (`instrument_idinstrument`) REFERENCES `instrument` (`idinstrument`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
