<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\GoalRequest;
use App\Services\GoalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function __construct(
        protected GoalService $service
    ) {}

    public function index(Request $request)
    {
        $status = $request->get('status');
        $goals = $this->service->getAll(Auth::id(), $status);

        return view('goals.index', compact('goals', 'status'));
    }

    public function store(GoalRequest $request)
    {
        $this->service->create(Auth::id(), $request->validated());

        return back()->with('success', 'Target tabungan berhasil dibuat.');
    }

    public function update(GoalRequest $request, int $id)
    {
        $goal = $this->service->getById($id, Auth::id());

        if (!$goal) {
            return back()->with('error', 'Target tabungan tidak ditemukan.');
        }

        $this->service->update($goal, $request->validated());

        return back()->with('success', 'Target tabungan berhasil diperbarui.');
    }

    public function addSavings(Request $request, int $id)
    {
        $request->validate(['amount' => ['required', 'integer', 'min:1']]);

        $goal = $this->service->getById($id, Auth::id());

        if (!$goal) {
            return back()->with('error', 'Target tabungan tidak ditemukan.');
        }

        if ($goal->status === 'completed') {
            return back()->with('error', 'Target tabungan sudah selesai.');
        }

        $this->service->addSavings($goal, $request->amount);

        return back()->with('success', 'Tabungan berhasil ditambahkan.');
    }

    public function destroy(int $id)
    {
        $goal = $this->service->getById($id, Auth::id());

        if (!$goal) {
            return back()->with('error', 'Target tabungan tidak ditemukan.');
        }

        $this->service->delete($goal);

        return back()->with('success', 'Target tabungan berhasil dihapus.');
    }
}
