-- ============================================================================
-- DeepEyes - Segurança Avançada para Supabase
-- Execute APÓS as políticas RLS (02_rls_policies.sql)
-- ============================================================================

-- ============================================================================
-- 1. AUDITORIA E LOGGING
-- ============================================================================

-- Tabela de auditoria para ações críticas
CREATE TABLE IF NOT EXISTS public.audit_logs (
    id BIGSERIAL PRIMARY KEY,
    table_name VARCHAR(100) NOT NULL,
    record_id TEXT NOT NULL,
    action VARCHAR(20) NOT NULL CHECK (action IN ('INSERT', 'UPDATE', 'DELETE')),
    old_data JSONB,
    new_data JSONB,
    user_id BIGINT,
    auth_uid UUID,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_audit_logs_table ON public.audit_logs(table_name);
CREATE INDEX idx_audit_logs_user ON public.audit_logs(user_id);
CREATE INDEX idx_audit_logs_created ON public.audit_logs(created_at DESC);

-- Habilitar RLS na auditoria
ALTER TABLE public.audit_logs ENABLE ROW LEVEL SECURITY;

-- Apenas admins podem ver logs de auditoria
CREATE POLICY "audit_logs_admin_only" ON public.audit_logs
    FOR ALL
    USING (public.is_admin());

-- Função de auditoria genérica
CREATE OR REPLACE FUNCTION public.audit_trigger_func()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        INSERT INTO public.audit_logs (table_name, record_id, action, new_data, auth_uid)
        VALUES (TG_TABLE_NAME, NEW.id::TEXT, 'INSERT', to_jsonb(NEW), auth.uid());
        RETURN NEW;
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO public.audit_logs (table_name, record_id, action, old_data, new_data, auth_uid)
        VALUES (TG_TABLE_NAME, NEW.id::TEXT, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW), auth.uid());
        RETURN NEW;
    ELSIF TG_OP = 'DELETE' THEN
        INSERT INTO public.audit_logs (table_name, record_id, action, old_data, auth_uid)
        VALUES (TG_TABLE_NAME, OLD.id::TEXT, 'DELETE', to_jsonb(OLD), auth.uid());
        RETURN OLD;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Aplicar auditoria em tabelas críticas
CREATE TRIGGER audit_users AFTER INSERT OR UPDATE OR DELETE ON public.users
    FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_func();

CREATE TRIGGER audit_chat_sessions AFTER INSERT OR UPDATE OR DELETE ON public.chat_sessions
    FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_func();

CREATE TRIGGER audit_subscriptions AFTER INSERT OR UPDATE OR DELETE ON public.subscriptions
    FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_func();

-- ============================================================================
-- 2. PROTEÇÃO CONTRA INJEÇÃO E VALIDAÇÃO
-- ============================================================================

-- Função para sanitizar input de texto
CREATE OR REPLACE FUNCTION public.sanitize_input(input TEXT)
RETURNS TEXT AS $$
BEGIN
    IF input IS NULL THEN
        RETURN NULL;
    END IF;
    
    -- Remove caracteres de controle (exceto newline, tab, carriage return)
    input := regexp_replace(input, E'[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x7F]', '', 'g');
    
    -- Trim
    input := trim(input);
    
    RETURN input;
END;
$$ LANGUAGE plpgsql IMMUTABLE;

-- Trigger para sanitizar inputs antes de inserir
CREATE OR REPLACE FUNCTION public.sanitize_before_insert()
RETURNS TRIGGER AS $$
BEGIN
    -- Sanitizar campos de texto comuns
    IF TG_TABLE_NAME = 'users' THEN
        NEW.name := public.sanitize_input(NEW.name);
        NEW.email := lower(trim(NEW.email));
    ELSIF TG_TABLE_NAME = 'chat_sessions' THEN
        NEW.title := public.sanitize_input(NEW.title);
        NEW.target_domain := public.sanitize_input(NEW.target_domain);
    ELSIF TG_TABLE_NAME = 'chat_messages' THEN
        NEW.content := public.sanitize_input(NEW.content);
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER sanitize_users BEFORE INSERT OR UPDATE ON public.users
    FOR EACH ROW EXECUTE FUNCTION public.sanitize_before_insert();

CREATE TRIGGER sanitize_chat_sessions BEFORE INSERT OR UPDATE ON public.chat_sessions
    FOR EACH ROW EXECUTE FUNCTION public.sanitize_before_insert();

CREATE TRIGGER sanitize_chat_messages BEFORE INSERT OR UPDATE ON public.chat_messages
    FOR EACH ROW EXECUTE FUNCTION public.sanitize_before_insert();

-- ============================================================================
-- 3. PROTEÇÃO CONTRA BRUTE FORCE
-- ============================================================================

