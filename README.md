# DeepEyes - IA para Pentest & Red Team

<p align="center">
  <img src="public/logo.png" alt="DeepEyes Logo" width="200">
</p>

Sistema profissional de IA especializada em **Pentest** e **Red Team**, construído com Laravel 11 e integração com DeepSeek API via OpenRouter.

## 🚀 Demo

**URL:** https://deepeyes.online

## ✨ Funcionalidades

### 💬 Chat com IA Especializada
- Respostas em tempo real com **streaming**
- Contexto de memória por sessão
- Múltiplas sessões organizadas por alvo/domínio
- Formatação de código com syntax highlighting
- Botão de **copiar código** em blocos de código
- Suporte a Markdown completo

### � Scannear de Vulnerabilidades
- Interface visual para análise de alvos
- Integração com ferramentas de reconhecimento
- Resultados formatados e exportáveis

### 💻 Terminal Interativo
- **Execução de comandos reais** no servidor
- **Whitelist de segurança** - apenas comandos permitidos
- **Rate limiting** - 10 comandos/minuto, 60 comandos/hora
- **Logging completo** - todos os comandos são auditados
- Comandos disponíveis:
  - DNS/WHOIS: `whois`, `dig`, `nslookup`, `host`
  - Rede: `ping`, `traceroute`, `curl`
  - Scanner: `nmap`, `nikto`, `gobuster`, `wpscan`, `subfinder`
- Histórico de comandos com navegação por setas
- Exportação de sessão do terminal
- Integração com IA para análise de resultados

### ✅ Checklist de Pentest
- OWASP Top 10 interativo
- Tracking de progresso por categoria
- Exportação de relatório

### 📊 Relatórios
- Geração de relatórios profissionais
- Templates customizáveis
- Exportação em múltiplos formatos

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

## 🔐 Segurança

- **Autenticação obrigatória** em todas as ferramentas
- **Prompt System protegido**: Nunca exposto ao frontend
- **Rate limiting**: Por plano do usuário e por ferramenta
- **Terminal com whitelist**: Apenas comandos seguros permitidos
- **Logging de auditoria**: Todos os comandos do terminal são logados
- **Content moderation**: Bloqueio de padrões maliciosos
- **Prompt injection protection**: Detecção de tentativas de bypass
- **User banning**: Sistema de banimento por abuso

## 🏗️ Arquitetura

```
app/
├── Actions/DeepSeek/          # Actions para chat
├── DTO/                       # Data Transfer Objects
├── Http/
│   ├── Controllers/Api/       # Controllers da API
│   │   ├── AuthController     # Login/Registro
│   │   ├── ChatController     # Chat/Sessões
│   │   ├── AdminController    # Painel Admin
│   │   └── TerminalController # Terminal com whitelist
│   └── Middleware/
│       ├── EnsureAuthenticated # Proteção de rotas
│       ├── EnsureUserIsAdmin   # Proteção admin
│       ├── EnsureUserNotBanned
│       └── RateLimitAI         # Limite por plano
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
- **3D Effects:** Three.js (partículas na landing)

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

### Ferramentas necessárias no servidor (para Terminal):
```bash
apt update && apt install -y whois dnsutils iputils-ping traceroute curl nmap
# Opcionais para pentest avançado:
apt install -y nikto
# gobuster, subfinder, wpscan - instalar via Go ou gems
```

### Atualização rápida:
```bash
cd /var/www/deepeyes && git pull origin main && php artisan view:clear && php artisan cache:clear
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

## 📱 Páginas do Sistema

| Página | Rota | Descrição |
|--------|------|-----------|
| Landing | `/` | Página inicial com apresentação |
| Chat | `/chat` | Interface de chat com IA |
| Scanner | `/scanner` | Scanner de vulnerabilidades |
| Terminal | `/terminal` | Terminal interativo |
| Checklist | `/checklist` | Checklist OWASP |
| Reports | `/reports` | Geração de relatórios |
| Docs | `/docs` | Documentação |
| Profile | `/profile` | Perfil do usuário |

> ⚠️ Todas as páginas exceto `/` e `/docs` requerem autenticação.

## � CIontribuição

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## � Roadrmap

### ✅ Implementado
- [x] Chat com IA e streaming
- [x] Sistema de autenticação
- [x] Scanner de vulnerabilidades
- [x] Terminal interativo com whitelist
- [x] Rate limiting e logging
- [x] Checklist OWASP
- [x] Painel admin

### 🔜 Próximas Features
- [ ] Multi-modelo (GPT-4, Claude)
- [ ] Integração Stripe para pagamentos
- [ ] 2FA/MFA
- [ ] Workspaces de equipe
- [ ] Relatórios PDF profissionais

## � LiceAnça

Este projeto é privado e de uso restrito.

## 👨‍💻 Autor

**Zuckszinho** - Desenvolvido para profissionais de segurança.

---

<p align="center">
  <strong>🔴 DeepEyes - O olho que tudo vê 👁️</strong>
</p>
