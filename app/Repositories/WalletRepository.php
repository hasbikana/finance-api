<?php

namespace App\Repositories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;

class WalletRepository
{
    public function findByUser(int $userId): Collection
    {
        return Wallet::where('user_id', $userId)
            ->orderBy('type')
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id, int $userId): ?Wallet
    {
        return Wallet::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function getTotalBalance(int $userId): int
    {
        return (int) Wallet::where('user_id', $userId)
            ->where('is_active', true)
            ->sum('balance');
    }

    public function updateBalance(int $walletId, int $amount, string $type): void
    {
        $wallet = Wallet::findOrFail($walletId);
        if ($type === 'income') {
            $wallet->increment('balance', $amount);
        } else {
            $wallet->decrement('balance', $amount);
        }
    }
}
