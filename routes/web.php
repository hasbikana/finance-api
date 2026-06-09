<?php

use App\Http\Controllers\Web\AuthController as WebAuthController;
use App\Http\Controllers\Web\BudgetController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\GoalController;
use App\Http\Controllers\Web\InsightController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\WalletController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware(RedirectIfAuthenticated::class)->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('web.login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('web.login.submit');
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('web.register');
    Route::post('/register', [WebAuthController::class, 'register'])->name('web.register.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('web.dashboard');

    Route::post('/logout', [WebAuthController::class, 'logout'])->name('web.logout');

    // Profile
    Route::get('/profile', [WebAuthController::class, 'profile'])->name('web.profile');
    Route::put('/profile', [WebAuthController::class, 'updateProfile'])->name('web.profile.update');
    Route::put('/password', [WebAuthController::class, 'updatePassword'])->name('web.password.update');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('web.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('web.categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('web.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('web.categories.destroy');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('web.transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('web.transactions.store');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('web.transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('web.transactions.destroy');
    Route::get('/transactions/export-csv', [TransactionController::class, 'exportCsv'])->name('web.transactions.exportCsv');

    // Wallets
    Route::get('/wallets', [WalletController::class, 'index'])->name('web.wallets.index');
    Route::post('/wallets', [WalletController::class, 'store'])->name('web.wallets.store');
    Route::put('/wallets/{id}', [WalletController::class, 'update'])->name('web.wallets.update');
    Route::delete('/wallets/{id}', [WalletController::class, 'destroy'])->name('web.wallets.destroy');

    // Budgets
    Route::get('/budgets', [BudgetController::class, 'index'])->name('web.budgets.index');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('web.budgets.store');
    Route::put('/budgets/{id}', [BudgetController::class, 'update'])->name('web.budgets.update');
    Route::delete('/budgets/{id}', [BudgetController::class, 'destroy'])->name('web.budgets.destroy');

    // Goals
    Route::get('/goals', [GoalController::class, 'index'])->name('web.goals.index');
    Route::post('/goals', [GoalController::class, 'store'])->name('web.goals.store');
    Route::put('/goals/{id}', [GoalController::class, 'update'])->name('web.goals.update');
    Route::post('/goals/{id}/add-savings', [GoalController::class, 'addSavings'])->name('web.goals.addSavings');
    Route::delete('/goals/{id}', [GoalController::class, 'destroy'])->name('web.goals.destroy');

    // Reports
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('web.reports.monthly');
    Route::get('/reports/yearly', [ReportController::class, 'yearly'])->name('web.reports.yearly');
    Route::get('/reports/category', [ReportController::class, 'category'])->name('web.reports.category');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('web.reports.exportPdf');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('web.reports.exportCsv');

    // Insights
    Route::get('/insights', [InsightController::class, 'index'])->name('web.insights.index');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('web.notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->name('web.notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('web.notifications.readAll');
});
