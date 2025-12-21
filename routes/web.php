<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\MinifyHtml;
use Illuminate\Support\Facades\Route;

Route::middleware([MinifyHtml::class])->group(function () {
    // Landing page como página inicial
    Route::get('/', function () {
        return view('landing');
    });

    // Chat em /chat
    Route::get('/chat', function () {
        return view('chat');
    });

    // Documentação
    Route::get('/docs', function () {
        return view('docs');
    });

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});
