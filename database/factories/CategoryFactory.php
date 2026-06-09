<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;

class CategoryFactory extends \Illuminate\Database\Eloquent\Factories\Factory
{
    protected $model = \App\Models\Category::class;

    public function definition(): array
    {
        $categories = ['Gaji', 'Bonus', 'Investasi', 'Makanan', 'Transportasi', 'Belanja', 'Hiburan', 'Kesehatan', 'Pendidikan', 'Tagihan'];
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement($categories),
        ];
    }
}
