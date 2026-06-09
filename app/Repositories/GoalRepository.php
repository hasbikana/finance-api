<?php

namespace App\Repositories;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Collection;

class GoalRepository
{
    public function findByUser(int $userId, ?string $status = null): Collection
    {
        $query = Goal::where('user_id', $userId);

        if ($status && in_array($status, ['active', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function findById(int $id, int $userId): ?Goal
    {
        return Goal::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function getActiveGoals(int $userId): Collection
    {
        return Goal::where('user_id', $userId)
            ->where('status', 'active')
            ->orderBy('target_date')
            ->get();
    }
}
