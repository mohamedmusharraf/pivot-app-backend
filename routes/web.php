<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserProfileController; 
use App\Http\Controllers\Admin\ActivityController; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('/view-logs', function () {
    return view('viewLogs');
});

Route::get('/app-analyze', function () {
    return view('appAnalyze');
});



Route::get('/invite', function () {
    return view('invite-fallback');
});


// Route::prefix('admin')->name('admin.')->group(function () {

//     // 1. Dashboard Home
//     Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

//     // Guest Auth Routes
//     Route::middleware('guest')->group(function () {
//         Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
//         Route::post('login', [AuthController::class, 'login'])->name('login.post');
//     });

//     // Authenticated Admin Routes
//     Route::middleware('auth')->group(function () {
//         Route::post('logout', [AuthController::class, 'logout'])->name('logout');
//     });

//     // 2. Users Management
//     Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
//     Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
//     Route::resource('users', UserController::class);

//     // 3. User Profiles
//     Route::resource('user-profiles', UserProfileController::class)->only(['index', 'show', 'update', 'destroy']);

//     // 4. Activities
//     Route::resource('activities', ActivityController::class);

//     // 5. Categories
//     Route::resource('categories', CategoryController::class);

//     // 6. Challenge Packs
//     Route::resource('challenge-packs', ChallengePackController::class);

//     // 7. Groups
//     Route::resource('groups', GroupController::class);

//     // 8. Research Articles
//     Route::get('research-articles/{article}/download', [ResearchArticleController::class, 'downloadPdf'])->name('research-articles.download');
//     Route::resource('research-articles', ResearchArticleController::class);

//     // 9. Subscription Management
//     Route::resource('subscriptions', SubscriptionController::class);

//     // 10. Payment History
//     Route::resource('payment-history', PaymentHistoryController::class)->only(['index', 'show']);

//     // 11. User Activity Logs
//     Route::get('activity-logs', [UserActivityLogController::class, 'index'])->name('activity-logs.index');
//     Route::get('activity-logs/{log}', [UserActivityLogController::class, 'show'])->name('activity-logs.show');

//     // 12. Notifications
//     Route::post('notifications/{notification}/send', [NotificationController::class, 'sendNow'])->name('notifications.send');
//     Route::resource('notifications', NotificationController::class);

//     // 13. Reports & Analytics
//     Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
//     Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

//     // 14. Admin Management
//     Route::resource('admins', AdminManagementController::class);

//     // 15. Roles & Permissions
//     Route::resource('roles', RolePermissionController::class);

//     // 16. Settings
//     Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
//     Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
// });