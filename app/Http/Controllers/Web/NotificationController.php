<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $service
    ) {}

    public function index()
    {
        $notifications = $this->service->getAll(Auth::id(), 20);

        return view('notifications.index', compact('notifications'));
    }

    public function read(int $id)
    {
        $this->service->markAsRead($id, Auth::id());

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function readAll()
    {
        $this->service->markAllAsRead(Auth::id());

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
