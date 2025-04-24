<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::post('/auth', [LoginController::class, 'store']);
Route::middleware('auth:sanctum')->get('/auth/user', [LoginController::class, 'index']);
Route::middleware('auth:sanctum')->post('/auth/logout', [LoginController::class, 'destroy']);
