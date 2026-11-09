<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    // Trang danh sách tất cả thông báo
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    // Dữ liệu cho chuông thông báo
    public function feed(Request $request): JsonResponse
    {
        $user = $request->user();

        $items = $user->notifications()
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($notification) use ($user) {
                $data = $notification->data;

                return [
                    'id' => $notification->id,
                    'event' => $data['event'] ?? '',
                    'message' => $data['message'] ?? 'Thông báo mới.',
                    'time' => $notification->created_at
                        ->timezone('Asia/Ho_Chi_Minh')
                        ->format('H:i d/m/Y'),
                    'read' => $notification->read_at !== null,
                    'url' => $this->destination($notification, $user),
                ];
            });

        return response()->json([
            'items' => $items,
            'unread' => $user->unreadNotifications()->count(),
        ]);
    }

    // Đánh dấu một thông báo đã đọc
    public function read(
        Request $request,
        string $notification
    ): JsonResponse|RedirectResponse {
        $item = $request->user()
            ->notifications()
            ->whereKey($notification)
            ->firstOrFail();

        if ($item->read_at === null) {
            $item->markAsRead();
        }

        $url = $this->destination($item, $request->user());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        }

        return redirect()->to($url);
    }

    // Đánh dấu tất cả thông báo đã đọc
    public function readAll(
        Request $request
    ): JsonResponse|RedirectResponse {
        $request->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    // Chỉ mở chi tiết lịch khi NCS còn quyền truy cập
    private function destination(
        DatabaseNotification $notification,
        User $user
    ): string {
        $data = $notification->data;

        $patientId = (int) ($data['patient_id'] ?? 0);
        $reminderId = (int) ($data['reminder_id'] ?? 0);

        if ($patientId <= 0 || $reminderId <= 0) {
            return route('notifications.index');
        }

        $hasAccess = $user->ownedPatients()
            ->whereKey($patientId)
            ->exists()
            || $user->sharedPatients()
                ->whereKey($patientId)
                ->exists();

        if (!$hasAccess) {
            return route('notifications.index');
        }

        $reminderExists = Reminder::query()
            ->whereKey($reminderId)
            ->where('patient_id', $patientId)
            ->exists();

        if (!$reminderExists) {
            return route('notifications.index');
        }

        return route('reminders.show', [
            'patient' => $patientId,
            'reminder' => $reminderId,
        ]);
    }
}
