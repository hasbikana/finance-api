<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['type', 'category_id', 'wallet_id', 'start_date', 'end_date', 'search']);
        $perPage = $request->get('per_page', 50);

        $transactions = $this->service->getFiltered($request->user()->id, $filters, $perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Data transaksi berhasil diambil.',
            'data' => TransactionResource::collection($transactions),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
            ],
        ], 200);
    }

    public function store(TransactionRequest $request): JsonResponse
    {
        $transaction = $this->service->create($request->user()->id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil dibuat.',
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $transaction = $this->service->getById($id, $request->user()->id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data transaksi berhasil diambil.',
            'data' => new TransactionResource($transaction),
        ], 200);
    }

    public function update(TransactionRequest $request, int $id): JsonResponse
    {
        $transaction = $this->service->getById($id, $request->user()->id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $transaction = $this->service->update($transaction, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil diperbarui.',
            'data' => new TransactionResource($transaction),
        ], 200);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $transaction = $this->service->getById($id, $request->user()->id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $this->service->delete($transaction);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil dihapus.',
            'data' => null,
        ], 200);
    }

    public function quickStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'wallet_id' => ['nullable', 'integer', 'exists:wallets,id'],
            'date' => ['nullable', 'date', 'date_format:Y-m-d'],
        ]);

        $transaction = $this->service->quickCreate($request->user()->id, $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil dibuat.',
            'data' => new TransactionResource($transaction),
        ], 201);
    }
}
