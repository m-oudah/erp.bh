<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'job_title',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function department()
    {
        return $this->belongsTo(\App\Models\Correspondence\Department::class, 'department_id');
    }

    public function createdMemos()
    {
        return $this->hasMany(\App\Models\Correspondence\InternalMemo::class, 'created_by');
    }

    public function assignedMemos()
    {
        return $this->hasMany(\App\Models\Correspondence\InternalMemo::class, 'current_assignee_id');
    }

    public function receivedMemos()
    {
        return $this->belongsToMany(\App\Models\Correspondence\InternalMemo::class, 'memo_recipients', 'user_id', 'memo_id')
            ->withPivot(['department_id', 'status', 'read_at'])
            ->withTimestamps();
    }

    public function createdTasks()
    {
        return $this->hasMany(\App\Models\Correspondence\InternalTask::class, 'created_by');
    }

    public function assignedTasks()
    {
        return $this->hasMany(\App\Models\Correspondence\InternalTask::class, 'assigned_to');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
