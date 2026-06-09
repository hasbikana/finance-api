<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\Api\InsightController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RecurringTransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('/user', [AuthController::class, 'register'])->name('register');
Route::post('/user/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'profile'])->name('profile');
    Route::post('/user/logout', [AuthController::class, 'logout'])->name('logout');
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::post('/transactions/quick', [TransactionController::class, 'quickStore'])->name('transactions.quick');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');

    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::get('/goals/{goal}', [GoalController::class, 'show'])->name('goals.show');
    Route::put('/goals/{goal}', [GoalController::class, 'update'])->name('goals.update');
    Route::post('/goals/{goal}/add-savings', [GoalController::class, 'addSavings'])->name('goals.addSavings');
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');

    Route::get('/reports/charts', [ReportController::class, 'charts'])->name('reports.charts');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.exportCsv');

    Route::apiResource('wallets', WalletController::class);

    Route::apiResource('budgets', BudgetController::class);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read']);
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll']);

    Route::post('/device-tokens', [DeviceTokenController::class, 'store']);

    Route::apiResource('recurring-transactions', RecurringTransactionController::class);

    Route::get('/insights/monthly-comparison', [InsightController::class, 'monthlyComparison']);
    Route::get('/insights/auto-insights', [InsightController::class, 'autoInsights']);
});
