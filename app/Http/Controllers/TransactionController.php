<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Get all transactions for authenticated user with filters
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::where('user_id', $request->user()->id)
            ->with('category');

        // Filter by type
        if ($request->has('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // Search by description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 50);
        $transactions = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

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

    /**
     * Create new transaction
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        // Validate category belongs to user
        $category = Category::where('id', $request->category_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan atau bukan milik Anda.',
                'data' => null,
            ], 422);
        }

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        $transaction->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil dibuat.',
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    /**
     * Get single transaction
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with('category')
            ->first();

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

    /**
     * Update transaction
     */
    public function update(TransactionRequest $request, int $id): JsonResponse
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        // Validate category belongs to user
        $category = Category::where('id', $request->category_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan atau bukan milik Anda.',
                'data' => null,
            ], 422);
        }

        $transaction->update([
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        $transaction->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil diperbarui.',
            'data' => new TransactionResource($transaction),
        ], 200);
    }

    /**
     * Delete transaction
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}