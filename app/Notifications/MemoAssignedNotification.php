<?php

namespace App\Notifications;

use App\Models\Correspondence\InternalMemo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MemoAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public InternalMemo $memo,
        public User $fromUser,
        public ?string $directiveText = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'memo_assigned',
            'title' => 'مراسلة داخلية جديدة موجهة إليك',
            'memo_id' => $this->memo->id,
            'memo_number' => $this->memo->memo_number,
            'subject' => $this->memo->subject,
            'priority' => $this->memo->priority,
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'directive' => $this->directiveText,
            'action_url' => route('correspondence.memos.show', $this->memo->id),
            'icon' => 'envelope',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
