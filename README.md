# SentinelAI - Sistema de IA para Pentest

Sistema profissional de IA especializada em Pentest e Red Team, construído com Laravel 11 e integração com DeepSeek API.

## 🔐 Características de Segurança

- **Prompt System protegido**: Nunca exposto ao frontend
- **Rate limiting**: Por minuto, hora e dia
- **Content moderation**: Bloqueio de padrões maliciosos
- **Prompt injection protection**: Detecção de tentativas de bypass
- **User banning**: Sistema de banimento por abuso
- **Audit logging**: Registro de atividades suspeitas

## 🏗️ Arquitetura

```
app/
├── Actions/
│   └── DeepSeek/
│       ├── CreateSessionAction.php
│       ├── SendMessageAction.php
│       └── GetSessionHistoryAction.php
├── DTO/
│   ├── ChatMessageDTO.php
│   ├── CreateSessionDTO.php
│   └── DeepSeekResponseDTO.php
├── Exceptions/
│   └── DeepSeekException.php
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php
│   │       └── ChatController.php
│   ├── Middleware/
│   │   ├── EnsureUserNotBanned.php
│   │   └── RateLimitAI.php
│   └── Requests/
│       └── Api/
│           ├── CreateSessionRequest.php
│           ├── SendMessageRequest.php
│           └── UpdateSessionRequest.php
├── Jobs/
│   ├── CleanupOldSessionsJob.php
│   └── ProcessDeepSeekMessageJob.php
├── Models/
│   ├── AbuseLog.php
│   ├── ChatMessage.php
│   ├── ChatSession.php
│   └── User.php
├── Policies/
│   └── ChatSessionPolicy.php
└── Services/
    ├── ContentModerationService.php
    └── DeepSeekService.php
```

## ⚙️ Requisitos

- PHP 8.3+
- Composer
- MySQL 8.0+ ou PostgreSQL 14+
- Redis
- Laravel 11

## 🚀 Instalação

1. **Clone o repositório**
```bash
git clone https://github.com/seu-usuario/sentinelai.git
cd sentinelai
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure as variáveis de ambiente**
```env
# Database
DB_CONNECTION=mysql
DB_DATABASE=sentinelai

# Redis
REDIS_HOST=127.0.0.1

# DeepSeek API
DEEPSEEK_API_KEY=sk-your-api-key
DEEPSEEK_ENDPOINT=https://api.deepseek.com/chat/completions
DEEPSEEK_MODEL=deepseek-chat
```

5. **Execute as migrations**
```bash
php artisan migrate
```

6. **Inicie o servidor de queue**
```bash
php artisan queue:work redis --queue=default
```

7. **Inicie o servidor**
```bash
php artisan serve
```

## 📡 API Endpoints

### Autenticação

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/api/auth/register` | Registrar novo usuário |
| POST | `/api/auth/login` | Login |
| POST | `/api/auth/logout` | Logout |
| GET | `/api/auth/me` | Dados do usuário atual |

### Chat

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/chat/sessions` | Listar sessões |
| POST | `/api/chat/sessions` | Criar sessão |
| GET | `/api/chat/sessions/{id}` | Ver sessão com mensagens |
| PUT | `/api/chat/sessions/{id}` | Atualizar sessão |
| DELETE | `/api/chat/sessions/{id}` | Deletar sessão |
| POST | `/api/chat/sessions/{id}/messages` | Enviar mensagem (sync) |
| POST | `/api/chat/sessions/{id}/messages/async` | Enviar mensagem (async) |
| GET | `/api/chat/sessions/{id}/status` | Status da mensagem async |
| GET | `/api/chat/profiles` | Perfis disponíveis |

## 📋 Exemplos de Request/Response

### Criar Sessão

**Request:**
```bash
curl -X POST http://localhost:8000/api/chat/sessions \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title": "Auditoria de Segurança", "profile": "pentest"}'
```

**Response:**
```json
{
  "message": "Session created successfully",
  "data": {
    "id": "9c7f8e6d-5a4b-3c2d-1e0f-123456789abc",
    "title": "Auditoria de Segurança",
    "profile": "pentest",
    "is_active": true,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### Enviar Mensagem

**Request:**
```bash
curl -X POST http://localhost:8000/api/chat/sessions/{session_id}/messages \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"message": "Explique as vulnerabilidades do OWASP Top 10"}'
```

**Response:**
```json
{
  "data": {
    "message": {
      "id": "abc123-def456",
      "role": "assistant",
      "content": "📌 Vulnerabilidade: A01:2021 – Broken Access Control\n📍 Vetor de ataque (conceitual): ...",
      "created_at": "2024-01-15T10:31:00Z"
    },
    "usage": {
      "prompt_tokens": 150,
      "completion_tokens": 500,
      "total_tokens": 650
    }
  }
}
```

## 🎭 Perfis de IA

### SentinelAI (pentest)
- Modo defensivo
- Foco em identificação e mitigação
- Padrão OWASP
- Tom profissional

### BlackSentinel (redteam)
- Mentalidade adversarial
- Análise de superfície de ataque
- Threat modeling
- Apenas para usuários `redteam` ou `admin`

## 👥 Roles de Usuário

| Role | Perfis Disponíveis |
|------|-------------------|
| `user` | pentest |
| `analyst` | pentest |
| `redteam` | pentest, redteam |
| `admin` | pentest, redteam |

## 🔒 Rate Limiting

| Período | Limite Padrão |
|---------|---------------|
| Por minuto | 20 requests |
| Por hora | 100 requests |
| Por dia | 500 requests |

Headers de resposta:
- `X-RateLimit-Limit`
- `X-RateLimit-Remaining`
- `X-RateLimit-Daily-Remaining`

## 🧪 Testes

```bash
# Rodar todos os testes
php artisan test

# Testes unitários
php artisan test --testsuite=Unit

# Testes de feature
php artisan test --testsuite=Feature

# Com coverage
php artisan test --coverage
```

## 🔧 Comandos Úteis

```bash
# Limpar sessões antigas
php artisan schedule:run

# Processar queue
php artisan queue:work redis

# Monitorar queue
php artisan queue:listen
```

## 📦 Deploy Checklist

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] API Key configurada
- [ ] Redis configurado
- [ ] Queue worker rodando
- [ ] SSL/HTTPS habilitado
- [ ] Rate limiting ativo
- [ ] Logs configurados
- [ ] Backup de banco configurado

## 🛡️ Segurança

### Nunca expor:
- `DEEPSEEK_API_KEY`
- System prompts
- Logs de abuso

### Sempre validar:
- Input do usuário
- Tamanho de mensagens
- Padrões maliciosos
- Tentativas de prompt injection

## 📄 Licença

Proprietary - Todos os direitos reservados.
