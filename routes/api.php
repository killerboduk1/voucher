<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::apiResource('vouchers', VoucherController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');