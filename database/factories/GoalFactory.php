<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalFactory extends Factory
{
    protected $model = Goal::class;

    public function definition(): array
    {
        $target = $this->faker->numberBetween(1000000, 50000000);

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement(['Beli Motor', 'Liburan', 'Dana Darurat', 'Beli Laptop', 'Renovasi Rumah', 'Umroh']),
            'target_amount' => $target,
            'current_amount' => $this->faker->numberBetween(0, (int) ($target * 0.8)),
            'target_date' => $this->faker->dateTimeBetween('+1 month', '+12 months')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'completed']),
            'description' => $this->faker->sentence(),
        ];
    }
}
