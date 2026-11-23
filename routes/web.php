<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SystemAdmin\DashboardController;
use App\Http\Controllers\SystemAdmin\ClubController;

// Public Routes (Login & Register)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Add these two new lines:
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Placeholder blank page for Member & Club Admin portals (handled by groupmates)
Route::get('/portal-placeholder', function () {
    return response('', 200);
})->name('portal.placeholder');

// System Admin Protected Routes
// System Admin Protected Routes
Route::middleware(['auth', 'system.admin'])->prefix('system-admin')->name('system_admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clubs', ClubController::class);
    
    // Club Admin Management Routes
    Route::get('/club-admins', [\App\Http\Controllers\SystemAdmin\ClubAdminController::class, 'index'])->name('club_admins.index');
    Route::post('/club-admins/{user}/approve', [\App\Http\Controllers\SystemAdmin\ClubAdminController::class, 'approve'])->name('club_admins.approve');
    Route::post('/club-admins/{user}/reject', [\App\Http\Controllers\SystemAdmin\ClubAdminController::class, 'reject'])->name('club_admins.reject');
    
    
    Route::put('/club-admins/{id}', [\App\Http\Controllers\SystemAdmin\ClubAdminController::class, 'update'])->name('club_admins.update');
    Route::delete('/club-admins/{id}', [\App\Http\Controllers\SystemAdmin\ClubAdminController::class, 'destroy'])->name('club_admins.destroy');

    // Member Management Routes
    Route::get('/members', [\App\Http\Controllers\SystemAdmin\MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{user}', [\App\Http\Controllers\SystemAdmin\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{user}/edit', [\App\Http\Controllers\SystemAdmin\MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{user}', [\App\Http\Controllers\SystemAdmin\MemberController::class, 'update'])->name('members.update');

    // Membership Overview Route
    Route::get('/memberships', [\App\Http\Controllers\SystemAdmin\MembershipOverviewController::class, 'index'])->name('memberships.index');

    // Settings Routes
    Route::get('/settings', [\App\Http\Controllers\SystemAdmin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [\App\Http\Controllers\SystemAdmin\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [\App\Http\Controllers\SystemAdmin\SettingsController::class, 'updatePassword'])->name('settings.password');

    // Settings Routes
    Route::get('/settings', [\App\Http\Controllers\SystemAdmin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [\App\Http\Controllers\SystemAdmin\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [\App\Http\Controllers\SystemAdmin\SettingsController::class, 'updatePassword'])->name('settings.password');
});