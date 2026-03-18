<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Customer\CustomerProfileController;
use App\Http\Controllers\Api\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\Api\Merchant\ProfileController;
use App\Http\Controllers\Api\Merchant\TransactionController as MerchantTransactionController;
use App\Http\Controllers\Api\QrController;
use App\Http\Controllers\Api\TransactionController;
// use App\Http\Controllers\Api\MerchantAuthController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/forget-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/verify-forget-password-otp', [AuthController::class, 'verifyForgetPasswordOtp']);

    // Route::post('/merchant/register', [MerchantAuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me',       [AuthController::class, 'profile']);

        Route::get('/merchant/dashboard', [ProfileController::class, 'dashboard']);
        Route::post('/merchant/profile/update', [ProfileController::class, 'updateMerchant']);
        Route::get('/merchant/qr', [ProfileController::class, 'merchantQr']);
        Route::get('/merchant/my-transaction', [MerchantTransactionController::class, 'merchantTransactions']);
        Route::get('/merchant/transaction/approve/{id}', [MerchantTransactionController::class, 'approveTransaction']);
        Route::get('/merchant/transaction/reject/{id}', [MerchantTransactionController::class, 'rejectTransaction']);


        Route::post('/customer/profile/update', [CustomerProfileController::class, 'updateProfile']);
        Route::post('/customer/transaction/submit', [TransactionController::class, 'submitTransaction']);
        Route::get('/customer/my-transaction', [CustomerTransactionController::class, 'myTransactions']);
    });
});

Route::post('/qr/scan', [QrController::class, 'scanQr']);
