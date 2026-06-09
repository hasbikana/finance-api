<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        $amount = $this->faker->numberBetween(500000, 5000000);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => $this->faker->words(2, true),
            'amount' => $amount,
            'spent' => $this->faker->numberBetween(0, (int) ($amount * 1.2)),
            'period' => 'monthly',
            'month' => now()->format('Y-m'),
            'is_active' => true,
        ];
    }
}
