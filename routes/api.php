<?php

use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::apiResource('vouchers', VoucherController::class);