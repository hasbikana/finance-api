<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use Carbon\Carbon;

class DashboardService
{
    public function __construct(
        protected TransactionRepository $transactionRepo
    ) {}

    public function getSummary(int $userId): array
    {
        $totalIncome = $this->transactionRepo->getTotalIncome($userId);
        $totalExpense = $this->transactionRepo->getTotalExpense($userId);
        $balance = $totalIncome - $totalExpense;

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $thisMonthIncome = \App\Models\Transaction::withTrashed()
            ->where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $thisMonthExpense = \App\Models\Transaction::withTrashed()
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $balance,
            'this_month_income' => (int) $thisMonthIncome,
            'this_month_expense' => (int) $thisMonthExpense,
            'this_month_balance' => (int) ($thisMonthIncome - $thisMonthExpense),
            'current_month' => Carbon::now()->format('F Y'),
        ];
    }

    public function getRecentTransactions(int $userId, int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $this->transactionRepo->getRecent($userId, $limit);
    }

    public function getMonthlyTrend(int $userId, int $months = 6): array
    {
        $trend = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $summary = $this->transactionRepo->getMonthlySummary($userId, $month->format('Y-m'));
            $summary['month'] = $month->format('M Y');
            $summary['month_iso'] = $month->format('Y-m');
            $trend[] = $summary;
        }
        return $trend;
    }
}
