<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RecurringTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecurringTransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $transactions = RecurringTransaction::where('user_id', $request->user()->id)
            ->with(['category', 'wallet'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $transactions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'wallet_id' => ['nullable', 'exists:wallets,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
            'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ]);

        $transaction = RecurringTransaction::create([
            'user_id' => $request->user()->id,
            ...$validated,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berulang berhasil dibuat.',
            'data' => $transaction->load(['category', 'wallet']),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $transaction = RecurringTransaction::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Not found.'], 404);
        }

        $validated = $request->validate([
            'category_id' => ['sometimes', 'exists:categories,id'],
            'wallet_id' => ['nullable', 'exists:wallets,id'],
            'type' => ['sometimes', 'in:income,expense'],
            'amount' => ['sometimes', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:1000'],
            'frequency' => ['sometimes', 'in:daily,weekly,monthly,yearly'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $transaction->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berulang berhasil diperbarui.',
            'data' => $transaction->fresh()->load(['category', 'wallet']),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $transaction = RecurringTransaction::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Not found.'], 404);
        }

        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berulang berhasil dihapus.',
        ]);
    }
}
