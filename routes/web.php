<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('chat');
});

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/api', function () {
    return response()->json([
        'name' => 'DeepEyes',
        'version' => '1.0.0',
        'description' => 'AI-powered Pentest Assistant',
        'api_docs' => url('/api'),
    ]);
});
