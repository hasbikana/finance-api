<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\InsightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsightController extends Controller
{
    public function __construct(
        protected InsightService $service
    ) {}

    public function index(Request $request)
    {
        $comparison = $this->service->getMonthlyComparison(Auth::id(), $request->get('month'));
        $insights = $this->service->getAutoInsights(Auth::id());

        return view('insights.index', compact('comparison', 'insights'));
    }
}
