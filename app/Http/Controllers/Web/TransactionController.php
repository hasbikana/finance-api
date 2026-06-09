<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TransactionRequest;
use App\Services\TransactionService;
use App\Services\WalletService;
use App\Services\ExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $service,
        protected WalletService $walletService,
        protected ExportService $exportService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['type', 'category_id', 'wallet_id', 'start_date', 'end_date', 'search']);
        $perPage = $request->get('per_page', 20);

        $transactions = $this->service->getFiltered(Auth::id(), $filters, $perPage);
        $categories = \App\Models\Category::where('user_id', Auth::id())->orderBy('name')->get();
        $wallets = $this->walletService->getAll(Auth::id());

        return view('transactions.index', compact('transactions', 'categories', 'wallets', 'filters'));
    }

    public function store(TransactionRequest $request)
    {
        $this->service->create(Auth::id(), $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dibuat.',
            ], 201);
        }

        return back()->with('success', 'Transaksi berhasil dibuat.');
    }

    public function update(TransactionRequest $request, int $id)
    {
        $transaction = $this->service->getById($id, Auth::id());

        if (!$transaction) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $this->service->update($transaction, $request->validated());

        return back()->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $transaction = $this->service->getById($id, Auth::id());

        if (!$transaction) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $this->service->delete($transaction);

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dihapus.',
            ]);
        }

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = \App\Models\Transaction::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->orderBy('date', 'desc')
            ->get();

        $rows = [];
        foreach ($transactions as $t) {
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
            'transactions_' . $startDate . '_to_' . $endDate . '.csv'
        );
    }
}
