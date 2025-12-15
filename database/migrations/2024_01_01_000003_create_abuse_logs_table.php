<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abuse_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('session_id')->nullable();
            $table->string('ip_address', 45);
            $table->string('type', 50);
            $table->text('content')->nullable();
            $table->text('reason');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('session_id')
                ->references('id')
                ->on('chat_sessions')
                ->nullOnDelete();

            $table->index(['user_id', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abuse_logs');
    }
};
