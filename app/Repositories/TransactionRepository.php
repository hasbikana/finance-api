<?php

namespace App\Repositories;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository
{
    public function findFiltered(int $userId, array $filters = [], int $perPage = 50): LengthAwarePaginator
    {
        $query = Transaction::where('user_id', $userId)->with(['category', 'wallet']);

        if (!empty($filters['type']) && in_array($filters['type'], ['income', 'expense'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['wallet_id'])) {
            $query->where('wallet_id', $filters['wallet_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('date', '<=', $filters['end_date']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findById(int $id, int $userId): ?Transaction
    {
        return Transaction::where('id', $id)
            ->where('user_id', $userId)
            ->with(['category', 'wallet'])
            ->first();
    }

    public function getRecent(int $userId, int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Transaction::where('user_id', $userId)
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getMonthlySummary(int $userId, string $month = null): array
    {
        $month = $month ?? Carbon::now()->format('Y-m');
        $startDate = Carbon::parse($month . '-01')->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month . '-01')->endOfMonth()->toDateString();

        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $expense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return [
            'month' => $month,
            'income' => (int) $income,
            'expense' => (int) $expense,
            'balance' => (int) ($income - $expense),
        ];
    }

    public function getTotalIncome(int $userId): int
    {
        return (int) Transaction::withTrashed()
            ->where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');
    }

    public function getTotalExpense(int $userId): int
    {
        return (int) Transaction::withTrashed()
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');
    }
}
