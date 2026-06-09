<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Transaction;
use App\Repositories\BudgetRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BudgetService
{
    public function __construct(
        protected BudgetRepository $repository
    ) {}

    public function getAll(int $userId, ?string $period = null): Collection
    {
        return $this->repository->findByUser($userId, $period);
    }

    public function getById(int $id, int $userId): ?Budget
    {
        return $this->repository->findById($id, $userId);
    }

    public function getWithProgress(int $userId, ?string $month = null): Collection
    {
        return $this->repository->getWithProgress($userId, $month);
    }

    public function create(int $userId, array $data): Budget
    {
        $budget = Budget::create([
            'user_id' => $userId,
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'amount' => $data['amount'],
            'period' => $data['period'] ?? 'monthly',
            'month' => $data['month'] ?? Carbon::now()->format('Y-m'),
        ]);

        $this->recalculateSpent($budget);
        return $budget->fresh();
    }

    public function update(Budget $budget, array $data): Budget
    {
        $budget->update($data);
        $this->recalculateSpent($budget);
        return $budget->fresh();
    }

    public function delete(Budget $budget): void
    {
        $budget->delete();
    }

    public function recalculateSpent(Budget $budget): void
    {
        $month = $budget->month ?? Carbon::now()->format('Y-m');

        $spent = Transaction::where('user_id', $budget->user_id)
            ->where('category_id', $budget->category_id)
            ->where('type', 'expense')
            ->whereBetween('date', [
                Carbon::parse($month . '-01')->startOfMonth()->toDateString(),
                Carbon::parse($month . '-01')->endOfMonth()->toDateString(),
            ])
            ->sum('amount');

        $budget->update(['spent' => $spent]);
    }
}
