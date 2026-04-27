<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $totalIncome = Transaction::withTrashed()
            ->where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = Transaction::withTrashed()
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $thisMonthIncome = Transaction::withTrashed()
            ->where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $thisMonthExpense = Transaction::withTrashed()
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $transactionCount = Transaction::where('user_id', $userId)->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Data dashboard berhasil diambil.',
            'data' => [
                'total_income' => (int) $totalIncome,
                'total_expense' => (int) $totalExpense,
                'balance' => (int) $balance,
                'this_month_income' => (int) $thisMonthIncome,
                'this_month_expense' => (int) $thisMonthExpense,
                'this_month_balance' => (int) ($thisMonthIncome - $thisMonthExpense),
                'transaction_count' => (int) $transactionCount,
                'current_month' => Carbon::now()->format('F Y'),
            ],
        ], 200);
    }
}