-- Tabela para tracking de tentativas de login
CREATE TABLE IF NOT EXISTS public.login_attempts (
    id BIGSERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    success BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_login_attempts_email ON public.login_attempts(email, created_at DESC);
CREATE INDEX idx_login_attempts_ip ON public.login_attempts(ip_address, created_at DESC);

-- Habilitar RLS
ALTER TABLE public.login_attempts ENABLE ROW LEVEL SECURITY;

-- Apenas sistema pode acessar
CREATE POLICY "login_attempts_service" ON public.login_attempts
    FOR ALL
    USING (public.is_admin());

-- Função para verificar rate limit de login
CREATE OR REPLACE FUNCTION public.check_login_rate_limit(
    p_email VARCHAR(255),
    p_ip VARCHAR(45),
    p_max_attempts INTEGER DEFAULT 5,
    p_window_minutes INTEGER DEFAULT 15
)
RETURNS BOOLEAN AS $$
DECLARE
    v_email_attempts INTEGER;
    v_ip_attempts INTEGER;
BEGIN
    -- Contar tentativas por email
    SELECT COUNT(*) INTO v_email_attempts
    FROM public.login_attempts
    WHERE email = p_email
    AND success = FALSE
    AND created_at > NOW() - (p_window_minutes || ' minutes')::INTERVAL;
    
    -- Contar tentativas por IP
    SELECT COUNT(*) INTO v_ip_attempts
    FROM public.login_attempts
    WHERE ip_address = p_ip
    AND success = FALSE
    AND created_at > NOW() - (p_window_minutes || ' minutes')::INTERVAL;
    
    -- Bloquear se exceder limite
    RETURN v_email_attempts < p_max_attempts AND v_ip_attempts < (p_max_attempts * 3);
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Função para registrar tentativa de login
CREATE OR REPLACE FUNCTION public.log_login_attempt(
    p_email VARCHAR(255),
    p_ip VARCHAR(45),
    p_success BOOLEAN
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO public.login_attempts (email, ip_address, success)
    VALUES (p_email, p_ip, p_success);
    
    -- Limpar tentativas antigas (mais de 24h)
    DELETE FROM public.login_attempts
    WHERE created_at < NOW() - INTERVAL '24 hours';
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- ============================================================================
-- 4. PROTEÇÃO DE DADOS SENSÍVEIS
-- ============================================================================

-- View segura de usuários (sem dados sensíveis)
CREATE OR REPLACE VIEW public.users_safe AS
SELECT 
    id,
    name,
    avatar,
    role,
    created_at
FROM public.users
WHERE is_banned = FALSE;

-- Função para mascarar email
CREATE OR REPLACE FUNCTION public.mask_email(email TEXT)
RETURNS TEXT AS $$
DECLARE
    local_part TEXT;
    domain_part TEXT;
BEGIN
    IF email IS NULL OR position('@' in email) = 0 THEN
        RETURN '***@***.***';
    END IF;
    
    local_part := split_part(email, '@', 1);
    domain_part := split_part(email, '@', 2);
    
    IF length(local_part) <= 2 THEN
        RETURN '***@' || domain_part;
    END IF;
    
    RETURN left(local_part, 2) || '***@' || domain_part;
END;
$$ LANGUAGE plpgsql IMMUTABLE;

-- ============================================================================
-- 5. SINCRONIZAÇÃO COM AUTH.USERS
-- ============================================================================

-- Trigger para criar perfil quando usuário se registra
CREATE OR REPLACE FUNCTION public.handle_new_user()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO public.users (auth_id, email, name, role)
    VALUES (
        NEW.id,
        NEW.email,
        COALESCE(NEW.raw_user_meta_data->>'name', split_part(NEW.email, '@', 1)),
        'user'
    )
    ON CONFLICT (auth_id) DO UPDATE
    SET email = EXCLUDED.email;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Trigger no schema auth
CREATE OR REPLACE TRIGGER on_auth_user_created
    AFTER INSERT ON auth.users
    FOR EACH ROW EXECUTE FUNCTION public.handle_new_user();

-- ============================================================================
-- 6. LIMPEZA AUTOMÁTICA
-- ============================================================================

-- Função para limpar dados antigos
CREATE OR REPLACE FUNCTION public.cleanup_old_data()
RETURNS VOID AS $$
BEGIN
    -- Limpar sessões deletadas há mais de 30 dias
    DELETE FROM public.chat_sessions
    WHERE deleted_at IS NOT NULL
    AND deleted_at < NOW() - INTERVAL '30 days';
    
    -- Limpar logs de auditoria antigos (mais de 90 dias)
    DELETE FROM public.audit_logs
    WHERE created_at < NOW() - INTERVAL '90 days';
    
    -- Limpar logs de abuso antigos (mais de 90 dias)
    DELETE FROM public.abuse_logs
    WHERE created_at < NOW() - INTERVAL '90 days';
    
    -- Limpar tentativas de login antigas
    DELETE FROM public.login_attempts
    WHERE created_at < NOW() - INTERVAL '7 days';
    
    -- Limpar tokens expirados
    DELETE FROM public.personal_access_tokens
    WHERE expires_at IS NOT NULL
    AND expires_at < NOW();
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- ============================================================================
-- 7. ESTATÍSTICAS SEGURAS
-- ============================================================================

-- View de estatísticas para admin
CREATE OR REPLACE VIEW public.admin_stats AS
SELECT 
    (SELECT COUNT(*) FROM public.users) as total_users,
    (SELECT COUNT(*) FROM public.users WHERE is_banned = TRUE) as banned_users,
    (SELECT COUNT(*) FROM public.users WHERE created_at > NOW() - INTERVAL '7 days') as new_users_week,
    (SELECT COUNT(*) FROM public.chat_sessions WHERE deleted_at IS NULL) as active_sessions,
    (SELECT COUNT(*) FROM public.chat_messages) as total_messages,
    (SELECT COUNT(*) FROM public.subscriptions WHERE status = 'active') as active_subscriptions;

-- Apenas admins podem ver estatísticas
GRANT SELECT ON public.admin_stats TO authenticated;
