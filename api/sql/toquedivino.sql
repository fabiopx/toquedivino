-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 05-Out-2021 às 09:08
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
-- Estrutura da tabela `account`
--

CREATE TABLE `account` (
  `idaccount` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '0 = disable\n1 = enable',
  `phone` varchar(45) DEFAULT NULL,
  `photo` varchar(100) NOT NULL,
  `pin` int(11) DEFAULT NULL,
  `access` int(11) DEFAULT '0' COMMENT '0 = adm\n1 = user\n2 = client\n3 = musician'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `account`
--

INSERT INTO `account` (`idaccount`, `name`, `email`, `password`, `status`, `phone`, `photo`, `pin`, `access`) VALUES
(27, 'Fabio Paixão', 'fabiopaixao@live.com', 'ff240917', 1, '(11) 97256-6655', 'assets/uploads/IMG_2197_(2).JPG', 55692986, 1),
(28, 'Emerson Almeida', 'emerson@toquedivino.com', 'ea012345', 1, '(19) 99887-7665', '', 69573945, 0),
(29, 'Fernanda Branco Corrêa', 'brancofernanda44@gmail.com', 'Jhw3uzw4', 1, '(11) 99228-8527', '', 15551643, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `agreement`
--

CREATE TABLE `agreement` (
  `idagreement` int(11) NOT NULL,
  `code` bigint(20) NOT NULL,
  `date` datetime DEFAULT NULL,
  `value` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `addition` float DEFAULT NULL,
  `down_payment` float NOT NULL,
  `down_payment_date` date NOT NULL,
  `inscribe_idinscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `agreement`
--

INSERT INTO `agreement` (`idagreement`, `code`, `date`, `value`, `discount`, `addition`, `down_payment`, `down_payment_date`, `inscribe_idinscribe`) VALUES
(15, 202162288311, '2021-09-22 13:18:38', 4800, 0, 0, 1500, '2021-09-24', 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `agreement_has_signature`
--

CREATE TABLE `agreement_has_signature` (
  `agreement_idagreement` int(11) NOT NULL,
  `signature_idsignature` int(11) NOT NULL,
  `inscribe_idinscribe` int(11) NOT NULL,
  `sign` int(11) NOT NULL COMMENT '0 = no 1 = yes',
  `date` datetime NOT NULL,
  `ip` varchar(45) NOT NULL,
  `geolocation` varchar(200) NOT NULL,
  `hash` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `agreement_has_signature`
--

INSERT INTO `agreement_has_signature` (`agreement_idagreement`, `signature_idsignature`, `inscribe_idinscribe`, `sign`, `date`, `ip`, `geolocation`, `hash`) VALUES
(15, 18, 9, 1, '2021-09-22 20:09:38', '189.103.199.177', '-23.0087,-46.8411', '-809943055'),
(15, 19, 9, 1, '2021-09-22 20:12:14', '189.103.199.177', '-23.0087,-46.8411', '1539687839'),
(15, 22, 9, 0, NULL, '', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `composition`
--

CREATE TABLE `composition` (
  `idcomposition` int(11) NOT NULL,
  `service_idservice` int(11) NOT NULL,
  `formation_idformation` int(11) NOT NULL,
  `inscribe_idinscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `composition`
--

INSERT INTO `composition` (`idcomposition`, `service_idservice`, `formation_idformation`, `inscribe_idinscribe`) VALUES
(9, 7, 3, 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `composition_has_instrument`
--

CREATE TABLE `composition_has_instrument` (
  `composition_idcomposition` int(11) NOT NULL,
  `instrument_idinstrument` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `engaged`
--

CREATE TABLE `engaged` (
  `idengaged` int(11) NOT NULL,
  `groom_name` varchar(100) NOT NULL,
  `groom_address` mediumtext NOT NULL COMMENT 'logradouro, número, complemento, bairro, cidade, estado, país',
  `groom_phone` varchar(45) DEFAULT NULL,
  `groom_mobile` varchar(45) NOT NULL,
  `groom_photo` varchar(200) DEFAULT NULL,
  `groom_cpf` varchar(45) NOT NULL,
  `groom_rg` varchar(45) NOT NULL,
  `groom_birthdate` date NOT NULL,
  `groom_email` varchar(100) NOT NULL,
  `bride_name` varchar(100) NOT NULL,
  `bride_address` mediumtext NOT NULL,
  `bride_phone` varchar(45) DEFAULT NULL,
  `bride_mobile` varchar(45) NOT NULL,
  `bride_photo` varchar(200) DEFAULT NULL,
  `bride_cpf` varchar(45) NOT NULL,
  `bride_rg` varchar(45) NOT NULL,
  `bride_birthdate` date NOT NULL,
  `bride_email` varchar(100) NOT NULL,
  `inscribe_idinscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `event`
--

CREATE TABLE `event` (
  `idevent` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `address` mediumtext COMMENT 'logradouro, número, complemento, bairro, cidade, estado, país',
  `inscribe_idinscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `event`
--

INSERT INTO `event` (`idevent`, `name`, `date`, `time`, `address`, `inscribe_idinscribe`) VALUES
(13, 'Jantar', '2021-09-30', '20:30:00', '{\"street\":\"Avenida Prudente de Moraes\",\"number\":\"900\",\"complement\":\"salão de festas\",\"neighborhood\":\"Vila Santa Cruz\",\"city\":\"Itatiba\",\"zipcode\":\"13251500\",\"state\":\"SP\",\"country\":\"Brasil\"}', 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `formation`
--

CREATE TABLE `formation` (
  `idformation` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` mediumtext,
  `price` float NOT NULL,
  `video` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `formation`
--

INSERT INTO `formation` (`idformation`, `name`, `description`, `price`, `video`) VALUES
(1, 'Quarteto de Cordas', 'Formação com 2 violinos, viola e violoncelo', 1500, 'https://youtu.be/hZuzCgnVq7g'),
(3, 'Banda Base', 'Banda Base', 2500, 'https://youtu.be/lGFerO3J3vY');

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
(3, 1),
(3, 8),
(3, 9),
(3, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `graduation_committe`
--

CREATE TABLE `graduation_committe` (
  `idgraduation_committe` int(11) NOT NULL,
  `committe_name` mediumtext NOT NULL COMMENT 'nome, função',
  `inscribe_idinscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `inscribe`
--

CREATE TABLE `inscribe` (
  `idinscribe` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `accountable` varchar(100) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `address` mediumtext COMMENT 'logradouro, número, complemento, bairro, cidade, estado, país',
  `cpf` varchar(45) DEFAULT NULL,
  `rg` varchar(45) DEFAULT NULL,
  `cnpj` varchar(45) DEFAULT NULL,
  `account_idaccount` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0 = cadastro 1 = contrato nao validado 2 = contrato validado 3 = cancelado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `inscribe`
--

INSERT INTO `inscribe` (`idinscribe`, `datetime`, `accountable`, `phone`, `mobile`, `address`, `cpf`, `rg`, `cnpj`, `account_idaccount`, `status`) VALUES
(9, '2021-09-22 00:00:00', 'Fernanda Branco Corrêa', '(11) 99228-8527', '(11) 99228-8527', '{\"street\":\"Avenida Prudente de Moraes\",\"number\":\"900\",\"complement\":\"ap 23\",\"neighborhood\":\"Vila Santa Cruz\",\"city\":\"Itatiba\",\"zipcode\":\"13251-500\",\"state\":\"SP\",\"country\":\"Brasil\"}', '161.753.028-00', '27125136', NULL, 29, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `instrument`
--

CREATE TABLE `instrument` (
  `idinstrument` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `sound` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `instrument`
--

INSERT INTO `instrument` (`idinstrument`, `name`, `sound`, `image`) VALUES
(1, 'Cantor Solo', NULL, 'assets/img/instrument/singer.svg'),
(2, 'Clarim', NULL, 'assets/img/instrument/trumpet.svg'),
(3, 'Coral de 4 vozes', NULL, 'assets/img/instrument/choir_4voices.svg'),
(4, 'Coral de 8 Vozes', NULL, 'assets/img/instrument/choir.svg'),
(5, 'Dueto Vocal', NULL, 'assets/img/instrument/duet.svg'),
(6, 'Flauta', NULL, 'assets/img/instrument/flute.svg'),
(7, 'Harpa', NULL, 'assets/img/instrument/harp.svg'),
(8, 'Saxofone', NULL, 'assets/img/instrument/sax.svg'),
(9, 'Percussão', NULL, 'assets/img/instrument/drums.svg'),
(10, 'Piano Digital', NULL, 'assets/img/instrument/piano.svg'),
(11, 'Piano 1/4 de cauda', NULL, 'assets/img/instrument/grand-piano.svg'),
(12, 'Tímpano', NULL, 'assets/img/instrument/drum.svg'),
(13, 'Viola', NULL, 'assets/img/instrument/viola.svg'),
(14, 'Violino', NULL, 'assets/img/instrument/violin.svg'),
(15, 'Violoncelo', NULL, 'assets/img/instrument/cello.svg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_event`
--

CREATE TABLE `log_event` (
  `idlog_event` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `event` varchar(45) DEFAULT NULL,
  `log_visitor_idlog_visitor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_logon`
--

CREATE TABLE `log_logon` (
  `idlog_logon` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `account_idaccount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_marketing`
--

CREATE TABLE `log_marketing` (
  `idlog_marketing` int(11) NOT NULL,
  `action` int(11) NOT NULL COMMENT '1 = Email\n2 = WhatsApp',
  `datetime` datetime NOT NULL,
  `pre_inscribe_idpre_inscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `log_marketing`
--

INSERT INTO `log_marketing` (`idlog_marketing`, `action`, `datetime`, `pre_inscribe_idpre_inscribe`) VALUES
(6, 1, '2021-08-15 18:46:15', 1),
(7, 2, '2021-08-15 19:03:58', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_visitor`
--

CREATE TABLE `log_visitor` (
  `idlog_visitor` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `page` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `moments`
--

CREATE TABLE `moments` (
  `idmoments` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0 = disable\n1 = enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `moments`
--

INSERT INTO `moments` (`idmoments`, `name`, `description`, `status`) VALUES
(1, 'Entrada do Noivo', 'Músicas para Entrada do Noivo', 1),
(2, 'Entrada dos Padrinhos', 'Entrada dos Padrinhos', 1),
(3, 'Entrada de Florista, Daminha e Pajem', 'Entrada de Florista, Daminha e Pajem', 1),
(4, 'Cortejo da Noiva', 'Cortejo da Noiva', 1),
(5, 'Clarinadas ', 'Clarinadas (toque antes da entada da noiva)', 1),
(6, 'Entrada da Noiva', 'Entrada da Noiva', 1),
(7, 'Bençãos das Alianças', 'Bençãos das Alianças', 1),
(8, 'Comunhão', 'Comunhão', 1),
(9, 'Assinaturas', 'Assinaturas', 1),
(10, 'Pais e Padrinhos', 'Pais e Padrinhos', 1),
(11, 'Saída dos Noivos', 'Saída dos Noivos', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `moments_has_music`
--

CREATE TABLE `moments_has_music` (
  `moments_idmoments` int(11) NOT NULL,
  `music_idmusic` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `moments_has_music`
--

INSERT INTO `moments_has_music` (`moments_idmoments`, `music_idmusic`) VALUES
(1, 6),
(1, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `moments_has_repertory`
--

CREATE TABLE `moments_has_repertory` (
  `moments_idmoments` int(11) NOT NULL,
  `repertory_idrepertory` int(11) NOT NULL,
  `music_idmusic` int(11) NOT NULL,
  `sequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `music`
--

CREATE TABLE `music` (
  `idmusic` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `music`
--

INSERT INTO `music` (`idmusic`, `name`, `url`, `status`) VALUES
(6, '93 million miles – Jason Mraz', 'https://dl.dropbox.com/s/ckmpecb1rv98zui/02.mp3', 1),
(7, '9º sinfonia – L. V. Beethoven', 'https://dl.dropbox.com/s/u4hsy0n73oc0ox1/01.mp3', 1),
(8, 'A bela e a fera – Celine Dion', 'https://dl.dropbox.com/s/w9u388rtgxg6k4a/03.mp3', 1),
(10, 'A whole new world (Alladin) – Peabo Bryson', 'https://dl.dropbox.com/s/0sfhhhl9m8n3sne/05.mp3', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pre_inscribe`
--

CREATE TABLE `pre_inscribe` (
  `idpre_inscribe` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `origin` varchar(200) DEFAULT NULL,
  `accountable` varchar(100) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `flag` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pre_inscribe`
--

INSERT INTO `pre_inscribe` (`idpre_inscribe`, `datetime`, `ip`, `origin`, `accountable`, `phone`, `mobile`, `email`, `flag`) VALUES
(1, '2021-08-15 16:33:31', '000.000.000.000', '', 'Fábio Leandro Paixão', '(11) 97256-6655', '(11) 97256-6655', 'fabiopaixao@live', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pre_inscribe_has_inscribe`
--

CREATE TABLE `pre_inscribe_has_inscribe` (
  `pre_inscribe_idpre_inscribe` int(11) NOT NULL,
  `inscribe_idinscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `repertory`
--

CREATE TABLE `repertory` (
  `idrepertory` int(11) NOT NULL,
  `inscribe_idinscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `service`
--

CREATE TABLE `service` (
  `idservice` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` mediumtext,
  `status` int(11) DEFAULT '1' COMMENT '0 = disable\n1 = enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `service`
--

INSERT INTO `service` (`idservice`, `name`, `description`, `status`) VALUES
(7, 'Jantar', 'Banda toca repertório selecionado', 1),
(8, 'Recepção', 'Recepções', 1),
(9, 'Clarinada', 'Clarinadas', 1),
(10, 'Colação de Grau', 'Colações de Grau', 1),
(11, 'Bodas', 'Bodas', 1),
(12, 'Coquetel', 'Coquetel', 1),
(13, 'Homenagem', 'Homenagens', 1),
(14, 'Casamento', 'Casamento', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `service_has_tax`
--

CREATE TABLE `service_has_tax` (
  `service_idservice` int(11) NOT NULL,
  `tax_idtax` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `service_has_tax`
--

INSERT INTO `service_has_tax` (`service_idservice`, `tax_idtax`) VALUES
(7, 1),
(7, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `signature`
--

CREATE TABLE `signature` (
  `idsignature` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1 = contratante\n2 = contratado\n3 = testemunha\n',
  `font` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0 = inativa 1 = ativa',
  `inuse` int(11) DEFAULT '0' COMMENT '0 = no 1 = yes',
  `account_idaccount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `signature`
--

INSERT INTO `signature` (`idsignature`, `name`, `type`, `font`, `status`, `inuse`, `account_idaccount`) VALUES
(18, 'Fabio Paixão', 3, 'gf-mrs-saint-delafield', 1, 1, 27),
(19, 'Emerson Almeida', 2, 'gf-mrs-saint-delafield', 1, 1, 28),
(22, 'Fernanda Branco Corrêa', 1, 'gf-fuggles', 1, 1, 29);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tax`
--

CREATE TABLE `tax` (
  `idtax` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` mediumtext,
  `value` float NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 = fixa\n2 = multiplicada\n\nquando a taxa for do tipo 2 (multiplicada) precisa ter variante, valor que  pondera com o valor da taxa',
  `multiplied` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tax`
--

INSERT INTO `tax` (`idtax`, `name`, `description`, `value`, `type`, `multiplied`) VALUES
(1, 'Deslocamento', 'Taxa de deslocamento', 2.5, 2, 'por km'),
(3, 'Cachê dos músicos', 'Valor pago aos músicos', 450, 2, 'por pessoa');

-- --------------------------------------------------------

--
-- Estrutura da tabela `variant_tax`
--

CREATE TABLE `variant_tax` (
  `value` varchar(45) DEFAULT NULL,
  `tax_idtax` int(11) NOT NULL,
  `inscribe_idinscribe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `variant_tax`
--

INSERT INTO `variant_tax` (`value`, `tax_idtax`, `inscribe_idinscribe`) VALUES
('200', 1, 9),
('4', 3, 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `weddinig_services`
--

CREATE TABLE `weddinig_services` (
  `idweddinig_services` int(11) NOT NULL,
  `companyname` varchar(200) DEFAULT NULL,
  `address` mediumtext COMMENT 'object { street, number, complement, zipcode, disctirct, city, country }',
  `phone` varchar(45) DEFAULT NULL,
  `contactname` varchar(200) DEFAULT NULL,
  `engaged_idengaged` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`idaccount`);

--
-- Indexes for table `agreement`
--
ALTER TABLE `agreement`
  ADD PRIMARY KEY (`idagreement`),
  ADD KEY `fk_agreement_inscribe1_idx` (`inscribe_idinscribe`);

--
-- Indexes for table `agreement_has_signature`
--
ALTER TABLE `agreement_has_signature`
  ADD PRIMARY KEY (`agreement_idagreement`,`signature_idsignature`,`inscribe_idinscribe`),
  ADD KEY `fk_agreement_has_signature_signature1_idx` (`signature_idsignature`),
  ADD KEY `fk_agreement_has_signature_inscribe1_idx` (`inscribe_idinscribe`);

--
-- Indexes for table `composition`
--
ALTER TABLE `composition`
  ADD PRIMARY KEY (`idcomposition`),
  ADD KEY `fk_composition_service1_idx` (`service_idservice`),
  ADD KEY `fk_composition_formation1_idx` (`formation_idformation`),
  ADD KEY `fk_composition_inscribe1_idx` (`inscribe_idinscribe`);

--
-- Indexes for table `composition_has_instrument`
--
ALTER TABLE `composition_has_instrument`
  ADD PRIMARY KEY (`composition_idcomposition`,`instrument_idinstrument`),
  ADD KEY `fk_composition_has_instrument_instrument1_idx` (`instrument_idinstrument`),
  ADD KEY `fk_composition_has_instrument_composition1_idx` (`composition_idcomposition`);

--
-- Indexes for table `engaged`
--
ALTER TABLE `engaged`
  ADD PRIMARY KEY (`idengaged`,`inscribe_idinscribe`),
  ADD KEY `fk_engaged_inscribe1_idx` (`inscribe_idinscribe`) USING BTREE;

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`idevent`,`inscribe_idinscribe`),
  ADD KEY `fk_event_inscribe1_idx` (`inscribe_idinscribe`);

--
-- Indexes for table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`idformation`);

--
-- Indexes for table `formation_has_instrument`
--
ALTER TABLE `formation_has_instrument`
  ADD PRIMARY KEY (`formation_idformation`,`instrument_idinstrument`),
  ADD KEY `fk_formation_has_instrument_instrument1_idx` (`instrument_idinstrument`),
  ADD KEY `fk_formation_has_instrument_formation1_idx` (`formation_idformation`);

--
-- Indexes for table `graduation_committe`
--
ALTER TABLE `graduation_committe`
  ADD PRIMARY KEY (`idgraduation_committe`,`inscribe_idinscribe`),
  ADD KEY `fk_graduation_committe_inscribe1_idx` (`inscribe_idinscribe`);

--
-- Indexes for table `inscribe`
--
ALTER TABLE `inscribe`
  ADD PRIMARY KEY (`idinscribe`),
  ADD KEY `fk_inscribe_account1_idx` (`account_idaccount`);

--
-- Indexes for table `instrument`
--
ALTER TABLE `instrument`
  ADD PRIMARY KEY (`idinstrument`);

--
-- Indexes for table `log_event`
--
ALTER TABLE `log_event`
  ADD PRIMARY KEY (`idlog_event`),
  ADD KEY `fk_log_event_log_visitor1_idx` (`log_visitor_idlog_visitor`);

--
-- Indexes for table `log_logon`
--
ALTER TABLE `log_logon`
  ADD PRIMARY KEY (`idlog_logon`),
  ADD KEY `fk_log_logon_account1_idx` (`account_idaccount`);

--
-- Indexes for table `log_marketing`
--
ALTER TABLE `log_marketing`
  ADD PRIMARY KEY (`idlog_marketing`),
  ADD KEY `fk_log_marketing_pre_inscribe1` (`pre_inscribe_idpre_inscribe`);

--
-- Indexes for table `log_visitor`
--
ALTER TABLE `log_visitor`
  ADD PRIMARY KEY (`idlog_visitor`);

--
-- Indexes for table `moments`
--
ALTER TABLE `moments`
  ADD PRIMARY KEY (`idmoments`);

--
-- Indexes for table `moments_has_music`
--
ALTER TABLE `moments_has_music`
  ADD PRIMARY KEY (`moments_idmoments`,`music_idmusic`),
  ADD KEY `fk_moments_has_music_music1_idx` (`music_idmusic`),
  ADD KEY `fk_moments_has_music_moments1_idx` (`moments_idmoments`);

--
-- Indexes for table `moments_has_repertory`
--
ALTER TABLE `moments_has_repertory`
  ADD PRIMARY KEY (`moments_idmoments`,`repertory_idrepertory`,`music_idmusic`),
  ADD KEY `fk_moments_has_repertory_repertory1_idx` (`repertory_idrepertory`),
  ADD KEY `fk_moments_has_repertory_moments1_idx` (`moments_idmoments`),
  ADD KEY `fk_moments_has_repertory_music1_idx` (`music_idmusic`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`idmusic`);

--
-- Indexes for table `pre_inscribe`
--
ALTER TABLE `pre_inscribe`
  ADD PRIMARY KEY (`idpre_inscribe`);

--
-- Indexes for table `pre_inscribe_has_inscribe`
--
ALTER TABLE `pre_inscribe_has_inscribe`
  ADD PRIMARY KEY (`pre_inscribe_idpre_inscribe`,`inscribe_idinscribe`),
  ADD KEY `fk_pre_inscribe_has_inscribe_inscribe1_idx` (`inscribe_idinscribe`),
  ADD KEY `fk_pre_inscribe_has_inscribe_pre_inscribe1_idx` (`pre_inscribe_idpre_inscribe`);

--
-- Indexes for table `repertory`
--
ALTER TABLE `repertory`
  ADD PRIMARY KEY (`idrepertory`),
  ADD KEY `fk_repertory_inscribe1_idx` (`inscribe_idinscribe`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`idservice`);

--
-- Indexes for table `service_has_tax`
--
ALTER TABLE `service_has_tax`
  ADD PRIMARY KEY (`service_idservice`,`tax_idtax`),
  ADD KEY `fk_service_has_tax_tax1_idx` (`tax_idtax`),
  ADD KEY `fk_service_has_tax_service1_idx` (`service_idservice`);

--
-- Indexes for table `signature`
--
ALTER TABLE `signature`
  ADD PRIMARY KEY (`idsignature`),
  ADD KEY `fk_signature_account1_idx` (`account_idaccount`);

--
-- Indexes for table `tax`
--
ALTER TABLE `tax`
  ADD PRIMARY KEY (`idtax`);

--
-- Indexes for table `variant_tax`
--
ALTER TABLE `variant_tax`
  ADD PRIMARY KEY (`tax_idtax`,`inscribe_idinscribe`),
  ADD KEY `fk_variant_tax_inscribe1_idx` (`inscribe_idinscribe`);

--
-- Indexes for table `weddinig_services`
--
ALTER TABLE `weddinig_services`
  ADD PRIMARY KEY (`idweddinig_services`),
  ADD KEY `fk_weddinig_services_engaged1_idx` (`engaged_idengaged`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `idaccount` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `agreement`
--
ALTER TABLE `agreement`
  MODIFY `idagreement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `composition`
--
ALTER TABLE `composition`
  MODIFY `idcomposition` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `engaged`
--
ALTER TABLE `engaged`
  MODIFY `idengaged` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `idevent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `formation`
--
ALTER TABLE `formation`
  MODIFY `idformation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `graduation_committe`
--
ALTER TABLE `graduation_committe`
  MODIFY `idgraduation_committe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscribe`
--
ALTER TABLE `inscribe`
  MODIFY `idinscribe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `instrument`
--
ALTER TABLE `instrument`
  MODIFY `idinstrument` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `log_event`
--
ALTER TABLE `log_event`
  MODIFY `idlog_event` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_logon`
--
ALTER TABLE `log_logon`
  MODIFY `idlog_logon` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_marketing`
--
ALTER TABLE `log_marketing`
  MODIFY `idlog_marketing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `log_visitor`
--
ALTER TABLE `log_visitor`
  MODIFY `idlog_visitor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moments`
--
ALTER TABLE `moments`
  MODIFY `idmoments` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `idmusic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pre_inscribe`
--
ALTER TABLE `pre_inscribe`
  MODIFY `idpre_inscribe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `repertory`
--
ALTER TABLE `repertory`
  MODIFY `idrepertory` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `idservice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `signature`
--
ALTER TABLE `signature`
  MODIFY `idsignature` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tax`
--
ALTER TABLE `tax`
  MODIFY `idtax` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `weddinig_services`
--
ALTER TABLE `weddinig_services`
  MODIFY `idweddinig_services` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `agreement`
--
ALTER TABLE `agreement`
  ADD CONSTRAINT `fk_agreement_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agreement_has_signature`
--
ALTER TABLE `agreement_has_signature`
  ADD CONSTRAINT `fk_agreement_has_signature_agreement1` FOREIGN KEY (`agreement_idagreement`) REFERENCES `agreement` (`idagreement`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agreement_has_signature_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agreement_has_signature_signature1` FOREIGN KEY (`signature_idsignature`) REFERENCES `signature` (`idsignature`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `composition`
--
ALTER TABLE `composition`
  ADD CONSTRAINT `fk_composition_formation1` FOREIGN KEY (`formation_idformation`) REFERENCES `formation` (`idformation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_composition_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_composition_service1` FOREIGN KEY (`service_idservice`) REFERENCES `service` (`idservice`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `composition_has_instrument`
--
ALTER TABLE `composition_has_instrument`
  ADD CONSTRAINT `fk_composition_has_instrument_composition1` FOREIGN KEY (`composition_idcomposition`) REFERENCES `composition` (`idcomposition`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_composition_has_instrument_instrument1` FOREIGN KEY (`instrument_idinstrument`) REFERENCES `instrument` (`idinstrument`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `engaged`
--
ALTER TABLE `engaged`
  ADD CONSTRAINT `fk_engaged_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_event_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `formation_has_instrument`
--
ALTER TABLE `formation_has_instrument`
  ADD CONSTRAINT `fk_formation_has_instrument_formation1` FOREIGN KEY (`formation_idformation`) REFERENCES `formation` (`idformation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_formation_has_instrument_instrument1` FOREIGN KEY (`instrument_idinstrument`) REFERENCES `instrument` (`idinstrument`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `graduation_committe`
--
ALTER TABLE `graduation_committe`
  ADD CONSTRAINT `fk_graduation_committe_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `inscribe`
--
ALTER TABLE `inscribe`
  ADD CONSTRAINT `fk_inscribe_account1` FOREIGN KEY (`account_idaccount`) REFERENCES `account` (`idaccount`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `log_event`
--
ALTER TABLE `log_event`
  ADD CONSTRAINT `fk_log_event_log_visitor1` FOREIGN KEY (`log_visitor_idlog_visitor`) REFERENCES `log_visitor` (`idlog_visitor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `log_logon`
--
ALTER TABLE `log_logon`
  ADD CONSTRAINT `fk_log_logon_account1` FOREIGN KEY (`account_idaccount`) REFERENCES `account` (`idaccount`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `log_marketing`
--
ALTER TABLE `log_marketing`
  ADD CONSTRAINT `fk_log_marketing_pre_inscribe1` FOREIGN KEY (`pre_inscribe_idpre_inscribe`) REFERENCES `pre_inscribe` (`idpre_inscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `moments_has_music`
--
ALTER TABLE `moments_has_music`
  ADD CONSTRAINT `fk_moments_has_music_moments1` FOREIGN KEY (`moments_idmoments`) REFERENCES `moments` (`idmoments`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_moments_has_music_music1` FOREIGN KEY (`music_idmusic`) REFERENCES `music` (`idmusic`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `moments_has_repertory`
--
ALTER TABLE `moments_has_repertory`
  ADD CONSTRAINT `fk_moments_has_repertory_moments1` FOREIGN KEY (`moments_idmoments`) REFERENCES `moments` (`idmoments`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_moments_has_repertory_music1` FOREIGN KEY (`music_idmusic`) REFERENCES `music` (`idmusic`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_moments_has_repertory_repertory1` FOREIGN KEY (`repertory_idrepertory`) REFERENCES `repertory` (`idrepertory`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pre_inscribe_has_inscribe`
--
ALTER TABLE `pre_inscribe_has_inscribe`
  ADD CONSTRAINT `fk_pre_inscribe_has_inscribe_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pre_inscribe_has_inscribe_pre_inscribe1` FOREIGN KEY (`pre_inscribe_idpre_inscribe`) REFERENCES `pre_inscribe` (`idpre_inscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `repertory`
--
ALTER TABLE `repertory`
  ADD CONSTRAINT `fk_repertory_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `service_has_tax`
--
ALTER TABLE `service_has_tax`
  ADD CONSTRAINT `fk_service_has_tax_service1` FOREIGN KEY (`service_idservice`) REFERENCES `service` (`idservice`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_service_has_tax_tax1` FOREIGN KEY (`tax_idtax`) REFERENCES `tax` (`idtax`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `signature`
--
ALTER TABLE `signature`
  ADD CONSTRAINT `fk_signature_account1` FOREIGN KEY (`account_idaccount`) REFERENCES `account` (`idaccount`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `variant_tax`
--
ALTER TABLE `variant_tax`
  ADD CONSTRAINT `fk_variant_tax_inscribe1` FOREIGN KEY (`inscribe_idinscribe`) REFERENCES `inscribe` (`idinscribe`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_variant_tax_tax1` FOREIGN KEY (`tax_idtax`) REFERENCES `tax` (`idtax`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `weddinig_services`
--
ALTER TABLE `weddinig_services`
  ADD CONSTRAINT `fk_weddinig_services_engaged1` FOREIGN KEY (`engaged_idengaged`) REFERENCES `engaged` (`idengaged`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
