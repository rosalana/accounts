<?php

use Illuminate\Support\Facades\Route;
use Rosalana\Accounts\Http\Controllers\SessionController;
use Rosalana\Accounts\Http\Controllers\UserController;

Route::prefix('sessions')->group(function () {
    Route::get('/', [SessionController::class, 'index']);
    Route::post('/{id}/terminate', [SessionController::class, 'terminate']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/{id}/logout', [UserController::class, 'logout']);
});