<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\MinifyHtml;
use App\Http\Middleware\EnsureAuthenticated;
use Illuminate\Support\Facades\Route;

Route::middleware([MinifyHtml::class])->group(function () {
    // Landing page como página inicial (pública)
    Route::get('/', function () {
        return view('landing');
    });

    // Documentação (pública)
    Route::get('/docs', function () {
        return view('docs');
    });

    // Rotas protegidas - requerem autenticação
    Route::middleware([EnsureAuthenticated::class])->group(function () {
        // Chat
        Route::get('/chat', function () {
            return view('chat');
        });

        // Checklist de Pentest
        Route::get('/checklist', function () {
            return view('checklist');
        });

        // Scanner de Vulnerabilidades
        Route::get('/scanner', function () {
            return view('scanner');
        });

        // Gerador de Relatórios
        Route::get('/reports', function () {
            return view('reports');
        });

        // Terminal Interativo
        Route::get('/terminal', function () {
            return view('terminal');
        });

        // Perfil do usuário
        Route::get('/profile', function () {
            return view('profile');
        })->name('profile');
    });
});
