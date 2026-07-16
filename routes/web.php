<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;

// ─── Auth routes ───
Route::get('/', [LoginController::class, 'showForm']);
Route::get('/my-login', [LoginController::class, 'showForm']);
Route::post('/my-login', [LoginController::class, 'processLogin']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/register', [LoginController::class, 'showRegisterForm']);
Route::post('/register', [LoginController::class, 'registerCustomer']);
Route::get('/registration-success', [LoginController::class, 'showRegistrationSuccess']);

// ─── Dashboards ───
Route::get('/admin-dashboard', [LoginController::class, 'showAdminDashboard']);
Route::get('/cashier-dashboard', [LoginController::class, 'showCashierDashboard']);
Route::get('/customer-dashboard', [LoginController::class, 'showCustomerDashboard']);

// ─── Customer profile ───
Route::prefix('customer')->group(function () {
    Route::get('/profile', [CustomerController::class, 'showProfile']);
    Route::put('/profile', [CustomerController::class, 'updateProfile']);
    Route::get('/points-rewards', [CustomerController::class, 'viewPointsRewards']);
});

// ─── Admin operations ───
Route::prefix('admin')->group(function () {
    Route::get('/manage-membership', [AdminController::class, 'manageMembership']);
    Route::post('/manage-membership/change-status', [AdminController::class, 'changeStatus']);
    Route::get('/manage-rewards', [AdminController::class, 'manageRewards']);
    Route::post('/manage-rewards/add', [AdminController::class, 'addReward']);
    Route::post('/manage-rewards/update', [AdminController::class, 'updateReward']);
    Route::post('/manage-rewards/archive', [AdminController::class, 'archiveReward']);
    Route::match(['get', 'post'], '/generate-notification', [AdminController::class, 'showNotifForm']);
    Route::post('/generate-notification/send', [AdminController::class, 'saveOrSendNotif']);
    Route::get('/generate-report', [AdminController::class, 'showReport']);
});

// ─── Cashier operations ───
Route::prefix('cashier')->group(function () {
    Route::get('/manage-points', [CashierController::class, 'managePoints']);
    Route::post('/manage-points/add', [CashierController::class, 'addPoints']);
    Route::post('/manage-points/redeem', [CashierController::class, 'redeemPoints']);
});
