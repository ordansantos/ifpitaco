-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 07-Fev-2015 às 21:51
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Extraindo dados da tabela `tb_comentario_post`
--

INSERT INTO `tb_comentario_post` (`comentario_post_id`, `usuario_id`, `post_id`, `data_hora`, `comentario`) VALUES
(18, 157, 10, '2015-02-01 15:33:00', 'Greve agora não surtirá efeitos.'),
(32, 162, 10, '2015-02-03 00:25:15', 'Tem que ter greve sim! Estamos quase ajeitando o calendário anual, pode isso não. GREVE! GREVE!'),
(33, 163, 10, '2015-02-03 00:25:53', 'Raramente surte'),
(34, 163, 47, '2015-02-03 00:28:29', 'Melhor colocar a nota direto logo.'),
(35, 156, 47, '2015-02-03 00:29:29', 'Passando e relaxando, assim que eu gosto'),
(36, 164, 47, '2015-02-03 00:29:51', 'pode ser (y) qro os like tbm ordan'),
(37, 162, 47, '2015-02-03 00:30:03', 'É dez pra todo mundo.. '),
(38, 162, 47, '2015-02-03 00:30:10', 'Menos pra pedrinho..'),
(39, 164, 47, '2015-02-03 00:40:13', 'ei ordan, tu consegue fazer uma parada pra acompanhar as discussões ou votação?'),
(40, 162, 47, '2015-02-03 00:41:47', 'Aqui não é bate papo, man.'),
(41, 163, 47, '2015-02-03 00:43:12', 'Que ousada '),
(42, 156, 47, '2015-02-03 00:44:31', 'bate papo, boa ideia!'),
(43, 164, 47, '2015-02-03 00:45:19', 'não poha, bp não, só notificação já tava bom'),
(44, 162, 47, '2015-02-03 00:45:40', 'É, notificação é uma boa. '),
(46, 156, 48, '2015-02-03 01:38:24', 'Deveria haver penalização'),
(47, 156, 48, '2015-02-03 01:38:36', 'Menos dois pontos na disciplina de meio ambiente !'),
(48, 169, 48, '2015-02-03 03:49:04', 'se num pisar, morrem sem água, dá no msm .-.'),
(49, 156, 48, '2015-02-03 03:49:32', 'kkkkkkkk'),
(50, 156, 48, '2015-02-03 03:49:37', 'é uma zuera só'),
(51, 156, 55, '2015-02-03 03:53:08', 'Sociologia para que? Eu quero é relaxar');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Extraindo dados da tabela `tb_enquete`
--

INSERT INTO `tb_enquete` (`id_enquete`, `usuario_id`, `titulo`, `opt_1`, `opt_2`, `opt_3`, `opt_4`, `opt_5`, `qtd_opt`, `data_hora`) VALUES
(18, 156, 'O que acham da comida da cantina?', 'Boa', 'Precisa melhorar', 'Horrível', 'Delícia', '', 4, '2015-02-01 15:23:50'),
(25, 163, 'Seu projeto de Web já está pronto?', 'Mas é claro', 'Terminando', 'Não importa, já passei', 'QUE PROJETO?', '', 4, '2015-02-03 00:34:30');

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

--
-- Extraindo dados da tabela `tb_enquete_voto`
--

INSERT INTO `tb_enquete_voto` (`usuario_id`, `enquete_id`, `voto`) VALUES
(156, 18, 2),
(156, 25, 3),
(157, 18, 3),
(162, 25, 3),
(163, 18, 2),
(163, 25, 4),
(164, 18, 4),
(164, 25, 4),
(168, 25, 3),
(169, 18, 2),
(169, 25, 4),
(170, 18, 3),
(170, 25, 4);

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

--
-- Extraindo dados da tabela `tb_imagem_enquete`
--

