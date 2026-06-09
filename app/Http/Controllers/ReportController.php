<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\ExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $service,
        protected ExportService $exportService
    ) {}

    public function charts(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $data = $this->service->getCharts($request->user()->id, $startDate, $endDate);

        return response()->json([
            'status' => 'success',
            'message' => 'Data chart berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function export(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $data = $this->service->generateCsv($request->user()->id, $startDate, $endDate);

        return response()->json([
            'status' => 'success',
            'message' => 'Data export berhasil dibuat.',
            'data' => $data,
        ], 200);
    }

    public function monthly(Request $request): JsonResponse
    {
        $month = $request->get('month');

        if ($request->has('year')) {
            $month = sprintf('%04d-%02d', $request->get('year'), $request->get('month', 1));
        }

        $data = $this->service->getMonthly($request->user()->id, $month);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan bulanan berhasil diambil.',
            'data' => $data,
        ], 200);
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month');
        $data = $this->service->getMonthly($request->user()->id, $month);

        return $this->exportService->reportPdf(
            $request->user()->id,
            $data['transactions'],
            $data['summary'],
            'Laporan Keuangan - ' . $data['month_name']
        );
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $data = $this->service->getMonthly($request->user()->id, 
            $request->get('month', \Carbon\Carbon::now()->format('Y-m'))
        );

        $rows = [];
        foreach ($data['transactions'] as $t) {
            $rows[] = [
                'Tanggal' => $t->date->toDateString(),
                'Tipe' => $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                'Kategori' => $t->category?->name ?? 'Unknown',
                'Jumlah' => $t->amount,
                'Deskripsi' => $t->description ?? '',
            ];
        }

        return $this->exportService->csv(
            $rows,
            'transactions_' . ($request->get('month', \Carbon\Carbon::now()->format('Y-m'))) . '.csv'
        );
    }
}
