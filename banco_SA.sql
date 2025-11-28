-- =====================================================
-- BANCO DE DADOS - SISTEMA DE GERENCIAMENTO DE TRENS
-- =====================================================
-- Este script cria todas as tabelas necessárias para o sistema
-- Desenvolvido para XAMPP/MySQL

-- Remove banco se existir (cuidado em produção!)
DROP DATABASE IF EXISTS banco_SA;

-- Cria o banco de dados
CREATE DATABASE banco_SA CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco
USE banco_SA;

-- =====================================================
-- TABELA: usuarios
-- =====================================================
-- Armazena todos os usuários do sistema (admin e comum)
-- Admin pode cadastrar usuários comuns
CREATE TABLE usuarios (
    id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(100) NOT NULL,
    email_usuario VARCHAR(100) NOT NULL UNIQUE,
    senha_usuario VARCHAR(255) NOT NULL,
    cpf_usuario CHAR(11) NOT NULL UNIQUE,
    tipo_usuario ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    status_usuario ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email_usuario),
    INDEX idx_tipo (tipo_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: sensores
-- =====================================================
-- Armazena leituras dos sensores dos trens
-- Tipos: presenca (ultrassonico), umidade_temperatura (DHT11), iluminacao (LDR)
CREATE TABLE sensores (
    id_sensor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo_sensor ENUM('presenca', 'umidade_temperatura', 'iluminacao') NOT NULL,
    
    -- Campos para sensor de presença (ultrassônico)
    -- Valores: 0 = nada detectado, 1 = objeto detectado
    presenca_detectada TINYINT(1) DEFAULT NULL,
    
    -- Campos para sensor DHT11 (umidade e temperatura)
    temperatura DECIMAL(5,2) DEFAULT NULL,  -- Ex: 25.50°C
    umidade DECIMAL(5,2) DEFAULT NULL,      -- Ex: 65.30%
    
    -- Campo para sensor LDR (iluminação)
    nivel_iluminacao INT DEFAULT NULL,      -- Valores de 0 a 255
    
    -- Campos comuns
    descricao VARCHAR(200) DEFAULT NULL,
    timestamp_leitura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_tipo (tipo_sensor),
    INDEX idx_timestamp (timestamp_leitura)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: manutencoes
-- =====================================================
-- Registra manutenções realizadas nos trens
CREATE TABLE manutencoes (
    id_manutencao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    status_manutencao ENUM('pendente', 'em_andamento', 'concluida', 'cancelada') NOT NULL DEFAULT 'pendente',
    data_inicio DATE NOT NULL,
    data_termino DATE DEFAULT NULL,
    comentario TEXT DEFAULT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_status (status_manutencao),
    INDEX idx_data_inicio (data_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: rotas
-- =====================================================
-- Armazena informações sobre rotas dos trens
-- A duração será calculada via PHP (horario_chegada - horario_saida)
CREATE TABLE rotas (
    id_rota INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    local_saida VARCHAR(100) NOT NULL,
    local_destino VARCHAR(100) NOT NULL,
    horario_saida TIME NOT NULL,
    horario_chegada TIME NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_saida (local_saida),
    INDEX idx_destino (local_destino)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: trens
-- =====================================================
-- Armazena informações sobre os trens do sistema
CREATE TABLE trens (
    id_trem INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo_trem ENUM('transporte', 'carga') NOT NULL,
    carga_trem VARCHAR(100) DEFAULT NULL,
    status_trem ENUM('operante', 'em_manutencao', 'fora_de_servico') NOT NULL DEFAULT 'operante',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_tipo (tipo_trem),
    INDEX idx_status (status_trem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DADOS INICIAIS DE EXEMPLO
-- =====================================================
INSERT INTO trens (tipo_trem, carga_trem, status_trem) VALUES
('transporte', NULL, 'operante'),
('transporte', NULL, 'operante'),
('carga', 'Minério de ferro', 'operante'),
('carga', 'Grãos', 'em_manutencao'),
('transporte', NULL, 'fora_de_servico'),
('carga', 'Combustível', 'operante');

-- =====================================================
-- TABELA: notificacoes
-- =====================================================
-- Armazena notificações do sistema
CREATE TABLE notificacoes (
    id_notificacao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo_notificacao VARCHAR(200) NOT NULL,
    gravidade ENUM('critica', 'alta', 'media', 'baixa') NOT NULL DEFAULT 'media',
    descricao_notificacao TEXT NOT NULL,
    assunto ENUM('trens', 'sensores', 'manutencao', 'rotas', 'usuarios') NOT NULL,
    lida TINYINT(1) DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_gravidade (gravidade),
    INDEX idx_assunto (assunto),
    INDEX idx_lida (lida),
    INDEX idx_criado (criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DADOS INICIAIS DE EXEMPLO
-- =====================================================
INSERT INTO notificacoes (titulo_notificacao, gravidade, descricao_notificacao, assunto) VALUES
('Sensor de temperatura alta detectado', 'alta', 'Sensor DHT11 do vagão 3 registrou temperatura de 45°C', 'sensores'),
('Manutenção preventiva agendada', 'media', 'Trem #4 está agendado para manutenção preventiva na próxima semana', 'manutencao'),
('Novo usuário cadastrado', 'baixa', 'Usuário João Silva foi cadastrado no sistema', 'usuarios'),
('Falha crítica no sistema de freios', 'critica', 'Trem #2 apresentou falha no sistema de freios - AÇÃO IMEDIATA NECESSÁRIA', 'trens');



-- =====================================================
-- DADOS INICIAIS DE TESTE
-- =====================================================

-- Insere usuário administrador padrão
-- Senha: admin123 (hash gerado com password_hash)
INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario, cpf_usuario, tipo_usuario, status_usuario) 
VALUES (
    'Administrador do Sistema',
    'admin@sistema.com',
    'admin123',  -- senha: admin123
    '00000000000',
    'admin',
    'ativo'
);

-- Insere usuário comum de teste
-- Senha: usuario123
INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario, cpf_usuario, tipo_usuario, status_usuario) 
VALUES (
    'Usuário Teste',
    'usuario@sistema.com',
    'usuario123',  -- senha: usuario123
    '11111111111',
    'usuario',
    'ativo'
);

-- Insere dados de exemplo para sensores
INSERT INTO sensores (tipo_sensor, presenca_detectada, descricao) VALUES
('presenca', 1, 'Objeto detectado na linha 1'),
('presenca', 0, 'Nada detectado na linha 2');

INSERT INTO sensores (tipo_sensor, temperatura, umidade, descricao) VALUES
('umidade_temperatura', 25.5, 65.3, 'Leitura do vagão 1'),
('umidade_temperatura', 23.8, 70.2, 'Leitura do vagão 2');

INSERT INTO sensores (tipo_sensor, nivel_iluminacao, descricao) VALUES
('iluminacao', 180, 'Iluminação da estação central'),
('iluminacao', 45, 'Iluminação do túnel norte');

-- Insere dados de exemplo para manutenções
INSERT INTO manutencoes (status_manutencao, data_inicio, data_termino, comentario) VALUES
('concluida', '2024-11-01', '2024-11-02', 'Troca de freios do trem 001'),
('em_andamento', '2024-11-03', NULL, 'Revisão geral do trem 002'),
('pendente', '2024-11-05', NULL, 'Manutenção preventiva agendada');

-- Insere dados de exemplo para rotas
INSERT INTO rotas (local_saida, local_destino, horario_saida, horario_chegada) VALUES
('Estação Central', 'Estação Norte', '08:00:00', '08:45:00'),
('Estação Norte', 'Estação Sul', '09:00:00', '10:15:00'),
('Estação Sul', 'Estação Central', '10:30:00', '11:20:00');
