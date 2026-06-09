<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $notifications = $this->service->getAll($request->user()->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data notifikasi berhasil diambil.',
            'data' => $notifications->through(fn($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'message' => $n->message,
                'data' => $n->data,
                'is_read' => $n->is_read,
                'read_at' => $n->read_at?->toIso8601String(),
                'created_at' => $n->created_at->diffForHumans(),
            ]),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'last_page' => $notifications->lastPage(),
            ],
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => ['count' => $this->service->getUnreadCount($request->user()->id)],
        ]);
    }

    public function read(Request $request, int $id): JsonResponse
    {
        $this->service->markAsRead($id, $request->user()->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi ditandai sudah dibaca.',
        ]);
    }

    public function readAll(Request $request): JsonResponse
    {
        $this->service->markAllAsRead($request->user()->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Semua notifikasi ditandai sudah dibaca.',
        ]);
    }
}
