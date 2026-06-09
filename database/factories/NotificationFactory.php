<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $types = ['budget_overspent', 'goal_reached', 'info', 'system'];
        $type = $this->faker->randomElement($types);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'title' => match($type) {
                'budget_overspent' => 'Budget Terlampaui!',
                'goal_reached' => 'Target Tercapai!',
                default => $this->faker->sentence(3),
            },
            'message' => $this->faker->paragraph(1),
            'data' => null,
            'read_at' => $this->faker->optional()->dateTime(),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => ['read_at' => null]);
    }
}
