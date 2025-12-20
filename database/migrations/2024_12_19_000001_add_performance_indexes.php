<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona índices para melhorar a performance das queries
     */
    public function up(): void
    {
        // Índices para users
        Schema::table('users', function (Blueprint $table) {
            // Índice para ordenação por created_at (listagem de usuários)
            $table->index('created_at', 'users_created_at_index');
            
            // Índice para filtro por role
            $table->index('role', 'users_role_index');
            
            // Índice para filtro de banidos
            $table->index('is_banned', 'users_is_banned_index');
            
            // Índice composto para plan e ordenação
            $table->index(['plan_id', 'created_at'], 'users_plan_created_index');
        });

        // Índices adicionais para chat_sessions
        Schema::table('chat_sessions', function (Blueprint $table) {
            // Índice para ordenação geral (admin)
            $table->index('created_at', 'chat_sessions_created_at_index');
            
            // Índice para contagem de mensagens
            $table->index('message_count', 'chat_sessions_message_count_index');
        });

        // Índices adicionais para chat_messages
        Schema::table('chat_messages', function (Blueprint $table) {
            // Índice para filtro de hoje (estatísticas)
            $table->index('created_at', 'chat_messages_created_at_index');
            
            // Índice para mensagens flagged
            $table->index('is_flagged', 'chat_messages_is_flagged_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_created_at_index');
            $table->dropIndex('users_role_index');
            $table->dropIndex('users_is_banned_index');
            $table->dropIndex('users_plan_created_index');
        });

        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropIndex('chat_sessions_created_at_index');
            $table->dropIndex('chat_sessions_message_count_index');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('chat_messages_created_at_index');
            $table->dropIndex('chat_messages_is_flagged_index');
        });
    }
};
