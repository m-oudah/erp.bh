<?php

namespace App\Notifications;

use App\Models\Correspondence\InternalTask;
use App\Models\Correspondence\TaskComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskFeedbackNotification extends Notification
{
    use Queueable;

    public function __construct(
        public InternalTask $task,
        public TaskComment $comment,
        public User $fromUser
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'task_feedback',
            'title' => 'تحديث / تعليق جديد على المهمة: ' . $this->task->title,
            'task_id' => $this->task->id,
            'comment_id' => $this->comment->id,
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'status_changed_to' => $this->comment->status_change_to,
            'action_url' => route('correspondence.tasks.show', $this->task->id),
            'icon' => 'chat',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
