<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $service,
        protected TransactionService $transactionService
    ) {}

    public function summary(Request $request): JsonResponse
    {
        $summary = $this->service->getSummary($request->user()->id);
        $recentTransactions = $this->service->getRecentTransactions($request->user()->id, 5);
        $monthlyTrend = $this->service->getMonthlyTrend($request->user()->id, 6);

        return response()->json([
            'status' => 'success',
            'message' => 'Data dashboard berhasil diambil.',
            'data' => array_merge($summary, [
                'recent_transactions' => \App\Http\Resources\TransactionResource::collection($recentTransactions),
                'monthly_trend' => $monthlyTrend,
                'transaction_count' => \App\Models\Transaction::where('user_id', $request->user()->id)->count(),
            ]),
        ], 200);
    }
}
