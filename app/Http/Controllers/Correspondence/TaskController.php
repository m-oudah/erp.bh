<?php

namespace App\Http\Controllers\Correspondence;

use App\Http\Controllers\Controller;
use App\Models\Correspondence\InternalMemo;
use App\Models\Correspondence\InternalTask;
use App\Models\User;
use App\Services\CorrespondenceService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected CorrespondenceService $correspondenceService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'assigned_to_me');

        $query = InternalTask::with(['creator', 'assignee', 'memo'])
            ->latest();

        if ($tab === 'assigned_to_me') {
            $query->where('assigned_to', $user->id)
                  ->where('status', '!=', 'تم الإنجاز');
        } elseif ($tab === 'assigned_by_me') {
            $query->where('created_by', $user->id);
        } elseif ($tab === 'completed') {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            })->where('status', 'تم الإنجاز');
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status') && $tab !== 'completed') {
            $query->where('status', $request->status);
        }

        $tasks = $query->paginate(15)->withQueryString();

        $counts = [
            'assigned_to_me' => InternalTask::where('assigned_to', $user->id)->where('status', '!=', 'تم الإنجاز')->count(),
            'assigned_by_me' => InternalTask::where('created_by', $user->id)->count(),
            'completed' => InternalTask::where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)->orWhere('created_by', $user->id);
            })->where('status', 'تم الإنجاز')->count(),
        ];

        $users = User::orderBy('name')->get();
        $memos = InternalMemo::latest()->limit(30)->get();

        return view('correspondence.tasks.index', compact('tasks', 'tab', 'counts', 'users', 'memos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'memo_id' => 'nullable|exists:internal_memos,id',
            'priority' => 'required|in:عادية,متوسطة,عاجلة',
            'due_date' => 'nullable|date',
        ]);

        $task = $this->correspondenceService->createTask($request->all(), auth()->user());

        return back()->with('success', "تم إنشاء وتكليف المهمة بنجاح للموظف: {$task->assignee->name}");
    }

    public function show(InternalTask $task)
    {
        $task->load([
            'creator.department',
            'assignee.department',
            'memo',
            'comments.user',
        ]);

        return view('correspondence.tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, InternalTask $task)
    {
        $request->validate([
            'status' => 'required|in:جديدة,قيد التنفيذ,بانتظار الرد,تم الإنجاز,معلقة',
        ]);

        $updateData = ['status' => $request->status];
        if ($request->status === 'تم الإنجاز') {
            $updateData['completed_at'] = now();
        }

        $task->update($updateData);

        return back()->with('success', 'تم تحديث حالة المهمة بنجاح.');
    }

    public function addComment(Request $request, InternalTask $task)
    {
        $request->validate([
            'comment' => 'required|string',
            'status_change_to' => 'nullable|in:جديدة,قيد التنفيذ,بانتظار الرد,تم الإنجاز,معلقة',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar|max:15360',
        ]);

        $this->correspondenceService->addTaskComment(
            $task,
            $request->comment,
            $request->status_change_to,
            $request->file('attachment'),
            auth()->user()
        );

        return back()->with('success', 'تمت إضافة إفادتك / تعليقك على المهمة بنجاح.');
    }
}
