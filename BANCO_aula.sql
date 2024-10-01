-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/08/2024 às 02:52
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `aula`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `alunos_codigo` int(11) NOT NULL,
  `alunos_nome` varchar(255) NOT NULL,
  `alunos_sobrenome` varchar(255) NOT NULL,
  `alunos_nascimento` date NOT NULL,
  `alunos_turma` int(11) NOT NULL,
  `alunos_nota_1` decimal(4,2) DEFAULT NULL,
  `alunos_nota_2` decimal(4,2) DEFAULT NULL,
  `alunos_nota_3` decimal(4,2) DEFAULT NULL,
  `alunos_ativo` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`alunos_codigo`, `alunos_nome`, `alunos_sobrenome`, `alunos_nascimento`, `alunos_turma`, `alunos_nota_1`, `alunos_nota_2`, `alunos_nota_3`, `alunos_ativo`) VALUES
(1, 'João', 'Oliveira', '1986-06-24', 1, 8.00, 1.00, NULL, 0),
(2, 'João', 'Oliveira', '1986-06-10', 3, 5.00, 2.00, 8.00, 0),
(3, 'Gilmara', 'Rocha', '2024-02-25', 1, 4.00, 5.00, 1.00, 0),
(4, 'Wagner', 'Silva', '2023-06-28', 2, NULL, NULL, NULL, 0),
(5, 'Carlos', 'Eduardo', '1979-02-10', 3, NULL, NULL, NULL, 0),
(6, 'Carlos', 'Eduardo', '1985-02-10', 3, 0.00, 9.00, NULL, 0),
(7, 'Eduardo', 'Souza', '1986-06-12', 2, 8.00, 1.00, 1.00, 0),
(8, 'Esther', 'Vieira', '2002-01-13', 1, 7.00, 6.00, 2.00, 0),
(9, 'Sandro', 'Costa', '2009-02-15', 2, 3.00, 3.00, 1.00, 0),
(10, 'João', 'Oliveira', '1996-06-18', 1, 8.00, 9.00, NULL, 1),
(11, 'Alvaro', 'Henrique', '2004-03-24', 3, 4.00, 4.00, 4.00, 1),
(12, 'Maria', 'Beatriz', '2004-10-06', 3, 9.00, 9.00, NULL, 1),
(13, 'Ely', 'Rabelo', '1996-02-26', 2, 6.00, 3.00, 9.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `turmas_codigo` int(11) NOT NULL,
  `turmas_nome` varchar(255) NOT NULL,
  `turmas_usuario` int(11) NOT NULL,
  `turmas_ativo` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`turmas_codigo`, `turmas_nome`, `turmas_usuario`, `turmas_ativo`) VALUES
(1, 'A - Eng. de Computação', 1, 1),
(2, 'B - Ciência da Computação', 1, 1),
(3, 'C - Eng. de Software', 1, 1),
(4, 'D - Sistemas de Informação', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usuarios_codigo` int(11) NOT NULL,
  `usuarios_email` varchar(255) NOT NULL,
  `usuarios_senha` varchar(255) NOT NULL,
  `usuarios_ativo` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usuarios_codigo`, `usuarios_email`, `usuarios_senha`, `usuarios_ativo`) VALUES
(1, 'professor@aula.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`alunos_codigo`),
  ADD KEY `alunos_turma` (`alunos_turma`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`turmas_codigo`),
  ADD KEY `turmas_usuario` (`turmas_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuarios_codigo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `alunos_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `turmas_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuarios_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `alunos_ibfk_1` FOREIGN KEY (`alunos_turma`) REFERENCES `turmas` (`turmas_codigo`);

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`turmas_usuario`) REFERENCES `usuarios` (`usuarios_codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
