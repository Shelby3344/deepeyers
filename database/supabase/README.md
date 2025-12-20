# ============================================================================
# GUIA DE CONFIGURAÇÃO SUPABASE - DeepEyes
# ============================================================================

## 📋 PASSO A PASSO

### 1. Criar Projeto no Supabase

1. Acesse [https://supabase.com](https://supabase.com)
2. Clique em "Start your project"
3. Faça login com GitHub
4. Clique em "New project"
5. Escolha:
   - **Organization**: Sua organização
   - **Name**: `deepeyes-production`
   - **Database Password**: Gere uma senha forte (GUARDE ISSO!)
   - **Region**: Escolha a mais próxima (ex: `South America (São Paulo)`)
6. Clique em "Create new project" e aguarde ~2 minutos

---

### 2. Obter Credenciais

Após o projeto ser criado, vá em **Settings > API**:

```
SUPABASE_URL = https://YOUR_PROJECT_ID.supabase.co
SUPABASE_KEY = eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9... (anon key)
SUPABASE_SERVICE_KEY = eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9... (service_role)
SUPABASE_JWT_SECRET = (Settings > API > JWT Settings > JWT Secret)
```

Vá em **Settings > Database** para obter:

```
DB_HOST = db.YOUR_PROJECT_ID.supabase.co
DB_PORT = 5432
DB_DATABASE = postgres
DB_USERNAME = postgres
DB_PASSWORD = (a senha que você definiu ao criar o projeto)
```

---

### 3. Executar Scripts SQL

No Supabase, vá em **SQL Editor** e execute os scripts NA ORDEM:

1. **01_schema.sql** - Cria as tabelas
2. **02_rls_policies.sql** - Configura RLS
3. **03_security_advanced.sql** - Segurança avançada

Para cada arquivo:
1. Abra o SQL Editor no Supabase
2. Cole o conteúdo do arquivo
3. Clique em "Run"
4. Verifique se não há erros

---

### 4. Configurar .env

Atualize seu arquivo `.env`:

```env
# ===========================================
# SUPABASE CONFIG
# ===========================================
SUPABASE_URL=https://YOUR_PROJECT.supabase.co
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
SUPABASE_SERVICE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
SUPABASE_JWT_SECRET=your-jwt-secret

# ===========================================
# DATABASE (PostgreSQL via Supabase)
# ===========================================
DB_CONNECTION=pgsql
DB_HOST=db.YOUR_PROJECT.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=YOUR_DATABASE_PASSWORD

# SSL obrigatório para Supabase
DB_SSLMODE=require
```

---

### 5. Atualizar config/database.php

Certifique-se de que a configuração PostgreSQL inclui SSL:

```php
'pgsql' => [
    'driver' => 'pgsql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'sslmode' => env('DB_SSLMODE', 'prefer'),
],
```

---

### 6. Testar Conexão

```bash
php artisan tinker

# Testar conexão
DB::connection()->getPdo()

# Verificar tabelas
DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'")
```

---

### 7. Configurar Auth (Opcional)

Se quiser usar Supabase Auth ao invés de Laravel Sanctum:

1. Vá em **Authentication > Providers**
2. Configure os providers desejados (Email, Google, GitHub, etc.)
3. Em **Authentication > URL Configuration**:
   - Site URL: `https://seu-dominio.com`
   - Redirect URLs: `https://seu-dominio.com/auth/callback`

---

### 8. Configurar Storage (Opcional)

1. Vá em **Storage**
2. Crie um bucket chamado `avatars`
3. Configure as políticas:

```sql
-- Permitir upload apenas para usuários autenticados
CREATE POLICY "Avatar upload" ON storage.objects
    FOR INSERT TO authenticated
    WITH CHECK (bucket_id = 'avatars' AND auth.uid()::text = (storage.foldername(name))[1]);

-- Permitir visualização pública de avatares
CREATE POLICY "Avatar public view" ON storage.objects
    FOR SELECT TO public
    USING (bucket_id = 'avatars');
```

---

## 🔒 VERIFICAÇÃO DE SEGURANÇA

### Checklist RLS

Após configurar, verifique no Supabase:

1. Vá em **Table Editor**
2. Clique em cada tabela
3. Verifique se aparece o ícone 🔒 (RLS ativo)

### Testar Políticas

No SQL Editor, teste as políticas:

```sql
-- Simular usuário não autenticado
SET request.jwt.claim.sub = '';
SELECT * FROM public.users; -- Deve retornar vazio

-- Simular usuário autenticado
SET request.jwt.claim.sub = 'user-uuid-here';
SELECT * FROM public.chat_sessions; -- Deve retornar apenas sessões do usuário
```

---

## 🚨 SEGURANÇA IMPORTANTE

### NUNCA faça isso:

1. ❌ Expor `SUPABASE_SERVICE_KEY` no frontend
2. ❌ Desabilitar RLS em produção
3. ❌ Usar a mesma senha do banco em outros lugares
4. ❌ Deixar políticas RLS vazias

### SEMPRE faça isso:

1. ✅ Use `SUPABASE_KEY` (anon) no frontend
2. ✅ Use `SUPABASE_SERVICE_KEY` apenas no backend
3. ✅ Mantenha RLS ativo em todas as tabelas
4. ✅ Teste as políticas antes de ir para produção
5. ✅ Monitore os logs de auditoria

---

## 📊 Monitoramento

### Logs no Supabase

1. Vá em **Database > Logs**
2. Monitore queries lentas e erros

### Alertas

1. Vá em **Settings > Alerts**
2. Configure alertas para:
   - Alto uso de CPU
   - Erros de conexão
   - Quota de storage

---

## 🔄 Backup

O Supabase faz backups automáticos:
- **Free**: Backup diário, retenção 7 dias
- **Pro**: Backup point-in-time, retenção 30 dias

Para backup manual:
1. Vá em **Settings > Database**
2. Clique em "Download backup"

---

## 📝 Comandos Úteis

```bash
# Limpar cache após mudar .env
php artisan config:clear
php artisan cache:clear

# Verificar conexão
php artisan db:show

# Rodar migrations (se necessário)
php artisan migrate --force
```

---

## 🆘 Suporte

- Documentação: [https://supabase.com/docs](https://supabase.com/docs)
- Discord: [https://discord.supabase.com](https://discord.supabase.com)
- Status: [https://status.supabase.com](https://status.supabase.com)
