# 💸 Finance App

O **Finance App** é uma aplicação web desenvolvida em Laravel que simula o controle de compra e venda de ações. Usuários autenticados podem consultar cotações, realizar transações financeiras e acompanhar seu histórico e carteira com uma interface moderna e responsiva.

---

## 🚀 Funcionalidades

- 🔍 Consultar cotações de ações em tempo real
- 💰 Comprar e vender ações com validação de saldo
- 📈 Visualizar carteira de investimentos com saldo atualizado
- 🕒 Acompanhar histórico de transações
- 👤 Gerenciar perfil e atualizar dados
- ✅ Sistema de autenticação com proteção de rotas

---

## 🛠️ Tecnologias Utilizadas

- **PHP** com Laravel
- **Blade** + **Tailwind CSS**
- **Alpine.js** para interações leves no front-end
- **MySQL** como banco de dados
- **BRAPI.dev** como fonte de dados do mercado financeiro

---

## 📷 Screenshots

### Página de consulta
![Consulta](screenshots/consulta.png)

### Carteira
![Carteira](screenshots/carteira.png)

### Histórico de transações
![Histórico](screenshots/historico.png)

---

## ⚙️ Instalação e Execução

```bash
# Clonar o repositório
git clone https://github.com/seu-usuario/finance-app.git
cd finance-app

# Instalar dependências PHP
composer install

# Instalar dependências do front
npm install && npm run build

# Copiar e configurar o .env
cp .env.example .env
php artisan key:generate

# Configurar banco e rodar migrações
php artisan migrate

# Iniciar servidor
php artisan serve
