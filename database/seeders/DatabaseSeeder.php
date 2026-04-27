<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Gaji', 'user_id' => $user->id],
            ['name' => 'Investasi', 'user_id' => $user->id],
            ['name' => 'Makanan', 'user_id' => $user->id],
            ['name' => 'Transportasi', 'user_id' => $user->id],
            ['name' => 'Belanja', 'user_id' => $user->id],
            ['name' => 'Hiburan', 'user_id' => $user->id],
            ['name' => 'Tagihan', 'user_id' => $user->id],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Get category IDs
        $categoryIds = Category::where('user_id', $user->id)->pluck('id')->toArray();

        // Create transactions
        $transactions = [
            // Income transactions
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[0], // Gaji
                'type' => 'income',
                'amount' => 5000000,
                'description' => 'Gaji bulan Januari',
                'date' => Carbon::now()->subDays(25)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[1], // Investasi
                'type' => 'income',
                'amount' => 500000,
                'description' => 'Dividen investasi',
                'date' => Carbon::now()->subDays(20)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[0], // Gaji
                'type' => 'income',
                'amount' => 5000000,
                'description' => 'Gaji bulan Februari',
                'date' => Carbon::now()->subDays(5)->toDateString(),
            ],
            // Expense transactions
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[2], // Makanan
                'type' => 'expense',
                'amount' => 150000,
                'description' => 'Belanja bulanan',
                'date' => Carbon::now()->subDays(22)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[3], // Transportasi
                'type' => 'expense',
                'amount' => 200000,
                'description' => 'Bensin mobil',
                'date' => Carbon::now()->subDays(18)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[4], // Belanja
                'type' => 'expense',
                'amount' => 300000,
                'description' => 'Belanja pakaian',
                'date' => Carbon::now()->subDays(15)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[5], // Hiburan
                'type' => 'expense',
                'amount' => 100000,
                'description' => 'Nonton film',
                'date' => Carbon::now()->subDays(10)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[6], // Tagihan
                'type' => 'expense',
                'amount' => 250000,
                'description' => 'Listrik dan air',
                'date' => Carbon::now()->subDays(7)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[2], // Makanan
                'type' => 'expense',
                'amount' => 75000,
                'description' => 'Makan siang kantor',
                'date' => Carbon::now()->subDays(3)->toDateString(),
            ],
            [
                'user_id' => $user->id,
                'category_id' => $categoryIds[3], // Transportasi
                'type' => 'expense',
                'amount' => 50000,
                'description' => 'Ojol ke mall',
                'date' => Carbon::now()->subDays(1)->toDateString(),
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Test user: test@example.com / password123');
    }
}
