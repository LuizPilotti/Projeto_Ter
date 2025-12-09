# 🧱 Sistema de Login + Gerenciador Inteligente de Tarefas (PHP + MySQL)

Este projeto é um sistema completo contendo:

- 🔐 Autenticação de usuários (login e registro)
- 🗃 Armazenamento seguro com **criptografia de email e senha**
- 📡 API REST para login e tarefas
- 📋 Painel de tarefas com CRUD completo (criar, listar, atualizar e excluir)
- 🎨 Interface moderna com Glassmorphism
- 🐳 Ambiente Docker pronto para rodar

---

## 🚀 1. Tecnologias Utilizadas

- **PHP 8+**
- **MySQL 5.7+**
- **Apache**
- **JavaScript (Fetch API)**
- **CSS puro**
- **Docker / Docker Compose**

---

## 📦 2. Estrutura do Projeto

testepilotti/
└── testepilotti/
├── docker-compose.yml
├── Dockerfile
├── config.php
├── index.php
├── .htaccess
│
├── api/
│ ├── login.php
│ ├── usuario.php
│ └── api_tarefas.php
│
├── classes/
│ └── mysql.php
│
├── paginas/
│ ├── login.php
│ ├── registro.php
│ └── home.php
│
└── style/
└── style.css


---

## 🐳 3. Como Executar com Docker (Modo Recomendado)

### ✔ Passo 1 — Acessar a pasta

cd testepilotti/testepilotti

### ✔ Passo 2 — Inicializar o ambiente
docker-compose up -d

### ✔ Passo 3 — Acessar o sistema
http://localhost:8080

### 4. Configuração do Banco de Dados

## O projeto utiliza MySQL e precisa dessas duas tabelas:

### 🧩 Tabela usuarios
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email TEXT NOT NULL,
    senha TEXT NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);


## 🔒 O email e a senha são armazenados criptografados usando as funções do config.php:
criptografar($texto);
descriptografar($texto);

## 🧩 Tabela tarefas
CREATE TABLE tarefas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    prazo DATETIME NULL,
    prioridade ENUM('baixa','media','alta') DEFAULT 'media',
    status ENUM('pendente','progresso','concluida') DEFAULT 'pendente',
    meta INT DEFAULT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

## 🔐 5. Autenticação (API de Login)
# Endpoint: /api/login.php


# **Envia (POST)**
# **email (puro)**
# **senha (pura)**

## O backend faz:

**criptografia**
**comparação**
**retorno do usuário se OK**

## Resposta
{
  "sucesso": true,
  "usuario": {
     "id": 1,
     "email": "criptografado..."
  }
}

## 📝 6. API de Tarefas

**Criar tarefa**
POST → /api/api_tarefas.php?action=criar

**Listar tarefas**
GET → /api/api_tarefas.php?action=listar&id_usuario=1

**Atualizar status**
POST → /api/api_tarefas.php?action=status

**Excluir**
POST → /api/api_tarefas.php?action=excluir

## 💻 7. Interface (home.php)

**O painel mostra:**

- **Formulário para criar novas tarefas**
- **Lista de tarefas existentes**
- **Atualização dinâmica via JavaScript**
- **Exclusão instantânea**
- **Estilo moderno com glassmorphism**

## 🎨 8. Visual Moderno (Glass UI)

**O CSS inclui:**

- **Fundo translúcido**
- **Bordas suaves**
- **Cards de tarefas coloridos (pendente, progresso, concluída)**
- **Animações leves**
- **Layout clean estilo SaaS**

## 🔐 9. Criptografia

**O sistema utiliza duas funções globais:**

- **criptografar($texto)**
- **descriptografar($texto)**

**Aplicadas em:**

- **Registro de usuário**
- **Login**
- **Email**
- **Senha**

https://teste.naturaldobem.com.br
