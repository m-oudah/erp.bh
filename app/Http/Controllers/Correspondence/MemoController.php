<?php

namespace App\Http\Controllers\Correspondence;

use App\Http\Controllers\Controller;
use App\Models\ArchiveFile;
use App\Models\Correspondence\Department;
use App\Models\Correspondence\InternalMemo;
use App\Models\Correspondence\InternalTask;
use App\Models\Correspondence\MemoAttachment;
use App\Models\User;
use App\Services\CorrespondenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemoController extends Controller
{
    public function __construct(
        protected CorrespondenceService $correspondenceService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'inbox');

        $query = InternalMemo::with(['creator', 'assignee', 'targetDepartment', 'attachments'])
            ->latest();

        // Tabs logic
        if ($tab === 'inbox') {
            $query->where('current_assignee_id', $user->id)
                  ->where('status', '!=', 'تم الإنجاز');
        } elseif ($tab === 'outbox') {
            $query->where('created_by', $user->id);
        } elseif ($tab === 'completed') {
            $query->where(function ($q) use ($user) {
                $q->where('current_assignee_id', $user->id)
                  ->orWhere('created_by', $user->id);
            })->where('status', 'تم الإنجاز');
        } elseif ($tab === 'all') {
            // All accessible memos
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('memo_number', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status') && $tab !== 'completed') {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('target_department_id', $request->department_id);
        }

        $memos = $query->paginate(15)->withQueryString();

        // Statistics counts
        $counts = [
            'inbox' => InternalMemo::where('current_assignee_id', $user->id)->where('status', '!=', 'تم الإنجاز')->count(),
            'outbox' => InternalMemo::where('created_by', $user->id)->count(),
            'completed' => InternalMemo::where(function ($q) use ($user) {
                $q->where('current_assignee_id', $user->id)->orWhere('created_by', $user->id);
            })->where('status', 'تم الإنجاز')->count(),
            'all' => InternalMemo::count(),
            'my_tasks' => InternalTask::where('assigned_to', $user->id)->where('status', '!=', 'تم الإنجاز')->count(),
        ];

        $departments = Department::where('is_active', true)->orderBy('order')->get();

        return view('correspondence.memos.index', compact('memos', 'tab', 'counts', 'departments'));
    }

    public function create(Request $request)
    {
        $departments = Department::where('is_active', true)->with('users')->orderBy('order')->get();
        $users = User::orderBy('name')->get();
        $archiveFiles = ArchiveFile::latest()->limit(50)->get();

        return view('correspondence.memos.create', compact('departments', 'users', 'archiveFiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|string|in:عادية,متوسطة,عاجلة,سري / عاجل جداً',
            'current_assignee_id' => 'required|exists:users,id',
            'target_department_id' => 'nullable|exists:departments,id',
            'linked_archive_file_id' => 'nullable|exists:archive_files,id',
            'due_date' => 'nullable|date',
            'directive_text' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar|max:15360',
        ]);

        $memo = $this->correspondenceService->createMemo(
            $request->only([
                'subject', 'content', 'priority', 'current_assignee_id',
                'target_department_id', 'linked_archive_file_id', 'due_date', 'directive_text'
            ]),
            $request->file('attachments') ?? [],
            auth()->user()
        );

        return redirect()->route('correspondence.memos.show', $memo->id)
            ->with('success', "تم إنشاء المذكرة الداخلية بنجاح برقم: {$memo->memo_number}");
    }

    public function show(InternalMemo $memo)
    {
        $memo->load([
            'creator.department',
            'assignee.department',
            'targetDepartment',
            'linkedArchiveFile',
            'autoArchiveFile',
            'closer',
            'movements.fromUser',
            'movements.toUser',
            'movements.toDepartment',
            'movements.attachments',
            'attachments.uploader',
            'attachments.archiveDocument',
            'tasks.assignee',
            'tasks.creator',
        ]);

        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('order')->get();

        return view('correspondence.memos.show', compact('memo', 'users', 'departments'));
    }

    public function forward(Request $request, InternalMemo $memo)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'directive_text' => 'required|string|max:2000',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar|max:15360',
        ]);

        $this->correspondenceService->forwardMemo(
            $memo,
            $request->to_user_id,
            $request->directive_text,
            $request->file('attachments') ?? [],
            auth()->user()
        );

        return back()->with('success', 'تم توجيه وتحويل المذكرة بنجاح مع توثيق الشروحات في الخط الزمني.');
    }

    public function close(Request $request, InternalMemo $memo)
    {
        $request->validate([
            'final_note' => 'nullable|string|max:1000',
        ]);

        $this->correspondenceService->closeMemo($memo, $request->final_note, auth()->user());

        return back()->with('success', 'تم إنجاز المذكرة وإغلاقها بنجاح.');
    }

    public function addAttachment(Request $request, InternalMemo $memo)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar|max:15360',
        ]);

        $this->correspondenceService->storeAttachment($memo, $request->file('attachment'), null, auth()->user());

        return back()->with('success', 'تم رفع المرفق وأرشفته تلقائياً في الأرشيف المركزي.');
    }

    public function downloadAttachment(MemoAttachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'الملف غير موجود على الخادم.');
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}
