<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Services\ExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $service,
        protected ExportService $exportService
    ) {}

    public function monthly(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $data = $this->service->getMonthly(Auth::id(), $month);

        return view('reports.monthly', compact('data', 'month'));
    }

    public function yearly(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $monthlySummaries = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthStr = sprintf('%04d-%02d', $year, $m);
            $monthlySummaries[] = $this->service->getMonthly(Auth::id(), $monthStr);
        }

        return view('reports.yearly', compact('monthlySummaries', 'year'));
    }

    public function category(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $charts = $this->service->getCharts(Auth::id(), $startDate, $endDate);

        return view('reports.category', compact('charts', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $data = $this->service->getMonthly(Auth::id(), $month);

        return $this->exportService->reportPdf(
            Auth::id(),
            $data['transactions'],
            $data['summary'],
            'Laporan Keuangan - ' . $data['month_name']
        );
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = \App\Models\Transaction::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $rows = [];
        foreach ($transactions as $t) {
            $rows[] = [
                'Tanggal' => $t->date->toDateString(),
                'Tipe' => $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                'Kategori' => $t->category?->name ?? 'Unknown',
                'Jumlah' => $t->amount,
                'Deskripsi' => $t->description ?? '',
            ];
        }

        return $this->exportService->csv($rows, 'report_' . $startDate . '_' . $endDate . '.csv');
    }
}
