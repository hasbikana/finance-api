<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Get chart data - expense by category
     */
    public function charts(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        
        // Default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Expense by category
        $expenseByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'category_id' => $item->category_id,
                    'category_name' => $item->category?->name ?? 'Unknown',
                    'total' => (int) $item->total,
                ];
            });

        // Income by category
        $incomeByCategory = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'category_id' => $item->category_id,
                    'category_name' => $item->category?->name ?? 'Unknown',
                    'total' => (int) $item->total,
                ];
            });

        // Monthly trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->startOfMonth()->toDateString();
            $monthEnd = $month->endOfMonth()->toDateString();

            $income = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'month_iso' => $month->format('Y-m'),
                'income' => (int) $income,
                'expense' => (int) $expense,
                'balance' => (int) ($income - $expense),
            ];
        }

        // Daily trend (current month)
        $dailyTrend = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date', 'type', DB::raw('SUM(amount) as total'))
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($day) {
                $income = $day->where('type', 'income')->first()?->total ?? 0;
                $expense = $day->where('type', 'expense')->first()?->total ?? 0;
                return [
                    'date' => $day->first()->date,
                    'income' => (int) $income,
                    'expense' => (int) $expense,
                ];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'message' => 'Data chart berhasil diambil.',
            'data' => [
                'expense_by_category' => $expenseByCategory,
                'income_by_category' => $incomeByCategory,
                'monthly_trend' => $monthlyTrend,
                'daily_trend' => $dailyTrend,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
            ],
        ], 200);
    }

    /**
     * Export transactions to CSV
     */
    public function export(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        // Generate CSV content
        $csv = "Date,Type,Category,Amount,Description\n";
        
        foreach ($transactions as $t) {
            $date = $t->date->toDateString();
            $type = $t->type;
            $category = $t->category?->name ?? 'Unknown';
            $amount = $t->amount;
            $description = str_replace(['"', ',', "\n", "\r"], ' ', $t->description ?? '');
            
            $csv .= "{$date},{$type},{$category},{$amount},{$description}\n";
        }

        // Calculate summary
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        $csv .= "\nSummary\n";
        $csv .= "Total Income,{$totalIncome}\n";
        $csv .= "Total Expense,{$totalExpense}\n";
        $csv .= "Balance," . ($totalIncome - $totalExpense) . "\n";

        return response()->json([
            'status' => 'success',
            'message' => 'Data export berhasil dibuat.',
            'data' => [
                'filename' => 'transactions_' . $startDate . '_' . $endDate . '.csv',
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'total_records' => $transactions->count(),
                'total_income' => (int) $totalIncome,
                'total_expense' => (int) $totalExpense,
                'csv_content' => base64_encode($csv),
            ],
        ], 200);
    }

    /**
     * Monthly report
     */
    public function monthly(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $monthInput = $request->get('month');
        $yearInput = $request->get('year');

        if ($monthInput && $yearInput) {
            $year = (int) $yearInput;
            $monthNum = (int) $monthInput;
            $month = sprintf('%04d-%02d', $year, $monthNum);
        } else {
            $month = $request->get('month', Carbon::now()->format('Y-m'));
            $year = (int) substr($month, 0, 4);
            $monthNum = (int) substr($month, 5, 2);
        }

        $startDate = Carbon::createFromDate($year, $monthNum, 1)
            ->startOfMonth()
            ->toDateString();

        $endDate = Carbon::createFromDate($year, $monthNum, 1)
            ->endOfMonth()
            ->toDateString();

        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $totalIncome = $transactions
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = $transactions
            ->where('type', 'expense')
            ->sum('amount');

        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(function ($items, $categoryId) {
                $first = $items->first();

                return [
                    'category_id' => $categoryId,
                    'category_name' => $first->category?->name ?? 'Unknown',
                    'total' => (int) $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })
            ->values();

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($items, $categoryId) {
                $first = $items->first();

                return [
                    'category_id' => $categoryId,
                    'category_name' => $first->category?->name ?? 'Unknown',
                    'total' => (int) $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan bulanan berhasil diambil.',
            'data' => [
                'month' => $month,
                'month_name' => Carbon::createFromDate($year, $monthNum, 1)->format('F Y'),
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],

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
            ],
        ], 200);
    }
}