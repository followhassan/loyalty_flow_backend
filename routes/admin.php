<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AgentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MerchantController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController;
Use App\Http\Controllers\Admin\PromotionController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



//====================Admin Authentication=========================
Route::get('/test-mail', function () {
    try {
        Mail::raw('Test email from Laravel', function ($message) {
            $message->to('shuvo.bg7@gmail.com')
                    ->subject('Test Mail');
        });

        return 'Mail sent successfully';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('login.admin');
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::middleware('web')
    ->group(function () {
        Route::get('/admin', fn() => redirect()->route('admin.dashboard'));
        Route::get('/admin/', fn() => redirect()->route('admin.dashboard'));
    });

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth:admin'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/cc', [DashboardController::class, 'cacheClear'])->name('cacheClear');

     Route::get('profile', [DashboardController::class, 'adminProfile'])->name('profile');
    Route::get('profile-edit', [DashboardController::class, 'profileEdit'])->name('profile.edit');
    Route::post('profile-update', [DashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::post('password-update', [DashboardController::class, 'passwordUpdate'])->name('password.update');

    // Settings route
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('store', [SettingsController::class, 'store'])->name('store');
    });

    // admins routes
    Route::group(['prefix' => 'admins', 'as' => 'admins.'], function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('store', [AdminController::class, 'store'])->name('store');
        Route::get('{user}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('{user}', [AdminController::class, 'update'])->name('update');
        Route::post('{user}/suspend', [AdminController::class, 'suspend'])->name('suspend');
        Route::post('{user}/change-password', [AdminController::class, 'changePassword'])->name('chnage-password');

    });

    Route::group(['prefix' =>  'roles', 'as' => 'roles.'], function () {
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::get('/create', [RolesController::class, 'create'])->name('create');
        Route::post('/store', [RolesController::class, 'store'])->name('store');
        Route::get('{id}/edit', [RolesController::class, 'edit'])->name('edit');
        Route::post('{id}/update', [RolesController::class, 'update'])->name('update');

    });

    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/update', [UserController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'agents', 'as' => 'agents.'], function () {
        Route::get('/', [AgentController::class, 'index'])->name('index');
        Route::post('/store', [AgentController::class, 'storeAgent'])->name('store');
        Route::get('/view/{id}', [AgentController::class, 'show'])->name('show');
        Route::post('/update', [AgentController::class, 'update'])->name('update');
        Route::get('/{id}/toggle-status', [AgentController::class, 'toggleStatus'])->name('toggleStatus');



    });

    Route::group(['prefix' => 'merchants', 'as' => 'merchants.'], function () {
        Route::get('/', [MerchantController::class, 'index'])->name('index');
        Route::get('/view/{id}', [MerchantController::class, 'show'])->name('show');
        Route::post('/update', [MerchantController::class, 'update'])->name('update');
        Route::get('/{id}/toggle-status', [MerchantController::class, 'toggleStatus'])->name('toggleStatus');
    });


    Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');

    });

    Route::group(['prefix' => 'promotions', 'as' => 'promotions.'], function () {
        Route::get('/', [PromotionController::class, 'index'])->name('index');
    });





});
