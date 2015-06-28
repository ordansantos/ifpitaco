-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 11-Fev-2015 às 12:05
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
-- Estrutura da tabela `tb_comentario_post`
--

CREATE TABLE IF NOT EXISTS `tb_comentario_post` (
  `comentario_post_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(1000) NOT NULL,
  PRIMARY KEY (`comentario_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

--
-- Extraindo dados da tabela `tb_comentario_post`
--

INSERT INTO `tb_comentario_post` (`comentario_post_id`, `usuario_id`, `post_id`, `data_hora`, `comentario`) VALUES
(37, 1, 5, '2015-02-11 13:22:36', 'Tu tem que propor coisas possíveis de se realizar...'),
(38, 2, 5, '2015-02-11 13:24:38', 'isso é fiscalizar'),
(39, 1, 5, '2015-02-11 13:25:20', 'De rocha'),
(40, 2, 5, '2015-02-11 13:27:39', 'eu estou falando comigo mesmo?'),
(41, 1, 5, '2015-02-11 13:28:02', 'pare de zuera juan'),
(42, 2, 5, '2015-02-11 13:28:20', 'esse boe :v'),
(43, 2, 5, '2015-02-11 13:29:05', 'é massa'),
(44, 2, 5, '2015-02-11 13:29:06', 'gostei'),
(45, 11, 5, '2015-02-11 13:31:16', 'calúnia'),
(48, 11, 6, '2015-02-11 13:33:00', 'isso nem tá parecendo com rerisu'),
(50, 1, 6, '2015-02-11 13:33:24', 'acho que juan criou duas contas'),
(51, 1, 6, '2015-02-11 13:33:28', 'só acho'),
(52, 11, 6, '2015-02-11 13:33:54', 'deve ser vicc ordan?'),
(54, 1, 6, '2015-02-11 13:34:19', 'bento não usa vicc, e não recusaria uma delicia'),
(59, 11, 6, '2015-02-11 13:35:27', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk'),
(60, 1, 7, '2015-02-11 13:46:48', 'kkkkkkkk'),
(61, 1, 7, '2015-02-11 13:49:13', 'todas as friendzone?'),
(62, 11, 7, '2015-02-11 13:50:15', 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk'),
(63, 11, 7, '2015-02-11 13:50:45', 'e tem mais a opção de curiar no número'),
(64, 1, 7, '2015-02-11 13:51:05', 'aqui se curia tudo macho'),
(65, 2, 7, '2015-02-11 13:51:43', 'os boy tão tudo online mas nem comentam'),
(66, 2, 8, '2015-02-11 13:54:28', 'só tu que não vai'),
(67, 1, 8, '2015-02-11 13:56:37', '''-'''),
(68, 15, 8, '2015-02-11 13:59:46', 'kkkkk que sexy'),
(69, 1, 8, '2015-02-11 14:01:08', 'kkkkk'),
(70, 1, 9, '2015-02-11 14:01:26', ':D'),
(71, 2, 9, '2015-02-11 14:02:59', 'essa é mymymosa, olaine na nova rede social');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tb_enquete`
--

INSERT INTO `tb_enquete` (`id_enquete`, `usuario_id`, `titulo`, `opt_1`, `opt_2`, `opt_3`, `opt_4`, `opt_5`, `qtd_opt`, `data_hora`) VALUES
(2, 1, 'O que achastes dessa rede social?', 'Péssima', 'Razoável', 'Bom', 'Legalzinha', 'Uma delícia', 5, '2015-02-11 13:30:54'),
(3, 10, 'Vocês acham que eu deveria parar de tomar anabolizante?', 'Não, pois você é tijolo e sempre será!', 'Não, delícia de braço', 'Sim', '', '', 3, '2015-02-11 13:37:50');

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
(1, 1, 1),
(1, 2, 5),
(1, 3, 1),
(2, 2, 5),
(2, 3, 2),
(7, 2, 5),
(7, 3, 2),
(10, 3, 1),
(11, 2, 1),
(11, 3, 2),
(15, 2, 5),
(15, 3, 1);

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
(2, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423661457/azbfbxwfvzcm5u94vwez.jpg'),
(3, '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_imagem_usuario`
--

CREATE TABLE IF NOT EXISTS `tb_imagem_usuario` (
  `usuario_id` int(11) NOT NULL,
  `perfil` varchar(100) NOT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_imagem_usuario`
--

INSERT INTO `tb_imagem_usuario` (`usuario_id`, `perfil`) VALUES
(1, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423657650/b9w0zvtawg3i2ngwkeay.jpg'),
(2, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423662522/x91ntgnjk6ipnplw0ig3.jpg'),
(3, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(4, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(5, 'https://res.cloudinary.com/hikttgesy/image/upload/v1423618475/ydhfmrp9x2lmugvnx8s1.jpg'),
(6, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(7, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(8, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(9, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(10, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423661802/qjzesztbrzwnfotun5ai.png'),
(11, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423661459/xwsuz7casqimu1kugjfz.jpg'),
(12, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(13, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(14, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg'),
(15, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423663115/b10qr922rh9msrguv5xb.jpg'),
(16, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423353982/default_o5gzqs.jpg');

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
(3, 1),
(5, 1),
(5, 15),
(6, 15),
(7, 1),
(7, 11),
(7, 15),
(8, 1),
(8, 2),
(8, 11),
(8, 15),
(9, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_last_access`
--

CREATE TABLE IF NOT EXISTS `tb_last_access` (
  `usuario_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_last_access`
--

INSERT INTO `tb_last_access` (`usuario_id`, `time`) VALUES
(1, '2015-02-11 14:05:52'),
(2, '2015-02-11 14:05:59'),
(10, '2015-02-11 13:40:43'),
(11, '2015-02-11 14:05:47'),
(12, '2015-02-11 13:41:19'),
(8, '2015-02-11 13:43:27'),
(9, '2015-02-11 13:47:10'),
(4, '2015-02-11 13:51:48'),
(3, '2015-02-11 13:51:59'),
(13, '2015-02-11 13:53:46'),
(14, '2015-02-11 14:05:51'),
(7, '2015-02-11 14:00:27'),
(15, '2015-02-11 14:01:35'),
(16, '2015-02-11 14:05:49');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `tb_post`
--

INSERT INTO `tb_post` (`post_id`, `ramo_id`, `usuario_id`, `comentario`, `data_hora`, `tipo`, `imagem`) VALUES
(3, 1, 2, 'Fiscalizar o curso de manutenção, pera, o mec já fez isso :v', '2015-02-11 13:02:00', 1, ''),
(5, 1, 2, 'as 9inha pra me tirarem da FZ', '2015-02-11 13:19:45', 1, ''),
(6, 1, 11, 'Bem massa!', '2015-02-11 13:32:23', 0, ''),
(7, 1, 2, 'Proponho as boyzinha em casamento ', '2015-02-11 13:44:38', 0, ''),
(8, 1, 1, 'Quem vai para Campus Party Recife esse ano?  Se Dilma não cortar, de fato.', '2015-02-11 13:53:33', 2, 'http://res.cloudinary.com/hikttgesy/image/upload/v1423662814/cge7fwuqzyqiz5b5zwlm.jpg'),
(9, 1, 15, 'Genteeee, que top. Adorei!!', '2015-02-11 14:00:42', 0, '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

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
(15, 'Myllena', 'trezeeternapaixao', 'malexandrehs@gmail.com', 'Aluno', 'Informática Integrado', 4, 'Especialização'),
(16, 'Diego Takei', 'informatica', 'diegotakei1586@gmail.com', 'Aluno', 'Informática Integrado', 2, 'Especialização');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;