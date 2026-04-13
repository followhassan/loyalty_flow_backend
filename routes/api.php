<?php

use App\Http\Controllers\Api\Agent\DashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Customer\CustomerProfileController;
use App\Http\Controllers\Api\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\Api\Merchant\ProfileController;
use App\Http\Controllers\Api\Merchant\TransactionController as MerchantTransactionController;
use App\Http\Controllers\Api\MerchentCustomerController;
use App\Http\Controllers\Api\QrController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserDashboardController;
// use App\Http\Controllers\Api\MerchantAuthController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/forget-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/verify-forget-password-otp', [AuthController::class, 'verifyForgetPasswordOtp']);
    Route::post('/refresh-token', [AuthController::class, 'refresh']);


    // Route::post('/merchant/register', [MerchantAuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me',       [AuthController::class, 'profile']);

        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::get('/merchant/dashboard', [ProfileController::class, 'dashboard']);
        Route::post('/merchant/profile/update', [ProfileController::class, 'updateMerchant']);
        Route::get('/merchant/qr', [ProfileController::class, 'merchantQr']);
        Route::get('/merchant/my-transaction', [MerchantTransactionController::class, 'merchantTransactions']);
        Route::get('/merchant/transaction/approve/{id}', [MerchantTransactionController::class, 'approveTransaction']);
        Route::post('/merchant/transaction/reject/{id}', [MerchantTransactionController::class, 'rejectTransaction']);

        Route::get('/merchant/customer-list', [MerchentCustomerController::class, 'customers']);
        // Route::get('/merchant/customer-details/{id}', [MerchentCustomerController::class, 'customerDetails']);


        Route::get('/customer/dashboard', [UserDashboardController::class, 'dashboard']);
        Route::post('/customer/profile/update', [CustomerProfileController::class, 'updateProfile']);
        Route::post('/customer/transaction/submit', [TransactionController::class, 'submitTransaction']);
        Route::get('/customer/my-transaction', [CustomerTransactionController::class, 'myTransactions']);

        Route::get('/agent/dashboard', [DashboardController::class, 'dashboard']);
        Route::get('/agent/referral', [DashboardController::class, 'referral']);
        Route::get('/agent/merchant-list', [DashboardController::class, 'merchants']);
        Route::get('/agent/growth', [DashboardController::class, 'growth']);


        Route::post('/role/switch', [AuthController::class, 'switchRole']);
        Route::post('/apply/merchant', [AuthController::class, 'applyMerchant']);

    });
});


Route::post('/qr/scan', [QrController::class, 'scanQr']);
