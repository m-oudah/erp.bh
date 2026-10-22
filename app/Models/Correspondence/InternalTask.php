<?php

namespace App\Models\Correspondence;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InternalTask extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'internal_tasks';

    protected $fillable = [
        'memo_id',
        'title',
        'description',
        'created_by',
        'assigned_to',
        'due_date',
        'priority',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function memo()
    {
        return $this->belongsTo(InternalMemo::class, 'memo_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id')->orderBy('created_at', 'asc');
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'عاجلة' => 'bg-red-100 text-red-800 border border-red-300',
            'متوسطة' => 'bg-amber-100 text-amber-800 border border-amber-300',
            default => 'bg-slate-100 text-slate-700 border border-slate-300',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'جديدة' => 'bg-sky-50 text-sky-700 border border-sky-300',
            'قيد التنفيذ' => 'bg-teal-50 text-teal-700 border border-teal-300',
            'بانتظار الرد' => 'bg-amber-50 text-amber-800 border border-amber-300',
            'تم الإنجاز' => 'bg-emerald-50 text-emerald-700 border border-emerald-300',
            'معلقة' => 'bg-slate-100 text-slate-600 border border-slate-300',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'تم الإنجاز' || !$this->due_date) {
            return false;
        }
        return $this->due_date->isPast();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
