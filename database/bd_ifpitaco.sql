-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 19/08/2015 às 09:17
-- Versão do servidor: 5.5.44-0ubuntu0.14.04.1
-- Versão do PHP: 5.6.12-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `bd_ifpitaco`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_admins`
--

CREATE TABLE IF NOT EXISTS `tb_admins` (
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_comentario_post`
--

CREATE TABLE IF NOT EXISTS `tb_comentario_post` (
  `comentario_post_id` int(11) NOT NULL AUTO_INCREMENT,
  `deletado` tinyint(4) NOT NULL DEFAULT '0',
  `usuario_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(1000) NOT NULL,
  PRIMARY KEY (`comentario_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_enquete`
--

CREATE TABLE IF NOT EXISTS `tb_enquete` (
  `id_enquete` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(300) NOT NULL,
  `opt_1` varchar(100) NOT NULL,
  `opt_2` varchar(100) NOT NULL,
  `opt_3` varchar(100) NOT NULL,
  `opt_4` varchar(100) NOT NULL,
  `opt_5` varchar(300) NOT NULL,
  `qtd_opt` int(11) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletado` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_enquete`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_enquete_voto`
--

CREATE TABLE IF NOT EXISTS `tb_enquete_voto` (
  `usuario_id` int(11) NOT NULL,
  `enquete_id` int(11) NOT NULL,
  `voto` int(11) NOT NULL,
  PRIMARY KEY (`usuario_id`,`enquete_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_grupos`
--

CREATE TABLE IF NOT EXISTS `tb_grupos` (
  `id_grupo` int(11) NOT NULL AUTO_INCREMENT,
  `nm_grupo` varchar(100) NOT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_imagem_enquete`
--

CREATE TABLE IF NOT EXISTS `tb_imagem_enquete` (
  `enquete_id` int(11) NOT NULL,
  `imagem` varchar(100) NOT NULL,
  PRIMARY KEY (`enquete_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_imagem_usuario`
--

CREATE TABLE IF NOT EXISTS `tb_imagem_usuario` (
  `usuario_id` int(11) NOT NULL,
  `perfil` varchar(100) NOT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_laikar`
--

CREATE TABLE IF NOT EXISTS `tb_laikar` (
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_last_access`
--

CREATE TABLE IF NOT EXISTS `tb_last_access` (
  `usuario_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_post`
--

CREATE TABLE IF NOT EXISTS `tb_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `ramo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` varchar(1000) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` int(11) NOT NULL COMMENT '0, 1, 2 = proposta, fiscalização, fiscalização + foto',
  `imagem` varchar(100) NOT NULL,
  `deletado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=93 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_ramo`
--

CREATE TABLE IF NOT EXISTS `tb_ramo` (
  `nm_ramo` varchar(100) NOT NULL COMMENT 'Nome do ramo',
  `id_ramo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do ramo',
  PRIMARY KEY (`id_ramo`),
  KEY `id_ramo` (`id_ramo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabela que armazena o ID e a descrição de cada ramo' AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuario`
--

CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do usuárioi',
  `nm_usuario` varchar(100) NOT NULL COMMENT 'Nome do Usuário',
  `senha` varchar(100) NOT NULL COMMENT 'Senha do Usuário',
  `email` varchar(100) NOT NULL COMMENT 'Email do usuário',
  `usuario_tipo` varchar(100) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `ano_periodo` int(11) NOT NULL,
  `grau_academico` varchar(100) NOT NULL,
  `grupo` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`email`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=86 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
