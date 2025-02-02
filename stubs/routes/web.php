<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::middleware('auth.rosalana')->group(function () {
    // protected routes
});

require __DIR__ . '/auth.php';
