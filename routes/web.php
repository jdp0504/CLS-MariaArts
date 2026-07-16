<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MembershipStatusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViewPointController;
use App\Http\Controllers\UpdateRewardController;
use App\Http\Controllers\LoyaltyPointController;
use App\Http\Controllers\GenerateNotificationController;
use App\Http\Controllers\ViewReportController;

// ─── Auth routes ───
Route::get('/', [LoginController::class, 'showForm']);
Route::get('/my-login', [LoginController::class, 'showForm']);
Route::post('/my-login', [LoginController::class, 'processLogin']);
Route::post('/logout', [LoginController::class, 'logout']);

// ─── Registration ───
Route::get('/register', [RegisterController::class, 'showRegisterForm']);
Route::post('/register', [RegisterController::class, 'registerCustomer']);
Route::get('/registration-success', [RegisterController::class, 'showRegistrationSuccess']);

// ─── Dashboards ───
Route::get('/admin-dashboard', [MembershipStatusController::class, 'showAdminDashboard']);
Route::get('/cashier-dashboard', [LoyaltyPointController::class, 'showCashierDashboard']);
Route::get('/customer-dashboard', [ViewPointController::class, 'showCustomerDashboard']);

// ─── Customer profile ───
Route::prefix('customer')->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile']);
    Route::put('/profile', [ProfileController::class, 'updateProfile']);
    Route::get('/points-rewards', [ViewPointController::class, 'viewPointsRewards']);
});

// ─── Admin operations ───
Route::prefix('admin')->group(function () {
    Route::get('/manage-membership', [MembershipStatusController::class, 'manageMembership']);
    Route::post('/manage-membership/change-status', [MembershipStatusController::class, 'changeStatus']);
    Route::get('/manage-rewards', [UpdateRewardController::class, 'manageRewards']);
    Route::post('/manage-rewards/add', [UpdateRewardController::class, 'addReward']);
    Route::post('/manage-rewards/update', [UpdateRewardController::class, 'updateReward']);
    Route::post('/manage-rewards/archive', [UpdateRewardController::class, 'archiveReward']);
    Route::match(['get', 'post'], '/generate-notification', [GenerateNotificationController::class, 'showNotifForm']);
    Route::post('/generate-notification/send', [GenerateNotificationController::class, 'saveOrSendNotif']);
    Route::get('/generate-report', [ViewReportController::class, 'showReport']);
});

// ─── Cashier operations ───
Route::prefix('cashier')->group(function () {
    Route::get('/manage-points', [LoyaltyPointController::class, 'managePoints']);
    Route::post('/manage-points/add', [LoyaltyPointController::class, 'addPoints']);
    Route::post('/manage-points/redeem', [LoyaltyPointController::class, 'redeemPoints']);
});
