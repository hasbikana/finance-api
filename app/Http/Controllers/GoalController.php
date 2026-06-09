<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoalRequest;
use App\Http\Resources\GoalResource;
use App\Services\GoalService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function __construct(
        protected GoalService $service,
        protected NotificationService $notificationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $status = $request->get('status');
        $goals = $this->service->getAll($request->user()->id, $status);

        return response()->json([
            'status' => 'success',
            'message' => 'Data target tabungan berhasil diambil.',
            'data' => GoalResource::collection($goals),
        ], 200);
    }

    public function store(GoalRequest $request): JsonResponse
    {
        $goal = $this->service->create($request->user()->id, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Target tabungan berhasil dibuat.',
            'data' => new GoalResource($goal),
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $goal = $this->service->getById($id, $request->user()->id);

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

    public function update(GoalRequest $request, int $id): JsonResponse
    {
        $goal = $this->service->getById($id, $request->user()->id);

        if (!$goal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Target tabungan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $goal = $this->service->update($goal, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Target tabungan berhasil diperbarui.',
            'data' => new GoalResource($goal),
        ], 200);
    }

    public function addSavings(Request $request, int $id): JsonResponse
    {
        $request->validate(['amount' => ['required', 'integer', 'min:1']]);

        $goal = $this->service->getById($id, $request->user()->id);

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

        $goal = $this->service->addSavings($goal, $request->amount);

        if ($goal->status === 'completed') {
            $this->notificationService->createGoalReached($request->user()->id, $goal);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tabungan berhasil ditambahkan.',
            'data' => new GoalResource($goal),
        ], 200);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $goal = $this->service->getById($id, $request->user()->id);

        if (!$goal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Target tabungan tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $this->service->delete($goal);

        return response()->json([
            'status' => 'success',
            'message' => 'Target tabungan berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
