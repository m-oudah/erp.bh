<?php

namespace App\Notifications;

use App\Models\Correspondence\InternalTask;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public InternalTask $task,
        public User $fromUser
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'task_assigned',
            'title' => 'تم تكليفك بمهمة داخلية جديدة',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'priority' => $this->task->priority,
            'due_date' => $this->task->due_date?->format('Y-m-d'),
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'memo_id' => $this->task->memo_id,
            'action_url' => route('correspondence.tasks.show', $this->task->id),
            'icon' => 'clipboard-check',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