INSERT INTO `tb_imagem_enquete` (`enquete_id`, `imagem`) VALUES
(18, ''),
(25, 'WebService/uploaded_images/enquete_foto/25.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_imagem_fiscalizacao`
--

CREATE TABLE IF NOT EXISTS `tb_imagem_fiscalizacao` (
  `post_id` int(11) NOT NULL,
  `imagem` varchar(100) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_imagem_fiscalizacao`
--

INSERT INTO `tb_imagem_fiscalizacao` (`post_id`, `imagem`) VALUES
(101, 'WebService/uploaded_images/fiscalizacao_foto/101.jpg'),
(103, 'WebService/uploaded_images/fiscalizacao_foto/103.jpg');

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

--
-- Extraindo dados da tabela `tb_imagem_usuario`
--

INSERT INTO `tb_imagem_usuario` (`usuario_id`, `perfil_120`, `perfil_45`, `perfil_32`) VALUES
(156, 'WebService/uploaded_images/perfil_120/010215012215.jpg', 'WebService/uploaded_images/perfil_45/010215012215.jpg', 'WebService/uploaded_images/perfil_32/010215012215.jpg'),
(157, 'WebService/uploaded_images/perfil_120/010215013155.jpg', 'WebService/uploaded_images/perfil_45/010215013155.jpg', 'WebService/uploaded_images/perfil_32/010215013155.jpg'),
(160, 'WebService/uploaded_images/perfil_120/default.jpg', 'WebService/uploaded_images/perfil_45/default.jpg', 'WebService/uploaded_images/perfil_32/default.jpg'),
(162, 'WebService/uploaded_images/perfil_120/020215102156.jpg', 'WebService/uploaded_images/perfil_45/020215102156.jpg', 'WebService/uploaded_images/perfil_32/020215102156.jpg'),
(163, 'WebService/uploaded_images/perfil_120/020215102352.jpg', 'WebService/uploaded_images/perfil_45/020215102352.jpg', 'WebService/uploaded_images/perfil_32/020215102352.jpg'),
(164, 'WebService/uploaded_images/perfil_120/020215105548.jpg', 'WebService/uploaded_images/perfil_45/020215105548.jpg', 'WebService/uploaded_images/perfil_32/020215105548.jpg'),
(165, 'WebService/uploaded_images/perfil_120/default.jpg', 'WebService/uploaded_images/perfil_45/default.jpg', 'WebService/uploaded_images/perfil_32/default.jpg'),
(166, 'WebService/uploaded_images/perfil_120/default.jpg', 'WebService/uploaded_images/perfil_45/default.jpg', 'WebService/uploaded_images/perfil_32/default.jpg'),
(167, 'WebService/uploaded_images/perfil_120/default.jpg', 'WebService/uploaded_images/perfil_45/default.jpg', 'WebService/uploaded_images/perfil_32/default.jpg'),
(168, 'WebService/uploaded_images/perfil_120/030215124816.jpg', 'WebService/uploaded_images/perfil_45/030215124816.jpg', 'WebService/uploaded_images/perfil_32/030215124816.jpg'),
(169, 'WebService/uploaded_images/perfil_120/030215014749.jpg', 'WebService/uploaded_images/perfil_45/030215014749.jpg', 'WebService/uploaded_images/perfil_32/030215014749.jpg'),
(170, 'WebService/uploaded_images/perfil_120/030215022032.jpg', 'WebService/uploaded_images/perfil_45/030215022032.jpg', 'WebService/uploaded_images/perfil_32/030215022032.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_laikar`
--

CREATE TABLE IF NOT EXISTS `tb_laikar` (
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_laikar`
--

INSERT INTO `tb_laikar` (`post_id`, `usuario_id`) VALUES
(10, 169),
(47, 156),
(47, 162),
(48, 164),
(48, 167),
(48, 169),
(55, 156);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Extraindo dados da tabela `tb_post`
--

INSERT INTO `tb_post` (`post_id`, `ramo_id`, `usuario_id`, `comentario`, `data_hora`, `tipo`, `imagem`) VALUES
(10, 1, 156, 'Proponho mais uma greve!', '2015-02-01 15:24:09', 0, ''),
(47, 2, 164, 'Sobre a falta de professores, os alunos do segundo e do terceiro ano não apresentam aulas de Sociologia e o problema se estende desde o início do ano letivo. Só agora que a coordenação do curso veio dar um retorno a respeito disso e acho eu, ser ineficaz. Seria melhor acordar com os alunos aulas extras no próximo ano letivo do que passar uma atividade valendo por todo ano e não aprender nada. ', '2015-02-03 00:25:16', 0, ''),
(48, 17, 164, 'estão pisando na grama, elas vão morrer se continuarem fazendo isso :O tem que fiscalizar :v ', '2015-02-03 01:28:58', 1, ''),
(52, 1, 161, 'as fardas', '2015-02-03 01:38:14', 1, ''),
(55, 2, 169, 'Estamos sem professor de sociologia desde o inicio do ano :''( não que ele faça alguma falta... mas enfim :''(', '2015-02-03 03:50:54', 1, '');

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

--
-- Extraindo dados da tabela `tb_ramo`
--

INSERT INTO `tb_ramo` (`nm_ramo`, `id_ramo`) VALUES
('Instituição:Geral', 1),
('Estrutura de Curso:Informática Integrado', 2),
('Estrutura de Curso:Manutenção e Suporte em Informática Integrado', 3),
('Estrutura de Curso:Mineração Integrado', 4),
('Estrutura de Curso: Petróleo e Gás Integrado', 5),
('Estrutura de Curso:Operação de Microcomputadores Proeja', 6),
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=171 ;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`id_usuario`, `nm_usuario`, `senha`, `email`, `usuario_tipo`, `curso`, `ano_periodo`, `grau_academico`) VALUES
(156, 'Ordan', 'winter', 'citycold.ordan@gmail.com', 'Aluno', 'Informática Integrado', 4, 'Especialização'),
(157, 'Rhavy Maia', '123456', 'rhavy.maia@gmail.com', 'Professor', 'Informática Integrado', 1, 'Especialização'),
(162, 'Nats', '123456', 'nataliacfmoura@gmail.com', 'Aluno', 'Informática Integrado', 4, 'Especialização'),
(163, 'Jailson Mendes', '87063932', 'jonathan.ifpb@gmail.com', 'Aluno', 'Informática Integrado', 4, 'Especialização'),
(164, 'Juan Barros', '123abc', 'juanlyrabarros@gmail.com', 'Aluno', 'Informática Integrado', 3, 'Especialização'),
(165, 'Rerisson Daniel', 'oitoehdemais', 'rerissondaniel@gmail.com', 'Aluno', 'Informática Integrado', 1, 'Especialização'),
(168, 'Daniel', '123456', 'daniel_oliveira_@live.com', 'Aluno', 'Informática Integrado', 8, 'Especialização'),
(169, 'Jose Renan', '123456', 'joserenansl@hotmail.com', 'Aluno', 'Informática Integrado', 2, 'Especialização'),
(170, 'Gustavo Ribeiro', '123456', 'gustavofranklin10@gmail.com', 'Aluno', 'Informática Integrado', 3, 'Especialização');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
