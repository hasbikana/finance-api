<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kategori berhasil diambil.',
            'data' => CategoryResource::collection($categories),
        ], 200);
    }

    /**
     * Create new category
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create([
            'name' => $request->name,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dibuat.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Get single category
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

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

    /**
     * Update category
     */
    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui.',
            'data' => new CategoryResource($category),
        ], 200);
    }

    /**
     * Delete category
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}