<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\MinifyHtml;
use Illuminate\Support\Facades\Route;

Route::middleware([MinifyHtml::class])->group(function () {
    Route::get('/', function () {
        return view('chat');
    });

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});
