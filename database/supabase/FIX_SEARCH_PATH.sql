-- ============================================================================
-- FIX: Corrigir função update_updated_at_column com search_path explícito
-- Execute este script no SQL Editor do Supabase
-- ============================================================================

-- Dropar e recriar a função com search_path explícito
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

-- Verificar que a função foi criada corretamente
SELECT 
    proname as function_name,
    proconfig as config
FROM pg_proc 
WHERE proname = 'update_updated_at_column';
