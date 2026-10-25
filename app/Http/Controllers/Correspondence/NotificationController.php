<?php

namespace App\Http\Controllers\Correspondence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnread(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'count' => 0,
                'notifications' => [],
            ]);
        }

        $notifications = $user->notifications()->latest()->take(15)->get();
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'count' => $unreadCount,
            'notifications' => $notifications->map(function ($n) {
                $type = $n->data['type'] ?? (str_contains($n->type, 'Task') ? 'task' : (str_contains($n->type, 'Memo') ? 'memo' : 'general'));
                return [
                    'id' => $n->id,
                    'title' => $n->data['title'] ?? 'إشعار جديد',
                    'subject' => $n->data['subject'] ?? ($n->data['task_title'] ?? ''),
                    'from' => $n->data['from_user_name'] ?? '',
                    'url' => route('notifications.read', $n->id),
                    'time' => $n->created_at->diffForHumans(),
                    'type' => $type,
                    'is_unread' => is_null($n->read_at),
                ];
            }),
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $actionUrl = $notification->data['action_url'] ?? route('correspondence.memos.index');

        return redirect($actionUrl);
    }

    public function markAllAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تم تعيين جميع الإشعارات كمقروءة.');
    }
}
