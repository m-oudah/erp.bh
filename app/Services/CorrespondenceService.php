<?php

namespace App\Services;

use App\Models\ArchiveFile;
use App\Models\ArchiveFileType;
use App\Models\Correspondence\InternalMemo;
use App\Models\Correspondence\InternalTask;
use App\Models\Correspondence\MemoAttachment;
use App\Models\Correspondence\MemoMovement;
use App\Models\Correspondence\TaskComment;
use App\Models\User;
use App\Notifications\MemoAssignedNotification;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskFeedbackNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CorrespondenceService
{
    public function __construct(
        protected ArchiveService $archiveService
    ) {}

    /**
     * Create a new Internal Memo
     */
    public function createMemo(array $data, array $attachments = [], ?User $creator = null): InternalMemo
    {
        $creator = $creator ?? auth()->user();

        return DB::transaction(function () use ($data, $attachments, $creator) {
            $memoNumber = InternalMemo::generateMemoNumber();

            $memo = InternalMemo::create([
                'memo_number' => $memoNumber,
                'subject' => $data['subject'],
                'content' => $data['content'],
                'priority' => $data['priority'] ?? 'عادية',
                'status' => 'قيد المعالجة',
                'created_by' => $creator->id,
                'current_assignee_id' => $data['current_assignee_id'] ?? null,
                'target_department_id' => $data['target_department_id'] ?? null,
                'linked_archive_file_id' => $data['linked_archive_file_id'] ?? null,
                'due_date' => $data['due_date'] ?? null,
            ]);

            // Initial Movement
            $movement = MemoMovement::create([
                'memo_id' => $memo->id,
                'from_user_id' => $creator->id,
                'to_user_id' => $memo->current_assignee_id,
                'to_department_id' => $memo->target_department_id,
                'action_type' => 'إنشاء المذكرة',
                'directive_text' => $data['directive_text'] ?? 'تم إنشاء وتوجيه المذكرة للمتابعة.',
            ]);

            // Upload Attachments & Auto-Archive
            if (!empty($attachments)) {
                foreach ($attachments as $file) {
                    if ($file instanceof UploadedFile) {
                        $this->storeAttachment($memo, $file, $movement->id, $creator);
                    }
                }
            }

            // Check for @mentions in initial content / directive
            $fullText = $memo->content . ' ' . ($data['directive_text'] ?? '');
            $this->extractAndAssignTasksFromText($memo, $fullText, $creator);

            // Notify recipient if assigned
            if ($memo->assignee && $memo->assignee->id !== $creator->id) {
                $memo->assignee->notify(new MemoAssignedNotification($memo, $creator, $data['directive_text'] ?? null));
            }

            return $memo;
        });
    }

    /**
     * Forward memo with directive note (التحويل مع التوجيه الإداري)
     */
    public function forwardMemo(
        InternalMemo $memo,
        int $toUserId,
        ?string $directiveText,
        array $attachments = [],
        ?User $fromUser = null
    ): MemoMovement {
        $fromUser = $fromUser ?? auth()->user();
        $toUser = User::findOrFail($toUserId);

        return DB::transaction(function () use ($memo, $toUser, $directiveText, $attachments, $fromUser) {
            $movement = MemoMovement::create([
                'memo_id' => $memo->id,
                'from_user_id' => $fromUser->id,
                'to_user_id' => $toUser->id,
                'to_department_id' => $toUser->department_id,
                'action_type' => 'تحويل مع توجيه',
                'directive_text' => $directiveText,
            ]);

            // Update current assignee
            $memo->update([
                'current_assignee_id' => $toUser->id,
                'status' => 'قيد المعالجة',
            ]);

            // Upload any attachments associated with this movement
            if (!empty($attachments)) {
                foreach ($attachments as $file) {
                    if ($file instanceof UploadedFile) {
                        $this->storeAttachment($memo, $file, $movement->id, $fromUser);
                    }
                }
            }

            // Check for @mentions in directive
            if (!empty($directiveText)) {
                $this->extractAndAssignTasksFromText($memo, $directiveText, $fromUser);
            }

            // Notify assignee
            if ($toUser->id !== $fromUser->id) {
                $toUser->notify(new MemoAssignedNotification($memo, $fromUser, $directiveText));
            }

            return $movement;
        });
    }

    /**
     * Mark memo as completed and closed (إنجاز وإغلاق المذكرة)
     */
    public function closeMemo(InternalMemo $memo, ?string $finalNote = null, ?User $user = null): InternalMemo
    {
        $user = $user ?? auth()->user();

        return DB::transaction(function () use ($memo, $finalNote, $user) {
            $memo->update([
                'status' => 'تم الإنجاز',
                'closed_at' => now(),
                'closed_by' => $user->id,
            ]);

            MemoMovement::create([
                'memo_id' => $memo->id,
                'from_user_id' => $user->id,
                'to_user_id' => $memo->created_by,
                'action_type' => 'إنجاز وإغلاق المذكرة',
                'directive_text' => $finalNote ?? 'تم إنجاز كافة متطلبات المذكرة وإغلاقها رسمياً.',
            ]);

            // Notify creator if closed by someone else
            if ($memo->creator && $memo->creator->id !== $user->id) {
                $memo->creator->notify(new MemoAssignedNotification($memo, $user, 'تم إنجاز المذكرة وإغلاقها: ' . ($finalNote ?? '')));
            }

            return $memo;
        });
    }

    /**
     * Store and auto-archive memo attachment
     */
    public function storeAttachment(
        InternalMemo $memo,
        UploadedFile $file,
        ?int $movementId = null,
        ?User $uploader = null
    ): MemoAttachment {
        $uploader = $uploader ?? auth()->user();

        // 1. Ensure central archive file exists for this memo
        if (!$memo->auto_archive_file_id) {
            $type = ArchiveFileType::firstOrCreate(
                ['name' => 'أرشيف المراسلات والمذكرات الداخلية'],
                ['description' => 'ملفات ووثائق المراسلات والمذكرات الداخلية والمهام التابعة للنظام الموحد', 'is_active' => true]
            );

            $archiveFile = ArchiveFile::create([
                'archive_file_type_id' => $type->id,
                'file_no' => $memo->memo_number,
                'file_name' => 'ملف مذكرة: ' . $memo->subject,
                'department_id' => $memo->target_department_id ?? $memo->creator?->department_id,
                'dynamic_data' => [
                    'memo_id' => $memo->id,
                    'memo_number' => $memo->memo_number,
                    'subject' => $memo->subject,
                    'creator' => $memo->creator?->name,
                ],
            ]);

            $memo->update(['auto_archive_file_id' => $archiveFile->id]);
        }

        // 2. Store file via ArchiveService
        $archiveDoc = null;
        try {
            $archiveDoc = $this->archiveService->storeDocument(
                $memo->auto_archive_file_id,
                $file,
                $file->getClientOriginalName(),
                $memo->memo_number,
                null,
                $uploader->id
            );
        } catch (\Exception $e) {
            // Fallback storage if archiveService throws
            $path = $file->store('memo_attachments', 'public');
        }

        $filePath = $archiveDoc ? $archiveDoc->document_path : $file->store('memo_attachments', 'public');

        return MemoAttachment::create([
            'memo_id' => $memo->id,
            'movement_id' => $movementId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
            'uploaded_by' => $uploader->id,
            'archive_document_id' => $archiveDoc?->id,
        ]);
    }

    /**
     * Parse @mentions or tags from text and auto-create tasks
     */
    public function extractAndAssignTasksFromText(InternalMemo $memo, string $text, ?User $creator = null): array
    {
        $creator = $creator ?? auth()->user();
        $createdTasks = [];

        // Match patterns like @username or @[Full Name] or @{id}
        // Match @name or @id
        preg_match_all('/@([a-zA-Z0-9_\x{0600}-\x{06FF}]+)/u', $text, $matches);

        if (!empty($matches[1])) {
            $tags = array_unique($matches[1]);
            foreach ($tags as $tag) {
                // Find matching user by name or email or id
                $user = User::where('id', $tag)
                    ->orWhere('name', 'LIKE', "%{$tag}%")
                    ->orWhere('email', 'LIKE', "{$tag}%")
                    ->first();

                if ($user && $user->id !== $creator->id) {
                    $taskTitle = "مهمة مستخرجة من المذكرة {$memo->memo_number}";
                    $taskDescription = "تم تكليفك بالمهمة عبر الإشارة إليك (@{$tag}) في المذكرة: {$memo->subject}.\nنص التوجيه: {$text}";

                    $task = InternalTask::create([
                        'memo_id' => $memo->id,
                        'title' => $taskTitle,
                        'description' => $taskDescription,
                        'created_by' => $creator->id,
                        'assigned_to' => $user->id,
                        'priority' => $memo->priority,
                        'due_date' => $memo->due_date,
                        'status' => 'جديدة',
                    ]);

                    $user->notify(new TaskAssignedNotification($task, $creator));
                    $createdTasks[] = $task;
                }
            }
        }

        return $createdTasks;
    }

    /**
     * Create an internal task directly
     */
    public function createTask(array $data, ?User $creator = null): InternalTask
    {
        $creator = $creator ?? auth()->user();

        $task = InternalTask::create([
            'memo_id' => $data['memo_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'created_by' => $creator->id,
            'assigned_to' => $data['assigned_to'],
            'due_date' => $data['due_date'] ?? null,
            'priority' => $data['priority'] ?? 'عادية',
            'status' => 'جديدة',
        ]);

        if ($task->assignee && $task->assignee->id !== $creator->id) {
            $task->assignee->notify(new TaskAssignedNotification($task, $creator));
        }

        return $task;
    }

    /**
     * Add a comment / feedback loop to a task
     */
    public function addTaskComment(
        InternalTask $task,
        string $commentText,
        ?string $statusChange = null,
        ?UploadedFile $attachment = null,
        ?User $user = null
    ): TaskComment {
        $user = $user ?? auth()->user();

        return DB::transaction(function () use ($task, $commentText, $statusChange, $attachment, $user) {
            $attachmentPath = null;
            $attachmentName = null;

            if ($attachment) {
                $attachmentPath = $attachment->store('task_attachments', 'public');
                $attachmentName = $attachment->getClientOriginalName();
            }

            $comment = TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'comment' => $commentText,
                'status_change_to' => $statusChange,
                'attachment_path' => $attachmentPath,
                'attachment_name' => $attachmentName,
            ]);

            // Update status if changed
            if ($statusChange && in_array($statusChange, ['جديدة', 'قيد التنفيذ', 'بانتظار الرد', 'تم الإنجاز', 'معلقة'])) {
                $updateData = ['status' => $statusChange];
                if ($statusChange === 'تم الإنجاز') {
                    $updateData['completed_at'] = now();
                }
                $task->update($updateData);
            }

            // Notify participants (creator if commenter is assignee, or assignee if commenter is creator)
            $recipient = ($user->id === $task->created_by) ? $task->assignee : $task->creator;
            if ($recipient && $recipient->id !== $user->id) {
                $recipient->notify(new TaskFeedbackNotification($task, $comment, $user));
            }

            return $comment;
        });
    }
}
