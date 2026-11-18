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
        'avatar',
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

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        return null;
    }

    /**
     * Check if user has permission to access a specific ERP module.
     */
    public function canAccessModule(string $module): bool
    {
        // System admin has full access to all modules
        if ($this->hasRole('مدير النظام') || $this->email === 'admin@dev.com') {
            return true;
        }

        return match (strtolower($module)) {
            'archive' => $this->hasAnyPermission([
                'Module-Archive',
                'archive.files.create',
                'archive.files.edit',
                'archive.files.delete',
                'archive.documents.create',
                'archive.documents.delete',
            ]),
            'buildings' => $this->hasAnyPermission([
                'Module-Buildings',
                'Buildings',
                'Read-Buildings',
                'Show-Building',
                'Create-Building',
                'Update-Building',
                'Delete-Building',
                'LicenseForms',
                'Read-LicenseForms',
                'Show-LicenseForm',
                'Create-LicenseForm',
                'Update-LicenseForm',
                'Delete-LicenseForm',
                'Floors',
                'Read-Floors',
                'Create-Floor',
                'Update-Floor',
                'Delete-Floor',
                'Units',
                'Read-Units',
                'Create-Unit',
                'Update-Unit',
                'Delete-Unit',
                'Zones',
                'Read-Zones',
                'Streets',
                'Read-Streets',
                'BuildingTypes',
                'Read-BuildingTypes',
                'Opinions',
                'Show-Opinions',
                'FinalConfirmation',
                'LicensePrint',
                'UrbanPlanning-Opinion',
                'Gis-Opinion',
                'SurveyDepartment-Opinion',
                'WaterDepartment-Opinion',
                'SewerDepartment-Opinion',
                'CollectionDepartment-Opinion',
                'Legal-Opinions',
            ]),
            'crafts' => $this->hasAnyPermission([
                'Module-Crafts',
                'Crafts',
                'Read-Crafts',
                'Create-Craft',
                'Update-Craft',
                'Delete-Craft',
            ]),
            'customers' => $this->hasAnyPermission([
                'Module-Customers',
                'Subscriptions',
                'Read-Subscriptions',
                'Create-Subscription',
                'Update-Subscription',
                'Delete-Subscription',
                'Clients',
                'Read-Clients',
                'Create-Client',
                'Update-Client',
                'Delete-Client',
                'Read-Citizens',
                'Create-Citizen',
                'Update-Citizen',
                'Delete-Citizen',
                'Owners',
                'Read-Owners',
                'Create-Owner',
                'Update-Owner',
                'Delete-Owner',
            ]),
            'transactions' => $this->hasAnyPermission([
                'Module-Transactions',
                'Transactions',
                'Read-Transactions',
            ]),
            'correspondence' => $this->hasAnyPermission([
                'Module-Correspondence',
                'Correspondence',
                'Read-Correspondence',
            ]),
            'hr' => $this->hasAnyPermission([
                'Module-HR',
                'HR',
                'Employees',
                'Read-Employees',
                'Approve-Payroll',
                'Print-Payroll',
                'Manage-Payroll',
            ]),
            'settings' => $this->hasAnyPermission([
                'Module-Settings',
                'Access-Settings',
                'Settings-Backup',
                'Users',
                'Read-Users',
                'Create-User',
                'Update-User',
                'Delete-User',
                'Roles',
                'Read-Roles',
                'Create-Role',
                'Update-Role',
                'Delete-Role',
            ]),
            default => false,
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
