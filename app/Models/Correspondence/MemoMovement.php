<?php

namespace App\Models\Correspondence;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoMovement extends Model
{
    use HasFactory;

    protected $table = 'memo_movements';

    protected $fillable = [
        'memo_id',
        'from_user_id',
        'to_user_id',
        'to_department_id',
        'action_type',
        'directive_text',
    ];

    public function memo()
    {
        return $this->belongsTo(InternalMemo::class, 'memo_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function toDepartment()
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function attachments()
    {
        return $this->hasMany(MemoAttachment::class, 'movement_id');
    }
}
