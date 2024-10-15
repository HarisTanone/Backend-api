<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'edit']);
    Route::put('users/{id}/password', [UserController::class, 'updatePassword']);
});
