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

        $query = InternalMemo::with(['creator.department', 'assignee.department', 'recipients.department', 'targetDepartment', 'attachments'])
            ->latest();

        // Tabs logic
        if ($tab === 'inbox') {
            $query->where(function ($q) use ($user) {
                $q->where('current_assignee_id', $user->id)
                  ->orWhereHas('recipients', fn($r) => $r->where('users.id', $user->id));
            })->where('status', '!=', 'تم الإنجاز');
        } elseif ($tab === 'outbox') {
            $query->where('created_by', $user->id);
        } elseif ($tab === 'completed') {
            $query->where(function ($q) use ($user) {
                $q->where('current_assignee_id', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhereHas('recipients', fn($r) => $r->where('users.id', $user->id));
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

        if ($request->filled('memo_type')) {
            $query->where('memo_type', $request->memo_type);
        }

        if ($request->filled('department_id')) {
            $query->where('target_department_id', $request->department_id);
        }

        $memos = $query->paginate(15)->withQueryString();

        // Statistics counts
        $counts = [
            'inbox' => InternalMemo::where(function ($q) use ($user) {
                $q->where('current_assignee_id', $user->id)
                  ->orWhereHas('recipients', fn($r) => $r->where('users.id', $user->id));
            })->where('status', '!=', 'تم الإنجاز')->count(),
            'outbox' => InternalMemo::where('created_by', $user->id)->count(),
            'completed' => InternalMemo::where(function ($q) use ($user) {
                $q->where('current_assignee_id', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhereHas('recipients', fn($r) => $r->where('users.id', $user->id));
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
        $users = User::with('department')->orderBy('name')->get();
        $archiveFiles = ArchiveFile::latest()->limit(50)->get();

        $usersList = $users->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'department' => $u->department->name ?? 'بلدية بيت حانون',
            'job_title' => $u->job_title ?? 'موظف',
        ])->values();

        $oldAssigneeIds = old('assignee_ids', old('current_assignee_id') ? [(int)old('current_assignee_id')] : []);

        return view('correspondence.memos.create', compact('departments', 'users', 'archiveFiles', 'usersList', 'oldAssigneeIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'memo_type' => 'nullable|string|max:100',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'nullable|string',
            'assignee_ids' => 'nullable|array',
            'assignee_ids.*' => 'exists:users,id',
            'current_assignee_id' => 'nullable|exists:users,id',
            'linked_archive_file_id' => 'nullable|exists:archive_files,id',
            'due_date' => 'nullable|date',
            'directive_text' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar|max:15360',
        ]);

        $recipientIds = [];
        if ($request->has('assignee_ids') && is_array($request->assignee_ids)) {
            $recipientIds = array_values(array_unique(array_filter(array_map('intval', $request->assignee_ids))));
        }
        if (empty($recipientIds) && $request->filled('current_assignee_id')) {
            $recipientIds = [(int)$request->current_assignee_id];
        }

        if (empty($recipientIds)) {
            return back()->withInput()->withErrors(['assignee_ids' => 'يرجى اختيار موظف مستلم واحد على الأقل للمراسلة.']);
        }

        $memoType = $request->input('memo_type') ?: 'مذكرة داخلية';
        $firstUser = User::find($recipientIds[0]);

        $data = $request->only([
            'subject', 'content', 'linked_archive_file_id', 'due_date', 'directive_text'
        ]);
        $data['priority'] = $request->input('priority', 'عادية');
        $data['memo_type'] = $memoType;
        $data['recipient_ids'] = $recipientIds;
        $data['current_assignee_id'] = $recipientIds[0];
        $data['target_department_id'] = $firstUser?->department_id;

        // Save as ONE single memo with ONE serial number
        $memo = $this->correspondenceService->createMemo(
            $data,
            $request->file('attachments') ?? [],
            auth()->user()
        );

        $recipientsCount = count($recipientIds);
        $msg = $recipientsCount > 1
            ? "تم إرسال المراسلة الداخلية بنجاح برقم ({$memo->memo_number}) إلى ({$recipientsCount}) مستلمين."
            : "تم إرسال المراسلة الداخلية بنجاح برقم: {$memo->memo_number}";

        return redirect()->route('correspondence.memos.show', $memo->id)
            ->with('success', $msg);
    }

    public function show(InternalMemo $memo)
    {
        $memo->load([
            'creator.department',
            'assignee.department',
            'recipients.department',
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

    public function forwardPage(InternalMemo $memo)
    {
        $memo->load([
            'creator.department',
            'assignee.department',
            'recipients.department',
            'targetDepartment',
            'attachments.uploader',
            'attachments.archiveDocument',
            'movements.fromUser',
            'movements.toUser',
        ]);

        $departments = Department::where('is_active', true)->with('users')->orderBy('order')->get();
        $users = User::with('department')->where('id', '!=', auth()->id())->orderBy('name')->get();

        $usersList = $users->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'department' => $u->department->name ?? 'بلدية بيت حانون',
            'job_title' => $u->job_title ?? 'موظف',
        ])->values();

        $oldAssigneeIds = old('assignee_ids', old('to_user_id') ? [(int)old('to_user_id')] : []);

        return view('correspondence.memos.forward', compact('memo', 'departments', 'users', 'usersList', 'oldAssigneeIds'));
    }

    public function forward(Request $request, InternalMemo $memo)
    {
        $request->validate([
            'assignee_ids' => 'nullable|array',
            'assignee_ids.*' => 'exists:users,id',
            'to_user_id' => 'nullable|exists:users,id',
            'directive_text' => 'required|string|max:2000',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar|max:15360',
        ]);

        $recipientIds = [];
        if ($request->has('assignee_ids') && is_array($request->assignee_ids)) {
            $recipientIds = array_values(array_unique(array_filter(array_map('intval', $request->assignee_ids))));
        }
        if (empty($recipientIds) && $request->filled('to_user_id')) {
            $recipientIds = [(int)$request->to_user_id];
        }

        if (empty($recipientIds)) {
            return back()->withInput()->withErrors(['assignee_ids' => 'يرجى اختيار موظف محول إليه واحد على الأقل.']);
        }

        $this->correspondenceService->forwardMemo(
            $memo,
            $recipientIds,
            $request->directive_text,
            $request->file('attachments') ?? [],
            auth()->user()
        );

        return redirect()->route('correspondence.memos.show', $memo->id)
            ->with('success', 'تم إعادة توجيه وتحويل المراسلة بنجاح مع توثيق التوجيه في الخط الزمني.');
    }

    public function close(Request $request, InternalMemo $memo)
    {
        $request->validate([
            'final_note' => 'nullable|string|max:1000',
        ]);

        $this->correspondenceService->closeMemo($memo, $request->final_note, auth()->user());

        return back()->with('success', 'تم إنجاز المراسلة وإغلاقها بنجاح.');
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
