<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Goal;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

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

        $categoryIds = Category::where('user_id', $user->id)->pluck('id')->toArray();

        $wallets = [
            ['user_id' => $user->id, 'name' => 'Cash', 'type' => 'cash', 'balance' => 500000, 'color' => '#22C55E'],
            ['user_id' => $user->id, 'name' => 'BCA', 'type' => 'bank', 'balance' => 10000000, 'color' => '#0F766E'],
            ['user_id' => $user->id, 'name' => 'GoPay', 'type' => 'ewallet', 'balance' => 2000000, 'color' => '#14B8A6'],
        ];

        foreach ($wallets as $wallet) {
            Wallet::create($wallet);
        }

        $walletIds = Wallet::where('user_id', $user->id)->pluck('id')->toArray();

        $transactions = [
            ['category_id' => $categoryIds[0], 'wallet_id' => $walletIds[1], 'type' => 'income', 'amount' => 5000000, 'description' => 'Gaji bulan Januari', 'date' => Carbon::now()->subDays(25)],
            ['category_id' => $categoryIds[1], 'wallet_id' => $walletIds[1], 'type' => 'income', 'amount' => 500000, 'description' => 'Dividen investasi', 'date' => Carbon::now()->subDays(20)],
            ['category_id' => $categoryIds[0], 'wallet_id' => $walletIds[1], 'type' => 'income', 'amount' => 5000000, 'description' => 'Gaji bulan Februari', 'date' => Carbon::now()->subDays(5)],
            ['category_id' => $categoryIds[2], 'wallet_id' => $walletIds[2], 'type' => 'expense', 'amount' => 150000, 'description' => 'Belanja bulanan', 'date' => Carbon::now()->subDays(22)],
            ['category_id' => $categoryIds[3], 'wallet_id' => $walletIds[0], 'type' => 'expense', 'amount' => 200000, 'description' => 'Bensin mobil', 'date' => Carbon::now()->subDays(18)],
            ['category_id' => $categoryIds[4], 'wallet_id' => $walletIds[2], 'type' => 'expense', 'amount' => 300000, 'description' => 'Belanja pakaian', 'date' => Carbon::now()->subDays(15)],
            ['category_id' => $categoryIds[5], 'wallet_id' => $walletIds[2], 'type' => 'expense', 'amount' => 100000, 'description' => 'Nonton film', 'date' => Carbon::now()->subDays(10)],
            ['category_id' => $categoryIds[6], 'wallet_id' => $walletIds[1], 'type' => 'expense', 'amount' => 250000, 'description' => 'Listrik dan air', 'date' => Carbon::now()->subDays(7)],
            ['category_id' => $categoryIds[2], 'wallet_id' => $walletIds[0], 'type' => 'expense', 'amount' => 75000, 'description' => 'Makan siang kantor', 'date' => Carbon::now()->subDays(3)],
            ['category_id' => $categoryIds[3], 'wallet_id' => $walletIds[2], 'type' => 'expense', 'amount' => 50000, 'description' => 'Ojol ke mall', 'date' => Carbon::now()->subDays(1)],
        ];

        foreach ($transactions as $t) {
            Transaction::create(['user_id' => $user->id, ...$t]);
        }

        Budget::create(['user_id' => $user->id, 'category_id' => $categoryIds[2], 'name' => 'Budget Makan', 'amount' => 2000000, 'spent' => 225000, 'period' => 'monthly', 'month' => Carbon::now()->format('Y-m')]);
        Budget::create(['user_id' => $user->id, 'category_id' => $categoryIds[3], 'name' => 'Budget Transport', 'amount' => 1000000, 'spent' => 250000, 'period' => 'monthly', 'month' => Carbon::now()->format('Y-m')]);
        Budget::create(['user_id' => $user->id, 'category_id' => $categoryIds[4], 'name' => 'Budget Belanja', 'amount' => 1500000, 'spent' => 300000, 'period' => 'monthly', 'month' => Carbon::now()->format('Y-m')]);

        Goal::create(['user_id' => $user->id, 'name' => 'Beli Motor', 'target_amount' => 30000000, 'current_amount' => 10000000, 'target_date' => Carbon::now()->addMonths(6), 'status' => 'active', 'description' => 'Target beli motor baru']);
        Goal::create(['user_id' => $user->id, 'name' => 'Dana Darurat', 'target_amount' => 50000000, 'current_amount' => 15000000, 'target_date' => Carbon::now()->addMonths(12), 'status' => 'active', 'description' => 'Dana darurat 6 bulan pengeluaran']);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Test user: test@example.com / password123');
    }
}
