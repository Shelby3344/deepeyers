-- ============================================================================
-- DeepEyes - Script SIMPLIFICADO para Supabase
-- Execute este script COMPLETO no SQL Editor do Supabase
-- ============================================================================

-- PARTE 1: CRIAR TABELAS
-- ============================================================================

-- Tabela de planos (primeiro, pois users referencia ela)
CREATE TABLE IF NOT EXISTS public.plans (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    billing_cycle VARCHAR(20) DEFAULT 'monthly',
    requests_per_day INTEGER DEFAULT 100,
    requests_per_month INTEGER DEFAULT 3000,
    features JSONB DEFAULT '[]',
    allowed_profiles JSONB DEFAULT '["pentest"]',
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS public.users (
    id BIGSERIAL PRIMARY KEY,
    auth_id UUID UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMPTZ,
    password VARCHAR(255),
    role VARCHAR(50) DEFAULT 'user',
    avatar VARCHAR(500),
    is_banned BOOLEAN DEFAULT FALSE,
    banned_at TIMESTAMPTZ,
    ban_reason TEXT,
    daily_requests INTEGER DEFAULT 0,
    daily_requests_date DATE,
    plan_id BIGINT REFERENCES public.plans(id) ON DELETE SET NULL,
    last_login_at TIMESTAMPTZ,
    last_login_ip VARCHAR(45),
    remember_token VARCHAR(100),
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Tabela de assinaturas
CREATE TABLE IF NOT EXISTS public.subscriptions (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    plan_id BIGINT NOT NULL REFERENCES public.plans(id) ON DELETE CASCADE,
    status VARCHAR(20) DEFAULT 'pending',
    starts_at TIMESTAMPTZ,
    ends_at TIMESTAMPTZ,
    cancelled_at TIMESTAMPTZ,
    payment_method VARCHAR(100),
    payment_id VARCHAR(255),
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Tabela de sessões de chat
CREATE TABLE IF NOT EXISTS public.chat_sessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    title VARCHAR(255) DEFAULT 'Nova Sessão',
    profile VARCHAR(50) DEFAULT 'pentest',
    target_domain VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    total_tokens INTEGER DEFAULT 0,
    message_count INTEGER DEFAULT 0,
    metadata JSONB,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    deleted_at TIMESTAMPTZ
);

-- Tabela de mensagens
CREATE TABLE IF NOT EXISTS public.chat_messages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    session_id UUID NOT NULL REFERENCES public.chat_sessions(id) ON DELETE CASCADE,
    role VARCHAR(20) NOT NULL,
    content TEXT NOT NULL,
    tokens INTEGER,
    prompt_tokens INTEGER,
    completion_tokens INTEGER,
    model VARCHAR(100),
    metadata JSONB,
    is_flagged BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Tabela de logs de abuso
CREATE TABLE IF NOT EXISTS public.abuse_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES public.users(id) ON DELETE SET NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    request_path VARCHAR(500),
    request_method VARCHAR(10),
    abuse_type VARCHAR(50) NOT NULL,
    severity VARCHAR(20) DEFAULT 'medium',
    details JSONB,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Tabela de tokens de acesso (Sanctum)
CREATE TABLE IF NOT EXISTS public.personal_access_tokens (
    id BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    abilities TEXT,
    last_used_at TIMESTAMPTZ,
    expires_at TIMESTAMPTZ,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================================================
-- PARTE 2: CRIAR ÍNDICES
-- ============================================================================

CREATE INDEX IF NOT EXISTS idx_users_email ON public.users(email);
CREATE INDEX IF NOT EXISTS idx_users_role ON public.users(role);
CREATE INDEX IF NOT EXISTS idx_users_auth_id ON public.users(auth_id);
CREATE INDEX IF NOT EXISTS idx_subscriptions_user ON public.subscriptions(user_id);
CREATE INDEX IF NOT EXISTS idx_subscriptions_status ON public.subscriptions(status);
CREATE INDEX IF NOT EXISTS idx_chat_sessions_user ON public.chat_sessions(user_id);
CREATE INDEX IF NOT EXISTS idx_chat_sessions_user_active ON public.chat_sessions(user_id, is_active);
CREATE INDEX IF NOT EXISTS idx_chat_messages_session ON public.chat_messages(session_id);
CREATE INDEX IF NOT EXISTS idx_abuse_logs_user ON public.abuse_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_abuse_logs_ip ON public.abuse_logs(ip_address);
CREATE INDEX IF NOT EXISTS idx_personal_access_tokens_tokenable ON public.personal_access_tokens(tokenable_type, tokenable_id);

-- ============================================================================
-- PARTE 3: INSERIR PLANOS PADRÃO
-- ============================================================================

INSERT INTO public.plans (name, slug, description, price, billing_cycle, requests_per_day, requests_per_month, features, is_active, sort_order) 
VALUES 
    ('Free', 'free', 'Plano gratuito para testes', 0.00, 'monthly', 10, 300, '["Chat básico", "1 perfil"]', true, 1),
    ('Pro', 'pro', 'Para profissionais', 29.90, 'monthly', 100, 3000, '["Chat ilimitado", "Todos os perfis", "Suporte prioritário"]', true, 2),
    ('Enterprise', 'enterprise', 'Para equipes', 99.90, 'monthly', 500, 15000, '["Tudo do Pro", "API dedicada", "SLA garantido"]', true, 3)
ON CONFLICT (slug) DO NOTHING;

-- ============================================================================
-- PARTE 4: HABILITAR RLS (Row Level Security)
-- ============================================================================

ALTER TABLE public.users ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.plans ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.subscriptions ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.chat_sessions ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.chat_messages ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.abuse_logs ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.personal_access_tokens ENABLE ROW LEVEL SECURITY;

-- ============================================================================
-- PARTE 5: POLÍTICAS RLS BÁSICAS
-- ============================================================================

-- Planos: todos podem ver planos ativos
CREATE POLICY "plans_select_all" ON public.plans
    FOR SELECT USING (is_active = true);

-- Usuários: cada um vê apenas seus dados
CREATE POLICY "users_select_own" ON public.users
    FOR SELECT USING (auth.uid() = auth_id);

CREATE POLICY "users_update_own" ON public.users
    FOR UPDATE USING (auth.uid() = auth_id);

-- Chat Sessions: cada um vê apenas suas sessões
CREATE POLICY "chat_sessions_select_own" ON public.chat_sessions
    FOR SELECT USING (user_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid()));

CREATE POLICY "chat_sessions_insert_own" ON public.chat_sessions
    FOR INSERT WITH CHECK (user_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid()));

CREATE POLICY "chat_sessions_update_own" ON public.chat_sessions
    FOR UPDATE USING (user_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid()));

