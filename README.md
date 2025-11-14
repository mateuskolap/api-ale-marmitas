# API Alê Marmitas

API REST desenvolvida em Symfony 7.3 para gerenciamento de pedidos, clientes, produtos e pagamentos.

## 📋 Requisitos

- PHP 8.2 ou superior
- Composer
- MySQL 8.0 ou superior

## 🚀 Como Rodar o Projeto

### 1. Clonar o Repositório

```bash
git clone https://github.com/mateuskolap/api-ale-marmitas.git
cd api-ale-marmitas
```

### 2. Instalar Dependências

```bash
composer install
```

### 3. Configurar Variáveis de Ambiente

Copie o arquivo `.env` para `.env.local` e configure as variáveis:

```bash
cp .env .env.local
```

Edite o arquivo `.env.local` e configure:

```env
# Banco de dados
DATABASE_URL="mysql://seu_usuario:sua_senha@127.0.0.1:3306/nome_banco?serverVersion=8.0.32&charset=utf8mb4"

# JWT
JWT_PASSPHRASE=sua_senha_secreta

# Usuário admin
ADMIN_USER_EMAIL=seu_email@dominio.com
ADMIN_USER_PASSWORD=SuaSenha@123
```

### 4. Criar o Banco de Dados

```bash
symfony console doctrine:database:create
```

### 5. Executar as Migrações

```bash
symfony console doctrine:migrations:migrate
```

### 6. Gerar Chaves JWT

```bash
symfony console lexik:jwt:generate-keypair
```

As chaves serão geradas automaticamente em `config/jwt/`.

### 7. Carregar Dados Iniciais (Opcional)

Para criar o usuário admin configurado no `.env.local`:

```bash
symfony console doctrine:fixtures:load
```

### 8. Iniciar o Servidor

```bash
symfony serve --no-tls
```

A API estará disponível em: `http://localhost:8000`

## 🔑 Autenticação

A API utiliza JWT (JSON Web Token) para autenticação.

### Login

**Endpoint:** `POST /api/v1/auth/login`

**Body:**

```json
{
    "username": "seu_email@dominio.com",
    "password": "SuaSenha@123"
}
```

**Resposta:**

```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "refresh_token_expiration": "1765753302"
}
```

### Usando o Token

Adicione o token no header das requisições:

```
Authorization: eyJ0eXAiOiJKV1QiLCJhbGc...
```

### Refresh Token

**Endpoint:** `POST /api/v1/auth/refresh`

**Body:**

```json
{
    "refresh_token": "def50200...",
    "refresh_token_expiration": "1765753351"
}
```

## 📚 Endpoints da API

### Usuários

- `GET /api/v1/users` - Listar usuários
- `POST /api/v1/users` - Criar usuário
- `GET /api/v1/users/{id}` - Buscar usuário
- `PUT /api/v1/users/{id}` - Atualizar usuário
- `DELETE /api/v1/users/{id}` - Deletar usuário

### Clientes

- `GET /api/v1/customers` - Listar clientes
- `POST /api/v1/customers` - Criar cliente
- `GET /api/v1/customers/{id}` - Buscar cliente
- `PUT /api/v1/customers/{id}` - Atualizar cliente
- `DELETE /api/v1/customers/{id}` - Deletar cliente

### Produtos

- `GET /api/v1/products` - Listar produtos
- `POST /api/v1/products` - Criar produto
- `GET /api/v1/products/{id}` - Buscar produto
- `PUT /api/v1/products/{id}` - Atualizar produto
- `DELETE /api/v1/products/{id}` - Deletar produto

### Pedidos

- `GET /api/v1/orders` - Listar pedidos
- `POST /api/v1/orders` - Criar pedido
- `GET /api/v1/orders/{id}` - Buscar pedido
- `PUT /api/v1/orders/{id}` - Atualizar pedido
- `DELETE /api/v1/orders/{id}` - Deletar pedido
- `PATCH /api/v1/orders/{id}/status` - Atualizar status do pedido

### Pagamentos

- `GET /api/v1/payments` - Listar pagamentos
- `POST /api/v1/payments` - Criar pagamento
- `GET /api/v1/payments/{id}` - Buscar pagamento
- `PUT /api/v1/payments/{id}` - Atualizar pagamento
- `DELETE /api/v1/payments/{id}` - Deletar pagamento

## 📦 Tecnologias Utilizadas

- **Symfony 7.3** - Framework PHP
- **Doctrine ORM** - Mapeamento objeto-relacional
- **API Platform** - Criação de APIs REST
- **Lexik JWT** - Autenticação via JWT
- **KnpPaginatorBundle** - Paginação
- **Nelmio CORS** - Configuração de CORS

## 📄 Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo [LICENSE](LICENSE) para mais detalhes.

MIT License - você pode usar, copiar, modificar e distribuir este projeto livremente.

