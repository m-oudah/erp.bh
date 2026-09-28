<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ArchiveActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')
            ->whereIn('log_name', ['ArchiveFile', 'ArchiveDocument', 'ArchiveFileType', 'ArchiveDocumentCategory', 'default'])
            ->latest();

        // Filter by causer
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by event type
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->paginate(30)->withQueryString();

        $users = \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('settings.archive.activity_log', compact('activities', 'users'));
    }
}
