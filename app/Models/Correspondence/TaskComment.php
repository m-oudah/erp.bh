<?php

namespace App\Models\Correspondence;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    protected $table = 'task_comments';

    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
        'status_change_to',
        'attachment_path',
        'attachment_name',
    ];

    public function task()
    {
        return $this->belongsTo(InternalTask::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
