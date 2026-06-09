<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\TransactionService;
use App\Services\WalletService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $service,
        protected TransactionService $transactionService,
        protected WalletService $walletService,
        protected NotificationService $notificationService
    ) {}

    public function index()
    {
        $userId = Auth::id();

        $summary = $this->service->getSummary($userId);
        $recentTransactions = $this->service->getRecentTransactions($userId, 5);
        $monthlyTrend = $this->service->getMonthlyTrend($userId, 6);
        $wallets = $this->walletService->getAll($userId);
        $unreadCount = $this->notificationService->getUnreadCount($userId);
        $categories = \App\Models\Category::where('user_id', $userId)->orderBy('name')->get();

        return view('dashboard.index', compact(
            'summary', 'recentTransactions', 'monthlyTrend', 'wallets', 'unreadCount', 'categories'
        ));
    }
}
