<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BudgetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function __construct(
        protected BudgetService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $period = $request->get('period');
        $budgets = $this->service->getAll($request->user()->id, $period);

        $data = $budgets->map(fn($b) => [
            'id' => $b->id,
            'category_id' => $b->category_id,
            'category_name' => $b->category?->name,
            'name' => $b->name,
            'amount' => $b->amount,
            'spent' => $b->spent,
            'remaining' => $b->remaining_amount,
            'progress_percentage' => $b->progress_percentage,
            'is_over_budget' => $b->is_over_budget,
            'period' => $b->period,
            'month' => $b->month,
            'is_active' => $b->is_active,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data budget berhasil diambil.',
            'data' => $data,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'period' => ['required', 'in:monthly,yearly'],
            'month' => ['nullable', 'string'],
        ]);

        $budget = $this->service->create($request->user()->id, $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Budget berhasil dibuat.',
            'data' => $budget->load('category'),
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $budget = $this->service->getById($id, $request->user()->id);

        if (!$budget) {
            return response()->json([
                'status' => 'error',
                'message' => 'Budget tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $budget,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $budget = $this->service->getById($id, $request->user()->id);

        if (!$budget) {
            return response()->json([
                'status' => 'error',
                'message' => 'Budget tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'amount' => ['sometimes', 'integer', 'min:1'],
            'period' => ['sometimes', 'in:monthly,yearly'],
            'month' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $budget = $this->service->update($budget, $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Budget berhasil diperbarui.',
            'data' => $budget,
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $budget = $this->service->getById($id, $request->user()->id);

        if (!$budget) {
            return response()->json([
                'status' => 'error',
                'message' => 'Budget tidak ditemukan.',
            ], 404);
        }

        $this->service->delete($budget);

        return response()->json([
            'status' => 'success',
            'message' => 'Budget berhasil dihapus.',
        ]);
    }
}
