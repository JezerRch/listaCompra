-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/11/2023 às 22:29
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lista_compras`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `lista_id` int(11) DEFAULT NULL,
  `nome_produto` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data_adicao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `lista_id`, `nome_produto`, `quantidade`, `preco`, `usuario_id`, `data_adicao`) VALUES
(14, 10, 'Leite em po', 1, 35.90, 1, '2023-10-02 01:24:29'),
(15, 12, 'Bolacha', 10, 1.50, 2, '2023-10-02 02:08:22'),
(16, 12, 'Carne', 2, 35.60, 2, '2023-10-02 02:10:19'),
(17, 12, 'Bolacha Neskal', 5, 4.90, 2, '2023-10-02 02:10:30'),
(19, 13, 'Bolacha', 2, 2.00, 2, '2023-10-02 02:20:41'),
(22, 15, 'Carne', 1, 50.00, 2, '2023-10-02 02:29:21'),
(23, 10, 'Bolacha', 2, 5.00, 1, '2023-11-08 19:11:29'),
(24, 10, 'TESTE', 1, 50.00, 1, '2023-11-08 19:35:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `listas`
--

CREATE TABLE `listas` (
  `id` int(11) NOT NULL,
  `nome_lista` varchar(255) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `listas`
--

INSERT INTO `listas` (`id`, `nome_lista`, `usuario_id`) VALUES
(10, 'Lista', 1),
(11, 'Minha Lista Padrão', 2),
(16, 'Minha Lista Padrão', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Jezer', 'jezerprocha@gmail.com', '$2y$10$6UYpCAE2OgtCYTmykwdW8OcA5R8B/bB52jp2HBTuzJFJPM62HUDmC'),
(2, 'Thais Vilela de Paula Rocha', 'thais@gmail.com', '$2y$10$aAUj.HwAY1Kkv6WR/qvhzuKuLuEWSn77EYua9Kdca6xZFRkwKbwk2'),
(3, 'valorant', 'valorant@gmail.com', '$2y$10$xb/oGVsRxtNaTYPwEZICSOWrE7k77Ak62SViTaQtagT0b2qmyZWrm');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `listas`
--
ALTER TABLE `listas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `listas`
--
ALTER TABLE `listas`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
