<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\BudgetRequest;
use App\Services\BudgetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function __construct(
        protected BudgetService $service
    ) {}

    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $month = $request->get('month', Carbon::now()->format('Y-m'));

        $budgets = $this->service->getWithProgress(Auth::id(), $month);
        $categories = \App\Models\Category::where('user_id', Auth::id())->orderBy('name')->get();

        return view('budgets.index', compact('budgets', 'categories', 'period', 'month'));
    }

    public function store(BudgetRequest $request)
    {
        $this->service->create(Auth::id(), $request->validated());

        return back()->with('success', 'Budget berhasil dibuat.');
    }

    public function update(BudgetRequest $request, int $id)
    {
        $budget = $this->service->getById($id, Auth::id());

        if (!$budget) {
            return back()->with('error', 'Budget tidak ditemukan.');
        }

        $this->service->update($budget, $request->validated());

        return back()->with('success', 'Budget berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $budget = $this->service->getById($id, Auth::id());

        if (!$budget) {
            return back()->with('error', 'Budget tidak ditemukan.');
        }

        $this->service->delete($budget);

        return back()->with('success', 'Budget berhasil dihapus.');
    }
}
