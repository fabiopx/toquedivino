-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 19-Out-2021 às 09:56
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
-- Estrutura da tabela `formation`
--

CREATE TABLE `formation` (
  `idformation` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` mediumtext,
  `price` float NOT NULL,
  `video` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `formation`
--

INSERT INTO `formation` (`idformation`, `name`, `description`, `price`, `video`) VALUES
(1, 'Quarteto de Cordas', 'Formação com 2 violinos, viola e violoncelo', 1200, 'https://youtu.be/EUHBz0hgUAc'),
(2, 'Orquestra', 'Piano digital + Quinteto de cordas + sax/Flauta +  2 clarins + percussão + Regente.', 2500, 'https://youtu.be/ocXQkZGVEv4'),
(3, 'Quarteto Misto', 'Piano digital + violino + sax + percussão', 1800, 'https://youtu.be/ZSmCZb9K5Xc'),
(4, 'Trio 1', 'Piano digital + violino + sax', 1500, 'https://youtu.be/Ok1Hqfpubvw'),
(5, 'Trio 2', 'Piano digital + violino + violoncelo', 1500, 'https://youtu.be/RQuX0B0-AwQ'),
(6, 'Duo 1', 'Piano digital + Violino', 1500, 'https://youtu.be/S9dowPylue8'),
(7, 'Duo 2', 'Piano Digital + Violoncelo', 1500, 'https://youtu.be/DpjO44jJ4Mc'),
(8, 'Piano Digital', 'Piano Digital', 1200, 'https://youtu.be/KAQWzHokZXg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`idformation`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `formation`
--
ALTER TABLE `formation`
  MODIFY `idformation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
