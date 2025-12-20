-- ============================================================================
-- DeepEyes - Row Level Security (RLS) Policies
-- Execute APÓS o schema (01_schema.sql)
-- ============================================================================

-- ============================================================================
-- HABILITAR RLS EM TODAS AS TABELAS
-- ============================================================================
ALTER TABLE public.users ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.plans ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.subscriptions ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.chat_sessions ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.chat_messages ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.abuse_logs ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.personal_access_tokens ENABLE ROW LEVEL SECURITY;

-- ============================================================================
-- POLÍTICAS: users
-- ============================================================================

-- Usuários podem ver apenas seu próprio perfil
CREATE POLICY "users_select_own" ON public.users
    FOR SELECT
    USING (auth_id = auth.uid() OR public.is_admin());

-- Usuários podem atualizar apenas seu próprio perfil
CREATE POLICY "users_update_own" ON public.users
    FOR UPDATE
    USING (auth_id = auth.uid())
    WITH CHECK (
        auth_id = auth.uid() 
        -- Não pode mudar role ou is_banned
        AND role = (SELECT role FROM public.users WHERE auth_id = auth.uid())
        AND is_banned = (SELECT is_banned FROM public.users WHERE auth_id = auth.uid())
    );

-- Apenas admins podem inserir usuários diretamente
CREATE POLICY "users_insert_admin" ON public.users
    FOR INSERT
    WITH CHECK (public.is_admin() OR auth_id = auth.uid());

-- Apenas admins podem deletar usuários
CREATE POLICY "users_delete_admin" ON public.users
    FOR DELETE
    USING (public.is_admin());

-- ============================================================================
-- POLÍTICAS: plans
-- ============================================================================

-- Todos podem ver planos ativos
CREATE POLICY "plans_select_active" ON public.plans
    FOR SELECT
    USING (is_active = true OR public.is_admin());

-- Apenas admins podem modificar planos
CREATE POLICY "plans_insert_admin" ON public.plans
    FOR INSERT
    WITH CHECK (public.is_admin());

CREATE POLICY "plans_update_admin" ON public.plans
    FOR UPDATE
    USING (public.is_admin());

CREATE POLICY "plans_delete_admin" ON public.plans
    FOR DELETE
    USING (public.is_admin());

-- ============================================================================
-- POLÍTICAS: subscriptions
-- ============================================================================

-- Usuários podem ver suas próprias assinaturas
CREATE POLICY "subscriptions_select_own" ON public.subscriptions
    FOR SELECT
    USING (
        user_id = public.get_current_user_id() 
        OR public.is_admin()
    );

-- Apenas admins podem criar/modificar assinaturas
CREATE POLICY "subscriptions_insert_admin" ON public.subscriptions
    FOR INSERT
    WITH CHECK (public.is_admin());

CREATE POLICY "subscriptions_update_admin" ON public.subscriptions
    FOR UPDATE
    USING (public.is_admin());

CREATE POLICY "subscriptions_delete_admin" ON public.subscriptions
    FOR DELETE
    USING (public.is_admin());

-- ============================================================================
-- POLÍTICAS: chat_sessions
-- ============================================================================

-- Usuários podem ver apenas suas próprias sessões
CREATE POLICY "chat_sessions_select_own" ON public.chat_sessions
    FOR SELECT
    USING (
        user_id = public.get_current_user_id() 
        OR public.is_admin()
    );

-- Usuários podem criar sessões para si mesmos
CREATE POLICY "chat_sessions_insert_own" ON public.chat_sessions
    FOR INSERT
    WITH CHECK (
        user_id = public.get_current_user_id()
    );

-- Usuários podem atualizar suas próprias sessões
CREATE POLICY "chat_sessions_update_own" ON public.chat_sessions
    FOR UPDATE
    USING (user_id = public.get_current_user_id())
    WITH CHECK (
        user_id = public.get_current_user_id()
        -- Não pode mudar o user_id
        AND user_id = (SELECT user_id FROM public.chat_sessions WHERE id = chat_sessions.id)
    );

-- Usuários podem deletar (soft delete) suas próprias sessões
CREATE POLICY "chat_sessions_delete_own" ON public.chat_sessions
    FOR DELETE
    USING (
        user_id = public.get_current_user_id() 
        OR public.is_admin()
    );

-- ============================================================================
-- POLÍTICAS: chat_messages
-- ============================================================================

-- Usuários podem ver mensagens de suas próprias sessões
CREATE POLICY "chat_messages_select_own" ON public.chat_messages
    FOR SELECT
    USING (
        public.owns_session(session_id) 
        OR public.is_admin()
    );

-- Usuários podem inserir mensagens em suas próprias sessões
CREATE POLICY "chat_messages_insert_own" ON public.chat_messages
    FOR INSERT
    WITH CHECK (
        public.owns_session(session_id)
    );

