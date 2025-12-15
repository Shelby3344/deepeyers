<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_id');
            $table->enum('role', ['system', 'user', 'assistant']);
            $table->longText('content');
            $table->unsignedInteger('tokens')->nullable();
            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('completion_tokens')->nullable();
            $table->string('model', 100)->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->timestamps();

            $table->foreign('session_id')
                ->references('id')
                ->on('chat_sessions')
                ->cascadeOnDelete();

            $table->index(['session_id', 'created_at']);
            $table->index(['session_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
