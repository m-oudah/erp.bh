<?php

namespace App\Models\Correspondence;

use App\Models\ArchiveFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InternalMemo extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'internal_memos';

    protected $fillable = [
        'memo_number',
        'subject',
        'content',
        'priority',
        'status',
        'created_by',
        'current_assignee_id',
        'target_department_id',
        'linked_archive_file_id',
        'auto_archive_file_id',
        'due_date',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'closed_at' => 'datetime',
    ];

    public static function generateMemoNumber(): string
    {
        $year = date('Y');
        $lastMemo = static::whereYear('created_at', $year)
            ->where('memo_number', 'LIKE', "MEMO-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastMemo) {
            $parts = explode('-', $lastMemo->memo_number);
            $lastSeq = isset($parts[2]) ? (int)$parts[2] : 0;
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        return sprintf("MEMO-%s-%04d", $year, $nextSeq);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'current_assignee_id');
    }

    public function targetDepartment()
    {
        return $this->belongsTo(Department::class, 'target_department_id');
    }

    public function linkedArchiveFile()
    {
        return $this->belongsTo(ArchiveFile::class, 'linked_archive_file_id');
    }

    public function autoArchiveFile()
    {
        return $this->belongsTo(ArchiveFile::class, 'auto_archive_file_id');
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function movements()
    {
        return $this->hasMany(MemoMovement::class, 'memo_id')->orderBy('created_at', 'asc');
    }

    public function attachments()
    {
        return $this->hasMany(MemoAttachment::class, 'memo_id')->latest();
    }

    public function tasks()
    {
        return $this->hasMany(InternalTask::class, 'memo_id')->latest();
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'عاجلة' => 'bg-red-100 text-red-800 border border-red-300',
            'سري / عاجل جداً' => 'bg-rose-600 text-white font-bold shadow-sm',
            'متوسطة' => 'bg-amber-100 text-amber-800 border border-amber-300',
            default => 'bg-slate-100 text-slate-700 border border-slate-300',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'مسودة' => 'bg-slate-100 text-slate-600',
            'قيد المعالجة' => 'bg-teal-50 text-teal-700 border border-teal-300',
            'بانتظار رد' => 'bg-amber-50 text-amber-800 border border-amber-300',
            'تم الإنجاز' => 'bg-emerald-50 text-emerald-700 border border-emerald-300',
            'ملغاة' => 'bg-rose-50 text-rose-700 border border-rose-300',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
