<?php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function __construct(
        protected NotificationRepository $repository
    ) {}

    public function getAll(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->findByUser($userId, $perPage);
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->repository->getUnreadCount($userId);
    }

    public function markAsRead(int $id, int $userId): void
    {
        $this->repository->markAsRead($id, $userId);
    }

    public function markAllAsRead(int $userId): void
    {
        $this->repository->markAllAsRead($userId);
    }

    public function create(int $userId, string $type, string $title, string $message, ?array $data = null): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function createBudgetOverspent(int $userId, $budget): void
    {
        $exists = Notification::where('user_id', $userId)
            ->where('type', 'budget_overspent')
            ->where('data->budget_id', $budget->id)
            ->whereNull('read_at')
            ->exists();

        if (!$exists) {
            $this->create(
                $userId,
                'budget_overspent',
                'Budget Terlampaui!',
                "Budget '{$budget->name}' sudah melebihi batas. Total pengeluaran: Rp " . number_format($budget->spent, 0, ',', '.'),
                ['budget_id' => $budget->id, 'category_id' => $budget->category_id]
            );
        }
    }

    public function createGoalReached(int $userId, $goal): void
    {
        $this->create(
            $userId,
            'goal_reached',
            'Target Tercapai!',
            "Selamat! Target tabungan '{$goal->name}' telah tercapai.",
            ['goal_id' => $goal->id]
        );
    }
}
