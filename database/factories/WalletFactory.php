<?php

namespace Database\Factories;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        $types = ['cash', 'bank', 'ewallet'];
        $type = $this->faker->randomElement($types);

        return [
            'user_id' => User::factory(),
            'name' => match($type) {
                'cash' => 'Cash',
                'bank' => $this->faker->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI']),
                'ewallet' => $this->faker->randomElement(['GoPay', 'OVO', 'Dana', 'ShopeePay']),
            },
            'type' => $type,
            'balance' => $this->faker->numberBetween(100000, 10000000),
            'icon' => null,
            'color' => null,
            'is_active' => true,
        ];
    }
}
