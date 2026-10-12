-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/11/2025 às 17:49
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
-- Banco de dados: `agendamento_auditorio`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `horario_inicio` time NOT NULL,
  `horario_fim` time NOT NULL,
  `nome` varchar(255) NOT NULL,
  `setor` varchar(255) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `status` enum('pendente','aprovado','recusado') DEFAULT 'pendente',
  `motivo_recusa` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dataSolicitacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id`, `data_inicio`, `data_fim`, `horario_inicio`, `horario_fim`, `nome`, `setor`, `celular`, `email`, `descricao`, `status`, `motivo_recusa`, `created_at`, `updated_at`, `dataSolicitacao`, `data_criacao`) VALUES
(20, '2025-11-13', '2025-11-13', '04:00:00', '07:29:00', 'Adailton vieira da silva', 'TI', '6298282732', 'adailtonvieira0808@gmail.com', 'esse é o teste final', 'aprovado', NULL, '2025-11-14 00:23:13', '2025-11-14 00:25:39', '2025-11-14 00:23:13', '2025-11-14 00:23:13'),
(21, '2025-12-24', '2025-12-25', '23:30:00', '03:59:00', 'Maria', 'RH', '1114060606', 'festa@natal.2025', 'Festa de Natal ', 'aprovado', NULL, '2025-11-14 00:24:01', '2025-11-14 01:51:57', '2025-11-14 00:24:01', '2025-11-14 00:24:01'),
(22, '2025-11-24', '2025-11-24', '00:30:00', '02:29:00', 'Adailton vieira da silva', 'TI', '6298282732', 'adailtonvieira0808@gmail.com', 'e-mail', 'aprovado', NULL, '2025-11-14 00:49:55', '2025-11-14 01:50:23', '2025-11-14 00:49:55', '2025-11-14 00:49:55'),
(24, '2025-11-17', '2025-11-17', '08:00:00', '09:29:00', 'Adailton vieira da silva', 'TI', '6298282732', 'adailtonvieira0808@gmail.com', 'teset', 'recusado', 'nao fez a tarefinha', '2025-11-14 01:02:11', '2025-11-14 01:49:26', '2025-11-14 01:02:11', '2025-11-14 01:02:11'),
(30, '2025-11-30', '2025-11-30', '00:30:00', '01:59:00', 'Adailton vieira da silva', 'TI', '6298282732', 'adailtonvieira0808@gmail.com', 'aaaaaaa', 'recusado', 'EU NÃO QUEROOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '2025-11-14 02:07:19', '2025-11-14 02:08:20', '2025-11-14 02:07:19', '2025-11-14 02:07:19'),
(31, '2025-11-23', '2025-11-23', '00:00:00', '00:59:00', 'Adailton vieira da silva', 'TI', '6298282732', 'adailtonvieira0808@gmail.com', 'aaaa', 'aprovado', NULL, '2025-11-14 02:25:12', '2025-11-14 02:57:10', '2025-11-14 02:25:12', '2025-11-14 02:25:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(10) UNSIGNED NOT NULL,
  `chave` varchar(100) NOT NULL,
  `valor` text NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `configuracoes`
--

INSERT INTO `configuracoes` (`id`, `chave`, `valor`, `descricao`, `created_at`, `updated_at`) VALUES
(1, 'horario_abertura', '00:00:00', 'Horário de abertura do auditório', '2025-11-13 18:37:44', '2025-11-13 18:37:44'),
(2, 'horario_fechamento', '23:59:00', 'Horário de fechamento do auditório', '2025-11-13 18:37:44', '2025-11-13 18:37:44'),
(3, 'intervalo_minimo', '30', 'Intervalo mínimo entre agendamentos em minutos', '2025-11-13 18:37:44', '2025-11-13 18:37:44'),
(4, 'dias_antecedencia', '30', 'Número máximo de dias para agendamento antecipado', '2025-11-13 18:37:44', '2025-11-13 18:37:44'),
(5, 'email_notificacao', 'admin@hospitaljatai.com', 'E-mail para notificações', '2025-11-13 18:37:44', '2025-11-13 18:37:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `acao` varchar(255) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs`
--

