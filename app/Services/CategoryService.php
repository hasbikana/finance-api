<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $repository
    ) {}

    public function getAll(int $userId): Collection
    {
        return $this->repository->findByUser($userId);
    }

    public function getById(int $id, int $userId): ?Category
    {
        return $this->repository->findById($id, $userId);
    }

    public function create(int $userId, array $data): Category
    {
        return Category::create([
            'user_id' => $userId,
            'name' => $data['name'],
        ]);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update(['name' => $data['name']]);
        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    public function search(int $userId, string $search): Collection
    {
        return $this->repository->searchByName($userId, $search);
    }
}
