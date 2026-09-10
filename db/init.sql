SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuarios_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS casos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    especie VARCHAR(80) NOT NULL,
    nome_pet VARCHAR(120) NOT NULL,
    nome_tutor VARCHAR(150) NOT NULL,
    contato VARCHAR(40) NOT NULL,
    local VARCHAR(190) NOT NULL,
    data_cadastro DATE NOT NULL,
    descricao TEXT NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    raca VARCHAR(80) NULL,
    porte ENUM('Pequeno', 'Médio', 'Grande') NULL,
    cor VARCHAR(60) NULL,
    sexo ENUM('Macho', 'Fêmea') NULL,
    status ENUM('ativo', 'encontrado') NOT NULL DEFAULT 'ativo',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_casos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_casos_usuario (usuario_id),
    INDEX idx_casos_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS adocoes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    especie VARCHAR(80) NOT NULL,
    nome_pet VARCHAR(120) NOT NULL,
    idade VARCHAR(40) NOT NULL,
    descricao TEXT NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    raca VARCHAR(80) NULL,
    porte ENUM('Pequeno', 'Médio', 'Grande') NULL,
    cor VARCHAR(60) NULL,
    sexo ENUM('Macho', 'Fêmea') NULL,
    status ENUM('disponivel', 'adotado') NOT NULL DEFAULT 'disponivel',
    data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_adocoes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_adocoes_usuario (usuario_id),
    INDEX idx_adocoes_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
