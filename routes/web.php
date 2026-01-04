<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Hero');
});

Route::get('/sign-in', function () {
    return Inertia::render('auth/SignIn');
});

Route::get('/clients', [App\Http\Controllers\ClientsController::class, 'index'])->name('clients.index');
