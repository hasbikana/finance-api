<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use App\Repositories\BudgetRepository;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionService
{
    public function __construct(
        protected TransactionRepository $repository,
        protected WalletRepository $walletRepo,
        protected BudgetRepository $budgetRepo,
        protected NotificationService $notificationService
    ) {}

    public function getFiltered(int $userId, array $filters = [], int $perPage = 50): LengthAwarePaginator
    {
        return $this->repository->findFiltered($userId, $filters, $perPage);
    }

    public function getById(int $id, int $userId): ?Transaction
    {
        return $this->repository->findById($id, $userId);
    }

    public function getRecent(int $userId, int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->getRecent($userId, $limit);
    }

    public function create(int $userId, array $data): Transaction
    {
        $transaction = Transaction::create([
            'user_id' => $userId,
            'category_id' => $data['category_id'],
            'wallet_id' => $data['wallet_id'] ?? null,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
        ]);

        if (!empty($data['wallet_id'])) {
            $this->walletRepo->updateBalance($data['wallet_id'], $data['amount'], $data['type']);
        }

        $this->updateBudgetSpent($userId, $data['category_id'], $data);

        $transaction->load(['category', 'wallet']);
        return $transaction;
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        $oldAmount = $transaction->amount;
        $oldType = $transaction->type;
        $oldWalletId = $transaction->wallet_id;

        if ($oldWalletId) {
            $this->walletRepo->updateBalance($oldWalletId, $oldAmount, $oldType === 'income' ? 'expense' : 'income');
        }

        $transaction->update([
            'category_id' => $data['category_id'],
            'wallet_id' => $data['wallet_id'] ?? null,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'date' => $data['date'],
        ]);

        if (!empty($data['wallet_id'])) {
            $this->walletRepo->updateBalance($data['wallet_id'], $data['amount'], $data['type']);
        }

        $this->updateBudgetSpent($transaction->user_id, $data['category_id'], $data);

        $transaction->load(['category', 'wallet']);
        return $transaction;
    }

    public function delete(Transaction $transaction): void
    {
        if ($transaction->wallet_id) {
            $reverseType = $transaction->type === 'income' ? 'expense' : 'income';
            $this->walletRepo->updateBalance($transaction->wallet_id, $transaction->amount, $reverseType);
        }

        $transaction->delete();
    }

    public function quickCreate(int $userId, array $data): Transaction
    {
        $data['date'] = $data['date'] ?? Carbon::now()->toDateString();
        return $this->create($userId, $data);
    }

    public function getMonthlySummary(int $userId, ?string $month = null): array
    {
        return $this->repository->getMonthlySummary($userId, $month);
    }

    protected function updateBudgetSpent(int $userId, int $categoryId, array $data): void
    {
        if ($data['type'] !== 'expense') {
            return;
        }

        $month = Carbon::parse($data['date'])->format('Y-m');

        $budgets = \App\Models\Budget::where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->where('period', 'monthly')
            ->where('month', $month)
            ->get();

        foreach ($budgets as $budget) {
            $spent = Transaction::where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->where('type', 'expense')
                ->whereBetween('date', [
                    Carbon::parse($month . '-01')->startOfMonth()->toDateString(),
                    Carbon::parse($month . '-01')->endOfMonth()->toDateString(),
                ])
                ->sum('amount');

            $budget->update(['spent' => $spent]);

            if ($budget->fresh()->isOverBudget) {
                $this->notificationService->createBudgetOverspent($userId, $budget);
            }
        }
    }
}
