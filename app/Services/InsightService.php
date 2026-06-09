<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;

class InsightService
{
    public function getMonthlyComparison(int $userId, ?string $month = null): array
    {
        $currentMonth = $month ?? Carbon::now()->format('Y-m');
        $lastMonth = Carbon::parse($currentMonth . '-01')->subMonth()->format('Y-m');

        $current = $this->getMonthStats($userId, $currentMonth);
        $last = $this->getMonthStats($userId, $lastMonth);

        $expenseChange = $last['expense'] > 0
            ? round((($current['expense'] - $last['expense']) / $last['expense']) * 100, 1)
            : 0;

        $incomeChange = $last['income'] > 0
            ? round((($current['income'] - $last['income']) / $last['income']) * 100, 1)
            : 0;

        return [
            'current_month' => ['month' => $currentMonth, ...$current],
            'last_month' => ['month' => $lastMonth, ...$last],
            'expense_change_percent' => $expenseChange,
            'income_change_percent' => $incomeChange,
            'expense_trend' => $expenseChange > 0 ? 'up' : 'down',
            'income_trend' => $incomeChange > 0 ? 'up' : 'down',
        ];
    }

    public function getAutoInsights(int $userId): array
    {
        $insights = [];

        $currentMonth = Carbon::now()->format('Y-m');
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');

        $currentStats = $this->getMonthStats($userId, $currentMonth);
        $lastStats = $this->getMonthStats($userId, $lastMonth);

        if ($currentStats['expense'] > $currentStats['income']) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'bi-exclamation-triangle',
                'message' => 'Pengeluaran Anda bulan ini melebihi pemasukan.',
                'detail' => 'Pemasukan: Rp ' . number_format($currentStats['income'], 0, ',', '.') . ' | Pengeluaran: Rp ' . number_format($currentStats['expense'], 0, ',', '.'),
            ];
        }

        if ($currentStats['savings_rate'] < 20 && $currentStats['income'] > 0) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'bi-piggy-bank',
                'message' => 'Tingkat tabungan Anda di bawah 20%.',
                'detail' => 'Coba alokasikan minimal 20% dari pemasukan untuk ditabung.',
            ];
        }

        if ($currentStats['expense'] > $lastStats['expense'] && $lastStats['expense'] > 0) {
            $percent = round((($currentStats['expense'] - $lastStats['expense']) / $lastStats['expense']) * 100, 1);
            $insights[] = [
                'type' => 'warning',
                'icon' => 'bi-graph-up-arrow',
                'message' => "Pengeluaran naik {$percent}% dibanding bulan lalu.",
                'detail' => 'Bulan ini: Rp ' . number_format($currentStats['expense'], 0, ',', '.') . ' | Bulan lalu: Rp ' . number_format($lastStats['expense'], 0, ',', '.'),
            ];
        }

        $topCategory = $this->getTopExpenseCategory($userId, $currentMonth);
        if ($topCategory) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'bi-tag',
                'message' => "Kategori pengeluaran terbesar: {$topCategory['category_name']}",
                'detail' => 'Total: Rp ' . number_format($topCategory['total'], 0, ',', '.'),
            ];
        }

        if (empty($insights)) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'bi-check-circle',
                'message' => 'Keuangan Anda dalam kondisi baik!',
                'detail' => 'Tetap pertahankan kebiasaan finansial yang sehat.',
            ];
        }

        return $insights;
    }

    protected function getMonthStats(int $userId, string $month): array
    {
        $startDate = Carbon::parse($month . '-01')->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month . '-01')->endOfMonth()->toDateString();

        $income = (int) Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $expense = (int) Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $savingsRate = $income > 0 ? round((($income - $expense) / $income) * 100, 1) : 0;

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'savings_rate' => $savingsRate,
        ];
    }

    protected function getTopExpenseCategory(int $userId, string $month): ?array
    {
        $startDate = Carbon::parse($month . '-01')->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month . '-01')->endOfMonth()->toDateString();

        $result = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->first();

        if (!$result) {
            return null;
        }

        return [
            'category_id' => $result->category_id,
            'category_name' => $result->category?->name ?? 'Unknown',
            'total' => (int) $result->total,
        ];
    }
}
