<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoalRequest;
use App\Http\Resources\GoalResource;
use App\Models\Goal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * Get all goals for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $query = Goal::where('user_id', $request->user()->id);

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['active', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $goals = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data target tabungan berhasil diambil.',
            'data' => GoalResource::collection($goals),
        ], 200);
    }

    /**
     * Create new goal
     */
    public function store(GoalRequest $request): JsonResponse
    {
        $goal = Goal::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'target_date' => $request->target_date,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Target tabungan berhasil dibuat.',
            'data' => new GoalResource($goal),
        ], 201);
    }

    /**
     * Get single goal
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $goal = Goal::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$goal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Target tabungan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data target tabungan berhasil diambil.',
            'data' => new GoalResource($goal),
        ], 200);
    }

    /**
     * Update goal
     */
    public function update(GoalRequest $request, int $id): JsonResponse
    {
        $goal = Goal::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$goal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Target tabungan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $goal->update([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'target_date' => $request->target_date,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Target tabungan berhasil diperbarui.',
            'data' => new GoalResource($goal),
        ], 200);
    }

    /**
     * Add savings to goal
     */
    public function addSavings(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        $goal = Goal::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$goal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Target tabungan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        if ($goal->status === 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Target tabungan sudah selesai.',
                'data' => null,
            ], 422);
        }

        $goal->current_amount += $request->amount;
        
        // Auto-complete if target reached
        if ($goal->current_amount >= $goal->target_amount) {
            $goal->status = 'completed';
        }

        $goal->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tabungan berhasil ditambahkan.',
            'data' => new GoalResource($goal),
        ], 200);
    }

    /**
     * Delete goal
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $goal = Goal::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$goal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Target tabungan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $goal->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Target tabungan berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}