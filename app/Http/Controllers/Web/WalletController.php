<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\WalletRequest;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(
        protected WalletService $service
    ) {}

    public function index()
    {
        $wallets = $this->service->getAll(Auth::id());
        $totalBalance = $this->service->getTotalBalance(Auth::id());

        return view('wallets.index', compact('wallets', 'totalBalance'));
    }

    public function store(WalletRequest $request)
    {
        $this->service->create(Auth::id(), $request->validated());

        return back()->with('success', 'Wallet berhasil dibuat.');
    }

    public function update(WalletRequest $request, int $id)
    {
        $wallet = $this->service->getById($id, Auth::id());

        if (!$wallet) {
            return back()->with('error', 'Wallet tidak ditemukan.');
        }

        $this->service->update($wallet, $request->validated());

        return back()->with('success', 'Wallet berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $wallet = $this->service->getById($id, Auth::id());

        if (!$wallet) {
            return back()->with('error', 'Wallet tidak ditemukan.');
        }

        $this->service->delete($wallet);

        return back()->with('success', 'Wallet berhasil dihapus.');
    }
}
