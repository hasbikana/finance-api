<?php

namespace App\Services;

use App\Models\Goal;
use App\Repositories\GoalRepository;
use Illuminate\Database\Eloquent\Collection;

class GoalService
{
    public function __construct(
        protected GoalRepository $repository
    ) {}

    public function getAll(int $userId, ?string $status = null): Collection
    {
        return $this->repository->findByUser($userId, $status);
    }

    public function getById(int $id, int $userId): ?Goal
    {
        return $this->repository->findById($id, $userId);
    }

    public function getActiveGoals(int $userId): Collection
    {
        return $this->repository->getActiveGoals($userId);
    }

    public function create(int $userId, array $data): Goal
    {
        return Goal::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'target_amount' => $data['target_amount'],
            'target_date' => $data['target_date'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function update(Goal $goal, array $data): Goal
    {
        $goal->update([
            'name' => $data['name'],
            'target_amount' => $data['target_amount'],
            'target_date' => $data['target_date'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return $goal->fresh();
    }

    public function addSavings(Goal $goal, int $amount): Goal
    {
        $goal->current_amount += $amount;

        if ($goal->current_amount >= $goal->target_amount) {
            $goal->status = 'completed';
        }

        $goal->save();
        return $goal->fresh();
    }

    public function delete(Goal $goal): void
    {
        $goal->delete();
    }
}
