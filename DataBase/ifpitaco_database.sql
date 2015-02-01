-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 01-Fev-2015 às 13:49
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

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `edit_distance`(`s1` VARCHAR(255), `s2` VARCHAR(255)) RETURNS int(11)
    DETERMINISTIC
BEGIN 
    DECLARE s1_len, s2_len, i, j, c, c_temp, cost INT; 
    DECLARE s1_char CHAR; 
    -- max strlen=255 
    DECLARE cv0, cv1 VARBINARY(256); 
    SET s1_len = CHAR_LENGTH(s1), s2_len = CHAR_LENGTH(s2), cv1 = 0x00, j = 1, i = 1, c = 0; 
    IF s1 = s2 THEN 
      RETURN 0; 
    ELSEIF s1_len = 0 THEN 
      RETURN s2_len; 
    ELSEIF s2_len = 0 THEN 
      RETURN s1_len; 
    ELSE 
      WHILE j <= s2_len DO 
        SET cv1 = CONCAT(cv1, UNHEX(HEX(j))), j = j + 1; 
      END WHILE; 
      WHILE i <= s1_len DO 
        SET s1_char = SUBSTRING(s1, i, 1), c = i, cv0 = UNHEX(HEX(i)), j = 1; 
        WHILE j <= s2_len DO 
          SET c = c + 1; 
          IF s1_char = SUBSTRING(s2, j, 1) THEN  
            SET cost = 0; ELSE SET cost = 1; 
          END IF; 
          SET c_temp = CONV(HEX(SUBSTRING(cv1, j, 1)), 16, 10) + cost; 
          IF c > c_temp THEN SET c = c_temp; END IF; 
            SET c_temp = CONV(HEX(SUBSTRING(cv1, j+1, 1)), 16, 10) + 1; 
            IF c > c_temp THEN  
              SET c = c_temp;  
            END IF; 
            SET cv0 = CONCAT(cv0, UNHEX(HEX(c))), j = j + 1; 
        END WHILE; 
        SET cv1 = cv0, i = i + 1; 
      END WHILE; 
    END IF; 
    RETURN c; 
  END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_avaliacao`
--

CREATE TABLE IF NOT EXISTS `tb_avaliacao` (
  `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT,
  `ramo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nota` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_avaliacao`),
  KEY `ramo_id` (`ramo_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_comentario_post`
--

CREATE TABLE IF NOT EXISTS `tb_comentario_post` (
  `comentario_post_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(1000) NOT NULL,
  PRIMARY KEY (`comentario_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_enquete`
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
  PRIMARY KEY (`id_enquete`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_enquete_voto`
--

CREATE TABLE IF NOT EXISTS `tb_enquete_voto` (
  `usuario_id` int(11) NOT NULL,
  `enquete_id` int(11) NOT NULL,
  `voto` int(11) NOT NULL,
  PRIMARY KEY (`usuario_id`,`enquete_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_fiscalizacao`
--

CREATE TABLE IF NOT EXISTS `tb_fiscalizacao` (
  `id_fiscalizacao` int(11) NOT NULL AUTO_INCREMENT,
  `ramo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` varchar(1000) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_fiscalizacao`),
  KEY `ramo_id` (`ramo_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_imagem_enquete`
--

CREATE TABLE IF NOT EXISTS `tb_imagem_enquete` (
  `enquete_id` int(11) NOT NULL,
  `imagem` varchar(100) NOT NULL,
  PRIMARY KEY (`enquete_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_imagem_fiscalizacao`
--

CREATE TABLE IF NOT EXISTS `tb_imagem_fiscalizacao` (
  `post_id` int(11) NOT NULL,
  `imagem` varchar(100) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_imagem_usuario`
--

CREATE TABLE IF NOT EXISTS `tb_imagem_usuario` (
  `usuario_id` int(11) NOT NULL,
  `perfil_120` varchar(100) NOT NULL,
  `perfil_45` varchar(100) NOT NULL,
  `perfil_32` varchar(100) NOT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_laikar`
--

CREATE TABLE IF NOT EXISTS `tb_laikar` (
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_post`
--

CREATE TABLE IF NOT EXISTS `tb_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `ramo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` varchar(1000) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` int(11) NOT NULL COMMENT '0, 1, 2 = proposta, fiscalização, fiscalização + foto',
  `imagem` varchar(100) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_proposta`
--

CREATE TABLE IF NOT EXISTS `tb_proposta` (
  `id_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `ramo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` varchar(1000) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_proposta`),
  KEY `ramo_id` (`ramo_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ramo`
--

CREATE TABLE IF NOT EXISTS `tb_ramo` (
  `nm_ramo` varchar(100) NOT NULL COMMENT 'Nome do ramo',
  `id_ramo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do ramo',
  PRIMARY KEY (`id_ramo`),
  KEY `id_ramo` (`id_ramo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabela que armazena o ID e a descrição de cada ramo' AUTO_INCREMENT=25 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=158 ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tb_avaliacao`
--
ALTER TABLE `tb_avaliacao`
  ADD CONSTRAINT `tb_avaliacao_ibfk_1` FOREIGN KEY (`ramo_id`) REFERENCES `tb_ramo` (`id_ramo`),
  ADD CONSTRAINT `tb_avaliacao_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuario` (`id_usuario`);

--
-- Limitadores para a tabela `tb_fiscalizacao`
--
ALTER TABLE `tb_fiscalizacao`
  ADD CONSTRAINT `tb_fiscalizacao_ibfk_1` FOREIGN KEY (`ramo_id`) REFERENCES `tb_ramo` (`id_ramo`),
  ADD CONSTRAINT `tb_fiscalizacao_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuario` (`id_usuario`);

--
-- Limitadores para a tabela `tb_proposta`
--
ALTER TABLE `tb_proposta`
  ADD CONSTRAINT `tb_proposta_ibfk_1` FOREIGN KEY (`ramo_id`) REFERENCES `tb_ramo` (`id_ramo`),
  ADD CONSTRAINT `tb_proposta_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuario` (`id_usuario`);

--
-- Extraindo dados da tabela `tb_ramo`
--

INSERT INTO `tb_ramo` (`nm_ramo`, `id_ramo`) VALUES
('Estrutura de Curso:Informática Integrado', 1),
('Estrutura de Curso:Manutenção e Suporte em Informática Integrado', 2),
('Estrutura de Curso:Mineração Integrado', 3),
('Estrutura de Curso: Petróleo e Gás Integrado', 4),
('Estrutura de Curso:Operação de Microcomputadores Proeja', 5),
('Estrutura de Curso:Manutenção e Suporte em Informática Subsequente', 7),
('Estrutura de Curso:Mineração Subsequente ', 8),
('Estrutura de Curso:Construção de Edifícios Superior', 9),
('Estrutura de Curso:Física Superior', 10),
('Estrutura de Curso:Letras em Língua Portuguesa Superior', 11),
('Estrutura de Curso:Matemática Superior', 12),
('Infraestrutura:Banheiros', 13),
('Infraestrutura:Biblioteca', 14),
('Infraestrutura:Cantina', 15),
('Infraestrutura:Laboratórios', 16),
('Infraestrutura:Praça Esportiva', 17),
('Infraestrutura:Salas de aula', 18),
('Coordenação:Integrado', 19),
('Coordenação:Proeja', 20),
('Coordenação:Subsequente', 21),
('Coordenação:Superior', 22),
('Coordenação:Caest', 23),
('Coordenação:QAcadêmico', 24);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