-- Mensagens não podem ser atualizadas (imutáveis por segurança)
-- Admin pode marcar como flagged
CREATE POLICY "chat_messages_update_admin" ON public.chat_messages
    FOR UPDATE
    USING (public.is_admin());

-- Apenas admin pode deletar mensagens
CREATE POLICY "chat_messages_delete_admin" ON public.chat_messages
    FOR DELETE
    USING (public.is_admin());

-- ============================================================================
-- POLÍTICAS: abuse_logs
-- ============================================================================

-- Apenas admins podem ver logs de abuso
CREATE POLICY "abuse_logs_select_admin" ON public.abuse_logs
    FOR SELECT
    USING (public.is_admin());

-- Sistema pode inserir logs (via service role)
CREATE POLICY "abuse_logs_insert_service" ON public.abuse_logs
    FOR INSERT
    WITH CHECK (true); -- Será restrito via service_role key

-- Apenas admins podem modificar
CREATE POLICY "abuse_logs_update_admin" ON public.abuse_logs
    FOR UPDATE
    USING (public.is_admin());

CREATE POLICY "abuse_logs_delete_admin" ON public.abuse_logs
    FOR DELETE
    USING (public.is_admin());

-- ============================================================================
-- POLÍTICAS: personal_access_tokens
-- ============================================================================

-- Usuários podem ver seus próprios tokens
CREATE POLICY "tokens_select_own" ON public.personal_access_tokens
    FOR SELECT
    USING (
        tokenable_id = public.get_current_user_id() 
        AND tokenable_type = 'App\Models\User'
    );

-- Sistema pode inserir tokens
CREATE POLICY "tokens_insert_service" ON public.personal_access_tokens
    FOR INSERT
    WITH CHECK (true);

-- Usuários podem deletar seus próprios tokens
CREATE POLICY "tokens_delete_own" ON public.personal_access_tokens
    FOR DELETE
    USING (
        tokenable_id = public.get_current_user_id() 
        AND tokenable_type = 'App\Models\User'
    );

-- ============================================================================
-- FUNÇÕES DE SEGURANÇA ADICIONAL
-- ============================================================================

-- Função para rate limiting no banco
CREATE OR REPLACE FUNCTION public.check_rate_limit(
    p_user_id BIGINT,
    p_limit INTEGER DEFAULT 100
)
RETURNS BOOLEAN AS $$
DECLARE
    v_count INTEGER;
    v_plan_limit INTEGER;
BEGIN
    -- Obter limite do plano
    SELECT COALESCE(p.requests_per_day, 10)
    INTO v_plan_limit
    FROM public.users u
    LEFT JOIN public.plans p ON u.plan_id = p.id
    WHERE u.id = p_user_id;
    
    -- Contar requests de hoje
    SELECT daily_requests 
    INTO v_count
    FROM public.users 
    WHERE id = p_user_id 
    AND daily_requests_date = CURRENT_DATE;
    
    RETURN COALESCE(v_count, 0) < COALESCE(v_plan_limit, p_limit);
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Função para incrementar contador de requests
CREATE OR REPLACE FUNCTION public.increment_request_count(p_user_id BIGINT)
RETURNS VOID AS $$
BEGIN
    UPDATE public.users
    SET 
        daily_requests = CASE 
            WHEN daily_requests_date = CURRENT_DATE THEN daily_requests + 1
            ELSE 1
        END,
        daily_requests_date = CURRENT_DATE
    WHERE id = p_user_id;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Função para verificar se usuário está banido
CREATE OR REPLACE FUNCTION public.is_user_banned(p_user_id BIGINT)
RETURNS BOOLEAN AS $$
DECLARE
    v_banned BOOLEAN;
BEGIN
    SELECT is_banned INTO v_banned
    FROM public.users
    WHERE id = p_user_id;
    
    RETURN COALESCE(v_banned, false);
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- ============================================================================
-- GRANT PERMISSIONS
-- ============================================================================

-- Revogar acesso público
REVOKE ALL ON ALL TABLES IN SCHEMA public FROM anon;
REVOKE ALL ON ALL TABLES IN SCHEMA public FROM authenticated;

-- Conceder acesso seletivo para usuários autenticados
GRANT SELECT, INSERT, UPDATE, DELETE ON public.users TO authenticated;
GRANT SELECT ON public.plans TO authenticated;
GRANT SELECT ON public.subscriptions TO authenticated;
GRANT SELECT, INSERT, UPDATE, DELETE ON public.chat_sessions TO authenticated;
GRANT SELECT, INSERT ON public.chat_messages TO authenticated;
GRANT SELECT ON public.personal_access_tokens TO authenticated;

-- Conceder acesso às sequences
GRANT USAGE ON ALL SEQUENCES IN SCHEMA public TO authenticated;

-- Acesso anônimo apenas para planos
GRANT SELECT ON public.plans TO anon;
