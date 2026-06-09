<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        protected WalletService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $wallets = $this->service->getAll($request->user()->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data wallet berhasil diambil.',
            'data' => $wallets->map(fn($w) => [
                'id' => $w->id,
                'name' => $w->name,
                'type' => $w->type,
                'balance' => $w->balance,
                'icon' => $w->icon,
                'color' => $w->color,
                'is_active' => $w->is_active,
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:cash,bank,ewallet'],
            'balance' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        $wallet = $this->service->create($request->user()->id, $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet berhasil dibuat.',
            'data' => $wallet,
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $wallet = $this->service->getById($id, $request->user()->id);

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $wallet,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $wallet = $this->service->getById($id, $request->user()->id);

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'type' => ['sometimes', 'in:cash,bank,ewallet'],
            'balance' => ['sometimes', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:7'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $wallet = $this->service->update($wallet, $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet berhasil diperbarui.',
            'data' => $wallet,
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $wallet = $this->service->getById($id, $request->user()->id);

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet tidak ditemukan.',
            ], 404);
        }

        $this->service->delete($wallet);

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet berhasil dihapus.',
        ]);
    }
}
