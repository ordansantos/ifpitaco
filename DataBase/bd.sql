-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 11-Fev-2015 às 12:03
-- Versão do servidor: 5.5.41-0ubuntu0.14.04.1
-- versão do PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `bd_ifpitaco`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
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
  PRIMARY KEY (`id_usuario`,`email`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`id_usuario`, `nm_usuario`, `senha`, `email`, `usuario_tipo`, `curso`, `ano_periodo`, `grau_academico`) VALUES
(1, 'Ordan Santos', 'winter', 'citycold.ordan@gmail.com', 'Aluno', 'Informática Integrado', 4, 'Especialização'),
(2, 'Renan da Zuera', '123abc', 'juanlyrabarros@gmail.com', 'Aluno', 'Operação de Microcomputadores Proeja', 8, 'Especialização'),
(3, 'Ana', '*Oip152830', 'aninha.ribeiro96@yahoo.com.br', 'Aluno', 'Informática Integrado', 4, 'Especialização'),
(4, 'Thierry Barros', '123123', 'thierrybarros13@hotmail.com', 'Aluno', 'Informática Integrado', 2, 'Especialização'),
(5, 'Gaveta', '91586356 ', 'cesarguedes_@hotmail.com', 'Aluno', 'Informática Integrado', 6, 'Especialização'),
(6, 'José Renan', 'jrsl1401', 'joserenansl@hotmail.com', 'Aluno', 'Informática Integrado', 2, 'Especialização'),
(7, 'Rerisson Daniel', 'rerissondaniel99008780', 'rerissondaniel@gmail.com', 'Aluno', 'Informática Integrado', 3, 'Especialização'),
(8, 'yagogusmao', 'yago2806', 'yagogusmao1998@gmail.com', 'Aluno', 'Informática Integrado', 2, 'Especialização'),
(9, 'Thiago', '5287957', 'jthiagodss@hotmail.com', 'Aluno', 'Informática Integrado', 3, 'Especialização'),
(10, 'Lucas', 'lucas%luna', 'lucas.lunarf@gmail.com', 'Aluno', 'Informática Integrado', 4, 'Especialização'),
(11, 'jonathan', '123456', 'jonathan@uol.com', 'Aluno', 'Informática Integrado', 1, 'Especialização'),
(12, 'Pedro de Farias', 'nathalia', 'pedeodefariasleite@gmail.com', 'Aluno', 'Informática Integrado', 4, 'Especialização'),
(13, 'Arthur Dimitri', 'eunadopeito', 'arthur.android.if@gmail.com', 'Aluno', 'Manutenção e Suporte em Informática Integrado', 4, 'Especialização'),
(14, 'Poly', 'campinagrande321', 'polly_sol321@hotmail.com', 'Aluno', 'Manutenção e Suporte em Informática Integrado', 2, 'Especialização'),
(15, 'Myllena', 'trezeeternapaixao', 'malexandrehs@gmail.com', 'Aluno', 'Informática Integrado', 4, 'Especialização');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
