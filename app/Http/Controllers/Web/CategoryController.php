<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $service
    ) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $categories = $search
            ? $this->service->search(Auth::id(), $search)
            : $this->service->getAll(Auth::id());

        return view('categories.index', compact('categories', 'search'));
    }

    public function store(CategoryRequest $request)
    {
        $this->service->create(Auth::id(), $request->validated());

        return back()->with('success', 'Kategori berhasil dibuat.');
    }

    public function update(CategoryRequest $request, int $id)
    {
        $category = $this->service->getById($id, Auth::id());

        if (!$category) {
            return back()->with('error', 'Kategori tidak ditemukan.');
        }

        $this->service->update($category, $request->validated());

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $category = $this->service->getById($id, Auth::id());

        if (!$category) {
            return back()->with('error', 'Kategori tidak ditemukan.');
        }

        $this->service->delete($category);

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
