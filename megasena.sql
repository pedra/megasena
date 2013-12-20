-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 26-Ago-2013 às 16:10
-- Versão do servidor: 5.6.12-log
-- versão do PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `megasena`
--
CREATE DATABASE IF NOT EXISTS `megasena` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `megasena`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `resultados`
--

CREATE TABLE IF NOT EXISTS `resultados` (
  `ID` int(10) unsigned NOT NULL COMMENT 'NUMERO DO CONCURSO',
  `ACERTO` int(10) unsigned NOT NULL COMMENT 'ACERTADORES',
  `D1` int(10) unsigned NOT NULL,
  `D2` int(10) unsigned NOT NULL,
  `D3` int(10) unsigned NOT NULL,
  `D4` int(10) unsigned NOT NULL,
  `D5` int(10) unsigned NOT NULL,
  `D6` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Download do site da www.caixa.gov.br';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
