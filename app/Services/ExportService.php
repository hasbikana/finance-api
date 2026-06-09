<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportService
{
    public function csv(array $data, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if (!empty($data)) {
                fputcsv($handle, array_keys($data[0]));
                foreach ($data as $row) {
                    fputcsv($handle, $row);
                }
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function pdf(string $view, array $data, string $filename): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function reportPdf(int $userId, $transactions, array $summary, string $title): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('reports.pdf', [
            'transactions' => $transactions,
            'summary' => $summary,
            'title' => $title,
            'generated_at' => Carbon::now()->format('d F Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('report_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }
}
