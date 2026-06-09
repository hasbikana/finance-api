<?php

namespace App\Repositories;

use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BudgetRepository
{
    public function findByUser(int $userId, ?string $period = null): Collection
    {
        $query = Budget::where('user_id', $userId)->with('category');

        if ($period) {
            $query->where('period', $period);
        }

        if ($period === 'monthly') {
            $query->where('month', Carbon::now()->format('Y-m'));
        }

        return $query->orderBy('name')->get();
    }

    public function findById(int $id, int $userId): ?Budget
    {
        return Budget::where('id', $id)
            ->where('user_id', $userId)
            ->with('category')
            ->first();
    }

    public function getWithProgress(int $userId, string $month = null): Collection
    {
        $month = $month ?? Carbon::now()->format('Y-m');

        return Budget::where('user_id', $userId)
            ->where('period', 'monthly')
            ->where('month', $month)
            ->with('category')
            ->orderBy('name')
            ->get();
    }
}
