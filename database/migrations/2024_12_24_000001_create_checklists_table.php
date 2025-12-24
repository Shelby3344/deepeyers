<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('session_id')->nullable()->constrained('chat_sessions')->nullOnDelete();
            $table->string('title');
            $table->string('target_domain')->nullable();
            $table->string('type')->default('web'); // web, api, network, ad, mobile
            $table->json('states')->nullable(); // {item_id: 'tested'|'vuln'|'ok'}
            $table->json('notes')->nullable(); // {item_id: 'note text'}
            $table->string('share_token', 64)->nullable()->unique(); // Para compartilhamento público
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('share_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
