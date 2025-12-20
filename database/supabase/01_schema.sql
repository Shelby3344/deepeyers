-- ============================================================================
-- DeepEyes - Supabase Schema com RLS (Row Level Security)
-- Execute este script no SQL Editor do Supabase
-- ============================================================================

-- ============================================================================
-- 1. EXTENSÕES NECESSÁRIAS
-- ============================================================================
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ============================================================================
-- 2. TABELA: users (sincronizada com auth.users do Supabase)
-- ============================================================================
CREATE TABLE IF NOT EXISTS public.users (
    id BIGSERIAL PRIMARY KEY,
    auth_id UUID UNIQUE REFERENCES auth.users(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMPTZ,
    password VARCHAR(255), -- Pode ser null se usar apenas auth do Supabase
    role VARCHAR(50) DEFAULT 'user' CHECK (role IN ('user', 'admin', 'moderator')),
    avatar VARCHAR(500),
    is_banned BOOLEAN DEFAULT FALSE,
    banned_at TIMESTAMPTZ,
    ban_reason TEXT,
    daily_requests INTEGER DEFAULT 0,
    daily_requests_date DATE,
    plan_id BIGINT,
    last_login_at TIMESTAMPTZ,
    last_login_ip VARCHAR(45),
    remember_token VARCHAR(100),
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Índices
CREATE INDEX idx_users_email ON public.users(email);
CREATE INDEX idx_users_role ON public.users(role);
CREATE INDEX idx_users_auth_id ON public.users(auth_id);

-- ============================================================================
-- 3. TABELA: plans
-- ============================================================================
CREATE TABLE IF NOT EXISTS public.plans (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    billing_cycle VARCHAR(20) DEFAULT 'monthly' CHECK (billing_cycle IN ('monthly', 'yearly')),
    requests_per_day INTEGER DEFAULT 100,
    requests_per_month INTEGER DEFAULT 3000,
    features JSONB DEFAULT '[]'::jsonb,
    allowed_profiles JSONB DEFAULT '["pentest"]'::jsonb,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Adicionar FK em users
ALTER TABLE public.users 
    ADD CONSTRAINT fk_users_plan 
    FOREIGN KEY (plan_id) REFERENCES public.plans(id) ON DELETE SET NULL;

-- ============================================================================
-- 4. TABELA: subscriptions
-- ============================================================================
CREATE TABLE IF NOT EXISTS public.subscriptions (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    plan_id BIGINT NOT NULL REFERENCES public.plans(id) ON DELETE CASCADE,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('active', 'cancelled', 'expired', 'pending')),
    starts_at TIMESTAMPTZ,
    ends_at TIMESTAMPTZ,
    cancelled_at TIMESTAMPTZ,
    payment_method VARCHAR(100),
    payment_id VARCHAR(255),
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_subscriptions_user ON public.subscriptions(user_id);
CREATE INDEX idx_subscriptions_status ON public.subscriptions(status);

-- ============================================================================
-- 5. TABELA: chat_sessions
-- ============================================================================
CREATE TABLE IF NOT EXISTS public.chat_sessions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
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
    deleted_at TIMESTAMPTZ -- Soft delete
);

CREATE INDEX idx_chat_sessions_user ON public.chat_sessions(user_id);
CREATE INDEX idx_chat_sessions_user_active ON public.chat_sessions(user_id, is_active);
CREATE INDEX idx_chat_sessions_user_created ON public.chat_sessions(user_id, created_at DESC);

-- ============================================================================
-- 6. TABELA: chat_messages
-- ============================================================================
CREATE TABLE IF NOT EXISTS public.chat_messages (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    session_id UUID NOT NULL REFERENCES public.chat_sessions(id) ON DELETE CASCADE,
    role VARCHAR(20) NOT NULL CHECK (role IN ('system', 'user', 'assistant')),
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

CREATE INDEX idx_chat_messages_session ON public.chat_messages(session_id);
CREATE INDEX idx_chat_messages_session_created ON public.chat_messages(session_id, created_at);

-- ============================================================================
-- 7. TABELA: abuse_logs
-- ============================================================================
CREATE TABLE IF NOT EXISTS public.abuse_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES public.users(id) ON DELETE SET NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    request_path VARCHAR(500),
    request_method VARCHAR(10),
    abuse_type VARCHAR(50) NOT NULL,
    severity VARCHAR(20) DEFAULT 'medium' CHECK (severity IN ('low', 'medium', 'high', 'critical')),
    details JSONB,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_abuse_logs_user ON public.abuse_logs(user_id);
CREATE INDEX idx_abuse_logs_ip ON public.abuse_logs(ip_address);
CREATE INDEX idx_abuse_logs_type ON public.abuse_logs(abuse_type);
CREATE INDEX idx_abuse_logs_created ON public.abuse_logs(created_at DESC);

-- ============================================================================
-- 8. TABELA: personal_access_tokens (Sanctum)
-- ============================================================================
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

CREATE INDEX idx_personal_access_tokens_tokenable ON public.personal_access_tokens(tokenable_type, tokenable_id);

-- ============================================================================
-- 9. FUNÇÕES AUXILIARES
-- ============================================================================

-- Função para obter o user_id do usuário autenticado
CREATE OR REPLACE FUNCTION public.get_current_user_id()
RETURNS BIGINT AS $$
DECLARE
    v_user_id BIGINT;
BEGIN
    SELECT id INTO v_user_id 
    FROM public.users 
    WHERE auth_id = auth.uid();
    
    RETURN v_user_id;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Função para verificar se é admin
CREATE OR REPLACE FUNCTION public.is_admin()
RETURNS BOOLEAN AS $$
DECLARE
    v_role VARCHAR(50);
BEGIN
    SELECT role INTO v_role 
    FROM public.users 
    WHERE auth_id = auth.uid();
    
    RETURN v_role = 'admin';
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Função para verificar se usuário é dono da sessão
CREATE OR REPLACE FUNCTION public.owns_session(session_uuid UUID)
RETURNS BOOLEAN AS $$
DECLARE
    v_user_id BIGINT;
    v_session_owner BIGINT;
BEGIN
    v_user_id := public.get_current_user_id();
    
    SELECT user_id INTO v_session_owner 
    FROM public.chat_sessions 
    WHERE id = session_uuid;
    
    RETURN v_user_id = v_session_owner;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Trigger para atualizar updated_at
CREATE OR REPLACE FUNCTION public.update_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Aplicar trigger em todas as tabelas
CREATE TRIGGER users_updated_at BEFORE UPDATE ON public.users 
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at();
CREATE TRIGGER plans_updated_at BEFORE UPDATE ON public.plans 
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at();
CREATE TRIGGER subscriptions_updated_at BEFORE UPDATE ON public.subscriptions 
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at();
CREATE TRIGGER chat_sessions_updated_at BEFORE UPDATE ON public.chat_sessions 
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at();
CREATE TRIGGER chat_messages_updated_at BEFORE UPDATE ON public.chat_messages 
    FOR EACH ROW EXECUTE FUNCTION public.update_updated_at();

-- ============================================================================
-- 10. DADOS INICIAIS
-- ============================================================================

-- Planos padrão
INSERT INTO public.plans (name, slug, description, price, billing_cycle, requests_per_day, requests_per_month, features, is_active, sort_order) VALUES
('Free', 'free', 'Plano gratuito para testes', 0.00, 'monthly', 10, 300, '["Chat básico", "1 perfil"]', true, 1),
('Pro', 'pro', 'Para profissionais', 29.90, 'monthly', 100, 3000, '["Chat ilimitado", "Todos os perfis", "Suporte prioritário"]', true, 2),
('Enterprise', 'enterprise', 'Para equipes', 99.90, 'monthly', 500, 15000, '["Tudo do Pro", "API dedicada", "SLA garantido"]', true, 3)
ON CONFLICT (slug) DO NOTHING;
