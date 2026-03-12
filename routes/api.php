<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Customer\CustomerProfileController;
use App\Http\Controllers\Api\Merchant\ProfileController;
use App\Http\Controllers\Api\QrController;
use App\Http\Controllers\Api\TransactionController;
// use App\Http\Controllers\Api\MerchantAuthController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    // Route::post('/merchant/register', [MerchantAuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me',       [AuthController::class, 'profile']);

        Route::post('/merchant/profile/update', [ProfileController::class, 'updateMerchant']);
        Route::get('/merchant/qr', [ProfileController::class, 'merchantQr']);


        Route::post('/customer/profile/update', [CustomerProfileController::class, 'updateProfile']);
        Route::post('/customer/transaction/submit', [TransactionController::class, 'submitTransaction']);
    });
});

Route::post('/qr/scan', [QrController::class, 'scanQr']);