CREATE POLICY "chat_sessions_delete_own" ON public.chat_sessions
    FOR DELETE USING (user_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid()));

-- Chat Messages: cada um vê apenas mensagens de suas sessões
CREATE POLICY "chat_messages_select_own" ON public.chat_messages
    FOR SELECT USING (
        session_id IN (
            SELECT id FROM public.chat_sessions 
            WHERE user_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid())
        )
    );

CREATE POLICY "chat_messages_insert_own" ON public.chat_messages
    FOR INSERT WITH CHECK (
        session_id IN (
            SELECT id FROM public.chat_sessions 
            WHERE user_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid())
        )
    );

-- Subscriptions: cada um vê apenas suas assinaturas
CREATE POLICY "subscriptions_select_own" ON public.subscriptions
    FOR SELECT USING (user_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid()));

-- Tokens: cada um vê apenas seus tokens
CREATE POLICY "tokens_select_own" ON public.personal_access_tokens
    FOR SELECT USING (tokenable_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid()));

CREATE POLICY "tokens_delete_own" ON public.personal_access_tokens
    FOR DELETE USING (tokenable_id IN (SELECT id FROM public.users WHERE auth_id = auth.uid()));

-- ============================================================================
-- PARTE 6: FUNÇÃO PARA CRIAR USUÁRIO AUTOMÁTICO
-- ============================================================================

CREATE OR REPLACE FUNCTION public.handle_new_user()
RETURNS TRIGGER
LANGUAGE plpgsql
SECURITY DEFINER SET search_path = public
AS $$
BEGIN
    INSERT INTO public.users (auth_id, email, name, role, created_at, updated_at)
    VALUES (
        NEW.id,
        NEW.email,
        COALESCE(NEW.raw_user_meta_data->>'name', split_part(NEW.email, '@', 1)),
        'user',
        NOW(),
        NOW()
    )
    ON CONFLICT (auth_id) DO UPDATE SET
        email = EXCLUDED.email,
        updated_at = NOW();
    RETURN NEW;
END;
$$;

-- Trigger para criar usuário quando alguém se registra
DROP TRIGGER IF EXISTS on_auth_user_created ON auth.users;
CREATE TRIGGER on_auth_user_created
    AFTER INSERT ON auth.users
    FOR EACH ROW EXECUTE FUNCTION public.handle_new_user();

-- ============================================================================
-- PARTE 7: TRIGGER PARA UPDATED_AT
-- ============================================================================

CREATE OR REPLACE FUNCTION public.update_updated_at_column()
RETURNS TRIGGER
LANGUAGE plpgsql
SECURITY DEFINER
SET search_path = public
AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$;

CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON public.users
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();

CREATE TRIGGER update_plans_updated_at BEFORE UPDATE ON public.plans
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();

CREATE TRIGGER update_subscriptions_updated_at BEFORE UPDATE ON public.subscriptions
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();

CREATE TRIGGER update_chat_sessions_updated_at BEFORE UPDATE ON public.chat_sessions
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();

CREATE TRIGGER update_chat_messages_updated_at BEFORE UPDATE ON public.chat_messages
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();

-- ============================================================================
-- PRONTO! Banco configurado com RLS
-- ============================================================================
