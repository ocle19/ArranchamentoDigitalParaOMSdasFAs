-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 10-Ago-2021 às 09:03
-- Versão do servidor: 8.0.26-0ubuntu0.20.04.2
-- versão do PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `arranchamento`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `diasarranchado`
--

CREATE TABLE `diasarranchado` (
  `id` bigint NOT NULL,
  `militar` int NOT NULL,
  `data` date NOT NULL,
  `cafe` int NOT NULL DEFAULT '2',
  `almoco` int NOT NULL DEFAULT '2',
  `janta` int NOT NULL DEFAULT '2',
  `justcafe` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'aguardando',
  `justalmoco` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'aguardando',
  `justjanta` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'aguardando'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcao`
--

CREATE TABLE `funcao` (
  `id` int NOT NULL,
  `descricao` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `funcao`
--

INSERT INTO `funcao` (`id`, `descricao`) VALUES
(1, 'Normal'),
(2, 'Furriel'),
(3, 'Aprovisionador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `militares`
--

CREATE TABLE `militares` (
  `id` int NOT NULL,
  `numero` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nomeCompleto` varchar(90) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nomeGuerra` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subUnidade` int NOT NULL,
  `status` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ATIVADO',
  `grad` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `foto` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'anonimo.jpg',
  `ano` int DEFAULT '2021',
  `senha` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nivel` int NOT NULL DEFAULT '1',
  `laranjeira` int NOT NULL DEFAULT '0',
  `regras` int NOT NULL DEFAULT '1',
  `especial` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `militares`
--

INSERT INTO `militares` (`id`, `numero`, `nomeCompleto`, `nomeGuerra`, `subUnidade`, `status`, `grad`, `foto`, `ano`, `senha`, `nivel`, `laranjeira`, `regras`, `especial`) VALUES
(1, '-', 'Aprovisionador', 'aprov', 0, 'ATIVADO', '1º Ten', 'anonimo.jpg', 2021, '12345', 3, 0, 1, 0),
(2, '89', 'Furriel de Exemplo', 'furriel', 1, 'ATIVADO', '3º Sgt', 'anonimo.jpg', 2021, '12345', 2, 0, 1, 0),
(3, '200', 'Soldado Ep Exemplo', 'soldado', 1, 'ATIVADO', 'Sd Ep', 'anonimo.jpg', 2021, '12345', 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `subunidades`
--

CREATE TABLE `subunidades` (
  `id` int NOT NULL,
  `descricao` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `abreviatura` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `subunidades`
--

INSERT INTO `subunidades` (`id`, `descricao`, `abreviatura`) VALUES
(0, 'Bateria de Comando', 'Bia C'),
(1, '1ª Bateria  de Obuses', '1ª Bia O'),
(2, '2ª Bateria  de Obuses', '2ª Bia O'),
(3, '3ª Bateria  de Obuses', '3ª Bia O'),
(4, 'Conscritos', 'Conscritos'),
(10, 'Visitantes', 'Visitantes');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `diasarranchado`
--
ALTER TABLE `diasarranchado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MILITARES` (`militar`),
  ADD KEY `data` (`data`),
  ADD KEY `militar` (`militar`);

--
-- Índices para tabela `funcao`
--
ALTER TABLE `funcao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices para tabela `militares`
--
ALTER TABLE `militares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `Funcao` (`nivel`),
  ADD KEY `SubUnidade` (`subUnidade`);

--
-- Índices para tabela `subunidades`
--
ALTER TABLE `subunidades`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `diasarranchado`
--
ALTER TABLE `diasarranchado`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcao`
--
ALTER TABLE `funcao`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `militares`
--
ALTER TABLE `militares`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `subunidades`
--
ALTER TABLE `subunidades`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `diasarranchado`
--
ALTER TABLE `diasarranchado`
  ADD CONSTRAINT `MILITARES` FOREIGN KEY (`militar`) REFERENCES `militares` (`id`);

--
-- Limitadores para a tabela `militares`
--
ALTER TABLE `militares`
  ADD CONSTRAINT `Funcao` FOREIGN KEY (`nivel`) REFERENCES `funcao` (`id`),
  ADD CONSTRAINT `SubUnidade` FOREIGN KEY (`subUnidade`) REFERENCES `subunidades` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
