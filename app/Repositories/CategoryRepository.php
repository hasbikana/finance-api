<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository
{
    public function findByUser(int $userId): Collection
    {
        return Category::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findById(int $id, int $userId): ?Category
    {
        return Category::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function searchByName(int $userId, string $search): Collection
    {
        return Category::where('user_id', $userId)
            ->where('name', 'like', "%{$search}%")
            ->orderBy('name')
            ->get();
    }

    public function getWithTransactionCount(int $userId): Collection
    {
        return Category::where('user_id', $userId)
            ->withCount('transactions')
            ->orderBy('name')
            ->get();
    }
}
