<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function __construct(
        protected TransactionRepository $transactionRepo
    ) {}

    public function getCharts(int $userId, ?string $startDate = null, ?string $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $endDate ?? Carbon::now()->endOfMonth()->toDateString();

        $expenseByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(fn($item) => [
                'category_id' => $item->category_id,
                'category_name' => $item->category?->name ?? 'Unknown',
                'total' => (int) $item->total,
            ]);

        $incomeByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(fn($item) => [
                'category_id' => $item->category_id,
                'category_name' => $item->category?->name ?? 'Unknown',
                'total' => (int) $item->total,
            ]);

        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $summary = $this->transactionRepo->getMonthlySummary($userId, $month->format('Y-m'));
            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'month_iso' => $month->format('Y-m'),
                'income' => $summary['income'],
                'expense' => $summary['expense'],
                'balance' => $summary['balance'],
            ];
        }

        $dailyTrend = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date', 'type', DB::raw('SUM(amount) as total'))
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(fn($day) => [
                'date' => $day->first()->date,
                'income' => (int) ($day->where('type', 'income')->first()?->total ?? 0),
                'expense' => (int) ($day->where('type', 'expense')->first()?->total ?? 0),
            ])
            ->values();

        return [
            'expense_by_category' => $expenseByCategory,
            'income_by_category' => $incomeByCategory,
            'monthly_trend' => $monthlyTrend,
            'daily_trend' => $dailyTrend,
            'period' => ['start_date' => $startDate, 'end_date' => $endDate],
        ];
    }

    public function getMonthly(int $userId, ?string $month = null): array
    {
        $month = $month ?? Carbon::now()->format('Y-m');
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);

        $startDate = Carbon::createFromDate($year, $monthNum, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($year, $monthNum, 1)->endOfMonth()->toDateString();

        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(fn($items, $catId) => [
                'category_id' => $catId,
                'category_name' => $items->first()->category?->name ?? 'Unknown',
                'total' => (int) $items->sum('amount'),
                'count' => $items->count(),
            ])->values();

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(fn($items, $catId) => [
                'category_id' => $catId,
                'category_name' => $items->first()->category?->name ?? 'Unknown',
                'total' => (int) $items->sum('amount'),
                'count' => $items->count(),
            ])->values();

        return [
            'month' => $month,
            'month_name' => Carbon::createFromDate($year, $monthNum, 1)->format('F Y'),
            'period' => ['start_date' => $startDate, 'end_date' => $endDate],
            'total_income' => (int) $totalIncome,
            'total_expense' => (int) $totalExpense,
            'balance' => (int) ($totalIncome - $totalExpense),
            'transaction_count' => $transactions->count(),
            'summary' => [
                'total_income' => (int) $totalIncome,
                'total_expense' => (int) $totalExpense,
                'balance' => (int) ($totalIncome - $totalExpense),
                'transaction_count' => $transactions->count(),
            ],
            'transactions' => $transactions,
            'income_by_category' => $incomeByCategory,
            'expense_by_category' => $expenseByCategory,
        ];
    }

    public function generateCsv(int $userId, ?string $startDate = null, ?string $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $endDate ?? Carbon::now()->endOfMonth()->toDateString();

        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $csv = "Date,Type,Category,Amount,Description\n";

        foreach ($transactions as $t) {
            $date = $t->date->toDateString();
            $type = $t->type;
            $category = $t->category?->name ?? 'Unknown';
            $amount = $t->amount;
            $description = str_replace(['"', ',', "\n", "\r"], ' ', $t->description ?? '');

            $csv .= "{$date},{$type},{$category},{$amount},{$description}\n";
        }

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $csv .= "\nSummary\n";
        $csv .= "Total Income,{$totalIncome}\n";
        $csv .= "Total Expense,{$totalExpense}\n";
        $csv .= "Balance," . ($totalIncome - $totalExpense) . "\n";

        return [
            'filename' => 'transactions_' . $startDate . '_' . $endDate . '.csv',
            'period' => ['start_date' => $startDate, 'end_date' => $endDate],
            'total_records' => $transactions->count(),
            'total_income' => (int) $totalIncome,
            'total_expense' => (int) $totalExpense,
            'csv_content' => base64_encode($csv),
        ];
    }
}
