-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/09/2026 às 02:27
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
-- Banco de dados: `sistema_orcamento`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `tipo_pessoa` enum('fisica','juridica') DEFAULT 'fisica',
  `cpf_cnpj` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `tipo_pessoa`, `cpf_cnpj`, `telefone`, `whatsapp`, `email`, `cep`, `endereco`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `observacoes`, `status`, `criado_em`, `atualizado_em`) VALUES
(2, 'Tulio Montelo Farias', 'fisica', NULL, '63984345473', '63984345473', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ativo', '2026-09-01 22:35:44', '2026-09-01 22:36:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `razao_social` varchar(150) NOT NULL,
  `nome_fantasia` varchar(150) DEFAULT NULL,
  `cpf_cnpj` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `site` varchar(150) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `empresa`
--

INSERT INTO `empresa` (`id`, `razao_social`, `nome_fantasia`, `cpf_cnpj`, `telefone`, `whatsapp`, `email`, `site`, `cep`, `endereco`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `logo`, `observacoes`, `atualizado_em`) VALUES
(1, '57.018.403 THALES MARQUES RODRIGUES', 'SOLUCOES DE INFORMATICA - TMR', '57018403000185', '63992426451', '63992426451', 'tipalmas25@gmail.com', NULL, '77020-530', 'Quadra ARSE 22 Alameda 19', '10', '401', 'Plano Diretor Sul', 'Palmas', 'TO', 'logo_1788303624.jpg', 'PIX CPF 97861871215', '2026-09-01 23:00:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `razao_social` varchar(150) NOT NULL,
  `nome_fantasia` varchar(150) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `responsavel` varchar(150) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamentos`
--

CREATE TABLE `orcamentos` (
  `id` int(11) NOT NULL,
  `numero` varchar(30) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `data_orcamento` date NOT NULL,
  `validade` date DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `desconto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pendente','aprovado','recusado','finalizado') DEFAULT 'pendente',
  `observacoes` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `orcamentos`
--

INSERT INTO `orcamentos` (`id`, `numero`, `cliente_id`, `data_orcamento`, `validade`, `subtotal`, `desconto`, `total`, `status`, `observacoes`, `usuario_id`, `criado_em`, `atualizado_em`) VALUES
(1, 'ORC-2026-00001', 2, '2026-09-02', '2026-09-07', 834.43, 50.00, 784.43, 'pendente', 'com desconto de 50%', 2, '2026-09-01 22:48:18', '2026-09-01 22:48:18');

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamento_itens`
--

CREATE TABLE `orcamento_itens` (
  `id` int(11) NOT NULL,
  `orcamento_id` int(11) NOT NULL,
  `servico_id` int(11) DEFAULT NULL,
  `descricao` varchar(255) NOT NULL,
  `quantidade` decimal(10,2) NOT NULL DEFAULT 1.00,
  `valor_unitario` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `orcamento_itens`
--

INSERT INTO `orcamento_itens` (`id`, `orcamento_id`, `servico_id`, `descricao`, `quantidade`, `valor_unitario`, `total`) VALUES
(1, 1, 7, 'SSD Kingston A400, 480GB, SATA III, 2.5', 1.00, 644.43, 644.43),
(2, 1, 4, 'Formatação de computador', 1.00, 190.00, 190.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `unidade` varchar(50) DEFAULT 'Serviço',
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `custo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `nome`, `descricao`, `categoria`, `unidade`, `valor`, `custo`, `status`, `criado_em`, `atualizado_em`) VALUES
(2, 'Motagem e Instalação de computador', NULL, 'PC', 'Serviço', 350.00, 0.00, 'ativo', '2026-09-01 22:25:45', '2026-09-01 22:25:45'),
(3, 'Manutenção preventiva', NULL, 'PC/NOTEBOOK', 'Serviço', 250.00, 0.00, 'ativo', '2026-09-01 22:26:12', '2026-09-01 22:26:12'),
(4, 'Formatação de computador', NULL, 'PC/NOTEBOOK', 'Serviço', 190.00, 0.00, 'ativo', '2026-09-01 22:26:46', '2026-09-01 22:26:46'),
(5, 'APP/PACOTE OFFICE', NULL, 'PC/NOTEBOOK', 'Serviço', 280.00, 0.00, 'ativo', '2026-09-01 22:27:50', '2026-09-01 22:27:50'),
(7, 'SSD Kingston A400, 480GB', 'SSD Kingston A400, 480GB, SATA III, 2.5\", Leitura: 500MB/s, Gravação: 450MB/s, Preto - SA400S37/480G', 'SSD', 'Serviço', 644.43, 0.00, 'ativo', '2026-09-01 22:42:06', '2026-09-01 22:42:06'),
(8, 'SSD Rise Mode Gamer Line, 240GB', 'SSD Rise Mode Gamer Line, 240GB, SATA III, Leitura: 530MB/s, Gravação: 520MB/s, Preto - RM-SSD-240', 'SSD', 'Serviço', 341.16, 0.00, 'ativo', '2026-09-01 22:42:50', '2026-09-01 22:42:50'),
(9, 'SSD Husky 128GB', 'SSD Husky 128GB, SATA III, 2.5\", Leitura 500MB/s, Gravação 450MB/s, Preto - HSSD001128', 'SSD', 'Serviço', 294.11, 0.00, 'ativo', '2026-09-01 22:44:13', '2026-09-01 22:44:13'),
(10, 'SSD Kingston A400, 960GB', 'SSD Kingston A400, 960GB, SATA III, 2.5\", Leitura: 500MB/s, Gravação: 450MB/s, Preto - SA400S37/960G', 'SSD', 'Serviço', 1444.43, 0.00, 'ativo', '2026-09-01 22:45:05', '2026-09-01 22:45:05'),
(11, 'SSD Sandisk 1TB', 'SSD Sandisk Sdssda-1t00-g27, Plus, 1TB, Sata Iii 6GB/s, Leitura 535mb/s, Gravação 350mb/s', 'SSD', 'Serviço', 805.50, 0.00, 'ativo', '2026-09-01 22:46:25', '2026-09-01 22:46:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','usuario') DEFAULT 'usuario',
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `status`, `criado_em`) VALUES
(2, 'Administrador', 'tipalmas25@gmail.com', '$2y$10$JwjQ7n4VYeM/pJWsqTxk1O4LlYwfMrcudcRRdiYdHgrMxLkbBhcP2', 'admin', 'ativo', '2026-09-01 02:34:28');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `fk_orcamento_cliente` (`cliente_id`),
  ADD KEY `fk_orcamento_usuario` (`usuario_id`);

--
-- Índices de tabela `orcamento_itens`
--
ALTER TABLE `orcamento_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_orcamento` (`orcamento_id`),
  ADD KEY `fk_item_servico` (`servico_id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `orcamento_itens`
--
ALTER TABLE `orcamento_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `orcamentos`
--
ALTER TABLE `orcamentos`
  ADD CONSTRAINT `fk_orcamento_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_orcamento_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `orcamento_itens`
--
ALTER TABLE `orcamento_itens`
  ADD CONSTRAINT `fk_item_orcamento` FOREIGN KEY (`orcamento_id`) REFERENCES `orcamentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_servico` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
