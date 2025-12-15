#  DeepEyes - IA para Pentest & Red Team

<p align="center">
  <img src="public/logo.png" alt="DeepEyes Logo" width="200">
</p>

Sistema profissional de IA especializada em **Pentest** e **Red Team**, construído com Laravel 11 e integração com DeepSeek API via OpenRouter.

## 🚀 Demo

**URL:** http://3.134.81.123

## ✨ Funcionalidades

### 💬 Chat com IA Especializada
- Respostas em tempo real com **streaming**
- Contexto de memória por sessão
- Múltiplas sessões organizadas por alvo/domínio
- Formatação de código com syntax highlighting
- Botão de **copiar código** em blocos de código
- Suporte a Markdown completo

### 👥 Sistema de Usuários
- Registro e login com autenticação segura
- Upload de avatar personalizado
- Perfil editável (nome, email, senha)
- Sistema de planos com limites de requisições


### 🛡️ Painel Admin
- **Dashboard** com estatísticas
- **Gerenciamento de usuários**: criar, editar, banir, deletar
- **Visualização de sessões**: ver conversas dos usuários
- **Gerenciamento de planos**: editar preços e limites
- Atribuir planos aos usuários
- Preview de avatares

## 🔐 Segurança

- **Prompt System protegido**: Nunca exposto ao frontend
- **Rate limiting**: Por plano do usuário
- **Content moderation**: Bloqueio de padrões maliciosos
- **Prompt injection protection**: Detecção de tentativas de bypass
- **User banning**: Sistema de banimento por abuso
- **Audit logging**: Registro de atividades suspeitas

## 🏗️ Arquitetura

```
app/
├── Actions/DeepSeek/          # Actions para chat
├── DTO/                       # Data Transfer Objects
├── Http/
│   ├── Controllers/Api/       # Controllers da API
│   │   ├── AuthController     # Login/Registro
│   │   ├── ChatController     # Chat/Sessões
│   │   └── AdminController    # Painel Admin
│   └── Middleware/
│       ├── EnsureUserIsAdmin  # Proteção admin
│       ├── EnsureUserNotBanned
│       └── RateLimitAI        # Limite por plano
├── Models/
│   ├── User                   # Usuários
│   ├── ChatSession            # Sessões de chat
│   ├── ChatMessage            # Mensagens
│   └── Plan                   # Planos
└── Services/
    └── DeepSeekService        # Integração com IA
```

## 🛠️ Tecnologias

- **Backend:** Laravel 11, PHP 8.2+
- **Database:** SQLite (ou MySQL)
- **Frontend:** Blade, TailwindCSS, Alpine.js
- **IA:** DeepSeek via OpenRouter API
- **Auth:** Laravel Sanctum
- **Icons:** Font Awesome 6

## 📦 Instalação Local

```bash
# Clone o repositório
git clone https://github.com/Shelby3344/deepeyers.git
cd deepeyers

# Instale dependências
composer install

# Configure ambiente
cp .env.example .env
php artisan key:generate

# Configure o .env
# DB_CONNECTION=sqlite
# DEEPSEEK_API_KEY=sua_chave_openrouter
# DEEPSEEK_ENDPOINT=https://openrouter.ai/api/v1/chat/completions
# DEEPSEEK_MODEL=deepseek/deepseek-chat

# Crie o banco
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Inicie o servidor
php artisan serve
```

## 🌐 Deploy em Produção

Veja o guia completo em [DEPLOY_HOSTINGER.md](DEPLOY_HOSTINGER.md)

### Resumo:
```bash
# No servidor Ubuntu
apt install php8.2 php8.2-fpm nginx composer git

# Clone e configure
cd /var/www
git clone https://github.com/Shelby3344/deepeyers.git deepeyes
cd deepeyes
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Configure o .env com suas credenciais

# Banco e migrações
touch database/database.sqlite
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

# Permissões
chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache

# Configure Nginx e reinicie
```

## 🔑 Configuração OpenRouter

1. Crie uma conta em [openrouter.ai](https://openrouter.ai)
2. Gere uma API Key em [openrouter.ai/keys](https://openrouter.ai/keys)
3. Configure no `.env`:

```env
DEEPSEEK_API_KEY=sk-or-v1-sua_chave_aqui
DEEPSEEK_ENDPOINT=https://openrouter.ai/api/v1/chat/completions
DEEPSEEK_MODEL=deepseek/deepseek-chat
```

## 📱 Screenshots

### Tela de Chat
- Interface dark mode profissional
- Sidebar com sessões organizadas
- Streaming de respostas em tempo real
- Blocos de código com botão de copiar

### Painel Admin
- Gerenciamento completo de usuários
- Edição de planos inline
- Visualização de todas as sessões
- Estatísticas do sistema

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📄 Licença

Este projeto é privado e de uso restrito.

## 👨‍💻 Autor

**Zuckszinho** - Desenvolvido para profissionais de segurança.

---

<p align="center">
  <strong>🔴 DeepEyes - O olho que tudo vê 👁️</strong>
</p>
