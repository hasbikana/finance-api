<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InsightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function __construct(
        protected InsightService $service
    ) {}

    public function monthlyComparison(Request $request): JsonResponse
    {
        $month = $request->get('month');

        $data = $this->service->getMonthlyComparison($request->user()->id, $month);

        return response()->json([
            'status' => 'success',
            'message' => 'Data perbandingan bulanan berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function autoInsights(Request $request): JsonResponse
    {
        $insights = $this->service->getAutoInsights($request->user()->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data insight berhasil diambil.',
            'data' => $insights,
        ]);
    }
}
