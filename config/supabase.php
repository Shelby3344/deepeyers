<?php

/**
 * Configuração do Supabase para DeepEyes
 * 
 * Adicione estas variáveis ao seu .env:
 * 
 * SUPABASE_URL=https://YOUR_PROJECT.supabase.co
 * SUPABASE_KEY=eyJ... (anon key - público)
 * SUPABASE_SERVICE_KEY=eyJ... (service_role key - NUNCA expor no frontend!)
 * SUPABASE_JWT_SECRET=your-jwt-secret
 * 
 * DB_CONNECTION=pgsql
 * DB_HOST=db.YOUR_PROJECT.supabase.co
 * DB_PORT=5432
 * DB_DATABASE=postgres
 * DB_USERNAME=postgres
 * DB_PASSWORD=YOUR_DATABASE_PASSWORD
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase URL
    |--------------------------------------------------------------------------
    */
    'url' => env('SUPABASE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Supabase Anon Key (público - pode ser exposto no frontend)
    |--------------------------------------------------------------------------
    */
    'anon_key' => env('SUPABASE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Supabase Service Role Key (PRIVADO - NUNCA expor!)
    |--------------------------------------------------------------------------
    */
    'service_key' => env('SUPABASE_SERVICE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | JWT Secret para validação de tokens
    |--------------------------------------------------------------------------
    */
    'jwt_secret' => env('SUPABASE_JWT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Configurações de Storage
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'bucket' => env('SUPABASE_STORAGE_BUCKET', 'avatars'),
        'public' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Auth
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'auto_confirm_email' => env('SUPABASE_AUTO_CONFIRM', false),
        'enable_signup' => env('SUPABASE_ENABLE_SIGNUP', true),
        'password_min_length' => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting via Supabase
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        'enabled' => true,
        'requests_per_minute' => 60,
        'requests_per_hour' => 1000,
    ],
];
