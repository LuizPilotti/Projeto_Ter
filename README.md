# 🍔 FastPoint - Sistema de Gestão para Fast Food

Projeto final desenvolvido para a disciplina de Desenvolvimento de Sistemas. O objetivo é fornecer uma solução Full Stack robusta para gerenciamento de produtos, estoque e autenticação de usuários em uma rede de fast food.

## 🚀 Tecnologias Utilizadas

* **Backend:** C# .NET 9.0 (Web API)
* **Banco de Dados:** PostgreSQL 16 (Container Docker)
* **Frontend:** HTML5, CSS3 (Bootstrap 5) e JavaScript Vanilla
* **Arquitetura:** Clean Architecture (Domain, Application, Infrastructure, API)
* **ORM:** Entity Framework Core
* **Segurança:** Autenticação JWT (JSON Web Token)
* **Testes:** xUnit (Testes Unitários de Domínio)
* **Documentação:** Swagger (OpenAPI)

## ⚙️ Funcionalidades

* ✅ **Login Seguro:** Autenticação de gerentes e atendentes via Token JWT.
* ✅ **Gestão de Produtos:** CRUD completo (Criar, Ler, Atualizar, Deletar).
* ✅ **Controle de Estoque:** Validação de regras de negócio (ex: estoque não pode ser negativo).
* ✅ **Interface Web:** Dashboard responsivo para operação do sistema.

---

## 🔧 Como Rodar o Projeto

### Pré-requisitos
* [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado e rodando.
* [.NET SDK 9.0](https://dotnet.microsoft.com/en-us/download/dotnet/9.0) instalado.
* VS Code (com extensão "Live Server" recomendada).

### Passo a Passo

#### 1. Subir o Banco de Dados
Na raiz do projeto, execute o comando para subir o container do PostgreSQL:
```bash
docker-compose up -d

2. Configurar o Banco (Primeira vez)
Aplique as tabelas no banco de dados:
dotnet ef database update -p FastPoint.Infrastructure/FastPoint.Infrastructure.csproj -s FastPoint.API/FastPoint.API.csproj

3. Iniciar a API (Backend)
cd FastPoint.API
dotnet run

A API estará rodando em: http://localhost:5010 O Swagger estará em: http://localhost:5010/swagger

4. Iniciar o Site (Frontend)
1. abra a pasta em FastPoint-Web no VS Code.

2. Clique com o botão direito no arquivo index.html.

3. Selecione "Open with Live Server".

🧪 Testes Automatizados
Para rodar os testes de unidade e validar as regras de negócio:
dotnet test

👥 Autores
- Eduardo Ferreira Ramos

- Luiz Fernando Pilotti
