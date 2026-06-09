<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        if ($request->has('search')) {
            $categories = $this->service->search($request->user()->id, $request->search);
        } else {
            $categories = $this->service->getAll($request->user()->id);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data kategori berhasil diambil.',
            'data' => CategoryResource::collection($categories),
        ], 200);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $category = $this->service->create($request->user()->id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dibuat.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $category = $this->service->getById($id, $request->user()->id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data kategori berhasil diambil.',
            'data' => new CategoryResource($category),
        ], 200);
    }

    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->service->getById($id, $request->user()->id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $category = $this->service->update($category, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui.',
            'data' => new CategoryResource($category),
        ], 200);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $category = $this->service->getById($id, $request->user()->id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $this->service->delete($category);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
