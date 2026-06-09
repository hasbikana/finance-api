<?php

namespace App\Services;

use App\Models\Wallet;
use App\Repositories\WalletRepository;
use Illuminate\Database\Eloquent\Collection;

class WalletService
{
    public function __construct(
        protected WalletRepository $repository
    ) {}

    public function getAll(int $userId): Collection
    {
        return $this->repository->findByUser($userId);
    }

    public function getById(int $id, int $userId): ?Wallet
    {
        return $this->repository->findById($id, $userId);
    }

    public function getTotalBalance(int $userId): int
    {
        return $this->repository->getTotalBalance($userId);
    }

    public function create(int $userId, array $data): Wallet
    {
        $colors = ['cash' => '#22C55E', 'bank' => '#0F766E', 'ewallet' => '#14B8A6'];

        return Wallet::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'type' => $data['type'],
            'balance' => $data['balance'] ?? 0,
            'icon' => $data['icon'] ?? null,
            'color' => $data['color'] ?? ($colors[$data['type']] ?? '#0F766E'),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(Wallet $wallet, array $data): Wallet
    {
        $wallet->update($data);
        return $wallet->fresh();
    }

    public function delete(Wallet $wallet): void
    {
        $wallet->update(['is_active' => false]);
    }

    public function transfer(Wallet $from, Wallet $to, int $amount): void
    {
        $from->decrement('balance', $amount);
        $to->increment('balance', $amount);
    }
}