INSERT INTO `logs` (`id`, `usuario`, `acao`, `detalhes`, `ip`, `created_at`) VALUES
(1, 'Sistema', 'Novo agendamento solicitado', 'Agendamento para Adailton vieira da silva (aa) - 2025-11-13 00:00 até 2025-11-13 00:59', '::1', '2025-11-13 18:40:08'),
(2, 'Sistema', 'Novo agendamento solicitado', 'Agendamento para Adailtonnnnnnnnnnnnnnnn (aa) - 2025-11-20 00:00 até 2025-11-21 16:59', '::1', '2025-11-13 18:49:41'),
(3, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #3 para rodri (aa) - 2025-11-22 01:30 até 2025-11-22 03:59', '::1', '2025-11-13 19:05:59'),
(4, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #4 para Secretaria de Estado da Saúde / HEJ (abion) - 2025-11-15 08:00 até 2025-11-15 09:29', '::1', '2025-11-13 19:08:25'),
(5, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #5 para rodri (aa) - 2025-11-13 05:30 até 2025-11-13 07:59', '::1', '2025-11-13 19:09:52'),
(6, 'admin1', 'Logout do sistema', 'Usuário saiu do painel administrativo', '::1', '2025-11-13 19:47:31'),
(7, 'admin1', 'Logout do sistema', 'Usuário saiu do painel administrativo', '::1', '2025-11-13 19:49:56'),
(8, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #6 para Adailton vieira da silva (aa) - 2025-11-13 02:30 até 2025-11-13 04:29', '::1', '2025-11-13 20:02:58'),
(9, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #7 para testeeeee (adad) - 2025-11-24 08:00 até 2025-11-24 09:29', '::1', '2025-11-13 20:19:53'),
(10, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #8 para Adailton vieira da silva (TI) - 2025-11-18 05:00 até 2025-11-19 13:59', '::1', '2025-11-13 20:52:46'),
(11, 'admin1', 'Logout do sistema', 'Usuário saiu do painel administrativo', '::1', '2025-11-13 21:23:21'),
(12, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #9 para Secretaria de Estado da Saúde / HEJ (66) - 2025-11-19 02:00 até 2025-11-19 03:29', '::1', '2025-11-13 21:24:43'),
(13, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #10 para rodri (66) - 2025-11-13 01:30 até 2025-11-13 03:59', '::1', '2025-11-13 21:42:25'),
(14, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #11 para Secretaria de Estado da Saúde / HEJ (abion) - 2025-11-21 18:30 até 2025-11-21 20:29', '::1', '2025-11-13 21:43:06'),
(15, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #12 para AU!PLAYyyyyyyyyy (teste) - 2025-11-24 23:00 até 2025-11-24 23:59', '::1', '2025-11-13 21:46:14'),
(16, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #13 para AU!PLAYyyyyyyyyy (teste) - 2025-11-18 03:00 até 2025-11-18 04:29', '::1', '2025-11-13 21:47:37'),
(17, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #14 para Adailton vieira da silva (teste) - 2025-11-17 02:00 até 2025-11-17 05:59', '::1', '2025-11-13 21:48:39'),
(18, 'Sistema', 'Novo agendamento solicitado', 'Agendamento #15 para AU!PLAYyyyyyyyyy (TI) - 2025-12-25 04:30 até 2025-12-25 04:59', '::1', '2025-11-13 21:51:31'),
(19, 'admin1', 'Logout do sistema', 'Usuário saiu do painel administrativo', '::1', '2025-11-14 02:34:15'),
(20, 'admin2', 'Logout do sistema', 'Usuário saiu do painel administrativo', '::1', '2025-11-14 02:54:50'),
(21, 'admin2', 'Logout do sistema', 'Usuário saiu do painel administrativo', '::1', '2025-11-14 02:55:04'),
(22, 'eu', 'Logout do sistema', 'Usuário saiu do painel administrativo', '::1', '2025-11-14 02:56:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$pifhYC.wtLDmqmZcauG0P.M/wYxW6QBu.mE3ff7o5YtgTT6TOajya', '2025-11-11 01:13:57'),
(5, 'admin1', '$2y$10$.EJqdu98AA3fCuA8XtUc1eHJCb.oglSRo2XIjxhROlO.5.SD9TDR2', '2025-11-13 19:45:35'),
(6, 'admin2', '$2y$10$eBZOEvLnZVHpK6l6mWnZbukD9Gc0Cvdz8KEOjw0dBbD11T816FbTi', '2025-11-14 02:51:46'),
(4294967295, 'Adailton', '$2y$10$uo5UHd7naiugeFZnstkerO0PkMNO4QHZCaGo14DmxMVVEJnaHADHG', '2025-11-14 02:55:57');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_inicio` (`data_inicio`),
  ADD KEY `status` (`status`);

--
-- Índices de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chave` (`chave`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `created_at` (`created_at`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4294967296;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
