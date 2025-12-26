# DeepEyes - IA para Pentest & Red Team

<p align="center">
  <img src="public/logo.png" alt="DeepEyes Logo" width="200">
</p>

Sistema profissional de IA especializada em **Pentest** e **Red Team**.

**URL:** https://deepeyes.online

## ✨ Funcionalidades

### 💬 Chat com IA Especializada
- Respostas em tempo real com **streaming**
- Contexto de memória por sessão
- Múltiplas sessões organizadas por alvo/domínio
- Formatação de código com syntax highlighting
- Botão de **copiar código** em blocos de código
- Suporte a Markdown completo
- **Terminal integrado** - Execute comandos diretamente no chat (plano Full Attack)

### 🔍 Scanner de Vulnerabilidades (Real)
- **Análise de Headers HTTP** - HSTS, CSP, X-Frame-Options, X-Content-Type-Options, etc.
- **Verificação SSL/TLS** - Certificado, validade, emissor, dias restantes
- **Análise DNS** - Registros A, AAAA, MX, NS, SPF, DMARC
- **Detecção de Tecnologias** - WordPress, Laravel, React, Vue, jQuery, Bootstrap, etc.
- Rate limiting: 10 scans por hora

### 💻 Terminal Interativo
- **Execução de comandos reais** no servidor
- **Disponível apenas para plano Full Attack** ou Admin
- **Whitelist de segurança** - apenas comandos permitidos
- **Rate limiting** - 10 comandos/minuto, 60 comandos/hora
- **Proteção contra sobrecarga**:
  - Máximo 500 caracteres por comando
  - Máximo 20 argumentos
  - Máximo 5 alvos por comando
  - Range de IP máximo /24 (256 hosts)
  - Range de portas máximo 1000
  - Bloqueio de `-p-` (scan completo de portas)
  - Limite de `--min-rate 500` no nmap
  - Limite de 20 threads no gobuster
  - Bloqueio de wordlists grandes (rockyou, big.txt)
  - Apenas 1 nikto por vez por usuário
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
- **Usuários em tempo real**: atualização automática a cada 5 segundos
- **Indicador de online**: bolinha verde para usuários ativos
- **Estatísticas ao vivo**: Total, Online, Ativos, Banidos
- **Visualização de sessões**: ver conversas dos usuários
- **Gerenciamento de planos**: editar preços e limites

## 💰 Planos

| Plano | Recursos |
|-------|----------|
| **Pentest** (Free) | Chat com IA, Scanner, Checklist |
| **Red Team** | Tudo do Pentest + Relatórios avançados |
| **Full Attack** | Tudo + **Terminal Integrado** |

## 🔐 Segurança

- **Autenticação obrigatória** em todas as ferramentas
- **Prompt System protegido**: Nunca exposto ao frontend
- **Rate limiting**: Por plano do usuário e por ferramenta
- **Terminal com whitelist**: Apenas comandos seguros permitidos
- **Proteção contra comandos grandes**: Limites de caracteres, argumentos e alvos
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
│   │   ├── TerminalController # Terminal com whitelist
│   │   └── ScannerController  # Scanner real
│   └── Middleware/
│       ├── EnsureAuthenticated # Proteção de rotas
│       ├── EnsureUserIsAdmin   # Proteção admin
│       ├── EnsureUserNotBanned
│       ├── CheckTerminalAccess # Acesso ao terminal por plano
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
- **Database:** SQLite (ou MySQL/Supabase)
- **Frontend:** Blade, TailwindCSS (compilado localmente), Alpine.js
- **IA:** DeepSeek via OpenRouter API
- **Auth:** Laravel Sanctum
- **Icons:** Font Awesome 6
- **3D Effects:** Three.js (partículas na landing)

## ⚡ Otimizações de Performance

- **Tailwind CSS compilado localmente** (~50KB vs ~3MB do CDN)
- **Fontes Google com carregamento assíncrono**
- **Font Awesome com carregamento assíncrono**
- **Preconnect para CDNs externos**
- **Cache do Laravel otimizado**

## 📦 Instalação Local

```bash
# Clone o repositório
git clone https://github.com/Shelby3344/deepeyers.git
cd deepeyers

# Instale dependências
composer install
npm install

# Compile o Tailwind CSS
npm run build

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

### Ferramentas necessárias no servidor (para Terminal):
```bash
apt update && apt install -y whois dnsutils iputils-ping traceroute curl nmap
# Opcionais para pentest avançado:
apt install -y nikto
# gobuster, subfinder, wpscan - instalar via Go ou gems
```

### Deploy rápido:
```bash
bash deploy.sh
```

### Atualização manual:
```bash
cd /var/www/deepeyes && git pull origin main && php artisan cache:clear && php artisan view:clear
chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache
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
| Scanner | `/scanner` | Scanner de vulnerabilidades (real) |
| Terminal | `/terminal` | Terminal interativo (Full Attack) |
| Checklist | `/checklist` | Checklist OWASP |
| Reports | `/reports` | Geração de relatórios |
| Docs | `/docs` | Documentação |
| Profile | `/profile` | Perfil do usuário + Admin |

> ⚠️ Todas as páginas exceto `/` e `/docs` requerem autenticação.

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

### 🔜 Próximas Features
- [ ] Multi-modelo (GPT-4, Claude)
- [ ] Integração Stripe para pagamentos
- [ ] 2FA/MFA
- [ ] Workspaces de equipe
- [ ] Relatórios PDF profissionais
- [ ] Port Scan no Scanner

### ✅ Implementado Recentemente
- [x] **Scanner Real** - Headers, SSL, DNS, Tecnologias (não mais fictício)
- [x] **Terminal restrito por plano** - Apenas Full Attack ou Admin
- [x] **Proteção contra comandos grandes** - Limites de caracteres, argumentos, alvos
- [x] **Usuários em tempo real no Admin** - Atualização a cada 5 segundos
- [x] **Indicador de online** - Bolinha verde para usuários ativos
- [x] **Tailwind CSS compilado** - Performance otimizada (~50KB)
- [x] Terminal integrado no Chat
- [x] Seleção múltipla e exclusão em massa de usuários no admin
- [x] Validação de email (apenas provedores confiáveis)
- [x] Validação de senha forte
- [x] Terminal interativo com whitelist de comandos
- [x] Rate limiting e logging de comandos

## 📄 Licença

Este projeto é privado e de uso restrito.

## 👨‍💻 Autor

**Zuckszinho** - Desenvolvido para profissionais de segurança.

---
